<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
    	<div class="row">
          <?php echo form_open_multipart('admin/payroll/edit_incentive_select',array('class'=>'staff-form','autocomplete'=>'off')); ?>
      <div class="col-md-<?php if(!isset($member)){echo '8 col-md-offset-2';} else {echo '5';} ?>" id="small-table">
      	<div class="panel_s">
            		<div class="panel-body">
            			<a href="<?= admin_url('payroll/getIncentiveSelect'); ?>" class="btn btn-info">Show List</a>
            		</div>
            	</div>
              <?php  //print_r($incentive[0]->company); exit(); ?>
         <div class="panel_s">
            <div class="panel-body">
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                    <input type="hidden" value="<?= $incentive[0]->id;?>" name="incantive_Id" id="incantive_Id">
                    <div class="row">
                     <div class="form-group col-md-6" app-field-wrapper="company"><label for="company" class="control-label">Company</label>
                            <select id="company" name="company" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" required="required">
                                  <option value="1" <?php if($incentive[0]->company=='1') {echo "selected"; }?>>BFC Capital</option>
                                  <option value="2" <?php if($incentive[0]->company=='2') {echo "selected"; }?>>BFC Infotech</option>
                                  <option value="3" <?php if($incentive[0]->company=='3') {echo "selected"; }?>>BFC Publication</option>
                            </select>  
                        </div>

                        <div class="form-group col-md-6" app-field-wrapper="departments"><label for="departments" class="control-label">Department</label>
                            <select id="departments" name="departments" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple required="required">
                               <?php 
                               $this->db->where('company_id', $incentive[0]->company);
                               $result = $this->db->get('tbldepartments')->result();
                               foreach($result as $department){ 
                              ?>
                              <option value="<?= $department->departmentid; ?>" <?php if($department->departmentid==$incentive[0]->departments) { echo "selected"; }?>><?php echo $department->name; ?></option>
                              <?php }?>
                                    
                            </select>  
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6" app-field-wrapper="teamselect"><label for="teamselect" class="control-label">Group/Team</label>
                            <select id="teamselect" name="teamselect" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple required="required">
                                 <?php 
                               $this->db->where('department_id', $incentive[0]->departments);
                               $teamresult = $this->db->get('tblteams')->result();
                               foreach($teamresult as $team){ 
                              ?>
                              <option value="<?= $team->id; ?>" <?php if($team->id==$incentive[0]->teamselect) {echo "selected"; }?>><?php echo $team->team_name; ?></option>
                              <?php }?>
                                  
                            </select>  
                        </div>
                      
                        <div class="form-group col-md-6" app-field-wrapper="empselect">

                          <?php if($incentive[0]->inc_type==2){
                            $incentive[0]->teamselect='';
                          }?>
                          <label for="empselect" class="control-label">Staff</label>
                            <select id="empselect" name="empselect" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" multiple >
                            <?php 
                            $select = 'tblstaffdepartments.*, tblstaff.firstname, tblstaff.lastname, tblstaff.bio_id';
                            $this->db->select($select);
                            $this->db->from("tblstaffdepartments");
                            $this->db->join('tblstaff', 'tblstaff.staffid = tblstaffdepartments.staffid');
                            $this->db->where_in('tblstaffdepartments.team_id',$incentive[0]->teamselect);
                            $this->db->where('tblstaff.active ', 1);
                            $query = $this->db->get(); 
                            $staffresult = $query->result();
                            foreach($staffresult as $staff){
                            ?>
                             <option value="<?= $staff->staffid; ?>" <?php if($staff->staffid==$incentive[0]->staff_id) {echo "selected"; }?>><?php echo $staff->firstname; ?> <?php echo $staff->lastname; ?></option>
                            <?php }?>
                            </select>  


                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6" app-field-wrapper="form_date"><label for="form_date" class="control-label">From Date</label>
                          <input type="text" id="datestart" name="datestart" placeholder="Period From" class="form-control datepicker" value="<?= $incentive[0]->datestart; ?>" required="required"/>
                        </div>
                        <div class="form-group col-md-6" app-field-wrapper="to_date"><label for="to_date" class="control-label">To Date</label>
                          <input type="text" id="dateend" name="dateend" placeholder="Period From" class="form-control datepicker" value="<?= $incentive[0]->dateend; ?>" required="required"/>
                        </div>
                        </div>
                        <div class="row">
                        	<div class="col-md-3"><label class="control-label">Credit Score Level</label></div>
                        	<div class="col-md-3"><label class="control-label">CTC</label></div>
                        	<div class="col-md-3"><label class="control-label">Incentive Slabs</label></div>
                        	<div class="col-md-3"></div>
                        </div>
                        <div class="optionBox">
                         <?php  
                              $this->db->where('incentive_id', $incentive[0]->id);
                              $this->db->where('empselect', $incentive[0]->staff_id);
                              $incantive_result = $this->db->get('tbl_incentice_select')->result();
                              $box_count = count($incantive_result);
                              $sr = 1;
                              foreach($incantive_result as $rr){
                            ?>

                        <div class="row" id="inc_<?= $rr->id; ?>">
                           <br>
                            <div class="col-md-3"> <input type="text" id="credit_score" autocomplete="false" name="credit_score[]" placeholder="=1*" class="form-control" value="<?= $rr->credit_score; ?>" required="required"/></div>
                            <div class="col-md-3"> <input type="text" id="ctc" autocomplete="false" name="ctc[]" placeholder="CTC" class="form-control" readonly="readonly" value="QCTC" required="required"/></div>
                            <div class="col-md-3"> <input type="text" id="incantive" autocomplete="false" name="incantive[]" placeholder="0%" class="form-control" value="<?= $rr->incantive; ?>" required="required"/></div>
                            <!-- <span class="btn btn-danger" onclick="remove_inc('<?= $rr->id; ?>');">Remove</span> -->
                            <span class="remove  btn btn-danger">Remove</span>
                        </div>
                         <?php $sr++; }  ?>
                        <div class="add_row"></div>
                       
                        <br>
                        
                        </div>
                         <input type="hidden" value="<?= $box_count;?>" id="box_count" name="box_count">
                        <span class="add  btn btn-info">Add More</span>
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
		  $.ajax({
	           url: '/admin/leave/show_team_member',
	           type: 'POST',
	           data: {team_id: teams},
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
	   var x = $("#box_count").val();//initlal text box count

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

     /*$('.optionBox').on('click','.remove_inc',function() {

      
      });
*/
	});

    function remove_inc(id){
      var incantive_Id = $("#incantive_Id").val();
      //alert(incantive_Id);
      var x = $("#box_count").val();
      $.ajax({
        type: "GET",
        url: "<?= admin_url('payroll/delete_inc/'); ?>"+id,
        data: {id: id,incantive_Id: incantive_Id},
        success: function (res) {
          $('.optionBox').html(res);
        }
      });
    }
</script>
</body>
</html>
