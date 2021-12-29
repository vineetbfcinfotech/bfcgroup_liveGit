<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DCR extends Admin_controller
{
    /**
     * Codeigniter Instance
     * Expenses detailed report filters use $ci
     * @var object
     */
    private $ci;

    public function __construct()
    {
        parent::__construct();
        /*if (!has_permission('reports', '', 'view')) {
            access_denied('reports');
        }*/
        $this->ci = &get_instance();
        $this->load->model('reports_model');
        // $this->load->model('DCR_model');
        $this->load->model('payroll_model');
        $this->load->model('teams_model');
        $this->load->model('DCR_model');
        $this->load->model('leads_model');
        $this->load->model('Leadmodel_chfilter');
        $this->load->model('TransferLeadModel');
        
    }

    /* No access on this url */
    public function index()
    {
        redirect(admin_url());
    }
    public function submit_dcr()

    {

            $data['title'] = 'Submit Daily Calling Report ';

            $data['leadstatus'] = $this->DCR_model->get_status();

            $this->load->view('admin/DCR/submit_dcr', $data);

    }
    public function apply_dcr()
    {
    //    print_r($_POST);
       $data = array(
           'staff_id'=>$_POST['staff_id'],
           'date'=>$_POST['date'],
           'apply_date'=>$_POST['apply_date'],
           'work_duration'=>$_POST['work_duration'],
           'description'=>$_POST['description'],
           'remark'=>$_POST['remark'],
       );
      $data_insert =  $this->db->insert('tbl_pc_dwr',$data);
       if($data_insert){
        set_alert('success', _l('Daily Work Report Upload successfully...'));
        redirect($_SERVER['HTTP_REFERER']);
       }else{
        set_alert('error', _l('Somthing went wrong...'));
        redirect($_SERVER['HTTP_REFERER']);
       }
      
    }
    public function get_dcr_call_count()

    {

        $work_date=$_REQUEST['work_date'];

        $staffId=$_REQUEST['staff_id'];

        $getCount=$this->DCR_model->get_dcrCallCount($work_date,$staffId);

        echo $getCount;

        

    }
    public function get_dcr_call_data()

    {

        $work_date=$_REQUEST['work_date'];

        $staffId=$_REQUEST['staff_id'];

        

        if($staffId!=0)

        {

           $this->db->where('assigned', $staffId);

           $this->db->where('lastcontact', $work_date);

        }

        else

        {

           $this->db->where('lastcontact', $work_date);

        }



        $this->db->distinct();

        $this->db->select('data_source');

        $getData= $this->db->get('tblleads')->result_array();

        $a=0;

        foreach($getData as $rt)

        {

          $data_source=$rt['data_source'];

          if($staffId!=0)

            {

               $this->db->where('assigned', $staffId);

               $this->db->where('lastcontact', $work_date);

               $this->db->where('data_source', $data_source);

            }

            else

            {

               $this->db->where('lastcontact', $work_date);

               $this->db->where('data_source', $data_source);

            }

            

            $this->db->distinct();

            $this->db->select('calling_objective');

            $getDataTwo = $this->db->get('tblleads')->result_array();

            

            

            foreach($getDataTwo as $rtt)

            {

                $callingObjective=$rtt['calling_objective'];

                $leadstatus=$this->DCR_model->get_status();

              ?>

                <tr>

                    <?php foreach($leadstatus as $str) {

                    $status=$str['id'];

                    $status_name=$str['name'];

                    

                    if($staffId!=0)

                    {

                       $this->db->where('assigned', $staffId);

                       $this->db->where('lastcontact', $work_date);

                       $this->db->where('data_source', $data_source);

                       $this->db->where('calling_objective', $callingObjective);

                       $this->db->where('status', $status);

                    }

                    else

                    {

                       $this->db->where('lastcontact', $work_date);

                       $this->db->where('data_source', $data_source);

                       $this->db->where('calling_objective', $callingObjective);

                       $this->db->where('status', $status);

                    }

                    

                    $this->db->select('*');

                    $getRowCount = $this->db->get('tblleads')->result_array();

                    $totalRows=count($getRowCount);

                    ?>

                    <td>

                      <?= $totalRows; ?>

                      <input type="hidden" name="call_count<?php echo $a; ?>[]" value="<?php echo $totalRows ?>">

                      <input type="hidden" name="status<?php echo $a; ?>[]" value="<?php echo $status ?>">

                      <input type="hidden" name="status_name<?php echo $a; ?>[]" value="<?php echo $status_name ?>">

                      

                    </td>

                    <?php } ?> 

                    <td>

                      <?php echo $callingObjective; ?>

                      <input type="hidden" name="calling_objective[]" value="<?php echo $callingObjective; ?>">

                    </td>

                    <td>

                        <?php echo $data_source; ?>

                        <input type="hidden" name="data_source[]" value="<?php echo $data_source; ?>">

                    </td>

                    <td>

                       <input type="text" name="calling_pitch[]" placeholder="Calling Pitch">

                    </td>

                    <input type="hidden" name="row_number[]" value="<?php echo $a; ?>"> 

                </tr>

                

              <?php

              $a++;

            }

            

        }

        

    }
    public function dcr_list()

    {

      $data['title'] = 'Daily Calling Report ';

      $data['leadstatus'] = $this->DCR_model->get_status();

      

       if (is_admin() || is_headtrm())

       {

         $this->db->distinct();

         $this->db->select('unique_dcr_id');

         $data['dcrList'] = $this->db->get('tbldcr')->result_array();

         

         

        

         $this->db->select('`tblstaff`.`staffid`,`tblstaff`.`firstname`,`tblstaff`.`lastname`,`tbldcr`.`staff_id`');

         $this->db->join('tbldcr', 'tblstaff.staffid = tbldcr.staff_id', 'left');

//       $this->db->group_by('`tbldcr`.`staff_id`');

         $data['staff_list']=$this->db->get('tblstaff')->result_array();

         

       }

       else

       {

         $loginid = $this->session->userdata('staff_user_id');

         $this->db->distinct();

         $this->db->where('staff_id',$loginid);

         $this->db->select('unique_dcr_id');

         $data['dcrList'] = $this->db->get('tbldcr')->result_array();

         

         $this->db->where('staffid',$loginid);

         $data['staff_list']=$this->db->get('tblstaff')->result_array();

       }

      

            

      $this->load->view('admin/DCR/dcr_list', $data);

    }
    public function custom_dcr_filter()

    {

        $filterrm=$_REQUEST['filterrm'];

        $transctiondatestart=$_REQUEST['transctiondatestart'];

        $transctiondateend=$_REQUEST['transctiondateend'];



         $this->db->distinct();

         

         

         

         if($filterrm!="")

         {

        $this->db->where_in('staff_id', $filterrm); 

         }

         

         if($filterrm== null)

         {

                if (is_admin() || is_headtrm())

       {

          $this->db->where_in('staff_id', $filterrm); 

         

       }

       

       else

       {

           $loginid = $this->session->userdata('staff_user_id');

           $this->db->where_in('staff_id', $loginid); 

       }

         }

         

         if($transctiondatestart!="")

         {

             $this->db->where('dcr_date >=', $transctiondatestart);

         }

         

         if($transctiondateend!="")

         {

            $this->db->where('dcr_date <=', $transctiondateend);

         }



         $this->db->select('unique_dcr_id');

         $dcrList = $this->db->get('tbldcr')->result_array();

         

         $leadstatus = $this->DCR_model->get_status();

         

         foreach($dcrList as $rt)

         {

            $unique_dcr_id=$rt['unique_dcr_id'];

                                     

            $this->db->where('unique_dcr_id' , $unique_dcr_id);

            $this->db->select('*');

            $getData=$this->db->get('tbldcr')->result_array();

            $sr=$getData[0];

            $didOne=$sr['id'];

            $staffId=$sr['staff_id'];

                                     

            $this->db->where('staffid' , $staffId);

            $this->db->select('*');

            $getStaff=$this->db->get('tblstaff')->result_array();

                                     

        ?>

            <tr>

                <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getStaff[0]['firstname']; ?> <?php echo $getStaff[0]['lastname']; ?></td>

                <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['dcr_date']; ?> </td>

                <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['total_call']; ?> </td>

                                       

                <?php 

                    foreach($leadstatus as $str)

                    {

                        $sid=$str['id'];

                        $this->db->where('status' , $sid);

                        $this->db->where('dcr_id' , $didOne);

                        $this->db->select('call_count');

                        $getStatusData=$this->db->get('tbldcr_status_count')->result_array();

                ?>

                    <td class="text-center"><?= $getStatusData[0]['call_count']; ?></td>

                <?php 

                    }

                ?> 

                    <td class="text-center"><?php echo $sr['calling_objective']; ?></td>

                    <td class="text-center"><?php echo $sr['data_source']; ?></td>

                    <td class="text-center"><?php echo $sr['calling_pitch']; ?></td>

                    

                <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['other_work']; ?> </td>

                <td class="text-center" rowspan="<?php echo count($getData); ?>"><?php echo $getData[0]['other_work_duration']; ?> </td>

            </tr>

        <?php

            foreach($getData as $sr)

            {

                $did=$sr['id'];

                if($did!=$didOne)

                {

        ?>

                <tr>

                    <?php

                        foreach($leadstatus as $str)

                        {

                            $sid=$str['id'];

                            $this->db->where('status' , $sid);

                            $this->db->where('dcr_id' , $did);

                            $this->db->select('call_count');

                            $getStatusData=$this->db->get('tbldcr_status_count')->result_array();

                    ?>

                        <td class="text-center"><?= $getStatusData[0]['call_count']; ?></td>

                    <?php

                        }

                    ?> 

                        <td class="text-center"><?php echo $sr['calling_objective']; ?></td>

                        <td class="text-center"><?php echo $sr['data_source']; ?></td>

                        <td class="text-center"><?php echo $sr['calling_pitch']; ?></td>

                </tr>

                    <?php

                }

                }

        }

        

    }
    
    function view_work_report()
    {
        $this->load->helper('url');
        
        
        // $leads = $this->DCR_model->filter_by_task_type();
        //print_r($leads);die;
        $data['get_staff'] = $this->leads_model->get_pc();

        $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";
        $data['title'] = "Work Report";
        $data['bodyclass'] = 'hide-sidebar';
        
        $data['work_report'] = "";
      
    //    $data['get_staff'] = $this->DCR_model->get_staff();
    //     $data['get_task_type'] = $this->DCR_model->get_task_type();
        //print_r($data['get_staff']);
        $loginid = $this->session->userdata('staff_user_id');
        // echo $loginid;
        $data['result'] = $this->db->get_where('tbl_pc_dwr',array('staff_id'=>$loginid))->result();
        // print_r($data);die;
        $this->load->view('admin/DCR/view_dcr', $data);
    }
    public function filter_by_task_type1($id = '')
    {
        $leads = $this->DCR_model->filter_by_task_type1();
        // print_r($leads);die;
        $totalCount = $this->DCR_model->work_report_get_total();
        // print_r($totalCount);die;
        $json_data = array(
			"draw"            => intval( $_POST['draw'] ),   
			"recordsTotal"    => intval( $totalCount ),  
			"recordsFiltered" => intval($totalCount),
			"aaData"            => $leads   // total data array
			);
			echo json_encode($json_data);
        // $this->printLeadData($leads);
    }
    public function view_DCR()
    {

      $data['title'] = 'Daily Calling Report ';

    //   $data['leadstatus'] = $this->DCR_model->get_status_dcr();
    $start_date = date('01-m-y');
    $end_date = date('d-m-y');

       if (is_admin() || is_headtrm())

       {

        $this->db->distinct();
        $this->db->select('`tblstaff`.`staffid`,`tblstaff`.`firstname`,`tblstaff`.`lastname`');

        $this->db->join('tblleads', 'tblstaff.staffid = tblleads.assigned');
        $this->db->group_by('tblleads.assigned');
        $this->db->where('tblstaff.role_id',64);
        $this->db->where('tblleads.lead_created_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');

        $data['dcrList']=$this->db->get('tblstaff')->result();
      
      
  


         

         

        

         $this->db->select('`tblstaff`.`staffid`,`tblstaff`.`firstname`,`tblstaff`.`lastname`');

         $this->db->where('role_id',64);

         $data['staff_list']=$this->db->get('tblstaff')->result_array();

         

       }

       else

       {

        //  $loginid = $this->session->userdata('staff_user_id');

        //  $this->db->distinct();

        //  $this->db->where('staff_id',$loginid);

        //  $this->db->select('unique_dcr_id');

        //  $data['dcrList'] = $this->db->get('tbldcr')->result_array();

         

        //  $this->db->where('staffid',$loginid);

        //  $data['staff_list']=$this->db->get('tblstaff')->result();


        $arr = herapermission();
     
			$staffId = explode(",",$arr);
			$this->db->select('*');
			$this->db->order_by('department_id');
			$this->db->where('active',1);
			$this->db->where_in('staffid', $staffId);
			$data_staff_list = $this->db->get('tblstaff')->result_array();

            if ($data_staff_list > 1) {
                $arr = herapermission();
                
                $staffId = explode(",",$arr);
                $this->db->select('*');
                $this->db->order_by('department_id');
                $this->db->where('active',1);
                $this->db->where_in('staffid', $staffId);
                $data['staff_list'] = $this->db->get('tblstaff')->result_array();

            }else{
                 $loginid = $this->session->userdata('staff_user_id');

         $this->db->distinct();

         $this->db->where('staff_id',$loginid);

         $this->db->select('unique_dcr_id');

         $this->db->where('tblleads.lead_created_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
         $data['dcrList'] = $this->db->get('tbldcr')->result_array();


         $this->db->where('staffid',$loginid);

         $data['staff_list']=$this->db->get('tblstaff')->result();
            }
         
   

       }
       
       $this->load->view('admin/DCR/view_dcr_list', $data);

    }
    public function custom_view_dcr_filter()

    {

        $filterrm=$_REQUEST['filterrm'];

        $transctiondatestart=$_REQUEST['transctiondatestart'];

        $transctiondateend=$_REQUEST['transctiondateend'];
        $i =0;
        $data_c = array(39,5,16,38,30,32,41);
        $data_count = array(39,5,16,38,30,32,41,40);

        $arr = herapermission();
        $staffId = explode(",",$arr);
        $this->db->select('*');
        $this->db->order_by('department_id');
        $this->db->where('active',1);
        $this->db->where_in('staffid', $staffId);
        $data_staff_list = $this->db->get('tblstaff')->result_array();
        if (is_admin() || is_headtrm())
       {
     
            if($filterrm!="" || $transctiondatestart!="" || $transctiondateend!="")
          {
            foreach ($filterrm as $key => $values) {
               $value = $this->db->select('staffid,firstname,lastname')->get_where('tblstaff',array('staffid'=>$values))->row(); ?>
                <tr>
                <td class="bold"><?= $value->firstname.' '.$value->lastname?></td>
                <?php foreach ($data_count as $key => $data_c_a) {
                     $this->db->select('assigned,COUNT(*) as cnt');
         
          if($transctiondatestart!="")
          {
             $transctiondatestart1 = $transctiondatestart.' 00:00:00';
              $this->db->where('lead_approve_current_date >=', $transctiondatestart1);
          }
          if($transctiondateend!="")
          {
             $transctiondateend1 = $transctiondateend.' 23:59:59';
             $this->db->where('lead_approve_current_date <=', $transctiondateend1);
          }
          if($filterrm!="")
          {
            $this->db->where_in('assigned', $value->staffid); 
          }
          $this->db->where('lead_category_id',$data_c_a);
          $getData_all = $this->db->get('tblleads')->result_array(); 
                 
                      $all[$i] += $getData_all[0]['cnt'];
                   
                 }?>
                <td class="bold"><?=$all[$i];?></td>
                <?php foreach ($data_c as $key => $data_c_a) {
                        if ($data_c_a == 32) {
                            $this->db->select('assigned,COUNT(*) as cnt');
                                
                            if($transctiondatestart!="")
                            {
                            $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                                $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                            }
                            if($transctiondateend!="")
                            {
                            $transctiondateend3 = $transctiondateend.' 23:59:59';
                            $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                            }
                            if($filterrm!="")
                            {
                            $this->db->where_in('assigned', $value->staffid); 
                            }
                            $this->db->where('lead_category_id',$data_c_a);
                            $getData_np = $this->db->get('tblleads')->result_array();
                            $getData_np_all[$i] += $getData_np[0]['cnt']; 
                            $full_count = $all[$i] -  $getData_np_all[$i];

                        }
                            
                            // echo $this->db->last_query();
                            //  $getData =   $this->db->select('assigned,COUNT(*) as cnt')->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array(); ?>

                            
                            <?php } ?>
                            <td class="bold"><?php echo($full_count)?></td>
                <?php foreach ($data_c as $key => $data_c_a) {

                    $this->db->select('assigned,COUNT(*) as cnt');
                        
                    if($transctiondatestart!="")
                    {
                    $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                        $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                    }
                    if($transctiondateend!="")
                    {
                    $transctiondateend3 = $transctiondateend.' 23:59:59';
                    $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                    }
                    if($filterrm!="")
                    {
                    $this->db->where_in('assigned', $value->staffid); 
                    }
                    $this->db->where('lead_category_id',$data_c_a);
                    $getData = $this->db->get('tblleads')->result_array(); 
                    // echo $this->db->last_query();
                //  $getData =   $this->db->select('assigned,COUNT(*) as cnt')->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array(); ?>
              
               <td class="bold"><?php echo($getData[0]['cnt'])?></td>
              <?php $i++;} ?>
                
            
            </tr>
            <?php  }
          }
       

       }else if(count($data_staff_list) > 1){

        
     
        if($filterrm!="" || $transctiondatestart!="" || $transctiondateend!="")
        {
          foreach ($filterrm as $key => $values) {
             $value = $this->db->select('staffid,firstname,lastname')->get_where('tblstaff',array('staffid'=>$values))->row(); ?>
              <tr>
              <td class="bold"><?= $value->firstname.' '.$value->lastname?></td>
              <?php foreach ($data_count as $key => $data_c_a) {
                   $this->db->select('assigned,COUNT(*) as cnt');
       
        if($transctiondatestart!="")
        {
           $transctiondatestart1 = $transctiondatestart.' 00:00:00';
            $this->db->where('lead_approve_current_date >=', $transctiondatestart1);
        }
        if($transctiondateend!="")
        {
           $transctiondateend1 = $transctiondateend.' 23:59:59';
           $this->db->where('lead_approve_current_date <=', $transctiondateend1);
        }
        if($filterrm!="")
        {
          $this->db->where_in('assigned', $value->staffid); 
        }
        $this->db->where('lead_category_id',$data_c_a);
        $getData_all = $this->db->get('tblleads')->result_array(); 
               
                    $all[$i] += $getData_all[0]['cnt'];
               }?>
              <td class="bold"><?=$all[$i];?></td>
              <?php foreach ($data_count as $key => $data_c_a) {
                  if ($data_c_a == 32) {
                    $this->db->select('assigned,COUNT(*) as cnt');
       
                    if($transctiondatestart!="")
                    {
                       $transctiondatestart1 = $transctiondatestart.' 00:00:00';
                        $this->db->where('lead_approve_current_date >=', $transctiondatestart1);
                    }
                    if($transctiondateend!="")
                    {
                       $transctiondateend1 = $transctiondateend.' 23:59:59';
                       $this->db->where('lead_approve_current_date <=', $transctiondateend1);
                    }
                    if($filterrm!="")
                    {
                      $this->db->where_in('assigned', $value->staffid); 
                    }
                    $this->db->where('lead_category_id',$data_c_a);
                           
                    $getData_np = $this->db->get('tblleads')->result_array();
                    $getData_np_all[$i] += $getData_np[0]['cnt']; 
                    $full_count = $all[$i] -  $getData_np_all[$i];
                  }
                   
               }?>
              <td class="bold"><?=$full_count;?></td>
              <?php foreach ($data_c as $key => $data_c_a) {

                  $this->db->select('assigned,COUNT(*) as cnt');
                      
                  if($transctiondatestart!="")
                  {
                  $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                      $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                  }
                  if($transctiondateend!="")
                  {
                  $transctiondateend3 = $transctiondateend.' 23:59:59';
                  $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                  }
                  if($filterrm!="")
                  {
                  $this->db->where_in('assigned', $value->staffid); 
                  }
                  $this->db->where('lead_category_id',$data_c_a);
                  $getData = $this->db->get('tblleads')->result_array(); 
                  // echo $this->db->last_query();
              //  $getData =   $this->db->select('assigned,COUNT(*) as cnt')->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array(); ?>
            
             <td class="bold"><?php echo($getData[0]['cnt'])?></td>
            <?php $i++;} ?>
              
          
          </tr>
          <?php  }
        }
       }
       else
        {
          
           $loginid = $this->session->userdata('staff_user_id');
            $staff_list = $this->db->select('staffid,firstname,lastname')->get_where('tblstaff',array('staffid'=>$loginid))->row(); 

        ?><tr>
           
         <!-- <td class="bold"><?= $staff_list->firstname.' '.$staff_list->lastname?></td>  -->
        <?php foreach ($data_count as $key => $data_c_a) { 
            // $getData_all =   $this->db->select('assigned,COUNT(*) as cnt')->get_where('tblleads',array('assigned'=>$staff_list[0]->staffid,'lead_category_id'=>$data_c_a))->result_array(); 
            $this->db->select('assigned,COUNT(*) as cnt');
         
            if($transctiondatestart!="")
            {
               $transctiondatestart1 = $transctiondatestart.' 00:00:00';
                $this->db->where('lead_approve_current_date >=', $transctiondatestart1);
            }
            if($transctiondateend!="")
            {
               $transctiondateend1 = $transctiondateend.' 23:59:59';
               $this->db->where('lead_approve_current_date <=', $transctiondateend1);
            }
        
            $this->db->where_in('assigned', $loginid); 
            $this->db->where('lead_category_id',$data_c_a);
            $getData_all = $this->db->get('tblleads')->result_array(); 
            $all[$i] += $getData_all[0]['cnt'];
         }?>
        <td class="bold"><?=$all[$i];?></td>
        <?php foreach ($data_c as $key => $data_c_a) { 
            if ($data_c_a == 32) {
                $this->db->select('assigned,COUNT(*) as cnt');
                        
                if($transctiondatestart!="")
                {
                $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                    $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                }
                if($transctiondateend!="")
                {
                $transctiondateend3 = $transctiondateend.' 23:59:59';
                $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                }
          
                $this->db->where_in('assigned', $loginid); 
                $this->db->where('lead_category_id',$data_c_a);
                $getData_np = $this->db->get('tblleads')->result_array();
                $getData_np_all[$i] += $getData_np[0]['cnt']; 
                $full_count = $all[$i] -  $getData_np_all[$i];
            }
       
                } ?>
      <td class="bold"><?php echo($full_count)?></td>

        <?php foreach ($data_c as $key => $data_c_a) { 
        $this->db->select('assigned,COUNT(*) as cnt');
                        
                        if($transctiondatestart!="")
                        {
                        $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                            $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                        }
                        if($transctiondateend!="")
                        {
                        $transctiondateend3 = $transctiondateend.' 23:59:59';
                        $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                        }
                  
                        $this->db->where_in('assigned', $loginid); 
                        $this->db->where('lead_category_id',$data_c_a);
                        $getData = $this->db->get('tblleads')->result_array();
                      ?>
        <td class="bold"><?php echo($getData[0]['cnt'])?></td>
      <?php } ?>
        
    
    </tr>
      <?php }
             
        // print_r($dcrList);
    }
    public function filter_view_dcr()
    {
        // print_r($_POST);
        // die;
        // $start_date = (isset($_POST['start_date']) && trim($_POST['start_date'])!='')?date("Y-m-d",strtotime(trim($_POST['start_date']))):'';//$_POST['start_date'];die;
        // $end_date = (isset($_POST['end_date']) && trim($_POST['end_date'])!='')?date("Y-m-d",strtotime(trim($_POST['end_date']))):'';//$_POST['end_date'];
        // $staff_name = $_POST['staff_name'];
        // if (count($staff_name) > 0) {

        //     $this->db->where_in('staff_id', $staff_name);
        // }
      
        // if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        // {

        //     $this->db->where('apply_date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        // }
        
        // $this->db->select('tbl_pc_dwr.*');
        //     $this->db->join('tblstaff', 'tbl_pc_dwr.staff_id= tblstaff.staffid');
		// 	$this->db->order_by('tbl_pc_dwr.id', 'desc');
        //    $data = $this->db->get('tbl_pc_dwr')->result();
        // //    echo $this->db->last_query();
        // //    die;
        // //    print_r($data);
        //    $html = '';
        //    $i = 1;
        //    foreach ($data as $t){
        //        $html.='<tr>';
        //         $html .= '<td>'.$i.'<td>';
        //         $html.= '<td>'.$t->work_duration.'</td>';
        //         $html.= '<td>'.$t->description.'</td>';
        //         $html.= '<td>'.$t->remark.'</td>';
        //         $html.= '</tr>';
        //         $i++;
        //   }
        //    print_r($html);
           
        $filterrm=$_REQUEST['staff_name'];

        $transctiondatestart=$_REQUEST['start_date'];

        $transctiondateend=$_REQUEST['end_date'];
        $i =0;
        $data_c = array(39,5,16,38,30,32,41);
        $data_count = array(39,5,16,38,30,32,41,40);

        $arr = herapermission();
        $staffId = explode(",",$arr);
        $this->db->select('*');
        $this->db->order_by('department_id');
        $this->db->where('active',1);
        $this->db->where_in('staffid', $staffId);
        $data_staff_list = $this->db->get('tblstaff')->result_array();
        if (is_admin() || is_headtrm())
       {
     
            if($filterrm!="" || $transctiondatestart!="" || $transctiondateend!="")
          {
            foreach ($filterrm as $key => $values) {
               $value = $this->db->select('staffid,firstname,lastname')->get_where('tblstaff',array('staffid'=>$values))->row(); ?>
                <tr>
                <td class="bold"><?= $value->firstname.' '.$value->lastname?></td>
                <?php foreach ($data_count as $key => $data_c_a) {
                     $this->db->select('assigned,COUNT(*) as cnt');
         
          if($transctiondatestart!="")
          {
             $transctiondatestart1 = $transctiondatestart.' 00:00:00';
              $this->db->where('lead_approve_current_date >=', $transctiondatestart1);
          }
          if($transctiondateend!="")
          {
             $transctiondateend1 = $transctiondateend.' 23:59:59';
             $this->db->where('lead_approve_current_date <=', $transctiondateend1);
          }
          if($filterrm!="")
          {
            $this->db->where_in('assigned', $value->staffid); 
          }
          $this->db->where('lead_category_id',$data_c_a);
          $getData_all = $this->db->get('tblleads')->result_array(); 
                 
                      $all[$i] += $getData_all[0]['cnt'];
                   
                 }?>
                <td class="bold"><?=$all[$i];?></td>
                <?php foreach ($data_c as $key => $data_c_a) {
                        if ($data_c_a == 32) {
                            $this->db->select('assigned,COUNT(*) as cnt');
                                
                            if($transctiondatestart!="")
                            {
                            $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                                $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                            }
                            if($transctiondateend!="")
                            {
                            $transctiondateend3 = $transctiondateend.' 23:59:59';
                            $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                            }
                            if($filterrm!="")
                            {
                            $this->db->where_in('assigned', $value->staffid); 
                            }
                            $this->db->where('lead_category_id',$data_c_a);
                            $getData_np = $this->db->get('tblleads')->result_array();
                            $getData_np_all[$i] += $getData_np[0]['cnt']; 
                            $full_count = $all[$i] -  $getData_np_all[$i];

                        }
                            
                            // echo $this->db->last_query();
                            //  $getData =   $this->db->select('assigned,COUNT(*) as cnt')->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array(); ?>

                            
                            <?php } ?>
                            <td class="bold"><?php echo($full_count)?></td>
                <?php foreach ($data_c as $key => $data_c_a) {

                    $this->db->select('assigned,COUNT(*) as cnt');
                        
                    if($transctiondatestart!="")
                    {
                    $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                        $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                    }
                    if($transctiondateend!="")
                    {
                    $transctiondateend3 = $transctiondateend.' 23:59:59';
                    $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                    }
                    if($filterrm!="")
                    {
                    $this->db->where_in('assigned', $value->staffid); 
                    }
                    $this->db->where('lead_category_id',$data_c_a);
                    $getData = $this->db->get('tblleads')->result_array(); 
                    // echo $this->db->last_query();
                //  $getData =   $this->db->select('assigned,COUNT(*) as cnt')->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array(); ?>
              
               <td class="bold"><?php echo($getData[0]['cnt'])?></td>
              <?php $i++;} ?>
                
            
            </tr>
            <?php  }
          }
       

       }else if(count($data_staff_list) > 1){

        
     
        if($filterrm!="" || $transctiondatestart!="" || $transctiondateend!="")
        {
          foreach ($filterrm as $key => $values) {
             $value = $this->db->select('staffid,firstname,lastname')->get_where('tblstaff',array('staffid'=>$values))->row(); ?>
              <tr>
              <td class="bold"><?= $value->firstname.' '.$value->lastname?></td>
              <?php foreach ($data_count as $key => $data_c_a) {
                   $this->db->select('assigned,COUNT(*) as cnt');
       
        if($transctiondatestart!="")
        {
           $transctiondatestart1 = $transctiondatestart.' 00:00:00';
            $this->db->where('lead_approve_current_date >=', $transctiondatestart1);
        }
        if($transctiondateend!="")
        {
           $transctiondateend1 = $transctiondateend.' 23:59:59';
           $this->db->where('lead_approve_current_date <=', $transctiondateend1);
        }
        if($filterrm!="")
        {
          $this->db->where_in('assigned', $value->staffid); 
        }
        $this->db->where('lead_category_id',$data_c_a);
        $getData_all = $this->db->get('tblleads')->result_array(); 
               
                    $all[$i] += $getData_all[0]['cnt'];
               }?>
              <td class="bold"><?=$all[$i];?></td>
              <?php foreach ($data_count as $key => $data_c_a) {
                  if ($data_c_a == 32) {
                    $this->db->select('assigned,COUNT(*) as cnt');
       
                    if($transctiondatestart!="")
                    {
                       $transctiondatestart1 = $transctiondatestart.' 00:00:00';
                        $this->db->where('lead_approve_current_date >=', $transctiondatestart1);
                    }
                    if($transctiondateend!="")
                    {
                       $transctiondateend1 = $transctiondateend.' 23:59:59';
                       $this->db->where('lead_approve_current_date <=', $transctiondateend1);
                    }
                    if($filterrm!="")
                    {
                      $this->db->where_in('assigned', $value->staffid); 
                    }
                    $this->db->where('lead_category_id',$data_c_a);
                           
                    $getData_np = $this->db->get('tblleads')->result_array();
                    $getData_np_all[$i] += $getData_np[0]['cnt']; 
                    $full_count = $all[$i] -  $getData_np_all[$i];
                  }
                   
               }?>
              <td class="bold"><?=$full_count;?></td>
              <?php foreach ($data_c as $key => $data_c_a) {

                  $this->db->select('assigned,COUNT(*) as cnt');
                      
                  if($transctiondatestart!="")
                  {
                  $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                      $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                  }
                  if($transctiondateend!="")
                  {
                  $transctiondateend3 = $transctiondateend.' 23:59:59';
                  $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                  }
                  if($filterrm!="")
                  {
                  $this->db->where_in('assigned', $value->staffid); 
                  }
                  $this->db->where('lead_category_id',$data_c_a);
                  $getData = $this->db->get('tblleads')->result_array(); 
                  // echo $this->db->last_query();
              //  $getData =   $this->db->select('assigned,COUNT(*) as cnt')->get_where('tblleads',array('assigned'=>$value->staffid,'lead_category_id'=>$data_c_a))->result_array(); ?>
            
             <td class="bold"><?php echo($getData[0]['cnt'])?></td>
            <?php $i++;} ?>
              
          
          </tr>
          <?php  }
        }
       }
       else
        {
          
           $loginid = $this->session->userdata('staff_user_id');
            $staff_list = $this->db->select('staffid,firstname,lastname')->get_where('tblstaff',array('staffid'=>$loginid))->row(); 

        ?><tr>
           
         <!-- <td class="bold"><?= $staff_list->firstname.' '.$staff_list->lastname?></td>  -->
        <?php foreach ($data_count as $key => $data_c_a) { 
            // $getData_all =   $this->db->select('assigned,COUNT(*) as cnt')->get_where('tblleads',array('assigned'=>$staff_list[0]->staffid,'lead_category_id'=>$data_c_a))->result_array(); 
            $this->db->select('assigned,COUNT(*) as cnt');
         
            if($transctiondatestart!="")
            {
               $transctiondatestart1 = $transctiondatestart.' 00:00:00';
                $this->db->where('lead_approve_current_date >=', $transctiondatestart1);
            }
            if($transctiondateend!="")
            {
               $transctiondateend1 = $transctiondateend.' 23:59:59';
               $this->db->where('lead_approve_current_date <=', $transctiondateend1);
            }
        
            $this->db->where_in('assigned', $loginid); 
            $this->db->where('lead_category_id',$data_c_a);
            $getData_all = $this->db->get('tblleads')->result_array(); 
            $all[$i] += $getData_all[0]['cnt'];
         }?>
        <td class="bold"><?=$all[$i];?></td>
        <?php foreach ($data_c as $key => $data_c_a) { 
            if ($data_c_a == 32) {
                $this->db->select('assigned,COUNT(*) as cnt');
                        
                if($transctiondatestart!="")
                {
                $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                    $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
                }
                if($transctiondateend!="")
                {
                $transctiondateend3 = $transctiondateend.' 23:59:59';
                $this->db->where('lead_approve_current_date <=', $transctiondateend3);
                }
          
                $this->db->where_in('assigned', $loginid); 
                $this->db->where('lead_category_id',$data_c_a);
                $getData_np = $this->db->get('tblleads')->result_array();
                $getData_np_all[$i] += $getData_np[0]['cnt']; 
                $full_count = $all[$i] -  $getData_np_all[$i];
            }
       
                } ?>
      <td class="bold"><?php echo($full_count)?></td>

        <?php foreach ($data_c as $key => $data_c_a) { 
        $this->db->select('assigned,COUNT(*) as cnt');
            if($transctiondatestart!="")
            {
            $transctiondatestart2 = $transctiondatestart.' 00:00:00';
                $this->db->where('lead_approve_current_date >=', $transctiondatestart2);
            }
            if($transctiondateend!="")
            {
            $transctiondateend3 = $transctiondateend.' 23:59:59';
            $this->db->where('lead_approve_current_date <=', $transctiondateend3);
            }
        
            $this->db->where_in('assigned', $loginid); 
            $this->db->where('lead_category_id',$data_c_a);
            $getData = $this->db->get('tblleads')->result_array();
            ?>
        <td class="bold"><?php echo($getData[0]['cnt'])?></td>
      <?php } ?>
    </tr>
      <?php }
        // print_r($dcrList);
    }

    public function viewTaskReport(){
        
        $search_text = "";
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('search_global');
            $this->session->set_userdata(array("search_global" => $search_text));
        } else {
            if ($this->session->userdata('search_global') != NULL) {
                $search_text = $this->session->userdata('search_global');
            } else {}
        }
        $expsearch_cat = "";
        $expstaff_name  = "";
        $expstart_date = "";
        $expend_date = "";
        $exp_ms="";
        if($this->input->post('submit_cat') != NULL ){
            $expstaff_name = $this->input->post('staff_name');
            $this->session->set_userdata(array("expstaff_name"=>$expstaff_name));
            $expstart_date = $this->input->post('start_date');
            $expend_date = $this->input->post('end_date');
            $this->session->set_userdata(array("expstart_date"=>$expstart_date,"expend_date"=>$expend_date));
        }else{
            if( ($this->session->userdata('expstaff_name') != NULL) || ($this->session->userdata('expstart_date') != NULL) ){
                $expstaff_name = $this->session->userdata('expstaff_name');
                $expstart_date = $this->session->userdata('expstart_date');
                $expend_date = $this->session->userdata('expend_date');
            }else{
            }
        }
        $data['useraid'] = $useraid;
        $data['get_staff'] = $this->leads_model->get_pc();
        $allcount = $this->DCR_model->getTrCount($search_text,$expsearch_cat,$expstart_date,$expend_date,$expstaff_name,$exp_ms);

        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() .'admin/DCR/viewTaskReport';
        $config['total_rows'] =$allcount;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['project_data'] =  $this->DCR_model->TrData($config["per_page"], $page ,$search_text,$expsearch_cat,$expstart_date,$expend_date,$expstaff_name,$exp_ms);
        $data['bodyclass'] = 'hide-sidebar';
        if(empty($this->uri->segment(4))){
            $curpage=$this->uri->segment(3);
            $result_start=1; 
            $result_end=$curpage+$config['per_page'];
       }else {
            $curpage=$this->uri->segment(4);
            $result_start=$curpage+1;
            $result_end=$curpage+$config['per_page'];
            if($result_end>$allcount){$result_end=$allcount;}
       }
        $data['pagination_number'] = "Displaying " . $result_start . " to " . $result_end . " of " . $allcount;
        $data['search'] = $search_text;
        $data['staff_name'] = $expstaff_name;
        $data['start_date'] = $expstart_date;
        $data['end_date'] = $expend_date;
        $this->load->view('admin/DCR/view_task_report',$data);
    }
    
    public function viewDCRReport(){
        
        $search_text = "";
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('search_global');
            $this->session->set_userdata(array("search_global" => $search_text));
        } else {
            if ($this->session->userdata('search_global') != NULL) {
                $search_text = $this->session->userdata('search_global');
            } else {}
        }
        $expsearch_cat = "";
        $expstaff_name  = "";
        $expstart_date = "";
        $expend_date = "";
        $exp_ms="";
        if($this->input->post('submit_cat') != NULL ){
            $expstaff_name = $this->input->post('staff_name');
            $this->session->set_userdata(array("expstaff_name"=>$expstaff_name));
            $expstart_date = $this->input->post('start_date');
            $expend_date = $this->input->post('end_date');
            $this->session->set_userdata(array("expstart_date"=>$expstart_date,"expend_date"=>$expend_date));
        }else{
            if( ($this->session->userdata('expstaff_name') != NULL) || ($this->session->userdata('expstart_date') != NULL) ){
                $expstaff_name = $this->session->userdata('expstaff_name');
                $expstart_date = $this->session->userdata('expstart_date');
                $expend_date = $this->session->userdata('expend_date');
            }else{
            }
        }
        $data['useraid'] = $useraid;
        $data['get_staff'] = $this->leads_model->get_pc();
        $allcount = $this->DCR_model->getDCRCount($search_text,$expsearch_cat,$expstart_date,$expend_date,$expstaff_name,$exp_ms);

        $this->load->library('pagination');
        $config = array();
        $config['base_url'] =  base_url() .'admin/DCR/viewDCRReport';
        $config['total_rows'] =$allcount;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['project_data'] =  $this->DCR_model->getDCRData($config["per_page"], $page ,$search_text,$expsearch_cat,$expstart_date,$expend_date,$expstaff_name,$exp_ms);
        $data['bodyclass'] = 'hide-sidebar';
        if(empty($this->uri->segment(4))){
            $curpage=$this->uri->segment(3);
            $result_start=1; 
            $result_end=$curpage+$config['per_page'];
       }else {
            $curpage=$this->uri->segment(4);
            $result_start=$curpage+1;
            $result_end=$curpage+$config['per_page'];
            if($result_end>$allcount){$result_end=$allcount;}
       }
        $data['pagination_number'] = "Displaying " . $result_start . " to " . $result_end . " of " . $allcount;
        $data['search'] = $search_text;
        $data['staff_name'] = $expstaff_name;
        $data['start_date'] = $expstart_date;
        $data['end_date'] = $expend_date;
        $this->load->view('admin/DCR/dcr_report',$data);
    }

    public function new_export($start_date='',$end_date='',$staff_name=''){
        $url = $_SERVER['HTTP_REFERER'];
        // print_r($this->session->userdata('expstaff_name'));
        // die();
              if( ($start_date == 'no_date') && ($end_date == 'no_date')){
                  //   echo("<script LANGUAGE='JavaScript'>
                  //         window.alert('Please Select any one filter');
                  //     window.location.href='".$url."';
                  //         </script>");
                  echo ("<script LANGUAGE='JavaScript'>  window.alert('Please Select Date filter'); window.location.href='".$url."';
                  </script>");die;
                  
              }else{
                  $start_date1 = $start_date;
                  $end_date1 = $end_date;
              }

          $useraid = $this->session->userdata('staff_user_id');
          $role = get_imp_role();
          $arr = herapermission();
          if($staff_name == ""){
           $arr = explode(',', $arr);
       }else{
           $arr = explode('-', $staff_name);
       }
          $this->db->select('*');
         if (!(is_admin())) {
          if ($useraid == 28) {
         
          }elseif($role ==92){
            //   if($staff_name!=''){
            //     $this->db->where_in('staff_id',$staff_name);
            //   }else{
                $this->db->where_in('staff_id',$arr);
            //   }
          }else{
              $this->db->where('staff_id',$useraid);
          }
       
      }else{
          if($staff_name!=''){
            $this->db->where_in('staff_id',$arr);
          }
      }
      if (isset($end_date1) && $end_date1 != null  ) 
      {
          $this->db->where('date BETWEEN "'.$start_date1.' 00:00:00.000" and "'. $end_date1.' 23:59:59.997"');
      }
         $this->db->order_by("date","asc");
         $result = $this->db->get('tbl_pc_dwr')->result();
         $datas =array();
         foreach ($result as $dwr) { 
            $name = $this->db->select('firstname')->from('tblstaff')->where('staffid', $dwr->staff_id)->order_by('staffid', 'ASC')->get()->row()->firstname;
             $my = array('Working Person'=>$name,'Duration'=>$dwr->work_duration,'Description'=>$dwr->description,'Remark'=>$dwr->remark);
             array_push($datas,$my);  
         }
         header("Content-type: application/csv");
         header("Content-Disposition: attachment; filename=\"task_report_dwr".".csv\"");
         header("Pragma: no-cache");
         header("Expires: 0");
         $handle = fopen('php://output', 'w');
         fputcsv($handle, array("Sr. No.","Working Person","Duration","Description","Remark"));
         $cnt=1;
         foreach ($datas as $key) {
             $narray=array($cnt++,$key["Working Person"],$key["Duration"],$key["Description"],$key["Remark"]);
             fputcsv($handle, $narray);
         }
         fclose($handle);
         exit;
     }
    

          

        

    
}
