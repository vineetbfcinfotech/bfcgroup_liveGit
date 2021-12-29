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

                                   onclick=""> Back </a>
                                   <br>
                                <!-- <select id="filterrm" multiple data-none-selected-text="Filter By RM"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                                    <?php if (!empty($welthteam)) {
                                          foreach($welthteam as $teamLead){
                                          if($teamLead->teamcount >1){
                                    ?>
                                            <option value=""><?= $teamLead->firstname. " ".$teamLead->lastname ?></option>
                                    <?php 
                                          }
                                        }
                                      } 
                                    ?>
                                </select> -->
                                <?php 
                                  $start_date = "2020-04-01";
                                  $end_date = "2021-03-31";
                                ?>
                                
                                <div class="dropdown bootstrap-select show-tick">
                                    <input type="text" id="datestart" autocomplete="false" name="datestart"
                                           placeholder="Period From"
                                           class="form-control datepicker custom_lead_filter" value="" />
                                </div>
                                <div class="dropdown bootstrap-select show-tick">
                                    <input type="text" id="dateend" autocomplete="false" name="dateend"
                                           placeholder="Period To"
                                           class="form-control datepicker custom_lead_filter" value=""/>
                                </div>

                                </div>

                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
                        <div class="ajax-data">
                        <?php //if (!empty($incentive_payment_info)) { ?>
                          <table name="incentive_report"  id="incentive_report" class="table dt-table scroll-responsive">
                            <thead>
                              <tr>
                              <th><?php echo _l('id'); ?></th>
                              <th class="bold">GL/TL Name</th>
                              <th class="bold">Finacial Year</th>
                              <th class="bold">GROUP/TEAM BEP</th>
                              <th class="bold">GROUP/TEAM Baby Sitting Loss</th>
                              <th class="bold">Qualifying CTC Of GROUP/Team</th>
                              <th class="bold">Credit score Of GROUP/Team</th>
                              <th class="bold">Credit score as % of QCTC Of GROUP/Team</th>
                              <th class="bold">Profit / Lose</th>
                              <th class="bold">Incentive </th>

                                </tr>

                                </thead>

                                <tbody class="">

                                

                        <?php 

                        /* $arr=herapermission();

                        $teamMembers = get_team_members($arr); */

                        $i = 1; 

                        //if(count($teamMembers) > 1){
						           // $transctiondatestart = '';   $transctiondateend = '';
                        foreach($welthteam as $teamLead){

                        
                        if($teamLead->teamcount >1){
                          
                        ?>

                           <tr>

                              <td><?= $i ?></td>

                              <td><?= $teamLead->firstname. " ".$teamLead->lastname ?></td>

                              <td><?= $end_date ?></td>

                              <td>
              							  <?= $teamLead->teamTotalBep; ?>
              							  </td>

                              <td>0</td>

                              <td><?php echo number_format((float)$teamLead->teamQulify_ctc, 2, '.', ''); ?></td>

                              <td><?=  number_format((float)$teamLead->teamTotalCredit, 2, '.', ''); ?></td>

                              <td class="pl_over"><?=  number_format((float)$teamLead->teamCreditPer, 2, '.', ''); ?> %</td>
                              <td class="pl_over"><?= number_format((float)$teamLead->creditprofit, 2, '.', ''); ?></td>
                              <td><?= number_format((float)$teamLead->teamTotalIncentive, 2, '.', ''); ?></td>

                           </tr>

                        <?php $i++; }}

                        //}

                        ?>
                          </tbody>
                          </table>
                          <?php

                        /* } else {

                            echo "No Incentive Report Found";

                        } */ ?>

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

  datestart = $('#datestart').val();
  dateend = $('#dateend').val();
  
  url = "<?= base_url('admin/reports/tl_incentive_filter') ?>";
  $.get(url, {
      datestart: datestart,
      dateend: dateend
  },
  function (res) { //alert(res);
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

