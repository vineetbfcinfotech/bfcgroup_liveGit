<?php init_head(); ?>
<div id="wrapper">
   <?php //init_clockinout(); ?>
   <style type="text/css">
    

   </style>
   <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                     <a href="#"  class="btn mright5 btn-info pull-left display-block">
                     Back  </a>
                  </div>
                  <div class="clearfix"></div>
                  <hr class="hr-panel-heading" />
                  <form action="<?php echo base_url() ?>admin/pco/sendprintQuotation" method="POST">
                  <div class="row">
                        <div class="col-md-4">
                          <?php if (!empty($all_data)) { ?>
                            <input type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-primary" name="delete_leads" value="Send Mail" style="float: right;margin-bottom: 20px;">
                         <?php }else{} ?>
						
                            <h3><!--<a href="" class="btn btn-primary">Send Mail</a>--></h3>  
                        </div>
                    </div>
                  <table class="table dt-table scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                     <thead>
                        <tr role="row">
                           <th>-</th>
                           <th>Sr. No.</th>
                           <th>Author Name</th>
                           <th>Book Title</th>
                           <th>Contact No.</th>
                          
                           <th>Pages</th>
                           <th>Size</th>
                           <th>Text color</th>
                           <th>Text paper</th>
                           <th>Cover paper</th>
                           <th>Binding</th>
                           <th>Lamination</th>
                           
                           <th>Book Qty</th>
                           <th>Books Quantity Deliver to Author</th>
                           <th>Books Quantity Deliver to Publisher</th>
                           <th>Publisher Address</th>
                           <th>Author Address</th>
                           <th>Per copy rate including GST & delivery </th>
                           <th>total</th>

                        </tr>
                     </thead>
                     <tbody>
                        <?php $i = 1; foreach ($all_data as  $value) { ?>
                         
                         
                           <tr>
                               <td>
                                            <input type="checkbox" name="data_id[]" value="<?php echo $value->id; ?>"> 
                                            <a onclick="return confirm('Are you sure?')" href="<?= sprintf(base_url('admin/pco/sendMail/%s'), $result[$i]->id); ?>">
                                                
                                                </a>
                                                
                                           
                                            </td>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $value->lead_author_name; ?></td>
                              <td><?php echo $value->lead_booktitle; ?></td>
                              <td><?php echo $value->phonenumber; ?></td>
                            
                              <td><?php echo $value->lead_book_pages; ?></td>
                              <td>
                                  <?php if ($value->lead_book_size==1) {
                              echo '5*8';
                            }
                            else if($value->lead_book_size==2) {
                              echo '6*9';
                            } else if($value->lead_book_size==3) {
                              echo '9*11';
                            } ?>   
                              </td>
                              <td>B/W</td>
                              <td><?php if ($value->paper_type == 1) {
                                echo "Creamy";
                              
                              }else if($value->paper_type == 2){
                                echo "White";
                              } ?></td>
                              <td>250 GSM</td>
                              <td><?= $value->book_cover_sc; ?></td>
                              <td>
                                  <?php if ($value->lamination==1) {
                              echo 'Gloss';
                            }
                            else if($value->lamination==2) {
                              echo 'Matte';
                            } ?>   
                              </td>
                             
                              <!-- <td><?php // echo $value->complimentry_copies; ?></td> -->
                                <td><?php echo $value->total_number_of_copies; ?></td>
                              <td><?php echo $value->complimentry_copies; ?></td>
                             
                              <td><?php echo $value->total_number_of_copies-$value->complimentry_copies; ?></td>
                              <td>CP - 61| Viraj Khand | Gomti Nagar | Lucknow - 226010</td>
                              <td><?php  $asf =$this->db->get_where('chorus_asf',array('lead_id'=>$value->id))->row(); 
                              echo $asf->asf_address; ?></td>
                               <td></td>
                              <td></td>
                              
                           </tr>
                           <?php $i++; } ?>
                        </tbody>
                     </table>
                  </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<?php init_tail(); ?>
</body>
</html>