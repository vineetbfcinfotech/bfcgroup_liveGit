<?php init_head(); ?>

<?php init_clockinout(); ?>

<?php echo '<pre>';print_r($business);echo '</pre>';?>



<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

<!-- Custom Filter -->

<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4);?>" style="max-height: 1100px;" >

    <div class="content" style="min-width: 1900px;">

        <div class="row">

            <div class="col-md-12">

                <div class="panel_s">

                    <div class="panel-body">

                        <div class="_buttons">

                            <h3>

                                <a href="<?= base_url('admin/leads/business_mobilization'); ?>" class="btn btn-alert"

                                   onclick="">

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
						
						<div class="dropdown bootstrap-select show-tick">
                            <input type="text" id="inputname" autocomplete="false" name="inputname" placeholder="Search by key" class="form-control custom_lead_filter"/>
                        </div>

                        

                        <button id="unsetallfilter">Clear All Filters</button>

                        <div class="clearfix"></div>



                        <hr class="hr-panel-heading"/>

                        <div class="tableData">
						
						
						
						<table class="table dt-table scroll-responsive" >
                               <thead>
                               <tr>
                                   <th>Investor Type</th>
									<th>TRANSACTION DATE</th>
									<th>Product Type</th>
									<th>Company</th>
									<th>Folio Number</th>
									<th>Tenure</th>
									<th>Scheme</th>
									<th>transaction type</th>
									<th>transaction amount</th>
									<th>credit rate</th>
									<th>gross credit amount</th>
									<th>gst rate</th>
									<th>post gst rate</th>
									<th>TDS rate</th>
									<th>net credit</th>
									<th>Converted By</th>
									<th>Status</th>
                               </tr>
                               </thead>
                               <tbody >
                                   <?php foreach($allBusinessData as $business){ ?>
                                   <tr>
                                       <td><?php echo $business->investor_name; ?></td>
                                       <td><?php echo $business->transaction_date; ?></td>
                                       <td><?php echo $business->pro_type; ?></td>
                                       <td><?php echo $business->company_name; ?></td>
                                       <td><?php echo $business->folio_number; ?></td>
                                       <td><?php echo $business->tenure; ?></td>
                                       <td><?php echo $business->product_name; ?></td>
                                       <td><?php echo $business->transaction_type; ?></td>
                                       <td><?php echo $business->transaction_amount; ?></td>
                                       <td><?php echo $business->credit_rate; ?></td>
                                       <td><?php echo $business->gross_credit_amount; ?></td>
                                       <td><?php echo $business->gst_rate; ?></td>
                                       <td><?php echo $business->post_gst_credit; ?></td>
                                       <td><?php echo $business->tds_rate; ?></td>
                                       <td><?php echo $business->net_credit; ?></td>
                                       <td><?php echo $business->staff_res; ?></td>
                                       <td></td>
                                       
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



<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->





<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script> -->
<!--  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script> -->

<script>



    var dataTable = $('#table_id').DataTable({

		 "bProcessing": true,

         "serverSide": true,
		 
/* 		 "dom": 'Bfrtip', 
        "buttons": [
            $.extend( true, {}, buttonCommon, {
                extend: 'excelHtml5'
            } ),
        ], */

        "bFilter": true,
         'searching': false,
         "lengthMenu": [[10, 25, 50, 100,500], [10, 25, 50, 100,500]],
		 "pageLength": 50,
		 
		 
		 
				
         "aoColumns": [

						{ mData: 'investor_name' } ,

						{ mData: 'transaction_date' } ,

						{ mData: 'pro_type' },

						{ mData: 'company_name' },

						{ mData: 'folio_number' },

						{ mData: 'tenure' },

						{ mData: 'product_name' },

						{ mData: 'transaction_type' } ,

						{ mData: 'transaction_amount' } ,

						{ mData: 'credit_rate' } ,

						{ mData: 'gross_credit_amount' } ,

						{ mData: 'gst_rate' } ,

						{ mData: 'post_gst_credit' } ,

						{ mData: 'tds_rate' } ,

						{ mData: 'net_credit' } ,

						{ mData: 'staff_res' } ,

						{ mData: ''} ,

                ],
				

         "columnDefs": [ {

            "targets": -1,

            "data": '',

            'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-lead-id', rowData.pro_id); 
                   $(td).html('<select class="form-control  business_status_list" name="business_status" data-lead-id="'+rowData.pro_id+'"><option value="New" '+((rowData.status=="New")?"selected":"")+'>Unverified</option><option value="Hold" '+((rowData.status=="Hold")?"selected":"")+'>Hold</option><option value="Verified" '+((rowData.status=="Verified")?"selected":"")+'>Verified</option><option value="Rejected" '+((rowData.status=="Rejected")?"selected":"")+'>Rejected</option></select>');
                },

            "defaultContent": 'l' 

        } ],

         "ajax":{

            url :"<?php echo base_url('admin/leads/custom_business_filter1');?>", // json datasource

            type: "post",  // type of method  , by default would be get

            'data': function(data){

                console.log(data);

                  // Read values

                  var filterrm = $('#filterrm').val();

                  var filterstatus = $('#filterstatus').val();

                  var filterprotype = $('#filterprotype').val();

                  var transctiondatestart = $('#transctiondatestart').val();

                  var transctiondateend = $('#transctiondateend').val();
					var inputname = $('#inputname').val();
        

                  // Append to data

                  data.filterrm = filterrm;
				  
				  data.inputname = inputname;

                  data.filterstatus = filterstatus;

                  data.filterprotype = filterprotype;

                  data.transctiondatestart = transctiondatestart;

                  data.transctiondateend = transctiondateend;

               },

            error: function(){  // error handling code

             // $(".employee-grid-error").html("");

              //$("#employee_grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');

              $("#employee_grid_processing").css("display","none");

            }

          }


        }); 

        $('#table_id tbody').on( 'change', '.business_status_list', function () {

            var business_status = $(this).val();

            var business_id = $(this).attr('data-lead-id');

            var url = "<?= base_url('admin/leads/bussiness_status_update') ?>";

            $.get(url, {

                    business_status: business_status,

                    business_id: business_id

                },

                function (res) {

                    $('.lightboxOverlay').html(res);

                })

        } );
		
        
        $('#filterrm,#filterstatus,#filterprotype,#transctiondatestart,#transctiondateend,#inputname').change(function(){
            dataTable.draw();
          });

  



 /* $('#table_id').DataTable({

 		"bProcessing": true,

          "sAjaxSource":"<?php echo base_url('admin/leads/custom_business_filter1');?>", // json datasource

           "aoColumns": [

 						{ mData: 'investor_name' } ,

 						{ mData: 'investor_name' } ,
 						{ mData: 'investor_name' } ,

                         { mData: 'company_name' },

                        { mData: 'pro_type' }

                ]

         });   */ 



});

// $(document).ready(function(){

//     $('#table_id').DataTable({

//         "pageLength": 10

//     });

// })

</script>



</body>

</html>

