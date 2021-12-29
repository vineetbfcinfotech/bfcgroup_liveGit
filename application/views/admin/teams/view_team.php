<?php init_head(); ?>
<div id="wrapper">
   <?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <!-- <a href="<?php //echo admin_url('teams/add_new'); ?>"
                               class="btn btn-info pull-left display-block"><?php //echo _l('create_new_team'); ?></a> -->
							   <button onclick="goBack()" class="btn btn-info pull-right display-block">Go Back</button>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<p><strong>Team:</strong> <i><?php echo $team_Name->team_name; ?></i></p>
                        <hr class="hr-panel-heading"/>
						<div class="clearfix"></div>
                       <?php if ( !empty($team_members) > 0 ) { ?>
                           <table class="table dt-table scroll-responsive">
                               <thead>
                               <th><?php echo _l('Sr.no'); ?></th>
                               <th><?php echo _l('Team Member Name'); ?></th>
                               <th><?php echo _l('Email'); ?></th>
                               <th><?php echo _l('Mobile'); ?></th>
                               </thead>
                               <tbody>
                               <?php if ( !empty($team_members) ) {
                                  foreach ($team_members as $team_member) { ?>
                                      <tr>
                                          <td><?= @++$i; ?></td>
                                          <td><?= $team_member->full_name; ?></td>
                                          <td><?= $team_member->email; ?></td>
                                          <td><?= $team_member->phonenumber; ?></td>
                                          
                                      </tr>
                                  <?php }
                               } ?>
                               </tbody>
                           </table>
                       <?php }else{ ?>
							<table class="table dt-table scroll-responsive">
								<tbody>
									<tr><th><strong>No Data Found</strong></th></tr>
								</tbody>
							</table>
					  <?php } ?>
					   
                    </div>
                </div>
            </div>
        </div>
    </div>
   <?php init_tail(); ?>
    <script>
function goBack() {
  window.history.back();
}
</script>
</div>
</body>
</html>