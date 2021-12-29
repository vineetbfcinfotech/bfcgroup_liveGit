<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
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
						<?php $converted_by = $this->session->userdata('staff_user_id') ?>
                        <center><h3>FD Tracker</h3></center>
                        <div class="ajax-data">
						
                        <table class="table dt-table scroll-responsive">
                            <thead>
                            <tr>
                                <th><?php echo _l('id'); ?></th>
                                <th class="bold"> Investor name</th>
                                <th class="bold"> Transaction Date</th>
                                <th class="bold">Category</th>
                                <th class="bold">Company</th>
                                <th class="bold">Tenure</th>
                                <th class="bold">Transaction Amount</th>
                                <th class="bold">Date of Maturity</th>
                                <th class="bold">Maturity Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($work_report as $work_rep) { ?>
                                <tr>
								<? //if ($work_rep->converted_by == $converted_by || $converted_by == 1) { ?>
                                    <td><?= @++$i; ?></td>
                                    <td><?= $work_rep->investor_name; ?></td>
                                    
                                     <td><span style="display:none"><?php if($work_rep->real_transaction_date == null){ echo $work_rep->transaction_date; } else { echo $work_rep->real_transaction_date; }  ?> </span><input autofocus="off" <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
                                                    } else {
                                                        echo "disabled";
                                                    } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="transaction_date" name="transaction_date" class="form-control datepicker" value="<?php if($work_rep->real_transaction_date == null){ echo $work_rep->transaction_date; } else { echo $work_rep->real_transaction_date; }  ?>"></td>
                                    
                                    
                                    <td><?= $work_rep->product_name; ?></td>
                                    <td><?= $work_rep->company_name; ?></td>
                                    <td>
                                        <span style="display:none"> <?= $work_rep->tenure; ?> </span>
                                    
                                    <input <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
                                                    } else {
                                                        echo "disabled";
                                                    } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="tenure" name="tenure" class="form-control" value="<?= $work_rep->tenure; ?>">
                                    
                                    
                                    </td>
                                    <td><?= $work_rep->transaction_amount; ?></td>
                                     <td>
                                     <span style="display:none"> <?= $work_rep->date_maturity; ?> </span>
                                     <input <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
                                                    } else {
                                                        echo "disabled";
                                                    } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="date_maturity" name="date_maturity" class="form-control datepicker" value="<?= $work_rep->date_maturity; ?>"></td>
                                    <td>
                                    
                                     <span style="display:none"> <?= $work_rep->maturity_amount; ?> </span>
                                    <input <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
                                                    } else {
                                                        echo "disabled";
                                                    } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="maturity_amount" name="maturity_amount" class="form-control" value="<?= $work_rep->maturity_amount; ?>">
                                        
                                        </td>
                                   
								<?php //} ?>
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
        $("input").change(function(){
                const  checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = $(this).val(); 
                //(checkid);
                
                
                url = "<?= base_url('admin/reports/update_fd_tracker') ?>";
        $.get(url, {
                checkid: checkid,
                value: value,
                member_id:member_id
            } ,
            
           
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
            });
    });  
</script>


<script>
     $(document).on('change', '.custom_lead_filter', function () {
      const filterrm = $('#filterrm').val(),
            transctiondatestart = $('#transctiondatestart').val();
            transctiondateend = $('#transctiondateend').val();
          //  filterstatus = $('#filterstatus').val();
          
          
        url = "<?= base_url('admin/reports/custom_fd_filter') ?>";
        
        $.get(url, {
                filterrm: filterrm,
                transctiondatestart: transctiondatestart,
                transctiondateend:transctiondateend
            },
            function (res) {
                $('.ajax-data').html(res);
            })
    });
	
	<?php if($converted_by != 1){ ?>
	$(document).ready(function(){
		$("#header .hide-menu").click(function(){
			$("body").toggleClass("show-sidebar");
		});
		
	});
	<?php } ?>
</script>

</body>
</html>
