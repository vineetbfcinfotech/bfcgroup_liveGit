<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Add Inventory</h3>  
                            </div>
                        </div>
                        <form method="post" action="<?php echo base_url();?>admin/InventoryDashboard/save_inventory" >
                        <div class="row">
                            <div class="col-md-3">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="book_title" class="control-label">Book Title</label>
                                        <input type="text" id="book_title" name="book_title" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" app-field-wrapper="email">
                                        <label for="author_name" class="control-label">Total Books</label>
                                        <input type="text" id="author_name" name="author_name" class="form-control" value="" required>
                                    </div>
                                </div> 
                                
                                <div class="col-md-2">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="FlipKart" class="control-label">FlipKart</label>
                                        <input type="text" id="FlipKart" name="FlipKart" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="Amazon" class="control-label">Amazon</label>
                                        <input type="text" id="Amazon" name="Amazon" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="bfc_book_store" class="control-label">BFC Book Store</label>
                                        <input type="text" id="bfc_book_store" name="bfc_book_store" class="form-control" value="" required>
                                    </div>
                                </div>
                                  <div class="col-md-1">
                                <div class="form-group" app-field-wrapper="file_csv">
                                     <label for="file_csv" class="control-label"></label>
                                     <input type="submit" value="Save" class="btn btn-info import btn-import-submit">
                                    <!--<button type="submit" name="submit" id="button_disable" class="btn btn-info import btn-import-submit" value="button_disable"> Save
                                    </button>-->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          
                        </div>
                        </form>
                        <div id="loading-image" style="display: none; text-align: center;">
					        <img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
				        </div>
				        
                    </div>
                </div>
            </div>
        </div>
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
                                    <table class="table dt-table scroll-responsive tablebusie dt-no-serverside dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 1577px; margin-left: 0px;" id="example33">
                                    <!--<table class="table dt-table scroll-responsive">-->
                                        <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Book Title</th>
                                            <th>Total Books</th>
                                            <th>Amazon</th>
                                            <th>Flipkart</th>
                                            <th>BFC Book Store</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <!-- lagao bhaiya code -->
                                        <tbody>
                                        <?php $i=1; foreach($list as $getdata){ ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            
                                            <td><?= $getdata->book_title?></td>
                                            <td <?php if ($getdata->total_books <= 2){?>style="background-color:#f34456;" <?php }else{} ?>><?= $getdata->total_books?></td>
                                            <td <?php if ($getdata->amazone <= 2){?>style="background-color:#f34456;" <?php }else{} ?> ><?= $getdata->amazone;?></td>
                                            <td <?php if ($getdata->flipkart <= 2){?>style="background-color:#f34456;" <?php }else{} ?> ><?= $getdata->flipkart?></td>
                                            <td <?php if ($getdata->bfcstore <= 2){?>style="background-color:#f34456;" <?php }else{} ?> ><?= $getdata->bfcstore?></td>
                                            
                                            <td><a href="javascript:void(0)" data-totalbook="<?php echo $getdata->total_books;?>" data-id="<?php echo $getdata->id;?>" data-a_name="<?= $getdata->author_name?>" data-bookt="<?= $getdata->book_title?>" data-flipkart="<?= $getdata->flipkart?>" data-Amazon="<?= $getdata->amazone?>" data-bfcstore="<?= $getdata->bfcstore?>" id="edit" class="edit">Edit</a>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0)" class="delete" data-id="<?php echo $getdata->id;?>" data-package="" data-service="" id="delete">Delete</a></td>
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
                <h5 class="modal-title">Subscribe our Newsletter</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo base_url();?>admin/InventoryDashboard/update_inventory" >
                        <div class="row">
                            <input type="hidden" class="form-control serviceId" name="serviceId">
                                
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="book_title" class="control-label">Book Title</label>
                                        <input type="text" id="book_title" name="book_title" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" app-field-wrapper="email">
                                        <label for="totbook" class="control-label">Total Books</label>
                                        <input type="text" id="totbook" name="totbook" class="form-control" value="" required>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="FlipKart" class="control-label">FlipKart</label>
                                        <input type="text" id="FlipKart" name="FlipKart" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="Amazon" class="control-label">Amazon</label>
                                        <input type="text" id="Amazon" name="Amazon" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="bfc_book_store" class="control-label">BFC Book Store</label>
                                        <input type="text" id="bfc_book_store" name="bfc_book_store" class="form-control" value="" required>
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
        var a_name = $(this).attr("data-a_name");
        var bookt = $(this).attr("data-bookt");
        var flipkart = $(this).attr("data-flipkart");
        var Amazon = $(this).attr("data-Amazon");
        var bfcstore = $(this).attr("data-bfcstore");
        var totbook = $(this).attr("data-totalbook");
        var title="Update Inventory";
        
        
        
        $("#myModal .modal-title").html(title);
        $("#myModal .serviceId").val(id);
        $("#myModal #author_name").val(a_name);
        $("#myModal #book_title").val(bookt);
        $("#myModal #FlipKart").val(flipkart);
        $("#myModal #Amazon").val(Amazon);
        $("#myModal #totbook").val(totbook);
        $("#myModal #bfc_book_store").val(bfcstore);
        $("#myModal").modal('show');
    });

  $('.delete').click(function(){
       var Id = $(this).attr("data-id");
       var classd="Delete";
       $.ajax({
    		type: "POST",
    		url: "<?php echo admin_url('InventoryDashboard/delete_inventory'); ?>",
    		data: {'Id': Id},
    		dataType: "html",
    		success: function(data){
                alert_float(classd, "Delete Successfully");
                $('#loading-image').hide();
                window.location.href = "add";
    		}
	    });
    });    
});
</script>   
<?php init_tail(); ?>
</body>
</html>