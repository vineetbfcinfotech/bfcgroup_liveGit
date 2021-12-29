<?php init_head(); ?> <div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
         
          <br>
          <div class="panel_s">
            <div class="panel-body">
              
              <div id="loading-image" style="display: none; text-align: center;">
              <img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
              </div>
              <div class="table-responsive">
                         <!--<table class="table dt-table scroll-responsive example11">-->
                    <table class="table dt-table table-responsive scroll-responsive tablebusie" id="emailStatus"  cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                      <thead>
                        <tr role="row">
                           <th>S.No.</th>
            <!--                <th>Project Name</th> -->
                           <th>Author Name</th>
                           <th>Email</th>
                           <th>Contact Number</th>
                           <th>Package Name</th>
                           <th>Book Type</th>
                           <th>Total Amount</th>
                           <th>Date</th>
                           <th>Download</th>
                           
                        </tr>
                      </thead>
                        <tbody>
                          <?php
                          $i=0;
                            //  print_r($newproject_data);
                            foreach($project_data as $row){
                              $i++;
                                $status = $row->project_status;
                                $author = $row->lead_author_name;
                                $booktitle = $row->lead_booktitle;
                                $projectname= $author . "_" . $booktitle;
                                $Package_name = '';
                                if ($row->lead_package_detail == 1) {
                                 $Package_name = 'Standard';
                                }else if ($row->lead_package_detail == 2) {
                                 $Package_name = 'Customized';
                                }else if ($row->lead_package_detail == 3) {
                                  $Package_name = 'Standard Customized';
                                }else{

                                }
                              ?>
                        <tr>
                           <td><?php echo $i;?></td>
                           <td><?php echo $author;?></td>
                           <td><?= $row->email;?></td>
                           <td><?= $row->phonenumber;?></td>
                           <td><?= $Package_name;?></td>
                           <td><?php echo $row->lead_book_type;?></td>
                           <td><?= $row->lead_packg_totalamount;?></td>
                           <td><?php echo $row->createPackEmailDate;?></td>
                           <?php if($row->lead_pdf_data != null){?>
                            <th> <a class="btn btn-info" target="_blank" href="<?= base_url('assets/authorMail/'.$row->lead_pdf_data);?>">Download</a></th>
                          <?php }else{ ?>
                                <th> <a class="btn btn-danger">No File Found</a></th>
                           <?php }?>
                         
                          
                        </tr>
                        <?php } ?>
                
                      </tbody>
                  </table>
                  </div>
              <hr>
              <br>
              
            </div>
          </div>
              <div id="author_data" class="modal fade" role="dialog">

              <div class="modal-dialog">



              <!-- Modal content-->

              <div class="modal-content">

              <form action="<?php echo base_url() ?>admin/leads/addRemarks" method="POST">

              <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal">&times;</button>

              <h4 class="modal-title">Package Details:</h4>

              </div>

              <div class="modal-data">



              </div>

              <span id="alert-msg"></span>

              </form>

              </div>



              </div>

              </div>
        </div>
      </div>
    </div> <?php init_tail(); ?> 
    </body>
    </html>