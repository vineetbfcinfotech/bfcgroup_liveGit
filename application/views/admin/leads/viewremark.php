<div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Update Lead Status </h4>
            
        </div>
        <div class="modal-body">
            <div class="row">
                <?php //echo "test";echo "<pre>"; print_r($book_title);exit();?>
                <form action="" id="remarkform" autocomplete="off" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="row">
                        <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                       <?php echo render_input('id', '', array('readonly' => 'readonly'), 'hidden'); ?> 
                       
                       <?php echo render_input('srnumber', '', array('readonly' => 'readonly'), 'hidden'); ?>
                        <div class="col-md-4">
                           <?php //echo render_input('name', 'custom_lead_name', array('readonly' => 'readonly')); ?>
                           <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Name</label>
                           <input type="text" id="name" name="name" class="form-control" value="<?php echo $name;?>" autocomplete="no" readonly></div>
                        </div>
                        
                        <div class="col-md-4">
                           <?php echo render_input('phonenumber', 'leads_dt_phonenumber'); ?>
                        </div>
                         <div class="col-md-4" id="otherphonelabel">
                            <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <label for="otherphonenumber" class="control-label">Other Phone</label>
                                 <input type="text" id="otherphonenumber" name="otherphonenumber" class="form-control" value="<?php echo $otherphonenumber;?>" autocomplete="no">
                            </div>
                        </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                <a href="#" onclick="showphone();" class="btn btn-primary">Add New</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                           <?php //echo render_input('email', 'leads_dt_email', array('readonly' => 'readonly')); ?>
                           <div class="form-group" app-field-wrapper="email">
                                <label for="email" class="control-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>" <?php if($email !=''){ echo "disabled";}?>>
                           </div>
                        </div>
                         <div class="col-md-6">
                           <?php echo render_input('calling_objective', 'lead_calling_objective', array('readonly' => 'readonly')); ?>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6" id="book_formatdiv">
                         
                           <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Book Format</label>
                           <select name="book_format" class="form-control" data-width="100%"  data-live-search="true"  id="book_format">
                                     <option value="Ebook" <?php if($book_format == 'Ebook'){ echo "selected";}?>>Ebook</option>
                                     <option value="Paperback" <?php if($book_format == 'Paperback'){ echo "selected";}?>>Paperback</option>
                                   </select> 
                           </div>
                           
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="designation">
                                 <label for="booktitle" class="control-label">Book Title</label>
                                 <input type="text" id="booktitle" name="booktitle" class="form-control" autocomplete="no" value="<?php echo $booktitle;?>">
                            </div>
                        </div>
                       
                        </div>
                        
                        <!--<div class="col-md-6">
                           <?php //echo render_input('data_source', 'lead_data_source', array('readonly' => 'readonly')); ?>
                        </div>-->
                        
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="publishedEarlier">
                                 <label for="next_PublishedEarlier" class="control-label">Published Earlier</label>
                                 <select name="publishedEarlier" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="publishedEarlier" data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php if($publishedEarlier != ''){ echo "disabled"; }?>>
                                     <option value="yes" <?php if($publishedEarlier == 'yes'){ echo "selected";}?>>Yes</option>
                                     <option value="no" <?php if($publishedEarlier == 'no'){ echo "selected";}?>>No</option>
                                   </select>  
                                 <!--<input type="text" id="publishedEarlier" name="next_PublishedEarlier" class="form-control" autocomplete="no" value="<?php echo $publishedEarlier;?>">-->
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <label for="email" class="control-label">Status</label>

                            <select onchange="origin()" name="status" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="lead_status_change"
                                                   data-lead-id="<?= $allleadremark[0]->lead_id; ?>">
                                <option value="" selected>Select status</option>
                                              <?php foreach ($lstatus as $leadst) {
                                                 echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $status ? 'selected' : '', $leadst->name);
                                              } ?>
                                           </select>
                        </div>
                        <input type="hidden" name="assigned" id="assigned"  value="<?= $assigned; ?>">
                        
                       
                        <div class="col-md-6" id="wpdiv">
                            <label for="rmlists" class="control-label">WP List</label>
                                              
                                              
                                              
                                               
                                                
                                                
                                                
                                                <?php
                                                $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname , tblmeeting_scheduled.assigned as assignedwp ');
                                                $this->db->where('lead_id', $lead_id);
                                                $this->db->join('tblstaff', 'tblmeeting_scheduled.assigned=tblstaff.staffid');
                                                $query = $this->db->get('tblmeeting_scheduled');
                                                $q2 = $query->result();
                                                
                                                

                                                $aswp = $query->num_rows();
                                                if ($aswp > 0) {
                                                    $asgndwp = $q2['0']->wpname;
                                                } else {
                                                    $asgndwp = "0";
                                                }

                                                ?>
                                               
                                                <?= $q2['0']->wpname; ?>
                                        
                                               <select name="reassignlead" id="wpformeet" data-none-selected-text="choose WP"
                                                        data-live-search="true" class="form-control rmList"
                                                        data-lead_id="<?= $lead_id; ?>">

                                                    <?php if ($asgndwp != "0") { ?>
                                                          <option selected value="<?= $q2['0']->assignedwp ?>"> <?= $q2['0']->wpname; ?></option>
                                                        
                                                    <?php } 
                                                    
                                                    
                                                    ?>
                                                </select>
                                            
                                              
                                              
                                              </div>
                                          
                        
                        
                        
                        <div class="col-md-6" id="meetingtimefromdiv">
                           <?php echo render_input('meetingtimefrom', 'meetingtimefrom', array('readonly' => 'readonly')); ?>
                        </div>
                        
                        <div class="col-md-6" id="meetingtimetodiv">
                           <?php echo render_input('meetingtimeto', 'meetingtimeto', array('readonly' => 'readonly')); ?>
                        </div>
                        
                        <div class="row">
                        
                        <div class="col-md-6 " id="next_callingdiv"  >
                          <!-- <?php // echo render_input('next_calling', 'next_calling', array('readonly' => 'readonly')); ?>-->
                           <div class="form-group next_callingdivdata" app-field-wrapper="next_calling" style="<?php if($status == "39"){ ?> display:none; <?php } ?>" ><label for="next_calling" class="control-label">Next Calling Date</label>
                           <input type="text" id="next_calling" name="next_calling" class="form-control datetimepicker" value="<?php if($next_calling == null ){ echo ""; } elseif($next_calling == '0000-00-00 00:00:00'){ echo "";  }else { echo $next_calling; } ?>"></div>
                           
                        </div>
                        </div>
                        
                        
                        <div class="row aquired_data" style="<?php if($status == "39"){ ?>display: block;<?php }else{ ?> display: none; <?php } ?>">
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="package_cost">
                                     <label for="package_cost" class="control-label">Package cost</label>
                                     <input type="text" id="package_cost" name="package_cost" class="form-control" value="<?php echo $aquired_data->lead_packg_totalamount; ?>" disabled>
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="booking_amount">
                                      <label for="booking_amount" class="control-label">Booking amount</label>
                                      <input type="text" id="booking_amount" name="booking_amount" class="form-control"  value="<?php echo $aquired_data->booking_amount; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="finstallment">
                                      <label for="finstallment" class="control-label">First installment</label>
                                      <input type="text" id="finstallment" name="finstallment" class="form-control" disabled value="<?php echo $aquired_data->first_installment; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="final_payment">
                                      <label for="final_payment" class="control-label">Final payment</label>
                                      <input type="text" id="final_payment" name="final_payment" class="form-control" disabled value="<?php echo $aquired_data->final_payment; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="receipt">
                                      <label for="receipt" class="control-label">Upload Receipt</label>
                                      <input type="file" id="receipt" name="receipt" class="form-control" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="gst_number">
                                      <label for="gst_number" class="control-label">GST Number</label>
                                      <input type="text" id="gst_number" name="gst_number" class="form-control" value="<?php echo $aquired_data->lead_gst_number; ?>">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <?php if($aquired_data->lead_payment_reciept){ ?>
                                     <img src="<?= base_url();?>assets/images/payment_receipt/<?php echo $aquired_data->lead_payment_reciept; ?>" height = "100px" width="100px" />
                                 <?php } ?>
                               </div>
                            </div>
                            
                            
                            
                       
                        </br>
                       <?php
                          if ( !empty($allleadremark) ) {
                             foreach ($allleadremark as $allremark) {
                                
                                $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname  ');
                                
                                                $this->db->where('id', $lead_id);
                                                $this->db->join('tblstaff', 'tblleads.assigned=tblstaff.staffid');
                                               
                                                $result = $this->db->get('tblleads')->result();
                                ?>
                                
                                 <div class="col-md-11 remark_data" style="<?php if($status == "39"){ ?> display:none; <?php } ?>" >
                                     <textarea class="form-control" rows="4" cols="10" readonly><?php echo $allremark->remark; ?></textarea>

<p class="pull-right"> On <?php echo date('j M, Y', strtotime($allremark->created_on)) ?> By <?php echo $result[0]->wpname; ?></p>
                                 </div>
                                 
                                 <div class="col-md-1 remark_data" style="<?php if($status == "39"){ ?> display:none; <?php } ?>" >
                                     <a href="http://bfcpublications.com/chorus/admin/leads/deleteremarkid/<?= $allremark->id; ?>"
                                        class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                 </div>
                             <?php }
                       }?>
                        <div class="form-group remark_data" app-field-wrapper="description" style="<?php if($status == "39"){ ?> display:none; <?php } ?>" >
                        <div class="clearfix"></div>
                            <label for="description" class="control-label">Remark</label>
                            <input type="text" id="descriptions" name="description" class="form-control" value="">
                        </div>
                        <!--<div class="form-group" app-field-wrapper="Reminder"><label for="Reminder" class="control-label">Set Reminder</label><input type="checkbox" id="reminder" name="reminder" class="form-control" value=""></div>-->
                       <!--<?php echo render_input('description', 'custom_lead_remark'); ?>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <?php if (! is_admin())
                                            {
                                           ?>
                        <button type="submit" name="submit" class="btn btn-info category-save-btn">Submit</button>
                        
                       <?php  }?>
                    </div>
                </form>
            </div>
        </div>


<script type="text/javascript">
    $("form#remarkform").submit(function (e) {
        // alert('Form ');
        e.preventDefault();
        
        //var remarkform = $("form").serialize();
        //console.log(remarkform);
        //var formData = new FormData(this);
         //var form = $("#remarkform");
        //  console.log(form_data);return false;
        
        
        
        
        
        
        
        
        
        
        var id = $("input[name='id']").val();
        var name = $("input[name='name']").val();
        var srnumber = $("input[name='srnumber']").val();
        //alert(name+"--"+srnumber);
             
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
        
        var package_cost = $('#package_cost').val();
        var booking_amount = $('#booking_amount').val();
        var finstallment = $('#finstallment').val();
        var final_payment = $('#final_payment').val();
        var gst_number = $('#gst_number').val();
        
        //alert(gst_number);return false;
        
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
            
            //console.log(package_cost);
            
     //alert(publishedEarlier);


        $.ajax({
            url: '<?= base_url();?>admin/leads/update_custom_lead_remark_update/',
            
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            //dataType: "json",
            /* {
                id: id,
                name: name,
                phonenumber: phonenumber,
                booktitle:booktitle,
                otherphonenumber:otherphonenumber,
                email: email,
                designation: designation,
                company: company,
                address: address,
                data_source: data_source,
                calling_objective: calling_objective,
                description: description,
                meetingtimefrom: meetingtimefrom,
                meetingtimeto: meetingtimeto,
                status: status,
                nextcalling: nextcalling,
                wplead: wplead,
                reassignlead: reassignlead,
                reminder:reminder,
                assigned:assigned,
                added_by: added_by,
                srnumber: srnumber,
                publishedEarlier: publishedEarlier,
                book_format: book_format,
                package_cost: package_cost,
                booking_amount: booking_amount,
                finstallment: finstallment,
                final_payment: final_payment,
                file_data: file_data
            } */
            error: function () {
                alert('Something is wrong');
            },
            success: function (data) {
               // console.log(data);return false;
                $('#product_catagory').modal('hide');
               location.reload();
               
               // $.ajax({
               //      url: "<?php echo base_url(); ?>admin/leads/get_row_lead_data",
               //      method: 'POST',
               //      data: {
               //          id: id,
               //          srnumber:srnumber
               //      },
               //       success: function (res) {
               //          $('#lead_id_'+id).replaceWith(res);
               //          alert_float('success', 'Lead Updated Successfully');
                 
               //      }
               //  });
                
            }
        });
    });
$(document).ready(function(){   
    $('#lead_status_change').change(function(){
        //alert(this.value);
        if(this.value == "39"){
            $(".next_callingdivdata").hide();
            $(".remark_data").hide();
            $(".aquired_data").show();
        }else{
            $(".next_callingdivdata").show();
            $(".remark_data").show();
            $(".aquired_data").hide();
        }
    });
});
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
</script>

 <script>

$(document).ready(function(){
  $("#descriptions").attr("autocomplete", "off");
  $("#next_calling").attr("autocomplete", "off");
  $('#phonenumber').css('width','250px');
});
 </script>
 <script>
 $(document).ready(function(){
    var package_cost = $('#package_cost').val();

   var booking_amount =  (package_cost/100)*40;
    booking_amount =  booking_amount.toFixed(2)
   var first_installment =  (package_cost/100)*40;
    first_installment =  first_installment.toFixed(2)
    //  perc = ((pEarned/pPos) * 100).toFixed(3);
    // alert(booking_amount);
     var final_payment =  (package_cost/100)*20;
      final_payment =  final_payment.toFixed(2)
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
        if(booking_amount_data > booking_amount) 
        {
            var total_c =booking_amount_data - booking_amount;
            var total_co = booking_amount+total_c;
            
        }else{
            var total_c = booking_amount - booking_amount_data;
                var total_co = booking_amount+total_c;
        }
     document.getElementById("finstallment").value = total_co;
    
        
  });
});
</script>
    </div>
</div>
</div>