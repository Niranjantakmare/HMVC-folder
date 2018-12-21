<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {

	public function __construct(){
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		$this->load->helper(array('form', 'url','json_output'));
        $this->load->library('form_validation');
	    $this->load->model('auth_service');
		date_default_timezone_set('Asia/Calcutta'); 
    }

	public function index(){
		
	}
	function alpha_dash_space($fullname){
		if (! preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function getAllCurrentShowRequests($pageNo=0,$pageLimit=10){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
		        $ShowId  = $this->input->get_request_header('ShowId', TRUE);
				$resp = $this->auth_service->getAllShowRequests($pageNo,$pageLimit,$ShowId);
				json_output(200,$resp);
			}
		}
	}
	
	public function getShowSongList($pageNo=0,$pageLimit=10,$showStatus="L"){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
		        $ShowId  = $this->input->get_request_header('ShowId', TRUE);
				$resp = $this->auth_service->getShowSonglist($pageNo=0,$pageLimit=10,$ShowId,$showStatus);
				json_output(200,$resp);
			}
		}
	}
	

	function callback_alpha_dash_space($fullname){
		if (! preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
			return FALSE;
		} else {
			return TRUE;
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
				$this->form_validation->set_rules('songID', 'Song Name', 'trim|required|numeric|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('tip', 'Tip', 'trim|required|numeric|min_length[1]|max_length[5]');
				$this->form_validation->set_rules('customerName', 'Customer Name', 'trim|required|callback_alpha_dash_space|min_length[1]|max_length[50]');
				$this->form_validation->set_rules('customerComment', 'Customer Comment', 'min_length[3]|trim|required|callback_alpha_dash_space');
				if ($this->form_validation->run() == FALSE){
					$errors = validation_errors();
					$errorMessage=json_encode(['error'=>$errors]);
					$resp = array('status' => 400,'message' =>  $errorMessage);
					json_output(200,$resp);
				}else{
					$date_time=date("Y-m-d h:i:s");
					$songID=trim($_POST['songID']);
					$tip=trim($_POST['tip']);
					$customerName=trim($_POST['customerName']);
					$customerComment=trim($_POST['customerComment']);
					$songName=trim($_POST['songName']);
					$ShowId  = $this->input->get_request_header('ShowId', TRUE);
					$post_data=array("songID"=>$songID,
					"songName"=>$songName,
					"tip"=>$tip,
					"showID"=>$ShowId,
					"customerName"=>$customerName,
					"comment"=>$customerComment,
					"created_on"=>$date_time);
					
					$resp = $this->auth_service->request_song($post_data);
					if($resp['status']){
							json_output(200,$resp);
					}else{
							json_output(202,$resp);
					}
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
