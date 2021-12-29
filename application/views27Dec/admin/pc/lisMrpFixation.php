<?php init_head(); ?>

<div id="wrapper">

   <?php init_clockinout(); ?>

    <div class="content">

        <div class="row">

            <div class="col-md-12">

                <div class="panel_s">

                   <div class="panel-body">

                      <div class="_buttons">

                         <a href="#"  class="btn mright5 btn-info pull-left display-block" style="margin-bottom: 10px;">Back  </a>

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

                				   <th>Author Name</th>

                				   <th>email</th>

                				   <th>Mobile</th>

                				   <th>Book Type</th>

								   <th>Book Title</th>

                				   <th>Production Cost</th>

                				   <th>Author Production Cost</th>

                				   <th>Author REC MRP</th>

                				   <th>Amazon Royalty</th>

                				   <th>BFC Royalty</th>

                				   <th>KDP Royalty</th>

                				   

                				</tr>

                			</thead>

                            <tbody>

                                <?php $i = 1; foreach($mrpfixationList as $mrpfixationListData){?>

                            	<tr>

                            	  <td><?php echo $i++;?></td>

                            	  <td><?php echo $mrpfixationListData->author_name;?></td>

                            	  <td><?php echo $mrpfixationListData->email;?></td>

                            	  <td><?php echo $mrpfixationListData->mobile;?></td>

                            	  <td><?php echo $mrpfixationListData->book_type;?></td>
								  <td><?php echo $mrpfixationListData->book_title;?></td>

                            	  <td><?php echo $mrpfixationListData->production_cost;?></td>

                            	  <td><?php echo $mrpfixationListData->author_p_cost;?></td>

                            	  <td><?php echo $mrpfixationListData->rec_mrp;?></td>

                            	  <td><?php echo $mrpfixationListData->amazon;?></td>

                            	  <td><?php echo $mrpfixationListData->bfc;?></td>

                            	  <td><?php echo $mrpfixationListData->kdp;?></td>

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