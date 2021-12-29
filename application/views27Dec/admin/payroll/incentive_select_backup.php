<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>Define Incentive structure</h3></center>
                         
                        
                        <div class="col-md-12">
                            <table class="table table-striped DataTables  dataTable no-footer dtr-inline"
                               id="pending_approval_table" cellspacing="0" width="100%" role="grid"
                               aria-describedby="DataTables_info" style="width: 100%;">
                            <thead>
                            <tr role="row">
                                <th><?= _l('sl') ?></th>
                                <th><?= _l('staff_name') ?></th>
                                <th><?= "Period From" ?></th>
                                <th><?= "Period To" ?></th>
                                <th><b><?= _l('net_salary') ?></b></th>
                                <th><?= "Annual Salary" ?></th>
                                <th><?= "Expected Annual CTC" ?></th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $key = 1; 
                               if ( !empty($all_salary_template) ):
                                  foreach ($all_salary_template as $v_salary_info): ?>
                                      <tr>
                                          <td><?= $key; ?></td>
                                          <td><?= $v_salary_info->firstname; ?></td>
                                          <td><?= $v_salary_info->period_from; ?></td>
                                          <td><?= $v_salary_info->period_to; ?></td>
                                          <td><b><?= number_format($v_salary_info->net_salary, 2) ?></b></td>
                                          <td><?= number_format($v_salary_info->annual_sal, 2) ?></td>
                                          <td><?= number_format($v_salary_info->exp_annual_ctc, 2) ?></td>
                                          <td>
                                       <a href="<?= base_url('admin/payroll/define_incentive');?>/<?php echo  $v_salary_info->salary_grade; ?>"   class="btn btn-default btn-icon text-danger">Define Incentive</a>

                                       </td>
                                      </tr>
                                     <?php $key++;
                                  endforeach;
                               endif; ?>
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
<script>
    $("#datepicker").datepicker( {
    format: " yyyy", // Notice the Extra space at the beginning
    viewMode: "years", 
    minViewMode: "years"
});
</script>
</body>
</html>
