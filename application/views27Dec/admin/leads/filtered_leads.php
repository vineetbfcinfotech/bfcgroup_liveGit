<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body"><h4>Filtered Lead Report</h4>
                        <hr class="hr-panel-heading"/>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table dt-table scroll-responsive">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Designation</th>
                                        <th>Company</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                   <?php if ( !empty($leads) ) {
                                      echo '<tbody>';
                                      foreach ($leads as $lead) {
                                         echo sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td></td></tr>", $lead->name, $lead->email, $lead->phonenumber, $lead->designation, $lead->company, $lead->address);
                                      }
                                      echo '</tbody>';
                                   } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>