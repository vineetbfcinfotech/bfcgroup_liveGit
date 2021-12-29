<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dbbkp extends CI_controller
{
    /* public function __construct()
	{
		parent::__construct();
		$this->load->database();  
	}  */

    public function index()
    {
		//$this->load->database();
		exit("Test");
        //echo "gdsjadhs";exit;
		$this->load->dbutil();
		$prefs = array(
		'format' => 'zip',
		'filename' => 'my_db_backup.sql'
		);
		
		$backup =& $this->dbutil->backup($prefs);
		//print_r($backup);exit;
		$db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
		$save = 'public/uploads/'.$db_name;
		$this->load->helper('file');
		write_file($save, $backup);
		$this->load->helper('download');
		force_download($db_name, $backup);
    }
}
