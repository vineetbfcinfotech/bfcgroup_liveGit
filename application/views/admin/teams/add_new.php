<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel_s">
					<div class="panel-body">
					 	<h4 class="no-margin">
					 	 <button class="btn btn-alert"  onclick="window.location='<?php echo base_url(); ?>admin/teams';"> Back</button> 	<?php echo $title; ?>
						</h4>
						<hr class="hr-panel-heading" />
						<?php echo form_open($this->uri->uri_string()); ?>
						<div class="col-md-6">
							<div class="form-group">
								<label for="company_id" class="control-label">Company Name*</label>
								<select id="company_id" data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter" name="company_id" required="required">
									<option value="">--Select--</option>
									<option value="1">BFC Capital</option>
									<option value="2">BFC Infotech</option>
									<option value="3">BFC Publications</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<?//= render_select('department_id',$departments,array('departmentid','name'),'department_name'); ?>
							<div class="form-group">
								<label for="department_id" class="control-label">Department Name*</label>
								<select id="department_id" data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter" name="department_id" required="required">
									<option value="">--Select--</option>
								</select>
							</div>
						</div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<div class="col-md-6" style="">
							<?php echo render_input('team_name','team_name','','text'); ?>
						</div>
						<div class="col-md-6">
						<?php //echo "<pre>"; print_r($roles); exit;?>
							<?//= render_select('staffid',$staff_members,array('staffid','full_name'),'team_lead'); ?>
							<div class="form-group">
								<label for="staffid" class="control-label">Team Lead*</label>
								<select id="staffid" data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter" name="staffid" required="required">
									<option value="">--Select--</option>
								</select>
							</div>
						</div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<div class="col-md-5 role">
							<?= render_select('role_ids[]',$roles,array('roleid','name'),'Reporting person'); ?>
							<!-- <div class="form-group">
								<label for="role_ids[]" class="control-label">Assign Role</label>
								<select id="role_ids[]" data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter" name="role_ids[]" required="required">
									<option value="">--Select--</option>
								</select>
							</div> -->
						</div>
						<div class="col-md-5">
							<?= render_select('rm_ids[]',$roles,array('roleid','name','as RM'),'Reporting to'); ?>
							<!-- <div class="form-group">
								<label for="role_ids[]" class="control-label">Assign Reporting Authority</label>
								<select id="role_ids[]" data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter" name="role_ids[]" required="required">
									<option value="">--Select--</option>
								</select>
							</div> -->
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="add_more_roles">
								<label for="add_more_team_role" class="control-label"> Add More Roles</label>
								<button style="margin-left: 4px;" type="button" class="btn btn-info" id="add_more_team_role"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div id="addnewrole"></div>
						<div class="col-md-12">
							<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
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
		<?= render_select('role_ids[]',$roles,array('roleid','name')); ?>
	</div>
	<div class="col-md-5 role_row_{{id}}">
		<?= render_select('rm_ids[]',$roles,array('roleid','name','as RM')); ?>
	</div>
	<div class="col-md-2 role_row_{{id}}">
		<div class="form-group" app-field-wrapper="add_more_roles">
			<label for="add_more_team_role" class="control-label">&nbsp;</label>
			<button type="button" data-row="{{id}}" class="btn btn-danger remove_team_role"><i class="fa fa-minus"></i></button>
		</div>
	</div>
</script>
<script>
jQuery('#date').addClass('datepicker');
		$(function(){
			_validate_form($('form'),{team_name:'required'});
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
