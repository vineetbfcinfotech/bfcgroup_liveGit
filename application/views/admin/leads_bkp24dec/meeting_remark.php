<div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Update Meeting </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <form action="" id="remarkform">
                    <div class="col-md-12">
                        <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                        <?php echo render_input('id', '', array('readonly' => 'readonly'), 'hidden'); ?>
                        <div class="col-md-6">
                            <?php echo render_input('name', 'custom_lead_name', array('readonly' => 'readonly')); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('phonenumber', 'leads_dt_phonenumber'); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('email', 'leads_dt_email', array('readonly' => 'readonly')); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('designation', 'acs_roles', array('readonly' => 'readonly')); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('company', 'clients_list_company', array('readonly' => 'readonly')); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('address', 'lead_address', array('readonly' => 'readonly')); ?>
                        </div>


                        <?php
                        if ( !empty($allleadremark) ) {
                            echo "<h5>Lead Remark</h5>";
                            foreach ($allleadremark as $allremark) {
                                
                                 $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname  ');
                                                $this->db->where('id', $allremark->lead_id);
                                                $this->db->join('tblstaff', 'tblleads.assigned=tblstaff.staffid');
                                                $result = $this->db->get('tblleads')->result();
                                ?>
                                <div class="col-md-11">
                                    <input type="text" class="form-control" value="<?php echo $allremark->remark; ?>"
                                           readonly/> <p class="pull-right">On <?php echo date('j M, Y', strtotime($allremark->created_on)) ?> By <?php echo $result[0]->wpname; ?></p>
                                </div>

                                <div class="col-md-1">
                                    <a href="<?= base_url(); ?>admin/leads/deleteremarkid/<?= $allremark->id; ?>"
                                       class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                </div>
                            <?php }
                        } ?>
                        <?php
                        if ( !empty($allmeetingremark) ) {
                            echo "<h5>Meeting Remark</h5>";
                            foreach ($allmeetingremark as $allremarkm) {
                                $this->db->where('staffid', $_SESSION['staff_user_id']);
                                $result = $this->db->get('tblstaff')->result();
                                
                                $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname');
                                                $this->db->where('lead_id', $allremark->lead_id);
                                                $this->db->join('tblstaff', 'tblmeeting_scheduled.assigned=tblstaff.staffid');
                                $result = $this->db->get('tblmeeting_scheduled')->result();
                                ?>

                                <div class="col-md-11">
                                    <input type="text" class="form-control" value="<?php echo $allremarkm->meeting_remark; ?>"
                                           readonly/><p class="pull-right"> On <?php echo date('j M, Y', strtotime($allremarkm->created_at)) ?> By <?php echo $result[0]->wpname; ?></p>
                                </div>

                                <div class="col-md-1">
                                    <a href="<?= base_url(); ?>admin/leads/deletemeetingremark/<?= $allremarkm->id; ?>"
                                       class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                </div>
                            <?php }
                        } ?>
                        <div class="clearfix"></div>
                        <!--<div class="form-group" app-field-wrapper="description"><label for="duration" class="control-label">Duration Of Interaction</label><input type="text" id="duration" name="duration" class="form-control" value=""></div>
-->
                        <div class="form-group" app-field-wrapper="description"><label for="meeting_remark" class="control-label">Remark</label><input type="text" id="meeting_remark" name="meeting_remark" class="form-control" value=""></div>
                        <!--<?php echo render_textarea('description', 'custom_lead_remark'); ?>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-info category-save-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>


        <script type="text/javascript">
            $("#remarkform").submit(function (e) {
                // alert('Form ');
                e.preventDefault();

                var id = $("input[name='id']").val();
                var name = $("input[name='name']").val();


                var phonenumber = $("input[name='phonenumber']").val();
                var email = $("input[name='email']").val();
                var designation = $("input[name='designation']").val();
                var company = $("input[name='company']").val();
                var address = $("input[name='address']").val();

                var meeting_remark = $("input[name='meeting_remark']").val();
                var duration = $("input[name='duration']").val();
                var added_by = $("input[name='added_by']").val();


                $.ajax({
                    url: '<?= base_url();?>admin/leads/update_meeting_remark/',
                    type: 'GET',
                    data: {
                        id: id,
                        name: name,
                        phonenumber: phonenumber,
                        email: email,
                        designation: designation,
                        company: company,
                        address: address,
                        meeting_remark: meeting_remark,
                        duration: duration,
                        added_by: added_by
                    },
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                        $("tbody").append("<tr><td>" + name + "</td><td>" + meeting_remark + "</td></tr>");
                        //alert("Record added successfully");
                        location.reload();
                    }
                });
            });
        </script>
    </div>
</div>
</div>