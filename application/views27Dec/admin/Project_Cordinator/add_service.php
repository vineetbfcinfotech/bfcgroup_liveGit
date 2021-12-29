<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Add services</h3>  
                            </div>
                        </div>
                        <div class="row">
                            <form action="<?php echo site_url(); ?>admin/ProjectCordinatorDashboard/save_service" method="post"
                           name="form1" id="form1">
                                <div class="col-md-3">
                                    <div class="form-group" app-field-wrapper="email">
                                        <label for="email" class="control-label">Select Package</label>
                                        <select name="Package" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="Package"
                                        data-lead-id="">
                                            <option value="0" selected>Select Package</option>
                                            <option value="1">Standard</option>
                                            <option value="2">Customized</option>
                                            <option value="3">Standard Customized</option>
                                            
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <label for="file_csv" class="control-label">Enter Service</label>
                                        <input type="text" id="Service" name="Service" class="form-control" value="" required>
                                    </div>
                                </div>
                            </form>
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
    });
</script>
<?php init_tail(); ?>
</body>
</html>