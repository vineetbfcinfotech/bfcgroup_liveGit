<?php init_head(); ?>
<?php //print_r($transation_type);exit; ?>
<div id="wrapper">
   <?php init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h2>Product Basket</h2>
                  <hr class="hr-panel-heading">
                  <?php if(has_permission('manage_product_basket','','create')){ ?>
                  <div class="_buttons">
                     <button href="#" class="btn btn-info pull-left display-block" data-toggle="modal" data-target="#myModal" onclick="refreshForm();" ><?php echo _l('basket'); ?></button>
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
	  
	  
      <div id="myModal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Create Basket</h4>
               </div>
               <div class="modal-body">
                     <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-12 col-sm-12">
                        <div class="panel panel-info" >
                           <div style="" class="panel-body" >
                              <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                              <form id="create_basket" class="" role="form">
							  <input type="hidden" id="basket_id" name="basket_id" value="">
								<div class="" style="margin: 15px;">
                                 <div class="form-data">
									<div style="margin-bottom: 25px" class="form-group">
										<label for="transaction_type"><b>Transaction Type:</b></label>
										<select id="transaction_type" name="transaction_type" class="form-control selectpicker" data-live-search="true">
											<option value="">--Select--</option>
											<?php foreach($transation_type as $transation){ ?>
											<option value="<?php echo $transation->transaction_type; ?>"><?php echo $transation->transaction_type; ?></option>
											<?php } ?>
									   </select>                                        
									 </div>
									 
									 <div style="margin-bottom: 25px" class="form-group">
										<label for="anchoring"><b>Anchoring:</b></label>
										<select id="anchoring" name="anchoring" class="form-control selectpicker" data-live-search="true">
											<option value="">--Select--</option>
											<?php foreach($anchoring as $anchor){ ?>
											<option value="<?php echo $anchor->anchoring; ?>"><?php echo $anchor->anchoring; ?></option>
											<?php } ?>
									   </select> 
									 </div>
									 <div style="margin-bottom: 25px" class="form-group">
										<label for="option"><b>Option:</b></label>
										<select id="option" name="option" class="form-control selectpicker" data-live-search="true">
											<option value="">--Select--</option>
											<?php foreach($options as $option){ ?>
											<option value="<?php echo $option->option; ?>"><?php echo $option->option; ?></option>
											<?php } ?>
										</select> 
										
									 </div>
									 
									 <div style="margin-bottom: 25px" class="form-group">
										<label for="constellation"><b>Constellation:</b></label>
										<select id="constellation" name="constellation" class="form-control selectpicker" data-live-search="true">
											<option value="">--Select--</option>
											<?php foreach($constellations as $constellation){ ?>
											<option value="<?php echo $constellation->constellation; ?>"><?php echo $constellation->constellation; ?></option>
											<?php } ?>
									   </select>
									 </div>
									 
									 <div style="margin-bottom: 25px" class="form-group">
										<label for="asset_type"><b>Asset Type:</b></label>
									   <select id="asset_type" name="asset_type[]" class="form-control selectpicker" data-live-search="true" multiple >
									   </select>
									 </div>
									 
									 <div style="margin-bottom: 25px" class="form-group">
										<label for="scheme_name"><b>Schemes:</b></label>
										<select id="scheme_name" name="scheme_name[]" class="form-control selectpicker" data-live-search="true" multiple>
									   </select>
									 </div>
								 </div>
								 
                                 <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->
                                    <div class="col-sm-12 controls">
                                       <button type="submit" class="btn btn-success pull-right">Submit</button>
                                    </div>
                                 </div>
								</div>
                              </form>
                           </div>
                        </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
               </div>
            </div>
         </div>
      </div>
	  
	  
   </div>
</div>

<script>
$(document).ready(function(){
	$('#create_basket').on('submit', function(event){
		event.preventDefault();
		//alert(new FormData(this));
		var basket_id = $('#basket_id').val();
		var transaction_type = $('#transaction_type').val();
		var anchoring = $('#anchoring').val();
		var option = $('#option').val();
		var constellation = $('#constellation').val();
		var asset_type = $('#asset_type').val();
		var schemes = $('#scheme_name').val();
		if(transaction_type == ""){
			alert_float("warning", "Please select Transaction Type!");
			return false;
		}
		if(anchoring == ""){
			alert_float("warning", "Please select Anchoring!");
			return false;
		}
		if(option == ""){
			alert_float("warning", "Please select Option!");
			return false;
		}
		if(constellation == ""){
			alert_float("warning", "Please select Constellation!");
			return false;
		}
		if(asset_type == ""){
			alert_float("warning", "Please select Asset Type!");
			return false;
		}
		if(schemes == ""){
			alert_float("warning", "Please select Schemes!");
			return false;
		}
		
		/* var product_code = $("#scheme_name option:selected").map(function() {
						  return $(this).data("code");
						}).get(); */
		var isin = $("#scheme_name option:selected").map(function() {
						  return $(this).data("isin");
						}).get();
		$.ajax({ 
			url: "<?php echo admin_url(); ?>development/create_basket",
			method: "POST",
			data: {basket_id:basket_id, transaction_type:transaction_type, anchoring:anchoring, option:option, constellation:constellation, asset_type:asset_type, schemes:schemes, isin_no:isin },//new FormData(this),
			/* processData:false,
			contentType:false,
			cache:false,
			async:false, */
			success:function(data)
			{
				var obj = JSON.parse(data);
				if(obj.val == 1 || obj.val == 2){
					document.getElementById("create_basket").reset();
					$('.selectpicker').selectpicker('refresh');
					alert_float("success", obj.msg);
					$("#loading-image").show();
					$(".table").hide();
					
					$.ajax({
						type: "post",
						url: "<?php echo admin_url(); ?>development/getBasketData",
						//data: "<?php echo admin_url(); ?>development/getBasketData",
						success: function (data) {
							$('.ajax-data').html(data);
							$("#loading-image").hide();
							$(".table").show();
						}
					});
				}else{
					alert_float("warning", "Basket Not Created!");
				}
				$('#myModal').modal('hide');
			}
		});
	});
	
	$("#loading-image").show();
	$(".table").hide();
	$.ajax({
		url: "<?php echo admin_url(); ?>development/getBasketData",
		method: "POST",
		//data: new FormData(this),
		success:function(data)
		{
			$('.ajax-data').html(data);
			$("#loading-image").hide();
			$(".table").show();
		}
	});
	
	$(".ajax-data").on("click",".delete", function(){
	  var dataid = $(this).data("id");
	  $.ajax({
		url: "<?php echo admin_url(); ?>development/deleteBasket",
		method: "POST",
		data: {"dataid":dataid},
		success:function(data)
		{
			//$('.ajax-data').html(data);
			$("#loading-image").show();
			$(".table").hide();
			alert_float("success", "Basket Deleted Successfully!");
			$.ajax({
                type: "post",
                url: "<?php echo admin_url(); ?>development/getBasketData",
                //data: "<?php echo admin_url(); ?>development/getBasketData",
                success: function (data) {
					$('.ajax-data').html(data);
					$("#loading-image").hide();
					$(".table").show();
                }
			});
		}
	  });
	});
	
	$(".ajax-data").on("click",".edit", function(){
	  var dataid = $(this).data("id");
	  var transaction_type = $("#transaction_type").val();
	  $.ajax({
		url: "<?php echo admin_url(); ?>development/getEditData",
		method: "POST",
		data: {"dataid":dataid},
		success:function(data)
		{
			var obj = JSON.parse(data);
			$("#basket_id").val(obj.id);
			$("#transaction_type").val(obj.transaction_type);
			$("#anchoring").val(obj.anchoring);
			$("#option").val(obj.option);
			$("#constellation").val(obj.constellation);
			
			var schems = obj.schemes;
			var asset_type = obj.asset_type;
			//var product_code = obj.product_code;
			var isin_no = obj.isin_no;
			var schemsarr = schems.split(",");
			//var datacode = product_code.split(",");
			var dataisin = isin_no.split(",");
			var htmldata = "";
			for (var i = 0; i < schemsarr.length; i++) {
			  htmldata += "<option value='"+$.trim(schemsarr[i])+"' data-isin='"+$.trim(dataisin[i])+"' selected=''>"+schemsarr[i]+"</option>";
			}
			var asset_typeHtml = "";
			var asset_typearr = asset_type.split(",");
			
			for (var i = 0; i < asset_typearr.length; i++) {
				asset_typeHtml += "<option value='"+ $.trim(asset_typearr[i])+"' selected=''>"+asset_typearr[i]+"</option>";
			}
			console.log(asset_typeHtml);
			$("#asset_type").html(asset_typeHtml);
			
			$("#scheme_name").html(htmldata);
			$("#transaction_type, #anchoring, #option, #constellation, #asset_type, #scheme_name").selectpicker('refresh');
			$("#myModal").modal("show");
		}
	  });
	});
	
	
	
	$("#constellation").change(function(){
		var transaction_type = $("#transaction_type").val();
		var anchoring = $("#anchoring").val();
		var option = $("#option").val();
		var constellation = $(this).val();
		var consthtml = $("#constellation").html();
		if(transaction_type == ""){
			alert_float("warning", "Please select transaction type!");
			$("#constellation").html();
			$("#constellation").html(consthtml);
			$('#constellation').selectpicker('refresh');
			return false;
		}
		if(anchoring == ""){
			alert_float("warning", "Please select anchoring!");
			$("#constellation").html();
			$("#constellation").html(consthtml);
			$('#constellation').selectpicker('refresh');
			return false;
		}
		if(option == ""){
			alert_float("warning", "Please select option!");
			$("#constellation").html();
			$("#constellation").html(consthtml);
			$('#constellation').selectpicker('refresh');
			return false;
		}
		$.ajax({
			type: "post",
			url: "<?php echo admin_url(); ?>development/getAssetType",
			data: {"transaction_type":transaction_type, "anchoring":anchoring, "option":option, "constellation":constellation },
			success: function (data) {
				var obj = JSON.parse(data);
				if(obj.html == 1){
					var conshtml = $('#constellation').html();
					alert_float("warning", obj.msg);
					$("#asset_type").html("");
					$("#constellation").html(conshtml);
					$('#asset_type, #constellation').selectpicker('refresh');
				}else{
					$('#asset_type').html(obj.html);
					$('#asset_type').selectpicker('refresh');
				}
			}
		});
	});
	$("#asset_type").change(function(){
		var transaction_type = $("#transaction_type").val();
		var anchoring = $("#anchoring").val();
		var option = $("#option").val();
		var constellation = $("#constellation").val();
		var asset_type = $(this).val();
		$.ajax({
			type: "post",
			url: "<?php echo admin_url(); ?>development/getSchemeName",
			data: {"transaction_type":transaction_type, "anchoring":anchoring, "option":option, "constellation":constellation, "asset_type":asset_type },
			success: function (data) {
				$('#scheme_name').html(data);
				$('#scheme_name').selectpicker('refresh');
			}
		});
	});
	
	
});

function refreshForm(){
	document.getElementById("create_basket").reset(); 
	$("#basket_id").val("");
	$('.selectpicker').selectpicker('refresh');
}
</script>
<?php init_tail(); ?>
</body>
</html>

