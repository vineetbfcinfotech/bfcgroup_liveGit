<?php
// Start the session
session_start();
?>
<div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Multiple book Status </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <?php //echo "test";echo "<pre>"; 
                //print_r($next_calling); 
   
                //exit();?>
                <?php 
                // $this->db->order_by("created_on", "desc");


//mayank code comment
                // $this->db->where('lead_id',  $lead_id);
                // $this->db->group_by("remark");
                // $this->db->select('remark');
                // $data = $this->db->get('tblleadremark')->result();
//here
                
                // echo "<span class='assignedleads2_line395'>".substr(current($data)->remark,0,10)."</span>";
                // echo "<span style='display: none;'>";
                // if(	current($data)->remark != ''){
                // $seprationss = ";"; 
                // }else{
                // $seprationss = '';
                // }
                
                // echo substr(current($data)->remark,30).$seprationss;
                // unset($data[0]); // unset 1st element
              ?>
                    
                    
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <?php if (! is_admin())
	                                        {
	                                       ?>
                        <button type="submit" name="submit" id="formSubmit" class="btn btn-info category-save-btn cline215">Submit</button>
                        <button type="submit" name="submit" id="formSubmit_otb" onclick="saveOneBooK('add_book');" class="btn btn-info category-save-btn cline215">Save & Continue</button>
                       <?php  }?>
                    </div>
                </form>
            </div>
        </div>
<script>
    $(document).ready(function(){
        $("#no_of_books_blocks").hide();
        $("#formSubmit_otb").hide();
        //var id = $('#id').val();
        // alert(id);


        //multiple code start here 

        $("#select_multi_book").change(function(){

          var book_name =  $(this).val();
          var lead_id =  $('#hidden_lead_id').val();
          $('#product_catagory').modal('hide');
          $.ajax({
                            url: "<?php echo base_url(); ?>admin/leads/get_lead_multi_b",
                            method: 'POST',
                            data: {
                                lead_id: lead_id,
                                book_name: book_name
                            },
                            success: function(data) {
                              
                            }
                        });


            // alert('test');
            // $("#formSubmit").hide();
        


        });











        //end here  
        $("#add_more_book").click(function(){
            // saveOneBooK();
            // alert('saveOneBooK');
            // $("#add_more_book").hide();
            $("#no_of_books_blocks").show();
            $("#formSubmit_otb").show();

            $("#formSubmit").hide();
            
            // alert('1');
            // $('a.add_book[data-id='+id+']').click();
        });
        $('#formSubmit_otb').click(function(){
          var id = $('#id').val();
            $('a.add_book[data-id='+id+']').click();
        });

    });
              
</script>

        <script type="text/javascript">
            $("#remarkform").submit(function (e) {
            });

            $('#formSubmit').click(function(e){
              var catogary =  $("#lead_status_change").val();
              if (catogary == 39 && ($('#state').val()) =='' ) {
                  alert('Please Enter Author State');
                  return false;
              }
              e.preventDefault();
              var id = $("input[name='id']").val();                
              var name = $("input[name='name']").val();
              var srnumber = $("input[name='srnumber']").val();
              var phonenumber = $("input[name='phonenumber']").val();
              var email = $("input[name='email']").val();
              var designation = $("input[name='designation']").val();
              var company = $("input[name='company']").val();
              var otherphonenumber = $("input[name='otherphonenumber']").val();
              var booktitle = $("input[name='booktitle']").val();
              var publishedEarlier = $("#publishedEarlier").val();
              var address = $("input[name='address']").val();
              var data_source = $("input[name='data_source']").val();
              var calling_objective = $("input[name='calling_objective']").val();
              var description = $("input[name='description']").val();
              var meetingtimefrom = $('#meetingtimefrom').val();
              var meetingtimeto = $('#meetingtimeto').val();
              var added_by = $("input[name='added_by']").val();
              var status = $('select[name="status"]').val();
              var nextcalling = $('#next_calling').val();
              var wplead = $("input[name='wplead']").val();
              var reassignlead = $('select[name="reassignlead"]').val();
              var assigned = $("input[name='assigned']").val();
              var reminder = $('#reminder').val();
              var book_format = $('#book_format').val();
              var bookLanguage = $('#bookLanguage').val();
              var manuscriptStatus = $('#manuscriptStatus').val();
              var package_cost = $('#package_cost').val();
              var booking_amount = $('#booking_amount').val();
              var finstallment = $('#finstallment').val();
              var final_payment = $('#final_payment').val();
              var gst_number = $('#gst_number').val();
              var state = $('#state').val();
              var file_data = $("input[name='receipt']").prop('files')[0];
              var form_data = new FormData();
              form_data.append('file', file_data);
              form_data.append('id', id);
              form_data.append('name', name);
              form_data.append('phonenumber', phonenumber);
              form_data.append('booktitle', booktitle);
              form_data.append('otherphonenumber', otherphonenumber);
              form_data.append('email', email);
              form_data.append('designation', designation);
              form_data.append('company', company);
              form_data.append('address', address);
              form_data.append('data_source', data_source);
              form_data.append('calling_objective', calling_objective);
              form_data.append('description', description);
              form_data.append('meetingtimefrom', meetingtimefrom);
              form_data.append('meetingtimeto', meetingtimeto);
              form_data.append('status', status);
              form_data.append('nextcalling', nextcalling);
              form_data.append('wplead', wplead);
              form_data.append('reassignlead', reassignlead);
              form_data.append('reminder', reminder);
              form_data.append('assigned', assigned);
              form_data.append('added_by', added_by);
              form_data.append('srnumber', srnumber);
              form_data.append('publishedEarlier', publishedEarlier);
              form_data.append('book_format', book_format);
              form_data.append('package_cost', package_cost);
              form_data.append('booking_amount', booking_amount);
              form_data.append('finstallment', finstallment);
              form_data.append('final_payment', final_payment);
              form_data.append('gst_number', gst_number);
              form_data.append('bookLanguage', bookLanguage);
              form_data.append('manuscriptStatus', manuscriptStatus);
              form_data.append('state', state);

              $.ajax({
                url: '<?= base_url();?>admin/leads/update_custom_lead_remark/',
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                error: function () {
                    alert('Something is wrong');
                },
                success: function (data) {
                      $('#product_catagory').modal('hide');
                      if (status == 5 || status == 16 || status == 38) {
                        //#rowData14049 > td:nth-child(3) > a
                        $("#rowData"+id+" > td:nth-child(3) > a").removeAttr("style");
                      }else{
                        //pointer-events: none; color: #9cc6d9;
                        $("#rowData"+id+" > td:nth-child(3) > a").css("pointer-events", "none");;
                      }
                                
                      $.ajax({
                            url: "<?php echo base_url(); ?>admin/leads/get_row_lead_data",
                            method: 'POST',
                            data: {
                                id: id,
                                srnumber:srnumber
                            },
                             success: function (res) {
                                var sta = '';
                                if(status == 5){
                                    sta = 'A';
                                }else if(status == 16 ){
                                    sta = 'B';
                                }else if(status == 38){
                                    sta = 'B+';
                                }else if(status == 30 ){
                                    sta = 'C';
                                }else if(status == 32 ){
                                    sta = 'NP';
                                }else if(status == 39){
                                    sta = 'Acquired';
                                }else if(status == 40){
                                   sta = 'Unattended'; 
                                }else if(status == 41){
                                   sta = 'Scrap'; 
                                }else{                                    
                                }

                                //alert("It is in development phase");
                                //alert(status);


                                /*var currDate = "<?php //echo date("Y-m-d");?>";                       
                                var myDataTable= $('#example33').DataTable();
                                var row = myDataTable.row( '#lead_id_'+id);                                
                                var fullLength =description.length;
                                var remarks = description.slice(0, 10) +"<span class='"+fullLength+"' style='display:none'>"+description.slice(10, fullLength)+"<?php //echo ";".$allleadremarkdata ?></span>";
                              
                                myDataTable.cell(row, 3).data(name).draw();
                                myDataTable.cell(row, 4).data(phonenumber).draw();
                                myDataTable.cell(row, 6).data(manuscriptStatus).draw();
                                myDataTable.cell(row, 7).data(publishedEarlier).draw();
                                myDataTable.cell(row, 8).data(email).draw();
                                myDataTable.cell(row, 9).data(bookLanguage).draw();
                                myDataTable.cell(row, 10).data(currDate).draw();
                                myDataTable.cell(row, 12).data(remarks).draw();
                                myDataTable.cell(row, 11).data(sta).draw();
                                myDataTable.cell(row, 13).data(nextcalling).draw();
                                myDataTable.cell(row, 15).data(book_format).draw();
                                myDataTable.cell(row, 16).data(booktitle).draw();
                                myDataTable.cell(row, 17).data(otherphonenumber).draw(); */

                                /*window.load();
                                alert_float('success', 'Lead Updated Successfully');
                                alert("test");*/
                                location.reload();
                            }
                        });
                        
                    }
                });
            })
              function saveOneBooK(add_book){
                //alert("test");
                 //$_SESSION["no_of_books"] = $("#no_of_books").val();
                var no_of_books = $("#no_of_books").val();
                
                sessionStorage.setItem("no_of_books", no_of_books);
                //localStorage.setItem("no_of_books", no_of_books);
                alert(sessionStorage.getItem("no_of_books"));
               $('#product_catagory').modal('show');
                var catogary = $("#lead_status_change").val();
                if (catogary == 39 && ($('#state').val()) == '') {
                    alert('Please Enter Author State');
                    return false;
                }
                var id = $("input[name='id']").val();
                var name = $("input[name='name']").val();
                var srnumber = $("input[name='srnumber']").val();
                var phonenumber = $("input[name='phonenumber']").val();
                var email = $("input[name='email']").val();
                var designation = $("input[name='designation']").val();
                var company = $("input[name='company']").val();
                var otherphonenumber = $("input[name='otherphonenumber']").val();
                var booktitle = $("input[name='booktitle']").val();
                var publishedEarlier = $("#publishedEarlier").val();
                var address = $("input[name='address']").val();
                var data_source = $("input[name='data_source']").val();
                var calling_objective = $("input[name='calling_objective']").val();
                var description = $("input[name='description']").val();
                var meetingtimefrom = $('#meetingtimefrom').val();
                var meetingtimeto = $('#meetingtimeto').val();
                var added_by = $("input[name='added_by']").val();
                var status = $('select[name="status"]').val();
                var nextcalling = $('#next_calling').val();
                var wplead = $("input[name='wplead']").val();
                var reassignlead = $('select[name="reassignlead"]').val();
                var assigned = $("input[name='assigned']").val();
                var reminder = $('#reminder').val();
                var book_format = $('#book_format').val();
                var bookLanguage = $('#bookLanguage').val();
                var manuscriptStatus = $('#manuscriptStatus').val();
                var package_cost = $('#package_cost').val();
                var booking_amount = $('#booking_amount').val();
                var finstallment = $('#finstallment').val();
                var final_payment = $('#final_payment').val();
                var gst_number = $('#gst_number').val();
                var state = $('#state').val();
                var file_data = $("input[name='receipt']").prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('id', id);
                form_data.append('name', name);
                form_data.append('phonenumber', phonenumber);
                form_data.append('booktitle', booktitle);
                form_data.append('otherphonenumber', otherphonenumber);
                form_data.append('email', email);
                form_data.append('designation', designation);
                form_data.append('company', company);
                form_data.append('address', address);
                form_data.append('data_source', data_source);
                form_data.append('calling_objective', calling_objective);
                form_data.append('description', description);
                form_data.append('meetingtimefrom', meetingtimefrom);
                form_data.append('meetingtimeto', meetingtimeto);
                form_data.append('status', status);
                form_data.append('nextcalling', nextcalling);
                form_data.append('wplead', wplead);
                form_data.append('reassignlead', reassignlead);
                form_data.append('reminder', reminder);
                form_data.append('assigned', assigned);
                form_data.append('added_by', added_by);
                form_data.append('srnumber', srnumber);
                form_data.append('publishedEarlier', publishedEarlier);
                form_data.append('book_format', book_format);
                form_data.append('package_cost', package_cost);
                form_data.append('booking_amount', booking_amount);
                form_data.append('finstallment', finstallment);
                form_data.append('final_payment', final_payment);
                form_data.append('gst_number', gst_number);
                form_data.append('bookLanguage', bookLanguage);
                form_data.append('manuscriptStatus', manuscriptStatus);
                form_data.append('state', state);

                $.ajax({
                    url: '<?= base_url(); ?>admin/leads/update_custom_lead_remark/',
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        $('#product_catagory').modal('hide');
                        if (status == 5 || status == 16 || status == 38) {
                            //#rowData14049 > td:nth-child(3) > a
                            $("#rowData" + id + " > td:nth-child(3) > a").removeAttr("style");
                        } else {
                            //pointer-events: none; color: #9cc6d9;
                            $("#rowData" + id + " > td:nth-child(3) > a").css("pointer-events", "none");;
                        }

                        $.ajax({
                            url: "<?php echo base_url(); ?>admin/leads/get_row_lead_data",
                            method: 'POST',
                            data: {
                                id: id,
                                srnumber: srnumber
                            },
                            success: function(res) {
                                var sta = '';
                                if (status == 5) {
                                    sta = 'A';
                                } else if (status == 16) {
                                    sta = 'B';
                                } else if (status == 38) {
                                    sta = 'B+';
                                } else if (status == 30) {
                                    sta = 'C';
                                } else if (status == 32) {
                                    sta = 'NP';
                                } else if (status == 39) {
                                    sta = 'Acquired';
                                } else if (status == 40) {
                                    sta = 'Unattended';
                                } else if (status == 41) {
                                    sta = 'Scrap';
                                } else {}
                            }
                        });
                    }
                });
                 var id = $('#id').val();
                 alert("Save Successfully, Now you can add new book details here.");
            $('a.add_book[data-id='+id+']').click();
            }
        </script>
        

        <script>
$( "#meetingtimefrom" ).addClass( "datetimepicker" );
$( "#meetingtimeto" ).addClass( "datetimepicker" );
$( "#next_calling" ).addClass( "datetimepicker" );

init_datepicker();

</script>

<script>

function showphone(){
    $(this).hide();
     //$('#otherphonenumber').show();
    $('#otherphonelabel').show();
}
    $(function () {
        //$('#otherphonenumber').hide();
        if($('#otherphonenumber').val() != ''){
            $('#otherphonelabel').show();
        }else{
           $('#otherphonelabel').hide(); 
        }
        
    var status = $('select[name="status"]').val();
    
    switch (status)
    {
        case "1":
            $('#meetingtimefromdiv').show();
            $('#meetingtimetodiv').show();
            $('#wpdiv').show();
            $('#next_callingdiv').hide();
        break;
        
        case "2":
            $('#meetingtimefromdiv').hide();
            $('#meetingtimetodiv').hide();
            $('#wpdiv').hide();
            $('#next_callingdiv').hide();
        break;
        
        default :
        
            $('#meetingtimefromdiv').hide();
            $('#meetingtimetodiv').hide();
            $('#wpdiv').hide();
            $('#next_callingdiv').show();
        
    }
        

        
    });
    </script>



<script>
$(function () {
    $(document).ready(function(){
      
    $("select.statuschangelead").change(function(){
        var stat = $(this).children("option:selected").val();
       // alert(stat);
        switch (stat)
    {
        case "1":
            $('#meetingtimefromdiv').show();
            $('#meetingtimetodiv').show();
            $('#wpdiv').show();
            $('#next_callingdiv').hide();
        break;
        
        case "2":
            $('#meetingtimefromdiv').hide();
            $('#meetingtimetodiv').hide();
            $('#wpdiv').hide();
            $('#next_callingdiv').hide();
        break;
        
        default :
        
            $('#meetingtimefromdiv').hide();
            $('#meetingtimetodiv').hide();
            $('#wpdiv').hide();
            $('#next_callingdiv').show();
        
    }
    });
    $(document).ready(function(){   
    $('#lead_status_change').change(function(){
        //alert(this.value);
        if(this.value == "39"){
            $(".next_callingdivdata").hide();
            $(".remark_data").hide();
            $(".aquired_data").show();
            $("#setReminder").hide();
            
        }else{
            $(".next_callingdivdata").show();
            $(".remark_data").show();
            $(".aquired_data").hide();
            $("#setReminder").show();
        }
    });
});

        $("input#meetingtimefrom").change(function(){
          var meetingtimefrom = $('#meetingtimefrom').val();
          //alert(meetingtimefrom);
		 
          if(meetingtimefrom != '')
          {
           $.ajax({
            url:"<?php echo base_url(); ?>admin/leads/meetingtimewp",
            method:"POST",
            data:{meetingtimefrom:meetingtimefrom},
            success:function(data)
            {
			   $('#wpformeet').html(data);
             
            }
           });
          }
		  else
		  {
		    alert('Select Date & Time');
		  }
        
         });
        
    });
    });
</script>
<script>
    $(function() {
  $('.form-group input[type="checkbox"]').change(function() {
    if ($(this).is(":checked")) {
      $(this).val('1')
      //alert($('#reminder').val());
    } else
      $(this).val('0');
     // alert($('#reminder').val());
  });
});
    $(document).ready(function(){
    var package_cost = $('#package_cost').val();

   var booking_amount =  (package_cost/100)*40;
    booking_amount =  Math.round(booking_amount);
   var first_installment =  (package_cost/100)*40;
    first_installment =  Math.round(first_installment);
    //  perc = ((pEarned/pPos) * 100).toFixed(3);
    // alert(booking_amount);
     var final_payment =  (package_cost/100)*20;
      final_payment =  Math.round(final_payment);
     document.getElementById("booking_amount").value = booking_amount;
     document.getElementById("finstallment").value = first_installment;
     document.getElementById("final_payment").value = final_payment;
 
});
 $(document).ready(function(){
  $("#booking_amount").keyup(function(){
      
      var package_cost = $('#package_cost').val();
    var booking_amount_data = $(this).val();
        var final_payment =  (package_cost/100)*20;
            var booking_amount =  (package_cost/100)*40;
            // alert(booking_amount)
            // alert(booking_amount_data)
        if(booking_amount_data > booking_amount) 
        {
          var cal = (booking_amount_data /package_cost)*100;
          if(cal == 100){
            var get_per = 100 - cal;
             var total_co = (package_cost/100)*get_per;
             document.getElementById("final_payment").value = Math.round(total_co);
          }else if (cal > 80) {
            alert('Please enter amount atleast 80% or 100% of package cost');
            window.reload();
          }else{
            var get_per = 80 - cal;
             var total_co = (package_cost/100)*get_per;
          }
   
        }else{
            var total_c = booking_amount - booking_amount_data;
                var total_co = booking_amount+total_c;
        }
     document.getElementById("finstallment").value = Math.round(total_co);
    
        
  });
});
</script>
 <script>

$(document).ready(function(){
    //alert();
    
  $("#descriptions").attr("autocomplete", "off");
  $("#next_calling").attr("autocomplete", "off");
  $('#phonenumber').css('width','250px');
});
$('#product_catagory').modal({
    backdrop: 'static',
    keyboard: false
})

 </script>
    </div>
</div>
</div>