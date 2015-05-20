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
		/*for($i =0; $i <= count($data)-1; $i++){
			$this->DCSMS_Model->addStudentInfo($data[$i]['stunum'], $data[$i]['name']);
			for($j=0; $j <= count($data[$i]['grades'])-1; $j++){
				$this->DCSMS_Model->addStudentGWA($data[$i]['stunum'], $data[$i]['grades'][$j]['schoolYear'], $data[$i]['grades'][$j]['semNumber'], $data[$i]['grades'][$j]['GWA']);
				for($k=0; $k <= count($data[$i]['grades'][$j]['GradesForSem'])-1; $k++){
					$this->DCSMS_Model->addStudentGrade($data[$i]['stunum'], $data[$i]['grades'][$j]['GradesForSem'][$k]['subject'], $data[$i]['grades'][$j]['GradesForSem'][$k]['units'], $data[$i]['grades'][$j]['GradesForSem'][$k]['grade'], $data[$i]['grades'][$j]['schoolYear'], $data[$i]['grades'][$j]['semNumber']);
				}
			}
		}
		*/
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
				$this->DCSMS_Model->addStudentGWA($stunum, $schoolYear, $semNumber, $row2['GWA']);
				foreach($row2['GradesForSem'] as $row3){
					$this->DCSMS_Model->addStudentGrade($stunum, $row3['subject'], $row3['units'], $row3['grade'], $schoolYear, $semNumber);
				}
			}
		}
		return count($data[$i]['grades']);
	
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
		$this->load->model('DCSMS_Model');
		$this->load->helper('url');

		$StuNum = $this->uri->segment(3);
		$query1 = $this->DCSMS_Model->getStudent($StuNum);
		$query2 = $this->DCSMS_Model->getStudentGWA($StuNum);
		$query3 = $this->DCSMS_Model->getDQs($StuNum);
		
		$data['query1'] = $query1;
		$data['query2'] = $query2;
		$data['query3'] = $query3;
		
		$data['StuNum'] = $StuNum;
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

