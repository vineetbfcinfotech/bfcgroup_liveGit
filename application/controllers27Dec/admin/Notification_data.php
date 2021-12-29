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
         
             $all_data = $this->db->order_by("id", "DESC")->get_where('lead_all_action',array('notify_to'=>$_POST['view']))->result();
             $output = '';
             foreach ($all_data as $key => $value) {
						$output .='<li class="relative notification-wrapper" data-notification-id="11552">';
						$output .='<div class="notification-box unread">';
						$output .='<img src="https://bfcpublications.com/bfcgroup/assets/images/user-placeholder.jpg" class="staff-profile-image-small img-circle notification-image pull-left" alt="">';
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
               redirect('admin');
           }
        }
         public function count($value='')
        {
           if (!empty($_POST['view'])) {
           	// echo $_POST['view'];die;
         
            
				$this->db->select('status');
				$this->db->from('lead_all_action');
				$this->db->where('notify_to',$_POST['view']);
				$this->db->where('status',0);
				$num_results = $this->db->count_all_results();
				echo($num_results);
     
           }else{
               redirect('admin');
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
               redirect('admin');
           }
        }
            public function popup($value='')
        {
           if (!empty($_POST['view'])) {
            // echo $_POST['view'];die;
         
                $all_data_reminder = $this->db->get_where('lead_all_action',array('notify_to'=>$_POST['view'],'popup_status'=>0,'next_calling !='=> ''))->row();

                if ($all_data_reminder) {
                   $next_calling_date = date('Y-m-d h:i', strtotime($all_data_reminder->next_calling));
                   //  echo date('Y-m-d h:i');
                   if ($next_calling_date == date('Y-m-d h:i')) {
                        echo $all_data_reminder->message;
                $data_array = array(
                'popup_status' =>1
                );
                $this->db->where('id',$all_data_reminder->id);
                $this->db->update('lead_all_action',$data_array);
                   }
              

                }else{

                $all_data = $this->db->get_where('lead_all_action',array('notify_to'=>$_POST['view'],'popup_status'=>0))->row();
                echo $all_data->message;
                $data_array = array(
                'popup_status' =>1
                );
                $this->db->where('id',$all_data->id);
                $this->db->update('lead_all_action',$data_array);
                }

            

           }
        }
        public function all_notification($value='')
        {
          $data['result'] = $this->db->order_by("id", "DESC")->get_where('lead_all_action',array('notify_to'=>$value))->result();
          $this->load->view('admin/notification/all_notification', $data);
        }
    }