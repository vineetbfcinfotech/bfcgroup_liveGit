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
            		    <table class="table dt-table table-responsive scroll-responsive tablebusie" id="examples"  cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                			<thead>
                			    <tr role="row">
                                   <!-- <th>Sr. No.</th>-->
                                    <th>Author Name</th>
                                    <th>Domain URL</th>
                                    <th>Expairy Date</th>
                                    <th>Purchase Platform</th>
                                    <th>PM NAME</th>
                                    <th>Action</th>
                                    <th>Receipt Status</th>
                                    <th>Payment Status</th>
                				</tr>
                			</thead>
                				<tbody>
                          <?php
                          $i=0;
                            foreach($domain_dtl as $row){
                              $i++;
                                
                              ?>
                				<tr>
                				   <!--<td><?php //echo $i;?></td>-->
                           <td><?php echo $row->author_name;?></td>
                           <td><?php echo $row->domain_url;?></td>
                           <td><?php echo $row->expairy_date;?></td>
                      
                           <td><?php echo $row->platform_type;?></td>
                           <td><?php echo $row->pm_name;?></td>
                           <td>
                            <select id="sel_<?php echo $row->id; ?>" onchange="mySelect(<?php echo $row->id; ?>)">
                             <option value=0 <?php if($row->pm_status==0){echo 'selected';}?>>Active</option>
                             <option value=1 <?php if($row->pm_status==1){echo 'selected';}?>>Renew</option>
                             <option value=2 <?php if($row->pm_status==2){echo 'selected';}?>>Deactive</option>
                           </select></td>
                           <td>
                            <?php if ($row->pm_status==1) {
                            if ($row->image) { ?>
                              <button class="btn  btn-success">Uploaded</button>
                            <?php }else{ ?>

                            <form method="post" action="<?= admin_url();?>Domain_controller/upload_image" enctype="multipart/form-data">
                             <input type="hidden" name="hidden_id" value="<?= $row->id; ?>">
                             <input type="file" name="upload_img"  class="form-control" style="border: hide;">
                             <button type="submit"  class="btn btn-primary">Upload</button>
                            </form>
                             <?php } ?>

                         
                          <?php  }else{ ?>
                              <button class="btn btn-info">Active</button>
                         <?php  } ?>
                            
                            </td>
                            <td>
                              <?php if ($row->account_status == 0) { ?>
                                <button type="submit"  class="btn btn-success">Approved</button>
                             <?php }elseif ($row->account_status==1) { ?>
                                 <button type="submit"  class="btn btn-danger">Disapproved</button>
                            <?php }else{} ?>
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




<?php init_tail_new();
         ?>

<script type="text/javascript">
  $(document).ready(function(){ 

  });

  function mySelect(i) {
    var value = $('#sel_'+i+' option:selected').val();
    // alert(value);
    var value2 = null;
    data = {
      id: i,
      pm_value:value,
      ac_value: value2
    }
    // console.log(value);
    $.ajax({
      type:'post',
      url: 'updateStatus',
      data:data,
      dataType:'json',beforeSend: function() {
        // $("#loading-image").show();
      },
      success: function(data){
        console.log(data);
        // $("#loading-image").hide();
      },
    })
  }


</script>
</body>

</html>