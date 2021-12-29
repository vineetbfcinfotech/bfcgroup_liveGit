<?php init_head(); ?>
<div id="wrapper">
     
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="#" onclick="init_lead(); return false;"
                               class="btn mright5 btn-info pull-left display-block">
                               <?= _l('new_lead'); ?>
                            </a>
                           
                               <a  href="<?= admin_url('leads/import_lead'); ?>"
                                  class="btn btn-info pull-left display-block hidden-xs">
                                  <?= _l('import_leads'); ?>
                               </a>
                       
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading"/>
                           <?php
                              if ( !empty($allleads) ) {
                                 foreach ($allleads as $alllead) { ?>
                                     <ul style="width: 20%;margin-right: 6px;display: inline-block;text-align: center">
                                         <a href="<?= sprintf(base_url('admin/leads/view_custom_lead/%s/%s/%s'), str_replace(' ', '_',$alllead->name ), $alllead->lead_id_start, $alllead->lead_id_end); ?>"
                                            style="cursor:pointer;">
                                             <i class="fa fa-file-excel-o fa-5x"
                                                aria-hidden="true"></i><br/><?= $alllead->name ?>
                                             <br/><?= $alllead->uploaded_on ?><br/>
                                            <p> <?= $alllead->fullname ?></p>
                                              </a>
                                              <a class="pull-right btn btn-primary btn-xs" href="<?= sprintf(base_url('admin/leads/createXLS/%s/%s/%s/%s'),  $alllead->lead_id_start, $alllead->lead_id_end, $alllead->assigned_id,$alllead->name); ?>"></i> Export Data</a>
                                              <a href="<?= sprintf(base_url('admin/leads/delete_leads/%s/%s'), $alllead->lead_id_start, $alllead->lead_id_end); ?>" class="_delete text-danger" <?php if (sprintf('%s',$staff_role->role) == "40"  ) echo 'style="display:none !important"'; ?>> Delete</a>
                                        
                                     </ul>
                                    <?php
                                 }
                              } else {
                                 echo NOLEADFOUND;
                              }
                           ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
