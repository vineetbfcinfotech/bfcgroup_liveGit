<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>Leave Report Summary</h3></center>
						<!-- <a href="<?php echo site_url('/admin/reports/special_leave'); ?>" class="btn btn-info leave-btn" >Show Special Leave</a> -->
                        <hr class="hr-panel-heading">
                       <?=form_open('', array(
    'method' => 'GET'
)); ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="start" id="start_date" class="form-control"
                                           placeholder="Select Start Date.." value="<?=@$start; ?>" autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="end" id="end_date" class="form-control datepicker"
                                           value="<?=@$end; ?>"
                                           data-format="dd-mm-yyyy" data-parsley-id="17"
                                           placeholder="Select End Date.." autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                               <?=form_submit(['value' => "Filter", 'class' => 'btn btn-info']); ?>
                            </div>
                        </div>
                       <?=form_close(); ?>
                        <br>
                        
                        <div class="row">
                        <div class="col-md-12">
                            <hr class="hr-panel-heading"/>
                            <h4 class="no-margin text-center">-: Staff Wise Leave Report  Summary <?=$show_date; ?> :- </h4>
                            <hr class="hr-panel-heading"/>
                        </div>
                    </div>
                    
                    
                   <?php if (!empty($staffList))
                    {
                        foreach ($staffList as $staff)
                        { ?>
                            <div class="row leads-overview">
                              <h4 style="text-indent: 15px">
                                  <strong><?=ucfirst($staff['firstname']) . ' ' . ucfirst($staff['lastname']); ?></strong>
                              </h4>
                              <hr class="hr-panel-heading"/>
                              <div class="col-md-12">
                                <table class="table  scroll-responsive   no-footer" id="DataTables_Table_<?=@++$i; ?>" role="grid" aria-describedby="DataTables_Table_<?=@++$j; ?>_info">
                                   <tr>
                                       <tr><td><b>Category</b></td>
                                        <?php
                                        $staffLeadStatus = get_leave_summary_satffwise($staff['staffid'], @$start, @$end);
                                        if (!empty($staffLeadStatus))
                                        {
                                            foreach ($staffLeadStatus as $status)
                                            { ?>
                                               <td><b><span style="color:<?php echo $status['color']; ?>" class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>"><?php echo $status['leave_category']; ?></span></b> </td>
                                        <?php
                                            }
                                            echo "<td> <b>Special Leave </b></td>";
                                        } ?>
                                   </tr>
                                   <tr>
                                       <td><b>Quota</b></td>
                                       <?php
                                        $staffLeadStatus = get_leave_summary_satffwise($staff['staffid'], @$start, @$end);
                                        if (!empty($staffLeadStatus))
                                        {
                                            foreach ($staffLeadStatus as $status)
                                            {  
                $leave_id = $status['leave_category_id'];
                $confirmationDate = get_confirmation_date_satffwise($staff['staffid']);
                $confDate = $confirmationDate->confirmation_date;
                if(($start<$confDate) && ($end>$confDate)){
                    $start_date = $confDate;
                    $date=1;
                }

                if(($start<$confDate) && ($end<$confDate)){
                   $start_date = $start; //null
                   $date=0;
                }

                if(($start>$confDate) && ($end>$confDate)){
                    $start_date = $start;
                    $date=1;
                }

                $leave_start = DateTime::createFromFormat('Y-m-d',$start_date);
                $leave_end = DateTime::createFromFormat('Y-m-d',$end);
                $diffMonths = $leave_end->diff($leave_start)->format("%m")+1;
                $leave_quota = $status['leave_quota'];
                //$total = $status['total'];
                $this->db->select_sum('duration');
                $this->db->where('user_id',$staff['staffid']);
                $this->db->where('leave_category_id',$leave_id);
                $this->db->where('application_status',2);
                $this->db->where('leave_start_date >=', $start_date);
                $this->db->where('leave_start_date <=', $end);
                $totalData = $this->db->get('tblleaveapplication')->result();
              
                $q = 12/$leave_quota;
                $quota = $diffMonths/$q;

                if($date==1){
                    $quota =  $quota;
                    //$total = $total;
                }else{
                    $quota = 0;
                    //$total = 0;
                }

                if($totalData[0]->duration>0){ 
                    $total = $totalData[0]->duration;
                    $color = 'style="color:#FF0000"';
                }else{ 
                    $total = 0;
                    $color = '';
                } 

                if($leave_id==11){
                    $quota =  $leave_quota;
                    echo "<td><span ".$color.">".$total."</span></td>";
                }else{
                    echo "<td><span ".$color.">".$total."/".$quota."</span></td>";
                }

                

                //echo $quota;
                ?>
                                            <!-- <td>0/<?= $quota; ?></td> -->
                                        <?php
                                            }
            $getAppliedSpecialLeave = getAppliedSpecialLeave($staff['staffid']);
            $appliedLeave = 0;
            if ($getAppliedSpecialLeave->duration != "")
            {
                $appliedLeave = $getAppliedSpecialLeave->duration;
            }

            $getTotalSpecialLeave = totalSpecialLeave($staff['staffid']);
            $totalSpecialLeaveData = 0;
            if ($getTotalSpecialLeave->quota != "")
            {
                $totalSpecialLeaveData = $getTotalSpecialLeave->quota;
            }
            echo "<td>";
            echo $appliedLeave . " / " . $totalSpecialLeaveData;
            echo "</td>";
                                        } ?>
                                   </tr>
                                </table>
                              </div>
                            </div>
                          <hr class="hr-panel-heading"/>
                      <?php
                        }
                    } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    
</script>
</body>
</html>