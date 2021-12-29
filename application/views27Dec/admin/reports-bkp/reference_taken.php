<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3>Reference taken</h3>
                        
                        <div class="_buttons">
                        <select id="staff_name" name="staff_name"  multiple data-none-selected-text="Filter By Staff" data-live-search="true" class="selectpicker custom_lead_filter" >
                                   <? foreach ($rmconverted as $get_comp) { ?>
                                         <option value="<?= $get_comp->staff_id; ?>"><?= $get_comp->staffname; ?></option>
                                     <?php }
                                  ?>
                        </select>          
                        <hr class="hr-panel-heading">
                        <div class="ajax-data">
                        <?php if (!empty($reference_report)) { ?>
                        <table class="table dt-table scroll-responsive">
                               <thead>
                               <tr>
                                   <th><?php echo _l('id'); ?></th>
                                   <th class="bold">Reference Name</th>
                                   <th class="bold">Number</th>
                                   <th class="bold">Relationship Manager</th>
                                   <th class="bold">Date</th>
                               </tr>
                               </thead>
                               <tbody >
                                   <?php  foreach ($reference_report as $work_rep) { ?>
                                   <tr>
                                       <td><?= @++$i; ?></td>
                                       <td><?= $work_rep->name; ?></td>
                                       <td><?= $work_rep->number; ?></td>
                                       <td><?= $work_rep->staffname; ?></td>
                                       <td><?= $work_rep->added_on; ?></td>
                                       
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
<?php init_tail(); ?>
<script>
    $(document).on('change', '.custom_lead_filter', function () {
        const  staff_name = $('#staff_name').val(),
        start_date = $('#start_date').val(),
        end_date = $('#end_date').val();
            url = "<?= base_url('admin/reports/filter_reference_data') ?>";
        $.get(url, {staff_name:staff_name,start_date:start_date,end_date:end_date},
            function (res) {
                $('.ajax-data').html(res);
            })
    });
</script>
</body>
</html>
