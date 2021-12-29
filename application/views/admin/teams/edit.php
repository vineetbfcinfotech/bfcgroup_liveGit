<?php init_head(); ?>
<div id="wrapper"> <?= init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <button class="btn btn-success pull-right"
                                    onclick="window.location='<?php echo base_url(); ?>admin/teams';"> Back
                            </button> <?= @$title; ?>
                        </h4>
                        <hr class="hr-panel-heading"/>
                       <?php echo form_open($this->uri->uri_string()); ?>
					   <div class="col-md-6">
							<div class="form-group">
								<label for="company_id" class="control-label">Company Name</label>
								<select id="company_id" data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter" name="company_id">
									<option value="">--Select--</option>
									<option value="1" <?php if($team->company_id == 1){ echo "selected"; }?> >BFC Capital</option>
									<option value="2" <?php if($team->company_id == 2){ echo "selected"; }?> >BFC Infotech</option>
									<option value="3" <?php if($team->company_id == 3){ echo "selected"; }?> >BFC Publications</option>
								</select>
							</div>
						</div>
						
						<div class="col-md-6">
                           <?= render_select('department_id', $departments, array('departmentid', 'name'), 'department_name', $team->department_id); ?>
                        </div>
						<hr class="hr-panel-heading"/>
						<div class="clearfix"></div>
                        <div class="col-md-6">
                           <?= form_hidden('team_id', $team->team_id); ?>
                           <?php echo render_input('team_name', 'team_name', $team->team_name, 'text'); ?>
                        </div>
                        <?php $this->db->select('*');
                        $this->db->where('staffid', $team->staffid);
                        $this->db->from('tblstaff');
                        $staff = $this->db->get()->row();
                        ?>
                        <div class="col-md-6">
                           <?//= render_select('satffid', $staff_members, array('staffid', 'full_name'), 'team_lead', $team->staffid); ?>
                           <div class="form-group">
								<label for="staffid" class="control-label">Team Lead*</label>
								<select id="staffid" data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter" name="staffid" required="required">
									<option value="<?php echo $staff->staffid; ?>"><?php echo $staff->firstname." ".$staff->lastname; ?></option>
								</select>
							</div>
                        </div>
                        <hr class="hr-panel-heading"/>
                        <div class="clearfix"></div>
                       <?php $old_roles = $this->teamsm->getTeamDepartmentRolesByTeamId($team->team_id, $team->department_id);
                          if ( !empty($old_roles) ) {
                             echo '<div id="addnewrole">';
                             echo '<input type="hidden" name="deleted_team_dept_ids" value="" id="deleted_team_dept_ids">';
                             foreach ($old_roles as $key => $old_role) { ?>
                                 <div class="col-md-5 role role_row_<?= $key; ?>">
                                    <?= form_hidden('team_dept_id[]', $old_role->id); ?>
                                    <?= render_select('role_ids[]', $roles, array('roleid', 'name'), $key == 0 ? 'team_roles' : '', $old_role->role_id); ?>
                                 </div>
                                 <div class="col-md-5 role_row_<?= $key; ?>">
                                    <?= render_select('rm_ids[]', $roles, array('roleid', 'name', 'as RM'), $key == 0 ? 'role_rm' : '', $old_role->rm_role_id); ?>
                                 </div>
                                 <div class="col-md-2 role_row_<?= $key; ?>">
                                     <div class="form-group" app-field-wrapper="add_more_roles">
                                        <?php if ( $key == 0 ) { ?>
                                            <label for="add_more_team_role" class="control-label"> Add More
                                                Roles</label>
                                            <button type="button" data-team_dept="<?= $old_role->id; ?>"
                                                    class="btn btn-info"
                                                    id="add_more_team_role"><i class="fa fa-plus"></i></button>
                                        <?php } else { ?>
                                            <button type="button" data-team_dept="<?= $old_role->id; ?>"
                                                    data-row="<?= $key; ?>"
                                                    class="btn btn-danger remove_team_role">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        <?php } ?>
                                     </div>
                                 </div>
                             <?php }
                             echo '</div>';
                          } else {
                             ?>
                              <div class="col-md-5 role">
                                 <?= render_select('role_ids[]', $roles, array('roleid', 'name'), 'team_roles'); ?>
                              </div>
                              <div class="col-md-5">
                                 <?= render_select('rm_ids[]', $roles, array('roleid', 'name', 'as RM'), 'role_rm'); ?>
                              </div>
                              <div class="col-md-2">
                                  <div class="form-group" app-field-wrapper="add_more_roles">
                                      <label for="add_more_team_role" class="control-label"> Add More Roles</label>
                                      <button style="margin-left: 4px;" type="button" class="btn btn-info"
                                              id="add_more_team_role"><i class="fa fa-plus"></i></button>
                                  </div>
                              </div>
                              <div id="addnewrole"></div>
                             <?php
                          } ?>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-info pull-right"><?php echo _l('update'); ?></button>
                        </div>
                       <?= form_close(); ?>
                    </div>
                </div>
               <?php init_tail(); ?>
            </div>
        </div>
    </div>
</div>
<script id="addroletemplate" type="text/template">
    <div class="col-md-5 role role_row_{{id}}">
       <?= render_select('role_ids[]', $roles, array('roleid', 'name')); ?>
    </div>
    <div class="col-md-5 role_row_{{id}}">
       <?= render_select('rm_ids[]', $roles, array('roleid', 'name', 'as RM')); ?>
    </div>
    <div class="col-md-2 role_row_{{id}}">
        <div class="form-group" app-field-wrapper="add_more_roles">
            <button type="button" data-row="{{id}}" class="btn btn-danger remove_team_role"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</script>
<script>
    $(function () {
        _validate_form($('form'), {team_name: 'required'});
    });
    
    $(document).ready(function(){
	  $("#company_id").change(function(){
		var comp_id = $(this).val();
		$.ajax({
           url: '/admin/teams/getdepartment',
           type: 'POST',
           data: {comp_id: comp_id},
           error: function() {
              alert('Something is wrong');
           },
           success: function(data) {
			   var decodeJson = JSON.parse(data);
                $('#department_id').html('');
				$('#department_id').append(decodeJson.department);
				$('#department_id').selectpicker('refresh');
				
				$('#staffid').html('');
				$('#staffid').append(decodeJson.staff);
				$('#staffid').selectpicker('refresh');
           }
        });
	  });
	});
</script>
</body>
</html>
