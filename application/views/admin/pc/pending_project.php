<?php init_head(); ?>
<div id="wrapper">
  <style type="text/css">
    .border-none{
      border:0px!important;
    }
  </style>
  <?php //init_clockinout(); ?>
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
           <th>Project Name</th>
           <th>Author Name</th>
           <th>PC</th>
           <th>Date of Acquisition</th>
           <th>Email</th>
           <th>Contact No.</th>
           <th>Package Type</th>
           <th>Send Welcome Mail & ASF</th>
           <th>View ASF</th>
           <th>Mail Agreement</th>
           <th>Upload Agreement</th>
           <th>Action</th>
           
         </tr>
       </thead>
       <tbody>
        <?php $i =1; foreach ($projects as $key => $value) { ?>
          
         
          <tr>
           <td><?= $i; ?></td>
          
           <td>Project_<?= $value->lead_author_name; ?></td>
           <td><?=  $value->lead_author_name; ?></td>
           <?php $pc =  $this->db->get_where('tblstaff',array('staffid'=>$value->assigned))->row(); ?>
           <td><?= $pc->firstname.' '.$pc->lastname ?></td>
           <td><?= $value->lead_booking_amount_date ?></td>
           <td><?= $value->email ?></td>
           <td><?= $value->phonenumber ?></td>
           <td><?= $value->lead_bookformat ?></td>
           <td>
            <?php if($value->send_asf_mail_status == 0){?>
              <a class="btn btn-success btn-xs "  data-target="#myModal1<?= $i ?>" data-toggle="modal" class="MainNavText MainNavText<?= $i ?>" id="MainNavHelp" 
            href="#myModal1<?= $i ?>">Mail Send </a>
            <?php }else{ 
             $earlier = new DateTime($value->asf_send_date);
             $c_date = date("Y-m-d");
             $later = new DateTime($c_date);
            //  $later = new DateTime("2010-07-09");
             
             $abs_diff = $later->diff($earlier)->format("%a");
            if ($abs_diff >= 7) { ?>
              <a class="btn btn-danger btn-xs "  data-target="#myModal1<?= $i ?>" data-toggle="modal" class="MainNavText MainNavText<?= $i ?>" id="MainNavHelp" 
              href="#myModal1<?= $i ?>">Mail Sent Successfully</a>
            <?php }else{ ?>
              <a class="btn btn-info btn-xs "  data-target="#myModal1<?= $i ?>" data-toggle="modal" class="MainNavText MainNavText<?= $i ?>" id="MainNavHelp" 
              href="#myModal1<?= $i ?>">Mail Sent Successfully</a>
            <?php } ?>
              


            <?php }?>
           
          </td>
            <td><?php if ($value->lead_asf_status == 1) { ?>
             <a class="btn btn-success btn-xs"  href="<?=base_url('assets/asf_authorMail/'.$value->asf_pdf_data)?>" target="_blank" >
               View ASF
             </a>
           <?php }else{ ?> 
            <a class="btn btn-default btn-xs" disabled>
             Not Received
           </a>
           
         <?php } ?>
       </td>
       <td>
         <?php if ($value->lead_asf_status == 1) {
           if($value->pdf_mail_agreement){ ?>
            <a class="btn btn-info btn-xs" data-target="#myModal<?= $value->id ?>" data-toggle="modal" class="MainNavText" id="MainNavHelp" 
            href="#myModal<?= $value->id ?>" >
              Agreement Send
            </a>
    <?php }else{ ?>
      <a class="btn btn-success btn-xs" data-target="#myModal<?= $value->id ?>" data-toggle="modal" class="MainNavText" id="MainNavHelp" 
            href="#myModal<?= $value->id ?>" >
             Agreement Send
          </a>
  <?php  } ?>
           
        <?php }else{ ?>  
         <a class="btn btn-default btn-xs" disabled  class="MainNavText" id="MainNavHelp">
            Agreement Sent
         </a>
       <?php } ?>
     </td>
     <td> 
      <?php if ($value->pdf_mail_agreement) { if (empty($value->final_mail_agreement_upload)) {
        ?> <form method="post" action="<?php echo base_url(); ?>admin/Pm_lead/upload_agreeent" enctype="multipart/form-data"> 
          <div class="form-group">
            <input type="hidden" name="hidden_id" id="hidden_id" class="hidden_id" value="<?php echo $value->id; ?>"/>
            <input type="file" class="from-control border-none" name="file">
            <button type="submit" id="submit_upload_agreement" class="btn btn-info" >Upload</button>
          </div>
        </form>
      <?php  }else{  ?>
       <a class="btn btn-prime btn-xs " >Uploaded</a>

     <?php } }else{ ?>
      <a class="btn btn-default btn-xs " disabled>Upload</a>
    <?php  } ?>
    
  </td>
  <td>
   <?php if ($value->final_mail_agreement_upload) { ?>
     <a class="btn btn-success btn-xs take_up_data"  data-id="<?= $value->id; ?>" data-tbl="<?= $value->tbltype; ?>" >
       Take Up
     </a>
     
   <?php }else{ ?> 
     <a class="btn btn-default btn-xs" disabled>
       Take Up
     </a>
   <?php } ?> 
 </td>
 
</tr>
<div id="myModal<?= $value->id ?>" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
   <?php $all_asf_data = $this->db->get_where('chorus_asf',array('lead_id'=>$value->id))->row(); ?>
   <!-- Modal content-->
   <?php $data['address']= $all_asf_data->asf_address;
   $from = new DateTime($all_asf_data->asf_dob);
   $to   = new DateTime('today');
   $data['dob'] = $from->diff($to)->y;
   $data['lead_booking_amount_date']= $value->lead_booking_amount_date; ?>
   <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Agreement</h4>
    </div>
    <div class="modal-body ">
     <form > 
      <div class="row" style="text-align: justify;">
       <div class="col-md-1"></div>
       <div class="col-md-10">
        <div class="form-group">
          <!-- *********  POPUP data for aggrement  ****************-->   
          <div style="text-align: center;">
             <img  src="<?php echo base_url();?>uploads/company/bfc_logo.jpg">
          </div>    
         


          <h2>Book Publishing and Distribution Agreement </h2>
          <p  > This agreement is entered into between  <?=  $value->lead_author_name; ?> of the address  <?=  $data['address']; ?>, <?=  $value->city; ?>, <?=  $value->state; ?>, <?=  $value->zip; ?>, aged about <?=  $data['dob']; ?> as of  <?= $data['lead_booking_amount_date'];?>, hereafter to be referred to as the “Author”, and BFC Publications Private Limited, having its office at CP-61, Viraj Khand, Gomti Nagar, Lucknow - 226010, to be referred to as “BFC Publications” from here on. </p>
          <p>Since the Author has written a book or work that is to be published through BFC Publications in consideration of the mutual promises listed in the agreement, both the parties agree as follows.</p>
          <h4>A)  Services to be offered by BFC Publications</h4>
          <p><b>Publishing Services:</b> There are several packages listed on BFC Publications’ website (<a href="www.bfcpublications.com">www.bfcpublications.com</a>) offering corresponding services that the Author can choose from depending upon his/her needs. The scope of this service covers the editorial process, cover designing, allotment of ISBN, copyright and marketing of the work/book.</p>

          <p><b>Distribution Services:</b> Post the selection of the publishing package and the publication of your work, BFC Publications will be the official distributor of the same, both online and offline.</p>

          <h4>B)  Author’s Obligations and Duties</h4>
          <p><b>Author Approval:</b> The Author shall receive from BFC Publications, a Package Confirmation Mail listing the services on offer under his/her shortlisted package. The Author needs to respond to this mail, acknowledging and approving the same.</p>

          <p><b>Payment:</b> The Author needs to clear the monetary obligations outstanding against BFC Publications, in line with the agreed-upon terms of payment. He/She also needs to inform BFC Publications about the payment made by sharing the receipt over mail.</p>

          <p><b>Author Submission Form (ASF):</b> The Author needs to submit with BFC Publications a duly filled ASF Form, based on which the agreement shall be drafted.</p>

          <p><b>Agreement:</b> The Author needs to enter into an Agreement with BFC Publications, thereby inking terms of engagement in black and white.</p>

          <p><b>Submission of Work/Manuscript:</b> The Author shall submit with BFC Publications, his/her work in an acceptable electronic format. (Formats other than Microsoft Word or rich text format should be approved in advance by the publisher.)</p>

          <p><b>Submission of Graphics/Illustrations:</b> The Author shall submit with BFC Publications, any graphics or illustrations that he/she needs to include in the book, in a clear, clean and acceptable electronic format. (Formats other than .JPG must be approved in advance by the publisher.)</p>

          <p><b>Other Information:</b> The Author shall select from the options provided by BFC Publications, such as the book interior, cover design, the format finalised for the text, etc., no later than a week of submission. Other information required to carry out the publishing work, like Bios, Blurbs, Preface, Acknowledgement, if any, must also be provided by the Author, when sought by BFC Publications.</p>

          <p><b>Responsibility of Content:</b> BFC Publications, under no circumstances, shall be legally responsible for the content of the book. All continued and unconditional responsibility pertaining to the content remains with the Author.</p>

          <h4>C)  Intellectual Property Rights</h4>

          <p>All intellectual property rights in the work, and other rights listed under the law of copyright shall remain with the Author. The rights granted to BFC Publications are non-exclusive, which means they are limited to printing, publishing, distributing and selling of the work.</p>

          <h4>D)  Payment </h4>
          <p>The Author or someone on his/her behalf needs to pay BFC Publications the package fee payable for the services to be availed. BFC Publications shall commence work on the book only upon the receipt of the package fee. The Author needs to ensure that the obligation is settled within the timeline listed in the Package Confirmation Mail, and as per the agreed terms. Any defaults or delays in the same shall be interpreted as breach of contract leading to suspension of services.</p>

          <h4>E)  Author’s Rights</h4>

          <p>1)  The Author has the exclusive right to retain the work.</p>
          <p>2)  The rights granted to BFC Publications are non-exclusive, which means they are limited to printing, publishing, distributing and selling the work. The Author is entitled to transfer these rights to any other publisher, provided the monetary obligation outstanding against BFC Publications is satisfied. The purview of these rights shall, however, depend upon the publishing package and the underlying services selected by the Author.</p>

          <h4>F)  BFC Publications’ Rights and Duties</h4>
          <p><b>Publishing and Distribution:</b> BFC Publications reserves the right to choose the distribution channels that are to be leveraged to market the Author’s work. BFC Publications and the entities in its distribution channels are entitled to determine if excerpts of the Author’s work are to be made available online, and to what extent. On occasions, these excerpts may include entire chapters or at times even twenty-five pages or more.</p>

          <p><b>Versions and ISBNs:</b> BFC Publications is entitled to introduce additional versions of your work, as it is to be made available in multiple formats. BFC Publications also reserves the right to terminate and reissue individual ISBNs for each selected format.</p>

          <p><b>Possession of Submitted Material:</b> BFC Publications may retain in its possession the materials submitted by the Author, and is not obligated to return or store such materials. The Author also grants BFC Publications the right to use the statistical information pertaining to the sale of his/her work.</p>

          <p><b>Hosting:</b> The Author grants BFC Publications the right to copy, reproduce, warehouse, host, store, use, transmit and distribute tangible, electronic copies of the work in any current or future methods of technology to fulfil the services listed under this Agreement.</p>

          <p><b>Publicity:</b> The Author grants BFC Publications the right to publicize information about the work, or the Author, including his/her biographical sketch, as well as excerpts of the Author’s work at BFC Publications’ discretion, for advertising, promotion and marketing purposes.</p>

          <p><b>PR Gimmicks:</b> For PR purposes, the Author grants BFC Publications the consent and right to use his/her name, voice, photograph, image, likeness, distinctive appearance, gestures, or mannerisms.</p>

          <p><b>Showcasing:</b> The Author grants BFC Publications the right to showcase his/her work on BFC Publications' website or its partners’ or contractors’ websites, which are contractually bound with BFC Publications, to facilitate promotion, marketing, distribution, and sales of such works.</p>

          <p><b>Giveaways:</b>  The Author grants BFC Publications the right to send free review copies to the members of media, including, but not limited to, editors, college newspapers, bloggers, professors, television, internet, and radio commentators, and other potential book reviewers, based on BFC Publications' discretion, for promotion purposes.</p>

          <p><b>Services/Opportunities/Items:</b> BFC Publications reserves the right to discontinue a promotional service/opportunity/item or substitute it with another equivalent of equal or greater value on behalf of the Author, with prior notification to him/her.</p>

          <p><b>Confidentiality:</b> BFC Publications shall ensure that the content provided by the Author is kept confidential. Neither BFC Publications nor any of its associated parties shall be involved in any act that compromises the security of the content before, during and after the work is published.</p>

          <p><b>Deadline:</b> BFC Publications shall provide the Author the finished work in all formats based on the shortlisted package no later than sixty working days, starting from the date of submission of manuscript. This period does not include any loss of days, in whole or in part, caused due to the Author’s delayed response to inquiries or requests made by BFC Publications. The sixty-day deadline also does not include delays due to an Act of God/ natural calamity or delays due to government regulations owing to pandemics and public holidays.</p>

          <p><b>Format and Design:</b> BFC Publications shall format and publish the work as specified by the Author. BFC Publications shall follow the Author’s suggestions for custom-designed cover while simultaneously complying with its own interior and cover design guidelines.</p>

          <p><b>Copyright:</b> BFC Publications shall include a copyright notice in each copy of the work, in accordance with the Author’s instructions, subject to its inclusion in the package.</p>

          <h4>G)  Term and Termination</h4>

          <p><b>Term:</b> This Agreement comes to force from the Effective Date mentioned in it, and is to remain in force until terminated by one or both parties.</p>

          <p><b>Termination: </b> The following are the provisions and reasons for the termination of this Agreement:</p>

          <p>1)  BFC Publications’ services stand terminated once each service is approved by the Author as completed. For instance, upon the receipt of the Author’s email approving the final cover design file that particular service comes to an end. The Author can, however, choose to avail additional services based on BFC Publications' offers, which would begin and terminate based on the choices made by the Author.</p>

          <p>2)  The Distribution Services shall continue to stay in effect until this Agreement is terminated.</p>

          <p>3)  Both, the Author and BFC Publications, can mutually agree and have the right to terminate this Agreement at any stage, in writing.</p>

          <p>4)  The Author can inform BFC Publications about breach of contract if the latter has not complied with any of the duties or obligations listed in this Agreement, or vice versa. Either party can inform the other about the breach of terms. This agreement can be terminated if the issue is not sorted or rectified within 30 days.</p>

          <h4>H)  Refunds</h4>

          <p>Refunds shall apply only to the Publication Services provided by BFC Publications, depending upon the time at which the Agreement is terminated and the amount of work completed.</p>

          <p>1)  The Author is only entitled to a refund if he/she has adhered to all the payment terms mentioned in the Package Confirmation Mail. Non-adherence of the agreed payment terms makes the Author ineligible for any refunds, regardless of the publishing stage. </p>

          <p>2)  The Author shall be eligible for a refund of  75 percent of the package cost mentioned in the Package Confirmation Mail , if he/she fails to submit his/her work with BFC Publications within three months, starting from the day of payment of booking amount.</p>

          <p>3)  After the submission of the manuscript or three months from the Effective Date, whichever comes earlier, the Author is not eligible for any refunds, regardless of the publishing stage. </p>

          <p>BFC Publications shall make its best effort to ensure that refunds are processed within thirty days of the termination of this Agreement.</p>

          <h4>I)  Representations and Warranties</h4>

          <p>Both, the Author and BFC Publications, undertake that the Author can enter into this Agreement and that there are no other contractual obligations preventing the Author from signing this Agreement. The Author will be wholly and completely responsible for the content of the work and shall ensure that the work is not illegal, unlawful or it does not contain any objectionable content. BFC Publications is not liable to any third party or other person or entity, for the work, regardless of whether BFC Publications had any knowledge or could have reasonably known of any illegal, unlawful, or objectionable content in the work. The Author also agrees to indemnify and compensate for any harm or loss occurring to BFC Publications in case of any legal proceedings with a third party regarding copyright infringement or action on grounds of illegality as mentioned.</p>

          <h4>J)  Disclaimer of Warranties</h4>

          <p><b>Sales Not Guaranteed:</b> BFC Publications cannot and does not guarantee sales of any of the author's work. BFC Publications makes no guarantees or promises as to the minimum success of the services or the quantum of sale which may result from any or all of the services. </p>

          <p><b>General Disclaimer:</b> Except for any Warranties or Representations explicitly mentioned in this Agreement, BFC Publications makes no other warranty and explicitly disclaims all other warranties, conditions or representations (express or implied, oral or written) with respect to the services, or any part thereof.</p>

          <p><b>Third Party Fault:</b> BFC Publications is not responsible for retrieving the work from or for any sales of the work in the possession of an entity other than BFC Publications. BFC Publications is not liable for delays, errors, or non-performance of services caused by any of BFC Publications' vendors or suppliers, like, the failure of a third party to (a) timely remove the Author’s work from circulation, following BFC Publications' or the Author’s notice to do so, and (b) the failure of any third party to timely update any changes to the work.</p>

          <h4>K)  Indemnification</h4>

          <p>The Author agrees to indemnify and hold BFC Publications and its distribution partners harmless against any legal claims connected to the breach of the representations and warranties, or the Author’s breach of this Agreement, or regarding any claims of Intellectual Property Ownership. BFC Publications agrees to indemnify the Author against any claims connected to any matter inserted into the work by BFC Publications if the Author did not provide BFC Publications with it. </p>

          <p>BFC Publications may retain payments due to the Author until its claims of indemnity have been satisfied or settled.</p>

          <h4>L)  Limitation of Liabilities/Remedies</h4>

          <p>1)  In no event shall BFC Publications or any of its affiliates be liable under this Agreement to the Author or any other person or entity for any consequential, incidental, indirect, exemplary, special or punitive damages, regardless of whether we could anticipate such damages or advised the Author about them. </p>

          <p>2)  In no event shall BFC Publications or its affiliates total liability arising out of or related to this agreement, exceed the total amount the Author paid to BFC Publications under this Agreement.</p>


          <h4>M)  General Provisions</h4>

          <p><b>Legal Proceedings:</b>  The Courts in Lucknow shall have exclusive jurisdiction to decide on matters arising out of this Agreement. If there is any dispute, it should be referred to an Arbitrator appointed by both the parties. If the Author and BFC Publications cannot agree on one Arbitrator, both the parties shall appoint one Arbitrator each, who will appoint a third Arbitrator so that a panel of three can adjudicate. All arbitration proceedings shall be conducted in the English language, in accordance with the Arbitration and Conciliation Act, 1996 and the place of arbitration shall be Lucknow, Uttar Pradesh, India. </p>

          <p><b>Entire Agreement:</b> This Agreement, including the Package Confirmation Mail and Schedule A, is all that governs the terms between the Author and BFC Publications. If there are any changes to the terms, they have to be made in writing and agreed upon by both parties. </p>

          <p><b>Notices:</b> All notices to BFC Publications can be sent by email to support@bfcpublications.com and all notices to the Author can be sent to any of the Author's registered email addresses that have been used to communicate with BFC Publications in the past.</p>

          <p><b> Assignment:</b> The Author cannot assign or transfer any of the rights, or duties in this Agreement to anyone else. BFC Publications may transfer some of its rights to another related entity to comply with its duties as per this Agreement.</p>

          <p><b>Force Majeure:</b> Both, the Author and BFC Publications shall not be liable or responsible to each other or deemed to have defaulted under this Agreement for failure or delay in fulfilling or performing any terms of this Agreement if the delay is due to an Act of God or out of control of the parties. They shall be extended by a period equal to any period of force majeure (circumstance beyond the control of the Publisher) that prevents both the parties from performing such obligations. </p>

          <p><b>Survival:</b> Even after the termination of this Agreement, all the clauses of this agreement shall survive until explicitly terminated by the parties via an Agreement, signed by both parties.</p>

          <h4>Schedule A</h4>

          <p><b>1)  Author Earnings</p></b>

          <p><b>A)  Earnings from Paperback/Hardcover Sales</b></p>

          <p><b>BFC Publications:</b> (MRP - (Production Cost + 15% of MRP as Transaction Charges))*85%</p>

          <p><b>Others:</b> (MRP - (Production Cost + 50% of MRP as Distribution Charges))*85%</p>

          <p><b>B)  Earnings from eBook Sales</b></p>

          <p><b>Others:</b>  (MRP – (70% of MRP as Distribution Fee))*85%</p>

          <p><b>C)  Disbursal Timings of Author Earnings</p></b>

          <p>Author Earnings shall be determined monthly and paid to the Author within sixty (60) days, after the conclusion of the month in which the amount was earned, provided the earnings aggregate to or exceed Rs. 1,000. If the earnings do not touch Rs. 1,000 by the end of the ongoing calendar year, they shall be paid to the Author within sixty (60) days of the conclusion of that calendar year.</p>

          <p>If the Author has a bank account outside India, the minimum required cumulative earnings need to touch Rs. 10,000, following which they shall be transferred to the Author as per the above mentioned turn-around-time. Additionally, a wire transfer fee based on the bank will be applicable, which shall be borne by the Author.</p>

          <p><b>D)  Mode of Transfer</b></p>

          <p>Author earnings shall be transferred in his/her bank account through electronic fund transfer (NEFT/RTGS). For this purpose, the Author needs to provide BFC Publications the relevant bank details.</p>

          <p><b>E)  Tax Withholding and PAN Number</b></p>

          <p>All Author earnings shall be subjected to applicable tax requirements. The Author shall provide BFC Publications all information and documentation necessary for compliance of tax provisions, including his/her PAN number.</p>

          <p>In case the Author fails to provide BFC Publications the proper documentation and information, or fails to comply with the provisions of this Agreement, BFC Publications holds the right to withhold the amounts owed to him/her, or any money that needs to be deducted or withheld in compliance with the tax code or other governing laws. The Author shall have no right to seek reimbursement from BFC Publications, for such withholdings or the payments made to the authorities concerned.</p>

          <p><b>2) Pricing</b></p>

          <p>The Author shall set the default and maximum price of the work over and above the suggested MRP threshold recommended by BFC Publications after taking into account the cost of printing and distribution. BFC Publications may ask the Author via electronic mail to modify the price of the paperback, hardcover, or eBook, should costs change or market conditions warrant.</p>

          <p><b>3) Author Discounts</b></p>

          <p>The Author shall be entitled to purchase copies of his/her work at the subsidised rates displayed on the author dashboard of BFC Publications' website at the time of purchase. All payments in this regard must be made in advance.</p>

          <p><b>Acknowledgement</b></p>

          <p>I have read and fully understand BFC Publications’ Book Publishing and Distribution Agreement and the associated Package Confirmation Mail that details the list of service deliverables and the payment terms for the publishing of my book. I will follow the guidelines and the terms of payment agreed upon by me in this agreement.</p>

        </div>
      </div>
      <div class="col-md-1"></div>
    </div>
    <div class="row">
     <div class="col-md-6"></div>
     <div class="col-md-6">
      <button type="submit" id="" class="btn btn-info submit_asf_pdf" data-id="<?= $value->id; ?>"
       data-asf_id="<?= $all_asf_data->asf_id; ?>" >Send</button>
     </div>
   </div>
   
   
 </form>
</div>

</div>

</div>
</div>

<div id="myModal1<?= $i ?>" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mail To Author</h4>
      </div>
      <div class="modal-body ">
        <form > 
          <div class="row">
            <div class="col-md-12">
             <p>Hi <b><?= $value->lead_author_name;?></b></p>
             <p>Thanks for short listing BFC as your publisher. It's great to finally have you on board.</p>
             <p>Please allow me to start by introducing myself, I am Gaurav and I have been nominated by BFC Publications to guide your project, due to which, we shall remain in constant touch over the next few weeks.</p>
             <p>The idea behind this is to ensure that there is uninterrupted to-and-fro communication so you are up-to-date with the progress made at every turn.</p>
             <p>Just so you know, we are obligated to get your book published within minimum 45 days, starting the day after manuscript submission (No Commitments for final date)
             I also want you to know that we are open to suggestions moving forward. So if you'd like to talk about anything, or have a feedback, feel free to reach out through mail or over the phone.</p>
             <p>Also, listed below is a hyperlink to the Author Submission Form (<a href="<?php echo base_url();?>admin/Asf_controller/index/<?php echo $value->email;?>">ASF</a>) that is to be filled at your end. This will help us learn more about you, your book, and most importantly your bank details wherein your royalty earnings shall be credited.</p>
             <p>Looking forward to hear from you.</p>
             <p>Happy Writing!</p>
             To Fill ASF <a href="<?php echo base_url();?>admin/Asf_controller/index/<?php echo $value->email;?>">Click Here</a> 
             
             
           </div>
         </div>
         <div class="row">
          <div class="col-md-6"></div>
          <div class="col-md-6">
           
            <a class="btn btn-success btn-xs " href="<?= admin_url('pm_lead/sendmail/'.$value->id.'/'.$value->tbltype);?>">Send Mail</a>
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
<script>
  $('document').ready(function(){


    
    

 


    $(".submit_asf_pdf").click(function() {
      
     var id = $(this).attr("data-id");
     var asf_id =  $(this).attr("data-asf_id");
   
      $.ajax({
        
       url: "<?php echo base_url(); ?>admin/Pm_lead/send_agreement",
       method: 'POST',
       data: {
        id: id,
        asf_id: asf_id,
      },
              success: function (data) // A function to be called if request succeeds
              {
                // console.log(data);return false;
                if(data == "1"){
                     alert("Mail Send Successfully"); 
                     console.log(data)
                 location.reload();
                // return false;
               }else{
                 alert("Mail not Send");
                 location.reload();
                // return false;
                
               }
               
               
               
               
               
             }
           });
    });
    

  });
  $(".take_up_data").click(function() {
    
   var id = $(this).attr("data-id");
   var tbl = $(this).attr("data-tbl");
   $.ajax({
    
     url: "<?php echo base_url(); ?>admin/Pm_lead/take_up",
     method: 'POST',
     data: {
      id: id,
      tbl:tbl
    },
              success: function (data) // A function to be called if request succeeds
              {
              // alert(data);
                  //console.log(data);
                  location.reload();
                  //window.load();

                  
                  
                }
              });
 });
</script>

<?php init_tail(); ?>
</body>
</html>