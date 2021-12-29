<?php init_head(); ?>
<?php init_clockinout(); ?>
<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4);?>">
    <div class="content" style="">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <h3>
                                <!-- <a href="<?//= base_url('admin/leads/business_mobilization'); ?>" class="btn btn-alert"
                                   onclick="">
                                    Back</a> -->
									<a href="javascript:void(0);" class="btn btn-alert"
                                   onclick="goBack()">
                                    Back</a>
                        </div>
                        <select id="filterrm" multiple data-none-selected-text="Filter By RM"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php if (!empty($rmconverted)) {
                                foreach ($rmconverted as $rmconverteds) { ?>
                                
                                    <option <?php echo set_value('filterrm', $rmconverteds->staffid); ?> value="<?= $rmconverteds->staffid; ?>"><?= $rmconverteds->firstname; ?></option>
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

                        <select id="filterstatus" multiple data-none-selected-text="Filter By Status"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php if (!empty($bustatus)) {
                                foreach ($bustatus as $bustatuss) { ?>
                                    <option value="<?= $bustatuss->status; ?>"><? if ($bustatuss->status == "New") {
                                                            echo "Unverified";
                                                        }
                                                        else
                                                        {
                                                        echo $bustatuss->status;
                                                        }?></option>
                                <?php }
                            } ?>
                        </select>
                        
                        <select id="filterprotype" multiple data-none-selected-text="Filter By Product Type"
                                data-live-search="true" class="selectpicker custom_lead_filter">

                            <?php if (!empty($bpro_type)) {
                                foreach ($bpro_type as $bpro_type1) { ?>
                                    <option value="<?= $bpro_type1->product_type; ?>"><?= $bpro_type1->prod_name; ?></option>
                                <?php }
                            } ?>
                        </select>
                        
                        <button id="unsetallfilter">Clear All Filters </button>
                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>
                        
                        <div class=" ajax-data">
                            <div class="table-responsive">
                                <?php if (!empty($business)) { ?>
                                    <table id="business_report" class="table dt-table scroll-responsive tablebusie ">
                                        <thead>
                                        <tr>
                                            <th><?php echo _l('id'); ?></th>
                                            <th class="bold">Investor Name</th>
                                            <th class="bold">TRANSACTION DATE</th>
                                            <th class="bold">PRODUCT TYPE</th>
                                            <th class="bold">COMPANY</th>
                                            <th class="bold">FOLIO NUMBER</th>
                                            <th class="bold">TENURE</th>
                                            <th class="bold">SCHEME</th>
                                            <th class="bold">TRANSACTION TYPE</th>
                                            <th class="bold">TRANSACTION AMOUNT</th>
                                            <th class="bold">Credit Rate</th>
                                            <th class="bold">Gross Credit Amount</th>
                                            <th class="bold"> GST Rate</th>
                                            <th class="bold">Post GST Credit</th>
                                            <th class="bold">TDS Rate</th>
                                            <th class="bold">NET CREDIT</th>
                                            <th class="bold">Converted By</th>
                                            <th class="bold">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody class="">
                                        
                                </tbody>
                                
                            </table> 
                            <p><?php echo $links; ?></p>
                                 <?php echo $this->pagination->create_links(); ?>
                                    <?php
                                } else {
                                    echo "No Business Report Found";
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>


<script>
 
    if(sessionStorage.getItem("filterrm") != null)
    {
        var filterrm = sessionStorage.getItem("filterrm");
        $('#filterrm').val(filterrm);
    }
    if(sessionStorage.getItem("transctiondatestart") != null)
    {
        var transctiondatestart = sessionStorage.getItem("transctiondatestart");
        $('#transctiondatestart').val(transctiondatestart);
    }
    if(sessionStorage.getItem("transctiondateend") != null)
    {
        var transctiondateend = sessionStorage.getItem("transctiondateend");
        $('#transctiondateend').val(transctiondateend);
    }
    if(sessionStorage.getItem("filterstatus") != null)
    {
        var filterstatus = sessionStorage.getItem("filterstatus");
        $('#filterstatus').val(filterstatus);
    }
    if(sessionStorage.getItem("filterprotype") != null)
    {
        var filterprotype = sessionStorage.getItem("filterprotype");
        $('#filterprotype').val(filterprotype);
    }
</script>
<script>
    $(document).on('change', '.custom_lead_filter', function () {
        const filterrm = $('#filterrm').val(),
        transctiondatestart = $('#transctiondatestart').val();
        if(transctiondatestart == ""){
            transctiondatestart = output;
        }
        transctiondateend = $('#transctiondateend').val();
        if(transctiondateend == ""){
            transctiondateend = output2;
        }
        filterstatus = $('#filterstatus').val();
        filterprotype = $('#filterprotype').val();
        var curPage = $('#wrapper').attr('data-curpage');
        // if(curPage != ''){
        //     curPage = '\'+curPage;
        // }
        url = "<?= base_url('admin/leads/custom_business_filter') ?>";
        
        
        sessionStorage.setItem("filterrm", filterrm);
        sessionStorage.setItem("transctiondatestart", transctiondatestart);
        sessionStorage.setItem("transctiondateend", transctiondateend);
        sessionStorage.setItem("filterstatus", filterstatus);
        sessionStorage.setItem("filterprotype", filterprotype);
    
        $('.ajax-data').html("<center> <img src='http://www.itgeared.com/demo/images/loading.gif' style='width:100px'></center>");    
        
        $.get(url, {
                filterrm: filterrm,
                transctiondatestart: transctiondatestart,
                transctiondateend: transctiondateend,
                filterstatus: filterstatus,
                filterprotype:filterprotype
            },
            function (res) {
                
                $('.ajax-data').html(res);
                initDataTableInline();
            })
    });
</script>
<script>
/*$(document).ready(function() {
    $('#business_report').DataTable({
        "ajax": {
            url : "<?= base_url('admin/leads/custom_business_filter') ?>",
            type : 'GET'
        },
    });
});*/
var d = new Date();
var month = d.getMonth()-1;
var day = 1;

var year = d.getFullYear()
var output = d.getFullYear() + '-' +
    ((''+month).length<2 ? '0' : '') + month + '-' +
    ((''+day).length<2 ? '0' : '') + day;

var lastday = d.getDate();
var lastmonth = d.getMonth()+1;

var lastdate = lastday;
var output2 = d.getFullYear() + '-' +
    ((''+lastmonth).length<2 ? '0' : '') + lastmonth + '-' +
    ((''+lastdate).length<2 ? '0' : '') +lastdate;

//console.log(output2);
//alert(startDateFilter);
   $(window).load(function(){
        const filterrm = $('#filterrm').val();
        //transctiondatestart = $('#transctiondatestart').val();
		var startDateFilter = document.getElementById("transctiondatestart").value;
		var endDateFilter = document.getElementById("transctiondateend").value;
		//alert(startDateFilter);
		if(startDateFilter == ""){
			transctiondatestart = output;
		}else{
			transctiondatestart = startDateFilter;
		}
        //transctiondateend = $('#transctiondateend').val();
		if(endDateFilter == ""){
			transctiondateend = output2;
		}else{
			transctiondateend = endDateFilter;
		}        
        filterstatus = $('#filterstatus').val();
        filterprotype = $('#filterprotype').val();
        url = "<?= base_url('admin/leads/custom_business_filter') ?>";
        
        
        sessionStorage.setItem("filterrm", filterrm);
        sessionStorage.setItem("transctiondatestart", transctiondatestart);
        sessionStorage.setItem("transctiondateend", transctiondateend);
        sessionStorage.setItem("filterstatus", filterstatus);
        sessionStorage.setItem("filterprotype", filterprotype);
    
         $('.ajax-data').html("<center> <img src='http://www.itgeared.com/demo/images/loading.gif' style='width:100px'></center>");       
        
        $.get(url, {
                filterrm: filterrm,
                transctiondatestart: transctiondatestart,
                transctiondateend: transctiondateend,
                filterstatus: filterstatus,
                filterprotype:filterprotype
            },
            function (res) {
                
                $('.ajax-data').html(res);
                initDataTableInline();
                //$('#business_report').DataTable();
            })
    
        
        
    });
</script>

<script>
    $("select.business_status_list").change(function () {
        const business_status = $(this).children("option:selected").val();
        business_id = $(this).data('lead_id');
        url = "<?= base_url('admin/leads/bussiness_status_update') ?>";
        $.get(url, {
                business_status: business_status,
                business_id: business_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
    });
</script>
<script>
$(document).ready(function(){
  $("#unsetallfilter").click(function(){
  sessionStorage.removeItem('filterrm');
  sessionStorage.removeItem('transctiondatestart');
  sessionStorage.removeItem('transctiondateend');
  sessionStorage.removeItem('filterstatus');
  sessionStorage.removeItem('filterprotype');
  
      window.location.reload();

  });
});

function goBack() {
  window.history.back();
}
    
</script>
</body>
</html>
