<?php init_head_new(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
          <div class="col-md-12">
              </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s" >
                    <div class="panel-body" style="overflow: auto;">
                            <div class="_buttons">
                                <div class="row">
                                    <div class="col-md-4">
                                      <h3>
                                            <button class="btn btn-alert"
                                                    onclick="window.location='<?= base_url(); ?>admin/leads/refferleads';"> Back
                                            </button> 
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                   
                                </div>
                               

                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<?php if (is_admin()) { ?>
						<div class="clearfix"></div>
                      
						<?php } ?>
					
                        <div class="mytable">

                            <?php if (!empty($leadsdetails) > 0) { ?>
                                 <?php //print_r($leadsdetails);
                                 //echo $bookformat = $leadsdetails['book_format'];
                                 //echo "test".$leadsdetails[0]['ad_name'];
                                 
                                 
                                 ?>
                                  <form action="<?php echo base_url() ?>admin/leads/updateRefLeads" id="remarkform" autocomplete="off">
                    <div class="col-md-12">
                        <div class="row">
                        <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                        <input type="hidden" name="lead_id" value="<?php echo $leadsdetails[0]->id; ?>">
                       <?php echo render_input('id', '', array('readonly' => 'readonly'), 'hidden'); ?> 
                       
                       <?php echo render_input('srnumber', '', array('readonly' => 'readonly'), 'hidden'); ?>
                        <div class="col-md-3">
                           <?php //echo render_input('name', 'custom_lead_name', array('readonly' => 'readonly')); ?>
                           <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Author Name</label>
                           <input type="text" id="name" name="name" class="form-control" value="<?php echo $leadsdetails[0]->lead_author_name; ?>" autocomplete="no" <?php// if($name !=''){ echo "disabled";}?>></div>
                        </div>
                        
                        <div class="col-md-3">
                          <div class="form-group" app-field-wrapper="phonenumber">
                                 <label for="phonenumber" class="control-label">Phone</label>
                                 <input type="text" id="phonenumber" name="phonenumber" class="form-control" value="<?php echo $leadsdetails[0]->phonenumber; ?>" autocomplete="no">
                            </div>
                        </div>
                         <div class="col-md-3" id="otherphonelabel">
                            <div class="form-group" app-field-wrapper="otherphonenumber">
                                 <label for="otherphonenumber" class="control-label">Other Phone</label>
                                 <input type="text" id="otherphonenumber" name="otherphonenumber" class="form-control" value="<?php echo $leadsdetails[0]->otherphonenumber; ?>" autocomplete="no">
                            </div>
                        </div>
                         <div class="col-md-3">
                                               <div class="form-group" app-field-wrapper="email">
                                                    <label for="email" class="control-label">Category</label>
                                                    
                                                      <select onchange="origin()" name="category" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="category"
                                                   data-lead-id="<?= $allleadremark[0]->lead_id; ?>">
                                                        <option value="" selected>Select Category</option>
                                              <?php foreach ($lstatus as $leadst) {
                                                 echo sprintf('<option value="%s" %s>%s</option>', $leadst->id, $leadst->id == $leadsdetails[0]->status ? 'selected' : '', $leadst->name);
                                              } ?>
                                           </select>
                                               </div>
                                            </div> 
                        </div>
                        
                        <div class="row">
                            <!--<div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                <a href="#" onclick="showphone();" class="btn btn-primary">Add New</a>
                                </div>
                            </div>-->
                             
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                               <?php //echo render_input('email', 'leads_dt_email', array('readonly' => 'readonly')); ?>
                               <div class="form-group" app-field-wrapper="email">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="text" id="email" name="email" class="form-control" value="<?php echo $leadsdetails[0]->email; ?>" <?php //if($email !=''){ echo "disabled";}?>>
                               </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" app-field-wrapper="designation">
                                     <label for="booktitle" class="control-label">Book Title</label>
                                     <input type="text" id="booktitle" name="booktitle" class="form-control" autocomplete="no" value="<?php echo $leadsdetails[0]->lead_booktitle; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                               <?php //echo render_input('calling_objective', 'lead_calling_objective', array('readonly' => 'readonly')); ?>
                               <div class="form-group" app-field-wrapper="email">
                                    <label for="email" class="control-label">Ad Name</label>
                                    <input type="text" id="adName" name="adName" class="form-control" value="<?php echo $leadsdetails[0]->lead_adname; ?>" <?php //if($email !=''){ echo "disabled";}?>>
                               </div>
                            </div>
                            <div class="col-md-3" id="book_formatdiv">
                             
                               <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Book Format</label>
                               <select name="book_format" class="form-control" data-width="100%"  data-live-search="true"  id="book_format">
                                        <option value=""></option>
                                        <option value="Ebook" <?php if($leadsdetails[0]->lead_bookformat == 'Ebook'){ echo "selected";}?>>Ebook</option>
                                        <option value="Paperback" <?php if($leadsdetails[0]->lead_bookformat == 'Paperback'){ echo "selected";}?>>Paperback</option>
                                </select> 
                               </div>
                               
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-3" id="book_formatdiv1">
                             
                               <div class="form-group" app-field-wrapper="book_format"><label for="book_format" class="control-label">Manuscript Status</label>
                               <select name="manuscriptStatus" class="form-control" data-width="100%"  data-live-search="true"  id="manuscriptStatus">
                                        <option value=""></option>
                                        <option value="completed" <?php if($leadsdetails[0]->lead_author_msstatus == 'completed'){ echo "selected";}?>>Completed</option>
                                        <option value="in_process" <?php if($leadsdetails[0]->lead_author_msstatus == 'in_process'){ echo "selected";}?>>In process</option>
                                </select> 
                               </div>
                               
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" app-field-wrapper="designation">
                                     <label for="booktitle" class="control-label">Book Language</label>
                                      <select name="bookLanguage" class="form-control" data-width="100%"  data-live-search="true"  id="bookLanguage">
                                        <option value=""></option>
                                        <option value="hindi" <?php if($leadsdetails[0]->lead_author_mslanguage == 'hindi'){ echo "selected";}?>>Hindi</option>
                                        <option value="english" <?php if($leadsdetails[0]->lead_author_mslanguage == 'english'){ echo "selected";}?>>English</option>
                                        <option value="others" <?php if($leadsdetails[0]->lead_author_mslanguage == 'others'){ echo "selected";}?>>Others</option>
                                </select> 
                                </div>
                            </div>
                            <div class="col-md-3">
                            <div class="form-group" app-field-wrapper="publishedEarlier">
                                 <label for="next_PublishedEarlier" class="control-label">Published Earlier</label>
                                 <select name="publishedEarlier" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="publishedEarlier"
                                                   data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                                     <option value=""></option>
                                     <option value="yes" <?php if($leadsdetails[0]->lead_publishedearlier == 'yes'){ echo "selected";}?>>Yes</option>
                                     <option value="no" <?php if($leadsdetails[0]->lead_publishedearlier == 'no'){ echo "selected";}?>>No</option>
                                   </select>  
                                 <!--<input type="text" id="publishedEarlier" name="next_PublishedEarlier" class="form-control" autocomplete="no" value="<?php echo $publishedEarlier;?>">-->
                            </div>
                        </div>
                         <div class="col-md-3" id="next_callingdiv">
                          <!-- <?php // echo render_input('next_calling', 'next_calling', array('readonly' => 'readonly')); ?>-->
                           <div class="form-group" app-field-wrapper="next_calling"><label for="next_calling" class="control-label">Next Calling Date</label>
                           <input type="text" id="next_calling" name="next_calling" class="form-control datetimepicker" value="<?php    if($leadsdetails[0]->next_calling == null ){ echo ""; } elseif($leadsdetails[0]->next_calling == '0000-00-00 00:00:00'){ echo "";  }else { echo $leadsdetails[0]->next_calling; } ?>"></div>
                           
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="description">
                                <label for="description" class="control-label">Remark</label>
                                <input type="text" id="descriptions" name="description" class="form-control" value="">
                            </div>
                            </div>
                            <?php if ( !empty($allleadremark) ) {
                             foreach ($allleadremark as $allremark) {
                                $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  wpname  ');
                                $this->db->where('id', $leadsdetails[0]->id);
                                $this->db->join('tblstaff', 'tblleads.assigned=tblstaff.staffid');
                                $result = $this->db->get('tblleads')->result();
                            ?>
                                <div class="col-md-11">
                                <textarea class="form-control" rows="10" cols="10" readonly><?php echo $allremark->remark; ?></textarea>
                                
                                <p class="pull-right"> On <?php echo date('j M, Y', strtotime($allremark->created_on)) ?> <!--By <?php //echo $result[0]->wpname; ?>--></p>
                                </div>
                                
                                <div class="col-md-1">
                                
                                </div>
                             <?php }
                            } ?>
                            
                        </div>
                        
                        <div class="row">
                        
                        <input type="hidden" name="assigned" id="assigned"  value="<?= $assigned; ?>">
                        
                        </br>
                       
                        <div class="clearfix"></div>
                        
                        </div>
                       
                       <?php// echo render_input('description', 'custom_lead_remark'); ?>
                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                       <?php if (! is_admin())
	                                        {
	                                       ?>
                        <button type="submit" name="submit" class="btn btn-info category-save-btn cline215">Update</button>
                        
                       <?php  }?>
                    </div>
                </form>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <?php init_tail_new();
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
        
        //alert(rem);
        //alert(remarks_id);
        });
/*$('#submit').click(function() {
    var form_data = {
        remarks: $('#rem').val(),
        
        id: 1
    };
    $.ajax({
        url: "<?php echo site_url('admin/leads/addRemarks'); ?>",
        type: 'POST',
        data: form_data,
        success: function(msg) {
            alert(msg);
            if (msg == 'YES')
                $('#alert-msg').html('<div class="alert alert-success text-center">Your mail has been sent successfully!</div>');
            else if (msg == 'NO')
                $('#alert-msg').html('<div class="alert alert-danger text-center">Error in sending your message! Please try again later.</div>');
            else
                $('#alert-msg').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
    return false;
});*/
</script>
<script>
$('document').ready(function(){
    //alert("test");
    $('#clean_filter').click(function(){
        location.reload();
    });
    $("button").click(function() {
    var fired_button = $(this).val();
    //alert(fired_button);
    $('#remarks_id').val(fired_button);
    var remarks = $(this).attr("data-remarks");
    
    $('#rem').val(remarks);
    
});

$('#modelClose').click(function(){
   
});




})
   function edit_product_catagory(invoker, id, name,publishing, phonenumber) {
        //print_r($data);
        var name = $(invoker).data('name');
        var status = $(invoker).data('status');
        var description = $(invoker).data('description');
        var phonenumber = $(invoker).data('phone_number');
        var booktitle = $(invoker).data('booktitle');
        var book_format = $(invoker).data('book_format');
        var otherphonenumber = $(invoker).data('otherphonenumber');
        var srnumber = $(invoker).data('srnumber');
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
        var publishedEarlier = $(invoker).data('publishedEarlier');
        
        
        console.log(name);
        console.log(status);
        console.log(description);
        console.log(phonenumber);
        console.log(email);
        console.log(designation);
        console.log(company);
        console.log(address);
        console.log(data_source);
        console.log(calling_objective);
        console.log(meetingtimefrom);
        console.log(meetingtimeto);
        console.log(assigned);
        console.log(next_calling);
        console.log(booktitle);
        console.log(otherphonenumber);
        console.log("publishi:"+publishing);
        

        $('#additional').append(hidden_input('id', id));
        $('#product_catagory input[name="id"]').val(id);
        $('#product_catagory input[name="name"]').val(name);
        $('#product_catagory input[name="phonenumber"]').val(phonenumber);
        $('#product_catagory input[name="booktitle"]').val(booktitle);
        $('#product_catagory input[name="book_format"]').val(book_format);
        $('#product_catagory input[name="otherphonenumber"]').val(otherphonenumber);
        $('#product_catagory input[name="email"]').val(email);
       


       
        $('#product_catagory input[name="data_source"]').val(data_source);
        $('#product_catagory input[name="calling_objective"]').val(calling_objective);
        $('#product_catagory input[name="meetingtimefrom"]').val(meetingtimefrom);
        $('#product_catagory input[name="meetingtimeto"]').val(meetingtimeto);
        $('#product_catagory input[name="assigned"]').val(assigned);
        $('#product_catagory input[name="srnumber"]').val(srnumber);
        $('#product_catagory input[name="next_calling"]').val(next_calling);
        $('#product_catagory input[name="publishedEarlier"]').val(publishing);
        $('#product_catagory input[name="status"]').val(status);

        $.ajax({
            url: "<?php echo base_url(); ?>admin/leads/allleadremark2",
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
                
            },
            success: function (data) // A function to be called if request succeeds
            {
                  //console.log(data);
                $("#product_catagory").html(data);
                $('#phonenumber').attr("disabled","disabled");
                //$("#next_calling").datepicker({ minDate: new Date()});
                //$('#data_source').attr("disabled","disabled");
                //$('#publishedEarlier').attr("disabled","disabled");
                //$('#email').attr("disabled","disabled");
                //$('#test').text();
                $("label[for='calling_objective']").text("Ad Name");
                $('#calling_objective').attr("disabled","disabled");
                $("#descriptions").removeAttr( "autocomplete" );
               // $('#autocomplete').autocomplete().disable();
                $("form").attr('autocomplete', 'off'); 
                $('#product_catagory').modal('show');
            }
        });
    }
    
    function edit_product_catagory2(invoker, id, name,publishing, phonenumber,otarray,manuscript,language) {
        //alert(id);
        
         $.ajax({
            url: "<?php echo base_url(); ?>admin/leads/getallleads",
            method: 'GET',
            data: {
                id: id,
                otarray:otarray
            },
            success: function (datar) 
            {
           
           
             console.log(datar);
             var obj = JSON.parse(datar);
           
           //console.log("ghnnnnggccc:"+ obj.full_name);
              
                
               /* ggg:{"id":"1936","full_name":"Shiv Ranjan","email":"Blankemail","phone_number":"p:+919835555024","ad_name":"FB Comments","book_format":"Blankbook_format","booktitle":"Blankbooktitle","PublishedEarlier":"BlankPublishedEarlier","categorisation":"Blankcategorisation","next_callingd":"hcfdfh","next_calling":"0000-00-00 00:00:00","otherphonenumber_n":"line5781233","manuscript_status":"Blankmanuscript_status","user_language":"Blankuser_language"}*/
                
                
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/leads/allleadremark2",
                    method: 'POST',
                    data: {
                        id: id,
                        name: obj.full_name,
                        email:obj.email,
                        phonenumber: obj.phone_number,
                        calling_objective:obj.ad_name,
                        booktitle:obj.booktitle,
                        book_format:obj.book_format,
                        publishedEarlier:obj.PublishedEarlier,
                        description:obj.categorisation,
                        next_callingd:obj.next_callingd,
                        next_calling:obj.next_callingd,
                        otherphonenumber:obj.otherphonenumber_n,
                        status: obj.categorisation,
                        manuscript:obj.manuscript_status,
                        language:obj.user_language,
                        
                        
                    },
                    success: function (data)
                    {
                         
                        $("#product_catagory").html(data);
                        $('#phonenumber').attr("disabled","disabled");
                        
                        $("label[for='calling_objective']").text("Ad Name");
                        $('#calling_objective').attr("disabled","disabled");
                        $("#descriptions").removeAttr( "autocomplete" );
                      
                        $("form").attr('autocomplete', 'off'); 
                        $('#product_catagory').modal('show');
                    }
                });
               
    
            }
        });
        
      
      
        
        

    $('#additional').append(hidden_input('id', id));
      

        
    }
</script>
<script>
    
    $(document).on('change', '.custom_lead_filter', function () {
        //alert('test');
		$('.all_select').prop('checked', false);
        const pageURL = window.location.href,
            urlcuren = pageURL.split( '/' ),
            status = $('#statusfilter').val(),
            company = $('#companyfilter').val(),
            data_source = $('#data_sourcefilter').val(),
            language = $('#userLanguagefilter').val(),
            publishedearlier = $('#publishedearlier').val(),
            publishingConsultant = $('#publishingConsultant').val(),
            manuscript_status = $('#menuscriptstatus').val(),
            createdDate = $('#createdDate').val(),
            
            adName = $('#adName').val(),
            
            source = $('#lead_sourcefilter').val(),
            lastcontact = $('#datefilter').val();
            url = "<?= base_url('admin/leads/custom_lead_filter_added2') ?>";
            leadpagename = urlcuren[ urlcuren.length - 1 ];
            user_language = $('#userLanguagefilter').val();
            
        
            
        $.get(url, {status: status,leadpagename:leadpagename,user_language:user_language,manuscript_status:manuscript_status,createdDate:createdDate,adName:adName,publishedearlier:publishedearlier,publishingConsultant:publishingConsultant},
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


 $(document).ready(function() {
    
} );



</script>
</body>
</html>