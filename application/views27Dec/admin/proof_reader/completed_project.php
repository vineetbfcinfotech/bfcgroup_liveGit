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
                                $totalpage = $row->lead_book_pages;
                                $date= $row->takeup_date;
                                $enddate = $row->lead_upload_ms_date;
                                
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

<script>
   $(document).on('click', '.submit', function() {
      
          var project_id   = this.id;
          var completed_pages= $("#completed_pages").val();

          	$.ajax({

			type: "POST",

			url: "<?php echo admin_url('format_editor/updatepages'); ?>",

			data: {'project_id': project_id, 'pages': completed_pages},

			dataType: "html",

			success: function(data){
			    alert("Pages Updated Successfully");
			    window.location.reload();
	 
			}

		});
  }); 
</script>
<?php init_tail(); ?>

</body>

</html>