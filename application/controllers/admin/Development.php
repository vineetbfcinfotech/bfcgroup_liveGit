<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Development extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('development_model','development'); 
		$this->prodigy = $this->load->database(anotherdb, true);
    }
 
    /* This is admin dashboard view */
    public function basket() 
    {
		$data['transation_type'] = $this->development->getTransationType();
		$data['anchoring'] = $this->development->getAnchoring();
		$data['constellations'] = $this->development->getConstellation();
		$data['options'] = $this->development->getOptions();
		//echo "<pre>";
		//print_r($data['transation_type']);exit;
		$data['title'] = "Research & Development";
		$this->load->view('admin/development/basket',$data);
    }

	public function upload()
    {
		$data['title'] = "Research & Development";
		$this->load->view('admin/development/upload',$data);
    }
	
	public function getImportedData(){
		$data = $this->development->get_uploaded_scheme_data();
		$html = $this->productHtml($data);
		print_r($html);
	}
	
	public function getImportedSchemeData(){
		$data = $this->development->get_uploaded_scheme_data();
		$html = $this->productSchemeHtml($data);
		print_r($html);
	}
	
	public function import_csv(){
        if ($_FILES) {
            $file = $_FILES['sachems']['tmp_name'];
			$handle = fopen($file, "r");
			$c = 0;//
			
			while ($row = fgetcsv($handle, 1000, ",")) {
				$rows[] = $row;
			}
			array_shift($rows);
			$delete = $this->prodigy->truncate('advisory');
			foreach($rows as $rowdata){
				$data = array(
					"anchoring" => $rowdata[0],
					"constellation" => $rowdata[1],
					"option" => $rowdata[2],
					"transaction_type" => $rowdata[3],
					"asset_class" => $rowdata[4],
					"scheme_name" => $rowdata[5],
					//"product_code" => $rowdata[6],
					"isin_no" => $rowdata[6],
				);
				//$this->development->getData($rowdata[0]);
				$this->development->import($data);
				/* if(empty($result)){
					$this->development->import($data);
				}else{
					$this->development->importUpdate($rowdata[0],$data);
				} */
			}
			
		}else{
			set_alert('warning', _l('import_upload_failed'));
		}
	}
	
	public function create_basket(){
		//print_r($_POST);exit;
		$data = array(
			"transaction_type" => $_POST["transaction_type"],
			"anchoring" => $_POST["anchoring"],
			"option" => $_POST["option"],
			"constellation" => $_POST["constellation"],
			"asset_type" => implode(", ", $_POST["asset_type"]),
			"schemes" => implode(", ", $_POST["schemes"]),
			"product_code" => implode(", ", $_POST["product_code"]),
			"isin_no" => implode(", ", $_POST["isin_no"])
		);
		if($_POST['basket_id'] == ""){
			$result = $this->development->createBasket($data);
			$dataresult["msg"] = "Basket Created Successfully!";
			$dataresult["val"] = "1";
		}else{
			$result = $this->development->updateBasket($_POST['basket_id'], $data);
			$dataresult["msg"] = "Basket Updated Successfully!";
			$dataresult["val"] = "2";
		}
		if($result){
			echo json_encode($dataresult);
		}else{
			$dataresult["val"] = "0";
			$dataresult["msg"] = "Basket Not Created!";
			echo json_encode($dataresult);
		}

	}
	
	public function getEditData(){
		$dataid = $_POST["dataid"];
		$data = $this->development->getEditData($dataid);
		echo json_encode($data);
	}
	
	public function getBasketData(){
		$data = $this->development->getBasketData();
		$html = $this->basketHtml($data);
	}
	
	public function deleteBasket(){
		$dataid = $_POST['dataid'];
		$delete = $this->development->deleteBasket($dataid);
		if($delete){
			$data = $this->development->getBasketData();
			$html = $this->basketHtml($data);
			echo $html;
		}
	}
	
	public function getAssetType(){
		$transaction_type = $_POST['transaction_type'];
		$anchoring = $_POST['anchoring'];
		$option = $_POST['option'];
		$constellation = $_POST['constellation'];
		
		$filterAll = $this->development->filterDataForBasket($transaction_type, $anchoring, $option, $constellation);
		
		$assets = $this->development->getAssetType($transaction_type, $anchoring, $option, $constellation);
		if(!empty($filterAll)){
			$data["html"] = "1";
			$data["msg"] = "Sorry, this basket already created!.";
		}else{
			if(!empty($assets)){
				$html = "<option value=''>--Select--</option>";
				foreach($assets as $asset){
					$html .= "<option value='".$asset->asset_class."'>".$asset->asset_class."</option>";
				}
				$data["html"] = $html;
				$data["msg"] = "";
			}else{
				$data["html"] = "1";
				$data["msg"] = "Sorry, Data not found!.";
			}
		}
		echo json_encode($data);
	}
	
	public function getSchemeName(){
		$transaction_type = $_POST['transaction_type'];
		$anchoring = $_POST['anchoring'];
		$option = $_POST['option'];
		$constellation = $_POST['constellation'];
		$asset_type = $_POST['asset_type'];
		$schemes = $this->development->getSchemeName($transaction_type, $anchoring, $option, $constellation, $asset_type);
		$html = "<option value=''>--Select--</option>";
		foreach($schemes as $scheme){
			$html .= "<option value='".$scheme->scheme_name."' data-code='".$scheme->product_code."' data-isin='".$scheme->isin_no."'>".$scheme->scheme_name."</option>";
		}
		echo $html;
	}
	
	public function scheme_performance(){
		$this->load->view('admin/development/schemedata',$data);
	}
	
	public function import_scheme_data(){
		if ($_FILES) {
            $file = $_FILES['sachems']['tmp_name'];
			$handle = fopen($file, "r");
			$c = 0;//
			
			while ($row = fgetcsv($handle, 1000, ",")) {
				$rows[] = $row;
			}
			array_shift($rows);
			$delete = $this->prodigy->truncate('scheme_performance');
			foreach($rows as $rowdata){
				$data = array(
					"scheme_name" => $rowdata[0],
					"category" => $rowdata[1],
					"isin" => $rowdata[2],
				);
				$this->development->import_scheme($data);
				
			}
			
		}else{
			set_alert('warning', _l('import_upload_failed'));
		}
	}
	
	public function scheme_list(){
		$data = $this->development->scheme_list();
		echo json_encode($data);
	}
	
	public function notify(){
		$data['title'] = "Uploade Notification";
		$this->load->view('admin/development/notify',$data);
	}
	
	public function import_notify(){
		$title = $_POST['title'];
		$description = $_POST['description'];
		
		$data = array(
				"title" => $title,
				"description" => $description
				);
		 
		$this->development->import_notify($data);
	}
	
	public function getImportNotify(){
		$data = $this->development->get_import_notify();
		$html = $this->notifyHtml($data);
		print_r($html);
	}
	
	function productHtml($data){ ?>
		<table class="table dt-table scroll-responsive dt-no-serverside dataTable no-footer">
		   <thead>
		   <tr>
			   <th><?php echo _l('id'); ?></th>
			   <th class="bold">Anchoring</th>
			   <th class="bold">Constellation</th>
			   <th class="bold">Transaction Type</th>
			   <th class="bold">Option</th>
			   <th class="bold">Asset Class</th>
			   <th class="bold">Scheme Name</th>
		   </tr>
		   </thead>
		   <tbody class="">
			   <?php  foreach ($data as $report) { ?>
			   <tr>
				   <td><?= @++$i; ?></td>
				   <td><?= $report->anchoring; ?></td>
				   <td><?= $report->constellation; ?></td>
				   <td><?= $report->transaction_type; ?></td>
				   <td><?= $report->option; ?></td>
				   <td><?= $report->asset_class; ?></td>
				   <td><?= $report->scheme_name; ?></td>
				</tr>
			   
		   <?php } ?>
			<script src="<?php echo base_url('assets/js/main.js?v=2.1.1'); ?>"></script>
		   </tbody>
	</table>
	<?php 
	}
	
	function productSchemeHtml($data){ ?>
		<table class="table dt-table scroll-responsive dt-no-serverside dataTable no-footer">
		   <thead>
		   <tr>
			   <th><?php echo _l('id'); ?></th>
			   <th class="bold">Category</th>
			   <th class="bold">ISIN</th>
			   <th class="bold">Scheme Name</th>
		   </tr>
		   </thead>
		   <tbody class="">
			   <?php  foreach ($data as $report) { ?>
			   <tr>
				   <td><?= @++$i; ?></td>
				   <td><?= $report->category; ?></td>
				   <td><?= $report->isin; ?></td>
				   <td><?= $report->scheme_name; ?></td>
				</tr>
			   
		   <?php } ?>
			<script src="<?php echo base_url('assets/js/main.js?v=2.1.1'); ?>"></script>
		   </tbody>
	</table>
	<?php 
	}
	
	function notifyHtml($data){ ?>
		<table class="table dt-table scroll-responsive dt-no-serverside dataTable no-footer">
		   <thead>
		   <tr>
			   <th><?php echo _l('id'); ?></th>
			   <th class="bold">Title</th>
			   <th class="bold">Description</th>
			  
		   </tr>
		   </thead>
		   <tbody class="">
			   <?php  foreach ($data as $report) { ?>
			   <tr>
				   <td><?= @++$i; ?></td>
				   <td><?= $report->title; ?></td>
				   <td><?= $report->description; ?></td>
				 
				</tr>
			   
		   <?php } ?>
			<script src="<?php echo base_url('assets/js/main.js?v=2.1.1'); ?>"></script>
		   </tbody>
	</table>
	<?php 
	}
	
	function basketHtml($data){ ?>
		<table class="table dt-table scroll-responsive dt-no-serverside dataTable no-footer">
		   <thead>
		   <tr>
			   <th><?php echo _l('id'); ?></th>
			   <th class="bold">Basket Name</th>
			   <th class="bold">Action</th>
		   </tr>
		   </thead>
		   <tbody class="">
			   <?php  foreach ($data as $report) { ?>
			   <tr>
				   <td><?= @++$i; ?></td>
				   <td><?= $report->transaction_type."-".$report->constellation."-".$report->anchoring."-".$report->option; ?></td>
				   <td><a href="javascript:void(0)" data-id="<?= $report->id; ?>" class="edit">Edit</a> | <a href="javascript:void(0)" data-id="<?= $report->id; ?>" class="delete">Delete</a></td>
				</tr>
			   
		   <?php } ?>
			<script src="<?php echo base_url('assets/js/main.js?v=2.1.1'); ?>"></script>
		   </tbody>
	</table>
	<?php 
	}
	
	
}
