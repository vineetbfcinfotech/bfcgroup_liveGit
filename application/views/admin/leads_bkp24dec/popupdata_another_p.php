<div class="modal-body">

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
                                $additional_author_copy =  $package_data->additional_author_copy;
                                   echo "<li>".$result->subservice_name ."(".$additional_author_copy.") </li>";
                            }else if($result->subservice_name == 'Book Cover'){
                               $book_cover_sc =  $package_data->book_cover_sc;
                                   echo "<li>".$result->subservice_name ."(".$book_cover_sc.") </li>";
                            }else if($result->subservice_name == 'Color Pages'){
								$color_pages =  $package_data->color_pages;
									echo "<li>".$result->subservice_name ."(".$color_pages.") </li>";
							 }
							else{
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

	 <?php if($package_data->lead_packge_discount > 0){?>

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
	 <?php  if (isset($package_data->cost_of_additional_copy) && ($package_data->cost_of_additional_copy != '') ) { ?>
	 <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Cost of additional copy</strong>

		   </h6>

		   <span id="total_amountd">₹ <?php echo $package_data->cost_of_additional_copy; ?></span>

		</td>

	 </tr>
	 <?php }  ?>
     <?php if (isset($package_data->gross_amt) && ($package_data->gross_amt != '') ) {?>
	  <tr>

		<td>

		   <h6>

			  <strong style="font-size: 17px;">Gross Amount</strong>

		   </h6>

		   <span id="total_amountd">₹ <?php echo $package_data->gross_amt; ?></span>

		</td>

	 </tr>
	 <?php } ?>
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

   
   <span >ashishkverma@bfcpublications.com</span><br>
   <span >gaurav@bfcpublications.com</span><br>

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

          <a href="<?php echo admin_url('Leads/createOthPackage/'.$package_data->leadid);?>" class="btn btn-success" >Edit</a>

	

          <a class="btn btn-success" href="<?php echo admin_url('Leads/sendmail_another/'.$package_data->id);?>" tooltip='test'>Send mail</a>
    
         <a class="btn btn-success" href="#" id="emaiInfoshow">Show Email CC info</a>

      </div>
      <script>
          $(document).ready(function(){
              $('#emaiInfoshow').click(function(){
                 $('#emaiInfo').show();    
              })
           //
          });
      </script>
	