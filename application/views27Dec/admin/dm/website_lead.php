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
            		    <table class="table dt-table table-responsive scroll-responsive " id="" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                			<thead>
                				<tr role="row">
                          <th>Lead Creation Date</th>
                          <th>Ad Id</th>
                          <th>Ad Name</th>
                          <th>Book Language</th>
                    			<th>Manuscript Status</th>
                    			<th>Published Earlier</th>
                          <th>Email</th>
                          <th>Author Name</th>
                          <th>Contact Number</th>
                				</tr>
                			</thead>
                				<tbody>
                          <?php
                          $i=0;
                            //  print_r($newproject_data);
                            foreach($contact as $row){
                    
                                
                              ?>
                				<tr>
                			    <td><?php echo $row->created_at;?></td>
                          <td><?php echo 'ag:23846284007160187';?></td>
                          <td><?php echo 'website contact us';?></td>
                          <td><?php echo $row->lang;?></td>
                          <td></td>
                          <td></td>
                          <td><?php echo $row->email;?></td>
                          <td><?php echo $row->name;?></td>
                            <td><?php echo $row->phone;?></td>
                         
                          
                				</tr>
                        <?php }
                            foreach($refer_friends as $row){
                             
                                
                              ?>
                        <tr>
                          <td><?php echo $row->created_at;?></td>
                          <td><?php echo 'ag:23846284007160187';?></td>
                          <td><?php echo 'website home';?></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><?php echo $row->email;?></td>
                          <td><?php echo $row->name;?></td>
                            <td><?php echo $row->phone;?></td>
                         
                          
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