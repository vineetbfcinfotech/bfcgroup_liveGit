<div class="modal-dialog <?php echo get_option('product_catagory_modal_class'); ?>">
    <div class="modal-content data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Update Lead </h4>
        </div>
        <div class="modal-body">
                        <div class="row">
                        <?php //echo "test";echo "<pre>"; print_r($manuscript_status);exit();?>
                        <form action="" id="updateform" autocomplete="off">
                        <div class="col-md-12">
                        <div class="row">
                        <input type="hidden" name="added_by" value="<?= $_SESSION['staff_user_id']; ?>">
                        <?php echo render_input('id', '', array('readonly' => 'readonly'), 'hidden'); ?> 
                        
                        <?php echo render_input('srnumber', '', array('readonly' => 'readonly'), 'hidden'); ?>
                        <div class="col-md-4">
                        <?php //echo render_input('name', 'custom_lead_name', array('readonly' => 'readonly')); ?>
                        <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Author Name</label>
                        <input type="text" id="name1" name="name1" class="form-control" value="<?php echo $name;?>" autocomplete="no" <?php// if($name !=''){ echo "disabled";}?>></div>
                        </div>
                        
                        <div class="col-md-4">
                        <?php echo render_input('phonenumber', 'leads_dt_phonenumber'); ?>
                        </div>
                        <div class="col-md-4">
                        <?php //echo render_input('email', 'leads_dt_email', array('readonly' => 'readonly')); ?>
                        <div class="form-group" app-field-wrapper="email">
                        <label for="email" class="control-label">Email</label>
                        <input type="text" id="email1" name="email1" class="form-control" value="<?php echo $email; ?>" <?php //if($email !=''){ echo "disabled";}?>>
                        </div>
                        </div>
                        </div>
                        
                        
                        <div class="row">
                        
                        <div class="col-md-4">
                        <?php //echo render_input('calling_objective', 'lead_calling_objective', array('readonly' => 'readonly')); ?>
                        <div class="form-group" app-field-wrapper="calling_objective">
                        <label for="calling_objective" class="control-label">Ad Name</label>
                        <input type="text" id="adname" name="adname" class="form-control" value="<?php echo $adname;?>" >
                        </div>
                        </div>
                        
                        <div class="col-md-4">
                        <div class="form-group" app-field-wrapper="publishedEarlier">
                        <label for="next_PublishedEarlier" class="control-label">Published Earlier</label>
                        <select name="publishedEarlier1" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="publishedEarlier1"
                        data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                         <option value="" selected></option>
                        <option value="yes" <?php if($publishedEarlier == 'yes'){ echo "selected";}?>>Yes</option>
                        <option value="no" <?php if($publishedEarlier == 'no'){ echo "selected";}?>>No</option>
                        </select>  
                        
                        </div>
                        </div>
                        
                        
                        <div class="col-md-4">
                        <div class="form-group" app-field-wrapper="publishedEarlier">
                        <label for="next_PublishedEarlier" class="control-label">Manuscript Status</label>
                        <select name="manuscript_status" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="manuscript_status"
                        data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                         <option value="" selected></option>
                        <option value="completed" <?php if($manuscript_status == 'completed'){ echo "selected";}?>>completed</option>
                        <option value="in_process" <?php if($manuscript_status == 'in_process'){ echo "selected";}?>>in_process</option>
                        </select>  
                        
                        </div>
                        </div>
                        
                        </div>
                        
                        <div class="row">
                        
                        <div class="col-md-4">
                        
                        <div class="form-group" app-field-wrapper="calling_objective">
                        <label for="calling_objective" class="control-label">Ad Id</label>
                        <input type="text" id="ad_id" name="ad_id" class="form-control" value="<?php echo $ad_id;?>" >
                        </div>
                        </div>
                        
                        <div class="col-md-4">
                        <div class="form-group" app-field-wrapper="publishedEarlier">
                        <label for="next_PublishedEarlier" class="control-label">Book Language</label>
                        <select name="user_language" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="user_language"
                        data-publishedEarlier-id="<?= $allleadremark[0]->lead_id; ?>" <?php //if($publishedEarlier != ''){ echo "disabled"; }?>>
                        <option value="hindi" <?php if($user_language == 'hindi'){ echo "selected";}?>>hindi</option>
                        <option value="english" <?php if($user_language == 'english'){ echo "selected";}?>>english</option>
                        <option value="others" <?php if($user_language == 'others'){ echo "selected";}?>>others</option>
                        </select>  
                        
                        </div>
                        </div>
                        
                        <div class="col-md-3" style="display:none;">
                        <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label">Lead Creation Date</label>
                        <input type="text" id="leadCreationDate" name="leadCreationDate" class="form-control datetimepicker" value="<?php echo $created_time;?>" autocomplete="no"></div>
                        </div>
                        
                        
                        
                        
                        </div>
                        
                     
                       
                        
                  
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       
                        <button type="submit" name="submit" class="btn btn-info category-save-btn">Submit</button>
                        
                      
                    </div>
                </form>
            </div>
        </div>


        <script type="text/javascript">
        
        $("#updateform").submit(function(e){
            
            //alert("test");
             e.preventDefault();

                var id = $("input[name='id']").val();
                var srnumber = $("input[name='srnumber']").val();
                
                var name = $("input[name='name1']").val();
                var phonenumber = $("input[name='phonenumber']").val();
                var email = $("input[name='email1']").val();
                var adname = $("input[name='adname']").val();
                var leadCreationDate = $("input[name='leadCreationDate']").val();
                var publishedEarlier = $("#publishedEarlier1").val();
                var manuscript_status = $("#manuscript_status").val();
                var ad_id = $("#ad_id").val();
                var user_language = $("#user_language").val();
                var leadCreationDate = $("#leadCreationDate").val();
                //alert(publishedEarlier);
                //alert(email);
                
                
               
                $.ajax({
                    url: '<?= base_url();?>admin/leads/update_added_lead1/',
                    type: 'GET',
                    data: {
                        id: id,
                        name: name,
                        phonenumber: phonenumber,
                       leadCreationDate:leadCreationDate,
                        email: email,
                        publishedEarlier: publishedEarlier,
                        manuscript_status: manuscript_status,
                        user_language:user_language,
                        ad_id:ad_id,
                        adname:adname,
                    },
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                        $('#product_catagory').modal('hide');
                         //alert('test2');
                         //alert(data);
                       location.reload();
                       
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
                                
                            }
                        });
                       
                        
                    }
                });
        })
            $("#remarkform").submit(function (e) {
               //alert('test1');
                e.preventDefault();

                var id = $("input[name='id']").val();
                var srnumber = $("input[name='srnumber']").val();
                
                var name = $("input[name='name']").val();
                var phonenumber = $("input[name='phonenumber']").val();
                var email = $("input[name='email']").val();
                var adname = $("input[name='adname']").val();
                var leadCreationDate = $("input[name='leadCreationDate']").val();
                var publishedEarlier = $("#publishedEarlier").val();
                var manuscript_status = $("#manuscript_status").val();
                var ad_id = $("#ad_id").val();
                var user_language = $("#user_language").val();
                var leadCreationDate = $("#leadCreationDate").val();
                
                
                
               
                $.ajax({
                    url: '<?= base_url();?>admin/leads/update_added_lead1/',
                    type: 'GET',
                    data: {
                        id: id,
                        name: name,
                        phonenumber: phonenumber,
                       leadCreationDate:leadCreationDate,
                        email: email,
                        publishedEarlier: publishedEarlier,
                        manuscript_status: manuscript_status,
                        user_language:user_language,
                        ad_id:ad_id,
                        adname:adname,
                    },
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                        $('#product_catagory').modal('hide');
                         //alert('test2');
                         //alert(data);
                       location.reload();
                       
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
                                
                            }
                        });
                       
                        
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

function showphone(){
    //$(this).hide();
   //  //$('#otherphonenumber').show();
    //$('#otherphonelabel').show();
}
    $(function () {
        //$('#otherphonenumber').hide();
        /*if($('#otherphonenumber').val() != ''){
            $('#otherphonelabel').show();
        }else{
           $('#otherphonelabel').hide(); 
        }*/
        
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
  $("#descriptions").attr("autocomplete", "off");
  $("#next_calling").attr("autocomplete", "off");
  $('#phonenumber').css('width','250px');
});
 </script>
    </div>
</div>
</div>