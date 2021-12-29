<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class DcrTest extends Admin_controller
{
    private $not_importable_leads_fields;

    public function __construct()
    {
        parent::__construct();
        $this->not_importable_leads_fields = do_action('not_importable_leads_fields', ['id', 'source', 'assigned', 'status', 'dateadded', 'last_status_change', 'addedfrom', 'leadorder', 'date_converted', 'lost', 'junk', 'is_imported_from_email_integration', 'email_integration_uid', 'is_public', 'dateassigned', 'client_id', 'lastcontact', 'last_lead_status', 'from_form_id', 'default_language', 'hash']);
        $this->load->model('leads_model');
        $this->load->model('product_model', 'pmodel');
        $this->load->model('departments_model', 'depart_model');
        $this->load->model('teams_model', 'teamsm');
        $this->load->library("pagination");
        $this->load->helper('url');
        $this->load->library('excel');
    }


    /* List all leads */
    public function index($id = '')
    {
        close_setup_menu();
        if (!is_staff_member()) {
            access_denied('Leads');
        }
        $data['switch_kanban'] = true;
        if ($this->session->userdata('leads_kanban_view') == 'true') {
            $data['switch_kanban'] = false;
            $data['bodyclass'] = 'hide-sidebar';
        }
        $data['staff'] = $this->staff_model->get('', ['active' => 1]);
        if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
            $this->load->model('gdpr_model');
            $data['consent_purposes'] = $this->gdpr_model->get_consent_purposes();
        }
        $data['summary'] = get_leads_summary();
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources'] = $this->leads_model->get_source();
        $data['title'] = _l('leads');
        $data['leadid'] = $id;
        $this->load->view('admin/leads/manage_leads', $data);
    }

    public function table()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }
        $this->app->get_table_data('leads');
    }

    public function kanban()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }
        $data['statuses'] = $this->leads_model->get_status();
        echo $this->load->view('admin/leads/kan-ban', $data, true);
    }

    /* Add or update lead */
    public function lead($id = '')
    {
        if (!is_staff_member() || ($id != '' && !$this->leads_model->staff_can_access_lead($id))) {
            $this->access_denied_ajax();
        }

        if ($this->input->post()) {
            if ($id == '') {
                $id = $this->leads_model->add($this->input->post());
                $message = $id ? _l('added_successfully', _l('lead')) : '';
                //   set_alert('success', "New Lead Added Successfully");
                //   redirect(admin_url('leads/allleads'));


                echo json_encode([
                    'success' => $id ? true : false,
                    'id' => $id,
                    'message' => $message,
                ]);

            } else {
                $emailOriginal = $this->db->select('email')->where('id', $id)->get('tblleads')->row()->email;
                $proposalWarning = false;
                $message = '';
                $success = $this->leads_model->update($this->input->post(), $id);

                if ($success) {
                    $emailNow = $this->db->select('email')->where('id', $id)->get('tblleads')->row()->email;

                    $proposalWarning = (total_rows('tblproposals', [
                            'rel_type' => 'lead',
                            'rel_id' => $id,]) > 0 && ($emailOriginal != $emailNow) && $emailNow != '') ? true : false;

                    $message = _l('updated_successfully', _l('lead'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                    'id' => $id,
                    'proposal_warning' => $proposalWarning,
                    'leadView' => $this->_get_lead_data($id),
                ]);
            }
            die;
        }

        echo json_encode([
            'leadView' => $this->_get_lead_data($id),
        ]);
    }

    private function access_denied_ajax()
    {
        header('HTTP/1.0 404 Not Found');
        echo _l('access_denied');
        die;
    }

    private function _get_lead_data($id = '')
    {
        $reminder_data = '';
        $data['lead_locked'] = false;
        $data['openEdit'] = $this->input->get('edit') ? true : false;
        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);
        $data['prod_cats'] = $this->pmodel->get_categories('', 'result_array');
        $data['prod_schemes'] = $this->pmodel->get_scheme_types('', 'result_array');
        $data['prod_companies'] = $this->pmodel->get_companies('', 'result_array');
        $data['status_id'] = $this->input->get('status_id') ? $this->input->get('status_id') : get_option('leads_default_status');

        if (is_numeric($id)) {
            $leadWhere = (has_permission('leads', '', 'view') ? [] : '(assigned = ' . get_staff_user_id() . ' OR addedfrom=' . get_staff_user_id() . ' OR is_public=1)');

            $lead = $this->leads_model->get($id, $leadWhere);

            if (!$lead) {
                header('HTTP/1.0 404 Not Found');
                echo _l('lead_not_found');
                die;
            }

            if (total_rows('tblclients', ['leadid' => $id]) > 0) {
                $data['lead_locked'] = ((!is_admin() && get_option('lead_lock_after_convert_to_customer') == 1) ? true : false);
            }

            $reminder_data = $this->load->view('admin/includes/modals/reminder', [
                'id' => $lead->id,
                'name' => 'lead',
                'members' => $data['members'],
                'reminder_title' => _l('lead_set_reminder_title'),
            ], true);

            $data['lead'] = $lead;
            $data['mail_activity'] = $this->leads_model->get_mail_activity($id);
            $data['notes'] = $this->misc_model->get_notes($id, 'lead');
            $data['activity_log'] = $this->leads_model->get_lead_activity_log($id);

            if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
                $this->load->model('gdpr_model');
                $data['purposes'] = $this->gdpr_model->get_consent_purposes($lead->id, 'lead');
                $data['consents'] = $this->gdpr_model->get_consents(['lead_id' => $lead->id]);
            }
        }


        $data['statuses'] = $this->leads_model->get_status();
        $data['sources'] = $this->leads_model->get_source();

        $data = do_action('lead_view_data', $data);

        return [
            'data' => $this->load->view('admin/leads/lead', $data, true),
            'reminder_data' => $reminder_data,
        ];
    }

    public function leads_kanban_load_more()
    {
        if (!is_staff_member()) {
            $this->access_denied_ajax();
        }

        $status = $this->input->get('status');
        $page = $this->input->get('page');

        $this->db->where('id', $status);
        $status = $this->db->get('tblleadsstatus')->row_array();

        $leads = $this->leads_model->do_kanban_query($status['id'], $this->input->get('search'), $page, [
            'sort_by' => $this->input->get('sort_by'),
            'sort' => $this->input->get('sort'),
        ]);

        foreach ($leads as $lead) {
            $this->load->view('admin/leads/_kan_ban_card', [
                'lead' => $lead,
                'status' => $status,
            ]);
        }
    }

    public function switch_kanban($set = 0)
    {
        if ($set == 1) {
            $set = 'true';
        } else {
            $set = 'false';
        }
        $this->session->set_userdata([
            'leads_kanban_view' => $set,
        ]);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function export($id)
    {
        if (is_admin()) {
            export_lead_data($id);
        }
    }

    public function viewassignedleads()
    {
        if (is_admin() || is_headtrm()) {
            $useraid = $this->session->userdata('staff_user_id');
            $leadsource = array(1, 5, 6, 8);
            $this->db->where_in('source', $leadsource);
            $this->db->select('CONCAT(tbll.firstname, ' . ', tbll.lastname) AS fullname, tbll.staffid  ,tblleads.*');
            $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
            $data['assignedleads'] = $this->db->get('tblleads')->result();

            $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
            $data['get_company'] = $this->leads_model->get_company2();
            $data['data_source'] = $this->leads_model->get_data_source2();
            $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            $data['getleadsource'] = $this->leads_model->get_leadsource2();
            $data['bodyclass'] = 'hide-sidebar';

            $this->load->view('admin/leads/assignedleads', $data);
        } else {
            $useraid = $this->session->userdata('staff_user_id');
            $leadsource = array(1, 5, 6, 8);
            $data['useraid'] = $useraid;
            $this->db->where('assigned', $useraid);
            $this->db->where_in('source', $leadsource);
            $data['assignedleads'] = $this->db->get('tblleads')->result();
            $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
            $data['get_company'] = $this->leads_model->get_company2();
            $data['data_source'] = $this->leads_model->get_data_source2();
            $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            $data['bodyclass'] = 'hide-sidebar';
            $this->load->view('admin/leads/assignedleads', $data);
        }
    }

    /* Delete lead from database */

    public function allleadremark()
    {
        $lead_idnew = $_POST['id'];
        $lead_name = $_POST['name'];
        $data['lead_id'] = $_POST['id'];
        $lead_status = $_POST['status'];
        $lead_description = $_POST['description'];
        $this->db->where('lead_id', $lead_idnew);
        $data['allleadremark'] = $this->db->get(' tblleadremark')->result();
        $data['lead_id'] = $lead_idnew;
        $data['name'] = $lead_name;
        $data['status'] = $lead_status;
        $data['assigned'] = $_POST['assigned'];
        $data['next_calling'] = $_POST['next_calling'];
        $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
        $data['description'] = $lead_description;
        $data['wplistlead'] = $this->leads_model->get_availwplead();
        //echo "this page";
        //print_r($data['wplistlead']);
        $this->load->view('admin/leads/viewremark', $data); 
        
    }

    public function meeting_remark()
    {
        $lead_idnew = $_POST['id'];
        $lead_id = $_POST['lead_id'];
        $lead_name = $_POST['name'];
        $data['lead_id'] = $_POST['id'];
        $lead_status = $_POST['status'];
        $lead_description = $_POST['description'];
        $this->db->where('lead_id', $lead_id);
        $data['allleadremark'] = $this->db->get(' tblleadremark')->result();
        $this->db->where('meeting_id', $lead_idnew);
        $data['allmeetingremark'] = $this->db->get(' tblmeeting_remark')->result();

        $data['name'] = $lead_name;
        $data['status'] = $lead_status;
        $data['description'] = $lead_description;
        $this->load->view('admin/leads/meeting_remark', $data);
    }

    public function deleteremarkid($id)
    {
        $response = $this->leads_model->deleteremarkid($id);
        set_alert('success', _l('deleted', _l('leads_remark')));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function deletemeetingremark($id)
    {
        $response = $this->leads_model->deletemeetingremark($id);
        set_alert('success', _l('deleted', _l('Meeting Remark')));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('leads'));
        }
        if (!is_lead_creator($id) && !has_permission('leads', '', 'delete')) {
            access_denied('Delte Lead');
        }
        $response = $this->leads_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_lowercase')));
        } elseif ($response === true) {
            set_alert('success', _l('deleted', _l('lead')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_lowercase')));
        }
        $ref = $_SERVER['HTTP_REFERER'];
        if (!$ref || strpos($ref, 'index/' . $id) !== false) {
            redirect(admin_url('leads'));
        }
        redirect($ref);
    }

    public function mark_as_lost($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $message = '';
        $success = $this->leads_model->mark_as_lost($id);
        if ($success) {
            $message = _l('lead_marked_as_lost');
        }
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'leadView' => $this->_get_lead_data($id),
            'id' => $id,
        ]);
    }

    public function unmark_as_lost($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $message = '';
        $success = $this->leads_model->unmark_as_lost($id);
        if ($success) {
            $message = _l('lead_unmarked_as_lost');
        }
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'leadView' => $this->_get_lead_data($id),
            'id' => $id,
        ]);
    }

    public function mark_as_junk($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $message = '';
        $success = $this->leads_model->mark_as_junk($id);
        if ($success) {
            $message = _l('lead_marked_as_junk');
        }
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'leadView' => $this->_get_lead_data($id),
            'id' => $id,
        ]);
    }

    public function unmark_as_junk($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $message = '';
        $success = $this->leads_model->unmark_as_junk($id);
        if ($success) {
            $message = _l('lead_unmarked_as_junk');
        }
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'leadView' => $this->_get_lead_data($id),
            'id' => $id,
        ]);
    }

    public function add_activity()
    {
        $leadid = $this->input->post('leadid');
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($leadid)) {
            $this->access_denied_ajax();
        }
        if ($this->input->post()) {
            $message = $this->input->post('activity');
            $aId = $this->leads_model->log_lead_activity($leadid, $message);
            if ($aId) {
                $this->db->where('id', $aId);
                $this->db->update('tblleadactivitylog', ['custom_activity' => 1]);
            }
            echo json_encode(['leadView' => $this->_get_lead_data($leadid), 'id' => $leadid]);
        }
    }

    public function get_convert_data($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') {
            $this->load->model('gdpr_model');
            $data['purposes'] = $this->gdpr_model->get_consent_purposes($id, 'lead');
        }
        $data['lead'] = $this->leads_model->get($id);
        $this->load->view('admin/leads/convert_to_customer', $data);
    }

    /* Used in kanban when dragging and mark as */

    /**
     * Convert lead to client
     * @return mixed
     * @since  version 1.0.1
     */
    public function convert_to_customer()
    {
        if (!is_staff_member()) {
            access_denied('Lead Convert to Customer');
        }

        if ($this->input->post()) {
            $default_country = get_option('customer_default_country');
            $data = $this->input->post();
            $data['password'] = $this->input->post('password', false);

            $original_lead_email = $data['original_lead_email'];
            unset($data['original_lead_email']);

            if (isset($data['transfer_notes'])) {
                $notes = $this->misc_model->get_notes($data['leadid'], 'lead');
                unset($data['transfer_notes']);
            }

            if (isset($data['transfer_consent'])) {
                $this->load->model('gdpr_model');
                $consents = $this->gdpr_model->get_consents(['lead_id' => $data['leadid']]);
                unset($data['transfer_consent']);
            }

            if (isset($data['merge_db_fields'])) {
                $merge_db_fields = $data['merge_db_fields'];
                unset($data['merge_db_fields']);
            }

            if (isset($data['merge_db_contact_fields'])) {
                $merge_db_contact_fields = $data['merge_db_contact_fields'];
                unset($data['merge_db_contact_fields']);
            }

            if (isset($data['include_leads_custom_fields'])) {
                $include_leads_custom_fields = $data['include_leads_custom_fields'];
                unset($data['include_leads_custom_fields']);
            }

            if ($data['country'] == '' && $default_country != '') {
                $data['country'] = $default_country;
            }

            $data['billing_street'] = $data['address'];
            $data['billing_city'] = $data['city'];
            $data['billing_state'] = $data['state'];
            $data['billing_zip'] = $data['zip'];
            $data['billing_country'] = $data['country'];

            $data['is_primary'] = 1;
            $id = $this->clients_model->add($data, true);
            if ($id) {
                $primary_contact_id = get_primary_contact_user_id($id);

                if (isset($notes)) {
                    foreach ($notes as $note) {
                        $this->db->insert('tblnotes', [
                            'rel_id' => $id,
                            'rel_type' => 'customer',
                            'dateadded' => $note['dateadded'],
                            'addedfrom' => $note['addedfrom'],
                            'description' => $note['description'],
                            'date_contacted' => $note['date_contacted'],
                        ]);
                    }
                }
                if (isset($consents)) {
                    foreach ($consents as $consent) {
                        unset($consent['id']);
                        unset($consent['purpose_name']);
                        $consent['lead_id'] = 0;
                        $consent['contact_id'] = $primary_contact_id;
                        $this->gdpr_model->add_consent($consent);
                    }
                }
                if (!has_permission('customers', '', 'view') && get_option('auto_assign_customer_admin_after_lead_convert') == 1) {
                    $this->db->insert('tblcustomeradmins', [
                        'date_assigned' => date('Y-m-d H:i:s'),
                        'customer_id' => $id,
                        'staff_id' => get_staff_user_id(),
                    ]);
                }
                $this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted', false, serialize([
                    get_staff_full_name(),
                ]));
                $default_status = $this->leads_model->get_status('', [
                    'isdefault' => 1,
                ]);
                $this->db->where('id', $data['leadid']);
                $this->db->update('tblleads', [
                    'date_converted' => date('Y-m-d H:i:s'),
                    'status' => $default_status[0]['id'],
                    'junk' => 0,
                    'lost' => 0,
                ]);
                // Check if lead email is different then client email
                $contact = $this->clients_model->get_contact(get_primary_contact_user_id($id));
                if ($contact->email != $original_lead_email) {
                    if ($original_lead_email != '') {
                        $this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted_email', false, serialize([
                            $original_lead_email,
                            $contact->email,
                        ]));
                    }
                }
                if (isset($include_leads_custom_fields)) {
                    foreach ($include_leads_custom_fields as $fieldid => $value) {
                        // checked don't merge
                        if ($value == 5) {
                            continue;
                        }
                        // get the value of this leads custom fiel
                        $this->db->where('relid', $data['leadid']);
                        $this->db->where('fieldto', 'leads');
                        $this->db->where('fieldid', $fieldid);
                        $lead_custom_field_value = $this->db->get('tblcustomfieldsvalues')->row()->value;
                        // Is custom field for contact ot customer
                        if ($value == 1 || $value == 4) {
                            if ($value == 4) {
                                $field_to = 'contacts';
                            } else {
                                $field_to = 'customers';
                            }
                            $this->db->where('id', $fieldid);
                            $field = $this->db->get('tblcustomfields')->row();
                            // check if this field exists for custom fields
                            $this->db->where('fieldto', $field_to);
                            $this->db->where('name', $field->name);
                            $exists = $this->db->get('tblcustomfields')->row();
                            $copy_custom_field_id = null;
                            if ($exists) {
                                $copy_custom_field_id = $exists->id;
                            } else {
                                // there is no name with the same custom field for leads at the custom side create the custom field now
                                $this->db->insert('tblcustomfields', [
                                    'fieldto' => $field_to,
                                    'name' => $field->name,
                                    'required' => $field->required,
                                    'type' => $field->type,
                                    'options' => $field->options,
                                    'display_inline' => $field->display_inline,
                                    'field_order' => $field->field_order,
                                    'slug' => slug_it($field_to . '_' . $field->name, [
                                        'separator' => '_',
                                    ]),
                                    'active' => $field->active,
                                    'only_admin' => $field->only_admin,
                                    'show_on_table' => $field->show_on_table,
                                    'bs_column' => $field->bs_column,
                                ]);
                                $new_customer_field_id = $this->db->insert_id();
                                if ($new_customer_field_id) {
                                    $copy_custom_field_id = $new_customer_field_id;
                                }
                            }
                            if ($copy_custom_field_id != null) {
                                $insert_to_custom_field_id = $id;
                                if ($value == 4) {
                                    $insert_to_custom_field_id = get_primary_contact_user_id($id);
                                }
                                $this->db->insert('tblcustomfieldsvalues', [
                                    'relid' => $insert_to_custom_field_id,
                                    'fieldid' => $copy_custom_field_id,
                                    'fieldto' => $field_to,
                                    'value' => $lead_custom_field_value,
                                ]);
                            }
                        } elseif ($value == 2) {
                            if (isset($merge_db_fields)) {
                                $db_field = $merge_db_fields[$fieldid];
                                // in case user don't select anything from the db fields
                                if ($db_field == '') {
                                    continue;
                                }
                                if ($db_field == 'country' || $db_field == 'shipping_country' || $db_field == 'billing_country') {
                                    $this->db->where('iso2', $lead_custom_field_value);
                                    $this->db->or_where('short_name', $lead_custom_field_value);
                                    $this->db->or_like('long_name', $lead_custom_field_value);
                                    $country = $this->db->get('tblcountries')->row();
                                    if ($country) {
                                        $lead_custom_field_value = $country->country_id;
                                    } else {
                                        $lead_custom_field_value = 0;
                                    }
                                }
                                $this->db->where('userid', $id);
                                $this->db->update('tblclients', [
                                    $db_field => $lead_custom_field_value,
                                ]);
                            }
                        } elseif ($value == 3) {
                            if (isset($merge_db_contact_fields)) {
                                $db_field = $merge_db_contact_fields[$fieldid];
                                if ($db_field == '') {
                                    continue;
                                }
                                $this->db->where('id', $primary_contact_id);
                                $this->db->update('tblcontacts', [
                                    $db_field => $lead_custom_field_value,
                                ]);
                            }
                        }
                    }
                }
                // set the lead to status client in case is not status client
                $this->db->where('isdefault', 1);
                $status_client_id = $this->db->get('tblleadsstatus')->row()->id;
                $this->db->where('id', $data['leadid']);
                $this->db->update('tblleads', [
                    'status' => $status_client_id,
                ]);

                set_alert('success', _l('lead_to_client_base_converted_success'));

                if (is_gdpr() && get_option('gdpr_after_lead_converted_delete') == '1') {
                    $this->leads_model->delete($data['leadid']);

                    $this->db->where('userid', $id);
                    $this->db->update('tblclients', ['leadid' => null]);
                }

                logActivity('Created Lead Client Profile [LeadID: ' . $data['leadid'] . ', ClientID: ' . $id . ']');
                do_action('lead_converted_to_customer', ['lead_id' => $data['leadid'], 'customer_id' => $id]);
                redirect(admin_url('clients/client/' . $id));
            }
        }
    }

    public function update_lead_status()
    {
        $post = $this->input->post();
        if ($post && $this->input->is_ajax_request()) {
            $this->leads_model->update_lead_status($post);
            if ($post['status'] == '9') {
                $this->leads_model->lead_assigned_reporting_manager_notification($post['leadid']);
            }
        }
    }

    public function update_status_order()
    {
        $post = $this->input->post();
        if ($post) {
            $this->leads_model->update_status_order($post);
            if ($post['status'] == '9') {
                $this->leads_model->lead_assigned_reporting_manager_notification($post['leadid']);
            }
        }
    }

    public function add_lead_attachment()
    {
        $id = $this->input->post('id');
        $lastFile = $this->input->post('last_file');

        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }

        handle_lead_attachments($id);
        echo json_encode(['leadView' => $lastFile ? $this->_get_lead_data($id) : [], 'id' => $id]);
    }

    public function add_external_attachment()
    {
        if ($this->input->post()) {
            $this->leads_model->add_attachment_to_database(
                $this->input->post('lead_id'),
                $this->input->post('files'),
                $this->input->post('external')
            );
        }
    }

    public function delete_attachment($id, $lead_id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($lead_id)) {
            $this->access_denied_ajax();
        }
        echo json_encode([
            'success' => $this->leads_model->delete_lead_attachment($id),
        ]);
    }

    public function delete_leads()
    {

        $lead_id_start = $this->uri->segment(4);
        $lead_id_end = $this->uri->segment(5);

        $success = $this->leads_model->delete_leads($lead_id_start, $lead_id_end);

        set_alert('success', _l('deleted', "Lead"));

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete_addedleads()
    {

        $lead_id = $this->uri->segment(4);

        $success = $this->leads_model->delete_addedleads($lead_id);

        set_alert('success', _l('deleted', "Lead"));

        redirect($_SERVER['HTTP_REFERER']);
    }


    public function delete_note($id, $lead_id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($lead_id)) {
            $this->access_denied_ajax();
        }
        echo json_encode([
            'success' => $this->misc_model->delete_note($id),
        ]);
    }

    public function update_all_proposal_emails_linked_to_lead($id)
    {
        $success = false;
        $email = '';
        if ($this->input->post('update')) {
            $this->load->model('proposals_model');

            $this->db->select('email');
            $this->db->where('id', $id);
            $email = $this->db->get('tblleads')->row()->email;

            $proposals = $this->proposals_model->get('', [
                'rel_type' => 'lead',
                'rel_id' => $id,
            ]);
            $affected_rows = 0;

            foreach ($proposals as $proposal) {
                $this->db->where('id', $proposal['id']);
                $this->db->update('tblproposals', [
                    'email' => $email,
                ]);
                if ($this->db->affected_rows() > 0) {
                    $affected_rows++;
                }
            }

            if ($affected_rows > 0) {
                $success = true;
            }
        }

        echo json_encode([
            'success' => $success,
            'message' => _l('proposals_emails_updated', [
                _l('lead_lowercase'),
                $email,
            ]),
        ]);
    }

    public function save_form_data()
    {
        $data = $this->input->post();

        // form data should be always sent to the request and never should be empty
        // this code is added to prevent losing the old form in case any errors
        if (!isset($data['formData']) || isset($data['formData']) && !$data['formData']) {
            echo json_encode([
                'success' => false,
            ]);
            die;
        }
        $this->db->where('id', $data['id']);
        $this->db->update('tblwebtolead', [
            'form_data' => $data['formData'],
        ]);
        if ($this->db->affected_rows() > 0) {
            echo json_encode([
                'success' => true,
                'message' => _l('updated_successfully', _l('web_to_lead_form')),
            ]);
        } else {
            echo json_encode([
                'success' => false,
            ]);
        }
    }

    public function form($id = '')
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id = $this->leads_model->add_form($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('web_to_lead_form')));
                    redirect(admin_url('leads/form/' . $id));
                }
            } else {
                $success = $this->leads_model->update_form($id, $this->input->post());
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('web_to_lead_form')));
                }
                redirect(admin_url('leads/form/' . $id));
            }
        }

        $data['formData'] = [];
        $custom_fields = get_custom_fields('leads', 'type != "link"');

        $cfields = format_external_form_custom_fields($custom_fields);
        $data['title'] = _l('web_to_lead');

        if ($id != '') {
            $data['form'] = $this->leads_model->get_form([
                'id' => $id,
            ]);
            $data['title'] = $data['form']->name . ' - ' . _l('web_to_lead_form');
            $data['formData'] = $data['form']->form_data;
        }

        $this->load->model('roles_model');
        $data['roles'] = $this->roles_model->get();
        $data['sources'] = $this->leads_model->get_source();
        $data['statuses'] = $this->leads_model->get_status();

        $data['members'] = $this->staff_model->get('', [
            'active' => 1,
            'is_not_staff' => 0,
        ]);

        $data['languages'] = $this->app->get_available_languages();
        $data['cfields'] = $cfields;

        $db_fields = [];
        $fields = [
            'name',
            'title',
            'email',
            'phonenumber',
            'company',
            'address',
            'city',
            'state',
            'country',
            'zip',
            'description',
            'website',
        ];

        $fields = do_action('lead_form_available_database_fields', $fields);

        $className = 'form-control';

        foreach ($fields as $f) {
            $_field_object = new stdClass();
            $type = 'text';
            $subtype = '';
            if ($f == 'email') {
                $subtype = 'email';
            } elseif ($f == 'description' || $f == 'address') {
                $type = 'textarea';
            } elseif ($f == 'country') {
                $type = 'select';
            }

            if ($f == 'name') {
                $label = _l('lead_add_edit_name');
            } elseif ($f == 'email') {
                $label = _l('lead_add_edit_email');
            } elseif ($f == 'phonenumber') {
                $label = _l('lead_add_edit_phonenumber');
            } else {
                $label = _l('lead_' . $f);
            }

            $field_array = [
                'subtype' => $subtype,
                'type' => $type,
                'label' => $label,
                'className' => $className,
                'name' => $f,
            ];

            if ($f == 'country') {
                $field_array['values'] = [];
                $countries = get_all_countries();
                foreach ($countries as $country) {
                    $selected = false;
                    if (get_option('customer_default_country') == $country['country_id']) {
                        $selected = true;
                    }
                    array_push($field_array['values'], [
                        'label' => $country['short_name'],
                        'value' => (int)$country['country_id'],
                        'selected' => $selected,
                    ]);
                }
            }

            if ($f == 'name') {
                $field_array['required'] = true;
            }

            $_field_object->label = $label;
            $_field_object->name = $f;
            $_field_object->fields = [];
            $_field_object->fields[] = $field_array;
            $db_fields[] = $_field_object;
        }
        $data['bodyclass'] = 'web-to-lead-form';
        $data['db_fields'] = $db_fields;
        $this->load->view('admin/leads/formbuilder', $data);
    }

    public function forms($id = '')
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('web_to_lead');
        }

        $data['title'] = _l('web_to_lead');
        $this->load->view('admin/leads/forms', $data);
    }

    // Sources
// Sources
    /* Manage leads sources */

    public function delete_form($id)
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }

        $success = $this->leads_model->delete_form($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('web_to_lead_form')));
        }

        redirect(admin_url('leads/forms'));
    }

    /* Add or update leads sources */

    public function sources()
    {
        if (!is_admin()) {
            access_denied('Leads Sources');
        }
        $data['sources'] = $this->leads_model->get_source();
        $data['title'] = 'Leads sources';
        $this->load->view('admin/leads/manage_sources', $data);
    }

    /* Delete leads source */

    public function source()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_source') == '0') {
            access_denied('Leads Sources');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }

                $id = $this->leads_model->add_source($data);

                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_source')));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : fales, 'id' => $id]);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_source($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_source')));
                }
            }
        }
    }

    // Statuses
    /* View leads statuses */

    public function delete_source($id)
    {
        if (!is_admin()) {
            access_denied('Delete Lead Source');
        }
        if (!$id) {
            redirect(admin_url('leads/sources'));
        }
        $response = $this->leads_model->delete_source($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_source_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_source')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_source_lowercase')));
        }
        redirect(admin_url('leads/sources'));
    }

    /* Add or update leads status */

    public function statuses()
    {
        if (!is_admin()) {
            access_denied('Leads Statuses');
        }
        $data['statuses'] = $this->leads_model->get_status();
        $data['title'] = 'Leads statuses';
        $this->load->view('admin/leads/manage_statuses', $data);
    }

    /* Delete leads status from databae */

    public function status()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_status') == '0') {
            access_denied('Leads Statuses');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $id = $this->leads_model->add_status($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_status')));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : fales, 'id' => $id]);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_status($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_status')));
                }
            }
        }
    }

    /* Add new lead note */

    public function delete_status($id)
    {
        if (!is_admin()) {
            access_denied('Leads Statuses');
        }
        if (!$id) {
            redirect(admin_url('leads/statuses'));
        }
        $response = $this->leads_model->delete_status($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/statuses'));
    }

    public function add_note($rel_id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($rel_id)) {
            $this->access_denied_ajax();
        }

        if ($this->input->post()) {
            $data = $this->input->post();

            if ($data['contacted_indicator'] == 'yes') {
                $contacted_date = to_sql_date($data['custom_contact_date'], true);
                $data['date_contacted'] = $contacted_date;
            }

            unset($data['contacted_indicator']);
            unset($data['custom_contact_date']);

            // Causing issues with duplicate ID or if my prefixed file for lead.php is used
            $data['description'] = isset($data['lead_note_description']) ? $data['lead_note_description'] : $data['description'];

            if (isset($data['lead_note_description'])) {
                unset($data['lead_note_description']);
            }

            $note_id = $this->misc_model->add_note($data, 'lead', $rel_id);

            if ($note_id) {
                if (isset($contacted_date)) {
                    $this->db->where('id', $rel_id);
                    $this->db->update('tblleads', [
                        'lastcontact' => $contacted_date,
                    ]);
                    if ($this->db->affected_rows() > 0) {
                        $this->leads_model->log_lead_activity($rel_id, 'not_lead_activity_contacted', false, serialize([
                            get_staff_full_name(get_staff_user_id()),
                            _dt($contacted_date),
                        ]));
                    }
                }
            }
        }
        echo json_encode(['leadView' => $this->_get_lead_data($rel_id), 'id' => $rel_id]);
    }

    public function test_email_integration()
    {
        if (!is_admin()) {
            access_denied('Leads Test Email Integration');
        }

        require_once(APPPATH . 'third_party/php-imap/Imap.php');

        $mail = $this->leads_model->get_email_integration();
        $ps = $mail->password;
        if (false == $this->encryption->decrypt($ps)) {
            set_alert('danger', _l('failed_to_decrypt_password'));
            redirect(admin_url('leads/email_integration'));
        }
        $mailbox = $mail->imap_server;
        $username = $mail->email;
        $password = $this->encryption->decrypt($ps);
        $encryption = $mail->encryption;
        // open connection
        $imap = new Imap($mailbox, $username, $password, $encryption);

        if ($imap->isConnected() === false) {
            set_alert('danger', _l('lead_email_connection_not_ok') . '<br /><b>' . $imap->getError() . '</b>');
        } else {
            set_alert('success', _l('lead_email_connection_ok'));
        }

        redirect(admin_url('leads/email_integration'));
    }

    public function email_integration()
    {
        if (!is_admin()) {
            access_denied('Leads Email Intregration');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['password'] = $this->input->post('password', false);

            if (isset($data['fakeusernameremembered'])) {
                unset($data['fakeusernameremembered']);
            }
            if (isset($data['fakepasswordremembered'])) {
                unset($data['fakepasswordremembered']);
            }

            $success = $this->leads_model->update_email_integration($data);
            if ($success) {
                set_alert('success', _l('leads_email_integration_updated'));
            }
            redirect(admin_url('leads/email_integration'));
        }
        $data['roles'] = $this->roles_model->get();
        $data['sources'] = $this->leads_model->get_source();
        $data['statuses'] = $this->leads_model->get_status();

        $data['members'] = $this->staff_model->get('', [
            'active' => 1,
            'is_not_staff' => 0,
        ]);

        $data['title'] = _l('leads_email_integration');
        $data['mail'] = $this->leads_model->get_email_integration();
        $data['bodyclass'] = 'leads-email-integration';
        $this->load->view('admin/leads/email_integration', $data);
    }

    public function change_status_color()
    {
        if ($this->input->post()) {
            $this->leads_model->change_status_color($this->input->post());
        }
    }

    public function import()
    {
        

        $simulate_data = [];
        $total_imported = 0;
        if ($this->input->post()) {
            $simulate = $this->input->post('simulate');
            if (isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                do_action('before_import_leads');

                // Get the temp file path
                $tmpFilePath = $_FILES['file_csv']['tmp_name'];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    $tmpDir = TEMP_FOLDER . '/' . time() . uniqid() . '/';

                    if (!file_exists(TEMP_FOLDER)) {
                        mkdir(TEMP_FOLDER, 0755);
                    }

                    if (!file_exists($tmpDir)) {
                        mkdir($tmpDir, 0755);
                    }

                    // Setup our new file path
                    $newFilePath = $tmpDir . $_FILES['file_csv']['name'];

                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $import_result = true;
                        $fd = fopen($newFilePath, 'r');
                        $rows = [];
                        while ($row = fgetcsv($fd)) {
                            $rows[] = $row;
                        }
                        fclose($fd);
                        $data['total_rows_post'] = count($rows);
                        if (count($rows) <= 1) {
                            set_alert('warning', 'Not enought rows for importing');
                            redirect(admin_url('leads/import'));
                        }

                        unset($rows[0]);
                        //   $total_rows_inserted = count($rows);
                        if ($simulate) {
                            if (count($rows) > 500) {
                                set_alert('warning', 'Recommended splitting the CSV file into smaller files. Our recomendation is 500 row, your CSV file has ' . count($rows));
                            }
                        }
                        $db_temp_fields = $this->db->list_fields('tblleads');
                        array_push($db_temp_fields, 'tags');

                        $db_fields = [];
                        foreach ($db_temp_fields as $field) {
                            if (in_array($field, $this->not_importable_leads_fields)) {
                                continue;
                            }
                            $db_fields[] = $field;
                        }
                        $custom_fields = get_custom_fields('leads');
                        $_row_simulate = 0;
                        foreach ($rows as $row) {
                            // do for db fields
                            $insert = [];
                            for ($i = 0; $i < count($db_fields); $i++) {
                                // Avoid errors on nema field. is required in database
                                if ($db_fields[$i] == 'name' && $row[$i] == '') {
                                    $row[$i] = '/';
                                } elseif ($db_fields[$i] == 'country') {
                                    if ($row[$i] != '') {
                                        if (!is_numeric($row[$i])) {
                                            $this->db->where('iso2', $row[$i]);
                                            $this->db->or_where('short_name', $row[$i]);
                                            $this->db->or_where('long_name', $row[$i]);
                                            $country = $this->db->get('tblcountries')->row();
                                            if ($country) {
                                                $row[$i] = $country->country_id;
                                            } else {
                                                $row[$i] = 0;
                                            }
                                        }
                                    } else {
                                        $row[$i] = 0;
                                    }
                                }
                                if ($row[$i] === 'NULL' || $row[$i] === 'null') {
                                    $row[$i] = '';
                                }
                                $insert[$db_fields[$i]] = $row[$i];
                            }

                            if (count($insert) > 0) {
                                if (isset($insert['email']) && $insert['email'] != '') {
                                    if (total_rows('tblleads', ['email' => $insert['email']]) > 0) {
                                        continue;
                                    }
                                }
                                $total_imported++;
                                $insert['dateadded'] = date('Y-m-d H:i:s');
                                $insert['addedfrom'] = get_staff_user_id();
                                //   $insert['lastcontact'] = null;
                                $insert['status'] = $this->input->post('status');
                                $insert['source'] = $this->input->post('source');
                                if ($this->input->post('responsible')) {
                                    $insert['assigned'] = $this->input->post('responsible');
                                }
                                if (!$simulate) {
                                    foreach ($insert as $key => $val) {
                                        $insert[$key] = trim($val);
                                    }
                                    if (isset($insert['tags'])) {
                                        $tags = $insert['tags'];
                                        unset($insert['tags']);
                                    }
                                    $this->db->insert('tblleads', $insert);
                                    $leadid = $this->db->insert_id();
                                } else {
                                    if ($insert['country'] != 0) {
                                        $c = get_country($insert['country']);
                                        if ($c) {
                                            $insert['country'] = $c->short_name;
                                        }
                                    } else {
                                        $insert['country'] = '';
                                    }
                                    $simulate_data[$_row_simulate] = $insert;
                                    $leadid = true;
                                }
                                if ($leadid) {
                                    if (!$simulate) {
                                        handle_tags_save($tags, $leadid, 'lead');
                                    }
                                    $insert = [];
                                    foreach ($custom_fields as $field) {
                                        if (!$simulate) {
                                            if ($row[$i] != '' && $row[$i] !== 'NULL' && $row[$i] !== 'null') {
                                                $this->db->insert('tblcustomfieldsvalues', [
                                                    'relid' => $leadid,
                                                    'fieldid' => $field['id'],
                                                    'value' => trim($row[$i]),
                                                    'fieldto' => 'leads',
                                                ]);
                                            }
                                        } else {
                                            $simulate_data[$_row_simulate][$field['name']] = $row[$i];
                                        }
                                        $i++;
                                    }
                                }
                            }
                            $_row_simulate++;
                            if ($simulate && $_row_simulate >= 100) {
                                break;
                            }
                        }
                        @delete_dir($tmpDir);
                    }
                } else {
                    set_alert('warning', _l('import_upload_failed'));
                }
            }
        }
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources'] = $this->leads_model->get_source();

        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);

        if (count($simulate_data) > 0) {
            $data['simulate'] = $simulate_data;
        }

        if (isset($import_result)) {

            $last_id = $this->db->query("SELECT id FROM `tblleads` ORDER BY id DESC LIMIT 0 , 1");
            $data_lead_id = $last_id->result();
            $last_id = $data_lead_id[0]->id;


            $lead_end_id = $last_id;
            $lead_start_id = $lead_end_id - $total_imported;
            $filename = $_POST['file_name'];


            $data_custom = array(

                'name' => $filename,
                'lead_id_start' => $lead_start_id,
                'lead_id_end' => $lead_end_id,
                'assigned_id' => $this->input->post('responsible')
            );
            $data_custom['crane_features'] = $this->db->insert('tblleads_custom', $data_custom);
            set_alert('success', _l('import_total_imported', $total_imported));

        }
        // $scheme_id =


        $data['not_importable'] = $this->not_importable_leads_fields;
        $data['title'] = _l('import');
        $this->load->view('admin/leads/import', $data);
    }

    public function email_exists()
    {
        if ($this->input->post()) {
            // First we need to check if the email is the same
            $leadid = $this->input->post('leadid');

            if ($leadid != '') {
                $this->db->where('id', $leadid);
                $_current_email = $this->db->get('tblleads')->row();
                if ($_current_email->email == $this->input->post('email')) {
                    echo json_encode(true);
                    die();
                }
            }
            $exists = total_rows('tblleads', [
                'email' => $this->input->post('email'),
            ]);
            if ($exists > 0) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    public function bulk_action()
    {
        if (!is_staff_member()) {
            $this->access_denied_ajax();
        }

        do_action('before_do_bulk_action_for_leads');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids = $this->input->post('ids');
            $status = $this->input->post('status');
            $source = $this->input->post('source');
            $assigned = $this->input->post('assigned');
            $visibility = $this->input->post('visibility');
            $tags = $this->input->post('tags');
            $last_contact = $this->input->post('last_contact');
            $lost = $this->input->post('lost');
            $has_permission_delete = has_permission('leads', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_delete')) {
                        if ($has_permission_delete) {
                            if ($this->leads_model->delete($id)) {
                                $total_deleted++;
                            }
                        }
                    } else {
                        if ($status || $source || $assigned || $last_contact || $visibility) {
                            $update = [];
                            if ($status) {
                                // We will use the same function to update the status
                                $this->leads_model->update_lead_status([
                                    'status' => $status,
                                    'leadid' => $id,
                                ]);
                            }
                            if ($source) {
                                $update['source'] = $source;
                            }
                            if ($assigned) {
                                $update['assigned'] = $assigned;
                            }
                            if ($last_contact) {
                                $last_contact = to_sql_date($last_contact, true);
                                $update['lastcontact'] = $last_contact;
                            }

                            if ($visibility) {
                                if ($visibility == 'public') {
                                    $update['is_public'] = 1;
                                } else {
                                    $update['is_public'] = 0;
                                }
                            }

                            if (count($update) > 0) {
                                $this->db->where('id', $id);
                                $this->db->update('tblleads', $update);
                            }
                        }
                        if ($tags) {
                            handle_tags_save($tags, $id, 'lead');
                        }
                        if ($lost == 'true') {
                            $this->leads_model->mark_as_lost($id);
                        }
                    }
                }
            }
        }

        if ($this->input->post('mass_delete')) {
            set_alert('success', _l('total_leads_deleted', $total_deleted));
        }
    }

    public function allleads()
    {
        $useraid = $this->session->userdata('staff_user_id');
        $data['allleads'] = $this->leads_model->getallleads('tblleads_custom');
        $data['staff_role'] = $this->leads_model->getstaffrole('tblstaff', $useraid);

        $this->load->view('admin/leads/allleads', $data);
    }

    public function viewlead($id = '')
    {
        $status = $_POST['status'];
        $lead_id_start = $_POST['lead_id_start'];
        $lead_id_end = $_POST['lead_id_end'];
        $data['allleads'] = $this->leads_model->get_allleads();
        $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
        $this->load->view('admin/leads/viewlead', $data);
    }

    public function updatecustomlead($id = '')
    {
        if (is_numeric($id)) {
            $getcategory = $this->leads_model->getcustomlead($id);
        }
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $description = $this->input->post('description');
            $this->leads_model->addupadte_customlead(array('name' => $name, 'status' => $status, 'description' => $description), $id);
        }
        echo json_encode([
            'success' => true,
            'message' => _l('Updated_successfully'),
        ]);
    }

    /**
     * @return mixed
     */
    public function update_cust_lead()
    {
        $this->leads_model->update_cust_lead();
        $data['success'] = "success";
        set_alert('success', "Lead Status Updated Successfully");
        return $data;
    }

    public function update_cust_meet()
    {
        $this->leads_model->update_cust_meet();
        $data['success'] = "success";
        set_alert('success', "Meeting Status Updated Successfully");
        return $data;
    }

    public function update_cust_lead_popup()
    {
        $this->leads_model->update_cust_lead_pop();
        echo json_encode([
            'success' => true,
            'message' => _l('Updated_successfully'),
        ]);
    }

    public function get_status($table)
    {
        $this->db->where(array());
        $data = $this->db->get($table)->result();
        return $data;
    }

    function import_lead()
    {
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources'] = $this->leads_model->get_source();
        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);
        $data['title'] = _l('import');
        $this->load->view('admin/leads/import_lead', $data);
    }

    function upload_leads()
    {
        $this->leads_model->upload_leads();
        redirect('admin/leads/import_lead');
    }

    public function test($leadid)
    {
        $this->leads_model->lead_assigned_reporting_manager_notification($leadid);
    }

    public function custom_remark()
    {
        $custom_lid = $_POST['id'];
        $updatecu = $_POST['description'];
        $upd['description'] = $updatecu;
        $this->db->where('id', $custom_lid);
        $this->db->update("tblleads", $upd);
        echo 'Approved';
    }

    public function view_custom_lead($id = '')
    {


        $name = $this->uri->segment(4);
        $lead_id_start = $this->uri->segment(5);
        $lead_id_end = $this->uri->segment(6);
        $this->db->where('lead_id_end', $lead_id_end);
        $data['bodyclass'] = 'hide-sidebar';
        $data['name'] = $this->db->get('tblleads_custom')->row();
        $config["base_url"] = base_url() . "admin/leads/view_custom_lead/" . $name . "/" . $lead_id_start . "/" . $lead_id_end;
        $config["total_rows"] = $this->leads_model->get_count();

        /*print_r($config["total_rows"]);
        exit;*/
        $config["per_page"] = 100;
        $config["uri_segment"] = 7;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);
        //$page = $config["per_page"] * ($config["per_page"]-1);

        $page = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;

        $data["links"] = $this->pagination->create_links();
        $data['allleads'] = $this->leads_model->get_allleads($config["per_page"], $page);
        //$last = $this->db->last_query();
        //print_r($last);
        $data['get_company'] = $this->leads_model->get_company();
        $data['data_source'] = $this->leads_model->get_data_source();
        $data['calling_obj'] = $this->leads_model->get_calling_obj();
        $data['wplistlead'] = $this->leads_model->get_availwplead();
        $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');

        // print_r($data['wplistlead']);
        $this->load->view('admin/leads/viewlead', $data);
    }

    public function update_custom_lead_remark()
    {
        $id = $_GET['id'];
        $name = $_GET['name'];
        $phonenumber = $_GET['phonenumber'];
        $email = $_GET['email'];
        $designation = $_GET['designation'];
        $company = $_GET['company'];
        $address = $_GET['address'];
        $data_source = $_GET['data_source'];
        $calling_objective = $_GET['calling_objective'];
        $added_by = $_GET['added_by'];
        $remark = $_GET['description'];
        $status = $_GET['status'];
        $meetingtimeto = $_GET['meetingtimeto'];
        $meetingtimefrom = $_GET['meetingtimefrom'];
        $status = $_GET['status'];
        $reassignlead = $_GET['reassignlead'];
        $next_calling = $_GET['nextcalling'];
        $reminder = $_GET['reminder'];
        
        if(isset($_GET['reminder']))
        {
            $reminder=1;
        }
        else
        {
            $reminder=0;
        }
        
        $date = date('Y-m-d');
        
        if($remark != null ) {
        $data = array('name' => $name, 'status' => $status, 'phonenumber' => $phonenumber, 'email' => $email, 'designation' => $designation, 'company' => $company, 'address' => $address, 'data_source' => $data_source, 'calling_objective' => $calling_objective, 'description' => $remark, 'meetingtimefrom' => $meetingtimefrom, 'meetingtimeto' => $meetingtimeto, 'next_calling' => $next_calling, 'status' => $status, 'lastcontact' => $date);  
        }
        else
        {
          
        $data = array('name' => $name, 'status' => $status, 'phonenumber' => $phonenumber, 'email' => $email, 'designation' => $designation, 'company' => $company, 'address' => $address, 'data_source' => $data_source, 'calling_objective' => $calling_objective, 'meetingtimefrom' => $meetingtimefrom, 'meetingtimeto' => $meetingtimeto, 'next_calling' => $next_calling,  'status' => $status, 'lastcontact' => $date);
        }
        
        print_r($this->input->post());

        if((isset($_GET['reminder'])) && ($reminder == '1') && ($next_calling != '0000-00-00 00:00:00'))
        {
            
            $this->db->select('rel_id');
            $this->db->where('rel_id',$id);
            $this->db->where('rel_type','lead');
            $query = $this->db->get('tblreminders');
            $checkreminder = $query->num_rows();
            if($checkreminder > 0)
            {
                $datareminder = array('description' => $remark, 'date' => $next_calling, 'isnotified' => 0, 'staff' => $added_by, 'rel_type' => 'lead', 'notify_by_email' => 1, 'creator' => $added_by);
                $this->db->where('rel_id',$id);
                $this->db->where('rel_type','lead');
                $this->db->update('tblreminders', $datareminder); 
            }
            else
            {
              $datareminder = array('description' => $remark, 'date' => $next_calling, 'isnotified' => 0, 'rel_id' => $id, 'staff' => $added_by, 'rel_type' => 'lead', 'notify_by_email' => 1, 'creator' => $added_by);
                $this->db->insert('tblreminders', $datareminder);  
            }
            
             
        }
        
        
        
       

        $this->db->where('id', $id);
        $this->db->update('tblleads', $data);
        if($remark != null) {
        $data2 = array('lead_id' => $id, 'remark' => $remark, 'added_by' => $added_by);
        $this->db->insert('tblleadremark', $data2);
        }
        $lead_id = $id;
        $staff_id = $reassignlead;
        $this->leads_model->reassign($lead_id, $staff_id);
        $data['success'] = "success";
        set_alert('success', "Lead Updated Successfully");
        return $data;
    }

    public function update_meeting_remark()
    {
        $id = $_GET['id'];
        $name = $_GET['name'];
        $phonenumber = $_GET['phonenumber'];
        $email = $_GET['email'];
        $designation = $_GET['designation'];
        $company = $_GET['company'];
        $address = $_GET['address'];
        $added_by = $_GET['added_by'];
        $meeting_remark = $_GET['meeting_remark'];
        $duration = $_GET['duration'];
        $meeting_cat = "Prospect";
        $date = date('Y-m-d');
        $data = array('meeting_cat' => $meeting_cat, 'meeting_remark' => $meeting_remark, 'duration' => $duration);
        /*print_r($data);*/
        $this->db->where('id', $id);
        $this->db->update('tblmeeting_scheduled', $data);
        $data2 = array('meeting_id' => $id, 'meeting_remark' => $meeting_remark, 'added_by' => $added_by, 'created_at' => date('Y-m-d H:i:s'));
        $this->db->insert('tblmeeting_remark', $data2);
        $data['success'] = "success";
        set_alert('success', "Meeting Remark Updated Successfully");
        return $data;
    }

    /**
     * @param string $id
     */
    public function custom_lead_filter($id = '')
    {
        $lead_id_end = $_GET['lead_id_end'];
        $this->db->where('lead_id_end', $lead_id_end);
        $data['name'] = $this->db->get('tblleads_custom')->result();
        $leads = $this->leads_model->get_allleads_filter();
        $this->printLeadData($leads);
    }

    public function custom_lead_filter_added($id = '')
    {

        $leads = $this->leads_model->get_allleads_filter_added();
        $this->printLeadData($leads);
    }


    private function printLeadData($leads)
    {
        $leadStatus = $this->leads_model->get_leadstatus('tblleadsstatus');
        $i = 1;
        if (!empty($leads)) {
            foreach ($leads as $lead) { ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><a href="#"
                           onclick="edit_product_catagory(this,<?= $lead->id; ?>);return false;"
                           data-id="<?= $lead->id; ?>" data-name="<?= $lead->name; ?>"
                           data-phonenumber="<?= $lead->phonenumber; ?>"
                           data-email="<?= $lead->email; ?>"
                           data-designation="<?= $lead->designation; ?>"
                           data-company="<?= $lead->company; ?>"
                           data-address="<?= $lead->address; ?>"
                           data-data_source="<?= $lead->data_source; ?>"
                           data-calling_objective="<?= $lead->calling_objective; ?>"
                           data-status="<?= $lead->status; ?>"
                           data-description="<?= $lead->description; ?>"> <?= $lead->name; ?></a></td>
                    <td><?= $lead->phonenumber; ?></td>
                    <td><?= $lead->company; ?></td>
                    <td><?= $lead->calling_objective; ?></td>
                    <td><? if ($lead->lastcontact == null) {
                            echo "";
                        } else {
                            echo date('d M, Y', strtotime($lead->lastcontact));
                        } ?></td>
                    <td><?= $lead->description; ?></td>
                    <td style="width:160px !important">
                        <select name="rate" id="lead_status_change"
                                data-lead-id="<?= $lead->id; ?>">
                            <?php foreach ($leadStatus as $status) {
                                echo sprintf('<option value="%s" %s>%s</option>', $status->id, $status->id == $lead->status ? 'selected' : '', $status->name);
                            } ?>
                        </select>
                    </td>
                    <td>
                        <?php if ($lead->status == 1) { ?>
                            <select class="rmList" data-lead_id="<?= $lead->id; ?>">
                                <?= rmlist($lead->assigned); ?>
                            </select>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo NOLEADFOUND;
        }
    }

    /**
     * @param string $id
     */
    public function filter_by_company($id = '')
    {
        $lead_id_end = $_GET['lead_id_end'];
        $this->db->where('lead_id_end', $lead_id_end);
        $data['name'] = $this->db->get('tblleads_custom')->result();
        $leads = $this->leads_model->filter_by_company();
        $this->printLeadData($leads);
    }

    public function filter_by_company_added($id = '')
    {

        $leads = $this->leads_model->filter_by_company_added();
        $this->printLeadData($leads);
    }

    /**
     * @param string $id
     */
    public function filter_by_data_source($id = '')
    {
        $lead_id_end = $_GET['lead_id_end'];
        $lead_id_start = $_GET['lead_id_start'];
        $this->db->where('lead_id_end', $lead_id_end);
        $data['name'] = $this->db->get('tblleads_custom')->result();
        $leads = $this->leads_model->filter_by_data_source();
        $this->printLeadData($leads);
    }

    public function filter_by_data_source_added($id = '')
    {

        $leads = $this->leads_model->filter_by_data_source_added();
        $this->printLeadData($leads);
    }

    /**
     * @param string $id
     */
    public function filter_by_calling_objective($id = '')
    {
        $lead_id_end = $_GET['lead_id_end'];
        $lead_id_start = $_GET['lead_id_start'];
        $this->db->where('lead_id_end', $lead_id_end);
        $data['name'] = $this->db->get('tblleads_custom')->result();
        $leads = $this->leads_model->filter_by_calling_objective();
        $this->printLeadData($leads);
    }

    public function filter_by_calling_objective_added($id = '')
    {

        $leads = $this->leads_model->filter_by_calling_objective_added();
        $this->printLeadData($leads);
    }

    public function filter_by_lastcontact($id = '')
    {
        $lead_id_end = $_GET['lead_id_end'];
        $lead_id_start = $_GET['lead_id_start'];
        $this->db->where('lead_id_end', $lead_id_end);
        $data['name'] = $this->db->get('tblleads_custom')->result();
        $leads = $this->leads_model->filter_by_lastcontact();
        $this->printLeadData($leads);
    }

    public function filter_by_lastcontact_added($id = '')
    {

        $leads = $this->leads_model->filter_by_lastcontact_added();
        $this->printLeadData($leads);
    }

    public function reassignlead()
    {
        $lead_id = $_GET['lead_id'];
        $staff_id = $_GET['staff_id'];
        $this->leads_model->reassign($lead_id, $staff_id);
        set_alert('success', "Lead Status Updated Successfully");
        return true;
    }

    function meeting_scheduled()
    {
        $data['title'] = 'Scheduled Meetings';
        $data['bodyclass'] = 'hide-sidebar';
        
        
        $data['leads'] = $this->leads_model->getMeetingScheduledLeads();
        $data['mstatus'] = $this->leads_model->get_leadstatus('tblmeetingstatus');
        /*print_r($data['leads']);
        exit;*/
        $this->load->view('admin/leads/meetingScheduledLeads', $data);
    }

    /**
     * Lead report Controller
     */
    public function lead_report()
    {
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $data['title'] = 'Lead Report';
        $data['start'] = $start;
        $data['end'] = $end;
        if($start)
        {
           $data['summary'] = get_leads_summary_date_wise($start, $end);
		   
		   $data['business_total']=get_tot_business_count($start, $end);
        }
        else
        {
            $month = date('m');
            $year = date('Y');
            if($month <= '3')
            {
                
                $fi_end_year = $year;
                $fi_start_year = $year-1;
            }
            else
            {
               $fi_end_year = $year+1;
               $fi_start_year = $year; 
            }
            
            $data['start'] = $fi_start_year.'-04-01';
            $data['end'] = $fi_end_year.'-03-31';
            
            $data['summary'] = get_leads_summary_date_wise($data['start'], $data['end']);
            
            $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : date('d, M, Y', strtotime($data['start'])) . ' To ' . date('d, M, Y', strtotime($data['end'])) ;
		   
		   $data['business_total']=get_tot_business_count($data['start'], $data['end']);
            
        }
        if(is_admin() || is_headtrm() )
        {
            $data['staffList'] = $this->staff_model->get('', ['active' => 1, 'department_id' => 13]);
            
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            
            //$this->db->where("tblbusiness.converted_by IN (".$arr.")",NULL, false);
             $data['staffList'] = $this->staff_model->get('', ['active' => 1, 'department_id' => 13, 'staffid' => $arr]);
            
        }
        
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources'] = $this->leads_model->get_source();
        $this->load->view('admin/leads/lead_report', $data);
    }

    public function meeting_report()
    {
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $data['title'] = 'Conducted Meetings';
        $data['start'] = $start;
        $data['end'] = $end;
        $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";
        $data['summary'] = get_meeting_summary_date_wise($start, $end);
        //$data['staffList'] = $this->staff_model->get('', ['active' => 1]);
        $data['staffList'] = $this->staff_model->get_hera_staff('', ['active' => 1, 'department_id' => 12]);
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources'] = $this->leads_model->get_source();
        $this->load->view('admin/leads/meeting_report', $data);
    }

    function lead_report_filtered($status = '', $start = '', $end = '', $staff_id = '')
    {
        $data['title'] = 'Lead Report';
        $data['status'] = $status;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['leads'] = $this->leads_model->filter_lead_last_connect($status, $start, $end, $staff_id);
        $this->load->view('admin/leads/filtered_leads', $data);
    }


    function business_mobilization()
    {
        $this->db->select('tbll.id, tbll.name as conname');
        $this->db->join('tblleads as tbll', 'tblbusiness.meet_lead_id = tbll.id');
        $this->db->where('transaction_date', null);
        $data['converted'] = $this->db->get('tblbusiness')->result();

        $data['category'] = $this->pmodel->get_categories();
        $data['title'] = "Business Mobilization";
        $this->load->view('admin/leads/business_mobilization', $data);
    }

    function fetch_company()
    {
        $catid = $this->input->post('category');
        //  echo $catid;
        if ($this->input->post('category')) {
            echo $this->leads_model->fetch_company($catid);
        }
    }

    function fetch_product()
    {
        $company = $this->input->post('company');
        if ($this->input->post('company')) {
            echo $this->leads_model->fetch_product($company);
        }
    }

    function fetch_credit()
    {
        $productname = $this->input->post('productname');
        if ($this->input->post('productname')) {
            echo $this->leads_model->fetch_credit($productname);
        }
    }
    
	function convert_amount_in_words()
	{
	  $amtVal= $this->input->post('amtVal');
	  $amtWords=$this->convertToIndianCurrency($amtVal);
	  echo $amtWords;
	}
	
    function save_bussiness_mob()
    {
        $meet_lead_id = $this->input->post('name');
        $new_investor = $this->input->post('new_investor');
        $transaction_date = $this->input->post('transaction_date');
        $category = $this->input->post('cat_id');
        $company = $this->input->post('company');
        $productname = $this->input->post('productname');
        $folio_number = $this->input->post('folio_number');
        $tenure = $this->input->post('tenure');
        $credit = $this->input->post('credit');
        $transaction_amount = $this->input->post('transaction_amount');
        $transaction_amount_words = $this->input->post('transaction_amount_words');
        $gross_credit_amount = $this->input->post('gross_credit_amount');
        $gst = $this->input->post('gst');
        $post_gst_credit = $this->input->post('post_gst_credit');
        $tds = $this->input->post('tds');
        $net_credit = $this->input->post('net_credit');
        $transaction_type = $this->input->post('transaction_type');


        if ($this->input->post('new_investor')) {
            $investor_name = $new_investor;
            $converted_by = $this->session->userdata('staff_user_id');


            $data = array('converted_by' => $converted_by, 'investor_name' => $investor_name, 'transaction_date' => $transaction_date, 'product_type' => $category, 'company' => $company, 'folio_number' => $folio_number, 'tenure' => $tenure, 'scheme' => $productname, 'transaction_type' => $transaction_type, 'transaction_amount' => $transaction_amount,'transaction_amount_words' => $transaction_amount_words, 'credit_rate' => $credit, 'gross_credit_amount' => $gross_credit_amount, 'gst_rate' => $gst, 'post_gst_credit' => $post_gst_credit, 'tds_rate' => $tds, 'net_credit' => $net_credit);

            //$this->db->where('meet_lead_id ', $meet_lead_id);
            $this->db->insert('tblbusiness', $data);
        } elseif ($this->input->post('name')) {
            $this->db->select('name');
            $this->db->where('id', $meet_lead_id);
            $query = $this->db->get('tblleads')->row();
            $investor_name = $query->name;


            $data = array('investor_name' => $investor_name, 'transaction_date' => $transaction_date, 'product_type' => $category, 'company' => $company, 'folio_number' => $folio_number, 'tenure' => $tenure, 'scheme' => $productname, 'transaction_type' => $transaction_type, 'transaction_amount' => $transaction_amount,'transaction_amount_words' => $transaction_amount_words, 'credit_rate' => $credit, 'gross_credit_amount' => $gross_credit_amount, 'gst_rate' => $gst, 'post_gst_credit' => $post_gst_credit, 'tds_rate' => $tds, 'net_credit' => $net_credit);

            $this->db->where('meet_lead_id ', $meet_lead_id);
            $this->db->update('tblbusiness', $data);
            
            $datalead = array('status' => 35);

            $this->db->where('id ', $meet_lead_id);
            $this->db->update('tblleads', $datalead);
        }
        /*print_r($this->input->post());
         exit;*/


        set_alert('success', "Submitted  Successfully");
        redirect($_SERVER['HTTP_REFERER']);


    }

    function fetch_transaction_type()
    {
        $transaction_type = $this->input->post('transaction_type');
        $category = $this->input->post('category');
        if ($this->input->post('transaction_type')) {
            echo $this->leads_model->fetch_transaction_type($transaction_type);
        }
    }


    function business_report()
    {

        $data['title'] = 'Business Report';
        $data['bodyclass'] = 'hide-sidebar';
        $data['business'] = $this->leads_model->business_report();
        $data['rmconverted'] = $this->leads_model->rmconverted();
        $data['bustatus'] = $this->leads_model->bustatus();
        $data['bpro_type'] = $this->leads_model->protype();
        $this->load->view('admin/leads/business_report', $data);

    }

    function custom_business_filter()
    {
        
        $leads = $this->leads_model->get_bussiness_filter();
        
        $this->printBussinessData($leads); 
    }
    
    function availablewp()
    {
        $data['title'] = 'Available Working Person';

        if ($this->input->post()) {
            $chdate = $this->input->post('date');
            $this->db->select('date');
            $this->db->where(array('tt.date' => $chdate));
            $query = $this->db->get('tblavailablewp tt');
            $checkdate = $query->num_rows();
            if ($checkdate > 0) {
                set_alert('warning', "WP List Exist For $chdate ");
                redirect(admin_url('leads/availablewp'));
            } else {
                $insert_data['date'] = $this->input->post('date');
                $telermids = $this->input->post('telermids');
                $wpids = $this->input->post('wpids');
                $total_count = count($telermids);

                for ($i = 0; $i < $total_count; $i++) {
                    $teamdeptrm_id = $this->input->post('teamdeptrm_id');
                    $inserdata['date'] = $insert_data['date'];
                    $inserdata['telerm'] = $telermids[$i];
                    $inserdata['wp'] = $wpids[$i];
                    $this->db->insert('tblavailablewp', $inserdata);
                }
                set_alert('success', _l('added_successfully', "Available WP"));

            }
            return redirect(admin_url('leads/availablewp'));
        }


        /*$data['bodyclass'] = 'hide-sidebar';*/
        $data['telerm'] = $this->leads_model->get_telerm('', 'staffid,CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');
        $data['availwp'] = $this->leads_model->get_avaliablewp('', 'staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');
        $data['assignedwp'] = $this->leads_model->getassignedwp();


        $this->load->view('admin/leads/availablewp', $data);
    }

    public function editavailablewp($id)
    {
        $team = $this->leads_model->getassignedwpById($id);
        if (!empty($team)) {
            $data['team'] = $team;
            $data['title'] = 'Edit Available Working Person For ' . $team->date;
            if ($this->input->post()) {
                $id = $this->input->post('date');
                $update_data['date'] = $this->input->post('date');


                $this->db->select('date');
                $this->db->where(array('tt.date' => $id));
                $query = $this->db->get('tblavailablewp tt');
                $checkdate = $query->num_rows();
                if ($checkdate > 0) {
                    $date = $this->input->post('date');
                }
                $teamDeptIds = $this->input->post('team_dept_id');


                $telermids = $this->input->post('telermids');
                $wpids = $this->input->post('wpids');
                $total_count = count($telermids);


                for ($i = 0; $i < $total_count; $i++) {
                    $teamDeptData = array('date' => $date, 'telerm' => $telermids[$i], 'wp' => $wpids[$i]);
                    if (!empty($teamDeptIds[$i])) {

                        $this->db->update('tblavailablewp', $teamDeptData, array('date' => $date));
                    } else {
                        $this->db->insert('tblavailablewp', $teamDeptData);
                    }
                }
                if (!empty($this->input->post('deleted_team_dept_ids'))) {
                    $ids = $this->input->post('deleted_team_dept_ids');
                    $this->db->query("DELETE FROM `tblavailablewp` WHERE `id` IN('$ids')");
                }
                set_alert('success', _l('success', _l('updated_successfully')));
                redirect(admin_url('leads/availablewp'));
            }
            $data['telerm'] = $this->leads_model->get_telerm('', 'staffid,firstname', 'result_array');
            $data['availwp'] = $this->leads_model->get_avaliablewp('', 'staffid,firstname', 'result_array');
            $this->load->view('admin/leads/editavailablewp', $data, FALSE);
        } else {
            set_alert('warning', _l('invalid', _l('invalid_request')));
            redirect(admin_url('leads/availablewp'));
        }
    }

    function deleavailablewp($id)
    {
        $this->db->where('date', $id);
        $this->db->delete('tblavailablewp');

        set_alert('success', "Assigned WP List For  $id ");
        redirect(admin_url('leads/availablewp'));
    }
    
    private function printBussinessData($leads)
    {
                                 if (!empty($leads)) { ?>
                            <table class="table dt-table scroll-responsive ">
                                <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th class="bold">Investor Name</th>
                                    <th class="bold">TRANSACTION DATE</th>
                                    <th class="bold">PRODUCT TYPE</th>
                                    <th class="bold">COMPANY</th>
                                    <th class="bold">FOLIO NUMBER</th>
                                    <th class="bold">TENURE</th>
                                    <th class="bold">SCHEME</th>
                                    <th class="bold">TRANSACTION TYPE</th>
                                    <th class="bold">TRANSACTION AMOUNT</th>
                                    <th class="bold">Credit Rate</th>
                                    <th class="bold">Gross Credit Amount</th>
                                    <th class="bold"> GST Rate</th>
                                    <th class="bold">Post GST Credit</th>
                                    <th class="bold">TDS Rate</th>
                                    <th class="bold">NET CREDIT</th>
                                    <th class="bold">Converted By</th>
                                    <th class="bold">Status</th>
                                </tr>
                                </thead>
                                <tbody class="">
                                <?php 
								$totTransactionAmt=0;
								$totNetCredit=0;
								foreach ($leads as $alllead) {
                                    $proName = $alllead->product_name;
                                    $proName = str_replace("(", "", $proName);
                                    $proName = str_replace(")", "", $proName);
                                    $proName = str_replace("&", "and", $proName);
                                    ?>
                                    <tr>
                                        <td><?= @++$i; ?>
                                        <? if ($alllead->status == "Verified") {
                                                            echo "";
                                                        } 
                                                        else
                                                        {
                                                            echo '
                              <a href="'.admin_url() .'leads/editbusinessreport/'.$alllead->id.'" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                              <a href="'.admin_url() .'leads/deletebusinessreport/'.$alllead->id.'" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>';
                                                        }
                              ?>
                                        
                                        
                                        </td>
                                        <td> <?= $alllead->investor_name; ?>
                                        </td>
                                        <td><?= $alllead->transaction_date; ?></td>
                                        <td><?= $alllead->pro_type; ?></td>
                                        <td><?= $alllead->company_name; ?></td>
                                        <td><?= $alllead->folio_number; ?></td>
                                        <td><?= $alllead->tenure; ?></td>
                                        <td><?= $proName; ?></td>
                                        <td><?= $alllead->transaction_type; ?></td>
                                        <td><?= $alllead->transaction_amount; ?></td>
                                        <td><?= str_replace('%', '', $alllead->credit_rate); ?></td>
                                        <td><?= $alllead->gross_credit_amount; ?></td>
                                        <td><?= $alllead->gst_rate; ?> </td>
                                        <td><?= $alllead->post_gst_credit; ?></td>
                                        <td><?= $alllead->tds_rate; ?> </td>
                                        <td><?= $alllead->net_credit; ?></td>
                                        <td>
                                        <?= $alllead->staff_res; ?></td>
                                        
                                        <td>
                                            <select <?php if(($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) { } else {echo "disabled"; }  ?> class="form-control  business_status_list" name="business_status" id="business_statusval" data-lead_id="<?= $alllead->id; ?>">
                                                <option value="New" <?php if($alllead->status == "New") { echo "Selected"; } ?>>Unverified</option>
                                                <option value="Hold" <?php if($alllead->status == "Hold") { echo "Selected"; } ?>>Hold</option>
                                                <option value="Verified" <?php if($alllead->status == "Verified") { echo "Selected"; } ?>>Verified</option>
                                                <option value="Rejected" <?php if($alllead->status == "Rejected") { echo "Selected"; } ?>>Rejected</option>
                                                
                                            </select>
                                        </td>
                                        
<script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>
<script>
     $("select.business_status_list").change(function(){
      const business_status = $(this).children("option:selected").val();
            business_id = $(this).data('lead_id');
        url = "<?= base_url('admin/leads/bussiness_status_update') ?>";
        $.get(url, {
                business_status: business_status,
                business_id: business_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
    });
</script>
                                    </tr>
                                <?php 	
								$totTransactionAmt = $totTransactionAmt + $alllead->transaction_amount;
							    $totNetCredit = $totNetCredit + $alllead->net_credit;
							
							  } 
							    $n=$totTransactionAmt;
								$nCrdAmt=$totNetCredit;
								
								if ($n > 0 && $n < 1000) {
									// 1 - 999
									$n_format = floor($n);
									$suffix = '';
								} else if ($n >= 1000 && $n < 1000000) {
									// 1k-999k
									$n_format = floor($n / 1000);
									$suffix = 'K+';
								} else if ($n >= 1000000 && $n < 1000000000) {
									// 1m-999m
									$n_format = floor($n / 1000000);
									$suffix = 'M+';
								} else if ($n >= 1000000000 && $n < 1000000000000) {
									// 1b-999b
									$n_format = floor($n / 1000000000);
									$suffix = 'B+';
								} else if ($n >= 1000000000000) {
									// 1t+
									$n_format = floor($n / 1000000000000);
									$suffix = 'T+';
								}
								$rtAmt=!empty($n_format . $suffix) ? $n_format . $suffix : 0;
								
								
								
								if ($nCrdAmt > 0 && $nCrdAmt < 1000) {
									// 1 - 999
									$nCrd_format = floor($nCrdAmt);
									$suffixCrd = '';
								} else if ($nCrdAmt >= 1000 && $nCrdAmt < 1000000) {
									// 1k-999k
									$nCrd_format = floor($nCrdAmt / 1000);
									$suffixCrd = 'K+';
								} else if ($nCrdAmt >= 1000000 && $nCrdAmt < 1000000000) {
									// 1m-999m
									$nCrd_format = floor($nCrdAmt / 1000000);
									$suffixCrd = 'M+';
								} else if ($nCrdAmt >= 1000000000 && $nCrdAmt < 1000000000000) {
									// 1b-999b
									$nCrd_format = floor($nCrdAmt / 1000000000);
									$suffixCrd = 'B+';
								} else if ($nCrdAmt >= 1000000000000) {
									// 1t+
									$nCrd_format = floor($nCrdAmt / 1000000000000);
									$suffixCrd = 'T+';
								}
								$netCreditAmt=!empty($nCrd_format . $suffixCrd) ? $nCrd_format . $suffixCrd : 0;
								?>
                                <tr>
                                    <th ></th>
                                    <th ></th>
                                    <th ></th>
                                    <th ></th>
                                    <th ></th>
                                    <th ></th>
                                    <th ></th>
                                    <th ></th>
                                    <th style="text-align:right;font-weight: bold;">Total Transaction Amount</th>
                                    <th style="text-align:left;font-weight: bold;"><span id="sum_transaction_amount">Rs. <?php echo $n; ?></span></th>
									<th ></th>
									<th ></th>
									<th ></th>
									
                                    <th colspan="2" style="text-align:right;font-weight: bold;">Total Net Credit</th>
                                    <th style="text-align:left;font-weight: bold;"><span >Rs. <?php echo $nCrdAmt; ?></span></th>
									<th ></th>
									<th ></th>
									<th ></th>
								
								</tr>
                                </tbody>
                            </table>
                            <?php

                        } else {
                            echo "No Business Report Found";
                        } 
						
    }
    
    public function bussiness_status_update()
    {
      $business_status =   $_GET['business_status']; 
      $business_id = $_GET['business_id'];
      $data = array('status' => $business_status);
      
      $this->db->where('id', $business_id);
      $this->db->update('tblbusiness', $data);
      set_alert('success',  "Status Updated Successfully");
    }
    
    public function meetingtimewp()
    {
        $date = $this->input->post('meetingtimefrom');
        $time = new DateTime($date);
        $team_id = $time->format('Y-m-d');
        echo $team_id;
        echo $this->leads_model->getassignedwpBymdate($team_id);
        
    }
	
	public function convertToIndianCurrency($number) {
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10]).' Paise'  : '';
    return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . " Only";
}

public function deletebusinessreport($id)
    {
        $businessid = $this->uri->segment(4);
        $response = $this->leads_model->deletebusinessreport($businessid);
        set_alert('success', _l('deleted', "Business Report"));
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function editbusinessreport($id)
    {
            $businessid = $this->uri->segment(4);
            $this->db->select('*');
            $this->db->where('id', $businessid);
            $data['business_data'] = $this->db->get('tblbusiness')->row();
            $data['category'] = $this->pmodel->get_categories();
            //print_r($data['business_data']);
            $data['title'] = 'Edit Business ';
            
            $this->load->view('admin/leads/edit_business', $data, FALSE);
    }
    
  public  function save_edited_bussiness_mob()
    {
        
        $new_investor = $this->input->post('new_investor');
        $transaction_date = $this->input->post('transaction_date');
        $category = $this->input->post('cat_id');
        $company = $this->input->post('company');
        $productname = $this->input->post('productname');
        $folio_number = $this->input->post('folio_number');
        $tenure = $this->input->post('tenure');
        $credit = $this->input->post('credit');
        $transaction_amount = $this->input->post('transaction_amount');
        $transaction_amount_words = $this->input->post('transaction_amount_words');
        $gross_credit_amount = $this->input->post('gross_credit_amount');
        $gst = $this->input->post('gst');
        $post_gst_credit = $this->input->post('post_gst_credit');
        $tds = $this->input->post('tds');
        $net_credit = $this->input->post('net_credit');
        $transaction_type = $this->input->post('transaction_type');
        $business_id =  $this->input->post('business_id');
        
        


        if ($this->input->post('new_investor')) {
            $investor_name = $new_investor;
            //$converted_by = $this->session->userdata('staff_user_id');


            $data = array('investor_name' => $investor_name, 'transaction_date' => $transaction_date, 'product_type' => $category, 'company' => $company, 'folio_number' => $folio_number, 'tenure' => $tenure, 'scheme' => $productname, 'transaction_type' => $transaction_type, 'transaction_amount' => $transaction_amount,'transaction_amount_words' => $transaction_amount_words, 'credit_rate' => $credit, 'gross_credit_amount' => $gross_credit_amount, 'gst_rate' => $gst, 'post_gst_credit' => $post_gst_credit, 'tds_rate' => $tds, 'net_credit' => $net_credit);

            //$this->db->where('meet_lead_id ', $meet_lead_id);
            $this->db->where('id', $business_id);
            $this->db->update('tblbusiness', $data);
            set_alert('success', "Business Updated  Successfully");
            redirect(admin_url('leads/business_report'));
        } 
    }
    
    public function submit_dcr()
    {
            $data['title'] = 'Submit Daily Calling Report ';
            $data['leadstatus'] = $this->leads_model->get_status();
            $this->load->view('admin/leads/submit_dcr', $data);
    }
	
	public function get_dcr_call_count()
    {
        $work_date=$_REQUEST['work_date'];
		$staffId=$_REQUEST['staff_id'];
		$getCount=$this->leads_model->get_dcrCallCount($work_date,$staffId);
		echo $getCount;
		
    }
	
	public function get_dcr_call_data()
    {
        $work_date=$_REQUEST['work_date'];
		$staffId=$_REQUEST['staff_id'];
		
		if($staffId!=0)
		{
		   $this->db->where('assigned', $staffId);
		   $this->db->where('lastcontact', $work_date);
		}
		else
		{
		   $this->db->where('lastcontact', $work_date);
		}

        $this->db->distinct();
		$this->db->select('data_source');
        $getData= $this->db->get('tblleads')->result_array();
		$a=1;
        foreach($getData as $rt)
		{
		  $data_source=$rt['data_source'];
		  if($staffId!=0)
			{
			   $this->db->where('assigned', $staffId);
		       $this->db->where('lastcontact', $work_date);
		       $this->db->where('data_source', $data_source);
			}
			else
			{
			   $this->db->where('lastcontact', $work_date);
		       $this->db->where('data_source', $data_source);
			}
			
			$this->db->distinct();
	    	$this->db->select('calling_objective');
            $getDataTwo = $this->db->get('tblleads')->result_array();
			foreach($getDataTwo as $rtt)
			{
			    $callingObjective=$rtt['calling_objective'];
			    $leadstatus=$this->leads_model->get_status();
			  ?>
			    <tr>
                    <?php foreach($leadstatus as $str) {
                    $status=$str['id'];
                    $status_name=$str['name'];
                    
                    if($staffId!=0)
        			{
        			   $this->db->where('assigned', $staffId);
        		       $this->db->where('lastcontact', $work_date);
        		       $this->db->where('data_source', $data_source);
        		       $this->db->where('calling_objective', $callingObjective);
        		       $this->db->where('status', $status);
        			}
        			else
        			{
        			   $this->db->where('lastcontact', $work_date);
        		       $this->db->where('data_source', $data_source);
        		       $this->db->where('calling_objective', $callingObjective);
        		       $this->db->where('status', $status);
        			}
        			
                    $this->db->select('*');
                    $getRowCount = $this->db->get('tblleads')->result_array();
                    $totalRows=count($getRowCount);
                    ?>
                    <td>
                      <?= $totalRows; ?>
					  <input type="hidden" name="call_count<?php echo $a; ?>[]" value="<?php echo $totalRows ?>">
					  <input type="hidden" name="status<?php echo $a; ?>[]" value="<?php echo $status ?>">
					  <input type="hidden" name="status_name<?php echo $a; ?>[]" value="<?php echo $status_name ?>">
                    </td>
                    <? } ?> 
                    <td>
                      <?php echo $callingObjective; ?>
					  <input type="hidden" name="calling_objective[]" value="<?php echo $callingObjective; ?>">
                    </td>
                    <td>
                        <?php echo $data_source; ?>
						<input type="hidden" name="data_source[]" value="<?php echo $data_source; ?>">
                    </td>
                    <td>
                       <input type="text" name="calling_pitch[]" placeholder="Calling Pitch">
                    </td>
                </tr>
			    <input type="hidden" name="row_number[]" value="<?php echo $a; ?>"> 
			  <?php
			}
			$a++;
		}
		
    }
    
    public function saveDcr()
    {
        $date_work=$_REQUEST['date_work'];
        $staff_id=$_REQUEST['staff_id'];
        $total_calls=$_REQUEST['total_calls'];
        $other_work=$_REQUEST['other_work'];
        $other_work_duration=$_REQUEST['other_work_duration'];
		
        $row_count=count($_REQUEST['row_number']);
        if((!isset($_REQUEST['row_number'])) || ($row_count==0))
        {
              $inData=array();
    		  $inData['staff_id']=$staff_id;
    		  $inData['dcr_date']=$date_work;
    		  $inData['total_call']=$total_calls;
    		  $inData['calling_objective']=$calling_objective;
    		  $inData['data_source']=$data_source;
    		  $inData['calling_pitch']=$calling_pitch;
    		  $inData['other_work']=$other_work;
    		  $inData['other_work_duration']=$other_work_duration;
    		  $this->db->insert('tbldcr', $inData);
    		  print_r($this->db->last_query());
    		  $insert_id = $this->db->insert_id();
    		  
    		  $unique_dcr_id=$insert_id;
    		  $array=array('unique_dcr_id' => $unique_dcr_id);
    		  $this->db->where('id', $insert_id);
    		  $this->db->update('tbldcr',$array);
        }
        else
        {
            for($a=0;$a<$row_count;$a++)
    		{
    		  $calling_objective=$_REQUEST['calling_objective'][$a];
    		  $data_source=$_REQUEST['data_source'][$a];
    		  $calling_pitch=$_REQUEST['calling_pitch'][$a];
    		  
    		  $inData=array();
    		  $inData['staff_id']=$staff_id;
    		  $inData['dcr_date']=$date_work;
    		  $inData['total_call']=$total_calls;
    		  $inData['calling_objective']=$calling_objective;
    		  $inData['data_source']=$data_source;
    		  $inData['calling_pitch']=$calling_pitch;
    		  $inData['other_work']=$other_work;
    		  $inData['other_work_duration']=$other_work_duration;
    		  
    		  if($a!=0)
    		  {
    		    $this->db->order_by('id', 'DESC');
    			$this->db->limit(1);
    			$getRt=$this->db->get('tbldcr')->row();
    			$unique_dcr_id=$getRt->unique_dcr_id;
    		  }
    		  
    		  $this->db->insert('tbldcr', $inData);
    		  $insert_id = $this->db->insert_id();
    		  if($a==0)
    		  {
    		    $unique_dcr_id=$insert_id;
    			$array=array('unique_dcr_id' => $unique_dcr_id);
    			$this->db->where('id', $insert_id);
    			$this->db->update('tbldcr',$array);
    		  }
    		  else
    		  {
    			$array=array('unique_dcr_id' => $unique_dcr_id);
    			$this->db->where('id', $insert_id);
    			$this->db->update('tbldcr',$array);
    		  }
    
    		  $row_number=$_REQUEST['row_number'][$a];
    		  $call_name="call_count".$row_number;
    		  $statusCount=count($_REQUEST[$call_name]);
    		  for($b=0;$b<$statusCount;$b++)
    		  {
    		    $call_count_name="call_count".$row_number;
    		    $status_name="status".$row_number;
    		    $statusname_name="status_name".$row_number;
    			
    			$call_count=$_REQUEST[$call_name][$b];
    			$status=$_REQUEST[$status_name][$b];
    			$status_nameData=$_REQUEST[$statusname_name][$b];
    			
    			$inArray=array();
    			$inArray['dcr_id']=$insert_id;
    			$inArray['call_count']=$call_count;
    			$inArray['status']=$status;
    			$inArray['status_name']=$status_nameData;
    			
    			$this->db->insert('tbldcr_status_count', $inArray);
    		  }
    		}
    		
        }
		
		redirect(admin_url('leads/dcr_list'));
        exit;
    }
	
	public function dcr_list()
	{
	  $data['title'] = 'Daily Calling Report ';
      $data['leadstatus'] = $this->leads_model->get_status();
	  
	   if (is_admin() || is_headtrm())
	   {
	     $this->db->distinct();
	     $this->db->select('unique_dcr_id');
         $data['dcrList'] = $this->db->get('tbldcr')->result_array();
		 
		
		 $this->db->select('`tblstaff`.`staffid`,`tblstaff`.`firstname`,`tblstaff`.`lastname`,`tbldcr`.`staff_id`');
		 $this->db->join('tbldcr', 'tblstaff.staffid = tbldcr.staff_id', 'left');
		 $this->db->group_by('`tbldcr`.`staff_id`');
		 $data['staff_list']=$this->db->get('tblstaff')->result_array();
		 
	   }
	   else
	   {
	     $loginid = $this->session->userdata('staff_user_id');
	     $this->db->distinct();
		 $this->db->where('staff_id',$loginid);
	     $this->db->select('unique_dcr_id');
         $data['dcrList'] = $this->db->get('tbldcr')->result_array();
         
         $this->db->where('staffid',$loginid);
         $data['staff_list']=$this->db->get('tblstaff')->result_array();
	   }
      
            
      $this->load->view('admin/leads/dcr_list', $data);
	}
	
	public function custom_dcr_filter()
	{
	    $filterrm=$_REQUEST['filterrm'];
	    $transctiondatestart=$_REQUEST['transctiondatestart'];
	    $transctiondateend=$_REQUEST['transctiondateend'];

	     $this->db->distinct();
	     if($filterrm!="")
	     {
	        $this->db->where_in('staff_id', $filterrm); 
	     }
	     
	     if($transctiondatestart!="")
	     {
	         $this->db->where('dcr_date >=', $transctiondatestart);
	     }
	     
	     if($transctiondateend!="")
	     {
	        $this->db->where('dcr_date <=', $transctiondateend);
	     }

	     $this->db->select('unique_dcr_id');
         $dcrList = $this->db->get('tbldcr')->result_array();
         
         $leadstatus = $this->leads_model->get_status();
         
         foreach($dcrList as $rt)
		 {
			$unique_dcr_id=$rt['unique_dcr_id'];
									 
			$this->db->where('unique_dcr_id' , $unique_dcr_id);
			$this->db->select('*');
			$getData=$this->db->get('tbldcr')->result_array();
			$sr=$getData[0];
			$didOne=$sr['id'];
			$staffId=$sr['staff_id'];
									 
			$this->db->where('staffid' , $staffId);
			$this->db->select('*');
			$getStaff=$this->db->get('tblstaff')->result_array();
									 
		?>
			<tr>
				<td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getStaff[0]['firstname']; ?> <?php echo $getStaff[0]['lastname']; ?></td>
				<td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['dcr_date']; ?> </td>
				<td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['total_call']; ?> </td>
				<td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['other_work']; ?> </td>
				<td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['other_work_duration']; ?> </td>
									   
				<?php 
					foreach($leadstatus as $str)
					{
					    $sid=$str['id'];
						$this->db->where('status' , $sid);
						$this->db->where('dcr_id' , $didOne);
						$this->db->select('call_count');
						$getStatusData=$this->db->get('tbldcr_status_count')->result_array();
				?>
					<td class="text-center"><?= $getStatusData[0]['call_count']; ?></td>
				<? 
					}
				?> 
					<td class="text-center"><?php echo $sr['calling_objective']; ?></td>
				    <td class="text-center"><?php echo $sr['data_source']; ?></td>
					<td class="text-center"><?php echo $sr['calling_pitch']; ?></td>
			</tr>
		<?php
			foreach($getData as $sr)
			{
				$did=$sr['id'];
				if($did!=$didOne)
				{
		?>
				<tr>
					<?php
						foreach($leadstatus as $str)
						{
							$sid=$str['id'];
							$this->db->where('status' , $sid);
							$this->db->where('dcr_id' , $did);
							$this->db->select('call_count');
							$getStatusData=$this->db->get('tbldcr_status_count')->result_array();
					?>
						<td class="text-center"><?= $getStatusData[0]['call_count']; ?></td>
					<? 
						}
					?> 
						<td class="text-center"><?php echo $sr['calling_objective']; ?></td>
						<td class="text-center"><?php echo $sr['data_source']; ?></td>
						<td class="text-center"><?php echo $sr['calling_pitch']; ?></td>
				</tr>
					<?php
				}
				}
	    }
	    
	}
	
	public function duplicate_entry()
	{
	  $data['title'] = 'Duplicate Entry';
      
	   if (is_admin() || is_headtrm())
	   {
		 $data['getData']=$this->db->query("SELECT phonenumber, COUNT(*) as cnt FROM `tblleads` GROUP BY `phonenumber` HAVING `cnt` > 1")->result_array();
		 
		 $this->load->view('admin/leads/duplicate_entry', $data);
		 
	   }
	   else
	   {
	     redirect('admin/dashboard');
	   }

	}
	
	public function view_duplicate_list($number)
	{
	    $data['title'] = 'Duplicate Entry List';
      
	   if (is_admin() || is_headtrm())
	   {
		    $useraid = $this->session->userdata('staff_user_id');
            $this->db->where('tblleads.phonenumber', $number);
            $this->db->select('CONCAT(tbll.firstname, ' . ', tbll.lastname) AS fullname, tbll.staffid  ,tblleads.*');
            $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
            $data['getLeads'] = $this->db->get('tblleads')->result();

		 
		 $this->load->view('admin/leads/duplicate_entry_detail', $data);
		 
	   }
	   else
	   {
	     redirect('admin/dashboard');
	   }

	}
	
	public function delete_duplicateleads()
    {

        $leadId = implode(',',$_REQUEST['delete_id']);
		
		$exLeadId=explode(',',$leadId);
		$cnt=count($exLeadId);
		for($a=0;$a<$cnt;$a++)
		{
		  $lead_id=$exLeadId[$a];
		  $success = $this->leads_model->delete_addedleads($lead_id);
          set_alert('success', _l('deleted', "Lead"));
		}

        redirect($_SERVER['HTTP_REFERER']);
    }
    
     public function exportall($id = '')
    {
        close_setup_menu();
        if (!is_staff_member()) {
            access_denied('Leads');
        }
        $data['switch_kanban'] = true;
        if ($this->session->userdata('leads_kanban_view') == 'true') {
            $data['switch_kanban'] = false;
            $data['bodyclass'] = 'hide-sidebar';
        }
        $data['staff'] = $this->staff_model->get('', ['active' => 1]);
        if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
            $this->load->model('gdpr_model');
            $data['consent_purposes'] = $this->gdpr_model->get_consent_purposes();
        }
        $data['summary'] = get_leads_summary();
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources'] = $this->leads_model->get_source();
        $data['title'] = _l('leads');
        $data['leadid'] = $id;
        $this->load->view('admin/leads/exportall', $data);
    }
    
    
    // create xlsx
    public function createXLS() {
        
        $importedname1 = $this->uri->segment(7);
        $importedname2 = str_replace(' ', '',$importedname1);
        $importedname = "Lead_exported";
    // create file name
        $fileName = 'data-'.time().'.xlsx';  
    // load excel library
        $this->load->library('excel');
        $empInfo = $this->leads_model->exportalllead();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Contact Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Designation');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Company');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Address'); 
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Email Id');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Data Source');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Calling Objective');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Calling Date'); 
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Next Calling  Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Remark');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Status');      
        // set Row
        $rowCount = 2;
        foreach ($empInfo as $element) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['phonenumber']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['designation']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['company']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['address']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['email']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element['data_source']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $element['calling_objective']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $element['lastcontact']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $element['next_calling']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $element['description']);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $element['statusname']);
            $rowCount++;
        }
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($importedname.$fileName);
    // download file
        header("Content-Type: application/vnd.ms-excel");
        redirect($importedname.$fileName);        
    }
}
