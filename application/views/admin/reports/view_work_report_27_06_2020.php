<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h2>Daily Work Report</h2>
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
                                         <option value="<?= $get_comp->task_type; ?>"><?= $get_comp->task_type; ?></option>
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
                        <div class="ajax-data">
                            <?php if (!empty($work_report)) { ?>
                        <table class="table dt-table scroll-responsive">
                               <thead>
                               <tr>
                                   <th><?php echo _l('id'); ?></th>
                                   <th class="bold"> Submitted By</th>
                                   <th class="bold"> Task Type</th>
                                   <th class="bold"> Category</th>
                                   <th class="bold">Company Name</th>
                                   <th class="bold">NAME OF THE PERSON</th>
                                   <th class="bold">Designation</th>
                                   <th class="bold">Mobile No.</th>
                                   <th class="bold">Address</th>
                                   <th class="bold">Duration</th>
                                   <th class="bold">Remarks</th>
                                   <th class="bold">Working date</th>
                                   <th class="bold">Next FU date</th>
                                   <th class="bold">Reference taken</th>
                                   <th class="bold">Categorisation</th>
                               </tr>
                               </thead>
                               <tbody class="">
                                   <?php  foreach ($work_report as $work_rep) { ?>
                                   <tr>
                                       <td><?= @++$i; ?></td>
                                       <td><?= $work_rep->staffname; ?></td>
                                       <td><?if($work_rep->task_type == "Personal_Meeting") { echo "Meeting"; }
                                       else { echo $work_rep->task_type; }
                                       ?></td>
                                       <td><?php if($work_rep->task_type == "Assignment") { echo $work_rep->ass_desc; }  else { echo $work_rep->task_category; } ?></td>
                                       <td><?= $work_rep->company_name; ?></td>
                                       <td><?= $work_rep->person_name; ?></td>
                                       <td><?= $work_rep->designation; ?></td>
                                       <td><?= $work_rep->mobile_number; ?></td>
                                       <td><?php if($work_rep->address == "") { echo ""; 
                                       }  else { echo 
                                       preg_replace('/[^A-Za-z0-9\-]/', ' ', $work_rep->address);
                                       
                                          } ?></td>
                                       <td><?= $work_rep->duration; ?></td>
                                       <td><?= $work_rep->remark; ?></td>
                                       <td> <?php if($work_rep->task_type == "Assignment") { echo $work_rep->date; }  else { echo $work_rep->date; } ?></td>
                                       <td><?= $work_rep->next_fu_date; ?></td>
                                       <td><?= $work_rep->refernece_taken; ?></td>
                                       <td>
                                           <?php if($work_rep->categorisation == "3") { echo "Converted"; }  else { echo $work_rep->categorisation; } ?></td>
                                       
                                   </tr>
                               <?php } ?>
                               </tbody>
                        </table>
                        <?php
                        } else {
                            echo "No Work Report Found";
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
    $(document).on('change', '.custom_lead_filter', function () {
        const tasktype = $('#tasktypefilter').val(),
        staff_name = $('#staff_name').val(),
        start_date = $('#start_date').val(),
        end_date = $('#end_date').val();
            url = "<?= base_url('admin/reports/filter_by_task_type') ?>";
        $.get(url, {staff_name:staff_name, tasktype: tasktype,start_date:start_date,end_date:end_date},
            function (res) {
                $('.ajax-data').html(res);
            })
    });
</script>
</body>
</html>
