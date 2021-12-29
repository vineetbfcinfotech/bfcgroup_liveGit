<?php init_head_new(); ?>
<style>

button, input, select, textarea {
    color: black!import;
}
* {box-sizing: border-box}

/* Set height of body and the document to 100% */
body, html {
  height: 100%;
  margin: 0;
  font-family: Arial;
}

/* Style tab links */
.tablink {
  background-color: #555;
  color: white;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  font-size: 17px;
  width: 16%;
}

.tablink:hover {
  background-color: #777;
}

/* Style the tab content (and add height:100% for full page content) */
.tabcontent {
  color: white;
  display: none;
  padding: 100px 20px;
  height: 100%;
}

.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: #f9f9f9;
    color: black !important;
}
.table-striped > tbody > tr:nth-of-type(even) {
    background-color: #f9f9f9;
    color: black !important;
}
.no-margin {
   color: black !important;
}
#Home {background-color: #fffff;}
#News {background-color: #fffff;}
#Contact {background-color: #fffff;}
#About {background-color: #fffff;}
#Acquired {background-color: #fffff;}
#No_Category {background-color: #fffff;}
</style>
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
					<button class="tablink" onclick="openPage('ABBplus', this, 'black')">A,B,B+</button>
<button class="tablink" onclick="openPage('News', this, 'black')" id="defaultOpen">NP</button>
<button class="tablink" onclick="openPage('Contact', this, 'black')">C</button>
<button class="tablink" onclick="openPage('About', this, 'black')">Scrap</button>
<button class="tablink" onclick="openPage('Acquired', this, 'black')">Acquired</button>
<button class="tablink" onclick="openPage('No_Category', this, 'black')">No Category</button>

<div id="ABBplus" class="tabcontent">
  <h3>Home</h3>
  <div class="mytable">

                            <?php if (!empty($assignedleads_a_b)) { ?>
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
                                    <?php $co = 1; $con = 1; foreach ($assignedleads_a_b as $alllead) { ?>
									
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

<div id="News" class="tabcontent">
  <h3>NP</h3>
  <div class="mytable">

                            <?php if (!empty($assignedleads_np)) { ?>
                                 <!--<table id="example" class="display" style="width:100%">-->
                                 <table id="example34" class="table table-striped table-bordered" cellspacing="0" width="100%" height="50%">
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
                                    <?php $co = 1; $con = 1; foreach ($assignedleads_np as $alllead) { ?>
									
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

<div id="Contact" class="tabcontent">
  <h3>C</h3>
  <div class="mytable">

                            <?php if (!empty($assignedleads_c)) { ?>
                                 <!--<table id="example" class="display" style="width:100%">-->
                                 <table id="example35" class="table table-striped table-bordered" cellspacing="0" width="100%" height="50%">
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
                                    <?php $co = 1; $con = 1; foreach ($assignedleads_c as $alllead) { ?>
									
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

<div id="About" class="tabcontent">
  <h3>Scrap</h3>
  <div class="mytable">

                            <?php if (!empty($assignedleads_scrap)) { ?>
                                 <!--<table id="example" class="display" style="width:100%">-->
                                 <table id="example36" class="table table-striped table-bordered" cellspacing="0" width="100%" height="50%">
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
                                    <?php $co = 1; $con = 1; foreach ($assignedleads_scrap as $alllead) { ?>
									
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


<div id="Acquired" class="tabcontent">
  <h3>Acquired</h3>
  <div class="mytable">

                            <?php if (!empty($assignedleads_acquired)) { ?>
                                 <!--<table id="example" class="display" style="width:100%">-->
                                 <table id="example37" class="table table-striped table-bordered" cellspacing="0" width="100%" height="50%">
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
                                    <?php $co = 1; $con = 1; foreach ($assignedleads_acquired as $alllead) { ?>
									
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
<div id="No_Category" class="tabcontent">
  <h3>No Category</h3>
  <div class="mytable">

                            <?php if (!empty($assignedleads_noCategory)) { ?>
                                 <!--<table id="example" class="display" style="width:100%">-->
                                 <table id="example38" class="table table-striped table-bordered" cellspacing="0" width="100%" height="50%">
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
                                    <?php $co = 1; $con = 1; foreach ($assignedleads_noCategory as $alllead) { ?>
									
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

<script>
function openPage(pageName,elmnt,color) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = "";
  }
  document.getElementById(pageName).style.display = "block";
  elmnt.style.backgroundColor = color;
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <?php init_tail_new();
    ?>
   


 
</div>
</div>






<?php // echo count($assignedleads);exit; ?>
</body>
</html>