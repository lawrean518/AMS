<?php
class DCSMS extends CI_Controller {


	public function index(){ //loaded by default if second segment of URI is empty
		
		#$this->load->model('DCSMS_Model');
		$this->load->helper('url');
		//$this->load->view('AMSwelcome');
		$this->load->view('welcome_message');

	}		

	public function home(){
		$query = $this->input->get("INPUT");
		$this->load->helper('url');
		$data['searchString'] = $query;
		$this->load->view('AMShome', $data);
	}
}