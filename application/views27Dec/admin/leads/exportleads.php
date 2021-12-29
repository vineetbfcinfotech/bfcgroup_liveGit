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
                                     
                                            <a onclick="window.location='<?php echo base_url(); ?>';"> Home
                                            </a> / Export Leads
                                         
                                         
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

                            <?php if (!empty($assignedleads) > 0) { ?>
                                 <!--<table id="example" class="display" style="width:100%">-->
                                 <table id="example33" class="table table-striped table-bordered" cellspacing="0" width="100%" height="50%">
        <thead>
                                    <tr>
									<?php if (is_admin()) { ?>
										<td style="display:none;"></td>
									<?php } ?>
									<th class="bold">DB Id</th>
									<th class="bold">Sr. No.</th>
                                        
                                        <th class="bold">Author Name</th>
                                        <th class="bold">Contact No 1</th>
                                        <th class="bold">Contact No 2</th>
                                        <th class="bold">Email Id</th>
                                        <th class="bold">Book Language</th>
                                        <th class="bold">Manuscript Status</th>
                                        <th class="bold">Remarks</th>
                                        <th class="bold">Calling Date</th>
                                        <th class="bold">Category</th>
                                        <th class="bold">Next Calling Date</th>
                                        <th class="bold">Book Format</th>
                                        <th class="bold">Book Title</th>
                                        <th class="bold">Created Date</th>
                                        <th class="bold">Ad Name</th>
                                        <th class="bold">Published Earlier</th>
                                        <?php if (is_admin()){?>
	                                        <th class="bold">Assigned to</th>
	                                    <?php }?>
                                 </tr>
                                    
                                    </thead>
                                    <tbody class="ajax-data">
                                    <?php $co = 1; $con = 1; foreach ($assignedleads as $alllead) { ?>
									
                                       <tr id='lead_id_<?= $alllead->id; ?>'>
									   <?php if (is_admin()) { ?>
									   <td style="display:none;">
									       <input  type="checkbox" name="deleted_value" value="<?= $alllead->id; ?>" class="selected_row" >
									       </td>
									   <?php } ?>
                     
									   <td ><?= $alllead->id; ?></td>
									   <td ><?= $con++; ?></td>
                                        
                                            <td> <?= $alllead->lead_author_name; ?></td>
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
                                        
                                         <td><?php echo $alllead->otherphonenumber;?></td>
                                         <td ><?= $alllead->email; ?></td>
                                          <td><?= $alllead->lead_author_mslanguage; ?></td>  
                                           <td>
                                            <? $address = $alllead->lead_author_msstatus;
                                            $address = str_replace(".", "", $address);
                                        $address =    preg_replace('/[^a-zA-Z0-9_ -]/s','',$address);
                                            echo $address;  ?>
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
                                               $data = explode(';', $alllead->ImEx_leadRemarks); echo $data[0];
												
												echo "<span>";
												//echo count($data);print_r($data);
												if(count($data)>1){
    												for($i=1;$i<=count($data);$i++){
    												    echo ";".$data[$i];
    												}
												}
											
											
											
												echo "</span>";
											   ?>
                                             </td>
                                             <?php if($alllead->lead_callingdate != ''){?>
                                       <td>
                                          
                                           <?php 
                                           echo $alllead->lead_callingdate;?>
                                           </td>
                                       <?php }else {?>
                                        <td></td>
                                       <?php }?>
                                          <td>
                                           
                                                
                                                <?php if($alllead->lead_category_id == 5) {echo "A";} ?>
                                                <?php if($alllead->lead_category_id == 16) {echo "B";} ?>
                                               <?php if($alllead->lead_category_id == 38) {echo "B+";} ?>
                                                <?php if($alllead->lead_category_id == 30) {echo "C";} ?>
                                                <?php if($alllead->lead_category_id == 32) {echo "NP";} ?>
                                                <?php if($alllead->lead_category_id == 39) {echo "Acquired";} ?>
                                               <?php if($alllead->lead_category_id == 40) {echo "UnAttended";} ?>
                                                <?php if($alllead->lead_category_id == 41) {echo "Scrap";} ?>
                                                
                                                
                                               
                                            </td> 
                                            <?php if($alllead->ImEx_NextcallingDate != ''){?>
                                            <td>
                                           <?php 
                                         
                                           echo $alllead->ImEx_NextcallingDate;
                                               ?>
                                    
                                           </td>
                                       <?php }else {?>
                                        <td><?php $nextCalling = explode(" ",$alllead->next_calling);
                                         
                                           echo $alllead->next_calling;
                                               ?></td>
                                       <?php }?>
                                       
	                                         <td><?php echo $alllead->lead_bookformat;?></td>
                                        <td><?php echo $alllead->lead_booktitle;?></td>
	                                       <td >
                                              <?php if ($alllead->ImEx_CreatedAt) { ?>
                                               <?php $nextCalling = explode(" ",$alllead->ImEx_CreatedAt);
                                                
                                                echo $alllead->ImEx_CreatedAt;
                                                ?>
                                             <?php }else{
                                               $nextCalling = explode(" ",$alllead->lead_created_date);
                                                
                                                echo $alllead->lead_created_date;
                                               
                                             } ?>
                                                
                                                
                                                
                                            </td>
                                               
                                        <td><?= $alllead->lead_adname; ?></td>
                                        
                                            <td><?= $alllead->lead_publishedearlier; ?></td>
                                        
                                        
                                        
                                        
                                        
                                         
                                      
                                   
                                        <?php
                                        $colid = $alllead->status;
                                        $this->db->where('id', $colid);
                                        $result = $this->db->get('tblleadsstatus')->result();
                                        ?>
                                       
                                        
                                        <?php
                                    if (is_admin())
	                                        {
	                                       ?>
	                                        <td> <?= $alllead->lead_author_name; ?></td>
	                                          <?php
	                                        }
	                                        ?>
	                                        
	                                     
	                                       
	                                       
                                       
                                        
                                    </tr>
                                    <?php  } ?>
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
        
       
        });

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


</script>
<?php // echo count($assignedleads);exit; ?>
</body>
</html>