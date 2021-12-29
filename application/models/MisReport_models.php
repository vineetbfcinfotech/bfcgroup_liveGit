<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MisReport_models extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getmisdata($limit = '', $start = '', $search_text = '', $staff_name = '', $start_date = '', $end_date = '')
    {
        
        $this->db->select('id,lead_author_name,lead_booktitle,lead_acquired_date,lead_asf_status,lead_agreement_status,lead_author_msstatus,project_status, print_quotation_status, lead_isbn_status,lead_first_installment, final_installment_amount, send_print_quatation, lead_isbn_paperback,lead_asf_assign_marketing,lead_book_type,lead_author_mslanguage,pdf_mail_agreement,lead_raw_ms,project_status_gd,lead_isbn_ebook,lead_isbn_paperback');
        //  $this->db->limit($limit, $start);

        //  $this->db->where('lead_category_id',39);
        // $this->db->order_by('lead_acquired_date','ASC');


        // if ($staff_name != '') {
        //     $pc = array();
        
        //     foreach ($staff_name as $staff){
        //         // print_r($staff);
        //     $this->db->where('pm_assign_to', $staff);
        //    $this->db->get('tblstaff');
        //    $query =  $this->db->select('staffid')->get_where('tblstaff',array('pm_assign_to'=>$staff))->result();
        //         foreach ($query as $row){
        //             array_push($pc,$row->staffid);
        //         }
        //     }
        //     $this->db->where_in('assigned', $pc);
        // }
        // $id = $this->session->userdata('staff_user_id');
        // echo $id;die;
        if((is_admin())  || ($id == 55)){
            if ($staff_name != '') {
            
                $pc = array();
            
                foreach ($staff_name as $staff){
                $this->db->where('pm_assign_to', $staff);
               $this->db->get('tblstaff');
               $query11 =  $this->db->select('staffid')->get_where('tblstaff',array('pm_assign_to'=>$staff))->result();
                    foreach ($query11 as $row){
                        array_push($pc,$row->staffid);
                    }
                }
                // $this->db->select('count(*) as allcount');
                $this->db->where_in('assigned', $pc);
            }  
        }else if (($id == 82) || ($id == 61)) {
            $pc = array();
           $query11 =  $this->db->select('staffid')->get_where('tblstaff',array('pm_assign_to'=>$id))->result();
                foreach ($query11 as $row){
                    array_push($pc,$row->staffid);
                }
            // }
            // print_r($pc);
            // $this->db->select('count(*) as allcount');
            $this->db->where_in('assigned', $pc);
        }

        $start_date = (isset($start_date) && trim($start_date) != '') ? date("Y-m-d", strtotime(trim($start_date))) : '';
        $end_date = (isset($end_date) && trim($end_date) != '') ? date("Y-m-d", strtotime(trim($end_date))) : '';

        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != '') {

            $this->db->where('lead_acquired_date BETWEEN "' . $start_date . ' 00:00:00.000" and "' . $end_date . ' 23:59:59.997"');
        }elseif($search_text == '' && $staff_name == ''){
            $start_date = date('Y-m-01');
            // // echo $start_date;
            $end_date = date('Y-m-t');
            // // echo $end_date;
            $this->db->where('lead_acquired_date BETWEEN "' . $start_date . ' 00:00:00.000" and "' . $end_date . ' 23:59:59.997"');
            // $this->session->set_userdata(array("start_date" => $start_date, "end_date" => $end_date)); 
        }

        
        
        if ($search_text != '') {
            $this->db->like('lead_author_name', $search_text);
            $this->db->or_like('lead_booktitle', $search_text);
            $this->db->or_like('lead_acquired_date', $search_text);
            $this->db->or_like('lead_asf_status', $search_text);
            $this->db->or_like('lead_agreement_status', $search_text);
            $this->db->or_like('lead_author_msstatus', $search_text);
            $this->db->or_like('project_status', $search_text);
            $this->db->or_like('print_quotation_status', $search_text);
            $this->db->or_like('lead_isbn_status', $search_text);
            $this->db->or_like('send_print_quatation', $search_text);
            $this->db->or_like('lead_book_type', $search_text);
            $this->db->or_like('lead_author_mslanguage', $search_text);
            $this->db->or_like('lead_asf_assign_marketing', $search_text);
        }

        $this->db->limit($limit, $start);

        $this->db->where('lead_category_id',39);
       $this->db->order_by('lead_acquired_date','ASC');
        $query = $this->db->get('tblleads');
      /*echo 'misdata<br>';   
 echo $this->db->last_query();
 die;*/
        if ($query) {
            return $query->result();
        } else {
            return '';
        }
        
    }




    public function getmiscount( $search_text = '',$staff_name = '', $start_date = '', $end_date = '')
    {
        $id = $this->session->userdata('staff_user_id');
        // echo "he";
        // print_r($start_date);
        // print_r($end_date);die;
        $start_date = (isset($start_date) && trim($start_date) != '') ? date("Y-m-d", strtotime(trim($start_date))) : '';
        $end_date = (isset($end_date) && trim($end_date) != '') ? date("Y-m-d", strtotime(trim($end_date))) : '';

        // $this->db->select('count(*) as allcount');

        if((is_admin())  || ($id == 55)){
            if ($staff_name != '') {
            
                $pc = array();
            
                foreach ($staff_name as $staff){
                $this->db->where('pm_assign_to', $staff);
               $this->db->get('tblstaff');
               $query11 =  $this->db->select('staffid')->get_where('tblstaff',array('pm_assign_to'=>$staff))->result();
                    foreach ($query11 as $row){
                        array_push($pc,$row->staffid);
                    }
                }
                $this->db->select('count(*) as allcount');
                $this->db->where_in('assigned', $pc);
            }  
        }else if (($id == 82) || ($id == 61)) {
            $pc = array();
           $query11 =  $this->db->select('staffid')->get_where('tblstaff',array('pm_assign_to'=>$id))->result();
                foreach ($query11 as $row){
                    array_push($pc,$row->staffid);
                }
            // }
            // print_r($pc);
            $this->db->select('count(*) as allcount');
            $this->db->where_in('assigned', $pc);
        }

      

       

        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != '') {
            $this->db->select('count(*) as allcount');
            $this->db->where('lead_acquired_date BETWEEN "' . $start_date . ' 00:00:00.000" and "' . $end_date . ' 23:59:59.997"');
        }elseif($search_text == '' && $staff_name == ''){
            $start_date = date('Y-m-01');
            // // echo $start_date;
            $end_date = date('Y-m-t');
            $this->db->select('count(*) as allcount');
            // // echo $end_date;
            $this->db->where('lead_acquired_date BETWEEN "' . $start_date . ' 00:00:00.000" and "' . $end_date . ' 23:59:59.997"');
            // $this->session->set_userdata(array("start_date" => $start_date, "end_date" => $end_date)); 
        }
               
        if ($search_text != '') {
            $this->db->like('lead_author_name', $search_text);
            $this->db->or_like('lead_booktitle', $search_text);
            $this->db->or_like('lead_acquired_date', $search_text);
            $this->db->or_like('lead_asf_status', $search_text);
            $this->db->or_like('lead_agreement_status', $search_text);
            $this->db->or_like('lead_author_msstatus', $search_text);
            $this->db->or_like('project_status', $search_text);
            $this->db->or_like('print_quotation_status', $search_text);
            $this->db->or_like('lead_isbn_status', $search_text);
            $this->db->or_like('send_print_quatation', $search_text);
            $this->db->or_like('lead_book_type', $search_text);
            $this->db->or_like('lead_author_mslanguage', $search_text);
            $this->db->or_like('lead_asf_assign_marketing', $search_text);
        }

        if($search_text == '' && $staff_name == '' &&  $start_date == '' && $end_date == ''){
            $this->db->select('count(*) as allcount');
            $this->db->where('lead_category_id',39);
        }
        
        $this->db->where('lead_category_id',39);
        $this->db->from('tblleads');
        $query1 = $this->db->get();
// echo $this->db->last_query();

       
        if($query1){
            $result = $query1->result_array();
            return $result[0]['allcount'];
        }else{
            return 0;
        }
        // print_r($result[0]['allcount']);die;
      
    }


    public function get_pm()
    {
        $this->db->select('staffid,firstname');
        $this->db->where('role_id', 50);
        $query = $this->db->get('tblstaff');
        if ($query->num_rows()) {
            return $query->result();
        }

    }

    public function pc_assigned_pm($staff_name){
        // print_r($staff_name);die;
        // $this->db->select('staffid');
        $pc = array();
        
        foreach ($staff_name as $staff){
            // print_r($staff);
        $this->db->where('pm_assign_to', $staff);
        $query = $this->db->get('tblstaff');
        if ($query->num_rows()) {
            $result = $query->result();
            foreach ($result as $row){
                array_push($pc,$row->staffid);
            }
        }
        return $pc;
    }
      
    }
    public function getmiscount_after( $search_text = '',$staff_name = '', $start_date = '', $end_date = '')
    {
        $id = $this->session->userdata('staff_user_id');
        // echo $id;
        $this->db->select('count(*) as allcount');
        $this->db->from('tblleads');
        // if (!(is_admin())) {
        //     if ($id == 34) {
           
        //     }else{
        //         $this->db->where('assigned',$useraid);
        //     }
        // //   $this->db->where('assigned', $id);
        // }
        
        if($search != ''){
            $this->db->like('lead_author_name', $search_text);
            $this->db->or_like('lead_booktitle', $search_text);
            $this->db->or_like('lead_acquired_date', $search_text);
            $this->db->or_like('lead_asf_status', $search_text);
            $this->db->or_like('lead_agreement_status', $search_text);
            $this->db->or_like('lead_author_msstatus', $search_text);
            $this->db->or_like('project_status', $search_text);
            $this->db->or_like('print_quotation_status', $search_text);
            $this->db->or_like('lead_isbn_status', $search_text);
            $this->db->or_like('send_print_quatation', $search_text);
            $this->db->or_like('lead_book_type', $search_text);
            $this->db->or_like('lead_author_mslanguage', $search_text);
            $this->db->or_like('lead_asf_assign_marketing', $search_text);
        }
        if($staff_name != ''){
            $pc = array();
        
            foreach ($staff_name as $staff){
                // print_r($staff);
            $this->db->where('pm_assign_to', $staff);
           $this->db->get('tblstaff');
           $query11 =  $this->db->select('staffid')->get_where('tblstaff',array('pm_assign_to'=>$staff))->result();
                foreach ($query11 as $row){
                    array_push($pc,$row->staffid);
                }
            }
            $this->db->where_in('assigned', $pc);
            //    $this->db->where_in('assigned', $staff_name); 
           
        }
        if (($id == 82) || ($id == 61))  {
            $pc = array();
            $query11 =  $this->db->select('staffid')->get_where('tblstaff',array('pm_assign_to'=>$id))->result();
            foreach ($query11 as $row){
                array_push($pc,$row->staffid);
            }
            $this->db->where_in('assigned', $pc);
        }
       
        
        

//           $start_date = (isset($start_date) && trim($start_date)!='')?date("Y-m-d",strtotime(trim($start_date))):'';//$_POST['start_date'];die;
//         $end_date = (isset($end_date) && trim($end_date)!='')?date("Y-m-d",strtotime(trim($end_date))):'';//$_POST['end_date'];
     
//  if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
//         {
//             $this->db->where('lead_acquired_date BETWEEN "'.$start_date.' 00:00:00.000" and "'. $end_date.' 23:59:59.997"');
//         }

//         $query = $this->db->get();
//         $result = $query->result_array();
//         return $result[0]['allcount'];




//          // echo "he";
//         // print_r($start_date);
//         // print_r($end_date);die;
//         $start_date = (isset($start_date) && trim($start_date) != '') ? date("Y-m-d", strtotime(trim($start_date))) : '';
//         $end_date = (isset($end_date) && trim($end_date) != '') ? date("Y-m-d", strtotime(trim($end_date))) : '';

//         // $this->db->select('count(*) as allcount');


//         if ($staff_name != '') {
           
//         }

       

//         if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != '') {
//             $this->db->select('count(*) as allcount');
//             $this->db->where('lead_acquired_date BETWEEN "' . $start_date . ' 00:00:00.000" and "' . $end_date . ' 23:59:59.997"');
//         }
               
//         if ($search_text != '') {
//             $this->db->like('lead_author_name', $search_text);
//             $this->db->or_like('lead_booktitle', $search_text);
//             $this->db->or_like('lead_acquired_date', $search_text);
//             $this->db->or_like('lead_asf_status', $search_text);
//             $this->db->or_like('lead_agreement_status', $search_text);
//             $this->db->or_like('lead_author_msstatus', $search_text);
//             $this->db->or_like('project_status', $search_text);
//             $this->db->or_like('print_quotation_status', $search_text);
//             $this->db->or_like('lead_isbn_status', $search_text);
//             $this->db->or_like('send_print_quatation', $search_text);
//             $this->db->or_like('lead_book_type', $search_text);
//             $this->db->or_like('lead_author_mslanguage', $search_text);
//             $this->db->or_like('lead_asf_assign_marketing', $search_text);
//         }

//         if($search_text == '' && $staff_name == '' &&  $start_date == '' && $end_date == ''){
//             $this->db->select('count(*) as allcount');
//             $this->db->where('lead_category_id',39);
//         }

$this->db->where('lead_category_id',39);
$query = $this->db->get();
$result = $query->result_array();
// echo $this->db->last_query();

        // print_r($result[0]['allcount']);die;
        if($query){
            return $result[0]['allcount'];
        }else{
            return 0;
        }
       
    }
    public function getmisdata_after($limit = '', $start = '', $search_text = '', $staff_name = '', $start_date = '', $end_date = '')
    {
        $this->db->select('id,lead_author_name,lead_booktitle,lead_acquired_date,lead_asf_status,lead_agreement_status,lead_author_msstatus,project_status, print_quotation_status, lead_isbn_status,lead_first_installment, final_installment_amount, send_print_quatation, lead_isbn_paperback,lead_asf_assign_marketing,lead_book_type,lead_author_mslanguage,pdf_mail_agreement,lead_raw_ms,project_status_gd,lead_isbn_ebook,lead_isbn_paperback');
     

        if ($staff_name != '') {
            $pc = array();
        
            foreach ($staff_name as $staff){
                // print_r($staff);
            $this->db->where('pm_assign_to', $staff);
           $this->db->get('tblstaff');
           $query =  $this->db->select('staffid')->get_where('tblstaff',array('pm_assign_to'=>$staff))->result();
                foreach ($query as $row){
                    array_push($pc,$row->staffid);
                }
            }
            $this->db->where_in('assigned', $pc);
        }

        $start_date = (isset($start_date) && trim($start_date) != '') ? date("Y-m-d", strtotime(trim($start_date))) : '';
        $end_date = (isset($end_date) && trim($end_date) != '') ? date("Y-m-d", strtotime(trim($end_date))) : '';

        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != '') {

            $this->db->where('lead_acquired_date BETWEEN "' . $start_date . ' 00:00:00.000" and "' . $end_date . ' 23:59:59.997"');
        }
        
        
        if ($search_text != '') {
            $this->db->like('lead_author_name', $search_text);
            $this->db->or_like('lead_booktitle', $search_text);
            $this->db->or_like('lead_acquired_date', $search_text);
            $this->db->or_like('lead_asf_status', $search_text);
            $this->db->or_like('lead_agreement_status', $search_text);
            $this->db->or_like('lead_author_msstatus', $search_text);
            $this->db->or_like('project_status', $search_text);
            $this->db->or_like('print_quotation_status', $search_text);
            $this->db->or_like('lead_isbn_status', $search_text);
            $this->db->or_like('send_print_quatation', $search_text);
            $this->db->or_like('lead_book_type', $search_text);
            $this->db->or_like('lead_author_mslanguage', $search_text);
            $this->db->or_like('lead_asf_assign_marketing', $search_text);
        }

        $this->db->limit($limit, $start);

        $this->db->where('lead_category_id',39);
       $this->db->order_by('lead_acquired_date','ASC');
        $query = $this->db->get('tblleads');
    //  echo 'misdata<br>';   
// echo $this->db->last_query();
// die;
        if ($query) {
            return $query->result();
        } else {
            return '';
        }
    }


}