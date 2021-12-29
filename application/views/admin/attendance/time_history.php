<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
		<button class="btn btn-success" data-toggle="modal" data-target="#attendanceModal" >Upload Attendance</button>
     <input type="button" class="btn btn-success" onclick="printDiv('printableArea')" value="Attendance Print" />
        <hr class="hr-panel-heading">		
                        <?=form_open('', array(
    'method' => 'POST'
)); ?>
 <div class="row">
                            <div class="col-md-3">
							<?php //print_r($selectedStaff);exit;
 ?>
                                <select id="staff_name" name="staff_name[]"  multiple data-none-selected-text="Filter By Staff" data-live-search="true" class="selectpicker custom_lead_filter" >
                                   
                                  <?php if (!empty($staffs))

{
    foreach ($staffs as $get_comp)
    { ?>
                                         <option <?php if (in_array($get_comp->staffid, $selectedStaff))
        {
            echo 'selected';
        } ?> value="<?=$get_comp->staffid; ?>"><?=$get_comp->staffname; ?></option>
                                     <?php
    }
} ?>
                               </select>
                                
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="start" id="start_date" class="form-control"
                                           placeholder="Select Start Date.." value="<?=$start; ?>" autocomplete="off" readonly >
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="end" id="end_date" class="form-control datepicker"
                                           value="<?=$end; ?>"
                                           data-format="dd-mm-yyyy" data-parsley-id="17"
                                           placeholder="Select End Date.." autocomplete="off" readonly >
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <?=form_submit(['value' => "Filter", 'name' => "filter", 'class' => 'btn btn-info']); ?>
                            </div>
                        </div>
                        <?=form_close(); ?>
                        <br>
                        <hr class="hr-panel-heading">
        <div class="row">
              <div class="col-sm-12" id="printableArea">
                    
                    <table class="table table-striped">
                        <tr>
                            <td><strong> Employee \ Date</strong></td>
                             <?php foreach($list as $dates) { ?>
                                <td><strong><?php echo $dates; ?></strong></td>
                            <?php }?>     
                            
                        </tr>
                         <?php 
                         $sumofmornhalf = 0;
                         $sumofevenhalf = 0;
                         /* echo "<pre>";
                         print_r($staff_members);exit; */
                            foreach ($staff_members as $v_employee) 
                            { ?>
                            <tr>  
                              <td> <strong><?php echo $v_employee['firstname']. ' '. $v_employee['lastname'];   ?></strong></td>
                              <?php foreach($list as $dates) { ?>
                                        <td>
                                             <?php   $currentdate = $dates;
                                             ?>
        
                                           
                                           <?php
                                            $curremtuserid = $v_employee['bio_id'];
                                            if( $curremtuserid == "" ){
                                                $curremtuserid = "12345";
                                            }
                                            $curentstafid = $v_employee['staffid'];
                                            /* $this->db->select('LogDate');
                                            $this->db->where('date(LogDate)', $currentdate);
                                            $this->db->where('UserId', $curremtuserid); */
                                            // $this->db->or_where('staffId', $curentstafid);
                                            $this->db->select('`LogDate` FROM `deviceLogs_2_2020` WHERE date(LogDate) = "'.$currentdate.'" AND (`UserId` = "'.$curremtuserid.'" OR `staffId` = "'.$curentstafid.'")', FALSE);
                                            
                                            //$this->db->select('(SELECT SUM(payments.amount) FROM payments WHERE payments.invoice_id=4) AS amount_paid', FALSE);
                                            
                                            $this->db->order_by("time(LogDate)", "asc");
                                            $this->db->limit("1"); 
                                           
                                           $res= $this->db->get()->row();
                                           
                                           //echo $this->db->last_query();exit;
                                           
                                           $currentattendance_id = $res->LogDate;
                                            $time1 = date("H:i",strtotime($currentattendance_id));
                                        
                                            /* $this->db->select('LogDate'); 
                                           $this->db->where('date(LogDate)', $currentdate);
                                           $this->db->where('UserId', $curremtuserid);
                                           $this->db->or_where('staffId', $curentstafid); */
                                           $this->db->select('`LogDate` FROM `deviceLogs_2_2020` WHERE date(LogDate) = "'.$currentdate.'" AND (`UserId` = "'.$curremtuserid.'" OR `staffId` = "'.$curentstafid.'")', FALSE);
                                        $this->db->order_by("time(LogDate)", "desc");
                                        //$this->db->order_by("DeviceLogId", "desc");
                                        $this->db->limit("1");
                                           
                                           $res1= $this->db->get()->row();
                                           //echo $this->db->last_query();exit;
                                           $currentattendance_id1 = $res1->LogDate;
                                           $time2 = date("H:i",strtotime($currentattendance_id1));
                                           
                                           $this->db->select('*');
                                            $this->db->from('tbl_poss');
                                            $this->db->where('user_id',$curentstafid);
                                            $this->db->where('date',$currentdate);
                                            $pauseData = $this->db->get()->row();
                                            //echo $this->db->last_query();exit;
                                            if($pauseData != ""){
                                            echo "<span style='color:#FFCC00;'>Pause ".substr($pauseData->start_time, 0, -10)."--".substr($pauseData->stop_time, 0, -10)."</span> &nbsp;";
                                            }
                                                    
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
                                                                  $time1 = "<spna style='color:#FF0000'><center>$time1 - L</center></span><spna style='color:#4b454c'></span>";
                                                                 $sumofmornhalf+= 1;
                                                             }

                                                             $this->db->select('tblleavecategory.leave_category as leavename, tblleaveapplication.leave_type as leave_type ');
                                                            $this->db->where('date(leave_start_date)', $currentdate);
                                                            $this->db->where('half_shift', 'Evening');
                                                            $this->db->where('user_id', $curentstafid);
                                                            $this->db->where('application_status', '2');
                                                            $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                            $leaveexmorn = $this->db->get('tblleaveapplication')->row();

                                                            //echo $this->db->last_query();
                                                            $leavemorn = $leaveexmorn->leavename;

                                                            if(count($leaveexmorn)> 0)
                                                            {
                                                                echo "Login $time1 <spna style='color:#FF0000'><center>$leavemorn.-HD</center></span><spna style='color:#4b454c'></span>";
                                                                
                                                            }else{


                                                              $this->db->select('tblleavecategory.leave_category as leavename, tblleaveapplication.leave_type as leave_type ');
                                                            $this->db->where('date(leave_start_date)', $currentdate);
                                                            $this->db->where('leave_type', 'single_day');
                                                            $this->db->where('user_id', $curentstafid);
                                                            $this->db->where('application_status', '2');
                                                            $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                            $leaveexmorn = $this->db->get('tblleaveapplication')->row();
                                                              $leavemorn = $leaveexmorn->leavename;
                                                              if(count($leaveexmorn)> 0)
                                                              {
                                                                  echo "<spna style='color:#FF0000'><center>$leavemorn</center></span><spna style='color:#4b454c'></span>";
                                                                  
                                                              }else{

                                                                echo  "Login $time1  Logout -- --  "; 
                                                              }
                                                            }
                                                       }
                                                       else
                                                       {


                                                           $sumofmornmissed+= 1;
                                                                if(strtotime($minlogouttime) > strtotime($time1))
                                                             {
                                                                 $time1 = "<spna style='color:#FF0000'><center>$time1 - E</center></span><spna style='color:#4b454c'></span>";
                                                                 $sumofevenhalf+= 1;
                                                             }

                                                            
                                                          echo  "Login -- --   Logout $time1";
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
                                                    //print_r($leaveexeven);
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
                                                            $time2 = "<spna style='color:#FF0000'><center>CLR</center></span>";
                                                            
                                                        }
                                                    }
                                                    
                                                    
                                                    
                                                     if(strtotime($emptime1) == strtotime($time1))
                                                     {
                                                         if(strtotime($emptime1) > strtotime($maxlogintime))
                                                         {
                                                             $time1 = "<spna style='color:#FF0000'><center>$emptime1 - L</center></span><spna style='color:#4b454c'></span>";
                                                          
                                                         }
                                                     }
                                                     
                                                     if(strtotime($emptime2) == strtotime($time2))
                                                     {
                                                         if(strtotime($emptime2) < strtotime($minlogouttime))
                                                         {
                                                             $time2 = "<spna style='color:#FF0000'><center>$emptime2 - E</center></span><spna style='color:#4b454c'></span>";
                                                             $sumofevenhalf+= 1;
                                                             
                                                         }
                                                     }




                                                            $this->db->where('leave_category_id', 0);
                                                            $this->db->where('date(leave_start_date)', $currentdate);
                                                            $this->db->where('half_shift', 'Morning');
                                                            $this->db->where('user_id', $curentstafid);
                                                            $this->db->where('application_status', '2');
                                                            $leaveexmorn = $this->db->get('tblleaveapplication')->row();

                                                            //echo $this->db->last_query();
                                                            $leavemorn = $leaveexmorn->leavename;
                                                            if(count($leaveexmorn)> 0)
                                                            {
                                                                echo "Login <spna style='color:#FF0000'><center>SL.-HD</center></span><spna style='color:#4b454c'> Logout $time2  </span>";
                                                                
                                                            }else{

                                                               $this->db->select('tblleavecategory.leave_category as leavename, tblleaveapplication.leave_type as leave_type ');
                                                              $this->db->where('date(leave_start_date)', $currentdate);
                                                              $this->db->where('user_id', $curentstafid);
                                                              $this->db->where('half_shift', '');
                                                              $this->db->where('application_status', '2');
                                                              $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                              $leaveexmorn = $this->db->get('tblleaveapplication')->row();
                                                               //echo $this->db->last_query();
                                                              //print_r($leaveexmorn);
                                                              $leaveeven = $leaveexmorn->leavename;
                                                              if($leaveexmorn!=''){ 
                                                                 echo "<spna style='color:#FF0000'><center>$leaveeven</center></span></span>";
                                                              }else{
                                                                 echo "Login $time1  Logout $time2 ";
                                                              }
                                                              
                                                               // echo "Login $time1  Logout $time2 ";
                                                            }

                                                     
                                                       // echo "Login $time1  Logout $time2"; 
                                                       

                                                   
                                                   
                                               }
                                           }
                                           else
                                           {
                                               
                                             $currentday =  date('D', strtotime($currentdate));
                                            if( $currentday == "Sun")
                                            {
                                                $this->db->select('tblleavecategory.leave_category as leavename');
                                                $this->db->where('date(leave_start_date) <=', $currentdate);
                                                $this->db->where('date(leave_end_date) >=', $currentdate);
                                                $this->db->where('user_id', $curentstafid);
                                                $this->db->where('application_status', '2');
                                                $this->db->where('sandwitch_avail', null);
                                                $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                $leaveex = $this->db->get('tblleaveapplication')->row();
                                                $leave = $leaveex->leavename;
												// echo $leave;exit;
                                                if(count($leaveex)>0){
                                                  echo "  <spna style='color:#FF0000'><center>$leave</center></span> ";
                                                } 
                                                else {
                                                     echo  "<spna style='color:#FF0000'><center>$currentday</center></span>";
                                                }

                                                
                                            }
                                            else
                                            {
                                                /* $this->db->select('name');
                                                $this->db->where('quota', $currentdate);
												$this->db->or_where('leave_end_date', $currentdate);
                                                $holithisday = $this->db->get('tblholidays')->row(); */
												
												$query = $this->db->query("SELECT `name` FROM `tblholidays` WHERE `quota` = '".$currentdate."' OR `leave_end_date` = '".$currentdate."' OR (`quota` < '".$currentdate."' AND`leave_end_date` > '".$currentdate."')");
												
												$holithisday = $query->row();
												
												//echo $this->db->last_query();
                                                if($holithisday->name != null)
                                                {
                                                 $this->db->select('tblleavecategory.leave_category as leavename');
                                                  $this->db->where('date(leave_start_date) <=', $currentdate);
                                                  $this->db->where('date(leave_end_date) >=', $currentdate);
                                                  $this->db->where('user_id', $curentstafid);
                                                  $this->db->where('application_status', '2');
                                                  $this->db->where('sandwitch_avail', null);
                                                  $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                  $leaveex = $this->db->get('tblleaveapplication')->row();
                                                  $leave = $leaveex->leavename;

                                                  if(count($leaveex)>0){
                                                    echo "  <spna style='color:#FF0000'><center>$leave</center></span> ";
                                                  } 
                                                  else {
                                                     echo  "<spna style='color:#FF0000'><center>$holithisday->name  </center></span>";

                                                  }


                                                  // echo  "<spna style='color:#FF0000'><center>$holithisday->name </center></span>";
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

                                                      $this->db->select('tblleavecategory.leave_category as leavename, tblleaveapplication.leave_type as leave_type ');
                                                      $this->db->where('date(leave_start_date)', $currentdate);
                                                      $this->db->where('user_id', $curentstafid);
                                                      $this->db->where('application_status', '2');
                                                       $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id=tblleavecategory.leave_category_id');
                                                      $leaveexmorn = $this->db->get('tblleaveapplication')->result();
                                                      
                                                      //print_r($leaveexmorn);
                                                      //echo count($leaveexmorn);

                                                      $firt_half = $leaveexmorn[0]->leavename;
                                                      $second_half = $leaveexmorn[1]->leavename;
                                                     
                                                      if(count($leaveexmorn)==2)
                                                      {
                                                        echo "<spna style='color:#FF0000'><center>$firt_half .-HD</center></span><spna style='color:#FF0000'> $second_half .-HD</span>";
                                                                
                                                      }else if(count($leaveexmorn)==1){
                                                         echo "  <spna style='color:#FF0000'><center>$firt_half </center></span> ";
                                                      } 
                                                       else {
                                                        echo "  <spna style='color:#FF0000'><center>$leave </center></span> ";

                                                      }

                                                    }
                                                    else
                                                    {
                                                /* $this->db->select("*");
                                                $this->db->form("tblleaveapplication");
                                                $this->db->where("leave_start_date", $currentdate);
                                                $this->db->where("leave_category_id", 0);
                                                $spectaialData = $this->db->get();
												print_r($spectaialData->result()); */
												
                                                echo "  <spna style='color:#FF0000'><center>A</center></span> ";
                                                }
                                                }
                                            }
                                           }
        
        
        
                                           ?>
        
        
        
        
        
                                           </td>
                             <?php }  $sumofmornhalf; 
                              $sumofevenhalf;
                             
                             $sumofmornhalf = 0;
                             $sumofevenhalf = 0; ?> 
                            </tr>
                            
                         <?php } ?>
                                            

                    </table>
                    
                </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="attendanceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload CSV for Attendance</h4>
      </div>
      <div class="modal-body">
        <form id="attendance-form">
		  <div class="form-group">
			<label for="email">CSV File:</label>
			<input type="file" class="form-control" id="file" name="file">
		  </div>
		  <button type="submit" class="btn btn-default" >Submit</button>
		</form>
      </div>
    </div>

  </div>
</div>
<?php init_tail(); ?>
<script>
$(document).ready(function(){
	$('#attendance-form').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: "<?php echo admin_url(); ?>attendance/import_attendance",
			method: "POST",
			data: new FormData(this),
			processData:false,
			contentType:false,
			cache:false,
			async:false,
			success:function(data)
			{	
				if(data == 1){
					$('#attendance-form').modal('hide');
					alert_float("success", "Attendance Imported Successfully!");
					window.setTimeout(function(){location.reload()},2000);
				}
			}
		});
	});	
});
</script>
<script type="text/javascript">
  function printDiv(divName) {
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
  }
</script>
</body>
</html>
