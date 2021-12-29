<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <hr class="hr-panel-heading">
                        <?= form_open('', array('method' => 'GET')); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <select id="staff_name" name="staff_name"  multiple data-none-selected-text="Filter By Staff" data-live-search="true" class="selectpicker custom_lead_filter" >
                                   
                                  <?php print_r($staffs); if ( !empty($staffs) ) {
                                     foreach ($staffs as $get_comp) { ?>
                                         <option value="<?= $get_comp->staffid; ?>"><?= $get_comp->staffname; ?></option>
                                     <?php }
                                  } ?>
                               </select>
                                
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="start" id="start_date" class="form-control"
                                           placeholder="Select Start Date.." value="<?= @$start; ?>" autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="end" id="end_date" class="form-control datepicker"
                                           value="<?= @$end; ?>"
                                           data-format="dd-mm-yyyy" data-parsley-id="17"
                                           placeholder="Select End Date.." autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <?= form_submit(['value' => "Filter", 'class' => 'btn btn-info']); ?>
                            </div>
                        </div>
                        <?= form_close(); ?>
                        <br>
                        <hr class="hr-panel-heading">
        <div class="row">
              <div class="col-sm-12" >
                    
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
                                             <?php   $currentdate = $dates; ?>
        
                                           
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
                                                             
                                                           echo  "Login $time1  Logout -- --";
                                                             
                                                             
                                                             
                                                             
                                                       }
                                                       else
                                                       {
														   $sumofmornmissed+= 1;
                                                                if(strtotime($minlogouttime) > strtotime($time1))
                                                             {
                                                                 $time1 = "<spna style='color:#FF0000'><center>$time1 - Early</center></span><spna style='color:#4b454c'></span>";
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
                                                             $time1 = "<spna style='color:#FF0000'><center>$emptime1 - Late</center></span><spna style='color:#4b454c'></span>";
                                                          
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
                                                     
                                                       echo "Login $time1  Logout $time2"; 
                                                       

                                                   
                                                   
                                               }
                                           }
                                           else
                                           {
                                               
                                             $currentday =  date('D', strtotime($currentdate));
                                            if( $currentday == "Sun")
                                            {
                                                echo  "<spna style='color:#FF0000'><center>$currentday</center></span>";
                                            }
                                            else
                                            {
                                                $this->db->select('name');
                                                $this->db->where('quota', $currentdate);
                                                $holithisday = $this->db->get('tblholidays')->row();
                                                if($holithisday->name != null)
                                                {
                                                    
                                                   echo  "<spna style='color:#FF0000'><center>$holithisday->name</center></span>";
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
                                                      echo "  <spna style='color:#FF0000'><center>$leave</center></span> ";  
                                                    }
                                                    else
                                                    {
                                                
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
<?php init_tail(); ?>
<script>
</script>
</body>
</html>
