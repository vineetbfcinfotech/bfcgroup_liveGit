<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
              
            </div > 
        </div>
        <div class="row">
          
    </div>
        <div class="row">
              <div class="col-md-12">
                
        </div>
            <div class="col-md-12">
                
                    <div class="panel-body" style="overflow: auto;">
                            
                </div>
           
                <div class="panel_s" >
                    <div class="panel-body" style="overflow: auto;">
                        <div class="_buttons">
                                <div class="row">
                                    <div class="col-md-4">
                                      <h3>
            
                                            Add Reference Leads
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                   
                                </div>
                                <form action="<?php echo base_url() ?>admin/leads/addRefLeads" id="remarkform" autocomplete="off">
                                    <div class="col-md-12">
                                        <div class="row">
                                           <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Lead Creation Date</label>
                                                <input type="text" id="leadCreationDate" name="leadCreationDate" class="form-control" value="<?php echo date("Y-m-d h:i:s");?>" disabled>
                                               <!-- <input type="text" id="leadCreationDate" name="leadCreationDate" class="form-control datetimepicker" value="" autocomplete="no">--></div>
                                            </div>
                                            <div class="col-md-3" id="otherphonelabel">
                                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                                    <label for="otherphonenumber" class="control-label">Ad Id</label>
                                                    <input type="text" id="adId" name="adId" class="form-control" value="" autocomplete="no">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Ad Name</label>
                                                    <input type="text" id="adName" name="adName" class="form-control" value="">
                                               </div>
                                            </div>
                                             <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Category</label>
                                                    
                                                      <select onchange="origin()" name="category" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="category"
                                                   data-lead-id="<?= $allleadremark[0]->lead_id; ?>">
                                                        <option value="" selected>Select Category</option>
                                              <?php foreach ($lstatus as $leadst) {
                                                 echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $categoryStatus ? 'selected' : '', $leadst->name);
                                              } ?>
                                           </select>
                                               </div>
                                            </div> 
                                           
                                        </div>
                                        <div class="row">
                                           
                                            <div class="col-md-3" id="otherphonelabel">
                                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                                    <label for="otherphonenumber" class="control-label"> Manuscript Status</label>
                                                    <select name="manuscriptStatus" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="manuscriptStatus"
                                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>">
                                                         <option value="" selected></option>
                                                     <option value="completed" <?php if($publishedEarlier == 'completed'){ echo "selected";}?>>Completed</option>
                                                     <option value="in_process" <?php if($publishedEarlier == 'in_process'){ echo "selected";}?>>In Process</option>
                                                   </select> 
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Published Earlier</label>
                                                    
                                                     <select name="publishedEarlier" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="publishedEarlier"
                                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                                                      <option value="" selected></option>
                                                     <option value="yes" <?php if($publishedEarlier == 'yes'){ echo "selected";}?>>Yes</option>
                                                     <option value="no" <?php if($publishedEarlier == 'no'){ echo "selected";}?>>No</option>
                                                   </select>  
                                               </div>
                                            </div> 
                                            <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Author Name</label>
                                                <input type="text" id="name" name="name" class="form-control" value="<?php echo $name;?>" autocomplete="no" <?php// if($name !=''){ echo "disabled";}?>></div>
                                            </div>
                                            <div class="col-md-3" id="otherphonelabel">
                                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                                    <label for="otherphonenumber" class="control-label"> Contact Number</label>
                                                    <input type="text" id="otherphonenumber" name="otherphonenumber" class="form-control" value="<?php echo $otherphonenumber;?>" autocomplete="no">
                                                    <span id="error_data" style="color:red;"></span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                            <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Email</label>
                                                    <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>" <?php //if($email !=''){ echo "disabled";}?>>
                                               </div>
                                            </div>
                                            <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Book Title</label>
                                                    <input type="text" id="bookTitle" name="bookTitle" class="form-control" value="<?php echo $email; ?>" <?php //if($email !=''){ echo "disabled";}?>>
                                               </div>
                                            </div>
                                             <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Book Format</label>
                                                    
                                                     <select name="bookFormat" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="bookFormat"
                                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                                                      <option value="" selected>Select Book Format</option>
                                                     <option value="Ebook" <?php if($publishedEarlier == 'Ebook'){ echo "selected";}?>>Ebook</option>
                                                     <option value="Paperback" <?php if($publishedEarlier == 'no'){ echo "selected";}?>>Paperback</option>
                                                   </select>  
                                               </div>
                                            </div> 
                                              
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Book Language</label>
                                                <select name="bookLanguage" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="bookLanguage"
                                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>">
                                                    <option value="" selected></option>
                                                     <option value="hindi" <?php if($publishedEarlier == 'hindi'){ echo "selected";}?>>Hindi</option>
                                                     <option value="english" <?php if($publishedEarlier == 'english'){ echo "selected";}?>>English</option>
                                                     <option value="others" <?php if($publishedEarlier == 'others'){ echo "selected";}?>>Others</option>
                                                   </select> 
                                                </div>
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="row">
                                           <!-- <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="description">
                                                    <label for="description" class="control-label">Remark</label>
                                                    <input type="text" id="descriptions" name="description" class="form-control" value="">
                                                </div>
                                            </div>-->
                                             <div class="col-md-3">
                                                <!--<div class="form-group" app-field-wrapper="Reminder">
                                                    <label for="Reminder" class="control-label">Set Reminder</label>
                                                    <input type="checkbox" id="reminder" name="reminder" class="form-control" value="">
                                                </div> -->  
                                                 <div class="form-group" app-field-wrapper="next_calling"><label for="next_calling" class="control-label">Next Calling Date</label>
                           <input type="text" id="next_calling" name="next_calling" class="form-control datetimepicker" value="<?php    if($next_calling == null ){ echo ""; } elseif($next_calling == '0000-00-00 00:00:00'){ echo "";  }else { echo $next_calling; } ?>"></div>
                           
                                            </div> 
                                            <div class="col-md-3">
                                                 <div class="form-group" app-field-wrapper="description">
                                                    <label for="description" class="control-label">Remark</label>
                                                    <input type="text" id="descriptions" name="description" class="form-control" value="">
                                                </div>
                                               <!--<div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Book Language</label>
                                                    
                                                     <select name="bookLanguage" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="bookLanguage"
                                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                                                      <option value="" selected>Select Book Language</option>
                                                     <option value="Hindi" <?php if($publishedEarlier == 'Hindi'){ echo "selected";}?>>Hindi</option>
                                                     <option value="English" <?php if($publishedEarlier == 'English'){ echo "selected";}?>>English</option>
                                                     <option value="Others" <?php if($publishedEarlier == 'Others'){ echo "selected";}?>>Others</option>
                                                   </select>  
                                               </div>-->
                                            </div>
                                           
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-12">
                                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                        <button type="submit" id="add_lead" name="submit" class="btn btn-info category-save-btn">Add Lead</button>  
                                    </div>   
                                </form>

                        <div class="clearfix"></div>
                         <hr class="hr-panel-heading"/> <hr class="hr-panel-heading"/>
                        <div class="row">
                                    <div class="col-md-4">
                                      <h3>
            
                                              Reference Leads Details
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                   
                                </div>
                      
						<?php if (is_admin()) { ?>
						<div class="clearfix"></div>
                       
						<?php } ?>
                        <div >

                           

                        </div>
                    </div>
                            <div class="_buttons">
                                <div class="row">
                                    <div class="col-md-4">
                                      <h3>
                                            
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                    <!--<div class="col-md-4">
                                        <input type="text" value="" placeholder="search here...." name="leadsearch" id="leadsearch" onkeyup="searchFunction($(this).val())"><span id="nav-search"> <i class="fa fa-search"></i> Search</span>
                                    </div>-->
                                </div>
                                
                               
                                    
                                
                             <!-- <select id="statusfilter"  data-select="false" multiple data-none-selected-text="Filter By categorisation" data-live-search="true"  class="selectpicker custom_lead_filter">
                                   <option id="select_allstatus" >Select All</option>-->
                                  <?php /*if ( !empty($lstatus) ) {
                                      
                                     foreach ($lstatus as $leadfilter) { ?>
                                         <option value="<?= $leadfilter->id; ?>"><?= $leadfilter->name; ?></option>
                                     <?php }
                                  } */?>
                               <!--</select>-->
                               
                             <!--  <select id="adName" multiple data-none-selected-text="Filter By Ad Name" data-live-search="true" class="selectpicker custom_lead_filter">
                                  
                                  <?php //if ( !empty($get_adName) ) {
                                     //foreach ($get_adName as $adname) { ?>
                                         <option value="<?//= $adname->ad_name ?>">
                                             <?//= $adname->ad_name; ?></option>
                                     <?php// }
                                 // } ?>
                               </select>-->
                               
                               
                               <!--<select id="userLanguagefilter" multiple data-none-selected-text="Filter By Language" data-live-search="true" class="selectpicker custom_lead_filter">
                                  
                                  <?php //if ( !empty($get_language) ) {
                                     //foreach ($get_language as $language) { ?>
                                         <option value="<?//= $language->user_language ?>"><?//= $language->user_language ?></option>
                                     <?php// }
                                 // } ?>
                               </select>-->
                               
                              <!-- <select id="publishedearlier" multiple data-none-selected-text="Filter By Published Earlier" data-live-search="true" class="selectpicker custom_lead_filter">
                                  <option value="yes">yes</option>
                                  <option value="no">no</option>-->
                                  <?php /*if ( !empty($get_publishedearlier) ) {
                                     foreach ($get_publishedearlier as $publishedearlier) { ?>
                                         <option value="<?= $publishedearlier->PublishedEarlier ?>"><?= $publishedearlier->PublishedEarlier ?></option>
                                     <?php }
                                  } */?>
                               <!--</select>-->
                               
                              <!-- <select id="menuscriptstatus" multiple data-none-selected-text="Filter By Manuscript Status" data-live-search="true" class="selectpicker custom_lead_filter">
                                  
                                  <?php //if ( !empty($get_manuStatus) ) {
                                     //foreach ($get_manuStatus as $manuStatus) { 
                                     //if($manuStatus->manuscript_status == ''){ ?>
                                       
                                       <option value="<?//= $manuStatus->manuscript_status ?>">N/A</option>
                                     <?php// }else { ?>
                                         <option value="<?//= $manuStatus->manuscript_status ?>"><?//= $manuStatus->manuscript_status ?></option>
                                     <?php //}
                                 // } } ?>
                               </select>-->
                               
                               <!--<select id="createdDate" multiple data-none-selected-text="Filter By Created Date" data-live-search="true" class="selectpicker custom_lead_filter">
                                  
                                  <?php //if ( !empty($get_createdDate) ) {
                                     //foreach ($get_createdDate as $createdDate) { ?>
                                         <option value="<?//= $createdDate->created_at ?>">
                                             <?php 
                                             //echo $year = date('Y-m-d', strtotime($createdDate->created_at));
                                             ?></option>
                                     <?php// }
                                  //} ?>
                               </select>-->
                               
                               
                               
                               
                               <!--<select id="companyfilter"  multiple data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter">
                                   
                                  <?php if ( !empty($get_company) ) {
                                     foreach ($get_company as $get_comp) { ?>
                                         <option value="<?= $get_comp->company; ?>"><?= $get_comp->company; ?></option>
                                     <?php }
                                  } ?>
                               </select>-->
                               <!--<select id="data_sourcefilter" multiple data-none-selected-text="Filter By Data Source" data-live-search="true" class="selectpicker custom_lead_filter">
                                   
                                  <?php if ( !empty($data_source) ) {
                                     foreach ($data_source as $data_sour) {
                                        ?>
                                         <option value="<?= $data_sour->data_source; ?>"><?= $data_sour->data_source; ?></option>
                                     <?php }
                                  } ?>
                               </select>-->
                               
                               <!--<select id="lead_sourcefilter" multiple data-none-selected-text="Filter By Lead Source" data-live-search="true" class="selectpicker custom_lead_filter">
                                  
                                  <?php if ( !empty($getleadsource) ) {
                                     foreach ($getleadsource as $lead_sour) { ?>
                                         <option value="<?= $lead_sour->sourceid ?>"><?= $lead_sour->source ?></option>
                                     <?php }
                                  } ?>
                               </select>-->
                               
                              
                               
                               <!--<div class="dropdown bootstrap-select show-tick">
                                   <input type="text" id="datefilter" autocomplete="false" name="lastcontact"
                                          placeholder="Filter By Calling Date" class="form-control datepicker custom_lead_filter"/>
                                   
                               </div>-->

                            <!--   <div class="col-md-12">
            <div class="col-md-6">
                <div class="_buttons">
                     <h3>
                     <a href="<?= base_url(); ?>admin/leads/allleads">Back</a>                     
                   </h3>
                    
                  </div>
            </div>
            <div class="col-md-6">
                    <div class="_buttons">
                   <h3>
                    <?php echo ucfirst($filterlead->name) ?> - <?= $filterlead->uploaded_on; ?>                    
                   </h3>
                   </div>
            </div>
        </div> --->


                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<?php if (is_admin()) { ?>
						<!-- <label class="select_row"><input type="checkbox" class="all_select" style="width: 22px; height: 22px;"></label><span style="position: relative;top: -4px; margin-right: 20px; font-size: 20px;"> Select All</span>
						 <button class="btn btn-warning" style="position: absolute;"><span class="delete_selected">Delete Selected</span></button>
						 --><div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<?php } ?>
                        <div >

                            <?php if (!empty($assignedleads) > 0) { ?>
                                <table class="table dt-table scroll-responsive example11">
                                    <thead>
                                     <tr>
									
                                    <th><?php echo _l('id'); ?></th>
                                    <th class="bold">Name</th>
                                    <th class="bold">Contact Number</th>
                                    
                                    <th  style="display:none" class="bold">Email Id</th>
                                    <th style="display:none" class="bold">Data Source</th>
                                    <th style="display:none" class="bold">Adset Name</th>
                                    <th style="display:none" class="bold">Adset Name</th>
                                    <th class="bold">Ad Name</th>
                                   
                                    <th class="bold">Manuscript Status</th>
                                    
                                    <th class="bold">Email Id</th>
                                    <th class="bold">Language</th>
                                    
                                    
                                    
                                   
                                    
                                   <!-- <th>Status</th>
                                    <th>WP List</th>-->
                                    <!--<th>Created By</th>-->
                                   
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
                                        <td>
                                            <!--<a href="#"
                                               onclick="edit_product_catagory(this,<?= $alllead->id; ?>,'<?= $alllead->full_name; ?>','<?= $alllead->PublishedEarlier; ?>','1','<?= $alllead->adset_name; ?>');return false;"
                                               data-id="<?= $alllead->id; ?>"
                                                
                                               data-name="<?= $alllead->full_name; ?>"
                                               data-phone_number="<?= $alllead->phone_number; ?>"
											   data-srnumber="<?= $count; ?>"
                                               data-booktitle="<?= $alllead->booktitle; ?>"
                                               data-book_format="<?= $alllead->book_format; ?>"
                                               data-otherphonenumber="<?= $alllead->otherphonenumber; ?>"
                                               data-email="<?= $alllead->email; ?>"
                                               data-designation="<?= $alllead->designation; ?>"
                                               data-company="<?= $alllead->company; ?>"
                                               data-address="<?= $alllead->address; ?>"
                                               data-data_source="<?= $alllead->adset_name; ?>"
                                               data-publishedEarlier="<?= $alllead->PublishedEarlier; ?>"
                                               data-calling_objective="<?= $alllead->ad_name; ?>"
                                               data-manuscript_status="<?= $alllead->manuscript_status; ?>"
                                               data-user_language="<?= $alllead->user_language; ?>"
                                               data-ad_id="<?= $alllead->ad_id; ?>"
                                               data-created_time="<?= $alllead->created_time; ?>"

                                              

                                               data-assigned="<?= $alllead->assigned; ?>"

                                               data-next_calling="<?= $alllead->next_calling; ?>"
                                             
                                               data-status="<?= $alllead->status; ?>"
                                               data-description="<?= $alllead->description; ?>"> <?= $alllead->full_name; ?>-->
                                               <a href="<?php echo base_url();?>admin/leads/editReferleads/<?= $alllead->id; ?>"><?= $alllead->lead_author_name; ?></a>
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
                                               <?= $alllead->lead_booktitle; ?>
                                               </td>
                                               <td style="display:none;" >
                                               <?= $alllead->lead_bookformat; ?>
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
                                       
                                       
                                            <?php 
                                            $string = $alllead->description;
                                            $explode_str = explode(" ",$string);
                                            $substring = substr($string,0,10);
                                            ?>
                                      
                              
                                        <?php
                                        $colid = $alllead->status;
                                        $this->db->where('id', $colid);
                                        $result = $this->db->get('tblleadsstatus')->result();
                                        ?>
                                       
                                        
                                   
	                                        
	                                     
	                                  
	                                       
	                                       <td ><?= $alllead->lead_created_date; ?></td>
                                    </tr>
                                    <?php $count++; } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <p class="no-margin"><?php echo "No Lead Found" ?></p>
                            <?php } ?>

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
    $( "#otherphonenumber" ).keyup(function() {
        var number = $(this).val();
        if (number.length < 10) {
            $("#error_data").html('Number should be 10 Digit');
        }else{
            $("#error_data").html('');
            var form_data = {
                number: number,
            };
            $.ajax({
                url: "<?php echo site_url('admin/leads/reffer_lead_check'); ?>",
                type: 'POST',
                data: form_data,
                success: function(msg) {
                    if (msg==1) {
                        $('#add_lead').attr('disabled' , true);
                        $("#error_data").html('Can not add this number');
                    }else{
                        $('#add_lead').attr('disabled' , false);
                    } 
                }
            });
            
        }
    });
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
        //print_r(adname);
       
        var status = $(invoker).data('status');
        var description = $(invoker).data('description');
        var phonenumber = $(invoker).data('phone_number');
        var booktitle = $(invoker).data('booktitle');
        var book_format = $(invoker).data('book_format');
        var otherphonenumber = $(invoker).data('otherphonenumber');
        var srnumber = $(invoker).data('srnumber');
      
        var designation = $(invoker).data('designation');
        var company = $(invoker).data('company');
        var address = $(invoker).data('address');
        var data_source = $(invoker).data('data_source');
        var calling_objective = $(invoker).data('calling_objective');
        var meetingtimefrom = $(invoker).data('meetingtimefrom');
        var meetingtimeto = $(invoker).data('meetingtimeto');
        var assigned = $(invoker).data('assigned');
        var next_calling = $(invoker).data('next_calling');
        
        var name = $(invoker).data('name');
        var email = $(invoker).data('email');
        var publishedEarlier = $(invoker).data('publishedEarlier');
        var manuscript_status = $(invoker).data('manuscript_status');
        var user_language = $(invoker).data('user_language');
        var ad_id = $(invoker).data('ad_id');
        var created_time = $(invoker).data('created_time');
        var adname = $(invoker).data('adname');
        
        
        
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
        $('#product_catagory input[name="calling_objective"]').val(calling_objective);
        $('#product_catagory input[name="booktitle"]').val(booktitle);
        $('#product_catagory input[name="book_format"]').val(book_format);
        $('#product_catagory input[name="otherphonenumber"]').val(otherphonenumber);
        $('#product_catagory input[name="email"]').val(email);
        $('#product_catagory input[name="manuscript_status"]').val(manuscript_status);
        $('#product_catagory input[name="leadCreationDate"]').val(leadCreationDate);
        $('#product_catagory input[name="user_language"]').val(user_language);
        $('#product_catagory input[name="ad_id"]').val(ad_id);
        $('#product_catagory input[name="created_time"]').val(created_time);
       


       
        $('#product_catagory input[name="data_source"]').val(data_source);
        $('#product_catagory input[name="calling_objective"]').val(calling_objective);
        $('#product_catagory input[name="meetingtimefrom"]').val(meetingtimefrom);
        $('#product_catagory input[name="meetingtimeto"]').val(meetingtimeto);
        $('#product_catagory input[name="assigned"]').val(assigned);
        $('#product_catagory input[name="srnumber"]').val(srnumber);
        $('#product_catagory input[name="next_calling"]').val(next_calling);
        $('#product_catagory input[name="publishedEarlier"]').val(publishing);
        $('#product_catagory input[name="adname"]').val(adname);
        $('#product_catagory input[name="status"]').val(status);

        $.ajax({
          
           url: "<?php echo base_url(); ?>admin/leads/allleadremark_after",
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
                manuscript_status: manuscript_status,
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
                user_language:user_language,
                ad_id:ad_id,
                created_time:created_time,
                
            },
            success: function (data) // A function to be called if request succeeds
            {
                  //console.log(data);
                $("#product_catagory").html(data);
               // $('#phonenumber').attr("disabled","disabled");
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