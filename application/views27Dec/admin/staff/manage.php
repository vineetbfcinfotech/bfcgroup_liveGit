<?php init_head(); ?>

<div id="wrapper"><?php init_clockinout(); ?>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php if(has_permission('staff','','create')){ ?>
						<?php if($checkIncative != 'member_inactive'){ ?>
						<div class="_buttons">
							<a href="<?php echo admin_url('staff/member'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_staff'); ?></a>
							<a href="<?php echo admin_url('staff/member_inactive'); ?>" class="btn btn-info pull-right display-block">Inactive Staff</a>
						</div>
						<?php } ?>
						<?php if($checkIncative == 'member_inactive'){ ?>
							<a href="javascript:void(0);" class="btn btn-info pull-left display-block" onclick="goBack()">Back</a>
						<?php } ?>
						<p class="custom_flash" style="display: none;">Status updated successfully <span>x</span></p>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<?php } ?>
						<div class="clearfix"></div>
						
						<table class="table dt-table scroll-responsive">
						<thead><tr>
						<th>S.N</th>
						<th><?php echo _l('staff_dt_name')?></th>
						<th><?php echo _l('staff_dt_email')?></th>
						<th><?php echo _l('staff_dt_reporting_manager')?></th>
						<th><?php echo _l('staff_dt_last_Login')?></th>
						<th><?php echo _l('staff_dt_active')?></th>
						</tr>
						</thead>
						
						<tbody>
						
						<?php 
						$i=1;
						foreach($staff_members as $staff){
						
						?>
						<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $staff['full_name']; ?> </td>
						<td><?php echo $staff['email'] ?> </td>
						<td><?php echo get_staff_full_name($staff['reporting_manager']) ?> </td>
						<td><?php echo $staff['last_login'] ?> </td>
						<td>
						<label class="" style="margin-right: 5px;"> <a href="<?php echo base_url(); ?>admin/staff/member/<?php echo $staff['staffid']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a></label>
						<select class="label label-success data-action" data-id="<?php echo $staff['staffid'] ?>">
							<option value="1" <?php if($staff['active'] == 1){ echo "selected"; } ?> >Active</option>
							<option value="0" <?php if($staff['active'] == 0){ echo "selected"; } ?> >Inactive</option>
						</select>
						</td>
						</tr>
						<?php
						$i++;
						} ?>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="delete_staff" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<?php echo form_open(admin_url('staff/delete',array('delete_staff_form'))); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo _l('delete_staff'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="delete_id">
					<?php echo form_hidden('id'); ?>
				</div>
				<p><?php echo _l('delete_staff_info'); ?></p>
				<?php
				echo render_select('transfer_data_to',$staff_members,array('staffid',array('firstname','lastname')),'staff_member',get_staff_user_id(),array(),array(),'','',false);
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-danger _delete"><?php echo _l('confirm'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style>
.dt-table-loading.table, .table-loading table thead th, .table-loading table tbody tr, .table-loading .dataTables_length, .table-loading .dt-buttons, .table-loading .dataTables_filter {
    opacity: 1 !important;
}
</style>
<?php init_tail(); ?>
<script>

	function delete_staff_member(id){
		$('#delete_staff').modal('show');
		$('#transfer_data_to').find('option').prop('disabled',false);
		$('#transfer_data_to').find('option[value="'+id+'"]').prop('disabled',true);
		$('#delete_staff .delete_id input').val(id);
		$('#transfer_data_to').selectpicker('refresh');
	}
</script>
<script>
$(document).ready(function() {
    
    $(document).on('change','.data-action',function(e) {
        var userid=$(this).attr("data-id");
		var status=$(this).val();
		
        $.ajax({
           url: '<?php echo base_url(); ?>admin/staff/change_active',
           type: 'POST',
           data: {'id': userid, 'status': status},
           dataType: 'json',
           error: function() {
				alert('Something is wrong');
           },
		   
           success: function(data) {
			   $(".custom_flash").show();
                setTimeout(function() { $(".custom_flash").hide(); }, 5000);
				setTimeout(function() { location.reload(); }, 3000);
			} 
        });
    });
	
	$(".custom_flash span").click(function(){
	  $(".custom_flash").hide();
	});
});
function goBack() {
  window.history.back();
}
</script>
<style>
p.custom_flash {
    max-width: 500px;
    float: right;
    background-color: #84c529;
    color: #fff;
    padding: 6px 20px;
    text-transform: uppercase;
    font-size: 13.5px;
	margin-bottom: 0;
}
p.custom_flash span{ 
	margin-left: 30px;
    background-color: #ff0000ed;
    font-size: 16px;
    padding: 5px 10px;
	cursor: pointer;
}
</style>
</body>
</html>
