<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
           
						
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                         <?
                        $loginid = $this->session->userdata('staff_user_id');
                        $this->db->where('staffid', $loginid);

                        $this->db->select('CONCAT(tbll.firstname, " ", tbll.lastname) AS fullname,tbll.staffid ');
                        $data = $this->db->get('tblstaff as tbll')->result();
                        //print_r($data);
                        ?>
                      
                        <div class="row">
                            <table class="table dt-table table-bordered scroll-responsive">
							
                                <thead>
									<tr>
										<td class="bold text-center">Sl No</td>
										<td class="bold text-center">Phone Number</td>
										<td class="bold text-center">Total Count</td>
										<td class="bold text-center">#</td>
									</tr>
                                </thead>
                                
                                <tbody id="tblResult">
                                   <?php
								   $a=1;
								   foreach($getData as $rt)
								   {
								    ?>
									 <tr>
									   <td class="text-center"><?php echo $a; ?> </td>
									   <td class="text-center"><?php echo $rt['phonenumber']; ?></td>
									   <td class="text-center" ><?php echo $rt['cnt']; ?> </td>
										  <td class="text-center"><a class="btn btn-small btn-primary" href="<?php echo base_url() ?>admin/leads/view_duplicate_list/<?php echo $rt['phonenumber']; ?> ">View</a></td>
									</tr>
									<?php
									  $a++;
								   }
								   ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>


</body>
</html>
