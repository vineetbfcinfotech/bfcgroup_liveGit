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
                                <h3>List Sub Services</h3>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <!--<th>Id</th>
                                            <th>Service Id</th> -->
                                            <th>Package Name</th>
                                            <th>Service Name</th>
                                            <th>Book Type</th>
                                            <th>Package Type</th>
                                            <th>Subservice Name</th>
                                            <th>Cost</th>
                                            <th>Action</th>
                                        </tr>
                                        <?php $i=1; foreach($subservices as $getdata){ ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                           <!-- <td><?= $getdata->id ?></td>
                                            <td><?=$getdata->serviceid?></td>-->
                                            <td><?php if($getdata->packageid ==1){ echo "Standard";}elseif($getdata->packageid ==2){ echo "customized";}else { echo "Standard customized";}?></td>
                                            <td><?= $getdata->service_name?></td>
                                            <td><?= $getdata->book_type?></td>
                                            <td><?= $getdata->package_value?></td>
                                            <td><?= $getdata->subservice_name?></td>
                                            <td><?= $getdata->cost?></td>
                                            
                                            <td>
                                                <a href="<?php echo admin_url('ProjectCordinatorDashboard/edit_subservice/');echo $getdata->id; ?>">Edit</a>
                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                                <a href="javascript:void(0)" class="delete" data-id="<?php echo $getdata->id;?>" data-package="<?php echo $getdata->packageid;?>" data-service="<?php echo $getdata->service_name;?>" id="delete">Delete</a>
                                                
                                                <!--<a href="javascript:void(0)" data-cost="<?php echo $getdata->cost;?>" data-subserviceName="<?php echo $getdata->subservice_name;?>" data-packageValue="<?php echo $getdata->package_value;?>" data-bookType="<?php echo $getdata->book_type;?>" data-id="<?php echo $getdata->id;?>" data-package="<?php echo $getdata->packageid;?>" data-service="<?php echo $getdata->service_name;?>" id="edit" class="edit">Edit</a> 
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                                <a href="javascript:void(0)" class="delete" data-id="<?php echo $getdata->id;?>" data-package="<?php echo $getdata->packageid;?>" data-service="<?php echo $getdata->service_name;?>" id="delete">Delete</a>-->
                                            </td>
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
                
                <form action="<?php echo site_url(); ?>admin/ProjectCordinatorDashboard/update_subservice" method="post"
                           name="form1" id="form1">
                    <div class="form-group" >
                        <input type="hidden" class="form-control subserviceId" name="subserviceId">
                        <input type="hidden" class="form-control packageId" name="packageId">
                    </div>
                    <div class="form-group packageNamedata" >
                        
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Service Name</label>
                        <input type="text" class="form-control serviceName" placeholder="Service Name" name="serviceName">
                    </div>
                    
                     <div class="form-group">
                        <label for="email" class="control-label">Book Type</label>
                        <input type="text" class="form-control bookType" placeholder="Service Name" name="bookType">
                    </div>
                     <div class="form-group">
                        <label for="email" class="control-label">Package Type</label>
                        <input type="text" class="form-control packageType" placeholder="Service Name" name="packageType">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="control-label">Subservice Name</label>
                        <input type="text" class="form-control subserviceName" placeholder="Service Name" name="subserviceName">
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Cost</label>
                        <input type="text" class="form-control cost" placeholder="Service Name" name="cost">
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
        var title="Update Sub Service";
        
        var cost = $(this).attr("data-cost");
        var subserviceName = $(this).attr("data-subserviceName");
        var packageType = $(this).attr("data-packageValue");
        var bookType = $(this).attr("data-bookType");
        var service= $(this).attr("data-service");
        
        var package= $(this).attr("data-package");
        var packageData ='';
       if(package == 1){
            packageData = '<label for="email" class="control-label">Select Package</label><select onchange="myFunction()" name="Package" class="form-control statuschangelead dropone" data-width="100%"  data-live-search="true"  id="Package choice" data-lead-id=""><option value="0">Select Package</option><option value="1" selected>Standard</option><option value="2">Customized</option><option value="3">Standard Customized</option></select>';
        }else if(package == 2){
            packageData = '<label for="email" class="control-label">Select Package</label><select onchange="myFunction()" name="Package" class="form-control statuschangelead dropone" data-width="100%"  data-live-search="true"  id="Package1 choice1" data-lead-id=""><option value="0">Select Package</option><option value="1">Standard</option><option value="2" selected>Customized</option><option value="3">Standard Customized</option></select>';
        }else{
            packageData = '<label for="email" class="control-label">Select Package</label><select onchange="myFunction()" name="Package" class="form-control statuschangelead dropone" data-width="100%"  data-live-search="true"  id="Package2 choice2" data-lead-id=""><option value="0">Select Package</option><option value="1">Standard</option><option value="2">Customized</option><option value="3" selected>Standard Customized</option></select>';
        }
        
        $("#myModal .modal-title").html(title);
        $("#myModal .subserviceId").val(id);
        $("#myModal .cost").val(cost);
        $("#myModal .subserviceName").val(subserviceName);
        $("#myModal .packageType").val(packageType);
        $("#myModal .bookType").val(bookType);
        $("#myModal .packageNamedata").html(packageData);
        $("#myModal .serviceName").val(service);
        $("#myModal").modal('show');
    });
    $('.delete').click(function(){
       var serviceId = $(this).attr("data-id");
       var classd="Delete";
       $.ajax({
    		type: "POST",
    		url: "<?php echo admin_url('ProjectCordinatorDashboard/delete_subservice'); ?>",
    		data: {'serviceId': serviceId},
    		dataType: "html",
    		success: function(data){
                alert_float(classd, "Successfully");
                $('#loading-image').hide();
                alert_float(classd, "Successfully");
                window.location.href = "subservice_list";
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