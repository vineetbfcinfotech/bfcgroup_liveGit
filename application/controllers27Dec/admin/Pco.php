<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class Pco extends Admin_controller{
    private $not_importable_leads_fields;
    public function __construct(){
        parent::__construct();
       
    }
    public function index($value='')
   {
       echo "string";
   }
    //Quotation list
    public function printing_quotation(){
        // print_r("test");exit;
        $data['title'] = "Pending Projects";
        $data['business'] = "";
          $this->db->from ( 'tblleads' );
		    // $this->db->join ( 'chorus_asf', 'chorus_asf.lead_id = tblleads.id');
		    $this->db->where ( 'tblleads.print_quotation_status', 1);
		    $this->db->where ( 'tblleads.send_print_quatation', 0);
		    $query = $this->db->get();
		    // echo $this->db->last_query();
		     $data['all_data'] = $query->result();
		    
        $this->load->view('admin/pco/printing_quotation', $data);
    }
    	/*Import and list Printing Order details*/
	public function printing_order(){
	    $data['title'] = "Add Printing Order";
	    $this->db->select('*');
        $data['result'] = $this->db->where("send",0)->get('bfc_order')->result();
	    //$data['subservice']=$this->db->select('id,service_name')->get('tblpackageservices')->result();
	    $this->load->view('admin/Project_Cordinator/import_list_printingOrder', $data); 
	}

	
	public function import_printing_order(){
        $total_imported = 0;
        $count = 0;
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
        $leadCount = 0;
        $c =0;
        $t=0;
        $currentDate = date("Y-m-d h:i:s");
        $csv_line = fgetcsv($fp, 10240);
        while ($csv_line = fgetcsv($fp, 10240)) {
            $count++;
            for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                $insert_csv = array();
                $insert_csv['0'] = htmlspecialchars($csv_line[0]);
                $insert_csv['1'] = htmlspecialchars($csv_line[1]);
                $insert_csv['2'] =  htmlspecialchars($csv_line[2]);
                $insert_csv['3'] =  htmlspecialchars($csv_line[3]);
                $insert_csv['4'] = htmlspecialchars($csv_line[4]);
                $insert_csv['5'] =  htmlspecialchars($csv_line[5]);
                $insert_csv['6'] =  htmlspecialchars($csv_line[6]);
                $insert_csv['7'] = htmlspecialchars($csv_line[7]);
                $insert_csv['8'] =  htmlspecialchars($csv_line[8]);
                $insert_csv['9'] = htmlspecialchars($csv_line[9]);
                $insert_csv['10'] = htmlspecialchars($csv_line[10]);
                $insert_csv['11'] = htmlspecialchars($csv_line[11]);
                $insert_csv['12'] = htmlspecialchars($csv_line[12]);
                $insert_csv['13'] = htmlspecialchars($csv_line[13]);
                $insert_csv['14'] = htmlspecialchars($csv_line[14]);
                $insert_csv['15'] = htmlspecialchars($csv_line[15]);
                $insert_csv['16'] = htmlspecialchars($csv_line[16]);
                $insert_csv['17'] = htmlspecialchars($csv_line[17]);
                $insert_csv['18'] = htmlspecialchars($csv_line[18]);
            }
            $total_imported++;
            $leadCount++;
            $str = $insert_csv['7'];
            $datavv = array(
                'lead_no' => $insert_csv['1'],
                'AuthorName' => $insert_csv['2'],
                'BookTitle' => $insert_csv['3'],
                'ContactNo' => $insert_csv['4'],
                'BookQty' => $insert_csv['5'],
                'Pages' => $insert_csv['6'],
                'Size' => $insert_csv['7'],
                'TextColor' =>$insert_csv['8'] ,
                'TextPaper' => $insert_csv['9'],
                'CoverPaper' => $insert_csv['10'],
                'Binding' => $insert_csv['11'],
                'Lamination' => $insert_csv['12'],
              
                'BookQtyDeliverToAuthor' => $insert_csv['13'],
                // 'BookQtyDeliverToAuthor' => $insert_csv['15'],
                'BookQtyDeliverToPublisher' => $insert_csv['14'],
                // 'BookQtyDeliverToPublisher' => $insert_csv['16'],
                'Publisher' => $insert_csv['15'],
                'AuthorAddress' => $insert_csv['16'],
                // 'total_mrp' => $insert_csv['18'],
                  'PerCopyRate' => $insert_csv['17'],
                'Total' => $insert_csv['18'],
                'CreatedAt'=>$currentDate,
            );
            // print_r($datavv);die;
        $this->db->insert('bfc_order', $datavv);
        $insert_id_last_lead = $this->db->insert_id();
        $data['success'] = "success";
        
        }
        set_alert('success', _l('import_total_imported', $total_imported));
        redirect('admin/Pco/printing_order');
	}
	
	// Send Mail on Printing Order page
	public function sendMail(){
	    $leadId = implode(',',$_REQUEST['data_id']);
		$exLeadId=explode(',',$leadId);
		$cnt=count($exLeadId);
			
              $this->load->library("excel");
              $object = new PHPExcel();

              $object->setActiveSheetIndex(0);

              $table_columns = array("Sr. No.","Author Name", "Book Title", "Contact No.","Pages","Size","Text color","Text paper","Cover paper","Binding","Lamination", "Book Qty", "Books Quantity Deliver to Author","Books Quantity Deliver to Author","Publisher","Publisher Address","Per copy rate including GST & delivery ","Total","Front Cover PDF","Back Cover PDF","Paperback PDF");

              $column = 0;

              foreach($table_columns as $field)
              {
               $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
               $column++;
              }
              $excel_row = 2;
              $i = 1;
		for($a=0;$a<$cnt;$a++){
            $lead_id=$exLeadId[$a];
            $this->db->from ( 'bfc_order' );
            $this->db->where ( 'bfc_order.id', $lead_id);
            $query = $this->db->get();
             $data_all = $query->result();
              foreach($data_all as $row)
              {
                // $this->db->select('lead_fe_ms_paperback','lead_gd_final_front','lead_gd_final_back');
                $this->db->from('tblleads');
                $this->db->where('id',$row->lead_no);
                $result = $this->db->get();
                $result_data = $result->row();

                if (!empty($result_data->lead_fe_ms_paperback)) {
                  $Paperback_pdf = base_url().'assets/menuscript/format_editor_ms/paperback/'.$result_data->lead_fe_ms_paperback;
                }else{
                   $Paperback_pdf = '';
                }
                 if (!empty($result_data->lead_gd_final_front)) {
                $front_pdf = base_url().'assets/cover/final_cover/front_cover/'.$result_data->lead_gd_final_front; 
                }else{
                   $front_pdf = '';
                }
                 if (!empty($result_data->lead_gd_final_back)) {
                 $back_pdf = base_url().'assets/cover/final_cover/back_cover/'.$result_data->lead_gd_final_back; 
                }else{
                   $back_pdf = '';
                }
                
               
               

               $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $i);
               $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->AuthorName);
               $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->BookTitle);
               $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->ContactNo);
               
               $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->Pages);
               $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->Size);
               $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->TextColor);
               $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->TextPaper);
               $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->CoverPaper);
               $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->Binding);
               $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->Lamination);
               $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->BookQty);
               $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->BookQtyDeliverToAuthor);
               $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->BookQtyDeliverToPublisher);
               $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->Publisher);
               $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row->AuthorAddress);
               $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $row->PerCopyRate);
               $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->Total);
               $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $front_pdf);
               $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $back_pdf);
               $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $Paperback_pdf);
               $excel_row++;
               $i++;
              }
            $data = array(
                    'Send' => 1,
            );
             $this->db->where('id', $lead_id);
             $this->db->update('bfc_order', $data);
            
		}
		 	   // $excel_row = 2;
       //        $i = 1;
       //        for($a=0;$a<$cnt;$a++){
       //        	 $lead_id=$exLeadId[$a];
              	
       //    }

              $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
              header('Content-Type: application/vnd.ms-excel');
              $excel_name = date('Y-m-d');
              $excel_full_name = $excel_name.'.xls';
              $name = 'assets/print_order/'.$excel_name.'.xls';
               $object_writer->save($name);  //for download the file inn php
                
               $file = base_url('assets/print_order/'.$excel_full_name); 

                $to = 'mayankmishrabfcinfotech@gmail.com';
		       $subject = 'BFC Publications Printing Order';
		       $message = "<p>Dear Akshay,</p>";
 			   $message = "<p>Approved, find the details and process for printing.</p>";
          $message .="Thanks & Regards,<br>";
          $message .="Project Coordinator<br>";
      $message .="BFC Publications Pvt. Ltd.<br>";
      $message .="(M) +91 95060 30542<br>"; 
      $message .="CP - 61| Viraj Khand | Gomti Nagar | Lucknow - 226010<br>";
		
		
		        $this->email->set_newline("\r\n");
		        //$this->email->from($from);
				$this->email->from('ashishkumarvermabfc@gmail.com', 'BFC Publications');
		        $this->email->to($to);
		        $this->email->cc('rajeshguptabfcinfotech@gmail.com');
		         $this->email->cc('ashishkumarvermabfc@gmail.com');
		        $this->email->subject($subject);
		        $this->email->message($message);
				$this->email->attach($file);
				$result = $this->email->send();

						$by = $this->session->userdata('staff_user_id');
						$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
				         ->from('tblstaff')
				         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
				          $this->db->where('tblstaff.staffid',$by);
						$query = $this->db->get();
						$ret = $query->row();
						     // echo $this->db->last_query();die;
		            	 $data1 = array(
					        'user_id'=> '',
					        'take_by'=> $by,
							'role' => $ret->name,
							'project_name' => '',
							'author_name' => '',
							'book_name' => '',
							'action' => 17,
							'message' => 'Mail Send successfully for Printing Quotation',
							'discription' => 'PCO '.$ret->firstname.' '.$ret->lastname.' in send Mail for Printing Quotation',
						);
				        $this->db->insert('lead_all_action',$data1);

		set_alert('success', _l('Send Mail', "successfully"));
        redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function sendprintQuotation(){
	 $leadId = implode(',',$_REQUEST['data_id']);
		$exLeadId=explode(',',$leadId);
		$cnt=count($exLeadId);
		for($a=0;$a<$cnt;$a++){
            $lead_id=$exLeadId[$a];
            $data = array(
                    'send_print_quatation' => 1,
            );
            $this->db->where('id', $lead_id);
            $this->db->update('tblleads', $data);
            
		}
         //$this->load->model("excel_export_model");
              $this->load->library("excel");
              $object = new PHPExcel();

              $object->setActiveSheetIndex(0);

              $table_columns = array("Sr. No.","Lead No","Author Name", "Book Title", "Contact No.", "Book Qty", "Pages","Size","Text color","Text paper","Cover paper","Binding","Lamination","Books Quantity Deliver to Author","Books Quantity Deliver to Publisher","Publisher","Author Address","Per copy rate including GST & delivery ","Total");

              $column = 0;

              foreach($table_columns as $field)
              {
               $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
               $column++;
              }

              // $employee_data = $this->excel_export_model->fetch_data();
               

              $excel_row = 2;
              $i = 1;
              for($a=0;$a<$cnt;$a++){
              	 $lead_id=$exLeadId[$a];
              	$this->db->from ( 'tblleads' );
            $this->db->where ( 'tblleads.print_quotation_status', 1);
            $this->db->where ( 'tblleads.id', $lead_id);
            $query = $this->db->get();
             $data_all = $query->result();
              foreach($data_all as $row)
              {
              	$asf =$this->db->get_where('chorus_asf',array('lead_id'=>$row->id))->row(); 
                if ($row->lead_book_size==1) {
                $book_size = '5*8';
                }
                else if($row->lead_book_size==2) {
                  $book_size = '6*9';
                } else if($row->lead_book_size==3) {
                   $book_size ='9*11';
                }
                if ($row->lamination==1) {
                  $lamination = 'Gloss';
                }
                else if($row->lamination==2) {
                  $lamination = 'Matte';
                }

                $text_color = 'b/w';
                $text_paper = 'white';
                $cover_paper = 'white';
                $binding = 'yes';
               $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $i);
               $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->id);
               $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->lead_author_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->lead_booktitle);
               $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->phonenumber);
               $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->total_number_of_copies);
               $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->lead_book_pages);
               $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $book_size);
               $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $text_color);
               $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $text_paper);
               $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $cover_paper);
               $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $binding);
               $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $lamination);
               $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->complimentry_copies);
               $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->total_number_of_copies - $row->complimentry_copies);
               $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, 'CP - 61| Viraj Khand | Gomti Nagar | Lucknow - 226010');
               $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $asf->asf_address);
               $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, '');
               $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, '');
               $excel_row++;
               $i++;
              }
          }

              $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
              header('Content-Type: application/vnd.ms-excel');
              $excel_name = date('Y-m-d');
              $excel_full_name = $excel_name.'.xls';
              $name = 'assets/print_quatation/'.$excel_name.'.xls';
               $object_writer->save($name);  //for download the file inn php
                
               $file = base_url('assets/print_quatation/'.$excel_full_name); 


               $to = 'mayankmishrabfcinfotech@gmail.com';
		       $subject = 'BFC Publications Print Quotation';
		       $message = "<p>Dear Akshay,</p>";
 			   $message .= "<p>Find the quotation and provide the rates for the same.</p>";
          $message .="Thanks & Regards,<br>";
          $message .="Project Coordinator<br>";
      $message .="BFC Publications Pvt. Ltd.<br>";
      $message .="(M) +91 95060 30542<br>"; 
      $message .="CP - 61| Viraj Khand | Gomti Nagar | Lucknow - 226010<br>";
		
		
		        $this->email->set_newline("\r\n");
		        //$this->email->from($from);
				$this->email->from('ashishkumarvermabfc@gmail.com', 'BFC Publications');
		        $this->email->to($to);
		        $this->email->cc('rajeshguptabfcinfotech@gmail.com');
		        $this->email->cc('ashishkumarvermabfc@gmail.com');
		        $this->email->subject($subject);
		        $this->email->message($message);
				$this->email->attach($file);
				$result = $this->email->send();

if ($result) {
						$by = $this->session->userdata('staff_user_id');
						$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
				         ->from('tblstaff')
				         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
				          $this->db->where('tblstaff.staffid',$by);
						$query = $this->db->get();
						$ret = $query->row();
						     // echo $this->db->last_query();die;
		            	 $data1 = array(
					        'user_id'=> '',
					        'take_by'=> $by,
							'role' => $ret->name,
							'project_name' => '',
							'author_name' => '',
							'book_name' => '',
							'action' => 17,
							'message' => 'Mail Send successfully for Printing Order',
							'discription' => 'PCO '.$ret->firstname.' '.$ret->lastname.' in send Mail for Printing Order',
						);
				        $this->db->insert('lead_all_action',$data1);
	
          set_alert('success', _l('Mail Send')); 
                        redirect($_SERVER['HTTP_REFERER']); 
}else{
	set_alert('danger', _l('Mail not send', "not send"));
        redirect($_SERVER['HTTP_REFERER']); 
}


             
		  
	}
	
	/*Showing MIS Printing Order which had been send from Printing Order page*/
	public function mis_printing_order(){
	    $data['title'] = "List MIS Printing Order";
	    $data['result'] = $this->db->where("send",1)->get('bfc_order')->result();
	    $this->load->view('admin/Project_Cordinator/mis_printing_order', $data); 
	}
   
   
    
}
      
     
       
