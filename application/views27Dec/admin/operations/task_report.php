<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h2>Task Report</h2>
                        <hr class="hr-panel-heading">
                        <div class="_buttons">
                            <select id="staff_name" name="staff_name"  multiple data-none-selected-text="Filter By Staff" data-live-search="true" class="selectpicker custom_lead_filter" >
                                   
                                  <?php if ( !empty($get_staff) ) {
                                     foreach ($get_staff as $get_comp) { ?>
                                         <option value="<?= $get_comp->staff_id; ?>"><?= $get_comp->staffname; ?></option>
                                     <?php }
                                  } ?>
                               </select>
                               
                               <select id="tasktypefilter"  multiple data-none-selected-text="Filter By Task Type" name="task_type" data-live-search="true" class="selectpicker custom_lead_filter">
                                   
                                  <?php if ( !empty($get_task_type) ) {
                                     foreach ($get_task_type as $get_comp) { ?>
                                         <option value="<?= $get_comp->task_frequency; ?>"><?= $get_comp->task_frequency; ?></option>
                                     <?php }
                                  } ?>
                               </select>
                               <div class="dropdown bootstrap-select show-tick">
                                   <input type="text" id="start_date" autocomplete="false" name="start_date"
                                          placeholder="Select Start Date" class="form-control datepicker custom_lead_filter"/>
                                   
                               </div>
                               <div class="dropdown bootstrap-select show-tick">
                                   <input type="text" id="end_date" autocomplete="false" name="end_date"
                                          placeholder="Select End Date" class="form-control datepicker custom_lead_filter"/>
                                   
                               </div>
                               </div>
                      
                        <br>
						<div id="loading-image" style="display: none; text-align: center;"><img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>"></div>
                        <div class="ajax-data">
							
                            <?php if (!empty($work_report)) { ?>
                        <table class="table dt-table scroll-responsive">
                               <thead>
                               <tr>
                                   <th><?php echo _l('id'); ?></th>
                                   <th class="bold"> Date</th>
                                   <th class="bold">Submitted By</th>
                                   <th class="bold">Task Type</th>
                                   <th class="bold">Task</th>
                                   <th class="bold">Task Done</th>
                                   <th class="bold">Remark</th>
                               </tr>
                               </thead>
                               <tbody class="">
                                   <?php  foreach ($work_report as $work_rep) { ?>
                                   <tr>
                                       <td><?= @++$i; ?></td>
                                       <td><?= $work_rep->date; ?></td>
                                       <td><?= $work_rep->staffname; ?></td>
                                       <td><?= $work_rep->task_frequency; ?></td>
                                       <td><?= $work_rep->taskname; ?></td>
                                       <td>
                                       <?php if($work_rep->taskdone == 1) 
                                       {
                                           echo "Yes";
                                       }
                                       else
                                       {
                                           echo "<p style='color:#FF0000'>No</p>";
                                       }
                                       ?>
                                       
                                       </td>
                                       <td><?= $work_rep->taskremark; ?></td>
                                       
                                       
                                   </tr>
                               <?php } ?>
                               </tbody>
                        </table>
                        <?php
                        } else {
                            echo "No Task Report Found";
                        } ?>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    //$(document).on('change', '.custom_lead_filter', function () {
$(document).ready(function(){
	$(".custom_lead_filter").change(function(e){
		e.preventDefault();
        tasktype = $('#tasktypefilter').val(),
        staff_name = $('#staff_name').val(),
        start_date = $('#start_date').val(),
        end_date = $('#end_date').val();
		$('#loading-image').show();
		$('.table').hide();
            //url = "<?= base_url('admin/operations/filter_by_task_type') ?>";
        /* $.get(url, {staff_name:staff_name, tasktype: tasktype,start_date:start_date,end_date:end_date},
            function (res) {
                $('.ajax-data').html(res);
            }) */
		//alert(tasktype);
			$.ajax({
					type: "POST",
				  url: "/admin/operations/filter_by_task_type",
				  //cache: false,
				  data:{ tasktype: tasktype, staff_name: staff_name, start_date: start_date, end_date: end_date },
				  success: function(html){
					$('.ajax-data').html(html);
					$('#loading-image').hide();
					$('.table').show();
				  }
			});
    });
});
</script>
</body>
</html>
