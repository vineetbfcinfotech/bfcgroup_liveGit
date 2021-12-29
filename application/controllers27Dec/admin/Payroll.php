<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;

class Payroll extends Admin_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('payroll_model');
        $this->load->model('roles_model');
    }

    public function salary_template($id = null)
    {

        if (!empty($id)) {


            $data['active'] = 2;
        } else {
            $data['active'] = 1;
        }
        $data['title'] = _l('salary_template_details');


        $this->payroll_model->_table_name = "tbl_salary_template"; // table name
        $this->payroll_model->_order_by = "salary_template_id"; // $id
        $data['roles'] = $this->roles_model->get();
        $data['all_salary_template'] = $this->payroll_model->get();


        $this->load->view('admin/payroll/salary_template', $data);
    }

    public function manage_salary($id = null)
    {  
        if (!empty($id)) {
            
            //echo "string"; exit();
            $sal_id = $this->uri->segment(4);
            
            $this->db->select('tbl_salary_template.*');
            $this->db->where('salary_template_id', $sal_id);
            $data['staffsal'] = $this->db->get('tbl_salary_template')->row();
            //print_r($data['staffsal']);
            $to = $data['staffsal']->period_to;
            $from = $data['staffsal']->period_from;
            $staffid =  $data['staffsal']->salary_grade;
            
            //exit();
            $this->db->select_sum('net_credit');
            $this->db->where('converted_by', $staffid);
            $this->db->where('transaction_date >=', $from);
            $this->db->where('transaction_date <=', $to);
            $data['creditscore'] =  $this->db->get('tblbusiness')->result();
            
            $this->db->select('baby_sitting_loss');
            $this->db->where('staff_id', $staffid);
            $this->db->where('finacial_year', 2018);
            $data['bsl'] =  $this->db->get('tblincentive')->result();
            //print_r($this->db->last_query());
            
            //print_r($data['bsl']);
            $this->db->where('sal_temp_id', $staffid);
            $this->db->order_by("id", "asc");
            $data['salary_wef'] = $this->db->get('tbl_salary_wef')->result();
            
            
            $data['allemployee'] = $this->db->query("SELECT CONCAT(tblstaff.firstname,' ', tblstaff.lastname)as name, tblstaff.staffid as staffid  from tblstaff WHERE `staffid` = $staffid ")->row();
           
            $data['title'] = "Edit Salary Template";
            $this->load->view('admin/payroll/editsalary', $data);
        } else { ///echo "string-3";  exit();
            $data['allemployee'] = $this->db->query("SELECT CONCAT(tblstaff.firstname,' ', tblstaff.lastname)as name, tblstaff.staffid as staffid  from tblstaff ORDER BY `staffid` ASC ")->result();
            $data['disabledempids'] = $this->db->query("SELECT GROUP_CONCAT(salary_grade) as staffid from tbl_salary_template ORDER BY `staffid` ASC ")->row()->staffid;
            $data['title'] = _l('salary_template_details');
            $this->load->view('admin/payroll/manage_salary', $data);
        }

    }
    
    public function editempsal()
    {
        if($this->input->post())
        {
            $userid = $_POST['staffid'];
            $basic_salary = $_POST['basic_salary'];
            $overtime_salary = $_POST['overtime_salary'];
            $house_rent_allowance = $_POST['house_rent_allowance'];
            $special_allowance = $_POST['special_allowance'];
            $conveyance_allowance = $_POST['conveyance_allowance'];
            $provident_fund = $_POST['provident_fund'];
            $tax_deduction = $_POST['tax_deduction'];
            $net_salary = $_POST['net_salary_emp'];
            $period_to = $_POST['enddate'];
            $period_from = $_POST['startdate'];
            $annual_sal = $_POST['annual_ctc'];
            $ctc_factor = $_POST['annual_per'];
            $ex_annual_ctc = $_POST['ex_annual_ctc'];
            $direct_cost = $_POST['direct_cost'];
            $bep = $_POST['bep'];
            $monthy_sal = $_POST['price'];
            $q_ctc = $_POST['qualifying_ctc'];
            $baby_shitiing_loss = $_POST['baby_shitiing_loss'];
            $start_date_wef = $_POST['start_date_wef'];
            $ctc_sal_factor =  $_POST['annual_per'];
            $numberOfMonths =  $_POST['numberOfMonths'];
            $last_fincial_year = $_POST['last_fincial_year'];
            $start_finacial_year = $_POST['start_finacial_year'];
            
            $resultData1 = array('basic_salary' => $basic_salary, 'direct_cost' => $direct_cost, 'bep' => $bep,'period_to' => $period_to, 'period_from' => $period_from, 'annual_sal' => $annual_sal, 'ctc_factor' => $ctc_factor, 'exp_annual_ctc' => $ex_annual_ctc,'overtime_salary' => $overtime_salary, 'house_rent_allowance' => $house_rent_allowance,'special_allowance' => $special_allowance, 'conveyance_allowance' => $conveyance_allowance, 'provident_fund' => $provident_fund,'tax_deduction' => $tax_deduction, 'net_salary' => $net_salary,'qualifying_ctc' => $q_ctc,'baby_shitiing_loss' => $baby_shitiing_loss,'last_finacial_year' => $last_fincial_year,'start_finacial_year' => $start_finacial_year);
            $this->db->where('salary_grade', $userid);
            $this->db->update('tbl_salary_template', $resultData1);
            
            $this->db->select('timefrom, id');
            $this->db->where(array('sal_temp_id' => $userid));
            $this->db->order_by("id", "desc");
            $query = $this->db->get('tbl_salary_wef');
            $wefresult = $query->result();
            
            if ($wefresult[0]->timefrom == $start_date_wef) {
                $wef_data = array('monthly' => $monthy_sal, 'ctc_factor' => $ctc_sal_factor, 'q_ctc' => $q_ctc, 'timefrom' => $start_date_wef, 'timeto' => $period_to,'numberOfMonths' => $numberOfMonths, 'annual_ctc' => $annual_sal);
                $this->db->where('sal_temp_id', $userid);
                $this->db->where('timefrom', $start_date_wef);
                $this->db->update('tbl_salary_wef', $wef_data);
            
                set_alert('success', 'Salary Updated Successfully');
                redirect(base_url('admin/payroll/salary_template'));
            } 
            else
            { 
                if($start_date_wef!=''){
                    if($wefresult[0]->timefrom != null)
                    {
                        $lastwefid= $wefresult[0]->id;
                        $timeto = $wefresult[0]->timefrom;
                        $lastwefdate = new DateTime($start_date_wef);
                        $lastwefdate = $lastwefdate->modify('-1 day');
                        $lastwefdate = $lastwefdate->format('Y-m-d');
                        $updatetimeto = array('timeto' => $lastwefdate);
                         $this->db->where('id', $lastwefid);
                        $this->db->update('tbl_salary_wef', $updatetimeto);
                    }
                }else{
                    $start_date_wef = $period_from;
                    $this->db->select('timefrom, id');
                    $this->db->where(array('sal_temp_id' => $userid));
                    $this->db->where('timefrom',$start_date_wef);
                    $this->db->order_by("id", "desc");
                    $query = $this->db->get('tbl_salary_wef')->result();

                    if($query){
                        $wefid= $wefresult[0]->id;
                        $wef_data = array('monthly' => $monthy_sal, 'ctc_factor' => $ctc_sal_factor, 'q_ctc' => $q_ctc, 'timefrom' => $start_date_wef, 'timeto' => $period_to,'numberOfMonths' => $numberOfMonths, 'annual_ctc' => $annual_sal);
                        $this->db->where('sal_temp_id', $userid);
                        $this->db->where('timefrom', $start_date_wef);
                        $this->db->update('tbl_salary_wef', $wef_data);
                    
                        set_alert('success', 'Salary Updated Successfully');
                        redirect(base_url('admin/payroll/salary_template'));
                    }
                }
               
                $wef_data = array('sal_temp_id' => $userid, 'monthly' => $monthy_sal, 'ctc_factor' => $ctc_sal_factor, 'q_ctc' => $q_ctc, 'timefrom' => $start_date_wef, 'timeto' => $period_to,'numberOfMonths' => $numberOfMonths,'annual_ctc' => $annual_sal);
                $this->db->insert('tbl_salary_wef', $wef_data);
                set_alert('success', 'Salary WEF Updated Successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
            /*$staff_id = $userid;
            $financial_year = $this->input->post('finacial_year', TRUE);
            $ctc = $ex_annual_ctc;
            
            $qualifying_ctc  = $this->input->post('qualifying_ctc', TRUE);
            $credit_score_fy = $this->input->post('credit_score_fy', TRUE);
            $cs_per_qctc = $this->input->post('cs_per_qctc', TRUE);
            $pl_over_ctc = $this->input->post('pl_over_ctc', TRUE);
            $rm_incentive_fy = $this->input->post('payment_amount1', TRUE);
            $bbsht = $pl_over_ctc;
            $baby_shitiing_loss = preg_replace("/&#?[a-z0-9]{2,8};/i","",$bbsht); 
            
            
            $this->db->select('staff_id');
            $this->db->where(array('tt.staff_id' => $staff_id, 'tt.finacial_year' => $financial_year ));
            $query = $this->db->get('tblincentive tt');
            $checkdate = $query->num_rows();
            if ($checkdate > 0) {
                set_alert('warning', "Staff Incentive Already Defined For $financial_year ");
                redirect($_SERVER['HTTP_REFERER']);
            } 
            
            $resultData = array('staff_id' => $staff_id, 'period_to' => $period_to, 'period_from' => $period_from, 'ctc' => $ctc, 'baby_sitting_loss' => $baby_shitiing_loss, 'qualifying_ctc' => $qualifying_ctc, 'credit_score_fy' => $credit_score_fy, 'cs_per_qctc' => $cs_per_qctc, 'pl_over_ctc' =>$pl_over_ctc, 'rm_incentive_fy' => $rm_incentive_fy );
            
            
            
            $this->db->insert('tblincentive', $resultData);*/
    
            /*set_alert('success', 'Salary Updated Successfully');
            redirect(base_url('admin/payroll/salary_template'));*/
            }
    }
    
    public function deletesaltem()
    {
        $sal_id = $this->uri->segment(4);
        

           $success = $this->payroll_model->deletesaltem($sal_id);

           set_alert('success', _l('deleted', "Salary Template"));

           redirect($_SERVER['HTTP_REFERER']);
    }

    public function set_salary_details()
    {

        $userid = $_POST['salary_grade'];
        $basic_salary = $_POST['basic_salary'];
        $overtime_salary = $_POST['overtime_salary'];
        $house_rent_allowance = $_POST['house_rent_allowance'];
        $special_allowance = $_POST['special_allowance'];
        $conveyance_allowance = $_POST['conveyance_allowance'];
        $provident_fund = $_POST['provident_fund'];
        $tax_deduction = $_POST['tax_deduction'];
        $net_salary = $_POST['net_salary_emp'];
        
            $period_to = $_POST['enddate'];
            $period_from = $_POST['startdate'];
            $annual_sal = $_POST['annual_ctc'];
            $ctc_factor = $_POST['annual_per'];
            $ex_annual_ctc = $_POST['ex_annual_ctc'];
            $direct_cost = $_POST['direct_cost'];
            $bep = $_POST['bep'];
            $qualifying_ctc = $_POST['qualifying_ctc'];

            $last_fincial_year = $_POST['last_fincial_year'];
            $start_finacial_year = $_POST['start_finacial_year'];
            $numberOfMonths =  $_POST['numberOfMonths'];

        $resultData = array( 'direct_cost' => $direct_cost, 'bep' => $bep,'period_to' => $period_to, 'period_from' => $period_from, 'annual_sal' => $annual_sal, 'ctc_factor' => $ctc_factor, 'exp_annual_ctc' => $ex_annual_ctc,'salary_grade' => $userid, 'basic_salary' => $basic_salary, 'overtime_salary' => $overtime_salary, 'house_rent_allowance' => $house_rent_allowance, 'special_allowance' => $special_allowance, 'conveyance_allowance' => $conveyance_allowance, 'provident_fund' => $provident_fund, 'tax_deduction' => $tax_deduction, 'net_salary' => $net_salary,'qualifying_ctc' => $q_ctc,'baby_shitiing_loss' => $baby_shitiing_loss,'last_finacial_year' => $last_fincial_year,'start_finacial_year' => $start_finacial_year);
        // print_r($resultData);
        //  exit;
        $this->db->insert('tbl_salary_template', $resultData);
        $wef_data = array('sal_temp_id' => $userid, 'monthly' => $net_salary, 'ctc_factor' => $ctc_factor, 'q_ctc' => $qualifying_ctc, 'timefrom' => $period_from, 'timeto' => $period_to,'numberOfMonths' => $numberOfMonths,'annual_ctc' => $annual_sal);
                $this->db->insert('tbl_salary_wef', $wef_data);

        set_alert('success', 'Salary Added Successfully');
        redirect(base_url('admin/payroll/manage_salary'));
    }

    public function salary_report()
    {   
        $data['title'] = _l('salary_report');
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);


        $subview = 'salary_report';

        $this->load->view('admin/payroll/' . $subview, $data);
    }

    public function get_salary_report()
    {   
        
        $staff_id = $this->input->post('staff_id', TRUE);
        $this->db->select('bio_id');
        $this->db->where('staffid',$staff_id);
        $staff_bio = $this->db->get('tblstaff')->row();
        $staff_bio_id = $staff_bio->bio_id;
        $date = explode('-', $this->input->post('date', TRUE));
        $month = $date[0];
        $year = $date[1];
        $data['tmonth'] = $month . "-" . $year;
        $dayinmonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        /*Sunday Calculation in current Month */
        $monthName = date("F", mktime(0, 0, 0, $month));
        $fromdt = date('Y-m-01 ', strtotime("First Day Of  $monthName $year"));
        $todt = date('Y-m-d ', strtotime("Last Day of $monthName $year"));

        $num_sundays = '';
        for ($i = 0; $i <= ((strtotime($todt) - strtotime($fromdt)) / 86400); $i++) {
            if (date('l', strtotime($fromdt) + ($i * 86400)) == 'Sunday') {
                $num_sundays++;
            }
        }

        $totalsunday = $num_sundays;


        /* End of Sunday Calculation*/

        /*Holiday in month start*/
        $this->db->select_sum('days');
        $this->db->where(array('month(quota)' => $month, 'year(quota)' => $year));
        $hlday_inmth = $this->db->get('tblholidays')->row();
        $holi_month = $hlday_inmth->days;


        /*echo $holi_month;
       
        exit;*/
        /*Holiday in month end*/
        if ($holi_month > 0) {
            //$newdayinmonth = $dayinmonth + $holi_month;
            $num = $dayinmonth - ($totalsunday + $holi_month);

        } else {
            $num = $dayinmonth - $totalsunday;
        }
        $this->db->select('net_salary');
        $this->db->where(array('salary_grade' => $staff_id));
        $staffsal = $this->db->get('tbl_salary_template')->result_array();
        $staffnetsal = $staffsal[0]['net_salary'];

        $monthpds = $staffnetsal / $num;

        
        $tableatt = "deviceLogs_1_2020";
        if (!empty($staff_id)) {
            $this->db->select('*');
            $this->db->where(array('UserId' => $staff_bio_id, 'month(LogDate)' => $month, 'year(LogDate)' => $year));
            //$this->db->where($where);
            // $this->db->group_by("date(LogDate)");
            $salarydays = $this->db->get($tableatt)->num_rows();
            $this->db->select('*,CONCAT(firstname," ",lastname) as full_name');
            $this->db->where(array('staffid' => $staff_id));
            $data['employee'] = $this->db->get('tblstaff')->result_array();
            $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
            $this->db->select('*');
            $this->db->where(array('UserId' => $staff_bio_id, 'month(LogDate)' => $month));
            // $this->db->group_by("date(LogDate)");
            $data['nworking'] = $this->db->get($tableatt)->num_rows();
            $this->db->select('*');
            $this->db->where(array('UserId' => $staff_bio_id, 'month(LogDate)' => $month, 'year(LogDate)' => $year));
            // $this->db->group_by("date(LogDate)");
            $data['nleave'] = $this->db->get($tableatt)->num_rows();
            $data['monthsal'] = $monthpds * $salarydays;


        }

        $data['title'] = _l('salary_report');
        $this->load->view('admin/payroll/salary_report', $data);
    }

    public function make_payment($id = "")
    {
        if (!empty($id)) {


            $staff_id = $this->uri->segment(4);
            $date = explode('-', $this->uri->segment(5));
            $month = $date[0];
            $year = $date[1];
            $data['tmonth1'] = $year . "-" . $month;
            $data['employeesal'] = $this->payroll_model->getemployeesal($staff_id);
            $data['monthsal'] = $this->uri->segment(6);

            /*print_r($data['employeesal']);
            exit;*/


        } else {

        }
        $data['salary_payment_info'] = $this->payroll_model->getpaymenthistory($staff_id);
        /*print_r($data['salary_payment_info']);
        exit;*/
        $data['employeedetails'] = $this->payroll_model->employeedetails($staff_id);
        $data['title'] = _l('salary_template_details');
        $this->load->view('admin/payroll/make_payment', $data);
    }

    public function get_payment()
    {

        $staff_id = $this->input->post('staffid', TRUE);
        $payment_month = $this->input->post('payment_month', TRUE);
        $fine_deduction = $this->input->post('fine_deduction', TRUE);
        $fine_deduction_comment = $this->input->post('fine_deduction_comment', TRUE);
        $payment_type = $this->input->post('payment_type', TRUE);
        $comments = $this->input->post('comments', TRUE);
        $paid_date = date('Y-m-d');
        $gross_salary = $this->input->post('gross_salary', TRUE);
        $net_salary = $this->input->post('net_salary', TRUE);
        $amount = $this->input->post('payment_amount', TRUE);

        $resultData = array('user_id' => $staff_id, 'payment_month' => $payment_month, 'fine_deduction' => $fine_deduction, 'fine_deduction_comment' => $fine_deduction_comment, 'payment_type' => $payment_type, 'comments' => $comments, 'paid_date' => $paid_date, 'gross_salary' => $gross_salary, 'net_salary' => $net_salary, 'amount' => $amount);
        /*print_r($resultData);
        exit;*/
        $this->db->insert(' tbl_salary_payment', $resultData);

        set_alert('success', 'Salary Issued Successfully');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function generate_payslip($id = '', $pdf = '')
    {
        $salary_payment_id = $this->uri->segment(4);
        $data['id'] = $salary_payment_id;
        $data['salary_payment_info'] = $this->payroll_model->getpaymenthistory($salary_payment_id);
        $staff_id = $data['salary_payment_info'][0]->user_id;
        $data['employeedetails'] = $this->payroll_model->employeedetails($staff_id);
        $data['employeesal'] = $this->payroll_model->getemployeesal($staff_id);
        $data['title'] = _l('salary_report');
        if (!empty($pdf)) {
            $html = $this->load->view('admin/payroll/salary_slip', $data, true);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->set_option('defaultFont', 'Courier');
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->render();
            $dompdf->stream();
            //$pdf = $dompdf->output();
        } else {
            $this->load->view('admin/payroll/generate_payslip', $data);
        }
    }

    function test()
    {

        $html = "<html><body>hello</body></html>";
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->set_option('defaultFont', 'Courier');
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->render();
        $dompdf->stream();
        $pdf = $dompdf->output();
    }

    function salary_payment_details_pdf()
    {

    }
    
    public function incentive_select()
    {
        $data['title'] = "Select Designation For Incentive Calculation";
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        $this->payroll_model->_table_name = "tbl_salary_template"; // table name
        $this->payroll_model->_order_by = "salary_template_id"; // $id
        $data['roles'] = $this->roles_model->get();
        $data['all_salary_template'] = $this->payroll_model->get_incen();
        $this->load->view('admin/payroll/incentive_select', $data);
    }

    public function save_incentive_select(){

    	$company = $this->input->post('company', TRUE);
    	$departments = $this->input->post('departments', TRUE);
    	$teamselect = $this->input->post('teamselect', TRUE);
    	$empselect = $this->input->post('empselect', TRUE);
    	$datestart = $this->input->post('datestart', TRUE);
    	$dateend = $this->input->post('dateend', TRUE);
    	$credit_score = $this->input->post('credit_score', TRUE);
    	$incantive = $this->input->post('incantive', TRUE);

    	if($empselect){
    		// Defind Staff Incentive
    		foreach ($empselect as $emp) {
	    		$resultData = array('company' => $company, 'departments' => $departments, 'teamselect' => $teamselect, 'staff_id' => $emp, 'datestart' => $datestart, 'dateend' => $dateend, 'inc_type' =>1);
	        	$this->db->insert('tbl_incentive_select_staff', $resultData);
	        	$incentive_id = $this->db->insert_id();
	        	
	        	for($i=0;$i<count($credit_score);$i++){ 
		    		$incData = array('incentive_id' =>$incentive_id, 'empselect' => $emp, 'datestart' => $datestart, 'dateend' => $dateend, 'credit_score' => $credit_score[$i], 'incantive' => $incantive[$i], 'ctc' =>"QCTC");
		        	$this->db->insert('tbl_incentice_select',$incData);
		        	//echo $this->db->last_query()."<br>";
		    	}
	    	}

    	}else{
    		$staff = $this->db->where('id',$teamselect)->select('staffid')->get('tblteams')->result();
	        $resultData = array('company' => $company, 'departments' => $departments, 'teamselect' => $teamselect, 'staff_id' => $staff[0]->staffid,'datestart' => $datestart, 'dateend' => $dateend, 'inc_type' =>2);
    		$this->db->insert('tbl_incentive_select_staff', $resultData);
	        $incentive_id = $this->db->insert_id();

    		for($i=0;$i<count($credit_score);$i++){ 
		    	$incData = array('incentive_id' =>$incentive_id, 'empselect' =>$staff[0]->staffid, 'datestart' => $datestart, 'dateend' => $dateend, 'credit_score' => $credit_score[$i], 'incantive' => $incantive[$i], 'ctc' =>"QCTC");
		        $this->db->insert('tbl_incentice_select',$incData);
		    }
    	}

    	set_alert('success', 'Incentive Added Successfully ');
        redirect('admin/payroll/getIncentiveSelect');
    }

    public function getIncentiveSelect()
    {   
        $this->session->set_userdata('datestart', '');
        $this->session->set_userdata('dateend', '');
    	$start_date = date('Y');
    	$end_date = $start_date+1;
    	$data['title'] = "View Designation For Incentive Calculation";
    	$data['incentive'] = $this->payroll_model->get_IncentiveSelect($start_date,$end_date );
    	//echo $this->db->last_query(); exit();
    	$this->load->view('admin/payroll/incentive_select_list', $data);
    }

    public function editIncentiveSelect($id)
    {
    	$data['title'] = "Edit Designation For Incentive Calculation";
    	$data['incentive'] = $this->payroll_model->get_edit_IncentiveSelect($id);
    	$this->load->view('admin/payroll/editIncentiveSelect', $data);
    }

    public function edit_incentive_select()
    {	
    	$incantive_Id = $this->input->post('incantive_Id', TRUE);
    	$company = $this->input->post('company', TRUE);
    	$departments = $this->input->post('departments', TRUE);
    	$teamselect = $this->input->post('teamselect', TRUE);
    	$empselect = $this->input->post('empselect', TRUE);
    	$datestart = $this->input->post('datestart', TRUE);
    	$dateend = $this->input->post('dateend', TRUE);
    	$credit_score = $this->input->post('credit_score', TRUE);
    	$incantive = $this->input->post('incantive', TRUE);

    	if($empselect){
    		// Defind Staff Incentive
    		$resultData = array('company' => $company, 'departments' => $departments, 'teamselect' => $teamselect, 'staff_id' => $empselect, 'datestart' => $datestart, 'dateend' => $dateend, 'inc_type' =>1);
	        $this->db->where('id', $incantive_Id);
            $this->db->update('tbl_incentive_select_staff', $resultData);
	        
	        $this->db->where('incentive_id', $incantive_Id)->delete('tbl_incentice_select');

            for($i=0;$i<count($credit_score);$i++){ 
		    	$incData = array('incentive_id' =>$incantive_Id, 'empselect' => $empselect, 'datestart' => $datestart, 'dateend' => $dateend, 'credit_score' => $credit_score[$i], 'incantive' => $incantive[$i], 'ctc' =>"QCTC");
		        $this->db->insert('tbl_incentice_select',$incData);
		    }

    	}else{ //echo "team"; exit();
    		$staff = $this->db->where('id',$teamselect)->select('staffid')->get('tblteams')->result();
    		$resultData = array('company' => $company, 'departments' => $departments, 'teamselect' => $teamselect, 'staff_id' => $staff[0]->staffid, 'datestart' => $datestart, 'dateend' => $dateend, 'inc_type' =>2);
	        $this->db->where('id', $incantive_Id);
            $this->db->update('tbl_incentive_select_staff', $resultData);
	        
	        $this->db->where('incentive_id', $incantive_Id)->delete('tbl_incentice_select');

    		for($i=0;$i<count($credit_score);$i++){
		    	$incData = array('incentive_id' =>$incantive_Id, 'empselect' =>$staff[0]->staffid, 'datestart' => $datestart, 'dateend' => $dateend, 'credit_score' => $credit_score[$i], 'incantive' => $incantive[$i], 'ctc' =>"QCTC");
		        $this->db->insert('tbl_incentice_select',$incData);
		    }
    	}

    	set_alert('success', 'Incentive Updated Successfully ');
        redirect('admin/payroll/getIncentiveSelect');
    }
    

    public function delete_incentive($id){
    	$this->db->where('id', $id)->delete('tbl_incentive_select_staff');
    	$this->db->where('incentive_id', $id)->delete('tbl_incentice_select');
    }

    public function define_incentive_filter(){
        if(isset($_REQUEST['datestart'])){
            $datestart = $_REQUEST['datestart'];
            $dateend = $_REQUEST['dateend'];
        }else{
            $datestart =$this->session->userdata('datestart');
            $dateend =$this->session->userdata('dateend');
        }
    	
		$data['incentive'] = $this->payroll_model->filter_IncentiveSelect($datestart,$dateend);

        $this->session->set_userdata('datestart', $datestart);
        $this->session->set_userdata('dateend', $dateend);

		$this->load->view('admin/payroll/incentive_select_list', $data);

    }

    public function define_incentive()
    {
        $data['title'] = "View / Define Incentive ";
        $staff_id = $this->uri->segment(4);
        //echo $staff_id;
        $data['employeedetails'] = $this->payroll_model->employeedetails($staff_id);
        $data['employeesal'] = $this->payroll_model->getemployeesal($staff_id);
        $this->db->select_sum('net_credit');
        $this->db->where('converted_by', $staff_id);
        $data['creditscore'] =  $this->db->get('tblbusiness')->result();
        $data['incentive_payment_info'] = $this->payroll_model->getincentivehistory($staff_id);
        //print_r($this->db->last_query());
        //print_r($data['incentive_payment_info']);
        $this->load->view('admin/payroll/define_incentive', $data);
    }
    
    function save_incentive()
    {
        $staff_id = $this->input->post('staffid', TRUE);
        $financial_year = $this->input->post('finacial_year', TRUE);
        $ctc = $this->input->post('exp_annual_ctc', TRUE);
        $baby_shitiing_loss = $this->input->post('baby_shitiing_loss', TRUE);
        $qualifying_ctc  = $this->input->post('qualifying_ctc', TRUE);
        $credit_score_fy = $this->input->post('credit_score_fy', TRUE);
        $cs_per_qctc = $this->input->post('cs_per_qctc', TRUE);
        $pl_over_ctc = $this->input->post('pl_over_ctc', TRUE);
        $rm_incentive_fy = $this->input->post('payment_amount1', TRUE);
        
        
        $this->db->select('staff_id');
        $this->db->where(array('tt.staff_id' => $staff_id, 'tt.finacial_year' => $financial_year ));
        $query = $this->db->get('tblincentive tt');
        $checkdate = $query->num_rows();
        if ($checkdate > 0) {
            set_alert('warning', "Staff Incentive Already Defined For $financial_year ");
            redirect($_SERVER['HTTP_REFERER']);
        } 
       
        
        
        
        $resultData = array('staff_id' => $staff_id, 'finacial_year' => $financial_year, 'ctc' => $ctc, 'baby_sitting_loss' => $baby_shitiing_loss, 'qualifying_ctc' => $qualifying_ctc, 'credit_score_fy' => $credit_score_fy, 'cs_per_qctc' => $cs_per_qctc, 'pl_over_ctc' =>$pl_over_ctc, 'rm_incentive_fy' => $rm_incentive_fy );
        
        
        
        $this->db->insert('tblincentive', $resultData);

        set_alert('success', 'Incentive Added Successfully ');
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function deleteincentive()
    {
        $id = $this->uri->segment(4);
        

           $success = $this->payroll_model->deleteincentive($id);

           set_alert('success', _l('deleted', "Incentive"));

           redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function incentive_report()
    {
        $data['title'] = "Incentive Report ";
        $data['bodyclass'] = 'hide-sidebar';
        $data['rmconverted'] = $this->payroll_model->rmconverted();
        $data['incentive_payment_info'] = $this->payroll_model->get_incen();
        
        $this->load->view('admin/payroll/incentive_report', $data);
    }
    
    function custom_incentive_filter()
    {
        
        $leads = $this->payroll_model->get_bussiness_filter();
        $sr=0;
        foreach ($leads as  $value) {
           $count =  $this->db->where('staffid', $value->salary_grade)->where('company_id',1)->get('tblteams')->num_rows();
            $leads[$sr]->count=$count;
            $sr++;
        }
        $transctiondatestart = $_GET['datestart'];
        $transctiondateend = $_GET['dateend'];
        $this->printBussinessData($leads, $transctiondatestart,$transctiondateend); 
    }

    public function getIncentive($bep,$net_credit){
        // return $net_credit.'-'.$bep ."<br>";
       $profit =  $net_credit-$bep;
       $staffProfitPer = ($profit/$bep)*100;
       $staffProfitPer = number_format($staffProfitPer, 2, '.', '');

       if(($staffProfitPer > "0") && ($staffProfitPer <= "10")){
            $staffincen =  ($profit*5)/100;
       } elseif(($staffProfitPer > "10") && ($staffProfitPer <= "20")){
            $staffincen =  ($profit*10)/100;
       }elseif(($staffProfitPer > "20") && ($staffProfitPer <= "30")){
            $staffincen =  ($profit*15)/100;
       }elseif(($staffProfitPer > "30") && ($staffProfitPer <= "40")){
            $staffincen =  ($profit*20)/100;
       }elseif(($staffProfitPer > "40") && ($staffProfitPer <= "50")){
            $staffincen =  ($profit*25)/100;
       }elseif(($staffProfitPer > "50")){
            $staffincen =  ($profit*40)/100;
       } else{
            $staffincen =  ($profit*0)/100;
       }  
       return $staffincen;
    }
    
    private function printBussinessData($leads,$transctiondatestart,$transctiondateend)
    {
         if (!empty($leads)) { ?>
                            <table name="incentive_report"  id="incentive_report" class="table dt-table scroll-responsive">
                                <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th class="bold">Staff Name</th>
                                    <th class="bold">Finacial Year</th>
                                    <th class="bold">BEP</th>
                                    <th class="bold">Baby Sitting Loss</th>
                                    <th class="bold">Qualifying CTC</th>
                                    <th class="bold">Credit score</th>
                                    <th class="bold">P/L over QCTC</th>
                                    <th class="bold">Credit score as % of QCTC</th>
                                    <th class="bold">RM's Incentive for the FY</th>
                                    <!--<th class="bold">TLs' incentive for the FY</th>
                                    <th class="bold">GL's Bonus for the FY</th>-->
                                </tr>
                                </thead>
                                <tbody class="">
                                <?php 

                                $leave_start = DateTime::createFromFormat('Y-m-d', $transctiondatestart);
                                $leave_end = DateTime::createFromFormat('Y-m-d', $transctiondateend);
                                $customdate = $leave_end->diff($leave_start)->format("%a")+1;
                                 foreach ($leads as $alllead) {
                                    if($alllead->count<=1){
                                    ?>
                                    <tr>
                                        <td><?= @++$i; ?></td>
                                        <td><?= $alllead->firstname; ?>
                                        </td>
                                        <td><?= $alllead->period_to; ?>
                                        </td>
                                        <td for="bep"> 
                                        <?php  
                                        $staff_exp_annual_ctc=0; $total_mon=0;
                                        $this->db->where("timefrom>=", $transctiondatestart);
                                        $this->db->where("timeto<=", $transctiondateend);
                                        $this->db->where('sal_temp_id',$alllead->salary_grade);
                                        $data_salary_wef = $this->db->get('tbl_salary_wef')->result();
                                        foreach($data_salary_wef as $sal_wef_2)
                                        { 
                                            $leave_start = DateTime::createFromFormat('Y-m-d', $sal_wef_2->timefrom);
                                            $leave_end = DateTime::createFromFormat('Y-m-d', $sal_wef_2->timeto);
                                            $diffmon= $leave_end->diff($leave_start)->format("%m")+1;

                                            $date = date("d",strtotime($sal_wef_2->timefrom));
                                            $pre_day_sal = $sal_wef_2->monthly/30;
                                                                                
                                            $first_mon_day = $leave_start->format("d");
                                            $last_mon_day = $leave_end->format("d");
                                            $remain_month = $diffmon - 2;
                                                                               
                                            $first_mon_day =  30 - $first_mon_day + 1;
                                            if($last_mon_day==31){
                                                $last_mon_day = 30;
                                            }

                                            $first_mon_sal = $first_mon_day*$pre_day_sal;
                                            $last_mon_sal = $last_mon_day*$pre_day_sal;
                                            $two_mon_sal = $first_mon_sal + $last_mon_sal;

                                            if(($date>=4) && ($remain_month>0)){
                                                $remain_month_sal = $sal_wef_2->monthly*($remain_month+1);
                                            }else{
                                                $remain_month_sal = $sal_wef_2->monthly*$remain_month;
                                            }
                        
                                        $pre_mon_sal = $remain_month_sal + $two_mon_sal;
                                        $pre_exp_annual_ctc = $pre_mon_sal*$sal_wef_2->ctc_factor;
                                        $staff_exp_annual_ctc = $staff_exp_annual_ctc+$pre_exp_annual_ctc;
                                        $total_mon =  $total_mon + $sal_wef_2->numberOfMonths;
                                        }

                                        $direct_cost = $alllead->direct_cost*$total_mon;
                                        $bep= $staff_exp_annual_ctc+$direct_cost;
                                    ?>

                                        <?= number_format($bep, 2, '.', ''); ?></td>
                                        <td for="baby_shitiing_loss">0
                                        </td>
                                        <td> 
                                        <?php 
                                          $qualifying_ctc =   $bep + $alllead->baby_shitiing_loss;
                                          echo number_format($qualifying_ctc, 2, '.', '');
                                        ?> 
                                        </td>
                                        <td>
                                        <?php
                                        $this->db->select_sum('net_credit');
                                        $this->db->where('converted_by', $alllead->salary_grade);
                                        $this->db->where('status', "Verified");
                                        $this->db->where('transaction_date >=', $transctiondatestart);
                                        $this->db->where('transaction_date <=', $transctiondateend);
                                        $creditscore =  $this->db->get('tblbusiness')->result();

                                        
                                        ?>
                                        <?= number_format($creditscore[0]->net_credit, 2, '.', ''); ?>
                                        </td>
                                      <td class="text-center pl_over">
                                        <?php 
                                          $creadit =   $creditscore[0]->net_credit - $qualifying_ctc;
                                          echo number_format($creadit, 2, '.', '');
                                        ?>
                                        </td>
                                        <td class="pl_over"> 
                                        <?php
                                          $profit_per=($creadit*100)/ $qualifying_ctc;
                                          if($profit_per<0){
                                             $profit_per=0;
                                          }
                                          echo number_format($profit_per, 2, '.', '');
                                        ?>   
                                        </td>
                                        <td><b>
                                        <?php //echo $alllead->salary_grade;
                                            $this->db->select('id');
                                            $this->db->where('staff_id',$alllead->salary_grade);
                                            $this->db->where('departments',12);
                                            $this->db->where('inc_type',1);
                                            $this->db->where("datestart>=",  $transctiondatestart);
                                            $this->db->where("dateend<=", $transctiondateend);
                                            $incentive =  $this->db->get('tbl_incentive_select_staff')->result();
                                            //$profit_per
                                            if($incentive){
                                                $this->db->where('incentive_id',$incentive[0]->id);
                                                $this->db->where('credit_score<=',$profit_per);
                                                $this->db->order_by('id', 'DESC');
                                                $this->db->limit(1);  
                                                $incentive_data =  $this->db->get('tbl_incentice_select')->result();
                                                $incentive_data[0]->incantive;

                                                if($profit_per<=0){
                                                    $inc = ($creadit*0)/100;
                                                }else{
                                                    $inc = ($creadit*$incentive_data[0]->incantive)/100;
                                                }
                                            }else{
                                              $inc = 0;
                                            }

                                            echo number_format($inc, 2, '.', '');
                                        ?>  
                                        </b></td>
                                        <!--<td><?= $alllead->tl_incentive_fy; ?></td>
                                        <td><?= $alllead->gl_incentive_fy; ?></td>-->
                                    </tr>
                                    <?php 
                                    }
                                $totbep = $totbep + (number_format($onedaybep*$customdate, 2, '.', ''));
                                $tocredit = $tocredit +  number_format($creditscore[0]->net_credit, 2, '.', '');
                                        }

                                        $totalbep = $totbep;
                                        $totalcredit = $tocredit;
                                        ?>
                                        <tr>
                                    <th ></th>
                                    <th ></th>
                                    <th ></th>
                                    <th style="font-weight: bold;"><span id="sum_bep_amount">Total BEP: Rs. <?php echo $totalbep; ?></span></th>
									<th ></th>
									<th ></th>
                                    <th style="font-weight: bold;"><span id= "sum_credit_amount">Net Credit: Rs. <?php echo $totalcredit; ?></span></th>
									<th ></th>
									<th ></th>
									
                                    
									<th ></th>
									<th ></th>
                                    <th ></th>
									<th ></th>
								
								</tr>
                                    <script>
    $(document).ready(function() {
    $('.pl_over:contains("-")').css('color', 'red');
  $('.status:contains("Received")').css('color', 'green'); 
});
</script>



<script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>
                                
                                </tbody>
                            </table>
                            <?php
                        } else {
                            echo "No Incentive Report Found";
                        } 
        
    }
    
    public function delete_wef_sal()
    {
        $wefid = $this->uri->segment('4');
        $this->db->where('id', $wefid);
        $salary_wef = $this->db->get('tbl_salary_wef')->result();
        $sal_temp_id = $salary_wef[0]->sal_temp_id;

        $this->db->where('salary_grade', $sal_temp_id);
        $data_salary_temp = $this->db->get('tbl_salary_template')->result();
        $direct_cost = $data_salary_temp[0]->direct_cost;
        $baby_shitiing_loss = $data_salary_temp[0]->baby_shitiing_loss;
        $last_finacial_year = $data_salary_temp[0]->last_finacial_year;
        $start_finacial_year = $data_salary_temp[0]->start_finacial_year;
        $bep = $data_salary_temp[0]->bep;
        $annual_sal = $data_salary_temp[0]->annual_sal;
        $exp_annual_ctc = $data_salary_temp[0]->exp_annual_ctc;

        $leave_start=DateTime::createFromFormat('Y-m-d',$salary_wef[0]->timefrom);
        $leave_end = DateTime::createFromFormat('Y-m-d',$salary_wef[0]->timeto);
        $diffDays = $leave_end->diff($leave_start)->format("%m")+1;

        $this->db->where("timefrom>=", $start_finacial_year);
        $this->db->where('sal_temp_id', $sal_temp_id);
        $this->db->order_by("id", "desc");
        $data_salary_wef = $this->db->get('tbl_salary_wef')->result();
        $numberOfMonths = $data_salary_wef[0]->numberOfMonths;
        $total_month= $numberOfMonths- $diffDays;

        
        $preOfMonths = $salary_wef[0]->numberOfMonths;
        $prectc_factor = $salary_wef[0]->ctc_factor;
        $pre_day_sal = $salary_wef[0]->monthly/30;

        $first_mon_day = $leave_start->format("d");
        $last_mon_day = $leave_end->format("d");
        $remain_month = $diffDays - 2;
                                       
        $first_mon_day =  30 - $first_mon_day + 1;
        if($last_mon_day==31){
            $last_mon_day = 30;
        }

        $first_mon_sal = $first_mon_day*$pre_day_sal;
        $last_mon_sal = $last_mon_day*$pre_day_sal;
        $two_mon_sal = $first_mon_sal + $last_mon_sal;
        $remain_month_sal = $salary_wef[0]->monthly*$remain_month;
        $pre_mon_sal = $remain_month_sal + $two_mon_sal;
        $pre_exp_annual_ctc=$pre_mon_sal*$salary_wef[0]->ctc_factor;
        $direct_cost = $direct_cost*$diffDays;
        $wef_bep = $direct_cost+$pre_exp_annual_ctc;

        $this->db->where("timefrom>=", $start_finacial_year);
        $this->db->where('sal_temp_id', $sal_temp_id);
        $this->db->order_by("id", "desc");
        $this->db->where("id !=",$wefid);

        $data_salary_wef = $this->db->get('tbl_salary_wef')->result();
        $wef_id = $data_salary_wef[0]->id;
        $monthly_sal = $data_salary_wef[0]->monthly;
        $ctc_factor = $data_salary_wef[0]->ctc_factor;
        $timefrom = $data_salary_wef[0]->timefrom;
        $timeto = $data_salary_wef[0]->timeto;

        $basic = 50;
        $house_rent = 40;
        $special = 35;
        $conveyance = 25;

        $basic_salary = ($monthly_sal*$basic)/100;
        $house_rent_allowance = ($basic_salary*$house_rent)/100;
        $special_allowance =  ($basic_salary*$special)/100;
        $conveyance_allowance =  ($basic_salary*$conveyance)/100;
        $bep = $bep-$wef_bep;
        $annual_sal = $annual_sal - $pre_mon_sal;
        $exp_annual_ctc = $exp_annual_ctc - $pre_exp_annual_ctc;
        
        $this->db->where('id', $wefid);
        $this->db->delete('tbl_salary_wef');

        if(count($data_salary_wef)>0){
           $resultData1 = array('basic_salary' => $basic_salary,'bep' => $bep,'period_to' => $timeto, 'period_from' => $timefrom, 'annual_sal' => $annual_sal, 'ctc_factor' => $ctc_factor, 'exp_annual_ctc' => $exp_annual_ctc, 'house_rent_allowance' => $house_rent_allowance,'special_allowance' => $special_allowance, 'conveyance_allowance' => $conveyance_allowance, 'net_salary' => $monthly_sal, 'qualifying_ctc' => $bep);
            $result = array('numberOfMonths' => $total_month,'annual_ctc' => $annual_sal);
            $this->db->where('id', $wef_id);
            $this->db->update('tbl_salary_wef', $result);

       }else{

        $timefrom = date('Y', strtotime($last_finacial_year))-1;
        $timeto =  date('Y', strtotime($start_finacial_year))-1;
       
          $resultData1 = array('basic_salary' => 0, 'bep' => 0,'period_to' => $timeto, 'period_from' =>$timefrom, 'annual_sal' =>0, 'ctc_factor' => 0, 'exp_annual_ctc' => '', 'house_rent_allowance' => 0,'special_allowance' => 0, 'conveyance_allowance' => 0, 'net_salary' => 0,'qualifying_ctc' => 0);
       }
        /*print_r($result);
        exit();*/
       $this->db->where('salary_grade', $sal_temp_id);
       $this->db->update('tbl_salary_template', $resultData1);
       /**/

        set_alert('success', 'W.E.F. Salary Deleted Successfully');
        redirect($_SERVER['HTTP_REFERER']);
        
        
    }
    
    public function emp_salary()
    {
        $search = $this->input->post('search', TRUE);
         if (!empty($search)) {
            $data['edit'] = true;
         }
         $user_id = $this->input->post('staff_id', TRUE);
         if (!empty($user_id)) {
            $data['user_id'] = $user_id;
         } else {
            $data['user_id'] = $this->session->userdata('staff_user_id');
         }
         $data['active'] = date('Y');
         //it();
        
         $this->db->select('*');
         //$this->db->order_by('department_id');
         $this->db->where('active',1);
         $ignore = array(1, 25,26,52);
         $this->db->where_not_in('staffid', $ignore);
		 $this->db->order_by("firstname", "asc");
         $data['staff_members'] = $this->db->get('tblstaff')->result_array();
         
         $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname, tblstaff.staffid');
         $this->db->where('active',1);
         $ignore = array(1, 25,26,52);
         $this->db->where_not_in('staffid', $ignore);
		 $this->db->order_by("firstname", "asc");
         $data['staffs'] = $this->db->get('tblstaff')->result();
         // echo $this->db->last_query();exit;
         $data['list'] = array();
            $month = '11';
            $year = '2021';
            if($month == date("m") && $year == date("Y"))
            {
                $datelimit = date("d");
            }
            else
            {
                $datelimit = '31';
            }
            for($d='1'; $d<=$datelimit; $d++)
            {
                $time=mktime(12, 0, 0, $month, $d, $year);          
                if (date('m', $time)==$month)       
                    $data['list'][]=date('Y-m-d', $time);
            }
         // print_r($data);
         // die();
         $this->load->view('admin/payroll/emp_salary', $data);
    }
    
    public function adjustleavequota()
    {
        echo $staffid = $this->input->post('staffid');
        $bioid = $this->input->post('bioid');
        $actualdd = $this->input->post('actualdd');
        $end = $this->input->post('monthdate');
        $time=strtotime($end);
        $month = date('m', $time);
            $year = date('Y', $time);
            if($month <= '3')
            {
                
                $fi_end_year = $year;
                $fi_start_year = $year-1;
            }
            else
            {
               $fi_end_year = $year+1;
               $fi_start_year = $year; 
            }
        $start = $fi_start_year.'-04-01';
        $staffballeaves = get_leave_summary_satffwise($staffid, @$start, @$end);
        
        if ( !empty($staffballeaves) ) {
                                   foreach ($staffballeaves as $status) { ?>
                                       <td > 
                                              
                                          
                                     
                                              <?php
                                                 if ( isset($status['total']) )          {
                                                     
                                                     
                                                         echo  '<span style="color:#FF0000"> '.$status['leave_category'].'/' . $status['total'] . ' / ' . $status['totalleave'] . ' </span>';
                                                    
                                                     
                                                 }
                                                 else {
                                                      if($status['leave_category'] == 'LWP' && $status['total'] == '')
                                                     {
                                                      echo  '<span style="color:#FF0000"> '.$status['leave_category'].'/' . $status['total'] . ' / ' . $status['totalleave'] . ' </span>';
                                                    
                                                     
                                                  }
                                                     else
                                                     {
                                                      echo  '<span style="color:#FF0000"> '.$status['leave_category'].'/' . $status['total'] . ' / ' . $status['totalleave'] . ' </span>';
                                                    
                                                     
                                                  }
                                                 }
                                              ?>
                                          
                                           
                                       </td>
                                   <?php }
                                } 
        
        
        
        
    }

}