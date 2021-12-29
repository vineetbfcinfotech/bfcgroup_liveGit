<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class Notification_data extends Admin_controller{
    private $not_importable_leads_fields;
        public function __construct(){
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
        public function index($value='')
        {
           if (!empty($_POST['view'])) {
           	// echo $_POST['view'];die;
         
             $all_data = $this->db->order_by("id", "DESC")->limit(5)->get_where('lead_all_action',array('notify_to'=>$_POST['view']))->result();
             $output = '';
             foreach ($all_data as $key => $value) {
						$output .='<li class="relative notification-wrapper" data-notification-id="11552">';
						$output .='<div class="notification-box unread">';
						$output .='<img src="https://bfcpublications.com/bfcgroup/assets/images/user-placeholder.jpg" class="staff-profile-image-small img-circle notification-image pull-left" alt="Aviharsha  Singh">';
						$output .='<div class="media-body">';
						$output .='<a href="'.admin_url('Notification_data/all_notification/'.$_POST['view']).'">';
						$output .='<span class="notification-title">'.$value->discription.'</span><br>';
						$output .='</a>';
						$output .='</div>';
						$output .='</div>';
					
						$output .='</li>';
             }
             echo $output;
           }else{
            redirect(base_url());
           }
        }
         public function index_pc($value='')
        {
           if (!empty($_POST['view'])) {
            // echo $_POST['view'];die;
        // $current_time = date('Y-m-d H:I:S')
             $all_data = $this->db->order_by("id", "DESC")->limit(5)->get_where('tblreminders',array('creator'=>$_POST['view']))->result();
           

             $output = '';
             foreach ($all_data as $key => $value) {
             	// $myvalue = '28-1-2011 14:32:55';


	 $output .='<li class="relative notification-wrapper" data-notification-id="11552">';
            $output .='<div class="notification-box unread">';
            $output .='<img src="https://bfcpublications.com/bfcgroup/assets/images/user-placeholder.jpg" class="staff-profile-image-small img-circle notification-image pull-left" alt="Aviharsha  Singh">';
            $output .='<div class="media-body">';
            $output .='<a href="'.admin_url('Notification_data/all_notification_pc/'.$_POST['view']).'">';
            $output .='<span class="notification-title">This is reminder message on date '.$value->date.'</span><br>';
            $output .='</a>';
            $output .='</div>';
            $output .='</div>';
          
            $output .='</li>';

           
             }
             echo $output;
           }else{
            redirect(base_url());
           }
        }
         public function count($value='')
        {
            // echo "string"; die;
           if (!empty($_POST['view'])) {
           	// echo $_POST['view'];die;
         
            
				$this->db->select('status');
				$this->db->from('lead_all_action');
				$this->db->where('notify_to',$_POST['view']);
				$this->db->where('status',0);
				$num_results = $this->db->count_all_results();
				echo($num_results);
     
           }else{
            redirect(base_url());
           }
        }
         public function count_pc($value='')
        {
        if (!empty($_POST['view'])) {
           
        // $this->db->select('status');
        $this->db->from('tblreminders');
        $this->db->where('creator',$_POST['view']);
        $this->db->where('isnotified',0);
        $num_results = $this->db->count_all_results();
        echo($num_results);
     
           }else{
            redirect(base_url());
           }
        }
        public function change_status($value='')
        {
         if (!empty($_POST['id'])) {
         	$id = $_POST['id'];
         $data_array = array(
         	'status' =>1
         );
            
			$this->db->where('notify_to',$id);
			 $this->db->update('lead_all_action',$data_array);

     		$this->db->select('status');
				$this->db->from('lead_all_action');
				$this->db->where('notify_to',$id);
				$this->db->where('status',0);
				$num_results = $this->db->count_all_results();
				echo($num_results);
           }else{
            redirect(base_url());
           }
        }
        public function change_status_pc($value='')
        {
         if (!empty($_POST['id'])) {

         	
          $id = $_POST['id'];
         $data_array = array(
          'isnotified' =>1
         );
            
      $this->db->where('creator',$id);
       $this->db->update('tblreminders',$data_array);

        // $this->db->select('status');
        $this->db->from('tblreminders');
        $this->db->where('creator',$id);
        $this->db->where('isnotified',0);
        $num_results = $this->db->count_all_results();
        echo($num_results);
           }else{
            redirect(base_url());
           }
        }
           public function popup_pc($value='')
        {
           if (!empty($_POST['view'])) {
            // echo $_POST['view'];die;
         
            
               $all_data = $this->db->get_where('tblreminders',array('creator'=>$_POST['view'],'popup_status'=>0))->row();
              $datetime = new DateTime($all_data->date);

$date = $datetime->format('Y-m-d');
$current_time = date('Y-m-d');
if ($date == $current_time) {
	echo $all_data->description;
                $data_array = array(
                'popup_status' =>1
                );


                $this->db->where('id',$all_data->id);
                $this->db->update('tblreminders',$data_array);
}else{}
                

           }else{
            redirect(base_url());
           }
        }
            public function popup($value='')
        {
           if (!empty($_POST['view'])) {
            // echo $_POST['view'];die;
         
            
               $all_data = $this->db->get_where('lead_all_action',array('notify_to'=>$_POST['view'],'popup_status'=>0))->row();

              

           }else{
            redirect(base_url());
           }
        }
        public function all_notification($value='')
        {
          $data['result'] = $this->db->order_by("id", "DESC")->get_where('lead_all_action',array('notify_to'=>$value))->result();
          $this->load->view('admin/notification/all_notification', $data);
        }
         public function all_notification_pc($value='')
        {
          $data['result'] = $this->db->order_by("id", "DESC")->get_where('tblreminders',array('creator'=>$value))->result();
          $this->load->view('admin/notification/all_notification_pc', $data);
        }
    }