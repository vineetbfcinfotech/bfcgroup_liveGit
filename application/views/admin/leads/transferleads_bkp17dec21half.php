<?php init_head(); ?>





<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

<!-- Custom Filter -->

<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4); ?>">

    <div class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">
                        

                        <div class="tableData">
                            <div class="row">
                                <div class="col-md-1">
                                    <a href="JavaScript:Void(0);" onclick="goBack()" class="btn btn-primary " onclick="">Back</a>
                                </div>
                                <div class="col-md-1">
                                <form method='post' action="<?= base_url() ?>admin/leads_order/expclear_filter">
                                        <input class="btn btn-primary" type='submit' name='submit' value='Clear Filter'>
                                    </form>
                                    </div>
                                <div class="col-md-10">

                                </div>
                            </div>
                            <hr class="hr-panel-heading" >
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                        <?php $staff_id = $_SESSION['staff_user_id'];
                                                $role = get_imp_role();
                                        if ((is_admin()) || $staff_id == 28 || $staff_id == 34 || $role == 92 || $role ==78 || ($staff_id==74) ) { ?>
                                            <form method='post' action="<?= base_url() ?>admin/TransferLeadsController/reffer_lead_check">
                                            <select id="staff_name" name="staff_name[]" multiple data-none-selected-text="Existing PC" data-live-search="true" class="selectpicker custom_lead_filter">
                                                <?php if (!empty($get_staff)) {
                                                    foreach ($get_staff as $get_comp) { ?>
                                                        <option <?php if (in_array($get_comp->staffid, $staff_name)) {
                                                                echo "selected";
                                                            } ?> value="<?= $get_comp->staffid; ?>"><?= $get_comp->firstname; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <select  style="width: 5.5em" multiple data-none-selected-text="Category" name="category_type[]" id="tasktypefilter" data-live-search="true" class="selectpicker custom_lead_filter" >
                                            <?php if ( !empty($get_task_type) ) {
                                                foreach ($get_task_type as $get_comp) {  ?>
                                                    <option <?php if (in_array($get_comp->id, $search_cat)) { echo "selected";
                                                    } ?> value="<?= $get_comp->id; ?>"><?= $get_comp->name; ?></option>
            
                                                <?php } } ?>
                                            </select>
                                            <select name="sel_ms" id="sel_ms" data-live-search="true" name="selsct_ms" class="selectpicker custom_lead_filter" >
                                                <?php echo $select_ms;?>
                                                <option value="">MenuScript</option>
                                                <option value="in_process" <?php if($select_ms=='in_process'){ echo 'selected';}else{} ?>>In Progress</option>
                                                <option value="completed" <?php if($select_ms=='completed'){ echo 'selected';}else{} ?>>Completed</option>
                                            </select>
                                            <div class="dropdown bootstrap-select show-tick">
                                                <input type="text" id="start_date" autocomplete="false" <?php if ($start_date == 'no_date') { ?>value="" <? } else { ?>value="<?= $start_date; ?>" <? } ?> name="start_date" placeholder="From Date" class="form-control datepicker custom_lead_filter" />
                                            </div>
                                            <div class="dropdown bootstrap-select show-tick">
                                                <input type="text" id="end_date" autocomplete="false" <?php if ($end_date == 'no_date') { ?>value="" <? } else { ?>value="<?= $end_date; ?>" <? } ?> name="end_date" placeholder="To Date" class="form-control datepicker custom_lead_filter" />
                                            </div>
                                            <input class="btn btn-primary" type='submit' name='submit_cat' value='Search'>
                                        </form>
                                        <hr class="hr-panel-heading" >
                                        <?php  } else { } ?>
                                        </div>
                                    </div>

                                  
                                    

                                    
                                </div>
                               

                            </div>

                                
                                
                                <div class="row" >
                                     <div class="col-md-9" >
                                        <form id="updatepc" action="<?php echo base_url('admin/TransferLeadsController/updatepc') ?>" method="POST">
                            
                                      <label class="select_row"><input type="checkbox" class="all_select" style="width: 22px; height: 22px; z-index: -1;"></label><span style="position: relative;top: -4px; margin-right: 20px; font-size: 20px;"> Select All</span>
                                      <input type="text" name="From_Srno" id="from_srno" value="" placeholder="From Sr. no" min="1" max="50">
                                      <input type="text" name="To_Srno" id="to_srno" value="" placeholder="To Sr. no" min="1" max="50">
                                            <select id="changepc" name="staff_name"  data-live-search="true" class="selectpicker custom_lead_filter">
                                                <option value="">New PC</option>
                                <?php if (!empty($get_staff)) {
                                                    foreach ($get_staff as $get_comp) { ?>
                                                        <option value="<?= $get_comp->staffid; ?>"><?= $get_comp->firstname; ?></option>

                                                <?php } } ?>
                                </select>
                                <input type="submit" id="subpcbtn" form="updatepc" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-info" name="assign" value="Assign"   disabled="true">  
                                    </div>
                                    
                                    <!-- <div class="col-md-3" >
                                       <form method='post' action="<?= base_url() ?>admin/TransferLeadsController/reffer_lead_check" >
     <input type='text' name='search_global'  class="form-input" value='<?= $search ?>'>
     <input class="btn btn-primary" type='submit' name='submit' value='Search'>
   </form>
                                    </div>   -->
                                   

                                                                     
                                </div>
                                <hr class="hr-panel-heading" >

                                 <div class="row" >
                                    <div class="col-md-6" >
                                       <?php echo $links; ?><br/><?php echo $pagination_number; ?> 
                                    </div>
                                    <div class="col-md-3" >
                                       
                                    </div> 
                                    <div class="col-md-3" >
                                        
                                    </div>
                                </div>    

                                
 
                                <table id="example33" class="table datatable table-responsive scroll-responsive tablebusie  table-striped table-bordered" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                            <div class="mytable table-responsive">
                                    <thead>
                                        <tr role="row">
                                            
                                        <th class="bold" style="backgroud-color:red !important"><b>Sr. No.</b></th>
                                        <th class="bold" style="backgroud-color:red !important"><b>DB Id</b></th>
                                        <th class="bold" style="backgroud-color:red !important"><b>Assign.</b></th>
                    <?php if (is_admin() || is_headtrm() || $staff_id == 34 ||  $staff_id == 28 || $role == 92 || $role ==78 ) { ?>
                    <th class="bold"><b>PC Name</b></th>
                    <?php }  ?>
                    <th class="bold"><b>Author Name</b></th>
                    <th class="bold"><b>Phone Number</b></th>
                    <th class="bold"><b>Email ID</b></th>
                    <th class="bold"><b>Category</b></th>
                    <th class="bold"><b>Manuscript Status</b></th>
                    <th class="bold"><b>Next calling date</b></th>
                    <th class="bold"><b>Created Date</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;

                                        foreach ($project_data as $row) {
                                            
                                            $i++;
                                            $cat ="";
                                            if($row->lead_category_id == 5) { $cat ="A";}
                                            else if($row->lead_category_id == 16) {$cat ="B";}
                                            else if($row->lead_category_id == 38) {$cat ="B+";}
                                            else if($row->lead_category_id == 30) {$cat ="C";}
                                            else if($row->lead_category_id == 32) {$cat ="NP";}
                                            else if($row->lead_category_id == 39) {$cat ="Acquired";}
                                            else if($row->lead_category_id == 40) {$cat ="UnAttended";}
                                            else if($row->lead_category_id == 41) {$cat ="Scrap";}
                                            else{}
                                        ?>
                                            <tr>
                                                 
                                                <td id="sr_no<?php echo $i; ?>">
                                                    <?php echo $i; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row->id; ?>
                                                </td>
                                                <td>
                                                <input type="checkbox" class="selected_row"  name="tranfer_lead_id[]" value="<?php echo $row->id; ?>" id="chkbox_<?php echo $i; ?>">
                                                </td>
                                                <?php $name = $this->db->select('firstname')->from('tblstaff')->where('staffid', $row->assigned)->order_by('staffid', 'ASC')->get()->row()->firstname;?> 
                                                <?php if (is_admin() || is_headtrm() || $staff_id == 34 ||  $staff_id == 28 || $role == 92 || $role ==78 ) { ?>
                                                    <td><?php echo $name;?></td>
                                                <?php }  ?>
                                                <td><?php echo $row->lead_author_name; ?></td>
                                                <td><?php echo $row->phonenumber; ?></td>
                                                <td><?php echo $row->email; ?></td>
                                                <td><?= $cat; ?></td>
                                                <td><?php echo $row->lead_author_msstatus; ?></td>
                                                <td><?php echo $row->ImEx_NextcallingDate; ?></td>
                                                <td><?php echo $row->lead_created_date; ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </form>
                            </div>
                            <p><?php echo $links; ?></p>
 <p><?php echo $pagination_number; ?></p>
                        </div>

                    </div>

                </div>

            </div>

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


<?php init_tail(); ?>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

<script type="text/javascript">
    $(document).on('click', '.full_description', function() {
        var author_id = $(this).attr("data-id");
        var author_m_id = $(this).attr("data-m_id");
        $.ajax({

            type: "POST",

            url: "<?php echo admin_url('Leads/get_acquisition_d'); ?>",

            data: {
                'author_id': author_id,
                'author_m_id': author_m_id
            },
            dataType: "html",
            success: function(data) {
                $(".modal-data").html(data);
                $("#author_data").modal('show');
            },
            error: function() {
                alert("Error posting feed.");
            }

        });
    });
    $('.select_row').click(function() {
        if($('.all_select').is(":checked")) {
            $("#from_srno").val(1);
            $("#to_srno").val(50);
            $('.selected_row').prop('checked', true);
        }else{
            $('.selected_row').prop('checked', false);
            $("#from_srno").val('');
            $("#to_srno").val('');
        }
    });
$(document).ready(function() {
    $("select#changepc").change(function(){
        if($(this).val() != ""){
            if($('input.selected_row').is(':checked')){
                $('#subpcbtn').prop("disabled", false);
            }else{ }
        }else{
            $('#subpcbtn').prop("disabled", true);
        }
    });
});
$(document).ready(function() {
    $("input.selected_row").click(function(){
        if($('input.selected_row').is(':checked')){
            if($('select#changepc').val() != ""){
                $('#subpcbtn').prop("disabled", false);
            }else{ }
        }else{
            $('#subpcbtn').prop("disabled", true);
        }
    });
    $("input.all_select").click(function(){
    if($('input.all_select').is(':checked')){
            if($('select#changepc').val() != ""){
                $('#subpcbtn').prop("disabled", false);
            }else{ }
        }else{
            $('#subpcbtn').prop("disabled", true);
        }
    });
});


$(function(){
  $("#to_srno").on('keyup', function (e) {
    $(this).val($(this).val().replace(/[^0-9]/g, ''));
    if($(this).val()>50){
        $(this).val('');
    }
  });
  $("#from_srno").on('keyup', function (e) {
    $(this).val($(this).val().replace(/[^0-9]/g, ''));
    if($(this).val()>=50){
        $(this).val('');
    }
  });
});
$(function(){
    $('#to_srno').keyup(function(){
    $('.selected_row').prop('checked', false);
    from = $("#from_srno").val();
    to = $("#to_srno").val();
    for (let i = from; i <= to; i++){
        $('#chkbox_'+i+'.selected_row').prop('checked', true);
        // console.log(i);
    }
    if($('input.selected_row').is(':checked')){
            if($('select#changepc').val() != ""){
                $('#subpcbtn').prop("disabled", false);
            }else{ }
    }else{
        $('#subpcbtn').prop("disabled", true);
    }
    });
    $('#from_srno').keyup(function(){
    $('.selected_row').prop('checked', false);
    from = $("#from_srno").val();
    to = $("#to_srno").val();
    for (let i = from; i <= to; i++){
        $('#chkbox_'+i+'.selected_row').prop('checked', true);
        // console.log(i);
    }
    if($('input.selected_row').is(':checked')){
            if($('select#changepc').val() != ""){
                $('#subpcbtn').prop("disabled", false);
            }else{ }
    }else{
        $('#subpcbtn').prop("disabled", true);
    }
    });
});

</script>

<script>
    $(document).ready(function() {
        $('#example33').DataTable({

            "bInfo": false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
            "paging": false, //Dont want paging                
            "bPaginate": false, //Dont want paging
            "bFilter": false, //hide Search bar      
            "iDisplayStart ": false,
            "iDisplayLength": false,
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