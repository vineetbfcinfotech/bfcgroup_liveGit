<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CRM_Model {
    public function __construct()
    {
        parent::__construct();
    }  
   public function addupadte_category($data,$cat_id='')
    {
        
        if ($cat_id) {
            $this->db->update('tblproduct_categories', $data,array('id',$cat_id));
        }else{
            $this->db->insert('tblproduct_categories', $data);
        }
    }
    
    
    
    public function updateproducttype($table,$result,$id)
    {
      $this->db->where('id',$id); 
      $up=$this->db->update($table,$result);
      return $up; 
    }

    public function add_scheme($data)
    {
        unset($data['score_changedcheck']);
        $this->db->insert('tblproducts', $data);
        $insert_id = $this->db->insert_id();
        $data['scheme_id'] = $insert_id;
         unset($data['id'], $data['cat_id'], $data['company_id'], $data['scheme_type_id'], $data['product_name'], $data['score_changedcheck'], $data['gst'], $data['tds']);
         /*Print_r($data);
         exit;*/
         
         $this->db->insert('tblcredit_rate', $data);
         
        if ($insert_id) {
            logActivity('New Scheme Added [ID: ' . $insert_id . ', Name: ' . $data['product_name'] . ']');
        }
        return $insert_id;
    }
    
    public function add_scheme_changed($data,$data2,$scid='')
    {
        
        
        $this->db->insert('tblcredit_rate', $data);
        /*print_r($this->db->last_query());
        exit;*/
        $insert_id = $this->db->insert_id();
        echo $insert_id;
        /*print_r($data);
        exit;*/
        unset($data2['score_changedcheck']);
        $this->db->where('id', $scid);
        $this->db->update('tblproducts', $data2);
        if ($insert_id) {
            logActivity('Update Scheme [ID: ' . $insert_id . ']');
        }
        return $insert_id;
    }
    public function update_scheme($data2,$id='')
    {
        $this->db->where('id', $id);
        $this->db->update('tblproducts', $data2);
        if ($this->db->affected_rows() > 0) {
            logActivity('Update Scheme [ID: ' . $id . ' Name: ' . $data2['product_name'] . ']');

            return true;
        }

        return false;
    }

 public function add_categories($data)
    {
        $this->db->insert('tblproduct_categories', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Product category Added [ID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
        }
        return $insert_id;
    }
    public function update_categories($data,$cat_id='')
    {
        $this->db->where('id', $id);
        $this->db->update('tblscheme_type', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Update Product Category [ID: ' . $id . ' Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
    public function add_scheme_type($data)
    {
        $this->db->insert('tblscheme_type', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Product Scheme Type Added [ID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
        }
        return $insert_id;
    }
    public function update_scheme_type($data,$cat_id='')
    {
        $this->db->where('id', $id);
        $this->db->update('tblscheme_type', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Update Product Scheme Type [ID: ' . $id . ' Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
    public function add_company($data)
    {
        $this->db->insert('tblproduct_companies', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Product Company Added [ID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
        }
        return $insert_id;
    }
    public function update_company($data,$cid='')
    {
        $this->db->where('id', $id);
        $this->db->update('tblproduct_companies', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Update Product Company [ID: ' . $id . ' Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
    public function getcategory($id)
    {
        return $this->db->get_where('tblproduct_categories',array('id'=>$id))->row();
    }

    public function get_categories($id='',$return="result")
    {
        if (!empty($id) && is_integer($id))  {
            $this->db->where('id', $id);
        }
        return $this->_checkRecords($this->db->get('tblproduct_categories'),$return);
    }
    public function get_companies($id='',$return="result")
    {
        if (!empty($id) && is_integer($id))  {
            $this->db->where('id', $id);
        }
        return $this->_checkRecords($this->db->get('tblproduct_companies'),$return);
    }
    public function get_scheme_types($id='',$return="result")
    {
        if (!empty($id) && is_integer($id))  {
            $this->db->where('id', $id);
        }
        return $this->_checkRecords($this->db->get('tblscheme_type'),$return);
    }

    public function _checkRecords($query,$return)
    {
        if ($query->num_rows()) {
            return $query->$return();
        }
    }

    public function getCustomRecords($tbl,$where,$select,$return='result',$limit=1000,$offset=0)
    {
        $this->db->where($where);
        $this->db->select($select);
        $query=$this->db->get($tbl, $limit, $offset);
        return $this->_checkRecords($query,$return);
    }

    public function getAllSchemes($id='')
    {
        $this->db->select('cat_id');
        $this->db->where('id',$id);
        $data_comp = $this->db->get('tblproducts')->result();
        $procate = $data_comp[0]->cat_id;
        
        
        switch($procate)
        {
            case "80":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.credit as credit,,p.tenure as tenure,p.gst as gst,p.tds as tds,p.product_name as pname,p.credit_rate_lum as crlum,credit_rate_sip as crsip,p.active as active,p.created,c.id as cid,c.name as cname,s.id as sid,s.name as sname,cp.id as cpid,cp.name as cpname');
                
                $this->db->join('tblscheme_type as s', 's.id = p.scheme_type_id');
                break;
                
            case "81":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.tenure as tenure,p.tds as tds,p.product_name as pname,p.credit as credit,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
            
                break;
                
            case "82":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
                break;
                
            case "83":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
                break;
                
            case "84":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
                break;
                
            case "85":
                 $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit as credit,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
                break;
                
            case "86":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
                break;
                
            case "87":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit as credit,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
                break;
                
            case "88":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit as credit,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
                break;
                
            case "89":
                $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
                break;
            
            default:
                $this->db->select('p.id as id,p.score_changed as score_changed,p.credit as credit,,p.tenure as tenure,p.gst as gst,p.tds as tds,p.product_name as pname,p.credit_rate_lum as crlum,credit_rate_sip as crsip,p.active as active,p.created,c.id as cid,c.name as cname,s.id as sid,s.name as sname,cp.id as cpid,cp.name as cpname');
                break;
        }
        
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r);     
        
    }
    
    public function change_rates($id='')
    {
        $this->db->select('p.*');
        $this->db->from('tblcredit_rate as p');
        
            $this->db->where('p.scheme_id', $id);
            //$r='row';
       
            $r= 'result';
        
        $query=$this->db->get();
        return $this->_checkRecords($query,$r);  
    }

    public function delete($table,$id)
    {
        $this->db->where('id', $id);
        $this->db->delete($table);
        return true;
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
    
   function uploadData()
    {
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }//keep this if condition if you want to remove the first row
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
        //        $insert_csv['id'] = $csv_line[0];//remove if you want to have primary key,
                $insert_csv['empName'] = $csv_line[1];
                $insert_csv['empAddress'] = $csv_line[2];

            }
            $i++;
            $data = array(
                
                'name' => $insert_csv['empName'],
                'short_name' => $insert_csv['empAddress']
               );
            $data['crane_features']=$this->db->insert('tblproduct_categories', $data);
        }
        fclose($fp) or die("can't close file");
        $data['success']="success";
         set_alert('success', _l('pro_type_import'));
        return $data;
    }
    
    function uploadschemecat()
    {
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }//keep this if condition if you want to remove the first row
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
        //        $insert_csv['id'] = $csv_line[0];//remove if you want to have primary key,
                $insert_csv['name'] = $csv_line[1];
                $insert_csv['sname'] = $csv_line[2];

            }
            $i++;
            $data = array(
                
                'name' => $insert_csv['name'],
                'short_name' => $insert_csv['sname']
               );
            $data['crane_features']=$this->db->insert('tblscheme_type', $data);
        }
        fclose($fp) or die("can't close file");
        $data['success']="success";
         set_alert('success', _l('scheme_cat_import'));
        return $data;
    }
    
       function uploadschemes()
    {
        $cat = $_POST['cat_id']; 
        $company = $_POST['company_id'];
        switch($cat)
        {
            case "80":
                
        
        $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['scheme_type_id'] = $csv_line[1];
                $insert_csv['credit_rate_lum'] = $csv_line[2];
                $insert_csv['credit_rate_sip'] = $csv_line[3];
                $insert_csv['gst'] = $csv_line[4];
                $insert_csv['tds'] = $csv_line[5];
                $insert_csv['effective'] = $csv_line[6];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                $scheme_type_name =  $insert_csv['scheme_type_id'];
                 }
            $i++;
            
     
            $ld2=$this->db->query("SELECT * FROM `tblscheme_type` WHERE `name`='$scheme_type_name'");
            //$ld->execute();
            $data2 = $ld2->result();
            $cow2=$ld2->num_rows();
            if($cow2>0)
            {
            $scheme_id = $data2[0]->id;
            }
            else 
            {
               $datascheme = array(
                
                'name' => $scheme_type_name
               );
            $datascheme['crane_features']=$this->db->insert('tblscheme_type', $datascheme);
            $scheme_id = $this->db->insert_id();
            }
            
            
            $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'scheme_type_id' => $scheme_id,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit_rate_lum' => $insert_csv['credit_rate_lum'],
                'credit_rate_sip' => $insert_csv['credit_rate_sip'],  
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array(
                'credit_rate_lum' => $insert_csv['credit_rate_lum'],
                'credit_rate_sip' => $insert_csv['credit_rate_sip'],
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
        break;
        
        case "81":
            
            
            $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['tenure'] = $csv_line[1];
                $insert_csv['credit'] = $csv_line[2];
                $insert_csv['gst'] = $csv_line[3];
                $insert_csv['tds'] = $csv_line[4];
                $insert_csv['effective'] = $csv_line[5];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'tenure' => $insert_csv['tenure'],
                'credit' => $insert_csv['credit'],  
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array(
                'credit' => $insert_csv['credit'], 
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
            
            case "82":
            
            
            $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['credit_fresh'] = $csv_line[1];
                $insert_csv['credit_renewal'] = $csv_line[2];
                $insert_csv['gst'] = $csv_line[3];
                $insert_csv['tds'] = $csv_line[4];
                $insert_csv['effective'] = $csv_line[5];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'],  
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array(
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'],
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
            
            case "83":
            
            
            $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['credit_fresh'] = $csv_line[1];
                $insert_csv['credit_renewal'] = $csv_line[2];
                $insert_csv['gst'] = $csv_line[3];
                $insert_csv['tds'] = $csv_line[4];
                $insert_csv['effective'] = $csv_line[5];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'],  
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array(
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'], 
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
            
            case "84":
            
            
            $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['credit_fresh'] = $csv_line[1];
                $insert_csv['credit_renewal'] = $csv_line[2];
                $insert_csv['gst'] = $csv_line[3];
                $insert_csv['tds'] = $csv_line[4];
                $insert_csv['effective'] = $csv_line[5];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'],  
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array(
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'], 
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
            
            case "85":
            
            
            $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['credit'] = $csv_line[1];
                $insert_csv['gst'] = $csv_line[2];
                $insert_csv['tds'] = $csv_line[3];
                $insert_csv['effective'] = $csv_line[4];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit' => $insert_csv['credit'],
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array( 
                'credit' => $insert_csv['credit'],
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
            
            case "86":
            
            
            $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['credit_fresh'] = $csv_line[1];
                $insert_csv['credit_renewal'] = $csv_line[2];
                $insert_csv['gst'] = $csv_line[3];
                $insert_csv['tds'] = $csv_line[4];
                $insert_csv['effective'] = $csv_line[5];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'],  
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array(
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'], 
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
            
            case "87":
            
            
            $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['credit'] = $csv_line[1];
                $insert_csv['gst'] = $csv_line[2];
                $insert_csv['tds'] = $csv_line[3];
                $insert_csv['effective'] = $csv_line[4];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit' => $insert_csv['credit'],
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array(
                'credit' => $insert_csv['credit'],
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
        
        case "88":
            
            
           $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['credit'] = $csv_line[1];
                $insert_csv['gst'] = $csv_line[2];
                $insert_csv['tds'] = $csv_line[3];
                $insert_csv['effective'] = $csv_line[4];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit' => $insert_csv['credit'],
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array( 
                'credit' => $insert_csv['credit'],
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
            
            case "89":
            
            
            $ld_comp=$this->db->query("SELECT * FROM `tblproduct_companies` WHERE `id`='$company'");
            //$ld->execute();
            $data_comp = $ld_comp->result();
            $comp = $data_comp[0]->name;
        
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1)
            {
                continue;
            }
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
                $insert_csv = array();
                $insert_csv['product_name'] = str_replace($comp,"",$csv_line[0]);
                $insert_csv['credit_fresh'] = $csv_line[1];
                $insert_csv['credit_renewal'] = $csv_line[2];
                $insert_csv['gst'] = $csv_line[3];
                $insert_csv['tds'] = $csv_line[4];
                $insert_csv['effective'] = $csv_line[5];
                
                $score_changed = strtotime($insert_csv['effective']);
                
                
                
                 }
                 
            $i++;
            
     $data = array(
                
                'cat_id' => $cat,
                'company_id' => $company,
                'product_name' => str_replace(" - ","",$insert_csv['product_name']),  
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'],  
                'gst' => $insert_csv['gst'],
                'tds' => $insert_csv['tds'],
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
               /*echo $score_changed;
               print_r($data);
               exit;*/
               
              
            $data['crane_features']=$this->db->insert('tblproducts', $data);
            
            $insert_schemeid = $this->db->insert_id();
            
            $datacr = array(
                'credit_fresh' => $insert_csv['credit_fresh'],
                'credit_renewal' => $insert_csv['credit_renewal'], 
                'scheme_id' => $insert_schemeid,
                'score_changed' =>  date('Y-m-d', $score_changed)
               );
            $datacr['crane_features']=$this->db->insert('tblcredit_rate', $datacr);
        }
        fclose($fp) or die("can't close file");
            break;
        
        
        }
       if( $data['success']="success") {
         set_alert('success', _l('scheme__import'));
       }
       else{
           set_alert('warning', _l('import_upload_failed'));
       }
        return $data;
    } 
    
    public function deletelist($ids){
        
        $this->db->where_in('id', $ids,FALSE,FALSE);
        $delete = $this->db->delete('tblproducts');
        print_r($this->db->last_query());
       // $delete = $this->db->delete($this->tblproducts);
        return $delete?true:false;
    }
    
    public function allcategory($return="result")
    {
        return $this->_checkRecords($this->db->get('tblproduct_categories'),$return);
    }
    
    public function get_allcategory($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblproduct_categories')->row();
        }

       //$statuses = $this->object_cache->get('dwr-all-statuses');

        if (!$statuses) {
           // $this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get('tblproduct_categories')->result_array();
           // $this->object_cache->add('dwr-all-statuses', $statuses);
        }

        return $statuses;

    }
    
    public function view_cat_schemes( $id = '') 
    {   
        $cat_id = $this->uri->segment(4);
        switch($cat_id)
        {
            case "80":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.tds as tds,p.product_name as pname,p.credit_rate_lum as crlum,credit_rate_sip as crsip,p.active as active,p.created,c.id as cid,c.name as cname,s.id as sid,s.name as sname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblscheme_type as s', 's.id = p.scheme_type_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break;
        
        case "81":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.tenure as tenure,p.tds as tds,p.product_name as pname,p.credit as credit,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break; 
        
        case "82":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break;
        
        case "83":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break; 
        
        case "84":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
		//echo $this->db->last_query();exit;
        return $this->_checkRecords($query,$r); 
        break; 
        
        case "85":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit as credit,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break; 
        
        case "86":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break;
        
        case "87":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit as credit,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break; 
        
        case "88":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit as credit,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break; 
        
         case "89":
        $this->db->select('p.id as id,p.score_changed as score_changed,p.gst as gst,p.credit_fresh as credit_fresh,p.credit_renewal as credit_renewal,p.tds as tds,p.product_name as pname,p.active as active,p.created,c.id as cid,c.name as cname,cp.id as cpid,cp.name as cpname');
        $this->db->where('cat_id', $cat_id);
        $this->db->from('tblproducts as p');
        $this->db->join('tblproduct_categories as c', 'c.id = p.cat_id');
        $this->db->join('tblproduct_companies as cp', 'cp.id = p.company_id');
        if (is_numeric($id)) {
            $this->db->where('p.id', $id);
            $r='row';
        }else{
            $r= 'result';
        } 
        $query=$this->db->get();
        return $this->_checkRecords($query,$r); 
        break;
        
        
        }
        
            
        
            
    }
}
?>