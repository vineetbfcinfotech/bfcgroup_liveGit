<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>Assign Task</h3></center>
                       
                        <table class="table dt-table scroll-responsive">
                        <thead>
                             <th><?php echo _l('id'); ?></th>
                          <th>Task</th>
                          <th>Frequency</th>
                          <th>Staff</th>
                          
                        </thead>
                        <tbody>
		                <?php foreach($tasks as $task) {?>
		                <tr>
		                    <td><?= @++$i; ?></td>
		                    <td><?= $task->name; ?> <input type="hidden" value="<?= $task->id; ?>" name="task" ></td>
		                    <td><?= $task->frequency; ?></td>
		                    <td style="width:30%"  class="assignedpers">
		                      
		                        <select  name="staff[]" value="" class="selectpicker task_list" data-task_id="<?= $task->id; ?>" data-width="100%" data-none-selected-text="All" multiple="1" data-actions-box="1" data-live-search="true" tabindex="-98">
						    
		                            
		                            <option>Select Staff</option>
		                            <?php $assigned = explode(',',$task->assigned); ?>
		                            <?php foreach($staffs as $staff){ 
		                            $stfId=$staff[staffid];
		                            ?>
		                            
		                            <option <?php
		                            
		                            
		                            
		                            
		                            
		                            if(in_array($stfId,$assigned)) { echo "selected"; } ?> value="<?= $staff[staffid]; ?>"><?= $staff[firstname]; ?> <?= $staff[lastname]; ?> </option>
		                            <?php }
		                            ?>
		                        </select>
		                    </td>
		                </tr>
		                <?php }?>
		                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $("select.task_list").change(function () {
        //const assigned = $(this).children("option:selected").val();
        const assigned = $(this).children("option:selected").map(function () {
        return $(this).val();
    }).get().join(',');
        task_id = $(this).data('task_id');
        url = "<?= base_url('admin/operations/task_assign_update') ?>";
        $.get(url, {
                assigned: assigned,
                task_id: task_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
    });
</script>
</body>
</html>
