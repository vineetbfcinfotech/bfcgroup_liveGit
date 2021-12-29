<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class Format_editor extends Admin_controller{
	public function __construct(){
	    parent::__construct();
	    $this->load->model('format_editor_modal');
	}
	 // List of New Projects
	public function new_project(){
		$data['title'] = "Format Editor Dashboard";
		$this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where('project_status',9);
		if (is_admin() || is_headtrm()) {}else{
		$this->db->where('project_assign_to',$_SESSION['staff_user_id']);
	}
		$query = $this->db->get();
		$newproject_data = $query->result();
		$data['newproject_data'] = $newproject_data;
		$this->load->view('admin/format_editor/new_project', $data);
	}

	 // Change Product Status
	public function changeProjectStatus(){
		$project_id = $_POST['project_id'];
		$by = $this->session->userdata('staff_user_id');
		$author = $_POST['author_name'];
		$id = $_POST['user_id'];
		$proj_name = $_POST['proj_name'];

		$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				          $this->db->where('tblleads.id',$id);
						$query_data = $this->db->get();
						$return = $query_data->row();
						
		$this->db->select('tblstaff.staffid, tblstaff.role, tblroles.*')
         ->from('tblstaff')
         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
          $this->db->where('tblstaff.staffid',$by);
		$query = $this->db->get();
		$ret = $query->row();
		$data1 = array(
			'notify_to'=> $return->pm_assign_to,
			'user_id'=> $id,
			'take_by'=> $by,
			'role' => $ret->name,
			'project_name' => $proj_name,
			'author_name' => $author,
			'action' => 1,
			'message' => $author.' Project taken by Format editor ',
			'discription' => $author.' Project taken by Format editor',
		 );
		
		$this->db->insert('lead_all_action',$data1);


		$data = array(
			"project_status" => 10,
			"takeup_date" => date('Y-m-d H:i:s'),
		);
		echo $update = $this->format_editor_modal->changeProjectStatus($project_id, $data);	
		//redirect($_SERVER['HTTP_REFERER']);
	}
	// download_status
	public function download_status(){
		$id = $_POST['lead_id'];
		$data = array(
			"lead_download_status" => 1,
		);
		$update = $this->format_editor_modal->changeProjectStatus($id, $data);
		redirect($_SERVER['HTTP_REFERER']);
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
		set_alert('success', _l('Pages update successfully...'));
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
		if (is_admin() || is_headtrm()) {}else{
		$this->db->where('project_assign_to',$_SESSION['staff_user_id']);
	}
		$query = $this->db->get();
		$newproject_data1 = $query->result();
	    $data['newproject_data1'] = $newproject_data1;
	    $this->load->view('admin/format_editor/project_in_process',$data);
	}

	// upload Ms by Format Editor when done
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

						// all action array
						$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				          $this->db->where('tblleads.id',$id);
						$query_data = $this->db->get();
						$return = $query_data->row();
					
						$by = $this->session->userdata('staff_user_id');

						$this->db->select('tblstaff.staffid, tblstaff.role, tblroles.*')
				         ->from('tblstaff')
				         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
				          $this->db->where('tblstaff.staffid',$by);
						$query = $this->db->get();
						$ret = $query->row();
		            	 $data1 = array(
		            	 	'notify_to'=> $return->pm_assign_to,
					        'user_id'=> $id,
					        'take_by'=> $by,
							'role' => $ret->name,
							'project_name' => $proj_name,
							'author_name' => $author_name,
							'book_name' => $book_name,
							'action' => 9,
							'message' => $author.' work submitted by Format editor',
							'discription' => $author.' work submitted by Format editor',
						);
				        $this->db->insert('lead_all_action',$data1);
              
		                if($ckeck_ms->lead_fe_ms_file){
		                    unlink('assets/menuscript/format_editor_ms/'.$ckeck_ms->lead_fe_ms_file);
		                }
			            $response = $location;
		            	set_alert('success', _l('MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);
		            }
	            }else{
	            	 set_alert('worning', _l('Please select a valid file.'));
                    redirect($_SERVER['HTTP_REFERER']); 
	            }
           }else{
            set_alert('worning', _l('Please select a file.'));
            redirect($_SERVER['HTTP_REFERER']);
        
        }
	}
	// upload Ms ebook PDF by Format Editor when done
	public function upload_ms_ebookpdf(){
		$id = $_POST['lead_id'];
		$author_name = $_POST['author_name'];
		$book_name = $_POST['book_name'];
		$proj_name = $_POST['proj_name'];
		$by = $this->session->userdata('staff_user_id');

	$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
         ->from('tblstaff')
         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
         // if (is_admin() || is_headtrm()) {}else{
          $this->db->where('tblstaff.staffid',$by);
      // }
		$query = $this->db->get();
		$ret = $query->row();

    	 $filename = $_FILES['file']['name'];
    	 	$valid_extensions = array("pdf");
    	 	 $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
			$filename = $ckeck_ms->lead_author_name.'_'.$filename;
            	$location = "assets/menuscript/format_editor_ms/ebook/".$filename;
         	$imageFileType = pathinfo($location,PATHINFO_EXTENSION);
        
        	$imageFileType = strtolower($imageFileType);
           

            if ($filename) {
            	
            	if(in_array($imageFileType, $valid_extensions)){
            	
            	
	           
	            /* Valid extensions */
	           
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		        	if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
			             $data_array = array(
	            			'lead_fe_ms_ebook'=>$filename,
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
							'action' => 10,
							'message' => $author_name.'Final file uploaded by'.$ret->firstname.' '.$ret->lastname,
							'discription' => $author_name.'Final file uploaded by'.$ret->firstname.' '.$ret->lastname,
						);
				        $this->db->insert('lead_all_action',$data1);
		              
		                if($ckeck_ms->lead_fe_ms_ebook){
		                    unlink('assets/menuscript/format_editor_ms/ebook/'.$ckeck_ms->lead_fe_ms_ebook);
		                }
		               set_alert('success', _l('eBook MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }
	        }
	        else{
	        	set_alert('danger', _l('Invalid File extenstion...'));
		        		redirect($_SERVER['HTTP_REFERER']);
	        }
           }else{
	        	set_alert('danger', _l('Please select a file'));
		        		redirect($_SERVER['HTTP_REFERER']);
	        }
	}
	// upload Ms Paperback PDF by Format Editor when done
	public function upload_ms_paperback(){
		$id = $_POST['lead_id'];
		$author_name = $_POST['author_name'];
		$book_name = $_POST['book_name'];
		$proj_name = $_POST['proj_name'];
		$by = $this->session->userdata('staff_user_id');

		$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
         ->from('tblstaff')
         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
         // if (is_admin() || is_headtrm()) {}else{
          $this->db->where('tblstaff.staffid',$by);
      // }
		$query = $this->db->get();
		$ret = $query->row();

    	 $filename = $_FILES['file']['name'];
    	 $valid_extensions = array("pdf");
    	   $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
    	 		$filename = $ckeck_ms->lead_author_name.'_'.$filename;
            	$location = "assets/menuscript/format_editor_ms/paperback/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
          
            if ($filename) {
            	if(in_array($imageFileType, $valid_extensions)){
            	
	            /* Valid extensions */
	            
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
		             $data_array = array(
            			'lead_fe_ms_paperback'=>$filename,
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
						'action' => 11,
						'message' => $author_name.'MS Uploded',
						'discription' => $author_name.'MS Uploded',
					);
			        $this->db->insert('lead_all_action',$data1);
	              
		                if($ckeck_ms->lead_fe_ms_paperback){
		                    unlink('assets/menuscript/format_editor_ms/paperback/'.$ckeck_ms->lead_fe_ms_paperback);
		                }
		               set_alert('success', _l('Paperback MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }
	        }
	        else{
	        	set_alert('danger', _l('Invalid File extenstion...'));
		        		redirect($_SERVER['HTTP_REFERER']);
	        }
           }else{
	        	set_alert('danger', _l('Please select a file'));
		        		redirect($_SERVER['HTTP_REFERER']);
	        }
	}
	// upload Ms Doc by Format Editor when done
	public function upload_ms_doc(){
		$id = $_POST['lead_id'];
		$author_name = $_POST['author_name'];
		$book_name = $_POST['book_name'];
		$proj_name = $_POST['proj_name'];
		$by = $this->session->userdata('staff_user_id');

		$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
         ->from('tblstaff')
         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
         // if (is_admin() || is_headtrm()) {}else{
          $this->db->where('tblstaff.staffid',$by);
      // }
		$query = $this->db->get();
		$ret = $query->row();

    	 $filename = $_FILES['file']['name'];
    	 $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
    	 $filename = $ckeck_ms->lead_author_name.'_'.$filename;
    	 $location = "assets/menuscript/format_editor_ms/doc/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
	            /* Valid extensions */
	            $valid_extensions = array("doc", "docx");
            
            if ($filename) {
            	if(in_array($imageFileType, $valid_extensions)){
            	
            	
	            
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
		             $data_array = array(
            			'lead_fe_ms_doc'=>$filename,
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
						'action' => 12,
						'message' =>$author_name.'Doc file uploaded by'.$ret->firstname.' '.$ret->lastname,
						'discription' => $author_name.'Doc file uploaded by'.$ret->firstname.' '.$ret->lastname,
					);
			        $this->db->insert('lead_all_action',$data1);
	              
	                if($ckeck_ms->lead_fe_ms_doc){
	                    unlink('assets/menuscript/format_editor_ms/doc/'.$ckeck_ms->lead_fe_ms_doc);
	                }
		               set_alert('success', _l('Doc MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }
	        }
	        else{
	        	set_alert('danger', _l('Invalid File extenstion...'));
		        		redirect($_SERVER['HTTP_REFERER']);
	        }
           }else{
	        	set_alert('danger', _l('Please select a file'));
		        		redirect($_SERVER['HTTP_REFERER']);
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
         // if (is_admin() || is_headtrm()) {}else{
          $this->db->where('tblstaff.staffid',$by);
      // }
		$query = $this->db->get();
		$ret = $query->row();

    	 $filename = $_FILES['file']['name'];
            $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
            	$filename = $ckeck_ms->lead_author_name.'_'.$filename;
            
	            $location = "assets/menuscript/format_editor_ms/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
	            /* Valid extensions */
	            $valid_extensions = array("pdf", "doc", "docx");
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
		              
		                if($ckeck_ms->lead_fe_ms_file){
		                    unlink('assets/menuscript/format_editor_ms/'.$ckeck_ms->lead_fe_ms_file);
		                }
		            	set_alert('success', _l('MS uploaded successfully...'));
		        		redirect($_SERVER['HTTP_REFERER']);

		            }
	            }else{
	        	set_alert('danger', _l('Please select a valid file'));
		        		redirect($_SERVER['HTTP_REFERER']);
	        }
           }else{
	        	set_alert('danger', _l('Please select a file'));
		        		redirect($_SERVER['HTTP_REFERER']);
	        }
	}

	    // List of Completed Project
	public function completed_project(){
		$this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where('assign_id_format_editer', 1);
		if (is_admin() || is_headtrm()) {}else{
		$this->db->where('project_assign_to_fe',$_SESSION['staff_user_id']);
	}

		$query = $this->db->get();
		$completed_data = $query->result();
		// echo $this->db->last_query();
	    $data['completed_data'] = $completed_data;
	    $this->load->view('admin/format_editor/completed_project',$data);
	} 
	public function open_for_working_pages($value='')
	{
		// Initialize a file URL to the variable
		
// 		$url = $this->input->post('href_link_new');
		
		
// echo $url; exit;
// $file_name = basename($url);

// if(file_put_contents( $file_name,file_get_contents($url))) {
//     echo "File downloaded successfully";
// }
// else {
//     echo "File downloading failed.";
// }
		// echo "string";exit;
		$id = $this->input->post('id');
		$data = array(
			'download_ms_by_fe' => 1
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


