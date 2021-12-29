<?php init_head(); ?>
<div id="wrapper">
   <?php //init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                     <a href="#"  class="btn mright5 btn-info pull-left display-block">
                     Back </a>
                  </div>
                  <?php if(!empty($this->session->flashdata('success'))) {?>
                   <div class="text-success text-center"><?php echo  $this->session->flashdata('success'); ?></div>
                <?php }else if(!empty($this->session->flashdata('error'))){?>
                   <div class="text-danger text-center"><?php echo  $this->session->flashdata('error'); ?></div>
                <?php } ?>
                <select id="staff_name" multiple data-none-selected-text="Select PC"
                                data-live-search="true" class="selectpicker custom_lead_filter">
                          <?php
                          if (!empty($get_staff) ) {

                                     foreach ($get_staff as $get_comp) { ?>

                                         <option <?php if (in_array($get_comp->staffid, $staff_name)) { echo "selected";
                                         } ?>  value="<?= $get_comp->staffid; ?>"><?= $get_comp->firstname; ?></option>

                                     <?php }

                                  } ?>

                        </select>
                          
                        </select>
                <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="start_date" autocomplete="false" name="start_date"
                                          placeholder="Select Start Date" class="form-control datepicker custom_lead_filter"/>

                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="end_date" autocomplete="false" name="end_date"
                                          placeholder="Select End Date" class="form-control datepicker custom_lead_filter"/>

                        </div>
                <div class="clearfix"></div>
                <hr class="hr-panel-heading" />
                <table class="table dt-table scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                  <thead>
                     <tr role="row">
                        <th>Sr. No.</th>
                       
                        <th>Working Duration</th>
                        <th>Task Description</th>
                        <th>Remark / Details</th>
                        </tr>
                     </thead>
                     <tbody id="table_data">
                        <?php $i=1; foreach($result as $project){ ?>
                           <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $project->work_duration; ?></td>
                                <td><?php echo $project->description; ?></td>
                              <td><?php echo $project->remark; ?></td>
                             
                          </tr>
                          <?php $i++; } ?>
                        <!--
                           <td>Payed</td>
                           <td>Approved</td>
                        -->
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
   $(document).ready(function(){
 
    
    $(".custom_lead_filter").on("change", function(){
                var staff_name = $('#staff_name').val();
                  var start_date = $('#start_date').val();
                  var end_date = $('#end_date').val();
                  $.ajax({
         type: "POST",
         url: "<?php echo admin_url('DCR/filter_view_dcr'); ?>",
         data: {
                            'staff_name': staff_name,
                            'start_date': start_date,
                            'end_date': end_date
                        },
           	// data: {'staff_name': staff_name, 'start_date':start_date, 'end_date':end_date}, // <--- THIS IS THE CHANGE
   			dataType: "html",
   			success: function(data){
   				console.log(data);
               if(data != ''){
               $('#table_data').html('');
               $('#table_data').html(data);
               $('#pending_approval_table').DataTable().order([0, 'asc']).draw();
            }else{
               $('#table_data').html('');
            }
               data = '';
   			},
   			error: function() { //alert("Error posting feed."); 
               }
        });
   });
   });
    
</script>
<?php init_tail(); ?>
</body>
</html>

