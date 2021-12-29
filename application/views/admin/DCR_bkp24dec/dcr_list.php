<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                       <?php $converted_by = $this->session->userdata('staff_user_id'); ?>
                        <select id="filterrm" multiple data-none-selected-text="Filter By Staff"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php  if (!empty($staff_list)) {
                                foreach ($staff_list as $stf) { ?>
                                    <option value="<?= $stf['staffid']; ?>"><?= $stf['firstname']; ?> <?= $stf['lastname']; ?></option>
                                <?php }
                            } ?>
                        </select>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="transctiondatestart" autocomplete="false" name="transctiondatestart"
                                   placeholder="Period From"
                                   class="form-control datepicker custom_lead_filter"/>

                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="transctiondateend" autocomplete="false" name="transctiondateend"
                                   placeholder="Period To"
                                   class="form-control datepicker custom_lead_filter"/>

                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                        <input type="button" onclick="tableToExcel('testTable', 'W3C Example Table')" value="Export to Excel">
                        </div>
					</div>	
				</div>	
			</div>	
						
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3> Calling Report</h3></center>
                         <?
                        $loginid = $this->session->userdata('staff_user_id');
                        $this->db->where('staffid', $loginid);

                        $this->db->select('CONCAT(tbll.firstname, " ", tbll.lastname) AS fullname,tbll.staffid ');
                        $data = $this->db->get('tblstaff as tbll')->result();
                        //print_r($data);
                        ?>
                      
                        <div class="row">
                            <table id="testTable" summary="Code page support in different versions of MS Windows." rules="groups" frame="hsides" border="2" class="table table-bordered scroll-responsive">
							
                                <thead>
									<tr>
										<td class="bold">Staff</td>
										<td class="bold">Date</td>
										<td class="bold">Total Calls</td>
										<?php foreach($leadstatus as $status) 
										{
										?>
										   <td class="bold"><?= $status[name]; ?></td>
										<?
										}
										$countTd=count($leadstatus);
										$countTd=$countTd + 3;
										?> 
										<td class="bold">Calling Obejective</td>
										<td class="bold">Data Source</td>
										<td class="bold">Calling Pitch</td>
										<td class="bold">Other Work</td>
										<td class="bold">Other Work Duration</td>
									</tr>
                                </thead>
                                
                                <tbody  id="tblResult">
                                   <?php
								   foreach($dcrList as $rt)
								   {
								     $unique_dcr_id=$rt['unique_dcr_id'];
									 
									 $this->db->where('unique_dcr_id' , $unique_dcr_id);
									 //$this->db->where('unique_dcr_id' , 2);
									 $this->db->select('*');
									 $getData=$this->db->get('tbldcr')->result_array();
									 
									 
									 $sr=$getData[0];
									 $didOne=$sr['id'];
									 $staffId=$sr['staff_id'];
									 
									 $this->db->where('staffid' , $staffId);
									 $this->db->select('*');
									 $getStaff=$this->db->get('tblstaff')->result_array();
									
								    ?>
									 <tr>
									   <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getStaff[0]['firstname']; ?> <?php echo $getStaff[0]['lastname']; ?></td>
									   <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['dcr_date']; ?> </td>
									   <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['total_call']; ?> </td>
									   
									    <?php 
										foreach($leadstatus as $str)
										{
										  $sid=$str['id'];
										  $this->db->where('status' , $sid);
										  $this->db->where('dcr_id' , $didOne);
										  $this->db->select('call_count');
										  $getStatusData=$this->db->get('tbldcr_status_count')->result_array();
										  
										?>
										  <td class="text-center"><?= $getStatusData[0]['call_count']; ?></td>
										<? 
										}
										
										?> 
										  <td class="text-center"><?php echo $sr['calling_objective']; ?></td>
										  <td class="text-center"><?php echo $sr['data_source']; ?></td>
										  <td class="text-center"><?php echo $sr['calling_pitch']; ?></td>
										  
									   <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['other_work']; ?> </td>
									   <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['other_work_duration']; ?> </td>
									</tr>
									<?php
									foreach($getData as $sr)
									{
									    
									  $did=$sr['id'];
									  if($did!=$didOne)
									  {
						            ?>
									    <tr>
									      <?php
										  foreach($leadstatus as $str)
										  {
											$sid=$str['id'];
											$this->db->where('status' , $sid);
											$this->db->where('dcr_id' , $did);
											$this->db->select('call_count');
											$getStatusData=$this->db->get('tbldcr_status_count')->result_array();
											//print_r($this->db->last_query());
											?>
											<td class="text-center"><?= $getStatusData[0]['call_count']; ?></td>
											<? 
										   }
											?> 
											<td class="text-center"><?php echo $sr['calling_objective']; ?></td>
											<td class="text-center"><?php echo $sr['data_source']; ?></td>
											<td class="text-center"><?php echo $sr['calling_pitch']; ?></td>
											
										</tr>
									<?php
									  }
									}
								   }
								   ?>
                                </tbody>
                            </table>
                        </div>

                    
                    <script type="text/javascript">
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>



</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
     $(document).on('change', '.custom_lead_filter', function () {
      const filterrm = $('#filterrm').val(),
            transctiondatestart = $('#transctiondatestart').val();
            transctiondateend = $('#transctiondateend').val();
          //  filterstatus = $('#filterstatus').val();
          
		 
        url = "<?= base_url('admin/leads/custom_dcr_filter') ?>";
        
        $.get(url, {
                filterrm: filterrm,
                transctiondatestart: transctiondatestart,
                transctiondateend:transctiondateend
            },
            function (res) {
                
                $('#tblResult').html(res);
            })
    });
</script>

</body>
</html>
