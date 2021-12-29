<?php init_head(); ?>
<div id="wrapper"> <?= init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <button class="btn btn-success pull-right"
                                    onclick="window.location='<?php echo base_url(); ?>admin/leads/availablewp';"> Back
                            </button> <?= @$title; ?>
                        </h4>
                        <hr class="hr-panel-heading"/>
                       <?php echo form_open($this->uri->uri_string()); ?>
                        <div class="col-md-6">
                           <?= form_hidden('date', $team->date); ?>
                           <div class="form-group" app-field-wrapper="date"><input type="hidden" id="date" name="date" class="form-control" value="<?= $team->date ?>" autocomplete="off"></div>
                        </div>
                        <div class="col-md-6">
                          
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
                        <div class="clearfix"></div>
                       <?php $old_roles = $this->leads_model->getassignedwpBydate($team->date);
                       /*print_r($old_roles);*/
                          if ( !empty($old_roles) ) {
                             echo '<div id="addnewrole">';
                             echo '<input type="hidden" name="deleted_team_dept_ids" value="" id="deleted_team_dept_ids">';
                             foreach ($old_roles as $key => $old_role) { ?>
                                 <div class="col-md-5 role role_row_<?= $key; ?>">
                                    <?= form_hidden('team_dept_id[]', $old_role->id); ?>
                                    <?= render_select('telermids[]', $telerm, array('staffid', 'firstname'), $key == 0 ? 'Tele RM' : '', $old_role->telerm); ?>
                                    
                                   
                                 </div>
                                 <div class="col-md-5 role_row_<?= $key; ?>">
                                    <?= render_select('wpids[]', $availwp, array('staffid', 'firstname', 'as RM'), $key == 0 ? 'WP' : '', $old_role->wp); ?>
                                 </div>
                                 <div class="col-md-2 role_row_<?= $key; ?>">
                                     <div class="form-group" app-field-wrapper="add_more_roles">
                                        <?php if ( $key == 0 ) { ?>
                                            <label for="add_more_team_role" class="control-label"> Add More
                                                </label>
                                            <button type="button" data-team_dept="<?= $old_role->id; ?>"
                                                    class="btn btn-info"
                                                    id="add_more_team_role"><i class="fa fa-plus"></i></button>
                                        <?php } else { ?>
                                            <button type="button" data-team_dept="<?= $old_role->id; ?>"
                                                    data-row="<?= $key; ?>"
                                                    class="btn btn-danger remove_team_role">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        <?php } ?>
                                     </div>
                                 </div>
                             <?php }
                             echo '</div>';
                          } else {
                             ?>
                              <div class="col-md-5 role">
							<?= render_select('telermids[]',$telerm,array('staffid','firstname'),'Tele RM'); ?>
						</div>
						<div class="col-md-5">
							<?= render_select('wpids[]',$availwp,array('staffid','firstname','as WP'),'Available WP'); ?>
						</div>
                              <div class="col-md-2">
                                  <div class="form-group" app-field-wrapper="add_more_roles">
                                      <label for="add_more_team_role" class="control-label"> Add More </label>
                                      <button style="margin-left: 4px;" type="button" class="btn btn-info"
                                              id="add_more_team_role"><i class="fa fa-plus"></i></button>
                                  </div>
                              </div>
                              <div id="addnewrole"></div>
                             <?php
                          } ?>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-info pull-right"><?php echo _l('update'); ?></button>
                        </div>
                       <?= form_close(); ?>
                    </div>
                </div>
               <?php init_tail(); ?>
            </div>
        </div>
    </div>
</div>
<script id="addroletemplate" type="text/template">
    <div class="col-md-5 role role_row_{{id}}">
		<?= render_select('telermids[]',$telerm,array('staffid','firstname')); ?>
	</div>
	<div class="col-md-5 role_row_{{id}}">
		<?= render_select('wpids[]',$availwp,array('staffid','firstname','as WP')); ?>
	</div>
    <div class="col-md-2 role_row_{{id}}">
        <div class="form-group" app-field-wrapper="add_more_roles">
            <button type="button" data-row="{{id}}" class="btn btn-danger remove_team_role"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</script>
<script>
    $(function () {
        _validate_form($('form'), {team_name: 'required'});
    });
</script>
</body>
</html>
