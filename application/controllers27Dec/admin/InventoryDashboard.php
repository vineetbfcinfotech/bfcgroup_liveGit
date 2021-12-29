<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed'); 
class InventoryDashboard extends Admin_controller{
    private $not_importable_leads_fields;
    public function __construct(){
        parent::__construct();
    }
    public function add(){
        $data['title'] = "Add New Inventory";
        $data['business'] = "";
        $this->db->select('*');
        $this->db->from('tblinventory');
        $this->db->order_by('total_books');
        $query = $this->db->get();
        $data['list']= $query->result();
        $this->load->view('admin/inventory/add', $data);
    }
    public function save_inventory(){
        $data = array(
            		"total_books" => $_POST['author_name'],
            		"book_title" => $_POST['book_title'],
            		"amazone" => $_POST['Amazon'],
            		"flipkart" => $_POST['FlipKart'],
            		"bfcstore" => $_POST['bfc_book_store'],
            		);
        $result= $this->db->insert('tblinventory',$data);
        if ($result) {
			set_alert('success', _l('Added succesfully'));
		    redirect($_SERVER['HTTP_REFERER']);
		}else{
			set_alert('danger', _l('Something went wrong'));
			redirect($_SERVER['HTTP_REFERER']);
	        $this->load->view('admin/inventory/add', $data);
		}
    }
    public function update_inventory($value=''){
        
       $id = $_POST['serviceId'];
              $data = array(
                    "total_books" => $_POST['author_name'],
                    "book_title" => $_POST['book_title'],
                    "amazone" => $_POST['Amazon'],
                    "flipkart" => $_POST['FlipKart'],
                    "bfcstore" => $_POST['bfc_book_store'],
                    );
        // 
              $this->db->where('id',$id);
              $result= $this->db->update('tblinventory',$data);
        if ($result) {
            set_alert('success', _l('update succesfully'));
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            set_alert('danger', _l('Something went wrong'));
            redirect($_SERVER['HTTP_REFERER']);
            $this->load->view('admin/inventory/add', $data);
        }
    }
    public function delete_inventory(){
        $Id = $_POST['Id']; 
       $this->db->where('id', $Id);
        $result = $this->db->delete('tblinventory');
        set_alert('success', _l('Delete succesfully'));
		redirect($_SERVER['HTTP_REFERER']);
    }
    public function inventoryOut(){
        $data['title'] = "List";
        $data['business'] = "";
        $data['list']= $this->db->select('*')->get('tblinventory')->result();
        $this->load->view('admin/inventory/inventoryout', $data);     
    }
    public function save_saleReport(){
        $id = $_POST['serviceId'];
        $all_data = $this->db->get_where('tblinventory',array('id'=>$id))->row();
        $cout=0;
        if ($_POST['platform'] == 'flipkart') {
           $all_remaning_data = $all_data->flipkart-$_POST['quantity'];
           if ($all_remaning_data >= 0) {
              $count++;
           }else{

           }

         }else if ($_POST['platform'] == 'amazone') {
            $all_remaning_data = $all_data->amazone-$_POST['quantity'];
           if ($all_remaning_data >= 0) {
              $count++;
           }else{

           }
          
         }elseif ($_POST['platform'] == 'bfcstore') {
            $all_remaning_data = $all_data->bfcstore-$_POST['quantity'];
           if ($all_remaning_data >= 0) {
              $count++;
           }else{

           }
          
         }else{}
        if($count>=1){
            $data = array(
                        "inventory_id" =>$_POST['serviceId'],
                        "platform" => $_POST['platform'],
                        "buyer_name" => $_POST['buyer_name'],
                        "order_id" => $_POST['order_id'],
                        "dispatch_date" => $_POST['dispatch_date'],
                        "quantity" => $_POST['quantity'],
                        );
            $result= $this->db->insert('tblSaleReport',$data);
            if ($result) {
                
                 if ($_POST['platform'] == 'flipkart') {
                   $total_book_q = $all_data->flipkart-$_POST['quantity'];
                   $total_data_q =  $all_data->total_books-$_POST['quantity']; 
                    $data = array(
                        "flipkart" => $total_book_q,
                        "total_books" => $total_data_q,
                        );
                 }else if ($_POST['platform'] == 'amazone') {
                   $total_book_q = $all_data->amazone-$_POST['quantity'];
                   $total_data_q =  $all_data->total_books-$_POST['quantity']; 
                   $data = array(
                        "amazone" => $total_book_q,
                        "total_books" => $total_data_q,
                        );
                 }elseif ($_POST['platform'] == 'bfcstore') {
                   $total_book_q = $all_data->bfcstore-$_POST['quantity'];
                   $total_data_q =  $all_data->total_books-$_POST['quantity']; 
                   $data = array(
                        "bfcstore" => $total_book_q,
                        "total_books" => $total_data_q,
                        );
                 }else{}
                  $this->db->where('id',$id);

                  //print_r($data);exit;
                  $result= $this->db->update('tblinventory',$data);
                set_alert('success', _l('added succesfully'));
                redirect($_SERVER['HTTP_REFERER']);
            }else{
                set_alert('danger', _l('Something went wrong'));
                redirect($_SERVER['HTTP_REFERER']);
                $this->load->view('admin/inventory/add', $data);
            }
        }else{
            set_alert('danger', _l('Book should be minimum  1'));
                redirect($_SERVER['HTTP_REFERER']);
        }
    }
    public function inventoryReturn(){
        $data['title'] = "Inventory Return";
        $data['business'] = "";
        $this->db->select('*');
        $this->db->from('tblSaleReturnReport');
        $this->db->order_by('updated_at');
        $query = $this->db->get();
        $data['list']= $query->result();
        $this->load->view('admin/inventory/inventoryReturn', $data);  
    }
    public function save_saleReturnReport(){
       $data = array(
            		"platform" => $_POST['platform'],
            		"order_id" => $_POST['order_id'],
            		"buyer_name" => $_POST['buyer_name'],
            		"book_title" => $_POST['book_title'],
            		"quantity" => $_POST['quantity'],
            		"return_date" => $_POST['return_date'],
            		);
        $result= $this->db->insert('tblSaleReturnReport',$data);
        
        if ($result) {
            $query = $this->db->select('*')->from('tblSaleReport')->where('buyer_name',$_POST['buyer_name'])->where('order_id',$_POST['order_id'])->get()->result();
            if(count($query)){
                $all_data = $this->db->get_where('tblinventory',array('book_title'=>$_POST['book_title']))->row();
                
                
                if($_POST['platform'] == 'amazone'){
                  $data = array(
                    "total_books" => $all_data->total_books+$_POST['quantity'],
                    "amazone" => $all_data->amazone+$_POST['quantity'],
                    );  
                }else if($_POST['platform'] == 'flipkart'){
                  $data = array(
                    "total_books" => $all_data->total_books+$_POST['quantity'],
                    "flipkart" =>  $all_data->flipkart+$_POST['quantity'],
                    );   
                }else if($_POST['platform'] == 'bfcstore'){
                  $data = array(
                    "total_books" => $all_data->total_books+$_POST['quantity'],
                    "bfcstore" =>  $all_data->bfcstore+$_POST['quantity'],
                    );   
                }else{
                    
                }
                 $this->db->where('book_title',$_POST['book_title']);
              $result= $this->db->update('tblinventory',$data);
                
                $this->db->where('id',$id);
                $result= $this->db->update('tblinventory',$data);
              
                set_alert('success', _l('Submit succesfully'));
    		    redirect($_SERVER['HTTP_REFERER']); 
            }else {
                set_alert('success', _l('Inputed Data invalid'));
		        redirect($_SERVER['HTTP_REFERER']);
            }
           
			
		}else{
			set_alert('danger', _l('Something went wrong'));
			redirect($_SERVER['HTTP_REFERER']);
	        $this->load->view('admin/inventory/inventoryReturn', $data);
		}
    }
}
      
     
       
