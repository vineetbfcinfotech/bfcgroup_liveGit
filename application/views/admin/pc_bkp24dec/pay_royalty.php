<?php init_head(); ?>
<style type="text/css">
	.d-flex{
		display: flex;
	}
	.mt-3{
		margin-top: 1rem;
	}
	.pull-right{
		float: right;;
	}
</style>
<div id="wrapper">

	<?php //init_clockinout(); ?>

	<div class="content">

		<div class="row">

			<div class="col-md-12">

				<div class="panel_s">

					<div class="panel-body">

						<div class="_buttons">

							<a href="#"  class="btn mright5 btn-info pull-left display-block" style="margin-bottom: 10px;">

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


<!-- Modal -->

<script>

	$(document).ready(function(){

		$('#loading-image').show();

		$('.randerData').hide();

		$.ajax({

			type: "POST",

			url: "<?php echo admin_url('account/getdata_royalty'); ?>",

		//data: {'package': this.value, 'book_type':book_type}, // <--- THIS IS THE CHANGE

		dataType: "html",

		success: function(data){
			//console.log(data);

			$('#randerData').html(data);

			$('#loading-image').hide();

			$('.randerData').show();

			
		}

	});


		$(document).on('change', '.payment_status', function() {

			$('#myModalmk').modal('show');



			var payment_id = $(this).data('id');

			var payment_status = this.value;
			var assigned_by_id = $("#assigned").val();

			if(payment_status == 0){

				var datat = "Not Received.";

				var classd = "warning";

			}else{

				var datat = "Received.";

				var classd = "success";		

			}
			if (payment_status !=1 ) {
				$.ajax({

					type: "POST",

					url: "<?php echo admin_url('account/changePaymentStatus'); ?>",

					data: {'payment_id': payment_id, 'payment_status':payment_status},

					dataType: "html",

					success: function(data){
				//console.log(data);return false;

				alert_float(classd, "Payment "+datat);

			}

		});
			}
			if (payment_status ==1 ) {

				$(document).on('click', '#submit_booking_amount', function() {

					var data = $('#received_booking_amount').val();
				//alert(payment_id);
				$.ajax({

					type: "POST",

					url: "<?php echo admin_url('account/change_book_PaymentStatus'); ?>",

					data: {'payment_id': payment_id, 'data':data},

					dataType: "html",

					success: function(data){
				//console.log(data);return false;

				alert_float(classd, "Payment "+datat);
				window.location.reload();

			}

		});

			});
			}
		});

		$(document).on('click', '.ssbtn', function() {

			$('#myModalss').modal('show');
			var mrp_id = $(this).attr('data-id');
			var authorname = $('#authorname').val();
			var data_amt = $(this).attr('data-amt');
			var data_name = $(this).attr('data-name');

			var total_return_amt = $(this).attr('data-total_return_amt');
			var total_sale_amt = $(this).attr('data-total_sale_amt');
			document.getElementById("totalroyalty").value = data_amt;
			document.getElementById("authorname").value = data_name;
 //alert(mrp_id);
 //alert(data_name);
  // 	$.ajax({

		// 	type: "POST",

		// 	url: "<?php // echo admin_url('account/updateroyaltystatus'); ?>",

		// 	data: {'name':authorname},

		// 	dataType: "html",

		// 	success: function(data){
		// 		//console.log(data);return false;

		// 		//alert("Status Update Successfully");

		// 	}

		// });

		$(document).on('click', '#submit_royalty_amount', function() {
            $(':input[type="submit"]').prop('disabled', true);
            var data = $('#pay_royalty_amount').val();
            var totalroyalty = $('#totalroyalty').val();
            var authorname = $('#authorname').val();
            //alert(totalroyalty);
            //alert(data);
            var remaining_data = totalroyalty -data;
    // if (totalroyalty > 500) {
        var remaining_data1 = totalroyalty -data;
        if(remaining_data1 >= 0){
         	$.ajax({
        		type: "POST",
        		url: "<?php echo admin_url('account/payroyaltyamount'); ?>",
        		data: {'data1':data, 'royalty':totalroyalty, 'name':authorname , 'mrp_id':mrp_id, 'total_return_amt':total_return_amt, 'total_sale_amt':total_sale_amt},
        		dataType: "html",
        		success: function(data){
        			// alert(data);
    				window.location.reload();
    			}
		    });   
        }else{
    	    $("#error_msg_data").html('Remaining Amount at least 500 Rs...');
        }
    //  }else{
    //  	$("#error_msg_data").html('Remaining Amount at least 500 Rs...');
    //  }

});

	});
	});

</script>

<?php init_tail(); ?>

</body>

</html>