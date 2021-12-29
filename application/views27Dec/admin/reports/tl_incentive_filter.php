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
						
                        foreach($welthteam as $teamLead){

                        
                        if($teamLead->teamcount >1){
                        ?>

                           <tr>

                              <td><?= $i ?></td>

                              <td><?= $teamLead->firstname. " ".$teamLead->lastname ?></td>

                              <td><?= $end_date ?></td>

                              <td><?= $teamLead->teamTotalBep ?></td>

                              <td>0</td>

                               <td><?= number_format((float)$teamLead->teamQulify_ctc, 2, '.', ''); ?></td>

                              <td><?=  number_format((float)$teamLead->teamTotalCredit, 2, '.', ''); ?></td>

                              <td><?=  number_format((float)$teamLead->teamCreditPer, 2, '.', ''); ?> %</td>
                    <td><?= number_format((float)$teamLead->creditprofit, 2, '.', ''); ?></td>
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