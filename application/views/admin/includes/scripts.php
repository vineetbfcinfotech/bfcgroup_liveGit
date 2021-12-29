<?php include_once(APPPATH.'views/admin/includes/helpers_bottom.php'); ?>
<?php do_action('before_js_scripts_render'); ?>
<script src="<?php echo base_url('assets/plugins/app-build/vendor.js?v='.get_app_version()); ?>"></script>
<script src="<?php echo base_url('assets/plugins/jquery/jquery-migrate.'.(ENVIRONMENT === 'production' ? 'min.' : '').'js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/datatables.min.js?v='.get_app_version()); ?>"></script>
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

<script type="text/javascript">
// this is comment for the bug of the notification popup come again and again
$(document).ready(function() {
	 load_unseen_notification(<?= $_SESSION['staff_user_id']; ?>);
    load_count_notification(<?= $_SESSION['staff_user_id']; ?>);
    load_status_notification(<?= $_SESSION['staff_user_id']; ?>);
    load_popup_notification(<?= $_SESSION['staff_user_id']; ?>);
  
});
      setInterval(function(){ 
    load_popup_notification(<?= $_SESSION['staff_user_id']; ?>);
    load_unseen_notification(<?= $_SESSION['staff_user_id']; ?>);
    load_count_notification(<?= $_SESSION['staff_user_id']; ?>);
 }, 10000);


 function load_unseen_notification(view = '')
 {
  
   $.ajax({
    url:"<?php echo admin_url('Notification_data/index'); ?>",
    method:"POST",
    data:{view:view},
    success:function(data)
    {
     
      $('.notifications').html(data);
     
    },
    error: function (jqXHR, status, err) {
      // alert("Local error callback.");
    },
    complete: function (jqXHR, status) {
    }
   });
        
 }
function load_unseen_notification_pc(view = '')
 {
  
   $.ajax({
    url:"<?php echo admin_url('Notification_data/index_pc'); ?>",
    method:"POST",
    data:{view:view},
    success:function(data)
    {
     
      $('.notifications').html(data);
     
    },
    error: function (jqXHR, status, err) {
      // alert("Local error callback.");
    },
    complete: function (jqXHR, status) {
    }
   });
        
 }

  function load_popup_notification(view = '')
 {
  
   $.ajax({
    url:"<?php echo admin_url('Notification_data/popup'); ?>",
    method:"POST",
    data:{view:view},
    success:function(data)
    {
      if (!empty(data)) {
        alert_float('success', data);
      }else{

      }
     
      // $('.notifications').html(data);
     
    },
    error: function (jqXHR, status, err) {
      // alert("Local error callback.");
    },
    complete: function (jqXHR, status) {
    }
   });
        
 }
  function load_popup_notification_pc(view = '')
 {
  
   $.ajax({
    url:"<?php echo admin_url('Notification_data/popup_pc'); ?>",
    method:"POST",
    data:{view:view},
    success:function(data)
    {
      if (!empty(data)) {
        alert_float('success', data);
      }else{

      }
     
      // $('.notifications').html(data);
     
    },
    error: function (jqXHR, status, err) {
      // alert("Local error callback.");
    },
    complete: function (jqXHR, status) {
    }
   });
        
 }
 function load_count_notification(view = '')
 {
  
   $.ajax({
    url:"<?php echo admin_url('Notification_data/count'); ?>",
    method:"POST",
    data:{view:view},
    success:function(data)
    {
      // console.log(data);
      // alert(data);
      $('.count').html(data);
     
    },
    error: function (jqXHR, status, err) {
      // alert("Local error callback.");
    },
    complete: function (jqXHR, status) {
    }
   });
 //       setInterval(function(){ 
 //  load_unseen_notification();; 
 // }, 5000);   
 }
 function load_count_notification_pc(view = '')
 {
  
   $.ajax({
    url:"<?php echo admin_url('Notification_data/count_pc'); ?>",
    method:"POST",
    data:{view:view},
    success:function(data)
    {
      // console.log(data);
      // alert(data);
      $('.count').html(data);
     
    },
    error: function (jqXHR, status, err) {
      // alert("Local error callback.");
    },
    complete: function (jqXHR, status) {
    }
   });
 //       setInterval(function(){ 
 //  load_unseen_notification();; 
 // }, 5000);   
 }
  function load_status_notification(view = '')
 {
 $(".notification_data_status").click(function() {
      $.ajax({
            url: "<?php echo base_url(); ?>admin/Notification_data/change_status",
            method: 'POST',
            data: {
            id: view,
            },
            success: function (data) 
            {
              $('.count').html(data);
            }
      });
      });
 // setInterval(function(){ 
 //  load_unseen_notification();; 
 // }, 5000);
}
 function load_status_notification_pc(view = '')
 {
 $(".notification_data_status").click(function() {
      $.ajax({
            url: "<?php echo base_url(); ?>admin/Notification_data/change_status_pc",
            method: 'POST',
            data: {
            id: view,
            },
            success: function (data) 
            {
              $('.count').html(data);
            }
      });
      });
 // setInterval(function(){ 
 //  load_unseen_notification();; 
 // }, 5000);
}
      

</script>
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
<script type="text/javascript">
   $('#leadData1').dataTable({
        "searching": false,
        "bPaginate": false 
    });

</script>
<script>
    function check_required_inputs() {
    $('.required').each(function(){
        if( $(this).val() == "" ){
          alert('Please fill all the fields');

          return false;
        }
    });
};
var table = $('#emailStatus1').DataTable({
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
</script>

<?php } ?> 
<?php
/**
 * End users can inject any javascript/jquery code after all js is executed
 */
do_action('after_js_scripts_render');
?>
