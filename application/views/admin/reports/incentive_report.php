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
                                    Back </a>
                        </div>
						<a href="<?php echo admin_url("reports/tl_incentive"); ?>" class="btn btn-default">View Team Leader Incentive</a>
                        <p><i class="fa fa-star"></i> Please Select All filters for Filtered Result</p>
                        <select id="filterrm" multiple data-none-selected-text="Filter By RM"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php if (!empty($rmconverted)) {
                                foreach ($rmconverted as $rmconverteds) { ?>
                                    <option value="<?= $rmconverteds->staffid; ?>"><?= $rmconverteds->firstname; ?></option>
                                <?php }
                            } ?>
                        </select>
                        <?php 
                            /*$start_date = date('Y').'-04-01';
                            $end_date = ($start_date+1).'-03-31';*/
                        ?>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="datestart" autocomplete="false" name="datestart" placeholder="Period From" class="form-control datepicker custom_lead_filter"  />

                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="dateend" autocomplete="false" name="dateend" placeholder="Period To" class="form-control datepicker custom_lead_filter" />

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
                                    <th class="bold">P/L over QCTC</th>
                                    <th class="bold">Credit score as % of QCTC</th>
                                    <th class="bold">RM's Incentive for the FY</th>
                                </tr>
                                </thead>
                                <tbody class="">
                                <?php
								/* echo "<pre>";
								print_r($incentive_payment_info);
								exit(); */
                                foreach ($incentive_payment_info as $alllead) {
                                   if($alllead->count<=1){
                                  
                                ?>
                                    <tr class="">
                                        <td><?= @++$i; ?></td>
                                        <td> <?= $alllead->firstname; ?>
                                        </td>
                                        <td><?= $alllead->period_to; ?></td>
                                        <td for="bep"><?= $alllead->bep; ?> </td>
                                        <td for="baby_shitiing_loss"><?= $alllead->baby_shitiing_loss; ?>
                                        </td>
                                        <td><?= $alllead->qualifying_ctc; ?>  </td>
                                        <td class="text-center">
                                        <?php
                                            $this->db->select_sum('net_credit');
                                            $this->db->where('converted_by', $alllead->salary_grade);
                                            $this->db->where('status', "Verified");
    										$this->db->where('transaction_date >=', $alllead->start_finacial_year);
    										$this->db->where('transaction_date <=', $alllead->last_finacial_year);
                                            $creditscore =  $this->db->get('tblbusiness')->result();
                                            ?>
                                            <?= number_format($creditscore[0]->net_credit, 2, '.', ''); ?>
                                        </td>
                                        <td class="text-center pl_over">
                                        <?php 
                                          $creadit =   $creditscore[0]->net_credit - $alllead->qualifying_ctc;
                                          echo number_format($creadit, 2, '.', '');
                                        ?>
                                        </td>
                                        <td class="text-center pl_over">
                                        <?php
                                          $profit_per=($creadit*100)/ $alllead->qualifying_ctc;
                                          if($profit_per<0){
                                             $profit_per=0;
                                          }
                                          echo number_format($profit_per, 2, '.', '');
                                        ?>
                                        </td>
                                        <td class="pl_over">
                                        <?php //echo $alllead->salary_grade;
                                            $this->db->select('id');
                                            $this->db->where('staff_id',$alllead->salary_grade);
                                            $this->db->where('departments',12);
                                            $this->db->where('inc_type',1);
                                            $this->db->where("datestart>=",  $alllead->start_finacial_year);
                                            $this->db->where("dateend<=", $alllead->last_finacial_year);
                                            $incentive =  $this->db->get('tbl_incentive_select_staff')->result();
                                            //$profit_per
                                            if($incentive){
                                                $this->db->where('incentive_id',$incentive[0]->id);
                                                $this->db->where('credit_score<=',$profit_per);
                                                $this->db->order_by('id', 'DESC');
                                                $this->db->limit(1);  
                                                $incentive_data =  $this->db->get('tbl_incentice_select')->result();
                                                $incentive_data[0]->incantive;

                                                if($profit_per<=0){
                                                    $inc = ($creadit*0)/100;
                                                }else{
                                                    $inc = ($creadit*$incentive_data[0]->incantive)/100;
                                                }
                                            }else{
                                              $inc = 0;
                                            }

                                            /*$inc = ($creadit*$incentive_data[0]->incantive)/100;
                                            if($inc<1){ 
                                                echo $inc=0;
                                            }else{ 
                                                echo $inc;
                                            }*/

                                            echo number_format($inc, 2, '.', '');
                                        ?>   
                                        </td>
                                    </tr>
                                    <?php }
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





</body>
</html>
