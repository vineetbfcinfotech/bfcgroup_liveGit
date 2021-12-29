<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        
         <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>List Inventory</h3>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table dt-table scroll-responsive">
                                        <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Book Title</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1; foreach($list as $getdata){ ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><a href="javascript:void(0)" data-id="<?php echo $getdata->id;?>"  data-bookt="<?= $getdata->book_title?>" data-flipkart="<?= $getdata->flipkart?>" data-Amazon="<?= $getdata->amazone?>" data-bfcstore="<?= $getdata->bfcstore?>" id="b_title" class="edit"><?= $getdata->book_title?></a></td>
                                            <!-- <td><a href=""> <?= $getdata->book_title?></td> -->
                                            </tr>
                                        <?php $i++;}?>
                                        </tbody>
                                </table>
                                </div> 
                            </div>
                        </div>
                        <div id="loading-image" style="display: none; text-align: center;">
					        <img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
				        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                                        
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo base_url();?>admin/InventoryDashboard/save_saleReport" >
                        <div class="row">
                            <input type="hidden" class="form-control serviceId" name="serviceId" >
                                
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="book_title" class="control-label">Store Plateform</label>
                                        <select name="platform" class="form-control">
                                            <option>--select--</option>
                                            <option value="flipkart">Flipkart</option>
                                            <option value="amazone">Amazon</option>
                                            <option value="bfcstore">BFC Store</option>
                                            
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" app-field-wrapper="email">
                                        <label for="buyer_name" class="control-label">Buyer Name</label>
                                        <input type="text" id="buyer_name" name="buyer_name" class="form-control" value="" required>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="order_id" class="control-label">Order ID</label>
                                        <input type="text" id="order_id" name="order_id" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="dispatch_date" class="control-label">Dispatch Date</label>
                                        <input type="date" id="dispatch_date" name="dispatch_date" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="quantity" class="control-label">Quantity</label>
                                        <input type="text" id="quantity" name="quantity" class="form-control" value="" required>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" app-field-wrapper="file_csv">
                                     <label for="file_csv" class="control-label"></label>
                                     <input type="submit" value="Save" class="btn btn-info import btn-import-submit">
                                    <!--<button type="submit" name="submit" id="button_disable" class="btn btn-info import btn-import-submit" value="button_disable"> Save
                                    </button>-->
                                </div>
                            </div>
                        </div>
                        </form>
          
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('.edit').click(function(){
        var id = $(this).attr("data-id");
     
     
        var title="Inventor Out";
        
        
        
        $("#myModal .modal-title").html(title);
        $("#myModal .serviceId").val(id);
   
        
        $("#myModal").modal('show');
    });
   
});
</script>   
<?php init_tail(); ?>
</body>
</html>