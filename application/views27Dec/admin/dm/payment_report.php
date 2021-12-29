<?php init_head(); ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

<!-- Custom Filter -->

<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4); ?>">

    <div class="content">

        <div class="row">

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-8">
                        <form method='post' action="<?= base_url() ?>admin/digital_marketing/website_payment_report/">

                            <div class="dropdown bootstrap-select show-tick">

                                <input type="text" id="start_date" autocomplete="false" value="<?= $start_date; ?>" name="start_date" placeholder="From Date" class="form-control datepicker custom_lead_filter" />

                            </div>

                            <div class="dropdown bootstrap-select show-tick">

                                <input type="text" id="end_date" autocomplete="false" value="<?= $end_date; ?>" name="end_date" placeholder="To Date" class="form-control datepicker custom_lead_filter" />

                            </div>
                            <input class="btn btn-primary" type='submit' name='submit_cat' value='Filter'>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method='post' action="<?= base_url() ?>admin/digital_marketing/website_payment_report/">
                            <input type='text' name='search_global' value='<?= $search ?>'>
                            <input class="btn btn-primary" type='submit' name='submit' value='Search'>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <form method='post' action="<?= base_url() ?>admin/digital_marketing/clear_filter">
                            <input class="btn btn-primary " type='submit' name='submit' value='Clear Filter'>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="panel_s">

                        <div class="mytable">
                            <table id="emailStatus1" class="  dataTable no-footer display table table-responsive table-striped table-bordered" cellspacing="0" width="100%" height="100%" role="grid" aria-describedby="example33_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="bold"><b>Sr. No.</b></th>
                                        <th class="bold"><b>Buyer Name</b></th>

                                        <th class="bold"><b>Buyer Phone Number</b></th>

                                        <th class="bold"><b>Buyer Email</b></th>

                                        <th class="bold"><b>Order Id</b></th>

                                        <th class="bold"><b>Order Amount</b></th>

                                        <th class="bold"><b>Transaction Status</b></th>

                                        <th class="bold"><b>Mode of Transaction</b></th>

                                        <th class="bold"><b>Transaction Time</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($payment_data as $r) :
                                    ?>
                                        <tr id="rowData<?php echo $r->id; ?>">
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $r->userName; ?></td>
                                            <td><?php echo $r->userPhone; ?></td>
                                            <td><?php echo $r->userEmail; ?></td>
                                            <td><?php echo $r->order_id; ?></td>
                                            <td><?php echo $r->OrderAmount; ?></td>
                                            <td><?php echo $r->Message; ?></td>
                                            <td><?php echo $r->PaymentMode; ?></td>
                                            <td><?php echo $r->TransactionTime; ?></td>
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


    <?php init_tail(); ?>

    <script>

    </script>