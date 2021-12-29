<?php init_head(); ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

<!-- Custom Filter -->

<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4); ?>">

    <div class="content">

        <div class="d-flex">

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        
                           <a href="https://bfcgroup.in/admin/" class="btn btn-primary">back</a>
                    </div>
                    <div class="col-md-6">
                        <form method='post' action="<?= base_url() ?>admin/MisReport/mis_for_isbn">

                            <div class="dropdown bootstrap-select show-tick">

                                <input type="text" id="start_date" autocomplete="false" value="<?= $start_date; ?>" name="start_date" placeholder="From Date" class="form-control datepicker custom_lead_filter" />

                            </div>

                            <div class="dropdown bootstrap-select show-tick">

                                <input type="text" id="end_date" autocomplete="false" value="<?= $end_date; ?>" name="end_date" placeholder="To Date" class="form-control datepicker custom_lead_filter" />

                            </div>
                            <input class="btn btn-primary" type='submit' name='submit_cat' value='Filter'>
                        </form>
                    </div>

                    <div class="col-md-3">
                        <form method='post' action="<?= base_url() ?>admin/MisReport/mis_for_isbn">
                            <input type='text' name='search_global' value='<?= $search ?>'>
                            <input class="btn btn-primary" type='submit' name='submit' value='Search'>
                        </form>
                    </div>
                    <div class="col-md-2">
                        <form method='post' action="<?= base_url() ?>admin/MisReport/clear_filter">
                            <input class="btn btn-primary " type='submit' name='submit' value='Clear Filter'>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="panel_s">
                        <div class="tableData">
                            <div class="mytable">
                                <table id="leadData1" class="  dataTable no-footer display table table-responsive table-striped table-bordered" cellspacing="0" width="100%" height="100%" role="grid" aria-describedby="example33_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="bold"><b>Sr. No.</b></th>
                                            <th class="bold"><b>Author's Name</b></th>

                                            <th class="bold"><b>Book Title </b></th>

                                            <th class="bold"><b>Package</b></th>

                                            <th class="bold"><b>Aquisition Date</b></th>

                                            <th class="bold"><b>Book Language</b></th>
                                            <th class="bold"><b>Manuscript Status</b></th>
                                            <th class="bold"><b>ISBN Status</b></th>
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
                                            <tr >
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $r->lead_author_name; ?></td>
                                                <td><?php echo $r->lead_booktitle; ?></td>
                                                <td><?php echo $r->lead_book_type; ?></td>
                                                <td><?php echo $r->lead_acquired_date; ?></td>
                                                <td><?php echo $r->lead_author_mslanguage; ?></td>
                                                <td><?php echo $lead_raw_ms; ?></td>
                                                <td><?php echo $lead_isbn_ebook; ?></td>
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
</div>
</div>
<script>
    // $('#mis_isbn').dataTable({
    //     searching: false,
    //     paging: false,
    //     info: false
    // });

$(document).ready(function() {
    //alert("test");
   /* $('#emailStatus1').dataTable({
        "searching": true, 
    });*/
});​
</script>

<?php init_tail_new(); ?>

