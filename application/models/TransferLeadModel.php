<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TransferLeadModel extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        echo "model test    ";
    }
    // get data for acquisition data

    public function TotLeadData($limit, $start, $pc_name = '', $from_date = '', $to_date = '',$search_text='',$search_cat='')
    {
        $staff_id  = $this->session->userdata('staff_user_id');
        $role = get_imp_role();
        $arr = herapermission();
        $arr = explode(',', $arr);
        $data_a = $this->db->get_where('tblstaff', array('role_id' => 64))->result();
        $data_a = explode(',', $data_a);
        $this->load->database();
        $this->db->select('*');
        $this->db->limit($limit, $start);
        $from_date = (isset($from_date) && trim($from_date) != '') ? date("Y-m-d", strtotime(trim($from_date))) : ''; //$_POST['start_date'];die;
        $to_date = (isset($to_date) && trim($to_date) != '') ? date("Y-m-d", strtotime(trim($to_date))) : ''; //$_POST['end_date'];

        if($search_text != ''){
            $this->db->group_start();
            $this->db->like('lead_author_name', $search_text);
            $this->db->or_like('phonenumber', $search_text);
            $this->db->or_like('lead_author_msstatus', $search_text);
            $this->db->or_like('email', $search_text);
            $this->db->or_like('lead_created_date', $search_text);
            $this->db->or_like('lead_booktitle', $search_text);
            $this->db->or_like('otherphonenumber', $search_text);
            $this->db->group_end();
        }
        if ($search_cat != '') {
            if ($search_cat == 'no_category') {
                $search_cat = 'null';
                $this->db->where_in('lead_category_id', $search_cat);
            } else {
                $this->db->where_in('lead_category_id', $search_cat);
            }
        }
        if ((is_admin()) ||  ($staff_id == 34) || ($staff_id == 28) || ($role == 92) || ($role == 78)) {
            if ($from_date != '') {
                $this->db->where('lead_acquired_date >=', $from_date);
            } else {
                $from_date = date('Y-m-01');
                $this->db->where('lead_acquired_date >=', $from_date);
            }
            if ($to_date != "") {
                $this->db->where('lead_acquired_date <=', $to_date);
            } else {
                $to_date = date('Y-m-d');
                $this->db->where('lead_acquired_date <=', $to_date);
            }
            if ($pc_name != '') {
                if ($pc_name != '0') {
                    $this->db->where_in('assigned', $pc_name);
                }
            } else {
                // if ($role == 92) {
                //     $this->db->where_in('assigned', $arr);
                // } else {
                //     $this->db->where_in('assigned', $data_a);
                // }
            }
            $this->db->order_by('lead_acquired_date', 'DESC');
            // $this->db->where('lead_category_id', 39);
            // $this->db->where('createPackEmailStatus', 1);
            // $this->db->where('lead_payment_status', 1);
            $this->db->select('CONCAT(tbll.firstname, ' . " " . ', tbll.lastname) AS fullname,tblleads.*');
            $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
        } else {
            if ($from_date != '') {
                $this->db->where('lead_acquired_date >=', $from_date);
            }
            if ($to_date != "") {
                $this->db->where('lead_acquired_date <=', $to_date);
            }
            // $this->db->where('lead_category_id ', 39);
            $this->db->order_by('lead_acquired_date', 'DESC');
            $this->db->where('assigned', $staff_id);
            // $this->db->where('createPackEmailStatus', 1);
            // $this->db->where('lead_payment_status', 1);
            $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
        }
        $result  = $this->db->get('tblleads')->result();

        // print_r($this->db->last_query());
        // die(); 
        $this->db->select('*');
        $this->db->limit($limit, $start);
        $from_date = (isset($from_date) && trim($from_date) != '') ? date("Y-m-d", strtotime(trim($from_date))) : ''; //$_POST['start_date'];die;
        $to_date = (isset($to_date) && trim($to_date) != '') ? date("Y-m-d", strtotime(trim($to_date))) : ''; //$_POST['end_date'];

        if ($search_text != '') {
            $this->db->group_start();
            $this->db->like('lead_author_name', $search_text);
            $this->db->or_like('phonenumber', $search_text);
            $this->db->or_like('lead_author_msstatus', $search_text);
            $this->db->or_like('email', $search_text);
            $this->db->or_like('lead_created_date', $search_text);
            $this->db->or_like('lead_booktitle', $search_text);
            $this->db->or_like('otherphonenumber', $search_text);
            $this->db->group_end();
        }
        if ($search_cat != '') {
            $this->db->group_start();
            if ($search_cat == 'no_category') {
                $search_cat = 'null';
                $this->db->where_in('lead_category_id', $search_cat);
            } else {
                $this->db->where_in('lead_category_id', $search_cat);
            }
            $this->db->group_end();
        }
        if ((is_admin()) ||  ($staff_id == 34) || ($staff_id == 28) || ($role == 92)) {
            if ($from_date != '') {
                $this->db->where('lead_acquired_date >=', $from_date);
            } else {
                $from_date = date('Y-m-01');
                $this->db->where('lead_acquired_date >=', $from_date);
            }
            if ($to_date != "") {
                $this->db->where('lead_acquired_date <=', $to_date);
            } else {
                $to_date = date('Y-m-d');
                $this->db->where('lead_acquired_date <=', $to_date);
            }
            if ($pc_name != '') {
                if ($pc_name != '0') {
                    $this->db->where_in('assigned', $pc_name);
                }
            } else {
                // if ($role == 92) {
                //     $this->db->where_in('assigned', $arr);
                // } else {
                //     $this->db->where_in('assigned', $data_a);
                // }
            }
            $this->db->order_by('lead_acquired_date', 'DESC');
            // $this->db->where('category', 39);
            // $this->db->where('createPackEmailStatus', 1);
            // $this->db->where('lead_payment_status', 1);
            $this->db->select('CONCAT(tbll.firstname, ' . " " . ', tbll.lastname) AS fullname,tblleads_create_package.*');
            $this->db->join('tblstaff AS tbll', 'tblleads_create_package.assigned=tbll.staffid');
        } else {
            if ($from_date != '') {
                $this->db->where('lead_acquired_date >=', $from_date);
            }
            if ($to_date != "") {
                $this->db->where('lead_acquired_date <=', $to_date);
            }
            // $this->db->where('category', 39);
            $this->db->order_by('lead_acquired_date', 'DESC');
            $this->db->where('assigned', $staff_id);
            // $this->db->where('createPackEmailStatus', 1);
            // $this->db->where('lead_payment_status', 1);
            $this->db->join('tblstaff AS tbll', 'tblleads_create_package.assigned=tbll.staffid');
        }
        $result1  = $this->db->get('tblleads_create_package')->result();

        $result2 = array_merge($result, $result1);
        
        return $result2;
    }
    
    //page count for acquisition
    public function TotLeadCount($pc_name='',$from_date = '',$to_date='',$search_text='',$search_cat='')
    {
        $staff_id = $this->session->userdata('staff_user_id');
        $role = get_imp_role();
        $arr = herapermission();
        $arr = explode(',', $arr);
        $data_a = $this->db->get_where('tblstaff', array('role_id' => 64))->result();
        $data_a = explode(',', $data_a);


        $this->db->select('count(*) as allcount');
        $this->db->from('tblleads');

        if ($search_text != '') {
            $this->db->group_start();
            $this->db->like('lead_author_name', $search_text);
            $this->db->or_like('phonenumber', $search_text);
            $this->db->or_like('lead_author_msstatus', $search_text);
            $this->db->or_like('email', $search_text);
            $this->db->or_like('lead_created_date', $search_text);
            $this->db->or_like('lead_booktitle', $search_text);
            $this->db->or_like('otherphonenumber', $search_text);
            $this->db->group_end();
        }
        if ($search_cat != '') {
            if ($search_cat == 'no_category') {
                $search_cat = 'null';
                $this->db->where_in('lead_category_id', $search_cat);
            } else {
                $this->db->where_in('lead_category_id', $search_cat);
            }
        }
        if (is_admin() || ($staff_id == 34) || ($staff_id == 28) || ($role == 92) || ($role == 78)) {
            if ($from_date != '') {
                $from_date = (isset($from_date) && trim($from_date) != '') ? date("Y-m-d", strtotime(trim($from_date))) : '';
                $this->db->where('lead_acquired_date >=', $from_date);
            } else {
                $from_date = date('Y-m-01');
                $this->db->where('lead_acquired_date >=', $from_date);
            }
            if ($to_date != "") {
                $to_date = (isset($to_date) && trim($to_date) != '') ? date("Y-m-d", strtotime(trim($to_date))) : '';
                $this->db->where('lead_acquired_date <=', $to_date);
            } else {
                $to_date = date('Y-m-d');
                $this->db->where('lead_acquired_date <=', $to_date);
            }
            if ($pc_name != '') {
                if ($pc_name != '0') {
                    $this->db->where_in('assigned', $pc_name);
                }
            } else {
                // if ($role == 92) {
                //     $this->db->where_in('assigned', $arr);
                // } else {
                //     $this->db->where_in('assigned', $data_a);
                // }
            }
            // $this->db->where('lead_category_id', 39);
            // $this->db->where('createPackEmailStatus', 1);
            // $this->db->where('lead_payment_status', 1);
            $this->db->select('CONCAT(tbll.firstname, ' . " " . ', tbll.lastname) AS fullname,tblleads.*');
            $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
        } else {
            if ($from_date != '') {
                $from_date = (isset($from_date) && trim($from_date) != '') ? date("Y-m-d", strtotime(trim($from_date))) : '';
                $this->db->where('lead_acquired_date >=', $from_date);
            }
            if ($to_date != "") {
                $to_date = (isset($to_date) && trim($to_date) != '') ? date("Y-m-d", strtotime(trim($to_date))) : '';
                $this->db->where('lead_acquired_date <=', $to_date);
            }
            $this->db->where('assigned', $staff_id);
            // $this->db->where('lead_category_id ', 39);
            // $this->db->where('createPackEmailStatus', 1);
            // $this->db->where('lead_payment_status', 1);
            $this->db->select('CONCAT(tbll.firstname, ' . " " . ', tbll.lastname) AS fullname,tblleads.*');
            $this->db->join('tblstaff AS tbll', 'tblleads.assigned=tbll.staffid');
        }

        $query = $this->db->get();
        $result = $query->result_array();
        $this->db->select('count(*) as allcount');
        $this->db->from('tblleads_create_package');

        if ($search_text != '') {
            $this->db->group_start();
            $this->db->like('lead_author_name', $search_text);
            $this->db->or_like('phonenumber', $search_text);
            $this->db->or_like('lead_author_msstatus', $search_text);
            $this->db->or_like('email', $search_text);
            $this->db->or_like('lead_created_date', $search_text);
            $this->db->or_like('lead_booktitle', $search_text);
            $this->db->or_like('otherphonenumber', $search_text);
            $this->db->group_end();
        }
        if ($search_cat != '') {
            if ($search_cat == 'no_category') {
                $search_cat = 'null';
                $this->db->where_in('lead_category_id', $search_cat);
            } else {
                $this->db->where_in('lead_category_id', $search_cat);
            }
        }
        if (is_admin() || ($staff_id == 34) || ($staff_id == 28) || ($role == 92)) {
            if ($from_date != '') {
                $from_date = (isset($from_date) && trim($from_date) != '') ? date("Y-m-d", strtotime(trim($from_date))) : '';
                $this->db->where('lead_acquired_date >=', $from_date);
            } else {
                $from_date = date('Y-m-01');
                $this->db->where('lead_acquired_date >=', $from_date);
            }
            if ($to_date != "") {
                $to_date = (isset($to_date) && trim($to_date) != '') ? date("Y-m-d", strtotime(trim($to_date))) : '';
                $this->db->where('lead_acquired_date <=', $to_date);
            } else {
                $to_date = date('Y-m-d');
                $this->db->where('lead_acquired_date <=', $to_date);
            }
            if ($pc_name != '') {
                if ($pc_name != '0') {
                    $this->db->where_in('assigned', $pc_name);
                }
            } else {
                // if ($role == 92) {
                //     $this->db->where_in('assigned', $arr);
                // } else {
                //     $this->db->where_in('assigned', $data_a);
                // }
            }
            // $this->db->where('category', 39);
            // $this->db->where('createPackEmailStatus', 1);
            // $this->db->where('lead_payment_status', 1);
            $this->db->select('CONCAT(tbll.firstname, ' . " " . ', tbll.lastname) AS fullname,tblleads_create_package.*');
            $this->db->join('tblstaff AS tbll', 'tblleads_create_package.assigned=tbll.staffid');
        } else {
            if ($from_date != '') {
                $from_date = (isset($from_date) && trim($from_date) != '') ? date("Y-m-d", strtotime(trim($from_date))) : '';
                $this->db->where('lead_acquired_date >=', $from_date);
            }
            if ($to_date != "") {
                $to_date = (isset($to_date) && trim($to_date) != '') ? date("Y-m-d", strtotime(trim($to_date))) : '';
                $this->db->where('lead_acquired_date <=', $to_date);
            }
            $this->db->where('assigned', $staff_id);
            // $this->db->where('category ', 39);
            // $this->db->where('createPackEmailStatus', 1);
            // $this->db->where('lead_payment_status', 1);
            $this->db->select('CONCAT(tbll.firstname, ' . " " . ', tbll.lastname) AS fullname,tblleads_create_package.*');
            $this->db->join('tblstaff AS tbll', 'tblleads_create_package.assigned=tbll.staffid');
        }
        $query = $this->db->get();
        $result1 = $query->result_array();
        return $result[0]['allcount'] + $result1[0]['allcount'];
    }
    
    public function getrecordCount($search = '',$search_cat='',$start_date = '',$end_date='',$staff_name = '',$sel_ms='') {
        $id = $this->session->userdata('staff_user_id');
        $role = get_imp_role();
        $arr = herapermission();
        $arr = explode(',', $arr);
        $this->db->select('count(*) as allcount');
        $this->db->from('tblleads');
        if (!(is_admin())) {
            if ($id == 34 || $id == 28 ) {
                
            }elseif($role ==92){
                $this->db->where_in('assigned',$arr);
            }else{
                $this->db->where('assigned',$id);
            }
        }
        if($sel_ms !=''){
            $this->db->where('lead_author_msstatus', $sel_ms);
        }
        if($search != ''){
           $this->db->like('lead_author_name', $search);
      $this->db->or_like('phonenumber', $search);
      $this->db->or_like('lead_adname', $search);
      $this->db->or_like('lead_author_msstatus', $search);
      $this->db->or_like('lead_publishedearlier', $search);
      $this->db->or_like('email', $search);
      $this->db->or_like('language',$search);
      $this->db->or_like('lead_callingdate',$search);
      $this->db->or_like('ImEx_NextcallingDate',$search);
      $this->db->or_like('lead_created_date',$search);
      $this->db->or_like('lead_bookformat',$search);
      $this->db->or_like('lead_booktitle',$search);
      $this->db->or_like('otherphonenumber',$search);
        }
        if($search_cat != ''){
            if($search_cat == 'no_category'){
                $search_cat = 'null';
                $this->db->where_in('lead_category_id', $search_cat);
            }else{
               $this->db->where_in('lead_category_id', $search_cat); 
            }
        }
        if($staff_name != ''){
            $this->db->where_in('assigned', $staff_name); 
        }

        $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
 if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {

            //$this->db->where('lead_created_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
            $this->db->where('lead_created_date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }

        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result[0]['allcount'];
    }
    public function leedsData($limit, $start,$search_text='',$search_cat='',$start_date = '',$end_date='', $staff_name = '',$sel_ms=''){
        $useraid = $this->session->userdata('staff_user_id');
        // print_r($useraid);die;
        $role = get_imp_role();
        $arr = herapermission();
        $arr = explode(',', $arr);
        $this->load->database();
        $this->db->select('id,lead_author_name,phonenumber,	lead_adname,lead_author_msstatus,lead_publishedearlier,email,lead_author_mslanguage,lead_callingdate,lead_category_id,description,ImEx_NextcallingDate,lead_created_date,lead_bookformat,lead_booktitle,otherphonenumber,craete_package,create_other_package,assigned');
        if (!(is_admin())) {
            if ($useraid == 34 || $useraid == 28) {
           
            }elseif($role ==92){
                $this->db->where_in('assigned',$arr);
            }else{
                $this->db->where('assigned',$useraid);
            }
         
        }
        
        $this->db->order_by('lead_approve_current_date','ASC');
        
        $this->db->limit($limit, $start);
        
        if($sel_ms !=''){
            $this->db->where('lead_author_msstatus', $sel_ms);
        }
        
      if($search_text != ''){
        $this->db->group_start();
        $this->db->like('lead_author_name', $search_text);
      $this->db->or_like('phonenumber', $search_text);
      $this->db->or_like('lead_adname', $search_text);
      $this->db->or_like('lead_author_msstatus', $search_text);
      $this->db->or_like('lead_publishedearlier', $search_text);
      $this->db->or_like('email', $search_text);
      $this->db->or_like('language',$search_text);
      $this->db->or_like('lead_callingdate',$search_text);
      $this->db->or_like('ImEx_NextcallingDate',$search_text);
      $this->db->or_like('lead_created_date',$search_text);
      $this->db->or_like('lead_bookformat',$search_text);
      $this->db->or_like('lead_booktitle',$search_text);
      $this->db->or_like('otherphonenumber',$search_text);
      $this->db->group_end();
        }
        if($search_cat != ''){
            if($search_cat == 'no_category'){
                $search_cat = 'null';
                $this->db->where_in('lead_category_id', $search_cat);
            }else{
               $this->db->where_in('lead_category_id', $search_cat); 
            }
        }
        if($staff_name != ''){
           
               $this->db->where_in('assigned', $staff_name); 
           
        }
        $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
        $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
 if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {

           // $this->db->where('lead_created_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
           $this->db->where('lead_created_date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
        }
       
        $query = $this->db->get('tblleads');
        // echo $this->db->last_query();die;
        return $query->result();
    }

}