<?php init_head(); ?>
<style>
#specialleave_row .col-md-3{
	min-height: 44px;
}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3><center>Special Leave</center></h3>
                        <hr/>
                        <form method="post" action="<?= base_url('admin/leave/specialleave');?>" >
                        <div class="row" id="specialleave_row">
							<div class="col-md-3"></div>
							<div class="col-md-3">
								<label class="checkbox-inline">
								  <input type="checkbox" id="checkAllTeam">Select All Team
								</label>
							</div>
							<div class="col-md-3">
								<label class="checkbox-inline">
								  <input type="checkbox" id="checkAll">Select All Employee
								</label>
							</div>
							<div class="col-md-3"></div>
                            <div class="col-md-3">
                                <lable>Quota</lable>
                                <input class="form-control" name="quota" value="" required />
                            </div>
                            
                            <div class="col-md-3" >
								<lable>Select Team</lable>
                                <select class="form-control selectpicker" id="teamselect" name="team[]" data-live-search="true" multiple="multiple">
                                    <!-- <option value="no">Select Team</option> -->
                                    <?php  foreach($teams as $team) { ?>
                                    <option value="<?= $team['id']; ?>" ><?= $team['team_name']; ?> </option>
                                   
                                    <? } ?>
                                    </select>
                            </div>
                            
                            <div class="col-md-3" id="selectempdata">
                                <lable>Select Employee</lable>
                                <select class="form-control selectpicker" id="empselect" name="employee[]" data-live-search="true" multiple="multiple">
                                    <!-- <option value="no">Select Employee</option --> 
                                    <?php  foreach($staff_members as $staff_member) { ?>
                                    <option value="<?= $staff_member['staffid']; ?>"><?= $staff_member['firstname']; ?> <?= $staff_member['lastname']; ?></option>
                                   
                                    <? } ?>
                                    </select>
                            </div>
							<div class="col-md-3 margin pull-right" id="submitbutton" style="margin-top: 20px;">
                                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                             </div>
                            </div>
                            
                            
                             
                             </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>

$(document).ready(function(){
  $("#teamselect").change(function(){
      //var teams = $(this).children("option:selected").val();
	  var teams = $('#teamselect').val();
	  $.ajax({
           url: '/admin/leave/show_team_member',
           type: 'POST',
           data: {team_id: teams},
           error: function() {
              alert('Something is wrong');
           },
           success: function(data) {
			   //alert(data);
				$('#empselect').html('');
				//$('#empselect').append('<option value="19">Standard</option>');
				$('#empselect').append(data);
				$('#empselect').selectpicker('refresh');			   
           }
        });
      
      /* if(teams == "no")
      {
        $('#empselect').removeAttr('disabled');  
      }
      else
      {
        $('#empselect').attr("disabled", "disabled"); 
      } */
  });
  
  $("#checkAll").click(function(){
	  
	  if(this.checked){
            $('#checkAll').each(function(){
                $('#empselect option').attr('selected','selected');
				$('#empselect').selectpicker('refresh');
            });
        }else{
             $('#checkAll').each(function(){
				$('#empselect option').removeAttr('selected');
				$('#empselect').selectpicker('refresh'); 
            });
        }
	     
	});
	
	$("#checkAllTeam").click(function(){
	  
	  if(this.checked){
            $('#checkAll').each(function(){
                $('#teamselect option').attr('selected','selected');
				$('#teamselect').selectpicker('refresh');
            });
        }else{
             $('#checkAll').each(function(){
				$('#teamselect option').removeAttr('selected');
				$('#teamselect').selectpicker('refresh'); 
            });
        }
	     
	});
	
});

/* $(document).ready(function(){
  $("#empselect").change(function(){
      var teams = $(this).children("option:selected").val();
    
      if(teams == "no")
      {
        $('#teamselect').removeAttr('disabled');  
      }
      else
      {
        $('#teamselect').attr("disabled", "disabled"); 
      }
  });
});  */ 
</script>
</body>
</html>
