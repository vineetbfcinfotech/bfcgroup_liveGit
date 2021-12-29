<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>Credit Report Summary</h3></center>
                        <hr class="hr-panel-heading">
                       <?= form_open('', array('method' => 'GET')); ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="start" id="start_date" class="form-control"
                                           placeholder="Select Start Date.." value="<?= @$start; ?>" autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="end" id="end_date" class="form-control datepicker"
                                           value="<?= @$end; ?>"
                                           data-format="dd-mm-yyyy" data-parsley-id="17"
                                           placeholder="Select End Date.." autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                               <?= form_submit(['value' => "Filter", 'class' => 'btn btn-info']); ?>
                            </div>
                        </div>
                       <?= form_close(); ?>
                        <br>
                        
                        <div class="row">
                        <div class="col-md-12">
                            <hr class="hr-panel-heading"/>
                            <h4 class="no-margin text-center">-: Staff Wise Credit Report  Summary <?= $show_date; ?> :- </h4>
                            <hr class="hr-panel-heading"/>
                        </div>
                    </div>
                    
                    
                   <?php if ( !empty($staffList) ) {
                      foreach ($staffList as $staff)  { ?>
                          <div class="row leads-overview">
                              <h4 style="text-indent: 15px">
                                  <strong><?= ucfirst($staff['firstname']) . ' ' . ucfirst($staff['lastname']); ?></strong>
                              </h4>
                              
                              <hr class="hr-panel-heading"/>
                              <div class="col-md-12">
                              
                           <table class="table  scroll-responsive   no-footer" id="DataTables_Table_<?= @++$i; ?>" role="grid" aria-describedby="DataTables_Table_<?= @++$j; ?>_info">
                               
                                  <tr><td><b>Product</b></td>   
                                  <?php
                                $staffLeadStatus = get_credit_summary_satffwise($staff['staffid'], @$start, @$end);
                              //  print_r($staffLeadStatus);
                                if ( !empty($staffLeadStatus) ) {
                                   foreach ($staffLeadStatus as $status) { ?>
                                       <td>
                                           <b>
                                              
                                          
                                           <span style="color:<?php echo $status['color']; ?>"
                                                 class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>"><?php echo $status['name']; ?></span>
                                      </b> 
                                              
                                       </td>
                                   <?php }
                                } ?></tr>
                                  <tr><td><b>Transaction Amount</b></td>
                                  
                                    <?php
                                $staffLeadStatus = get_credit_summary_satffwise($staff['staffid'], @$start, @$end);
                              //  print_r($staffLeadStatus);
                                if ( !empty($staffLeadStatus) ) {
                                   foreach ($staffLeadStatus as $status) { ?>
                                       <td>
                                              
                                          
                                     
                                              <?php
                                                 if ( isset($status['percent']) ) {
                                                    echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                 } else {
                                                    echo $status['total'];
                                                 }
                                              ?>
                                          
                                           
                                       </td>
                                   <?php }
                                } ?>
                                  
                                  
                                  </tr>
                                  <tr><td><b>Net Credit Score</b></td>
                                  <?php
                                $staffLeadStatus = get_credit_summary_netcredit_satffwise($staff['staffid'], @$start, @$end);
                              //  print_r($staffLeadStatus);
                                if ( !empty($staffLeadStatus) ) {
                                   foreach ($staffLeadStatus as $status) { ?>
                                       <td>
                                              <?php
                                                 if ( isset($status['percent']) ) {
                                                    echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                 } else {
                                                    echo $status['total'];
                                                 }
                                              ?>
                                           </td>
                                          
                                       </div>
                                   <?php }
                                } ?>
                                  
                                  
                                  </tr>
                                  
                                  <tr>
                                      <td><b>Total Net Credit</b></td>
                                      <?
                                      $staffLeadStatus = get_total_credit_summary_netcredit_satffwise($staff['staffid'], @$start, @$end);
                              //  print_r($staffLeadStatus);
                                if ( !empty($staffLeadStatus) ) {
                                   ?>
                                       <td>
                                              <?php
                                              
                                              echo $staffLeadStatus[0]['total'];
                                             ?>
                                           </td>
                                          
                                       </div>
                                   <?php 
                                } ?>
                                  </tr>
                              </table>
                            
                                </div>
                          </div>
                          <hr class="hr-panel-heading"/>
                      <?php }
                   } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    
</script>
</body>
</html>
