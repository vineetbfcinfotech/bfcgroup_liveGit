<?php init_head(); ?>

<div id="wrapper">

   <?php //init_clockinout(); ?>

   <div class="content">

      <div class="row">

         <div class="col-md-12">

            <div class="panel_s">

               <div class="panel-body">

                  <div class="_buttons">

                     <a href="<?php echo base_url();?>admin"  class="btn mright5 btn-info pull-left display-block" style="margin-bottom: 10px;">

                     Back  </a>

                  </div>

                  <div class="clearfix"></div>

				  <div id="loading-image" style="display: none; text-align: center;">

					<img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">

				  </div>

                  <div id="randerData">

				  

				  </div>

               </div>

            </div>

         </div>

      </div>

   </div>

</div>

</div>
<!-- Trigger the modal with a button -->

<script>

$(document).ready(function(){

	$('#loading-image').show();

	$('.randerData').hide();

    $.ajax({

		type: "POST",
		url: "<?php echo admin_url('format_editor/new_project'); ?>",

		dataType: "html",
		success: function(data){
		    
			$('#randerData').html(data);

			$('#loading-image').hide();

			$('.randerData').show();
			
		}

	});

});


  $(document).on('click', '.take', function() {
      alert("hii");
          var project_id   = $(this).data('id');

          	$.ajax({

			type: "POST",

			url: "<?php echo admin_url('format_editor/project_in_process'); ?>",

			data: {'project_id': project_id},

			dataType: "html",

			success: function(data){

				alert_float(classd, "Payment "+datat);
	 
			}

		});
  }); 
</script>

<?php init_tail(); ?>

</body>

</html>