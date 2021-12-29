<?php init_head(); ?> <div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
         
          <br>
          <div class="panel_s">
            <div class="panel-body">
              <!-- <h2 class="faq-heading">Acquisition Report</h2> -->
              <div id="loading-image" style="display: none; text-align: center;">
    					<img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
    				  </div>
              <div class="table-responsive">
                      <div class="row">
                        <div class="col-md-2"> <a href="<?php echo base_url();?>admin"  class="btn mright5 btn-info pull-left display-block" style="margin-bottom: 10px;">
                         Back  </a></div>
                        <div class="col-md-2">
                          <?php $staff_id = $_SESSION['staff_user_id'];?>
                          <?php if (is_admin() || is_headtrm() || $staff_id==34 || $staff_id==28) { ?>
                          <select class="form-control" name="pc_select" id="pc_select">
                            <option value="0">Select PC</option>
                            <?php foreach ($pcList as $key => $value) { ?>
                              <option value="<?= $value->staffid; ?>"><?= $value->firstname; ?></option>
                           <?php } ?>
                          </select>
                        <?php } ?>
                        </div>
                        <div class="col-md-1">
                        	
                        </div>
                        <div class="col-md-7">
                          <div class="row">
                          	<label>From date</label>
                          	<input type="date" id="from_date" name="">
                          	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          	<label>To date</label>
                          	<input type="date" id="to_date" name="">
                          	<input type="submit" class="btn btn-info" id="submit_date" value="Search" name="">
                          </div>
                        </div>
                      </div>
                      <hr>
                      <?php //echo "<pre>";print_r($project_data);exit;?>
                      <div class="mytable">
            		    <table id="aquisitionReport" class="table dt-table table-responsive scroll-responsive tablebusie  table-striped table-bordered pending_approval_table"  cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                			<thead>
                				<tr role="row">
                           <th>Sr. No.</th>
                           <th>Acquisition date</th>
                           <th>Author Name</th>
                           <!-- <th>Ad Name/ Lead Source</th>
                           <th>Book Language</th> -->
                           <?php if (is_admin() || is_headtrm() || $staff_id==34) { ?>
                           <th>PC Name</th>
                            <?php } ?>
                           <th>Package Name</th>
                           <th>Book Format</th>
                           <th>Package Value Excluding GST</th>
                           <th>Cost of Additional Copies(Ex GST)</th>
                           <th>Total Package Value</th>
                           
                        
                				   
                				</tr>
                			</thead>
                				<tbody >
                          <?php
                          $i=0;
                              
                            foreach($project_data as $row){
                              $i++;
                                
                                $Package_name = '';
                                if ($row->lead_package_detail == 1) {
                                 $Package_name = 'Standard';
                                }else if ($row->lead_package_detail == 2) {
                                 $Package_name = 'Customized';
                                }else if ($row->lead_package_detail == 3) {
                                  $Package_name = 'Standard Customized';
                                }else{

                                }
                              ?>
                				<tr><td><?php echo $i;?></td>
                           <td><?= $row->lead_acquired_date;?></td>
                           <td><a style="cursor: pointer;" class="full_description" data-id=<?= $row->id; ?>><?= $row->lead_author_name;?></a></td>
                          
                            <?php if (is_admin() || is_headtrm() || $staff_id==34) { ?>
                           <td><?= $row->fullname;?></td>
                            <?php } ?>
                           <td><?= $Package_name;?></td>
                           <td><?php echo $row->lead_book_type;?></td>
                           <td><?php echo  $row->lead_packge_value;?></td>
                            <td><?php echo  ($row->cost_of_additional_copy)*0.25;?></td>
                             <td><?php echo  $row->lead_packge_value + ($row->cost_of_additional_copy)*0.25;?></td>
                           
                          
                				</tr>
                        <?php } ?>
                
                			</tbody>
            			</table>
            			</div>
		            	</div>
              <hr>
              <br>
              
            </div>
          </div>
              <div id="author_data" class="modal fade" role="dialog">

              <div class="modal-dialog">



              <!-- Modal content-->

              <div class="modal-content">

              <form action="<?php echo base_url() ?>admin/leads/addRemarks" method="POST">

              <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal">&times;</button>

              <h4 class="modal-title">Package Details:</h4>

              </div>

              <div class="modal-data">



              </div>

              <span id="alert-msg"></span>

              </form>

              </div>



              </div>

              </div>
        </div>
      </div>
    </div> <?php init_tail(); ?> <script>
  $('#pc_select').on('change', function() {
 $('#loading-image').show();
	var staff_id = this.value;

            $.ajax({

      type: "POST",

      url: "<?php echo admin_url('Leads/change_pc_acquisition'); ?>",

      data: {'staff_id': staff_id},

      dataType: "html",

      success: function(data){
        // console.log(data);
        $('#loading-image').hide();

        if (data) {
          $('.pending_approval_table tbody').html(data);
        }else{
          $('.pending_approval_table tbody').html('');
        }
       
        

      }

    });
  }); 

$(document).on('click', '.full_description', function(){
  
  var author_id = $(this).attr("data-id");
//   $("#full_remarks").val(full_remarks);
  $.ajax({

      type: "POST",

      url: "<?php echo admin_url('Leads/get_acquisition_d'); ?>",

      data: {'author_id': author_id}, // <--- THIS IS THE CHANGE

      dataType: "html",

      success: function(data){

         $(".modal-data").html(data);

         $("#author_data").modal('show');

      },

      error: function() { alert("Error posting feed."); }

       });
});



$("#submit_date").click(function(){
  var pc_name = $('#pc_select').find(":selected").val();
  var from_date = $("#from_date").val();
  var to_date = $("#to_date").val();
  //alert(pc_name);
    console.log(from_date)
    console.log(to_date)
	 $.ajax({
      type: "POST",
      url: "<?php echo admin_url('Leads/date_filter_acquisition'); ?>",
      data: {'from_date': from_date, 'to_date':to_date, 'pc_name':pc_name},
      dataType: "html",
      success: function(data){
        console.log(data)
        $('#loading-image').hide();
        $('.pending_approval_table tbody').html(data);
      }
    });

});
</script>
    </body>
    </html>