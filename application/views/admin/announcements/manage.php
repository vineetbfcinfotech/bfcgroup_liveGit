<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<?php if(is_admin()) { ?>
							<a href="<?php echo admin_url('announcements/announcement'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_announcement'); ?></a>
							<div class="clearfix"></div>
							<hr class="hr-panel-heading" />
							<?php } else { echo '<h4 class="no-margin bold">'._l('announcements').'</h4>';} ?>
						</div>
						<div class="clearfix"></div>
						<table id="example" class="table dt-table scroll-responsive" style="width:100%">
							<thead>
								<tr>
									<th>Sr. No.</th>
									<th>Subject</th>
									<th>Announcement by</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
							<?php $i = 1; foreach($announcements as $announcement){ ?>
								<tr>
									<td><?= $i; ?></td>
									<td><a href="<?= admin_url(); ?>announcements/view/<?= $announcement->announcementid ?>"><?= $announcement->name; ?></a></td>
									<td><?= $announcement->userid; ?></td>
									<td><?= $announcement->dateadded; ?></td>
								</tr>
							<?php $i++; } ?>
						</table>
						<?php //render_datatable(array(_l('name'),_l('announcement_date_list'),_l('announcement_userid')),'announcements'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>

</body>
</html>
