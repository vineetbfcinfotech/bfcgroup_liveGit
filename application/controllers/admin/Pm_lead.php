<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class Pm_lead extends Admin_controller{
    private $not_importable_leads_fields;
        public function __construct(){
            parent::__construct();
            $this->not_importable_leads_fields = do_action('not_importable_leads_fields', ['id', 'source', 'assigned', 'status', 'dateadded', 'last_status_change', 'addedfrom', 'leadorder', 'date_converted', 'lost', 'junk', 'is_imported_from_email_integration', 'email_integration_uid', 'is_public', 'dateassigned', 'client_id', 'lastcontact', 'last_lead_status', 'from_form_id', 'default_language', 'hash']);
            $this->load->model('leads_model');
            $this->load->model('product_model', 'pmodel');
            $this->load->model('departments_model', 'depart_model');
            $this->load->model('teams_model', 'teamsm');
            $this->load->model('leadsdata_model', 'leadsdata');
            $this->load->library("pagination");
            $this->load->helper('url');
            $this->load->library('excel');
            $this->load->library('pdf');
        }
        //project aquired function
        public function project_aquired(){
            $data['title'] = "Pending Projects";
            $data['business'] = "";	 
            $data['projects'] = $this->leadsdata->get_aquired_Projects();
            $this->load->view('admin/pc/pending_project', $data);
        }
        //project inprogress function
        public function inprogress_projects(){
            $data['title'] = "Inprogress Projects";
            $data['business'] = "";
            $data['projects'] = $this->leadsdata->get_inprogress_Projects();
            $this->load->view('admin/pc/inprogress_progress', $data);
        }
        //completed projects function
        public function completed_projects(){
            $data['title'] = "Approved Projects";
            $data['projects'] = $this->leadsdata->getCompletedProjects();
            $this->load->view('admin/pc/approved_project', $data);
        }
        //asf mail send function
        public function sendmail($leadid = '',$tbltype=""){
          $staff_id = $_SESSION['staff_user_id'];
            $all_staff = $this->db->get_where('tblstaff',array('staffid' => $staff_id))->row();
            // $leadId = end($this->uri->segment_array());
            $leadId = $leadid;
            // echo $tbltype; 
            // die();
            if($tbltype=='3'){
                $packageData = $this->leadsdata->getmultiBookData($leadId);
            }else{
                $packageData = $this->leadsdata->getleadsData($leadId);
            }
            echo '<pre>';
            // print_r($packageData);
            // die();
            // $to = $packageData->email;
            $to = 'vineet.bfcinfotech@gmail.com';
            $this->load->library('phpmailer_lib');
            $mail = $this->phpmailer_lib->load();
            $mail->IsSMTP();
            $subject = 'Welcome mail';
            $message = "<p>Hi <b>".$packageData->lead_author_name."</b>,</p>";
            $message .= "<p>Thanks for short listing BFC as your publisher. It's great to finally have you on board."; 
            $message .= "<p>Please allow me to start by introducing myself, I am ";
            if ($staff_id == 82) {
                $message .= "Gaurav ";
            }else{
                $message .= "Shivangi ";
            }
           
            $message .= "and I have been nominated by BFC Publications to guide your project, due to which, we shall remain in constant touch over the next few weeks.</p>";
            $message .= "<p>The idea behind this is to ensure that there is uninterrupted to-and-fro communication so you are up-to-date with the progress made at every turn.</p>";
            $message .= "</p>Just so you know, we are obligated to get your book published within minimum 45 days, starting the day after manuscript submission (No Commitments for final date)
            I also want you to know that we are open to suggestions moving forward. So if you'd like to talk about anything, or have a feedback, feel free to reach out through mail or over the phone.</p>";
            $message .="<p>Also, listed below is a hyperlink to the Author Submission Form (<a href=". base_url("admin/Asf_controller/index/". $packageData->email).">ASF</a>) that is to be filled at your end. This will help us learn more about you, your book, and most importantly your bank details wherein your royalty earnings shall be credited.</p>";
            $message .="<p>Looking forward to hear from you.</p>";
            $message .="<p>Happy Writing!</p>";
            $message .="To fill ASF <a href=". base_url("admin/Asf_controller/index/". $packageData->email).">Click here</a>";
         

          $message .="<br><br><br>Thanks & Regards,<br>";
			
            if ($staff_id == 82) {
                $message .="Gaurav Saxena<br>";
                $message .="Project Manager<br>";
                $message .="BFC Publications Pvt. Ltd.<br>";
                $message .="(M) +91 7307088330<br>";
                $message .="Email: gaurav@bfcpublications.com <br>";
                $message .="CP - 61| Viraj Khand | Gomti Nagar | Lucknow | 226010<br>";
                $mail->Username   = 'vineet.bfcinfotech@gmail.com';
                $mail->Password   = 'BFC@2021';
                $mail->SetFrom('gaurav@bfcpublications.com', "BFC Publications");
                
            }else if ($staff_id == 61) {
                $message .="Sr. Project Manager<br>";
                $message .="BFC Publications Pvt. Ltd.<br>";
                $message .="(M) +91 9511115760<br>";
                $message .="Email: shivangiyadav@bfcpublications.com <br>"; 
                $message .="CP - 61| Viraj Khand | Gomti Nagar | Lucknow | 226010<br>";
                // Email setup
                $mail->Username   = 'shivangiyadav@bfcpublications.com';
                $mail->Password   = '8303319818@2021';
                $mail->SetFrom('shivangiyadav@bfcpublications.com', "BFC Publications"); 
            }else{
                // $mail->Username   = 'ashishkverma@bfcpublications.com';
                // $mail->Password   = 'ashish@2020';
                // $mail->SetFrom('ashishkverma@bfcpublications.com', "BFC Publications"); 
            }

			

            $mail->SMTPDebug  = 0;
            $mail->SMTPAuth   = TRUE;
            $mail->SMTPSecure = "ssl";
            $mail->Port       = 465;
            $mail->Host       = "smtp.gmail.com";
            
            $mail->IsHTML(true);
            $mail->AddAddress($to, '');
            // $mail->addcc('gaurav@bfcpublications.com', '');
            // $mail->addcc('projectcoodinator.bfcpub@gmail.com', '');
            // if ($staff_id == 82) {
            //     $mail->addcc('shivangiyadav@bfcpublications.com', '');
            // }
            // $mail->addAttachment($file); 
               
            $mail->Subject = $subject;
            $mail->MsgHTML($message);

            if($mail->Send()){

              //notification work 
              $proj_name =  $packageData->lead_author_name.'_'.$packageData->lead_booktitle;
              $by = $this->session->userdata('staff_user_id');
            $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                 ->from('tblstaff')
                 ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                  $this->db->where('tblstaff.staffid',$by);
            $query = $this->db->get();
            $ret = $query->row();
                   $data1 = array(
                  'user_id'=> $leadId,
                  'take_by'=> $by,
              'role' => $ret->name,
              'project_name' => $proj_name,
              'author_name' => $packageData->lead_author_name,
              'book_name' => $packageData->lead_booktitle,
              'action' => 20,
              'message' => 'Welcome mail send Successfully',
              'discription' => ''.$proj_name.' project Welcome mail send by '.$ret->firstname.' '.$ret->lastname,
            );
                $this->db->insert('lead_all_action',$data1);

                $data_array = array(
                  'send_asf_mail_status'=> 1,
                  'asf_send_date' => date("Y-m-d")
                );
                $this->db->where('id', $leadId);
                if($tbltype=='3'){
                    $this->db->update('tblleads_create_package', $data_array);
                }else{
                    $this->db->update('tblleads', $data_array);
                }
              //echo $this->db->last_query();exit;  

                set_alert('success', _l('Mail sent successfully...'));
                redirect($_SERVER['HTTP_REFERER']);
            }else{
                set_alert('warning', _l('Mail not sent'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        //view asf from
        public function asf_from($value=''){
            $email = end($this->uri->segment_array());
            $this->load->view('admin/pc/asf_form');
        }
        //send agreement mail function
        public function send_agreement($value=''){

            $this->load->library('phpmailer_lib');
            $mail = $this->phpmailer_lib->load();
            $mail->IsSMTP();

          $staff_id = $_SESSION['staff_user_id'];
            $all_staff = $this->db->get_where('tblstaff',array('staffid' => $staff_id))->row();
         $id = $this->input->post('id');

         $asf_id = $this->input->post('asf_id');
         $all_asf_data = $this->db->get_where('chorus_asf',array('lead_id'=>$id))->row();
        //  print_r($all_asf_data);die;
         $all_lead_data = $this->db->get_where('tblleads',array('id'=>$id ))->row();
            
         $this->load->library('pdf');
         $this->load->helper('url');
         $this->load->library('excel');
         $name = $all_asf_data->asf_authorname;
         $regex = '~^[a-zA-Z]+$~';
         // $string = 'abdul';
         if (preg_match($regex, $name)) {
             $filename = str_replace(' ', '', $name)."_bfc_agreement.pdf";
         } else {
             $filename = $id."_bfc_agreement.pdf";
         }
        //  $filename = str_replace(' ', '', $name)."_bfc_agreement.pdf";
         $data['author_name']= $name;
         $data['address']= $all_asf_data->asf_address;
         $data['city']= $all_lead_data->city;
         $data['state']= $all_lead_data->state;
         $data['zip']= $all_lead_data->zip;
         $from = new DateTime($all_asf_data->asf_dob);
         $to   = new DateTime('today');
         $data['dob'] = $from->diff($to)->y;
         $data['lead_booking_amount_date']= $all_lead_data->lead_booking_amount_date;
         $html = $this->load->view('admin/pc/agreement_pdf', $data, true);
         $dompdf = new Dompdf\DOMPDF();
         $dompdf->load_html($html);
         $dompdf->render();
         $dompdf->set_paper('A4', 'landscape');
         $output = $dompdf->output();
         $filepath = 'assets/asf_agreementMail/'.$filename;
         file_put_contents($filepath, $output);
       
         $file = base_url('assets/asf_agreementMail/'.$filename); 
        //  $to = $all_lead_data->email;
         $to = 'vineet.bfcinfotech@gmail.com';
        //    $to = 'mayankmishrabfcinfotech@gmail.com';
         $subject = 'Agreement';
         $message = "<p>Hi <b>".$name."</b>,</p>";
         $message .= "<p>The agreement you and BFC Publications are to enter before your book can be published. You are required to respond to this mail, approving the terms of engagement listed in the agreement, to get the ball rolling.</p>"; 
        //  $message .= "<p>Please find attached with this mail, the agreement you and BFC Publications are to enter before your book can be published. You are required to respond to this mail, approving the terms of engagement listed in the agreement, to get the ball rolling.</p>"; 
         $message .= "<p>Click here to <a href='".$file."'>Download</a> Agreement</p>";
        //  $message .= "<p>".$file."</p>";
         $message .= "<p>Please be informed, we can only start work on the manuscript after acquiring your acceptance.</p>";
         $message .="<p>Happy Writing!</p>";
        //  $message .= $file;
        	$message .="Thanks & Regards,<br>";
			 if ($staff_id == 82) {
                $message .="Gaurav Saxena<br>";
                $message .="Project Manager<br>";
                $message .="BFC Publications Pvt. Ltd.<br>";
                $message .="(M) +91 7307088330<br>";
                $message .="Email: gaurav@bfcpublications.com <br>";
                $message .="CP - 61| Viraj Khand | Gomti Nagar | Lucknow - 226010<br>";
                // $mail->Username   = 'gaurav@bfcpublications.com';
                // $mail->Password   = 'gaurav@2022';
                $mail->Username   = 'vineet.bfcinfotech@gmail.com';
                $mail->Password   = 'BFC@2021';
                $mail->SetFrom('gaurav@bfcpublications.com', "BFC Publications");
                
            }else if ($staff_id == 61) {
                $message .="Sr. Project Manager<br>";
                $message .="BFC Publications Pvt. Ltd.<br>";
                $message .="(M) +91 9511115760<br>";
                $message .="Email: shivangiyadav@bfcpublications.com <br>"; 
                $message .="CP - 61| Viraj Khand | Gomti Nagar | Lucknow - 226010<br>";
                // Email setup
                $mail->Username   = 'shivangiyadav@bfcpublications.com';
                $mail->Password   = '8303319818@2021';
                $mail->SetFrom('shivangiyadav@bfcpublications.com', "BFC Publications"); 
            }else{}

        
        //  print_r($file);die;
        //  $this->email->set_newline("\r\n");
        //  $this->email->from($all_staff->email, 'BFC Publications');
        //  $this->email->to($to);
        //  $this->email->cc('projectcoodinator.bfcpub@gmail.com');  
        //  $this->email->subject($subject);
        //  $this->email->message($message);
        //  $this->email->attach($file);
        //  $result = $this->email->send();
         


         $mail->SMTPDebug  = 0;
         $mail->SMTPAuth   = TRUE;
         $mail->SMTPSecure = "ssl";
         $mail->Port       = 465;
         $mail->Host       = "smtp.rediffmailpro.com";
         
         $mail->IsHTML(true);
         $mail->AddAddress($to, '');
         //$mail->addcc('rajeshguptabfcinfotech@gmail.com', '');
        //  $mail->addcc('gaurav@bfcpublications.com', '');
        //  $mail->addcc('projectcoodinator.bfcpub@gmail.com', '');
        //  if ($staff_id == 82) {
        //      $mail->addcc('shivangiyadav@bfcpublications.com', '');
        //  }else{}
         
        //  $mail->addAttachment('https://bfcgroup.in/assets/asf_agreementMail/UjalaTripathi_bfc_agreement.pdf');
        //  $mail->addAttachment($file); 
         $file_path = base_url('assets/asf_agreementMail/');
         $mail->AddAttachment($file_path.'/'.$filename);
        //  $mail->addAttachment($file); 
         $mail->Subject = $subject;
         $mail->MsgHTML($message);
          

         if($mail->Send()){
               
            // if($result == "1"){

                $data_array = array(
                    'pdf_mail_agreement'=> $filename,
                );
                $this->db->where('id', $id);
                $updateed =  $this->db->update('tblleads',$data_array);
                

                // notification work
                $proj_name =  $all_lead_data->lead_author_name.'_'.$all_lead_data->lead_booktitle;
              $by = $this->session->userdata('staff_user_id');
            $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                 ->from('tblstaff')
                 ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                  $this->db->where('tblstaff.staffid',$by);
            $query = $this->db->get();
            $ret = $query->row();
                   $data1 = array(
                  'user_id'=> $id,
                  'take_by'=> $by,
              'role' => $ret->name,
              'project_name' => $proj_name,
              'author_name' => $all_lead_data->lead_author_name,
              'book_name' => $all_lead_data->lead_booktitle,
              'action' => 21,
              'message' => 'Mail Agreement Send Successfully',
              'discription' => ''.$proj_name.' project Mail Agreement mail send by '.$ret->firstname.' '.$ret->lastname,
            );
                $this->db->insert('lead_all_action',$data1);

                 echo "1";  
            }else{
                echo "0";
                        
            }
        }
        // Upload agreement function    
        public function upload_agreeent($value=''){
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                $url = "https://";   
            else  
                $url = "http://"; 

            $url.= $_SERVER['HTTP_HOST'];   
            $url.= $_SERVER['REQUEST_URI']; 
            $link_array = explode('/',$url);
            $current_url = $link_array[0]."/".$link_array[1]."/".$link_array[2]."/".$link_array[3]."/".$link_array[4]."/".$link_array[5]."/project_aquired";
            $id = $_POST['hidden_id'];
            $filename = $_FILES['file']['name'];
            $ckeck_image = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
                $filename = $ckeck_image->lead_author_name.'_'.$filename;
                $location = "assets/final_agreement/".$filename;
                $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
                $imageFileType = strtolower($imageFileType);

                /* Valid extensions */
                $valid_extensions = array("jpg","jpeg","png","pdf");
                $response = 0;
                if(in_array(strtolower($imageFileType), $valid_extensions)) {
                    if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                       $data_array = array(
                        'final_mail_agreement_upload'=>$filename,
                    );
                       $this->db->where('id',$id);
                       $this->db->update('tblleads',$data_array);

                       //notification work
                        $proj_name =  $ckeck_image->lead_author_name.'_'.$ckeck_image->lead_booktitle;
                        $by = $this->session->userdata('staff_user_id');
                        $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                        ->from('tblstaff')
                        ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                        $this->db->where('tblstaff.staffid',$by);
                        $query = $this->db->get();
                        $ret = $query->row();
                        $data1 = array(
                        'user_id'=> $id,
                        'take_by'=> $by,
                        'role' => $ret->name,
                        'project_name' => $proj_name,
                        'author_name' => $ckeck_image->lead_author_name,
                        'book_name' => $ckeck_image->lead_booktitle,
                        'action' => 22,
                        'message' => 'Agreement Upload Send Successfully',
                        'discription' => ''.$proj_name.' project Agreement Uploaded by '.$ret->firstname.' '.$ret->lastname,
                        );
                        $this->db->insert('lead_all_action',$data1);


                        if($ckeck_image->final_mail_agreement_upload){
                            unlink('assets/final_agreement/'.$ckeck_image->final_mail_agreement_upload);
                        }
                        $response = $location;
                        set_alert('success', _l('Final Agreement upload successfully...'));
                        // header("Location:".$current_url);   
                        redirect('admin/pm_lead/project_aquired');  

                    }
                }else{
                    set_alert('warning', _l('Please select a valid file'));
                    // header("Location:".$current_url);
                    redirect('admin/pm_lead/project_aquired');
                }
            }else{
                set_alert('warning', _l('Please choose a file..'));
                // header("Location:".$current_url);
                redirect('admin/pm_lead/project_aquired');
            }
        }
        //take up function
        public function take_up($value=''){
            $id = $this->input->post('id');
            $data_array = array(
                'pm_project_status'=>2,
                'lead_pm_takeup_date'=>date('y-m-d h:i:s'),
            );
            $tbl_type =  $this->input->post('tbl');
            $this->db->where('id',$id);
            if($tbl_type=='3'){
                $data = $this->db->update('tblleads_create_package',$data_array);
                //notification work
                $all_data = $this->db->get_where('tblleads_create_package',array('id'=>$id))->row();
            }else{
                $data = $this->db->update('tblleads',$data_array);
                //notification work
                $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
            }
                        $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                        $by = $this->session->userdata('staff_user_id');
                        $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                        ->from('tblstaff')
                        ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                        $this->db->where('tblstaff.staffid',$by);
                        $query = $this->db->get();
                        $ret = $query->row();
                        $data1 = array(
                        'user_id'=> $id,
                        'take_by'=> $by,
                        'role' => $ret->name,
                        'project_name' => $proj_name,
                        'author_name' => $all_data->lead_author_name,
                        'book_name' => $all_data->lead_booktitle,
                        'action' => 23,
                        'message' => 'Project Take up Successfully',
                        'discription' => ''.$proj_name.' project Take up by '.$ret->firstname.' '.$ret->lastname,
                        );
                        $this->db->insert('lead_all_action',$data1);
            set_alert('success', _l('Project goes to in process'));
        }
        // project in progress function
        public function pip($value=''){
            $id =  $this->uri->segment(4);
            $data['all_data'] = $this->db->get_where('tblleads',array('id'=>$id))->row();
             $data['affan_remaing_pages']= $this->common_func_for_remaining_p(84, 'gd'); 
             $data['surabhi_remaing_pages']= $this->common_func_for_remaining_p(83, 'gd'); 
              $data['manish_remaing_pages']= $this->common_func_for_remaining_p(85, 'fe'); 
       
         $data['amrendra_remaing_pages']= $this->common_func_for_remaining_p(81, 'fe'); 
         
         $data['gourav_remaing_pages']= $this->common_func_for_remaining_p(82, 'fe');
        

          $data['ravindra_remaing_pages']= $this->common_func_for_remaining_p(90,'pr'); 
        
          $data['bharti_remaing_pages']= $this->common_func_for_remaining_p(108,'pr');
          $data['chayan_remaing_pages']= $this->common_func_for_remaining_p(89,'pr');

            // $this->load->view('admin/pc/auther_project', $data);
            $this->load->view('admin/pc/auther_project1', $data);
        }
        //upload ms file function
        public function upload_ms($value=''){
            $id = $_POST['hidden_id'];
            $filename = $_FILES['file']['name'];
            $ckeck_ms = $this->db->get_where('tblleads',array('id'=>$id))->row();
            if ($filename) {
                $filename = $ckeck_ms->lead_author_name.'_'.$filename;
                $data_array = array(
                    'lead_raw_ms'=>$filename,
                );
                $this->db->where('id',$id);
                $this->db->update('tblleads',$data_array);
                
                if($ckeck_ms->lead_raw_ms){
                    unlink('assets/menuscript/raw_ms/'.$ckeck_ms->lead_raw_ms);
                }
                $location = "assets/menuscript/raw_ms/".$filename;
                $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
                $imageFileType = strtolower($imageFileType);
                /* Valid extensions */
                $valid_extensions = array("pdf", "doc", "docx",'zip');
                $response = 0;
                if(in_array(strtolower($imageFileType), $valid_extensions)) {
                    if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

                        //notification work
                        $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                        $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                        $by = $this->session->userdata('staff_user_id');
                        $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                        ->from('tblstaff')
                        ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                        $this->db->where('tblstaff.staffid',$by);
                        $query = $this->db->get();
                        $ret = $query->row();
                        $data1 = array(
                        'user_id'=> $id,
                        'take_by'=> $by,
                        'role' => $ret->name,
                        'project_name' => $proj_name,
                        'author_name' => $all_data->lead_author_name,
                        'book_name' => $all_data->lead_booktitle,
                        'action' => 23,
                        'message' => 'MS Uploaded Successfully',
                        'discription' => ''.$proj_name.' project MS Uploaded  by PM '.$ret->firstname.' '.$ret->lastname,
                        );
                        $this->db->insert('lead_all_action',$data1);

                        $response = $location;
                        set_alert('success', _l('MS uploaded successfully...'));
                        redirect($_SERVER['HTTP_REFERER']);
                        
                    }
                }else{
                  set_alert('warning', _l('MS file is pdf doc docx'));
                  redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
        //upload isbn function
        public function upload_isbn($value=''){
            $id = $_POST['hidden_id'];
            $ebook = $_POST['ebook'];
            $paperback =  $_POST['paperback'];
            
            $getebookSign = substr($ebook, 0, 1);
            if($getebookSign == '+'){
                $cur_ebook=str_replace("+","",$ebook); 
            }elseif($getebookSign == '-'){
                $cur_ebook=str_replace("-","",$ebook);
            }else{
                $cur_ebook=$ebook;
            }
            $getpaperbackSign = substr($paperback, 0, 1);
            if($getpaperbackSign == '+'){
                $cur_paperback=str_replace("+","",$paperback);     
            }elseif($getpaperbackSign == '-'){
                $cur_paperback=str_replace("-","",$paperback);
                
            }else{
                $cur_paperback=$paperback;
            }
            $data = array(
                // 'lead_isbn_status' => 1,
                'lead_isbn_ebook' => $cur_ebook,
                'lead_isbn_paperback' => $cur_paperback
            );
            $this->db->where('id',$id);
            $update =  $this->db->update('tblleads',$data);
            if ($update) {
                        //notification work
                        $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                        $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                        $by = $this->session->userdata('staff_user_id');
                        $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                        ->from('tblstaff')
                        ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                        $this->db->where('tblstaff.staffid',$by);
                        $query = $this->db->get();
                        $ret = $query->row();
                        $data_noti = array(
                        'user_id'=> $id,
                        'take_by'=> $by,
                        'role' => $ret->name,
                        'project_name' => $proj_name,
                        'author_name' => $all_data->lead_author_name,
                        'book_name' => $all_data->lead_booktitle,
                        'action' => 23,
                        'message' => 'MS Uploaded Successfully',
                        'discription' => ''.$proj_name.' project MS Uploaded  by PM '.$ret->firstname.' '.$ret->lastname,
                        );
                        $this->db->insert('lead_all_action',$data_noti);
             set_alert('success', _l('Lead uploaded successfully...'));
             redirect($_SERVER['HTTP_REFERER']);
            }
        }
          public function common_func_for_remaining_p($id='', $type='')
        {
        	 
            // echo '<pre>';
            // print_r($get_all_data);
        	 if ($type == 'gd') {
        	 	$get_all_data = $this->db->get_where('tblleads',array('lead_asf_assign_gd'=>$id , 'project_status_gd !='=>3))->result();
        	 	// echo $this->db->last_query(); exit;
        	 $total_fe_remaining_page = count($get_all_data);

        	 }else{
        
        	 	$get_all_data = $this->db->get_where('tblleads',array('project_assign_to'=>$id))->result();
        	 
	        	 if ($type == 'fe') {
	        	 	$ty = 'lead_fe_completed_pages';
	        	 }else{
	        	 	$ty = 'lead_pr_completed_pages';
	        	 }
	            foreach ($get_all_data as $key => $value) {
	            	$f_e_all_pages += $value->lead_book_pages;
	            	$f_e_completed_pages += $value->$ty;
	            }
	             $total_fe_remaining_page = $f_e_all_pages- $f_e_completed_pages;	
        	}
        	return $total_fe_remaining_page;
        	 
        }
        //view assign html
        public function select_assign_html($value='')
        {
            $select_id = $_POST['select_id'];
            $hidden_id = $_POST['hidden_id'];

         $manish_remaing_pages= $this->common_func_for_remaining_p(85, 'fe'); 
       
         $amrendra_remaing_pages= $this->common_func_for_remaining_p(81, 'fe'); 
         
         $gourav_remaing_pages= $this->common_func_for_remaining_p(82, 'fe');
        

          $ravindra_remaing_pages= $this->common_func_for_remaining_p(90,'pr'); 
        
          $varuna_remaing_pages= $this->common_func_for_remaining_p(80,'pr');  
            // if ($select_id == 1) {
            //    echo "<option>-select-</option><option value='85'>Manish(".$manish_remaing_pages.")</option><option value='81'>Amrendra(".$amrendra_remaing_pages.") </option><option value='82'>Gaurav(".$gourav_remaing_pages.")</option>";
            // }else{
            //   echo "<option>-select-</option><option value='90'>Ravindra(".$ravindra_remaing_pages.")</option><option value='80'>Varuna(".$varuna_remaing_pages.")</option>";
            // }
            if ($select_id == 1) {
               echo "<option>-select-</option><option value='85'>Manish</option><option value='81'>Amrendra </option><option value='82'>Gaurav</option>";
            }else{
              echo "<option>-select-</option><option value='90'>Ravindra</option><option value='80'>Varuna</option>";
            }
        }
      
          
        //assigned fromatediter and proffreader
        public function assigned_fe_pr($value='')
        {
         $id = $_POST['hidden_id'];
         $farmat_e_proof_r = $_POST['hidden_id_for_check'];
         $assigned_fe_pr = $_POST['format_assign_to'];
            if ($farmat_e_proof_r == 1) {
                //Format Editing
               $data = array(
                'project_assign_to' => $assigned_fe_pr,
                'project_assign_to_fe' => $assigned_fe_pr,
                'project_status' => 9,
                'dateassigned' => date('Y-m-d'),
            );
         
               //notification work
               $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$assigned_fe_pr))->row();
                $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                  'notify_to'=> $assigned_fe_pr,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 24,
                'message' => 'New Project Assigned',
                'discription' => ''.$proj_name.' New project Assign to the Format Editor ('.$staff_data->firstname.' '.$staff_data->lastname.') by PM '.$ret->firstname.' '.$ret->lastname,
                );
 
            }else if($farmat_e_proof_r==2) {
                //Proof Reading
               $data = array(
                'lead_pr_ms_rework' => 3,
                'project_assign_to' => $assigned_fe_pr,
                'project_status' => 5,
                'dateassigned' => date('Y-m-d'),
            );
               //notification work
               $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$assigned_fe_pr))->row();
                $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                    'notify_to'=> $assigned_fe_pr,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 25,
                'message' => 'New Project Assigned',
                'discription' => ''.$proj_name.' New project Assign to the Proof Reading ('.$staff_data->firstname.' '.$staff_data->lastname.') by PM '.$ret->firstname.' '.$ret->lastname,
                );
            }
            // echo $id;
            //  print_r($data);die;
            $this->db->where('id',$id);
            $update =  $this->db->update('tblleads',$data);
            if ($update) {
                $this->db->insert('lead_all_action',$data_noti);
             set_alert('success', _l('Lead uploaded successfully...'));
             redirect($_SERVER['HTTP_REFERER']);
            }
        }
        //cover assigned function
        public function cover_assigned_to($value='')
        {
            $id = $_POST['hidden_id'];
            $cover_assign_to = $_POST['cover_assign_to'];
            $data = array(
            	'project_status_gd' => 1,
                'lead_asf_assign_gd' => $cover_assign_to,
                'lead_asf_assign_gd_date' => date('Y-m-d'),
                
            );
            $this->db->where('id',$id);
            $update =  $this->db->update('tblleads',$data);
            if ($update) {
               //notification work
               $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$cover_assign_to))->row();
                $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                    'notify_to'=> $cover_assign_to,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 25,
                'message' => 'New Project Assigned',
                'discription' => ''.$proj_name.' New project Assign to cover ('.$staff_data->firstname.' '.$staff_data->lastname.') by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
                set_alert('success', _l('Cover Assigned successfully...'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        //mrp approved function
        public function mrp_approved($value='')
        {
         $id = $_POST['hidden_id'];
         $cover_assign_to = $_POST['mrp_approved_by_author'];
            if ($cover_assign_to > 0) {
               $data = array(
                'mrp_approved_by_author' => $cover_assign_to,
                );
               $this->db->where('id',$id);
               $update =  $this->db->update('tblleads',$data);
                if ($update) {
              

                 set_alert('success', _l('MRP Update successfully...'));
                 redirect($_SERVER['HTTP_REFERER']);
                }
            }else{
             set_alert('warning', _l('MRP should be positive number'));
             redirect($_SERVER['HTTP_REFERER']);
            }
        }
        // total number of pages function
        public function total_no_of_pages($value='')
        {
         $id = $_POST['hidden_id'];
         $total_no_of_pages = $_POST['total_no_of_pages'];
            if ($total_no_of_pages > 0) {
                 $data = array(
                    'lead_book_pages' => $total_no_of_pages,
                );
                 $this->db->where('id',$id);
                 $update =  $this->db->update('tblleads',$data);
                 if ($update) {
                     set_alert('success', _l('Update successfully...'));
                     redirect($_SERVER['HTTP_REFERER']);
                 }
            }else{
              set_alert('warning', _l('Total number of copy should be positive number'));
              redirect($_SERVER['HTTP_REFERER']);
            }
        }
        //total number of copyes function
        public function total_no_of_copy($value='')
        {
         $id = $_POST['hidden_id'];
         $total_no_of_copy = $_POST['total_no_of_copy'];
            if ($total_no_of_copy > 0) {
                 $data = array(
                    'total_number_of_copies' => $total_no_of_copy,
                );
                 $this->db->where('id',$id);
                 $update =  $this->db->update('tblleads',$data);
                 if ($update) {
                     set_alert('success', _l('Update successfully...'));
                     redirect($_SERVER['HTTP_REFERER']);
                 }
            }else{
              set_alert('warning', _l('Total number of copy should be positive number'));
              redirect($_SERVER['HTTP_REFERER']);
            }
        }
        //complimentry copies function
        public function complimentry_copies($value='')
        {
         $id = $_POST['hidden_id'];
         $complimentry_copies = $_POST['complimentry_copies'];
            if ($complimentry_copies > 0) {
                 $data = array(
                    'complimentry_copies' => $complimentry_copies,

                );
                 $this->db->where('id',$id);
                 $update =  $this->db->update('tblleads',$data);
                 if ($update) {
                    set_alert('success', _l('Update successfully...'));
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
            else{
              set_alert('warning', _l('Complimentry copies should be positive number'));
              redirect($_SERVER['HTTP_REFERER']);
            }
        }
        //content allowed function
        public function content_allowed($value='')
        {
             $id = $_POST['hidden_id'];
             $content_allowed = $_POST['content_allowed'];
             $data = array(
                'content_allowed' => $content_allowed,
            );
             $this->db->where('id',$id);
             $update =  $this->db->update('tblleads',$data);
            if ($update) {
                set_alert('success', _l('Update successfully...'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        //paper type function
        public function paper_type($value='')
        {
         $id = $_POST['hidden_id'];
         $paper_type = $_POST['paper_type'];
         $data = array( 'paper_type' => $paper_type, );
         $this->db->where('id',$id);
         $update =  $this->db->update('tblleads',$data);
            if ($update) {
                set_alert('success', _l('Update successfully...'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        // book size function
        public function book_size($value='')
        {
         $id = $_POST['hidden_id'];
         $book_size = $_POST['book_size'];
         $data = array(
            'lead_book_size' => $book_size, );
         $this->db->where('id',$id);
         $update =  $this->db->update('tblleads',$data);
         if ($update) {
             set_alert('success', _l('Update successfully...'));
             redirect($_SERVER['HTTP_REFERER']);
         }
        }
        // lamination function
        public function lamination($value='')
        {
         $id = $_POST['hidden_id'];
         $lamination = $_POST['lamination'];
         $data = array(
            'lamination' => $lamination, );
         $this->db->where('id',$id);
         $update =  $this->db->update('tblleads',$data);
         if ($update) {
             set_alert('success', _l('Update successfully...'));
             redirect($_SERVER['HTTP_REFERER']);
         }
        }
        //book interior function
        public function book_interior($value='')
        {
         $id = $_POST['hidden_id'];
         $book_interior = $_POST['book_interior'];
         $data = array(
            'book_interior' => $book_interior,  );
         $this->db->where('id',$id);
         $update =  $this->db->update('tblleads',$data);
            if ($update) {
                 set_alert('success', _l('Update successfully...'));
                 redirect($_SERVER['HTTP_REFERER']);
            }
        }
        //payment details function
        public function payment_details($value='')
        {
            $data['title'] = "Payment Details";
            $data['business'] = "";
            $data['projects'] = $this->leadsdata->get_payment_details_Projects();
            $this->load->view('admin/pc/payment_details_project', $data);
        }
        //upload first payment function
        public function upload_first_payment($value=''){
          $id = $_POST['hidden_id'];
          $filename = $_FILES['file']['name'];
          $ckeck_image = $this->db->get_where('tblleads',array('id'=>$id))->row();
          if ($filename) {
                $data_array = array(
                    'lead_first_payment_receipt'=>$filename,
                );
                $this->db->where('id',$id);
                $this->db->update('tblleads',$data_array);
                //notification work
                $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                  'notify_to'=> 49,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 26,
                'message' => 'Approve First Installment of'.$proj_name,
                'discription' => ''.$proj_name.' Approve First Installment Receipt Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
                if($ckeck_image->lead_first_payment_receipt){
                    unlink('assets/images/payment_receipt_first_final/'.$ckeck_image->lead_first_payment_receipt);
                }
                $location = "assets/images/payment_receipt_first_final/".$filename;
                $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
                $imageFileType = strtolower($imageFileType);

                $response = 0;
                if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                    $response = $location;
                    set_alert('success', _l('First Payment Receipt upload successfully...'));
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
        //upload final payment function
        public function upload_final_payment($value=''){
          

          $id = $_POST['id'];
          $filename = $_FILES['file']['name'];
          $ckeck_image = $this->db->get_where('tblleads',array('id'=>$id))->row();
          if ($filename) {
                $data_array = array(
                    'lead_final_payment_receipt'=>$filename,
                );
                $this->db->where('id',$id);
                $this->db->update('tblleads',$data_array);
                //notification work
                $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                   'notify_to'=> 49,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 27,
                'message' => 'Approve Final Installment of '.$proj_name,
                'discription' => ''.$proj_name.' Approve Final Installment Receipt Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
                if($ckeck_image->lead_final_payment_receipt){
                    unlink('assets/images/payment_receipt_first_final/'.$ckeck_image->lead_final_payment_receipt);
                }
                $location = "assets/images/payment_receipt_first_final/".$filename;
                $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
                $imageFileType = strtolower($imageFileType);

                $response = 0;

                if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                    $response = $location;
                    // set_alert('success', _l('Final Payment Receipt upload successfully...'));
                    // redirect($_SERVER['HTTP_REFERER']);
                  $data['success'] = "success";
                  set_alert('success', "Final Payment Receipt upload successfully...");
                  return $data;

                }
            }
        }
        //proof reader ms function
        public function proof_reader_ms($value='')
        {
            $id = $_POST['hidden_id'];
            $user_id = $_POST['hidden_id_pr'];
            $rework_or_completed = $_POST['rework_or_completed'];
            if ($rework_or_completed == 1) {
              $data = array(
                'lead_pr_ms_rework' => $rework_or_completed,
                'rework_pr_update_status'=>0,
                );
               //notification work
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$user_id))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                   'notify_to'=> $user_id,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Rework on'.$all_data->lead_author_name,
                'discription' => 'Rework on '.$all_data->lead_author_name,
                );
                 $this->db->insert('lead_all_action',$data_noti);
            }else{
                 $data = array(
                    'lead_pr_ms_rework' => $rework_or_completed,
                    'project_status' => 7,
                    'assign_id_proof_reader' => 1,
                    'project_assign_to_pr'=> $user_id

                );
                  //notification work
                 $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$user_id))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                  'notify_to'=> $user_id,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Project completed of Proof Reader Successfully',
                'discription' => ''.$proj_name.' Project completed of Proof Reader('.$staff_data->firstname.' '.$staff_data->lastname.') Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
            }

             $this->db->where('id',$id);
             $update =  $this->db->update('tblleads',$data);
             if ($update) {
                 set_alert('success', _l('Update successfully...'));
                 redirect($_SERVER['HTTP_REFERER']);
             }
        }
        //format editor ms function
        public function format_editor_ms($value='')
        {
            $id = $_POST['hidden_id'];
            $staff = $_POST['hidden_id_fe'];
            $rework_or_completed = $_POST['rework_or_completed'];
            if ($rework_or_completed == 1) {
                  $data = array(
                    'lead_fe_ms_rework' => $rework_or_completed,
                    'rework_fe_update_status'=>0
                );
                   //notification work
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$staff))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                  'notify_to'=> $staff,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Project Rework to Format Editor Successfully',
                'discription' => ''.$proj_name.' Project Rework to Format Editor('.$staff_data->firstname.' '.$staff_data->lastname.') Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
            }else{
                 $data = array(
                    'lead_fe_ms_rework' => $rework_or_completed,
                    'project_status' =>11,
                    'assign_id_format_editer' =>1,
                    'project_assign_to_fe'=> $staff

                );
                      //notification work
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$staff))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                  'notify_to'=> $staff,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Project completed of Format Editor Successfully',
                'discription' => ''.$proj_name.' Project completed of Format Editor('.$staff_data->firstname.' '.$staff_data->lastname.') Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
            }

         $this->db->where('id',$id);
         $update =  $this->db->update('tblleads',$data);
         if ($update) {
             set_alert('success', _l('Update successfully...'));
             redirect($_SERVER['HTTP_REFERER']);
         }
        }
         //proof reader ms function
        public function cover_assign_work($value='')
        {
        	 // print_r($_POST);rework_or_completed
            $id = $_POST['hidden_id'];
            $staff_cover_id = $_POST['staff_cover_id'];
            $rework_or_completed = $_POST['rework_or_completed'];
            // print_r($rework_or_completed); die;
// project_status_gd  = 5

            $additional_rework_or_completed = $_POST['additional_rework_or_completed'];
            if ($rework_or_completed < 5) {
            	  if ($rework_or_completed == 2 || $rework_or_completed == 4) {
              $data = array(
                'gd_work_status' => 2,
                'lead_gd_total_cover' =>1,
                'project_status_gd' => $rework_or_completed,
                );
                    //notification work
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$staff_cover_id))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                    'notify_to'=> $staff_cover_id,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Project rework to cover Successfully',
                'discription' => ''.$proj_name.' Project rework to cover('.$staff_data->firstname.' '.$staff_data->lastname.') Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
            }else if($rework_or_completed == 3){
                 $data = array(
                    'gd_work_status' => $rework_or_completed,
                    'project_status_gd' => 3,
                    'assign_id_graphic_d' => 1,

                );
                    //notification work
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$staff_cover_id))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                   'notify_to'=> $staff_cover_id,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Project completed of cover Successfully',
                'discription' => ''.$proj_name.' Project completed of cover('.$staff_data->firstname.' '.$staff_data->lastname.') Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
            }else {}
            
            $this->db->where('id',$id);
             $update =  $this->db->update('tblleads',$data);
             if ($update) {
                 set_alert('success', _l('Project status Changed to Rework...'));
                 redirect($_SERVER['HTTP_REFERER']);
             }

            }else if($additional_rework_or_completed) {

            	 if ($additional_rework_or_completed == 2 ) {
            	 	//additinal rework
              $data = array(
                'gd_ad_work_status' => $additional_rework_or_completed,
                
                'gd_ad_work_status' => 2,
                'lead_gd_total_cover' =>2,
                );
               //notification work
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$staff_cover_id))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                   'notify_to'=> $staff_cover_id,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Project additinal rework to cover Successfully',
                'discription' => ''.$proj_name.' Project additinal rework to cover('.$staff_data->firstname.' '.$staff_data->lastname.') Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
            }else{
            	//additinal completed
                 $data = array(
                    'gd_ad_work_status' => $additional_rework_or_completed,
                    'project_status_gd' => 3,
                    'assign_id_graphic_d' => 1,

                );
                   //notification work
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$staff_cover_id))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                   'notify_to'=> $staff_cover_id,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Project additinal completed of cover Successfully',
                'discription' => ''.$proj_name.' Project additinal completed of cover('.$staff_data->firstname.' '.$staff_data->lastname.') Upload Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
            }
              
            	  $this->db->where('id',$id);
             $update =  $this->db->update('tblleads',$data);
             if ($update) {
                 set_alert('success', _l('Status changed successfully...'));
                 redirect($_SERVER['HTTP_REFERER']);
             }
            }
          

            
        }
        public function mrp_form($value='')
        {
            $this->db->from('mrpfixation');
            $this->db->order_by("id", "desc");
            $query = $this->db->get();
            $data['mrpfixation'] = $query->row();
            // print_r($query->row());die;

            $this->load->view('admin/pc/mrp_form',$data);
        }
        public function mrpfixation($value=''){
            $data = array(
            'author_id'=>$this->input->post('author_id'),
            'author_id_name'=>$this->input->post('author_id_name'),
            'author_name'=>$this->input->post('author_name'),
            'book_title'=>$this->input->post('book_title'),
            'email'=>$this->input->post('email'),
            'mobile'=>$this->input->post('mobile'),
            'book_type'=>$this->input->post('book_type'),
            'production_cost'=>$this->input->post('production_cost'),
            'author_p_cost'=>$this->input->post('author_p_cost'),
            'rec_mrp'=>$this->input->post('rec_mrp'),
            'amazon'=>$this->input->post('amazon'),
            'bfc'=>$this->input->post('bfc'),
            'kdp'=>$this->input->post('kdp')
            );
            $data_insert =  $this->db->insert('mrpfixation',$data);
            if($data_insert){
                set_alert('success', _l('Data added successfully...'));
                // echo'<script>alert("data insert successfully");</script>' ;
                $data['mrpfixationList'] = $this->db->get('mrpfixation')->result();
                $this->load->view('admin/pc/lisMrpFixation',$data);
            }
        }
        public function listMrpFixation(){
            $data['mrpfixationList'] = $this->db->get('mrpfixation')->result();
            $this->load->view('admin/pc/lisMrpFixation',$data);
        }
        public function cover_status_for_additional($value='')
         {
        	    $id = $_POST['id'];
            $select_id = $_POST['select_id'];
              $data = array(
                'project_status_gd' => $select_id,
                'lead_gd_total_cover' => 2,
                'gd_ad_work_status' => 1,
                );
              $this->db->where('id',$id);
              $this->db->update('tblleads',$data);
            
        }
        public function mail_mrp_approved($value='')
        {
          $staff_id = $_SESSION['staff_user_id'];
            $all_staff = $this->db->get_where('tblstaff',array('staffid' => $staff_id))->row();

          // $leadId = end($this->uri->segment_array());
          $leadId =  $_POST['id'];
          // print_r($_POST);exit;



          $all_asf_data = $this->leadsdata->getleadsData($leadId);

           $this->load->library('pdf');
           $this->load->helper('url');
           $this->load->library('excel');
           $name = $all_asf_data->lead_author_name;
           $filename = str_replace(' ', '', $name)."_mrp_suggestion.pdf";
            $data['author_name']= $name;
            $data['book_size']= $_POST['book_size'];
            $data['book_type']= $_POST['book_type'];
            $data['cover_paper_type']= $_POST['cover_paper_type'];
            $data['paper_lamination']= $_POST['paper_lamination'];
            $data['interior']= $_POST['interior'];
            $data['total_pages']= $_POST['total_pages'];
            $data['production_cost']= $_POST['production_cost'];
            $data['author_subsidized']= $_POST['author_subsidized'];
            $data['minimum_mrp']= $_POST['minimum_mrp'];
            $data['mrp_indian_point']= $_POST['mrp_indian_point'];
            $data['bfc_pub']= $_POST['bfc_pub'];
            $data['amazon_flip']= $_POST['amazon_flip'];
            $data['ebook_mrp_for_amazon']= $_POST['ebook_mrp_for_amazon'];
            $data['ebook_mrp']= $_POST['ebook_mrp'];
            
           $html = $this->load->view('admin/pc/mrp_pdf', $data, true);
           $dompdf = new Dompdf\DOMPDF();
           $dompdf->load_html($html);
           $dompdf->render();
           $dompdf->set_paper('A4', 'landscape');
           $output = $dompdf->output();
           $filepath = 'assets/mrp_suggestion_pdf/'.$filename;
            file_put_contents($filepath, $output);
        //  $to = $all_asf_data->email;
        $to = "vineet.bfcinfoteh@gmail.com";
         $subject = 'MRP Suggestion';
         $message = "<p>Hi <b>".$name."</b>,</p>";
         // $message .= "<p>Thanks for short listing BFC as your publisher. It's great to finally have you on board."; 
         $message .= "<p>Please find listed below, the specifications and price recommendations for your upcoming book, based on currently prevalent industry norms and practices. Kindly acknowledge the receipt of this mail and let us know if the suggested prices are up to your satisfaction.</p>"; 
         $message .= "<p>Feel free to get in touch if you wish to push the selling price over and above the suggested price.</p>";
         $message .="<p>Happy Writing!</p>";
         	$message .="Thanks & Regards,<br>";
			$message .= $all_staff->firstname.' '.$all_staff->lastname.'<br>';
			if ($all_staff->firstname.''.$all_staff->lastname == 'Shivangi Yadav') {
			$message .="Sr. Project Manager<br>";
			$message .="BFC Publications Pvt. Ltd.<br>";
			$message .="(M) +91 95111 15760<br>";
			}else if ($all_staff->firstname.''.$all_staff->lastname == 'Ashish Verma') {
			$message .="Project Coordinator<br>";
			$message .="BFC Publications Pvt. Ltd.<br>";
			$message .="(M) +91 95060 30542<br>";	
			}
			$message .="CP - 61| Viraj Khand | Gomti Nagar | Lucknow - 226010<br>";
			
         $file = base_url('assets/mrp_suggestion_pdf/'.$filename); 
         $this->email->set_newline("\r\n");
         $this->email->from($all_staff->email, 'BFC Publications');
         $this->email->to($to);
        //  $this->email->cc('projectcoodinator.bfcpub@gmail.com');  
         $this->email->subject($subject);
         $this->email->message($message);
         $this->email->attach($file);
         $result = $this->email->send();
               
           if($result == "1"){
             //notification work
              // $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$staff_cover_id))->row();
               $all_data = $this->db->get_where('tblleads',array('id'=>$leadId))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'Project MRP Suggestion mail send Successfully',
                'discription' => ''.$proj_name.' Project MRP Suggestion mail send Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
              if ($data['book_type'] == '(paperback)') {
               $data = array(
              'lead_final_mrp_suggestion'=>$data['mrp_indian_point'],
              'lead_final_mrp_suggestion_ebook'=>$data['ebook_mrp']
             );
              }else{
                   $data = array(
              'lead_final_mrp_suggestion'=>$data['ebook_mrp']
             );
              }
              $this->db->where('id',$leadId);
              $this->db->update('tblleads',$data);
            

              set_alert('success', _l('Mail send Successfully'));
                 redirect($_SERVER['HTTP_REFERER']);
                   
            }else{
                echo "0";
                        
            }

        }
        public function print_quotation($value='')
        {
          $id = end($this->uri->segment_array());
          $data = array(
            'print_quotation_status'=>1
          );
          $this->db->where('id',$id);
        $updated = $this->db->update('tblleads',$data);
           if ($updated) {
            $all_data = $this->db->get_where('tblleads',array('id'=>$id))->row();
                $proj_name =  $all_data->lead_author_name.'_'.$all_data->lead_booktitle;
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                'notify_to'=> 55,
                'user_id'=> $id,
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => $proj_name,
                'author_name' => $all_data->lead_author_name,
                'book_name' => $all_data->lead_booktitle,
                'action' => 28,
                'message' => 'New printing quotation received',
                'discription' => 'You have received new printing quotation',
                );
                 $this->db->insert('lead_all_action',$data_noti);
                 set_alert('success', _l('printing quotation send successfully'));
                 redirect($_SERVER['HTTP_REFERER']);
             }
        }

        public function misc_addlist(){
            // $data['title'] = "MISC Project";
            // $data['business'] = "";
            $by = $this->session->userdata('staff_user_id');
            if (is_admin() || is_headtrm()) {
            $data['list']= $this->db->select('*')->get('misc_project')->result();
          }else{
              $data['list']= $this->db->select('*')->where('alloted_by',$by)->get('misc_project')->result();
          }
            $this->load->view('admin/pc/misc_project', $data); 
          
        }
        public function save_misc(){
            $data = array(
            		"alloted_by" => $_SESSION['staff_user_id'],
            		"alloted_to" => $_POST['alloted_to'],
            		"alloted_date" => date('Y-m-d'),
            		"description" => $_POST['Description'],
            		);
            $result= $this->db->insert('misc_project',$data);
            if ($result) {
               //notification work
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$_POST['alloted_to']))->row();
        
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                   'notify_to'=> $_POST['alloted_to'],
                'user_id'=> $_POST['alloted_to'],
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => '',
                'author_name' => '',
                'book_name' => '',
                'action' => 29,
                'message' => 'Project MISC assign Successfully',
                'discription' => 'MISC Assign to ('.$staff_data->firstname.' '.$staff_data->lastname.') Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);
    			set_alert('success', _l('Added succesfully'));
    		    redirect($_SERVER['HTTP_REFERER']);
    		}else{
    			set_alert('danger', _l('Something went wrong'));
    			redirect($_SERVER['HTTP_REFERER']);
    	        $this->load->view('admin/pm_lead/misc_project', $data);
    		}
        }
    public function delete_misc(){
         $Id = $_POST['Id']; 
       $this->db->where('id', $Id);
        $result = $this->db->delete('misc_project');
        set_alert('success', _l('Delete succesfully'));
		redirect($_SERVER['HTTP_REFERER']);
    }
    public function update_misc($value=''){
        $id = $_POST['miscId'];
        $data = array(
            "alloted_date" => date('Y-m-d'),
            "description" => $_POST['Description'],
            "alloted_to" => $_POST['alloted_to'],
            );
        
        $this->db->where('id',$id);
        $result= $this->db->update('misc_project',$data);
        if ($result) {
            set_alert('success', _l('update succesfully'));
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            set_alert('danger', _l('Something went wrong'));
            redirect($_SERVER['HTTP_REFERER']);
            $this->load->view('admin/pm_lead/misc_project', $data);
        }
    }
    public function marketing_assigned_to($value='')
        {
            $id = $_POST['hidden_id'];
            
                 $data = array(
                    'lead_asf_assign_marketing' => $_POST['market_assign'],
                    'dm_project_status' =>1,
                    // 'assign_id_format_editer' =>1,
                    // 'project_assign_to_fe'=> $staff

                );
              $staff_data = $this->db->get_where('tblstaff',array('staffid'=>$_POST['market_assign']))->row();
        
                $by = $this->session->userdata('staff_user_id');
                $this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
                ->from('tblstaff')
                ->join('tblroles', 'tblstaff.role = tblroles.roleid');
                $this->db->where('tblstaff.staffid',$by);
                $query = $this->db->get();
                $ret = $query->row();
                $data_noti = array(
                   'notify_to'=> $_POST['market_assign'],
                'user_id'=> $_POST['market_assign'],
                'take_by'=> $by,
                'role' => $ret->name,
                'project_name' => '',
                'author_name' => '',
                'book_name' => '',
                'action' => 29,
                'message' => 'New project assign successfully',
                'discription' => 'Marketing Assign to ('.$staff_data->firstname.' '.$staff_data->lastname.') Successfully by PM '.$ret->firstname.' '.$ret->lastname,
                );
                 $this->db->insert('lead_all_action',$data_noti);

         $this->db->where('id',$id);
         $update =  $this->db->update('tblleads',$data);
         if ($update) {
             set_alert('success', _l('Update successfully...'));
             redirect($_SERVER['HTTP_REFERER']);
         }
        }
        public function author_review($value='')
        {
          $this->db->where('author_db_fr_rework_completed !=', '');
          $this->db->or_where('author_db_pr_rework_completed !=','');
          $this->db->or_where('author_db_cover_rework_completed !=','');
          $this->db->or_where('author_db_description !=','');
        
          $query['auther_review']=$this->db->get('tblleads')->result();
          $this->load->view('admin/pc/auther_review', $query);
        }

        public function author_review1($value='')
        {
          /*$this->db->where('author_db_fr_rework_completed !=', '');
          $this->db->or_where('author_db_pr_rework_completed !=','');
          $this->db->or_where('author_db_cover_rework_completed !=','');
          $this->db->or_where('author_db_description !=','');
        
          $query['auther_review']=$this->db->get('tblleads')->result();
          $this->load->view('admin/pc/auther_review', $query);*/


          $by = $this->session->userdata('staff_user_id');
          $data['list']= $this->db->select('*')->where('alloted_by',$by)->get('misc_project')->result();
          //print_r($data);
          $this->load->view('admin/pc/misc_project', $data);
        }
        public function binding($value='')
        {
         $id = $_POST['hidden_id'];
         $binding = $_POST['binding'];
         $data = array(
            'book_cover_sc' => $binding, );
         $this->db->where('id',$id);
         $update =  $this->db->update('tblleads',$data);
         if ($update) {
             set_alert('success', _l('Update successfully...'));
             redirect($_SERVER['HTTP_REFERER']);
         }
        }
        public function apply_isbn($value='')
        {
         $id = $_POST['id'];
           $data = array(
            'lead_isbn_status' => 1, );
         $this->db->where('id',$id);
         $update =  $this->db->update('tblleads',$data);
         if ($update) {
             set_alert('success', _l('Update successfully...'));
         }
        }
}
