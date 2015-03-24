<?php
class DCSMS extends CI_Controller {

	public function index(){ //loaded by default if second segment of URI is empty
		$this->load->helper('url');
		$this->load->view('AMSwelcome');
	}		

	public function search(){ //passing parameters for search button loading view homepage
		$this->load->helper('url');
		$this->load->model('DCSMS_Model'); //load model

		$query = $this->input->get("INPUT"); 
		$by = $this->input->get("DROPDOWN");
		$buttonPushed = $this->input->get('submit');
		$data['searchString'] = $query;
		$data['buttonPushed'] = "Search";
		$data['searchBy'] = $by;
		$this->load->view('AMShome', $data);
	}

	public function showAll(){ //passing parameters for show all button loading view homepage
		$this->load->helper('url');
		$this->load->model('DCSMS_Model');
		
		$query = $this->input->get("INPUT");

		$buttonPushed = $this->input->get('submit');
		$data['searchString'] = "";
		$data['buttonPushed'] = "Show All";
		$data['searchBy'] = "Student Number";
		$this->load->view('AMShome', $data);
	}

	public function individualProfile(){ //passing parameter for individual profile loading view individual profile
		$this->load->helper('url');
		$this->load->model('DCSMS_Model');
		$this->load->view('AMSindividualprofile');

		$remarks = $this->input->post('myRemark');
		$stuNum = $this->uri->segment(3);
		$this->DCSMS_Model->updateRemarks($stuNum, $remarks);
		$this->showIndividualProfile();
	}

	public function showIndividualProfile(){
		$this->load->helper('url');
		$this->load->model('DCSMS_Model');
		$data['StuNum'] = $this->uri->segment(3);
		$this->load->view('AMSindividualProfile', $data);
	}

	public function showIndividualProfile_(){ //showindividual profile
		$this->load->helper('url');
		$this->load->model('DCSMS_Model');
		$remarks = $this->input->post('myRemark');
		$stuNum = $this->uri->segment(3);
		$this->DCSMS_Model->updateRemarks($stuNum, $remarks);
		$this->showIndividualProfile();
	}

	public function exportDB(){ //export to database
		$this->load->helper('url');
		$this->load->model('DCSMS_Model');
		$this->DCSMS_Model->exportDBtoCSV();
		$this->search();
//		$this->home();
	}
	//PLAN
	//Pagkapindot ng buttons sa welcome page, load default page
	//On welcome page, pagkapindot ng update and ng export, hindi magbabago ung look nung page
	//Tables on that page, sortable, use jQuery for this
	//On click of student numbers, show their individual profile
}

