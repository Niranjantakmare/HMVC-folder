<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs extends MY_Controller {

	public function __construct(){
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		$this->load->helper(array('form', 'url','json_output'));
        $this->load->library('form_validation');
			  $this->load->model('auth_service');
        /*
        $check_auth_client = $this->auth_service->check_auth_client();
		if($check_auth_client != true){
			die($this->output->get_output());
		}
		*/
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

	public function performerSongsList($pageNo=0,$pageLimit=10,$searchStr=''){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
		        $ShowId  = $this->input->get_request_header('ShowId', TRUE);
				$resp = $this->auth_service->get_performer_songslist($ShowId,$pageNo,$pageLimit,$isFavorite=0,$isCompleted=0,$searchStr);
				json_output(200,$resp);
			}
		}
	}
	public function performerFavoriteSongsList($pageNo=0,$pageLimit=10){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
		        $ShowId  = $this->input->get_request_header('ShowId', TRUE);
				$resp = $this->auth_service->get_performer_songslist($ShowId,$pageNo,$pageLimit,$isFavorite=1,$isCompleted=0,$searchStr='');
				json_output(200,$resp);
			}
		}
	}
	
	public function performerCompletedSongsList($pageNo=0,$pageLimit=10){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
		       
				$show_id  = $this->input->get_request_header('ShowId', TRUE);
				$resp = $this->auth_service->get_performer_songslist($show_id,$pageNo,$pageLimit,$isFavorite=0,$isCompleted=1,$searchStr='');
				json_output(200,$resp);
				
			}
		}
	}
	
	public function NewGuid()
    {
        $s        = strtoupper(md5(uniqid(rand(), true)));
        $guidText = substr($s, 0, 8) . '-' . substr($s, 8, 4) . '-' . substr($s, 12, 4) . '-' . substr($s, 16, 4) . '-' . substr($s, 20);
        return $guidText;
    }
	
}
