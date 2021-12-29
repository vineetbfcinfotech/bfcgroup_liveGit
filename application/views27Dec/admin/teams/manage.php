<?php init_head(); ?>
<div id="wrapper">
   <?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('teams/add_new'); ?>"
                               class="btn btn-info pull-left display-block"><?php echo _l('create_new_team'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<div style="">
							<select id="company" data-none-selected-text="Filter By Company" data-live-search="true" class="selectpicker custom_lead_filter">
								<option value="1">BFC Capital</option>
								<option value="2">BFC Infotech</option>
								<option value="3">BFC Publications</option>
							</select>
						</div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<div id="loading-image" style="display: none; text-align: center;">
							<img src="<?php echo base_url("/assets/images/task_loader.gif"); ?>" >
						</div>
						<div class="ajax-data">
                       <?php if ( !empty($teams) > 0 ) { ?>
                           <table class="table dt-table scroll-responsive">
                               <thead>
                               <th><?php echo _l('Sr.no'); ?></th>
                               <th><?php echo _l('dt_team_name'); ?></th>
                               <th><?php echo _l('dt_department_name'); ?></th>
                               <th><?php echo "Team Head"; ?></th>
                               <th><?php echo _l('product_dt_active'); ?></th>

                               <th><?php echo _l('options'); ?></th>
                               </thead>
                               <tbody>
                               <?php if ( !empty($teams) ) {
                                  foreach ($teams as $team) { ?>
                                      <tr>
                                          <td><?= @++$i; ?></td>
                                          <td><?= $team->team_name; ?></td>
                                          <td><?= $team->department_name; ?></td>
                                          <td><?= $team->team_head; ?></td>
                                          <td>
                                              <div class="onoffswitch" data-toggle="tooltip"
                                                   data-title="<?= $team->team_name; ?>">
                                                  <input type="checkbox"
                                                         data-switch-url="<?= admin_url('teams/active/teams') ?>"
                                                         name="onoffswitch" class="onoffswitch-checkbox"
                                                         id="<?= $team->id ?>"
                                                         data-id="<?= $team->id ?>" <?= $team->Active == 1 ? 'checked' : '' ?>>
                                                  <label class="onoffswitch-label" for="<?= $team->id; ?>"></label>
                                              </div>
                                          </td>
                                          <td>
											<a href="<?php echo admin_url('teams/view/' . $team->id); ?>"
                                                 class="btn btn-info btn-icon" title="View Team Member" ><i
                                                          class="fa fa-eye"></i></a>
										  
                                              <a href="<?php echo admin_url('teams/edit/' . $team->id); ?>"
                                                 class="btn btn-info btn-icon"><i
                                                          class="fa fa-pencil"></i></a>
                                              <a href="<?php echo admin_url('teams/deleteteam/' . $team->id); ?>"
                                                 class="btn btn-danger btn-icon _delete"><i
                                                          class="fa fa-remove"></i></a>
                                          </td>
                                      </tr>
                                  <?php }
                               } ?>
                               </tbody>
                           </table>
                       <?php } ?>
					   </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <?php init_tail(); ?>
    <script>
        var csfrData = {};
        csfrData['<?php echo $this->security->get_csrf_token_name(); ?>']
            = '<?php echo $this->security->get_csrf_hash(); ?>';
    </script>
    <script>
        $(function () {
            // Attach csfr data token
            $.ajaxSetup({
                data: csfrData
            });
        });
		
		$(document).ready(function(){
		  $("#company").change(function(){
			var comp_id = $(this).val();
			$('#loading-image').show();
			$('.ajax-data').hide();
			$.ajax({
			   url: "<?php echo base_url('/admin/teams/getteam') ?>",
			   type: 'POST',
			   data: {comp_id: comp_id},
			   error: function() {
				  alert('Something is wrong');
			   },
			   success: function(data) {
					$('.ajax-data').html(data);
					initDataTableInline();
			   },
			  complete: function(){
				$('#loading-image').hide();
				$('.ajax-data').show();
				$(".dataTables_scrollHeadInner").css("width", "100%");
			  }
			});
		  });
		});
    </script>
</div>
</body>
</html>
