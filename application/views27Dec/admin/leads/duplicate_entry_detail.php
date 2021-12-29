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
                      
                        <div class="row">
                            <button style="max-width: 20%;" class="btn btn-primary btn-block" onclick="goBack()">Go Back</button>
                            <!--<a href="<?php //echo base_url() ?>admin/leads/availablewp" class="btn btn-primary"> Back </a>-->
                             <?php if (!empty($getLeads) > 0) { ?>
							 <form action="<?php echo base_url() ?>admin/leads/delete_duplicateleads" method="POST">
							 <input type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger" name="delete_leads" value="Delete Selected" style="float: right;margin-bottom: 20px;">
                                <table class="table dt-table scroll-responsive">
                                    <thead>
                                    <th><?php echo _l('id'); ?></th>
                                    
                                    <th class="bold">Id</th>
                                    <th class="bold">Name</th>
                                    <th class="bold">Contact Number</th>
                                
                                    <!--<th class="bold">Company</th>
             
                                    <th class="bold">Data Source</th>
                                    <th class="bold">Calling Objective</th>
                                    <th class="bold">Calling Date</th>
                                    <th class="bold" style="min-width: 250px;">Remark</th>
                                    <th>Status</th>
                                    <th class="bold">Lead Source</th>-->
                                    <th class="bold">Assigned To</th>
                                    
                                    </thead>
                                    <tbody class="ajax-data">
                                    <?php $j = 0; foreach ($getLeads as $alllead) { 
                                    $this->db->select('*');
                    $this->db->where('lead_id_start <=', $alllead->id);
                    $this->db->where('lead_id_end >=', $alllead->id);
                    $res = $this->db->get('tblleads_custom')->row();
                   // echo $res->name;
                    ?>
                                        <tr>
                                            <td>
                                            <?php if ($j == 0) {?>
                                                <?= @++$i; ?>
                                            <?php }else{ ?>
                                                 <input type="checkbox" name="delete_id[]" value="<?php echo $alllead->id; ?>"> <?= @++$i; ?> <a onclick="return confirm('Are you sure?')" href="<?= sprintf(base_url('admin/leads/delete_addedleads/%s'), $alllead->id); ?>"><i class="fa fa-trash text-danger"></i></a>
                                                 <?php } ?></td>
                                           
                                              <td><?= $alllead->id; ?></td>
                                            <td><?= $alllead->lead_author_name; ?></td>
                                            
                                            <td><?php echo $alllead->phonenumber;?></td>
                                           
 <td><?= $alllead->firstname." ".$alllead->lastname; ?></td>
                                            

                                            <?php
                                            $colid = $alllead->status;
                                            $this->db->where('id', $colid);
                                            $result = $this->db->get('tblleadsstatus')->result();

                                            ?>
                                            <td><?= $result->name; ?></td>
                                          
                                            <td><? if( $alllead->source == "1") {echo "Via Campaign"; } elseif($alllead->source == "6") {echo "Via Qc";} elseif($alllead->source == "8") {echo "Via Reference";}    elseif($alllead->source == "5") {echo "Others";}    ?> </td>

                                            <td> <a href="<?= base_url('admin/profile/');?><?= $alllead->staffid; ?>">  <?= $alllead->fullname; ?></a> </td>
                                            
                                            
                                            
                                        </tr>
                                    <?php $j++; } ?>
                                    </tbody>
                                </table>
								</form>
                            <?php } else { ?>
                                <p class="no-margin"><?php echo "No Lead Find" ?></p>
                            <?php } ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
function goBack() {
  window.history.back();
}
</script>

</body>
</html>
