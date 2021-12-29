<?php init_head(); ?>
<!-- all lead code by Shourabh -->
<style>
    .bootstrap-select {
        width: 100% !important;
    }
    .bootstrap-select .dropdown-toggle .filter-option-inner-inner {
        text-transform: none !important;
    }
    .dynamicservice ul {
        margin-left: 20px !important;
    }
    .rowpackage {
        margin-bottom: 10px;
    }
    #servicesd ul ul {
        margin-left: 15px;
    }
</style>
<?php init_clockinout(); //print_r($packages);exit; 
?>
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
                            <h2 class="text-center">Other Package Details</h2>
                            <h4>Section I: Author Details</h4>

<table class="table table-bordered table-condensed">

  <tbody>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Author Name</strong>

		   </h6>

		   <span id="name"><?php echo $package_data->lead_author_name; ?></span>

		</td>

	 </tr>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Email address</strong>

		   </h6>

		   <span id="emaild"><?php echo $package_data->email; ?></span>

		</td>

	 </tr>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Mobile</strong>

		   </h6>

		   <span id="mobiled"><?php echo $package_data->phonenumber; ?></span>

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

			  <strong style="font-size: 17px;">Manuscript Status</strong>

		   </h6>

		   <span id="msstatusd"><?php echo $package_data->lead_author_msstatus; ?></span>

		</td>

	 </tr>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Package Details</strong>

		   </h6>

		   <span id="pkgdrtailsd"><?php if($package_data->lead_package_detail == 1){ echo "Standard"; }else if($package_data->lead_package_detail == 2){ echo "Customized"; }else if($package_data->lead_package_detail == 3){ echo "Standard Customized"; } ?></span>

		</td>

	 </tr>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Book Type</strong>

		   </h6>

		   <span id="booktyped"><?php if($package_data->lead_book_type == "ebook"){ echo "eBook"; }else{ echo "Paperback"; } ?></span>

		</td>

	 </tr>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Package Name</strong>

		   </h6>

		   <span id="packagenamed"><?php echo $package_data->lead_package_name; ?></span>

		</td>

	 </tr>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Services and Sub-service</strong>

		   </h6>

		   <span id="servicesd">

		       <ul>

		       <?php
		        foreach($services as $service){ 

		       echo "<li><strong>".$service->service_name . "</strong></li>"; 
		       echo "<ul style='margin: 0px 0px 0px 15px;' >";
		        foreach($sub_services as $sub_service){ 
		        	$this->db->select('*');

					$this->db->from('tblpackagesubservices');

					$this->db->where_in('id', $sub_service->id); 

					$this->db->where('serviceid', $service->id); 
					if ($sub_service->packageid != 2) {

						 $this->db->where('book_type', $package_data->lead_book_type); 
					}

					//

					$this->db->where('packageid', $sub_service->packageid);

					$result = $this->db->get(); 
					$result = $result->row();
					// echo $this->db->last_query();
					 if ($result->subservice_name == 'Number of Pages Allowed') {
                                
                            $number_page =  $package_data->lead_book_pages;
                                // htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                                 echo "<li>".$result->subservice_name ."(".$number_page.") </li>";
                            }else if ($result->subservice_name == 'Format Editing') {
                                
                               $number_page =  $package_data->lead_book_pages;
                                // htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                                  echo "<li>".$result->subservice_name ."(".$number_page.") </li>";
                            }else if ($result->subservice_name == 'Paper Type') {
                                
                              $paper_type_sc =  $package_data->paper_type_sc;
                                  echo "<li>".$result->subservice_name ."(".$paper_type_sc.") </li>";
                            }else if($result->subservice_name == 'Book Size'){
                               $book_size_sc =  $package_data->book_size_sc;
                                   echo "<li>".$result->subservice_name ."(".$book_size_sc.") </li>";
                            }else if($result->subservice_name == 'Lamination'){
                                 $lamination_sc =  $package_data->lamination_sc;
                                   echo "<li>".$result->subservice_name ."(".$lamination_sc.") </li>";
                            }else if($result->subservice_name == 'Complimentary Author Copies'){
                                $complimentry_copies =  $package_data->complimentry_copies;
                                   echo "<li>".$result->subservice_name ."(".$complimentry_copies.") </li>";
                            }else if($result->subservice_name == 'Additional Author Copies - Order at Subsidised Price'){
                                $last_lead_status =  $package_data->last_lead_status;
                                   echo "<li>".$result->subservice_name ."(".$last_lead_status.") </li>";
                            }else if($result->subservice_name == 'Book Cover'){
                               $book_cover_sc =  $package_data->book_cover_sc;
                                   echo "<li>".$result->subservice_name ."(".$book_cover_sc.") </li>";
                            }else{
                                 echo "<li>".$result->subservice_name . "</li>"; 
                            }
		       
					

		       }
		    echo "</ul>";
		       }?>


		        </ul>

		       </span>

		</td>

	 </tr>

	<!--  <tr>

		<td>

		   <h6>

			   <strong style="font-size: 17px;">Sub Services</strong> 

		   </h6>

		   <span id="subservicesd">
		     <ul>

		       <?php foreach($sub_services as $sub_service){ 

		       //echo "<li> - ".$sub_service->subservice_name . "</li>"; 

		       }?>

		       </ul> 

		  </span>

		</td>

	 </tr> -->

  </tbody>

</table>

<hr/>

<h4>Section III: Package Value Details</h4>

<table class="table table-bordered table-condensed">

  <tbody>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Package Value</strong>

		   </h6>

		   <span id="package_valued">₹ <?php echo $package_data->lead_packge_value; ?></span>

		</td>

	 </tr>

	 <?php if($package_data->discount > 0){?>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Discount(%)</strong>

		   </h6>

		   <span id="discountd"> <?php echo $package_data->lead_packge_discount; ?></span>

		</td>

	 </tr>

	 <?php }?>

	 <?php if($package_data->less_pkg_value > 0){?>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Less Package Value</strong>

		   </h6>

		   <span id="less_package_valued">₹ <?php echo $package_data->less_pkg_value; ?></span>

		</td>

	 </tr>

	 <?php }?>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">GST Amount (18%)</strong>

		   </h6>

		   <span id="gstd">₹ <?php echo $package_data->lead_packg_gst; ?></span>

		</td>

	 </tr>

	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Total Amount</strong>

		   </h6>

		   <span id="total_amountd">₹ <?php echo $package_data->lead_packg_totalamount; ?></span>

		</td>

	 </tr>
	 <?php if(($package_data->website > 0) && ($package_data->junk > 0)){ ?>
	 	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Cost of additional copy</strong>

		   </h6>

		   <span id="total_amountd">₹ <?php echo $package_data->website; ?></span>

		</td>

	 </tr>
	  <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Gross Amount</strong>

		   </h6>

		   <span id="total_amountd">₹ <?php echo $package_data->junk; ?></span>

		</td>

	 </tr>
	 <?php  }else{} 
	 if ($package_data->create_p_offer != '') { ?>
		 <tr>
		 <td>

			<h6>

			<strong style="font-size: 17px;">Offers</strong>

			</h6>

			<span id="offers"><?php echo $package_data->create_p_offer; ?></span>

			</td>
		 </tr>
	 <?php } ?>
	 <tr>

<td id="emaiInfo" style="display:none;">

   <h6>

	  <strong style="font-size: 17px;">Mail CC Information</strong>

   </h6>

   
   <span >ashishkumarvermabfc@gmail.com</span><br>
   <span >shivangiyadav@bfcpublications.com</span>

</td>

</tr>

  </tbody>

</table>



 </div>

      <div class="modal-footer">

          <a href="<?php echo base_url("assets/authorMail/").$package_data->lead_pdf_data; ?>" target="_blank" class="btn btn-success mail_preview" download>

                          Download

                      </a>

          <a href="<?php echo admin_url('Leads/multi_create_pack/'.$package_data->id);?>" class="btn btn-success" >Edit</a>

	

          <a class="btn btn-success" href="<?php echo admin_url('Leads/sendmail_another/'.$package_data->id);?>" tooltip='test'>Send mail</a>
    
         <a class="btn btn-success" href="#" id="emaiInfoshow">Show Email CC info</a>

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
    <? php // echo json_encode($packageData->services);exit; 
    ?>
    <script>
          $(document).ready(function(){
              $('#emaiInfoshow').click(function(){
                 $('#emaiInfo').show();    
              })
           //
          });
      </script>
    
    </body>
    </html>