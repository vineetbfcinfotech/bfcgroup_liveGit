<?php init_head(); ?>
<style>
   #inprocessavailablewp label, #completeavailablewp label, #nullavailablewp label{
	min-height: 36px;
   }
   .remove_field.btn.btn-info, .remove_field1.btn.btn-info,.remove_field2.btn.btn-info {
	margin-top: 44px;
   }
   .panel_s .panel-body {
    background: #dce1ef;
    border: 1px solid #dce1ef;
    border-radius: 4px;
    padding: 20px;
    position: relative;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
}

</style>
<div id="wrapper">
   <?php init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body" >
                  <!--<center>-->
                     <!--<h3>Available Working Person</h3>-->
                     <h3><b>Manuscript Status :: Completed</b></h3><br/><br/>
                  <!--</center>-->
                  <?php //print_r($leads);exit;?>
                  <form data-parsley-validate="" id="completeavailablewp" name="completeavailablewp" novalidate="" role="form" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/leads/availablewp" method="post" class="">
                     <?php //echo form_open($this->uri->uri_string()); ?>
                     <div class="input_fields_wrap">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="form-group" app-field-wrapper="date">
                                 <label for="date" class="control-label">Date</label>
                                 <input type="text" id="startdate" name="date" class="form-control " value="<?php echo  date("Y-m-d");?>" autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-3">
                              <?php if($totLeadscompleted != 0){ ?>
                              <div class="form-group" app-field-wrapper="date">
                                 <label for="date" class="control-label">Total Leads</label>
                                 <input type="text" id="totLeads" name="totLeads" class="form-control " value="<?php echo $totLeadscompleted?>" autocomplete="off" readonly>
                              </div>
                              <?php }?>
                              <?php if($totLeadscompleted == 0){ ?>
                              <div class="form-group" app-field-wrapper="date">
                                 <label for="date" class="control-label">
                                    <h2>No Leads</h2>
                                 </label>
                              </div>
                              <?php }?>
                           </div>
                           <?php if($totLeadscompleted != 0){ ?>
                           <div class="col-md-3">
                              <div class="form-group" app-field-wrapper="date">
                                 <label for="date" class="control-label">Remaining Leads</label>
                                 <!--<input type="text" id="remainLeads" name="totLeads" class="form-control " value="<?php //echo $totLeadscompleted?>" autocomplete="off" readonly> -->
                                 <input type="text" id="remainLeadsinprocess2" name="totLeads" class="form-control " value="<?php echo $totLeadscompleted?>" autocomplete="off" style="" readonly>
                              </div>
                           </div>
                        </div>
                        <!--<div class="clearfix"></div>
                           <hr class="hr-panel-heading" />-->
                        <div class="clearfix"></div>
                        <div class="row">
                            <?php //echo "<pre>"; echo "tes";print_r($pcList[0]->staffid);?>
                           <div class="col-md-3 role row1">
                               <label for="telermids[]" class="control-label">Select Publishing Consultant</label>
                               <select id="telermids[]" name="telermids[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                                   <option value=""></option>
                                 <?php for($i=0; $i<count($pcList); $i++){?>
                                  
                                   <option value="<?php echo $pcList[$i]->staffid?>"><?php echo $pcList[$i]->firstname?></option>
                                   <?php }?> 
                                   </select>
                               <?php //for($i=0;$i <=count($pcList);$i++)?>
                              <?php //render_select('telermids[]',$telerm,array('staffid','firstname'),'Publishing Consultant'); ?>
                           </div>
                           <div class="col-md-3 row1">
                              <div class="form-group">
                                 <label for="sel1">No of Leads to be allotted:</label>
                                 <input type="text" class="form-control firstitem"  name="leadFrom[]"/>
                              </div>
                              <?//= render_select('wpids[]',$availwp1,array('staffid','firstname','as WP'),'Available Leads'); ?>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group add_field_button" app-field-wrapper="add_more_roles">
                                 <label for="add_more_team_role" class="control-label addmore11">  </label>
                                 <input style="margin-top:3.6rem;margin-left: 4px;" type="button" value="Add More" class="btn btn-info" id="add_more_team_role11">
                                 <i class="fa fa-plus plus11" style="display:none;"></i><i class="fa fa-minus minus11" style="display:none;"></i></button>
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php }?>
                     <div id="addnewrole"></div>
                     <?php if($totLeadscompleted != 0){ ?>
                     <div class="col-md-12">
                        <button type="submit" class="btn btn-info pull-right" <?php if($totLeadscompleted == 0) echo "disabled";?>><?php echo _l('submit'); ?></button>
                     </div>
                     <?php  }?>
                  </form>
                  <?//= form_close(); ?>
                  <!--<div class="clearfix"></div>
                     <hr class="hr-panel-heading" />
                     <div class="clearfix"></div>-->
                  <?php if ( !empty($assignedwp) > 0 ) { ?>
                  <center>
                     <!--<h3>Available Working Person List</h3>-->
                  </center>
                  <table class="table dt-table scroll-responsive">
                     <thead>
                        <th><?php echo _l('Sr.no'); ?></th>
                        <th><?php echo "Date"; ?></th>
                        <th><?php echo _l('options'); ?></th>
                     </thead>
                     <tbody>
                        <?php if ( !empty($assignedwp) ) {
                           foreach ($assignedwp as $team) { ?>
                        <tr>
                           <td><?= @++$i; ?></td>
                           <td><?= $team->date; ?></td>
                           <td>
                              <a href="<?php echo admin_url('leads/editavailablewp/' . $team->id); ?>"
                                 class="btn btn-info btn-icon"><i
                                 class="fa fa-pencil"></i></a>
                              <a href="<?php echo admin_url('leads/deleavailablewp/' . $team->date); ?>"
                                 class="btn btn-danger btn-icon _delete"><i
                                 class="fa fa-remove"></i></a>
                           </td>
                        </tr>
                        <?php }
                           } ?>
                     </tbody>
                  </table>
                  <?php } ?>
               </div>
            </div>
         </div>
		 <?php if($totLeadscompleted == 0){ ?>
		 </div>
		 </div>
		 <?php } ?>
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
<!--                  <center>-->
                     <!--<h3>Available Working Person</h3>-->
                     <h3><b>Manuscript Status :: In Process</b></h3><br/><br/><br/>
                  <!--</center>-->
                  <form data-parsley-validate="" id="inprocessavailablewp" name="inprocessavailablewp" novalidate="" role="form" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/leads/availablewpinprocess" method="post" class="">
                     <div class="input_fields_wrap1">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="form-group" app-field-wrapper="date">
                                 <label for="date" class="control-label">Date</label>
                                 <input type="text" id="startdate" name="date" class="form-control " value="<?php echo  date("Y-m-d");?>" autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-3">
                              <?php if($totLeadsinprogress != 0){ ?>
                              <div class="form-group" app-field-wrapper="date">
                                 <label for="date" class="control-label">Total Leads</label>
                                 <input type="text" id="totLeadsinprocess" name="totLeads" class="form-control " value="<?php echo $totLeadsinprogress?>" autocomplete="off" readonly>
                              </div>
                              <?php }?>
                              <?php if($totLeadsinprogress == 0){ ?>
                              <div class="form-group" app-field-wrapper="date">
                                 <label for="date" class="control-label">
                                    <h2>No Leads</h2>
                                 </label>
                              </div>
                              <?php }?>
                           </div>
                           <?php if($totLeadsinprogress != 0){ ?>
                           <div class="col-md-3">
                              <div class="form-group" app-field-wrapper="date">
                                 <label for="date" class="control-label">Remaining Leads</label>
                                 <input type="text" id="remainLeadsinprocess" name="totLeads" class="form-control " value="<?php echo $totLeadsinprogress?>" autocomplete="off" readonly>
                              </div>
                           </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                         <!--   <div class="col-md-3 role row11">
                              <?= render_select('telermids[]',$telerm,array('staffid','firstname'),'Publishing Consultant'); ?>
                           </div> -->
                            <div class="col-md-3 role row11">
                               <label for="telermids[]" class="control-label">Publishing Consultant</label>
                               <select id="telermids[]" name="telermids[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                                   <option value=""></option>
                                 <?php for($i=0; $i<count($pcList); $i++){?>
                                  
                                   <option value="<?php echo $pcList[$i]->staffid?>"><?php echo $pcList[$i]->firstname?></option>
                                   <?php }?> 
                                   </select>
                               <?php //for($i=0;$i <=count($pcList);$i++)?>
                              <?php //render_select('telermids[]',$telerm,array('staffid','firstname'),'Publishing Consultant'); ?>
                           </div>
                           <div class="col-md-3 row11">
                              <div class="form-group">
                                 <label for="sel1">No of Leads to be allotted:</label>
                                 <input type="text" class="form-control addressitem11 firstitem1" id="leadsinprocess" name="leadFrom[]"/>
                              </div>
                              <?//= render_select('wpids[]',$availwp1,array('staffid','firstname','as WP'),'Available Leads'); ?>
                           </div>
                           <div class="col-md-3 row11" >
                              <div class="form-group add_field_button1" app-field-wrapper="add_more_roles">
                                 <label for="add_more_team_role" class="control-label addmore11">  </label>
                                 <input style="margin-left: 4px;margin-top:3.6rem;" type="button" class="btn btn-info" value="Add More" id="add_more_team_role11">
                                 <i class="fa fa-plus plus11" style="display:none;"></i><i class="fa fa-minus minus11" style="display:none;"></i></button>
                              </div>
                           </div>
                           <?php }?>
                        </div>
                     </div>
                     <div id="addnewrole"></div>
                     <?php if($totLeadsinprogress != 0){ ?>
                     <div class="col-md-12">
                        <button type="submit" class="btn btn-info pull-right" <?php if($totLeadsinprogress == 0) echo "disabled";?>><?php echo _l('submit'); ?></button>
                     </div>
                     <?php  }?>
                  </form>
                  <!--<div class="clearfix"></div>
                     <hr class="hr-panel-heading" />-->
                  <div class="clearfix"></div>
                  <?php if ( !empty($assignedwp) > 0 ) { ?>
                  <center>
                     <h3>Available Working Person List</h3>
                  </center>
                  <table class="table dt-table scroll-responsive">
                     <thead>
                        <th><?php echo _l('Sr.no'); ?></th>
                        <th><?php echo "Date"; ?></th>
                        <th><?php echo _l('options'); ?></th>
                     </thead>
                     <tbody>
                        <?php if ( !empty($assignedwp) ) {
                           foreach ($assignedwp as $team) { ?>
                        <tr>
                           <td><?= @++$i; ?></td>
                           <td><?= $team->date; ?></td>
                           <td>
                              <a href="<?php echo admin_url('leads/editavailablewp/' . $team->id); ?>"
                                 class="btn btn-info btn-icon"><i
                                 class="fa fa-pencil"></i></a>
                              <a href="<?php echo admin_url('leads/deleavailablewp/' . $team->date); ?>"
                                 class="btn btn-danger btn-icon _delete"><i
                                 class="fa fa-remove"></i></a>
                           </td>
                        </tr>
                        <?php }
                           } ?>
                     </tbody>
                  </table>
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <!--<center>-->
                     <!--<h3>Available Working Person</h3>-->
                     <h3><b>Manuscript Status :: N/A</b></h3><br/><br/>
                     
                  <!--</center>-->
                  <?php //print_r($leads);exit;?>
                  <form data-parsley-validate="" id="nullavailablewp" name="nullavailablewp" novalidate="" role="form" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/leads/availablewpnull" method="post" class="">
				  <div style="border-style: ridge;" class="input_fields_wrap2">
				  <div class="row">
                     <?php //echo form_open($this->uri->uri_string()); ?>
                     <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="date">
                           <label for="date" class="control-label">Date</label>
                           <input type="text" id="startdate" name="date" class="form-control " value="<?php echo  date("Y-m-d");?>" autocomplete="off">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <?php if($totLeadsnull != 0){ ?>
                        <div class="form-group" app-field-wrapper="date">
                           <label for="date" class="control-label">Total Leads</label>
                           <input type="text" id="totLeadsnull" name="totLeadsnull" class="form-control " value="<?php echo $totLeadsnull?>" autocomplete="off" readonly>
                        </div>
                        <?php }?>
                        <?php if($totLeadsnull == 0){ ?>
                        <div class="form-group" app-field-wrapper="date">
                           <label for="date" class="control-label">
                              <h2>No Leads</h2>
                           </label>
                        </div>
                        <?php }?>
                     </div>
					 <div class="col-md-3">
                        <?php if($totLeadsnull != 0){ ?>
                        <div class="form-group" app-field-wrapper="date">
                           <label for="date" class="control-label">Remaining Leads</label>
                           <input type="text" id="remainingLeadsna" name="remainingLeadsna" class="form-control " value="<?php echo $totLeadsnull?>" autocomplete="off" readonly>
                        </div>
                        <?php }?>
                     </div>
                     </div>
                     
					 
					 
                     <div class="clearfix"></div>
					 <?php if($totLeadsnull != 0){ ?>
					<div class="row">
                     <!-- <div class="col-md-3 role row666">
                        <?= render_select('telermids[]',$telerm,array('staffid','firstname'),'Select Publishing Consultant'); ?>
                     </div> -->
                     <div class="col-md-3 role row666">
                               <label for="telermids[]" class="control-label">Select Publishing Consultant</label>
                               <select id="telermids[]" name="telermids[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                                   <option value=""></option>
                                 <?php for($i=0; $i<count($pcList); $i++){?>
                                  
                                   <option value="<?php echo $pcList[$i]->staffid?>"><?php echo $pcList[$i]->firstname?></option>
                                   <?php }?> 
                                   </select>
                               <?php //for($i=0;$i <=count($pcList);$i++)?>
                              <?php //render_select('telermids[]',$telerm,array('staffid','firstname'),'Publishing Consultant'); ?>
                           </div>

                     <div class="col-md-3 row666">
                        <div class="form-group">
                           <label for="sel1">No of Leads to be allotted:</label>
                           <input type="text" class="form-control addressitem555" id="leads555" name="leadFrom[]"/>
                        </div>
                        <?//= render_select('wpids[]',$availwp1,array('staffid','firstname','as WP'),'Available Leads'); ?>
                     </div>
                     <div class="col-md-3 row666">
                        <div class="form-group" app-field-wrapper="add_more_roles">
                            <input type="hidden"/>
                           <label for="add_more_team_role" class="control-label addmore666">  </label>
                           <input type="button" style="margin-top:3.6rem;" class="btn btn-info add_field_button2" id="add_more_team_role666" value="Add More" <?php if($totLeadsnull == 0) echo "disabled";?>>
                           <i class="fa fa-minus minus666" style="display:none;"></i>
                        </div>
                     </div>
					</div>
					 <?php } ?>
					</div>
					<div class="row">
                     <div id="addnewrole"></div>
                     <?php if($totLeadsnull != 0){ ?>
                     <div class="col-md-12">
                        <button type="submit" class="btn btn-info pull-right" <?php if($totLeadsnull == 0) echo "disabled";?>><?php echo _l('submit'); ?></button>
                     </div>
					</div>
                     <?php  }?>
                  </form>
                  <?//= form_close(); ?>
                  <!--<div class="clearfix"></div>
                     <hr class="hr-panel-heading" />
                     <div class="clearfix"></div>-->
                  <?php if ( !empty($assignedwp) > 0 ) { ?>
                  <center>
                     <h3>Available Working Person List</h3>
                  </center>
                  <table class="table dt-table scroll-responsive">
                     <thead>
                        <th><?php echo _l('Sr.no'); ?></th>
                        <th><?php echo "Date"; ?></th>
                        <th><?php echo _l('options'); ?></th>
                     </thead>
                     <tbody>
                        <?php if ( !empty($assignedwp) ) {
                           foreach ($assignedwp as $team) { ?>
                        <tr>
                           <td><?= @++$i; ?></td>
                           <td><?= $team->date; ?></td>
                           <td>
                              <a href="<?php echo admin_url('leads/editavailablewp/' . $team->id); ?>"
                                 class="btn btn-info btn-icon"><i
                                 class="fa fa-pencil"></i></a>
                              <a href="<?php echo admin_url('leads/deleavailablewp/' . $team->date); ?>"
                                 class="btn btn-danger btn-icon _delete"><i
                                 class="fa fa-remove"></i></a>
                           </td>
                        </tr>
                        <?php }
                           } ?>
                     </tbody>
                  </table>
                  <?php } ?>
               </div>
            </div>
         </div>
		 
		 
      </div>
   </div>
</div>
<?php init_tail(); ?>
<script>
   $(function(){
   	_validate_form($('form'),{date:'required'});
   });
</script>
<script>
   $(document).ready(function(){
      
      $("#startdate").addClass("datetimepicker");
   });
</script>
<script>
   $('#startdate').datetimepicker({
      format: 'Y-m-d',
      minDate: '0',
      onShow: function (ct) {
          this.setOptions({
              minDate: 0
          })
      },
      timepicker: false
   });
</script>
<script>
   /***** Start for Completed Manuscript *******/
   $(document).ready(function() {
       var max_fields = 15; //maximum input boxes allowed
       var wrapper = $(".input_fields_wrap"); //Fields wrapper
       var add_button = $(".add_field_button"); //Add button ID
       var x = 1; //initlal text box count
       $(add_button).click(function(e) { //on add input button click
           e.preventDefault();
           if (x < max_fields) { //max input box allowed
               x++; //text box increment
               $(wrapper).append('<div class="row"><div class="col-md-3 role"><div class="form-group" app-field-wrapper="telermids[]"><label for="telermids[]" class="control-label">Publishing Consultant</label><div class="dropdown bootstrap-select" style="width: 100%;"><select  id="telermids[]" name="telermids[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98"><option  value=""></option><?php foreach($pcList as $telrm){ ?><option  value="<?php echo $telrm->staffid; ?>"><?php echo $telrm->firstname; ?></option><? } ?></select><div class="dropdown-menu open" role="combobox" style="max-height: 192px; overflow: hidden; min-height: 152px;"><div class="bs-searchbox"><input type="text" class="form-control" autocomplete="off" role="textbox" aria-label="Search"></div><div class="inner open" role="listbox" aria-expanded="false" tabindex="-1" style="max-height: 136px; overflow-y: auto; min-height: 96px;"><ul class="dropdown-menu inner "><li class="selected active"><a role="option" aria-disabled="false" tabindex="0" class="selected active" aria-selected="true"><span class="glyphicon glyphicon-ok check-mark"></span><span class="text"></span></a></li><?php foreach($pcList as $telrm){ ?><li><a role="option" aria-disabled="false" tabindex="0" aria-selected="false"><span class="glyphicon glyphicon-ok check-mark"></span><span class="text"><?php echo $telrm->firstname; ?></span></a></li><? } ?></ul></div></div></div></div></div><div class="col-md-3 row1"><div class="form-group"><label for="sel1">No of Leads to be allotted:</label><input type="text" class="form-control addressitem"  name="leadFrom[]"/></div></div><div style="cursor:pointer;background-color:red;margin-left:3rem;" class="remove_field btn btn-info">Remove</div></div>'); //add input box
           }
		   $('.selectpicker').selectpicker('refresh');
       });
       $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
           e.preventDefault();
           $(this).parent('div').remove();
           x--;
       })
   });
   
   $(document).on('keyup','.firstitem',function(){
   	var valueField = $(this).val()*1;
   	var totLeads = $('#totLeads').val()*1;
   	var firstitem = $('.firstitem').val()*1;
   	var remainLeads = $('#remainLeadsinprocess2').val()*1;
   	//alert(valueField);
   	var allVal = '';
   	  $(".addressitem").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
   	var res = allVal.split(",");
   	
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	console.log(valuess);
   	if(valuess > 0 || valuess == totLeads){
   		$('#remainLeadsinprocess2').val(valuess);
   	}else{
   		if(totLeads > valueField || totLeads == valueField){
   			var calRemainLeads = totLeads - valueField;
   			$('#remainLeadsinprocess2').val(calRemainLeads);
   		}else{
   			alert('No of allotted leads should be less than remaining Leads');
   			$(this).val('0');
   			$('#remainLeadsinprocess2').val(totLeads);
   		}
   	}
   });
   
   $(document).on('keyup','.addressitem',function(){
   	var valueField = $(this).val()*1;
   	var remainLeads = $('#remainLeadsinprocess2').val()*1;
   	var abc = $('.addressitem').val()*1;
   	var firstitem = $('.firstitem').val()*1;
   	
   	var totLeads = $('#totLeads').val()*1;
   	
   	var allVal = '';
   	  $(".addressitem").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
   	var res = allVal.split(",");
   	
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	console.log(valuess);
   	if(valuess > 0 || valuess == totLeads){
   		$('#remainLeadsinprocess2').val(valuess);
   	}else{
   		var calRemainLeads = remainLeads - valueField;
   		//alert(totLeads +"--"+ valuess +"----"+ calRemainLeads);
   		if(remainLeads > valueField || valuess == 0){
   			$('#remainLeadsinprocess2').val(valuess);
   		}else{
   			alert('No of allotted leads should be less than remaining Leads');
   			$(this).val('0');
   			$('#remainLeadsinprocess2').val(remainLeads);
   		}
   	}
   });
   $(document).on('click','.remove_field',function(){
   	var firstitem = $('.firstitem').val()*1;
   	var totLeads = $('#totLeads').val()*1;
   	var allVal = '';
   	  $(".addressitem").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
   	var res = allVal.split(",");
   	
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	console.log(valuess);
   	if(valuess != 0){
   		$('#remainLeadsinprocess2').val(valuess);
   	}
   });
   /***** Stop for Completed Manuscript *******/
   
   /***** Start for In Process Manuscript *******/
   $(document).ready(function() {
       var max_fields = 15; //maximum input boxes allowed
       var wrapper = $(".input_fields_wrap1"); //Fields wrapper
       var add_button = $(".add_field_button1"); //Add button ID
       var x = 1; //initlal text box count
       $(add_button).click(function(e) { //on add input button click
           e.preventDefault();
           if (x < max_fields) { //max input box allowed
               x++; //text box increment
               $(wrapper).append('<div class="row"><div class="col-md-3 role"><div class="form-group" app-field-wrapper="telermids[]"><label for="telermids[]" class="control-label">Publishing Consultant</label><div class="dropdown bootstrap-select" style="width: 100%;"><select id="telermids[]" name="telermids[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98"><option value=""></option><?php foreach($pcList as $telrm){ ?><option value="<?php echo $telrm->staffid; ?>"><?php echo $telrm->firstname; ?></option><? } ?></select><div class="dropdown-menu open" role="combobox" style="max-height: 192px; overflow: hidden; min-height: 152px;"><div class="bs-searchbox"><input type="text" class="form-control" autocomplete="off" role="textbox" aria-label="Search"></div><div class="inner open" role="listbox" aria-expanded="false" tabindex="-1" style="max-height: 136px; overflow-y: auto; min-height: 96px;"><ul class="dropdown-menu inner "><li class="selected active"><a role="option" aria-disabled="false" tabindex="0" class="selected active" aria-selected="true"><span class="glyphicon glyphicon-ok check-mark"></span><span class="text"></span></a></li><?php foreach($pcList as $telrm){ ?><li><a role="option" aria-disabled="false" tabindex="0" aria-selected="false"><span class="glyphicon glyphicon-ok check-mark"></span><span class="text"><?php echo $telrm->firstname; ?></span></a></li><? } ?></ul></div></div></div></div></div><div class="col-md-3 row1"><div class="form-group"><label for="sel1">No of Leads to be allotted:</label><input type="text" class="form-control addressitem1"  name="leadFrom[]"/></div></div><div style="cursor:pointer;background-color:red;margin-left:3rem;" class="remove_field1 btn btn-info">Remove</div></div>'); //add input box
           }
		   $('.selectpicker').selectpicker('refresh');
       });
       $(wrapper).on("click", ".remove_field1", function(e) { //user click on remove text
           e.preventDefault();
           $(this).parent('div').remove();
           x--;
       })
   });
   
   $(document).on('keyup','.firstitem1',function(){
   	var valueField = $(this).val()*1;
   	var totLeads = $('#totLeadsinprocess').val()*1;
   	var firstitem = $('.firstitem1').val()*1;
   	var remainLeads = $('#remainLeadsinprocess').val()*1;
   	//alert(valueField);
   	var allVal = '';
   	  $(".addressitem1").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
	  //alert(allVal);
   	var res = allVal.split(",");
   	
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	console.log(valuess);
   	if(valuess > 0 || valuess == totLeads){
   		$('#remainLeadsinprocess').val(valuess);
   	}else{
   		if(totLeads > valueField || totLeads == valueField){
   			var calRemainLeads = totLeads - valueField;
   			$('#remainLeadsinprocess').val(calRemainLeads);
   		}else{
   			alert('No of allotted leads should be less than remaining Leads');
   			$(this).val('0');
   			$('#remainLeadsinprocess').val(totLeads);
   		}
   	}
   });
   
   $(document).on('keyup','.addressitem1',function(){
   	var valueField = $(this).val()*1;
   	var remainLeads = $('#remainLeadsinprocess').val()*1;
   	var abc = $('.addressitem1').val()*1;
   	var firstitem = $('.firstitem1').val()*1;
   	
   	var totLeads = $('#totLeadsinprocess').val()*1;
   	
   	var allVal = '';
   	  $(".addressitem1").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
   	var res = allVal.split(",");
   	
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	//console.log(valuess);
   	//console.log(valueField);
   	
   	if(valuess > 0 || valuess == totLeads){
   		$('#remainLeadsinprocess').val(valuess);
   	}else{
   		var calRemainLeads = remainLeads - valueField;
   		//alert(remainLeads +"---"+ totLeads +"--"+ valuess +"----"+ calRemainLeads);
   		if(remainLeads > valueField || valuess == 0 || remainLeads == valueField){
   			$('#remainLeadsinprocess').val(valuess);
   		}else{
   			alert('No of allotted leads should be less than remaining Leads');
   			$(this).val('0');
   			$('#remainLeadsinprocess').val(remainLeads);
   		}
   	}
   });
   $(document).on('click','.remove_field1',function(){
   	var firstitem = $('.firstitem1').val()*1;
   	var totLeads = $('#totLeadsinprocess').val()*1;
   	var allVal = '';
   	  $(".addressitem1").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
   	var res = allVal.split(",");
   	
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	console.log(valuess);
   	if(valuess != 0){
   		$('#remainLeadsinprocess').val(valuess);
   	}
   });
   /******* Stop for In Process Manuscript*********/
   
   /***** Start for Not Applicable Manuscript *******/
   $(document).ready(function() {
       var max_fields = 15; //maximum input boxes allowed
       var wrapper = $(".input_fields_wrap2"); //Fields wrapper
       var add_button = $(".add_field_button2"); //Add button ID
       var x = 1; //initlal text box count
       $(add_button).click(function(e) { //on add input button click
           e.preventDefault();
           if (x < max_fields) { //max input box allowed
               x++; //text box increment
               $(wrapper).append('<div class="row"><div class="col-md-3 role"><div class="form-group" app-field-wrapper="telermids[]"><label for="telermids[]" class="control-label">Publishing Consultant</label><div class="dropdown bootstrap-select" style="width: 100%;"><select id="telermids[]" name="telermids[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98"><option value=""></option><?php foreach($pcList as $telrm){ ?><option value="<?php echo $telrm->staffid; ?>"><?php echo $telrm->firstname; ?></option><? } ?></select><div class="dropdown-menu open" role="combobox" style="max-height: 192px; overflow: hidden; min-height: 152px;"><div class="bs-searchbox"><input type="text" class="form-control" autocomplete="off" role="textbox" aria-label="Search"></div><div class="inner open" role="listbox" aria-expanded="false" tabindex="-1" style="max-height: 136px; overflow-y: auto; min-height: 96px;"><ul class="dropdown-menu inner "><li class="selected active"><a role="option" aria-disabled="false" tabindex="0" class="selected active" aria-selected="true"><span class="glyphicon glyphicon-ok check-mark"></span><span class="text"></span></a></li><?php foreach($pcList as $telrm){ ?><li><a role="option" aria-disabled="false" tabindex="0" aria-selected="false"><span class="glyphicon glyphicon-ok check-mark"></span><span class="text"><?php echo $telrm->firstname; ?></span></a></li><? } ?></ul></div></div></div></div></div><div class="col-md-3 row1"><div class="form-group"><label for="sel1">No of Leads to be allotted:</label><input type="text" class="form-control addressitem2"  name="leadFrom[]"/></div></div><div style="cursor:pointer;background-color:red;margin-left:3rem;" class="remove_field2 btn btn-info">Remove</div></div>'); //add input box
           }
		   $('.selectpicker').selectpicker('refresh');
       });
       $(wrapper).on("click", ".remove_field2", function(e) { //user click on remove text
           e.preventDefault();
           $(this).parent('div').remove();
           x--;
       })
   });
   
   $(document).on('keyup','.addressitem555',function(){
   	var valueField = $(this).val()*1;
   	var totLeads = $('#totLeadsnull').val()*1;
   	var firstitem = $('.addressitem555').val()*1;
   	var remainLeads = $('#remainingLeadsna').val()*1;
   	//alert(valueField);
   	var allVal = '';
   	  $(".addressitem2").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
   	var res = allVal.split(",");
   	
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	//console.log(valuess);
   	if(valuess > 0 || valuess == totLeads){
   		$('#remainingLeadsna').val(valuess);
   	}else{
   		if(totLeads > valueField || totLeads == valueField){
   			var calRemainLeads = totLeads - valueField;
   			$('#remainingLeadsna').val(calRemainLeads);
   		}else{
   			alert('No of allotted leads should be less than remaining Leads');
   			$(this).val('0');
   			$('#remainingLeadsna').val(totLeads);
   		}
   	}
   });
   
   $(document).on('keyup','.addressitem2',function(){
	var valueField = $(this).val()*1;
   	var totLeads = $('#totLeadsnull').val()*1;
   	var firstitem = $('.addressitem555').val()*1;
   	var remainLeads = $('#remainingLeadsna').val()*1;
   	
   	var allVal = '';
   	  $(".addressitem2").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
   	var res = allVal.split(",");
   	//alert(valueField);
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	//console.log(valuess);
   	//console.log(valueField);
   	
   	if(valuess > 0 || valuess == totLeads){
   		$('#remainingLeadsna').val(valuess);
   	}else{
   		var calRemainLeads = remainLeads - valueField;
   		//alert(remainLeads +"---"+ totLeads +"--"+ valuess +"----"+ calRemainLeads);
   		if(remainLeads > valueField || valuess == 0 || remainLeads == valueField){
   			$('#remainingLeadsna').val(valuess);
   		}else{
   			alert('No of allotted leads should be less than remaining Leads');
   			$(this).val('0');
   			$('#remainingLeadsna').val(remainLeads);
   		}
   	}
   });
   $(document).on('click','.remove_field2',function(){
   	var firstitem = $('.addressitem555').val()*1;
   	var totLeads = $('#remainingLeadsna').val()*1;
   	var allVal = '';
   	  $(".addressitem2").each(function() {
   		allVal += $(this).val()*1+",";
   	  });
   	var res = allVal.split(",");
   	
   	var valuess = "";
   	for (i = 0; i < res.length; i++) {
   	  valuess = (res[i]*1) + (valuess*1);
   	}
   	valuess = totLeads-((firstitem*1)+(valuess*1));
   	console.log(valuess);
   	if(valuess != 0){
   		$('#remainingLeadsna').val(valuess);
   	}
   });
   /******* Stop for Not Applicable Manuscript*********/
</script>
</body>
</html>