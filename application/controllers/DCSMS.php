<?php
class DCSMS extends CI_Controller {


	public function index(){ //loaded by default if second segment of URI is empty
		
		#$this->load->model('DCSMS_Model');
		$this->load->helper('url');
		$this->load->view('DCSMSview_default');

	}		




}

?>