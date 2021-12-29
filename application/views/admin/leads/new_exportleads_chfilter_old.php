<?php init_head(); ?>





<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

<!-- Custom Filter -->

<div id="wrapper" data-curpage="<?php echo $this->uri->segment(4); ?>">

    <div class="content" style="min-width: 1900px;">

        <div class="row">

            <div class="col-md-12">

                <div class="panel_s">



                    <div class="_buttons">

                        <h3>



                    </div>
                    <div class="row">
                        <div class="col-md-2"> <a href="JavaScript:Void(0);" onclick="goBack()" class="btn btn-primary " onclick="">

                                Back</a>

                                <a href="exports_data"  class="btn btn-primary ">

Export All</a>
                            <a href="JavaScript:Void(0);" class="btn btn-primary export_filter">

                                Export</a>

                            <?php if (is_admin()) { ?>



                            <?php }  ?>
                        </div>

                        <div class="col-md-7">
                            <form method='post' action="<?= base_url() ?>admin/leads_order/exportleads">
                                <?php if ((is_admin())) { ?>
                                    <select id="staff_name" name="staff_name[]" multiple data-none-selected-text="Select PC" data-live-search="true" class="selectpicker custom_lead_filter">



                                        <?php
                                        if (!empty($get_staff)) {

                                            foreach ($get_staff as $get_comp) { ?>

                                                <option <?php if (in_array($get_comp->staffid, $staff_name)) {
                                                            echo "selected";
                                                        } ?> value="<?= $get_comp->staffid; ?>"><?= $get_comp->firstname; ?></option>

                                        <?php }
                                        } ?>

                                    </select>
                                <?php  } else {
                                } ?>

                                <select multiple data-none-selected-text="Select Category" name="category_type[]" id="tasktypefilter" data-live-search="true" class="selectpicker custom_lead_filter">

                                    <option <?php if (in_array(0, $search_cat)) {
                                                echo "selected";
                                            } ?> value="0">No Category</option>

                                    <?php
                                    if (!empty($get_task_type)) {

                                        foreach ($get_task_type as $get_comp) {  ?>

                                            <option <?php if (in_array($get_comp->id, $search_cat)) {
                                                        echo "selected";
                                                    } ?> value="<?= $get_comp->id; ?>"><?= $get_comp->name; ?></option>

                                    <?php }
                                    } ?>

                                </select>

                                <div class="dropdown bootstrap-select show-tick">

                                    <input type="text" id="start_date" autocomplete="false" value="<?= $start_date; ?>" name="start_date" placeholder="From Date" class="form-control datepicker custom_lead_filter" />



                                </div>

                                <div class="dropdown bootstrap-select show-tick">

                                    <input type="text" id="end_date" autocomplete="false" value="<?= $end_date; ?>" name="end_date" placeholder="To Date" class="form-control datepicker custom_lead_filter" />



                                </div>
                                <input class="btn btn-primary" type='submit' name='submit_cat' value='Search'>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">

                            <form method='post' action="<?= base_url() ?>admin/leads_order/expclear_filter">
                                <input class="btn btn-primary" type='submit' name='submit' value='Clear Filter'>
                            </form>
                        </div>
                        
                    </div>


                    <div class="panel-body" style="width: 4000px !important;">



                        <div class="clearfix"></div>



                        <hr class="hr-panel-heading" />

                        <div class="tableData">
                            <div class="col-md-1">


                                <p><?php echo $links; ?></p>
                                <p><?php echo $pagination_number; ?></p>

                                    <form method='post' action="<?= base_url() ?>admin/leads_order/exportleads">
                                        <input type='text' name='search_global' value='<?= $search ?>'>
                                        <input class="btn btn-primary" type='submit' name='submit' value='Search'>
                                    </form>
                               <br />

                            </div>
                             

                            <br />
                            <div class="mytable">

                                <table id="example33" class="  dataTable no-footer display table table-responsive table-striped table-bordered" cellspacing="0" width="100%" height="100%" role="grid" aria-describedby="example33_info" style="width: 100%;">


                                    <thead>

                                        <tr>

                                        <tr>
                                            <th class="bold"> Sr. No.</th>

                                            <th class="bold"> DB Id</th>

                                            <th class="bold"> Author Name</th>

                                            <th class="bold">Contact No 1</th>

                                            <th class="bold">Contact No 2</th>

                                            <th class="bold">Email Id</th>

                                            <th class="bold">Book Language</th>

                                            <th class="bold">Manuscript Status</th>

                                            <th class="bold">Remarks</th>

                                            <th class="bold">Calling Date</th>

                                            <th class="bold">Category</th>

                                            <th class="bold">Next Calling Date</th>

                                            <th class="bold">Book Format</th>

                                            <th class="bold">Book Title</th>

                                            <th class="bold">Created Date</th>

                                            <th class="bold">Ad Name</th>

                                            <th class="bold">Published Earlier</th>

                                        </tr>


                                    </thead>

                                    <tbody id="tbody" class="">

                                        <?php $i = 1;

                                        foreach ($leads as $r) : ?>

                                            <?php if ($r->lead_category_id == 5) {
                                                $cat = "A";
                                            } else if ($r->lead_category_id == 16) {
                                                $cat = "B";
                                            } else if ($r->lead_category_id == 38) {
                                                $cat = "B+";
                                            } else if ($r->lead_category_id == 30) {
                                                $cat = "C";
                                            } else if ($r->lead_category_id == 32) {
                                                $cat = "NP";
                                            } else if ($r->lead_category_id == 39) {
                                                $cat = "Acquired";
                                            } else if ($r->lead_category_id == 40) {
                                                $cat = "UnAttended";
                                            } else if ($r->lead_category_id == 41) {
                                                $cat = "Scrap";
                                            } else {

                                                $cat = "";
                                            } ?>

                                            <tr id="rowData<?php echo $r->id; ?>">



                                                <td><?php echo $i; ?></td>




                                                <td><?php echo $r->id; ?></td>

                                                <td><?php echo $r->lead_author_name; ?></td>

                                                <td><?php echo $r->phonenumber; ?></td>

                                                <td><?php echo $r->otherphonenumber; ?></td>

                                                <td><?php echo $r->email; ?></td>

                                                <td><?php echo $r->lead_author_mslanguage; ?></td>

                                                <td><?php echo $r->lead_author_msstatus; ?></td>

                                                <td><?php
                                                    $string = strip_tags($r->description);
                                                    if (strlen($string) > 100) {

                                                        $stringCut = substr($string, 0, 100);
                                                        $endPoint = strrpos($stringCut, ' ');

                                                        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                        $string .= '... <a class="read_full_description" data-details="' . $r->description . '">Read More</a>';
                                                    }
                                                    echo $string;
                                                    ?></td>

                                                <td><?php echo $r->lead_callingdate; ?>

                                                <td><?php echo $cat; ?></td>
                                                <td><?php echo $r->ImEx_NextcallingDate; ?></td>


                                                <td><?php echo $r->lead_bookformat; ?></td>

                                                <td><?php echo $r->lead_booktitle; ?></td>

                                                <td><?php echo $r->ImEx_CreatedAt; ?></td>

                                                <td><?php echo $r->lead_adname; ?></td>

                                                <td><?php echo $r->lead_publishedearlier; ?></td>



                                            </tr>



                                            <!-- Modal -->



                                            <!-- Modal close -->



                                        <?php $i++;

                                        endforeach ?>

                                    </tbody>

                                </table>

                            </div>
                            <p><?php echo $links; ?></p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div id="description_full_data" class="modal fade" role="dialog">

<div class="modal-dialog">

<!-- Modal content-->

<div class="modal-content">

           <form action="<?php echo base_url() ?>admin/leads/addRemarks" method="POST">

              <div class="modal-header">

                 <button type="button" class="close" data-dismiss="modal">&times;</button>

                 <h4 class="modal-title">Full Description:</h4>

              </div>

              <div class="modal-body">

                 <textarea cols="90" rows="10" id="full_remarks" readonly="" style="border:hidden;"></textarea>

                 <!-- <input type="hidden" name="remarks_id" id="remarks_id" class="remarks_ids" value=""/> -->

              </div>

              <div class="modal-footer">

                 <!-- <a href="#" class="btn btn-default" id="submit" name="submit">add</a> -->

                 <button type="button" class="btn btn-default" data-dismiss="modal" id="modelClose">Close</button>

              </div>

              <span id="alert-msg"></span>

           </form>

        </div>



</div>

</div>





</div>

</div>


<?php init_tail(); ?>





<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

<script type="text/javascript">
    $(document).on('click', '.read_full_description', function() {

        var full_remarks = $(this).attr("data-details");
        $("#full_remarks").val(full_remarks);
        $("#description_full_data").modal('show');
    });

    // function updateData(i) {

    //     sno = $('#modal-body_' + i + '> input[name="usno' + i + '"]').val();

    //     name = $('#modal-body_' + i + '> input[name="uname' + i + '"]').val();

    //     phno = $('#modal-body_' + i + '> input[name="uauthor' + i + '"]').val();

    //     msstatus = $('#modal-body_' + i + '> input[name="upublisher' + i + '"]').val();

    //     pub_earlier = $('#modal-body_' + i + '> input[name="uprice' + i + '"]').val();

    //     language = $('#modal-body_' + i + '> input[name="urating' + i + '"]').val();

    //     console.log(name + ' <-name ' + language + ' <-language ' + ' published-earlier->' + pub_earlier + ' status->' + msstatus + " phon->" + phno);

    //     data = {

    //         id: i,

    //         name: name,

    //         phno: phno,

    //         msstatus: msstatus,

    //         pub_earlier: pub_earlier,

    //         language: language

    //     }



    //     $.ajax({

    //         type: "post",

    //         url: '<?php echo base_url('admin/leads/update'); ?>',

    //         data: data,

    //         dataType: "json",

    //         success: function(data) {

    //             console.log(data);

    //             location.reload();

    //         }

    //     });

    // }
    // //  filter on row 
    // function filter() {
    //     auname = $('#auname').val();
    //     phno = $('#phno').val();
    //     adname = $('#adname').val();
    //     ms_status = $('#ms_status').val();
    //     pub_early = $('#pub_early').val();
    //     email = $('#email').val();
    //     lang = $('#lang').val();
    //     call_date = $('#call_date').val();
    //     cate = $('#cate').val();
    //     nc_date = $('#nc_date').val();
    //     create_date = $('#create_date').val();
    //     formate = $('#formate').val();
    //     title = $('#title').val();
    //     other_phno = $('#other_phno').val();
    //     console.log(ms_status)
    //     data = {
    //         auname: auname,
    //         phno: phno,
    //         adname: adname,
    //         ms_status: ms_status,
    //         pub_early: pub_early,
    //         email: email,
    //         lang: lang,
    //         call_date: call_date,
    //         cate: cate,
    //         nc_date: nc_date,
    //         create_date: create_date,
    //         formate: formate,
    //         title: title,
    //         other_phno: other_phno
    //     }
    //     // alert(JSON.stringify(data))
    //     $.ajax({
    //         type: "post",
    //         url: "<?php echo base_url('admin/leads/filter'); ?>",
    //         data: data,
    //         dataType: "json",
    //         success: function(data) {
    //             $('#tbody').html("");
    //             var j = 1;
    //             for (var i = 0; i < data.msg.data.length; i++) {
    //                 if (data.msg.data[i].lead_category_id == 5) {
    //                     cat = "A";
    //                 } else if (data.msg.data[i].lead_category_id == 16) {
    //                     cat = "B";
    //                 } else if (data.msg.data[i].lead_category_id == 38) {
    //                     cat = "B+";
    //                 } else if (data.msg.data[i].lead_category_id == 30) {
    //                     cat = "C";
    //                 } else if (data.msg.data[i].lead_category_id == 32) {
    //                     cat = "NP";
    //                 } else if (data.msg.data[i].lead_category_id == 39) {
    //                     cat = "Acquired";
    //                 } else if (data.msg.data[i].lead_category_id == 40) {
    //                     cat = "UnAttended";
    //                 } else if (data.msg.data[i].lead_category_id == 41) {
    //                     cat = "Scrap";
    //                 } else {
    //                     cat = "";
    //                 }
    //                 // console.log(data.msg.data[i].id);
    //                 if (data.msg.data[i].lead_category_id == 5 || data.msg.data[i].lead_category_id == 16 || data.msg.data[i].lead_category_id == 38) {
    //                     var create_pack = "<a href='#' style='pointer-events: none; color: #9cc6d9;'>create package</a>";
    //                 } else {
    //                     if (data.msg.data[i].craete_package != 1) {
    //                         var create_pack = "<a href='<?php echo base_url(); ?>admin/leads/publishing/" + data.msg.data[i].id + "' target='_blank' >create package</a>";
    //                     } else {

    //                         var create_pack = "<a href='javascript:void(0);' data-id='" + data.msg.data[i].id + "' class='prviewdata' >Preview</a>";


    //                     }

    //                 }
    //                 $('#tbody').append(
    //                     "<tr id='rowData+" + data.msg.data[i].id + "'><td>" + j + "</td><td><a data-controls-modal='your_div_id' data-backdrop='static' data-keyboard='false' href='javascript:void(0)' class='edit_lead' data-otarray='assignedleads2_line245' data-id='" + data.msg.data[i].id + "'><i class='fa fa-edit text-primary' style='font-size:24px;color:red'></i> </a></td><td>" + create_pack + "</td><td>" + data.msg.data[i].lead_author_name + "</td><td>" + data.msg.data[i].phonenumber + "</td><td>" + data.msg.data[i].lead_adname + "</td><td>" + data.msg.data[i].lead_author_msstatus + "</td><td>" + data.msg.data[i].lead_publishedearlier + "</td><td>" + data.msg.data[i].email + "</td><td>" + data.msg.data[i].language + "</td><td>" + data.msg.data[i].lead_callingdate + "</td><td>" + cat + "</td><td>" + data.msg.data[i].description + "</td><td>" + data.msg.data[i].ImEx_NextcallingDate + "</td><td>" + data.msg.data[i].lead_created_date + "</td><td>" + data.msg.data[i].lead_bookformat + "</td><td>" + data.msg.data[i].lead_booktitle + "</td><td>" + data.msg.data[i].otherphonenumber + "</td></tr>"
    //                 );
    //                 j++;
    //             }
    //         }
    //     });

    // }

    // function globalFilter() {
    //     key = $('#s_global').val();
    //     data = {
    //         key: key,
    //     }

    //     $.ajax({
    //         type: "post",
    //         url: "<?php echo base_url('admin/leads/gsearch'); ?>",
    //         data: data,
    //         dataType: "json",
    //         success: function(data) {
    //             if (data == null) {
    //                 location.reload();
    //             } else {
    //                 $('#tbody').html("");

    //                 for (var i = 0; i < data.msg.data.length; i++) {
    //                     var cat = '';
    //                     if (data.msg.data[i].lead_category_id == 5) {
    //                         cat = "A";
    //                     } else if (data.msg.data[i].lead_category_id == 16) {
    //                         cat = "B";
    //                     } else if (data.msg.data[i].lead_category_id == 38) {
    //                         cat = "B+";
    //                     } else if (data.msg.data[i].lead_category_id == 30) {
    //                         cat = "C";
    //                     } else if (data.msg.data[i].lead_category_id == 32) {
    //                         cat = "NP";
    //                     } else if (data.msg.data[i].lead_category_id == 39) {
    //                         cat = "Acquired";
    //                     } else if (data.msg.data[i].lead_category_id == 40) {
    //                         cat = "UnAttended";
    //                     } else if (data.msg.data[i].lead_category_id == 41) {
    //                         cat = "Scrap";
    //                     } else {
    //                         cat = "";
    //                     }
    //                     if (data.msg.data[i].lead_category_id == 5 || data.msg.data[i].lead_category_id == 16 || data.msg.data[i].lead_category_id == 38) {
    //                         var create_pack = "<a href='#' style='pointer-events: none; color: #9cc6d9;'>create package</a>";
    //                     } else {
    //                         if (data.msg.data[i].craete_package != 1) {
    //                             var create_pack = "<a href='<?php echo base_url(); ?>admin/leads/publishing/" + data.msg.data[i].id + "' target='_blank' >create package</a>";
    //                         } else {

    //                             var create_pack = "<a href='javascript:void(0);' data-id='" + data.msg.data[i].id + "' class='prviewdata' >Preview</a>";


    //                         }

    //                     }
    //                     // console.log(data.msg.data[i].id);
    //                     $('#tbody').append(
    //                         "<tr id='rowData+" + data.msg.data[i].id + "'><td>" + i + "</td><td><a data-controls-modal='your_div_id' data-backdrop='static' data-keyboard='false' href='javascript:void(0)' class='edit_lead' data-otarray='assignedleads2_line245' data-id='" + data.msg.data[i].id + "'><i class='fa fa-edit text-primary' style='font-size:24px;color:red'></i> </a></td><td>" + create_pack + "</td><td>" + data.msg.data[i].lead_author_name + "</td><td>" + data.msg.data[i].phonenumber + "</td><td>" + data.msg.data[i].lead_adname + "</td><td>" + data.msg.data[i].lead_author_msstatus + "</td><td>" + data.msg.data[i].lead_publishedearlier + "</td><td>" + data.msg.data[i].email + "</td><td>" + data.msg.data[i].language + "</td><td>" + data.msg.data[i].lead_callingdate + "</td><td>" + cat + "</td><td>" + data.msg.data[i].description + "</td><td>" + data.msg.data[i].ImEx_NextcallingDate + "</td><td>" + data.msg.data[i].lead_created_date + "</td><td>" + data.msg.data[i].lead_bookformat + "</td><td>" + data.msg.data[i].lead_booktitle + "</td><td>" + data.msg.data[i].otherphonenumber + "</td></tr>"
    //                     );
    //                 }
    //             }
    //         }
    //     });
    // }

    // $(document).on('change', '.class_name_here', function() {
    //     var staff_name = $('#staff_name').val();

    //     var tasktypefilter = $('#tasktypefilter').val();

    //     var start_date = $('#start_date').val();

    //     var end_date = $('#end_date').val();

    //     data = {
    //         staff_name: staff_name,
    //         tasktypefilter: tasktypefilter,
    //         start_date: start_date,
    //         end_date: end_date,
    //     }

    //     $.ajax({
    //         type: "post",
    //         url: "<?php echo base_url('admin/leads/filter_by_all'); ?>",
    //         data: data,
    //         dataType: "json",
    //         success: function(data) {
    //             if (data == null) {
    //                 location.reload();
    //             } else {
    //                 $('#tbody').html("");
    //                 var j = 0;
    //                 for (var i = 0; i < data.msg.data.length; i++) {
    //                     var cat = '';
    //                     j++;
    //                     if (data.msg.data[i].lead_category_id == 5) {
    //                         cat = "A";
    //                     } else if (data.msg.data[i].lead_category_id == 16) {
    //                         cat = "B";
    //                     } else if (data.msg.data[i].lead_category_id == 38) {
    //                         cat = "B+";
    //                     } else if (data.msg.data[i].lead_category_id == 30) {
    //                         cat = "C";
    //                     } else if (data.msg.data[i].lead_category_id == 32) {
    //                         cat = "NP";
    //                     } else if (data.msg.data[i].lead_category_id == 39) {
    //                         cat = "Acquired";
    //                     } else if (data.msg.data[i].lead_category_id == 40) {
    //                         cat = "UnAttended";
    //                     } else if (data.msg.data[i].lead_category_id == 41) {
    //                         cat = "Scrap";
    //                     } else {
    //                         cat = "";
    //                     }
    //                     if (data.msg.data[i].lead_category_id == 5 || data.msg.data[i].lead_category_id == 16 || data.msg.data[i].lead_category_id == 38) {
    //                         var create_pack = "<a href='#' style='pointer-events: none; color: #9cc6d9;'>create package</a>";
    //                     } else {
    //                         if (data.msg.data[i].craete_package != 1) {
    //                             var create_pack = "<a href='<?php echo base_url(); ?>admin/leads/publishing/" + data.msg.data[i].id + "' target='_blank' >create package</a>";
    //                         } else {

    //                             var create_pack = "<a href='javascript:void(0);' data-id='" + data.msg.data[i].id + "' class='prviewdata' >Preview</a>";


    //                         }

    //                     }
    //                     // console.log(data.msg.data[i].id);
    //                     $('#tbody').append(
    //                         "<tr id='rowData+" + data.msg.data[i].id + "'><td>" + j + "</td><td><a data-controls-modal='your_div_id' data-backdrop='static' data-keyboard='false' href='javascript:void(0)' class='edit_lead' data-otarray='assignedleads2_line245' data-id='" + data.msg.data[i].id + "'><i class='fa fa-edit text-primary' style='font-size:24px;color:red'></i> </a></td><td>" + create_pack + "</td><td>" + data.msg.data[i].lead_author_name + "</td><td>" + data.msg.data[i].phonenumber + "</td><td>" + data.msg.data[i].lead_adname + "</td><td>" + data.msg.data[i].lead_author_msstatus + "</td><td>" + data.msg.data[i].lead_publishedearlier + "</td><td>" + data.msg.data[i].email + "</td><td>" + data.msg.data[i].language + "</td><td>" + data.msg.data[i].lead_callingdate + "</td><td>" + cat + "</td><td>" + data.msg.data[i].description + "</td><td>" + data.msg.data[i].ImEx_NextcallingDate + "</td><td>" + data.msg.data[i].lead_created_date + "</td><td>" + data.msg.data[i].lead_bookformat + "</td><td>" + data.msg.data[i].lead_booktitle + "</td><td>" + data.msg.data[i].otherphonenumber + "</td></tr>"
    //                     );
    //                 }
    //             }
    //         }
    //     });
    // });
</script>

<script>
    $(document).ready(function() {

        // $(document).on('change','#staff_name,#tasktypefilter,#start_date,#end_date',function(){

        //     dataTable.draw();

        //   });
        $('#example33').DataTable({

            "bInfo": false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
            "paging": false, //Dont want paging                
            "bPaginate": false, //Dont want paging
            "bFilter": false, //hide Search bar      
            "iDisplayStart ": false,
            "iDisplayLength": false,
        });

    });





    $('.export_filter').click(function() {

        var staff_name = $('#staff_name').val();

        var tasktypefilter = $('#tasktypefilter').val();

        var start_date = $('#start_date').val();

        var end_date = $('#end_date').val();
        var total_date = start_date + end_date;
        console.log(tasktypefilter.length);

        if ((tasktypefilter.length > 0)) {
            $.ajax({

                url: '<?php echo base_url('admin/leads_order/filter_export'); ?>',

                type: 'POST',

                data: {
                    staff_name: staff_name,
                    tasktypefilter: tasktypefilter,
                    start_date: start_date,
                    end_date: end_date
                },

                error: function() {

                    alert('Something is wrong');

                },

                success: function(data) {

                    var json_pre = data;

                    var json = JSON.parse(json_pre, function(key, value) {

                        if (key == "Remarks") {

                            return value;

                        } else {

                            return value;

                        }


                    });

                    console.log(json);

                    var csv = JSON2CSV(json);

                    var downloadLink = document.createElement("a");

                    var blob = new Blob(["\ufeff", csv]);

                    var url = URL.createObjectURL(blob);

                    downloadLink.href = url;

                    downloadLink.download = "data.csv";

                    document.body.appendChild(downloadLink);

                    downloadLink.click();
                    return false;

                }

            });

        } else if (total_date != '') {
            $.ajax({

                url: '<?php echo base_url('admin/leads_order/filter_export'); ?>',

                type: 'POST',

                data: {
                    staff_name: staff_name,
                    tasktypefilter: tasktypefilter,
                    start_date: start_date,
                    end_date: end_date
                },

                error: function() {

                    alert('Something is wrong');

                },

                success: function(data) {

                    var json_pre = data;

                    var json = JSON.parse(json_pre, function(key, value) {

                        if (key == "Remarks") {

                            return value;

                        } else {

                            return value;

                        }


                    });

                    console.log(json);

                    var csv = JSON2CSV(json);

                    var downloadLink = document.createElement("a");

                    var blob = new Blob(["\ufeff", csv]);

                    var url = URL.createObjectURL(blob);

                    downloadLink.href = url;

                    downloadLink.download = "data.csv";

                    document.body.appendChild(downloadLink);

                    downloadLink.click();
                    return false;

                }

            });
        } else {
            alert('Please Select any one filter');


        }



    });

    function JSON2CSV(objArray) {

        var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;

        var str = '';

        var line = '';



        if ($("#labels").is(':checked')) {

            var head = array[0];

            if ($("#quote").is(':checked')) {

                for (var index in array[0]) {

                    var value = index + "";

                    line += '"' + value.replace(/"/g, '""') + '",';

                }

            } else {

                for (var index in array[0]) {

                    line += index + ',';

                }

            }



            line = line.slice(0, -1);

            str += line + '\r\n';

        }



        for (var i = 0; i < array.length; i++) {

            var line = '';



            if ($("#quote").is(':checked')) {

                for (var index in array[i]) {

                    var value = array[i][index] + "";

                    line += '"' + value.replace(/"/g, '""') + '",';

                }

            } else {

                for (var index in array[i]) {

                    line += array[i][index] + ',';

                }

            }



            line = line.slice(0, -1);

            str += line + '\r\n';

        }

        return str;

    }
    // $(document).on('click', '.edit_lead', function() {





    //     var otarray = $(this).attr("data-otarray");

    //     var id = $(this).attr("data-id");

    //     //   alert(id);

    //     // alert(otarray);

    //     $.ajax({

    //         url: "<?php echo base_url(); ?>admin/leads/getallleads",

    //         method: 'POST',

    //         data: {

    //             id: id,

    //             otarray: otarray

    //         },

    //         success: function(datar)

    //         {

    //             console.log(datar);

    //             var obj = JSON.parse(datar);

    //             $.ajax({

    //                 url: "<?php echo base_url(); ?>admin/leads/allleadremark2",

    //                 method: 'POST',

    //                 data: {

    //                     id: id,

    //                     name: obj.full_name,

    //                     email: obj.email,

    //                     phonenumber: obj.phone_number,

    //                     calling_objective: obj.ad_name,

    //                     booktitle: obj.booktitle,

    //                     book_format: obj.book_format,

    //                     publishedEarlier: obj.PublishedEarlier,

    //                     description: obj.categorisation,

    //                     next_callingd: obj.next_callingd,

    //                     next_calling: obj.next_callingd,

    //                     otherphonenumber: obj.otherphonenumber_n,

    //                     status: obj.categorisation,

    //                     manuscript: obj.manuscript_status,

    //                     language: obj.user_language,

    //                 },

    //                 success: function(data)

    //                 {
    //                     $("#product_catagory").html(data);

    //                     $('#phonenumber').attr("disabled", "disabled");



    //                     $("label[for='calling_objective']").text("Ad Name");

    //                     $('#calling_objective').attr("disabled", "disabled");

    //                     $("#descriptions").removeAttr("autocomplete");



    //                     $("form").attr('autocomplete', 'off');

    //                     $('#product_catagory').modal('show');

    //                 }

    //             });

    //         }

    //     });


    //     $('#additional').append(hidden_input('id', id));


    // });

    // $(document).on('click', '.prviewdata', function() {

    //     // alert('testt');

    //     var author_id = $(this).attr("data-id");

    //     $.ajax({

    //         type: "POST",

    //         url: "<?php echo admin_url('Leads/getPackageData'); ?>",

    //         data: {
    //             'author_id': author_id
    //         }, // <--- THIS IS THE CHANGE

    //         dataType: "html",

    //         success: function(data) {

    //             $(".modal-data").html(data);

    //             $("#author_data").modal('show');

    //         },

    //         error: function() {
    //             alert("Error posting feed.");
    //         }

    //     });

    // });
</script>

<script>
    function goBack() {

        window.history.back();

    }
</script>



</body>

</html>