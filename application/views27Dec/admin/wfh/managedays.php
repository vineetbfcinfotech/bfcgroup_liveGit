<?php init_head(); ?>
<style>
.calander-wfh {
    float: right;
	position: relative;
	top: -30px;
	padding: 0px 10px 0 5px;
	border-left: 1px solid #cac4c4;
}
.calander-wfh i{
	font-size: 25px;
}
</style>

<div id="wrapper">
<?php init_clockinout(); ?>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<a href="javascript:void(0);" class="btn btn-info pull-left display-block" onclick="goBack()">Back</a>
						<a href="javascript:void(0);" class="btn btn-info pull-right display-block set-form-data" data-toggle="modal" data-target="#wfhModal" >Apply WFH</a>
						<div class="clearfix"></div>
						<hr>
						<div class="clearfix"></div>
						

						<table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
							<thead>
							   <tr role="row">
								  <th>Name</th>
								  <th>Duration</th>
								  <th>Start Date</th>
								  <th>End Date</th>
								  <th>Reason</th>
								  <th>Action</th>
							   </tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="wfhModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Apply Work From Home</h4>
      </div>
      <div class="modal-body">
        <form id="wfh_from">
			<input type="hidden" name="staff_id" value="<?php echo $staffID; ?>">
			<div class="form-group">
				<label class="radio-inline"><input type="radio" name="duration" value="sinlge_day" checked>Single Day</label>
				<label class="radio-inline"><input type="radio" name="duration" value="multiple_day">Multiple Days</label>
			</div>
			<div class="form-group">
				<label for="start_date" class="start_date">Select Date</label>
				<input type="text" class="form-control" id="start_date" name="start_date" placeholder="Please Select Date" readonly >
				
				<div class="calander-wfh"><label for="start_date" style="cursor: pointer;color: #7dd7ec;"><i class="fa fa-calendar"></i></label></div>
				
			</div>
			<div class="form-group end_date_hide" style="display:none;">
				<label for="end_date">End Date</label>
				<input type="text" class="form-control" id="end_date" name="end_date" placeholder="Please Select Date" readonly >
				<div class="calander-wfh"><a href="#"><i class="fa fa-calendar"></i></a></div>
			</div>
			<div class="form-group">
				<label for="reason">Reason</label>
				<textarea type="text" class="form-control" name="reason" id="reason" ></textarea>
			</div>
			
			<button type="submit" class="btn btn-primary">Apply</button>
		</form>
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