<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <hr class="hr-panel-heading">
                        
                        <div class="row">
                            <center><h3>Employee Monthly Salary Report</h3></center>
                        </div>
                        <br>
                        <hr class="hr-panel-heading">
                        <?php
                        
                        //$date = "2020-09-11";
                        //$reportmonthdate = "2020-09-11";
						$date = date("Y-m-d", strtotime("first day of previous month"));
						$reportmonthdate = date("Y-m-d", strtotime("last day of previous month"));
						
                          $timestamp = strtotime($date);
                          $day = date("d", $timestamp);
                          $month = date("m", $timestamp);
                          if(preg_match("~^0\d+$~", $month))
                          {
                              $month = str_replace("0", "", $month);
                          }
                          $year = date("Y", $timestamp );
                          
                          //$table = "deviceLogs_".$month."_".$year;
                          $table = "deviceLogs_"."2"."_".$year;
                        
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
                            
                              $num_sundays='';                
                              for ($i = 0; $i < ((strtotime($todt) - strtotime($fromdt)) / 86400); $i++)
                              {
                                if(date('l',strtotime($fromdt) + ($i * 86400)) == 'Sunday')
                                {
                                        $num_sundays++;
                                }    
                              }
                              $totalsunday = $num_sundays;
                             
                             $totalsundaysonholidays = $singleholisunday+$multiholisunday;
                             
                             $totalsunday = $totalsunday-$totalsundaysonholidays;
                           
                            $this->db->select_sum('days');
                            $this->db->where('month(quota)', $month);
                            $holithisday = $this->db->get('tblholidays')->row();
							
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
                    
                    <table id="testTable" class="table table-striped">
                        <tr>
                            <td><strong> Employee \ Date</strong></td>
                             
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
                                <td><strong><?="Total Dedcutions"
?></strong></td>
                                <td><strong><?="Net Salary This Month"
?></strong></td>
                                
                            
                        </tr>
                         <?php
                         //$date = "2020-09-11";
						 $date = date("Y-m-d", strtotime("first day of previous month"));
                          $timestamp = strtotime($date);
                          $day = date("d", $timestamp);
                          $month = date("m", $timestamp);
                          if(preg_match("~^0\d+$~", $month))
                          {
                              $month = str_replace("0", "", $month);
                          }
                          $year = date("Y", $timestamp );
                          
                          
                          //$table = "deviceLogs_".$month."_".$year;
                          $table = "deviceLogs_"."2"."_".$year;
                          
                         
                  
foreach ($staff_members as $v_employee)
{ 
	$staff_id = $v_employee['staffid'];
	$bio_id = $v_employee['bio_id'];

	$totalday = $this->db->query('SELECT  count(UserId) as cnt FROM deviceLogs_2_2020 WHERE year(LogDate) = 2020 AND (UserId = "'.$bio_id.'" OR staffId = "'.$staff_id.'") GROUP BY `UserId`, date(LogDate) HAVING cnt = 1');

	$missedLogs = $totalday->result();
	$totalMissedLog = 0;
	foreach($missedLogs as $missedLog){
		$totalMissedLog = $totalMissedLog+$missedLog->cnt;
	}
	
	/* $totalDelayTime = array();
	$totalDelay = $this->db->query('SELECT  LogDate FROM deviceLogs_2_2020 WHERE time(LogDate) < "09:46:00" AND (UserId = "'.$bio_id.'" OR staffId = "'.$staff_id.'")');
	$totalDelayTime = $totalDelay->result();
	echo $this->db->last_query();exit; */
?>

                            <tr>  
                              <td> <strong><?php print_r($totalDelayTime); echo $v_employee['firstname'] . ' ' . $v_employee['lastname']; ?></strong></td>
                              
                              
                                <?php 
                                $sumofmornhalf = 0;
                         $sumofevenhalf = 0;
                         
                         
                         $sumofmornmissed = 0;
                         $sumofevenmissed = 0;
                         $sumoftotalabsent = 0;
                         $totalsumofmornclr = 0;
                         $totalsumofevenclr = 0;
                                foreach($list as $dates) { ?>
                                    
                                             <?php   $currentdate = $dates;
                                             ?>
        
                                           
                                           <?php
                                            $curremtuserid = $v_employee['bio_id'];
                                            $curentstafid = $v_employee['staffid'];
                                           $this->db->select('LogDate');
                                           $this->db->where('date(LogDate)', $currentdate);
                                           $this->db->where('UserId', $curremtuserid);
                                       $this->db->order_by("time(LogDate)", "asc");
                                        $this->db->limit("1");
                                           
                                           $res= $this->db->get('deviceLogs_2_2020')->row();
                                           $currentattendance_id = $res->LogDate;
                                            $time1 = date("H:i",strtotime($currentattendance_id));
                                            
                                             $this->db->select('LogDate');
                                           $this->db->where('date(LogDate)', $currentdate);
                                           $this->db->where('UserId', $curremtuserid);
                                        $this->db->order_by("time(LogDate)", "desc");
                                        $this->db->limit("1");
                                           
                                           $res1= $this->db->get('deviceLogs_2_2020')->row();
                                           $currentattendance_id1 = $res1->LogDate;
                                            $time2 = date("H:i",strtotime($currentattendance_id1));
                                           if ($currentattendance_id) 
                                           {
                                                   $emptime1 = $time1;
                                                   $emptime2 = $time2;
                                                   
                                                   $maxlogintime = "09:45:00";
                                                   $minlogouttime = "18:30:00";
                                                   
                                                   $halfdaylogintime = "14:00:00";
                                               
                                               if($time1 == $time2)
                                               {
                                                   
                                              
                                                       if(strtotime($halfdaylogintime) > strtotime($time1))
                                                       {
                                                           
                                                             
                                                             $sumofevenmissed+= 1;
                                                             
                                                             if(strtotime($maxlogintime) < strtotime($time1))
                                                             {
                                                                  $time1 = "<spna style='color:#FF0000'><center>$time1 - Late</center></span><spna style='color:#4b454c'></span>";
                                                                 $sumofmornhalf+= 1;
                                                             }
                                                             
                                                             "Login $time1  Logout -- --";
                                                             
                                                             
                                                             
                                                             
                                                       }
                                                       else
                                                       {
                                                           
                                                              $sumofmornmissed+= 1;
                                                                if(strtotime($minlogouttime) > strtotime($time1))
                                                             {
                                                                 $time1 = "<spna style='color:#FF0000'><center>$time1 - Early</center></span><spna style='color:#4b454c'></span>";
                                                                 $sumofevenhalf+= 1;
                                                             }
                                                             
                                                             
                                                            "Login -- --   Logout $time1";
                                                       }
                                               
                                               }
                                               else
                                               {
                                                   
                                                   
                                                   
                                                   
                                                    $this->db->select('tblleavecategory.leave_category as leavename, tblleaveapplication.leave_type as leave_type ');
                                                    $this->db->where('date(leave_start_date)', $currentdate);
                                                    $this->db->where('half_shift', 'Morning');
                                                    $this->db->where('user_id', $curentstafid);
                                                    $this->db->where('application_status', '2');
                                                    $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                    $leaveexmorn = $this->db->get('tblleaveapplication')->row();
                                                    $leavemorn = $leaveexmorn->leavename;
                                                    if(count($leaveexmorn)> 0)
                                                    {
                                                        $time1 = "<spna style='color:#FF0000'><center>$leavemorn.-HD</center></span><spna style='color:#4b454c'></span>";
                                                        
                                                    }
                                                    else
                                                    {
                                                         $this->db->select('*');
                                                         $this->db->where('status', '1');
                                                         $this->db->where('period', 'Morning Meeting');
                                                         $this->db->group_by('date(scheduled_time)');
                                                         $this->db->where('date(scheduled_time)', $currentdate);
                                                         $this->db->where('wp_id',$v_employee['staffid']);
                                                         $apphalfmorn = $this->db->get('tblearlychekout')->row();
                                                        if(count($apphalfmorn)> 0)
                                                        {
                                                            $totalsumofmornclr+= 1;
                                                            $time1 = "<spna style='color:#FF0000'><center>CLR</center></span><spna style='color:#4b454c'></span>";
                                                            
                                                        }
                                                        
                                                        
                                                        
                                                    }
                                                    
                                                    $this->db->select('tblleavecategory.leave_category as leavename, tblleaveapplication.leave_type as leave_type ');
                                                    $this->db->where('date(leave_start_date)', $currentdate);
                                                    $this->db->where('half_shift', 'Evening');
                                                    $this->db->where('user_id', $curentstafid);
                                                    $this->db->where('application_status', '2');
                                                    $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                    $leaveexeven = $this->db->get('tblleaveapplication')->row();
                                                    $leaveeven = $leaveexeven->leavename;
                                                    if(count($leaveexeven)> 0)
                                                    {
                                                        
                                                        $time2 = "<spna style='color:#FF0000'><center>$leaveeven.-HD</center></span>";
                                                        
                                                    }
                                                    else
                                                    {
                                                         $this->db->select('*');
                                                         $this->db->where('status', '1');
                                                         $this->db->where('period', 'Evening Meeting');
                                                         $this->db->group_by('date(scheduled_time)');
                                                         $this->db->where('date(scheduled_time)', $currentdate);
                                                         $this->db->where('wp_id',$v_employee['staffid']);
                                                         $apphalfeven = $this->db->get('tblearlychekout')->row();
                                                        if(count($apphalfeven)> 0)
                                                        {
                                                            $totalsumofevenclr+= 1;
                                                            $time2 = "<spna style='color:#FF0000'><center>CLR</center></span>";
                                                            
                                                        }
                                                    }
                                                    
                                                    
                                                    
                                                     if(strtotime($emptime1) == strtotime($time1))
                                                     {
                                                         if(strtotime($emptime1) > strtotime($maxlogintime))
                                                         {
                                                             $time1 = "<spna style='color:#FF0000'><center>$emptime1 - Late</center></span><spna style='color:#4b454c'></span>";
                                                            $sumofmornhalf+= 1;
                                                         }
                                                     }
                                                     
                                                     if(strtotime($emptime2) == strtotime($time2))
                                                     {
                                                         if(strtotime($emptime2) < strtotime($minlogouttime))
                                                         {
                                                             $time2 = "<spna style='color:#FF0000'><center>$emptime2 - Early</center></span><spna style='color:#4b454c'></span>";
                                                             $sumofevenhalf+= 1;
                                                             
                                                         }
                                                     }
                                                     
                                                        "Login $time1  Logout $time2"; 
                                                       

                                                   
                                                   
                                               }
                                           }
                                           else
                                           {
                                               
                                             $currentday =  date('D', strtotime($currentdate));
                                            if( $currentday == "Sun")
                                            {
                                                  "<spna style='color:#FF0000'><center>$currentday</center></span>";
                                            }
                                            else
                                            {
                                                $this->db->select('name');
                                                $this->db->where('quota', $currentdate);
                                                $holithisday = $this->db->get('tblholidays')->row();
                                                if($holithisday->name != null)
                                                {
                                                    
                                                     "<spna style='color:#FF0000'><center>$holithisday->name</center></span>";
                                                }
                                                else
                                                {
                                                    $this->db->select('tblleavecategory.leave_category as leavename, tblleaveapplication.leave_type as leave_type ');
                                                    $this->db->where('date(leave_start_date)', $currentdate);
                                                    $this->db->where('user_id', $curentstafid);
                                                    $this->db->where('application_status', '2');
                                                    $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                    $leaveex = $this->db->get('tblleaveapplication')->row();
                                                    if($leaveex->leave_type != 'single_day')
                                                    {
                                                        $this->db->select('tblleavecategory.leave_category as leavename, tblleaveapplication.leave_type as leave_type ');
                                                    
                                                                            $this->db->where('date(leave_start_date) <=', $currentdate);
                                                                            $this->db->where('date(leave_end_date) >=', $currentdate);
                                                    $this->db->where('user_id', $curentstafid);
                                                    $this->db->where('application_status', '2');
                                                    $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                    $leaveex = $this->db->get('tblleaveapplication')->row();
                                                    }
                                                   
                                                    
                                                                if($leaveex->leavename != null)
                                                                {
                                                                             if($leaveex->leave_type == 'single_day')
                                                                        {
                                                                            $leave = $leaveex->leavename;
                                                                        }
                                                                        else
                                                                        {
                                                                            $this->db->select('tblleavecategory.leave_category as leavename');
                                                                            $this->db->where('date(leave_start_date) <=', $currentdate);
                                                                            $this->db->where('date(leave_end_date) >=', $currentdate);
                                                                            $this->db->where('user_id', $curentstafid);
                                                                            $this->db->where('application_status', '2');
                                                                            $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                                            $leaveex = $this->db->get('tblleaveapplication')->row();
                                                                             $leave = $leaveex->leavename;
                                                                        }
                                                       "  <spna style='color:#FF0000'><center>$leave</center></span> ";  
                                                    }
                                                    else
                                                    {
                                                        $sumoftotalabsent+= 1;
                                                
                                                 "  <spna style='color:#FF0000'><center>A</center></span> ";
                                                }
                                                }
                                            }
                                           }
        
        
        
                                           ?>
        
                             <?php } 
                             
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
                                 $datemiss[] = $totallogsmonthl->LogDate;
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
                              $this->db->where('application_status', '2');
                              $this->db->where('leave_category_id !=', '11');
                              $paidleaves = $this->db->get('tblleaveapplication')->row();
                              $paidlvs = $paidleaves->duration;
                              
                              
                              $this->db->select_sum('duration');
                              $this->db->where('user_id',$v_employee['staffid']);
                              $this->db->where('month(leave_start_date)', $month);
                              $this->db->where('application_status', '2');
                              $this->db->where('leave_category_id', '1');
                              $cls = $this->db->get('tblleaveapplication')->row();
                              $totalcl = $cls->duration;
                              
                              
                              $this->db->select_sum('duration');
                              $this->db->where('user_id',$v_employee['staffid']);
                              $this->db->where('month(leave_start_date)', $month);
                              $this->db->where('application_status', '2');
                              $this->db->where('leave_category_id', '7');
                              $els = $this->db->get('tblleaveapplication')->row();
                              $totalel = $els->duration;
                              
                              $this->db->select_sum('duration');
                              $this->db->where('user_id',$v_employee['staffid']);
                              $this->db->where('month(leave_start_date)', $month);
                              $this->db->where('application_status', '2');
                              $this->db->where('leave_category_id', '3');
                              $mls = $this->db->get('tblleaveapplication')->row();
                              $totalml = $mls->duration;
                              
                              $this->db->select_sum('duration');
                              $this->db->where('user_id',$v_employee['staffid']);
                              $this->db->where('month(leave_start_date)', $month);
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
                             
                             $totallwpabsent = $totallwp+$sumoftotalabsent;
                             
                             
                             $totaldayded = $totallwpabsent+($acthldcount/2);
                             
                             ?>
                              
                         <!--<td> <?= $totaldaylogin+$totalsunday+$totalholidays+$paidlvs; ?> </td>-->
                         <td><?=$SLleaves; ?></td>
                         <td><?=$totalcl; ?></td>
                         <td><?=$totalml; ?></td>
                         <td><?=$totalel; ?></td>
                         <td><?=$totallwpabsent; ?></td>
                         
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
                         <td> <? $totalded1 = ($totaldayded * $perdaysal);
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
