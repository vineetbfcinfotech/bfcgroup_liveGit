
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
        <?php  // print_r($all_data); 
       // echo $all_data->lead_sub_service;
       $sub_service = explode(", ", $all_data->lead_sub_service); 
       $service = explode(", ", $all_data->lead_service); 
       //print_r($sub_service);
        ?>
     </div>
     <div class="clearfix"></div>
     <hr class="hr-panel-heading" />
     <table class="table dt-table scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
       <thead>
        <tr role="row">
          <th>Author Name</th>
         <th>View/Download ASF</th>
         <th>Upload MS(doc, docx,pdf)</th>
         <th>ISBN</th>
         <?php 
         foreach ($sub_service as $key => $value) {
          $sub_name = $this->db->get_where('tblpackagesubservices',array('id'=>$value))->row();
          if ($sub_name->subservice_name  == 'Format Editing (Upto 100 Pages)' || $sub_name->subservice_name  == 'Format Editing') {?>
           <th>Format Editing</th>
          <?php }
          if ($sub_name->subservice_name  == 'Proofreading' || $sub_name->subservice_name == 'Proof Reading') {?>
          <th>Proof Reading</th>
          <?php }
          if ($sub_name->subservice_name  == 'Cover Design - Premium' || $sub_name->subservice_name  == 'Cover Design') {?>
          <th>Cover</th>
          <?php }
         }
         foreach ($service as $key => $value_service) {
          $service_name = $this->db->get_where('tblpackageservices',array('id'=>$value_service))->row();
          if ($service_name->service_name == 'Marketing your Masterpiece') { ?>
           <th>Marketing</th>
         <?php }
        }
          ?>
         
         <th>MRP Approved by author</th>
         <th>Total No. of pages</th>
         <th>Total No. of copies</th>
         <th>Complimentry Copies</th>
         <th>Content Allowed</th>
         <th>Paper Type</th>
         <th>Book Size</th>
         <th>Lamination</th>
         <th>Book Interior</th>

       </tr>
     </thead>
     <tbody>



      <tr>
        <td><?php echo $all_data->lead_author_name; ?></td>
       <td><a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">View ASF</a></td>

       <td>
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
    

  </td>
  <td><form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/upload_isbn" > 
    <div class="form-group">
     <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
     <label>eBook Number</label>
     <input type="text" class="from-control border-none" name="ebook" value="<?php echo $all_data->lead_isbn_ebook; ?>">
   </div>
   <div class="form-group">
    <label>Paperback Number</label>
    <input type="text" class="from-control border-none" name="paperback" value="<?php echo $all_data->lead_isbn_paperback; ?>">
  </div>
  <div class="submit-block">
   <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Submit</button>
 </div>

</form>

</td>
<?php 
         foreach ($sub_service as $key => $value) {
          $sub_name = $this->db->get_where('tblpackagesubservices',array('id'=>$value))->row();
          if ($sub_name->subservice_name  == 'Format Editing (Upto 100 Pages)' || $sub_name->subservice_name  == 'Format Editing') {?>
<td>
  <?php if ($all_data->lead_raw_ms=='') { ?>
    <a class="btn btn-default btn-xs" disabled  class="MainNavText" >Assigned To</a>
  <?php }else{ ?>

    <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/assigned_fe_pr"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <input type="hidden" name="hidden_id_for_check" id="hidden_id_for_check" class="hidden_id" value="1"/>
   <label for="sel1">Format editing Assign To</label>
    <select class="form-control" id="format_assign_to" name="format_assign_to" <?php if ($all_data->project_assign_to) { echo "disabled"; }?>>
    <option>-select-</option>
    <option <?php if ($all_data->project_assign_to_fe == 85) { echo "Selected"; }?> value='85'>Manish(<?= $manish_remaing_pages;?>)</option>
    <option <?php if ($all_data->project_assign_to_fe == 81) { echo "Selected"; }?> value='81'>Amrendra(<?=$amrendra_remaing_pages;?>) </option>
    <option <?php if ($all_data->project_assign_to_fe == 82) { echo "Selected"; }?> value='82'>Gaurav(<?=$gourav_remaing_pages;?>)</option>

  </select> 
 
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" <?php if ($all_data->project_assign_to) { echo "disabled"; }?> >Assign</button>
 </div>
</div>
</form>

  <?php } ?>
  <br><br>
  <?php if ($all_data->project_assign_to) { ?>
    
    <?php if ($all_data->project_status == 9 || $all_data->project_status == 10 || $all_data->project_status == 11) { ?>
      <a class="btn btn-info" >
       Project assigned to Format Editing <b>(<?php if ($all_data->project_assign_to == 85) { ?>Manish<?php }elseif($all_data->project_assign_to == 81){?>Amrendra<?php }elseif($all_data->project_assign_to == 82){?>Gaurav<?php } ?>) </b>
       </a><?php } ?>

     <?php }
     
     if ($all_data->lead_fe_ms_file) { ?>
       <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/<?php echo $all_data->lead_fe_ms_file ?>">Download MS</a>

     <?php if ($all_data->lead_fe_ms_ebook) { ?>
       <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/ebook/<?php echo $all_data->lead_fe_ms_ebook ?>">Download ebook MS</a>
     <?php } 
     if ($all_data->lead_fe_ms_paperback){ ?>
         <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/paperback/<?php echo $all_data->lead_fe_ms_paperback ?>">Download Paperback MS</a>
    <?php }
    if ($all_data->lead_fe_ms_doc) { ?>
       <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/doc/<?php echo $all_data->lead_fe_ms_doc ?>">Download Doc MS</a>
    <?php } ?> 
      
       

       <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/format_editor_ms"> 
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
      <div class="submit-block">
        <div class="form-group">
         <button type="submit" id="" class="btn btn-info" <?php if ($all_data->lead_fe_ms_rework == 2) { echo "disabled"; }?>>Submit</button>
       </div>
     </div>
   </form>

 <?php } ?>

</td> <?php }   if ($sub_name->subservice_name  == 'Proofreading' || $sub_name->subservice_name == 'Proof Reading') {?>
<td>
 <?php if ($all_data->lead_raw_ms=='') { ?>
  <a class="btn btn-default btn-xs" disabled  class="MainNavText" >Assigned To</a>
<?php }else{ ?>
  <!-- <a class="btn btn-success btn-xs"  data-target="#myModal1" data-toggle="modal" class="MainNavText" id="MainNavHelp"  href="#myModal1">Assigned To</a> -->
   <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/assigned_fe_pr"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <input type="hidden" name="hidden_id_for_check" id="hidden_id_for_check" class="hidden_id" value="2"/>
   <label for="sel1">proof reader Assign To</label>
    <select class="form-control" id="format_assign_to" name="format_assign_to" <?php if ($all_data->project_assign_to ==90 || $all_data->project_assign_to==80) { echo "disabled"; }?>>
    <option>-select-</option>
    <option <?php if ($all_data->project_assign_to == 90) { echo "Selected"; }?> value='90'>Ravindra(<?= $ravindra_remaing_pages;?>)</option>
    <option <?php if ($all_data->project_assign_to == 80) { echo "Selected"; }?> value='80'>Varuna(<?=$varuna_remaing_pages;?>)</option>

  </select> 
 
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" <?php if ($all_data->project_assign_to ==90 || $all_data->project_assign_to==80) { echo "disabled"; }?> >Assign</button>
 </div>
</div>
</form>

<?php } ?>
<br><br>
<?php if ($all_data->project_assign_to) { ?>
  
  <?php if ($all_data->project_status == 5 || $all_data->project_status == 6 || $all_data->project_status == 7) { ?><a class="btn btn-success btn-xs" >
    Proof Reading assign to <?php if ($all_data->project_assign_to == 90) { ?>Ravindra<?php }elseif($all_data->project_assign_to == 80){?>Varuna<?php }?></a>
  <?php } ?>

<?php }
if ($all_data->lead_pr_ms_file) { ?>
 <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/menuscript/proof_reader_ms/<?php echo $all_data->lead_pr_ms_file ?>">Download Ms</a>

 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/proof_reader_ms"> 
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
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" <?php if ($all_data->lead_pr_ms_rework == 2) { echo "disabled"; }?>>Submit</button>
 </div>
</div>
</form>

<?php }
?>

</td><?php } if ($sub_name->subservice_name  == 'Cover Design - Premium' || $sub_name->subservice_name  == 'Cover Design') { ?>
<td>
 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/cover_assigned_to"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <label for="sel1">Cover Assign To</label>
    <select class="form-control" id="cover_assign_to" name="cover_assign_to" <?php if ($all_data->lead_asf_assign_gd) { echo "disabled"; }?>>
    <option>-select-</option>
    <option <?php if ($all_data->lead_asf_assign_gd == 84) { echo "Selected"; }?> value="84">Affan(<?= $affan_remaing_pages;?>)</option>
    <option <?php if ($all_data->lead_asf_assign_gd == 83) { echo "Selected"; }?> value="83">Surabhi(<?= $surabhi_remaing_pages;?>)</option>
  </select> 
 
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" <?php if ($all_data->lead_asf_assign_gd) { echo "disabled"; }?> >Assign</button>
 </div>
</div>
</form>
 <?php 
if ($all_data->gd_cover) { ?>
 <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/cover/gd_cover/<?php echo $all_data->gd_cover ?>">Download Cover</a>

 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/cover_assign_work"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id_for_cover" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <input type="hidden" name="staff_cover_id" id="staff_cover_id" class="hidden_id" value="<?php echo $all_data->lead_asf_assign_gd; ?>"/>
   <label for="sel1" id="ms_status" <?php if ($all_data->gd_work_status == 4) { ?>style="display: none;" <?php }else{ ?>
style="display: block;"
 <?php } ?>>MS Status</label>
 <label for="sel1" id="additional_ms_status" <?php if ($all_data->gd_work_status == 4) { ?>style="display: block;" <?php }else{ ?>
style="display: none;"
 <?php } ?>>Additional MS Status</label>

   <select class="form-control" id="cover_rework_or_completed" name="rework_or_completed" <?php if ($all_data->gd_work_status == 3) { echo "disabled"; }?><?php if ($all_data->gd_work_status == 4) { ?>style="display: none;" <?php }else{ ?>
style="display: block;"
 <?php } ?>>>
    <option>-select-</option>
    <option <?php if ($all_data->gd_work_status == 3) { echo "Selected"; }?> value="3">Approved</option>
    <option <?php if ($all_data->gd_work_status == 4) { echo "Selected"; }?> value="4">Additional cover</option>
    <option <?php if ($all_data->gd_work_status == 2) { echo "Selected"; }?> value="2">Rework</option>
  </select>
  <select class="form-control" id="additional_rework_or_completed" name="additional_rework_or_completed" <?php if ($all_data->gd_ad_work_status == 3) { echo "disabled"; }?> <?php if ($all_data->gd_work_status == 4) { ?>style="display: block;" <?php }else{ ?>
style="display: none;"
 <?php } ?>>
    <option>-select-</option>
    <option <?php if ($all_data->gd_ad_work_status == 3) { echo "Selected"; }?> value="3">Additional Approved</option>
   
    <option <?php if ($all_data->gd_ad_work_status == 2) { echo "Selected"; }?> value="2">Additional Rework</option>
  </select>
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" <?php if ($all_data->gd_ad_work_status == 3 || $all_data->gd_work_status == 3) { echo "disabled"; }?>>Submit</button>
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

</td> <?php } }
        foreach ($service as $key => $value_service) {
          $service_name = $this->db->get_where('tblpackageservices',array('id'=>$value_service))->row();
          if ($service_name->service_name == 'Marketing your Masterpiece') { ?>
            <td>
             <?php if ($all_data->lead_raw_ms=='') { ?>
    <a class="btn btn-default btn-xs" disabled  class="MainNavText" >Assigned To</a>
  <?php }else{ ?>
    <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/marketing_assigned_to"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <label for="sel1">DM Assign To</label>
    <select class="form-control" id="market_assign" name="market_assign" <?php if ($all_data->lead_asf_assign_marketing) { echo "disabled"; }?>>
    <option>-select-</option>
    <option <?php if ($all_data->lead_asf_assign_marketing == 86) { echo "Selected"; }?> value="86">Nitesh</option>
    <option <?php if ($all_data->lead_asf_assign_marketing == 87) { echo "Selected"; }?> value="87">Amulya</option>
  </select> 
 
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" <?php if ($all_data->lead_asf_assign_marketing) { echo "disabled"; }?> >Assign</button>
 </div>
</div>
</form>

  <?php 
      $all_dm_report = $this->db->get_where('upload_dm_report_table',array('lead_id'=>$all_data->id))->result();
      if (!empty($all_dm_report)) {
        foreach ($all_dm_report as $key => $all_dm_report) { ?>
           <a class="btn btn-info" target="_blank" href="<?php echo base_url(); ?>assets/digital_marketing/report/<?php echo $all_dm_report->upload_report ?>"><?php  echo $all_dm_report->sub_service_name; ?></a><br>
       <?php  }
      }
   } ?>
  </td>
         <?php }
        } ?>
<td>
  <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/mrp_approved" > 
   <div class="form-group">
    <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
    <label>MRP Approved by author</label>
    <input type="text" class="from-control border-none" name="mrp_approved_by_author" value="<?php echo $all_data->mrp_approved_by_author; ?>">
  </div>
  <div class="submit-block">
   <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Submit</button>
 </div>
</form>

</td>
<td>
 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/total_no_of_pages" > 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <label>Total No. of pages</label>
   <input type="text" class="from-control border-none" name="total_no_of_pages" value="<?php echo $all_data->lead_book_pages; ?>">
 </div>
 <div class="submit-block">
  <button type="submit" id="" class="btn btn-info" >Submit</button>
</div>
</form>

</td>
<td>
 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/total_no_of_copy" > 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <label>Total No. of copies</label>
   <input type="text" class="from-control border-none" name="total_no_of_copy" value="<?php echo $all_data->total_number_of_copies; ?>">
 </div>
 <div class="submit-block">
  <button type="submit" id="" class="btn btn-info" >Submit</button>
</div>
</form>

</td>
<td>
  <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/complimentry_copies" > 
   <div class="form-group">
    <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
    <label>Complimentry Copies</label>
    <input type="text" class="from-control border-none" name="complimentry_copies" value="<?php echo $all_data->complimentry_copies; ?>">
  </div>
  <div class="submit-block">
   <button type="submit" id="" class="btn btn-info" >Submit</button>
 </div>
</form>
</td>
<td>
 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/content_allowed"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <label>ContentAllowed</label>
   <select class="form-control" id="cover_assign_to" name="content_allowed" >
    <option>-select-</option>
    <option <?php if ($all_data->content_allowed == 1) { echo "Selected"; }?> value="1">Text</option>
    <option <?php if ($all_data->content_allowed == 2) { echo "Selected"; }?> value="2">Image</option>
  </select>
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" >Submit</button>
 </div>
</div>
</form>
</td>
<td>
  <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/paper_type"> 
   <div class="form-group">
    <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
    <label>Paper Type</label>
    <select class="form-control" id="cover_assign_to" name="paper_type" >
     <option>-select-</option>
     <option <?php if ($all_data->paper_type == 1) { echo "Selected"; }?> value="1">Creamy</option>
     <option <?php if ($all_data->paper_type == 2) { echo "Selected"; }?> value="2">White</option>
   </select>
 </div>
 <div class="submit-block">
   <div class="form-group">
    <button type="submit" id="" class="btn btn-info" >Submit</button>
  </div>
</div>
</form>
</td>
<td>
 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/book_size"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <label>Book Size</label>
   <select class="form-control" id="cover_assign_to" name="book_size" >
    <option>-select-</option>
    <option <?php if ($all_data->lead_book_size == 1) { echo "Selected"; }?> value="1">5*8</option>
    <option <?php if ($all_data->lead_book_size == 2) { echo "Selected"; }?> value="2">6*9</option>
    <option <?php if ($all_data->lead_book_size == 3) { echo "Selected"; }?> value="3">8*11</option>
  </select>
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" >Submit</button>
 </div>
</div> 
</form>
</td>
<td>
 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/lamination"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <label>Lamination</label>
   <select class="form-control" id="cover_assign_to" name="lamination" >
    <option>-select-</option>
    <option <?php if ($all_data->lamination == 1) { echo "Selected"; }?> value="1">Gloss</option>
    <option <?php if ($all_data->lamination == 2) { echo "Selected"; }?> value="2">Matte</option>
  </select>
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" >Submit</button>
 </div>
</div>
</form>
</td>
<td>
 <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/book_interior"> 
  <div class="form-group">
   <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
   <label>Book Interior</label>
   <select class="form-control" id="cover_assign_to" name="book_interior" >
    <option>-select-</option>
    <option <?php if ($all_data->book_interior == 1) { echo "Selected"; }?> value="1">Black</option>
    <option <?php if ($all_data->book_interior == 2) { echo "Selected"; }?> value="2">White</option>
  </select>
</div>
<div class="submit-block">
  <div class="form-group">
   <button type="submit" id="" class="btn btn-info" >Submit</button>
 </div>
</div>
</form>
</td>


</tr>
</tbody>
</table>
<div id="myModal1" class="modal fade" role="dialog">
 <div class="modal-dialog modal-md">
   <!-- Modal content-->
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title">Assigned To</h4>
   </div>
   <div class="modal-body ">
     <form  method="post" action="<?php echo base_url(); ?>admin/Pm_lead/assigned_fe_pr" > 
      <div class="row">
       <div class="col-md-6">
         <div class="form-group">
          <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>

          <select class="form-control" id="select_assign_to" name="select_fe_pr" >
           <option>-select-</option>
           <option value="1">Format Editing</option>
           <option value="2">Proof Reading</option>
         </select>
       </div>
     </div>
     <div class="col-md-6">
       <div class="select_assign" style="display: none;">
        <div class="form-group">
         <select class="form-control" id="assigned_fe_pr" name="assigned_to_fe_pr">

         </select>
       </div>
     </div>
   </div>
 </div>

 
 <div class="assinged-block">
  <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Submit</button>
</div>       

</form>
</div>

</div>

</div>
</div>



</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">

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
  $("#cover_rework_or_completed").change(function() {
    // alert('test');
    var select_id = this.value;
    // alert(select_id);
    if (select_id==4) {
     
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
                $('#cover_rework_or_completed').hide();
                $('#additional_ms_status').show();
                $('#ms_status').hide();

             }
           });
      
    }
  });

</script>
<?php init_tail(); ?>
</body>
</html>