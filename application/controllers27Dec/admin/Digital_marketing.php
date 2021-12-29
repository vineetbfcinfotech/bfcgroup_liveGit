<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');
class Digital_marketing extends Admin_controller
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
        $this->load->model('leadsdata_model', 'leadsdata');
        $this->load->library("pagination");
        $this->load->helper('url');
        $this->load->library('excel');
        $this->load->library('pdf');
    }

    //project aquired function
    public function project_aquired()
    {
        $data['title'] = "Pending Projects";
        $data['business'] = "";
        $useraid = $this->session->userdata('staff_user_id');
        // $data['newproject_data'] = $this->db->get_where('tblleads',array('print_quotation_status'=>1,"dm_project_status" => 0,))->result();
        if (is_admin() || is_headtrm()) {
            $this->db->select('*');
            $this->db->from('tblleads');
            // $this->db->where_in('lead_asf_assign_marketing', $useraid);
            $this->db->where('dm_project_status', 1);
            $query = $this->db->get();
            $newproject_data = $query->result();
        } else {
            $this->db->select('*');
            $this->db->from('tblleads');
            $this->db->where_in('lead_asf_assign_marketing', $useraid);
            $this->db->where('dm_project_status', 1);
            $query = $this->db->get();
            $newproject_data = $query->result();
        }


        $data['newproject_data'] = $newproject_data;
        $this->load->view('admin/dm/pending_project', $data);
    }
    public function changeProjectStatus()
    {
        $project_id = $_POST['project_id'];
        $author_name = $_POST['author_name'];
        $id = $_POST['user_id'];
        $proj_name = $_POST['proj_name'];
        $book_name = $_POST['book_name'];

        $data['all_data'] = $this->db->get_where('tblleads', array('id', $project_id))->row();
        // print_r($data['all_data']->lead_sub_service);
        $sub_service = explode(", ", $data['all_data']->lead_sub_service);
        $i = 0;
        foreach ($sub_service as $key => $value) {

            $this->db->select('*');
            $this->db->from('tblpackagesubservices');
            $this->db->where('id', $value);
            $where = '(serviceid=29  or serviceid = 4 or serviceid = 13)';
            $this->db->where($where);

            $result = $this->db->get();
            $result = $result->result();


            if ($result) {
                ++$i;
            }
        }
        // echo $i;


        $data_array = array(
            "total_dm_report_counting" => $i,
            "dm_project_status" => 2,
            "dm_takeup_date" => date('Y-m-d H:i:s'),
        );
        $this->db->where('id', $project_id);
        $this->db->update('tblleads', $data_array);
        // print_r($_SESSION);die;
        $by = $this->session->userdata('staff_user_id');

        $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
            ->from('tblstaff')
            ->join('tblroles', 'tblstaff.role = tblroles.roleid');
        $this->db->where('tblstaff.staffid', $by);
        $query = $this->db->get();
        $ret = $query->row();
        // print_r($ret); die;
        $this->db->select('tblstaff.pm_assign_to')
            ->from('tblleads')
            ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
        $this->db->where('tblleads.id', $id);
        $query_data = $this->db->get();
        $return = $query_data->row();
        $data1 = array(
            'notify_to' => $return->pm_assign_to,
            'user_id' => $id,
            'take_by' => $by,
            'role' => $ret->name,
            'project_name' => $proj_name,
            'author_name' => $author_name,
            'book_name' => $book_name,
            'action' => 1,
            'message' => $author_name . ' Project taken by ' . $ret->firstname . ' ' . $ret->lastname,
            'discription' => $author_name . ' Project taken by ' . $ret->firstname . ' ' . $ret->lastname,
        );
        $this->db->insert('lead_all_action', $data1);
        // echo $this->db->last_query();

    }

    //project inprogress function
    public function inprogress_projects()
    {
        $data['title'] = "Inprogress Projects";
        $data['business'] = "";
        //$data['projects'] = $this->leadsdata->get_inprogress_Projects();
        // $data['newproject_data'] = $this->db->get_where('tblleads',array('print_quotation_status'=>1,"dm_project_status" => 1,))->result();
        if (is_admin() || is_headtrm()) {
            $this->db->select('*');
            $this->db->from('tblleads');
            $this->db->where('dm_project_status', 2);
            // $this->db->where('lead_asf_assign_marketing',$_SESSION['staff_user_id']);
            $query = $this->db->get();
            $newproject_data1 = $query->result();
            $data['newproject_data'] = $newproject_data1;
        } else {
            $this->db->select('*');
            $this->db->from('tblleads');
            $this->db->where('dm_project_status', 2);
            $this->db->where('lead_asf_assign_marketing', $_SESSION['staff_user_id']);
            $query = $this->db->get();
            $newproject_data1 = $query->result();
            $data['newproject_data'] = $newproject_data1;
        }

        $this->load->view('admin/dm/inprogress_progress', $data);
    }
    public function pip($id)
    {
        // echo $id;
        $data['id'] = $id;
        $data['all_data'] = $this->db->get_where('tblleads', array('id' => $id))->row();

        $data['sub_service'] = explode(", ", $data['all_data']->lead_sub_service);
        // print_r($data['sub_service']);
        $this->load->view('admin/dm/pip_project', $data);
    }
    public function upload_report()
    {
        $author_name = $_POST['author_name'];

        $proj_name = $_POST['proj_name'];
        $book_name = $_POST['book_name'];
        $id = $_POST['hidden_id'];
        $sub_service_id = $_POST['sub_service_id'];
        $sub_service_name = $_POST['sub_service_name'];

        $filename = $_FILES['file']['name'];
        $ckeck_ms = $this->db->get_where('tblleads', array('id' => $id))->row();
        $total_number = count($ckeck_ms_file);
        if ($filename) {
            $filename = $ckeck_ms->lead_author_name . '_' . $filename;


            // if($ckeck_ms->lead_pr_ms_file){
            //     unlink('assets/digital_marketing/facebook_report/'.$ckeck_ms->lead_pr_ms_file);
            // }
            $location = "assets/digital_marketing/report/" . $filename;
            $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
            /* Valid extensions */
            $valid_extensions = array("pdf", "doc", "docx", "csv");
            $response = 0;
            if (in_array(strtolower($imageFileType), $valid_extensions)) {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                    $total_dm_report_counting = $ckeck_ms->total_dm_report_counting - 1;
                    $response = $location;
                    if ($total_dm_report_counting == 0) {
                        $data_update = array(
                            'dm_end_date' => date('Y-m-d H:i:s'),
                            'dm_project_status' => 3,
                            'total_dm_report_counting' => $total_dm_report_counting,
                            'assign_id_for_dm' => 19
                        );
                    } else {
                        $data_update = array(
                            'total_dm_report_counting' => $total_dm_report_counting
                        );
                    }


                    $this->db->where('id', $id);
                    $this->db->update('tblleads', $data_update);
                    $data_array = array(
                        'lead_id' => $id,
                        'sub_service_id' => $sub_service_id,
                        'sub_service_name' => $sub_service_name,
                        'upload_report' => $filename,
                    );
                    $this->db->insert('upload_dm_report_table', $data_array);
                    $by = $this->session->userdata('staff_user_id');

                    $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                        ->from('tblstaff')
                        ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                    $this->db->where('tblstaff.staffid', $by);
                    $query = $this->db->get();
                    $ret = $query->row();
                    $this->db->select('tblstaff.pm_assign_to')
                        ->from('tblleads')
                        ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
                    $this->db->where('tblleads.id', $id);
                    $query_data = $this->db->get();
                    $return = $query_data->row();
                    $data1 = array(
                        'notify_to' => $return->pm_assign_to,
                        'user_id' => $id,
                        'take_by' => $by,
                        'role' => $ret->name,
                        'project_name' => $proj_name,
                        'author_name' => $author_name,
                        'book_name' => $book_name,
                        'action' => 14,
                        'message' => $author_name . ' report submitted by ' . $ret->firstname . ' ' . $ret->lastname,
                        'discription' => $author_name . ' report submitted by ' . $ret->firstname . ' ' . $ret->lastname,
                    );
                    $this->db->insert('lead_all_action', $data1);
                    // echo $this->db->last_query();
                    set_alert('success', _l('Report uploaded successfully...'));
                    if ($total_dm_report_counting == 0) {
                        redirect('admin/Digital_marketing/completed_projects');
                    } else {
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            } else {
                set_alert('warning', _l('Please select valid file.'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            set_alert('danger', _l('Please select a file.'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    //completed projects function
    public function completed_projects()
    {

        $data['title'] = "Approved Projects";
        $data['project_data'] = $this->db->get_where('tblleads', array('dm_project_status' => 3, 'assign_id_for_dm' => 19))->result();
        $this->load->view('admin/dm/completed_project', $data);
    }
    public function bfcPublications()
    {
        $data['title'] = "BFC Publications";
        //$data['projects'] = $this->leadsdata->getCompletedProjects();
        $this->load->view('admin/dm/bfcPublications', $data);
    }
    public function bfcCapital()
    {
        $data['title'] = "BFC Capital";
        //$data['projects'] = $this->leadsdata->getCompletedProjects();
        $this->load->view('admin/dm/bfcCapital', $data);
    }
    public function bfcInfotech()
    {
        $data['title'] = "BFC Infotech";
        //$data['projects'] = $this->leadsdata->getCompletedProjects();
        $this->load->view('admin/dm/bfcInfotech', $data);
    }
    public function lead_website($value = '')
    {
        $data['title'] = "Website Lead";
        $this->db2 = $this->load->database('secend_db', TRUE);
        $data['contact'] = $this->db2->order_by("id", "DESC")->get_where('contacts')->result();
        $data['refer_friends'] = $this->db2->order_by("id", "DESC")->get_where('refer_friends')->result();
        $this->load->view('admin/dm/website_lead', $data);
    }

    public function website_payment_report()
    {

        $search_text = "";
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('search_global');
            $this->session->set_userdata(array("search_global" => $search_text));
        } else {
            if ($this->session->userdata('search_global') != NULL) {
                $search_text = $this->session->userdata('search_global');
            } else {
            }
        }

        $start_date = "";
        $end_date = "";

        if ($this->input->post('submit_cat') != NULL) {

            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $this->session->set_userdata(array("start_date" => $start_date, "end_date" => $end_date));
        } else {
            if (($this->session->userdata('start_date') != NULL)) {
                $start_date = $this->session->userdata('start_date');
                $end_date = $this->session->userdata('end_date');
            } else {
            }
        }
        // $this->db2 =  $this->load->database('secend_db', TRUE);
        // $this->db2->select('id,order_id,OrderAmount,TransactionStatus,Message,PaymentMode,TransactionTime,frm, userName,userEmail,userPhone');
        // $data['payment_data'] = $this->db2->get('payment')->result();

        $allcount = $this->getpaymentcount($search_text, $start_date, $end_date);
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() . 'admin/digital_marketing/website_payment_report/';
        $config['total_rows'] = $allcount;
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

        $data['payment_data'] =  $this->getpaymentdata($config["per_page"], $page, $search_text, $start_date, $end_date);

        $data['search'] = $search_text;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        if (empty($this->uri->segment(4))) {
            $curpage = $this->uri->segment(3);
            // echo "test";
            $result_start = 1;
            $result_end = $curpage + $config['per_page'];
        } else {
            $curpage = $this->uri->segment(4);
            // echo "hii";
            $result_start = $curpage + 1;
            $result_end = $curpage + $config['per_page'];
            if ($result_end > $allcount) {
                $result_end = $allcount;
            }
        }
        $data['pagination_number'] = "Displaying " . $result_start . " to " . $result_end . " of " . $allcount;


        $this->load->view('admin/dm/payment_report', $data);
    }



    public function getpaymentdata($limit = '', $start = '', $search_text = '', $start_date = '', $end_date = '')
    {
        $this->db2 =  $this->load->database('secend_db', TRUE);
        $this->db2->select('id,order_id,OrderAmount,TransactionStatus,Message,PaymentMode,TransactionTime,frm, userName,userEmail,userPhone');

        $this->db2->limit($limit, $start);

        $this->db2->order_by('created_at', 'DESC');

        if ($search_text != '') {
            $this->db2->like('order_id', $search_text);
            $this->db2->or_like('OrderAmount', $search_text);
            $this->db2->or_like('Message', $search_text);
            $this->db2->or_like('PaymentMode', $search_text);
            $this->db2->or_like('TransactionTime', $search_text);
            $this->db2->or_like('userName', $search_text);
            $this->db2->or_like('userEmail', $search_text);
            $this->db2->or_like('userPhone', $search_text);
        }

        $start_date = (isset($start_date) && trim($start_date) != '') ? date("Y-m-d", strtotime(trim($start_date))) : '';
        $end_date = (isset($end_date) && trim($end_date) != '') ? date("Y-m-d", strtotime(trim($end_date))) : '';

        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != '') {

            $this->db2->where('created_at BETWEEN "' . $start_date . ' 00:00:00.000" and "' . $end_date . ' 23:59:59.997"');
        }

        $query = $this->db2->get('payment')->result();

        return $query;
    }


    public function getpaymentcount($search_text = '', $start_date = '', $end_date = '')
    {
        $this->db2 =  $this->load->database('secend_db', TRUE);
        $this->db2->select('count(*) as allcount');
        $this->db2->from('payment');

        if ($search_text != '') {
            $this->db2->like('order_id', $search_text);
            $this->db2->or_like('OrderAmount', $search_text);
            $this->db2->or_like('Message', $search_text);
            $this->db2->or_like('PaymentMode', $search_text);
            $this->db2->or_like('TransactionTime', $search_text);
            $this->db2->or_like('frm', $search_text);
            $this->db2->or_like('userName', $search_text);
            $this->db2->or_like('userEmail', $search_text);
            $this->db2->or_like('userPhone', $search_text);
        }

        $start_date = (isset($start_date) && trim($start_date) != '') ? date("Y-m-d", strtotime(trim($start_date))) : '';
        $end_date = (isset($end_date) && trim($end_date) != '') ? date("Y-m-d", strtotime(trim($end_date))) : '';

        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != '') {

            $this->db2->where('created_at BETWEEN "' . $start_date . ' 00:00:00.000" and "' . $end_date . ' 23:59:59.997"');
        }

        $query = $this->db2->get();


        $result = $query->result_array();
        return $result[0]['allcount'];
    }



    public function clear_filter()
    {
        $this->session->unset_userdata('start_date');
        $this->session->unset_userdata('end_date');
        $this->session->unset_userdata('search_global');
        // redirect('admin/MisReport/index', 'refresh');
        redirect($_SERVER['HTTP_REFERER']);
    }
}
