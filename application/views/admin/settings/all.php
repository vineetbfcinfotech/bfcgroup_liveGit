<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <?php echo form_open_multipart($this->uri->uri_string().'?group='.$view_name,array('id'=>'settings-form')); ?>
      <div class="row">
         <?php if($this->session->flashdata('debug')){ ?>
         <div class="col-lg-12">
            <div class="alert alert-warning">
               <?php echo $this->session->flashdata('debug'); ?>
            </div>
         </div>
         <?php } ?>
         <div class="col-md-3">
            <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
               <?php $settings_groups = array(
                  array(
                    'name'=>'general',
                    'lang'=>_l('settings_group_general'),
                    'order'=>1,
                  ),
                  array(
                    'name'=>'company',
                    'lang'=>_l('company_information'),
                    'order'=>2,
                  ),

                
                  array(
                    'name'=>'tasks',
                    'lang'=>_l('tasks'),
                    'order'=>8,
                  ),
                  array(
                    'name'=>'leads',
                    'lang'=>_l('leads'),
                    'order'=>10,
                  ),
                  
                 
                 
                  
                  );
                 
                  usort($settings_groups, function($a, $b) {
                  return $a['order'] - $b['order'];
                  });
                  /*print_r($settings_groups);*/
                  ?>
               <?php
                  $i = 0;
                  foreach($settings_groups as $group){
                    if($group['name'] == 'update' && !is_admin()){continue;}
                    ?>
               <li<?php if($i == 0){echo " class='active'"; } ?>>
                  <a href="<?php echo (!isset($group['url']) ? admin_url('settings?group='.$group['name']) : $group['url']) ?>" data-group="<?php echo $group['name']; ?>">
                  <?php echo $group['lang']; ?></a>
               </li>
               <?php $i++; } ?>
            </ul>
            <div class="panel_s">
               <div class="panel-body">
                   <div class="btn-bottom-toolbar text-right">
                     <button type="submit" class="btn btn-info"><?php echo _l('settings_save'); ?></button>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-9">
            <div class="panel_s">
               <div class="panel-body">
                  <?php do_action('before_settings_group_view',$group_view); ?>
                  <?php echo $group_view; ?>
                  <?php do_action('after_settings_group_view',$group_view); ?>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
      </div>
      <?php echo form_close(); ?>
      <div class="btn-bottom-pusher"></div>
   </div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>
<script>
   $(function(){
   var viewName = "<?php echo $view_name; ?>";
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var settingsForm = $('#settings-form');
      var tab = $(this).attr('href').slice(1);
      settingsForm.attr('action','<?php echo site_url($this->uri->uri_string()); ?>?group='+viewName+'&active_tab='+tab)
    });
    $('input[name="settings[email_protocol]"]').on('change',function(){
      if($(this).val() == 'mail'){
        $('.smtp-fields').addClass('hide');
      } else {
       $('.smtp-fields').removeClass('hide');
     }
   });
    
    });
    <?php if($view_name == 'pusher'){ ?>
      <?php if(get_option('desktop_notifications') == '1'){ ?>
        // Let's check if the browser supports notifications
        if (!("Notification" in window)) {
          $('#pusherHelper').html('<div class="alert alert-danger">Your browser does not support desktop notifications, please disable this option or use more modern browser.</div>');
        } else {
          if(Notification.permission == "denied"){
            $('#pusherHelper').html('<div class="alert alert-danger">Desktop notifications not allowed in browser settings, search on Google "How to allow desktop notifications for <?php echo $this->agent->browser(); ?>"</div>');
          }
        }
        <?php } ?>
          <?php if(get_option('pusher_realtime_notifications') == '0'){ ?>
              $('input[name="settings[desktop_notifications]"]').prop('disabled',true);
          <?php } ?>
          <?php } ?>
          $('input[name="settings[pusher_realtime_notifications]"]').on('change',function(){
            if($(this).val() == '1'){
              $('input[name="settings[desktop_notifications]"]').prop('disabled',false);
            } else {
              $('input[name="settings[desktop_notifications]"]').prop('disabled',true);
              $('input[name="settings[desktop_notifications]"][value="0"]').prop('checked',true);
            }
          });
          $('.test_email').on('click', function() {
            var email = $('input[name="test_email"]').val();
            if (email != '') {
             $(this).attr('disabled', true);
             $.post(admin_url + 'emails/sent_smtp_test_email', {
              test_email: email
            }).done(function(data) {
              window.location.reload();
            });
          }
        });
          $('#update_app').on('click',function(e){
           e.preventDefault();
           $('input[name="settings[purchase_key]"]').parents('.form-group').removeClass('has-error');
           var purchase_key = $('input[name="settings[purchase_key]"]').val();
           var latest_version = $('input[name="latest_version"]').val();
           var update_errors;
           if(purchase_key != ''){
             var ubtn = $(this);
             ubtn.html('<?php echo _l('wait_text'); ?>');
             ubtn.addClass('disabled');
             $.post(admin_url+'auto_update',{purchase_key:purchase_key,latest_version:latest_version,auto_update:true}).done(function(){
               window.location.reload();
             }).fail(function(response){
               update_errors = JSON.parse(response.responseText);
               $('#update_messages').html('<div class="alert alert-danger"></div>');
               for (var i in update_errors){
                $('#update_messages .alert').append('<p>'+update_errors[i]+'</p>');
              }
              ubtn.removeClass('disabled');
              ubtn.html($('.update_app_wrapper').data('original-text'));
            });
           } else {
            $('input[name="settings[purchase_key]"]').parents('.form-group').addClass('has-error');
          }
        });
        });
</script>
</body>
</html>
