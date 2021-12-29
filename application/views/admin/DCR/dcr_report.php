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
                                            <form method='post' action="<?= base_url() ?>admin/DCR/viewDCRReport">
                                            <select id="staff_name" name="staff_name[]" multiple data-none-selected-text="Select PC" data-live-search="true" class="selectpicker custom_lead_filter">
                                                <?php if (!empty($get_staff)) {
                                                    foreach ($get_staff as $get_comp) { ?>
                                                        <option <?php if (in_array($get_comp->staffid, $staff_name)) {
                                                                echo "selected";
                                                            } ?> value="<?= $get_comp->staffid; ?>"><?= $get_comp->firstname; ?></option>
                                                <?php } } ?>
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

                                    <div class="row">
                                         <div class="col-md-12">
                                         <div class="row">
          <div class="col-md-6 mt-1">
                   <form method='post' action="<?= base_url() ?>admin/DCR/viewDCRReport" >
     <input type='text' name='search_global'  class="form-input" value='<?= $search ?>'>
     <input class="btn btn-primary" type='submit' name='submit' value='Search'>
   </form>
 </div>
        </div>
                                   
                                        </div>
                                    </div>
                                    

                                    
                                </div>
                               

                            </div>
<hr class="hr-panel-heading" >
                                <p><?php echo $links; ?></p>
                                <table id="example33" class="table datatable table-responsive scroll-responsive tablebusie  table-striped table-bordered" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                            <div class="mytable table-responsive">
                                    <thead>
                                        <tr role="row">
                                        <th class="bold" style="backgroud-color:red !important"><b>Sr. No.</b></th>
                    <?php if (is_admin() || is_headtrm() || $staff_id == 34 ||  $staff_id == 28 || $role == 92 || $role ==78 ) { ?>
                    <th class="bold"><b>Working Person</b></th>
                    <?php }  ?>
                    <th class="bold"><b>Working Duration</b></th>
                    <th class="bold"><b>Task Description</b></th>
                    <th class="bold"><b>Remarks/Details</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($project_data as $row) {
                                            $i++;
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
                                                <?php $name = $this->db->select('firstname')->from('tblstaff')->where('staffid', $row->staff_id)->order_by('staffid', 'ASC')->get()->row()->firstname;?> 
                                                <?php if (is_admin() || is_headtrm() || $staff_id == 34 ||  $staff_id == 28 || $role == 92 || $role ==78 ) { ?>
                                                    <td><?php echo $name;?></td>
                                                <?php }  ?>
                                                <td><?php echo $row->work_duration; ?></td>
                                                <td><?php echo $row->description; ?></td>
                                                <td><?php echo $row->remark; ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                            <p><?php echo $pagination_number; ?></p>
                            <p><?php echo $links; ?></p>

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