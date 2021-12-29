<!--<?php print_r($oldrmlist); ?>
<?php echo render_select('departments',$departments,array('departmentid','name'),'staff_add_edit_departments',$teamdetails->departmentid); ?>
<?php echo render_select('team_id',$teams,array('id','team_name'),'Team Name',$teamdetails->team_id); ?>
<?php echo render_select('role_id',$oldroles,array('role_id','name'),'staff_add_edit_role',$teamdetails->role_id); ?>
<?php echo render_select('rm_id',$oldrmlist,array('id','full_name'),'Reportig Manager',$teamdetails->rm_id); ?>
<div class="form-group" app-field-wrapper="team_id"><label for="team_id" class="control-label">Team</label>
    <select id="team_id" name="team_id" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
        <option value=""></option>
    </select>  
</div>  
<div class="form-group" app-field-wrapper="role_id"><label for="role_id" class="control-label"><?= _l('staff_add_edit_role'); ?></label>
    <select id="role_id" name="role_id" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
        <option value=""></option>
    </select>  
</div>  
<div class="form-group" app-field-wrapper="rm_id"><label for="rm_id" class="control-label"><?= _l('Reportig Manager'); ?></label>
    <select id="rm_id" name="rm_id" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
        <option value=""></option>
    </select>  
</div>  -->