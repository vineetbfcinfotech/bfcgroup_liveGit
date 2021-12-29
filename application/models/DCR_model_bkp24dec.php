<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DCR_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function do_kanban_query($status, $search = '', $page = 1, $sort = [], $count = false)
    {
        $limit = get_option('leads_kanban_limit');
        $default_leads_kanban_sort = get_option('default_leads_kanban_sort');
        $default_leads_kanban_sort_type = get_option('default_leads_kanban_sort_type');
        $has_permission_view = has_permission('leads', '', 'view');

        $this->db->select('tblleads.name as lead_name,tblleadssources.name as source_name,tblleads.id as id,tblleads.assigned,tblleads.email,tblleads.phonenumber,tblleads.company,tblleads.dateadded,tblleads.status,tblleads.lastcontact,(SELECT COUNT(*) FROM tblclients WHERE leadid=tblleads.id) as is_lead_client, (SELECT COUNT(id) FROM tblfiles WHERE rel_id=tblleads.id AND rel_type="lead") as total_files, (SELECT COUNT(id) FROM tblnotes WHERE rel_id=tblleads.id AND rel_type="lead") as total_notes,(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM tbltags_in JOIN tbltags ON tbltags_in.tag_id = tbltags.id WHERE rel_id = tblleads.id and rel_type="lead" ORDER by tag_order ASC) as tags');
        $this->db->from('tblleads');
        $this->db->join('tblleadssources', 'tblleadssources.id=tblleads.source', 'left');
        $this->db->join('tblstaff', 'tblstaff.staffid=tblleads.assigned', 'left');
        $this->db->where('status', $status);
        if (!$has_permission_view) {
            $this->db->where('(assigned = ' . get_staff_user_id() . ' OR addedfrom=' . get_staff_user_id() . ' OR is_public=1)');
        }
        if ($search != '') {
            if (!_startsWith($search, '#')) {
                $this->db->where('(tblleads.name LIKE "%' . $search . '%" OR tblleadssources.name LIKE "%' . $search . '%" OR tblleads.email LIKE "%' . $search . '%" OR tblleads.phonenumber LIKE "%' . $search . '%" OR tblleads.company LIKE "%' . $search . '%" OR CONCAT(tblstaff.firstname, \' \', tblstaff.lastname) LIKE "%' . $search . '%")');
            } else {
                $this->db->where('tblleads.id IN
(SELECT rel_id FROM tbltags_in WHERE tag_id IN
(SELECT id FROM tbltags WHERE name="' . strafter($search, '#') . '")
AND tbltags_in.rel_type=\'lead\' GROUP BY rel_id HAVING COUNT(tag_id) = 1)
');
            }
        }

        if (isset($sort['sort_by']) && $sort['sort_by'] && isset($sort['sort']) && $sort['sort']) {
            $this->db->order_by($sort['sort_by'], $sort['sort']);
        } else {
            $this->db->order_by($default_leads_kanban_sort, $default_leads_kanban_sort_type);
        }

        if ($count == false) {
            if ($page > 1) {
                $page--;
                $position = ($page * $limit);
                $this->db->limit($limit, $position);
            } else {
                $this->db->limit($limit);
            }
        }

        if ($count == false) {
            return $this->db->get()->result_array();
        }

        return $this->db->count_all_results();
    }

    /**
     * Add new lead to database
     * @param mixed $data lead data
     * @return mixed false || leadid
     */
    public function add($data)
    {
        if (isset($data['custom_contact_date']) || isset($data['custom_contact_date'])) {
            if (isset($data['contacted_today'])) {
                $data['lastcontact'] = date('Y-m-d H:i:s');
                unset($data['contacted_today']);
            } else {
                $data['lastcontact'] = to_sql_date($data['custom_contact_date'], true);
            }
        }

        if (isset($data['is_public']) && ($data['is_public'] == 1 || $data['is_public'] === 'on')) {
            $data['is_public'] = 1;
        } else {
            $data['is_public'] = 0;
        }

        if (!isset($data['country']) || isset($data['country']) && $data['country'] == '') {
            $data['country'] = 0;
        }

        if (isset($data['custom_contact_date'])) {
            unset($data['custom_contact_date']);
        }

        $data['description'] = nl2br($data['description']);
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['addedfrom'] = get_staff_user_id();

        $data = do_action('before_lead_added', $data);

        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $data['address'] = trim($data['address']);
        $data['address'] = nl2br($data['address']);

        $data['email'] = trim($data['email']);
        $this->db->insert('tblleads', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Lead Added [ID: ' . $insert_id . ']');
            $this->log_lead_activity($insert_id, 'not_lead_activity_created');

            handle_tags_save($tags, $insert_id, 'lead');

            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields);
            }
            $this->lead_assigned_member_notification($insert_id, $data['assigned']);
            do_action('lead_created', $insert_id);

            return $insert_id;
        }

        return false;
    }

    /**
     * Add lead activity from staff
     * @param mixed $id lead id
     * @param string $description activity description
     * @return
     */
    public function log_lead_activity($id, $description, $integration = false, $additional_data = '')
    {
        $log = [
            'date' => date('Y-m-d H:i:s'),
            'description' => $description,
            'leadid' => $id,
            'staffid' => get_staff_user_id(),
            'additional_data' => $additional_data,
            'full_name' => get_staff_full_name(get_staff_user_id()),
        ];
        if ($integration == true) {
            $log['staffid'] = 0;
            $log['full_name'] = '[CRON]';
        }

        $this->db->insert('tblleadactivitylog', $log);

        return $this->db->insert_id();
    }

    public function lead_assigned_member_notification($lead_id, $assigned, $integration = false)
    {
        if ((!empty($assigned) && $assigned != 0)) {
            if ($integration == false) {
                if ($assigned == get_staff_user_id()) {
                    return false;
                }
            }

            $name = $this->db->select('name')->from('tblleads')->where('id', $lead_id)->get()->row()->name;

            $notification_data = [
                'description' => ($integration == false) ? 'not_assigned_lead_to_you' : 'not_lead_assigned_from_form',
                'touserid' => $assigned,
                'link' => '#leadid=' . $lead_id,
                'additional_data' => ($integration == false ? serialize([
                    $name,
                ]) : serialize([])),
            ];

            if ($integration != false) {
                $notification_data['fromcompany'] = 1;
            }

            if (add_notification($notification_data)) {
                pusher_trigger_notification([$assigned]);
            }

            $this->db->where('staffid', $assigned);
            $email = $this->db->get('tblstaff')->row()->email;

            $this->load->model('emails_model');
            $merge_fields = [];
            $merge_fields = array_merge($merge_fields, get_lead_merge_fields($lead_id));
            $this->emails_model->send_email_template('new-lead-assigned', $email, $merge_fields);

            $this->db->where('id', $lead_id);
            $this->db->update('tblleads', [
                'dateassigned' => date('Y-m-d'),
            ]);

            $not_additional_data = [
                get_staff_full_name(),
                '<a href="' . admin_url('profile/' . $assigned) . '" target="_blank">' . get_staff_full_name($assigned) . '</a>',
            ];

            if ($integration == true) {
                unset($not_additional_data[0]);
                array_values(($not_additional_data));
            }

            $not_additional_data = serialize($not_additional_data);

            $not_desc = ($integration == false ? 'not_lead_activity_assigned_to' : 'not_lead_activity_assigned_from_form');
            $this->log_lead_activity($lead_id, $not_desc, $integration, $not_additional_data);
        }
    }

    public function lead_assigned_reporting_manager_notification($lead_id)
    {
        $lead_details = $this->db->select('id,name,assigned')->from('tblleads')->where('id', $lead_id)->get()->row();
        if (is_object($lead_details)) {
            $res = $this->db->query("SELECT a.staffid as assignedid, b.staffid as rmid, CONCAT(a.firstname,' ',a.lastname) AS assignednm, CONCAT(b.firstname,' ',b.lastname) AS rmnm FROM tblstaff as a INNER JOIN tblstaff as b ON b.staffid=a.reporting_manager");
            if ($res->num_rows()) {
                $user = $res->row();
                $notification_data = [
                    'description' => 'not_assigned_lead_to_tel_to_rm',
                    'touserid' => $user->rmid,
                    'link' => '#leadid=' . $lead_id,
                    'additional_data' => serialize([]),
                ];
                if (add_notification($notification_data)) {
                    pusher_trigger_notification([$user->rmid]);
                }
            }
        }
    }


    /**
     * Update lead
     * @param array $data lead data
     * @param mixed $id leadid
     * @return boolean
     */
    public function update($data, $id)
    {
        $current_lead_data = $this->get($id);
        $current_status = $this->get_status($current_lead_data->status);
        if ($current_status) {
            $current_status_id = $current_status->id;
            $current_status = $current_status->name;
        } else {
            if ($current_lead_data->junk == 1) {
                $current_status = _l('lead_junk');
            } elseif ($current_lead_data->lost == 1) {
                $current_status = _l('lead_lost');
            } else {
                $current_status = '';
            }
            $current_status_id = 0;
        }

        $affectedRows = 0;
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }
        if (!defined('API')) {
            if (isset($data['is_public'])) {
                $data['is_public'] = 1;
            } else {
                $data['is_public'] = 0;
            }

            if (!isset($data['country']) || isset($data['country']) && $data['country'] == '') {
                $data['country'] = 0;
            }

            if (isset($data['description'])) {
                $data['description'] = nl2br($data['description']);
            }
        }

        if (isset($data['lastcontact']) && $data['lastcontact'] == '' || isset($data['lastcontact']) && $data['lastcontact'] == null) {
            $data['lastcontact'] = null;
        } elseif (isset($data['lastcontact'])) {
            $data['lastcontact'] = to_sql_date($data['lastcontact'], true);
        }

        if (isset($data['tags'])) {
            if (handle_tags_save($data['tags'], $id, 'lead')) {
                $affectedRows++;
            }
            unset($data['tags']);
        }

        if (isset($data['remove_attachments'])) {
            foreach ($data['remove_attachments'] as $key => $val) {
                $attachment = $this->get_lead_attachments($id, $key);
                if ($attachment) {
                    $this->delete_lead_attachment($attachment->id);
                }
            }
            unset($data['remove_attachments']);
        }

        $data['address'] = trim($data['address']);
        $data['address'] = nl2br($data['address']);

        $data['email'] = trim($data['email']);

        $this->db->where('id', $id);
            $this->db->update('tblleads', $data);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if (isset($data['status']) && $current_status_id != $data['status']) {
                $this->db->where('id', $id);
                $this->db->update('tblleads', [
                    'last_status_change' => date('Y-m-d H:i:s'),
                ]);
                $new_status_name = $this->get_status($data['status'])->name;
                $this->log_lead_activity($id, 'not_lead_activity_status_updated', false, serialize([
                    get_staff_full_name(),
                    $current_status,
                    $new_status_name,
                ]));

                do_action('lead_status_changed', ['lead_id' => $id, 'old_status' => $current_status_id, 'new_status' => $data['status']]);
            }

            if (($current_lead_data->junk == 1 || $current_lead_data->lost == 1) && $data['status'] != 0) {
                $this->db->where('id', $id);
                $this->db->update('tblleads', [
                    'junk' => 0,
                    'lost' => 0,
                ]);
            }

            if (isset($data['assigned'])) {
                if ($current_lead_data->assigned != $data['assigned'] && (!empty($data['assigned']) && $data['assigned'] != 0)) {
                    $this->lead_assigned_member_notification($id, $data['assigned']);
                }
            }
            logActivity('Lead Updated [ID: ' . $id . ']');

            return true;
        }
        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get lead
     * @param string $id Optional - leadid
     * @return mixed
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('*,tblleads.name, tblleads.id,tblleadsstatus.name as status_name,tblleadssources.name as source_name');
        $this->db->join('tblleadsstatus', 'tblleadsstatus.id=tblleads.status', 'left');
        $this->db->join('tblleadssources', 'tblleadssources.id=tblleads.source', 'left');

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('tblleads.id', $id);
            $lead = $this->db->get('tblleads')->row();
            if ($lead) {
                if ($lead->from_form_id != 0) {
                    $lead->form_data = $this->get_form([
                        'id' => $lead->from_form_id,
                    ]);
                }
                $lead->attachments = $this->get_lead_attachments($id);
                $lead->public_url = leads_public_url($id);
            }

            return $lead;
        }

        return $this->db->get('tblleads')->result_array();
    }

    public function get_form($where)
    {
        $this->db->where($where);

        return $this->db->get('tblwebtolead')->row();
    }

    /**
     * Get lead attachments
     * @param mixed $id lead id
     * @return array
     * @since Version 1.0.4
     */
    public function get_lead_attachments($id = '', $attachment_id = '', $where = [])
    {
        $this->db->where($where);
        $idIsHash = !is_numeric($attachment_id) && strlen($attachment_id) == 32;
        if (is_numeric($attachment_id) || $idIsHash) {
            $this->db->where($idIsHash ? 'attachment_key' : 'id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'lead');
        $this->db->order_by('dateadded', 'DESC');

        return $this->db->get('tblfiles')->result_array();
    }

    /**
     * Get lead statuses
     * @param mixed $id status id
     * @return mixed      object if id passed else array
     */
    public function get_status($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblleadsstatus')->row();
        }

        $statuses = $this->object_cache->get('leads-all-statuses');

        if (!$statuses) {
            $this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get('tblleadsstatus')->result_array();
            $this->object_cache->add('leads-all-statuses', $statuses);
        }

        return $statuses;

    }
    public function get_status_dcr($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblleadsstatus')->row();
        }

        $statuses = $this->object_cache->get('leads-all-statuses');

        if (!$statuses) {
            $this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get('tblleadsstatus')->result_array();
            $this->object_cache->add('leads-all-statuses', $statuses);
        }

        return $statuses;

    }
    
    public function get_status_dwr($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tbldwrstatus')->row();
        }


        if (!$statuses) {
            $this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get('tbldwrstatus')->result_array();
        }

        return $statuses;

    }
    
    
    public function get_business_converted($id = '', $where = [])
    {
        
            $this->db->select('meet_lead_id');
            $this->db->where('transaction_date !=', null);
            $this->db->where('meet_lead_id !=', "0");
            //$this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get('tblbusiness')->result_array();
            
        

        return $statuses;

    }

    public function get_meetingstatus($return = "result")
    {
        $this->db->select('meeting_cat');
        $this->db->group_by('meeting_cat');
        return $this->_checkRecords($this->db->get('tblmeeting_scheduled'), $return);

    }
    
    
    public function get_dwrstatus($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tbldwrstatus')->row();
        }

      // $statuses = $this->object_cache->get('dwr-all-statuses');

        if (!$statuses) {
            $this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get('tbldwrstatus')->result_array();
          //  $this->object_cache->add('dwr-all-statuses', $statuses);
        }

        return $statuses;

    }
    
    public function get_dwrcallingstatus($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('dwrcallingstatus')->row();
        }

       //$statuses = $this->object_cache->get('dwr-all-statuses');

        if (!$statuses) {
            $this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get('dwrcallingstatus')->result_array();
            //$this->object_cache->add('dwr-all-statuses', $statuses);
        }

        return $statuses;

    }

    /**
     * Delete lead attachment
     * @param mixed $id attachment id
     * @return boolean
     */
    public function delete_lead_attachment($id)
    {
        $attachment = $this->get_lead_attachments('', $id);
        $deleted = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('lead') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Lead Attachment Deleted [ID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('lead') . $attachment->rel_id)) {
// Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('lead') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
// okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('lead') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Delete lead from database and all connections
     * @param mixed $id leadid
     * @return boolean
     */

    public function deleteremarkid($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblleadremark');
    }
    
    public function deletebusinessreport($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblbusiness');
    }

    public function deletemeetingremark($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblmeeting_remark');
    }

    public function delete($id)
    {
        $affectedRows = 0;

        do_action('before_lead_deleted', $id);

        $lead = $this->get($id);

        $this->db->where('id', $id);
        $this->db->delete('tblleads');
        if ($this->db->affected_rows() > 0) {
            logActivity('Lead Deleted [Deleted by: ' . get_staff_full_name() . ', ID: ' . $id . ']');

            $attachments = $this->get_lead_attachments($id);
            foreach ($attachments as $attachment) {
                $this->delete_lead_attachment($attachment['id']);
            }

// Delete the custom field values
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'leads');
            $this->db->delete('tblcustomfieldsvalues');

            $this->db->where('leadid', $id);
            $this->db->delete('tblleadactivitylog');

            $this->db->where('leadid', $id);
            $this->db->delete('tblleadsemailintegrationemails');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'lead');
            $this->db->delete('tblnotes');

            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $this->db->delete('tblreminders');

            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $this->db->delete('tbltags_in');

            $this->load->model('proposals_model');
            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'lead');
            $proposals = $this->db->get('tblproposals')->result_array();

            foreach ($proposals as $proposal) {
                $this->proposals_model->delete($proposal['id']);
            }

// Get related tasks
            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $tasks = $this->db->get('tblstafftasks')->result_array();
            foreach ($tasks as $task) {
                $this->tasks_model->delete_task($task['id']);
            }

            if (is_gdpr()) {
                $this->db->where('(description LIKE "%' . $lead->email . '%" OR description LIKE "%' . $lead->name . '%" OR description LIKE "%' . $lead->phonenumber . '%")');
                $this->db->delete('tblactivitylog');
            }

            $affectedRows++;
        }
        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Mark lead as lost
     * @param mixed $id lead id
     * @return boolean
     */
    public function mark_as_lost($id)
    {
        $this->db->select('status');
        $this->db->from('tblleads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->status;

        $this->db->where('id', $id);
        $this->db->update('tblleads', [
            'lost' => 1,
            'status' => 0,
            'last_status_change' => date('Y-m-d H:i:s'),
            'last_lead_status' => $last_lead_status,
        ]);
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_marked_lost');
            logActivity('Lead Marked as Lost [ID: ' . $id . ']');
            do_action('lead_marked_as_lost', $id);

            return true;
        }

        return false;
    }

    /**
     * Unmark lead as lost
     * @param mixed $id leadid
     * @return boolean
     */
    public function unmark_as_lost($id)
    {
        $this->db->select('last_lead_status');
        $this->db->from('tblleads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->last_lead_status;

        $this->db->where('id', $id);
        $this->db->update('tblleads', [
            'lost' => 0,
            'status' => $last_lead_status,
        ]);
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_unmarked_lost');
            logActivity('Lead Unmarked as Lost [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

// Sources

    /**
     * Mark lead as junk
     * @param mixed $id lead id
     * @return boolean
     */
    public function mark_as_junk($id)
    {
        $this->db->select('status');
        $this->db->from('tblleads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->status;

        $this->db->where('id', $id);
        $this->db->update('tblleads', [
            'junk' => 1,
            'status' => 0,
            'last_status_change' => date('Y-m-d H:i:s'),
            'last_lead_status' => $last_lead_status,
        ]);
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_marked_junk');
            logActivity('Lead Marked as Junk [ID: ' . $id . ']');
            do_action('lead_marked_as_junk', $id);

            return true;
        }

        return false;
    }

    /**
     * Unmark lead as junk
     * @param mixed $id leadid
     * @return boolean
     */
    public function unmark_as_junk($id)
    {
        $this->db->select('last_lead_status');
        $this->db->from('tblleads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->last_lead_status;

        $this->db->where('id', $id);
        $this->db->update('tblleads', [
            'junk' => 0,
            'status' => $last_lead_status,
        ]);
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_unmarked_junk');
            logActivity('Lead Unmarked as Junk [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    public function add_attachment_to_database($lead_id, $attachment, $external = false, $form_activity = false)
    {
        $this->misc_model->add_attachment_to_database($lead_id, 'lead', $attachment, $external);

        if ($form_activity == false) {
            $this->leads_model->log_lead_activity($lead_id, 'not_lead_activity_added_attachment');
        } else {
            $this->leads_model->log_lead_activity($lead_id, 'not_lead_activity_log_attachment', true, serialize([
                $form_activity,
            ]));
        }

// No notification when attachment is imported from web to lead form
        if ($form_activity == false) {
            $lead = $this->get($lead_id);
            $not_user_ids = [];
            if ($lead->addedfrom != get_staff_user_id()) {
                array_push($not_user_ids, $lead->addedfrom);
            }
            if ($lead->assigned != get_staff_user_id() && $lead->assigned != 0) {
                array_push($not_user_ids, $lead->assigned);
            }
            $notifiedUsers = [];
            foreach ($not_user_ids as $uid) {
                $notified = add_notification([
                    'description' => 'not_lead_added_attachment',
                    'touserid' => $uid,
                    'link' => '#leadid=' . $lead_id,
                    'additional_data' => serialize([
                        $lead->name,
                    ]),
                ]);
                if ($notified) {
                    array_push($notifiedUsers, $uid);
                }
            }
            pusher_trigger_notification($notifiedUsers);
        }
    }

    /**
     * Add new lead source
     * @param mixed $data source data
     */
    public function add_source($data)
    {
        $this->db->insert('tblleadssources', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Leads Source Added [SourceID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
        }

        return $insert_id;
    }

// Statuses

    /**
     * Update lead source
     * @param mixed $data source data
     * @param mixed $id source id
     * @return boolean
     */
    public function update_source($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblleadssources', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Leads Source Updated [SourceID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete lead source from database
     * @param mixed $id source id
     * @return mixed
     */
    public function delete_source($id)
    {
        $current = $this->get_source($id);
// Check if is already using in table
        if (is_reference_in_table('source', 'tblleads', $id) || is_reference_in_table('lead_source', 'tblleadsintegration', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete('tblleadssources');
        if ($this->db->affected_rows() > 0) {
            if (get_option('leads_default_source') == $id) {
                update_option('leads_default_source', '');
            }
            logActivity('Leads Source Deleted [SourceID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Get leads sources
     * @param mixed $id Optional - Source ID
     * @return mixed object if id passed else array
     */
    public function get_source($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblleadssources')->row();
        }

        return $this->db->get('tblleadssources')->result_array();
    }

    /**
     * Add new lead status
     * @param array $data lead status data
     */
    public function add_status($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = do_action('default_lead_status_color', '#757575');
        }

        if (!isset($data['statusorder'])) {
            $data['statusorder'] = total_rows('tblleadsstatus') + 1;
        }

        $this->db->insert('tblleadsstatus', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Leads Status Added [StatusID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    public function update_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblleadsstatus', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Leads Status Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /* Ajax */

    /**
     * Delete lead status from database
     * @param mixed $id status id
     * @return boolean
     */
    public function delete_status($id)
    {
        $current = $this->get_status($id);
// Check if is already using in table
        if (is_reference_in_table('status', 'tblleads', $id) || is_reference_in_table('lead_status', 'tblleadsintegration', $id)) {
            return [
                'referenced' => true,
            ];
        }

        $this->db->where('id', $id);
        $this->db->delete('tblleadsstatus');
        if ($this->db->affected_rows() > 0) {
            if (get_option('leads_default_status') == $id) {
                update_option('leads_default_status', '');
            }
            logActivity('Leads Status Deleted [StatusID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Update canban lead status when drag and drop
     * @param array $data lead data
     * @return boolean
     */
    public function update_lead_status($data)
    {
        $this->db->select('status');
        $this->db->where('id', $data['leadid']);
        $_old = $this->db->get('tblleads')->row();

        $old_status = '';

        if ($_old) {
            $old_status = $this->get_status($_old->status);
            if ($old_status) {
                $old_status = $old_status->name;
            }
        }

        $affectedRows = 0;
        $current_status = $this->get_status($data['status'])->name;

        $this->db->where('id', $data['leadid']);
        $this->db->update('tblleads', [
            'status' => $data['status'],
        ]);

        $_log_message = '';

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if ($current_status != $old_status && $old_status != '') {
                $_log_message = 'not_lead_activity_status_updated';
                $additional_data = serialize([
                    get_staff_full_name(),
                    $old_status,
                    $current_status,
                ]);

                do_action('lead_status_changed', ['lead_id' => $data['leadid'], 'old_status' => $old_status, 'new_status' => $current_status]);
            }
            $this->db->where('id', $data['leadid']);
            $this->db->update('tblleads', [
                'last_status_change' => date('Y-m-d H:i:s'),
            ]);
        }
        if (isset($data['order'])) {
            foreach ($data['order'] as $order_data) {
                $this->db->where('id', $order_data[0]);
                $this->db->update('tblleads', [
                    'leadorder' => $order_data[1],
                ]);
            }
        }
        if ($affectedRows > 0) {
            if ($_log_message == '') {
                return true;
            }
            $this->log_lead_activity($data['leadid'], $_log_message, false, $additional_data);

            return true;
        }

        return false;
    }

    /**
     * All lead activity by staff
     * @param mixed $id lead id
     * @return array
     */
    public function get_lead_activity_log($id)
    {
        $sorting = do_action('lead_activity_log_default_sort', 'ASC');

        $this->db->where('leadid', $id);
        $this->db->order_by('date', $sorting);

        return $this->db->get('tblleadactivitylog')->result_array();
    }

    public function staff_can_access_lead($id, $staff_id = '')
    {
        $staff_id = $staff_id == '' ? get_staff_user_id() : $staff_id;

        if (has_permission('leads', $staff_id, 'view')) {
            return true;
        }

        if (total_rows('tblleads', 'id="' . $id . '" AND (assigned=' . $staff_id . ' OR is_public=1 OR addedfrom=' . $staff_id . ')') > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get email integration config
     * @return object
     */
    public function get_email_integration()
    {
        $this->db->where('id', 1);

        return $this->db->get('tblleadsintegration')->row();
    }

    /**
     * Get lead imported email activity
     * @param mixed $id leadid
     * @return array
     */
    public function get_mail_activity($id)
    {
        $this->db->where('leadid', $id);
        $this->db->order_by('dateadded', 'asc');

        return $this->db->get('tblleadsemailintegrationemails')->result_array();
    }

    /**
     * Update email integration config
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_email_integration($data)
    {
        $this->db->where('id', 1);
        $original_settings = $this->db->get('tblleadsintegration')->row();

        $data['create_task_if_customer'] = isset($data['create_task_if_customer']) ? 1 : 0;
        $data['active'] = isset($data['active']) ? 1 : 0;
        $data['delete_after_import'] = isset($data['delete_after_import']) ? 1 : 0;
        $data['notify_lead_imported'] = isset($data['notify_lead_imported']) ? 1 : 0;
        $data['only_loop_on_unseen_emails'] = isset($data['only_loop_on_unseen_emails']) ? 1 : 0;
        $data['notify_lead_contact_more_times'] = isset($data['notify_lead_contact_more_times']) ? 1 : 0;
        $data['mark_public'] = isset($data['mark_public']) ? 1 : 0;
        $data['responsible'] = !isset($data['responsible']) ? 0 : $data['responsible'];

        if ($data['notify_lead_contact_more_times'] != 0 || $data['notify_lead_imported'] != 0) {
            if (isset($data['notify_type']) && $data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids'] = serialize([]);
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

// Check if not empty $data['password']
// Get original
// Decrypt original
// Compare with $data['password']
// If equal unset
// If not encrypt and save
        if (!empty($data['password'])) {
            $or_decrypted = $this->encryption->decrypt($original_settings->password);
            if ($or_decrypted == $data['password']) {
                unset($data['password']);
            } else {
                $data['password'] = $this->encryption->encrypt($data['password']);
            }
        }

        $this->db->where('id', 1);
        $this->db->update('tblleadsintegration', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function change_status_color($data)
    {
        $this->db->where('id', $data['status_id']);
        $this->db->update('tblleadsstatus', [
            'color' => $data['color'],
        ]);
    }

    public function update_status_order($data)
    {
        foreach ($data['order'] as $status) {
            $this->db->where('id', $status[0]);
            $this->db->update('tblleadsstatus', [
                'statusorder' => $status[1],
            ]);
        }
    }

    public function add_form($data)
    {
        $data = $this->_do_lead_web_to_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);
        $data['form_key'] = app_generate_hash();

        $data['create_task_on_duplicate'] = (int)isset($data['create_task_on_duplicate']);
        $data['mark_public'] = (int)isset($data['mark_public']);

        if (isset($data['allow_duplicate'])) {
            $data['allow_duplicate'] = 1;
            $data['track_duplicate_field'] = '';
            $data['track_duplicate_field_and'] = '';
            $data['create_task_on_duplicate'] = 0;
        } else {
            $data['allow_duplicate'] = 0;
        }

        $data['dateadded'] = date('Y-m-d H:i:s');

        $this->db->insert('tblwebtolead', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Web to Lead Form Added [' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    private function _do_lead_web_to_form_responsibles($data)
    {
        if (isset($data['notify_lead_imported'])) {
            $data['notify_lead_imported'] = 1;
        } else {
            $data['notify_lead_imported'] = 0;
        }

        if ($data['responsible'] == '') {
            $data['responsible'] = 0;
        }
        if ($data['notify_lead_imported'] != 0) {
            if ($data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids'] = serialize([]);
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

        return $data;
    }

    public function update_form($id, $data)
    {
        $data = $this->_do_lead_web_to_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);

        $data['create_task_on_duplicate'] = (int)isset($data['create_task_on_duplicate']);
        $data['mark_public'] = (int)isset($data['mark_public']);

        if (isset($data['allow_duplicate'])) {
            $data['allow_duplicate'] = 1;
            $data['track_duplicate_field'] = '';
            $data['track_duplicate_field_and'] = '';
            $data['create_task_on_duplicate'] = 0;
        } else {
            $data['allow_duplicate'] = 0;
        }

        $this->db->where('id', $id);
        $this->db->update('tblwebtolead', $data);

        return ($this->db->affected_rows() > 0 ? true : false);
    }

    public function delete_form($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblwebtolead');

        $this->db->where('from_form_id', $id);
        $this->db->update('tblleads', [
            'from_form_id' => 0,
        ]);

        if ($this->db->affected_rows() > 0) {
            logActivity('Lead Form Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    public function getallleads($table)
    {
         $loginid = $this->session->userdata('staff_user_id');


            //$this->db->select('CONCAT(tbll.firstname, " ", tbll.lastname) AS fullname,tblleads_custom.*');

            $data = $this->db->order_by("uploaded_on", "desc");

          // $this->db->join('tblstaff AS tbll', );
           $this->db->group_by('id');
            $data = $this->db->get($table)->result();
            //echo "<pre>";
            //print_r($data);die;
            return $data;
    }

    public function get_count()
    {
        $lead_id_start = $this->uri->segment(5);
        $lead_id_end = $this->uri->segment(6);
        // $result = $this->db->where('id >', $lead_id_start)->where('id <=', $lead_id_end)->get('tblleads');
        $result = $this->db->query("SELECT COUNT(*) as 'rowcnt' FROM `tblleads` WHERE `id` >= $lead_id_start AND `id` <= $lead_id_end");
        $cnt = $result->result();
        $count = $cnt[0]->rowcnt;
        // echo $this->db->last_query();


        return $count;
    }

    public function get_allleads($limit, $start, $lead_id_start = '', $lead_id_end = '', $return = "result")
    {
        $lead_id_start = $this->uri->segment(5);
        $lead_id_end = $this->uri->segment(6);
        $this->db->select('assigned_id');
        $this->db->where('lead_id_start',$lead_id_start);
        $getassres = $this->db->get('tblleads_custom')->row();
        //print_r($this->db->last_query());
        $assignedid = $getassres->assigned_id;
      
        if (!empty($id) && is_integer($id)) {
// $this->db->where('id', $id);
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);
        }
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        $this->db->where('source', '2');
        $this->db->order_by('id', 'asc');
        $this->db->limit($limit, $start);
        
        
        if(is_admin() || is_headtrm())
        {
              
            $this->db->where('assigned', $assignedid);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }
        else
        {
            $loginid = $this->session->userdata('staff_user_id');
            $this->db->where('assigned', $loginid);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }
    }

    public function _checkRecords($query, $return)
    {
        if ($query->num_rows()) {
            return $query->$return();
        }
    }

    public function get_company($lead_id_start = '', $lead_id_end = '', $return = "result")
    {
        $lead_id_start = $this->uri->segment(5);
        $lead_id_end = $this->uri->segment(6);
        if (!empty($id) && is_integer($id)) {
// $this->db->where('id', $id);
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);
        }
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        $this->db->select('company');
        $this->db->group_by('company');
        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }

    public function get_company2($return = "result")
    {
        if (!is_admin()) {
            $loginid = $this->session->userdata('staff_user_id');
            $leadsource = array(1, 5, 6, 8);
            $this->db->where('assigned', $loginid);
            $this->db->where_in('source', $leadsource);
            $this->db->select('company');
            $this->db->group_by('company');
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            $leadsource = array(1, 5, 6, 8);
            $this->db->where_in('source', $leadsource);
            $this->db->select('company');
            $this->db->group_by('company');
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }
    }

    public function get_data_source($lead_id_start = '', $lead_id_end = '', $return = "result")
    {
        $lead_id_start = $this->uri->segment(5);
        $lead_id_end = $this->uri->segment(6);
        if (!empty($id) && is_integer($id)) {
// $this->db->where('id', $id);
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);
        }
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        $this->db->select('data_source');
        $this->db->group_by('data_source');
        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }

    public function get_data_source2($return = "result")
    {
        if (!is_admin()) {
            $userid = $this->session->userdata('staff_user_id');
            $leadsource = array(1, 5, 6, 8);
            $this->db->where('assigned', $userid);
            $this->db->where_in('source', $leadsource);
            $this->db->select('data_source');
            $this->db->group_by('data_source');
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            $leadsource = array(1, 5, 6, 8);
            $this->db->where_in('source', $leadsource);
            $this->db->select('data_source');
            $this->db->group_by('data_source');
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }
    }

    public function get_calling_obj($lead_id_start = '', $lead_id_end = '', $return = "result")
    {
        $lead_id_start = $this->uri->segment(5);
        $lead_id_end = $this->uri->segment(6);
        if (!empty($id) && is_integer($id)) {
// $this->db->where('id', $id);
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);
        }
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        $this->db->select('calling_objective');
        $this->db->group_by('calling_objective');
        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }

    public function get_calling_obj2($return = "result")
    {
        if (!is_admin()) {
            $userid = $this->session->userdata('staff_user_id');
            $leadsource = array(1, 5, 6, 8);
            $this->db->where('assigned', $userid);
            $this->db->where_in('source', $leadsource);
            $this->db->select('calling_objective');
            $this->db->group_by('calling_objective');
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            $leadsource = array(1, 5, 6, 8);
            $this->db->where_in('source', $leadsource);
            $this->db->select('calling_objective');
            $this->db->group_by('calling_objective');
            return $this->_checkRecords($this->db->get('tblleads'), $return);

        }
    }

    public function get_leadsource2($return = "result")
    {
        if (!is_admin()) {
            $userid = $this->session->userdata('staff_user_id');
            $leadsource = array(1, 5, 6, 8);
            $this->db->where('assigned', $userid);
            $this->db->where_in('source', $leadsource);
            $this->db->select('ts.id as sourceid, ts.name as source');
            $this->db->group_by('source');
            $this->db->join('tblleadssources as ts', 'tblleads.source=ts.id');
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            $leadsource = array(1, 5, 6, 8);
            $this->db->where_in('source', $leadsource);
            $this->db->select('ts.id as sourceid, ts.name as source');
            $this->db->group_by('source');
            $this->db->join('tblleadssources as ts', 'tblleads.source=ts.id');
            return $this->_checkRecords($this->db->get('tblleads'), $return);

        }
    }

    public function getcustomlead($id)
    {
        return $this->db->get_where('tblleads', array('id' => $id))->row();
    }

    public function addupadte_customlead($data, $cat_id = '')
    {
        if ($cat_id) {
            $this->db->update('tblleads', $data, array('id', $cat_id));
        } else {
            $this->db->insert('tblleads', $data);
        }
    }

    public function update_cust_lead()
    {
        $status = $_GET['status'];
        $id = $_GET['id'];
        $date = date('Y-m-d');
        $data = array('status' => $status, 'lastcontact' => $date);
        $this->db->where('id', $id);
        $this->db->update('tblleads', $data);
    }

    public function update_cust_meet()
    {
        $status = $_GET['status'];
        $id = $_GET['id'];
        $lead_id = $_GET['lead_id'];
        $userid = $this->session->userdata('staff_user_id');
        echo $lead_id;

        switch ($status) {
            case "3":
                {
                    $data = array('status' => $status);
                    $this->db->where('id', $id);
                    $this->db->update('tblmeeting_scheduled', $data);

                    $data = array('meet_lead_id' => $lead_id, 'converted_by' => $userid);
                    $this->db->insert('tblbusiness', $data);
                }
                break;

            default:
                {
                    $data = array('status' => $status);
                    $this->db->where('id', $id);
                    $this->db->update('tblmeeting_scheduled', $data);
                }
        }


    }

    public function update_cust_lead_pop()
    {
        var_dump($status = $_GET['status']);
        var_dump($id = $_GET['id']);
        var_dump($description = $_GET['description']);

        $data = array('status' => $status, 'description');

        $this->db->update('tblleads', $data, array('id', $id));
    }

   // public function get_leadstatus($table)
   //  {
   //      $this->db->where(array());
   //      $data = $this->db->get($table)->result();
   //      return $data;
   //  }

    public function get_meetingtatus($table)
    {
        $this->db->where(array());
        $data = $this->db->get($table)->result();
        return $data;
    }
    function upload_leads()
    {
        $total_imported = 0;
        $status = $_POST['status'];
        $source = $_POST['source'];
        $file_name = $_POST['file_name'];
        
        $count = 0;
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
        $leadCount = 0;
        $c =0;
        $t=0;
        $currentDate = date("Y-m-d h:i:s");
        while ($csv_line = fgetcsv($fp, 10240)) {
            $count++;
            if ($count == 1) {
                continue;
            }
            
       
                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                        $insert_csv = array();
                        $insert_csv['0'] = htmlspecialchars($csv_line[0]);
                        $insert_csv['1'] = htmlspecialchars($csv_line[1]);
                        $insert_csv['2'] =  htmlspecialchars($csv_line[2]);
                        $insert_csv['3'] =  htmlspecialchars($csv_line[3]);
                        $insert_csv['4'] = htmlspecialchars($csv_line[4]);
                        $insert_csv['5'] =  htmlspecialchars($csv_line[5]);
                        $insert_csv['6'] =  htmlspecialchars($csv_line[6]);
                        $insert_csv['7'] = htmlspecialchars($csv_line[7]);
                        $insert_csv['8'] =  htmlspecialchars($csv_line[8]);
                        $insert_csv['9'] = htmlspecialchars($csv_line[9]);

                  
                    //}
                }
                $i++;
                $total_imported++;
                $leadCount++;
                $result = array();
            

                $str = $insert_csv['7'];
                $findslash = strpos($csv_line[8],"/");
                $findslash1 = strpos($csv_line[8],",");
                $findslash2 = strpos($csv_line[8],"-");
                if($findslash >=1){
                    $seprate_date=explode("/",$csv_line[8]);
                   
                    $p = 0;
                    foreach($seprate_date as $key => $value){
                    $this->db->select('id,phonenumber');
                    $this->db->where('phonenumber', $value);
                    $this->db->from('tblleads');
                    $query = $this->db->get();
                    $result = $query->result();
                    // print_r($result);
                    if (count($result)>0) {
                     
                    
                            $data_array = array(
                                'duplicate_l' => $result[0]->id
                            );
                           
                            $this->db->where('id',$result[0]->id);
                            $this->db->update('tblleads',$data_array);
                           
                            $datavv = array(
                                'lead_created_date' => $insert_csv['0'],
                                'lead_ad_id' => $insert_csv['1'],
                                //'leadId' => $insert_csv['1'],
                                'lead_adname' => $insert_csv['2'],
                                'lead_author_mslanguage' => $insert_csv['3'],
                                'lead_author_msstatus' => $insert_csv['4'],
                                'lead_publishedearlier' => $insert_csv['5'],
                                
                                'email' => $insert_csv['6'],
                               
                                'lead_author_name' => $str,
                                'phonenumber' => $insert_csv['8'],
                                'lead_created_date'=>$currentDate,
                                'duplicate_l'=>$result[0]->id,
                                'assigned'=>0,
                                
                            );
                        $p++;
                        }else{
                            if ($p > 0 ) {
                                
                            }else{
                                $datavv = array(
                                    'lead_created_date' => $insert_csv['0'],
                                    'lead_ad_id' => $insert_csv['1'],
                                    //'leadId' => $insert_csv['1'],
                                    'lead_adname' => $insert_csv['2'],
                                    'lead_author_mslanguage' => $insert_csv['3'],
                                    'lead_author_msstatus' => $insert_csv['4'],
                                    'lead_publishedearlier' => $insert_csv['5'],
                                    
                                    'email' => $insert_csv['6'],
                                   
                                    'lead_author_name' => $str,
                                    'phonenumber' => $insert_csv['8'],
                                    'lead_created_date'=>$currentDate,
                                    'assigned'=>0,
                                    'assigned'=>'no',
                                    
                                );
                            }
                           
                            
                        }
                       
                    }
                // print_r($datavv);
                  //  }
                
                }else if($findslash2 >=1){
                    $seprate_date2=explode("-",$csv_line[8]);
                    $k = 0;
                    foreach($seprate_date2 as $key => $value){
                    $this->db->select('id,phonenumber');
                    $this->db->like('phonenumber', $value);
                    $this->db->from('tblleads');
                    $query = $this->db->get();
                    $result = $query->result();
                  
                    if ($result !='') {
                       
                            $data_array = array(
                                'duplicate_l' => $result[0]->id
                            );
                        
                            $this->db->where('id',$result[0]->id);
                            $this->db->update('tblleads',$data_array);
                        
                            $datavv = array(
                                'lead_created_date' => $insert_csv['0'],
                                'lead_ad_id' => $insert_csv['1'],
                                //'leadId' => $insert_csv['1'],
                                'lead_adname' => $insert_csv['2'],
                                'lead_author_mslanguage' => $insert_csv['3'],
                                'lead_author_msstatus' => $insert_csv['4'],
                                'lead_publishedearlier' => $insert_csv['5'],
                                
                                'email' => $insert_csv['6'],
                            
                                'lead_author_name' => $str,
                                'phonenumber' => $insert_csv['8'],
                                'lead_created_date'=>$currentDate,
                                'duplicate_l'=>$result[0]->id,
                                'assigned'=>0,
                                
                            );
                            $k++;
                    }else{ if ($k == 0 ) {
                        $datavv = array(
                            'lead_created_date' => $insert_csv['0'],
                            'lead_ad_id' => $insert_csv['1'],
                            //'leadId' => $insert_csv['1'],
                            'lead_adname' => $insert_csv['2'],
                            'lead_author_mslanguage' => $insert_csv['3'],
                            'lead_author_msstatus' => $insert_csv['4'],
                            'lead_publishedearlier' => $insert_csv['5'],
                            
                            'email' => $insert_csv['6'],
                        
                            'lead_author_name' => $str,
                            'phonenumber' => $insert_csv['8'],
                            'lead_created_date'=>$currentDate,
                            'assigned'=>0,
                            
                        );   
                        }else{
                           
                    
                        }
                
                
                    }
                }
                }else{
                    $datavv = array(
                        'lead_created_date' => $insert_csv['0'],
                        'lead_ad_id' => $insert_csv['1'],
                        //'leadId' => $insert_csv['1'],
                        'lead_adname' => $insert_csv['2'],
                        'lead_author_mslanguage' => $insert_csv['3'],
                        'lead_author_msstatus' => $insert_csv['4'],
                        'lead_publishedearlier' => $insert_csv['5'],
                        
                        'email' => $insert_csv['6'],
                       
                        'lead_author_name' => $str,
                        'phonenumber' => $insert_csv['8'],
                        'lead_created_date'=>$currentDate,
                        'assigned'=>0,
                        
                    );
                }
               
                $leadId = $insert_csv['1'];
                $teleRM = $this->leads_model->get_telerm('', 'staffid,CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');
                $totTeleRM = count($teleRM);
                $result = $leadCount / $totTeleRM;
                $ids= array();
                foreach ($teleRM as $row){
                 $ids[] = $row['staffid'];
                }  
                $datavv['assigned']=0;
               $surbhi[__LINE__][]= $data['crane_features'] = $this->db->insert('tblleads', $datavv);
               $insert_id_last_lead = $this->db->insert_id();
                $surbhi[__LINE__][]= $this->db->last_query();
                if($c == $result){
                    $c=0;  
                }else{
                    $c++;  
                }
            }   
            // die;
        // }
        
        fclose($fp) or die("can't close file");
        
        if (isset($data['crane_features'])) {
          
            $last_id = $insert_id_last_lead;


            $lead_end_id = $last_id;
            $lead_start_id = ($lead_end_id - $total_imported);
            $filename = preg_replace('/[^ \w]+/', '_', $_POST['file_name']);


            $data_custom = array(
                'name' => $filename,
                'lead_id_start' => $lead_start_id+1,
                'lead_id_end' => $lead_end_id,
                'assigned_id' => '0'
            );
            
        
            $data_custom['crane_features'] = $this->db->insert('tblleads_custom', $data_custom);
            
             

        }

        $data['success'] = "success";
        set_alert('success', _l('import_total_imported', $total_imported));
        // die;
        return $data;
    } 
    function upload_leads_bkpdata()
    {
        $total_imported = 0;
        $status = $_POST['status'];
        $source = $_POST['source'];
        $file_name = $_POST['file_name'];
        
        $count = 0;
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
        $leadCount = 0;
        $c =0;
        $t=0;
        $currentDate = date("Y-m-d h:i:s");
        while ($csv_line = fgetcsv($fp, 10240)) {
            $count++;
            if ($count == 1) {
                continue;
            }
            
            // $this->db->select('*')->from('tblleads');
            // $this->db->where('email',$csv_line['6']);
            // $query = $this->db->get()->result();
            // $tot=count($query);
            // if($tot >=1){
            //    $found = $query[0]->email;
            // }
           
            // if($found == ""){
                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                    //if (!empty($csv_line[1])) {
                        $insert_csv = array();
                        $insert_csv['0'] = htmlspecialchars($csv_line[0]);
                        $insert_csv['1'] = htmlspecialchars($csv_line[1]);
                        $insert_csv['2'] =  htmlspecialchars($csv_line[2]);
                        $insert_csv['3'] =  htmlspecialchars($csv_line[3]);
                        $insert_csv['4'] = htmlspecialchars($csv_line[4]);
                        $insert_csv['5'] =  htmlspecialchars($csv_line[5]);
                        $insert_csv['6'] =  htmlspecialchars($csv_line[6]);
                        $insert_csv['7'] = htmlspecialchars($csv_line[7]);
                        $insert_csv['8'] =  htmlspecialchars($csv_line[8]);
                        $insert_csv['9'] = htmlspecialchars($csv_line[9]);
                        
                       
                    //}
                }
                $i++;
                $total_imported++;
                $leadCount++;
                $str = $insert_csv['7'];
                $datavv = array(
                    'lead_created_date' => $insert_csv['0'],
                    'lead_ad_id' => $insert_csv['1'],
                    //'leadId' => $insert_csv['1'],
                    'lead_adname' => $insert_csv['2'],
                    'lead_author_mslanguage' => $insert_csv['3'],
                    'lead_author_msstatus' => $insert_csv['4'],
                    'lead_publishedearlier' => $insert_csv['5'],
                    
                    'email' => $insert_csv['6'],
                   
                    'lead_author_name' => $str,
                    'phonenumber' => $insert_csv['8'],
                      'lead_created_date'=>$currentDate,
                    
                );
                $leadId = $insert_csv['1'];
                $teleRM = $this->leads_model->get_telerm('', 'staffid,CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');
                $totTeleRM = count($teleRM);
                $result = $leadCount / $totTeleRM;
                $ids= array();
                foreach ($teleRM as $row){
                 $ids[] = $row['staffid'];
                }
                     
                $datavv['assigned']=0;
                $surbhi[__LINE__][]= $data['crane_features'] = $this->db->insert('tblleads', $datavv);
                $insert_id_last_lead = $this->db->insert_id();
                $surbhi[__LINE__][]= $this->db->last_query();
                if($c == $result){
                    $c=0;  
                }else{
                    $c++;  
                }
            }    
        // } 
        
        fclose($fp) or die("can't close file");
        
        if (isset($data['crane_features'])) {
          
            $last_id = $insert_id_last_lead;


            $lead_end_id = $last_id;
            $lead_start_id = ($lead_end_id - $total_imported);
            $filename = preg_replace('/[^ \w]+/', '_', $_POST['file_name']);


            $data_custom = array(
                'name' => $filename,
                'lead_id_start' => $lead_start_id+1,
                'lead_id_end' => $lead_end_id,
                'assigned_id' => '0'
            );
            
        
            $data_custom['crane_features'] = $this->db->insert('tblleads_custom', $data_custom);
            
             

        }

        $data['success'] = "success";
        set_alert('success', _l('import_total_imported', $total_imported));
        return $data;
    }

    public function getallleads_custom($table)
    {
        if (is_admin()) {
            $loginid = $this->session->userdata('staff_user_id');

            $data = $this->db->get($table)->result();
// $data= $this->db->order_by('asc');
            return $data;
        } else {

            $loginid = $this->session->userdata('staff_user_id');
//$this->db->where('application_status','2');
            $this->db->where('assigned_id', $loginid);
            $data = $this->db->get($table)->result();
// $data= $this->db->order_by('asc');
            return $data;

        }
    }

     public function get_row_lead_data($return = "result")
    {
        $lead_id = $_POST['id'];
        $this->db->select('tbll.*,CONCAT(tbls.firstname, " ", tbls.lastname) AS  firstname');
        $this->db->join('tblstaff AS tbls', 'tbll.assigned=tbls.staffid');
        $this->db->where('id', $lead_id);
        return $this->_checkRecords($this->db->get('tblleads as tbll'), $return);
    }
    public function get_allleads_filter($return = "result")
    {
        $status = $_GET['status'];
        $lead_id_end = $_GET['lead_id_end'];
        $lead_id_start = $_GET['lead_id_start'];
        $company = $_GET['company'];
        $data_source = $_GET['data_source'];
        $calling_objective = $_GET['calling_objective'];
        $lastcontact = $_GET['lastcontact'];

        if (isset($status)) {

            $this->db->where_in('status', $status);
        }
        if (isset($company)) {

            $this->db->where_in('company', $company);
        }
        if (isset($data_source)) {

            $this->db->where_in('data_source', $data_source);
        }
        if (isset($calling_objective)) {

            $this->db->where_in('calling_objective', $calling_objective);
        }
        if (isset($lastcontact) && $lastcontact != null) {

            $this->db->where_in('lastcontact', $lastcontact);
        }

       // $this->db->where_in('source', $source);
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }

    public function get_allleads_filter_added($return = "result")
    {
        $leadviewpage = $_GET['leadpagename'];
        echo $leadviewpage;
        
        
        $source = array(1, 5, 6, 8);
        $status =  $_GET['status'];
        $company = $_GET['company'];
        $data_source = $_GET['data_source'];
        $calling_objective = $_GET['calling_objective'];
        $source = $_GET['source'];
        $lastcontact = $_GET['lastcontact'];


        if (!is_admin()) {
            $userid = $this->session->userdata('staff_user_id');
            $this->db->where('assigned', $userid);
            if (isset($status)) {

                $this->db->where_in('status', $status);
            }
            if (isset($company)) {

                $this->db->where_in('company', $company);
            }
            if (isset($data_source)) {

                $this->db->where_in('data_source', $data_source);
            }
            if (isset($calling_objective)) {

                $this->db->where_in('calling_objective', $calling_objective);
            }
            if (isset($source)) {
                

                
            }
            if (isset($lastcontact) && $lastcontact != null) {

                $this->db->where_in('lastcontact', $lastcontact);
            }
            if($leadviewpage == 'viewassignedleads')
                {
                    $this->db->where_not_in("source",'2');
                }
                else
                {
                    $this->db->where_in('source', 2);
                }
            
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            if (isset($status)) {

                $this->db->where_in('status', $status);
            }
            if (isset($company)) {

                $this->db->where_in('company', $company);
            }
            if (isset($data_source)) {

                $this->db->where_in('data_source', $data_source);
            }
            if (isset($calling_objective)) {

                $this->db->where_in('calling_objective', $calling_objective);
            }
            if (isset($source)) {

               
            }
            if (isset($lastcontact) && $lastcontact != null) {

                $this->db->where_in('lastcontact', $lastcontact);
            }
            
             if($leadviewpage == 'viewassignedleads')
                {
                    $this->db->where_not_in("source",'2');
                }
                else
                {
                    $this->db->where_in('source', 2);
                }

            $this->db->where_in('source', $source);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }


    }

    public function filter_by_company($return = "result")
    {
        $company = $_GET['company'];
        $lead_id_end = $_GET['lead_id_end'];
        $lead_id_start = $_GET['lead_id_start'];

        if ($company == "0") {

// $this->db->where('id', $id);
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);

        } else {
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);
            $this->db->where_in('company', $company);

        }

        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }

    public function filter_by_company_added($return = "result")
    {
        $company = $_GET['company'];
        $source = array(1, 5, 6, 8);

        if (!is_admin()) {
            $userid = $this->session->userdata('staff_user_id');
            $this->db->where('assigned', $userid);

            $this->db->where_in('source', $lead_id_end);
            $this->db->where_in('company', $company);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            $this->db->where_in('source', $lead_id_end);
            $this->db->where_in('company', $company);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }

    }

    public function filter_by_data_source($return = "result")
    {
        $data_source = $_GET['data_source'];
        $lead_id_end = $_GET['lead_id_end'];
        $lead_id_start = $_GET['lead_id_start'];

        if ($data_source == "0") {

// $this->db->where('id', $id);
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);

        } else {
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);
            $this->db->where_in('data_source', $data_source);

        }

        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }


    public function filter_by_data_source_added($return = "result")
    {
        $data_source = $_GET['data_source'];
        $source = array(1, 5, 6, 8);

        if (!is_admin()) {
            $userid = $this->session->userdata('staff_user_id');
            $this->db->where('assigned', $userid);
            $this->db->where_in('source', $source);
            $this->db->where_in('data_source', $data_source);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            $this->db->where_in('source', $source);
            $this->db->where_in('data_source', $data_source);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }
    }

    public function filter_by_calling_objective($return = "result")
    {
        $calling_objective = $_GET['calling_objective'];
        $lead_id_end = $_GET['lead_id_end'];
        $lead_id_start = $_GET['lead_id_start'];

        if ($calling_objective == "0") {

// $this->db->where('id', $id);
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);

        } else {
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);
            $this->db->where_in('calling_objective', $calling_objective);

        }

        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }

    public function filter_by_calling_objective_added($return = "result")
    {
        $calling_objective = $_GET['calling_objective'];
        $source = array(1, 5, 6, 8);
        if (!is_admin()) {
            $userid = $this->session->userdata('staff_user_id');
            $this->db->where('assigned', $userid);
            $this->db->where_in('source', $source);
            $this->db->where_in('calling_objective', $calling_objective);

            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            $this->db->where_in('source', $source);
            $this->db->where_in('calling_objective', $calling_objective);

            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }
    }

    public function filter_by_lastcontact($return = "result")
    {
        $lastcontact = $_GET['lastcontact'];
        $lead_id_end = $_GET['lead_id_end'];
        $lead_id_start = $_GET['lead_id_start'];

        if ($lastcontact == "0") {

// $this->db->where('id', $id);
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);

        } else {
            $this->db->where('id >=', $lead_id_start);
            $this->db->where('id <=', $lead_id_end);
            $this->db->where('lastcontact', $lastcontact);

        }

        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }

    public function filter_by_lastcontact_added($return = "result")
    {
        $lastcontact = $_GET['lastcontact'];

        $source = array(1, 5, 6, 8);
        if (!is_admin()) {
            $userid = $this->session->userdata('staff_user_id');
            $this->db->where('assigned', $userid);
            $this->db->where_in('source', $source);
            $this->db->where('lastcontact', $lastcontact);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        } else {
            $this->db->where_in('source', $source);
            $this->db->where('lastcontact', $lastcontact);
            return $this->_checkRecords($this->db->get('tblleads'), $return);
        }
    }

    function reassign($lead_id, $staff_id,$mainassigned)
    {
        $lead = $this->db->get_where('tblleads', array('id' => $lead_id))->row();
        if ($lead->status == 1) {
            $insertUpdate = array(
                'meeting_cat' => "Lead",
                'lead_id' => $lead->id,
                'assigned' => $staff_id,
                'assigned_by' => $mainassigned
            );
            $query = $this->db->get_where('tblmeeting_scheduled', array('lead_id' => $lead_id));
            if ($query->num_rows()) {
                $insertUpdate['updated'] = date('Y-m-d H:i:s');
                $this->db->update('tblmeeting_scheduled', $insertUpdate, array('lead_id' => $lead_id));
            } else {
                $insertUpdate['created'] = date('Y-m-d H:i:s');
                $this->db->insert('tblmeeting_scheduled', $insertUpdate);
                $insertId = $this->db->insert_id();
                $meetingUpdate = array(
                    'meeting_id' => $insertId,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->db->insert('tblmeeting_remark', $meetingUpdate);

            }
        }
        //$this->db->update('tblleads', array('assigned' => $staff_id), array('id' => $lead_id));
        $this->lead_assigned_member_notification($lead_id, $staff_id);
        $notification_data = [
            'description' => 'not_assigned_lead_to_tel_to_rm',
            'touserid' => $staff_id,
            'link' => '#leadid=' . $lead_id,
            'additional_data' => serialize([]),
        ];
        if (add_notification($notification_data)) {
            pusher_trigger_notification([$staff_id]);
        }
    }

    function getMeetingScheduledLeads()
    {
        $this->db->select('tbll.name,tbll.id as lead_id, tbll.meetingtimefrom,tbll.meetingtimeto,
        tbll.description, tbll.phonenumber,tbll.email,tbll.designation,tbll.company,tbll.address, CONCAT(tbls.firstname, " ", tbls.lastname) AS  firstname, CONCAT(tbls1.firstname, " ", tbls1.lastname) AS  assigned_to, tblms.status as meetingstatus, tblms.*');
        $this->db->join('tblleads AS tbll', 'tblms.lead_id=tbll.id');
        $this->db->join('tblstaff AS tbls', 'tblms.assigned_by=tbls.staffid');
        $this->db->join('tblstaff AS tbls1', 'tblms.assigned=tbls1.staffid');
        $this->db->where('tblms.status', "New");
        if(is_admin())
        {
            $this->db->from('tblmeeting_scheduled AS tblms');
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result();
            }
            return false;
        }
        // this code is for hirearchy permissions for different modules
        
        elseif(herapermission())
        {
             $arr=herapermission();
			 $useraid = $this->session->userdata('staff_user_id');
             $staffids = array($arr);
             
             
            $this->db->where("tblms.assigned IN (".$arr.")",NULL, false);
            // $this->db->where_in('tblms.assigned', $staffids);
			 $this->db->or_where_in('tblms.assigned', $useraid);
             $this->db->from('tblmeeting_scheduled AS tblms');
             $query = $this->db->get();
            // print_r($this->db->last_query());
             if ($query->num_rows()) {
                 return $query->result();
             }
             return false;
        }
        

    }

    public function attemptlead($today)
    {
        $this->db->where('lastcontact', $today);
        $query = $this->db->get('tblleads');
        $num = $query->num_rows();
        return $num;
    }

    public function attemptleadbystatus($today)
    {
        $this->db->select('status');
        $this->db->where('lastcontact', $today);
        $this->db->group_by('status');
        $query = $this->db->get('tblleads');
        $query1 = $query->result_array();
        $status = array_column($query1, 'status');

        $rows = [];
        foreach ($status as $sta) {

            $this->db->where('lastcontact', $today);
            $this->db->where('status', $sta);
            $query = $this->db->get('tblleads');
            $num = $query->num_rows();
            $rows[$sta] = $num;

        }

        return $rows;
    }

    function filter_lead_last_connect($status, $start, $end, $staff_id)
    {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM tblleads WHERE ";
        if (!empty($start) && !empty($end)) {
            $sql .= " DATE(lastcontact) BETWEEN '$start' AND '$end' ";
        } else {
            $sql .= " DATE(lastcontact) = '$today' ";
        }
        if (!empty($staff_id)) {
            $sql .= "AND assigned='$staff_id' ";
        }
        if (!empty($status)) {
            $sql .= "AND status='$status' ";
        }
        return $result = $this->db->query($sql)->result();
    }

    public function delete_leads($lead_id_start, $lead_id_end)
    {
        /*echo $lead_id_end;
        exit;*/
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);

        $this->db->delete('tblleads');
//$this->db->delete('tblleads');
        $this->db->where('lead_id_start', $lead_id_start);

        $this->db->delete('tblleads_custom');
    }

    public function delete_addedleads($lead_id)
    {

        $this->db->where('id', $lead_id);
        $this->db->delete('tblleads');

        $this->db->where('lead_id', $lead_id);
        $this->db->delete('tblleads_custom');
        
        $this->db->where('rel_id', $lead_id);
        $this->db->where('rel_type', 'lead');
        $this->db->delete('tblreminders');
        
        $this->db->where('lead_id', $lead_id);
        $this->db->delete(' tblmeeting_scheduled');
        
    }

    public function getstaffrole($table, $useraid)
    {
        $this->db->select('role');
        $this->db->where('staffid', $useraid);
        return $this->db->get($table)->row();
    }

    function fetch_company($catid)
    {
        switch ($catid) {
            case "111":
                {

                    $this->db->where('cat_id', $catid);
                    $this->db->group_by('company_id');
                    $this->db->join('tblproduct_companies AS tbll', 'tblproducts.company_id=tbll.id');
                    $this->db->select('company_id, tbll.name as companyname');

                    //$this->db->join('tblproduct_companies AS tblc', 'tblproducts.cat_id=tblc.id');

                    $query = $this->db->get('tblproducts');
                    print_r($query->result());
                    $output = '
                    <select name="category" id="category" class="form-control  relation selectpicker">';
                    '<option value="">Select Scheme Category</option>';
                    foreach ($query->result() as $row) {
                        $output .= '
                    <option value="' . $row->company_id . '">' . $row->companyname . '</option>';
                    }
                    '</select>';
                    $output .= '';
                    return $output;

                }
                break;

            default:
                {
                    $this->db->where('cat_id', $catid);
                    $this->db->group_by('company_id');
                    $this->db->join('tblproduct_companies AS tbll', 'tblproducts.company_id=tbll.id');
                    $this->db->select('company_id, tbll.name as companyname');

//$this->db->join('tblproduct_companies AS tblc', 'tblproducts.cat_id=tblc.id');

                    $query = $this->db->get('tblproducts');

                    $output = '
<option value="">Select Company</option>';
                    foreach ($query->result() as $row) {
                        $output .= '
<option value="' . $row->company_id . '">' . $row->companyname . '</option>';
                        $output .= '';
                    }
                    return $output;
                }
        }

    }

    function fetch_product($company)
    {
        $catid = $this->input->post('category');
        //echo $catid;
        $this->db->where('cat_id', $catid);
        $this->db->where('company_id', $company);
        switch ($catid) {
            case "81":
                {
                    $this->db->select('id,CONCAT(product_name, " - Tenure ", tenure, " Months") AS product_name');
                }
                break;
            default:
                {
                    $this->db->select('id, product_name');
                }
        }

//$this->db->group_by('company_id');
        $query = $this->db->get('tblproducts');
        //echo $this->db->last_query();
        $output = '
<option value="">Select Product</option>';
        foreach ($query->result() as $row) {
            $output .= '
<option value="' . $row->id . '">' . $row->product_name . '</option>';
            $output .= '';
        }
		//echo $this->db->last_query();exit;
        return $output;
    }

    function fetch_transaction_type($transaction_type)
    {
        $category = $this->input->post('category');
        $productname = $this->input->post('productname');
        
        $transdate = $this->input->post('trandate');
        $this->db->select('created');
        $this->db->where('id',$productname);
        $creprev = $this->db->get('tblproducts')->row();
        $creditpreex = $creprev->created;
        $creditpreex = "2019-4-01";
        echo $creditpreex;
        if(strtotime($transdate) < strtotime($creditpreex))
        {
            print_r($this->db->last_query()); 
          return  $output = '<input name="credit" id="credit" value="0" class="form-control hidediv" />';
        }
        
        switch ($category) {
            case "80":
                {
                    switch ($transaction_type) {
                        case "LUMPSUM":
                            {
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_rate_lum');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_rate_lum');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_rate_lum');
                                $query = $this->db->get('tblproducts');  
                                }
                               

                                
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_rate_lum . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                            break;

                        case "SIP":
                            {
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_rate_sip');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_rate_sip');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_rate_sip');
                                $query = $this->db->get('tblproducts');  
                                }
                                

                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_rate_sip . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                            break;

                        default:
                            {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_rate_lum');
                                $query = $this->db->get('tblproducts');

                                //print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_rate_lum . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                    }
                }
                break;

            case "81":
                {
                    switch ($transaction_type) {
                        default :
                            {
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                    $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                    $this->db->select('credit');
                                    $query = $this->db->get('tblcredit_rate');  
                                }
                                
                                else
                                {
                                    $this->db->where('id', $productname);
                                    $this->db->select('credit');
                                    $query = $this->db->get('tblproducts');
                                }
                                print_r($this->db->last_query()); 
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                    }
                }
                break;

            case "82":
                {
                    switch ($transaction_type) {
                        case "FRESH":
                            {   
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblproducts');  
                                }

                                print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_fresh . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                            break;

                        case "RENEWAL":
                            {
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblproducts');  
                                }
                                

                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_renewal . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                            break;

                        default:
                            {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_rate_lum');
                                $query = $this->db->get('tblproducts');

                                print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_rate_lum . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                    }
                }
                break;

            case "83":
                {
                    switch ($transaction_type) {
                        case "FRESH":
                            {
                                
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblproducts');  
                                }

                                print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_fresh . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                            break;

                        case "RENEWAL":
                            {

                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblproducts');  
                                }

                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_renewal . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                            break;

                        default:
                            {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_rate_lum');
                                $query = $this->db->get('tblproducts');

                                print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_rate_lum . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                    }
                }
                break;

            case "84":
                {
                    switch ($transaction_type) {
                        case "FRESH":
                            {
                                
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblproducts');  
                                }

                                print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_fresh . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                            break;

                        case "RENEWAL":
                            {

                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblproducts');  
                                }

                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_renewal . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                            break;

                        default:
                            {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_rate_lum');
                                $query = $this->db->get('tblproducts');

                                print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_rate_lum . '" class="form-control hidediv" />';
                                    $output .= '';
                                }
                            }
                    }
                }
                break;

            case "85":
                {

                    $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                    $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                    $this->db->select('credit');
                                    $query = $this->db->get('tblcredit_rate');   
                                }
                                
                                else
                                {
                                    $this->db->where('id', $productname);
                                    $this->db->select('credit');
                                    $query = $this->db->get('tblproducts');
                                }

                    print_r($this->db->last_query());
                    foreach ($query->result() as $row) {
                        $output = '<input name="credit" id="credit" value="' . $row->credit . '" class="form-control hidediv" />';
                        $output .= '';
                    }
                }
                break;

            case "86":
                {
                    switch ($transaction_type) {
                        case "FRESH":
                            {
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblproducts');  
                                }

                                print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_fresh . '" class="form-control hidediv" />';
                                     $output .= '';
                                }
                            }
                            break;

                        case "RENEWAL":
                            {

                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_renewal');
                                $query = $this->db->get('tblproducts');  
                                }

                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_renewal . '" class="form-control hidediv" />';
                                     $output .= '';
                                }
                            }
                            break;

                        default:
                            {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_rate_lum');
                                $query = $this->db->get('tblproducts');

                                print_r($this->db->last_query());
                                foreach ($query->result() as $row) {
                                    $output = '<input name="credit" id="credit" value="' . $row->credit_rate_lum . '" class="form-control hidediv" />';
                                     $output .= '';
                                }
                            }
                    }
                }
                break;

            case "87":
                {
                    $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                    $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                    $this->db->select('credit');
                                    $query = $this->db->get('tblcredit_rate');   
                                }
                                
                                else
                                {
                                    $this->db->where('id', $productname);
                                    $this->db->select('credit');
                                    $query = $this->db->get('tblproducts');
                                }

                    print_r($this->db->last_query());
                    foreach ($query->result() as $row) {
                        $output = '<input name="credit" id="credit" value="' . $row->credit . '" class="form-control hidediv" />';
                         $output .= '';
                    }
                }
                break;

            case "88":
                {
                    $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                    $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                    $this->db->select('credit');
                                    $query = $this->db->get('tblcredit_rate');   
                                }
                                
                                else
                                {
                                    $this->db->where('id', $productname);
                                    $this->db->select('credit');
                                    $query = $this->db->get('tblproducts');
                                }

                    print_r($this->db->last_query());
                    foreach ($query->result() as $row) {
                        $output = '<input name="credit" id="credit" value="' . $row->credit . '" class="form-control hidediv" />';
                         $output .= '';
                    }
                }
                break;

            case "89":
                {
                    
                    $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                $nums = $query->num_rows();
                                if($nums > 0)
                                {
                                    
                                $this->db->where('scheme_id', $productname);
                                $this->db->where('score_changed <=', $transdate);
                                $this->db->order_by('score_changed', 'ASC');
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblcredit_rate');
                                }
                                
                                else
                                {
                                $this->db->where('id', $productname);
                                $this->db->select('credit_fresh');
                                $query = $this->db->get('tblproducts');  
                                }
                                print_r($this->db->last_query());

                   
                    foreach ($query->result() as $row) {
                        $output = '<input name="credit" id="credit" value="' . $row->credit_fresh . '" class="form-control hidediv" />';
                         $output .= '';
                    }
                }
                break;

            default:
                {
                    $this->db->where('id', $productname);
                    $this->db->select('credit');
                    $query = $this->db->get('tblproducts');
                    foreach ($query->result() as $row) {
                        $output = '<input name="credit" id="credit" value="' . $row->credit . '" class="form-control hidediv" />';
                         $output .= '';
                    }
                }
        }
        return $output;


    }

    function fetch_credit($productname)
  {
        $productname = $this->input->post('productname');
        $category = $this->input->post('category');
        //echo $category;

        switch ($category) {
            case "80":
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" name="transaction_type" onchange="getTransactionType(this.value)" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            <option value="LUMPSUM">LUMPSUM </option>
                            <option value="SIP">SIP </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Folio Number)</small>
            <input name="folio_number" id="folio_number" value="" placeholder="Folio Number" class="form-control" />
            </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required />
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
			
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            
            <script>function calculatePrice() {
            
         var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
            gc_amount = ((credit_cal / 100 ) * transaction_amount_cal);
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal / 100 )  * gc_amount);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal / 100) * post_gst_credit_cal);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
		amountinWords(transa_amount);
        
    }</script>';
                         $output .= '';

                    }
                    return $output;
                }
                break;
            case "81":
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,tenure,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" onchange="getTransactionType(this.value)" name="transaction_type" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            <option value="LUMPSUM">LUMPSUM </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Tenure)</small>
            <input name="tenure" id="tenure" value="' . $row->tenure . ' Months" class="form-control" readonly/>
            </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required/>
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            <script>function calculatePrice() {
            
         var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
			asm = credit_cal*1000;
            gc_amount = ((asm / 100 ) * transaction_amount_cal)/1000;
			//gc_amount = transaction_amount_cal;
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal * gc_amount)/ 100);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal * post_gst_credit_cal)/ 100);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
        amountinWords(transa_amount);
    }</script>';
                         $output .= '';

                    }
                    return $output;
                }
                break;

            case "82":
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,tenure,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" onchange="getTransactionType(this.value)" name="transaction_type" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            <option value="FRESH">FRESH </option>
                            <option value="RENEWAL">RENEWAL </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required/>
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            
            <script>function calculatePrice() {
            
         var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
            gc_amount = ((credit_cal / 100 ) * transaction_amount_cal);
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal / 100 )  * gc_amount);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal / 100) * post_gst_credit_cal);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
        amountinWords(transa_amount);
    }
    
    </script>';
                         $output .= '';

                    }
                    return $output;
                }
                break;

            case "83":
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,tenure,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" onchange="getTransactionType(this.value)" name="transaction_type" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            <option value="FRESH">FRESH </option>
                            <option value="RENEWAL">RENEWAL </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required />
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            
            <script>function calculatePrice() {
            
         var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
            gc_amount = ((credit_cal / 100 ) * transaction_amount_cal);
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal / 100 )  * gc_amount);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal / 100) * post_gst_credit_cal);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
        amountinWords(transa_amount);
    }
    
    </script>';
                         $output .= '';

                    }
                    return $output;
                }
                break;
            case "84":
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,tenure,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" onchange="getTransactionType(this.value)" name="transaction_type" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            <option value="FRESH">FRESH </option>
                            <option value="RENEWAL">RENEWAL </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required />
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            
            <script>function calculatePrice() {
            
         var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
            gc_amount = ((credit_cal / 100 ) * transaction_amount_cal);
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal / 100 )  * gc_amount);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal / 100) * post_gst_credit_cal);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
        amountinWords(transa_amount);
    }
    
    </script>';
                         $output .= '';

                    }
                    return $output;
                }
                break;
            case "85":
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,tenure,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" onchange="getTransactionType(this.value)" name="transaction_type" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            
                            <option value="LUMPSUM">LUMPSUM </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required />
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            
            <script>function calculatePrice() {
            
         var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
            gc_amount = ((credit_cal / 100 ) * transaction_amount_cal);
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal / 100 )  * gc_amount);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal / 100) * post_gst_credit_cal);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
        amountinWords(transa_amount);
    }
    
    </script>';
                         $output .= '';

                    }
                    return $output;
                }
                break;

            case "86":
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,tenure,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" onchange="getTransactionType(this.value)" name="transaction_type" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            <option value="FRESH">FRESH </option>
                            <option value="RENEWAL">RENEWAL </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required />
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            
            <script>function calculatePrice() {
            
         var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
            gc_amount = ((credit_cal / 100 ) * transaction_amount_cal);
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal / 100 )  * gc_amount);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal / 100) * post_gst_credit_cal);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
        amountinWords(transa_amount);
    }
    
    </script>';
                         $output .= '';

                    }
                    return $output;
                }
                break;

            case "87-":
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,tenure,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" onchange="getTransactionType(this.value)" name="transaction_type" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            
                            <option value="LUMPSUM">LUMPSUM </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required />
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            
            <script>function calculatePrice() {
            
         var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
            gc_amount = ((credit_cal / 100 ) * transaction_amount_cal);
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal / 100 )  * gc_amount);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal / 100) * post_gst_credit_cal);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
        amountinWords(transa_amount);
    }
    
    </script>';
                         $output .= '';

                    }
                    return $output;
                }
                break;
            default:
                {
                    $this->db->where('id', $productname);
                    $this->db->select('id, credit,gst,tds,tenure,cat_id');
                    $query = $this->db->get('tblproducts');
                    //echo $this->db->last_query();

                    foreach ($query->result() as $row) {
                        $output = '
            <div class=" col-md-4">
                        <small> (Transaction Type)</small>
                        <select data-none-selected-text="Select Employee" onchange="getTransactionType(this.value)" name="transaction_type" id="transaction_type" class="form-control " required> 
                            <option value="">Select Transaction Type</option>
                            <option value="FRESH">FRESH </option>
                            <option value="RENEWAL">RENEWAL </option>
                            <option value="LUMPSUM">LUMPSUM </option>
                            <option value="SIP">SIP </option>
                        </select>
                    </div>
            <div class=" col-md-4">
            <small> (Transaction Amount)</small>
            <input name="transaction_amount" id="transaction_amount" onkeyup="calculatePrice()" value="" placeholder="Transaction Amount" class="form-control" required />
			<small style="font-size: 11px;" id="tr_amt_words"></small>
			<input type="hidden" id="transaction_amount_words" name="transaction_amount_words">
            </div>
            
            <div class=" col-md-4 hidediv" id="trans_type_change">
            
            </div>
            <div class=" col-md-4 hidediv">
            <small> (Gross Credit Amount)</small>
            <input name="gross_credit_amount" id="gross_credit_amount" value="" placeholder="Gross Credit Amount" class="form-control " readonly/>
            </div>
            <input name="gst" id="gst" value="' . $row->gst . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Post GST Credit)</small>
            <input name="post_gst_credit" id="post_gst_credit" value="" placeholder="Post GST Credit" class="form-control" readonly/>
            </div>
            <input name="tds" id="tds" value="' . $row->tds . '"  class="form-control hidediv" />
            <div class=" col-md-4 hidediv">
            <small> (Net Credit)</small>
            <input name="net_credit" id="net_credit" value="" placeholder="Net Credit" class="form-control" readonly/>
            <input name="cat_id" id="cat_id" value="' . $row->cat_id . '" />
            </div>
            
            <script>function calculatePrice() {
            
            var transa_amount = $("input[name=\"transaction_amount\"]").val(),
            transaction_amount_cal =  transa_amount,
            cred = $("input[name=\"credit\"]").val(),
            credit_cal = cred.replace(/%/g, ""),
            gstin = $("input[name=\"gst\"]").val(),
            gst_cal = gstin,
            tdsin = $("input[name=\"tds\"]").val(),
            tds_cal = tdsin,
            gc_amount = ((credit_cal / 100 ) * transaction_amount_cal);
            
        $("input[name=\"gross_credit_amount\"]").val(gc_amount);
        
            post_gst_credit_1 = ((gst_cal / 100 )  * gc_amount);
            post_gst_credit_cal = gc_amount - post_gst_credit_1;
        
            
        $("input[name=\"post_gst_credit\"]").val(post_gst_credit_cal);
        
            net_credit_cal_1 = ((tds_cal / 100) * post_gst_credit_cal);
            net_credit_cal = post_gst_credit_cal - net_credit_cal_1;
        $("input[name=\"net_credit\"]").val(net_credit_cal);
        amountinWords(transa_amount);
    }
    
    </script>';
                         $output .= '';

                    }
                    return $output;
                }
        }


    }

function business_report1($start,$length)
    { $return = "result";
    
        // print_r($_POST);
        //Filter start
        $filterrm = $_POST['filterrm'];
        $transctiondatestart = (isset($_POST['transctiondatestart']) && trim($_POST['transctiondatestart'])!='')?date("Y-m-d",strtotime(trim($_POST['transctiondatestart']))):'';
        $transctiondateend = (isset($_POST['transctiondateend']) && trim($_POST['transctiondateend'])!='')?date("Y-m-d",strtotime(trim($_POST['transctiondateend']))):'';
        $filterstatus = $_POST['filterstatus'];//(isset($_POST['filterstatus']) && trim($_POST['filterstatus'])!='')?trim($_POST['filterstatus']):''; 
        $filterprotype = $_POST['filterprotype'];//(isset($_POST['filterprotype']) && trim($_POST['filterprotype'])!='')?trim($_POST['filterprotype']):'';
        //Filter end
        
        if(is_admin())
        {
            // $this->db->select('tblproduct_categories.name as pro_type,
            //                     tblproduct_companies.name as company_name,
            //                     tblproducts.product_name as product_name
                                
            //                     ');
            $this->db->select('tblbusiness.id as pro_id,tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            if (count($filterrm) > 0) {
                $this->db->where_in('converted_by', $filterrm);
            }
            if (count($filterstatus) > 0) {
                $this->db->where_in('status', $filterstatus);
            }
            
            if (count($filterprotype) > 0) {
                $this->db->where_in('product_type', $filterprotype);
            }
            if ($transctiondatestart != '' && $transctiondateend != '') {
                $this->db->where('transaction_date BETWEEN "'.$transctiondatestart.'" and "'. $transctiondateend.'"');
            }
        
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        elseif(is_sub_admin())
        {
            $this->db->select('tblbusiness.id as pro_id, tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            // $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name');
            $this->db->where('transaction_date !=', NULL);
            if (count($filterrm) > 0) {
                $this->db->where_in('converted_by', $filterrm);
            }
            if (count($filterstatus) > 0) {
                $this->db->where_in('status', $filterstatus);
            }
            
            if (count($filterprotype) > 0) {
                $this->db->where_in('product_type', $filterprotype);
            }
            if ($transctiondatestart != '' && $transctiondateend != '') {
                $this->db->where('transaction_date BETWEEN "'.$transctiondatestart.'" and "'. $transctiondateend.'"');
            }
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('tblbusiness.id as pro_id,tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            // $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name');
            $this->db->where('transaction_date !=', NULL);
            //$this->db->where_in('tblbusiness.converted_by', $arr);
            $this->db->where("tblbusiness.converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('tblbusiness.converted_by', $useraid);
            if (count($filterrm) > 0) {
                $this->db->where_in('converted_by', $filterrm);
            }
            if (count($filterstatus) > 0) {
                $this->db->where_in('status', $filterstatus);
            }
            
            if (count($filterprotype) > 0) {
                $this->db->where_in('product_type', $filterprotype);
            }
            if ($transctiondatestart != '' && $transctiondateend != '') {
                $this->db->where('transaction_date BETWEEN "'.$transctiondatestart.'" and "'. $transctiondateend.'"');
            }
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        
        
        //Filter start
        // $filterrm = $_POST['filterrm'];//(isset($_POST['filterrm']) && trim($_POST['filterrm'])!='')?trim($_POST['filterrm']):'';
        // $transctiondatestart = (isset($_POST['transctiondatestart']) && trim($_POST['transctiondatestart'])!='')?date("Y-m-d",trim($_POST['transctiondatestart'])):'';
        // $transctiondateend = (isset($_POST['transctiondateend']) && trim($_POST['transctiondateend'])!='')?date("Y-m-d",trim($_POST['transctiondateend'])):'';
        // $filterstatus = $_POST['filterstatus'];//(isset($_POST['filterstatus']) && trim($_POST['filterstatus'])!='')?trim($_POST['filterstatus']):''; 
        // $filterprotype = $_POST['filterprotype'];//(isset($_POST['filterprotype']) && trim($_POST['filterprotype'])!='')?trim($_POST['filterprotype']):'';
        
        
        //print_r($_POST);print_r($filterrm);echo 'sdsd';
        
        // if (count($filterrm) > 0) {
        //     $this->db->where_in('converted_by', $filterrm);
        // }
        // if ($filterstatus != '') {

        //         $this->db->where_in('status', $filterstatus);
        // }
        
        // if ($filterprotype != '') {

        //         $this->db->where_in('product_type', $filterprotype);
        // }
        // if ($transctiondatestart != '' && $transctiondateend != '') 
        // {

        //     $this->db->where('transaction_date BETWEEN "'.$transctiondatestart.'" and "'. $transctiondateend.'"');
        // }
        
        //filter end
        
        $this->db->limit($length,$start);
		$this->db->order_by('tblbusiness.id', 'DESC');
        $ss=$this->_checkRecords($this->db->get('tblbusiness'), $return);
        // print_r($this->db->last_query());
         return $ss;
    }
    
    
    function business_report($limit,$start)
    {    $return = "result";
        
        if(is_admin())
        {
            $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
           
            
            
        }
        elseif(is_sub_admin())
        {
            $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            //$this->db->where_in('tblbusiness.converted_by', $arr);
            $this->db->where("tblbusiness.converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('tblbusiness.converted_by', $useraid);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
               
             //  echo $this->db->count_all('tblbusiness');
        
           
       
           $this->db->limit($limit,$start);
          $ss=$this->_checkRecords($this->db->get('tblbusiness'), $return);
         //print_r($this->db->last_query());
         return $ss;
        
    }
    
    
    
    function business_report_get_total() 
    {
        return $this->db->count_all('tblbusiness');
    }
    
    function business_report_get_total1() 
    {
        //Filter start
        // $filterrm = $_POST['filterrm'];
        // $transctiondatestart = (isset($_POST['transctiondatestart']) && trim($_POST['transctiondatestart'])!='')?date("Y-m-d",strtotime(trim($_POST['transctiondatestart']))):'';
        // $transctiondateend = (isset($_POST['transctiondateend']) && trim($_POST['transctiondateend'])!='')?date("Y-m-d",strtotime(trim($_POST['transctiondateend']))):'';
        // $filterstatus = $_POST['filterstatus'];//(isset($_POST['filterstatus']) && trim($_POST['filterstatus'])!='')?trim($_POST['filterstatus']):''; 
        // $filterprotype = $_POST['filterprotype'];//(isset($_POST['filterprotype']) && trim($_POST['filterprotype'])!='')?trim($_POST['filterprotype']):'';
        // //Filter end
        // if (count($filterrm) > 0) {
        //         $this->db->where_in('converted_by', $filterrm);
        //     }
        //     if (count($filterstatus) > 0) {
        //         $this->db->where_in('status', $filterstatus);
        //     }
            
        //     if (count($filterprotype) > 0) {
        //         $this->db->where_in('product_type', $filterprotype);
        //     }
        //     if ($transctiondatestart != '' && $transctiondateend != '') {
        //         $this->db->where('transaction_date BETWEEN "'.$transctiondatestart.'" and "'. $transctiondateend.'"');
        //     }
        // return $this->db->count_all('tblbusiness');
        
        $return = "result";
    
        // print_r($_POST);
        //Filter start
        $filterrm = $_POST['filterrm'];
        $transctiondatestart = (isset($_POST['transctiondatestart']) && trim($_POST['transctiondatestart'])!='')?date("Y-m-d",strtotime(trim($_POST['transctiondatestart']))):'';
        $transctiondateend = (isset($_POST['transctiondateend']) && trim($_POST['transctiondateend'])!='')?date("Y-m-d",strtotime(trim($_POST['transctiondateend']))):'';
        $filterstatus = $_POST['filterstatus'];//(isset($_POST['filterstatus']) && trim($_POST['filterstatus'])!='')?trim($_POST['filterstatus']):''; 
        $filterprotype = $_POST['filterprotype'];//(isset($_POST['filterprotype']) && trim($_POST['filterprotype'])!='')?trim($_POST['filterprotype']):'';
        //Filter end
        
        if(is_admin())
        {
            // $this->db->select('tblproduct_categories.name as pro_type,
            //                     tblproduct_companies.name as company_name,
            //                     tblproducts.product_name as product_name
                                
            //                     ');
            $this->db->select('tblbusiness.id as pro_id,tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            if (count($filterrm) > 0) {
                $this->db->where_in('converted_by', $filterrm);
            }
            if (count($filterstatus) > 0) {
                $this->db->where_in('status', $filterstatus);
            }
            
            if (count($filterprotype) > 0) {
                $this->db->where_in('product_type', $filterprotype);
            }
            if ($transctiondatestart != '' && $transctiondateend != '') {
                $this->db->where('transaction_date BETWEEN "'.$transctiondatestart.'" and "'. $transctiondateend.'"');
            }
        
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        elseif(is_sub_admin())
        {
            $this->db->select('tblbusiness.id as pro_id, tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            // $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name');
            $this->db->where('transaction_date !=', NULL);
            if (count($filterrm) > 0) {
                $this->db->where_in('converted_by', $filterrm);
            }
            if (count($filterstatus) > 0) {
                $this->db->where_in('status', $filterstatus);
            }
            
            if (count($filterprotype) > 0) {
                $this->db->where_in('product_type', $filterprotype);
            }
            if ($transctiondatestart != '' && $transctiondateend != '') {
                $this->db->where('transaction_date BETWEEN "'.$transctiondatestart.'" and "'. $transctiondateend.'"');
            }
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('tblbusiness.id as pro_id,tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            // $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name');
            $this->db->where('transaction_date !=', NULL);
            //$this->db->where_in('tblbusiness.converted_by', $arr);
            $this->db->where("tblbusiness.converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('tblbusiness.converted_by', $useraid);
            if (count($filterrm) > 0) {
                $this->db->where_in('converted_by', $filterrm);
            }
            if (count($filterstatus) > 0) {
                $this->db->where_in('status', $filterstatus);
            }
            
            if (count($filterprotype) > 0) {
                $this->db->where_in('product_type', $filterprotype);
            }
            if ($transctiondatestart != '' && $transctiondateend != '') {
                $this->db->where('transaction_date BETWEEN "'.$transctiondatestart.'" and "'. $transctiondateend.'"');
            }
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        
        $ss=$this->_checkRecords($this->db->get('tblbusiness'), $return);
        //print_r($ss);
        // print_r($this->db->last_query());
         return count($ss);
    }
    
    
    
    function business_report_updates($return = "result")
    { 
        
        if(is_admin())
        {
            $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('investor_name', NULL);
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        elseif(is_sub_admin())
        {
            $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('investor_name', NULL);
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('investor_name', NULL);
            //$this->db->where_in('tblbusiness.converted_by', $arr);
            $this->db->where("tblbusiness.converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('tblbusiness.converted_by', $useraid);
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        
        return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    public function get_bussiness_filter($limit,$start)
    {
         $return = "result";
         
        $filterrm = $_GET['filterrm'];
        $from_date =$_GET['transctiondatestart'];
        $to_date = $_GET['transctiondateend'];
        
        
        $transctiondatestart = date("Y-m-d",strtotime($from_date)); 
        $transctiondateend = date("Y-m-d",strtotime($to_date));
        $filterstatus = $_GET['filterstatus'];
        $filterprotype = $_GET['filterprotype'];
        

        
        if ($filterrm != null) {

            $this->db->where_in('converted_by', $filterrm);
        }
        if ($filterrm == null ) {
            
            if(is_admin() || is_sub_admin())
            {
                
            }
            else
            {
               $arr=herapermission();
             $this->db->where("converted_by IN (".$arr.")",NULL, false); 
            }
            
           
        }
        if (isset($filterstatus)) {

                $this->db->where_in('status', $filterstatus);
        }
        
        if (isset($filterprotype)) {

                $this->db->where_in('product_type', $filterprotype);
        }
        if (isset($to_date) && $to_date != null  ) 
        {

            $this->db->where('transaction_date BETWEEN "'.$from_date.'" and "'. $to_date.'"');
        }
        
        $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
        $this->db->where('transaction_date !=', NULL);
        $this->db->where('transaction_date >=', $transctiondatestart);
		$this->db->where('transaction_date <=', $transctiondateend);
        $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
        $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
        $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
        $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
		$this->db->order_by('tblbusiness.id', 'DESC');
        
        
        
        
        return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    public function get_updated_bussiness_filter($return = "result")
    {
        $filterrm = $_GET['filterrm'];
        $from_date =$_GET['transctiondatestart'];
        $to_date = $_GET['transctiondateend'];
        
        
        $transctiondatestart = date("Y-m-d",strtotime($from_date)); 
        $transctiondateend = date("Y-m-d",strtotime($to_date));
        $filterstatus = $_GET['filterstatus'];
        $filterprotype = $_GET['filterprotype'];
        

        
        if ($filterrm != null) {

            $this->db->where_in('converted_by', $filterrm);
        }
        if ($filterrm == null ) {
            
            if(is_admin() || is_sub_admin())
            {
                
            }
            else
            {
               $arr=herapermission();
             $this->db->where("converted_by IN (".$arr.")",NULL, false); 
            }
            
           
        }
        if (isset($filterstatus)) {

                $this->db->where_in('status', $filterstatus);
        }
        
        if (isset($filterprotype)) {

                $this->db->where_in('product_type', $filterprotype);
        }
        if (isset($to_date) && $to_date != null  ) 
        {

            $this->db->where('transaction_date BETWEEN "'.$from_date.'" and "'. $to_date.'"');
        }
        
            $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('investor_name', NULL);
			$this->db->where('transaction_date !=', NULL);
			$this->db->where('transaction_date >=', $transctiondatestart);
			$this->db->where('transaction_date <=', $transctiondateend);
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }

    public function get_telerm($id = '', $select = '*', $return = "result")
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            return $this->db->get('tblstaff')->row();
        }
        $this->db->select($select);
        $this->db->from('tblstaff');
        $this->db->where('role', '40');
        return $this->_checkRecords($this->db->get(), $return);
    }

    public function get_avaliablewp($id = '', $select = '*', $return = "result")
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            return $this->db->get('tblstaff')->row();
            $leadsource = array(1, 5, 6, 8);
        }
        $this->db->select($select);
        $this->db->from('tblstaff');
        $wpids = array(5, 6, 12, 33);
        $this->db->where_in('role', $wpids);
        return $this->_checkRecords($this->db->get(), $return);
    }
    
    public function get_wealthperson($id = '', $select = '*', $return = "result")
    {
        
        $this->db->select($select);
        $this->db->from('tblstaff');
        $this->db->where_in('department_id', 12);
        return $this->_checkRecords($this->db->get(), $return);
    }
    
    public function getdatepermwp()
    {
        
        $today = date("Y-m-d");
        $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   namewp,  tt.*');
        $this->db->where('date <=', $today);
        $this->db->group_by("date");
        $this->db->join('tblstaff', 'tt.wp=tblstaff.staffid');
        $query = $this->db->get('tbldwrdatepermsn tt');
        if ($query->num_rows()) {
            return $query->result();
        }
        return false;
    }
    
    public function getassignedwp()
    {
        $today = date("Y-m-d");
        //$this->db->select('tt.*');
        //$this->db->where('date >=', $today);
        //$this->db->group_by("date");
        $query = $this->db->get('tblavailablewp');
        
         $last = $this->db->last_query();
         //echo $last;die;
        if ($query->num_rows()) {
            return $query->result();
        }else{
          return false;  
        }
        
    }

    public function getassignedwpById($date)
    {
        $this->db->select('tt.*');
        //$this->db->join('tblroles tr','tr.roleid=tt.department_id');
        $this->db->where(array('tt.id' => $date));
        $query = $this->db->get('tblavailablewp tt');
        if ($query->num_rows()) {
            return $query->row();
        }
        return false;
    }

    public function getassignedwpBydate($team_id)
    {
        $userid = $this->session->userdata('staff_user_id');
        $query = $this->db->get_where('tblavailablewp', array('date' => $team_id));
        if ($query->num_rows()) {
            return $query->result();
        }
    }

    public function get_availwplead($return = "result")
    {
        if (!is_admin()) {
            $toady = date("Y-m-d");
            $userid = $this->session->userdata('staff_user_id');
            $this->db->where('date', $toady);
            $this->db->where('telerm', $userid);
            $this->db->select('tblstaff.firstname, tblavailablewp.wp ');
            $this->db->join('tblstaff', 'tblstaff.staffid=tblavailablewp.wp');
            //$this->db->group_by('date');
            return $this->_checkRecords($this->db->get('tblavailablewp'), $return);
        } else {
            $toady = date("Y-m-d");
            $userid = $this->session->userdata('staff_user_id');
            $this->db->where('date', $toady);
            $this->db->where('telerm', $userid);
            $this->db->select('tblstaff.firstname, tblavailablewp.wp ');
            $this->db->join('tblstaff', 'tblstaff.staffid=tblavailablewp.wp');
            //$this->db->group_by('date');
            return $this->_checkRecords($this->db->get('tblavailablewp'), $return);

        }
    }
    
    function rmconverted($return = "result")
    {
        
        
        if(is_admin())
        {
            
        $this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
        $this->db->group_by('converted_by');
        $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        
        elseif(is_sub_admin())
        {
            
        $this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
        $this->db->group_by('converted_by');
        $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        elseif(herapermission())
        {
            $arr=herapermission();
			//echo $arr;exit;
            $useraid = $this->session->userdata('staff_user_id');
			$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
            //$this->db->where_in('converted_by',$arr );
            
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('converted_by',$useraid);
            $this->db->group_by('converted_by');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    function bustatus($return = "result")
    {
        
        
        if(is_admin())
        {
            
        $this->db->select('status');
        $this->db->group_by('status');
        }
        
        elseif(is_sub_admin())
        {
            
        $this->db->select('status');
        $this->db->group_by('status');
            
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('status');
            $this->db->group_by('status');
            //$this->db->where_in('converted_by',$arr );
            
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('converted_by',$useraid );
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    
    function protype($return = "result")
    {
        
        
        if(is_admin())
        {
            
        $this->db->select('product_type, tblproduct_categories.name as prod_name ');
        $this->db->group_by('product_type');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
        }
        
        elseif(is_sub_admin())
        {
            
        $this->db->select('product_type, tblproduct_categories.name as prod_name ');
        $this->db->group_by('product_type');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('product_type, tblproduct_categories.name as prod_name ');
            $this->db->group_by('product_type');
            //$this->db->where_in('converted_by',$arr );
            
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('converted_by',$useraid );
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            
        }
        return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    public function getassignedwpBymdate($team_id)
    {
        $userid = $this->session->userdata('staff_user_id');
        $this->db->select('tblstaff.firstname as wpname, tblavailablewp.wp');
        $this->db->where('date', $team_id);
        $this->db->where_in('telerm', $userid);
        $this->db->join('tblstaff', 'tblavailablewp.wp=tblstaff.staffid');
        $query = $this->db->get('tblavailablewp');
        print_r($this->db->last_query());
        $output ='<option value="">Select Available WP</option>';
                    foreach ($query->result() as $row) {
                        $output .= '
                    <option value="' . $row->wp . '">' . $row->wpname . '</option>';
                    }
                     $output .= '';
                    return $output;
    }
    
    public function get_leave_cat($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblleavecategory')->row();
        }

      // $statuses = $this->object_cache->get('dwr-all-statuses');

        if (!$statuses) {
           // $this->db->order_by('tblleavecategory', 'asc');

            $statuses = $this->db->get('tblleavecategory')->result_array();
          //  $this->object_cache->add('dwr-all-statuses', $statuses);
        }

        return $statuses;

    }
    
    public function getData( $tbl, $col, $operator )
      {
         $set = "";
         $x = 1;
         foreach ($col as $name => $value) {
            
            $set .= " $name = '$value' ";
            if ( $x < count($col) ) {
               $set .= $operator;
            }
            
            $x++;
            
         }
         
         
         $sql = $this->db->query("SELECT * FROM $tbl WHERE $set");
         $data = $sql->result();
         return $data;
      }
      
    public function get_leave_cat_custom($id,$start = '', $end = '')
    {
        
        $this->db->select('gender,marital_status,confirmation_date');
          $this->db->where('staffid', $id);
          $query = $this->db->get('tblstaff')->row();
          $confirmation_date = $query->confirmation_date;
          $gender = $query->gender;
          $marital_status = $query->marital_status;
          if($gender == 'Male')
          {
              
              $this->db->where('leave_gender !=', 'Female');
              
          }
          else
          {
              
              $this->db->where('leave_gender !=', 'Male');
          }
          if($marital_status == 'Married')
          {
              
              $this->db->where('leave_marital !=', 'Unmarried');
          }
          else
          {
              
              $this->db->where('leave_marital !=', 'Married');
          }
          
           if($confirmation_date == "0000-00-00")
         {
             $this->db->where('leave_category_id', '11');
         }
         
         
         
         
         
         
         
        $statuses = $this->db->get('tblleavecategory')->result();
        
        $stoff = $this->getData('tblstaff', array('staffid' => $id), 'null');
         /* Count The Time Duration Between Two Date Example => 2 Year, $months, 5 Days */
         $date1 = $stoff[0]->confirmation_date;
         
         $date2 = date('Y-m-d');
         $diff = abs(strtotime($date2) - strtotime($date1));
         
         $years = floor($diff / (365 * 60 * 60 * 24));
         $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
         $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
         /* Count The Time Duration Between Two Date Example => 2 Year, $months, 5 Days */
        
         if(!empty($end))
         {
             $time=strtotime($end);
             $cur_month = date('m',$time);
             $cur_year = date('Y',$time);
         }
         else
         {
             $cur_month = date('m');
             $cur_year = date('Y');
         }
         
            if($cur_month <= '3')
            {
                
                $fi_end_year = $cur_year;
                $fi_start_year = $cur_year-1;
            }
            else
            {
               $fi_end_year = $cur_year+1;
               $fi_start_year = $cur_year; 
            }
            
            $curdate=strtotime($date1);
            $mydate=strtotime('01-04-'.$fi_start_year);
            
           $date_end_cus = '31-03-'.$fi_end_year;
         $diff_custom = abs(strtotime($date_end_cus) - strtotime($date1));
         
         $years_custom = floor($diff_custom / (365 * 60 * 60 * 24));
         $months_custom = floor(($diff_custom - $years_custom * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
         $days_custom = floor(($diff_custom - $years_custom * 365 * 60 * 60 * 24 - $months_custom * 30 * 60 * 60 * 24) / (60 * 60 * 24));
          
         if($curdate <= $mydate)
         {
            $result = array();
         foreach ($statuses as $value) {
            $count = 0;
            
            if ( $value->leave_rate == '0' ) {
               $lcatid = $value->leave_category_id;
               $catLeaveCount = $this->getData('tblleaveapplication', array('user_id' => $id, 'leave_category_id' => $lcatid), 'AND');
               if ( count($catLeaveCount) == 0 ) {
                  $count = $value->leave_quota;
               } else {
                  $count = "0";
               }
            } else if ( $value->leave_rate == '1' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * $value->leave_quota;
               }
               $count = $count + 1 * $value->leave_quota;
               // $count=$count+$years*$value->leave_quota;
               //$count=$count+$months*$value->leave_quota;
            } else if ( $value->leave_rate == '2' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * 12 * $value->leave_quota;
               }
               $count = $count + $months * $value->leave_quota;
            }
            
            array_push($result, array('leave_category_id' => $value->leave_category_id, 'leave_category' => $value->leave_category, 'leave_quota' => $value->leave_quota, 'leave_rate' => $value->leave_rate, 'leave_carry' => $value->leave_carry, 'totalleave' => $count));
            // print_r($value->leave_category.'--'.$count.'<br/>'); 
         } 
         }
         else
         {
             $result = array();
         foreach ($statuses as $value) {
            $count = 0;
            
            if ( $value->leave_rate == '0' ) {
               $lcatid = $value->leave_category_id;
               $catLeaveCount = $this->getData('tblleaveapplication', array('user_id' => $id, 'leave_category_id' => $lcatid), 'AND');
               if ( count($catLeaveCount) == 0 ) {
                  $count = $value->leave_quota;
               } else {
                  $count = "0";
               }
            } else if ( $value->leave_rate == '1' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * (round(($value->leave_quota*$months_custom)/12));
               }
               $count = $count + 1 * (round(($value->leave_quota*$months_custom)/12));
               // $count=$count+$years*$value->leave_quota;
               //$count=$count+$months*$value->leave_quota;
            } else if ( $value->leave_rate == '2' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * 12 * (round(($value->leave_quota*$months_custom)/12));
               }
               $count = $count + $months * (round(($value->leave_quota*$months_custom)/12));
            }
            
            array_push($result, array('leave_category_id' => $value->leave_category_id, 'leave_category' => $value->leave_category, 'leave_quota' => $value->leave_quota, 'leave_rate' => $value->leave_rate, 'leave_carry' => $value->leave_carry, 'totalleave' => $count));
            // print_r($value->leave_category.'--'.$count.'<br/>'); 
         }
         }
        
        
        return $result;

    }
    
    
    public function exportalllead($lead_id_start = '', $lead_id_end = '', $return = "result")
    {
        $lead_id_start = $this->uri->segment(4);
        $lead_id_end = $this->uri->segment(5);
        $assigned_id = $this->uri->segment(6);
        
        
        if (!empty($id) && is_integer($id)) {
// $this->db->where('id', $id);
            $this->db->where('tblleads.id >', $lead_id_start);
            $this->db->where('tblleads.id <=', $lead_id_end);
        }
        $this->db->select('tblleads.*, tblleadsstatus.name as statusname');
        $this->db->where('tblleads.id >', $lead_id_start);
        $this->db->where('tblleads.id <=', $lead_id_end);
        $this->db->join('tblleadsstatus', 'tblleadsstatus.id=tblleads.status', 'left');
        $this->db->order_by('tblleads.id', 'ASC');
        if(is_admin() || is_headtrm())
        {
                $this->db->where('tblleads.assigned', $assigned_id);
                $red = $this->db->get('tblleads')->result_array();
                return $red;
            //return $this->_checkRecords($this->db->get('tblleads'), $return);
        }
        else
        {
            $this->db->where('tblleads.assigned', $assigned_id);
                $red = $this->db->get('tblleads')->result_array();
                return $red;
        }
    }
    
    public function get_dcrCallCount($workDate,$staffId)
    {
        if($staffId==0)
        {
             $this->db->where('tblleads.lastcontact', $workDate);
             $red = $this->db->get('tblleads')->result_array();
             return count($red);
        }
        else
        {
             $this->db->where('tblleads.assigned', $staffId);
             $this->db->where('tblleads.lastcontact', $workDate);
             $red = $this->db->get('tblleads')->result_array();
             return count($red);
        }
        
    }
    
    public function get_all_filter_meeting_by_trm($return = "result")
    {
        $rm_id = $_GET['trmfilter'];
        $meetingfilter = $_GET['meetingfilter'];

        if (isset($rm_id)) {

            $this->db->where_in('assigned_by', $rm_id);
        }
        if (isset($meetingfilter)) {
            
            
           
        }
                $this->db->where('status', 1);
        return $this->_checkRecords($this->db->get('tblleads'), $return);
    }

       public function get_manuStatus($table)
    {
        $userid = $this->session->userdata('staff_user_id'); 
        $this->db->distinct();
         $this->db->where('assigned', $userid);
       $this->db->select('lead_author_msstatus');
        $data = $this->db->get($table)->result();
        return $data;
    }
    public function get_createdDate($table)
    {
        $userid = $this->session->userdata('staff_user_id');     
        $this->db->distinct();
        $this->db->where('assigned', $userid);
        $this->db->select('lead_created_date');
        $data = $this->db->get($table)->result();
        return $data;
    }
      public function get_publishedearlier($table)
    {
        $userid = $this->session->userdata('staff_user_id'); 
        $this->db->distinct();
        $this->db->where('assigned', $userid);
        $this->db->select('lead_publishedearlier');
        $data = $this->db->get($table)->result();
        return $data;
    }
       public function get_adName($table)
    {   
        $userid = $this->session->userdata('staff_user_id');
        $this->db->distinct();
        $this->db->where('assigned', $userid);
        $this->db->select('lead_adname');
        $data = $this->db->get($table)->result();
        return $data;
    }
    //      *******************************  mayank_work        *******************************//
         public function get_language($table)
    {
         $userid = $this->session->userdata('staff_user_id'); 
        $this->db->distinct();
        $this->db->where('assigned', $userid);
        $this->db->select('lead_author_mslanguage');
        $data = $this->db->get($table)->result();
        
        return $data;
    }
        public function getleads()
    {
        
       
        $this->db->select('tt.*');
        $this->db->where('assigned',0);
        
        $query = $this->db->get('tblleads tt');
        if ($query->num_rows()) {
            return $query->result();
        }
        return false;
    }
    public function get_languageAdmin($table)
    {
         $userid = $this->session->userdata('staff_user_id'); 
        $this->db->distinct();
        //$this->db->where('assigned', $userid);
        $this->db->select('lead_author_mslanguage');
        $data = $this->db->get($table)->result();
        
         
        
        return $data;
    }
     public function get_manuStatusAdmin($table)
    {
        $userid = $this->session->userdata('staff_user_id'); 
        $this->db->distinct();
        // $this->db->where('assigned', $userid);
       $this->db->select('lead_author_msstatus');
        $data = $this->db->get($table)->result();
        return $data;
    }
      public function get_createdDateAdmin($table)
    {
        $userid = $this->session->userdata('staff_user_id');     
        $this->db->distinct();
        //$this->db->where('assigned', $userid);
        $this->db->select('lead_created_date');
        $data = $this->db->get($table)->result();
        return $data;
    }
     public function get_adNameAdmin($table)
    {   
        $userid = $this->session->userdata('staff_user_id');
        $this->db->distinct();
        $this->db->select('lead_adname');
        $data = $this->db->get($table)->result();
        return $data;
    }
     public function get_publisingConsultantAdmin($table)
    {
        $userid = $this->session->userdata('staff_user_id'); 
        $this->db->select('tblleads.s,CONCAT(tbls.firstname, " ", tbls.lastname) AS  firstname');
        $this->db->where('assigned !=', 0);
        $this->db->join('tblstaff AS tbls', 'tblleads.assigned=tbls.staffid');
        $this->db->distinct();
        $data = $this->db->get($table)->result();
        return $data;
       
        
    }
      public function get_publishedearlierAdmin($table)
    {
        $userid = $this->session->userdata('staff_user_id'); 
        $this->db->distinct();
        $this->db->where('assigned', $userid);
        $this->db->select('lead_publishedearlier');
        $data = $this->db->get($table)->result();
        return $data;
    }
    //  public function get_language($table)
    // {
    //      $userid = $this->session->userdata('staff_user_id'); 
    //     $this->db->distinct();
    //     $this->db->where('assigned', $userid);
    //     $this->db->select('lead_author_mslanguage');
    //     $data = $this->db->get($table)->result();
        
         
        
    //     return $data;
    // }
    public function get_leadstatus($table)
    {
       
        $this->db->where('status', 1);
        $data = $this->db->get($table)->result();
        return $data;
    }
      public function get_leadstatus1($table)
    {
       
        $this->db->where('status', 1);
        $data = $this->db->get($table)->result();
        return $data;
    }
     public function get_leadstatus2($table)
    {
       
        $this->db->where('status', 1);
        $data = $this->db->get($table)->result();
        return $data;
    }
    function uploadleadsbyPc()
    {
        $total_imported = 0;
        $status = $_POST['status'];
        $source = $_POST['source'];
        $file_name = $_POST['file_name'];
        //print_r($file_name);exit;
        $count = 0;
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
        $leadCount = 0;
        $c =0;
        $t=0;
       
        while ($csv_line = fgetcsv($fp, 10240)) {
            $count++;
             
            if ($count == 1) {
                continue;
            }
            for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                    $insert_csv = array();
                    $insert_csv['0'] = htmlspecialchars($csv_line[0]);//Db id
                    $insert_csv['1'] = htmlspecialchars($csv_line[1]); // sr. no.
                    $insert_csv['2'] =  htmlspecialchars($csv_line[2]); // name
                    $insert_csv['3'] =  htmlspecialchars($csv_line[3]); // phone
                    $insert_csv['4'] = htmlspecialchars($csv_line[4]);  // phone 2
                    $insert_csv['5'] =  htmlspecialchars($csv_line[5]);  // email
                    $insert_csv['6'] =  htmlspecialchars($csv_line[6]);  // Book lang
                    $insert_csv['7'] = htmlspecialchars($csv_line[7]);   // manu script
                    $insert_csv['8'] =  htmlspecialchars($csv_line[8]);  // remarks
                   
                    if(htmlspecialchars($csv_line[9])!= ''){
                        $getdate = htmlspecialchars($csv_line[9]);
                        $get_date=explode(" ",$getdate);

                        $findslash = strpos($get_date[0],"/");
                        if($findslash >=1){
                            $seprate_date=explode("/",$get_date[0]);
                            $insert_csv['9'] = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2];
                        }else{
                            $seprate_date=explode("-",$get_date[0]);
                            $insert_csv['9'] = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2];
                        } 
                        
                    }else{
                        $insert_csv['9'] = '';
                    }
                   
                    $cat = htmlspecialchars($csv_line[10]);
                    if($cat == 'a' || $cat == 'A'){
                        $insert_csv['10'] = 5; 
                    }
                    if($cat == 'b' || $cat == 'B'){
                        $insert_csv['10'] = 16;
                    }
                    if($cat == 'c' || $cat == 'C'){
                        $insert_csv['10'] = 30; 
                    }
                    if($cat == 'b+' || $cat == 'B+'){
                        $insert_csv['10'] =38; 
                    }
                    if($cat == 'NP' || $cat == 'np'){
                        $insert_csv['10'] = 32;
                    }
                    if($cat == 'acquired' || $cat == 'Acquired'){
                        $insert_csv['10'] = 39;
                    }
                    if($cat == 'anattended' || $cat == 'UnAttended'){
                        $insert_csv['10'] = 40;
                    }
                    if($cat == 'scrap' || $cat == 'Scrap'){
                        $insert_csv['10'] = 41;
                    }
                    
                    $insert_csv['11'] = htmlspecialchars($csv_line[11]);  // Next calling
                    if(htmlspecialchars($csv_line[11])!= ''){
                       
                        $getdate = htmlspecialchars($csv_line[11]);
                        $get_date=explode(" ",$getdate);
                        $findslash1 = strpos($get_date[0],"/");
                        if($findslash1 >=1){
                            $seprate_date=explode("/",$get_date[0]);
                            $insert_csv['11'] = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2];
                        }else{
                            $seprate_date=explode("-",$get_date[0]);
                            $insert_csv['11'] = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2];
                        } 
                        //$seprate_date=explode("-",$get_date[0]);
                        //$insert_csv['11'] = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2]; 
                        //$newDate2 = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2]; 
                       // $insert_csv['11'] = date("Y/d/m", strtotime($newDate2)); 
                    }else{
                        $insert_csv['11'] = '';
                    }
                    $insert_csv['12'] = htmlspecialchars($csv_line[12]);  // book format
                    $insert_csv['13'] = htmlspecialchars($csv_line[13]);  // book title
                    $insert_csv['14'] = htmlspecialchars($csv_line[14]);  // created date
                    if(htmlspecialchars($csv_line[14])!= ''){
                        $getdate = htmlspecialchars($csv_line[14]);
                        $get_date=explode(" ",$getdate);
                        $findslash2 = strpos($get_date[0],"/");
                        if($findslash2 >=1){
                            $seprate_date=explode("/",$get_date[0]);
                            $insert_csv['14'] = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2];
                        }else{
                            $seprate_date=explode("-",$get_date[0]);
                            $insert_csv['14'] = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2];
                        } 
                        //$seprate_date=explode("-",$get_date[0]);
                        //$insert_csv['14'] = $seprate_date[0].'/'.$seprate_date[1].'/'.$seprate_date[2];  
                        
                        //$insert_csv['14'] = date("Y/d/m", strtotime($newDate));   
                    }else{
                        $insert_csv['14'] = '';
                    }
                    $insert_csv['15'] = htmlspecialchars($csv_line[15]);  // ads name
                    $insert_csv['16'] = htmlspecialchars($csv_line[16]); // publishing earlier
                    $lead_id = $insert_csv['0'];
                    
                   $all_remarks =  $this->db->order_by('id','DESC')->get_where('tblleadremark',array('lead_id'=>$lead_id))->row();

                    $remarks_array =  explode("|",$insert_csv['8']);
                       $count =  count($remarks_array);
                       $new_remarks = '';
                        if ($count > 0) {
                            $new_remarks = $remarks_array[$count-1];
                            
                          }else{}
                    if ($all_remarks->remark == $new_remarks) {
                   }else{
                     $useraid = $this->session->userdata('staff_user_id');
                            $d_array = array(
                                'lead_id' => $lead_id,
                                'remark' => $new_remarks,
                                'added_by' => $useraid
                            );
                            $this->db->insert('tblleadremark',$d_array);
                   }
                        
                   // Data code
                   $data = array(
                        'lead_author_name' => $insert_csv['2'],
                        'phonenumber' => $insert_csv['3'],
                        'otherphonenumber' => $insert_csv['4'],
                        'email' => $insert_csv['5'],
                        'lead_author_mslanguage' => $insert_csv['6'], 
                        'lead_author_msstatus' => $insert_csv['7'],
                        
                        'ImEx_leadRemarks' => $insert_csv['8'],  
                        'description' => $insert_csv['8'],  

                        'lead_callingdate' => $insert_csv['9'],
                        'lead_category_id' => $insert_csv['10'], 
                            
                        'ImEx_NextcallingDate' => $insert_csv['11'],    
                        'next_calling' => $insert_csv['11'],    
                        'lead_bookformat' => $insert_csv['12'],     
                        'lead_booktitle' => $insert_csv['13'], 
                    // 'lead_created_date' => $insert_csv['14'], 
                        'ImEx_CreatedAt' => $insert_csv['14'], 
                        'lead_adname' => $insert_csv['15'],
                        'lead_publishedearlier' => $insert_csv['16']     
                    );

                
                    $this->db->where('id', $lead_id);
                    $this->db->update('tblleads', $data);
                  // echo $this->db->last_query();die;
                    
                }
           //$total_imported = 'import successfully';
         
        }
        // echo $lead_id;
        //  print_r($data);exit;
              
        
        fclose($fp) or die("can't close file");
        //$data['success'] = "success";
       // set_alert('success', _l('import_total_imported', $total_imported));
        return $data;
    }
        public function getLeadData($leadId){

        $this->db->select('*');

        $this->db->from('tblleads');

        $this->db->where('id', $leadId);

        $result = $this->db->get();

        return $result->row();

    }
    function getservies_standard($package, $book_type){

        //echo $book_type;exit;

        $this->db->select('tblpackagesubservices.serviceid,tblpackageservices.service_name');

        $this->db->from('tblpackagesubservices');

        $this->db->join('tblpackageservices', 'tblpackageservices.id = tblpackagesubservices.serviceid');

        $this->db->where('tblpackagesubservices.package_value', $package);
        $this->db->where('tblpackageservices.packageid', 1);

        $this->db->where('tblpackagesubservices.book_type', $book_type); 

        //$this->db->group_by('tblpackagesubservices.subservice'); 

        $result = $this->db->get(); 

        $result = $result->result_array();

        //echo $this->db->last_query();exit;

        return array_map("unserialize", array_unique(array_map("serialize", $result)));

                

        //return $result->result();

    }
    public function getservies($package, $book_type){


        $this->db->select('tblpackagesubservices.serviceid,tblpackageservices.service_name');

        $this->db->from('tblpackagesubservices');

        $this->db->join('tblpackageservices', 'tblpackageservices.id = tblpackagesubservices.serviceid');

        $this->db->where('tblpackagesubservices.packageid', $package);

        $this->db->where('tblpackagesubservices.book_type', $book_type); 

        //$this->db->group_by('tblpackagesubservices.subservice'); 

        $result = $this->db->get(); 

        $result = $result->result_array();
           //   $this->db->last_query();

        return array_map("unserialize", array_unique(array_map("serialize", $result)));

    }
    public function getsubservies($services, $book_type){

// return  "test".$services.$book_type;die;
        $this->db->select('*');

        $this->db->from('tblpackagesubservices');

        $this->db->where('serviceid', $services); 

        $this->db->where('book_type', $book_type); 

        $result = $this->db->get(); 

        return $result->result();

    }
    public function saveLeadData($data){

        return $this->db->insert('tblleads',$data);

    }
    public function updtaeLeadData($package_id, $data){

        $this->db->where('id', $package_id);

        $result = $this->db->update('tblleads',$data);
    // echo $this->db->last_query(); exit;
        return $result;

    }
    public function getlead_create_Data($leadId){

        $this->db->select('*');

        $this->db->from('tblleads');

        $this->db->where('id', $leadId);

        $result = $this->db->get();

        //echo $this->db->last_query();exit;

        return $result->row();

    }

    function getPackageService($services){

        $services = explode(", ", $services);

        $this->db->select('*');

        $this->db->from('tblpackageservices');

        $this->db->where_in('id', $services); 

        $result = $this->db->get(); 

        return $result->result();

    }
        function getPackageSubService($sub_services){

        $sub_services = explode(", ", $sub_services);

        $this->db->select('*');

        $this->db->from('tblpackagesubservices');

        $this->db->where_in('id', $sub_services); 

        $result = $this->db->get(); 

        return $result->result();

    }
    function getserviesedit($service){

        //print_r($service);

        $this->db->select('*');

        $this->db->from('tblpackageservices');

        $this->db->where_in('id', $service); 

        $result = $this->db->get(); 

        //echo $this->db->last_query();exit;

        return $result->result();

    }
    
    function sub_servicesedit($service, $sub_services, $book_type){

        $this->db->select('*');

        $this->db->from('tblpackagesubservices');

        $this->db->where_in('id', $sub_services); 

        $this->db->where('serviceid', $service); 

        $this->db->where('book_type', $book_type); 

        $this->db->where('packageid', 2);

        $result = $this->db->get(); 

        //echo $this->db->last_query();exit;

        return $result->result();

    }
    function sub_servicesedit_data($service, $sub_services, $book_type){

        $this->db->select('*');

        $this->db->from('tblpackagesubservices');

        $this->db->where_in('id', $sub_services); 

        $this->db->where('serviceid', $service); 

        $this->db->where('book_type', $book_type); 

        $this->db->where('packageid', 1);

        $result = $this->db->get(); 

        //echo $this->db->last_query();exit;

        return $result->result();

    }
     function getservies_coustize_standard($package, $book_type,$package_details){

        //echo $book_type;exit;

        $this->db->select('tblpackagesubservices.serviceid,tblpackageservices.service_name');

        $this->db->from('tblpackagesubservices');

        $this->db->join('tblpackageservices', 'tblpackageservices.id = tblpackagesubservices.serviceid');
         $this->db->where('tblpackageservices.packageid', $package_details); 
        $this->db->where('tblpackagesubservices.package_value', $package);

        $this->db->where('tblpackagesubservices.book_type', $book_type); 
       

        //$this->db->group_by('tblpackagesubservices.subservice'); 

        $result = $this->db->get(); 

        $result = $result->result_array();

        //echo $this->db->last_query();exit;

        return array_map("unserialize", array_unique(array_map("serialize", $result)));

                

        //return $result->result();

    }
    
   
    function work_report_get_total() 
    {
        
        // return $this->db->count_all('tblworkreport');
        //print_r($_POST);die;
        $return = "result";
        $start_date = (isset($_POST['start_date']) && trim($_POST['start_date'])!='')?date("Y-m-d",strtotime(trim($_POST['start_date']))):'';//$_POST['start_date'];die;
        $end_date = (isset($_POST['end_date']) && trim($_POST['end_date'])!='')?date("Y-m-d",strtotime(trim($_POST['end_date']))):'';//$_POST['end_date'];
        $staff_name = $_POST['staff_name'];
        //$stf = $_POST['staff_name'];die;
       // echo $stf[0];

        
        if (count($staff_name) > 0) {

            $this->db->where_in('staff_id', $staff_name);
        }
        // else  {
            
        //     if(is_admin())
        //     {
                
        //     }
        //     else
        //     {
        //       $arr=herapermission();
            
        //      $this->db->where("staff_id IN (".$arr.")",NULL, false);  
        //     }
            
           
        
        // }
    
        
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {

            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
        
       //echo $limit."-----".$start;exit;
            $this->db->select('tbl_pc_dwr.id');
            $this->db->join('tblstaff', 'tbl_pc_dwr.staff_id= tblstaff.staffid');
			$this->db->order_by('tblstaff.staffid', 'desc');
            $this->db->limit($limit,$start);
            // $this->db->limit(500,0);
			
            $ss = $this->db->get('tbl_pc_dwr');
			
			/* $this->db->select('tblworkreport.*,tblstaff.*');
			$this->db->from('tblworkreport');
			//$this->db->join('credentials', 'tblanswers.answerid = credentials.cid', 'right outer'); 
			$this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
			$query = $this->db->get();
			$data = $query->result();

			
			print_r($this->db->last_query);die; */
			
            // $ss2 = $this->_checkRecords($this->db->get('tblworkreport'), $return);
            // print_r($ss);
            // echo $ss->num_rows();
            // print_r($ss->result);
            
            // print_r($this->db->last_query());
            return $ss->num_rows();
    }
       public function get_pc($return = "result")
    {
            $this->db->select('staffid,firstname');
            	$this->db->where('role_id',64);
            return $this->_checkRecords($this->db->get('tblstaff'), $return);

    }
      public function get_pc_category($return = "result")
    {
            $this->db->select('id,name');
            	$this->db->where('status',1);
            return $this->_checkRecords($this->db->get('tblleadsstatus'), $return);

    }
    public function getrecordCount($search = '',$search_cat='',$start_date = '',$end_date='',$staff_name = '') {
        $id = $this->session->userdata('staff_user_id');
        $this->db->select('count(*) as allcount');
        $this->db->from('tblleads');
        if (!(is_admin())) {
            if ($id == 34) {
           
            }else{
                $this->db->where('assigned',$id);
            }
        //   $this->db->where('assigned', $id);
        }
        
        if($search != ''){
           $this->db->like('lead_author_name', $search);
      $this->db->or_like('phonenumber', $search);
      $this->db->or_like('lead_adname', $search);
      $this->db->or_like('lead_author_msstatus', $search);
      $this->db->or_like('lead_publishedearlier', $search);
      $this->db->or_like('email', $search);
      $this->db->or_like('language',$search);
      $this->db->or_like('lead_callingdate',$search);
      $this->db->or_like('ImEx_NextcallingDate',$search);
      $this->db->or_like('lead_created_date',$search);
      $this->db->or_like('lead_bookformat',$search);
      $this->db->or_like('lead_booktitle',$search);
      $this->db->or_like('otherphonenumber',$search);
        }
        if($search_cat != ''){
            if($search_cat == 'no_category'){
                $search_cat = 'null';
                $this->db->where_in('lead_category_id', $search_cat);
            }else{
               $this->db->where_in('lead_category_id', $search_cat); 
            }
        }
        if($staff_name != ''){
           
               $this->db->where_in('assigned', $staff_name); 
           
        }

          $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
 if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {

            //$this->db->where('lead_created_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
            $this->db->where('lead_created_date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }

        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]['allcount'];
    }
    public function getcontactCount($search = '',$search_cat='',$start_date = '',$end_date='',$staff_name = '') {
        $id = $this->session->userdata('staff_user_id');
        /* Contact data count*/
        $this->db2 = $this->load->database('secend_db', TRUE);
        $this->db2->select('count(*) as allcount');
        $this->db2->from('refer_friends');
        
        if($search != ''){
            $this->db2->like('name', $search);
            $this->db2->or_like('phone', $search);
            $this->db2->or_like('email', $search);
        }
        $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) {
            $this->db2->where('created_at BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }
        
        $query = $this->db2->get();
        $result = $query->result_array();
        
        /* Refer Friend data count*/
        $this->db2 = $this->load->database('secend_db', TRUE);
        $this->db2->select('count(*) as allcount');
        $this->db2->from('refer_friends');
        
        if($search != ''){
            $this->db2->like('name', $search);
            $this->db2->or_like('phone', $search);
            $this->db2->or_like('email', $search);
        }
        $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) {
            $this->db2->where('created_at BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }
        
        $query1 = $this->db2->get();
        $result1 = $query1->result_array();
        
        
        
        return $result[0]['allcount'];
    }
    public function leedsData($limit, $start,$search_text='',$search_cat='',$start_date = '',$end_date='', $staff_name = ''){
        $useraid = $this->session->userdata('staff_user_id');
        // print_r($useraid);die;
        $this->load->database();
        $this->db->select('id,lead_author_name,phonenumber,	lead_adname,lead_author_msstatus,lead_publishedearlier,email,lead_author_mslanguage,lead_callingdate,lead_category_id,description,ImEx_NextcallingDate,lead_created_date,lead_bookformat,lead_booktitle,otherphonenumber,craete_package,create_other_package,assigned');
        if (!(is_admin())) {
            if ($useraid == 34) {
           
            }else{
                $this->db->where('assigned',$useraid);
            }
         
        }
        
         $this->db->order_by('lead_approve_current_date','ASC');
        
        $this->db->limit($limit, $start);
      if($search_text != ''){
           $this->db->like('lead_author_name', $search_text);
      $this->db->or_like('phonenumber', $search_text);
      $this->db->or_like('lead_adname', $search_text);
      $this->db->or_like('lead_author_msstatus', $search_text);
      $this->db->or_like('lead_publishedearlier', $search_text);
    //   $this->db->or_like('email', $search_text);
      $this->db->or_like('language',$search_text);
      $this->db->or_like('lead_callingdate',$search_text);
      $this->db->or_like('ImEx_NextcallingDate',$search_text);
      $this->db->or_like('lead_created_date',$search_text);
      $this->db->or_like('lead_bookformat',$search_text);
      $this->db->or_like('lead_booktitle',$search_text);
      $this->db->or_like('otherphonenumber',$search_text);
        }
        if($search_cat != ''){
            if($search_cat == 'no_category'){
                $search_cat = 'null';
                $this->db->where_in('lead_category_id', $search_cat);
            }else{
               $this->db->where_in('lead_category_id', $search_cat); 
            }
        }
        if($staff_name != ''){
           
               $this->db->where_in('assigned', $staff_name); 
           
        }
            $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
 if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {

           // $this->db->where('lead_created_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
           $this->db->where('lead_created_date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }
       
        $query = $this->db->get('tblleads');
        // echo $this->db->last_query();die;
        return $query->result();
    }
    public function contactData($limit, $start,$search_text='',$search_cat='',$start_date = '',$end_date='', $staff_name = ''){
        $useraid = $this->session->userdata('staff_user_id');
        
        $this->db2 = $this->load->database('secend_db', TRUE);
        $this->db2->select('*');
        $this->db2->order_by('created_at','ASC');
        $this->db2->limit($limit, $start);
        if($search_text != ''){
            $this->db2->like('name', $search);
            $this->db2->or_like('phone', $search);
            $this->db2->or_like('email', $search); 
        }
        
        $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) {
            $this->db2->where('created_at BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }
        
        $query = $this->db2->get('refer_friends');
        return $query->result();
    }
     public function getSimilarData($auname,$phno,$adname,$ms_status,$pub_early,$email,$lang,$call_date,$cate,$nc_date,$create_date,$formate,$title,$other_phno){
      $this->load->database();
          $staff_id = $_SESSION['staff_user_id'];
      $this->db->select('id,lead_author_name,phonenumber,lead_adname,lead_author_msstatus,lead_publishedearlier,email,language,lead_callingdate,lead_category_id,description,ImEx_NextcallingDate,lead_created_date,lead_bookformat,lead_booktitle,otherphonenumber,craete_package');
    if(!(is_admin())){
      $this->db->where('assigned',$staff_id);
  }
      if($auname){
         $this->db->like('lead_author_name', $auname);
      }

      if($phno){
         $this->db->like('phonenumber', $phno);
      }
      
      if($adname){
         $this->db->like('lead_adname', $adname);
      }
      
      if($ms_status){
         $this->db->like('lead_author_msstatus', $ms_status);
      }
      
      if($pub_early){
         $this->db->like('lead_publishedearlier', $pub_early);
      }
      if($email){
         $this->db->like('email', $email);
      }
      if($lang){
         $this->db->like('language',$lang);
      }
      if($call_date){
         $this->db->like('lead_callingdate',$call_date);
      }
      if($cate){
         // $this->db->like('lead_category_id',$cate);
      }
      if($nc_date){
         $this->db->like('ImEx_NextcallingDate',$nc_date);
      }
      if($create_date){
         $this->db->like('lead_created_date',$create_date);
      }
      if($formate){
         $this->db->like('lead_bookformat',$formate);
      }
      if($title){
         $this->db->like('lead_booktitle',$title);
      }
      if($other_phno){
         $this->db->like('otherphonenumber',$other_phno);
      }


      $query = $this->db->get('tblleads');
      //print_r($query->result());
      return $query->result();
     }
      public function globalSearch($val){
      
$staff_id = $_SESSION['staff_user_id'];
      $this->db->select('id,lead_author_name,phonenumber,lead_adname,lead_author_msstatus,lead_publishedearlier,email,language,lead_callingdate,lead_category_id,description,ImEx_NextcallingDate,lead_created_date,lead_bookformat,lead_booktitle,otherphonenumber,craete_package');
        if(!(is_admin())){
      $this->db->where('assigned',$staff_id);
  }
      $this->db->like('lead_author_name', $val);
      $this->db->or_like('phonenumber', $val);
      $this->db->or_like('lead_adname', $val);
      $this->db->or_like('lead_author_msstatus', $val);
      $this->db->or_like('lead_publishedearlier', $val);
      $this->db->or_like('email', $val);
      $this->db->or_like('language',$val);
      $this->db->or_like('lead_callingdate',$val);
      $this->db->or_like('ImEx_NextcallingDate',$val);
      $this->db->or_like('lead_created_date',$val);
      $this->db->or_like('lead_bookformat',$val);
      $this->db->or_like('lead_booktitle',$val);
      $this->db->or_like('otherphonenumber',$val);

     
      $query = $this->db->get('tblleads');
      return $query->result();

     }
      public function filter_by_all(){
      
$staff_id = $_SESSION['staff_user_id'];

 $task_type = $_POST['tasktypefilter'];
        $start_date = (isset($_POST['start_date']) && trim($_POST['start_date'])!='')?date("Y-m-d",strtotime(trim($_POST['start_date']))):'';//$_POST['start_date'];die;
        $end_date = (isset($_POST['end_date']) && trim($_POST['end_date'])!='')?date("Y-m-d",strtotime(trim($_POST['end_date']))):'';//$_POST['end_date'];
        $staff_name = $_POST['staff_name'];
   
        if ($staff_name != null) {

            $this->db->where_in('assigned', $staff_name);
        }
        if(!(is_admin())){
            $useraid = $this->session->userdata('staff_user_id');
               $this->db->where('tblleads.assigned', $useraid);
        }
       
        if ($task_type != null) {
            if($task_type == 'no_category'){
                $task_type = 'null';
                $this->db->where_in('lead_category_id', $task_type);
            }else{}
            $this->db->where_in('lead_category_id', $task_type);
        }
        
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {

            $this->db->where('lead_created_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
      $this->db->select('id,lead_author_name,phonenumber,lead_adname,lead_author_msstatus,lead_publishedearlier,email,language,lead_callingdate,lead_category_id,description,ImEx_NextcallingDate,lead_created_date,lead_bookformat,lead_booktitle,otherphonenumber,craete_package');
     

     
      $query = $this->db->get('tblleads');
      return $query->result();

     }
     function upload_previousleads()
    {
        $total_imported = 0;
        $status = $_POST['status'];
        $source = $_POST['source'];
        $file_name = $_POST['file_name'];
        
        $count = 0;
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
        $leadCount = 0;
        $c =0;
        $t=0;
        $mm = 0;
        $n = 0;
        $currentDate = date("Y-m-d h:i:s");
        while ($csv_line = fgetcsv($fp, 10240)) {
            $count++;
            if ($count == 1) {
                continue;
            }
            // $string = str_replace('/\s+/', '', $csv_line['7']);
            // $str = str_ireplace (' ', '', $csv_line['7']);
            $this->db->select('*')->from('tblleads');
            $this->db->where('phonenumber',$csv_line['7']);
            $query = $this->db->get()->result();
            // echo '<pre>';
            // print_r($query);
            $tot=count($query);
            if($tot >=1){
               $found = $query[$tot]->phonenumber;
               $tot++;
            //    print_r($found);
            }
            // echo $this->db->last_query();die;
          
            if($found == ""){
                print_r($found);
                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {

                    //if (!empty($csv_line[1])) {
                        $insert_csv = array();
                        $insert_csv['0'] = htmlspecialchars($csv_line[0]);
                        $insert_csv['1'] = htmlspecialchars($csv_line[1]);
                        $insert_csv['2'] =  htmlspecialchars($csv_line[2]);
                        $insert_csv['3'] =  htmlspecialchars($csv_line[3]);
                        $insert_csv['4'] = htmlspecialchars($csv_line[4]);
                        $insert_csv['5'] =  htmlspecialchars($csv_line[5]);
                        $insert_csv['6'] =  htmlspecialchars($csv_line[6]);
                        $insert_csv['7'] = htmlspecialchars($csv_line[7]);
                        
                       
                    //}
                }
                $i++;
               
                $leadCount++;
                $str = $insert_csv['6'];
                $assigned = 200;
                $this->db->select('*')->from('tblleads');
                $this->db->where('phonenumber',$insert_csv['7']);
                $query1 = $this->db->get()->row();
                // echo '<pre>';
                // print_r($query1);
                if ($query1) {
                    $mm++;
                    echo 'no'.$mm;
                    
                }else{
                    $total_imported++;
                    $n++;
                    echo 'yes'.$n.'<br>';
                    $datavv = array(
                        'lead_created_date' => $insert_csv['0'],
                        // 'lead_ad_id' => $insert_csv['1'],
                        //'leadId' => $insert_csv['1'],
                        'lead_adname' => $insert_csv['1'],
                        'lead_author_mslanguage' => $insert_csv['2'],
                        'lead_publishedearlier' => $insert_csv['3'],
                        'lead_author_msstatus' => $insert_csv['4'],
                       
                        
                        'email' => $insert_csv['5'],
                       
                        'lead_author_name' => $str,
                        'phonenumber' => $insert_csv['7'],
                        'assigned' => $assigned
                        
                    );
                }
               
                // echo '<pre>';
                // print_r($datavv);
                     
                 $this->db->insert('tblleads', $datavv);
                
                if($c == $result){
                    $c=0;  
                }else{
                    $c++;  
                }
            }    
        }
        // die;
        
        fclose($fp) or die("can't close file");
        
        

        $data['success'] = "success";
        set_alert('success', _l('import_total_imported', $total_imported));
        return $data;
    }
    function upload_create_package()
    {
        $total_imported = 0;
        $status = $_POST['status'];
        $source = $_POST['source'];
        $file_name = $_POST['file_name'];
        
        $count = 0;
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
        $leadCount = 0;
        $c =0;
        $t=0;
        $mm = 0;
        $n = 0;
        $currentDate = date("Y-m-d h:i:s");
        while ($csv_line = fgetcsv($fp, 10240)) {
            $count++;
            if ($count == 1) {
                continue;
            }
            // $string = str_replace('/\s+/', '', $csv_line['7']);
            // $str = str_ireplace (' ', '', $csv_line['7']);
            // $this->db->select('*')->from('tblleads');
            // $this->db->where('phonenumber',$csv_line['7']);
            // $query = $this->db->get()->result();
            // echo '<pre>';
            // print_r($query);
            // $tot=count($query);
            // if($tot >=1){
            //    $found = $query[$tot]->phonenumber;
            //    $tot++;
            // //    print_r($found);
            // }
            // echo $this->db->last_query();die;
          
            // if($found == ""){
                // print_r($found);
                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {

                    //if (!empty($csv_line[1])) {
                        $insert_csv = array();
                       
                        // $insert_csv['1'] = htmlspecialchars($csv_line[1]);
                        $insert_csv['2'] =  htmlspecialchars($csv_line[2]);//lead_acquired_date
                        $insert_csv['3'] =  htmlspecialchars($csv_line[3]);//lead_author_name
                        $insert_csv['4'] = htmlspecialchars($csv_line[4]);//lead_author_msstatus
                        $insert_csv['5'] =  htmlspecialchars($csv_line[5]);//lead_package_detail
                        $insert_csv['6'] =  htmlspecialchars($csv_line[6]);//lead_book_type
                        $insert_csv['7'] = htmlspecialchars($csv_line[7]);//lead_package_name
                        $insert_csv['8'] = htmlspecialchars($csv_line[8]);//lead_package_value
                        $insert_csv['9'] = htmlspecialchars($csv_line[9]);//lead_package_discount
                        $insert_csv['10'] = htmlspecialchars($csv_line[10]);//lead_packg_gst
                        $insert_csv['11'] = htmlspecialchars($csv_line[11]);//lead_packg_totalamount
                        $insert_csv['12'] = htmlspecialchars($csv_line[12]);//lead_package_cost
                        $insert_csv['13'] = htmlspecialchars($csv_line[13]);//email
                        $insert_csv['14'] = htmlspecialchars($csv_line[14]);//phonenumber

                        
                       
                    //}
                }
                $i++;
               
                $leadCount++;
            
                    $total_imported++;
                   if ($insert_csv['5'] == 'Standard') {
                       $lead_package_detail = 1;
                   }elseif ($insert_csv['5'] == 'Customized') {
                    $lead_package_detail = 2;
                   }elseif ($insert_csv['5'] == 'Standard Customized') {
                    $lead_package_detail = 3;
                   }else{}
                   $multiClause = array('email' => $insert_csv['13'], 'phonenumber' => $insert_csv['14']);
                   $this->db->where($multiClause);	
                 $all_data =   $this->db->get('tblleads')->row();
                if ($all_data) {
                    $datavv = array(
                        'lead_acquired_date' => $insert_csv['2'],
                        'lead_author_msstatus' => $insert_csv['4'],
                        'lead_package_detail' => $lead_package_detail,
                        'lead_book_type' => $insert_csv['6'],
                        'lead_bookformat' => $insert_csv['6'],
                        'lead_package_name' => $insert_csv['7'],
                        'lead_packge_value' => $insert_csv['8'],
                        'lead_packge_discount' => $insert_csv['9'],
                        'lead_packg_gst' => $insert_csv['10'],
                        'lead_packg_totalamount' => $insert_csv['11'],
                        'lead_package_cost' => $insert_csv['12'],
                        'craete_package' =>1
                    );
             
               
            //   print_r($all_data);
            $this->db->where('id',$all_data->id);
                $this->db->update('tblleads',$datavv);
                echo $this->db->last_query();die;
                }else{
                    
                }
                    
                // echo '<pre>';
                // print_r($datavv);
                     
                //  $this->db->insert('tblleads', $datavv);
                
                
            }    
        // }
        // die;
        
        fclose($fp) or die("can't close file");
        
        

        $data['success'] = "success";
        set_alert('success', _l('import_total_imported', $total_imported));
        return $data;
    }
    public function getfaqCount($search = '') {
        $id = $this->session->userdata('staff_user_id');
        $this->db->select('count(*) as allcount');
        $this->db->from('faq');
        
        
        if($search != ''){
            $this->db->like('question', $search);
            $this->db->or_like('answer', $search);
        }
 

        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]['allcount'];
    }
    public function faq_Data($limit, $start,$search = ''){
        $useraid = $this->session->userdata('staff_user_id');
        $this->load->database();
        // $this->db->select('id,lead_author_name,phonenumber,	lead_adname,lead_author_msstatus,lead_publishedearlier,email,lead_author_mslanguage,lead_callingdate,lead_category_id,description,ImEx_NextcallingDate,lead_created_date,lead_bookformat,lead_booktitle,otherphonenumber,craete_package');
        
        
        $this->db->limit($limit, $start);
        if($search != ''){
            $this->db->like('question', $search);
            $this->db->or_like('answer', $search);
        }
        $this->db->from("faq");
        $this->db->join("tblstaff",'faq.question_by = tblstaff.staffid');
        $this->db->order_by('faq.id','desc');
        $query=$this->db->get();
        // $query = $this->db->get('faq');
        return $query->result();
    }
    public function getLeadDataOtherPkg($leadId){
        // print_r($leadId);die;
        $data_check =  $this->db->get_where('tblleads',array('id'=>$leadId,'create_other_package'=>0))->row();
       
        // echo $this->db->last_query();
        // print_r($data_check); die;
        if ($data_check) {
         $this->db->select('id,lead_author_name,phonenumber,email');
     
         $this->db->from('tblleads');
     
         $this->db->where('id', $leadId);
     
         $result = $this->db->get();
     
         return $result->row();
        }else{
       
             $this->db->select('tblleads.*,compaire_create_package.*');
             $this->db->from('tblleads');
             $this->db->join('compaire_create_package', 'compaire_create_package.leadid = tblleads.id');
             $this->db->where('compaire_create_package.leadid', $leadId);
             $result = $this->db->get();
             $finall_data = $result->row();
           
         return $finall_data;
        }
       
     }
     public function getLeadDatamultiPkg($leadId){
        // print_r($leadId);die;
        $data_check =  $this->db->get_where('tblleads_create_package',array('id'=>$leadId,'craete_package'=>0))->row();
       
        // echo $this->db->last_query();
        // print_r($data_check); die;
        if ($data_check) {
        //  $this->db->select('id,lead_author_name,phonenumber,email');
     
        //  $this->db->from('tblleads');
     
        //  $this->db->where('id', $leadId);
     
        //  $result = $this->db->get();
     
        //  return $result->row();

         $this->db->select('tblleads.id as lead_id,tblleads.lead_author_name as author_name,tblleads.phonenumber,tblleads.email,tblleads_create_package.*');
         $this->db->from('tblleads_create_package');
         $this->db->join('tblleads', 'tblleads_create_package.leadid = tblleads.id');
         $this->db->where('tblleads_create_package.id', $leadId);
         $result = $this->db->get();
         return  $result->row();
        }else{
       
             $this->db->select('tblleads.id as lead_id,tblleads.lead_author_name as author_name,tblleads.phonenumber,tblleads.email,tblleads_create_package.*');
             $this->db->from('tblleads_create_package');
             $this->db->join('tblleads', 'tblleads_create_package.leadid = tblleads.id');
             $this->db->where('tblleads_create_package.id', $leadId);
             $result = $this->db->get();
             $finall_data = $result->row();
           
         return $finall_data;
        }
       
     }
     public function getlead_create_Data_another_package($leadId){
        $this->db->select('tblleads.email,tblleads.phonenumber,compaire_create_package.*');

        $this->db->from('compaire_create_package');

        $this->db->join('tblleads','tblleads.id = compaire_create_package.leadid');

        $this->db->where('tblleads.id', $leadId);

        $this->db->where('compaire_create_package.leadid', $leadId); 

        $result = $this->db->get(); 

        return $result->row();

    }
    public function getlead_create_Data_multiple_package($leadId){
        $this->db->select('tblleads.email,tblleads.phonenumber,tblleads_create_package.*');

        $this->db->from('tblleads_create_package');

        $this->db->join('tblleads','tblleads.id = tblleads_create_package.leadid');

        // $this->db->where('tblleads.id', $leadId);

        $this->db->where('tblleads_create_package.id', $leadId); 

        $result = $this->db->get(); 

        return $result->row();

    }
    public function saveOtherCreatePackage($package_id, $data){

        //echo "<pre>";
        //print_r($data);exit;
        $lead_id = $package_id;
        $data_check = $this->db->get_where('compaire_create_package',array('leadid'=>$lead_id))->row();
        if($data_check){
           
            $this->db->where('leadid', $lead_id);
            $this->db->update('compaire_create_package',$data);
            // $result = $this->db->insert('compaire_create_package',$data);
        }else{
            $result = $this->db->insert('compaire_create_package',$data);
        }

        $data1 = array(
            'create_other_package' => 1);
        $this->db->where('id', $lead_id);
        $this->db->update('tblleads',$data1);
        // echo $this->db->last_query();die;
        return $result;
    }
    public function savemultiCreatePackage($package_id, $data){
        // echo $package_id;
        // echo "<pre>";
        // print_r($data);exit;
        $lead_id = $package_id;
        // $data_check = $this->db->get_where('compaire_create_package',array('leadid'=>$lead_id))->row();
        // if($data_check){
           
            $this->db->where('id', $lead_id);
            return $result = $this->db->update('tblleads_create_package',$data);
            // $result = $this->db->insert('compaire_create_package',$data);
        // }else{
        //     $result = $this->db->insert('compaire_create_package',$data);
        // }

        // $data1 = array(
        //     'create_other_package' => 1);
        // $this->db->where('id', $lead_id);
        // $this->db->update('tblleads',$data1);
        // // echo $this->db->last_query();die;
        // return $result;
    }
    public function filter_by_task_type($limit=30,$start=1)
    {
        $return = "result";
        $task_type = $this->input->get('tasktype');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $staff_name = $this->input->get('staff_name');
        $stf = $this->input->get('staff_name');
       // echo $stf[0];

        
        if ($stf[0] != null) {

            $this->db->where_in('staff_id', $staff_name);
        }
        else  {
            
            if(is_admin())
            {
                
            }
            else
            {
              $arr=herapermission();
            
             $this->db->where("staff_id IN (".$arr.")",NULL, false);  
            }
            
           
        
        }
        if (isset($task_type)) {
            
            $this->db->where_in('task_type', $task_type);
        }
        
        if (isset($end_date) && $end_date != null  ) 
        {

            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
        
       

        
            $this->db->select('tblworkreport.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
            
            $this->db->limit($limit,$start);
            return $this->_checkRecords($this->db->get('tblworkreport'), $return);
        
        
    }
    public function get_staff($return = "result")
    {
        if(is_admin() || is_sub_admin())
        {
            $this->db->select('staff_id, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->group_by('staff_id');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
			$this->db->order_by('tblstaff.staffid', 'desc');
            return $this->_checkRecords($this->db->get('tblworkreport'), $return);
        }
        elseif(herapermission())
        {
            $arr=herapermission();
			$useraid = $this->session->userdata('staff_user_id');
            $leadsource = array($arr);
            $this->db->select('staff_id, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->where("staff_id IN (".$arr.")",NULL, false);
			$this->db->or_where_in('staff_id', $useraid);
            $this->db->group_by('staff_id');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
			$this->db->order_by('tblstaff.staffid', 'desc');
            return $this->_checkRecords($this->db->get('tblworkreport'), $return);
        }
            

    }
    public function get_task_type($return = "result")
    {

        $this->db->select('task_type');
        $this->db->group_by('task_type');
        return $this->_checkRecords($this->db->get('tblworkreport'), $return);

    }
    public function filter_by_task_type1()
    {
        $limit=$_REQUEST['length'];//30;
        $start=$_REQUEST['start'];//1;
        $return = "result";
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $stf = $_POST['staff_name'];
       // echo $stf[0];

        
        if ($stf != null) {

            $this->db->where_in('staff_id', $staff_name);
        }
        else  {
            
            if(is_admin() || is_sub_admin())
            {
                
            }
            else
            {
              $arr=herapermission();
            
             $this->db->where("staff_id IN (".$arr.")",NULL, false);  
            }
            
           
        
        }
    
        
        if (isset($end_date) && $end_date != null  ) 
        {

            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
        
       

        
            $this->db->select('tbl_pc_dwr.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->join('tblstaff', 'tbl_pc_dwr.staff_id= tblstaff.staffid');
            $this->db->order_by("id", "desc"); 
            $this->db->limit($limit,$start);
            return $this->_checkRecords($this->db->get('tbl_pc_dwr'), $return);
        
        
    }
    
    public function getTrCount($search = '',$search_cat='',$start_date = '',$end_date='',$staff_name = '',$sel_ms='') {
        $id = $this->session->userdata('staff_user_id');
        $role = get_imp_role();
        $arr = herapermission();
        $arr = explode(',', $arr);
        $this->db->select('count(*) as allcount');
        $this->db->from('tbl_pc_dwr');
        if (!(is_admin())) {
            if ($id == 34 || $id == 28 ) {
                
            }elseif($role ==92){
                $this->db->where_in('staff_id',$arr);
            }else{
                $this->db->where('staff_id',$id);
            }
        }
        if($search != ''){
            $this->db->like('work_duration', $search);
            $this->db->or_like('description', $search);
            $this->db->or_like('remark', $search);
        }
        if($staff_name != ''){
            $this->db->where_in('staff_id', $staff_name); 
        }

        $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {
            $this->db->where('date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }

        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result[0]['allcount'];
    }
    
    public function TrData($limit, $start,$search_text='',$search_cat='',$start_date = '',$end_date='', $staff_name = '',$sel_ms=''){
        $useraid = $this->session->userdata('staff_user_id');
        // print_r($useraid);die;
        $role = get_imp_role();
        $arr = herapermission();
        $arr = explode(',', $arr);
        $this->load->database();
        $this->db->select('*');
        if (!(is_admin())) {
            if ($useraid == 34 || $useraid == 28) {
           
            }elseif($role ==92){
                $this->db->where_in('staff_id',$arr);
            }else{
                $this->db->where('staff_id',$useraid);
            }
         
        }
        
        $this->db->order_by('date','DESC');
        
        $this->db->limit($limit, $start);
        
      if($search_text != ''){
        $this->db->group_start();
        $this->db->like('work_duration', $search_text);
        $this->db->or_like('description', $search_text);
        $this->db->or_like('remark', $search_text);
      $this->db->group_end();
        }
        if($staff_name != ''){
            $this->db->where_in('staff_id', $staff_name); 
        }
        $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {
           $this->db->where('date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }
       
        $query = $this->db->get('tbl_pc_dwr');
        return $query->result();
    }

    public function getDCRCount($search = '',$search_cat='',$start_date = '',$end_date='',$staff_name = '',$sel_ms='') {
        $id = $this->session->userdata('staff_user_id');
        $role = get_imp_role();
        $arr = herapermission();
        $arr = explode(',', $arr);
        $this->db->select('count(*) as allcount');
        $this->db->from('tbl_pc_dwr');
        if (!(is_admin())) {
            if ($id == 34 || $id == 28 ) {
            }elseif($role ==92){
                $this->db->where_in('staff_id',$arr);
            }else{
                $this->db->where('staff_id',$id);
            }
        }
        if($search != ''){
            $this->db->like('work_duration', $search);
            $this->db->or_like('description', $search);
            $this->db->or_like('remark', $search);
        }
        if($staff_name != ''){
            $this->db->where_in('staff_id', $staff_name); 
        }

        $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {
            $this->db->where('date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }

        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result[0]['allcount'];
    }
    public function getDCRData($limit, $start,$search_text='',$search_cat='',$start_date = '',$end_date='', $staff_name = '',$sel_ms=''){
    //     $useraid = $this->session->userdata('staff_user_id');
    //     // print_r($useraid);die;
    //     $role = get_imp_role();
    //     $arr = herapermission();
    //     $arr = explode(',', $arr);
    //     $this->load->database();
    //     $this->db->select('*');
    //     if (!(is_admin())) {
    //         if ($useraid == 34 || $useraid == 28) {
           
    //         }elseif($role ==92){
    //             $this->db->where_in('staff_id',$arr);
    //         }else{
    //             $this->db->where('staff_id',$useraid);
    //         }
         
    //     }
        
    //     $this->db->order_by('date','DESC');
        
    //     $this->db->limit($limit, $start);
        
    //   if($search_text != ''){
    //     $this->db->group_start();
    //     $this->db->like('work_duration', $search_text);
    //     $this->db->or_like('description', $search_text);
    //     $this->db->or_like('remark', $search_text);
    //   $this->db->group_end();
    //     }
    //     if($staff_name != ''){
    //         $this->db->where_in('staff_id', $staff_name); 
    //     }
    //     $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
    //     $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
    //     if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
    //     {
    //        $this->db->where('date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
    //     }
       
    //     $query = $this->db->get('tbl_pc_dwr');
    //     return $query->result();
    $this->db->distinct();
        $this->db->select('`tblstaff`.`staffid`,`tblstaff`.`firstname`,`tblstaff`.`lastname`');

        $this->db->join('tblleads', 'tblstaff.staffid = tblleads.assigned');
        $this->db->group_by('tblleads.assigned');
        $this->db->where('tblstaff.role_id',64);

        $data=$this->db->get('tblstaff')->result();
        return $data;
    }
    
 
    
 
}
