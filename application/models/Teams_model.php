<?php
   
   defined('BASEPATH') or exit('No direct script access allowed');
   
   class Teams_model extends CRM_Model
   {
      public function __construct()
      {
         parent::__construct();
      }
      
      /**
       * @param integer ID (optional)
       * @param boolean (optional)
       * @return mixed
       * Get department object based on passed id if not passed id return array of all departments
       * Second parameter is to check if the request is coming from clientarea, so if any departments are hidden from client to exclude
       */
      public function get_departments( $id = '', $select = '*', $return = "result" )
      {
         if ( is_numeric($id) ) {
            $this->db->where('departmentid', $id);
            return $this->db->get('tbldepartments')->row();
         }
         $this->db->select($select);
         $this->db->where(array('hidefromclient' => 0));
         $this->db->from('tbldepartments');
         return $this->_checkRecords($this->db->get(), $return);
      }
      
      public function _checkRecords( $query, $return )
      {
         if ( $query->num_rows() ) {
            return $query->$return();
         }
      }
      
      public function get_roles( $id = '', $select = '*', $return = "result" )
      {
         if ( is_numeric($id) ) {
            $this->db->where('roleid', $id);
            return $this->db->get('tblroles')->row();
         }
         $this->db->select($select);
         $this->db->from('tblroles');
         return $this->_checkRecords($this->db->get(), $return);
      }
      
      /**
       * @param array $_POST data
       * @return integer
       * Add new department
       */
      public function add( $data )
      {
         if ( isset($data['hidefromclient']) ) {
            $data['hidefromclient'] = 1;
         } else {
            $data['hidefromclient'] = 0;
         }
         
         if ( !empty($data['password']) ) {
            $data['password'] = $this->encryption->encrypt($data['password']);
         }
         
         if ( !isset($data['encryption']) ) {
            $data['encryption'] = '';
         }
         
         if ( !isset($data['delete_after_import']) ) {
            $data['delete_after_import'] = 0;
         } else {
            $data['delete_after_import'] = 1;
         }
         
         $data = do_action('before_department_added', $data);
         $this->db->insert('tbldepartments', $data);
         $insert_id = $this->db->insert_id();
         if ( $insert_id ) {
            do_action('after_department_added', $insert_id);
            logActivity('New Department Added [' . $data['name'] . ', ID: ' . $insert_id . ']');
         }
         
         return $insert_id;
      }
      
      /**
       * @param array $_POST data
       * @param integer ID
       * @return boolean
       * Update department to database
       */
      public function update( $data, $id )
      {
         $dep_original = $this->get($id);
         if ( !$dep_original ) {
            return false;
         }
         $hook_data['data'] = $data;
         $hook_data['id'] = $id;
         $hook_data = do_action('before_department_updated', $hook_data);
         $data = $hook_data['data'];
         $id = $hook_data['id'];
         
         if ( !isset($data['encryption']) ) {
            $data['encryption'] = '';
         }
         
         if ( !isset($data['delete_after_import']) ) {
            $data['delete_after_import'] = 0;
         } else {
            $data['delete_after_import'] = 1;
         }
         
         if ( $data['email'] == '' ) {
            $data['email'] = null;
         }
         if ( isset($data['hidefromclient']) ) {
            $data['hidefromclient'] = 1;
         } else {
            $data['hidefromclient'] = 0;
         }
         // Check if not empty $data['password']
         // Get original
         // Decrypt original
         // Compare with $data['password']
         // If equal unset
         // If not encrypt and save
         if ( !empty($data['password']) ) {
            $or_decrypted = $this->encryption->decrypt($dep_original->password);
            if ( $or_decrypted == $data['password'] ) {
               unset($data['password']);
            } else {
               $data['password'] = $this->encryption->encrypt($data['password']);
            }
         }
         
         $this->db->where('departmentid', $id);
         $this->db->update('tbldepartments', $data);
         if ( $this->db->affected_rows() > 0 ) {
            logActivity('Department Updated [Name: ' . $data['name'] . ', ID: ' . $id . ']');
            
            return true;
         }
         
         return false;
      }
      
      /**
       * @param integer ID
       * @return mixed
       * Delete department from database, if used return array with key referenced
       */
      public function delete( $id )
      {
         $id = do_action('before_delete_department', $id);
         $current = $this->get($id);
         if ( is_reference_in_table('department', 'tbltickets', $id) ) {
            return [
               'referenced' => true,
            ];
         }
         do_action('before_department_deleted', $id);
         $this->db->where('departmentid', $id);
         $this->db->delete('tbldepartments');
         if ( $this->db->affected_rows() > 0 ) {
            logActivity('Department Deleted [ID: ' . $id . ']');
            
            return true;
         }
         
         return false;
      }
      
      /**
       * @param integer ID (option)
       * @param boolean (optional)
       * @return mixed
       * Get departments where staff belongs
       * If $onlyids passed return only departmentsID (simple array) if not returns array of all departments
       */
      
      public function deleteteam( $id )
      {
         
         $this->db->where('id', $id);
         $this->db->delete('tblteams');
         return true;
      }
      
      public function get_staff_departments( $userid = false, $onlyids = false )
      {
         if ( $userid == false ) {
            $userid = get_staff_user_id();
         }
         if ( $onlyids == false ) {
            $this->db->select();
         } else {
            $this->db->select('tblstaffdepartments.departmentid');
         }
         $this->db->from('tblstaffdepartments');
         $this->db->join('tbldepartments', 'tblstaffdepartments.departmentid = tbldepartments.departmentid', 'left');
         $this->db->where('staffid', $userid);
         $departments = $this->db->get()->result_array();
         if ( $onlyids == true ) {
            $departmentsid = [];
            foreach ($departments as $department) {
               array_push($departmentsid, $department['departmentid']);
            }
            
            return $departmentsid;
         }
         return $departments;
      }
      
      public function add_team( $data )
      {
         $this->db->insert('tblteams', $data);
         return $this->db->insert_id();
      }
      
      public function teamdeptrmrelation( $data, $id )
      {
         if ( is_numeric($id) ) {
            $this->db->update('tblteamdeptrmrelation', $data, array('id' => $id));
         } else {
            $this->db->insert('tblteamdeptrmrelation', $data);
         }
      }
      
      public function getAllTeam()
      {
         $this->db->select('tt.*,td.name as department_name,CONCAT(ts.firstname," ",ts.lastname) as team_head');
         $this->db->join('tbldepartments td', 'td.departmentid=tt.department_id');
         $this->db->join('tblstaff ts', 'ts.staffid=tt.staffid');
         //$this->db->join('tblroles tr','tr.roleid=tt.department_id');
		 $this->db->where('tt.company_id', '1');
         $query = $this->db->get('tblteams tt');
		 //echo $this->db->last_query();exit;
         if ( $query->num_rows() ) {
            return $query->result();
         }
         return false;
      }
      
      public function getTeamById( $team_id )
      {
         $this->db->select('tt.id AS team_id,tt.team_name,tt.company_id,tt.department_id,tt.Active,tt.staffid');
         $this->db->join('tbldepartments td', 'td.departmentid=tt.department_id');
         //$this->db->join('tblroles tr','tr.roleid=tt.department_id');
         $this->db->where(array('tt.id' => $team_id));
         $query = $this->db->get('tblteams tt');
         if ( $query->num_rows() ) {
            return $query->row();
         }
         return false;
      }
      
      public function getTeamDepartmentRolesByTeamId( $team_id, $dept_id )
      {
         $query = $this->db->get_where('tblteamdeptrmrelation', array('team_id' => $team_id, 'dept_id' => $dept_id));
         if ( $query->num_rows() ) {
            return $query->result();
         }
      }
      
      public function getRoleNameByIds( $ids )
      {
         $this->db->select('group_concat(name) AS role_name');
         $this->db->where('roleid', $ids, FALSE, FALSE);
         $query = $this->db->get('tblroles');
         if ( $query->num_rows() ) {
            return $query->row_array();
         }
         return false;
      }
      
      public function getTeamByDeptId($id)
      {
          $this->db->select('id,team_name');
          //$this->db->where('department_id', $id);
		      $this->db->where_in('department_id', $id);
          $query = $this->db->get('tblteams');
          if ( $query->num_rows() ) {
            // $query->result_array();
            return $query->result();
          }
         return false;
      }
      
      public function getRolesByDeptIdWithTeamId($team_id,$dept_id )
      {
        $this->db->select('teamrm.role_id,role.name as name');
        /*$this->db->where('teamrm.dept_id', $dept_id);
        $this->db->where('teamrm.team_id', $team_id);*/
		    $this->db->where_in('teamrm.dept_id', $dept_id);
        $this->db->where_in('teamrm.team_id', $team_id);
        $this->db->join('tblroles role', 'role.roleid=teamrm.role_id');
        //$this->db->group_by('teamrm.dept_id');
        $query = $this->db->get('tblteamdeptrmrelation teamrm');
        if ( $query->num_rows() ) {
          return $query->result();
        }
        return false;
      }
	  
	  public function getRolesByDeptIdWithTeamId1( $team_id='', $dept_id, $role='' )
      {
         if($role == "all"){
             $this->db->select('teamrm.roleid as role_id,teamrm.name as name');
             /* $this->db->where('teamrm.dept_id', $dept_id);
             $this->db->where('teamrm.team_id', $team_id); */
    		     //$this->db->where_in('teamrm.dept_id', $dept_id);
             //$this->db->where_in('teamrm.team_id', $team_id);
             //$this->db->join('tblroles role', 'role.roleid=teamrm.role_id');
             $query = $this->db->get('tblroles teamrm');
         }else{
             $this->db->select('teamrm.role_id,role.name as name');
             /* $this->db->where('teamrm.dept_id', $dept_id);
             $this->db->where('teamrm.team_id', $team_id); */
    		 $this->db->where_in('teamrm.dept_id', $dept_id);
             //$this->db->where_in('teamrm.team_id', $team_id);
             $this->db->join('tblroles role', 'role.roleid=teamrm.role_id');
             $query = $this->db->get('tblteamdeptrmrelation teamrm');
         }
         
         if ( $query->num_rows() ) {
            //return $query->result_array();
            return $query->result();
         }
         return false;
      }
      
      public function getRmlistByDeptIdWithTeamIdRoleId($role_id,$team_id,$dept_id)
      {
        
    		 $this->db->select('*');
    		 $this->db->where_in('dept_id', $dept_id);
         $this->db->where_in('team_id', $team_id);
         $rm = $this->db->get('tblteamdeptrmrelation')->row();
		 
         if ( !empty($rm) ) {
            $this->db->select("GROUP_CONCAT(DISTINCT staffid) as staffid");
            $this->db->where_in('departmentid', $dept_id);
      			$this->db->where_in('team_id', $team_id);
      			$this->db->where_in('role_id', $role_id);
      			$this->db->where_in('rm_id', $rm->rm_role_id);
            $query = $this->db->get('tblstaffdepartments');
			
            if ( $query->num_rows() ) {
               $this->db->select('staffid as id,role,CONCAT(firstname," ",lastname) as full_name');
               $this->db->where_in('staffid', $query->row()->staffid, FALSE, FALSE);
               $this->db->where('active', 1);
               $query1 = $this->db->get('tblstaff'); 
			   
               if ($query1->num_rows()){
                  return $query1->result_array(); 
               }
            }
         }
         
         return false;
      }
      
      public function change_status( $table, $id, $status )
      {
         $this->db->where('id', $id);
         $this->db->update($table, array('Active' => $status));
         if ( $this->db->affected_rows() > 0 ) {
            return true;
         }
         
         return false;
      }
      
      public function getStaffTeamDetails( $staffid )
      {
         return $this->db->get_where('tblstaffdepartments', array('staffid' => $staffid))->row();
      }
      
      function rmuserlist()
      {
         $this->db->select('GROUP_CONCAT(DISTINCT rm_role_id) AS role_ids');
         $query = $this->db->get('tblteamdeptrmrelation');
         if ( $query->num_rows() ) {
            $this->db->select(array('staffid AS id', 'CONCAT(firstname," ",lastname) AS name'));
            $this->db->where_in('role', $query->row()->role_ids, FALSE);
            return $this->db->get('tblstaff')->result();
         }
      }
	  
	  function get_team_member($id){
		$this->db->select('tblteams.*,tblteamdeptrmrelation.team_id, CONCAT(tblstaff.firstname," ",tblstaff.lastname) as full_name, tblstaff.email,tblstaff.phonenumber');
		$this->db->where('tblteams.id', $id);
		$this->db->where('tblstaff.active', 1);
		$this->db->from('tblteams');
		$this->db->join('tblteamdeptrmrelation', 'tblteams.id = tblteamdeptrmrelation.team_id'); 
		$this->db->join('tblstaff', 'tblstaff.role = tblteamdeptrmrelation.role_id'); 
		$query = $this->db->get();
		return $query->result();
		//echo $this->db->last_query();exit;
	  }
	  
	  public function get_team_name($id){
		$this->db->select('team_name');
		$this->db->from('tblteams');
		$this->db->where('tblteams.id', $id);
		$query = $this->db->get();
		return $query->row();
	  }
      
   }
