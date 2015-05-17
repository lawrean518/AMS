<?php
class DCSMS extends CI_Controller {

	public function index(){ //loaded by default if second segment of URI is empty
		$this->load->helper('url');
		$this->load->view('AMSwelcome');
	}		

	public function search(){ //passing parameters for search button loading view homepage
		$this->load->helper('url');
		$this->load->model('DCSMS_Model'); //load model

		$searchString = $this->input->get("INPUT"); 
		$searchBy = $this->input->get("DROPDOWN");
		$buttonPushed = $this->input->get('submit');
		$data['searchString'] = $searchString;
		$data['buttonPushed'] = "Search";
		$data['searchBy'] = $searchBy;
		$query = $this->DCSMS_Model->showSearchQuery($searchString, $searchBy); //gets the value of search textbox and the dropdown menu
		$data['query'] = $query;
		$this->load->view('AMShome', $data);
	}

	public function showAll(){ //passing parameters for show all button loading view homepage
		$this->load->helper('url');
		$this->load->model('DCSMS_Model');
	
		$buttonPushed = $this->input->get('submit');
		$data['searchString'] = "";
		$data['buttonPushed'] = "Show All";
		$data['searchBy'] = "Student Number";
		$query = $this->DCSMS_Model->showAllStudents();
        $data['query'] = $query;        
		$this->load->view('AMShome', $data);
	}
	public function script(){
		$data = (array) json_decode($_POST['json'], true);
		$i =0;
		$j = 0;
		$k =  0;
	  // here i would like use foreach
		$this->load->model('DCSMS_Model');
		for($i =0; $i <= count($data)-1; $i++){
			for($j=0; $j <= count($data[$i]['grades'])-1; $j++){
				for($k=0; $k <= count($data[$i]['grades'][$j]['GradesForSem'])-1; $k++){
					echo $this->DCSMS_Model->addStudent($data[$i]['stunum'], $data[$i]['name'], $data[$i]['grades'][$j]['GradesForSem'][$k]['subject'], $data[$i]['grades'][$j]['GradesForSem'][$k]['units'], $data[$i]['grades'][$j]['GradesForSem'][$k]['grade'], $data[$i]['grades'][$j]['SchoolYear'], $data[$i]['grades'][$j]['SemNumber'], $data[$i]['grades'][$j]['GWA']);
				}
			}
		}
	
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
								
		$query = $this->input->get("INPUT"); 
		$by = $this->input->get("DROPDOWN");
		$buttonPushed = $this->input->get('submit');

		$data['searchString'] = "";
		$data['buttonPushed'] = "Export DB";
		$data['searchBy'] = "Student Number";
		$this->load->view('AMShome', $data);
	//$this->home();
	}
	//PLAN
	//Pagkapindot ng buttons sa welcome page, load default page
	//On welcome page, pagkapindot ng update and ng export, hindi magbabago ung look nung page
	//Tables on that page, sortable, use jQuery for this
	//On click of student numbers, show their individual profile
}

