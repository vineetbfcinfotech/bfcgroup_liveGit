<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
              
            </div > 
        </div>
        <div class="row">
          
    </div>
        <div class="row">
              <div class="col-md-12">
                
        </div>
            <div class="col-md-12">
                
                    <div class="panel-body" style="overflow: auto;">
                            
                </div>
           
                <div class="panel_s" >
                    <div class="panel-body" style="overflow: auto;">
                        <div class="_buttons">
                                <div class="row">
                                    <div class="col-md-4">
                                      <h3>
            
                                            Add Leads
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                   
                                </div>
                                <form action="<?php echo base_url() ?>admin/leads/addLeads" methode="post" id="remarkform" autocomplete="off">
                                    <div class="col-md-12">
                                        <div class="row">
                                           <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Lead Creation Date</label>
                                                <input type="text" id="leadCreationDate" name="leadCreationDate" class="form-control" value="<?php echo date("Y-m-d");?>" disabled>
                                               <!-- <input type="text" id="leadCreationDate" name="leadCreationDate" class="form-control datetimepicker" value="" autocomplete="no">--></div>
                                            </div>
                                            <div class="col-md-3" id="otherphonelabel">
                                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                                    <label for="otherphonenumber" class="control-label">Ad Id</label>
                                                    <input type="text" id="adId" name="adId" class="form-control" value="" autocomplete="no">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Ad Name</label>
                                                    <input type="text" id="adName" name="adName" class="form-control" value="">
                                               </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                           <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Book Language</label>
                                                <select name="bookLanguage" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="bookLanguage"
                                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>">
                                                     <option value="hindi" <?php if($publishedEarlier == 'hindi'){ echo "selected";}?>>Hindi</option>
                                                     <option value="english" <?php if($publishedEarlier == 'english'){ echo "selected";}?>>English</option>
                                                     <option value="others" <?php if($publishedEarlier == 'others'){ echo "selected";}?>>Others</option>
                                                   </select> 
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="otherphonelabel">
                                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                                    <label for="otherphonenumber" class="control-label"> Manuscript Status</label>
                                                    <select name="manuscriptStatus" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="manuscriptStatus"
                                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>">
                                                        <option value=""></option>
                                                     <option value="completed" <?php if($publishedEarlier == 'completed'){ echo "selected";}?>>Completed</option>
                                                     <option value="in_process" <?php if($publishedEarlier == 'in_process'){ echo "selected";}?>>In Process</option>
                                                   </select> 
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Published Earlier</label>
                                                     <select name="publishedEarlier" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="publishedEarlier"
                                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                                                                   <option value=""></option>
                                                     <option value="yes" <?php if($publishedEarlier == 'yes'){ echo "selected";}?>>Yes</option>
                                                     <option value="no" <?php if($publishedEarlier == 'no'){ echo "selected";}?>>No</option>
                                                   </select>  
                                               </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Author Name</label>
                                                <input type="text" id="name" name="name" class="form-control" value="<?php echo $name;?>" autocomplete="no" <?php// if($name !=''){ echo "disabled";}?>></div>
                                            </div>
                                            <div class="col-md-3" id="otherphonelabel">
                                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                                    <label for="otherphonenumber" class="control-label"> Contact Number</label>
                                                    <input type="text" id="otherphonenumber" name="otherphonenumber" class="form-control" value="<?php echo $otherphonenumber;?>" autocomplete="no">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Email</label>
                                                    <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>" <?php //if($email !=''){ echo "disabled";}?>>
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                        <button type="submit" name="submit" class="btn btn-info category-save-btn">Add Lead</button>  
                                    </div>   
                                </form>

                        <div class="clearfix"></div>
                         <hr class="hr-panel-heading"/> <hr class="hr-panel-heading"/>
                        <div class="row">
                                    <div class="col-md-4">
                                      <h3>
            
                                              Added Leads Details
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                   
                                </div>
                      
						<?php if (is_admin()) { ?>
						<div class="clearfix"></div>
                       
						<?php } ?>
                        <div >

                           

                        </div>
                    </div>
                            <div class="_buttons">
                                <div class="row">
                                    <div class="col-md-4">
                                      <h3>
                                            
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                   
                                </div>
                                
                               
                               


                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<?php if (is_admin()) { ?>
						
						 --><div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<?php } ?>
                        <div >

                            <?php if (!empty($assignedleads) > 0) { ?>
                                <table class="table dt-table scroll-responsive example11">
                                    <thead>
                                     <tr>
									
                                    <th><?php echo _l('id'); ?></th>
                                    <th class="bold">Name</th>
                                    <th class="bold">Contact Number</th>
                                    
                                    <th  style="display:none" class="bold">Email Id</th>
                                    <th style="display:none" class="bold">Data Source</th>
                                    <th style="display:none" class="bold">Adset Name</th>
                                    <th style="display:none" class="bold">Adset Name</th>
                                    <th class="bold">Ad Name</th>
                                   
                                    <th class="bold">Manuscript Status</th>
                                    
                                    <th class="bold">Email Id</th>
                                    <th class="bold">Language</th>
                                    
                                    
                                    <th class="bold">Created Date</th>
                                    
                                </tr>
                                    
                                    </thead>
                                    <tbody class="ajax-data">
                                    <?php $count = 1; foreach ($assignedleads as $alllead) { ?>
									
                                       <tr id='lead_id_<?= $alllead->id; ?>'>
									  
                                        <td id="srNumberget">
                                            <?= $count; ?>
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
                                            <a href="#"
                                               onclick="edit_product_catagory(this,<?= $alllead->id; ?>,'<?= $alllead->lead_author_name; ?>','<?= $alllead->lead_publishedearlier; ?>','1','<?= $alllead->adset_name; ?>');return false;"
                                               data-id="<?= $alllead->id; ?>"
                                                
                                               data-name="<?= $alllead->lead_author_name; ?>"
                                               data-phone_number="<?= $alllead->phonenumber; ?>"
											   data-srnumber="<?= $count; ?>"
                                               data-booktitle="<?= $alllead->booktitle; ?>"
                                               data-book_format="<?= $alllead->book_format; ?>"
                                               data-otherphonenumber="<?= $alllead->otherphonenumber; ?>"
                                               data-email="<?= $alllead->email; ?>"
                                               data-designation="<?= $alllead->designation; ?>"
                                               data-company="<?= $alllead->company; ?>"
                                               data-address="<?= $alllead->address; ?>"
                                               data-data_source="<?= $alllead->adset_name; ?>"
                                               data-publishedEarlier="<?= $alllead->lead_publishedearlier; ?>"
                                               data-calling_objective="<?= $alllead->lead_adname; ?>"
                                               data-manuscript_status="<?= $alllead->lead_author_msstatus; ?>"
                                               data-user_language="<?= $alllead->lead_author_mslanguage; ?>"
                                               data-ad_id="<?= $alllead->lead_ad_id; ?>"
                                               data-created_time="<?= $alllead->lead_created_date; ?>"

                                              

                                               data-assigned="<?= $alllead->assigned; ?>"

                                               data-next_calling="<?= $alllead->next_calling; ?>"
                                             
                                               data-status="<?= $alllead->status; ?>"
                                               data-description="<?= $alllead->description; ?>"> <?= $alllead->lead_author_name; ?></a>
                                        </td>
                                        <td>
                                        <?php 
                                            $var = explode("p:+91",$alllead->phonenumber);
                                            if($var[0] != '' || $var[0] != null ){
                                                echo $var[0];
                                            }else{
                                              echo $var[1];  
                                            } 
                                        ?>
                                        </td>
                                        <td style="display:none" >
                                            <?php  $designation = str_replace("(", "", $alllead->designation);
                                            $designation = str_replace(")", "", $designation);
                                            $designation = str_replace("-", "", $designation);
                                            echo $designation;
                                            
                                            ?>
                                           </td>
                                           <td style="display:none;" >
                                               <?= $alllead->otherphonenumber; ?>
                                               </td>
                                                <td style="display:none;" >
                                               <?= $alllead->booktitle; ?>
                                               </td>
                                               <td style="display:none;" >
                                               <?= $alllead->book_format; ?>
                                               </td>
                                               
                                               
                                               
                                        <td><?= $alllead->lead_adname; ?></td>
                                        <td>
                                            <? $address = $alllead->lead_author_msstatus;
                                            $address = str_replace(".", "", $address);
                                        $address =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$address);
                                            echo $address;  ?>
                                            </td>
                                        
                                        <td ><?= $alllead->email; ?></td>
                                        <td><?= $alllead->lead_author_mslanguage; ?></td>
                                       
                                       
                                            <?php 
                                            $string = $alllead->description;
                                            $explode_str = explode(" ",$string);
                                            $substring = substr($string,0,10);
                                            ?>
                                      
                              
                                        <?php
                                        $colid = $alllead->status;
                                        $this->db->where('id', $colid);
                                        $result = $this->db->get('tblleadsstatus')->result();
                                        ?>
                                       
                                        
                                   
	                                        
	                                     
	                                  
	                                       
	                                       <td ><?= $alllead->lead_created_date; ?></td>
                                    </tr>
                                    <?php $count++; } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <p class="no-margin"><?php echo "No Lead Found" ?></p>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <?php init_tail();
    ?>
   <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
         <form action="<?php echo base_url() ?>admin/leads/addRemarks" method="POST">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Remarks here</h4>
      </div>
      <div class="modal-body">
          <textarea cols="50" rows="10" id="rem" name="remarksss"></textarea>
          
       <input type="hidden" name="remarks_id" id="remarks_id" class="remarks_ids" value=""/>
      </div>
      <div class="modal-footer">
          <a href="#" class="btn btn-default" id="submit" name="submit">add</a>
        
        <button type="button" class="btn btn-default" data-dismiss="modal" id="modelClose">Close</button>
      </div>
      <span id="alert-msg"></span>
      </form>
    </div>

  </div>
</div>

    <!-- Product category Data Add/Edit-->
    <div class="modal fade product-catagory-modal" id="product_catagory" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">


    </div>
</div>
</div>

<script type="text/javascript">
function searchFunction(leadvalue){
   // alert(leadvalue);
    const pageURL = window.location.href,
        url = "<?= base_url('admin/leads/Searchcustom_lead_filter_added') ?>";
        
    $.get(url, {leadvalue: leadvalue},
        function (res) {
                $('.ajax-data').html(res);
        });
    
}
$("#submit").click(function(){  
    //alert();
    var form_data = {
        rem: $('#rem').val(),
        remarks_id: $('#remarks_id').val(),
    };
        /*var rem = $("#rem").val();  
        var remarks_id = $("#remarks_id").val(); */
        
        
        $.ajax({
        url: "<?php echo site_url('admin/leads/addRemarks'); ?>",
        type: 'POST',
         data: form_data,
        //data: form_data,
        success: function(msg) {
            //alert(msg);
            if (msg == 'YES')
                $('#alert-msg').html('<div class="alert alert-success text-center">Remarks added successfully!</div>');
                
                
            else if (msg == 'NO')
                $('#alert-msg').html('<div class="alert alert-danger text-center">Error in adding your remarks! Please try again later.</div>');
               
            else
                $('#alert-msg').html('<div class="alert alert-danger">' + msg + '</div>');
            setTimeout(function(){// wait for 5 secs(2)
                   //location.reload(); // then reload the page.(3)
                    window.location = 'http://bfcpublications.com/chorus/admin/leads/viewassignedleads';
            }, 5000);    
               
        }
    });
    return false;
        

</script>
<script>
$('document').ready(function(){
 
    $("button").click(function() {
    var fired_button = $(this).val();

    $('#remarks_id').val(fired_button);
    var remarks = $(this).attr("data-remarks");
    
    $('#rem').val(remarks);
    
});

$('#modelClose').click(function(){
   
});




})
   function edit_product_catagory(invoker, id, name,publishing, phonenumber,adname) {
       // print_r(invoker);
       //alert("test");
       
        var status = $(invoker).data('status');
        var description = $(invoker).data('description');
        var phonenumber = $(invoker).data('phone_number');
        var booktitle = $(invoker).data('booktitle');
        var book_format = $(invoker).data('book_format');
        var otherphonenumber = $(invoker).data('otherphonenumber');
        var srnumber = $(invoker).data('srnumber');
      
        var designation = $(invoker).data('designation');
        var company = $(invoker).data('company');
        var address = $(invoker).data('address');
        var data_source = $(invoker).data('data_source');
        var calling_objective = $(invoker).data('calling_objective');
        var meetingtimefrom = $(invoker).data('meetingtimefrom');
        var meetingtimeto = $(invoker).data('meetingtimeto');
        var assigned = $(invoker).data('assigned');
        var next_calling = $(invoker).data('next_calling');
        
        var name = $(invoker).data('name');
        var email = $(invoker).data('email');
        var publishedEarlier = $(invoker).data('publishedEarlier');
        var manuscript_status = $(invoker).data('manuscript_status');
        var user_language = $(invoker).data('user_language');
        var ad_id = $(invoker).data('ad_id');
        var created_time = $(invoker).data('created_time');
        var adname = $(invoker).data('adname');
        
        
        
        console.log("name:"+name);
        console.log("phone no:"+phonenumber);
        console.log("email:"+email);
        console.log("manuscript_status:"+manuscript_status);
       
        console.log("ad name:"+calling_objective);
       
        console.log("publishi:"+publishing);
        console.log("book language:"+user_language);
        console.log("ad id:"+ad_id);
        console.log("created time:"+created_time);
        

        $('#additional').append(hidden_input('id', id));
        $('#product_catagory input[name="id"]').val(id);
        $('#product_catagory input[name="name"]').val(name);
        $('#product_catagory input[name="phonenumber"]').val(phonenumber);
        $('#product_catagory input[name="calling_objective"]').val(calling_objective);
        $('#product_catagory input[name="booktitle"]').val(booktitle);
        $('#product_catagory input[name="book_format"]').val(book_format);
        $('#product_catagory input[name="otherphonenumber"]').val(otherphonenumber);
        $('#product_catagory input[name="email"]').val(email);
        $('#product_catagory input[name="manuscript_status"]').val(manuscript_status);
        $('#product_catagory input[name="leadCreationDate"]').val(leadCreationDate);
        $('#product_catagory input[name="user_language"]').val(user_language);
        $('#product_catagory input[name="ad_id"]').val(ad_id);
        $('#product_catagory input[name="created_time"]').val(created_time);
       


       
        $('#product_catagory input[name="data_source"]').val(data_source);
        $('#product_catagory input[name="calling_objective"]').val(calling_objective);
        $('#product_catagory input[name="meetingtimefrom"]').val(meetingtimefrom);
        $('#product_catagory input[name="meetingtimeto"]').val(meetingtimeto);
        $('#product_catagory input[name="assigned"]').val(assigned);
        $('#product_catagory input[name="srnumber"]').val(srnumber);
        $('#product_catagory input[name="next_calling"]').val(next_calling);
        $('#product_catagory input[name="publishedEarlier"]').val(publishing);
        $('#product_catagory input[name="adname"]').val(adname);
        $('#product_catagory input[name="status"]').val(status);

        $.ajax({
          
           url: "<?php echo base_url(); ?>admin/leads/allleadremark_after",
            method: 'POST',
            data: {
                id: id,
                name: name,
                publishedEarlier:publishing,
                description: description,
                phonenumber: phonenumber,
                otherphonenumber:otherphonenumber,
                booktitle:booktitle,
                book_format:book_format,
                srnumber:srnumber,
                email: email,
                manuscript_status: manuscript_status,
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
                adname:calling_objective,
                user_language:user_language,
                ad_id:ad_id,
                created_time:created_time,
                
            },
            success: function (data) // A function to be called if request succeeds
            {
                  //console.log(data);
                $("#product_catagory").html(data);
           
                $("#descriptions").removeAttr( "autocomplete" );
               // $('#autocomplete').autocomplete().disable();
                $("form").attr('autocomplete', 'off'); 
                $('#product_catagory').modal('show');
            }
        });
    }
</script>
<script>
   
    $(document).on('change', '.custom_lead_filter', function () {
		$('.all_select').prop('checked', false);
        const pageURL = window.location.href,
            urlcuren = pageURL.split( '/' ),
            status = $('#statusfilter').val(),
            company = $('#companyfilter').val(),
            data_source = $('#data_sourcefilter').val(),
            language = $('#userLanguagefilter').val(),
            publishedearlier = $('#publishedearlier').val(),
            manuscript_status = $('#menuscriptstatus').val(),
            createdDate = $('#createdDate').val(),
            adName = $('#adName').val(),
            
            source = $('#lead_sourcefilter').val(),
            lastcontact = $('#datefilter').val();
            url = "<?= base_url('admin/leads/custom_lead_filter_added') ?>";
            leadpagename = urlcuren[ urlcuren.length - 1 ];
            user_language = $('#userLanguagefilter').val();
           
            
        $.get(url, {status: status,leadpagename:leadpagename,user_language:user_language,manuscript_status:manuscript_status,createdDate:createdDate,adName:adName,publishedearlier:publishedearlier},
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
        })
    })
</script>
<script>
function confirmdelete() {
  
  
  var retVal = confirm("Are You Sure Want to delete ");
               if( retVal == true ) {
                  
                  return true;
               } else {
                  
                  return false;
               }
  
  
}
</script>


<script>
$(document).ready(function() {

	$('.select_row').click(function() {
		if($('.all_select').is(":checked")) {
			/* if($('.selected_row').is(":checked")) {
				$('.selected_row').prop('checked', false);
			}else{
				$('.selected_row').prop('checked', true);
			} */
			$('.selected_row').prop('checked', true);
		}else{
			$('.selected_row').prop('checked', false);
		}
	});
	
	$(".delete_selected").click(function(){
		if(confirm('Are you sure you want to delete this?')){
			var selectdId = [];
			$.each($("input[name='deleted_value']:checked"), function(){            
				selectdId.push($(this).val());
			});
			$.ajax({
			  type: "POST",
			  url: "<?php echo admin_url('leads/deletedSelected'); ?>",
			  data: {selectdId: selectdId},
			  success: function (data) {
				if(data == 1){
					alert_float('success', 'Leads Deleted Successfully..');
					//table.ajax.reload();
					setTimeout(function() {
						location.reload();
					}, 2000);
				}else{
					alert_float('warning', 'Please Select Leads..');
				}
			  }
			  
			});
			$.each(selectdId, function(key, val){
				$('#lead_id_' + val).remove();
			});
		};
	
    });
});
 
</script>
</body>
</html>