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
                              action="<?php echo base_url() ?>admin/reports/save_daily_report" method="post"
                              class="form-horizontal form-groups-bordered">

                            <div class="row">
                                <div class="col-md-3"><h5>WP Name: <?= $data[0]->fullname; ?></h5>
                                    <?
                                    $this->db->select('date');
                                    $this->db->where('wp', $data[0]->staffid);
                                    
                                    
                                    $query = $this->db->get('tbldwrdatepermsn');
                                    $checkdate = $query->num_rows();
                                    if ($checkdate > 0) {
                                      $res =  $query->result();
                                       
                                        $crdate = $res[0]->date;
                                       
                                    }
                                    
                                    else
                                    {
                                    //$crdate1= "2019-08-16";
                                    
                                    
                                    $crdate = date('Y-m-d', strtotime($crdate1 . ' -1 day'));
									//$crdate = date('Y-m-d', strtotime($crdate1 . ' -2 day'));
                                    $this->db->select('*');
                                    //$this->db->where('leave_end_date >=', $crdate);
                                    $this->db->where('quota <=', $crdate);
                                    //$this->db->or_where('quota <=', $crdate2);
                                    $holiday = $this->db->get('tblholidays')->result();
									$countArray = count($holiday);
									$lastArray = $countArray-1;
                                    //$holidate = $holiday[0]->quota;
                                    $holidate = $holiday[$lastArray]->quota;
									
                                  //if(!empty($holidate[0])) {
                                  if(!empty($holidate[0])) {
                                      
                                      $leavestartpre = date('Y-m-d', strtotime($holidate . ' -1 day'));
                                      
                                      
                                        if (date('l', strtotime($leavestartpre)) == 'Sunday') {
                                            $crdate = date('Y-m-d', strtotime($leavestartpre . ' -1 day'));
                                            $crdate;
                                        }
                                        
                                        
                                        
                                        else {
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
                                    
                                    }
									//echo $leavestartpre."--Test";exit;
                                    ?>
                                    <input id="allowed_date" TYPE="hidden" name="allowed_date" value="<?= $crdate ?>" />
                                
                                    <h5>Date: <input type="text" name="date" id="date_work"
                                                     class="form-control datepicker" value="<?= date('Y-m-d');?>" data-format="dd-mm-yyyy"
                                                     data-parsley-id="17" placeholder="Select Date.."
                                                     autocomplete="off" required></h5></div>
                            </div>
                            <input type="hidden" name="staff_id" value="<?= $data[0]->staffid; ?>"/>

                            <div class="row">
                                <div class="col-md-4">

                                    <small>Task Type</small>
                                    <select name="task_type" id="task_type" class="form-control selectpicker">
                                        <option>Select Task Type</option>
                                        <option value="Personal_Meeting">Meeting</option>
                                        <option value="Assignment">Assignment</option>
                                        <option value="Calling">Calling</option>
                                    </select>
                                </div>
                            </div>
                            </hr>
                            <div class="row">
                                <div class="" id="task_category_div">

                                </div>
                                <div class="refencediv"></div>

                                <div class="input_fields_container" style="display:none">

                                    <div>
                                        <div class="row">
                                            <div class="col-md-4"><input type="text" name="reference_name[]"
                                                                         class="form-control"
                                                                         placeholder="Name of reference Person"></div>
                                            <div class="col-md-4"><input type="text" name="reference_number[]"
                                                                         class="form-control"
                                                                         placeholder="Number of reference Person" />
                                            </div>
                                            <button class="btn btn-sm btn-primary add_more_button">Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </br>
                            <div class="row">
                                <div class="col-md-4 margin pull-right" id="submitbutton" style="display: none;">
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
    $(document).ready(function () {
        $('#task_type').change(function () {
            var task_type = $('#task_type').val();
            if (task_type != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/reports/task_type",
                    method: "POST",
                    data: {task_type: task_type},
                    success: function (data) {
                        if (task_type == '111') {
                            $('.hidediv').html(data);
                        } else {
                            $('#task_category_div').html(data);
                            $('#submitbutton').show();
                            $('html, body').animate({scrollTop: $('#submitbutton').offset().top}, 'slow');
                        }

                    }
                });
            } else {
                $('#state').html('<option value="">Select State</option>');
                $('#city').html('<option value="">Select City</option>');
            }
        });


        $('#task_category').change(function () {
            var task_category = $('#task_category').val();
            if (task_category != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/reports/task_category",
                    method: "POST",
                    data: {task_category: task_category},
                    success: function (data) {
                        if (task_category == '111') {
                            $('.hidediv').html(data);
                        } else {
                            $('#task_category_fields').html(data);
                        }

                    }
                });
            } else {
                $('#state').html('<option value="">Select State</option>');
                $('#city').html('<option value="">Select City</option>');
            }
        });


        $(".checkbox").change(function () {
            if (this.checked) {
                var $ctrl = $('<input/>').attr({type: 'text', name: 'text', value: 'text'}).addClass("text");
                $("#refencediv").append($ctrl);
            }
        });


    });
</script>


<script>
    $(document).ready(function () {
        var max_fields_limit = 10; //set limit for maximum input fields
        var x = 1; //initialize counter for text box
        $('.add_more_button').click(function (e) { //click event on add more fields button having class add_more_button
            e.preventDefault();
            if (x < max_fields_limit) { //check conditions
                x++; //counter increment
                $('.input_fields_container').append('<div><div class="row"><div class="col-md-4"><input type="text" name="reference_name[]" class="form-control" placeholder="Name of reference Person" ></div><div class="col-md-4"><input type="text" name="reference_number[]" class="form-control" placeholder="Number of reference Person" /></div><a href="#" class="remove_field" style="margin-left:10px;">Remove</a></div></div>'); //add input field
            }
        });
        $('.input_fields_container').on("click", ".remove_field", function (e) { //user click on remove text links
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });
</script>
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
