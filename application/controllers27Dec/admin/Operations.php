<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Operations extends Admin_controller
{
    private $ci;

    public function __construct()
    {
        parent::__construct();
        
        $this->ci = &get_instance();
        $this->load->model('reports_model');
        $this->load->model('leads_model');
        $this->load->model('staff_model');
        $this->load->model('product_model','pmodel');
        $this->load->model('operations_model','omodel');
        
    }
    
    
    public function task_list()
    {
        
        
        if ($this->input->post()) {
           
            
            if (!$this->input->post('id')) {
                $id = $this->omodel->add_task($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', "Task"));
                }
            } else {
               /* alert();
           exit;*/
                
                $upd['name']=$_POST['name'];
                $upd['frequency']=$_POST['frequency'];
                $upd['date_from']=$_POST['date_from'];
                $upd['date_to']=$_POST['date_to'];
                $upd['months']=$_POST['months'];
                foreach ($_POST['months'] as $rateingvalue)
                {  
                  $upd['months'] .= $rateingvalue.",";
                }
                
                $upd['months'] = str_replace("Array","",$upd['months']);
                
               
                $id=$_POST['id'];
                
                
                $success = $this->omodel->updatetask('tbltasklist',$upd, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', "Task"));
                }
                
                
            }
            die;
        }
        $data['companies'] = $this->omodel->get_tasks();
        $data['title'] = "Task List";
        $this->load->view('admin/operations/task_list', $data);
    
    }
    
    public function delete($id)
    {
        $this->omodel->delete('tbltasklist',$id);
        set_alert('success', _l('Deleted Successfully', "Task"));
        redirect(admin_url('operations/task_list'));
    }
    
    public function active($type,$id,$status)
    {
        if ($this->input->is_ajax_request()) {
            switch ($type) {
                case 'task':
                    $this->omodel->change_status('tbltasklist',$id,$status);
                    break;
                default:
                    # code...
                    break;
            }
        }
    }
    
    public function assign_task()
    {
        $this->db->select('tbltasklist.*');
        $data['tasks']=$this->db->get('tbltasklist')->result();
        //$data['tasks'] = $this->omodel->get_tasks();
        
        $data['staffs'] = $this->staff_model->get('', ['active' => 1, 'department_id' => 15]);
        //print_r($data['staff']);
        $data['title'] = "Assign Task";
        $this->load->view('admin/operations/assign_task', $data);
    }
    
     public function task_assign_update()
    {
      $assigned =   $_GET['assigned']; 
      $task_id = $_GET['task_id'];
      $data = array('assigned' => $assigned);
      
      $this->db->where('id', $task_id);
      $this->db->update('tbltasklist', $data);
      set_alert('success',  "Task Assigned Successfully");
    }
    
    public function submit_task_report()
    {
         if ($this->input->post())
         {
             $staffid = $this->input->post('staff_id');
             $date = $this->input->post('date');
             $task_type = $this->input->post('task_type');
             $task = $this->input->post('task');
             $taskremark = $this->input->post('taskremark');
             $taskdone = $this->input->post('taskdone');
             
             for($i=0;$i<sizeof($task);$i++)
               {
			     $tid=$task[$i];
				 $taskDoneName="taskdone_".$tid;
				 $taskDoneval=$_REQUEST[$taskDoneName];
			     if($taskDoneval!=1)
				 {
				   $taskDoneval=Null;
				 }
				 else
				 {
				   $taskDoneval=1;
				 }
                 $dataSet[$i] = array(
                    'staffid'=> $staffid,
                    'date' => $date,
                    'task_frequency' => $task_type,
                    'task' => $task[$i],
                    'taskdone' => $taskDoneval,
                    'taskremark' => $taskremark[$i]
                );
               }

              $this->omodel->insert_stat($dataSet);
             
             
             set_alert('success', _l('added_successfully', "Work Report"));
             
             redirect(admin_url('operations/submit_task_report'));
            
         }
             
             
        $data['title'] = "Submit Task Report";
        $this->load->view('admin/operations/submit_task_report', $data);
    }
    
    function task_type()
    { 
        $task_type = $this->input->post('task_type');
        $date_work = $this->input->post('date_work');
        //exit;
        
        if ($this->input->post('task_type')) {
            echo $this->omodel->fetch_task_category($task_type,$date_work);
        }
    }
    
    function task_report()
    {
		$previous_date_month = date("Y-m-d", strtotime("first day of previous month"));
        if(is_admin())
        {
			$this->db->select('tbltaskreport.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname, tbltasklist.name as taskname ');
			$this->db->where('tbltaskreport.date > ', $previous_date_month);
            $this->db->join('tblstaff', 'tbltaskreport.staffid= tblstaff.staffid');
            $this->db->join('tbltasklist', 'tbltaskreport.task= tbltasklist.id');
			$this->db->order_by('id', 'DESC');
			$data['work_report'] = $this->db->get('tbltaskreport')->result();
        }
        
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
            $this->db->select('tbltaskreport.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname, tbltasklist.name as taskname ');
            $staffids = array($arr);
            //$this->db->where_in('staff_id', $staffids);
            $this->db->where("tbltaskreport.staffid IN (".$arr.")",NULL, false);
			$this->db->or_where_in('tbltaskreport.staffid', $useraid);
			$this->db->where('tbltaskreport.date > ', $previous_date_month);
            $this->db->join('tblstaff', 'tbltaskreport.staffid= tblstaff.staffid');
            $this->db->join('tbltasklist', 'tbltaskreport.task= tbltasklist.id');
            $this->db->order_by('id', 'DESC');
            
            $data['work_report'] = $this->db->get('tbltaskreport')->result();
        
        }
       
       
       
        $data['get_staff'] = $this->omodel->get_staff();
        $data['get_task_type'] = $this->omodel->get_task_type();
        $data['title'] = "Task Report";
        $this->load->view('admin/operations/task_report', $data);  
    }
    
    public function filter_by_task_type($id = '')
    {
		//print_r($_POST);exit;
		
		$task_type = $_POST['tasktype'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $staff_name = $_POST['staff_name'];
        $stf = $_POST['staff_name'];
        $leads = $this->omodel->filter_by_task_type($task_type, $start_date, $end_date, $staff_name, $stf);
		//print_r($leads);exit;
        //print_r($this->db->last_query());
        $this->printLeadData($leads);
    }
    
     private function printLeadData($leads)
    {
          if (!empty($leads)) { ?>
                        <table class="table dt-table scroll-responsive dt-no-serverside dataTable no-footer">
                               <thead>
                               <tr>
                                   <th><?php echo _l('id'); ?></th>
                                   <th class="bold"> Date</th>
                                   <th class="bold">Submitted By</th>
                                   <th class="bold">Task Type</th>
                                   <th class="bold">Task</th>
                                   <th class="bold">Task Done</th>
                                   <th class="bold">Remark</th>
                               </tr>
                               </thead>
                               <tbody class="">
                                   <?php  foreach ($leads as $work_rep) { ?>
                                   <tr>
                                       <td><?= @++$i; ?></td>
                                       <td><?= $work_rep->date; ?></td>
                                       <td><?= $work_rep->staffname; ?></td>
                                       <td><?= $work_rep->task_frequency; ?></td>
                                       <td><?= $work_rep->taskname; ?></td>
                                       <td>
                                       <?php if($work_rep->taskdone == 1) 
                                       {
                                           echo "Yes";
                                       }
                                       else
                                       {
                                           echo "<p style='color:#FF0000'>No</p>";
                                       }
                                       ?>
                                       
                                       </td>
                                       <td><?= $work_rep->taskremark; ?></td>
                                       
                                       
                                   </tr>
                                   
                               <?php } ?>
								<script src="http://bfccrm-uat.ga/assets/js/main.js?v=2.1.1"></script>
                               </tbody>
                        </table>
                        <?php
                        } else {
                            echo "No Task Report Found";
                        } 
    }
    
    
    
    
}
    
    
    