<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons ">
                            <a href="manage_salary" class="btn mright5 btn-info pull-left display-block"> Manage
                                Salary </a>
                        </div>
                        <div class="clearfix"></div>
                        <table class="table table-striped DataTables  dataTable no-footer dtr-inline"
                               id="pending_approval_table" cellspacing="0" width="100%" role="grid"
                               aria-describedby="DataTables_info" style="width: 100%;">
                            <thead>
                            <tr role="row">
                                <th><?= _l('sl') ?></th>
                                <th><?= _l('staff_name') ?></th>
                                <th><?= _l('basic_salary') ?></th>
                                <!--<th><?= _l('overtime') ?>
                                    <small>(<?= _l('per_hour') ?>)</small>
                                </th>-->
                                <th><?= _l('house_rent_allowance') ?></th>
                                <th>Special Allowance</th>
                                <th>Conveyance Allowance</th>
                                <th><?= _l('provident_fund') ?></th>
                                <th><?= _l('tax_deduction') ?></th>
                                <th><b><?= _l('net_salary') ?></b></th>
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
                                          <td><?= number_format($v_salary_info->basic_salary, 2) ?></td>
                                          <!--<td><?= $v_salary_info->overtime_salary; ?></td>-->
                                          <td><?= number_format($v_salary_info->house_rent_allowance, 2) ?></td>
                                          <td><?= number_format($v_salary_info->special_allowance, 2) ?></td>
                                          <td><?= number_format($v_salary_info->conveyance_allowance, 2) ?></td>
                                          <td><?= number_format($v_salary_info->provident_fund, 2) ?></td>
                                          <td><?= number_format($v_salary_info->tax_deduction, 2) ?></td>
                                          <td><b><?= number_format($v_salary_info->net_salary, 2) ?></b></td>
                                          <td>
                                       <a href="<?= base_url('admin/payroll/manage_salary');?>/<?php echo  $v_salary_info->salary_template_id; ?>"   class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>

                                        <a href="<?php echo  base_url(); ?>admin/payroll/deletesaltem/<?php echo  $v_salary_info->salary_template_id; ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>

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
   <?php init_tail(); ?>
    </body>
    </html>
