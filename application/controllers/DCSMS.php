<?php
class DCSMS extends CI_Controller {


	public function index(){ //loaded by default if second segment of URI is empty
		
		#$this->load->model('DCSMS_Model');

		$this->load->helper('url');
		//$this->load->view('AMSwelcome');
		$this->load->view('AMSwelcome');
	}		

	public function home(){

		$this->load->helper('url');
		$this->load->model('DCSMS_Model');
		
		$query = $this->input->get("INPUT");
		$buttonPushed = $this->input->get('submit');
		$data['searchString'] = $query;
		$data['buttonPushed'] = $buttonPushed;
		$this->load->view('AMShome', $data);
	}
	
	public function prof(){
		$this->load->helper('url');
		$this->load->view('AMSindividualprofile');
	}

	public function exportdb(){
		$this->load->helper('url');
		//$this->load->view('AMShome');
		$this->load->model('DCSMS_Model');

		$this->DCSMS_Model->exportDBtoCSV();
		$this->home();
	}
	//PLAN
	//Pagkapindot ng buttons sa welcome page, load default page
	//On welcome page, pagkapindot ng update and ng export, hindi magbabago ung look nung page
	//Tables on that page, sortable, use jQuery for this
	//On click of student numbers, show their individual profile
}

