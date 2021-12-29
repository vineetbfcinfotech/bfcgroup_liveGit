<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Designationtrees extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_admin()) {
            access_denied('designationtrees');
        }
        $this->load->model('roles_model');
        $this->load->model('staff_model','staffm');   
        $this->load->model('designationtree_model','designam');   
    }

    /* List all departments */
    public function index()
    {
        $data['title']                = _l('designationtrees');
        $data['roles']                = $this->designam->getRoles();
        $data['staffs']               = $this->designam->getAllStaff();
        $data['staffwithrole']               = $this->designam->getStaffWithRole();
        $this->load->view('admin/designation/trees', $data);
    }
}
