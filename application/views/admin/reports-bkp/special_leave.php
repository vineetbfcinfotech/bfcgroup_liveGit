<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>Special Leave Report Summary</h3></center>
						<a href="javascript:void(0);" class="btn btn-alert"
                                   onclick="goBack()">
                                    Back</a>
                        <hr class="hr-panel-heading">
						
						<div class=" ajax-data">
                            <div class="table-responsive">
                                <?php if (!empty($specialLeaveData)): ?>
                                    <table id="business_report" class="table dt-table scroll-responsive tablebusie ">
                                        <thead>
                                        <tr>
											<th class="bold">No.</tj>
                                            <th class="bold">Name</th>
                                            <th class="bold">Total Special Leave</th>
                                            <th class="bold">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="">
                                        <?php $i=1; foreach($specialLeaveData as $leaveData){ ?>
											<tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $leaveData->staffname; ?></td>
												<td><?php echo $leaveData->sp_leave; ?></td>
												<td><a href="javascript:void(0);"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
											</tr>
										<?php $i++; } ?>
										</tbody>
                                
								</table>
							<?php endif; ?>
                    </div>
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
</body>
</html>
