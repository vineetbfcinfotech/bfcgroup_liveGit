<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-6 left-column">
            <div class="panel_s">
               <div class="panel-body">
                   
                   <button class="btn btn-alert"  onclick="window.location='<?php echo base_url(); ?>admin/products/allcategory';"> Back</button>  
                  <?= form_open($this->uri->uri_string(),array('id'=>'schema_form')); ?> 
                  <?php if (!empty($scheme) && is_object($scheme)) {
                      echo form_hidden('id', $scheme->id);
                  } ?>
                  <?= render_select('cat_id',$categories,array('id',array('name')),'product_cat_id_add_edit_assigned',@$scheme->cid); ?>
                  <?= render_select('company_id',$companies,array('id',array('name')),'product_company_id_add_edit_assigned',@$scheme->cpid); ?>
                  <?if($scheme->cid != 80) {}
                  else {
                  echo render_select('scheme_type_id',$scheme_types,array('id',array('name')),'product_scheme_type_id_add_edit_assigned',@$scheme->sid); } ?>
                  <?= render_input('product_name','product_add_edit_name',@$scheme->pname,'text',array('required'=>'true')); ?>
                 <?php 
                 switch($scheme->cid)
                 {
                     case '80':
                         echo  render_input('credit_rate_lum','product_add_edit_credit_rate_lum',@$scheme->crlum,'number',array('required'=>'true')); 
                         echo
                  render_input('credit_rate_sip','product_add_edit_credit_rate_sip',@$scheme->crsip,'number',array('required'=>'true')); 
                         break;
                         
                    case '81':
                        echo render_input('credit','product_dt_credit',@str_replace("%","",$scheme->credit),'number',array('required'=>'true')); ;
                        break;
                        
                    case '82':
                         echo  render_input('credit_fresh','product_dt_credit_fresh',@str_replace("%","",$scheme->credit_fresh),'number',array('required'=>'true')); 
                         echo
                          render_input('credit_renewal','product_dt_credit_renewal',@str_replace("%","",$scheme->credit_renewal),'number',array('required'=>'true')); ; 
                         break;
                         
                    case '83':
                         echo  render_input('credit_fresh','product_dt_credit_fresh',@str_replace("%","",$scheme->credit_fresh),'number',array('required'=>'true')); 
                         echo
                          render_input('credit_renewal','product_dt_credit_renewal',@str_replace("%","",$scheme->credit_renewal),'number',array('required'=>'true')); ;  
                         break;
                         
                    case '84':
                         echo  render_input('credit_fresh','product_dt_credit_fresh',@str_replace("%","",$scheme->credit_fresh),'number',array('required'=>'true')); 
                         echo
                          render_input('credit_renewal','product_dt_credit_renewal',@str_replace("%","",$scheme->credit_renewal),'number',array('required'=>'true')); ; 
                         break;
                         
                    case '85':
                        echo render_input('credit','product_dt_credit',@str_replace("%","",$scheme->credit),'number',array('required'=>'true')); ;
                        break;
                        
                    case '86':
                         echo  render_input('credit_fresh','product_dt_credit_fresh',@str_replace("%","",$scheme->credit_fresh),'number',array('required'=>'true')); 
                         echo
                          render_input('credit_renewal','product_dt_credit_renewal',@str_replace("%","",$scheme->credit_renewal),'number',array('required'=>'true')); ; 
                         break;
                         
                    case '87':
                        echo render_input('credit','product_dt_credit',@str_replace("%","",$scheme->credit),'number',array('required'=>'true')); ;
                        break;
                        
                    case '88':
                        echo render_input('credit','product_dt_credit',@str_replace("%","",$scheme->credit),'number',array('required'=>'true')); ;
                        break;
                        
                    case '89':
                         echo  render_input('credit_fresh','product_dt_credit_fresh',@str_replace("%","",$scheme->credit_fresh),'number',array('required'=>'true')); 
                         echo
                          render_input('credit_renewal','product_dt_credit_renewal',@str_replace("%","",$scheme->credit_renewal),'number',array('required'=>'true')); ; 
                         break;
                 }
                 
                 
                 ?>
                  
                  
                  
                  <?= render_input('gst','p_gst',@$scheme->gst,'number',array('required'=>'true')); ?>
                  <?= render_input('tds','p_tds',@$scheme->tds,'number',array('required'=>'true')); ?>
                   <label for="score_changed" class="control-label">Effective From</label>
                  <div class="input-group">
                           
                                    <input type="text" name="score_changed" class="form-control datepicker" data-date-min-date="" value=" <?= @$scheme->score_changed ?>" placeholder="Select A Date..">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                  <div class="checkbox"><input type="checkbox" data-shortname="score_changed" data-can-create="" name="score_changedcheck" value="0">
                                                    <label>Is Score Changed ?</label>
                                                </div>
                  <div class="btn-bottom-toolbar text-right">
                     <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>
         </div>
         <div class="col-md-6 right-column">
            <div class="panel_s">
               <div class="panel-body">
                   <table>
                       <?php
                       $this->db->select('cat_id');
                       $this->db->where('id', $previous_rate[0]->scheme_id);
                       $cat = $this->db->get('tblproducts')->row();
                       $catid = $cat->cat_id;
                       switch($catid)
                       {
                           case '80':
                           echo '<tr><td class="col-md-4" ><b>Credit Rate-LUMPSUM </b></td><td class="col-md-4"><b>Credit Rate-SIP</b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                           break;
                           
                           case '81':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate </b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                               break;
                               
                            case '82':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate-Fresh </b></td><td class="col-md-4"><b>Credit Rate-Renewal</b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                                break;
                                
                            case '83':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate-Fresh </b></td><td class="col-md-4"><b>Credit Rate-Renewal</b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                                break;
                                
                            case '84':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate-Fresh </b></td><td class="col-md-4"><b>Credit Rate-Renewal</b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                                break;
                                
                                
                            case '85':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate </b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                               break;
                               
                               
                            case '86':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate-Fresh </b></td><td class="col-md-4"><b>Credit Rate-Renewal</b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                                break;
                                
                            
                             case '87':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate </b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                               break;
                               
                            case '88':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate </b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                               break;
                               
                            case '89':
                               echo '<tr><td class="col-md-4" ><b>Credit Rate-Fresh </b></td><td class="col-md-4"><b>Credit Rate-Renewal</b></td><td class="col-md-4"><b>Effective From</b></td><td>Action</td></tr>';
                                break;
                       }
                       
                       
                       
                       ?>
                       
                       
                       <?php if(!empty($previous_rate)) foreach ($previous_rate as $pc_rate){ 
                       
                       switch($catid)
                       {
                           case '80':
                           echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit_rate_lum",2).'</td>
                           <td class="col-md-4">'.$pc_rate->credit_rate_sip .'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                           break;
                           
                           case '81':
                                echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit",2).'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                               break;
                               
                            case '82':
                               echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit_fresh",2).'</td>
                           <td class="col-md-4">'.$pc_rate->credit_renewal .'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                                break;
                                
                            case '83':
                              echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit_fresh",2).'</td>
                           <td class="col-md-4">'.$pc_rate->credit_renewal .'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                                break;
                                
                            case '84':
                               echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit_fresh",2).'</td>
                           <td class="col-md-4">'.$pc_rate->credit_renewal .'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                                break;
                                
                                
                            case '85':
                               echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit",2).'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                               break;
                               
                               
                            case '86':
                               echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit_fresh",2).'</td>
                           <td class="col-md-4">'.$pc_rate->credit_renewal .'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                                break;
                                
                            
                             case '87':
                               echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit",2).'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                               break;
                               
                            case '88':
                               echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit",2).'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                               break;
                               
                            case '89':
                               echo '<tr>
                           <td class="col-md-4">'.number_format("$pc_rate->credit_fresh",2).'</td>
                           <td class="col-md-4">'.$pc_rate->credit_renewal .'</td>
                           <td class="col-md-4">'. $date = date_create($pc_rate->score_changed); echo date_format($date, ' jS F Y') .'</td>
                           <td class="col-md-4"><a href="'.admin_url().'/products/deletescoreschnaged/'.$pc_rate->id .'" >Delete</a></td>
                       </tr>';
                                break;
                       }
                       
                       }
                       ?>
                       
                    
                   </table>
                   
                  
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<script>
    $('#schema_form').validate();
    
 </script>
</body>
</html>
