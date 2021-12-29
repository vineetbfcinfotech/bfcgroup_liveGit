<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
<div class="content">
<div class="row">
<div class="col-md-12">
<div class="panel_s">
<div class="panel-body">
    
    <center><h4>Submit Credit Adjustment</h4></center>
     <form action="<?php echo base_url(); ?>/admin/reports/bussiness_updates/" method="post" accept-charset="utf-8" autocomplete="no">
                    <div class="row">
                    
                    
                    <div class=" col-md-4">
                                  <small> (Transaction Date)</small>
                        <input type="text" name="transaction_date" id="transaction_date" class="form-control datepicker" value="" data-format="dd-mm-yyyy" placeholder="Select A Date.." autocomplete="no" autofocus="autofocus" required>
                    </div>
                            
                            
                   <div class=" col-md-4">
                                  <small> (Action)</small>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">Select Action</option>
                            <option value="add">Add Credit Score</option>
                            <option value="deduct">Deduct Credit Score</option>
                            
                        </select>
                    </div>
                    
                    <div class=" col-md-4">
                                  <small> (WP)</small>
                        <select name="working_person" id="working_person" class="form-control" required>
                            <option value="">Select WP</option>
                            <?php foreach($rmconverted as $wp) { ?>
                            <option value="<?= $wp->staffid; ?>"><?= $wp->staffname; ?></option>
                            <?php } ?>
                            
                        </select>
                    </div>
                    
                    <div class=" col-md-4">
                                  <small> (Amount)</small>
                        <input name="amount" id="amount" class="form-control" placeholder="Amount" autocomplete="no" autofocus="autofocus" required>
                    </div>
                    
                    <div class=" col-md-4">
                                  <small> (Remark)</small>
                        <textarea name="remark" id="remark" class="form-control" placeholder="remark" autocomplete="no" autofocus="autofocus" required> </textarea>
                    </div>
                    
                    </div>
                    <div class="row">
                    <div class="col-md-4 margin pull-right">
                          <small>  </small>
                                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                    </div>
   </div> 
   </form>
</div>
</div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script>

</script>
</body>
</html>