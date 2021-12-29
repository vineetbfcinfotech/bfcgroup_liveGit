<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Format_editor_modal extends CRM_Model
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
    //     $this->db->get('tblleads')->result();
    // //   return $this->db->get_where('tblleads',array('project_status'=>9))->result();
    $query=$this->db->select('*')->get('tblleads');
    return $query->result();
    
	}
	
	public function changeProjectStatus($project_id, $data){
		$this->db->where('id', $project_id);
		$this->db->update("tblleads", $data);
	}
	public function changePageStatus($project_id, $data1){
		$this->db->where('id', $project_id);
		$this->db->update("tblleads", $data1);
	}
	
	
}
