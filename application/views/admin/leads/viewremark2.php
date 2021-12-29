<?php
// Start the session
session_start();
?>
<div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Update Lead Status </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <?php //echo "test";echo "<pre>"; 
                //print_r($next_calling); 
   
                //exit();?>
                <?php 
                // $this->db->order_by("created_on", "desc");
                $this->db->where('lead_id',  $lead_id);
                $this->db->group_by("remark");
                $this->db->select('remark');
                $data = $this->db->get('tblleadremark')->result();
                
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

                <?php //echo $lead_id; 

            $this->db->where_in('id', $lead_id);
            $this->db->select('lead_payment_status');
            $lead_status_data = $this->db->get('tblleads')->result();
          //print_r(expression)
           $lead_status=$lead_status_data['0']->lead_payment_status;

           //echo $lead_status;
           



                ?>
                <form action="" id="remarkform" autocomplete="off" onsubmit="event.preventDefault();">
                    <div class="col-md-12">
                        <div class="row">
                        <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                        <input type="hidden" id="hidden_lead_id" value="<?= $lead_id; ?>">
                       <?php echo render_input('id', '', array('readonly' => 'readonly'), 'hidden'); ?> 
                       
                       <?php echo render_input('srnumber', '', array('readonly' => 'readonly'), 'hidden'); ?>
                        <div class="col-md-4">
                           <?php //echo render_input('name', 'custom_lead_name', array('readonly' => 'readonly')); ?>
                           <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Name </label>
                           <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "readonly"; }?> type="text" id="name" name="name" class="form-control" value="<?php echo $name;?>" autocomplete="no" <?php// if($name !=''){ echo "disabled";}?>></div>
                        </div>
                        
                        <div class="col-md-4">
                           <?php echo render_input('phonenumber', 'leads_dt_phonenumber'); ?>
                        </div>
                         <div class="col-md-4" id="otherphonelabel">
                            <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <label for="otherphonenumber" class="control-label">Other Phone</label>
                                 <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "readonly"; }?> type="text" id="otherphonenumber" name="otherphonenumber" class="form-control" value="<?php echo $otherphonenumber;?>" autocomplete="no">
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
                                <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "readonly"; }?> type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>" <?php //if($email !=''){ echo "disabled";}?>>
                           </div>
                        </div>
                         <div class="col-md-6">
                           <?php echo render_input('calling_objective', 'lead_calling_objective', array('readonly' => 'readonly')); ?>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6" id="book_formatdiv">
                         
                           <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Book Format</label>
                           <select <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> name="book_format" class="form-control" data-width="100%"  data-live-search="true"  id="book_format">
                                    <option value=""></option>
                                    <option value="Ebook" <?php if($book_format == 'Ebook'){ echo "selected";}?>>Ebook</option>
                                    <option value="Paperback" <?php if($book_format == 'Paperback'){ echo "selected";}?>>Paperback</option>
                            </select> 
                           </div>
                           
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="designation">
                                 <label for="booktitle" class="control-label">Book Title</label>
                                 <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "readonly"; }?> type="text" id="booktitle" name="booktitle" class="form-control" autocomplete="no" value="<?php echo $booktitle;?>">
                            </div>
                        </div>
                       
                        </div>
                        
                        <div class="row">
                        <div class="col-md-6" id="book_formatdiv1">
                         
                           <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Manuscript Status</label>
                           <select <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> name="manuscriptStatus" class="form-control" data-width="100%"  data-live-search="true"  id="manuscriptStatus">
                                    <option value=""></option>
                                    <option value="completed" <?php if($manuscript == 'completed'){ echo "selected";}?>>Completed</option>
                                    <option value="in_process" <?php if($manuscript == 'in_process'){ echo "selected";}?>>In process</option>
                            </select> 
                           </div>
                           
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="designation">
                                 <label for="booktitle" class="control-label">Book Language</label>
                                  <select <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> name="bookLanguage" class="form-control" data-width="100%"  data-live-search="true"  id="bookLanguage">
                                    <option value=""></option>
                                    <option value="hindi" <?php if($language == 'hindi'){ echo "selected";}?>>Hindi</option>
                                    <option value="english" <?php if($language == 'english'){ echo "selected";}?>>English</option>
                                    <option value="others" <?php if($language == 'others'){ echo "selected";}?>>Others</option>
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
                                 <select <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> name="publishedEarlier" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="publishedEarlier"
                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                                     <option value=""></option>
                                     <option value="yes" <?php if($publishedEarlier == 'yes'){ echo "selected";}?>>Yes</option>
                                     <option value="no" <?php if($publishedEarlier == 'no'){ echo "selected";}?>>No</option>
                                   </select>  
                                 <!--<input type="text" id="publishedEarlier" name="next_PublishedEarlier" class="form-control" autocomplete="no" value="<?php echo $publishedEarlier;?>">-->
                            </div>
                        </div>
                        
                         
                        <div class="col-md-6">
                            <label for="email" class="control-label">Category</label>
                            <select <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> name="status" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="lead_status_change"
                                                   data-lead-id="<?= $allleadremark[0]->lead_id; ?>">
                                <option value="" selected>Select Category</option>
                                              <?php foreach ($lstatus as $leadst) {
                                                 echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $categoryStatus ? 'selected' : '', $leadst->name);
                                              } ?>
                                           </select>
                        </div>
                          <div class="col-md-6" id="next_callingdiv">
                          <!-- <?php // echo render_input('next_calling', 'next_calling', array('readonly' => 'readonly')); ?>-->
                           <div class="form-group" app-field-wrapper="next_calling"><label for="next_calling" class="control-label">Next Calling Date</label>
                           <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="text" id="next_calling" name="next_calling" class="form-control datetimepicker" value="<?php    if($next_calling == null ){ echo ""; } elseif($next_calling == '0000-00-00 00:00:00'){ echo "";  }else { echo $next_calling; } ?>"></div>
                           
                        </div>
                        <div class="row aquired_data" style="<?php if($categoryStatus == "39"){ ?>display: block;<?php }else{ ?> display: none; <?php } ?>">
                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="package_cost">
                                <label for="select_package" class="control-label">Select Package</label>
                                <select name="select_package" id="package_sel">
                                <option value="">Select Package</option>
                                <option value="<?= $leadst->id; ?>">First Package</option>
                                <?php 
                                if( (isset($compare_id)) && ($compare_id->id !='')) { ?>
                                <option value="<?= $compare_id->id; ?>">Second Package</option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div id="first_package" style="display: none;">
                                   
                        <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="package_cost">
                                     <label for="package_cost" class="control-label">Package cost</label>
                                     <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="text" id="package_cost" name="package_cost" class="form-control" value="<?php if($all_lead_data->cost_of_additional_copy>0){echo $all_lead_data->gross_amt; }else{ echo $all_lead_data->lead_packg_totalamount;} ?>" readonly>
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="booking_amount">
                                      <label for="booking_amount" class="control-label">Booking amount</label>
                                      <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="text" id="booking_amount" name="booking_amount" class="form-control"  value="<?php echo $all_lead_data->lead_booking_amount; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="finstallment">
                                      <label for="finstallment" class="control-label">First installment</label>
                                      <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="text" id="finstallment" name="finstallment" class="form-control" disabled value="<?php echo $all_lead_data->lead_first_installment; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="final_payment">
                                      <label for="final_payment" class="control-label">Final payment</label>
                                      <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="text" id="final_payment" name="final_payment" class="form-control" disabled value="<?php echo $all_lead_data->lead_final_payment; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="receipt">
                                      <label for="receipt" class="control-label">Upload Receipt</label>
                                      <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="file" id="receipt" name="receipt" class="form-control" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="gst_number">
                                      <label for="gst_number" class="control-label">GST Number</label>
                                      <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="text" id="gst_number" name="gst_number" class="form-control" value="<?php echo $all_lead_data->lead_gst_number; ?>">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="State">
                                      <label for="" class="control-label">State</label>
                                      <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="text" id="state" name="state" class="form-control" value="<?php echo $all_lead_data->state_create_p; ?>">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <?php if($all_lead_data->lead_payment_reciept){ ?>
                                     <img src="<?= base_url();?>assets/images/payment_receipt/<?php echo $all_lead_data->lead_payment_reciept; ?>" height = "100px" width="100px" />
                                 <?php } ?>
                               </div>
                        </div> 
                        <?php
                        if( (isset($compare_id)) && ($compare_id->id !='')) { ?>
                        <div id="Second_package" style="display: none;">
                                   
                        <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="package_cost">
                                     <label for="package_cost" class="control-label">Package cost</label>
                                     <input <?php if($compare_id->lead_status ==1 || $compare_id->lead_status==2 || $compare_id->lead_status==3 ){ echo "disabled"; }?> type="text" id="package_cost" name="package_cost" class="form-control" value="<?php if($compare_id->cost_of_additional_copy>0){echo $compare_id->gross_amt; }else{ echo $compare_id->lead_packg_totalamount;} ?>" readonly>
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="booking_amount">
                                      <label for="booking_amount" class="control-label">Booking amount</label>
                                      <input <?php if($compare_id->lead_status ==1 || $compare_id->lead_status==2 || $compare_id->lead_status==3 ){ echo "disabled"; }?> type="text" id="booking_amount" name="booking_amount" class="form-control"  value="<?php echo $compare_id->lead_booking_amount; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="finstallment">
                                      <label for="finstallment" class="control-label">First installment</label>
                                      <input <?php if($compare_id->lead_status ==1 || $compare_id->lead_status==2 || $compare_id->lead_status==3 ){ echo "disabled"; }?> type="text" id="finstallment" name="finstallment" class="form-control" disabled value="<?php echo $compare_id->lead_first_installment; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="final_payment">
                                      <label for="final_payment" class="control-label">Final payment</label>
                                      <input <?php if($compare_id->lead_status ==1 || $compare_id->lead_status==2 || $compare_id->lead_status==3 ){ echo "disabled"; }?> type="text" id="final_payment" name="final_payment" class="form-control" disabled value="<?php echo $compare_id->lead_final_payment; ?>" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="receipt">
                                      <label for="receipt" class="control-label">Upload Receipt</label>
                                      <input <?php if($compare_id->lead_status ==1 || $compare_id->lead_status==2 || $compare_id->lead_status==3 ){ echo "disabled"; }?> type="file" id="receipt" name="receipt" class="form-control" >
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="gst_number">
                                      <label for="gst_number" class="control-label">GST Number</label>
                                      <input <?php if($compare_id->lead_status ==1 || $compare_id->lead_status==2 || $compare_id->lead_status==3 ){ echo "disabled"; }?> type="text" id="gst_number" name="gst_number" class="form-control" value="<?php echo $compare_id->lead_gst_number; ?>">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group" app-field-wrapper="State">
                                      <label for="" class="control-label">State</label>
                                      <input <?php if($compare_id->lead_status ==1 || $compare_id->lead_status==2 || $compare_id->lead_status==3 ){ echo "disabled"; }?> type="text" id="state" name="state" class="form-control" value="<?php echo $compare_id->state_create_p; ?>">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <?php if($compare_id->lead_payment_reciept){ ?>
                                     <img src="<?= base_url();?>assets/images/payment_receipt/<?php echo $compare_id->lead_payment_reciept; ?>" height = "100px" width="100px" />
                                 <?php } ?>
                               </div>
                        </div> 
                        <?php } ?>
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
                            <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="text" id="descriptions" name="description" class="form-control" value="">
                        </div>
                        <div class="row">
                          <div class="col-md-4"> 
                            <div class="form-group" id="setReminder" app-field-wrapper="Reminder" style="">
                                <label for="Reminder" class="control-label">Set Reminder</label>
                               
                            </div>
                          </div>
                          <div class="col-md-4">
                           <input <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="checkbox" id="reminder" name="reminder" class="form-control" value=""> 
                          </div>
                          <div class="col-md-4"> 
                          </div>  
                        </div>
                        <hr>
                       <?php// echo render_input('description', 'custom_lead_remark'); ?>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group" id="setReminder" app-field-wrapper="Reminder">
                            <a type="button" name="add_more_book" id="add_more_book" class="control-label btn btn-primary">Add More books</a>
                            
                          </div>
                        </div>
                        <div class="col-md-4" id="no_of_books_blocks">
                          <div class="form-group" id="setReminder" app-field-wrapper="Reminder">
                         <label for="email" class="control-label">No. of Books</label>
                           <select name="no_of_books" class="form-control statuschangelead" data-width="100%" data-live-search="true" id="no_of_books">
                              <option value="" selected>Select No. of Books</option>
                              <?php for ($i=1; $i <=10 ; $i++) { ?>
                                <option value="<?php echo $i;?>"> <?php echo $i;?> </option>
                              <?php }?>
                            </select>
                           <!--  <input type="text" name="no_of_books" id="no_of_books" class="form-control" hidden>  -->
                          </div>
                        </div>
                        <div class="col-md-4">
                            <?php if ($all_book) { ?>
                                <label for="email" class="control-label">Select Books</label>
                           <select name="no_of_books" class="form-control " data-width="100%" data-live-search="true" id="select_multi_book">
                              <option value="" selected>Select Books</option>
                              <?php foreach($all_book as $value){ ?>
                                <option value="<?= $value->book_title?>"><?= $value->book_title?></option>
                              <?php } ?>
                             
                            </select>
                            <?php }else{} ?>
                      
                        </div>
                      </div>
                    </div>
                    
                    
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <?php if (! is_admin())
	                                        {
	                                       ?>
                                        
                        <button <?php if($lead_status ==1 || $lead_status==2 || $lead_status==3 ){ echo "disabled"; }?> type="submit" name="submit" id="formSubmit" class="btn btn-info category-save-btn cline215">Submit</button>
                      
                        <button type="submit" name="submit" id="formSubmit_otb" onclick="saveOneBooK('add_book');" class="btn btn-info category-save-btn cline215">Save & Continue</button>
                       <?php  }?>
                    </div>
                </form>
            </div>
        </div>
<script>
    $(document).ready(function(){
        $("#package_sel").change(function(){
            package = $(this).val();
            alert(package);
        });
        $("#no_of_books_blocks").hide();
        $("#formSubmit_otb").hide();
        //var id = $('#id').val();
        // alert(id);


        //multiple code start here 

        $("#select_multi_book").change(function(){

          var book_name =  $(this).val();
          var lead_id =  $('#hidden_lead_id').val();
          
          $.ajax({
                            url: "<?php echo base_url(); ?>admin/leads/get_lead_multi_b",
                            method: 'POST',
                            data: {
                                lead_id: lead_id,
                                book_name: book_name
                            },
                            success: function(data) {
                                console.log(data)
                                $('#product_catagory').modal('hide');
                                $("#product_catagory_multi").html(data);
                                $('#product_catagory_multi').modal('show');
                              
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
              var books = '';
                    if ($("#no_of_books").val() != '') {
                        books = $("#no_of_books").val();
                    }
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
              form_data.append('books', books);

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
                 var books = '';
                    if ($("#no_of_books").val() != '') {
                        books = $("#no_of_books").val();
                    }
               
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
                form_data.append('books', books);

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
    var booking_amount1 =  $("#booking_amount").val();
      if ( (booking_amount1 <= 0) || (booking_amount1=="undefined") || (booking_amount1=='') || (booking_amount1==undefined) ) {

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
      }
 
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