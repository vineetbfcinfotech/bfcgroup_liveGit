<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Edit Sub Services</h3>  
                                <?php //print_r($subservice);?>
                            </div>
                        </div>
                        <form action="<?php echo site_url(); ?>admin/ProjectCordinatorDashboard/update_subservice" method="post"
                           name="form1" id="form1">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" app-field-wrapper="email">
                                        <label for="email" class="control-label">Select Package</label>
                                        <select name="Package" class="form-control statuschangelead dropone" data-width="100%"  data-live-search="true"  id="Package"
                                        data-lead-id="">
                                            <option value="0" <?php if($subservice[0]->packageid == "0"){ echo "selected";}?>>Select Package</option>
                                            <option value="1" <?php if($subservice[0]->packageid == "1"){ echo "selected";}?>>Standard</option>
                                            <option value="2" <?php if($subservice[0]->packageid == "2"){ echo "selected";}?>>Customized</option>
                                            <option value="3" <?php if($subservice[0]->packageid == "3"){ echo "selected";}?>>Standard Customized</option>
                                        </select>
                                        <input type="hidden" class="form-control subserviceId" name="subserviceId" value="<?php echo $subservice[0]->id;?>">
                                        <input type="hidden" class="form-control packageId" name="packageId" value="<?php echo $subservice[0]->packageid;?>">
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv" id="serList">
                                         <label for="email" class="control-label">Select Service</label>
                                        <select name="service" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="service"
                                        data-lead-id="">
                                            <option value="0" selected>Select Package</option>
                                            <?php $i =0; foreach($subservicelist as $data){?>
                                               <option <?php if($subservice[0]->serviceid == $i){ echo "selected";}?> value="<?php echo $data->id?>"><?php echo $data->service_name?></option> 
                                            <?php $i++; }?>
                                           
                                        </select>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="email" class="control-label">Select Book Type</label>
                                        <select name="bookType" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="bookType"
                                        data-lead-id="">
                                            <option value="0">Select Book type</option>
                                            <option value="ebook" <?php if($subservice[0]->book_type == "ebook"){ echo "selected";}?>>Ebook</option>
                                            <option value="paperback" <?php if($subservice[0]->book_type == "paperback"){ echo "selected";}?>>Paperback</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                       <div class="form-group" app-field-wrapper="file_csv">
                                            <label for="email" class="control-label">Select Package Type</label>
                                            <select name="packageType" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="packageType"
                                            data-lead-id="">
                                                <option value="0" selected>Select Package Type</option>
                                                <option value="essential" <?php if($subservice[0]->package_value == "essential"){ echo "selected";}?>>Essential</option>
                                                <option value="regular" <?php if($subservice[0]->package_value == "regular"){ echo "selected";}?>>Regular</option>
                                                <option value="superior" <?php if($subservice[0]->package_value == "superior"){ echo "selected";}?>>Superior</option>
                                                <option value="premium" <?php if($subservice[0]->package_value == "premium"){ echo "selected";}?>>Premium</option>
                                            </select>
                                        </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                       <div class="form-group" app-field-wrapper="file_csv">
                                            <label for="file_csv" class="control-label">Enter Sub Service Name</label>
                                            <input type="text" id="subserviceName" name="subserviceName" class="form-control" value="<?= $subservice[0]->subservice_name?>" required>
                                        </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                       <div class="form-group" app-field-wrapper="file_csv">
                                            <label for="file_csv" class="control-label">Enter Cost</label>
                                            <input type="text" id="cost" name="cost" class="form-control" value="<?= $subservice[0]->cost?>" required>
                                        </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                
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
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#button_disable').click(function(){
            var package = $('#Package').val();
            var Service= $('#Service').val();
            var classd="Added";
            $('#loading-image').show();
            $.ajax({
        		type: "POST",
        		url: "<?php echo admin_url('ProjectCordinatorDashboard/save_service'); ?>",
        		data: {'package': package, 'Service':Service},
        		dataType: "html",
        		success: function(data){
                    alert_float(classd, "Successfully");
                    $('#Service').val("");
                    $('#Package').prop('selectedIndex',0);
                    $('#loading-image').hide();
        		}
    	    });
        });
        
        $('select.dropone').change(function(){
            var serviceId = $(this).children("option:selected").val();
            $.ajax({
            	type: "POST",
            	url: "<?php echo admin_url('ProjectCordinatorDashboard/get_service'); ?>",
            	data: {'serviceId': serviceId},
            	dataType: "html",
            	success: function(data){
            	    //var html= '<label for="email" class="control-label">Select Service</label><select name="Service" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="Service" data-lead-id=""><option value="0" selected>Select Package</option>';
                    var html= '<label for="email" class="control-label">Select Service</label><select name="Service" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="Service" data-lead-id="">';
                    var obj = JSON.parse(data);
                    obj.forEach(function(element) { 
                        html += '<option value="'+element.id+'" selected>'+element.name+'</option>';
                    });
                    html += '</select>';
                    $('#serList').html(html);
                    $('#loading-image').hide();
                     var packageId = $('.statuschangelead').val();
                    $(".packageId").val(packageId);
            	}
            });
        });
    });
    $('#update').click(function(){
        var packageId = $('.statuschangelead').val();
        $(".packageId").val(packageId);
    });
</script>
<?php init_tail(); ?>
</body>
</html>