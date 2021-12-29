<?php include_once(APPPATH.'views/admin/includes/helpers_bottom.php'); ?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<?php do_action('before_js_scripts_render'); ?>
<script src="<?php echo base_url('assets/plugins/app-build/vendor.js?v='.get_app_version()); ?>"></script>
<script src="<?php echo base_url('assets/plugins/jquery/jquery-migrate.'.(ENVIRONMENT === 'production' ? 'min.' : '').'js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/app-build/moment.min.js'); ?>"></script>
<?php app_select_plugin_js($locale); ?>
<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js?v='.get_app_version()); ?>"></script>
<?php app_jquery_validation_plugin_js($locale); ?>
<?php if(get_option('dropbox_app_key') != ''){ ?>
<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="<?php echo get_option('dropbox_app_key'); ?>"></script>
<?php } ?>
<?php if(isset($media_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/elFinder/js/elfinder.min.js'); ?>"></script>
<?php if(file_exists(FCPATH.'assets/plugins/elFinder/js/i18n/elfinder.'.get_media_locale($locale).'.js') && get_media_locale($locale) != 'en'){ ?>
<script src="<?php echo base_url('assets/plugins/elFinder/js/i18n/elfinder.'.get_media_locale($locale).'.js'); ?>"></script>
<?php } ?>
<?php } ?>
<?php if(isset($projects_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/jquery-comments/js/jquery-comments.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/gantt/js/jquery.fn.gantt.min.js'); ?>"></script>
<?php } ?>
<?php if(isset($circle_progress_asset)){ ?>
<script src="<?php echo base_url('assets/plugins/jquery-circle-progress/circle-progress.min.js'); ?>"></script>
<?php } ?>
<?php if(isset($calendar_assets)){ ?>
<script src="<?php echo base_url('assets/plugins/fullcalendar/fullcalendar.min.js?v='.get_app_version()); ?>"></script>
<?php if(get_option('google_api_key') != ''){ ?>
<script src="<?php echo base_url('assets/plugins/fullcalendar/gcal.min.js'); ?>"></script>
<?php } ?>
<?php if(file_exists(FCPATH.'assets/plugins/fullcalendar/locale/'.$locale.'.js') && $locale != 'en'){ ?>
<script src="<?php echo base_url('assets/plugins/fullcalendar/locale/'.$locale.'.js'); ?>"></script>
<?php } ?>
<?php } ?>
<?php echo app_script('assets/js','main.js'); ?>
<?php
/**
 * Global function for custom field of type hyperlink
 */
echo get_custom_fields_hyperlink_js_function(); ?>
<?php
/**
 * Check for any alerts stored in session
 */
app_js_alerts();
?>

<?php
/**
 * Check pusher real time notifications
 */
if(get_option('pusher_realtime_notifications') == 1){ ?>
<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
<script type="text/javascript">
 $(function(){
   // Enable pusher logging - don't include this in production
   // Pusher.logToConsole = true;
   <?php $pusher_options = do_action('pusher_options',array());
   if(!isset($pusher_options['cluster']) && get_option('pusher_cluster') != ''){
     $pusher_options['cluster'] = get_option('pusher_cluster');
   } ?>
   var pusher_options = <?php echo json_encode($pusher_options); ?>;
   var pusher = new Pusher("<?php echo get_option('pusher_app_key'); ?>", pusher_options);
   var channel = pusher.subscribe('notifications-channel-<?php echo get_staff_user_id(); ?>');
   channel.bind('notification', function(data) {
      fetch_notifications();
   });
});
</script>
<script>
    function check_required_inputs() {
    $('.required').each(function(){
        if( $(this).val() == "" ){
          alert('Please fill all the fields';

          return false;
        }
    });
});
</script>
<?php } ?>
<?php
/**
 * End users can inject any javascript/jquery code after all js is executed
 */
do_action('after_js_scripts_render');
?>

<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>




<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

<script>
$(document).ready(function() {
    
    // Setup - add a text input to each footer cell
    $('#example thead tr').clone(true).appendTo( '#example thead' );
    $('#example thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input style="width:100%;" type="text" placeholder="Search '+title+'" />' );
        //$(this).html( '<input style="background-color:#add8e6;width:80%;font-size:10px;width: 100%;padding: 12px 20px;margin: 8px 0;box-sizing: border-box;" type="text" placeholder="" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( '^' + this.value, true, false )
                    .draw();
            }
        } );
        
        $('input[type = search]').on( 'keyup', function () {

    //start check from first character
   table.search( '^' + this.value, true, false).draw();
}); 
        $('button').on( 'click', function () {
        //alert("test");  
    })
        
    } );
    var table = $('#example').DataTable( {
       // lengthMenu: [ 10, 25, 50, 75, 100 ],
        pageLength: 200,
        bPaginate: true,
        orderCellsTop: true,
        fixedHeader: true,
        dom: 'Bfrtip',
        //dom: 'lrtip',
        
        
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            
        ]
      
        
    } );
    $('#example3 thead tr').clone(true).appendTo( '#example3 thead' );
    $('#example3 thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        //$(this).html( '<input style="width:100%;" type="text" placeholder="Search '+title+'" />' );
        $(this).html( '<input style="width:100%;background-color:#add8e6;font-size:9px" type="text" placeholder="" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
        
        
    } );
    var table = $('#example3').DataTable({
         lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
         dom: 'lBfrtip,rtlp',
        
         //scrollY:        '50vh',
         buttons: [
              'excelHtml5', 'csvHtml5'
            //'excelHtml5', 'pdfHtml5', 'csvHtml5'
          ],
          bPaginate: true,
          fixedHeader: true,
          
          
          
        /*scrollY:        '50vh',
        scrollCollapse: true,
        
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        //fixedHeader: true,
        dom: 'lBfrtip,rtlp',
        //dom:["<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>"],
          buttons: [
              'excelHtml5', 'csvHtml5'
            //'excelHtml5', 'pdfHtml5', 'csvHtml5'
          ]
       */
        
    } );
    $('#example33 thead tr').clone(true).appendTo( '#example33 thead' );
    $('#example33 thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        //$(this).html( '<input style="width:100%;" type="text" placeholder="Search '+title+'" />' );
        $(this).html( '<input style="width:100%;background-color:#add8e6;font-size:9px" type="text" placeholder="" />' );
        //$(this).html( '<input style="background-color:#add8e6;font-size:10px;width: 100%;padding: 19px 50px;margin: 8px 0;box-sizing: border-box;" type="text" placeholder="" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
        $('button').on( 'click', function () {
        //alert("test");  
    })
        
    } );
    var table = $('#example33').DataTable({
         //scrollY:        '50vh',
        scrollCollapse: true,
        
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        //fixedHeader: true,
        dom: 'lBfrtip,rtlp',
        //dom:["<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>"],
          buttons: [
              'csvHtml5'
            //'excelHtml5', 'pdfHtml5', 'csvHtml5'
          ],
       order: [[ 0, "asc" ]]
        
    } );
    //
    $('#example34 thead tr').clone(true).appendTo( '#example34 thead' );
    $('#example34 thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        //$(this).html( '<input style="width:100%;" type="text" placeholder="Search '+title+'" />' );
        $(this).html( '<input style="width:100%;background-color:#add8e6;font-size:9px" type="text" placeholder="" />' );
        //$(this).html( '<input style="background-color:#add8e6;font-size:10px;width: 100%;padding: 19px 50px;margin: 8px 0;box-sizing: border-box;" type="text" placeholder="" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
        $('button').on( 'click', function () {
        //alert("test");  
    })
        
    } );
    var table = $('#example34').DataTable({
         //scrollY:        '50vh',
        scrollCollapse: true,
        
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        //fixedHeader: true,
        dom: 'lBfrtip,rtlp',
        //dom:["<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>"],
          buttons: [
              'csvHtml5'
            //'excelHtml5', 'pdfHtml5', 'csvHtml5'
          ],
       order: [[ 1, "asc" ]]
        
    } );
    
    $('#example35 thead tr').clone(true).appendTo( '#example35 thead' );
    $('#example35 thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        //$(this).html( '<input style="width:100%;" type="text" placeholder="Search '+title+'" />' );
        $(this).html( '<input style="width:100%;background-color:#add8e6;font-size:9px" type="text" placeholder="" />' );
        //$(this).html( '<input style="background-color:#add8e6;font-size:10px;width: 100%;padding: 19px 50px;margin: 8px 0;box-sizing: border-box;" type="text" placeholder="" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
        $('button').on( 'click', function () {
        //alert("test");  
    })
        
    } );
    var table = $('#example35').DataTable({
         //scrollY:        '50vh',
        scrollCollapse: true,
        
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        //fixedHeader: true,
        dom: 'lBfrtip,rtlp',
        //dom:["<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>"],
          buttons: [
              'csvHtml5'
            //'excelHtml5', 'pdfHtml5', 'csvHtml5'
          ],
       order: [[ 1, "asc" ]]
        
    } );
    
    $('#example36 thead tr').clone(true).appendTo( '#example36 thead' );
    $('#example36 thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        //$(this).html( '<input style="width:100%;" type="text" placeholder="Search '+title+'" />' );
        $(this).html( '<input style="width:100%;background-color:#add8e6;font-size:9px" type="text" placeholder="" />' );
        //$(this).html( '<input style="background-color:#add8e6;font-size:10px;width: 100%;padding: 19px 50px;margin: 8px 0;box-sizing: border-box;" type="text" placeholder="" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
        $('button').on( 'click', function () {
        //alert("test");  
    })
        
    } );
    var table = $('#example36').DataTable({
         //scrollY:        '50vh',
        scrollCollapse: true,
        
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        //fixedHeader: true,
        dom: 'lBfrtip,rtlp',
        //dom:["<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>"],
          buttons: [
              'csvHtml5'
            //'excelHtml5', 'pdfHtml5', 'csvHtml5'
          ],
       order: [[ 1, "asc" ]]
        
    } );
    
     $('#example37 thead tr').clone(true).appendTo( '#example37 thead' );
    $('#example37 thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        //$(this).html( '<input style="width:100%;" type="text" placeholder="Search '+title+'" />' );
        $(this).html( '<input style="width:100%;background-color:#add8e6;font-size:9px" type="text" placeholder="" />' );
        //$(this).html( '<input style="background-color:#add8e6;font-size:10px;width: 100%;padding: 19px 50px;margin: 8px 0;box-sizing: border-box;" type="text" placeholder="" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
        $('button').on( 'click', function () {
        //alert("test");  
    })
        
    } );
    var table = $('#example37').DataTable({
         //scrollY:        '50vh',
        scrollCollapse: true,
        
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        //fixedHeader: true,
        dom: 'lBfrtip,rtlp',
        //dom:["<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>"],
          buttons: [
              'csvHtml5'
            //'excelHtml5', 'pdfHtml5', 'csvHtml5'
          ],
       order: [[ 1, "asc" ]]
        
    } );
    
    
     $('#example38 thead tr').clone(true).appendTo( '#example38 thead' );
    $('#example38 thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        //$(this).html( '<input style="width:100%;" type="text" placeholder="Search '+title+'" />' );
        $(this).html( '<input style="width:100%;background-color:#add8e6;font-size:9px" type="text" placeholder="" />' );
        //$(this).html( '<input style="background-color:#add8e6;font-size:10px;width: 100%;padding: 19px 50px;margin: 8px 0;box-sizing: border-box;" type="text" placeholder="" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
        $('button').on( 'click', function () {
        //alert("test");  
    })
        
    } );
    var table = $('#example38').DataTable({
         //scrollY:        '50vh',
        scrollCollapse: true,
        
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        //fixedHeader: true,
        dom: 'lBfrtip,rtlp',
        //dom:["<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>"],
          buttons: [
              'csvHtml5'
            //'excelHtml5', 'pdfHtml5', 'csvHtml5'
          ],
       order: [[ 1, "asc" ]]
        
    } );
} );


</script>

<script type="text/javascript">

/*$(document).ready(function() {
    $('#exampletest').DataTable({
       
        scrollY:        '50vh',
        scrollCollapse: true,
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        //fixedHeader: true,
        dom: 'lBfrtip,rtlp',
          buttons: [
              'excelHtml5', 'csvHtml5'
          ],
         fixedColumns:   {
            leftColumns: 7
        },
        "ajax": {
            url : "http://bfcpublications.com/dev/chorus/admin/leads/viewassignedleads4",
            type : 'GET'
            
        },
        "columns": [
            { "data": "id" },
            { "data": "full_name" },
            { "data": "email" },
            { "data": "phonenumber" },
            { "data": "next_calling" },
            { "data": "language" },
            { "data": "book_format" },
            { "data": "booktitle" },
            { "data": "PublishedEarlier" },
            { "data": "description" },
            { "data": "calling_date" },
            { "data": "created_at" },
            
        ],
       

    });
        
});
      */  
      
    var table = $('#emailStatus').DataTable({
        scrollCollapse: true,
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        dom: 'lBfrtip,rtlp',
          buttons: [
              'csvHtml5'
          ],
       order: [[ 0, "asc" ]]
    } );
    
     var table = $('#aquisitionReport').DataTable({
        scrollCollapse: true,
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        dom: 'lBfrtip,rtlp',
          buttons: [
              'csvHtml5'
          ],
       order: [[ 0, "asc" ]]
    } );
    
     var table = $('#leadData').DataTable({
        scrollCollapse: true,
        lengthMenu: [[5,10,25,50,100,200,-1], [5,10,25,50,100,200,"All"] ],
        bPaginate: true,
        orderCellsTop: true,
        dom: 'lBfrtip,rtlp',
          buttons: [
              'csvHtml5'
          ],
       order: [[ 0, "asc" ]]
    } );


      $('#leadData1').dataTable({
        "searching": false,
        "bPaginate": false 
    });

</script>
