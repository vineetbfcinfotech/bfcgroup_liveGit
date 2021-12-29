<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <a href="<?php echo site_url(); ?>admin/" class="btn btn-primary">Back</a> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Add MISC Project</h3>  
                            </div>
                        </div>
                        <form action="<?php echo site_url(); ?>admin/pm_lead/save_misc" method="post"
                           name="form1" id="form1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                       <div class="col-md-2">
                                       <div class="form-group" app-field-wrapper="file_csv">
                                            <label for="file_csv" class="control-label">Alloted to</label>
                                           
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group" app-field-wrapper="file_csv">
                                             <select class="form-control" name="alloted_to" id="alloted_to">
                                                <option value="" disabled>--Graphic Designer--</option>
                                                <option value="83">Surabhi</option>
                                                <option value="84">Affan</option>
                                                <option value="" disabled>--Format Editing--</option>
                                                <option value="81">Amrendra</option>
                                                <option value="82">Gaurav</option>
                                                <option value="85">Manish</option>
                                                <option value="" disabled>--Proof Reading--</option>
                                                <option value="80">Varuna</option>
                                                <option value="90">Ravindra</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                         </div>
                                    </div>
                                    
                                </div>
                            </div>
                        <div class="row">
                       
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                     <label for="file_csv" class="control-label">Enter Work Description</label>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="form-group" app-field-wrapper="file_csv">
                                       
                                        <textarea id="Description" name="Description" class="form-control" required rows="10"></textarea>
              
                                    </div>
                                     </div>
                                     <div class="col-md-4">
                                         </div>
                                    </div> 
                                   
                                </div>
                       
                        
                        
                            <div class="col-md-4">
                                <div class="form-group" app-field-wrapper="file_csv">
                                     <label for="file_csv" class="control-label"></label>
                                    <button type="submit" name="submit" id="button_disable" class="btn btn-info import btn-import-submit" value="button_disable"> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                        </form>
                        <div id="loading-image" style="display: none; text-align: center;">
					        <img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
				        </div>
                    </div>
                </div>
            </div>
        </div>
         <!--  addd her -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>List MISC</h3>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table dt-table scroll-responsive">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Alloted To</th>
                                                <th>Description</th>
                                                <th>Alloted Date</th>
                                                <th>Download</th>
                                                
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1; foreach($list as $getdata){ ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                               
                                                <td><?php if($getdata->alloted_to== 84) {echo "Affan"; }elseif($getdata->alloted_to== 83){ echo "Surabhi"; }elseif($getdata->alloted_to== 81){ echo "Amrendra"; }elseif($getdata->alloted_to== 82){ echo "Gaurav"; }elseif($getdata->alloted_to== 85){ echo "Manish"; }elseif($getdata->alloted_to== 80){ echo "Varuna"; }elseif($getdata->alloted_to== 90){ echo "Ravindra"; }?></td> 
                                                <td><?= $getdata->description?></td>
                                                
                                                <td><?= $getdata->alloted_date?></td>
                                                <td> <?php if($getdata->file_path !=""){?>
                                                <a download href="<?php echo base_url(); ?>/assets/cover/misc/<?php echo $getdata->file_path;?>">Download</a> 
                                                <?php }else {  echo "Not Upload";} ?>
                                                </td>
                                                
                                                <td><a href="javascript:void(0)" data-id="<?php echo $getdata->id;?>" data-alloted_to="<?= $getdata->alloted_to?>" data-description="<?= $getdata->description?>" data-flipkart="" data-Amazon="" data-bfcstore="" id="edit" class="edit">Edit</a>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0)" class="delete" data-id="<?php echo $getdata->id;?>" data-package="" data-service="" id="delete">Delete</a></td>
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
                <form method="post" action="<?php echo base_url();?>admin/pm_lead/update_misc" >
                        <div class="row">
                            <input type="hidden" class="form-control miscId" name="miscId">
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="file_csv" class="control-label">Alloted to</label>
                                        <select class="form-control" name="alloted_to" id="alloted_to">
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="bfc_book_store" class="control-label">Description</label>
                                        <input type="text" id="Description" name="Description" class="form-control Description" value="" required>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" app-field-wrapper="file_csv">
                                     <label for="file_csv" class="control-label"></label>
                                     <input type="submit" value="Update" class="btn btn-info import btn-import-submit">
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
        var alloted_to = $(this).attr("data-alloted_to");
        var description = $(this).attr("data-description");
        var title="Update MISC";
        
        $("#myModal .modal-title").html(title);
        $("#myModal .Description").val(description);
        
        if(alloted_to == 84){
            var html_data = '<option value="" disabled>--Graphic Designer--</option><option value="83">Surabhi</option><option value="84" selected>Affan</option><option value="" disabled>--Format Editing--</option><option value="81">Amrendra</option><option value="82">Gaurav</option><option value="85">Manish</option><option value="" disabled>--Proof Reading--</option><option value="80">Varuna</option><option value="90">Ravindra</option>';
        }else if(alloted_to == 83){
            var html_data = '<option value="" disabled>--Graphic Designer--</option><option value="83" selected>Surabhi</option><option value="84">Affan</option><option value="" disabled>--Format Editing--</option><option value="81">Amrendra</option><option value="82">Gaurav</option><option value="85">Manish</option><option value="" disabled>--Proof Reading--</option><option value="80">Varuna</option><option value="90">Ravindra</option>';
        }else if(alloted_to == 81){
            var html_data = '<option value="" disabled>--Graphic Designer--</option><option value="83">Surabhi</option><option value="84">Affan</option><option value="" disabled>--Format Editing--</option><option value="81" selected>Amrendra</option><option value="82">Gaurav</option><option value="85">Manish</option><option value="" disabled>--Proof Reading--</option><option value="80">Varuna</option><option value="90">Ravindra</option>';
        }else if(alloted_to == 82){
            var html_data = '<option value="" disabled>--Graphic Designer--</option><option value="83">Surabhi</option><option value="84">Affan</option><option value="" disabled>--Format Editing--</option><option value="81">Amrendra</option><option value="82" selected>Gaurav</option><option value="85">Manish</option><option value="" disabled>--Proof Reading--</option><option value="80">Varuna</option><option value="90">Ravindra</option>';
        }else if(alloted_to == 80){
            var html_data = '<option value="" disabled>--Graphic Designer--</option><option value="83">Surabhi</option><option value="84">Affan</option><option value="" disabled>--Format Editing--</option><option value="81" >Amrendra</option><option value="82">Gaurav</option><option value="85">Manish</option><option value="" disabled>--Proof Reading--</option><option value="80" selected>Varuna</option><option value="90">Ravindra</option>';
        }else if(alloted_to == 90){
            var html_data = '<option value="" disabled>--Graphic Designer--</option><option value="83">Surabhi</option><option value="84">Affan</option><option value="" disabled>--Format Editing--</option><option value="81" selected>Amrendra</option><option value="82">Gaurav</option><option value="85">Manish</option><option value="" disabled>--Proof Reading--</option><option value="80">Varuna</option><option value="90" selected>Ravindra</option>';
        }else if(alloted_to == 85){
            var html_data = '<option value="" disabled>--Graphic Designer--</option><option value="83">Surabhi</option><option value="84">Affan</option><option value="" disabled>--Format Editing--</option><option value="81" selected>Amrendra</option><option value="82">Gaurav</option><option value="85" selected>Manish</option><option value="" disabled>--Proof Reading--</option><option value="80">Varuna</option><option value="90">Ravindra</option>';
        }
        
        
        $("#myModal #alloted_to").html(html_data);
        $("#myModal .miscId").val(id);
        $("#myModal").modal('show');
    });

  $('.delete').click(function(){
       var Id = $(this).attr("data-id");
       var classd="Delete";
       $.ajax({
    		type: "POST",
    		url: "<?php echo admin_url('pm_lead/delete_misc'); ?>",
    		data: {'Id': Id},
    		dataType: "html",
    		success: function(data){
                alert_float(classd, "Delete Successfully");
                $('#loading-image').hide();
                window.location.href = "misc_project";
    		}
	    });
    });    
});
</script>  
<?php init_tail(); ?>
</body>
</html>