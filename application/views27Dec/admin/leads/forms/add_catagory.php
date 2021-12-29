<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title"><?= _l('add_new'); ?></h4>
</div>
<?= form_open($this->uri->uri_string(),array('id'=>'product_category')); ?>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <?php echo form_hidden('update', true); ?>
            <?php echo form_hidden('categoryid', true); ?>
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
