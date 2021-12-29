<?php init_head(); ?>
<style>
    table {border-collapse: collapse;border-spacing: 0;width: 100%;border: 1px solid #ddd;}
    th, td {text-align: left;padding: 8px;}
    tr:nth-child(even){background-color: #f2f2f2}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Assigned PC's</h3>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Pc Name</th>
                                            <th>Assigned</th>
                                            <th >Assigned To</th>
                                            
                                        </tr>
                                        <?php $i=1; foreach($data as $getdata){ ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                           
                                            <td><?= $getdata->firstname?> <?= $getdata->lastname?></td>
                                            <?php $get_pm = $this->db->get_where('tblstaff',array('staffid'=>$getdata->pm_assign_to))->row(); ?>
                                            <td><?= $get_pm->firstname?> <?= $get_pm->lastname?></td>

                                            <td>
                                                <form method="post" action="<?php echo base_url();?>admin/ProjectCordinatorDashboard/assign_pc_to_pm" >
                                              <input type="hidden" name="hidden_id" value="<?= $getdata->staffid; ?>">
                                                <select class="form-control" name="pm_assign" required>
                                                    <option>--select--</option>
                                                    <?php foreach ($pm_data as $key => $value) { ?>
                                                       <option <?php if ($getdata->pm_assign_to == $value->staffid) {
                                                          echo "selected";
                                                       } ?> value="<?= $value->staffid?>"><?= $value->firstname?> <?= $value->lastname?></option>
                                                    <?php } ?>
                                                </select>  
                                                <button type="Submit" class="btn btn-primary" >Assign</button>
                                                </form>                                                  
                                            </td>
                                       
                                            
                                            
                                        </tr>
                                        <?php $i++;}?>
                                </table>
                                </div> 
                            </div>
                        </div>
                        <div id="loading-image" style="display: none; text-align: center;">
					        <img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
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