<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <center><h3>Submit Task Report</h3></center>
                        
                        <?
                        $loginid = $this->session->userdata('staff_user_id');
                        $this->db->where('staffid', $loginid);

                        $this->db->select('CONCAT(tbll.firstname, " ", tbll.lastname) AS fullname,tbll.staffid ');
                        $data = $this->db->get('tblstaff as tbll')->result();
                        //print_r($data);
                        ?>
                       <form id="reportform" role="form" enctype="multipart/form-data"
                              action="<?php echo base_url() ?>admin/operations/submit_task_report" method="post"
                              class="form-horizontal form-groups-bordered">

                            <div class="row">
                                <div class="col-md-3"><h5>RM Name: <?= $data[0]->fullname; ?></h5>
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
                                   // $crdate2 = date('Y-m-d', strtotime($crdate1 . ' -2 day'));
                                    $this->db->select('quota');
                                    $this->db->where('leave_end_date >=', $crdate);
                                    $this->db->where('quota <=', $crdate);
                                    //$this->db->or_where('quota <=', $crdate2);
                                    $holiday = $this->db->get('tblholidays')->result();
                                    $holidate = $holiday[0]->quota;
                                    
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

                                    ?>
                                    <input id="allowed_date" TYPE="HIDDEN" name="allowed_date" value="<?= $crdate ?>" />
                                
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
                                        <option>Select Frequency of Task</option>
                                        <option value="Daily">Daily</option>
						    <option value="Weekly">Weekly</option>
						    <option value="Monthly">Monthly</option>
						    <option value="Quartely">Quartely</option>
						    <option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                            </hr>
                            <div class="row">
                                <div class="" id="task_category_div">

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
<?php init_tail(); ?>
<script>
    	

</script>
<script>

    
$(function(){
    var che = 	dt.rows().count();
    alert(che);
    
    if ($('input.checkbox_check').is(':checked')) {
       $("input").prop('required',true);
    }
});
</script>

<script>
    $(document).ready(function () {
        $('#task_type').change(function () {
            var task_type = $('#task_type').val();
            var date_work = $('#date_work').val();
            if (task_type != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/operations/task_type",
                    method: "POST",
                    data: {task_type: task_type,date_work: date_work},
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


        


    });
</script>
<script>

$(document).ready(function () {
    
    $(".taskdone").change(function () {
        alert("false");
   if ($(this).prop("checked")==true){ 
        alert("false");
        //do something
    }
    
 });

    
    
     
});
  // send form data to server
 
            
  

 </script>
      
</body>
</html>
