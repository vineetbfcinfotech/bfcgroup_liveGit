<?php defined('BASEPATH') or exit('No direct script access allowed');
class inventory_Dashboard_modal extends CRM_Model{
    public function __construct(){
        parent::__construct();
    }
    
    // Insert a value into database
    public function save_inventory_model($data){
        print_r($data);exit;
        $result = $this->db->insert('tblinventory',$data);
        return $result;  
    }
   
 
    
    
}