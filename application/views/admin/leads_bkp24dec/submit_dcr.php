<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                         <?
                        $loginid = $this->session->userdata('staff_user_id');
                        $this->db->where('staffid', $loginid);

                        $this->db->select('CONCAT(tbll.firstname, " ", tbll.lastname) AS fullname,tbll.staffid ');
                        $data = $this->db->get('tblstaff as tbll')->result();
                        //print_r($data);
                        ?>
                        <form action="<?php echo base_url() ?>admin/leads/saveDcr" method="POST">
                        <div class="row">
                            <div class="col-md-3">
                                <h5>RM Name: <?= $data[0]->fullname; ?></h5>

                                    <h5>Date: 
                                    <input type="text" onchange="getCallCount()" name="date_work" id="date_work" class="form-control datepicker"  data-format="dd-mm-yyyy" data-parsley-id="17" placeholder="Select Date.." autocomplete="off" required="">
							
								
                                    </h5>
                                    <input type="hidden" id="staff_id" name="staff_id" value="<?= $data[0]->staffid; ?>"/>
                                   
                                    
                                    <h5>Total Calls: 
                                    <input readonly type="text" name="total_calls" id="total_calls" class="form-control " value=""  placeholder="Total Calls" autocomplete="off" required="">
                                    </h5>
                            </div>
                        </div>
                        
                        <div class="row">
                            <table class="table scroll-responsive">
                                <thead>
                                <tr>
                                       <?php foreach($leadstatus as $status) {?>
                                    <td class="bold">
                                         <?= $status[name]; ?>
                                    </td>
                                    <? }
                                    $countTd=count($leadstatus);
                                    $countTd=$countTd + 3;
                                    ?> 
                                    <td class="bold">
                                        Calling Obejective
                                    </td>
                                    <td class="bold">
                                        Data Source
                                    </td>
                                    <td class="bold">
                                        Calling Pitch
                                    </td>
                                </tr>
                                </thead>
                                
                                <tbody  id="tblResult">
                                   
                                </tbody>
                             
                            </table>
							
							<div class="col-md-12" id="otherTask" style="display:none">
                                <h5>Other Task: </h5>
                               
							   <textarea  onkeyup="checkTask()" name="other_work" id="other_work" class="form-control" placeholder="Other Task" autocomplete="off"></textarea>
							   
							   <h5>Other Work Duration: </h5>
                               <input type="text" name="other_work_duration" id="other_work_duration" class="form-control"  placeholder="Other Work Duration" autocomplete="off">
                                    </h5>
                            </div>
							
                        </div>
                        
                          <input type="submit" name="submit" class="btn btn-info" value="Save">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    function getCallCount()
	{
	  var data_url = "<?= base_url('admin/leads/get_dcr_call_count') ?>";
      var dtWrk=$('#date_work').val();
      var stfId=$('#staff_id').val();

          $.ajax({
           type: "GET",
           url: data_url, 
           data: {work_date: dtWrk, staff_id: stfId },
           dataType: "text",  
           cache:false,
           success: 
              function(data){
                 $('#total_calls').val(data);
                 if(data!=0)
                 {
                     	  var data_urlTwo = "<?= base_url('admin/leads/get_dcr_call_data') ?>";
                          var dtWrk=$('#date_work').val();
                          var stfId=$('#staff_id').val();
                    
                              $.ajax({
                               type: "GET",
                               url: data_urlTwo, 
                               data: {work_date: dtWrk, staff_id: stfId },
                               dataType: "text",  
                               cache:false,
                               success: 
                                  function(data){
                                     $('#tblResult').html(data);
                                  }
                                });// you have missed this bracket
                 }
                 
                 document.getElementById('otherTask').style.display="block";

              }
            });// you have missed this bracket
			
	}
	
	function checkTask()
	{
	  var vl=$('#other_work').val();
	  if(vl!="")
	  {
	    $('#other_work_duration').attr('required',true);
	  }
	  else
	  {
	     $('#other_work_duration').attr('required',false);
	  }
	}
</script>
</body>
</html>
