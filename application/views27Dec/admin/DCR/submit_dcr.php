<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?
                        $loginid = $this->session->userdata('staff_user_id');
                        $this->db->where('staffid', $loginid);

                        $this->db->select('CONCAT(tbll.firstname, " ", tbll.lastname) AS fullname,tbll.staffid ');
                        $data = $this->db->get('tblstaff as tbll')->result();
                        //print_r($data);
                        ?>
                       
                        <center>
                            <h3>
                                Daily Work Report
                            </h3>
                        </center>
                        <form role="form" enctype="multipart/form-data"
                              action="<?php echo base_url() ?>admin/DCR/apply_dcr" method="post"
                              class="form-horizontal form-groups-bordered">

                            <div class="row">
                                <div class="col-md-3"><h5>PC Name: <?= $data[0]->fullname; ?></h5>
                                   
                                    <input id="allowed_date" TYPE="hidden" name="date" value="<?= date('Y-m-d H:i:s') ?>" />
                                
                                    <h5>Date: <input type="text" name="apply_date" id="date_work"
                                                     class="form-control datepicker" value="<?= date('Y-m-d');?>" data-format="dd-mm-yyyy"
                                                     data-parsley-id="17" placeholder="Select Date.."
                                                     autocomplete="off" required></h5></div>
                            </div>
                            <input type="hidden" name="staff_id" value="<?= $data[0]->staffid; ?>"/>

                            <div class="row">
                            <div class="col-md-3">
                                <small>Working Duration</small>
                                <input class="form-control" id="ass_duration" name="work_duration" placeholder="Working Duration" required/>
                                </div>
                                <div class="col-md-3">
                                <small>Task Description</small>
                                <input class="form-control" id="ass_desc" name="description" placeholder="Task Description" required/>
                                </div>
                                <div class="col-md-6">
                                <small>Remark/Detailed</small>
                                <textarea class="form-control" rows="4" cols="50" name="remark" id="remark" required> </textarea>
                                </div>
                            </div>
                           <button type="submit" class="btn btn-primary">Submit</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php init_tail(); ?>

<script>


    $(function () {
        var d = new Date();
        //previousdate = d.setDate(d.getDate() - 1);
        //previousdate =   $('#allowed_date').val();
		previousdate =   '2021-07-01';

        //alert(previousdate);
        $('#date_work').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    minDate: previousdate,
                    maxDate: new Date()
                })
            },
            timepicker: false
        });
    });
</script>
</body>
</html>
