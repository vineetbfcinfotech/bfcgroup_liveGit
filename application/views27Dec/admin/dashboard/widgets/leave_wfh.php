<div class="widget" id="widget-<?php echo basename(__FILE__,".php"); ?>" data-name="<?php echo _l('user_widget'); ?>">
<?php $CI=&get_instance(); ?>

   <div class="panel_s leave-wfh">
      <div class="panel-body home-activity">
         <div class="widget-dragger"></div>
         <div class="horizontal-scrollable-tabs">
            <div class="scroller scroller-left arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="scroller scroller-right arrow-right"><i class="fa fa-angle-right"></i></div>
            <div class="horizontal-tabs">
				<ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
				   <li role="presentation" class="active">
					  <a href="#leave" role="tab" data-toggle="tab" aria-expanded="true">
					  <i class="fa fa-house menu-icon"></i>Applied Leave</a>
				   </li>
				   <li role="presentation">
					  <a href="#wfh_tab" aria-controls="home_announcements" role="tab" data-toggle="tab">
					  <i class="fa fa-briefcase menu-icon"></i>Applied WFH</a>
				   </li>
				</ul>
				<div class="tab-content" id="myTabContent">
				  <div class="tab-pane fade active in" id="leave" role="tabpanel" aria-labelledby="leave-tab">
					 <table class="table table-hover">
						<thead>
						  <tr>
							<th>Name</th>
							<th>Subject</th>
							<th>Leave Category</th>
							<th>Start Date</th>
							<th>Status</th>
						  </tr>
						</thead>
						<tbody>
						<?php
						if(is_admin() || is_sub_admin()){
							$arr = 1;
						}else{
							$arr=herapermission();
						}
						$leaveData = rander_getLeave($arr);
						if(!empty($leaveData)){
						foreach($leaveData as $leaves){
						?>
						  <tr>
							<td><?= $leaves->full_name; ?></td>
							<td><p data-toggle="tooltip" data-placement="top" title="<?= $leaves->reason; ?>"><?php echo  ucfirst(substr($leaves->reason,0,10)); ?></p></td>
							<td><?php $catId=$leaves->leave_category_id;
                                     $category=rander_getLeaveCategory($catId);
                                     if($category)
                                     {
                                      echo  $category[0]->leave_category; 
                                     }
                                     else
                                     {
                                        echo "Special Leave";
                                     } ?></td>
							<td><?= $leaves->leave_start_date; ?></td>
							<td><?php
							if($leaves->application_status == 1){
								echo "Pending";
							}elseif($leaves->application_status == 2){
								echo "Accepted";
							}elseif($leaves->application_status == 3){
								echo "Rejected";
							}
							?></td>
						  </tr>
						<?php } }else{ ?>
							<tr>
								<td colspan="5">Not Applied Now</td>
							</tr>
						<?php } ?>
						</tbody>
					  </table>
				  </div>
				  
				  <div class="tab-pane fade" id="wfh_tab" role="tabpanel" aria-labelledby="wfh_tab-tab">
					 <table class="table table-hover">
						<thead>
						  <tr>
							<th>Name</th>
							<th>Subject</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Status</th>
						  </tr>
						</thead>
						<tbody>
						<?php 
						if(is_admin() || is_sub_admin()){
							$arr = 1;
						}else{
							$arr=herapermission();
						}
						$wfhData = rander_getWfh($arr); 
						if(!empty($wfhData)){
							foreach($wfhData as $wfh){
						?>
						  <tr>
							<td><?= $wfh->full_name ?></td>
							<td><p data-toggle="tooltip" data-placement="top" title="<?= $wfh->reason; ?>"><?php echo  ucfirst(substr($wfh->reason,0,10)); ?></p></td>
							<td><?= $wfh->start_date ?></td>
							<td><?php if($wfh->start_date <= $wfh->end_date){ echo $wfh->end_date; }  ?></td> 
							<td><?php
							if($wfh->status == 0){
								echo "Pending";
							}elseif($wfh->status == 1){
								echo "Accepted";
							}elseif($wfh->status == 2){
								echo "Rejected";
							}
							?></td>
						  </tr>
						<?php } }else{ ?>
						<tr>
							<td colspan="4">Not Applied Now</td>
						  </tr>
						<?php } ?>
						</tbody>
					  </table>
				  </div>
				</div>            
			</div>
         </div>
      </div>
   </div>
</div>