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
                				   <th>S.No.</th>
                				   <th>Project Name</th>
                				   <th>Author Name</th>
                           <th>Book Title</th>
                           <th>Book Size</th>
                           <th>Total Pages</th>
                           <th>Start Date</th>
                           <th>End Date</th>
                           <th>ISBN Paperback</th>
                           <th>ISBN eBook</th>
                           <th>MS eBook PDF</th>
                           <th>MS Paperback PDF</th>
                				   <th>MS Doc File</th>
                				   <th>Status</th>
                				</tr>
                			</thead>
                				<tbody>
                				    <?php
                           
                				    foreach($completed_data as $row){
                				        $i++;
                				        $status = $row->project_status;
                				        $author = $row->lead_author_name;
                				        $booktitle = $row->lead_booktitle;
                				        $projectname= $author . "_" . $booktitle;
                                $totalpage = $row->lead_book_pages;
                                $date= $row->takeup_date;
                                $enddate = $row->lead_upload_ms_date;
                                $ebookpdf = $row->lead_fe_ms_ebook;
                                $paperback = $row->lead_fe_ms_paperback;
                                $doc = $row->lead_fe_ms_doc;
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
                           <td><?php echo $totalpage;?></td>
                           <td><?php echo $date;?></td>
                           <td><?php echo $enddate; ?></td>
                           <td><?php echo $row->lead_isbn_paperback;?></td>
                           <td><?php echo $row->lead_isbn_ebook;?></td>
                           <td>
                            <?php if($ebookpdf== '') {?>
                              <form method="post" action="<?php echo base_url(); ?>admin/format_editor/upload_ms_ebookpdf" enctype="multipart/form-data"> 
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
                              echo '<button type="submit" id="submit_ms" class="btn btn-success" >Uploaded</button>';
                            } ?>
                           </td>
                           <td>
                            <?php if($paperback== '') {?>
                              <form method="post" action="<?php echo base_url(); ?>admin/format_editor/upload_ms_paperback" enctype="multipart/form-data"> 
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
                              echo '<button type="submit" id="submit_ms" class="btn btn-success" >Uploaded</button>';
                            } ?>
                           </td>
                           <td>
                            <?php if($doc== '') {?>
                             <form method="post" action="<?php echo base_url(); ?>admin/format_editor/upload_ms_doc" enctype="multipart/form-data"> 
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
                              echo '<button type="submit" id="submit_ms" class="btn btn-success" >Uploaded</button>';
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