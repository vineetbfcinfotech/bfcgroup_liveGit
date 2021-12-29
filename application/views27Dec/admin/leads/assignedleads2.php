<?php init_head_new(); ?>
<?php  //print_r($assignedleads);die; ?>
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
                                           
                                        </h3>  
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                   
                                </div>
                                
                               
                                
                               <a href="" class="btn btn-primary" id="clean_filter">clear Filter</a>
                               
                               


                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
						<?php if (is_admin()) { ?>
						<div class="clearfix"></div>
                      
						<?php } ?>
					
                        <div class="mytable">

                            <?php if (!empty($assignedleads) > 0) { ?>
                                 <!--<table id="example" class="display" style="width:100%">-->
                                 <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%" height="50%">
        <thead>
                                     <tr>
									 <?php if (is_admin()) { ?>
										<td style="display:none;"></td>
									<?php } ?>
                                    <!--<th style="width:20px"><?php echo _l('id'); ?></th>-->
                                    <th class="bold">-</th>
                                    <th class="bold">Sr. no</th>
                                    <th class="bold">Name</th>
                                    <th class="bold">Contact Number</th>
                                    
                                    
                                    <th class="bold">Ad Name</th>
                                   
                                    <th class="bold">Manuscript Status</th>
                                    <th class="bold">Published Earlier</th>
                                    <th class="bold">Email Id</th>
                                    <th class="bold">Language</th>
                                    <th class="bold">Calling Date</th>
                                    <th class="bold">Category</th>
                                    <th class="bold">Remarks</th>
                                    
                                    
                                    
                                   
                                     <?php
                                    if (is_admin())
	                                        {
	                                       ?>
	                                        <th class="bold">Assigned to</th>
	                                          <?php
	                                        }
	                                        ?>
                                   
                                    <th class="bold">Next Calling Date</th>
                                   
                                    <th class="bold">Created Date</th>
                                    <th class="bold">Book Format</th>
                                    <th class="bold">Book Title</th>
                                    <th class="bold">Other Phone </th>
                                    
                                </tr>
                                    
                                    </thead>
                                    <tbody class="ajax-data">
                                    <?php $ii = 1; foreach ($assignedleads as $alllead) { ?>
									
                                       <tr id='lead_id_<?= $alllead->id; ?>'>
									   <?php if (is_admin()) { ?>
									   <td style="display:none;"><input  type="checkbox" name="deleted_value" value="<?= $alllead->id; ?>" class="selected_row" ></td>
									   <?php } ?>
									   
									   
                                        <td id="srNumberget">
                                            <?php // echo $ii*1; ?>   
                                            
                                              <a data-controls-modal="your_div_id" data-backdrop="static" data-keyboard="false" href="#"
                                               onclick="edit_product_catagory2(this,<?= $alllead->id; ?>,'<?= $alllead->full_name; ?>','<?= $alllead->PublishedEarlier; ?>','1','assignedleads2_line245','<?= $alllead->manuscript_status; ?>','<?= $alllead->user_language; ?>');return false;"
                                               data-id="<?= $alllead->id; ?>"
                                                data-publishedEarlier="<?= $alllead->PublishedEarlier; ?>"
                                               data-name="<?= $alllead->full_name; ?>"
                                               data-phone_number="<?= $alllead->phone_number; ?>"
											   data-srnumber="<?= $count; ?>"
                                               data-booktitle="<?= $alllead->booktitle; ?>"
                                               data-book_format="<?= $alllead->book_format; ?>"
                                               data-otherphonenumber="<?= $alllead->otherphonenumber; ?>"
                                               data-email="<?= $alllead->email; ?>"
                                               data-designation="<?= $alllead->designation; ?>"
                                               data-company="<?= $alllead->company; ?>"
                                               data-address="<?= $alllead->address; ?>"
                                               data-data_source="<?= $alllead->adset_name; ?>"
                                               data-calling_objective="<?= $alllead->ad_name; ?>"

                                              

                                               data-assigned="<?= $alllead->assigned; ?>"

                                               data-next_calling="<?= $alllead->next_calling; ?>"
                                             
                                               data-status="<?= $alllead->status; ?>"
                                               data-description="<?= $alllead->description; ?>"><i class="fa fa-edit text-primary" style="font-size:24px;color:red"></i> </a>
                                            <?php
                                            if (is_admin() || is_headtrm())
	                                        {
	                                       ?>
	                                          <a style="display:none;" onclick="return confirm('Are you sure?')" href="<?= sprintf(base_url('admin/leads/delete_addedleads/%s'), $alllead->id); ?>"><i class="fa fa-trash text-danger"></i></a> 
	                                        <?php
	                                        }
	                                        ?>
                                            </td>
                                            
                                            
                                           <td><?php echo $ii*1; ?></td> 
                                            
                                            
                                        <td> <?= $alllead->full_name; ?></a>
                                        </td>
                                        <td>
                                        <?php 
                                            $var = explode("p:+91",$alllead->phone_number);
                                            if($var[0] != '' || $var[0] != null ){
                                                echo $var[0];
                                            }else{
                                              echo $var[1];  
                                            } 
                                        ?>
                                        </td>
                                        
                                        
                                        <td><?= $alllead->ad_name; ?></td>
                                        <td>
                                            <? $address = $alllead->manuscript_status;
                                            $address = str_replace(".", "", $address);
                                        $address =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$address);
                                            echo $address;  ?>
                                            </td>
                                        <td><?= $alllead->PublishedEarlier; ?></td>
                                        <td ><?= $alllead->email; ?></td>
                                        <td><?= $alllead->user_language; ?></td>
                                        <?php if($alllead->calling_date != '0000-00-00'){?>
                                        <td><?= $alllead->calling_date; ?></td>
                                        <?php }else {?>
                                        <td></td>
                                        <?php }?>
                                        <td>
                                           <?php if($alllead->categorisation == 5) {echo "A";} ?>
                                            <?php if($alllead->categorisation == 16) {echo "B";} ?>
                                              <?php if($alllead->categorisation == 38) {echo "B+";} ?>
                                                <?php if($alllead->categorisation == 30) {echo "C";} ?>
                                                <?php if($alllead->categorisation == 32) {echo "NP";} ?>
                                                <?php if($alllead->categorisation == 39) {echo "Acquired";} ?>
                                               <?php if($alllead->categorisation == 40) {echo "UnAttended";} ?>
                                                <?php if($alllead->categorisation == 41) {echo "Scrap";} ?>
                                               
                                            </td>
                                            <?php 
                                            $string = $alllead->description;
                                            $explode_str = explode(" ",$string);
                                            $substring = substr($string,0,10);
                                            ?>
                                           
                                       <?php 
                                       $excerpt = array();
                                       $full = array();
                                       $arr_excerpt = array();
                                       $arr_full = array();
                                       $fullexcerpt = array();
                                       $fullexcerptfinal = array();
                                       for($i=0;$i<=count($explode_str);$i++){?>
                                                <?php if($i <= 3){?>
                                                    <?php $arr_excerpt = array_push($excerpt,$explode_str[$i]);?>
                                                <?php }else{ ?>
                                                     <?php $arr_full = array_push($full,$explode_str[$i]);?>
                                                <?php } ?>
                                        <?php } ?>
                                        <td>
                                                <?php //echo $ex = implode(" ",$excerpt);?>
                                               <?php
                                               
											   $this->db->order_by("created_on", "desc");
												$this->db->where('lead_id',  $alllead->id);
												$this->db->group_by("remark");
												$this->db->select('remark');
												$data = $this->db->get('tblleadremark')->result();
												
												echo "<span class='assignedleads2_line395'>".substr(current($data)->remark,0,10)."</span>";
												echo "<span style='display: none;'>";
												if(	current($data)->remark != ''){
												       $seprationss = ";"; 
												    }else{
												        $seprationss = '';
												    }
											
												echo substr(current($data)->remark,10).$seprationss;
												unset($data[0]); // unset 1st element
												foreach($data as $remarkdata){
												    if($remarkdata->remark != ''){
												       $seprations = ";"; 
												    }else{
												        $seprations = '';
												    }
													echo $remarkdata->remark.$seprations;
												}
												echo "</span>";
											   ?>
                                             </td>
                                      
                                   
                                        <?php
                                        $colid = $alllead->status;
                                        $this->db->where('id', $colid);
                                        $result = $this->db->get('tblleadsstatus')->result();
                                        ?>
                                       
                                        
                                        <?php
                                        if (is_admin())
	                                        {
	                                       ?>
	                                        <td> <?= $alllead->fullname; ?></td>
	                                          <?php
	                                        }
	                                        ?>
	                                        
	                                     
	                                       <?php if($alllead->next_calling != '0000-00-00 00:00:00'){?>
                                            <td>
                                           <?php $nextCalling = explode(" ",$alllead->next_calling);
                                           
                                            echo $nextCalling[0]." ";
                                             if($nextCalling[1]!= '00:00:00')  {
                                                echo  $nextCalling[1];
                                            }                                     
                                               ?>
                                      
                                           <!--<input type="text" value="<?php echo $next;?>">-->
                                           </td>
                                       <?php }else {?>
                                        <td></td>
                                       <?php }?>
	                                       
	                                       <td ><?= $alllead->created_at; ?></td>
	                                         <td><?php echo $alllead->book_format;?></td>
                                        <td><?php echo $alllead->booktitle;?></td>
                                        <td><?php echo $alllead->otherphonenumber;?></td>
                                        
                                    </tr>
                                    <?php $ii++; } ?>
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
       /* $('#product_catagory input[name="id"]').val(id);
        $('#product_catagory input[name="name"]').val(name);
        $('#product_catagory input[name="phonenumber"]').val(phonenumber);*/
        //$('#product_catagory input[name="booktitle"]').val(booktitle);
        //$('#product_catagory input[name="book_format"]').val(book_format);
        //$('#product_catagory input[name="otherphonenumber"]').val(otherphonenumber);
         /*$('#product_catagory input[name="email"]').val(email);
       


       
       $('#product_catagory input[name="data_source"]').val(data_source);
        $('#product_catagory input[name="calling_objective"]').val(calling_objective);
       
        
        $('#product_catagory input[name="srnumber"]').val(srnumber);
        $('#product_catagory input[name="next_calling"]').val(next_calling);
        $('#product_catagory input[name="publishedEarlier"]').val(publishedEarlier);
        $('#product_catagory input[name="status"]').val(status);*/

        
    }
</script>
<script>
    // $(document).on('change', '#lead_status_change', function () {
    //     const status = $(this).val(), id = $(this).data('lead-id'),
    //         url = "<?= base_url('admin/leads/update_cust_lead') ?>";
    //     $.get(url, {status: status, id: id}, function (res) {
    //         location.reload();
    //     })
    // });
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
            
            
            //var filterDate = $('#createdDate').val();
           
              //localStorage.setItem("filterDate", filterDate); 
              //document.getElementById("result").innerHTML = localStorage.getItem("filterDate");
           
            //alert(language);
        /*if(status == "" && company == "" && data_source == "" && calling_objective == "")
        {
            
            location.reload();
        }*/
            
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
    
	/* var table = $('.example11').DataTable({ 
		ajax: "http://bfcpublications.com/chorus/admin/leads/viewassignedleads",
		paging: false,
		searching: false 
	}); */
	
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
		/* var selectdId = [];
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
				tableData.ajax.reload();
			}else{
				alert_float('warning', 'Please Select Leads..');
			}
		  }
		  
		});
		$.each(selectdId, function(key, val){
			$('#lead_id_' + val).remove();
		}); */
	
    });
});
/*setTimeout(function(){
         alert("test");
  	$( ".mytable" ).load( "http://bfcpublications.com/chorus/admin/leads/viewassignedleads .mytable" );
}, 2000); */



 $(document).ready(function() {
     ///resources/load.html #projects li"
     
     
     /*alert("test");
     $('#createdDate').click(function(){
        alert(); 
     });*/
    /*var table = $('#example').DataTable();
 
    $('#example tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
 
    $('#button').click( function () {
        table.row('.selected').remove().draw( false );
    } );*/
} );

/* var cpt = 0;
    var dir = 1;
     $('#example').DataTable();
    $("#test").on("click",function(){
        alert("test");
        setInterval (function (){
            
            var d = table.row(cpt).data(); 
            d.name = (dir > 0 ? d.name + ' ASC' : d.name.slice(0, -4));
            table.row(cpt).data(d);
            table.row(cpt).invalidate();
            
            table.draw(false);
            cpt = cpt + dir;
            if (cpt > 55 || cpt < 1){
                
                dir = dir * -1;
            }
            
            
        },200);
        
   
        
    });*/

</script>
</body>
</html>