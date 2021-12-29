<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');

/**
 * Check if the document should be RTL or LTR
 * The checking are performed in multiple ways eq Contact/Staff Direction from profile or from general settings *
 * @param  boolean $client_area
 * @return boolean
 */
function is_rtl($client_area = false)
{
    $CI = & get_instance();
    if (is_client_logged_in()) {
        $CI->db->select('direction')->from('tblcontacts')->where('id', get_contact_user_id());
        $direction = $CI->db->get()->row()->direction;

        if ($direction == 'rtl') {
            return true;
        } elseif ($direction == 'ltr') {
            return false;
        } elseif (empty($direction)) {
            if (get_option('rtl_support_client') == 1) {
                return true;
            }
        }

        return false;
    } elseif ($client_area == true) {
        // Client not logged in and checked from clients area
        if (get_option('rtl_support_client') == 1) {
            return true;
        }
    } elseif (is_staff_logged_in()) {
        if (isset($GLOBALS['current_user'])) {
            $direction = $GLOBALS['current_user']->direction;
        } else {
            $CI->db->select('direction')->from('tblstaff')->where('staffid', get_staff_user_id());
            $direction = $CI->db->get()->row()->direction;
        }

        if ($direction == 'rtl') {
            return true;
        } elseif ($direction == 'ltr') {
            return false;
        } elseif (empty($direction)) {
            if (get_option('rtl_support_admin') == 1) {
                return true;
            }
        }

        return false;
    } elseif ($client_area == false) {
        if (get_option('rtl_support_admin') == 1) {
            return true;
        }
    }

    return false;
}
/**
 * Generate encryption key for app-config.php
 * @return stirng
 */
function generate_encryption_key()
{
    $CI = & get_instance();
    // In case accessed from my_functions_helper.php
    $CI->load->library('encryption');
    $key = bin2hex($CI->encryption->create_key(16));

    return $key;
}

/**
 * Set current full url to for user to be redirected after login
 * Check below function to see why is this
 */
function redirect_after_login_to_current_url()
{
    get_instance()->session->set_userdata([
        'red_url' => current_full_url(),
    ]);
}
/**
* Check if user accessed url while not logged in to redirect after login
* @return null
*/
function maybe_redirect_to_previous_url()
{
    $CI = &get_instance();
    if ($CI->session->has_userdata('red_url')) {
        $red_url = $CI->session->userdata('red_url');
        $CI->session->unset_userdata('red_url');
        redirect($red_url);
    }
}
/**
 * Function used to validate all recaptcha from google reCAPTCHA feature
 * @param  string $str
 * @return boolean
 */
function do_recaptcha_validation($str = '')
{
    $CI = & get_instance();
    $CI->load->library('form_validation');
    $google_url = 'https://www.google.com/recaptcha/api/siteverify';
    $secret     = get_option('recaptcha_secret_key');
    $ip         = $CI->input->ip_address();
    $url        = $google_url . '?secret=' . $secret . '&response=' . $str . '&remoteip=' . $ip;
    $curl       = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($res, true);
    //reCaptcha success check
    if ($res['success']) {
        return true;
    }
    $CI->form_validation->set_message('recaptcha', _l('recaptcha_error'));

    return false;
}
/**
 * Get current date format from options
 * @return string
 */
function get_current_date_format($php = false)
{
    $format = get_option('dateformat');
    $format = explode('|', $format);

    $hook_data = do_action('get_current_date_format', [
        'format' => $format,
        'php'    => $php,
    ]);

    $format = $hook_data['format'];
    $php    = $php;

    if ($php == false) {
        return $format[1];
    }

    return $format[0];
}
/**
 * Check if current user is admin
 * @param  mixed $staffid
 * @return boolean if user is not admin
 */
function is_admin($staffid = '')
{
    /**
     * Checking for current user?
     */
    if (!is_numeric($staffid)) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->admin === '1';
        }
        $staffid = get_staff_user_id();
    }

    $CI = & get_instance();
    $CI->db->select('1')
    ->where('admin', 1)
    ->where('staffid', $staffid);

    return $CI->db->count_all_results('tblstaff') > 0 ? true : false;
}

function is_headtrm($staffid = '')
{
    /**
     * Checking for current user?
     */
    if (!is_numeric($staffid)) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->admin === '3';
        }
        $staffid = get_staff_user_id();
    }

    $CI = & get_instance();
    
    $CI->db->select('1')
    ->where('role', '40')
    ->where('reporting_manager', $staffid);
    

    return $CI->db->count_all_results('tblstaff') > 0 ? true : false;
}
/**
 * Is user logged in
 * @return boolean
 */
function is_logged_in()
{
    if (!is_client_logged_in() && !is_staff_logged_in()) {
        return false;
    }

    return true;
}
/**
 * Is client logged in
 * @return boolean
 */
function is_client_logged_in()
{
    $CI = & get_instance();
    if ($CI->session->has_userdata('client_logged_in')) {
        return true;
    }

    return false;
}
/**
 * Is staff logged in
 * @return boolean
 */
function is_staff_logged_in()
{
    $CI = & get_instance();
    if ($CI->session->has_userdata('staff_logged_in')) {
        return true;
    }

    return false;
}
/**
 * Return logged staff User ID from session
 * @return mixed
 */
function get_staff_user_id()
{
    $CI = & get_instance();
    if (!$CI->session->has_userdata('staff_logged_in')) {
        return false;
    }

    return $CI->session->userdata('staff_user_id');
}
/**
 * Return logged client User ID from session
 * @return mixed
 */
function get_client_user_id()
{
    $CI = & get_instance();
    if (!$CI->session->has_userdata('client_logged_in')) {
        return false;
    }

    return $CI->session->userdata('client_user_id');
}

/**
 * Get contact user id
 * @return mixed
 */
function get_contact_user_id()
{
    $CI = & get_instance();
    if (!$CI->session->has_userdata('contact_user_id')) {
        return false;
    }

    return $CI->session->userdata('contact_user_id');
}
/**
 * Get timezones list
 * @return array timezones
 */
function get_timezones_list()
{
    return [
        'EUROPE'     => DateTimeZone::listIdentifiers(DateTimeZone::EUROPE),
        'AMERICA'    => DateTimeZone::listIdentifiers(DateTimeZone::AMERICA),
        'INDIAN'     => DateTimeZone::listIdentifiers(DateTimeZone::INDIAN),
        'AUSTRALIA'  => DateTimeZone::listIdentifiers(DateTimeZone::AUSTRALIA),
        'ASIA'       => DateTimeZone::listIdentifiers(DateTimeZone::ASIA),
        'AFRICA'     => DateTimeZone::listIdentifiers(DateTimeZone::AFRICA),
        'ANTARCTICA' => DateTimeZone::listIdentifiers(DateTimeZone::ANTARCTICA),
        'ARCTIC'     => DateTimeZone::listIdentifiers(DateTimeZone::ARCTIC),
        'ATLANTIC'   => DateTimeZone::listIdentifiers(DateTimeZone::ATLANTIC),
        'PACIFIC'    => DateTimeZone::listIdentifiers(DateTimeZone::PACIFIC),
        'UTC'        => DateTimeZone::listIdentifiers(DateTimeZone::UTC),
    ];
}

/**
 * Check if visitor is on mobile
 * @return boolean
 */
function is_mobile()
{
    $CI = & get_instance();

    if ($CI->agent->is_mobile()) {
        return true;
    }

    return false;
}
/**
 * Set session alert / flashdata
 * @param string $type    Alert type
 * @param string $message Alert message
 */
function set_alert($type, $message)
{
    $CI = & get_instance();
    $CI->session->set_flashdata('message-' . $type, $message);
}
/**
 * Redirect to blank page
 * @param  string $message Alert message
 * @param  string $alert   Alert type
 */
function blank_page($message = '', $alert = 'danger')
{
    set_alert($alert, $message);
    redirect(admin_url('not_found'));
}
/**
 * Redirect to access danied page and log activity
 * @param  string $permission If permission based to check where user tried to acces
 */
function access_denied($permission = '')
{
    set_alert('danger', _l('access_denied'));
    logActivity('Tried to access page where don\'t have permission [' . $permission . ']');
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER']);
    } else {
        redirect(admin_url('access_denied'));
    }
}
/**
 * Throws header 401 not authorized, used for ajax requests
 */
function ajax_access_denied()
{
    header('HTTP/1.0 401 Unauthorized');
    echo _l('access_denied');
    die;
}
/**
 * Set debug message - message wont be hidden in X seconds from javascript
 * @since  Version 1.0.1
 * @param string $message debug message
 */
function set_debug_alert($message)
{
    get_instance()->session->set_flashdata('debug', $message);
}

/**
 * System popup message for admin area
 * This is used to show some general message for user within a big full screen div with white background
 * @param string $message message for the system popup
 */
function set_system_popup($message)
{
    if (!is_admin()) {
        return false;
    }

    if (defined('APP_DISABLE_SYSTEM_STARTUP_HINTS') && APP_DISABLE_SYSTEM_STARTUP_HINTS) {
        return false;
    }

    get_instance()->session->set_userdata([
        'system-popup' => $message,
    ]);
}
/**
 * Available date formats
 * @return array
 */
function get_available_date_formats()
{
    $date_formats = [
        'd-m-Y|%d-%m-%Y' => 'd-m-Y',
        'd/m/Y|%d/%m/%Y' => 'd/m/Y',
        'm-d-Y|%m-%d-%Y' => 'm-d-Y',
        'm.d.Y|%m.%d.%Y' => 'm.d.Y',
        'm/d/Y|%m/%d/%Y' => 'm/d/Y',
        'Y-m-d|%Y-%m-%d' => 'Y-m-d',
        'd.m.Y|%d.%m.%Y' => 'd.m.Y',
    ];

    return do_action('get_available_date_formats', $date_formats);
}
/**
 * Get weekdays as array
 * @return array
 */
function get_weekdays()
{
    return [
        _l('wd_monday'),
        _l('wd_tuesday'),
        _l('wd_wednesday'),
        _l('wd_thursday'),
        _l('wd_friday'),
        _l('wd_saturday'),
        _l('wd_sunday'),
    ];
}
/**
 * Get non translated week days for query help
 * Do not edit this
 * @return array
 */
function get_weekdays_original()
{
    return [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];
}
/**
 * Get admin url
 * @param string url to append (Optional)
 * @return string admin url
 */
function admin_url($url = '')
{
    $adminURI = get_admin_uri();

    if ($url == '' || $url == '/') {
        if ($url == '/') {
            $url = '';
        }

        return site_url($adminURI) . '/';
    }

    return site_url($adminURI . '/' . $url);
}
/**
 * Return admin URI
 * CUSTOM_ADMIN_URL is not yet tested well, don't define it
 * @return string
 */
function get_admin_uri()
{
    return do_action('admin_uri', DEFINED('CUSTOM_ADMIN_URL') ? CUSTOM_ADMIN_URL : ADMIN_URL);
}

/**
 * Outputs language string based on passed line
 * @since  Version 1.0.1
 * @param  string $line  language line string
 * @param  string $label sprint_f label
 * @return string        formatted language
 */
function _l($line, $label = '', $log_errors = true)
{
    $CI = & get_instance();

    $hook_data = do_action('before_get_language_text', ['line' => $line, 'label' => $label]);
    $line      = $hook_data['line'];
    $label     = $hook_data['label'];

    if (is_array($label) && count($label) > 0) {
        $_line = vsprintf($CI->lang->line(trim($line), $log_errors), $label);
    } else {
        $_line = @sprintf($CI->lang->line(trim($line), $log_errors), $label);
    }

    $hook_data = do_action('after_get_language_text', ['line' => $line, 'label' => $label, 'formatted_line' => $_line]);
    $_line     = $hook_data['formatted_line'];
    $line      = $hook_data['line'];

    if ($_line != '') {
        if (preg_match('/"/', $_line) && !is_html($_line)) {
            $_line = htmlspecialchars($_line, ENT_COMPAT);
        }

        return ForceUTF8\Encoding::toUTF8($_line);
    }

    if (mb_strpos($line, '_db_') !== false) {
        return 'db_translate_not_found';
    }

    return ForceUTF8\Encoding::toUTF8($line);
}

/**
 * Format date to selected dateformat
 * @param  date $date Valid date
 * @return date/string
 */
function _d($date)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00') {
        return '';
    }
    if (strpos($date, ' ') !== false) {
        return _dt($date);
    }
    $format = get_current_date_format();
    $date   = strftime($format, strtotime($date));

    return do_action('after_format_date', $date);
}

/**
 * Format datetime to selected datetime format
 * @param  datetime $date datetime date
 * @return datetime/string
 */
function _dt($date, $is_timesheet = false)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00 00:00:00') {
        return '';
    }
    $format = get_current_date_format();
    $hour12 = (get_option('time_format') == 24 ? false : true);

    if ($is_timesheet == false) {
        $date = strtotime($date);
    }

    if ($hour12 == false) {
        $tf = '%H:%M:%S';
        if ($is_timesheet == true) {
            $tf = '%H:%M';
        }
        $date = strftime($format . ' ' . $tf, $date);
    } else {
        $date = date(get_current_date_format(true) . ' g:i A', $date);
    }

    return do_action('after_format_datetime', $date);
}

/**
 * Convert string to sql date based on current date format from options
 * @param  string $date date string
 * @return mixed
 */
function to_sql_date($date, $datetime = false)
{
    if ($date == '' || $date == null) {
        return null;
    }

    $to_date     = 'Y-m-d';
    $from_format = get_current_date_format(true);


    $hook_data['date']        = $date;
    $hook_data['from_format'] = $from_format;
    $hook_data['datetime']    = $datetime;

    $hook_data = do_action('before_sql_date_format', $hook_data);

    $date        = $hook_data['date'];
    $from_format = $hook_data['from_format'];

    if ($datetime == false) {
        return date_format(date_create_from_format($from_format, $date), $to_date);
    }
    if (strpos($date, ' ') === false) {
        $date .= ' 00:00:00';
    } else {
        $hour12 = (get_option('time_format') == 24 ? false : true);
        if ($hour12 == false) {
            $_temp = explode(' ', $date);
            $time  = explode(':', $_temp[1]);
            if (count($time) == 2) {
                $date .= ':00';
            }
        } else {
            $tmp  = _simplify_date_fix($date, $from_format);
            $time = date('G:i', strtotime($tmp));
            $tmp  = explode(' ', $tmp);
            $date = $tmp[0] . ' ' . $time . ':00';
        }
    }

    $date = _simplify_date_fix($date, $from_format);
    $d    = strftime('%Y-%m-%d %H:%M:%S', strtotime($date));

    return do_action('to_sql_date_formatted', $d);
}

/**
 * Function that will check the date before formatting and replace the date places
 * This function is custom developed because for some date formats converting to y-m-d format is not possible
 * @param  string $date        the date to check
 * @param  string $from_format from format
 * @return string
 */
function _simplify_date_fix($date, $from_format)
{
    if ($from_format == 'd/m/Y') {
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $date);
    } elseif ($from_format == 'm/d/Y') {
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    } elseif ($from_format == 'm.d.Y') {
        $date = preg_replace('#(\d{2}).(\d{2}).(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    } elseif ($from_format == 'm-d-Y') {
        $date = preg_replace('#(\d{2})-(\d{2})-(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    }

    return $date;
}
/**
 * Check if passed string is valid date
 * @param  string  $date
 * @return boolean
 */
function is_date($date)
{
    if (strlen($date) < 10) {
        return false;
    }

    return (bool) strtotime($date);
}
/**
 * Get locale key by system language
 * @param  string $language language name from (application/languages) folder name
 * @return string
 */
function get_locale_key($language = 'english')
{
    $locale = 'en';
    if ($language == '') {
        return $locale;
    }

    $locales = get_locales();

    if (isset($locales[$language])) {
        $locale = $locales[$language];
    } elseif (isset($locales[ucfirst($language)])) {
        $locale = $locales[ucfirst($language)];
    } else {
        foreach ($locales as $key => $val) {
            $key      = strtolower($key);
            $language = strtolower($language);
            if (strpos($key, $language) !== false) {
                $locale = $val;
            // In case $language is bigger string then $key
            } elseif (strpos($language, $key) !== false) {
                $locale = $val;
            }
        }
    }

    $locale = do_action('before_get_locale', $locale);

    return $locale;
}
/**
 * Check if staff user has permission
 * @param  string  $permission permission shortname
 * @param  mixed  $staffid if you want to check for particular staff
 * @return boolean
 */
function has_permission($permission, $staffid = '', $can = '')
{
    $CI = & get_instance();

    /**
     * Maybe permission is function?
     * Example is_admin or is_staff_member
     */
    if (function_exists($permission) && is_callable($permission)) {
        return call_user_func($permission, $staffid);
    }

    /**
     * If user is admin return true
     * Admin have all permissions
     */
    if (is_admin($staffid)) {
        return true;
    }

    $staffid     = ($staffid == '' ? get_staff_user_id() : $staffid);
    $can         = ($can == '' ? 'view' : $can);
    $permissions = null;

    /**
     * Stop making query if we are doing checking for current user
     * Current user is stored in $GLOBALS including the permissions
     */
    if ((string) $staffid === (string) get_staff_user_id() && isset($GLOBALS['current_user'])) {
        $permissions = $GLOBALS['current_user']->permissions;
    }

    /**
     * Not current user?
     * Get permissions for this staff
     * Permissions will be cached in object cache upon first request
     */
    if (!$permissions) {
        if (!class_exists('staff_model')) {
            $CI->load->model('staff_model');
        }
        $permissions = $CI->staff_model->get_staff_permissions($staffid);
    }

    $hasPermission = false;
    /**
     * Based on permissions staff object check if user have permission
     */
    foreach ($permissions as $permObject) {
        if ($permObject->permission_name == $permission
            && $permObject->{'can_' . $can} == '1') {
            $hasPermission = true;

            break;
        }
    }

    return $hasPermission;
}
/**
 * Check if user is staff member
 * In the staff profile there is option to check IS NOT STAFF MEMBER eq like contractor
 * Some features are disabled when user is not staff member
 * @param  string  $staff_id staff id
 * @return boolean
 */
function is_staff_member($staff_id = '')
{
    $CI = & get_instance();
    if ($staff_id == '') {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->is_not_staff === '0';
        }
        $staff_id = get_staff_user_id();
    }

    $CI->db->where('staffid', $staff_id)
    ->where('is_not_staff', 0);

    return $CI->db->count_all_results('tblstaff') > 0 ? true : false;
}
/**
 * Load language in admin area
 * @param  string $staff_id
 * @return string return loaded language
 */
function load_admin_language($staff_id = '')
{
    $CI = & get_instance();

    $CI->lang->is_loaded = [];
    $CI->lang->language  = [];

    $language = get_option('active_language');
    if (is_staff_logged_in() || $staff_id != '') {
        $staff_language = get_staff_default_language($staff_id);
        if (!empty($staff_language)) {
            if (file_exists(APPPATH . 'language/' . $staff_language)) {
                $language = $staff_language;
            }
        }
    }

    $CI->lang->load($language . '_lang', $language);
    if (file_exists(APPPATH . 'language/' . $language . '/custom_lang.php')) {
        $CI->lang->load('custom_lang', $language);
    }

    $language = do_action('after_load_admin_language', $language);

    return $language;
}

/**
 * Get current url with query vars
 * @return string
 */
function current_full_url()
{
    $CI  = & get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());

    return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
}
/**
 * Triggers
 * @param  array  $users id of users to receive notifications
 * @return null
 */
function pusher_trigger_notification($users = [])
{
    if (get_option('pusher_realtime_notifications') == 0) {
        return false;
    }

    if (!is_array($users)) {
        return false;
    }

    if (count($users) == 0) {
        return false;
    }

    $app_key    = get_option('pusher_app_key');
    $app_secret = get_option('pusher_app_secret');
    $app_id     = get_option('pusher_app_id');

    if ($app_key == '' || $app_secret == '' || $app_id == '') {
        return false;
    }

    $pusher_options = do_action('pusher_options', []);

    if (!isset($pusher_options['cluster']) && get_option('pusher_cluster') != '') {
        $pusher_options['cluster'] = get_option('pusher_cluster');
    }

    $pusher = new Pusher\Pusher(
        $app_key,
        $app_secret,
        $app_id,
        $pusher_options
    );

    $channels = [];
    foreach ($users as $id) {
        array_push($channels, 'notifications-channel-' . $id);
    }

    $channels = array_unique($channels);

    $pusher->trigger($channels, 'notification', []);
}

if (defined('APP_CSRF_PROTECTION') && APP_CSRF_PROTECTION) {
    add_action('app_admin_head', 'csrf_jquery_token');
    add_action('app_customers_head', 'csrf_jquery_token');
    add_action('app_external_form_head', 'csrf_jquery_token');
    add_action('elfinder_tinymce_head', 'csrf_jquery_token');
}

/**
 * Generate md5 hash
 * @return string
 */
function app_generate_hash()
{
    return md5(rand() . microtime() . time() . uniqid());
}

/**
 * If user have enabled CSRF proctection this function will take care of the ajax requests and append custom header for CSRF
 * @return mixed
 */
function csrf_jquery_token()
{
    $csrf               = [];
    $csrf['formatted']  = [get_instance()->security->get_csrf_token_name() => get_instance()->security->get_csrf_hash()];
    $csrf['token_name'] = get_instance()->security->get_csrf_token_name();
    $csrf['hash']       = get_instance()->security->get_csrf_hash(); ?>
    <script>
        if (typeof (jQuery) === 'undefined' && !window.deferAfterjQueryLoaded) {
            window.deferAfterjQueryLoaded = [];
            Object.defineProperty(window, "$", {
                set: function (value) {
                    window.setTimeout(function () {
                        $.each(window.deferAfterjQueryLoaded, function (index, fn) {
                            fn();
                        });
                    }, 0);
                    Object.defineProperty(window, "$", {
                        value: value
                    });
                },
                configurable: true
            });
        }

        var csrfData = <?php echo json_encode($csrf); ?>;

        if (typeof(jQuery) == 'undefined') {

            window.deferAfterjQueryLoaded.push(function () {
                csrf_jquery_ajax_setup();
            });
            window.addEventListener('load',function(){
                csrf_jquery_ajax_setup();
            },true);
        } else {
            csrf_jquery_ajax_setup();
        }

        function csrf_jquery_ajax_setup() {
                $.ajaxSetup({
                    data: csrfData.formatted
                });
        }
 </script>
 <?php
}

/**
 * In some places of the script we use app_happy_text function to output some words in orange color
 * @param  string $text the text to check
 * @return string
 */
function app_happy_text($text)
{
    $regex = do_action('app_happy_regex', 'congratulations!?|congrats!?|happy!?|feel happy!?|awesome!?|yay!?');
    $re    = '/' . $regex . '/i';

    $app_happy_color = do_action('app_happy_color', 'rgb(255, 59, 0)');

    preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);
    foreach ($matches as $match) {
        $text = preg_replace('/' . $match[0] . '/i', '<span style="color:' . $app_happy_color . ';font-weight:bold;">' . $match[0] . '</span>', $text);
    }

    return $text;
}

/**
 * Return server temporary directory
 * @return string
 */
function get_temp_dir()
{
    if (function_exists('sys_get_temp_dir')) {
        $temp = sys_get_temp_dir();
        if (@is_dir($temp) && is_writable($temp)) {
            return rtrim($temp, '/\\') . '/';
        }
    }

    $temp = ini_get('upload_tmp_dir');
    if (@is_dir($temp) && is_writable($temp)) {
        return rtrim($temp, '/\\') . '/';
    }

    $temp = TEMP_FOLDER;
    if (is_dir($temp) && is_writable($temp)) {
        return $temp;
    }

    return '/tmp/';
}

// TODO
function round_timesheet_time($datetime)
{
    $dt = new DateTime($datetime);
    $r  = 15;
    // echo roundUpToMinuteInterval($dt,$r)->format('Y-m-d H:i:s') . '<br />';
    // echo roundDownToMinuteInterval($dt,$r)->format('Y-m-d H:i:s') . '<br />';
    $datetime = roundUpToMinuteInterval($dt, $r)->format('Y-m-d H:i:s');

    return $datetime;
}

/**
 * @param $dateTime
 * @param int $minuteInterval
 * @return \DateTime
 */
function roundUpToMinuteInterval($dateTime, $minuteInterval = 10)
{
    return $dateTime->setTime(
        $dateTime->format('H'),
        ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
        0
    );
}

/**
 * @param $dateTime
 * @param int $minuteInterval
 * @return \DateTime
 */
function roundDownToMinuteInterval($dateTime, $minuteInterval = 10)
{
    return $dateTime->setTime(
        $dateTime->format('H'),
        floor($dateTime->format('i') / $minuteInterval) * $minuteInterval,
        0
    );
}

/**
 * @param $dateTime
 * @param int $minuteInterval
 * @return \DateTime
 */
function roundToNearestMinuteInterval($dateTime, $minuteInterval = 10)
{
    return $dateTime->setTime(
        $dateTime->format('H'),
        round($dateTime->format('i') / $minuteInterval) * $minuteInterval,
        0
    );
}


function herapermission ($staffid = '')
{
    /**
     * Checking for current user?
     */
     
     $CI = & get_instance();
    if (!$CI->session->has_userdata('staff_logged_in')) {
        return false;
    }
        $staffid = $CI->session->userdata('staff_user_id');
    
     $CI = & get_instance();
    $rt=$CI->db->query("SELECT GROUP_CONCAT(staffid) as stfId FROM `tblstaff` WHERE `reporting_manager` = $staffid")->result_array();
    
      if(!empty($rt[0]['stfId'])) 
            {   
                    
                    $emp1=$rt[0];
                    $arr1 = $rt[0]['stfId'];
                    
                    $firstArray0= $emp1['stfId'];
                    $firstArray0=explode(',',$firstArray0);
                    array_push($firstArray0,$staffid);
                    implode( ',', $firstArray0 ); 
                    $finalone = implode( ',', $firstArray0 ); 
                    //return $finalone;
                    $mainar = explode(',',$finalone);
                    //return $mainar;
                    
                     
                    $rt1=$CI->db->query("SELECT GROUP_CONCAT(staffid) as stfId FROM `tblstaff` WHERE `reporting_manager` IN ($finalone)")->result_array();
                    
                    
                            if(!empty($rt1[0]['stfId']))
                            {
                            $emp2 = $rt1[0];
                            $firstArray= $emp1['stfId'];
                            $secondArray=$emp2['stfId'];
                            $firstArray=$mainar;
                            $secondArray=explode(',',$secondArray);
                            $arrmer1=array_merge($firstArray,$secondArray);
                            $exp2 = implode( ',', $arrmer1 ); 
                            
                            return $exp2;
                            $rt2=$CI->db->query("SELECT GROUP_CONCAT(staffid) as stfId FROM `tblstaff` WHERE `reporting_manager` IN ($exp2)")->result_array();
                            
                           
                                   /* if(!empty($rt2[0]['stfId']))
                                    {
                                        
                                        $emp3 = $rt2[0];
                                        $firstArray= $emp2['stfId'];
                                        $secondArray=$emp3['stfId'];
                                        $firstArray=explode(',',$firstArray);
                                        $secondArray=explode(',',$secondArray);
                                        $arrmer2=array_merge($firstArray,$secondArray);
                                        $exp3 = implode( ',', $arrmer2 ); 
                                        $rt3=$CI->db->query("SELECT GROUP_CONCAT(staffid) as stfId FROM `tblstaff` WHERE `reporting_manager` IN ($exp3)")->result_array();
                                        $arr4 = $rt3[0]['stfId'];
                                        if(!empty($rt3[0]['stfId']))
                                        {
                                            return "enter";
                                        }
                                        
                                        else
                                        {
                                          return $exp3;  
                                        }
                                        
                                        
                                    }
                                    
                                    else
                                    {
                                       
                                       return $exp2;
                                    }*/
                                
                            }
                            
                            else
                            {
                                return $arr1;
                            }
            }
      else
      {
          return $staffid;
      }
   
   
   // return $arr;
   
   
   
}

function is_sub_admin ($staffid = '')
        {
            /**
             * Checking for current user?
             */
            if (!is_numeric($staffid)) {
                if (isset($GLOBALS['current_user'])) {
                    return $GLOBALS['current_user']->admin === '2';
                }
                $staffid = get_staff_user_id();
            }
        
            $CI = & get_instance();
            $CI->db->select('1')
            ->where('admin', 2)
            ->where('staffid', $staffid);
        
            return $CI->db->count_all_results('tblstaff') > 0 ? true : false;
        }
        
 function get_credit_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('product_model') ) {
         $CI->load->model('product_model');
         
      }
      $statuses = $CI->product_model->get_allcategory();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
     foreach ($statuses as $status) {
         $s = $status['id'];
         $sql .= "SELECT SUM(transaction_amount) as total FROM tblbusiness WHERE product_type= '$s' AND converted_by='$staff_id' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(transaction_date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(transaction_date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $test =  $CI->db->last_query();
    // return $test;
      foreach ($statuses as $key => $status) {
         
         //$statuses[$key]['total'] = $result[$key]->total;
         if($result[$key]->total == null)
         {
             
         $statuses[$key]['total'] = 0;
         }
         else
         {
          
         $statuses[$key]['total'] = number_format($result[$key]->total, 2);  
         }
      }
      return $statuses;
   }
   
   
function get_credit_summary_netcredit_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('product_model') ) {
         $CI->load->model('product_model');
         
      }
      $statuses = $CI->product_model->get_allcategory();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
     foreach ($statuses as $status) {
         $s = $status['id'];
         $sql .= "SELECT SUM(net_credit) as total FROM tblbusiness WHERE product_type= '$s' AND converted_by='$staff_id' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(transaction_date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(transaction_date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $test =  $CI->db->last_query();
    // return $test;
      foreach ($statuses as $key => $status) {
         
         //$statuses[$key]['total'] = $result[$key]->total;
         if($result[$key]->total == null)
         {
             
         $statuses[$key]['total'] = 0;
         }
         else
         {
          
         $statuses[$key]['total'] =   number_format($result[$key]->total, 2);
         }
      }
      return $statuses;
   }
   
   
   function get_total_credit_summary_netcredit_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('product_model') ) {
         $CI->load->model('product_model');
         
      }
      $statuses = $CI->product_model->get_allcategory();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
     foreach ($statuses as $status) {
         $s = $status['id'];
         $sql .= "SELECT SUM(net_credit) as total FROM tblbusiness WHERE  converted_by='$staff_id' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(transaction_date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(transaction_date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $test =  $CI->db->last_query();
    // return $test;
      foreach ($statuses as $key => $status) {
         
         //$statuses[$key]['total'] = $result[$key]->total;
         if($result[$key]->total == null)
         {
             
         $statuses[$key]['total'] = 0;
         }
         else
         {
          
         $statuses[$key]['total'] =   number_format($result[$key]->total, 2);
         }
      }
      return $statuses;
   }
        
function get_dwr_member_meting_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_dwrstatus();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
      foreach ($statuses as $status) {
         $s = $status['name'];
         $sql .= "SELECT COUNT(*) as total FROM tblworkreport WHERE categorisation= '$s' AND staff_id='$staff_id' AND task_type='Personal_Meeting' AND task_category='Member' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $total_leads = $CI->db->count_all_results('tbldwrstatus');
      foreach ($statuses as $key => $status) {
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
         
         
             $statuses[$key]['total'] =  $result[$key]->total; 
         
      }
      return $statuses;
   }
   
   
 
   
   

function get_dwr_Client_meting_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_dwrstatus();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
      foreach ($statuses as $status) {
         $s = $status['name'];
         $sql .= "SELECT COUNT(*) as total FROM tblworkreport WHERE categorisation= '$s' AND staff_id='$staff_id' AND task_type='Personal_Meeting' AND task_category='Client' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $total_leads = $CI->db->count_all_results('tbldwrstatus');
      foreach ($statuses as $key => $status) {
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
         $statuses[$key]['total'] = $result[$key]->total;
      }
      return $statuses;
   }
   function get_dwr_Prospect_meting_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_dwrstatus();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
      foreach ($statuses as $status) {
         $s = $status['name'];
         $sql .= "SELECT COUNT(*) as total FROM tblworkreport WHERE categorisation= '$s' AND staff_id='$staff_id' AND task_type='Personal_Meeting' AND task_category='Prospect' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $total_leads = $CI->db->count_all_results('tbldwrstatus');
      foreach ($statuses as $key => $status) {
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
         $statuses[$key]['total'] = $result[$key]->total;
      }
      return $statuses;
   }function get_dwr_Lead_meting_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_dwrstatus();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
      foreach ($statuses as $status) {
         $s = $status['name'];
         $sql .= "SELECT COUNT(*) as total FROM tblworkreport WHERE categorisation= '$s' AND staff_id='$staff_id' AND task_type='Personal_Meeting' AND task_category='Lead' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $total_leads = $CI->db->count_all_results('tbldwrstatus');
      foreach ($statuses as $key => $status) {
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
         $statuses[$key]['total'] = $result[$key]->total;
      }
      return $statuses;
   }function get_dwr_Reference_meting_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_dwrstatus();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
      foreach ($statuses as $status) {
         $s = $status['name'];
         $sql .= "SELECT COUNT(*) as total FROM tblworkreport WHERE categorisation= '$s' AND staff_id='$staff_id' AND task_type='Personal_Meeting' AND task_category='Reference' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $total_leads = $CI->db->count_all_results('tbldwrstatus');
      foreach ($statuses as $key => $status) {
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
         $statuses[$key]['total'] = $result[$key]->total;
      }
      return $statuses;
   }function get_dwr_Visit_meting_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_dwrstatus();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
      foreach ($statuses as $status) {
         $s = $status['name'];
         $sql .= "SELECT COUNT(*) as total FROM tblworkreport WHERE categorisation= '$s' AND staff_id='$staff_id' AND task_type='Personal_Meeting' AND task_category='Visit' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $total_leads = $CI->db->count_all_results('tbldwrstatus');
      foreach ($statuses as $key => $status) {
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
         $statuses[$key]['total'] = $result[$key]->total;
      }
      return $statuses;
   }function get_dwr_Others_meting_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_dwrstatus();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
      foreach ($statuses as $status) {
         $s = $status['name'];
         $sql .= "SELECT COUNT(*) as total FROM tblworkreport WHERE categorisation= '$s' AND staff_id='$staff_id' AND task_type='Personal_Meeting' AND task_category='Others' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $total_leads = $CI->db->count_all_results('tbldwrstatus');
      foreach ($statuses as $key => $status) {
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
         $statuses[$key]['total'] = $result[$key]->total;
      }
      return $statuses;
   }
function get_dwr_calling_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_dwrcallingstatus();
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
     
      foreach ($statuses as $status) {
         $s = $status['name'];
         $sql .= "SELECT COUNT(*) as total FROM tblworkreport WHERE categorisation= '$s' AND staff_id='$staff_id' AND task_type='Calling' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
      
      $result = [];
      $sql = substr($sql, 0, -10);
      $result = $CI->db->query($sql)->result();
      $total_leads = $CI->db->count_all_results('tbldwrstatus');
      foreach ($statuses as $key => $status) {
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
         $statuses[$key]['total'] = $result[$key]->total;
      }
      return $statuses;
   }
   
function workingdaysdwr($staff_id, $start = '', $end = '' )
{
    
      $CI = &get_instance();
    if ( !class_exists('reports_model') ) {
         $CI->load->model('reports_model');
      }
      $statuses = $CI->reports_model->get_workingdaysdwr($staff_id,$start,$end  );
    //  $last = $CI->last_query();
    //  return $last;
      $total_workingdaysdwr = count($statuses);
      
      return $statuses;
}

function reference_taken($staff_id, $start = '', $end = '')
{
    $CI = &get_instance();
    if ( !class_exists('reports_model') ) {
         $CI->load->model('reports_model');
      }
      $statuses = $CI->reports_model->get_reference_taken($staff_id,$start,$end  );
      $total_workingdaysdwr = count($statuses);
      
      return $statuses;
}


function get_leave_summary_satffwise( $staff_id, $start = '', $end = '' )
   {
      $today = date('Y-m-d');
      $CI = &get_instance();
      if ( !class_exists('leads_model') ) {
         $CI->load->model('leads_model');
      }
      $statuses = $CI->leads_model->get_leave_cat_custom($staff_id,@$start, @$end);
      $totalStatuses = count($statuses);
      $has_permission_view = false;
      $sql = '';
	  
      foreach ($statuses as $status) {
         $s = $status['leave_category_id'];
         $sql .= "SELECT SUM(duration) as total FROM tblleaveapplication WHERE leave_category_id= '$s' AND user_id='$staff_id' AND application_status= '2' ";
         if ( !empty($start) && !empty($end) ) {
            $sql .= "AND DATE(leave_start_date) BETWEEN '$start' AND '$end' ";
         } else {
            $sql .= "AND DATE(leave_start_date) = '$today' ";
         }
         $sql .= " UNION ALL ";
      }
	  
      
      $result = [];
      $sql = substr($sql, 0, -10);
	  
      $result = $CI->db->query($sql)->result();
	 
      $total_leads = $CI->db->count_all_results('tblleavecategory');
	  
	   
      foreach ($statuses as $key => $status) {
		  /* echo "<pre>";
		  print_r($status); */
         if ( isset($status['lost']) || isset($status['junk']) ) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
         }
		 $result[$key]->total;
            $statuses[$key]['total'] =  $result[$key]->total; 
        }
      return $statuses;
   }


function assignment_done($staff_id, $start = '', $end = '')
{
    $CI = &get_instance();
    if ( !class_exists('reports_model') ) {
         $CI->load->model('reports_model');
      }
      $statuses = $CI->reports_model->get_assignment_done($staff_id,$start,$end  );
      $total_workingdaysdwr = count($statuses);
      
      return $statuses;
}
function totalSpecialLeave($staff_id){
	//print_r($staff_id); exit;
	$CI = &get_instance();
	$CI->load->model('Leave_management');
	$totalEmpSeLeave = $CI->Leave_management->getTotalSpecialLeave($staff_id);
	//echo $CI->db->last_query();exit;
	//print_r($totalEmpSeLeave);
	return $totalEmpSeLeave;
	//return $staff_id;
}

function getAppliedSpecialLeave($staff_id){
	$CI = &get_instance();
	$CI->load->model('Leave_management');
	$totalAppliesLeave = $CI->Leave_management->getAppliedSpecialLeave($staff_id);
	//echo $CI->db->last_query();exit;
	return $totalAppliesLeave;
}


function is_staffId($staffid)
{
    $CI = & get_instance();
    if (!$CI->session->has_userdata('staff_logged_in')) {
        return false;
    }
        //$staffid = $CI->session->userdata('staff_user_id');
    
    $rt=$CI->db->query("SELECT GROUP_CONCAT(staffid) as stfId FROM `tblstaff` WHERE `reporting_manager` = $staffid AND `active` = 1")->result_array();
    //echo $CI->db->last_query();exit;
	
      if(!empty($rt[0]['stfId']))
            {    
                    $emp1=$rt[0];
                    $arr1 = $rt[0]['stfId'];
                    
                    $firstArray0= $emp1['stfId'];
                    $firstArray0=explode(',',$firstArray0);
                    array_push($firstArray0,$staffid);
                    implode( ',', $firstArray0 ); 
                    $finalone = implode( ',', $firstArray0 ); 
                    //return $finalone;
                    $mainar = explode(',',$finalone);
                    //return $mainar;
                    
                     
                    $rt1=$CI->db->query("SELECT GROUP_CONCAT(staffid) as stfId FROM `tblstaff` WHERE `reporting_manager` IN ($finalone)")->result_array();
                    
                    
                            if(!empty($rt1[0]['stfId']))
                            {
                            $emp2 = $rt1[0];
                            $firstArray= $emp1['stfId'];
                            $secondArray=$emp2['stfId'];
                            $firstArray=$mainar;
                            $secondArray=explode(',',$secondArray);
                            $arrmer1=array_merge($firstArray,$secondArray);
                            $exp2 = implode( ',', $arrmer1 ); 
                            
                            return $exp2;
                            $rt2=$CI->db->query("SELECT GROUP_CONCAT(staffid) as stfId FROM `tblstaff` WHERE `reporting_manager` IN ($exp2)")->result_array();
                            }
                            
                            else
                            {
                                return $arr1;
                            }
            }
      else
      { 
          return $staffid;
      }
   
}
/* get staff role */
function get_imp_role($staffid = ""){
    if (!is_numeric($staffid)) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->role ;
        }
        $staffid = get_staff_user_id();
    }
    $CI = & get_instance();
    $CI->db->select('role')
        ->where('staffid', $staffid);
    return $CI->db->get('tblstaff')->row()->role ;
}
