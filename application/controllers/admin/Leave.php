<?php
   
   defined('BASEPATH') or exit('No direct script access allowed');
   
   class Leave extends Admin_controller
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
         //  if (!has_permission('reports', '', 'view')) {
         //    access_denied('reports');
         //    }
         $this->ci = &get_instance();
         $this->load->model('reports_model');
         $this->load->model('leave_management');
		 $this->load->model('leads_model');
      }
      
      public function index()
      {
         $loginid = $this->session->userdata('staff_user_id');
         $data['title'] = _l('Leave Management');
		 
         $arr=herapermission();
		 
         $data['Approvalleave'] = $this->leave_management->getApprovalleave('tblleaveapplication');
         $data['Rejectedleave'] = $this->leave_management->getRejectedleave('tblleaveapplication');
         $data['ourholidays'] = $this->leave_management->getholidays('tblholidays');
         
		 
          $this->db->select('gender,marital_status,confirmation_date, notice_date');
          $this->db->where('staffid', $loginid);
          $query = $this->db->get('tblstaff')->row();
          $confirmation_date = $query->confirmation_date;
		  $notice_date = $query->notice_date;
          $gender = $query->gender;
          $marital_status = $query->marital_status;
          if($gender == 'Male')
          {
              
              $this->db->where('leave_gender !=', 'Female');
              
          }
          else
          {
              
              $this->db->where('leave_gender !=', 'Male');
          }
          if($marital_status == 'Married')
          {
              
              $this->db->where('leave_marital !=', 'Unmarried');
          }
          else
          {
              
              $this->db->where('leave_marital !=', 'Married');
          }
          
		  $currentDate = date("Y-m-d");
		  //echo $currentDate." --- ".$notice_date;
          
         $category = $this->db->get('tblleavecategory')->result();
         
         if($confirmation_date == "0000-00-00")
         {
             $this->db->where('leave_category_id', '11');
             $category = $this->db->get('tblleavecategory')->result();
             
         }elseif($notice_date != Null && $currentDate >= $notice_date){
			 $this->db->where('leave_category_id', '11');
             $category = $this->db->get('tblleavecategory')->result();
		 }
		 
		 $data['currentDate'] = $currentDate;
		 $data['notice_date'] = $notice_date;
		 
         $stoff = $this->leave_management->getData('tblstaff', array('staffid' => $loginid), 'null');
         /* Count The Time Duration Between Two Date Example => 2 Year, $months, 5 Days */
         $date1 = $stoff[0]->confirmation_date;
         
         $date2 = date('Y-m-d');
         $diff = abs(strtotime($date2) - strtotime($date1));
         
         $years = floor($diff / (365 * 60 * 60 * 24));
         $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
         $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
         /* Count The Time Duration Between Two Date Example => 2 Year, $months, 5 Days */
        
         
         $cur_month = date('m');
            $cur_year = date('Y');
            if($cur_month <= '3')
            {
                
                $fi_end_year = $cur_year;
                $fi_start_year = $cur_year-1;
            }
            else
            {
               $fi_end_year = $cur_year+1;
               $fi_start_year = $cur_year; 
            }
            
            $curdate=strtotime($date1);
            $mydate=strtotime('01-04-'.$fi_start_year);
            
           $date_end_cus = '31-03-'.$fi_end_year;
         $diff_custom = abs(strtotime($date_end_cus) - strtotime($date1));
         
         $years_custom = floor($diff_custom / (365 * 60 * 60 * 24));
         $months_custom = floor(($diff_custom - $years_custom * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
         $days_custom = floor(($diff_custom - $years_custom * 365 * 60 * 60 * 24 - $months_custom * 30 * 60 * 60 * 24) / (60 * 60 * 24));
         //echo $mydate;exit;
         if($curdate <= $mydate)
         {
            $result = array();
         foreach ($category as $value) {
            $count = 0;
            
            if ( $value->leave_rate == '0' ) {
               $lcatid = $value->leave_category_id;
               $catLeaveCount = $this->leave_management->getData('tblleaveapplication', array('user_id' => $loginid, 'leave_category_id' => $lcatid), 'AND');
               if ( count($catLeaveCount) == 0 ) {
                  $count = $value->leave_quota;
               } else {
                  $count = "0";
               }
            } else if ( $value->leave_rate == '1' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * $value->leave_quota;
               }
               $count = $count + 1 * $value->leave_quota;
               // $count=$count+$years*$value->leave_quota;
               //$count=$count+$months*$value->leave_quota;
            } else if ( $value->leave_rate == '2' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * 12 * $value->leave_quota;
               }
               $count = $count + $months * $value->leave_quota;
            }
            
            array_push($result, array('leave_category_id' => $value->leave_category_id, 'leave_category' => $value->leave_category, 'leave_quota' => $value->leave_quota, 'leave_rate' => $value->leave_rate, 'leave_carry' => $value->leave_carry, 'totalleave' => $count));
            // print_r($value->leave_category.'--'.$count.'<br/>'); 
         } 
         }
         else
         {
             $result = array();
         foreach ($category as $value) {
            $count = 0;
            
            if ( $value->leave_rate == '0' ) {
               $lcatid = $value->leave_category_id;
               $catLeaveCount = $this->leave_management->getData('tblleaveapplication', array('user_id' => $loginid, 'leave_category_id' => $lcatid), 'AND');
               if ( count($catLeaveCount) == 0 ) {
                  $count = $value->leave_quota;
               } else {
                  $count = "0";
               }
            } else if ( $value->leave_rate == '1' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * ($value->leave_quota*$months_custom)/12;
               }
               $count = $count + 1 * (($value->leave_quota*$months_custom)/12);
               // $count=$count+$years*$value->leave_quota;
               //$count=$count+$months*$value->leave_quota;
            } else if ( $value->leave_rate == '2' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * 12 * (($value->leave_quota*$months_custom)/12);
               }
               $count = $count + $months * (($value->leave_quota*$months_custom)/12);
            }
            
            array_push($result, array('leave_category_id' => $value->leave_category_id, 'leave_category' => $value->leave_category, 'leave_quota' => $value->leave_quota, 'leave_rate' => $value->leave_rate, 'leave_carry' => $value->leave_carry, 'totalleave' => $count));
            // print_r($value->leave_category.'--'.$count.'<br/>'); 
         } 
         }
         //echo "<pre>";
         //print_r($result); die();
		 //echo $this->db->last_query();exit;
         $data['leaveCategory'] = $result;
         $month = date('m');
            $year = date('Y');
            if($month <= '3')
            {
                
                $fi_end_year = $year;
                $fi_start_year = $year-1;
            }
            else
            {
               $fi_end_year = $year+1;
               $fi_start_year = $year; 
            }
			$loginid=$this->session->userdata('staff_user_id');	
			$data['total_special_leave'] = $this->leave_management->dataEmpToatalSpLeave($loginid);
			$data['applied_special_leave'] = $this->leave_management->appliedSpecialLeave($loginid);
            
            $data['start'] = $fi_start_year.'-04-01';
            $data['end'] = $fi_end_year.'-03-31';
			//echo $this->db->last_query();exit;
         $this->load->view('admin/leave_management/leave_management', $data);
      }
      
      public function saveleave()
      {
         /* print_r($this->input->post());
          exit;*/
         //print_r($_POST);exit;
			$userid = $this->session->userdata('staff_user_id');
			 $categoryid = $_POST['leave_category_id'];
			 $filename = $_FILES['file']['name'];
			 $leave_type = $_POST['leave_type'];
			 $reason = $_POST['reason'];
			 $half_shift = $_POST['half_shift'];
			 $leave_start_date = "";
			 $leave_end_date = '';
			 $sandwitchleave = $_POST['sandwitchleave'];
			 $sandwitch = $_POST['sandwitch'];
			 	
			$staff_id = $this->session->userdata('staff_user_id');
			$bio_id = $this->session->userdata('staff_bio_id');
			// echo "sandwitchleave-".$sandwitchleave.",sandwitch-".$sandwitch;
		 	// 	exit;	  
		 if($_POST["quota_exceed"] == 0 || $_POST["application_id"] != ""){
			 //print_r($_POST);exit;
			 $totalday = $this->db->query('SELECT  count(UserId) as cnt FROM deviceLogs_2_2020 WHERE year(LogDate) = 2020 AND (UserId = "'.$bio_id.'" OR staffId = "'.$staff_id.'") GROUP BY `UserId`, date(LogDate) HAVING cnt = 1');
			  $missedLogs = $totalday->result();
			  $totalMissedLog = 0;
				foreach($missedLogs as $missedLog){
					$totalMissedLog = $totalMissedLog+$missedLog->cnt;
				}
				if($totalMissedLog > 0){
					  $totalMissedDays = $totalMissedLog/2;
				  }else{
					  $totalMissedDays = 0;
				  }
			 $totalAppliedLeave = $this->leave_management->appliedTotalLeave($userid, $categoryid);
			 //print_r($totalAppliedLeave);exit;
			 $appliedLeave = $totalAppliedLeave->duration+$applayDay+$totalMissedDays;
			 
			 
			 switch ($leave_type) {
				case 'single_day':
				   $leave_start_date = $_POST['single_day_start_date'];
				   $leave_end_date = $_POST['single_day_start_date'];
				   break;
				   
				case 'half_day':
				   $leave_start_date = $_POST['single_day_start_date'];
				   $leave_end_date = $_POST['single_day_start_date'];
				   break;
				
				case 'multiple_days':
				   $leave_start_date = $_POST['multiple_days_start_date'];
				   $leave_end_date = $_POST['multiple_days_end_date'];
				   break;
			 }
			
			 
			 if ( $leave_type == "multiple_days" ) {
			 	if($sandwitch==1){

			 		$this->db->select('name');
			        $this->db->where('date(quota) >=', $leave_start_date);
			        $this->db->where('date(quota) <=', $leave_end_date);
			        $holid = $this->db->get('tblholidays')->row();
			        if(count($holid) > 0)
			        {
			           $holiresponce = count($holid);
			        }
			        else
			        {
			           $holiresponce = "0";
			        }

			        $datetime1 = new DateTime($leave_start_date);
					$datetime2 = new DateTime($leave_end_date);
					
					$interval = $datetime1->diff($datetime2);
					$tempcount = $interval->format('%a');
					$tempadd = "1";
					// echo $diff = $tempcount + $tempadd . " Days";
					$leaveDays = $tempcount + $tempadd;

					$start = new DateTime($leave_start_date);
					$end = new DateTime($leave_end_date);
					$days = $start->diff($end, true)->days;
					$sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);
					$totalHoliday =  $sundays+$holiresponce;
					$diff = $leaveDays -$totalHoliday;
			 		//echo $applayDay."aa";
			 	}else{
			 		//echo "string-2";
			 		$datetime1 = new DateTime($leave_start_date);
					$datetime2 = new DateTime($leave_end_date);
					
					$interval = $datetime1->diff($datetime2);
					$tempcount = $interval->format('%a');
					$tempadd = "1";
					$diff = $tempcount + $tempadd . " Days";
					$applayDay = $tempcount + $tempadd;
					$diffenleav = $tempcount+$tempadd;
			 	}

				
				 // echo $applayDay;
				 // exit;
			 } elseif ( $leave_type == "single_day" ) {
				$diff = "1 Day";
				$diffenleav = "1";
			 }
			 
			 elseif ( $leave_type == "half_day" ) {
				$diff = "0.5 Day";
				$diffenleav = "0.5";
			 }
			 
			 $upd['days'] = $diff;
			 
			 if($categoryid == 0)
			 {
				 
				$loginid=$this->session->userdata('staff_user_id');	
				$this->db->select('sp_leave');
				$this->db->where('staffid', $loginid);
				$spstaff = $this->db->get('tblstaff')->row();
				$lquo = $spstaff->sp_leave;
			 }
			 else
			 {
			   $this->db->where('leave_category_id', $categoryid);
			   $category = $this->db->get('tblleavecategory')->result(); 
			   $lquo = $category[0]->leave_quota;
			 }
			 
			 
			 
			
			 if($categoryid == 0){
				 $totalSpecialLeave = $this->leave_management->dataEmpToatalSpLeave($userid);
				 $totalLeaveQuota = $totalSpecialLeave->quota;
			 }else{
				 $leaveQuota = $this->leave_management->getLeaveQuota($categoryid);
				 $totalLeaveQuota = $leaveQuota->leave_quota;
			 }
			 
			 //echo $totalLeaveQuota.">".$applayDay;exit;
			 //if ( $diffenleav > $lquo ) {
			 if ( $totalLeaveQuota < $applayDay ) {
				  // echo $applayDay."qq1"; exit;
				$this->session->set_flashdata('error', 'Leave Quota Exeeded Please Apply Balance Leave Only ..');
				redirect(base_url('admin/leave/index'));
			 } else {
				
				//  echo $diff;
				 // echo $applayDay."qq2"; exit;
				
				$date = date('Y-m-d H:i:s');
				if ( $filename ) {
				   $this->load->library('upload');
				   $exten = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				   $filename = "attachement" . rand(15, 35) . date("G", time()) . "." . $exten;
				   $_FILES['file']['name'] = $filename;
				   $config['upload_path'] = './assets/attachment/';
				   $config['allowed_types'] = 'jpeg|jpg|png|gif|exif|tiff|bmp|ppm|pdf|docx|dotx|docb|pptx|pptm|ppsx|csv|html|htm|xhtml|xlsx|xls|ods|3gp|mp4';
				   $config['max_size'] = '5000';
				   $this->upload->initialize($config);
				   if ( !$this->upload->do_upload('file') ) {
					  $error = array('error' => $this->upload->display_errors());
					  echo "<br/>";
					  echo $this->upload->display_errors();
					  echo "<br/> Attachment Upload error.<br/>";
				   } else {
					  $fdata = $this->upload->data();
					  $file = $fdata['file_name'];
				   }
				   
				}
				$app_id_update = $_POST['application_id'];
				
				$resultData = array('sandwitch_leave' => $sandwitchleave, 'sandwitch_avail' => $sandwitch, 'user_id' => $userid, 'leave_category_id' => $categoryid, 'reason' => $reason, 'half_shift' => $half_shift,  'leave_type' => $leave_type, 'leave_start_date' => $leave_start_date, 'leave_end_date' => $leave_end_date, 'duration' => $diff, 'application_status' => '1', 'application_date' => $date, 'attachment' => $filename, 'comments' => '');
				
				
				if($_POST['application_id'] == ""){
					$this->db->insert('tblleaveapplication', $resultData);
					$lastid = $this->db->insert_id();
					$msg = "New Leave Application Added Successfully..";
				}elseif($_POST['application_id'] != ""){
					$this->db->where('leave_application_id', $app_id_update);
					$this->db->update('tblleaveapplication', $resultData);
					$lastid = $_POST['application_id'];
					$msg = "Leave Application Updated Successfully..";
				}
				
				$this->leave_management->leavesubmitreport($lastid);
				//$subject = "Leave Application Submitted";
				//$message = "Staff submitted a Leave Application ";
				//$this->emails_model->send_simple_email('aadi.compaddicts@gmail.com', $subject, $message);
				
				$this->session->set_flashdata('success', $msg);
				redirect(base_url('admin/leave/index'));
				
			 }
		}elseif($_POST['quota_exceed'] == 1 ){
			$totalday = $this->db->query('SELECT  count(UserId) as cnt FROM deviceLogs_2_2020 WHERE year(LogDate) = 2020 AND (UserId = "'.$bio_id.'" OR staffId = "'.$staff_id.'") GROUP BY `UserId`, date(LogDate) HAVING cnt = 1');
			  $missedLogs = $totalday->result();
			  $totalMissedLog = 0;
				foreach($missedLogs as $missedLog){
					$totalMissedLog = $totalMissedLog+$missedLog->cnt;
				}
				if($totalMissedLog > 0){
					  $totalMissedDays = $totalMissedLog/2;
				  }else{
					  $totalMissedDays = 0;
				  }
				  
			$totalAppliedLeave = $this->leave_management->appliedTotalLeave($userid, $categoryid);
			 //print_r($totalAppliedLeave);exit;
			 $appliedLeave = $totalAppliedLeave->duration;
			 
			 if($categoryid == 0){
				 $totalSpecialLeave = $this->leave_management->dataEmpToatalSpLeave($userid);
				 $totalLeaveQuota = $totalSpecialLeave->quota;
			 }else{
				 $leaveQuota = $this->leave_management->getLeaveQuota($categoryid);
				 $totalLeaveQuota = $leaveQuota->leave_quota;
			 }
			 $leftLeaveQuota = $totalLeaveQuota-$appliedLeave+$totalMissedDays;
			 //echo $leftLeaveQuota;exit;
			 
			$leave_start_date = $_POST['multiple_days_start_date'];
			$leave_end_date = $_POST['multiple_days_end_date'];
			
			$mainEndDate = $leftLeaveQuota-1;
			
			$after_leave_start_date = date('Y-m-d', strtotime($leave_start_date. ' + '.$leftLeaveQuota.' days'));
			$after_leave_end_date = date('Y-m-d', strtotime($leave_end_date. ' - '.$leftLeaveQuota.' days'));
			$end_date_for_quota = date('Y-m-d', strtotime($leave_start_date. ' + '. $mainEndDate .' days'));
			//echo $after_leave_start_date."<br>".$leftLeaveQuota;exit;

				$datetime1 = new DateTime($leave_start_date);
				$datetime2 = new DateTime($leave_end_date);
				
				$interval = $datetime1->diff($datetime2);
				$tempcount = $interval->format('%a');
				$tempadd = "1";
				$diff = $tempcount + $tempadd . " Days";
				$applayDay = $tempcount + $tempadd;
				//$diffenleav = $tempcount+$tempadd;
				//echo $applayDay;exit;
				$lwpQuota = $applayDay-$leftLeaveQuota;
				//echo $lwpQuota;exit;
				
				
				$date = date('Y-m-d H:i:s');
				if ( $filename ) {
				   $this->load->library('upload');
				   $exten = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				   $filename = "attachement" . rand(15, 35) . date("G", time()) . "." . $exten;
				   $_FILES['file']['name'] = $filename;
				   $config['upload_path'] = './assets/attachment/';
				   $config['allowed_types'] = 'jpeg|jpg|png|gif|exif|tiff|bmp|ppm|pdf|docx|dotx|docb|pptx|pptm|ppsx|csv|html|htm|xhtml|xlsx|xls|ods|3gp|mp4';
				   $config['max_size'] = '5000';
				   $this->upload->initialize($config);
				   if ( !$this->upload->do_upload('file') ) {
					  $error = array('error' => $this->upload->display_errors());
					  echo "<br/>";
					  echo $this->upload->display_errors();
					  echo "<br/> Attachment Upload error.<br/>";
				   } else {
					  $fdata = $this->upload->data();
					  $file = $fdata['file_name'];
				   }
				   
				}
				//echo $leave_start_date."<br>".$end_date_for_quota."<br>".$leftLeaveQuota."<br>type end date:".$leave_end_date;exit;
				
				//if($leftLeaveQuota >= $applayDay){
				if($lwpQuota > 0){

					$this->db->where('leave_category', "LWP");
				   $category = $this->db->get('tblleavecategory')->result(); 
				   $category_id = $category[0]->leave_category_id;
				   $app_id_update = $_POST['application_id'];
				   
				   $resultData = array('sandwitch_leave' => $sandwitchleave, 'sandwitch_avail' => $sandwitch, 'user_id' => $userid, 'leave_category_id' => $category_id, 'reason' => $reason, 'half_shift' => $half_shift,  'leave_type' => $leave_type, 'leave_start_date' => $after_leave_start_date, 'leave_end_date' => $leave_end_date, 'duration' => $lwpQuota, 'application_status' => '1', 'application_date' => $date, 'attachment' => $filename, 'comments' => '');
					$this->db->insert('tblleaveapplication', $resultData);
					//$lastid = $this->db->insert_id(); 
					$this->leave_management->leavesubmitreport($lastid);
					$msg = "New Leave Application Added Successfully..";
					
				}
				if($leftLeaveQuota != ""){
					$category_id = $categoryid;
					$resultData = array('sandwitch_leave' => $sandwitchleave, 'sandwitch_avail' => $sandwitch, 'user_id' => $userid, 'leave_category_id' => $category_id, 'reason' => $reason, 'half_shift' => $half_shift,  'leave_type' => $leave_type, 'leave_start_date' => $leave_start_date, 'leave_end_date' => $end_date_for_quota, 'duration' => $leftLeaveQuota, 'application_status' => '1', 'application_date' => $date, 'attachment' => $filename, 'comments' => '');
					//$this->db->insert('tblleaveapplication', $resultData);

					if($_POST['application_id'] == ""){
						$this->db->insert('tblleaveapplication', $resultData);
						$lastid = $this->db->insert_id();
						$msg = "New Leave Application Added Successfully..";
					}elseif($_POST['application_id'] != ""){
						$this->db->where('leave_application_id', $app_id_update);
						$this->db->update('tblleaveapplication', $resultData);
						$lastid = $_POST['application_id'];
						$msg = "Leave Application Updated Successfully..";
					}

					//$lastid = $this->db->insert_id();
					$this->leave_management->leavesubmitreport($lastid);
				}
				
				
				
				//$subject = "Leave Application Submitted";
				//$message = "Staff submitted a Leave Application ";
				//$this->emails_model->send_simple_email('aadi.compaddicts@gmail.com', $subject, $message);
				
				$this->session->set_flashdata('success', 'New Leave Application Added Successfully..');
				redirect(base_url('admin/leave/index'));
				
				
				
				
				
				
			print_r($_POST);
		}
      }
	  
	  public function getLeave(){
		$app_id = $_POST['applicationId'];
		$this->db->select("*");
		$this->db->from("tblleaveapplication");
		$this->db->where("leave_application_id", $app_id);
		$result = $this->db->get()->row();
		print_r(json_encode($result));
		//return json_encode($result);
		//echo $this->db->last_query();
	  }
      
	  public function deleteLeave(){
		$app_id = $_POST['applicationId'];
		$this->db->where('leave_application_id', $app_id);
		$this->db->delete('tblleaveapplication');
	  }
      
      public function updateleave()
      {
         $upd['leave_category'] = $_POST['leavename'];
         $leave_category_id = $_POST['leaveid'];
         $upd['leave_quota'] = $_POST['leavequota'];
         $upd['leave_rate'] = $_POST['leave_rate'];
         $upd['leave_carry'] = $_POST['leave_carry'];
         $upd['leave_gender'] = $_POST['leave_gender'];
         $upd['leave_marital'] = $_POST['leave_marital'];
         if ( $leave_category_id != '' && $upd['leave_category'] != '' && $upd['leave_quota'] != '' && $upd['leave_rate'] != '' && $upd['leave_carry'] != '' ) {
            $up = $this->leave_management->updatedata('tblleavecategory', $upd, $leave_category_id);
            if ( $up ) {
               $this->session->set_flashdata('success', 'Leave Updated Successfully..');
               redirect(base_url('admin/leave/allleaveManagement'));
            } else {
               $this->session->set_flashdata('error', ' Somthing Went Wrong here..');
               redirect(base_url('admin/leave/allleaveManagement'));
            }
         } else {
            $this->session->set_flashdata('error', 'All  * Fields Are Required..');
            redirect(base_url('admin/leave/allleaveManagement'));
         }
         
      }
      
      public function updateholiday()
      {
         $upd['name'] = $_POST['leavename'];
         $leave_id = $_POST['leaveid'];
         $upd['leave_type'] = $_POST['leave_type'];
         $upd['quota'] = $_POST['single_day_start_date'];
         $upd['leave_end_date'] = $_POST['leave_end_date'];
         
         $count = $_POST['leave_type'];
         $date1 = $_POST['single_day_start_date'];
         $date2 = $_POST['leave_end_date'];
         
         
         $datetime1 = new DateTime($date1);
         $datetime2 = new DateTime($date2);
         
         if ( $count == "multiple_days" ) {
            $interval = $datetime1->diff($datetime2);
            $tempcount = $interval->format('%a');
            $tempadd = "1";
            $diff = $tempcount + $tempadd . " Days";
         } else {
            $diff = "1 Day";
         }
         $upd['days'] = $diff;
         if ( $leave_id != '' && $upd['name'] != '' ) {
            $up = $this->leave_management->updateldata('tblholidays', $upd, $leave_id);
            if ( $up ) {
               $this->session->set_flashdata('success', 'Holiday Updated Successfully..');
               redirect(base_url('admin/leave/allleaveManagement'));
            } else {
               $this->session->set_flashdata('error', ' Somthing Went Wrong here..');
               redirect(base_url('admin/leave/allleaveManagement'));
            }
         } else {
            $this->session->set_flashdata('error', 'All  * Fields Are Required..');
            redirect(base_url('admin/leave/allleaveManagement'));
         }
         
      }
      
      public function allleaveManagement()
      {
       
         $data['title'] = _l('Leave_management');
         $data['leaveCategory'] = $this->db->get('tblleavecategory')->result();
         $data['leaveAppplication'] = $this->db->query("SELECT * from tblleaveapplication  WHERE application_status IN ('1','3','4') order by  leave_application_id desc")->result();
		 $data['holidays'] = $this->leave_management->getholidays('tblholidays');
		// echo '<pre>';
		// print_r($data['leaveAppplication']);exit;
         $this->load->view('admin/leave_management/all-leaveManagement', $data);
      }
      
      public function addleavecategory()
      {
         $data['title'] = _l('Leave_management');
         $upd['leave_category'] = $_POST['leavename'];
         $upd['leave_quota'] = $_POST['leavequota'];
         $upd['leave_rate'] = $_POST['leave_rate'];
         $upd['leave_carry'] = $_POST['leave_carry'];
         
         $upd['leave_gender'] = $_POST['leave_gender'];
         $upd['leave_marital'] = $_POST['leave_marital'];
         
         if ( $upd['leave_category']  ) {
            // $resultData=array('user_id'=>$userid,'leave_category_id'=>$categoryid,'reason'=>$reason,'leave_type'=>$leave_type,'hours'=>$hours,'leave_start_date'=>$leave_start_date,'leave_end_date'=>$leave_end_date,'application_status'=>'1','application_date'=>$date,'attachment'=>$filename,'comments'=>''); 
            $up = $this->db->insert('tblleavecategory', $upd);
            
            if ( $up ) {
               $this->session->set_flashdata('success', 'New Leave Added  Successfully..');
               redirect(base_url('admin/leave/allleaveManagement'));
            } else {
               $this->session->set_flashdata('error', ' Somthing Went Wrong here..');
               redirect(base_url('admin/leave/allleaveManagement'));
            }
         } else {
             
            $this->session->set_flashdata('error', ' All fields Are Required..');
            redirect(base_url('admin/leave/allleaveManagement'));
         }
         
      }
      
      public function addholiday()
      {
         $data['title'] = _l('Leave_management');
         $upd['name'] = $_POST['leavename'];
         $upd['leave_type'] = $_POST['leave_type'];
         $upd['quota'] = $_POST['single_day_start_date'];
         $upd['leave_end_date'] = $_POST['leave_end_date'];
         $count = $_POST['leave_type'];
         $date1 = $_POST['single_day_start_date'];
         $date2 = $_POST['leave_end_date'];
         
         
         $datetime1 = new DateTime($date1);
         $datetime2 = new DateTime($date2);
         
         
         if ( $count == "multiple_days" ) {
            
            
            $interval = $datetime1->diff($datetime2);
            
            $diff = $interval->format('%a Days');
            
         } else {
            $diff = "1 Day";
         }
         
         $upd['days'] = $diff;
         
         if ( $upd['name'] ) {
            // $resultData=array('user_id'=>$userid,'leave_category_id'=>$categoryid,'reason'=>$reason,'leave_type'=>$leave_type,'hours'=>$hours,'leave_start_date'=>$leave_start_date,'leave_end_date'=>$leave_end_date,'application_status'=>'1','application_date'=>$date,'attachment'=>$filename,'comments'=>''); 
            $up = $this->db->insert('tblholidays', $upd);
            
            if ( $up ) {
               $this->session->set_flashdata('success', 'New Holiday Added  Successfully..');
               redirect(base_url('admin/leave/allleaveManagement'));
            } else {
               $this->session->set_flashdata('error', ' Somthing Went Wrong here..');
               redirect(base_url('admin/leave/inallleaveManagementdex'));
            }
         } else {
            $this->session->set_flashdata('error', ' All fields Are Required..');
            redirect(base_url('admin/leave/allleaveManagement'));
         }
         
      }
      
      public function deleteholiday( $id )
      {
         $del = $this->leave_management->deleteleavecategory('tblholidays', array('id' => $id));
         if ( $del ) {
            $this->session->set_flashdata('success', 'Holiday Deleted Successfully..');
            redirect(base_url('admin/leave/allleaveManagement'));
         } else {
            $this->session->set_flashdata('error', ' Somthing Went Wrong here..');
            redirect(base_url('admin/leave/allleaveManagement'));
         }
      }
      
      public function deleteleavecategory( $id )
      {
         $del = $this->leave_management->deleteleavecategory('tblleavecategory', array('leave_category_id' => $id));
         if ( $del ) {
            $this->session->set_flashdata('success', 'Leave Delete Successfully..');
            redirect(base_url('admin/leave/allleaveManagement'));
         } else {
            $this->session->set_flashdata('error', ' Somthing Went Wrong here..');
            redirect(base_url('admin/leave/allleaveManagement'));
         }
      }
      
      /*-------  get Application Details Or reason  ---------*/
      
      public function getApplicationdetails()
      {
         $AppId = $_POST['applicationId'];
         $count = 0;
         $data['leavedata'] = $this->leave_management->getData('tblleaveapplication', array('leave_application_id' => $AppId), null);
         $uid = $data['leavedata'][0]->user_id;
         $this->db->select('gender,marital_status,confirmation_date');
          $this->db->where('staffid', $uid);
          $query = $this->db->get('tblstaff')->row();
          $confirmation_date = $query->confirmation_date;
          $gender = $query->gender;
          $marital_status = $query->marital_status;
          if($gender == 'Male')
          {
              
              $this->db->where('leave_gender !=', 'Female');
              
          }
          else
          {
              
              $this->db->where('leave_gender !=', 'Male');
          }
          if($marital_status == 'Married')
          {
              
              $this->db->where('leave_marital !=', 'Unmarried');
          }
          else
          {
              
              $this->db->where('leave_marital !=', 'Married');
          }
          
         $category = $this->db->get('tblleavecategory')->result();
         
         if($confirmation_date == "0000-00-00")
         {
             $this->db->where('leave_category_id', '11');
             $category = $this->db->get('tblleavecategory')->result();
             
         }
         $stoff = $this->leave_management->getData('tblstaff', array('staffid' => $uid), 'null');
         $date1 = $stoff[0]->confirmation_date;
         $date2 = date('Y-m-d');
         $diff = abs(strtotime($date2) - strtotime($date1));
         $years = floor($diff / (365 * 60 * 60 * 24));
         $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
         $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
         
         $cur_month = date('m');
            $cur_year = date('Y');
            if($cur_month <= '3')
            {
                
                $fi_end_year = $cur_year;
                $fi_start_year = $cur_year-1;
            }
            else
            {
               $fi_end_year = $cur_year+1;
               $fi_start_year = $cur_year; 
            }
            
            $curdate=strtotime($date1);
            $mydate=strtotime('01-04-'.$fi_start_year);
            $date_end_cus = '31-03-'.$fi_end_year;
         $diff_custom = abs(strtotime($date_end_cus) - strtotime($date1));
         
         $years_custom = floor($diff_custom / (365 * 60 * 60 * 24));
         $months_custom = floor(($diff_custom - $years_custom * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
         $days_custom = floor(($diff_custom - $years_custom * 365 * 60 * 60 * 24 - $months_custom * 30 * 60 * 60 * 24) / (60 * 60 * 24));
          // echo  $date_end_cus;
          
          
         if($curdate <= $mydate)
         {
            $result = array();
         foreach ($category as $value) {
            $count = 0;
            
            if ( $value->leave_rate == '0' ) {
               $lcatid = $value->leave_category_id;
               $catLeaveCount = $this->leave_management->getData('tblleaveapplication', array('user_id' => $loginid, 'leave_category_id' => $lcatid), 'AND');
               if ( count($catLeaveCount) == 0 ) {
                  $count = $value->leave_quota;
               } else {
                  $count = "0";
               }
            } else if ( $value->leave_rate == '1' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * $value->leave_quota;
               }
               $count = $count + 1 * $value->leave_quota;
               // $count=$count+$years*$value->leave_quota;
               //$count=$count+$months*$value->leave_quota;
            } else if ( $value->leave_rate == '2' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * 12 * $value->leave_quota;
               }
               $count = $count + $months * $value->leave_quota;
            }
            
            array_push($result, array('leave_category_id' => $value->leave_category_id, 'leave_category' => $value->leave_category, 'leave_quota' => $value->leave_quota, 'leave_rate' => $value->leave_rate, 'leave_carry' => $value->leave_carry, 'totalleave' => $count));
            // print_r($value->leave_category.'--'.$count.'<br/>'); 
         } 
         }
         else
         {
             $result = array();
         foreach ($category as $value) {
            $count = 0;
            
            if ( $value->leave_rate == '0' ) {
               $lcatid = $value->leave_category_id;
               $catLeaveCount = $this->leave_management->getData('tblleaveapplication', array('user_id' => $loginid, 'leave_category_id' => $lcatid), 'AND');
               if ( count($catLeaveCount) == 0 ) {
                  $count = $value->leave_quota;
               } else {
                  $count = "0";
               }
            } else if ( $value->leave_rate == '1' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * (round(($value->leave_quota*$months_custom)/12));
               }
               $count = $count + 1 * (round(($value->leave_quota*$months_custom)/12));
               // $count=$count+$years*$value->leave_quota;
               //$count=$count+$months*$value->leave_quota;
            } else if ( $value->leave_rate == '2' ) {
               if ( $value->leave_carry == '1' ) {
                  $count = $count + $years * 12 * (round(($value->leave_quota*$months_custom)/12));
               }
               $count = $count + $months * (round(($value->leave_quota*$months_custom)/12));
            }
            
            array_push($result, array('leave_category_id' => $value->leave_category_id, 'leave_category' => $value->leave_category, 'leave_quota' => $value->leave_quota, 'leave_rate' => $value->leave_rate, 'leave_carry' => $value->leave_carry, 'totalleave' => $count));
            // print_r($value->leave_category.'--'.$count.'<br/>'); 
         }
         }
         
         
         
         $data['leaveCategory'] = $result;
         $data['uid'] = $uid;
         $month = date('m');
            $year = date('Y');
            if($month <= '3')
            {
                
                $fi_end_year = $year;
                $fi_start_year = $year-1;
            }
            else
            {
               $fi_end_year = $year+1;
               $fi_start_year = $year; 
            }
            
            $data['start'] = $fi_start_year.'-04-01';
            $data['end'] = $fi_end_year.'-03-31';
         $this->load->view('admin/leave_management/leavepopup', $data);
         
      }
      
      
      public function togglestatus()
      {
         $applicationId = $_POST['AppId'];
         $status = $_POST['sts'];
         $leaveappliaction = $this->leave_management->getData('tblleaveapplication', array('leave_application_id' => $applicationId), null);
         if ( $status == '2' ) {
            $this->leave_management->leave_report($applicationId, $status);
            $this->db->select('leave_category_id,duration,user_id');
            $this->db->where('leave_application_id',$applicationId);
            $leavect = $this->db->get('tblleaveapplication')->row();
            
            $catid = $leavect->leave_category_id;
            if($catid == 0)
            {
                            $this->db->select('sp_leave');
                            $this->db->where('staffid', $leavect->user_id);
                            $spstaff = $this->db->get('tblstaff')->row();
                            $spstaffid = $spstaff->sp_leave;
                            
                            $balance = $spstaffid-round($leavect->duration);
                            $arr = array('sp_leave',$balance);
                            $this->db->where('staffid',$leavect->user_id);
                            $this->db->update('tblstaff', $arr);
            }
            
            echo 'Approved';
         } else if ( $status == '3' ) {
            $this->leave_management->leave_report($applicationId, $status);
            echo 'Rejected';
         }
      }
      
      public function custom_remark()
      {
         $custom_lid = $_POST['id'];
         $updatecu = $_POST['description'];
         $upd['description'] = $updatecu;
         
         
         $this->db->where('id', $custom_lid);
         $this->db->update("tblleads", $upd);
         
         echo 'Approved';
      }
      
      
      public function import_holidays()    
      {
         
         
         // $this->load->model('leave_management');
         $data['title'] = _l('Leave_management');
         
         
         $data['leaveCategory'] = $this->db->get('tblleavecategory')->result();
         
         $data['leaveAppplication'] = $this->db->query("SELECT * from tblleaveapplication  order by leave_application_id desc limit 50")->result();
         $data['holidays'] = $this->leave_management->getholidays('tblholidays');
         
         $this->load->view('admin/leave_management/import_holidays', $data);
      }
      
      function upload_holidays()
      {
         $this->leave_management->upload_holidays();
         redirect('admin/leave/import_holidays');
      }
      
      public function deleteholibulk()
      {
         
         if ( isset($_POST['emp_id']) ) {
            
            
            $ids = trim($_POST['emp_id']);
            
            $emp_id = trim($_POST['emp_id']);
            
            
            $delete = $this->leave_management->deleteholibulk($ids);
            
            // If delete is successful
            if ( $delete ) {
               set_alert('success', 'Selected Holiday have been deleted successfully');
            } else {
               set_alert('success', 'Some problem occurred, please try again.');
            }
            
            echo $emp_id;
         }
      }
      
      public function getstaffleave($staff_id)
      {
          $this->db->select('gender,marital_status');
          $this->db->where('staffid', $staff_id);
          $query = $this->db->get('tblstaff')->row();
          $confirmation_date = $query->confirmation_date;
          $gender = $query->gender;
          $marital_status = $query->marital_status;
      }
      
      public function specialleave()
      {
           if($this->input->post())
          {
			  //print_r($_POST);exit;
              $quota = $this->input->post('quota');
              /* if($this->input->post('employee'))
              {
                  $emp = $this->input->post('employee');
                  
                  $this->db->select('sp_leave');
                  $this->db->where('bio_id', $emp);
                  $stfdetails = $this->db->get('tblstaff')->row();
                  
                  $prespleave = $stfdetails->sp_leave;
                  $newspleave = $prespleave+$quota;
                  
                  $arr = array('sp_leave' => $newspleave);
                  $this->db->where('bio_id', $emp );
                  $login = $this->db->update('tblstaff', $arr);
                  
                  
                  set_alert('success', "Special Leave Alloted Successfully");
                redirect(admin_url('leave/specialleave'));
              }
              else
              {
                  
                  $teamid = $this->input->post('team');
                  $this->db->select('staffid');
                  $this->db->where('id', $teamid);
                  $teama = $this->db->get('tblteams')->row();
                  $rmid = $teama->staffid;
                  if($rmid)
                  {
                          $emp = $rmid;
                      
                          $this->db->select('sp_leave');
                          $this->db->where('staffid', $emp);
                          $stfdetails = $this->db->get('tblstaff')->row();
                          
                          $prespleave = $stfdetails->sp_leave;
                          $newspleave = $prespleave+$quota;
                          
                          $arr = array('sp_leave' => $newspleave);
                          $this->db->where('staffid', $emp );
                          $login = $this->db->update('tblstaff', $arr);
                      
                         
                         
                         
                     $this->db->select('staffid');
                     $this->db->where('reporting_manager', $rmid);
                     $staffbiolists = $this->db->get('tblstaff')->result();
                     foreach($staffbiolists as $staffid)
                     {
                          $emp = $staffid->staffid;
                          $this->db->select('sp_leave');
                          $this->db->where('staffid', $emp);
                          $stfdetails = $this->db->get('tblstaff')->row();
                          
                          $prespleave = $stfdetails->sp_leave;
                          $newspleave = $prespleave+$quota;
                          
                          $arr = array('sp_leave' => $newspleave);
                          $this->db->where('staffid', $emp );
                          $login = $this->db->update('tblstaff', $arr);
                         
                         
                         
                            
                     }
                  
                      
                      set_alert('success', "Team Attendance Recorded Successfully");
                            //redirect(admin_url('attendance/mass_attanadance'));
                            redirect(admin_url('reports/leave_report'));
                            
                  
                  } */
				  
              //}
			  
			  foreach($_POST['employee'] as $staffid){
				  $this->db->select("team_id");
				  $this->db->from("tblstaffdepartments");
				  $this->db->where("staffid", $staffid);
				  $query = $this->db->get();
				  //echo $this->db->last_query();
				  $result = $query->row();
				  $data = array("team_id" => $result->team_id, "emp_id" => $staffid, "quota" => $_POST['quota']);
				$this->leave_management->saveSpecialLeave($data);
				  
			  }
				
			set_alert('success', "Special Leave Alloted Successfully");
			redirect(admin_url('leave/specialleave'));
             
          }

         $data['title'] = "Special Leave";
         $data['staff_members'] = $this->staff_model->get('', ['active' => 1, 'bio_id !=' => null]);
         $this->db->select('*');
         $this->db->where('active',1);
         $this->db->where('department_id !=',0);
         $data['teams'] = $this->db->get('tblteams')->result_array();
         $this->load->view('admin/leave_management/specialleave', $data); 
      }
	  
	  public function show_team_member(){
			$teamIds = $_POST['team_id'];
			$mass = $_POST['mass'];
			//echo $mass;exit;
			$select = 'tblstaffdepartments.*, tblstaff.firstname, tblstaff.lastname, tblstaff.bio_id';
			$this->db->select($select);
			$this->db->from("tblstaffdepartments");
			$this->db->join('tblstaff', 'tblstaff.staffid = tblstaffdepartments.staffid');
			$this->db->where_in('tblstaffdepartments.team_id', $teamIds);
			$this->db->where('tblstaff.active ', 1);
			
			/* $select = 'tblstaff.firstname, tblstaff.lastname, tblstaff.bio_id, tblstaff.staffid';
			$this->db->select($select);
			$this->db->from("tblstaff");
			$this->db->where_in('tblstaff.member_team', $teamIds);
			$this->db->where('tblstaff.active', 1); */
			
			$query = $this->db->get(); 
			//echo $this->db->last_query(); exit;
			
			//echo "<pre>";
			//print_r($query->result());exit;
			$result = $query->result();
			if(!empty($result)){
			foreach($result as $totaldata){
				if($mass == "mass"){
					$html .= "<option value='".$totaldata->bio_id."'>";
				}else{
					$html .= "<option value='".$totaldata->staffid."'>";
				}
					
				$html .= $totaldata->firstname." ".$totaldata->lastname;
				$html .= "</option>";
			}
			}else{
				$html = "<option>No data found</option>";
			}
			echo $html;
	  }
      
      public function sandwitchcheck()
      {
          $startdate = $this->input->post('start_date');
          $enddate = $this->input->post('end_date');
          
          $this->db->select('name');
          $this->db->where('date(quota) >=', $startdate);
          $this->db->where('date(quota) <=', $enddate);
          $holid = $this->db->get('tblholidays')->row();
          if(count($holid) > 0)
          {
           echo   $holiresponce = count($holid);
          }
          else
          {
            echo  $holiresponce = "0";
          }
      }
	  
	  public function countLeave(){
		  $userId = $_POST['user_id'];
		  $leaveCatId = $_POST['leave_cat_id'];
		  
		  $staff_id = $this->session->userdata('staff_user_id');
			$bio_id = $this->session->userdata('staff_bio_id');
		  
		  $this->db->select_sum('duration');
		  $this->db->from('tblleaveapplication');
          $this->db->where('user_id', $userId);
          $this->db->where('leave_category_id', $leaveCatId);
          $this->db->where('application_status', 2);
		  $this->db->where('leave_start_date BETWEEN "'. date('Y-m-d', strtotime('2020-04-01')). '" and "'. date('Y-m-d', strtotime('2021-03-31')).'"');
          $holid = $this->db->get()->row();
		  
		  $totalday = $this->db->query('SELECT  count(UserId) as cnt FROM deviceLogs_2_2020 WHERE year(LogDate) = 2020 AND (UserId = "'.$bio_id.'" OR staffId = "'.$staff_id.'") GROUP BY `UserId`, date(LogDate) HAVING cnt = 1');
		  $missedLogs = $totalday->result();
		  $totalMissedLog = 0;
			foreach($missedLogs as $missedLog){
				$totalMissedLog = $totalMissedLog+$missedLog->cnt;
			}
		  //print_r($totalMissedLog);exit;
		  if($totalMissedLog > 0){
			  $totalMissedDays = $totalMissedLog/2;
		  }else{
			  $totalMissedDays = 0;
		  }
		  //echo $this->db->last_query();exit;
		  //print_r($holid);exit;
		  if($leaveCatId == 0){
			  $this->db->select_sum('quota');
			  $this->db->from('special_leave');
			  $this->db->where('emp_id', $userId);
			  //$this->db->where('leave_category_id', $leaveCatId);
			  $quota = $this->db->get()->row();
			  $total = ($quota->quota) - ($holid->duration);
		  }else{
			  $this->db->select_sum('leave_quota');
			  $this->db->from('tblleavecategory');
			  //$this->db->where('user_id', $userId);
			  $this->db->where('leave_category_id', $leaveCatId);
			  $quota = $this->db->get()->row();
			  //echo $this->db->last_query();exit;
			  $total = ($quota->leave_quota) - ($holid->duration+$totalMissedDays);
		  }
		  echo $total;
	  }

	  /*** Start Sanction Leave ***/
	  public function sanctionApprove(){
		$app_id = $_POST['applicationId'];
		$data = array(
			'application_status' => 4
		);
		$this->db->where('leave_application_id', $app_id);
		$this->db->update('tblleaveapplication', $data);
	  }
	  /*** Stop Sanction ***/
	/** Start Get pending team's leaves  **/  
	public function pendingApprovels() {
		$data['title'] = _l('Pending Approval Leaves');
		$arr=herapermission();
		//print_r($arr);exit;
        $data['pendingApproval'] = $this->leave_management->getTeamPendingApproval('tblleaveapplication', $arr);
		 
		$this->load->view('admin/leave_management/pending_approvel', $data);
	}
	/** Stop Get pending team's leaves  **/  
	/*** Start Get leave category by id ***/
	public function getLeaveCategoryById($catId){
		$this->db->where('leave_category_id',$catId); 
		return $this->db->get('tblleavecategory')->result();
	}
	/*** Stop Get leave category by id ***/
	/*** Stop Get leave category by id ***/
	public function sanctionDisapprove(){
		$app_id = $_POST['applicationId'];
		$data = array(
		'application_status' => 3
		);
		$this->db->where('leave_application_id', $app_id);
		$this->db->update('tblleaveapplication', $data);
	}
	
	/*** Stop Get leave category by id ***/
	
   }
