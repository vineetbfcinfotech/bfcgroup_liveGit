<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <h3>
                                <a href="<?= base_url('admin/leads/business_mobilization'); ?>" class="btn btn-alert"
                                   onclick="">
                                    Back</a>
                        </div>
                        <p><i class="fa fa-star"></i> Please Select All filters for Filtered Result</p>
                        <select id="filterrm" multiple data-none-selected-text="Filter By RM"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php if (!empty($rmconverted)) {
                                foreach ($rmconverted as $rmconverteds) { ?>
                                    <option value="<?= $rmconverteds->staffid; ?>"><?= $rmconverteds->firstname; ?></option>
                                <?php }
                            } ?>
                        </select>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="datestart" autocomplete="false" name="datestart"
                                   placeholder="Period From"
                                   class="form-control datepicker custom_lead_filter"/>

                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="dateend" autocomplete="false" name="dateend"
                                   placeholder="Period To"
                                   class="form-control datepicker custom_lead_filter"/>

                        </div>

                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>
                        <div class="ajax-data">
                        <?php if (!empty($incentive_payment_info)) { ?>
                            <table name="incentive_report"  id="incentive_report" class="table dt-table scroll-responsive">
                                <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th class="bold">Staff Name</th>
                                    <th class="bold">Finacial Year</th>
                                    <th class="bold">BEP</th>
                                    <th class="bold">Baby Sitting Loss</th>
                                    <th class="bold">Qualifying CTC</th>
                                    <th class="bold">Credit score</th>
                                    <th class="bold">Credit score as % of QCTC</th>
                                    <th class="bold">P/L over QCTC</th>
                                    <th class="bold">RM's Incentive for the FY</th>
                                    <!--<th class="bold">TLs' incentive for the FY</th>
                                    <th class="bold">GL's Bonus for the FY</th>-->
                                </tr>
                                </thead>
                                <tbody class="">
                                <?php foreach ($incentive_payment_info as $alllead) {
                                    
                                    ?>
                                    <tr class="">
                                        <td><?= @++$i; ?></td>
                                        <td> <?= $alllead->firstname; ?>
                                        </td>
                                        <td><?= $alllead->period_to; ?></td>
                                        <td for="bep"><?= $alllead->bep; ?> </td>
                                        <td for="baby_shitiing_loss">0
                                        </td>
                                        <td><?= $alllead->qualifying_ctc; ?>  </td>
                                        <td>
                                        <?php
                                        $this->db->select_sum('net_credit');
                                        $this->db->where('converted_by', $alllead->salary_grade);
                                        $this->db->where('status', "Verified");
                                        $creditscore =  $this->db->get('tblbusiness')->result();
                                        ?>
                                        <?= number_format($creditscore[0]->net_credit, 2, '.', ''); ?>
                                        </td>
                                        <td> </td>
                                        <td class="pl_over"><?= $alllead->pl_over_ctc; ?></td>
                                        <td id=""><b><?= $alllead->rm_incentive_fy; ?></b></td>
                                      <!-- <td class="teamincentive" ><?
                                       $this->db->select('GROUP_CONCAT(staffid) as stfId');
                                       $this->db->where_in('reporting_manager', $alllead->salary_grade);
                                       $underteamids =  $this->db->get('tblstaff')->result_array();
                                       if(!empty($underteamids[0]['stfId'])) 
                                       {
                                       $arr1 = $underteamids[0]['stfId'];
                                       $emp1=$underteamids[0];
                                       $firstArray0= $emp1['stfId'];
                                       $firstArray0=explode(',',$firstArray0);
                                        array_push($firstArray0,$alllead->salary_grade);
                                        implode( ',', $firstArray0 ); 
                                        $finalone = implode( ',', $firstArray0 );
                                        
                                        
                                        
                                        
                                       
                                           
                                        
                                       
                                        $this->db->select('GROUP_CONCAT(staffid) as stfId');
                                        $this->db->where("reporting_manager IN (".$finalone.")",NULL, false);
                                        //$this->db->where_in('reporting_manager', $finalone);
                                           $allunderteamids =  $this->db->get('tblstaff')->result_array();
                                       
                                        $emp2=$allunderteamids[0];
                                           $firstArray1= $emp2['stfId'];
                                       $firstArray1=explode(',',$firstArray1);
                                        array_push($firstArray1,$alllead->salary_grade);
                                        implode( ',', $firstArray1 ); 
                                        $arr2 = implode( ',', $firstArray1 );
                                        
                                        
                                        
                                        
                                        $this->db->select_sum('bep');
                                       
                                        $this->db->where("salary_grade IN (".$arr2.")",NULL, false);
                                        $teamcostingres =  $this->db->get('tbl_salary_template')->result();
                                        $teamcosting =  number_format($teamcostingres[0]->bep, 2, '.', '');
                                        
                                        
                                       
                                        $this->db->select_sum('net_credit');
                                       
                                        $this->db->where("converted_by IN (".$arr2.")",NULL, false);
                                        $this->db->where('status', "Verified");
                                        $creditscore1 =  $this->db->get('tblbusiness')->result();
                                        
                                        $teamcreditscore =   number_format($creditscore1[0]->net_credit, 2, '.', '');
                                       
                                        $percentagediffence = $teamcreditscore-$teamcosting;
                                       
                                    
                                     $percentageslab = ($percentagediffence/$teamcosting)*100;
                                    if(($percentageslab > "0") && ($percentageslab <= "10")  )
                                        {
                                            $profit =  ($percentagediffence*5)/100;
                                        }
                                        
                                        else if(($percentageslab > "10") && ($percentageslab <= "20")  )
                                        {
                                            $profit =  ($percentagediffence*10)/100;
                                        }
                                        
                                        else if(($percentageslab > "20") && ($percentageslab <= "30")  )
                                        {
                                            $profit =  ($percentagediffence*15)/100;
                                        }
                                        
                                        else if(($percentageslab > "30") && ($percentageslab <= "40")  )
                                        {
                                            $profit =  ($percentagediffence*20)/100;
                                        }
                                        
                                        else if(($percentageslab > "40") && ($percentageslab <= "50")  )
                                        {
                                            
                                           $profit =  ($percentagediffence*25)/100;
                                           
                                        }
                                        
                                        else if((percentageslab > "50")   )
                                        {
                                           $profit =  ($percentagediffence*40)/100;
                                        }
                                        
                                        else 
                                        {
                                            $profit =  ($percentagediffence*0)/100;
                                        }
                                        
                                        echo $profit;
                                       }
                                       ?></td>
                                        <td><?= $alllead->gl_incentive_fy; ?></td>-->
                                    </tr>
                                    <?php
                                    $alllead->bep
                                    ?>
                                    
                                    
                                <?php 
                                
                                $totbep = $totbep + $alllead->bep;
                                $toqctc = $toqctc + $alllead->qualifying_ctc;
                                $tocredit = $tocredit +  number_format($creditscore[0]->net_credit, 2, '.', '');
                                        }
                                        $totalqctc = $toqctc;
                                        $totalbep = $totbep;
                                        $totalcredit = $tocredit;
                                        ?>
                                        <tr>
                                    <th style="font-weight: bold;"><span id="totalro">Total</span></th>
                                    <th ></th>
                                    <th ></th>
                                    <th style="font-weight: bold;"><span id="sum_bep_amount"><?php echo $totalbep; ?></span></th>
									<th ></th>
									<th style="font-weight: bold;"><span id= "sum_qctc_amount"> <?php echo $totalqctc; ?></span></th>
                                    <th style="font-weight: bold;"><span id= "sum_credit_amount"> <?php echo $totalcredit; ?></span></th>
									<th style="font-weight: bold;"><span id= "sum_csap_amount"></span></th>
									<th style="font-weight: bold;"><span id= "sum_plfperiod_amount"></span></th>
									
                                    
									<th style="font-weight: bold;"><span id= "sum_rminc_amount"></span></th>
									<th ></th>
                                    <th ></th>
									<th ></th>
								
								</tr>
                                
                                </tbody>
                            </table>
                            <?php
                        } else {
                            echo "No Incentive Report Found";
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
      const filterrm = $('#filterrm').val(),
            datestart = $('#datestart').val();
            dateend = $('#dateend').val();
        url = "<?= base_url('admin/payroll/custom_incentive_filter') ?>";
        $.get(url, {
                filterrm: filterrm,
                datestart: datestart,
                dateend: dateend
            },
            function (res) {
                $('.ajax-data').html(res);
            })
    });
</script>
<script>
    $(document).ready(function() {
    $('.pl_over:contains("-")').css('color', 'red');
  $('.status:contains("Received")').css('color', 'green'); 
  $('.teamincentive:contains("-")').hide();
});
</script>

<script>

    var table = document.getElementById("incentive_report");
    var newsalqctc = 0;
    var plfperiod = 0;
    var bsloss = 0;
    var csap = 0;
    var rminc = 0;
    var tlinc = 0;
    
    for (var i = 1; i < table.rows.length -1; i++) {
   var firstCol = table.rows[i].cells[3]; //first column
    console.log(firstCol.innerHTML);
    
    
        
        var exp_annual_ctc = table.rows[i].cells[3].innerHTML;
         var   baby_shitiing_loss = table.rows[i].cells[4].innerHTML;
          var expctc = parseFloat(exp_annual_ctc.replace(",", ""));
          var   credit_score = table.rows[i].cells[6].innerHTML;
          var credit_score_fy = parseFloat(credit_score.replace(",", ""));  
          bbys = parseFloat(baby_shitiing_loss);
          
          //alert(bbys);
            calcPrice = expctc + bbys;
            
            discountPrice = calcPrice.toFixed(2);
            
            //alert(credit_score_fy);
            
            percentagediff = credit_score_fy - discountPrice;
            
            percentagediffence = percentagediff.toFixed(2);
            
            //alert(percentagediffence);
            percentageslab = (percentagediffence/discountPrice)*100;
            percentageslabdis = percentageslab.toFixed(2);
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
            
         //alert(percentageslab);
         
        //$('input[name=\'cs_per_qctc[]\']').val(percentageslab);
        table.rows[i].cells[5].innerHTML = discountPrice;
        table.rows[i].cells[7].innerHTML = percentageslabdis;
        table.rows[i].cells[8].innerHTML = percentagediffence;
        table.rows[i].cells[9].innerHTML = totalprofit;
        
//newsalqctc += discountPrice;     
newsalqctc += parseInt(discountPrice);
plfperiod += parseInt(percentagediffence); 
csap += parseInt(percentageslabdis);  
rminc += parseInt(totalprofit);
}

var sum_qctc_amount = + newsalqctc;
$('#sum_qctc_amount').html(sum_qctc_amount);

var sum_plfperiod_amount = + plfperiod;
$('#sum_plfperiod_amount').html(sum_plfperiod_amount);

var sum_csap_amount = + csap;
$('#sum_csap_amount').html(sum_csap_amount);

var sum_rminc_amount = + rminc;
$('#sum_rminc_amount').html(sum_rminc_amount);
     


</script>



</body>
</html>
