<?php init_head(); ?>
<style>
/* Ensure that the demo table scrolls */
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    .overlapp{
        z-index: 9999;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.0.0/css/fixedColumns.dataTables.min.css" >
<!-- Custom Filter -->
<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4); ?>">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9">
                        <form method='post' action="<?= base_url() ?>admin/MisReport/index">
                            <?php if ((is_admin())||($_SESSION['staff_user_id'] == 55) ) { ?>
                                <select id="staff_name" name="staff_name[]" multiple data-none-selected-text="Select PM" data-live-search="true" class="selectpicker custom_lead_filter">
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
                            <a class="btn btn-primary" href="https://bfcgroup.in/admin/">Back</a>
                            <div class="dropdown bootstrap-select show-tick">
                                <input type="date" id="start_date" autocomplete="false" value="<?= $start_date; ?>" name="start_date" placeholder="From Date" class="form-control datepicker custom_lead_filter" />
                            </div>

                            <div class="dropdown bootstrap-select show-tick">
                                <input type="date" id="end_date" autocomplete="false" value="<?= $end_date; ?>" name="end_date" placeholder="To Date" class="form-control datepicker custom_lead_filter" />
                            </div>
                            <div class="dropdown bootstrap-select show-tick">
                                <input type='text' name='search_global' placeholder="Search here.." value='<?= $search ?>'>    
                            </div>
                            <input class="btn btn-primary" type='submit' name='submit_cat' value='Search'>
                        </form>
                    </div>
                    <!-- <div class="col-md-3">
                        <form method='post' action="<?= base_url() ?>admin/MisReport/index">
                            <input type='text' name='search_global' value='<?= $search ?>'>
                            <input class="btn btn-primary" type='submit' name='submit' value='Search'>
                        </form>
                    </div> -->
                </div>
                <div class="row my-1">
                    <div class="col-md-3">
                        <form method='post' action="<?= base_url() ?>admin/MisReport/clear_filter">
                            <input class="btn btn-primary" type='submit' name='submit' value='Clear Filter'>
                        </form>
                    </div>
                    <div class="col-md-3">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="panel_s">
                        <br>
                        <p><?php echo $links; ?></p>
                        <p><?php echo $pagination_number; ?></p> 
                          <table id="example_datatable" class="stripe row-border order-column table " style="width:100%">
                                <thead >
                                    <tr>
                                       <!--  <th class="bold"><b>Sr. No.</b></th> -->
                                        <th class="bold overlapp" scope="col"><b>Author's Name</b></th>
                                        <th class="bold" scope="col"><b>Project Name/Book Name</b></th>
                                        <th class="bold" scope="col"><b>Aquisition Date</b></th>
                                        <th class="bold" scope="col"><b>ASF Status</b></th>
                                        <th class="bold" scope="col"><b>Agreement</b></th>
                                        <th class="bold" scope="col"><b>Manuscript Status</b></th>
                                        <th class="bold" scope="col"><b>Project Status</b></th>
                                        <th class="bold" scope="col"><b>Cover</b></th>
                                        <th class="bold" scope="col"><b>ISBN</b></th>
                                        <th class="bold" scope="col"><b>Printing Approval</b></th>
                                        <th class="bold" scope="col"><b>Payment - 1st Installment</b></th>
                                        <th class="bold" scope="col"><b>Payment - 2nd Installment</b></th>
                                        <th class="bold" scope="col"><b>Printing Quotation</b></th>
                                        <th class="bold" scope="col"><b>Printed Book</b></th>
                                        <th class="bold" scope="col"><b>Marketing</b></th>
                                        <th class="bold" scope="col"><b>Current Inventory</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($misdata as $r) :
                                        if ($r->lead_asf_status == 1) {
                                            $asf_status = 'Received';
                                        } else {
                                            $asf_status = 'Not Received';
                                        }
                                        if ($r->pdf_mail_agreement != '') {
                                            $agreement_status = 'Uploaded';
                                        } else {
                                            $agreement_status = 'Not Uploaded';
                                        }
                                        if ($r->lead_raw_ms != '') {
                                            $ms_status = 'Uploaded';
                                        } else {
                                            $ms_status = 'Not Uploaded';
                                        }
                                        if ($r->project_status == 5 || $r->project_status == 6) {
                                            $project_status = 'In Proofreading';
                                        } else if ($r->project_status == 9 || $r->project_status == 10) {
                                            $project_status = 'In Format Editing';
                                        } 
                                        else if ( $r->project_status == 8) {
                                            $project_status = 'PR-Rework';
                                        } 
                                        else if ( $r->project_status == 12) {
                                            $project_status = 'FE-Rework';
                                        } 
                                        else if ($r->project_status == 7 || $r->project_status == 11 ) {
                                            $project_status = 'Approved';
                                        } 
                                        else {
                                            $project_status = 'Not Received.';
                                        }

                                        if ( $r->project_status_gd == 3) {
                                            $project_status_gd = 'Approved';
                                        }
                                        elseif($r->project_status_gd == 4 ){
                                            $project_status_gd = '1st-Changes';
                                        }
                                        elseif($r->project_status_gd == 2 || $r->project_status_gd == 1 || $r->project_status_gd == 5){
                                            $project_status_gd = 'In Process';
                                        }
                                         else {
                                            $project_status_gd = 'ASF not Received';
                                        }

                                        if($r->lead_isbn_status == 1){
                                            $lead_isbn_ebook = 'Applied';
                                            if($r->lead_isbn_ebook == 1 && $r->lead_isbn_paperback == 1){
                                                $lead_isbn_ebook = 'Recieved';
                                            }else{ }
                                        }elseif($r->lead_isbn_status === 0){
                                            $lead_isbn_ebook = 'Rejected';
                                        }
                                        else {
                                            $lead_isbn_ebook = 'Not Applied';
                                        }

                                        
                                        if ($r->lead_raw_ms != '') {
                                            $lead_raw_ms = 'Received';
                                        } else {
                                            $lead_raw_ms = 'Not Received';
                                        }
                                        
                                        if ($r->print_quotation_status != '') {
                                            $print_quotation_status = 'Approved';
                                        } else {
                                            $print_quotation_status = 'Not Approved';
                                        }
                                        if ($r->send_print_quatation != '') {
                                            $send_print_quatation = 'Shared';
                                        } else {
                                            $send_print_quatation = 'Not Shared';
                                        }

                                        if ($r->dm_project_status != '') {
                                            $dm_project_status = 'Completed';
                                        } else {
                                            $dm_project_status = 'Not Completed';
                                        }
                                        if($data_inventry->total_books != ""){
                                            $total_books = $data_inventry->total_books;
                                        }else{
                                            $total_books = 0;
                                        }
                                        $data_inventry =  $this->db->select('total_books')->get_where('tblinventory', array('book_title' => $r->lead_booktitle))->row();
                                    ?>
                                        <tr id="rowData<?php echo $r->id; ?>">
                                            <td><?php echo $r->lead_author_name; ?></td>
                                            <td><?php echo $r->lead_booktitle; ?></td>
                                            <td><?php echo $r->lead_acquired_date; ?></td>
                                            <td><?php echo $asf_status; ?></td>
                                            <td><?php echo $agreement_status;?></td>
                                            <td><?php echo $lead_raw_ms;?></td>
                                            <td><?php echo $project_status;?></td>
                                            <td><?php echo $project_status_gd; ?></td>
                                            <td><?php echo $lead_isbn_ebook; ?></td>
                                            <td><?php echo $print_quotation_status; ?></td>
                                            <td><?php echo $r->lead_first_installment; ?></td>
                                            <td><?php echo $r->final_installment_amount; ?></td>
                                            <td><?php echo $send_print_quatation; ?></td>
                                            <td>
                                                <?php if ($data_inventry) {
                                                    echo 'Received';
                                                } else {
                                                    echo 'Not Received';
                                                } ?>
                                            </td>
                                            <td><?php echo $dm_project_status; ?></td>
                                            <td><?php echo $total_books; ?></td>
                                        </tr>
                                    <?php $i++;
                                    endforeach; ?>
                                </tbody>
                            </table>
                            <p><?php echo $links; ?></p>
                        <p><?php echo $pagination_number; ?></p>
                        </div> 
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php init_tail_new(); ?>
<script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js" ></script>
<script>
$(document).ready(function() {
    var table = $('#example_datatable').DataTable({
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            left: 1
        },
        oLanguage: {
            sEmptyTable: "No Data is available for current Month"
        }
    });
});
</script>