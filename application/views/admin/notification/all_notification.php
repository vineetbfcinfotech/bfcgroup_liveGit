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
                      <h2>All Notification List</h2>
    				  <div id="loading-image" style="display: none; text-align: center;">
    					<img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
    				  </div>
                     <div class="table-responsive">
            		    <table class="table dt-table table-responsive scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                			<thead>
                				<tr role="row">
                           <th>S.No.</th>
                            <th>Author Name</th>
                           <th>Project Name</th>
                          
                           <th>Book Title</th>
                           <th>Message</th>
                           <th>Discription</th>
                           <th>Date</th>
                				</tr>
                			</thead>
                				<tbody>
                          <?php
                          $i=0;
                            //  print_r($newproject_data);
                            foreach($result as $row){
                              $i++;
                              ?>
                				<tr>
                			<td><?php echo $i;?></td>
                           <td><?php echo $row->author_name;?></td>
                           <td><?php echo $row->project_name;?></td>
                           <td><?php echo $row->book_name;?></td>
                            <td><?php echo $row->message;?></td>
                            <td><?php echo $row->discription;?></td>
                            <td><?php echo $row->date;?></td>
                         
                          
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