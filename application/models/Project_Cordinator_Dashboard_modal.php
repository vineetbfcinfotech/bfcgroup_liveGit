<?php defined('BASEPATH') or exit('No direct script access allowed');
class Project_Cordinator_Dashboard_modal extends CRM_Model{
    public function __construct(){
        parent::__construct();
    }
    
    /*================Service page functions=============================*/
    // Insert a value into database
    public function save_service($data){
        $result = $this->db->insert('tblpackageservices',$data);
        return $result;  
    }
    // Get all services based on package id
    public function update_service($serviceId,$data){
        $this->db->where('id', $serviceId);
        $result = $this->db->update('tblpackageservices',$data);
        return $result;  
    }
    // Delete single service
    public function delete_service($serviceId){
        $this->db->where('id', $serviceId);
        $result = $this->db->delete('tblpackageservices');
        return $result;
        
    }
  
    /*================Sub Service page functions=============================*/
    
    // Insert subservice  value into database
    public function save_subservice($data){
        $result = $this->db->insert('tblpackagesubservices',$data);
        return $result;  
    }
    // Get  subservices data based on id
    public function update_subservice($subserviceId,$data){
        $this->db->where('id', $subserviceId);
        $result = $this->db->update('tblpackagesubservices',$data);
        return $result;  
    }
    // Delete single service
    public function delete_subservice($subserviceId){
        $this->db->where('id', $subserviceId);
        $result = $this->db->delete('tblpackagesubservices');
        return $result;
        
    }
     // Get service list based on package id
    public function get_services($serviceId){
        $this->db->where('packageid', $serviceId);
        $this->db->select('id,packageid,service_name');
        $result = $this->db->get('tblpackageservices')->result();
        $arr_data = array();
        $i=0;
        foreach($result as $data){
            
            $arr_data[$i]["id"] = $data->id;
            $arr_data[$i]["packageid"] = $data->packageid;
            $arr_data[$i]["name"] = $data->service_name;
            $i++;
            
        }
        //print_r($arr_data);
        return $arr_data;
        
    }
    public function get_pc($pc_id)
    {
       return $this->db->get_where('tblstaff',array('role_id'=>$pc_id))->result();
    }
    public function get_pm($pm_id)
    {
       return $this->db->get_where('tblstaff',array('role_id'=>$pm_id))->result();
   // echo    $this->db->last_query();
    }
    
    
}