<div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Update Lead Status </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <form action="" id="remarkform">
                    <div class="col-md-12">
                        <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                       <?php echo render_input('id', '', array('readonly' => 'readonly'), 'hidden'); ?> 
                       
                       <?php echo render_input('srnumber', '', array('readonly' => 'readonly'), 'hidden'); ?>
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
                        <div class="col-md-6">
                           <?php echo render_input('data_source', 'lead_data_source', array('readonly' => 'readonly')); ?>
                        </div>
                        <div class="col-md-6">
                           <?php echo render_input('calling_objective', 'lead_calling_objective', array('readonly' => 'readonly')); ?>
                        </div>
                        
                        
                        
                        <div class="col-md-6">
                            <label for="email" class="control-label">Status</label>
                            <select onchange="origin()" name="status" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="lead_status_change"
                                                   data-lead-id="<?= $allleadremark[0]->lead_id; ?>">
                                              <?php foreach ($lstatus as $leadst) {
                                                 echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $status ? 'selected' : '', $leadst->name);
                                              } ?>
                                           </select>
                        </div>
                        <input type="hidden" name="assigned" id="assigned"  value="<?= $assigned; ?>">
                        
                       
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
                                        
                                               <select name="reassignlead" id="wpformeet" data-none-selected-text="choose WP"
                                                        data-live-search="true" class="form-control rmList"
                                                        data-lead_id="<?= $lead_id; ?>">

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
                        
                        <div class="col-md-6" id="next_callingdiv">
                          <!-- <?php  echo render_input('next_calling', 'next_calling', array('readonly' => 'readonly')); ?>-->
                           <div class="form-group" app-field-wrapper="next_calling"><label for="next_calling" class="control-label">Next Calling Date</label><input type="text" id="next_calling" name="next_calling" class="form-control datetimepicker" value="<?php if($next_calling == null ){ echo ""; } else { echo $next_calling; } ?>"></div>
                           
                        </div>
                       
                        </br>
                       <?php
                          if ( !empty($allleadremark) ) {
                             foreach ($allleadremark as $allremark) {
                                
                                $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname  ');
                                                $this->db->where('id', $lead_id);
                                                $this->db->join('tblstaff', 'tblleads.assigned=tblstaff.staffid');
                                                $result = $this->db->get('tblleads')->result();
                                ?>
                                 <div class="col-md-11">
                                     <input type="text" class="form-control" value="<?php echo $allremark->remark; ?>"
                                            readonly/><p class="pull-right"> On <?php echo date('j M, Y', strtotime($allremark->created_on)) ?> By <?php echo $result[0]->wpname; ?></p>
                                 </div>
                                 
                                 <div class="col-md-1">
                                     <a href="https://bfccapital.com/crm/admin/leads/deleteremarkid/<?= $allremark->id; ?>"
                                        class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                 </div>
                             <?php }
                          } ?>
                        <div class="clearfix"></div>
                        <div class="form-group" app-field-wrapper="description"><label for="description" class="control-label">Remark</label><input type="text" id="description" name="description" class="form-control" value=""></div>
                        <div class="form-group" app-field-wrapper="Reminder"><label for="Reminder" class="control-label">Set Reminder</label><input type="checkbox" id="reminder" name="reminder" class="form-control" value=""></div>
                       <!--<?php echo render_input('description', 'custom_lead_remark'); ?>-->
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
                var srnumber = $("#srnumber").val();


                var phonenumber = $("input[name='phonenumber']").val();
                var email = $("input[name='email']").val();
                var designation = $("input[name='designation']").val();
                var company = $("input[name='company']").val();
                var address = $("input[name='address']").val();
                var data_source = $("input[name='data_source']").val();
                var calling_objective = $("input[name='calling_objective']").val();

                var description = $("input[name='description']").val();
                var meetingtimefrom = $('#meetingtimefrom').val();
                var meetingtimeto = $('#meetingtimeto').val();
                var added_by = $("input[name='added_by']").val();
                var status = $('select[name="status"]').val();
                var nextcalling = $('#next_calling').val();
                var wplead = $("input[name='wplead']").val();
                var reassignlead = $('select[name="reassignlead"]').val();
                var assigned = $("input[name='assigned']").val();
                var    reminder = $('#reminder').val();
                
                    
                    
                    
                
               //alert(srnumber);


                $.ajax({
                    url: '<?= base_url();?>admin/leads/update_custom_lead_remark/',
                    
                    type: 'GET',
                    data: {
                        id: id,
                        name: name,
                        phonenumber: phonenumber,
                        email: email,
                        designation: designation,
                        company: company,
                        address: address,
                        data_source: data_source,
                        calling_objective: calling_objective,
                        description: description,
                        meetingtimefrom: meetingtimefrom,
                        meetingtimeto: meetingtimeto,
                        status: status,
                        nextcalling: nextcalling,
                        wplead: wplead,
                        reassignlead: reassignlead,
                        reminder:reminder,
                        assigned:assigned,
                        added_by: added_by,
                        srnumber: srnumber
                    },
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                        $('#product_catagory').modal('hide');
                       //location.reload();
                       
                       $.ajax({
                            url: "<?php echo base_url(); ?>admin/leads/get_row_lead_data",
                            method: 'POST',
                            data: {
                                id: id,
                                srnumber:srnumber
                            },
                             success: function (res) {
                                $('#lead_id_'+id).replaceWith(res);
                                alert_float('success', 'Lead Updated Successfully');
                        //$('#product_catagory').modal('hide');
                            }
                        });
                       // alert(id);
                        
                    }
                });
            });
        </script>
        

        <script>
$( "#meetingtimefrom" ).addClass( "datetimepicker" );
$( "#meetingtimeto" ).addClass( "datetimepicker" );
$( "#next_calling" ).addClass( "datetimepicker" );

init_datepicker();

</script>

<script>
    $(function () {
    var status = $('select[name="status"]').val();
    
    switch (status)
    {
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
        
        default :
        
            $('#meetingtimefromdiv').hide();
            $('#meetingtimetodiv').hide();
            $('#wpdiv').hide();
            $('#next_callingdiv').show();
        
    }
        

        
    });
    </script>

<script>
$(function () {
    $(document).ready(function(){
    $("select.statuschangelead").change(function(){
        var stat = $(this).children("option:selected").val();
       // alert(stat);
        switch (stat)
    {
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
        
        default :
        
            $('#meetingtimefromdiv').hide();
            $('#meetingtimetodiv').hide();
            $('#wpdiv').hide();
            $('#next_callingdiv').show();
        
    }
    });

        $("input#meetingtimefrom").change(function(){
          var meetingtimefrom = $('#meetingtimefrom').val();
          //alert(meetingtimefrom);
		 
          if(meetingtimefrom != '')
          {
           $.ajax({
            url:"<?php echo base_url(); ?>admin/leads/meetingtimewp",
            method:"POST",
            data:{meetingtimefrom:meetingtimefrom},
            success:function(data)
            {
			   $('#wpformeet').html(data);
             
            }
           });
          }
		  else
		  {
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
      //alert($('#reminder').val());
    } else
      $(this).val('0');
     // alert($('#reminder').val());
  });
});
</script>
 <script>
     $(document).ready(function(){ 
    $("form").attr("autocomplete", "no"); 
    
    $("input").attr("autocomplete", "no"); 
});

 </script>
    </div>
</div>
</div>