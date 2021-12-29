<?php init_head(); ?>
<div id="wrapper">
<div class="content">
<div class="row">
  <div class="col-md-12">
    <div class="panel-body" style="overflow: auto;">
    <a href="<?php echo base_url() ?>admin/pm_lead/listMrpFixation">Show List</a>
    </div>
    <div class="panel_s" >
    <div class="panel-body" style="overflow: auto;">
        <div class="_buttons">
        <div class="row">
            <div class="col-md-4">
                <h3>MRP Fixation</h3>  
            </div>
            <div class="col-md-4">
            </div>
        </div>
        <form action="<?php echo base_url() ?>admin/pm_lead/mrpfixation"  id="asfform" autocomplete="off"  method="post" >
                          <div class="col-md-12">
                            <!-- <h4>MRP Fixation</h4> -->
                            <div class="row">
    
                              <div class="col-md-3" id="otherphonelabel">
                                <div class="form-group" app-field-wrapper="otherphonenumber">
                                  <label for="otherphonenumber" class="control-label">Author id name</label>
                                  <input type="text" id="author_id_name" name="author_id_name" class="form-control" value="" autocomplete="no">
                                  <input type="hidden" id="author_id" name="author_id" class="form-control" value="<?=  $mrpfixation->author_id+1;?>" autocomplete="no">
                        
                                </div>
                              </div>
                              <div class="col-md-3">
                               <div class="form-group" app-field-wrapper="email">
                                <label for="email" class="control-label">Author Name</label>
                                <input type="text" id="author_name" name="author_name" class="form-control" value="">
                              </div>
                            </div>
                            <div class="col-md-3">
                             <div class="form-group" app-field-wrapper="email">
                              <label for="email" class="control-label">email</label>
                              <input type="email" id="email" name="email" class="form-control" value="">
                            </div>
                          </div>  
                          <div class="col-md-3">
                           <div class="form-group" app-field-wrapper="email">
                            <label for="email" class="control-label">Contact No</label>
                            <input type="text" id="mobile" name="mobile" class="form-control" value="">
                          </div>
                        </div>  
                        <div class="col-md-3">
                         <div class="form-group" app-field-wrapper="email">
                          <label for="email" class="control-label">Book Type</label>
                           <select name="book_type" class="form-control statuschangelead" data-width="100%"  data-live-search="true"  id="book_type">
                              <option value="" selected></option>
                              <option value="ebook" >Ebook</option>
                              <option value="paperback" >Pepaerback</option>
                            </select>
                        </div>
                      </div> 
                      <div class="col-md-3">
                       <div class="form-group" >
                        <label for="emailid" class="control-label">Book Title</label>
                        <input type="text"  id="book_title" name="book_title" class="form-control" value="" >
                      </div>
                    </div> 
                      <div class="col-md-3">
                       <div class="form-group" >
                        <label for="emailid" class="control-label">Production Cost</label>
                        <input type="text"  id="production_cost" name="production_cost" class="form-control" value="<?= $email;?>" >
                      </div>
                    </div> 
                    <div class="col-md-3">
                     <div class="form-group" app-field-wrapper="email">
                      <label for="email" class="control-label">Author Production cost</label>
                      <input type="text" id="author_p_cost" name="author_p_cost" class="form-control" value="">
                    </div>
                  </div> 
                  <div class="col-md-3">
                   <div class="form-group" app-field-wrapper="email">
                    <label for="email" class="control-label">Rec MRP</label>
                    <input type="text" id="rec_mrp" name="rec_mrp" class="form-control" value="">
                  </div>
                </div> 
                <div class="col-md-3">
                 <div class="form-group" app-field-wrapper="email">
                  <label for="email" class="control-label">Amazon Royalty</label>
                  <input type="text" id="amazon" name="amazon" class="form-control" value="">
                </div>
              </div> 
              <div class="col-md-3">
               <div class="form-group" app-field-wrapper="email">
                <label for="email" class="control-label">BFC Royalty</label>
                <input type="text" id="bfc" name="bfc" class="form-control" value="">
              </div>
            </div> 
            <div class="col-md-3">
             <div class="form-group" app-field-wrapper="email">
              <label for="email" class="control-label">KDP Royalty</label>
              <input type="text" id="kdp" name="kdp" class="form-control" value="">
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

</div>

</div>


<!-- Modal -->
<script type="text/javascript">
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
<?php init_tail(); ?>

</body>

</html>