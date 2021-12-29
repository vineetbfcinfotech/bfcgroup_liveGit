<script type="text/javascript">
    function startTime() {
        var time = new Date();
        var date = time.getDate();
        var month = time.getMonth() + 1;
        var years = time.getFullYear();
        var hr = time.getHours();
        var hour = time.getHours();
        var min = time.getMinutes();
        var minn = time.getMinutes();
        var sec = time.getSeconds();
        var secc = time.getSeconds();
        if (date <= 9) {
            var dates = "0" + date;
        } else {
            dates = date;
        }
        if (month <= 9) {
            var months = "0" + month;
        } else {
            months = month;
        }
        var ampm = " PM "
        if (hr < 12) {
            ampm = " AM "
        }
        if (hr > 12) {
            hr -= 12
        }

        if (hr < 10) {
            hr = " " + hr
        }
        if (min < 10) {
            min = "0" + min
        }
        if (sec < 10) {
            sec = "0" + sec
        }
        document.getElementById('date').value = years + "-" + months + "-" + dates;
        document.getElementById('clock_time').value = hour + ":" + minn + ":" + secc;
        document.getElementById('txt').innerHTML = hr + ":" + min + ":" + sec + ampm;
        var t = setTimeout(function () {
            startTime()
        }, 500);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }
        ;  // add zero in front of numbers < 10
        return i;
    }
	var el = document.getElementById('myCoolForm');


    //return ConfirmDialog('Confirm', 'Are you sure?');
	  
	  $(document).ready(function(){
		  $(".clock_out_btn").click(function(){
			return confirm('Are you sure want to clock-out?');

		  });
		});
function addPossReason()
 {
   $('#add-poss-reason').modal('show');
 }
$(document).ready(function(){
	$(".clock_in_hide").click(function(){
	  $(".clock_in_hide").hide();
	});
});
</script>
<?php $currentDay =  date("l"); ?>
<div class="content-wrapper rjcont">
    <div class="content-heading row">
        <span class="rjmember">
        <?php echo _l($this->uri->segment(2));
        $user_id = $this->session->userdata('staff_user_id');
		$date = date("Y-m-d");
		
		$poss_info = $this->db->where('user_id', $user_id)->where('date', $date)->get('tbl_poss')->row();
		
		//echo $pages_array->slug2;
		//print_r($poss_info);exit("test");
        //$attendance_info = $this->db->where('user_id', $user_id)->get('tbl_attendance')->result();
		
		$attendance_info = $this->db->where('user_id', $user_id)->where('date_in', $date)->get('tbl_attendance')->row();
		//print_r($attendance_info); exit;
        foreach ($attendance_info as $v_info) {
            $all_clocking[] = $this->dashboard_model->check_by(array('attendance_id' => $v_info->attendance_id, 'clocking_status' => 1), 'tbl_clock');
        }
        if (!empty($all_clocking)) {
            foreach ($all_clocking as $v_clocking) {
                if (!empty($v_clocking)) {
                    $clocking = $v_clocking;
                }
            }
        }
        ?>
		<?php
		$workGroup = rander_getWorkGroup($user_id);
		$WfhDate = rander_getWfhDate($user_id);
		foreach($WfhDate as $WfhDate){
			if($WfhDate->duration == "sinlge_day"){
				$wfhdateenable[] = $WfhDate->start_date;
			}else{
				function getDatesFromRange($start, $end, $format = 'Y-m-d') {
					// Declare an empty array 
					$array = array();
					// Variable that store the date interval 
					// of period 1 day 
					$interval = new DateInterval('P1D');
					$realEnd = new DateTime($end); 
					$realEnd->add($interval);
					$period = new DatePeriod(new DateTime($start), $interval, $realEnd);
					// Use loop to store date into array 
					foreach($period as $date) {                  
						$array[] = $date->format($format);  
					}
					// Return the array elements 
					return $array; 
				}
				// Function call with passing the start date and end date 
				$wfhdateenable = getDatesFromRange($WfhDate->start_date, $WfhDate->end_date); 
			}
		}
		//print_r($wfhdateenable);exit;
		?>
        </span>
        <div class="pull-right data-hide" >
            <?php /* <form method="post" action="<?php echo base_url('admin/dashboard/set_clocking/') ?><?php */ ?>
            <form method="post" action="<?php echo base_url('admin/dashboard/set_attendance/') ?><?php
            if (!empty($clocking)) {
                echo $clocking->clock_id;
            }
            ?>">
                <div>
					<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <small class="text-sm">
                        &nbsp;<?php echo _l(date('l')) . ' ' . _l(date('jS')) . ' ' . _l(date('F')) . ' ' . date('\- Y,'); ?>
                        &nbsp;<?= _l('time') ?>
                        &nbsp;<span id="txt"></span></small>
                    <input type="hidden" name="clock_date" value="" id="date">
                    <input type="hidden" name="clock_time" value="" id="clock_time">
					<?php //echo $attendance_info->clocking_status; exit; ?>
                    <?php //if (!empty($clocking->clock_id)): ?>
                    <?php 
					if (empty($attendance_info) || $attendance_info->date_out_time != ""): ?>
                        <!-- <button 
                                class="btn btn-success  clock_in_button" <?php if(($attendance_info->date_out_time != "" || $workGroup->work_group == "A") && !in_array(date('Y-m-d'), $wfhdateenable) && ($currentDay != "Saturday")){ echo "disabled"; } ?> ><i
                                    class="fa fa-arrow-left"></i> <?= _l('clock_in')  ?>
                        </button>  --->
                        <button class="btn btn-success clock_in_button clock_in_hide" <?php if(($attendance_info->date_out_time != "" ) && !in_array(date('Y-m-d'), $wfhdateenable)){ echo "disabled"; } ?> ><i class="fa fa-arrow-left"></i> <?= _l('clock_in')  ?>
                        </button>
                    <?php else: ?>
                        <button onclick="myFunction()" 
                                class="btn btn-danger clock_in_button clock_out_btn"><i
                                    class="fa fa-sign-out"></i>  <?= _l('clock_out') ?>
                        </button>
						<?php if($poss_info != "" && $poss_info->stop_time == ""): ?> 
						<a href="<?php echo  base_url(); ?>admin/dashboard/addposs" onclick="return confirm('Are you sure ready for work?')" 
                                class="btn btn-warning clock_in_button"><i
                                    class="fa fa-exclamation-triangle"></i>  <?php echo "Resume"  ?>
                        </a>
						<?php else: ?>
						<a href="javascript:void();" id="" 
                                class="btn btn-info clock_in_button" onclick="<?php if($poss_info == "" && $poss_info->stop_time == ""){ echo "addPossReason()"; }else{ echo "disable"; } ?>" ><i
                                    class="fa fa-play"></i>  <?php echo "Pause"  ?>
                        </a>
						<?php endif; ?>
                    <?php endif; ?>
					
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add-poss-reason" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form autocomplete="off" action="<?php echo  base_url(); ?>admin/dashboard/addposs" id="ticket-service-form" method="post" accept-charset="utf-8">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title">Add Pause Reason</span>
                        <!-- <span class="add-title">New Product</span> -->
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="additional"></div>
                            <!-- <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Start Time</label>
                              <input type="text" id="poss_time" placeholder="Enter Start Time * " name="poss_time" class="form-control datetimepicker"  value="">
                            </div> -->
                            <div class="form-group" app-field-wrapper="name">
                              <label for="name" class="control-label">Reason</label>
                              <textarea id="poss_reason" name="poss_reason" class="form-control" placeholder="Enter Poss  Reason *" ></textarea>
                            </div> 
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div><!-- /.modal-content -->
            </form>        

          </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
	