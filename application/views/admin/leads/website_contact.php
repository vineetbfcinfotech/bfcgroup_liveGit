<?php init_head(); ?>
<?php // init_clockinout(); ?>
<?php //echo '<pre>';print_r($business);echo '</pre>';?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<!-- Custom Filter -->
<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4);?>">
<div class="content" style="min-width: 1900px;">
   <div class="row">
      <div class="col-md-12">
         <div class="panel_s">
            <div class="_buttons">
               <h3>
            </div>
            
            <div  class="panel-body" style="width: 1024px !important;">
               <div class="clearfix"></div>
               <hr class="hr-panel-heading"/>
               <div class="tableData">
                  <!--<a href="export" type="button" class="btn btn-success">Export csv</a><br>-->
                  <p><?php echo $links; ?></p>
                  <p><?php echo $pagination_number; ?></p>
                  <div>
                     <!--<label for="s_global">SEARCH</label>
                        <input type="search" name="s_global" id="s_global" onkeyup="globalFilter()">-->
                  </div>
                  <div class="row">
                       <div class="col-md-2">
                 <a href="JavaScript:Void(0);" onclick="goBack()"  class="btn btn-primary "
                     onclick="">
                  Back</a></div>
               <div class="col-md-2">
                 
                  <!--   <a href="<?= admin_url();?>Leads/exports_data"  class="btn btn-primary ">
                     Export All</a> -->
                  <a href="JavaScript:Void(0);"  class="btn btn-primary export_filter">
                  Export</a>
                  <?php if(is_admin()){ ?>
                  <?php }  ?>
               </div>
               <div class="col-md-3">
                        <form method='post' action="<?= base_url() ?>admin/leads/contact_data_clear_filter" >
                           <input class="btn btn-primary" type='submit' name='submit' value='Clear Filter'>
                        </form>
                     </div>
               <div class="col-md-5">
                  <form method='post' action="<?= base_url() ?>admin/leads/websitecontact" >
                     <!-- </form>
                        <form method='post' action="<?= base_url() ?>admin/leads/assignedleads_array" > -->
                     <div class="dropdown bootstrap-select show-tick">
                        <input type="text" id="start_date" autocomplete="false" value="<?= $start_date;?>"  name="start_date"
                           placeholder="From Date" class="form-control datepicker custom_lead_filter"/>
                     </div>
                     <div class="dropdown bootstrap-select show-tick">
                        <input type="text" id="end_date" autocomplete="false" value="<?= $end_date;?>" name="end_date"
                           placeholder="To Date" class="form-control datepicker custom_lead_filter"/>
                     </div>
                     <input class="btn btn-primary" type='submit' name='submit_cat' value='Search'>
                  </form>
                  
               </div>
               
            </div>
                  <div class="row">
                    <!-- <div class="col-md-1">
                        <form method='post' action="<?= base_url() ?>admin/leads/websitecontact" >
                           <input type='text' name='search_global' value='<?= $search ?>'>
                           <input class="btn btn-primary" type='submit' name='submit' value='Search'>
                        </form>
                     </div>-->
                     
                     <!--  <from action="<?php echo base_url() ?>admin/leads/assignedleads_array" method="POST">
                        <label for="s_global">SEARCH</label>
                        <input type="search" name="search_global"  >
                        <input type='submit' name='submit' value='Submit'>
                        </from> -->
                  </div>
                  <br/>
                  <div class="mytable">
                      
                     <table id="example33" class="  dataTable no-footer display table table-responsive table-striped table-bordered" cellspacing="0" width="100%" height="100%" role="grid" aria-describedby="example33_info" style="width: 100%;">
                        <thead>
                           <tr>
                              <th class="bold" style="backgroud-color:red !important"><b>Sr. NO.</b></th>
                              <th class="bold"><b>Name</b></th>
                              <th class="bold"><b>Email ID</b></th>
                              <th class="bold"><b>Phone Number</b></th>
                              <th>From</th>
                              <th>Created Date</th>
                           </tr>
                        </thead>
                        <tbody id="tbody" class="">
                           <?php $i = 1;
                              foreach ($leads as $r) : ?>
                           <tr id="rowData<?php echo $r->id; ?>" >
                              <td><?php echo $i; ?></td>
                              <td><?php echo $r->name; ?></td>
                              <td><?php echo $r->email; ?></td>
                              <td><?php echo $r->phone; ?></td>
                              <td>Contact</td>
                              <td><?= $r->created_at;?></td>
                           </tr>
                           <!-- Modal -->
                           <!-- Modal close -->
                           <?php $i++;
                              endforeach ?>
                        </tbody>
                     </table>
                  </div>
                  <p><?php echo $links; ?></p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

</div>
<?php init_tail(); ?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

<script>
   $( document ).ready(function() {
   
           $(document).on('change','#staff_name,#tasktypefilter,#start_date,#end_date',function(){
   
               dataTable.draw();
   
             });
   
   });
   
        
   
   
   
     $('.export_filter').click(function(){
         
   
      
   
       var start_date = $('#start_date').val();
   
       var end_date = $('#end_date').val();
   var total_date = start_date+end_date;
       //console.log(tasktypefilter.length);
    
       if(total_date != '' ){$.ajax({
   
              url: '<?php echo base_url('admin/leads/contact_filter_export');?>',
   
              type: 'POST',
   
              data: {start_date: start_date, end_date: end_date},
   
              error: function() {
   
                 alert('Something is wrong');
   
              },
   
             success: function(data) {
   
                var json_pre = data;
   
               var json  = JSON.parse(json_pre, function (key, value) {
   
                 if (key == "name") {
   
                   return value;
   
                 }else{
   
                     return value;
   
                 }
   
   
               });
                console.log('test');
               console.log(json);
   
               var csv = JSON2CSV(json); 
   
               var downloadLink = document.createElement("a");
   
               var blob = new Blob(["\ufeff", csv]);
   
               var url = URL.createObjectURL(blob);
   
               downloadLink.href = url;
   
               downloadLink.download = "website_leads.csv";
   
               document.body.appendChild(downloadLink);
   
               downloadLink.click(); return false;
   
              }
   
           });
     }else{
         alert('Please Select date filter');
   
           
       }
   
      
   
     });
   
     function JSON2CSV(objArray) {
   
       var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
   
       var str = '';
   
       var line = '';
   
   
   
       if ($("#labels").is(':checked')) {
   
           var head = array[0];
   
           if ($("#quote").is(':checked')) {
   
               for (var index in array[0]) {
   
                   var value = index + "";
   
                   line += '"' + value.replace(/"/g, '""') + '",';
   
               }
   
           } else {
   
               for (var index in array[0]) {
   
                   line += index + ',';
   
               }
   
           }
   
   
   
           line = line.slice(0, -1);
   
           str += line + '\r\n';
   
       }
   
   
   
       for (var i = 0; i < array.length; i++) {
   
           var line = '';
   
   
   
           if ($("#quote").is(':checked')) {
   
               for (var index in array[i]) {
   
                   var value = array[i][index] + "";
   
                   line += '"' + value.replace(/"/g, '""') + '",';
   
               }
   
           } else {
   
               for (var index in array[i]) {
   
                   line += array[i][index] + ',';
   
               }
   
           }
   
   
   
           line = line.slice(0, -1);
   
           str += line + '\r\n';
   
       }
   
       return str;
   
   }
    $(document).on('click', '.edit_lead', function(){
   
          
   
             
   
             var otarray = $(this).attr("data-otarray");
   
             var id = $(this).attr("data-id");
   
           //   alert(id);
   
           // alert(otarray);
   
            $.ajax({
   
                  url: "<?php echo base_url(); ?>admin/leads/getallleads",
   
                  method: 'POST',
   
                  data: {
   
                      id: id,
   
                      otarray:otarray
   
                  },
   
                  success: function (datar) 
   
                  {
   
                   console.log(datar);
   
                   var obj = JSON.parse(datar);
   
                      $.ajax({
   
                          url: "<?php echo base_url(); ?>admin/leads/allleadremark2",
   
                          method: 'POST',
   
                          data: {
   
                              id: id,
   
                              name: obj.full_name,
   
                              email:obj.email,
   
                              phonenumber: obj.phone_number,
   
                              calling_objective:obj.ad_name,
   
                              booktitle:obj.booktitle,
   
                              book_format:obj.book_format,
   
                              publishedEarlier:obj.PublishedEarlier,
   
                              description:obj.categorisation,
   
                              next_callingd:obj.next_callingd,
   
                              next_calling:obj.next_callingd,
   
                              otherphonenumber:obj.otherphonenumber_n,
   
                              status: obj.categorisation,
   
                              manuscript:obj.manuscript_status,
   
                              language:obj.user_language,
   
                          },
   
                          success: function (data)
   
                          {
                              $("#product_catagory").html(data);
   
                              $('#phonenumber').attr("disabled","disabled");
   
                              
   
                              $("label[for='calling_objective']").text("Ad Name");
   
                              $('#calling_objective').attr("disabled","disabled");
   
                              $("#descriptions").removeAttr( "autocomplete" );
   
                            
   
                              $("form").attr('autocomplete', 'off'); 
   
                              $('#product_catagory').modal('show');
                           
                          }
   
                      });
   
                  }
   
              });
   
   
          $('#additional').append(hidden_input('id', id));
   
   
       });
   
   $(document).on('click', '.prviewdata', function(){
   
           // alert('testt');
   
            var author_id = $(this).attr("data-id");
   
         $.ajax({
   
         type: "POST",
   
         url: "<?php echo admin_url('Leads/getPackageData'); ?>",
   
         data: {'author_id': author_id}, // <--- THIS IS THE CHANGE
   
         dataType: "html",
   
         success: function(data){
   
            $(".modal-data").html(data);
   
            $("#author_data").modal('show');
   
         },
   
         error: function() { alert("Error posting feed."); }
   
          });
   
       });
      
   
</script>
<script>
   function goBack() {
   
     window.history.back();
   
   }
   $(document).ready(function() {
       $('#example33').dataTable({dom: 'lrt',bFilter: false,});
 //$('#example33').DataTable();
});
</script>

</body>
</html>