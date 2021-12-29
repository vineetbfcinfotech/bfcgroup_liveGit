<?php init_head(); ?>
<?php init_clockinout(); ?>
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
                                <a href="JavaScript:Void(0);" onclick="goBack()" class="btn btn-alert"
                                   onclick="">
                                    Back</a>
                        </div>
                        <select id="staff_name" multiple data-none-selected-text="Filter By Staff"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php if ( !empty($get_staff) ) {
                                     foreach ($get_staff as $get_comp) { ?>
                                         <option value="<?= $get_comp->staff_id; ?>"><?= $get_comp->staffname; ?></option>
                                     <?php }
                                  } ?>
                        </select>
                        <select id="tasktypefilter"  multiple data-none-selected-text="Filter By Task Type" name="task_type" data-live-search="true" class="selectpicker custom_lead_filter">
                                   
                                  <?php if ( !empty($get_task_type) ) {
                                     foreach ($get_task_type as $get_comp) { ?>
                                         <option value="<?= $get_comp->task_type; ?>"><?= $get_comp->task_type; ?></option>
                                     <?php }
                                  } ?>
                               </select>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="start_date" autocomplete="false" name="start_date"
                                          placeholder="Select Start Date" class="form-control datepicker custom_lead_filter"/>

                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="end_date" autocomplete="false" name="end_date"
                                          placeholder="Select End Date" class="form-control datepicker custom_lead_filter"/>

                        </div>

                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>
                        <div class="tableData">
                            <table id="table_id" class="display">
    <thead>
        <tr>
            <th class="bold"> Submitted By</th>
                                   <th class="bold"> Task Type</th>
                                   <th class="bold"> Category</th>
                                   <th class="bold">Company Name</th>
                                   <th class="bold">NAME OF THE PERSON</th>
                                   <th class="bold">Designation</th>
                                   <th class="bold">Mobile No.</th>
                                   <th class="bold">Address</th>
                                   <th class="bold">Duration</th>
                                   <th class="bold">Remarks</th>
                                   <th class="bold">Description</th>
                                   <th class="bold">Working date</th>
                                   <th class="bold">Next FU date</th>
                                   <th class="bold">Reference taken</th>
                                   <th class="bold">Categorisation</th>
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
         "aoColumns": [
						{ mData: 'staffname' } ,
						{ mData: 'task_type' } ,
                        { mData: 'task_category' },
                        { mData: 'company_name' },
                        { mData: 'person_name' },
                        { mData: 'designation' },
                        { mData: 'mobile_number' },
						{ mData: 'address' } ,
						{ mData: 'duration' } ,
						{ mData: 'remark' } ,
						{ mData: 'ass_desc' } ,
						{ mData: 'date' } ,
						{ mData: 'next_fu_date' } ,
						{ mData: 'refernece_taken' } ,
						{ mData: 'categorisation' } ,
                ],
         "ajax":{
            url :"<?php echo base_url('admin/reports/filter_by_task_type1');?>", // json datasource
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
          }
        }); 
        
        $(document).on('change','#staff_name,#tasktypefilter,#start_date,#end_date',function(){
            dataTable.draw();
          });
          
  

});

</script>
<script>
function goBack() {
  window.history.back();
}
</script>

</body>
</html>
