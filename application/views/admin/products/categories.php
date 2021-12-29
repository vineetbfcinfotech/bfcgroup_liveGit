<?php init_head(); ?>
<div id="wrapper">
    <?php init_clockinout(); ?>
    <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                    <a href="#" onclick="addcatagory()" class="btn mright5 btn-info pull-left display-block">
                     <?php echo _l('add_new'); ?>
                    </a>
                  </div>
                  <div class="clearfix"></div>
                  <hr class="hr-panel-heading" />
                      <?php if(!empty($categories) > 0){ ?>
                        <table class="table dt-table scroll-responsive">
    <thead>
    <th>&nbsp;</th>
    <th><?php echo _l('id'); ?></th>
    <th style="width: 25%;"><?php echo _l('product_category_dt_name'); ?></th>
    <th style="width: 25%;"><?php echo _l('product_category_dt_short_name'); ?></th>
    <th style
    ="width: 25%;"><?php echo _l('product_dt_active'); ?></th>
    <th style="width: 25%;"><?php echo _l('options'); ?></th>
    </thead>
    <tbody>
    <?php foreach ($categories as $category) { ?>
        <tr>
            <td>&nbsp;</td>
            <td><?= @++$i; ?></td>
            <td><?= $category->name; ?>
            </td>
            <td><?= $category->short_name; ?></td>
            <td>
                <div class="onoffswitch" data-toggle="tooltip" data-title="<?= $category->name; ?>">
                    <input type="checkbox" data-switch-url="<?= admin_url('products/active/categories') ?>"
                           name="onoffswitch" class="onoffswitch-checkbox" id="<?= $category->id ?>"
                           data-id="<?= $category->id ?>" <?= $category->active == 1 ? 'checked' : '' ?>>
                    <label class="onoffswitch-label" for="<?= $category->id; ?>"></label>
                </div>
            </td>
            <td>
                <a href="#" title="edit-Leave"
                   onclick="editcatagory('<?php echo $category->id; ?>','<?php echo $category->name; ?>','<?php echo $category->short_name; ?>')"
                   data-name="Via Excel Sheet" class="btn btn-default btn-icon"><i
                            class="fa fa-pencil-square-o"></i></a> <a
                        href="<?php echo admin_url('products/delete/categories/' . $category->id); ?>"
                        class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
            </td>
        </tr>
    <?php } ?>

    </tbody>
</table>
                      <?php } else { ?>
                      <p class="no-margin"><?php echo _l('no_product_categories_found'); ?></p>
                      <?php } ?>
                      </div>
                </div>
            </div>
        </div>
    </div>                
</div>
<?php init_tail(); ?>

<div class="modal fade" id="editcatagory-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form autocomplete="off" action="<?php echo  base_url(); ?>admin/products/updateproducttype" id="ticket-service-form" method="post" accept-charset="utf-8">
            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">
                                        <span class="edit-title">Update Prodduct Type</span>
                                        <!-- <span class="add-title">New Product</span> -->
                                    </h4>
                                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="additional"></div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Name</label>
                              <input type="text" id="name" name="name" class="form-control"  value="">
                              
                              <label for="name" class="control-label">Short Name</label>
                              <input type="text" id="short_name" name="short_name" class="form-control"  value="">
                              <input type="hidden" id="id" name="id" class="form-control"  value="">
                             
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
    
    <div class="modal fade" id="addcatagory-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form autocomplete="off" action="<?php echo  base_url(); ?>admin/products/categories"  id="addcatagory" method="post" accept-charset="utf-8">
            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">
                                        <span class="edit-title">Add Prodduct Type</span>
                                        <!-- <span class="add-title">New Product</span> -->
                                    </h4>
                                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="additional"></div>
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Name</label>
                              <input type="text" id="name" name="name" class="form-control"  value="">
                              
                              <label for="name" class="control-label">Short Name</label>
                              <input type="text" id="short_name" name="short_name" class="form-control"  value="">
                             
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button onclick="form_submit()"  class="btn btn-info">Save</button>
                </div>
            </div><!-- /.modal-content -->
            </form>        

          </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>


<!-- Product category Data Add/Edit-->
<div class="modal fade product-catagory-modal" id="product_catagory" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><?= _l('add_new'); ?></h4>
        </div>
        <?= form_open($this->uri->uri_string()); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <?php echo render_input('name','product_category_name'); ?>
                    <?php echo render_input('short_name','product_category_short_name'); ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info category-save-btn"><?php echo _l('submit'); ?></button>
        </div>
        <?= form_close(); ?>
    </div>
  </div>
</div><script>
	$(function(){
		_validate_form($('form'),{name:'required'},manage_product_catagories);
		$('#product_catagory').on('hidden.bs.modal', function(event) {
			$('#additional').html('');
			$('#product_catagory input[name="name"]').val('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
		});
	});
	function manage_product_catagories(form) {
		var data = $(form).serialize();
		var url = form.action;
		$.post(url, data).done(function(response) {
			window.location.reload();
		});
		return false;
	}
	function add_new_category(){
		$('#product_catagory').modal('show');
		$('.edit-title').addClass('hide');
	}
	function edit_product_catagories(invoker,id){
		var name = $(invoker).data('name');
		$('#additional').append(hidden_input('id',id));
		$('#product_catagory input[name="name"]').val(name);
		$('#product_catagory').modal('show');
		$('.add-title').addClass('hide');
	}
	
	function manage_product_catagories(form) {
		var data = $(form).serialize();
		var url = form.action;
		$.post(url, data).done(function(response) {
			window.location.reload();
		});
		return false;
	}
	function new_product_catagory(){
		$('#product_catagory').modal('show');
		$('.edit-title').addClass('hide');
	}
	function edit_product_catagory(invoker,id){
		var name = $(invoker).data('name');
		var short_name = $(invoker).data('short_name');
		$('#additional').append(hidden_input('id',id));
		$('#product_catagory input[name="name"]').val(name);
		$('#product_catagory input[name="short_name"]').val(short_name);
		$('#product_catagory').modal('show');
		$('.add-title').addClass('hide');
	}
	
	function editcatagory(id,name,short_name)
 {
    $("#id").val(id);
    $("#name").val(name);
    $("#short_name").val(short_name);
    $('#editcatagory-modal').modal('show');
 }
 function addcatagory(){
		$('#addcatagory-modal').modal('show');
	}
</script>
<script type="text/javascript">
  function form_submit() {
    document.getElementById("addcatagory").submit();
   }    
  </script>
</body>
</html>