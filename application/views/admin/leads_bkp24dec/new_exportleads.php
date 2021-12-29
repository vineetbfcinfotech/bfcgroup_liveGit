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
                    <div class="panel-body">
                        <div class="_buttons">
                            <h3>
                                <a href="JavaScript:Void(0);" onclick="goBack()"  class="btn btn-primary "
                                   onclick="">
                                    Back</a>
                        </div>
                        <a href="exports_data"  class="btn btn-primary ">
                                    Export All</a>
                                      <a href="JavaScript:Void(0);"  class="btn btn-primary export_filter">
                                    Export</a>
                                    <?php if(is_admin()){ ?>
                                      <select id="staff_name" multiple data-none-selected-text="Select PC"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                          <?php if ( !empty($get_staff) ) {
                                     foreach ($get_staff as $get_comp) { ?>
                                         <option value="<?= $get_comp->staffid; ?>"><?= $get_comp->firstname; ?></option>
                                     <?php }
                                  } ?>
                        </select>
                                     <?php }  ?>
                      
                        <select id="tasktypefilter"  multiple data-none-selected-text="Select Category" name="task_type" data-live-search="true" class="selectpicker custom_lead_filter">
                                   
                                 <?php if ( !empty($get_task_type) ) {
                                     foreach ($get_task_type as $get_comp) { ?>
                                         <option value="<?= $get_comp->id; ?>"><?= $get_comp->name; ?></option>
                                     <?php }
                                  } ?>
                               </select>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="start_date" autocomplete="false" name="start_date"
                                          placeholder="From Date" class="form-control datepicker custom_lead_filter"/>

                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="end_date" autocomplete="false" name="end_date"
                                          placeholder="To Date" class="form-control datepicker custom_lead_filter"/>

                        </div>

                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>
                        <div class="tableData">
                            <table id="table_id" class="display">
    <thead>
                                 <tr><th class="bold"> Sr. No.</th>
                                    <th class="bold"> DB Id</th>
                                   <th class="bold"> Author Name</th>
                                        <th class="bold">Contact No 1</th>
                                        <th class="bold">Contact No 2</th>
                                        <th class="bold">Email Id</th>
                                        <th class="bold">Book Language</th>
                                        <th class="bold">Manuscript Status</th>
                                        <th class="bold">Remarks</th>
                                        <th class="bold">Calling Date</th>
                                        <th class="bold">Category</th>
                                        <th class="bold">Next Calling Date</th>
                                        <th class="bold">Book Format</th>
                                        <th class="bold">Book Title</th>
                                        <th class="bold">Created Date</th>
                                        <th class="bold">Ad Name</th>
                                        <th class="bold">Published Earlier</th>
        </tr>
    </thead>
</table>
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
    var dataTable = $('#table_id').DataTable({
		"bProcessing": true,
         "serverSide": true,
         'searching': false,
         'dom': 'lBfrtip,rtlp',
         'lengthMenu': [[5,10,25,50,100,200,500], [5,10,25,50,100,200,500] ],
          'buttons': 'csvHtml5',
         "aoColumns": [
						{ mData: 'id' } ,
						{ mData: 'id' } ,
						{ mData: 'lead_author_name' } ,
                        { mData: 'phonenumber' },
                        { mData: 'otherphonenumber' },
                        { mData: 'email' },
                        { mData: 'lead_author_mslanguage' },
                        { mData: 'lead_author_msstatus' },
                        { mData: 'description' },
                        { mData: 'lead_callingdate' },
                        { mData: 'name' },
                        { mData: 'ImEx_NextcallingDate' },
                        { mData: 'lead_bookformat' },
                        { mData: 'lead_booktitle' },
                        { mData: 'ImEx_CreatedAt' },
                        { mData: 'lead_adname' },
                        { mData: 'lead_publishedearlier' }
                ],
         "ajax":{
            url :"<?php echo base_url('admin/leads/filter_by_task_type');?>",  // json datasource
            type: "post",  // type of method  , by default would be get
            'data': function(data){
              
                  // Read values
                  var staff_name = $('#staff_name').val();
                  var tasktypefilter = $('#tasktypefilter').val();
                  var start_date = $('#start_date').val();
                  var end_date = $('#end_date').val();
        
                  // Append to data
                  data.staff_name = staff_name;
                  data.tasktypefilter = tasktypefilter;
                  data.start_date = start_date;
                  data.end_date = end_date;
               },
            error: function(){  // error handling code
             // $(".employee-grid-error").html("");
              //$("#employee_grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee_grid_processing").css("display","none");
            }
          },
           "fnRowCallback" : function(nRow, aData, iDisplayIndex){
     
            $("td:first", nRow).html(iDisplayIndex +1);
            return nRow;
        },
        }); 
        
        $(document).on('change','#staff_name,#tasktypefilter,#start_date,#end_date',function(){
            dataTable.draw();
          });
          
  

});

  $('.export_filter').click(function(){
    var staff_name = $('#staff_name').val();
    var tasktypefilter = $('#tasktypefilter').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
     $.ajax({
           url: '<?php echo base_url('admin/leads/filter_export');?>',
           type: 'POST',
           data: {staff_name: staff_name, tasktypefilter: tasktypefilter, start_date: start_date, end_date: end_date},
           error: function() {
              alert('Something is wrong');
           },
          success: function(data) {
            //   var json_pre = '[{"Id":1,"UserName":"Sam Smith"},{"Id":2,"UserName":"Fred Frankly"},{"Id":1,"UserName":"Zachary Zupers"}]';
//               var json_pre = data;
//               console.log(json_pre);
// var json = $.parseJSON(json_pre);



// var csv = JSON2CSV(json);
 //   var json_pre = '[{"Id":1,"UserName":"Sam Smith"},{"Id":2,"UserName":"Fred Frankly"},{"Id":1,"UserName":"Zachary Zupers"}]';
            var json_pre = data;
            //console.log(json_pre);
            
            // var json = $.parseJSON(json_pre);
            
            
            //Remarks  Ad Name
            var json  = JSON.parse(json_pre, function (key, value) {
              if (key == "Remarks") {
                  //var data = value.replace(",", ";");
                  var data = value.replace(/[#_,]/g,'');
                return data;
              }else{
                  return value;
              }
              
              
             
            });
            
            console.log(json);
            
            var csv = JSON2CSV(json);
var downloadLink = document.createElement("a");
var blob = new Blob(["\ufeff", csv]);
var url = URL.createObjectURL(blob);
downloadLink.href = url;
downloadLink.download = "data.csv";

document.body.appendChild(downloadLink);
downloadLink.click();	return false;
               
		
           //  var example = [{"f_name":"Nishit","l_name":"patel","mobile":"999999999","gender":"male"},{"f_name":"Nishitm","l_name":"patelm","mobile":"9999999996","gender":"fmale"},{"f_name":"Nishitn","l_name":"pateln","mobile":"9999999979","gender":"male"}]

     
        
        
        
        
        // document.body.removeChild(downloadLink);
//         var example = data;
//         //  	var x = new CSVExport(example);
// 			return false;
       
                //alert("Record added successfully");  
           }
        });
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
</script>
<script>
function goBack() {
  window.history.back();
}
</script>

</body>
</html>
