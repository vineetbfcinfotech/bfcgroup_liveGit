<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="dropdown bootstrap-select show-tick">
                                <input type="button" class = "btn btn-primary" onclick="tableToExcel('testTable', 'W3C Example Table')" value="Export">
                            </div>
                        </div>
                        <div class="row">

                        <?php $converted_by = $this->session->userdata('staff_user_id'); 
                            if (is_admin()){ ?>
                                <select id="filterrm" multiple data-none-selected-text="Select PC"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php  if (!empty($staff_list)) {
                                foreach ($staff_list as $stf) { ?>
                                    <option value="<?= $stf['staffid']; ?>"><?= $stf['firstname']; ?> <?= $stf['lastname']; ?></option>
                                <?php }
                            } ?>
                        </select>
                            <?php }else {
                                if (count($staff_list) > 1) { ?>
                                <select id="filterrm" multiple data-none-selected-text="Select PC"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php  if (!empty($staff_list)) {
                                $id = $this->session->userdata('staff_user_id');
                            
                        
                                foreach ($staff_list as $stf) {
                                    if ($id == $stf['staffid']) {
                            
                                    }else{ ?>
                                    <option value="<?= $stf['staffid']; ?>"><?= $stf['firstname']; ?> <?= $stf['lastname']; ?></option>
                                <?php } }
                            } ?>
                        </select>
                                
                            <?php }?>



                            <?php }?>
                        
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
                                <input type="button" class = "btn btn-primary" onclick="window.location.reload();" value="Clear Filter">
                            </div>
                        </div>
					</div>	
				</div>	
			</div>	
						
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>View DCR</h3></center>
                         <?
                        $loginid = $this->session->userdata('staff_user_id');
                        $this->db->where('staffid', $loginid);

                        $this->db->select('CONCAT(tbll.firstname, " ", tbll.lastname) AS fullname,tbll.staffid ');
                        $data = $this->db->get('tblstaff as tbll')->result();
                        //print_r($data);
                        ?>
                      
                        <div class="row">
                            <table id="example33" summary="Code page support in different versions of MS Windows." rules="groups" frame="hsides" border="2" class="table table-bordered scroll-responsive">
							
                                <thead>
									<tr>
                                   <?php if (is_admin()){ ?>
										<td class="bold">PC Name</td>
                                        <?php } else if (count($staff_list) > 1) {  ?>
                                            <td class="bold">PC Name</td>
                                       <?php }?>
										<td class="bold">Total Calls</td>
                                        <td class="bold">Interacted with</td>
										<td class="bold">Acquired</td>
										<td class="bold">A</td>
										<td class="bold">B+</td>
										<td class="bold">B</td>
										<td class="bold">C</td>
										<td class="bold">NP</td>
										<td class="bold">Scrap</td>
									</tr>
                                </thead>
                                
                                <tbody  id="tblResult">
                                   <?php
								   $data_c = array(39,5,16,38,30,32,41);
                                   $data_count = array(39,5,38,16,30,32,41,40);
                                   $i=0;
                                   $sdate = date('Y-m-01 00:00:00');
                                   $edate = date('Y-m-31 23:59:59');
                                //    echo $sdate;
                                //    echo $edate;
									 if (is_admin()){
										
										foreach ($dcrList as $value) {
                                  
                                            ?>
                                        <tr>
                                            <td class="bold"><?= $value->firstname.' '.$value->lastname?></td>
                                            <?php foreach ($data_count as $key => $data_c_a) { 
                                                $getData_all =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array(); 
                                            $all[$i] += $getData_all[0]['cnt'];
                                       }?>
                                            <td class="bold"><?=$all[$i];?></td>
                                            <?php $j=0; foreach ($data_c as $key => $data_c_a) {
                                                   if ($data_c_a  == 32) {
                                                    $getData_np =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array();  
                                                $getData_np_all[$i] += $getData_np[0]['cnt'];
                                                $full_count = $all[$i] -  $getData_np_all[$i];
                                                }
                                           $j++; } ?>
                                             <td class="bold"><?=$full_count;?></td>
                                            <?php foreach ($data_c as $key => $data_c_a) {
                                               
                                            $getData =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array(); ?>
                                           
                                           <td class="bold"><?php echo($getData[0]['cnt'])?></td>
                                        <?php } ?>
                                            
                                        
                                        </tr>
                                        <?php $i++;	}
                                 
									 }else if(count($staff_list) > 1){
                                       $id = $this->session->userdata('staff_user_id');
                                        foreach ($staff_list as $value) {
                                  if ($id == $value['staffid']) {
                                     
                                  }else{

                                  
                                            ?>
                                        <tr>
                                            <td class="bold"><?= $value['firstname'].' '.$value['lastname']?></td>
                                            <?php foreach ($data_count as $key => $data_c_a) { 
                                                $getData_all =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$value['staffid'],'lead_category_id'=>$data_c_a))->result_array(); 
                                            $all[$i] += $getData_all[0]['cnt'];
                                             }?>
                                            <td class="bold"><?=$all[$i];?></td>
                                            <?php foreach ($data_c as $key => $data_c_a) {
                                                   if ($data_c_a  == 32) {
                                                    $getData_np =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$value['staffid'],'lead_category_id'=>$data_c_a))->result_array();  
                                              
                                                    $getData_np_all[$i] += $getData_np[0]['cnt'];
                                                $full_count = $all[$i] -  $getData_np_all[$i];
                                                }
                                           } ?>
                                             <td class="bold"><?=$full_count;?></td>
                                            <?php foreach ($data_c as $key => $data_c_a) {
                                             $getData =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$value['staffid'],'lead_category_id'=>$data_c_a))->result_array(); ?>
                                            <td class="bold"><?php echo($getData[0]['cnt'])?></td>
                                          <?php } ?>
                                            
                                        
                                        </tr>
                                        <?php $i++;}	}
                                     }
                                     else{ //print_r($staff_list); ?>
                                        <tr>
										<!-- <td class="bold"><?= $staff_list[0]->firstname.' '.$staff_list[0]->lastname?></td> -->
                                        <?php foreach ($data_count as $key => $data_c_a) { 
                                            $getData_all =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$staff_list[0]['staffid'],'lead_category_id'=>$data_c_a))->result_array(); 
                                        $all[$i] += $getData_all[0]['cnt'];
                                         }?>
										<td class="bold"><?=$all[$i];?></td>
                                        <?php foreach ($data_c as $key => $data_c_a) {
                                                   if ($data_c_a  == 32) {
                                                    $getData_np =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$staff_list[0]['staffid'],'lead_category_id'=>$data_c_a))->result_array();  
                                              
                                                    $getData_np_all[$i] += $getData_np[0]['cnt'];
                                                $full_count = $all[$i] -  $getData_np_all[$i];
                                                }
                                           } ?>
                                             <td class="bold"><?=$full_count;?></td>
                                        <?php foreach ($data_c as $key => $data_c_a) {
                                         $getData =   $this->db->select('assigned,COUNT(*) as cnt')->where('lead_approve_current_date >=', $sdate)->where('lead_approve_current_date <=', $edate)->get_where('tblleads',array('assigned'=>$staff_list[0]['staffid'],'lead_category_id'=>$data_c_a))->result_array(); ?>
                                        <td class="bold"><?php echo($getData[0]['cnt'])?></td>
                                      <?php } ?>

									
									</tr>
									<?php }
								 
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
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

<script>
     $(document).on('change', '.custom_lead_filter', function () {
      const filterrm = $('#filterrm').val(),
            transctiondatestart = $('#transctiondatestart').val();
            transctiondateend = $('#transctiondateend').val();
          //  filterstatus = $('#filterstatus').val();
          
		 
        url = "<?= base_url('admin/DCR/custom_view_dcr_filter') ?>";
        
        $.get(url, {
                filterrm: filterrm,
                transctiondatestart: transctiondatestart,
                transctiondateend:transctiondateend
            },
            function (res) {
                
                $('#tblResult').html(res);
            })
    });
     $('#example33').dataTable({searching: true, paging: true, info: false});
</script>

</body>
</html>
