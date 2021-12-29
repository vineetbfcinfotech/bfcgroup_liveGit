<?php
// code by Shivani 
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class Proof_reader extends Admin_controller{
	public function __construct(){
	    parent::__construct();
	    $this->load->model('proof_reader_modal');
	}
	// List of New Projects
	public function new_project(){
		$useraid = $this->session->userdata('staff_user_id');
		$data['title'] = "Proof Reader Dashboard";
		if (is_admin() || is_headtrm()) {
		$this->db->select('*');
		$this->db->from('tblleads');
		// $this->db->where_in('project_assign_to', $useraid);
		$this->db->where('project_status',5);
		$query = $this->db->get();
		$newproject_data = $query->result();
		$data['newproject_data'] = $newproject_data;
		}else{
			$this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where_in('project_assign_to', $useraid);
		$this->db->where('project_status',5);
		$query = $this->db->get();
		$newproject_data = $query->result();
		$data['newproject_data'] = $newproject_data;
		}
		$this->load->view('admin/proof_reader/new_project', $data);
	}

	// Change Product Status
	public function changeProjectStatus(){
		$project_id = $_POST['project_id'];
		$by = $this->session->userdata('staff_user_id');
		$author = $_POST['author_name'];
		$id = $_POST['user_id'];
		$proj_name = $_POST['proj_name'];

		$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
         ->from('tblstaff')
         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
          $this->db->where('tblstaff.staffid',$by);

		$query = $this->db->get();
		$ret = $query->row();

		$data = array(
			"project_status" => 6,
			"takeup_date" => date('Y-m-d H:i:s'),
		);
		// action array
		      	$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				          $this->db->where('tblleads.id',$id);
						$query_data = $this->db->get();
						$return = $query_data->row();
		$data1 = array(
			'notify_to'=> $return->pm_assign_to,
			'user_id'=> $id,
			'take_by'=> $by,
			'role' => $ret->name,
			'project_name' => $proj_name,
			'author_name' => $author,
			'action' => 1,
			'message' => $author.' Project taken by '.$ret->firstname.' '.$ret->lastname,
			'discription' => $author.' Project taken by '.$ret->firstname.' '.$ret->lastname,
		 );
		$update = $this->proof_reader_modal->changeProjectStatus($project_id, $data);
		echo $this->db->insert('lead_all_action',$data1);
	}


	// List of project in progress
	public function project_in_process(){
	    $this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where('project_status',6);
		if (is_admin() || is_headtrm()) {
		}else{
			$this->db->where('project_assign_to',$_SESSION['staff_user_id']);
		}
		$query = $this->db->get();
		$newproject_data1 = $query->result();
	    $data['newproject_data1'] = $newproject_data1;
	    $this->load->view('admin/proof_reader/project_in_process',$data);
	}

	// Update Pages after Team done page
	public function updatepages(){
		$id = $_POST['lead_id'];
		$pages1 = $_POST['completed_pages'];
		$workdone = $_POST['workpage'];
		$pages = $pages1+$workdone;
		if (empty($_POST['workpage']) || $pages <= $_POST['total_pages']) {
		$data1 = array(
			"lead_pr_completed_pages" => $pages,
		);
		$update = $this->proof_reader_modal->changePageStatus($id, $data1);
		set_alert('success', _l('Pages uploaded successfully...'));
		redirect($_SERVER['HTTP_REFERER']);
		}else{
			set_alert('success', _l('Completed pages can not be more than total pages...'));
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	// upload Ms by Proof Reader when done
	public function upload_ms(){
		$id = $_POST['lead_id'];
		$author_name = $_POST['author_name'];
		$book_name = $_POST['book_name'];
		$proj_name = $_POST['proj_name'];
		$by = $this->session->userdata('staff_user_id');

		$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
         ->from('tblstaff')
         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
          $this->db->where('tblstaff.staffid',$by);
		$query = $this->db->get();
		$ret = $query->row();

    	 $filename = $_FILES['file']['name'];
            $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
            	$filename = $ckeck_ms->lead_author_name.'_'.$filename;
            	
              
                if($ckeck_ms->lead_pr_ms_file){
                    unlink('assets/menuscript/proof_reader_ms/'.$ckeck_ms->lead_pr_ms_file);
                }
            $location = "assets/menuscript/proof_reader_ms/".$filename;
            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
            /* Valid extensions */
            $valid_extensions = array("pdf", "doc", "docx","csv");
            $response = 0;
            if(in_array(strtolower($imageFileType), $valid_extensions)) {
            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
             $response = $location;
             $data_array = array(
            		'lead_pr_ms_file'=>$filename,
            		'lead_upload_ms_date' => date('Y-m-d H:i:s'),
            	);
            	$this->db->where('id',$id);
            	$this->db->update('tblleads',$data_array);

            	// all action array
            	      	$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				          $this->db->where('tblleads.id',$id);
						$query_data = $this->db->get();
						$return = $query_data->row();
            	 $data1 = array(
            	 	'notify_to'=> $return->pm_assign_to,
			        'user_id'=> $id,
			        'take_by'=> $by,
					'role' => $ret->name,
					'project_name' => $proj_name,
					'author_name' => $author_name,
					'book_name' => $book_name,
					'action' => 9,
					'message' => $author_name.' work submitted by '.$ret->firstname.' '.$ret->lastname,
					'discription' => $author_name.' work submitted by '.$ret->firstname.' '.$ret->lastname,
				);
		        $this->db->insert('lead_all_action',$data1);

               set_alert('success', _l('MS uploaded successfully...'));
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
		$author_name = $_POST['author_name'];
		$book_name = $_POST['book_name'];
		$proj_name = $_POST['proj_name'];
		$by = $this->session->userdata('staff_user_id');

		$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
         ->from('tblstaff')
         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
          $this->db->where('tblstaff.staffid',$by);
		$query = $this->db->get();
		$ret = $query->row();

    	 $filename = $_FILES['file']['name'];
            $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
            	$filename = $ckeck_ms->lead_author_name.'_'.$filename;
            	
              
                if($ckeck_ms->lead_pr_ms_file){
                    unlink('assets/menuscript/proof_reader_ms/'.$ckeck_ms->lead_pr_ms_file);
                }
	            $location = "assets/menuscript/proof_reader_ms/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
	            /* Valid extensions */
	            $valid_extensions = array("pdf", "doc", "docx","csv");
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
		             $data_array = array(
            		'lead_pr_ms_file'=>$filename,
            		'rework_pr_update_status'=>1,
            	);
            	$this->db->where('id',$id);
            	$this->db->update('tblleads',$data_array);

            	// all action array
            	      	$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				          $this->db->where('tblleads.id',$id);
						$query_data = $this->db->get();
						$return = $query_data->row();
            	 $data1 = array(
            	 	'notify_to'=> $return->pm_assign_to,
			        'user_id'=> $id,
			        'take_by'=> $by,
					'role' => $ret->name,
					'project_name' => $proj_name,
					'author_name' => $author_name,
					'book_name' => $book_name,
					'action' => 13,
					'message' => $author_name.'Rework submitted by '.$ret->firstname.' '.$ret->lastname,
					'discription' => $author_name.'Rework submitted by '.$ret->firstname.' '.$ret->lastname,
				);
		        $this->db->insert('lead_all_action',$data1);
		        
		            	set_alert('success', _l('MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }else{
	                set_alert('success', _l('Please select valid file.'));
		        		redirect($_SERVER['HTTP_REFERER']);
	            }
           }else{
               set_alert('success', _l('MS not uploaded successfully'));
		        redirect($_SERVER['HTTP_REFERER']);
           }
	}

	 // List of Completed Project
	public function completed_project(){
		$this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where('assign_id_proof_reader',1);
		if (is_admin() || is_headtrm()) {}else{
			$this->db->where('project_assign_to_pr',$_SESSION['staff_user_id']);
		}
		
		
		$query = $this->db->get();
		$completed_data = $query->result();
	    $data['completed_data'] = $completed_data;
	    $this->load->view('admin/proof_reader/completed_project',$data);
	} 
		public function open_for_working_pages($value='')
	{
		$id = $this->input->post('id');
		$data = array(
			'download_ms_by_pr' => 1
		);
		$this->db->where('id',$id);
		$update = $this->db->update('tblleads',$data);
		if ($update) {
			echo '1';
		}else{
			echo '0';
		}

	}
		public function list_misc_project(){


		$data['title'] = "List MISC";
// print_r($_SESSION['staff_user_id']);exit;
	$to = $this->session->userdata('staff_user_id');
		$this->db->select('*');
	if (is_admin() || is_headtrm()) {}else{
$this->db->where('alloted_to',$to);
}
		$this->db->from('misc_project');

		$query = $this->db->get();
// echo $this->db->last_query();exit;
		$newproject_data = $query->result();

		$data['miscproject_data'] = $newproject_data;

		$this->load->view('admin/format_editor/misc_project', $data);

	}
	// upload MiscFile

	public function upload_misc(){

		$id = $_POST['misc_id'];
		
		$by = $this->session->userdata('staff_user_id');

    	 $filename = $_FILES['file']['name'];

           

            if ($filename) {

            	$filename = 'misc'.'_'.$filename;

            	$data_array = array(

            		'file_path'=>$filename,

            	);

            	$this->db->where('id',$id);

            	$this->db->update('misc_project',$data_array);

            	// save status for actions

            
              

                if($ckeck_ms->gd_cover_jpg){

                    unlink('assets/cover/misc/'.$ckeck_ms->gd_cover_jpg);

                }

	            $location = "assets/cover/misc/".$filename;

	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

	            $imageFileType = strtolower($imageFileType);

	            /* Valid extensions */

	            $valid_extensions = array("pdf", "doc", "docx", "png", "jpg", "jpeg", "webp","zip");

	            $response = 0;

	            if(in_array(strtolower($imageFileType), $valid_extensions)) {

		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

		             $response = $location;
		             $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
         ->from('tblstaff')
         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
          $this->db->where('tblstaff.staffid',$by);
		$query = $this->db->get();
		$ret = $query->row();
		             $this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				          $this->db->where('tblleads.id',$id);
						$query_data = $this->db->get();
						$return = $query_data->row();
            	$data1 = array(
            		'notify_to'=> $return->pm_assign_to,
		        'user_id'=> $id,
		        'take_by'=> $by,
				'role' => $ret->name,
				'project_name' => $proj_name,
				 'author_name' => $author_name,
				 'book_name' => $book_name,
				 'action' => 7,
				 'message' => $author_name.' MISC upload successfully by '.$ret->firstname.' '.$ret->lastname,
				 'discription' => $author_name.' MISC upload successfully by '.$ret->firstname.' '.$ret->lastname,
				);
		        $this->db->insert('lead_all_action',$data1);

		            	set_alert('success', _l('Uploaded successfully...'));

		        		redirect($_SERVER['HTTP_REFERER']);



		            }

	            }else{
	                	set_alert('success', _l('Please upload a valid file.'));

	        		redirect($_SERVER['HTTP_REFERER']);
	            }

           }else{
	                	set_alert('success', _l('Please upload a file'));

	        		redirect($_SERVER['HTTP_REFERER']);
	            }

	}
}