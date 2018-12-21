<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {
	
	
	public function __construct(){
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		$this->load->helper(array('form', 'url','json_output'));
        $this->load->library('form_validation');
	
		  $this->load->model('auth_service');
    }
	
	public function login()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
		//header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-
		//Encoding, X-Auth-Token, content-type');
		$method = $_SERVER["REQUEST_METHOD"];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			 $check_auth_client = $this->auth_service->check_auth_client();
			
			if($check_auth_client == true){
				
				$email_id = $_GET['email_id'];
				$password = $_GET['password'];
				$config = [
					[
						'field' => 'email_id',
						'label' => 'email_id',
						'rules' => 'trim|required|min_length[5]|max_length[25]|valid_email',
						'errors' => [
								'required' => 'You must provide a Email id',
								'min_length'=>'Minimum email id length is 3 characters',
								'alpha_dash'=>'You can just use a-z 0-9 _ . – characters for input',
						]
					],
					[
						'field' => 'password',
						'label' => 'password',
						'rules' => 'required|min_length[6]|max_length[15]',
						'errors' => [
								'required' => 'You must provide a password.',
								'min_length'=>'Minimum password length is 6 characters',
						]
					]
				];
				$data = $this->input->get();
				$this->form_validation->set_data($data);
				$this->form_validation->set_rules($config);
				if($this->form_validation->run()==FALSE){
					$validatation_error=implode(",",$this->form_validation->error_array());
					echo json_encode(array('status' => 204,'message' => $validatation_error));
				}
				else{
					$response = $this->auth_service->login($email_id,$password);
					echo json_encode($response);
				}
			}
		}
	}

	public function logout()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->MyModel->logout();
				json_output($response['status'],$response);
			}
		}
	}
	
}
