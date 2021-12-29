 
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
                                      <?php foreach($leaveAppplication as $application){
                                        
                                          $this->db->where('staffid',$application->user_id); 
                                          $result=$this->db->get('tblstaff')->result();
                                          
                                      ?>
                                       <tr class="odd" id="rowid_<?php echo $application->leave_application_id; ?>">
                                      <td ><?php echo $result[0]->firstname.' '.$result[0]->lastname; ?></td>
                                     
                                      <td> <?php if( $application->leave_type == "multiple_days"){ echo date('j M, Y',strtotime($application->leave_start_date)).' -To- '.date('j M,Y',strtotime($application->leave_end_date)); } else { echo   date('j M, Y',strtotime($application->leave_start_date)); }  ?>
                                     </td>
                                     <td><?php echo  $application->leavename; ?></td> 
                                       <td><?= $application->duration ?></td>
                                       
                                       <?php if($application->attachment != '') { ?>
                          
                                      <td><a href="<php base_url();?>assets/attachment/<php $application->attachment; ?>" target="_blank" >View</a> </td>
                                  
                                  
                                  <? }else{ ?>
                          
                                      <td></td>
                                    
                                  <?php }?>
                                      
                                    
                                    </tr>     
                                    <?php }?>
                                    </tbody>
                                </table>