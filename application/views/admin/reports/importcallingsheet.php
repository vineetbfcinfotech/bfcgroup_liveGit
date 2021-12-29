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
                                Daily Work Report - Upload Calling Sheet
                            </h3>
                        </center>
                        <script>
                            var data = [
                                ['Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data'],
                                ['Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data'],
                                ['Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data'],
                                ['Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data']
                            ];


                            function download_csv() {
                                var csv = 'Task Category,Company Name,Person Name,Address,Mobile Number,Designation,Duration,Remark,Next FU date,Categorisation\n';
                                data.forEach(function (row) {
                                    csv += row.join(',');
                                    csv += "\n";
                                });

                                console.log(csv);
                                var hiddenElement = document.createElement('a');
                                hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                                hiddenElement.target = '_blank';
                                hiddenElement.download = 'Calling Sheet DWR Sample.csv';
                                hiddenElement.click();
                            }
                        </script>
                        <button class="btn btn-success" onclick="download_csv()">Download Sample</button>
                        <div class="table-responsive no-dt">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>

                                        <th class="bold">Task Category</th>
                                        <th class="bold">Company Name</th>
                                        <th class="bold">Person Name</th>
                                        <th class="bold">Address</th>
                                        <th class="bold">Mobile Number</th>
                                        <th class="bold">Designation</th>
                                        <th class="bold">Duration</th>
                                        <th class="bold">Remark</th>
                                        <th class="bold">Next FU date</th>
                                        <th class="bold">Categorisation</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                        <td>Sample Data</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        <form role="form" enctype="multipart/form-data"
                              action="<?php echo base_url() ?>admin/reports/importcallingsheet" method="post"
                              class="form-horizontal form-groups-bordered">

                            <div class="row">
                                <div class="col-md-3"><h5>RM Name: <?= $data[0]->fullname; ?></h5>
                                    <?
                                    //$crdate1= "2019-08-16";
                                    $crdate1 = date("Y-m-d");
                                    
                                    $crdate = date('Y-m-d', strtotime($crdate1 . ' -1 day'));
                                    $crdate2 = date('Y-m-d', strtotime($crdate1 . ' -2 day'));
                                    $this->db->select('quota');
                                    $this->db->where('leave_end_date >=', $crdate);
                                    $this->db->where('quota <=', $crdate);
                                    $this->db->or_where('quota <=', $crdate2);
                                    $holiday = $this->db->get('tblholidays')->result();
                                    $holidate = $holiday[0]->quota;
                                    
                                  if(!empty($holidate[0])) {
                                      
                                      $leavestartpre = date('Y-m-d', strtotime($holidate . ' -1 day'));
                                        if (date('l', strtotime($leavestartpre)) == 'Sunday') {

                                            $crdate = date('Y-m-d', strtotime($leavestartpre . ' -1 day'));
                                            $crdate;
                                        } else {
                                            $crdate = $leavestartpre;
                                        }

                                    } else {
                                         $crdate = date('Y-m-d', strtotime($crdate1 . ' -1 day'));
                                        
                                        if (date('l', strtotime($crdate)) == 'Sunday') {

                                            $crdate = date('Y-m-d', strtotime($crdate . ' -1 day'));
                                               $crdate;
                                        } else {
                                            $crdate = $crdate;
                                        }
                                    }


                                    ?>
                                    <input id="allowed_date" TYPE="HIDDEN" name="allowed_date" value="<?= $crdate ?>" />
                                    <h5>Date: <input type="text"  name="date" id="date_work"
                                                     class="form-control datepicker" value="<?= date('Y-m-d');?>" data-format="dd-mm-yyyy"
                                                     data-parsley-id="17" placeholder="Select Date.."
                                                     autocomplete="off" required></h5></div>
                            </div>
                            <input type="hidden" name="staff_id" value="<?= $data[0]->staffid; ?>"/>

                            <div class="row">
                                <div class="col-md-4">

                                    <small>Task Type</small>
                                    <select disabled name="task_type" id="task_type" class="form-control selectpicker" required>
                                        <option>Select Task Type</option>
                                        <option value="Personal_Meeting">Meeting</option>
                                        <option value="Assignment">Assignment</option>
                                        <option selected value="Calling">Calling</option>
                                    </select>
                                    <input value="Calling" name="task_type" type="hidden" />
                                </div>
                                <div class="form-group col-md-4" app-field-wrapper="userfile"><small>Choose
                                            CSV File</small><input required type="file" id="userfile" name="userfile" class="form-control"  autocomplete="off"></div>
                            </div>
                            </hr>
                           
                            <div class="row">
                                <div class="col-md-4 margin pull-right" id="submitbutton" style="">
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
</div>
<?php init_tail(); ?>




<script>


    $(function () {
        var d = new Date();
        //previousdate = d.setDate(d.getDate() - 1);
           previousdate =   $('#allowed_date').val();

        // alert(previousdate);
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
