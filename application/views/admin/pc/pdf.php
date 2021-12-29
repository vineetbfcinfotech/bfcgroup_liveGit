<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
  <style>
 .font_use{
      font-family: 'Poppins', sans-serif;
    }
    html {
      font-family: "Lucida Sans", sans-serif;
    }    
    * {
      box-sizing: border-box;
    }

    .row::after {
      content: "";
      clear: both;
      /*  display: table;*/
      display: flex;
    }

    [class*="col-"] {
      float: left;
      padding: 15px;
    }

    /* For mobile phones: */
    [class*="col-"] {
      width: 100%;
    }

    @media only screen and (min-width: 600px) {
      /* For tablets: */
      .col-s-1 {width: 8.33%;}
      .col-s-2 {width: 16.66%;}
      .col-s-3 {width: 25%;}
      .col-s-4 {width: 33.33%;}
      .col-s-5 {width: 41.66%;}
      .col-s-6 {width: 50%;}
      .col-s-7 {width: 58.33%;}
      .col-s-8 {width: 66.66%;}
      .col-s-9 {width: 75%;}
      .col-s-10 {width: 83.33%;}
      .col-s-11 {width: 91.66%;}
      .col-s-12 {width: 100%;}
    }
    @media only screen and (min-width: 768px) {
      /* For desktop: */
      .col-1 {width: 8.33%;}
      .col-2 {width: 16.66%;}
      .col-3 {width: 25%;}
      .col-4 {width: 33.33%;}
      .col-5 {width: 41.66%;}
      .col-6 {width: 50%;}
      .col-7 {width: 58.33%;}
      .col-8 {width: 66.66%;}
      .col-9 {width: 75%;}
      .col-10 {width: 83.33%;}
      .col-11 {width: 91.66%;}
      .col-12 {width: 100%;}
    }
    
    .heading{
      text-align: center;
      padding: .7rem;
      background-color: #ffc952;
      font-weight:700;
    }
    
    
    .padding-3{
      padding: 1rem;
    }
    
    .padding-2{
      padding: .5rem;
    }
    
    .bg-color-blue{
      background-color: yellowgreen;
    }
    
    .color-blue{
      color: cadetblue;
    }
    
    .text-center{
      text-align: center;
    }
    .margin-0{
      margin: 0;
    }
    .padding-0{
      padding: 0;
    }
    .padding-bottom-0{
      padding-bottom: 0;
    }
    .margin-top-0{
      margin-top: 0;
    }
    
/*
    .contain{
       padding-left: 30px;
        padding-right: 30px;
    }
    */
/*
    .jumbo {
    padding-top: 50px;
    padding-bottom: 50px;
    margin-bottom: 30px;
    color: inherit;
    background-color: #eee;
}
    
*/
/* css for table class   */

table {
  border-collapse: collapse;
}

.table {
  width: 100%;
  margin-bottom: 1rem;
  background-color: transparent;
}    

*, ::after, ::before {
  box-sizing: border-box;
}    

.table thead th {
  vertical-align: bottom;
  border: 1px solid #dee2e6;
}
.table td, .table th {
  padding: .50rem;
  vertical-align: top;
  border: 1px solid #dee2e6;
}
th {
  text-align: inherit;
}
.table td, .table th {
  padding: .50rem;
  vertical-align: top;
  border: 1px solid #dee2e6;
}
*, ::after, ::before {
  box-sizing: border-box;
} 

.table-address{
  text-align: right;
  padding-bottom: 0;
  margin-bottom: 0;
}

/*
    .table-auto-responsive{
        overflow-x: auto;
        overflow-y: auto;
       display: block;
        width: 100%;
      
    }    
    */
    
    .profile-img{
      height: 200px;
      width: 100%px;
      border-radius: 10px;
    }
    .table-content{
      text-align: center;
    }
    .table-content thead th{
      background-color: #eee;
      border: 1px solid #dee2e6 !important;
    }
    .table-content thead td{
     border: 1px solid #dee2e6;
     
   }
   
   .no-border{
    border:none !important;
  }
  .no-border td, th{
   border:none !important;
 }
 
 @media only screen and (max-width: 600px) {
  .img-small {
   display: none;
 }
}  
</style>
</head>
<body>
  
  <!--    <div class="row">-->
    <!--  <div class="col-7 col-s-12">-->
      <!--  </div>-->
      <!--  <div class="col-5 col-s-12">-->
        <!--      <table class="table-address table no-border">-->
          <!--        <tr><td> Address Line 1 </td></tr>-->
          <!--        <tr><td> Address Line 2 </td></tr>-->
          <!--        <tr><td> Address line 3 </td></tr>-->
          <!--        <tr><td> Address line 4 </td></tr>-->
          <!--    </table> -->
          <!--  </div>-->
          <!--</div>  -->
          <h1>BFC PUBLICATIONS PVT. LTD.</h1>
          <!-- <img src="https://bfcgroup.in//assets/asf_authorMail/profilePic/bfc_img1.png"> -->
          <!-- <iframe src="https://bfcgroup.in//assets/asf_authorMail/profilePic/bfc_img1.png" style="width: 100%;height: 100%;border: none;"></iframe> -->
          
          
          <div class="row">
            <div class="col-7 col-s-12">
              <div class="client-info">
               <h3 class="heading margin-0">Personal Details</h3>
               <table class="table table-auto-responsive no-border">
                <tr><th>Author Name</th> <td class="font_use"> <?php echo $author_name; ?> </td></tr>
                <tr><th>Father Name</th> <td class="font_use"><?php echo $father_name; ?></td></tr>
                <tr><th>Date of Birth </th> <td><?php echo $dob; ?></td></tr>
                <tr><th>Contact No</th> <td><?php echo $contact_no; ?></td></tr>
                <tr><th>Alternate contact no.</th> <td><?php echo $alternate_no; ?></td></tr>
                <tr><th>e-mail ID</th> <td><?php echo $email; ?></td></tr>
                <tr><th>Nominee Name</th> <td class="font_use"><?php echo $nominee_name; ?></td></tr>
                <tr><th>Correspondence  Address :</th> <td class="font_use"><?php echo $correspondence_address; ?></td></tr>
                <tr><th>Landmark</th> <td class="font_use"><?php echo $landmark; ?></td></tr>
                <tr><th>City</th> <td class="font_use"><?php echo $city; ?></td></tr>
                <tr><th>State</th> <td class="font_use"><?php echo $state; ?></td></tr>
                <tr><th>Pin Code </th> <td><?php echo $pincode; ?></td></tr>
                <tr><th>Country</th> <td class="font_use"><?php echo $country; ?></td></tr>
                
              </table>
              <h3 class="heading margin-0">BANK DETAILS</h3>
              <table class="table table-auto-responsive no-border">
               
                
                
                <tr><th>Account  holder’s name</th> <td class="font_use"><?php echo $account_holder_name; ?></td></tr>
                <tr><th>Account No.</th> <td><?php echo $account_no; ?></td></tr>
                <tr><th>Bank Name</th> <td class="font_use"><?php echo $bank_name; ?></td></tr>
                <tr><th>Branch</th> <td class="font_use"><?php echo $branch; ?></td></tr>
                <tr><th>IFSC Code</th> <td><?php echo $ifsc_code; ?></td></tr>
                <tr><th>Pan No.</th> <td><?php echo $pan_no; ?></td></tr>
              </table>

              <h3 class="heading margin-0">Book Details</h3>
              <table class="table table-auto-responsive no-border">
               
                
                
                <tr><th>Book Title</th> <td class="font_use"><?php echo $bookTitle; ?></td></tr>
                <tr><th>How would you like your name to appear on book?</th> <td class="font_use"><?php echo $name_appear_on_book; ?></td></tr>
                <tr><th>Manuscript Language</th> <td><?php echo $manuscript_language; ?></td></tr>
                <tr><th>Book Genre</th> <td><?php echo $book_genre; ?></td></tr>
                <tr><th>Number of images (If any)</th> <td><?php echo $number_of_images; ?></td></tr>
                <tr><th>Manuscript Status </th> <td><?php  if($manuscript_status==1){ echo 'Completed';}elseif ($manuscript_status==2) { echo 'Proof Read';  } ?></td></tr>
                <tr><th>Book Size  </th> <td><?php if($book_size==1){ echo '5"x8"';}elseif ($book_size==2) {
                  echo '6"x9"';  }elseif ($book_size==3) { echo '8.5"x11"';  } ?></td></tr>
                </table>
                
                <h3 class="heading margin-0">Cover details</h3>
                <table class="table table-auto-responsive no-border">
                 
                  
                  
                  <tr><th>Synopsis </th> <td class="font_use"><?php echo $synopsis; ?></td></tr>
                  <tr><th>Blurb  </th> <td class="font_use"><?php echo $blurb; ?></td></tr>
                  <tr><th>Author Bio</th> <td class="font_use"><?php echo $author_bio; ?></td></tr>
                  
                </table>



              </div>  
            </div>
            
          </div>  
          
          
        </body>
        </html>

