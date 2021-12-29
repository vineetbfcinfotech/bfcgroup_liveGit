<style>
#loader{
    width:100%;
    height:100%;
    position:fixed;
    z-index:9999;
	background:url("<?php echo base_url("assets/images/page_loader.gif"); ?>") no-repeat center center rgba(0,0,0,0.25);
	background-size: 50px auto;
}
</style>
<!-- <div id="loading-image" style="display: none; text-align: center;">
    <img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
</div> -->
<div id="loader"></div>
<div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Book Details</h4>
        </div>
        <div class="modal-body">
            <div class="row">
 

                <?php
                // $this->db->order_by("created_on", "desc");
                $this->db->where('lead_id',  $lead_id);
                $this->db->group_by("remark");
                $this->db->select('remark');
                $data = $this->db->get('tblleadremark')->result();

                $all_remarks = array();
                foreach ($data as $remarkdata) {

                    array_push($all_remarks, $remarkdata->remark);
                }

                $otherphonenumber = $otherphonenumber;
                $allleadremarkdata = implode(';', $all_remarks)
                ?>

                <form action="" id="remarkform" autocomplete="off">
                    <div class="col-md-12">
                        <div class="row">
                            <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                            <?php echo render_input('id', '', array('readonly' => 'readonly'), 'hidden'); ?>

                            <?php echo render_input('srnumber', '', array('readonly' => 'readonly'), 'hidden'); ?>
                            <div class="col-md-4">
                                <?php //echo render_input('name', 'custom_lead_name', array('readonly' => 'readonly')); 
                                ?>
                                <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $name; ?>" autocomplete="no">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" app-field-wrapper="phonenumber">
                                    <label for="phonenumber" class="control-label">Phone</label>
                                    <input type="text" id="phonenumber" name="phonenumber" class="form-control" value="<?php echo $phonenumber; ?>" disabled="disabled">
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="form-group" app-field-wrapper="email">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4" hidden>
                                <?php echo render_input('calling_objective', 'lead_calling_objective', array('readonly' => 'readonly')); ?>
                            </div>
                        </div>

                        <hr>

                        <h3 class="text-center">BOOK DETAILS </h3>
                        <?php
                        for ($i = 0; $i < $all_lead_data->no_of_books; $i++) {
                        ?>

                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" app-field-wrapper="designation">
                                        <label for="booktitle" class="control-label">Book Title</label>
                                        <input type="text" id="booktitle" name="booktitle-<?= $i; ?>" class="form-control" autocomplete="no" value="<?php echo $booktitle; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4" id="book_formatdiv">

                                    <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Book Format</label>
                                        <select name="book_format" class="form-control" data-width="100%" data-live-search="true" id="book_format-<?= $i; ?>">
                                            <option value=""></option>
                                            <option value="Ebook" <?php if ($book_format == 'Ebook') {
                                                                        echo "selected";
                                                                    } ?>>Ebook</option>
                                            <option value="Paperback" <?php if ($book_format == 'Paperback') {
                                                                            echo "selected";
                                                                        } ?>>Paperback</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" app-field-wrapper="designation">
                                        <label for="booktitle" class="control-label">Book Language</label>
                                        <select name="bookLanguage" class="form-control" data-width="100%" data-live-search="true" id="bookLanguage-<?= $i; ?>">
                                            <option value=""></option>
                                            <option value="hindi" <?php if ($language == 'hindi') {
                                                                        echo "selected";
                                                                    } ?>>Hindi</option>
                                            <option value="english" <?php if ($language == 'english') {
                                                                        echo "selected";
                                                                    } ?>>English</option>
                                            <option value="others" <?php if ($language == 'others') {
                                                                        echo "selected";
                                                                    } ?>>Others</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4" id="book_formatdiv1">

                                    <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Manuscript Status</label>
                                        <select name="manuscriptStatus" class="form-control" data-width="100%" data-live-search="true" id="manuscriptStatus-<?= $i; ?>">
                                            <option value=""></option>
                                            <option value="completed" <?php if ($manuscript == 'completed') {
                                                                            echo "selected";
                                                                        } ?>>Completed</option>
                                            <option value="in_process" <?php if ($manuscript == 'in_process') {
                                                                            echo "selected";
                                                                        } ?>>In process</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" app-field-wrapper="publishedEarlier">
                                        <label for="next_PublishedEarlier" class="control-label">Published Earlier</label>
                                        <select name="publishedEarlier" class="form-control statuschangelead" data-width="100%" data-live-search="true" id="publishedEarlier-<?= $i; ?>" data-publishedEarlier-id="">
                                            <option value=""></option>
                                            <option value="yes" <?php if ($publishedEarlier == 'yes') {
                                                                    echo "selected";
                                                                } ?>>Yes</option>
                                            <option value="no" <?php if ($publishedEarlier == 'no') {
                                                                    echo "selected";
                                                                } ?>>No</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <label for="email" class="control-label">Category</label>
                                    <select name="status-<?= $i; ?>" class="form-control statuschangelead" data-width="100%" data-live-search="true" id="lead_status_change" data-lead-id="<?php //echo $allleadremark[0]->lead_id; 
                                                                                                                                                                                            ?>">
                                        <option value="" selected>Select Category</option>
                                        <?php foreach ($lstatus as $leadst) {
                                            echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $categoryStatus ? 'selected' : '', $leadst->name);
                                        } ?>
                                    </select>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4" id="next_callingdiv">

                                    <div class="form-group" app-field-wrapper="next_calling"><label for="next_calling" class="control-label">Next Calling Date</label>
                                        <input type="text" id="next_calling" name="next_calling" class="form-control datetimepicker" value="<?php if ($next_calling == null) {
                                                                                                                                                echo "";
                                                                                                                                            } elseif ($next_calling == '0000-00-00 00:00:00') {
                                                                                                                                                echo "";
                                                                                                                                            } else {
                                                                                                                                                echo $next_calling;
                                                                                                                                            } ?>">
                                    </div>

                                </div>
                                <div class="col-md-4 form-group" app-field-wrapper="description">
                                    <label for="description" class="control-label">Remark</label>
                                    <input type="text" id="descriptions" name="description-<?= $i; ?>" class="form-control" value="">
                                </div>
                                <div class="col-md-4 form-group" id="setReminder" app-field-wrapper="Reminder" style="<?php if ($categoryStatus == "39") { ?>display: none;<?php } else { ?> display: block; <?php } ?>">
                                    <label for="Reminder" class="control-label">Set Reminder</label>
                                    <input type="checkbox" id="reminder" name="reminder" class="form-control" value="">
                                </div>

                            </div>
                        <?php
                        }
                        ?>
                        <div class="row aquired_data" style="<?php if ($categoryStatus == "39") { ?>display: block;<?php } else { ?> display: none; <?php } ?>">
                            <div class="col-md-6">
                                <div class="form-group" app-field-wrapper="package_cost">
                                    <label for="package_cost" class="control-label">Package cost</label>
                                    <input type="text" id="package_cost-<?= $i; ?>" name="package_cost" class="form-control" value="<?php echo $all_lead_data->lead_packg_totalamount; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" app-field-wrapper="booking_amount">
                                    <label for="booking_amount" class="control-label">Booking amount</label>
                                    <input type="text" id="booking_amount-<?= $i; ?>" name="booking_amount" class="form-control" value="<?php echo $all_lead_data->booking_amount; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" app-field-wrapper="finstallment">
                                    <label for="finstallment" class="control-label">First installment</label>
                                    <input type="text" id="finstallment-<?= $i; ?>" name="finstallment" class="form-control" disabled value="<?php echo $all_lead_data->first_installment; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" app-field-wrapper="final_payment">
                                    <label for="final_payment" class="control-label">Final payment</label>
                                    <input type="text" id="final_payment-<?= $i; ?>" name="final_payment" class="form-control" disabled value="<?php echo $all_lead_data->final_payment; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" app-field-wrapper="receipt">
                                    <label for="receipt" class="control-label">Upload Receipt</label>
                                    <input type="file" id="receipt" name="receipt-<?= $i; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" app-field-wrapper="gst_number">
                                    <label for="gst_number" class="control-label">GST Number</label>
                                    <input type="text" id="gst_number" name="gst_number" class="form-control" value="<?php echo $all_lead_data->lead_gst_number; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" app-field-wrapper="State">
                                    <label for="" class="control-label">State</label>
                                    <input type="text" id="state" name="state" class="form-control" value="<?php echo $all_lead_data->state_create_p; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php if ($all_lead_data->lead_payment_reciept) { ?>
                                    <img src="<?= base_url(); ?>assets/images/payment_receipt/<?php echo $all_lead_data->lead_payment_reciept; ?>" height="100px" width="100px" />
                                <?php } ?>
                            </div>
                        </div>
                        <input type="hidden" name="assigned-<?= $i; ?>" id="assigned" value="<?= $assigned; ?>">


                        <div class="col-md-6" id="wpdiv">
                            <label for="rmlists" class="control-label">WP List</label>







                            <?php
                            $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname , tblmeeting_scheduled.assigned as assignedwp ');
                            $this->db->where('lead_id', $lead_id);
                            $this->db->join('tblstaff', 'tblmeeting_scheduled.assigned=tblstaff.staffid');
                            $query = $this->db->get('tblmeeting_scheduled');
                            $q2 = $query->result();



                            $aswp = $query->num_rows();
                            if ($aswp > 0) {
                                $asgndwp = $q2['0']->wpname;
                            } else {
                                $asgndwp = "0";
                            }

                            ?>

                            <?= $q2['0']->wpname; ?>

                            <select name="reassignlead" id="wpformeet" data-none-selected-text="choose WP" data-live-search="true" class="form-control rmList" data-lead_id="<?= $lead_id; ?>">

                                <?php if ($asgndwp != "0") { ?>
                                    <option selected value="<?= $q2['0']->assignedwp ?>"> <?= $q2['0']->wpname; ?></option>

                                <?php }


                                ?>
                            </select>



                        </div>




                        <div class="col-md-6" id="meetingtimefromdiv">
                            <?php echo render_input('meetingtimefrom', 'meetingtimefrom', array('readonly' => 'readonly')); ?>
                        </div>

                        <div class="col-md-6" id="meetingtimetodiv">
                            <?php echo render_input('meetingtimeto', 'meetingtimeto', array('readonly' => 'readonly')); ?>
                        </div>




                        </br>

                        <div class="clearfix"></div>

                        <?php // echo render_input('description', 'custom_lead_remark'); 
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?php if (!is_admin()) {
                        ?>
                            <button type="submit" name="submit" id="formSubmit" class="btn btn-info category-save-btn cline215">Submit</button>

                        <?php  } ?>
                    </div>
                </form>
            </div>
        </div>


        <script type="text/javascript">
         $('#loader').hide();
            $("#remarkform").submit(function(e) {});

            $('#formSubmit').click(function(e) {
                var catogary = $("#lead_status_change").val();
                if (catogary == 39 && ($('#state').val()) == '') {
                    alert('Please Enter Author State');
                    return false;
                }
                e.preventDefault();
                <?php
                for ($i = 0; $i < $all_lead_data->no_of_books; $i++) {
                ?>
                    var id = $("input[name='id']").val();
                    var booktitle = $("input[name='booktitle-<?= $i; ?>']").val();
                    var publishedEarlier = $("#publishedEarlier-<?= $i; ?>").val();
                    var description = $("input[name='description-<?= $i; ?>']").val();
                    var status = $('select[name="status-<?= $i; ?>"]').val();
                    var book_format = $('#book_format-<?= $i; ?>').val();
                    var bookLanguage = $('#bookLanguage-<?= $i; ?>').val();
                    var manuscriptStatus = $('#manuscriptStatus-<?= $i; ?>').val();
                    var package_cost = $('#package_cost-<?= $i; ?>').val();
                    var booking_amount = $('#booking_amount-<?= $i; ?>').val();
                    var finstallment = $('#finstallment-<?= $i; ?>').val();
                    var final_payment = $('#final_payment-<?= $i; ?>').val();;
                    var form_data = new FormData();
                    var url_data = window.location.href; 
                    
                    form_data.append('id', id);
                    form_data.append('book_format', book_format);
                    form_data.append('booktitle', booktitle);
                    form_data.append('manuscriptStatus', manuscriptStatus);
                    form_data.append('bookLanguage', bookLanguage);
                    form_data.append('publishedEarlier', publishedEarlier);
                    form_data.append('status', status);
                    form_data.append('package_cost', package_cost);
                    form_data.append('booking_amount', booking_amount);
                    form_data.append('finstallment', finstallment);
                    form_data.append('final_payment', final_payment);
                    form_data.append('description', description);
                    console.log(form_data);                   
                   
                    
                    $('#loader').show();
                    $.ajax({
                        url: '<?= base_url(); ?>admin/leads/update_custom_lead_remark_other_book/',
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                 
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            $('#product_catagory').modal('hide');

                            if (status == 5 || status == 16 || status == 38) {
                                //#rowData14049 > td:nth-child(3) > a
                                $("#rowData" + id + " > td:nth-child(3) > a").removeAttr("style");
                            } else {
                                //pointer-events: none; color: #9cc6d9;
                                $("#rowData" + id + " > td:nth-child(3) > a").css("pointer-events", "none");;
                            }
                            if (data == "sameTitle") {
                                alert("Title already exist.");
                                window.location.href=url_data;
                            } else {

                                $.ajax({
                                    url: "<?php echo base_url(); ?>admin/leads/get_row_lead_data",
                                    method: 'POST',
                                    data: {
                                        id: id,
                                        srnumber: srnumber
                                    },
                                    success: function(res) {

                                        var sta = '';
                                        if (status == 5) {
                                            sta = 'A';
                                        } else if (status == 16) {
                                            sta = 'B';
                                        } else if (status == 38) {
                                            sta = 'B+';
                                        } else if (status == 30) {
                                            sta = 'C';
                                        } else if (status == 32) {
                                            sta = 'NP';
                                        } else if (status == 39) {
                                            sta = 'Acquired';
                                        } else if (status == 40) {
                                            sta = 'Unattended';
                                        } else if (status == 41) {
                                            sta = 'Scrap';
                                        } else {}

                                        // $('#loading-image').hide();
                                         $('#loader').hide();
                                         alert("test");
                                        $(".prviewdata").css({ 'pointer-events' : '', 'opacity' : '' });
                                        $( ".modal-backdrop" ).removeClass( "fade fade in" )
                                        $(".modal-backdrop").css({'position': 'unset'})
                                        window.location.href=url_data;
                                        // window.location.reload();
                                    }
                                });

                            }
                        }
                    });
                <?php } ?>
            })
        </script>


        <script>
            $("#meetingtimefrom").addClass("datetimepicker");
            $("#meetingtimeto").addClass("datetimepicker");
            $("#next_calling").addClass("datetimepicker");

            init_datepicker();
        </script>

        <script>
            function showphone() {
                $(this).hide();
                //$('#otherphonenumber').show();
                $('#otherphonelabel').show();
            }
            $(function() {
                //$('#otherphonenumber').hide();
                if ($('#otherphonenumber').val() != '') {
                    $('#otherphonelabel').show();
                } else {
                    $('#otherphonelabel').hide();
                }

                var status = $('select[name="status"]').val();

                switch (status) {
                    case "1":
                        $('#meetingtimefromdiv').show();
                        $('#meetingtimetodiv').show();
                        $('#wpdiv').show();
                        $('#next_callingdiv').hide();
                        break;

                    case "2":
                        $('#meetingtimefromdiv').hide();
                        $('#meetingtimetodiv').hide();
                        $('#wpdiv').hide();
                        $('#next_callingdiv').hide();
                        break;

                    default:

                        $('#meetingtimefromdiv').hide();
                        $('#meetingtimetodiv').hide();
                        $('#wpdiv').hide();
                        $('#next_callingdiv').show();

                }



            });
        </script>

        <script>
            $(function() {
                $(document).ready(function() {

                    $("select.statuschangelead").change(function() {
                        var stat = $(this).children("option:selected").val();
                        // alert(stat);
                        switch (stat) {
                            case "1":
                                $('#meetingtimefromdiv').show();
                                $('#meetingtimetodiv').show();
                                $('#wpdiv').show();
                                $('#next_callingdiv').hide();
                                break;

                            case "2":
                                $('#meetingtimefromdiv').hide();
                                $('#meetingtimetodiv').hide();
                                $('#wpdiv').hide();
                                $('#next_callingdiv').hide();
                                break;

                            default:

                                $('#meetingtimefromdiv').hide();
                                $('#meetingtimetodiv').hide();
                                $('#wpdiv').hide();
                                $('#next_callingdiv').show();

                        }
                    });
                    $(document).ready(function() {
                        $('#lead_status_change').change(function() {
                            //alert(this.value);
                            if (this.value == "39") {
                                $(".next_callingdivdata").hide();
                                $(".remark_data").hide();
                                $(".aquired_data").show();
                                $("#setReminder").hide();

                            } else {
                                $(".next_callingdivdata").show();
                                $(".remark_data").show();
                                $(".aquired_data").hide();
                                $("#setReminder").show();
                            }
                        });
                    });

                    $("input#meetingtimefrom").change(function() {
                        var meetingtimefrom = $('#meetingtimefrom').val();
                        //alert(meetingtimefrom);

                        if (meetingtimefrom != '') {
                            $.ajax({
                                url: "<?php echo base_url(); ?>admin/leads/meetingtimewp",
                                method: "POST",
                                data: {
                                    meetingtimefrom: meetingtimefrom
                                },
                                success: function(data) {
                                    $('#wpformeet').html(data);

                                }
                            });
                        } else {
                            alert('Select Date & Time');
                        }

                    });

                });
            });
        </script>
        <script>
            $(function() {
                $('.form-group input[type="checkbox"]').change(function() {
                    if ($(this).is(":checked")) {
                        $(this).val('1')
                    } else
                        $(this).val('0');
                });
            });
            $(document).ready(function() {
                var package_cost = $('#package_cost').val();

                var booking_amount = (package_cost / 100) * 40;
                booking_amount = Math.round(booking_amount);
                var first_installment = (package_cost / 100) * 40;
                first_installment = Math.round(first_installment);
                var final_payment = (package_cost / 100) * 20;
                final_payment = Math.round(final_payment);
                document.getElementById("booking_amount").value = booking_amount;
                document.getElementById("finstallment").value = first_installment;
                document.getElementById("final_payment").value = final_payment;

            });
            $(document).ready(function() {
                $("#booking_amount").keyup(function() {

                    var package_cost = $('#package_cost').val();
                    var booking_amount_data = $(this).val();
                    var final_payment = (package_cost / 100) * 20;
                    var booking_amount = (package_cost / 100) * 40;
                    if (booking_amount_data > booking_amount) {
                        var cal = (booking_amount_data / package_cost) * 100;
                        if (cal == 100) {
                            var get_per = 100 - cal;
                            var total_co = (package_cost / 100) * get_per;
                            document.getElementById("final_payment").value = Math.round(total_co);
                        } else if (cal > 80) {
                            alert('Please enter amount atleast 80% or 100% of package cost');
                            window.reload();
                        } else {
                            var get_per = 80 - cal;
                            var total_co = (package_cost / 100) * get_per;
                        }


                    } else {
                        var total_c = booking_amount - booking_amount_data;
                        var total_co = booking_amount + total_c;
                    }
                    document.getElementById("finstallment").value = Math.round(total_co);


                });
            });
        </script>
        <script>
            $(document).ready(function() {
                //alert();

                $("#descriptions").attr("autocomplete", "off");
                $("#next_calling").attr("autocomplete", "off");
                $('#phonenumber').css('width', '250px');
            });
            $('#product_catagory').modal({
                backdrop: 'static',
                keyboard: false
            })
        </script>
    </div>
</div>
</div>