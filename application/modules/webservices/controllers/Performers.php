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
		date_default_timezone_set('Asia/Calcutta'); 
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
	
	public function forgotPassword(){
		$this->load->library('email'); // load email library
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client){
				$email_address=trim($_POST['email_address']);
				$resp = $this->auth_service->get_performer_data($email_address);
				$content             = $this->load->view('performer/user_forgetpassword_mail.php', $resp['data'], true);
				//die;
				if($resp['status']){
					$this->email->from('niranjantakmare@gmail.com', 'Total Request Live');
					$this->email->to('monika.bonage@gmail.com');
					$this->email->subject('Check you forgetted password');
					$this->email->message($content);
					if ($this->email->send()){
						json_output(200,array('status' => 1,'message' => 'You username and password has been successfully sented to your email id. Please check your email....'));
					}
					else{
						json_output(200,array('status' => 2,'message' => 'Failed to send username and password'));
					}
				}else{
					json_output(200,array('status' => 2,'message' => 'Email address is not found..'));
				}
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
	function callback_alpha_dash_space($fullname){
		if (! preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	function CheckEmailAddressAlreadyExist(){
		$method = $_SERVER['REQUEST_METHOD'];
	
		if($method != 'GET'){
			$arrayToJs = array();
			$arrayToJs[0] = $validateId;
			$arrayToJs[1] = false;

		} else {
			$validateValue=$_REQUEST['fieldValue'];
			$validateId=$_REQUEST['fieldId'];
			$validateError= "This username is already taken";
			$validateSuccess= "This username is available";
			$resp = $this->auth_service->checkEmailID($validateValue);
			$arrayToJs = array();
			$arrayToJs[0] = $validateId;
			if($resp){
				$arrayToJs[1] = true;
			}else{
				$arrayToJs[1] = false;
			}
		}
			echo json_encode($arrayToJs);
	}
	
	function create(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
					$params = json_decode(file_get_contents('php://input'), TRUE);
					$this->form_validation->set_rules('profileFirstName', 'First Name', 'trim|required|min_length[1]|max_length[25]');
					$this->form_validation->set_rules('profileLastName', 'Last Name', 'trim|required|min_length[1]|max_length[25]');
					$this->form_validation->set_rules('profileStageName', 'Stage Name', 'trim|required|min_length[1]|max_length[25]');
					$this->form_validation->set_rules('profileEmail', 'Email Address', 'trim|required|min_length[1]|max_length[255]');
					$this->form_validation->set_rules('profilePassword', 'Password', 'trim|required|min_length[6]|max_length[15]');
					$this->form_validation->set_rules('profileConfirmPassword', 'Confirm password', 'trim|required|min_length[6]|max_length[15]');
					$this->form_validation->set_rules('profileMessage', 'Message', 'trim|required|min_length[1]|max_length[500]');
				  $changeprofilephoto_size = $_FILES["changeprofilephoto"]["size"];
				 $profile_file_name = $_FILES["changeprofilephoto"]["name"];
				if($changeprofilephoto_size != 0)
				{
					$config = array(
					'upload_path' => "./images/",
					'allowed_types' => "gif|jpg|png|jpeg",
					'overwrite' => TRUE,
					'max_size' => "10240000"
					);
					//print_r($config);
					$this->load->library('upload', $config);
					$this->upload->initialize($config); 
					if($this->upload->do_upload('changeprofilephoto'))
					 {
						// $data = array('upload_data' => $this->upload->data());
					//1	 echo json_encode($data);
						// print_r($data);
					}
					else
					{
						$resp = array('status' => 204,'message' =>  $this->upload->display_errors());
						echo json_encode($resp);
						//echo json_output(204,$resp);
						die;
					}
				}else{
						$resp = array('status' => 204,'message' =>  'Please upload file');
						echo json_encode($resp);
						die;
				}

					if ($this->form_validation->run() == FALSE){
						$errors = validation_errors();
						$resp = array('status' => 204,'message' =>  $errors);
						json_output(200,$resp);
					}else{
						$date_time=date("Y-m-d h:i:s");
						$profileFirstName=trim($_POST['profileFirstName']);
						$profileLastName=trim($_POST['profileLastName']);
						$profileStageName=trim($_POST['profileStageName']);
						$profileEmail=trim($_POST['profileEmail']);
						$profilePassword=trim($_POST['profilePassword']);
						$profileConfirmPassword=trim($_POST['profileConfirmPassword']);
						$profileMessage=trim($_POST['profileMessage']);
						$unique_id=$this->NewGuid();
						$post_data=array("firstname"=>$profileFirstName,
						"lastname"=>$profileLastName,
						"unique_id"=>$unique_id,
						"stagename"=>$profileStageName,
						"email"=>$profileEmail,
						"image"=>$profile_file_name,
						"password"=>$profilePassword,
						"message"=>$profileMessage,
						'created_at'=>$date_time,
						);
						
						$resp = $this->auth_service->performer_create_data($post_data);
						if($resp['status']){
							json_output(200,$resp);
						}else{
							json_output(204,$resp);
						}
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
	
	public function getShowDetails($unique_id=''){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
		
			if($check_auth_client == true){
		        $response = $this->auth_service->auth();
				 $respStatus = $response['status'];
				$performer_id=2;
		        if($response['status'] == 200){
					if(!empty($unique_id)){
						$resp = $this->auth_service->getShowDetails($unique_id);
						if(count($resp)>0){
							json_output(200,array('status' => 1,'data' => $resp));
						}else{
							json_output(200,array('status' => 2,'message' => 'request details not found'));
						}
					}else{
					
					json_output(400,array('status' => 3,'message' => 'Please provide valid parameter'));
					}
		        }
			}
		}
	}	
	public function getAllPerformerShows($pageNo=0,$pageLimit=10){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
		
			if($check_auth_client == true){
		        $response = $this->auth_service->auth();
			    $respStatus = $response['status'];
				  $users_id  = $this->input->get_request_header('User-ID', TRUE);
		        if($response['status'] == 200){
					$resp = $this->auth_service->getAllPerformerShows($pageNo,$pageLimit,$users_id);
					json_output($respStatus,$resp);
		        }
			}
		}
	}
	
	
	public function startPerformerShow(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST' ){
			json_output(200,array('status' => 2,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
		
			if($check_auth_client == true){
		        $response = $this->auth_service->auth();
			    $respStatus = $response['status'];
				if($response['status'] == 200){
					if(isset($_POST['show_id']) && !empty($_POST['show_id'])){
						$show_id=trim($_POST['show_id']);
						$unique_id=trim($_POST['unique_id']);
						$resp = $this->auth_service->startShow($unique_id,$show_id);
						json_output(200,$resp);
					}else{
						json_output(200,array('status' => 2,'message' => 'Show id not available to start show'));
					}
		        }
			}
		}
	}
	
	public function endPerformerShow(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
		
			if($check_auth_client == true){
		        $response = $this->auth_service->auth();
			    $respStatus = $response['status'];
				if($response['status'] == 200){
					if(isset($_POST['show_id']) && !empty($_POST['show_id'])){
						$show_id=trim($_POST['show_id']);
						$unique_id=trim($_POST['unique_id']);
						$resp = $this->auth_service->endShow($unique_id,$show_id);
						json_output(200,$resp);
					}else{
						json_output(200,array('status' => 2,'message' => 'Show id not available to start show'));
					}
		        }
			}
		}
	}
	
	public function mySongsList($pageNo=0,$pageLimit=10){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
			//	$response = $this->auth_service->auth();
			   // $respStatus = $response['status'];
				$response['status']=200;
				if($response['status'] == 200){
					$users_id  = $this->input->get_request_header('User-ID', TRUE);
					$searchStr=TRIM($_POST['searchString']);
					$resp = $this->auth_service->MySongsList($users_id,$pageNo,$pageLimit,$isFavorite=0,$isCompleted=0,$searchStr);
					json_output(200,$resp);
				}
			}
		}
	}
	
	public function AllSongsList($pageNo=0,$pageLimit=10){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST' ){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->auth_service->check_auth_client();
			if($check_auth_client == true){
			//	$response = $this->auth_service->auth();
			   // $respStatus = $response['status'];
				$response['status']=200;
				if($response['status'] == 200){
					$searchStr=TRIM($_POST['searchString']);
					$resp = $this->auth_service->AllSongsList($pageNo,$pageLimit,$searchStr);
					json_output(200,$resp);
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
