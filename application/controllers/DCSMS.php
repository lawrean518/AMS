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
		foreach($data as $row){
			$stunum = $row['stunum'];
			$stuname = $row['name'];
			$AH = $row['AH'];
			$MST = $row['MST'];
			$SSP = $row['SSP'];
			$this->DCSMS_Model->addStudentInfo($stunum, $stuname, $AH, $MST, $SSP);
			foreach($row['grades'] as $row2){
				$schoolYear = $row2['schoolYear'];
				$semNumber = $row2['semNumber'];
				$failed = $row2['failed'];
				$passed = $row2['passed'];
				$this->DCSMS_Model->addStudentGWA($stunum, $schoolYear, $semNumber, $row2['GWA']);
				foreach($row2['GradesForSem'] as $row3){
					$this->DCSMS_Model->addStudentGrade($stunum, $row3['subject'], $row3['units'], $row3['grade'], $schoolYear, $semNumber);
				}
				$this->addDQs($stunum, $failed, $passed);
			}
		}
		return count($data[$i]['grades']);
	
		//$this->addDQs(201420142, 60, 40);	
	} 	

	public function addDQs($student, $failed, $passed){
		$this->load->model('DCSMS_Model');
		if($passed==0)
			$this->DCSMS_Model->addDQs($student, "PERMANENT DISQUALIFICATION: Failed to pass at least one unit the last semester");
		if($failed >= 75)
			$this->DCSMS_Model->addDQs($student,  "For dismissal: Failed to pass at least 25% of units taken");
	/*	if(DQDetails contains "On probation: "&& failed > 50% of units)
			output: "For dismissal: Failed to lift probation status"
		if(a student has units < 110 && passed < 24 units for the SY)
			output: "For dismissal: Failed to pass at least 24 units creditable to the curriculum for the school year"
		if (student has units < 110 && passed < 50% units for the SY)
			output: "For dismissal: Failed to pass at least 50% of units taken during the SY"
	*/	if($failed >= 50 && $failed < 75)
		 	$this->DCSMS_Model->addDQs($student, "On probation: Failed to obtain final grades of '3' or better in 50% to 75% of academics taken during the semester");
		if($failed >= 25 && $failed < 50)
		 	$this->DCSMS_Model->addDQs($student, "Warning: Failed to pass in 25% to less than 50% of units taken this semester");
		if(($this->DCSMS_Model->checkSubjectFailures($student, "CS11"))>=2)
			$this->DCSMS_Model->addDQs($student, "For dismissal: Failed to pass CS 11 within two takes");
		if(($this->DCSMS_Model->checkSubjectFailures($student, "CS12"))>=2)
			$this->DCSMS_Model->addDQs($student, "For dismissal: Failed to pass CS 12 within two takes");
		if(($this->DCSMS_Model->checkSubjectFailures($student, "CS21"))>=2)
			$this->DCSMS_Model->addDQs($student, "For dismissal: Failed to pass CS 21 within two takes");
		if(($this->DCSMS_Model->checkSubjectFailures($student, "CS32"))>=2)
			$this->DCSMS_Model->addDQs($student, "For dismissal: Failed to pass CS 32 within two takes");
		if(($this->DCSMS_Model->checkSubjectFailures($student, "Math17"))>=2)
			$this->DCSMS_Model->addDQs($student, "For dismissal: Failed to pass Math 17 within two takes");
		if(($this->DCSMS_Model->checkSubjectFailures($student, "Math53"))>=2)
			$this->DCSMS_Model->addDQs($student, "For dismissal: Failed to pass Math 53 within two takes");
		if(($this->DCSMS_Model->checkSubjectFailures($student, "Math54"))>=2)
			$this->DCSMS_Model->addDQs($student, "For dismissal: Failed to pass Math 54 within two takes");
		if(($this->DCSMS_Model->checkSubjectFailures($student, "Math55"))>=2)
			$this->DCSMS_Model->addDQs($student, "For dismissal: Failed to pass Math 55 within two takes");
		
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

