<?php init_head(); ?>

<div id="wrapper">
    
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (!empty($name)) {
                            echo form_hidden('lead_id_start', $name->lead_id_start);
                            ?>
                            <div class="_buttons">
                                <h3>
                                    <a href="<?= base_url('admin/leads/allleads'); ?>" class="btn btn-alert" onclick="">
                                        Back</a>
                                    <?= ucfirst($name->name) . '-' . $name->uploaded_on; ?>
                                </h3>
                                <select id="statusfilter" multiple data-none-selected-text="Filter By Status"
                                        data-live-search="true" class="selectpicker custom_lead_filter">

                                    <?php if (!empty($lstatus)) {
                                        foreach ($lstatus as $leadfilter) { ?>
                                            <option value="<?= $leadfilter->id; ?>"><?= $leadfilter->name; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <select id="companyfilter" multiple data-none-selected-text="Filter By Company"
                                        data-live-search="true" class="selectpicker custom_lead_filter">

                                    <?php if (!empty($get_company)) {
                                        foreach ($get_company as $get_comp) { ?>
                                            <option value="<?= $get_comp->company; ?>"><?= $get_comp->company; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <select id="data_sourcefilter" multiple data-none-selected-text="Filter By Data Source"
                                        data-live-search="true" class="selectpicker custom_lead_filter">

                                    <?php if (!empty($data_source)) {
                                        foreach ($data_source as $data_sour) {
                                            ?>
                                            <option value="<?= $data_sour->data_source; ?>"><?= $data_sour->data_source; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <select id="calling_objectivefilter" multiple
                                        data-none-selected-text="Filter By Calling Objective" data-live-search="true"
                                        class="selectpicker custom_lead_filter">

                                    <?php if (!empty($calling_obj)) {
                                        foreach ($calling_obj as $calling_objective) { ?>
                                            <option value="<?= $calling_objective->calling_objective ?>"><?= $calling_objective->calling_objective ?></option>
                                        <?php }
                                    } ?>
                                </select>


                                <div class="dropdown bootstrap-select show-tick">
                                    <input type="text" id="lastcontactfilter" autocomplete="false" name="lastcontact"
                                           placeholder="Filter By Calling Date"
                                           class="form-control datepicker custom_lead_filter"/>

                                </div>
                                
                        <button id="unsetallfilter">Clear All Filters</button>
                            </div>
                            <?php
                        } ?>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-1">
                                <input type="text" id="jumtopage" autocomplete="false" name="jumtopage"
                                           placeholder="Page Number"
                                           class="form-control jumtopage"/>
                            </div>
                            <div class="dt-buttons btn-group"><a class="btn btn-default"  onclick="jumptopagefunc()" ><span>Go</span></a></div>
                            
                           
                            
                        </div>
                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading"/>
                        <?php if (!empty($allleads)) { ?>
                            <table id="leadtable" class="table dt-table scroll-responsive">
                                <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th class="bold">Name</th>
                                    <th class="bold">Contact Number</th>
                                    <th style="display:none" class="bold">Designation</th>
                                    <th class="bold">Company</th>
                                    <th style="display:none"  class="bold">Address</th>
                                    <th  style="display:none"  class="bold">Email Id</th>
                                    <th style="display:none"  class="bold">Data Source</th>
                                    <th class="bold">Calling Objective</th>
                                    <th class="bold">Calling Date</th>
                                    <th class="bold">Next Calling  Date</th>
                                    <th class="bold" style="min-width: 250px;">Remark</th>
                                    <th>Status</th>
                                    <th>WP List</th>
                                </tr>
                                </thead>
                                <tbody class="ajax-data">
                                <?php $i='1'; foreach ($allleads as $alllead) { ?>
                                    <tr id='lead_id_<?= $alllead->id; ?>'>
                                        <td><input type="hidden" value="<?= $i; ?>" name="srnumber" id="srnumber" />
                                            <?= $i; ?>
                                            <?php
                                            if (is_admin() || is_headtrm())
	                                        {
	                                       ?>
	                                          <a onclick="return confirm('Are you sure?')" href="<?= sprintf(base_url('admin/leads/delete_addedleads/%s'), $alllead->id); ?>"><i class="fa fa-trash text-danger"></i></a> 
	                                        <?php
	                                        }
	                                        ?>
                                            </td>
                                        <td>
                                            <?php if($alllead->name == "") { ?>
                                            <a href="#"
                                               onclick="edit_product_catagory(this,<?= $alllead->id; ?>);return false;"
                                               data-id="<?= $alllead->id; ?>" data-name="<?= $alllead->name; ?>"
                                               data-srnumber=" <?= $i; ?>"
                                               data-phonenumber="<?= $alllead->phonenumber; ?>"
                                               data-email="<?= $alllead->email; ?>"
                                               data-designation="<?= $alllead->designation; ?>"
                                               data-company="<?= $alllead->company; ?>"
                                               data-address="<?= $alllead->address; ?>"
                                               data-data_source="<?= $alllead->data_source; ?>"
                                               data-calling_objective="<?= $alllead->calling_objective; ?>"

                                               data-meetingtimefrom="<?= $alllead->meetingtimefrom; ?>"
                                               data-meetingtimeto="<?= $alllead->meetingtimeto; ?>"

                                               data-assigned="<?= $alllead->assigned; ?>"

                                               data-next_calling="<?= $alllead->next_calling; ?>"
                                               data-status="<?= $alllead->status; ?>"
                                               data-description="<?= $alllead->description; ?>"> <?= "No Name"; ?></a>
                                               <?php } else { ?>
                                            
                                            
                                            <a href="#"
                                               onclick="edit_product_catagory(this,<?= $alllead->id; ?>);return false;"
                                               data-id="<?= $alllead->id; ?>" data-name="<?= $alllead->name; ?>"
                                               data-srnumber=" <?= $i; ?>"
                                               data-phonenumber="<?= $alllead->phonenumber; ?>"
                                               data-email="<?= $alllead->email; ?>"
                                               data-designation="<?= $alllead->designation; ?>"
                                               data-company="<?= $alllead->company; ?>"
                                               data-address="<?= $alllead->address; ?>"
                                               data-data_source="<?= $alllead->data_source; ?>"
                                               data-calling_objective="<?= $alllead->calling_objective; ?>"

                                               data-meetingtimefrom="<?= $alllead->meetingtimefrom; ?>"
                                               data-meetingtimeto="<?= $alllead->meetingtimeto; ?>"

                                               data-assigned="<?= $alllead->assigned; ?>"

                                               data-next_calling="<?= $alllead->next_calling; ?>"
                                               data-status="<?= $alllead->status; ?>"
                                               data-description="<?= $alllead->description; ?>"> <?= $alllead->name; ?></a>
                                               <?php  } ?>
                                        </td>
                                        <td><?=  preg_replace('/[^a-zA-Z0-9_ -]/s','',$alllead->phonenumber); ?></td>
                                        <td style="display:none" >
                                            <?php  $designation = str_replace("(", "", $alllead->designation);
                                            $designation = str_replace(")", "", $designation);
                                            $designation = str_replace("-", "", $designation);
                                            echo $designation;
                                            
                                            ?>
                                           </td>
                                        <td><?= $alllead->company; ?></td>
                                        <td style="display:none" >
                                            <? $address = $alllead->address;
                                            $address = str_replace(".", "", $address);
                                        $address =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$address);
                                            echo $address;  ?>
                                            </td>
                                        <td style="display:none" ><?= $alllead->email; ?></td>
                                        <td style="display:none" ><?= $alllead->data_source; ?></td>
                                        <td><?= $alllead->calling_objective; ?></td>
                                        <td><? if ($alllead->lastcontact == null) {
                                                echo "";
                                            } else {
                                                echo date('d M, Y', strtotime($alllead->lastcontact));
                                            } ?></td>
                                            
                                          <td><? if ($alllead->next_calling == null || $alllead->next_calling == '0000-00-00 00:00:00' ) {
                                                echo "";
                                            } else {
                                                echo date('d M, Y H:i:s', strtotime($alllead->next_calling));
                                            } ?></td>     
                                        <td><? $description = $alllead->description;
                                            $description = str_replace(".", "", $description);
                                        $description =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$description);
                                            echo $description;  ?></td>
                                        <?php
                                        $colid = $alllead->status;
                                        $this->db->where('id', $colid);
                                        $result = $this->db->get('tblleadsstatus')->result();
                                        ?>
                                        <td style="width:160px !important">
                                         <!--   <select class="selectpicker" disabled="true" name="rate" id="lead_status_change"
                                                    data-lead-id="<?= $alllead->id; ?>">
                                                <?php foreach ($lstatus as $leadst) {
                                                    echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $alllead->status ? 'selected' : '', $leadst->name);
                                                } ?>
                                            </select>-->
                                            <?php echo $result[0]->name; ?>
                                        </td>
                                        <td>
                                            <?php if ($alllead->status == 1) { ?>
                                               
                                                <?php
                                                $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname');
                                                $this->db->where('lead_id', $alllead->id);
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
                                                
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php $i++; } ?>
                                </tbody>
                            </table>

                            <?php echo $links; ?>

                            <?php
                        } else {
                            echo NOLEADFOUND;
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php init_tail(); ?>
    <div class="modal fade product-catagory-modal" id="product_catagory" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel"></div>
</div>
<script>
    function edit_product_catagory(invoker, id, name, phonenumber) {

        var name = $(invoker).data('name');
        var status = $(invoker).data('status');
        var description = $(invoker).data('description');
        var phonenumber = $(invoker).data('phonenumber');
        var email = $(invoker).data('email');
        var designation = $(invoker).data('designation');
        var company = $(invoker).data('company');
        var address = $(invoker).data('address');
        var data_source = $(invoker).data('data_source');
        var calling_objective = $(invoker).data('calling_objective');
        var meetingtimefrom = $(invoker).data('meetingtimefrom');
        var meetingtimeto = $(invoker).data('meetingtimeto');
        var assigned = $(invoker).data('assigned');
        var next_calling = $(invoker).data('next_calling');
        var srnumber = $(invoker).data('srnumber');

        $('#additional').append(hidden_input('id', id));
        $('#product_catagory input[name="srnumber"]').val(srnumber);
        $('#product_catagory input[name="id"]').val(id);
        $('#product_catagory input[name="name"]').val(name);
        $('#product_catagory input[name="phonenumber"]').val(phonenumber);
        $('#product_catagory input[name="email"]').val(email);
        $('#product_catagory input[name="description"]').val(description);


        $('#product_catagory input[name="designation"]').val(designation);
        $('#product_catagory input[name="company"]').val(company);
        $('#product_catagory input[name="address"]').val(address);
        $('#product_catagory input[name="data_source"]').val(data_source);
        $('#product_catagory input[name="calling_objective"]').val(calling_objective);
        $('#product_catagory input[name="meetingtimefrom"]').val(meetingtimefrom);
        $('#product_catagory input[name="meetingtimeto"]').val(meetingtimeto);
        $('#product_catagory input[name="assigned"]').val(assigned);
        $('#product_catagory input[name="next_calling"]').val(next_calling);
        $('#product_catagory input[name="status"]').val(status);

        $.ajax({
            url: "<?php echo base_url(); ?>admin/leads/allleadremark",
            method: 'POST',
            data: {
                id: id,
                name: name,
                description: description,
                phonenumber: phonenumber,
                email: email,
                designation: designation,
                company: company,
                address: address,
                data_source: data_source,
                calling_objective: calling_objective,
                status: status,
                meetingtimefrom: meetingtimefrom,
                meetingtimeto: meetingtimeto,
                assigned: assigned,
                next_calling: next_calling,
                srnumber: srnumber
            },
            success: function (data)   // A function to be called if request succeeds
            {
                //  alert(data);
                $("#product_catagory").html(data);
                
                
                $('#product_catagory').modal('show');
                
                initautocompleteoff();
            }
        });
    }
</script>

<script>
 
    if(sessionStorage.getItem("statusfilter") != null)
    {
        var statusfilter = sessionStorage.getItem("statusfilter");
        $('#statusfilter').val(statusfilter);
    }
    if(sessionStorage.getItem("companyfilter") != null)
    {
        var companyfilter = sessionStorage.getItem("companyfilter");
        $('#companyfilter').val(companyfilter);
    }
    if(sessionStorage.getItem("data_sourcefilter") != null)
    {
        var data_sourcefilter = sessionStorage.getItem("data_sourcefilter");
        $('#data_sourcefilter').val(data_sourcefilter);
    }
    if(sessionStorage.getItem("calling_objectivefilter") != null)
    {
        var calling_objectivefilter = sessionStorage.getItem("calling_objectivefilter");
        $('#calling_objectivefilter').val(calling_objectivefilter);
    }
    if(sessionStorage.getItem("lastcontactfilter") != null)
    {
        var lastcontactfilter = sessionStorage.getItem("lastcontactfilter");
        $('#lastcontactfilter').val(lastcontactfilter);
    }
</script>

<script>
    $(document).on('change', '#lead_status_change', function () {
        
        if ($('#lead_status_change').val() == '1') {
            $('#mt1').show();
            $('#mt2').show();
            $('#nextcalling').hide();
        } else {
            $('#mt1').hide();
            $('#mt2').hide();
        }
        
    });
    $(document).on('change', '.custom_lead_filter', function () {
        const pageURL = window.location.href,
            urlcuren = pageURL.split( '/' ),
            lead_id_start = $("input[name='lead_id_start']").val(),
            status = $('#statusfilter').val(),
            company = $('#companyfilter').val(),
            data_source = $('#data_sourcefilter').val(),
            calling_objective = $('#calling_objectivefilter').val(),
            lastcontact = $('#lastcontactfilter').val();
        url = "<?= base_url('admin/leads/custom_lead_filter') ?>";
        
        
         if(status == "" && company == "" && data_source == "" && calling_objective == "")
        {
            
            location.reload();
        }
        
            if(urlcuren.length == 10)
            {
               lead_id_end = urlcuren[ urlcuren.length - 1 ]; 
            }
            else
            {
                lead_id_end = urlcuren[ urlcuren.length - 2 ];
            }
        
        
        //alert(urlcuren.length);
        
       
        $('.ajax-data').html("<center> <img src='http://www.itgeared.com/demo/images/loading.gif' style='width:100%'></center>");    
        $.get(url, {
                status: status,
                company: company,
                data_source: data_source,
                calling_objective: calling_objective,
                lastcontact: lastcontact,
                lead_id_start: lead_id_start,
                lead_id_end: lead_id_end
            },
            function (res) {
                $('.ajax-data').html(res);
            })
    });
    $(document).on('change', '#filter_by_company', function () {
        const pageURL = window.location.href, lead_id_end = pageURL.substr(pageURL.lastIndexOf('/') + 1),
            lead_id_start = $("input[name='lead_id_start']").val(), company = $(this).val(),
            url = "<?= base_url('admin/leads/filter_by_company') ?>";
        $.get(url, {company: company, lead_id_start: lead_id_start, lead_id_end: lead_id_end},
            function (res) {
                $('.ajax-data').html(res);
            });
    });
    $(document).on('change', '#filter_by_data_source', function () {
        const pageURL = window.location.href, lead_id_end = pageURL.substr(pageURL.lastIndexOf('/') + 1),
            lead_id_start = $("input[name='lead_id_start']").val(), data_source = $(this).val(),
            url = "<?= base_url('admin/leads/filter_by_data_source') ?>";
        $.get(url, {data_source: data_source, lead_id_start: lead_id_start, lead_id_end: lead_id_end}, function (res) {
            $('.ajax-data').html(res);
        })
    });
    $(document).on('change', '#filter_by_calling_objective', function () {
        var pageURL = window.location.href;
        var lead_id_end = pageURL.substr(pageURL.lastIndexOf('/') + 1);
        var lead_id_start = $("input[name='lead_id_start']").val();
        var calling_objective = $(this).val();
        var url = "<?= base_url('admin/leads/filter_by_calling_objective') ?>";
        $.get(url, {calling_objective: calling_objective, lead_id_start: lead_id_start, lead_id_end: lead_id_end},
            function (res) {
                $('.ajax-data').html(res);
            })
    });
    $(document).on('change', '#filter_by_lastcontact', function () {

        var pageURL = window.location.href;
        var lead_id_end = pageURL.substr(pageURL.lastIndexOf('/') + 1);
        var lead_id_start = $("input[name='lead_id_start']").val();
        var lastcontact = $(this).val();
        var url = "<?= base_url('admin/leads/filter_by_lastcontact') ?>";
        $.get(url, {lastcontact: lastcontact, lead_id_start: lead_id_start, lead_id_end: lead_id_end},
            function (res) {

                $('.ajax-data').html(res);

            })
    });
    $(document).on('change', '.rmList', function () {
        const staff_id = $(this).val(),
            lead_id = $(this).data('lead_id'),
            url = "<?= base_url('admin/leads/reassignlead') ?>";
        $.get(url, {lead_id: lead_id, staff_id: staff_id}, function (res) {
            //window.location.reload();
            {
                //alert("WP Updated Successfully");
            }
        })
    })
</script>
 <script>
                                function jumptopagefunc()
                                {
                                    var pagenumber =  $("input[name='jumtopage']").val();
                                    var acpagenumber = pagenumber - 1;
                                    var pagecons = acpagenumber*100;
                                    var pageURL = window.location.href;
                                    var urlcuren = pageURL.split( '/' );
                                    if(urlcuren.length == 10)
                                        {
                                           
                                     desiredpage = pageURL+('/')+pagecons;
                                        }
                                        else
                                        {
                                          
                                    desiredpage = pageURL.replace(/\/[^\/]*$/, '/')+pagecons;
                                        }
                                    location.replace(desiredpage);    
                                   // alert(desiredpage);
                                    
                                   
                                }
                            </script>

<script>
    $(function () {
        $('#leadtable_paginate').hide();
    });

</script>

 
 <script>
$(document).ready(function(){
  $("#unsetallfilter").click(function(){
      
       
        
  sessionStorage.removeItem('statusfilter');
  sessionStorage.removeItem('companyfilter');
  sessionStorage.removeItem('data_sourcefilter');
  sessionStorage.removeItem('calling_objectivefilter');
  sessionStorage.removeItem('lastcontactfilter');
  
      window.location.reload();

  });
});
    
</script>
</body>
</html>