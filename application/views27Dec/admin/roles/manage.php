<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<a href="<?php echo admin_url('roles/role'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_role'); ?></a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						
						<table class="table dt-table scroll-responsive">
							<thead><tr>
							<th>S.N</th>
							<th>Designation Name</th>
							<th><?php echo _l('options')?></th>
							</tr>
							</thead>
							<tbody>
							<?php
							$i = 1;
							foreach($roles as $role){  ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $role->name; ?></td>
									<td><a href="<?php echo base_url('admin/roles/role/').$role->roleid; ?>" ><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a> | <a href="<?php echo base_url('admin/roles/delete/').$role->roleid; ?>"><span><i class="fa fa-trash" aria-hidden="true"></i></span></a></td>
								</tr>
							<?php 
							$i++;
							}
							?>				
							</tbody>
						</table>
						
						<?php 
						/* render_datatable(array(
							_l('roles_dt_name'),
							_l('options')
							),'roles'); */ 
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php init_tail(); ?>
	<script>
	console.log(window.location.href);
		initDataTable('.table-roles', window.location.href, [1], [1]);
	</script>
</body>
</html>
