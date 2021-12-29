<?php init_head(); ?>
<div id="wrapper">
   <?= init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body" style="overflow-x: auto;">
                  <div class="dt-loader hide"></div>
                  <div id="attendance_calendar"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<script>
    var calendar_selector = $('#attendance_calendar');
    
    if (calendar_selector.length > 0) {
        var calendar_settings = {
            themeSystem: 'bootstrap3',
            customButtons: {},
            header: {
                left: '',
                center: 'title',
                right: ''
            },
            editable: false,
            eventLimit: parseInt(app_calendar_events_limit) + 1,
            views: {
                day: {
                    eventLimit: false
                }
            },
            defaultView: app_default_view_calendar,
            isRTL: (isRTL == 'true' ? true : false),
            eventStartEditable: false,
            timezone: app_timezone,
            firstDay: parseInt(app_calendar_first_day),
            year: moment.tz(app_timezone).format("YYYY"),
            month: moment.tz(app_timezone).format("M"),
            date: moment.tz(app_timezone).format("DD"),
            loading: function(isLoading, view) {
                isLoading && $('#calendar .fc-header-toolbar .btn-default').addClass('btn-info').removeClass('btn-default').css('display', 'block');
                !isLoading ? $('.dt-loader').addClass('hide') : $('.dt-loader').removeClass('hide');
            },
            eventSources: [{
                url: admin_url + 'attendance/currentmonth',
                type: 'POST',
                error: function() {
                    console.error('There was error fetching calendar data');
                },
            }, ],
            eventRender: function(event, element) {
                element.attr('title', event._tooltip);
                element.attr('id',event.id);
                element.attr('onclick', event.onclick);
                element.attr('data-toggle', 'tooltip');
                if (!event.url) {
                    element.click(function() { view_event(event.eventid); });
                }
            },
        };
        calendar_selector.fullCalendar(calendar_settings);
    }
</script>
</body>
</html>
