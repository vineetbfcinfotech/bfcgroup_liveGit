<?php init_head(); ?>
<div id="wrapper">
  <style type="text/css">
    .border-none{
      border:0px!important;
    }
  </style>
  <?php //init_clockinout(); ?>
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
       <table class="table dt-table scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
         <thead>
          <tr role="row">
           <th>Sr. No.</th>
           
           <th>Author Name</th>
           <th>PC</th>
           <th>Date of Acquisition</th>
           <th>Email</th>
           <th>Contact No.</th>
           <th>First Payment</th>
           <th>Final Payment</th>
           
           
         </tr>
       </thead>
       <tbody>
        <?php $i =1; foreach ($projects as $key => $value) { ?>
          
         
          <tr>
           <td><?= $i; ?></td>
           
           <td><?=  $value->lead_author_name; ?></td>
           <?php $pc =  $this->db->get_where('tblstaff',array('staffid'=>$value->assigned))->row(); ?>
           <td><?= $pc->firstname.' '.$pc->lastname ?></td>
           <td><?= $value->lead_booking_amount_date ?></td>
           <td><?= $value->email ?></td>
           <td><?= $value->phonenumber ?></td>
           <td> <?php if (empty($value->lead_first_payment_receipt)) { ?>
            <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/upload_first_payment" enctype="multipart/form-data"> 
              <div class="form-group">
                <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $value->id; ?>"/>
                <input type="file" class="from-control border-none" name="file">
                <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Upload</button>
              </div>
            </form>

          <?php  }else{ ?>
            <a class="btn btn-success btn-xs " >Uploaded</a>
            <?php  } ?></td>
            <td> <?php if (empty($value->lead_final_payment_receipt)) {
            if ($value->lead_payment_status == 2) {
              
               $remaimning ='';
              if ($value->lead_payment_status == 0) {
                $remaimning = $value->lead_package_cost;
              }else if ($value->lead_payment_status == 1) {
                $remaimning = $value->lead_package_cost - $value->lead_recived_booking_amount;
              }else if ($value->lead_payment_status == 2){
                $remaimning = $value->lead_recived_booking_amount + $value->first_installment_amount;

                $remaimning = $value->lead_package_cost - $remaimning;

              }else if ($value->lead_payment_status == 3){
                $remaimning = $value->lead_package_cost - ($value->lead_recived_booking_amount + $value->first_installment_amount + $value->final_installment_amount);
              } 
            //  echo round($remaimning);
              ?>
              <a class="btn btn-success btn-xs btnShowPopup" data-id="<?php echo $value->id; ?>" data-r_amount="<?php echo $remaimning; ?>">Upload Receipt </a>
             <!-- <input type="button" id="btnShowPopup" value="Show Popup" class="btn btn-info btn-lg" /> -->


             <!--  <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/upload_final_payment" enctype="multipart/form-data"> 
                <div class="form-group">
                  <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $value->id; ?>"/>
                  <input type="file" class="from-control border-none" name="file">
                  <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Upload</button>
                </div>
              </form> -->

            <?php  }else{ ?>
              <a class="btn btn-success btn-xs " >First payment approval due</a>
             
            <?php } }else{ ?>
               <a class="btn btn-success btn-xs " >Uploaded</a>
              <?php  } ?></td>
              
            </tr>
            

            


            
            <?php $i++; } ?>
          </tbody>
          <div id="MyPopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;</button>
                <h4 class="modal-title">
                </h4>
            </div>
            <div class="modal-body">

              <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/upload_final_payment" enctype="multipart/form-data"> 
                <span id="error_msg_data_remaining"></span><br><br>
                <div class="form-group">
                  <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value=""/>
                  <div class="row">
                     <div class="col-md-6">
                  <label>Remaining Balance</label>
               
                  <input type="text" name="r_amount" id="r_amount" class="form-control" disabled="" value=""/>
                </div>
              </div>
              <div class="row">
                     <div class="col-md-6">
                  <label>Available Balance</label>
               
                  <input type="text" name="available_amount" id="available_amount" class="form-control"  value=""/>
                </div>
              </div>
                  <input type="file" class="form-control border-none" name="file"><br><br>
                  <button type="submit" id="submit_upload_agreement" class="btn btn-info submit_upload_agreement" >Upload</button>
                </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    Close</button>
            </div>
        </div>
    </div>
</div>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
<script>
</script>

<?php init_tail(); ?>
</body>
</html>
<script type="text/javascript">
    $(function () {
        $(".btnShowPopup").click(function () {
            var title = "Receipt Payment";
            var id = $(this).data("id");
            var r_amount = $(this).data("r_amount");
             // alert(r_amount);
              $("#hidden_id").val(id);
              $("#r_amount").val(r_amount);
            $("#MyPopup .modal-title").html(title);
            // $("#MyPopup .modal-body").html(body);

            $("#MyPopup").modal("show");
        });
    });

$(document).on('click', '.submit_upload_agreement', function() {
$(".submit_upload_agreement").prop('disabled', true);
    // var data = $('#received_booking_amount').val();
   var id = $("#hidden_id").val();
    var r_amount = $("#r_amount").val();
    var available_amount = $("#available_amount").val();
    
      alert(id);
      alert(r_amount);
      alert(available_amount);
      // 
      var decimal_remaining =Number(r_amount);
      if (available_amount==decimal_remaining) {
          // var id = $("#hidden_id").val();
          var file_data = $("input[name='file']").prop('files')[0];
                var form_data = new FormData();

                form_data.append('file', file_data);
                form_data.append('id', id);

        $.ajax({

          // type: "POST",

          url: "<?php echo admin_url('Pm_lead/upload_final_payment'); ?>",

          // data: {'hidden_id': id, 'payment_status':payment_status, 'data':data, 'author':author, 'book':book},

          dataType: "html",
           type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,

          success: function(data){
            // console.log(data);
            alert_float(data);
            window.location.reload();
          }

        });
      }else{
        $("#error_msg_data_remaining").html('Paid Amount Should be equal to Remaining Amount');
        
      }
      
    });
</script>