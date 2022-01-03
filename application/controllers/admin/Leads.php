<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class Leads extends Admin_controller

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
    public function importleads()
    {
        
            $useraid = $this->session->userdata('staff_user_id');
            
            $data['useraid'] = $useraid;
            $this->db->where('assigned', $useraid);
            $data['assignedleads'] = $this->db->get('tblleads')->result();
            $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
            $data['get_company'] = $this->leads_model->get_company2();
            $data['get_language'] = $this->leads_model->get_language('tblleads');
            $data['get_manuStatus'] = $this->leads_model->get_manuStatus('tblleads');
            $data['get_createdDate'] = $this->leads_model->get_createdDate('tblleads');
            $data['get_publishedearlier'] = $this->leads_model->get_publishedearlier('tblleads');
            $data['get_adName'] = $this->leads_model->get_adName('tblleads');
            $data['data_source'] = $this->leads_model->get_data_source2();
            $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            //$data['bodyclass'] = 'hide-sidebar';
            $this->load->view('admin/leads/importleads', $data);
       
    }




function getAjaxDatatable(){
    $this->db->query("select tblbusiness.id as pro_id,tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name from tblbusiness")
    ->result();
    }


public function table()

    {

       

        if (!is_staff_member()) {

            ajax_access_denied();

        }

        

        // $this->app->get_table_data('leads');

         

         

        

    }









 

    public function table_Anuj()

    {

       

        if (!is_staff_member()) {

            ajax_access_denied();

        }

        

        // $this->app->get_table_data('leads');

        $data = $this->leads_model->get_source();

         $totalCount = $this->leads_model->business_report_get_total();

        $json_data = array(

            "draw"            => intval( $_POST['draw'] ),   

            "recordsTotal"    => intval( $totalCount ),  

            "recordsFiltered" => intval($totalCount),

            "aaData"            => $data   // total data array

            );

            echo json_encode($json_data);

        

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

        //$this->db->group_by("remark");

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

        //$this->db->group_by("remark");

        $data['allleadremark'] = $this->db->get(' tblleadremark')->result();

        $this->db->where('meeting_id', $lead_idnew);

        //$this->db->group_by("meeting_remark");

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

        $all_d = $this->db->get_where('tblleads',array('id'=>$lead_id))->row();
        $data = array(
            'email' => $all_d->email,
            'assigned' => $all_d->assigned,
            'lead_adname' => $all_d->lead_adname,
            'lead_author_msstatus' => $all_d->lead_author_msstatus,
            'lead_author_name' => $all_d->lead_author_name,
            'phonenumber' => $all_d->phonenumber,
            'lead_ad_id' => $all_d->lead_ad_id,
            'lead_author_mslanguage' => $all_d->lead_author_mslanguage,
            'lead_publishedearlier' => $all_d->lead_publishedearlier,
            'create_at' => $all_d->lead_created_date,
            'lead_id' => $all_d->id,
            'remarks' => $all_d->remarks,
        );
        // print_r($data);
       $this->db->insert('lead_recyclebin',$data);
    //    echo $this->db->last_query();die;

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

        $useraid = $this->session->userdata('staff_user_id');
        $data['allleads'] = $this->leads_model->getallleads('tblleads_custom');
        $data['staff_role'] = $this->leads_model->getstaffrole('tblstaff', $useraid);


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



    public function view_custom_lead__old($id = '')

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
        //   print_r($_POST);
           $id = $_POST['id'];
           $no_of_books= $_POST['books'];

           $total_book = $this->db->select('total_no_of_books')->get_where('tblleads',array('id'=>$id))->row();
           if($total_book){
               $no_of_books1 = $total_book->total_no_of_books+$no_of_books;
           }else{}
        // echo $id = $_POST['id'];
        // print_r($_FILES);  exit;
            $filename = $_FILES['file']['name'];
            // echo $filename;die;
            $ckeck_image = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
              
                if($ckeck_image->lead_payment_reciept){
                    unlink('assets/images/payment_receipt/'.$ckeck_image->lead_payment_reciept);
                }
            
                
            $location = "assets/images/payment_receipt/".$filename;
            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);

            /* Valid extensions */
            //$valid_extensions = array("jpg","jpeg","png","");
            $response = 0;
            //if(in_array(strtolower($imageFileType), $valid_extensions)) {
            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
             $response = $location;
            }
           }
            
        $id = $_POST['id'];
        $otarray=$_POST['otarray'];
        $name = $_POST['name'];
        $booktitle = $_POST['booktitle'];
        $otherphonenumber = $_POST['otherphonenumber'];
        $PublishedEarlier = $_POST['publishedEarlier'];
        $full_name = $_POST['full_name'];
        $phonenumber = $_POST['phonenumber'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $designation = $_POST['designation'];
        $company = $_POST['company'];
        $address = $_POST['address'];
        $data_source = $_POST['data_source'];
        $adset_name = $_POST['data_source'];
        $calling_objective = $_POST['calling_objective'];
        $added_by = $_POST['added_by'];
        $remark = $_POST['description'];
        $categorisation = $_POST['status'];
        $status = $_POST['status'];
        $meetingtimeto = $_POST['meetingtimeto'];
        $meetingtimefrom = $_POST['meetingtimefrom'];
        $status = $_POST['status'];
        $reassignlead = $_POST['reassignlead'];
        $next_calling = $_POST['nextcalling'];
        $reminder = $_POST['reminder'];
        $assigned = $_POST['assigned'];
        $book_format = $_POST['book_format'];
        $manuscriptStatus = $_POST['manuscriptStatus'];
        $bookLanguage = $_POST['bookLanguage'];
       // echo "<pre>";print_r($_GET);exit;
        
         $package_cost = $_POST['package_cost'];
        $booking_amount = $_POST['booking_amount'];
        $finstallment = $_POST['finstallment'];
        $final_payment = $_POST['final_payment'];
        $gst_number = $_POST['gst_number'];
        $state = $_POST['state'];
      //  echo $gst_number;
        //return $gst_number;die;
        if($filename){
            $aquiredData = array(
        "lead_package_cost"=>$package_cost,
        "lead_booking_amount"=>$booking_amount,
        "lead_first_installment"=>$finstallment,
        "lead_final_payment"=>$final_payment,
        "lead_gst_number"=>$gst_number,
         "state_create_p"=>$state,
        "lead_payment_reciept"=>$filename
        );
        }else{
                $aquiredData = array(
        "lead_package_cost"=>$package_cost,
        "lead_booking_amount"=>$booking_amount,
        "lead_first_installment"=>$finstallment,
        "lead_final_payment"=>$final_payment,
        "lead_gst_number"=>$gst_number,
         "state_create_p"=>$state,
        );
        }
         if($status == "39"){

            $aquiredData['status']='39';
            $aquiredData['lead_status']='1';

                        $lead_details = $this->db->get_where('tblleads',array('id'=>$id))->row();
                        $proj_name =  $lead_details->lead_author_name.'_'.$lead_details->lead_booktitle;

                         $by = $this->session->userdata('staff_user_id');
                        $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                         ->from('tblstaff')
                         ->join('tblroles', 'tblstaff.role_id = tblroles.roleid');
                          $this->db->where('tblstaff.staffid',$by);
                        $query = $this->db->get();

                        $ret = $query->row();
                        // print_r($ret);
                         // echo $this->db->last_query();exit;
                         $data1 = array(
                            'notify_to'=> 49,
                            'user_id'=> $id,
                            'take_by'=> $by,
                            'role' => $ret->name,
                            'project_name' => $proj_name,
                            'author_name' => $lead_details->lead_author_name,
                            'book_name' => $lead_details->lead_booktitle,
                            'action' => 16,
                            'message' => 'Project Aquired Successfully',
                            'discription' => ''.$proj_name.' Project Aquired Successfully by '.$ret->firstname.' '.$ret->lastname,
                        );
                        $this->db->insert('lead_all_action',$data1);

        }
        $this->db->where('id', $id);
        $result = $this->db->update('tblleads',$aquiredData);
        
        $this->db->select('assigned');
        $this->db->where('id',$id);
        $res1 = $this->db->get('tblleads')->row();
        $mainassigned = $res1->assigned;
        if($remark != ''){
           $calling_date = date("d/m/Y");
           $lead_calling_date = date("Y-m-d");
           $created_date = date("Y-m-d h:i:s"); 
        }else{
           $calling_date = date("d/m/Y");
           $lead_calling_date = date("Y-m-d");
           $created_date = date("Y-m-d h:i:s"); 
           

        }
        if(isset($_POST['reminder']))
        
        {
            $reminder=1;
        }
        else
        {
            $reminder=0;
        }
        if(isset($_POST['reminder']) && $_POST['reminder']!='')
        
        {
            $rem=1;
        }
        else
        {
            $rem=0;
        }
        
        $date = date('Y-m-d');
        
        if($remark != null ) {
   

        $data = array('lead_bookformat'=>$book_format,'lead_callingdate'=>$calling_date,'lead_publishedearlier'=>$PublishedEarlier,'lead_booktitle'=>$booktitle,'otherphonenumber'=>$otherphonenumber,'lead_author_name' => $name,'lead_author_name' => $name,  'phonenumber' => $phonenumber, 'email' => $email, 'designation' => $designation, 'company' => $company, 'address' => $address, 'data_source' => $data_source,  'calling_objective' => $calling_objective,  'meetingtimefrom' => $meetingtimefrom, 'meetingtimeto' => $meetingtimeto, 'next_calling' => $next_calling, 'status' => $status, 'lastcontact' => $date, 'assigned' => $assigned,'lead_category_id' => $categorisation,'lead_author_msstatus'=>$manuscriptStatus,'lead_author_mslanguage'=>$bookLanguage, 'ImEx_NextcallingDate' => $next_calling, 'ImEx_callingDate' => $calling_date, 'ImEx_CreatedAt' => $calling_date ,'lead_acquired_date'=> $date,'no_of_books'=>$no_of_books,'total_no_of_books'=>$no_of_books1,'lead_calling_date'=>$lead_calling_date);  
        }
        else
        {
          
        $data = array('lead_bookformat'=>$book_format,'lead_callingdate'=>$calling_date,'lead_publishedearlier'=>$PublishedEarlier,'lead_booktitle'=>$booktitle,'otherphonenumber'=>$otherphonenumber,'lead_author_name' => $name,'lead_author_name' => $name, 'status' => $status, 'phonenumber' => $phonenumber, 'email' => $email, 'designation' => $designation, 'company' => $company, 'address' => $address, 'data_source' => $data_source, 'calling_objective' => $calling_objective, 'meetingtimefrom' => $meetingtimefrom, 'meetingtimeto' => $meetingtimeto, 'next_calling' => $next_calling,  'status' => $status, 'lastcontact' => $date, 'assigned' => $assigned,'lead_category_id' => $categorisation,'lead_author_msstatus'=>$manuscriptStatus,'lead_author_mslanguage'=>$bookLanguage, 'ImEx_NextcallingDate' => $next_calling, 'ImEx_callingDate' => $calling_date, 'ImEx_CreatedAt' => $calling_date, 'lead_acquired_date'=> $date,'no_of_books'=>$no_of_books,'total_no_of_books'=>$no_of_books1,'lead_calling_date'=>$lead_calling_date);
        }
        
       // print_r($data);exit;
        //if((isset($_POST['reminder'])) && ($reminder == '1') && ($next_calling != '0000-00-00 00:00:00')){

            if($next_calling != '0000-00-00 00:00:00'){
            $this->db->select('rel_id');
            $this->db->where('rel_id',$id);
            $this->db->where('rel_type','lead');
            $query = $this->db->get('tblreminders');
            $checkreminder = $query->num_rows();
            if($checkreminder > 0)
            {
                $datareminder = array('is_set' =>$rem, 'description' => $remark, 'date' => $next_calling, 'isnotified' => 0, 'staff' => $added_by, 'rel_type' => 'lead', 'notify_by_email' => 1, 'creator' => $added_by,'popup_status'=>0);
                $this->db->where('rel_id',$id);
                $this->db->where('rel_type','lead');
                $this->db->update('tblreminders', $datareminder); 

                  $lead_details = $this->db->get_where('tblleads',array('id'=>$id))->row();
                        $proj_name =  $lead_details->lead_author_name.'_'.$lead_details->lead_booktitle;

                         $by = $this->session->userdata('staff_user_id');
                        $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                         ->from('tblstaff')
                         ->join('tblroles', 'tblstaff.role_id = tblroles.roleid');
                          $this->db->where('tblstaff.staffid',$by);
                        $query = $this->db->get();

                        $ret = $query->row();
                        // echo $this->db->last_query();exit;
                 $data1 = array(
                            'notify_to'=> $by,
                            'user_id'=> $id,
                            'take_by'=> $by,
                            'role' => $ret->name,
                            'project_name' => $proj_name,
                            'author_name' => $lead_details->lead_author_name,
                            'book_name' => $lead_details->lead_booktitle,
                            'action' => 16,
                            'message' => 'This is reminder message at '.$next_calling,
                            'discription' => $lead_details->lead_author_name.' ,reminder : '. $remark.' at : '.$next_calling,
                            'next_calling' => $next_calling,
                        );
                        $this->db->insert('lead_all_action',$data1);

            }
            else
            {
                $datareminder = array('is_set' =>$rem,'description' => $remark, 'date' => $next_calling, 'isnotified' => 0, 'rel_id' => $id, 'staff' => $added_by, 'rel_type' => 'lead', 'notify_by_email' => 1, 'creator' => $added_by);
                $this->db->insert('tblreminders', $datareminder);  
            }
            echo $this->db->last_query();
            
             
        }
        
        
        
       
        //print_r($data);
        //print_r($id);
        $this->db->where('id', $id);
        $this->db->update('tblleads', $data);
        echo $this->db->last_query();
        if($remark != null) {
        $data2 = array('lead_id' => $id, 'remark' => $remark, 'added_by' => $added_by);
        $this->db->insert('tblleadremark', $data2);
        $all_lead_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
        $data3 = array(
            'ImEx_leadRemarks' => $all_lead_data->ImEx_leadRemarks.' | '.$remark,
            'description' => $all_lead_data->ImEx_leadRemarks.' | '.$remark,
        );
        $this->db->where('id',$id);
        $this->db->update('tblleads',$data3);
        }
        if($status == 1)
        {
            $this->db->select('*');
            $this->db->where('lead_id',$id);
            $checkwp_assigned = $this->db->get('tblmeeting_scheduled')->row();
            $wp_id = $checkwp_assigned->assigned;
            if($wp_id == null)
            {
                $lead_id = $id;
                $staff_id = $reassignlead;
                $this->leads_model->reassign($lead_id, $staff_id,$mainassigned); 
            }
            else
            {
                if(!$reassignlead)
                {
                    $reassignlead = '0';
                }
                $insertUpdate['assigned'] = $reassignlead;
                $insertUpdate['assigned_by'] = $mainassigned;
                $this->db->update('tblmeeting_scheduled', $insertUpdate, array('lead_id' => $id));
            }
            
        }    
        
        
        
        
        $insertUpdate['assigned'] = $mainassigned;
        $this->db->update('tblleads', $insertUpdate, array('id' => $id));
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

    

   public function get_row_lead_data($id = '')
    {
        $lead_id = $_POST['id'];
        $srnumber = $_POST['srnumber'];
        //print_r($srnumber);exit;
        $leads = $this->leads_model->get_row_lead_data();
       /*echo "<pre>";
        print_r($leads);
        exit;*/
        $this->printLeadData_row_lead($leads,$srnumber);
    }


    public function custom_lead_filter_added($id = '')

    {



        $leads = $this->leads_model->get_allleads_filter_added();

        $this->printLeadData($leads);

    }



    

    private function printLeadData_row_lead($leads,$srnumber)

    {
        $leadStatus = $this->leads_model->get_leadstatus('tblleadsstatus');
        $i = 1;
        $count = 1;
        if (!empty($leads)) {
          //  print_r($leads);die;
            foreach ($leads as $alllead) {   ?>
                                    <tr id='lead_id_<?= $alllead->id; ?>'>
                                        
                                            <?php if (is_admin()) { ?>
                                            <td style="display:none;"><input type="checkbox" name="deleted_value" value="<?= $alllead->id; ?>" class="selected_row" ></td>
                                            <?php } ?>
                                            <td> <a data-controls-modal="your_div_id" data-backdrop="static" data-keyboard="false" href="#"
                                       onclick="edit_product_catagory2(this,<?= $alllead->id; ?>,'<?= $alllead->lead_author_name; ?>','<?= $alllead->lead_publishedearlier; ?>','1','assignedleads2_line245','<?= $alllead->lead_author_msstatus; ?>','<?= $alllead->lead_author_mslanguage; ?>');return false;"
                                       data-id="<?= $alllead->id; ?>"
                                       data-publishedEarlier="<?= $alllead->lead_publishedearlier; ?>"
                                       data-name="<?= $alllead->lead_author_name; ?>"
                                       data-phone_number="<?= $alllead->phonenumber; ?>"
                                       data-srnumber="<?= $count; ?>"
                                       data-booktitle="<?= $alllead->lead_booktitle; ?>"
                                       data-book_format="<?= $alllead->lead_bookformat; ?>"
                                       data-otherphonenumber="<?= $alllead->otherphonenumber; ?>"
                                       data-email="<?= $alllead->email; ?>"
                                       data-designation="<?= $alllead->designation; ?>"
                                       data-company="<?= $alllead->company; ?>"
                                       data-address="<?= $alllead->address; ?>"
                                       data-data_source="<?= $alllead->adset_name; ?>"
                                       data-calling_objective="<?= $alllead->lead_adname; ?>"
                                       data-assigned="<?= $alllead->assigned; ?>"
                                       data-next_calling="<?= $alllead->next_calling; ?>"
                                       data-status="<?= $alllead->lead_category_id; ?>"
                                       data-description="<?= $alllead->description; ?>"><i class="fa fa-edit text-primary" style="font-size:24px;color:red"></i> </a></td>
                                            <td>
                                            <!-- <input type="hidden" value="<?//= $srnumber; ?>" name="srnumber" id="srnumber" /> -->
                                           <?= $count; ?>
                                           <?php// echo $srnumber;?>
                                            <?php
                                            if (is_admin() || is_headtrm())
                                            {
                                           ?>
                                              <a style="display:none;" onclick="return confirm('Are you sure?')" href="<?= sprintf(base_url('admin/leads/delete_addedleads/%s'), $alllead->id); ?>"><i class="fa fa-trash text-danger"></i></a> 
                                            <?php
                                            }
                                            ?>
                                           
                                            
                                        <td><a href="#"
                                               onclick="edit_product_catagory(this,<?= $alllead->id; ?>,'<?= $alllead->lead_author_name; ?>','<?= $alllead->lead_publishedearlier; ?>','1');return false;"
                                               data-id="<?= $alllead->id; ?>" 
                                                data-publishedEarlier="<?= $alllead->lead_publishedearlier; ?>"
                                               data-name="<?= $alllead->lead_author_name; ?>"
                                               
                                               data-srnumber="<?= $count; ?>"
                                               data-phone_number="<?= $alllead->phonenumber; ?>"
                                               data-booktitle="<?= $alllead->lead_booktitle; ?>"
                                               data-book_format="<?= $alllead->lead_bookformat; ?>"
                                               data-otherphonenumber="<?= $alllead->otherphonenumber; ?>"
                                               data-email="<?= $alllead->email; ?>"
                                               data-designation="<?= $alllead->adset_name; ?>"
                                               data-company="<?= $alllead->company; ?>"
                                               data-address="<?= $alllead->address; ?>"
                                               data-data_source="<?= $alllead->data_source; ?>"
                                               data-calling_objective="<?= $alllead->calling_objective; ?>"

                                               data-meetingtimefrom="<?= $alllead->meetingtimefrom; ?>"
                                               data-meetingtimeto="<?= $alllead->meetingtimeto; ?>"

                                               data-assigned="<?= $alllead->assigned; ?>"

                                               data-next_calling="<?= $alllead->next_calling; ?>"
                                               data-status="<?= $alllead->status; ?>"
                                               data-description="<?= $alllead->description; ?>"> <?= $alllead->lead_author_name; ?></a>
                                        </td>
                                        <td><?=  preg_replace('/[^a-zA-Z0-9_ -]/s','',$alllead->phonenumber);
                                        //echo substr($alllead->phone_number,5);
                                        ?></td>
                                        <td style="display:none" >
                                            <?php  $designation = str_replace("(", "", $alllead->designation);
                                            $designation = str_replace(")", "", $designation);
                                            $designation = str_replace("-", "", $designation);
                                            echo $designation;
                                            
                                            ?>
                                           </td>
                                           
                                           <td style="display:none" >
                                            <?php  
                                            echo $alllead->lead_booktitle;
                                            
                                            ?>
                                           </td>
                                           <td style="display:none" >
                                            <?php  
                                            echo $alllead->lead_bookformat;
                                            
                                            ?>
                                           </td>
                                           
                                           
                                           <td style="display:none" >
                                            <?php  echo $alllead->otherphonenumber;
                                            
                                            ?>
                                           </td>
                                            <td style="display:none;" >
                                               <?= $alllead->lead_publishedearlier; ?>
                                               </td>
                                           
                                           <td><?= $alllead->lead_adname; ?></td>
                                        <td>
                                            <?php $address = $alllead->lead_author_msstatus;
                                            $address = str_replace(".", "", $address);
                                        $address =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$address);
                                            echo $address;  ?>
                                            </td>
                                        <!--<td ><?//= $alllead->adset_name; ?></td>-->
                                        <!-- <td ><?= $alllead->email; ?></td> -->
                                        <td >test@gmail</td>
                                        <td><?= $alllead->lead_author_mslanguage; ?></td>
                                         <?php if($alllead->calling_date != '0000-00-00'){?>
                                       <td><?= $alllead->lead_callingdate; ?></td>
                                       <?php }else {?>
                                        <td></td>
                                       <?php }?>
                                       <!-- <td><?//= $alllead->dateassigned; ?></td>-->
                                        <td>
                                            
                                                <?php if($alllead->lead_category_id == 5) {echo "A";} ?>
                                                <?php if($alllead->lead_category_id == 16) {echo "B";} ?>
                                               <?php if($alllead->lead_category_id == 38) {echo "B+";} ?>
                                                <?php if($alllead->lead_category_id == 30) {echo "C";} ?>
                                                <?php if($alllead->lead_category_id == 32) {echo "NP";} ?>
                                                <?php if($alllead->lead_category_id == 39) {echo "Acquired";} ?>
                                               <?php if($alllead->lead_category_id == 40) {echo "UnAttended";} ?>
                                                <?php if($alllead->lead_category_id == 41) {echo "Scrap";} ?>
                                            </td>
                                        <?php 
                                            //$string = $alllead->description;
                                           // $substring = substr($string,0,10);
                                            ?>
                                           
                                        <td>
                                            <span>Line2620</span>
                                            
                                            
                                            
                                            <?php  //echo $substring."....";  ?>
                                        
                                        
                                                <?php //echo $ex = implode(" ",$excerpt);?>
                                               <?php
                                               $this->db->order_by("created_on", "desc");
                                                $this->db->where('lead_id',  $alllead->id);
                                                ////$this->db->group_by("remark");
                                                $this->db->select('remark');
                                                $data = $this->db->get('tblleadremark')->result();
                                                
                                                echo "<span class='ffffline2635'>".substr(current($data)->remark,0,10)."</span>";
                                                echo "<span style='display: none;'>";
                                                if( current($data)->remark != ''){
                                                       $seprationss = ";"; 
                                                    }else{
                                                        $seprationss = '';
                                                    }
                                                echo substr(current($data)->remark,30).$seprationss;
                                                unset($data[0]); // unset 1st element
                                                foreach($data as $remarkdata){
                                                     if($remarkdata->remark != ''){
                                                       $seprations = ";"; 
                                                    }else{
                                                        $seprations = '';
                                                    }
                                                    echo $remarkdata->remark.$seprations;
                                                }
                                                echo "</span>";
                                               ?>
                                             
                                        
                                        </td>
                                       
                                       
                                       
                                       <?php
                                    if (is_admin())
                                            {
                                           ?>
                                            <td>
                                                <?php 
                                                $this->db->where('staffid',  $alllead->assigned);
                                                $this->db->select('firstname,lastname');
                                                $data = $this->db->get('tblstaff')->result();
                                                echo $name = $data[0]->firstname.''.$data[0]->lastname;
                                            
                                                ?>
                                                <?//= $alllead->assigned; ?></td>
                                              <?php
                                            }
                                            ?>
                                        
                                      <?php if($alllead->next_calling != '0000-00-00 00:00:00'){?>
                                       <td><?= $alllead->next_calling; ?></td>
                                       <?php }else {?>
                                        <td></td>
                                       <?php }?>
                                           <td ><?= $alllead->lead_created_date; ?></td>
                                      
                                       
                                    </tr>
                                <?php $count++; } 
        } else {
            echo NOLEADFOUND;
        }

    }

    

    private function printLeadData($leads)

    {

        $leadStatus = $this->leads_model->get_leadstatus('tblleadsstatus');

        $i = 1;

        if (!empty($leads)) {

            foreach ($leads as $alllead) { ?>

                                    <tr id='lead_id_<?= $alllead->id; ?>'>

                                        <td>

                                            <?= @++$i; ?>

                                            <?php

                                            if (is_admin() || is_headtrm())

                                            {

                                           ?>

                                              <a onclick="return confirm('Are you sure?')" href="<?= sprintf(base_url('admin/leads/delete_addedleads/%s'), $alllead->id); ?>"><i class="fa fa-trash text-danger"></i></a> 

                                            <?php

                                            }

                                            ?>

                                            </td>

                                        <td><a href="#"

                                               onclick="edit_product_catagory(this,<?= $alllead->id; ?>);return false;"

                                               data-id="<?= $alllead->id; ?>" data-name="<?= $alllead->name; ?>"

                                               data-phonenumber="<?= $alllead->phonenumber; ?>"

                                               data-email="<?= $alllead->email; ?>"

                                               data-designation="<?= $alllead->designation; ?>"

                                               data-company="<?= $alllead->company; ?>"

                                               data-address="<?= $alllead->address; ?>"

                                               data-data_source="<?= $alllead->data_source; ?>"

                                               data-calling_objective="<?= $alllead->calling_objective; ?>"



                                               data-meetingtimefrom="<?= $alllead->meetingtimefrom; ?>"

                                               data-meetingtimeto="<?= $alllead->meetingtimeto; ?>"



                                               data-assigned="<?= $alllead->assigned; ?>"



                                               data-next_calling="<?= $alllead->next_calling; ?>"

                                               data-status="<?= $alllead->status; ?>"

                                               data-description="<?= $alllead->description; ?>"> <?= $alllead->name; ?></a>

                                        </td>

                                        <td><?=  preg_replace('/[^a-zA-Z0-9_ -]/s','',$alllead->phonenumber); ?></td>

                                        <td style="display:none" >

                                            <?php  $designation = str_replace("(", "", $alllead->designation);

                                            $designation = str_replace(")", "", $designation);

                                            $designation = str_replace("-", "", $designation);

                                            echo $designation;

                                            

                                            ?>

                                           </td>

                                        <td><?= $alllead->company; ?></td>

                                        <td style="display:none" >

                                            <?php
 $address = $alllead->address;

                                            $address = str_replace(".", "", $address);

                                        $address =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$address);

                                            echo $address;  ?>

                                            </td>

                                        <td style="display:none" ><?= $alllead->email; ?></td>

                                        <td style="display:none" ><?= $alllead->data_source; ?></td>

                                        <td><?= $alllead->calling_objective; ?></td>

                                        <td><?php if ($alllead->lastcontact == null) {

                                                echo "";

                                            } else {

                                                echo date('d M, Y', strtotime($alllead->lastcontact));

                                            } ?></td>

                                            

                                         <td><?php if ($alllead->next_calling == null || $alllead->next_calling == '0000-00-00 00:00:00' ) {

                                                echo "";

                                            } else {

                                                echo date('d M, Y H:i:s', strtotime($alllead->next_calling));

                                            } ?></td>    

                                        <td><?php 
                                        $description = $alllead->description;

                                            $description = str_replace(".", "", $description);

                                        $description =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$description);

                                            echo $description;  ?></td>

                                        <?php

                                        $colid = $alllead->status;

                                        $this->db->where('id', $colid);

                                        $result = $this->db->get('tblleadsstatus')->result();

                                        ?>

                                        <td style="width:160px !important">

                                         <!--   <select class="selectpicker" disabled="true" name="rate" id="lead_status_change"

                                                    data-lead-id="<?= $alllead->id; ?>">

                                                <?php foreach ($lstatus as $leadst) {

                                                    echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $alllead->status ? 'selected' : '', $leadst->name);

                                                } ?>

                                            </select>-->

                                            <?php echo $result[0]->name; ?>

                                        </td>

                                        <td>

                                            <?php if ($alllead->status == 1) { ?>

                                               

                                                <?php

                                                $this->db->select('tblstaff.firstname as wpname');

                                                $this->db->where('lead_id', $alllead->id);

                                                $this->db->join('tblstaff', 'tblmeeting_scheduled.assigned=tblstaff.staffid');

                                                $query = $this->db->get('tblmeeting_scheduled');

                                                $q2 = $query->result();



                                                $aswp = $query->num_rows();

                                                if ($aswp > 0) {

                                                    $asgndwp = $q2['0']->wpname;

                                                } else {

                                                    $asgndwp = "0";

                                                }



                                                ?>

                                                <?= $q2['0']->wpname; ?>

                                                

                                            <?php } ?>

                                        </td>

                                    </tr>

                                <?php } 

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

        if($start)

        {

           $data['summary'] = get_meeting_summary_date_wise($start, $end);

           

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

            

            $data['summary'] = get_meeting_summary_date_wise($data['start'], $data['end']);

            

            $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : date('d, M, Y', strtotime($data['start'])) . ' To ' . date('d, M, Y', strtotime($data['end'])) ;

           

            

        }

       

       // $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";

        

        //$data['staffList'] = $this->staff_model->get('', ['active' => 1]);

        $data['staffList'] = $this->staff_model->get_hera_staff('', ['active' => 1, 'department_id' => 12]);

        $data['statuses'] = $this->leads_model->get_status_dwr();

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

        

        

        $converted_by = $this->session->userdata('staff_user_id');

        $this->db->select('tbll.id, tbll.name as conname');

        $this->db->join('tblleads as tbll', 'tblbusiness.meet_lead_id = tbll.id');

        $this->db->where('converted_by', $converted_by);

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
        //print_r($_POST);exit;

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





    function business_report2()

    {  //print_r($_SESSION);

          $this->load->helper('url');

          $this->load->library("pagination");

        

        $configx = array();

        $configx["base_url"] = base_url()."admin/leads/business_report";

        $configx["total_rows"] = 2000;

        $configx["per_page"] = 30;

        //echo $configx["uri_segment"] = 3;

        //echo $this->uri->segment(4);

        //echo "----------------";

        

         $limit_per_page=30;

        $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $pagination = $this->pagination->create_links();

        

        $this->pagination->initialize($configx);

        $data['title'] = 'Business Report';

        $data['bodyclass'] = 'hide-sidebar';

        $data['business'] = $this->leads_model->business_report($limit_per_page,$start_index);

        $data['business_updates'] = $this->leads_model->business_report_updates();

        $data['rmconverted'] = $this->leads_model->rmconverted();

        $data['bustatus'] = $this->leads_model->bustatus();

        $data['bpro_type'] = $this->leads_model->protype();

        

        $this->load->view('admin/leads/business_report', $data);

    }





function business_report()

    {  

        // print_r($params);

        

          //$this->load->library('pagination');

          //$config['base_url'] = 'business_report';

          //$config["total_rows"] = 100;

          //$config['per_page'] = 20;

          //$this->pagination->initialize($config);

          //$data["links"] = $this->pagination->create_links();

        // /* https://codeigniter.com/userguide3/libraries/pagination.html */



        // $this->pagination->initialize($config);

        $data['title'] = 'Business Report';

        $data['bodyclass'] = 'hide-sidebar';

        $data['business'] = $this->leads_model->business_report1($params['start'],$params['length']);

        $data['business_updates'] = $this->leads_model->business_report_updates();

        $data['rmconverted'] = $this->leads_model->rmconverted();

        $data['bustatus'] = $this->leads_model->bustatus();

        $prodtype = $this->leads_model->protype();

        foreach($prodtype as $val){

            $pType[$val->product_type] = $val->prod_name;

        }

        $data['prod_types'] = $pType;

        

//         echo $params['start'];

//         $data = $this->leads_model->business_report1($params['start'],$params['length']);

//         $json_data = array(

//          "draw"            => intval( $params['draw'] ),   

//          "recordsTotal"    => count($data),//intval( $totalRecords ),  

//          "recordsFiltered" => count($data),//intval($totalRecords),

//          "data"            => $data   // total data array

//          ); 



        /* $data['title'] = 'Business Report';

        $data['bodyclass'] = 'hide-sidebar';
        $data['business_updates'] = $this->leads_model->business_report_updates();

        $data['rmconverted'] = $this->leads_model->rmconverted();

        $data['bustatus'] = $this->leads_model->bustatus();

        $data['bpro_type'] = $this->leads_model->protype();

        $data['allBusinessData'] = $this->get_business_data(); */
        $data['bpro_type'] = $this->leads_model->protype();
        
        $this->load->view('admin/leads/business_report', $data);

    }

    function get_business_data(){
        $data = $this->leads_model->business_report1($_POST['start'],$_POST['length']);
        return $data;
    }
    

    function custom_business_filter1()

    {

        //print_r($_POST);

         $data = $this->leads_model->business_report1($_POST['start'],$_POST['length']);

          //$data = json_decode($data,true);

          //print_r($data);

        $totalCount = $this->leads_model->business_report_get_total1();

        if ($totalCount > 0) {
            
            //echo "LINE5135";

            //print_r($data);exit;

        $json_data = array(

            "draw"            => intval( $_POST['draw'] ),   

            "recordsTotal"    => intval( $totalCount ),  

            "recordsFiltered" => intval($totalCount),

            "aaData"            => $data   // total data array

            );

            echo json_encode($json_data);

        }else{

            return "<h4>No record found!</h4>";

        }

        

//          echo echo '{"draw":2,"recordsTotal":225,"recordsFiltered":225,"data": '.$data.'}';

        // echo '{"draw":2,"recordsTotal":225,"recordsFiltered":225,"data":[["2253","Paul","100000","12"],["2254","haydar","0","31"],["2255","test","2323","64"],["2257","sdafds","0","0"],["2258","Michael Orven Solomon","15","24"],["2259","","0","0"],["2260","","0","0"],["2261","sad","0","0"],["2263","matu","1000","10"],["2264","kbkj","0","0"]]}';//json_encode($json_data);

//         $results = array(

//          "sEcho" => 1,

//         "iTotalRecords" => count($data),

//         "iTotalDisplayRecords" => count($data),

//           "aaData"=>$data);

//           echo json_encode($results);

    }

    

    



    function custom_business_filter()

    {
        //print_r($_GET);
         $this->load->helper('url');

          $this->load->library("pagination");

        

        $configx = array();

        $configx["base_url"] = base_url()."admin/leads/business_report";

        $configx["total_rows"] = 2000;

        $configx["per_page"] = 30;

        //echo $configx["uri_segment"] = 3;

        //echo $this->uri->segment(4);

        //echo "----------------";

        

         $limit_per_page=30;

        $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $pagination = $this->pagination->create_links();

        

        $this->pagination->initialize($configx);

        

        $leads = $this->leads_model->get_bussiness_filter($limit_per_page,$start_index);
        //echo $this->db->last_query();exit;
        $updatedbusiness = $this->leads_model->get_updated_bussiness_filter();

        //print_r($this->db->last_query());

        $this->printBussinessData($leads,$updatedbusiness); 

    }

    

    // function availablewp()

    // {

    //     $data['title'] = 'Available Working Person';



    //     if ($this->input->post()) {

    //         $chdate = $this->input->post('date');

    //         $this->db->select('date');

    //         $this->db->where(array('tt.date' => $chdate));

    //         $query = $this->db->get('tblavailablewp tt');

    //         $checkdate = $query->num_rows();

    //         if ($checkdate > 0) {

    //             set_alert('warning', "WP List Exist For $chdate ");

    //             redirect(admin_url('leads/availablewp'));

    //         } else {

    //             $insert_data['date'] = $this->input->post('date');

    //             $telermids = $this->input->post('telermids');

    //             $wpids = $this->input->post('wpids');

    //             $total_count = count($telermids);



    //             for ($i = 0; $i < $total_count; $i++) {

    //                 $teamdeptrm_id = $this->input->post('teamdeptrm_id');

    //                 $inserdata['date'] = $insert_data['date'];

    //                 $inserdata['telerm'] = $telermids[$i];

    //                 $inserdata['wp'] = $wpids[$i];

    //                 $this->db->insert('tblavailablewp', $inserdata);

    //             }

    //             set_alert('success', _l('added_successfully', "Available WP"));



    //         }

    //         return redirect(admin_url('leads/availablewp'));

    //     }





    //     /*$data['bodyclass'] = 'hide-sidebar';*/

    //     $data['telerm'] = $this->leads_model->get_telerm('', 'staffid,CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');

    //     $data['availwp'] = $this->leads_model->get_avaliablewp('', 'staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');

    //     $data['assignedwp'] = $this->leads_model->getassignedwp();





    //     $this->load->view('admin/leads/availablewp', $data);

    // }



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

            $data['telerm'] = $this->leads_model->get_telerm('', 'staffid,CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname ', 'result_array');

            $data['availwp'] = $this->leads_model->get_avaliablewp('', 'staffid,CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');

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

    

      
 private function printBussinessData($leads,$updatedbusiness)

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
      <?php
      
      ?>
         <td><?= @++$i; ?>
            <?php if ($alllead->status == "Verified") {
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
            <?= $alllead->staff_res; ?>
         </td>
         <td>
            <select <?php if(($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) { } else {echo "disabled"; }  ?> class="form-control  business_status_list" name="business_status" id="business_statusval" data-lead_id="<?= $alllead->id; ?>">
               <option value="New" <?php if($alllead->status == "New") { echo "Selected"; } ?>>Unverified</option>
               <option value="Hold" <?php if($alllead->status == "Hold") { echo "Selected"; } ?>>Hold</option>
               <option value="Verified" <?php if($alllead->status == "Verified") { echo "Selected"; } ?>>Verified</option>
               <option value="Rejected" <?php if($alllead->status == "Rejected") { echo "Selected"; } ?>>Rejected</option>
            </select>
         </td>
         <!--<script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>-->
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
         if($alllead->status != "Rejected"){
            $totNetCredit = $totNetCredit + $alllead->net_credit;
         }
         
         
         
         } ?>
      <?php foreach ($updatedbusiness as $alllead1) { ?>
      <tr>
         <td><?= @++$i; ?>
            <?php if ($alllead1->status == "Verified") {
               echo "";
               
               } 
               
               else
               
               {
               
               echo '
               
               <a href="'.admin_url() .'leads/deletebusinessreport/'.$alllead1->id.'" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>';
               
               }
               
               ?>
         </td>
         <td> </td>
         <td><?= $alllead1->transaction_date; ?></td>
         <td><b>Remark : <?= $alllead1->remark; ?></b> </td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td> </td>
         <td></td>
         <td> </td>
         <td><?= $alllead1->net_credit; ?></td>
         <td>
            <?= $alllead1->staff_res; ?>
         </td>
         <td>
            <select <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
               } else {
               
                   echo "disabled";
               
               } ?> class="form-control  business_status_list"
               name="business_status" id="business_statusval"
               data-lead_id="<?= $alllead1->id; ?>">
               <option value="New" <?php if ($alllead1->status == "New") {
                  echo "Selected";
                  
                  } ?>>Unverified
               </option>
               <option value="Hold" <?php if ($alllead1->status == "Hold") {
                  echo "Selected";
                  
                  } ?>>Hold
               </option>
               <option value="Verified" <?php if ($alllead1->status == "Verified") {
                  echo "Selected";
                  
                  } ?>>Verified
               </option>
               <option value="Rejected" <?php if ($alllead1->status == "Rejected") {
                  echo "Selected";
                  
                  } ?>>Rejected
               </option>
            </select>
         </td>
      </tr>
      <?php     
         $totTransactionAmt = $totTransactionAmt + $alllead1->transaction_amount;
            if($alllead1->status != "Rejected"){
                $totNetCredit = $totNetCredit + $alllead1->net_credit;
            }
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
         //$netCreditAmt=300; 
         
         ?>
      
   </tbody>
   <tfoot>
   <tr>
         <td ></td>
         <td ></td>
         <td ></td>
         <td ></td>
         <td ></td>
         <td ></td>
         <td ></td>
         <td ></td>
         <td style="text-align:right;font-weight: bold;">Total Transaction Amount</td>
         <td style="text-align:left;font-weight: bold;"><span id="sum_transaction_amount">Rs. <?php echo $n; ?></span></td>
         <td ></td>
         <td ></td>
         <td ></td>
         <td ></td>
         <td style="text-align:right;font-weight: bold;">Total Net Credit</td>
         <td style="text-align:left;font-weight: bold;"><span >Rs. <?php echo $nCrdAmt; ?></span></td>
         <td ></td>
         <td ></td>
      </tr>
   </tfoot>
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

        $a=0;

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

                    <?php } ?> 

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

                    <input type="hidden" name="row_number[]" value="<?php echo $a; ?>"> 

                </tr>

                

              <?php

              $a++;

            }

            

        }

        

    }

    

    public function saveDcr()

    {

        $date_work=$_REQUEST['date_work'];

        $staff_id=$_REQUEST['staff_id'];

        $total_calls=$_REQUEST['total_calls'];

        $other_work=$_REQUEST['other_work'];

        $other_work_duration=$_REQUEST['other_work_duration'];

        

        $this->db->select('dcr_date');

        $this->db->where('dcr_date',$date_work);

        $this->db->where('staff_id',$staff_id);

        $predcr = $this->db->get('tbldcr')->row();

        if($predcr->dcr_date == $date_work)

        {

            set_alert('warning', "DCR Already Submitted for the date $date_work");

            redirect(admin_url('leads/submit_dcr'));

            

        }

        

        

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

//       $this->db->group_by('`tbldcr`.`staff_id`');

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

         

         if($filterrm== null)

         {

                if (is_admin() || is_headtrm())

       {

          $this->db->where_in('staff_id', $filterrm); 

         

       }

       

       else

       {

           $loginid = $this->session->userdata('staff_user_id');

           $this->db->where_in('staff_id', $loginid); 

       }

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

                <?php 

                    }

                ?> 

                    <td class="text-center"><?php echo $sr['calling_objective']; ?></td>

                    <td class="text-center"><?php echo $sr['data_source']; ?></td>

                    <td class="text-center"><?php echo $sr['calling_pitch']; ?></td>

                    

                <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['other_work']; ?> </td>

                <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['other_work_duration']; ?> </td>

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

                    <?php

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

        $data['title'] = 'Duplicate Entry Listdd';

           $useraid = $this->session->userdata('staff_user_id');
             
            $number = $number;

            // $this->db->select('id,full_name,phone_number,firstname,lastname');    
            // $this->db->from('tblstaff');
            // $this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid','right');
            // $this->db->like('phone_number', $number, 'right');
            // $data['getLeads'] = $this->db->get()->result();
       
            $this->db->select('id,lead_author_name,phonenumber');    
            $this->db->from('tblleads');
            $this->db->like('phonenumber', $number, 'right');
            $this->db->order_by('id','asc');
            $data['getLeads'] = $this->db->get()->result();
      
         
         $this->load->view('admin/leads/duplicate_entry_detail', $data);



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

    

    public function meetings_by_trm()

    

    {

        $this->db->where('tblleads.status', 1);

        $this->db->select('CONCAT(tbll.firstname, ' . ', tbll.lastname) AS fullname, tbll.staffid  ,tblleads.*');

        $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');

        $data['getLeads'] = $this->db->get('tblleads')->result();

        $data['availwp'] = $this->leads_model->get_avaliablewp('', 'staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');

        $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  trmname, tblmeeting_scheduled.assigned_by');

        $this->db->group_by('tblmeeting_scheduled.assigned_by');

        $this->db->join('tblstaff', 'tblmeeting_scheduled.assigned_by=tblstaff.staffid');

        $data['alltrms'] = $this->db->get('tblmeeting_scheduled')->result();

        

        $this->load->view('admin/leads/meetings_by_trm', $data);

        

    }

    

    public function filter_meetings_by_trm()

    {

        $leads = $this->leads_model->get_all_filter_meeting_by_trm();

        $this->printfilter_meeting_by_trmData($leads);

        

    }

    

    public function assign_wp_on_lead()

    {

            $lead_id = $_GET['lead_id'];

            $staff_id = $_GET['wp_id'];

            $mainassigned = $_GET['staffid'];

            $this->leads_model->reassign($lead_id, $staff_id,$mainassigned);

            $data['success'] = "success";

            set_alert('success', "Lead Updated Successfully");

            return $data;

    }

    

    private function printfilter_meeting_by_trmData($leads)

    {

        $i = 1;

         if ( !empty($leads) ) {

                                  foreach ($leads as $lead) { ?>

                                      <tr>

                                          <td><?= @++$i; ?></td>

                                          <td><?= $lead->fullname; ?></td>

                                          <td><?= $lead->name; ?></td>

                                          <td><?= $lead->address; ?></td>

                                          <td><?php $date = date_create($lead->meetingtimefrom); echo date_format($date, 'g:ia'); ?> - <?php $date = date_create($lead->meetingtimeto); echo date_format($date, 'g:ia \o\n l jS F Y'); ?></td>

                                          <td> 

                                         

                                               

                                                <?php

                                                $this->db->select('tblmeeting_scheduled.*,CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname');

                                                $this->db->where('lead_id', $lead->id);

                                                $this->db->join('tblstaff', 'tblmeeting_scheduled.assigned=tblstaff.staffid');

                                                $query = $this->db->get('tblmeeting_scheduled');

                                                $q2 = $query->result();



                                                $aswp = $query->num_rows();

                                                if ($aswp > 0) {

                                                    $asgndwp = $q2['0']->assigned;

                                                    $wpname = $q2['0']->wpname;

                                                } else {

                                                    $asgndwp = "0";

                                                    $wpname = "";

                                                }



                                                ?>

                                                <?php if (is_admin() || is_headtrm())

                                                { ?>

                                                   <select data-lead_id="<?= $lead->id; ?>" data-staffid="<?= $lead->assigned; ?>"  id="working_person" name="working_person" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">

                                              <option  value=""></option>

                                              <?php foreach($availwp as $wp) { ?>

                                              <option <?php if($asgndwp == $wp[staffid] ) { echo "selected"; } ?> value="<?= $wp[staffid]; ?>"><?= $wp[firstname]; ?></option>

                                              <?php } ?>

                                          </select>

                                          

                                              <?php  } 

                                              else {

                                                  echo $wpname;

                                              }

                                               

                                              ?>

                                           

                                          </td>

                                      </tr>

                                  <?php }

                               } 

    }
     //      ******************************* _work        *******************************//
     public function view_duplicate_list_email($email){
        $data['title'] = 'Duplicate Entry List';
        $useraid = $this->session->userdata('staff_user_id');
        $this->db->like('tblleads.email', $email, 'right');
        $this->db->select('CONCAT(tbll.firstname, ' . ', tbll.lastname) AS fullname, tbll.staffid  ,tblleads.*');
        $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid','left');
        $data['getLeads'] = $this->db->get('tblleads')->result();
        $this->load->view('admin/leads/duplicate_email_entry_detail', $data);
    }
    public function view_custom_lead()
    {
        
        $data['getData']=$this->db->query("SELECT phonenumber, COUNT(*) as cnt FROM `tblleads` where phonenumber !=''   GROUP BY `phonenumber` HAVING `cnt` > 1")->result_array();


         $data['getDataEmail']=$this->db->query("SELECT email, COUNT(*) as cnt FROM `tblleads` where email !=''   GROUP BY `email` HAVING `cnt` > 1")->result_array();
         $data['getDuplicateDatabasedOnEmail']=$this->db->query("SELECT email, COUNT(*) as cnt FROM `tblleads` where email !='' AND date(`lead_created_date`)>='2021-12-10'  GROUP BY `email` HAVING `cnt` > 1")->result_array();
         
        /* echo "<pre>";
         print_r($data['getDuplicateDatabasedOnEmail']);
         exit;*/

        $data['getData_multi_n']=$this->db->query("SELECT phonenumber, COUNT(*) as cnt FROM `tblleads` where phonenumber !='' and duplicate_l !=''  GROUP BY `duplicate_l` HAVING `cnt` > 1")->result_array();
        
        $lead_id_start = $this->uri->segment(5);
        $lead_id_end = $this->uri->segment(6);
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        
        $ignore = '??? ?????';
        
        $this->db->not_like('lead_author_name', $ignore, 'both');
        $remainleads = $this->db->get('tblleads')->result();
        $data['remainleads'] = $remainleads;
       
        
        //echo $lead_id_start.' -  '.$lead_id_end;
         $ignore1 = '??? ?????';
        $blank = ' ';
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        //$this->db->where("(lead_author_name LIKE '".$blank."')", NULL, FALSE);

        $this->db->like('lead_author_name',$ignore1, 'both'); 
        $res = $this->db->get('tblleads')->result();
       // echo $this->db->last_query();
        $data['assignedleads'] = $res;
       //    echo "<pre>";
       // print_r($data['assignedleads']);
        // exit();
        //$data['assignedleads'] = $this->db->get('tblleads')->result();
        
        
        $data['bodyclass'] = 'hide-sidebar';
//echo "<pre>";
        //print_r($data);die;
        
        $this->load->view('admin/leads/before_assignedleads', $data);
        
    }
    public function view_custom_lead_bkp()
    {
        
        $data['getData']=$this->db->query("SELECT phonenumber, COUNT(*) as cnt FROM `tblleads` where phonenumber !=''  GROUP BY `phonenumber` HAVING `cnt` > 1")->result_array();
        
        $lead_id_start = $this->uri->segment(5);
        $lead_id_end = $this->uri->segment(6);
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        
        $ignore = '??? ?????';
        
        $this->db->not_like('lead_author_name', $ignore, 'both');
        $remainleads = $this->db->get('tblleads')->result();
        $data['remainleads'] = $remainleads;
       
        
        //echo $lead_id_start.' -  '.$lead_id_end;
         $ignore1 = '??? ?????';
        $blank = ' ';
        $this->db->where('id >=', $lead_id_start);
        $this->db->where('id <=', $lead_id_end);
        //$this->db->where("(lead_author_name LIKE '".$blank."')", NULL, FALSE);

        $this->db->like('lead_author_name',$ignore1, 'both'); 
        $res = $this->db->get('tblleads')->result();
       // echo $this->db->last_query();
        $data['assignedleads'] = $res;
       //    echo "<pre>";
       // print_r($data['assignedleads']);
        // exit();
        //$data['assignedleads'] = $this->db->get('tblleads')->result();
        
        
        $data['bodyclass'] = 'hide-sidebar';
//echo "<pre>";
        //print_r($data);die;
        
        $this->load->view('admin/leads/before_assignedleads', $data);
        
    }
     public function allleadremark_before()
    {
        //echo "<pre>";print_r($_POST);exit;
        $lead_idnew = $_POST['id'];
        $lead_name = $_POST['name'];
        $data['lead_id'] =$jklead= $_POST['id'];
        
           $this->db->where(' id', $jklead);
        
       $rs=$this->db->get(' tblleads')->result_array();
       $ssk=$this->db->last_query();
        
         $data['phone_number']=$phone_number= $rs[0]['phone_number'];
         
        // print_r($data['phone_number']);
         
        //$mmujead= json_encode($mujead);
      //  $mmujead= "dsfa";
        
        $lead_status = $_POST['status'];
        $lead_description = $_POST['description'];
        $data['lead_id'] = $lead_idnew;
        $data['name'] = $lead_name;
        // $data['phone_number'] = $phone_number;
        
        
        $data['status'] = $lead_status;
        $data['assigned'] = $_POST['assigned'];
        $data['next_calling'] = $_POST['next_calling'];
        $data['publishedEarlier'] = $_POST['publishedEarlier'];
        $data['adname'] = $_POST['adname'];
        $data['manuscript_status'] = $_POST['manuscript_status'];
        $data['user_language'] = $_POST['user_language'];
        $data['ad_id'] = $_POST['ad_id'];
        $data['created_time'] = $_POST['created_time'];
        $data['booktitle'] = $_POST['booktitle'];
        $data['book_format'] = $_POST['book_format'];
        $data['email'] = $_POST['email'];
        $data['otherphonenumber'] = $_POST['otherphonenumber'];
        $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
        //echo "<pre>";
        //print_r($data['lstatus']);
        //exit;
        
       
        
        $data['description'] = $lead_description;
        $data['wplistlead'] = $this->leads_model->get_availwplead();
        //echo "this page";
        //print_r($data['wplistlead']);
        $this->load->view('admin/leads/viewremark_before', $data); 
        
    }
    public function update_added_lead1()
    {
        
        $id = $_GET['id'];
        $name = $_GET['name'];
        $otherphonenumber = $_GET['otherphonenumber'];
        $PublishedEarlier = $_GET['publishedEarlier'];
        $full_name = $_GET['full_name'];
        $phonenumber = $_GET['phonenumber'];
        $phone_number = $_GET['phone_number'];
        $email = $_GET['email'];
        $adname = $_GET['adname'];
        $manuscript_status = $_GET['manuscript_status'];
        $ad_id = $_GET['ad_id'];
        $user_language = $_GET['user_language'];
        $leadCreationDate = $_GET['leadCreationDate'];
         
        //print_r($_GET);die;

        $data = array('lead_author_mslanguage'=>$user_language,'lead_adname'=>$adname,'lead_ad_id'=>$ad_id,'lead_author_msstatus'=>$manuscript_status,'lead_publishedearlier'=>$PublishedEarlier,'lead_author_name' => $name, 'phonenumber' => $phonenumber, 'email' => $email);
        $this->db->update('tblleads', $data, array('id' => $id));
         
        echo $this->db->last_query();

        
       // echo "test";
        $data['success'] = "success";
        set_alert('success', "Lead Updated Successfully");
        return $data;
    }
     public function addeddleads()
    {
           
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->where('lead_addbyui','manual');
            $data['assignedleads'] = $this->db->get('tblleads')->result();
        
            $data['bodyclass'] = 'hide-sidebar';
            
            $this->load->view('admin/leads/addeddleads', $data);
 
    }
      public function addLeads()
    {
        $name = $_REQUEST['name'];
        $phonenumber = $_REQUEST['otherphonenumber'];
        $email = $_REQUEST['email'];
        $publishedEarlier = $_REQUEST['publishedEarlier'];
        $manuscriptStatus = $_REQUEST['manuscriptStatus']; 
        $bookLanguage = $_REQUEST['bookLanguage']; 
        $adName = $_REQUEST['adName']; 
        $adId = $_REQUEST['adId']; 
        $leadCreationDate = $_REQUEST['leadCreationDate']; 
        
        $data = array('lead_author_name' => $name,'phonenumber' => $phonenumber,'email' => $email,'lead_author_msstatus' => $manuscriptStatus,'lead_publishedearlier' => $publishedEarlier,'lead_author_mslanguage'=>$bookLanguage,'lead_adname'=>$adName,'lead_ad_id'=>$adId,'lead_created_date'=>date("Y-m-d").' '.date("h:i:s"),'lead_addbyui'=>'manual',' lead_reviewstatus'=>1);  
       // print_r($data);

        $this->db->insert('tblleads',$data);

        $data['success'] = "Lead Added Successfully.";
        set_alert('success', "Lead Added Successfully.");
        return redirect(admin_url('leads/addeddleads'));
      
    }
    public function allleadremark_after()
    {
        //echo "<pre>";print_r($_POST);exit;
        $lead_idnew = $_POST['id'];
        $lead_name = $_POST['name'];
        $data['lead_id'] = $_POST['id'];
        $lead_status = $_POST['status'];
        $lead_description = $_POST['description'];
        $this->db->order_by("created_on", "asc");
        $this->db->where('lead_id', $lead_idnew);
       // $this->db->group_by("remark");
        $data['allleadremark'] = $this->db->get(' tblleadremark')->result();
        $data['lead_id'] = $lead_idnew;
        $data['name'] = $lead_name;
        $data['status'] = $lead_status;
        $data['assigned'] = $_POST['assigned'];
        $data['next_calling'] = $_POST['next_calling'];
        $data['publishedEarlier'] = $_POST['publishedEarlier'];
        $data['adname'] = $_POST['adname'];
        $data['manuscript_status'] = $_POST['manuscript_status'];
        $data['user_language'] = $_POST['user_language'];
        $data['ad_id'] = $_POST['ad_id'];
        $data['created_time'] = $_POST['created_time'];
        
        $data['booktitle'] = $_POST['booktitle'];
        $data['book_format'] = $_POST['book_format'];
        $data['email'] = $_POST['email'];
        $data['otherphonenumber'] = $_POST['otherphonenumber'];
        $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
        /*echo "<pre>";
        print_r($data);
        exit;*/
        
        $data['description'] = $lead_description;
        $data['wplistlead'] = $this->leads_model->get_availwplead();
        //echo "this page";
        //print_r($data['wplistlead']);
        $this->load->view('admin/leads/viewremark_after', $data); 
        
    }
    function availablewp()
    {
        
        $data['title'] = 'Available Working Person';
        
        //echo "test";
        //exit;

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
                $leadFrom = $this->input->post('leadFrom');
                $total_count_member = count($telermids);
                $total_count_from = count($leadFrom);
               
                for ($i = 0; $i < $total_count_member; $i++) {
                    $teamdeptrm_id = $this->input->post('teamdeptrm_id');
                    $inserdata['date'] = $insert_data['date'];
                    $user_id = $telermids[$i];
                    $noOfRows =  $leadFrom[$i];
                    
                    $query = $this->db->select('id')
                    ->limit($noOfRows)
                    ->where('lead_author_msstatus', 'completed')
                    ->where('assigned',0)
                    ->order_by("id", "asc")
                    ->get('tblleads');
                    
                    foreach ($query->result() as $row){
                        $data = array('assigned' => $user_id);
                        $this->db->where('lead_author_msstatus', 'completed');
                        $this->db->where('id', $row->id);
                        $this->db->update('tblleads', $data);
                    }
                }
                
                if($noOfRows == '' || $noOfRows == null || $noOfRows == 0){
                  set_alert('success', _l('please enter a valid number greater than zero', "Available WP"));     
                }else if( $user_id != ''){
                    
                    set_alert('success', _l('added_successfully', "Available WP"));   
                }else{
                    set_alert('success', _l('please select publishing consultant', "Available WP"));   
                }
            }
            return redirect(admin_url('leads/availablewp'));
        }


        $this->db->select('id');
        $this->db->from('tblleads');
        $this->db->where('lead_author_msstatus','completed');
        $this->db->where('lead_reviewstatus !=',0);
        $leads = $this->db->where('assigned',0);
        $num_results_completed = $this->db->count_all_results();
        
        
        $this->db->select('id');
        $this->db->from('tblleads');
        $this->db->where('lead_author_msstatus','in_process');
        $this->db->where('lead_reviewstatus !=',0);
        $leads = $this->db->where('assigned',0);
        $num_results_inprogress = $this->db->count_all_results();
        
        
        
        $this->db->select('id');
        $this->db->from('tblleads');
        $this->db->where('lead_author_msstatus','');
        $this->db->where('lead_reviewstatus !=',0);
        $leads = $this->db->where('assigned',0);
        $num_results_null = $this->db->count_all_results();
       

        $data['telerm'] = $this->leads_model->get_telerm('', 'staffid,CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');
        $data['availwp'] = $this->leads_model->get_avaliablewp('', 'staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');
        $data['totLeadscompleted'] =$num_results_completed;
        $data['totLeadsinprogress'] =$num_results_inprogress;
        $data['totLeadsnull'] =$num_results_null;
        $data['leads'] =$this->leads_model->getleads();
        
        // $data['assignedwp'] = $this->leads_model->getassignedwp();
        $data['assignedwp'] = '';
        
        
       
         $this->db->select('staffid,firstname');
                      $this->db->where('department_id',24);
                      $this->db->where('active',1);
                    $query = $this->db->get('tblstaff')->result();
                    
$data['pcList']=$query;
//echo "<pre>";
//print_r($data['pcList']);exit;

        $this->load->view('admin/leads/availablewp', $data);
    }
    function availablewpinprocess(){
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
                $leadFrom = $this->input->post('leadFrom');
                $total_count_member = count($telermids);
                $total_count_from = count($leadFrom);
               
                for ($i = 0; $i < $total_count_member; $i++) {
                    $teamdeptrm_id = $this->input->post('teamdeptrm_id');
                    $inserdata['date'] = $insert_data['date'];
                    $user_id = $telermids[$i];
                    $noOfRows =  $leadFrom[$i];
                    
                    $query = $this->db->select('id')
                    ->limit($noOfRows)
                    ->where('lead_author_msstatus', 'in_process')
                    ->where('assigned',0)
                    ->order_by("id", "asc")
                    ->get('tblleads');
                    
                    foreach ($query->result() as $row){
                        $data = array('assigned' => $user_id);
                        $this->db->where('lead_author_msstatus', 'in_process');
                        $this->db->where('id', $row->id);
                        $this->db->update('tblleads', $data);
                    }
                }
                if($noOfRows == '' || $noOfRows == null || $noOfRows == 0){
                  set_alert('success', _l('please enter a valid number greater than zero', "Available WP"));     
                }else if( $user_id != ''){
                    
                    set_alert('success', _l('added_successfully', "Available WP"));   
                }else{
                    set_alert('success', _l('please select publishing consultant', "Available WP"));   
                }
            }
            return redirect(admin_url('leads/availablewp'));
        } 
    }
      function availablewpnull(){
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
                $leadFrom = $this->input->post('leadFrom');
                $total_count_member = count($telermids);
                $total_count_from = count($leadFrom);
               
                for ($i = 0; $i < $total_count_member; $i++) {
                    $teamdeptrm_id = $this->input->post('teamdeptrm_id');
                    $inserdata['date'] = $insert_data['date'];
                    $user_id = $telermids[$i];
                    $noOfRows =  $leadFrom[$i];
                    
                    $query = $this->db->select('id')
                    ->limit($noOfRows)
                    ->where('lead_author_msstatus', '')
                    ->where('assigned',0)
                    ->order_by("id", "asc")
                    ->get('tblleads');
                    
                    foreach ($query->result() as $row){
                        $data = array('assigned' => $user_id);
                        $this->db->where('lead_author_msstatus', '');
                        $this->db->where('id', $row->id);
                        $this->db->update('tblleads', $data);
                    }
                }
               if($noOfRows == '' || $noOfRows == null || $noOfRows == 0){
                  set_alert('success', _l('please enter a valid number greater than zero', "Available WP"));     
                }else if( $user_id != ''){
                    
                    set_alert('success', _l('added_successfully', "Available WP"));   
                }else{
                    set_alert('success', _l('please select publishing consultant', "Available WP"));   
                }
            }
            return redirect(admin_url('leads/availablewp'));
        } 
    }
    public function viewassignedleads2()
    {
        $useraid = $this->session->userdata('staff_user_id');
        // print_r($useraid);die;
        //echo "test<pre>";print_r($data);exit();
        if (is_admin() || is_headtrm() || $useraid == 28) {
             
            // $data['useraid'] = $useraid;
            // $this->db->select('*');
            // $data['assignedleads'] = $this->db->get('tblleads')->result();
            // $data['get_company'] = $this->leads_model->get_company2();
            // $data['get_language'] = $this->leads_model->get_language('tblleads');
            // $data['get_manuStatus'] = $this->leads_model->get_manuStatus('tblleads');
            // $data['get_createdDate'] = $this->leads_model->get_createdDate('tblleads');
            // $data['get_publishedearlier'] = $this->leads_model->get_publishedearlier('tblleads');
            // $data['get_adName'] = $this->leads_model->get_adName('tblleads');
            // $data['data_source'] = $this->leads_model->get_data_source2();
            // $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            // $data['bodyclass'] = 'hide-sidebar';
            // $this->load->view('admin/leads/viewassignedleads', $data);

            $useraid = $this->session->userdata('staff_user_id');
    $data['useraid'] = $useraid;
    $data['get_staff'] = $this->leads_model->get_pc();
    $data['get_task_type'] = $this->leads_model->get_pc_category();
    
    
    $allcount = $this->leads_model->getrecordCount($search_text);
    $this->load->library('pagination');
    $config = array();
    $config['base_url'] =  base_url() .'admin/leads/viewassignedleads2/';
    $config['total_rows'] =$allcount;
    $config['per_page'] = 100;
    $config['full_tag_open'] = "<ul class='pagination'>";
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';

    $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $this->pagination->initialize($config);
    $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
    $data["links"] = $this->pagination->create_links();
  
    $data['leads'] =  $this->leads_model->leedsData($config["per_page"], $page);
    $data['bodyclass'] = 'hide-sidebar';
    // print_r($data['leads']);die;
    $this->load->view('admin/leads/viewassignedleads_array', $data);

        } else if($useraid == 34 || $useraid == 28) {
               $data['useraid'] = $useraid;
            // $this->db->where('assigned', $useraid);
            $this->db->select('*');
            
            $data['assignedleads'] = $this->db->get('tblleads')->result();
             //$data['lstatus'] = $this->leads_model->get_leadstatus1('tblleadsstatus');
             $data['get_company'] = $this->leads_model->get_company2();
             $data['get_language'] = $this->leads_model->get_language('tblleads');
             $data['get_manuStatus'] = $this->leads_model->get_manuStatus('tblleads');
             $data['get_createdDate'] = $this->leads_model->get_createdDate('tblleads');
             $data['get_publishedearlier'] = $this->leads_model->get_publishedearlier('tblleads');
             $data['get_adName'] = $this->leads_model->get_adName('tblleads');
            $data['data_source'] = $this->leads_model->get_data_source2();
            $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            $data['bodyclass'] = 'hide-sidebar';
            // print_r($data);die;
            $this->load->view('admin/leads/viewassignedleads', $data);
        }else{
            // echo "string";die;
           
           $useraid = $this->session->userdata('staff_user_id');
            
             $data['useraid'] = $useraid;
              $this->db->select('*');
           
             $this->db->where('assigned', $useraid);
            $data['assignedleads'] = $this->db->get('tblleads')->result();
            // print_r($data['assignedleads']);
             //$data['lstatus'] = $this->leads_model->get_leadstatus1('tblleadsstatus');
             $data['get_company'] = $this->leads_model->get_company2();
             $data['get_language'] = $this->leads_model->get_language('tblleads');
             $data['get_manuStatus'] = $this->leads_model->get_manuStatus('tblleads');
             $data['get_createdDate'] = $this->leads_model->get_createdDate('tblleads');
             $data['get_publishedearlier'] = $this->leads_model->get_publishedearlier('tblleads');
             $data['get_adName'] = $this->leads_model->get_adName('tblleads');
            $data['data_source'] = $this->leads_model->get_data_source2();
            $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            $data['bodyclass'] = 'hide-sidebar';
           
        $this->load->view('admin/leads/viewassignedleads', $data);

        }
    }
    public function getallleads()
    {
        $id = $_POST['id'];
        $this->db->where('id',$id);
        $query = $this->db->get('tblleads');
        $array = array();
        $id=0;
        $name ='';
        $data = '';
        
       
        foreach($query->result_array() as $row)
        {
           
            
                $id =$row['id'];
            
                $full_name =$row['lead_author_name'];
            
                $email = $row['email'];
            
                $phone_number =$row['phonenumber'];
            
               $ad_name =$row['lead_adname'] ; 
            
                $book_format =$row['lead_bookformat'];
            
                $booktitle =$row['lead_booktitle'];
            
                $PublishedEarlier =$row['lead_publishedearlier'];
            
               $categorisation = $row['lead_category_id'] ;
            
            
            
                $next_callingd =$row['next_calling'];
           
                $otherphonenumber_n = $row['otherphonenumber'];
            
                $manuscript_status =$row['lead_author_msstatus'];
            
                $user_language =$row['lead_author_mslanguage']; 
           
    
           
           
           $data=array();
              
             $data['id']=$id;
             $data['full_name']=$full_name;
             $data['email']=$email;
             $data['phone_number']=$phone_number;
             $data['ad_name']=$ad_name;
             $data['book_format']=$book_format;
             $data['booktitle']=$booktitle;
             $data['PublishedEarlier']=$PublishedEarlier;
             $data['categorisation']=$categorisation;
             $data['next_callingd']=$next_callingd;
             $data['next_calling']=$next_calling;
             $data['otherphonenumber_n']=$otherphonenumber_n;
             $data['manuscript_status']=$manuscript_status;
             $data['user_language']=$user_language;
             
            
             
            $xdata=json_encode($data);
            
            
           
            
        }
        print_r($xdata);
    }
      public function allleadremark2()
    {
       $lead_idnew = $_POST['id'];
       $data['all_book'] = $this->db->select('book_title')->get_where('tblleads_create_package',array('leadid'=>$lead_idnew))->result();
    //    print_r($data);die;
        $lead_name = $_POST['name'];
        $data['lead_id'] = $_POST['id'];
        $lead_status = $_POST['status'];
        $lead_description = $_POST['description'];
        // $this->db->order_by("created_on", "asc");
        $this->db->where('lead_id', $lead_idnew);
        // $this->db->group_by("remark");
        $data['allleadremark'] = $this->db->get(' tblleadremark')->result();

        $this->db->where('id', $lead_idnew);
        $data['all_lead_data'] = $this->db->get('tblleads')->row();
        $data['lead_id'] = $lead_idnew;
        $data['name'] = $lead_name;
        $data['status'] = $lead_status;
        $data['assigned'] = $_POST['assigned'];
        $data['next_calling'] = $_POST['next_calling'];
         $data['next_callingd'] =  "n11nnnhjghjgh".$_POST['next_callingd'];
        $data['publishedEarlier'] = $_POST['publishedEarlier'];
        $data['phonenumber'] = $_POST['phonenumber'];
        
        $data['booktitle'] = $_POST['booktitle'];
        $data['book_format'] = $_POST['book_format'];
        $data['manuscript'] = $_POST['manuscript'];
        $data['language'] = $_POST['language'];
        $data['email'] = $_POST['email'];
        
        $data['otherphonenumber'] = $_POST['otherphonenumber'];
        $data['lstatus'] = $this->leads_model->get_leadstatus2('tblleadsstatus');
        $data['categoryStatus'] = $_POST['status'];
    
        $data['description'] = $lead_description;
        $data['wplistlead'] = $this->leads_model->get_availwplead();
        $data["compare_id"] = $this->db->select('*')->where("leadid",$_POST['id'])->get('compaire_create_package')->row();
        $data['status'] = $_POST['category'];
//print_r($data);
        $this->load->view('admin/leads/viewremark2', $data); 
        
    }





    public function get_lead_multi_b()
    {
       $lead_id = $_POST['lead_id'];
       $data['lead_id'] = $_POST['lead_id'];
        $book_name = $_POST['book_name'];
        $data['booktitle'] = $_POST['book_name'];

        $this->db->select('tblleads.id,tblleads.lead_author_name as author_name,tblleads.email,tblleads.phonenumber,tblleads.otherphonenumber,tblleads_create_package.*,tblleads_create_package.id as multiple_book_id,tblleads_create_package.lead_packg_totalamount');

        $this->db->from('tblleads_create_package');
        $this->db->join('tblleads', 'tblleads.id = tblleads_create_package.leadid');
         $this->db->where('tblleads_create_package.leadid', $lead_id); 
        $this->db->where('tblleads_create_package.book_title', $book_name);
       

        $result = $this->db->get(); 

        $data['all_data'] = $result->row();
        // echo "<pre>";
        // print_r($data);die;
        $data['lstatus'] = $this->leads_model->get_leadstatus2('tblleadsstatus');
        $this->load->view('admin/leads/remark_multiple_book', $data); 
        
    }

    public function exportleads()
    {
         $useraid = $this->session->userdata('staff_user_id');
         if (is_admin() || is_headtrm() || $useraid == 28){
              $useraid = $this->session->userdata('staff_user_id');
            
            // $data['useraid'] = $useraid;
            // $this->db->where('assigned', $useraid);
             $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
            $this->db->order_by("lead_created_date","asc");
            $data['assignedleads'] = $this->db->get('tblleads')->result();
            
            $data['get_staff'] = $this->leads_model->get_pc();
             $data['get_task_type'] = $this->leads_model->get_pc_category();
            $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
            $data['get_company'] = $this->leads_model->get_company2();
            $data['get_language'] = $this->leads_model->get_language('tblleads');
            $data['get_manuStatus'] = $this->leads_model->get_manuStatus('tblleads');
            $data['get_createdDate'] = $this->leads_model->get_createdDate('tblleads');
            $data['get_publishedearlier'] = $this->leads_model->get_publishedearlier('tblleads');
            $data['get_adName'] = $this->leads_model->get_adName('tblleads');
            $data['data_source'] = $this->leads_model->get_data_source2();
            $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            //$data['bodyclass'] = 'hide-sidebar';
            $this->load->view('admin/leads/new_exportleads', $data);
         }else{
            $useraid = $this->session->userdata('staff_user_id');
            
            $data['useraid'] = $useraid;
            // $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
            // $this->db->where('assigned', $useraid);
            // $this->db->order_by("lead_created_date","desc");
            // $this->db->limit(1500);
        
            // $data['assignedleads'] = $this->db->get('tblleads')->result();
            
            $data['get_staff'] = $this->leads_model->get_pc();
             $data['get_task_type'] = $this->leads_model->get_pc_category();
            $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
            $data['get_company'] = $this->leads_model->get_company2();
            $data['get_language'] = $this->leads_model->get_language('tblleads');
            $data['get_manuStatus'] = $this->leads_model->get_manuStatus('tblleads');
            $data['get_createdDate'] = $this->leads_model->get_createdDate('tblleads');
            $data['get_publishedearlier'] = $this->leads_model->get_publishedearlier('tblleads');
            $data['get_adName'] = $this->leads_model->get_adName('tblleads');
            $data['data_source'] = $this->leads_model->get_data_source2();
            $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            //$data['bodyclass'] = 'hide-sidebar';
             $this->load->view('admin/leads/new_exportleads', $data);
         }
          
       
    }
     public function filter_by_task_type()
    {
       $leads = $this->leads_model->filter_by_task_type1();
        // print_r($leads);die;
        $totalCount = $this->leads_model->work_report_get_total();
        // print_r($totalCount);die;
        $json_data = array(
            "draw"            => intval( $_POST['draw'] ),   
            "recordsTotal"    => intval( $totalCount ),  
            "recordsFiltered" => intval($totalCount),
            "aaData"            => $leads   // total data array
            );
            echo json_encode($json_data);
        // $this->printLeadData($leads);
    }
     public function addRemarks()
    {
            $remarks = $this->input->post('rem');
            $id = $this->input->post('remarks_id');

            $value=array('lead_remark'=>$remarks, 'ImEx_leadRemarks'=>$remarks);
            $this->db->where('id',$id);
            $check = $this->db->update('tblleads',$value);
            if($check){
              echo "YES";  
            }else{
                 echo "NO"; 
            }
           
            
    }
     function uploadleadsbyPc()
    { 
        /*$this->leads_model->uploadleadsbyPc();
        
        redirect('admin/leads/importleads');*/
        $this->leads_model->uploadleadsbyPc();
       $total_imported = 'test';
       echo '<script>alert("import Succesfully");
       window.location.replace("importleads");</script>';
    }
     public function refferleads()
    {
           $useraid = $this->session->userdata('staff_user_id');
           //print_r($useraid);die;
            $this->db->select('id,assigned,status,lead_created_date,email,lead_adname,lead_author_msstatus,lead_author_name,phonenumber,created_at,lead_author_mslanguage,lead_publishedearlier');
           
            $this->db->where('assigned',$useraid);
            $this->db->where('lead_addbyui','refer');
            $data['assignedleads'] = $this->db->get('tblleads')->result();
            
            $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
            $data['get_company'] = $this->leads_model->get_company2();
            
            $data['get_language'] = $this->leads_model->get_language('tblleads');
            $data['get_manuStatus'] = $this->leads_model->get_manuStatus('tblleads');
            $data['get_createdDate'] = $this->leads_model->get_createdDate('tblleads');
            
            $data['get_adName'] = $this->leads_model->get_adName('tblleads');
           $data['get_publishedearlier'] = $this->leads_model->get_publishedearlier('tblleads');
            $data['data_source'] = $this->leads_model->get_data_source2();
            $data['calling_obj'] = $this->leads_model->get_calling_obj2();
            $data['getleadsource'] = $this->leads_model->get_leadsource2();
            //$data['bodyclass'] = 'hide-sidebar';
            
            $this->load->view('admin/leads/refferleads', $data);
       
    }
       public function addRefLeads(){
        $useraid = $this->session->userdata('staff_user_id');
        $name = $_GET['name'];
        $phonenumber = $_GET['otherphonenumber'];
        $email = $_GET['email'];
        $publishedEarlier = $_GET['publishedEarlier'];
        $manuscriptStatus = $_GET['manuscriptStatus']; 
        $bookLanguage = $_GET['bookLanguage']; 
        $adName = $_GET['adName']; 
        $adId = $_GET['adId']; 
        $leadCreationDate = date("Y-m-d h:i:s"); 
        $bookFormat= $_GET['bookFormat'];
        $bookLanguage= $_GET['bookLanguage'];
        $category= $_GET['category'];
        $description= $_GET['description'];
        $next_calling= $_GET['next_calling'];
        $bookTitle= $_GET['bookTitle'];
        
        $data = array('assigned'=>$useraid,'lead_booktitle'=>$bookTitle,'next_calling'=>$next_calling,'description'=> $description,'lead_bookformat' => $bookFormat,'lead_author_mslanguage' => $bookLanguage, 'status'=> $category, 'lead_author_name' => $name,'phonenumber' => $phonenumber,'email' => $email,'lead_author_msstatus' => $manuscriptStatus,'lead_publishedearlier' => $publishedEarlier,'lead_adname'=>$adName,'lead_ad_id'=>$adId,'lead_created_date'=>$leadCreationDate,'lead_reviewstatus'=>1,'lead_addbyui'=>'refer');  
        $this->db->insert('tblleads', $data);
      //echo  $this->db->last_query();die;
        $insertId=$this->db->insert_id();
        
        if($description != null) {
            $data2 = array('lead_id' => $insertId, 'remark' => $description, 'added_by' => $useraid);
            $this->db->insert('tblleadremark', $data2);
        }
        
        $data['success'] = "Lead Added Successfully.";
        set_alert('success', "Lead Added Successfully.");
        return redirect(admin_url('leads/refferleads'));
      
    }
        public function editReferleads()
    {
        $lead_id = end($this->uri->segment_array());
        
        $this->db->where('id',$lead_id);
        $data['leadsdetails'] = $this->db->get('tblleads')->result();
        // print_r($data);die;
        $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
        $this->db->select('id,lead_id,remark,added_by');
        $this->db->order_by("created_on", "desc");
        $this->db->where('lead_id', $lead_id);
        // $this->db->group_by("remark");
        $data['allleadremark'] = $this->db->get(' tblleadremark')->result();
        // echo $this->db->last_query();die;
        // print_r($data);
        // exit;
        $this->load->view('admin/leads/edit_ref_lead', $data); 
        
    }
     public function updateRefLeads(){
        $leadid = $_GET['lead_id'];
        $userid = $this->session->userdata('staff_user_id');
        $name = $_GET['name'];
        $phonenumber =$_GET['phonenumber'];
        $otherPhone = $_GET['otherphonenumber'];
        $email = $_GET['email'];
        $bookTitle = $_GET['booktitle'];
        $adName = $_GET['adName'];
        $bookFormat = $_GET['book_format'];
        $manuscriptStatus = $_GET['manuscriptStatus'];
        $bookLanguage = $_GET['bookLanguage'];
        $publishedEarlier= $_GET['publishedEarlier'];
        $next_calling = $_GET['next_calling'];
        $category = $_GET['category'];
        $description = $_GET['description'];
          
        // $data = array('otherphonenumber'=>$otherPhone,'booktitle'=>$bookTitle,'next_calling'=>$next_calling,'description'=> $description,'book_format' => $bookFormat,'language' => $bookLanguage, 'status'=> $category, 'full_name' => $name,'phone_number' => $phonenumber,'email' => $email,'manuscript_status' => $manuscriptStatus,'PublishedEarlier' => $publishedEarlier,'user_language'=>$bookLanguage,'ad_name'=>$adName,'review_status'=>1);

         $data = array('otherphonenumber'=>$otherPhone, 'lead_booktitle'=>$bookTitle,'next_calling'=>$next_calling,'description'=> $description,'lead_bookformat' => $bookFormat,'lead_author_mslanguage' => $bookLanguage, 'status'=> $category, 'lead_author_name' => $name,'phonenumber' => $phonenumber,'email' => $email,'lead_author_msstatus' => $manuscriptStatus,'lead_publishedearlier' => $publishedEarlier,'lead_adname'=>$adName,'lead_reviewstatus'=>1);  

        $this->db->where('id', $leadid);
        $this->db->update('tblleads', $data);
        
        if($description != null) {
            $data2 = array('lead_id' => $leadid, 'remark' => $description, 'added_by' => $userid);
            $this->db->insert('tblleadremark', $data2);
        }
        
        $data['success'] = "success";
        set_alert('success', "Lead Updated Successfully");
        return redirect(admin_url('leads/refferleads'));
          
    }
        public function publishing()
    {
         $data['url'] = $_SERVER['HTTP_REFERER'];

        
        $leadId = end($this->uri->segment_array());
        $data['leadData'] = $this->leads_model->getLeadData($leadId);
        
        $data['title'] = "Publishing";
        $data['business'] = "";
        // echo "<pre>";
        // print_r($data);
        // die();
        $this->load->view('admin/leads/publishing', $data);
    }
    public function getservies(){
        if(empty($_POST['package_data'])){
            $book_type = $_POST['book_type'];
            $package = $_POST['package'];
            $result = $this->leads_model->getservies_standard($package, $book_type);

            $services = array();
            $new_sub_cost =1;
            //$servicecost = 1;
            $html .= "<ul class='dynamicservice'>";
            foreach($result as $data){
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                $html .= "<li style='font-weight:bold;'><input type='checkbox' name='services[]' class='servicesstand' value='".$data['serviceid']."' checked onclick='return false;' data-name='".$data['service_name']."' > ".$data['service_name']."</li>";
                $html .= "<ul>";
                    $subservices = $this->sub_services($book_type, $package, $data['serviceid']);
                    
                    foreach($subservices as $subservice){
                    $html .= "<li class='".$subservice->cost."'><input type='checkbox' name='sub_services[]' value='".$subservice->id."' class='subservicesstand' checked onclick='return false;' data-name='".$subservice->subservice_name."' > ".$subservice->subservice_name."</li>";
                    $sub_servicecost += $subservice->cost;

                    }

                $html .= "</ul>";
// echo $this->db->last_query();
            
            //$new_sub_cost =   $sub_servicecost;
            }
            // print_r("test".$new_sub_cost);die;
            $html .= "</ul>";
            $data['html'] = $html;
            $sub_servicecost += ($sub_servicecost*40)/100;
            $data['pkgvalue'] = $sub_servicecost; 
            $data['gst'] = ($sub_servicecost*18)/100; 
            $data['totalamt'] = ($sub_servicecost+$data['gst']); 
            echo json_encode($data);exit;
        }else{
            $package = $_POST['package_data'];
            $book_type = $_POST['book_type'];
            $result = $this->leads_model->getservies($package, $book_type);
            // echo $this->db->last_query();exit;
            $services = array();
            $services[] = "<option value=''>--Select--</option>";
            foreach($result as $data){
                $services[] = "<option value='".$data['serviceid']."'>".$data['service_name']."</option>";
            }
            echo json_encode($services);
        }
        
    }

    function sub_services($book_type,$packages,$serviceid){
        $this->db->select('*');
        $this->db->from('tblpackagesubservices');
        $this->db->where('book_type', $book_type); 
        $this->db->where('package_value', $packages); 
        $this->db->where('serviceid', $serviceid); 
        $this->db->where('packageid', 1); 
        $result = $this->db->get(); 
        return $result->result();
    }
    public function getsubservies(){
        $services = $_POST['services'];
        $book_type = $_POST['book_type'];
        $packages = $_POST['packages'];
        $increment = $_POST['increment'];
// echo $services."book".$book_type;die;
        if($book_type != "" && $packages != "" && $services != ""){
            $result = $this->leads_model->getsubserviesstandard($book_type,$packages,$services);
            //var_dump($result);die;
        
        }else{
            $result = $this->leads_model->getsubservies($services, $book_type);
                //print_r($result);die;
                if(empty($result)){
                   $result_data = $this->db->get_where('tblpackagesubservices',array('serviceid'=>$service,'book_type'=>$book_type))->result_array();
                 //var_dump($result_data);die;
                    
                }
            
        }
        
        $html = $this->randerhtml($result, $packages,$services ,$increment);

        echo $html;
    }

    public function randerhtml($result, $packages=NULL, $services , $increment){
        if(!empty($result)){

            if($services == 2){ ?>
            <div class="col-md-12">
               <div class="form-group">
               <label for="services" class="control-label">No of pages:</label>
                <input type="text" id="increment" name="increment"  class="form-control" value="1" >
             </div>
            </div>
            <?php }
            
        ?>
        
        <div class="col-md-5">
            <label for="sub_service" class="control-label">Sub Services:</label>
        </div>
        <div class="col-md-4">
            <label for="cost" class="control-label">Cost:</label>
        </div>
        <div class="col-md-3" style="min-height: 30px;"></div>
        <?php
        foreach($result as $data){ 
            if ($data->subservice_name == "Complimentary Author Copies" ) { 
               $cost_number =  $data->cost/100; ?>

                <div class="col-md-12">
               <div class="form-group">
               <label for="services" class="control-label">No of Copy:</label>
                <input type="text" id="complimentry_copies" name="complimentry_copies" onkeyup='myFunctiondatacomplimentry("<?= $data->id; ?>","<?=$data->cost ?>");'  class="form-control" readonly value="10" >
             </div>
            </div>
           <?php }
                if ($data->subservice_name == "Additional Author Copies - Order at Subsidised Price" ) { 
                    $cost_number =  $data->cost/100; ?>
     
                     <div class="col-md-12">
                    <div class="form-group">
                    <label for="services" class="control-label">No of Copy:</label>
                     <input type="text" id="additional_author_copy_customized" name="additional_author_copy_customized"   class="form-control" style="display:none;" >
                  </div>
                 </div>
                <?php }
            ?>

        <div class="main-data">
            <div class="col-md-5">

             <div class="form-group sub_service_data">
                <label class="checkbox-inline">

                <input type="checkbox" id="check_box<?= $data->id ?>" class="subservice_check <?php if($data->subservice_name == "Format Editing" ){ echo 'page_cost'; }elseif($data->subservice_name == "Proofreading"){ echo 'page_cost2'; }elseif($data->subservice_name == "Hindi Typing"){ echo 'page_cost3'; }elseif($data->subservice_name == "English Typing"){ echo 'page_cost4'; }elseif($data->subservice_name == "Urdu Typing"){ echo 'page_cost5'; }elseif($data->subservice_name == "Color Pages"){ echo 'color_pages_customized_class'; }elseif($data->subservice_name == "Additional Author Copies - Order at Subsidised Price"){ echo 'additional_author_copy_customized_class'; } ?>" name="sub_services[]" data-cost="<?php echo $data->cost; ?>"  data-cost_money="<?php echo $data->cost; ?>" data-id="<?php echo $data->id; ?>" value="<?php echo $data->id; ?>" <?php if($data->cost == 15){
                    if($data->subservice_name == "Format Editing" ){ echo 'data-page-cost = "15"'; }elseif($data->subservice_name == "Proofreading"){ echo 'data-page-cost = "15"'; }
                }elseif ($data->cost == 11) {
                    if($data->subservice_name == "Format Editing" ){ echo 'data-page-cost = "11"'; }elseif($data->subservice_name == "Proofreading"){ echo 'data-page-cost = "11"'; }
                }  ?> data-name="<?php echo $data->subservice_name; ?>" <?php if($data->cost == 0){ echo "checked"; } ?> <?php if($data->cost == 0){ ?>onclick='return false;' <?php } ?> >
                <?php echo $data->subservice_name; ?>
                </label>
             </div>
            </div>
            <div class="col-md-4">
             <div class="form-group">
                <div class="dropdown bootstrap-select form-control">

                   <input type="text" id="cost[]" name="cost" class="form-control check_box<?= $data->id ?> <?php if($data->subservice_name == "Number of Pages Allowed" ){ echo 'number_page_allowed'; } if($data->subservice_name == "Format Editing" ){ echo 'cost_page'; }elseif($data->subservice_name == "Proofreading"){ echo 'cost_page2'; }elseif($data->subservice_name == "Hindi Typing"){ echo 'cost_page3'; }elseif($data->subservice_name == "English Typing"){ echo 'cost_page4'; }elseif($data->subservice_name == "Urdu Typing"){ echo 'cost_page5'; } ?>" readonly  value="<?php if($data->subservice_name == "Number of Pages Allowed" ){ echo $increment; }else{?><?php echo $data->cost; }?>" >
                </div>
             </div>
            </div>
            <div class="col-md-3" style="min-height: 70px;">
                <?php if($data->subservice_name == "Format Editing"){ ?>
                <div class="form-group">
                    <div class="dropdown bootstrap-select form-control">
                       <input type="text" name="pages[]" class="form-control page-cost" min="1" value="1" readonly>
                    </div>
                </div>
                <?php } ?>
                <?php if($data->subservice_name == "Proofreading"){ ?>
                <div class="form-group">
                    <div class="dropdown bootstrap-select form-control">
                       <input type="text" name="pages2[]" class="form-control page-cost2" min="1" value="1" readonly>
                    </div>
                </div>
                <?php } if($data->subservice_name == "Paper Type"){ ?>
                <div class="form-group">
                     <?php $paper_type = explode("/",$data->subServiceNameValue); ?>
                     <select id='paper_type_c' name='paper_type_c' class="form-control">
                        <option>---select----</option>
                     <?php foreach ($paper_type as $key => $value) { ?>
                      <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                     
                    </select>
                </div>
                <?php } if($data->subservice_name == "Book Size"){ ?>
                <div class="form-group">
                     <?php $book_size = explode("/",$data->subServiceNameValue); ?>
                     <select id='book_size_c' name='book_size_c' class="form-control">
                        <option>---select----</option>
                     <?php foreach ($book_size as $key => $value) { ?>
                      <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                     
                    </select>
                </div>
                <?php } if($data->subservice_name == "Lamination"){ ?>
                <div class="form-group">
                     <?php $Lamination = explode("/",$data->subServiceNameValue); ?>
                     <select id='lamination_c' name='lamination_c' class="form-control">
                        <option>---select----</option>
                     <?php foreach ($Lamination as $key => $value) { ?>
                      <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                     
                    </select>
                </div>
                <?php }if($data->subservice_name == "Book Cover"){ ?>
                <div class="form-group">
                     <?php $book_cover = explode("/",$data->subServiceNameValue); ?>
                     <select id='book_cover_c' name='book_cover_c' class="form-control">
                        <option>---select----</option>
                     <?php foreach ($book_cover as $key => $value) { ?>
                      <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                     
                    </select>
                </div>
                <?php }elseif ($data->subservice_name == "Color Pages") { ?>
                	 <input type="text" id="color_pages_customized" name="color_pages_customized"   class="form-control" style="display:none;" >
               <?php }  ?>
             </div>
            </div>
          <?php 
        }
        }
        ?>
        <script>

        $(document).ready(function() {



            var update_package_value = parseInt($('#package_value').val());
            $(".checkbox-inline").click(function(){
                
                    //$(document).ready(function(){
                    if($("#total_amount").val()){
                    var total_amount_d = $("#total_amount").val();
                    var discount_40 = ((40/100)*total_amount_d).toFixed(2);
                    var discount_40_ = (40/100)*total_amount_d;
                    var discount_20 = total_amount_d - (discount_40+discount_40);
                    var discount_20_ = ((20/100)*total_amount_d).toFixed(2);
                    document.getElementById("prec_40").innerHTML = '(₹ '+Math.round(discount_40)+')';
                    document.getElementById("prec_40_").innerHTML = '(₹ '+Math.round(discount_40)+')';
                    document.getElementById("prec_20").innerHTML = '(₹ '+Math.round(discount_20_)+')';
                    document.getElementById("prec_400").innerHTML = '₹ '+Math.round(discount_40)+'';
                    document.getElementById("prec_400_").innerHTML = '₹ '+Math.round(discount_40)+'';
                    document.getElementById("prec_200").innerHTML = '₹ '+Math.round(discount_20_)+'';

                    }
                    //});
                
                    
                var favorite = [];
                
                //console.log(typeof(update_package_value));
                //alert($(this +" .subservice_check").attr("data-id"));
                $.each($(".subservice_check:checked"), function(){            
                    favorite.push($(this).attr("data-cost"));
                });
                var editcost = 0;
                editcost = $(".cost_page").val()*100;
                //console.log(editcost);
                var total = 0;
                for (var i = 0; i < favorite.length; i++) {
                    total += favorite[i] << 0;
                }
                if($("#book_type").val() == 'paperback'){
                if($('.page_cost').is(':checked')){
                    //console.log($(this).val());
                    var cost1 = $(".cost_page").val()*100;
                    total = (total+(cost1/100))-11;
                }
                if($('.page_cost2').is(':checked')){
                    var cost1 = $(".cost_page2").val()*100;
                    total = (total+(cost1/100))-11;
                }       
            }else if($("#book_type").val() == 'ebook'){
                    if($('.page_cost').is(':checked')){
                    //console.log($(this).val());
                    var cost1 = $(".cost_page").val()*100;
                    total = (total+(cost1/100))-11;
                }
                if($('.page_cost2').is(':checked')){
                    var cost1 = $(".cost_page2").val()*100;
                    total = (total+(cost1/100))-11;
                }   
            }
                
                // alert(total);
                    
                    
            if(update_package_value != ""){
                var total_data = total;
                // var total_data = total+update_package_value;
            }else{
                var total_data = total;
            }
              // alert(total_data);
                
                if($("#book_type").val() == 'paperback'){
                    //alert('test');
                var total_data_percent = (total_data+(total_data*0.40))+400
            }else{
                var total_data_percent = total_data+(total_data*0.40)
            }

                
                $("#lead_ori_packge_value").val(Math.round(total_data_percent));
                $("#package_value").val(Math.round(total_data_percent));
                if($("#discount").val()){
                    var discount_datata = $("#discount").val();
                
                     var total_after_discount = ( discount_datata / 100) * total_data_percent;
                    total_after_discount = total_data_percent - total_after_discount;
                    $("#less_package_value").val(Math.round(total_after_discount));
                }else{
                    $("#less_package_value").val(Math.round(total_data_percent));
                }
                
                
                var gst = (total_data_percent*18)/100;
            
                $("#gst").val(Math.round(gst));
                var total_value = parseInt(gst)+parseInt(total_data_percent);
                if($("#discount").val()){
                total_value = gst+total_after_discount;
                }
                $("#total_amount").val(Math.round(total_value));
            });
            $(".page-cost").keyup(function(){
                var pages = this.value;
                //alert($(this +" .subservice_check").attr("data-id"));
                var page_cost = $(".page_cost").attr("data-page-cost");
                var total_cost = page_cost*pages;
                $(".cost_page").val(total_cost);
                var favorite = [];
                //alert($(this +" .subservice_check").attr("data-id"));
                $.each($(".subservice_check:checked"), function(){            
                    favorite.push($(this).attr("data-cost"));
                });
                var editcost = 0;
                editcost = $(".cost_page").val()*100;
                var total = 0;
                for (var i = 0; i < favorite.length; i++) {
                    total += favorite[i] << 0;
                }
                //console.log(total);
                if($("#book_type").val() == 'paperback'){
                if($('.page_cost').is(':checked')){
                    var cost1 = $(".cost_page").val()*100;
                    total = (total+(cost1/100))-11;
                }
                if($('.page_cost2').is(':checked')){
                    var cost1 = $(".cost_page2").val()*100;
                    total = (total+(cost1/100))-11;
                }   
            }else if($("#book_type").val() == 'ebook'){
                    if($('.page_cost').is(':checked')){
                    var cost1 = $(".cost_page").val()*100;
                    total = (total+(cost1/100))-11;
                }
                if($('.page_cost2').is(':checked')){
                    var cost1 = $(".cost_page2").val()*100;
                    total = (total+(cost1/100))-11;
                }
            }
                
                console.log(total);
                $("#package_value").val(Math.round(total));
                $("#less_package_value").val(Math.round(total));
                var gst = (total*18)/100;
                // $("#gst").val(gst);
                $("#gst").val(Math.round(gst));
                var total_value = gst+total;
                $("#total_amount").val(Math.round(total_value));
            });
            $("#increment").keyup(function(){ 
                var vall = this.value;
            //    alert(vall);
                $(".number_page_allowed").val(vall);

                var page_cost = $(".page_cost").attr("data-cost_money");
                var page_cost1 = $(".page_cost2").attr("data-cost");
                // alert(page_cost+'page_cost');
                // alert(page_cost1+'page_cost1');
                var page_cost3 = $(".page_cost3").attr("data-cost_money");
                var page_cost4 = $(".page_cost4").attr("data-cost_money");
                var page_cost5 = $(".page_cost5").attr("data-cost_money");
                // alert(page_cost3+'page_cost3');
                // alert(page_cost4+'page_cost4');
                // alert(page_cost5+'page_cost5');
                var total_cost = page_cost*vall; 
                // alert(total_cost+'total_cost');
                var total_cost2 = page_cost1*vall;
                var total_cost3 = page_cost3*vall;
               // alert(total_cost3);
                var total_cost4 = page_cost4*vall;
                var total_cost5 = page_cost5*vall;
                // alert(total_cost+'page_cost');
                // alert(total_cost2+'page_cost2');
                // alert(total_cost3+'page_cost3');
                // alert(total_cost4+'page_cost4');
                $(".page-cost").val(vall);
                $(".page-cost2").val(vall);
                $(".cost_page").val(total_cost);
                $(".cost_page2").val(total_cost2);
                $(".cost_page3").val(total_cost3);
                $(".cost_page4").val(total_cost4);
                $(".cost_page5").val(total_cost5);
                $(".page_cost3").attr('data-cost',total_cost3); 
                $(".page_cost4").attr('data-cost',total_cost4); 
                $(".page_cost5").attr('data-cost',total_cost5); 
                var pages = this.value;
                //alert($(this +" .subservice_check").attr("data-id"));
                var page_cost = $(".page_cost").attr("data-cost_money");
                var total_cost = page_cost*pages;
                $(".cost_page").val(total_cost);
                var favorite = [];
                //alert($(this +" .subservice_check").attr("data-id"));
                $.each($(".subservice_check:checked"), function(){            
                    favorite.push($(this).attr("data-cost"));
                });
                var editcost = 0;
                editcost = $(".cost_page").val()*100;
                var total = 0;
                for (var i = 0; i < favorite.length; i++) {
                    total += favorite[i] << 0;
                }
                //console.log(total);
                if($('.page_cost').is(':checked')){
                    var cost1 = $(".cost_page").val()*100;
                    total = (total+(cost1/100))-11;

                    $("#number_of_pages").val(pages);
                }
                if($('.page_cost2').is(':checked')){
                    var cost1 = $(".cost_page2").val()*100;
                    total = (total+(cost1/100))-11;
                }
                console.log(total);
                $("#package_value").val(Math.round(total));
                $("#less_package_value").val(Math.round(total));
                var gst = (total*18)/100;
                // $("#gst").val(gst);
                $("#gst").val(Math.round(gst));
                var total_value = gst+total;
                $("#total_amount").val(Math.round(total_value));
                
            });
            
            
            $(".page-cost2").keyup(function(){
                var pages = this.value;
                //alert($(this +" .subservice_check").attr("data-id"));
                var page_cost = $(".page_cost2").attr("data-page-cost");
                var total_cost = page_cost*pages;
                $(".cost_page2").val(total_cost);
                var favorite = [];
                //alert($(this +" .subservice_check").attr("data-id"));
                $.each($(".subservice_check:checked"), function(){            
                    favorite.push($(this).attr("data-cost"));
                });
                var editcost = 0;
                editcost = $(".cost_page2").val()*100;
                var total = 0;
                for (var i = 0; i < favorite.length; i++) {
                    total += favorite[i] << 0;
                }
                if($('.page_cost').is(':checked')){
                    var cost1 = $(".cost_page").val()*100;
                    total = (total+(cost1/100))-11;
                }
                if($('.page_cost2').is(':checked')){
                    var cost1 = $(".cost_page2").val()*100;
                    total = (total+(cost1/100))-11;
                }
                //console.log(total);
                $("#package_value").val(Math.round(total));
                $("#less_package_value").val(Math.round(total));
                var gst = (total*18)/100;
                // $("#gst").val(gst);
                $("#gst").val(Math.round(gst));
                var total_value = gst+total;
                $("#total_amount").val(Math.round(total_value));
            })
        });
    </script>
        <?php
    }
    public function saveLeads(){

//         echo '<pre>';
//  print_r($_POST);die();

        $this->load->helper('url');
        $this->load->library('excel');
        $this->load->library('pdf');
        
        $this->load->config('email');
        $this->load->library('email');
        $this->load->helper('phpass');
        $url = $this->input->post('url');
        $no_of_page = $this->input->post('increment');


        $craete_package = $this->input->post('craete_package');
        $authorId = $this->input->post('author_id');
        $email = $this->input->post('email');
        $name = $this->input->post('author_name');
        if(!empty($this->input->post('package_name_data'))){
            $package__data = $this->input->post('package_name_data');
        }else{
            $package__data = $this->input->post('package_name');
        }
        //echo $package_name_data;die;
        $filename = str_replace(' ', '', $name).".pdf";
       
        $data['name'] = $name;
        $data['email'] = $email;
        $data['mobile'] = $this->input->post('mobile');
        $data['packagecost'] = $this->input->post('cost');
        
        $data['full_payment_disc'] = $this->input->post('full_payment');
        $data['total_amount_popup_pdf'] = $this->input->post('total_amount_popup_pdf');
        $data['booking_amount_popup_pdf'] = $this->input->post('booking_amount_popup_pdf');
        $data['booking_f_amount_popup_pdf'] = $this->input->post('booking_f_amount_popup_pdf');
        $data['booking_s_amount_popup_pdf'] = $this->input->post('booking_s_amount_popup_pdf');
if ($this->input->post('number_of_complimentary_copies_edit')) {
    $number_of_complimentary_copies = $this->input->post('number_of_complimentary_copies_edit');
}else{
    $number_of_complimentary_copies = $this->input->post('number_of_complimentary_copies');
}
       if($this->input->post('package_details') == 1 ){
            $data['package_name'] = $this->input->post('package_name_data');
            $data['book_type'] = $this->input->post('book_type_value');
        }elseif($this->input->post('package_details') == 2){
            $data['book_cover_sc'] = $this->input->post('book_cover_c');
            $data['paper_type_sc'] = $this->input->post('paper_type_c');
            $data['book_size_sc'] = $this->input->post('book_size_c');
            $data['lamination_sc'] = $this->input->post('lamination_c');
            $data['complimentry_copies'] = $this->input->post('complimentry_copies');
            $data['number_of_pages'] = $this->input->post('increment');
            $data['package_name'] = $this->input->post('package_name');
            $data['book_type'] = $this->input->post('book_type_value');
            $data['cost_of_additional_copy'] = $this->input->post('cost_of_additional');
            $data['gross_amt'] = $this->input->post('additional_gross_amount');
            $data['create_p_offer']= $this->input->post('create_p_offer');
            $data['color_pages'] = $this->input->post('color_pages_customized');
            $data['additional_author_copy'] = $this->input->post('additional_author_copy_customized');
        }else{

            $data['book_cover_sc'] = $this->input->post('book_cover_sc');
            $data['paper_type_sc'] = $this->input->post('paper_type_sc');
            $data['book_size_sc'] = $this->input->post('book_size_sc');
            $data['lamination_sc'] = $this->input->post('lamination_sc');

            $data['complimentry_copies'] = $number_of_complimentary_copies;
            $data['number_of_pages'] = $this->input->post('number_of_pages_for_sc');

            $data['package_name'] = $this->input->post('package_name_data_value');
            $data['book_type'] = $this->input->post('book_type');
            $data['cost_of_additional_copy'] = $this->input->post('cost_of_additional');
            $data['gross_amt'] = $this->input->post('additional_gross_amount');
            $data['create_p_offer']= $this->input->post('create_p_offer');
            $data['color_pages'] = $this->input->post('color_pages');
            $data['additional_author_copy'] = $this->input->post('additional_author_copies_number');
        } 
     
        $data['package_value'] = $this->input->post('package_value');
        $data['discount'] = $this->input->post('discount');
        $data['less_pkg_value'] = $this->input->post('less_package_value');
        $data['gst'] = $this->input->post('gst');
        $data['total_amount'] = $this->input->post('total_amount');
        $data['msstatus'] = $this->input->post('msstatus');

        if($this->input->post('package_details') == 3){
        $data['sub_services'] = implode(", ",$this->input->post('sub_services'));
        foreach ($this->input->post('sub_services') as $key => $value) {
                $service_get = $this->db->get_where('tblpackagesubservices',array('id'=>$value))->row();
                $cart[] = $service_get->serviceid;  

        }
        for ($i=0; $i < count($cart); $i++) { 

            if ($cart[$i] != $cart[$i+1]) {
                $service_array[] = $cart[$i];  
            }
        }
        $data['service'] = implode(", ",$service_array);


    }else{
        $data['service'] = implode(", ",$this->input->post('services'));
        $data['sub_services'] = implode(", ",$this->input->post('sub_services'));
    }
    
        $html = $this->load->view('admin/pdf/pdf', $data, true);


        $dompdf = new Dompdf\DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->set_paper('A4', 'landscape');
        $output = $dompdf->output();
        $filepath = 'assets/authorMail/'.$filename;
        
        file_put_contents($filepath, $output);
        
        
        if($this->input->post('package_details')==1){
                $services = implode(", ",$this->input->post('services'));
                
                $explodedStr = explode(", ", $services);

            $filteredArray = array_filter( $explodedStr );

// Convert Array into String with comma delimiter 
$servicess = implode(", ",  $filteredArray);
            }else if($this->input->post('package_details')==2){
                $servicess = implode(", ",$this->input->post('services'));
            }else if($this->input->post('package_details')==3) {
            $servicess = $data['service'];
            }

        if($this->input->post('package_details') == 1 ){
            if ($this->input->post('book_type_value') == 'ebook') {
                 $lead_book_pages = 100;
            }else{
                if ($this->input->post('package_name_data') == 'essential') {
               $lead_book_pages = 100;
            }else if ($this->input->post('package_name_data') == 'regular') {
               $lead_book_pages = 150;
            }else  if ($this->input->post('package_name_data') == 'superior') {
              $lead_book_pages = 250;
            }else  if ($this->input->post('package_name_data') == 'premium') {
              $lead_book_pages = 400;
            }else  if ($this->input->post('package_name_data') == 'rapid') {
               $lead_book_pages = 400;
            }else{
                 $lead_book_pages = 400;
            }
            }
               
           $data = array(
            'craete_package' => 1,
            'lead_author_name' => $name,
            'assigned' => $this->input->post('assigned_by_id'),
            'phonenumber' => $this->input->post('mobile'),
            'email' => $email,
            'lead_author_msstatus' => $this->input->post('msstatus'), 
            'lead_package_detail' => $this->input->post('package_details'),
            'lead_book_type' => $this->input->post('book_type_value'),
            'lead_package_name' => $this->input->post('package_name_data'), 
            
            'lead_service' => $servicess,//
            
            'lead_sub_service' => implode(", ",$this->input->post('sub_services')),//
            
            'lead_packge_value' => $this->input->post('package_value'),
            'lead_packge_discount' => $this->input->post('discount'),
            'lead_lesspckg_value' => $this->input->post('less_package_value'),
            'lead_packg_gst' => $this->input->post('gst'),
            'lead_packg_totalamount' => $this->input->post('total_amount'),
            'lead_book_pages' => $lead_book_pages,
            'create_p_offer'=> $this->input->post('create_p_offer'),
            'lead_pdf_data' => $filename
        );
        }elseif($this->input->post('package_details') == 2){
           $data = array(
            'craete_package' => 1,
            'lead_author_name' => $name,
            'assigned' => $this->input->post('assigned_by_id'),
            'phonenumber' => $this->input->post('mobile'),
            'email' => $email,
            'lead_author_msstatus' => $this->input->post('msstatus'), 
            'lead_package_detail' => $this->input->post('package_details'),

            'lead_book_type' => $this->input->post('book_type_value'),
            'lead_package_name' => $this->input->post('package_name'), 
            
            'lead_service' => $servicess,//
            'lead_ori_packge_value' => $this->input->post('lead_ori_packge_value'),
            'lead_sub_service' => implode(", ",$this->input->post('sub_services')),//
            'complimentry_copies' => $this->input->post('complimentry_copies'),
            'book_cover_sc' => $this->input->post('book_cover_c'),
            'paper_type_sc' => $this->input->post('paper_type_c'),
            'book_size_sc' => $this->input->post('book_size_c'),
            'lamination_sc' => $this->input->post('lamination_c'),
            'lead_packge_value' => $this->input->post('package_value'),
            'lead_packge_discount' => $this->input->post('discount'),
            'lead_lesspckg_value' => $this->input->post('less_package_value'),
            'lead_packg_gst' => $this->input->post('gst'),
            'lead_packg_totalamount' => $this->input->post('total_amount'),
            'lead_book_pages' => $no_of_page,
            'lead_pdf_data' => $filename,
            'cost_of_additional_copy' => $this->input->post('cost_of_additional'),
            'gross_amt' => $this->input->post('additional_gross_amount'),
            'create_p_offer'=> $this->input->post('create_p_offer'),
            'color_pages'=> $this->input->post('color_pages_customized'),
         'additional_author_copy' => $this->input->post('additional_author_copy_customized'),
        );
        }else{
           $data = array(
            'craete_package' => 1,
            'lead_author_name' => $name,
            'assigned' => $this->input->post('assigned_by_id'),
            'phonenumber' => $this->input->post('mobile'),
            'email' => $email,
            'lead_author_msstatus' => $this->input->post('msstatus'), 

            'lead_package_detail' => $this->input->post('package_details'),
            'lead_ori_packge_value' => $this->input->post('lead_ori_packge_value'),
            'lead_book_type' => $this->input->post('book_type'),
            'lead_package_name' => $this->input->post('package_name_data_value'), 
            
            'lead_service' => $servicess,
            
            'lead_sub_service' => implode(", ",$this->input->post('sub_services')),//
            //'lead_book_pages' => implode(", ",$this->input->post('pages')),//
            'complimentry_copies' => $number_of_complimentary_copies,
            'book_cover_sc' => $this->input->post('book_cover_sc'),
            'paper_type_sc' => $this->input->post('paper_type_sc'),
            'book_size_sc' => $this->input->post('book_size_sc'),
            'lamination_sc' => $this->input->post('lamination_sc'),
            'lead_packge_value' => $this->input->post('package_value'),
            'lead_packge_discount' => $this->input->post('discount'),
            'lead_lesspckg_value' => $this->input->post('less_package_value'),
            'lead_packg_gst' => $this->input->post('gst'),
            'lead_packg_totalamount' => $this->input->post('total_amount'),
            'lead_book_pages' => $this->input->post('number_of_pages_for_sc'),//
            'lead_pdf_data' => $filename,
            'cost_of_additional_copy' => $this->input->post('cost_of_additional'),
            'gross_amt' => $this->input->post('additional_gross_amount'),
            'create_p_offer'=> $this->input->post('create_p_offer'),
            'color_pages'=> $this->input->post('color_pages'),
            'additional_author_copy' => $this->input->post('additional_author_copies_number'),
        );
        } 
        
        //  print_r($data);die;
        // if($craete_package == "0"){
        //  $result = $this->leads_model->saveLeadData($data);
        //  echo $this->db->last_query();die;
        //  set_alert('success', _l('Package has been created successfully.'));
        //  redirect('admin/Leads/viewassignedleads2');
        //  //redirect($_SERVER['HTTP_REFERER']);
        // }else{
        // echo $authorId;
        // print_r($data); 
            $result = $this->leads_model->updtaeLeadData($authorId, $data);
            //echo $this->db->last_query();die;
            // echo $url;die;

            set_alert('success', _l('Package has been updated successfully.'));
            //redirect($_SERVER['HTTP_REFERER']);
//          redirect($url);
            //   echo ("<script LANGUAGE='JavaScript'> window.location.href='".$url."';
            //                 </script>");
         redirect('admin/Leads/assignedleads_array');
        //}
    }
        public function getPackageData(){
        $authorId = $_POST['author_id'];
        $data['package_data'] = $this->leads_model->getlead_create_Data($authorId);
        $services = $data['package_data']->lead_service;
        $sub_services = $data['package_data']->lead_sub_service;
        $data['services'] = $this->leads_model->getPackageService($services);
        $data['sub_services'] = $this->leads_model->getPackageSubService($sub_services);
        $this->load->view('admin/leads/popupdata', $data);
    }
    public function get_acquisition_d(){
        // print_r($_POST);
            $authorId = $_POST['author_id'];
            $author_m_id = $_POST['author_m_id'];
            if($author_m_id){
                $data['package_data'] = $this->db->select('email,phonenumber')->get_where('tblleads',array('id'=>$authorId))->row();
                $data['package_data'] = $this->leads_model->getlead_multi_create_Data($authorId);
            
            }else{
                $data['package_data'] = $this->leads_model->getlead_create_Data($authorId);
            }
       
    //  print_r($data['package_data']);
        $services = $data['package_data']->lead_service;
        $sub_services = $data['package_data']->lead_sub_service;
        $data['services'] = $this->leads_model->getPackageService($services);
        $data['sub_services'] = $this->leads_model->getPackageSubService($sub_services);
        // print_r($data);die;
        $this->load->view('admin/leads/acquisition_full_d', $data);
    }
    public function addLeadSubmit()
    {
            $start = $this->input->post('start');
            $end = $this->input->post('end');

            $value=array('lead_reviewstatus'=>1);
            //$this->db->where('id',$id);
            $this->db->where("id BETWEEN '$start' AND '$end'");
            $check = $this->db->update('tblleads',$value);
            
            /*$var = $this->db->last_query();
            echo $var;
            die;*/
            if($check){
              echo "YES";  
            }else{
                 echo "NO"; 
            }
    }
    public function allleadremark_model()
    {
        $lead_idnew = $_POST['id'];
        $lead_name = $_POST['name'];
        $data['lead_id'] = $_POST['id'];
        $lead_status = $_POST['status'];
        $lead_description = $_POST['description'];
        $this->db->order_by("created_on", "asc");
        $this->db->where('lead_id', $lead_idnew);
       // $this->db->group_by("remark");
        $data['allleadremark'] = $this->db->get(' tblleadremark')->result();
        $data['lead_id'] = $lead_idnew;
        $data['name'] = $lead_name;
        $data['status'] = $lead_status;
        $data['assigned'] = $_POST['assigned'];
        $data['next_calling'] = $_POST['next_calling'];
        $data['publishedEarlier'] = $_POST['publishedEarlier'];
        
        $data['booktitle'] = $_POST['booktitle'];
        $data['book_format'] = $_POST['book_format'];
        $data['email'] = $_POST['email'];
        $data['otherphonenumber'] = $_POST['otherphonenumber'];
        $data['lstatus'] = $this->leads_model->get_leadstatus('tblleadsstatus');
    
        
        $this->db->select("*");
        $this->db->from("tblleads");
        $this->db->where('id', $lead_idnew);
        $data['aquired_data'] = $this->db->get()->row();
        $data['description'] = $lead_description;
        $data['wplistlead'] = $this->leads_model->get_availwplead();
      
        $this->load->view('admin/leads/viewremark', $data); 
        
    }
    public function update_custom_lead_remark_update()
    {
      
         $id = $_POST['id'];
        // print_r($_FILES);exit();  
            $filename = $_FILES['file']['name'];
            echo $filename; exit;
            $ckeck_image = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
              
                if($ckeck_image->lead_payment_reciept){
                    unlink('assets/images/payment_receipt/'.$ckeck_image->lead_payment_reciept);
                }
            }
                
            $location = "assets/images/payment_receipt/".$filename;
            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);

            /* Valid extensions */
            //$valid_extensions = array("jpg","jpeg","png","");
            $response = 0;
            //if(in_array(strtolower($imageFileType), $valid_extensions)) {
            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
             $response = $location;
            }
          //  }
            
            
   
        
        $name = $_POST['name'];
        $booktitle = $_POST['booktitle'];
        $otherphonenumber = $_POST['otherphonenumber'];
        $PublishedEarlier = $_POST['publishedEarlier'];
        $full_name = $_POST['full_name'];
        $phonenumber = $_POST['phonenumber'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $designation = $_POST['designation'];
        $company = $_POST['company'];
        $address = $_POST['address'];
        $data_source = $_POST['data_source'];
        $adset_name = $_POST['data_source'];
        $calling_objective = $_POST['calling_objective'];
        $added_by = $_POST['added_by'];
        $remark = $_POST['description'];
        $categorisation = $_POST['status'];
        $status = $_POST['status'];
        $meetingtimeto = $_POST['meetingtimeto'];
        $meetingtimefrom = $_POST['meetingtimefrom'];
        $status = $_POST['status'];
        $reassignlead = $_POST['reassignlead'];
        $next_calling = $_POST['nextcalling'];
        $reminder = $_POST['reminder'];
        $assigned = $_POST['assigned'];
        $book_format = $_POST['book_format'];
        
        $package_cost = $_POST['package_cost'];
        $booking_amount = $_POST['booking_amount'];
        $finstallment = $_POST['finstallment'];
        $final_payment = $_POST['final_payment'];
        $gst_number = $_POST['gst_number'];
    

        // if(!empty($filename)){
            $aquiredData = array(
        "lead_package_cost"=>$package_cost,
        "lead_booking_amount"=>$booking_amount,
        "lead_first_installment"=>$finstallment,
        "lead_final_payment"=>$final_payment,
        "lead_gst_number"=>$gst_number,
        "lead_payment_reciept"=>$filename
        );
        // }else{
        //         $aquiredData = array(
        // "lead_package_cost"=>$package_cost,
        // "lead_booking_amount"=>$booking_amount,
        // "lead_first_installment"=>$finstallment,
        // "lead_final_payment"=>$final_payment,
        // "lead_gst_number"=>$gst_number
        // );
        // }
        if($status == "39"){
            $aquiredData['status']='1';
            $aquiredData['lead_status']='1';
        }
        $this->db->where('id', $id);
        $result = $this->db->update('tblleads',$aquiredData);
        
        
        $this->db->select('assigned');
        $this->db->where('id',$id);
        $res1 = $this->db->get('tblleads')->row();
        $mainassigned = $res1->assigned;
        if($remark != ''){
           $calling_date = date("d/m/Y"); 
        }else{
           $calling_date = ''; 
        }
        
        /*if(isset($_GET['reminder']))
        {
            $reminder=1;
        }
        else
        {
            $reminder=0;
        }*/
        
        $date = date('Y-m-d');
        $acquired_date = date('Y-m-d h:i:s');
        
        if($remark != null ) {
        $data = array('lead_bookformat'=>$book_format,'lead_callingdate'=>$date,'lead_publishedearlier'=>$PublishedEarlier,'lead_booktitle'=>$booktitle,'otherphonenumber'=>$otherphonenumber,'lead_author_name' => $name, 'lead_category_id' => $status, 'phonenumber' => $phonenumber, 'email' => $email, 'designation' => $designation, 'company' => $company, 'address' => $address, 'data_source' => $data_source,  'calling_objective' => $calling_objective, 'description' => $remark, 'meetingtimefrom' => $meetingtimefrom, 'meetingtimeto' => $meetingtimeto, 'next_calling' => $next_calling, 'lastcontact' => $date, 'assigned' => $assigned,'lead_acquired_date'=> $acquired_date);  
        }
        else
        {
          
        $data = array('lead_bookformat'=>$book_format,'lead_callingdate'=>$date,'lead_publishedearlier'=>$PublishedEarlier,'lead_booktitle'=>$booktitle,'otherphonenumber'=>$otherphonenumber,'lead_author_name' => $name,'lead_category_id' => $status, 'phonenumber' => $phonenumber, 'email' => $email, 'designation' => $designation, 'company' => $company, 'address' => $address, 'data_source' => $data_source, 'calling_objective' => $calling_objective, 'description' => $remark,'meetingtimefrom' => $meetingtimefrom, 'meetingtimeto' => $meetingtimeto, 'next_calling' => $next_calling,  'lastcontact' => $date, 'assigned' => $assigned, 'lead_acquired_date'=> $acquired_date);
        }
        
        //print_r($data);exit;

        if((isset($_POST['reminder'])) && ($reminder == '1') && ($next_calling != '0000-00-00 00:00:00'))
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
        //echo $this->db->last_query();die;
        if($remark != null) {
        $data2 = array('lead_id' => $id, 'remark' => $remark, 'added_by' => $added_by);
        $this->db->insert('tblleadremark', $data2);
        }
        if($status == 1)
        {
            $this->db->select('*');
            $this->db->where('lead_id',$id);
            $checkwp_assigned = $this->db->get('tblmeeting_scheduled')->row();
            $wp_id = $checkwp_assigned->assigned;
            if($wp_id == null)
            {
                $lead_id = $id;
                $staff_id = $reassignlead;
                $this->leads_model->reassign($lead_id, $staff_id,$mainassigned); 
            }
            else
            {
                if(!$reassignlead)
                {
                    $reassignlead = '0';
                }
                $insertUpdate['assigned'] = $reassignlead;
                $insertUpdate['assigned_by'] = $mainassigned;
                $this->db->update('tblmeeting_scheduled', $insertUpdate, array('lead_id' => $id));
            }
            
        }    
        
        
        
        
        $insertUpdate['assigned'] = $mainassigned;
        $this->db->update('tblleads', $insertUpdate, array('id' => $id));
        $data['success'] = "success";
        set_alert('success', "Lead Updated Successfully");
        return $data;
    }
    public function getserviesforedit(){
        
    
       $services = $_POST['service'];
       $book_type = $_POST['book_type'];
     
        $sub_services = $_POST['sub_service'];
        $service = explode(", ", $services);
        $sub_services = explode(", ", $sub_services);
        
    
        $service = array_filter($service); 
        $result = $this->leads_model->getserviesedit($service);
        
        $html .= "<ul class='dynamicservice'>";
            foreach($result as $data){
                
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                $html .= "<li style='font-weight:bold;'> <input type='checkbox' name='services[]' class='servicesstand' value='".$data->id."' checked onclick='return false;' data-name='".$data->service_name."' > ". $data->service_name."</li>";
                $html .= "<ul>";
                    $subservices = $this->leads_model->sub_servicesedit_data($data->id, $sub_services , $book_type);
                    //print_r($subservices);
                        
                    foreach($subservices as $subservice)
                    
                    {
                    
                    $html .= "<li><input type='checkbox' name='sub_services[]' value='".$subservice->id."' class='subservicesstand' checked onclick='return false;' data-name='".$subservice->subservice_name."' > ".$subservice->subservice_name."</li>";
                    $servicecost = $subservice->cost;
                    }
                $html .= "</ul>";
                
            }
            $html .= "</ul>";
        echo $html;
        //print_r($html);
    }

    public function getservies_sc1(){
      
            $book_type = $_POST['book_type'];
            $package = $_POST['package'];
            $package_details = $_POST['package_details'];
            $result = $this->leads_model->getservies_coustize_standard($package, $book_type,$package_details);
          
            $services = array();
            $html .= "<ul class='dynamicservice'>";
            foreach($result as $data){
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                $html .= "<li style='font-weight:bold;'><input type='checkbox' checked style='display:none' name='services[]' class='servicesstand' value='".$data['serviceid']."'  data-name='".$data['service_name']."' data-id='".$data['serviceid']."' > ".$data['service_name']."</li>";
                $html .= "<ul>";
                    $subservices = $this->cus_stand_sub_services($book_type, $package, $data['serviceid']);
                    if($subservices[1]->id){
                        if ($data['service_name'] == 'Editing Services') {
                    $html .=    "<label>No of pages:</label><input type='number' onkeyup='myFunctiondata(".$subservices[0]->id.",".$subservices[0]->cost.",".$subservices[1]->id.",".$subservices[1]->cost.");' value='0' id='number_of_pages_for_sc' name='number_of_pages_for_sc'>";
                        }
                    }else{
                        if($data['service_name'] == 'Editing Services'){
                             $html .=   "<label>No of pages:</label><input type='number' onkeyup='myFunctiondata(".$subservices[0]->id.",".$subservices[0]->cost.");' value='0' id='number_of_pages_for_sc' name='number_of_pages_for_sc'>";
                            }
                        }

                  
                    foreach($subservices as $subservice){

                        if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                        $number_of_copies = $subservice->cost/100;
                    $html .=    "<br><label>No of Copies</label><input type='number' onkeyup='myFunctiondatacopies(".$subservice->id.",".$subservice->cost.");' value='".$number_of_copies."' min='5' id='number_of_complimentary_copies' name='number_of_complimentary_copies' readonly>";
                        }
                        
                    $html .= "<li >";
                        if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                    $html .= "<input type='checkbox' checked='' onclick='return false;' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                }else{
                     $html .= "<input type='checkbox' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                }
                    if ($subservice->subservice_name == 'Book Size') { 

                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_size_sc' name='book_size_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Lamination') { 
                          $Lamination = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='lamination_sc' name='lamination_sc'><option>---select----</option>";
                     foreach ($Lamination as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Paper Type') { 
                          $paper_back = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='paper_type_sc' name='paper_type_sc'><option>---select----</option>";
                     foreach ($paper_back as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     
                     $html .= "</select>";
                   }

                     if ($subservice->subservice_name == 'Book Cover') { 

                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_cover_sc' name='book_cover_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     
                     $html .= "</select>";
                   }
                  
                   if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                  
                }else{
                    if ($subservice->subservice_name == 'Number of Pages Allowed' || $subservice->subservice_name == 'Number of Pages Allowed - 400') {
                    $html .=    "<input type='number' disabled  value='100' min='5' id='number_of_pages_allowed' name='number_of_pages_allowed'>";
                    $html .= "<input type='hidden' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>";
                    $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'ISBN Allocation') {
                      $html .= "<input type='text' id='sub_service_cost_sc".$subservice->id."' disabled value='0'</li>"; $servicecost = $subservice->cost;
                        }else{
                           $html .= "<input type='text' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                        }
                     
                      
                    }
                }
                $html .= "</ul>";

                 
            }?>
          
            <?php
            $html .= "</ul>";
            $data['html'] = $html;
            $data['pkgvalue'] = $servicecost; 
            $data['gst'] = ($servicecost*18)/100; 
            $data['totalamt'] = ($servicecost+$data['gst']); 
            echo json_encode($data);exit;
        
        
    }
      public function getservies_sc(){
            $book_type = $_POST['book_type'];
            $package = $_POST['package'];
            $package_details = $_POST['package_details'];
            $result = $this->leads_model->getservies_coustize_standard($package, $book_type,$package_details);
            $services = array();
            $html .= "<ul class='dynamicservice'>";
            foreach($result as $data){
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                $html .= "<li style='font-weight:bold;'><input type='checkbox' checked style='display:none' name='services[]' class='servicesstand' value='".$data['serviceid']."'  data-name='".$data['service_name']."' data-id='".$data['serviceid']."' > ".$data['service_name']."</li>";
                $html .= "<ul>";
                    $subservices = $this->cus_stand_sub_services($book_type, $package, $data['serviceid']);
                    if($subservices[1]->id){
                        if ($data['service_name'] == 'Editing Services') {
                    $html .=    "<label>No of pages:</label><input type='number' onkeyup='myFunctiondata(".$subservices[0]->id.",".$subservices[0]->cost.",".$subservices[1]->id.",".$subservices[1]->cost.");' value='0' id='number_of_pages_for_sc'  name='number_of_pages_for_sc'>";
                        }
                    }else{
                        if($data['service_name'] == 'Editing Services'){
                             $html .=   "<label>No of pages:</label><input type='number' onkeyup='myFunctiondata(".$subservices[0]->id.",".$subservices[0]->cost.");' value='0' id='number_of_pages_for_sc'  name='number_of_pages_for_sc'>";
                            }
                        }
                    foreach($subservices as $subservice){
                        if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                        $number_of_copies = $subservice->cost/100;
                    $html .=    "<br><label>No of Copies</label><input type='number' onkeyup='myFunctiondatacopies(".$subservice->id.",".$subservice->cost.");' value='".$number_of_copies."' min='5' id='number_of_complimentary_copies' readonly name='number_of_complimentary_copies'>";
                        }
                    $html .= "<li >";
                        if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                        $html .= "<input type='checkbox' checked='' onclick='return false;' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }else if ($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price') {
                             $html .= "<input type='checkbox' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc additional_author_copy' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }else if($subservice->subservice_name == 'Color Pages'){
                             $html .= "<input type='checkbox' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc color_pages' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }else{
                            $html .= "<input type='checkbox' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc ' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }
                    if ($subservice->subservice_name == 'Book Size') { 
                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_size_sc'  disabled name='book_size_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Lamination') { 
                          $Lamination = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='lamination_sc' name='lamination_sc'><option>---select----</option>";
                     foreach ($Lamination as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Paper Type') { 
                          $paper_back = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='paper_type_sc'  name='paper_type_sc'><option>---select----</option>";
                     foreach ($paper_back as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                     if ($subservice->subservice_name == 'Book Cover') { 
                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_cover_sc' disabled name='book_cover_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                }else{ 
                    if ($subservice->subservice_name == 'Number of Pages Allowed' || $subservice->subservice_name == 'Number of Pages Allowed - 400') {
                    $html .=    "<input type='number' disabled  value='100' min='5' id='number_of_pages_allowed' name='number_of_pages_allowed'>";
                    $html .= "<input type='hidden' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>";
                    $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'ISBN Allocation') {
                      $html .= "<input type='text' id='sub_service_cost_sc".$subservice->id."' disabled value='0'</li>"; $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price') {
                      $html .= "<input type='text' id='sub_service_cost_sc".$subservice->id."' disabled value='0'>&nbsp; &nbsp; &nbsp;<input type='number' id='additional_author_copies_number' name='additional_author_copies_number'   style='display:none;'></li>"; $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'English to Hindi translation (250-300 words in a page)') {
                            $html .= "<input type='text' class='form-control english_hindi_translation' data-id='".$subservice->id."' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                              }else if ($subservice->subservice_name == 'Hindi to English translation (250-300 words in a page)') {
                                $html .= "<input type='text' class='form-control hindi_english_translation' data-id='".$subservice->id."' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                                  }else if ($subservice->subservice_name == 'Hindi Typing') {
                                    $html .= "<input type='text' class='form-control hindi_typing' data-id='".$subservice->id."' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                                      }else if ($subservice->subservice_name == 'English Typing') {
                                    $html .= "<input type='text' class='form-control english_typing' data-id='".$subservice->id."' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                                      }else if ($subservice->subservice_name == 'Urdu Typing') {
                                        $html .= "<input type='text' class='form-control urdu_typing' data-id='".$subservice->id."' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                                          }else if($subservice->subservice_name == 'Color Pages'){
                                    $html .= "<input type='text' class='' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;<input type='number' id='color_pages_number' name='color_pages'   style='display:none;'></li>"; $servicecost = $subservice->cost;
                                  }else{
                                    $html .= "<input type='text' class='form-control' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                        }
                    }
                }
                $html .= "</ul>";
            }?>
            <?php
            $html .= "</ul>";
            $data['html'] = $html;
            $data['pkgvalue'] = $servicecost; 
            $data['gst'] = ($servicecost*18)/100; 
            $data['totalamt'] = ($servicecost+$data['gst']); 
            echo json_encode($data);exit;
    }
    public function getservies_sc123(){
            $book_type = $_POST['book_type'];
            $package = $_POST['package'];
            $package_details = $_POST['package_details'];
            $result = $this->leads_model->getservies_coustize_standard($package, $book_type,$package_details);
            $services = array();
            $html .= "<ul class='dynamicservice'>";
            foreach($result as $data){
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                $html .= "<li style='font-weight:bold;'><input type='checkbox' checked style='display:none' name='services[]' class='servicesstand' value='".$data['serviceid']."'  data-name='".$data['service_name']."' data-id='".$data['serviceid']."' > ".$data['service_name']."</li>";
                $html .= "<ul>";
                    $subservices = $this->cus_stand_sub_services($book_type, $package, $data['serviceid']);
                    if($subservices[1]->id){
                        if ($data['service_name'] == 'Editing Services') {
                    $html .=    "<label>No of pages:</label><input type='number' onkeyup='myFunctiondata(".$subservices[0]->id.",".$subservices[0]->cost.",".$subservices[1]->id.",".$subservices[1]->cost.");' value='0' id='number_of_pages_for_sc'  name='number_of_pages_for_sc'>";
                        }
                    }else{
                        if($data['service_name'] == 'Editing Services'){
                             $html .=   "<label>No of pages:</label><input type='number' onkeyup='myFunctiondata(".$subservices[0]->id.",".$subservices[0]->cost.");' value='0' id='number_of_pages_for_sc'  name='number_of_pages_for_sc'>";
                            }
                        }
                    foreach($subservices as $subservice){
                        if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                        $number_of_copies = $subservice->cost/100;
                    $html .=    "<br><label>No of Copies</label><input type='number' class='form-control' onkeyup='myFunctiondatacopies(".$subservice->id.",".$subservice->cost.");' value='".$number_of_copies."' min='5' id='number_of_complimentary_copies'  name='number_of_complimentary_copies' readonly>";
                        }
                    $html .= "<li >";
                        if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                        $html .= "<input type='checkbox' checked='' onclick='return false;' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }else if ($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price') {
                             $html .= "<input type='checkbox' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc additional_author_copy' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }else{
                             $html .= "<input type='checkbox' name='sub_services[]' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");' data-service='".$data['service_name']."' data-name='".$subservice->subservice_name."' data-id='".$subservice->id."' data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }
                    if ($subservice->subservice_name == 'Book Size') { 
                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_size_sc'  disabled name='book_size_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Lamination') { 
                          $Lamination = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='lamination_sc' name='lamination_sc'><option>---select----</option>";
                     foreach ($Lamination as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Paper Type') { 
                          $paper_back = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='paper_type_sc'  name='paper_type_sc'><option>---select----</option>";
                     foreach ($paper_back as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                     if ($subservice->subservice_name == 'Book Cover') { 
                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_cover_sc' disabled name='book_cover_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == 'Complimentary Author Copies - 10') {
                }else{ 
                    if ($subservice->subservice_name == 'Number of Pages Allowed' || $subservice->subservice_name == 'Number of Pages Allowed - 400') {
                    $html .=    "<input type='number' disabled  value='100' min='5' id='number_of_pages_allowed' name='number_of_pages_allowed'>";
                    $html .= "<input type='hidden' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>";
                    $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'ISBN Allocation') {
                      $html .= "<input type='text' id='sub_service_cost_sc".$subservice->id."' disabled value='0'</li>"; $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price') {
                      $html .= "<input type='text' id='sub_service_cost_sc".$subservice->id."' disabled value='0'>&nbsp; &nbsp; &nbsp;<input type='number' id='additional_author_copies_number' name='additional_author_copies_number'   style='display:none;'></li>"; $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'English to Hindi translation (250-300 words in a page)') {
                            $html .= "<input type='text' class='form-control english_hindi_translation' data-id='".$subservice->id."' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                              }else if ($subservice->subservice_name == 'Hindi to English translation (250-300 words in a page)') {
                                $html .= "<input type='text' class='form-control hindi_english_translation' data-id='".$subservice->id."' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                                  }else{
                           $html .= "<input type='text' class='form-control' id='sub_service_cost_sc".$subservice->id."' disabled value='".$subservice->cost."'</li>"; $servicecost = $subservice->cost;
                        }
                    }
                }
                $html .= "</ul>";
            }?>
            <?php
            $html .= "</ul>";
            $data['html'] = $html;
            $data['pkgvalue'] = $servicecost; 
            $data['gst'] = ($servicecost*18)/100; 
            $data['totalamt'] = ($servicecost+$data['gst']); 
            echo json_encode($data);exit;
    }
    function cus_stand_sub_services($book_type,$packages,$serviceid){
        $this->db->select('*');
        $this->db->from('tblpackagesubservices');
        $this->db->where('book_type', $book_type); 
        $this->db->where('package_value', $packages); 
        $this->db->where('serviceid', $serviceid); 
        $result = $this->db->get(); 
        return $result->result();
    }
    public function getservies_sc_for_edit1($value='')
    {
         
       $complimentry_copies = $_POST['complimentry_copies'];
       $book_cover_sc_data = $_POST['book_cover_sc'];
       $book_size_sc = $_POST['book_size_sc'];
       $paper_type_sc = $_POST['paper_type_sc'];
       $lamination_sc = $_POST['lamination_sc'];
       $book_type = $_POST['book_type'];
       $lead_book_pages = $_POST['lead_book_pages'];
       $package_details = $_POST['package_details'];
       $package = $_POST['lead_package_name'];
     
        $sub_services = $_POST['sub_service'];
        $service = explode(", ", $services);

        $sub_services = explode(", ", $sub_services);

         $result = $this->leads_model->getservies_coustize_standard($package, $book_type,$package_details);
          
            $services = array();
            $html .= "<ul class='dynamicservice'>";
            //$i = 0;
            foreach($result as $data){
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                $html .= "<li style='font-weight:bold;'><input type='checkbox' checked style='display:none' name='services[]' class='servicesstand' value='".$data['serviceid']."'  data-name='".$data['service_name']."' data-id='".$data['serviceid']."' > ".$data['service_name']."</li>";
                $html .= "<ul>";
                    $subservices = $this->cus_stand_sub_services($book_type, $package, $data['serviceid']);
                if ($data['service_name'] == 'Editing Services') {
                    $service_cost = $subservices[0]->cost/100;
                    $total_pages_sc = 11*$lead_book_pages;
                    $service_cost1 = $subservices[1]->cost/100;
                    $total_pages_sc1 = 11*$lead_book_pages;
                     if($subservices[1]->id){
                        if ($data['service_name'] == 'Editing Services') {
                    
                            $html .=    "<label>No of pagess:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$total_pages_sc.");' value='".$lead_book_pages."' id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                        }
                    }else{
                        if($data['service_name'] == 'Editing Services'){
                            
                             $html .=   "<label>No of pagesss:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$total_pages_sc.");' value='".$lead_book_pages."' id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                            }
                        }
                    // $html .= "<label>No of pages:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$subservices[1]->id.",".$subservices[1]->cost.",".$total_pages_sc.",".$total_pages_sc1.");' value='".$lead_book_pages."' min='100' id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                        }
                  
                    foreach($subservices as $subservice){
                        if ($subservice->subservice_name == 'Complimentary Author Copies') {
                        
                            $number_of_copies = $subservice->cost/100;
                            $single_copy_cost = $subservice->cost/$number_of_copies;
                            $number_of_copy_cost = $single_copy_cost*$complimentry_copies;

                    $html .="<br><label>No of Copies</label><input type='number' onkeyup='myFunctioneditcopies(".$subservice->id.",".$subservice->cost.",".$number_of_copy_cost.");' value='".$complimentry_copies."' min='5' id='number_of_complimentary_copies_edit' name='number_of_complimentary_copies_edit' readonly>";
                        }
                      
                    $html .= "<li><input type='checkbox' name='sub_services[]'";  
                    // if ($subservice->id == $sub_services[$i]) {

                    if (in_array($subservice->id, $sub_services)) {
                        $html .= "checked";
                    }
                    if ($subservices[0]->id==$subservice->id && $data['service_name']=='Editing Services') {
                        $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$total_pages_sc.");'   data-cost='".$total_pages_sc."' > ".$subservice->subservice_name."<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$total_pages_sc."'</li>";
                    $servicecost = $total_pages_sc;
                    }else if($subservices[1]->id==$subservice->id && $data['service_name']=='Editing Services'){
                        $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$total_pages_sc1.");'   data-cost='".$total_pages_sc1."' > ".$subservice->subservice_name."<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$total_pages_sc1."'</li>";
                    $servicecost = $subservice->cost;
                    }else{
                        if ($subservice->subservice_name == 'Complimentary Author Copies') {
                            $html .= " checked='' onclick='return false;' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."'  onclick='myFunction(".$subservice->id.",".$number_of_copy_cost.");'   data-cost='".$number_of_copy_cost."' > ".$subservice->subservice_name."";
                        }else{
                            $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");'   data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }
                    
                        if ($subservice->subservice_name == 'Book Size') { 

                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_size_sc' name='book_size_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option ";
                       if ($value == $book_size_sc) {
                        $html .=  "selected";
                       }
                       $html .= " value='".$value."'>".$value."</option>";
                     }
                     
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Lamination') { 
                          $Lamination = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='lamination_sc' name='lamination_sc'><option>---select----</option>";
                     foreach ($Lamination as $key => $value) {
                       $html .= "<option ";
                       if ($value == $lamination_sc) {
                          $html .=  "selected";
                       }
                        $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Book Cover') { 
                        $book_cover_sc = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_cover_sc' name='book_cover_sc'><option>---select----</option>";
                     foreach ($book_cover_sc as $key => $value) {
                       $html .= "<option ";
                       if ($value == $book_cover_sc_data) {
                          $html .=  "selected";
                       }
                        $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }

                    if ($subservice->subservice_name == 'Paper Type') { 
                          $paper_back = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='paper_type_sc' name='paper_type_sc'><option>---select----</option>";
                     foreach ($paper_back as $key => $value) {
                       $html .= "<option ";
                       if ($value == $paper_type_sc) {
                          $html .=  "selected";
                       }
                       $html .= " value='".$value."'>".$value."</option>";
                     }
                     
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == ' Complimentary Author Copies - 10') {

                       $html .=  "<input type='hidden' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$number_of_copy_cost."'></li>";
                    // $servicecost = $number_of_copy_cost;
                }else{
                       if ($subservice->subservice_name == 'Number of Pages Allowed' || $subservice->subservice_name == 'Number of Pages Allowed - 400') {
                    $html .=    "<input type='number' disabled  value='".$lead_book_pages."' min='5' id='number_of_pages_allowed' name='number_of_pages_allowed'>";
                      $html .=  "<input type='hidden' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'></li>";
                        }else if ($subservice->subservice_name == 'ISBN Allocation') {
                      $html .= "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='0'</li>";
                        }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'></li>";
                    $servicecost = $subservice->cost;
                        }
                     
                }
                    }
                    
                 // $i++;
              }
                $html .= "</ul>";

                 
              }?>
          
            <?php
            $html .= "</ul>";
            $data['html'] = $html;
            $data['pkgvalue'] = $servicecost; 
            $data['gst'] = ($servicecost*18)/100; 
            $data['totalamt'] = ($servicecost+$data['gst']); 
            echo json_encode($data);exit;
    }
 public function getservies_sc_for_edit123($value='')
    {
       $complimentry_copies = $_POST['complimentry_copies'];
       $book_cover_sc_data = $_POST['book_cover_sc'];
       $book_size_sc = $_POST['book_size_sc'];
       $paper_type_sc = $_POST['paper_type_sc'];
       $lamination_sc = $_POST['lamination_sc'];
       $book_type = $_POST['book_type'];
       $lead_book_pages = $_POST['lead_book_pages'];
       $package_details = $_POST['package_details'];
       $package = $_POST['lead_package_name'];
        $additional_author_copies_number = $_POST['additional_author_copies_number'];
       $additional_gross_amount = $_POST['additional_gross_amount'];
       $cost_of_additional = $_POST['cost_of_additional'];
        $sub_services = $_POST['sub_service'];
        $service = explode(", ", $services);
        $sub_services = explode(", ", $sub_services);
         $result = $this->leads_model->getservies_coustize_standard($package, $book_type,$package_details);
            $services = array();
            $html .= "<ul class='dynamicservice'>";
            //$i = 0;
            foreach($result as $data){
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                $html .= "<li style='font-weight:bold;'><input type='checkbox' checked style='display:none' name='services[]' class='servicesstand' value='".$data['serviceid']."'  data-name='".$data['service_name']."' data-id='".$data['serviceid']."' > ".$data['service_name']."</li>";
                $html .= "<ul>";
                    $subservices = $this->cus_stand_sub_services($book_type, $package, $data['serviceid']);
                if ($data['service_name'] == 'Editing Services') {
                    $service_cost = $subservices[0]->cost/100;
                    $total_pages_sc = 11*$lead_book_pages;
                    $service_cost1 = $subservices[1]->cost/100;
                    $total_pages_sc1 = 11*$lead_book_pages;
                     if($subservices[1]->id){
                        if ($data['service_name'] == 'Editing Services') {
                            $html .=    "<label>No of pagess:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$total_pages_sc.");' value='".$lead_book_pages."'  id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                        }
                    }else{
                        if($data['service_name'] == 'Editing Services'){
                             $html .=   "<label>No of pagesss:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$total_pages_sc.");' value='".$lead_book_pages."'  id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                            }
                        }
                    // $html .= "<label>No of pages:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$subservices[1]->id.",".$subservices[1]->cost.",".$total_pages_sc.",".$total_pages_sc1.");' value='".$lead_book_pages."' min='100' id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                        }
                    foreach($subservices as $subservice){
                        if ($subservice->subservice_name == 'Complimentary Author Copies') {
                            $number_of_copies = $subservice->cost/100;
                            $single_copy_cost = $subservice->cost/$number_of_copies;
                            $number_of_copy_cost = $single_copy_cost*$complimentry_copies;
                    $html .="<br><label>No of Copies</label><input type='number' onkeyup='myFunctioneditcopies(".$subservice->id.",".$subservice->cost.",".$number_of_copy_cost.");' value='".$complimentry_copies."' min='5' id='number_of_complimentary_copies_edit'  name='number_of_complimentary_copies_edit' readonly>";
                        }
                    $html .= "<li><input type='checkbox' name='sub_services[]'";  
                    // if ($subservice->id == $sub_services[$i]) {
                    if (in_array($subservice->id, $sub_services)) {
                        $html .= "checked";
                    }
                    if ($subservices[0]->id==$subservice->id && $data['service_name']=='Editing Services') {
                        $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$total_pages_sc.");'   data-cost='".$total_pages_sc."' > ".$subservice->subservice_name."<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$total_pages_sc."'</li>";
                    $servicecost = $total_pages_sc;
                    }else if($subservices[1]->id==$subservice->id && $data['service_name']=='Editing Services'){
                        $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$total_pages_sc1.");'   data-cost='".$total_pages_sc1."' > ".$subservice->subservice_name."<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$total_pages_sc1."'</li>";
                    $servicecost = $subservice->cost;
                    }else{
                        if ($subservice->subservice_name == 'Complimentary Author Copies') {
                            $html .= " checked='' onclick='return false;' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."'  onclick='myFunction(".$subservice->id.",".$number_of_copy_cost.");'   data-cost='".$number_of_copy_cost."' > ".$subservice->subservice_name."";
                        }else if($subservice->subservice_name == 'English to Hindi translation (250-300 words in a page)'){
                           $english_to_hindi =  $subservice->cost;
                           $english_total = $english_to_hindi*$lead_book_pages;
                            $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$english_total.");'   data-cost='".$english_total."' > ".$subservice->subservice_name."";
                        }else if($subservice->subservice_name == 'Hindi to English translation (250-300 words in a page)'){
                            $hindi_to_english =  $subservice->cost;
                            $hindi_total = $hindi_to_english*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$hindi_total.");'   data-cost='".$hindi_total."' > ".$subservice->subservice_name."";
                         }else{
                            $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");'   data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }
                        if ($subservice->subservice_name == 'Book Size') { 
                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_size_sc' name='book_size_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option ";
                       if ($value == $book_size_sc) {
                        $html .=  "selected";
                       }
                       $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Lamination') { 
                          $Lamination = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='lamination_sc' name='lamination_sc'><option>---select----</option>";
                     foreach ($Lamination as $key => $value) {
                       $html .= "<option ";
                       if ($value == $lamination_sc) {
                          $html .=  "selected";
                       }
                        $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Book Cover') { 
                        $book_cover_sc = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_cover_sc' name='book_cover_sc'><option>---select----</option>";
                     foreach ($book_cover_sc as $key => $value) {
                       $html .= "<option ";
                       if ($value == $book_cover_sc_data) {
                          $html .=  "selected";
                       }
                        $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Paper Type') { 
                          $paper_back = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='paper_type_sc' name='paper_type_sc'><option>---select----</option>";
                     foreach ($paper_back as $key => $value) {
                       $html .= "<option ";
                       if ($value == $paper_type_sc) {
                          $html .=  "selected";
                       }
                       $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == ' Complimentary Author Copies - 10') {
                       $html .=  "<input type='hidden' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$number_of_copy_cost."'></li>";
                    // $servicecost = $number_of_copy_cost;
                }else{
                       if ($subservice->subservice_name == 'Number of Pages Allowed' || $subservice->subservice_name == 'Number of Pages Allowed - 400') {
                    $html .=    "<input type='number' disabled  value='".$lead_book_pages."' min='5' id='number_of_pages_allowed' name='number_of_pages_allowed'>";
                      $html .=  "<input type='hidden' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'></li>";
                        }else if ($subservice->subservice_name == 'ISBN Allocation') {
                      $html .= "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='0'</li>";
                        }else if ($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price') {
                            if ($additional_author_copies_number > 0) {
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;&nbsp;&nbsp;<input type='number' id='additional_author_copies_number' name='additional_author_copies_number' value='".$additional_author_copies_number."'  ></li>";
                    $servicecost = $subservice->cost;
                            }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;&nbsp;&nbsp;<input type='number' id='additional_author_copies_number' name='additional_author_copies_number' style='display:none;' ></li>";
                    $servicecost = $subservice->cost;
                            }
                        }else if($subservice->subservice_name == 'English to Hindi translation (250-300 words in a page)'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$english_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if($subservice->subservice_name == 'Hindi to English translation (250-300 words in a page)'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$hindi_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'></li>";
                    $servicecost = $subservice->cost;
                        }
                }
                    }
                 // $i++;
              }
                $html .= "</ul>";
              }?>
            <?php
            $html .= "</ul>";
            $data['html'] = $html;
            $data['pkgvalue'] = $servicecost; 
            $data['gst'] = ($servicecost*18)/100; 
            $data['totalamt'] = ($servicecost+$data['gst']); 
            echo json_encode($data);exit;
    }
     public function getservies_sc_for_edit_bkp13nov($value='')
    {
       $complimentry_copies = $_POST['complimentry_copies'];
       $color_pages = $_POST['color_pages'];
       $book_cover_sc_data = $_POST['book_cover_sc'];
       $book_size_sc = $_POST['book_size_sc'];
       $paper_type_sc = $_POST['paper_type_sc'];
       $lamination_sc = $_POST['lamination_sc'];
       $book_type = $_POST['book_type'];
       $lead_book_pages = $_POST['lead_book_pages'];
       $package_details = $_POST['package_details'];
       $package = $_POST['lead_package_name'];
        $additional_author_copies_number = $_POST['additional_author_copies_number'];
       $additional_gross_amount = $_POST['additional_gross_amount'];
       $cost_of_additional = $_POST['cost_of_additional'];
        $sub_services = $_POST['sub_service'];
        $service = explode(", ", $services);
        $sub_services = explode(", ", $sub_services);
         $result = $this->leads_model->getservies_coustize_standard($package, $book_type,$package_details);
            $services = array();
            $html .= "<ul class='dynamicservice'>";
            //$i = 0;
            foreach($result as $data){
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                $html .= "<li style='font-weight:bold;'><input type='checkbox' checked style='display:none' name='services[]' class='servicesstand' value='".$data['serviceid']."'  data-name='".$data['service_name']."' data-id='".$data['serviceid']."' > ".$data['service_name']."</li>";
                $html .= "<ul>";
                    $subservices = $this->cus_stand_sub_services($book_type, $package, $data['serviceid']);
                if ($data['service_name'] == 'Editing Services') {
                    $service_cost = $subservices[0]->cost/100;
                    $total_pages_sc = 11*$lead_book_pages;
                    $service_cost1 = $subservices[1]->cost/100;
                    $total_pages_sc1 = 11*$lead_book_pages;
                     if($subservices[1]->id){
                        if ($data['service_name'] == 'Editing Services') {
                            $html .=    "<label>No of pagess:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$total_pages_sc.");' value='".$lead_book_pages."'  id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                        }
                    }else{
                        if($data['service_name'] == 'Editing Services'){
                             $html .=   "<label>No of pagesss:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$total_pages_sc.");' value='".$lead_book_pages."'  id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                            }
                        }
                    // $html .= "<label>No of pages:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$subservices[1]->id.",".$subservices[1]->cost.",".$total_pages_sc.",".$total_pages_sc1.");' value='".$lead_book_pages."' min='100' id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                        }
                    foreach($subservices as $subservice){
                        if ($subservice->subservice_name == 'Complimentary Author Copies') {
                            $number_of_copies = $subservice->cost/100;
                            $single_copy_cost = $subservice->cost/$number_of_copies;
                            $number_of_copy_cost = $single_copy_cost*$complimentry_copies;
                            $number_of_copies = $subservice->cost/100;
                    $html .="<br><label>No of Copies</label><input type='number' onkeyup='myFunctioneditcopies(".$subservice->id.",".$subservice->cost.",".$number_of_copies.");' value='".$number_of_copies."' min='5' id='number_of_complimentary_copies_edit' readonly name='number_of_complimentary_copies_edit'>";
                        }
                    $html .= "<li><input type='checkbox' name='sub_services[]'";  
                    // if ($subservice->id == $sub_services[$i]) {
                    if (in_array($subservice->id, $sub_services)) {
                        $html .= "checked";
                    }
                    if ($subservices[0]->id==$subservice->id && $data['service_name']=='Editing Services') {
                        $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$total_pages_sc.");'   data-cost='".$total_pages_sc."' > ".$subservice->subservice_name."<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$total_pages_sc."'</li>";
                    $servicecost = $total_pages_sc;
                    }else if($subservices[1]->id==$subservice->id && $data['service_name']=='Editing Services'){
                        $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$total_pages_sc1.");'   data-cost='".$total_pages_sc1."' > ".$subservice->subservice_name."<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$total_pages_sc1."'</li>";
                    $servicecost = $subservice->cost;
                    }else{
                        if ($subservice->subservice_name == 'Complimentary Author Copies') {
                            $html .= " checked='' onclick='return false;' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."'  onclick='myFunction(".$subservice->id.",".$number_of_copy_cost.");'   data-cost='".$number_of_copy_cost."' > ".$subservice->subservice_name."";
                        }else if($subservice->subservice_name == 'English to Hindi translation (250-300 words in a page)'){
                           $english_to_hindi =  $subservice->cost;
                           $english_total = $english_to_hindi*$lead_book_pages;
                            $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$english_total.");'   data-cost='".$english_total."' > ".$subservice->subservice_name."";
                        }else if($subservice->subservice_name == 'Hindi to English translation (250-300 words in a page)'){
                            $hindi_to_english =  $subservice->cost;
                            $hindi_total = $hindi_to_english*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$hindi_total.");'   data-cost='".$hindi_total."' > ".$subservice->subservice_name."";
                         }else if($subservice->subservice_name == 'Hindi Typing'){
                            $hindi_type =  $subservice->cost;
                            $hindi_type_total = $hindi_type*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$hindi_type_total.");'   data-cost='".$hindi_type_total."' > ".$subservice->subservice_name."";
                         }else if($subservice->subservice_name == 'English Typing'){
                            $english_typeing =  $subservice->cost;
                            $english_type_total = $english_typeing*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$english_type_total.");'   data-cost='".$english_type_total."' > ".$subservice->subservice_name."";
                         }else if($subservice->subservice_name == 'Urdu Typing'){
                            $urdu_typeing =  $subservice->cost;
                            $urdu_type_total = $urdu_typeing*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$urdu_type_total.");'   data-cost='".$urdu_type_total."' > ".$subservice->subservice_name."";
                         }else if($subservice->subservice_name == 'Color Pages'){
                    
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc color_pages' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");'   data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                         }else{
                            $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");'   data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }
                        if ($subservice->subservice_name == 'Book Size') { 
                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_size_sc' name='book_size_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option ";
                       if ($value == $book_size_sc) {
                        $html .=  "selected";
                       }
                       $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Lamination') { 
                          $Lamination = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='lamination_sc' name='lamination_sc'><option>---select----</option>";
                     foreach ($Lamination as $key => $value) {
                       $html .= "<option ";
                       if ($value == $lamination_sc) {
                          $html .=  "selected";
                       }
                        $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Book Cover') { 
                        $book_cover_sc = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_cover_sc' name='book_cover_sc'><option>---select----</option>";
                     foreach ($book_cover_sc as $key => $value) {
                       $html .= "<option ";
                       if ($value == $book_cover_sc_data) {
                          $html .=  "selected";
                       }
                        $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Paper Type') { 
                          $paper_back = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='paper_type_sc' name='paper_type_sc'><option>---select----</option>";
                     foreach ($paper_back as $key => $value) {
                       $html .= "<option ";
                       if ($value == $paper_type_sc) {
                          $html .=  "selected";
                       }
                       $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == ' Complimentary Author Copies - 10') {
                       $html .=  "<input type='hidden' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$number_of_copy_cost."'></li>";
                    // $servicecost = $number_of_copy_cost;
                }else{
                       if ($subservice->subservice_name == 'Number of Pages Allowed' || $subservice->subservice_name == 'Number of Pages Allowed - 400') {
                    $html .=    "<input type='number' disabled  value='".$lead_book_pages."' min='5' id='number_of_pages_allowed' name='number_of_pages_allowed'>";
                      $html .=  "<input type='hidden' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'></li>";
                        }else if ($subservice->subservice_name == 'ISBN Allocation') {
                      $html .= "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='0'</li>";
                        }else if ($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price') {
                            if ($additional_author_copies_number > 0) {
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;&nbsp;&nbsp;<input type='number' id='additional_author_copies_number' name='additional_author_copies_number' value='".$additional_author_copies_number."'  ></li>";
                    $servicecost = $subservice->cost;
                            }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;&nbsp;&nbsp;<input type='number' id='additional_author_copies_number' name='additional_author_copies_number' style='display:none;' ></li>";
                    $servicecost = $subservice->cost;
                            }
                        }else if($subservice->subservice_name == 'English to Hindi translation (250-300 words in a page)'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$english_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if($subservice->subservice_name == 'Hindi to English translation (250-300 words in a page)'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$hindi_total."'></li>";
                    $servicecost = $subservice->cost; 
                        }else if($subservice->subservice_name == 'Hindi Typing'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$hindi_type_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if($subservice->subservice_name == 'English Typing'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$english_type_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if($subservice->subservice_name == 'Urdu Typing'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$urdu_type_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'Color Pages') {
                            if ($color_pages > 0) {
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;<input type='number' id='color_pages_number' name='color_pages' value='".$color_pages."'  ></li>";
                    $servicecost = $subservice->cost;
                            }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;<input type='number' id='color_pages_number' name='color_pages' style='display:none;' ></li>";
                    $servicecost = $subservice->cost;
                            }
                         }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'></li>";
                    $servicecost = $subservice->cost;
                        }
                }
                    }
                 // $i++;
              }
                $html .= "</ul>";
              }?>
            <?php
            $html .= "</ul>";
            $data['html'] = $html;
            $data['pkgvalue'] = $servicecost; 
            $data['gst'] = ($servicecost*18)/100; 
            $data['totalamt'] = ($servicecost+$data['gst']); 
            echo json_encode($data);exit;
    }
    public function getservies_sc_for_edit($value='')
    {
       $complimentry_copies = $_POST['complimentry_copies'];
       $color_pages = $_POST['color_pages'];
       $book_cover_sc_data = $_POST['book_cover_sc'];
       $book_size_sc = $_POST['book_size_sc'];
       $paper_type_sc = $_POST['paper_type_sc'];
       $lamination_sc = $_POST['lamination_sc'];
       $book_type = $_POST['book_type'];
       $lead_book_pages = $_POST['lead_book_pages'];
       $package_details = $_POST['package_details'];
       $package = $_POST['lead_package_name'];
        $additional_author_copies_number = $_POST['additional_author_copies_number'];
       $additional_gross_amount = $_POST['additional_gross_amount'];
       $cost_of_additional = $_POST['cost_of_additional'];
        $sub_services = $_POST['sub_service'];
        $service = explode(", ", $services);
        $sub_services = explode(", ", $sub_services);
         $result = $this->leads_model->getservies_coustize_standard($package, $book_type,$package_details);
            $services = array();
            $html .= "<ul class='dynamicservice'>";
            //$i = 0;
            foreach($result as $data){
                //$services[] = "<option value='$data[serviceid]'>".$data['service_name']."</option>";
                // print_r($result);
                // die();
                $html .= "<li style='font-weight:bold;'><input type='checkbox' checked style='display:none' name='services[]' class='servicesstand' value='".$data['serviceid']."'  data-name='".$data['service_name']."' data-id='".$data['serviceid']."' > ".$data['service_name']."</li>";
                $html .= "<ul>";
                    $subservices = $this->cus_stand_sub_services($book_type, $package, $data['serviceid']);
                if ($data['service_name'] == 'Editing Services') {
                    $service_cost = $subservices[0]->cost/100;
                    $total_pages_sc = 11*$lead_book_pages;
                    $service_cost1 = $subservices[1]->cost/100;
                    $total_pages_sc1 = 11*$lead_book_pages;
                     if($subservices[1]->id){
                        if ($data['service_name'] == 'Editing Services') {
                            $html .=    "<label>No of pagess:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$total_pages_sc.");' value='".$lead_book_pages."'  id='number_of_pages_for_sc' name='number_of_pages_for_sc'>";
                        }
                    }else{
                        if($data['service_name'] == 'Editing Services'){
                             $html .=   "<label>No of pagesss:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$total_pages_sc.");' value='".$lead_book_pages."'  id='number_of_pages_for_sc' name='number_of_pages_for_sc'>";
                            }
                        }
                    // $html .= "<label>No of pages:</label><input type='number' onkeyup='myFunctiondataedit(".$subservices[0]->id.",".$subservices[0]->cost.",".$subservices[1]->id.",".$subservices[1]->cost.",".$total_pages_sc.",".$total_pages_sc1.");' value='".$lead_book_pages."' min='100' id='number_of_pages_for_sc_for_edit' name='number_of_pages_for_sc'>";
                        }
                    foreach($subservices as $subservice){
                        if ($subservice->subservice_name == 'Complimentary Author Copies') {
                            $number_of_copies = $subservice->cost/100;
                            $single_copy_cost = $subservice->cost/$number_of_copies;
                            $number_of_copy_cost = $single_copy_cost*$complimentry_copies;
                            $number_of_copies = $subservice->cost/100;
                    $html .="<br><label>No of Copies</label><input type='number' onkeyup='myFunctioneditcopies(".$subservice->id.",".$subservice->cost.",".$number_of_copies.");' value='".$number_of_copies."' min='5' id='number_of_complimentary_copies' readonly name='number_of_complimentary_copies_edit'>";
                        }
                    $html .= "<li><input type='checkbox' name='sub_services[]'";  
                    // if ($subservice->id == $sub_services[$i]) {
                    if (in_array($subservice->id, $sub_services)) {
                        $html .= "checked";
                    }
                    if ($subservices[0]->id==$subservice->id && $data['service_name']=='Editing Services') {
                        $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$total_pages_sc.");'   data-cost='".$total_pages_sc."' > ".$subservice->subservice_name."<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$total_pages_sc."'</li>";
                    $servicecost = $total_pages_sc;
                    }else if($subservices[1]->id==$subservice->id && $data['service_name']=='Editing Services'){
                        $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$total_pages_sc1.");'   data-cost='".$total_pages_sc1."' > ".$subservice->subservice_name."<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$total_pages_sc1."'</li>";
                    $servicecost = $subservice->cost;
                    }else{
                        if ($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price') {
                            $html .= " value='".$subservice->id."' class='subservicesstand_sc additional_author_copy' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");'   data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }
                        else if ($subservice->subservice_name == 'Complimentary Author Copies') {
                            $html .= " checked='' onclick='return false;' value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."'  onclick='myFunction(".$subservice->id.",".$number_of_copy_cost.");'   data-cost='".$number_of_copy_cost."' > ".$subservice->subservice_name."";
                        }else if($subservice->subservice_name == 'English to Hindi translation (250-300 words in a page)'){
                           $english_to_hindi =  $subservice->cost;
                           $english_total = $english_to_hindi*$lead_book_pages;
                            $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$english_total.");'   data-cost='".$english_total."' > ".$subservice->subservice_name."";
                        }else if($subservice->subservice_name == 'Hindi to English translation (250-300 words in a page)'){
                            $hindi_to_english =  $subservice->cost;
                            $hindi_total = $hindi_to_english*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$hindi_total.");'   data-cost='".$hindi_total."' > ".$subservice->subservice_name."";
                         }else if($subservice->subservice_name == 'Hindi Typing'){
                            $hindi_type =  $subservice->cost;
                            $hindi_type_total = $hindi_type*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$hindi_type_total.");'   data-cost='".$hindi_type_total."' > ".$subservice->subservice_name."";
                         }else if($subservice->subservice_name == 'English Typing'){
                            $english_typeing =  $subservice->cost;
                            $english_type_total = $english_typeing*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$english_type_total.");'   data-cost='".$english_type_total."' > ".$subservice->subservice_name."";
                         }else if($subservice->subservice_name == 'Urdu Typing'){
                            $urdu_typeing =  $subservice->cost;
                            $urdu_type_total = $urdu_typeing*$lead_book_pages;
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$urdu_type_total.");'   data-cost='".$urdu_type_total."' > ".$subservice->subservice_name."";
                         }else if($subservice->subservice_name == 'Color Pages'){
                    
                             $html .= " value='".$subservice->id."' class='subservicesstand_sc color_pages' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");'   data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                         }else{
                            $html .= " value='".$subservice->id."' class='subservicesstand_sc' id='myCheck".$subservice->id."' data-id='".$subservice->id."' data-service='".$data['service_name']."'  data-name='".$subservice->subservice_name."' onclick='myFunction(".$subservice->id.",".$subservice->cost.");'   data-cost='".$subservice->cost."' > ".$subservice->subservice_name."";
                        }
                        if ($subservice->subservice_name == 'Book Size') { 
                    $book_size = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_size_sc' name='book_size_sc'><option>---select----</option>";
                     foreach ($book_size as $key => $value) {
                       $html .= "<option ";
                       if ($value == $book_size_sc) {
                        $html .=  "selected";
                       }
                       $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Lamination') { 
                          $Lamination = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='lamination_sc' name='lamination_sc'><option>---select----</option>";
                     foreach ($Lamination as $key => $value) {
                       $html .= "<option ";
                       if ($value == $lamination_sc) {
                          $html .=  "selected";
                       }
                        $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Book Cover') { 
                        $book_cover_sc = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='book_cover_sc' name='book_cover_sc'><option>---select----</option>";
                     foreach ($book_cover_sc as $key => $value) {
                       $html .= "<option ";
                       if ($value == $book_cover_sc_data) {
                          $html .=  "selected";
                       }
                        $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                    if ($subservice->subservice_name == 'Paper Type') { 
                          $paper_back = explode("/",$subservice->subServiceNameValue);
                     $html .= "<select id='paper_type_sc' name='paper_type_sc'><option>---select----</option>";
                     foreach ($paper_back as $key => $value) {
                       $html .= "<option ";
                       if ($value == $paper_type_sc) {
                          $html .=  "selected";
                       }
                       $html .= " value='".$value."'>".$value."</option>";
                     }
                     $html .= "</select>";
                   }
                   if ($subservice->subservice_name == 'Complimentary Author Copies' || $subservice->subservice_name == ' Complimentary Author Copies - 10') {
                       $html .=  "<input type='hidden' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$number_of_copy_cost."'></li>";
                    // $servicecost = $number_of_copy_cost;
                }else{
                       if ($subservice->subservice_name == 'Number of Pages Allowed' || $subservice->subservice_name == 'Number of Pages Allowed - 400') {
                    $html .=    "<input type='number' disabled  value='".$lead_book_pages."' min='5' id='number_of_pages_allowed' name='number_of_pages_allowed'>";
                      $html .=  "<input type='hidden' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'></li>";
                        }else if ($subservice->subservice_name == 'ISBN Allocation') {
                      $html .= "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='0'</li>";
                        }else if ($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price') {
                            if ($additional_author_copies_number > 0) {
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;&nbsp;&nbsp;<input type='number' id='additional_author_copies_number' name='additional_author_copies_number' value='".$additional_author_copies_number."'  ></li>";
                    $servicecost = $subservice->cost;
                            }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;&nbsp;&nbsp;<input type='number' id='additional_author_copies_number' name='additional_author_copies_number' style='display:none;' ></li>";
                    $servicecost = $subservice->cost;
                            }
                        }else if($subservice->subservice_name == 'English to Hindi translation (250-300 words in a page)'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$english_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if($subservice->subservice_name == 'Hindi to English translation (250-300 words in a page)'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$hindi_total."'></li>";
                    $servicecost = $subservice->cost; 
                        }else if($subservice->subservice_name == 'Hindi Typing'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$hindi_type_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if($subservice->subservice_name == 'English Typing'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$english_type_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if($subservice->subservice_name == 'Urdu Typing'){
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$urdu_type_total."'></li>";
                    $servicecost = $subservice->cost;
                        }else if ($subservice->subservice_name == 'Color Pages') {
                            if ($color_pages > 0) {
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;<input type='number' id='color_pages_number' name='color_pages' value='".$color_pages."'  ></li>";
                    $servicecost = $subservice->cost;
                            }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'>&nbsp;<input type='number' id='color_pages_number' name='color_pages' style='display:none;' ></li>";
                    $servicecost = $subservice->cost;
                            }
                         }else{
                            $html .=  "<input type='text' id='sub_service_cost_sc_edit".$subservice->id."' disabled value='".$subservice->cost."'></li>";
                    $servicecost = $subservice->cost;
                        }
                }
                    }
                 // $i++;
              }
                $html .= "</ul>";
              }?>
            <?php
            $html .= "</ul>";
            $data['html'] = $html;
            $data['pkgvalue'] = $servicecost; 
            $data['gst'] = ($servicecost*18)/100; 
            $data['totalamt'] = ($servicecost+$data['gst']); 
            echo json_encode($data);exit;
    }
    public function sendmail(){
        $this->load->library('phpmailer_lib');
        $mail = $this->phpmailer_lib->load();
        $mail->IsSMTP();
        // $leadId = $this->input->post('id');
        $leadId = end($this->uri->segment_array());
        $packageData = $this->db->get_where('tblleads',array('id'=>$leadId))->row();
        $staff_id = $_SESSION['staff_user_id'];
        $staff_details = $this->db->get_where('tblstaff',array('staffid'=>$staff_id))->row();

        //echo "<pre>";
        // print_r($staff_id);exit;

    
        $to = $packageData->email;
    //   $to = 'mayankmishrabfcinfotech@gmail.com';
        $subject = 'BFC Publications Package Details';
        $message = "<p>Dear Mr./Miss. <b>".$packageData->lead_author_name."</b>,</p>";
       
        $message .= "<p>As discussed on call about your publishing requirements, we have specially curated a package which will best suit you to start your publishing journey with us.</p><br>";
        if($packageData->create_p_offer != ''){
            $message .= "<p><b>Offer: </b>".$packageData->create_p_offer.",</p>";
        }
        $message .= "<p><b>Please refer the attachment for package details.</b></p>";
      
        $message .= "<p>Package Value: Rs. <b>".$packageData->lead_packge_value."</b> + 18% GST </p>";
        if ($packageData->lead_packge_discount  ==0){}else{
            $new_width = ($packageData->lead_packge_discount / 100) * $packageData->lead_packge_value;
            $message .= "<p>Discount: Rs. <b>".$new_width."</b></p>";
        }
        $message .= "<p>GST: Rs. <b>".$packageData->lead_packg_gst."</b></p>";
        

        if ($packageData->gross_amt != 0 && $packageData->cost_of_additional_copy != 0) {
            $message .= "<p>Additional author copy cost: Rs. <b>".$packageData->cost_of_additional_copy."</b></p><br>";
        $message .= "<p>Net Package Cost (including additional author copy): Rs. <b>".$packageData->gross_amt."</b></p><br>";
            
        }else{
        $message .= "<p>Net Package Cost (After GST): Rs. <b>".$packageData->lead_packg_totalamount."</b></p><br>";

        }
        $file = 'assets/authorMail/'.$packageData->lead_pdf_data; 
        $message .= "<p><b>Will connect again shortly so we can take things to the Next Step.</b></p>";
        $message .= "<p><b>Payment Details for transferring our fees</b>:</p>";
        $message .= "<p><b>Bank Name</b>: Kotak Mahindra Bank</p>";
        $message .= "<p><b>Name</b>: BFC Publications Private Limited</p>";
        $message .= "<p><b>Account Number</b>: 2814002782</p>";
        $message .= "<p><b>IFSC</b>: KKBK0005196</p>";
        $message .= "<p><b>Payment Gateway Link:</b></p>";
        //$message.= $file;

        $message .= "<a href='https://authordashboard.bfcpublications.com/paymentop'>https://authordashboard.bfcpublications.com/paymentop</a><br>";
        $message .= "<p>---</p>";
        $message .="Thanks & Regards,<br>";
        $message .= $staff_details->firstname.' '.$staff_details->lastname.'<br>';
        if ($staff_id == 54) {
            $message .="Sr. Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 9305807466<br>";
            $message .="Email: chitrapal@bfcpublications.com <br>";
            // Email setup (Ok)
            $mail->Username   = 'chitrapal@bfcpublications.com';
            $mail->Password   = 'Good@9918';
            $mail->SetFrom('chitrapal@bfcpublications.com', "BFC Publications");
            
        }else if ($staff_id == 91) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7347700444<br>";
            $message .="Email: aviharshasingh@bfcpublications.com <br>"; 
            // Email setup
            $mail->Username   = 'aviharshasingh@bfcpublications.com';
            $mail->Password   = 'bfc@2021';
            $mail->SetFrom('aviharshasingh@bfcpublications.com', "BFC Publications"); 
        }else if ($staff_id == 53) {
            $message .="Sr. Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 9129981117<br>";
            $message .="Email: priyabajpayee@bfcpublications.com <br>";
            // Email setup
            $mail->Username   = 'priyabajpayee@bfcpublications.com';
            $mail->Password   = '123456';
            $mail->SetFrom('priyabajpayee@bfcpublications.com', "BFC Publications");  
        }else if ($staff_id == 38) {
            $message .="Sr. Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 6307937532<br>";
            $message .="Email: shwetamishra@bfcpublications.com <br>";  
        
            $mail->Username   = 'shwetamishra@bfcpublications.com';
            $mail->Password   = 'Shweta@12345';
            $mail->SetFrom('shwetamishra@bfcpublications.com', "BFC Publications");
            // $mail->Username   = 'chitrapal@bfcpublications.com';
            // $mail->Password   = 'Good@9918';
            // $mail->SetFrom('chitrapal@bfcpublications.com', "BFC Publications");
        }else if ($staff_id == 99) {
            
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307088329<br>";
            $message .="Email: shalinisingh@bfcpublications.com <br>"; 
            $mail->Username   = 'Shalinisingh@bfcpublications.com';
            $mail->Password   = '9454767195';
          
            $mail->SetFrom('Shalinisingh@bfcpublications.com', "BFC Publications"); 

        }else if ($staff_id == 100) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307088328<br>";
            $message .="Email: Nehasahu@bfcpublications.com <br>"; 
            $mail->Username   = 'Nehasahu@bfcpublications.com';
            $mail->Password   = 'neha@0808';
            $mail->SetFrom('Nehasahu@bfcpublications.com', "BFC Publications"); 

        }
        else if ($staff_id == 105) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307083682<br>";
            $message .="Email: saumyakhajanchi@bfcpublications.com <br>"; 
            $mail->Username   = 'Saumyakhajanchi@bfcpublications.com';
            $mail->Password   = 'saumya@2021';
            $mail->SetFrom('Saumyakhajanchi@bfcpublications.com', "BFC Publications"); 
            
        }  else if ($staff_id == 58) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 9129984448<br>";
            $message .="Email: ravisingh@bfcpublications.com <br>"; 
            $mail->Username   = 'ravisingh@bfcpublications.com';
            $mail->Password   = 'Nishu@181';
            $mail->SetFrom('ravisingh@bfcpublications.com', "BFC Publications"); 

        }else if ($staff_id == 107) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307083684<br>";
            $message .="Email: anamikasingh@bfcpublications.com <br>"; 
            $mail->Username   = 'anamikasingh@bfcpublications.com';
            $mail->Password   = 'BFC@2021';
            $mail->SetFrom('anamikasingh@bfcpublications.com', "BFC Publications"); 

        }else if ($staff_id == 55) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307083684<br>";
            $message .="Email: ashishkverma@bfcpublications.com <br>"; 
            $mail->Username   = 'ashishkverma@bfcpublications.com';
            $mail->Password   = 'ashish@2020';
            $mail->SetFrom('ashishkverma@bfcpublications.com', "BFC Publications");

        }
        else{
            // $mail->Username   = 'shwetamishra@bfcpublications.com';
            // $mail->Password   = 'Shweta@12345';
            // $mail->SetFrom('shwetamishra@bfcpublications.com', "BFC Publications");
        }
            
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "ssl";
        $mail->Port       = 465;
        $mail->Host       = "smtp.rediffmailpro.com";
       
        $mail->IsHTML(true);
        $mail->AddAddress($to, '');
        //$mail->addcc('rajeshguptabfcinfotech@gmail.com', '');
        $mail->addcc('projectcoodinator.bfcpub@gmail.com', '');
        $mail->addcc('shivangiyadav@bfcpublications.com', '');
        $mail->addcc('gaurav@bfcpublications.com', '');
        if ($staff_id == 54) {
            $mail->addcc('chitrapal@bfcpublications.com', '');
        }else if ($staff_id == 91) {
            $mail->addcc('aviharshasingh@bfcpublications.com', '');
            $mail->addcc('shwetamishra@bfcpublications.com', '');
        }else if ($staff_id == 53) {
            $mail->addcc('priyabajpayee@bfcpublications.com', ''); 
        }else if ($staff_id == 38) {
            $mail->addcc('shwetamishra@bfcpublications.com', '');
        }else if ($staff_id == 99) {
            $mail->addcc('Shalinisingh@bfcpublications.com', '');
              $mail->addcc('priyabajpayee@bfcpublications.com', ''); 

        }else if ($staff_id == 100) {
            $mail->addcc('Nehasahu@bfcpublications.com', '');
            $mail->addcc('chitrapal@bfcpublications.com', '');

        }else if ($staff_id == 105) {
            $mail->addcc('saumyakhajanchi@bfcpublications.com', '');
            $mail->addcc('chitrapal@bfcpublications.com', '');

        }  else if ($staff_id == 58) {
            $mail->addcc('ravisingh@bfcpublications.com', '');

        }else if ($staff_id == 107) {
            $mail->addcc('anamikasingh@bfcpublications.com', '');
            $mail->addcc('priyabajpayee@bfcpublications.com', ''); 

        }else{}
        $mail->addAttachment($file); 
           
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $result =0;
        if ($mail->Send()) {
            $result =1;
            echo '<script>alert("Your Query Has Been Submitted Successfully.");</script>';
        } else {
            echo $mail->ErrorInfo;
        }
    

    
        if($result){
            $data_array = array(
            'createPackEmailStatus' => 1,
            'createPackEmailDate' => date('Y-m-d H-i-s'),
            );
            $this->db->where('id',$leadId);
            $this->db->update('tblleads',$data_array);
            set_alert('success', _l('Mail sent successfully...'));
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            set_alert('warning', _l('Mail not sent'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function sendmail_another(){
        $uri_path = $_SERVER['HTTP_REFERER'];
        $uri_segments = explode('/', $uri_path);

     

        $this->load->library('phpmailer_lib');
        $mail = $this->phpmailer_lib->load();
        $mail->IsSMTP();

        $leadId = end($this->uri->segment_array());
        if ($uri_segments[5] == 'multi_prview_pack') {
            $this->db->select('tblleads.email,tblleads.phonenumber,tblleads_create_package.*');

            $this->db->from('tblleads_create_package');
    
            $this->db->join('tblleads','tblleads.id = tblleads_create_package.leadid');
    
            $this->db->where('tblleads_create_package.id', $leadId); 
    
            $result = $this->db->get(); 
    
            $packageData = $result->row();
        }else{
            $this->db->select('tblleads.email,tblleads.phonenumber,compaire_create_package.*');

            $this->db->from('compaire_create_package');
    
            $this->db->join('tblleads','tblleads.id = compaire_create_package.leadid');
    
            $this->db->where('compaire_create_package.id', $leadId); 
    
            $result = $this->db->get(); 
    
            $packageData = $result->row();
        }
      


        $staff_id = $_SESSION['staff_user_id'];
        $staff_details = $this->db->get_where('tblstaff',array('staffid'=>$staff_id))->row();

        //echo "<pre>";
        // print_r($staff_id);exit;

        // $to = 'mayankmishrabfcinfotech@gmail.com';
        $to = $packageData->email;

        $subject = 'BFC Publications Package Details';
        $message = "<p>Dear Mr. <b>".$packageData->lead_author_name."</b>,</p>";
       
        $message .= "<p>As discussed on call about your publishing requirements, we have specially curated a package which will best suit you to start your publishing journey with us.</p><br>";
        if ($packageData->create_p_offer) {
            $message .= "<p><b>Offer: </b>".$packageData->create_p_offer.",</p>";
        }
       
        $message .= "<p><b>Please refer the attachment for package details.</b></p>";
     
        $message .= "<p>Package Value: Rs. <b>".$packageData->lead_packge_value."</b> + 18% GST </p>";
        if ($packageData->lead_packge_discount != null) {
            $new_width = ($packageData->lead_packge_discount / 100) * $packageData->lead_packge_value;
            $message .= "<p>Discount: Rs. <b>".$new_width."</b></p>";
        }
        $message .= "<p>GST: Rs. <b>".$packageData->lead_packg_gst."</b></p>";
        
        if ($packageData->gross_amt != '') {
            $message .= "<p>Additional author copy cost: Rs. <b>".$packageData->cost_of_additional_copy."</b></p>";
        $message .= "<p>Net Package Cost (including additional author copy): Rs. <b>".$packageData->gross_amt."</b></p><br>";
            
        }else{
        $message .= "<p>Net Package Cost (After GST): Rs. <b>".$packageData->lead_packg_totalamount."</b></p><br>";

        }
        $file = 'assets/authorMail/'.$packageData->lead_pdf_data; 
        $message .= "<p><b>Will connect again shortly so we can take things to the Next Step.</b></p>";
        $message .= "<p><b>Payment Details for transferring our fees</b>:</p>";
        $message .= "<p><b>Bank Name</b>: Kotak Mahindra Bank</p>";
        $message .= "<p><b>Name</b>: BFC Publications Private Limited</p>";
        $message .= "<p><b>Account Number</b>: 2814002782</p>";
        $message .= "<p><b>IFSC</b>: KKBK0005196</p>";
        $message .= "<p><b>Payment Gateway Link:</b></p>";
        //$message.= $file;

        $message .= "<a href='https://authordashboard.bfcpublications.com/paymentop'>https://authordashboard.bfcpublications.com/paymentop</a><br>";
        $message .= "<p>---</p>";
        $message .="Thanks & Regards,<br>";
        $message .= $staff_details->firstname.' '.$staff_details->lastname.'<br>';
        if ($staff_id == 54) {
            $message .="Sr. Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 9305807466<br>";
            $message .="Email: chitrapal@bfcpublications.com <br>";
            // Email setup (Ok)
            $mail->Username   = 'chitrapal@bfcpublications.com';
            $mail->Password   = 'Good@9918';
            $mail->SetFrom('chitrapal@bfcpublications.com', "BFC Publications");
            
        }else if ($staff_id == 91) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7347700444<br>";
            $message .="Email: aviharshasingh@bfcpublications.com <br>"; 
            // Email setup
            $mail->Username   = 'aviharshasingh@bfcpublications.com';
            $mail->Password   = 'bfc@2021';
            $mail->SetFrom('aviharshasingh@bfcpublications.com', "BFC Publications"); 
        }else if ($staff_id == 53) {
            $message .="Sr. Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 9129981117<br>";
            $message .="Email: priyabajpayee@bfcpublications.com <br>";
            // Email setup
            $mail->Username   = 'priyabajpayee@bfcpublications.com';
            $mail->Password   = '123456';
            $mail->SetFrom('priyabajpayee@bfcpublications.com', "BFC Publications"); 
        }else if ($staff_id == 38) {
            $message .="Sr. Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 6307937532<br>";
            $message .="Email: shwetamishra@bfcpublications.com <br>";  
        
            $mail->Username   = 'shwetamishra@bfcpublications.com';
            $mail->Password   = 'Shweta@12345';
            $mail->SetFrom('shwetamishra@bfcpublications.com', "BFC Publications");
            // $mail->Username   = 'chitrapal@bfcpublications.com';
            // $mail->Password   = 'Good@9918';
            // $mail->SetFrom('chitrapal@bfcpublications.com', "BFC Publications");
        }else if ($staff_id == 99) {
            
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307088329<br>";
            $message .="Email: shalinisingh@bfcpublications.com <br>"; 
            $mail->Username   = 'Shalinisingh@bfcpublications.com';
            $mail->Password   = '9454767195';
          
            $mail->SetFrom('Shalinisingh@bfcpublications.com', "BFC Publications"); 

        }else if ($staff_id == 100) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307088328<br>";
            $message .="Email: Nehasahu@bfcpublications.com <br>"; 
            $mail->Username   = 'Nehasahu@bfcpublications.com';
            $mail->Password   = 'neha@0808';
            $mail->SetFrom('Nehasahu@bfcpublications.com', "BFC Publications"); 

        }
        else if ($staff_id == 105) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307083682<br>";
            $message .="Email: saumyakhajanchi@bfcpublications.com <br>"; 
            $mail->Username   = 'Saumyakhajanchi@bfcpublications.com';
            $mail->Password   = 'saumya@2021';
            $mail->SetFrom('Saumyakhajanchi@bfcpublications.com', "BFC Publications"); 

        }  else if ($staff_id == 58) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 9129984448<br>";
            $message .="Email: ravisingh@bfcpublications.com <br>"; 
            $mail->Username   = 'ravisingh@bfcpublications.com';
            $mail->Password   = 'Nishu@181';
            $mail->SetFrom('ravisingh@bfcpublications.com', "BFC Publications"); 

        }else if ($staff_id == 107) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307083684<br>";
            $message .="Email: anamikasingh@bfcpublications.com <br>"; 
            $mail->Username   = 'anamikasingh@bfcpublications.com';
            $mail->Password   = 'BFC@2021';
            $mail->SetFrom('anamikasingh@bfcpublications.com', "BFC Publications"); 

        }else if ($staff_id == 55) {
            $message .="Publishing Consultant<br>";
            $message .="BFC Publications Pvt. Ltd.<br>";
            $message .="(M) +91 7307083684<br>";
            $message .="Email: ashishkverma@bfcpublications.com <br>"; 
            $mail->Username   = 'ashishkverma@bfcpublications.com';
            $mail->Password   = 'ashish@2020';
            $mail->SetFrom('ashishkverma@bfcpublications.com', "BFC Publications");

        }else{
            
        }
            
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "ssl";
        $mail->Port       = 465;
        $mail->Host       = "smtp.rediffmailpro.com";
        
        $mail->IsHTML(true);
        $mail->AddAddress($to, '');
        //$mail->addcc('rajeshguptabfcinfotech@gmail.com', '');
        $mail->addcc('projectcoodinator.bfcpub@gmail.com', '');
        $mail->addcc('shivangiyadav@bfcpublications.com', '');
        $mail->addcc('gaurav@bfcpublications.com', '');
        // if ($staff_id == 54) {
        //     $mail->addcc('chitrapal@bfcpublications.com', '');
        // }else if ($staff_id == 91) {
        //     $mail->addcc('aviharshasingh@bfcpublications.com', '');
        // }else if ($staff_id == 53) {
        //     $mail->addcc('priyabajpayee@bfcpublications.com', ''); 
        // }else if ($staff_id == 38) {
        //     $mail->addcc('shwetamishra@bfcpublications.com', '');
        // }else if ($staff_id == 99) {
        //     $mail->addcc('Shalinisingh@bfcpublications.com', '');

        // }else if ($staff_id == 100) {
        //     $mail->addcc('Nehasahu@bfcpublications.com', '');

        // }
        // else if ($staff_id == 105) {
        //     $mail->addcc('saumyakhajanchi@bfcpublications.com', '');

        // }  else if ($staff_id == 58) {
        //     $mail->addcc('ravisingh@bfcpublications.com', '');

        // }else if ($staff_id == 107) {
        //     $mail->addcc('anamikasingh@bfcpublications.com', '');
        //     $mail->addcc('priyabajpayee@bfcpublications.com', ''); 

        // }else{}
        if ($staff_id == 54) {
            $mail->addcc('chitrapal@bfcpublications.com', '');
        }else if ($staff_id == 91) {
            $mail->addcc('aviharshasingh@bfcpublications.com', '');
            $mail->addcc('shwetamishra@bfcpublications.com', '');
        }else if ($staff_id == 53) {
            $mail->addcc('priyabajpayee@bfcpublications.com', ''); 
        }else if ($staff_id == 38) {
            $mail->addcc('shwetamishra@bfcpublications.com', '');
        }else if ($staff_id == 99) {
            $mail->addcc('Shalinisingh@bfcpublications.com', '');
              $mail->addcc('priyabajpayee@bfcpublications.com', ''); 

        }else if ($staff_id == 100) {
            $mail->addcc('Nehasahu@bfcpublications.com', '');
            $mail->addcc('chitrapal@bfcpublications.com', '');

        }else if ($staff_id == 105) {
            $mail->addcc('saumyakhajanchi@bfcpublications.com', '');
            $mail->addcc('chitrapal@bfcpublications.com', '');

        }  else if ($staff_id == 58) {
            $mail->addcc('ravisingh@bfcpublications.com', '');

        }else if ($staff_id == 107) {
            $mail->addcc('anamikasingh@bfcpublications.com', '');
            $mail->addcc('priyabajpayee@bfcpublications.com', ''); 

        }else{}
        $mail->addAttachment($file); 
           
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $result =0;
       
        if ($mail->Send()) {
            $result =1;
            echo '<script>alert("Your Query Has Been Submitted Successfully.");</script>';
        } else {
            echo $mail->ErrorInfo;
        }
    // echo $result;
    //     print_r($message);
    //     print_r($packageData);die;
    
        if($result){
            // $data_array = array(
            // 'createPackEmailStatus' => 1,
            // 'createPackEmailDate' => date('Y-m-d H-i-s'),
            // );
            // $this->db->where('id',$leadId);
            // $this->db->update('tblleads',$data_array);
            set_alert('success', _l('Mail sent successfully...'));
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            set_alert('warning', _l('Mail not sent'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    public function email_status($value='')
    {
        $staff_id = $_SESSION['staff_user_id'];
        if (is_admin() || is_headtrm() || $staff_id==28 ) {
            $data['project_data'] =$this->db->select('createPackEmailDate,lead_book_type,lead_package_detail,lead_created_date,email,lead_author_name,phonenumber,created_at,lead_package_name,lead_packg_totalamount,lead_pdf_data,cost_of_additional_copy,gross_amt')->order_by("createPackEmailDate", "desc")->get_where('tblleads',array('createPackEmailStatus'=>1,'createPackEmailDate !='=>''))->result();
        }else{
            $data['project_data'] = $this->db->select('createPackEmailDate,lead_book_type,lead_package_detail,lead_created_date,email,lead_author_name,phonenumber,created_at,lead_package_name,lead_packg_totalamount,lead_pdf_data,cost_of_additional_copy,gross_amt')->order_by("createPackEmailDate", "desc")->get_where('tblleads',array('createPackEmailStatus'=>1,'createPackEmailDate !='=>'', 'assigned'=>$staff_id ))->result();
    }
    $this->load->view('admin/leads/email_status', $data); 
    }
    public function acquisition_report($value='')
    {
        $staff_id = $_SESSION['staff_user_id'];
        if (is_admin() || is_headtrm() || $staff_id==34 || $staff_id==28) {
    //$data['project_data'] = $this->db->order_by("id", "desc")->get_where('tblleads',array('status'=>39))->result();
           

    $this->db->where('lead_category_id', 39);
    $this->db->where('createPackEmailStatus', 1);
    $this->db->where('lead_payment_status', 1);
    $this->db->select('CONCAT(tbll.firstname, ' .' '. ', tbll.lastname) AS fullname,tblleads.*');
    $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
    $result_data = $this->db->get('tblleads')->result();
    $data['project_data'] = $result_data;
    //echo "<pre>";
    //print_r($result_data);
    //exit();
    }else{
        $staff_id = $_SESSION['staff_user_id'];

        $this->db->where('lead_category_id', 39);
        $this->db->where('createPackEmailStatus', 1);
        $this->db->where('lead_payment_status', 1);
        $this->db->where('assigned', $staff_id);
        $this->db->select('CONCAT(tbll.firstname, ' .''. ', tbll.lastname) AS fullname,tblleads.*');
        $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
        $result_data = $this->db->get('tblleads')->result();


        $data['project_data'] = $result_data;
    }
$this->db->select('staffid,firstname');
                      $this->db->where('department_id',24);
                      $this->db->where('active',1);
                    $query = $this->db->get('tblstaff')->result();
                    
$data['pcList']=$query;
// print_r($data['pcList']);die;
    $this->load->view('admin/leads/acquisition_report', $data); 
    }
    public function change_pc_acquisition($value='')
    {
        $staffid = $_POST['staff_id'];
        // echo $staffid;die;
        $this->db->where('lead_category_id', 39);
        $this->db->where('createPackEmailStatus', 1);
        $this->db->where('assigned', $staffid);
        $this->db->where('lead_payment_status', 1);
        $this->db->select('CONCAT(tbll.firstname, ' ." ".', tbll.lastname) AS fullname,tblleads.*');
        $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
        $result = $this->db->get('tblleads')->result();
        // $result = $this->db->order_by("id", "desc")->get_where('tblleads',array('lead_category_id'=>39 ,'createPackEmailStatus'=>1, 'assigned'=>$staffid))->result();
        $html = "";
         $i=1;
            foreach($result as $row){
                
                $Package_name = '';
                                if ($row->lead_package_detail == 1) {
                                 $Package_name = 'Standard';
                                }else if ($row->lead_package_detail == 2) {
                                 $Package_name = 'Customized';
                                }else if ($row->lead_package_detail == 3) {
                                  $Package_name = 'Standard Customized';
                                }else{

                                }
                             
                    $html .= "<tr>
                            <td>".$i."</td>
                           <td>". $row->lead_acquired_date."</td>
                           
                           <td><a style='cursor: pointer;' class='full_description' data-id=".$row->id.">".$row->lead_author_name."</a></td>
                           <td>". $row->fullname. "</td>
                           <td>". $Package_name."</td>
                           <td>". $row->lead_book_type."</td>
                           <td>". $row->lead_packge_value."</td>
                           <td>". (($row->cost_of_additional_copy)*0.25) ."</td>
                           
                           
                           <td>". ($row->lead_packge_value + (($row->cost_of_additional_copy)*0.25)) ."</td>
                           
                         
                          
                                </tr>";
                

    $i++;}
    echo $html; 

}
public function date_filter_acquisition($value='')
{
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $staff_id = $_SESSION['staff_user_id'];
    $pc_name =  $_POST['pc_name'];
    $staff_id = $_SESSION['staff_user_id'];


    if (is_admin() || is_headtrm() || $staff_id==34 || $staff_id==28) {
        $this->db->where('lead_acquired_date >=', $from_date);
        $this->db->where('lead_acquired_date <=', $to_date);
        $this->db->where('lead_category_id', 39);
        if($pc_name !='0'){
           $this->db->where('assigned',$pc_name);  
        }
        $this->db->where('createPackEmailStatus', 1);
        $this->db->where('lead_payment_status', 1); 
        $this->db->select('CONCAT(tbll.firstname, ' ." ". ', tbll.lastname) AS fullname,tblleads.*');
        $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
        

       
    }else{
        $this->db->where('lead_acquired_date >=', $from_date);
        $this->db->where('lead_acquired_date <=', $to_date);
        $this->db->where('lead_category_id ', 39);
        // if($pc_name !='0'){
        //    $this->db->where('assigned',$pc_name);  
        // }
        $this->db->where('assigned',$staff_id);
        $this->db->where('createPackEmailStatus',1);
        $this->db->select('CONCAT(tbll.firstname, ' ." ". ', tbll.lastname) AS fullname,tblleads.*');
        $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
        
    }  

    
    $result  = $this->db->get('tblleads')->result();
// echo $this->db->last_query();die;


     $html = "";
         $i=1;
            foreach($result as $row){
                
                $Package_name = '';
                                if ($row->lead_package_detail == 1) {
                                 $Package_name = 'Standard';
                                }else if ($row->lead_package_detail == 2) {
                                 $Package_name = 'Customized';
                                }else if ($row->lead_package_detail == 3) {
                                  $Package_name = 'Standard Customized';
                                }else{

                                }
         
                    $html .= "<tr>
                            <td>".$i."</td>
                           <td>". $row->lead_acquired_date."</td>
                           <td><a style='cursor: pointer;' class='full_description' data-id='".$row->id."'>".$row->lead_author_name."</a></td>";
                            if (is_admin() || is_headtrm() || $staff_id==34 || $staff_id==28) { 
                                $html .= "<td>". $row->fullname."</td>";
                      }
                     
                          $html .= "<td>". $Package_name."</td>
                           <td>". $row->lead_book_type."</td>
                           <td>". $row->lead_packge_value."</td>
                            <td>". $row->cost_of_additional_copy*0.25."</td>
                          <td>".($row->lead_packge_value + ($row->cost_of_additional_copy*0.25))."</td>
                          
                                </tr>";
                

    $i++;}
    echo $html;

}

                                                
public function exportCategoryWise(){
    $useraid = $this->session->userdata('staff_user_id');
    $data['useraid'] = $useraid;
    $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
    $this->db->where('assigned', $useraid);
    //$this->db->where('lead_category_id',5);
    //$this->db->where('lead_category_id',16);
    $this->db->where('(lead_category_id = 5 or lead_category_id = 16 or lead_category_id = 38)');
    // $this->db->or_where('lead_category_id',16);
    $this->db->order_by("lead_created_date","desc");
    //$this->db->limit(1500);
    $data['assignedleads_a_b'] = $this->db->get('tblleads')->result();
  
    
    
    
    $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
    $this->db->where('assigned', $useraid);
  
    $this->db->where('(lead_category_id = 32 or lead_category_id = 40)');
    $this->db->order_by("lead_created_date","desc");
    //$this->db->limit(1500);
    $data['assignedleads_np'] = $this->db->get('tblleads')->result();
     
    $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
    $this->db->where('assigned', $useraid);
    $this->db->where('lead_category_id',30);
    $this->db->order_by("lead_created_date","desc");
    //$this->db->limit(1500);
    $data['assignedleads_c'] = $this->db->get('tblleads')->result();
     
    
    $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
    $this->db->where('assigned', $useraid);
    $this->db->where('lead_category_id',41);
    $this->db->order_by("lead_created_date","desc");
    //$this->db->limit(1500);
    $data['assignedleads_scrap'] = $this->db->get('tblleads')->result();
  
     $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
    $this->db->where('assigned', $useraid);
    $this->db->where('lead_category_id',39);
    $this->db->order_by("lead_created_date","desc");
    //$this->db->limit(1500);
    $data['assignedleads_acquired'] = $this->db->get('tblleads')->result();
    
     $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
    $this->db->where('assigned', $useraid);
    $this->db->where('lead_category_id',null);
    $this->db->order_by("lead_created_date","desc");
    //$this->db->limit(1500);
    $data['assignedleads_noCategory'] = $this->db->get('tblleads')->result();
    

    
    $this->load->view('admin/leads/exportCategoryWise',$data);
    
}
    function import_previous_lead()

    {

        $data['statuses'] = $this->leads_model->get_status();

        $data['sources'] = $this->leads_model->get_source();

        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);

        $data['title'] = _l('import previous lead');

        $useraid = $this->session->userdata('staff_user_id');
        // $data['allleads'] = $this->leads_model->getallleads('tblleads_custom');
        $data['staff_role'] = $this->leads_model->getstaffrole('tblstaff', $useraid);


        $this->load->view('admin/leads/import_previous_lead', $data);

    }
    function upload_previousleads()

    {

        $this->leads_model->upload_previousleads();

        echo 'upload sucessfully';

    }
     public function exports_data1(){
             $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
            /*$this->db->select('tblleadsstatus.name as category_name,tblleads.id,tblleads.otherphonenumber,tblleads.lead_booktitle,tblleads.lead_bookformat,tblleads.description,tblleads.calling_objective,tblleads.assigned,tblleads.status,tblleads.lead_created_date,tblleads.email,tblleads.next_calling,tblleads.lead_adname,tblleads.lead_author_msstatus,tblleads.lead_author_name,tblleads.phonenumber,tblleads.created_at,tblleads.lead_ad_id,tblleads.lead_author_mslanguage,tblleads.leadId,tblleads.lead_category_id,tblleads.lead_publishedearlier,tblleads.lead_callingdate,tblleads.lead_reviewstatus,tblleads.lead_approve_current_date,tblleads.ImEx_leadRemarks,tblleads.ImEx_CreatedAt,tblleads.ImEx_NextcallingDate');
            $this->db->join('tblleadsstatus', 'tblleads.lead_category_id= tblleadsstatus.id');*/
            $this->db->order_by("lead_created_date","asc");
            $result = $this->db->get('tblleads')->result();
           
            $datas =array();
            foreach ($result as $alllead) { 
                if($alllead->lead_category_id == 5) { $cat ="A";} 
                if($alllead->lead_category_id == 16) {$cat ="B";} 
                if($alllead->lead_category_id == 38) {$cat ="B+";} 
                if($alllead->lead_category_id == 30) {$cat ="C";} 
                if($alllead->lead_category_id == 32) {$cat ="NP";} 
                if($alllead->lead_category_id == 39) {$cat ="Acquired";} 
                if($alllead->lead_category_id == 40) {$cat ="UnAttended";} 
                if($alllead->lead_category_id == 41) {$cat ="Scrap";}
                $my = array('DB Id'=>$alllead->id,'Author Name'=>$alllead->lead_author_name,'Contact No 1'=>$alllead->phonenumber,'Contact No 2'=>$alllead->otherphonenumber,'Email Id'=>$alllead->email,'Book Language'=>$alllead->lead_author_mslanguage,'Manuscript Status'=>$alllead->lead_author_msstatus,'Remarks'=>$alllead->description,'Calling Date'=>$alllead->lead_callingdate,'Category'=>$cat,'Next Calling Date'=>$alllead->next_calling,'Book Format'=>$alllead->lead_bookformat,'Book Title'=>$alllead->lead_booktitle,'Created Date'=>$alllead->lead_created_date,'Ad Name'=>$alllead->lead_adname,'Published Earlier'=>$alllead->lead_publishedearlier);
                array_push($datas,$my);  
            }
            
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"test".".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
    
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array("DB Id","Sr. No.","Author Name","Contact No 1","Contact No 2","Email Id","Book Language","Manuscript Status",'Remarks','Calling Date','Category','Next Calling Date','Book Format','Book Title','Created Date','Ad Name','Published Earlier'));
            $cnt=1;
            foreach ($datas as $key) {
                $narray=array($key["DB Id"],$cnt++,$key["Author Name"],$key["Contact No 1"],$key["Contact No 2"],$key["Email Id"],$key["Book Language"],$key["Manuscript Status"],$key["Remarks"],$key["Calling Date"],$key["Category"],$key["Next Calling Date"],$key["Book Format"],$key["Book Title"],$key["Created Date"],$key["Ad Name"],$key["Published Earlier"]);
                fputcsv($handle, $narray);
            }
            fclose($handle);
            exit;
        }
     public function exports_data(){
         $useraid = $this->session->userdata('staff_user_id');
             $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
            /*$this->db->select('tblleadsstatus.name as category_name,tblleads.id,tblleads.otherphonenumber,tblleads.lead_booktitle,tblleads.lead_bookformat,tblleads.description,tblleads.calling_objective,tblleads.assigned,tblleads.status,tblleads.lead_created_date,tblleads.email,tblleads.next_calling,tblleads.lead_adname,tblleads.lead_author_msstatus,tblleads.lead_author_name,tblleads.phonenumber,tblleads.created_at,tblleads.lead_ad_id,tblleads.lead_author_mslanguage,tblleads.leadId,tblleads.lead_category_id,tblleads.lead_publishedearlier,tblleads.lead_callingdate,tblleads.lead_reviewstatus,tblleads.lead_approve_current_date,tblleads.ImEx_leadRemarks,tblleads.ImEx_CreatedAt,tblleads.ImEx_NextcallingDate');
            $this->db->join('tblleadsstatus', 'tblleads.lead_category_id= tblleadsstatus.id');*/
             if(!(is_admin())){
                
               $this->db->where('assigned', $useraid);
             }else{
                
            }
            $this->db->order_by("lead_created_date","asc");
            $result = $this->db->get('tblleads')->result();
           
            $datas =array();
            foreach ($result as $alllead) { 
                if($alllead->lead_category_id == 5) { $cat ="A";} 
                else if($alllead->lead_category_id == 16) {$cat ="B";} 
                 else if($alllead->lead_category_id == 38) {$cat ="B+";} 
                 else if($alllead->lead_category_id == 30) {$cat ="C";} 
                 else if($alllead->lead_category_id == 32) {$cat ="NP";} 
                 else if($alllead->lead_category_id == 39) {$cat ="Acquired";} 
                 else if($alllead->lead_category_id == 40) {$cat ="UnAttended";} 
                 else if($alllead->lead_category_id == 41) {$cat ="Scrap";}
                 else{$cat ='';}
                $my = array('DB Id'=>$alllead->id,'Author Name'=>$alllead->lead_author_name,'Contact No 1'=>$alllead->phonenumber,'Contact No 2'=>$alllead->otherphonenumber,'Email Id'=>$alllead->email,'Book Language'=>$alllead->lead_author_mslanguage,'Manuscript Status'=>$alllead->lead_author_msstatus,'Remarks'=>$alllead->description,'Calling Date'=>$alllead->lead_callingdate,'Category'=>$cat,'Next Calling Date'=>$alllead->next_calling,'Book Format'=>$alllead->lead_bookformat,'Book Title'=>$alllead->lead_booktitle,'Created Date'=>$alllead->lead_created_date,'Ad Name'=>$alllead->lead_adname,'Published Earlier'=>$alllead->lead_publishedearlier);
                array_push($datas,$my);  
            }
            
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"test".".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
    
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array("DB Id","Sr. No.","Author Name","Contact No 1","Contact No 2","Email Id","Book Language","Manuscript Status",'Remarks','Calling Date','Category','Next Calling Date','Book Format','Book Title','Created Date','Ad Name','Published Earlier'));
            $cnt=1;
            foreach ($datas as $key) {
                $narray=array($key["DB Id"],$cnt++,$key["Author Name"],$key["Contact No 1"],$key["Contact No 2"],$key["Email Id"],$key["Book Language"],$key["Manuscript Status"],$key["Remarks"],$key["Calling Date"],$key["Category"],$key["Next Calling Date"],$key["Book Format"],$key["Book Title"],$key["Created Date"],$key["Ad Name"],$key["Published Earlier"]);
                fputcsv($handle, $narray);
            }
            fclose($handle);
            exit;
        }
        
        
         public function filter_export(){
            
            $task_type = $_POST['tasktypefilter'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $staff_name = $_POST['staff_name'];
        // $stf = $_POST['staff_name'];
         $useraid = $this->session->userdata('staff_user_id');
         if(is_admin()){
            $this->db->where_in('tblleads.assigned', $staff_name);
         }else{
               $this->db->where_in('tblleads.assigned', $useraid);
         }
     
        if (isset($task_type)) {
            
            $this->db->where_in('tblleads.lead_category_id', $task_type);
        }
        
        if (isset($end_date) && $end_date != null  ) 
        {

            $this->db->where('tblleads.lead_created_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
        
            $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
           
     
           
            $this->db->order_by("lead_created_date","asc");
            
            
            
            
            //   $this->db->select('id,otherphonenumber,lead_booktitle,lead_bookformat,name,description,calling_objective,assigned,status,lead_created_date,email,next_calling,lead_adname,lead_author_msstatus,email,lead_author_name,phonenumber,created_at,lead_ad_id,lead_author_mslanguage,leadId,lead_category_id,lead_publishedearlier,lead_callingdate,lead_reviewstatus,lead_approve_current_date,ImEx_leadRemarks,ImEx_CreatedAt');
            /*$this->db->select('tblleadsstatus.name as category_name,tblleads.id,tblleads.otherphonenumber,tblleads.lead_booktitle,tblleads.lead_bookformat,tblleads.description,tblleads.calling_objective,tblleads.assigned,tblleads.status,tblleads.lead_created_date,tblleads.email,tblleads.next_calling,tblleads.lead_adname,tblleads.lead_author_msstatus,tblleads.lead_author_name,tblleads.phonenumber,tblleads.created_at,tblleads.lead_ad_id,tblleads.lead_author_mslanguage,tblleads.leadId,tblleads.lead_category_id,tblleads.lead_publishedearlier,tblleads.lead_callingdate,tblleads.lead_reviewstatus,tblleads.lead_approve_current_date,tblleads.ImEx_leadRemarks,tblleads.ImEx_CreatedAt,tblleads.ImEx_NextcallingDate');
            $this->db->join('tblleadsstatus', 'tblleads.lead_category_id= tblleadsstatus.id');*/
            // $this->db->order_by("lead_created_date","asc");
            $result = $this->db->get('tblleads')->result();
           $datas =array();
            $my =array("DB Id"=>"DB Id","Sr. No."=>"Sr. No.","Author Name"=>"Author Name","Contact No 1"=>"Contact No 1","Contact No 2"=>"Contact No 2","Email Id"=>"Email Id","Book Language"=>"Book Language","Manuscript Status"=>"Manuscript Status","Remarks"=>"Remarks",'Calling Date'=>'Calling Date','Category'=>'Category','Next Calling Date'=>'Next Calling Date','Book Format'=>'Book Format','Book Title'=>'Book Title','Created Date'=>'Created Date','Ad Name'=>'Ad Name','Published Earlier'=>'Published Earlier');
             array_push($datas,$my);
             $i = 1;
            foreach ($result as $alllead) { 
                if($alllead->lead_category_id == 5) { $cat ="A";} 
                else if($alllead->lead_category_id == 16) {$cat ="B";} 
                else if($alllead->lead_category_id == 38) {$cat ="B+";} 
                else if($alllead->lead_category_id == 30) {$cat ="C";} 
                else if($alllead->lead_category_id == 32) {$cat ="NP";} 
                else if($alllead->lead_category_id == 39) {$cat ="Acquired";} 
                else if($alllead->lead_category_id == 40) {$cat ="UnAttended";} 
                else if($alllead->lead_category_id == 41) {$cat ="Scrap";}
                else{
                    $cat =" ";
                }
                 $searchForValue = ',';
                
                if( strpos($alllead->lead_adname, $searchForValue) !== false ) {
              $alllead->lead_adname = preg_replace('/[,]+/', '-', trim($alllead->lead_adname));
                }else{
                    $alllead->lead_adname = $alllead->lead_adname;
                }
                if( strpos($alllead->description, $searchForValue) !== false ) {
              $alllead->description = preg_replace('/[,]+/', '-', trim($alllead->description));
                }else{
                    $alllead->description = $alllead->description;
                }
                
                // $dataArr[] = array('DB Id'=> $alllead->id, 'l_name'=> $alllead->lead_author_name);
                $my = array('DB Id'=>$alllead->id,'Sr. No.'=>$i,'Author Name'=>$alllead->lead_author_name,'Contact No 1'=>$alllead->phonenumber,'Contact No 2'=>$alllead->otherphonenumber,'Email Id'=>$alllead->email,'Book Language'=>$alllead->lead_author_mslanguage,'Manuscript Status'=>$alllead->lead_author_msstatus,'Remarks'=>$alllead->description,'Calling Date'=>$alllead->lead_callingdate,'Category'=>$cat,'Next Calling Date'=>$alllead->next_calling,'Book Format'=>$alllead->lead_bookformat,'Book Title'=>$alllead->lead_booktitle,'Created Date'=>$alllead->lead_created_date,'Ad Name'=>$alllead->lead_adname,'Published Earlier'=>$alllead->lead_publishedearlier);
                array_push($datas,$my);  
            $i++;}
            //  $dataArr[] ='';
             
          $myJSON = json_encode($datas);

echo $myJSON;

        }
        public function contact_filter_export(){
            $this->db2 = $this->load->database('secend_db', TRUE);    
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
          
            if (isset($end_date) && $end_date != null  ){
                 $this->db2->where('created_at BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
                //$this->db2->where('contacts.created_at BETWEEN "'.$start_date.'" and "'. $end_date.'"');
            }
            $this->db2->select('*');
            $this->db2->order_by("created_at","asc");
            $result = $this->db2->get('refer_friends')->result();
            $datas =array();
            $my =array("Sr. No."=>"Sr. No.","Name"=>"Name","Phone No"=>"Phone No","Email Id"=>"Email Id",'Created Date'=>'Created Date');
            array_push($datas,$my);
            $i = 1;
            foreach ($result as $alllead) { 
                $my = array('Sr. No.'=>$i,'Name'=>$alllead->name,'Phone No'=>$alllead->phone,'Email Id'=>$alllead->email,'Created Date'=>$alllead->created_at);
                array_push($datas,$my);  
                $i++;
            }
           // print_r($datas);exit;
            $myJSON = json_encode($datas);
            echo $myJSON;

        }
         public function filter(){
        $auname = $this->input->post('auname');

        $phno = $this->input->post('phno');
        $adname = $this->input->post('adname');
        $ms_status = $this->input->post('ms_status');
        $pub_early = $this->input->post('pub_early');
        $email = $this->input->post('email');
        $lang = $this->input->post('lang');
        $call_date = $this->input->post('call_date');
        $cate = $this->input->post('cate');
        $nc_date = $this->input->post('nc_date');
        $create_date = $this->input->post('create_date');
        $formate = $this->input->post('formate');
        $title = $this->input->post('title');
        $other_phno = $this->input->post('other_phno');


       
        $val = $this->leads_model->getSimilarData($auname,$phno,$adname,$ms_status,$pub_early,$email,$lang,$call_date,$cate,$nc_date,$create_date,$formate,$title,$other_phno);
        // print_r($val);die;
        $data['data'] = $val;
        

        echo json_encode(['code'=>200, 'msg'=>$data]);

    }
       public function gsearch(){
        $key = $this->input->post('key');
        $val = $this->leads_model->globalSearch($key);
        $data['data'] = $val;
        echo json_encode(['code'=>200, 'msg'=>$data]);
    }
      public function filter_by_all($value='')
    {
           $useraid = $this->session->userdata('staff_user_id');
            
            $task_type = $_POST['tasktypefilter'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $staff_name = $_POST['staff_name'];
        // $stf = $_POST['staff_name'];
           // $key = $this->input->post('key');
        $val = $this->leads_model->filter_by_all();
        $data['data'] = $val;
        echo json_encode(['code'=>200, 'msg'=>$data]);
    }
    public function clear_filter(){
        $this->session->unset_userdata('search_global');
         $this->session->unset_userdata('category_type');
          $this->session->unset_userdata('start_date');
       $this->session->unset_userdata('end_date');
       $this->session->unset_userdata('staff_name');
     //$this->session->set_userdata(array("search_global"=>''));
      redirect('admin/leads/assignedleads_array', 'refresh');
    }

     public function clear_filter_tran(){
        $this->session->unset_userdata('search_global');
         $this->session->unset_userdata('category_type');
          $this->session->unset_userdata('start_date');
       $this->session->unset_userdata('end_date');
       $this->session->unset_userdata('staff_name');
     //$this->session->set_userdata(array("search_global"=>''));
      redirect('admin/leads/transferred_leads', 'refresh');
    }
    public function contact_data_clear_filter(){
        $this->session->unset_userdata('search_global');
         $this->session->unset_userdata('category_type');
          $this->session->unset_userdata('start_date');
       $this->session->unset_userdata('end_date');
       $this->session->unset_userdata('staff_name');
     //$this->session->set_userdata(array("search_global"=>''));
      redirect('admin/leads/websitecontact', 'refresh');
    }
    public function assignedleads_array(){
        // $this->session->set_userdata($newdata);
        //print_r($_POST);die;
       
          $search_text = "";
        if($this->input->post('submit') != NULL ){
          $search_text = $this->input->post('search_global');
          $this->session->set_userdata(array("search_global"=>$search_text));
        }else{
          if($this->session->userdata('search_global') != NULL){
            $search_text = $this->session->userdata('search_global');
          }else{
          }
        }
        $search_cat = "";
         $start_date = "";
          $end_date = "";
          if($this->input->post('submit_cat') != NULL ){
          $staff_name = $this->input->post('staff_name');
          $search_cat = $this->input->post('category_type');
          $this->session->set_userdata(array("category_type"=>$search_cat));
          $this->session->set_userdata(array("staff_name"=>$staff_name));
           $start_date = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          $this->session->set_userdata(array("start_date"=>$start_date,"end_date"=>$end_date));
        }else{
          if(($this->session->userdata('category_type') != NULL) ||  ($this->session->userdata('staff_name') != NULL) || ($this->session->userdata('start_date') != NULL) ){
            $staff_name = $this->session->userdata('staff_name');
            $search_cat = $this->session->userdata('category_type');
              $start_date = $this->session->userdata('start_date');
            $end_date = $this->session->userdata('end_date');
          }else{
          }
        }

        $useraid = $this->session->userdata('staff_user_id');
        $data['useraid'] = $useraid;
        $data['get_staff'] = $this->leads_model->get_pc();
        $data['get_task_type'] = $this->leads_model->get_pc_category();
        $allcount = $this->leads_model->getrecordCount($search_text,$search_cat,$start_date,$end_date,$staff_name);
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() .'admin/leads/assignedleads_array/';
        $config['total_rows'] =$allcount;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['leads'] =  $this->leads_model->leedsData($config["per_page"], $page ,$search_text,$search_cat,$start_date,$end_date,$staff_name);
        $data['bodyclass'] = 'hide-sidebar';
         $data['search'] = $search_text;
         $data['search_cat'] = $search_cat;
         $data['start_date'] = $start_date;
         $data['end_date'] = $end_date;
         $data['staff_name'] = $staff_name;
        if(empty($this->uri->segment(4))){
             $curpage=$this->uri->segment(3); 
            // echo "test";
            $result_start=1; 
            $result_end=$curpage+$config['per_page'];
        }
        else {
            $curpage=$this->uri->segment(4); 
            // echo "hii";
            $result_start=$curpage+1;
            $result_end=$curpage+$config['per_page'];
            if($result_end>$allcount){$result_end=$allcount;}
        }
        $data['pagination_number'] = "Displaying ".$result_start." to ".$result_end." of ".$allcount;
        $this->load->view('admin/leads/viewassignedleads_array', $data);
    }


    public function transferred_leads(){
        // $this->session->set_userdata($newdata);
        //print_r($_POST);die;
       
          $search_text = "";
        if($this->input->post('submit') != NULL ){
          $search_text = $this->input->post('search_global');
          $this->session->set_userdata(array("search_global"=>$search_text));
        }else{
          if($this->session->userdata('search_global') != NULL){
            $search_text = $this->session->userdata('search_global');
          }else{
          }
        }
        $search_cat = "";
         $start_date = "";
          $end_date = "";
          if($this->input->post('submit_cat') != NULL ){
          $staff_name = $this->input->post('staff_name');
          $search_cat = $this->input->post('category_type');
          $this->session->set_userdata(array("category_type"=>$search_cat));
          $this->session->set_userdata(array("staff_name"=>$staff_name));
           $start_date = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          $this->session->set_userdata(array("start_date"=>$start_date,"end_date"=>$end_date));
        }else{
          if(($this->session->userdata('category_type') != NULL) ||  ($this->session->userdata('staff_name') != NULL) || ($this->session->userdata('start_date') != NULL) ){
            $staff_name = $this->session->userdata('staff_name');
            $search_cat = $this->session->userdata('category_type');
              $start_date = $this->session->userdata('start_date');
            $end_date = $this->session->userdata('end_date');
          }else{
          }
        }

        $useraid = $this->session->userdata('staff_user_id');
        $data['useraid'] = $useraid;
        $data['get_staff'] = $this->leads_model->get_pc();
        $data['get_task_type'] = $this->leads_model->get_pc_category();
        $allcount = $this->leads_model->getTransferRecordCount($search_text,$search_cat,$start_date,$end_date,$staff_name);
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() .'admin/leads/transferred_leads/';
        $config['total_rows'] =$allcount;
        $config['per_page'] = 1000;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['leads'] =  $this->leads_model->transferLeadsData($config["per_page"], $page ,$search_text,$search_cat,$start_date,$end_date,$staff_name);
        // echo "<pre>";
        //  print_r($this->db->last_query());
        //  die();
        $data['bodyclass'] = 'hide-sidebar';
         $data['search'] = $search_text;
         $data['search_cat'] = $search_cat;
         $data['start_date'] = $start_date;
         $data['end_date'] = $end_date;
         $data['staff_name'] = $staff_name;
        if(empty($this->uri->segment(4))){
             $curpage=$this->uri->segment(3); 
            // echo "test";
            $result_start=1; 
            $result_end=$curpage+$config['per_page'];
        }
        else {
            $curpage=$this->uri->segment(4); 
            // echo "hii";
            $result_start=$curpage+1;
            $result_end=$curpage+$config['per_page'];
            if($result_end>$allcount){$result_end=$allcount;}
        }
        $data['pagination_number'] = "Displaying ".$result_start." to ".$result_end." of ".$allcount;
        $this->load->view('admin/leads/transferred_leads_pc', $data);
    }
    public function websitecontact($value='')
    {
        /*$this->db2 = $this->load->database('secend_db', TRUE);
        $this->db2->order_by("created_at", "desc");
        $this->db2->select('*');
        $data = $this->db2->get('contacts')->result();
        $data['contacts'] =$data;
        $this->db2->order_by("created_at", "desc");
        $this->db2->select('*');
        $data1 = $this->db2->get('refer_friends')->result();
        $data['refer_friends'] = $data1;
        $this->load->view('admin/leads/website_contact', $data);*/
        if($this->input->post('submit') != NULL ){
          $search_text = $this->input->post('search_global');
          $this->session->set_userdata(array("search_global"=>$search_text));
        }else{
          if($this->session->userdata('search_global') != NULL){
            $search_text = $this->session->userdata('search_global');
          }else{
          }
        }
        $search_cat = "";
         $start_date = "";
          $end_date = "";
          if($this->input->post('submit_cat') != NULL ){
          $staff_name = $this->input->post('staff_name');
          $search_cat = $this->input->post('category_type');
          $this->session->set_userdata(array("category_type"=>$search_cat));
          $this->session->set_userdata(array("staff_name"=>$staff_name));
           $start_date = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          $this->session->set_userdata(array("start_date"=>$start_date,"end_date"=>$end_date));
        }else{
          if(($this->session->userdata('category_type') != NULL) ||  ($this->session->userdata('staff_name') != NULL) || ($this->session->userdata('start_date') != NULL) ){
            $staff_name = $this->session->userdata('staff_name');
            $search_cat = $this->session->userdata('category_type');
              $start_date = $this->session->userdata('start_date');
            $end_date = $this->session->userdata('end_date');
          }else{
          }
        }
        
        $useraid = $this->session->userdata('staff_user_id');
        $data['useraid'] = $useraid;
      
        $allcount = $this->leads_model->getcontactCount($search_text,$search_cat,$start_date,$end_date,$staff_name);
        $this->load->library('pagination');
        $config = array();
        //https://bfcgroup.in/admin/leads/websitecontact
        $config['base_url'] =  base_url() .'admin/leads/websitecontact/';
        $config['total_rows'] =$allcount;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['leads'] =  $this->leads_model->contactData($config["per_page"], $page ,$search_text,$search_cat,$start_date,$end_date,$staff_name);
        //$data['bodyclass'] = 'hide-sidebar';
         $data['search'] = $search_text;
         $data['search_cat'] = $search_cat;
         $data['start_date'] = $start_date;
         $data['end_date'] = $end_date;
         $data['staff_name'] = $staff_name;
        if(empty($this->uri->segment(4))){
         $curpage=$this->uri->segment(3); 
        // echo "test";
        $result_start=1; 
        $result_end=$curpage+$config['per_page'];
        }
        else {
        $curpage=$this->uri->segment(4); 
        // echo "hii";
        $result_start=$curpage+1;
        $result_end=$curpage+$config['per_page'];
        if($result_end>$allcount){$result_end=$allcount;}
        }
        $data['pagination_number'] = "Displaying ".$result_start." to ".$result_end." of ".$allcount;
        //echo "<pre>";
        //print_r($data);exit;
        $this->load->view('admin/leads/website_contact', $data);
    }
    public function add_domain_dtl(){
        $data['title'] = _l('Add Domail Purchase details');
        $this->load->view('admin/leads/add_domain_dtl', $data);
      }
    public function saveDomainDtl(){
        $expairy_date = $_GET['expairy_date'];
        $author_name =$_GET['author_name'];
        $domarin_url = $_GET['domarin_url'];
        $purchase_platform = $_GET['purchase_platform'];
        $pm_name = $_GET['pm_name'];
        $data = array('author_name' => $author_name, 'domain_url' => $domarin_url, 'expairy_date' => $expairy_date,'platform_type'=>$purchase_platform,'pm_name'=> $pm_name);
        $this->db->insert('domain_details', $data);
        $data['success'] = "success";
        set_alert('success', "Save Successfully");
        return redirect(admin_url('leads/add_domain_dtl'));
    }
    public function send_php_mail()
        {
             
        // Load PHPMailer library
        $this->load->library('phpmailer_lib');
        
        // PHPMailer object
        $mail = $this->phpmailer_lib->load();

        $name = 'test-name';
        $email = 'vineet161631@gmail.com';
        $subject = 'test-subject';  
        $msg = 'test-msg 213245dsfjgkljg353';

            $mail->IsSMTP();

            $mail->SMTPDebug  = 0;
            $mail->SMTPAuth   = TRUE;
            $mail->SMTPSecure = "tls";
            $mail->Port       = 587;
            $mail->Host       = "smtp.gmail.com";
            $mail->Username   = 'stormjackson908@gmail.com';
            $mail->Password   = '12345';

            $mail->IsHTML(true);
            $mail->AddAddress("rajeshgupta@gmail.com", "vineet");
            $mail->SetFrom('stormjackson908@gmail.com', "BFC Publications");
            $mail->AddReplyTo('vineet@12345', 'VInet');
            // $mail->AddCC("cc-recipient-email", "cc-recipient-name");
            $mail->Subject = 'Query from Website';
            $content = "Dear Mr. ABC";
            $content .= "<br/>You Have Recieved a Query From Your Website";
            $content .= "<br/>Details are Given Below<br/>";
            $content .=
                        "<br/>
                        <table>
                       
                        </table>";

            $mail->MsgHTML($content);
            if ($mail->Send()) {
                echo '<script>alert("Your Query Has Been Submitted Successfully.");</script>';
            } else {
                echo $mail->ErrorInfo;
            }
            }

            function import_create_package()

            {
        
                $data['statuses'] = $this->leads_model->get_status();
        
                $data['sources'] = $this->leads_model->get_source();
        
                $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);
        
                $data['title'] = _l('import previous lead');
        
                $useraid = $this->session->userdata('staff_user_id');
                // $data['allleads'] = $this->leads_model->getallleads('tblleads_custom');
                $data['staff_role'] = $this->leads_model->getstaffrole('tblstaff', $useraid);
        
        
                $this->load->view('admin/leads/import_previous_lead1', $data);
        
            }
            function upload_create_package()
        
            {
        
                $this->leads_model->upload_create_package();
        
                echo 'upload sucessfully';
        
            }
            public function ask_faq(){

        //     $this->db->from("faq");
        // $this->db->join("tblstaff",'faq.question_by = tblstaff.staffid');
        // $query=$this->db->get();
        // $data['project_data'] =$query->result(); 
        if($this->input->post('submit') != NULL ){
            $search_text = $this->input->post('search_global');
            $this->session->set_userdata(array("search_global"=>$search_text));
          }else{
            if($this->session->userdata('search_global') != NULL){
              $search_text = $this->session->userdata('search_global');
            }else{
            }
          }




        $allcount = $this->leads_model->getfaqCount($search_text);
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() .'admin/leads/ask_faq/';
        $config['total_rows'] =$allcount;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['project_data'] =  $this->leads_model->faq_Data($config["per_page"], $page,$search_text);
        $data['bodyclass'] = 'hide-sidebar';
        if(empty($this->uri->segment(4))){
            $curpage=$this->uri->segment(3); 
           // echo "test";
           $result_start=1; 
           $result_end=$curpage+$config['per_page'];
       }
       else {
           $curpage=$this->uri->segment(4); 
           // echo "hii";
           $result_start=$curpage+1;
           $result_end=$curpage+$config['per_page'];
           if($result_end>$allcount){$result_end=$allcount;}
       }
         $data['pagination_number'] = "Displaying ".$result_start." to ".$result_end." of ".$allcount;
         $data['search'] = $search_text;





        
                // $data['project_data'] = $this->db->get('faq')->result();
                $this->load->view('admin/leads/ask_faq', $data);
            }
            public function upload_ask_q(){
                $staff_id = $_SESSION['staff_user_id'];
                $data = array(
                    'question'=>$this->input->post('ask_question'),
                    'question_by'=>$staff_id
                );
                $this->db->insert('faq',$data);
                set_alert('success', _l('Question upload successfully...'));
                redirect($_SERVER['HTTP_REFERER']);
            }
            public function save_answer(){
              
                $id = $_POST['hidden_id'];
                $answer = $_POST['answer'];
                $filename = $_FILES['file']['name'];
            
             
                $ckeck_image = $this->db->get_where('faq',array('id'=>$id))->row();
                if ($filename) {
                  
                    if($ckeck_image->file){
                        unlink('assets/faq_file/'.$ckeck_image->file);
                    }
                
                    
                $location = "assets/faq_file/".$filename;
                $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
                $imageFileType = strtolower($imageFileType);
    
                /* Valid extensions */
                //$valid_extensions = array("jpg","jpeg","png","");
                $response = 0;
                //if(in_array(strtolower($imageFileType), $valid_extensions)) {
                if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                 $response = $location;
                }
                $data_array = array(
                    'answer' => $answer,
                    'file' => $filename,
                );
                // print_r($data_array);die;
                $this->db->where('id',$id);
                $this->db->update('faq',$data_array);
                set_alert('success', _l('Answer upload successfully...'));
                redirect($_SERVER['HTTP_REFERER']);
            }else{
                $data_array = array(
                    'answer' => $answer,
                );
                // print_r($data_array);die;
                $this->db->where('id',$id);
                $this->db->update('faq',$data_array);
                set_alert('success', _l('Answer upload successfully...'));
                redirect($_SERVER['HTTP_REFERER']);
            }
            set_alert('fail', _l('Something went wrong...'));
            redirect($_SERVER['HTTP_REFERER']);
            }
            public function clear_filter_faq(){
                $this->session->unset_userdata('search_global');
             //$this->session->set_userdata(array("search_global"=>''));
              redirect('admin/leads/ask_faq', 'refresh');
            }

    public function allleadremark3()
    {
       $lead_idnew = $_POST['id'];
        $lead_name = $_POST['name'];
        $data['lead_id'] = $_POST['id'];
        $lead_status = '';
        $lead_description = '';
        // $this->db->order_by("created_on", "asc");
        $this->db->where('lead_id', $lead_idnew);
        // $this->db->group_by("remark");
        // $data['allleadremark'] = array();
        $data['allleadremark'] = $this->db->get(' tblleadremark')->result();

        // print_r($data['allleadremark']);die;

        $this->db->where('id', $lead_idnew);
        $data['all_lead_data'] = $this->db->get('tblleads')->row();
        $data['lead_id'] = $lead_idnew;
        $data['name'] = $lead_name;
        $data['status'] = $lead_status;
        $data['assigned'] = $_POST['assigned'];
        $data['next_calling'] = $_POST['next_calling'];
         $data['next_callingd'] =  "n11nnnhjghjgh".$_POST['next_callingd'];
        $data['publishedEarlier'] = '';
        $data['phonenumber'] = $_POST['phonenumber'];
        
        $data['booktitle'] = '';
        $data['book_format'] = '';
        $data['manuscript'] = '';
        $data['language'] = '';
        $data['email'] = $_POST['email'];
        
        $data['otherphonenumber'] = $_POST['otherphonenumber'];
        $data['lstatus'] = $this->leads_model->get_leadstatus2('tblleadsstatus');
        $data['categoryStatus'] = '';
    
        $data['description'] = $lead_description;
        $data['wplistlead'] = $this->leads_model->get_availwplead();
    
        $data['status'] = $_POST['category'];
//print_r($data);
        $this->load->view('admin/leads/viewremark_otherBook', $data); 
        
    }
    public function update_custom_lead_remark_other_book()
   {
       //   print_r($_POST);
          $id = $_POST['id'];
       // echo $id = $_POST['id'];
       // print_r($_FILES);  exit;
           $filename = $_FILES['file']['name'];
           // echo $filename;die;
           $ckeck_image = $this->db->get_where('tblleads',array('id'=>$id))->row();
           if ($filename) {
             
               if($ckeck_image->lead_payment_reciept){
                   unlink('assets/images/payment_receipt/'.$ckeck_image->lead_payment_reciept);
               }
           
               
           $location = "assets/images/payment_receipt/".$filename;
           $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
           $imageFileType = strtolower($imageFileType);

           // Valid extensions 
           //$valid_extensions = array("jpg","jpeg","png","");
           $response = 0;
           //if(in_array(strtolower($imageFileType), $valid_extensions)) {
           if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
            $response = $location;
           }
          }
           
       $id = $_POST['id'];
       $booktitle = $_POST['booktitle'];
       $PublishedEarlier = $_POST['publishedEarlier'];
      
       $remark = $_POST['description'];
       $categorisation = $_POST['status'];
       $book_format = $_POST['book_format'];
       $manuscriptStatus = $_POST['manuscriptStatus'];
       $bookLanguage = $_POST['bookLanguage'];
       
        $package_cost = $_POST['package_cost'];
       $booking_amount = $_POST['booking_amount'];
       $finstallment = $_POST['finstallment'];
       $final_payment = $_POST['final_payment'];

        if($location){
            $data = array(
                'leadid' => $id,
                'book_formate' => $book_format,
                'book_title' => $booktitle,
                'ms_status' => $manuscriptStatus,
                'book_language' => $bookLanguage,
                'published_earlier' => $PublishedEarlier,
                'category' => $categorisation,
                'package_cost' => $package_cost,
                'lead_booking_amount' => $booking_amount,
                'lead_first_installment' => $finstallment,
                'lead_final_payment' => $final_payment,
                'remark' => $remark,
                'receipt' => $location
            );

        }else{
            $data = array(
                'leadid' => $id,
                'book_format' => $book_format,
                'book_title' => $booktitle,
                'ms_status' => $manuscriptStatus,
                'book_language' => $bookLanguage,
                'published_earlier' => $PublishedEarlier,
                'category' => $categorisation,
                'lead_package_cost' => $package_cost,
                'lead_booking_amount' => $booking_amount,
                'lead_first_installment' => $finstallment,
                'lead_final_payment' => $final_payment,
                'remark' => $remark
            );
        }
        $check = $this->db->select('book_title')->where('book_title',$booktitle)->get('tblleads_create_package');
        // print_r($check->num_rows());die;
        if($check->num_rows()>0){
            echo 'sameTitle';
            return 'sameTitle';
            die;
        }
        $this->db->insert('tblleads_create_package', $data);
       

       $data['success'] = "success";
       set_alert('success', "Lead Updated Successfully");
       return $data;
   }
   public function update_other_book_details()
   {
        //  print_r($_POST);die;
          $id = $_POST['id'];
          $multiple_book_id = $_POST['multiple_book_id'];
       // echo $id = $_POST['id'];
    //    print_r($_FILES);  exit;
           $filename = $_FILES['file']['name'];
        //    echo $filename;die;
           $ckeck_image = $this->db->get_where('tblleads_create_package',array('id'=>$multiple_book_id))->row();
           if ($filename) {
             
               if($ckeck_image->receipt){
                   unlink('assets/images/payment_receipt/'.$ckeck_image->receipt);
               }
           
               
           $location = "assets/images/payment_receipt/".$filename;
           $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
           $imageFileType = strtolower($imageFileType);

           // Valid extensions 
           //$valid_extensions = array("jpg","jpeg","png","");
           $response = 0;
           //if(in_array(strtolower($imageFileType), $valid_extensions)) {
           if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
            $response = $location;
           }
          }
        //    print_r($location);die;
       $id = $_POST['id'];
       $booktitle = $_POST['booktitle'];
       $PublishedEarlier = $_POST['publishedEarlier'];
       
       $remark = $_POST['description'];
       $categorisation = $_POST['status'];
       $book_format = $_POST['book_format'];
       $manuscriptStatus = $_POST['manuscriptStatus'];
       $bookLanguage = $_POST['bookLanguage'];
       
        $package_cost = $_POST['package_cost'];
       $booking_amount = $_POST['booking_amount'];
       $finstallment = $_POST['finstallment'];
       $final_payment = $_POST['final_payment'];
       $gst_number = $_POST['gst_number'];
       $state = $_POST['state'];

        if($location){
            $data = array(
                'leadid' => $id,
                'book_formate' => $book_format,
                'book_title' => $booktitle,
                'ms_status' => $manuscriptStatus,
                'book_language' => $bookLanguage,
                'published_earlier' => $PublishedEarlier,
                'category' => $categorisation,
                'lead_package_cost' => $package_cost,
                'lead_booking_amount' => $booking_amount,
                'lead_first_installment' => $finstallment,
                'lead_final_payment' => $final_payment,
                'remark' => $remark,
                'state_create_p' => $state,
                'lead_gst_number' => $gst_number,
                'receipt_path' => $location,
                'lead_status'=> 1,
                'assigned'=> $this->session->userdata('staff_user_id')
            );

        }else{
            $data = array(
                'leadid' => $id,
                'book_format' => $book_format,
                'book_title' => $booktitle,
                'ms_status' => $manuscriptStatus,
                'book_language' => $bookLanguage,
                'published_earlier' => $PublishedEarlier,
                'category' => $categorisation,
                'lead_package_cost' => $package_cost,
                'lead_booking_amount' => $booking_amount,
                'lead_first_installment' => $finstallment,
                'lead_final_payment' => $final_payment,
                'state_create_p' => $state,
                'lead_gst_number' => $gst_number,
                'remark' => $remark,
                'lead_status'=> 1,
                'assigned'=> $this->session->userdata('staff_user_id')
            );
        }
    //   print_r($data);die;
        $this->db->where('id',$multiple_book_id);
        $this->db->update('tblleads_create_package',$data);
        // echo $this->db->last_query();die;
       $data['success'] = "success";
       set_alert('success', "Lead Updated Successfully");
       return $data;
   }
   public function createOthPackage(){

    $data['url'] = $_SERVER['HTTP_REFERER'];
    $leadId = end($this->uri->segment_array());
    $data['leadId'] = end($this->uri->segment_array());
    $data['leadData'] = $this->leads_model->getLeadDataOtherPkg($leadId);
    $data['title'] = "CreateOtherPackage";
    $data['business'] = "";
   
    $this->load->view('admin/leads/createOtherPackage', $data);
}
public function multi_create_pack(){

    $data['url'] = $_SERVER['HTTP_REFERER'];
    $leadId = end($this->uri->segment_array());
    $data['leadId'] = end($this->uri->segment_array());
    $data['leadData'] = $this->leads_model->getLeadDatamultiPkg($leadId);
    $data['title'] = "CreateOtherPackage";
    $data['business'] = "";
//     echo "<pre>";
//    print_r($data);die;
    $this->load->view('admin/leads/createMultiPackage', $data);
}
public function createOtherPackage(){
    // print_r($_POST);die;
    $this->load->helper('url');
    $this->load->library('excel');
    $this->load->library('pdf');
    $this->load->config('email');
    $this->load->library('email');
    $this->load->helper('phpass');
    $url = $this->input->post('url');
    $no_of_page = $this->input->post('increment');
    $craete_package = $this->input->post('craete_package');
    $authorId = $this->input->post('author_id');
    $email = $this->input->post('email');
    $name = $this->input->post('author_name');
    if(!empty($this->input->post('package_name_data'))){
        $package__data = $this->input->post('package_name_data');
    }else{
        $package__data = $this->input->post('package_name');
    }
    if ($this->input->post('number_of_complimentary_copies_edit')) {
        $number_of_complimentary_copies = $this->input->post('number_of_complimentary_copies_edit');
    }else{
        $number_of_complimentary_copies = $this->input->post('number_of_complimentary_copies');
    }
    $filename = str_replace(' ', '', $name.'another_package').".pdf";
    $data['name'] = $name;
    $data['email'] = $email;
    $data['mobile'] = $this->input->post('mobile');
    $data['packagecost'] = $this->input->post('cost');

    $data['full_payment_disc'] = $this->input->post('full_payment');
        $data['total_amount_popup_pdf'] = $this->input->post('total_amount_popup_pdf');
        $data['booking_amount_popup_pdf'] = $this->input->post('booking_amount_popup_pdf');
        $data['booking_f_amount_popup_pdf'] = $this->input->post('booking_f_amount_popup_pdf');
        $data['booking_s_amount_popup_pdf'] = $this->input->post('booking_s_amount_popup_pdf');
        $data['create_p_offer'] =  $this->input->post('create_p_offer');
    if($this->input->post('package_details') == 1 ){
        $data['package_name'] = $this->input->post('package_name_data');
        $data['book_type'] = $this->input->post('book_type_value');
    }elseif($this->input->post('package_details') == 2){
        $data['book_cover_sc'] = $this->input->post('book_cover_c');
        $data['paper_type_sc'] = $this->input->post('paper_type_c');
        $data['book_size_sc'] = $this->input->post('book_size_c');
        $data['lamination_sc'] = $this->input->post('lamination_c');
        $data['complimentry_copies'] = $this->input->post('complimentry_copies');
        $data['number_of_pages'] = $this->input->post('increment');
        $data['package_name'] = $this->input->post('package_name');
        $data['additional_author_copy'] = $this->input->post('additional_author_copy_customized');
        $data['color_pages'] = $this->input->post('color_pages_customized');
        $data['book_type'] = $this->input->post('book_type_value');
    }else{
        $data['book_cover_sc'] = $this->input->post('book_cover_sc');
        $data['paper_type_sc'] = $this->input->post('paper_type_sc');
        $data['book_size_sc'] = $this->input->post('book_size_sc');
        $data['lamination_sc'] = $this->input->post('lamination_sc');
        $data['complimentry_copies'] = $number_of_complimentary_copies;
        $data['number_of_pages'] = $this->input->post('number_of_pages_for_sc');
        $data['package_name'] = $this->input->post('package_name_data_value');
        $data['book_type'] = $this->input->post('book_type');
    } 
    $data['package_value'] = $this->input->post('package_value');
    $data['discount'] = $this->input->post('discount');
    $data['less_pkg_value'] = $this->input->post('less_package_value');
    $data['gst'] = $this->input->post('gst');
    $data['total_amount'] = $this->input->post('total_amount');
    $data['msstatus'] = $this->input->post('msstatus');
    if($this->input->post('cost_of_additional')){
        $data['cost_of_additional_copy'] = $this->input->post('cost_of_additional') ;
    }
    if($this->input->post('additional_gross_amount')){
        $data['gross_amt'] = $this->input->post('additional_gross_amount') ;
    }
    if($this->input->post('package_details') == 3){
        $data['sub_services'] = implode(", ",$this->input->post('sub_services'));
        foreach ($this->input->post('sub_services') as $key => $value) {
            $service_get = $this->db->get_where('tblpackagesubservices',array('id'=>$value))->row();
            $cart[] = $service_get->serviceid;  
        }
        for ($i=0; $i < count($cart); $i++) { 
            if ($cart[$i] != $cart[$i+1]) {
                $service_array[] = $cart[$i];  
            }
        }
        $data['service'] = implode(", ",$service_array);
    }else{
        $data['service'] = implode(", ",$this->input->post('services'));
        $data['sub_services'] = implode(", ",$this->input->post('sub_services'));
    }
    $html = $this->load->view('admin/pdf/pdf', $data, true);
    $dompdf = new Dompdf\DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();
    $dompdf->set_paper('A4', 'landscape');
    $output = $dompdf->output();
    $filepath = 'assets/authorMail/'.$filename;
    file_put_contents($filepath, $output);
    if($this->input->post('package_details')==1){
        $services = implode(", ",$this->input->post('services'));
        $explodedStr = explode(", ", $services);
        $filteredArray = array_filter( $explodedStr );
        // Convert Array into String with comma delimiter 
        $servicess = implode(", ",  $filteredArray);
    }else if($this->input->post('package_details')==2){
        $servicess = implode(", ",$this->input->post('services'));
    }else if($this->input->post('package_details')==3) {
        $servicess = $data['service'];
    }
    if($this->input->post('package_details') == 1 ){
    if ($this->input->post('book_type_value') == 'ebook') {
        $lead_book_pages = 100;
    }else{
        if ($this->input->post('package_name_data') == 'essential') {
            $lead_book_pages = 100;
        }else if ($this->input->post('package_name_data') == 'regular') {
            $lead_book_pages = 150;
        }else  if ($this->input->post('package_name_data') == 'superior') {
            $lead_book_pages = 250;
        }else  if ($this->input->post('package_name_data') == 'premium') {
            $lead_book_pages = 400;
        }else  if ($this->input->post('package_name_data') == 'rapid') {
            $lead_book_pages = 400;
        }else{
            $lead_book_pages = 400;
        }
    }
        $data = array(
        'leadid' => $this->input->post('author_id'),
        'craete_package' => 1,
        'lead_author_name' => $name, 
        'lead_package_detail' => $this->input->post('package_details'),
        'lead_book_type' => $this->input->post('book_type_value'),
        'lead_package_name' => $this->input->post('package_name_data'), 
        'lead_service' => $servicess,//
        'lead_sub_service' => implode(", ",$this->input->post('sub_services')),//
        'lead_packge_value' => $this->input->post('package_value'),
        'lead_packge_discount' => $this->input->post('discount'),
        'lead_lesspckg_value' => $this->input->post('less_package_value'),
        'lead_packg_gst' => $this->input->post('gst'),
        'lead_packg_totalamount' => $this->input->post('total_amount'),
        'lead_book_pages' => $lead_book_pages,
        'create_p_offer'=> $this->input->post('create_p_offer'),
        'lead_pdf_data' => $filename
        );
    }elseif($this->input->post('package_details') == 2){
        $data = array(
        'leadid' => $this->input->post('author_id'),
        'craete_package' => 1,
        'lead_author_name' => $name, 
        'lead_package_detail' => $this->input->post('package_details'),
        'lead_book_type' => $this->input->post('book_type_value'),
        'lead_package_name' => $this->input->post('package_name'), 
        'lead_service' => $servicess,//
        'lead_ori_packge_value' => $this->input->post('lead_ori_packge_value'),
        'lead_sub_service' => implode(", ",$this->input->post('sub_services')),//
        'complimentry_copies' => $this->input->post('complimentry_copies'),
        'additional_author_copy' => $this->input->post('additional_author_copy_customized'),
        'color_pages' => $this->input->post('color_pages_customized'),
        'book_cover_sc' => $this->input->post('book_cover_c'),
        'paper_type_sc' => $this->input->post('paper_type_c'),
        'book_size_sc' => $this->input->post('book_size_c'),
        'lamination_sc' => $this->input->post('lamination_c'),
        'lead_packge_value' => $this->input->post('package_value'),
        'lead_packge_discount' => $this->input->post('discount'),
        'lead_lesspckg_value' => $this->input->post('less_package_value'),
        'lead_packg_gst' => $this->input->post('gst'),
        'lead_packg_totalamount' => $this->input->post('total_amount'),
        'lead_book_pages' => $no_of_page,
        'lead_pdf_data' => $filename,
        'cost_of_additional_copy' => $this->input->post('cost_of_additional'),
        'gross_amt' => $this->input->post('additional_gross_amount'),
        'create_p_offer'=> $this->input->post('create_p_offer'),
        );
    }else{
        $data = array(
        'leadid' => $this->input->post('author_id'),
        'craete_package' => 1,
        'lead_author_name' => $name, 
        'lead_package_detail' => $this->input->post('package_details'),
        'lead_ori_packge_value' => $this->input->post('lead_ori_packge_value'),
        'lead_book_type' => $this->input->post('book_type'),
        'lead_package_name' => $this->input->post('package_name_data_value'), 
        'lead_service' => $servicess,
        'lead_sub_service' => implode(", ",$this->input->post('sub_services')),
        'complimentry_copies' => $number_of_complimentary_copies,
        'book_cover_sc' => $this->input->post('book_cover_sc'),
        'paper_type_sc' => $this->input->post('paper_type_sc'),
        'book_size_sc' => $this->input->post('book_size_sc'),
        'lamination_sc' => $this->input->post('lamination_sc'),
        'lead_packge_value' => $this->input->post('package_value'),
        'lead_packge_discount' => $this->input->post('discount'),
        'lead_lesspckg_value' => $this->input->post('less_package_value'),
        'lead_packg_gst' => $this->input->post('gst'),
        'lead_packg_totalamount' => $this->input->post('total_amount'),
        'lead_book_pages' => $this->input->post('number_of_pages_for_sc'),//
        'lead_pdf_data' => $filename,
        'cost_of_additional_copy' => $this->input->post('cost_of_additional'),
        'gross_amt' => $this->input->post('additional_gross_amount'),
        'create_p_offer'=> $this->input->post('create_p_offer'),
        );
    } 
    // print_r($data);
    // echo $authorId;die;
    $result = $this->leads_model->saveOtherCreatePackage($authorId, $data);
    //echo "<pre>";
    //print_r($result);
    set_alert('success', _l('Package has been updated successfully.'));
    echo ("<script LANGUAGE='JavaScript'> window.location.href='".$url."';</script>");
    
}
public function createMuliplePackage(){

//         echo '<pre>';
//  print_r($_POST);die();

    $this->load->helper('url');
    $this->load->library('excel');
    $this->load->library('pdf');
    $this->load->config('email');
    $this->load->library('email');
    $this->load->helper('phpass');
    $url = $this->input->post('url');
    // print_r($_POST);die;
    $no_of_page = $this->input->post('increment');
    $craete_package = $this->input->post('craete_package');
    $authorId = $this->input->post('author_id');
    $email = $this->input->post('email');
    $name = $this->input->post('author_name');

    if(!empty($this->input->post('package_name_data'))){
        $package__data = $this->input->post('package_name_data');
    }else{
        $package__data = $this->input->post('package_name');
    }
    if ($this->input->post('number_of_complimentary_copies_edit')) {
        $number_of_complimentary_copies = $this->input->post('number_of_complimentary_copies_edit');
    }else{
        $number_of_complimentary_copies = $this->input->post('number_of_complimentary_copies');
    }
    $filename = str_replace(' ', '', $name.'multiple_package').".pdf";
    $data['name'] = $name;
    $data['email'] = $email;
    $data['mobile'] = $this->input->post('mobile');
    $data['packagecost'] = $this->input->post('cost');
    $data['full_payment_disc'] = $this->input->post('full_payment');
    $data['total_amount_popup_pdf'] = $this->input->post('total_amount_popup_pdf');
    $data['booking_amount_popup_pdf'] = $this->input->post('booking_amount_popup_pdf');
    $data['booking_f_amount_popup_pdf'] = $this->input->post('booking_f_amount_popup_pdf');
    $data['booking_s_amount_popup_pdf'] = $this->input->post('booking_s_amount_popup_pdf');
    $data['create_p_offer'] =  $this->input->post('create_p_offer');
    if($this->input->post('package_details') == 1 ){
        $data['package_name'] = $this->input->post('package_name_data');
        $data['book_type'] = $this->input->post('book_type_value');
    }elseif($this->input->post('package_details') == 2){
        $data['book_cover_sc'] = $this->input->post('book_cover_c');
        $data['paper_type_sc'] = $this->input->post('paper_type_c');
        $data['book_size_sc'] = $this->input->post('book_size_c');
        $data['lamination_sc'] = $this->input->post('lamination_c');
        $data['complimentry_copies'] = $this->input->post('complimentry_copies');
        $data['additional_author_copy'] = $this->input->post('additional_author_copy_customized');
        $data['number_of_pages'] = $this->input->post('increment');
        $data['package_name'] = $this->input->post('package_name');
        $data['book_type'] = $this->input->post('book_type_value');
        $data['color_pages'] = $this->input->post('color_pages_customized');
    }else{
        $data['book_cover_sc'] = $this->input->post('book_cover_sc');
        $data['paper_type_sc'] = $this->input->post('paper_type_sc');
        $data['book_size_sc'] = $this->input->post('book_size_sc');
        $data['lamination_sc'] = $this->input->post('lamination_sc');
        $data['complimentry_copies'] = $number_of_complimentary_copies;
        $data['number_of_pages'] = $this->input->post('number_of_pages_for_sc');
        $data['package_name'] = $this->input->post('package_name_data_value');
        $data['book_type'] = $this->input->post('book_type');
    } 
    $data['package_value'] = $this->input->post('package_value');
    $data['discount'] = $this->input->post('discount');
    $data['less_pkg_value'] = $this->input->post('less_package_value');
    $data['gst'] = $this->input->post('gst');
    $data['total_amount'] = $this->input->post('total_amount');
    $data['msstatus'] = $this->input->post('msstatus');
    if($this->input->post('cost_of_additional')){
        $data['cost_of_additional_copy'] = $this->input->post('cost_of_additional') ;
    }
    if($this->input->post('additional_gross_amount')){
        $data['gross_amt'] = $this->input->post('additional_gross_amount') ;
    }
    if($this->input->post('package_details') == 3){
        $data['sub_services'] = implode(", ",$this->input->post('sub_services'));
        foreach ($this->input->post('sub_services') as $key => $value) {
            $service_get = $this->db->get_where('tblpackagesubservices',array('id'=>$value))->row();
            $cart[] = $service_get->serviceid;  
        }
        for ($i=0; $i < count($cart); $i++) { 
            if ($cart[$i] != $cart[$i+1]) {
                $service_array[] = $cart[$i];  
            }
        }
        $data['service'] = implode(", ",$service_array);
    }else{
        $data['service'] = implode(", ",$this->input->post('services'));
        $data['sub_services'] = implode(", ",$this->input->post('sub_services'));
    }
    $html = $this->load->view('admin/pdf/pdf', $data, true);
    $dompdf = new Dompdf\DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();
    $dompdf->set_paper('A4', 'landscape');
    $output = $dompdf->output();
    $filepath = 'assets/authorMail/'.$filename;
    file_put_contents($filepath, $output);
    if($this->input->post('package_details')==1){
        $services = implode(", ",$this->input->post('services'));
        $explodedStr = explode(", ", $services);
        $filteredArray = array_filter( $explodedStr );
        // Convert Array into String with comma delimiter 
        $servicess = implode(", ",  $filteredArray);
    }else if($this->input->post('package_details')==2){
        $servicess = implode(", ",$this->input->post('services'));
    }else if($this->input->post('package_details')==3) {
        $servicess = $data['service'];
    }
    if($this->input->post('package_details') == 1 ){
    if ($this->input->post('book_type_value') == 'ebook') {
        $lead_book_pages = 100;
    }else{
        if ($this->input->post('package_name_data') == 'essential') {
            $lead_book_pages = 100;
        }else if ($this->input->post('package_name_data') == 'regular') {
            $lead_book_pages = 150;
        }else  if ($this->input->post('package_name_data') == 'superior') {
            $lead_book_pages = 250;
        }else  if ($this->input->post('package_name_data') == 'premium') {
            $lead_book_pages = 400;
        }else  if ($this->input->post('package_name_data') == 'rapid') {
            $lead_book_pages = 400;
        }else{
            $lead_book_pages = 400;
        }
    }
        $data = array(
        // 'leadid' => $this->input->post('author_id'),
        'craete_package' => 1,
        'lead_author_name' => $name, 
        'lead_package_detail' => $this->input->post('package_details'),
        'lead_book_type' => $this->input->post('book_type_value'),
        'lead_package_name' => $this->input->post('package_name_data'), 
        'lead_service' => $servicess,//
        'lead_sub_service' => implode(", ",$this->input->post('sub_services')),//
        'lead_packge_value' => $this->input->post('package_value'),
        'lead_packge_discount' => $this->input->post('discount'),
        'lead_lesspckg_value' => $this->input->post('less_package_value'),
        'lead_packg_gst' => $this->input->post('gst'),
        'lead_packg_totalamount' => $this->input->post('total_amount'),
        'lead_book_pages' => $lead_book_pages,
        'create_p_offer'=> $this->input->post('create_p_offer'),
        'lead_pdf_data' => $filename
        );
    }elseif($this->input->post('package_details') == 2){
        $data = array(
        // 'leadid' => $this->input->post('author_id'),
        'craete_package' => 1,
        'lead_author_name' => $name, 
        'lead_package_detail' => $this->input->post('package_details'),
        'lead_book_type' => $this->input->post('book_type_value'),
        'lead_package_name' => $this->input->post('package_name'), 
        'lead_service' => $servicess,//
        'lead_ori_packge_value' => $this->input->post('lead_ori_packge_value'),
        'lead_sub_service' => implode(", ",$this->input->post('sub_services')), //
        'complimentry_copies' => $this->input->post('complimentry_copies'),
        'additional_author_copy' => $this->input->post('additional_author_copy_customized'),
        'color_pages' => $this->input->post('color_pages_customized'),
        'book_cover_sc' => $this->input->post('book_cover_c'),
        'paper_type_sc' => $this->input->post('paper_type_c'),
        'book_size_sc' => $this->input->post('book_size_c'),
        'lamination_sc' => $this->input->post('lamination_c'),
        'lead_packge_value' => $this->input->post('package_value'),
        'lead_packge_discount' => $this->input->post('discount'),
        'lead_lesspckg_value' => $this->input->post('less_package_value'),
        'lead_packg_gst' => $this->input->post('gst'),
        'lead_packg_totalamount' => $this->input->post('total_amount'),
        'lead_book_pages' => $no_of_page,
        'lead_pdf_data' => $filename,
        'cost_of_additional_copy' => $this->input->post('cost_of_additional'),
        'gross_amt' => $this->input->post('additional_gross_amount'),
        'create_p_offer'=> $this->input->post('create_p_offer'),
        );
    }else{
        $data = array(
        // 'leadid' => $this->input->post('author_id'),
        'craete_package' => 1,
        'lead_author_name' => $name, 
        'lead_package_detail' => $this->input->post('package_details'),
        'lead_ori_packge_value' => $this->input->post('lead_ori_packge_value'),
        'lead_book_type' => $this->input->post('book_type'),
        'lead_package_name' => $this->input->post('package_name_data_value'), 
        'lead_service' => $servicess,
        'lead_sub_service' => implode(", ",$this->input->post('sub_services')),
        'complimentry_copies' => $number_of_complimentary_copies,
        'book_cover_sc' => $this->input->post('book_cover_sc'),
        'paper_type_sc' => $this->input->post('paper_type_sc'),
        'book_size_sc' => $this->input->post('book_size_sc'),
        'lamination_sc' => $this->input->post('lamination_sc'),
        'lead_packge_value' => $this->input->post('package_value'),
        'lead_packge_discount' => $this->input->post('discount'),
        'lead_lesspckg_value' => $this->input->post('less_package_value'),
        'lead_packg_gst' => $this->input->post('gst'),
        'lead_packg_totalamount' => $this->input->post('total_amount'),
        'lead_book_pages' => $this->input->post('number_of_pages_for_sc'),  //
        'lead_pdf_data' => $filename,
        'cost_of_additional_copy' => $this->input->post('cost_of_additional'),
        'gross_amt' => $this->input->post('additional_gross_amount'),
        'create_p_offer'=> $this->input->post('create_p_offer'),
        );
    } 
    // print_r($data);
    // echo $authorId;die;
    $result = $this->leads_model->savemultiCreatePackage($authorId, $data);
    //echo "<pre>";
    //print_r($result);
    set_alert('success', _l('Package has been updated successfully.'));
    echo ("<script LANGUAGE='JavaScript'> window.location.href='".$url."';</script>");
    
}
public function getPackageData_another_p(){
    $authorId = $_POST['author_id'];
    $data['package_data'] = $this->leads_model->getlead_create_Data_another_package($authorId);
    // print_r( $data['package_data'] );die;
    $services = $data['package_data']->lead_service;
    $sub_services = $data['package_data']->lead_sub_service;
    $data['services'] = $this->leads_model->getPackageService($services);
    $data['sub_services'] = $this->leads_model->getPackageSubService($sub_services);
    $this->load->view('admin/leads/popupdata_another_p', $data);
}
public function multi_prview_pack(){
    $data['url'] = $_SERVER['HTTP_REFERER'];
    $leadId = end($this->uri->segment_array());
    $data['package_data'] = $this->leads_model->getlead_create_Data_multiple_package($leadId);
    // echo "<pre>";
    // print_r($data['package_data']);die;
    $services = $data['package_data']->lead_service;
    $sub_services = $data['package_data']->lead_sub_service;
    $data['services'] = $this->leads_model->getPackageService($services);
    $data['sub_services'] = $this->leads_model->getPackageSubService($sub_services);
    $this->load->view('admin/leads/popup_multiple_create', $data);
}
public function my_data()
{
        $data = array(
        6203539574
        );


        $this->load->database();
        $this->db->select('lead_adname,lead_author_name,phonenumber,assigned');
        $noarray = array();
        $inarray = array();
        $this->db->where('phonenumber','9830264576');
        $indb = $this->db->get('tblleads');
        echo'<pre>';
        //     print_r($indb->result());,
        foreach ($data as $num) {
            $this->db->like('phonenumber',$num);
            $indb = $this->db->get('tblleads');
            // $result = $indb->result();,
            // print_r($indb);,
            if($indb->num_rows()>0){
                array_push($inarray,$num);
                // echo'<pre>';,
                // echo 'Present in db:'.$num.'<br/>';,
            }
            else{
                array_push($noarray,$num);
                // echo $num."<br/>";,
            }
        }
        foreach($inarray as $number){
                $this->db->like('phonenumber',$number);
                $indbs = $this->db->get('tblleads');
                // $result = $indb->result();,
                // print_r($indb);,
                if($indbs->num_rows()>0){
                    array_push($inarrays,$number);
                    // echo'<pre>';,
                    // echo 'Present in db:'.$num.'<br/>';,
                }
                else{
                    array_push($noarrays,$num);
                    // echo $num."<br/>";,
                }
        }
        echo "<table>
        <tr>
            <th>in db</th>
            <th>not in db</th>
        </tr>
        <tr>
        <td>";
        foreach($inarrays as $data){
         echo $data.'<br>';   
        }
        echo '</td>
        <td>';
        foreach($noarray as $data){
            echo $data.'<br>';   
           }
        echo '</td>
        </tr>';



}
public function reffer_lead_check()
    {
        $number = $_POST['number'];
       $data =  $this->db->get_where('tblleads',array('phonenumber'=>$number))->row();
       if ($data) {
        echo '1';
       }else{
        echo '0';
       }
    }
    public function swap_package(){
        $lead_id = $this->input->post("lead_id");
        $compair_id = $this->input->post("compair_id");
        $data1 = $this->db->select('lead_package_detail,lead_ori_packge_value,lead_book_type,lead_package_name,lead_service,lead_sub_service,complimentry_copies,book_cover_sc,paper_type_sc,book_size_sc,lamination_sc,lead_packge_value,lead_lesspckg_value,lead_packg_gst,lead_packg_totalamount,lead_book_pages,lead_pdf_data,cost_of_additional_copy,lead_booking_amount,gross_amt,create_p_offer,color_pages,additional_author_copy,lead_packge_discount')->where('id',$lead_id)->get('tblleads')->row();
        $data2 = $this->db->select('lead_package_detail,lead_ori_packge_value,lead_book_type,lead_package_name,lead_service,lead_sub_service,complimentry_copies,book_cover_sc,paper_type_sc,book_size_sc,lamination_sc,lead_packge_value,lead_lesspckg_value,lead_packg_gst,lead_packg_totalamount,lead_book_pages,lead_pdf_data,cost_of_additional_copy,lead_booking_amount,gross_amt,create_p_offer,color_pages,additional_author_copy,lead_packge_discount')->where('id',$compair_id)->get('compaire_create_package')->row();
        $updata1 = array(
            'lead_package_detail'=> $data1->lead_package_detail,
            'lead_ori_packge_value'=> $data1->lead_ori_packge_value,
            'lead_book_type'=> $data1->lead_book_type,
            'lead_package_name'=> $data1->lead_package_name,
            'lead_service'=> $data1->lead_service,
            'lead_sub_service'=> $data1->lead_sub_service,
            'complimentry_copies'=> $data1->complimentry_copies,
            'book_cover_sc'=> $data1->book_cover_sc,
            'paper_type_sc'=> $data1->paper_type_sc,
            'book_size_sc'=> $data1->book_size_sc,
            'lamination_sc'=> $data1->lamination_sc,
            'lead_packge_value'=> $data1->lead_packge_value,
            'lead_lesspckg_value'=> $data1->lead_lesspckg_value,
            'lead_packg_gst'=> $data1->lead_packg_gst,
            'lead_packg_totalamount'=> $data1->lead_packg_totalamount,
            'lead_book_pages'=> $data1->lead_book_pages,
            'lead_pdf_data'=> $data1->lead_pdf_data,
            'cost_of_additional_copy'=> $data1->cost_of_additional_copy,
            'lead_booking_amount'=> $data1->lead_booking_amount,
            'gross_amt'=> $data1->gross_amt,
            'create_p_offer'=> $data1->create_p_offer,
            'color_pages'=> $data1->color_pages,
            'additional_author_copy'=> $data1->additional_author_copy,
            'lead_packge_discount'=> $data1->lead_packge_discount
        );
        $updata2 = array(
            'lead_package_detail'=> $data2->lead_package_detail,
            'lead_ori_packge_value'=> $data2->lead_ori_packge_value,
            'lead_book_type'=> $data2->lead_book_type,
            'lead_package_name'=> $data2->lead_package_name,
            'lead_service'=> $data2->lead_service,
            'lead_sub_service'=> $data2->lead_sub_service,
            'complimentry_copies'=> $data2->complimentry_copies,
            'book_cover_sc'=> $data2->book_cover_sc,
            'paper_type_sc'=> $data2->paper_type_sc,
            'book_size_sc'=> $data2->book_size_sc,
            'lamination_sc'=> $data2->lamination_sc,
            'lead_packge_value'=> $data2->lead_packge_value,
            'lead_lesspckg_value'=> $data2->lead_lesspckg_value,
            'lead_packg_gst'=> $data2->lead_packg_gst,
            'lead_packg_totalamount'=> $data2->lead_packg_totalamount,
            'lead_book_pages'=> $data2->lead_book_pages,
            'lead_pdf_data'=> $data2->lead_pdf_data,
            'cost_of_additional_copy'=> $data2->cost_of_additional_copy,
            'lead_booking_amount'=> $data2->lead_booking_amount,
            'gross_amt'=> $data2->gross_amt,
            'create_p_offer'=> $data2->create_p_offer,
            'color_pages'=> $data2->color_pages,
            'additional_author_copy'=> $data2->additional_author_copy,
            'lead_packge_discount'=> $data2->lead_packge_discount,
            'package_swap'=> 1
        );
        $upleads = $this->db->where('id',$lead_id)->update('tblleads',$updata2);
        $upcompair = $this->db->where('id',$compair_id)->update('compaire_create_package',$updata1);
        echo "<pre>";
        if($upleads==1 & $upcompair==1){
            echo 1;
            die();
        }
        // if($upcompair){
        //     print_r($upcompair);
        // }
        // print_r($updata2);

        // echo "test";
    }
}
