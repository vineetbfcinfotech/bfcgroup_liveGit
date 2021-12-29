<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>Submit Custom Login / Logout</h3></center>
                        
                        <hr>
                        <form method="post" action="<?= base_url('admin/attendance/submit_earlychekout');?>" >
                       <div class="input_fields_wrap">
                        <div class="row">
                            <div class="col-md-3">
                                <lable>Meeting Slot</lable>
                                <select name="period[]" class="form-control" >
                                    <option value="Morning Meeting">Morning Meeting</option>
                                    <option value="Evening Meeting">Evening Meeting</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                
                            </div>
                                
                            <div class="col-md-2">
                                <lable>Meeting With</lable>
                                <input type="text" name="meeting_with[]" id="meeting_with" class="form-control" value=""  placeholder="Meeting With.." autocomplete="no" required>
                            </div>
                            <div class="col-md-2">
                                <lable>Purpose</lable>
                                <input type="text" name="purpose[]" id="purpose" class="form-control" value=""  placeholder="Purpose.." autocomplete="no" required>
                            </div>
                            <div class="col-md-2">
                                <lable>Location</lable>
                                <input type="text" name="location[]" id="location" class="form-control" value=""  placeholder="Location.." autocomplete="no" required>
                            </div>
                            <div class="col-md-2">
                                <lable>Scheduled Time</lable>
                                <input type="text" name="scheduled_time[]" id="scheduled_time" class="form-control datetimepicker" value=""  placeholder="Scheduled Time.." autocomplete="no" required>
                            </div>
                           
                        </div>
                        </br>
                        </div>
    <button class="add_field_button">Add More Meetings</button>

                        </br>
                        <div class="row">
                             <div class="col-md-3 margin pull-right" id="submitbutton" style="">
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
<?php init_tail(); ?>
<script>
   $(document).ready(function() {
	var max_fields      = 10; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
	var add_button      = $(".add_field_button"); //Add button ID
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			init_datepicker();
			$(wrapper).append('<div><div class="row"><div class="col-md-3"><lable>Meeting Slot</lable><select name="period[]" class="form-control" ><option value="Morning Meeting">Morning Meeting</option><option value="Evening Meeting">Evening Meeting</option></select></div><div class="col-md-1"></div><div class="col-md-2"><lable>Meeting With</lable><input type="text" name="meeting_with[]" id="meeting_with" class="form-control" value=""  placeholder="Meeting With.." autocomplete="no" required></div><div class="col-md-2"><lable>Purpose</lable><input type="text" name="purpose[]" id="purpose" class="form-control" value=""  placeholder="Purpose.." autocomplete="no" required></div><div class="col-md-2"><lable>Location</lable><input type="text" name="location[]" id="location" class="form-control" value=""  placeholder="Location.." autocomplete="no" required></div><div class="col-md-2"><lable>Scheduled Time</lable><input type="text" name="scheduled_time[]" id="" class="form-control datetimepicker" value=""  placeholder="Scheduled Time.." autocomplete="no" required></div></div><a href="#" class="remove_field">Remove</a></div></br>'); //add input box
		    init_datepicker();
		    
		}
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
	})
}); 
</script>
</body>
</html>
