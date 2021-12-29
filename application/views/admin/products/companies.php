<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                    <a href="#" onclick="new_company(); return false;" class="btn mright5 btn-info pull-left display-block">
                     <?php echo _l('add_new_company'); ?>
                     </a>
                     <div class="clearfix"></div>
                      <hr class="hr-panel-heading" />
                      <?php if(!empty($companies) > 0){ ?>
                      <table class="table dt-table scroll-responsive">
                        <thead>
                          <th><?php echo _l('id'); ?></th>
                          <th><?php echo _l('product_dt_company_name'); ?></th>
                          <th><?php echo _l('product_dt_active'); ?></th>
                          <th><?php echo _l('options'); ?></th>
                        </thead>
                        <tbody>
                          <?php foreach($companies as $company){ ?>
                          <tr>
                            <td><?= @++$i; ?></td>
                            <td><a href="#" onclick="edit_company(this,<?= $company->id; ?>);return false;" data-name="<?= $company->name; ?>"><?= $company->name; ?></a></td>
                            <td>
                              <div class="onoffswitch" data-toggle="tooltip" data-title="<?= $company->name; ?>">
                                <input type="checkbox" data-switch-url="<?= admin_url('products/active/products') ?>" name="onoffswitch" class="onoffswitch-checkbox" id="<?= $company->id ?>" data-id="<?= $company->id ?>" <?= $company->active == 1 ? 'checked' : '' ?>>
                                <label class="onoffswitch-label" for="<?= $company->id; ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a href="#" onclick="edit_company(this,<?= $company->id; ?>); return false" data-name="<?= $company->name; ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                              <a href="<?php echo admin_url('products/delete/companies/'.$company->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                            </td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <?php } else { ?>
                      <p class="no-margin"><?php echo _l('no_product_companies_found'); ?></p>
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
					<span class="edit-title"><?php echo _l('product_company_edit'); ?></span>
					<span class="add-title"><?php echo _l('product_company_add'); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="additional"></div>
						<?php echo render_input('name','product_company_add_edit_name'); ?>
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