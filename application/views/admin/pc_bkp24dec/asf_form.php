<?php //init_head(); ?>

<html lang="en">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />

  <link href="<?php echo base_url();?>/uploads/company/favicon.png" rel="shortcut icon">

  <title>Pending Projects</title>

  <link href="<?php echo base_url();?>/assets/css/reset.css?v=2.1.1" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

  <link href='https://bfcgroup.in/assets/plugins/roboto/roboto.css' rel='stylesheet'>

  <link href="https://bfcgroup.in/assets/plugins/app-build/vendor.css?v=2.1.1" rel="stylesheet">

  <link href="https://bfcgroup.in/assets/css/style.css?v=2.1.1" rel="stylesheet">

</head>

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<style type="text/css">

  .error{

    color: red;

  }
  .personalHeading{
		color: red;
	}
  @media only screen and (max-width: 768px) {
	.personalHeading{
		width: 1000px !important;
		color: red;
    font-size: 18px;
	}
	.provideHeading{
	   font-size: 19px; 
	}
}

</style>

<body>



  <div class="content">





    <div class="row">



      <div class="col-md-12">



        <div class="panel-body" style="overflow: auto;">



        </div>



        <div class="panel_s" >

          <div class="panel-body" style="overflow: auto;">

            <div class="_buttons">

                <div class="row">

                    <div class="col-md-12">
                      <div style="text-align: center;">
                        <img  src="<?php echo base_url(); ?>/assets/images/logos/bfc_img.png"/>
                      </div>
                    </div> 

                </div>

              <div class="row">

                <div class="col-md-8">

                  <h2 class="provideHeading">Please Provide Your Details 

                  </h2>  

                </div>

                <div class="col-md-4">

                </div>

                <?php $email = $this->uri->segment(4);

                $data_author = $this->db->get_where('tblleads',array('email'=>$email))->row();

                                  // print_r($data_author); ?>

                                </div>

                                <?php echo form_open_multipart('admin/asf_controller/save_data');?>

                                

                                  <div class="col-md-12">

                                    <h2 class="personalHeading">Personal Details</h2>

                                    <div class="row">

                                      <div class="col-md-3">

                                       <div class="form-group" app-field-wrapper="email">

                                        <label for="email" class="control-label">Profile pic(<span style="color:red;">max 2mb</span>)</label>

                                         <input type="file"  name="userfile" id="userfile" required/>
                                         
                                         <span id="img_genrated_err"></span>
                                      </div>

                                    </div>

                                      <div class="col-md-3" id="otherphonelabel">

                                        <div class="form-group" app-field-wrapper="otherphonenumber">

                                          <label for="otherphonenumber" class="control-label">Author Name</label>

                                          <input type="text" id="author_name" name="author_name" class="form-control" value="" autocomplete="no" required>

                                          <input type="hidden" id="hidden_id" name="hidden_id" class="form-control" value="<?=  $data_author->id;?>" autocomplete="no">

                                          <input type="hidden" id="hidden_email" name="hidden_email" class="form-control" value="<?=  $data_author->email;?>" autocomplete="no">

                                        </div>

                                      </div>

                                      <div class="col-md-3">

                                       <div class="form-group" app-field-wrapper="email">

                                        <label for="email" class="control-label">Father Name</label>

                                        <input type="text" id="father_name" name="father_name" class="form-control" value="" required>

                                      </div>

                                    </div>

                                    <div class="col-md-3">

                                     <div class="form-group" app-field-wrapper="email">

                                      <label for="email" class="control-label">Date of Birth</label>

                                      <input type="date" id="dob" name="dob" class="form-control" value="" required>

                                    </div>

                                  </div>  

                                  <div class="col-md-3">

                                   <div class="form-group" app-field-wrapper="email">

                                    <label for="email" class="control-label">Contact No</label>

                                    <input type="text" id="contact_no" name="contact_no" class="form-control" value="" required>

                                  </div>

                                </div>  

                                <div class="col-md-3">

                                 <div class="form-group" app-field-wrapper="email">

                                  <label for="email" class="control-label">Alternate contact no.</label>

                                  <input type="text" id="alternate_no" name="alternate_no" class="form-control" value="">

                                </div>

                              </div>  

                              <div class="col-md-3">

                               <div class="form-group" >

                                <label for="emailid" class="control-label">e-mail ID</label>

                                <input type="text"  id="email_id" name="email_id" class="form-control" value="<?= $email;?>" disabled>

                              </div>

                            </div> 

                            <div class="col-md-3">

                             <div class="form-group" app-field-wrapper="email">

                              <label for="email" class="control-label">Nominee Name</label>

                              <input type="text" id="nominee_name" name="nominee_name" class="form-control" value="">

                            </div>

                          </div> 

                          <div class="col-md-3">

                           <div class="form-group" app-field-wrapper="email">

                            <label for="email" class="control-label">Correspondence  Address :</label>

                            <input type="text" id="correspondence_address" name="correspondence_address" class="form-control" value="" required>

                          </div>

                        </div> 

                        <div class="col-md-3">

                         <div class="form-group" app-field-wrapper="email">

                          <label for="email" class="control-label">Landmark</label>

                          <input type="text" id="landmark" name="landmark" class="form-control" value="" required>

                        </div>

                      </div> 

                      <div class="col-md-3">

                       <div class="form-group" app-field-wrapper="email">

                        <label for="email" class="control-label">City</label>

                        <input type="text" id="city" name="city" class="form-control" value="" required>

                      </div>

                    </div> 

                    <div class="col-md-3">

                     <div class="form-group" app-field-wrapper="email">

                      <label for="email" class="control-label">State</label>

                      <input type="text" id="state" name="state" class="form-control" value="" required>

                    </div>

                  </div> 

                  <div class="col-md-3">

                   <div class="form-group" app-field-wrapper="Pin Code">

                    <label for="Pin Code" class="control-label">Pin Code</label>

                    <input type="number" id="pincode" name="pincode" class="form-control" value="" required>

                  </div>

                </div> 

                <div class="col-md-3">

                 <div class="form-group" app-field-wrapper="Country">

                  <label for="Country" class="control-label">Country</label>

                  <input type="text" id="country" name="country" class="form-control" value="" required>

                </div>

              </div> 

            </div>

            <h2 style="color:red;">Bank Details</h2>

            <div class="row">

              <div class="col-md-3">

               <div class="form-group" app-field-wrapper="email">

                <label for="email" class="control-label">Account  holder’s name</label>

                <input type="text" id="account_holder_name" name="account_holder_name" class="form-control" value="" required>

              </div>

            </div> 

            <div class="col-md-3">

             <div class="form-group" app-field-wrapper="email">

              <label for="email" class="control-label">Account No.</label>

              <input type="number" id="account_no" name="account_no" class="form-control" maxlength="16" value="" required>

            </div>

          </div> 

          <div class="col-md-3">

           <div class="form-group" app-field-wrapper="email">

            <label for="email" class="control-label">Bank Name</label>

            <input type="text" id="bank_name" name="bank_name" class="form-control" value="" required>

          </div>

        </div>

        <div class="col-md-3">

         <div class="form-group" app-field-wrapper="email">

          <label for="email" class="control-label">Branch</label>

          <input type="text" id="branch" name="branch" class="form-control" value="" required>

        </div>

      </div> 

      <div class="col-md-3">

       <div class="form-group" app-field-wrapper="email">

        <label for="email" class="control-label">IFSC Code</label>

        <input type="text" id="ifsc_code" name="ifsc_code" class="form-control" value="" required>

      </div>

    </div> 

    <div class="col-md-3">

     <div class="form-group" app-field-wrapper="email">

      <label for="email" class="control-label">Pan No.</label>

      <input type="text" id="pan_no" name="pan_no" class="form-control" value="" required>

    </div>

  </div>



</div>

<h2 style="color:red;">Book Details</h2>



<div class="row">



  <div class="col-md-3">

   <div class="form-group" app-field-wrapper="email">

    <label for="email" class="control-label">Book Title</label>

    <input type="text" id="bookTitle" name="bookTitle" class="form-control" value=""  required>

  </div>

</div>

 

<div class="col-md-3">

 <div class="form-group" app-field-wrapper="email">

  <label for="email" class="control-label">Manuscript Language</label>

  <!-- <input type="text" id="manuscript_language" name="manuscript_language" class="form-control" value=""> -->

  <select name="manuscript_language" class="form-control" id="manuscript_language" >

    <option value="" selected="true" disabled="disabled">Select</option>

    <option value="English">English</option>

    <option value="Hindi">Hindi</option>

    <option value="Hindi">Others</option>

    

  </select>

</div>

</div> 

<div class="col-md-3">

 <div class="form-group" app-field-wrapper="email">

  <label for="email" class="control-label">Book Genre</label>

  <!-- <input type="text" id="book_genre" name="book_genre" class="form-control" value=""> -->

  <select name="book_genre" class="form-control" id="book_genre" >

    <option value="" selected="true" disabled="disabled">Select</option>

    <option value="Fiction">Fiction</option>

    <option value="Non-Fiction">Non-Fiction</option>

    <option value="Poetry">Poetry</option>

    <option value="Academics">Academics</option>

    <!--<option value="Indian Language">Indian Language</option>-->

    <option value="Children Book">Children's Book</option>

    <!--<option value="Photo Book">Photo Book</option>-->

    <!--<option value="Cook Book">Cook Book</option>-->

    <option value="Others">Others</option>

  </select>

</div>

</div> 

<div class="col-md-3">

 <div class="form-group" app-field-wrapper="email">

  <label for="email" class="control-label">Number of images (If any)</label>

  <input type="number" id="number_of_images" name="number_of_images" class="form-control" value="" required>

</div>

</div>

<div class="col-md-3">

 <div class="form-group" app-field-wrapper="email">

  <label for="email" class="control-label">Manuscript Status </label>

  <select name="manuscript_status" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="manuscript_status">

    <option value="" selected></option>

    <option value="1" >Completed</option>

    <option value="2" >In-process</option>

  </select>

</div>

</div> 

<div class="col-md-3">

 <div class="form-group" app-field-wrapper="email">

  <label for="email" class="control-label">Book Size</label>

  <select name="book_size" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="book_size">

    <option value="" selected></option>

    <option value="1" >5"x8"</option>

    <option value="2" >6"x9"</option>

    <option value="3">8"x11"</option>

  </select>

</div>

</div> 

<div class="col-md-4">

 <div class="form-group" app-field-wrapper="email">

  <label for="email" class="control-label">How would you like your name to appear on book?</label>

  <input type="text" id="name_appear_on_book" name="name_appear_on_book" class="form-control" value="" required>

</div>

</div>

</div>

<h2 style="color:red;">Cover details</h2>

<div class="row">

  <div class="col-md-4">

   <div class="form-group" app-field-wrapper="email">

    <label for="email" class="control-label"><h4>Synopsis -</h4> about 500 words ( to enable the cover designer to understand the theme of the book and would not be printed on the book)</label>

    <textarea class="ckeditor" cols="80" id="synopsis" name="synopsis" rows="10" required=""></textarea>

  </div>

</div> 

<div class="col-md-4">

 <div class="form-group" app-field-wrapper="email">

  <label for="email" class="control-label"><h4>Blurb -</h4> about the book (a short description of the book to be printed on the back of the cover.)</label>

  <textarea class="ckeditor" cols="80" id="blurb" name="blurb" rows="10" required=""></textarea>

</div>

</div> 

<div class="col-md-4">

 <div class="form-group" app-field-wrapper="email">

  <label for="email" class="control-label"><h4>Author Bio -</h4> about 200 words (a short description about you which will be printed on the back cover of the book.)</label>

  <textarea class="ckeditor" cols="80" id="author_bio" name="author_bio" rows="10" required=""></textarea>

</div>

</div> 

</div>





</div>

<div class="col-md-12">

  <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->

  <button type="submit" name="submit" class="btn btn-info category-save-btn">Submit</button>  

</div>   

</form>



<div class="clearfix"></div>



</div>



</div>

</div>

</div>

</div>

</div>









<!-- Modal -->

<script type="text/javascript">

$(document).ready(function(){
	$("#userfile").change(function(){
		$("#img_genrated_err").html("");
		var file_size = $('#userfile')[0].files[0].size;
    var fileUpload = document.getElementById("userfile");
        if (typeof (fileUpload.files) != "undefined") {
            var size = parseFloat(fileUpload.files[0].size / 1024).toFixed(2);
		if(size > 2024) {
			$("#img_genrated_err").html("<p style='color:#FF0000'>Your File size is greater than 2MB</p>");
      $('#userfile').val(null);
      
		}else{

    } 
        }
		// return true;
	});
});

 $("#asfform").validate({

  rules: {

   "author_name": {

    required: true

  },

  "father_name": {

    required: true

  },

  "dob": {

    required: true

  },

  "contact_no": {

    required: true

  },

  

  "nominee_name": {

    required: true

  },

  "correspondence_address": {

    required: true

  },



  "city": {

    required: true

  },

  "state": {

    required: true

  },

  "pincode": {

    required: true

  },

  "country": {

    required: true

  },

  "account_holder_name": {

    required: true

  },

  "account_no": {

    required: true

  },

  "bank_name": {

    required: true

  },

  "branch": {

    required: true

  },

  "ifsc_code": {

    required: true

  },

  "pan_no": {

    required: true

  },

  "bookTitle": {

    required: true

  },

  "name_appear_on_book": {

    required: true

  },

  "book_genre": {

    required: true

  },

  "manuscript_status": {

    required: true

  },



  "book_size": {

    required: true

  },



},

messages: {

  "author_name": {

    required: "Please enter Name"

  },

  "father_name": {

    required: "Please enter  Father Name"

  },

  "dob": {

    required: "Please enter Date of Birth"

  },

  "contact_no": {

    required: "Please enter Contact No"

  },



  "nominee_name": {

    required: "Please enter Nominee Name"

  },

  "correspondence_address": {

    required: "Please enter Correspondence Address"

  },

  

  "city": {

    required: "Please enter City"

  },

  "state": {

    required: "Please enter State"

  },

  "pincode": {

    required: "Please enter Pin Code"

  },

  "country": {

    required: "Please enter Country"

  },

  "account_holder_name": {

    required: "Please enter Account Holder Name"

  },

  "account_no": {

    required: "Please enter Account No"

  },

  "bank_name": {

    required: "Please enter Bank Name"

  },

  "branch": {

    required: "Please enter Branch Name"

  },

  "ifsc_code": {

    required: "Please enter IFSC Code"

  },

  "pan_no": {

    required: "Please enter Pan Number"

  },



  "bookTitle": {

    required: "Please enter Book Title"

  },

  "name_appear_on_book": {

    required: "Please enter Name Appear On Book"

  },

  "manuscript_language": {

    required: "Please enter Manuscript Language"

  },

  "book_genre": {

    required: "Please enter Book Genre"

  },

  "manuscript_status": {

    required: "Please enter Manuscript Status"

  },

  "book_size": {

    required: "Please select Book Size"

  },



},

});

</script>

<?php //init_tail(); ?>

</body>

</html>