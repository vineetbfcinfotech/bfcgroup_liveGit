<?php
// code by Shivani 
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class Format_editor extends Admin_controller{
	public function __construct(){
	    parent::__construct();
	    $this->load->model('format_editor_modal');
	}
	 // List of New Projects
	public function new_project(){
		$useraid = $this->session->userdata('staff_user_id');
		
		$data['title'] = "Format Editor Dashboard";
		$this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where_in('project_assign_to', $useraid);
		$this->db->where('project_status',9);
		$query = $this->db->get();
		$newproject_data = $query->result();
		$data['newproject_data'] = $newproject_data;
		$this->load->view('admin/format_editor/new_project', $data);
	}

	 // Change Product Status
	public function changeProjectStatus(){
		$project_id = $_POST['project_id'];
		$data = array(
			"project_status" => 10,
			"takeup_date" => date('Y-m-d H:i:s'),
		);
		$update = $this->format_editor_modal->changeProjectStatus($project_id, $data);
	} 
	
	  // Update Pages after Team done page
	public function updatepages(){
		$id = $_POST['lead_id'];
		$pages1 = $_POST['completed_pages'];
		$workdone = $_POST['workpage'];
		$pages = $pages1+$workdone;
		if (empty($_POST['workpage']) || $pages <= $_POST['total_pages']) {
		$data1 = array(
			"lead_fe_completed_pages" => $pages,
		);
		
		$update = $this->format_editor_modal->changePageStatus($id, $data1);
		set_alert('success', _l('Pages uploaded successfully...'));
		redirect($_SERVER['HTTP_REFERER']);
		}else{
			set_alert('success', _l('Completed pages can not be more than total pages...'));
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	   // List of project in progress
	public function project_in_process(){
	    $this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where('project_status',10);
		$query = $this->db->get();
		$newproject_data1 = $query->result();
	    $data['newproject_data1'] = $newproject_data1;
	    $this->load->view('admin/format_editor/project_in_process',$data);
	}

	// upload Ms by Format Editor when done
	public function upload_ms(){
        $id = $_POST['lead_id'];
        $filename = $_FILES['file']['name'];
        $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
        if ($filename) {
            $filename = $ckeck_ms->lead_author_name.'_'.$filename;
          
            if($ckeck_ms->lead_fe_ms_file){
                unlink('assets/menuscript/format_editor_ms/'.$ckeck_ms->lead_fe_ms_file);
            }
            $location = "assets/menuscript/format_editor_ms/".$filename;
            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
            /* Valid extensions */
            $valid_extensions = array("pdf", "doc", "docx","csv");
            $response = 0;
            if(in_array(strtolower($imageFileType), $valid_extensions)) {
                if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                      $data_array = array(
                'lead_fe_ms_file'=>$filename,
                'lead_upload_ms_date' => date('Y-m-d H:i:s'),
            );
            $this->db->where('id',$id);
            $this->db->update('tblleads',$data_array);
            
                    $response = $location;
                    set_alert('success', _l('MS uploaded successfully...'));
                    redirect($_SERVER['HTTP_REFERER']);
                
                }
            }else{
               set_alert('worning', _l('Please select valid file.'));
                    redirect($_SERVER['HTTP_REFERER']); 
            }
        }else{
            set_alert('worning', _l('MS not uploaded successfully...'));
            redirect($_SERVER['HTTP_REFERER']);
        
        }
	}
	// upload Ms ebook PDF by Format Editor when done
	public function upload_ms_ebookpdf(){
		$id = $_POST['lead_id'];
    	 $filename = $_FILES['file']['name'];
            $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
            	$filename = $ckeck_ms->lead_author_name.'_'.$filename;
            	
              
                if($ckeck_ms->lead_fe_ms_ebook){
                    unlink('assets/menuscript/format_editor_ms/ebook/'.$ckeck_ms->lead_fe_ms_ebook);
                }
	            $location = "assets/menuscript/format_editor_ms/ebook/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
	            /* Valid extensions */
	           $valid_extensions = array("pdf", "doc", "docx","csv");
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		        	if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
                        $data_array = array(
                        'lead_fe_ms_ebook'=>$filename,
                        );
                        $this->db->where('id',$id);
                        $this->db->update('tblleads',$data_array);
		               set_alert('success', _l('eBook MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }else{
	                
	               set_alert('worning', _l('Please select valid file.'));
		        		redirect($_SERVER['HTTP_REFERER']); 
	            }
           }else{
                set_alert('success', _l('eBook MS not uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);
           }
	}
	// upload Ms Paperback PDF by Format Editor when done
	public function upload_ms_paperback(){
		$id = $_POST['lead_id'];
    	 $filename = $_FILES['file']['name'];
            $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
            	$filename = $ckeck_ms->lead_author_name.'_'.$filename;
            	
              
                if($ckeck_ms->lead_fe_ms_paperback){
                    unlink('assets/menuscript/format_editor_ms/paperback/'.$ckeck_ms->lead_fe_ms_paperback);
                }
	            $location = "assets/menuscript/format_editor_ms/paperback/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
	            /* Valid extensions */
	            $valid_extensions = array("pdf", "doc", "docx","csv");
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
		             $data_array = array(
            		'lead_fe_ms_paperback'=>$filename,
            	);
            	$this->db->where('id',$id);
            	$this->db->update('tblleads',$data_array);
		               set_alert('success', _l('Paperback MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }else{
	                set_alert('success', _l('Paperback MS not uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);
	            }
           }
	}
	// upload Ms Doc by Format Editor when done
	public function upload_ms_doc(){
		$id = $_POST['lead_id'];
    	 $filename = $_FILES['file']['name'];
            $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
            	$filename = $ckeck_ms->lead_author_name.'_'.$filename;
            
                if($ckeck_ms->lead_fe_ms_doc){
                    unlink('assets/menuscript/format_editor_ms/doc/'.$ckeck_ms->lead_fe_ms_doc);
                }
	            $location = "assets/menuscript/format_editor_ms/doc/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
	            /* Valid extensions */
	            $valid_extensions = array("pdf", "doc", "docx");
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
		             	$data_array = array(
            		'lead_fe_ms_doc'=>$filename,
            	);
            	$this->db->where('id',$id);
            	$this->db->update('tblleads',$data_array);
              
		               set_alert('success', _l('Doc MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }else{
	                set_alert('success', _l('Please select valid file.'));
		        		redirect($_SERVER['HTTP_REFERER']); 
	            }
           }
	}

	// upload_reworkms
	public function upload_reworkms(){
		$id = $_POST['lead_id'];
    	 $filename = $_FILES['file']['name'];
            $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
            	$filename = $ckeck_ms->lead_author_name.'_'.$filename;
                if($ckeck_ms->lead_fe_ms_file){
                    unlink('assets/menuscript/format_editor_ms/'.$ckeck_ms->lead_fe_ms_file);
                }
	            $location = "assets/menuscript/format_editor_ms/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
	            /* Valid extensions */
	            $valid_extensions = array("pdf", "doc", "docx","csv");
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
		             $data_array = array(
            		'lead_fe_ms_file'=>$filename,
            		'rework_fe_update_status'=>1,
            	);
            	$this->db->where('id',$id);
            	$this->db->update('tblleads',$data_array);
	            	set_alert('success', _l('MS uploaded successfully...'));
	        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }else{
	                set_alert('success', _l('Please select valid file.'));
		        		redirect($_SERVER['HTTP_REFERER']); 
	            }
           }
	}
	
	// List of Completed Project
	public function completed_project(){
		$this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where('project_status',11);
		$query = $this->db->get();
		$completed_data = $query->result();
	    $data['completed_data'] = $completed_data;
	    $this->load->view('admin/format_editor/completed_project',$data);
	} 
}


