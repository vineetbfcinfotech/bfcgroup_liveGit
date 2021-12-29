<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <strong>Mark Attendance</strong>
                                <div class="pull-right ">
                                    <form action="<?php echo base_url() ?>admin/attendance/update_clock" method="post">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                        <div class="checkbox checkbox-inline">
                                            <input type="checkbox" id="select_all_clock_in" class="clock_in"
                                                   data-toggle="tooltip" data-placement="top"
                                                   data-original-title="Mark all clock in who did not clock in yet."><label
                                                    for="select_all_clock_in"><?= _l('mark_all_clock_in'); ?></label>
                                        </div>
                                        <div class="checkbox checkbox-inline">
                                            <input type="checkbox" id="select_all_clock_out" class="clock_out" data-toggle="tooltip"
                                                   data-placement="top"
                                                   data-original-title="Mark all clock out who already clock in"><label
                                                    for="select_all_clock_out"><?= _l('mark_all_clock_out'); ?></label>
                                        </div>
                                        <div class="checkbox checkbox-inline">
                                            <input type="checkbox" id="select_all" data-toggle="tooltip" data-placement="top" data-original-title="Mark all clock in and clock out"><label
                                                    for="select_all"><?= _l('mark_all'); ?></label>
                                        </div>
                                        <button type="submit" class="btn btn-primary"><?= _l('update') ?></button>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading"/>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered roles no-margin">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= _l('staff_email'); ?></th>
                                    <th><?= _l('staff_name'); ?></th>
                                    <th><?= _l('clocking_hours'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ((!empty($date)) && !empty($attendace_info)) {
                                   $total_hour = 0;
                                   $total_minutes = 0;
                                   foreach ($attendace_info
                                   
                                   as $nkey => $v_attendace_info) {
                                   $already_clock = null;
                                   $currently_clock_in = null;
                                   $clock_id = null;
                                   $holiday = null;
                                   $name = 'clock_in';
                                   $total_hh = 0;
                                   $total_mm = 0;
                                   if (!empty($v_attendace_info)) {
                                      foreach ($v_attendace_info as $v_mytime) {
                                         if ($v_mytime->attendance_status == 1) {
                                            if (!empty($v_mytime->clockout_time)) {
                                               $startdatetime = strtotime($v_mytime->date_in . " " . $v_mytime->clockin_time);
                                               $enddatetime = strtotime($v_mytime->date_out . " " . $v_mytime->clockout_time);
                                               $difference = $enddatetime - $startdatetime;
                                               $years = abs(floor($difference / 31536000));
                                               $days = abs(floor(($difference - ($years * 31536000)) / 86400));
                                               $hours = abs(floor(($difference - ($years * 31536000) - ($days * 86400)) / 3600));
                                               $mins = abs(floor(($difference - ($years * 31536000) - ($days * 86400) - ($hours * 3600)) / 60));#floor($difference / 60);
                                               $total_mm += $mins;
                                               $total_hh += $hours;
                                               $already_clock = true;
                                            } else {
                                               $currently_clock_in = '<span style="padding:5px 75px; font-size: 12px;" class="label label-purple std_p">' . _l('currently_clock_in') . '</span>';
                                               $clock_id = $v_mytime->clock_id;
                                               $name = 'clock_out';
                                            }
                                         } elseif ($v_mytime->attendance_status == 'H') {
                                            $holiday = '<span style="padding:5px 109px; font-size: 12px;" class="label label-info std_p">' . _l('holiday') . '</span>';
                                         } elseif ($v_mytime->attendance_status == '3') {
                                            $holiday = '<span style="padding:5px 109px; font-size: 12px;" class="label label-warning std_p">' . _l('on_leave') . '</span>';
                                         } elseif ($v_mytime->attendance_status == '0') {
                                            $holiday = '<span style="padding:5px 109px; font-size: 12px;" class="label label-danger std_p">' . _l('absent') . '</span>';
                                         }
                                      }
                                   }
                                ?>
                                <tr>
                                    <td>

                                        <div class="checkbox c-checkbox checkbox-inline"
                                        ">
                                        <input type="checkbox" class="<?= $name ?>"
                                               name="<?= $name; ?>[]"
                                               value="<?= $users[$nkey]['staffid']; ?>">
                                        <label></label>
                        </div>
                        <input type="hidden" name="<?= $users[$nkey]['staffid']; ?>"
                               value="<?= $clock_id; ?>">

                        </td>
                        <td><?= $users[$nkey]['email']; ?></td>
                        <td><?= $users[$nkey]['full_name']; ?></td>
                        <td>
                           <?php
                              if (!empty($already_clock)) {
                                 if ($total_mm > 59) {
                                    $total_hh += intval($total_mm / 60);
                                    $total_mm = intval($total_mm % 60);
                                 }
                                 echo $total_hh . " : " . $total_mm . " m" . '<br/>';
                              }
                              if (!empty($currently_clock_in)) {
                                 echo $currently_clock_in . ' ' . '<a style="padding:5px 10px; font-size: 12px;" class="label label-danger" href="' . base_url() . 'admin/dashboard/set_clocking/' . $clock_id . '/' . $users[$nkey]['staffid'] . '"' . '>' . _l('clock_out') . '</a>';
                              } else {
                                 echo $holiday . ' ' . '<a style="padding:5px 10px; font-size: 12px;" class="label label-success" href="' . base_url() . 'admin/dashboard/set_clocking/0/' . $users[$nkey]['staffid'] . '/1"' . '>' . _l('clock_in') . '</a>';
                              }
                           ?>
                        </td>
                        </tr>
                       <?php
                          }
                          } ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#select_all').change(function () {
            var c = this.checked;
            $(':checkbox').prop('checked', c);
        });
        // select select_all_view
        $(".clock_in").change(function () {
            $('.clock_in').prop("checked", this.checked);
        });
        // select select_all_view
        $(".clock_out").change(function () {
            $('.clock_out').prop("checked", this.checked);
        });
    });
</script>
</body>
</html>
