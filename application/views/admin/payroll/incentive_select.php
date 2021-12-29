<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
    	<div class="row">
          <?php echo form_open_multipart('admin/payroll/save_incentive_select',array('class'=>'staff-form','autocomplete'=>'off')); ?>
      <div class="col-md-<?php if(!isset($member)){echo '8 col-md-offset-2';} else {echo '5';} ?>" id="small-table">
      	<div class="panel_s">
            		<div class="panel-body">
            			<a href="<?= admin_url('payroll/getIncentiveSelect'); ?>" class="btn btn-info">Show List</a>
            		</div>
            	</div>
         <div class="panel_s">
            <div class="panel-body">
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                    <div class="row">
                     <div class="form-group col-md-6" app-field-wrapper="company"><label for="company" class="control-label">Company</label>
                            <select id="company" name="company" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" required="required">
                                  <option value="">Select Company</option>
                                  <option value="1">BFC Capital</option>
                                  <option value="2">BFC Infotech</option>
                                  <option value="3">BFC Publication</option>
                            </select>  
                        </div>

                        <div class="form-group col-md-6" app-field-wrapper="departments"><label for="departments" class="control-label">Department</label>
                            <select id="departments" name="departments" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98"  required="required">
                               
                                    
                            </select>  
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6" app-field-wrapper="teamselect"><label for="teamselect" class="control-label">Group/Team</label>
                            <select id="teamselect" name="teamselect" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98"  required="required">
                                 
                                  
                            </select>  
                        </div>
                      
                        <div class="form-group col-md-6" app-field-wrapper="empselect"><label for="empselect" class="control-label">Staff</label>
                            <select id="empselect" name="empselect[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple>
                                 
                            </select>  
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6" app-field-wrapper="form_date"><label for="form_date" class="control-label">From Date</label>
                          <input type="text" id="datestart" name="datestart" placeholder="Period From" class="form-control datepicker" required="required"/>
                        </div>
                        <div class="form-group col-md-6" app-field-wrapper="to_date"><label for="to_date" class="control-label">To Date</label>
                          <input type="text" id="dateend" name="dateend" placeholder="Period From" class="form-control datepicker" required="required"/>
                        </div>
                        </div>
                        <div class="row">
                        	<div class="col-md-3"><label class="control-label">Credit Score Level</label></div>
                        	<div class="col-md-3"><label class="control-label">CTC</label></div>
                        	<div class="col-md-3"><label class="control-label">Incentive Slabs</label></div>
                        	<div class="col-md-3"></div>
                        </div>
                        <input type="hidden" value="1" id="box_count" name="box_count">
                        <div class="optionBox">
                        <div class="row block">
                        	<div class="col-md-3"> <input type="text" id="credit_score" autocomplete="false" name="credit_score[]" placeholder="=1*" class="form-control" value="" required="required"/></div>
                        	<div class="col-md-3"> <input type="text" id="ctc" autocomplete="false" name="ctc[]" placeholder="CTC" class="form-control" readonly="readonly" value="QCTC" required="required"/></div>
                        	<div class="col-md-3"> <input type="text" id="incantive" autocomplete="false" name="incantive[]" placeholder="0%" class="form-control" value="" required="required"/></div>
                        	<span class="add  btn btn-info">Add More</span>
                        </div>
                        <div class="add_row"></div>
                        </div>
                  </div>
                 
               </div>
            </div>
         </div>
      </div>
      <div class="btn-bottom-toolbar text-right btn-toolbar-container-out" style="width: calc(100% - 293px);">
         <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $("#datepicker").datepicker( {
    format: " yyyy", // Notice the Extra space at the beginning
    viewMode: "years", 
    minViewMode: "years"
});
    $(document).ready(function(){
	  $("#company").change(function(){
		var comp_id = $(this).val();

		$.ajax({
           url: '/admin/staff/getdepartment',
           type: 'POST',
           data: {comp_id: comp_id},
           error: function() {
              alert('Something is wrong');
           },
           success: function(html) {
                $('#departments').html('');
				$('#departments').append(html);
				$('#departments').selectpicker('refresh');
				$('#team_id').html('');
				$('#team_id').selectpicker('refresh');
				
           }
        });
	  });
	  
	  $('#departments').on('change',function(){
            csrf_jquery_ajax_setup();
            var did = $(this).val();
            $.ajax({
                type: "GET",
                url: "<?= admin_url('teams/getTeamListByDepartmentId'); ?>",
                data: {dept_id: did},
                success: function (res) {
					var obj = JSON.parse(res);
                    $('#teamselect').html(obj['team']);
                    $('.selectpicker').selectpicker('refresh');
                }
            });
       });

	   $("#teamselect").change(function(){
    	  var teams = $('#teamselect').val();
        var did = $('#departments').val();
        // url: '/admin/leave/show_team_member',
		  $.ajax({
	           url: '/admin/teams/show_team_member',
	           type: 'POST',
	           data: {team_id: teams,dept_id: did},
	           error: function() {
	              alert('Something is wrong');
	           },
	           success: function(data) {
					$('#empselect').html('');
					$('#empselect').append(data);
					$('#empselect').selectpicker('refresh');			   
	           }
	        });
	     });

	   var max_fields      = 10; 
	   var x = 1; //initlal text box count
	   $('.add').click(function() {
	   	 if(x < max_fields){ //max input box allowed
            x++; //text box increment
			$("#box_count").val(x);
		    $('.add_row').before(' <div class="row block"> <br><div class="col-md-3"> <input type="text" id="credit_score"name="credit_score[]" placeholder="=1*" class="form-control" value="" /></div><div class="col-md-3"> <input type="text" id="ctc" name="ctc[]" placeholder="CTC" class="form-control" readonly="readonly" value="QCTC" /></div><div class="col-md-3"> <input type="text" id="incantive"  name="incantive[]" placeholder="0%" class="form-control" value="" /></div><span class="remove  btn btn-danger">Remove</span></div>');
		   }

		});

		$('.optionBox').on('click','.remove',function() {
		 	$(this).parent().remove();  x--;
		 	$("#box_count").val(x);
		});

	});
</script>
</body>
</html>
