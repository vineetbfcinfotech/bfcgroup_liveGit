<?php
   
   /*
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
    */
   
   /**
    * Description of attendance_model
    *
    * @author NaYeM
    */
   class Attendance_Model extends CRM_Model
   {
      
      public $_table_name;
      public $_order_by;
      public $_primary_key;
      
      public function get_employee_id_by_dept_id($departments_id)
      {
         $this->db->select('*');
         $this->db->from('');
         $this->db->where('departmentid', $departments_id);
         $query = $this->db->get();
         if ($query->num_rows()) {
            return $query->result();
         }
      }
      
      public function get_designation_by_dept_id($departments_id)
      {
         $this->db->select('tbl_designations.*', FALSE);
         $this->db->select('tbl_departments.*', FALSE);
         $this->db->from('tbl_designations');
         $this->db->join('tbl_departments', 'tbl_departments.departments_id = tbl_designations.departments_id', 'left');
         $this->db->where('tbl_departments.departments_id', $departments_id);
         $query_result = $this->db->get();
         $result = $query_result->result();
         return $result;
      }
      
      public function attendance_report_by_empid($user_id = null, $sdate = null, $flag = NULL, $leave = NULL)
      {
         
         $this->db->select('tbl_attendance.*', FALSE);
         $this->db->select('tbl_clock.*', FALSE);
         $this->db->select('tblstaff.staffid', FALSE);
         $this->db->from('tbl_attendance');
         $this->db->join('tbl_clock', 'tbl_clock.attendance_id  = tbl_attendance.attendance_id', 'left');
         $this->db->join('tblstaff', 'tbl_attendance.user_id  = tblstaff.staffid', 'left');
         $this->db->where('tbl_attendance.user_id', $user_id);
         $this->db->where('tbl_attendance.date_in', $sdate);
         //$this->db->where('tbl_attendance.date_out <=', $sdate);
         
         $query_result = $this->db->get();
         $result = $query_result->result();
         
         if (empty($result)) {
            //$val['attendance_status'] = $leave;
            $val['attendance_status'] = $flag;
            $val['date'] = $sdate;
            $result[] = (object)$val;
         } else {
            if ($result[0]->attendance_status == 0) {
               if ($flag == 'H') {
                  $result[0]->attendance_status = 'H';
               }
            }
         }
         return $result;
      }
      
      public function attendance_report_2_by_empid($user_id = null, $sdate = null, $flag = NULL, $leave = NULL)
      {
         
         $this->db->select('tbl_attendance.*', FALSE);
//        $this->db->select('tbl_clock.*', FALSE);
         $this->db->select('tbl_account_details.user_id', FALSE);
         $this->db->from('tbl_attendance');
//        $this->db->join('tbl_clock', 'tbl_clock.attendance_id  = tbl_attendance.attendance_id', 'left');
         $this->db->join('tbl_account_details', 'tbl_attendance.user_id  = tbl_account_details.user_id', 'left');
         $this->db->where('tbl_attendance.user_id', $user_id);
         $this->db->where('tbl_attendance.date_in', $sdate);
         //$this->db->where('tbl_attendance.date_out <=', $sdate);
         
         $query_result = $this->db->get();
         $result = $query_result->result();
         
         if (empty($result)) {
            //$val['attendance_status'] = $leave;
            $val['attendance_status'] = $flag;
            $val['date'] = $sdate;
            $result[] = (object)$val;
         } else {
            if ($result[0]->attendance_status == 0) {
               if ($flag == 'H') {
                  $result[0]->attendance_status = 'H';
               }
            }
         }
         return $result;
      }
      
      public function get_all_clock_history($clock_history_id = null)
      {
         
         $this->db->select('tbl_clock.*', FALSE);
         $this->db->select('tbl_clock_history.*', FALSE);
         $this->db->select('tbl_account_details.*', FALSE);
         $this->db->from('tbl_clock_history');
         $this->db->join('tbl_account_details', 'tbl_clock_history.user_id  = tbl_account_details.user_id', 'left');
         $this->db->join('tbl_clock', 'tbl_clock_history.clock_id  = tbl_clock.clock_id', 'left');
         if (!empty($clock_history_id)) {
            $this->db->where('tbl_clock_history.clock_history_id', $clock_history_id);
            $query_result = $this->db->get();
            $result = $query_result->row();
         } else {
            $this->db->order_by('tbl_clock_history.clock_history_id', "DESC");
            $query_result = $this->db->get();
            $result = $query_result->result();
         }
         return $result;
      }
      
      public function get_mytime_info($attendance_id = NULL, $clock_id = NULL)
      {
         
         $this->db->select('tbl_attendance.*', FALSE);
         $this->db->select('tbl_clock.*', FALSE);
         $this->db->from('tbl_attendance');
         $this->db->join('tbl_clock', 'tbl_clock.attendance_id  = tbl_attendance.attendance_id', 'left');
         if (!empty($attendance_id)) {
            $this->db->where('tbl_attendance.attendance_id', $attendance_id);
            $query_result = $this->db->get();
            $result = $query_result->result();
         }
         if (!empty($clock_id)) {
            $this->db->where('tbl_clock.clock_id', $clock_id);
            $query_result = $this->db->get();
            $result = $query_result->row();
         }
         
         return $result;
      }
      
      function getReport($year, $month, $day)
      {
         if (!is_null($month) && !is_null($day)) {
            $this->db->where(array('date_in' => YEAR($year)));
         } elseif (is_null($day) && !is_null($month)) {
            $date = $year . '-' . $month;
            $this->db->where(array('date_in' => MONTH($date)));
         } else {
            $date = $year . '-' . $month . '-' . $day;
            $this->db->where(array('date_in' => DAY($date)));
         }
         $query = $this->db->get('tbl_attendance');
         print_r($this->db->last_query());
         return $query->result_array();
         
      }
      
      function getCourntMonthReport($month)
      {
         $year = date('Y');
         //$query=$this->db->query("SELECT SUM(IF(a.attendance_status = '1', 1, 0)) AS present, SUM(IF(a.attendance_status = '3', 1, 0)) AS leaved,SUM(IF(a.attendance_status = '0', 1, 0)) AS apsent,s.firstname as title,a.user_id,a.attendance_id,a.leave_application_id,a.date_in as start,a.date_out as end,CASE WHEN a.attendance_id = 1 THEN 'P' WHEN a.attendance_id = 3 THEN 'L' ELSE 'A' END AS attendance_status,CONCAT(s.firstname,' ',s.lastname) as full_name, CASE WHEN a.clocking_status = 1 THEN \"Clock In\" ELSE \"Clock Out\" END AS clocking_status FROM tbl_attendance as a JOIN tblstaff as s ON s.staffid = a.user_id WHERE MONTH(a.date_in) = '$month' AND YEAR(a.date_in) = '$year' GROUP BY date_in");
         $query=$this->db->query("SELECT SUM(IF(attendance_status = '1', 1, 0)) AS present, SUM(IF(attendance_status = '3', 1, 0)) AS leaved,SUM(IF(attendance_status = '0', 1, 0)) AS absent,date_in as start,date_out as end FROM tbl_attendance WHERE MONTH(date_in) = '$month' AND YEAR(date_in) = '$year' GROUP BY date_in");
         return $query->result_array();
      }
	  
	  public function getLeaveDay($alldate){
		  $this->db->select("*");
		  $this->db->from("tblholidays");
		  $this->db->where("quota", $alldate);
		  $query = $this->db->get();
		  $result = $query->result();
		  return $result;
	  }
	  
	  public function getAttendanceData($date, $userId){
		  //echo $userId."<br>";
		  $this->db->select("*");
		  $this->db->from("deviceLogs_2_2020");
		  $this->db->where("LogDate", $date);
		  $this->db->where("UserId", $userId);
		  return $this->db->get()->row();
		  //echo $this->db->last_query()."<br>";
	  }
	  
	  public function getWorkGroupData(){
		  echo "Test";exit;
	  }
   }
