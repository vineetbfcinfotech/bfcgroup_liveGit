<?php init_head(); ?>
<style>
    .ui-datepicker-month, .ui-datepicker-year {
        color: #0a0a0a !important;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-custom" data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong><?= _l('salary_report') ?></strong>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form id="attendance-form" action="<?= base_url('admin/payroll/get_salary_report'); ?>"
                              method="post" class="form-horizontal">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                   value="<?= $this->security->get_csrf_hash(); ?>"/>
                            <div class="col-sm-5 col-md-offset-3">
                               <?= render_select('staff_id', $staff_members, array('staffid', 'firstname'), 'choose_staff'); ?>
                            </div>
                            <div class="col-sm-5 col-md-offset-3">
                                <div class="form-group" app-field-wrapper="date">
                                    <label for="date" class="control-label"><?= _l('month'); ?><span
                                                class="required"> *</span></label>
                                    <div class="input-group date">
                                        <input type="text" id="date" name="date" class="form-control monthYearPicker" required>
                                        <div class="input-group-addon"><i class="fa fa-calendar calendar-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 pull-right">
                                <div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-5 ">
                                        <button type="submit" id="sbtn"
                                                class="btn btn-primary"><?= _l('search') ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table table-striped DataTables  dataTable no-footer dtr-inline"
                       id="pending_approval_table" cellspacing="0" width="100%" role="grid"
                       aria-describedby="DataTables_info" style="width: 100%;">
                    <thead>
                    <tr role="row">
                        <th class="col-sm-1"><?= _l('sl') ?></th>
                        <th><?= _l('staff_name') ?></th>
                        <th><?= _l('sal_month') ?></th>
                        <th><?= _l('net_salary') ?></th>
                        <th><?= _l('n_working') ?></th>
                        <th><?= _l('n_leave') ?></th>
                        <th>Action</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php $key = 1; ?>
                    <?php if ( !empty($employee) ): foreach ($employee as $v_salary_info): ?>
                        <tr>
                            <td><?php echo $key; ?></td>
                            <td><?= $v_salary_info['full_name']; ?></td>
                            <td><?= $tmonth; ?></td>
                            <td><?= number_format($monthsal, 2); ?> INR</td>
                            <td><?= $nworking; ?></td>
                            <td><?= $nleave; ?></td><td class="sorting_1">
                            <a class="text-danger" href="<?= base_url();?>/admin/payroll/make_payment/<?= $v_salary_info['staffid']; ?>/<?= $tmonth; ?>/<?= $monthsal; ?>">Make Payment</a>
                                                                        </td>
                            

                        </tr>
                       <?php
                       $key++;
                    endforeach;
                       ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $('form').attr('autocomplete', 'off');
    $('.monthYearPicker').datepicker({
        dateFormat: 'mm-yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        onClose: function (dateText, inst) {
            const month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            const year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
    $(".monthYearPicker").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });
</script>

</body>
</html>
