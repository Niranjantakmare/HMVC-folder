<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Performers extends MY_Controller {

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
	public function forgotPassword(){
		$this->load->library('email'); // load email library
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'GETd'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == false){
				$email_address="graeme4545@gmail.com";
				$resp = $this->auth_service->get_performer_data($email_address);
				$content             = $this->load->view('performer/user_forgetpassword_mail.php', $resp['data'], true);
				//die;
				if($resp['status']){
					$this->email->from('niranjantakmare@gmail.com', 'Total Request Live');
					$this->email->to('monika.bonage@gmail.com');
					$this->email->subject('Check you forgetted password');
					$this->email->message($content);
					if ($this->email->send()){
						json_output(200,array('status' => 200,'message' => 'You username and password has been successfully sented to your email id. Please check your email....'));
					}
					else{
						json_output(200,array('status' => 200,'message' => 'Failed to send username and password'));
					}
				}else{
					json_output(200,"Email address doesn't exist.");
				}
		    }
		}
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
	
	public function check_shorten_url($tiny_url){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
				$result = $this->auth_service->check_shorten_url($tiny_url);
				json_output(200,$result);
		    }
		}
	}

	public function detail($unique_id){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
				$resp = $this->auth_service->performer_detail_data($unique_id);
				json_output(200,$resp);
		    }
		}
	}

	public function create(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->auth_service->auth();
		        $respStatus = $response['status'];
		        if($response['status'] == 200){
					$params = json_decode(file_get_contents('php://input'), TRUE);
					if ($params['firstname'] == "" || $params['lastname'] == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'firstname or lastname can\'t empty');
					} else {
		        		$resp = $this->auth_service->performer_create_data($params);
					}
					json_output($respStatus,$resp);
		        }
			}
		}
	}

	public function update($id){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'PUT' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->auth_service->auth();
		        $respStatus = $response['status'];
		        if($response['status'] == 200){
					$params = json_decode(file_get_contents('php://input'), TRUE);
					$params['updated_at'] = date('Y-m-d H:i:s');
					if ($params['firstname'] == "" || $params['lastname'] == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'firstname or lastname can\'t empty');
					} else {
		        		$resp = $this->auth_service->performer_update_data($id,$params);
					}
					json_output($respStatus,$resp);
		        }
			}
		}
	}

	public function delete($id){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'DELETE' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->auth_service->auth();
		        if($response['status'] == 200){
		        	$resp = $this->auth_service->performer_delete_data($id);
					json_output($response['status'],$resp);
		        }
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
