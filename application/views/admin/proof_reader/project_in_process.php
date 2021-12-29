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
                				   <th>Manuscript</th>
                				   <th>Manuscript Status</th>
                				   <th>Manuscript Upload</th>
                				</tr>
                			</thead>
                				<tbody>
                				    <?php
                				    //  print_r($newproject_data);die;
                				    foreach($newproject_data1 as $row){
                				        $i++;
                				        $status = $row->project_status;
                				        $author = $row->lead_author_name;
                				        $booktitle = $row->lead_booktitle;
                				        $projectname= $author . "_" . $booktitle;
                				        
                				        $totalpage = $row->lead_book_pages;
                				        $completepages = $row->lead_pr_completed_pages;
                				        
                				        $balance = $totalpage-$completepages;
                                $workpage =$row->lead_pr_completed_pages;
                				        
                				        $date= $row->takeup_date;

                                $msfile = $row->lead_pr_ms_file;
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
                				            <form method="post" action="<?php echo base_url(); ?>admin/proof_reader/updatepages" enctype="multipart/form-data"> 
                                        <div class="form-group" style="display:flex;">
                                          <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                          <input type="hidden" name="completed_pages"  id="completed_pages" class="form-control w-80" value="<?php echo $completepages;?>"/>
                                          <input type="hidden" name="total_pages"  id="total_pages" class="form-control w-80" value="<?php echo $row->lead_book_pages;?>"/>
                                         <?php if ($row->download_ms_by_pr==1) { ?>
                                          <input type="text" name="workpage"  id="workpage" class="form-control w-80" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/>
                                          <button type="submit" id="submit_ms" class="btn btn-info" >Save</button>
                                        <?php }else{ ?>
                                          <input type="text" name="workpage" disabled id="workpage" class="form-control w-80" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/>
                                          <button type="submit" id="submit_ms" disabled class="btn btn-info" >Save</button>
                                        <?php }?>
                                        </div>
                                    </form>
                				       </div>
                				   </td>
                				   <td><?php echo $balance;?></td>
                				   <td><?php echo $date;?></td>
                				   <td>
                            <?php if ($completepages != $totalpage) { 
                               if ($row->lead_raw_ms) { ?>
                            <input type="hidden" id="confirm_format" value="<?= $row->lead_fe_ms_rework ?>">
                            <?php if($row->lead_fe_ms_rework == 2){ ?>
                              <a class="btn btn-xs btn-primary download_ms_proof" data-id="<?=$row->id; ?>" data-href="<?=$row->lead_fe_ms_file?>" href="">Download</a>
                           <?php }else{ ?>
                            <a class="btn btn-xs btn-primary download_ms_proof" data-id="<?=$row->id; ?>" data-href="<?=$row->lead_raw_ms?>" href="">Download</a>
                           <?php } ?>
                               <?php }else{?>
                               <a class="btn btn-xs btn-primary">Download</a>
                             <?php } } ?>
                               <?php if ($completepages == $totalpage) { ?>
                                <?php if($msfile==""){ ?>
                               <form method="post" action="<?php echo base_url(); ?>admin/proof_reader/upload_ms" enctype="multipart/form-data"> 
                                  <div class="form-group">
                                    <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $row->lead_author_name; ?>"/>
                                    <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $booktitle; ?>"/>
                                    <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>
                                    <input type="file" class="from-control border-none" name="file" required>
                                    <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>
                                  </div>
                              </form>
                            <?php } else{
                                  echo '<button class="btn btn-success">Uploaded</button>';
                                } ?>
                                <?php }?>
                           </td>
                				   <td>
                            <?php if($msfile!="" && $row->lead_pr_ms_rework==3){
                              echo '<span class="btn-default btn-icon">Under Review</span>';
                            }
                            else if($msfile==""){
                              echo '<span class="btn-default btn-icon">Pending</span>';
                            } else if($row->lead_pr_ms_rework==1) {
                              echo '<span class="btn-default btn-icon">Re-work</span>';
                            }
                              ?>
                               
                           </td>
                           <td>
                            <?php if($row->lead_pr_ms_rework==1){ ?>
                              <!-- <a class="btn btn-xs btn-primary" <?php if ($row->lead_raw_ms) { ?> href="<?=base_url('assets/menuscript/raw_ms/'.$row->lead_raw_ms)?>" <?php } ?>>Download</a> -->
                              <?php if($row->rework_pr_update_status!=1){ ?>
                            <form method="post" action="<?php echo base_url(); ?>admin/proof_reader/upload_reworkms" enctype="multipart/form-data"> 
                              <div class="form-group">
                                <input type="hidden" name="lead_id" id="lead_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $row->lead_author_name; ?>"/>
                                    <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $booktitle; ?>"/>
                                    <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>
                                <input type="file" class="from-control border-none" name="file" required>
                                <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>
                              </div>
                            </form>
                            <?php  }else{
                                  echo '<button class="btn btn-success">Uploaded</button>';
                                } ?>
                          <?php } ?>
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
  $('.download_ms_proof').click(function(){
    window.location.replace("");
    var confirm_format = $("#confirm_format").val();
    if (confirm_format == 2) {
      var href_link =  $(this).attr('data-href');
    var old_link = '<?=base_url();?>assets/menuscript/format_editor_ms/';
    var href_link_new = ''+old_link+href_link+'';
    var id =  $(this).attr('data-id');
    $('.download_ms_proof').attr({target: '_blank', href  : href_link_new});
    }else{
      var href_link =  $(this).attr('data-href');
    var old_link = '<?=base_url();?>assets/menuscript/raw_ms/';
    var href_link_new = ''+old_link+href_link+'';
    var id =  $(this).attr('data-id');
    $('.download_ms_proof').attr({target: '_blank', href  : href_link_new});
    }
   

    $.ajax({
     url: "<?php echo base_url(); ?>admin/proof_reader/open_for_working_pages",
     method: 'POST',
     data: {
      id: id
      },
      success: function (data) 
      {
        alert('Please disable Pop-Ups otherwise ignore, Click on Pop-Ups blocked icon and allowed them.');
        alert("MS Download Successfully");
        window.location.href = "<?php echo admin_url('proof_reader/project_in_process'); ?>"
        if (data=='1') {

        }else{

        }
      }
    });
    
  })
</script>
</body>

</html>