<?php init_head(); ?>

 

<script type="text/javascript">
  function AddLeave()
 {    
    $('#ticket-service-modal').modal('show');
    //$('.add-title').addClass('hide');
 }
  function editleave_quota(id,category,quota,rate,carry,gender,marital)
 {
    $("#leaveid").val(id);
    $("#leavename").val(category);
    $("#leavequota").val(quota); 
    $("#leave_rate").val(rate); 
    $("#leave_carry").val(carry); 
    $("#leave_gender").val(gender); 
    $("#leave_marital").val(marital);     
    $('#edit-Leave-quota-modal').modal('show');
 }
 
  function editholiday_quota(id,name,leavetype,quota,leave_end_date)
 {
    $("#id").val(id);
    $("#name").val(name);
   // $("#leave_type").val(leave_type).prop("checked", true);
   
    $("input[name=leave_type][value=" + leavetype + "]").prop('checked', true);
    $("#start_date").val(quota);
   //$()
  // $(".leave_type"+leave_type).prop("checked", true)
   // alert(leave_end_date);
    
    if(leavetype == "multiple_days"){
        $("#dvPassport").css("display", "block");
    }else{
        $("#dvPassport").css("display", "none");
    }
   // $("#single_day_start_date").val(quota); 
    $("#end_date").val(leave_end_date);  
    $('#edit-holiday-modal').modal('show');
 }
 

 function addleaveCategory()
 {
   $('#add-Leave-quota-modal').modal('show');
 }
 
  function addHoliday()
 {
   $('#add-holiday-modal').modal('show');
 }
</script>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                   
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#pending_application" data-toggle="tab">Leave Application</a>
                        </li>
                        <li class=""><a href="#pending_approval" data-toggle="tab">Leave Category</a>
                        </li>
                        <li class=""><a href="#holidays" data-toggle="tab">Holidays</a>
                        </li>
                        <li class="pull-right">
                          <a href="import_holidays" class="bg-info "   >Import Holidays</a>
                        </li>
                        <li class="pull-right">
                          <a href="#" class="bg-info "  onclick="addHoliday()" >Add Holidays</a>
                        </li>
                        <li class="pull-right">
                          <a href="#" class="bg-info "  onclick="addleaveCategory()" >Add Leave Category</a>
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

                <div class="tab-pane" id="pending_approval" style="position: relative;">
                  <div class="panel panel-custom">
                    <div class="panel-body">
                            <div class="table-responsive">
                                <div id="DataTables_wrapper" class="dataTables_wrapper form-inline no-footer">
                                 <!--  <div class="dataTables_length" id="DataTables_length"><label>
                                    <select name="DataTables_length" aria-controls="DataTables" class="form-control input-sm"><option value="10">10</option><option value="20">20</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
                                  </label>
                                </div> -->
                                <!-- <div id="DataTables_filter" class="dataTables_filter">
                                <label>Search all columns:
                                <input type="search" class="form-control input-sm" placeholder="" aria-controls="DataTables">
                                </label></div> -->

                                  <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                                    <thead>
                                    <tr role="row">
                                      <th style="width: 154px;" >Name</th>

                                      <th style="width: 210px;">leave Quota</th>

                                     <th>Leave Rate</th>
                                     <th>Carry Forward</th>
                                 
                                      
                                      <th style="width: 161px;"  colspan="2">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                    
                                     <?php foreach($leaveCategory as $category) { ?>
                                       <tr class="odd">
                                      <td ><?php echo  $category->leave_category; ?></td>
                                      <td><?php echo  $category->leave_quota; ?></td>
                                      <td><?php if($category->leave_rate=='1'){ echo 'Per Year'; }else if($category->leave_rate=='2'){echo  'Per Month';}else{ echo 'Once in A life'; }  ?></td>
                                      <td><?php if($category->leave_carry=='1'){ echo 'Yes'; }else if($category->leave_carry=='0'){echo  'No';}else{ echo ''; }  ?></td>
                                      <td colspan="2">
                                       <a href="#" title="edit-Leave" onclick="editleave_quota('<?php echo  $category->leave_category_id; ?>','<?php echo  $category->leave_category; ?>','<?php echo  $category->leave_quota; ?>','<?php echo $category->leave_rate;?>','<?php echo  $category->leave_carry; ?>','<?php echo  $category->leave_gender; ?>','<?php echo  $category->leave_marital; ?>')"  data-name="Via Excel Sheet" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>

                                        <a href="<?php echo  base_url(); ?>admin/leave/deleteleavecategory/<?php echo  $category->leave_category_id; ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>

                                       </td>

                                    </tr>     
                                    <?php }?>

                                    </tbody>
                                </table>
                               
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane active" id="pending_application" style="position: relative;">
                  <div class="panel panel-custom">
                    <div class="panel-body">
                            <div class="table-responsive">
                                <div id="DataTables_wrapper" class="dataTables_wrapper form-inline no-footer">
                                 <!--  <div class="dataTables_length" id="DataTables_length"><label>
                                    <select name="DataTables_length" aria-controls="DataTables" class="form-control input-sm"><option value="10">10</option><option value="20">20</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
                                  </label>
                                </div> -->
                                <!-- <div id="DataTables_filter" class="dataTables_filter">
                                <label>Search all columns:
                                <input type="search" class="form-control input-sm" placeholder="" aria-controls="DataTables">
                                </label></div> -->

                                  <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                                    <thead>
                                    <tr role="row">
                                      <th style="width: 154px;" >Name</th>

                                      <th style="width: 210px;">leave date</th>
                                     <!--  <th style="width: 210px;">leave Category</th> -->
                                     <th>Duration</th> 
                                     <th>Details</th> 
                                    
                                 
                                      
                                      <th style="width: 161px;"  colspan="2">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                    
                                     <?php foreach($leaveAppplication as $application) {
                                          $this->db->where('staffid',$application->user_id); 
                                          $result=$this->db->get('tblstaff')->result();
                                          //echo $result[0]['firstname'].' '.$lastname;  

                                      ?>
                                       <tr class="odd" id="rowid_<?php echo $application->leave_application_id; ?>">
                                      <td ><?php echo $result[0]->firstname.' '.$result[0]->lastname; ?></td>
                                     
                                      <td> <?php if( $application->leave_type == "multiple_days"){ echo date('j M, Y',strtotime($application->leave_start_date)).' -To- '.date('j M,Y',strtotime($application->leave_end_date)); } else { echo   date('j M, Y',strtotime($application->leave_start_date)); }  ?>
                                     </td>
                                     <!--  <td><?php echo  $application->leave_category_id; ?></td> -->
                                       <td><?= $application->duration ?></td>
                                      <td><!-- <?php echo  $application->reason;   ?></ -->
                                     
                                         <a href="#" title="edit-Leave" onclick="leavedetails('<?php echo  $application->leave_application_id; ?>')"  data-name="Via Excel Sheet" class="btn btn-default btn-icon"><i class="fa fa-eye"></i></a>
                                         </td>
										 

                                      <td colspan="2">
                                        <?php if($application->application_status=='1'){?>
                                           <button  style="cursor: none;" class="btn btn-warning btn-xs appstatus btn-icon _delete">Pending</button>
                                        <?php 
                                      }else if($application->application_status=='2'){?>
                                           <button style="cursor: none;"  class="btn btn-success appstatus btn-icon">Approved</button>
                                      <?php }else if($application->application_status=='4'){?>
                                           <button style="cursor: none;"  class="btn btn-success appstatus btn-icon">Sanctioned</button>
                                      <?php }else{?>
                                         <button  style="cursor: none;" class="btn btn-danger btn-xs appstatus btn-icon _delete">Rejected</button>
                                      <?php }?>
                                        

                                       </td>
                                    
                                    </tr>     
                                    <?php }?>

                                    </tbody>
                                </table>

                               

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="tab-pane" id="holidays" style="position: relative;">
                  <div class="panel panel-custom">
                    <div class="panel-body">
                            <div class="table-responsive">
                                <div id="DataTables_wrapper" class="dataTables_wrapper form-inline no-footer">
                                 
                            <div class="col-md-2 well" style="width: 100%;">
                        <span class="rows_selected" id="select_count">0 Selected</span>
                        <a type="button" id="delete_records" class="btn btn-primary pull-right">Delete</a>
                        </div>
                                  <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="holidays_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                                    <thead>
                                    <tr role="row">
                                        <th><input type="checkbox" id="select_all"></th>
                                      <th style="width: 154px;" >Name</th>

                                      <th style="width: 210px;">Leave Type</th>
                                      
                                      <th style="width: 210px;">Start Date</th>
                                      
                                      <th style="width: 210px;">End Date</th>
                                      
                                      <th style="width: 210px;">Duration</th>
                                 
                                      
                                      <th style="width: 161px;"  colspan="2">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                    
                                     <?php foreach($holidays as $holi) { ?>
                                       <tr class="">
                                           <td><input type="checkbox" class="emp_checkbox" data-emp-id="<?php echo $holi->id; ?>"/></td>
                                      <td ><?php echo  $holi->name; ?></td>
                                      <td><?php if( $holi->leave_type == "multiple_days"){ echo "Multiple Days"; } else { echo  "Single Day"; }  ?></td>
                                      <td><?php echo  $holi->quota; ?></td>
                                      <td><?php   if( $holi->leave_type == "multiple_days"){ echo $holi->leave_end_date; } else { echo  "N/A"; } ?></td>
                                      <td><?php echo  $holi->days; ?></td>
                                      <td colspan="2">
                                       
                                        <a href="#" title="edit-Leave" onclick="editholiday_quota('<?php echo  $holi->id; ?>','<?php echo  $holi->name; ?>','<?php echo  $holi->leave_type; ?>','<?php echo  $holi->quota; ?>','<?php echo  $holi->leave_end_date; ?>')"  data-name="Via Excel Sheet" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                                        <a href="<?php echo  base_url(); ?>admin/leave/deleteholiday/<?php echo  $holi->id; ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>

                                       </td>

                                    
                                   
                                      
                                    
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
                              <input type="text" id="leavename" placeholder="Enter Category Name * " name="leavename" class="form-control"  value="">
                              <input type="hidden" id="leaveid" name="leaveid" class="form-control"  value="">
                            </div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Quota</label>
                              <input type="text" id="leavequota" placeholder="Enter Quota * "  name="leavequota" class="form-control"  value="">
                            </div> 
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Rate</label>
                              <select name="leave_rate" style="width: 100%" class="form-control select_box select2-hidden-accessible" id="leave_rate" required="" data-parsley-id="4" tabindex="-1" aria-hidden="true">
                                    <option value="">Select Rate -*-</option>
                                    <option value="1" selected > Per Year</option>
                                    <option value="2">Per Month</option>
                                    <option value="0">Once In A Life Time</option>
                              </select>
                            </div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Carry Forward</label>
                              <select name="leave_carry" style="width: 100%" class="form-control select_box select2-hidden-accessible" id="leave_carry" required="" data-parsley-id="4" tabindex="-1" aria-hidden="true">
                            
                                  <option value="">Carry Forward -*-</option>
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                            </select>
                            </div>
                            
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">For Gender (Optional)</label>
                              <select name="leave_gender" style="width: 100%" class="form-control select_box select2-hidden-accessible" id="leave_gender"  data-parsley-id="4" tabindex="-1" aria-hidden="true">
                            
                                  <option value="">Gender -*-</option>
                                  <option value="Female">Female</option>
                                  <option value="Male">Male</option>
                            </select>
                            </div>
                            
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">For Marital Status (Optional)</label>
                              <select name="leave_marital" style="width: 100%" class="form-control select_box select2-hidden-accessible" id="leave_marital"  data-parsley-id="4" tabindex="-1" aria-hidden="true">
                            
                                  <option value="">Marital Status -*-</option>
                                  <option value="Married">Married</option>
                                  <option value="Unmarried">Unmarried</option>
                            </select>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div><!-- /.modal-content -->
            </form>        

          </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<div class="modal fade" id="add-Leave-quota-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form autocomplete="off" action="<?php echo  base_url(); ?>admin/leave/addleavecategory" id="ticket-service-form" method="post" accept-charset="utf-8">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title">Add Leave Category</span>
                        <!-- <span class="add-title">New Product</span> -->
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="additional"></div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Leave Name</label>
                              <input type="text" id="leavename" placeholder="Enter Leave Name * " name="leavename" class="form-control"  value="">
                             
                            </div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Quota</label>
                              <input type="text" placeholder="Enter Quota * " id="leavequota" name="leavequota" class="form-control"  value="">
                            </div> 
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Rate</label>
                              <select name="leave_rate" style="width: 100%" class="form-control select_box select2-hidden-accessible" id="" required="" data-parsley-id="4" tabindex="-1" aria-hidden="true">
                                    <option value="">Select Rate -*-</option>
                                    <option value="1">Per Year</option>
                                    <option value="2">Per Month</option>
                                    <option value="0">Once In A LifeTime</option>
                              </select>
                            </div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Carry Forward</label>
                              <select name="leave_carry" style="width: 100%" class="form-control select_box select2-hidden-accessible" id="" required="" data-parsley-id="4" tabindex="-1" aria-hidden="true">
                            
                                  <option value="">Carry Forward -*-</option>
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                            </select>
                            </div>
                            
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">For Gender</label>
                              <select name="leave_gender" style="width: 100%" class="form-control select_box select2-hidden-accessible" id="leave_gender"  data-parsley-id="4" tabindex="-1" aria-hidden="true">
                            
                                  <option value="">Gender -*-</option>
                                  <option value="Female">Female</option>
                                  <option value="Male">Male</option>
                            </select>
                            </div>
                            
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">For Marital Status</label>
                              <select name="leave_marital" style="width: 100%" class="form-control select_box select2-hidden-accessible" id="leave_marital"  data-parsley-id="4" tabindex="-1" aria-hidden="true">
                            
                                  <option value="">Marital Status -*-</option>
                                  <option value="Married">Married</option>
                                  <option value="Unmarried">Unmarried</option>
                            </select>
                            </div>


                            



                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div><!-- /.modal-content -->
            </form>        

          </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<div class="modal fade" id="add-holiday-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form autocomplete="off" action="<?php echo  base_url(); ?>admin/leave/addholiday" id="ticket-service-form" method="post" accept-charset="utf-8">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title">Add Holiday</span>
                        <!-- <span class="add-title">New Product</span> -->
                    </h4>
                </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                    <div id="additional"></div>
                                                    <div class="form-group" app-field-wrapper="name">
                                                      <label for="name" class="control-label">Holiday For</label>
                                                      <input type="text" id="leavename" placeholder="Enter Holiday Name * " name="leavename" class="form-control"  value="">
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-sm-4 control-label">Duration <span class="required"> *</span></label>
                                                                <div class="col-sm-8">
                                                                    <label class="radio-inline c-radio">
                                                                        <input type="radio" name="leave_type" value="single_day" checked="" data-parsley-multiple="leave_type" data-parsley-id="7" >
                                                                        Single day
                                                                    </label>
                                                                    <label class="radio-inline c-radio">
                                                                        <input type="radio" name="leave_type" value="multiple_days" data-parsley-multiple="leave_type">
                                                                       Multiple days
                                                                    </label>
                                                                </div>
                                                        </div>
                                                    </div>



                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-sm-4 control-label">Start Date 
                                                              <span class="required"> *</span></label>
                                                                <div class="col-sm-8">
                                                                    <div class="input-group">
                                                                        <input type="text" name="single_day_start_date" id="start_date" class="form-control datepicker" value="" data-format="dd-mm-yyyy" data-parsley-id="13" placeholder="Select A Date..">
                                                                        <div class="input-group-addon">
                                                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div id="multiple_days" style="display: none;">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">End Date <span class="required"> *</span></label>
                                                                    <div class="col-sm-8">
                                                                        <div class="input-group">
                                                                            <input type="text" name="leave_end_date" id="end_date"  class="form-control datepicker" value="" data-format="dd-mm-yyyy" data-parsley-id="17" placeholder="Select End Date..">
                                                                            <div class="input-group-addon">
                                                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                            
                                            </div>
                                        </div>
                                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div><!-- /.modal-content -->
            </form>        

          </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!--- edit holiday -->
    <div class="modal fade" id="edit-holiday-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form autocomplete="off" action="<?php echo  base_url(); ?>admin/leave/updateholiday" id="ticket-service-form" method="post" accept-charset="utf-8">
            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">
                                        <span class="edit-title">Update Holiday</span>
                                        <!-- <span class="add-title">New Product</span> -->
                                    </h4>
                                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="additional"></div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Holiday For</label>
                              <input type="text" id="name" name="leavename" class="form-control"  value="">
                              <input type="hidden" id="id" name="leaveid" class="form-control"  value="">
                             
                            </div>
                           <div class="form-group">
                           <div class="row">
                            <label class="col-sm-4 control-label">Duration <span class="required"> *</span></label>
                            <div class="col-sm-8">
                                <label class="radio-inline c-radio">
                                    <input type="radio"   name="leave_type" id="singlchk"  value="single_day"  >
                                    Single day</label>
                                <label class="radio-inline c-radio">
                                    <input type="radio"    name="leave_type"  id="chkPassport" value="multiple_days" >
                                   Multiple days</label>
                          
                          </div>
                          </div>
                        </div>



                        <div id="singlec">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Start Date 
                                        <span class="required"> *</span></label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" name="single_day_start_date" id="start_date"  class="form-control datepicker" value="" data-format="yyyy-mm-dd"  >
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                </div>
                            </div>
                        </div>
                            <div id="dvPassport" style="display: none;">
                                <div class="form-group">
                                    <div class="row">
                                    <label class="col-sm-4 control-label">End Date <span class="required"> *</span></label>

                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" name="leave_end_date" id="end_date"  class="form-control datepicker" value="" data-format="yyyy-mm-dd"  >
                                                <div class="input-group-addon">
                                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div><!-- /.modal-content -->
            </form>        

          </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>
    <!---  edit holiday end --->


<div class="modal fade" id="application-Details-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title">Leave Reason details</span>
                        <!-- <span class="add-title">New Product</span> -->
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                           
                            
                            
                            <div class="table-responsive">
                                <div id="applicationdetails" class="dataTables_wrapper form-inline no-footer">
                             <table>
                                    <thead>
                                    <tr>
                                      <th style="width: 154px;" >Name</th>
                                      <th style="width: 210px;">leave Quota</th>
                                    </tr>
                                    </thead>
                                    
                                    <tbody>
                                    
                                     <?php foreach($leaveappliaction as $category) { ?>
                                       <tr >
                                      <td ><?php echo  $category->leave_type; ?></td>
                                      <td><?php echo  $category->reason; ?></td>
                                      </tr>     
                                    <?php }?>

                                    </tbody>
                                </table>
                               
                               

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sx" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<script id="hidden-columns-table-leads" type="text/json">
   <?php echo get_staff_meta(get_staff_user_id(), 'hidden-columns-table-leads'); ?>
</script>
<?php include_once(APPPATH.'views/admin/leads/status.php'); ?>
<?php init_tail(); ?>
<script>
  

  $(document).ready(function() {

    $('#pending_approval_table').DataTable();
    $('#holidays_table').DataTable();
     $('#my_leave_table').DataTable();
     $('#leave_report_table').DataTable();

    } );

$('.radio-inline.c-radio input[type="radio"]').click(function(){
   
    if($(this).is(':checked'))
    {
      $val=$(this).val();
      if($val=='single_day')
      {
        $('#single_day').css('display','block'); 
        $('#multiple_days').css('display','none'); 
        $('#hours').css('display','none');  
      }
      if($val=='multiple_days')
      {
        $('#single_day').css('display','none'); 
        $('#multiple_days').css('display','block'); 
        $('#hours').css('display','none'); 
      }
      if($val=='hours')
      {
        $('#single_day').css('display','none'); 
        $('#multiple_days').css('display','none'); 
        $('#hours').css('display','block'); 
      }

    }
  });



 
 function leavesave()
 {
   var leave_category_id=$('#leave_category_id').val();
   var duration=$(".radio-inline.c-radio input[name='leave_type']:checked").val();
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
 
 $('#Save-leave-form').submit(function(e){ 
      e.preventDefault(); 
 //alert()
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
            
            


       
 }); 

 function leavedetails(applicationid)
 {
     $.ajax({
        url: "<?php echo base_url(); ?>admin/leave/getApplicationdetails", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: {applicationId:applicationid}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        success: function(data)   // A function to be called if request succeeds
        {
          //  alert(data);
          $("#applicationdetails").html(data);
          $('#application-Details-modal').modal('show');
        }
        });
 }
 function togglestatus(applicationId)
 {
     
     $.ajax({
        url: "<?php echo base_url(); ?>admin/leave/togglestatus", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: {AppId:applicationId}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        success: function(data)   // A function to be called if request succeeds
        {
          //alert(data); 
          $("#rowid_"+applicationId+" .appstatus").html(data);
          if(data=='Approved')
          {
             $("#rowid_"+applicationId+" .appstatus").removeClass('btn-danger');
             $("#rowid_"+applicationId+" .appstatus").addClass('btn-success');
          }
          else
          {
            $("#rowid_"+applicationId+" .appstatus").removeClass('btn-success');
            $("#rowid_"+applicationId+" .appstatus").addClass('btn-danger');
          }
          //$('#application-Details-modal').modal('show');
        }
        });
   }



</script>

<script>
    $(function () {
        $("#chkPassport").click(function () {
            if ($(this).is(":checked")) {
                $("#dvPassport").show();
            } else {
                $("#dvPassport").hide();
            }
        });
    });
</script>

<script>
$(function(){
 $('#start_date').datetimepicker({
  format:'Y-m-d',
  minDate:'0',
  onShow:function( ct ){
   this.setOptions({
    maxDate:$('#end_date').val()?$('#end_date').val():false
   })
  },
  timepicker:false
 });
 $('#end_date').datetimepicker({
  format:'Y-m-d',
  onShow:function( ct ){
   this.setOptions({
    minDate:$('#start_date').val()?$('#start_date').val():false
   })
  },
  timepicker:false
 });
});
</script>

<script>
$(document).on('click', '#select_all', function() {
$(".emp_checkbox").prop("checked", this.checked);
$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
});
$(document).on('click', '.emp_checkbox', function() {
if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
$('#select_all').prop('checked', true);
} else {
$('#select_all').prop('checked', false);
}
$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
});



// delete selected records
$('#delete_records').on('click', function(e) {
var employee = [];
$(".emp_checkbox:checked").each(function() {
employee.push($(this).data('emp-id'));
});
if(employee.length <=0) { alert("Please select Schemes."); } else { WRN_PROFILE_DELETE = "Are you sure you want to delete "+(employee.length>1?"these":"this")+" row?";
var checked = confirm(WRN_PROFILE_DELETE);
if(checked == true) {
var selected_values = employee.join(",");
$.ajax({
type: "POST",
url: "<?php echo base_url(); ?>admin/leave/deleteholibulk",
cache:false,
data: 'emp_id='+selected_values,
success: function(response) {
// remove deleted employee rows
window.location.reload();
var emp_ids = response.split(",");
for (var i=0; i < emp_ids.length; i++ ) { $("#"+emp_ids[i]).remove(); } } }); } } });
</script>



</body>
</html>
