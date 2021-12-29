<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        
         <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>List MIS Printing Order</h3>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table id="example"  class="table dt-table scroll-responsive example33 dt-no-serverside dataTable no-footer" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Author Name</th>
                                            <th>Book Title</th>
                                            <th>Contact No</th>
                                            
                                            <th>Pages</th>
                                            <th>Size</th>
                                            <th>Text Color</th>
                                            <th>Text Paper</th>
                                            <th>Cover Paper</th>
                                            <th>Binding</th>
                                            <th>Lamination</th>
                                            <th>Book Qty</th>
                                           
                                            <th>Book Qty Deliver To Author</th>
                                            <th>Book Qty Deliver To Publisher</th>
                                            <th>Publisher</th>
                                            <th>AuthorAddress</th>
                                             <th>Per Copy Rate</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $cou=1; for($i=0; $i<count($result); $i++){ ?>
                                        <tr>
                                            
                                            <td><?php echo $cou ?></td>
                                            <td><?php echo $result[$i]->AuthorName?></td>
                                            <td><?php echo $result[$i]->BookTitle?></td>
                                            <td><?php echo $result[$i]->ContactNo?></td>
                                      
                                            <td><?php echo $result[$i]->Pages?></td>
                                            <td><?php echo $result[$i]->Size?></td>
                                            <td><?php echo $result[$i]->TextColor?></td>
                                            <td><?php echo $result[$i]->TextPaper?></td>
                                            <td><?php echo $result[$i]->CoverPaper?></td>
                                            <td><?php echo $result[$i]->Binding?></td>
                                            <td><?php echo $result[$i]->Lamination?></td>
                                                  <td><?php echo $result[$i]->BookQty?></td>

                                        
                                            <!-- <td><?php echo $result[$i]->PerCopyRate?></td> -->

                                           
                                         <!--    <td><?php echo $result[$i]->BookQty - $result[$i]->PerCopyRate?></td> -->
                                            <!-- <td><?php echo $result[$i]->BookQtyDeliverToPublisher?></td> -->
                                            <td><?php echo $result[$i]->BookQtyDeliverToAuthor?></td>
                                            <td><?php echo $result[$i]->BookQtyDeliverToPublisher?></td>
                                                <td><?php echo $result[$i]->Publisher?></td>
                                                <td><?php echo $result[$i]->AuthorAddress?></td>

                                            <!-- <td><?php echo $result[$i]->Total?></td> -->
                                            <td><?php echo $result[$i]->PerCopyRate?></td>
                                                <td><?php echo $result[$i]->Total?></td>
                                            <!--<td><a href="javascript:void(0)" data-id="<?php echo $getdata->id;?>" data-a_name="<?= $getdata->author_name?>" data-bookt="<?= $getdata->book_title?>" data-flipkart="<?= $getdata->flipkart?>" data-Amazon="<?= $getdata->amazone?>" data-bfcstore="<?= $getdata->bfcstore?>" id="edit" class="edit">Edit</a>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0)" class="delete" data-id="<?php echo $getdata->id;?>" data-package="" data-service="" id="delete">Delete</a></td>-->
                                        </tr>
                                        <?php $cou++;}?>
                                        
                                    </tbody>
                                    <!--<tfoot>-->
                                    <!--    <tr>-->
                                    <!--        <th>Sr. No.1</th>-->
                                    <!--        <th>Author Name</th>-->
                                    <!--        <th>Book Title</th>-->
                                    <!--        <th>Contact No</th>-->
                                    <!--        <th>Book Qty</th>-->
                                    <!--        <th>Pages</th>-->
                                    <!--        <th>Size</th>-->
                                    <!--        <th>Text Color</th>-->
                                    <!--        <th>Text Paper</th>-->
                                    <!--        <th>Cover Paper</th>-->
                                    <!--        <th>Binding</th>-->
                                    <!--        <th>Lamination</th>-->
                                    <!--        <th>Per Copy Rate</th>-->
                                    <!--        <th>Total</th>-->
                                    <!--        <th>Book Qty Deliver To Author</th>-->
                                    <!--        <th>Book Qty Deliver To Publisher</th>-->
                                    <!--        <th>Publisher</th>-->
                                    <!--        <th>AuthorAddress</th>-->
                                    <!--    </tr>-->
                                    <!--</tfoot>-->
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
                    <form action="<?php echo site_url(); ?>admin/leads/uploadleadsbyPc" method="post"
                              enctype="multipart/form-data" name="form1" id="form1">
                      
                          <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" app-field-wrapper="userfile">
                                        <label for="userfile" class="control-label">Choose CSV File</label>
                                        <input type="file" id="userfile" name="userfile" class="form-control" onchange="myFunction()">
                                    </div>
                                    
                                    <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="file_csv" class="control-label">File Name</label>
                                        <input type="text" id="file_name" name="file_name" class="form-control" value="" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-info import btn-import-submit"> Upload</button>
                                </div>
                        
                    </div>
                    </form>
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
        var title="Update Service";
        
        
        
        $("#myModal .modal-title").html(title);
        $("#myModal .serviceId").val(id);
        $("#myModal #author_name").val(a_name);
        $("#myModal #book_title").val(bookt);
        $("#myModal #FlipKart").val(flipkart);
        $("#myModal #Amazon").val(Amazon);
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