<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Account_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get announcements
     * @param  string $id    optional id
     * @param  array  $where perform where
     * @param  string $limit
     * @return mixed
     */
    
	public function index(){
        $q1 = array();
        $q2 = array();
        $q1=$this->db->select('*')->where('lead_status ','1')->order_by('lead_acquired_date', 'DESC')->get('tblleads')->result_array();
        // $q2=$this->db->select('*')->where('lead_status ','1')->order_by('lead_acquired_date', 'DESC')->get('tblleads_create_package')->result_array();

        // $this->db->select('tblleads.email as email,tblleads.phonenumber as phonenumber,tblleads_create_package.*');
        // $this->db->from('tblleads_create_package');
        // $this->db->join('tblleads', 'tblleads.id=tblleads_create_package.leadid', 'right');
        // $q2 = $this->db->where('lead_status ','1')->result_array();
        // $q2 = $this->db->order_by('lead_acquired_date', 'DESC')->result_array();
        $this->db->select('tblleads.email as email,tblleads.phonenumber as phonenumber, tblleads_create_package.*');
        $this->db->from('tblleads');
        $this->db->join('tblleads_create_package', 'tblleads_create_package.leadid=tblleads.id');
        $this->db->where('tblleads_create_package.lead_status', '1');
        $q2 = $this->db->get()->result_array();
//        echo "<pre>";
// echo $this->db->last_query();
// die;
        // print_r($q2);
        // die;
        $result = array();
        // $result = $this->db->query('SELECT * FROM tblleads');
        //  $result =array_push($q1,$q2);
        $result = array_merge($q1,$q2);
    //    echo "<pre>";
    //     print_r($result);die;
        //print_r($q2);
        //exit;
        return $result;
        // return $q=$this->db
        //      ->select('*')
        //      ->where('lead_status ','1')
        //      ->order_by('lead_acquired_date', 'DESC')
        //      ->get('tblleads')
        //     ->result();
	}
	
	public function changePaymentStatus($payment_id, $data,$tbltype){
        $this->db->where('id', $payment_id);
        if($tbltype==1){
            $this->db->update("tblleads", $data);
        }elseif($tbltype==3){
            $this->db->update("tblleads_create_package", $data);
        }
	}
	
	public function changeroyaltyStatus($name, $data){
		$this->db->where('author_name', $name);
		$this->db->update("mrpfixation", $data);
	
	}
	
	public function save_paid_royalty($data1){
		$result = $this->db->insert('account_payment',$data1);
		return $result;
	}
}
