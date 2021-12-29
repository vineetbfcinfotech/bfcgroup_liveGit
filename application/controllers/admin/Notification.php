<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends Admin_controller
{
    /**
     * Codeigniter Instance
     * Expenses detailed report filters use $ci
     * @var object
     */
    private $ci;

    public function __construct()
    {
        parent::__construct();
        /*if (!has_permission('reports', '', 'view')) {
            access_denied('reports');
        }*/
        $this->ci = &get_instance();
        $this->load->model('announcements_model');
        
    }
	
	public function index(){
		$data['notifications'] = $this->announcements_model->get_custom_notification();
		$data['title'] = "HR Custom Notifications";
        $this->load->view('admin/notification/manage', $data);
	}
	
	public function save(){
		$subject = $this->input->post("subject");
		$message = $this->input->post("message");
		$date = date("Y-m-d h:i:s");
		$user_id = get_staff_user_id();
		$data = array("isread"=> "0", "isread_inline"=> "0", "description"=> "custom_notification", "custom_subject"=> $subject, "custom_notification"=> $message, "date"=> $date, "fromuserid"=> $user_id );
		//print_r($data);exit;
		$insert = $this->announcements_model->save_custom_notification($data);
		//$this->session->set_flashdata('success', "Notification added successfully!");
		$this->session->set_flashdata('success', 'Notification added successfully!');
		redirect(base_url('admin/notification'));
	}
	
	
}