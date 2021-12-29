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

                				   <th>Work Alloted By</th>

                				   <th>Discription</th>

                				   <th>Allt. Date</th>

                				   <th>Status</th>

                				</tr>

                			</thead>

                				<tbody>

                				    <?php

                				    foreach($miscproject_data as $row){

                				        $i++;

                				    ?>

                				   

                				<tr>

                				   <td><?php echo $i;?></td>

                				   <td><?php if($row->alloted_by== 97) echo "Ashish"; else echo "Shivangi";?></td>

                				   <td><?php echo $row->description;?></td>

                				   <td><?php echo $row->alloted_date;?></td>

                				   <td>

                            <?php if($row->file_path=="") {?>

                              <form method="post" action="<?php echo base_url(); ?>admin/graphic_designer/upload_misc" enctype="multipart/form-data"> 

                                  <div class="form-group">

                                    <input type="hidden" name="misc_id" id="misc_id" class="hidden_id" value="<?php echo $row->id; ?>"/>
                                    

                                    <input type="file" class="from-control border-none" name="file" required>

                                    <button type="submit" id="submit_ms" class="btn btn-info" >Upload</button>

                                  </div>

                              </form>   

                              <?php } else{

                                echo '<button class="btn btn-success" >Uploaded</button>';

                              } ?> 

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



</body>

</html>