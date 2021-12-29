<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
							<?php if(!empty($this->session->flashdata('success'))) {?>
							   <div class="text-success text-center"><?php echo  $this->session->flashdata('success'); ?></div>
						   <?php }else if(!empty($this->session->flashdata('error'))){?>
							   <div class="text-danger text-center"><?php echo  $this->session->flashdata('error'); ?></div>
						   <?php } ?>
                       <?php $converted_by = $this->session->userdata('staff_user_id'); ?>
                        <select id="filterrm" multiple data-none-selected-text="Filter By RM"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php if (!empty($rmconverted)) {
                                foreach ($rmconverted as $rmconverteds) { ?>
                                    <option value="<?= $rmconverteds->staffid; ?>"><?= $rmconverteds->firstname; ?></option>
                                <?php }
                            } ?>
                        </select>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="transctiondatestart" autocomplete="false" name="transctiondatestart"
                                   placeholder="Period From"
                                   class="form-control datepicker custom_lead_filter"/>

                        </div>
                        <div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="transctiondateend" autocomplete="false" name="transctiondateend"
                                   placeholder="Period To"
                                   class="form-control datepicker custom_lead_filter"/>

                        </div>
                        <center><h3>Insurance Tracker</h3></center>
                        <div class="ajax-data">
                        <table class="table dt-table scroll-responsive" id="insurance_tracker">
                            <thead>
                            <tr>
                                <th><?php echo _l('id'); ?></th>
                                <th class="bold">Investor name</th>
                                <th class="bold">Transaction Date</th>
                                <th class="bold">Product Type</th>
                                <th class="bold">Company</th>
                                <th class="bold">Scheme</th>
                                <th class="bold">Transaction Type</th>
                                <th class="bold">Policy Number</th>
                                <th class="bold">Sum Assured</th>
                                <th class="bold">Transaction Amount</th>
                                <th class="bold">Renewal Date</th>
                            </tr>
                            </thead>
                            <tbody >
                            <?php foreach ($work_report as $work_rep) { ?>
                                <tr>
                                    <td><?= @++$i; ?></td>
                                    <td><?= $work_rep->investor_name; ?></td>
                                    <td>
                                        <p style="display:none"><? if ($work_rep->real_transaction_date != null) {
                                            echo $work_rep->real_transaction_date;
                                        }
                                        else 
                                        {
                                            echo $work_rep->transaction_date;
                                        }
                                        
                                        ?></p>
                                    <input autofocus="off" <? if (!is_sub_admin()) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="real_transaction_date" name="real_transaction_date" class="form-control datepicker" value=" <? if ($work_rep->real_transaction_date != null) {
                                            echo $work_rep->real_transaction_date;
                                        }
                                        else 
                                        {
                                            echo $work_rep->transaction_date;
                                        }
                                        
                                        ?>">
                                    
                                    </td>
                                    <td><?= $work_rep->pro_type; ?></td>
                                    <td><?= $work_rep->company_name; ?></td>
                                    <td><?= $work_rep->product_name; ?></td>
                                    <td><?= $work_rep->transaction_type; ?></td>
                                    <td>
                                     <p style="display:none"><?= $work_rep->policy_number; ?></p>
                                    <input <? if (!is_sub_admin()) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="policy_number" name="policy_number" class="form-control get_data" value="<?= $work_rep->policy_number; ?>"></td>
                                    
                                    <td>
                                         <p style="display:none"><?= $work_rep->sum_assured; ?></p>
                                        <input <? if (!is_sub_admin()) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="sum_assured" name="sum_assured" class="form-control get_data" value="<?= $work_rep->sum_assured; ?>">
                                    </td>
                                    <td>
                                         <?= $work_rep->transaction_amount; ?></td>
                                        
                                        <td>
                                            <p style="display:none"><?= $work_rep->renewal_date; ?></p>
                                         <input <? if (!is_sub_admin()) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="renewal_date" name="renewal_date" class="form-control datepicker get_data" value="<?= $work_rep->renewal_date; ?>"></td>

                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $('#worki8ng_paper:checkbox').change(function () {
 const filterrm = $('#working_paper').val(), 
       member_id = $(this).data('member_id');
        alert('changed');
    });
    
    
</script>



<script>
  $(document).ready(function(){
        /* $("#insurance_tracker input[type='text']").blur(function(){
                const  checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = $(this).val(); 
                //alert(value);
                
                url = "<?= base_url('admin/reports/update_insurance_data') ?>";
        $.get(url, {
                checkid: checkid,
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
            }); */
			
		
    });  
$(document).ready(function(){
  $("#insurance_tracker").on("change", ".get_data", function(){
	   // your code goes here
		checkid = $(this).attr("id");
		member_id = $(this).data('member_id');
		value = $(this).val(); 
		 $.ajax({
            type: "GET",
            url: "<?php echo base_url('admin/reports/update_insurance_data'); ?>",
            data: {checkid: checkid, member_id:member_id, value:value},
            //dataType: "html",
            success: function(res){
                alert_float('success', 'Business Updated Successfully!');
            },
            error: function() { alert("Error posting feed."); }
       });
	});
});

</script>


<script>
     $(document).on('change', '.custom_lead_filter', function () {
      const filterrm = $('#filterrm').val(),
            transctiondatestart = $('#transctiondatestart').val();
            transctiondateend = $('#transctiondateend').val();
          //  filterstatus = $('#filterstatus').val();
          
          
        url = "<?= base_url('admin/reports/custom_insurance_filter') ?>";
        
        $.get(url, {
                filterrm: filterrm,
                transctiondatestart: transctiondatestart,
                transctiondateend:transctiondateend
            },
            function (res) {
                $('.ajax-data').html(res);
            })
    });
</script>

</body>
</html>
