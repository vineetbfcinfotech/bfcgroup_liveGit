<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body"><h2>Daily Lead Report</h2>
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
                        <div class="row leads-overview">
                            <?php if(is_admin() || is_headtrm()) { ?>
                            <div class="col-md-12"><h4 class="no-margin text-center">-: Overall Lead
                                    Summary <?= $show_date; ?>
                                    :- </h4>
                                <hr class="hr-panel-heading"/>
                            </div>
                            <?php
                              if ( !empty($summary) ) {
                                 foreach ($summary as $status) { ?>
                                     <div class="col-md-2 col-xs-6 border-right">
                                         <a style="color:<?php echo $status['color']; ?>" >
                                             <h3 class="bold"><?= $status['total']; ?></h3>
                                             <span><?php echo $status['name']; ?></span></a>
                                     </div>
                                 <?php }
								 ?>
								 <div class="col-md-2 col-xs-6 border-right">
                                         <a style="color:green" >
                                             <h3 class="bold"><?= $business_total[0]->tot_business; ?></h3>
                                             <span>Business Mobilized</span></a>
                                     </div>
							<?php		 
                              }
							  ?>
                            <?php } ?>

                           
                        </div>
                        <div class="row">
                        <div class="col-md-12">
                            <hr class="hr-panel-heading"/>
                            <h4 class="no-margin text-center">-: Staff Wise Lead Summary <?= $show_date; ?> :- </h4>
                            <hr class="hr-panel-heading"/>
                        </div>
                    </div>
                    
                    
                   <?php if ( !empty($staffList) ) {
                      foreach ($staffList as $staff)  {
                      $summaryStaff = get_leads_summary_staff_date_wise($staff['staffid'] , $start , $end);
                      $business_total_staff = get_tot_staff_business_count($staff['staffid'] , $start , $end);

		   
                      ?>
                           <div class="row leads-overview">
                            <div class="col-md-12"><h4 class="no-margin"> <strong> <?= ucfirst($staff['firstname']) . ' ' . ucfirst($staff['lastname']); ?> </strong></h4>
                                <hr class="hr-panel-heading"/>
                            </div>

                           <?php
                              if ( !empty($summaryStaff) ) {
                                 foreach ($summaryStaff as $status) { ?>
                                     <div class="col-md-2 col-xs-6 border-right">
                                         <a style="color:<?php echo $status['color']; ?>">
                                             <h3 class="bold"><?= $status['total']; ?></h3>
                                             <span><?php echo $status['name']; ?></span></a>
                                     </div>
                                 <?php }
								 ?>
								<div class="col-md-2 col-xs-6 border-right">
                                        <a style="color:green" >
                                             <h3 class="bold"><?= $business_total_staff[0]->tot_staff_business; ?></h3>
                                             <span>Business Mobilized</span>
										</a>
                                </div>
							   <?php	
                              } ?>
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
</body>
</html>