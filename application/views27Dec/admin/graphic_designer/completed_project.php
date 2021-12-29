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

                           <th>Total Cover</th>

                           <th>Start Date</th>

                           <th>End Date</th>

                           <th>ISBN Paperback</th>

                           <th>MRP</th>

                           <th>Book Cover JPG</th>

                           <th>Book Cover Front PDF</th>

                				   <th>Book Cover Back PDF</th>
                           <th>Upload Creative</th>

                				   <th>Status</th>

                				</tr>

                			</thead>

                				<tbody>

                				    <?php

                            

                				    // print_r($newproject_data);

                				    foreach($completed_data as $row){

                				        $i++;

                				        $status = $row->project_status;

                				        $author = $row->lead_author_name;

                				        $booktitle = $row->lead_booktitle;

                				        $projectname= $author . "_" . $booktitle;

                                $totalcover = $row->lead_gd_total_cover;

                                $date= $row->takeup_date;

                                $enddate = $row->lead_upload_cover_date;

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

                           <td><?php echo $totalcover;?></td>

                           <td><?php echo $date;?></td>

                           <td><?php echo $enddate; ?></td>

                           <td><?php echo $row->lead_isbn_paperback;?></td>

                           <td><?php echo $row->lead_final_mrp_suggestion;?></td>

                           <td>

                            <?php if($row->lead_gd_final_cover== '') {?>

                              <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_book_cover" enctype="multipart/form-data"> 

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

                              echo '<button class="btn btn-success" >Uploaded</button>';

                            } ?>

                           </td>

                           <td>

                            <?php if($row->lead_gd_final_front== '') {?>

                              <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_cover_front" enctype="multipart/form-data"> 

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

                              echo '<button class="btn btn-success" >Uploaded</button>';

                            } ?>

                           </td>

                           <td>

                            <?php if($row->lead_gd_final_back== '') {?>

                             <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_cover_back" enctype="multipart/form-data"> 

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

                              echo '<button class="btn btn-success" >Uploaded</button>';

                            } ?>

                           </td>
                           <td>
                              <?php if($row->gd_creative == '' ) {?>

                             <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_creative" enctype="multipart/form-data"> 

                                  <div class="form-group">

                                    <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>

                                    <input type="file" class="from-control border-none" name="file" required>

                                    <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>

                                  </div>

                              </form>

                               <?php }else{

                              echo '<button class="btn btn-success">Uploaded</button>';

                            } ?> 
                           </td>

                				   <td>Completed</td>

                				</tr>

                				<?php }

				                ?>

                

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