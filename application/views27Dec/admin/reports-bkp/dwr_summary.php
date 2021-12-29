<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body"><h2>DWR Summary</h2>
                        <hr class="hr-panel-heading">
                        <?= form_open('', array('method' => 'GET')); ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="start" id="start_date" class="form-control"
                                           placeholder="Select Start Date.." value="<?= @$start; ?>" autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="end" id="end_date" class="form-control datepicker"
                                           value="<?= @$end; ?>"
                                           data-format="dd-mm-yyyy" data-parsley-id="17"
                                           placeholder="Select End Date.." autocomplete="off">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <?= form_submit(['value' => "Filter", 'class' => 'btn btn-info']); ?>
                            </div>
                        </div>
                        <?= form_close(); ?>
                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <hr class="hr-panel-heading"/>
                                <h4 class="no-margin text-center">-: Staff Wise DWR Summary <?= $show_date; ?> :- </h4>
                                <hr class="hr-panel-heading"/>
                            </div>
                        </div>


                        <?php if (!empty($staffList)) {
                            foreach ($staffList as $staff) { ?>
                                <div class="row leads-overview">
                                    <h4 style="text-indent: 15px">
                                        <strong><?= ucfirst($staff['firstname']) . ' ' . ucfirst($staff['lastname']); ?></strong>
                                    </h4>

                                    <hr class="hr-panel-heading"/>
                                    <table class="table  scroll-responsive   no-footer"
                                          role="grid"
                                           >
                                        
                                    <tbody>   
                                        <tr role="row">
                                            <td><h5 style="text-indent: 15px"> Working Days : <?php


                                                    $wrkndays = workingdaysdwr($staff['staffid'], @$start, @$end);
                                                    echo $wrkndays;

                                                    ?></h5>
                                            </td>
                                            <td>
                                                <h5 style="text-indent: 15px"> Reference taken : <?php


                                                    $reference_taken = reference_taken($staff['staffid'], @$start, @$end);
                                                    echo $reference_taken->refernece_taken;
                                                    //print_r($reference_taken);
                                                    ?></h5>
                                            </td>

                                        </tr>


                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Meeting : Member</h5>
                                            </td>

                                            <?php
                                            $staffLeadStatus = get_dwr_member_meting_summary_satffwise($staff['staffid'], @$start, @$end);
                                            if (!empty($staffLeadStatus)) {
                                                foreach ($staffLeadStatus as $status) { ?>
                                                    <td>
                                           <span style="color:<?php echo $status['color']; ?>"
                                                 class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>"><h4
                                                       class="bold"><?php echo $status['name']; ?></h4></span>
                                                        <h4 class="bold">
                                                            <?php
                                                            if (isset($status['percent'])) {
                                                                echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                            } else {
                                                                echo $status['total'];
                                                            }
                                                            ?>
                                                        </h4>

                                                    </td>
                                                <?php }
                                            } ?>
                                        </tr>
                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Meeting : Client</h5>
                                            </td>
                                            <?php
                                            $staffLeadStatus = get_dwr_Client_meting_summary_satffwise($staff['staffid'], @$start, @$end);
                                            if (!empty($staffLeadStatus)) {
                                                foreach ($staffLeadStatus as $status) { ?>
                                                    <td>
                                                        <h4 class="bold">
                                                            <?php
                                                            if (isset($status['percent'])) {
                                                                echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                            } else {
                                                                echo $status['total'];
                                                            }
                                                            ?>
                                                        </h4>

                                                    </td>
                                                <?php }
                                            } ?>
                                        </tr>
                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Meeting : Prospect</h5>
                                            </td>
                                            <?php
                                            $staffLeadStatus = get_dwr_Prospect_meting_summary_satffwise($staff['staffid'], @$start, @$end);
                                            if (!empty($staffLeadStatus)) {
                                                foreach ($staffLeadStatus as $status) { ?>
                                                    <td>
                                                        <h4 class="bold">
                                                            <?php
                                                            if (isset($status['percent'])) {
                                                                echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                            } else {
                                                                echo $status['total'];
                                                            }
                                                            ?>
                                                        </h4>

                                                    </td>
                                                <?php }
                                            } ?>
                                        </tr>
                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Meeting : Lead</h5>

                                            </td><?php
                                            $staffLeadStatus = get_dwr_Lead_meting_summary_satffwise($staff['staffid'], @$start, @$end);
                                            if (!empty($staffLeadStatus)) {
                                                foreach ($staffLeadStatus as $status) { ?>
                                                    <td>
                                                        <h4 class="bold">
                                                            <?php
                                                            if (isset($status['percent'])) {
                                                                echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                            } else {
                                                                echo $status['total'];
                                                            }
                                                            ?>
                                                        </h4>

                                                    </td>
                                                <?php }
                                            } ?>
                                        </tr>
                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Meeting : Reference</h5></td>
                                            <?php
                                            $staffLeadStatus = get_dwr_Reference_meting_summary_satffwise($staff['staffid'], @$start, @$end);
                                            if (!empty($staffLeadStatus)) {
                                                foreach ($staffLeadStatus as $status) { ?>
                                                    <td>
                                                        <h4 class="bold">
                                                            <?php
                                                            if (isset($status['percent'])) {
                                                                echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                            } else {
                                                                echo $status['total'];
                                                            }
                                                            ?>
                                                        </h4>

                                                    </td>
                                                <?php }
                                            } ?>
                                        </tr>
                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Meeting : Visit</h5>
                                            </td>
                                            <?php
                                            $staffLeadStatus = get_dwr_Visit_meting_summary_satffwise($staff['staffid'], @$start, @$end);
                                            if (!empty($staffLeadStatus)) {
                                                foreach ($staffLeadStatus as $status) { ?>
                                                    <td>
                                                        <h4 class="bold">
                                                            <?php
                                                            if (isset($status['percent'])) {
                                                                echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                            } else {
                                                                echo $status['total'];
                                                            }
                                                            ?>
                                                        </h4>

                                                    </td>
                                                <?php }
                                            } ?>
                                        </tr>
                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Meeting : Others</h5>
                                            </td>
                                            <?php
                                            $staffLeadStatus = get_dwr_Others_meting_summary_satffwise($staff['staffid'], @$start, @$end);
                                            if (!empty($staffLeadStatus)) {
                                                foreach ($staffLeadStatus as $status) { ?>
                                                    <td>
                                                        <h4 class="bold">
                                                            <?php
                                                            if (isset($status['percent'])) {
                                                                echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                            } else {
                                                                echo $status['total'];
                                                            }
                                                            ?>
                                                        </h4>

                                                    </td>
                                                <?php }
                                            } ?>
                                        </tr>
                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Calling</h5>
                                            </td>
                                            <?php
                                            $staffLeadStatus = get_dwr_calling_summary_satffwise($staff['staffid'], @$start, @$end);
                                            // print_r($staffLeadStatus);
                                            if (!empty($staffLeadStatus)) {
                                                foreach ($staffLeadStatus as $status) { ?>
                                                    <td>
                                           <span style="color:<?php echo $status['color']; ?>"
                                                 class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>"><h4
                                                       class="bold"><?php echo $status['name']; ?></h4></span>
                                                        <h4 class="bold">
                                                            <?php
                                                            if (isset($status['percent'])) {
                                                                echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                                            } else {
                                                                echo $status['total'];
                                                            }
                                                            ?>
                                                        </h4>

                                                    </td>
                                                <?php }
                                            } ?>
                                        </tr>

                                        <tr role="row">
                                            <td>
                                                <h5 style="text-indent: 15px"> Assignment</h5>
                                            </td>
                                            <td>
                                                <h4 class="bold">
                                                    <?php


                                                    $reference_taken = assignment_done($staff['staffid'], @$start, @$end);

                                                    // print_r($reference_taken);
                                                    echo count($reference_taken);
                                                    /*foreach($reference_taken as $ass) {
                                                    echo $ass->ass_desc.', ';
                                                    }*/
                                                    ?>

                                                </h4>
                                            </td>
                                        </tr>
                                        </tbody> 


                                    </table>
                                </div>
                                <hr class="hr-panel-heading"/>
                            <?php }
                        } ?>
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
