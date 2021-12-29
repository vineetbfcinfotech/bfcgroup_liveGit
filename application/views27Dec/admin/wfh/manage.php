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
<?php $staffID = $this->session->userdata('staff_user_id'); ?>
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
								<?php $i = 1; foreach($wfhData as $wfh){ ?>
								<tr class="row-<?= $i ?>">
								  <td><?= $wfh->full_name; ?></td>
								  <td><?php if($wfh->duration == "sinlge_day"){ echo "Single Day"; }else{ echo "Multiple Days"; } ?></td>
								  <td><?= $wfh->start_date; ?></td>
								  <td><?php if($wfh->start_date <= $wfh->end_date){ echo $wfh->end_date; } ?></td>
								  <td><?= substr($wfh->reason, 0, 50); ?></td>
								  <td>
								  <?php if($wfh->staffid == $staffID){ 
								  if($wfh->status == 1){?> 
								  <a class="btn btn-success" href="javascript:void(0);" data-id="<?= $wfh->id; ?>">Approved</a> 
								  <?php }elseif($wfh->status == 2){ ?>
								  <a class="btn btn-danger" href="javascript:void(0);" data-id="<?= $wfh->id; ?>">Rejected</a> 
								  <?php }elseif($wfh->status == 0){ ?>
									<a href="javascript:void(0);" class="editwfh" data-id="<?= $wfh->id; ?>">Edit</a> | <a href="javascript:void(0);" class="deletewfh" data-id="<?= $wfh->id; ?>" data-row="<?= $i ?>">Delete</a>
								  <?php }}else{ ?>
									  <?php if($wfh->status == 0){ ?>
										<a class="btn btn-success approve" href="javascript:void(0);" onclick="approveWfh(<?= $wfh->id; ?>)" data-id="<?= $wfh->id; ?>">Approve</a> | <a class="btn btn-danger reject" href="javascript:void(0);" data-id="<?= $wfh->id; ?>">Reject</a>
									  <?php }elseif($wfh->status == 1){ ?>
										<a class="btn btn-success modify" href="javascript:void(0);" data-id="<?= $wfh->id; ?>">Modify</a> | <a class="btn btn-warning reject" href="javascript:void(0);" data-id="<?= $wfh->id; ?>">Reject</a>
									  <?php }elseif($wfh->status == 2){ ?>
										<a class="btn btn-danger" href="javascript:void(0);" data-id="<?= $wfh->id; ?>">Rejected</a>
								  <?php } ?>
									  
								  <?php } ?>
								  </td>
								</tr>
								<?php $i++; } ?>
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
				<div class="calander-wfh"><a href="#"><i class="fa fa-calendar"></i></a></div>
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
$(document).ready(function(){
	$("input[name=duration").click(function(){
		var duration = $("input[name=duration]:checked").val();
		if(duration == "multiple_day"){
			$(".end_date_hide").show();
			$(".start_date").text("Start Date");
		}else{
			$(".end_date_hide").hide();
			$(".start_date").text("Select Date");
		}
	});
	
	$('#wfh_from').on('submit', function(event){
		event.preventDefault();
		//alert(new FormData(this));
		$.ajax({
			url: "<?php echo admin_url(); ?>wfh/saveWfh",
			method: "POST",
			data: new FormData(this),
			processData:false,
			contentType:false,
			cache:false,
			async:false,
			success:function(data)
			{	
				if(data == 1){
					$('#wfhModal').modal('hide');
					alert_float("success", "Work From Home Applied!");
					document.getElementById("wfh_from").reset();
					$(".end_date_hide").hide();
					$(".start_date").text("Select Date");
				}
				else{
					alert_float("warning", "WFH not applied!");
				}
				
			}
		});
	});
	//$(".approve").click(function(){
//function approveWfh() {
    //alert("test");
		//if (confirm('Are you sure want to approve this?')) {
// 			$.ajax({
// 				url: "<?php echo admin_url(); ?>wfh/approveWfh",
// 				method: "POST",
// 				data: {"wfhid":$(this).data("id")},
// 				success:function(data)
// 				{	
// 					alert_float("success", "Approved Successfully!");
// 					location.reload();
// 				}
// 			});
	//	}
	//});
//}
	$(".deletewfh").click(function(){
		var row = $(this).data("row");
		if (confirm('Are you sure want to delete this?')) {
			$.ajax({
				url: "<?php echo admin_url(); ?>wfh/deleteWfh",
				method: "POST",
				data: {"wfhid":$(this).data("id")},
				success:function(data)
				{	
					$('tr.row-'+row).remove();
					alert_float("success", "Deleted Successfully!");
				}
			});
		}
	});
	$(".deletewfh").click(function(){
		var row = $(this).data("row");
		if (confirm('Are you sure want to delete this?')) {
			$.ajax({
				url: "<?php echo admin_url(); ?>wfh/deleteWfh",
				method: "POST",
				data: {"wfhid":$(this).data("id")},
				success:function(data)
				{	
					$('tr.row-'+row).remove();
					alert_float("success", "Deleted Successfully!");
				}
			});
		}
	});
	$(".reject").click(function(){
		if (confirm('Are you sure want to reject this?')) {
			$.ajax({
				url: "<?php echo admin_url(); ?>wfh/rejectWfh",
				method: "POST",
				data: {"wfhid":$(this).data("id")},
				success:function(data)
				{	
					alert_float("success", "Rejected Successfully!");
				}
			});
		}
	});
	
	
});

function approveWfh(id) {
    if (confirm('Are you sure want to approve this?')) {
        url = "<?php echo admin_url(); ?>wfh/approveWfh";
        //var wfhid = $(this).data("id");
        
        $.post(url, {
                wfhid: id
            },
            function (res) {
                alert_float("success", "Approved Successfully!");
    			location.reload();
            })
        
// 		$.ajax({
// 			url: "<?php echo admin_url(); ?>wfh/approveWfh",
// 			method: "POST",
// 			data: {"wfhid":id},
// 			success:function(successdata)
// 			{	
// 			    alert(successdata);
// 				// alert_float("success", "Approved Successfully!");
// 				// location.reload();
// 			}
// 		});
 	}    
 }
</script>
</body>
</html>