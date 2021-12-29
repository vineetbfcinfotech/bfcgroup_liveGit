<?php init_head(); ?>

<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                       <?php $admin = $this->session->userdata('admin_role'); $converted_by = $this->session->userdata('staff_user_id'); ?>
					   <?php //echo "<pre>"; print_r($rmconverted);exit; ?>
                        <select id="filterrm" multiple data-none-selected-text="Filter By WP"
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
                        <center><h3>Member Tracker</h3></center>
                        <div class="ajax-data">
						
						
						
						
                        <table class="table dt-table scroll-responsive">
                            <thead>
                            <tr>
                                <th><?php echo _l('id'); ?></th>
                                <th class="bold"> Members's Name</th>
                                <th class="bold"> Membership Acquisition Date</th>
                                <th class="bold">Category</th>
                                <th class="bold">Working Papers</th>
                                <th class="bold">Goal Sheet</th>
                                <th class="bold">Existing Portfolio Review</th>
                                <th class="bold">Investment</th>
                                <th class="bold">Remarks</th>
                                <th class="bold">Expected Date of investments</th>
                                <th class="bold">Converted By</th>
                            </tr>
                            </thead>
							
                            <tbody >
                            <?php foreach ($work_report as $work_rep) {  ?>
                                <tr>
								
								<? //if ($work_rep->converted_by == $converted_by || $converted_by == 1 || $admin == 1) { ?>
									
                                    <td><?= @++$i; ?></td>
                                    <td><?= $work_rep->investor_name; ?></td>
                                    <td><?= $work_rep->transaction_date; ?></td>
                                    <td><?= $work_rep->product_name; ?></td>
                                    <td><p style="display:none"><? if ($work_rep->working_paper == 1) {
                                            echo "Yes";
                                        } else { echo "No"; } ?> </p> <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="checkbox"  data-member_id="<?= $work_rep->id; ?>" class="form-control" name="working_paper"
                                               id="working_paper" value="" <? if ($work_rep->working_paper == 1) {
                                            echo "checked";
                                        } ?> /></td>
                                    <td>
                                    <p style="display:none"><? if ($work_rep->goal_sheet == 1) {
                                            echo "Yes";
                                        } else { echo "No"; } ?> </p>
                                    <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="checkbox" data-member_id="<?= $work_rep->id; ?>" class="form-control" name="goal_sheet" id="goal_sheet"
                                               value="" <? if ($work_rep->goal_sheet == 1) {
                                            echo "checked";
                                        } ?>/></td>
                                    <td>
                                    <p style="display:none"><? if ($work_rep->existing_port == 1) {
                                            echo "Yes";
                                        } else { echo "No"; } ?> </p>
                                    <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="checkbox" data-member_id="<?= $work_rep->id; ?>" class="form-control" name="existing_port"
                                               id="existing_port" value="" <? if ($work_rep->existing_port == 1) {
                                            echo "checked";
                                        } ?>/></td>
                                    <td>
                                    <p style="display:none"><? if ($work_rep->investment == 1) {
                                            echo "Yes";
                                        } else { echo "No"; } ?> </p>
                                        <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="checkbox" data-member_id="<?= $work_rep->id; ?>" class="form-control" value=""  name="investment" id="investment"
                                               <? if ($work_rep->investment == 1) {
                                            echo "checked";
                                        } ?>/></td>
                                    <td>
                                        <? if ($work_rep->converted_by != $converted_by) {
                                             $condition = "disabled";
                                        }else{ $condition = ""; } ?>
                                        <? $general4 = $work_rep->remark; ?>
                                        <?= form_textarea('remark', $general4, "class = 'form_control'  id='remark' data-member_id='$work_rep->id' $condition") ?>
                                    </td>
                                    <td>
                                         <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="exp_investment" name="exp_investment" class="form-control datepicker" value="<?= $work_rep->exp_investment; ?>"></td>

                                    <td><?= $work_rep->staff_res; ?></td>
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
<script type="text/javascript">
    $(document).ready(function(){
        $("input[type='checkbox']").click(function(){
            if($(this).prop("checked") == true){
                const checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = "1";
                //alert("Checkbox is checked.");
                url = "<?= base_url('admin/reports/update_membership_papers') ?>";
        $.get(url, {
                checkid: checkid,
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            }
            else if($(this).prop("checked") == false){
                const checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = "0";
               // alert("Checkbox is unchecked.");
                url = "<?= base_url('admin/reports/update_membership_papers') ?>";
        $.get(url, {
                checkid: checkid,
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
            }
        });
    });
</script>


<script>
  $(document).ready(function(){
        $("textarea").blur(function(){
                const  checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = $(this).val(); 
                //alert(value);
                
                url = "<?= base_url('admin/reports/update_membership_remark') ?>";
        $.get(url, {
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
            });
    });  
</script>
<script>
  $(document).ready(function(){
        $("input[type='text']").blur(function(){
                const checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = $(this).val(); 
                
                url = "<?= base_url('admin/reports/update_membership_exp_investment') ?>";
        $.get(url, {
            
                value: value,
                member_id:member_id
            },
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
          
          
        url = "<?= base_url('admin/reports/custom_member_filter') ?>";
        
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
<style>
.show-menu {
    padding: 18px 14px 18px 14px;
    font-size: 14px;
    float: left;
    color: #D0D0D0;
    /* border-right: 1px solid #54606f; */
    cursor: pointer;
    line-height: 27px;
}
</style>

</body>
</html>
