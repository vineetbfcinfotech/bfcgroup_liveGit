<?php init_head(); ?>
<style>
.dataTables_length, .dt-buttons.btn-group, .dataTables_filter, #example_info, #example_paginate{
	display: none !important;
}
</style>
<div id="wrapper">
    <?php init_clockinout(); ?>
    <div class="screen-options-area"></div>
    <div class="screen-options-btn">
     <!--   <?php echo _l('dashboard_options'); ?>-->
    </div>
    
    <div class="content">
        <div class="row">

            <?php //include_once(APPPATH . 'views/admin/includes/alerts.php'); ?>

            <?php //do_action( 'before_start_render_dashboard_content' ); ?>

            <div class="clearfix"></div>

           <!-- <div class="col-md-12 mtop30" data-container="top-12">
                <?php render_dashboard_widgets('top-12'); ?>
            </div>-->

            <div class="col-md-8" data-container="middle-left-6">
                <?php render_dashboard_widgets('middle-left-6'); ?>
            </div> 
            <div class="col-md-4" data-container="middle-right-6">
                <?php render_dashboard_widgets('middle-right-6'); ?>
            </div>

            <?php do_action('after_dashboard_half_container'); ?>
			
            <div class="col-md-8" data-container="left-8">
			<?php 
			render_dashboard_widgets('left-leave'); 
			?>
				
                <?php render_dashboard_widgets('left-8'); ?>
            </div>
            <div class="col-md-4" data-container="right-4">
                <?php render_dashboard_widgets('right-4'); ?>
            </div>

            <div class="clearfix"></div>
            <div class="clearfix"></div>
 
            <div class="col-md-8" data-container="bottom-right-4">
               <div id="chartContainer"></div>
            </div>
 
            <?php do_action('after_dashboard'); ?>
        </div>
    </div>
 
</div>
<script>
    google_api = '<?php echo get_option('google_api_key'); ?>';
    calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>

<!-- <script>(function(){var pp=document.createElement('script'), ppr=document.getElementsByTagName('script')[0]; stid='bFNNcHhoY3Z4U05rTlpYb05NOHFxUT09';pp.type='text/javascript'; pp.async=true; pp.src=('https:' == document.location.protocol ? 'https://' : 'http://') + 's01.live2support.com/dashboardv2/chatwindow/'; ppr.parentNode.insertBefore(pp, ppr);})();</script> -->

<!-- <script type="text/javascript">window.maytapChat||(function(a,b,c){var e,f=a.getElementsByTagName(b)[0];a.getElementById(c)||(e=a.createElement(b),e.id=c,e.async=true,e.src='//live.maytap.me/widget/98207880-18ee-11eb-b776-9762a3fcd585',e.onload=function(){new maytapChat().widget()},f.parentNode.insertBefore(e,f))})(document,'script','maytapChat');</script> -->


</body>
</html>
