<?php init_head(); ?>
<style>
.row.ml-10{
	margin-left: 20px;
	margin-bottom: 5px;
}
</style>
<div id="wrapper">
   <?php init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <form id="ed_sal_tem" data-parsley-validate="" novalidate="" role="form" enctype="multipart/form-data"
                     action="<?php echo base_url() ?>admin/payroll/editempsal" method="post"
                     class="form-horizontal form-groups-bordered">
                     <div class="row">
                        <div class="form-group" id="border-none">
                           <label for="field-1" class="col-sm-3 control-label"><?= _l('staff_name') ?><span
                              class="required"> *</span></label>
                           <div class="col-sm-5">
                              <input class=" form-control" name="staffname" value="<?= $allemployee->name; ?>"  readonly/>
                              <input type="hidden" class=" form-control" name="staffid" value="<?= $allemployee->staffid; ?>" />
                           </div>
                        </div>
                        <div class=" form-group row" >
                           <div class="col-md-3" align="right">
                              <label for="field-1" class=" control-label"><?= "Period" ?><span
                                 class="required"> *</span></label>
                           </div>
                           <div class="col-md-4" >
                              <div class="input-group">
                                 <input type="text" name="startdate" onchange="calculatePrice()" id="start_date" class="form-control " placeholder="Select Start Date.." value="<?= $staffsal->period_from; ?>" autocomplete="off">
                                 <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4" >
                              <div class="input-group">
                                 <input type="text" name="enddate" onchange="calculatePrice()" id="end_date" class="form-control datepicker" value="<?= $staffsal->period_to; ?>" data-format="dd-mm-yyyy" data-parsley-id="17" placeholder="Select End Date.." autocomplete="off">
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
                           <div class="col-sm-4">
                              <input class="form-control" type="text" name="price" placeholder="Ex: 10000"
                                 value="<?= $staffsal->net_salary; ?>"
                                 onkeyup="calculatePrice()"><br>
                              <input type="hidden" name="percentage" value="50">
                              <input type="hidden" name="percentage_hra" value="40">
                              <input type="hidden" name="percentage_sa" value="35">
                              <input type="hidden" name="percentage_ca" value="25">
                              <input type="hidden" name="pre_sal" value="<?= $staffsal->net_salary; ?>">
                           </div>
                           <div class="col-sm-4">
                              <label for="field-1" class="col-sm-2 control-label"><?= "WEF" ?><span
                                 class="required"> *</span></label>
                              <div class="input-group">
                                 <input type="text" name="start_date_wef" onchange="calculatePrice()" id="start_date_wef" class="form-control datepicker" placeholder="Select Start Date.." value="<?php
                                    $this->db->select('timefrom, id');
                                    $this->db->where('sal_temp_id',$allemployee->staffid);
                                    $this->db->where('timefrom>=',$staffsal->start_finacial_year);
                                    $this->db->where('timeto<=',$staffsal->last_finacial_year);
                                    $this->db->order_by("id", "desc");
                                    $query = $this->db->get('tbl_salary_wef');
                                    $wefresult = $query->result();
                                    if(count($wefresult)>1){
                                         echo $wefresult[0]->timefrom;
                                    }
                                    
                                    ?>" autocomplete="off">
                                 <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                 </div>
                                 <div id="submitwef" class="input-group-addon ">
                                    <button type="button" id="submitBtn"><i class="fa fa-check" style="color:red;"></i></button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <input type="hidden" value="<?= $wefresult[0]->timefrom;?>" name="pre_start_wef">
                        <div class="form-group" id="border-none">
                           <label for="field-1" class="col-sm-3 control-label">CTC Factor<span class="required"> *</span></label>
                           <div class="col-sm-5">
                              <input class="form-control" type="text" name="annual_per" placeholder="Ex: 2" value="<?= $staffsal->ctc_factor; ?>" onkeyup="calculatePrice()"><br>
                              <input type="hidden" name="pre_ctc" value="<?= $staffsal->ctc_factor; ?>">
                           </div>
                        </div>
                        <div class="alert alert-info fade out" id="bsalert">
                           <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                           <strong>Info!</strong> After Updating Monthly Salary / CTC Factor click on <i class="fa fa-check" style="color:red;"></i> Button Next To WEF Date For <strong>WEF Update</strong>.
                        </div>
                     </div>
                     <?php
						$numItems = count($salary_wef);
						$no = 1;
                        foreach( $salary_wef as $sal_wef)
                        { 
						
						
                            if($sal_wef->timefrom>=$staffsal->start_finacial_year){ 
							
							?>
                     <div class="row ml-10" id="delete-row-<?= $sal_wef->id; ?>" style="align:center">
                        <div class="col-md-2">
                           <small>Period From</small>
                           <input readonly class="wefdiv" type="text" name="wef_timefrom<?= @++$i; ?>" value="<?= $sal_wef->timefrom?>"/>  
                        </div>
                        <div class="col-md-2">
                           <small>Period To</small> 
                           <input readonly type="text" name="wef_timeto<?= @++$j; ?>" value="<?= $sal_wef->timeto?>"/> 
                        </div>
                        <div class="col-md-2">
                           <small>Monthly Salary</small>
                           <input readonly type="text" name="wef_sal<?= @++$k; ?>" value="<?= $sal_wef->monthly?>"/> 
                        </div>
                        <div class="col-md-2">
                           <small>CTC Factor</small>
                           <input readonly type="text" name="wef_ctc_factor<?= @++$l; ?>" value="<?= $sal_wef->ctc_factor?>"/> 
                        </div>
                        <?php if($no == $numItems) { ?>
                        <div class="col-md-2">
                           <a href="<?= admin_url('payroll') ?>/delete_wef_sal/<?= $sal_wef->id; ?>" >Delete W.E.F.</a>
                        </div>
                        <?php } ?>
                        </br>
                     </div>
                     <?}
                        $no++;
					 }
						if(isset($salary_wef)){
							 $total_exp_annual_ctc=0;  $total_annual_ctc=0;
							 foreach($salary_wef as $sal_wef_2)
							 {
								 if(($sal_wef_2->id!=$sal_wef->id) && ($sal_wef_2->timefrom>=$staffsal->start_finacial_year)){
									 $leave_start = DateTime::createFromFormat('Y-m-d', $sal_wef_2->timefrom);
									 $leave_end = DateTime::createFromFormat('Y-m-d', $sal_wef_2->timeto);
									 $pre_day_sal = $sal_wef_2->monthly/30;

									 $diffMonths = $leave_end->diff($leave_start)->format("%m")+1;
									 $first_mon_day = $leave_start->format("d");
									 $last_mon_day = $leave_end->format("d");
									 $remain_month = $sal_wef_2->numberOfMonths - 2;
									
									 $first_mon_day =  30 - $first_mon_day + 1;
									 if($last_mon_day==31){
										 $last_mon_day = 30;
									 }
									 $first_mon_sal = $first_mon_day*$pre_day_sal;
									 $last_mon_sal = $last_mon_day*$pre_day_sal;
									 $two_mon_sal = $first_mon_sal + $last_mon_sal;

									 $pre_mon_sal = $sal_wef_2->monthly* $sal_wef_2->numberOfMonths;
									 
									 //$pre_mon_sal = $remain_month_sal + $two_mon_sal;
									 $total_annual_ctc = $total_annual_ctc+$pre_mon_sal;

									 $pre_exp_annual_ctc=$pre_mon_sal*$sal_wef_2->ctc_factor;
									 $total_exp_annual_ctc = $total_exp_annual_ctc+$pre_exp_annual_ctc;

								 }?>
                     <!--  <input readonly type="text" value="<?= $remain_month;?>" />  -->
                     <?php
                        }
                        }
                        
                        ?>
                     <input readonly type="hidden" value="<?= $total_annual_ctc;?>" name="pre_annual_sal" /> 
                     <input readonly type="hidden" value="<?= $total_exp_annual_ctc;?>" name="pre_exp_annual" /> 
                     <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label">Annual Salary<span
                           class="required"> *</span></label>
                        <div class="col-sm-5">
                           <input class="form-control" type="text" name="annual_ctc" placeholder="Ex: 10000"
                              value="<?= $staffsal->annual_sal; ?>"><br>
                           <input type="hidden" name="pre_annual" value="<?= $staffsal->exp_annual_ctc; ?>">
                        </div>
                     </div>
                     <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label">Expected Annual CTC<span
                           class="required"> *</span></label>
                        <div class="col-sm-5">
                           <input class="form-control" type="text" name="ex_annual_ctc" placeholder="Ex: 10000"
                              value="<?= $staffsal->exp_annual_ctc; ?>"><br>
                           <input type="hidden" name="pre_ex_annual_ctc" value="<?= $staffsal->exp_annual_ctc; ?>">
                        </div>
                     </div>
                     <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label">Direct Cost <span
                           class="required"> (Optional)</span></label>
                        <div class="col-sm-5">
                           <input class="form-control" onkeyup="calculatePrice()" type="text" value="<?= $staffsal->direct_cost; ?>"  name="direct_cost" placeholder="Ex: 10000"
                              value=""><br>
                        </div>
                     </div>
                     <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label">BEP <span
                           class="required"> *</span></label>
                        <div class="col-sm-5">
                           <input class="form-control" type="text" name="bep" value="<?= $staffsal->bep; ?>" placeholder="Ex: 10000"
                              value=""><br>
                        </div>
                     </div>
                     <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label">Baby Sitting Loss <span
                           class="required"> (If Any)</span></label>
                        <div class="col-sm-5">
                           <input class="form-control" type="text"  placeholder="Enter BSL" name="baby_shitiing_loss" id="baby_shitiing_loss"  onkeyup="calculatePrice()" value="<? if($bsl[0]->baby_sitting_loss == null){ echo "0"; } else { echo $bsl[0]->baby_sitting_loss;} ?>"><br>
                        </div>
                     </div>
                     <input type="hidden" value="" name="cs_per_qctc" >
                     <input type="hidden" value="" name="pl_over_ctc" >
                     <input type="hidden" readonly name="credit_score_fy" value="<?= number_format($creditscore[0]->net_credit, 2); ?>" class="form-control">
                     <input type="hidden" readonly name="payment_amount1" value="">
                     <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label">Qualifying CTC</label>
                        <div class="col-sm-5">
                           <input class="form-control" type="text"  readonly type="text" data-parsley-type="text" name="qualifying_ctc" id="qualifying_ctc" value="<?= $staffsal->qualifying_ctc; ?>" class="form-control">
                        </div>
                     </div>
                     <div class="form-group" id="border-none">
                        <label for="field-1" class="col-sm-3 control-label"><?= _l('basic_salary') ?><span
                           class="required"> *</span>
                        <small> (50 % of Total Salary)</small>
                        </label>
                        <div class="col-sm-5">
                           <input type="text" data-parsley-type="number" name="basic_salary" value="<?php
                              if (!empty($staffsal->basic_salary)) {
                                  echo $staffsal->basic_salary;
                              }
                              ?>" class="salary form-control" required
                              placeholder="50 % of Total Salary" readonly>
                        </div>
                     </div>
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
                                    value="<?= $staffsal->house_rent_allowance; ?>"
                                    placeholder="40 % of Basic Salary"
                                    class="salary form-control" readonly>
                              </div>
                              <div class="">
                                 <label class="control-label">Special Allowance </label>
                                 <small> (35 % of Basic Salary)</small>
                                 <input type="text" data-parsley-type="number"
                                    name="special_allowance"
                                    value="<?= $staffsal->special_allowance; ?>"
                                    placeholder="35 % of Basic Salary"
                                    class="salary form-control" readonly>
                              </div>
                              <div class="">
                                 <label class="control-label">Conveyance Allowance</label>
                                 <small> (25 % of Basic Salary)</small>
                                 <input type="text" data-parsley-type="number"
                                    name="conveyance_allowance"
                                    value="<?= $staffsal->conveyance_allowance; ?>"
                                    placeholder="25 % of Basic Salary"
                                    class="salary form-control" readonly>
                              </div>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                     <!-- ********************Allowance End ******************-->
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
                                    value="<?= $staffsal->provident_fund; ?>"
                                    class="deduction form-control">
                              </div>
                              <div class="">
                                 <label class="control-label"><?= _l('tax_deduction') ?>  <small> (In Rupees)</small></label>
                                 <input type="text" data-parsley-type="number" name="tax_deduction"
                                    value="<?= $staffsal->tax_deduction; ?>"
                                    class="deduction form-control">
                              </div>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                     <!-- ****************** Deduction End  *******************-->
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
										<input type="text" name="" disabled value="" id="total" class="form-control">
								   </td>
							   </tr> <!-- / Sub total -->
							   <tr><!-- Total tax -->
								   <th class="col-sm-8 vertical-td">
										<strong><?= _l('total_deduction') ?>
										:</strong></th>
								   <td class="">
										<input type="text" name="" disabled value="" id="deduc" class="form-control" required>
								   </td>
							   </tr><!-- / Total tax -->
							   <tr><!-- Grand Total -->
							   <th class="col-sm-8 vertical-td">
									<strong><?= _l('net_salary') ?>
									:</strong>
							   </th>
							   <td class="">
									<input type="hidden" data-parsley-type="number"
								  name="net_salary_emp" value="<?php
									 echo $total - $total_deduction; ?>" required>
									<input type="text" name="snet" readonly required value="" id="net_salary" class="form-control" required>
							   </td>
							  </tr><!-- Grand Total -->
						   </table><!-- Order Total table list start -->
					   </div>
					   </div>
				   </div><!-- ****************** Total Salary Details End  *******************-->
				   <div class="col-sm-6 margin pull-right">
				   <button  type="submit" class="btn btn-primary btn-block"><?= _l('save') ?></button>
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
   $(document).ready ( function(){
   
       $(document).bind("keyup change", function () {
           
           var sum = 0;
           var deduc = 0;
   
   
           $(".salary").each(function () {
               sum += +parseFloat($(this).val());
               //alert($(this).val());
           });
   
           $(".deduction").each(function () {
               deduc += +parseFloat($(this).val(), 10);
           });
           // var ctc = $("#ctc").val();
           
   
           $("#total").val(sum);
           $("#deduc").val(deduc);
           var net_salary = 0;
           net_salary = sum - deduc;
		   //alert(sum);
           $("#net_salary").val(net_salary);
           $('input[name=\'net_salary_emp\']').val(net_salary);
   
   
       });
   });
</script>
<script>
   function calculatePrice() {
       var percentage = $('input[name=\'percentage\']').val(),
           price = $('input[name=\'price\']').val(),
           calcPrice = price - ((price / 100) * percentage),
           discountPrice = calcPrice.toFixed(2);
       $('input[name=\'basic_salary\']').val(discountPrice);
   
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
       //start_date_wef
       var price_ac = $('input[name=\'price\']').val(),
           startdate = $('input[name=\'startdate\']').val(),
           enddate = $('input[name=\'enddate\']').val(),
           start_date_wef = $('input[name=\'start_date_wef\']').val(),
           pre_start_wef = $('input[name=\'pre_start_wef\']').val(),
           pre_annual_sal = $('input[name=\'pre_annual_sal\']').val(),
           pre_exp_annual = $('input[name=\'pre_exp_annual\']').val(),
           last_fincial_year = $('input[name=\'last_fincial_year\']').val(),
           date1 = new Date(startdate);
           date2 = new Date(enddate);
           dateforsal = new Date(enddate);
           diffTime = Math.abs(date2.getTime() - date1.getTime());
           diffDays = Math.ceil(diffTime / (60 * 60 * 24 * 7 * 4)); 
           year1=date1.getFullYear();
           year2=date2.getFullYear();
           month1=date1.getMonth();
           month2=date2.getMonth();
           numberOfMonths = (year2 - year1) * 12 + (month2 - month1) + 1 ;
   
           if(startdate>last_fincial_year){
               var fyear = 1;
               var finacial_year = date1.getFullYear()+1;
               var startnacial_year = date1.getFullYear();
               $('input[name=\'start_finacial_year\']').val(startnacial_year+"-04-01");
               $('input[name=\'last_fincial_year\']').val(finacial_year+"-03-31");
               $('input[name=\'start_date_wef\']').val('');
   
           }else if(startdate<last_fincial_year){
               var fyear='';
               var finacial_year = date1.getFullYear()+1;
               var startnacial_year = date1.getFullYear();
               $('input[name=\'start_finacial_year\']').val(startnacial_year+"-04-01");
               $('input[name=\'last_fincial_year\']').val(finacial_year+"-03-31");
               //$('input[name=\'start_date_wef\']').val(pre_start_wef);
   
           }
   
           var diff_date =  date2 - date1;
           var years = Math.floor(diff_date/31536000000);
           var months = Math.floor((diff_date % 31536000000)/2628000000);
           var days = Math.floor(((diff_date % 31536000000) % 2628000000)/86400000);
           
           var daysincurmonth = new Date(dateforsal.getFullYear(), dateforsal.getMonth()+1, 0).getDate();
           var pdayslinmonth = price_ac / 30; 
           
           var date1 = new Date(startdate);
           var date2 = new Date(enddate);
           var diffYears = date2.getFullYear()-date1.getFullYear();
           var diffMonths = date2.getMonth()-date1.getMonth();
           var diffDays = date2.getDate()-date1.getDate();
   
           if(fyear==1){
   
               var pre_ex_annual_ctc=0;
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
   
           }else if(start_date_wef){ 
               if(start_date_wef==pre_start_wef){
                  
                   var next_date1 = new Date(start_date_wef);
                   var next_date2 = new Date(enddate);
                   var next_diffYears = next_date2.getFullYear()-next_date1.getFullYear();
                   var next_diffMonths = next_date2.getMonth()-next_date1.getMonth();
                   var next_diffDays = next_date2.getDate()-next_date1.getDate();
                   var next_months = (next_diffYears*12 + next_diffMonths)+1;
                   var first_mon_days = 30 - next_date1.getDate() + 1 ;
                   var last_mon_days =  next_date2.getDate();
                   if(last_mon_days==31){
                       last_mon_days = 30;
                   }else if(last_mon_days==29){
                       last_mon_days = 30;
                   }
                  
                   var perdaysal = price/30;
                   var first_mon_sal = perdaysal*first_mon_days;
                   var last_mon_sal = perdaysal*last_mon_days;
                   var two_month_sal = parseFloat(first_mon_sal, 10) + parseFloat(last_mon_sal, 10);
                   var remain_month = next_months - 2;
                   var remain_month_sal = price*remain_month;
                   next_yearsal = (parseFloat(remain_month_sal, 10) + parseFloat(two_month_sal, 10));
                   yearsal = parseFloat(next_yearsal,10) + parseFloat(pre_annual_sal,10);
                   //alert(first_mon_sal);
               }else{ 
   
                   if(pre_annual_sal!=0){
                       var pre_sal = $('input[name=\'pre_sal\']').val();
                       var pre_ctc = $('input[name=\'pre_ctc\']').val();
   
                       var pre_date1 = new Date(pre_start_wef);
                       var pre_date2 = new Date(start_date_wef);
                       var pre_diffYears = pre_date2.getFullYear()-pre_date1.getFullYear();
                       var pre_diffMonths = pre_date2.getMonth()-pre_date1.getMonth();
                       var pre_diffDays = pre_date2.getDate()-pre_date1.getDate();
                       var pre_months = (pre_diffYears*12 + pre_diffMonths)+1;
   
                       var pre_first_mon_days = 30 - pre_date1.getDate() + 1 ;
                       var pre_last_mon_days =  pre_date2.getDate()-1;
                       if(pre_last_mon_days==31){
                           pre_last_mon_days = 30;
                       }else if(pre_last_mon_days==29){
                           pre_last_mon_days = 30;
                       }
   
                       
                       var perdsal = pre_sal/30;
                       var pre_first_mon_sal = perdsal*pre_first_mon_days;
                       var pre_last_mon_sal = perdsal*pre_last_mon_days;
                       var pre_two_month_sal = parseFloat(pre_first_mon_sal, 10) + parseFloat(pre_last_mon_sal, 10);
                       var pre_remain_month = pre_months - 2;
                       var pre_remain_month_sal = pre_sal*pre_remain_month;
                       pre_yearsal = (parseFloat(pre_remain_month_sal, 10) + parseFloat(pre_two_month_sal, 10));
                       var p_exp_annual_ctc = pre_yearsal*pre_ctc;
                       
                       var next_date1 = new Date(start_date_wef);
                       var next_date2 = new Date(enddate);
                       var next_diffYears = next_date2.getFullYear()-next_date1.getFullYear();
                       var next_diffMonths = next_date2.getMonth()-next_date1.getMonth();
                       var next_diffDays = next_date2.getDate()-next_date1.getDate();
                       var next_months = (next_diffYears*12 + next_diffMonths)+1;
                       var first_mon_days = 30 - next_date1.getDate() + 1 ;
                       var last_mon_days =  next_date2.getDate();
                       if(last_mon_days==31){
                           last_mon_days = 30;
                       }else if(last_mon_days==29){
                           last_mon_days = 30;
                       }
                       
                       var perdaysal = price/30;
   
                       var first_mon_sal = perdaysal*first_mon_days;
                       var last_mon_sal = perdaysal*last_mon_days;
                       var two_month_sal = parseFloat(first_mon_sal, 10) + parseFloat(last_mon_sal, 10);
   
                       var remain_month = next_months - 2;
                       var remain_month_sal = price*remain_month;
                       next_yearsal = (parseFloat(remain_month_sal, 10) + parseFloat(two_month_sal, 10));
                       
                       yearsal = parseFloat(next_yearsal,10) + parseFloat(pre_annual_sal,10)+ parseFloat(pre_yearsal,10);
   
                   }else{
                       var pre_sal = $('input[name=\'pre_sal\']').val();
                       var pre_ctc = $('input[name=\'pre_ctc\']').val();
                       var annual_ctc = $('input[name=\'pre_annual\']').val();
                       var pre_ex_annual_ctc = $('input[name=\'pre_ex_annual_ctc\']').val();
                       
                       var pre_date1 = new Date(startdate);
                       var pre_date2 = new Date(start_date_wef);
                       var pre_diffYears = pre_date2.getFullYear()-pre_date1.getFullYear();
                       var pre_diffMonths = pre_date2.getMonth()-pre_date1.getMonth();
                       var pre_diffDays = pre_date2.getDate()-pre_date1.getDate();
                       var pre_months = (pre_diffYears*12 + pre_diffMonths)+1;
                       var pre_first_mon_days = 30 - pre_date1.getDate() + 1 ;
                       var pre_last_mon_days =  pre_date2.getDate()-1;
                       
                       if(pre_last_mon_days==31){
                           pre_last_mon_days = 30;
                       }else if(pre_last_mon_days==29){
                           pre_last_mon_days = 30;
                       }
   
                       var perdsal = pre_sal/30;
                       var pre_first_mon_sal = perdsal*pre_first_mon_days;
                       var pre_last_mon_sal = perdsal*pre_last_mon_days;
                       var pre_two_month_sal = parseFloat(pre_first_mon_sal, 10) + parseFloat(pre_last_mon_sal, 10);
   
                       var pre_remain_month = pre_months - 2;
                       var pre_remain_month_sal = pre_sal*pre_remain_month;
   
                       pre_yearsal = (parseFloat(pre_remain_month_sal, 10) + parseFloat(pre_two_month_sal, 10));
   
                       $('input[name=\'pre_annual\']').val(pre_yearsal);
                       $('input[name=\'pre_ex_annual_ctc\']').val(pre_yearsal*pre_ctc);
                       var pre_ex_annual_ctc = $('input[name=\'pre_ex_annual_ctc\']').val();
   
                       var next_date1 = new Date(start_date_wef);
                       var next_date2 = new Date(enddate);
                       var next_diffYears = next_date2.getFullYear()-next_date1.getFullYear();
                       var next_diffMonths = next_date2.getMonth()-next_date1.getMonth();
                       var next_diffDays = next_date2.getDate()-next_date1.getDate();
                       var next_months = (next_diffYears*12 + next_diffMonths)+1;
                       var first_mon_days = 30 - next_date1.getDate() + 1 ;
                       var last_mon_days =  next_date2.getDate();
                       if(last_mon_days==31){
                           last_mon_days = 30;
                       }else if(last_mon_days==29){
                           last_mon_days = 30;
                       }
                       
                       var perdaysal = price/30;
   
                       var first_mon_sal = perdaysal*first_mon_days;
                       var last_mon_sal = perdaysal*last_mon_days;
                       var two_month_sal = parseFloat(first_mon_sal, 10) + parseFloat(last_mon_sal, 10);
   
                       var remain_month = next_months - 2;
                       var remain_month_sal = price*remain_month;
                       next_yearsal = (parseFloat(remain_month_sal, 10) + parseFloat(two_month_sal, 10));
                       yearsal = parseFloat(next_yearsal, 10) + parseFloat(pre_yearsal, 10);
                   }
                  
               }
               
   
           }else{ 
               var pre_ex_annual_ctc=0;
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
           }
   
           annual = yearsal.toFixed();
           // alert(newper_ac1);
           $('input[name=\'annual_ctc\']').val(annual);
           $('input[name=\'numberOfMonths\']').val(numberOfMonths);
       
       if(fyear==1){
            var annual_ctc = $('input[name=\'annual_ctc\']').val(),
            annual_per1 = $('input[name=\'annual_per\']').val(),
            ex_annual_ctc1 = (annual_ctc * annual_per1);
   
       }else if(start_date_wef){
           if(start_date_wef==pre_start_wef){
               var annual_ctc = $('input[name=\'annual_ctc\']').val(),
               annual_per1 = $('input[name=\'annual_per\']').val(),
               ex_annual_ctc1 = (next_yearsal * annual_per1) + parseFloat(pre_exp_annual, 10);
           }else{
               if(pre_annual_sal!=0){
                   var annual_per1 = $('input[name=\'annual_per\']').val(),
                   ex_annual_ctc1 = (next_yearsal * annual_per1) + parseFloat(pre_exp_annual, 10)+ parseFloat(p_exp_annual_ctc, 10);
                  // alert(next_yearsal+"*"+annual_per1+"+"+pre_exp_annual+"+"+p_exp_annual_ctc);
               }else{
                   var annual_ctc = $('input[name=\'annual_ctc\']').val(),
                   annual_per1 = $('input[name=\'annual_per\']').val(),
                   ex_annual_ctc1 = (next_yearsal * annual_per1) + parseFloat(pre_ex_annual_ctc, 10);
               }
               
           }
           
       }else{
           var annual_ctc = $('input[name=\'annual_ctc\']').val(),
           annual_per1 = $('input[name=\'annual_per\']').val(),
           ex_annual_ctc1 = (annual_ctc * annual_per1);
       }
      
   
   
       $('input[name=\'ex_annual_ctc\']').val(ex_annual_ctc1);
       
       var annual_ctc = $('input[name=\'ex_annual_ctc\']').val(),
           numberOfMonths = $('input[name=\'numberOfMonths\']').val(),
           s = $('input[name=\'direct_cost\']').val(),
        direct_cost = parseInt(s) || 0,
        bep = parseInt(annual_ctc) + parseInt(direct_cost) * numberOfMonths;;
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
	   
	   var sum = 0;
        var deduc = 0;


        $(".salary").each(function () {
            sum += +parseFloat($(this).val(), 10);
            /*alert(sum);*/
        });

        $(".deduction").each(function () {
            deduc += +parseFloat($(this).val(), 10);
        });
        // var ctc = $("#ctc").val();
        

        $("#total").val(sum);
        $("#deduc").val(deduc);
        var net_salary = 0;
        net_salary = sum - deduc;
        $("#net_salary").val(net_salary);
        $('input[name=\'net_salary_emp\']').val(net_salary);
        
   }
   window.onload = calculatePrice;
   
   function calculatePerc() {
       var discountPrice = $('input[name=\'discount]\']').val(),
           price = $('input[name=\'price\']').val(),
           calcPerc = (price / 100) * (price - discountPrice),
           discountPerc = calcPerc.toFixed();
       $('input[name=\'percentage\']').val(discountPerc);
   }
</script>
<script>
   $(document).ready(function(){
       $("#submitBtn").click(function(){        
           $("#ed_sal_tem").submit(); // Submit the form
       });
   });
</script>
<script>
   $(document).ready(function(){
   	$('#submitwef ').hide();
   	$('#start_date_wef').on('change', function() {
           $('#submitwef ').show();
           //alert("Please Click on Check Option after Updating figures");
       });
   });
   
   function toggleAlert(){
       $(".alert").toggleClass('in out'); 
       return false; // Keep close.bs.alert event from removing from DOM
   }
   
   $("#start_date_wef").on("change", toggleAlert);
   $('#bsalert').on('close.bs.alert', toggleAlert)
   
</script>
</body>
</html>