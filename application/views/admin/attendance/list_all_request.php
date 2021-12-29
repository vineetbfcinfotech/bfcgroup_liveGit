<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
            <div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong>All Time Change Request</strong>
                            <div class="pull-right hidden-print">
                                <a href="#" onclick="manual_time(); return false;" class="btn mright5 btn-info display-block">
                                    <i class="fa fa-plus "> </i><?php echo _l('add_time_manually'); ?>
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading" />
                            <div class="clearfix"></div>
                        </div>
                    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="manual_time" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<?php echo form_open($this->uri->uri_string()); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="edit-title"><?php echo _l('manual_time_edit'); ?></span>
					<span class="add-title"><?php echo _l('manual_time_add'); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="additional"></div>
					</div>
					<div class="col-md-12">
						<?= render_select('staff_id', $staff_members, array('staffid','firstname'), 'choose_staff'); ?>
					</div> 
					<div class="col-md-6">
						<?= render_date_input("date_in",'date_in'); ?>
					</div>
					<div class="col-md-6">
						<?= render_input("time_in","time_in",'','time'); ?>
					</div>
					<div class="col-md-6">
						<?= render_date_input("date_out",'date_out'); ?>
					</div>
					<div class="col-md-6">
						<?= render_input("time_out",'time_out','','time'); ?>
					</div>
                    
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div>
<?php init_tail(); ?>
<script>
	$(function(){
		_validate_form($('form'),{name:'required'},manage_manual_times);
		$('#manual_time').on('hidden.bs.modal', function(event) {
			$('#additional').html('');
			$('#manual_time input[name="name"]').val('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
		});
	});
	function manage_manual_times(form) {
		var data = $(form).serialize();
    var url = form.action;
		$.post(url, data).done(function(response) {
      window.location.reload();
		});
		return false;
	}
	function manual_time(){
		$('#manual_time').modal('show');
		$('.edit-title').addClass('hide');
	}
	function edit_company(invoker,id){
		var name = $(invoker).data('name');
		$('#additional').append(hidden_input('id',id));
		$('#manual_time input[name="name"]').val(name);
		$('#manual_time').modal('show');
		$('.add-title').addClass('hide');
	}
</script>
</body>
</html>
