<?php
class DCSMS extends CI_Controller {


	public function index(){ //loaded by default if second segment of URI is empty
		
		#$this->load->model('DCSMS_Model');
		$this->load->view('AMSwelcome');

	}		

	public function home(){
		$query = $this->input->get("INPUT");
		$this->load->helper('url');
		$data['searchString'] = $query;
		$this->load->view('AMShome', $data);

	}
<<<<<<< HEAD

	
	public function prof(){
		$this->load->helper('url');
		$this->load->view('AMSindividualprofile');
	}

	public function exportdb(){
		$this->load->helper('url');
		$this->load->view('AMShome');
	}
=======
>>>>>>> b8fc165f0c61fcc90d3e44914b80425abedf0cfe
	//PLAN
	//Pagkapindot ng buttons sa welcome page, load default page
	//On welcome page, pagkapindot ng update and ng export, hindi magbabago ung look nung page
	//Tables on that page, sortable, use jQuery for this
	//On click of student numbers, show their individual profile



}

?>