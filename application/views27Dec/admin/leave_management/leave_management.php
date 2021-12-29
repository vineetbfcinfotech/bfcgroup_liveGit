<?php init_head(); ?>
<script type="text/javascript">
  function AddLeave()
 {    
    $('#ticket-service-modal').modal('show');
    //$('.add-title').addClass('hide');
	$("#leave-title").text("Add New Leave");
 }
 function editLeave(elem)
 {    
    //$('#ticket-service-modal').modal('show');
    //alert($(this).attr("#data-id"));
	var applicationId = $(elem).data("id");
	$("#leave-title").text("Edit Leave");
	$.ajax({
           url: '/admin/leave/getLeave',
           type: 'POST',
           data: {applicationId: applicationId},
           error: function() {
              alert('Something is wrong');
           },
           success: function(data) {
                //alert(data->leave_application_id);
				var obj = JSON.parse(data);
				$("#application_id").val(obj.leave_application_id);
				$("#leave_category_id").val(obj.leave_category_id);
				if(obj.leave_type == "single_day"){
					$("#single_leave_id").val(obj.leave_type);
					$("#single_leave_id").attr("checked", "checked");
					$("input[name='single_day_start_date']").val(obj.leave_start_date);
					$("#single_day").show();
					$("#hours").hide();
					$("#multiple_days").hide();
				}
				if(obj.leave_type == "multiple_days"){
					$("#multiple_days_id").val(obj.leave_type);
					$("#multiple_days_id").attr("checked", "checked");
					$("input[name='multiple_days_start_date']").val(obj.leave_start_date);
					$("input[name='multiple_days_end_date']").val(obj.leave_end_date);
					$("#single_day").hide();
					$("#hours").hide();
					$("#multiple_days").show();
					
				}
				if(obj.leave_type == "half_day"){
					$("#half_day_id").attr("checked", "checked");
					$("input[name='hours_start_date']").val(obj.leave_start_date);
					$("input[name='single_day_start_date']").val(obj.leave_start_date);
					$("#hours").show();
					$("#single_day").hide();
					$("#multiple_days").hide();
				}
				$("#present").val(obj.reason);
				
					
				$('#ticket-service-modal').modal('show');
           }
        });

	
	
 }
 function deleteLeave(elem)
 {    
    //$('#ticket-service-modal').modal('show');
    //alert($(this).attr("#data-id"));
	var applicationId = $(elem).data("id");
	$("#leave-title").text("Edit Leave");
	
		var r = confirm("Are you sure want to delete this?");
		if (r == true) {
		  $.ajax({
           url: '/admin/leave/deleteLeave',
           type: 'POST',
           data: {applicationId: applicationId},
           error: function() {
              alert('Something is wrong');
           },
           success: function(data) {
			   alert_float('success', 'Leave Deleted Successfully..');
               /* setTimeout(function() {
					location.reload();
				}, 3000); */
			$('#row-'+applicationId).remove();
           }
			});
		}
	}
	
	
	 
  function editleave_quota(id,category,quota)
 {
    $("#leaveid").val(id);
    $("#leavename").val(category);
    $("#leavequota").val(quota);   
    $('#edit-Leave-quota-modal').modal('show');
 }
 
</script>

 

<?php $loginid=$this->session->userdata('staff_user_id'); ?> 
<div id="wrapper"><?php init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
		 
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                   
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs">
                        <!-- <li class=""><a href="#pending_approval" data-toggle="tab">Pending Approval</a>
                        </li> -->
                        <li class="active"><a href="#my_leave" data-toggle="tab">My Leave</a></li>
                        <li class=""><a href="#holidays" data-toggle="tab">Holidays</a></li>
                        <li class="float-right">
                          <a href="#" class="bg-info "  onclick="AddLeave()" ></i> Apply Leave </a>
                        </li>
                    </ul>
                    </div>
                            <div class="row">
                                <div class="tab-content" style="border: 0;padding:0;">

                   <?php if(!empty($this->session->flashdata('success'))) {?>
                       <div class="text-success text-center"><?php echo  $this->session->flashdata('success'); ?></div>
                   <?php }else if(!empty($this->session->flashdata('error'))){?>
                       <div class="text-danger text-center"><?php echo  $this->session->flashdata('error'); ?></div>
                   <?php } ?>

                <!-- <div class="tab-pane " id="pending_approval" style="position: relative;">
                  <div class="panel panel-custom">
                    <div class="panel-body">
					
					         <?php if(count($pendingApproval)>0) {?>
                            <div class="table-responsive">
                                <div id="DataTables_wrapper" class="dataTables_wrapper form-inline no-footer">
                                  <div class="dataTables_length" id="DataTables_length"><label>
                                    <select name="DataTables_length" aria-controls="DataTables" class="form-control input-sm"><option value="10">10</option><option value="20">20</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
                                  </label>
                                </div>
                                <!-- <div id="DataTables_filter" class="dataTables_filter">
                                <label>Search all columns:
                                <input type="search" class="form-control input-sm" placeholder="" aria-controls="DataTables">
                                </label></div> -->
                                 
                                  <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                                    <thead>
                                    <tr role="row">
                                      <th style="width: 154px;" >Name</th>
                                      <th style="width: 337px;">Leave Category</th>
                                      <th style="width: 128px;">Date</th>

                                      <th style="width: 210px;" >Duration</th>
                                      <th style="width: 300px;" >Action</th>
                                      <th style="width: 161px;" >Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                    
                                     <?php foreach($pendingApproval as $pendingLeave) { ?>
                                       <tr class="odd" id="row-<?php echo $pendingLeave->leave_application_id; ?>"> 
                                      <td ><?php echo  ucfirst(substr($pendingLeave->reason,0,10)); ?></td>
                                      <td><?php 

                                        $catId=$pendingLeave->leave_category_id;
                                        $this->db->where('leave_category_id',$catId); 
                                         $category=$this->db->get('tblleavecategory')->result(); 
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
                                      
										<td>
										
                                          <span><button style="" class="btn btn-xs btn-success edit-leave" data-id="<?php echo $pendingLeave->leave_application_id; ?>" onclick="editLeave(this)">Edit</button></span> |
                                          <span><button style="" class="btn btn-xs btn-warning" data-id="<?php echo $pendingLeave->leave_application_id; ?>" onclick="deleteLeave(this)">Delete</button></span>
										
                                        </td>
                                      <td><?php if($pendingLeave->application_status=='1') { ?>
                                          <button style="" class="btn btn-xs btn-danger">Pending</button>
                                        <?php  } ?></td>
                                    </tr>     
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
							
							 <?php }else{?>
							      <div class="text-danger text-center">No Records ...</div>
							 <?php }?>
							
							
							
                        </div>
                    </div>
                </div> -->
                <div class="tab-pane active" id="my_leave" style="position: relative;">
                    <div class="panel panel-custom">
                        <div class="panel-body">
						   <?php if(count($Approvalleave)>0) {?>
                          <div class="table-responsive">
                                <div id="DataTables_wrapper" class="dataTables_wrapper form-inline no-footer">
                                  <div class="dataTables_length" id="DataTables_length"><label>
                                    <select name="DataTables_length" aria-controls="DataTables" class="form-control input-sm"><option value="10">10</option><option value="20">20</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
                                  </label>
                                </div>
                                <!-- <div id="DataTables_filter" class="dataTables_filter">
                                <label>Search all columns:
                                <input type="search" class="form-control input-sm" placeholder="" aria-controls="DataTables">
                                </label></div>-->

                                  <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                                    <thead>
                                    <tr role="row">

                                      <th class="sorting_asc" tabindex="0" aria-controls="DataTables" style="width: 154px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Reason</th>
                                      <th class="sorting" tabindex="0" aria-controls="DataTables" rowspan="1" colspan="1" style="width: 337px;" aria-label="Leave Category: activate to sort column ascending">Leave Category</th>
                                      <th class="sorting" tabindex="0" aria-controls="DataTables" style="width: 128px;" aria-label="Date: activate to sort column ascending">Date</th>

                                      <th class="sorting" tabindex="0" aria-controls="DataTables" style="width: 105px;" aria-label="Duration: activate to sort column ascending">Duration</th>
                                      <!--<th class="sorting" tabindex="0" aria-controls="DataTables" style="width: 105px;" aria-label="Duration: activate to sort column ascending">Attachment</th>
                                     -->
									 <th class="sorting" tabindex="0" aria-controls="DataTables" style="width: 161px;" aria-label="Status: activate to sort column ascending">Action</th>
									 
                                      <th class="sorting" tabindex="0" aria-controls="DataTables" style="width: 161px;" aria-label="Status: activate to sort column ascending">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                    
                                     <?php foreach($Approvalleave as $Aleave) { ?>
                                       <tr class="odd">
                                      <td ><?php echo ucfirst(substr($Aleave->reason,0,10)); ?></td>
                                      <td> <?php $catId=$Aleave->leave_category_id;  $this->db->where('leave_category_id',$catId);  $category=$this->db->get('tblleavecategory')->result(); 
                                      if($category)
                                         {
                                          echo  $category[0]->leave_category; 
                                         }
                                         else
                                         {
                                           echo  "Special Leave";
                                         }
                                          ?></td>
                                      <td >

                                        <!--<?php echo date('j M, Y g:i a',strtotime($Aleave->leave_start_date)); ?> -->
                                        <?php if( $Aleave->leave_type == "multiple_days"){ echo date('j M, Y',strtotime($Aleave->leave_start_date)).' -To- '.date('j M,Y',strtotime($Aleave->leave_end_date)); } else { echo   date('j M, Y',strtotime($Aleave->leave_start_date)); }  ?>
                                      <!--  <?php echo date('j M, Y',strtotime($Aleave->leave_start_date)).' -To- '.date('j M,Y',strtotime($Aleave->leave_end_date)); ?> -->
                                       </td>
                                       
                                        <td><?= $Aleave->duration?></td>
                                       <!-- <td><img src="<?php echo base_url()."assets/attachment"/".$Aleave->attachment;"?>" /> </td> -->
									   
									   <td>
										<?php if($Aleave->application_status == 1 || $Aleave->application_status == 4){ ?>
                                          <span><button style="" class="btn btn-xs btn-success edit-leave" data-id="<?php echo $Aleave->leave_application_id; ?>" onclick="editLeave(this)" <?php if($Aleave->application_status == 1 || $Aleave->application_status == 4 ){ echo "enabled"; }else { echo "disabled"; }?> >Edit</button></span> |
                                          <span><button style="" class="btn btn-xs btn-warning" data-id="<?php echo $Aleave->leave_application_id; ?>" onclick="deleteLeave(this)" <?php if($Aleave->application_status == 1 || $Aleave->application_status == 4 ){ echo "enabled"; }else { echo "disabled"; }?> >Delete</button></span>
										<?php } ?>
                                        </td>
                                      <td><?php if($Aleave->application_status=='2') { ?>
                                          <button style="" class="btn btn-xs btn-success">Accepted.</button>
                                        <?php  }elseif($Aleave->application_status=='1'){ ?>
											<button style="" class="btn btn-xs btn-success">Pending.</button>
										<?php }elseif($Aleave->application_status=='4'){ ?>
											<button style="" class="btn btn-xs btn-success">Sanctioned.</button>
										<?php } ?></td>
                                   
                                      
                                    
                                    </tr>     
                                    <?php }?>

                                    </tbody>
                                       <tbody>
                                    
                                    
                                     <?php foreach($Rejectedleave as $Rleave) { ?>
                                       <tr class="odd">
                                      <td ><?php echo  ucfirst(substr($Rleave->reason,0,10)); ?></td>
                                      <td> <?php $catIdR=$Rleave->leave_category_id;  $this->db->where('leave_category_id',$catId);  $category=$this->db->get('tblleavecategory')->result(); 
                                      if($category)
                                         {
                                          echo  $category[0]->leave_category; 
                                         } 
                                          ?></td>
                                      <td colspan="2">

                                        <!--<?php echo date('j M, Y g:i a',strtotime($Aleave->leave_start_date)); ?> -->
                                    
                                        
                                        <?php if( $Rleave->leave_type == "multiple_days"){ echo date('j M, Y',strtotime($Rleave->leave_start_date)).' -To- '.date('j M,Y',strtotime($Rleave->leave_end_date)); } else { echo   date('j M, Y',strtotime($Rleave->leave_start_date)); }  ?>
                                        
                                        
                                        
                                        
                                       </td>
                                       
                                       <td>
                                          <!-- <span><button style="" class="btn btn-xs btn-success edit-leave" disabled >Edit</button></span> |
                                          <span><button style="" class="btn btn-xs btn-warning" disabled >Delete</button></span> -->
                                        </td>
									   
                                      <td><?php if($Rleave->application_status=='3') { ?>
                                          <button  style="" class="btn btn-xs btn-danger">Rejected.</button>
                                        <?php  } ?></td>
                                   
                                      
                                    
                                    </tr>     
                                    <?php }?>

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
                <div class="tab-pane" id="holidays" style="position: relative;">
                    <div class="panel panel-custom">
                        <div class="panel-body">
                            
                          <div class="table-responsive">
                                <div id="DataTables_wrapper" class="dataTables_wrapper form-inline no-footer">
                                  <div class="dataTables_length" id="DataTables_length"><label>
                                    <select name="DataTables_length" aria-controls="DataTables" class="form-control input-sm"><option value="10">10</option><option value="20">20</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
                                  </label>
                                </div>
                                <!-- <div id="DataTables_filter" class="dataTables_filter">
                                <label>Search all columns:
                                <input type="search" class="form-control input-sm" placeholder="" aria-controls="DataTables">
                                </label></div>-->

                                  <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="holidays_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                                    <thead>
                                    <tr role="row">

                                     <th style="width: 154px;" >Name</th>                                     
                                     
                                     <th style="width: 210px;">Leave Type</th>
                                      
                                      <th style="width: 210px;">Start Date</th>
                                      
                                      <th style="width: 210px;">End Date</th>
                                      
                                      <th style="width: 210px;">Duration</th>
                                    </thead>
                                    <tbody>
                                    
                                    
                                     <?php foreach($ourholidays as $hol) { ?>
                                   
                                       <tr class="odd">
                                      <td ><?php echo  $hol->name; ?></td>
                                      <td><?php if( $hol->leave_type == "multiple_days"){ echo "Multiple Days"; } else { echo  "Single Day"; }  ?></td>
                                      <td><?php echo  $hol->quota; ?></td>
                                      <td><?php   if( $hol->leave_type == "multiple_days"){ echo $hol->leave_end_date; } else { echo  "N/A"; } ?></td>
                                      <td><?php echo  $hol->days; ?></td>
                                     
                                      
                                      
                                    
                                    </tr>     
                                    <?php }?>

                                    </tbody>
                                      
                                </table>
                            

                            </div>
                            </div>
							
							
                        </div>
                    </div>

                </div>
              
                


                                </div>

                            </div>
                </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="edit-Leave-quota-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form autocomplete="off" action="<?php echo  base_url(); ?>admin/leave/updateleave" id="ticket-service-form" method="post" accept-charset="utf-8">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title">Edit Leave</span>
                        <!-- <span class="add-title">New Product</span> -->
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="additional"></div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Leave Name</label>
                              <input type="text" id="leavename" name="leavename" class="form-control"  value="">
                              <input type="hidden" id="leaveid" name="leaveid" class="form-control"  value="">
                            </div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Quota</label>
                              <input type="text" id="leavequota" name="leavequota" class="form-control"  value="">
                            </div> 

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div><!-- /.modal-content -->
            </form>        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<div class="modal fade" id="ticket-service-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" >
          <!--   <form action=""  method="post" accept-charset="utf-8" > -->
<input type="hidden" name="csrf_token_name" value="70b7e1b0a91ba66b991a5ea162fb3753" />                       
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title" id="leave-title">Add New Leave</span>
                       <!--  <span class="add-title"> New Leave</span> -->
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8">
                          <div class="alertmessage"></div> <!-- Save-leave-form -->
                <form autocomplete="off" id="" action="<?php echo base_url(); ?>admin/leave/saveleave" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="panel_controls">
                            <input type="hidden" id="user_id" value="17">
                            <input type="hidden" id="current_user_id" value="<?php echo $this->session->userdata('staff_user_id'); ?>">
							<input type="hidden" name="application_id" id="application_id" value="">
							
                            <div class="form-group">
                            <label for="field-1" class="col-sm-4 control-label">Leave Category<span class="required"> *</span></label>

                            <div class="col-sm-8 ">
                                <select name="leave_category_id" style="width: 100%;" class="form-control select_box select2-hidden-accessible" id="leave_category_id" required="" data-parsley-id = "4" tabindex="-1" aria-hidden="true">
                                    <option value="">Select Leave Category</option>
                                    <?php $i=1;  $total_Approveleave=0; 
                                     foreach($leaveCategory as $category) {
                                        $catId=$category['leave_category_id']; 
                                        $loginid=$this->session->userdata('staff_user_id'); 
                                       $this->db->select_sum('duration');
                                        $this->db->where('leave_category_id',$catId); 
                                        $this->db->where('application_status','2'); 
                                        $this->db->where('date(leave_start_date) >=', $start);
                                        $this->db->where('date(leave_end_date) <=', $end);
                                        $this->db->where('user_id',$loginid); 
                                        $result=$this->db->get('tblleaveapplication')->result();
                                        $to = $result[0]->duration;
                                        
                                        
                                        $count=count($result); 
                                        if($to == null)
                                        { $to = "0"; } 
                                        
                                        // echo $to;
                                        // echo $category['totalleave'];
                                        
                                         ?>
                                         
                                         <option <?php if($to >=$category['leave_quota']) echo 'disabled'; ?> value="<?php echo  $category['leave_category_id']; ?>"><?php echo  $category['leave_category']; ?></option>
                                    <?php $i++;
                               
                                }?>
                                <?php
                                
                                $this->db->select('sp_leave');
                            $this->db->where('staffid', $loginid);
                            $spstaff = $this->db->get('tblstaff')->row();
                            $spstaffid = $spstaff->sp_leave;
                            
                            $this->db->select_sum('duration');
                            $this->db->where('user_id', $loginid);
                            $this->db->where('application_status','2');
                            $this->db->where('date(leave_start_date) >=', $start);
                            $this->db->where('date(leave_end_date) <=', $end);
                            $specialleave=$this->db->get('tblleaveapplication')->row();
                            $specialleavetaken = $specialleave->duration;
                            
                            
                            if($total_special_leave->quota != "" && $total_special_leave->quota > $applied_special_leave->duration && ($notice_date == null || $currentDate < $notice_date)) {
                                ?>
                                
                                    <option value="0">Special Leave</option>
                                    
                                
                                <?}?>
                                
                                ?>
                                </select> 

                            </div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                <div class="required" id="username_result"></div>
                            </div>
                        </div>
                        <div class="form-group">
                           <div class="row no-margin">
                            <label class="col-sm-4 control-label">Duration <span class="required"> *</span></label>
                            <div class="col-sm-8">
                                <label class="radio-inline c-radio">
                                    <input type="radio" id="single_leave_id" name="leave_type" value="single_day" checked="" data-parsley-multiple="leave_type" data-parsley-id="7" >
                                    Single day</label>
                                <label class="radio-inline c-radio">
                                    <input type="radio" name="leave_type" value="multiple_days" id="multiple_days_id" data-parsley-multiple="leave_type">
                                   Multiple days</label>
                              <label class="radio-inline c-radio">
                                    <input type="radio" name="leave_type" value="half_day" id="half_day_id" data-parsley-multiple="leave_type">
                                    Half Day</label> 
                            </div>
                          </div>
                        </div>



                        <div class="form-group" id="single_day">
                           <div class="row no-margin">
                            <label class="col-sm-4 control-label">Start Date 
                              <span class="required"> *</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" name="single_day_start_date"  class="form-control datepicker sandwitchcheck" data-date-min-date="" placeholder="Select A Date..">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                    <select name="half_shift" style="width: 100%; display: none;" class="form-control select_box select2-hidden-accessible" id="half_shift"  tabindex="-1" aria-hidden="true">
                                    <option value="" >Select Shift</option>                                        
                                         <option value="Morning">Morning</option>                                        
                                         <option value="Evening">Evening</option>
                                                                    </select> 
                                </div>
                            </div>
                                
                          </div>
                        </div>
                        <div id="multiple_days" style="display: none;">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Start Date 
                                  <span class="required"> *</span>
                                </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" name="multiple_days_start_date" id="start_date" class="form-control sandwitchcheck" placeholder="Select Start Date..">
                                        <div class="input-group-addon" >
                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">End Date <span class="required"> *</span></label>

                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" name="multiple_days_end_date" id="end_date"  class="form-control datepicker sandwitchcheck" value="" data-format="dd-mm-yyyy" data-parsley-id="17" placeholder="Select End Date..">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
              <?php 
                    
                 
              ?>
							<div class="form-group quota_exceed_data" style="display:none; ">
								<div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <div class="input-group">

                   <label class="checkbox-inline">
										  <input type="checkbox" value="0" name="quota_exceed" class="quota_exceed" checked="checked" readonly="readonly" /><span style="color: #fb0000;">Your Quota Exceeded. Now You Are Applied In LWP.</span>
										</label>
                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="hours" style="display: none;">
                            <label class="col-sm-4 control-label">Start Date
                              <span class="required"> *</span></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" name="hours_start_date" id="hours_start_date"  class="form-control datepicker sandwitchcheck" value="" data-format="dd-mm-yyyy" data-parsley-id="19" placeholder="Select A Date..">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-5 control-label"> Hours                                    <span class="required"> *</span></label>
                                <div class="col-sm-7 pr0">
                                    <select name="hours" id="hours" class="form-control" data-parsley-id="21">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-4 control-label">Reason</label>
                            <div class="col-sm-8"><textarea id="present" name="reason" class="form-control" rows="6" data-parsley-id="23"></textarea></div>
                        </div>

                        
                        <div class="form-group" style="margin-bottom: 0px">
                            <label for="field-1" class="col-sm-4 control-label">Attachment</label>

                            <div class="col-sm-8">
                               <input type="file" name="file" class="form-control">
                            </div>
                        </div>
                         <?php 
                                  $this->db->where('application_status','2'); 
                                  $this->db->where('date(leave_start_date) >=', $start);
                                  $this->db->where('date(leave_end_date) <=', $end);
                                  $this->db->where('user_id',$loginid);
                                  $this->db->where('sandwitch_avail', 1); 
                                  $result=$this->db->get('tblleaveapplication')->result();
                                  //count($result);
                                  if(count($result)==0){
                              ?>
                        <div id="sandwitchdiv" class="form-group" style="margin-bottom: 0px;display: none;">
                            <label for="field-1" class="col-sm-4 control-label">Sandwitch Leave Exception</label>

                            <div class="col-sm-8">
                             
                               <label class="radio-inline c-radio">
                                   <input id="sandwitchleave" type="hidden" name="sandwitchleave" value="0" />
                                    <input class="sandcheckbox"  type="checkbox" name="sandwitch" value="1" >
                                    </label>
                              
                            </div>
                        </div>  <?php }?>
                        <br/><br/>
                        <div class="form-group mt-lg">
                            <div class="col-sm-offset-4 col-sm-5">
                                <button type="submit" id="leave-save-button"  name="sbtn" value="1" class="btn btn-primary">Apply
                                </button>
                            </div>
                        </div>
                        <br>
                    </div>
                </form>
            </div>
                        <div class="col-md-4">
                         <div class="panel panel-custom">
                    <!-- Default panel contents -->
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong>My Leave Details</strong>
                        </div>
                    </div>
                   <table class="table">
                        <tbody>
                          <?php 
                          
                          $total_quota=0; $total_Approveleave=0;
						  
						  /* Start Get Leave From Attendance */
						  
						  $staff_id = $this->session->userdata('staff_user_id');
						  $bio_id = $this->session->userdata('staff_bio_id');
						  
						  $totalday = $this->db->query('SELECT  count(UserId) as cnt FROM deviceLogs_2_2020 WHERE year(LogDate) = 2020 AND (UserId = "'.$bio_id.'" OR staffId = "'.$staff_id.'") GROUP BY `UserId`, date(LogDate) HAVING cnt = 1');
						  
                            $missedLogs = $totalday->result();
							$totalMissedLog = 0;
							foreach($missedLogs as $missedLog){
								$totalMissedLog = $totalMissedLog+$missedLog->cnt;
							}
							
						  /* Stop Get Leave From Attendance */
                      foreach($leaveCategory as $category1) {
							              $catId=$category1['leave_category_id']; 
                            $this->db->select_sum('duration');
                            $this->db->where('leave_category_id',$catId); 
                            $this->db->where('application_status','2');
							              $loginid=$this->session->userdata('staff_user_id');	
                            $this->db->where('user_id',$loginid); 
                            $this->db->where('date(leave_start_date) >=', $start);
                            $this->db->where('date(leave_end_date) <=', $end);
                            $result=$this->db->get('tblleaveapplication')->result(); 
							              //echo $this->db->last_query()."<br><br>";
                            $to = $result[0]->duration;
                            $this->db->where('staffid',$loginid); 
                            $copmpl=$this->db->get('tblstaff')->result(); 
                            $comp_off = $copmpl[0]->comp_off;
                            
							
                             
                            // print_r($leaveCategory);
							
							if($category1['leave_category'] == "CL"){
								$appliedCL = $to;
								if($appliedCL > 12){
									$to = 12;
								}else{
									$to = $to;
								}
							}
							//echo $to;
							if($category1['leave_category'] == "EL" && $appliedCL > 12){
								$appliedEL = $to+($appliedCL-12);
								$to = $to+$appliedEL;
								if($to > 6){
									$to = 6;
								}
							}
							// "<h1>Test</h1>";
							if($category1['leave_category'] == "LWP" && $appliedEL > 6){
								$to = $to+($appliedEL-6);
							}
                            
                            ?>
                               <tr>
                                    <td><strong> <?php echo  $category1['leave_category']; ?></strong>:</td>
                                    <td><? if($category1['leave_category'] == 'LWP') { ?> <? if($to == null){ echo "0"; } else { echo  $to; } ?> <? } else { ?> <? if($to == null){ echo "0"; } else { echo  $to; } ?>/<?= $category1['leave_quota']; ?> <? } ?></td>
                                            
                                </tr>
                               
                          <?php

                           $total_Approveleave=$total_Approveleave+ $to;
                           $total_quota=$total_quota+$category1['leave_quota'];  

                           } ?>
                           
                            <? 
                            $this->db->select_sum('quota');
                            $this->db->where('emp_id', $loginid);
                            $spstaff = $this->db->get('special_leave')->row();
                            $spstaffid = $spstaff->quota;
                            //echo $this->db->last_query(); 
                            $this->db->select_sum('duration');
                            $this->db->where('user_id', $loginid);
                            $this->db->where('application_status','2');
                            $this->db->where('leave_category_id','0');
                            $this->db->where('date(leave_start_date) >=', $start);
                            $this->db->where('date(leave_end_date) <=', $end);
                            $specialleave=$this->db->get('tblleaveapplication')->row();
                            $specialleavetaken = $specialleave->duration;
							//echo $this->db->last_query();
                            $total_Approveleave = $total_Approveleave+$specialleavetaken;
                            
                            
                            if($spstaffid > 0  || $specialleavetaken > 0) {
                                ?>
                                <tr>
                                    <td><strong><?= "Special Leave"; ?></strong>:</td>
                                    <td>
                                        <?php if($applied_special_leave->duration == ""){ echo "0"; }else{ echo $applied_special_leave->duration; } ?>/<?php if($total_special_leave->quota == ""){ echo "0"; } else { echo  $total_special_leave->quota; }; ?></td>
                                </tr>
                               
                                
                                <?}?>
                            <tr>
                              <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;">
                                  <strong> Total Availed</strong>:
                              </td>
                              <td style="background-color: #e8e8e8; font-size: 14px; font-weight: bold;"><?php echo  $total_Approveleave ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-info">Save</button> -->
                </div>
            </div><!-- /.modal-content -->
          <!--   </form>   -->      

          </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<script id="hidden-columns-table-leads" type="text/json">
   <?php echo get_staff_meta(get_staff_user_id(), 'hidden-columns-table-leads'); ?>
</script>
<?php include_once(APPPATH.'views/admin/leads/status.php'); ?>
<?php init_tail(); ?>
<script>

$('.radio-inline.c-radio input[type="radio"]').click(function(){
   
    if($(this).is(':checked'))
    {
      $val=$(this).val();
	  //alert($val);
      if($val=='single_day')
      {
        $('#single_day').css('display','block'); 
        $('#multiple_days').css('display','none'); 
        $('#hours').css('display','none');  
        $('#half_shift').css('display','none');
        $('#half_shift').prop('required',false);
    		$(".quota_exceed_data").css('display', 'none');
    		$(".quota_exceed").val('0');
    		$("input[name='multiple_days_start_date']").val("");
    		$("input[name='multiple_days_end_date']").val("");
      }
      if($val=='multiple_days')
      {
        $('#single_day').css('display','none'); 
        $('#multiple_days').css('display','block'); 
        $('#hours').css('display','none'); 
        $('#half_shift').prop('required',false);
		    $("input[name='single_day_start_date']").val("");
		
		
		  $("#end_date").change(function(){
				var date1 = $("#start_date").val();
				var date2 = $("#end_date").val();
				
				var startDate = Date.parse(date1);
				var endDate = Date.parse(date2);
				var timeDiff = endDate - startDate;
				daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
				var totalDays = daysDiff+1;
				var user_id = $("#current_user_id").val();
				var leave_cat_id = $("#leave_category_id").val();
				var appplication_id = $("#application_id").val();
				//alert(appplication_id);
				if(appplication_id == ""){
					$.ajax({ 
					   url: '/admin/leave/countLeave',
					   type: 'POST',
					   data: {user_id: user_id, leave_cat_id: leave_cat_id },
					   /* error: function() {
						  alert('Something is wrong');
					   }, */
					   success: function(data) {
							if(data < totalDays){
								$(".quota_exceed").val('1');
								$(".quota_exceed_data").css('display','block');
							}else{
								$(".quota_exceed").val('0');
								$(".quota_exceed_data").css('display', 'none');
							}
							
							//if(data.leave_category_id)
					   }
					});
				}
				
			});
			
      }
      if($val=='half_day')
      {
        $('#single_day').css('display','block'); 
        $('#multiple_days').css('display','none'); 
        $('#hours').css('display','none');  
        $('#half_shift').css('display','block');
        $('#half_shift').prop('required',true);
    		$(".quota_exceed_data").css('display', 'none');
    		$(".quota_exceed").val('0');
      }

    }
  });

 function leavesave()
 {
     var sel = $('#leave_category_id').val();
                    if (sel == '3') {
                        if ($('input[name=file]').val() == '') {
                            alert('Please Attach Medical Document while applying Medical Leave');
                            return false; //prevent submit from submitting
                        }
                    }
    var half_shift = $('#half_shift').val();
    var sandwitchleave = $('#sandwitchleave').val();
                    
   var leave_category_id=$('#leave_category_id').val();
   var duration=$(".radio-inline.c-radio input[name='leave_type']:checked").val();
   if (duration == 'half_day') {
                        if (half_shift == 'Select Shift') {
                            alert('Please Select Halft day leave Shift ');
                            return false; //prevent submit from submitting\
                            }
                    }
   var single_day_start_date=""; 
   var multiple_days_start_date="";
   var multiple_days_end_date="";
   var hours_start_date="";
   var hours=""; 
    
   if (duration) {
    
          switch(duration)
          {
             case 'single_day':
             single_day_start_date=$('#single_day_start_date').val();
             break;
                  
             case 'multiple_days':
               multiple_days_start_date=$('#multiple_days_start_date').val();
               multiple_days_end_date=$('#multiple_days_end_date').val();
             break;

             case 'hours':
             hours_start_date=$('#hours_start_date').val(); 
             hours=$('#hours').val(); 
             break;
          }
          
          //alert( leave_category_id+'--'+duration+'--'+single_day_start_date+'--'+multiple_days_start_date+'--'+multiple_days_end_date+'--'+hours_start_date+'--'+hours);

          var reason=$('#present').val();  
          var file=""; 

          $.ajax({
            url: "<?php echo base_url(); ?>admin/leave/saveleave", // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: new FormData('#Save-leave-form'), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {

            //$('#loading').hide();
            // $("#message").html(data);
            }
            });

          


   }else{
  
     $('.alertmessage').html('<div class="text-danger">Plase first  select Leave Duration ..</div>'); 
   }

 }
 
 /* $('#Save-leave-form').submit(function(e){ 
     
     var sel = $('#leave_category_id').val();
                    if (sel == '3') {
                        if ($('input[name=file]').val() == '') {
                            alert('Please Attach Medical Document while applying Medical Leave ');
                            return false; //prevent submit from submitting
                        }
                    }
      e.preventDefault(); 
 alert()
    var formURL = $('#Save-leave-form').attr("action");
    var formData = new FormData($('#Save-leave-form')[0]);

      $.ajax({
            url: "<?php echo base_url(); ?>admin/leave/saveleave", // Url to which the request is send
            //url:formURL, 
            type: "POST",             // Type of request to be send, called as method
            //data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            data:formData, 
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
              //console.log(data); 
             if(data=='save')
             {
                $('#ticket-service-modal').modal('hide');
             }

            //$('#loading').hide();
            // $("#message").html(data);
            }
            });


       
 });  */


</script>

<script>
$(document).ready(function(){
    $("#txtFromDate").datepicker({
        numberOfMonths: 2,
        onSelect: function(selected) {
          $("#txtToDate").datepicker("option","minDate", selected)
        }
    });
    $("#txtToDate").datepicker({ 
        numberOfMonths: 2,
        onSelect: function(selected) {
           $("#txtFromDate").datepicker("option","maxDate", selected)
        }
    });  
});
</script>

<script>
  $(document).ready(function(){
    $(".sandwitchcheck").on('change', function postinput(){
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var startDate = new Date(start_date);
        var endDate = new Date(end_date);
        var totalSundays = 0;
       
        for (var i = startDate; i <= endDate; ){
            if (i.getDay() == 0){
                totalSundays++;
            }
            i.setTime(i.getTime() + 1000*60*60*24);
        }
        if(totalSundays > 0)
        {    //alert("hello")
            $('#sandwitchdiv').css('display','block');
            $('#sandwitchleave').val('1');
            alert_float('success', 'You are applying a Sandwitch Leave');
        }
        else
        {
          $('#sandwitchdiv').css('display','none'); 
          $.ajax({ 
            url: 'leave/sandwitchcheck',
            data: { start_date: start_date,end_date:end_date},
            type: 'post'
          }).done(function(responseData) {
           // alert(responseData);
           if(responseData > 0)
           {
               
               $('#sandwitchdiv').css('display','block');
                $('#sandwitchleave').val('1');
               alert_float('success', 'You are applying a Sandwitch Leave');
           }
           else
           {
               $('#sandwitchleave').val('0');
               $('#sandwitchleave').val('0');
               $('#sandwitchdiv').css('display','none');
           }
                                
        }).fail(function() {
                               
        });
        }
        
    });
  });     
</script>
<script>
   $(document).ready(function(){
    $(".sandcheckbox").on('click', function postinput(){
     if($(this).prop("checked") == true)
     {  //alert("hello-1");
        // $('#sandwitchleave').val('1');
        $('input[name=sandwitch]').attr('checked', false);
     }
     else
     {  //alert("hello-2");

        // $('#sandwitchleave').val('2');
        $('input[name=sandwitch]').attr('checked', true);
     }
	});
	
	$("#leave-save-button").on("click",function(event){
		 if($("#leave_category_id").find(":selected").val() == 3 && $('input[name=file]').val() == ""){
			confirm('Please Attach Medical Document while applying Medical Leave');
				return false;
		}else{
			return true;
		}
	});

}); 
</script>


</body>
</html>
