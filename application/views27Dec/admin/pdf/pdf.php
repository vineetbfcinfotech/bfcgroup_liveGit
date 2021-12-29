




<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

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
    

        <h3 class="heading margin-0">Package Details</h3>
    
    <div class="row">
  <div class="col-7 col-s-12">
    <div class="client-info">
        <h4>Author's Information</h4>
        <table class="table table-auto-responsive no-border">
        <tr><th>Name</th> <td> <?php echo $name; ?> </td></tr>
        <tr><th>Email</th> <td><?php echo $email; ?></td></tr>
        <tr><th>Mobile</th> <td><?php echo $mobile; ?></td></tr>
		</table>
		<h4>Package Information</h4>
		 <table class="table table-auto-responsive no-border">
		 
		<?php  
		$amount_40 = (40 / 100) * $total_amount; 
		$amount_20 = (20 / 100) * $total_amount; 
		?>
		
        <tr><th>Package Name</th> <td> <?php echo $package_name; ?>  </td></tr>
		<tr><th>Book Type</th> <td> <?php echo $book_type; ?>  </td></tr>
		<?php if($full_payment_disc == 'def'){ ?>
      <tr><th>40% Booking Amount</th> <td> <?php echo 'Rs. '.$amount_40; ?>  </td></tr>
		<tr><th>40% After 15 Day's</th> <td> <?php echo 'Rs. '.$amount_40; ?>  </td></tr>
		<tr><th>20% After 30 Day's</th> <td> <?php echo 'Rs. '.$amount_20; ?>  </td></tr>
    <?php }elseif ($full_payment_disc == 'full') { ?>
      	<tr><th>Full Payment</th> <td> <?php echo 'Rs. '.$total_amount_popup_pdf; ?>  </td></tr>
    <?php }else if($full_payment_disc == 'inst'){?>
      <tr><th>Booking Amount</th> <td> <?php echo 'Rs. '.$booking_amount_popup_pdf; ?>  </td></tr>
		<tr><th>After 15 Day's</th> <td> <?php echo 'Rs. '.$booking_f_amount_popup_pdf; ?>  </td></tr>
		<tr><th>After 30 Day's</th> <td> <?php echo 'Rs. '.$booking_s_amount_popup_pdf; ?>  </td></tr>
    <?php } ?>
		
		
		<tr><th>Package Value </th> <td> <?php echo 'Rs. '.$package_value; ?></td></tr>
		<?php if($discount==0){}else{
		
		$new_width = ($discount / 100) * $package_value; ?>

		<tr><th>Discount</th> <td> <?php echo 'Rs. '.$new_width; ?></td></tr>
		<?php }?>
		
		<tr><th>GST (18%)</th> <td> <?php echo 'Rs. '.$gst; ?></td></tr>
        <tr><th>Total Amount</th> <td> <?php echo 'Rs. '.$total_amount; ?></td></tr>
        <?php if ($cost_of_additional_copy != 0 && $gross_amt != 0) { ?>
         <tr><th>Cost of additional copy</th> <td> <?php echo 'Rs. '.$cost_of_additional_copy; ?></td></tr>
         <tr><th>Gross Amount</th> <td> <?php echo 'Rs. '.$gross_amt; ?></td></tr>
       <?php } ?>
        </table>
    </div>  
  </div>
  <div class="col-5 col-s-12 img-small">
  </div> 
</div>  
 
        <h3 class="heading margin-top-0">Services</h3>
 
 <div class="row">
  <div class="col-12 col-s-12">
       <table class="table table-auto-responsive table-content">
        <thead>
            <tr style=" text-align: justify; text-align-last: justify;">
                <th>Service Name</th>
                <th>Sub Service Name</th>
                      
            </tr>
        </thead>
           
        <tbody>
            <?php //print_r($service); 
                 $sub_services = explode(",", $sub_services);
                foreach ($sub_services as $sub_service_data) {
                $this->db->select('tblpackagesubservices.subservice_name , tblpackageservices.service_name');
                $this->db->where('tblpackagesubservices.id' ,$sub_service_data);
                $this->db->from('tblpackagesubservices');
                $this->db->join('tblpackageservices', 'tblpackageservices.id = tblpackagesubservices.serviceid');
                $result = $this->db->get();
                $data_sub_service =$result->row_array();
                //print_r($data_sub_service); ?>

            <tr style=" text-align: justify; text-align-last: justify;">
                
                <td><?php if ($service_name == $data_sub_service['service_name']) {
               
                }else{
                   $service_name = $data_sub_service['service_name'];
                   echo $service_name;
                }  ?></td>
                <td><?php if ($data_sub_service['subservice_name'] == 'Format Editing') {
                  if ($number_of_pages) {
                   echo $data_sub_service['subservice_name'].'('.$number_of_pages.')';
                  }else{
                    echo $data_sub_service['subservice_name'];
                  }
                }elseif ($data_sub_service['subservice_name'] == 'Complimentary Author Copies') {
                 if ($complimentry_copies) {
                   echo $data_sub_service['subservice_name'].'('.$complimentry_copies.')';
                  }else{
                    echo $data_sub_service['subservice_name'];
                  }
                }elseif ($data_sub_service['subservice_name'] == 'Additional Author Copies - Order at Subsidised Price') {
                  if ($complimentry_copies) {
                    echo $data_sub_service['subservice_name'].'('.$additional_author_copy.')';
                   }else{
                     echo $data_sub_service['subservice_name'];
                   }
                 }elseif ($data_sub_service['subservice_name'] == 'Number of Pages Allowed') {
                 if ($number_of_pages) {
                   echo $data_sub_service['subservice_name'].'('.$number_of_pages.')';
                  }else{
                    echo $data_sub_service['subservice_name'];
                  }
                } elseif ($data_sub_service['subservice_name'] == 'Color Pages') {
                  if ($number_of_pages) {
                    echo $data_sub_service['subservice_name'].'('.$color_pages.')';
                   }else{
                     echo $data_sub_service['subservice_name'];
                   }
                 }elseif ($data_sub_service['subservice_name'] == 'Paper Type') {
                 if ($paper_type_sc) {
                   echo $data_sub_service['subservice_name'].'('.$paper_type_sc.')';
                  }else{
                    echo $data_sub_service['subservice_name'];
                  }
                }elseif ($data_sub_service['subservice_name'] == 'Book Size') {
                 if ($book_size_sc) {
                   echo $data_sub_service['subservice_name'].'('.$book_size_sc.')';
                  }else{
                    echo $data_sub_service['subservice_name'];
                  }
                }elseif ($data_sub_service['subservice_name'] == 'Lamination') {
                 if ($lamination_sc) {
                   echo $data_sub_service['subservice_name'].'('.$lamination_sc.')';
                  }else{
                    echo $data_sub_service['subservice_name'];
                  }
                }elseif ($data_sub_service['subservice_name'] == 'Book Cover') {
                 if ($book_cover_sc) {
                   echo $data_sub_service['subservice_name'].'('.$book_cover_sc.')';
                  }else{
                    echo $data_sub_service['subservice_name'];
                  }
                }else{
                    echo $data_sub_service['subservice_name'];
                } ?></td>
                
            </tr>
            <?php } ?>

        </tbody>
    </table>
  </div>
</div>
</body>
</html>

