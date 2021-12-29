<?php
   
   defined('BASEPATH') or exit('No direct script access allowed');
   
   /**
    * @property  db
    */
   class Leave_management extends CRM_Model
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
      
      public function getpendingApproval( $table, $members )
      {
         $loginid = $this->session->userdata('staff_user_id');
         $this->db->where('application_status', '1');
         $this->db->where('user_id', $loginid);
         $this->db->order_by("leave_application_id", "desc");
         $data = $this->db->get($table)->result();
		 
		 /* $this->db->select('tblleaveapplication.*, CONCAT(tblstaff.firstname," ",tblstaff.lastname) as full_name, tblstaff.staffid');
		 $this->db->from('tblleaveapplication');
		 $this->db->where('tblleaveapplication.application_status', '1');
		 $this->db->where('tblstaff.active', '1');
         //$this->db->where_in('user_id', $members);
		 $this->db->where("tblleaveapplication.user_id IN (".$members.")",NULL, false);
		 $this->db->join('tblstaff', 'tblleaveapplication.user_id = tblstaff.staffid');
         $this->db->order_by("tblleaveapplication.leave_application_id", "desc");
         $data = $this->db->get()->result(); */
		
         return $data;
      }
	  
	  public function getTeamPendingApproval( $table, $members )
      {
         $loginid = $this->session->userdata('staff_user_id');
		 $is_admin = $this->session->userdata('admin_role');
		 
		 $this->db->select('tblleaveapplication.*, CONCAT(tblstaff.firstname," ",tblstaff.lastname) as full_name, tblstaff.staffid');
		 $this->db->from('tblleaveapplication');
		 $this->db->where('tblleaveapplication.application_status', '1');
		 $this->db->where('tblstaff.active', '1');
		 //$this->db->where('user_id !=', $loginid);
		 if($is_admin != 1){
			$this->db->where("tblleaveapplication.user_id IN (".$members.")",NULL, false);
		 }
		 $this->db->join('tblstaff', 'tblleaveapplication.user_id = tblstaff.staffid');
         $this->db->order_by("tblleaveapplication.leave_application_id", "desc");
         $data = $this->db->get()->result();
		 //echo $this->db->last_query();exit;
         return $data;
      }
      
      public function getApprovalleave( $table )
      {
		 $where = "(`application_status` = '2' OR `application_status` = '1' OR `application_status` = '4')";
         $loginid = $this->session->userdata('staff_user_id');
         $this->db->where($where);
         $this->db->where('user_id', $loginid);
		 $this->db->order_by('leave_application_id', 'DESC');
         $data = $this->db->get($table)->result();
		 //echo $this->db->last_query();exit; 
         return $data;
      }
      
      public function getRejectedleave( $table )
      {
         $loginid = $this->session->userdata('staff_user_id');
         $this->db->where('application_status', '3');
         $this->db->where('user_id', $loginid);
         $data = $this->db->get($table)->result();
         return $data;
      }
      
      public function getholidays( $table )
      {
         
         $this->db->where(array());
         
         
         $data = $this->db->order_by("quota", "asc")->get($table)->result();
         return $data;
      }
      
      
      public function savedata( $table, $data )
      {
         $insert = $this->db->insert($table, $data);
         return $insert;
      }
      
      public function updatedata( $table, $result, $id )
      {
         $this->db->where('leave_category_id', $id);
         $up = $this->db->update($table, $result);
         return $up;
      }
      
      public function updateldata( $table, $result, $id )
      {
         $this->db->where('id', $id);
         $up = $this->db->update($table, $result);
         return $up;
      }
      
      public function deleteleavecategory( $table, $id )
      {
         $del = $this->db->delete($table, $id);
         return $del;
      }
      
      // select from database
      
      public function updateQuery( $tbl, $dat, $col )
      {
         
         $setdat = "";
         $y = 1;
         foreach ($dat as $name => $value) {
            
            $setdat .= " $name = '$value' ";
            if ( $y < count($dat) ) {
               $setdat .= ',';
            }
            
            $y++;
            
         }
         
         
         $set = "";
         $x = 1;
         foreach ($col as $name => $value) {
            $operator = $value[0];
            $value = $value[1];
            if ( $operator == 'like' ) {
               $set .= " lower($name) LIKE '%$value%' ";
            } else {
               $set .= " $name $operator '$value' ";
            }
            
            if ( $x < count($col) ) {
               $set .= ' and ';
            }
            
            $x++;
            
         }
         $sql = $this->db->query("UPDATE $tbl SET $setdat WHERE $set");
         return $sql;
      }

// select from database
      
      /**
       * @param $applicationId
       * @param $status
       */
      public function leave_report( $applicationId, $status )
      {
         $upd['application_status'] = $status;
         $this->db->where('leave_application_id', $applicationId);
         $this->db->update("tblleaveapplication", $upd);
         $leave_data = $this->db->get_where('tblleaveapplication', array('leave_application_id' => $applicationId))->row();
         if ( $status == 2 ) {
            $start_date = $leave_data->leave_start_date;
            $days = preg_replace("/[^0-9]/", "", $leave_data->duration);
            $attendance = array();
            for ($i = 0; $i < $days; $i++) {
               $date = date("Y-m-d", strtotime("+$i day", strtotime($start_date)));
               $attendance[] = array(
                  'user_id' => $leave_data->user_id,
                  'leave_application_id' => $leave_data->leave_application_id,
                  'date_in' => NULL,
                  'date_out' => $date,
                  'attendance_status' => 3,
                  'clocking_status' => 0
               );
            }
            $this->db->insert_batch('tbl_attendance', $attendance);
            $leave_message = 'leave_approved_message';
         } elseif ( $status == 3 ) {
            $leave_message = 'leave_rejected_message';
         }
         $notification_data = [
            'description' => $leave_message,
            'touserid' => $leave_data->user_id,
            'additional_data' => serialize([]),
         ];
         if ( add_notification($notification_data) ) {
            pusher_trigger_notification($leave_data->user_id);
         }
      }
      
      public function getData( $tbl, $col, $operator )
      {
         $set = "";
         $x = 1;
         foreach ($col as $name => $value) {
            
            $set .= " $name = '$value' ";
            if ( $x < count($col) ) {
               $set .= $operator;
            }
            
            $x++;
            
         }
         
         
         $sql = $this->db->query("SELECT * FROM $tbl WHERE $set");
         $data = $sql->result();
         return $data;
      }
      
      public function leavesubmitreport( $applicationId )
      {
         $stfId = $lastid;
         $data['stfdata'] = $this->leave_management->getData(' tblstaff', array('staffid' => $stfId), null);
         $name = $data['stfdata'][0]->firstname;
         $uid = '1';
         
         
         $notification_data = [
            'description' => 'leave_submit_message',
            'touserid' => $uid,
            'link' => 'leave/allleaveManagement',
            'additional_data' => serialize([]),
         ];

         if ( add_notification($notification_data) ) {
            pusher_trigger_notification($uid);
         }
      }
      
      function upload_holidays()
      {
         $total_imported = 0;
         
         $count = 0;
         $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
         while ($csv_line = fgetcsv($fp, 1024)) {
            $count++;
            if ( $count == 1 ) {
               continue;
            }
            for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
               if ( !empty($csv_line[0]) ) {
                  $insert_csv = array();
                  $insert_csv['name'] = $csv_line[0];
                  $insert_csv['leave_type'] = $csv_line[1];
                  $insert_csv['quota'] = $csv_line[2];
                  $insert_csv['leave_end_date'] = $csv_line[3];
                  $datestart = str_replace('/', '-', $insert_csv['quota']);
                  $newDatestart = date("Y-m-d", strtotime($datestart));
                  
                  
                  $dateend = str_replace('/', '-', $insert_csv['leave_end_date']);
                  $newDateend = date("Y-m-d", strtotime($dateend));
                  
                  
                  if ( $insert_csv['leave_type'] == "multiple_days" ) {
                     
                     $datetime1 = new DateTime($datestart);
                     $datetime2 = new DateTime($dateend);
                     
                     $interval = $datetime1->diff($datetime2);
                     $tempcount = $interval->format('%a');
                     $tempadd = "1";
                     $diff = $tempcount + $tempadd . " Days";
                     $diffenleav = "$tempcount+$tempadd";
                  } elseif ( $insert_csv['leave_type'] == "single_day" ) {
                     $diff = "1 Day";
                     $diffenleav = "1";
                  }
                  $insert_csv['days'] = $diff;
               }
            }
            $i++;
            $total_imported++;
            $data = array(
               
               'name' => $insert_csv['name'],
               'leave_type' => $insert_csv['leave_type'],
               'quota' => $newDatestart,
               'leave_end_date' => $newDateend,
               'days' => $insert_csv['days']
            );
            $data['crane_features'] = $this->db->insert('tblholidays', $data);
         }
         fclose($fp) or die("can't close file");
         
         
         $data['success'] = "success";
         set_alert('success', _l('import_total_imported', $total_imported));
         return $data;
      }
      
      public function deleteholibulk( $ids )
      {
         
         $this->db->where_in('id', $ids, FALSE, FALSE);
         $delete = $this->db->delete('tblholidays');
         print_r($this->db->last_query());
         // $delete = $this->db->delete($this->tblproducts);
         return $delete ? true : false;
      }
      
	  public function getTotalSpecialLeave($staff_id){
		  //echo $staff_id;exit;
		  $this->db->select_sum('quota');
		  $this->db->from('special_leave');
		  //$this->db->join('tblstaffdepartments', 'tblstaffdepartments.team_id = special_leave.team_id');
		  $this->db->where_in('special_leave.emp_id', $staff_id );
		  //$this->db->where('tblstaffdepartments.staffid', $staff_id );
		  //$this->db->or_where('special_leave.team_id', 0 );
		  $query = $this->db->get(); 
		  return $query->row();
	  }
	  
	  public function saveSpecialLeave($data){
		  $result = $this->db->insert('special_leave', $data);
		  return $result;
	  }
	  
	  public function getAppliedSpecialLeave($staff_id){
		  $this->db->select_sum('duration');
		  $this->db->from('tblleaveapplication');
		  $this->db->where_in('user_id', $staff_id );
		  $this->db->where('leave_category_id', 0);
		  $this->db->where('application_status', 2); 
		  $query = $this->db->get();
		  return $query->row();
	  }
	  
	 public function dataEmpToatalSpLeave($staffId){
		 $this->db->select_sum('quota');
		  $this->db->from('special_leave');
		  $this->db->where_in('emp_id', $staffId );
		  $query = $this->db->get();
		  //echo $this->db->last_query();
		  return $query->row();
	 }
	 
	 public function appliedSpecialLeave($staffId){
		  $this->db->select_sum('duration');
		  $this->db->from('tblleaveapplication');
		  //$this->db->where_in('user_id', $staff_id );
		  $this->db->where('user_id', $staffId );
		  $this->db->where('leave_category_id', 0);
		  $this->db->where('application_status', 2); 
		  $this->db->where('leave_start_date BETWEEN "'. date('Y-m-d', strtotime('2020-04-01')). '" and "'. date('Y-m-d', strtotime('2021-03-31')).'"');
		  $query = $this->db->get();
		  //echo $this->db->last_query();exit;
		  return $query->row();
	 }
	 
	 public function appliedTotalLeave($userid, $categoryid){
		 //echo $userid."--".$categoryid;exit;
		  $this->db->select_sum('duration');
		  $this->db->from('tblleaveapplication');
		  $this->db->where_in('user_id', $userid );
		  $this->db->where('leave_category_id', $categoryid);
		  $this->db->where('application_status', 2); 
		  $this->db->where('leave_start_date BETWEEN "'. date('Y-m-d', strtotime('2021-04-01')). '" and "'. date('Y-m-d', strtotime('2022-03-31')).'"');
		  $query = $this->db->get();
		  return $query->row();
	 }
	 
	 public function getLeaveQuota($categoryid){
		 //echo $categoryid."--";exit;
		 $this->db->select('leave_quota,leave_category');
		  $this->db->from('tblleavecategory');
		  $this->db->where('leave_category_id', $categoryid);
		  $query = $this->db->get();
		  return $query->row();
	 }
	 
	 
	 
   }
