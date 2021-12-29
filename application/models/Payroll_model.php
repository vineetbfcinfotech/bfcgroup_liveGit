<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payroll_model
 *
 * @author NaYeM
 */
class Payroll_Model extends CRM_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('roleid', $id);
            $this->db->where('active', 1);
            return $this->db->get('tbl_salary_template')->row();
        }
        
        if(is_admin())
        {
            $this->db->select('tbl_salary_template.*,CONCAT(tblstaff.firstname," ",tblstaff.lastname) as firstname');
            $this->db->from('tbl_salary_template');
            $this->db->where('active', 1);
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade = tblstaff.staffid');
            $query = $this->db->get();
        }
        elseif(is_sub_admin())
        {
            $this->db->select('tbl_salary_template.*,CONCAT(tblstaff.firstname," ",tblstaff.lastname) as firstname');
            $this->db->from('tbl_salary_template');
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade = tblstaff.staffid');
            $this->db->where('active', 1);
            $query = $this->db->get();
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('tbl_salary_template.*,CONCAT(tblstaff.firstname," ",tblstaff.lastname) as firstname');
            $this->db->from('tbl_salary_template');
            $this->db->where("salary_grade IN (".$arr.")",NULL, false);
            $this->db->where('active', 1);
            //$this->db->where_in('salary_grade', $arr);
            $this->db->or_where_in('salary_grade', $useraid);
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade = tblstaff.staffid');
            $query = $this->db->get();
            
        }
        
        

        return $query->result();
    }
    
    
    public function get_incen($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('roleid', $id);
			$this->db->order_by('id', 'DESC');
            return $this->db->get('tbl_salary_template')->row();
        }
        
        

        if(is_admin())
        {  
            $this->db->select('tbl_salary_template.*,CONCAT(tblstaff.firstname," ",tblstaff.lastname) as firstname');
            $this->db->from('tbl_salary_template');
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade = tblstaff.staffid');
            //$this->db->where('tblstaff.department_id', '12');
			$this->db->like('tblstaff.department_id', '12');
            $this->db->where('tblstaff.active', '1');
            $query = $this->db->get();
        }
        elseif(is_sub_admin())
        {  
            $this->db->select('tbl_salary_template.*,CONCAT(tblstaff.firstname," ",tblstaff.lastname) as firstname');
            $this->db->from('tbl_salary_template');
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade = tblstaff.staffid');
            //$this->db->where('tblstaff.department_id', '12');
			$this->db->like('tblstaff.department_id', '12');
            $query = $this->db->get();
        }
        elseif(herapermission())
        {   
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('tbl_salary_template.*,CONCAT(tblstaff.firstname," ",tblstaff.lastname) as firstname');
            $this->db->from('tbl_salary_template');
            $this->db->where("salary_grade IN (".$arr.")",NULL, false);
            //$this->db->where_in('salary_grade', $arr);
            $this->db->or_where_in('salary_grade', $useraid);
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade = tblstaff.staffid');
            //$this->db->where('tblstaff.department_id', '12');
			$this->db->like('tblstaff.department_id', '12');
            $query = $this->db->get();
            
        }
        

        return $query->result();
    }

    public function getemployeesal($staff_id)
    {

        return $this->db->get_where('tbl_salary_template', array('salary_grade' => $staff_id))->row();
    }
    
    public function getpaymenthistory($staff_id)
    {

        return $this->db->get_where('tbl_salary_payment', array('user_id' => $staff_id))->result();
    }

    public function employeedetails($staff_id)
    {

        return $this->db->get_where('tblstaff', array('staffid' => $staff_id))->result();
    }
    
    public function deletesaltem( $sal_id)
       {
        
           $this->db->where('salary_template_id', $sal_id);
           $this->db->delete('tbl_salary_template');

       }
    
    public function _checkRecords($query, $return)
    {
        if ($query->num_rows()) {
            return $query->$return();
        }
    }
    
    public function getincentivehistory($staff_id)
    {

        return $this->db->get_where('tblincentive', array('staff_id' => $staff_id))->result();
    }
    
    public function getincentivehistoryall()
    {
        
        $this->db->select('CONCAT(tbls.firstname," ", tbls.lastname)as staffname, tblincentive.*');
        $this->db->join('tblstaff AS tbls', 'tblincentive.staff_id=tbls.staffid');
        $this->db->order_by('id', ASC);
        return $this->db->get_where('tblincentive', array())->result();
    }
    
    public function deleteincentive($id)
       {
        
           $this->db->where('id', $id);
           $this->db->delete('tblincentive');

       }
       
    function rmconverted($return = "result")
    {
        if(is_admin())
        {
            $this->db->select('tblstaff.staffid as staffid,CONCAT(tblstaff.firstname," ", tblstaff.lastname)as firstname');
            $this->db->group_by('salary_grade');
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade=tblstaff.staffid');
        }
        elseif(is_sub_admin())
        {
            $this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname," ", tblstaff.lastname)as firstname');
            $this->db->group_by('salary_grade');
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade=tblstaff.staffid');
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname," ", tblstaff.lastname)as firstname');
           // $this->db->where_in('salary_grade', $arr);
            $this->db->where("salary_grade IN (".$arr.")",NULL, false);
            $this->db->or_where_in('salary_grade', $useraid);
            $this->db->group_by('salary_grade');
            $this->db->join('tblstaff', 'tbl_salary_template.salary_grade=tblstaff.staffid');
            
        }
        $this->db->like('department_id', 12);
        $this->db->where('active', 1);
        return $this->_checkRecords($this->db->get('tbl_salary_template'), $return);
    }
    
    public function get_bussiness_filter($return = "result")
    {
        $filterrm = $_GET['filterrm'];
        $transctiondatestart = $_GET['datestart'];
        $transctiondateend = $_GET['dateend'];
        
        
        
        if (isset($filterrm)) {

            $this->db->where_in('salary_grade', $filterrm);
        }
        else
        {
            $this->db->where_in('department_id','12');
        }
        

        $this->db->select('tbl_salary_template.*,CONCAT(tblstaff.firstname," ",tblstaff.lastname) as firstname');
        $this->db->from('tbl_salary_template');
        $this->db->join('tblstaff', 'tbl_salary_template.salary_grade = tblstaff.staffid');
        $this->db->where('active',1);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_IncentiveSelect($start, $end){
        $start = $start."-04-01";
        $end = $end."-03-31";
        $this->db->from('tbl_incentive_select_staff');
        $this->db->join('tblstaff', 'tbl_incentive_select_staff.staff_id = tblstaff.staffid');
        $this->db->where('active',1);
        $this->db->where("datestart>=", $start);
        $this->db->where("dateend<=", $end);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_edit_IncentiveSelect($id){
        $this->db->from('tbl_incentive_select_staff');
        $this->db->join('tblstaff', 'tbl_incentive_select_staff.staff_id = tblstaff.staffid');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result();
    }

    public function filter_IncentiveSelect($start, $end){

        $this->db->from('tbl_incentive_select_staff');
        $this->db->join('tblstaff', 'tbl_incentive_select_staff.staff_id = tblstaff.staffid');
        $this->db->where('active',1);
        $this->db->where("datestart>=", $start);
        $this->db->where("dateend<=", $end);
        $query = $this->db->get();
        return $query->result();

    }
}
