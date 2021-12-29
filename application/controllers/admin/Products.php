<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends Admin_controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model','pmodel');
        
    }
    
    public function index()
    {
        $data['schemes']=$this->pmodel->getAllSchemes();
        $data['title']                = _l('products');
        $this->load->view('admin/products/manage', $data);
    }


    public function deletesche(){
    
    if(isset($_POST['emp_id'])) {
        
                
                $ids = trim($_POST['emp_id']);
                 
                $emp_id = trim($_POST['emp_id']);
                
                
                 $delete = $this->pmodel->deletelist($ids);
                
                // If delete is successful
                if($delete){
                    set_alert('success', 'Selected Scheme have been deleted successfully');
                }else{
                    set_alert('success', 'Some problem occurred, please try again.');
                }
               
                echo $emp_id;
                }
    }

public function updateproducttype()
{
               $upd['name']=ucfirst($_POST['name']);
                $id=$_POST['id'];
                $upd['short_name'] = $_POST['short_name'];
                
                
                $success = $this->pmodel->updateproducttype('tblproduct_categories',$upd, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('categories')));
                }
}

public function categories()
    {
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->pmodel->add_categories($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('categories')));
                     redirect(admin_url('products/categories'));
                    
                }
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                echo $id;
                exit;
                unset($data['id']);
                $success = $this->pmodel->update_categories($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('categories')));
                }
            }
            die;
        }
        $data['categories'] = $this->pmodel->get_categories();
        $data['title']                = _l('categories');
        $this->load->view('admin/products/categories', $data);
    }

    public function add_catagory($id = '')
    {
        if (!is_staff_member() || ($id != '' && !$this->leads_model->staff_can_access_lead($id))) {
            $this->access_denied_ajax();
        }
        if(is_numeric($id)){
            $getcategory = $this->pmodel->getcategory($id);
        }
        if ($this->input->post()) {
            $id=$this->input->post('cat');
            $name=$this->input->post('name');
            $short_name=$this->input->post('short_name');
            $this->pmodel->addupadte_category(array('name'=>$name,'short_name'=>$short_name),$id);
        }

        echo json_encode([
            'success'=>true,
            'message'=>_l('added_successfully'),
            'getcategoryView' => $this->_get_product_catagory_data($id),
        ]);
    }
    public function _get_product_catagory_data($id = '')
    {
        $reminder_data       = '';
        $data['openEdit']    = $this->input->get('edit') ? true : false;
        return [
            'data'          => $this->load->view('admin/products/forms/add_catagory', $data, true),
            'reminder_data' => $reminder_data,
        ];
    }
    public function companies()
    {
        
        if ($this->input->post()) {
           
            
            if (!$this->input->post('id')) {
                $id = $this->pmodel->add_company($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('product_companies')));
                }
            } else {
               /* alert();
           exit;*/
                
                $upd['name']=$_POST['name'];
                $id=$_POST['id'];
                
                
                $success = $this->pmodel->updateproducttype('tblproduct_companies',$upd, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('companies')));
                }
                
                
            }
            die;
        }
        $data['companies'] = $this->pmodel->get_companies();
        $data['title']                = _l('product_companies');
        $this->load->view('admin/products/companies', $data);
    }

    public function schemes($id='')
    {
        
        if (is_numeric($id)) {
            $data['scheme']=$this->pmodel->getAllSchemes($id);
            //print_r($data['scheme']);
            $data['previous_rate']=$this->pmodel->change_rates($id);
            
            
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $data            = $this->input->post();
                
                
                $id              = $this->pmodel->add_scheme($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('scheme')));
                    redirect(admin_url('products'));
                }
            }else{
                $data2            = $this->input->post();
                
                $scheme_id = $this->input->post('id');
                $cat_id = $this->input->post('cat_id');
                $company_id = $this->input->post('company_id');
                $product_name = $this->input->post('product_name');
                
                $credit_rate_lum = $this->input->post('credit_rate_lum');
                $credit_rate_sip = $this->input->post('credit_rate_sip');
                
                $credit_rate = $this->input->post('credit');
                
                $credit_rate_fresh = $this->input->post('credit_fresh');
                $credit_rate_renewal = $this->input->post('credit_renewal');
                
                $gst = $this->input->post('gst');
                $tds = $this->input->post('tds');
                $score_changed = $this->input->post('score_changed');
                
                
                
                if($this->input->post('score_changedcheck') == "0"){
                     
                    switch($cat_id)
                    {
                        case '80':
                            $data = array('scheme_id' => $scheme_id, 'credit_rate_lum' => $credit_rate_lum, 'credit_rate_sip' => $credit_rate_sip, 'score_changed' => $score_changed);
                            
                            break;
                            
                        case '81':
                            $data = array('scheme_id' => $scheme_id, 'credit' => $credit_rate, 'score_changed' => $score_changed);
                            break;
                            
                        case '82':
                            $data = array('scheme_id' => $scheme_id, 'credit_fresh' => $credit_rate_fresh, 'credit_renewal' => $credit_rate_renewal, 'score_changed' => $score_changed); 
                            break;
                            
                        case '83':
                            $data = array('scheme_id' => $scheme_id, 'credit_fresh' => $credit_rate_fresh, 'credit_renewal' => $credit_rate_renewal, 'score_changed' => $score_changed); 
                            break;
                            
                        case '84':
                            $data = array('scheme_id' => $scheme_id, 'credit_fresh' => $credit_rate_fresh, 'credit_renewal' => $credit_rate_renewal, 'score_changed' => $score_changed);  
                            break;
                        
                        case '85':
                            $data = array('scheme_id' => $scheme_id, 'credit' => $credit_rate, 'score_changed' => $score_changed); 
                            break;
                            
                        case '86':
                           $data = array('scheme_id' => $scheme_id, 'credit_fresh' => $credit_rate_fresh, 'credit_renewal' => $credit_rate_renewal, 'score_changed' => $score_changed);  
                            break;
                            
                        case '87':
                            $data = array('scheme_id' => $scheme_id, 'credit' => $credit_rate, 'score_changed' => $score_changed);  
                            break;
                            
                        case '88':
                            $data = array('scheme_id' => $scheme_id, 'credit' => $credit_rate, 'score_changed' => $score_changed);  
                            break;
                            
                        case '89':
                            $data = array('scheme_id' => $scheme_id, 'credit_fresh' => $credit_rate_fresh, 'credit_renewal' => $credit_rate_renewal, 'score_changed' => $score_changed);  
                            break;
                    }
                    
                    
                    
                    $insertscorechange = $this->db->insert('tblcredit_rate', $data);
                    $id   = $data2['id'];
                    unset($data2['score_changedcheck']);
                    $success = $this->pmodel->update_scheme($data2, $id);
                    if ($success)
                    {
                       set_alert('success', _l('updated_successfully', _l('scheme')));
                        redirect($_SERVER['HTTP_REFERER']);
                        
                    }
                    
                }
                else 
                {
                    
                $id   = $data2['id'];
                $success = $this->pmodel->update_scheme($data2, $id);
                if ($success)
                {
                   set_alert('success', _l('updated_successfully', _l('scheme')));
                    redirect($_SERVER['HTTP_REFERER']);
                }
                }
                     
                    
                    
                    
                
                
            }
        }
        $data['categories']= $this->pmodel->getCustomRecords('tblproduct_categories',array('active'=>'1'),'id,name','result_array');
        $data['companies']= $this->pmodel->getCustomRecords('tblproduct_companies',array('active'=>'1'),'id,name','result_array');
        $data['scheme_types']= $this->pmodel->getCustomRecords('tblscheme_type',array('active'=>'1'),'id,name,short_name','result_array');
        $data['title']                = _l('product_schemes');
        $this->load->view('admin/products/schemes', $data);
    }
    public function scheme_types()
    {
        
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->pmodel->add_scheme_type($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('product_scheme_types')));
                }
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->pmodel->update_scheme_type($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('product_scheme_types')));
                }
            }
            die;
        }
        $data['scheme_types'] = $this->pmodel->get_scheme_types();
        $data['title']                = _l('product_schemes');
        $this->load->view('admin/products/scheme_types', $data);
    }
    
    public function deletescoreschnaged()
    {
        $changescoreid = $this->uri->segment(4);
        $this->pmodel->delete('tblcredit_rate',$changescoreid);
        set_alert('success', _l('Deleted Successfully', 'Changed Credit Score'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete($type,$id)
    {
        if (!$id) {
            redirect(admin_url('products/'.$type));
        }
        switch ($type) {
            case 'schemes':
                $this->pmodel->delete('tblproducts',$id);
                break;
            case 'scheme_types':
                $this->pmodel->delete('tblscheme_type',$id);
                break;
            case 'companies':
                $this->pmodel->delete('tblproduct_companies',$id);
                break;
            case 'categories':
                $this->pmodel->delete('tblproduct_categories',$id);
                break;
            default:
                set_alert('success', _l('invalid_request'));
                break;
        }
        set_alert('success', _l('Deleted Successfully', _l($type)));
        switch ($type) {
            case 'schemes':
                 redirect(admin_url('products/allcategory'));
                break;
                
            default:
               redirect(admin_url('products/'.$type));
                break;
        }
        
    }
    public function active($type,$id,$status)
    {
        if ($this->input->is_ajax_request()) {
            switch ($type) {
                case 'products':
                    $this->pmodel->change_status('tblproducts',$id,$status);
                    break;
                case 'scheme_types' :
                    $this->pmodel->change_status('tblscheme_type',$id,$status);
                    break;
                case 'companies' :
                    $this->pmodel->change_status('tblproduct_companies',$id,$status);
                    break;
                case 'categories' :
                    $this->pmodel->change_status('tblproduct_categories',$id,$status);
                    break;
                default:
                    # code...
                    break;
            }
        }
    }
    
    
    
    
  

    
   function import_categories()
    {
        $data['title']          = _l('import');
        $this->load->view('admin/products/import_categories',$data);
    }
    function uploadData()
    {
        $this->pmodel->uploadData();
        redirect('admin/products/import_categories');
    }
    
    function import_scheme_cat()
    {
        $data['title']          = _l('import');
        $this->load->view('admin/products/import_scheme_cat',$data);
    }
    function uploadschemecat()
    {
        $this->pmodel->uploadschemecat();
        redirect('admin/products/import_scheme_cat');
    }
    
    
     function import_schemes()
    {
        $data['categories']= $this->pmodel->getCustomRecords('tblproduct_categories',array('active'=>'1'),'id,name','result_array');
        $data['companies']= $this->pmodel->getCustomRecords('tblproduct_companies',array('active'=>'1'),'id,name','result_array');
        $data['scheme_types']= $this->pmodel->getCustomRecords('tblscheme_type',array('active'=>'1'),'id,name,short_name','result_array');
        $data['title']          = _l('import');
        $this->load->view('admin/products/import_schemes',$data);
    }
    
    
    function uploadschemes()
    {
        $this->pmodel->uploadschemes();
        redirect('admin/products/import_schemes');
    }
    
     public function deletelist(){
        $data = array();
        
        // If record delete request is submitted
        if($this->input->post('bulk_delete_submit')){
            // Get all selected IDs
            $ids = $this->input->post('checked_id');
            
             // If id array is not empty
            if(!empty($ids)){
                // Delete records from the database
                $delete = $this->pmodel->deletelist($ids);
                
                // If delete is successful
                if($delete){
                    set_alert('success', 'Selected Scheme have been deleted successfully');
                }else{
                    set_alert('success', 'Some problem occurred, please try again.');
                }
            }else{
                set_alert('success', 'Select at least 1 Scheme to delete');
            }
        }
        
        $this->load->view('products', $data);
    }
    
    public function allcategory()
    {
        $data['allcategory']=$this->pmodel->allcategory();
        $data['title']                = _l('All Products Categories');
        $this->load->view('admin/products/allcategory', $data);
    }
    
    public function view_cat_schemes() 
    {
        $data['schemes']=$this->pmodel->view_cat_schemes();
        $data['title']                = _l('products');
        $cattype = $data['schemes'][0]->cid;
        if($cattype == 80)
        {
        $this->load->view('admin/products/mutualfund', $data);
        }
        elseif($cattype == 81)
        {
        $this->load->view('admin/products/corporatefd', $data);
        }
        elseif($cattype == 82)
        {
        $this->load->view('admin/products/insurance', $data);
        }
        elseif($cattype == 83)
        {
        $this->load->view('admin/products/insurance', $data);
        }
        elseif($cattype == 84)
        {
        $this->load->view('admin/products/insurance', $data);
        }
        elseif($cattype == 85)
        {
        $this->load->view('admin/products/bonds', $data);
        }
        elseif($cattype == 86)
        {
        $this->load->view('admin/products/insurance', $data);
        }
        elseif($cattype == 87)
        {
        $this->load->view('admin/products/bonds', $data);
        }
        elseif($cattype == 88)
        {
        $this->load->view('admin/products/bonds', $data);
        }
        
        elseif($cattype == 89)
        {
        $this->load->view('admin/products/insurance', $data);
        }
    }
    
    
    public function test()
    {
        
    }
}



?>