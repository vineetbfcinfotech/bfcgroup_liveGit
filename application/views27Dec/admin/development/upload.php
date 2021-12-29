<?php init_head(); ?>
<div id="wrapper">
   <?php init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h2><?php echo _l('upload_basket'); ?></h2>
                  <hr class="hr-panel-heading">
                  <?php if(has_permission('manage_product_basket','','create')){ ?>
                  <div class="_buttons">
                     <button href="" class="btn btn-info pull-left display-block" data-toggle="modal" data-target="#import_modal" ><?php echo _l('upload_basket'); ?></button>
                  </div>
                  <?php } ?>
                  <div class="clearfix"></div>
                  <hr class="hr-panel-heading">
                  <div id="loading-image" style="display: none; text-align: center;"><img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>"></div>
                  <div class="ajax-data">
                  </div>
               </div>
            </div>
         </div>
      </div>
	  
	  <?php //print_r($advisory);exit;?>
      <div id="import_modal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Upload Basket</h4>
               </div>
               <div class="modal-body">
                     <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-12 col-sm-12">
                        <div class="panel panel-info" >
                           <div style="padding-top:30px" class="panel-body" >
                              <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                              <form id="import_csv" class="form-horizontal" enctype="multipart/form-data">
								<label><b>CSV File</b></label>
                                 <div style="margin-bottom: 25px" class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                    <input id="sachems" type="file" class="form-control" name="sachems" value="" accept=".csv" >     
								 </div>
                                 <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->
                                    <div class="col-sm-12 controls">
                                       <button type="submit" id=""  class="btn btn-success">Submit</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
	  
	  
   </div>
</div>
<script>
$(document).ready(function(){
	$('#import_csv').on('submit', function(event){
		event.preventDefault();
		var fileName = $("#sachems").val();
		var ext = fileName.split('.').pop();
		if(ext != "csv"){
			alert_float("warning", "Please upload CSV file!");
			return false;
		}
		var formdata = $('#import_csv').serialize();
		//alert(new FormData(this));
		$.ajax({
			url: "<?php echo admin_url(); ?>development/import_csv",
			method: "POST",
			data: new FormData(this),
			processData:false,
			contentType:false,
			cache:false,
			async:false,
			success:function(data)
			{
				$('#import_modal').modal('hide');
			    alert_float("success", "Imported Successfully!");
				$("#loading-image").show();
				$(".table").hide();
				$.ajax({
					url: "<?php echo admin_url(); ?>development/getImportedData",
					method: "POST",
					//data: new FormData(this),
					success:function(data)
					{
						$('.ajax-data').html(data);
						$("#loading-image").hide();
						$(".table").show();
					}
				});
			}
		});
	});
	$("#loading-image").show();
	$(".table").hide();
	$.ajax({
		url: "<?php echo admin_url(); ?>development/getImportedData",
		method: "POST",
		//data: new FormData(this),
		success:function(data)
		{
			$('.ajax-data').html(data);
			$("#loading-image").hide();
			$(".table").show();
		}
	});
	
		
});
</script>
<?php init_tail(); ?>
</body>
</html>

