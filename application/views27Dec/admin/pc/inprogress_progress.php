<?php init_head(); ?>
<div id="wrapper">
   <?php //init_clockinout(); ?>
   <style type="text/css">
    

   </style>
   <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                     <a href="<?= base_url('admin')?>"  class="btn mright5 btn-info pull-left display-block">
                     Back  </a>
                  </div>
                  <div class="clearfix"></div>
                  <hr class="hr-panel-heading" />
                  <table class="table dt-table scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                     <thead>
                        <tr role="row">
                           <th>Sr. No.</th>
                           <th>Acquisition Date</th>
                           <th>Author Name</th>
                           <th>ISBN</th>
                           <th>Manuscript</th>
                           <th>Mail MRP Suggestions</th>
                           <th>Package Cost (Inclu. GST)</th>
                           <th>Booking  Amount</th>
                           <th>1st installment(80%of package cost - Booking amount)</th>
                           <th>Final Payment (20% of total package cost)</th>
                           <th>Balance Amount</th>
                           <th>Printing Quotation</th>
                            <th>Book Received</th>
                            <th>Create Package pdf</th>
                           
                           <!--<th>KDP</th>
                           <th>Google Playbooks</th>
                           <th>Flipkart</th>
                           <th>Amazon</th>
                           <th>Kobo</th>
                           <th>Smashword</th>
                           <th>Snapdeal</th>
                           <th>Shopclues</th> -->

                        </tr>
                     </thead>
                     <tbody>
                        <?php $i = 1; foreach ($projects as  $value) { ?>
                         
                         
                           <tr>
                              <td><?php echo $i; ?></td>
                              <td><?= $value->lead_booking_amount_date ?></td>
                              <td><a href="<?php base_url();?>pip/<?php echo $value->id; ?>"><?php echo $value->lead_author_name; ?></a></td>
                              <td><?php if($value->lead_isbn_ebook || $value->lead_isbn_paperback){?> Received <?php }else{ ?> Pending <?php }?></td>
                              <td><?php if($value->lead_raw_ms){?> Received <?php }else{ ?> Pending <?php }?></td>
                              <!-- <td><?php if($value->mrp_approved_by_author){?> Received <?php }else{ ?> Pending <?php }?></td> -->
                              <td><?php if ($value->lead_final_mrp_suggestion) { ?>
                                  <a class="btn btn-primary btn-xs "  data-target="#myModal1<?= $i ?>" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal1<?= $i ?>">Mail Sent</a>
                             <?php }else{ ?>
                              <a class="btn btn-success btn-xs "  data-target="#myModal1<?= $i ?>" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#myModal1<?= $i ?>">Mail Send</a>
                            <?php } ?> </td>
                              <td><?php echo round($value->lead_package_cost); ?></td>
                              <td><?php echo round($value->lead_recived_booking_amount); ?></td>
                              <td><?php echo round($value->lead_first_installment); ?></td>
                              <td><?php echo round($value->lead_final_payment); ?></td>

                              <td><?php  $remaining = ($value->lead_package_cost-($value->lead_recived_booking_amount+ $value->first_installment_amount+ $value->final_installment_amount)) ; 
                              echo round($remaining); ?></td>
                              <td><?php if ($remaining==0.00) { 
                                if ($value->print_quotation_status==1) { ?>
                                   <a class="btn btn-success btn-xs " >Quotation Sent</a>
                                <?php }else{ ?>
                                   <a class="btn btn-primary btn-xs "  href="<?php base_url();?>print_quotation/<?php echo $value->id; ?>">Quotation Send </a>
                                <?php } ?>
                               
                             <?php }else{ ?>
                              Pending
                             <?php } ?></td>
                             <td>Pending</td>
                             <td>
                               <?php if ($value->lead_pdf_data) { ?>
                                <a class="btn btn-primary btn-xs " download="" href="<?php echo base_url();?>assets/authorMail/<?php echo $value->lead_pdf_data; ?>">View </a>
                                   
                                <?php }else{ ?>
                                  <a class="btn btn-success btn-xs " >File not Found</a>
                                <?php } ?>
                             </td>
                             <!--   <td>Pending</td>
                              <td>Pending</td>
                              <td>Pending</td>
                              <td>Pending</td>
                              <td>Pending</td>
                              <td>Pending</td>
                              <td>Pending</td>
                              <td>Pending</td> -->
                              
                           </tr>
                           <div id="myModal1<?= $i ?>" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">MRP Suggestion</h4>
      </div>
      <div class="modal-body ">
        
        <form > 
          <div class="row">
            <div class="col-md-12">
               
                <?php 
                $booktype=0;
                $book_size_check='';
                if($value->lead_book_size==1){ $book_size_check ='5x8';}elseif ($value->lead_book_size==2) {
                $book_size_check = '6x9';  }elseif ($value->lead_book_size==3) {  $book_size_check ='8.5x11';  } 
                
                if($value->lead_book_type=='paperback'){
                if($value->lead_book_pages <= 72){
                if($book_size_check =='5x8'){
                
                $booktype=0.605;
                }else if($book_size_check =='6x9'){
                $booktype=0.718;
                
                }else if($book_size_check =='8x11'){
                $booktype=0.85;
                
                }else{}  
                }else if($value->lead_book_pages >= 73 && $value->lead_book_pages <=96){
                if($book_size_check =='5x8'){
                
                $booktype=0.605;
                }else if($book_size_check =='6x9'){
                $booktype=0.718;
                
                }else if($book_size_check =='8x11'){
                $booktype=0.85;
                
                }else{}
                }else if($value->lead_book_pages >= 97 && $value->lead_book_pages <=108 ){
                if($book_size_check =='5x8'){
                $booktype=0.533;
                }else if($book_size_check =='6x9'){
                $booktype=0.602;
                }else if($book_size_check =='8x11'){
                $booktype=0.74;
                }else{}
                }else if($value->lead_book_pages >= 109 && $value->lead_book_pages <=128 ){
                if($book_size_check =='5x8'){
                $booktype=0.533;
                }else if($book_size_check =='6x9'){
                $booktype=0.602;
                }else if($book_size_check =='8x11'){
                $booktype=0.70;
                }else{}
                }else if($value->lead_book_pages >= 129 && $value->lead_book_pages <=148 ){
                if($book_size_check =='5x8'){
                $booktype=0.460;
                }else if($book_size_check =='6x9'){
                $booktype=0.573;
                }else if($book_size_check =='8x11'){
                $booktype=0.68;
                }else{}           
                }else if($value->lead_book_pages >= 149 && $value->lead_book_pages <=192 ){
                if($book_size_check =='5x8'){
                $booktype=0.460;
                }else if($book_size_check =='6x9'){
                $booktype=0.573;
                }else if($book_size_check =='8x11'){
                $booktype=0.62;
                }else{}
                }else if($value->lead_book_pages >= 193 && $value->lead_book_pages <=216 ){
                if($book_size_check =='5x8'){
                $booktype=0.424;
                }else if($book_size_check =='6x9'){
                $booktype=0.537;
                }else if($book_size_check =='8x11'){
                $booktype=0.62;
                }else{}
                }else if($value->lead_book_pages >= 217 && $value->lead_book_pages <=252 ){
                
                if($book_size_check =='5x8'){
                $booktype=0.424;
                }else if($book_size_check =='6x9'){
                $booktype=0.537;
                }else if($book_size_check =='8x11'){
                $booktype=0.59;
                }else{}
                }else if($value->lead_book_pages >= 253 && $value->lead_book_pages <=256 ){
                
                if($book_size_check =='5x8'){
                $booktype=0.424;
                }else if($book_size_check =='6x9'){
                $booktype=0.537;
                }else if($book_size_check =='8x11'){
                $booktype=0.59;
                }else{}
                }else if($value->lead_book_pages >= 257 && $value->lead_book_pages <=276 ){
                
                if($book_size_check =='5x8'){
                $booktype=0.413;
                }else if($book_size_check =='6x9'){
                $booktype=0.515;
                }else if($book_size_check =='8x11'){
                $booktype=0.59;
                }else{}
                }else if($value->lead_book_pages >= 277 && $value->lead_book_pages <=320 ){
                if($book_size_check =='5x8'){
                $booktype=0.413;
                }else if($book_size_check =='6x9'){
                $booktype=0.515;
                }else if($book_size_check =='8x11'){
                $booktype=0.57;
                }else{}
                }else if($value->lead_book_pages >= 321 && $value->lead_book_pages <=352 ){
                
                if($book_size_check =='5x8'){
                $booktype=0.405;
                }else if($book_size_check =='6x9'){
                $booktype=0.500;
                }else if($book_size_check =='8x11'){
                $booktype=0.57;
                }else{}
                }else if($value->lead_book_pages >= 353 && $value->lead_book_pages <=384 ){
                
                if($book_size_check =='5x8'){
                $booktype=0.405;
                }else if($book_size_check =='6x9'){
                $booktype=0.500;
                }else if($book_size_check =='8x11'){
                $booktype=0.56;
                }else{}
                }else if($value->lead_book_pages >= 385 && $value->lead_book_pages <=432 ){
                
                if($book_size_check =='5x8'){
                $booktype=0.405;
                }else if($book_size_check =='6x9'){
                $booktype=0.500;
                }else if($book_size_check =='8x11'){
                $booktype=0.55;
                }else{}
                }else if($value->lead_book_pages >= 433 && $value->lead_book_pages <=492 ){
                
                if($book_size_check =='8x11'){
                $booktype=0.54;
                }if($book_size_check =='5x8'){
                $booktype=0.405;
                }else if($book_size_check =='6x9'){
                $booktype=0.500;
                }else{
                
                }
                }else if($value->lead_book_pages >= 493 && $value->lead_book_pages <=548 ){
                
                if($book_size_check =='8x11'){
                $booktype=0.54;
                }if($book_size_check =='5x8'){
                $booktype=0.405;
                }else if($book_size_check =='6x9'){
                $booktype=0.500;
                }else{
                
                }
                }else if($value->lead_book_pages >=549 ){
                if($book_size_check =='8x11'){
                $booktype=0.54;
                }if($book_size_check =='5x8'){
                $booktype=0.405;
                }else if($book_size_check =='6x9'){
                $booktype=0.500;
                }else{
                
                }
                }else{}
                
                $production_cost = $value->lead_book_pages * $booktype + 8.5;
                $prodsubpercentage = ($production_cost*15)/100;
                $subsidisedPrice = $production_cost + $prodsubpercentage;

                $recommendedPrice = $production_cost*2.5;
                $brfmrppercent  = ($recommendedPrice * 15)/100;
                $othermrppercent  = ($recommendedPrice * 50)/100;
                $setbfcprice = ($recommendedPrice-($production_cost + $brfmrppercent))*85/100;
                $setohterprice = ($recommendedPrice-($production_cost + $othermrppercent))*85/100;
               
                }
                ?>
                <!-- <input type="hidden" name="hidden_id" class="hidden_id" value="<?= $value->id;?>"> -->
                <div style="text-align: inherit;">
                  <h4 class="heading margin-0" style="background-color:#70bed4">Book And Print Specifications</h4>
                   <p><b>Book Size  </b><span id="book_size<?= $value->id;?>"><?php if($value->lead_book_size==1){ echo  '5"x8"';}elseif ($value->lead_book_size==2) {
                  echo  '6"x9"';  }elseif ($value->lead_book_size==3) { echo  '8.5"x11"';  } ?></span></p>
                     <p><b>Book Type  </b><span id="book_type<?= $value->id;?>"><?php echo "(".$value->lead_book_type.")"; ?></span></p>
                     <p><b>Cover Paper Type  </b><span id="cover_paper_type<?= $value->id;?>"><?php if($value->paper_type==1){ echo "(".'Creamy'.")";}elseif ($value->paper_type==2) { echo "(".'White'.")";  }?></span></p>
                     <p><b>Paper Lamination(Suggested)  </b><span id="paper_lamination<?= $value->id;?>"><?php if($value->lamination==1){ echo "(".'Gloss'.")";}elseif ($value->lamination==2) { echo "(".'Matte'.")";  }?></span></p>
                     <p><b>Interior Paper Type  </b><span id="interior<?= $value->id;?>"><?php if($value->book_interior==1){ echo "(".'Black'.")";}elseif ($value->book_interior==2) { echo "(".'White'.")";  }?></span></p>
                     
                     <p><b>Total paper Count  </b><span id="total_pages<?= $value->id;?>"><?php echo $value->lead_book_pages; ?></span></p>
                      
                      <?php if($value->lead_book_type=='paperback') { ?>

                        <h4 class="heading margin-0" style="background-color:#70bed4">Costing and Minimum MRP - Paperback</h4>
                        <p><b>Production Cost(Printing Cost1)  </b><span id="production_cost<?= $value->id;?>">₹<?=  number_format($production_cost, 2); ?></span><input type="hidden" id="production_cost_val<?= $value->id;?>"  value="<?= $production_cost; ?>"></p>
                      <p><b>Author Subsidized Cost (Cost at which the Author can procure extra copies)  </b><span id="author_subsidized<?= $value->id;?>"> ₹<?= number_format($subsidisedPrice, 2); ?></span></p>
                      <p><b>minimum MRP (2.5 times the production cost )   ₹</b><span id="minimum_mrp<?= $value->id;?>"><?= number_format($recommendedPrice, 2); ?></span></p>
                      <h4 class="heading margin-0" style="background-color:#70bed4">Price Recommendations</h4>
                        
                      <p><b>MRP for indian point distibution   ₹</b><span id="mrp_indian_point<?= $value->id;?>">
                        <input type="text" name="paperback_mrp" class="paperback_mrp" data-id="<?= $value->id;?>" value="<?php     if (!empty($value->lead_final_mrp_suggestion)) { $recommendedPrice = $value->lead_final_mrp_suggestion; }else{
                  $recommendedPrice = $recommendedPrice;
                 } ?><?= number_format($recommendedPrice, 2); ?>">
                        <input type="hidden" name="paperback_mrp_data" class="paperback_mrp_data" data-id="" value="">
                      </span></p>
                      <p>(Rupees 50 shipping charges applicable)</p>
                      <h4 class="heading margin-0" style="background-color:#70bed4">Author Earning Calculation</h4>
                      <h5><b>Author Earning For Indian Distribution - Paperback </b> <b>Author Earnings </b> </h5>
                      <p><b>BFC Publications </b> <b><span id="bfc_pub<?= $value->id;?>"> ₹<?= number_format($setbfcprice, 2); ?></span></b> <b>MRP</b></p>
                      <p><b>Amazon and Flipkart </b> <b><span id="amazon_flip<?= $value->id;?>"> ₹<?= number_format($setohterprice, 2); ?></span></b> <b>MRP</b></p>


                       <h4 class="heading margin-0" style="background-color:#70bed4">Costing and Minimum MRP - eBook</h4>
                      <h4 class="heading margin-0" style="background-color:#70bed4">Price Recommendations</h4>
                        <p><b>MRP for eBook  </b>₹ <input type="text" name="ebook_mrp" id="ebook_mrp<?= $value->id;?>" class="ebook_mrp"></p>
                       <h4 class="heading margin-0" style="background-color:#70bed4">Author Earning Calculation</h4>
                         <h5><b>Author Earning For Indian Distribution - eBook </b> <b>Author Earnings </b> </h5>
                          <p><b>Amazon Kindle and Google Play  </b>₹ <input type="text" disabled name="ebook_mrp_for_amazon" class="ebook_mrp_for_amazon" style="border: none;"></p>
                     
                     <?php }else{ ?>

                      <h4 class="heading margin-0" style="background-color:#70bed4">Costing and Minimum MRP - eBook</h4>
                      <h4 class="heading margin-0" style="background-color:#70bed4">Price Recommendations</h4>
                        <p><b>MRP for eBook  </b>₹ <input type="text" name="ebook_mrp" class="ebook_mrp"></p>
                       <h4 class="heading margin-0" style="background-color:#70bed4">Author Earning Calculation</h4>
                         <h5><b>Author Earning For Indian Distribution - eBook </b> <b>Author Earnings </b> </h5>
                          <p><b>Amazon Kindle and Google Play  </b>₹ <input type="text" disabled name="ebook_mrp_for_amazon" class="ebook_mrp_for_amazon" style="border: none;"></p>
                    <?php  } ?>
                      
                     <!-- <p><b>Author Earning For Indian Distribution - eBook </b> <b> 75% </b> <b>MRP</b></p>-->
                      
             
           </div>
         </div>
         </div>
         <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-8">
           
       
        <a class="btn btn-success btn-xs send_mail_mrp" href="" data-id="<?= $value->id;?>">Send Mail</a>
          </div>
        </div>
        
        
      </form>
    </div>
    
  </div>

</div>
</div>
                           <?php $i++; } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript">
    $(".ebook_mrp").keyup(function(){  

       var mrp = parseInt($(this).val());
       var ebook_erning = (mrp - (mrp*0.70))*0.85;
       $('.ebook_mrp_for_amazon').val(ebook_erning.toFixed(2));
    }); 
    $(".paperback_mrp").keyup(function(){  

       var mrp = parseInt($(this).val());
       $('.paperback_mrp_data').val(mrp);
       var id = $(this).attr("data-id");
       var book_type = $('#book_type'+id).html();
       var total_pages = $('#total_pages'+id).html();
       var book_size = $('#book_size'+id).html();
       // alert(book_size);
       let booktype=0;
      if(book_type=='(paperback)'){
        
          if(total_pages <= 72){
            if(book_size =="5x8"){
              
              booktype=0.605
            }else if(book_size =="6x9"){
              booktype=0.718
              
            }else if(book_size =="8x11"){
              booktype=0.85
              
            }else{}
            
          }else if(total_pages >= 73 && total_pages <=96){
            if(book_size =="5x8"){
             
              booktype=0.605
            }else if(book_size =="6x9"){
              booktype=0.718
              
            }else if(book_size =="8x11"){
                booktype=0.85
                
              }else{}

          }else if(total_pages >= 97 && total_pages <=108 ){
            if(book_size =="5x8"){
              
              booktype=0.533
            }else if(book_size =="6x9"){
              booktype=0.602
             
            }else if(book_size =="8x11"){
              booktype=0.74
             
            }else{}

          }else if(total_pages >= 109 && total_pages <=128 ){
            if(book_size =="5x8"){
              
              booktype=0.533
            }else if(book_size =="6x9"){
              booktype=0.602
              
            }else if(book_size =="8x11"){
              booktype=0.70
              
            }else{}

          }else if(total_pages >= 129 && total_pages <=148 ){
              if(book_size =="5x8"){
               
                booktype=0.460
              }else if(book_size =="6x9"){
                booktype=0.573
               
              }else if(book_size =="8x11"){
                booktype=0.68
               
              }else{}
            
          }else if(total_pages >= 149 && total_pages <=192 ){
            if(book_size =="5x8"){
              
              booktype=0.460
            }else if(book_size =="6x9"){
              booktype=0.573
             
            }else if(book_size =="8x11"){
              booktype=0.62
              
            }else{}
          }else if(total_pages >= 193 && total_pages <=216 ){
            if(book_size =="5x8"){
              
              booktype=0.424
            }else if(book_size =="6x9"){
              booktype=0.537
              
            }else if(book_size =="8x11"){
              booktype=0.62
              
            }else{}

          }else if(total_pages >= 217 && total_pages <=252 ){
            
            if(book_size =="5x8"){
              
              booktype=0.424
            }else if(book_size =="6x9"){
              booktype=0.537
              
            }else if(book_size =="8x11"){
              booktype=0.59
              
            }else{}
          }else if(total_pages >= 253 && total_pages <=256 ){

            if(book_size =="5x8"){
              
              booktype=0.424
            }else if(book_size =="6x9"){
              booktype=0.537
              
            }else if(book_size =="8x11"){
              booktype=0.59
              
            }else{}
          }else if(total_pages >= 257 && total_pages <=276 ){
            
            if(book_size =="5x8"){
              
              booktype=0.413
            }else if(book_size =="6x9"){
              booktype=0.515
              
            }else if(book_size =="8x11"){
              booktype=0.59
              
            }else{}
          }else if(total_pages >= 277 && total_pages <=320 ){
            if(book_size =="5x8"){
             
              booktype=0.413
            }else if(book_size =="6x9"){
              booktype=0.515
             
            }else if(book_size =="8x11"){
              booktype=0.57
              
            }else{}
          }else if(total_pages >= 321 && total_pages <=352 ){
            
            if(book_size =="5x8"){
              
              booktype=0.405
            }else if(book_size =="6x9"){
              booktype=0.500
              
            }else if(book_size =="8x11"){
              booktype=0.57
              
            }else{}
          }else if(total_pages >= 353 && total_pages <=384 ){
            
            if(book_size =="5x8"){
             
              booktype=0.405
            }else if(book_size =="6x9"){
              booktype=0.500
              
            }else if(book_size =="8x11"){
              booktype=0.56
             
            }else{}
          }else if(total_pages >= 385 && total_pages <=432 ){
            
            if(book_size =="5x8"){
              
              booktype=0.405
            }else if(book_size =="6x9"){
              booktype=0.500
              
            }else if(book_size =="8x11"){
              booktype=0.55
              
            }else{}
          }else if(total_pages >= 433 && total_pages <=492 ){
            
            if(book_size =="8x11"){
              booktype=0.54
             
            }if(book_size =="5x8"){
             
              booktype=0.405
            }else if(book_size =="6x9"){
              booktype=0.500
             
            }else{
              
            }
          }else if(total_pages >= 493 && total_pages <=548 ){
            
            if(book_size =="8x11"){
              booktype=0.54
             
            }if(book_size =="5x8"){
              booktype=0.405
              
            }else if(book_size =="6x9"){
              booktype=0.500
              
            }else{

            }
          }else if(total_pages >=549 ){
            if(book_size =="8x11"){
              booktype=0.54
              
            }if(book_size =="5x8"){
              booktype=0.405
              
            }else if(book_size =="6x9"){
              booktype=0.500
              
            }else{

            }
          }else{}
            var production_cost = $('#production_cost_val'+id).val();
          
          var prodsubpercentage = (production_cost*15)/100
          var prodsubpercentagetot = production_cost + prodsubpercentage
          prodsubpercentagetot = parseFloat(prodsubpercentagetot).toFixed(2);
          var recommendedPrice = production_cost*2.5
// alert(production_cost);


          var brfmrppercent  = mrp * 0.15;
      
          var othermrppercent  = mrp * 0.50;
          var setbfcprice = parseInt(production_cost) + parseInt(brfmrppercent);
       
          var setbfcprice1 = mrp-setbfcprice;
   
          var setbfcprice2 = setbfcprice1*0.85;
                setbfcprice2 = parseFloat(setbfcprice2).toFixed(2);

          var setohterprice = (mrp-(parseInt(production_cost) + parseInt(othermrppercent)))*0.85;
          setohterprice = parseFloat(setohterprice).toFixed(2);
          $('#bfc_pub'+id).html('₹'+setbfcprice2);
          $('#amazon_flip'+id).html('₹'+setohterprice);
       
          
      }
    });   
    $(".send_mail_mrp").click(function(){

        var id = $(this).attr("data-id");

          var book_size = $('#book_size'+id).html();
         var book_type = $('#book_type'+id).html();
        // alert(book_type);
         var cover_paper_type = $('#cover_paper_type'+id).html();
         var paper_lamination = $('#paper_lamination'+id).html();
         var interior = $('#interior'+id).html();
         var total_pages = $('#total_pages'+id).html();
         if (book_type == '(paperback)') {
           var production_cost =  $('#production_cost'+id).html();
        var author_subsidized =  $('#author_subsidized'+id).html();
        var minimum_mrp =  $('#minimum_mrp'+id).html();
         var mrp_indian_point =  $('.paperback_mrp_data').val();
          var ebook_mrp =  $('#ebook_mrp'+id).val();
        var ebook_mrp_for_amazon = $('.ebook_mrp_for_amazon').val();
         var bfc_pub = $('#bfc_pub'+id).html();
         var amazon_flip = $('#amazon_flip'+id).html();
         var data_array = {'id':id,'book_size' :book_size,'book_type': book_type, 'cover_paper_type':cover_paper_type, 'paper_lamination':paper_lamination,'interior':interior, 'total_pages': total_pages , 'production_cost':production_cost, 'author_subsidized': author_subsidized, 'minimum_mrp': minimum_mrp , 'mrp_indian_point': mrp_indian_point, 'bfc_pub': bfc_pub, 'amazon_flip': amazon_flip, 'ebook_mrp_for_amazon':ebook_mrp_for_amazon, 'ebook_mrp': ebook_mrp}
       }else{
        var ebook_mrp =  $('.ebook_mrp').val();
        var ebook_mrp_for_amazon = $('.ebook_mrp_for_amazon').val();
       // alert(ebook_mrp);
       // alert(ebook_mrp_for_amazon);
        var data_array = {'id':id,'book_size' :book_size,'book_type': book_type, 'cover_paper_type':cover_paper_type, 'paper_lamination':paper_lamination,'interior':interior, 'total_pages': total_pages , 'ebook_mrp_for_amazon':ebook_mrp_for_amazon, 'ebook_mrp': ebook_mrp}
      
       }
      ;

// console.log(JSON.stringify(data_array));
//  alert(data_array);

      $.ajax({

      type: "POST",

      url: "<?php echo admin_url('pm_lead/mail_mrp_approved'); ?>",

      data: data_array, // <--- THIS IS THE CHANGE


    success: function(data){
      // alert('test');
      window.onload();

      // alert(data);
      // return false;
     

      
    }

  });
       
         
         
    })
</script>
<?php init_tail(); ?>
</body>
</html>