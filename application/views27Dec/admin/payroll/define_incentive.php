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
                                                  action="<?= base_url(); ?>/admin/payroll/save_incentive/" method="post"
                                                  class="form-horizontal form-groups-bordered">
                                                <div class="col-sm-3" data-spy="scroll" data-offset="0">
                                                    <div class="row">

                                                        <div class="panel panel-custom fees_payment">
                                                            <!-- Default panel contents -->
                                                            <div class="panel-heading">
                                                                <div class="panel-title">
                                                                    <strong>Incentive For Financial Year <span
                                                                                class="text-danger"><?= date("Y", strtotime($employeesal->period_from)); ?> - <?= date("Y,", strtotime($employeesal->period_to)); ?></span></strong>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" value="<?= date("Y", strtotime($employeesal->period_from)); ?> - <?= date("Y", strtotime($employeesal->period_to)); ?>" name="finacial_year">
                                                            <input type="hidden" value="" name="cs_per_qctc" >
                                                            <input type="hidden" value="" name="pl_over_ctc" >
                                                            <div class="panel-body">
                                                                <div class="">
                                                                    <label class="control-label">BEP </label>
                                                                    <input type="text" name="exp_annual_ctc"
                                                                           readonly
                                                                           value="<?= number_format($employeesal->bep, 2); ?>"
                                                                           class="salary form-control">
                                                                </div>
                                                                

                                                                <input type="hidden" name="total_award" id="total_award"
                                                                       value="" class="form-control">
                                                                <div class="">
                                                                    <label class="control-label">Baby Shitting Loss </label>
                                                                    <input type="text" data-parsley-type="number"
                                                                          placeholder="Enter BSL" name="baby_shitiing_loss" id="baby_shitiing_loss"
                                                                           value="" onkeyup="calculatePrice()"
                                                                           class="form-control">
                                                                </div>
                                                                <div class="">
                                                                    <label class="control-label">Qualifying CTC</label>
                                                                    <input readonly type="text" data-parsley-type="text"
                                                                           name="qualifying_ctc"
                                                                           id="qualifying_ctc"
                                                                           value="" class="form-control">
                                                                </div>
                                                                <div class="">
                                                                    <label class="control-label"><strong>Credit Score
                                                                             </strong></label>
                                                                    <input type="text" readonly name="credit_score_fy"
                                                                           value="<?= number_format($creditscore[0]->net_credit, 2); ?>"
                                                                           class="form-control">
                                                                </div>
                                                                <div class="">
                                                                    <label class="control-label"><strong>Total Incentive
                                                                             </strong></label>
                                                                    <input type="text" readonly name="payment_amount1"
                                                                           value=""
                                                                           class="payment_amount form-control">
                                                                </div>
                                                                
                                                                
                                                                

                                                                <input type="hidden" name="staffid"
                                                                       value="<?= $employeedetails[0]->staffid; ?> "/>
                                                                       <div class="clear_fix">
                                                                          </br> 
                                                                       </div>
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
                                                                <strong>Incentive  History</strong>
                                                                
                                                            </div>
                                                        </div>

                                                        <!-- Table -->
                                                        <table class="table table-striped " id="DataTables"
                                                               cellspacing="0" width="100%">
                                                            <thead>
                                                            <tr>
                                                                <th>Period</th>
                                                                <th>BEP</th>
                                                                <th>Baby Shitting Loss</th>
                                                                <th>Qualifying CTC</th>
                                                                <th>Credit Score</th>
                                                                <th>Total Incentive</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>


                                                            <tbody>
                                                            <?php foreach ($incentive_payment_info as $incentive_payment) { ?>
                                                                <tr>
                                                                    <td> <?= $incentive_payment->finacial_year; ?></td>
                                                                    <td> <?= $incentive_payment->ctc; ?></td>
                                                                    <td> <?= $incentive_payment->baby_sitting_loss; ?></td>
                                                                    <td> <?= $incentive_payment->qualifying_ctc; ?></td>
                                                                    <td> <?= $incentive_payment->credit_score_fy; ?></td>
                                                                    <td> <?= $incentive_payment->rm_incentive_fy; ?></td>
                                                                    <td>
                                                                        <a class="text-danger" href="<?= base_url(); ?>/admin/payroll/deleteincentive/<?= $incentive_payment->id; ?>">
                                                                            Delete </a></td>
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
        
        var exp_annual_ctc = $('input[name=\'exp_annual_ctc\']').val();
         var   baby_shitiing_loss = $('input[name=\'baby_shitiing_loss\']').val();
          var expctc = parseFloat(exp_annual_ctc.replace(",", ""));
          var   credit_score = $('input[name=\'credit_score_fy\']').val();
          var credit_score_fy = parseFloat(credit_score.replace(",", ""));
          bbys = parseFloat(baby_shitiing_loss);
          //alert(bbys);
          //alert(bbys);
            calcPrice = expctc + bbys;
            discountPrice = calcPrice.toFixed(2);
            percentagediff = credit_score_fy - calcPrice;
            percentagediffence = percentagediff.toFixed(2);
            percentageslab = (percentagediffence/discountPrice)*100;
           // alert(percentageslab);
            
            if((percentageslab > "0") && (percentageslab <= "10")  )
            {
                profit1 =  (percentagediffence*5)/100;
               totalprofit = profit1.toFixed(2);
            }
            
            else if((percentageslab > "10") && (percentageslab <= "20")  )
            {
                profit1 =  (percentagediffence*10)/100;
               totalprofit = profit1.toFixed(2);
            }
            
            else if((percentageslab > "20") && (percentageslab <= "30")  )
            {
                profit1 =  (percentagediffence*15)/100;
               totalprofit = profit1.toFixed(2);
            }
            
            else if((percentageslab > "30") && (percentageslab <= "40")  )
            {
                profit1 =  (percentagediffence*20)/100;
               totalprofit = profit1.toFixed(2);
            }
            
            else if((percentageslab > "40") && (percentageslab <= "50")  )
            {
                
               profit1 =  (percentagediffence*25)/100;
               totalprofit = profit1.toFixed(2);
               
            }
            
            else if((percentageslab > "50")   )
            {
               profit1 =  (percentagediffence*40)/100;
               totalprofit = profit1.toFixed(2);
            }
            
            else 
            {
                profit1 =  (percentagediffence*0)/100;
               totalprofit = profit1.toFixed(2);
            }
            
        //  alert(percentageslab);
        $('input[name=\'cs_per_qctc\']').val(percentageslab);
        $('input[name=\'pl_over_ctc\']').val(percentagediffence);
        $('input[name=\'qualifying_ctc\']').val(discountPrice);
        $('input[name=\'payment_amount1\']').val(totalprofit);
    }
</script>
</body>
</html>