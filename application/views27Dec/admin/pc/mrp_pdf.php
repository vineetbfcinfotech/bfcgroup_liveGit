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

          <h1 style="text-align: center; color: #d0363f;">BFC PUBLICATIONS PVT. LTD.</h1>

          <div class="row">
            <div class="col-7 col-s-12">
              <div class="client-info">
               <h3 class="heading margin-0">Book And Print Specifications</h3>
               <table class="table table-auto-responsive no-border">
                <!-- <tr><th>Book Size</th> <td> <?php echo $author_name; ?> </td></tr> -->
                <tr><th>Book Size</th> <td> <?php echo $book_size; ?></td></tr>
                <tr><th>Book Type</th> <td><?php echo $book_type; ?></td></tr>
                <tr><th>Cover Paper Type</th> <td><?php echo $cover_paper_type; ?></td></tr>
                <tr><th>Paper Lamination(Suggested)</th> <td><?php echo $paper_lamination; ?></td></tr>
                <tr><th>Interior Paper Type</th> <td><?php echo $interior; ?></td></tr>
                <tr><th>Total paper Count</th> <td><?php echo $total_pages; ?></td></tr>
               
                
              </table>
              <?php  if ($book_type == '(paperback)') {?>
              <h3 class="heading margin-0">Costing and Minimum MRP - Paperback</h3>
              <table class="table table-auto-responsive no-border">
               
                
                
                <tr><th>Production Cost(Printing Cost)</th> <td><?php echo $production_cost; ?></td></tr>
                <tr><th>Author Subsidized Cost (Cost at which the Author can procure extra copies)</th> <td><?php echo $author_subsidized; ?></td></tr>
                <tr><th>minimum MRP (2.5 times the production cost)</th> <td><?php echo $minimum_mrp; ?></td></tr>
                
              </table>

              <h3 class="heading margin-0">Price Recommendations</h3>
              <table class="table table-auto-responsive no-border">
               
                
                
                <tr><th>MRP for indian point distibution</th> <td><?php echo $mrp_indian_point; ?></td></tr>
                <tr><th>(Rupees 50 shipping charges applicable)</th> <td></td></tr>
              
                </table>
                
                <h3 class="heading margin-0">Author Earning Calculation</h3>
                <table class="table table-auto-responsive no-border">



                <tr><th>Author Earning For Indian Distribution - Paperback </th> <th>Author Earnings</th></tr> 
                <tr><td>BFC Publications</td> <td><?php echo $bfc_pub; ?></td></tr>
                <tr><td>Amazon and Flipkart  </td> <td><?php echo $amazon_flip; ?></td></tr>

                <h3 class="heading margin-0">Costing and Minimum MRP - eBook</h3>
                <h4 class="heading margin-0" >Price Recommendations</h4>
             
                </table>
                 <table class="table table-auto-responsive no-border">
               
                <tr><th>MRP for eBook </th> <td><?php echo $ebook_mrp; ?></td></tr>
                <tr><th>Author Earning For Indian Distribution - eBook</th> <td>Author Earnings </td></tr>
              <tr><td>Amazon kindle and Google Play</td> <td><?php echo $ebook_mrp_for_amazon; ?></td></tr>
                </table>
              <?php }else{ ?>
                <h3 class="heading margin-0">Costing and Minimum MRP - eBook</h3>
                <h4 class="heading margin-0" >Price Recommendations</h4>
              <table class="table table-auto-responsive no-border">
               
                <tr><th>MRP for eBook </th> <td><?php echo $ebook_mrp; ?></td></tr>
                <tr><th>Author Earning For Indian Distribution - eBook</th> <td>Author Earnings </td></tr>
              <tr><td>Amazon kindle and Google Play</td> <td><?php echo $ebook_mrp_for_amazon; ?>%</td></tr>
                </table>
                 
            <?php   }?>
             



              </div>  
            </div>
            
          </div>  
          
          
        </body>
        </html>

