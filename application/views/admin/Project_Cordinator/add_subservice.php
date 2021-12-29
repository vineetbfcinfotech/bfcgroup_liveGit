<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Add Sub Services</h3>  
                            </div>
                        </div>
                        <form action="<?php echo site_url(); ?>admin/ProjectCordinatorDashboard/save_subservicedata" method="post"
                           name="form1" id="form1">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" app-field-wrapper="email">
                                        <label for="email" class="control-label">Select Package</label>
                                        <select name="Package" class="form-control statuschangelead dropone" data-width="100%"  data-live-search="true"  id="Package"
                                        data-lead-id="">
                                            <option value="0" selected>Select Package</option>
                                            <option value="1">Standard</option>
                                            <option value="2">Customized</option>
                                            <option value="3">Standard Customized</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv" id="serList">
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="email" class="control-label">Select Book Type</label>
                                        <select name="bookType" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="bookType"
                                        data-lead-id="">
                                            <option value="0" selected>Select Book type</option>
                                            <option value="ebook">Ebook</option>
                                            <option value="paperback">Paperback</option>
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
                                                <option value="essential">Essential</option>
                                                <option value="regular">Regular</option>
                                                <option value="superior">Superior</option>
                                                <option value="premium">Premium</option>
                                                <option value="elite">Elite</option>
                                                <option value="rapid">Rapid</option>
                                            </select>
                                        </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                       <div class="form-group" app-field-wrapper="file_csv">
                                            <label for="file_csv" class="control-label">Enter Sub Service Name</label>
                                            <input type="text" id="subServiceName" name="subServiceName" class="form-control" value="" required>
                                        </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                       <div class="form-group" app-field-wrapper="file_csv">
                                            <label for="file_csv" class="control-label">Enter Sub Service Value</label>
                                            <input type="text" id="subServiceNameValue" name="subServiceNameValue" class="form-control" value="" required>
                                        </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                       <div class="form-group" app-field-wrapper="file_csv">
                                            <label for="file_csv" class="control-label">Enter Cost</label>
                                            <input type="text" id="cost" name="cost" class="form-control" value="" required>
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
            	}
            });
        });
    });
</script>
<?php init_tail(); ?>
</body>
</html>