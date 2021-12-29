<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Wfh extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('wfh_model');
    }

    /* List all staff members */
    public function index()
    {
		$arr=herapermission();
		$data['wfhData'] = $this->wfh_model->getWfhData($arr);
		$this->load->view('admin/wfh/manage', $data);
	}
	
	public function saveWfh(){
		$data = array(
			"staffid" => $_POST["staff_id"],
			"duration" => $_POST["duration"],
			"start_date" => $_POST["start_date"],
			"end_date" => $_POST["end_date"],
			"reason" => $_POST["reason"],
			"status" => 0,
		);
		$result = $this->db->insert("tbl_wfh", $data);
		if($result == 1){
			echo "1";
		}else{
			echo "0";
		}
	}
	public function approveWfh(){
		$data = array("status"=>1);
		$this->db->where("id", $_POST["wfhid"]);
		$this->db->update("tbl_wfh", $data);
	}
	
	public function deleteWfh(){
		$this->db->where('id', $_POST["wfhid"]);
		$this->db->delete('tbl_wfh');
	}
	
	public function rejectWfh(){
		$arr=herapermission();
		
		$data = array("status"=>2);
		$this->db->where("id", $_POST["wfhid"]);
		$this->db->update("tbl_wfh", $data);
		
		$wfhdata = $this->wfh_model->getWfhData($arr);
		return json_encode($wfhdata);
	}
	
	public function days(){
		$this->load->view('admin/wfh/managedays', $data);
	}
	
	
}
