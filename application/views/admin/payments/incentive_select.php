<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>Define Incentive structure</h3></center>
                        <form method='post' action='' id="incentivesform">
                            <div class="col-md-5 role">
                                <div class="form-group" app-field-wrapper="telermids[]">
                                    <label for="telermids" class="control-label">Select Designation</label>
                                    <div class="dropdown bootstrap-select" style="width: 100%;">
                                        <select id="telermids" name="telermids" class="selectpicker"
                                                data-width="100%" data-none-selected-text="Nothing selected"
                                                data-live-search="true" tabindex="-98">
                                            <option value=""></option>
                                            <option value="01">RM / WM</option>
                                            <option value="02">Team Leader</option>
                                        </select>
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
<?php init_tail(); ?>
<script>
    $(document).ready(function(){
   $('#telermids').change(function(){
        this.form.submit();
    });
});
</script>
</body>
</html>
