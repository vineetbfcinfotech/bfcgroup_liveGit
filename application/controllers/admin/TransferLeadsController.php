<?php
//all lead code by Shourabh 
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class TransferLeadsController extends Admin_controller
{
    
    public function __construct()
    { 
        
        parent::__construct();
        $this->not_importable_leads_fields = do_action('not_importable_leads_fields', ['id', 'source', 'assigned', 'status', 'dateadded', 'last_status_change', 'addedfrom', 'leadorder', 'date_converted', 'lost', 'junk', 'is_imported_from_email_integration', 'email_integration_uid', 'is_public', 'dateassigned', 'client_id', 'lastcontact', 'last_lead_status', 'from_form_id', 'default_language', 'hash']);
        $this->load->model('leads_model');
        $this->load->model('Leadmodel_chfilter');
        $this->load->model('TransferLeadModel');

        $this->load->model('product_model', 'pmodel');
        $this->load->model('departments_model', 'depart_model');
        $this->load->model('teams_model', 'teamsm');
        $this->load->library("pagination");
        $this->load->helper('url');
        $this->load->library('excel');
    }

    public function reffer_lead_check()
    {
        $search_text = "";
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('search_global');
            $this->session->set_userdata(array("search_global" => $search_text));
        } else {
            if ($this->session->userdata('search_global') != NULL) {
                $search_text = $this->session->userdata('search_global');
            } else {}
        }
        $expsearch_cat = "";
        $expstaff_name  = "";
        $expstart_date = "";
        $expend_date = "";
        $exp_ms="";
        if($this->input->post('submit_cat') != NULL ){
            $expstaff_name = $this->input->post('staff_name');
            $expsearch_cat = $this->input->post('category_type');
            $this->session->set_userdata(array("expcategory_type"=>$expsearch_cat));
            $this->session->set_userdata(array("expstaff_name"=>$expstaff_name));
            $exp_ms = $this->input->post('sel_ms');
            $expstart_date = $this->input->post('start_date');
            $expend_date = $this->input->post('end_date');
            $this->session->set_userdata(array("expstart_date"=>$expstart_date,"expend_date"=>$expend_date,'exp_ms'=>$exp_ms));
        }else{
            if( ($this->session->userdata('exp_ms') != NULL) || ($this->session->userdata('expstaff_name') != NULL) || ($this->session->userdata('expcategory_type') != NULL) || ($this->session->userdata('expstart_date') != NULL) ){
                $expstaff_name = $this->session->userdata('expstaff_name');
                $expsearch_cat = $this->session->userdata('expcategory_type');
                $expstart_date = $this->session->userdata('expstart_date');
                $expend_date = $this->session->userdata('expend_date');
                $exp_ms = $this->session->userdata('exp_ms');
            }else{
            }
        }
        $useraid = $this->session->userdata('staff_user_id');
        $data['useraid'] = $useraid;
        $data['get_staff'] = $this->leads_model->get_pc();
        $data['get_task_type'] = $this->leads_model->get_pc_category();
        // print_r($expsearch_cat);
        // die();

        $allcount = $this->TransferLeadModel->getrecordCount($search_text,$expsearch_cat,$expstart_date,$expend_date,$expstaff_name,$exp_ms);

        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() .'admin/TransferLeadsController/reffer_lead_check';
        $config['total_rows'] =$allcount;
        $config['per_page'] = 500;
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
        $data['project_data'] =  $this->TransferLeadModel->leedsData($config["per_page"], $page ,$search_text,$expsearch_cat,$expstart_date,$expend_date,$expstaff_name,$exp_ms);
        $data['bodyclass'] = 'hide-sidebar';
        if(empty($this->uri->segment(4))){
            $curpage=$this->uri->segment(3);
            $result_start=1; 
            $result_end=$curpage+$config['per_page'];
       }else {
            $curpage=$this->uri->segment(4);
            $result_start=$curpage+1;
            $result_end=$curpage+$config['per_page'];
            if($result_end>$allcount){$result_end=$allcount;}
       }
        $data['pagination_number'] = "Displaying " . $result_start . " to " . $result_end . " of " . $allcount;
        $data['search'] = $search_text;
        $data['staff_name'] = $expstaff_name;
        $data['search_cat'] = $expsearch_cat;
        $data['start_date'] = $expstart_date;
        $data['end_date'] = $expend_date;
        $data['select_ms'] = $exp_ms;
        // print_r($data['selsct_ms']);
        // die();
$this->load->view('admin/leads/transferleads',$data);
    }

    public function updatepc(){
        // echo "test";
        // print_r($this->input->post('tranfer_lead_id'));
        // die();
        $this->load->helper('url');
        // print_r($this->input->post());
        $leadid = $this->input->post('tranfer_lead_id');
        $staff_id = $this->input->post('staff_name');
        $data = array(
            "assigned"=>$staff_id
        );
      // print_r($leadid);echo "test";
        $query = $this->db->set('previous_assigned', 'assigned', false)
            ->where_in('id', $leadid)
            ->update('tblleads',$data);
           // print_r($this->db->last_query());exit();
            if($query){
                // echo '<script>window.location.href="http://google.com";</script>';
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }
            // print_r($this->db->last_query());
    }


}