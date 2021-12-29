<?php
//all lead code by Shourabh 
header('Content-Type: text/html; charset=utf-8');

defined('BASEPATH') or exit('No direct script access allowed'); 



class Domain_controller extends Admin_controller

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

      
      public function domain_dtl(){
        $data['title'] = _l('Domail Purchase details');
        
        ///
        $this->db->select('*')
->from('domain_details');


 $query = $this->db->get();         
 
        /*$this->db->select('*');
        $this->db->get('domain_details');
         $this->db->order_by("id", "desc");*/
       
        $data['domain_dtl'] =$query->result(); 
        $today = date('Y-m-d');
        // $today = date_create('2022-8-18');
        
        foreach($data['domain_dtl'] as $row){
          $exp = $row->expairy_date;
          $date1=date_create($exp);
          $date2=date_create($today);
          $diff=date_diff($date2,$date1);
          $d_left = $diff->format("%R%a");
          // echo $d_left;
          if ($row->pm_status==0){
            if($d_left<=30){
              $val = array(
                'pm_status' => 1
              );
              $this->db->where('id',$row->id);
              $this->db->update('domain_details',$val);
              // echo 'yap'.$d_left;
              $row->pm_status = 1;

            }
          }
          if ($row->pm_status==1){
            if($d_left > 30){
              $val = array(
                'pm_status' => 0
              );
              $this->db->where('id',$row->id);
              $this->db->update('domain_details',$val);
              // echo 'yap'.$d_left;
              $row->pm_status = 0;

            }
          }
        }
        $this->load->view('admin/leads/domain_dtl', $data);
      }
      
      public function add_domain_dtl(){
          //echo "test";exit;
        $data['title'] = _l('Add Domail Purchase details');
        $this->load->view('admin/leads/add_domain_dtl', $data);
      }
        public function upload_image(){
        $id = $this->input->post('hidden_id');
       // echo "test";
       // print_r($_FILES);
        if ($_FILES) {
            $config['upload_path']          = './assets/domain_img/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 1024;
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('upload_img'))
            {
                $error = array('error' => $this->upload->display_errors());
                $data['error'] = "error";
                set_alert('error', "something went wrong");
                return redirect(admin_url('domain_controller/domain_dtl'));
            }
            else
            {
                $imageDetailArray = $this->upload->data();
                $image =  $imageDetailArray['file_name'];
                $data = array('image' => $image);
                $this->db->where('id',$id);
                $this->db->update('domain_details', $data);
                $data['success'] = "success";
                set_alert('success', "Save Successfully");
                return redirect(admin_url('domain_controller/domain_dtl'));
            }
        }else{
           set_alert('error', "Select any one file.");
                return redirect(admin_url('domain_controller/domain_dtl')); 
        }

      }
    public function saveDomainDtl(){
        $expairy_date = $_GET['expairy_date'];
        $author_name =$_GET['author_name'];
        $domarin_url = $_GET['domarin_url'];
        $purchase_platform = $_GET['purchase_platform'];
      
        $data = array('author_name' => $author_name, 'domain_url' => $domarin_url, 'expairy_date' => $expairy_date,'platform_type'=>$purchase_platform);
        $this->db->insert('domain_details', $data);
        
        $data['success'] = "success";
        set_alert('success', "Save Successfully");
        return redirect(admin_url('leads/add_domain_dtl'));
    }

    public function domain_account(){
        $data['title'] = _l('Domail Purchase details');
        $this->db->select('*')->from('domain_details');


        $query = $this->db->get();         
       
        $data['domain_dtl'] =$query->result(); 

        $this->load->view('admin/leads/domain_dtl_account', $data);

    }

    public function updateStatus(){
      $id = $this->input->post('id');
      $pm_status = $this->input->post('pm_value');
      $ac_status = $this->input->post('ac_value');
      $result_arr = array(
        'id'=>$id,
        'pm_stat'=>$pm_status,
        'ac_stat'=>$ac_status
      );
      $this->db->where('id', $id);
      if($pm_status!=null){
        $val = array(
          'pm_status' => $pm_status
        );
      }
      if($ac_status!=null){
        $val = array(
          'account_status' => $ac_status
        );
      }
      
     $data = $this->db->update('domain_details', $val);
     
if ($data) {
   echo json_encode(['code'=>200, 'msg'=>'success', 'data'=>$val]);
}else{
   echo json_encode(['code'=>400, 'msg'=>'error', 'data'=>$result_arr]);
}
      // echo $status;
     
  }
    
    }
