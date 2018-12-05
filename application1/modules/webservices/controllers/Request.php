<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {

	public function __construct(){
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		$this->load->helper(array('form', 'url','json_output'));
        $this->load->library('form_validation');
	    $this->load->model('auth_service');
    }

	public function index(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
				$resp = $this->auth_service->performer_all_data();
	    		json_output(200,$resp);
		    }
		}
	}
	
	public function createRequest(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
				$params = json_decode(file_get_contents('php://input'), TRUE);
				$this->form_validation->set_rules('songID', 'Song Name', 'required');
				$this->form_validation->set_rules('bidAmountnt', 'Bid Amount', 'required');
				$this->form_validation->set_rules('customerName', 'Customer Name', 'required|valid_email');
				$this->form_validation->set_rules('customerComment', 'Customer Comment', 'required');
				if ($this->form_validation->run() == FALSE){
					$errors = validation_errors();
					$errorMessage=json_encode(['error'=>$errors]);
					$resp = array('status' => 400,'message' =>  $errorMessage);
					json_output(200,$resp);
				}else{
					$resp = $this->auth_service->request_song($params);
				}

			}
		}
	}
	
	public function NewGuid(){
        $s        = strtoupper(md5(uniqid(rand(), true)));
        $guidText = substr($s, 0, 8) . '-' . substr($s, 8, 4) . '-' . substr($s, 12, 4) . '-' . substr($s, 16, 4) . '-' . substr($s, 20);
        return $guidText;
    }
	
}
