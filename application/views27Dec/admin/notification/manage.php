<?php init_head(); ?>
<div id="wrapper">

<div class="content">
   <div class="row">
	<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons" style="margin-bottom: 10px; display: inline-block;">
							<a href="javascript:void(0);" class="btn btn-info pull-left display-block"  data-target="#myModal" data-toggle="modal" >Add New</a>
						</div>
						
						<?php if(!empty($this->session->flashdata('success'))) {?>
						   <div class="text-success text-center"><?php echo  $this->session->flashdata('success'); ?></div>
					   <?php }else if(!empty($this->session->flashdata('error'))){?>
						   <div class="text-danger text-center"><?php echo  $this->session->flashdata('error'); ?></div>
					   <?php } ?>

						<div class="clearfix"></div>
						
						<table class="table dt-table scroll-responsive">
						<thead>
						<tr>
							<th>S.N</th>
							<th>Subject</th>
							<th>Message</th>
							<th>Action</th>
						</tr>
						</thead>
						
						<tbody>
						<?php $i = 1; foreach($notifications as $notification){ ?>
						<tr>
							<td><?= $i; ?></td>
							<td><?= $notification->custom_subject; ?></td>
							<td><?= $notification->custom_notification; ?></td>
							<td><a href="#"><strong><i class="fa fa-edit"></i></strong></a> | <a href="#" style="background-color: red;"><strong><i class="fa fa-trash"></i></strong></a></td>
						</tr>
						<?php $i++; } ?>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		
		<!-----------Start Add New --------->
		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			<form name="custom_notification" action="<?php echo admin_url('notification/save'); ?>" method="post">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add New Notification</h4>
			  </div>
			  <div class="modal-body">
					<?php //echo render_input('subject','subject', ); ?>
					<div class="form-group" app-field-wrapper="subject">
						<label for="subject" class="control-label">subject</label>
						<input type="text" id="subject" name="subject" class="form-control" value="" autofocus="autofocus" required="required" >
					</div>
						
					<p class="bold"><?php echo _l('announcement_message'); ?></p>
					<?php echo render_textarea('message','',$contents,array(),array(),'','tinymce'); ?>
			  </div>
			  <div class="modal-footer">
				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
				<button type="submit" class="btn btn-default">Save</button>
			  </div>
			  </form>
			</div>

		  </div>
		</div>
		<!-----------Stop Add New --------->
   
   </div>
   <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>
<script>
$(document).ready(function(){
  $("#subject").attr("autocomplete", "off");
});
</script>
</body>
</html>