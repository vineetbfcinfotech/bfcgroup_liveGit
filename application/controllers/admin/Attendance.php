<?php
   /*
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
    */
   
   /**
    * Description of attendance
    *
    * @property  session
    * @property  staff_model
    * @property  staff_model
    * @property  db
    * @author NaYeM
    */
   class Attendance extends Admin_controller
   {
      
      public function __construct()
      {
         parent::__construct();
         $this->load->model('attendance_model');
         
         $this->load->model('teams_model', 'teamsm');
      }
      
      /**
       *
       */
       
      public function view_attendance()
      {
          /* $this->db->select('*');
         $data['monthatts'] = $this->db->get('deviceLogs_12_2019')->result();
         $data['list'] = array();
        $month = '12';
        $year = '2019';
        for($d='1'; $d<='31'; $d++)
        {
            $time=mktime(12, 0, 0, $month, $d, $year);          
            if (date('m', $time)==$month)       
                $data['list'][]=date('Y-m-d', $time);
        }
         $data['title'] = "View Attendance";
         $this->db->select("CONCAT(firstname, ' ', lastname) AS name, staffid,bio_id");
         $this->db->where('active', '1');
          $data['staff_members'] = $this->db->get('tblstaff')->result(); */
		  
		  
		$data['user_id'] = $this->session->userdata('staff_user_id');
		
		$attendance_info = $this->db->get_where('tbl_attendance', array('user_id' => $data['user_id']))->result();
		$data['mytime_info'] = $this->get_mytime_info($attendance_info);
		if (is_admin()){ 
			$this->db->select('*');
			$this->db->order_by('department_id');
			$this->db->where('active',1);
			$ignore = array(1, 25,26,52);
			$this->db->where_not_in('staffid', $ignore);
			$data['staff_members'] = $this->db->get('tblstaff')->result_array();
		}elseif(herapermission()){
			$arr = herapermission();
			$staffId = explode(",",$arr);
			$this->db->select('*');
			$this->db->order_by('department_id');
			$this->db->where('active',1);
			$this->db->where_in('staffid', $staffId);
			$data['staff_members'] = $this->db->get('tblstaff')->result_array();
		}
		//echo $this->db->last_query();exit; 


		$data['list'] = array();
		$startDate = 1;
		if($_POST){
			$startdate=strtotime($this->input->post('start'));
			$month=date("m",$startdate);
			$year = date("Y", $startdate);
			$startDate = date("d",$startdate);
			$data['start'] = $this->input->post('start');
		}else{
			//$month = '11';
			$month = date("m");
			//$year = '2020';
			$year = date("Y");
		}
		if($month == date("m") && $year == date("Y"))
		{
			$datelimit = date("d");
		}
		else
		{
			$datelimit = '31';
		}
		if($_POST){
			$endtdate=strtotime($this->input->post('end'));
			$datelimit = date("d",$endtdate);
			//$year = date("Y", $endtdate);
			$data['end'] = $this->input->post('end');
		}
		for($d=$startDate; $d<=$datelimit; $d++)
		{
			$time=mktime(12, 0, 0, $month, $d, $year);          
			if (date('m', $time)==$month)       
				$data['list'][]=date('Y-m-d', $time);
		}
			
        $this->load->view('admin/attendance/view_attendance', $data);
      }
      
      public function time_history()
      {
         $search = $this->input->post('search', TRUE);
		 
         if (!empty($search)) {
            $data['edit'] = true;
         }
         $user_id = $this->input->post('staff_id', TRUE);
         if (!empty($user_id)) {
            $data['user_id'] = $user_id;
         } else {
            $data['user_id'] = $this->session->userdata('staff_user_id');
         }
         $data['active'] = date('Y');
         //it();
		 
		 $filterStaff = $this->input->post('staff_name');
		 $data['selectedStaff'] = $filterStaff;
		 
         $attendance_info = $this->db->get_where('tbl_attendance', array('user_id' => $data['user_id']))->result();
         $data['mytime_info'] = $this->get_mytime_info($attendance_info);
         $this->db->select('*');
         //$this->db->order_by('department_id');
         
		 if($this->input->post('staff_name') != ""){
			$this->db->where_in('staffid', $filterStaff);
		 }else{
		    $this->db->where('active',1);
			$ignore = array(1, 25,26);
			//$this->db->or_where('staffId',70);
			$this->db->where_not_in('staffid', $ignore);
		 }
		 $this->db->order_by("firstname", ASC);
         $data['staff_members'] = $this->db->get('tblstaff')->result_array();
        //echo $this->db->last_query();exit;
         
         $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname, tblstaff.staffid');
         $this->db->where('active',1);
         //$this->db->or_where('staffId',70);
		 $ignore = array(1, 25,26);
		 $this->db->where_not_in('staffid', $ignore);
		 $this->db->order_by('firstname', ASC);
         $data['staffs'] = $this->db->get('tblstaff')->result();
         //print_r($_POST[]);exit;
         $data['list'] = array();
            $startDate = 1;
			if($_POST){
				$startdate=strtotime($this->input->post('start'));
				$month=date("m",$startdate);
				$year = date("Y", $startdate);
				$startDate = date("d",$startdate);
				$data['start'] = $this->input->post('start');
			}else{
				//$month = '11';
				$month = date("m");
				//$year = '2020';
				$year = date("Y");
			}
            if($month == date("m") && $year == date("Y"))
            {
                $datelimit = date("d");
            }
            else
            {
                $datelimit = '31';
            }
			if($_POST){
				$endtdate=strtotime($this->input->post('end'));
				$datelimit = date("d",$endtdate);
				//$year = date("Y", $endtdate);
				$data['end'] = $this->input->post('end');
			}
            for($d=$startDate; $d<=$datelimit; $d++)
            {
                $time=mktime(12, 0, 0, $month, $d, $year);          
                if (date('m', $time)==$month)       
                    $data['list'][]=date('Y-m-d', $time);
            }
			/* echo "<pre>";
			print_r($data['list']);exit; */
         
         $this->load->view('admin/attendance/time_history', $data);
      }
      
      public function get_mytime_info($attendance_info)
      {
         if (!empty($attendance_info)) {
            foreach ($attendance_info as $v_info) {
               if ($v_info->date_in == $v_info->date_out) {
                  $date = strftime(config_item('date_format'), strtotime($v_info->date_in));
               } else {
                  $date = _l('date_in') . ' : ' . strftime(config_item('date_format'), strtotime($v_info->date_in)) . ', ' . _l('day_out') . ': ' . strftime(config_item('date_format'), strtotime($v_info->date_out));
               }
               $clock_info[date('Y', strtotime($v_info->date_in))][date('W', strtotime($v_info->date_in))][$date] = $this->attendance_model->get_mytime_info($v_info->attendance_id);
            }
            return $clock_info;
         }
      }
      
      public function time_history_pdf($user_id)
      {
         $data['title'] = _l('time_logs'); //Page title
         $this->attendance_model->_table_name = "tbl_attendance"; // table name
         $this->attendance_model->_order_by = "user_id"; // $id
         $attendance_info = $this->attendance_model->get_by(array('user_id' => $user_id), FALSE);
         $data['user_info'] = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
         $data['mytime_info'] = $this->get_mytime_info($attendance_info);
         $this->load->helper('dompdf');
         $viewfile = $this->load->view('admin/attendance/all_timehistory_pdf', $data, TRUE);
         pdf_create($viewfile, _l('time_logs') . '-' . $data['user_info']->fullname);
      }
      
      /**
       *
       */
      public function mark_attendance()
      {
         $date = date('Y-m-d');
         $data['title'] = _l('mark_attendance');
         $data['users'] = $this->staff_model->get('', ['active' => 1]);
         $data['date'] = $date;
         $date = date('Y-m-d');
         $month = date('n', strtotime($date));
         $year = date('Y', strtotime($date));
         $day = date('d', strtotime($date));
         
         if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
         } else {
            $yymm = $year . '-' . $month;
         }
         foreach ($data['users'] as $sl => $v_employee) {
            if ($v_employee['staffid'] != $this->session->userdata('staff_user_id')) {
               $x = 1;
               if ($day >= 1 && $day <= 9) {
                  $sdate = $yymm . '-' . '0' . $day;
               } else {
                  $sdate = $yymm . '-' . $day;
               }
               $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $day)));
               
               // get leave info
               if (!empty($holidays)) {
                  foreach ($holidays as $v_holiday) {
                     if ($v_holiday->day == $day_name) {
                        $flag = 'H';
                     }
                  }
               }
               if (!empty($p_hday)) {
                  foreach ($p_hday as $v_hday) {
                     if ($v_hday == $sdate) {
                        $flag = 'H';
                     }
                  }
               }
               if (!empty($flag)) {
                  $data['attendace_info'][$sl] = $this->attendance_model->attendance_report_by_empid($v_employee['staffid'], $sdate, $flag);
               } else {
                  $data['attendace_info'][$sl] = $this->attendance_model->attendance_report_by_empid($v_employee['staffid'], $sdate);
               }
               $flag = '';
            }
         }
         $this->load->view('admin/attendance/mark_attendance', $data); //page load
      }
      
      public function update_clock()
      {
         $clock_in = $this->input->post('clock_in', true);
         $clock_out = $this->input->post('clock_out', true);
         if (!empty($clock_in)) {
            foreach ($clock_in as $user_id) {
               $this->set_clocking(0, $user_id, true, true);
            }
         }
         if (!empty($clock_out)) {
            foreach ($clock_out as $clock_out_id) {
               $clock_id = $this->input->post($clock_out_id, true);
               $this->set_clocking($clock_id, $clock_out_id, 0, true);
            }
         }
         redirect($_SERVER["HTTP_REFERER"]);
      }
      
      public function set_clocking($id = NULL, $user_id = null, $row = null, $redirect = null)
      {
         
         if ($id == 0) {
            $id = null;
         }
         if ($row == 0) {
            $row = null;
         }
         // sate into attendance table
         if (!empty($user_id)) {
            $adata['user_id'] = $user_id;
         } else {
            $adata['user_id'] = $this->session->userdata('user_id');
         }
         
         if (!empty($row)) {
            $clocktime = 1;
         } else {
            $clocktime = $this->input->post('clocktime', TRUE);
         }
         
         $date = $this->input->post('clock_date', TRUE);
         $clock_date = date('Y', strtotime($date));
         if (empty($date)) {
            $date = date('Y-m-d');
         } elseif ($clock_date >= 1969 && $clock_date <= 1999) {
            $date = date('Y-m-d');
         }
         $time = $this->input->post('clock_time', TRUE);
         if (empty($time)) {
            $time = date('h:i:s');;
         }
//        $already_clocking = $this->admin_model->check_by(array('user_id' => $adata['user_id'], 'clocking_status' => 1), 'tbl_attendance');
         if ($clocktime == 1) {
            $adata['date_in'] = $date;
         } else {
            $adata['date_out'] = $date;
         }
         
         if (!empty($adata['date_in'])) {
            // check existing date is here or not
            $check_date = $this->db->get_where('tbl_attendance', array('user_id' => $adata['user_id'], 'date_in' => $adata['date_in']))->row();
            //$this->admin_model->check_by(, '');
         }
         if (!empty($check_date)) { // if exis do not save date and return id
            $this->attendance_model->_table_name = "tbl_attendance"; // table name
            $this->attendance_model->_primary_key = "attendance_id"; // $id
            
            if ($check_date->attendance_status != '1') {
               $udata['attendance_status'] = 1;
               $this->attendance_model->save($udata, $check_date->attendance_id);
            }
            if ($check_date->clocking_status == 0) {
               $udata['date_out'] = $date;
               $udata['clocking_status'] = 1;
               $this->attendance_model->save($udata, $check_date->attendance_id);
            }
            $data['attendance_id'] = $check_date->attendance_id;
         } else { // else save data into tbl attendance
            // get attendance id by clock id into tbl clock
            // if attendance id exist that means he/she clock in
            // return the id
            // and update the day out time
            
            $check_existing_data = $this->db->get_where('tbl_clock', array('clock_id' => $id))->row();
            $this->attendance_model->_table_name = "tbl_attendance"; // table name
            $this->attendance_model->_primary_key = "attendance_id"; // $id
            if (!empty($check_existing_data)) {
               $adata['clocking_status'] = 0;
               $this->attendance_model->save($adata, $check_existing_data->attendance_id);
            } else {
               $adata['attendance_status'] = 1;
               $adata['clocking_status'] = 1;
               //save data into attendance table
               $data['attendance_id'] = $this->attendance_model->save($adata);
            }
         }
         // save data into clock table
         if ($clocktime == 1) {
            $data['clockin_time'] = $time;
         } else {
            $data['clockout_time'] = $time;
            $data['comments'] = $this->input->post('comments', TRUE);
         }
         $data['ip_address'] = $this->input->ip_address();
         //save data in database
         $this->attendance_model->_table_name = "tbl_clock"; // table name
         $this->attendance_model->_primary_key = "clock_id"; // $id
         if (!empty($id)) {
            $data['clocking_status'] = 0;
            $this->attendance_model->save($data, $id);
         } else {
            $data['clocking_status'] = 1;
            $id = $this->attendance_model->save($data);
            if (!empty($check_date)) {
               if ($check_date->clocking_status == 1) {
                  $data['clockout_time'] = $time;
                  $data['clocking_status'] = 0;
                  $this->attendance_model->save($data, $id);
               }
            }
         }
         if (empty($redirect)) {
            redirect($_SERVER["HTTP_REFERER"]);
         } else {
            return true;
         }
      }
      
      public function edit_mytime($clock_id)
      {
         
         $data['title'] = _l('edit_time');
         $attendance_id = NULL;
         $data['clock_info'] = $this->attendance_model->get_mytime_info($attendance_id, $clock_id);
         $data['modal_subview'] = $this->load->view('admin/attendance/edit_mytime', $data, FALSE);
         $this->load->view('admin/_layout_modal', $data);
      }
      
      public function cheanged_mytime($clock_id)
      {
         
         $cdata = $this->attendance_model->array_from_post(array('reason', 'clockin_edit', 'clockout_edit'));
         
         $data['clock_id'] = $clock_id;
         $data['user_id'] = $this->session->userdata('user_id');
         $data['clockin_edit'] = display_time($cdata['clockin_edit']);
         $data['clockout_edit'] = display_time($cdata['clockout_edit']);
         $data['reason'] = $cdata['reason'];
         
         //save data in database
         $this->attendance_model->_table_name = "tbl_clock_history"; // table name
         $this->attendance_model->_order_by = "clock_history_id"; // $id
         $history_id = $this->attendance_model->save($data);
         
         $user_type = $this->session->userdata('user_type');
         if ($user_type == 1) {
            $msg = _l('time_change_request_admin');
            $cldata['clockin_time'] = $data['clockin_edit'];
            $cldata['clockout_time'] = $data['clockout_edit'];
            
            $this->attendance_model->_table_name = 'tbl_clock';
            $this->attendance_model->_primary_key = 'clock_id';
            $this->attendance_model->save($cldata, $clock_id);
            
            $adata['status'] = '2';
            
            $this->attendance_model->_table_name = 'tbl_clock_history';
            $this->attendance_model->_primary_key = 'clock_history_id';
            $this->attendance_model->save($adata, $history_id);
         } else {
            $msg = _l('time_change_request_staff');
            $all_admin = $this->db->where('role_id', 1)->get('tbl_users')->result();
            $notifyUser = array();
            if (!empty($all_admin)) {
               foreach ($all_admin as $v_admin) {
                  if (!empty($v_admin)) {
                     if ($v_admin->user_id != $this->session->userdata('user_id')) {
                        array_push($notifyUser, $v_admin->user_id);
                        add_notification(array(
                           'to_user_id' => $v_admin->user_id,
                           'description' => 'not_timechange_request',
                           'icon' => 'file-text',
                           'link' => 'admin/attendance/view_changerequest/' . $history_id,
                           'value' => _l('edited') . ' ' . _l('by') . ' ' . $this->session->userdata('name'),
                        ));
                     }
                  }
               }
            }
            if (!empty($notifyUser)) {
               show_notification($notifyUser);
            }
         }
         
         $user_info = $this->db->where('user_id', $this->session->userdata('user_id'))->get('tbl_account_details')->row();
         // save into activities
         $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'attendance',
            'module_field_id' => $clock_id,
            'activity' => 'activity_time_change_request',
            'icon' => 'fa-clock-o',
            'link' => 'admin/attendance/view_changerequest/' . $history_id,
            'value1' => $user_info->fullname,
            'value2' => $data['reason'],
         );
         
         // Update into tbl_project
         $this->attendance_model->_table_name = "tbl_activities"; //table name
         $this->attendance_model->_primary_key = "activities_id";
         $this->attendance_model->save($activities);
         
         // messages for user
         $type = "success";
         $message = $msg;
         set_message($type, $message);
         redirect('admin/attendance/time_history');
      }
      
      public function add_time_manually()
      {
         
         $data['title'] = _l('view_timerequest');
         // retrive all data from department table
         $data['all_employee'] = $this->attendance_model->get_all_employee();
         
         $data['subview'] = $this->load->view('admin/attendance/add_time_manually', $data, FALSE);
         $this->load->view('admin/_layout_modal', $data);
      }
      
      public function saved_manual_time()
      {
         
         $adata = $this->attendance_model->array_from_post(array('user_id', 'date_in', 'date_out'));
         $date_in = date('Y', strtotime($adata['date_in']));
         if (empty($adata['date_in'])) {
            $adata['date_in'] = date('Y-m-d');
         } elseif ($date_in >= 1969 && $date_in <= 1999) {
            $adata['date_in'] = date('Y-m-d');
         }
         $date_in = date('Y', strtotime($adata['date_out']));
         if (empty($adata['date_out'])) {
            $adata['date_out'] = date('Y-m-d');
         } elseif ($date_in >= 1969 && $date_in <= 1999) {
            $adata['date_out'] = date('Y-m-d');
         }
         $check_date = $this->attendance_model->check_by(array('user_id' => $adata['user_id'], 'date_in' => $adata['date_in']), 'tbl_attendance');
         $this->attendance_model->_table_name = "tbl_attendance"; // table name
         $this->attendance_model->_primary_key = "attendance_id"; // $id
         
         if (!empty($check_date)) { // if exis do not save date and return id
            if ($check_date->attendance_status != '1') {
               $adata['attendance_status'] = 1;
               $this->attendance_model->save($adata, $check_date->attendance_id);
            }
            $data['attendance_id'] = $check_date->attendance_id;
         } else { // else save data into tbl attendance
            $adata['attendance_status'] = 1;
            //save data into attendance table
            $data['attendance_id'] = $this->attendance_model->save($adata);
         }
         
         $data['clockin_time'] = display_time($this->input->post('clockin_time', TRUE));
         $data['clockout_time'] = display_time($this->input->post('clockout_time', TRUE));
         $data['clocking_status'] = 0;
         //save data in database
         $this->attendance_model->_table_name = "tbl_clock"; // table name
         $this->attendance_model->_primary_key = "clock_id"; // $id
         $id = $this->attendance_model->save($data);
         $user_info = $this->db->where('user_id', $adata['user_id'])->get('tbl_account_details')->row();
         $hdata['user_id'] = $adata['user_id'];
         $hdata['clock_id'] = $id;
         $hdata['clockin_edit'] = $data['clockin_time'];
         $hdata['clockout_edit'] = $data['clockout_time'];
         $hdata['reason'] = _l('manually_added') . ' ' . _l('by') . ' ' . $user_info->fullname;
         $user_type = $this->session->userdata('user_type');
         if ($user_type == 1) {
            $hdata['status'] = '2';
         }
         $this->attendance_model->_table_name = "tbl_clock_history"; // table name
         $this->attendance_model->_primary_key = "clock_history_id"; // $id
         $clock_history_id = $this->attendance_model->save($hdata);
         
         
         // save into activities
         $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'attendance',
            'module_field_id' => $data['attendance_id'],
            'activity' => 'activity_time_manually',
            'icon' => 'fa-clock-o',
            'link' => 'admin/attendance/view_changerequest/' . $clock_history_id,
            'value1' => $user_info->fullname,
            'value2' => $adata['date_in'] . ' To ' . $adata['date_out'],
         );
         
         // Update into tbl_project
         $this->attendance_model->_table_name = "tbl_activities"; //table name
         $this->attendance_model->_primary_key = "activities_id";
         $this->attendance_model->save($activities);
         
         if ($user_type != 1) {
            $all_admin = $this->db->where('role_id', 1)->get('tbl_users')->result();
            $notifyUser = array();
            if (!empty($all_admin)) {
               foreach ($all_admin as $v_admin) {
                  if (!empty($v_admin)) {
                     if ($v_admin->user_id != $this->session->userdata('user_id')) {
                        array_push($notifyUser, $v_admin->user_id);
                        add_notification(array(
                           'to_user_id' => $v_admin->user_id,
                           'description' => 'not_timechange_request',
                           'icon' => 'file-text',
                           'link' => 'admin/attendance/view_changerequest/' . $clock_history_id,
                           'value' => _l('manually_added') . ' ' . _l('by') . ' ' . $user_info->fullname,
                        ));
                     }
                  }
               }
            }
            if (!empty($notifyUser)) {
               show_notification($notifyUser);
            }
         }
         
         $type = "success";
         $message = _l('time_manually_added');
         set_message($type, $message);
         redirect('admin/attendance/timechange_request'); //redirect page
         
      }
      
      public function timechange_request()
      {
         $data['title'] = _l('timechange_request');
         $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
         $this->load->view('admin/attendance/list_all_request', $data);
      }
      
      public function view_changerequest($clock_history_id)
      {
         $data['title'] = _l('view_timerequest');
         $data['clock_history'] = $this->attendance_model->get_all_clock_history($clock_history_id);
         $data['subview'] = $this->load->view('admin/attendance/request_details', $data, TRUE);
         $this->load->view('admin/_layout_main', $data);
      }
      
      public function view_timerequest($clock_history_id)
      {
         $data['title'] = _l('view_timerequest');
         $data['clock_history'] = $this->attendance_model->get_all_clock_history($clock_history_id);
         $data['subview'] = $this->load->view('admin/attendance/request_details', $data, FALSE);
         $this->load->view('admin/_layout_modal_lg', $data);
      }
      
      public function delete_request($id)
      {
         $clock_history = $this->attendance_model->check_by(array('clock_history_id' => $id), 'tbl_clock_history');
         // save into activities
         $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'attendance',
            'module_field_id' => $id,
            'activity' => 'activity_delete_timechage_request',
            'icon' => 'fa-clock-o',
            'value1' => _l('clock_in') . ' ' . display_time($clock_history->clockin_edit),
            'value2' => _l('clock_out') . ' ' . display_time($clock_history->clockout_edit),
         );
         // Update into tbl_project
         $this->attendance_model->_table_name = "tbl_activities"; //table name
         $this->attendance_model->_primary_key = "activities_id";
         $this->attendance_model->save($activities);
         
         //save data into table.
         $this->attendance_model->_table_name = "tbl_clock_history"; // table name
         $this->attendance_model->_primary_key = "clock_history_id"; // $id
         $this->attendance_model->delete($id);
         
         $type = "success";
         $message = _l('delete_timechage_request');
         
         echo json_encode(array("status" => $type, 'message' => $message));
      }
      
      public function set_time_status($history_id)
      {
         // get input status
         // if status == 1 its pending
         // if status == 2 its accept  and set timein/timeout into tbl_clock
         // and 3 == reject
         $status = $this->input->post('status', TRUE);
         $clock_history_info = $this->invoice_model->check_by(array('clock_history_id' => $history_id), 'tbl_clock_history');
         
         if ($status == 2) {
            $clock_id = $this->input->post('clock_id', TRUE);
            $clockin_time = $this->input->post('clockin_time', TRUE);
            if (!empty($clockin_time)) {
               $data['clockin_time'] = $clockin_time;
            }
            $clockout_time = $this->input->post('clockout_time', TRUE);
            if (!empty($clockout_time)) {
               $data['clockout_time'] = $clockout_time;
            }
            $this->attendance_model->_table_name = 'tbl_clock';
            $this->attendance_model->_primary_key = 'clock_id';
            $this->attendance_model->save($data, $clock_id);
            $adata['status'] = $status;
            
            $this->attendance_model->_table_name = 'tbl_clock_history';
            $this->attendance_model->_primary_key = 'clock_history_id';
            $this->attendance_model->save($adata, $history_id);
            
            $type = "success";
            $message = _l('time_change_request') . ' ' . _l('accepted');
            $description = 'time_change_request_accepted';
         } else {
            $data['status'] = $status;
            $this->attendance_model->_table_name = 'tbl_clock_history';
            $this->attendance_model->_primary_key = 'clock_history_id';
            $this->attendance_model->save($data, $history_id);
            
            $type = "error";
            $message = _l('time_change_request') . ' ' . _l('rejected');
            $description = 'time_change_request_rejected';
         }
         // save into activities
         $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'attendance',
            'module_field_id' => $history_id,
            'activity' => $description,
            'icon' => 'fa-clock-o',
            'link' => 'admin/attendance/view_changerequest/' . $history_id,
            'value1' => _l('by') . ' ' . $this->session->userdata('name'),
         );
// Update into tbl_project
         $this->attendance_model->_table_name = "tbl_activities"; //table name
         $this->attendance_model->_primary_key = "activities_id";
         $this->attendance_model->save($activities);
         $notifyUser = array($clock_history_info->user_id);
         if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
               add_notification(array(
                  'to_user_id' => $v_user,
                  'description' => $description,
                  'icon' => 'clock-o',
                  'link' => 'admin/attendance/view_changerequest/' . $history_id,
                  'value' => _l('by') . ' ' . $this->session->userdata('name'),
               ));
            }
         }
         if (!empty($notifyUser)) {
            show_notification($notifyUser);
         }
         
         set_message($type, $message);
         redirect('admin/attendance/timechange_request'); //redirect page
      }
      
      public function attendance_report()
      {
         $data['title'] = _l('attendance_report');
         $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
         
         if (config_item('attendance_report') == 1) {
            $subview = 'attendance_report';
         } elseif (config_item('attendance_report') == 2) {
            $subview = 'attendance_report_2';
         } else {
            $subview = 'attendance_report_3';
         }
         $this->load->view('admin/attendance/' . $subview, $data);
      }
      
      public function get_report()
      {
         $departments_id = $this->input->post('departments_id', TRUE);
         $date = $this->input->post('date', TRUE);
         
         $month = date('n', strtotime($date));
         $year = date('Y', strtotime($date));
         $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
         $data['employee_info'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
         
         $holidays = $this->global_model->get_holidays(); //tbl working Days Holiday
         
         if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
         } else {
            $yymm = $year . '-' . $month;
         }
         
         $public_holiday = $this->global_model->get_public_holidays($yymm);
         
         //tbl a_calendar Days Holiday
         if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
               $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
         }
         
         foreach ($data['employee_info'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {
               
               if ($i >= 1 && $i <= 9) {
                  $sdate = $yymm . '-' . '0' . $i;
               } else {
                  $sdate = $yymm . '-' . $i;
               }
               $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));
               
               $data['week_info'][date('W', strtotime($sdate))][$sdate] = $sdate;
               
               // get leave info
               if (!empty($holidays)) {
                  foreach ($holidays as $v_holiday) {
                     if ($v_holiday->day == $day_name) {
                        $flag = 'H';
                     }
                  }
               }
               if (!empty($p_hday)) {
                  foreach ($p_hday as $v_hday) {
                     if ($v_hday == $sdate) {
                        $flag = 'H';
                     }
                  }
               }
               if (!empty($flag)) {
                  $data['attendace_info'][date('W', strtotime($sdate))][$sdate][$v_employee->user_id] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
               } else {
                  $data['attendace_info'][date('W', strtotime($sdate))][$sdate][$v_employee->user_id] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
               }
               $key++;
               $flag = '';
            }
         }
         $data['title'] = _l('attendance_report');
         $this->attendance_model->_table_name = "tbl_departments"; //table name
         $this->attendance_model->_order_by = "departments_id";
         $data['all_department'] = $this->attendance_model->get();
         $data['departments_id'] = $this->input->post('departments_id', TRUE);
         $data['date'] = $this->input->post('date', TRUE);
         $where = array('departments_id' => $departments_id);
         $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
         
         $data['month'] = date('F-Y', strtotime($yymm));
         
         $data['subview'] = $this->load->view('admin/attendance/attendance_report', $data, TRUE);
         $this->load->view('admin/_layout_main', $data);
      }
      
      public function get_report_2()
      {
         $departments_id = $this->input->post('departments_id', TRUE);
         $date = $this->input->post('date', TRUE);
         $month = date('n', strtotime($date));
         $year = date('Y', strtotime($date));
         $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
         
         $data['employee'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
         $day = date('d', strtotime($date));
         for ($i = 1; $i <= $num; $i++) {
            $data['dateSl'][] = $i;
         }
         $holidays = $this->global_model->get_holidays(); //tbl working Days Holiday
         
         if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
         } else {
            $yymm = $year . '-' . $month;
         }
         
         $public_holiday = $this->global_model->get_public_holidays($yymm);
         
         
         //tbl a_calendar Days Holiday
         if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
               $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
         }
         foreach ($data['employee'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {
               
               if ($i >= 1 && $i <= 9) {
                  
                  $sdate = $yymm . '-' . '0' . $i;
               } else {
                  $sdate = $yymm . '-' . $i;
               }
               $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));
               
               
               if (!empty($holidays)) {
                  foreach ($holidays as $v_holiday) {
                     
                     if ($v_holiday->day == $day_name) {
                        $flag = 'H';
                     }
                  }
               }
               if (!empty($p_hday)) {
                  foreach ($p_hday as $v_hday) {
                     if ($v_hday == $sdate) {
                        $flag = 'H';
                     }
                  }
               }
               if (!empty($flag)) {
                  $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate, $flag);
               } else {
                  $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate);
               }
               
               $key++;
               $flag = '';
            }
         }
         
         $data['title'] = _l('attendance_report');
         $this->attendance_model->_table_name = "tbl_departments"; //table name
         $this->attendance_model->_order_by = "departments_id";
         $data['all_department'] = $this->attendance_model->get();
         $data['departments_id'] = $this->input->post('departments_id', TRUE);
         $data['date'] = $this->input->post('date', TRUE);
         $where = array('departments_id' => $departments_id);
         $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
         
         $data['month'] = date('F-Y', strtotime($yymm));
         $data['subview'] = $this->load->view('admin/attendance/attendance_report_2', $data, TRUE);
         $this->load->view('admin/_layout_main', $data);
      }
      
      /**
       *
       */
      public function get_report_3()
      {
         $staff_members = $this->staff_model->get('', ['active' => 1]);
         $data['staff_members'] = $staff_members;
         $staff_id = $this->input->post('staff_id', TRUE);
         $date = $this->input->post('date', TRUE);
         $month = date('n', strtotime($date));
         $year = date('Y', strtotime($date));
         $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
         if (!empty($staff_id)) {
            $this->db->select('*,CONCAT(firstname," ",lastname) as full_name');
            $this->db->where(array('staffid' => $staff_id));
            $data['employee'] = $this->db->get('tblstaff')->result_array();
         } else {
            $data['employee'] = (array)$staff_members;
         }
         
         
         $holidays = null;//$this->global_model->get_holidays(); //tbl working Days Holiday
         
         if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
         } else {
            $yymm = $year . '-' . $month;
         }
         
         $public_holiday = null;//$this->global_model->get_public_holidays($yymm);
         
         //tbl a_calendar Days Holiday
         if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
               $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
         }
         
         foreach ($data['employee'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {
               
               if ($i >= 1 && $i <= 9) {
                  $sdate = $yymm . '-' . '0' . $i;
               } else {
                  $sdate = $yymm . '-' . $i;
               }
               $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));
               
               $data['week_info'][date('W', strtotime($sdate))][$sdate] = $sdate;
               // get leave info
               
               if (!empty($holidays)) {
                  foreach ($holidays as $v_holiday) {
                     if ($v_holiday->day == $day_name) {
                        $flag = 'H';
                     }
                  }
               }
               if (!empty($p_hday)) {
                  foreach ($p_hday as $v_hday) {
                     if ($v_hday == $sdate) {
                        $flag = 'H';
                     }
                  }
               }
               if (!empty($flag)) {
                  $data['attendace_info'][$sl][date('W', strtotime($sdate))][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee['staffid'], $sdate, $flag);
               } else {
                  $data['attendace_info'][$sl][date('W', strtotime($sdate))][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee['staffid'], $sdate);
               }
               $key++;
               $flag = '';
            }
         }
         
         $data['title'] = _l('attendance_report');
         //$this->attendance_model->_table_name = "tbl_departments"; //table name
         //$this->attendance_model->_order_by = "departments_id";
         //$data['all_department'] = $this->attendance_model->get();
         //$data['departments_id'] = $this->input->post('departments_id', TRUE);
         $data['date'] = $this->input->post('date', TRUE);
         //$where = array('departments_id' => $departments_id);
         //$data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
         
         $data['month'] = date('F Y', strtotime($yymm));
         $this->load->view('admin/attendance/attendance_report_3', $data);
      }
      
      public function attendance_pdf($type, $departments_id, $date)
      {
         if ($type == 1) {
            $month = date('n', strtotime($date));
            $year = date('Y', strtotime($date));
            $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $data['employee_info'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
            
            $holidays = $this->global_model->get_holidays(); //tbl working Days Holiday
            
            if ($month >= 1 && $month <= 9) {
               $yymm = $year . '-' . '0' . $month;
            } else {
               $yymm = $year . '-' . $month;
            }
            
            $public_holiday = $this->global_model->get_public_holidays($yymm);
            
            //tbl a_calendar Days Holiday
            if (!empty($public_holiday)) {
               foreach ($public_holiday as $p_holiday) {
                  $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
               }
            }
            
            foreach ($data['employee_info'] as $sl => $v_employee) {
               $key = 1;
               $x = 0;
               for ($i = 1; $i <= $num; $i++) {
                  
                  if ($i >= 1 && $i <= 9) {
                     $sdate = $yymm . '-' . '0' . $i;
                  } else {
                     $sdate = $yymm . '-' . $i;
                  }
                  $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));
                  
                  $data['week_info'][date('W', strtotime($sdate))][$sdate] = $sdate;
                  
                  // get leave info
                  if (!empty($holidays)) {
                     foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                           $flag = 'H';
                        }
                     }
                  }
                  if (!empty($p_hday)) {
                     foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                           $flag = 'H';
                        }
                     }
                  }
                  if (!empty($flag)) {
                     $data['attendace_info'][date('W', strtotime($sdate))][$sdate][$v_employee->user_id] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                  } else {
                     $data['attendace_info'][date('W', strtotime($sdate))][$sdate][$v_employee->user_id] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                  }
                  $key++;
                  $flag = '';
               }
            }
            $data['title'] = _l('attendance_report');
            $where = array('departments_id' => $departments_id);
            $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
            
            $data['month'] = date('F-Y', strtotime($yymm));
            $subview = 'attendance_report_pdf';
         } elseif ($type == 2) {
            $month = date('n', strtotime($date));
            $year = date('Y', strtotime($date));
            $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            
            $data['employee'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
            $day = date('d', strtotime($date));
            for ($i = 1; $i <= $num; $i++) {
               $data['dateSl'][] = $i;
            }
            $holidays = $this->global_model->get_holidays(); //tbl working Days Holiday
            
            if ($month >= 1 && $month <= 9) {
               $yymm = $year . '-' . '0' . $month;
            } else {
               $yymm = $year . '-' . $month;
            }
            
            $public_holiday = $this->global_model->get_public_holidays($yymm);
            
            
            //tbl a_calendar Days Holiday
            if (!empty($public_holiday)) {
               foreach ($public_holiday as $p_holiday) {
                  $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
               }
            }
            foreach ($data['employee'] as $sl => $v_employee) {
               $key = 1;
               $x = 0;
               for ($i = 1; $i <= $num; $i++) {
                  
                  if ($i >= 1 && $i <= 9) {
                     
                     $sdate = $yymm . '-' . '0' . $i;
                  } else {
                     $sdate = $yymm . '-' . $i;
                  }
                  $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));
                  
                  
                  if (!empty($holidays)) {
                     foreach ($holidays as $v_holiday) {
                        
                        if ($v_holiday->day == $day_name) {
                           $flag = 'H';
                        }
                     }
                  }
                  if (!empty($p_hday)) {
                     foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                           $flag = 'H';
                        }
                     }
                  }
                  if (!empty($flag)) {
                     $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate, $flag);
                  } else {
                     $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate);
                  }
                  
                  $key++;
                  $flag = '';
               }
            }
            
            $data['title'] = _l('attendance_report');
            $where = array('departments_id' => $departments_id);
            $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
            $data['month'] = date('F-Y', strtotime($yymm));
            $subview = 'attendance_report_2_pdf';
         } else {
            
            $month = date('n', strtotime($date));
            $year = date('Y', strtotime($date));
            $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            
            $data['employee'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
            
            $holidays = $this->global_model->get_holidays(); //tbl working Days Holiday
            
            if ($month >= 1 && $month <= 9) {
               $yymm = $year . '-' . '0' . $month;
            } else {
               $yymm = $year . '-' . $month;
            }
            
            $public_holiday = $this->global_model->get_public_holidays($yymm);
            
            //tbl a_calendar Days Holiday
            if (!empty($public_holiday)) {
               foreach ($public_holiday as $p_holiday) {
                  $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
               }
            }
            
            foreach ($data['employee'] as $sl => $v_employee) {
               $key = 1;
               $x = 0;
               for ($i = 1; $i <= $num; $i++) {
                  
                  if ($i >= 1 && $i <= 9) {
                     $sdate = $yymm . '-' . '0' . $i;
                  } else {
                     $sdate = $yymm . '-' . $i;
                  }
                  $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));
                  
                  $data['week_info'][date('W', strtotime($sdate))][$sdate] = $sdate;
                  // get leave info
                  
                  if (!empty($holidays)) {
                     foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                           $flag = 'H';
                        }
                     }
                  }
                  if (!empty($p_hday)) {
                     foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                           $flag = 'H';
                        }
                     }
                  }
                  if (!empty($flag)) {
                     $data['attendace_info'][$sl][date('W', strtotime($sdate))][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                  } else {
                     $data['attendace_info'][$sl][date('W', strtotime($sdate))][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                  }
                  $key++;
                  $flag = '';
               }
            }
            
            $data['title'] = _l('attendance_report');
            $where = array('departments_id' => $departments_id);
            $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
            
            $data['month'] = date('F Y', strtotime($yymm));
            $subview = 'attendance_report_3_pdf';
         }
         $data['title'] = _l('attendance_report'); //Page title
         $this->load->helper('dompdf');
         $viewfile = $this->load->view('admin/attendance/' . $subview, $data, TRUE);
         pdf_create($viewfile, $data['month'] . '-' . $data['dept_name']->deptname);
      }
      
      function getattendancecalender()
      {
         $data['calendar_assets'] = true;
         $data['title'] = _l('calendar');
         $month = date('m');
         $allrecord = $this->attendance_model->getCourntMonthReport($month);
         $data['events'] = json_encode($allrecord);
         $this->load->view('admin/attendance/calender.php', $data);
      }
      
      function currentmonth()
      {
         $month = date('m');
         $allrecord = $this->attendance_model->getCourntMonthReport($month);
         $events = array();
         foreach ($allrecord as $one) {
            $events[] = array(
               'title' =>  sprintf("Present (%s)", $one['present']),
               'id'=>'classpresent',
               'url' => base_url('admin/attendance/mark_attendance'),
               'start' => $one['start'],
               'end' => $one['end']
            );
            $events[] = array(
               'title' =>  sprintf("Absent (%s)", $one['absent']),
               'id'  => 'classabsent',
               'url' => base_url('admin/attendance/mark_attendance'),
               'start' => $one['start'],
               'end' => $one['end']
            );
            $events[] = array(
               'title' =>  sprintf("Leave (%s)", $one['leaved']),
               'id'=>   'classleaved',
               'url' => base_url('admin/attendance/mark_attendance'),
               'start' => $one['start'],
               'end' => $one['end']
            );
         }
         echo json_encode($events);
      }
      
     function submit_earlychekout ()
      {
          if($this->input->post())
          {
              
              $meeting = $this->input->post('meeting_with');
              $purpose = $this->input->post('purpose');
              $location = $this->input->post('location');
              $scheduled_time = $this->input->post('scheduled_time');
              $period = $this->input->post('period');
              $total_count = count($meeting);
              $wp_id = $this->session->userdata('staff_user_id');
            
              date_default_timezone_set('Asia/Kolkata');

                for ($i = 0; $i < $total_count; $i++) {
                    
                    $inserdata['wp_id'] = $wp_id;
                    $inserdata['meeting_with'] = $meeting[$i];
                    $inserdata['purpose'] = $purpose[$i];
                    $inserdata['location'] = $location[$i];
                    $inserdata['scheduled_time'] = $scheduled_time[$i];
                    $inserdata['period'] = $period[$i];
                    $inserdata['status'] = '0';
                    $insertdata['applied_on'] = date('Y-m-d H:i:s');
                    
                    $this->db->insert('tblearlychekout', $inserdata);
                    
                }
                set_alert('success', "Early Clock-out Requested Successfully");
                redirect(admin_url('attendance/submit_earlychekout'));
          }
         $data['title'] = "Submit Custom Login / Logout Request";
         
         $this->load->view('admin/attendance/submit_earlychekout', $data); 
      }
      
      public function timechangereq()
      {
          $data['title'] = "View Custom Login / Logout Request";
          if(is_admin() || is_sub_admin())
          {
          $this->db->select('tblearlychekout.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS wpname');
          $this->db->join('tblstaff','tblearlychekout.wp_id=tblstaff.staffid');
		  $this->db->order_by("tblearlychekout.id", DESC);
          $data['timehistory'] = $this->db->get('tblearlychekout')->result();
          }
          elseif(herapermission())
          {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('tblearlychekout.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS wpname');
            $this->db->where("wp_id IN (".$arr.")",NULL, false);
			$this->db->or_where_in('wp_id', $useraid);
            $this->db->join('tblstaff','tblearlychekout.wp_id=tblstaff.staffid');
			$this->db->order_by("tblearlychekout.id", DESC);
            $data['timehistory'] = $this->db->get('tblearlychekout')->result();
          }
         
         $this->load->view('admin/attendance/timechangereq', $data); 
      }
      
      public function updatetimechange()
      {
          $rowid = $this->input->post('rowid');
          $id = $this->input->post('id');
          $status = $this->input->post('status');
          
          $array = array('status' => $status);
          $this->db->where('id', $id);
          $this->db->update('tblearlychekout', $array);
          
          $this->db->select('status');
          $this->db->where('id', $id);
          $reqres = $this->db->get('tblearlychekout')->row();
          if($reqres->status == 1)
          {
             echo $output = '<button style="cursor: none;" class="btn btn-success appstatus btn-icon _delete">Approved</button>';
                    $output .= ''; 
          }
          else
          {
             echo $output = '<button style="cursor: none;" class="btn btn-danger appstatus btn-icon _delete">Rejected</button>';
                    $output .= ''; 
          }
           
                    return $output;
      }
      
      public function mass_attanadance()
      {
          if($this->input->post())
          {
              
              //$table = "deviceLogs_".$month."_".$year;
              $table = "deviceLogs_"."2"."_"."2020";
			  
			  $selectDays = $this->input->post('days');
			  
              if($selectDays == "single_day")
              {
				  //exit("single Day");
				  $date = $this->input->post('singledate');
				  $timestamp = strtotime($date);
				  $day = date("d", $timestamp);
				  $month = date("m", $timestamp);
				  if(preg_match("~^0\d+$~", $month))
				  {
					  $month = str_replace("0", "", $month);
				  }
				  $year = date("Y", $timestamp );
				  
				  
				  $logintime = "09:30:00";
				  $logouttime = "18:30:00";
				  $LogDatei = $date . ' ' . $logintime;
				  $LogDateo = $date . ' ' . $logouttime;
				  
				  $emps = $this->input->post('employee');
				  
				  $teams = $this->input->post('team');
				  
				  $select = 'tblstaffdepartments.*, tblstaff.firstname, tblstaff.lastname, tblstaff.bio_id';
					$this->db->select($select);
					$this->db->from("tblstaffdepartments");
					$this->db->join('tblstaff', 'tblstaff.staffid = tblstaffdepartments.staffid');
					$this->db->where_in('tblstaffdepartments.team_id', $teams);
					$this->db->where('tblstaff.active ', 1);
					$query = $this->db->get();
					$result = $query->result();
					$entemp[] = "";
					  foreach($result as $teamm){
						  $entemp[] = $teamm->bio_id;
					  }
				  
					if(empty($emps)){
						 foreach($entemp as $emp){
							  $arri = array('LogDate' => $LogDatei, 'DeviceId' => '1', 'UserId' => $emp);
							  $this->db->insert($table, $arri);
							  
							  $arro = array('LogDate' => $LogDateo, 'DeviceId' => '1', 'UserId' => $emp);
							  $this->db->insert($table, $arro);
						  }
					}else{
					  foreach($emps as $emp){
						  $arri = array('LogDate' => $LogDatei, 'DeviceId' => '1', 'UserId' => $emp);
						  //$logindata = array_push($logindata,$arri);
						  //echo "<pre>";print_r($arri);
						  $this->db->insert($table, $arri);
						  
						  $arro = array('LogDate' => $LogDateo, 'DeviceId' => '1', 'UserId' => $emp);
						  //echo "<pre>";print_r($arro);
						  $this->db->insert($table, $arro);
					  }
					}
                  //$emp = $this->input->post('employee');
				  //echo "<pre>";print_r($logindata);exit;
                  //exit;
                  
                  set_alert('success', "Emplyoee Attendance Recorded Successfully");
                redirect(admin_url('attendance/mass_attanadance'));
              }
			  elseif($selectDays == "multiple_days"){
				  $employee = $this->input->post('employee');
				  $fromdate = $this->input->post('fromdate');
				  $todate = $this->input->post('todate');
				  $teams = $this->input->post('team');
				  
				  $all_dates = $this->getDatesFromRange($fromdate, $todate);
				  
				  $select = 'tblstaffdepartments.*, tblstaff.firstname, tblstaff.lastname, tblstaff.bio_id';
					$this->db->select($select);
					$this->db->from("tblstaffdepartments");
					$this->db->join('tblstaff', 'tblstaff.staffid = tblstaffdepartments.staffid');
					$this->db->where_in('tblstaffdepartments.team_id', $teams);
					$this->db->where('tblstaff.active ', 1);
					$query = $this->db->get();
					$result = $query->result();
					$emps[] = "";
				  foreach($result as $teamm){
					  $emps[] = $teamm->bio_id;
				  }
				  
				  
				  foreach($all_dates as $alldate){
					  $sunday = date("l",strtotime($alldate));
					  $LogDatei = $alldate." 09:30:00";
					  $LogDateo = $alldate." 18:30:00";
					  
					  $leaveDay = $this->attendance_model->getLeaveDay($alldate);
					  
					  
					  if(empty($leaveDay)){
						   if($sunday != "Sunday"){
							  if(empty($employee)){
								  foreach($emps as $emp){
									  $arri = array('LogDate' => $LogDatei, 'DeviceId' => '1', 'UserId' => $emp);
									  //echo "<pre>";print_r($arri);
									  $this->db->insert($table, $arri);
									  
									  $arro = array('LogDate' => $LogDateo, 'DeviceId' => '1', 'UserId' => $emp);
									  //echo "<pre>";print_r($arro);
									  $this->db->insert($table, $arro);
								  }
							  }else{
								  foreach($employee as $emp){
									  $arri = array('LogDate' => $LogDatei, 'DeviceId' => '1', 'UserId' => $emp);
									  //echo "<pre>";print_r($arri);
									  $this->db->insert($table, $arri);
									  
									  $arro = array('LogDate' => $LogDateo, 'DeviceId' => '1', 'UserId' => $emp);
									  //echo "<pre>";print_r($arro);
									  $this->db->insert($table, $arro);
								  }
							  } 
						  }
					  }
				  }
				  set_alert('success', "Emplyoee Attendance Recorded Successfully");
					redirect(admin_url('attendance/mass_attanadance'));
			  }
              else
              {
                  $teamid = $this->input->post('team');
                  $this->db->select('staffid');
                  $this->db->where('id', $teamid);
                  $teama = $this->db->get('tblteams')->row();
                  $staffid = $teama->staffid;
                  $rmid = $teama->staffid;
                  $this->db->select('bio_id');
                  $this->db->where('staffid',$staffid);
                  $stafbio = $this->db->get('tblstaff')->row();
                  
                  $rmbioid = $stafbio->bio_id;
                  if($rmid)
                  {
                         $rm_bioid = $rmbioid;
                         
                         $this->db->where('UserId', $rm_bioid);
                         $this->db->where('LogDate', $LogDatei);
                         $this->db->delete($table);
                        
                         $arri = array('LogDate' => $LogDatei, 'DeviceId' => '1', 'UserId' => $rm_bioid);
                         $login = $this->db->insert($table, $arri);
                         
                         $this->db->where('UserId', $rm_bioid);
                         $this->db->where('LogDate', $LogDateo);
                         $this->db->delete($table);
                  
                         $arro = array('LogDate' => $LogDateo, 'DeviceId' => '1', 'UserId' => $rm_bioid);
                         $logout = $this->db->insert($table, $arro);
                         
                     $this->db->select('staffid');
                     $this->db->where('reporting_manager', $rmid);
                     $staffbiolists = $this->db->get('tblstaff')->result();
                     foreach($staffbiolists as $staffid)
                     {
                         $reporting_manager = $staffid->staffid;
                         
                         $this->db->select('bio_id');
                         $this->db->where('staffid', $reporting_manager);
                         $rmbios = $this->db->get('tblstaff')->row();
                         
                         $rm_bioid = $rmbios->bio_id;
                         
                         $this->db->where('UserId', $rm_bioid);
                         $this->db->where('LogDate', $LogDatei);
                         $this->db->delete($table);
                        
                         $arri = array('LogDate' => $LogDatei, 'DeviceId' => '1', 'UserId' => $rm_bioid);
                         $login = $this->db->insert($table, $arri);
                         
                         $this->db->where('UserId', $rm_bioid);
                         $this->db->where('LogDate', $LogDateo);
                         $this->db->delete($table);
                  
                         $arro = array('LogDate' => $LogDateo, 'DeviceId' => '1', 'UserId' => $rm_bioid);
                         $logout = $this->db->insert($table, $arro);
                         
                         
                         
                            
                     }
                  
                      
                      set_alert('success', "Team Attendance Recorded Successfully");
                      redirect(admin_url('attendance/mass_attanadance'));
                            
                  }
                  
              }
              
          }
         $data['title'] = "Mass Attendance";
         $data['staff_members'] = $this->staff_model->get('', ['active' => 1, 'bio_id !=' => null]);
         $this->db->select('*');
         $this->db->where('active',1);
         $this->db->where('department_id !=',0);
         $data['teams'] = $this->db->get('tblteams')->result_array();
         $this->load->view('admin/attendance/mass_attanadance', $data);
      }
	  
		function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
			$array = array(); 
			$interval = new DateInterval('P1D'); 
			$realEnd = new DateTime($end); 
			$realEnd->add($interval); 
		  
			$period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
			
			foreach($period as $date) {                  
				$array[] = $date->format($format);
			}
			return $array;
		} 
      
      public function missedtimelogs()
      {
		  $date = "2020-02-11";
		  $timestamp = strtotime($date);
		  $day = date("d", $timestamp);
		  $month = date("m", $timestamp);
		  if(preg_match("~^0\d+$~", $month))
		  {
			  $month = str_replace("0", "", $month);
		  }
		 $year = date("Y", $timestamp );
         $data['title'] = "Missed Time Log";
         $data['staff_members'] = $this->staff_model->get('', ['active' => 1, 'bio_id !=' => null]);
		 
         $this->load->view('admin/attendance/missedtimelogs', $data);
      }
      
      public function customize_timelog()
      {
          if($this->input->post())
          {
             $maxlogintime = "09:45:00";
             $minlogouttime = "18:30:00";
           
             $halfdaylogintime = "14:00:00"; 
              
              
              $daterange = $this->input->post('date_range');
              
              switch($daterange)
              {
                  case "single":
                      
                      $date = $this->input->post('date');
                      $timestamp = strtotime($date);
                      $day = date("d", $timestamp);
                      $month = date("m", $timestamp);
                      if(preg_match("~^0\d+$~", $month))
                      {
                          $month = str_replace("0", "", $month);
                      }
                      $year = date("Y", $timestamp );
                      $table = "deviceLogs_"."2"."_"."2020";
                      
                      $logintime = "09:30:00";
                      $logouttime = "18:30:00";
                      $LogDatei = $date . ' ' . $logintime;
                      $LogDateo = $date . ' ' . $logouttime;
                      $emp = $this->input->post('employee');
                      
                      $shifts = $this->input->post('shift');
                        foreach($shifts as $shift)
                        {
                        	if($shift == "morning")
                        	{
                        	    foreach($emp as $empl)
                                  {
                                   
                                   $this->db->where('date(LogDate)',$date);
                                   $this->db->where('time(LogDate) <',$halfdaylogintime);
                                   $this->db->where('UserId', $empl);
                                   $this->db->delete($table);
                                   
                                   $arri = array('LogDate' => $LogDatei, 'DeviceId' => '1', 'UserId' => $empl);
                                   $login = $this->db->insert($table, $arri);
                                      
                                      
                                  }
                        
                        	}
                        	else
                        	{
                        	    foreach($emp as $empl)
                                  {
                                   $this->db->where('date(LogDate)',$date);
                                   $this->db->where('time(LogDate) >',$halfdaylogintime);
                                   $this->db->where('UserId', $empl);
                                   $this->db->delete($table);
                                   
                                   $arri = array('LogDate' => $LogDateo, 'DeviceId' => '1', 'UserId' => $empl);
                                   $login = $this->db->insert($table, $arri);
                                      
                                  }
                        	
                        	}
                        	
                        }
                      break;
                      
                  case "multiple":
                      
                      
                      $startdate = $this->input->post('startdate');
                      $enddate = $this->input->post('enddate');
                      $timestamp = strtotime($startdate);
                      $day = date("d", $timestamp);
                      $month = date("m", $timestamp);
                      if(preg_match("~^0\d+$~", $month))
                      {
                          $month = str_replace("0", "", $month);
                      }
                      $year = date("Y", $timestamp );
                      $table = "deviceLogs_".$month."_".$year;
                      
                      
                      
                      $emp = $this->input->post('employee');
                      $enddate = $enddate->add(new DateInterval("P1D"));
                      
                      $period = new DatePeriod(
                                 new DateTime($startdate),
                                 new DateInterval('P1D'),
                                 new DateTime($enddate)
                            );
                      foreach ($period as $key => $value) {
                            $date = $value->format('Y-m-d');   
                        
                      $logintime = "09:30:00";
                      $logouttime = "18:30:00";
                      $LogDatei = $date . ' ' . $logintime;
                      $LogDateo = $date . ' ' . $logouttime;
                      
                      $shifts = $this->input->post('shift');
                        foreach($shifts as $shift)
                        {
                        	if($shift == "morning")
                        	{
                        	    foreach($emp as $empl)
                                  {
                                   
                                   $this->db->where('date(LogDate)',$date);
                                   $this->db->where('time(LogDate) <',$halfdaylogintime);
                                   $this->db->where('UserId', $empl);
                                   $this->db->delete($table);
                                   $arri = array('LogDate' => $LogDatei, 'DeviceId' => '1', 'UserId' => $empl);
                                   $login = $this->db->insert($table, $arri);
                                      
                                  }
                       
                        	}
                        	else
                        	{
                        	    foreach($emp as $empl)
                                  {
                                   $this->db->where('date(LogDate)',$date);
                                   $this->db->where('time(LogDate) >',$halfdaylogintime);
                                   $this->db->where('UserId', $empl);
                                   $this->db->delete($table);
                                   
                                   $arri = array('LogDate' => $LogDateo, 'DeviceId' => '1', 'UserId' => $empl);
                                   $login = $this->db->insert($table, $arri);
                                      
                                  }
                        	
                        	}
                        	
                        }
                        
                          $currentday =  date('D', strtotime($date));
                            if( $currentday == "Sun")
                            {
                                   $this->db->where('date(LogDate)',$date);
                                   $this->db->delete($table);
                            }
                            
                      }
                        
                      break;
              }
              
              
            set_alert('success', "Attendance Recorded Submited  Successfully");
            redirect(admin_url('attendance/customize_timelog'));  
              
              
          }
		 
         $data['title'] = "Cutomize TimeLog";
         $data['staff_members'] = $this->staff_model->get('', ['active' => 1, 'bio_id !=' => null]);
		 //echo count($data['staff_members']);exit;
         $this->db->select('*');
         $this->db->where('active',1);
         $data['teams'] = $this->db->get('tblteams')->result_array();
         $this->load->view('admin/attendance/customize_timelog', $data);
      }
      
      
      public function updatemissedtime()
      {
          $date = $this->input->post('date');
          $timestamp = strtotime($date);
          $day = date("d", $timestamp);
          $month = date("m", $timestamp);
          if(preg_match("~^0\d+$~", $month))
          {
              $month = str_replace("0", "", $month);
          }
          $year = date("Y", $timestamp );
          
          
          $table = "deviceLogs_"."2"."_".$year;
          //$table = "deviceLogs_".$month."_".$year;
          $bioid = $this->input->post('bioid');
          $logdate = $this->input->post('logdate');
         
         $arr = array('LogDate' => $logdate, 'DeviceId' => '1', 'UserId' => $bioid);
         $login = $this->db->insert($table, $arr);
          
      }
	  public function import_attendance(){
		  if ($_FILES) {
            $file = $_FILES['file']['tmp_name'];
			$handle = fopen($file, "r");
			$c = 0;//
			
			while ($row = fgetcsv($handle, 1000, ",")) {
				$rows[] = $row;
			}
			array_shift($rows);
			foreach($rows as $rowdata){
				if($rowdata[10] != "" ){
					$date = date("Y-m-d", strtotime($rowdata[0]))." ".$rowdata[10];
					$attendance = $this->attendance_model->getAttendanceData($date,$rowdata[1]);
					$indata = array(
						"DeviceId" => "1",
						"UserId" => $rowdata[1],
						"LogDate" => $date
					);
					
					if(empty($attendance)){
						//echo "insert<br>";
						$this->db->insert("deviceLogs_2_2020", $indata);
					}else{
						//echo "Update<br>";
						$this->db->where('UserId', $rowdata[1]);
						$this->db->where('LogDate', $date);
						$this->db->update($data);
					}
				}
				if($rowdata[11] != "" ){
					$date1 = date("Y-m-d", strtotime($rowdata[0]))." ".$rowdata[11];
					$attendance = $this->attendance_model->getAttendanceData($date1,$rowdata[1]);
					$outdata = array(
						"DeviceId" => "1",
						"UserId" => $rowdata[1],
						"LogDate" => $date1
					);
					
					if(empty($attendance)){
						//echo "insert<br>";
						$this->db->insert("deviceLogs_2_2020", $outdata);
						//echo $this->db->last_query()."<br>";
					}else{
						//echo "Update<br>";
						$this->db->where('UserId', $rowdata[1]);
						$this->db->where('LogDate', $date1);
						$this->db->update("deviceLogs_2_2020", $outdata);
						
					}
					//$this->db->insert("deviceLogs_2_2020", $outdata);
				}
			}//exit;
			echo "1";
			
		}else{
			set_alert('warning', _l('import_upload_failed'));
		}
	  }
	  
	/* public function view_attendance(){
		echo "Hello";
	} */
	  
   }