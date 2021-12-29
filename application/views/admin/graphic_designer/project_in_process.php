<?php init_head(); ?>

<div id="wrapper">



   <?php //init_clockinout(); ?>

   <style>

       .w-80{

           width:5rem!important;

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

                         <a href="#"  class="btn mright5 btn-info pull-left display-block" style="margin-bottom: 10px;">

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

                				   <th>S.No.</th>

                				   <th>Project Name</th>

                				   <th>Author Name</th>

                				   <th>Book Title</th>

                				   <th>Book Size</th>

                				   <th>Start Date</th>

                           <th>Download ASF</th>
                           <th>Author Img</th>


                				   <th>Upload Cover</th>

                           <th>Status</th>

                           <th>Upload Additional Cover</th>

                           <th>Status</th>

                          <!--  <th>Upload Creative</th>

                				   <th>Status</th> -->

                				</tr>

                			</thead>

                				<tbody>

                				    <?php

                          

                            $i =0;

                				    foreach($newproject_data1 as $row){

                				        $i++;

                				        $status = $row->project_status;

                				        $author = $row->lead_author_name;

                				        $booktitle = $row->lead_booktitle;

                				        $projectname= $author . "_" . $booktitle;

                				        $date= $row->takeup_date;

                				    ?>

                				    

                				<tr>

                				   <td><?php echo $i;?></td>

                				   <td><?php echo $projectname;?></td>

                				   <td><?php echo $author;?></td>

                				   <td><?php echo $booktitle;?></td>

                				   <td>

                         <?php if ($row->lead_book_size==1) {

                              echo '5*8';

                            }

                            else if($row->lead_book_size==2) {

                              echo '6*9';

                            } else if($row->lead_book_size==3) {

                              echo '9*11';

                            } ?>    

                           </td>

                           <td><?php echo $date; ?></td>

                           <td>

                             <a class="btn btn-primary btn-xs" <?php if ($row->asf_pdf_data) { ?> href="<?=base_url('assets/asf_authorMail/'.$row->asf_pdf_data)?>" target="_blank" <?php } ?>>Download ASF</a>

                           </td>
                          <td>
                            <?php $data_a = $this->db->get_where('chorus_asf',array('lead_id'=>$row->id))->row();
                            if ($data_a->profile_pic_url) { ?>
                              <a class="btn btn-primary btn-xs" <?php if ($data_a->profile_pic_url) { ?> href="<?=$data_a->profile_pic_url?>" target="_blank" <?php } ?>>Author Img</a>
                           <?php }else{?>
                          <a class="btn btn-danger btn-xs"  >No Author Img</a>
                         <?php  } ?>
                         
                          </td>
                           <td>

                            <!-- upload Cover -->

                            <?php if ($row->project_status_gd == 5) {
                             echo '<button class="btn btn-success">Uploaded</button>';
                            }else{
                                 if($row->gd_cover== '') {?>

                             <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_cover" enctype="multipart/form-data"> 

                                  <div class="form-group">

                                    <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $row->lead_author_name; ?>"/>
                                        <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $booktitle; ?>"/>
                                        <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>

                                    <input type="file" class="from-control border-none" name="file" required>

                                    <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>

                                  </div>

                              </form>

                           <?php }else if($row->gd_work_status==2){ ?>
                            <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_cover" enctype="multipart/form-data"> 

                                  <div class="form-group">

                                    <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="rework_id" id="rework_id" class="hidden_id" value="<?php echo $row->gd_work_status; ?>"/>
                                    <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $row->lead_author_name; ?>"/>
                                        <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $booktitle; ?>"/>
                                        <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>

                                    <input type="file" class="from-control border-none" name="file" required>

                                    <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>

                                  </div>

                              </form>
                          <?php }else{
                              echo '<button class="btn btn-success">Uploaded</button>';

                            }
                            }

                          ?> 

                           </td>

                           <td>

                             <?php if($row->gd_work_status==1){

                              echo '<span class="btn-default btn-icon">Under Review</span>';

                              } else if($row->gd_work_status==2 || $row->gd_work_status==4) {

                                echo '<span class="btn-default btn-icon">Re-work</span>';

                              }

                              else if($row->gd_work_status==3){

                                echo '<span class="btn-default btn-icon">Approved</span>';

                              }

                              ?>

                           </td>

                           

                           <td>

                            <!-- upload Additional Cover -->

                             <?php if($row->project_status_gd == 5 ) {?>



                              <?php if($row->gd_ad_work_status < 3) {?>

                             <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_additional_cover" enctype="multipart/form-data"> 

                                  <div class="form-group">

                                    <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $row->lead_author_name; ?>"/>
                                        <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $booktitle; ?>"/>
                                        <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>

                                    <input type="file" class="from-control border-none" name="file" required>

                                    <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>

                                  </div>

                              </form>

                              <?php } else if($row->gd_ad_work_status == 1) {

                              echo '<button class="btn btn-success">Uploaded</button>';

                            }else{
                              echo '<button class="btn btn-success">Uploaded</button>';
                            }?>  

                            

                            <?php } ?>

                           </td>

                         

                           <td>

                             <?php 

                              if($row->gd_ad_work_status==1){

                                echo '<span class="btn-default btn-icon">Under Review</span>';

                              }

                              else if($row->gd_ad_work_status==2){

                                echo '<span class="btn-default btn-icon">Re-work</span>';

                              }

                              else if($row->gd_ad_work_status==3){

                                echo '<span class="btn-default btn-icon">Approved</span>';

                              } ?>

                           </td>

                           <!-- <td>

                            <?php if($row->gd_ad_work_status == 3 ) {?>

                              <?php if($row->gd_creative == '' ) {?>

                             <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_creative" enctype="multipart/form-data"> 

                                  <div class="form-group">

                                    <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $row->lead_author_name; ?>"/>
                                        <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $booktitle; ?>"/>
                                        <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>

                                    <input type="file" class="from-control border-none" name="file" required>

                                    <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>

                                  </div>

                              </form>

                               <?php }else{

                              echo '<button class="btn btn-success">Uploaded</button>';

                            } ?> 

                            <?php } ?>

                           </td>

                				   <td>

                              <?php 

                              if($row->lead_gd_creative_status==1){

                                echo '<span class="btn-default btn-icon">Under Review</span>';

                              }

                              else if($row->lead_gd_creative_status==2){

                                echo '<span class="btn-default btn-icon">Rework</span>';

                              }

                              else if($row->lead_gd_creative_status==3){

                                echo '<span class="btn-default btn-icon">Approved</span>';

                              } ?>    

                           </td> -->

                				</tr>

                			<?php } ?>

                

                			</tbody>

            			</table>

		            	</div>

                   </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php init_tail(); ?>



</body>



</html>