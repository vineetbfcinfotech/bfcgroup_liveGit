<?php init_head(); ?>
<div id="wrapper">
    <?php $CI=&get_instance(); ?>
<div class="content">
   <div class="row">
       <?php //echo "<pre>"; print_r($member);exit; ?>
      <?php if(isset($member)){ ?>
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body no-padding-bottom">
                  <?php $this->load->view('admin/staff/stats'); ?>
               </div>
            </div>
         </div>
         <div class="member">
            <?php echo form_hidden('isedit'); ?>
            <?php echo form_hidden('memberid',$member->staffid); ?>
         </div>
      <?php } ?>
      <?php if(isset($member)){ ?>
         <div class="col-md-12">
            <?php if(total_rows('tbldepartments',array('email'=>$member->email)) > 0) { ?>
               <div class="alert alert-danger">
                  The staff member email exists also as support department email, according to the docs, the support department email must be unique email in the system, you must change the staff email or the support department email in order all the features to work properly.
               </div>
            <?php } ?>
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="no-margin"><?php echo $member->firstname . ' ' . $member->lastname; ?>
                     <?php if($member->last_activity && $member->staffid != get_staff_user_id()){ ?>
                     <small> - <?php echo _l('last_active'); ?>:
                           <span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($member->last_activity); ?>">
                                 <?php echo time_ago($member->last_activity); ?>
                           </span>
                        </small>
                     <?php } ?>
                     <a href="#" onclick="small_table_full_view(); return false;" data-placement="left" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>" class="toggle_view pull-right">
                     <i class="fa fa-expand"></i></a>
                  </h4>
               </div>
            </div>
         </div>
      <?php } ?>
      <?php echo form_open_multipart($this->uri->uri_string(),array('class'=>'staff-form','autocomplete'=>'off')); ?>
      <div class="col-md-<?php if(!isset($member)){echo '8 col-md-offset-2';} else {echo '5';} ?>" id="small-table">
         <div class="panel_s">
            <div class="panel-body">
                
               <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active">
                     <a href="#tab_staff_profile" aria-controls="tab_staff_profile" role="tab" data-toggle="tab">
                     <?php echo _l('staff_profile_string'); ?>
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#tab_staff_permissions" aria-controls="tab_staff_permissions" role="tab" data-toggle="tab">
                     <?php echo _l('staff_add_edit_permissions'); ?>
                     </a>
                  </li>
               </ul>
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                      <?php //echo "<pre>"; print_r($member->company);exit; ?>
                      <div class="form-group" app-field-wrapper="company"><label for="company" class="control-label">Company</label>
                            <select id="company" name="company[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple>
                                <option value=""></option>
								<?php $array_company = explode(",",$member->company); //print_r($array_company);exit;?>
                                <option <?php if(in_array("1", $array_company)) echo "selected"; ?> value="1">BFC Capital</option>
                                <option <?php if(in_array("2", $array_company)) echo "selected"; ?> value="2">BFC Infotech</option>
                                <option <?php if(in_array("3", $array_company)) echo "selected"; ?> value="3">BFC Publications</option>
                            </select>  
                        </div> 
                      <?php //echo "test"; print_r($teamdetails);exit; ?> 
                    <?php 
                    if(isset($member)){
                        echo sprintf('<input type="hidden" name="staffdepartmentid" value="%s">',$teamdetails->staffdepartmentid);
                        //echo render_select('departments',$departments,array('departmentid','name'),'staff_add_edit_departments',$teamdetails->departmentid);
                        //echo render_select('team_id',$teams,array('id','team_name'),'Team Name',$teamdetails->team_id);
                        //echo render_select('role_id',$oldroles,array('role_id','name'),'staff_add_edit_role',$teamdetails->role_id);
                        //echo render_select('rm_id',$oldrmlist,array('id','full_name'),'Reportig Manager',$teamdetails->rm_id);
                    }else{ ?>
                        <?php //echo render_select('departments',$departments,array('departmentid','name'),'staff_add_edit_departments'); ?>
						 
                    <?php }  ?>
					<div class="form-group" app-field-wrapper="departments">
							<label for="departments" class="control-label"> <small class="req text-danger">* </small>Member departments</label>
							<div class="dropdown bootstrap-select dropup" style="width: 100%;">
							   <select id="departments" name="departments[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple="">
							   <?php
								$department = $CI->department_name($member->department_id);
								foreach($department as $depart){
								?>
								<option value="<?php echo $depart->departmentid; ?>" selected ><?php echo $depart->name; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
						<?php //echo $member->member_team; exit;?>
                        <div class="form-group" app-field-wrapper="team_id"><label for="team_id" class="control-label">Team</label>
                            <select id="team_id" name="team_id[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple="">
							<?php $teams = $CI->team_name($member->member_team);
							foreach($teams as $team){
								 ?>
								<option value="<?php echo $team->id; ?>" selected ><?php echo $team->team_name; ?></option>
								<?php } ?>
                            </select>  
                        </div>  
                        <div class="form-group" app-field-wrapper="role_id"><label for="role_id" class="control-label"><?= _l('staff_add_edit_role'); ?></label>
                            <select id="role_id" name="role_id[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple="">
							<?php $roleall = $CI->role_name($member->role);
							foreach($roleall as $roless){
								 ?>
								<option value="<?php echo $roless->roleid; ?>" selected ><?php echo $roless->name; ?></option>
								<?php } ?>
                            </select>  
                           
                        </div> 
                        
                        <div class="form-group" app-field-wrapper="rm_id"><label for="rm_id" class="control-label"><?= _l('Reportig Manager'); ?></label>
                            <select id="rm_id" name="rm_id[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple="">
							<?php
							$reportings = $CI->reportings($member->reporting_manager);
							foreach($reportings as $reporting){
								 ?>
								<option value="<?php echo $reporting->staffid; ?>" selected ><?php echo $reporting->firstname." ". $reporting->lastname; ?></option>
								<?php } ?>
                            </select>  
                        </div> 
						<div class="form-group" app-field-wrapper="work_group"><label for="work_group" class="control-label"><?= _l('Working Category'); ?></label>
                            <select id="work_group" name="work_group" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" >
								<option value="A" <?php if($member->work_group == "A"){ echo "Selected"; } ?> >Group A</option>
								<option value="B" <?php if($member->work_group == "B"){ echo "Selected"; } ?> >Group B</option>
                            </select>  
                        </div> 
						
                    <?php if((isset($member) && $member->profile_image == NULL) || !isset($member)){ ?>
                    <div class="form-group">
                        <label for="profile_image" class="profile-image"><?php echo _l('staff_edit_profile_image'); ?></label>
                        <input type="file" name="profile_image" class="form-control" id="profile_image">
                    </div>
                    <?php } ?>
                     <?php if(isset($member) && $member->profile_image != NULL){ ?>
                     <div class="form-group">
                        <div class="row">
                           <div class="col-md-9">
                              <?php echo staff_profile_image($member->staffid,array('img','img-responsive','staff-profile-image-thumb'),'thumb'); ?>
                           </div>
                           <div class="col-md-3 text-right">
                              <a href="<?php echo admin_url('staff/remove_staff_profile_image/'.$member->staffid); ?>"><i class="fa fa-remove"></i></a>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
					 <?php $value = (isset($member) ? $member->bio_id : ''); ?>
					 <?php echo render_input('bio_id','staff_add_edit_bio_id',$value,'text',$attrs); ?>
                     <?php $value = (isset($member) ? $member->firstname : ''); ?>
                     <?php $attrs = (isset($member) ? array() : array('autofocus'=>true)); ?>                     
                     <?php echo render_input('firstname','staff_add_edit_firstname',$value,'text',$attrs); ?>
                     <?php $value = (isset($member) ? $member->lastname : ''); ?>
                     <?php echo render_input('lastname','staff_add_edit_lastname',$value); ?>
                     <?php $value = (isset($member) ? $member->email : ''); ?>
                     <?php echo render_input('email','staff_add_edit_email',$value,'email',array('autocomplete'=>'off')); ?>
                     <?php $value = (isset($member) ? $member->phonenumber : ''); ?>
                     <?php echo render_input('phonenumber','staff_add_edit_phonenumber',$value,'number', array('pattern'=>'[0-9]{10}', 'maxlength'=>'10', 'minlength'=>'10'));  ?>
                     <?php $value = (isset($member) ? $member->alternate_phonenumber : ''); ?>
                     <?php echo render_input('alternate_phonenumber','staff_add_edit_alternate_phonenumber',$value, 'number', array('pattern'=>'[0-9]{10}', 'maxlength'=>'10', 'minlength'=>'10'));  ?>
					 <?php $value = (isset($member) ? $member->emergency_number : ''); ?>
                     <?php echo render_input('emergency_number','staff_add_edit_emergency_number',$value, 'number', array('pattern'=>'[0-9]{10}', 'maxlength'=>'10', 'minlength'=>'10'));  ?>
                     
                     <div class="form-group">
                        <label for="gender" class="control-label">Gender</label>
                        <select required  id="gender" <?php if(has_permission('staff','','edit')){ ?> name="gender"<?php } else { ?> disabled="true"<?php } ?> class="form-control selectpicker" >
                            <option <?php if($member->gender == '') echo "selected"; ?> value="">Select Gender</option>
                            <option <?php if($member->gender == 'Male') echo "selected"; ?> value="Male">Male</option>
                            <option <?php if($member->gender == 'Female') echo "selected"; ?> value="Female">Female</option>
                            
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="gender" class="control-label">Marital Status</label>
                        <select required  id="marital_status" <?php if(has_permission('staff','','edit')){ ?> name="marital_status"<?php } else { ?> disabled="true"<?php } ?> class="form-control selectpicker" >
                            <option <?php if($member->marital_status == '') echo "selected"; ?> value="">Select Marital Status</option>
                            <option <?php if($member->marital_status == 'Married') echo "selected"; ?> value="Married">Married</option>
                            <option <?php if($member->marital_status == 'Unmarried') echo "selected"; ?> value="Unmarried">Unmarried</option>
                            
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="birth_date" class="control-label">Date OF Birth</label>
                        <input required  value="<?= $member->birth_date; ?>" id="birth_date" <?php if(has_permission('staff','','edit')){ ?> name="birth_date"<?php } else { ?> disabled="true"<?php } ?> class="form-control datepicker" readonly style="cursor: pointer;">
                          
                    </div>
                    
                    <div class="form-group">
                        <label for="gender" class="control-label">Date of confirmation</label>
                        <input  value="<?= $member->confirmation_date; ?>"  id="confirmation_date" <?php if(has_permission('staff','','edit')){ ?> name="confirmation_date"<?php } else { ?> disabled="true"<?php } ?> class="form-control datepicker" readonly style="cursor: pointer;">
                            
                    </div>
                     <?php $value = (isset($member) ? $member->street_address : ''); ?>
                     <?php echo render_input('street_address','staff_add_edit_street_address',$value); ?>
                     <?php $value = (isset($member) ? $member->city : ''); ?>
                     <?php echo render_input('city','staff_add_edit_city',$value); ?>
                     <?php $value = (isset($member) ? $member->postal_code : ''); ?>
                     <?php echo render_input('postal_code','staff_add_edit_postal_code',$value, 'number', array('pattern'=>'[0-9]{6}', 'maxlength'=>'6', 'minlength'=>'6'));  ?>
                     <?php $rel_id = (isset($member) ? $member->staffid : false); ?>
                     <?php  render_custom_fields('staff',$rel_id); ?>
					 <?php if($member->staffid > 0) {?>
					 
					 <div class="form-group">
                        <label for="notice_date" class="control-label">Employee Notice Date</label>
                        <input  value="<?= $member->notice_date; ?>"  id="notice_date" <?php if(has_permission('staff','','edit')){ ?> name="notice_date"<?php } else { ?> disabled="true"<?php } ?> class="form-control datepicker" >
                    </div>
					 
					 <div class="form-group">
                        <label for="exit_date" class="control-label">Employee Releaving Date</label>
                        <input  value="<?= $member->exit_date; ?>"  id="exit_date" <?php if(has_permission('staff','','edit')){ ?> name="exit_date"<?php } else { ?> disabled="true"<?php } ?> class="form-control datepicker" >
                    </div>
					<div class="form-group">
                        <label for="status1" class="control-label">Status</label>
						<select class="form-control" id="status1" name="active">
							<option value="1" <?php if($member->active == 1){ echo "selected"; }  ?> >Active</option>
							<option value="0" <?php if($member->active == 0){ echo "selected"; }  ?> >Inactive</option> 
						</select>
                        
                    </div>
					 <?php } ?>
                     
                     <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                     <input  type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1"/>
                     <input  type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1"/>
                     <div class="clearfix form-group"></div>
                      <label for="password" class="control-label"><?php echo "Sub Admin"; ?>
                         <input lass="form-control checkbox" type="checkbox" name="sub_admin" id="sub_admin" value="2" <?php echo ($member->admin==2 ? 'checked' : '');?>/>
                     </label>
                     <div class="clearfix form-group"></div>
                     <label for="password" class="control-label"><?php echo _l('staff_add_edit_password'); ?></label>
                     <div class="input-group">
                        <input type="password" class="form-control password" name="password" autocomplete="off">
                        <span class="input-group-addon">
                        <a href="#password" class="show_password" onclick="showPassword('password'); return false;"><i class="fa fa-eye"></i></a>
                        </span>
                        <span class="input-group-addon">
                        <a href="#" class="generate_password" onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
                        </span>
                     </div>
                     <?php if(isset($member)){ ?>
                     <p class="text-muted"><?php echo _l('staff_add_edit_password_note'); ?></p>
                     <?php if($member->last_password_change != NULL){ ?>
                     <?php echo _l('staff_add_edit_password_last_changed'); ?>:
                     <span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($member->last_password_change); ?>">
                        <?php echo time_ago($member->last_password_change); ?>
                     </span>
                     <?php } } ?>
                  </div>
                 <div role="tabpanel" class="tab-pane" id="tab_staff_permissions">
                     <?php
                        do_action('staff_render_permissions');
                        $selected = '';
                        foreach($roles as $role){
                           if(isset($member)){
                              if($member->role == $role['roleid']){
                                 $selected = $role['roleid'];
                              }
                           } else {
                              $default_staff_role = get_option('default_staff_role');
                                 if($default_staff_role == $role['roleid'] ){
                                 $selected = $role['roleid'];
                              }
                           }
                        }
                        ?>
                     <?php echo render_select('role',$roles,array('roleid','name'),'staff_add_edit_role',$selected); ?>
                     <hr />
                     <h4 class="font-medium mbot15 bold"><?php echo _l('staff_add_edit_permissions'); ?></h4>
                     <div class="table-responsive">
                        <table class="table table-bordered roles no-margin">
                           <thead>
                              <tr>
                                 <th class="bold"><?php echo _l('permission'); ?></th>
                                 <th class="text-center bold"><?php echo _l('permission_view'); ?> (<?php echo _l('permission_global'); ?>)</th>
                                 <th class="text-center bold"><?php echo _l('permission_view_own'); ?></th>
                                 <th class="text-center bold"><?php echo _l('permission_create'); ?></th>
                                 <th class="text-center bold"><?php echo _l('permission_edit'); ?></th>
                                 <th class="text-center text-danger bold"><?php echo _l('permission_delete'); ?></th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                                 if(isset($member)){
                                    $is_admin = is_admin($member->staffid);
                                    $is_staff_member = is_staff_member($member->staffid);
                                 }
                                 $conditions = get_permission_conditions();
                                 
                                 
                                 foreach($permissions as $permission){
                                  if($permission['shortname'] == 'leads' && isset($is_staff_member) && !$is_staff_member) {
                                       continue;
                                  }
                                  $permission_condition = $conditions[$permission['shortname']];
                                  ?>
                              <tr data-id="<?php echo $permission['permissionid']; ?>" data-name="<?php echo $permission['shortname']; ?>">
                                 <td>
                                    <?php echo _l($permission['shortname']); ?>
                                 </td>
                                 <td class="text-center">
                                    <?php if($permission_condition['view'] == true){
                                       $statement = '';
                                       if(isset($is_admin) && $is_admin || isset($member) && has_permission($permission['shortname'],$member->staffid,'view_own')){
                                        $statement = 'disabled';
                                       } else if(isset($member) && has_permission($permission['shortname'],$member->staffid,'view')){
                                        $statement = 'checked';
                                       }
                                       ?>
                                    <?php
                                       if(isset($permission_condition['help'])){
                                         echo '<i class="fa fa-question-circle text-danger" data-toggle="tooltip" data-title="'.$permission_condition['help'].'"></i>';
                                       }
                                       ?>
                                    <div class="checkbox">
                                       <input type="checkbox" data-can-view <?php echo $statement; ?> kkmkkm="kkmkkm" name="view[]" value="<?php echo $permission['permissionid']; ?>">
                                       <label></label>
                                    </div>
                                    <?php } ?>
                                 </td>
                                 <td class="text-center">
                                    <?php if($permission_condition['view_own'] == true){
                                       $statement = '';
                                       if(isset($is_admin) && $is_admin || isset($member) && has_permission($permission['shortname'],$member->staffid,'view')){
                                        $statement = 'disabled';
                                       } else if(isset($member) && has_permission($permission['shortname'],$member->staffid,'view_own')){
                                        $statement = 'checked';
                                       }
                                       ?>
                                    <div class="checkbox">
                                       <input type="checkbox" <?php echo $statement; ?> data-shortname="<?php echo $permission['shortname']; ?>" data-can-view-own name="view_own[]" value="<?php echo $permission['permissionid']; ?>">
                                       <label></label>
                                    </div>
                                    <?php } else if($permission['shortname'] == 'customers'){
                                       echo '<i class="fa fa-question-circle mtop5" data-toggle="tooltip" data-title="'._l('permission_customers_based_on_admins').'"></i>';
                                       } else if($permission['shortname'] == 'projects'){
                                       echo '<i class="fa fa-question-circle mtop15" data-toggle="tooltip" data-title="'._l('permission_projects_based_on_assignee').'"></i>';
                                       } else if($permission['shortname'] == 'tasks'){
                                       echo '<i class="fa fa-question-circle mtop15" data-toggle="tooltip" data-title="'._l('permission_tasks_based_on_assignee').'"></i>';
                                       } else if($permission['shortname'] == 'payments'){
                                       echo '<i class="fa fa-question-circle mtop5" data-toggle="tooltip" data-title="'._l('permission_payments_based_on_invoices').'"></i>';
                                       } ?>
                                 </td>
                                 <td class="text-center">
                                    <?php if($permission_condition['create'] == true){
                                       $statement = '';
                                       if(isset($is_admin) && $is_admin){
                                        $statement = 'disabled';
                                       } else if(isset($member) && has_permission($permission['shortname'],$member->staffid,'create')){
                                        $statement = 'checked';
                                       }
                                       ?>
                                    <div class="checkbox">
                                       <input type="checkbox" data-shortname="<?php echo $permission['shortname']; ?>" data-can-create <?php echo $statement; ?> name="create[]" value="<?php echo $permission['permissionid']; ?>">
                                       <label></label>
                                    </div>
                                    <?php } ?>
                                     <?php
                                       if(isset($permission_condition['help_create'])){
                                         echo '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="'.$permission_condition['help_create'].'"></i>';
                                       }
                                       ?>
                                 </td>
                                 <td  class="text-center">
                                    <?php if($permission_condition['edit'] == true){
                                       $statement = '';
                                       if(isset($is_admin) && $is_admin){
                                        $statement = 'disabled';
                                       } else if(isset($member) && has_permission($permission['shortname'],$member->staffid,'edit')){
                                        $statement = 'checked';
                                       }
                                       ?>
                                    <div class="checkbox">
                                       <input type="checkbox" data-shortname="<?php echo $permission['shortname']; ?>" data-can-edit <?php echo $statement; ?> name="edit[]" value="<?php echo $permission['permissionid']; ?>">
                                       <label></label>
                                    </div>
                                    <?php } ?>
                                     <?php
                                       if(isset($permission_condition['help_edit'])){
                                         echo '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="'.$permission_condition['help_edit'].'"></i>';
                                       }
                                       ?>

                                 </td>
                                 <td  class="text-center">
                                    <?php if($permission_condition['delete'] == true){
                                       $statement = '';
                                       if(isset($is_admin) && $is_admin){
                                         $statement = 'disabled';
                                       } else if(isset($member) && has_permission($permission['shortname'],$member->staffid,'delete')){
                                         $statement = 'checked';
                                       }
                                       ?>
                                    <div class="checkbox checkbox-danger">
                                       <input type="checkbox" data-shortname="<?php echo $permission['shortname']; ?>" data-can-delete <?php echo $statement; ?> name="delete[]" value="<?php echo $permission['permissionid']; ?>">
                                       <label></label>
                                    </div>
                                    <?php } ?>
                                 </td>
                              </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="btn-bottom-toolbar text-right btn-toolbar-container-out" style="width: calc(100% - 293px);">
         <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
      <?php if(isset($member)){ ?>
      <div class="col-md-7 small-table-right-col">
         <div class="panel_s">
            <div class="panel-body">
               <h4 class="no-margin">
                  <?php echo _l('staff_add_edit_notes'); ?>
               </h4>
               <hr class="hr-panel-heading" />
               <a href="#" class="btn btn-success" onclick="slideToggle('.usernote'); return false;"><?php echo _l('new_note'); ?></a>
               <div class="clearfix"></div>
               <hr class="hr-panel-heading" />
               <div class="mbot15 usernote hide inline-block full-width">
                  <?php echo form_open(admin_url('misc/add_note/'.$member->staffid . '/staff')); ?>
                  <?php echo render_textarea('description','staff_add_edit_note_description','',array('rows'=>5)); ?>
                  <button class="btn btn-info pull-right mbot15"><?php echo _l('submit'); ?></button>
                  <?php echo form_close(); ?>
               </div>
               <div class="clearfix"></div>
               <div class="mtop15">
                  <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                     <thead>
                        <tr>
                           <th width="50%"><?php echo _l('staff_notes_table_description_heading'); ?></th>
                           <th><?php echo _l('staff_notes_table_addedfrom_heading'); ?></th>
                           <th><?php echo _l('staff_notes_table_dateadded_heading'); ?></th>
                           <th><?php echo _l('options'); ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($user_notes as $note){ ?>
                        <tr>
                           <td width="50%">
                              <div data-note-description="<?php echo $note['id']; ?>">
                                 <?php echo check_for_links($note['description']); ?>
                              </div>
                              <div data-note-edit-textarea="<?php echo $note['id']; ?>" class="hide inline-block full-width">
                                 <textarea name="description" class="form-control" rows="4"><?php echo clear_textarea_breaks($note['description']); ?></textarea>
                                 <div class="text-right mtop15">
                                    <button type="button" class="btn btn-default" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                                    <button type="button" class="btn btn-info" onclick="edit_note(<?php echo $note['id']; ?>);"><?php echo _l('update_note'); ?></button>
                                 </div>
                              </div>
                           </td>
                           <td><?php echo $note['firstname'] . ' ' . $note['lastname']; ?></td>
                           <td data-order="<?php echo $note['dateadded']; ?>"><?php echo _dt($note['dateadded']); ?></td>
                           <td>
                              <?php if($note['addedfrom'] == get_staff_user_id() || has_permission('staff','','delete')){ ?>
                              <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
                              <a href="<?php echo admin_url('misc/delete_note/'.$note['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                              <?php } ?>
                           </td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         <div class="panel_s">
            <div class="panel-body">
               <h4 class="no-margin">
                  <?php echo _l('task_timesheets'); ?> & <?php echo _l('als_reports'); ?>
               </h4>
               <hr class="hr-panel-heading" />
               <?php echo form_open($this->uri->uri_string(),array('method'=>'GET')); ?>
               <?php echo form_hidden('filter','true'); ?>
               <div class="row">
                  <div class="col-md-6">
                     <div class="select-placeholder">
                        <select name="range" id="range" class="selectpicker" data-width="100%">
                           <option value="this_month" <?php if(!$this->input->get('range') || $this->input->get('range') == 'this_month'){echo 'selected';} ?>><?php echo _l('staff_stats_this_month_total_logged_time'); ?></option>
                           <option value="last_month" <?php if($this->input->get('range') == 'last_month'){echo 'selected';} ?>><?php echo _l('staff_stats_last_month_total_logged_time'); ?></option>
                           <option value="this_week" <?php if($this->input->get('range') == 'this_week'){echo 'selected';} ?>><?php echo _l('staff_stats_this_week_total_logged_time'); ?></option>
                           <option value="last_week" <?php if($this->input->get('range') == 'last_week'){echo 'selected';} ?>><?php echo _l('staff_stats_last_week_total_logged_time'); ?></option>
                           <option value="period" <?php if($this->input->get('range') == 'period'){echo 'selected';} ?>><?php echo _l('period_datepicker'); ?></option>
                        </select>
                     </div>
                     <div class="row mtop15">
                        <div class="col-md-12 period <?php if($this->input->get('range') != 'period'){echo 'hide';} ?>">
                           <?php echo render_date_input('period-from','',$this->input->get('period-from')); ?>
                        </div>
                        <div class="col-md-12 period <?php if($this->input->get('range') != 'period'){echo 'hide';} ?>">
                           <?php echo render_date_input('period-to','',$this->input->get('period-to')); ?>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-2 text-right">
                     <button type="submit" class="btn btn-success apply-timesheets-filters"><?php echo _l('apply'); ?></button>
                  </div>
               </div>
               <?php echo form_close(); ?>
               <hr class="hr-panel-heading" />
                  <table class="table dt-table scroll-responsive">
                     <thead>
                        <th><?php echo _l('task'); ?></th>
                        <th><?php echo _l('timesheet_start_time'); ?></th>
                        <th><?php echo _l('timesheet_end_time'); ?></th>
                        <th><?php echo _l('task_relation'); ?></th>
                        <th><?php echo _l('staff_hourly_rate'); ?> (<?php echo _l('als_staff'); ?>)</th>
                        <th><?php echo _l('time_h'); ?></th>
                        <th><?php echo _l('time_decimal'); ?></th>
                     </thead>
                     <tbody>
                        <?php
                           $total_logged_time = 0;
                           foreach($timesheets as $t){ ?>
                        <tr>
                           <td><a href="#" onclick="init_task_modal(<?php echo $t['task_id']; ?>); return false;"><?php echo $t['name']; ?></a></td>
                           <td data-order="<?php echo $t['start_time']; ?>"><?php echo _dt($t['start_time'],true); ?></td>
                           <td data-order="<?php echo $t['end_time']; ?>"><?php echo _dt($t['end_time'],true); ?></td>
                           <td>
                              <?php
                                 $rel_data   = get_relation_data($t['rel_type'], $t['rel_id']);
                                 $rel_values = get_relation_values($rel_data, $t['rel_type']);
                                 echo '<a href="' . $rel_values['link'] . '">' . $rel_values['name'].'</a>';
                                 ?>
                           </td>
                           <td><?php echo format_money($t['hourly_rate'],$base_currency->symbol); ?></td>
                           <td>
                              <?php echo '<b>'.seconds_to_time_format($t['end_time'] - $t['start_time']).'</b>'; ?>
                           </td>
                           <td data-order="<?php echo sec2qty($t['total']); ?>">
                              <?php
                                 $total_logged_time += $t['total'];
                                 echo '<b>'.sec2qty($t['total']).'</b>';
                                 ?>
                           </td>
                        </tr>
                        <?php } ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td align="right"><?php echo '<b>' . _l('total_by_hourly_rate') .':</b> '. format_money((sec2qty($total_logged_time) * $member->hourly_rate),$base_currency->symbol); ?></td>
                           <td align="right">
                              <?php echo '<b>'._l('total_logged_hours_by_staff') . ':</b> ' . seconds_to_time_format($total_logged_time); ?>
                           </td>
                           <td align="right">
                              <?php echo '<b>'._l('total_logged_hours_by_staff') . ':</b> ' . sec2qty($total_logged_time); ?>
                           </td>
                        </tr>
                     </tfoot>
                  </table>
            </div>
         </div>
        
      </div>
      <?php } ?>
   </div>
   <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>
<script>
   $(function() {

       $('select[name="role"]').on('change', function() {
           var roleid = $(this).val();
           init_roles_permissions(roleid, true);
       });

       $('input[name="administrator"]').on('change', function() {
           var checked = $(this).prop('checked');
           var isNotStaffMember = $('.is-not-staff');
           if (checked == true) {
               isNotStaffMember.addClass('hide');
               $('.roles').find('input').prop('disabled', true).prop('checked', false);
           } else {
               isNotStaffMember.removeClass('hide');
               isNotStaffMember.find('input').prop('checked', false);
               $('.roles').find('input').prop('disabled', false);
           }
       });

       $('#is_not_staff').on('change', function() {
           var checked = $(this).prop('checked');
           var row_permission_leads = $('tr[data-name="leads"]');
           if (checked == true) {
               row_permission_leads.addClass('hide');
               row_permission_leads.find('input').prop('checked', false);
           } else {
               row_permission_leads.removeClass('hide');
           }
       });
       
       $('#departments').on('change',function(){
            csrf_jquery_ajax_setup();
            var did = $(this).val();
            $.ajax({
                type: "GET",
                url: "<?= admin_url('teams/getTeamListByDepartmentId'); ?>",
                data: {dept_id: did},
                success: function (res) {
					//alert(res);
					var obj = JSON.parse(res);
                    $('#team_id').html(obj['team']);
                    $('#role_id').html(obj['role']);
                    $('#rm_id').html('');
                    $('.selectpicker').selectpicker('refresh');
                }
            });
       });
       
       $('#team_id').on('change',function(){
            csrf_jquery_ajax_setup();
            var team_id = $(this).val();
            var dept_id = $("#departments").val();
            $.ajax({
                type: "GET",
                url: "<?= admin_url('teams/getRoleListByTeamId'); ?>",
                data: {team_id:team_id, dept_id: dept_id},
                success: function (res) {
                    $('#role_id').html(res);
                    $('#rm_id').html('');
                    $('.selectpicker').selectpicker('refresh');
                }
            });
       });

       $('#role_id').on('change',function(){
            csrf_jquery_ajax_setup();
            var role_id = $(this).val();
            var dept_id = $("#departments").val();
            var team_id = $("#team_id").val();
            var role =   $( "select#role_id option:checked" ).val();
    $("#role").val(role);
            $.ajax({
                type: "GET",
                url: "<?= admin_url('teams/getRmListByTeamIdDeptIdRmRoleId'); ?>",
                data: {role_id:role_id,team_id:team_id, dept_id: dept_id},
                success: function (res) {
                    $('#rm_id').html(res);
                    $('.selectpicker').selectpicker('refresh');
                }
            });
       });

       init_roles_permissions();

       _validate_form($('.staff-form'), {
           firstname: 'required',
           lastname: 'required',
           username: 'required',
           departments: 'required',
           team_id: 'required',
           role_id: 'required',
           rm_id: 'required',
           password: {
               required: {
                   depends: function(element) {
                       return ($('input[name="isedit"]').length == 0) ? true : false
                   }
               }
           },
           email: {
               required: true,
               email: true,
               remote: {
                   url: site_url + "admin/misc/staff_email_exists",
                   type: 'post',
                   data: {
                       email: function() {
                           return $('input[name="email"]').val();
                       },
                       memberid: function() {
                           return $('input[name="memberid"]').val();
                       }
                   }
               }
           }
       });
   });

</script>
<script>
    $(function() {
		 var role =   $( "select#role_id option:checked" ).val();
		$("#role").val(role);
	});
	$('#birth_date').change(function(){
			var cdate = new Date($(this).val());
			var dtCurrent = new Date();
			if (dtCurrent.getFullYear() - cdate.getFullYear() < 18) {
                alert_float("warning", "Eligibility 18 years ONLY, Please select DOB above 18 Years.");
				$(this).val("");
            }
		});
$(document).ready(function(){
	  $("#company").change(function(){
		var comp_id = $(this).val();
		$.ajax({
           url: "<?php echo admin_url('/staff/getdepartment') ?>",
           type: 'POST',
           data: {comp_id: comp_id},
           error: function() {
              alert('Something is wrong');
           },
           success: function(html) {
                $('#departments').html('');
				$('#departments').append(html);
				$('#departments').selectpicker('refresh');
				
				$('#team_id').html('');
				$('#team_id').selectpicker('refresh');
				$('#role_id').html('');
				$('#role_id').selectpicker('refresh');
				$('#rm_id').html('');
				$('#rm_id').selectpicker('refresh');
           }
        });
	  });
	  /* $('#birth_date').datetimepicker({
			format: 'Y-m-d',
			onShow: function (ct) {
				this.setOptions({
					maxDate: new Date()
				})
			},
			timepicker: false
		}); */
	});
	
</script>
</body>
</html>
