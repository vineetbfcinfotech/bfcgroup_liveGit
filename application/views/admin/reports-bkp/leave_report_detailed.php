<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center>Leave Report Detailed</center>
                        
                        <table class="table dt-table scroll-responsive">
                                    <thead>
                                    <tr>
                                      <th>Name</th>
                                      <th>leave date</th>
                                      <th>leave Category</th> 
                                      <th>Duration</th> 
                                      <th>Attachment</th> 
                                    
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                    
                                     <?php foreach($leaveAppplication as $application) {
                                          $this->db->where('staffid',$application->user_id); 
                                          $result=$this->db->get('tblstaff')->result();
                                          //echo $result[0]['firstname'].' '.$lastname;  

                                      ?>
                                       <tr class="odd" id="rowid_<?php echo $application->leave_application_id; ?>">
                                      <td ><?php echo $result[0]->firstname.' '.$result[0]->lastname; ?></td>
                                     
                                      <td> <?php if( $application->leave_type == "multiple_days"){ echo date('j M, Y',strtotime($application->leave_start_date)).' -To- '.date('j M,Y',strtotime($application->leave_end_date)); } else { echo   date('j M, Y',strtotime($application->leave_start_date)); }  ?>
                                     </td>
                                     <td><?php echo  $application->leavename; ?></td> 
                                       <td><?= $application->duration ?></td>
                                       
                                        <?php if($application->attachment != '') { ?>
                          
                              <td><a href="<?= base_url();?>assets/attachment/<?= $application->attachment; ?>" target="_blank" >View</a> </td>
                          
                          
                          <? } ?>
                                    

                                      
                                    
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
<?php init_tail(); ?>
<script>
    
</script>
</body>
</html>
