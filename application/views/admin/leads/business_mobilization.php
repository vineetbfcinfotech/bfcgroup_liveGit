<?php init_head(); ?>
<style>
    .hidediv
    {
        display:none;
    }
</style>

<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                                <h3>
								<?php //echo date('Y-m-d', strtotime('last day of last month'));exit; ?>
                                    <button class="btn btn-alert "
                                            onclick="window.location='<?= base_url(); ?>admin/';"> Back
                                    </button> 
                                   <center>Business Mobilization</center> 
                                </h3>
                                
                        </div>
                        
                              <div class="row">  
                              <form data-parsley-validate="" id="signup" name="signup" novalidate="" role="form" enctype="multipart/form-data"
                              action="<?php echo base_url() ?>admin/leads/save_bussiness_mob" method="post"
                              class="form-horizontal form-groups-bordered">
                              <div class=" col-md-4">
                                  <small> (Investor Name)</small>
                        <select name="name" id="investorname" class="form-control  " required>
                            <option value="0">Investor Name</option>
                            <?php
                                            foreach($converted as $row)
                                            {
                                             echo '<option value="'.$row->id.'">'.$row->conname.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class=" col-md-4">
                                  <small> OR (Investor Name)</small>
                        <input name="new_investor" id ="new_investor" class="form-control" placeholder="Investor Name" />
                    </div>
                              <div class=" col-md-4">
                                  <small> (Transaction Date)</small>
                        <input type="text" name="transaction_date" id="transaction_date" class="form-control datepicker" value="" data-format="dd-mm-yyyy" placeholder="Select A Date.." readonly="readonly" style="cursor: pointer;" />
                            </div>
                                        <div class=" col-md-4">
                                  <small> (Product Type)</small>
                        <select name="category" id="category" class="form-control  relation" required>
                            <option value="">Select Category</option>
                            <?php
                                            foreach($category as $row)
                                            {
                                             echo '<option value="'.$row->id.'">'.$row->name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class=" col-md-4 hidediv">
                        <select name="category" id="category" class="form-control  relation" required>
                            <option value="">Select Scheme Category</option>
                            <?php
                                            foreach($category as $row)
                                            {
                                             echo '<option value="'.$row->id.'">'.$row->name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class=" col-md-4 " >
                                  <small> (Company)</small>
                        <select name="company" id="company" class="form-control " required>
                            <option value="">Select Company</option>
                        </select>
                    </div>
                    
                    
                    <div class=" col-md-4">
                                  <small> (Product )</small>
                        <select name="productname" id="productname" class="form-control " required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div  id="creditdiv">
                    
                    </div>
                    
                    
                    
                    </div> 
                    </br>
                    <div class="row">
                    <div class="col-md-4 margin pull-right">
                                    <button type="submit"  class="btn btn-primary btn-block"><?= _l('save') ?></button>
                                </div>
                    </div>
                    </form>
                        
                       
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#investorname').change(function () {

        var select_value = $(this).val();

        if (select_value == '0' || select_value == null) {
            $('#new_investor').prop('disabled', '')
           
        } else {
             $('#new_investor').prop('disabled', 'disabled');
        }
    });
    
    jQuery('#new_investor').on('keyup', function() {
    console.log(jQuery(this).val().length);
    if(jQuery(this).val().length == 0) {
        jQuery('#investorname').prop('disabled', false);
    }else{
        jQuery('#investorname').prop('disabled',true);
    }
});
  });
</script>
<script>
$(document).ready(function(){
 $('#category').change(function(){
  var category = $('#category').val();
  if(category != '')
  {
   $.ajax({
    url:"<?php echo base_url(); ?>admin/leads/fetch_company",
    method:"POST",
    data:{category:category},
    success:function(data)
    {
        if(category == '111'){
     $('.hidediv').html(data);
     init_selectpicker();
        }
        else
        {
            $('#company').html(data);
            init_selectpicker();
        }
     
    }
   });
  }
  else
  {
   $('#state').html('<option value="">Select State</option>');
   $('#city').html('<option value="">Select City</option>');
  }
 });


 $('#company').change(function(){
  var category = $('.relation').val(),
 
      company = $('#company').val();
      // alert(category);
  if(company != '')
  {
   $.ajax({
    url:"<?php echo base_url(); ?>admin/leads/fetch_product",
    method:"POST",
    data:{company:company,category:category},
    success:function(data)
    {
     $('#productname').html(data);
     init_selectpicker();
     
    }
   });
  }
  else
  {
   $('#company').html('<option value="">Select Company</option>');
   $('#productname').html('<option value="">Select Product</option>');
  }
 });
 
 $('#productname').change(function(){
  var productname = $('#productname').val(),
  category = $('#category').val();
       //alert(productname);
  if(productname != '')
  {
   $.ajax({
    url:"<?php echo base_url(); ?>admin/leads/fetch_credit",
    method:"POST",
    data:{productname:productname,category:category},
    success:function(data)
    {
     $('#creditdiv').html(data);
     init_selectpicker();
     
    }
   });
  }
  else
  {
   $('#company').html('<option value="">Select Company</option>');
   $('#productname').html('<option value="">Select Product</option>');
  }
 });
 


});

function getTransactionType(vl)
{
      var transaction_type = vl;
  productname = $("#productname").val(),
  category = $("#category").val();
  trandate = $("#transaction_date").val();
       //alert(productname);
  if(transaction_type != "")
  {
   $.ajax({
    url:"<?php echo base_url(); ?>admin/leads/fetch_transaction_type",
    method:"POST",
    data:{transaction_type:transaction_type,category:category,productname:productname,trandate:trandate},
    success:function(data)
    {
            //alert(data);
     $("#trans_type_change").html(data);
     init_selectpicker();
     
    }
   });
  }
  else
  {
   //alert('bhak');
  }
}




$(function () {
        var d = new Date();
        previousdate = d.setDate(d.getDate() - 5);
        previousdate2 = "<?php echo $prevmonth = date('Y-m-d', strtotime('-1 months')); ?>";
           //previousdate =   $('#allowed_date').val();
		var dateObj = new Date();
		dateObj.setDate(0)
		var month = dateObj.getMonth() + 1;
		var day = dateObj.getDate();
		var year = dateObj.getFullYear();
		//newdate = year + "/" + month + "/" + day;
		newdate = "2021/06/30";
		
		var currentdate = new Date(); 
		var date = currentdate.getDate();
		
		var day2 = new Date(newdate);
		var nextDay = new Date(day2);
		nextDay.setDate(day2.getDate()+1);

        $('#transaction_date').datetimepicker({
                format: 'Y-m-d',
                onShow: function (ct) {
                    this.setOptions({
                        minDate: nextDay,
                        maxDate: new Date()
                    })
                },
                timepicker: false
            });
				
		// if(date => 5){
		// 	$('#transaction_date').datetimepicker({
		// 		format: 'Y-m-d',
		// 		onShow: function (ct) {
		// 			this.setOptions({
		// 				minDate: nextDay,
		// 				maxDate: new Date()
		// 			})
		// 		},
		// 		timepicker: false
		// 	});
		// }else{
		// 	$('#transaction_date').datetimepicker({
  //           format: 'Y-m-d',
  //           onShow: function (ct) {
  //               this.setOptions({
  //                   minDate: previousdate2,
  //                   maxDate: new Date()
  //               })
  //           },
  //           timepicker: false
  //       });
		// }
    });
</script>

<script>
i = 0;
$(document).ready(function(){
  $("input").keypress(function(){
    $("span").text(i += 1);
  });
  $("#transaction_date").attr("autocomplete","off");
});
</script>
<script>
    $().ready(function () {
  // validate signup form on keyup and submit
  $("#signup").validate({
    rules: {
      transaction_date: "required",
      transaction_amount: "required"
    },
    messages: {
      transaction_date: "Please enter Transaction Date"
    },
    submitHandler: function (form) { // for demo
    //  alert('valid form');
      return TRUE;
    }
  });

});

function amountinWords(vl)
{

  $.ajax({
    url:"<?php echo base_url(); ?>admin/leads/convert_amount_in_words",
    method:"POST",
    data:{amtVal:vl},
    success:function(data)
    {
            //alert(data);
       $("#tr_amt_words").html(data);
       $("#transaction_amount_words").val(data);
     
    }
   });
}
</script>
</body>
</html>
