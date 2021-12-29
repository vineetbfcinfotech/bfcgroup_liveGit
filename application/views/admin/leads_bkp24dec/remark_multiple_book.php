<?php
// Start the session
session_start();
?>
<div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Book Detail</h4>
            <?php // print_r($all_data); ?>
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
                $all_remarks = array();
                foreach($data as $remarkdata){
                // if($remarkdata->remark != ''){
                // $seprations = ";"; 
                // }else{
                // $seprations = '';
                // }
                array_push($all_remarks,$remarkdata->remark);
                
                }
                //echo "</span>";
                //echo "<pre>";
                //print_r($otherphonenumber);
               // $otherphonenumber ='21999999-'.$otherphonenumber;
                $otherphonenumber =$otherphonenumber;
                $allleadremarkdata = implode(';', $all_remarks)
                ?>
                <form action="" id="remarkform" autocomplete="off" onsubmit="event.preventDefault();">
                    <div class="col-md-12">
                        <div class="row">
                        <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                        <input type="hidden" id="hidden_lead_id" value="<?= $lead_id; ?>">
                        <input type="hidden" id="multiple_book_id" value="<?= $all_data->multiple_book_id; ?>">
                       <?php echo render_input('id', '', array('readonly' => 'readonly'), 'hidden'); ?> 
                       
                       <?php echo render_input('srnumber', '', array('readonly' => 'readonly'), 'hidden'); ?>
                        <div class="col-md-4">
                           <?php //echo render_input('name', 'custom_lead_name', array('readonly' => 'readonly')); ?>
                           <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Name</label>
                           <input type="text" id="name" name="name" readonly class="form-control" value="<?php echo $all_data->author_name;?>" autocomplete="no" <?php// if($name !=''){ echo "disabled";}?>></div>
                        </div>
                        
                        <div class="col-md-4">
                        <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <label for="otherphonenumber" class="control-label">Phone</label>
                                 <input type="text" id="phonenumber" name="phonenumber" readonly class="form-control" value="<?php echo $all_data->phonenumber;?>" autocomplete="no">
                            </div>
                          
                        </div>
                         <div class="col-md-4" id="otherphonelabel">
                            <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <label for="otherphonenumber" class="control-label">Other Phone</label>
                                 <input type="text" id="otherphonenumber" name="otherphonenumber" readonly class="form-control" value="<?php echo $all_data->otherphonenumber;?>" autocomplete="no">
                            </div>
                        </div>
                        </div>
                        
                      
                        <div class="row">
                        <div class="col-md-6">
                           <?php //echo render_input('email', 'leads_dt_email', array('readonly' => 'readonly')); ?>
                           <div class="form-group" app-field-wrapper="email">
                                <label for="email" class="control-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control" readonly value="<?php echo $all_data->email; ?>" <?php //if($email !=''){ echo "disabled";}?>>
                           </div>
                        </div>
                        
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="designation">
                                 <label for="booktitle" class="control-label">Book Title</label>
                                 <input type="text" id="booktitle1" name="booktitle" class="form-control" autocomplete="no" value="<?php echo $booktitle;?>">
                            </div>
                        </div>
                        <div class="col-md-6" id="book_formatdiv">
                         
                           <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Book Format</label>
                           <select name="book_format" class="form-control" data-width="100%"  data-live-search="true"  id="book_format1">
                                    <option value=""></option>
                                    <option value="Ebook" <?php if($all_data->book_format == 'Ebook'){ echo "selected";}?>>Ebook</option>
                                    <option value="Paperback" <?php if($all_data->book_format == 'Paperback'){ echo "selected";}?>>Paperback</option>
                            </select> 
                           </div>
                           
                        </div>
                       
                       
                        </div>
                        
                        <div class="row">
                        <div class="col-md-6" id="book_formatdiv1">
                         
                           <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Manuscript Status</label>
                           <select name="manuscriptStatus" class="form-control" data-width="100%"  data-live-search="true"  id="manuscriptStatus1">
                                    <option value=""></option>
                                    <option value="completed" <?php if($all_data->ms_status == 'completed'){ echo "selected";}?>>Completed</option>
                                    <option value="in_process" <?php if($all_data->ms_status == 'in_process'){ echo "selected";}?>>In process</option>
                            </select> 
                           </div>
                           
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="designation">
                                 <label for="booktitle" class="control-label">Book Language</label>
                                  <select name="bookLanguage" class="form-control" data-width="100%"  data-live-search="true"  id="bookLanguage1">
                                    <option value=""></option>
                                    <option value="hindi" <?php if($all_data->book_language == 'hindi'){ echo "selected";}?>>Hindi</option>
                                    <option value="english" <?php if($all_data->book_language == 'english'){ echo "selected";}?>>English</option>
                                    <option value="others" <?php if($all_data->book_language == 'others'){ echo "selected";}?>>Others</option>
                            </select> 
                            </div>
                        </div>
                       
                        </div>
                        
                        <!--<div class="col-md-6">
                           <?php //echo render_input('data_source', 'lead_data_source', array('readonly' => 'readonly')); ?>
                        </div>-->
                        
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="publishedEarlier">
                                 <label for="next_PublishedEarlier" class="control-label">Published Earlier</label>
                                 <select name="publishedEarlier" class="form-control " data-width="100%"  data-live-search="true"  id="publishedEarlier1"
                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                                     <option value=""></option>
                                     <option value="yes" <?php if($all_data->published_earlier == 'yes'){ echo "selected";}?>>Yes</option>
                                     <option value="no" <?php if($all_data->published_earlier == 'no'){ echo "selected";}?>>No</option>
                                   </select>  
                                 <!--<input type="text" id="publishedEarlier" name="next_PublishedEarlier" class="form-control" autocomplete="no" value="<?php echo $publishedEarlier;?>">-->
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <label for="email" class="control-label">Category</label>
                            <select name="status" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="lead_status_change11"
                                                   data-lead-id="<?= $allleadremark[0]->lead_id; ?>">
                                <option value="" selected>Select Category</option>
                                              <?php foreach ($lstatus as $leadst) {
                                                 echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $all_data->category ? 'selected' : '', $leadst->name);
                                              } ?>
                                           </select>
                        </div>
                          <div class="col-md-6" id="next_callingdiv">
                          <!-- <?php // echo render_input('next_calling', 'next_calling', array('readonly' => 'readonly')); ?>-->
                           <div class="form-group" app-field-wrapper="next_calling"><label for="next_calling" class="control-label">Next Calling Date</label>
                           <input type="text" id="next_calling" name="next_calling" class="form-control datetimepicker" value="<?php    if($all_data->next_calling == null ){ echo ""; } elseif($all_data->next_calling == '0000-00-00 00:00:00'){ echo "";  }else { echo $all_data->next_calling; } ?>"></div>
                           
                        </div>
                        <div class="row aquired_data" style="<?php if($all_data->category == "39"){ ?>display: block;<?php }else{ ?> display: none; <?php } ?>">
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="package_cost">
                                     <label for="package_cost" class="control-label">Package cost </label>
                                     <input type="text" id="package_cost1" name="package_cost" class="form-control" value="<?php  if(($all_data->cost_of_additional_copy>0)||($all_data->cost_of_additional_copy !="")){echo $all_data->gross_amt; }else{ echo $all_data->lead_packg_totalamount;} ?>" disabled>
                                  </div>
                               </div>
                                <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="booking_amount">
                                      <label for="booking_amount" class="control-label">Booking amount</label>
                                      <input type="text" id="booking_amount1" name="booking_amount" class="form-control"  value="<?php echo $all_data->lead_booking_amount; ?>" >
                                  </div>
                                </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="finstallment">
                                      <label for="finstallment" class="control-label">First installment</label>
                                      <input type="text" id="finstallment1" name="finstallment" class="form-control" disabled value="<?php echo $all_data->lead_first_installment; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="final_payment">
                                      <label for="final_payment" class="control-label">Final payment</label>
                                      <input type="text" id="final_payment1" name="final_payment" class="form-control" disabled value="<?php echo $all_data->lead_final_payment; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="receipt">
                                      <label for="receipt" class="control-label">Upload Receipt</label>
                                      <input type="file" id="receipt1" name="receipt1" class="form-control" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="gst_number">
                                      <label for="gst_number" class="control-label">GST Number</label>
                                      <input type="text" id="gst_number1" name="gst_number" class="form-control" value="<?php echo $all_data->lead_gst_number; ?>">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="State">
                                      <label for="" class="control-label">State</label>
                                      <input type="text" id="state1" name="state" class="form-control" value="<?php echo $all_data->state_create_p; ?>">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <?php if($all_data->receipt_path){ ?>
                                     <img src="<?= base_url();?><?php echo $all_data->receipt_path; ?>" height = "100px" width="100px" />
                                 <?php } ?>
                               </div>
                            </div>
                        <input type="hidden" name="assigned" id="assigned"  value="<?= $assigned; ?>">
                        
                        </br>
                       <?php
                          if ( !empty($allleadremark) ) {
                             foreach ($allleadremark as $allremark) {
                                
                                $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname  ');
                                
                                $this->db->where('id', $lead_id);
                                $this->db->join('tblstaff', 'tblleads.assigned=tblstaff.staffid');
                               
                                $result = $this->db->get('tblleads')->result();
                                ?>
                                 <div class="col-md-11">
                                     <textarea class="form-control" rows="10" cols="10" readonly><?php echo $allremark->remark; ?></textarea>

                            <p class="pull-right"> On <?php echo date('j M, Y', strtotime($allremark->created_on)) ?> By <?php echo $result[0]->wpname; ?></p>
                                 </div>
                                 
                                 <div class="col-md-1">
                                    
                                 </div>
                             <?php }
                          } ?>
                        <div class="clearfix"></div>
                        <div class="form-group" app-field-wrapper="description">
                            <label for="description" class="control-label">Remark</label>
                            <input type="text" id="descriptions" name="description" class="form-control" value="<?php echo $all_data->remark; ?>">
                        </div>
                        <div class="row">
                          <div class="col-md-4"> 
                            <div class="form-group" id="setReminder" app-field-wrapper="Reminder" style="">
                                <label for="Reminder" class="control-label">Set Reminder</label>
                               
                            </div>
                          </div>
                          <div class="col-md-4">
                           <input type="checkbox" id="reminder" name="reminder" class="form-control" value=""> 
                          </div>
                          <div class="col-md-4"> 
                         
                          </div>  
                          
                        </div>
                        <?php if(($all_data->category == 5 || $all_data->category == 16 || $all_data->category == 38 || $all_data->category == 30) && ($all_data->craete_package != 1)){?>
                          <a href="<?= admin_url()?>leads/multi_create_pack/<?= $all_data->id ?>" class="btn btn-info" target="_blank" >create package</a>
                       <?php }else if($all_data->craete_package == 1){ ?>
                           <a href="<?= admin_url()?>leads/multi_prview_pack/<?= $all_data->id?>"  class="btn btn-info"  target="_blank" class="prviewdata1" >Preview</a>
                       <?php }else if($all_data->craete_package != 1){ ?>
                           <a href="#" style="pointer-events: none; color: #9cc6d9;" class="btn btn-info">create package</a>
                           <?php } ?>
                        <hr>
                       <?php // echo render_input('description', 'custom_lead_remark'); ?>
                      <!-- <div class="row">
                        <div class="col-md-4">
                          <div class="form-group" id="setReminder" app-field-wrapper="Reminder">
                            <a type="button" name="add_more_book" id="add_more_book" class="control-label btn btn-primary">Add More books</a>
                            
                          </div>
                        </div>
                       
                      </div> -->
                    </div>
                    
                    
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <?php if (! is_admin())
	                                        {
	                                       ?>
                        <button type="submit" name="submit" id="formSubmit11" class="btn btn-info category-save-btn cline215">Update</button>
                        <!-- <button type="submit" name="submit" id="formSubmit_otb" onclick="saveOneBooK('add_book');" class="btn btn-info category-save-btn cline215">Save & Continue</button> -->
                       <?php  }?>
                    </div>
                </form>
            </div>
        </div>
<script>
      
</script>

        <script type="text/javascript">
    $(document).ready(function(){   
    $('#lead_status_change11').change(function(){
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
    $('#lead_status_change').change(function() {
        alert(this.value);
    });
    $('.btn-default').click(function() {
    
        $(".modal-backdrop").remove();
    });
    $('.close').click(function() {
    
        $(".modal-backdrop").remove();
    });
            $("#remarkform").submit(function (e) {
                alert('test')
            });

            $('#formSubmit11').click(function(e){
                // alert('test1')
              var catogary =  $("#lead_status_change").val();
              if (catogary == 39 && ($('#state').val()) =='' ) {
                  alert('Please Enter Author State');
                  return false;
              }
              e.preventDefault();
              var id = $("input[name='id']").val();
              var multiple_book_id = $("#multiple_book_id").val();
                    // var booktitle = $("input[name='booktitle']").val();
                    var booktitle = $("#booktitle1").val();
                    var publishedEarlier = $("#publishedEarlier1").val();
                    var description = $("#descriptions").val();
                  
                    // var description = $("input[name='description']").val();
                    // var status = $('select[name="status"]').val();
                    var status = $('#lead_status_change11').val();
                    var book_format = $('#book_format1').val();
                    var bookLanguage = $('#bookLanguage1').val();
                    var manuscriptStatus = $('#manuscriptStatus1').val();
                    var package_cost = $('#package_cost1').val();
                    var booking_amount = $('#booking_amount1').val();
                    var finstallment = $('#finstallment1').val();
                    var final_payment = $('#final_payment1').val();;
                    var gst_number = $('#gst_number1').val();
                    var state = $('#state1').val();
                    var file_data = $("input[name='receipt1']").prop('files')[0];

                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    form_data.append('id', id);
                    form_data.append('multiple_book_id', multiple_book_id);
                    // form_data.append('file', file_data);
                    form_data.append('book_format', book_format);
                    form_data.append('booktitle', booktitle);
                    form_data.append('manuscriptStatus', manuscriptStatus);
                    form_data.append('bookLanguage', bookLanguage);
                    form_data.append('publishedEarlier', publishedEarlier);
                    form_data.append('status', status);
                    form_data.append('package_cost', package_cost);
                    form_data.append('booking_amount', booking_amount);
                    form_data.append('finstallment', finstallment);
                    form_data.append('final_payment', final_payment);
                    form_data.append('description', description);
                    form_data.append('gst_number', gst_number);
                    form_data.append('state', state);
                    console.log(form_data);
                    // alert(json.stringify(form_data));
                    // $('#loading-image').show();
                    var url_data = window.location.href; 
                    // alert(url_data);
               
                    $.ajax({
                        url: '<?= base_url(); ?>admin/leads/update_other_book_details/',
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function (data) {
                            // console.log(data);return false;
                      $('#product_catagory_multi').modal('hide');
                     
                        alert_float('success', 'Book detail Updated Successfully');
                                window.location.href=url_data;
                    }
                });
            })
            
        </script>
        

        <script>
$( "#meetingtimefrom" ).addClass( "datetimepicker" );
$( "#meetingtimeto" ).addClass( "datetimepicker" );
$( "#next_calling" ).addClass( "datetimepicker" );

init_datepicker();

</script>

<script>
    $(document).ready(function(){
      var booking_amount1 =  $("#booking_amount1").val();
      var package_cost = $('#package_cost1').val();
      if ( (booking_amount1 <= 0) || (booking_amount1=="undefined") || (booking_amount1=='') || (booking_amount1==undefined) ) {
        var package_cost = $('#package_cost1').val();

var booking_amount =  (package_cost/100)*40;
 booking_amount =  Math.round(booking_amount);
var first_installment =  (package_cost/100)*40;
 first_installment =  Math.round(first_installment);
 //  perc = ((pEarned/pPos) * 100).toFixed(3);
 // alert(booking_amount);
  var final_payment =  (package_cost/100)*20;
   final_payment =  Math.round(final_payment);
  document.getElementById("booking_amount1").value = booking_amount;
  document.getElementById("finstallment1").value = first_installment;
  document.getElementById("final_payment1").value = final_payment;  
      }
  
 
});
 $(document).ready(function(){
  $("#booking_amount1").keyup(function(){
      
      var package_cost = $('#package_cost1').val();
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
             document.getElementById("final_payment1").value = Math.round(total_co);
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
     document.getElementById("finstallment1").value = Math.round(total_co);
    
        
  });
});
</script>
 <script>

$(document).ready(function(){
   
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