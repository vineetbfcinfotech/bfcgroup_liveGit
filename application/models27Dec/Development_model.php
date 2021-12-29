<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Development_model extends CRM_Model

{

    public function __construct()

    {

        parent::__construct();

		$this->prodigy = $this->load->database(anotherdb, true);

    }

    public function index()

    {

        return "Test";

    }

	

	public function get_uploaded_data(){

		$this->prodigy->select("*");

		$this->prodigy->from("advisory");

		$this->prodigy->where("data_type", 1);

		$this->prodigy->order_by("id", ASC);

		return $this->prodigy->get()->result();

	}

	

	public function get_uploaded_scheme_data(){

		$this->prodigy->select("*");

		$this->prodigy->from("scheme_performance");

		$this->prodigy->order_by("id", ASC);

		return $this->prodigy->get()->result();

	}

	

	public function scheme_list(){

		$this->prodigy->select("*");

		$this->prodigy->from("scheme_performance");

		$this->prodigy->order_by("id", ASC);

		$records = $this->prodigy->get()->result();

		$data = array();

		 foreach($records as $record ){



			$data[] = array( 

			   "emp_name"=>$record->emp_name,

			   "email"=>$record->email,

			   "gender"=>$record->gender,

			   "salary"=>$record->salary,

			   "city"=>$record->city

			); 

		 }



		 ## Response

		 $response = array(

			"draw" => intval(50),

			"iTotalRecords" => $totalRecords,

			"iTotalDisplayRecords" => $totalRecordwithFilter,

			"aaData" => $data

		 );

		 return $response;



	}

	

	public function import($data){

		return $this->prodigy->insert('advisory',$data);

	}

	public function import_scheme($data){

		return $this->prodigy->insert('scheme_performance',$data);

	}

	

	public function importUpdate($investment,$data){

		$this->prodigy->where("investment_horizon", $investment);

		$this->prodigy->update('advisory',$data);

	}

	

	public function import_notify($data){

	    return$this->prodigy->insert('fcm_notify',$data);
	}

	

	public function getData($investment){

		$this->prodigy->select("*");

		$this->prodigy->from("advisory");

		$this->prodigy->where("investment_horizon", $investment);

		return $this->prodigy->get()->row();

	}

	

	public function createBasket($data){

		return $this->prodigy->insert('advisory_scheme',$data);

	}

	

	public function updateBasket($id, $data){

		$this->prodigy->where("id", $id);

		return $this->prodigy->update('advisory_scheme',$data);

	}

	

	public function getBasketData(){

		//$this->prodigy->select("*");

		$this->prodigy->select("advisory_scheme.*, anchoring_data.id as anc_id");

		$this->prodigy->from("advisory_scheme");

		$this->prodigy->join('anchoring_data', 'anchoring_data.name = advisory_scheme.anchoring');

		$this->prodigy->order_by("anchoring_data.id", ASC);

		return $this->prodigy->get()->result();

	}

	

	public function deleteBasket($id){

		$this->prodigy->where('id', $id);

		return $this->prodigy->delete('advisory_scheme'); 

	}

	

	public function getTransationType(){

		$this->prodigy->select("transaction_type");

		$this->prodigy->from("advisory");

		$this->prodigy->distinct();

		return $this->prodigy->get()->result();

	}

	public function getAnchoring(){

		$this->prodigy->select("advisory.anchoring");

		$this->prodigy->from("advisory");

		$this->prodigy->join('anchoring_data', 'anchoring_data.name = advisory.anchoring');

		$this->prodigy->order_by("anchoring_data.id", ASC);

		$this->prodigy->distinct();

		return $result = $this->prodigy->get()->result();

	}

	public function getConstellation(){

		$this->prodigy->select("constellation");

		$this->prodigy->from("advisory");

		$this->prodigy->distinct();

		return $this->prodigy->get()->result();

	}

	public function getOptions(){

		$this->prodigy->select("option");

		$this->prodigy->from("advisory");

		$this->prodigy->distinct();

		return $this->prodigy->get()->result();

	}

	

	public function getAssetType($transaction_type, $anchoring, $option, $constellation){

		$this->prodigy->select("asset_class");

		$this->prodigy->from("advisory");

		$this->prodigy->where("transaction_type", $transaction_type);

		$this->prodigy->where("anchoring", $anchoring);

		$this->prodigy->where("constellation", $constellation);

		$this->prodigy->where("option", $option);

		$this->prodigy->distinct();

		return $this->prodigy->get()->result();

	}

	

	public function getSchemeName($transaction_type, $anchoring, $option, $constellation, $asset_type){

		$this->prodigy->select("scheme_name, product_code, isin_no");

		$this->prodigy->from("advisory");

		$this->prodigy->where("transaction_type", $transaction_type);

		$this->prodigy->where("anchoring", $anchoring);

		$this->prodigy->where("constellation", $constellation);

		$this->prodigy->where("option", $option);

		$this->prodigy->where_in("asset_class", $asset_type);

		//$this->prodigy->distinct();

		return $this->prodigy->get()->result();

		//echo $this->prodigy->last_query();

		 

	}

	

	public function getEditData($dataid){

		$this->prodigy->select("*");

		$this->prodigy->from("advisory_scheme");

		$this->prodigy->where("id", $dataid);

		return $this->prodigy->get()->row();

	}

	

	public function filterDataForBasket($transaction_type, $anchoring, $option, $constellation){

		$this->prodigy->select("*");

		$this->prodigy->from("advisory_scheme");

		$this->prodigy->where("transaction_type", $transaction_type);

		$this->prodigy->where("anchoring", $anchoring);

		$this->prodigy->where("option", $option);

		$this->prodigy->where("constellation", $constellation);

		return $this->prodigy->get()->row();

	}

	

	public function getNotifications(){
		$this->prodigy->select("*");
		$this->prodigy->from("fcm_notify");
		$this->prodigy->order_by("notify_id", "desc");
		return $this->prodigy->get()->result();
	}

	public function getNotify($notify_id,$user_id){
		//echo $user_id; exit();
		$this->prodigy->select("*");
		$this->prodigy->from("user_notify");
		$this->db->order_by("id", "desc");
		$this->prodigy->where("user_id",$user_id);
		$this->prodigy->where("notify_id",$notify_id);
		return $this->prodigy->get()->result();
	    echo $this->prodigy->last_query(); exit();
	}

	public function checkUserNotify($notify_id,$user_id){

		$this->prodigy->select("*");
		$this->prodigy->from("user_notify");
		$this->db->order_by("id", "desc");
		$this->prodigy->where("user_id",$user_id);
		$this->prodigy->where("notify_id",$notify_id);
		return $this->prodigy->get()->result();
		//return $this->prodigy->update('user_notify',$data);
	}


	public function getuserNotify($notify_id){
		$this->prodigy->select("*");
		$this->prodigy->from("fcm_notify");
		$this->prodigy->where("notify_id",$notify_id);
		return $this->prodigy->get()->result();
	}
}

