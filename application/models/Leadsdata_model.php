<?php



defined('BASEPATH') or exit('No direct script access allowed');



class Leadsdata_model extends CRM_Model

{

    public function __construct()

    {

        parent::__construct();

    }

	

	function getCompletedProjects(){

		// $this->db->select('*');

		// $this->db->from('tblleads');

		// $this->db->where('status', 3); 

		// $result = $this->db->get(); 

		// return $result->result();


		$pm_id = $_SESSION['staff_user_id'];
if (is_admin() || is_headtrm()) {
	$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.status' => '3' ));
		 // $this->db->order_by('tblleads.lead_booking_amount_date','DESC');
		$query = $this->db->get();
		return $query->result();
}else{
		$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.status' => '3' ));
		 // $this->db->order_by('tblleads.lead_booking_amount_date','DESC');
		$query = $this->db->get();
		return $query->result();
}
		

	}
	function get_aquired_Projects(){


		$pm_id = $_SESSION['staff_user_id'];
if (is_admin() || is_headtrm()) {
	$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblleads.pm_project_status' => '1' ));
		
		$query = $this->db->get();
		return $query->result();
}else{
		$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.pm_project_status' => '1' ));
		 $this->db->order_by('tblleads.lead_booking_amount_date','DESC');
		 $query = $this->db->get();
		return $query->result();
}
		

  //            $q=$this->db
  //            ->select('*')
  //            ->where('pm_project_status ','1')
  //            ->get('tblleads');
  //     return $q->result();

	}
	function getleadsData($id)
	{
		return $this->db->get_where('tblleads',array('id'=>$id))->row();
	}
		function get_inprogress_Projects(){

		// return $q=$this->db
  //            ->select('*')
  //            ->order_by('lead_pm_takeup_date','DESC')
  //            ->where('pm_project_status','2')
  //            ->get('tblleads')
  //           ->result();
      			//echo $this->db->last_query();

        $pm_id = $_SESSION['staff_user_id'];
if (is_admin() || is_headtrm()) {
		$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblleads.pm_project_status' => '2'));
		 $this->db->order_by('tblleads.lead_pm_takeup_date','DESC');
		$query = $this->db->get();
		return $query->result();
}else{
		$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.pm_project_status' => '2'));
		 $this->db->order_by('tblleads.lead_pm_takeup_date','DESC');
		$query = $this->db->get();
		return $query->result();
}
	

	}
		public function get_payment_details_Projects($value='')
	{
		$pm_id = $_SESSION['staff_user_id'];
if (is_admin() || is_headtrm()) {
	$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblleads.pm_project_status >=' => '1' ,'tblleads.lead_payment_status >='=> '1'));
		 $this->db->order_by('tblleads.lead_booking_amount_date','DESC');
		$query = $this->db->get();
		return $query->result();
}else{
		$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.pm_project_status >=' => '1' ,'tblleads.lead_payment_status >='=> '1'));
		 $this->db->order_by('tblleads.lead_booking_amount_date','DESC');
		$query = $this->db->get();
		return $query->result();
}
		
	
		// $q=$this->db
		// ->select('*')
		// ->order_by('lead_booking_amount_date','DESC')
		// ->where('pm_project_status >=','1')
		// ->where('lead_payment_status >=','1')
		// // ->where('assigned','54')
		// ->get('tblleads');
		// return $q->result();

	}

	
    

    

}

