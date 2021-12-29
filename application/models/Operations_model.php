<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Operations_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function _checkRecords($query,$return)
    {
        if ($query->num_rows()) {
            return $query->$return();
        }
    }
    
     public function get_tasks($id='',$return="result")
    {
        if (!empty($id) && is_integer($id))  {
            $this->db->where('id', $id);
        }
        return $this->_checkRecords($this->db->get('tbltasklist'),$return);
    }
    
    public function add_task($data)
    {
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
        $this->db->insert('tbltasklist', $upd);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Task Added [ID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
        }
        return $insert_id;
    }
    
    public function updatetask($table,$result,$id)
    {
      $this->db->where('id',$id); 
      $up=$this->db->update($table,$result);
      return $up; 
    }
    
    public function change_status($table,$id,$status)
    {
        $this->db->where('id', $id);
        $this->db->update($table, array('active'=>$status));
        if ($this->db->affected_rows() > 0) {
            //logActivity('Status Changed [ID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');

            return true;
        }

        return false;
    }
    
    public function delete($table,$id)
    {
        $this->db->where('id', $id);
        $this->db->delete($table);
        return true;
    }
    
     function fetch_task_category($task_type,$date_work)
    {
        $dt = new DateTime($date_work);
        $datelimit =  $dt->format('d');
        $monthlimit = $dt->format('m');
        $montharray = array('months' => $monthlimit);
        $userid = $this->session->userdata('staff_user_id');
        $array = array('userid' => $userid);
        $this->db->select('*');
        $this->db->where('frequency', $task_type);
        $this->db->where('date_from <=', $datelimit);
        $this->db->where('date_to >=', $datelimit);
        $this->db->where("FIND_IN_SET(".$montharray['months'].",`months`)!=",0);
        //$this->db->where('FIND_IN_SET(.'$userid'.,assigned)');
        
       $this->db->where("FIND_IN_SET(".$array['userid'].",`assigned`)!=",0);
        $tasks = $this->db->get('tbltasklist')->result();
       // print_r($this->db->last_query());
        
        $output = '
        <table class="table  scroll-responsive">
	<thead>
		<th>Task</th> <th>Completed</th><th>Remark</th>

	</thead>
	<tbody>';
	foreach ($tasks as $task)
	{
	  $tid=$task->id;
	  $taskDoneName="taskdone_".$tid;
	$output .= '<tr>
			<td>' . $task->name . ' <input type="hidden" name="task[]" value="' . $task->id . '" data-task_id="' . $task->id . '"><input type="hidden" name="task_frequency[]" value="' . $task->frequency . '" data-task_id="' . $task->id . '"></td><td><input data-task_id="' . $task->id . '" type="checkbox" name="'.$taskDoneName.'" id="taskdone' . $task->id . '" class="taskdone" value="1" /></td><td><input data-task_id="' . $task->id . '" type="text" name="taskremark[]" id="taskremark' . $task->id . '" required/></td>
		</tr>';
	}
	$output .= '</tbody>
	
</table>
   <script>
	$(document).ready(function () {
    
    $(".taskdone").change(function () {
    var taskid =  $(this).data("task_id");
   if ($(this).prop("checked")==true)
   { 
       $("#taskremark" +taskid).prop("required",false);
   }
   else
   {
        $("#taskremark" +taskid).prop("required",true);
   }
    
    
 });

    
    
     
});  

 </script>     
 
      
 <script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>       ';
                    return $output;

    }
    
    public function get_task_type($return = "result")
    {

        $this->db->select('task_frequency');
        $this->db->group_by('task_frequency');
        return $this->_checkRecords($this->db->get('tbltaskreport'), $return);

    }
    
    public function get_staff($return = "result")
    {
        if(is_admin())
        {
            $this->db->select('tbltaskreport.staffid as staff_id, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->group_by('tbltaskreport.staffid');
            $this->db->join('tblstaff', 'tbltaskreport.staffid= tblstaff.staffid');
            return $this->_checkRecords($this->db->get('tbltaskreport'), $return);
        }
        elseif(herapermission())
        {
            $arr=herapermission();
			$useraid = $this->session->userdata('staff_user_id');
            $leadsource = array($arr);
            $this->db->select('tbltaskreport.staffid as staff_id, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->where("tbltaskreport.staffid IN (".$arr.")",NULL, false);
			$this->db->or_where_in('tbltaskreport.staffid', $useraid);
            $this->db->group_by('tbltaskreport.staffid');
            $this->db->join('tblstaff', 'tbltaskreport.staffid= tblstaff.staffid');
            return $this->_checkRecords($this->db->get('tbltaskreport'), $return);
        }
            

    }
    
    
    public function filter_by_task_type($task_type, $start_date, $end_date, $staff_name, $stf, $return = "result")
    {
        /* $task_type = $this->input->get('tasktype');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $staff_name = $this->input->get('staff_name');
        $stf = $this->input->get('staff_name'); */
       // echo $stf[0];

        
        if ($stf[0] != null) {

            $this->db->where_in('tbltaskreport.staffid', $staff_name);
        }
        else  {
            
            if(is_admin())
            {
                
            }
            else
            {
              $arr=herapermission();
            
             $this->db->where("tbltaskreport.staffid IN (".$arr.")",NULL, false);  
            }
            
           
        
        }
        if (isset($task_type)) {
            
            $this->db->where_in('tbltaskreport.task_frequency', $task_type);
        }
        
        if (isset($end_date) && $end_date != null  ) 
        {

            $this->db->where('tbltaskreport.date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
        
       

        
            $this->db->select('tbltaskreport.*, tbltasklist.name as taskname,  CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->join('tblstaff', 'tbltaskreport.staffid= tblstaff.staffid');
            $this->db->join('tbltasklist', 'tbltaskreport.task= tbltasklist.id');
            return $this->_checkRecords($this->db->get('tbltaskreport'), $return);
        
        
    }
    
    function insert_stat($dataSet)
    {
        $this->db->insert_batch('tbltaskreport', $dataSet);
    }
    
    
}
    