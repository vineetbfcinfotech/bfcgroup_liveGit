<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>DWR Date Permission</h3></center>
                        
                        <?php echo form_open($this->uri->uri_string()); ?>
						
						
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<div class="col-md-5 role" >
							<div class="form-group" app-field-wrapper="date"><label for="date" class="control-label">Date</label><input type="text" id="" name="date[]" class="form-control datepicker" value="" autocomplete="off"></div>
						</div>
						<div class="col-md-5">
							<?= render_select('wpids[]',$availwp,array('staffid','firstname',''),'Wealth Person'); ?>
						</div>
						<div class="col-md-2">
							<div class="form-group" app-field-wrapper="add_more_roles">
								<label for="add_more_team_role" class="control-label"> Add More </label>
								<button style="margin-left: 4px;" type="button" class="btn btn-info" id="add_more_team_role"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div id="addnewrole"></div>
						<div class="col-md-12">
							<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
						</div>
						<?= form_close(); ?>
						
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						
						<?php if ( !empty($assignedwp) > 0 ) { ?>
						<center><h3>Date Permission Assigned List</h3></center>
                           <table class="table dt-table scroll-responsive">
                               <thead>
                               <th><?php echo _l('Sr.no'); ?></th>
                               <th><?php echo "Date"; ?></th>
                               
                               <th><?php echo "Name"; ?></th>

                               <th><?php echo _l('options'); ?></th>
                               </thead>
                               <tbody>
                               <?php if ( !empty($assignedwp) ) {
                                  foreach ($assignedwp as $team) { ?>
                                      <tr>
                                          <td><?= @++$i; ?></td>
                                          <td><?= $team->date; ?></td>
                                          <td><?= $team->namewp; ?></td>
                                          <td>
                                              
                                              <a href="<?php echo admin_url('reports/deletedatewp/' . $team->id); ?>"
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
<script id="addroletemplate" type="text/template">
<div class="col-md-5 role role_row_{{id}}" >
							<div class="form-group" app-field-wrapper="date"><input type="text" id="" name="date[]" class="form-control datepicker" value="" autocomplete="off"></div>
						</div>

	<div class="col-md-5 role_row_{{id}}">
		<?= render_select('wpids[]',$availwp,array('staffid','firstname','')); ?>
	</div>
	<div class="col-md-2 role_row_{{id}}">
		<div class="form-group" app-field-wrapper="add_more_roles">
			<label for="add_more_team_role" class="control-label">&nbsp;</label>
			<button type="button" data-row="{{id}}" class="btn btn-danger remove_team_role"><i class="fa fa-minus"></i></button>
		</div>
	</div>
</script>
<script>
		$(function(){
			_validate_form($('form'),{date:'required'});
		});
</script>
<script>
$(document).ready(function(){
    $("#startdate").addClass("datetimepicker");
    
    
});
</script>

<script>
     $('#startdate').datetimepicker({
        format: 'Y-m-d',
        minDate: '0',
        onShow: function (ct) {
            this.setOptions({
                minDate: 0
            })
        },
        timepicker: false
    });
</script>



</body>
</html>
