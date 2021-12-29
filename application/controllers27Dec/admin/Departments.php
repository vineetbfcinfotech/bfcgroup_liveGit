<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Departments extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('departments_model');

        if (!has_permission('roles', '', 'view')) {
            access_denied('Departments');
        }
        
        
    }

    /* List all departments */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('departments');
        }
		
        //$data['email_exist_as_staff'] = $this->email_exist_as_staff();
		$compid = 1;
        $data['email_exist_as_staff'] = $this->departments_model->get($id="", $compid);
        $data['title']                = _l('departments');
        $this->load->view('admin/departments/manage', $data);
    }

    /* Edit or add new department */
    public function department($id = '')
    {
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);
			$data['company_id'] = $this->input->post('company_id');

            if (isset($data['fakeusernameremembered']) || isset($data['fakepasswordremembered'])) {
                unset($data['fakeusernameremembered']);
                unset($data['fakepasswordremembered']);
            }

            if (!$this->input->post('id')) {
                $id = $this->departments_model->add($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('department'));
                }
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                    'email_exist_as_staff' => $this->email_exist_as_staff(),
                ]);
            } else {
                $id = $data['id'];
                unset($data['id']); 
                $success = $this->departments_model->update($data, $id);
                if ($success) {
                    $message = _l('updated_successfully', _l('department'));
                }
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                    'email_exist_as_staff' => $this->email_exist_as_staff(),
                ]);
            }
            die;
        }
    }

    /* Delete department from database */
    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('departments'));
        }
        $response = $this->departments_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('department_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('department')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('department_lowercase')));
        }
        redirect(admin_url('departments'));
    }

    public function email_exists()
    {
        // First we need to check if the email is the same
        $departmentid = $this->input->post('departmentid');
        if ($departmentid) {
            $this->db->where('departmentid', $departmentid);
            $_current_email = $this->db->get('tbldepartments')->row();
            if ($_current_email->email == $this->input->post('email')) {
                echo json_encode(true);
                die();
            }
        }
        $exists = total_rows('tbldepartments', [
            'email' => $this->input->post('email'),
        ]);
        if ($exists > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function test_imap_connection()
    {
        $email         = $this->input->post('email');
        $password      = $this->input->post('password', false);
        $host          = $this->input->post('host');
        $imap_username = $this->input->post('username');
        if ($this->input->post('encryption')) {
            $encryption = $this->input->post('encryption');
        } else {
            $encryption = '';
        }

        require_once(APPPATH . 'third_party/php-imap/Imap.php');

        $mailbox = $host;

        if ($imap_username != '') {
            $username = $imap_username;
        } else {
            $username = $email;
        }

        $password   = $password;
        $encryption = $encryption;
        // open connection
        $imap = new Imap($mailbox, $username, $password, $encryption);
        if ($imap->isConnected() === true) {
            echo json_encode([
                'alert_type' => 'success',
                'message'    => _l('lead_email_connection_ok'),
            ]);
        } else {
            echo json_encode([
                'alert_type' => 'warning',
                'message'    => $imap->getError(),
            ]);
        }
    }

    private function email_exist_as_staff()
    {
        return total_rows('tbldepartments', 'email IN (SELECT email FROM tblstaff)') > 0;
    }
	
	public function getdepartment(){
		$comp_id = $this->input->post('comp_id');
		$this->db->where('company_id', $comp_id);
		$result = $this->db->get('tbldepartments')->result();
		$this->printDepartmentData($result);
	}
	
	private function printDepartmentData($result){ ?>
		<table class="table dt-table scroll-responsive">
						<thead><tr>
							<th>S.N</th>
							<th><?php echo _l('department_list_name')?></th>
							<th><?php echo _l('department_email')?></th>
							<!-- <th><?php //echo _l('options')?></th> -->
							<th><?php echo _l('Status')?></th>
						</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						
						foreach($result as $department){  ?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $department->name; ?></td>
								<td><?php echo $department->email; ?></td>
								<!-- <td><?php //echo $i; ?></td> -->
								<td><a href="javascript:void(0);" onclick="edit_department()" data-id="<?php echo $department->departmentid; ?>"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a> | <a href="<?php echo base_url('admin/departments/delete/').$department->departmentid; ?>"><span><i class="fa fa-trash" aria-hidden="true"></i></span></a></td>
							</tr>
						<?php 
						$i++;
						}
						?>
										
						</tbody>
					</table>
					<?php
	}
}
