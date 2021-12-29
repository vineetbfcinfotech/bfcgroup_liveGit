<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">
                        <script>
                            var data = [
                                ['Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data'],
                                ['Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data'],
                                ['Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data'],
                                ['Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data', 'Sample Data']
                            ];


                            function download_csv() {
                                 
                                var csv = 'Lead Creation Date,Ad Id,Ad Name,Book Language,Manuscript Status,Published Earlier,Email,Author Name,Contact Number\n';
                                data.forEach(function (row) {
                                    csv += row.join(',');
                                    csv += "\n";
                                });

                                console.log(csv);
                                var hiddenElement = document.createElement('a');
                                hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                                hiddenElement.target = '_blank';
                                hiddenElement.download = 'Lead Sample.csv';
                                hiddenElement.click();
                            }
                        </script>

                      <!--  <button class="btn btn-alert" onclick="window.location='allleads';"> Back</button>-->
                        <button class="btn btn-success" onclick="download_csv()">Download Sample</button>

                        <form action="<?php echo site_url(); ?>admin/leads/upload_leads" method="post"
                              enctype="multipart/form-data" name="form1" id="form1" onsubmit="button_disable.disabled = true; return true;">

                            <hr>
                           <!-- <ul>
                                <li>1. Your CSV data should be in the format below. The first line of your CSV file
                                    should be the column headers as in the table example. Also make sure that your file
                                    is <b>UTF-8</b> to avoid unnecessary <b>encoding problems</b>.
                                </li>
                                <li class="text-danger">2. Duplicate id rows wont be imported</li>
                            </ul>-->
                            <div class="table-responsive no-dt">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="bold"><b>Lead Creation Date</b></th>
                                        <th class="bold"><b>Ad Id</b></th>
                                        <th class="bold"><b>Ad Name</b></th>
                                        <th class="bold"><b>Book Language</b></th>
                                        <th class="bold"><b>Manuscript Status</b></th>
                                        <th class="bold"><b>Published Earlier</b></th>
                                        
                                        <th class="bold"><b>Email</b></th>
                                        <th class="bold"><b>Author Name</b></th>
                                        <th class="bold"><b>Contact Number</b></th>
                                        
                                        
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
                                       
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" app-field-wrapper="userfile"><label for="userfile"
                                                                                                class="control-label">Choose
                                            CSV File</label><input type="file" id="userfile" name="userfile"
                                                                   class="form-control" onchange="myFunction()"></div>
                                    <!-- <?php echo render_input('userfile', 'choose_csv_file', 'onkeyup="calculatePrice()', 'file'); ?>-->
                                    <?php
                                    //echo render_leads_status_select($statuses, ($this->input->post('status') ? $this->input->post('status') : get_option('leads_default_status')), 'lead_import_status');
                                    //echo render_leads_source_select($sources, ($this->input->post('source') ? $this->input->post('source') : get_option('leads_default_source')), 'lead_import_source');
                                    ?>
                                    <div class="form-group" app-field-wrapper="file_csv"><label for="file_csv"
                                                                                                class="control-label">File
                                            Name</label><input type="text" id="file_name" name="file_name"
                                                               class="form-control" value="" required></div>
                                    <?php //echo render_select('responsible', $members, array('staffid', array('firstname', 'lastname')), 'leads_import_assignee', $this->input->post('responsible')); ?>


                                    <button type="submit" name="submit" id="button_disable" class="btn btn-info import btn-import-submit" value="button_disable">
<!--                                        Import--> Upload
                                    </button>
                                </div>
                        </form>
                    </div>
                      <hr class="hr-panel-heading"/>
                      <h3>Review Leads</h3>
                      <hr class="hr-panel-heading"/>
                           <?php
                              if ( !empty($allleads) ) {
                                 foreach ($allleads as $alllead) { ?>
                                     <ul style="width: 20%;margin-right: 6px;display: inline-block;text-align: center">
                                         <a href="<?= sprintf(base_url('admin/leads/view_custom_lead/%s/%s/%s'), str_replace(' ', '_',$alllead->name ), $alllead->lead_id_start, $alllead->lead_id_end); ?>"
                                            style="cursor:pointer;">
                                             <i class="fa fa-folder fa-5x" aria-hidden="true"></i>
                                             <!--<i class="fa fa-file-excel-o fa-5x"
                                                aria-hidden="true"></i>--><br/><?= $alllead->name ?>
                                             <br/><?//= $alllead->uploaded_on ?><br/>
                                            <p> <?//= $alllead->fullname ?></p>
                                              </a>
                                              <!--<a class="pull-right btn btn-primary btn-xs" href="<?= sprintf(base_url('admin/leads/createXLS/%s/%s/%s/%s'),  $alllead->lead_id_start, $alllead->lead_id_end, $alllead->assigned_id,$alllead->name); ?>"></i> Export Data</a>-->
                                              <!--<a href="<?= sprintf(base_url('admin/leads/delete_leads/%s/%s'), $alllead->lead_id_start, $alllead->lead_id_end); ?>" class="_delete text-danger" <?php if (sprintf('%s',$staff_role->role) == "40"  ) echo 'style="display:none !important"'; ?>> Delete</a>-->
                                        
                                     </ul>
                                    <?php
                                 }
                              } else {
                                /* echo NOLEADFOUND;*/
                                echo "No Lead Found";
                              }
                           ?>
                </div>

            </div>
        </div>
        <?php init_tail(); ?>
        <script>
            function myFunction() {
//alert();
                var x = document.getElementById("userfile").value;
                y = x.split(" ").splice(-1);
                document.getElementById("file_name").value = y;

                // $( "#button_disable" ).click(function() {
                //      $("#button_disable").attr("disabled", true);
                //     $('#form1').submit();
                // });
            }
        </script>

        </body>
        </html>
