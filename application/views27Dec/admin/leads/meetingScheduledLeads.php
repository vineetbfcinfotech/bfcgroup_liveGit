<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
<style>
    .unselectable{
  background-color: #ddd;
  cursor: not-allowed;
}
</style>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body"><!--<div class="_buttons pull-right">
                            <a href="http://localhost:8800/bfclead/admin/leads/newmeeting" class="btn mright5 btn-info pull-left display-block">
                                Add New Meeting                     </a>
                        </div>--> <h4><?= @$title; ?></h4>

                        <hr class="hr-panel-heading"/>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table dt-table scroll-responsive">
                                    <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Assigned To</th>
                                        <th>Meeting Category</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Meeting DateTime</th>
                                        <th>Lead Remark</th>
                                        <th>Assigned By</th>

                                    </tr>
                                    </thead>
                                   <?php if ( !empty($leads) )
                                   { $n="1";
                                       foreach ($leads as $lead)
                                        { ?>

                                                    <tr>
                                                    <td><?= $n; ?> <i class="fa fa-eyes"></i> </td>
                                                    <td><?= $lead->assigned_to; ?></td>
                                                    <td><a href="#"
                                                           onclick="edit_product_catagory(this,<?= $lead->id; ?>);return false;"
                                                           data-id="<?= $lead->id; ?>" data-name="<?= $lead->name; ?>"
                                                           data-phonenumber="<?= $lead->phonenumber; ?>"
                                                           data-email="<?= $lead->email; ?>"
                                                           data-designation="<?= $lead->designation; ?>"
                                                           data-company="<?= $lead->company; ?>"
                                                           data-address="<?= $lead->address; ?>"
                                                           data-status="<?= $lead->status; ?>"
                                                           data-lead_id="<?= $lead->lead_id; ?>"
                                                           data-description="<?= $lead->meeting_remark; ?>"> <?= $lead->meeting_cat; ?>
                                                        </a></td>
                                                    <td><?= $lead->name; ?></td>
                                                    <td><?= $lead->address; ?></td>
                                                    <td><?= $lead->phonenumber; ?></td>
                                                    <td><?= $lead->meetingtimefrom; ?> -  <?= date("H:i:s",strtotime($lead->meetingtimeto));  ?></td>
                                                    <td><?= $lead->description; ?></td>
                                                    <td><?= $lead->firstname; ?></td>
                                                    
                                                    </tr>

                                    <?php $n++; }
                                   }
                                   ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<div class="modal fade product-catagory-modal" id="product_catagory" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel"></div>
<script>
    function edit_product_catagory(invoker, id, name, phonenumber) {

        var name = $(invoker).data('name');
        var status = $(invoker).data('status');
        var description = $(invoker).data('description');
        var phonenumber = $(invoker).data('phonenumber');
        var email = $(invoker).data('email');
        var designation = $(invoker).data('designation');
        var company = $(invoker).data('company');
        var address = $(invoker).data('address');
        var data_source = $(invoker).data('data_source');
        var calling_objective = $(invoker).data('calling_objective');
        var lead_id = $(invoker).data('lead_id');
        $('#additional').append(hidden_input('id', id));
        $('#product_catagory input[name="id"]').val(id);
        $('#product_catagory input[name="name"]').val(name);
        $('#product_catagory input[name="phonenumber"]').val(phonenumber);
        $('#product_catagory input[name="email"]').val(email);
        $('#product_catagory input[name="description"]').val(description);


        $('#product_catagory input[name="designation"]').val(designation);
        $('#product_catagory input[name="company"]').val(company);
        $('#product_catagory input[name="address"]').val(address);
        $('#product_catagory input[name="data_source"]').val(data_source);
        $('#product_catagory input[name="calling_objective"]').val(calling_objective);
        $('#product_catagory input[name="lead_id"]').val(lead_id);
        $.ajax({
            url: "<?php echo base_url(); ?>admin/leads/meeting_remark",
            method: 'POST',
            data: {
                id: id,
                name: name,
                description: description,
                phonenumber: phonenumber,
                email: email,
                designation: designation,
                company: company,
                address: address,
                data_source: data_source,
                calling_objective: calling_objective,
                lead_id: lead_id,
                status: status
            },
            success: function (data)   // A function to be called if request succeeds
            {
                //  alert(data);
                $("#product_catagory").html(data);
                $('#product_catagory').modal('show');
            }
        });
    }
</script>
<script>
    $(document).on('change', '#lead_status_change', function () {
        const status = $(this).val(), id = $(this).data('lead-id'), lead_id = $(this).data('l_id'),
            url = "<?= base_url('admin/leads/update_cust_meet') ?>";
        $.get(url, {status: status, id: id,lead_id: lead_id}, function (res) {
            location.reload();
        })
    });
    
    $('select').change(function() {
    var selected = $(this).val();

    if (selected == '3') {
        if (!confirm('Are you sure want to convert this meeting it cannot be undo again  ? ')) {
           // $(this).val($.data(this, 'current'));
            return false;
        }     
    }

    $.data(this, 'current', $(this).val());
});
</script>

</body>
</html>
