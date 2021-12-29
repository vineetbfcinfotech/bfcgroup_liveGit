<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3><center>Customize Time log</center></h3>
                        <hr/>
                        <form method="post" action="<?= base_url('admin/attendance/customize_timelog');?>" >
                        <div class="row">
                            
                            <div class="col-md-3">
                                <lable>Select Date Range</lable>
                                <select class="selectpicker" name="date_range" id="daterange" >
                                    <option value="single">Single Date</option>
                                    <option value="multiple">Multiple Date</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3" id="singledate">
                                <lable>Select Date</lable>
                                <input class="form-control datepicker" name="date" value="" />
                            </div>
                            
                            <div id="multipledate">
                              
                              <div class="col-md-3" >
                                <lable>Select Start Date</lable>
                                <input class="form-control datepicker" id="startdate" name="startdate" value="" />
                                
                            </div>
                            <div class="col-md-3" >
                                 <lable>Select End Date</lable>
                                <input class="form-control datepicker" id="enddate" name="enddate" value="" />
                            </div>
                                
                            </div>
                            
                            
                            
                            
                            
                            
                            <div class="col-md-3">
                                <lable>Select Shift</lable>
                                <select class="form-control selectpicker" id="shift" name="shift[]" multiple>
                                    
                                    <option value="morning">Morning</option>
                                    <option value="evening">Evening</option>
                                    
                                    
                                    </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label>Select Employee</label>
                                <select class="form-control selectpicker" id="empselect" name="employee[]" multiple>
                                    <option value="no">Select Employee</option>
                                    <?php  foreach($staff_members as $staff_member) { ?>
                                    <option value="<?= $staff_member['bio_id']; ?>" ><?= $staff_member['firstname']; ?> <?= $staff_member['lastname']; ?></option>
                                   
                                    <? } ?>
                                    </select>
                            </div>
                            </div>
                            
                            <div class="row col-md-3 margin pull-right" id="submitbutton" style="">
                                    <button type="submit" class="btn btn-primary btn-block">Save</button>
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
      var teams = $(this).children("option:selected").val();
      
      if(teams == "no")
      {
        $('#empselect').removeAttr('disabled');  
      }
      else
      {
        $('#empselect').attr("disabled", "disabled"); 
      }
  });
});

$(document).ready(function(){
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
});  

$(document).ready(function(){
    
    $('#multipledate').hide();
  $("#daterange").change(function(){
      var daterange = $(this).children("option:selected").val();
    
      if(daterange == "single")
      {
        $('#singledate').show();  
        $('#multipledate').hide();
      }
      else
      {
        $('#singledate').hide();  
        $('#multipledate').show();
      }
  });
});
</script>
</body>
</html>
