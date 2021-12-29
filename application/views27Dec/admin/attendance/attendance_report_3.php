<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-custom" data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong><?= _l('attendance_report') ?></strong>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form id="attendance-form" action="<?= base_url('admin/attendance/get_report_3'); ?>"
                              method="post" class="form-horizontal">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                   value="<?= $this->security->get_csrf_hash(); ?>"/>
                            <div class="col-sm-5 col-md-offset-3">
                               <?= render_select('staff_id', $staff_members, array('staffid', 'firstname'), 'choose_staff'); ?>
                            </div>
                            <div class="col-sm-5 col-md-offset-3">
                                <div class="form-group" app-field-wrapper="date">
                                    <label for="date" class="control-label"><?= _l('month'); ?><span
                                                class="required"> *</span></label>
                                    <div class="input-group date">
                                        <input type="text" id="date" name="date" class="form-control datepicker"
                                               value="">
                                        <div class="input-group-addon"><i class="fa fa-calendar calendar-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 pull-right">
                                <div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-5 ">
                                        <button type="submit" id="sbtn"
                                                class="btn btn-primary"><?= _l('search') ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
               <?php
                  if ((!empty($date)) && !empty($attendace_info)): ?>
                <div class="row" id="printableArea">
                    <div class="col-sm-12 std_print">
                        <div class="panel panel-custom ">
                            <div class="panel-heading" style="">
                                <h4 class="panel-title">
                                    <strong><?= _l('works_hours_deatils') . ' ' ?><?php echo $month; ?></strong>
                                    <div class="show_print">
                                    
                                    </div>
                                </h4>
                            </div>
                            <div class="panel-group" id="accordion" style="margin:8px 5px" role="tablist"
                                 aria-multiselectable="true">
                               <?php
                                  foreach ($attendace_info as $name => $v_attendace_info):
                                     ?>
                                      <div class="panel panel-default" style="border-radius: 0px ">
                                          <div class="panel-heading" style="border-radius: 0px;border: none" role="tab">
                                              <h4 class="panel-title">
                                                  <a data-toggle="collapse" data-parent="#accordion"
                                                     href="#<?php echo $name ?>"
                                                     aria-expanded="true" aria-controls="collapseOne">
                                                     <?php echo $employee[$name]['full_name'] ?>
                                                  </a>
                                              </h4>
                                          </div>
                                          <div id="<?php echo $name ?>" class="panel-collapse collapse" role="tabpanel"
                                               aria-labelledby="headingOne">
                                              <div class="panel-body">
                                                 <?php
                                                    if (!empty($v_attendace_info)): foreach ($v_attendace_info as $week => $v_attendace):
                                                       $total_hour = 0;
                                                       $total_minutes = 0;
                                                       ?>
                                                        <div class="panel-group" style="margin:8px 5px" id="accordion"
                                                             role="tablist"
                                                             aria-multiselectable="true">
                                                            <div class="panel panel-default"
                                                                 style="border-radius: 0px ">
                                                                <div class="panel-heading"
                                                                     style="border-radius: 0px;border: none"
                                                                     role="tab"
                                                                     id="headingOne">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse"
                                                                           data-parent="#<?php echo $name ?>"
                                                                           href="#<?php echo $name . $week; ?>"
                                                                           aria-expanded="true"
                                                                           aria-controls="collapseOne">
                                                                            <strong><?= _l('week') ?>
                                                                                : <?php echo $week; ?> </strong>
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="<?php echo $name . $week; ?>"
                                                                     class="panel-collapse collapse "
                                                                     role="tabpanel" aria-labelledby="headingOne">
                                                                    <div class="panel-body">
                                                                        <table class="table table-bordered table-hover">
                                                                            <thead>
                                                                            <tr>
                                                                                <th><?= _l('clock_in_time') ?></th>
                                                                                <th><?= _l('clock_out_time') ?></th>
                                                                                <th><?= _l('ip_address') ?></th>
                                                                                <th><?= _l('hours') ?></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php
                                                                               $total_hh = 0;
                                                                               $total_mm = 0;
                                                                               if (!empty($v_attendace)):
                                                                                  $hourly_leave = null;
                                                                                  
                                                                                  foreach ($v_attendace as $date => $v_mytime):
                                                                                     foreach ($v_mytime as $hmytime) {
                                                                                        if ($hmytime->attendance_status == 1) {
                                                                                           if (!empty($hmytime->leave_application_id)) { // check leave type is hours
                                                                                              $is_hours = get_row('tbl_leave_application', array('leave_application_id' => $hmytime->leave_application_id));
                                                                                              if (!empty($is_hours) && $is_hours->leave_type == 'hours') {
                                                                                                 $hourly_leave = "<small class='label label-pink text-sm' data-toggle='tooltip' data-placement='top'  title='" . _l('hourly') . ' ' . _l('leave') . ": " . $is_hours->hours . ":00" . ' ' . _l('hour') . "'>" . _l('hourly') . ' ' . _l('leave') . "</small>";
                                                                                              }
                                                                                           }
                                                                                        }
                                                                                     }
                                                                                     ?>
                                                                                      <td colspan="4"
                                                                                          style="background: rgba(233, 237, 228, 0.73);font-weight: bold"><?php echo $date; ?>
                                                                                          <span class="pull-right"><?= $hourly_leave ?></span>
                                                                                      </td>
                                                                                     
                                                                                     <?php
                                                                                     foreach ($v_mytime as $mytime) {
                                                                                        if ($mytime->attendance_status == 1) {
                                                                                           ?>
                                                                                            <tr>
                                                                                            <td><?php echo display_time($mytime->clockin_time); ?></td>
                                                                                            <td><?php
                                                                                                  if (empty($mytime->clockout_time)) {
                                                                                                     echo '<span class="text-danger">' . _l('currently_clock_in') . '<span>';
                                                                                                  } else {
                                                                                                     if (!empty($mytime->comments)) {
                                                                                                        $comments = ' <small> (' . $mytime->comments . ')</small>';
                                                                                                     } else {
                                                                                                        $comments = '';
                                                                                                     }
                                                                                                     echo display_time($mytime->clockout_time) . $comments;
                                                                                                  }
                                                                                               ?>
                                                                                            </td>
                                                                                            <td><?= $mytime->ip_address ?></td>
                                                                                            <td><?php
                                                                                                  if (!empty($mytime->clockout_time)) {
                                                                                                     // calculate the start timestamp
                                                                                                     $startdatetime = strtotime($mytime->date_in . " " . $mytime->clockin_time);
                                                                                                     // calculate the end timestamp
                                                                                                     $enddatetime = strtotime($mytime->date_out . " " . $mytime->clockout_time);
                                                                                                     // calulate the difference in seconds
                                                                                                     $difference = $enddatetime - $startdatetime;
                                                                                                     
                                                                                                     $years = abs(floor($difference / 31536000));
                                                                                                     $days = abs(floor(($difference - ($years * 31536000)) / 86400));
                                                                                                     $hours = abs(floor(($difference - ($years * 31536000) - ($days * 86400)) / 3600));
                                                                                                     $mins = abs(floor(($difference - ($years * 31536000) - ($days * 86400) - ($hours * 3600)) / 60));#floor($difference / 60);
                                                                                                     $total_mm += $mins;
                                                                                                     $total_hh += $hours;
                                                                                                     echo $hours . " : " . $mins . " m";
                                                                                                     
                                                                                                     // output the result
                                                                                                  }
                                                                                               ?></td>
                                                                                        <?php } elseif ($mytime->attendance_status == 'H') { ?>
                                                                                            <tr>
                                                                                                <td colspan="4"
                                                                                                    style="text-align: center">
                                                                            <span
                                                                                    style="padding:5px 109px; font-size: 12px;"
                                                                                    class="label label-info std_p"><?= _l('holiday') ?></span>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php } elseif ($mytime->attendance_status == '3') { ?>
                                                                                            <tr>
                                                                                                <td colspan="4"
                                                                                                    style="text-align: center">
                                                                            <span
                                                                                    style="padding:5px 109px; font-size: 12px;"
                                                                                    class="label label-warning std_p"><?= _l('on_leave') ?></span>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php } elseif ($mytime->attendance_status == '0') { ?>
                                                                                            <tr style="">
                                                                                                <td colspan="4"
                                                                                                    style="text-align: center">
                                                                            <span
                                                                                    style="padding:5px 109px; font-size: 12px;"
                                                                                    class="label label-danger std_p"><?= _l('absent') ?></span>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php } else { ?>
                                                                                            <tr>
                                                                                                <td colspan="4"
                                                                                                    style="text-align: center">
                                                                            <span style=" font-size: 12px;"
                                                                                  class=" std_p"><?= _l('no_data_available') ?> </span>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                         </tr>
                                                                                     <?php }; ?>
                                                                                     <?php
                                                                                     $hourly_leave = null;
                                                                                  endforeach; ?>
                                                                                   <table>
                                                                                       <tr>
                                                                                           <td colspan="2"
                                                                                               class="text-right">
                                                                                               <strong
                                                                                                       style="margin-right: 10px; "><?= _l('total_working_hour') ?>
                                                                                                   : </strong>
                                                                                           </td>
                                                                                           <td>
                                                                                              <?php
                                                                                                 if ($total_mm > 59) {
                                                                                                    $total_hh += intval($total_mm / 60);
                                                                                                    $total_mm = intval($total_mm % 60);
                                                                                                 }
                                                                                                 echo $total_hh . " : " . $total_mm . " m";
                                                                                              ?>
                                                                                           </td>
                                                                                       </tr>
                                                                                   </table>
                                                                               <?php else: ?>
                                                                                   <tr>
                                                                                       <td colspan="6">
                                                                                          <?= _l('nothing_to_display') ?>
                                                                                       </td>
                                                                                   </tr>
                                                                               <?php endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    <?php endif; ?>
                                              </div>
                                          </div>
                                      </div>
                                  
                                  <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                   <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
