<?php
//header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class Asf_controller extends CI_controller{
	public function __construct(){
        parent::__construct();
    }
    public function index($value='',$leadid="",$tbltype=""){
    	$data['email'] = end($this->uri->segment_array());
    	$data['tbltype'] = $tbltype;
		$data['lead_id'] = $leadid;
    	$this->load->view('admin/pc/asf_form',$data);
    }
    public function save_data($value=''){
		// print_r($_FILES);
		// print_r($_POST);die;
		$this->load->library('phpmailer_lib');
        $mail = $this->phpmailer_lib->load();
		$tbltype = $_POST['tbltype'];
        $mail->IsSMTP();
        $config['upload_path']          = './assets/asf_authorMail/profilePic';
        $config['allowed_types']        = 'gif|jpg|png|webp';
        $config['max_size']             = 2024;

        $this->load->library('upload', $config);
       
    	$this->load->library('pdf');
    	$this->load->helper('url');
        $this->load->library('excel');
        
        $this->load->helper(array('form', 'url'));

        $name = $this->input->post('author_name');
        $filename = str_replace(' ', '', $name).".pdf";
		$data['author_name']= $_POST['author_name'];
		$data['father_name']= $_POST['father_name'];
		$data['dob']= $_POST['dob'];
		$data['contact_no']= $_POST['contact_no'];
		$data['alternate_no']= $_POST['alternate_no'];
		$data['email']= $this->input->post('hidden_email');
		$data['nominee_name']= $_POST['nominee_name'];
		$data['correspondence_address']= $_POST['correspondence_address'];
		$data['landmark']= $_POST['landmark'];
		$data['city']= $_POST['city'];
		$data['state']= $_POST['state'];
		$data['pincode']= $_POST['pincode'];
		$data['country']= $_POST['country'];
		$data['account_holder_name']= $_POST['account_holder_name'];
		$data['account_no']= $_POST['account_no'];
		$data['bank_name']= $_POST['bank_name'];
		$data['branch']= $_POST['branch'];
		$data['ifsc_code']= $_POST['ifsc_code'];
		$data['pan_no']= $_POST['pan_no'];
		$data['bookTitle']= $_POST['bookTitle'];
		$data['name_appear_on_book']= $_POST['name_appear_on_book'];
		$data['manuscript_language']= $_POST['manuscript_language'];
		$data['book_genre']= $_POST['book_genre'];
		$data['number_of_images']= $_POST['number_of_images'];
		$data['manuscript_status']= $_POST['manuscript_status'];
		$data['book_size']= $_POST['book_size'];
		$data['synopsis']= $_POST['synopsis'];
		$data['blurb']= $_POST['blurb'];
		$data['author_bio'] = $_POST['author_bio'];
		
		$html = $this->load->view('admin/pc/pdf', $data, true);
		$dompdf = new Dompdf\DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$dompdf->set_paper('A4', 'landscape');
		//$dompdf->stream($filename.'.pdf', array('Attachment' => 0)); 
		$output = $dompdf->output();
		$filepath = 'assets/asf_authorMail/'.$filename;
		
		file_put_contents($filepath, $output);

        if ($this->upload->do_upload('userfile')){
            $data = array('upload_data' => $this->upload->data('file_name'));
            $fileName = $data['upload_data'];
            $fileFullURL = base_url().'/assets/asf_authorMail/profilePic/'.$fileName;
        }else{
            $data = array('upload_data' => $this->upload->data());
        }
    	$data = array(
    		'asf_authorname'=> $_POST['author_name'],
    		'asf_fathername'=> $_POST['father_name'],
    		'asf_dob'=> $_POST['dob'],
    		'asf_contact'=> $_POST['contact_no'],
    		'asf_altercontact'=> $_POST['alternate_no'],
    		'asf_email'=> $this->input->post('hidden_email'),
    		'asf_nomineename'=> $_POST['nominee_name'],
    		'asf_address'=> $_POST['correspondence_address'],
    		'asf_landmark'=> $_POST['landmark'],
    		'asf_city'=> $_POST['city'],
    		'asf_state'=> $_POST['state'],
    		'asf_pincode'=> $_POST['pincode'],
    		'asf_country'=> $_POST['country'],
    		'asf_accountholdername'=> $_POST['account_holder_name'],
    		'asf_accountno'=> $_POST['account_no'],
    		'asf_bankname'=> $_POST['bank_name'],
    		'asf_branch'=> $_POST['branch'],
    		'asf_ifsc'=> $_POST['ifsc_code'],
    		'asf_panno'=> $_POST['pan_no'],
    		'asf_booktitle'=> $_POST['bookTitle'],
    		'asf_appearbookname'=> $_POST['name_appear_on_book'],
    		'asf_mslanguage'=> $_POST['manuscript_language'],
    		'asf_bookgenre'=> $_POST['book_genre'],
    		'asf_imagecount'=> $_POST['number_of_images'],
    		'asf_msstatus'=> $_POST['manuscript_status'],
    		'asf_booksize'=> $_POST['book_size'],
    		'asf_synopsis'=> $_POST['synopsis'],
    		'asf_blurb'=> $_POST['blurb'],
    		'asf_authorbio'=> $_POST['author_bio'],
    		'profile_pic'=>$fileName,
    		'profile_pic_url'=>$fileFullURL,
    		'lead_id'=> $this->input->post('hidden_id'),
    	);

    	$insert = $this->db->insert('chorus_asf',$data);
    	if ($insert) {
    		$data_array_register = array(
    			'user_email' => $this->input->post('hidden_email'),
    			'password' => '$2y$10$GfUeg5La1gFqzhacVeiQtuTSGnTFqCLcnpHayCqi6LAnLIFm3WU8C',
    			'status' => 1,
    			'role_id' => 2,
    			'name' => $_POST['author_name'],
    		);

    		// $this->db2 = $this->load->database('secend_db', TRUE); 
    		// $data_correct = $this->db2->get_where('users',array('user_email'=>$this->input->post('hidden_email')))->row();
    		// //$this->db3 = $this->load->database('dev_db', TRUE);
    		// //$data_correct1 = $this->db3->get_where('users',array('user_email'=>$this->input->post('hidden_email')))->row();
            // // print_r($data_correct);die;
    		// if (empty($data_correct) && empty($data_correct1)) {
    		// 	 $this->db2->insert('users',$data_array_register);
    		// 	 //$this->db3->insert('users',$data_array_register);
    			 
    		// }else{

    		// }
            $to = $this->input->post('hidden_email');
            $subject = 'Registration Successfully';
            
            $message = "<p>Congratulations <b>".$_POST['author_name']."</b>,</p>";
            $message .= "<p>You have successfully registered with BFC Publications as an Author. We’re thrilled to have you on board.</p>"; 
            $message .= "<p>Use the below-listed credentials to access the Author Dashboard, and keep tabs on your book’s sales proceeds.</p>"; 
            $message .= "<p>Login url:  <a href='https://authordashboard.bfcpublications.com/login'>https://authordashboard.bfcpublications.com/login</a></p>";
            $message .= "<p>Username:  <b>".$this->input->post('hidden_email')."</b></p>";
            $message .= "<p>Password:  <b>123456</b></p>";
            $message .= "<p>Looking forward to hear from you.</p>";
            $message .= "<p>Happy Writing!</p>";
            $this->email->set_newline("\r\n");
			$mail->Username   = 'enquiry@bfcpublications.com';
            $mail->Password   = 'bfc@2020';
            $mail->SetFrom('enquiry@bfcpublications.com', "BFC Publications");

            // $this->email->from('support@bfcpublications.com', 'BFC Publications');
            // $this->email->to($to);
            //  $this->email->cc('ashishkverma@bfcpublications.com'); 
            // $this->email->subject($subject);
            // $this->email->message($message);
            // $result = $this->email->send();

		$mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "ssl";
        $mail->Port       = 465;
        $mail->Host       = "smtp.rediffmailpro.com";
        
        $mail->IsHTML(true);
        $mail->AddAddress($to, '');
        //$mail->addcc('rajeshguptabfcinfotech@gmail.com', '');
        $mail->addcc('ashishkverma@bfcpublications.com', '');
        // $mail->addAttachment($file); 
           
        $mail->Subject = $subject;
        $mail->MsgHTML($message);

		$mail->Send();
    		$id = $this->input->post('hidden_id');
    		$data_array = array(
    			'lead_asf_status'=> 1,
                'lead_author_name'=> $_POST['author_name'],
                'country'=> $_POST['country'],
                'zip'=> $_POST['pincode'],
                'city'=> $_POST['city'],
                'state'=> $_POST['state'],
                'address'=> $_POST['correspondence_address'],
                'lead_booktitle'=> $_POST['bookTitle'],
                'phonenumber'=> $_POST['contact_no'],
                'otherphonenumber'=> $_POST['alternate_no'],
                'lead_author_msstatus'=> $_POST['manuscript_status'],
                'lead_author_mslanguage'=> $_POST['manuscript_language'],
                'lead_book_size'=> $_POST['book_size'],
    			'asf_pdf_data'=> $filename
    			
    		);
    		$this->db->where('id', $id);
			if($tbltype=='3'){
				$this->db->update('tblleads_create_package', $data_array);
			}else{
				$this->db->update('tblleads', $data_array);
			}
    		$this->load->helper('download');
			if($this->uri->segment(3)){
			    $data   = file_get_contents('./assets/asf_authorMail/'.$filename);
			}
			$name   = $filename;
            $fulldata = array('name'=>$name,'data'=>$data);	
            $this->load->view('admin/pc/thanku_page',$fulldata);
            // force_download($name, $data);
    	}
    }
    public function thankyou($value='')
    {
       $this->load->view('admin/pc/thanku_page');
    }
}