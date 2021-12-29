<?php init_head(); ?>
<!-- all lead code by Shourabh -->
<style>
    .bootstrap-select {
        width: 100% !important;
    }
    .bootstrap-select .dropdown-toggle .filter-option-inner-inner {
        text-transform: none !important;
    }
    .dynamicservice ul {
        margin-left: 20px !important;
    }
    .rowpackage {
        margin-bottom: 10px;
    }
    #servicesd ul ul {
        margin-left: 15px;
    }
</style>
<?php init_clockinout(); //print_r($packages);exit; 
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <!-- <a href="#"  class="btn mright5 btn-info pull-left display-block">
                     Deal Acquired
                     </a> -->
                            <h2 class="text-center">Other Package Details</h2>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <form class="create_package_form" id="create_package_form" method="post" name="create_package_form" action="<?php echo admin_url('Leads/createOtherPackage'); ?>">
                            <?php //print_r($leadData);exit; 
                            ?>
                            <input type="hidden" value="" class="inputcountvalue">
                           
                            <div class="pdf_attachment" style="display:none; ">
                                <b>Click here to
                                    <a href="<?php echo base_url("assets/authorMail/") . $packageData->pdf_data; ?>" target="_blank" style="">
                                        download
                                    </a>
                                    PDF Attachment</b>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" app-field-wrapper="author_name">
                                        <label for="author_name" class="control-label">Author Name</label>
                                        <input type="text" id="author_name" name="author_name" class="form-control" readonly value="<?= $leadData->lead_author_name ?>">
                                        <input type="hidden" id="assigned_by_id" name="assigned_by_id" class="form-control" value="<?= $leadData->assigned; ?>">
                                        <input type="hidden" id="create_lead" name="craete_package" class="form-control" value="<?= $leadData->craete_package; ?>">
                                       
                                        <input type="hidden" name="author_id" id="author_id" value="<?php echo $leadId; ?>"> <input type="hidden" name="url" value="<?= $url; ?>">
                                      </div>
                                    <div class="form-group" app-field-wrapper="mobile"><label for="mobile" class="control-label">Mobile</label>
                                        <input type="text" id="mobile" name="mobile" class="form-control" readonly value="<?= $leadData->phonenumber ?>">
                                    </div>
                                    <div class="form-group" app-field-wrapper="email">
                                        <label for="email" class="control-label">Email</label>
                                        <input type="text" id="email" name="email" class="form-control" value="<?= $leadData->email ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="msstatus" class="control-label">Manuscript Status</label>
                                        <select id="msstatus" name="msstatus" class="form-control" tabindex="-98">
                                            <option selected="" value="">Select Manuscript Status</option>
                                            <option value="inprocess" <?php if ($leadData->lead_author_msstatus == 'inprocess') {
                                                                            echo "selected";
                                                                        } ?>>In-process</option>
                                            <option value="completed" <?php if ($leadData->lead_author_msstatus == 'completed') {
                                                                            echo "selected";
                                                                        } ?>>Completed</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="gender" class="control-label">Package Details</label>
                                        <select id="package_details" name="package_details" class="form-control" tabindex="-98">
                                            <option selected="" value="">Select Package Details</option>
                                            <option value="1" <?php if ($leadData->lead_package_detail == 1) {
                                                                    echo "selected";
                                                                } ?>>Standard</option>
                                            <option value="2" <?php if ($leadData->lead_package_detail == 2) {
                                                                    echo "selected";
                                                                } ?>>Customized</option>
                                            <option value="3" <?php if ($leadData->lead_package_detail == 3) {
                                                                    echo "selected";
                                                                } ?>>Standard Customized</option>
                                        </select>
                                    </div>
                                    <div class="form-group book_type">
                                        <input type="hidden" value="" name="book_type_value" id="book_type_value">
                                        <label for="book_type" class="control-label ">Book Format</label>
                                        <select id="book_type" name="book_type" class="form-control book_type_standard_and_custum" tabindex="-98">
                                            <option value="">Select Book Format</option>
                                            <option value="ebook" <?php if ($leadData->lead_book_type == 'ebook') {
                                                                        echo "selected";
                                                                    } ?>>eBook</option>
                                            <option value="paperback" <?php if ($leadData->lead_book_type == 'paperback') {
                                                                            echo "selected";
                                                                        } ?>>Paperback</option>
                                        </select>
                                    </div>
                                    <div class="form-group book_type_standard_custum" style="display: none;">
                                        <label for="book_type" class="control-label">Book Format</label>
                                        <select id="book_type_sc" name="book_type" class="form-control " tabindex="-98">
                                            <option selected="" value="">Select Book Format</option>
                                            <option value="ebook" <?php if ($leadData->lead_book_type == 'ebook') {
                                                                        echo "selected";
                                                                    } ?>>eBook</option>
                                            <option value="paperback" <?php if ($leadData->lead_book_type == 'paperback') {
                                                                            echo "selected";
                                                                        } ?>>Paperback</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="package_value_by_id" name="package_name_data" value="">
                                    <div class="package_data" style="<?php if (isset($leadData->lead_package_detail)) { ?>display:block; <?php } else { ?>display:none;<?php } ?>">
                                        <div class="form-group">
                                            <label for="package" class="control-label">Package Name</label>
                                            <select id="package" name="package_name" class="form-control package_ddl" tabindex="-98" style="<?php if (isset($leadData->lead_package_detail) && $leadData->lead_package_detail == 1) { ?>display:block; <?php } else { ?>display:none;<?php } ?>">
                                                <option selected="" value="">Select Package</option>
                                                <option value="essential" <?php if ($leadData->lead_package_name == 'essential') {
                                                                                echo "selected";
                                                                            } ?>>ESSENTIAL</option>
                                                <option value="regular" <?php if ($leadData->lead_package_name == 'regular') {
                                                                            echo "selected";
                                                                        } ?>>REGULAR</option>
                                                <option value="superior" <?php if ($leadData->lead_package_name == 'superior') {
                                                                                echo "selected";
                                                                            } ?>>SUPERIOR</option>
                                                <option value="premium" <?php if ($leadData->lead_package_name == 'premium') {
                                                                            echo "selected";
                                                                        } ?>>PREMIUM</option>
                                                <option value="elite" <?php if ($leadData->lead_package_name == 'elite') {
                                                                            echo "selected";
                                                                        } ?>>ELITE</option>
                                                <option value="rapid" <?php if ($leadData->lead_package_name == 'rapid') {
                                                                            echo "selected";
                                                                        } ?>>RAPID</option>
                                            </select>
                                            <input type="text" id="package_name" name="package_name" class="form-control package_name" value="<?php echo $leadData->lead_package_name; ?>" readonly style="<?php if (isset($leadData->lead_package_detail) && $leadData->lead_package_detail == 2) { ?>display:block; <?php } else { ?>display:none;<?php } ?>">
                                        </div>
                                    </div>
                                    <div class="package_data_for_s_c" style="<?php if (isset($leadData->lead_package_detail)) { ?>display:block; <?php } else { ?>display:none;<?php } ?>">
                                        <div class="form-group">
                                            <label for="package" class="control-label">Package Name</label>
                                            <select id="package_value_sc" name="package_name_data_value" class="form-control package_data_sc" tabindex="-98" style="">
                                                <option selected="" value="">Select Package</option>
                                                <option value="essential" <?php if ($leadData->lead_package_name == 'essential') {
                                                                                echo "selected";
                                                                            } ?>>CUSTOM ESSENTIAL</option>
                                                <option value="regular" <?php if ($leadData->lead_package_name == 'regular') {
                                                                            echo "selected";
                                                                        } ?>>CUSTOM REGULAR</option>
                                                <option value="superior" <?php if ($leadData->lead_package_name == 'superior') {
                                                                                echo "selected";
                                                                            } ?>>CUSTOM SUPERIOR</option>
                                                <option value="premium" <?php if ($leadData->lead_package_name == 'premium') {
                                                                            echo "selected";
                                                                        } ?>>CUSTOM PREMIUM</option>
                                            <?php if ($leadData->lead_book_type == 'paperback') { ?>
                                                                             <option value="elite" <?php if ($leadData->lead_package_name == 'elite') {
                                                                            echo "selected";
                                                                        } ?>>CUSTOM ELITE</option>
                                                                          <option value="rapid" <?php if ($leadData->lead_package_name == 'rapid') {
                                                                            echo "selected";
                                                                        } ?>>CUSTOM RAPID</option>
                                                                      <?php  } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="testing">
                                        <?php if ($leadData->lead_package_detail) {
                                            if ($leadData->lead_package_detail == 2) { ?>
                                                <div class="row customize_services " style="<?php if (isset($leadData->lead_package_detail) && $leadData->lead_package_detail == 2) { ?>display:block; <?php } else { ?>display:none;<?php } ?>">
                                                    <?php
                                                    $service = explode(", ", $leadData->lead_service);
                                                    $result = $this->leads_model->getserviesedit($service);
                                                    //print_r($result);exit;
                                                    $i = 1;
                                                    foreach ($result as $data) {    ?>
                                                        <div class="row-add<?= $i; ?>" data-id="<?= $i; ?>">
                                                            <div class="all-services servicess_data_<?= $i; ?>">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="services" class="control-label">Services:</label>
                                                                        <select class="form-control services" id="services<?= $i; ?>" class="services" name="services[]" style="width: 100%; <?php if ($leadData->lead_package_detail == 1) { ?>display:none; <?php } else { ?>display:block;<?php } ?>">
                                                                            <option value="">--Select--</option>
                                                                            <?php $this->db->from('tblpackagesubservices a');
                                                                            $this->db->join('tblpackageservices b', 'b.id = a.serviceid');
                                                                            $this->db->where('a.book_type', $leadData->lead_book_type);
                                                                            $this->db->where('a.packageid', 2);
                                                                            $this->db->group_by('a.serviceid');
                                                                            $query = $this->db->get();
                                                                            foreach ($query->result() as $services_data) {  ?>
                                                                                <option value="<?= $services_data->id; ?>" <?php if ($data->id == $services_data->id) {                                echo "selected";                                                                                                                            } ?>><?php echo $services_data->service_name; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="sub_services_data_<?= $i; ?> sub_services<?= $i; ?>   subservices">
                                                                <?php $sub_services = explode(", ", $leadData->lead_sub_service);
                                                                //print_r($sub_services);
                                                                $subservices = $this->leads_model->sub_servicesedit($data->id, $sub_services, $leadData->lead_book_type);
                                                                 if ($subservices[0]->subservice_name == 'Format Editing'|| $subservices[1]->subservice_name =='Proofreading') { ?>
                                                                        <div class="col-md-12">
                                                                        <div class="form-group">
                                                                        <label for="services" class="control-label">No of pages:</label>
                                                                        <input type="text" readonly id="increment" name="increment" onkeyup='myFunctiondatacomplimentry("<?= $data->id; ?>","<?=$data->cost ?>");'  class="form-control" value="<?= $leadData->lead_book_pages;?>" >
                                                                        </div>
                                                                        </div>
                                                                   <?php }
                                                                foreach ($subservices as $subservice) {
                                                                    if ($subservice->subservice_name == 'Complimentary Author Copies') { ?>
                                                                        <div class="col-md-12">
                                                                        <div class="form-group">
                                                                        <label for="services" class="control-label">No of Copy:</label>
                                                                        <input type="text" readonly id="complimentry_copies" name="complimentry_copies" onkeyup='myFunctiondatacomplimentry("<?= $data->id; ?>","<?=$data->cost ?>");'  class="form-control" value="<?= $leadData->complimentry_copies;?>" >
                                                                        </div>
                                                                        </div>
                                                                    <?php }else if($subservice->subservice_name == 'Additional Author Copies - Order at Subsidised Price'){ ?>
                                                                       <div class="col-md-12">
                                                                       <div class="form-group">
                                                                       <label for="services" class="control-label">No of Copy:</label>
                                                                       <input type="text"  id="additional_author_copy_customized" name="additional_author_copy_customized"   class="form-control" value="<?= $leadData->additional_author_copy ?>" <?php if ($leadData->additional_author_copy ){ echo 'readonly';} ?> >
                                                                       </div>
                                                                       </div>
                                                                   <?php }else{}
                                                                 ?>
                                                                    <div class="col-md-5">
                                                                        <label for="sub_service" class="control-label">Sub Services:</label>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="cost" class="control-label">Cost:</label>
                                                                    </div>
                                                                    <div class="col-md-3" style="min-height: 30px;">
                                                                    </div>
                                                                    <div class="main-data">
                                                                        <div class="col-md-5">
                                                                            <div class="form-group sub_service_data">
                                                                                <label class="checkbox-inline">
                                                                                 <?php  if ($subservice->subservice_name == 'Complimentary Author Copies') { 
                                                                                  $total_cost_copy =  $leadData->complimentry_copies*100; ?>
                                                                        <input type="checkbox" class="subservice_check" name="sub_services[]" data-cost="<?= $total_cost_copy; ?>" data-page-cost="<?= $total_cost_copy; ?>" data-id="<?= $subservice->id; ?>" value="<?= $subservice->id; ?>" data-name="<?= $subservice->subservice_name; ?>" checked="" onclick="return false;">
                                                                   <?php }else if($subservice->subservice_name == 'Format Editing' || $subservice->subservice_name == 'Proofreading'){ 
                                                                        $total_cost_page =  $leadData->lead_book_pages*11; ?>
                                                                        <input type="checkbox" class="subservice_check" name="sub_services[]" data-cost="<?= $total_cost_page; ?>" data-page-cost="<?= $total_cost_page; ?>" data-id="<?= $subservice->id; ?>" value="<?= $subservice->id; ?>" data-name="<?= $subservice->subservice_name; ?>" checked="" onclick="return false;">
                                                                   <?php }else{ ?>
                                                                      <input type="checkbox" class="subservice_check" name="sub_services[]" data-cost="<?= $subservice->cost; ?>" data-page-cost="<?= $subservice->cost; ?>" data-id="<?= $subservice->id; ?>" value="<?= $subservice->id; ?>" data-name="<?= $subservice->subservice_name; ?>" checked="" onclick="return false;">
                                                                   <?php } ?>
                                                                                    <?= $subservice->subservice_name; ?>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <div class="dropdown bootstrap-select form-control">
                                                                                <?php  if ($subservice->subservice_name == 'Complimentary Author Copies') { 
                                                                                  $total_cost_copy =  $leadData->complimentry_copies*100; ?>
                                                                        <input type="text" id="cost[]" name="cost" class="form-control page_cost_data" readonly="" value="<?= $total_cost_copy; ?>">
                                                                   <?php }else if($subservice->subservice_name == 'Format Editing' || $subservice->subservice_name == 'Proofreading'){ 
                                                                        $total_cost_page =  $leadData->lead_book_pages*11; ?>
                                                                        <input type="text" id="cost[]" name="cost" class="form-control page_cost_data" readonly="" value="<?= $total_cost_page; ?>">
                                                                   <?php }else if($subservice->subservice_name == 'Hindi Typing'){  $hindi_cost =  $leadData->lead_book_pages*$subservice->cost; ?>
                                                                        <input type="text" id="cost[]" name="cost" class="form-control page_cost_data" readonly="" value="<?= $hindi_cost; ?>">
                                                                   <?php }else if($subservice->subservice_name == 'English Typing'){  $english_cost =  $leadData->lead_book_pages*$subservice->cost; ?>
                                                                        <input type="text" id="cost[]" name="cost" class="form-control page_cost_data" readonly="" value="<?= $english_cost; ?>">
                                                                   <?php }else if($subservice->subservice_name == 'Urdu Typing'){  $urdu_cost =  $leadData->lead_book_pages*$subservice->cost; ?>
                                                                        <input type="text" id="cost[]" name="cost" class="form-control page_cost_data" readonly="" value="<?= $urdu_cost; ?>">
                                                                   <?php }else{
                                                                   if($subservice->subservice_name == 'Number of Pages Allowed'){ ?>
                                                                    <input type="text" id="cost[]" name="cost" class="form-control page_cost_data" readonly="" value="<?= $leadData->lead_book_pages; ?>">
                                                                  <?php }else{ ?>
                                                                    <input type="text" id="cost[]" name="cost" class="form-control page_cost_data" readonly="" value="<?= $subservice->cost; ?>">
                                                                 <?php }  } ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3" style="min-height: 70px;">
                                                                        <?php if($subservice->subservice_name == "Color Pages"){ ?>
                                                                        <div class="form-group">
                                                                             <?php $color_pages = $leadData->color_pages; ?>
                                                                             <input type="text" id="color_pages_customized" name="color_pages_customized" class="form-control resetbook_cover_c" style="" value="<?php echo $color_pages ?>" readonly>
                                                                        </div>
                                                                        <?php }else{ ?>
                                                                        <?php } ?>
                                                                        
                                                                           <?php if($subservice->subservice_name == "Paper Type"){ ?>
                                                                        <div class="form-group">
                                                                             <?php $paper_type = explode("/",$subservice->subServiceNameValue); ?>
                                                                             <select id='paper_type_c' name='paper_type_c' class="form-control resetbook_cover_c">
                                                                                <option>---select----</option>
                                                                             <?php foreach ($paper_type as $key => $value) { ?>
                                                                              <option <?php if ($value == $leadData->paper_type_sc) { 
                                                                               echo("selected"); } ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                                            <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <?php } if($subservice->subservice_name == "Book Size"){ ?>
                                                                        <div class="form-group">
                                                                             <?php $book_size = explode("/",$subservice->subServiceNameValue); ?>
                                                                             <select id='book_size_c' name='book_size_c' class="form-control resetbook_cover_c">
                                                                                <option>---select----</option>
                                                                             <?php foreach ($book_size as $key => $value) { ?>
                                                                              <option <?php if ($value == $leadData->book_size_sc) { 
                                                                               echo("selected"); } ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                                            <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <?php } if($subservice->subservice_name == "Lamination"){ ?>
                                                                        <div class="form-group">
                                                                             <?php $Lamination = explode("/",$subservice->subServiceNameValue); ?>
                                                                             <select id='lamination_c' name='lamination_c' class="form-control resetbook_cover_c">
                                                                                <option>---select----</option>
                                                                             <?php foreach ($Lamination as $key => $value) { ?>
                                                                              <option <?php if ($value == $leadData->lamination_sc) { 
                                                                               echo("selected"); } ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                                            <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <?php }if($subservice->subservice_name == "Book Cover"){ ?>
                                                                        <div class="form-group">
                                                                             <?php $book_cover = explode("/",$subservice->subServiceNameValue); ?>
                                                                             <select id='book_cover_c' name='book_cover_c' class="form-control">
                                                                                <option>---select----</option>
                                                                             <?php foreach ($book_cover as $key => $value) { ?>
                                                                              <option <?php if ($value == $leadData->book_cover_sc) { 
                                                                               echo("selected"); } ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                                            <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <?php }  ?>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div style="cursor:pointer;background-color:red;float:right;margin-right: 15px;" class="remove_fieldss btn btn-info">Remove</div>
                                                        </div>
                                                    <?php $i++;
                                                    }  ?>
                                                </div>
                                            <?php }
                                            if ($leadData->lead_package_detail != 2) { ?>
                                                <div class="row customize_services remove_attr_from" style="<?php if ($leadData->lead_package_detail == 2) { ?>display:none; <?php } else { ?>display:block;<?php } ?>">
                                                    <div class="all-services">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="services" class="control-label">Services:</label>
                                                                <select class="form-control services" id="" name="services[]" style="width: 100%; <?php if ($leadData->lead_package_detail == 1) { ?>display:none; <?php } else { ?>display:block;<?php } ?>">
                                                                    <option value="">--Select--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="sub_services">
                                                    </div>
                                                </div>
                                            <?php }
                                        } else { ?>
                                            <div class="row customize_services 1" style="">
                                                <div class="all-services">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="services" class="control-label">Services:</label>
                                                            <select class="form-control services" id="" name="services[]" style="width: 100%; <?php if ($leadData->lead_package_detail == 1) { ?>display:none; <?php } else { ?>display:block;<?php } ?>">
                                                                <option value="">--Select--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="sub_services">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div id="getservicewithcost">
                                    </div>
                                    <br>
                                    <div class="col-md-12 addmore " style="<?php if (isset($leadData->lead_package_detail) && $leadData->lead_package_detail == 1) { ?>display:none; <?php } else { ?>display:block;<?php } ?>">
                                        <div class="form-group text-right" app-field-wrapper="add_more_roles">
                                            <label for="add_more_team_role" class="control-label addmore666"> Add More</label>
                                            <button style="margin-left: 4px;" type="button" class="btn btn-info add_field_button" id="add_more_team_role666">
                                                <i class="fa fa-plus plus666"></i><i class="fa fa-minus minus666" style="display:none;"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" app-field-wrapper="package_value">
                                        <label for="package_value" class="control-label">Package Value</label>
                                        <input type="text" id="package_value" name="package_value" class="form-control" placeholder="0" readonly value="<?php echo $leadData->lead_packge_value; ?>">
                                    <input type="hidden" id="lead_ori_packge_value" name="lead_ori_packge_value" class="form-control" placeholder="0" value="<?php echo $leadData->lead_ori_packge_value; ?>" readonly>
                                    </div>
                                    <div class="form-group" app-field-wrapper="discount">
                                        <label for="discount" class="control-label">Discount(%)</label>
                                        <input type="text" id="discount" name="discount" class="form-control" placeholder="0" value="<?php echo $leadData->lead_packge_discount; ?>">
                                    </div>
                                    <div class="form-group" app-field-wrapper="less_package_value">
                                        <label for="less_package_value" class="control-label">Gross Package</label>
                                        <input type="text" id="less_package_value" name="less_package_value" class="form-control" placeholder="0" readonly value="<?php echo $leadData->lead_lesspckg_value; ?>">
                                    </div>
                                    <div class="form-group" app-field-wrapper="gst">
                                        <label for="gst" class="control-label">GST Amount (18%)</label>
                                        <input type="text" id="gst" name="gst" placeholder="18%" class="form-control" value="<?php echo $leadData->lead_packg_gst; ?>" readonly>
                                    </div>
                                    <div class="form-group" app-field-wrapper="total_amount">
                                        <label for="total_amount" class="control-label">Package Cost</label>
                                        <input type="text" id="total_amount" name="total_amount" class="form-control" placeholder="0" value="<?php echo $leadData->lead_packg_totalamount; ?>" readonly>
                                    </div> 
                                      <?php if (isset($leadData->cost_of_additional_copy) && ($leadData->cost_of_additional_copy != '') ) {?>
                                      <!-- <div class="form-group cost_additional_copy" app-field-wrapper="" >
                                        <label for="" class="control-label">Cost of additional copy</label>
                                        <input type="text" id="cost_of_additional" name="cost_of_additional" class="form-control" placeholder="0" value="<?php echo $leadData->cost_of_additional_copy; ?>" readonly>
                                       <input type="hidden" id="additional_cost_formula" name="" >
                                    </div> -->
                                       <?php } ?> 
                                      <?php if (isset($leadData->gross_amt) && ($leadData->gross_amt != '') ) {?>
                                        <!-- <div class="form-group additional_gross_amt" app-field-wrapper="" >
                                        <label for="" class="control-label">Gross Amount</label>
                                        <input type="text" id="additional_gross_amount" name="additional_gross_amount" class="form-control" placeholder="0" value="<?= $leadData->gross_amt?>" readonly>
                                    </div> -->
                                       <?php } ?>
                                       
                                       <?php if ( (($leadData->lead_package_detail == 3) && ($leadData->last_lead_status > 0)) || (isset($leadData->cost_of_additional_copy) && ($leadData->cost_of_additional_copy != '') ) || (isset($leadData->gross_amt) && ($leadData->gross_amt != '') ) ) {?>
                                            <div class="form-group cost_additional_copy" app-field-wrapper="" >
                                        <label for="" class="control-label">Cost of additional copy</label>
                                        <input type="text" id="cost_of_additional" name="cost_of_additional" class="form-control" placeholder="0" value="<?php  if($leadData->cost_of_additional_copy != ''){echo $leadData->cost_of_additional_copy;}else{echo $leadData->website;} ?>" readonly>
                                       <input type="hidden" id="additional_cost_formula" name="" >
                                    </div>
                                      <div class="form-group additional_gross_amt" app-field-wrapper="" >
                                        <label for="" class="control-label">Gross Amount</label>
                                        <input type="text" id="additional_gross_amount" name="additional_gross_amount" class="form-control" placeholder="0" value="<?php if($leadData->gross_amt != ''){echo $leadData->gross_amt;}else{echo $leadData->junk;} ?>" readonly>
                                    </div>
                                       <?php }else{ ?>
                                        <div class="form-group cost_additional_copy" app-field-wrapper="" style="display: none;">
                                        <label for="" class="control-label">Cost of additional copy</label>
                                        <input type="text" id="cost_of_additional" name="cost_of_additional" class="form-control" placeholder="0" value="" readonly>
                                       <input type="hidden" id="additional_cost_formula" name="" >
                                    </div>
                                      <div class="form-group additional_gross_amt" app-field-wrapper="" style="display: none;">
                                        <label for="" class="control-label">Gross Amount</label>
                                        <input type="text" id="additional_gross_amount" name="additional_gross_amount" class="form-control" placeholder="0" value="" readonly>
                                    </div>
                                    <div class="form-group " app-field-wrapper="" >
                                        <label for="create_p_offer" class="control-label">Write Offer Details here</label><br>
                                        <textarea id="create_p_offer" name="create_p_offer" rows="3" cols="74"><?php echo $leadData->create_p_offer; ?></textarea>
                                         </div>
                                      <?php } ?>
                                    <input type="hidden" id="number_of_pages" name="number_of_pages" class="form-control" value="1" readonly>
                                    <?php if ($leadData->lead_author_msstatus == 'inprocess') { ?>
                                    <? } ?>
                                    <div class="ms_status_inprogess" <?php if ($leadData->lead_author_msstatus == 'completed') { ?> style="display:show;" <? } else { ?> style="display:none;" <?php } ?>>
                                        
                                        <input type="radio"  class="full_payment_or_installment" value="def" checked name="full_payment" >
                                        <label for="full_payment">Default</label>
                                        <div class="row display_amount">
                                            <div class="col-md-12">
                                                <h3>Payment Schedule</h3>
                                                <input type="hidden" id="first_p1">
                                    <input type="hidden" id="first_p2">
                                    <input type="hidden" id="first_p3">
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11"><strong><span id="prec_40"></span></strong> 40% Booking Amount</div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11"><strong><span id="prec_40_"></span></strong> 40% After 15 Day's</div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11"><strong><span id="prec_20"></span></strong> 20% After 30 Day's</div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                          <div class="col-md-12">
                                            <input type="radio" class="full_payment_or_installment" value="full"  name="full_payment">
                                            <label for="full_payment">Full payment</label><br>
                                            <div class="hide_and_show_full" style="display:none;">
                                            <input type="hidden" name="total_amount_popup_pdf" id="total_amount_popup_pdf">

                                              <div class="col-md-12">
                                                <h3>Payment Schedule</h3>
                                              </div>
                                              <div class="col-md-1"></div>
                                              <div class="col-md-11"><strong><span id="hide_and_show_full_payment"></span></strong>Total Payment</div>
                                            </div>
                                            <input type="radio"  class="full_payment_or_installment" value="inst" name="full_payment" >
                                            <label for="age1">Installment</label>
                                          
                                          <div class="hide_and_show_inst" style="display:none;">
                                          <input type="hidden" name="booking_amount_popup_pdf" id="booking_amount_popup_pdf">
                                                <input type="hidden" name="booking_f_amount_popup_pdf" id="booking_f_amount_popup_pdf">
                                                <input type="hidden" name="booking_s_amount_popup_pdf" id="booking_s_amount_popup_pdf">
                                            <div class="col-md-12">
                                                <h3>Payment Schedule</h3>
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11"><strong><span ><input type="text" id="40_inst"> </span></strong> Booking Amount</div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11"><strong>(₹<span id="40_inst_"></span>)</strong> After 15 Day's</div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11"><strong>(₹<span id="20_inst"></span>)</strong> After 30 Day's</div>
                                        </div>
                                      </div>
                                    </div>


                                    <div class="ms_status_completed" <?php if ($leadData->lead_author_msstatus == 'inprocess') { ?> style="display:show;" <? } else { ?> style="display:none;" <?php } ?>>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3>Payment Schedule</h3>
                                            </div>
                                            <div class="col-md-12">Our Description</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="btn-bottom-toolbar text-right btn-toolbar-container-out" style="width: calc(100% - 293px);">
                                    <?php if (!empty($leadData)) { ?>
                                        <!--<a href="<?php echo base_url("assets/authorMail/") . $packageData->pdf_data; ?>" target="_blank" class="btn btn-info mail_preview" download>
                        Download
                        </a>-->
                                        <a href="<?php echo base_url("admin/leads/assignedleads_array"); ?>" class="btn btn-info">
                                            BACK
                                        </a>
                                        <!--<a type="button" href="javascript:void(0);<?php //echo admin_url('leadsdata/sendmail/'.$packageData->author_id);
                                                                                        ?>" class="btn btn-info mail_preview">Send Mail</a> -->
                                    <?php } ?>
                                    <button type="button" class="btn btn-info preview-btn">Preview & Save</button>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </form>
                        <!-- Modal Start -->
                        <div id="preview_data" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"></h4>
                                    </div>
                                    <div class="modal-body preview-package">
                                        <h4>Section I: Author Details</h4>
                                        <table class="table table-bordered table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>Author Name</strong>
                                                        </h6>
                                                        <span id="name"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>Email address</strong>
                                                        </h6>
                                                        <span id="emaild"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>Mobile</strong>
                                                        </h6>
                                                        <span id="mobiled"></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <hr />
                                        <h4>Section II: Package Information</h4>
                                        <table class="table table-bordered table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Manuscript Status</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="msstatusd"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Package Details</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="pkgdrtailsd"> </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Book Format</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="booktyped"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Package Name</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="packagenamed"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h4 style="font-size: 15px;">Services and Sub-service</h4>
                                                            </strong>
                                                        </h6>
                                                        <span id="servicesd"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <hr />
                                        <h4>Section III: Package Value Details</h4>
                                        <table class="table table-bordered table-condensed">
                                            <tbody>
                                            <tr  class="final_hide_default">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">40% Booking Amount</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="prec_400"></span>
                                                    </td>
                                                </tr>
                                                <tr  class="final_hide_default">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">40% After 15 Days of MS received</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="prec_400_"></span>
                                                    </td>
                                                </tr>
                                                <tr class="final_hide_default">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">20% After 30 Days of MS received</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="prec_200"></span>
                                                    </td>
                                                </tr>
                                                <tr class="final_hide_full" style="display:none;">
                                                
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Total Payment</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="total_amount_popup"></span>
                                                    </td>
                                                </tr>
                                              
                                                <tr class="final_hide_inst" style="display:none;">
                                                
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Booking Amount</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="booking_amount_popup"></span>
                                                    </td>
                                                </tr>
                                                <tr class="final_hide_inst" style="display:none;">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">After 15 Days of MS received</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="booking_f_amount_popup"></span>
                                                    </td>
                                                </tr>
                                                <tr class="final_hide_inst" style="display:none;">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">After 30 Days of MS received</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="booking_s_amount_popup"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Package Value</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="package_valued"></span>
                                                    </td>
                                                </tr>
                                                <tr id="distr">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Discount(%)</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="discountd"> </span>
                                                    </td>
                                                </tr>
                                                <tr id="grosstr">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Gross Package</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="less_package_valued"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">GST Amount (18%)</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="gstd"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Total Amount</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="total_amountd"></span>
                                                    </td>
                                                </tr>
                                               
                                                <tr id="showing_additional_cost" style="<?php if (isset($leadData->cost_of_additional_copy) && ($leadData->cost_of_additional_copy != '')){ echo "'display: block'";}else{echo "'display: none'";} ?>">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Cost of additional copy</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="showing_additional_cost_data"></span>
                                                    </td>
                                                </tr>
                                                <tr id="showing_gross_additional_cost" style="<?php if (isset($leadData->gross_amt) && ($leadData->gross_amt != '')){ echo "'display: block'";}else{echo "'display: none'";} ?>">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Gross Amount</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="showing_gross_additional_cost_data"></span>
                                                    </td>
                                                </tr>
                                                <tr id="offers_data" style="display: none">
                                                    <td>
                                                        <h6>
                                                            <strong>
                                                                <h5 style="font-size: 15px;">Offers</h5>
                                                            </strong>
                                                        </h6>
                                                        <span id="offers"></span>
                                                    </td>
                                                </tr>
                                              
                                            </tbody>
                                        </table>
                                        <div class="modal-footer">
                                            <button type="submit" form="create_package_form" class="btn btn-default">Save</button>
                                        </div>
                                    </div>
                                    <div class="modal-body mail_body">
                                        <h1>Author Submission form</h1>
                                        <p>Dear <span class="mail_author_name"> </span>.</p>
                                        <p>Please find below the link of the Author's submission form, you are required to fill it and send it back. Feel free to contact me if you have any queries
                                        <p>
                                        <p><strong>Note: Please make sure the form should not contain any sort of spelling or grammatical mistake as it would only cause problem and unnecessary delay in process.</strong>
                                        <p>
                                        <div class="pdf_data">
                                        </div>
                                        <div class="modal-footer">
                                            <!--  <a href="<?php // echo admin_url('leadsdata/sendmail'); 
                                                            ?>" class="btn btn-default author_id_mail">Send Mail</a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Stop -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php init_tail(); ?>
    <? php // echo json_encode($packageData->services);exit; 
    ?>
     <script>
        $(window).load(function() {
            <?php $service = explode(", ", $leadData->services);
            $i = count($service);
            ?>
            var count = <?php echo  $i; ?>;
            for (var i = 1; i <= count; i++) {
                $(document).on('change', '#services' + i, function() {
                    var serveiceId = $(this).attr("id");
                    var services = $(this).children(":selected").val();
                    var serveiceLastIdValue = serveiceId[serveiceId.length - 1];
                    var book_type = $("#book_type").val();
                    var packages = $("#package").val();
                    var parent_class = $(this).parent().parent().parent().prop('className'); //Get parent class
                    var lastChar = parent_class[parent_class.length - 1]; //Get last character from string
                    var pppp = parent_class.slice(0, -1); //Remove last character from string
                    $.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('Leads/getsubservies'); ?>",
                        data: {
                            'services': services,
                            'book_type': book_type,
                            'packages': packages
                        },
                        dataType: "html",
                        success: function(data) {
                            if (pppp == "row-add") {
                                $(".sub_services_data_" + lastChar).html(data, 500);
                            } else {
                                $(".sub_services" + serveiceLastIdValue).html(data);
                                //$(".sub_services_data_"+lastChar).html(data, 500);
                            }
                        },
                        error: function() {
                            alert("Error posting feed.");
                        }
                    });
                });
            }
            //var selects = $('select[name*="services"]')
            <?php if ($leadData->lead_package_detail) { ?>
                if ($("#msstatus").val() == 'completed' || $("#msstatus").val() == 'inprocess') {
                    $(document).ready(function() {
                        if ($("#total_amount").val()) {
                            <?php if (($leadData->lead_package_detail == 3) && ($leadData->junk > 0)) {?>
                        var total_amount_d = $("#additional_gross_amount").val();
                         <?php } if  (isset($leadData->gross_amt) && ($leadData->gross_amt != '') )  {?>
                          var total_amount_d = $("#additional_gross_amount").val();
                            <?php }else{ ?>
                                 var total_amount_d = $("#total_amount").val();
                           <?php }?>
                            // Math.round(package_value_data)
                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                            var discount_40_ = (40 / 100) * total_amount_d;
                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                        }
                    });
                }
                var additional_gross_amount=0;
                var cost_of_additional = 0;
                var package_details = <?php echo $leadData->lead_package_detail; ?>;
                var lead_package_name = '<?php echo $leadData->lead_package_name; ?>';
                var book_type = '<?= $leadData->lead_book_type; ?>';
                var lead_book_pages = '<?= $leadData->lead_book_pages; ?>';
                var color_pages = '<?= $leadData->color_pages; ?>';
                var paper_type_sc = '<?= $leadData->paper_type_sc; ?>';
                var book_size_sc = '<?= $leadData->book_size_sc; ?>';
                var lamination_sc = '<?= $leadData->lamination_sc; ?>';
                var book_cover_sc = '<?= $leadData->book_cover_sc; ?>';
                var complimentry_copies = '<?= $leadData->complimentry_copies; ?>';
                var additional_author_copies_number = '<?= $leadData->last_lead_status; ?>';
                <?php if (isset($leadData->cost_of_additional_copy) && ($leadData->cost_of_additional_copy != '') ) {?>
                   additional_gross_amount = '<?= $leadData->cost_of_additional_copy; ?>';
                <?php }else{ ?>
                additional_gross_amount = '<?= $leadData->junk; ?>';
                <?php } ?>
                
                <?php if (isset($leadData->gross_amt) && ($leadData->gross_amt != '') ) {?>
                  cost_of_additional = '<?= $leadData->gross_amt; ?>';
                <?php }else{ ?>
                  cost_of_additional = '<?= $leadData->website; ?>';
                <?php } ?>

                var service = <?php echo json_encode($leadData->lead_service) ?>;
                var sub_service = <?php echo json_encode($leadData->lead_sub_service) ?>;
                var total_amount_data = <?php echo $leadData->lead_packg_totalamount; ?>;
                //var less_pkg_value = '';
                var less_pkg_value = '<?php echo $leadData->lead_lesspckg_value; ?>';
                $("#total_amount").val(Math.round(total_amount_data));
                <?php if ($leadData->lead_packge_discount) {
                    $dicount  =  $leadData->lead_packge_discount;
                    $total_data = $leadData->lead_packge_value;
                    $new_data_calcuation = ($dicount / 100) * $total_data;
                ?>
                    var total_amount_discount = <?php echo $new_data_calcuation; ?>;
                    $("#less_package_value").val(Math.round(less_pkg_value));
                <?php } else { ?>
                    var gross_amount = <?php echo $leadData->lead_packge_value; ?>;
                    $("#less_package_value").val(Math.round(less_pkg_value));
                <?php } ?>
                if (package_details == 1) {
            var book_type = '<?php echo $leadData->lead_book_type;?>';
            $('#book_type_value').val(book_type);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('Leads/getserviesforedit'); ?>",
                        data: {
                            'service': service,
                            'sub_service': sub_service,
                            'book_type': book_type
                        }, // <--- THIS IS THE CHANGE
                        dataType: "html",
                        success: function(data) {
                            $("#getservicewithcost").html(data);
                            $("#testing").hide();
                            $("#all-services").hide();
                            $(".package_data_for_s_c").hide();
                            //$('#package').html('<option selected="" value="">Select Package</option><option value="essential" <?php  ?>>ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option>');
                        },
                    });
                } else if (package_details == 2) {
                    <?php $service = explode(", ", $leadData->lead_service);
                    $ii = count($service); ?>
                    $("#book_type_value").val('<?php echo $leadData->lead_book_type; ?>');
                    $(".package_data_for_s_c").hide();
                    var counting = 0;
                    $(document).on('click', '.remove_fieldss', function() {
                        var parent = $(this).parent().attr('class');
                        if ($(' .subservice_check').is(':checked')) {
                            var favorite = [];
                            $.each($("." + parent + " .subservice_check:checked"), function() {
                                favorite.push($(this).attr("data-cost"));
                            });
                            var editcost = 0;
                            editcost = $(".cost_page").val() * 100;
                            var total = 0;
                            var total_data_remove_data = 0;
                            for (var i = 0; i < favorite.length; i++) {
                                total += favorite[i] << 0;
                                //alert(total);
                            }
                            var total_data_remove = $("#package_value").val();
                            var book_type_sc = $("#book_type_sc").val();
                            //alert(book_type_sc);
                            var total_data_remove_database = $("#lead_ori_packge_value").val();
                            if (counting == 0) {
                                 if (total_data_remove != total_data_remove_database) {
                                total_data_remove_data = total_data_remove - total_data_remove_database;
                            total = (total+(total*0.40))
                                // alert(total);
                                // alert(total_data_remove);
                                // alert(total_data_remove_data);
                            var total_data = total_data_remove - total -total_data_remove_data ;
                        if (book_type_sc == 'paperback') {
                            var dropDown = document.getElementById("book_cover_c");
                        dropDown.selectedIndex = 0;
                        }
                    }else{
                         total = (total+(total*0.40))
                            //alert(total);
                            var total_data = total_data_remove - total ;
                    }
                        }else{
                            // total_data_remove_data = total_data_remove - total_data_remove_database;
                            total = (total+(total*0.40))
                            //alert(total);
                            var total_data = total_data_remove - total ;
                        }
                            $("#package_value").val(Math.round(total_data));
                            if ($("#discount").val()) {
                                var discount_datata = $("#discount").val();
                                var total_after_discount = (discount_datata / 100) * total_data;
                                total_after_discount = total_data - total_after_discount;
                                $("#less_package_value").val(Math.round(total_after_discount));
                            } else {
                                $("#less_package_value").val(Math.round(total_data));
                            }
                            var gst = (total_data * 18) / 100;
                            $("#gst").val(Math.round(gst));
                            var total_value = gst + total_data;
                            if ($("#discount").val()) {
                                total_value = gst + total_after_discount;
                            }
                            $("#total_amount").val(Math.round(total_value));
                                            var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                        }
                        $(this).parent('div').remove();
                         counting++;
                    });
                } else if (package_details == 3) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('Leads/getservies_sc_for_edit'); ?>",
                        data: {
                            'lead_book_pages': lead_book_pages,
                            'color_pages': color_pages,
                            'sub_service': sub_service,
                            'complimentry_copies': complimentry_copies,
                            'book_cover_sc': book_cover_sc,
                            'book_type': book_type,
                            'book_size_sc': book_size_sc,
                            'paper_type_sc': paper_type_sc,
                            'lamination_sc': lamination_sc,
                            'package_details': package_details,
                            'lead_package_name': lead_package_name,
                            'additional_author_copies_number': additional_author_copies_number,
                            'additional_gross_amount': additional_gross_amount,
                            'cost_of_additional': cost_of_additional
                        }, // <--- THIS IS THE CHANGE
                        dataType: "html",
                        success: function(data) {
                            var obj = JSON.parse(data);
                            $('#getservicewithcost').show(500);
                            $("#getservicewithcost").html(obj.html, 500);
                            $(".book_type").hide(500);
                            $(".package_data").hide(500);
                            $("#testing").hide(500);
                            $(".addmore").hide(500);
                            $(".book_type_standard_custum").show();
                            //$('#package').html('<option selected="" value="">Select Package</option><option value="essential" <?php  ?>>ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option>');
                        },
                    });
                }
            <?php } ?>
        });
        $(".remove_field2").click(function() {
            alert("The paragraph was clicked.");
        });
        $(document).ready(function() {
            $("#package_details").change(function() {
              if (this.value  == 1) {
                // $('#book_type').selectmenu('refresh');
                $('#book_type  option[value=""]').prop("selected", true);
                $('#package  option[value=""]').prop("selected", true);
                // $('#package').selectmenu('refresh');
              }
              if (this.value  == 3) {
                // $('#book_type').selectmenu('refresh');
                $('#book_type_sc  option[value=""]').prop("selected", true);
                $('#package_value_sc  option[value=""]').prop("selected", true);
                $("#getservicewithcost").hide()
                // $('#package').selectmenu('refresh');
              }
                //  $("#package_value").val(0);
                // $("#less_package_value").val(0);
                // $("#gst").val(0);
                // $("#total_amount").val(0);
                // $("#total_amount").val(0);
                $(".cost_additional_copy").hide(1000);
                $(".additional_gross_amt").hide(1000);
                $(".package_data").show(1000);
                $(".customize_services").show(1000);
                $('#book_type').html('<option selected="" value="">Select Book Format</option><option value="ebook">eBook</option><option value="paperback">Paperback</option>');
                   $("#book_type").change(function() {
                    // alert(this.value);
                if (this.value == 'ebook') {
                    $('#package').html('<option selected="" value="">Select Package</option><option value="essential">ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option><option value="rapid">RAPID</option>');
                }else{
                    $('#package').html('<option selected="" value="">Select Package</option><option value="essential">ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option><option value="elite">ELITE</option><option value="rapid">RAPID</option>');
                }
            });
                $(".sub_services").html("");
                if (this.value == "2") {
                    $(".package_ddl").hide(500);
                    $(".data_hide").remove();
                    $(".package_name").show(500);
                    var author_name = $("#author_name").val();
                    var str1 = " Book 1";
                    var res = author_name.concat(str1);
                    $("#package_name").val(res);
                    $('#getservicewithcost').hide(500);
                    $('.services').show(500);
                    $('.addmore').show(500);
                    $('#testing').show(500);
                    $(".sub_services1").show(500);
                    $(".sub_services2").show(500);
                    $(".all-services").show(500);
                    $('#package_value').val('');
                    $('#less_package_value').val('');
                    $('#gst').val('');
                    $('#discount').val('');
                    $('#total_amount').val('');
                    $(".book_type_standard_custum").hide(500);
                    $(".book_type").show(500);
                    $(".package_data_for_s_c").hide();
                      var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                } else if (this.value == "3") {
                    $("#book_type_sc").change(function() {
                    // alert(this.value);
                if (this.value == 'ebook') {
                     $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option>');
                    //  $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option><option value="rapid">CUSTOM RAPID</option>');
                }else{
                    $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option><option value="elite">CUSTOM ELITE</option>');
                    // $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option><option value="elite">CUSTOM ELITE</option><option value="rapid">CUSTOM RAPID</option>');
                }
            })
                    $(".book_type").hide(500);
                    $(".package_data").hide(500);
                    $("#testing").hide(500);
                    $(".addmore").hide(500);
                    $(".book_type_standard_custum").show(500);
                    $(".package_data_for_s_c").show(500);
                      var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                } else {
                    $(".package_data_for_s_c").hide();
                    $(".book_type_standard_custum").hide(500);
                    $(".package_ddl").show(500);
                    $(".book_type").show(500);
                    $(".package_name").hide(500);
                    $('.services').html("<option value=''> --No Data Found-- </option>");
                    //$('#services').selectpicker('refresh');
                    $('#getservicewithcost').show(500);
                    $('#getservicewithcost').html("");
                    $('.services').hide(500);
                    $('.addmore').hide(500);
                    //   $('.sub_services1').hide(500);
                    $("#testing").hide(500);
                    $(".sub_services1").hide(500);
                    $(".sub_services2").hide(500);
                    $(".all-services").hide(500);
                    $('#package_value').val('');
                    $('#less_package_value').val('');
                    $('#gst').val('');
                    $('#total_amount').val('');
                      var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                             document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                }
            });
            $("#book_type").change(function() {
                if (package_details == 1) {
                    $('#package').html('<option selected="" value="">Select Package</option><option value="essential">ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM2128</option>');
                    $('#package').selectpicker('refresh');
                    $('.services').html("<option value=''> --No Data Found-- </option>");
                    //$('#services').selectpicker('refresh');
                } else {
                $("#package_value").val(0);
                $("#less_package_value").val(0);
                $("#gst").val(0);
                $("#total_amount").val(0);
                $("#total_amount").val(0);
                $(".row-add1").remove();
                $(".row-add2").remove();
                $(".row-add3").remove();
                $(".row-add4").remove();
                $(".row-add5").remove();
                $(".row-add6").remove();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('Leads/getservies'); ?>",
                        data: {
                            'package_data': 2,
                            'book_type': this.value
                        }, // <--- THIS IS THE CHANGE
                        dataType: "html",
                        success: function(data) {
                            var obj = JSON.parse(data);
                            $('.services').html();
                            $('.services').html(obj);
                            //  $('.customize_services').show();
                            $('#getservicewithcost input').attr("disabled", true);
                            //$('#services').selectpicker('refresh');
                        },
                        error: function() {
                            alert("Error posting feed.");
                        }
                    });
                }
            });
            $("#package").change(function() {
                var book_type = $('#book_type').val();
                var packagename = $('#package').val();
                var packagename_sc = $('#package_value_sc').val();
                if (packagename != '') {
                    $('#package_value_by_id').val(packagename);
                } else {
                    $('#package_value_by_id').val(packagename_sc);
                }
                if (book_type == "") {
                    alert_float('warning', 'Please select book Format!');
                     $("#book_type").change(function() {
                  //  alert(this.value);
                if (this.value == 'ebook') {
                    //alert('test');
                    $('#package').html('<option selected="" value="">Select Package</option><option value="essential">ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option><option value="rapid">RAPID</option>');
                }else{
                     // alert('test right');
                     $('#package').html('<option selected="" value="">Select Package</option><option value="essential">ESSENTIAL</option><option value="regular">REGULAR</option><option value="superior">SUPERIOR</option><option value="premium">PREMIUM</option><option value="elite">ELITE</option><option value="rapid">RAPID</option>');
                }
            });
                       $('#package').selectpicker('refresh');
                } else {
                    var package_name = this.value;
                    $.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('Leads/getservies'); ?>",
                        data: {
                            'package': package_name,
                            'book_type': book_type
                        }, // <--- THIS IS THE CHANGE
                        dataType: "html",
                        success: function(data) {
                            // console.log(data);
                            if (packagename != "") {
                                // alert('hello');
                                var obj = JSON.parse(data);
                                $('#getservicewithcost').show(500);
                                $("#getservicewithcost").html(obj.html, 500);
                                $('.services').hide(500);
                                $('#package_value').val(Math.round(obj.pkgvalue));
                                $('#gst').val(Math.round(obj.gst));
                                $('#total_amount').val(Math.round(obj.totalamt));
                                $('#discount').val('0');
                                $('#less_package_value').val(Math.round(obj.pkgvalue));
                                if ($("#msstatus").val() == 'completed' || $("#msstatus").val() == 'inprocess') {
                                    $(document).ready(function() {
                                        if ($("#total_amount").val()) {
                                            var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                                        }
                                    });
                                }
                            } else {
                                var obj = JSON.parse(data);
                                $('.services').html();
                                $('.services').html(obj);
                                $('.services').selectpicker('refresh');
                                $('#getservicewithcost').hide(500);
                                $('.services').show(500);
                            }
                        },
                        error: function() {
                            alert("Error posting feed.");
                        }
                    });
                }
            });
                                     function ms_status() {
                                            if ($("#total_amount").val()) {
                                            var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                                        }else{
                                        }
                                        }
            $("#package_value").keyup(function() {
                var package_value = $(this).val();
                $('#less_package_value').val(Math.round($(this).val()));
                var gst = "";
                gst = (package_value * 18) / 100;
                $("#gst").val(Math.round(gst));
                var total_amount = parseFloat(package_value) + parseFloat(gst);
                $("#total_amount").val(Math.round(total_amount));
            });
            $("#discount").keyup(function() {
              if ($(this).val() > 10) {
                alert('Discount should be max 10%')
                $('#discount').val(10);
              }else{
                var package_value = ($("#package_value").val() * 100) / 100;
                var discount = ($(this).val() * 100) / 100;
                var discount_p = (package_value * discount) / 100;
                var less_value = package_value - discount_p;
                var gst = (less_value * 18) / 100;
                var total_after_less = less_value + gst;
                $("#less_package_value").val(Math.round(less_value));
                $("#gst").val(Math.round(gst));
                $("#total_amount").val(Math.round(total_after_less));
                var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                            var cost_of_add_c = $("#cost_of_additional").val();
                            var tot_gross_amt = parseInt(Math.round(total_after_less)) + parseInt(cost_of_add_c);
                            // alert(tot_gross_amt);
                            $("#additional_gross_amount").val(Math.round(tot_gross_amt));
             } });
            $(".mail_preview").click(function() {
                $(".modal-title").text("Mail Data");
                $(".mail_author_name").text($("#author_name").val());
                $(".author_id_mail").attr("href", "<?php echo admin_url('leadsdata/sendmail/'); ?>" + $("#author_id").val());
                $(".pdf_data").html($(".pdf_attachment").html());
                $(".mail_body").show();
                $(".preview-package").hide();
                $("#preview_data").modal('show');
            });
            $("#msstatus").change(function() {
                if (this.value == "inprocess") {
                    $(".ms_status_inprogess").show();
                    $(".ms_status_completed").hide();
                } else {
                    ms_status();
                    $(".ms_status_inprogess").show();
                    $(".ms_status_completed").hide();
                }
            });
        });
        $(document).on('change', '.services', function() {
            var value = $(this).val();
            // alert(value);
            var services = $(this).children(":selected").val();
            //$(services).prop('disabled', true);
            var book_type = $("#book_type").val();
            var increment = '';
             increment = $("#increment").val();
             // alert(increment);
            var packages = $("#package").val();
            var parent_class = $(this).parent().parent().parent().parent().prop('className'); //Get parent class
            // alert(parent_class);
            var lastChar = $("." + parent_class + "").attr('data-id');
            var parent_class_length = parent_class.length;
            if (parent_class_length == 8) {
                var pppp = parent_class.slice(0, -1); //Remove last character from string
            } else {
                var pppp = parent_class.slice(0, -2); //Remove last character from string
            }
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url('Leads/getsubservies'); ?>",
                data: {
                    'services': services,
                    'book_type': book_type,
                    'packages': packages,
                    'increment': increment
                },
                dataType: "html",
                success: function(data) {
                    if (pppp == "row-add") {
                        $(".sub_services_data_" + lastChar).html(data, 500);
                    } else {
                        $(".sub_services").html(data, 500);
                    }
                },
                error: function() {
                    alert("Error posting feed.");
                }
            });
        });
// $('.additional_author_copy').change(function() {
//     if ($('.additional_author_copy:checked').length > 0) {
//        alert('test');
//     } else {
//       alert('testt');
//     }
// });
        $(document).ready(function() {
 // if ($(".additional_author_copy").is(":checked")) {
 //     alert('test');
 //    } else {
 //       alert('testsdfvs');
 //    }
            //maximum input boxes allowed
            var max_fields = 20;
            var wrapper = $(".customize_services");
            var add_button = $(".add_field_button"); //Add button ID
            <?php if (!empty($leadData)) { ?>
                var dtttt = "<?php echo $leadData->lead_service; ?>";
                var dataArray = dtttt.split(", ");
                var x = dataArray.length;
            <?php } else { ?>
                var x = 1; //initlal text box count
            <?php } ?>
            $(add_button).click(function(e) { //on add input button click
                e.preventDefault();
                var packages = '<?php echo $leadData->lead_package_detail; ?>';
                    var packge_book_type_data = $("#book_type").val();
            //              if (packge_book_type_data == 'ebook') {
            //                 alert(packge_book_type_data+"ebook");
            // }else{
            //      // alert(packge_book_type_data+"paperback");
            // }
                if (packages != '') {
                    var package_details_data = $("#package_details").val();
                    <?php if ($leadData->lead_package_detail == 2) { ?>
                         var packge_book_type = $("#book_type").val();
                         //alert(packge_book_type);
                         if (packge_book_type == 'ebook') {
                                  <?php $this->db->from('tblpackagesubservices');
                                    $this->db->join('tblpackageservices', 'tblpackageservices.id = tblpackagesubservices.serviceid');
                                    $this->db->where('tblpackagesubservices.book_type', 'ebook');
                                    $this->db->where('tblpackagesubservices.packageid', 2);
                                    $this->db->group_by('tblpackagesubservices.serviceid');
                                    $query = $this->db->get(); 
                              ?>
                             // alert(packge_book_type_data+"ebook");
                               var fieldhtml = '<div class="col-md-12"><div class="form-group"><label for="services" class="control-label">Services:</label><select class="form-control services" id="" name="services[]" style="width: 100%; display:block;"><option value="">--Select--</option><?php foreach ($query->result() as $services_data) { ?><option value="<?= $services_data->id; ?>" ><?php echo $services_data->service_name; ?></option><?php } ?></select></div></div>';
                           }else{
                             // alert(packge_book_type_data+"paperback");
                              <?php $this->db->from('tblpackagesubservices');
                                $this->db->join('tblpackageservices', 'tblpackageservices.id = tblpackagesubservices.serviceid');
                                $this->db->where('tblpackagesubservices.book_type', 'paperback');
                                $this->db->where('tblpackagesubservices.packageid', 2);
                                $this->db->group_by('tblpackagesubservices.serviceid');
                                $query = $this->db->get(); ?>
                               var fieldhtml = '<div class="col-md-12"><div class="form-group"><label for="services" class="control-label">Services:</label><select class="form-control services" id="" name="services[]" style="width: 100%; display:block;"><option value="">--Select--</option>';
                               <?php foreach ($query->result() as $services_data) {  ?>
                                    fieldhtml += '<option value="<?= $services_data->id; ?>" ><?php echo $services_data->service_name; ?></option>';
                                  <?php } ?> 
                                  fieldhtml += '</select></div></div>';
                           }
                    <?php } else { ?>
                        var fieldhtml = $(".all-services").html();
                    <?php } ?>
                } else {
                    //Fields wrapper 
                    var fieldhtml = $(".all-services").html();
                }
                if (x < max_fields) { //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<div class="row-add' + x + '" data-id="' + x + '"><div class="servicess_data_' + x + '">' + fieldhtml + '</div><div class="sub_services_data_' + x + '"></div><div style="cursor:pointer;background-color:red;float:right;margin-right: 15px;" class="remove_field btn btn-info">Remove</div></div><div class="clearfix"></div>'); //add input box
                }
                $('.selectpicker').selectpicker('refresh');
            });
            $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
                e.preventDefault();
                var parent = $(this).parent().attr('class');
                if ($(' .subservice_check').is(':checked')) {
                    var favorite = [];
                    $.each($("." + parent + " .subservice_check:checked"), function() {
                        favorite.push($(this).attr("data-cost"));
                    });
                    var editcost = 0;
                    editcost = $(".cost_page").val() * 100;
                    var total = 0;
                    for (var i = 0; i < favorite.length; i++) {
                        total += favorite[i] << 0;
                    }
                    var total_data_remove = $("#package_value").val();
                    var total_data = total_data_remove - total;
                    $("#package_value").val(Math.round(total_data));
                    if ($("#discount").val()) {
                        var discount_datata = $("#discount").val();
                        var total_after_discount = (discount_datata / 100) * total_data;
                        total_after_discount = total_data - total_after_discount;
                        $("#less_package_value").val(Math.round(total_after_discount));
                    } else {
                        $("#less_package_value").val(Math.round(total_data));
                    }
                    var gst = (total_data * 18) / 100;
                    $("#gst").val(Math.round(gst));
                    var total_value = gst + total_data;
                    if ($("#discount").val()) {
                        total_value = gst + total_after_discount;
                    }
                    $("#total_amount").val(Math.round(total_value));
                }
                $(this).parent('div').remove();
                x--;
            })
        });
 $(document).on('change', '#book_type_sc', function() {
     var book_type_data = $("#book_type_sc").val();
     document.getElementById("prec_40").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_40_").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_20").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_400").innerHTML = '₹ ' + 0 + '';
                    document.getElementById("prec_400_").innerHTML = '₹ ' + 0 + '';
                    document.getElementById("prec_200").innerHTML = '₹ ' + 0 + '';
                    $("#cost_of_additional").val(0);
                    $("#additional_gross_amount").val(0);
     if (book_type_data == 'ebook') {
              $(".cost_additional_copy").hide(1000);
                $(".additional_gross_amt").hide(1000);
        //  $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option><option value="rapid">CUSTOM RAPID</option>');
         $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option>');
     }else if (book_type_data == 'paperback') {
      //  alert('test');
      //         $(".cost_additional_copy").show(1000);
      //           $(".additional_gross_amt").show(1000);
        // $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option><option value="elite">CUSTOM ELITE</option><option value="rapid">CUSTOM RAPID</option>');
        $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option><option value="elite">CUSTOM ELITE</option>');
     }
 })
 
$(document).on('click', '#myCheck300', function() {
    if ($(this).is(':checked')) {
        $('#book_size_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_size_sc').attr('disabled', true); //disable input
    }
});
$(document).on('click', '#myCheck303', function() {
    if ($(this).is(':checked')) {
        $('#book_cover_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_cover_sc').attr('disabled', true); //disable input
    }
});

  $(document).on('click', '#myCheck327', function() {
    if ($(this).is(':checked')) {
        $('#book_size_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_size_sc').attr('disabled', true); //disable input
    }
});
  $(document).on('click', '#myCheck330', function() {
    if ($(this).is(':checked')) {
        $('#book_cover_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_cover_sc').attr('disabled', true); //disable input
    }
});
$(document).on('click', '#myCheck357', function() {
    if ($(this).is(':checked')) {
        $('#book_size_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_size_sc').attr('disabled', true); //disable input
    }
});
  $(document).on('click', '#myCheck359', function() {
    if ($(this).is(':checked')) {
        $('#book_cover_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_cover_sc').attr('disabled', true); //disable input
    }
});
$(document).on('click', '#myCheck391', function() {
    if ($(this).is(':checked')) {
        $('#book_size_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_size_sc').attr('disabled', true); //disable input
    }
});
  $(document).on('click', '#myCheck394', function() {
    if ($(this).is(':checked')) {
        $('#book_cover_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_cover_sc').attr('disabled', true); //disable input
    }
});
$(document).on('click', '#myCheck490', function() {
    if ($(this).is(':checked')) {
        $('#book_size_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_size_sc').attr('disabled', true); //disable input
    }
});
  $(document).on('click', '#myCheck493', function() {
    if ($(this).is(':checked')) {
        $('#book_cover_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_cover_sc').attr('disabled', true); //disable input
    }
});
$(document).on('click', '#myCheck542', function() {
    if ($(this).is(':checked')) {
        $('#book_size_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_size_sc').attr('disabled', true); //disable input
    }
});
  $(document).on('click', '#myCheck545', function() {
    if ($(this).is(':checked')) {
        $('#book_cover_sc').removeAttr('disabled'); //enable input
    } else {
        $('#book_cover_sc').attr('disabled', true); //disable input
    }
});
$(document).on('click', '.additional_author_copy_customized_class', function() {
    if($(this).is(":checked")) {
    // alert('test');
        $("#additional_author_copy_customized").show();
        $(".cost_additional_copy").show();
        $(".additional_gross_amt").show();
        $("#showing_additional_cost").show();
        $("#showing_gross_additional_cost").show();
    } else {
    // alert('testtt');
        $("#additional_author_copy_customized").hide();
        $(".cost_additional_copy").hide();
        $(".additional_gross_amt").hide();
        $("#showing_additional_cost").hide();
        $("#showing_gross_additional_cost").hide();
    }
});
$(document).on('click', '.color_pages_customized_class', function() {
    if($(this).is(":checked")) {
    // alert('test');
        $("#color_pages_customized").show();
    } else {
        $("#color_pages_customized").hide();
    }
});
$(document).on('keyup', '#40_inst', function() {
  booking_amount_data =  $(this).val();
  var first_a1 = $('#first_p1').val(); 
    var first_a2 = $('#first_p2').val(); 
    var first_a3 = $('#first_p3').val(); 
    var package_cost = parseInt(first_a1)+parseInt(first_a2)+parseInt(first_a3);



    // var package_cost = $('#package_cost').val();
    // var booking_amount_data = $(this).val();
        var final_payment =  (package_cost/100)*20;
            var booking_amount =  (package_cost/100)*40;
            // alert(booking_amount)
            // alert(booking_amount_data)
        if(booking_amount_data > booking_amount) 
        {
          var cal = (booking_amount_data /package_cost)*100;
          if (cal > 80) {
            alert('Please enter amount atleast 80% or 100% of package cost');
            window.reload();
          }else{
            var get_per = 80 - cal;
             var total_co = (package_cost/100)*get_per;
          }
   
        }else{
            var total_c = booking_amount - booking_amount_data;
                var total_co = booking_amount+total_c;
        }
    document.getElementById("40_inst_").innerHTML =  Math.round(total_co);


    

 
});

$(document).on('click', '.full_payment_or_installment', function() {
  var value_data = $('.full_payment_or_installment:checked').val();

  if (value_data == 'def') {
    $('.display_amount').show();
    $('.hide_and_show_full').hide();
    $('.hide_and_show_inst').hide();
  }else if(value_data == 'full'){

    var first_a1 = $('#first_p1').val(); 
    var first_a2 = $('#first_p2').val(); 
    var first_a3 = $('#first_p3').val(); 
    var final_a = parseFloat(first_a1)+parseFloat(first_a2)+parseFloat(first_a3);
    // var check_additional_gross_amount  = $("#additional_gross_amount").val();
    final_a = parseInt(final_a)
    document.getElementById("hide_and_show_full_payment").innerHTML = '(₹ ' + final_a + ')';
   
    
         
    $('.display_amount').hide();
    $('.hide_and_show_full').show();
    $('.hide_and_show_inst').hide();
  }else if(value_data == 'inst'){
    
    var first_a1 = $('#first_p1').val(); 
    var first_a2 = $('#first_p2').val(); 
    var first_a3 = $('#first_p3').val(); 

    // document.getElementById("").innerHTML = '(₹ ' + Math.round(first_a1) + ')';
    $('#40_inst').val(Math.round(first_a1));
    document.getElementById("40_inst_").innerHTML =  Math.round(first_a2) ;
    document.getElementById("20_inst").innerHTML = Math.round(first_a3);
         
    $('.display_amount').hide();
    $('.hide_and_show_full').hide();
    $('.hide_and_show_inst').show();
  }
 
   
});


        $(document).on('change', '#package_value_sc', function() {
            $(".cost_additional_copy").hide(1000);
                $(".additional_gross_amt").hide(1000);
                  $("#cost_of_additional").val(0);
                    $("#additional_gross_amount").val(0);
                     document.getElementById("prec_40").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_40_").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_20").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_400").innerHTML = '₹ ' + 0 + '';
                    document.getElementById("prec_400_").innerHTML = '₹ ' + 0 + '';
                    document.getElementById("prec_200").innerHTML = '₹ ' + 0 + '';
            //alert('h');
$(".dynamicservice ul li.subservicesstand_sc").removeAttr("checked");
  // $('ul.nav-content li').removeClass('current');
                $("#package_value").val(0);
                $("#less_package_value").val(0);
                //var total_amount = (all_click_cost * 18) / 100;
                $("#gst").val(0);
                //total_amount_data = total_amount + all_click_cost;
                $("#total_amount").val(0);
                $("#total_amount").val(0);
            var book_type = $("#book_type_sc").val();
            if (book_type == "") {
                alert_float('warning', 'Please select book Format!');
                $('#package_value_sc').html('<option selected="" value="">Select Package</option><option value="essential">CUSTOM ESSENTIAL</option><option value="regular">CUSTOM REGULAR</option><option value="superior">CUSTOM SUPERIOR</option><option value="premium">CUSTOM PREMIUM</option>');
                $('#package_value_sc').selectpicker('refresh');
            } else {
                var package_name = this.value;
                var package_details = $("#package_details").val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo admin_url('Leads/getservies_sc'); ?>",
                    data: {
                        'package': package_name,
                        'book_type': book_type,
                        'package_details': package_details
                    }, // <--- THIS IS THE CHANGE
                    dataType: "html",
                    success: function(data) {
                        //alert(data);
                        // console.log(data);
                        var obj = JSON.parse(data);
                        $('#getservicewithcost').show(500);
                        $("#getservicewithcost").html(obj.html, 500);
                    },
                    error: function() {
                        alert("Error posting feed.");
                    }
                });
            }
        });
      var count_data = 0;
function myFunctiondataedit(f_id, f_cost ,s_id, s_cost,f_change_cost,s_change_cost) {
   // if(count_data==0){
     var value = document.getElementById('number_of_pages_for_sc_for_edit').value;
       var book_type_sc = document.getElementById('book_type_sc').value;
       if (book_type_sc == 'paperback') {
        if (value > 49) {
         if (($('#myCheck'+f_id).prop('checked'))  && ($('#myCheck'+s_id).prop("checked"))) {
            total_cost_data = f_change_cost+s_change_cost;
             $('#myCheck'+f_id).removeAttr("checked");
            $('#myCheck'+s_id).removeAttr("checked");
            }else{
            if ($('#myCheck'+f_id).prop("checked")) {
                $('#myCheck'+f_id).removeAttr("checked");
            total_cost_data = f_change_cost
            }else if ($('#myCheck'+s_id).prop("checked")){
                  $('#myCheck'+s_id).removeAttr("checked");
             total_cost_data = s_change_cost
            }else{
                total_cost_data = 0;
            }
            }
     // alert(total_cost_data);
               var package_value = $("#package_value").val();
            var package_value_for_edit = $("#lead_ori_packge_value").val();
               minus_of_data =package_value - total_cost_data;
               minus_of_data_edit =package_value_for_edit - total_cost_data;
              $("#package_value").val(Math.round(minus_of_data));
              $("#lead_ori_packge_value").val(Math.round(minus_of_data_edit));
              var package_value_data = parseInt($("#package_value").val());
                $("#less_package_value").val(Math.round(package_value_data));
                var total_amount = (package_value_data * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value_data;
                $("#total_amount").val(Math.round(total_amount_data));
        //          var per_page_cost = value*11;
        // var per_page_cost_second = value*11;
        // var value = document.getElementById('number_of_pages_for_sc_for_edit').value;
         var total_cost = 11*value;
         $('#myCheck'+f_id).attr('onclick','myFunction('+f_id+','+total_cost+')');
         $('#sub_service_cost_sc_edit'+f_id).val(total_cost);
         // var value = document.getElementById('number_of_pages_for_sc_for_edit').value;
         var total_cost_second = 11*value;
         $('#myCheck'+s_id).attr('onclick','myFunction('+s_id+','+total_cost_second+')');
         $('#sub_service_cost_sc_edit'+s_id).val(total_cost_second);
         $('#number_of_pages').val(value);
         $('#number_of_pages_allowed').val(value);
         // $('#number_of_pages_for_sc_for_edit').attr('onkeyup','myFunctiondataedit('+f_id+','+f_cost+','+total_cost+')');
         $('#number_of_pages_for_sc_for_edit').attr('onkeyup','myFunctiondataedit('+f_id+','+f_cost+','+s_id+','+s_cost+','+total_cost+','+total_cost_second+')');
        }else{
             alert_float('warning', 'Number of pages should be greater than 49');
        }
    }else{
         if ($('#myCheck'+f_id).prop("checked") && $('#myCheck'+s_id).prop("checked")) {
         total_cost_data = f_change_cost + s_change_cost;
           $('#myCheck'+f_id).removeAttr("checked");
    $('#myCheck'+s_id).removeAttr("checked");
     }else{
        if ($('#myCheck'+f_id).prop("checked")) {
            $('#myCheck'+f_id).removeAttr("checked");
            total_cost_data = f_change_cost
        }else if($('#myCheck'+s_id).prop("checked")){
             total_cost_data = s_change_cost
             $('#myCheck'+s_id).removeAttr("checked");
        }else{
                total_cost_data = s_id
        }
     }
    // alert(total_cost_data);
               var package_value = $("#package_value").val();
            var package_value_for_edit = $("#lead_ori_packge_value").val();
             if ($('#myCheck'+f_id).prop("checked") && $('#myCheck'+s_id).prop("checked")) {
                minus_of_data =package_value - total_cost_data;
               minus_of_data_edit =package_value_for_edit - total_cost_data;
             }else if ($('#myCheck'+f_id).prop("checked")) {
                 minus_of_data =package_value - total_cost_data;
               minus_of_data_edit =package_value_for_edit - total_cost_data;
           }else if ($('#myCheck'+s_id).prop("checked")) {
                 minus_of_data =package_value - total_cost_data;
               minus_of_data_edit =package_value_for_edit - total_cost_data;
             }else{
                 minus_of_data = total_cost_data;
               minus_of_data_edit = total_cost_data;
             }
              $("#package_value").val(Math.round(minus_of_data));
              $("#lead_ori_packge_value").val(Math.round(minus_of_data_edit));
              var package_value_data = parseInt($("#package_value").val());
                $("#less_package_value").val(Math.round(package_value_data));
                var total_amount = (package_value_data * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value_data;
                $("#total_amount").val(Math.round(total_amount_data));
        //          var per_page_cost = value*11;
        // var per_page_cost_second = value*11;
        var value = document.getElementById('number_of_pages_for_sc_for_edit').value;
         var total_cost = 11*value;
         $('#myCheck'+f_id).attr('onclick','myFunction('+f_id+','+total_cost+')');
         $('#sub_service_cost_sc_edit'+f_id).val(total_cost);
         var value = document.getElementById('number_of_pages_for_sc_for_edit').value;
         var total_cost_second = 11*value;
         $('#myCheck'+s_id).attr('onclick','myFunction('+s_id+','+total_cost_second+')');
         $('#sub_service_cost_sc_edit'+s_id).val(total_cost_second);
         $('#number_of_pages').val(value);
         $('#number_of_pages_allowed').val(value);
         // $('#number_of_pages_for_sc_for_edit').attr('onkeyup','myFunctiondataedit('+f_id+','+f_cost+','+s_id+','+s_cost+','+total_cost+','+total_cost_second+')');
         $('#number_of_pages_for_sc_for_edit').attr('onkeyup','myFunctiondataedit('+f_id+','+f_cost+','+total_cost+')');
    }
        }
        var count_data_copies = 0;
        function myFunctioneditcopies(id , cost, change_cost ) {
        
             if(count_data_copies==0){
                // if ($('#myCheck'+id).prop("checked")) {
                // $('#myCheck'+id).removeAttr("checked");
                // total_cost_data_sc =cost;
                // }else{
                total_cost_data_sc = 0;
                // }
              $('#myCheck'+id).removeAttr("checked");
                total_cost_data_sc =change_cost;
               var package_value = $("#package_value").val();
            var package_value_for_edit = $("#lead_ori_packge_value").val();
               minus_of_data_sc =package_value - total_cost_data_sc;
               minus_of_data_sc_for_edit =package_value_for_edit - total_cost_data_sc;
              $("#package_value").val(Math.round(minus_of_data_sc));
              $("#lead_ori_packge_value").val(Math.round(minus_of_data_sc_for_edit));
              var package_value_data = parseInt($("#package_value").val());
                $("#less_package_value").val(Math.round(package_value_data));
                var total_amount = (package_value_data * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value_data;
                $("#total_amount").val(Math.round(total_amount_data));
                var total_amount_d = $("#total_amount").val();
                var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                var discount_40_ = (40 / 100) * total_amount_d;
                var discount_20 = total_amount_d - (discount_40 + discount_40);
                var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                 document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
            }   count_data_copies++;
               var per_copy = cost/100;
        var single_copy_cost = cost/per_copy;
        var value = document.getElementById('number_of_complimentary_copies_edit').value;
         var total_cost = single_copy_cost*value;
         $('#myCheck'+id).attr('onclick','myFunction('+id+','+total_cost+')');
         $('#sub_service_cost_sc_edit'+id).val(total_cost);
         // sub_service_cost_sc_edit322
        }
        var count_data = 0;
        function myFunctiondata(f_id, f_cost ,s_id, s_cost) {
          // alert('test');
       var value = document.getElementById('number_of_pages_for_sc').value;
       var book_type_sc = document.getElementById('book_type_sc').value;
       if (book_type_sc == 'paperback') {
        if (value > 49) {
              if (($('#myCheck'+f_id).prop('checked'))  && ($('#myCheck'+s_id).prop("checked"))) {
            total_cost_data = f_cost+s_cost;
             $('#myCheck'+f_id).removeAttr("checked");
            $('#myCheck'+s_id).removeAttr("checked");
            }else{
            if ($('#myCheck'+f_id).prop("checked")) {
                $('#myCheck'+f_id).removeAttr("checked");
            total_cost_data = f_cost
            }else if ($('#myCheck'+s_id).prop("checked")){
                  $('#myCheck'+s_id).removeAttr("checked");
             total_cost_data = s_cost
            }else{
                total_cost_data = 0;
            }
            }
               var package_value = parseInt($("#package_value").val());
                var package_value_for_edit = parseInt($("#lead_ori_packge_value").val());
               minus_of_data =package_value - total_cost_data;
               minus_of_data_edit =package_value_for_edit - total_cost_data;
              $("#package_value").val(Math.round(minus_of_data));
              $("#lead_ori_packge_value").val(Math.round(minus_of_data_edit));
              var package_value_data = parseInt($("#package_value").val());
                $("#less_package_value").val(Math.round(package_value_data));
                var total_amount = (package_value_data * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value_data;
                $("#total_amount").val(Math.round(total_amount_data));
                 var total_amount_d = $("#total_amount").val();
                    var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                    var discount_40_ = (40 / 100) * total_amount_d;
                    var discount_20 = total_amount_d - (discount_40 + discount_40);
                    var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                    var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                    document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
        var per_page_cost = 11;
        var per_page_cost_second = 11;
         var total_cost = per_page_cost*value;
         $('#myCheck'+f_id).attr('onclick','myFunction('+f_id+','+total_cost+')');
         $('#sub_service_cost_sc'+f_id).val(total_cost);
         var value = document.getElementById('number_of_pages_for_sc').value;
         var total_cost_second = per_page_cost_second*value;
         $('#myCheck'+s_id).attr('onclick','myFunction('+s_id+','+total_cost_second+')');
         $('#sub_service_cost_sc'+s_id).val(total_cost_second);
         $('#number_of_pages').val(value);
         $('#number_of_pages_allowed').val(value);
         $('#number_of_pages_for_sc').attr('onkeyup','myFunctiondata('+f_id+','+total_cost+','+s_id+','+total_cost_second+')');
       
         var english_hindi_id = $('.english_hindi_translation').attr("data-id");
// alert(english_hindi_id);
// alert(english_hindi_id);
        var english_hindi_cost = $('#myCheck'+english_hindi_id).attr("data-cost");
        var english_h_total_c = value*english_hindi_cost;
        // alert(english_h_total_c);
        $('#myCheck'+english_hindi_id).attr('onclick','myFunction('+english_hindi_id+','+english_h_total_c+')');
        $('#myCheck'+english_hindi_id).attr('data-price',english_h_total_c);

        $('.english_hindi_translation').val(english_h_total_c);
        
        var hindi_english_id = $('.hindi_english_translation').attr("data-id");
        var hindi_english_cost = $('#myCheck'+hindi_english_id).attr("data-cost");
        var hindi_english_total_c = value*hindi_english_cost;
        $('#myCheck'+hindi_english_id).attr('onclick','myFunction('+hindi_english_id+','+hindi_english_total_c+')');
        $('.hindi_english_translation').val(hindi_english_total_c);


        var hindi_typing_id = $('.hindi_typing').attr("data-id");
        var hindi_typing_cost = $('#myCheck'+hindi_typing_id).attr("data-cost");
        var hindi_h_total_c = value*hindi_typing_cost;
        $('#myCheck'+hindi_typing_id).attr('onclick','myFunction('+hindi_typing_id+','+hindi_h_total_c+')');
        $('#myCheck'+hindi_typing_id).attr('data-price',hindi_h_total_c);
        $('.hindi_typing').val(hindi_h_total_c);

        var english_typing_id = $('.english_typing').attr("data-id");
        var english_typing_cost = $('#myCheck'+english_typing_id).attr("data-cost");
        var english_h_total_c = value*english_typing_cost;
        $('#myCheck'+english_typing_id).attr('onclick','myFunction('+english_typing_id+','+english_h_total_c+')');
        $('#myCheck'+english_typing_id).attr('data-price',english_h_total_c);
        $('.english_typing').val(english_h_total_c);

        var urdu_typing_id = $('.urdu_typing').attr("data-id");
        var urdu_typing_cost = $('#myCheck'+urdu_typing_id).attr("data-cost");
        var urdu_h_total_c = value*urdu_typing_cost;
        $('#myCheck'+urdu_typing_id).attr('onclick','myFunction('+urdu_typing_id+','+urdu_h_total_c+')');
        $('#myCheck'+urdu_typing_id).attr('data-price',urdu_h_total_c);
        $('.urdu_typing').val(urdu_h_total_c);
        
        }else{
              alert_float('warning', 'Number of pages should be more than 49');
        }
       }else{
        if (($('#myCheck'+f_id).prop('checked'))  && ($('#myCheck'+s_id).prop("checked"))) {
            total_cost_data = f_cost+s_cost;
             $('#myCheck'+f_id).removeAttr("checked");
            $('#myCheck'+s_id).removeAttr("checked");
            }else{
            if ($('#myCheck'+f_id).prop("checked")) {
                $('#myCheck'+f_id).removeAttr("checked");
            total_cost_data = f_cost
            }else if ($('#myCheck'+s_id).prop("checked")){
                  $('#myCheck'+s_id).removeAttr("checked");
             total_cost_data = s_cost
            }else{
                total_cost_data = 0;
            }
            }
               var package_value = parseInt($("#package_value").val());
                var package_value_for_edit = parseInt($("#lead_ori_packge_value").val());
               minus_of_data =package_value - total_cost_data;
               minus_of_data_edit =package_value_for_edit - total_cost_data;
              $("#package_value").val(Math.round(minus_of_data));
              $("#lead_ori_packge_value").val(Math.round(minus_of_data_edit));
              var package_value_data = parseInt($("#package_value").val());
                $("#less_package_value").val(Math.round(package_value_data));
                var total_amount = (package_value_data * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value_data;
                $("#total_amount").val(Math.round(total_amount_data));
                 var total_amount_d = $("#total_amount").val();
                    var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                    var discount_40_ = (40 / 100) * total_amount_d;
                    var discount_20 = total_amount_d - (discount_40 + discount_40);
                    var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                    var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                   document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
        var per_page_cost = 11;
        var per_page_cost_second = 11;
         var total_cost = per_page_cost*value;
         $('#myCheck'+f_id).attr('onclick','myFunction('+f_id+','+total_cost+')');
         $('#sub_service_cost_sc'+f_id).val(total_cost);
         var value = document.getElementById('number_of_pages_for_sc').value;
         var total_cost_second = per_page_cost_second*value;
         $('#myCheck'+s_id).attr('onclick','myFunction('+s_id+','+total_cost_second+')');
         $('#sub_service_cost_sc'+s_id).val(total_cost_second);
         $('#number_of_pages').val(value);
         $('#number_of_pages_allowed').val(value);
         $('#number_of_pages_for_sc').attr('onkeyup','myFunctiondata('+f_id+','+total_cost+','+s_id+','+total_cost_second+')');
       }
            // if(count_data==0){
        }
var ii = 1;
var package_value_for_c = '';
 $(document).on('change', '#book_cover_c', function() {
    if (ii==1) {
                package_value_for_c = parseInt($("#lead_ori_packge_value").val());
    i++;            }
                package_details = '';
              package_details = '<?php echo $leadData->lead_package_detail; ?>';
            // var package_value_for_edit = parseInt($("#lead_ori_packge_value").val());
                 var book_format = $(this).val();
            var no_of_pages = parseInt($("#increment").val());
            var book_size = $('#book_size_c :selected').text();
            // alert(book_size);
            // alert(book_format);
           let booktype=0;
      if(book_format=='MultiColor 250 GSM' || book_format=='MultiColor'){
          if(no_of_pages <= 72){
            if(book_size =='5*8'){
              booktype=0.605
            }else if(book_size =='6*9'){
              booktype=0.718
            }else if(book_size =='8*11'){
              booktype=0.85
            }else{}
          }else if(no_of_pages >= 73 && no_of_pages <=96){
            if(book_size =='5*8'){
              booktype=0.605
            }else if(book_size =='6*9'){
              booktype=0.718
            }else if(book_size =='8*11'){
                booktype=0.85
              }else{}
          }else if(no_of_pages >= 97 && no_of_pages <=108 ){
         // alert(book_size);
            if(book_size == '5*8'){
                // alert('test');
              booktype=0.533
            }else if(book_size =='6*9'){
              booktype=0.602
            }else if(book_size =='8*11'){
              booktype=0.74
            }else{}
          }else if(no_of_pages >= 109 && no_of_pages <=128 ){
            if(book_size =='5*8'){
              booktype=0.533
            }else if(book_size =='6*9'){
              booktype=0.602
            }else if(book_size =='8*11'){
              booktype=0.70
            }else{}
          }else if(no_of_pages >= 129 && no_of_pages <=148 ){
              if(book_size =='5*8'){
                booktype=0.460
              }else if(book_size =='6*9'){
                booktype=0.573
              }else if(book_size =='8*11'){
                booktype=0.68
              }else{}
          }else if(no_of_pages >= 149 && no_of_pages <=192 ){
            if(book_size =='5*8'){
              booktype=0.460
            }else if(book_size =='6*9'){
              booktype=0.573
            }else if(book_size =='8*11'){
              booktype=0.62
            }else{}
          }else if(no_of_pages >= 193 && no_of_pages <=216 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.62
            }else{}
          }else if(no_of_pages >= 217 && no_of_pages <=252 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.59
            }else{}
          }else if(no_of_pages >= 253 && no_of_pages <=256 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.59
            }else{}
          }else if(no_of_pages >= 257 && no_of_pages <=276 ){
            if(book_size =='5*8'){
              booktype=0.413
            }else if(book_size =='6*9'){
              booktype=0.515
            }else if(book_size =='8*11'){
              booktype=0.59
            }else{}
          }else if(no_of_pages >= 277 && no_of_pages <=320 ){
            if(book_size =='5*8'){
              booktype=0.413
            }else if(book_size =='6*9'){
              booktype=0.515
            }else if(book_size =='8*11'){
              booktype=0.57
            }else{}
          }else if(no_of_pages >= 321 && no_of_pages <=352 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.57
            }else{}
          }else if(no_of_pages >= 353 && no_of_pages <=384 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.56
            }else{}
          }else if(no_of_pages >= 385 && no_of_pages <=432 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.55
            }else{}
          }else if(no_of_pages >= 433 && no_of_pages <=492 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else if(no_of_pages >= 493 && no_of_pages <=548 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else if(no_of_pages >=549 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else{}
          var production_cost = no_of_pages * booktype + 8.5;
          var prodsubpercentage = production_cost * 0.15;
          var subsidPrice = production_cost + prodsubpercentage;
          subsidPrice = parseFloat(subsidPrice).toFixed(2);

        console.log(production_cost+'production_cost')
       var cost =  production_cost + (production_cost*40)/100;
       console.log(cost+'cost')
        var amount  = parseInt($("#complimentry_copies").val())*cost;
        console.log(amount+'amount')
        var package_value  = package_value_for_c+amount;
        console.log(package_value+'package_value')
       $("#lead_ori_packge_value").val(package_value_for_c);
       $("#package_value").val(Math.round(package_value));
       $("#less_package_value").val(Math.round(package_value));
                var total_amount = (package_value * 18) / 100;
                console.log(total_amount+'total_amount')
                $("#gst").val(Math.round(total_amount));

  if ($("#additional_author_copy_customized").val() != '' && $("#color_pages_customized").val() == '') {
         var a = subsidPrice*($("#additional_author_copy_customized").val());
         var b = 30*($("#additional_author_copy_customized").val());
         var c = Math.round(a)+Math.round(b);

         }else if($("#additional_author_copy_customized").val() != '' && $("#color_pages_customized").val() != ''){
          var number_of_color = parseInt($("#color_pages_customized").val());
         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
          //  alert('right');
          var  a = number_of_color*7;
          var b = a + (a*0.15);
           var e = b*$("#additional_author_copy_customized").val();
           var f = subsidPrice*($("#additional_author_copy_customized").val());
         var g = 30*($("#additional_author_copy_customized").val());
         var c = Math.round(e)+Math.round(f)+Math.round(g);


         var  a = number_of_color*7;
            var b = a + (a *0.40);
            var h = b*$("#complimentry_copies").val();
            package_value = package_value + h
            $("#package_value").val(Math.round(package_value));
            $("#less_package_value").val(Math.round(package_value));
              total_amount = (package_value * 18) / 100;
                // console.log(total_amount+'total_amount')
                $("#gst").val(Math.round(total_amount));
         
         }else{
          
            var a = 7*no_of_pages;
        
             var b = parseInt(a) +parseInt(a*0.15);
            
             var f = b * $("#additional_author_copy_customized").val()
        
              var c = parseInt(b)+parseInt(f)
         
              var complimentry_c = $("#complimentry_copies").val()*a
              complimentry_c = complimentry_c+ (complimentry_c*0.40)
         package_value = package_value_for_c+complimentry_c
         $("#package_value").val(Math.round(package_value));
            $("#less_package_value").val(Math.round(package_value));
              total_amount = (package_value * 18) / 100;
                $("#gst").val(Math.round(total_amount));
         }
         }
         else{
            var c = 0;
           if ($("#color_pages_customized").val() != '') {
            var number_of_color = $("#color_pages_customized").val();
         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
          // alert('test');
            var  a = number_of_color*7;
            var b = a + (a *0.40);
            var c = b*$("#complimentry_copies").val();
            package_value = package_value + c
            $("#package_value").val(Math.round(package_value));
            $("#less_package_value").val(Math.round(package_value));
              total_amount = (package_value * 18) / 100;
                // console.log(total_amount+'total_amount')
                $("#gst").val(Math.round(total_amount));
           }else {
            // alert('test1');
             var a = 7*no_of_pages;
             var b = a +(a*0.40);
             b = b*$("#complimentry_copies").val();
             package_value = package_value_for_c + b
            $("#package_value").val(Math.round(package_value));
            $("#less_package_value").val(Math.round(package_value));
              total_amount = (package_value * 18) / 100;
                // console.log(total_amount+'total_amount')
                $("#gst").val(Math.round(total_amount));
           }
         
         }
         }
                total_amount_data = total_amount + package_value;
                // console.log(total_amount_data+'total_amount_data')

                $("#total_amount").val(Math.round(total_amount_data));

       
         $("#cost_of_additional").val(Math.round(c));
         var total_amount_d = $("#total_amount").val();
         var after_total_additional_copy = Math.round(c)+Math.round(total_amount_d);
                $("#additional_gross_amount").val(Math.round(after_total_additional_copy));
                total_amount_d = after_total_additional_copy;
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                             document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
      }else{
       let booktype=0;
          if(no_of_pages <= 72){
            if(book_size =='5*8'){
                      booktype=0.605
            }else if(book_size =='6*9'){
              booktype=0.718
                     }else if(book_size =='8*11'){
              booktype=0.85
                        }else{}
          }else if(no_of_pages >= 73 && no_of_pages <=96){
            if(book_size =='5*8'){
                            booktype=0.605
            }else if(book_size =='6*9'){
              booktype=0.718
                          }else if(book_size =='8*11'){
                booktype=0.85
              }else{}
          }else if(no_of_pages >= 97 && no_of_pages <=108 ){
            if(book_size =='5*8'){
              booktype=0.533
            }else if(book_size =='6*9'){
              booktype=0.602
                          }else if(book_size =='8*11'){
              booktype=0.74
            }else{}
          }else if(no_of_pages >= 109 && no_of_pages <=128 ){
            if(book_size =='5*8'){
              booktype=0.533
            }else if(book_size =='6*9'){
              booktype=0.602
            }else if(book_size =='8*11'){
              booktype=0.70
            }else{}
          }else if(no_of_pages >= 129 && no_of_pages <=148 ){
              if(book_size =='5*8'){
                booktype=0.460
              }else if(book_size =='6*9'){
                booktype=0.573
              }else if(book_size =='8*11'){
                booktype=0.68
              }else{}
          }else if(no_of_pages >= 149 && no_of_pages <=192 ){
            if(book_size =='5*8'){
              booktype=0.460
            }else if(book_size =='6*9'){
              booktype=0.573
            }else if(book_size =='8*11'){
              booktype=0.62
            }else{}
          }else if(no_of_pages >= 193 && no_of_pages <=216 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.62
            }else{}
          }else if(no_of_pages >= 217 && no_of_pages <=252 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.59
              1
            }else{}
          }else if(no_of_pages >= 253 && no_of_pages <=256 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.59
            }else{}
          }else if(no_of_pages >= 257 && no_of_pages <=276 ){
            if(book_size =='5*8'){
              booktype=0.413
            }else if(book_size =='6*9'){
              booktype=0.515
            }else if(book_size =='8*11'){
              booktype=0.59
            }else{}
          }else if(no_of_pages >= 277 && no_of_pages <=320 ){
            if(book_size =='5*8'){
              booktype=0.413
            }else if(book_size =='6*9'){
              booktype=0.515
            }else if(book_size =='8*11'){
              booktype=0.57
            }else{}
          }else if(no_of_pages >= 321 && no_of_pages <=352 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.57
            }else{}
          }else if(no_of_pages >= 353 && no_of_pages <=384 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.56
            }else{}
          }else if(no_of_pages >= 385 && no_of_pages <=432 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.55
            }else{}
          }else if(no_of_pages >= 433 && no_of_pages <=492 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else if(no_of_pages >= 493 && no_of_pages <=548 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else if(no_of_pages >=549 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else{}
          console.log('test1')
          //alert(booktype);
          //alert(no_of_pages);
          var production_cost = no_of_pages * booktype + 55;
          var prodsubpercentage = production_cost * 0.15;
          var subsidPrice = production_cost + prodsubpercentage;
          subsidPrice = parseFloat(subsidPrice).toFixed(2);
          /// alert(production_cost+"production_cost");
       var cost =  production_cost + (production_cost*40)/100;
       // alert(cost+"cost");
        var amount  = parseInt($("#complimentry_copies").val())*cost;
       //  alert(amount+"amount");
         // 1516.20 
      //   alert(package_value_for_c);
        var package_value  = package_value_for_c+amount;
       $("#lead_ori_packge_value").val(package_value_for_c);
 // alert(package_value+"package_value");
       $("#package_value").val(Math.round(package_value));
       $("#less_package_value").val(Math.round(package_value));
                var total_amount = (package_value * 18) / 100;
                $("#gst").val(Math.round(total_amount));

                if ($("#additional_author_copy_customized").val() != '' && $("#color_pages_customized").val() == '') {
         var a = subsidPrice*($("#additional_author_copy_customized").val());
         var b = 40*($("#additional_author_copy_customized").val());
         var c = Math.round(a)+Math.round(b);

         }else if($("#additional_author_copy_customized").val() != '' && $("#color_pages_customized").val() != ''){
          var number_of_color = parseInt($("#color_pages_customized").val());
         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
          var  a = number_of_color*7;
          var b = a + (a*0.15);
           var e = b*$("#additional_author_copy_customized").val();
           var f = subsidPrice*($("#additional_author_copy_customized").val());
         var g = 40*($("#additional_author_copy_customized").val());
         var c = Math.round(e)+Math.round(f)+Math.round(g);

         var  a = number_of_color*7;
            var b = a + (a *0.40);
            var h = b*$("#complimentry_copies").val();
            package_value = package_value + h
            $("#package_value").val(Math.round(package_value));
            $("#less_package_value").val(Math.round(package_value));
              total_amount = (package_value * 18) / 100;
                // console.log(total_amount+'total_amount')
                $("#gst").val(Math.round(total_amount));
         }else{
            var a = 7*no_of_pages;
        
             var b = parseInt(a) +parseInt(a*0.15);
            
             var f = b * $("#additional_author_copy_customized").val()
        
              var c = parseInt(b)+parseInt(f)
         
              var complimentry_c = $("#complimentry_copies").val()*a
              complimentry_c = complimentry_c+ (complimentry_c*0.40)
         package_value = package_value_for_c+complimentry_c
         $("#package_value").val(Math.round(package_value));
            $("#less_package_value").val(Math.round(package_value));
              total_amount = (package_value * 18) / 100;
                $("#gst").val(Math.round(total_amount));
         }
         }
         else{
            var c = 0;
           if ($("#color_pages_customized").val() != '') {
            var number_of_color = $("#color_pages_customized").val();
         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
            var  a = number_of_color*7;
            var b = a + (a *0.40);
            var c = b*$("#complimentry_copies").val();
            package_value = package_value + c
            $("#package_value").val(Math.round(package_value));
            $("#less_package_value").val(Math.round(package_value));
              total_amount = (package_value * 18) / 100;
                // console.log(total_amount+'total_amount')
                $("#gst").val(Math.round(total_amount));
           }else {
             var a = 7*no_of_pages;
             var b = a +(a*0.40);
             b = b*$("#complimentry_copies").val();
             package_value = package_value_for_c + b
            $("#package_value").val(Math.round(package_value));
            $("#less_package_value").val(Math.round(package_value));
              total_amount = (package_value * 18) / 100;
                // console.log(total_amount+'total_amount')
                $("#gst").val(Math.round(total_amount));
           }
         
         }
         }

                total_amount_data = total_amount + package_value;
                $("#total_amount").val(Math.round(total_amount_data));
                 
             
         $("#cost_of_additional").val(Math.round(c));
         var total_amount_d = $("#total_amount").val();
         var after_total_additional_copy = Math.round(c)+Math.round(total_amount_d);
                $("#additional_gross_amount").val(Math.round(after_total_additional_copy));
                total_amount_d = after_total_additional_copy;
                // console.log(total_amount_d+'total_amount_d')
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                            // console.log(discount_40+'discount_40')
                            // console.log(discount_20_+'discount_20_')
                                       document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
      }
 });
var i = 1;
var package_value_for_c = '';
        $(document).on('change', '#book_cover_sc', function() {
            if (i==1) {
                package_value_for_c = parseInt($("#package_value").val());
    i++;            }
                package_details = '';
              package_details = '<?php echo $leadData->lead_package_detail; ?>';
              if (package_details=='3') {
                var lead_package_name = '<?php echo $leadData->lead_package_name; ?>';
                var current_package_name = $("#package_value_sc").val();
                if (lead_package_name == current_package_name ) {
                      var package_value_for_edit = parseInt($("#lead_ori_packge_value").val());
                 var book_format = $(this).val();
                var no_of_pages = parseInt($("#number_of_pages_for_sc").val());
                // var no_of_pages = parseInt($("#number_of_pages_for_sc_for_edit").val());

                var book_size = $('#book_size_sc :selected').text();
            let booktype=0;
            // alert(book_format);
      if(book_format=='MultiColor' || book_format=='MultiColor 250 GSM'){
        // alert('test');
          if(no_of_pages <= 72){
            if(book_size =='5*8'){
              booktype=0.605
            }else if(book_size =='6*9'){
              booktype=0.718
            }else if(book_size =='8*11'){
              booktype=0.85
            }else{}
          }else if(no_of_pages >= 73 && no_of_pages <=96){
            if(book_size =='5*8'){
              booktype=0.605
            }else if(book_size =='6*9'){
              booktype=0.718
            }else if(book_size =='8*11'){
                booktype=0.85
              }else{}
          }else if(no_of_pages >= 97 && no_of_pages <=108 ){
            if(book_size == '5*8'){
              booktype=0.533
            }else if(book_size =='6*9'){
              booktype=0.602
            }else if(book_size =='8*11'){
              booktype=0.74
            }else{}
          }else if(no_of_pages >= 109 && no_of_pages <=128 ){
            if(book_size =='5*8'){
              booktype=0.533
            }else if(book_size =='6*9'){
              booktype=0.602
            }else if(book_size =='8*11'){
              booktype=0.70
            }else{}
          }else if(no_of_pages >= 129 && no_of_pages <=148 ){
              if(book_size =='5*8'){
                booktype=0.460
              }else if(book_size =='6*9'){
                booktype=0.573
              }else if(book_size =='8*11'){
                booktype=0.68
              }else{}
          }else if(no_of_pages >= 149 && no_of_pages <=192 ){
            if(book_size =='5*8'){
              booktype=0.460
            }else if(book_size =='6*9'){
              booktype=0.573
            }else if(book_size =='8*11'){
              booktype=0.62
            }else{}
          }else if(no_of_pages >= 193 && no_of_pages <=216 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.62
            }else{}
          }else if(no_of_pages >= 217 && no_of_pages <=252 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.59
            }else{}
          }else if(no_of_pages >= 253 && no_of_pages <=256 ){
            if(book_size =='5*8'){
              booktype=0.424
            }else if(book_size =='6*9'){
              booktype=0.537
            }else if(book_size =='8*11'){
              booktype=0.59
            }else{}
          }else if(no_of_pages >= 257 && no_of_pages <=276 ){
            if(book_size =='5*8'){
              booktype=0.413
            }else if(book_size =='6*9'){
              booktype=0.515
            }else if(book_size =='8*11'){
              booktype=0.59
            }else{}
          }else if(no_of_pages >= 277 && no_of_pages <=320 ){
            if(book_size =='5*8'){
              booktype=0.413
            }else if(book_size =='6*9'){
              booktype=0.515
            }else if(book_size =='8*11'){
              booktype=0.57
            }else{}
          }else if(no_of_pages >= 321 && no_of_pages <=352 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.57
            }else{}
          }else if(no_of_pages >= 353 && no_of_pages <=384 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.56
            }else{}
          }else if(no_of_pages >= 385 && no_of_pages <=432 ){
            if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else if(book_size =='8*11'){
              booktype=0.55
            }else{}
          }else if(no_of_pages >= 433 && no_of_pages <=492 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else if(no_of_pages >= 493 && no_of_pages <=548 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else if(no_of_pages >=549 ){
            if(book_size =='8*11'){
              booktype=0.54
            }if(book_size =='5*8'){
              booktype=0.405
            }else if(book_size =='6*9'){
              booktype=0.500
            }else{
            }
          }else{}
          console.log('test3')
          var production_cost = no_of_pages * booktype + 8.5;
                 var prodsubpercentage = production_cost * 0.15;
          var subsidPrice = production_cost + prodsubpercentage;
          subsidPrice = parseFloat(subsidPrice).toFixed(2);
          var cost =  production_cost + (production_cost*40)/100;
        var amount  = parseInt($("#number_of_complimentary_copies").val())*cost;
        var  number_additional_copy = $("#additional_author_copies_number").val();
       var package_value_for_cs = package_value_for_c
       var val_of_colorrr = 0;
        if ($('.color_pages').is(":checked")) {
          var number_of_color = $("#color_pages_number").val();
         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
         val_of_color = number_of_color*7;
         val_of_color_addi = val_of_color
         if ($("#additional_author_copies_number").val() != '') {
          val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
         }else{
          val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
         }
         }else{
          val_of_color = no_of_pages*7;
          val_of_color_addi = val_of_color
          val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
          val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
          amount =0;
         }
         if ($("#additional_author_copies_number").val() != '') {
         package_value_for_cs = package_value_for_c+val_of_colorrr;
         }else{
          package_value_for_cs = package_value_for_c+val_of_color;
         }
        }else{
             val_of_color = no_of_pages*7;
             // alert(no_of_pages)
             val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
             // alert(val_of_color)
        }
       
        var package_value  = (package_value_for_cs+(package_value_for_cs*0.40))+amount;
  var additional_author_copy = $("#additional_author_copies_number").val();
  if ($("#additional_author_copies_number").val() != '') {
    if ($('.color_pages').is(":checked")) {
         var val_of_color_of = val_of_color_addi*additional_author_copy
    }else{
         var val_of_color_of = additional_author_copy
        
    }
  // var val_of_color_of = val_of_color_addi*additional_author_copy
  }else{
    var val_of_color_of = val_of_color*additional_author_copy
  }
        val_of_color_of = val_of_color_of+(val_of_color_of*0.15);
        if ($("#color_pages_number").val() != '') {
          if (number_of_color <= total_color_par ) {
            var cost_total1 = subsidPrice*additional_author_copy;
          }else{
            var cost_total1 = additional_author_copy;
          }
        }else{
          var cost_total1 = subsidPrice*additional_author_copy;
        }
              var cost_total2 =  additional_author_copy*30;
              var cost_total = parseInt(cost_total1)+parseInt(cost_total2)+parseInt(val_of_color_of);
     $("#cost_of_additional").val(Math.round(cost_total));
       $("#package_value").val(Math.round(package_value));
       $("#less_package_value").val(Math.round(package_value));
                var total_amount = (package_value * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value;
                $("#total_amount").val(Math.round(total_amount_data));
                var after_total_additional_copy = cost_total+total_amount_data;
                $("#additional_gross_amount").val(Math.round(after_total_additional_copy));
                 var total_amount_d = $("#total_amount").val();
                 total_amount_d = after_total_additional_copy;
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                          document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
      }else if(book_format == 'Hardbound-Jacket'){
            var booktype1 = 0;
          if(no_of_pages <= 72){
            if(book_size =='5*8'){
                      booktype1=0.605
            }else if(book_size =='6*9'){
              booktype1=0.718
                     }else if(book_size =='8*11'){
              booktype1=0.85
                        }else{}
          }else if(no_of_pages >= 73 && no_of_pages <=96){
            if(book_size =='5*8'){
                            booktype1=0.605
            }else if(book_size =='6*9'){
              booktype1=0.718
                          }else if(book_size =='8*11'){
                booktype1=0.85
              }else{}
          }else if(no_of_pages >= 97 && no_of_pages <=108 ){
            if(book_size =='5*8'){
              booktype1=0.533
            }else if(book_size =='6*9'){
              booktype1=0.602
                          }else if(book_size =='8*11'){
              booktype1=0.74
            }else{}
          }else if(no_of_pages >= 109 && no_of_pages <=128 ){
            if(book_size =='5*8'){
              booktype1=0.533
            }else if(book_size =='6*9'){
              booktype1=0.602
            }else if(book_size =='8*11'){
              booktype1=0.70
            }else{}
          }else if(no_of_pages >= 129 && no_of_pages <=148 ){
              if(book_size =='5*8'){
                booktype1=0.460
              }else if(book_size =='6*9'){
                booktype1=0.573
              }else if(book_size =='8*11'){
                booktype1=0.68
              }else{}
          }else if(no_of_pages >= 149 && no_of_pages <=192 ){
            if(book_size =='5*8'){
              booktype1=0.460
            }else if(book_size =='6*9'){
              booktype1=0.573
            }else if(book_size =='8*11'){
              booktype1=0.62
            }else{}
          }else if(no_of_pages >= 193 && no_of_pages <=216 ){
            if(book_size =='5*8'){
              booktype1=0.424
            }else if(book_size =='6*9'){
              booktype1=0.537
            }else if(book_size =='8*11'){
              booktype1=0.62
            }else{}
          }else if(no_of_pages >= 217 && no_of_pages <=252 ){
            if(book_size =='5*8'){
              booktype1=0.424
            }else if(book_size =='6*9'){
              booktype1=0.537
            }else if(book_size =='8*11'){
              booktype1=0.59
              1
            }else{}
          }else if(no_of_pages >= 253 && no_of_pages <=256 ){
            if(book_size =='5*8'){
              booktype1=0.424
            }else if(book_size =='6*9'){
              booktype1=0.537
            }else if(book_size =='8*11'){
              booktype1=0.59
            }else{}
          }else if(no_of_pages >= 257 && no_of_pages <=276 ){
            if(book_size =='5*8'){
              booktype1=0.413
            }else if(book_size =='6*9'){
              booktype1=0.515
            }else if(book_size =='8*11'){
              booktype1=0.59
            }else{}
          }else if(no_of_pages >= 277 && no_of_pages <=320 ){
            if(book_size =='5*8'){
              booktype1=0.413
            }else if(book_size =='6*9'){
              booktype1=0.515
            }else if(book_size =='8*11'){
              booktype1=0.57
            }else{}
          }else if(no_of_pages >= 321 && no_of_pages <=352 ){
            if(book_size =='5*8'){
              booktype1=0.405
            }else if(book_size =='6*9'){
              booktype1=0.500
            }else if(book_size =='8*11'){
              booktype1=0.57
            }else{}
          }else if(no_of_pages >= 353 && no_of_pages <=384 ){
            if(book_size =='5*8'){
              booktype1=0.405
            }else if(book_size =='6*9'){
              booktype1=0.500
            }else if(book_size =='8*11'){
              booktype1=0.56
            }else{}
          }else if(no_of_pages >= 385 && no_of_pages <=432 ){
            if(book_size =='5*8'){
              booktype1=0.405
            }else if(book_size =='6*9'){
              booktype1=0.500
            }else if(book_size =='8*11'){
              booktype1=0.55
            }else{}
          }else if(no_of_pages >= 433 && no_of_pages <=492 ){
            if(book_size =='8*11'){
              booktype1=0.54
            }if(book_size =='5*8'){
              booktype1=0.405
            }else if(book_size =='6*9'){
              booktype1=0.500
            }else{
            }
          }else if(no_of_pages >= 493 && no_of_pages <=548 ){
            if(book_size =='8*11'){
              booktype1=0.54
            }if(book_size =='5*8'){
              booktype1=0.405
            }else if(book_size =='6*9'){
              booktype1=0.500
            }else{
            }
          }else if(no_of_pages >=549 ){
            if(book_size =='8*11'){
              booktype1=0.54
            }if(book_size =='5*8'){
              booktype1=0.405
            }else if(book_size =='6*9'){
              booktype1=0.500
            }else{
            }
          }else{}
          console.log('test4')
          var production_cost = no_of_pages * booktype1 + 55;
              var prodsubpercentage = production_cost * 0.15;
          var subsidPrice = production_cost + prodsubpercentage;
          subsidPrice = parseFloat(subsidPrice).toFixed(2);
// alert(subsidPrice+'Hardbound!1');
var additional_author_copy = $("#additional_author_copies_number").val();
var cost_total1 = subsidPrice*additional_author_copy;
              var cost_total2 =  additional_author_copy*40;
              var cost_total = cost_total1+cost_total2;
     $("#cost_of_additional").val(Math.round(cost_total));
       var cost =  production_cost + (production_cost*40)/100;
        var amount  = parseInt($("#number_of_complimentary_copies").val())*cost;
        var package_value  = (package_value_for_edit+(package_value_for_edit*0.40))+amount;
  //         alert(production_cost);
  // alert(cost);
  // alert(amount);
  // alert(package_value);
       $("#package_value").val(Math.round(package_value));
       $("#less_package_value").val(Math.round(package_value));
                var total_amount = (package_value * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value;
                $("#total_amount").val(Math.round(total_amount_data));
                 var after_total_additional_copy = cost_total+total_amount_data;
                $("#additional_gross_amount").val(Math.round(after_total_additional_copy));
                  var total_amount_d = $("#total_amount").val();
                   total_amount_d = after_total_additional_copy;
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                           document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
      }
                }else{
                    // if package is change but it is edit part
                      var book_format = $(this).val();
            var no_of_pages = parseInt($("#number_of_pages_for_sc").val());
            var book_size = $('#book_size_sc :selected').text();
           let booktype2=0;
          // alert(package_value_for_c);
      if(book_format=='MultiColor' || book_format=='MultiColor 250 GSM'){
          if(no_of_pages <= 72){
            if(book_size =='5*8'){
              booktype2=0.605
            }else if(book_size =='6*9'){
              booktype2=0.718
            }else if(book_size =='8*11'){
              booktype2=0.85
            }else{}
          }else if(no_of_pages >= 73 && no_of_pages <=96){
            if(book_size =='5*8'){
              booktype2=0.605
            }else if(book_size =='6*9'){
              booktype2=0.718
            }else if(book_size =='8*11'){
                booktype2=0.85
              }else{}
          }else if(no_of_pages >= 97 && no_of_pages <=108 ){
            if(book_size == '5*8'){
              booktype2=0.533
            }else if(book_size =='6*9'){
              booktype2=0.602
            }else if(book_size =='8*11'){
              booktype2=0.74
            }else{}
          }else if(no_of_pages >= 109 && no_of_pages <=128 ){
            if(book_size =='5*8'){
              booktype2=0.533
            }else if(book_size =='6*9'){
              booktype2=0.602
            }else if(book_size =='8*11'){
              booktype2=0.70
            }else{}
          }else if(no_of_pages >= 129 && no_of_pages <=148 ){
              if(book_size =='5*8'){
                booktype2=0.460
              }else if(book_size =='6*9'){
                booktype2=0.573
              }else if(book_size =='8*11'){
                booktype2=0.68
              }else{}
          }else if(no_of_pages >= 149 && no_of_pages <=192 ){
            if(book_size =='5*8'){
              booktype2=0.460
            }else if(book_size =='6*9'){
              booktype2=0.573
            }else if(book_size =='8*11'){
              booktype2=0.62
            }else{}
          }else if(no_of_pages >= 193 && no_of_pages <=216 ){
            if(book_size =='5*8'){
              booktype2=0.424
            }else if(book_size =='6*9'){
              booktype2=0.537
            }else if(book_size =='8*11'){
              booktype2=0.62
            }else{}
          }else if(no_of_pages >= 217 && no_of_pages <=252 ){
            if(book_size =='5*8'){
              booktype2=0.424
            }else if(book_size =='6*9'){
              booktype2=0.537
            }else if(book_size =='8*11'){
              booktype2=0.59
            }else{}
          }else if(no_of_pages >= 253 && no_of_pages <=256 ){
            if(book_size =='5*8'){
              booktype2=0.424
            }else if(book_size =='6*9'){
              booktype2=0.537
            }else if(book_size =='8*11'){
              booktype2=0.59
            }else{}
          }else if(no_of_pages >= 257 && no_of_pages <=276 ){
            if(book_size =='5*8'){
              booktype2=0.413
            }else if(book_size =='6*9'){
              booktype2=0.515
            }else if(book_size =='8*11'){
              booktype2=0.59
            }else{}
          }else if(no_of_pages >= 277 && no_of_pages <=320 ){
            if(book_size =='5*8'){
              booktype2=0.413
            }else if(book_size =='6*9'){
              booktype2=0.515
            }else if(book_size =='8*11'){
              booktype2=0.57
            }else{}
          }else if(no_of_pages >= 321 && no_of_pages <=352 ){
            if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else if(book_size =='8*11'){
              booktype2=0.57
            }else{}
          }else if(no_of_pages >= 353 && no_of_pages <=384 ){
            if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else if(book_size =='8*11'){
              booktype2=0.56
            }else{}
          }else if(no_of_pages >= 385 && no_of_pages <=432 ){
            if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else if(book_size =='8*11'){
              booktype2=0.55
            }else{}
          }else if(no_of_pages >= 433 && no_of_pages <=492 ){
            if(book_size =='8*11'){
              booktype2=0.54
            }if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else{
            }
          }else if(no_of_pages >= 493 && no_of_pages <=548 ){
            if(book_size =='8*11'){
              booktype2=0.54
            }if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else{
            }
          }else if(no_of_pages >=549 ){
            if(book_size =='8*11'){
              booktype2=0.54
            }if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else{
            }
          }else{}
          console.log('test5')
          var production_cost = no_of_pages * booktype2 + 8.5;
             var prodsubpercentage = production_cost * 0.15;
          var subsidPrice = production_cost + prodsubpercentage;
          subsidPrice = parseFloat(subsidPrice).toFixed(2);
// alert(subsidPrice+'MultiColor!1');
var additional_author_copy = $("#additional_author_copies_number").val();
// var  number_additional_copy = $("#additional_author_copies_number").val();
// var cost_total1 = subsidPrice*additional_author_copy;
//               var cost_total2 =  additional_author_copy*30;
//               var cost_total = cost_total1+cost_total2;
//      $("#cost_of_additional").val(Math.round(cost_total));
 var package_value_for_cs = package_value_for_c
      var val_of_colorrr = 0;
        if ($('.color_pages').is(":checked")) {
          var number_of_color = $("#color_pages_number").val();
         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
         val_of_color = number_of_color*7;
         val_of_color_addi = val_of_color
         if ($("#additional_author_copies_number").val() != '') {
          val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
         }else{
          val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
         }
         }else{
          val_of_color = no_of_pages*7;
          val_of_color_addi = val_of_color
          val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
          val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
          amount =0;
         }
         if ($("#additional_author_copies_number").val() != '') {
         package_value_for_cs = package_value_for_c+val_of_colorrr;
         }else{
          package_value_for_cs = package_value_for_c+val_of_color;
         }
        }else{
             val_of_color = no_of_pages*7;
               val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
        }
 if ($("#additional_author_copies_number").val() != '') {
    if ($('.color_pages').is(":checked")) {
         var val_of_color_of = val_of_color_addi*additional_author_copy
    }else{
         var val_of_color_of = additional_author_copy
        
    }
 
  }else{
    var val_of_color_of = val_of_color*additional_author_copy
  }
    if ($("#color_pages_number").val() != '') {
          if (number_of_color <= total_color_par ) {
            var cost_total1 = subsidPrice*additional_author_copy;
          }else{
            var cost_total1 = additional_author_copy;
          }
        }else{
          var cost_total1 = subsidPrice*additional_author_copy;
        }
              var cost_total2 =  additional_author_copy*30;
              // console.log(cost_total1+'cost_total1')
              // console.log(cost_total2+'cost_total2')
              // console.log(val_of_color_of+'val_of_color_of')
              var cost_total = parseInt(cost_total1)+parseInt(cost_total2)+parseInt(val_of_color_of);
     $("#cost_of_additional").val(Math.round(cost_total));
       var cost =  production_cost + (production_cost*40)/100;
        var amount  = parseInt($("#number_of_complimentary_copies").val())*cost;
        // if ($('.color_pages').is(":checked")) {
        //   var number_of_color = $("#color_pages_number").val();
        //  var total_color_par = no_of_pages * 0.30;
        //  if (number_of_color <= total_color_par ) {
        //  val_of_color = number_of_color*7;
        //  if (number_additional_copy != '') {
        //    var total_copy_add= parseInt($("#number_of_complimentary_copies").val())+parseInt(number_additional_copy);
        //     val_of_color = val_of_color*total_copy_add;
        //   }else{
        //     val_of_color = val_of_color*$("#number_of_complimentary_copies").val();
        //   }
        //  }else{
        //   val_of_color = no_of_pages*7;
        //   if (number_additional_copy != '') {
        //    var total_copy_add= parseInt($("#number_of_complimentary_copies").val())+parseInt(number_additional_copy);
        //     val_of_color = val_of_color*total_copy_add;
        //   }else{
        //     val_of_color = val_of_color*$("#number_of_complimentary_copies").val();
        //   }
        //  }
        // //  console.log(val_of_color)
        //  package_value_for_c = package_value_for_c+val_of_color;
        // }
        // var package_value  = (package_value_for_c+(package_value_for_c*0.40))+amount;
          if ($('.color_pages').is(":checked")) {
          var number_of_color = $("#color_pages_number").val();
         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
         val_of_color = number_of_color*7;
         val_of_color_addi = val_of_color
         if ($("#additional_author_copies_number").val() != '') {
          val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
         }else{
          val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
         }
         }else{
          val_of_color = no_of_pages*7;
          val_of_color_addi = val_of_color
          val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
          val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
          amount =0;
         }
         if ($("#additional_author_copies_number").val() != '') {
         package_value_for_cs = package_value_for_c+val_of_colorrr;
         console.log(package_value_for_c+' package_value_for_c');
         console.log(val_of_colorrr+' val_of_colorrr');
         }else{
          package_value_for_cs = package_value_for_c+val_of_color;
          console.log(package_value_for_c+' package_value_for_c1');
          console.log(val_of_color+' val_of_colorrr1');
         }
         console.log(package_value_for_cs+' package_value_for_cs');
        }else{
            console.log(package_value_for_cs+' package_value_for_cs');
             val_of_color = no_of_pages*7;
               val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
        }
       
        var package_value  = (package_value_for_cs+(package_value_for_cs*0.40))+amount;
  // alert(production_cost+' package_cost');
  // alert(cost+' cost');
  // alert(amount+' amount');
  console.log(package_value+' package_value');
   
    console.log(amount+' amount');
     // console.log(package_value+' package_value');
       $("#package_value").val(Math.round(package_value));
       $("#less_package_value").val(Math.round(package_value));
                var total_amount = (package_value * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value;
                $("#total_amount").val(Math.round(total_amount_data));
 var after_total_additional_copy = cost_total+total_amount_data;
                $("#additional_gross_amount").val(Math.round(after_total_additional_copy));
                 var total_amount_d = $("#total_amount").val();
                     total_amount_d = after_total_additional_copy;
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                          document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
      }else if(book_format == 'Hardbound-Jacket'){
        var booktype3 = 0;
          if(no_of_pages <= 72){
            if(book_size =='5*8'){
                      booktype3=0.605
            }else if(book_size =='6*9'){
              booktype3=0.718
                     }else if(book_size =='8*11'){
              booktype3=0.85
                        }else{}
          }else if(no_of_pages >= 73 && no_of_pages <=96){
            if(book_size =='5*8'){
                            booktype3=0.605
            }else if(book_size =='6*9'){
              booktype3=0.718
                          }else if(book_size =='8*11'){
                booktype3=0.85
              }else{}
          }else if(no_of_pages >= 97 && no_of_pages <=108 ){
            if(book_size =='5*8'){
              booktype3=0.533
            }else if(book_size =='6*9'){
              booktype3=0.602
                          }else if(book_size =='8*11'){
              booktype3=0.74
            }else{}
          }else if(no_of_pages >= 109 && no_of_pages <=128 ){
            if(book_size =='5*8'){
              booktype3=0.533
            }else if(book_size =='6*9'){
              booktype3=0.602
            }else if(book_size =='8*11'){
              booktype3=0.70
            }else{}
          }else if(no_of_pages >= 129 && no_of_pages <=148 ){
              if(book_size =='5*8'){
                booktype3=0.460
              }else if(book_size =='6*9'){
                booktype3=0.573
              }else if(book_size =='8*11'){
                booktype3=0.68
              }else{}
          }else if(no_of_pages >= 149 && no_of_pages <=192 ){
            if(book_size =='5*8'){
              booktype3=0.460
            }else if(book_size =='6*9'){
              booktype3=0.573
            }else if(book_size =='8*11'){
              booktype3=0.62
            }else{}
          }else if(no_of_pages >= 193 && no_of_pages <=216 ){
            if(book_size =='5*8'){
              booktype3=0.424
            }else if(book_size =='6*9'){
              booktype3=0.537
            }else if(book_size =='8*11'){
              booktype3=0.62
            }else{}
          }else if(no_of_pages >= 217 && no_of_pages <=252 ){
            if(book_size =='5*8'){
              booktype3=0.424
            }else if(book_size =='6*9'){
              booktype3=0.537
            }else if(book_size =='8*11'){
              booktype3=0.59
              1
            }else{}
          }else if(no_of_pages >= 253 && no_of_pages <=256 ){
            if(book_size =='5*8'){
              booktype3=0.424
            }else if(book_size =='6*9'){
              booktype3=0.537
            }else if(book_size =='8*11'){
              booktype3=0.59
            }else{}
          }else if(no_of_pages >= 257 && no_of_pages <=276 ){
            if(book_size =='5*8'){
              booktype3=0.413
            }else if(book_size =='6*9'){
              booktype3=0.515
            }else if(book_size =='8*11'){
              booktype3=0.59
            }else{}
          }else if(no_of_pages >= 277 && no_of_pages <=320 ){
            if(book_size =='5*8'){
              booktype3=0.413
            }else if(book_size =='6*9'){
              booktype3=0.515
            }else if(book_size =='8*11'){
              booktype3=0.57
            }else{}
          }else if(no_of_pages >= 321 && no_of_pages <=352 ){
            if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else if(book_size =='8*11'){
              booktype3=0.57
            }else{}
          }else if(no_of_pages >= 353 && no_of_pages <=384 ){
            if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else if(book_size =='8*11'){
              booktype3=0.56
            }else{}
          }else if(no_of_pages >= 385 && no_of_pages <=432 ){
            if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else if(book_size =='8*11'){
              booktype3=0.55
            }else{}
          }else if(no_of_pages >= 433 && no_of_pages <=492 ){
            if(book_size =='8*11'){
              booktype3=0.54
            }if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else{
            }
          }else if(no_of_pages >= 493 && no_of_pages <=548 ){
            if(book_size =='8*11'){
              booktype3=0.54
            }if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else{
            }
          }else if(no_of_pages >=549 ){
            if(book_size =='8*11'){
              booktype3=0.54
            }if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else{
            }
          }else{}
          console.log('test6')
          // console.log(no_of_pages+'no_of_pages')
          // console.log(booktype3+'booktype3')
          var production_cost = no_of_pages * booktype3 + 55;
          // console.log(production_cost+'production_cost')
       var cost =  production_cost + (production_cost*40)/100;
       // console.log(cost+'cost')
        var amount  = parseInt($("#number_of_complimentary_copies").val())*cost;
        // console.log(amount+'amount')
     var  number_additional_copy = $("#additional_author_copies_number").val();
     // console.log(number_additional_copy+'number_additional_copy')
        // if ($('.color_pages').is(":checked")) {
        //   var number_of_color = $("#color_pages_number").val();
        //  var total_color_par = no_of_pages * 0.30;
        //  if (number_of_color <= total_color_par ) {
        //  val_of_color = number_of_color*7;
        //   if (number_additional_copy != '') {
        //    var total_copy_add= parseInt($("#number_of_complimentary_copies").val())+parseInt(number_additional_copy);
        //     val_of_color = val_of_color*total_copy_add;
        //   }else{
        //     val_of_color = val_of_color*$("#number_of_complimentary_copies").val();
        //   }
         
        //  }else{
        //   val_of_color = no_of_pages*7;
        //   if (number_additional_copy != '') {
        //    var total_copy_add= parseInt($("#number_of_complimentary_copies").val())+parseInt(number_additional_copy);
        //     val_of_color = val_of_color*total_copy_add;
        //   }else{
        //     val_of_color = val_of_color*$("#number_of_complimentary_copies").val();
        //   }
        //  }
        //  package_value_for_c = package_value_for_c+val_of_color;
        //  console.log(val_of_color)
        //  console.log(package_value_for_c+"package_value_for_c")
        // }
        if ($('.color_pages').is(":checked")) {
            var number_of_color = $("#color_pages_number").val();
            var total_color_par = no_of_pages * 0.30;
            if (number_of_color <= total_color_par ) {
            val_of_color = number_of_color*7;
            val_of_color_addi = val_of_color
            if ($("#additional_author_copies_number").val() != '') {
            val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
            }else{
            val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
            }
            }else{
            val_of_color = no_of_pages*7;
            val_of_color_addi = val_of_color
            val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
            val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
            amount =parseInt($("#number_of_complimentary_copies").val())*55;
            amount =amount+(amount*0.40);
            }
            if ($("#additional_author_copies_number").val() != '') {
            package_value_for_c = package_value_for_c+val_of_colorrr;
            }else{
            package_value_for_c = package_value_for_c+val_of_color;
            }
            }else{
               val_of_color = no_of_pages*7;
               val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
                
            }
        var package_value  = (package_value_for_c+(package_value_for_c*0.40))+amount;
        // console.log(package_value+"package_value")
          var prodsubpercentage = production_cost * 0.15;
          var subsidPrice = production_cost + prodsubpercentage;
          subsidPrice = parseFloat(subsidPrice).toFixed(2);
// // alert(subsidPrice+'Hardbound');
//     var additional_author_copy = $("#additional_author_copies_number").val();
//     var cost_total1 = subsidPrice*additional_author_copy;
//               var cost_total2 =  additional_author_copy*40;
//               var cost_total = cost_total1+cost_total2;
//      $("#cost_of_additional").val(Math.round(cost_total));
var additional_author_copy = $("#additional_author_copies_number").val();
if ($("#additional_author_copies_number").val() != '') {
    if ($('.color_pages').is(":checked")) {
         var val_of_color_of = val_of_color_addi*additional_author_copy
    }else{
         var val_of_color_of = additional_author_copy
    }
}else{
var val_of_color_of = val_of_color*additional_author_copy
}
val_of_color_of = val_of_color_of+(val_of_color_of*0.15);
if ($("#color_pages_number").val() != '') {
if (number_of_color <= total_color_par ) {
var cost_total1 = subsidPrice*additional_author_copy;
// console.log(subsidPrice+'subsidPrice')
// console.log(additional_author_copy+'additional_author_copy')
}else{
var cost_total1 = additional_author_copy*55;
}
}else{
var cost_total1 = subsidPrice*additional_author_copy;
}
 var cost_total2 =  additional_author_copy*40;
 // console.log(cost_total1+'cost_total1')
 // console.log(cost_total2+'cost_total2')
 // console.log(val_of_color_of+'val_of_color_of')
 var cost_total = parseInt(cost_total1)+parseInt(cost_total2)+parseInt(val_of_color_of);
$("#cost_of_additional").val(Math.round(cost_total));
       $("#package_value").val(Math.round(package_value));
       $("#less_package_value").val(Math.round(package_value));
                var total_amount = (package_value * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value;
                    var after_total_additional_copy = cost_total+total_amount_data;
                $("#additional_gross_amount").val(Math.round(after_total_additional_copy));
                $("#total_amount").val(Math.round(total_amount_data));
                 var total_amount_d = $("#total_amount").val();
                 total_amount_d = after_total_additional_copy;
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
      }
                }
              }else{
                //here start the create code for multicolor_code
                 var book_format = $(this).val();
            var no_of_pages = parseInt($("#number_of_pages_for_sc").val());
            var book_size = $('#book_size_sc :selected').text();
           let booktype2=0;
          // alert(package_value_for_c);
      if(book_format=='MultiColor' || book_format=='MultiColor 250 GSM'){
          if(no_of_pages <= 72){
            if(book_size =='5*8'){
              booktype2=0.605
            }else if(book_size =='6*9'){
              booktype2=0.718
            }else if(book_size =='8*11'){
              booktype2=0.85
            }else{}
          }else if(no_of_pages >= 73 && no_of_pages <=96){
            if(book_size =='5*8'){
              booktype2=0.605
            }else if(book_size =='6*9'){
              booktype2=0.718
            }else if(book_size =='8*11'){
                booktype2=0.85
              }else{}
          }else if(no_of_pages >= 97 && no_of_pages <=108 ){
            if(book_size == '5*8'){
              booktype2=0.533
            }else if(book_size =='6*9'){
              booktype2=0.602
            }else if(book_size =='8*11'){
              booktype2=0.74
            }else{}
          }else if(no_of_pages >= 109 && no_of_pages <=128 ){
            if(book_size =='5*8'){
              booktype2=0.533
            }else if(book_size =='6*9'){
              booktype2=0.602
            }else if(book_size =='8*11'){
              booktype2=0.70
            }else{}
          }else if(no_of_pages >= 129 && no_of_pages <=148 ){
              if(book_size =='5*8'){
                booktype2=0.460
              }else if(book_size =='6*9'){
                booktype2=0.573
              }else if(book_size =='8*11'){
                booktype2=0.68
              }else{}
          }else if(no_of_pages >= 149 && no_of_pages <=192 ){
            if(book_size =='5*8'){
              booktype2=0.460
            }else if(book_size =='6*9'){
              booktype2=0.573
            }else if(book_size =='8*11'){
              booktype2=0.62
            }else{}
          }else if(no_of_pages >= 193 && no_of_pages <=216 ){
            if(book_size =='5*8'){
              booktype2=0.424
            }else if(book_size =='6*9'){
              booktype2=0.537
            }else if(book_size =='8*11'){
              booktype2=0.62
            }else{}
          }else if(no_of_pages >= 217 && no_of_pages <=252 ){
            if(book_size =='5*8'){
              booktype2=0.424
            }else if(book_size =='6*9'){
              booktype2=0.537
            }else if(book_size =='8*11'){
              booktype2=0.59
            }else{}
          }else if(no_of_pages >= 253 && no_of_pages <=256 ){
            if(book_size =='5*8'){
              booktype2=0.424
            }else if(book_size =='6*9'){
              booktype2=0.537
            }else if(book_size =='8*11'){
              booktype2=0.59
            }else{}
          }else if(no_of_pages >= 257 && no_of_pages <=276 ){
            if(book_size =='5*8'){
              booktype2=0.413
            }else if(book_size =='6*9'){
              booktype2=0.515
            }else if(book_size =='8*11'){
              booktype2=0.59
            }else{}
          }else if(no_of_pages >= 277 && no_of_pages <=320 ){
            if(book_size =='5*8'){
              booktype2=0.413
            }else if(book_size =='6*9'){
              booktype2=0.515
            }else if(book_size =='8*11'){
              booktype2=0.57
            }else{}
          }else if(no_of_pages >= 321 && no_of_pages <=352 ){
            if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else if(book_size =='8*11'){
              booktype2=0.57
            }else{}
          }else if(no_of_pages >= 353 && no_of_pages <=384 ){
            if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else if(book_size =='8*11'){
              booktype2=0.56
            }else{}
          }else if(no_of_pages >= 385 && no_of_pages <=432 ){
            if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else if(book_size =='8*11'){
              booktype2=0.55
            }else{}
          }else if(no_of_pages >= 433 && no_of_pages <=492 ){
            if(book_size =='8*11'){
              booktype2=0.54
            }if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else{
            }
          }else if(no_of_pages >= 493 && no_of_pages <=548 ){
            if(book_size =='8*11'){
              booktype2=0.54
            }if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else{
            }
          }else if(no_of_pages >=549 ){
            if(book_size =='8*11'){
              booktype2=0.54
            }if(book_size =='5*8'){
              booktype2=0.405
            }else if(book_size =='6*9'){
              booktype2=0.500
            }else{
            }
          }else{}
          console.log('test8')
          var production_cost = no_of_pages * booktype2 + 8.5;
             var prodsubpercentage = production_cost * 0.15;
          var subsidPrice = production_cost + prodsubpercentage;
          subsidPrice = parseFloat(subsidPrice).toFixed(2);

       var cost =  production_cost + (production_cost*40)/100;
        var amount  = parseInt($("#number_of_complimentary_copies").val())*cost;
        var  number_additional_copy = $("#additional_author_copies_number").val();
       var package_value_for_cs = package_value_for_c
       var val_of_colorrr = 0;
        if ($('.color_pages').is(":checked")) {
          var number_of_color = $("#color_pages_number").val();
         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
         val_of_color = number_of_color*7;
         val_of_color_addi = val_of_color
         if ($("#additional_author_copies_number").val() != '') {
          val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
         }else{
          val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
         }
         }else{
          val_of_color = no_of_pages*7;
          val_of_color_addi = val_of_color
          val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
          val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
          amount =0;
         }
         if ($("#additional_author_copies_number").val() != '') {
         package_value_for_cs = package_value_for_c+val_of_colorrr;
         }else{
          package_value_for_cs = package_value_for_c+val_of_color;
         }
        }else{
             val_of_color = no_of_pages*7;
               val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
        }
       
        var package_value  = (package_value_for_cs+(package_value_for_cs*0.40))+amount;
  var additional_author_copy = $("#additional_author_copies_number").val();
  if ($("#additional_author_copies_number").val() != '') {
    if ($('.color_pages').is(":checked")) {
         var val_of_color_of = val_of_color_addi*additional_author_copy
    }else{
         var val_of_color_of = additional_author_copy
        
    }
 
  }else{
    var val_of_color_of = val_of_color*additional_author_copy
  }
        //val_of_color_of = val_of_color_of+(val_of_color_of*0.15);
        if ($("#color_pages_number").val() != '') {
          if (number_of_color <= total_color_par ) {
            var cost_total1 = subsidPrice*additional_author_copy;
          }else{
            var cost_total1 = additional_author_copy;
          }
        }else{
          var cost_total1 = subsidPrice*additional_author_copy;
        }
              var cost_total2 =  additional_author_copy*30;
              var cost_total = parseInt(cost_total1)+parseInt(cost_total2)+parseInt(val_of_color_of);
     $("#cost_of_additional").val(Math.round(cost_total));
       $("#package_value").val(Math.round(package_value));
       $("#less_package_value").val(Math.round(package_value));
                var total_amount = (package_value * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value;
                $("#total_amount").val(Math.round(total_amount_data));
                var after_total_additional_copy = cost_total+total_amount_data;
                $("#additional_gross_amount").val(Math.round(after_total_additional_copy));
                 var total_amount_d = $("#total_amount").val();
                 if ($("#additional_author_copies_number").val() != '') {        
                 total_amount_d = after_total_additional_copy;
                 }else{
                  total_amount_d = total_amount_data;
                 }
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                          document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
      }else if(book_format == 'Hardbound-Jacket'){
//this is for create package time and for hardbound
        var booktype3 = 0;
          if(no_of_pages <= 72){
            if(book_size =='5*8'){
                      booktype3=0.605
            }else if(book_size =='6*9'){
              booktype3=0.718
                     }else if(book_size =='8*11'){
              booktype3=0.85
                        }else{}
          }else if(no_of_pages >= 73 && no_of_pages <=96){
            if(book_size =='5*8'){
                            booktype3=0.605
            }else if(book_size =='6*9'){
              booktype3=0.718
                          }else if(book_size =='8*11'){
                booktype3=0.85
              }else{}
          }else if(no_of_pages >= 97 && no_of_pages <=108 ){
            if(book_size =='5*8'){
              booktype3=0.533
            }else if(book_size =='6*9'){
              booktype3=0.602
                          }else if(book_size =='8*11'){
              booktype3=0.74
            }else{}
          }else if(no_of_pages >= 109 && no_of_pages <=128 ){
            if(book_size =='5*8'){
              booktype3=0.533
            }else if(book_size =='6*9'){
              booktype3=0.602
            }else if(book_size =='8*11'){
              booktype3=0.70
            }else{}
          }else if(no_of_pages >= 129 && no_of_pages <=148 ){
              if(book_size =='5*8'){
                booktype3=0.460
              }else if(book_size =='6*9'){
                booktype3=0.573
              }else if(book_size =='8*11'){
                booktype3=0.68
              }else{}
          }else if(no_of_pages >= 149 && no_of_pages <=192 ){
            if(book_size =='5*8'){
              booktype3=0.460
            }else if(book_size =='6*9'){
              booktype3=0.573
            }else if(book_size =='8*11'){
              booktype3=0.62
            }else{}
          }else if(no_of_pages >= 193 && no_of_pages <=216 ){
            if(book_size =='5*8'){
              booktype3=0.424
            }else if(book_size =='6*9'){
              booktype3=0.537
            }else if(book_size =='8*11'){
              booktype3=0.62
            }else{}
          }else if(no_of_pages >= 217 && no_of_pages <=252 ){
            if(book_size =='5*8'){
              booktype3=0.424
            }else if(book_size =='6*9'){
              booktype3=0.537
            }else if(book_size =='8*11'){
              booktype3=0.59
              1
            }else{}
          }else if(no_of_pages >= 253 && no_of_pages <=256 ){
            if(book_size =='5*8'){
              booktype3=0.424
            }else if(book_size =='6*9'){
              booktype3=0.537
            }else if(book_size =='8*11'){
              booktype3=0.59
            }else{}
          }else if(no_of_pages >= 257 && no_of_pages <=276 ){
            if(book_size =='5*8'){
              booktype3=0.413
            }else if(book_size =='6*9'){
              booktype3=0.515
            }else if(book_size =='8*11'){
              booktype3=0.59
            }else{}
          }else if(no_of_pages >= 277 && no_of_pages <=320 ){
            if(book_size =='5*8'){
              booktype3=0.413
            }else if(book_size =='6*9'){
              booktype3=0.515
            }else if(book_size =='8*11'){
              booktype3=0.57
            }else{}
          }else if(no_of_pages >= 321 && no_of_pages <=352 ){
            if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else if(book_size =='8*11'){
              booktype3=0.57
            }else{}
          }else if(no_of_pages >= 353 && no_of_pages <=384 ){
            if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else if(book_size =='8*11'){
              booktype3=0.56
            }else{}
          }else if(no_of_pages >= 385 && no_of_pages <=432 ){
            if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else if(book_size =='8*11'){
              booktype3=0.55
            }else{}
          }else if(no_of_pages >= 433 && no_of_pages <=492 ){
            if(book_size =='8*11'){
              booktype3=0.54
            }if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else{
            }
          }else if(no_of_pages >= 493 && no_of_pages <=548 ){
            if(book_size =='8*11'){
              booktype3=0.54
            }if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else{
            }
          }else if(no_of_pages >=549 ){
            if(book_size =='8*11'){
              booktype3=0.54
            }if(book_size =='5*8'){
              booktype3=0.405
            }else if(book_size =='6*9'){
              booktype3=0.500
            }else{
            }
          }else{}
          console.log('test10')
          var production_cost = no_of_pages * booktype3 + 55;
          console.log(production_cost+'production_cost')
             var prodsubpercentage = production_cost * 0.15;
          var subsidPrice = production_cost + prodsubpercentage;
          subsidPrice = parseFloat(subsidPrice).toFixed(2);
       var cost =  production_cost + (production_cost*40)/100;
       console.log(cost+'cost')
        var amount  = parseInt($("#number_of_complimentary_copies").val())*cost;
         console.log(amount+'amount')
        var  number_additional_copy = $("#additional_author_copies_number").val();
var package_value_for_cs = package_value_for_c
var val_of_colorrr = 0;
if ($('.color_pages').is(":checked")) {
var number_of_color = $("#color_pages_number").val();
var total_color_par = no_of_pages * 0.30;
if (number_of_color <= total_color_par ) {
val_of_color = number_of_color*7;
val_of_color_addi = val_of_color
if ($("#additional_author_copies_number").val() != '') {
val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
}else{
val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
}
}else{
val_of_color = no_of_pages*7;
val_of_color_addi = val_of_color
val_of_colorrr =  val_of_color*parseInt($("#number_of_complimentary_copies").val())
val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
amount =parseInt($("#number_of_complimentary_copies").val())*55;
amount =amount+(amount*0.40);
}
if ($("#additional_author_copies_number").val() != '') {
package_value_for_cs = package_value_for_c+val_of_colorrr;
}else{
package_value_for_cs = package_value_for_c+val_of_color;
}
}else{
   val_of_color = no_of_pages*7;
   val_of_color = val_of_color*parseInt($("#number_of_complimentary_copies").val())
    
}

var package_value  = (package_value_for_cs+(package_value_for_cs*0.40))+amount;
console.log(package_value+'package_value')
var additional_author_copy = $("#additional_author_copies_number").val();
if ($("#additional_author_copies_number").val() != '') {
    if ($('.color_pages').is(":checked")) {
         var val_of_color_of = val_of_color_addi*additional_author_copy
    }else{
         var val_of_color_of = additional_author_copy
    }
}else{
var val_of_color_of = val_of_color*additional_author_copy
}
val_of_color_of = val_of_color_of+(val_of_color_of*0.15);
if ($("#color_pages_number").val() != '') {
if (number_of_color <= total_color_par ) {
var cost_total1 = subsidPrice*additional_author_copy;
}else{
var cost_total1 = additional_author_copy*55;
}
}else{
var cost_total1 = subsidPrice*additional_author_copy;
}
 var cost_total2 =  additional_author_copy*40;
 var cost_total = parseInt(cost_total1)+parseInt(cost_total2)+parseInt(val_of_color_of);
$("#cost_of_additional").val(Math.round(cost_total));
$("#package_value").val(Math.round(package_value));
$("#less_package_value").val(Math.round(package_value));
   var total_amount = (package_value * 18) / 100;
   $("#gst").val(Math.round(total_amount));
   total_amount_data = total_amount + package_value;
   $("#total_amount").val(Math.round(total_amount_data));
   var after_total_additional_copy = cost_total+total_amount_data;
   $("#additional_gross_amount").val(Math.round(after_total_additional_copy));
    var total_amount_d = $("#total_amount").val();
    total_amount_d = after_total_additional_copy;
                               var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                               var discount_40_ = (40 / 100) * total_amount_d;
                               var discount_20 = total_amount_d - (discount_40 + discount_40);
                               var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                               var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                             document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                               document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                               document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                               document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                               document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                               document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
      }
              }
        });
  function myFunctiondatacomplimentry(id, cost) {
        var no_of_copy = $("#complimentry_copies").val();
        var cost = no_of_copy*100;
        $('#check_box'+id).attr('data-cost', cost);
        // $('.check_box'+id).val(cost);
        }
  var count_data_copies = 0;
         function myFunctiondatacopies(f_id, f_cost) {
         //alert('test');
           if(count_data_copies==0){
          //     if ($('#myCheck'+f_id).prop("checked")) {
          // $('#myCheck'+f_id).removeAttr("checked");
          //       total_cost_data_sc =f_cost;
          //   }else{
                total_cost_data_sc = 0;
            // }
               var package_value = $("#package_value").val();
            var package_value_for_edit = $("#lead_ori_packge_value").val();
               minus_of_data_sc =package_value - total_cost_data_sc;
               minus_of_data_sc_for_edit =package_value_for_edit - total_cost_data_sc;
              $("#package_value").val(Math.round(minus_of_data_sc));
              $("#lead_ori_packge_value").val(Math.round(minus_of_data_sc_for_edit));
              var package_value_data = parseInt($("#package_value").val());
                $("#less_package_value").val(Math.round(package_value_data));
                var total_amount = (package_value_data * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value_data;
                $("#total_amount").val(Math.round(total_amount_data));
                var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                           document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
            } count_data_copies++;
        var per_copy = f_cost/100;
        var single_copy_cost = f_cost/per_copy;
        var value = document.getElementById('number_of_complimentary_copies').value;
         var total_cost = (single_copy_cost*value);
         $('#myCheck'+f_id).attr('onclick','myFunction('+f_id+','+total_cost+')');
         $('#sub_service_cost_sc'+f_id).val(total_cost);
        }
          var all_click_cost = 0;
        function myFunction(data, data1) {
          // alert(data)
          
            var package_d_data = '';
            package_d_data = '<?php echo $leadData->lead_package_detail; ?>';
            if (package_d_data == 3) {
              $(document).on('click', '.color_pages', function() {
    if($(this).is(":checked")) {
    // alert('test');
        $("#color_pages_number").show();
    } else {
        $("#color_pages_number").hide();
    }
});
$(document).on('keyup', '#color_pages_number', function() {
 var id = $('.color_pages').attr("data-id");
  var number_of_color = $("#color_pages_number").val();
  var no_of_pages = $("#number_of_pages_for_sc").val();

         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
         val_of_color = number_of_color*7;
         }else{
          val_of_color = no_of_pages*7;
         } 
         
         $('#sub_service_cost_sc'+id).val(val_of_color);
    
});
                 $(document).on('click', '.additional_author_copy', function() {
    if($(this).is(":checked")) {
    // alert('test');
        $("#additional_author_copies_number").show();
        $(".cost_additional_copy").show();
        $(".additional_gross_amt").show();
        $("#showing_additional_cost").show();
        $("#showing_gross_additional_cost").show();
    } else {
    // alert('testtt');
        $("#additional_author_copies_number").hide();
        $(".cost_additional_copy").hide();
        $(".additional_gross_amt").hide();
        $("#showing_additional_cost").hide();
        $("#showing_gross_additional_cost").hide();
    }
});


//custimze standadrd edit part
                all_click_cost_original = parseInt($("#lead_ori_packge_value").val());
                all_click_cost = parseInt($("#package_value").val());
                var checkBox = document.getElementById("myCheck" + data);
                if (checkBox.checked == true) {
                   
                    
                    var book_type_sc = $("#book_type_sc").val();
                       if (book_type_sc == 'paperback') {
                        all_click_cost += data1;
                   
                   all_click_cost_original += data1;
                            var dropDown = document.getElementById("book_cover_sc");
                        dropDown.selectedIndex = 0;
                        }else if (book_type_sc == 'ebook') {
                        
                          
                         
                data1 = data1 + (data1*0.40);
             
            
                    all_click_cost += data1;
               }else{}
                } else {
                    all_click_cost -= data1;
                    all_click_cost_original -= data1;
                      var book_type_sc = $("#book_type_sc").val();
                       if (book_type_sc == 'paperback') {
                            var dropDown = document.getElementById("book_cover_sc");
                        dropDown.selectedIndex = 0;
                        }else{
                            all_click_cost = all_click_cost + (all_click_cost*0.40);
                        }
                }
                $("#package_value").val(Math.round(all_click_cost));
                // alert(all_click_cost);
                $("#lead_ori_packge_value").val(Math.round(all_click_cost_original));
                $("#less_package_value").val(Math.round(all_click_cost));
                var total_amount = (all_click_cost * 18) / 100;
                $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + all_click_cost;
                 var cost_of_additional =   $("#cost_of_additional").val();
                var total_with_gross = parseInt(total_amount_data)+parseInt(cost_of_additional);
                $("#additional_gross_amount").val(Math.round(total_with_gross));
                $("#total_amount").val(Math.round(total_amount_data));
                var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
            } else {
                //standard custmize create part start here
                $(document).on('click', '.color_pages', function() {
    if($(this).is(":checked")) {
    // alert('test');
        $("#color_pages_number").show();
    } else {
        $("#color_pages_number").hide();
    }
});
$(document).on('keyup', '#color_pages_number', function() {
 var id = $('.color_pages').attr("data-id");
  var number_of_color = $("#color_pages_number").val();
  var no_of_pages = $("#number_of_pages_for_sc").val();

         var total_color_par = no_of_pages * 0.30;
         if (number_of_color <= total_color_par ) {
         val_of_color = number_of_color*7;
         }else{
          val_of_color = no_of_pages*7;
         } 
         
         $('#sub_service_cost_sc'+id).val(val_of_color);
    
});
    $(document).on('click', '.additional_author_copy', function() {
    if($(this).is(":checked")) {
    // alert('test');
        $("#additional_author_copies_number").show();
        $(".cost_additional_copy").show();
        $(".additional_gross_amt").show();
        $("#showing_additional_cost").show();
        $("#showing_gross_additional_cost").show();
    } else {
        $("#additional_author_copies_number").hide();
        $(".cost_additional_copy").hide();
        $(".additional_gross_amt").hide();
        $("#showing_additional_cost").hide();
        $("#showing_gross_additional_cost").hide();
    }
});



                $(document).on('change', '#package_value_sc', function() {
                     document.getElementById("prec_40").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_40_").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_20").innerHTML = '(₹ ' + 0 + ')';
                    document.getElementById("prec_400").innerHTML = '₹ ' + 0 + '';
                    document.getElementById("prec_400_").innerHTML = '₹ ' + 0 + '';
                    document.getElementById("prec_200").innerHTML = '₹ ' + 0 + '';
                    all_click_cost = 0;
                    $(".dynamicservice ul li.subservicesstand_sc").removeAttr("checked");
                })
                all_click_cost = parseInt($("#package_value").val());
              
                var checkBox = document.getElementById("myCheck" + data);
                // console.log(checkBox)
                var book_type_dis = $("#book_type_sc").val();
                if (checkBox.checked == true) {
                    // alert(data1);
                    if (book_type_dis == 'ebook') {
                data1 = data1 + (data1*0.40);
               }
            
                    all_click_cost += data1;
                } else {
                       if (book_type_dis == 'ebook') {
                data1 = data1 + (data1*0.40);
               }
              //  alert(data1);
              //  alert(all_click_cost)
                    all_click_cost -= data1;
                }
                $("#package_value").val(Math.round(all_click_cost));
               var package_value = parseInt($("#package_value").val());
              // alert(typeof(package_value));
              $("#lead_ori_packge_value").val(Math.round(all_click_cost));
                $("#less_package_value").val(Math.round(package_value));
                var total_amount = (package_value * 18) / 100;
               $("#gst").val(Math.round(total_amount));
                total_amount_data = total_amount + package_value;
               var cost_of_additional =   $("#cost_of_additional").val();
                var total_with_gross = parseInt(total_amount_data)+parseInt(cost_of_additional);
                $("#additional_gross_amount").val(Math.round(total_with_gross));
                $("#total_amount").val(Math.round(total_amount_data));
                                            var total_amount_d = $("#total_amount").val();
                                            var discount_40 = ((40 / 100) * total_amount_d).toFixed(2);
                                            var discount_40_ = (40 / 100) * total_amount_d;
                                            var discount_20 = total_amount_d - (discount_40 + discount_40);
                                            var discount_20_ = ((20 / 100) * total_amount_d).toFixed(2);
                                            var discount_20_1 = ((20 / 100) * total_amount_d);
                                            $("#first_p1").val(discount_40_)
                            $("#first_p2").val(discount_40_)
                            $("#first_p3").val(discount_20_1)
                                            document.getElementById("prec_40").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_40_").innerHTML = '(₹ ' + Math.round(discount_40) + ')';
                                            document.getElementById("prec_20").innerHTML = '(₹ ' + Math.round(discount_20_) + ')';
                                            document.getElementById("prec_400").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_400_").innerHTML = '₹ ' + Math.round(discount_40) + '';
                                            document.getElementById("prec_200").innerHTML = '₹ ' + Math.round(discount_20_) + '';
                // $("#total_amount").val(total_amount_data);
            }
        }
        $(".book_type_standard_and_custum").change(function() {
            var book_type = $(this).val();
            $('#book_type_value').val(book_type);
        });

        $(".preview-btn").click(function() {
          if($("#package_details").val() == 3 && $("#book_type_sc").val() == 'paperback'){
         let   hasDisabledAttr =  document.querySelector('#book_size_sc').hasAttribute('disabled');

          if(hasDisabledAttr === true){
            alert('Please Select Book Specifications');
            return false;
          }else{
          
          }
          }
          
          var popup_data_amount = $('.full_payment_or_installment:checked').val();
          if (popup_data_amount == 'def') {
            $('.final_hide_default').show()
            $('.final_hide_full').hide()
            $('.final_hide_inst').hide()
          }else if(popup_data_amount == 'full'){
            
            
    //   var first_a1 = $('#first_p1').val(); 
    // var first_a2 = $('#first_p2').val(); 
    // var first_a3 = $('#first_p3').val(); 
    // // var final_a = parseInt(first_a1)+parseInt(first_a2)+parseInt(first_a3);
    // var final_a = $("#total_amount").val();
    // document.getElementById("total_amount_popup").innerHTML = '₹ ' + Math.round(final_a) + '';

    var first_a1 = $('#first_p1').val(); 
    var first_a2 = $('#first_p2').val(); 
    var first_a3 = $('#first_p3').val(); 
    var final_a = parseFloat(first_a1)+parseFloat(first_a2)+parseFloat(first_a3);
    // var check_additional_gross_amount  = $("#additional_gross_amount").val();
    final_a = parseInt(final_a)
    document.getElementById("total_amount_popup").innerHTML = '₹ ' + Math.round(final_a) + '';
    $("#total_amount_popup_pdf").val(final_a)
            $('.final_hide_default').hide()
            $('.final_hide_full').show()
            $('.final_hide_inst').hide()
          }else if(popup_data_amount == 'inst'){
            // 40_inst
            
            var first_pop_inst = $("#40_inst").val();
            var second_pop_inst = $("#40_inst_").html();
            var third_pop_inst = $("#20_inst").html();
    document.getElementById("booking_amount_popup").innerHTML = '₹ ' + Math.round(first_pop_inst) + '';
    document.getElementById("booking_f_amount_popup").innerHTML = '₹ ' + Math.round(second_pop_inst) + '';
    document.getElementById("booking_s_amount_popup").innerHTML = '₹ ' + Math.round(third_pop_inst) + '';
    // alert(first_pop_inst)
    $("#booking_amount_popup_pdf").val(Math.round(first_pop_inst));
    $("#booking_f_amount_popup_pdf").val(Math.round(second_pop_inst));
    $("#booking_s_amount_popup_pdf").val(Math.round(third_pop_inst));

            $('.final_hide_default').hide()
            $('.final_hide_full').hide()
            $('.final_hide_inst').show()
}
     
            
            var discount_value = $("#discount").val();
            var less_package_value = $("#less_package_value").val();
            if (discount_value == 0) {
                $("#distr").hide();
            }
            if (less_package_value == 0) {
                $("#grosstr").hide();
            }
            var formdata = $('form#create_package_form').serialize();
            $("#name").text($("#author_name").val());
            $("#mobiled").text($("#mobile").val());
            $("#emaild").text($("#email").val());
            $("#msstatusd").text($("#msstatus").find("option:selected").text());
            $("#pkgdrtailsd").text($("#package_details").find("option:selected").text());
            if ($("#package_details").find("option:selected").text() == "Standard Customized") {
            $("#booktyped").text($("#book_type_sc").find("option:selected").text());
            }else{
              $("#booktyped").text($("#book_type").find("option:selected").text()); 
            }
            if ($("#package_details").find("option:selected").text() == "Standard") {
                $("#packagenamed").text($(".package_ddl").find("option:selected").text());
            } else  if ($("#package_details").find("option:selected").text() == "Standard Customized") {
                $("#packagenamed").text($(".package_data_sc").val());
            }else{
                  $("#packagenamed").text($(".package_name").val());
            }
            if ($("#package_details").find("option:selected").text() == "Standard") {
                var service_subservice_html = $("#getservicewithcost").html();
                var $temp = $(service_subservice_html);
                $("input", $temp).replaceWith("");
                var myhtmlcode = $temp.html();
                var finalhtml = "<ul>" + myhtmlcode + "</ul>"
                var services = $(".servicesstand:checked").map(function() {
                    return $(this).data('name')
                }).get().join(', ');
                var serviceArray = services.split(", ");
                var servicehtml = "<ul>";
                for (i = 0; i < serviceArray.length; i++) {
                    servicehtml += "<li>" + "- " + serviceArray[i] + "</li>";
                }
                servicehtml += "</ul>";
                var subservices = $(".subservicesstand:checked").map(function() {
                    return $(this).data('name')
                }).get().join(', ');
                var sub_serviceArray = subservices.split(", ");
                var sub_servicehtml = "<ul>";
                for (i = 0; i < serviceArray.length; i++) {
                    sub_servicehtml += "<li>" + "- " + sub_serviceArray[i] + "</li>";
                }
                sub_servicehtml += "</ul>";
                $("p").removeAttr("style");
                $("#servicesd").html(finalhtml);
            } else if ($("#package_details").find("option:selected").text() == "Customized") {
                var comp = '<?php echo $leadData->lead_package_detail; ?>';
                if (comp == 2) {
                    var subservices = $(".subservices .subservice_check:checked").map(function() {
                        return $(this).data('name')
                    }).get().join(', ');
                    var servicess = $(".all-services .services:checked").map(function() {
                        return $(this).data('name')
                    }).get().join(', ');
                    var servicessArray = servicess.split(", ");
                } else {
                    var subservices = $(".sub_services .subservice_check:checked").map(function() {
                        return $(this).data('name')
                    }).get().join(', ');
                    var servicessArray = $(".all-services option:selected").text();
                }
                var sub_serviceArray = subservices.split(", ");
                var $firstval = $(".all-services .services option:selected").text();
                var htmldata = "<ul>";
                <?php if ($leadData->lead_package_detail == 1) { ?>
                    htmldata += "<li><strong>" + $firstval + "</strong></li>";
                    htmldata += "<ul>";
                    for (i = 0; i < sub_serviceArray.length; i++) {
                        htmldata += "<li>" + sub_serviceArray[i] + "</li>";
                    }
                    htmldata += "</ul>";
                <?php } else if ($leadData->lead_package_detail == 2) { ?>
                <?php } else { ?>
                    htmldata += "<li><strong>" + $firstval + "</strong></li>";
                    htmldata += "<ul>";
                    for (i = 0; i < sub_serviceArray.length; i++) {
                         var number_pages =  $('#increment').val();
                          var number_c =  $('#complimentry_copies').val();
                         if (sub_serviceArray[i] == 'Format Editing') {
                            htmldata += "<li >"+sub_serviceArray[i]+" ("+number_pages+")</li>";
                        }else if (sub_serviceArray[i] == 'Complimentary Author Copies') {
                            htmldata += "<li >"+sub_serviceArray[i]+" ("+number_c+")</li>";
                        }else{
                            htmldata += "<li class='helo'>" + sub_serviceArray[i] + "</li>";
                        }
                    }
                    htmldata += "</ul>";
                <?php } ?>
                for (i = 1; i <= 15; i++) {
                    if ($(".customize_services div").hasClass("row-add" + i)) {
                        var $secondval = $(".servicess_data_" + i + " .services option:selected").text();
                        htmldata += "<li><strong>" + $secondval + "</strong></li>";
                        var subservicess = $(".sub_services_data_" + i + " .subservice_check:checked").map(function() {
                            return $(this).data('name')
                        }).get().join(', ');
                        var subb_serviceArray = subservicess.split(", ");
                        htmldata += "<ul>";
                        for (var j = 0; j < subb_serviceArray.length; j++) {
                             var number_pages =  $('#increment').val();
                         if (subb_serviceArray[j] == 'Format Editing') {
                            htmldata += "<li class='hi'>"+subb_serviceArray[j]+" ("+number_pages+")</li>";
                        }else if (subb_serviceArray[j] == 'Proofreading') {
                            htmldata += "<li class='hi'>"+subb_serviceArray[j]+" ("+number_pages+")</li>";
                        }else if (subb_serviceArray[j] == 'Complimentary Author Copies') {
                            var number_c =  $('#complimentry_copies').val();
                            htmldata += "<li class='hi'>"+subb_serviceArray[j]+" ("+number_c+")</li>";
                        }else if (subb_serviceArray[j] == 'Additional Author Copies - Order at Subsidised Price') {
                            var number_cs =  $('#additional_author_copy_customized').val();
                            htmldata += "<li class='hi'>"+subb_serviceArray[j]+" ("+number_cs+")</li>";
                        }else if (subb_serviceArray[j] == 'Number of Pages Allowed') {
                            htmldata += "<li>"+subb_serviceArray[j]+" ("+number_pages+")</li>";
                        }else if (subb_serviceArray[j] == 'Book Size') {
                            var book_size =  $('#book_size_c :selected').text();
                            htmldata += "<li>"+subb_serviceArray[j]+" ("+book_size+")</li>";
                        }else if (subb_serviceArray[j] == 'Paper Type') {
                            var paperback =  $('#paper_type_c :selected').text();
                            htmldata += "<li>"+subb_serviceArray[j]+" ("+paperback+")</li>";
                        }else if (subb_serviceArray[j] == 'Lamination') {
                            var lamination =  $('#lamination_c :selected').text();
                            htmldata += "<li>"+subb_serviceArray[j]+" ("+lamination+")</li>";
                        }else if (subb_serviceArray[j] == 'Book Cover') {
                            var book_cover =  $('#book_cover_c :selected').text();
                            htmldata += "<li>"+subb_serviceArray[j]+" ("+book_cover+")</li>";
                        }else if (subb_serviceArray[j] == 'Color Pages') {
                            var colorPage =  $('#color_pages_customized').val();
                            htmldata += "<li>"+subb_serviceArray[j]+" ("+colorPage+")</li>";
                        }
                        else{
                            htmldata += "<li class='test'>" + subb_serviceArray[j] + "</li>";
                        }
                        }
                        htmldata += "</ul>";
                    } else {
                        console.log("False");
                    }
                }
                htmldata += "</ul>";
                $("#servicesd").html(htmldata);
            } else if ($("#package_details").find("option:selected").text() == "Standard Customized") {
                var comp = '<?php echo $leadData->lead_package_detail; ?>';
                if (comp == 3) {
                    var servicess = $(".subservicesstand_sc:checked").map(function() {
                        return $(this).data('service')
                    }).get().join(', ');
                    // alert(servicess);
                    var servicessArray = servicess.split(", ");
                    function getUnique(array){
                        var uniqueArray = [];
                        // Loop through array values
                        for(i=0; i < array.length; i++){
                            if(uniqueArray.indexOf(array[i]) === -1) {
                                uniqueArray.push(array[i]);
                            }
                        }
                        return uniqueArray;
                    }
                    var uniqueServices = getUnique(servicessArray);
                    var htmldata = "<ul>";
                    var pack_name = $('.package_data_sc').val();
                    var pack_name_db = '<?php echo $leadData->lead_package_name; ?>';
                            if (pack_name == pack_name_db) {
                                   for(var i = 0; i < uniqueServices.length; i++){
                        htmldata += "<li class='hh'><strong>"+uniqueServices[i]+"</strong>";
                        var subservicesdata = $('input[data-service="' + uniqueServices[i] + '"]:checked').map(function() {
                            return $(this).data('name')
                        }).get().join(', ');
                        var subServicessArray = subservicesdata.split(", ");
                        htmldata += "<ul>";
                        for(var k=0; k < subServicessArray.length; k++){
                          var number_page =  $('#number_of_pages_for_sc').val();
                              var number_page1 =  $('#number_of_pages_for_sc').val();

                              if (number_page != '') {
                                console.log()
                                number_page = number_page;
                              }else if (number_page1 != '') {
                                number_page = number_page1;
                              }else{}

                             if (subServicessArray[k] == 'Number of Pages Allowed') {
                            
                              // var number_page =  $('#number_of_pages_for_sc').val();
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Format Editing') {
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Proofreading') {
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Paper Type') {
                              var papertype =  $('#paper_type_sc :selected').text();
                                htmldata += "<li>"+subServicessArray[k]+" ("+papertype+")</li>";
                            }else if(subServicessArray[k] == 'Book Size'){
                                var book_size =  $('#book_size_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+book_size+")</li>";
                            }else if(subServicessArray[k] == 'Lamination'){
                                var lamination =  $('#lamination_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+lamination+")</li>";
                            }else if(subServicessArray[k] == 'Complimentary Author Copies'){
                           
                              var number_of_complimentary_copies =  $('#number_of_complimentary_copies').val();
                                var number_of_complimentary_copies1 =  $('#number_of_complimentary_copies_edit').val();
                                // alert('test311');
                                console.log(number_of_complimentary_copies1)
                                console.log(number_of_complimentary_copies)
                                var additional_author_copies_number =  $('#additional_author_copies_number').val();
                                if(additional_author_copies_number != '' && number_of_complimentary_copies != ''){
                                  // alert('test11');
                                  number_of_complimentary_copies = parseInt(number_of_complimentary_copies)+parseInt(additional_author_copies_number);
                                }else if(additional_author_copies_number != '' && number_of_complimentary_copies1 != ''){
                                  number_of_complimentary_copies = parseInt(number_of_complimentary_copies1)+parseInt(additional_author_copies_number);
                                  // alert('test112');
                                }else if (number_of_complimentary_copies != undefined) {
                                  // alert('test11234');
                                  number_of_complimentary_copies = number_of_complimentary_copies;
                                }else if(number_of_complimentary_copies1 != undefined){
                                  // alert(number_of_complimentary_copies1);
                                  number_of_complimentary_copies = number_of_complimentary_copies1;
                                }
                                // alert(number_of_complimentary_copies)
                                 htmldata += "<li>"+subServicessArray[k]+" ("+number_of_complimentary_copies+")</li>";
                            }else if(subServicessArray[k] == 'Book Cover'){
                               var book_cover_sc =  $('#book_cover_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+book_cover_sc+")</li>";
                            }else if(subServicessArray[k] == 'Color Pages'){
                               var color_pages_number =  $('#color_pages_number').val();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+color_pages_number+")</li>";
                            }else{
                                htmldata += "<li>"+subServicessArray[k]+"</li>";
                            }
                        }
                        htmldata += "</ul>";
                        htmldata += "</li>";
                    }
                }else{
                       for(var i = 0; i < uniqueServices.length; i++){
                        htmldata += "<li class='hh'><strong>"+uniqueServices[i]+"</strong>";
                        var subservicesdata = $('input[data-service="' + uniqueServices[i] + '"]:checked').map(function() {
                            return $(this).data('name')
                        }).get().join(', ');
                        var subServicessArray = subservicesdata.split(", ");
                        htmldata += "<ul>";
                        for(var k=0; k < subServicessArray.length; k++){
                             if (subServicessArray[k] == 'Number of Pages Allowed') {
                              var number_page =  $('#number_of_pages_for_sc').val();
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Format Editing') {
                              var number_page =  $('#number_of_pages_for_sc').val();
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Proofreading') {
                                 var number_page =  $('#number_of_pages_for_sc').val();
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Paper Type') {
                              var papertype =  $('#paper_type_sc :selected').text();
                                htmldata += "<li>"+subServicessArray[k]+" ("+papertype+")</li>";
                            }else if(subServicessArray[k] == 'Book Size'){
                                var book_size =  $('#book_size_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+book_size+")</li>";
                            }else if(subServicessArray[k] == 'Lamination'){
                                var lamination =  $('#lamination_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+lamination+")</li>";
                            }else if(subServicessArray[k] == 'Complimentary Author Copies'){
                                var number_of_complimentary_copies =  $('#number_of_complimentary_copies').val();
                                var number_of_complimentary_copies1 =  $('#number_of_complimentary_copies_edit').val();
                                var additional_author_copies_number =  $('#additional_author_copies_number').val();
                                if(additional_author_copies_number != '' && number_of_complimentary_copies != ''){
                                  // alert('test');
                                  number_of_complimentary_copies = parseInt(number_of_complimentary_copies)+parseInt(additional_author_copies_number);
                                }else if(additional_author_copies_number != '' && number_of_complimentary_copies1 != ''){
                                  number_of_complimentary_copies = parseInt(number_of_complimentary_copies1)+parseInt(additional_author_copies_number);
                                  // alert('test1');
                                }else if (number_of_complimentary_copies != '') {
                                  number_of_complimentary_copies = number_of_complimentary_copies;
                                  // alert('test2');
                                }else if(number_of_complimentary_copies1 != ''){
                                  number_of_complimentary_copies = number_of_complimentary_copies1;
                                  alert('test3');
                                }
                                 htmldata += "<li>"+subServicessArray[k]+" ("+number_of_complimentary_copies+")</li>";
                            }else if(subServicessArray[k] == 'Book Cover'){
                               var book_cover_sc =  $('#book_cover_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+book_cover_sc+")</li>";
                            }else if(subServicessArray[k] == 'Color Pages'){
                               var color_pages_number =  $('#color_pages_number').val();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+color_pages_number+")</li>";
                            }else{
                                htmldata += "<li>"+subServicessArray[k]+"</li>";
                            }
                        }
                        htmldata += "</ul>";
                        htmldata += "</li>";
                    }
                }
                    // return false;
                    htmldata += "</ul>";
// console.log(htmldata);
                } else {
                    var servicess = $(".subservicesstand_sc:checked").map(function() {
                        return $(this).data('service')
                    }).get().join(', ');
                    var servicessArray = servicess.split(", ");
                    function getUnique(array){
                        var uniqueArray = [];
                        // Loop through array values
                        for(i=0; i < array.length; i++){
                            if(uniqueArray.indexOf(array[i]) === -1) {
                                uniqueArray.push(array[i]);
                            }
                        }
                        return uniqueArray;
                    }
                    var uniqueServices = getUnique(servicessArray);
                    var htmldata = "<ul>";
                    for(var i = 0; i < uniqueServices.length; i++){
                       htmldata += "<li><strong>"+uniqueServices[i]+"</strong>";
                        var subservicesdata = $('input[data-service="' + uniqueServices[i] + '"]:checked').map(function() {
                            return $(this).data('name')
                        }).get().join(', ');
                        var subServicessArray = subservicesdata.split(", ");
                        // console.log(subServicessArray);
                        htmldata += "<ul>";
                        // console.log(subServicessArray.length);
                        for(var k=0; k < subServicessArray.length; k++){
                            if (subServicessArray[k] == 'Number of Pages Allowed') {
                            
                              var number_page =  $('#number_of_pages_for_sc').val();
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Format Editing') {
                              var number_page =  $('#number_of_pages_for_sc').val();
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Proofreading') {
                              var number_page =  $('#number_of_pages_for_sc').val();
                                htmldata += "<li>"+subServicessArray[k]+" ("+number_page+")</li>";
                            }else if (subServicessArray[k] == 'Paper Type') {
                              var papertype =  $('#paper_type_sc :selected').text();
                                htmldata += "<li>"+subServicessArray[k]+" ("+papertype+")</li>";
                            }else if(subServicessArray[k] == 'Book Size'){
                                var book_size =  $('#book_size_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+book_size+")</li>";
                            }else if(subServicessArray[k] == 'Lamination'){
                                var lamination =  $('#lamination_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+lamination+")</li>";
                            }else if(subServicessArray[k] == 'Complimentary Author Copies'){
                              var number_of_complimentary_copies =  $('#number_of_complimentary_copies').val();
                                var number_of_complimentary_copies1 =  $('#number_of_complimentary_copies_edit').val();
                                var additional_author_copies_number =  $('#additional_author_copies_number').val();
                                if((additional_author_copies_number != '') && (number_of_complimentary_copies != '')){
                                  // alert('test');
                                  number_of_complimentary_copies = parseInt(number_of_complimentary_copies)+parseInt(additional_author_copies_number);
                                }else if(additional_author_copies_number != '' && number_of_complimentary_copies1 != ''){
                                  number_of_complimentary_copies = parseInt(number_of_complimentary_copies1)+parseInt(additional_author_copies_number);
                                  // alert('test1');
                                }else if (number_of_complimentary_copies != '') {
                                  number_of_complimentary_copies = number_of_complimentary_copies;
                                  // alert('test2');
                                }else if(number_of_complimentary_copies1 != ''){
                                  number_of_complimentary_copies = number_of_complimentary_copies1;
                                  // alert('test3');
                                }
                                 htmldata += "<li >"+subServicessArray[k]+" ("+number_of_complimentary_copies+")</li>";
                            }else if(subServicessArray[k] == 'Book Cover'){
                               var book_cover_sc =  $('#book_cover_sc :selected').text();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+book_cover_sc+")</li>";
                            }else if(subServicessArray[k] == 'Color Pages'){
                               var color_pages_number =  $('#color_pages_number').val();
                                 htmldata += "<li>"+subServicessArray[k]+" ("+color_pages_number+")</li>";
                            }else{
                                htmldata += "<li>"+subServicessArray[k]+"</li>";
                            }
                            // console.log(subServicessArray[k]);
                        }
                        htmldata += "</ul>";
                        htmldata += "</li>";
                    }
                    // return false;
                    htmldata += "</ul>";
                }
                $("#servicesd").html(htmldata);
            }
           
            $("#package_valued").text('₹ ' + $("#package_value").val());
            $("#discountd").text($("#discount").val());
            $("#less_package_valued").text('₹ ' + $("#less_package_value").val());
            $("#gstd").text('₹ ' + $("#gst").val());
            $("#total_amountd").text('₹ ' + $("#total_amount").val());
              $("#showing_additional_cost_data").text('₹ ' + $("#cost_of_additional").val());
            $("#showing_gross_additional_cost_data").text('₹ ' + $("#additional_gross_amount").val());
            if ($("#create_p_offer").val() != '') {
              $("#offers_data").show();
              $("#offers").text($("#create_p_offer").val());
}
            $(".modal-title").text("Package Details");
            $(".mail_body").hide();
            $(".preview-package").show();
            $("#preview_data").modal('show');
          //  }
        });
       
        $( document ).ready(function() {
    // console.log( "ready!" );
            
    $('.resetbook_cover_c').change(function() {    
      $("#book_cover_c").prop("selectedIndex", 0);
      $('#book_cover_c').focus();
      // alert( $("#book_cover_c").val());
      $('.preview-btn').prop("disabled",true);

});
$('#book_cover_c').change(function() {    
      // alert( $("#book_cover_c").val());
      if($('#book_cover_c').val() != '' ){
      $('.preview-btn').prop("disabled",false);
      }
});
});
       
    </script>
    </body>
    </html>