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
    //echo "<pre>"; print_r($staffList);exit;
    foreach ($staffList as $staff)
    { ?>
                          <div class="row leads-overview">
                              <h4 style="text-indent: 15px">
                                  <strong><?=ucfirst($staff['firstname']) . ' ' . ucfirst($staff['lastname']); ?></strong>
                              </h4>
                              
                              <hr class="hr-panel-heading"/>
                              <div class="col-md-12">
                              
                           <table class="table  scroll-responsive   no-footer" id="DataTables_Table_<?=@++$i; ?>" role="grid" aria-describedby="DataTables_Table_<?=@++$j; ?>_info">
                               
                                  <tr><td><b>Category</b></td>   
                                  <?php
        $staffLeadStatus = get_leave_summary_satffwise($staff['staffid'], @$start, @$end);
        //echo "<pre>";
        //print_r($staffLeadStatus);exit;
        if (!empty($staffLeadStatus))
        {
            foreach ($staffLeadStatus as $status)
            { ?>
                                       <td>
                                           <b>
                                              
                                          
                                           <span style="color:<?php echo $status['color']; ?>"
                                                 class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>"><?php echo $status['leave_category']; ?></span>
                                      </b> 
                                              
                                       </td>
                                   <?php
            }
            echo "<td> <b>Special Leave </b></td>";
        } ?></tr>
        <tr><td><b>Quota</b></td>
        <?php
        $confirmationDate = get_confirmation_date_satffwise($staff['staffid']);
        //print_r($confirmationDate->confirmation_date);
        //echo $confirmationDate->confirmation_date;
        $conYear = date('Y', strtotime($confirmationDate->confirmation_date));
        $conMonth = date('m', strtotime($confirmationDate->confirmation_date));
        if (date('m') > 4)
        {
            $year = date('Y') + 1;
        }
        else
        {
            $year = date('Y');
        }
        $financeYear = $year . "-03-31";
        $financeYear2 = $year - 1 . "-04-01";

        $shortOne = strtotime($financeYear);
        $shortTwo = strtotime($confirmationDate->confirmation_date);

        $year1 = date('Y', $shortOne);
        $year2 = date('Y', $shortTwo);

        $month1 = date('m', $shortOne);
        $month2 = date('m', $shortTwo);

        if (($confirmationDate->confirmation_date >= $financeYear2) && ($confirmationDate->confirmation_date <= $financeYear))
        {
            $diff = (($year1 - $year2) * 12) + ($month1 - $month2) + 1;
        }
        else
        {
            $diff = "12";
        }
		
        $staffLeadStatus = get_leave_summary_satffwise($staff['staffid'], @$start, @$end);
        //echo "<pre>"; print_r($staffLeadStatus);exit;
        if (!empty($staffLeadStatus))
        {
            foreach ($staffLeadStatus as $status)
            { ?>
                                       <td > 
                                              
                                          
                                     
                <?php
				
				$leaveQuote = ($status['leave_quota']/12)*$diff;
				
                if (isset($status['total']))
                {
                    if($status['leave_category'] == 'LWP')
					 {
						echo  '<span style="color:#FF0000"> ' . $status['total'] . ' </span>';
					 }
					else
					{
						if($status['leave_category'] == "CL" || $status['leave_category'] == "Medical" || $status['leave_category'] == "EL"){
							if($status['total'] == $status['totalleave'])
							{
								echo  '<span style="color:#FF0000"> ' . $status['total'] . ' / ' . $leaveQuote . ' </span>';
							} 
							else
							{
								echo $status['total'] . ' / ' . $leaveQuote; 
							}
						}else{
							if($status['total'] == $status['totalleave'])
							{
								echo  '<span style="color:#FF0000"> ' . $status['total'] . ' / ' . $status['leave_quota'] . ' </span>';
							} 
							else
							{
								echo $status['total'] . ' / ' . $status['leave_quota']; 
							}
						}
					}
                }
                else
                {
                    if ($status['leave_category'] == 'LWP' && $status['total'] == '')
                    {
                        echo 0;
                    }
                    else
                    {
						if($status['leave_category'] == "CL" || $status['leave_category'] == "Medical" || $status['leave_category'] == "EL"){
							echo ' 0 / ' .$leaveQuote;
						}else{
							if($status['total'] == $status['totalleave'])
							{
								echo  '<span style="color:#FF0000"> ' . $status['total'] . ' / ' . $status['leave_quota'] . ' </span>';
							} 
							else
							{
								echo '0 / ' . $status['leave_quota']; 
							}
						}
						//echo ' 0 / ' .$status['leave_quota'];
						
                    }
                }
?>
                                          
                                           
				   </td>
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