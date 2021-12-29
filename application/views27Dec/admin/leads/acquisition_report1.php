<?php init_head(); ?>





<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

<!-- Custom Filter -->

<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4); ?>">

    <div class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">
                        <hr class="hr-panel-heading" />

                        <div class="tableData">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="JavaScript:Void(0);" onclick="goBack()" class="btn btn-primary " onclick="">Back</a>
                                </div>
                                <div class="col-md-3">   
                                    <?php
                                    if ($staff_name_exp == '') {
                                        // echo 'test'.$search_category.'hello';
                                        $staff_name_exp = 'no_cat';
                                    }
                                    if ($start_date == '') {
                                        $start_date = 'no_date';
                                    }
                                    if ($end_date == '') {
                                        $end_date = 'no_date';
                                    } ?>
                                    
                                     <a href="<?= admin_url(); ?>leads_order/exportAcquisitionreport/<?= $staff_name_exp ?>/<?= $start_date ?>/<?= $end_date ?>" class="btn btn-primary float-right">Export</a>
                                </div>
                                <div class="col-md-3">
                                <form method='post' action="<?= base_url() ?>admin/leads_order/expclear_filter">
                                        <input class="btn btn-primary" type='submit' name='submit' value='Clear Filter'>
                                    </form>
                                    </div>
                                <div class="col-md-3">
                                 
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form method='post' action="<?= base_url() ?>admin/leads_order/acquisition_report_list">
                                        <?php $staff_id = $_SESSION['staff_user_id'];
                                                $role = get_imp_role();
                                        if ((is_admin()) || $staff_id == 28 || $role == 92 ) { ?>
                                            <select id="staff_name" name="staff_name[]" multiple data-none-selected-text="Select PC" data-live-search="true" class="selectpicker custom_lead_filter">
                                                <?php
                                                if (!empty($get_staff)) {

                                                    foreach ($get_staff as $get_comp) { ?>

                                                        <option <?php if (in_array($get_comp->staffid, $staff_name)) {
                                                                echo "selected";
                                                            } ?> value="<?= $get_comp->staffid; ?>"><?= $get_comp->firstname; ?></option>

                                                <?php }
                                                } ?>

                                            </select>
                                        <?php  } else {
                                        } ?>
                                        <div class="dropdown bootstrap-select show-tick">
                                            <input type="text" id="start_date" autocomplete="false" <?php if ($start_date == 'no_date') { ?>value="" <? } else { ?>value="<?= $start_date; ?>" <? } ?> name="start_date" placeholder="From Date" class="form-control datepicker custom_lead_filter" />
                                        </div>
                                        <div class="dropdown bootstrap-select show-tick">
                                            <input type="text" id="end_date" autocomplete="false" <?php if ($end_date == 'no_date') { ?>value="" <? } else { ?>value="<?= $end_date; ?>" <? } ?> name="end_date" placeholder="To Date" class="form-control datepicker custom_lead_filter" />
                                        </div>
                                        <input class="btn btn-primary" type='submit' name='submit_cat' value='Search'>
                                    </form>
                                        </div>
                                    </div>

                                    <div class="row">
                                         <div class="col-md-12">
                                            
                                   
                                        </div>
                                    </div>
                                    

                                    
                                </div>
                                <div class="col-10">
                                    <p><?php echo $links; ?></p>
                                    <p><?php echo $pagination_number; ?></p>
                                </div>

                            </div>

                            <br />
                            <div class="mytable table-responsive">

                                <table id="example33" class="table datatable table-responsive scroll-responsive tablebusie  table-striped table-bordered" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th>Sr. No.</th>
                                            <th>Acquisition date</th>
                                            <th>Author Name</th>
                                            <?php if (is_admin() || is_headtrm() || $staff_id == 34 ||  $staff_id == 28 || $role == 92 ) { ?>
                                                <th>PC Name</th>
                                            <?php } ?>
                                            <th>Package Name</th>
                                            <th>Book Format</th>
                                            <th>Package Value Excluding GST</th>
                                            <th>Cost of Additional Copies(Ex GST)</th>
                                            <th>Total Package Value</th>



                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;

                                        foreach ($project_data as $row) {
                                            
                                            $i++;

                                            $Package_name = '';
                                            if ($row->lead_package_detail == 1) {
                                                $Package_name = 'Standard';
                                            } else if ($row->lead_package_detail == 2) {
                                                $Package_name = 'Customized';
                                            } else if ($row->lead_package_detail == 3) {
                                                $Package_name = 'Standard Customized';
                                            } else {
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?= $row->lead_acquired_date; ?></td>
                                                <td><a style="cursor: pointer;" class="full_description" data-id=<?= $row->id; ?>  data-m_id=<?= $row->leadid; ?>><?= $row->lead_author_name; ?></a></td>

                                                <?php if (is_admin() || is_headtrm() || $staff_id == 34 ||  $staff_id == 28 || $role == 92 ) { ?>
                                                    <td><?= $row->fullname; ?></td>
                                                <?php } ?>
                                                <td><?= $Package_name; ?></td>
                                                <td><?php echo $row->lead_book_type; ?></td>

                                                <?php if(is_null($row->lead_packge_discount)){?>
 <td><?php echo  $row->lead_packge_value; ?></td>
<?php }else{ ?>
<td><?php echo  $row->lead_lesspckg_value; ?></td>
<?php }?>
                                               


                                                <td><?php echo ($row->cost_of_additional_copy) * 0.25; ?></td>
                                                 <?php if(is_null($row->lead_packge_discount)){?>
                                                <td><?php echo  $row->lead_packge_value + ($row->cost_of_additional_copy) * 0.25; ?></td>
                                                <?php }else{ ?>
<td><?php echo  $row->lead_lesspckg_value + ($row->cost_of_additional_copy) * 0.25; ?></td>
<?php }?>
                                            </tr>
                                        <?php } ?>

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

    $(document).on('click', '.full_description', function() {

       
    });






    $('.export_filter').click(function() {

        var staff_name = $('#staff_name').val();

        var tasktypefilter = $('#tasktypefilter').val();

        var start_date = $('#start_date').val();

        var end_date = $('#end_date').val();
        var total_date = start_date + end_date;
        console.log(tasktypefilter.length);

        if ((tasktypefilter.length > 0)) {
            $.ajax({

                url: '<?php echo base_url('admin/leads_order/filter_export'); ?>',

                type: 'POST',

                data: {
                    staff_name: staff_name,
                    tasktypefilter: tasktypefilter,
                    start_date: start_date,
                    end_date: end_date
                },

                error: function() {

                    alert('Something is wrong');

                },

                success: function(data) {

                    var json_pre = data;

                    var json = JSON.parse(json_pre, function(key, value) {

                        if (key == "Remarks") {

                            return value;

                        } else {

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

                    downloadLink.click();
                    return false;
                }
            });
        } else if (total_date != '') {
            $.ajax({

                url: '<?php echo base_url('admin/leads_order/filter_export'); ?>',

                type: 'POST',

                data: {
                    staff_name: staff_name,
                    tasktypefilter: tasktypefilter,
                    start_date: start_date,
                    end_date: end_date
                },

                error: function() {

                    alert('Something is wrong');

                },

                success: function(data) {

                    var json_pre = data;

                    var json = JSON.parse(json_pre, function(key, value) {

                        if (key == "Remarks") {

                            return value;

                        } else {

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

                    downloadLink.click();
                    return false;

                }

            });
        } else {
            alert('Please Select any one filter');
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
</script>

<script>
    function goBack() {

        window.history.back();

    }
</script>



</body>

</html>