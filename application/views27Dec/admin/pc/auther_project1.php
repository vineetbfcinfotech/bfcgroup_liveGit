
<style>

  .submit-block button{
   margin-top: 4px;
 }

 .assinged-block button{
   position: relative;
   left: 250px;
 }

</style>

<?php init_head(); ?>
<div id="wrapper">
 <?php //init_clockinout(); ?>
 <div class="content">
  <div class="row">
   <div class="col-md-12">
    <div class="panel_s">
     <div class="panel-body">
      <div class="_buttons">
       <a href="<?php echo base_url();?>admin/pm_lead/inprogress_projects"  class="btn mright5 btn-info pull-left display-block">
       Back  </a>
       <?php  
       $sub_service = explode(", ", $all_data->lead_sub_service); 
       $service = explode(", ", $all_data->lead_service); 
      $data_asf = $this->db->get_where('chorus_asf',array('lead_id'=>$all_data->id))->row();
        ?>
     </div>
     <div class="clearfix"></div>
     <hr class="hr-panel-heading" />

        <div class="col-md-12">
        <h4></h4>
              <div class="row">

              <div class="col-md-2" id="otherphonelabel">
              <div class="form-group" app-field-wrapper="otherphonenumber">
              <label for="otherphonenumber" class="control-label">Author Name</label>
              </div>
              </div>
              <div class="col-md-10" id="otherphonelabel">
              <div class="form-group" app-field-wrapper="otherphonenumber">
              <?php echo $all_data->lead_author_name; ?>

              </div>
              </div>

              </div> 

              <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">View/Download ASF</label>
                  </div>
                  </div>
                  <div class="col-md-4" id="otherphonelabel">
                    <div class="form-group" app-field-wrapper="otherphonenumber">
                      <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">View ASF</a>
                    </div>
                  </div>
                  <div class="col-md-4" id="otherphonelabel">
                    <div class="form-group" app-field-wrapper="otherphonenumber">
                      <?php if ($data_asf->profile_pic_url != '') { ?>
                      <a class="btn btn-info" target="_blank" href="<?php echo $data_asf->profile_pic_url; ?>">View Author ASF image</a>
                    <?php  }else{ ?>
                      <a class="btn btn-danger" >Author's Image not Available</a>
                    <?php } ?>
                      
                    </div>
                  </div>
              </div> 
              <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Upload MS</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                      <?php if (empty($all_data->lead_raw_ms)) { ?>
                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/upload_ms" enctype="multipart/form-data"> 
                      <div class="form-group">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                      <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                      <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Upload</button>
                      </div>
                      </form>
                      <?php }else{ ?>
                      <a class="btn btn-prime">Uploaded</a>
                      <?php  } ?>
                  </div>
                  </div>
                </div>


                <?php foreach ($sub_service as $key => $value) {
                $sub_name = $this->db->get_where('tblpackagesubservices',array('id'=>$value))->row();
                if ($sub_name->subservice_name  == 'Format Editing (Upto 100 Pages)' || $sub_name->subservice_name  == 'Format Editing') {?>


                    <div class="row">
                    <div class="col-md-2" id="otherphonelabel">
                    <div class="form-group" app-field-wrapper="otherphonenumber">
                    <label for="otherphonenumber" class="control-label">Format Editing</label>
                    </div>
                    </div>
                    <div class="col-md-10" id="otherphonelabel">
                    <div class="form-group" app-field-wrapper="otherphonenumber">
                    <?php if ($all_data->lead_raw_ms=='') { ?>
                        <a class="btn btn-default btn-xs" disabled  class="MainNavText" >Assigned To</a>
                        <?php }else{ ?>
                          <div class="row">
                        <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/assigned_fe_pr"> 
                          <div class="col-md-3" id="otherphonelabel">
                        <div class="form-group">
                        <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                        <input type="hidden" name="hidden_id_for_check" id="hidden_id_for_check" class="hidden_id" value="1"/>
                        <select class="form-control" id="format_assign_to" name="format_assign_to" <?php if ($all_data->project_assign_to) { echo ""; }?>>
                        <option>-select-</option>
                        <option <?php if ($all_data->project_assign_to_fe == 85) { echo "Selected"; }?> value='85'>Manish(<?= $manish_remaing_pages;?>)</option>
                        <option <?php if ($all_data->project_assign_to_fe == 81) { echo "Selected"; }?> value='81'>Amrendra(<?=$amrendra_remaing_pages;?>) </option>
                        <option <?php if ($all_data->project_assign_to_fe == 82) { echo "Selected"; }?> value='82'>Gaurav(<?=$gourav_remaing_pages;?>)</option>

                        </select> 

                        </div>
                      </div>
                      <div class="col-md-3" id="otherphonelabel">
                        <div class="submit-block">
                        <div class="form-group">
                        <button type="submit" id="" class="btn btn-info" <?php if ($all_data->project_assign_to) { echo ""; }?> >Assign</button>
                        </div>
                        </div>
                      </div>
                         <div class="col-md-3" id="otherphonelabel">
                          <?php if ($all_data->lead_fe_ms_file) { ?>
                        <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/<?php echo $all_data->lead_fe_ms_file ?>">Download MS</a>
                      <?php }else{} ?>
                         </div>
                        </form>
                      </div>
                        <?php } 
                   

                        if ($all_data->lead_fe_ms_file) { ?>
                        
                        <div class="row">
                        <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/format_editor_ms"> 
                          <div class="col-md-3" id="otherphonelabel">
                        <div class="form-group">
                        <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                        <input type="hidden" name="hidden_id_fe" id="hidden_id_fe" class="hidden_id" value="<?php echo $all_data->project_assign_to; ?>"/>
                        <label for="sel1">MS Status</label>
                        <select class="form-control" id="rework_or_completed" name="rework_or_completed" <?php if ($all_data->lead_fe_ms_rework == 2) { echo "disabled"; }?>>
                        <option>-select-</option>
                        <option <?php if ($all_data->lead_fe_ms_rework == 2) { echo "Selected"; }?> value="2">Approved</option>
                        <option <?php if ($all_data->lead_fe_ms_rework == 1) { echo "Selected"; }?> value="1">Rework</option>
                        </select>
                        </div>
                      </div>
                      <div class="col-md-2" id="otherphonelabel">
                        <div class="submit-block">
                        <div class="form-group">
                        <button type="submit" id="" class="btn btn-info" <?php if ($all_data->lead_fe_ms_rework == 2) { echo "disabled"; }?>>Submit</button>
                        </div>
                        </div>
                      </div>
                      <div class="col-md-2" id="otherphonelabel">
                           <?php if ($all_data->lead_fe_ms_ebook) { ?>
                        <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/ebook/<?php echo $all_data->lead_fe_ms_ebook ?>">Download ebook MS</a>
                        <?php } ?>
                      </div>
                      <div class="col-md-2" id="otherphonelabel">
                           <?php  if ($all_data->lead_fe_ms_paperback){ ?>
                        <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/paperback/<?php echo $all_data->lead_fe_ms_paperback ?>">Download Paperback MS</a>
                        <?php } ?>
                      </div>
                      <div class="col-md-2" id="otherphonelabel">
                           <?php  if ($all_data->lead_fe_ms_doc) { ?>
                        <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/doc/<?php echo $all_data->lead_fe_ms_doc ?>">Download Doc MS</a>
                        <?php } ?>
                      </div>
                        </form>
                      </div>
                        <?php } ?>
                    </div>
                    </div>
                    </div>

         








                <?php }
                if ($sub_name->subservice_name  == 'Proofreading' || $sub_name->subservice_name == 'Proof Reading') {?>
                 <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Proof Reading</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">

                    <?php if ($all_data->lead_raw_ms=='') { ?>
                    <a class="btn btn-default btn-xs" disabled  class="MainNavText" >Assigned To</a>
                    <?php }else{ ?>
               <div class="row">
                    <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/assigned_fe_pr"> 
                  <div class="col-md-3" id="otherphonelabel">
                    <div class="form-group">
                    <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                    <input type="hidden" name="hidden_id_for_check" id="hidden_id_for_check" class="hidden_id" value="2"/>
                    <select class="form-control" id="format_assign_to" name="format_assign_to" <?php if ($all_data->project_assign_to ==90 || $all_data->project_assign_to==108 || $all_data->project_assign_to==89) { echo ""; }?>>
                    <option>-select-</option>
                    <option <?php if ($all_data->project_assign_to == 90) { echo "Selected"; }?> value='90'>Ravindra(<?= $ravindra_remaing_pages;?>)</option>
                    <option <?php if ($all_data->project_assign_to == 108) { echo "Selected"; }?> value='108'>Bharti(<?=$bharti_remaing_pages;?>)</option>
                    <option <?php if ($all_data->project_assign_to == 89) { echo "Selected"; }?> value='89'>Chayan(<?=$chayan_remaing_pages;?>)</option>

                    </select> 

                    </div>
                  </div>
                  <div class="col-md-3" id="otherphonelabel">
                    <div class="submit-block">
                    <div class="form-group">
                    <button type="submit" id="" class="btn btn-info" <?php if ($all_data->project_assign_to ==90 || $all_data->project_assign_to==80) { echo ""; }?> >Assign</button>
                    </div>
                    </div>
                  </div>
                  <div class="col-md-4" id="otherphonelabel">
                   <?php if ($all_data->lead_pr_ms_file) { ?>
                    <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/proof_reader_ms/<?php echo $all_data->lead_pr_ms_file ?>">Download Ms</a>
                  <?php } ?>
                  </div>
                    </form>
                  </div>
                    <?php } 
                 
                    if ($all_data->lead_pr_ms_file) { ?>
                   <div class="row">
                    <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/proof_reader_ms"> 
                      <div class="col-md-3" id="otherphonelabel">
                    <div class="form-group">
                    <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                    <input type="hidden" name="hidden_id_pr" id="hidden_id_pr" class="hidden_id" value="<?php echo $all_data->project_assign_to; ?>"/>
                    <label for="sel1">MS Status</label>
                    <select class="form-control" id="rework_or_completed" name="rework_or_completed" <?php if ($all_data->lead_pr_ms_rework == 2) { echo "disabled"; }?>>
                    <option>-select-</option>
                    <option <?php if ($all_data->lead_pr_ms_rework == 2) { echo "Selected"; }?> value="2">Approved</option>
                    <option <?php if ($all_data->lead_pr_ms_rework == 1) { echo "Selected"; }?> value="1">Rework</option>
                    </select>
                    </div>
                  </div>
                   <div class="col-md-3" id="otherphonelabel">
                    <div class="submit-block">
                    <div class="form-group">
                    <button type="submit" id="" class="btn btn-info" <?php if ($all_data->lead_pr_ms_rework == 2) { echo "disabled"; }?>>Submit</button>
                    </div>
                    </div>
                  </div>
                   <div class="col-md-4" id="otherphonelabel">
                   </div>
                    </form>

                    <?php }
                    ?>
                  </div>
                  </div>
              </div>
              </div> 
                <?php }


                if ($sub_name->subservice_name  == 'Cover Design - Premium' || $sub_name->subservice_name  == 'Cover Design' || $sub_name->subservice_name  == 'Cover Design - Basic' || $sub_name->subservice_name  == 'Cover Design-Free') {?>
                   <div class="row">
                        <div class="col-md-2" id="otherphonelabel">
                        <div class="form-group" app-field-wrapper="otherphonenumber">
                        <label for="otherphonenumber" class="control-label">Cover</label>
                        </div>
                        </div>
                       
                        
                          <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/cover_assigned_to"> 
                             <div class="col-md-3" id="otherphonelabel">
                        <div class="form-group" app-field-wrapper="otherphonenumber">
                          <div class="form-group">
                          <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                          <!-- <label for="sel1">Cover Assign To</label> -->
                          <select class="form-control" id="cover_assign_to" name="cover_assign_to" <?php if ($all_data->lead_asf_assign_gd) { echo ""; }?>>
                          <option>-select-</option>
                          <option <?php if ($all_data->lead_asf_assign_gd == 84) { echo "Selected"; }?> value="84">Affan(<?= $affan_remaing_pages;?>)</option>
                          <option <?php if ($all_data->lead_asf_assign_gd == 83) { echo "Selected"; }?> value="83">Surabhi(<?= $surabhi_remaing_pages;?>)</option>
                          </select> 

                          </div>
                        </div>
                        </div>

                           <div class="col-md-3" id="otherphonelabel">
                   
                          <div class="submit-block">
                          <div class="form-group">
                          <button type="submit" id="" class="btn btn-info" <?php if ($all_data->lead_asf_assign_gd) { echo ""; }?> >Assign</button>
                          </div>
                          </div>
                        </div>
                          </form>
                           <?php 
if ($all_data->gd_cover) { ?>
   <div class="col-md-3" id="otherphonelabel">
    <div class="submit-block">
    <div class="form-group">
 <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/cover/gd_cover/<?php echo $all_data->gd_cover ?>">Download Cover</a>
</div>
</div>
</div>
</div>
  <div class="row">

 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/cover_assign_work"> 
   <div class="col-md-2" id="otherphonelabel">
    <div class="submit-block">
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id_for_cover" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <input type="hidden" name="staff_cover_id" id="staff_cover_id" class="hidden_id" value="<?php echo $all_data->lead_asf_assign_gd; ?>"/>
   <label for="sel1" id="ms_status" <?php if ($all_data->project_status_gd <= 4) { ?>style="display: block;" <?php }else{ ?>
style="display: none;"
 <?php } ?>>Cover Status</label>
 <label for="sel1" id="additional_ms_status" <?php if ($all_data->project_status_gd == 5) { ?>style="display: block;" <?php }else{ ?>
style="display: none;"
 <?php } ?>>Additional Cover Status</label>
</div>
</div>
</div>
 <div class="col-md-3" id="otherphonelabel">
    <div class="submit-block">
  <div class="form-group">
   <select class="form-control cover_rework_or_completed_2" id="" name="rework_or_completed" <?php if ($all_data->project_status_gd == 3 ) { echo "disabled "; }else if( $all_data->gd_ad_work_status == 3){
   echo "disabled "; }?><?php if ($all_data->project_status_gd == 5 ) { ?> style="display: none;" <?php }else{ ?>
style="display: block;"
 <?php } ?>>>
    <option>-select-</option>
    <option <?php if ($all_data->project_status_gd == 3) { echo "Selected"; }?> value="3">Approved</option>
    <option <?php if ($all_data->project_status_gd == 5) { echo "Selected"; }?> value="5">Additional cover</option>
    <option <?php if ($all_data->project_status_gd == 4) { echo "Selected"; }?> value="4">Rework</option>
  </select>
  <select class="form-control" id="additional_rework_or_completed" name="additional_rework_or_completed" <?php if ($all_data->gd_ad_work_status == 3) { echo "disabled"; }?> <?php if ($all_data->project_status_gd == 5) { ?>style="display: block;" <?php }else{ ?>
style="display: none;"
 <?php } ?>>
    <option>-select-</option>
    <option <?php if ($all_data->gd_ad_work_status == 3) { echo "Selected"; }?> value="3">Additional Approved</option>
   
    <option <?php if ($all_data->gd_ad_work_status == 2) { echo "Selected"; }?> value="2">Additional Rework</option>
  </select>
</div>
</div>
</div>
 <div class="col-md-3" id="otherphonelabel">

<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" <?php if ($all_data->gd_ad_work_status == 3 || $all_data->gd_work_status == 3) { echo "disabled"; }?>>Submit</button>
 </div>
</div>
</div>
</form>
<?php if ($all_data->gd_additional_cover != '') { ?>
<a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/cover/additional_cover/<?php echo $all_data->gd_additional_cover ?>">Download Additional Cover</a>
<?php }} ?>  

  <?php if ($all_data->lead_gd_final_cover) { ?>
       <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/cover/final_cover/cover_jpg/<?php echo $all_data->lead_gd_final_cover ?>">Download Book Cover</a>
     <?php } 
     if ($all_data->lead_gd_final_front){ ?>
         <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/cover/final_cover/front_cover/<?php echo $all_data->lead_gd_final_front ?>">Download Book Cover Front</a>
    <?php }
    if ($all_data->lead_gd_final_back) { ?>
       <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/cover/final_cover/back_cover/<?php echo $all_data->lead_gd_final_back ?>">Download Book Cover Back</a>
    <?php }
    if ($all_data->gd_creative) { ?>
       <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/cover/creative/<?php echo $all_data->gd_creative ?>">Download creative</a>
    <?php } ?>  





                        </div>
                       




                <?php }

                }

echo "<br>";
                foreach ($service as $key => $value_service) {
                $service_name = $this->db->get_where('tblpackageservices',array('id'=>$value_service))->row();
                if ($service_name->service_name == 'Marketing your Masterpiece') { ?>
                  <div class="row">
                        <div class="col-md-2" id="otherphonelabel">
                        <div class="form-group" app-field-wrapper="otherphonenumber">
                        <label for="otherphonenumber" class="control-label">Marketing</label>
                        </div>
                        </div>
                        <div class="col-md-10" id="otherphonelabel">
                        <div class="form-group" app-field-wrapper="otherphonenumber">
                        
                        <?php if ($all_data->lead_raw_ms=='') { ?>
                      <a class="btn btn-default btn-xs" disabled  class="MainNavText" >Assigned To</a>
                      <?php }else{ ?>

                         <div class="row">
                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/marketing_assigned_to"> 
                            <div class="col-md-3" id="otherphonelabel">
                      <div class="form-group">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                      <label for="sel1">DM Assign To</label>
                      <select class="form-control" id="market_assign" name="market_assign" <?php if ($all_data->lead_asf_assign_marketing) { echo "disabled"; }?>>
                      <option>-select-</option>
                      <option <?php if ($all_data->lead_asf_assign_marketing == 86) { echo "Selected"; }?> value="86">Nitesh</option>
                      <option <?php if ($all_data->lead_asf_assign_marketing == 87) { echo "Selected"; }?> value="87">Amulya</option>
                      </select> 

                      </div>
                    </div>
                          <div class="col-md-3" id="otherphonelabel">
                      <div class="submit-block">
                      <div class="form-group">
                      <button type="submit" id="" class="btn btn-info" <?php if ($all_data->lead_asf_assign_marketing) { echo "disabled"; }?> >Assign</button>
                      </div>
                      </div>
                    </div>
                      </form>
                    </div>
                       <?php





                        }


    $all_dm_report = $this->db->get_where('upload_dm_report_table',array('lead_id'=>$all_data->id))->result();
                      if (!empty($all_dm_report)) {
                      foreach ($all_dm_report as $key => $all_dm_report) { ?>
                      <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/digital_marketing/report/<?php echo $all_dm_report->upload_report ?>"><?php  echo $all_dm_report->sub_service_name; ?></a>
                      <?php  }
                      }
                  

                      ?>





                        </div>
                        </div>
                  </div>
                




                <?php }
                }
                 ?>






                <div class="row">  
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">ISBN</label>
                  </div> 
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                    <div class="row">
                    <div class="col-md-2" id="otherphonelabel">
                        <?php if ($all_data->lead_isbn_status == 1) { ?>
                          <button type="button" class="btn btn-success apply_isbn" data-b_title="<?php echo $all_data->lead_booktitle; ?>" data-author_n="<?php echo $all_data->lead_author_name; ?>" >ISBN Applied </button>
                       <?php }else{ ?>
                        <button type="button" class="btn btn-info apply_isbn" data-id="<?php echo $all_data->id; ?>" data-b_title="<?php echo $all_data->lead_booktitle; ?>" data-author_n="<?php echo $all_data->lead_author_name; ?>" id="isbn_apply">Apply ISBN</button>
                      <?php } ?>
                    </div>
                        <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/upload_isbn" > 
                          
                            <div class="col-md-10" id="otherphonelabel">
                              <div class="row">
                              <div class="col-md-4">
                              <div class="form-group">
                              <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                              <label>eBook Number</label>
                              <input type="text" class="from-control border-none" name="ebook" value="<?php echo $all_data->lead_isbn_ebook; ?>">
                              </div>
                              </div>
                              <div class="col-md-4">
                              <div class="form-group">
                                <label>Paperback Number</label>
                                <input type="text" class="from-control border-none" name="paperback" value="<?php echo $all_data->lead_isbn_paperback; ?>">
                                </div>
                                </div>
                                <div class="col-md-4">
                                  <label></label>
                              </div>
                             
                              <!-- </div>
                              <div class="col-md-4" id="otherphonelabel"> -->
                               
                      <!-- </div>
                       <div class="col-md-2" id="otherphonelabel"> -->
                        <div class="submit-block">
                        <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Submit</button>
                        </div>
                      </div>
                      </div>
                        </form>
                       
                         </div>
                  </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">MRP Approved by author</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                    <div class="row">
                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/mrp_approved" >
                      <div class="col-md-3">

                    <div class="form-group">
                    <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                   
                    <input type="text" class="from-control border-none" name="mrp_approved_by_author" value="<?php echo $all_data->mrp_approved_by_author; ?>">
                    </div>
                      </div>
                      <div class="col-md-3">
                          <div class="submit-block">
                    <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Submit</button>
                    </div>
                      </div>
                      <div class="col-md-4">
                      </div>
                        </form>
                    </div>
                  
                 
                  </div>
              </div>
               <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Total No. of pages</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                    <div class="row">
                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/total_no_of_pages" >  <div class="col-md-3">
                      <div class="form-group">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                      <input type="text" class="from-control border-none" name="total_no_of_pages" value="<?php echo $all_data->lead_book_pages; ?>">
                      </div>
                    </div>
                     <div class="col-md-3">
                      <div class="submit-block">
                      <button type="submit" id="" class="btn btn-info" >Submit</button>
                      </div>
                    </div>
                     <div class="col-md-4">
                     </div>
                      </form>
                  </div>
                  </div>
              </div>
               <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Total No. of copies</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                    <div class="row">

                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/total_no_of_copy" >
                      <div class="col-md-3"> 
                      <div class="form-group">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                      <input type="text" class="from-control border-none" name="total_no_of_copy" value="<?php echo $all_data->total_number_of_copies; ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="submit-block">
                      <button type="submit" id="" class="btn btn-info" >Submit</button>
                      </div>
                    </div>
                    <div class="col-md-4">
                    </div>
                      </form>
                  </div>
                  </div>
              </div>
               <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Complimentry Copies</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                    <div class="row">
                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/complimentry_copies" > 
                        <div class="col-md-3">
                      <div class="form-group">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                      <input type="text" class="from-control border-none" name="complimentry_copies" value="<?php echo $all_data->complimentry_copies; ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="submit-block">
                      <button type="submit" id="" class="btn btn-info" >Submit</button>
                      </div>
                    </div>
                    <div class="col-md-4">
                    </div>
                      </form>
                  </div>
                  </div>
              </div>
               <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Content Allowed</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                    <div class="row">
                    <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/content_allowed"> 
                      <div class="col-md-3">
                    <div class="form-group">
                    <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                    <select class="form-control" id="cover_assign_to" name="content_allowed" >
                    <option>-select-</option>
                    <option <?php if ($all_data->content_allowed == 1) { echo "Selected"; }?> value="1">Text</option>
                    <option <?php if ($all_data->content_allowed == 2) { echo "Selected"; }?> value="2">Image</option>
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="submit-block">
                    <div class="form-group">
                    <button type="submit" id="" class="btn btn-info" >Submit</button>
                    </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                  </div>
                    </form>
                    </div>
                  </div>
              </div>
               <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Paper Type</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                    <div class="row">

                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/paper_type">
                      <div class="col-md-3"> 
                      <div class="form-group">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                      <select class="form-control" id="cover_assign_to" name="paper_type" >
                      <option>-select-</option>
                      <option <?php if ($all_data->paper_type == 1) { echo "Selected"; }?> value="1">Creamy</option>
                      <option <?php if ($all_data->paper_type == 2) { echo "Selected"; }?> value="2">White</option>
                      </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="submit-block">
                      <div class="form-group">
                      <button type="submit" id="" class="btn btn-info" >Submit</button>
                      </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                    </div>
                      </form>
                      </div>
                  </div>
              </div>
               <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Book Size</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                    <div class="row">
                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/book_size"> 
                        <div class="col-md-3">
                      <div class="form-group">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                      <select class="form-control" id="cover_assign_to" name="book_size" >
                      <option>-select-</option>
                      <option <?php if ($all_data->lead_book_size == 1) { echo "Selected"; }?> value="1">5*8</option>
                      <option <?php if ($all_data->lead_book_size == 2) { echo "Selected"; }?> value="2">6*9</option>
                      <option <?php if ($all_data->lead_book_size == 3) { echo "Selected"; }?> value="3">8*11</option>
                      </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="submit-block">
                      <div class="form-group">
                      <button type="submit" id="" class="btn btn-info" >Submit</button>
                      </div>
                      </div>
                      </div>
                      <div class="col-md-4">
                      </div> 
                      </form>
                  </div>
                  </div>
              </div>
               <div class="row">
                  <div class="col-md-2" id="otherphonelabel">
                  <div class="form-group" app-field-wrapper="otherphonenumber">
                  <label for="otherphonenumber" class="control-label">Lamination</label>
                  </div>
                  </div>
                  <div class="col-md-10" id="otherphonelabel">
                    <div class="row">
                      <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/lamination"> 
                        <div class="col-md-3">
                      <div class="form-group">
                      <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                      <select class="form-control" id="cover_assign_to" name="lamination" >
                      <option>-select-</option>
                      <option <?php if ($all_data->lamination == 1) { echo "Selected"; }?> value="1">Gloss</option>
                      <option <?php if ($all_data->lamination == 2) { echo "Selected"; }?> value="2">Matte</option>
                      </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="submit-block">
                      <div class="form-group">
                      <button type="submit" id="" class="btn btn-info" >Submit</button>
                      </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                    </div>
                      </form>
                  </div>
                  </div>
              </div>
        
              <div class="row">
                <div class="col-md-2" id="otherphonelabel">
                <div class="form-group" app-field-wrapper="otherphonenumber">
                <label for="otherphonenumber" class="control-label">Book Interior</label>
                </div>
                </div>
                <div class="col-md-10" id="otherphonelabel">
                  <div class="row">
                  <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/book_interior"> 
                    <div class="col-md-3">
                  <div class="form-group">
                  <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                  <select class="form-control" id="cover_assign_to" name="book_interior" >
                  <option>-select-</option>
                  <option <?php if ($all_data->book_interior == 1) { echo "Selected"; }?> value="1">Black</option>
                  <option <?php if ($all_data->book_interior == 2) { echo "Selected"; }?> value="2">White</option>
                  </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="submit-block">
                  <div class="form-group">
                  <button type="submit" id="" class="btn btn-info" >Submit</button>
                  </div>
                  </div>
                </div>
                <div class="col-md-4">
                </div>
                  </form>
                </div>
                </div>
            </div>



        </div>
        <div class="col-md-12">

    </div>
 

</div>
</div>
</div>
</div>
</div>
</div>

<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ISBN Details</h4>
      </div>
      <div class="modal-body ">
        <form > 
          <div class="row">
            <div class="col-md-12">
          <div id="generateImg" style="background:white; text-align: center;">
          <br>
             <p style="color:black"><b>Title:</b> <span style="text-transform:capitalize ;" id="b_title"></span></p>
             <p style="color:black;text-transform: ;"><b>Author's Name:</b> <span id="author_n" style="text-transform:capitalize ;"></span></p>
             <p style="color:black"><b>Published by:</b> BFC Publication PVT. LTD.</p>
             <p style="color:black"><b>Publisher's Address:</b> 3/229, Viram Khand, Gomti Nagar, Lucknow-226010</p>
             <p style="color:black"><b>Printer's Details:</b> Manipal Technologies</p>
             <p style="color:black"><b>Edition Details:</b> (I)</p>
             <p style="color:black"><b>ISBN:</b> N/A</p>
             <p style="color:black"><b>copyright:</b> N/A</p>
             <br>
                       </div>
           </div>
           <div id="img" style="display:none;">
            <img src="" id="newimage" class="image" />
        </div>
         </div>
         <div class="row">
          <div class="col-md-5">
          
          </div>
          <div class="col-md-2"><a class="btn btn-primary" id="download_isbn_img">Download</a></div>
          <div class="col-md-5">
         
           
          </div>
        </div>
        
        
      </form>
    </div>
    
  </div>

</div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script type="text/javascript">
const capitalize = (s) => {
  if (typeof s !== 'string') return ''
  return s.charAt(0).toUpperCase() + s.slice(1)
}


$(".apply_isbn").click(function() {
  var b_title = $('.apply_isbn').attr("data-b_title").toLowerCase();
  //var author_n = $('.apply_isbn').attr("data-author_n");
  var author_n = $('.apply_isbn').attr("data-author_n").toLowerCase();
  // alert(b_title)
  //author_n = capitalize(author_n);
  //b_title = capitalize(b_title);
  //alert(author_n)
  $('#b_title').html(b_title);
  $('#author_n').html(author_n);
  $("#myModal1").modal('show');
});
$("#download_isbn_img").click(function() {
  html2canvas($("#generateImg"), {
    onrendered: function (canvas) {
                      
                            var imgsrc = canvas.toDataURL("image/png");
                            console.log(imgsrc);
                            $("#newimage").attr('src', imgsrc);
                            var author_n = $('.apply_isbn').attr("data-author_n");

                           var link = document.createElement('a');
                            link.href = imgsrc;
                            link.download = author_n+'_isbn.jpg';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                          
 
                       
                        }
   
 
  });
});

  $("#select_assign_to").change(function() {


   var select_id = this.value;
   var hidden_id =   $("#hidden_id").val();


   
   $.ajax({

     url: "<?php echo base_url(); ?>admin/Pm_lead/select_assign_html",
     method: 'POST',
     data: {
      select_id: select_id,
      hidden_id: hidden_id,
    },
              success: function (data) // A function to be called if request succeeds
              {
               $(".select_assign").show();
               $("#assigned_fe_pr").html(data);


               

             }
           });
 });
  $(".cover_rework_or_completed_2").change(function() {
    //alert('test');
    //console.log('fff');
    var select_id = this.value;
    // alert(select_id);
    if (select_id==5) {
     
      var id = $('#hidden_id_for_cover').val();
   
      $.ajax({
     url: "<?php echo base_url(); ?>admin/Pm_lead/cover_status_for_additional",
     method: 'POST',
     data: {
      select_id: select_id,
      id: id,
    },
              success: function (data) 
              {
                $('#additional_rework_or_completed').show();
                $('.cover_rework_or_completed_2').hide();
                $('#additional_ms_status').show();
                $('#ms_status').hide();

             }
           });
      
    }
  });

$("#isbn_apply").click(function() {
    var id = $(this).attr("data-id");
      
      $.ajax({
     url: "<?php echo base_url(); ?>admin/Pm_lead/apply_isbn",
     method: 'POST',
     data: {
      id: id,
    },
              success: function (data) 
              {
            //  location.reload();

             }
           });
      
   
  });
</script>
<?php init_tail(); ?>
</body>
</html>