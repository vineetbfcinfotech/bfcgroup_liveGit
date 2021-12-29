<?php init_head(); ?>
<div id="wrapper">

   <?php //init_clockinout(); ?>
   <style>
       .w-80{
           width:80%!important;
           margin-right:.5rem;
       }
       .border-none{
      border:0px!important;
    }
   </style>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                   <div class="panel-body">
                      <div class="_buttons">
                         <a href="<?php echo base_url();?>admin"  class="btn mright5 btn-info pull-left display-block" style="margin-bottom: 10px;">
                         Back  </a>
                      </div>
                      <div class="clearfix"></div>
    				  <div id="loading-image" style="display: none; text-align: center;">
    					<img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
    				  </div>
                     <div class="table-responsive">
            		    <table class="table dt-table table-responsive scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                			<thead>
                				<tr role="row">
                           <!-- <th>S.No.</th> -->
                           <th>WTD</th>
                           <th>Status</th>
                           <th></th>
                           <!-- <th></th> -->
                           <!-- <th></th> -->
                           <th></th>
                          
                				</tr>
                			</thead>
                				<tbody>
                          
                      <?php  $i=0;
                       foreach ($sub_service as $key => $values) {
                        // print_r($value);
                              $this->db->select('*');
                              $this->db->from('tblpackagesubservices');
                              $this->db->where('id', $values); 
                              $where = '(serviceid=29  or serviceid = 4 or serviceid = 13)';
                              $this->db->where($where);
                              $result = $this->db->get(); 
                               $result = $result->result();
                               // echo count($result);
                               // echo $this->db->last_query();
                             
                               foreach ($result as $key => $value) {  
                               $i++;  ?>
                              
                                <tr>
                           <td><?= $value->subservice_name;?></td>
                          <?php $check_report = $this->db->get_where('upload_dm_report_table',array('sub_service_id'=>$value->id))->row();
                          
                             if ($check_report=='') { ?>
                              <td>Pending</td>
                             <?php }else{ ?>
                              <td>Completed</td>
                              
                            <?php } ?>
                           
                       
                               
                           <?php
                            $this->db->select('subservice_name');
                              $this->db->from('tblpackagesubservices');
                              $this->db->where('id', $values); 
                              $result = $this->db->get(); 
                               $results_data = $result->row();
                               // print_r($results_data);s
                             if ($value->subservice_name == 'Cover Design - Basic' || $value->subservice_name == 'Cover Design - Premium' || $value->subservice_name == 'Cover Design') { ?>
                            <td><a class="btn btn-info">Creative Download</a></td>
                          <?php } ?>
                          <!--   <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/menuscript/format_editor_ms/<?php echo $all_data->final_ms_uploaded ?>">Download Manuscript</a></td> -->
                            <td><?php
                           $check_report = $this->db->get_where('upload_dm_report_table',array('sub_service_id'=>$value->id))->row();
                          
                             if ($check_report=='') {

                              $projectname= $all_data->lead_author_name . "_" . $all_data->lead_booktitle; ?>
                                  <form method="post" action="<?php echo base_url(); ?>admin/Digital_marketing/upload_report" enctype="multipart/form-data"> 

                               <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $all_data->lead_author_name; ?>"/>
                              <input type="hidden" name="user_id" id="user_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                              <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $all_data->lead_booktitle; ?>"/>
                              <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>
                                <div class="form-group">
                                 <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                                 <input type="hidden" name="sub_service_id" id="sub_service_id" class="sub_service_id" value="<?php echo $value->id; ?>"/>
                                 <input type="hidden" name="sub_service_name" id="sub_service_name" class="sub_service_name" value="<?php echo $value->subservice_name; ?>"/>
                                 <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                                 <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Report Upload</button>
                               </div>
                             </form>
                              
                           <?php }else{ $i--; ?>

                             <a class="btn btn-prime">Report Uploaded</a>
                          
                          <?php  } ?></td>
                            
                        </tr>
                              <?php }
                          }
                          //echo $i;
                          $data_a= array(
                           'total_dm_report_counting'=>$i
                         );
                          $this->db->where('id',$id);
                          $this->db->update('tblleads',$data_a);

                          ?>
                           























                         
                				<!-- <tr>
                           <td>Facebook Marketing</td>
                           <td>Pending</td>
                           <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download ASF</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Creative Download</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download Manuscript</a></td>
                            <td><?php if (empty($all_data->dm_facebook_report)) { ?>
         
                               <form method="post" action="<?php echo base_url(); ?>admin/Digital_marketing/upload_facebook_report" enctype="multipart/form-data"> 
                                <div class="form-group">
                                 <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                                 <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                                 <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Report Upload</button>
                               </div>
                             </form>
                           <?php }else{ ?>
                            <a class="btn btn-prime">Report Uploaded</a>
                          <?php  } ?></td>
                            
                				</tr>
                        <tr>
                           <td>Email marketing</td>
                           <td>Pending</td>
                           <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download ASF</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Creative Download</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download Manuscript</a></td>
                            <td><?php if (empty($all_data->dm_facebook_report)) { ?>
         
                               <form method="post" action="<?php echo base_url(); ?>admin/Digital_marketing/upload_facebook_report" enctype="multipart/form-data"> 
                                <div class="form-group">
                                 <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                                 <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                                 <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Report Upload</button>
                               </div>
                             </form>
                           <?php }else{ ?>
                            <a class="btn btn-prime">Report Uploaded</a>
                          <?php  } ?></td>
                            
                        </tr>
                        <tr>
                           <td>Whats app marketing</td>
                           <td>Pending</td>
                           <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download ASF</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Creative Download</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download Manuscript</a></td>
                            <td><?php if (empty($all_data->dm_facebook_report)) { ?>
         
                               <form method="post" action="<?php echo base_url(); ?>admin/Digital_marketing/upload_facebook_report" enctype="multipart/form-data"> 
                                <div class="form-group">
                                 <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                                 <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                                 <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Report Upload</button>
                               </div>
                             </form>
                           <?php }else{ ?>
                            <a class="btn btn-prime">Report Uploaded</a>
                          <?php  } ?></td>
                            
                        </tr>
                        <tr>
                           <td>Amazon ads</td>
                           <td>Pending</td>
                           <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download ASF</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Creative Download</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download Manuscript</a></td>
                            <td><?php if (empty($all_data->dm_facebook_report)) { ?>
         
                               <form method="post" action="<?php echo base_url(); ?>admin/Digital_marketing/upload_facebook_report" enctype="multipart/form-data"> 
                                <div class="form-group">
                                 <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                                 <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                                 <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Report Upload</button>
                               </div>
                             </form>
                           <?php }else{ ?>
                            <a class="btn btn-prime">Report Uploaded</a>
                          <?php  } ?></td>
                            
                        </tr>
                        <tr>
                           <td>KDP</td>
                           <td>Pending</td>
                           <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download ASF</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Creative Download</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download Manuscript</a></td>
                            <td><?php if (empty($all_data->dm_facebook_report)) { ?>
         
                               <form method="post" action="<?php echo base_url(); ?>admin/Digital_marketing/upload_facebook_report" enctype="multipart/form-data"> 
                                <div class="form-group">
                                 <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                                 <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                                 <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Report Upload</button>
                               </div>
                             </form>
                           <?php }else{ ?>
                            <a class="btn btn-prime">Report Uploaded</a>
                          <?php  } ?></td>
                            
                        </tr>
                        <tr>
                           <td>Amazon </td>
                           <td>Pending</td>
                           <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download ASF</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Creative Download</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download Manuscript</a></td>
                            <td><?php if (empty($all_data->dm_facebook_report)) { ?>
         
                               <form method="post" action="<?php echo base_url(); ?>admin/Digital_marketing/upload_facebook_report" enctype="multipart/form-data"> 
                                <div class="form-group">
                                 <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                                 <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                                 <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Report Upload</button>
                               </div>
                             </form>
                           <?php }else{ ?>
                            <a class="btn btn-prime">Report Uploaded</a>
                          <?php  } ?></td>
                            
                        </tr>
                         <tr>
                           <td>Flipkart</td>
                           <td>Pending</td>
                           <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download ASF</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Creative Download</a></td>
                            <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $all_data->asf_pdf_data ?>">Download Manuscript</a></td>
                            <td><?php if (empty($all_data->dm_facebook_report)) { ?>
         
                               <form method="post" action="<?php echo base_url(); ?>admin/Digital_marketing/upload_facebook_report" enctype="multipart/form-data"> 
                                <div class="form-group">
                                 <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $all_data->id; ?>"/>
                                 <input type="file" class="from-control border-none" name="file" style="border: 0px solid #bfcbd9;">
                                 <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Report Upload</button>
                               </div>
                             </form>
                           <?php }else{ ?>
                            <a class="btn btn-prime">Report Uploaded</a>
                          <?php  } ?></td>
                            
                        </tr> -->
                 
                
                			</tbody>
            			</table>
		            	</div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>


  $(document).on('click', '.take', function() {
      var success = "success";
          var project_id   = this.id;

            $.ajax({

      type: "POST",

      url: "<?php echo admin_url('Digital_marketing/changeProjectStatus'); ?>",

      data: {'project_id': project_id},

      dataType: "html",

      success: function(data){
          alert_float(success, "Project Takeup Successfully");
          window.location.replace("<?php echo admin_url('Digital_marketing/inprogress_projects'); ?>");
   
      }

    });
  }); 
</script>

<?php init_tail(); ?>

</body>

</html>