<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                    <a href="#" onclick="new_company(); return false;" class="btn mright5 btn-info pull-left display-block">
                     <?php echo "Add New Task"; ?>
                     </a>
                     <div class="clearfix"></div>
                      <hr class="hr-panel-heading" />
                      <?php if(!empty($companies) > 0){ ?>
                      <table class="table dt-table scroll-responsive">
                        <thead>
                          <th><?php echo _l('id'); ?></th>
                          <th><?php echo _l('task_add_edit'); ?></th>
                          <th><?php echo "Frequency"; ?></th>
                          <th><?php echo "Date Limit"; ?></th>
                          <th><?php echo _l('product_dt_active'); ?></th>
                          <th><?php echo _l('options'); ?></th>
                        </thead>
                        <tbody>
                          <?php foreach($companies as $company){ ?>
                          <tr>
                            <td><?= @++$i; ?></td>
                            <td><a href="#" onclick="edit_company(this,<?= $company->id; ?>);return false;" data-name="<?= $company->name; ?>"><?= $company->name; ?></a></td>
                             <td><?= $company->frequency; ?></td>
                             <td><?= $company->date_from; ?> - <?= $company->date_to; ?></td>
                            <td>
                                
                              <div class="onoffswitch" data-toggle="tooltip" data-title="<?= $company->name; ?>">
                                <input type="checkbox" data-switch-url="<?= admin_url('operations/active/task') ?>" name="onoffswitch" class="onoffswitch-checkbox" id="<?= $company->id ?>" data-id="<?= $company->id ?>" <?= $company->active == 1 ? 'checked' : '' ?>>
                                <label class="onoffswitch-label" for="<?= $company->id; ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a href="#" onclick="edit_company(this,<?= $company->id; ?>); return false" data-name="<?= $company->name; ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                              <a href="<?php echo admin_url('operations/delete/'.$company->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                            </td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <?php } else { ?>
                      <p class="no-margin"><?php echo "No Task Found"; ?></p>
                      <?php } ?>
                  </div>
                </div>
            </div>
        </div>
    </div>                
</div>
<div class="modal fade" id="product_company" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<?php echo form_open($this->uri->uri_string()); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="edit-title"><?php echo "Edit Task"; ?></span>
					<span class="add-title"><?php echo "Add New Task"; ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="additional"></div>
						<?php echo render_input('name','task_add_edit'); ?>
					</div>
					<div class="col-md-4">
					    <label for="date_from" class="control-label"> <small class="req text-danger">* </small>Date From</label>
						<select name="date_from" value="" id="date_from" class="form-control selectpicker" >
						    <?php  
for ($x = 1; $x <= 31; $x++) {
  echo "<option value='$x' >$x</option>";
}
?>  
						    
						</select>
					</div>
					<div class="col-md-4">
					      <label for="date_to" class="control-label"> <small class="req text-danger">* </small>Date To</label>
						<select name="date_to" value="" id="date_to" class="form-control selectpicker" >
						    <?php  
for ($x = 1; $x <= 31; $x++) {
  echo "<option value='$x' >$x</option>";
}
?>  
						    
						</select>
					</div>
					
					<div class="col-md-4">
					      <label for="months" class="control-label"> <small class="req text-danger">* </small>Months</label>
						<select name="months[]" value="" id="months" class="selectpicker" data-width="100%" data-none-selected-text="Please Select" multiple="1" data-actions-box="1" data-live-search="true" tabindex="-98">
						    <?php  
for ($x = 1; $x <= 12; $x++) {
  echo "<option value='$x' >$x</option>";
}
?>  
						    
						</select>
					</div>
					
					<div class="col-md-12">
					    <label for="name" class="control-label"> <small class="req text-danger">* </small>Frequency</label>
						<select name="frequency" value="" id="frequency" class="form-control selectpicker" >
						    <option value="Daily" >Daily</option>
						    <option value="Weekly" >Weekly</option>
						    <option value="Monthly" >Monthly</option>
						    <option value="Quartely" >Quartely</option>
						    <option value="Yearly" >Yearly</option>
						</select>
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
		_validate_form($('form'),{name:'required'},manage_product_companys);
		$('#product_company').on('hidden.bs.modal', function(event) {
			$('#additional').html('');
			$('#product_company input[name="name"]').val('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
		});
	});
	function manage_product_companys(form) {
		var data = $(form).serialize();
    var url = form.action;
		$.post(url, data).done(function(response) {
      window.location.reload();
		});
		return false;
	}
	function new_company(){
		$('#product_company').modal('show');
		$('.edit-title').addClass('hide');
	}
	function edit_company(invoker,id){
		var name = $(invoker).data('name');
		$('#additional').append(hidden_input('id',id));
		$('#product_company input[name="name"]').val(name);
		$('#product_company').modal('show');
		$('.add-title').addClass('hide');
	}
</script>
</body>
</html>