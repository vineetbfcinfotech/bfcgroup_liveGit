 <?php if(!empty($data['allleads']) > 0){ ?>
                      <table class="table dt-table scroll-responsive">
                        <thead>
                          <th><?php echo _l('id'); ?></th>
                          <th class="bold">Name</th>
                                                                  <th class="bold">Contact Number</th>
                                                                  <th class="bold">Designation</th>
                                                                  <th class="bold">Company</th>
                                                                  <th class="bold">Address</th>
                                                                  <!--<th class="bold">Email Id</th>-->
                                                                  <th class="bold">Data Source</th>
                                                                  <th class="bold">Calling Objective</th>
                                                                  <th class="bold">Calling Date</th>
                                                                  <th class="bold">Remark</th>
                          <th>Status</th>
                        </thead>
                        <tbody>
                          <?php foreach($data['allleads'] as $alllead){ ?>
                          <tr>
                            <td><?= @++$i; ?></td>
                             <td><a href="#" onclick="edit_product_catagory(this,<?= $alllead->id; ?>);return false;"  data-id="<?= $alllead->id; ?>" data-name="<?= $alllead->name; ?>" data-phonenumber="<?= $alllead->phonenumber; ?>"  data-email="<?= $alllead->email; ?>" data-designation="<?= $alllead->designation; ?>"data-company="<?= $alllead->company; ?>"data-address="<?= $alllead->address; ?>"data-data_source="<?= $alllead->data_source; ?>"data-calling_objective="<?= $alllead->calling_objective; ?>" data-status="<?= $alllead->status; ?>" data-description="<?= $alllead->description; ?>"> <?= $alllead->name; ?></a></td>
                            <td><?= $alllead->phonenumber; ?></td>
                             <td><?= $alllead->designation; ?></td>
                             
                             <td><?= $alllead->company; ?></td>
                             <td><?= $alllead->address; ?></td>
                            <!-- <td><?= $alllead->email; ?></td>-->
                             <td><?= $alllead->data_source; ?></td>
                             <td><?= $alllead->calling_objective; ?></td>
                             <td><?= date('d M, Y', strtotime($alllead->lastcontact)); ?></td>
                             <td><?= $alllead->description; ?></td>
                
                          <!--  <td><?= $alllead->status;?></td> -->
                       <?php
                       $colid = $alllead->status;
                       $this->db->where('id',$colid); 
                       $result=$this->db->get('tblleadsstatus')->result();
                       
                       ?>
                       
                          <td>
                              <span class="inline-block label label-" style="color:#28B8DA;border:1px solid #28B8DA"> 
                        <select name="rate" id="lead_status_change" data-lead-id="<?= $alllead->id; ?>" >
                                <?php foreach($data['lstatus'] as $leadst){
                                ?>
                          <option value="<?= $leadst->id;    ?>"  <?php  $lead =$leadst->id; if("<?= $leadst->id;?>" == "<?= $alllead->status;?>"){  ?> selected <?php }?> > <?= $leadst->name; ?></option>
                          
                                <?}?>
                                </select></span>
                        </td>
                 
                        
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <?php } else { ?>
                      <p class="no-margin"><?php echo "No Lead Find" ?></p>
                      <?php }  ?>