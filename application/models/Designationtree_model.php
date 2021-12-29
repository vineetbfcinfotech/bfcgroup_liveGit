<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Designationtree_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getRoles()
    {
        return $this->db->get('tblroles')->result();        
    }
    public function getRoleWiseStaff($role_id)      
    {
        $this->db->where('role_id', $role_id);
        return $this->db->get('tblstaff')->result();
    }
    public function getAllStaff()
    {
       $this->db->select('staffid,CONCAT(firstname," ",lastname) as full_name'); 
       return $this->db->get('tblstaff')->result();
    }
    public function getStaffWithRole($id = '')
    {
        $this->db->select('r.name as role, CONCAT(s.firstname," ",s.lastname) as full_name');
        $this->db->from('tblstaff as s');
        $this->db->join('tblroles as r', 'r.roleid = s.role', 'left');
        if ($id != '') {    
            $this->db->where('s.staffid',$id);
        }
        return $this->db->get()->result();
    }
}
