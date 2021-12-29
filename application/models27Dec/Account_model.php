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
        /*$q1 = array();
        $q2 = array();
        $q1=$this->db->select('*')->where('lead_status ','1')->order_by('lead_acquired_date', 'DESC')->get('tblleads')->result_array();
        $q2=$this->db->select('*')->where('lead_status ','1')->order_by('lead_acquired_date', 'DESC')->get('tblleads_create_package')->result_array();
        $result = array();*/
        // $result = $this->db->query('SELECT * FROM tblleads');
        //  $result =array_push($q1,$q2);
        // $result = array_merge($q1,$q2);
       // echo "<pre>";
        //print_r($result);
        //print_r($q2);
        //exit;
        // return $result;
        return $q=$this->db
             ->select('*')
             ->where('lead_status ','1')
             ->order_by('lead_acquired_date', 'DESC')
             ->get('tblleads')
            ->result();
	}
	
	public function changePaymentStatus($payment_id, $data){
		$this->db->where('id', $payment_id);
		$this->db->update("tblleads", $data);
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
