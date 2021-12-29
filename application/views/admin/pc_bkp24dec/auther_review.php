<?php init_head(); ?>
<div id="wrapper">

   <?php //init_clockinout(); ?>

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
                           <th>Format Editer</th>
                           <th>Proof Reader</th>
                           <th>Cover</th>
                           <th>Description</th>
                				</tr>
                
                			</thead>
                
                				<tbody>
                				    
                				    <?php
                				    //  print_r($newproject_data);
                				    foreach($auther_review as $row){
                				        $i++;
                				        $author = $row->lead_author_name;
                				        $booktitle = $row->lead_booktitle;
                				        $projectname= $author . "_" . $booktitle;
                				    ?>
                				    
                				   
                				   
                				<tr>
                				  <td><?php echo $i;?></td>
                				   <td><?php echo $projectname;?></td>
                				   <td><?php echo $author;?></td>
                           <td><?php echo $booktitle;?></td>
                           <td><?php if ($row->author_db_fr_rework_completed == 1) {
                             echo "Rework";
                           }elseif ($row->author_db_fr_rework_completed == 2) {
                             echo "Completed";
                           }else{} ?></td>
                           <td><?php if ($row->author_db_pr_rework_completed == 1) {
                             echo "Rework";
                           }elseif ($row->author_db_pr_rework_completed == 2) {
                             echo "Completed";
                           }else{} ?></td>
                           <td><?php if ($row->author_db_cover_rework_completed == 1) {
                             echo "Rework";
                           }elseif ($row->author_db_cover_rework_completed == 2) {
                             echo "Completed";
                           }else{} ?></td>
                           <td><?php echo $row->author_db_description; ?></td>
                				 
                				  
                				  
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

</script>
<?php init_tail(); ?>

</body>

</html>