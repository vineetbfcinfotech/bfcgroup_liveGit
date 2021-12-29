<?php init_head(); ?>
<div id="wrapper">
    <?php init_clockinout(); ?>
    <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                     <a href="#" onclick="new_scheme_type(); return false;" class="btn mright5 btn-info pull-left display-block">
                     <?php echo _l('add_new_scheme_cat'); ?>
                     </a>
                    <!--   <a href="import_scheme_cat"  class="btn mright5  pull-left display-block btn-success">
                     <?php echo _l('import_new_schm_cat'); ?>
                    </a> -->
										 <div class="clearfix"></div>
                      <hr class="hr-panel-heading" />
                      <?php if(!empty($scheme_types) > 0){ ?>
                      <table class="table dt-table scroll-responsive">
                        <thead>
                          <th><?php echo _l('sr_no'); ?></th>
                          <th><?php echo _l('product_dt_scheme_type_name'); ?></th>
                          <th><?php echo _l('product_dt_scheme_type_short_name'); ?></th>
                          <th><?php echo _l('product_dt_active'); ?></th>
                          <th><?php echo _l('options'); ?></th>
                        </thead>
                        <tbody>
                          <?php foreach($scheme_types as $scheme_type){ ?>
                          <tr>
                            <td><?= @++$i; ?></td>
                            <td><a href="#" onclick="edit_scheme_type(this,<?= $scheme_type->id; ?>);return false;" data-name="<?= $scheme_type->name; ?>" data-short_name="<?= $scheme_type->short_name; ?>"><?= $scheme_type->name; ?></a></td>
                            <td><?= $scheme_type->short_name; ?></td>
														<td>
                              <div class="onoffswitch" data-toggle="tooltip" data-title="<?= $scheme_type->name; ?>">
                                <input type="checkbox" data-switch-url="<?= admin_url('products/active/scheme_types') ?>" name="onoffswitch" class="onoffswitch-checkbox" id="<?= $scheme_type->id ?>" data-id="<?= $scheme_type->id ?>" <?= $scheme_type->active == 1 ? 'checked' : '' ?>>
                                <label class="onoffswitch-label" for="<?= $scheme_type->id; ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a href="#" onclick="edit_scheme_type(this,<?= $scheme_type->id; ?>); return false" data-name="<?= $scheme_type->name; ?>" data-short_name="<?= $scheme_type->short_name; ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                              <a href="<?php echo admin_url('products/delete/scheme_types/'.$scheme_type->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                            </td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <?php } else { ?>
                      <p class="no-margin text-center"><?php echo _l('no_product_scheme_types_found'); ?></p>
                      <?php } ?>
                  </div>
                </div>
            </div>
        </div>
    </div>                
</div>
<!-- <div class="modal fade" id="scheme" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<?php echo form_open($this->uri->uri_string(),array('id'=>'product_scheme')); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="edit-title"><?php echo _l('product_scheme_edit'); ?></span>
					<span class="add-title"><?php echo _l('product_scheme_add'); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="additional"></div>
						<?php echo render_input('name','product_scheme_add_edit_name'); ?>
						<?php echo render_input('credit_rate_lumpsum','product_scheme_add_edit_credit_rate_lumpsum'); ?>
						<?php echo render_input('credit_rate_sip','product_scheme_add_edit_credit_rate_sip'); ?>
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
</div> -->
<div class="modal fade" id="scheme_type" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<?php echo form_open('admin/products/scheme_types',array('id'=>'product_scheme_type')); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="edit-title"><?php echo _l('product_scheme_type_edit'); ?></span>
					<span class="add-title"><?php echo _l('product_scheme_type_add'); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="additional"></div>
						<?php echo render_input('name','product_dt_scheme_type_name'); ?>
					</div>
					<div class="col-md-12">
						<?php echo render_input('short_name','product_scheme_type_add_edit_short_name'); ?>
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
		_validate_form($('#product_scheme'),{name:'required'},manage_product_schemes);
		$('#scheme').on('hidden.bs.modal', function(event) {
			$('#additional').html('');
			$('#scheme input[name="name"]').val('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
		});
    _validate_form($('#product_scheme_type'),{name:'required'},manage_product_scheme_scheme_types);
		$('#scheme_type').on('hidden.bs.modal', function(event) {
			$('#additional').html('');
			$('#scheme_type input[name="name"]').val('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
		});
	});
	function manage_product_schemes(form) {
		var data = $(form).serialize();
		var url = form.action;
		$.post(url, data).done(function(response) {
			window.location.reload();
		});
		return false;
	}
	function new_scheme(){
		$('#scheme').modal('show');
		$('.edit-title').addClass('hide');
	}
	function edit_product_schemes(invoker,id){
		var name = $(invoker).data('name');
		$('#additional').append(hidden_input('id',id));
		$('#scheme input[name="name"]').val(name);
		$('#scheme').modal('show');
		$('.add-title').addClass('hide');
	}
	
	function manage_product_scheme_scheme_types(form) {
		var data = $(form).serialize();
		var url = form.action;
		$.post(url, data).done(function(response) {
			window.location.reload();
		});
		return false;
	}
	function new_scheme_type(){
		$('#scheme_type').modal('show');
		$('.edit-title').addClass('hide');
	}
	function edit_scheme_type(invoker,id){
		var name = $(invoker).data('name');
		var short_name = $(invoker).data('short_name');
		$('#additional').append(hidden_input('id',id));
		$('#scheme_type input[name="name"]').val(name);
		$('#scheme_type input[name="short_name"]').val(short_name);
		$('#scheme_type').modal('show');
		$('.add-title').addClass('hide');
	}
</script>
</body>
</html>