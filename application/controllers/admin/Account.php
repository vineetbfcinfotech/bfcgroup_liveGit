<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Account extends Admin_controller
{
	public function __construct()

	{

		parent::__construct();

		$this->load->model('account_model');

	}



	public function index()

	{

		$data['title'] = "Account Dashboard";

		$data['business'] = "";

		$this->load->view('admin/pc/account_data', $data);

	}

	

	public function getdata1(){

		$aquired_data = $this->account_model->index();


		$html = $this->htmlData($aquired_data);


	}
	
	public function new_project()
	{	
		$newproject_data = $this->format_editor_modal->index();

		$html = $this->projectData($newproject_data);
	}

	

	public function changePaymentStatus(){

		$payment_id = $_POST['payment_id'];
		$fromuserid = $this->session->userdata('staff_user_id');
		$payment_status = $_POST['payment_status'];
		$received_booking_amount = $_POST['data'];
		$proj_name =  $_POST['author'].'_'.$_POST['book'];
		$author_name = $_POST['author'];
		$book_name = $_POST['book'];
		$tbltype = $_POST['tbltype'];
		
		if ($payment_status == 1) {
			$data = array(
				"lead_payment_status" => $payment_status,
				"lead_booking_amount_date" => date("Y-m-d H:i:s"),
				"lead_recived_booking_amount" => $received_booking_amount,
				"pm_project_status" =>  1
			);

						$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				           $this->db->where('tblleads.id',$payment_id);
						$query_data = $this->db->get();
						$return = $query_data->row();
						
				 		$by = $this->session->userdata('staff_user_id');
						$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
				         ->from('tblstaff')
				         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
				          $this->db->where('tblstaff.staffid',$by);
						$query = $this->db->get();
						$ret = $query->row();
		            	 $data1 = array(
					        'notify_to'=> $return->pm_assign_to,
					        'user_id'=> $payment_id,
					        'take_by'=> $by,
							'role' => $ret->name,
							'project_name' => $proj_name,
							'author_name' => $author_name,
							'book_name' => $book_name,
							'action' => 15,
							'message' => 'New project alloted',
							'discription' => 'New project alloted by Accountent '.$ret->firstname.' '.$ret->lastname,
						);
				        $this->db->insert('lead_all_action',$data1);
		}else if ($payment_status == 2) {
			$data = array(
				"lead_payment_status" => $payment_status,
				"lead_first_installment_date" => date("Y-m-d H:i:s"),
				"first_installment_amount" => $received_booking_amount,
			);
						$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				           $this->db->where('tblleads.id',$payment_id);
						$query_data = $this->db->get();
						$return = $query_data->row();
						$by = $this->session->userdata('staff_user_id');

						$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
				         ->from('tblstaff')
				         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
				          $this->db->where('tblstaff.staffid',$by);
						$query = $this->db->get();
						$ret = $query->row();
		            	 $data1 = array(
		            	 	'notify_to'=> $return->pm_assign_to,
					        'user_id'=> $payment_id,
					        'take_by'=> $by,
							'role' => $ret->name,
							'project_name' => $proj_name,
							'author_name' => $author_name,
							'book_name' => $book_name,
							'action' => 16,
							'message' => 'Account First Installment successfully',
							'discription' => ''.$proj_name.' project  First Installment by Accountent '.$ret->firstname.' '.$ret->lastname,
						);
				        $this->db->insert('lead_all_action',$data1);
		}
		else if ($payment_status == 3) {
			$data = array(
				"lead_payment_status" => $payment_status,
				"lead_final_installment_date" => date("Y-m-d H:i:s"),
				"final_installment_amount" => $received_booking_amount,
			);
			$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				            $this->db->where('tblleads.id',$payment_id);
						$query_data = $this->db->get();
						$return = $query_data->row();
			$by = $this->session->userdata('staff_user_id');

						$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
				         ->from('tblstaff')
				         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
				          $this->db->where('tblstaff.staffid',$by);
						$query = $this->db->get();
						$ret = $query->row();
		            	 $data1 = array(
		            	 	'notify_to'=> $return->pm_assign_to,
					        'user_id'=> $payment_id,
					        'take_by'=> $by,
							'role' => $ret->name,
							'project_name' => $proj_name,
							'author_name' => $author_name,
							'book_name' => $book_name,
							'action' => 17,
							'message' => 'Account Final Installment successfully',
							'discription' => ''.$proj_name.' project  Final Installment by Accountent '.$ret->firstname.' '.$ret->lastname,
						);
				        $this->db->insert('lead_all_action',$data1);
		}
		

			$update = $this->account_model->changePaymentStatus($payment_id, $data,$tbltype);


	}

	public function payroyaltyamount()
	{

		$pay_royalty_amount = $_POST['data1']; 
		$totalroyalty = $_POST['totalroyalty']; 
		$name = $_POST['name']; 
		$new_str = str_replace(' ', '', $_POST['name']);

		// $this->db->select('*');
		// $this->db->from('account_payment');
		// $this->db->like('author_name',$new_str);
		// $all_da = $this->db->get()->row();

		// if($all_da){
  //       	//echo $pay_royalty_amount;
  //       	//echo $all_da->tot_pay_amt;
		// 	$total_am = $pay_royalty_amount+$all_da->tot_pay_amt;
		// 	$data = array( 
		// 		'tot_pay_amt' => $total_am,
		// 	);
		// 	$this->db->where('mrp_kdp_id', $all_da->mrp_kdp_id);
		// 	$this->db->update('account_payment', $data);
  //        //echo  $this->db->last_query();
		// }else{
			$data = array(
				'payment_date' => date('Y-m'),
				'author_name' => $new_str,
				'tot_royalty_amt' => $_POST['royalty'],
				'tot_pay_amt' => $_POST['data1'],
				'payment_status' => '1',
				'mrp_kdp_id' => $_POST['mrp_id'],
				'total_return' => $_POST['total_return_amt'],
				'total_sale' => $_POST['total_sale_amt']
			);
// print_r($data);die;

			$this->db->insert('account_payment', $data);
		// }



		$data['success'] = "success";
		set_alert('success', "Royalty paid successfully");
	}

	public function updateroyaltystatus()
	{
		$name = $_POST['name']; 
		
		$data = array(
			"royalty_status" => 1,
		);

		$update = $this->account_model->changeroyaltyStatus($name, $data);
	}



	

	

	

	function htmlData($aquired_data){
		?>

		<div class="table-responsive">
			<?php  
			// echo "<pre>";
			// print_r($aquired_data);
			?>
			<table class="table dt-table table-responsive scroll-responsive tablebusie main-table" id="" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">
				<thead>

					<tr role="row">

						<th>S.No.</th>

						<th>Author Name</th>

						<th>Author Phone</th>

						<th >Author Email</th>

						<th>GST Number</th>
							<th>State</th>

						<th>Package Name</th>

						<th>GST</th>
						<th>Discount</th>
						<th>Package Cost</th>


						<th>Pc Name</th>

						<th>Booking Receipt</th>
						<th>Booking Amount</th>
						 <th>First Payment Receipt</th>
						 <th>First Installment</th>
						  <th>Final Payment Receipt</th>

						

						

						<th>Final  Payment</th>
						<th>Remaining Balance</th>
						<th>BookingAmountStatus</th>
						<?php// if ($data->lead_payment_status >= 1) {
							// if ($data->lead_first_payment_receipt != '' || $data->lead_final_payment_receipt != '') { ?>
							<th>AuthorPaymentStatus</th>
							<th>Payment Status</th>


						<?php// }} ?>

					</tr>

				</thead>

				<tbody>

					<?php

				

					$i = 1; foreach($aquired_data as $data){ ?>

						<tr>

							<th><?php echo $i; ?></th>

							<th><?php echo $data['lead_author_name']; ?></th>

							<td><?php echo $data['phonenumber']; ?></td>

							<td style="width:40%!important"><?php echo $data['email']; ?></td>

							<td><?php echo $data['lead_gst_number']; ?></td>

	                            <td><?php echo $data['state_create_p']; ?></td>
							<td><?php echo $data['lead_packge_value']; ?></td>
							<td><?php echo $data["lead_packg_gst"]; ?></td>
							
							<td><?php echo $data['lead_packge_discount']; ?></td>

							<td><?php echo $data['lead_package_cost']; ?></td>



							<td>

								<?php

								$name = $data['assigned'];



								// if ($name == "53" | $name == "54" | $name == "38" | $name == "70") {
									$assigned_name = 	$this->db->get_where('tblstaff',array('staffid'=>$name))->row();
                   				echo $assigned_name->firstname.' '.$assigned_name->lastname;

								// }

								// else{

									//echo "<td>Online</td>";

								// }

								?>
							</td>




							<td>

								<?php if(!is_null($data['lead_payment_reciept'])){?>

									<a href="<?= base_url();?>assets/images/payment_receipt/<?= $data['lead_payment_reciept']; ?>" target="_blank">

										<img src="<?= base_url();?>assets/images/payment_receipt/<?= $data['lead_payment_reciept']; ?>" width="50" height="50" border="0" align="center"/>

									</a>

								<?php } if(!is_null($data['receipt_path'])){?>

									<a href="<?= base_url();?><?= $data['receipt_path']; ?>" target="_blank">

										<img src="<?= base_url();?><?= $data['receipt_path']; ?>" width="50" height="50" border="0" align="center"/>

									</a>



								<?php } ?>

							</td> 
							<td><?php echo round($data['lead_booking_amount']);  ?></td>
					<td>

				  <?php if($data['lead_first_payment_receipt']){?>

					<a href="<?= base_url();?>assets/images/payment_receipt_first_final/<?= $data['lead_first_payment_receipt']; ?>" target="_blank">

						  <img src="<?= base_url();?>assets/images/payment_receipt_first_final/<?= $data['lead_first_payment_receipt']; ?>" width="50" height="50" border="0" align="center"/>

					</a>
				<?php }else{ ?>
					 <img src="<?= base_url();?>assets/images/payment_receipt_first_final/no_payment.webp" width="60" height="60" border="0" align="center"/>
				<?php } ?>

				  

					</td>
					<td><?php echo round($data['lead_first_installment']); ?></td>
					 
					  
					<td>

				  <?php if($data['lead_final_payment_receipt']){?>

					<a href="<?= base_url();?>assets/images/payment_receipt_first_final/<?= $data['lead_final_payment_receipt']; ?>" target="_blank">

						  <img src="<?= base_url();?>assets/images/payment_receipt_first_final/<?= $data['lead_final_payment_receipt']; ?>" width="50" height="50" border="0" align="center"/>

					</a>
				<?php }else{ ?>
					 <img src="<?= base_url();?>assets/images/payment_receipt_first_final/no_payment.webp" width="60" height="60" border="0" align="center"/>
				<?php } ?>
				  

					</td>
					 
							
							

							<td><?php echo round($data['lead_final_payment']); ?></td>

							<td><?php  $remaimning ='';
							if ($data['lead_payment_status'] == 0) {
								$remaimning = $data["lead_package_cost"];
							}else if ($data['lead_payment_status'] == 1) {
								$remaimning = $data["lead_package_cost"] - $data['lead_recived_booking_amount'];
							}else if ($data['lead_payment_status'] == 2){
								$remaimning = $data['lead_recived_booking_amount'] + $data['first_installment_amount'];

								$remaimning = $data['lead_package_cost'] - $remaimning;

							}else if ($data['lead_payment_status'] == 3){
								$remaimning = $data['lead_package_cost'] - ($data['lead_recived_booking_amount'] + $data['first_installment_amount'] + $data['final_installment_amount']);
							} 
							echo round($remaimning);
							?></td>

							<td>

								<div class="form-group">

									<select style="width: 76%;" <?php if ($data['lead_payment_status'] >= 1) { echo " disabled "; } ?> class="form-control payment_status" name="payment_status" data-id="<?php echo $data['id']; ?>" data-author="<?php echo $data['lead_author_name']; ?>" data-book="<?php echo $data['lead_booktitle']; ?>" data-pcost="<?php echo $data['lead_package_cost']; ?>" data-tbltype="<?php echo $data['tbltype'];?>">

										<option value="0" <?php if($data['lead_payment_status'] == 0){ echo "selected"; }?> >Pending</option>

											<option value="1" <?php if($data['lead_payment_status'] >= 1){ echo "selected"; }?> class="bg-succcess">Approved</option>


										</select>
									</div>

								</td>
								<?php if ($data['lead_payment_status'] >= 1) {
								if ($data['lead_first_payment_receipt'] != '' || $data['lead_final_payment_receipt'] != '') { ?>
							


									<td>
										<div class="form-group">
											<select class="form-control payment_status" name="payment_status" data-id="<?php echo $data['id']; ?>"  data-author="<?php echo $data['lead_author_name']; ?>" data-book="<?php echo $data['lead_booktitle']; ?>" data-remaining_final="<?php echo $remaimning; ?>" data-tbltype="<?php echo $data['tbltype'];?>" >
												<?php if($data['lead_payment_status'] == 2){ ?>

													<option value="2" disabled <?php if($data['lead_payment_status'] == 2){ echo "selected"; }?> class="bg-succcess">First installment</option>
													<option value="3" <?php if($data['lead_payment_status'] == 3){ echo "selected"; }?> class="bg-succcess">Final installment</option> 
												<?php }
												else if($data['lead_payment_status'] > 2){ ?>
													<option value="3" <?php if($data['lead_payment_status'] == 3){ echo "selected"; }?> class="bg-succcess">Final installment</option> 
												<?php } else{ ?>
													<option>--select--</option>
													<option value="2"  <?php if($data['lead_payment_status'] == 2){ echo "selected"; }?> class="bg-succcess">First installment</option>
													<?php } ?>?>

												</select>
											</div>
										</td>
									<?php  }else{?>
										<td style="color:red;">
										Payment Due</td>

									<?php }  }else{?>
										<td  style="color:red;">
										Payment Due</td>

									<?php }  ?>
									<td>
										<?php if ($data['lead_payment_status'] == 3) {
											echo "Final Installment Approved ";
										}else if ($data['lead_payment_status'] == 2) {
											echo "First Installment Approved ";
										}else if ($data['lead_payment_status'] == 1) {
											echo "Booking Amount Approved";
										} else{ echo "Pending";}  ?>
										
									</td>
								</tr>

								<div id="myModalmk" class="modal fade" role="dialog">
									<div class="modal-dialog modal-sm">

										<!-- Modal content-->
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Received Booking Amount</h4>
											</div>
											<div class="modal-body ">
												<form > 
													<div class="row">
														<div class="col-md-1"></div>
														<div class="col-md-10">
															<div class="form-group">
																<span id="error_msg_data_remaining"></span><br><br>
																<label>Received Booking Amount</label><br>
																<span id="error_msg" style="background: red;"></span>
																<input type="text" id="received_booking_amount"  name="received_booking_amount" class="form-control">

																<!-- <input type="hidden" name="payment_status" class="form-control" value="1"> -->

															</div>
														</div>
														<div class="col-md-1"></div>
													</div>
													<div class="row">
														<div class="col-md-6"></div>
														<div class="col-md-6">
															<button type="submit" id="submit_booking_amount" class="btn btn-info" >Submit</button>
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

					<script src="<?php echo base_url(); ?>assets/js/main.js?v=2.1.1"></script>

					<?php

				}

				public function notification_popup(){



					$current_d = date("Y/m/d");

					$curr = date('H:i:s');

					$last_min = date('H:i:s', strtotime('-10 minutes'));



					$this->db->select();

					$this->db->from('packages_copy');

					$this->db->where('payment_status', 1);

					$this->db->where('approval_current_date', $current_d);

					$this->db->where('approval_current_time >=', $last_min);

					$this->db->where('approval_current_time <=', $curr);

					$this->db->order_by('approval_current_time', 'asc');

					$result = $this->db->get();

					$data_array = $result->result();

					print_r($data_array);die;

					foreach($data_array as $value){

						echo "Hello Dear ".$value->author_name." Your Payment is Succesfully";

						echo '<br>';

					}



				}

				public function calculation($value='')
				{
					$this->db->select('*');
					$this->db->from('mrpfixation');
					$this->db->join('kdp_sale_report', 'mrpfixation.author_name = kdp_sale_report.AuthorName');
					$this->db->group_by('kdp_sale_report.BookTitle');
					$query = $this->db->get();
					$all_result = $query->result();

// 		$html = $this->htmlDataOf_royalty($all_result);

					foreach ($all_result as $key => $value) {
						echo 'Hello '.$value->author_name.'Your  Amazon '.($value->amazon*$value->UnitsSold);echo '<br>';
						echo 'Hello '.$value->author_name.'Your  kdp '.($value->kdp*$value->UnitsSold);echo '<br>';
					}
				}
				public function payroyalty()

				{

					$data['title'] = "Account Dashboard";

					$data['business'] = "";

					$this->load->view('admin/pc/pay_royalty', $data);

				}
				public function getdata_royalty(){

				//exit;
				//        echo "<pre>";print_r($royalty);exit;

				// //$aquired_data = $this->account_model->index();
				// $this->db->select('*');
				// $this->db->select('SUM(UnitsSold) AS soldunit');
				// $this->db->join('kdp_sale_report', 'mrpfixation.author_name = kdp_sale_report.AuthorName');
				// $this->db->group_by('mrpfixation.author_id_name');
				// $query = $this->db->get('mrpfixation');
				//       //echo $this->db->last_query();exit;
				//       $all_resut_royalty = $query->result();
				//       //echo "<pre>";print_r($all_resut_royalty);exit;
				// $html = $this->htmlDataOf_royalty($all_resut_royalty);

				// 		$query3 = $this->db->query("SELECT * FROM account_payment");
				// 	 $result3 = $query3->result();
				// 	 return $result3;



					$royalty=	$this->db->select('*')->get('mrpfixation')->result();

					if(count($royalty)!=0){

						$amazonFlipkart =0;
						$kdpGooglePlay =0;
						$i=0;
						?>
						<div class="table-responsive">

							<table class="table dt-table table-responsive scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">

								<thead>

									<tr role="row">

										<th>S.No.</th>

										<th>Author Name</th>

										<th>Phone</th>

										<th>Email</th>

										<th>Unit Sold</th>

										<th>Total Royalty</th>
										<th>Paid Royalty</th>
										<th>Remaining Royalty</th>

										<th>Status</th>

									</tr>

								</thead>

								<tbody>
									<?php
									$j = 1;

									foreach($royalty as $royaltysalData){

										$amazonFlipkart=$royaltysalData->amazon; 
										$kdpGooglePlay=$royaltysalData->kdp; 
 										$authorId =   str_replace(' ','',$royaltysalData->author_name);

										$return_UnitsSold_g= $this->commonBookCount('GooglePlay',$authorId, 'return');
										$return_UnitsSold_a= $this->commonBookCount('Amazon',$authorId, 'return');
										$return_UnitsSold_f= $this->commonBookCount('Flipkart',$authorId, 'return');
										$return_UnitsSold_k= $this->commonBookCount('KDP',$authorId, 'return');

										$total_returns = $return_UnitsSold_g[0]->soldunit+$return_UnitsSold_a[0]->soldunit+$return_UnitsSold_f[0]->soldunit+$return_UnitsSold_k[0]->soldunit;

										$total_sale_return_k_g = $return_UnitsSold_g[0]->soldunit+$return_UnitsSold_k[0]->soldunit;
										$total_sale_return_a_f = $return_UnitsSold_a[0]->soldunit+$return_UnitsSold_f[0]->soldunit;
										$total_sale_returnkg  = $total_sale_return_k_g*$kdpGooglePlay;
										$total_sale_returnaf  = $total_sale_return_a_f*$amazonFlipkart;
										$total_retun_amount = $total_sale_returnkg+$total_sale_returnaf;


							//*******************for retun with month *********************//
										$return_UnitsSold_g_month= $this->commonBookCount('GooglePlay',$authorId, 'curent_month_return');
										$return_UnitsSold_a_month= $this->commonBookCount('Amazon',$authorId, 'curent_month_return');
										$return_UnitsSold_f_month= $this->commonBookCount('Flipkart',$authorId, 'curent_month_return');
										$return_UnitsSold_k_month= $this->commonBookCount('KDP',$authorId, 'curent_month_return');

										$total_returns_month = $return_UnitsSold_g_month[0]->soldunit+$return_UnitsSold_a_month[0]->soldunit+$return_UnitsSold_f_month[0]->soldunit+$return_UnitsSold_k_month[0]->soldunit;

										$total_sale_return_k_g_month = $return_UnitsSold_g_month[0]->soldunit+$return_UnitsSold_k_month[0]->soldunit;
										$total_sale_return_a_f_month = $return_UnitsSold_a_month[0]->soldunit+$return_UnitsSold_f_month[0]->soldunit;
										$total_sale_returnkg_month  = $total_sale_return_k_g_month*$kdpGooglePlay;
										$total_sale_returnaf_month  = $total_sale_return_a_f_month*$amazonFlipkart;
										$total_retun_amount_month = $total_sale_returnkg_month+$total_sale_returnaf_month;

							//*******************for retun with month end here*********************//
							//*******************for sold with month start here*********************//
										$googlePlaycount_month= $this->commonBookCount('GooglePlay',$authorId, 'current_sold_month');
										$amazoncount_month= $this->commonBookCount('Amazon',$authorId, 'current_sold_month');
										$flipKartcount_month= $this->commonBookCount('Flipkart',$authorId, 'current_sold_month');
										$kdpcount_month= $this->commonBookCount('KDP',$authorId, 'current_sold_month');

										$total_sold_month = $googlePlaycount_month[0]->soldunit+$amazoncount_month[0]->soldunit+$flipKartcount_month[0]->soldunit+$kdpcount_month[0]->soldunit;
										$total_unit_sold =$total_sold_month+$total_returns_month;

										$total_sale_k_g_month = $googlePlaycount_month[0]->soldunit+$kdpcount_month[0]->soldunit;
										$total_sale_a_f_month = $amazoncount_month[0]->soldunit+$flipKartcount_month[0]->soldunit;

										$total_sale_kg_month  = $total_sale_k_g_month*$kdpGooglePlay;
										$total_sale_af_month  = $total_sale_a_f_month*$amazonFlipkart;
										$total_sale_amount_month = $total_sale_kg_month+$total_sale_af_month;

										$tzam_month=$amazoncount_month[0]->soldunit-($return_UnitsSold_a_month[0]->soldunit)*-1; 
										$tzgp_month=$googlePlaycount_month[0]->soldunit-($return_UnitsSold_g_month[0]->soldunit)*-1;

										$tzkd_month = $kdpcount_month[0]->soldunit-($return_UnitsSold_k_month[0]->soldunit)*-1; 
										$tzfl_month = $flipKartcount_month[0]->soldunit-($return_UnitsSold_f_month[0]->soldunit)*-1; 



										$addam_month=$tzam_month+$tzfl_month; 

										$mulam_month=$addam_month*$amazonFlipkart; 

										$addgp_month=$tzgp_month+$tzkd_month; 
										$mulgp_month=$addgp_month*$kdpGooglePlay;

										$totamgp_month=$mulam_month+$mulgp_month;
										$totamgp_month=number_format((float)$totamgp_month, 2, '.', '');


							//*******************for sold with month end here*********************//			

										$googlePlaycount= $this->commonBookCount('GooglePlay',$authorId, 'sold');
										$amazoncount= $this->commonBookCount('Amazon',$authorId, 'sold');
										$flipKartcount= $this->commonBookCount('Flipkart',$authorId, 'sold');
										$kdpcount= $this->commonBookCount('KDP',$authorId, 'sold');

										$total_sold = $googlePlaycount[0]->soldunit+$amazoncount[0]->soldunit+$flipKartcount[0]->soldunit+$kdpcount[0]->soldunit;
										$total_unit_sold =$total_sold+$total_returns;

										$total_sale_k_g = $googlePlaycount[0]->soldunit+$kdpcount[0]->soldunit;
										$total_sale_a_f = $amazoncount[0]->soldunit+$flipKartcount[0]->soldunit;

										$total_sale_kg  = $total_sale_k_g*$kdpGooglePlay;
										$total_sale_af  = $total_sale_a_f*$amazonFlipkart;
										$total_sale_amount = $total_sale_kg+$total_sale_af;

										$tzam=$amazoncount[0]->soldunit-($return_UnitsSold_a[0]->soldunit)*-1; 
										$tzgp=$googlePlaycount[0]->soldunit-($return_UnitsSold_g[0]->soldunit)*-1;

										$tzkd = $kdpcount[0]->soldunit-($return_UnitsSold_k[0]->soldunit)*-1; 
										$tzfl = $flipKartcount[0]->soldunit-($return_UnitsSold_f[0]->soldunit)*-1; 



										$addam=$tzam+$tzfl; 

										$mulam=$addam*$amazonFlipkart; 

										$addgp=$tzgp+$tzkd; 
										$mulgp=$addgp*$kdpGooglePlay;

										$totamgp=$mulam+$mulgp;
										$totamgp=number_format((float)$totamgp, 2, '.', '');
       //echo $royaltysalData->author_id;
       // echo $totamgp."<br>";




										?>



										<?php

										++$i; 

										?>

										<tr>

											<td><?php echo $i; ?></td>

											<td><?php echo $royaltysalData->author_name; ?></td>


											<td><?php echo $royaltysalData->mobile; ?></td>
											<td><?php echo $royaltysalData->email; ?></td>
											<td><?php echo $total_unit_sold; ?></td>
											<td><?php echo $totamgp; ?></td>


											<?php


											$new_str = str_replace(' ', '', $royaltysalData->author_name);
											$this->db->select('*');
											$this->db->from('account_payment');
											$this->db->like('author_name',$new_str);
											$all_da = $this->db->get()->result();

											?><td><?php $tot_pay_data = 0;
												foreach ($all_da as $key => $value) {
													$tot_pay_data += $value->tot_pay_amt;
												}  echo $tot_pay_data; ?></td>

											<td><?php if (empty($all_da)) {
												echo $totamgp;
											}else{ echo  number_format(($all_da[0]->tot_royalty_amt - $tot_pay_data), 2, '.', ''); }?></td>



											<td>
												<?php $all_data_r = $this->db->get_where('account_payment',array('mrp_kdp_id'=>$royaltysalData->author_id))->result();
												$tot_pay_amt_data = 0;
												foreach ($all_data_r as $key => $value) {
													$tot_pay_amt_data += $value->tot_pay_amt;
												}

				   	// print_r($tot_pay_amt_data);
												$remaimning_data =  $all_data_r[0]->tot_royalty_amt-$tot_pay_amt_data;
												if ($all_data_r) { 
													if ($remaimning_data >= 500) {?> 


														<div class="form-group">
															<a href="#myModalss" class="btn btn-primary ssbtn" data-id="<?php echo $royaltysalData->author_id; ?>" data-amt="<?php echo $remaimning_data; ?>" data-name="<?php echo $royaltysalData->author_name; ?>" data-total_return_amt="<?php echo $total_retun_amount_month; ?>" data-total_sale_amt="<?php echo $total_sale_amount_month; ?>" >Pay Royalty</a>
														</div>
													<?php  }else{ ?>
														<div class="form-group">
															<a class="btn btn-primary " data-id="<?php echo $royaltysalData->author_id; ?>" data-amt="<?php echo $remaimning_data; ?>" data-name="<?php echo $royaltysalData->author_name; ?>" >Pay Royalty</a>
														</div>
													<?php }
												}else{
													if ($totamgp >= 500) { ?>
														<div class="form-group">
															<a href="#myModalss" class="btn btn-primary ssbtn" data-id="<?php echo $royaltysalData->author_id; ?>" data-amt="<?php echo $totamgp; ?>" data-name="<?php echo $royaltysalData->author_name; ?>" data-total_return_amt="<?php echo $total_retun_amount_month; ?>" data-total_sale_amt="<?php echo $total_sale_amount_month; ?>" >Pay Royalty</a>
														</div>
													<?php }else{ ?>
														<div class="form-group">
															<a class="btn btn-primary " data-id="<?php echo $royaltysalData->author_id; ?>" data-amt="<?php echo $totamgp; ?>" data-name="<?php echo $royaltysalData->author_name; ?>" >Pay Royalty</a>
														</div>
													<?php	}  	 ?>



												<?php 	} ?>



											</td>

										</tr>


										<div id="myModalss" class="modal fade" role="dialog">
											<div class="modal-dialog modal-sm">

												<!-- Modal content-->
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">Pay Royalty Amount</h4>
													</div>
													<div class="modal-body ">
														<form> 
															<div class="">
																<span id="error_msg_data"></span><br><br>
																<label><b>Total Royalty :</b></label>
																<input type="text" id="totalroyalty" name="totalroyalty"  value="<?php echo $totamgp; ?>" disabled class="form-control"/>
															</div>
															<br/>
															<div class="">
																<label><b>Pay Royalty Amount</b></label>
																<input type="text" id="pay_royalty_amount"  name="pay_royalty_amount" class="form-control">

																<input type="hidden" id="authorname" name="authorname" value="<?php echo $royaltysalData->author_id_name; ?>"/>

															</div>


															<div class="row mt-3">

																<div class="col-md-12 ">
																	<div class="pull-right">
																		<button type="submit" id="submit_royalty_amount" class="btn btn-info" >Submit</button>
																	</div>
																</div>
															</div>


														</form>
													</div>

												</div>

											</div>
										</div>






										<?php
									$j++; }
									?>
								</tbody>

							</table>

						</div>

						<script src="<?php echo base_url(); ?>assets/js/main.js?v=2.1.1"></script><?php  
					}else{
						$amazonFlipkart =0; 
						$kdpGooglePlay =0;
					}



				}
				public function commonBookCount($Marketplace='',$authorname='',$which='')
				{
					if ($which== 'return') {
						if ($Marketplace == 'GooglePlay' ) {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold'=>-1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'Amazon') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold'=>-1));
							$query = $this->db->get('kdp_sale_report');

							$data = $query->result();
						}
						if ($Marketplace == 'Flipkart') {


							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold'=>-1));
							$query = $this->db->get('kdp_sale_report');
							// echo $this->db->last_query();
							$data = $query->result();

						}
						if ($Marketplace == 'KDP') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold'=>-1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
					}
					if ($which== 'sold') {
						if ($Marketplace == 'GooglePlay' ) {

							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold >='=>1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'Amazon') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold >='=>1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'Flipkart') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold >='=>1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'KDP') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold >='=>1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
					}
					if ($which== 'current_sold_month') {
						$date_from = date('Y-m');
						$date_from_start = $date_from.'-01';
						$date_from_end = $date_from.'-31';
						if ($Marketplace == 'GooglePlay' ) {

							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('OrderDateDt >='=>$date_from_start,'OrderDateDt <='=>$date_from_end,'authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold >='=>1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'Amazon') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('OrderDateDt >='=>$date_from_start,'OrderDateDt <='=>$date_from_end,'authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold >='=>1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'Flipkart') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('OrderDateDt >='=>$date_from_start,'OrderDateDt <='=>$date_from_end,'authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold >='=>1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'KDP') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('OrderDateDt >='=>$date_from_start,'OrderDateDt <='=>$date_from_end,'authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold >='=>1));
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
					}


					if($which == 'curent_month_return'){
						$date_from = date('Y-m');
						$date_from_start = $date_from.'-01';
						$date_from_end = $date_from.'-31';
						if ($Marketplace == 'GooglePlay' ) {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('OrderDateDt >='=>$date_from_start,'OrderDateDt <='=>$date_from_end,'authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold'=>-1));
						
							// $this->db->where('OrderDateDt BETWEEN '.$date_from_start.' AND '.$date_from_end);
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'Amazon') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('OrderDateDt >='=>$date_from_start,'OrderDateDt <='=>$date_from_end,'authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold'=>-1));
							// $this->db->where('OrderDateDt BETWEEN '.$date_from_start.' AND '.$date_from_end);
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
						if ($Marketplace == 'Flipkart') {


							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('OrderDateDt >='=>$date_from_start,'OrderDateDt <='=>$date_from_end,'authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold'=>-1));
							// $this->db->where('OrderDateDt BETWEEN '.$date_from_start.' AND '.$date_from_end);
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();

						}
						if ($Marketplace == 'KDP') {
							$this->db->select('SUM(UnitsSold) AS soldunit');
							$this->db->where(array('OrderDateDt >='=>$date_from_start,'OrderDateDt <='=>$date_from_end,'authorId'=>$authorname , 'Marketplace'=>$Marketplace , 'UnitsSold'=>-1));
							// $this->db->where('OrderDateDt BETWEEN '.$date_from_start.' AND '.$date_from_end);
							$query = $this->db->get('kdp_sale_report');
							$data = $query->result();
						}
					}


					return $data;


				}

				function htmlDataOf_royalty($all_resut_royalty){

	   // echo "<pre>";

	   // print_r($all_resut_royalty);

	   // exit();

					?>

					<div class="table-responsive">

						<table class="table dt-table table-responsive scroll-responsive tablebusie" id="pending_approval_table" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 100%;">

							<thead>

								<tr role="row">

									<th>S.No.</th>

									<th>Author Name</th>

									<th>Phone</th>

									<th>Email</th>

									<th>Unit Sold</th>

									<th>Total Royalty</th>
									<th>Paid Royalty</th>
									<th>Remaining Royalty</th>

									<th>Status</th>

								</tr>

							</thead>

							<tbody>

								<?php

								$i = 1; 

								?>

								<tr>

									<td><?php echo $i; ?></td>

									<td><?php echo $all_resut_royalty['authorname']; ?></td>


									<td><?php echo $all_resut_royalty['phonenumber']; ?></td>
									<td><?php echo $all_resut_royalty['email']; ?></td>
									<td><?php echo $all_resut_royalty['Unit_Sold']; ?></td>
									<td><?php echo $all_resut_royalty['Total_Royalty']; ?></td>


									<?php


									$new_str = str_replace(' ', '', $data1->authorname);
									$this->db->select('*');
									$this->db->from('account_payment');
									$this->db->like('author_name',$new_str);
									$all_da = $this->db->get()->row();

									?><td> <?php  echo $all_da->tot_pay_amt; ?></td>

									<td><?php echo  number_format(($all_da->tot_royalty_amt - $all_da->tot_pay_amt), 2, '.', ''); ?></td>



									<td>
										<?php $all_data_r = $this->db->get_where('account_payment',array('mrp_kdp_id'=>$data1->author_id))->row();
				   	//print_r($all_data_r);
										$remaimning_data =  $all_data_r->tot_royalty_amt-$all_data_r->tot_pay_amt;
										if ($all_data_r) { ?>

											<div class="form-group">
												<a href="#myModalss" class="btn btn-primary ssbtn" data-id="<?php echo $data1->author_id; ?>" data-amt="<?php echo $remaimning_data; ?>" data-name="<?php echo $data1->AuthorName; ?>" >Pay Royalty</a>
											</div>
										<?php  }else{  	 ?>

											<div class="form-group">
												<a href="#myModalss" class="btn btn-primary ssbtn" data-id="<?php echo $data1->author_id; ?>" data-amt="<?php echo $total_royalty; ?>" data-name="<?php echo $data1->AuthorName; ?>" >Pay Royalty</a>
											</div>

										<?php 	} ?>



									</td>

								</tr>


								<div id="myModalss" class="modal fade" role="dialog">
									<div class="modal-dialog modal-sm">

										<!-- Modal content-->
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Pay Royalty Amount</h4>
											</div>
											<div class="modal-body ">
												<form> 
													<div class="">
														<label><b>Total Royalty :</b></label>
														<input type="text" id="totalroyalty" name="totalroyalty"  value="<?php echo $total_royalty; ?>" disabled class="form-control"/>
													</div>
													<br/>
													<div class="">
														<label><b>Pay Royalty Amount</b></label>
														<input type="text" id="pay_royalty_amount"  name="pay_royalty_amount" class="form-control">

														<input type="hidden" id="authorname" name="authorname" value="<?php echo $data1->author_id_name; ?>"/>
													</div>


													<div class="row mt-3">

														<div class="col-md-12 ">
															<div class="pull-right">
																<button type="submit" id="submit_royalty_amount" class="btn btn-info" >Pay</button>
															</div>
														</div>
													</div>


												</form>
											</div>

										</div>

									</div>
								</div>




							</tbody>

						</table>

					</div>

					<script src="<?php echo base_url(); ?>assets/js/main.js?v=2.1.1"></script>

					<?php

				}
	public function notification_to_pm($value='')
	{
		$payment_id = $_POST['payment_id'];
	$proj_name =  $_POST['author'].'_'.$_POST['book'];
		$author_name = $_POST['author'];
		$book_name = $_POST['book'];
		$this->db->select('tblstaff.pm_assign_to')
				         ->from('tblleads')
				         ->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');
				            $this->db->where('tblleads.id',$payment_id);
						$query_data = $this->db->get();
						$return = $query_data->row();
			$by = $this->session->userdata('staff_user_id');

						$this->db->select('tblstaff.staffid, tblstaff.role,tblstaff.firstname,tblstaff.lastname, tblroles.*')
				         ->from('tblstaff')
				         ->join('tblroles', 'tblstaff.role = tblroles.roleid');
				          $this->db->where('tblstaff.staffid',$by);
						$query = $this->db->get();
						$ret = $query->row();
		            	 $data1 = array(
		            	 	'notify_to'=> $return->pm_assign_to,
					        'user_id'=> $payment_id,
					        'take_by'=> $by,
							'role' => $ret->name,
							'project_name' => $proj_name,
							'author_name' => $author_name,
							'book_name' => $book_name,
							'action' => 17,
							'message' => 'Paid Amount Should be equal to Remaining Amount',
							'discription' => ''.$proj_name.' project Paid Amount Should be equal to Remaining Amount '.$ret->firstname.' '.$ret->lastname,
						);
				        $this->db->insert('lead_all_action',$data1);
	}
	public function cash_free(){
	    	$this->db2 = $this->load->database('secend_db', TRUE);
    // 		$data['all_data'] = $this->db2->get_where('payment',array('pay_from'=>1))->result();
    
							$this->db2->where('pay_from',1);
							$this->db2->order_by('id','DESC');
							$query = $this->db2->get('payment');
							$data['all_data'] = $query->result();
    // 		print_r($data);die;
    		
	    $this->load->view('admin/pc/cash_free', $data);
	}

}

