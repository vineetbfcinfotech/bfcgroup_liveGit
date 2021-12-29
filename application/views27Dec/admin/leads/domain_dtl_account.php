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
                                    
                                    <th>Domain Status</th>
                                    <th>Receipt</th>
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
                           
                           <td><select id="sel_<?php echo $row->id; ?>" disabled>
                             <option value=0 <?php if($row->pm_status==0){echo 'selected';}?>>Active</option>
                             <option value=1 <?php if($row->pm_status==1){echo 'selected';}?>>Renew</option>
                             <option value=2 <?php if($row->pm_status==2){echo 'selected';}?>>Deactive</option>
                           </select></td>
                           <td>
                             <?php if ($row->image) { ?>
                        <a href="<?= base_url();?>assets/domain_img/<?= $row->image?>" target="_blank"><img src="<?= base_url();?>assets/domain_img/<?= $row->image?>" height="60" width="60" alt="Recipt"></a>
                             <?php }else{ ?>
                              <img src="<?= base_url();?>assets/images/payment_receipt_first_final/no_payment.webp" width="60" height="60" border="0" align="center"/>
                             <?php } ?>
                           </td>
                           <td>
                           <!--   <select id="sel_<?php echo $row->id; ?>" onchange="mySelect(<?php echo $row->id; ?>)">
                             <option value=0 <?php if($row->account_status==0){echo 'selected';}?>>Approved</option>
                             <option value=1 <?php if($row->account_status==1){echo 'selected';}?>>Disapproved</option>
                            
                           </select> -->
<select class="select_id" data-id="<?php echo $row->id; ?>">
    <!--<option value="" selected>Active</option>-->
    <option value="0" <?php if($row->account_status==0){echo 'selected';}?>>Approved</option>
    <option value="1" <?php if($row->account_status==1){echo 'selected';}?>>Disapproved</option>
</select>
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
   function mySelect(i) {
     var value2 = $('#sel_'+i+' option:selected').val();
    
    
    var value = null;
    data = {
      id: i,
      pm_value:value,
      ac_value: value2
    }
    // console.log(value);
    // $.ajax({
    //   type:'post',
    //   url: 'updateStatus',
    //   data:data,
    //   dataType:'json',
    //   success: function(data){
    //     console.log(data);
    //   },
    // })
  }
  $(document).ready(function(){ 
   $('.select_id').on('change', function()
{
  var id = $(this).attr("data-id");
 var value2 = $(this).val();

  data = {
      id: id,
      ac_value: value2
    }
      $.ajax({
      type:'post',
      url: 'updateStatus',
      data:data,
      dataType:'json',
      success: function(data){
        if(data['code']==200){
       location.reload();
        }
        
      },
    })
  
}); 

  });

 

</script>
</body>

</html>