<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
              <div class="panel_s">
                <div class="panel-body">
                  <a href="<?= admin_url('payroll/incentive_select'); ?>" class="btn btn-info">Add</a>
                </div>
              </div>
                <div class="panel_s">
                    <div class="panel-body">
                      <form action="define_incentive_filter" method="POST" autocomplete="off" class="">
                        <?php 
                          
                          if($this->session->userdata('datestart')==''){
                            $start_date = date('Y').'-04-01';
                            $end_date = ($start_date+1).'-03-31';
                          }else{
                            $start_date =$this->session->userdata('datestart');
                            $end_date =$this->session->userdata('dateend');
                          }
                          
                        ?>  
                          <div class="dropdown bootstrap-select show-tick">
                              <input type="text" id="datestart_filter" autocomplete="false" name="datestart" placeholder="Period From" class="form-control datepicker custom_lead_filter" value="<?= $start_date ?> " />

                          </div>
                          <div class="dropdown bootstrap-select show-tick">
                              <input type="text" id="dateend_filter" autocomplete="false" name="dateend" placeholder="Period To" class="form-control datepicker custom_lead_filter" value="<?= $end_date ?>" />

                          </div>
                          <div class="dropdown bootstrap-select show-tick">
                              <input type="submit" id="filter" autocomplete="false" name="filter" class="btn btn-info" value="Filter" />
                          </div>
                        </form>
                       <!--  <div id="incfff"></div> -->
                        <br><br>
                        <div class="_buttons">
                             <table name="incentive_report"  id="incentive_report" class="table dt-table scroll-responsive">
                                <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th class="bold">Staff Name</th>
                                    <th class="bold">Type</th>
                                    <th class="bold">Finacial Year</th>
                                    <th class="bold">Action</th>
                                </tr>
                                </thead>
                                <tbody class="">
                                <?php foreach($incentive as $alllead) { ?>
                                    <tr class="inc<?= $alllead->id; ?>">
                                        <td><?= @++$i; ?></td>
                                        <td><?php if($alllead->inc_type==1){
                                          echo $alllead->firstname." ".$alllead->lastname;
                                        }else{
                                          $teamTL =  $this->db->where('id', $alllead->teamselect)->get('tblteams')->result();
                                          $staffData = $this->db->where('staffid',$teamTL[0]->staffid)->get('tblstaff')->result();
                                          echo $staffData[0]->firstname.' '.$staffData[0]->lastname;
                                        }?> 
                                        </td>
                                        <td> <?php if($alllead->inc_type==1){
                                          echo "Staff";
                                        }else{
                                          $team = $teamTL[0]->team_name;
                                          $team_arr = explode(' ',$team);
                                           echo $team_arr[0]." Leader";
                                        }?>
                                        </td>
                                        <td><?= $alllead->datestart; ?> - <?= $alllead->dateend; ?></td>
                                        <td><a href="<?= admin_url('payroll/editIncentiveSelect/'); ?><?= $alllead->id; ?>" class="btn btn-warning">Edit</a> | <a href="#" class="btn btn-info" data-toggle="modal" data-target="#view_modal_<?= $alllead->id; ?>">View</a> |<a href="#" class="btn btn-sm btn-danger" onclick="del('<?= $alllead->id; ?>')">Delete</a> </td>
        <div id="view_modal_<?= $alllead->id; ?>" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Edit Incentive</h4>
               </div>
               <div class="modal-body">
                     <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-12 col-sm-12">
                        <div class="panel panel-info" >
                           <div style="padding-top:30px" class="panel-body" >
                            
                              
                              <div class="row">
                     <div class="form-group col-md-6" app-field-wrapper="company"><label for="company" class="control-label">Company</label>
                            <select id="company" name="company[]" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" disabled="" required="required">
                                  <option value="1" <?php if($alllead->company=='1') {echo "selected"; }?>>BFC Capital</option>
                                  <option value="2" <?php if($alllead->company=='2') {echo "selected"; }?>>BFC Infotech</option>
                                  <option value="3" <?php if($alllead->company=='3') {echo "selected"; }?>>BFC Publication</option>
                            </select>  
                        </div>

                        <div class="form-group col-md-6" app-field-wrapper="departments"><label for="departments" class="control-label">Department</label>
                            <select id="departments" name="departments" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" disabled="" tabindex="-98" required="required">
                                <?php 
                               $this->db->where('company_id', $alllead->company);
                               $result = $this->db->get('tbldepartments')->result();
                               foreach($result as $department){ 
                              ?>
                              <option value="<?= $department->departmentid; ?>" <?php if($department->departmentid==$alllead->departments) { echo "selected"; }?>><?php echo $department->name; ?></option>
                              <?php }?>
                            </select>  
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6" app-field-wrapper="teamselect"><label for="teamselect" class="control-label">Group/Team</label>
                            <select id="teamselect" name="teamselect" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" disabled="" required="required">
                              <?php 
                               $this->db->where('department_id', $alllead->departments);
                               $teamresult = $this->db->get('tblteams')->result();
                               foreach($teamresult as $team){ 
                              ?>
                              <option value="<?= $team->id; ?>" <?php if($team->id==$alllead->teamselect) {echo "selected"; }?>><?php echo $team->team_name; ?></option>
                              <?php }?>
                                  
                            </select>  
                        </div>
                      
                        <div class="form-group col-md-6" app-field-wrapper="empselect"><label for="empselect" class="control-label">Staff</label>
                            <select id="empselect" name="empselect" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" disabled=""  required="required">
                              <?php 
                                $select = 'tblstaffdepartments.*, tblstaff.firstname, tblstaff.lastname, tblstaff.bio_id';
                                $this->db->select($select);
                                $this->db->from("tblstaffdepartments");
                                $this->db->join('tblstaff', 'tblstaff.staffid = tblstaffdepartments.staffid');
                                $this->db->where_in('tblstaffdepartments.team_id',$alllead->teamselect);
                                $this->db->where('tblstaff.active ', 1);
                                $query = $this->db->get(); 
                                $staffresult = $query->result();
                                foreach($staffresult as $staff){
                                ?>
                                 <option value="<?= $staff->staffid; ?>" <?php if($staff->staffid==$alllead->staff_id) {echo "selected"; }?>><?php echo $staff->firstname; ?> <?php echo $staff->lastname; ?></option>
                                <?php }?>
                            </select>  
                        </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6" app-field-wrapper="form_date"><label for="form_date" class="control-label">From Date</label>
                            <input type="text" id="datestart" name="datestart" placeholder="Period From" class="form-control datepicker" required="required" value="<?php echo $alllead->datestart; ?>" />
                          </div>
                          <div class="form-group col-md-6" app-field-wrapper="to_date"><label for="to_date" class="control-label">To Date</label>
                            <input type="text" id="dateend" name="dateend" placeholder="Period From" value="<?php echo $alllead->dateend; ?>" class="form-control datepicker" required="dateend"/>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label class="control-label">Credit Score Level</label></div>
                            <div class="col-md-4"><label class="control-label">CTC</label></div>
                            <div class="col-md-4"><label class="control-label">Incentive Slabs</label></div>
                            
                        </div>
                        
                        <div class="optionBox">
                          <?php  
                            $this->db->where('incentive_id',$alllead->id);
                            $incantive_result = $this->db->get('tbl_incentice_select')->result();
                            
                            foreach($incantive_result as $rr){
                          ?>

                        <div class="row block">
                           <br>
                            <div class="col-md-4"> <input type="text" id="credit_score" autocomplete="false" name="credit_score" placeholder="=1*" class="form-control" value="<?= $rr->credit_score; ?>" required="required" readonly="readonly"/></div>
                            <div class="col-md-4"> <input type="text" id="ctc" autocomplete="false" name="ctc[]" placeholder="CTC" class="form-control" readonly="readonly" value="QCTC" required="required"/></div>
                            <div class="col-md-4"> <input type="text" id="incantive" autocomplete="false" name="incantive" placeholder="0%" class="form-control" value="<?= $rr->incantive; ?>" required="required" readonly="readonly"/></div>
                        </div>
                         <?php }  ?>
                        </div>
                        
                     
                           </div>
                        </div>
                  </div>
               </div>

               <div class="modal-footer">
                    <button type="button" class="btn btn-defult" data-dismiss="modal">Close</button>

               </div>

            </div>

         </div>

      </div>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>
<script type="text/javascript">
  function del(id) {
    $.ajax({
      type: "GET",
      url: "<?= admin_url('payroll/delete_incentive/'); ?>"+id,
      data: {dept_id: id},
      success: function (res) {
        $(".inc"+id).remove();
      }
    });
  }

  function filter()
  {
    datestart = $('#datestart_filter').val();
    dateend = $('#dateend_filter').val();
    url = "<?= base_url('admin/payroll/define_incentive_filter') ?>";
    //alert(datestart+'-'+dateend+'-'+url);
    $.ajax({
      type: "GET",
      url: url,
      data: {datestart: datestart,dateend: dateend},
      success: function (res) {
       alert(res);
       $("#incfff").html(res);
      }
    });
  }

</script>
</body>
</html>
