<?php init_head(); ?>
 
<div id="wrapper">
   <?php init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h2><?php echo _l('upload notification'); ?></h2>
                  <hr class="hr-panel-heading">
                  <?php if(has_permission('manage_product_basket','','create')){ ?>
                  <div class="_buttons">
                     <button href="" class="btn btn-info pull-left display-block" data-toggle="modal" data-target="#import_modal" ><?php echo _l('upload notification'); ?></button>
                  </div>
                  <?php } ?>
                  <div class="clearfix"></div>
                  <hr class="hr-panel-heading">
                  <div id="loading-image" style="display: none; text-align: center;"><img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>"></div>
                  <div class="ajax-data">
				  
					<table id='empTable' class='dt-table'>
					   <thead>
						 <tr>
						   <th>Scheme NAV name</th>
						   <th>Category</th>
						   <th>ISIN</th>
						 </tr>
					   </thead>

					 </table>
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 <!-- <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </tfoot>
    </table> -->
	
	
	
	
	
	
	
	
	
	
	
	
					 
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
                  <h4 class="modal-title">Upload Notification</h4>
               </div>
               <div class="modal-body">
                     <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-12 col-sm-12">
                        <div class="panel panel-info" >
                           <div style="padding-top:30px" class="panel-body" >
                              <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                              <form id="import_notify" class="form-horizontal" enctype="multipart/form-data">
								<label><b>Title *</b></label>
                                 <div style="margin-bottom: 25px" class="">
                                    <input id="title" type="text" class="form-control" name="title" value="" >     
								 </div>
								 <label><b>Description *</b></label>
								 <div style="margin-bottom: 25px" class="">
                                    <textarea type="text" class="form-control"  name="description" rows="7" id="description"></textarea>  
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
	$('#import_notify').on('submit', function(event){
		event.preventDefault();
		var title = $("#title").val();
		var description = $("#description").val();
		//var ext = fileName.split('.').pop();
		if((title == "") || (description == "")){
			alert_float("warning", "All field is requried");
			return false;
		}
		var formdata = $('#import_notify').serialize();
		//alert(new FormData(this));
		$.ajax({
			url: "<?php echo admin_url(); ?>development/import_notify",
			method: "POST",
			data: new FormData(this),
			processData:false,
			contentType:false,
			cache:false,
			async:false,
			success:function(data)
			{
				//alert(data);
				 $('#import_modal').modal('hide');
			    alert_float("success", "Imported Successfully!");
				$("#loading-image").show();
				$(".table").hide();
				$.ajax({
					url: "<?php echo admin_url(); ?>development/getImportNotify",
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
	
	/* $('#empTable').DataTable({
	  'ajax': {
		 'url':'<?=admin_url()?>development/scheme_list'
	  },
	  'columns': [
		 { data: 'scheme_name' },
		 { data: 'category' },
		 { data: 'isin' }
	  ]
	}); */
	
	
	/* $(document).ready(function() {
    $('#example').DataTable( {
        "ajax": '<?php echo base_url(); ?>/arrays.txt'
    } );
} ); */


$.ajax({
	url: "<?php echo admin_url(); ?>development/getImportNotify",
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

