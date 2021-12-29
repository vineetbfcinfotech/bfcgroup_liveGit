<?php init_head(); ?>
<div id="wrapper">
   <?php //init_clockinout(); ?>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                     <a href="#"  class="btn mright5 btn-info pull-left display-block">
                     Back </a>
                  </div>
                  <?php if(!empty($this->session->flashdata('success'))) {?>
                   <div class="text-success text-center"><?php echo  $this->session->flashdata('success'); ?></div>
                <?php }else if(!empty($this->session->flashdata('error'))){?>
                   <div class="text-danger text-center"><?php echo  $this->session->flashdata('error'); ?></div>
                <?php } ?>
                <div class="clearfix"></div>
                <hr class="hr-panel-heading" />
                <table class="table dt-table scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
                  <thead>
                     <tr role="row">
                        <th>Sr. No.</th>
                        <th>Author Name</th>
                        <th>Book Name</th>
                           <!--<th>overtime <small>(Per Hour)</small>
                           </th>-->
                           <th>Mobile</th>
                           <th>Total Cost</th>
                           <th>Date</th>
                           <th>Status</th>
                           <!-- <th>Status</th> -->
                        </tr>
                     </thead>
                     <tbody>
                        <?php $i=1; foreach($projects as $project){ ?>
                           <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $project->author_name; ?></td>
                              <td>Dare to Dream</td>
                              <!--<td></td>-->
                              <td><?php echo $project->mobile; ?></td>
                              <td><?php echo $project->final_amount; ?></td>
                              <td><?php echo date('d-m-Y', strtotime($project->created_at)); ?></td>
                              <td>
                                 <?php 
                                 $CI=&get_instance();
                                 $authdata = $CI->getAuthorDataWithId($project->author_id);
                                 if(empty($authdata)){
                                    ?>
                                    <a href="javascript:void(0)" data-id="<?php echo $project->author_id; ?>" class="mrp-fixation" >MRP Fixation</a>
                                 <?php }else{ ?>
                                   <a href="javascript:void(0)" data-id="<?php echo $project->author_id; ?>" class="" >View & Edit</a>
                                <?php } ?>
                             </td>
                          </tr>
                          <?php $i++; } ?>
                        <!--
                           <td>Payed</td>
                           <td>Approved</td>
                        -->
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<div id="mrpFixationModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <form class="" method="post" name="" action="<?php echo admin_url('leadsdata/saveMrpFixation'); ?>">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">MRP Fixation</h4>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="auto-value">
                     </div>
                     <div class="form-group" app-field-wrapper="booktype">
                        <label for="booktype" class="control-label">Book Type</label>
                        <div class="dropdown bootstrap-select form-control">
                           <select required="" id="booktype" name="booktype" class="form-control selectpicker" tabindex="-98">
                              <option selected="" value="">Book Type</option>
                              <option value="ebook">eBook</option>
                              <option value="paperback">Paperback</option>
                           </select>
                        </div>
                     </div>
                     <div class="mrp-recommend-paperback" style="display:none;">
                        <div class="form-group" app-field-wrapper="booktype">
                           <label for="productioncost" class="control-label">Cost of Production</label>
                           <input type="text" id="productioncost" name="productioncost" class="form-control" value="100">
                        </div>
                        <div class="form-group" app-field-wrapper="booktype">
                           <label for="productioncostauthor" class="control-label">Cost of Production to Author</label>
                           <input type="text" id="productioncostauthor" name="productioncostauthor" class="form-control" readonly value="115">
                        </div>
                        <div class="form-group" app-field-wrapper="recommendmrp">
                           <label for="recommendmrp" class="control-label">Recommended MRP</label>
                           <input type="text" id="recommendmrp" readonly name="recommendmrp" class="form-control" value="288">
                        </div>
                        <h4>Author's Earnings</h4>
                        <hr>
                        <div class="form-group" app-field-wrapper="amazon">
                           <label for="amazon" class="control-label">Amazon & Flipkart</label>
                           <input type="text" id="amazon" readonly name="amazon" class="form-control" value="24.4375">
                        </div>
                        <div class="form-group" app-field-wrapper="booktype">
                           <label for="bfcpublication" class="control-label">BFC Publications</label>
                           <input type="text" id="bfcpublication" readonly name="bfcpublication" class="form-control" value="109.96875">
                        </div>
                        <div class="form-group" app-field-wrapper="booktype">
                           <label for="authorcost" class="control-label">Author's Subsidised Cost	</label>
                           <input type="text" id="authorcost" readonly name="authorcost" class="form-control" value="115"> 
                        </div>
                     </div>
                     <!-- eBook -->
                     <div class="mrp-recommend-ebook" style="display:none;">
                        <div class="form-group" app-field-wrapper="nopages">
                           <label for="nopages" class="control-label">Number of Pages</label>
                           <input type="text" id="nopages" name="nopages" class="form-control" value="100">
                        </div>
                        <div class="form-group" app-field-wrapper="booktype">
                           <label for="productioncostauthore" class="control-label">Recommended MRP</label>
                           <input type="text" id="productioncostauthore" name="productioncostauthore" readonly class="form-control" value="25">
                        </div>
                        <h4>Author's Earnings</h4>
                        <hr>
                        <div class="form-group" app-field-wrapper="kdp">
                           <label for="kdp" class="control-label">KDP & Google Play</label>
                           <input type="text" id="kdp" name="kdp" readonly class="form-control" value="6.375">
                        </div>
                        <div class="form-group" app-field-wrapper="bfcpublicationmrp" style="display:none;">
                           <label for="bfcpublicationmrp" class="control-label">BFC Publications</label>
                           <input type="text" id="bfcpublicationmrp" name="bfcpublicationmrp" class="form-control" readonly value="18.0625">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-success" >Submit</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $(document).ready(function(){
    $(".mrp-recommend-paperback").hide(500);
    $(".mrp-recommend-ebook").hide(500);
    
    $(".mrp-fixation").on("click", function(){
       var dataId = $(this).attr("data-id");
       $.ajax({
         type: "POST",
         url: "<?php echo admin_url('leadsdata/saveMrp'); ?>",
   			data: {'authorId': dataId}, // <--- THIS IS THE CHANGE
   			dataType: "html",
   			success: function(data){
   				$(".auto-value").html(data);
   				$("#mrpFixationModal").modal('show');
   			},
   			error: function() { alert("Error posting feed."); }
        });
    });
    
    $("#booktype").on("change", function(){
       if(this.value == "ebook"){
         $(".mrp-recommend-paperback").hide(500);
         $(".mrp-recommend-ebook").show(500);
         $(".mrp-recommend-paperback input").attr("disabled","true");
         $(".mrp-recommend-ebook input").removeAttr("disabled");
      }else if(this.value == "paperback"){
         $(".mrp-recommend-paperback").show(500);
         $(".mrp-recommend-ebook").hide(500);
         $(".mrp-recommend-ebook input").attr("disabled","true");
         $(".mrp-recommend-paperback input").removeAttr("disabled");
      }else{
         $(".mrp-recommend-paperback").hide(500);
         $(".mrp-recommend-ebook").hide(500);
      }
   });
    $("#productioncost").on("keyup", function(){
       var procost = this.value*100;
       var productioncostauthor = procost * 1.15;
       productioncostauthor = productioncostauthor/100;
       $("#productioncostauthor").val(productioncostauthor);
       $("#authorcost").val(productioncostauthor);
       var recommendmrp = productioncostauthor * 2.50;
		   //recommendmrp = recommendmrp;
		   var re50 = (recommendmrp*50)/100;
		   var re15 = (recommendmrp*15)/100;
		   var sec = productioncostauthor+re50;
		   var third = recommendmrp-sec;
		   var amazon = (third*85)/100;
		   var second = productioncostauthor+re15;
		   var last = recommendmrp-second;
		   var bfcpublication = (last*85)/100;
		   $("#amazon").val(amazon);
		   $("#recommendmrp").val(Math.round(recommendmrp));
		   $("#bfcpublication").val(bfcpublication);
		   //alert(bfcpublication);
     });
    
    $("#nopages").on("keyup", function(){
       var nopages = this.value*100;
       var productioncostauthore = (nopages*0.25)/100;
       $("#productioncostauthore").val(productioncostauthore);
       var b570 = (productioncostauthore*70)/100;
       var kdpear = ((productioncostauthore-b570)*85)/100;
       $("#kdp").val(kdpear);
       var b515 = (productioncostauthore*15)/100;
       var bfcear = ((productioncostauthore-b515)*85)/100;
       $("#bfcpublicationmrp").val(bfcear);
    });
 });
</script>
<?php init_tail(); ?>
</body>
</html>

