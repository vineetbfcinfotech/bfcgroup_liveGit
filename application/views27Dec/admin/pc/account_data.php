<?php init_head(); ?>

	<style type="text/css">
    .table-scroll {
  position: relative;
  width:100%;
  z-index: 1;
  margin: auto;
  overflow: auto;
  height: 400px;
}
.table-scroll table {
  width: 100%;
  min-width: 1280px;
  margin: auto;
  border-collapse: separate;
  border-spacing: 0;
}
.table-wrap {
  position: relative;
}

.table-scroll thead th {
  background: #eee;
  color: #000 !important;
  position: -webkit-sticky;
  position: sticky;
  top: 0;
}
th:first-child {
  position: -webkit-sticky;
  position: sticky;
  left: 0;
  z-index: 2;
  background: #ccc;
}
th:nth-child(2) {
  position: -webkit-sticky;
  position: sticky;
  left: 0;
  z-index: 2;
  background: #ccc;
}
thead th:first-child,
thead th:nth-child(2),
tfoot th:first-child {
  z-index: 5;
}
tfoot th:nth-child(2) {
  z-index: 5;
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

			url: "<?php echo admin_url('account/getdata1'); ?>",

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

			var data_remaining = $(this).attr('data-remaining_final');

			var payment_id = $(this).data('id');
			var pcost = $(this).data('pcost');
			var author = $(this).data('author');
			var book = $(this).data('book');
// alert(pcost);
var payment_status = this.value;
var assigned_by_id = $("#assigned").val();

if(payment_status == 0){

	var datat = "Not Received.";

	var classd = "warning";

}else{

	var datat = "Received.";

	var classd = "success";		

}
if (payment_status==1) {
	$(document).on('click', '#submit_booking_amount', function() {
	
		$("#submit_booking_amount").prop('disabled', true);

		var data = $('#received_booking_amount').val();
		
			//alert(data_remaining);
			
			$.ajax({

				type: "POST",

				url: "<?php echo admin_url('account/changePaymentStatus'); ?>",

				data: {'payment_id': payment_id, 'payment_status':payment_status, 'data':data, 'author':author, 'book':book},

				dataType: "html",

				success: function(data){
					
					alert_float(classd, "Payment "+datat);
					window.location.reload();
				}

			});
			
		});
}else if (payment_status==2) {
	$(document).on('click', '#submit_booking_amount', function() {
		$("#submit_booking_amount").prop('disabled', true);
		var data = $('#received_booking_amount').val();
		
			//alert(data_remaining);
			
			$.ajax({

				type: "POST",

				url: "<?php echo admin_url('account/changePaymentStatus'); ?>",

				data: {'payment_id': payment_id, 'payment_status':payment_status, 'data':data, 'author':author, 'book':book},

				dataType: "html",

				success: function(data){
					
					alert_float(classd, "Payment "+datat);
					window.location.reload();
				}

			});
			
		});
}else if (payment_status==3) {
	$(document).on('click', '#submit_booking_amount', function() {
$("#submit_booking_amount").prop('disabled', true);
		var data = $('#received_booking_amount').val();
		
			//alert(data_remaining);
			var decimal_remaining =Number(data_remaining).toFixed(2);
			if (data_remaining==data || decimal_remaining==data) {


				$.ajax({

					type: "POST",

					url: "<?php echo admin_url('account/changePaymentStatus'); ?>",

					data: {'payment_id': payment_id, 'payment_status':payment_status, 'data':data, 'author':author, 'book':book},

					dataType: "html",

					success: function(data){
						
						alert_float(classd, "Payment "+datat);
						window.location.reload();
					}

				});
			}else{
				$("#error_msg_data_remaining").html('Paid Amount Should be equal to Remaining Amount');
					$.ajax({

					type: "POST",

					url: "<?php echo admin_url('account/notification_to_pm'); ?>",

					data: {'payment_id': payment_id, 'payment_status':payment_status, 'data':data, 'author':author, 'book':book},

					dataType: "html",

					success: function(data){
						
					}

				});
			}
			
		});
}
});


	});

</script>

<?php init_tail(); ?>

</body>

</html>