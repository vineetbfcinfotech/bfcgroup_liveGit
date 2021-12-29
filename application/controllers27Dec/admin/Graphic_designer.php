<?php


header('Content-Type: text/html; charset=utf-8');

defined('BASEPATH') or exit('No direct script access allowed'); 

class Graphic_designer extends Admin_controller{

	public function __construct(){

	    parent::__construct();

	    $this->load->model('graphic_designer_modal');

	}



	// List of New Projects

	public function new_project(){


		$data['title'] = "Graphic Designer Dashboard";
// print_r($_SESSION['staff_user_id']);exit;
		$this->db->select('*');

		$this->db->from('tblleads');

		$this->db->where('project_status_gd',1);
			if (is_admin() || is_headtrm()) {}else{
		$this->db->where('lead_asf_assign_gd',$_SESSION['staff_user_id']);
	}


		$query = $this->db->get();
// echo $this->db->last_query();exit;
		$newproject_data = $query->result();

		$data['newproject_data'] = $newproject_data;

		$this->load->view('admin/graphic_designer/new_project', $data);

	}

	// Change Product Status as Takeup

	public function changeProjectStatus(){
		$project_id = $_POST['project_id'];
		$by = $this->session->userdata('staff_user_id');

		
	$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
     ->from('tblstaff')
     ->join('tblroles', 'tblstaff.role = tblroles.roleid');
      $this->db->where('tblstaff.staffid',$by);

		$query = $this->db->get();
		$ret = $query->row();

		$author = $_POST['author_name'];
		$id = $_POST['user_id'];
		$proj_name = $_POST['proj_name'];
		$data = array(
			"project_status_gd" => 2,
			"takeup_date" => date('Y-m-d H:i:s'),
		);
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

		$update = $this->graphic_designer_modal->changeProjectStatus($project_id, $data);

		$this->db->insert('lead_all_action',$data1);
	}



	// List of project in progress

	public function project_in_process(){

	    $this->db->select('*');

		$this->db->from('tblleads');
			if (is_admin() || is_headtrm()) {}else{
				$this->db->where('lead_asf_assign_gd',$_SESSION['staff_user_id']);
			}

		// $this->db->where('project_status !=',15);
		// $this->db->where('project_status >',14);
		$this->db->where('project_status_gd',2);
		$this->db->or_where('project_status_gd',4);
		$this->db->or_where('project_status_gd',5);
	    //   $where = '(project_status_gd != 3 or gd_ad_work_status != 3)';
	    //   $this->db->where($where);
		
		$this->db->order_by('takeup_date','desc');

		$query = $this->db->get();

		$newproject_data1 = $query->result();

	    $data['newproject_data1'] = $newproject_data1;

	    $this->load->view('admin/graphic_designer/project_in_process',$data);

	}



	// upload ASF by Graphic Designer 

	public function upload_cover(){

		$id = $_POST['lead_id'];
		$rework_id = $_POST['rework_id'];

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

                if($ckeck_ms->gd_cover){

                    unlink('assets/cover/gd_cover/'.$ckeck_ms->gd_cover);

                }

	            $location = "assets/cover/gd_cover/".$filename;

	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

	            $imageFileType = strtolower($imageFileType);

	            /* Valid extensions */

	            $valid_extensions = array("pdf", "doc", "docx", "png", "jpg", "jpeg", "webp", "gif");

	            $response = 0;

	            if(in_array(strtolower($imageFileType), $valid_extensions)) {

		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

		             $response = $location;
		             if ($rework_id==2) {
		             	$data_array = array(

                		'gd_cover'=>$filename,

                		

                		'lead_upload_cover_date' => date('Y-m-d H:i:s'),

                	);
		             }else{
		             	$data_array = array(

                		'gd_cover'=>$filename,

                		'gd_work_status'=>1,

                		'lead_upload_cover_date' => date('Y-m-d H:i:s'),

                	);
		             }
		             

                	$this->db->where('id',$id);

                	$this->db->update('tblleads',$data_array);

                	$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				          $this->db->where('tblleads.id',$id);
						$query_data = $this->db->get();
						$return = $query_data->row();
						// print_r($return);die;
                	 $data1 = array(
                	 'notify_to'=> $return->pm_assign_to,
			        'user_id'=> $id,
			        'take_by'=> $by,
					'role' => $ret->name,
					'project_name' => $proj_name,
					 'author_name' => $author_name,
					 'book_name' => $book_name,
					 'action' => 2,
					 'message' => $author_name.' work submitted by '.$ret->firstname.' '.$ret->lastname,
					 'discription' => $author_name.' work submitted by '.$ret->firstname.' '.$ret->lastname,
					);
			        $this->db->insert('lead_all_action',$data1);

	            	set_alert('success', _l('Cover uploaded successfully...'));

	        		redirect($_SERVER['HTTP_REFERER']);



		            }

	            }else{

						set_alert('success', _l('Please upload a valid file.'));

						redirect($_SERVER['HTTP_REFERER']);

	            	}

            	}else{

                set_alert('success', _l('Please upload a file.'));

            	redirect($_SERVER['HTTP_REFERER']);

        	}

	}

	// upload upload_additional_cover

	public function upload_additional_cover(){

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

            	$data_array = array(

            		'gd_additional_cover'=>$filename,

            		// 'gd_ad_work_status'=> 1,

            	);

            	$this->db->where('id',$id);

            	$this->db->update('tblleads',$data_array);
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
				 'action' => 3,
				 'message' => $author_name.' additional work submitted by '.$ret->firstname.' '.$ret->lastname,
				 'discription' => $author_name.' additional work submitted by '.$ret->firstname.' '.$ret->lastname,
				);
		        $this->db->insert('lead_all_action',$data1);

              

                if($ckeck_ms->gd_additional_cover){

                    unlink('assets/cover/additional_cover/'.$ckeck_ms->gd_additional_cover);

                }

	            $location = "assets/cover/additional_cover/".$filename;

	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

	            $imageFileType = strtolower($imageFileType);

	            /* Valid extensions */

	            $valid_extensions = array("pdf", "doc", "docx", "png", "jpg", "jpeg", "webp");

	            $response = 0;

	            if(in_array(strtolower($imageFileType), $valid_extensions)) {

		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

		             $response = $location;

		            	set_alert('success', _l('Additional Cover uploaded successfully...'));

		        		redirect($_SERVER['HTTP_REFERER']);



		            }

	            }

           }

	}

	// upload Creative from GD

	public function upload_creative(){

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

            	$data_array = array(

            		'gd_creative'=>$filename,

            		'lead_gd_creative_status'=>1,

            	);

            	$this->db->where('id',$id);

            	$this->db->update('tblleads',$data_array);
            	// save status for actions
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
				 'action' => 4,
				 'message' => $author_name.' Creative work submitted by '.$ret->firstname.' '.$ret->lastname,
				 'discription' => $author_name.' Creative work submitted by '.$ret->firstname.' '.$ret->lastname,
				);
		        $this->db->insert('lead_all_action',$data1);

              

                if($ckeck_ms->gd_creative){

                    unlink('assets/cover/creative/'.$ckeck_ms->gd_creative);

                }

	            $location = "assets/cover/creative/".$filename;

	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

	            $imageFileType = strtolower($imageFileType);

	            /* Valid extensions */

	            $valid_extensions = array("pdf", "doc", "docx", "png", "jpg", "jpeg", "webp");

	            $response = 0;

	            if(in_array(strtolower($imageFileType), $valid_extensions)) {

		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

		             $response = $location;

		            	set_alert('success', _l('Creative uploaded successfully...'));

		        		redirect($_SERVER['HTTP_REFERER']);



		            }

	            }

           }

	}



	// List of Completed Project

	public function completed_project(){
		$this->db->select('*');
		$this->db->from('tblleads');
		$this->db->where('gd_ad_work_status', 3);
		$this->db->or_where('gd_work_status', 3);
		// $this->db->where('project_status',15);
		$this->db->where('lead_asf_assign_gd',$_SESSION['staff_user_id']);
		$query = $this->db->get();
		$completed_data = $query->result();
	    $data['completed_data'] = $completed_data;
	    $this->load->view('admin/graphic_designer/completed_project',$data);

	} 



	// upload_book_cover

	public function upload_book_cover(){
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
                if($ckeck_ms->gd_cover_jpg){
                    unlink('assets/cover/final_cover/cover_jpg/'.$ckeck_ms->gd_cover_jpg);
                }
	            $location = "assets/cover/final_cover/cover_jpg/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);
	            /* Valid extensions */
	            $valid_extensions = array("pdf", "jpg", "png", "jpg", "jpeg","webp");
	            $response = 0;
	            if(in_array(strtolower($imageFileType), $valid_extensions)) {
		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		             $response = $location;
		             $data_array = array(
                		'lead_gd_final_cover'=>$filename,
                		'lead_upload_cover_date' => date('Y-m-d H:i:s'),
                	);
                	$this->db->where('id',$id);
                	$this->db->update('tblleads',$data_array);
                	// save status for actions
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
				'action' => 5,
				'message' => $author_name.' Final Book Cover submitted by '.$ret->firstname.' '.$ret->lastname,
				'discription' => $author_name.' Final Book Cover submitted by '.$ret->firstname.' '.$ret->lastname,
				);
		         $this->db->insert('lead_all_action',$data1);
	            	set_alert('success', _l('Cover uploaded successfully...'));
	        		redirect($_SERVER['HTTP_REFERER']);
		        }
	            }else{
	                set_alert('success', _l('Please upload a valid file.'));
	        		redirect($_SERVER['HTTP_REFERER']);
	            }
           }else{
	                set_alert('success', _l('Please upload a file.'));
	        		redirect($_SERVER['HTTP_REFERER']);
	            }
	}

	// upload_cover_front

	public function upload_cover_front(){

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
                if($ckeck_ms->gd_cover_jpg){
                    unlink('assets/cover/final_cover/front_cover/'.$ckeck_ms->gd_cover_jpg);
                }
	            $location = "assets/cover/final_cover/front_cover/".$filename;
	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	            $imageFileType = strtolower($imageFileType);

	            /* Valid extensions */

	            $valid_extensions = array("pdf", "doc", "docx", "png", "jpg", "jpeg", "webp");

	            $response = 0;

	            if(in_array(strtolower($imageFileType), $valid_extensions)) {

		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

		             $response = $location;
		             $data_array = array(

            		'lead_gd_final_front'=>$filename,

            	);

            	$this->db->where('id',$id);

            	$this->db->update('tblleads',$data_array);

            	// save status for actions
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
				 'action' => 6,
				 'message' => $author_name.' Final Front Cover submitted by '.$ret->firstname.' '.$ret->lastname,
				 'discription' => $author_name.' Final Front Cover submitted by '.$ret->firstname.' '.$ret->lastname,
				);
		        $this->db->insert('lead_all_action',$data1);

		            	set_alert('success', _l('Cover uploaded successfully...'));

		        		redirect($_SERVER['HTTP_REFERER']);



		            }

	            }else{
	                	set_alert('success', _l('Please upload a valid file.'));

	        		redirect($_SERVER['HTTP_REFERER']);
	            }

           }else{
	                	set_alert('success', _l('Please upload a file.'));

	        		redirect($_SERVER['HTTP_REFERER']);
	            }

	}



	// upload_cover_back

	public function upload_cover_back(){

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

            	

              

                if($ckeck_ms->gd_cover_jpg){

                    unlink('assets/cover/final_cover/back_cover/'.$ckeck_ms->gd_cover_jpg);

                }

	            $location = "assets/cover/final_cover/back_cover/".$filename;

	            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

	            $imageFileType = strtolower($imageFileType);

	            /* Valid extensions */

	            $valid_extensions = array("pdf", "doc", "docx", "png", "jpg", "jpeg","webp");

	            $response = 0;

	            if(in_array(strtolower($imageFileType), $valid_extensions)) {

		            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

		             $response = $location;
		             	$data_array = array(

            		'lead_gd_final_back'=>$filename,

            	);

            	$this->db->where('id',$id);

            	$this->db->update('tblleads',$data_array);
            	// save status for actions
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
				 'message' => $author_name.' Final Back Cover submitted by '.$ret->firstname.' '.$ret->lastname,
				 'discription' => $author_name.' Final Back Cover submitted by '.$ret->firstname.' '.$ret->lastname,
				);
		        $this->db->insert('lead_all_action',$data1);

		            	set_alert('success', _l('Cover uploaded successfully...'));

		        		redirect($_SERVER['HTTP_REFERER']);



		            }

	            }else{
	                	set_alert('success', _l('Please upload a valid file.'));

	        		redirect($_SERVER['HTTP_REFERER']);
	            }

           }else{
	                	set_alert('success', _l('Please upload a file.'));

	        		redirect($_SERVER['HTTP_REFERER']);
	            }

	}



	// List of MISC Project

	public function misc_project(){

		$this->db->select('*');

		$this->db->from('tblleads');

		$this->db->where('lead_gd_misc_status',1);

		$query = $this->db->get();

		$completed_data = $query->result();

	    $data['misc_data'] = $completed_data;

	    $this->load->view('admin/graphic_designer/misc_project',$data);

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

		            	set_alert('success', _l('Cover uploaded successfully...'));

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

		$this->load->view('admin/graphic_designer/misc_project', $data);

	}



}