<?php
   
defined('BASEPATH') or exit('No direct script access allowed');
   
   /**
    * @property  db
    */
class Wfh_model extends CRM_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Get lead
	* @param string $id Optional - leadid
	* @return mixed
	*/

	public function getWfhData($members)
	{
		if (is_admin() || is_headtrm()) {
			$this->db->select('tbl_wfh.*, CONCAT(tblstaff.firstname," ",tblstaff.lastname) as full_name, tblstaff.staffid');
			$this->db->from('tbl_wfh');
			$this->db->where('tblstaff.active', '1');
			$this->db->join('tblstaff', 'tbl_wfh.staffid = tblstaff.staffid');
			$this->db->order_by("tbl_wfh.id", "desc");
		}else{
			$loginid = $this->session->userdata('staff_user_id');
			//echo $loginid;exit;
			$this->db->select('tbl_wfh.*, CONCAT(tblstaff.firstname," ",tblstaff.lastname) as full_name, tblstaff.staffid');
			$this->db->from('tbl_wfh');
			//$this->db->where('tbl_wfh.application_status', '1');
			$this->db->where('tblstaff.active', '1');
			//$this->db->where('tbl_wfh.staffid !=', $loginid);
			$this->db->where("tbl_wfh.staffid IN (".$members.")",NULL, false);
			$this->db->join('tblstaff', 'tbl_wfh.staffid = tblstaff.staffid');
			$this->db->order_by("tbl_wfh.id", "desc");
		}
		
		return $data = $this->db->get()->result();
		//echo $this->db->last_query();exit;
	}
	 
	 
}
