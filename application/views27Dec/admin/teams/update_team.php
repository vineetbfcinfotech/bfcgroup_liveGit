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
							<?php echo render_input('team_name','team_name','','text'); ?>
						</div>
						<div class="col-md-6">
							<?= render_select('department_id',$departments,array('departmentid','name'),'department_name'); ?>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<div class="col-md-5 role">
							<?= render_select('role_ids[]',$roles,array('roleid','name'),'team_roles'); ?>
						</div>
						<div class="col-md-5">
							<?= render_select('rm_ids[]',$roles,array('roleid','name','as RM'),'role_rm'); ?>
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
		$(function(){
			_validate_form($('form'),{team_name:'required'});
		});
</script>
</body>
</html>
