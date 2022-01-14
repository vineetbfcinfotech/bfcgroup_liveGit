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
		$query1 = $this->db->get()->result();		
		$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads_create_package', 'tblleads_create_package.assigned = tblstaff.staffid');
		 $this->db->where(array('tblleads_create_package.pm_project_status' => '3' ));
		
		$query2 = $this->db->get()->result();
		return array_merge($query1,$query2);
}else{
		/*$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.status' => '3' ));
		 // $this->db->order_by('tblleads.lead_booking_amount_date','DESC');
		$query = $this->db->get()->result();
		return $query;*/
		// $this->db->select('*');
		// $this->db->from('tblstaff');
		// $this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		// $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.status' => '3'));
		// $this->db->where("tblleads.lead_booking_amount_date >= tblstaff.pm_assign_date ");
		// //  $this->db->order_by('tblleads.lead_pm_takeup_date','DESC');
		//  $query1 = $this->db->get()->result();
		// //  echo $this->db->last_query();
		// //  die();
		//  $query2 = $this->db->query("SELECT * FROM `tblstaff` JOIN `tblleads` ON `tblleads`.`assigned` = `tblstaff`.`staffid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads`.`status` = '3' AND tblleads.lead_booking_amount_date < tblstaff.pm_assign_date;")->result();
		 
		 $query3 = $this->db->query("SELECT `tblleads_create_package`.*,`tblstaff`.`firstname`,`tblstaff`.`lastname`,`tblleads`.`email`,`tblleads`.`phonenumber` FROM `tblstaff` JOIN `tblleads_create_package` ON `tblleads_create_package`.`assigned` = `tblstaff`.`staffid` JOIN `tblleads` ON `tblleads`.`id` = `tblleads_create_package`.`leadid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads_create_package`.`pm_project_status` = '3' AND DATE_FORMAT(tblleads_create_package.lead_booking_amount_date,'%Y-%m-%d') >= DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ORDER BY `tblleads_create_package`.`lead_booking_amount_date` DESC;")->result();
		 $query4 = $this->db->query("SELECT `tblleads_create_package`.*,`tblstaff`.`firstname`,`tblstaff`.`lastname`,`tblleads`.`email`,`tblleads`.`phonenumber` FROM `tblstaff` JOIN `tblleads_create_package` ON `tblleads_create_package`.`assigned` = `tblstaff`.`staffid` JOIN `tblleads` ON `tblleads`.`id` = `tblleads_create_package`.`leadid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads_create_package`.`pm_project_status` = '3' AND DATE_FORMAT(tblleads_create_package.lead_booking_amount_date,'%Y-%m-%d') < DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ORDER BY `tblleads_create_package`.`lead_booking_amount_date` DESC;")->result();
		//  $query = array_merge($query1,$query2,$query3,$query4);
		 $query = array_merge($query3,$query4);
		 return $query;
}
		

	}
	function get_aquired_Projects(){


		$pm_id = $_SESSION['staff_user_id'];
if (is_admin() || is_headtrm()) {
	$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		 $this->db->where(array('tblleads.pm_project_status' => '1' ));
		$query1 = $this->db->get()->result();
		
		$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads_create_package', 'tblleads_create_package.assigned = tblstaff.staffid');
		 $this->db->where(array('tblleads_create_package.pm_project_status' => '1' ));
		
		$query2 = $this->db->get()->result();
		return array_merge($query1,$query2);
}else{
		// $this->db->select('*');
		// $this->db->from('tblstaff');
		// $this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		// $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.pm_project_status' => '1'));
		// $this->db->where("DATE_FORMAT(tblleads.lead_booking_amount_date,'%Y-%m-%d') >= DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ");
		//  $this->db->order_by('tblleads.lead_booking_amount_date','DESC');
		//  $query1 = $this->db->get()->result();
		// //   echo $this->db->last_query();	
		//  $query2 = $this->db->query("SELECT * FROM `tblstaff` JOIN `tblleads` ON `tblleads`.`assigned` = `tblstaff`.`staffid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads`.`pm_project_status` = '1' AND DATE_FORMAT(tblleads.lead_booking_amount_date,'%Y-%m-%d') < DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ORDER BY `tblleads`.`lead_booking_amount_date` DESC;")->result();

		 $query3 = $this->db->query("SELECT `tblleads_create_package`.*,`tblstaff`.`firstname`,`tblstaff`.`lastname`,`tblleads`.`email`,`tblleads`.`phonenumber` FROM `tblstaff` JOIN `tblleads_create_package` ON `tblleads_create_package`.`assigned` = `tblstaff`.`staffid` JOIN `tblleads` ON `tblleads`.`id` = `tblleads_create_package`.`leadid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads_create_package`.`pm_project_status` = '1' AND DATE_FORMAT(tblleads_create_package.lead_booking_amount_date,'%Y-%m-%d') >= DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ORDER BY `tblleads_create_package`.`lead_booking_amount_date` DESC;")->result();

		 $query4 = $this->db->query("SELECT `tblleads_create_package`.*,`tblstaff`.`firstname`,`tblstaff`.`lastname`,`tblleads`.`email`,`tblleads`.`phonenumber` FROM `tblstaff` JOIN `tblleads_create_package` ON `tblleads_create_package`.`assigned` = `tblstaff`.`staffid` JOIN `tblleads` ON `tblleads`.`id` = `tblleads_create_package`.`leadid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads_create_package`.`pm_project_status` = '1' AND DATE_FORMAT(tblleads_create_package.lead_booking_amount_date,'%Y-%m-%d') < DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ORDER BY `tblleads_create_package`.`lead_booking_amount_date` DESC;")->result();
		// $query = array_merge($query1,$query2,$query3,$query4);
		 $query = array_merge($query3,$query4);
		
		//  echo "<pre>";
		//  print_r($query2);
		//  echo "</pre>";
		//  exit;
		//  die();
		return $query;
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
	function getmultiBookData($id)
	{
		$this->db->select('tblleads.email,tblleads.phonenumber,tblleads_create_package.book_title as lead_booktitle,tblleads_create_package.*')
		->from('tblleads_create_package')
		->join('tblleads','tblleads_create_package.leadid = tblleads.id');
		return $this->db->where('tblleads_create_package.id',$id)->get()->row();
		// return $this->db->get_where('tblleads_create_package',array('id'=>$id))->row();
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
		 $query1 = $this->db->get()->result();
		
		$this->db->select('*');
		$this->db->from('tblstaff');
		$this->db->join('tblleads_create_package', 'tblleads_create_package.assigned = tblstaff.staffid');
		 $this->db->where(array('tblleads_create_package.pm_project_status' => '2' ));
		
		$query2 = $this->db->get()->result();
		return array_merge($query1,$query2);
}else{
		// $this->db->select('*');
		// $this->db->from('tblstaff');
		// $this->db->join('tblleads', 'tblleads.assigned = tblstaff.staffid');
		//  $this->db->where(array('tblstaff.pm_assign_to' => $pm_id, 'tblleads.pm_project_status' => '2'));
		//  $this->db->where("DATE_FORMAT(tblleads.lead_booking_amount_date,'%Y-%m-%d') >= DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ");
		//  $this->db->order_by('tblleads.lead_pm_takeup_date','DESC');
		// $query1 = $this->db->get()->result();

		// $query2 = $this->db->query("SELECT * FROM `tblstaff` JOIN `tblleads` ON `tblleads`.`assigned` = `tblstaff`.`staffid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads`.`pm_project_status` = '2' AND DATE_FORMAT(tblleads.lead_booking_amount_date,'%Y-%m-%d') < DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ORDER BY `tblleads`.`lead_pm_takeup_date` DESC;")->result();

		$query3 = $this->db->query("SELECT `tblleads_create_package`.*,`tblstaff`.`firstname`,`tblstaff`.`lastname`,`tblleads`.`email`,`tblleads`.`phonenumber` FROM `tblstaff` JOIN `tblleads_create_package` ON `tblleads_create_package`.`assigned` = `tblstaff`.`staffid` JOIN `tblleads` ON `tblleads`.`id` = `tblleads_create_package`.`leadid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads_create_package`.`pm_project_status` = '2' AND DATE_FORMAT(tblleads_create_package.lead_booking_amount_date,'%Y-%m-%d') >= DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ORDER BY `tblleads_create_package`.`lead_pm_takeup_date` DESC;")->result();
		$query4 = $this->db->query("SELECT `tblleads_create_package`.*,`tblstaff`.`firstname`,`tblstaff`.`lastname`,`tblleads`.`email`,`tblleads`.`phonenumber` FROM `tblstaff` JOIN `tblleads_create_package` ON `tblleads_create_package`.`assigned` = `tblstaff`.`staffid` JOIN `tblleads` ON `tblleads`.`id` = `tblleads_create_package`.`leadid` WHERE `tblstaff`.`pre_pm_assign_to` = '".$pm_id."' AND `tblleads_create_package`.`pm_project_status` = '2' AND DATE_FORMAT(tblleads_create_package.lead_booking_amount_date,'%Y-%m-%d') < DATE_FORMAT(tblstaff.pm_assign_date,'%Y-%m-%d') ORDER BY `tblleads_create_package`.`lead_pm_takeup_date` DESC;")->result();
		// $query = array_merge($query1,$query2,$query3,$query4);
		 $query = array_merge($query3,$query4);
		 return $query;
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

