<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class ProjectCordinatorDashboard extends Admin_controller{
	public function __construct(){
	    parent::__construct();
	    $this->load->model('Project_Cordinator_Dashboard_modal');
	}
	
	/* =========  Add Service Page Code Like =========
	            - add service view page code (Using this function add_service())
	            - save data from add service page code (Using this function save_service())
            	- list all services page code (Using this function list_service())
            	- edit service page code 
            	- update service page code (Using this function update_service())
            	- delete service page code (Using this function delete_service())
	
	*/
	
	// Show Add Services View Page
	public function add_service(){
	    $data['title'] = "Add Service";
	    $this->load->view('admin/Project_Cordinator/add_service', $data);
	}
	// Save service paga data
	public function save_service(){
        $data = array(
            		"packageid" => $_POST['package'],
            		"service_name" => $_POST['Service'],
            		);
        $added = $this->Project_Cordinator_Dashboard_modal->save_service($data);
	}
	// Update Service View Page
	public function update_service(){
	    $serviceId = $_POST['serviceId'];
        $data = array(
            "packageid" => $_POST['packageId'],
            "service_name" => $_POST['serviceName'],
        );
        $update = $this->Project_Cordinator_Dashboard_modal->update_service($serviceId,$data);
        if($update){
            redirect('admin/ProjectCordinatorDashboard/list_service'); 
        }else{
            echo "fails";
        }
        
	}
	// Get Service List
	public function get_service_list(){
        $package_id = $_POST['package_id']; 
        $added = $this->Project_Cordinator_Dashboard_modal->get_service_list($package_id);
	}
	// List All Services
	public function list_service(){
        $data['title'] = "Add Service";
        $data['services']= $this->db->select('*')->get('tblpackageservices')->result();
        $this->load->view('admin/Project_Cordinator/list_service', $data);
	}
	//Delete single service accoding to id
	public function delete_service(){
	    $serviceId = $_POST['serviceId']; 
        $added = $this->Project_Cordinator_Dashboard_modal->delete_service($serviceId);
	}
	
	
		/* =========  Add Sub Service Page Code Like =========
	            - add service view page code (Using this function add_subService())
	            - save data from add service page code (Using this function save_subService())
            	- list all services page code (Using this function list_subService())
            	- edit service page code 
            	- update service page code (Using this function update_subService())
            	- delete service page code (Using this function delete_subService())
	
	*/
		// Show Add Services View Page
	public function add_subservice(){
	    $data['title'] = "Add SubService";
	    $data['subservice']=$this->db->select('id,service_name')->get('tblpackageservices')->result();
	    $this->load->view('admin/Project_Cordinator/add_subservice', $data);
	}
	// Save service paga data
	public function save_subservicedata(){
        $data = array(
            		"cost" => $_POST['cost'],
            		"subservice_name" => $_POST['subServiceName'],
            		"subServiceNameValue" => $_POST['subServiceNameValue'],
            		"package_value" => $_POST['packageType'],
            		"book_type" => $_POST['bookType'],
            		"serviceid" => $_POST['Service'],
            		"packageid" => $_POST['Package'],
            		);
            		
        //print_r($data);exit;    		
        $added = $this->Project_Cordinator_Dashboard_modal->save_subservice($data);
        if($added){
            redirect('admin/ProjectCordinatorDashboard/add_subservice'); 
        }else{
            echo "fails";
        }
	}
	// List All subServices
	public function subservice_list(){
	    $data['title'] = "List Sub Service";
	    $this->db->select('tss.*,ts.service_name ');
        $this->db->join('tblpackageservices as ts', 'tss.serviceid=ts.id');
        $data['subservices']=$this->db->get('tblpackagesubservices as tss')->result();
        $this->load->view('admin/Project_Cordinator/list_subservices', $data);
	}
	// Update subService View Page
	public function update_subservice(){
	   
	   $subserviceId=$_POST['subserviceId'];
        $data = array(
            
            "packageid"=>$_POST['Package'],
            "serviceid"=>$_POST['service'],
            "book_type"=>$_POST['bookType'],
            "package_value"=>$_POST['packageType'],
            "subservice_name" => $_POST['subserviceName'],
            "cost" => $_POST['cost'],
        );
        //print_r($data);
        //exit;
        $update = $this->Project_Cordinator_Dashboard_modal->update_subservice($subserviceId,$data);
        if($update){
            redirect('admin/ProjectCordinatorDashboard/subservice_list'); 
        }else{
            echo "fails";
        }
        
	}
	//Delete single subservice accoding to id
	public function delete_subservice(){
	    $serviceId = $_POST['serviceId']; 
        $added = $this->Project_Cordinator_Dashboard_modal->delete_subservice($serviceId);
	}
	//Get services based on package id
	public function get_service(){
        $serviceId = $_POST['serviceId']; 
        $added = $this->Project_Cordinator_Dashboard_modal->get_services($serviceId);
        echo json_encode($added);
	}
	
	// Edit page View
	public function edit_subservice(){
        $subserviceId = end($this->uri->segment_array());
        $data['title'] = "Add Service";
        $this->db->where('id', $subserviceId);
        $this->db->select('*');
        $result = $this->db->get('tblpackagesubservices')->result();
        $data['subservice']=$result;
        $serviceId = $result[0]->packageid;
        
        $this->db->where('packageid', $serviceId);
        $this->db->select('*');
        $result1 =$this->db->get('tblpackageservices')->result();
        //print_r($resul1);exit;
        $data['subservicelist']=$result1;
        
        $this->load->view('admin/Project_Cordinator/edit_subservice', $data); 
	}
	
	public function assigned_pc($value='')
	{
		$pc_id = 64;
		$pm_id = 50;
		 $list['data'] = $this->Project_Cordinator_Dashboard_modal->get_pc($pc_id);
		 $list['pm_data'] = $this->Project_Cordinator_Dashboard_modal->get_pm($pm_id);
		//  print_r( $list['pm_data']);die;
        $this->load->view('admin/Project_Cordinator/assign_pc',$list); 
		
	}
	public function assign_pc_to_pm($value='')
	{
		$id = $this->input->post('hidden_id');
		$get_prev_assigned_id = $this->db->select('pm_assign_to')->get_where('tblstaff',array('staffid'=>$id))->row();
		$prev_assigned_id = $get_prev_assigned_id->pm_assign_to;
		$pre_data  = array(
			'pre_pm_assign_to' => $prev_assigned_id			
		);
		$this->db->where('staffid',$this->input->post('hidden_id'));
		$this->db->update('tblstaff',$pre_data);

		

		$data  = array(
			'pm_assign_to' => $this->input->post('pm_assign'),
			'pm_assign_date' => Date('Y-m-d')
		);
		$this->db->where('staffid',$this->input->post('hidden_id'));
		$update = $this->db->update('tblstaff',$data);
		// echo $this->db->last_query();die;
		if ($update) {
			$pc_details = $this->db->get_where('tblstaff',array('staffid'=>$this->input->post('hidden_id')))->row();
			$pm_details = $this->db->get_where('tblstaff',array('staffid'=>$this->input->post('pm_assign')))->row();
						$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				          $this->db->where('tblleads.id',$id);
						$query_data = $this->db->get();
						$return = $query_data->row();
						$by = $this->session->userdata('staff_user_id');
						$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
				         ->from('tblstaff')
				         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
				          $this->db->where('tblstaff.staffid',$by);
						$query = $this->db->get();
						$ret = $query->row();
						     // echo $this->db->last_query();die;
		            	 $data1 = array(
		            	 	'notify_to'=> $return->pm_assign_to,
					        'user_id'=> $id,
					        'take_by'=> $by,
							'role' => $ret->name,
							'project_name' => '',
							'author_name' => '',
							'book_name' => '',
							'action' => 16,
							'message' => 'Pc Assign successfully',
							'discription' => 'PC '.$pc_details->firstname.' '.$pc_details->lastname.'is assign to the Pm '.$pm_details->firstname.' '.$pm_details->lastname,
						);
				        $this->db->insert('lead_all_action',$data1);
				       // echo $this->db->last_query();die;
			set_alert('success', _l('Update succesfully'));
		redirect($_SERVER['HTTP_REFERER']);
		}else{
			set_alert('danger', _l('Something went wrong'));
		redirect($_SERVER['HTTP_REFERER']);
		}

	}
	public function generate_link(){
		$list['genrate_printer_link'] = $this->db->get('genrate_printer_link')->result();
		$this->load->view('admin/pco/printer_link',$list); 
	}
	public function upload_printer_link(){
	
			$filename = $_FILES['file']['name'];
			$today = date("Y-m-d H:i:s");
            $filename = $today.''.$filename;

			ini_set('upload_max_filesize', '900M');
			ini_set('post_max_size', '900M');                               
			ini_set('max_input_time', 9000);                                
			ini_set('max_execution_time', 9000);
		
		$location = "assets/printer_genrate_link/".$filename;
		$imageFileType = pathinfo($location,PATHINFO_EXTENSION);
		$imageFileType = strtolower($imageFileType);

		$response = 0;
		if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		 $response = $location;
		}
		$data_array = array(
			'file' => $filename,
		);
		
		$this->db->insert('genrate_printer_link',$data_array);
		set_alert('success', _l('Answer upload successfully...'));
		redirect($_SERVER['HTTP_REFERER']);
	}
	public function delete_link($id=""){
		if ($id) {
			// echo $id;die;
			$ckeck_image = $this->db->get_where('genrate_printer_link',array('id'=>$id))->row();
              
                    if($ckeck_image->file){
                        unlink('assets/printer_genrate_link/'.$ckeck_image->file);
                    }
			$this->db->where('id',$id);
			$this->db->delete('genrate_printer_link');
			set_alert('success', _l('File Delete successfully...'));
		redirect($_SERVER['HTTP_REFERER']);

		}else{
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	


}


