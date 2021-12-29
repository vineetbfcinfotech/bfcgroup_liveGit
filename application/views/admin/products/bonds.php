<?php init_head(); ?>
<div id="wrapper">
    <?php init_clockinout(); ?>
    <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                    <!--<a href="<?= admin_url('products/schemes') ?>" class="btn mright5 btn-info pull-left display-block">-->
                    <!-- <?php echo _l('add_new_product'); ?>-->
                    <!-- </a>-->
                      <a href="<?= admin_url('products/import_schemes') ?>"  class="btn mright5  pull-left display-block btn-success">
                     <?php echo _l('import_schm'); ?>
                    </a>
                    
                     <div class="clearfix"></div>
                  <hr class="hr-panel-heading" />
                      <?php if(!empty($schemes) > 0){   ?>
                      <div class="row">
                        <div class="col-md-2 well" style="width: 100%;">
                        <span class="rows_selected" id="select_count">0 Selected</span>
                        <a type="button" id="delete_records" class="btn btn-primary pull-right">Delete</a>
                        </div>
                        </div>
                       
                        
                        
                        
                      <table  id="schemetable" class="table dt-table scroll-responsive">
                        <thead>
                           <th><input type="checkbox" id="select_all"></th>
                          <th>&nbsp;</th>
                          <th><?php echo _l('sr_no'); ?></th>
                          <th><?php echo _l('product_dt_name'); ?></th>
                          <th><?php echo _l('product_dt_company_name'); ?></th>
                          <th><?php echo _l('product_categories'); ?></th>
                          <th><?php echo "Credit Rate " ?></th>
                          <th><?php echo "GST" ?></th>
                          <th><?php echo "TDS" ?></th>
                          <th><?php echo "Effective From" ?></th>
                          <th><?php echo _l('product_dt_active'); ?></th>
                          <th><?php echo _l('options'); ?></th>
                        </thead>
                        <tbody>
                          <?php foreach($schemes as $val) { ?>
                          <tr>
                              <td><input type="checkbox" class="emp_checkbox" data-emp-id="<?php echo $val->id; ?>"/></td>
                            <td>&nbsp;</td>
                            <td><?= @++$i; ?></td>
                            <td><a href="<?= admin_url('products/schemes/'.$val->id); ?>" data-name="<?= $val->pname; ?>"><?= $val->pname; ?></a></td>
                            <td><?= $val->cpname; ?></td>
                            <td><?= $val->cname; ?></td>
                            <td><?= $val->credit; ?></td>
                            <td><?= $val->gst; ?> </td>
                            <td><?= $val->tds; ?> </td>
                            <td><? $date = date_create($val->score_changed); if( $val->score_changed == "0000-00-00 00:00:00") {echo ""; } else {echo date_format($date, ' jS F Y');}   ?> </td>
                            <td>
                              <div class="onoffswitch" data-toggle="tooltip" data-title="<?= $val->pname; ?>">
                                <input type="checkbox" data-switch-url="<?= admin_url('products/active/products') ?>" name="onoffswitch" class="onoffswitch-checkbox" id="<?= $val->id ?>" data-id="<?= $val->id ?>" <?= $val->active == 1 ? 'checked' : '' ?>>
                                <label class="onoffswitch-label" for="<?= $val->id; ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a href="<?= admin_url('products/schemes/'.$val->id) ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                              <a href="<?php echo admin_url('products/delete/schemes/'.$val->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                            </td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <?php } else { ?>
                      <p class="no-margin text-center"><?php echo _l('no_product_schemes_found'); ?></p>
                      <?php } ?>
                  </div>
                </div>
            </div>
        </div>
    </div>                
</div>
<?php init_tail(); ?>
<script>
$(document).on('click', '#select_all', function() {
$(".emp_checkbox").prop("checked", this.checked);
$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
});
$(document).on('click', '.emp_checkbox', function() {
if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
$('#select_all').prop('checked', true);
} else {
$('#select_all').prop('checked', false);
}
$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
});



// delete selected records
$('#delete_records').on('click', function(e) {
var employee = [];
$(".emp_checkbox:checked").each(function() {
employee.push($(this).data('emp-id'));
});
if(employee.length <=0) { alert("Please select Schemes."); } else { WRN_PROFILE_DELETE = "Are you sure you want to delete "+(employee.length>1?"these":"this")+" row?";
var checked = confirm(WRN_PROFILE_DELETE);
if(checked == true) {
var selected_values = employee.join(",");
$.ajax({
type: "POST",
url: "<?php echo base_url(); ?>admin/products/deletesche",
cache:false,
data: 'emp_id='+selected_values,
success: function(response) {
// remove deleted employee rows
window.location.reload();
var emp_ids = response.split(",");
for (var i=0; i < emp_ids.length; i++ ) { $("#"+emp_ids[i]).remove(); } } }); } } });
</script>
</body>
</html>