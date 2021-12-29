<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content" style="min-width: 1900px;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h4>Attendance Report</h4></center>
                        
                         <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
                        <div >
                            <?php if (!empty($monthatts) > 0) { ?>
                                <table class="table dt-table scroll-responsive">
                                    <thead>
                                     <tr>
                                    <td><strong> Employee \ Date</strong></td>
                             <?php foreach($list as $dates) { ?>
                                <td colspan="2"><strong><?php echo $dates; ?></strong></td>
                            <?php }?> 
                                    
                                </tr>
                                    
                                    </thead>
                                    <tbody class="ajax-data">
                                    <?php foreach ($staff_members as $staff_member) { ?>
                                       <tr>
                                           <td><?= $staff_member->name; ?></td>
                                            <?php foreach($list as $dates) { 
                                                $currentdate = date($dates);
                                            
                                            $this->db->select('date_in');
                                            $this->db->where('user_id', $staff_member->staffid);
                                            
                                            $this->db->where('DATE(date_in)',$dates);
                                            $res= $this->db->get('tblattendance')->row();
                                            ?>
                                <td colspan=""><strong><?php echo $res->date_in; ?></strong></td>
                            <?php }?> 
                                           
                                           
                                           
                                           
                                           </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <p class="no-margin"><?php echo "No Lead Find" ?></p>
                            <?php } ?>

                        </div>
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
