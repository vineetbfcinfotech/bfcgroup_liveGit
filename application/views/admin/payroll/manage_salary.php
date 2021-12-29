<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <form data-parsley-validate="" novalidate="" role="form" enctype="multipart/form-data"
                              action="<?php echo base_url() ?>admin/payroll/set_salary_details" method="post"
                              class="form-horizontal form-groups-bordered">
                            <div class="row">
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label"><?= _l('staff_name') ?><span
                                                class="required"> *</span></label>
                                    <div class="col-sm-5">
                                        <select name="salary_grade" data-none-selected-text="Select Employee"  data-live-search="true"  class="form-control selectpicker" required>
                                           <option>Select Employee</option> 
                                           <?php $disabledempids = explode(',',$disabledempids);
                                           foreach($allemployee as $employee){
                                               $disbled = in_array($employee->staffid,$disabledempids) ? 'disabled' : '';
                                               echo sprintf('<option value="%s" %s>%s</option>',$employee->staffid,$disbled,$employee->name);
                                           } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class=" form-group row" >
                                    <div class="col-md-3" align="right">
                                     <label for="field-1" class=" control-label"><?= "Period" ?><span
                                                class="required"> *</span></label>
                                                </div>
                            <div class="col-md-4" >
                                <div class="input-group">
                                    <input type="text" name="startdate" id="start_date" class="form-control " placeholder="Select Start Date.." onchange="calculatePrice()"  value="" autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" >
                                <div class="input-group">
                                    <input type="text" name="enddate" id="end_date" onchange="calculatePrice()" class="form-control datepicker" value="" data-format="dd-mm-yyyy" data-parsley-id="17" placeholder="Select End Date.." autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <input readonly type="hidden" value="" name="numberOfMonths" />
                            <input readonly type="hidden" value="<?= $staffsal->start_finacial_year; ?>" name="start_finacial_year" />
                            <input readonly type="hidden" value="<?= $staffsal->last_finacial_year; ?>" name="last_fincial_year" />
                        </div>
                                
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label"><?= "Monthly Salary" ?><span
                                                class="required"> *</span></label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="price" placeholder="Ex: 10000"
                                               value=""
                                               onkeyup="calculatePrice()"><br>
                                        <input type="hidden" name="percentage" value="50">
                                        <input type="hidden" name="percentage_hra" value="40">
                                        <input type="hidden" name="percentage_sa" value="35">
                                        <input type="hidden" name="percentage_ca" value="25">

                                    </div>
                                </div>
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label">Annual Salary<span
                                                class="required"> *</span></label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="annual_ctc" placeholder="Ex: 10000"
                                               value=""><br>

                                    </div>
                                </div>
                                
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label">CTC Factor<span
                                                class="required"> *</span></label>
                                    <div class="col-sm-5">
                                        <input class="form-control"   type="text" name="annual_per" placeholder="Ex: 2"
                                               value="" onkeyup="calculatePrice()"><br>

                                    </div>
                                </div>
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label">Expected Annual CTC<span
                                                class="required"> *</span></label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="ex_annual_ctc" placeholder="Ex: 10000"
                                               value=""><br>

                                    </div>
                                </div>
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label">Direct Cost <span
                                                class="required"> (Optional)</span></label>
                                    <div class="col-sm-5">
                                        <input class="form-control" onkeyup="calculatePrice()" type="text" name="direct_cost" placeholder="Ex: 10000"
                                               value=""><br>

                                    </div>
                                </div>
                                
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label">BEP <span
                                                class="required"> *</span></label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="bep" placeholder="Ex: 10000"
                                               value=""><br>

                                    </div>
                                </div>
                                
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label">Baby Sitting Loss <span
                                                class="required"> (If Any)</span></label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text"  placeholder="Enter BSL" name="baby_shitiing_loss" id="baby_shitiing_loss"  onkeyup="calculatePrice()" value=""><br>

                                    </div>
                                </div>
                                <input type="hidden" value="<?= date("Y", strtotime($employeesal->period_from)); ?> - <?= date("Y", strtotime($employeesal->period_to)); ?>" name="finacial_year">
                                                            <input type="hidden" value="" name="cs_per_qctc" >
                                                            <input type="hidden" value="" name="pl_over_ctc" >
                                                            <input type="hidden" readonly name="credit_score_fy"
                                                                           value="<?= number_format($creditscore[0]->net_credit, 2); ?>"
                                                                           class="form-control">
                                <div class="form-group" id="border-none">
                                                                    <label for="field-1" class="col-sm-3 control-label">Qualifying CTC</label>
                                                                    <div class="col-sm-5">
                                                                    <input class="form-control" type="text"  readonly type="text" data-parsley-type="text"
                                                                           name="qualifying_ctc"
                                                                           id="qualifying_ctc"
                                                                           value="" class="form-control">
                                                                           </div>
                                                                </div>
                                <div class="form-group" id="border-none">
                                    <label for="field-1" class="col-sm-3 control-label"><?= _l('basic_salary') ?><span
                                                class="required"> *</span>
                                        <small> (50 % of Total Salary)</small>
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" data-parsley-type="number" name="basic_salary" value="<?php
                                        if (!empty($salary_template_info->basic_salary)) {
                                            echo $salary_template_info->basic_salary;
                                        }
                                        ?>" class="salary form-control" required
                                               placeholder="50 % of Total Salary" readonly>
                                    </div>
                                </div>
                                <!--<div class="form-group" id="border-none">
                                                            <label for="field-1" class="col-sm-3 control-label"><?= _l('overtime_rate') ?>
                                                                <small> ( <?= _l('per_hour') ?>)</small>
                                                            </label>
                                                            <div class="col-sm-5">
                                                                <input type="text" data-parsley-type="number" name="overtime_salary"
                                                                       value="<?php
                                if (!empty($salary_template_info->overtime_salary)) {
                                    echo $salary_template_info->overtime_salary;
                                }
                                ?>" class="form-control"
                                                                       placeholder="<?= _l('enter') . ' ' . _l('overtime_rate') ?>">
                                                            </div>
                                                        </div>-->

                                <div class="col-sm-6">
                                    <div class="panel panel-custom">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <strong><?= _l('allowances') ?></strong>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            $total_salary = 0;
                                            if (!empty($salary_allowance_info)):foreach ($salary_allowance_info as $v_allowance_info):
                                                ?>
                                                <div class="">
                                                    <input type="text" style="margin:5px 0px;height: 28px;width: 56%;"
                                                           class="form-control" name="allowance_label[]"
                                                           value="<?php echo $v_allowance_info->allowance_label; ?>"
                                                           class="">
                                                    <input type="text" data-parsley-type="number"
                                                           name="allowance_value[]"
                                                           value="<?php echo $v_allowance_info->allowance_value; ?>"
                                                           class="salary form-control">
                                                    <input type="hidden" name="salary_allowance_id[]"
                                                           value="<?php echo $v_allowance_info->salary_allowance_id; ?>"
                                                           class="salary form-control">
                                                </div>
                                                <?php $total_salary += $v_allowance_info->allowance_value; ?>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="">
                                                    <label class="control-label"><?= _l('house_rent_allowance') ?> </label>
                                                    <small> (40 % of Basic Salary)</small>
                                                    <input type="text" data-parsley-type="number"
                                                           name="house_rent_allowance"
                                                           value=""
                                                           placeholder="40 % of Basic Salary"
                                                           class="salary form-control" readonly>
                                                </div>
                                                <div class="">
                                                    <label class="control-label">Special Allowance </label>
                                                    <small> (35 % of Basic Salary)</small>
                                                    <input type="text" data-parsley-type="number"
                                                           name="special_allowance"
                                                           value=""
                                                           placeholder="35 % of Basic Salary"
                                                           class="salary form-control" readonly>
                                                </div>

                                                <div class="">
                                                    <label class="control-label">Conveyance Allowance</label>
                                                    <small> (25 % of Basic Salary)</small>
                                                    <input type="text" data-parsley-type="number"
                                                           name="conveyance_allowance"
                                                           value=""
                                                           placeholder="25 % of Basic Salary"
                                                           class="salary form-control" readonly>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div><!-- ********************Allowance End ******************-->

                                <!-- ************** Deduction Panel Column  **************-->
                                <div class="col-sm-6">
                                    <div class="panel panel-custom">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <strong><?= _l('deductions') ?></strong>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            $total_deduction = 0;
                                            if (!empty($salary_deduction_info)):foreach ($salary_deduction_info as $v_deduction_info):
                                                ?>
                                                <div class="">
                                                    <input type="text" style="margin:5px 0px;height: 28px;width: 56%;"
                                                           class="form-control" name="deduction_label[]"
                                                           value="<?php echo $v_deduction_info->deduction_label; ?>"
                                                           class="">
                                                    <input type="text" data-parsley-type="number"
                                                           name="deduction_value[]"
                                                           value="<?php echo $v_deduction_info->deduction_value; ?>"
                                                           class="deduction form-control">
                                                    <input type="hidden" name="salary_deduction_id[]"
                                                           value="<?php echo $v_deduction_info->salary_deduction_id; ?>"
                                                           class="deduction form-control">
                                                </div>
                                                <?php $total_deduction += $v_deduction_info->deduction_value ?>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="">
                                                    <label class="control-label"><?= _l('provident_fund') ?> <small> (In Rupees)</small></label>
                                                    <input type="text" data-parsley-type="number" name="provident_fund"
                                                           value=""
                                                           class="deduction form-control">
                                                </div>
                                                <div class="">
                                                    <label class="control-label"><?= _l('tax_deduction') ?>  <small> (In Rupees)</small></label>
                                                    <input type="text" data-parsley-type="number" name="tax_deduction"
                                                           value=""
                                                           class="deduction form-control">
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div><!-- ****************** Deduction End  *******************-->
                                <!-- ************** Total Salary Details Start  **************-->
                            </div>
                            <div class="row">
                                <div class="col-md-8 pull-right">
                                    <div class="panel panel-custom">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <strong><?= _l('total_salary_details') ?></strong>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-bordered custom-table">
                                                <tr><!-- Sub total -->
                                                    <th class="col-sm-8 vertical-td"><strong><?= "Monthly Salary" ?>
                                                            :</strong>
                                                    </th>
                                                    <td class="">
                                                        <input type="text" name="" disabled value="<?php
                                                        if (!empty($total_salary) || !empty($salary_template_info->basic_salary)) {
                                                            echo $total = $total_salary + $salary_template_info->basic_salary;
                                                        }
                                                        ?>" id="total" class="form-control">
                                                    </td>
                                                </tr> <!-- / Sub total -->
                                                <tr><!-- Total tax -->
                                                    <th class="col-sm-8 vertical-td">
                                                        <strong><?= _l('total_deduction') ?>
                                                            :</strong></th>
                                                    <td class="">
                                                        <input type="text" name="" disabled value="<?php
                                                        if (!empty($total_deduction)) {
                                                            echo $total_deduction;
                                                        }
                                                        ?>" id="deduc" class="form-control">
                                                    </td>
                                                </tr><!-- / Total tax -->
                                                <tr><!-- Grand Total -->
                                                    <th class="col-sm-8 vertical-td"><strong><?= _l('net_salary') ?>
                                                            :</strong>
                                                    </th>
                                                    <td class="">
                                                        <input type="hidden" data-parsley-type="number"
                                                               name="net_salary_emp" value="<?php
                                                        echo $total - $total_deduction; ?>">
                                                        <input type="text" name="" disabled required value="<?php
                                                        if (!empty($total) || !empty($total_deduction)) {
                                                            echo $total - $total_deduction;
                                                        }
                                                        ?>" id="net_salary" class="form-control">
                                                    </td>
                                                </tr><!-- Grand Total -->
                                            </table><!-- Order Total table list start -->

                                        </div>
                                    </div>
                                </div><!-- ****************** Total Salary Details End  *******************-->
                                <div class="col-sm-6 margin pull-right">
                                    <button type="submit" class="btn btn-primary btn-block"><?= _l('save') ?></button>
                                </div>
                            </div>
                        </form>
                   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 100) {
                alert("Maximum 100 File is allowed");
            } else {
                var add_new = $('<div class="row">\n\
    <div class="col-sm-12"><input type="text" name="allowance_label[]" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control"  placeholder="<?= _l('enter') . ' ' . _l('allowances') . ' ' . _l('label')?>" required ></div>\n\
<div class="col-sm-9"><input  type="text" data-parsley-type="number" name="allowance_value[]" placeholder="<?= _l('enter') . ' ' . _l('allowances') . ' ' . _l('value')?>" required  value="<?php
                    if (!empty($emp_salary->allowance_value)) {
                        echo $emp_salary->allowance_value;
                    }
                    ?>"  class="salary form-control"></div>\n\
<div class="col-sm-3"><strong><a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more_deduc").click(function () {
            if (maxAppend >= 100) {
                alert("Maximum 100 File is allowed");
            } else {
                var add_new = $('<div class="row">\n\
    <div class="col-sm-12"><input type="text" name="deduction_label[]" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control" placeholder="<?= _l('enter') . ' ' . _l('deductions') . ' ' . _l('label')?>" required></div>\n\
<div class="col-sm-9"><input  type="text" data-parsley-type="number" name="deduction_value[]" placeholder="<?= _l('enter') . ' ' . _l('deductions') . ' ' . _l('value')?>" required  value="<?php
                    if (!empty($emp_salary->other_deduction)) {
                        echo $emp_salary->other_deduction;
                    }
                    ?>"  class="deduction form-control"></div>\n\
<div class="col-sm-3"><strong><a href="javascript:void(0);" class="remCF_deduc"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div></div>');
                maxAppend++;
                $("#add_new_deduc").append(add_new);
            }
        });

        $("#add_new_deduc").on('click', '.remCF_deduc', function () {
            $(this).parent().parent().parent().remove();
        });
    });
</script>
<script type="text/javascript">
    $(document).bind("keyup change", function () {
        var sum = 0;
        var deduc = 0;


        $(".salary").each(function () {
            sum += +$(this).val();
        });

        $(".deduction").each(function () {
            deduc += +$(this).val();
        });
        var ctc = $("#ctc").val();

        $("#total").val(sum);
        $("#deduc").val(deduc);
        var net_salary = 0;
        net_salary = sum - deduc;
        $("#net_salary").val(net_salary);
        $('input[name=\'net_salary_emp\']').val(net_salary);


    });

</script>
<script>
    function calculatePrice() {

        var percentage = $('input[name=\'percentage\']').val(),
            price = $('input[name=\'price\']').val(),
            calcPrice = price - ((price / 100) * percentage),
            discountPrice = calcPrice.toFixed(2);
        $('input[name=\'basic_salary\']').val(discountPrice);
        //alert(startdate);
        var percentage_hra = $('input[name=\'percentage_hra\']').val(),
            price_hra = $('input[name=\'basic_salary\']').val(),
            newper_hra = (price_hra * percentage_hra) / 100;

        $('input[name=\'house_rent_allowance\']').val(newper_hra);

        var percentage_sa = $('input[name=\'percentage_sa\']').val(),
            price_sa = $('input[name=\'basic_salary\']').val(),
            newper_sa = (price_sa * percentage_sa) / 100;
        //alert(newper_sa);
        $('input[name=\'special_allowance\']').val(newper_sa);

        var percentage_ca = $('input[name=\'percentage_ca\']').val(),
            price_ca = $('input[name=\'basic_salary\']').val(),
            newper_sa = (price_ca * percentage_ca) / 100;
        $('input[name=\'conveyance_allowance\']').val(newper_sa);
        
        var price_ac = $('input[name=\'price\']').val(),
            startdate = $('input[name=\'startdate\']').val(),
            enddate = $('input[name=\'enddate\']').val(),
            date1 = new Date(startdate);
            date2 = new Date(enddate);
            months = (date1.getFullYear() - date2.getFullYear()) * 12;
            //alert(months);
            //date2.setDate(date2.getDate() + 1);
            dateforsal = new Date(enddate);
            diffTime = Math.abs(date2.getTime() - date1.getTime());
            diffDays = Math.ceil(diffTime / (60 * 60 * 24 * 7 * 4)); 
            year1=date1.getFullYear();
            year2=date2.getFullYear();
            month1=date1.getMonth();
            month2=date2.getMonth();
            numberOfMonths = (year2 - year1) * 12 + (month2 - month1) + 1 ;
            
            var diff_date =  date2 - date1;
            var years = Math.floor(diff_date/31536000000);
            var months = Math.floor((diff_date % 31536000000)/2628000000);
            var days = Math.floor(((diff_date % 31536000000) % 2628000000)/86400000);
           // alert( years+" year(s) "+months+" month(s) "+days+" and day(s)");
            
            var daysincurmonth = new Date(dateforsal.getFullYear(), dateforsal.getMonth()+1, 0).getDate();
            var pdayslinmonth = price_ac / 30; 
            //alert(numberOfMonths);
            
            
            var date1 = new Date(startdate);
            var date2 = new Date(enddate);
            var diffYears = date2.getFullYear()-date1.getFullYear();
            var diffMonths = date2.getMonth()-date1.getMonth();
            var diffDays = date2.getDate()-date1.getDate();
            
            var first_mon_days = 30 - date1.getDate() + 1 ;
            var last_mon_days =  date2.getDate();
            if(last_mon_days==31){
                last_mon_days = 30;
            }else if(last_mon_days==29){
                last_mon_days = 30;
            }
            // alert(last_mon_days);
            var months = (diffYears*12 + diffMonths);
            var perdaysempsal = price_ac/30;
            var perdaysal = price/30;
            var first_mon_sal = perdaysal*first_mon_days;
            var last_mon_sal = perdaysal*last_mon_days;
            var two_month_sal = parseFloat(first_mon_sal, 10) + parseFloat(last_mon_sal, 10);

            var remain_month = numberOfMonths - 2;
            var remain_month_sal = price*remain_month;
            yearsal = (parseFloat(remain_month_sal, 10) + parseFloat(two_month_sal, 10));
            
            var months = (diffYears*12 + diffMonths);
            var perdaysempsal = price_ac/30;
            monthsal = (price_ac * months);
            annual = yearsal.toFixed(2);
           
            
            //alert(newper_ac);
          var finacial_year = date1.getFullYear()+1;
          var startnacial_year = date1.getFullYear();
          $('input[name=\'start_finacial_year\']').val(startnacial_year+"-04-01");
          $('input[name=\'last_fincial_year\']').val(finacial_year+"-03-31");

         $('input[name=\'annual_ctc\']').val(annual);
         $('input[name=\'numberOfMonths\']').val(numberOfMonths);
        
        
         var annual_ctc = $('input[name=\'annual_ctc\']').val(),
            annual_per1 = $('input[name=\'annual_per\']').val(),
            ex_annual_ctc1 = (annual_ctc * annual_per1);
        $('input[name=\'ex_annual_ctc\']').val(ex_annual_ctc1);
        
        var annual_ctc = $('input[name=\'ex_annual_ctc\']').val(),
            numberOfMonths = $('input[name=\'numberOfMonths\']').val(),
            s = $('input[name=\'direct_cost\']').val(),
         direct_cost = parseInt(s) || 0,
         bep = parseInt(annual_ctc) + parseInt(direct_cost) * numberOfMonths;
         $('input[name=\'bep\']').val(bep);
         
         var exp_annual_ctc = $('input[name=\'bep\']').val();
         var   baby_shitiing_loss = $('input[name=\'baby_shitiing_loss\']').val();
         var expctc = parseFloat(exp_annual_ctc.replace(",", ""));
         
          var   credit_score = $('input[name=\'credit_score_fy\']').val();
          var credit_score_fy = parseFloat(credit_score.replace(",", ""));
         bbys = parseFloat(baby_shitiing_loss);
         calqctc = expctc + bbys;
         fincalqctc = calqctc.toFixed(2);
         //alert(fincalqctc);
         
          $('input[name=\'qualifying_ctc\']').val(fincalqctc);
          
          percentagediff = credit_score_fy - calqctc;
            percentagediffence = percentagediff.toFixed(2);
            percentageslab = (percentagediffence/fincalqctc)*100;
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
        $('input[name=\'qualifying_ctc\']').val(fincalqctc);
        $('input[name=\'payment_amount1\']').val(totalprofit);
         
    }

    function calculatePerc() {
        var discountPrice = $('input[name=\'discount]\']').val(),
            price = $('input[name=\'price\']').val(),
            calcPerc = (price / 100) * (price - discountPrice),
            discountPerc = calcPerc.toFixed();
        $('input[name=\'percentage\']').val(discountPerc);
    }
</script>


</body>
</html>
