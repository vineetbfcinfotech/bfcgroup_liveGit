<?php init_head(); ?>
<style>
   #specialleave_row .col-md-3{
   min-height: 44px;
   }
</style>
<?php 
   $CI=&get_instance();
   $loginid=$this->session->userdata('staff_user_id'); 
   ?> 
<div id="wrapper">
   <?php init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h3>
                     <center>Sanction Pending Leaves</center>
                  </h3>
                  <hr/>
                  <?php if(count($pendingApproval)>0) {?>
                  <div class="table-responsive">
                     <div id="DataTables_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <div class="dataTables_length" id="DataTables_length">
                           <label>
                              <select name="DataTables_length" aria-controls="DataTables" class="form-control input-sm">
                                 <option value="10">10</option>
                                 <option value="20">20</option>
                                 <option value="25">25</option>
                                 <option value="50">50</option>
                                 <option value="100">100</option>
                              </select>
                           </label>
                        </div>
                        <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                           <thead>
                              <tr role="row">
                                 <th style="width: 154px;" >Member Name</th>
                                 <th style="width: 154px;" >Leave Subject</th>
                                 <th style="width: 337px;">Leave Category</th>
                                 <th style="width: 128px;">Date</th>
                                 <th style="width: 210px;" >Duration</th>
								 <th style="width: 161px;" >Attechment</th>
                                 <th style="width: 300px;" >Action</th>
                                 
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($pendingApproval as $pendingLeave) { ?>
                              <tr class="odd" id="row-<?php echo $pendingLeave->leave_application_id; ?>">
                                 <td ><?php echo $pendingLeave->full_name; ?></td>
                                 <td ><p data-toggle="tooltip" data-placement="top" title="<?= $pendingLeave->reason; ?>"><?php echo  ucfirst(substr($pendingLeave->reason,0,10)); ?></p></td>
                                 <td><?php 
                                    $catId=$pendingLeave->leave_category_id;
                                    /****** getLeaveCategoryById() is call for leave catefory and this is in the Leave Controller ******/
                                     $category=$CI->getLeaveCategoryById($catId); 
                                     if($category)
                                     {
                                      echo  $category[0]->leave_category; 
                                     }
                                     else
                                     {
                                         echo "Special Leave";
                                     }
                                    
                                       ?></td>
                                 <td>
                                    <?php if( $pendingLeave->leave_type == "multiple_days"){ echo date('j M, Y',strtotime($pendingLeave->leave_start_date)).' -To- '.date('j M,Y',strtotime($pendingLeave->leave_end_date)); } else { echo   date('j M, Y',strtotime($pendingLeave->leave_start_date)); }  ?>
                                 </td>
                                 <td><?= $pendingLeave->duration ?></td>
								 
								 <td><?php if($pendingLeave->attachment != ""){ ?><a href="<?php echo base_url('/assets/attachment/').$pendingLeave->attachment; ?>" target="_blank"><img src="<?php echo base_url('/assets/attachment/').$pendingLeave->attachment; ?>" style="max-width: 100px;"></a><?php } ?></td>
								 
                                 <td>
								 <?php if($pendingLeave->staffid != $loginid){ ?>
                                    <span><button style="" class="btn btn-xs btn-success edit-leave" data-id="<?php echo $pendingLeave->leave_application_id; ?>" data-cat="<?php echo $pendingLeave->leave_category_id; ?>" data-attechment="<?php echo $pendingLeave->attachment; ?>" onclick="sanctionLeave(this)">Sanction</button></span> | <span><button style="" class="btn btn-xs btn-warning" data-id="<?php echo $pendingLeave->leave_application_id; ?>" onclick="sanctionDisapprove(this)">Reject</button></span>
								 <?php } ?>
                                 </td>
                                 <!-- <td><?php //if($pendingLeave->application_status=='1') { ?>
                                    <button style="" class="btn btn-xs btn-danger">Pending</button>
                                    <?php  //} ?>
                                 </td> -->
                              </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <?php }else{?>
                  <div class="text-danger text-center">No Records ...</div>
                  <?php }?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<?php init_tail(); ?>
<script>
/*** Start script for Sanction Leave ***/
   function sanctionLeave(elem)
   {   
   	var applicationId = $(elem).data("id");
	var leaveCatId = $(elem).data("cat");
	var attechment = $(elem).data("attechment");
	
	if(leaveCatId == 3 && attechment == ""){
		alert("Leave category is medical leave and attachment not available!");
		return false;
	}
   	var r = confirm("Are You Sure Want to Sanction This Leave?");
   	if (r == true) {
   		$.ajax({
   		   url: '/admin/leave/sanctionApprove',
   		   type: 'POST',
   		   data: {applicationId: applicationId},
   		   error: function() {
   			  alert('Something is wrong');
   		   },
   		   success: function(data) {
   			   alert_float('success', 'Leave Sanctioned Successfully..');
   				$('#row-'+applicationId).remove();
   			}
   		});
   	}
   }
/*** Stop script for Sanction Leave ***/

/*** Start script for Reject Leave ***/
   function sanctionDisapprove(elem)
   {   
   	var applicationId = $(elem).data("id");
   	var r = confirm("Are You Sure Want to Reject This Leave?");
   	if (r == true) {
   		$.ajax({
   		   url: '/admin/leave/sanctionDisapprove',
   		   type: 'POST',
   		   data: {applicationId: applicationId},
   		   error: function() {
   			  alert('Something is wrong');
   		   },
   		   success: function(data) {
   			   alert_float('success', 'Leave Rejected Successfully..');
   				$('#row-'+applicationId).remove();
   			}
   		});
   	}
   }
/*** Stop script for Reject Leave ***/
</script>
</body>
</html>