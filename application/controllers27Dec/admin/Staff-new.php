<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Staff extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /* List all staff members */
    public function index()
    {
        
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('staff');
        }
        //$data['staff_members'] = $this->staff_model->get('', ['active'=>1]);
        $data['staff_members'] = $this->staff_model->get('', []);
		$data['checkIncative'] = end($this->uri->segment_array());
		//echo $this->db->last_query();exit;
        $data['title']         = _l('staff_members');
        $this->load->view('admin/staff/manage',$data);
    }

function getstaff(){
	$dd= $this->db
	->select('staffid,email,firstname,lastname,phonenumber')
			->where('active',1)
			->get('tblstaff')
			->result();
			
			
//	staff_model->get('', ['active'=>1]);
	echo json_encode($dd);
}
    /* Add new staff member or edit existing */
    public function member_bk($id = '')
    {
        
       
        do_action('staff_member_edit_view_profile', $id);

        $this->load->model('departments_model');
        if ($this->input->post()) {
             
            $data = $this->input->post();
            // Don't do XSS clean here.
            $data['email_signature'] = $this->input->post('email_signature', false);
            $data['password']        = $this->input->post('password', false);

            if ($id == '') {
                
                $id = $this->staff_model->add($data);
                if ($id) {
                    handle_staff_profile_image_upload($id);
                    set_alert('success', _l('added_successfully', _l('staff_member')));
                    redirect(admin_url('staff/member/' . $id));
                }
            } else {
                
                handle_staff_profile_image_upload($id);
               
                $response = $this->staff_model->update($data, $id);
                if (is_array($response)) {
                    if (isset($response['cant_remove_main_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_main_admin'));
                    } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                    }
                } elseif ($response == true) {
                    set_alert('success', _l('updated_successfully', _l('staff_member')));
                }
                redirect(admin_url('staff/member/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('staff_member_lowercase'));
        } else {
            $member = $this->staff_model->get($id);
            if (!$member) {
                blank_page('Staff Member Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = $member->firstname . ' ' . $member->lastname;
            $data['staff_permissions'] = $this->roles_model->get_staff_permissions($id);
            $data['staff_departments'] = $this->departments_model->get_staff_departments($member->staffid);

            $ts_filter_data = [];
            if ($this->input->get('filter')) {
                if ($this->input->get('range') != 'period') {
                    $ts_filter_data[$this->input->get('range')] = true;
                } else {
                    $ts_filter_data['period-from'] = $this->input->get('period-from');
                    $ts_filter_data['period-to']   = $this->input->get('period-to');
                }
            } else {
                $ts_filter_data['this_month'] = true;
            }

            $data['logged_time'] = $this->staff_model->get_logged_time_data($id, $ts_filter_data);
            $data['timesheets']  = $data['logged_time']['timesheets'];
        }
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['roles']         = $this->roles_model->get();
        $data['permissions']   = $this->roles_model->get_permissions();
        $data['user_notes']    = $this->misc_model->get_notes($id, 'staff');
        $data['departments']   = $this->departments_model->get();
        $data['reporting_managers']   = $this->staff_model->getAllRm();
        $data['title']         = $title;
        //echo "<pre>".print_r($data)."</pre>";exit;
        $this->load->view('admin/staff/member', $data);
    }
	public function department_name($d_id){
		$this->db->select("*");
		$this->db->from("tbldepartments");
		$this->db->where("departmentid", $d_id);
		return $this->db->get()->row();
	}
    public function member($id = ''){
        
       //$id = 31;
        
           
            do_action('staff_member_edit_view_profile', $id);
    
            $this->load->model('departments_model');
            $this->load->model('teams_model');
            if ($this->input->post()) {
                
                $data = $this->input->post();
               
                // Don't do XSS clean here.
                $data['email_signature']    = $this->input->post('email_signature', false);
                $data['password']           = $this->input->post('password', false);
                $data['reporting_manager']  = implode(",",$this->input->post('rm_id'));
                $data['department_id']  = implode(",",$this->input->post('departments'));
                $data['company']  = implode(",",$this->input->post('company'));
                $data['exit_date']              = $this->input->post('exit_date');
				if($id == ''){
					$data['active']              = '1';
				}else{
					$data['active']              = $this->input->post('active');
				}
                
                $currentdate = date("Y-m-d h:i:s");
				$fullname = $this->input->post('firstname')." ".$this->input->post('lastname');
				$confDate = $this->input->post('confirmation_date');
				
				
				
                if ($id == '') {
                    $id = $this->staff_model->add_new($data);
					
					$dataNot = array("isread"=> 0, "isread_inline"=> 0, "date"=>$currentdate, "description"=>"new_joining", "fromuserid"=> $id, "join_date"=>$this->input->post('confirmation_date'), "from_fullname"=>$this->input->post('firstname')." ".$this->input->post('lastname'));
					//print_r($dataNot);exit; 
					$this->db->insert('tblnotifications', $dataNot);
					/* echo $this->db->last_query();exit;
					exit("Notification"); */
                    if ($id) {
						
						/* $dataNot = array("isread"=>"0", "isread_inline"=>"0", "date"=>$currentdate, "fromuserid"=>$id, "from_fullname"=>$fullname, "description"=>"new_joining", "notification_date"=>$confDate);
					print_r($dataNot);exit;
					$this->db->insert('tblnotifications', $dataNot); */
						
                        handle_staff_profile_image_upload($id);
                        set_alert('success', _l('added_successfully', _l('staff_member')));
                        redirect(admin_url('staff/member/' . $id));
                    }
                } else {
                   
                   
                    handle_staff_profile_image_upload($id);
                   
                    $response = $this->staff_model->update_new($data, $id);
					if($this->input->post('active') == 1 || $this->input->post('exit_date') != ""){
						$dataNot = array("isread"=> 0, "isread_inline"=> 0, "date"=>$currentdate, "description"=>"inactive", "fromuserid"=> $id, "join_date"=>$this->input->post('exit_date'), "from_fullname"=>$this->input->post('firstname')." ".$this->input->post('lastname'));
						//print_r($dataNot);exit; 
						$this->db->insert('tblnotifications', $dataNot);
					}
					
                     /*print_r($data);
               echo "helo";
               exit;*/
                    if (is_array($response)) {
                        if (isset($response['cant_remove_main_admin'])) {
                            set_alert('warning', _l('staff_cant_remove_main_admin'));
                        } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                            set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                        }
                    } elseif ($response == true) {
                        set_alert('success', _l('updated_successfully', _l('staff_member')));
                    }
                    redirect(admin_url('staff/member/' . $id));
                }
            }
            if ($id == '') {
                $title = _l('add_new', _l('staff_member_lowercase'));
            
                //exit;
            } else {
                $member = $this->staff_model->get($id);
                if (!$member) {
                    blank_page('Staff Member Not Found', 'danger');
                }
                
                
                $data['member']            = $member;
                $title                     = $member->firstname . ' ' . $member->lastname;
                $data['staff_permissions'] = $this->roles_model->get_staff_permissions($id);
                $data['staff_departments'] = $this->departments_model->get_staff_departments($member->staffid);
                $old_teamdetails=$this->teams_model->getStaffTeamDetails($member->staffid);
                $data['teamdetails']=$old_teamdetails;
                $data['teams']=$this->teams_model->getTeamByDeptId($old_teamdetails->departmentid);
                $data['oldroles']=$this->teams_model->getRolesByDeptIdWithTeamId($old_teamdetails->team_id,$old_teamdetails->departmentid);
                $data['oldrmlist']=$this->teams_model->getRmlistByDeptIdWithTeamIdRoleId($old_teamdetails->role_id,$old_teamdetails->team_id,$old_teamdetails->departmentid);
                $ts_filter_data = [];
                if ($this->input->get('filter')) {
                    if ($this->input->get('range') != 'period') {
                        $ts_filter_data[$this->input->get('range')] = true;
                    } else {
                        $ts_filter_data['period-from'] = $this->input->get('period-from');
                        $ts_filter_data['period-to']   = $this->input->get('period-to');
                    }
                } else {
                    $ts_filter_data['this_month'] = true;
                }
    
                $data['logged_time'] = $this->staff_model->get_logged_time_data($id, $ts_filter_data);
                $data['timesheets']  = $data['logged_time']['timesheets'];
            }
            $this->load->model('currencies_model');
            $data['base_currency'] = $this->currencies_model->get_base_currency();
            $data['roles']         = $this->roles_model->get();
            $data['permissions']   = $this->roles_model->get_permissions();
            $data['user_notes']    = $this->misc_model->get_notes($id, 'staff');
            $data['departments']   = $this->departments_model->get();
            $data['reporting_managers']   = $this->staff_model->getAllRm();
            $data['title']         = $title;
            
            //echo "<pre>".print_r($data)."</pre>";exit;
            $this->load->view('admin/staff/member', $data);
        }

    public function save_dashboard_widgets_order()
    {
        do_action('before_save_dashboard_widgets_order');

        $post_data = $this->input->post();
        foreach ($post_data as $container => $widgets) {
            if ($widgets == 'empty') {
                $post_data[$container] = [];
            }
        }
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_order', serialize($post_data));
    }

    public function save_dashboard_widgets_visibility()
    {
        do_action('before_save_dashboard_widgets_visibility');

        $post_data = $this->input->post();
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility', serialize($post_data['widgets']));
    }

    public function reset_dashboard()
    {
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility', null);
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_order', null);

        redirect(admin_url());
    }

    public function save_hidden_table_columns()
    {
        do_action('before_save_hidden_table_columns');
        $data   = $this->input->post();
        $id     = $data['id'];
        $hidden = isset($data['hidden']) ? $data['hidden'] : [];
        update_staff_meta(get_staff_user_id(), 'hidden-columns-' . $id, json_encode($hidden));
    }

    public function change_language($lang = '')
    {
        $lang = do_action('before_staff_change_language', $lang);
        $this->db->where('staffid', get_staff_user_id());
        $this->db->update('tblstaff', ['default_language' => $lang]);
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url());
        }
    }

    public function timesheets()
    {
        $data['view_all'] = false;
        if (is_admin() && $this->input->get('view') == 'all') {
            $data['staff_members_with_timesheets'] = $this->db->query('SELECT DISTINCT staff_id FROM tbltaskstimers WHERE staff_id !=' . get_staff_user_id())->result_array();
            $data['view_all']                      = true;
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('staff_timesheets', ['view_all' => $data['view_all']]);
        }

        if ($data['view_all'] == false) {
            unset($data['view_all']);
        }
        $data['logged_time'] = $this->staff_model->get_logged_time_data(get_staff_user_id());
        $data['title']       = '';
        $this->load->view('admin/staff/timesheets', $data);
    }

    public function delete()
    {
        if (!is_admin()) {
            if (is_admin($this->input->post('id'))) {
                die('Busted, you can\'t delete administrators');
            }
        }
        if (has_permission('staff', '', 'delete')) {
            $success = $this->staff_model->delete($this->input->post('id'), $this->input->post('transfer_data_to'));
            if ($success) {
                set_alert('success', _l('deleted', _l('staff_member')));
            }
        }
        redirect(admin_url('staff'));
    }

    /* When staff edit his profile */
    public function edit_profile()
    {
        if ($this->input->post()) {
            handle_staff_profile_image_upload();
            $data = $this->input->post();
            // Don't do XSS clean here.
            $data['email_signature'] = $data['email_signature'] = $this->input->post('email_signature', false);

            $success = $this->staff_model->update_profile($data, get_staff_user_id());
            if ($success) {
                set_alert('success', _l('staff_profile_updated'));
            }
            redirect(admin_url('staff/edit_profile/' . get_staff_user_id()));
        }
        $member = $this->staff_model->get(get_staff_user_id());
        $this->load->model('departments_model');
        $data['member']            = $member;
        $data['departments']       = $this->departments_model->get();
        $data['staff_departments'] = $this->departments_model->get_staff_departments($member->staffid);
        $data['title']             = $member->firstname . ' ' . $member->lastname;
        $this->load->view('admin/staff/profile', $data);
    }

    /* Remove staff profile image / ajax */
    public function remove_staff_profile_image($id = '')
    {
        $staff_id = get_staff_user_id();
        if (is_numeric($id) && (has_permission('staff', '', 'create') || has_permission('staff', '', 'edot'))) {
            $staff_id = $id;
        }
        do_action('before_remove_staff_profile_image');
        $member = $this->staff_model->get($staff_id);
        if (file_exists(get_upload_path_by_type('staff') . $staff_id)) {
            delete_dir(get_upload_path_by_type('staff') . $staff_id);
        }
        $this->db->where('staffid', $staff_id);
        $this->db->update('tblstaff', [
            'profile_image' => null,
        ]);

        if (!is_numeric($id)) {
            redirect(admin_url('staff/edit_profile/' . $staff_id));
        } else {
            redirect(admin_url('staff/member/' . $staff_id));
        }
    }

    /* When staff change his password */
    public function change_password_profile()
    {
        if ($this->input->post()) {
            $response = $this->staff_model->change_password($this->input->post(null, false), get_staff_user_id());
            if (is_array($response) && isset($response[0]['passwordnotmatch'])) {
                set_alert('danger', _l('staff_old_password_incorrect'));
            } else {
                if ($response == true) {
                    set_alert('success', _l('staff_password_changed'));
                } else {
                    set_alert('warning', _l('staff_problem_changing_password'));
                }
            }
            redirect(admin_url('staff/edit_profile'));
        }
    }

    /* View public profile. If id passed view profile by staff id else current user*/
    public function profile($id = '')
    {
		if ($id == '') {
            $id = get_staff_user_id();
        }

        do_action('staff_profile_access', $id);

        $data['logged_time'] = $this->staff_model->get_logged_time_data($id);
        $data['staff_p']     = $this->staff_model->get($id);

        if (!$data['staff_p']) {
            blank_page('Staff Member Not Found', 'danger');
        }

        $this->load->model('departments_model');
        $data['staff_departments'] = $this->departments_model->get_staff_departments($data['staff_p']->staffid);
        $data['departments']       = $this->departments_model->get();
        $data['title']             = _l('staff_profile_string') . ' - ' . $data['staff_p']->firstname . ' ' . $data['staff_p']->lastname;
        // notifications
        $total_notifications = total_rows('tblnotifications', [
            'touserid' => get_staff_user_id(),
        ]);
        $data['total_pages'] = ceil($total_notifications / $this->misc_model->get_notifications_limit());
        $this->load->view('admin/staff/myprofile', $data);
    }

    /* Change status to staff active or inactive / ajax */
    public function change_staff_status($id, $status)
    {
        if (has_permission('staff', '', 'edit')) {
            if ($this->input->is_ajax_request()) {
                $this->staff_model->change_staff_status($id, $status);
            }
        }
    }

    /* Logged in staff notifications*/
    public function notifications()
    {
        $this->load->model('misc_model');
        if ($this->input->post()) {
            $page   = $this->input->post('page');
            $offset = ($page * $this->misc_model->get_notifications_limit());
            $this->db->limit($this->misc_model->get_notifications_limit(), $offset);
            $this->db->where('touserid', get_staff_user_id());
            $this->db->or_where('description', 'birthday_notification');
            $this->db->or_where('description', 'new_joining');
            $this->db->or_where('description', 'inactive');
            $this->db->order_by('date', 'desc');
            $notifications = $this->db->get('tblnotifications')->result_array();
            $i             = 0;
            foreach ($notifications as $notification) {
                if (($notification['fromcompany'] == null && $notification['fromuserid'] != 0) || ($notification['fromcompany'] == null && $notification['fromclientid'] != 0)) {
                    if ($notification['fromuserid'] != 0) {
                        $notifications[$i]['profile_image'] = '<a href="' . admin_url('staff/profile/' . $notification['fromuserid']) . '">' . staff_profile_image($notification['fromuserid'], [
                        'staff-profile-image-small',
                        'img-circle',
                        'pull-left',
                    ]) . '</a>';
                    } else {
                        $notifications[$i]['profile_image'] = '<a href="' . admin_url('clients/client/' . $notification['fromclientid']) . '">
                    <img class="client-profile-image-small img-circle pull-left" src="' . contact_profile_image_url($notification['fromclientid']) . '"></a>';
                    }
                } else {
                    $notifications[$i]['profile_image'] = '';
                    $notifications[$i]['full_name']     = '';
                }
                $additional_data = '';
                if (!empty($notification['additional_data'])) {
                    $additional_data = unserialize($notification['additional_data']);
                    $x               = 0;
                    foreach ($additional_data as $data) {
                        if (strpos($data, '<lang>') !== false) {
                            $lang = get_string_between($data, '<lang>', '</lang>');
                            $temp = _l($lang);
                            if (strpos($temp, 'project_status_') !== false) {
                                $status = get_project_status_by_id(strafter($temp, 'project_status_'));
                                $temp   = $status['name'];
                            }
                            $additional_data[$x] = $temp;
                        }
                        $x++;
                    }
                }
                $notifications[$i]['description'] = _l($notification['description'], $additional_data);
                $notifications[$i]['date']        = time_ago($notification['date']);
                $notifications[$i]['full_date']   = $notification['date'];
                $i++;
            } //$notifications as $notification
            echo json_encode($notifications);
            die;
        }
    }

    public function test()
    {
        echo "<pre>";
        $table_data = array(
            _l('staff_dt_name'),
            _l('staff_dt_email'),
            _l('staff_dt_reporting_manager'),
            _l('staff_dt_last_Login'),
            _l('staff_dt_active'),
        );
        $custom_fields = get_custom_fields('staff',array('show_on_table'=>1));
        foreach($custom_fields as $field){
            array_push($table_data,$field['name']);
        }
		render_datatable($table_data,'staff');
        $this->app->get_table_data('staff');
        echo "</pre>";
    }
	
	public function change_active(){
		
		$result = $this->staff_model->update_sataus($_POST['id'], $_POST['status']);
		echo $result;
	}
	
	public function member_inactive(){
		if ($this->input->is_ajax_request()) { 
            $this->app->get_table_data('staff');
        }
        //$data['staff_members'] = $this->staff_model->get('', ['active'=>1]);
        $data['staff_members'] = $this->staff_model->get('', []);
		//echo $this->db->last_query();exit;
		$data['checkIncative'] = end($this->uri->segment_array());
        $data['title']         = "Inactive Staff Members";
        $this->load->view('admin/staff/manage',$data);
	}
	
	public function getdepartment(){
		$comp_id = $this->input->post('comp_id');
		//print_r($comp_id);exit;
		//$this->db->where('company_id', $comp_id);
		$this->db->where_in('company_id', $comp_id);
		$result = $this->db->get('tbldepartments')->result();
		//echo $this->db->last_query(); exit;
		$html .= "<option value=''>Noting Selected</option>";
		if($result != ""){
			foreach($result as $getdata){
				$html .= "<option value='".$getdata->departmentid."'>".$getdata->name."</option>";
			}
		}else{
			$html .= "<option value=''>Data Not Found</option>";
		}
		echo $html;
	}
}
