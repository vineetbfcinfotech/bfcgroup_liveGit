    <?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="content-wrapper">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="panel panel-default">
                                                <!-- *********     Employee Search Panel ***************** -->
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <strong>Make Payment</strong>
                                                    </div>
                                                </div>

                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-sm-3 control-label"><strong>Employee Name
                                                                :</strong> <?= $employeedetails[0]->firstname . $employeedetails[0]->lastname; ?>
                                                        </div>
                                                        <div class="col-sm-3 control-label"><strong>Email
                                                                :</strong> <?= $employeedetails[0]->email; ?>
                                                        </div>
                                                        <div class="col-sm-3 control-label"><strong>Phone
                                                                :</strong> <?= $employeedetails[0]->phonenumber; ?>
                                                        </div>
                                                        <div class="col-sm-3 control-label"><strong>Address
                                                                :</strong> <?= $employeedetails[0]->street_address; ?>
                                                        </div>


                                                    </div>

                                                </div>
                                            </div>
                                            <!-- ******************** Employee Search Panel Ends ******************** -->
                                            <form role="form" data-parsley-validate="" novalidate=""
                                                  enctype="multipart/form-data"
                                                  action="<?= base_url(); ?>/admin/payroll/get_payment/" method="post"
                                                  class="form-horizontal form-groups-bordered">
                                                <div class="col-sm-3" data-spy="scroll" data-offset="0">
                                                    <div class="row">

                                                        <div class="panel panel-custom fees_payment">
                                                            <!-- Default panel contents -->
                                                            <div class="panel-heading">
                                                                <div class="panel-title">
                                                                    <strong>Payment For <span
                                                                                class="text-danger"><?= date("M Y,", strtotime($tmonth1)); ?></span></strong>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="">
                                                                    <label class="control-label">Basic Salary </label>
                                                                    <input type="text" name="house_rent_allowance"
                                                                           readonly
                                                                           value="<?= number_format($employeesal->basic_salary, 2); ?>"
                                                                           class="salary form-control">
                                                                </div>
                                                                <!--<div class="">
                                                                    <label class="control-label">Total Deduction </label>
                                                                    <input type="text" name="" disabled value="1000" class="salary form-control">
                                                                </div>-->
                                                                <div class="">
                                                                    <label class="control-label">Net Salary </label>
                                                                    <input type="text" id="net_salary"
                                                                           name="net_salary1" readonly
                                                                           value="<?= number_format($employeesal->net_salary, 2); ?>"
                                                                           class="salary form-control">
                                                                    <input type="hidden" name="net_salary"
                                                                           value="<?= number_format($employeesal->net_salary, 2); ?>"/>
                                                                    <input type="hidden" name="gross_salary"
                                                                           value="<?= number_format($employeesal->basic_salary, 2); ?>">
                                                                </div>

                                                                <input type="hidden" name="total_award" id="total_award"
                                                                       value="" class="form-control">
                                                                <div class="">
                                                                    <label class="control-label">Fine Deduction </label>
                                                                    <input type="text" data-parsley-type="number"
                                                                           name="fine_deduction" id="fine_deduction"
                                                                           value="" onkeyup="calculatePrice()"
                                                                           class="form-control">
                                                                </div>
                                                                <div class="">
                                                                    <label class="control-label">Deduction
                                                                        Comment </label>
                                                                    <input type="text" data-parsley-type="text"
                                                                           name="fine_deduction_comment"
                                                                           id="fine_deduction_comment"
                                                                           value="" class="form-control">
                                                                </div>
                                                                <div class="">
                                                                    <label class="control-label"><strong>Payment
                                                                            Amount </strong></label>
                                                                    <input type="text" readonly name="payment_amount1"
                                                                           value="<?= number_format($monthsal, 2); ?>"
                                                                           class="payment_amount form-control">
                                                                </div>
                                                                <input type="hidden" name="payment_amount_real"
                                                                       value="<?= $monthsal; ?>"
                                                                       class="payment_amount form-control">
                                                                <input type="hidden" name="payment_amount"
                                                                       value="<?= $monthsal; ?>"
                                                                       class="payment_amount form-control">
                                                                <!-- Hidden Employee Id -->

                                                                <input type="hidden" name="payment_month"
                                                                       value="<?= $tmonth1; ?>"
                                                                       class="salary form-control">
                                                                <div class=""><!-- Payment Type -->
                                                                    <label class="control-label">Payment Method <span
                                                                                class="required"> *</span></label>
                                                                    <select name="payment_type"
                                                                            class="form-control col-sm-5 selectpicker"
                                                                            onchange="get_payment_value(this.value)">
                                                                        <option value="">Select Payment Method</option>
                                                                        <option value="1">Online</option>
                                                                        <option value="4">Bank Transfer</option>
                                                                        <option value="5">Cash</option>
                                                                    </select>
                                                                </div><!-- Payment Type -->
                                                                <div class="">
                                                                    <label class="control-label">Comments </label>
                                                                    <input type="text" name="comments" value=""
                                                                           class=" form-control">
                                                                </div>

                                                                <input type="hidden" name="staffid"
                                                                       value="<?= $employeedetails[0]->staffid; ?> "/>
                                                                <div class="form-group mt-lg">
                                                                    <div class="col-sm-5">
                                                                        <button type="submit" name="sbtn" value="1"
                                                                                class="btn btn-primary btn-block">Update
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!--************ Fees payment End ***********-->

                                            </form>
                                            <!--************ Payment History Start ***********-->
                                            <!---************** Employee Info show When Print ***********************--->
                                            <div id="payment_history">

                                                <!--  **************** show when print End ********************* -->
                                                <div class="col-sm-9 print_width">

                                                    <div class="panel panel-custom">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading">
                                                            <div class="panel-title">
                                                                <strong>Payment History</strong>
                                                                <div class="pull-right">
                                                                    <!-- set pdf,Excel start action -->
                                                                    <label class="hidden-print control-label pull-left hidden-xs">
                                                                        <button class="btn btn-danger btn-xs"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="Print" type="button"
                                                                                onclick="payment_history('payment_history')">
                                                                            <i class="fa fa-print"></i>
                                                                        </button>
                                                                    </label>
                                                                </div><!-- set pdf,Excel start action -->
                                                            </div>
                                                        </div>

                                                        <!-- Table -->
                                                        <table class="table table-striped " id="DataTables"
                                                               cellspacing="0" width="100%">
                                                            <thead>
                                                            <tr>
                                                                <th>Month</th>
                                                                <th>Date</th>
                                                                <th>Net Salary</th>
                                                                <th>Fine Deduction</th>
                                                                <th>Amount</th>
                                                                <th>Details</th>
                                                                <th>Payment Method</th>
                                                                <th>Salary Slip</th>
                                                            </tr>
                                                            </thead>


                                                            <tbody>
                                                            <?php foreach ($salary_payment_info as $salary_payment_in) { ?>
                                                                <tr>
                                                                    <td><?php echo date('F-Y', strtotime($salary_payment_in->payment_month)); ?></td>
                                                                    <td><?php echo date('d-M-y', strtotime($salary_payment_in->paid_date)); ?></td>
                                                                    <td> <?= number_format($salary_payment_in->net_salary, 2); ?></td>
                                                                    <td> <?= number_format($salary_payment_in->fine_deduction, 2); ?></td>
                                                                    <td> <?= number_format($salary_payment_in->amount, 2); ?></td>
                                                                    <td> <?= $salary_payment_in->comments; ?></td>
                                                                    <td> <?= $salary_payment_in->payment_type; ?></td>
                                                                    <td>
                                                                        <a href="<?= base_url(); ?>/admin/payroll/generate_payslip/<?= $salary_payment_in->salary_payment_id; ?>">
                                                                            Generate </a></td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div><!--************ Payment History End***********-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
<script>
    function calculatePrice() {
        var fine_deduction = $('input[name=\'fine_deduction\']').val(),
            payment_amount = $('input[name=\'payment_amount_real\']').val(),
            calcPrice = payment_amount - fine_deduction,
            discountPrice = calcPrice.toFixed(2);
        // alert(discountPrice);
        $('input[name=\'payment_amount\']').val(discountPrice);
        $('input[name=\'payment_amount1\']').val(discountPrice);
    }
</script>
</body>
</html>