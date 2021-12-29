<?php init_head(); ?>
<style>
    table {border-collapse: collapse;border-spacing: 0;width: 100%;border: 1px solid #ddd;}
    th, td {text-align: left;padding: 8px;}
    tr:nth-child(even){background-color: #f2f2f2}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>List Services</h3>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Id</th>
                                            <th>Package Id</th>
                                            <th>Package Name</th>
                                            <th>Service Name</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                        <?php $i=1; foreach($services as $getdata){ ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $getdata->id?></td>
                                            <td><?= $getdata->packageid?></td>
                                            <td><?php if($getdata->packageid ==1){ echo "Standard";}elseif($getdata->packageid ==2){ echo "customized";}else { echo "Standard customized";}?></td>
                                            <td><?= $getdata->service_name?></td>
                                            <td><?= $getdata->created_at?></td>
                                            <td><a href="javascript:void(0)" data-id="<?php echo $getdata->id;?>" data-package="<?php echo $getdata->packageid;?>" data-service="<?php echo $getdata->service_name;?>" id="edit" class="edit">Edit</a>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0)" class="delete" data-id="<?php echo $getdata->id;?>" data-package="<?php echo $getdata->packageid;?>" data-service="<?php echo $getdata->service_name;?>" id="delete">Delete</a></td>
                                        </tr>
                                        <?php $i++;}?>
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
                
                <form action="<?php echo site_url(); ?>admin/ProjectCordinatorDashboard/update_service" method="post"
                           name="form1" id="form1">
                    <div class="form-group" >
                        <input type="hidden" class="form-control serviceId" name="serviceId">
                        <input type="hidden" class="form-control packageId" name="packageId">
                    </div>
                    <div class="form-group packageNamedata" >
                        
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control serviceName" placeholder="Service Name" name="serviceName">
                    </div>
                    <button type="submit" class="btn btn-primary" id="update">Update</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('.edit').click(function(){
        var id = $(this).attr("data-id");
        var title="Update Service";
        var package= $(this).attr("data-package");
        var service= $(this).attr("data-service");
        var packageData ='';
        if(package == 1){
            packageData = '<label for="email" class="control-label">Select Package</label><select name="Package" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="Package" data-lead-id=""><option value="0">Select Package</option><option value="1" selected>Standard</option><option value="2">Customized</option><option value="3">Standard Customized</option></select>';
        }else if(package == 2){
            packageData = '<label for="email" class="control-label">Select Package</label><select name="Package" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="Package1" data-lead-id=""><option value="0">Select Package</option><option value="1">Standard</option><option value="2" selected>Customized</option><option value="3">Standard Customized</option></select>';
        }else{
            packageData = '<label for="email" class="control-label">Select Package</label><select name="Package" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="Package2" data-lead-id=""><option value="0">Select Package</option><option value="1">Standard</option><option value="2">Customized</option><option value="3" selected>Standard Customized</option></select>';
        }
        
        $("#myModal .modal-title").html(title);
        $("#myModal .serviceId").val(id);
        $("#myModal .packageNamedata").html(packageData);
        $("#myModal .serviceName").val(service);
        $("#myModal").modal('show');
    });
    $('.delete').click(function(){
       var serviceId = $(this).attr("data-id");
       //alert(serviceId);
       var classd="Delete";
       $.ajax({
    		type: "POST",
    		url: "<?php echo admin_url('ProjectCordinatorDashboard/delete_service'); ?>",
    		data: {'serviceId': serviceId},
    		dataType: "html",
    		success: function(data){
                alert_float(classd, "Successfully");
                $('#loading-image').hide();
                alert_float(classd, "Successfully");
                window.location.href = "list_service";
    		}
	    });
    });
    
    $('#update').click(function(){
        var packageId = $('.statuschangelead').val();
        $(".packageId").val(packageId);
    });
});
</script>
<?php init_tail(); ?>
</body>
</html>