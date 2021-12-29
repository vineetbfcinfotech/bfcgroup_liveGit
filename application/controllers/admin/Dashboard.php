<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }
 
    /* This is admin dashboard view */
    public function index()
    {

       // print_r("test");exit;
        
        close_setup_menu();
        $this->load->model('departments_model');
        $this->load->model('todo_model');
        $data['departments'] = $this->departments_model->get();

        $data['todos'] = $this->todo_model->get_todo_items(0);
        // Only show last 5 finished todo items
        $this->todo_model->setTodosLimit(5);
        $data['todos_finished']            = $this->todo_model->get_todo_items(1);
        $data['upcoming_events_next_week'] = $this->dashboard_model->get_upcoming_events_next_week();
        $data['upcoming_events']           = $this->dashboard_model->get_upcoming_events();
        $data['title']                     = _l('dashboard_string');
        $this->load->model('currencies_model');
        $data['currencies']    = $this->currencies_model->get();
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['activity_log']  = $this->misc_model->get_activity_log();
        // Tickets charts
        $tickets_awaiting_reply_by_status     = $this->dashboard_model->tickets_awaiting_reply_by_status();
        $tickets_awaiting_reply_by_department = $this->dashboard_model->tickets_awaiting_reply_by_department();

        $data['tickets_reply_by_status']              = json_encode($tickets_awaiting_reply_by_status);
        $data['tickets_awaiting_reply_by_department'] = json_encode($tickets_awaiting_reply_by_department);

        $data['tickets_reply_by_status_no_json']              = $tickets_awaiting_reply_by_status;
        $data['tickets_awaiting_reply_by_department_no_json'] = $tickets_awaiting_reply_by_department;

        /*$data['projects_status_stats'] = json_encode($this->dashboard_model->projects_status_stats());*/
        $data['leads_status_stats']    = json_encode($this->dashboard_model->leads_status_stats());
        $data['google_ids_calendars']  = $this->misc_model->get_google_calendar_ids();
        $data['bodyclass']             = 'dashboard invoices-total-manual';
        $this->load->model('announcements_model');
        $data['staff_announcements']             = $this->announcements_model->get();
        $data['total_undismissed_announcements'] = $this->announcements_model->get_total_undismissed_announcements();

        $data['goals'] = [];
        if (is_staff_member()) {
            $this->load->model('goals_model');
            $data['goals'] = $this->goals_model->get_staff_goals(get_staff_user_id());
        }

        /*$this->load->model('projects_model');
        $data['projects_activity'] = $this->projects_model->get_activity('', do_action('projects_activity_dashboard_limit', 20));*/
        // To load js files
        $data['calendar_assets'] = true;
        $this->load->model('utilities_model');
        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();

        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();

        $wps_currency = 'undefined';
        if (is_using_multiple_currencies()) {
            $wps_currency = $data['base_currency']->id;
        }
        $data['weekly_payment_stats'] = json_encode($this->dashboard_model->get_weekly_payments_statistics($wps_currency));

        $data['dashboard'] = true;

        $data['user_dashboard_visibility'] = get_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility');

        if (!$data['user_dashboard_visibility']) {
            $data['user_dashboard_visibility'] = [];
        } else {
            $data['user_dashboard_visibility'] = unserialize($data['user_dashboard_visibility']);
        }
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);
		
		$data['announcements'] = $this->announcements_model->get_announcement();
		
		$day = date("d");
		$month = date("m");
		$currentdate = date("Y-m-d h:i:s");
		
		$this->db->select("*");
		$this->db->from("tblstaff");
		$this->db->where("day(birth_date)", $day);
		$this->db->where("month(birth_date)", $month);
		$this->db->where("active", 1);
		$sql = $this->db->get();
		$result1 = $sql->result();
		
		$resultcheck = array();

		foreach($result1 as $res){
			
			$this->db->select("bitrh_date");
			$this->db->from("tblnotifications");
			$this->db->where("bitrh_date", $res->birth_date);
			$this->db->where("fromuserid", $res->staffid); 
			$query = $this->db->get();
			$resultcheck = $query->result();
			
			if(empty($resultcheck)){
				
				$dataNot = array("isread"=> 0, "isread_inline"=> 0, "date"=>$currentdate, "description"=>"birthday_notification", "fromuserid"=> $res->staffid, "bitrh_date"=>$res->birth_date, "from_fullname"=>$res->firstname." ".$res->lastname);
					//print_r($dataNot);exit; 
				$this->db->insert('tblnotifications', $dataNot);
			}
		}
		/* echo $this->db->last_query();
		print_r($result);exit;  */
		
        $data = do_action('before_dashboard_render', $data);
    
        $this->load->view('admin/dashboard/dashboard', $data);
    }

    /* Chart weekly payments statistics on home page / ajax */
    public function weekly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_weekly_payments_statistics($currency));
            die();
        }
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
            $adata['user_id'] = $this->session->userdata('staff_user_id');
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
//        $already_clocking = $this->dashboard_model->check_by(array('user_id' => $adata['user_id'], 'clocking_status' => 1), 'tbl_attendance');
        if ($clocktime == 1) {
            $adata['date_in'] = $date;
        } else {
            $adata['date_out'] = $date;
        }

        if (!empty($adata['date_in'])) {
            // check existing date is here or not
            $check_date = $this->dashboard_model->check_by(array('user_id' => $adata['user_id'], 'date_in' => $adata['date_in']), 'tbl_attendance');
        }
        if (!empty($check_date)) { // if exis do not save date and return id
            $this->dashboard_model->_table_name = "tbl_attendance"; // table name
            $this->dashboard_model->_primary_key = "attendance_id"; // $id

            if ($check_date->attendance_status != '1') {
                $udata['attendance_status'] = 1;
                $this->dashboard_model->save($udata, $check_date->attendance_id);
            }
            if ($check_date->clocking_status == 0) {
                $udata['date_out'] = $date;
                $udata['clocking_status'] = 1;
                $this->dashboard_model->save($udata, $check_date->attendance_id);
            }
            $data['attendance_id'] = $check_date->attendance_id;
        } else { // else save data into tbl attendance
            // get attendance id by clock id into tbl clock
            // if attendance id exist that means he/she clock in
            // return the id
            // and update the day out time

            $check_existing_data = $this->dashboard_model->check_by(array('clock_id' => $id), 'tbl_clock');
            $this->dashboard_model->_table_name = "tbl_attendance"; // table name
            $this->dashboard_model->_primary_key = "attendance_id"; // $id
            if (!empty($check_existing_data)) {
                $adata['clocking_status'] = 0;
                $this->dashboard_model->save($adata, $check_existing_data->attendance_id);
            } else {
                $adata['attendance_status'] = 1;
                $adata['clocking_status'] = 1;
                //save data into attendance table
                $data['attendance_id'] = $this->dashboard_model->save($adata);
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
        $this->dashboard_model->_table_name = "tbl_clock"; // table name
        $this->dashboard_model->_primary_key = "clock_id"; // $id
        if (!empty($id)) {
            $data['clocking_status'] = 0;
            $this->dashboard_model->save($data, $id);
        } else {
            $data['clocking_status'] = 1;
            $id = $this->dashboard_model->save($data);
            if (!empty($check_date)) {
                if ($check_date->clocking_status == 1) {
                    $data['clockout_time'] = $time;
                    $data['clocking_status'] = 0;
                    $this->dashboard_model->save($data, $id);
                }
            }
        }
        if (empty($redirect)) {
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            return true;
        }
    }
	
	public function set_attendance(){
		$clock_date = $this->input->post('clock_date');
		$clock_time = $this->input->post('clock_time');
		$user_id = $this->session->userdata('staff_user_id');
		$check_clock = $this->dashboard_model->check_clock($user_id, $clock_date);
		$attendance_id = $check_clock->attendance_id;
		
		//$current_date_time = date("Y-m-d h:i:s");
		$current_date_time = $clock_date." ".$clock_time;
		$dataAttendance = array("DeviceId"=> 1, "staffId"=> $user_id, "LogDate"=>$current_date_time );
		//print_r($dataAttendance);exit;
		if(empty($check_clock)){
			$data = array("date_in"=> $clock_date, "date_in_time"=> $clock_time, "leave_application_id"=>0, "attendance_status"=>1, "clocking_status"=>1, "user_id"=> $user_id);
			//echo "save";exit;
			$insert = $this->dashboard_model->saveclock($data);
			$this->db->insert('deviceLogs_2_2020', $dataAttendance);
		}else{
			$data = array("date_out"=> $clock_date, "date_out_time"=> $clock_time, "leave_application_id"=>0, "attendance_status"=>1, "clocking_status"=>0);
			//echo "update";exit;
			$update = $this->dashboard_model->updateclock($data, $attendance_id);
			$this->db->insert('deviceLogs_2_2020', $dataAttendance);
		}
		
		if (empty($redirect)) {
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            return true;
        }
	}
	
	public function addposs(){
		$user_id = $this->session->userdata('staff_user_id');
		$date =date("Y-m-d H:i:s");
		$time = date("H:i:s",strtotime($date));
		$current_date = date("Y-m-d");
		
		$this->db->select("*");
		$this->db->from("tbl_poss");
		$this->db->where("user_id", $user_id);
		$this->db->where("date", $current_date);
		$result = $this->db->get()->row();
		if(empty($result)):
			$data = array("user_id"=> $user_id,"date"=> $current_date,"start_time"=> $time, "Reason"=>$this->input->post('poss_reason') );
			$this->db->insert('tbl_poss', $data);
		else:
			$data = array("stop_time"=> $time);
			$this->db->where('user_id', $user_id);
			$this->db->where('date', $current_date);
			$this->db->update('tbl_poss', $data);
		endif;
		//print_r($data);
		
		if (empty($redirect)) :
            redirect($_SERVER["HTTP_REFERER"]);
        else :
            return true;
        endif;
	}
}
