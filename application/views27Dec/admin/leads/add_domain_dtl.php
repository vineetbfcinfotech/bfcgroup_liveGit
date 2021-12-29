<?php init_head_new(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
          <div class="col-md-12">
              </div  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s" >
                    <div class="panel-body" style="overflow: auto;">
                            <div class="_buttons">
                                <div class="row">
                                    <div class="col-md-4">
                                      <h3>
                                            <button class="btn btn-primary"
                                                    onclick="window.location='<?= base_url(); ?>admin/';"> Back
                                            </button> 
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                   
                                </div>
                               

                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<?php if (is_admin()) { ?>
						<div class="clearfix"></div>
                      
						<?php } ?>
					
                        <div class="mytable">

                            
                                
                                  <form action="<?php echo base_url() ?>admin/leads/saveDomainDtl" id="remarkform" autocomplete="off">
                    <div class="col-md-12">
                        <div class="row">
                        
                       
                       
                      
                        <div class="col-md-3">
                           <?php //echo render_input('name', 'custom_lead_name', array('readonly' => 'readonly')); ?>
                           <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Author Name</label>
                           <input type="text" id="author_name" name="author_name" class="form-control" value="" autocomplete="no" <?php// if($name !=''){ echo "disabled";}?>></div>
                        </div>
                        
                        <div class="col-md-3">
                          <div class="form-group" app-field-wrapper="phonenumber">
                                 <label for="phonenumber" class="control-label">Domain URL</label>
                                 <input type="text" id="domarin_url" name="domarin_url" class="form-control" value="" autocomplete="no">
                            </div>
                        </div>
                         <div class="col-md-3" id="otherphonelabel">
                            <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <label for="otherphonenumber" class="control-label">Expairy Date</label>
                                 <input type="text" id="expairy_date" name="expairy_date" class="form-control" value="" autocomplete="no">
                            </div>
                        </div>
                        <div class="col-md-3" id="otherphonelabel">
                            <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <label for="otherphonenumber" class="control-label">Purchase Platform</label>
                                 <input type="text" id="purchase_platform" name="purchase_platform" class="form-control" value="" autocomplete="no">
                            </div>
                        </div>
                        <div class="col-md-3" id="otherphonelabel">
                            <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <label for="otherphonenumber" class="control-label">PM Name</label>
                                 <input type="text" id="purchase_platform" name="pm_name" class="form-control" value="" autocomplete="no">
                            </div>
                        </div>
                        
                        </div>
                        <div class="row">
                            <div class="col-md-3" id="otherphonelabel">
                            <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <button type="submit" name="submit" class="btn btn-info category-save-btn cline215">Save</button>
                            </div>
                        </div>
                            </div>
                        
                       
                     
                      
                        
                        
                       
                     
                    </div>
                   
                </form>
                           

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <?php init_tail_new();
    ?>

    
</div>
</div>



   




</body>
</html>