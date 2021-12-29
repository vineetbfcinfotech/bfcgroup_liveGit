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
                				   <th>Completed Pages</th>
                           <th>working Pages</th>
                				   <th>Balance pages</th>
                				   <th>Start Date</th>
                				   <th>TAT</th>
                				   <th>Status of Deadline</th>
                				   <th>Manuscript Download</th>
                				   <th>Manuscript Status</th>
                				    <th>Manuscript Upload</th>
                           <th></th>
                				</tr>
                			</thead>
                				<tbody>
                				    <?php
                				    foreach($newproject_data1 as $row){
                				        $i++;
                				        $status = $row->project_status;
                				        $author = $row->lead_author_name;
                				        $booktitle = $row->lead_booktitle;
                				        $projectname= $author . "_" . $booktitle;
                				        
                				        $totalpage = $row->lead_book_pages;
                				        $completepages = $row->lead_fe_completed_pages;
                				        
                				        $balance = $totalpage-$completepages;
                                $workpage =$row->lead_fe_completed_pages;
                				        
                				        $date= $row->takeup_date;
                                $mpl = $totalpage/70;

                                $msfile = $row->lead_fe_ms_file;

                                $currentdate = date('Y-m-d h:i:s');

                                // difference of dates 
                                $inDateTimestamp  = strtotime($currentdate);
                                $outDateTimestamp = strtotime($date);
                                // difference
                                $difference = $outDateTimestamp - $inDateTimestamp;

                                //Convert seconds into days.
                                $days = ($difference / (60*60*24));
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
                           <td><?php echo $completepages;?></td>
                				   <td>
                				       <div>
                                    <form method="post" action="<?php echo base_url(); ?>admin/format_editor/updatepages" enctype="multipart/form-data"> 
                                        <div class="form-group" style="display:flex;">
                                          <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                          <input type="hidden" name="completed_pages"  id="completed_pages" class="form-control w-80" value="<?php echo $completepages;?>"/>
                                          <input type="hidden" name="total_pages"  id="total_pages" class="form-control w-80" value="<?php echo $row->lead_book_pages;?>"/>
                                          <?php if ($row->download_ms_by_fe==1) { ?>
                                            <input type="text" name="workpage"  id="workpage" class="form-control w-80" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/>
                                          <button type="submit" id="submit_ms"  class="btn btn-info" >Save</button>
                                         <?php }else{ ?>
                                          <input type="text" name="workpage"  id="workpage" disabled class="form-control w-80" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/>
                                          <button type="submit" id="submit_ms" disabled class="btn btn-info" >Save</button>
                                        <? } ?>
                                         
                                        
                                        </div>
                                    </form>
                				       </div>
                				   </td>
                				   <td><?php echo $balance;?></td>
                				   <td><?php echo $date;?></td>
                				   <td><?php echo number_format($mpl, 2, '.', '');?></td>
                				   <td>
                            <?php if ($mpl >= $days && $completepages == $totalpage) {
                              echo 'Achieved';
                            }
                            else {
                              echo 'Not Achieved';
                            } ?>
                           </td>
                				   <td>
                           <?php if ($row->lead_raw_ms) { ?>
                              <a class="btn btn-xs btn-primary download_ms_format" data-id="<?=$row->id; ?>" data-href="<?=$row->lead_raw_ms?>" href="">Download</a>

                           <?php }else{?>

                            <a class="btn btn-xs btn-primary">Download</a>
                            <?php }?>
                              
                        
                              
                               
                           </td>
                				   <td>
                            <?php if($msfile!="" && $row->lead_fe_ms_rework==0){
                              echo '<span class="btn-default btn-icon">Under Review</span>';
                            }
                            else if($msfile==""){
                              echo '<span class="btn-default btn-icon">Pending</span>';
                            } else if($row->lead_fe_ms_rework==1) {
                              echo '<span class="btn-default btn-icon">Re-work</span>';
                            }
                              ?>
                               
                           </td>
                           <td>
                               <?php if($msfile=="" && $completepages != $totalpage ){?>
                                    <button class="btn btn-default" disabled>Upload</button>
                               <?php }else if($completepages == $totalpage && $row->lead_fe_ms_rework==0 ){?>
                               <?php if($msfile==""){ ?>
                                  <form method="post" action="<?php echo base_url(); ?>admin/format_editor/upload_ms" enctype="multipart/form-data"> 
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
                               <?php }else if($row->lead_fe_ms_rework==1){?>
                               <?php if($row->rework_fe_update_status==0){?>
                                    <form method="post" action="<?php echo base_url(); ?>admin/format_editor/upload_reworkms" enctype="multipart/form-data"> 
                                    <div class="form-group">
                                    <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $row->lead_author_name; ?>"/>
                                        <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $booktitle; ?>"/>
                                        <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>
                                    <input type="file" class="from-control border-none" name="file" required>
                                    <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>
                                    </div>
                                    </form>
                            <?php }else{?>
                            <button class="btn btn-success">Uploaded</button>
                           <?php }?>
                               <?php }else{?>
                               
                               <?php }?>
                             
                            </td>
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
<script type="text/javascript">
  $('.download_ms_format').click(function(){
    window.location.replace("");
    var href_link =  $(this).attr('data-href');
    var old_link = '<?=base_url();?>assets/menuscript/raw_ms/';
    var href_link_new = ''+old_link+href_link+'';
    var id =  $(this).attr('data-id');
    $('.download_ms_format').attr({target: '_blank', href  : href_link_new});

    $.ajax({
     url: "<?php echo base_url(); ?>admin/format_editor/open_for_working_pages",
     method: 'POST',
     data: {
      id: id
      },
      success: function (data) // A function to be called if request succeeds
      {
        alert('Please disable Pop-Ups otherwise ignore, Click on Pop-Ups blocked icon and allowed them.');
         alert("MS Download Successfully");
        window.location.href = "<?php echo admin_url('format_editor/project_in_process'); ?>"
        // $('.download_ms_format').attr({target: '_blank', href  : href_link_new});/
        if (data=='1') {
          
        }else{

        }
      }
    });
    window.load();
  })
</script>

</body>
</html>