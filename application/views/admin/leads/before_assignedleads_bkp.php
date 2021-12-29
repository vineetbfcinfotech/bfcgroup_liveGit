<?php init_head(); 

?>

<div id="wrapper">

    <div class="content">

        <div class="row">

            <div class="col-md-12">

                <div class="panel_s" >

                    <div class="panel-body" style="overflow: auto;">

                            <div class="_buttons">

                                <div class="row">

                                    <div class="col-md-4">

                                         <h3>

                                          <button class="btn btn-alert"

                                                    onclick="window.location='<?= base_url(); ?>admin/leads/import_lead';"> Back

                                            </button>

                                         

                                            <!--<button class="btn btn-alert"

                                                    onclick="window.location='<?= base_url(); ?>admin/leads/allleads';"> Back

                                            </button> -->

                                        </h3> 

                                        <h3>Review Leads</h3>

                                      

                                    </div>

                                    <div class="col-md-4">

                                        

                                    </div>

                                     

                                    <!--<div class="col-md-4">

                                        <input type="text" value="" placeholder="search here...." name="leadsearch" id="leadsearch" onkeyup="searchFunction($(this).val())"><span id="nav-search"> <i class="fa fa-search"></i> Search</span>

                                    </div>-->

                                </div>

                        



                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>

						<?php if (is_admin()) { ?>

						<!-- <label class="select_row"><input type="checkbox" class="all_select" style="width: 22px; height: 22px;"></label><span style="position: relative;top: -4px; margin-right: 20px; font-size: 20px;"> Select All</span>

						 <button class="btn btn-warning" style="position: absolute;"><span class="delete_selected">Delete Selected</span></button>

						 --><div class="clearfix"></div>

                        

						<?php } ?>

                        <div >



                            <?php if (!empty($assignedleads) > 0) { ?>

                                <table class="table dt-table scroll-responsive example11">

                                    <thead>

                                     <tr>

									 

                                    <th ><?php echo _l('id'); ?></th>

                                       <th >Lead DB ID</th>

                                    <th class="bold">Name</th>

                                    <th class="bold">Contact Number</th>

                                    

                                    <th class="bold">Email Id</th>

                                    <th style="display:none" class="bold">Data Source</th>

                                    <th style="display:none" class="bold">Adset Name</th>

                                    <th style="display:none" class="bold">Adset Name</th>

                                    <th style="display:none" class="bold">Ad Name</th>

                                   

                                    <th style="display:none" class="bold">Manuscript Status</th>

                                    

                                    <th style="display:none" class="bold">Email Id</th>

                                    <th style="display:none" class="bold">Language</th>

                                    <th style="display:none" class="bold">Calling Date</th>

                                    <th style="display:none" class="bold">Category</th>

                                    <th style="display:none" class="bold">Remarks</th>

                                    

                                    

                                   

                                     <?php

                                    if (is_admin())

	                                        {

	                                       ?>

	                                        <th style="display:none" class="bold">Assigned to</th>

	                                          <?php

	                                        }

	                                        ?>

                                   <!-- <th>Status</th>

                                    <th>WP List</th>-->

                                    <!--<th>Created By</th>-->

                                    <th style="display:none" class="bold">Next Calling Date</th>

                                    <!--<th class="bold" style="display:none" >Published Earlier</th>-->

                                    <th class="bold">Created Date</th>

                                    

                                </tr>

                                    

                                    </thead>

                                    <tbody class="ajax-data">

                                    <?php $count = 1; foreach ($assignedleads as $alllead) { ?>

									

                                       <tr id='lead_id_<?= $alllead->id; ?>'>

                                           

									   

									       

                                        <td id="srNumberget">

                                            <?= $count; ?>

                                            <?php

                                            if (is_admin() || is_headtrm())

	                                        {

	                                       ?>

	                                          <a onclick="return confirm('Are you sure?')" href="<?= sprintf(base_url('admin/leads/delete_addedleads/%s'), $alllead->id); ?>"><i class="fa fa-trash text-danger"></i></a> 



	                                        <?php

	                                        }

	                                        ?>

                                            </td>

                                            

                                            <td> <a href="#"

                                               onclick="edit_product_catagory(this,<?= $alllead->id; ?>,'<?= $alllead->lead_author_name; ?>','<?= $alllead->PublishedEarlier; ?>','1');return false;"

                                               data-id="<?= $alllead->id; ?>"

                                                data-publishedEarlier="<?= $alllead->PublishedEarlier; ?>"

                                               data-name="<?= $alllead->lead_author_name; ?>"

                                               data-phone_number="<?= $alllead->phonenumber; ?>"

                         data-srnumber="<?= $count; ?>"

                                               data-booktitle="<?= $alllead->booktitle; ?>"

                                               data-book_format="<?= $alllead->book_format; ?>"

                                               data-otherphonenumber="<?= $alllead->otherphonenumber; ?>"

                                               data-email="<?= $alllead->email; ?>"

                                               data-designation="<?= $alllead->designation; ?>"

                                               data-company="<?= $alllead->company; ?>"

                                               data-address="<?= $alllead->address; ?>"

                                               data-data_source="<?= $alllead->adset_name; ?>"

                                               data-calling_objective="<?= $alllead->lead_adname; ?>"



                                              



                                               data-assigned="<?= $alllead->assigned; ?>"



                                               data-next_calling="<?= $alllead->next_calling; ?>"

                                             

                                               data-status="<?= $alllead->status; ?>"

                                               data-description="<?= $alllead->description; ?>"><?= $alllead->id; ?></td>

                                        <td>

                                            <?= $alllead->name; ?></a>

                                        </td>

                                        <td>

                                        <?php 

                                            $var = explode("p:+91",$alllead->phonenumber);

                                            if($var[0] != '' || $var[0] != null ){

                                                echo $var[0];

                                            }else{

                                              echo $var[1];  

                                            } 

                                        ?>

                                        </td>

                                        <td style="display:none" >

                                            <?php  $designation = str_replace("(", "", $alllead->designation);

                                            $designation = str_replace(")", "", $designation);

                                            $designation = str_replace("-", "", $designation);

                                            echo $designation;

                                            

                                            ?>

                                           </td>

                                           <td style="display:none;" >

                                               <?= $alllead->phonenumber; ?>

                                               </td>

                                                <td style="display:none;" >

                                               <?= $alllead->booktitle; ?>

                                               </td>

                                               <td style="display:none;" >

                                               <?= $alllead->book_format; ?>

                                               </td>

                                               

                                               

                                               

                                        <td style="display:none"><?= $alllead->lead_adname; ?></td>

                                        <td style="display:none">

                                            <? $address = $alllead->lead_author_msstatus;

                                            $address = str_replace(".", "", $address);

                                        $address =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$address);

                                            echo $address;  ?>

                                            </td>

                                        

                                        <td><?= $alllead->email; ?></td>

                                        <td style="display:none"><?= $alllead->lead_author_mslanguage; ?></td>

                                        <?php if($alllead->calling_date != '0000-00-00'){?>

                                       <td style="display:none"><?= $alllead->calling_date; ?></td>

                                       <?php }else {?>

                                        <td></td>

                                       <?php }?>

                                        <td style="display:none">

                                           

                                                

                                                <?php if($alllead->categorisation == 5) {echo "A";} ?>

                                                <?php if($alllead->categorisation == 16) {echo "B";} ?>

                                               <?php if($alllead->categorisation == 38) {echo "B+";} ?>

                                                <?php if($alllead->categorisation == 30) {echo "C";} ?>

                                                <?php if($alllead->categorisation == 32) {echo "NP";} ?>

                                                <?php if($alllead->categorisation == 39) {echo "Acquired";} ?>

                                               <?php if($alllead->categorisation == 40) {echo "UnAttended";} ?>

                                                <?php if($alllead->categorisation == 41) {echo "Scrap";} ?>

                                               

                                            </td>

                                            <?php 

                                            $string = $alllead->description;

                                            $explode_str = explode(" ",$string);

                                            $substring = substr($string,0,10);

                                            ?>

                                           

                                       <?php 

                                       $excerpt = array();

                                       $full = array();

                                       $arr_excerpt = array();

                                       $arr_full = array();

                                       $fullexcerpt = array();

                                       $fullexcerptfinal = array();

                                       for($i=0;$i<=count($explode_str);$i++){?>

                                                <?php if($i <= 3){?>

                                                    <?php $arr_excerpt = array_push($excerpt,$explode_str[$i]);?>

                                                <?php }else{ ?>

                                                     <?php $arr_full = array_push($full,$explode_str[$i]);?>

                                                <?php } ?>

                                        <?php } ?>

                                             <td style="display:none">

                                                <?php //echo $ex = implode(" ",$excerpt);?>

                                               <?php

											   $this->db->order_by("created_on", "desc");

												$this->db->where('lead_id',  $alllead->id);

												$this->db->group_by("remark");

												$this->db->select('remark');

												$data = $this->db->get('tblleadremark')->result();

												

												echo "<span>".substr(current($data)->remark,0,30)."</span>";

												echo "<span style='display: none;'>";

												echo substr(current($data)->remark,30)." |, ";

												unset($data[0]); // unset 1st element

												foreach($data as $remarkdata){

													echo $remarkdata->remark." |, ";

												}

												echo "</span>";

											   ?>

                                             </td>

                                        

                                       <!-- <td>-->

<!--<button name=remarks_<?= $alllead->id; ?> id="<?= $alllead->id; ?>" value="<?= $alllead->id; ?>" class="btn btn-info btn-lg remarks" data-toggle="modal" data-target="#myModal" data-id="<?= $alllead->id; ?>" data-remarks="<?= $alllead->remarks; ?>">Add Remark</button></td>

                                            

  -->                                           

                                       

                                        <?php

                                        $colid = $alllead->status;

                                        $this->db->where('id', $colid);

                                        $result = $this->db->get('tblleadsstatus')->result();

                                        ?>

                                       

                                        

                                        <?php

                                    if (is_admin())

	                                        {

	                                       ?>

	                                        <td> <?= $alllead->fullname; ?></td>

	                                          <?php

	                                        }

	                                        ?>

	                                        

	                                     

	                                       <?php if($alllead->next_calling != '0000-00-00 00:00:00'){?>

                                       <td style="display:none">

                                           <?php $nextCalling = explode(" ",$alllead->next_calling);

                                           

                                            echo $nextCalling[0]." ";

                                            if($nextCalling[1]!= '00:00:00')  {

                                                echo  $nextCalling[1];

                                            }                                     

                                               ?>

                                           <?//= $alllead->next_calling; ?>

                                           

                                           </td>

                                       <?php }else {?>

                                        <td></td>

                                       <?php }?>

	                                       

	                                       <td><?= $alllead->lead_created_date; ?></td>

                                    </tr>

                                    <?php $count++; } ?>

                                    </tbody>

                                </table>

                            <?php } else { ?>

                                <p class="no-margin"><?php echo "No Leads Found" ?></p>

                            <?php } ?>



                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

           

						

            <div class="col-md-12">

                <div class="panel_s">

                    <div class="panel-body">

                         <?

                        $loginid = $this->session->userdata('staff_user_id');

                        $this->db->where('staffid', $loginid);



                        $this->db->select('CONCAT(tbll.firstname, " ", tbll.lastname) AS fullname,tbll.staffid ');

                        $data = $this->db->get('tblstaff as tbll')->result();

                        //print_r($data);

                        ?>

                       <div class="row">

                                    <div class="col-md-4">

                                      <h3>

                                          Duplicate Leads

                                            

                                        </h3>  

                                    </div>

                                    <div class="col-md-4">

                                        

                                    </div>

                                    

                                   

                                    <!--<div class="col-md-4">

                                        <input type="text" value="" placeholder="search here...." name="leadsearch" id="leadsearch" onkeyup="searchFunction($(this).val())"><span id="nav-search"> <i class="fa fa-search"></i> Search</span>

                                    </div>-->

                                </div>

                        <div class="row">

                            <?php //print_r(count($getData));exit;

                            if(count($getData) != 0){?>

                            <table class="table dt-table table-bordered scroll-responsive">

							

                                <thead>

									<tr>

										<td class="bold text-center">Sr. No</td>

										<td class="bold text-center">Phone Number</td>

										<td class="bold text-center">Total Count</td>

										<td class="bold text-center">#</td>

									</tr>

                                </thead>

                                

                                <tbody id="tblResult">

                                   <?php

								   $a=1;

								   foreach($getData as $rt)

								   {

								    ?>

									 <tr>

									   <td class="text-center"><?php echo $a; ?> </td>

									   <!--<td class="text-center"><?php //$var = explode("91",$rt['phone_number']);echo $var[1]; ?></td>-->

									   <td class="text-center"><?php echo $rt['phonenumber']; ?></td>

									   <td class="text-center" ><?php echo $rt['cnt']; ?> </td>

									   <?php $phone = substr($rt['phonenumber'], -10);?>

									<td class="text-center"><a class="btn btn-small btn-primary" href="<?php echo base_url() ?>admin/leads/view_duplicate_list/<?php echo $phone;//$data = $rt['phone_number']; ?> ">View</a></td>

									</tr>

									<?php

									  $a++;

								   }

								   ?>

                                </tbody>

                            </table>

                            <?php }else{?>

                            <div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>

                             <p class=""><?php echo "No Lead Found" ?></p>

                             <?php } ?>

                        </div>



                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <div class="panel_s" >

                    <div class="panel-body" style="overflow: auto;">

                            <div class="_buttons">

                                <div class="row">

                                    <div class="col-md-4">

                                      <h3>

                                          All Leads data

                                            

                                        </h3>  

                                    </div>

                                    <div class="col-md-4">

                                        

                                    </div>

                                    

                                   

                                    <!--<div class="col-md-4">

                                        <input type="text" value="" placeholder="search here...." name="leadsearch" id="leadsearch" onkeyup="searchFunction($(this).val())"><span id="nav-search"> <i class="fa fa-search"></i> Search</span>

                                    </div>-->

                                </div>

                                

                               

                            



 

                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>

						

						

						<!--  <button class="btn btn-warning" style="position: absolute;"><span class="delete_selected">Delete Selected</span></button> -->

						<div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>

						

                        <div >



                            <?php if (!empty($remainleads) > 0) { ?>



                            <!-- /  delete all  --> 

                               <form action="<?php echo base_url() ?>admin/leads/delete_duplicateleads" method="POST">

                               	<label class="select_row"><input type="checkbox" class="all_select" style="width: 22px; height: 22px;"></label><span style="position: relative;top: -4px; margin-right: 20px; font-size: 20px;"> Select All</span>

                              <input type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger" name="delete_leads" value="Delete Selected" style="margin-bottom: 20px;">

                                <table class="table dt-table scroll-responsive example11">

                                   

                                    <thead>

                                     <tr>

									 <!-- <?php //if (is_admin()) { ?>

										<td></th>

									<?php// } ?> -->

                                    <th><?php echo _l('id'); ?></th>

                                    <th class="bold">Lead DB IDs</th>

                                    <th class="bold">Name </th>

                                    <th class="bold">Contact Number</th>

                                    

                                    <th  style="display:none" class="bold">Email Id</th>

                                    <th style="display:none" class="bold">Data Source</th>

                                    <th style="display:none" class="bold">Adset Name</th>

                                    <th style="display:none" class="bold">Adset Name</th>

                                    <th class="bold">Ad Name</th>

                                   

                                    <th class="bold">Manuscript Status</th>

                                    

                                    <th class="bold">Email Id</th>

                                    <th class="bold">Language</th>

                                   

                                    <th class="bold">Created Date</th>

                                    

                                </tr>

                                    

                                    </thead>

                                    <tbody class="ajax-data">

                                    <?php $count = 1; foreach ($remainleads as $alllead) { ?>

									

                                       <tr id='lead_id_<?= $alllead->id; ?>'>

									  <!--  <?php // if (is_admin()) { ?>

									   <td><input type="checkbox" name="deleted_value" value="<?= $alllead->id; ?>" class="selected_roww" ></td>

									   <?php // } ?> -->

                                        <td id="srNumberget">

                                            <?= $count; ?>

                                            <?php

                                            //if (is_admin() || is_headtrm())

	                                        //{

	                                       ?>

	                                          

                                           <input type="checkbox" class="selected_row"  name="delete_id[]" value="<?php echo $alllead->id; ?>"> <a onclick="return confirm('Are you sure?')" href="<?= sprintf(base_url('admin/leads/delete_addedleads/%s'), $alllead->id); ?>"><i class="fa fa-trash text-danger"></i></a> 

	                                        <?php

	                                        //}

	                                        ?>

                                            </td>

                                            <td><a href="#"

                                               onclick="edit_product_catagory(this,<?= $alllead->id; ?>,'<?= $alllead->lead_author_name; ?>','<?= $alllead->lead_publishedearlier; ?>','1','<?= $alllead->adset_name; ?>');return false;"

                                               data-id="<?= $alllead->id; ?>"

                                                data-publishedEarlier="<?= $alllead->PublishedEarlier; ?>"

                                               data-name="<?= $alllead->lead_author_name; ?>"

                                               data-phone_number="<?= $alllead->phonenumber; ?>"

											   data-srnumber="<?= $count; ?>"

                                               data-booktitle="<?= $alllead->booktitle; ?>"

                                                data-manuscript_status="<?= $alllead->lead_author_msstatus; ?>"

                                               data-user_language="<?= $alllead->lead_author_mslanguage; ?>"

                                               data-ad_id="<?= $alllead->lead_ad_id; ?>"

                                               data-created_time="<?= $alllead->created_time; ?>"

                                               data-book_format="<?= $alllead->book_format; ?>"

                                               data-otherphonenumber="<?= $alllead->otherphonenumber; ?>"

                                               data-email="<?= $alllead->email; ?>"

                                               data-designation="<?= $alllead->designation; ?>"

                                               data-company="<?= $alllead->company; ?>"

                                               data-address="<?= $alllead->address; ?>"

                                               data-data_source="<?= $alllead->adset_name; ?>"

                                               data-calling_objective="<?= $alllead->lead_adname; ?>"



                                              



                                               data-assigned="<?= $alllead->assigned; ?>"



                                               data-next_calling="<?= $alllead->next_calling; ?>"

                                             

                                               data-status="<?= $alllead->status; ?>"

                                               data-description="<?= $alllead->description; ?>"><?= $alllead->id; ?></a></td>

                                            

                                        <td>

                                             <?= $alllead->lead_author_name; ?>

                                        </td>

                                        <td>

                                        <?php 

                                            $var = explode("p:+91",$alllead->phonenumber);

                                            if($var[0] != '' || $var[0] != null ){

                                                echo $var[0];

                                            }else{

                                              echo $var[1];  

                                            } 

                                        ?>

                                        </td>

                                        <td style="display:none" >

                                            <?php  $designation = str_replace("(", "", $alllead->designation);

                                            $designation = str_replace(")", "", $designation);

                                            $designation = str_replace("-", "", $designation);

                                            echo $designation;

                                            

                                            ?>

                                           </td>

                                           <td style="display:none;" >

                                               <?= $alllead->otherphonenumber; ?>

                                               </td>

                                                <td style="display:none;" >

                                               <?= $alllead->booktitle; ?>

                                               </td>

                                               <td style="display:none;" >

                                               <?= $alllead->book_format; ?>

                                               </td>

                                               

                                               

                                               

                                        <td><?= $alllead->lead_adname; ?></td>

                                        <td>

                                            <? $address = $alllead->lead_author_msstatus;

                                            $address = str_replace(".", "", $address);

                                        $address =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$address);

                                            echo $address;  ?>

                                            </td>

                                        

                                        <td ><?= $alllead->email; ?></td>

                                        <td><?= $alllead->lead_author_mslanguage; ?></td>

                                        

	                                       

	                                       <td ><?= $alllead->lead_created_date; ?></td>

                                    </tr>

                                    

                                    <?php $count++; } ?>

                                   

                                    </tbody>

                                </table>

                              </from>

                            <?php } else { ?>

                                <p class="no-margin"><?php echo "No Lead Found" ?></p>

                            <?php } ?>

                             <?php $review_status=0; foreach ($remainleads as $alllead) { ?>

                             <?php if($alllead->review_status == 1){

                                 $review_status++;

                             }else{

                             

                             }?>

                             

                            <?php }?>

                             <?php if($alllead->lead_reviewstatus == 1){?>

                              <a href="javascript:void(0)" disabled class="btn btn-primary">submitted</a>

                              <?php }else{?>

                                <a href="" class="btn btn-primary" id="leadSubmit">Submit</a>

                               <?php }?> 

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    

    

    <?php init_tail();

    ?>

   <!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">

  <div class="modal-dialog">



    <!-- Modal content-->

    <div class="modal-content">

         <form action="<?php echo base_url() ?>admin/leads/addRemarks" method="POST">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Add Remarks here</h4>

      </div>

      <div class="modal-body">

          <textarea cols="50" rows="10" id="rem" name="remarksss"></textarea>

          

       <input type="hidden" name="remarks_id" id="remarks_id" class="remarks_ids" value=""/>

      </div>

      <div class="modal-footer">

          <a href="#" class="btn btn-default" id="submit" name="submit">add</a>

        

        <button type="button" class="btn btn-default" data-dismiss="modal" id="modelClose">Close</button>

      </div>

      <span id="alert-msg"></span>

      </form>

    </div>



  </div>

</div>



    <!-- Product category Data Add/Edit-->

    <div class="modal fade product-catagory-modal" id="product_catagory" tabindex="-1" role="dialog"

         aria-labelledby="myLargeModalLabel">





    </div>

</div>

</div>



<script type="text/javascript">



$('#leadSubmit').click(function(e){

    e.preventDefault();

    //alert(window.location.pathname);

    var href = location.href;

    //console.log(href);

    var array = href.split('/');

    //console.log(array);

    var start = array[9];

    var end = array[8];

    

    

     var form_data = {

        start: start,

        end: end,

    };

    $.ajax({

        url: "<?php echo site_url('admin/leads/addLeadSubmit'); ?>",

        type: 'POST',

         data: form_data,

       

        success: function(msg) {

           // alert(msg);

            if (msg == 'YES'){

            alert('Leads Submit successfully!');

            location.reload();

            $('#alert-msg').html('<div class="alert alert-success text-center">Leads Submit successfully!</div>');

                

                

            }else if (msg == 'NO'){

                $('#alert-msg').html('<div class="alert alert-danger text-center">Leads not Submit successfully!! Please try again later.</div>');

               

           } else{

                $('#alert-msg').html('<div class="alert alert-danger">' + msg + '</div>');

            /*setTimeout(function(){// wait for 5 secs(2)

                   //location.reload(); // then reload the page.(3)

                    window.location = 'http://bfcpublications.com/chorus/admin/leads/viewassignedleads';

            }, 5000); */   

           }   

        }

    });

})

function searchFunction(leadvalue){

   // alert(leadvalue);

    const pageURL = window.location.href,

        url = "<?= base_url('admin/leads/Searchcustom_lead_filter_added') ?>";

        

    $.get(url, {leadvalue: leadvalue},

        function (res) {

                $('.ajax-data').html(res);

        });

    

}

$("#submit").click(function(){  

    //alert();

    var form_data = {

        rem: $('#rem').val(),

        remarks_id: $('#remarks_id').val(),

    };

        /*var rem = $("#rem").val();  

        var remarks_id = $("#remarks_id").val(); */

        

        

        $.ajax({

        url: "<?php echo site_url('admin/leads/addRemarks'); ?>",

        type: 'POST',

         data: form_data,

        //data: form_data,

        success: function(msg) {

            //alert(msg);

            if (msg == 'YES')

                $('#alert-msg').html('<div class="alert alert-success text-center">Remarks added successfully!</div>');

                

                

            else if (msg == 'NO')

                $('#alert-msg').html('<div class="alert alert-danger text-center">Error in adding your remarks! Please try again later.</div>');

               

            else

                $('#alert-msg').html('<div class="alert alert-danger">' + msg + '</div>');

            setTimeout(function(){// wait for 5 secs(2)

                   //location.reload(); // then reload the page.(3)

                    window.location = 'http://bfcpublications.com/chorus/admin/leads/viewassignedleads';

            }, 5000);    

               

        }

    });

    return false;

        

        //alert(rem);

        //alert(remarks_id);

        });

/*$('#submit').click(function() {

    var form_data = {

        remarks: $('#rem').val(),

        

        id: 1

    };

    $.ajax({

        url: "<?php echo site_url('admin/leads/addRemarks'); ?>",

        type: 'POST',

        data: form_data,

        success: function(msg) {

            alert(msg);

            if (msg == 'YES')

                $('#alert-msg').html('<div class="alert alert-success text-center">Your mail has been sent successfully!</div>');

            else if (msg == 'NO')

                $('#alert-msg').html('<div class="alert alert-danger text-center">Error in sending your message! Please try again later.</div>');

            else

                $('#alert-msg').html('<div class="alert alert-danger">' + msg + '</div>');

        }

    });

    return false;

});*/

</script>

<script>

$('document').ready(function(){

    //alert("test");

    

    $("button").click(function() {

    var fired_button = $(this).val();

    //alert(fired_button);

    $('#remarks_id').val(fired_button);

    var remarks = $(this).attr("data-remarks");

    

    $('#rem').val(remarks);

    

});



$('#modelClose').click(function(){

   

});









})

   function edit_product_catagory(invoker, id, name,publishing, phonenumber,adname) {

        //print_r($data);

       // var name = $(invoker).data('name');



        var status = $(invoker).data('status');

        var description = $(invoker).data('description');

        var phonenumber = $(invoker).data('phone_number');

        //alert(phonenumber);

        var booktitle = $(invoker).data('booktitle');

        var book_format = $(invoker).data('book_format');

        var otherphonenumber = $(invoker).data('otherphonenumber');

        var srnumber = $(invoker).data('srnumber');

        //var email = $(invoker).data('email');

        var designation = $(invoker).data('designation');

        var company = $(invoker).data('company');

        var address = $(invoker).data('address');

        var data_source = $(invoker).data('data_source');

        var calling_objective = $(invoker).data('calling_objective');

        var meetingtimefrom = $(invoker).data('meetingtimefrom');

        var meetingtimeto = $(invoker).data('meetingtimeto');

        var assigned = $(invoker).data('assigned');

        var next_calling = $(invoker).data('next_calling');

       // var publishedEarlier = $(invoker).data('publishedEarlier');

        

        var name = $(invoker).data('name');

        var email = $(invoker).data('email');

        var publishedEarlier = $(invoker).data('publishedEarlier');

        var manuscript_status = $(invoker).data('manuscript_status');

        var user_language = $(invoker).data('user_language');

        var ad_id = $(invoker).data('ad_id');

        var created_time = $(invoker).data('created_time');

        var adname = $(invoker).data('adname');

        

         

        console.log(name);

        console.log(status);

        console.log(description);

        console.log(phonenumber);

        console.log(email);

        console.log(designation);

        console.log(company);

        console.log(address);

        console.log(data_source);

        console.log(calling_objective);

        console.log(meetingtimefrom);

        console.log(meetingtimeto);

        console.log(assigned);

        console.log(next_calling);

        console.log(booktitle);

        console.log(otherphonenumber);

       console.log("name:"+name);

        console.log("phone no:"+phonenumber);

        console.log("email:"+email);

        console.log("manuscript_status:"+manuscript_status);

       

        console.log("ad name:"+calling_objective);

       

        console.log("publishi:"+publishing);

        console.log("book language:"+user_language);

        console.log("ad id:"+ad_id);

        console.log("created time:"+created_time);

        



        $('#additional').append(hidden_input('id', id));

        $('#product_catagory input[name="id"]').val(id);

        $('#product_catagory input[name="name"]').val(name);

        $('#product_catagory input[name="phonenumber"]').val(phonenumber);

        $('#product_catagory input[name="booktitle"]').val(booktitle);

        $('#product_catagory input[name="book_format"]').val(book_format);

        $('#product_catagory input[name="otherphonenumber"]').val(otherphonenumber);

        $('#product_catagory input[name="email"]').val(email);

       

$('#product_catagory input[name="manuscript_status"]').val(manuscript_status);

$('#product_catagory input[name="user_language"]').val(user_language);

        $('#product_catagory input[name="ad_id"]').val(ad_id);

        $('#product_catagory input[name="created_time"]').val(created_time);

        $('#product_catagory input[name="publishedEarlier"]').val(publishing);

        $('#product_catagory input[name="adname"]').val(adname);

       

        $('#product_catagory input[name="data_source"]').val(data_source);

        $('#product_catagory input[name="calling_objective"]').val(calling_objective);

        $('#product_catagory input[name="meetingtimefrom"]').val(meetingtimefrom);

        $('#product_catagory input[name="meetingtimeto"]').val(meetingtimeto);

        $('#product_catagory input[name="assigned"]').val(assigned);

        $('#product_catagory input[name="srnumber"]').val(srnumber);

        $('#product_catagory input[name="next_calling"]').val(next_calling);

        //$('#product_catagory input[name="publishedEarlier"]').val(publishing);

        $('#product_catagory input[name="status"]').val(status);



        $.ajax({

            url: "<?php echo base_url(); ?>admin/leads/allleadremark_before",

            method: 'POST',

            data: {

                id: id,

                name: name,

                publishedEarlier:publishing,

                description: description,

                phonenumber: phonenumber,

                otherphonenumber:otherphonenumber,

                booktitle:booktitle,

                book_format:book_format,

                srnumber:srnumber,

                email: email,

                designation: designation,

                company: company,

                address: address,

                data_source: data_source,

                calling_objective: calling_objective,

                status: status,

                meetingtimefrom: meetingtimefrom,

                meetingtimeto: meetingtimeto,

                assigned: assigned,

                next_calling: next_calling,

                adname:calling_objective,

                manuscript_status: manuscript_status,

                user_language:user_language,

                ad_id:ad_id,

                created_time:created_time,

                

            },

            success: function (data) // A function to be called if request succeeds

            {

                  //console.log(data);

                $("#product_catagory").html(data);

                //$('#phonenumber').attr("disabled","disabled");

                //$('#data_source').attr("disabled","disabled");

                //$('#publishedEarlier').attr("disabled","disabled");

                //$('#email').attr("disabled","disabled");

                //$('#calling_objective').attr("disabled","disabled");

                $("#descriptions").removeAttr( "autocomplete" );

               // $('#autocomplete').autocomplete().disable();

                $("form").attr('autocomplete', 'off'); 

                $('#product_catagory').modal('show');

            }

        });

    }

</script>

<script>

    // $(document).on('change', '#lead_status_change', function () {

    //     const status = $(this).val(), id = $(this).data('lead-id'),

    //         url = "<?= base_url('admin/leads/update_cust_lead') ?>";

    //     $.get(url, {status: status, id: id}, function (res) {

    //         location.reload();

    //     })

    // });

    $(document).on('change', '.custom_lead_filter', function () {

		$('.all_select').prop('checked', false);

        const pageURL = window.location.href,

            urlcuren = pageURL.split( '/' ),

            status = $('#statusfilter').val(),

            company = $('#companyfilter').val(),

            data_source = $('#data_sourcefilter').val(),

            language = $('#userLanguagefilter').val(),

            publishedearlier = $('#publishedearlier').val(),

            manuscript_status = $('#menuscriptstatus').val(),

            createdDate = $('#createdDate').val(),

            adName = $('#adName').val(),

            

            source = $('#lead_sourcefilter').val(),

            lastcontact = $('#datefilter').val();

            url = "<?= base_url('admin/leads/custom_lead_filter_added') ?>";

            leadpagename = urlcuren[ urlcuren.length - 1 ];

            user_language = $('#userLanguagefilter').val();

            //alert(language);

        /*if(status == "" && company == "" && data_source == "" && calling_objective == "")

        {

            

            location.reload();

        }*/

            

        $.get(url, {status: status,leadpagename:leadpagename,user_language:user_language,manuscript_status:manuscript_status,createdDate:createdDate,adName:adName,publishedearlier:publishedearlier},

            function (res) {

                $('.ajax-data').html(res);

            })

    });

    

   

    

    

    $(document).on('change', '.rmList', function () {

        const staff_id = $(this).val(),

            lead_id = $(this).data('lead_id'),

            url = "<?= base_url('admin/leads/reassignlead') ?>";

        $.get(url, {lead_id: lead_id, staff_id: staff_id}, function (res) {

            //window.location.reload();

        })

    })

</script>

<script>

function confirmdelete() {

  

  

  var retVal = confirm("Are You Sure Want to delete ");

               if( retVal == true ) {

                  

                  return true;

               } else {

                  

                  return false;

               }

  

  

}

</script>





<script>

$(document).ready(function() {

	/* var table = $('.example11').DataTable({ 

		ajax: "http://bfcpublications.com/chorus/admin/leads/viewassignedleads",

		paging: false,

		searching: false 

	}); */

	

	$('.select_row').click(function() {

		if($('.all_select').is(":checked")) {

			/* if($('.selected_row').is(":checked")) {

				$('.selected_row').prop('checked', false);

			}else{

				$('.selected_row').prop('checked', true);

			} */

			$('.selected_row').prop('checked', true);

		}else{

			$('.selected_row').prop('checked', false);

		}

	});

	

	$(".delete_selected").click(function(){

		if(confirm('Are you sure you want to delete this?')){

			var selectdId = [];

			$.each($("input[name='deleted_value']:checked"), function(){            

				selectdId.push($(this).val());

			});

			$.ajax({

			  type: "POST",

			  url: "<?php echo admin_url('leads/deletedSelected'); ?>",

			  data: {selectdId: selectdId},

			  success: function (data) {

				if(data == 1){

					alert_float('success', 'Leads Deleted Successfully..');

					//table.ajax.reload();

					setTimeout(function() {

						location.reload();

					}, 2000);

				}else{

					alert_float('warning', 'Please Select Leads..');

				}

			  }

			  

			});

			$.each(selectdId, function(key, val){

				$('#lead_id_' + val).remove();

			});

		};

		/* var selectdId = [];

		$.each($("input[name='deleted_value']:checked"), function(){            

			selectdId.push($(this).val());

		});

		$.ajax({

		  type: "POST",

		  url: "<?php echo admin_url('leads/deletedSelected'); ?>",

		  data: {selectdId: selectdId},

		  success: function (data) {

			if(data == 1){

				alert_float('success', 'Leads Deleted Successfully..');

				tableData.ajax.reload();

			}else{

				alert_float('warning', 'Please Select Leads..');

			}

		  }

		  

		});

		$.each(selectdId, function(key, val){

			$('#lead_id_' + val).remove();

		}); */

	

    });

});

 

</script>

</body>

</html>