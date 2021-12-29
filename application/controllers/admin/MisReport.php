<?php

use Twilio\TwiML\Voice\Echo_;

header('Content-Type: text/html; charset=utf-8');

defined('BASEPATH') or exit('No direct script access allowed');



class MisReport extends Admin_controller

{
    public function __construct()

    {

        parent::__construct();

        $this->not_importable_leads_fields = do_action('not_importable_leads_fields', ['id', 'source', 'assigned', 'status', 'dateadded', 'last_status_change', 'addedfrom', 'leadorder', 'date_converted', 'lost', 'junk', 'is_imported_from_email_integration', 'email_integration_uid', 'is_public', 'dateassigned', 'client_id', 'lastcontact', 'last_lead_status', 'from_form_id', 'default_language', 'hash']);

        $this->load->model('leads_model');

        $this->load->model('MisReport_models');

        $this->load->model('product_model', 'pmodel');

        $this->load->model('departments_model', 'depart_model');

        $this->load->model('teams_model', 'teamsm');

        $this->load->library("pagination");

        $this->load->helper('url');

        $this->load->library('excel');
    }

    /* List all leads */

    public function index()

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
            $search_text = $this->input->post('search_global');
            $this->session->set_userdata(array("search_global" => $search_text));

            $staff_name = $this->input->post('staff_name');

            $this->session->set_userdata(array("staff_name" => $staff_name));
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $this->session->set_userdata(array("start_date" => $start_date, "end_date" => $end_date));
            // echo 'first'; print_r($_POST); die;
        } else {
            if (($this->session->userdata('staff_name') != NULL) || ($this->session->userdata('start_date') != NULL) || ($this->session->userdata('search_global') != NULL) ) {
                $staff_name = $this->session->userdata('staff_name');
                $start_date = $this->session->userdata('start_date');
                $end_date = $this->session->userdata('end_date');
                // echo 'second';
                // die;
            } else {
                // $start_date = date('Y-m-01');
                // // echo $start_date;
                // $end_date = date('Y-m-t');
                // // echo $end_date;
               
                // $this->session->set_userdata(array("start_date" => $start_date, "end_date" => $end_date));
                // echo 'thired';  die;
            }
        } 
        
        // $pc_assigned = $this->MisReport_models->pc_assigned_pm($staff_name);

        // if ( (gettype($pc_assigned)=='array' && (empty($pc_assigned)) )) {
        //     $pc_assigned = 'Nodata';
        // }


        
        $data['get_staff'] = $this->MisReport_models->get_pm();

        $allcount = $this->MisReport_models->getmiscount($search_text,$staff_name, $start_date, $end_date);
        // $test_data = $this->MisReport_models->getmisdata('10','10', $staff_name, $start_date, $end_date);
// echo '<pre>';
// print_r($test_data);die;
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() . 'admin/MisReport/index';
        $config['total_rows'] = $allcount;
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
        $data['misdata'] = $this->MisReport_models->getmisdata($config["per_page"], $page,$search_text, $staff_name, $start_date, $end_date);
        // print_r($data['misdata']);die;
        $data['bodyclass'] = 'hide-sidebar';
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['staff_name'] = $staff_name;
        $data['search'] = $search_text;
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

        //print_r($data);exit;
        $this->load->view('admin/pc/view_misreport', $data);
    }







    public function clear_filter()
    {
        $this->session->unset_userdata('start_date');
        $this->session->unset_userdata('end_date');
        $this->session->unset_userdata('staff_name');
        $this->session->unset_userdata('search_global');
        redirect($_SERVER['HTTP_REFERER']);
        // redirect('admin/MisReport/index', 'refresh');
    }
    public function testing()
    {
        $staff_name = array(
            '0' =>82
        );
        $data['get_staff'] = $this->MisReport_models->pc_assigned_pm($staff_name);
        print_r($data['get_staff']);
    }






    public function mis_for_isbn(){
        $start_date = "";
        $end_date = "";

        if ($this->input->post('submit_cat') != NULL) {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $this->session->set_userdata(array("start_date" => $start_date, "end_date" => $end_date));
        } else {
            if ( ($this->session->userdata('start_date') != NULL) ) {
                $start_date = $this->session->userdata('start_date');
                $end_date = $this->session->userdata('end_date');
            } else {
                // $start_date = date('Y-m-01');
                // $end_date = date('Y-m-t');
                // $this->session->set_userdata(array("start_date" => $start_date, "end_date" => $end_date));
            }
        }

        
        $data['get_staff'] = $this->MisReport_models->get_pm();

        $allcount = $this->MisReport_models->getmiscount(null,null, $start_date, $end_date);

        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() . 'admin/MisReport/mis_for_isbn';
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
        $data['misdata'] = $this->MisReport_models->getmisdata($config["per_page"], $page,null, null , $start_date, $end_date);
        // print_r($data['misdata']);die;
        // $data['bodyclass'] = 'hide-sidebar';
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
        $data['bodyclass'] = 'hide-sidebar';
        $data['pagination_number'] = "Displaying " . $result_start . " to " . $result_end . " of " . $allcount;
        /* echo '<pre>';
         print_r($data);exit;*/
        $this->load->view('admin/leads/mis_isbn', $data);

    }
}
