<?php
   if ( !empty($leavedata) ) {
      foreach ($leavedata as $ld) {
         $this->db->where('staffid', $ld->user_id);
         $result = $this->db->get('tblstaff')->result();
		 
		 $this->db->where('staffid', $ld->user_id);
         $result2 = $this->db->get('tblteams')->result();
		 
         ?>
          <div class="modal-body">
              <div class="row">
                  <div class="col-sm-8" style=" font-size: 18px;line-height: 37px;">
                      <table>
                          <tr>
                              <th style="width: 154px;">Name</th>
                              <td><?= $result[0]->firstname . ' ' . $result[0]->lastname; ?></td>
                          </tr>
                          <tr>
                              <th style="width: 154px;">Applied On</th>
                              <td><?= $ld->application_date; ?></td>
                          </tr>
                          <tr>
                              <th style="width: 154px;">Type</th>
                              <td><?= ($ld->leave_type == "multiple_days") ? "Multiple Days" : "Single Day"; ?></td>
                          </tr>
                          <tr>
                              <th style="width: 154px;">Start Date</th>
                              <td><?= $ld->leave_start_date; ?></td>
                          </tr>

                          <tr>
                              <th style="width: 154px;">Duration</th>
                              <td><?= $ld->duration; ?></td>
                          </tr>
                          <tr>
                              <th style="width: 154px;">Leave Category</th>
                              <td> <?php $catId = $ld->leave_category_id;
                                    $this->db->where('leave_category_id', $catId);
                                    $category = $this->db->get('tblleavecategory')->result();
                                    if ( $category ) {
                                       echo $category[0]->leave_category;
                                    }
                                    else
                                    {
                                        echo "Special Leave";
                                    }
                                 ?></td>
                          </tr>
                          <tr>
                              <th style="width: 154px;">Reason</th>
                              <td><?= $ld->reason; ?></td>
                          </tr>
                          <?php if($ld->attachment != '') { ?>
                          <tr>
                              <th style="width: 154px;">Attachment</th>
                              <td><a href="<?= base_url();?>assets/attachment/<?= $ld->attachment; ?>" target="_blank" >View</a> </td>
                          </tr>
                          <? } ?>
						  <?php if($ld->attachment == '' && $ld->leave_category_id == 3) { ?>
                          <tr>
                              <td style="color:red;" colspan="2">Medical proof not attached!</td>
                          </tr>
                          <? } ?>
                          
                          
                          <td colspan="2">
                             <?php if ( $ld->application_status == '1' || $ld->application_status == '4' ) { ?>
								<?php if($ld->leave_category_id == 3){ ?>
                                 <button id="approvalbutton" onclick="togglestatus(<?= $ld->leave_application_id; ?>,'2')"
                                         class="btn btn-success appstatus btn-icon" <?php if( $ld->application_status == '4' && $ld->attachment != '' ){ echo "enabled"; } else{ echo "disabled"; } ?> >Approve
                                 </button>
								<?php }else{ ?>
								<button id="approvalbutton" onclick="togglestatus(<?= $ld->leave_application_id; ?>,'2')"
                                         class="btn btn-success appstatus btn-icon" <?php if( $ld->application_status == '4' ){ echo "enabled"; } else{ echo "disabled"; } ?> >Approve
                                 </button>
								<?php } ?>
								 
								 <?php if($ld->application_status == '4'){ ?>
									<button id="approvedbutton" class="btn btn-success appstatus btn-icon" disabled>Sanctioned</button>
								 <?php }else { ?>
									 <button id="rejectionbutton" class="btn btn-warning appstatus btn-icon _delete" disabled>Sanction
                                 </button>
								 <?php } ?>
                                 <button id="rejectionbutton" onclick="togglestatus(<?= $ld->leave_application_id; ?>,'3')"
                                         class="btn btn-danger appstatus btn-icon _delete">Reject
                                 </button>
                                <?php
                             } else if ( $ld->application_status == '2' ) { ?>
                                 <button id="approvedbutton" class="btn btn-success appstatus btn-icon">Approved</button>
                             <?php } else { ?>
                                 <button id="rejectedbutton" class="btn btn-danger appstatus btn-icon _delete">Rejected</button>
                             <?php } ?>
                          </td>
                      </table>
                  </div>
                  <div class="col-md-4" style="border: #000 !important;border-radius: 20%;background: #343;">
                      <div class="panel panel-custom">
                          <!-- Default panel contents -->
                          <div class="panel-heading">
                              <div class="panel-title">
                                  <strong><?= $result[0]->firstname; ?>'s Leave Details</strong>
                              </div>
                          </div>
                          <table class="table">
                              <tbody>
                              <?php $total_quota = 0;
                                 $total_Approveleave = 0;
                                 if ( !empty($leaveCategory) ) {
                                    foreach ($leaveCategory as $category) {
                                       $catId = $category['leave_category_id'];
                                       $this->db->select_sum('duration');
                                       $this->db->where('leave_category_id', $catId);
                                       $this->db->where('application_status', '2');
                                       $loginid = $this->session->userdata('staff_user_id');
                                       $this->db->where('user_id', $uid);
									   $this->db->where('date(leave_start_date) >=', $start);
									   $this->db->where('date(leave_end_date) <=', $end);
                                       $result = $this->db->get('tblleaveapplication')->result();
									   //echo $this->db->last_query()."<br><br><br>";
                                       $to = (!empty($result)) ? $result[0]->duration : 0;
                                       
                                       ?>
                                        <tr>
                                            <td><strong> <?= $category['leave_category']; ?></strong>:</td>
                                             <td><? if($category['leave_category'] == 'LWP') { ?> <? if($to == null){ echo "0"; } else { echo  $to; } ?> <? } else { ?> <? if($to == null){ echo "0"; } else { echo  $to; } ?>/<?= $category['leave_quota']; ?> <? } ?></td>
                             
                                        </tr>
                                       <?php
                                       $total_Approveleave += $to;
                                       $total_quota += $category['totalleave'];
                                    }
                                 } ?>
                                 
                                 <? 
                            /* $this->db->select('sp_leave');
                            $this->db->where('staffid', $uid);
                            $spstaff = $this->db->get('tblstaff')->row();
                            $spstaffid = $spstaff->sp_leave; */
							$current_year = date('Y');
							$this->db->select_sum('quota');
                            $this->db->where('year(created_at)', $current_year);
                            $this->db->where('emp_id', $uid);
                            $spstaff = $this->db->get('special_leave')->row();
                            $spstaffid = $spstaff->quota;
                            
                            $this->db->select_sum('duration');
                            $this->db->where('user_id', $uid);
                            $this->db->where('application_status','2');
                            $this->db->where('date(leave_start_date) >=', $start);
                            $this->db->where('date(leave_end_date) <=', $end);
                            $this->db->where('leave_category_id <=', 0);
                            $specialleave=$this->db->get('tblleaveapplication')->row();
                            $specialleavetaken = $specialleave->duration;
							
							//echo $this->db->last_query();
							
                            $total_Approveleave = $total_Approveleave+$specialleavetaken;
                            
                            
                            if($spstaffid > 0  || $specialleavetaken > 0) {
                                
                                
                                ?>
                                <tr>
                                    <td><strong>  <?= "Special Leave"; ?></strong>:</td>
                                    <td>
                                        <?php if($specialleavetaken == null){ echo "0"; } else { echo  $specialleavetaken; }  ?>/<?php if($spstaffid == ""){echo 0;}else{echo  $spstaffid;} ?></td>
                                </tr>
                               
                                
                                <?}?>
                                 
                                 
                              <tr>
                                  <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">
                                      <strong> Total Availed</strong>:
                                  </td>
                                  <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"><?= $total_Approveleave ?></td>
                              </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      <?php }
   } ?>
<script>
    function togglestatus(applicationId, status) {
        const contanier = $("#rowid_" + applicationId + " .appstatus");
        $.ajax({
            url: "<?= base_url(); ?>admin/leave/togglestatus", // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: {AppId: applicationId, sts: status}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            success: function (data)   // A function to be called if request succeeds
            {
                contanier.html(data);
                if (data === 'Approved') {
                     $("#rejectionbutton").hide();
                     $("#approvalbutton").html("<b>Approved</b>");
                     
                    contanier.removeClass('btn-danger');
                    contanier.addClass('btn-success');
                } else {
                    
                     $("#approvalbutton").hide();
                     $("#rejectionbutton").html("<b>Rejected</b>");
                    contanier.removeClass('btn-success');
                    contanier.addClass('btn-danger');
                }
            }
        });
    }
</script>

                         