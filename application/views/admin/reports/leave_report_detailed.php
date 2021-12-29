<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center>Leave Report Detailed</center>
                         <?= form_open('', array('method' => 'POST','id'=>'filter_data','action'=>'<?php echo admin_url(); ?>reports/leave_report_detailed')); ?>
                        <div class="row">
                          <input type="hidden" name="filter_staff" value="1">
                            <div class="col-md-3">
                            <?php //print_r($selectedStaff);exit;
                            ?>
                                <select id="staff_name" name="staff_name[]" multiple  data-none-selected-text="Filter By Staff" data-live-search="true" class="selectpicker custom_lead_filter" >
                                   <!-- <option value="">Select Staff</option> -->
                                  <?php if ( !empty($staffs) ) {
                                     $selectedStaff = $this->session->userdata('staff_name');
                                     foreach ($staffs as $get_comp) { ?>
                                         <option <?php if (in_array($get_comp->staffid, $selectedStaff)){ echo 'selected'; }?> value="<?= $get_comp->staffid; ?>"><?= $get_comp->staffname; ?></option>
                                     <?php }
                                  } ?>
                               </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="start" id="start_date" class="form-control"
                                           placeholder="Select Start Date.." value="<?= $this->session->userdata('start_date'); ?>" autocomplete="off" readonly >
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="end" id="end_date" class="form-control datepicker"
                                           value="<?= $this->session->userdata('end_date'); ?>"
                                           data-format="dd-mm-yyyy" data-parsley-id="17"
                                           placeholder="Select End Date.." autocomplete="off" readonly >
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <?= form_submit(['value' => "Filter", 'type' => "submit", 'name'=>"filter", 'class' => 'btn btn-info']); ?>
                            </div>
                             <div class="col-md-1 text-right">
                               <input type="button" class="btn btn-primary" onclick="printDiv();" value="Print" />
                             </div>
                        </div>
                        <?= form_close(); ?>
                        
                        <br>
                        <div class="data" id="printableArea">
                        <table class="table  dt-table scroll-responsive"> 
                                    <thead>
                                    <tr>
									<th style="display:none">id</th>
                                      <th>Name</th>
                                      <th>leave date</th>
                                      <th>leave Category</th> 
                                      <th>Duration</th> 
                                      <th>Attachment</th> 
                                    </tr>
                                    </thead>
                                    <tbody >
                                      <?php $i = 1;
									 foreach($leaveAppplication as $application) {
                                          $this->db->where('staffid',$application->user_id); 
                                          $result=$this->db->get('tblstaff')->result();
                                          //echo $result[0]['firstname'].' '.$lastname;  

                                      ?>
									  
                                       <tr class="odd" id="rowid_<?php echo $application->leave_application_id; ?>">
									   <td style="display:none"><?= $i ?></td>
                                      <td ><?php echo $result[0]->firstname.' '.$result[0]->lastname; ?></td>
                                     
                                      <td> <?php if( $application->leave_type == "multiple_days"){ echo date('j M, Y',strtotime($application->leave_start_date)).' -To- '.date('j M,Y',strtotime($application->leave_end_date)); } else { echo   date('j M, Y',strtotime($application->leave_start_date)); }  ?>
                                     </td>
                                     <td><?php
										if(isset($application->leavename)){
										 echo  $application->leavename;
										}else{
											echo "Special Leave";
										}
										 ?></td> 
                                       <td><?= $application->duration ?></td>
                                       
                                       <?php if($application->attachment != '') { ?>
                          
                                      <td><a href="<php base_url();?>assets/attachment/<php $application->attachment; ?>" target="_blank" >View</a> </td>
                                  
                                  
                                  <? }else{ ?>
                                      <td></td>
                                    
                                  <?php }?>
                                      
                                    
                                    </tr>     
                                    <?php $i++; }?>
                                    </tbody>
                                </table>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script type="text/javascript">
  function printDiv() {
     
      $.ajax({
      url: "<?php echo admin_url(); ?>reports/filter_data_leave",
      method: "GET",
      success:function(data)
      { 
        var printContents = data;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
      }
    });
    
  }
  
</script>
</body>
</html>
