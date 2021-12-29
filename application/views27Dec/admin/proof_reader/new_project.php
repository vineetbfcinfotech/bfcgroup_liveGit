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
                           <th>Total Pages</th>
                				   <th>Take Up</th>
                				</tr>
                			</thead>
                				<tbody>
                          <?php
                          $i=0;
                            //  print_r($newproject_data);
                            foreach($newproject_data as $row){
                              $i++;
                                $status = $row->project_status;
                                $author = $row->lead_author_name;
                                $booktitle = $row->lead_booktitle;
                                $projectname= $author . "_" . $booktitle;
                              ?>
                				<tr>
                				   <td><?php echo $i;?></td>
                           <td><?php echo $projectname;?></td>
                           <td><?php echo $author;?></td>
                           <td><?php echo $booktitle;?></td>
                           <td><?php echo $row->lead_book_pages;?></td>
                         
                           <td>
                            <form method="post" enctype="multipart/form-data"> 
                              <input type="hidden" name="author_name" id="author_name" class="hidden_id" value="<?php echo $author; ?>"/>
                              <input type="hidden" name="user_id" id="user_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                              <input type="hidden" name="book_name" id="book_name" class="hidden_id" value="<?php echo $booktitle; ?>"/>
                              <input type="hidden" name="proj_name" id="proj_name" class="hidden_id" value="<?php echo $projectname; ?>"/>
                              <a class="btn btn-success btn-xs take"  data-id="<?php echo $row->id;?>" >Take Up</a>
                              <!-- <button class="take btn btn-sm btn-primary" id="<?php //echo $row->id;?>">Take Up</button> -->
                            </form>
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


<script>


  $(document).on('click', '.take', function() {
      var classd = "success";
          var project_id   = $(this).attr("data-id");
          var author_name = $('#author_name').val();
          var user_id = $('#user_id').val();
          var proj_name = $('#proj_name').val();

            $.ajax({

      type: "POST",

      url: "<?php echo admin_url('proof_reader/changeProjectStatus'); ?>",

      data: {'project_id': project_id, 'author_name': author_name, 'user_id':user_id, 'proj_name':proj_name},

      dataType: "html",

      success: function(data){
         alert_float(classd, "Project Takeup Successfully");
         window.location.href = "<?php echo admin_url('proof_reader/project_in_process'); ?>"
          //window.location.replace("<?php //echo admin_url('proof_reader/project_in_process'); ?>");
   
      }

    });
  }); 
</script>

<?php init_tail(); ?>

</body>

</html>