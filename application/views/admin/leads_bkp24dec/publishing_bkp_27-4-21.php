<?php init_head(); ?>
<style>
   .bootstrap-select{
   width: 100% !important;
   }      
   .bootstrap-select .dropdown-toggle .filter-option-inner-inner{
   text-transform: none !important;
   }
   .dynamicservice ul{
   margin-left: 20px !important;
   }
   .rowpackage{
   margin-bottom: 10px;
   }
   #servicesd ul ul{
   margin-left: 15px;
   }
</style>
<?php init_clockinout(); //print_r($packages);exit; ?>
<div id="wrapper">
<div class="content">
   <div class="row">
      <div class="col-md-12">
         <div class="panel_s">
            <div class="panel-body">
               <div class="_buttons">
                  <!-- <a href="#"  class="btn mright5 btn-info pull-left display-block">
                     Deal Acquired
                     
                     </a> -->
                  <h2 class="text-center">Package Details</h2>
               </div>
               <div class="clearfix"></div>
               <hr class="hr-panel-heading" />
               <form class="create_package_form" id="create_package_form" method="post" name="create_package_form" action="<?php echo admin_url('Leads/saveLeads'); ?>">
                  <?php //print_r($leadData);exit; ?>
                  <input type="hidden" value="" class="inputcountvalue">
                  <input type="hidden" name="author_id" id="author_id" value="<?php echo $leadData->id; ?>">
                 
                  <div class="pdf_attachment" style="display:none; ">
                     <b>Click here to
                     <a href="<?php echo base_url("assets/authorMail/").$packageData->pdf_data; ?>" target="_blank" style="">
                     download 
                     </a>
                     PDF Attachment</b>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group" app-field-wrapper="author_name">
                           <label for="author_name" class="control-label">Author Name</label>
                           <input type="text" id="author_name" name="author_name" class="form-control" readonly  value="<?= $leadData->lead_author_name ?>">
                           <input type="hidden" id="assigned_by_id" name="assigned_by_id" class="form-control"  value="<?= $leadData->assigned; ?>">
                           <input type="hidden" id="create_lead" name="craete_package" class="form-control"  value="<?= $leadData->craete_package; ?>">
                        </div>
                        <div class="form-group" app-field-wrapper="mobile"><label for="mobile" class="control-label">Mobile</label>
                           <input type="text" id="mobile" name="mobile" class="form-control" readonly value="<?= $leadData->phonenumber ?>">
                        </div>
                        <div class="form-group" app-field-wrapper="email">
                           <label for="email" class="control-label">Email</label>
                           <input type="text" id="email" name="email" class="form-control"  value="<?= $leadData->email ?>" readonly>
                        </div>
                        <div class="form-group">
                           <label for="msstatus" class="control-label">Manuscript Status</label>
                           <select id="msstatus" name="msstatus" class="form-control" tabindex="-98">
                              <option selected="" value="">Select Manuscript Status</option>
                              <option value="inprocess" <?php if($leadData->lead_author_msstatus == 'inprocess'){echo "selected";} ?> >In-process</option>
                              <option value="completed" <?php if($leadData->lead_author_msstatus == 'completed'){echo "selected";} ?>>Completed</option>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="gender" class="control-label">Package Details</label>
                           <select id="package_details" name="package_details" class="form-control" tabindex="-98">
                              <option selected="" value="">Select Package Details</option>
                              <option value="1" <?php if($leadData->lead_package_detail == 1){echo "selected";} ?> >Standard</option>
                              <option value="2" <?php if($leadData->lead_package_detail == 2){echo "selected";} ?>>Customized</option>
                              <option value="3" <?php if($leadData->lead_package_detail == 3){echo "selected";} ?>>Standard Customized</option>
                           </select>
                        </div>

                        <div class="form-group book_type">

                           <input type="text" value="" name = "book_type_value" id="book_type_value">  
                            <label for="book_type" class="control-label ">Book Type</label>
                           <select id="book_type" name="book_type" class="form-control book_type_standard_and_custum" tabindex="-98">
                              <option  value="">Select Book Type</option>
                              <option value="ebook" <?php if($leadData->lead_book_type == 'ebook'){echo "selected";} ?>>eBook</option>
                              <option value="paperback" <?php if($leadData->lead_book_type == 'paperback'){echo "selected";} ?> >Paperback</option>
                           </select>
                        </div>

                         <div class="form-group book_type_standard_custum" style="display: none;">
                           <label for="book_type" class="control-label">Book Type</label>
                           <select id="book_type_sc" name="book_type" class="form-control " tabindex="-98">
                              <option selected="" value="">Select Book Type</option>
                              <option value="ebook" <?php if($leadData->lead_book_type == 'ebook'){echo "selected";} ?>>eBook</option>
                              <option value="paperback" <?php if($leadData->lead_book_type == 'paperback'){echo "selected";} ?> >Paperback</option>
                           </select>
                        </div>
<input type="text" id="package_value_by_id" name="package_name_data" value="">
                        <div class="package_data" style="<?php if(isset($leadData->lead_package_detail)){ ?>display:block; <?php }else{ ?>display:none;<?php } ?>">
                           <div class="form-group">
                              <label for="package" class="control-label">Package Name</label>
							  
                              <select  id="package" name="package_name" class="form-control package_ddl" tabindex="-98" style="<?php if(isset($leadData->lead_package_detail) && $leadData->lead_package_detail == 1){ ?>display:block; <?php }else{ ?>display:none;<?php } ?>">
                                 <option selected="" value="">Select Package</option>
                                 <option value="essential" <?php if($leadData->lead_package_name == 'essential'){echo "selected";} ?> >ESSENTIAL</option>
                                 <option value="regular" <?php if($leadData->lead_package_name == 'regular'){echo "selected";} ?> >REGULAR</option>
                                 <option value="superior" <?php if($leadData->lead_package_name == 'superior'){echo "selected";} ?> >SUPERIOR</option>
                                 <option value="premium" <?php if($leadData->lead_package_name == 'premium'){echo "selected";} ?> >PREMIUM</option>
                              </select>
                              <input type="text" id="package_name" name="package_name" class="form-control package_name"  value="<?php echo $leadData->lead_package_name; ?>" readonly style="<?php if(isset($leadData->lead_package_detail) && $leadData->lead_package_detail == 2){ ?>display:block; <?php }else{ ?>display:none;<?php } ?>">
                           </div>
                        </div>

                        <div class="package_data_for_s_c">
                           <div class="form-group">
                              <label for="package" class="control-label">Package Name</label>
                       
                              <select  id="package_value_sc" name="package_name_data_value" class="form-control package_data_sc" tabindex="-98" style="">
                                 <option selected="" value="">Select Package</option>
                                 <option value="essential" <?php if($leadData->lead_package_name == 'essential'){echo "selected";} ?> >CUSTOM ESSENTIAL</option>
                                 <option value="regular" <?php if($leadData->lead_package_name == 'regular'){echo "selected";} ?> >CUSTOM REGULAR</option>
                                 <option value="superior" <?php if($leadData->lead_package_name == 'superior'){echo "selected";} ?> >CUSTOM SUPERIOR</option>
                                 <option value="premium" <?php if($leadData->lead_package_name == 'premium'){echo "selected";} ?> >CUSTOM PREMIUM</option>
                              </select>
                             
                           </div>
                        </div>


                        <div id="testing">
                           <?php if($leadData->lead_package_detail){ 
						  	 if($leadData->lead_package_detail == 2){ ?>
                           <div class="row customize_services " style="<?php if(isset($leadData->lead_package_detail) && $leadData->lead_package_detail == 2){ ?>display:block; <?php }else{ ?>display:none;<?php } ?>">
                              <?php 
                                 $service = explode(", ", $leadData->lead_service);
                                 
                                 $result = $this->leads_model->getserviesedit($service);
								 //print_r($result);exit;
							
                                             $i =1;
                                 foreach($result as $data){	?>
								<div class="row-add<?= $i; ?>" data-id="<?= $i; ?>">
                              <div class="all-services servicess_data_<?= $i; ?>">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <label for="services" class="control-label">Services:</label>
                                       <select class="form-control services" id="services<?= $i; ?>" class="services" name="services[]" style="width: 100%; <?php if($leadData->lead_package_detail==1){ ?>display:none; <?php }else{ ?>display:block;<?php } ?>">
                                          <option value="">--Select--</option>
                                          <?php $this->db->from('tblpackagesubservices a');
                                             $this->db->join('tblpackageservices b', 'b.id = a.serviceid'); 
                                             $this->db->where('a.book_type', $leadData->lead_book_type); 
                                             $this->db->where('a.packageid', 2); 
                                             $this->db->group_by('a.serviceid'); 
                                             $query = $this->db->get();
                                                   
                                             foreach ($query->result() as $services_data) {  ?>   
                                          <option value="<?= $services_data->id; ?>" <?php if($data->id==$services_data->id){ echo "selected";} ?>   ><?php echo $services_data->service_name; ?></option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="sub_services_data_<?= $i; ?> sub_services<?= $i; ?>   subservices">
                                 <?php   $sub_services = explode(", ", $leadData->lead_sub_service);
                                    //print_r($sub_services);
                                    $subservices = $this->leads_model->sub_servicesedit($data->id, $sub_services, $leadData->lead_book_type); 
                                    foreach($subservices as $subservice){ ?>
                                 <div class="col-md-5">
                                    <label for="sub_service" class="control-label">Sub Services:</label>
                                 </div>
                                 <div class="col-md-4">
                                    <label for="cost" class="control-label">Cost:</label>
                                 </div>
                                 <div class="col-md-3" style="min-height: 30px;">
                                 </div>
                                 <div class="main-data">
                                    <div class="col-md-5">
                                       <div class="form-group sub_service_data">
                                          <label class="checkbox-inline">
                                          <input type="checkbox" class="subservice_check" name="sub_services[]" data-cost="<?= $subservice->cost; ?>" data-page-cost="<?= $subservice->cost; ?>" data-id="<?= $subservice->id; ?>" value="<?= $subservice->id; ?>" data-name="<?= $subservice->subservice_name; ?>" checked="" onclick="return false;">
                                          <?= $subservice->subservice_name; ?>
                                          </label>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                          <div class="dropdown bootstrap-select form-control">
                                             <input type="text" id="cost[]" name="cost" class="form-control page_cost_data" readonly="" value="<?= $subservice->cost; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-3" style="min-height: 70px;">
                                    </div>
                                 </div>
                                 <?php } ?>
                              </div>
						
							   <div style="cursor:pointer;background-color:red;float:right;margin-right: 15px;" class="remove_fieldss btn btn-info">Remove</div>
						
							  </div>
							   
								 <?php $i++; }  ?>
                           </div>
							 <?php }
							if($leadData->lead_package_detail != 2){ ?>
						       <div class="row customize_services remove_attr_from" style="<?php if($leadData->lead_package_detail == 2){ ?>display:none; <?php }else{ ?>display:block;<?php } ?>">
							
                              <div class="all-services">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <label for="services" class="control-label">Services:</label>
                                       <select class="form-control services" id="" name="services[]" style="width: 100%; <?php if($leadData->lead_package_detail==1){ ?>display:none; <?php }else{ ?>display:block;<?php } ?>">
                                          <option value="">--Select--</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="sub_services">
                              </div>
                           </div>
							<?php } }else{ ?>
                           <div class="row customize_services 1" style="">
                              <div class="all-services">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <label for="services" class="control-label">Services:</label>
                                       <select class="form-control services" id="" name="services[]" style="width: 100%; <?php if($leadData->lead_package_detail==1){ ?>display:none; <?php }else{ ?>display:block;<?php } ?>">
                                          <option value="">--Select--</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="sub_services">
                              </div>
                           </div>
                           <?php }?>
						  
                        </div>
                        <div id="getservicewithcost"  >
                        </div>
                        <br>
                        <div class="col-md-12 addmore " style="<?php if(isset($leadData->lead_package_detail) && $leadData->lead_package_detail == 1){ ?>display:none; <?php }else{ ?>display:block;<?php } ?>">
                           <div class="form-group text-right" app-field-wrapper="add_more_roles">
                              <label for="add_more_team_role" class="control-label addmore666"> Add More</label>
                              <button style="margin-left: 4px;" type="button" class="btn btn-info add_field_button" id="add_more_team_role666" >
                              <i class="fa fa-plus plus666"></i><i class="fa fa-minus minus666" style="display:none;"></i></button>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group" app-field-wrapper="package_value">
                           <label for="package_value" class="control-label">Package Value</label>
                           <input type="text" id="package_value" name="package_value" class="form-control"  placeholder="0" readonly value="<?php echo $leadData->lead_packge_value; ?>">
                        </div>
                        <div class="form-group" app-field-wrapper="discount">
                           <label for="discount" class="control-label">Discount(%)</label>
                           <input type="text" id="discount" name="discount" class="form-control"  placeholder="0" value="<?php echo $leadData->lead_packge_discount; ?>">
                        </div>
                        <div class="form-group" app-field-wrapper="less_package_value">
                           <label for="less_package_value" class="control-label">Gross Package</label>
                           <input type="text" id="less_package_value" name="less_package_value" class="form-control"  placeholder="0" readonly value="<?php echo $leadData->lead_lesspckg_value; ?>">
                        </div>
                        <div class="form-group" app-field-wrapper="gst">
                           <label for="gst" class="control-label">GST Amount</label>
                           <input type="text" id="gst" name="gst" placeholder="18%" class="form-control" value="<?php echo $leadData->lead_packg_gst; ?>" readonly>
                        </div>
                        <div class="form-group" app-field-wrapper="total_amount">
                           <label for="total_amount" class="control-label">Package Cost</label>
                           <input type="text" id="total_amount" name="total_amount" class="form-control" placeholder="0" value="<?php echo $leadData->lead_packg_totalamount; ?>" readonly>
                        </div>
                         <input type="hidden" id="number_of_pages" name="number_of_pages" class="form-control" value="1"  readonly>
                        <?php if($leadData->lead_author_msstatus == 'inprocess'){ ?>
                        <? }?>
                        <div class="ms_status_inprogess" <?php if($leadData->lead_author_msstatus == 'completed'){ ?> style="display:show;" <? }else{?> style="display:none;" <?php } ?>>
                           <div class="row">
                              <div class="col-md-12">
                                 <h3>Payment Term</h3>
                              </div>
                              <div class="col-md-1"></div>
                              <div class="col-md-11"><strong><span id="prec_40" ></span></strong> 40% Booking Amount</div>
                              <div class="col-md-1"></div>
                              <div class="col-md-11"><strong><span id="prec_40_"></span></strong> 40% After 15 Day's</div>
                              <div class="col-md-1"></div>
                              <div class="col-md-11"><strong><span id="prec_20"></span></strong> 20% After 30 Day's</div>
                           </div>
                        </div>
                        <div class="ms_status_completed" <?php if($leadData->lead_author_msstatus == 'inprocess'){ ?> style="display:show;" <? }else{?> style="display:none;" <?php } ?>>
                           <div class="row">
                              <div class="col-md-12">
                                 <h3>Payment Term</h3>
                              </div>
                              <div class="col-md-12">Our Description</div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-4">
                     </div>
                     <div class="col-md-4">
                     </div>
                     <div class="btn-bottom-toolbar text-right btn-toolbar-container-out" style="width: calc(100% - 293px);">
                        <?php if(!empty($leadData)){ ?>
                        <!--<a href="<?php echo base_url("assets/authorMail/").$packageData->pdf_data; ?>" target="_blank" class="btn btn-info mail_preview" download>
                        Download
                        </a>-->
						<a href="<?php echo base_url("admin/leads/viewassignedleads"); ?>"  class="btn btn-info">
                        BACK
                        </a>
                        <!--<a type="button" href="javascript:void(0);<?php //echo admin_url('leadsdata/sendmail/'.$packageData->author_id);?>" class="btn btn-info mail_preview">Send Mail</a> -->
                        <?php } ?>
                        <button type="button" class="btn btn-info preview-btn" >Preview & Save</button>
                     </div>
                     <div class="col-md-4">
                     </div>
                  </div>
               </form>
               <!-- Modal Start -->
               <div id="preview_data" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                     <!-- Modal content-->
                     <div class="modal-content">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                           <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body preview-package">
                           <h4>Section I: Author Details</h4>
                           <table class="table table-bordered table-condensed">
                              <tbody>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>Author Name</strong>
                                       </h6>
                                       <span id="name"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>Email address</strong>
                                       </h6>
                                       <span id="emaild"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>Mobile</strong>
                                       </h6>
                                       <span id="mobiled"></span>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <hr/>
                           <h4>Section II: Package Information</h4>
                           <table class="table table-bordered table-condensed">
                              <tbody>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">Manuscript Status</h5>
                                          </strong>
                                       </h6>
                                       <span id="msstatusd"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">Package Details</h5>
                                          </strong>
                                       </h6>
                                       <span id="pkgdrtailsd"</span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">Book Type</h5>
                                          </strong>
                                       </h6>
                                       <span id="booktyped"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">Package Name</h5>
                                          </strong>
                                       </h6>
                                       <span id="packagenamed"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h4 style="font-size: 15px;">Services and Sub-service</h4>
                                          </strong>
                                       </h6>
                                       <span id="servicesd"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <!-- <td>
                                       <h6>
                                       
                                          <strong>
                                       
                                             <h5>Sub Services</h5>
                                       
                                          </strong>
                                       
                                       </h6>
                                       
                                       <span id="subservicesd">555-555-5555</span>
                                       
                                       </td> -->
                                 </tr>
                              </tbody>
                           </table>
                           <hr/>
                           <h4>Section III: Package Value Details</h4>
                           <table class="table table-bordered table-condensed">
                              <tbody>
							  <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">40% Booking Amount</h5>
                                          </strong>
                                       </h6>
                                       <span id="prec_400"></span>
                                    </td>
                                 </tr>
								 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">40% After 15 Day's</h5>
                                          </strong>
                                       </h6>
                                       <span id="prec_400_"></span>
                                    </td>
                                 </tr>
								 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">20% After 30 Day's</h5>
                                          </strong>
                                       </h6>
                                       <span id="prec_200"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">Package Value</h5>
                                          </strong>
                                       </h6>
                                       <span id="package_valued"></span>
                                    </td>
                                 </tr>
                                 <tr id="distr">
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">Discount(%)</h5>
                                          </strong>
                                       </h6>
                                       <span id="discountd">  </span>
                                    </td>
                                 </tr>
                                 <tr id="grosstr">
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">Gross Package</h5>
                                          </strong>
                                       </h6>
                                       <span id="less_package_valued"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">GST Amount</h5>
                                          </strong>
                                       </h6>
                                       <span id="gstd"></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <h6>
                                          <strong>
                                             <h5 style="font-size: 15px;">Total Amount</h5>
                                          </strong>
                                       </h6>
                                       <span id="total_amountd"></span>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <div class="modal-footer">
                              <button type="submit" form="create_package_form" class="btn btn-default">Save</button>
                           </div>
                        </div>
                        <div class="modal-body mail_body">
                           <h1>Author Submission form</h1>
                           <p >Dear <span class="mail_author_name"> </span>.</p>
                           <p>Please find below  the link of the Author's submission form, you are required to fill it and send it back. Feel free to contact me if you have any queries
                           <p>
                           <p><strong>Note: Please make sure the form should not contain any sort of spelling or grammatical mistake as it would only cause problem and unnecessary delay in process.</strong>
                           <p>
                           <div class="pdf_data">
                           </div>
                           <div class="modal-footer">
                              <!--  <a href="<?php // echo admin_url('leadsdata/sendmail'); ?>" class="btn btn-default author_id_mail">Send Mail</a> -->
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Modal Stop -->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<?php init_tail(); ?>
<?php// echo json_encode($packageData->services);exit; ?>
<script>

   $(window).load(function() {
   
         <?php $service = explode(", ", $leadData->services);
      $i = count($service); 
      ?>
   var count = <?php echo  $i; ?>;
   for (var i=1; i <= count; i++) {
   $(document).on('change','#services'+i, function() {
   
   var serveiceId = $(this).attr("id");
   
   var services = $(this).children(":selected").val();
   
   var serveiceLastIdValue =  serveiceId[serveiceId.length -1];
   
   var book_type = $("#book_type").val();
   
   var packages = $("#package").val();
   
   var parent_class = $(this).parent().parent().parent().prop('className');//Get parent class
   
   var lastChar = parent_class[parent_class.length -1];//Get last character from string
   
   var pppp = parent_class.slice(0, -1);//Remove last character from string
   
   
   $.ajax({
   
      type: "POST",
   
      url: "<?php echo admin_url('Leads/getsubservies'); ?>",
   
      data: {'services': services, 'book_type': book_type, 'packages': packages },
   
      dataType: "html",
   
      success: function(data){
   
   
   if(pppp == "row-add"){
   
      $(".sub_services_data_"+lastChar).html(data, 500);
   
   }else{
   
   
      $(".sub_services"+serveiceLastIdValue).html(data);
      //$(".sub_services_data_"+lastChar).html(data, 500);
   
   }
   
   },
   
   error: function() { alert("Error posting feed."); }
   
   });
   
   });
   }
				//var selects = $('select[name*="services"]')
    
		  
   
       <?php if($leadData->lead_package_detail){ ?>
	   
	   if($("#msstatus").val() == 'completed'){
					$(document).ready(function(){
					if($("#total_amount").val()){
					var total_amount_d = $("#total_amount").val();
					var discount_40 = ((40/100)*total_amount_d).toFixed(2);
					var discount_40_ = (40/100)*total_amount_d;
					var discount_20 = total_amount_d - (discount_40+discount_40);
					var discount_20_ = ((20/100)*total_amount_d).toFixed(2);
					document.getElementById("prec_40").innerHTML = '(₹ '+discount_40+')';
					document.getElementById("prec_40_").innerHTML = '(₹ '+discount_40+')';
					document.getElementById("prec_20").innerHTML = '(₹ '+discount_20_+')';
					document.getElementById("prec_400").innerHTML = '₹ '+discount_40+'';
					document.getElementById("prec_400_").innerHTML = '₹ '+discount_40+'';
					document.getElementById("prec_200").innerHTML = '₹ '+discount_20_+'';

					}
					});
				}
   
       var package_details = <?php echo $leadData->lead_package_detail; ?>;
       var lead_package_name = '<?php echo $leadData->lead_package_name; ?>';
   
       var book_type = '<?= $leadData->lead_book_type; ?>';
   
   
       var service = <?php echo json_encode($leadData->lead_service) ?>;
   
       var sub_service = <?php echo json_encode($leadData->lead_sub_service) ?>;
   
       var total_amount_data =  <?php echo $leadData->lead_packg_totalamount; ?>;
       //var less_pkg_value = '';
	    var less_pkg_value =  '<?php echo $leadData->lead_lesspckg_value; ?>';
              $("#total_amount").val(total_amount_data);

        <?php if($leadData->lead_packge_discount){
        $dicount  =  $leadData->lead_packge_discount;
        $total_data = $leadData->lead_packge_value;
         $new_data_calcuation = ($dicount / 100) * $total_data;
         ?>

         var total_amount_discount =  <?php echo $new_data_calcuation; ?>;
              $("#less_package_value").val(less_pkg_value);

       <?php }else{ ?>
         var gross_amount =  <?php echo $leadData->lead_packge_value; ?>;
              $("#less_package_value").val(less_pkg_value);
        <?php } ?>

       if(package_details == 1){
         

       $.ajax({
   
         	type: "POST",
   
         	url: "<?php echo admin_url('Leads/getserviesforedit'); ?>",
   
         	data: {'service': service, 'sub_service':sub_service,'book_type':book_type}, // <--- THIS IS THE CHANGE
   
         	dataType: "html",
   
         	success: function(data){
   
   
         		$("#getservicewithcost").html(data);
   
         		$("#testing").hide(500);
   
         		$("#all-services").hide(500);
   
         		//$('#package').html('<option selected="" value="">Select Package</option><option value="essential" <?php  ?>>ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option>');

         	    },
   
          });
   
       }else if(package_details == 2){
		   <?php $service = explode(", ", $leadData->lead_service);
			$ii = count($service); ?>
			
			$("#book_type_value").val('<?php echo $leadData->lead_book_type; ?>');
			
			
			
			$(document).on('click','.remove_fieldss', function() {

				var parent = $(this).parent().attr('class');
				if($(' .subservice_check').is(':checked')){
						var favorite = [];
				
				$.each($("."+parent+" .subservice_check:checked"), function(){            
					favorite.push($(this).attr("data-cost"));
				});
				var editcost = 0;
				editcost = $(".cost_page").val()*100;
				var total = 0;
				for (var i = 0; i < favorite.length; i++) {
					total += favorite[i] << 0;
				}
				var total_data_remove = $("#package_value").val();
				var total_data = total_data_remove-total;
				$("#package_value").val(total_data);
				
				if($("#discount").val()){
					var discount_datata = $("#discount").val();
				
					 var total_after_discount = ( discount_datata / 100) * total_data;
					total_after_discount = total_data - total_after_discount;
					$("#less_package_value").val(total_after_discount);
				}else{
					$("#less_package_value").val(total_data);
				}
				var gst = (total_data*18)/100;
				$("#gst").val(gst);
				var total_value = gst+total_data;
				if($("#discount").val()){
				total_value = gst+total_after_discount;
				}
				$("#total_amount").val(total_value);
				}
				$(this).parent('div').remove();
			});

	
	 
	 
	 
	 
	   }else if(package_details == 3){


       $.ajax({
   
            type: "POST",
   
            url: "<?php echo admin_url('Leads/getservies_sc_for_edit'); ?>",
   
            data: {'sub_service':sub_service,'book_type':book_type,'package_details':package_details,'lead_package_name':lead_package_name}, // <--- THIS IS THE CHANGE
   
            dataType: "html",
   
            success: function(data){
   
   
                  //alert(data);

              
              // console.log(data);
                  var obj = JSON.parse(data);
   
                  $('#getservicewithcost').show(500);
               
                  $("#getservicewithcost").html(obj.html, 500);
                   $(".book_type").hide(500);
     $(".package_data").hide(500);
     $("#testing").hide(500);
     $(".addmore").hide(500);
   
               //$('#package').html('<option selected="" value="">Select Package</option><option value="essential" <?php  ?>>ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option>');

                },
   
          });
      }
    <?php } ?>
   
   });
   
   $( ".remove_field2").click(function(){
   
   alert("The paragraph was clicked.");
   
   });

   $(document).ready(function(){
   
   
   $("#package_details").change(function(){
   
     $(".package_data").show(1000); 
   
     $(".customize_services").show(1000); 
   
   $('#book_type').html('<option selected="" value="">Select Book Type</option><option value="ebook">eBook</option><option value="paperback">Paperback</option>');
   
   $('#package').html('<option selected="" value="">Select Package</option><option value="essential">ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option>');
   
   
   
   $(".sub_services").html("");
   
   if(this.value == "2"){
   
   $(".package_ddl").hide(500);
   $(".data_hide").remove();
   
   
   $(".package_name").show(500);
   
   var author_name = $("#author_name").val();
   
          var str1 = " Book 1";
   
          var res = author_name.concat(str1);
   
   $("#package_name").val(res);
   
   $('#getservicewithcost').hide(500);
   
   $('.services').show(500);
   
   $('.addmore').show(500);
   
   $('#testing').show(500);
   
   $(".sub_services1").show(500);
   
   $(".sub_services2").show(500);
   
   $(".all-services").show(500);
   $('#package_value').val('');
   $('#less_package_value').val('');
   $('#gst').val('');
   $('#discount').val('');
   $('#total_amount').val('');
   $(".book_type_standard_custum").hide(500);
   $(".book_type").show(500);
      $(".package_data_for_s_c").hide();
   
   
   }else if(this.value == "3"){
       
     $(".book_type").hide(500);
     $(".package_data").hide(500);
     $("#testing").hide(500);
     $(".addmore").hide(500);
     $(".book_type_standard_custum").show(500);
     $(".package_data_for_s_c").show(500);
  
   
   }else{
      $(".package_data_for_s_c").hide();
      $(".book_type_standard_custum").hide(500);
   $(".package_ddl").show(500);
   $(".book_type").show(500);
   $(".package_name").hide(500);
   
   $('.services').html("<option value=''> --No Data Found-- </option>");
   
   //$('#services').selectpicker('refresh');
   
   $('#getservicewithcost').show(500);
   
   $('#getservicewithcost').html("");
   
   $('.services').hide(500);
   
   $('.addmore').hide(500);
   
   //   $('.sub_services1').hide(500);

   
   $("#testing").hide(500);
   
   $(".sub_services1").hide(500);
   
   $(".sub_services2").hide(500);
   
   $(".all-services").hide(500);
      $('#package_value').val('');
   $('#less_package_value').val('');
   $('#gst').val('');
   $('#total_amount').val('');
   }
   
   });
   
   $("#book_type").change(function(){
  
   
   if(package_details == 1){
   
   $('#package').html('<option selected="" value="">Select Package</option><option value="essential">ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option>');
   
   $('#package').selectpicker('refresh');
   
   $('.services').html("<option value=''> --No Data Found-- </option>");
   
   //$('#services').selectpicker('refresh');
   
   }
   else{
   
   $.ajax({
   
   type: "POST",
   
   url: "<?php echo admin_url('Leads/getservies'); ?>",
   
   data: {'package_data': 2, 'book_type': this.value}, // <--- THIS IS THE CHANGE
   
   dataType: "html",
   
   success: function(data){
   
   var obj = JSON.parse(data);
   
   $('.services').html();
   
   $('.services').html(obj);
   
   // 	$('.customize_services').show();
   
   $('#getservicewithcost input').attr("disabled", true);
   
   //$('#services').selectpicker('refresh');
   
   },
   
   error: function() { alert("Error posting feed."); }
   
   });
   
   }
   
   });
   
   
   $("#package").change(function(){
   
   var book_type = $('#book_type').val();
   
   var packagename = $('#package').val();
   var packagename_sc = $('#package_value_sc').val();
   alert(packagename);
   alert(packagename_sc);

   if(packagename !=''){
    $('#package_value_by_id').val(packagename);  
 }else{
    $('#package_value_by_id').val(packagename_sc);
 }
   
   
  
   
   
   if(book_type == ""){
   
   alert_float('warning', 'Please select book type!');
   
   $('#package').html('<option selected="" value="">Select Package</option><option value="essential">ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option>');
   
   $('#package').selectpicker('refresh');
   
   }else{
   
   var package_name = this.value;
   
   $.ajax({
   
   type: "POST",
   
   url: "<?php echo admin_url('Leads/getservies'); ?>",
   
   data: {'package': package_name, 'book_type':book_type}, // <--- THIS IS THE CHANGE
   
   dataType: "html",
   
   success: function(data){
   //console.log(data);
   if(packagename != ""){
   // alert('hello');
   	var obj = JSON.parse(data);
   
   	$('#getservicewithcost').show(500);
   
   	$("#getservicewithcost").html(obj.html, 500);
   
   	$('.services').hide(500);
   
   	$('#package_value').val(obj.pkgvalue);
   
   	$('#gst').val(obj.gst);
   
   	$('#total_amount').val(obj.totalamt);
   
   	$('#discount').val('0');
   
   	$('#less_package_value').val(obj.pkgvalue);
	if($("#msstatus").val() == 'completed'){
					$(document).ready(function(){
					if($("#total_amount").val()){
					var total_amount_d = $("#total_amount").val();
					var discount_40 = ((40/100)*total_amount_d).toFixed(2);
					var discount_40_ = (40/100)*total_amount_d;
					var discount_20 = total_amount_d - (discount_40+discount_40);
					var discount_20_ = ((20/100)*total_amount_d).toFixed(2);
					document.getElementById("prec_40").innerHTML = '(₹ '+discount_40+')';
					document.getElementById("prec_40_").innerHTML = '(₹ '+discount_40+')';
					document.getElementById("prec_20").innerHTML = '(₹ '+discount_20_+')';
					document.getElementById("prec_400").innerHTML = '₹ '+discount_40+'';
					document.getElementById("prec_400_").innerHTML = '₹ '+discount_40+'';
					document.getElementById("prec_200").innerHTML = '₹ '+discount_20_+'';

					}
					});
				}
   
   }else{
   
   	var obj = JSON.parse(data);
   
   	$('.services').html();
   
   	$('.services').html(obj);
   
   	$('.services').selectpicker('refresh');
   
   	$('#getservicewithcost').hide(500);
   
   	$('.services').show(500);
   
   }
   
   },
   
   error: function() { alert("Error posting feed."); }
   
   });
   
   }
   
   });
   
   
   $("#package_value").keyup(function() {
   
   var package_value = $(this).val();
   
   $('#less_package_value').val($(this).val());
   
   var gst = "";
   
   gst = (package_value*18)/100;
   
   $("#gst").val(gst);
   
   var total_amount = parseFloat(package_value) + parseFloat(gst);
   
   $("#total_amount").val(total_amount);
   
   });
   
   
   $(".preview-btn").click(function() {
   
    // var package_details = $("#package_details").val();
   
          var discount_value=$("#discount").val();
   
   var less_package_value=$("#less_package_value").val();
  
   
   if(discount_value == 0){
   
     $("#distr").hide();
   
   }
   
   if(less_package_value == 0){
   
     $("#grosstr").hide();
   
   }

   
   //var datahtml = $(".create_package_form").html();
   
   var formdata = $('form#create_package_form').serialize();
   
   //$(".preview-package").html(formdata['author_id']);
   
   $("#name").text($("#author_name").val());
   
   $("#mobiled").text($("#mobile").val());
   
   $("#emaild").text($("#email").val());
   
   $("#msstatusd").text($("#msstatus").find("option:selected").text());
   
   $("#pkgdrtailsd").text($("#package_details").find("option:selected").text());
   
   $("#booktyped").text($("#book_type").find("option:selected").text());
   
   if($("#package_details").find("option:selected").text() == "Standard"){
   
   $("#packagenamed").text($(".package_ddl").find("option:selected").text());
   
   }else{
   
   $("#packagenamed").text($(".package_name").val());
   
   }
   
   
   if($("#package_details").find("option:selected").text() == "Standard"){
   
    //$("#servicesd").html($("#getservicewithcost").html());
   
   
   //$("#getservicewithcost ul li").removeAttr("checked");
   
   var service_subservice_html = $("#getservicewithcost").html();
   
   var $temp = $(service_subservice_html);
   
   $("input", $temp).replaceWith("");
   

   
   var myhtmlcode = $temp.html();
   
   var finalhtml = "<ul>"+myhtmlcode+"</ul>"
   
    var services = $(".servicesstand:checked").map(function () {
   
       return $(this).data('name')
   
    }).get().join(', ');
   
    var serviceArray = services.split(", ");
   
    var servicehtml = "<ul>";
   
    for (i = 0; i < serviceArray.length; i++) {
   
       servicehtml += "<li>"+"- "+serviceArray[i]+"</li>";
   
       }
   
    servicehtml += "</ul>";
   
   
    var subservices = $(".subservicesstand:checked").map(function () {
   
       return $(this).data('name')
   
    }).get().join(', ');
   
    var sub_serviceArray = subservices.split(", "); 
   
    var sub_servicehtml = "<ul>";
   
    for (i = 0; i < serviceArray.length; i++) {
   
       sub_servicehtml += "<li>"+"- "+sub_serviceArray[i]+"</li>";
   
       }
   
       sub_servicehtml += "</ul>";
   
    //$("#servicesd").text(services);
   
   $("p").removeAttr("style");
   
    $("#servicesd").html(finalhtml);
   
    //$("#subservicesd").html(sub_servicehtml);
   
   }else if($("#package_details").find("option:selected").text() == "Customized"){
   
   var  comp = '<?php echo $leadData->lead_package_detail; ?>';
   
   if(comp == 2){
   var subservices = $(".subservices .subservice_check:checked").map(function () {
   
   return $(this).data('name')
   
   }).get().join(', ');
   var servicess = $(".all-services .services:checked").map(function () {
   
   return $(this).data('name')
   
   }).get().join(', ');
   var servicessArray = servicess.split(", ");
   //alert(servicessArray);
   }else{
   var subservices = $(".sub_services .subservice_check:checked").map(function () {
   
   return $(this).data('name')
   
   }).get().join(', ');
   var servicessArray = $(".all-services option:selected").text();
   }
   
   var sub_serviceArray = subservices.split(", ");
   
   
   var $firstval = $(".all-services .services option:selected").text();
   
   var htmldata = "<ul>";
   
   <?php if($leadData->lead_package_detail == 1){ ?>  
   htmldata += "<li><strong>"+$firstval+"</strong></li>";
   
   htmldata += "<ul>";
   
    for (i = 0; i < sub_serviceArray.length; i++) {
   
       htmldata += "<li>"+sub_serviceArray[i]+"</li>";
   
       }
   
       htmldata += "</ul>";
   <?php }else if($leadData->lead_package_detail == 2){ ?>
	
   <?php }else{ ?>
	      htmldata += "<li><strong>"+$firstval+"</strong></li>";
   
   htmldata += "<ul>";
   
    for (i = 0; i < sub_serviceArray.length; i++) {
   
       htmldata += "<li>"+sub_serviceArray[i]+"</li>";
   
       }
   
       htmldata += "</ul>";
   <?php } ?>
   
   for(i = 1; i <= 15; i++){
   
   if($(".customize_services div").hasClass("row-add"+i)){
   
   var $secondval = $(".servicess_data_"+i+" .services option:selected").text();
   
   htmldata += "<li><strong>"+$secondval+"</strong></li>";
   
   var subservicess = $(".sub_services_data_"+i+" .subservice_check:checked").map(function () {
   
   return $(this).data('name')
   
   }).get().join(', ');
   
   
   var subb_serviceArray = subservicess.split(", ");
   
   htmldata += "<ul>";
   
   for (var j = 0; j < subb_serviceArray.length; j++) {
   
   htmldata += "<li>"+subb_serviceArray[j]+"</li>";
   
   }
   
   htmldata += "</ul>";
   
   
   }else{
   
   console.log("False");
   
   
   }
   
   }
   
   htmldata += "</ul>";
   
    $("#servicesd").html(htmldata);
   



   }


   else if($("#package_details").find("option:selected").text() == "Standard Customized"){
   
//alert('right');
   var  comp = '<?php echo $leadData->lead_package_detail; ?>';
   
   if(comp == 3){
   var subservices = $(".subservicesstand_sc:checked").map(function () {
   
   return $(this).data('name')
   
   }).get().join(', ');
   var servicess = $(".servicesstand:checked").map(function () {
   
   return $(this).data('name')
   
   }).get().join(', ');
   var servicessArray = servicess.split(", ");
   //alert(servicessArray);
   }


   else{



      var subservices = $(".subservicesstand_sc:checked").map(function () {
    
         return $(this).data('name')
      }).get().join(', ');
      var sub_servicessArray = subservices.split(", ");
      // alert(sub_servicessArray);


      var servicess = $(".subservicesstand_sc:checked").map(function () {
         return $(this).data('service')
      }).get().join(', ');
      var servicessArray = servicess.split(", ");
      var all_service_name = [];

      var htmldata = "<ul>";
      // console.log(sub_servicessArray);
      for (var i = 0; i <= servicessArray.length; i++) { 
         if (servicessArray[i] != servicessArray[i+1]) {
            // // all_service_name = servicessArray[i];
            // all_service_name.push(servicessArray[i]);  
            // console.log(servicessArray[i]);
            htmldata += "<li>"+servicessArray[i]+"</li>";
            htmldata += "<ul style='color: red;'>";
            for(var j = 0; j <= sub_servicessArray.length; j++){

               console.log(sub_servicessArray[j]);
               htmldata += "<li>"+sub_servicessArray[j]+"</li>";
            }
            htmldata += "</ul>";
         }
      }
      //alert(all_service_name);
      htmldata += "</ul>";
      //return false;
      console.log(htmldata);
      //all_service_name.push(sub_servicessArray); 
      //alert(all_service_name);
   }
   
   // var sub_serviceArray = subservices.split(", ");
   
   
   // var $firstval = $(".all-services .services option:selected").text();
   
   // var htmldata = "<ul>";
   
   // <?php if($leadData->lead_package_detail == 1){ ?>  
   // htmldata += "<li><strong>"+$firstval+"</strong></li>";
   
   // htmldata += "<ul>";
   
   //  for (i = 0; i < sub_serviceArray.length; i++) {
   
   //     htmldata += "<li>"+sub_serviceArray[i]+"</li>";
   
   //     }
   
   //     htmldata += "</ul>";
   // <?php }else if($leadData->lead_package_detail == 2){ ?>
   
   // <?php }else{ ?>
      


   //    for (i = 0; i < all_service_name.length; i++) {
   //       htmldata += "<li><strong>"+all_service_name[i]+"</strong></li>";
   
   // htmldata += "<ul>";
   
   //  for (i = 0; i < sub_serviceArray.length; i++) {
   
   //     htmldata += "<li>"+sub_serviceArray[i]+"</li>";
   
   //     }
   
   //     htmldata += "</ul>";
   //  }
   // <?php } ?>
   
   
   // htmldata += "</ul>";
   
    $("#servicesd").html(htmldata);
   
   }
   
   $("#package_valued").text('₹ '+$("#package_value").val());
 
	   $("#discountd").text($("#discount").val());
	   
  
   
   
   $("#less_package_valued").text('₹ '+$("#less_package_value").val());
   
   $("#gstd").text('₹ '+$("#gst").val());
   
   $("#total_amountd").text('₹ '+$("#total_amount").val());
   
   $(".modal-title").text("Package Details");
   
   $(".mail_body").hide();
   
   $(".preview-package").show();
   
   $("#preview_data").modal('show');
   
   });
   
  
   
   $("#discount").keyup(function(){
   
   var package_value = ($("#package_value").val()*100)/100;
   
   var discount = ($(this).val()*100)/100;
   
   var discount_p = (package_value*discount)/100;
   
   var less_value = package_value-discount_p;
   
   var gst = (less_value*18)/100;
   
   var total_after_less = less_value+gst;
   
   $("#less_package_value").val(less_value);
   
   $("#gst").val(gst);
   
   $("#total_amount").val(total_after_less);
   
   });
   
   
   $(".mail_preview").click(function(){
   
   $(".modal-title").text("Mail Data");
   
   $(".mail_author_name").text($("#author_name").val());
   
   $(".author_id_mail").attr("href", "<?php echo admin_url('leadsdata/sendmail/'); ?>"+$("#author_id").val());
   
   $(".pdf_data").html($(".pdf_attachment").html());
   
   $(".mail_body").show();
   
   $(".preview-package").hide();
   
   $("#preview_data").modal('show');
   
   });

   
   $("#msstatus").change(function(){
   
   if(this.value == "inprocess"){
   
   $(".ms_status_inprogess").hide();
   
   $(".ms_status_completed").show();
   
   }else{
					$(document).ready(function(){
					if($("#total_amount").val()){
					var total_amount_d = $("#total_amount").val();
					var discount_40 = ((40/100)*total_amount_d).toFixed(2);
					var discount_40_ = (40/100)*total_amount_d;
					var discount_20 = total_amount_d - (discount_40+discount_40);
					var discount_20_ = ((20/100)*total_amount_d).toFixed(2);
					document.getElementById("prec_40").innerHTML = '(₹ '+discount_40+')';
					document.getElementById("prec_40_").innerHTML = '(₹ '+discount_40+')';
					document.getElementById("prec_20").innerHTML = '(₹ '+discount_20_+')';
					document.getElementById("prec_400").innerHTML = '₹ '+discount_40+'';
					document.getElementById("prec_400_").innerHTML = '₹ '+discount_40+'';
					document.getElementById("prec_200").innerHTML = '₹ '+discount_20_+'';

					}
					});
   
   $(".ms_status_inprogess").show();
   
   $(".ms_status_completed").hide();
   
   }
   
   });
   
   });
   
   
   
   $(document).on('change','.services', function() {
	   
		
		 var value = $(this).val();

  // alert(value);
     
   var services = $(this).children(":selected").val();
   //$(services).prop('disabled', true);
   
   var book_type = $("#book_type").val();
   
   var packages = $("#package").val();
   
   var parent_class = $(this).parent().parent().parent().parent().prop('className');//Get parent class
   // alert(parent_class);
   var lastChar = $("."+parent_class+"").attr('data-id');
   
//alert(lastChar);

   //var lastChar = parent_class[parent_class.length -1];//Get last character from string
//alert(parent_class);
var  parent_class_length = parent_class.length;
//alert(parent_class_length);
//if(parent_class_length==8){
if(parent_class_length==8){
	var pppp = parent_class.slice(0, -1);//Remove last character from string
}else{
	var pppp = parent_class.slice(0, -2);//Remove last character from string
}   
   //alert(pppp);
   //alert(lastChar);
   $.ajax({
   
      type: "POST",
   
      url: "<?php echo admin_url('Leads/getsubservies'); ?>",
   
      data: {'services': services, 'book_type': book_type, 'packages': packages },
   
      dataType: "html",
   
      success: function(data){
         //alert(data);
  
        
   
   if(pppp == "row-add"){
   
      $(".sub_services_data_"+lastChar).html(data, 500);
   
   }else{
   
      $(".sub_services").html(data, 500);
   
   }
   
   },
   
   error: function() { alert("Error posting feed."); }
   
   });
   
   });
   
   
   
   $(document).ready(function() {
   
     //maximum input boxes allowed
   var max_fields = 20;
   var wrapper = $(".customize_services");
   
    var add_button = $(".add_field_button"); //Add button ID
   
    //var x = 1; //initlal text box count
    // var packages =''; 
   
   
   /* $(".add_field_button").click(function(){
   
   var mycount = $(".inputcountvalue").val();
   
   }); */
   
   
   <?php if(!empty($leadData)) { ?>
	var dtttt = "<?php echo $leadData->lead_service; ?>";
	var dataArray = dtttt.split(", ");
	var x = dataArray.length;
	
		/*  $("#package_details").change(function(){
				 var value_datataa = $(this).val();
				if(value_datataa==2){
				  var x = 1;
			}
			}); */
			
	  // alert(x);
   <?php }else{ ?>
    var x = 1; //initlal text box count
	
  <?php } ?>
    $(add_button).click(function(e) { //on add input button click
   
    
     e.preventDefault();
   
   var packages = '<?php  echo $leadData->lead_package_detail; ?>';
   <?php $this->db->from('tblpackagesubservices a');
      $this->db->join('tblpackageservices b', 'b.id = a.serviceid'); 
      $this->db->where('a.book_type', $leadData->lead_book_type); 
      $this->db->where('a.packageid', 2); 
      $this->db->group_by('a.serviceid'); 
      $query = $this->db->get(); ?>
                          
   // console.log(packages);
   if (packages!='') {
	  var package_details_data = $("#package_details").val();
	 <?php if($leadData->lead_package_detail == 2){ ?>
   var fieldhtml = '<div class="col-md-12"><div class="form-group"><label for="services" class="control-label">Services:</label><select class="form-control services" id="" name="services[]" style="width: 100%; display:block;"><option value="">--Select--</option><?php foreach ($query->result() as $services_data) {?><option value="<?= $services_data->id; ?>" ><?php echo $services_data->service_name; ?></option><?php } ?></select></div></div>';
	 <?php }else{ ?>
		 var fieldhtml = $(".all-services").html();
	<?php } ?>
    /* $("#package_details").change(function(){
		 var value_datata = $(this).val();
		if(value_datata==2){
		 var fieldhtml = 'hello';
		 alert(fieldhtml);
	}
	}); */
   }else{
   //Fields wrapper 
   var fieldhtml = $(".all-services").html();
   
   }

   
     if (x < max_fields) { //max input box allowed

      x++; //text box increment
   //alert(x);
   // 	 var html_data =  ('<div class="row-add'+x+'">'+fieldhtml+'<div class="sub_services_data_'+x+'"></div><div style="cursor:pointer;background-color:red;float:right;margin-right: 15px;" class="remove_field btn btn-info">Remove</div></div><div class="clearfix"></div>')
   
      $(wrapper).append('<div class="row-add'+x+'" data-id="'+x+'"><div class="servicess_data_'+x+'">'+fieldhtml+'</div><div class="sub_services_data_'+x+'"></div><div style="cursor:pointer;background-color:red;float:right;margin-right: 15px;" class="remove_field btn btn-info">Remove</div></div><div class="clearfix"></div>'); //add input box
   
 
   
     }
   
     $('.selectpicker').selectpicker('refresh');
   
    });
   
    $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
   
     e.preventDefault();
   
   var parent = $(this).parent().attr('class');
				if($(' .subservice_check').is(':checked')){
						var favorite = [];
				
				$.each($("."+parent+" .subservice_check:checked"), function(){            
					favorite.push($(this).attr("data-cost"));
				});
				var editcost = 0;
				editcost = $(".cost_page").val()*100;
				var total = 0;
				for (var i = 0; i < favorite.length; i++) {
					total += favorite[i] << 0;
				}
				var total_data_remove = $("#package_value").val();
				var total_data = total_data_remove-total;
				$("#package_value").val(total_data);
				
				if($("#discount").val()){
					var discount_datata = $("#discount").val();
				
					 var total_after_discount = ( discount_datata / 100) * total_data;
					total_after_discount = total_data - total_after_discount;
					$("#less_package_value").val(total_after_discount);
				}else{
					$("#less_package_value").val(total_data);
				}
				var gst = (total_data*18)/100;
				$("#gst").val(gst);
				var total_value = gst+total_data;
				if($("#discount").val()){
				total_value = gst+total_after_discount;
				}
				$("#total_amount").val(total_value);
				}
     $(this).parent('div').remove();
   
     x--;
   
    })
   
   });

      $(document).on('change','#package_value_sc', function() {
      //alert('h');
         
          var book_type = $("#book_type_sc").val();
            if(book_type == ""){

            alert_float('warning', 'Please select book type!');

            $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option>');

            $('#package_value_sc').selectpicker('refresh');

            }else{
               
               var package_name = this.value;
               var package_details = $("#package_details").val();
               
               $.ajax({
               
               type: "POST",
               
               url: "<?php echo admin_url('Leads/getservies_sc'); ?>",
               
               data: {'package': package_name, 'book_type':book_type, 'package_details':package_details}, // <--- THIS IS THE CHANGE
               
               dataType: "html",
               
               success: function(data){
                    //alert(data);

              
              // console.log(data);
                  var obj = JSON.parse(data);
   
                  $('#getservicewithcost').show(500);
               
                  $("#getservicewithcost").html(obj.html, 500);
               
                 
               },
               
               error: function() { alert("Error posting feed."); }
               
               });
               
               }

          // $(".subservicesstand_sc").click(function(){
               
          //         alert('h');
          //        });


      }); 
         $(document).ready(function() {
            // $(".subservicesstand_sc").click(function(){
               
            //       //      var sub_service_id = $(".subservicesstand_sc").attr("data-id");
            //       //   var sub_service_cost = $(".subservicesstand_sc").attr("data-cost");
            //       // alert(sub_service_cost);
            //       alert('g');
            //      });

         });
         
         // function testing(data , data1){
         
         //     all_click_cost += data1;
         //    alert(all_click_cost);
         // }
         var all_click_cost = 0;
         function myFunction(data, data1) {
            var package_d_data = '';
             package_d_data = '<?php echo $leadData->lead_package_detail; ?>';
            if (package_d_data == 3) {

               all_click_cost = parseInt($("#package_value").val());
               // all_click_cost =(parseInt(all_click_cost)
               //alert(typeof(all_click_cost));
              //all_click_cost =  $("#less_package_value").val();
               var checkBox = document.getElementById("myCheck"+data);
              if (checkBox.checked == true){
               all_click_cost += data1;
               } else {
                all_click_cost -= data1;
               }
               $("#package_value").val(all_click_cost);
               $("#less_package_value").val(all_click_cost);
               var total_amount = (all_click_cost*18)/100;
               $("#gst").val(total_amount);
               total_amount_data = total_amount+all_click_cost;
                $("#total_amount").val(total_amount_data);
                $("#total_amount").val(total_amount_data);

            }else{
               
               var checkBox = document.getElementById("myCheck"+data);
              // alert(all_click_cost);
               if (checkBox.checked == true){
                  //alert(data1);
               all_click_cost += data1;
               } else {
                all_click_cost -= data1;
               }
              // alert(all_click_cost);
               $("#package_value").val(all_click_cost);
               $("#less_package_value").val(all_click_cost);
             
               
               var total_amount = (all_click_cost*18)/100;
               $("#gst").val(total_amount);
               total_amount_data = total_amount+all_click_cost;
                $("#total_amount").val(total_amount_data);
                $("#total_amount").val(total_amount_data);

            }



            }
              $(".book_type_standard_and_custum").change(function(){
               var book_type = $(this).val();
               alert(book_type);
               $('#book_type_value').val(book_type);
               alert(book_type);
              });
     
                 
                 


   
   
   
</script>
</body>
</html>