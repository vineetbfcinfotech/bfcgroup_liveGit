<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <hr class="hr-panel-heading">
                        
                        <div class="row">
                        <center><h3>Employee Monthly Salary Report </h3></center>
                        </div>
                        <br>
                        <hr class="hr-panel-heading">
                        <?php
                        
                        $date = date("Y-m-d", strtotime("first day of previous month"));
                        $reportmonthdate = date("Y-m-d", strtotime("last day of previous month"));
                          $timestamp = strtotime($date);
                          $day = date("d", $timestamp);
                          //$month = date("m", $timestamp);
                          $month = 11;
                          if(preg_match("~^0\d+$~", $month))
                          {
                              $month = str_replace("0", "", $month);
                          }
                          $year = date("Y", $timestamp );
                          //echo $year;exit;
                          //$table = "deviceLogs_".$month."_".$year;
                          $table = "deviceLogs_2_2020";
                        
                        $numberofdaysincumonth = cal_days_in_month(CAL_GREGORIAN,$month,$year);
                             
                             $this->db->select('quota');
                             $this->db->where('leave_type', 'single_day');
                             $this->db->where('month(quota)', $month);
                             $holisingles = $this->db->get('tblholidays')->result();
                             
                             $singleholisunday = 0;
                             foreach($holisingles as $holisingle)
                             {
                                 $singleholi = $holisingle->quota;
                                 $thisholiday = date('D', strtotime($singleholi));
                                 if($thisholiday == 'Sun')
                                 {
                                   $singleholisunday+=1;  
                                 }
                                 
                             }
                             
                             $this->db->select('quota,leave_end_date');
                             $this->db->where('leave_type', 'multiple_days');
                             $this->db->where('month(quota)', $month);
                             $holimulties = $this->db->get('tblholidays')->result();
                             
                             $multiholisunday = 0;
                             foreach($holimulties as $holimultie)
                             {
                                 $multiholistart = $holimultie->quota;
                                 $multiholidayend = $holimultie->leave_end_date; 
                                 
                                 $timestart = strtotime($multiholistart);
                                 $startDate = date('Y-m-d',$timestart);
                                 
                                 $startDate = new DateTime($multiholistart);
                                $endDate = new DateTime($multiholidayend);


                                    while ($startDate <= $endDate) {
                                        if ($startDate->format('w') == 0) {
                                          $multiholisunday+=1;
                                        
                                        }
                                        $startDate->modify('+1 day');
                                    
                                    }

                                 
                             }
                             
                              $monthName = date("F", mktime(0, 0, 0, $month));
                              $fromdt=date('Y-m-01 ',strtotime("First Day Of  $monthName $year")) ;
                              $todt=date('Y-m-d ',strtotime("Last Day of $monthName $year"));
                            
                            //echo $todt;
                            
                              $num_sundays='';                
                              for ($i = 0; $i <= ((strtotime($todt) - strtotime($fromdt)) / 86400); $i++)
                              {
                                if(date('l',strtotime($fromdt) + ($i * 86400)) == 'Sunday')
                                {
                                        $num_sundays++;
                                }    
                              }
                              $totalsunday = $num_sundays;
                             
                             
                             $totalsundaysonholidays = $singleholisunday+$multiholisunday;
                             //echo $totalsunday;exit;
                             $totalsunday = $totalsunday-$totalsundaysonholidays;
                           
                            $this->db->select_sum('days');
                            $this->db->where('month(quota)', $month);
                            $holithisday = $this->db->get('tblholidays')->row();
                            //echo $this->db->last_query();exit;
                            $totalholidays = $holithisday->days;

                            $totalleavesinmonth = $totalholidays+$totalsunday;

                            $actworkingdaysinmonth = $numberofdaysincumonth-$totalleavesinmonth;
                              
                            
                              
                        
                        ?>
                        <div class="dropdown bootstrap-select show-tick">
                        <input type="button" onclick="tableToExcel('testTable', 'W3C Example Table')" value="Export to Excel">
                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                        <input type="button" id="adjustleave" value="Adjust Leave Quota">
                        </div>
                        <hr class="hr-panel-heading">
                        <div class="row">
                            <div class="col-md-4">
                                <h4>Month: <?= $monthName; ?> <?= $year?></h4>
                            </div>
                            <div class="col-md-4">
                                <h4>Total Holidays: <?= $totalleavesinmonth; ?></h4>
                            </div>
                            <div class="col-md-4">
                                <h4>Actual Working Days: <?= $actworkingdaysinmonth; ?> </h4>
                            </div>
                        </div>
                        
        <div class="row">
              <div class="col-sm-12" >
                    
                    <table id="testTable" class="table table-responsive">
                    <thead>
                        <tr>
                            <td><strong> Employee </strong></td>
                             
							<!--<td><strong><?="Total Working Days" ?></strong></td>-->
							<td><b>SL</b></td>
							<td><b>CL</b></td>
							<td><b>ML</b></td>
							<td><b>EL</b></td>
							<td><b>LWP</b></td>
							<td>Half Day By Missed Logs</td>
							<td>Half Day By Late Login</td>
							<td>Half Day By Early Logout</td>
							<td>Approved CLR Halfday</td>
							<td><strong>Actual Half Day</strong></td>
							
							<td><strong>Actual Day Deduction</strong></td>
							<td><strong><?="Total Dedcutions" ?></strong></td>
                            <td><strong><?="Net Salary This Month" ?></strong></td>
                                
                            
                        </tr>
                        </thead>
                        <tbody>
                         <?php
                         $date = "2021-11-11";
                          $timestamp = strtotime($date);
                          $day = date("d", $timestamp);
                          $month = date("m", $timestamp);
                          if(preg_match("~^0\d+$~", $month))
                          {
                              $month = str_replace("0", "", $month);
                          }
                          $year = date("Y", $timestamp );
                          
                          
                        //   $table = "deviceLogs_".$month."_".$year;
                        $table = "deviceLogs_2_2020";
                          
                        // print_r($staff_members);
                        
foreach ($staff_members as $v_employee)
{ ?>
    <tr>  
      <td> <strong><?=$v_employee['firstname'] . ' ' . $v_employee['lastname']; ?></strong></td>
                              
                              
    <?php 
    $sumofmornhalf = 0;
     $sumofevenhalf = 0;
     
     
     $sumofmornmissed = 0;
     $sumofevenmissed = 0;
     $sumoftotalabsent = 0;
     $totalsumofmornclr = 0;
     $totalsumofevenclr = 0;
	 
	 $date = date("Y-m-d");
	// echo $date;exit;
	
	$deductMissed = 0;
	 
     foreach($list as $dates) {
        /* $this->db->select("*");
        $this->db->from($table);
		$this->db->where("date(LogDate)", $dates);
        $this->db->where("staffid", $v_employee['staffid']);
		$this->db->order_by("DeviceLogId", "asc");
        $query = $this->db->get();
        $attendanceDate = $query->result(); */
		
		// $sql_query = "SELECT deviceLogs_2_2020.* FROM `deviceLogs_2_2020` JOIN tblleaveapplication ON deviceLogs_2_2020.staffId=tblleaveapplication.user_id WHERE date(deviceLogs_2_2020.LogDate) = '".$dates."' AND (`staffid` = '".$v_employee['staffid']."' || deviceLogs_2_2020.UserId = '".$v_employee['bio_id']."') &&  ORDER BY deviceLogs_2_2020.LogDate ASC";
		
		$sql_query = "SELECT * FROM `deviceLogs_2_2020` WHERE date(LogDate) = '".$dates."' AND (`staffid` = '".$v_employee['staffid']."' || `UserId` = '".$v_employee['bio_id']."') ORDER BY `LogDate` ASC";
		$get_query = $this->db->query($sql_query);
		$attendanceDate = $get_query->result();
		
		//echo "<pre>";print_r($attendanceDate);
		
		//$this->db->select("tblleaveapplication.*, deviceLogs_2_2020.staffId, deviceLogs_2_2020.LogDate");
		$this->db->select("tblleaveapplication.*");
		$this->db->from("tblleaveapplication");
		//$this->db->join('deviceLogs_2_2020', 'tblleaveapplication.user_id = deviceLogs_2_2020.staffId');
		$this->db->where("tblleaveapplication.user_id",$v_employee['staffid']);
		$this->db->where("date(tblleaveapplication.leave_start_date)", $dates);
		$query = $this->db->get();
		$leaveDate = $query->result();
		// echo substr($attendanceDate[0]->LogDate, 0, 10)." -- ". $leaveDate[0]->leave_start_date . " -- " .$v_employee['staffid']." --<br>";
		$leaveDuration = 0;
		if(!empty($leaveDate[0]) && substr($attendanceDate[0]->LogDate, 0, 10) == $leaveDate[0]->leave_start_date && $leaveDate[0]->duration == "0.5" && date("G:i", strtotime(end($attendanceDate)->LogDate)) < date("G:i", strtotime("18:30"))){
			$leaveDuration = $leaveDuration+0.5;
		}
		
		// echo count($attendanceDate)." -- ".$v_employee['staffid']."<br>";
		
		if(date("d-M-yyyy", strtotime($attendanceDate[0]->LogDate)) == date("d-M-yyyy", strtotime($dates)) && (date("G:i", strtotime($attendanceDate[0]->LogDate)) > date("G:i", strtotime("09:45"))) || date("G:i", strtotime($attendanceDate[0]->LogDate)) < date("G:i", strtotime("02:00"))){
			$sumofmornhalf = $sumofmornhalf + 1;
		}
		if(!empty($leaveDate[0]) && date("G:i", strtotime($attendanceDate[0]->LogDate)) <= date("G:i", strtotime("01:00")) && $leaveDate[0]->half_shift == "Morning"){
			$sumofmornhalf = $sumofmornhalf - 1;
		}
		
		if(date("d-M-yyyy", strtotime(end($attendanceDate)->LogDate)) == date("d-M-yyyy", strtotime($dates)) && (date("G:i", strtotime(end($attendanceDate)->LogDate)) < date("G:i", strtotime("18:30")) && date("G:i", strtotime(end($attendanceDate)->LogDate)) > date("G:i", strtotime("04:00")))){
			$sumofevenhalf = $sumofevenhalf + 1;
		}
		
		/* if(!empty($leaveDate[0]) && substr($attendanceDate[0]->LogDate, 0, 10) != $leaveDate[0]->leave_start_date && ($leaveDate[0]->duration != "0.5" && date("G:i", strtotime(end($attendanceDate)->LogDate)) < date("G:i", strtotime("14:00")))){
			$sumofevenhalf = $sumofevenhalf + 1;
		} */
		
		if(empty($leaveDate[0]) && (date("G:i", strtotime(end($attendanceDate)->LogDate)) < date("G:i", strtotime("18:30")))){
			$sumofevenhalf = $sumofevenhalf + 1;
		}
		
		/* if(date("G:i", strtotime($attendanceDate[0]->LogDate)) > date("G:i", strtotime("02:00")) && date("G:i", strtotime(end($attendanceDate)->LogDate)) > date("G:i", strtotime("18:30"))){
			$sumofevenhalf = $sumofevenhalf + 2;
		} */
		

		
		//&& (!empty($leaveDate[0]) && substr($attendanceDate[0]->LogDate, 0, 10) == $leaveDate[0]->leave_start_date && $leaveDate[0]->duration != "0.5" && date("G:i", strtotime(end($attendanceDate)->LogDate)) < date("G:i", strtotime("18:30")))
		
		if(count($attendanceDate) == 1){
			$sumofevenmissed = $sumofevenmissed + 1;
		}
		/* if(count($attendanceDate) == 1 && date("G:i", strtotime($attendanceDate[0]->LogDate)) > date("G:i", strtotime("09:45"))){
			$sumofevenmissed = $sumofevenmissed + 1;
			$sumofmornhalf = $sumofmornhalf + 1;
		} */
		
		if($v_employee['confirmation_date'] == "0000-00-00" || $v_employee['confirmation_date'] == NULL){
			$deductMissed = $sumofevenmissed;
		}
		
		/* if(count($attendanceDate) == 1 && date("G:i", strtotime($attendanceDate[0]->LogDate)) > date("G:i", strtotime("09:45"))){
			$sumofmornmissed = $sumofmornmissed + 1;
		} */
		
		//echo "<pre>";print_r($attendanceDate);
    }
	
	// echo $attendanceDate[0]->LogDate ." == ".$v_employee['firstname'];
	
	
	if($v_employee['confirmation_date'] == null || $v_employee['confirmation_date'] == "0000-00-00"){
		$totDeductDays = ($sumofmornhalf+$sumofevenhalf)/2;
	}else{
		$totDeductDays = 0;
	}
	
	
    ?> 
    <?php
    
    
                              
      $empbioidmonth = $v_employee['bio_id'];
      
      
      $morecountinmonth =   $this->db->query("SELECT `LogDate`, `UserId`, count(UserId) as cnt FROM $table WHERE month(LogDate) = $month AND `UserId` = '$empbioidmonth' GROUP BY `UserId`, date(LogDate) HAVING cnt > '2'");
    
    $morecounts =  $morecountinmonth->result();
    $cntofemp = $morecounts[0]->cnt;
    
    if($cntofemp > 2)
    {
    
    foreach($morecounts as $morecount)
    {
       $s = strtotime($morecount->LogDate);
    
       $date = date('Y-m-d', $s);
       $this->db->select('DeviceLogId,LogDate');
       $this->db->where('date(LogDate)', $date);
       $this->db->where('UserId', $v_employee['bio_id']);
       $this->db->order_by('time(LogDate)', 'ASC');
       $firstlogind = $this->db->get($table)->row();
       
       //print_r($this->db->last_query());
       $firstlogid = $firstlogind->DeviceLogId;
       
       $this->db->select('DeviceLogId,LogDate');
       $this->db->where('date(LogDate)', $date);
      $this->db->where('UserId', $v_employee['bio_id']);
       $this->db->order_by('time(LogDate)', 'DESC');
       $lastlogind = $this->db->get($table)->row();
       
       $lastlogid = $lastlogind->DeviceLogId;
       
       $safeids = array($firstlogid,$lastlogid);
       
       $this->db->where_not_in('DeviceLogId', $safeids);
       $this->db->where('date(LogDate)',$date);
      $this->db->where('UserId', $v_employee['bio_id']);
      $this->db->delete($table); 
       
    }

    
    }
      
      $totaldaymissed = $this->db->query("SELECT `LogDate`, `UserId`, count(UserId) as cnt FROM $table WHERE month(LogDate) = $month AND `UserId` = '$empbioidmonth' GROUP BY `UserId`, date(LogDate) HAVING cnt = '1'");
      $totallogsmonth = $totaldaymissed->result();
      
      
      //print_r($this->db->last_query());
      $totalmissday = count($totallogsmonth);
      
      $datemiss = array();
        foreach($totallogsmonth as $totallogsmonthl) {
         $datemiss[] = $totallogsmonthl->LogDate;;
        }

        if($datemiss != null)
        {
      $this->db->select('*');
      $this->db->where('UserId', $v_employee['bio_id']);
      $this->db->where('month(LogDate)', $month);
       $this->db->where_not_in('date(LogDate)', $datemiss);
      $this->db->group_by('date(LogDate)');
      $totalempatt = $this->db->get($table)->result();
      $totalday = count($totalempatt);
        }
        else
        {
      $this->db->select('*');
      $this->db->where('UserId', $v_employee['bio_id']);
      $this->db->where('month(LogDate)', $month);
      $this->db->group_by('date(LogDate)');
      $totalempatt = $this->db->get($table)->result();
      $totalday = count($totalempatt);
        }
      
      
      $this->db->select('*');
      $this->db->where('UserId', $v_employee['bio_id']);
      $this->db->where('month(LogDate)', $month);
      $this->db->where('time(LogDate) >=', "09:46:00");
      $this->db->where('time(LogDate) <=', "12:00:00");
      $this->db->group_by('date(LogDate)');
      $totalempatt = $this->db->get($table)->result();
      $totalmorninghalf = count($totalempatt);
      

      $this->db->select('*');
      $this->db->where('UserId', $v_employee['bio_id']);
      $this->db->where('month(LogDate)', $month);
      $this->db->where('time(LogDate) >', "12:00:00");
      $this->db->where('time(LogDate) <', "18:30:00");
      $this->db->group_by('date(LogDate)');
      $totalempatt = $this->db->get($table)->result();
     //print_r($this->db->last_query());
      $totalveninginghalf = count($totalempatt);
      
      $totalhalf = $totalmorninghalf+$totalveninginghalf;
      
     
      
      $this->db->select_sum('duration');
      $this->db->where('user_id',$v_employee['staffid']);
      $this->db->where('month(leave_start_date)', $month);
      $this->db->where('year(leave_start_date)', 2021);
      $this->db->where('application_status', '2');
      $this->db->where('leave_category_id !=', '11');
      $paidleaves = $this->db->get('tblleaveapplication')->row();
      $paidlvs = $paidleaves->duration;
      
      
      $this->db->select_sum('duration');
      $this->db->where('user_id',$v_employee['staffid']);
      $this->db->where('month(leave_start_date)', $month);
      $this->db->where('year(leave_start_date)', 2021);
      $this->db->where('application_status', '2');
      $this->db->where('leave_category_id', '1');
      $cls = $this->db->get('tblleaveapplication')->row();
      $totalcl = $cls->duration;
      
      
      $this->db->select_sum('duration');
      $this->db->where('user_id',$v_employee['staffid']);
      $this->db->where('month(leave_start_date)', $month);
      $this->db->where('year(leave_start_date)', 2021);
      $this->db->where('application_status', '2');
      $this->db->where('leave_category_id', '0');
      $cls = $this->db->get('tblleaveapplication')->row();
      $SLleaves = $cls->duration;
      
      
      $this->db->select_sum('duration');
      $this->db->where('user_id',$v_employee['staffid']);
      $this->db->where('month(leave_start_date)', $month);
      $this->db->where('year(leave_start_date)', 2021);
      $this->db->where('application_status', '2');
      $this->db->where('leave_category_id', '7');
      $els = $this->db->get('tblleaveapplication')->row();
      $totalel = $els->duration;
      
      $this->db->select_sum('duration');
      $this->db->where('user_id',$v_employee['staffid']);
      $this->db->where('month(leave_start_date)', $month);
      $this->db->where('year(leave_start_date)', 2021);
      $this->db->where('application_status', '2');
      $this->db->where('leave_category_id', '3');
      $mls = $this->db->get('tblleaveapplication')->row();
      $totalml = $mls->duration;
      
      $this->db->select_sum('duration');
      $this->db->where('user_id',$v_employee['staffid']);
      $this->db->where('month(leave_start_date)', $month);
      $this->db->where('year(leave_start_date)', 2021);
      $this->db->where('application_status', '2');
      $this->db->where('leave_category_id', '11');
      $lwps = $this->db->get('tblleaveapplication')->row();
      $totallwp = $lwps->duration;

      
      
     $this->db->select('*');
     $this->db->where('status', '1');
     $this->db->where('period', 'Morning Meeting');
     $this->db->group_by('date(scheduled_time)');
     $this->db->where('month(scheduled_time)', '2');
     $this->db->where('wp_id',$v_employee['staffid']);
     $apphalfq = $this->db->get('tblearlychekout');
     $apphalfmorn = $apphalfq->num_rows();
     
     $this->db->select('*');
     $this->db->where('status', '1');
     $this->db->where('period', 'Evening Meeting');
     $this->db->group_by('date(scheduled_time)');
     $this->db->where('month(scheduled_time)', '2');
     $this->db->where('wp_id',$v_employee['staffid']);
     $apphalfq = $this->db->get('tblearlychekout');
     $apphalfeven = $apphalfq->num_rows();
     
     $apphalf = $apphalfeven+$apphalfmorn;
     
     $apphalf = $totalsumofmornclr+$totalsumofevenclr;
     
     
     
     
     
     $totalactualhalfday = $totalhalf-$apphalf;
     $acthldcount1 = $totalactualhalfday;
     
     $totlhalfdaybymussday = $totalmissday;
     $acthldcount = $acthldcount1+$totlhalfdaybymussday;
     
     $acthldcount = ($sumofmornhalf+$sumofevenhalf+$sumofmornmissed+$sumofevenmissed);
     $totaldaylogin = $totalday-$acthldcount;
     
     $totalworking = $totalday+$totalsunday+$totalholidays+$paidlvs;
     $dayinmonth=cal_days_in_month(CAL_GREGORIAN,$month,$year);
     
     $totalab1 = $dayinmonth-$totalworking;
     $totalab = $totalab1-$totallwp;
     
     
     if($totalab < 0)
     {
        $totalab = 0; 
     }
     

     $jointDateArr = explode("-", $v_employee['datecreated']);
     $jointM = $jointDateArr[0].'-'.$jointDateArr[1];
      $preDate = 0;
      if($jointM == '2021-11'){
        /* $jointDateArr = explode(" ", $v_employee['datecreated']);
		
        $date1 = date_create(date('Y').'-'.date('m').'-'.'01');
		
        $date2 = date_create($jointDateArr[0]);
        $diff = date_diff($date1,$date2);
        $preDate = $diff->format("%a"); */
		
		$date1=date_create("2021-11-01");
		$date2=date_create($v_employee['datecreated']);
		$diff=date_diff($date1,$date2);
		$preDate = $diff->format("%a");
		
      }
	  
	  /* if($v_employee['staffid'] == 89){
		  $totallwp = $totallwp+2;
	  } */
	  
	  /* if($v_employee['staffid'] == 108){
		  $sumofmornmissed = 0;
		  $sumofmornhalf = 0;
		  $deductMissed = 0;
		  $totDeductDays = 0;
		  $totallwpabsent = 18;
		  $acthldcount = 0;
	  } */
	  
	 //echo $preDate." -- ".$v_employee['firstname'];

     //$totallwpabsent = $totallwp+$sumoftotalabsent+ $preDate ;
	 $totallwpabsent = $totallwp+$sumoftotalabsent + $preDate;
     
     $totaldayded = $totDeductDays+$totallwpabsent+($acthldcount/2);
     
     // if($v_employee['staffid'] == 106){
     //     $totallwpabsent = 20;
     // }
     // if($v_employee['staffid'] == 105){
     //     $totallwpabsent = 19;
     // }
     
     ?>
                              
     <!--<td> <?= $totaldaylogin+$totalsunday+$totalholidays+$paidlvs; ?> </td>-->
     <td><?=$SLleaves; ?></td>
     <td><?=$totalcl; ?></td>
     <td><?=$totalml; ?></td>
     <td><?=$totalel; ?> </td>
     <td><?=$totallwpabsent; ?> </td>
     
     <td><?= $sumofmornmissed+$sumofevenmissed; ?></td>
     <td><?= $sumofmornhalf; ?></td>
     <td><?= $sumofevenhalf; ?></td>
     <td><?= $apphalf; ?></td>
     <td><?= $acthldcount; ?></td>
     <td data-staffid="<?= $curentstafid; ?>" data-bioid="<?= $empbioidmonth;  ?>" data-monthdate="<?= $reportmonthdate; ?>" class="daydeduction"><?= $totaldayded; ?></td>
     <?php $this
        ->db
        ->select('net_salary');
    $this
        ->db
        ->where('salary_grade', $v_employee['staffid']);
    $empsald = $this
        ->db
        ->get('tbl_salary_template')
        ->row();
    $empsal = $empsald->net_salary;
	
    $perdaysal = number_format((float)($empsal / 30) , 2, '.', '');

	
?>
                         <td> <? // $totalded1 = ($totaldayded * $perdaysal);
                         $totalded1 = (($totallwpabsent+$totDeductDays+($deductMissed/2)) * $perdaysal);
    echo $totalded = number_format((float)($totalded1) , 2, '.', ''); ?> </td>
                         <td> <?=$monthsalemp = number_format((float)($empsal - $totalded) , 2, '.', ''); ?> </td>
                            </tr> 
       
                          <?php } 
                          
                             $sumofmornhalf = 0;
                             $sumofevenhalf = 0;
                             
                         
                         $sumofmornmissed = 0;
                         $sumofevenmissed = 0;
                         $sumoftotalabsent = 0;
                         $totalsumofmornclr = 0;
                         $totalsumofevenclr = 0;
                          ?>                  
                        </tbody>
                    </table>
                    
                </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>

<script>
    $(document).ready(function(){
  $("#adjustleave").click(function(){
    
      
      $('.daydeduction').each(function(){
    // element reference to your div, that you'll access inside your ajax call
    var actualdd = $(this).text();
    var staffid = $(this).data("staffid");
    var bioid = $(this).data("bioid");
    var monthdate = $(this).data("monthdate");
    var url = "<?= base_url('admin/payroll/adjustleavequota') ?>";
      if(actualdd > 0){

       
    
    $.ajax({
                    url: url,
                    
                    type: 'POST',
                    data: {actualdd: actualdd, staffid: staffid,bioid:bioid,monthdate:monthdate},
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                      
                        
                    }
                });
}

   
  });
  alert_float('warning', 'Leave adjustment in progress');
});

});
</script>

</body>
</html>
