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
                           <th>Email</th>
                           <th>Book Title</th>
                           <th>ASF</th>
                			<th>Total Pages</th>
                			<th>Start Date</th>
                			<th>MRP</th>
                      <th>MRP Ebook</th>
                			<th>ISBN eBook</th>
                			<th>ISBN Paperback</th>
                			<th>Project Status</th>
                				</tr>
                			</thead>
                				<tbody>
                          <?php
                          $i=0;
                            //  print_r($newproject_data);
                            foreach($newproject_data as $row){
                              $i++;
                                $author = $row->lead_author_name;
                                $booktitle = $row->lead_booktitle;
                                $projectname= $author . "_" . $booktitle;
                              ?>
                				<tr>
                			<td><?php echo $i;?></td>
                           <td><a href="<?php base_url();?>pip/<?php echo $row->id; ?>"><?php echo $projectname;?></a></td>
                           <td><?php echo $author;?></td>
                           <td><?php echo $row->email;?></td>
                           <td><?php echo $booktitle;?></td>
                           <td><a class="btn btn-info" target="_blank" download href="<?php echo base_url(); ?>assets/asf_authorMail/<?php echo $row->asf_pdf_data ?>">Download ASF</a></td> 
                            <td><?php echo $row->lead_book_pages;?></td>
                            <td><?php echo $row->dm_takeup_date;?></td>
                            <td><?php echo $row->lead_final_mrp_suggestion;?></td>
                            <td><?php echo $row->lead_final_mrp_suggestion_ebook;?></td>
                            <td><?php echo $row->lead_isbn_ebook;?></td>
                            <td><?php echo $row->lead_isbn_paperback;?></td>
                            <td>Under review</td>
                         
                          
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