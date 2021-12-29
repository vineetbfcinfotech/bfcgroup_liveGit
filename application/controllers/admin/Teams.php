<?php
   
   defined('BASEPATH') or exit('No direct script access allowed');
   
   /**
    * Description of attendance
    *
    * @property  session
    * @property  staff_model
    * @property  staff_model
    * @property  db
    * @author NaYeM
    */
   class Teams extends Admin_controller
   {
      public function __construct()
      {
         parent::__construct();
         $this->load->model('departments_model', 'depart_model');
         $this->load->model('teams_model', 'teamsm');
         
      }
      
      /* List all staff roles */
      public function index()
      {
         $data['title'] = _l('rmlist');
         $data['teams'] = $this->teamsm->getAllTeam();
         $this->load->view('admin/teams/manage', $data);
      }
      
      public function add_new()
      {
         $data['title'] = _l('create_new_team');
         if ( $this->input->post() ) {
            if ( empty($this->input->post('id')) ) {
               $insert_data['team_name'] = $this->input->post('team_name');
               $insert_data['department_id'] = $this->input->post('department_id');
               $insert_data['company_id'] = $this->input->post('company_id');
               $insert_data['staffid'] = $this->input->post('staffid');
               $role_ids = $this->input->post('role_ids');
               $rm_ids = $this->input->post('rm_ids');
               $total_count = count($role_ids);
               //$insert_data['role_ids']=   implode($this->input->post('role_ids'),',');
               //$insert_data['rm_ids']=     implode($this->input->post('rm_ids'),',');
               if ( $id = $this->teamsm->add_team($insert_data) ) {
                  for ($i = 0; $i < $total_count; $i++) {
                     $teamdeptrm_id = $this->input->post('teamdeptrm_id');
                     $inserdata['team_id'] = $id;
                     $inserdata['dept_id'] = $insert_data['department_id'];
                     $inserdata['role_id'] = $role_ids[$i];
                     $inserdata['rm_role_id'] = $rm_ids[$i];
                     $this->teamsm->teamdeptrmrelation($inserdata, $teamdeptrm_id);
                  }
                  set_alert('success', _l('added_successfully', _l('teams')));
                  return redirect(admin_url('teams'));
               }
            }
         }
         $data['departments'] = $this->teamsm->get_departments('', 'departmentid,name', 'result_array');
         $data['roles'] = $this->teamsm->get_roles('', 'roleid,name', 'result_array');
         $data['staff_members'] = $this->staff_model->get('', ['active' => 1, 'bio_id !=' => null]);
         $this->load->view('admin/teams/add_new', $data, FALSE);
      }
      
      /**
       * @param $id
       */
      public function edit( $id )
      {
         $team = $this->teamsm->getTeamById($id);
         if ( !empty($team) ) {
            $data['team'] = $team;
            $data['title'] = 'Edit : ' . $team->team_name;
            if ( $this->input->post() ) {
               $id = $this->input->post('team_id');
               $update_data['team_name'] = $this->input->post('team_name');
               $update_data['department_id'] = $this->input->post('department_id');
               $update_data['staffid'] = $this->input->post('staffid');
               
			   
               $this->db->update('tblteams', $update_data, array('id' => $id));
               
               $role_ids = $this->input->post('role_ids');
               $rm_ids = $this->input->post('rm_ids');
               $teamDeptIds = $this->input->post('team_dept_id');
               $total_count = count($role_ids);
               for ($i = 0; $i < $total_count; $i++) {
                  $teamDeptData = array('team_id' => $id, 'dept_id' => $update_data['department_id'], 'role_id' => $role_ids[$i], 'rm_role_id' => $rm_ids[$i]);
                  if ( !empty($teamDeptIds[$i]) ) {
                     $this->db->update('tblteamdeptrmrelation', $teamDeptData, array('id' => $teamDeptIds[$i]));
                  } else {
                     $this->db->insert('tblteamdeptrmrelation', $teamDeptData);
                  }
               }
               if (!empty($this->input->post('deleted_team_dept_ids'))){
                  $ids= $this->input->post('deleted_team_dept_ids');
                  $this->db->query("DELETE FROM `tblteamdeptrmrelation` WHERE `id` IN('$ids')");
               }
               set_alert('success', _l('success', _l('updated_successfully')));
               redirect(admin_url('teams'));
            }
            $data['departments'] = $this->teamsm->get_departments('', 'departmentid,name', 'result_array');
            $data['roles'] = $this->teamsm->get_roles('', 'roleid,name', 'result_array');
            $data['staff_members'] = $this->staff_model->get('', ['active' => 1, 'bio_id !=' => null]);
            $this->load->view('admin/teams/edit', $data, FALSE);
         } else {
            set_alert('warning', _l('invalid', _l('invalid_request')));
            redirect(admin_url('teams'));
         }
      }
      
      /* Add new role or edit existing one */
      public function role( $id = '' )
      {
         if ( !has_permission('roles', '', 'view') ) {
            access_denied('roles');
         }
         if ( $this->input->post() ) {
            if ( $id == '' ) {
               if ( !has_permission('roles', '', 'create') ) {
                  access_denied('roles');
               }
               $id = $this->roles_model->add($this->input->post());
               if ( $id ) {
                  set_alert('success', _l('added_successfully', _l('role')));
                  redirect(admin_url('roles/role/' . $id));
               }
            } else {
               if ( !has_permission('roles', '', 'edit') ) {
                  access_denied('roles');
               }
               $success = $this->roles_model->update($this->input->post(), $id);
               if ( $success ) {
                  set_alert('success', _l('updated_successfully', _l('role')));
               }
               redirect(admin_url('roles/role/' . $id));
            }
         }
         if ( $id == '' ) {
            $title = _l('add_new', _l('role_lowercase'));
         } else {
            $data['role_permissions'] = $this->roles_model->get_role_permissions($id);
            $role = $this->roles_model->get($id);
            $data['role'] = $role;
            $title = _l('edit', _l('role_lowercase')) . ' ' . $role->name;
         }
         $data['permissions'] = $this->roles_model->get_permissions();
         $data['title'] = $title;
         $this->load->view('admin/roles/role', $data);
      }
      
      /* Delete staff role from database */
      public function delete( $id )
      {
         if ( !has_permission('roles', '', 'delete') ) {
            access_denied('roles');
         }
         if ( !$id ) {
            redirect(admin_url('roles'));
         }
         $response = $this->roles_model->delete($id);
         if ( is_array($response) && isset($response['referenced']) ) {
            set_alert('warning', _l('is_referenced', _l('role_lowercase')));
         } elseif ( $response == true ) {
            set_alert('success', _l('deleted', _l('role')));
         } else {
            set_alert('warning', _l('problem_deleting', _l('role_lowercase')));
         }
         redirect(admin_url('roles'));
      }
      
      public function getTeamListByDepartmentId( $dept_id = '' )
      {
         $dept_id = ($dept_id) ? $dept_id : $_REQUEST['dept_id'];
         $teams = $this->teamsm->getTeamByDeptId($dept_id);
         
         if ( !empty($teams) ) {
            $data['team'][0] = "<option value='0'>--SELECT--</option>";
			$i = 1;
            foreach ($teams as $team) {
               $data['team'][$i] = '<option value="'.$team->id.'">'.$team->team_name.'</option>';
			   $i++;
            }
         }
		 
		 $team_id = "";
		 $role = "all";
		 $roles = $this->teamsm->getRolesByDeptIdWithTeamId1($team_id, $dept_id, $role);
		 //echo $this->db->last_query();exit;
         if ( !empty($roles) ) {
			$i = 1;
            $data['role'][0] = "<option value='0'>--SELECT--</option>";
            foreach ($roles as $role) {
               $data['role'][$i] = '<option value="'.$role->role_id.'">'.$role->name.'</option>';
			   $i++;
            }
         }
		 print_r(json_encode($data));
      }
      
      // show Staff
      public function show_team_member(){
         $team_id = $_REQUEST['team_id'];
         $dept_id = $_REQUEST['dept_id'];
		 
		 $this->db->select("*");
		 $this->db->from("tblteams");
		 $this->db->where("id", $team_id);
		 $datateam = $this->db->get()->row();
		 //print_r($this->db->last_query());exit;
		 $staffid=is_staffId($datateam->staffid);
		 $staffid = explode(",",$staffid);
		 //print_r($arr);exit;
         /* $query =  $this->db->where('departmentid', $dept_id)->where('team_id', $team_id)->get('tblstaffdepartments')->result();
         $team =  $this->db->where('department_id', $dept_id)->where('id', $team_id)->get('tblteams')->result();
         $team_lead = $team[0]->staffid;
         $staffid = array();
         if($query){
            foreach ($query as $value) {
               $staffid[] = $value->staffid;
               if($team_lead !=$value->staffid){ 
                  $teamData = $this->db->where('department_id', $dept_id)->where('staffid', $value->staffid)->get('tblteams')->result();
              
                  $teamquery =  $this->db->where('departmentid', $dept_id)->where('team_id', $teamData[0]->id)->get('tblstaffdepartments')->result();
                  if($teamquery){
                     foreach ($teamquery as $key) {
                        $staffid[] = $key->staffid;
                     }
                  }
               }
            }
         } */
     
         if($staffid){
            $staff_id = array_unique($staffid);
         }else{
            $staff_id = array();
         }
         //print_r($staff_id);exit;
         if (!empty($staff_id)) {
            echo "<option value='0'>--SELECT--</option>";
            foreach ($staff_id as $staff) {
               $staffData = $this->db->where('staffid',$staff)->like('department_id',$dept_id)->where('active',1)
			   ->get('tblstaff')->result();
              //echo $this->db->last_query();exit;
              if($staffData){
               $staff_name = $staffData[0]->firstname.' '.$staffData[0]->lastname;
               echo sprintf('<option value="%s">%s</option>', $staffData[0]->staffid,$staff_name);
              }
               
            }
         }
         
         exit();   
      }


      public function getRoleListByTeamId( $team_id = '', $dept_id = '' )
      {
         $team_id = ($team_id) ? $dept_id : $_REQUEST['team_id'];
         $dept_id = ($dept_id) ? $dept_id : $_REQUEST['dept_id'];
         $roles = $this->teamsm->getRolesByDeptIdWithTeamId($team_id,$dept_id);
         foreach ($roles as $value) {
           $arr[] = $value->role_id;
         }
         $designation = array_unique($arr);
         if ( !empty($designation) ) {
            echo "<option value='0'>--SELECT--</option>";
            foreach ($designation as $role) {
               $roleData = $this->db->where('roleid', $role)->get('tblroles')->result();
               echo sprintf('<option value="%s">%s</option>', $roleData[0]->roleid, $roleData[0]->name);
            }
         }
      }
      
      public function deleteteam( $id )
      {
         
         if ( !$id ) {
            redirect(admin_url('teams'));
         }
         
         
         $response = $this->teamsm->deleteteam($id);
         if ( $response == true ) {
            set_alert('success', _l('deleted', _l('team')));
         } else {
            set_alert('warning', _l('problem_deleting', _l('role_lowercase')));
         }
         redirect(admin_url('teams'));
      }
      
      public function active( $type, $id, $status )
      {
         if ( $this->input->is_ajax_request() ) {
            switch ($type) {
               case 'teams':
                  $this->teamsm->change_status('tblteams', $id, $status);
                  break;
               default:
                  # code...
                  break;
            }
         }
      }
      
      public function getRmListByTeamIdDeptIdRmRoleId( $role_id = '', $dept_id = '', $team_id = '' )
      {
         $team_id = ($team_id) ? $dept_id : $_REQUEST['team_id'];
         $dept_id = ($dept_id) ? $dept_id : $_REQUEST['dept_id'];
         $role_id = ($role_id) ? $role_id : $_REQUEST['role_id'];
         $rmstaff = $this->teamsm->getRmlistByDeptIdWithTeamIdRoleId($role_id, $team_id, $dept_id); 
         /*echo $this->db->last_query();
         exit();*/
         if ( !empty($rmstaff) ) {
            echo "<option value='0'>--SELECT--</option>";
            foreach ($rmstaff as $rm) {
               echo sprintf('<option value="%s">%s</option>', $rm['id'], $rm['full_name']);
            }
         }
      }

      public function getVteam( $role_id = '', $dept_id = '', $team_id = '' )
      {
         $team_id = ($team_id) ? $dept_id : $_REQUEST['team_id'];
         $dept_id = ($dept_id) ? $dept_id : $_REQUEST['dept_id'];
         $role_id = ($role_id) ? $role_id : $_REQUEST['role_id'];
         
         $this->db->select('staffid as id,CONCAT(firstname," ",lastname) as full_name');
         $this->db->where('active', 1);
         $this->db->where('admin', 1);
         $admin_member = $this->db->get('tblstaff')->result();
          foreach ($admin_member as $ad) {
              $arr[] = $ad->id;
               
         }

         $this->db->select('staffid as id,member_team,CONCAT(firstname," ",lastname) as full_name');
         $this->db->where('active', 1);
         $member = $this->db->get('tblstaff')->result();

         foreach ($team_id as $value) {
            foreach ($member as $mm) {
               $sqlvalue = "$mm->member_team";
               $hiddenProducts = explode(',',$sqlvalue);
               if (in_array($value,$hiddenProducts))
               {
                  $arr[] = $mm->id;
               }
            }
         }

        
         $rmstaff = array_unique($arr);
         if ( !empty($rmstaff) ) {
            echo "<option value='0'>--SELECT--</option>";
            foreach ($rmstaff as $rm) {
               $this->db->select('staffid as id,member_team,CONCAT(firstname," ",lastname) as full_name');
               $this->db->where('active', 1);
               $this->db->where('staffid', $rm);
               $staffData = $this->db->get('tblstaff')->result();
               echo sprintf('<option value="%s">%s</option>', $staffData[0]->id, $staffData[0]->full_name);
            }
         }
      }
      
      public function update_team( $id = '' )
      {
         
         $data['title'] = _l('update_new_team');
         if ( $this->input->post() ) {
            if ( empty($this->input->post('id')) ) {
               $insert_data['team_name'] = $this->input->post('team_name');
               $insert_data['department_id'] = $this->input->post('department_id');
               $role_ids = $this->input->post('role_ids');
               $rm_ids = $this->input->post('rm_ids');
               $total_count = count($role_ids);
               //$insert_data['role_ids']=   implode($this->input->post('role_ids'),',');
               //$insert_data['rm_ids']=     implode($this->input->post('rm_ids'),',');
               if ( $id = $this->teamsm->add_team($insert_data) ) {
                  for ($i = 0; $i < $total_count; $i++) {
                     $teamdeptrm_id = $this->input->post('teamdeptrm_id');
                     $inserdata['team_id'] = $id;
                     $inserdata['dept_id'] = $insert_data['department_id'];
                     $inserdata['role_id'] = $role_ids[$i];
                     $inserdata['rm_role_id'] = $rm_ids[$i];
                     $this->teamsm->teamdeptrmrelation($inserdata, $teamdeptrm_id);
                  }
                  set_alert('success', _l('added_successfully', _l('teams')));
                  return redirect(admin_url('teams'));
               }
            }
         }
         
         $data['departments'] = $this->teamsm->get_departments('', 'departmentid,name', 'result_array');
         $data['roles'] = $this->teamsm->get_roles('', 'roleid,name', 'result_array');
         $this->load->view('admin/teams/add_new', $data, FALSE);
      }
	  
	  public function view($id){
		$data['team_members'] = $this->teamsm->get_team_member($id);
		$data['team_Name'] = $this->teamsm->get_team_name($id);
		$this->load->view('admin/teams/view_team', $data, FALSE);
		//print_r($data['team_members']);
	  }
	  
	  public function getdepartment(){
		$comp_id = $this->input->post('comp_id');
		
		$this->db->where('company_id', $comp_id);
		$result = $this->db->get('tbldepartments')->result();
		if(!empty($result)){
			foreach($result as $totaldata){
				$html .= "<option value='".$totaldata->departmentid."'>";
				$html .= $totaldata->name;
				$html .= "</option>";
			}
		}else{
			$html = "<option>No data found</option>";
		}
		
		/* $this->db->where('company', $comp_id);
		$this->db->where('active', 1);
		$result2 = $this->db->get('tblstaff')->result();
		if(!empty($result2)){
			foreach($result2 as $tstaffdata){
				$html2 .= "<option value='".$tstaffdata->staffid."'>";
				$html2 .= $tstaffdata->firstname." ".$tstaffdata->lastname;
				$html2 .= "</option>";
			}
		}else{
			$html2 = "<option>No data found</option>";
		} */
		
		//$this->db->where('company', $comp_id);
		$this->db->like('company', $comp_id);
		$this->db->where('active', 1);
		$result3 = $this->db->get('tblstaff')->result();
		if(!empty($result3)){
			foreach($result3 as $tstaffdata){
				$html2 .= "<option value='".$tstaffdata->staffid."'>";
				$html2 .= $tstaffdata->firstname." ".$tstaffdata->lastname;
				$html2 .= "</option>";
			}
		}else{
			$html2 = "<option>No data found</option>";
		}
		
		$data["department"] = $html;
		$data["staff"] = $html2;
		print_r(json_encode($data));
	  }
	  
	  public function getteam(){
		$comp_id = $this->input->post('comp_id');
		
		$this->db->select('tt.*,td.name as department_name,CONCAT(ts.firstname," ",ts.lastname) as team_head');
		$this->db->join('tbldepartments td', 'td.departmentid=tt.department_id');
		$this->db->join('tblstaff ts', 'ts.staffid=tt.staffid');
		//$this->db->join('tblroles tr','tr.roleid=tt.department_id');
		$this->db->where('tt.company_id', $comp_id);
		$query = $this->db->get('tblteams tt');
		$result = $query->result();
		
		$this->printTeamData($result);
	  }
	  private function printTeamData($result){
		  ?>
		  <table class="table dt-table scroll-responsive">
			   <thead>
				   <th><?php echo _l('Sr.no'); ?></th>
				   <th><?php echo _l('dt_team_name'); ?></th>
				   <th><?php echo _l('dt_department_name'); ?></th>
				   <th><?php echo "Team Head"; ?></th>
				   <th><?php echo _l('product_dt_active'); ?></th>

				   <th><?php echo _l('options'); ?></th>
			   </thead>
			   <tbody>
			   <?php if ( !empty($result) ) {
				  foreach ($result as $team) { ?>
					  <tr>
						  <td><?= @++$i; ?></td>
						  <td><?= $team->team_name; ?></td>
						  <td><?= $team->department_name; ?></td>
						  <td><?= $team->team_head; ?></td>
						  <td>
							  <div class="onoffswitch" data-toggle="tooltip"
								   data-title="<?= $team->team_name; ?>">
								  <input type="checkbox"
										 data-switch-url="<?= admin_url('teams/active/teams') ?>"
										 name="onoffswitch" class="onoffswitch-checkbox"
										 id="<?= $team->id ?>"
										 data-id="<?= $team->id ?>" <?= $team->Active == 1 ? 'checked' : '' ?>>
								  <label class="onoffswitch-label" for="<?= $team->id; ?>"></label>
							  </div>
						  </td>
						  <td>
							<a href="<?php echo admin_url('teams/view/' . $team->id); ?>"
								 class="btn btn-info btn-icon" title="View Team Member" ><i
										  class="fa fa-eye"></i></a>
						  
							  <a href="<?php echo admin_url('teams/edit/' . $team->id); ?>"
								 class="btn btn-info btn-icon"><i
										  class="fa fa-pencil"></i></a>
							  <a href="<?php echo admin_url('teams/deleteteam/' . $team->id); ?>"
								 class="btn btn-danger btn-icon _delete"><i
										  class="fa fa-remove"></i></a>
						  </td>
					  </tr>
				  <?php }
			   } ?>
			   </tbody>
		   </table>
		  <?php
	  }
   }
