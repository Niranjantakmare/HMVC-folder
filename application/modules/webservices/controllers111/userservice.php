<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class userservice extends CI_Controller
{
    public $per_page = 10;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home/home_model');
        
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->model('webservice/user_web_model', 'u');
        $this->load->model('candidate/getallselectresults');
        $this->load->model('search/Locations');
        $this->load->model('search/Jobs');
        $this->load->library('session');
        $this->load->model("search/Function_Job_Category", "f");
        $this->load->library('email');
        $this->load->model("search/EmploymentType", "emptype");
        $this->load->model("home/company_dashboard_model", "company_model");
        $this->load->model("search/QualificationMaster", "qtype");
        $this->load->model("search/ExperienceLevel", "elevel");
        $this->load->model("search/Industry");
        $this->load->model('candidate/createprofileinsert');
        $this->load->helper('url_clean_helper');
        $this->load->helper('mail_helper');
        $this->load->helper('ckeditor');
        $this->load->model("candidate/getallselectresults");
        $this->load->library('mongo_db');
        $this->load->model('candidate/get_dashboard_details', 'get_details');
        $this->load->model('createprofileinsert');
        $this->config->load('searchconfig');
        
    }
    
    public function index()
    {
        
        
        
    }
    
    
    function getVerificationCode()
    {
        $alphabet    = "abcdefghijklmnpqrstuwxyzo123456789";
        $pass        = array();
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 6; $i++) {
            $n      = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string        
    }
    
    public function NewGuid()
    {
        
        $s        = strtoupper(md5(uniqid(rand(), true)));
        $guidText = substr($s, 0, 8) . '-' . substr($s, 8, 4) . '-' . substr($s, 12, 4) . '-' . substr($s, 16, 4) . '-' . substr($s, 20);
        return $guidText;
        
    }
    
    
    public function login()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            $email_id = $postdata['email_id'];
            $password = $postdata['password'];
            if (!empty($email_id) && !empty($password)) {
                $result = $this->u->validateUserCredential($email_id, md5($password));
                if ($result) {
                    if ($result['is_published'] == 1) {
                        if ($result['is_active'] == 0) {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Your account is already exist but deleted by admin to activate please contact to EnergyJob admin.";
                        } else {
                            $userdata                       = array();
                            $userdata['id']                 = $result['id'];
                            $userdata['first_name']         = $result['first_name'];
                            $userdata['last_name']          = $result['last_name'];
                            $userdata['email_id']           = $result['email_id'];
                            $userdata['profile_image_path'] = isset($result['profile_pic']) ? base_url() . PROFILE_IMAGES . $result['profile_pic'] : " ";
                            $userdata['mobile_no']          = isset($result['mobile_no']) ? $result['mobile_no'] : " ";
                            $dataArray['status']            = true;
                            $dataArray['userdata']          = $userdata;
                            $dataArray['message']           = LOGGEDIN;
                        }
                    } else {
                        
                        /*  $verify_id = $this->NewGuid();
                        
                        $updatePass = array(
                        'email_verification_code' => $verify_id
                        );
                        
                        $this->db->where('id', $result['id'] );
                        $this->db->update('users', $updatePass);
                        
                        
                        $actual_link = base_url() . "frontend/home/jobseeker_activate?email=".urlencode( $result['email_id'] )."&unique=".urlencode( $result['unique_id'])."&verify_id=".urlencode($verify_id)."&jobfair=".urlencode(0)."&mobile=1";
                        
                        $toEmail             = $result['email_id'];
                        $subject             = "EnergyJOBZ.com - Account activation link";
                        $data['actual_link'] = $actual_link;
                        $data['first_name']  = $result['first_name'];
                        
                        if(isset($_POST['is_campaign']) && !empty($_POST['is_campaign'])) {
                        
                        $data['compaingnuser']=" Please find below login credentials for your energyjobz.com account. 
                        <br> Username: ".$_POST['email']." <br> Password: ".$_POST['password'] ;
                        
                        }       
                        
                        $content             = $this->load->view('user/user_registration_mail.php', $data, true);
                        $statistics          = array();
                        
                        $headers = array (
                        'Content-Type'=>'text/html;charset=UTF-8',
                        'From' =>    SENDER,
                        'To' =>      $toEmail,
                        'Subject' => $subject
                        );
                        
                        if( $this->comman_class->send_ses_email( $headers,$content ) ) {
                        
                        $statistics['sent_datetime']        = date('Y-m-d H:i:s');
                        $statistics['email_status']         = 'Sent';
                        $dataArray['message']     = REACTIVATION_LINK;
                        
                        }else {
                        
                        $statistics['sent_datetime']        = date('Y-m-d H:i:s');
                        $statistics['email_status']         = 'Failed';
                        $dataArray['message']     = "Account already exits. Problem in reactivation email please try after some time. ";
                        }
                        
                        $this->comman_class->amazon_ses_statistics_email( $statistics );*/
                        
                        
                        $dataArray['message'] = "Thank you for registering with EarlyJoiner.com.please login with email id to access your account.";
                        
                        $dataArray['status'] = false;
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "Wrong email id or password";
                    
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Please provide email id and password";
            }
            
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        echo json_encode($dataArray);
    }
    
    public function forgotPassword()
    {
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (!empty($postdata['email_id']) && isset($postdata['email_id'])) {
                if (preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/', $postdata['email_id'])) {
                    
                    if ($this->u->checkIfEmailIdExists($postdata['email_id'])) {
                        $otp = $this->getVerificationCode();
                        
                        $query = "update users set otp='" . $otp . "' where email_id='" . $postdata['email_id'] . "'";
                        
                        if ($this->db->query($query)) {
                            $sql = "select first_name from users where email_id='" . $postdata['email_id'] . "'";
                            $res = $this->db->query($sql)->row();
                            
                            $mailHeaders = "From:Earlyjoiner \r\n";
                            $mailHeaders .= "MIME-Version: 1.0" . "\r\n";
                            $mailHeaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                            $subject = "Earlyjoiner Reset Password OTP";
                            $message = "Dear " . $res->first_name . ",<br/><br/>";
                            $message .= " One time password for reseting  password - " . $otp;
                            $content = $this->load->view("siteadmin/mailtemplate", array(
                                "contentapp" => $message
                            ), true);
                            $toEmail = $postdata['email_id'];
                            if (sendMail($toEmail, $subject, $content)) {
                                $dataArray['status']      = true;
                                $dataAArray['user_email'] = $postdata['email_id'];
                                $dataArray['message']     = "OTP has been send to your email id  to reset password";
                                
                            } else {
                                
                                $statistics['sent_datetime'] = date('Y-m-d H:i:s');
                                $statistics['email_status']  = 'Failed';
                                $this->comman_class->amazon_ses_statistics_email($statistics);
                                
                                $dataArray['status']  = false;
                                $dataArray['message'] = "Something went wrong try again";
                                
                            }
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = $query;
                            
                        }
                        
                        
                    } else {
                        
                        $dataArray['status']  = false;
                        $dataArray['message'] = "Email id not found";
                        
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "Enter valid email id";
                    
                }
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Enter email id ";
            }
            
            
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
        }
        
        echo json_encode($dataArray);
        
    }
    
    
    
    public function setPassword()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            
            
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (!empty($postdata['email_id']) && isset($postdata['email_id'])) {
                if (!empty($postdata['otp']) && isset($postdata['otp'])) {
                    if ((!empty($postdata['password']) && isset($postdata['password'])) && (!empty($postdata['cpassword']) && isset($postdata['cpassword']))) {
                        if (strlen($postdata['password']) < 6 || strlen($postdata['password']) > 25) {
                            
                            $dataArray['status']  = false;
                            $dataArray['message'] = " password should have minimum 6 character or maximum 25 characters ";
                            
                        } else {
                            if ($postdata['password'] == $postdata['cpassword']) {
                                
                                $query = "update users set password='" . md5($postdata['password']) . "', otp='' where email_id='" . $postdata['email_id'] . "'";
                                $query .= "and otp='" . $postdata['otp'] . "'";
                                $this->db->query($query);
                                if ($this->db->affected_rows()) {
                                    $dataArray['status']  = true;
                                    $dataArray['message'] = "Password has been reset successfully";
                                    //$dataArray['message']=$result;
                                } else {
                                    $dataArray['status']  = false;
                                    $dataArray['message'] = "Wrong otp or email id";
                                    
                                }
                                
                                
                            } else {
                                $dataArray['status']  = false;
                                $dataArray['message'] = "Password & confirm password not match";
                                
                            }
                            
                        }
                        
                        
                        
                        
                    } else {
                        if (empty($postdata['password']) && !isset($postdata['password'])) {
                            
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Enter password";
                            
                        }
                        
                        if (empty($postdata['cpassword']) && !isset($postdata['cpassword'])) {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Enter password";
                            
                            
                        }
                        
                        
                        
                    }
                    
                    
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "One time password is not provided";
                    
                    
                }
                
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Email id  not provided";
                
            }
            
            
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
            
        }
        
        echo json_encode($dataArray);
        
        
        
    }
    
    
    
    
    
    public function register()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $error    = array();
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            $postdata['unique_id'] = $this->NewGuid();
            if (!empty($postdata['email_id']) && isset($postdata['email_id'])) {
                if (preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/', $postdata['email_id'])) {
                    if ($this->u->checkIfEmailIdExists($postdata['email_id'])) {
                        $error[] = "Email Id already exist";
                        
                    }
                } else {
                    $error[] = "Enter valid email id";
                }
            } else {
                $error[] = "Enter email id ";
            }
            
            
            if (!empty($postdata['first_name']) && isset($postdata['first_name'])) {
                if (!preg_match('/^[A-Za-z ]{2,50}$/', $postdata['first_name'])) {
                    $error[] = "Enter only character and  firstName Should be have minimum 2 or maximmum 50 ";
                }
                
            } else {
                $error[] = "Enter first name";
            }
            
            
            if (!empty($postdata['last_name']) && isset($postdata['last_name'])) {
                if (!preg_match('/^[A-Za-z ]{2,50}$/', $postdata['last_name'])) {
                    $error[] = "Enter only character  and LastName Should be have minimum 2 or maximmum 50";
                }
            } else {
                $error[] = "Eneter first name";
            }
            
            if ((!empty($postdata['password']) && isset($postdata['password'])) && (!empty($postdata['cpassword']) && isset($postdata['cpassword']))) {
                
                if ($postdata['password'] != $postdata['cpassword']) {
                    
                    $error[] = "Password & Confirm password are not match";
                    
                    
                }
            } else {
                if (empty($postdata['password']) && !isset($postdata['password'])) {
                    $error[] = "Enter Password";
                }
                
                if (empty($postdata['cpassword']) && !isset($postdata['cpassword'])) {
                    $error[] = "Enter Password";
                }
            }
            if (!empty($postdata['mobile_no']) && isset($postdata['mobile_no'])) {
                if (!preg_match('/^[0-9]{7,15}$/', $postdata['mobile_no'])) {
                    $error[] = "mobile number  should be 7 or 15 digit";
                }
                
            } else {
                $error[] = "Enter mobile no";
            }
            
            
            if (count($error) > 0) {
                $dataArray['status']  = false;
                $dataArray['message'] = $error[0];
            } else {
                unset($postdata['cpassword']);
                $postdata['password'] = md5($postdata['password']);
                $postdata['username'] = $postdata['email_id'];
                $unique_id            = $this->NewGuid();
                
                $insert_id = $this->u->register($postdata, 0, 0, $unique_id);
                if ($insert_id != 0) {
                    
                    
                    $verify_id  = $this->NewGuid();
                    $updatePass = array(
                        'email_verification_code' => $verify_id
                    );
                    $this->db->where('unique_id', $unique_id);
                    $this->db->update('users', $updatePass);
                    $actual_link = base_url() . "home/jobseeker_home/jobseeker_activate?email=" . $postdata['email_id'] . "&unique=" . $unique_id . "&verify_id=" . urlencode($verify_id);
                    ;
                    $toEmail             = $postdata['email_id'];
                    $subject             = "To activate your earlyjoiner account and verify your email address";
                    $data['actual_link'] = $actual_link;
                    $data['first_name']  = $postdata['first_name'];
                    $content             = $this->load->view('home/user_registration_mail.php', $data, true);
                    
                    if (sendMail($toEmail, $subject, $content)) {
                        $msg = "You have registered successfully. An activation email has been sent to " . $toEmail . ". Please click on the link provided in the mail to activate your earlyjoiner account.";
                        
                        $dataArray['message'] = $msg;
                        
                        $dataArray['status'] = true;
                        
                        $status = 1;
                    } else {
                        
                        $dataArray['message'] = "Registration completed successfully.problem in send activation mail.please try again!";
                        
                        $dataArray['status'] = true;
                        
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "Something went wrong try again";
                }
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
        }
        
        
        echo json_encode($dataArray);
        
    }
    
    
    
    public function registerWithFacebook()
    {
        $dataArray = array();
        $flag      = 0;
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $error    = array();
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            $postdata['unique_id'] = $this->NewGuid();
            if (!empty($postdata['email_id']) && isset($postdata['email_id'])) {
                if (preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/', $postdata['email_id'])) {
                    
                    if ($this->u->checkIfEmailIdExists($postdata['email_id'])) {
                        
                        
                        $flag = 1;
                        
                    }
                }
            } else {
                
                $error[] = "Enter email id ";
            }
            
            
            if (!empty($postdata['first_name']) && isset($postdata['first_name'])) {
                if (!preg_match('/^[A-Za-z ]{2,50}$/', $postdata['first_name'])) {
                    $error[] = "Enter only character and  firstName Should be have minimum 2 or maximmum 50 ";
                }
                
            } else {
                $error[] = "Enter first name";
            }
            
            
            if (!empty($postdata['last_name']) && isset($postdata['last_name'])) {
                if (!preg_match('/^[A-Za-z ]{2,50}$/', $postdata['last_name'])) {
                    $error[] = "Enter only character  and LastName Should be have minimum 2 or maximmum 50";
                }
            } else {
                $error[] = "Eneter first name";
            }
            if ((!empty($postdata['password']) && isset($postdata['password'])) && (!empty($postdata['cpassword']) && isset($postdata['cpassword']))) {
                
                if ($postdata['password'] != $postdata['cpassword']) {
                    
                    $error[] = "Password & Confirm password are not match";
                    
                    
                }
            } else {
                if (empty($postdata['password']) && !isset($postdata['password'])) {
                    $error[] = "Enter Password";
                }
                
                if (empty($postdata['cpassword']) && !isset($postdata['cpassword'])) {
                    $error[] = "Enter Password";
                }
                
            }
            if (count($error) > 0) {
                $dataArray['status']  = false;
                $dataArray['message'] = $error[0];
                
            } else {
                unset($postdata['cpassword']);
                $postdata['password'] = md5($postdata['password']);
                unset($postdata['password']);
                $postdata['username'] = $postdata['email_id'];
                if ($flag == 0) {
                    $result = $this->u->register($postdata, 1);
                } else {
                    $result = $this->u->register($postdata, 1, 1);
                }
                
                if (count($result) > 0) {
                    $dataArray['status']            = true;
                    $dataArray['message']           = "Logged in  successfully";
                    $userdata                       = array();
                    $userdata['id']                 = $result['id'];
                    $userdata['first_name']         = $result['first_name'];
                    $userdata['last_name']          = $result['last_name'];
                    $userdata['email_id']           = $result['email_id'];
                    $userdata['gender']             = $result['gender'];
                    $userdata['profile_image_path'] = isset($result['profile_pic']) ? base_url() . PROFILE_IMAGES . $result['profile_pic'] : " ";
                    $userdata['mobile_no']          = isset($result['mobile_no']) ? $result['mobile_no'] : " ";
                    $dataArray['status']            = true;
                    $dataArray['userdata']          = $userdata;
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "Something went wrong try again";
                    
                }
            }
            
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        echo json_encode($dataArray);
    }
    
    public function saveProfile()
    {
        $dataArray = array();
        $error     = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata     = $_POST;
            // $postdata = json_decode( file_get_contents('php://input'), true );
            $user_profile = array();
            require_once APPPATH . 'libraries/filetotext.php';
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                
                if (isset($postdata['employer_join_date']) and !empty($postdata['employer_join_date'])) {
                    $or_Dateofjoining                   = date("Y-m-d", strtotime($postdata['employer_join_date']));
                    $user_profile['date_of_joining']    = $or_Dateofjoining;
                    $userprofiledata['date_of_joining'] = $or_Dateofjoining;
                } else {
                    $or_Dateofjoining                   = "";
                    $user_profile['date_of_joining']    = $or_Dateofjoining;
                    $userprofiledata['date_of_joining'] = $or_Dateofjoining;
                }
                
                $this->load->library('mongo_db');
                $mongo  = $this->mongo_db->db->candidate;
                $result = $mongo->find(array(
                    "id" => (int) $postdata['user_id']
                ));
                foreach ($result as $id => $post) {
                    foreach ($post as $key => $val) {
                        if ($key == "id") {
                            $this->userAllDetails[$key] = intval($val);
                        } else {
                            $this->userAllDetails[$key] = $val;
                        }
                    }
                }
                
                $userprofiledata = array();
                $filedata        = $_FILES;
                $this->load->helper('fileuploader');
                
                    $userprofiledata['is_resume_uploaded']=1;

                if (!empty($filedata['file']['name'])) {
                    $Config = array(
                        'allowed_types' => array(
                            'pdf',
                            'doc',
                            'docx'
                        ),
                        'upload_path' => "./" . RESUME_FOLDER,
                        'input_field_name' => 'file'
                    );
                    
                    $UploadResult = general_upload_file($Config, false);
                    if (!$UploadResult['status']) {
                        
                        $error[] = $UploadResult['error'];
                    } else {
                        $ext         = pathinfo($filedata['file']['name'], PATHINFO_EXTENSION);
                        $upload_dir1 = './uploads/Candidate_Resume/';
                        
                        $tmpName = $upload_dir1 . $UploadResult['file_name'];
                        
                        $tempfilename = "./uploads/Candidate_Resume/" . $UploadResult['file_name'];
                        if (file_exists($tempfilename)) {
                            chmod($tmpName, 0777);
                        }
                        $Resume_textformat = "";
                        if ($ext != 'txt') {
                            if ($ext == "pdf") {
                                $is_pdf_upload = 1;
                            }
                            $docObj            = new Filetotext($tmpName);
                            $Resume_textformat = $docObj->convertToText();
                        } else {
                            if ($ext == 'txt') {
                                $f = fopen($tmpName, "r");
                                
                                $Resume_textformat = '';
                                while (!feof($f)) {
                                    $Resume_textformat = $Resume_textformat . ' ' . fgets($f) . "";
                                }
                                fclose($f);
                                
                            } else {
                                $Resume_textformat = '';
                            }
                        }
                     
                        $user_profile['upload_resume_text']    = $Resume_textformat;
                        $user_profile['upload_resume_file']    =$UploadResult['file_name'];
                        $userprofiledata['upload_resume_text'] = $Resume_textformat;
                        $userprofiledata['upload_resume_file'] = $UploadResult['file_name'];
                        //  $postdata['original_resume_file_name']=$filedata['file']['name']; 
                                $userprofiledata['is_resume_uploaded']=1;
                        //  $postdata['uploaded_resume_date']=date("Y-m-d");
                        //$postdata['profile_update_on']=date("Y-m-d");
                        //  $postdata['created_on']=date("Y-m-d h:i:s");
                        // $postdata['updated_on']=date("Y-m-d h:i:s");
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "File not selected";
                }
                
                
                if (isset($postdata['file'])) {
                    unset($postdata['file']);
                }
                
                if (empty($postdata['mobile_no']) && !isset($postdata['mobile_no'])) {
                    $error[] = "Mobile number not provided";
                } else {
                    $personal_details['mobile_no'] = $postdata['mobile_no'];
                }
                if (!empty($postdata['first_name']) && isset($postdata['first_name'])) {
                    if (!preg_match('/^[A-Za-z ]{3,50}$/', $postdata['first_name'])) {
                        $error[] = "Enter only charapostdatacter";
                    } else {
                        $personal_details['first_name'] = $postdata['first_name'];
                    }
                } else {
                    $error[] = "Enter first name";
                }
                
                if (!empty($postdata['last_name']) && isset($postdata['last_name'])) {
                    if (!preg_match('/^[A-Za-z ]{3,50}$/', $postdata['last_name'])) {
                        $error[] = "Enter only character";
                    } else {
                        $personal_details['last_name'] = $postdata['last_name'];
                    }
                } else {
                    $error[] = "Enter first name";
                }
                
                if (empty($postdata['gender']) && !isset($postdata['gender'])) {
                    $error[] = "select gender";
                } else {
                    $gender = $postdata['gender'];
                    if ($gender == 1) {
                        $postdata['gender'] = "Male";
                    } else {
                        $postdata['gender'] = "Female";
                    }
                }
                
                if (empty($postdata['dob']) && !isset($postdata['dob'])) {
                    $error[] = "Enter dob ";
                } else {
                    $postdata['dob']         = date("Y-m-d", strtotime($postdata['dob']));
                    $personal_details['dob'] = $postdata['dob'];
                }
                
                
                
                
                /********** profile summary array **********/
                
                
                
                
                if (isset($postdata['notice_period_days']) and !empty($postdata['notice_period_days'])) {
                    $NoticePeriod                             = $postdata['notice_period_days'];
                    $user_profile['notice_period_in_days']    = $NoticePeriod;
                    $userprofiledata['notice_period_in_days'] = $NoticePeriod;
                } else {
                    $NoticePeriod                             = "";
                    $user_profile['notice_period_in_days']    = $NoticePeriod;
                    $userprofiledata['notice_period_in_days'] = $NoticePeriod;
                    
                }
                
                
                if (isset($postdata['Immdiately_check']) and $postdata['Immdiately_check'] == "true") {
                    $is_immediate_joining                    = 1;
                    $user_profile['is_immediate_joining']    = $is_immediate_joining;
                    $userprofiledata['is_immediate_joining'] = $is_immediate_joining;
                }
                
                if (isset($postdata['Immdiately_check']) and $postdata['Immdiately_check'] == "false") {
                    $is_immediate_joining                    = 0;
                    $user_profile['is_immediate_joining']    = $is_immediate_joining;
                    $userprofiledata['is_immediate_joining'] = $is_immediate_joining;
                }
                
                
                
                
                if (isset($postdata['ChangeReason']) and !empty($postdata['ChangeReason'])) {
                    $ChangeReason = $this->input->post('ChangeReason');
                } else {
                    $ChangeReason                       = "";
                    $user_profile['is_noticeperiod']    = $ChangeReason;
                    $userprofiledata['is_noticeperiod'] = $ChangeReason;
                    
                }
                
                if (isset($postdata['reason_id']) and !empty($postdata['reason_id'])) {
                    
                    $reason_id                            = implode(",", json_decode(stripslashes($postdata['reason_id'])));
                    $user_profile['reason_for_change']    = $reason_id;
                    $userprofiledata['reason_for_change'] = $reason_id;
                    unset($postdata['reason_id']);
                    unset($postdata['reason']);
                } else {
                    $ChangeReason                         = "";
                    $user_profile['reason_for_change']    = $ChangeReason;
                    $userprofiledata['reason_for_change'] = $ChangeReason;
                    
                }
                
                
                
                
                
                if (isset($postdata['description']) and !empty($postdata['description'])) {
                    $description                           = $postdata['description'];
                    $user_profile['reason_description']    = $description;
                    $userprofiledata['reason_description'] = $description;
                    unset($postdata['description']);
                } else {
                    $description                           = "";
                    $user_profile['reason_description']    = $description;
                    $userprofiledata['reason_description'] = $description;
                    unset($postdata['description']);
                }
                
                if (isset($ChangeReason) and !empty($ChangeReason)) {
                    if (count($ChangeReason) > 0) {
                        $ChangeReason                       = implode(',', $ChangeReason);
                        $user_profile['is_noticeperiod']    = $ChangeReason;
                        $userprofiledata['is_noticeperiod'] = $ChangeReason;
                        
                    }
                }
                
                $Innoticeperiod = 0;
                
                if (isset($postdata['already_resigned_yes']) and $postdata['already_resigned_yes'] == "true") {
                    $Innoticeperiod = 1;
                }
                
                
                if (isset($postdata['already_resigned_no']) and $postdata['already_resigned_no'] == "true") {
                    
                    $Innoticeperiod = 0;
                }
                
                if (isset($postdata['currently_working_yes']) and $postdata['currently_working_yes'] == "true") {
                    $workingyes = 1;
                }
                
                
                if (isset($postdata['currently_working_no']) and $postdata['currently_working_no'] == "true") {
                    $workingyes = 0;
                }
                
                
                $currentdate = date('Y-m-d');
                
                if ($workingyes == 1) {
                    $user_profile['is_working']    = 1;
                    $userprofiledata['is_working'] = 1;
                    if ($Innoticeperiod == 1) {
                        
                        
                        if ($currentdate < $or_Dateofjoining) {
                            $from                                     = date_create($or_Dateofjoining);
                            $to                                       = date_create($currentdate);
                            $diff                                     = date_diff($to, $from);
                            $NoticePeriod                             = $diff->format('%a');
                            $user_profile['notice_period_in_days']    = $NoticePeriod;
                            $userprofiledata['notice_period_in_days'] = $NoticePeriod;
                        }
                    }
                } else {
                    $is_working                    = 0;
                    $user_profile['is_working']    = 0;
                    $userprofiledata['is_working'] = 0;
                    
                    
                    if ($currentdate < $or_Dateofjoining) {
                        $from                                     = date_create($or_Dateofjoining);
                        $to                                       = date_create($currentdate);
                        $diff                                     = date_diff($to, $from);
                        $NoticePeriod                             = $diff->format('%a');
                        $user_profile['notice_period_in_days']    = $NoticePeriod;
                        $userprofiledata['notice_period_in_days'] = $NoticePeriod;
                    }
                }
                
                
                if ($Innoticeperiod == 1) {
                    $is_noticeperiod                    = 1;
                    $user_profile['is_noticeperiod']    = 1;
                    $userprofiledata['is_noticeperiod'] = 1;
                    
                } else {
                    $user_profile['is_noticeperiod']    = 0;
                    $userprofiledata['is_noticeperiod'] = 0;
                }
                
                if (empty($postdata['emptype_ids']) && !isset($postdata['emptype_ids'])) {
                    $error[] = "Select employment type ";
                } else {
                    $emptype_ids                 = json_decode(stripslashes($postdata['emptype_ids']));
                    $userprofiledata['industry'] = $postdata['industry_ids'];
                    if (isset($postdata['industry_name'])) {
                        $userprofiledata['emp_type'] = $postdata['employee_type_name'];
                    }
                    unset($postdata['emptype_ids']);
                    unset($postdata['employee_type_name']);
                }
                
                if (empty($postdata['industry_ids']) && !isset($postdata['industry_ids'])) {
                    $error[] = "Select industry ";
                } else {
                    $postdata['industry_ids'] = implode(",", json_decode(stripslashes($postdata['industry_ids'])));
                    $user_profile['industry'] = $postdata['industry_ids'];
                    
                    $userprofiledata['industry'] = $postdata['industry_ids'];
                    
                    if (isset($postdata['industry_name'])) {
                        $userprofiledata['indus'] = $postdata['industry_name'];
                    }
                    unset($postdata['industry_ids']);
                }
                
                if (empty($postdata['function_category_id']) && !isset($postdata['function_category_id'])) {
                    $error[] = "Select function category";
                } else {
                    $user_profile['function']    = implode(",", json_decode(stripslashes($postdata['function_category_id'])));
                    $userprofiledata['function'] = implode(",", json_decode(stripslashes($postdata['function_category_id'])));
                    if (isset($postdata['function_category_name'])) {
                        $userprofiledata['fun'] = $postdata['function_category_name'];
                    }
                    
                    unset($postdata['function_category_id']);
                }
                if (empty($postdata['workexperience']) && !isset($postdata['workexperience'])) 
                    {
                    $error[] = "Enter work experince";
                    } 
                  else 
                    {
                    $workexperienceInYear  = $postdata['workexperienceInYear'];
                    $workexperienceInMonth = $postdata['workexperienceInMonth'];
                    if (strlen($workexperienceInMonth) > 1) {
                        $total_experience = $workexperienceInYear . "." . $workexperienceInMonth;
                    } 
                    else 
                    {
                        $total_experience = $workexperienceInYear . ".0" . $workexperienceInMonth;
                    }
                    $user_profile['total_experience']=$total_experience;
                    $userprofiledata['total_experience']=(double)$total_experience;
                    unset($postdata['workexperience']);
                  }
                
                if (empty($user_profile['open_to_relocate']) && !isset($user_profile['open_to_relocate'])) {
                    $user_profile['is_open_to_relocate'] = 1;
                } else {
                    $user_profile['is_open_to_relocate'] = 0;
                }
                
                if (empty($postdata['expected_ctc_key']) && !isset($postdata['expected_ctc_key'])) {
                    //  $error[]="Select expected CTC";  
                } else {
                    $user_profile['expected_ctc']    = $postdata['expected_ctc_key'];
                    $userprofiledata['expected_ctc'] = (double) $postdata['expected_ctc_key'];
                    unset($postdata['expected_ctc_key']);
                }
                
                
                if (empty($postdata['last_ctc_key']) && !isset($postdata['last_ctc_key'])) {
                    //$error[]="Enter current CTC";  
                } else {
                    $user_profile['current_ctc']    = $postdata['last_ctc_key'];
                    $userprofiledata['current_ctc'] =(double) $postdata['last_ctc_key'];
                    unset($postdata['last_ctc_key']);
                }
                
                if (empty($user_profile['expected_employment']) && !isset($user_profile['expected_employment'])) {
                    // /$error[]="Enter current CTC";  
                } else {
                    $postdata['expected_employment']     = implode(",", json_decode(stripslashes($postdata['expected_employment'])));
                    $user_profile['expected_employment'] = $postdata['expected_employment'];
                    $userprofiledata['emp_ids']          = $postdata['expected_employment'];
                    
                    unset($postdata['expected_employment']);
                }
                
                
                
                if (empty($postdata['current_city_id']) && !isset($postdata['current_city_id'])) {
                    $error[] = "Select current city";
                } else {
                    $user_profile['current_city_id']        = $postdata['current_city_id'];
                    $personal_details['current_city_id']    = $postdata['current_city_id'];
                    $personal_details['current_state_id']   = $postdata['current_state_id'];
                    $personal_details['current_country_id'] = $postdata['current_country_id'];
                    
                    
                    if (isset($postdata['current_city_name'])) {
                        $personal_details['current_city'] = $postdata['current_city_name'];
                    }
                    
                    if (isset($postdata['current_state_name'])) {
                        $personal_details['current_state'] = $postdata['current_state_name'];
                    }
                    
                    if (isset($postdata['current_country_name'])) {
                        $personal_details['current_state'] = $postdata['current_country_name'];
                    }
                    
                    
                    
                }
                
                
                /********** Finish profile summary **********/
                
                if (empty($postdata['current_country_id']) && !isset($postdata['current_country_id'])) {
                    $error[] = "Select  current country";
                }
                $skill_str = "";
                
                if (isset($emptype_ids)) {
                    $this->db->query("delete from user_employment where user_id=" . $postdata['user_id']);
                    $seqno = 1;
                    foreach ($emptype_ids as $key => $emp_id) {
                        $sarray                  = array();
                        $sarray['id']            = $seqno;
                        $sarray['updated_on']    = date("Y-m-d h:i:s");
                        $sarray['user_id']       = $postdata['user_id'];
                        $sarray["employment_id"] = $emp_id;
                        
                        $sarray['updated_by'] = $postdata['user_id'];
                        
                        $this->db->insert("user_employment", $sarray);
                        $seqno++;
                    }
                    
                }
                
                if (!empty($postdata['skill']) && isset($postdata['skill'])) {
                    $this->db->query("delete from user_skills where user_id=" . $postdata['user_id']);
                    $skill     = json_decode(stripslashes($postdata['skill']), true);
                    $skill_str = "";
                    $seq       = 1;
                    foreach ($skill['skill'] as $key => $s) {
                        $sarray                         = array();
                        $sarray['updated_on']           = date("Y-m-d h:i:s");
                        $sarray['user_id']              = $postdata['user_id'];
                        $sarray['id']                   = $seq;
                        $seq                            = $seq + 1;
                        $sarray["last_used"]            = "1995";
                        $sarray["skill_name"]           = trim($s['skill_name']);
                        $skill_str                      = trim($s['skill_name']);
                        $sarray["level_of_proficiency"] = $s['skill_level'];
                        $sarray["experince_in_month"]   = $s['experience_in_years'];
                        $this->db->insert("user_skills", $sarray);
                    }
                    $userprofiledata1               = array();
                    $userprofiledata1['skill_name'] = trim($skill_str, ",");
                    $this->updateMongo($userprofiledata, $flag = null, $postdata['user_id']);
                    unset($postdata['skill']);
                }
                
                if (count($user_profile) > 0) 
                  {
                    $this->db->select("*");
                    $this->db->from('user_profile_summary');
                    $this->db->where('user_id', $postdata['user_id']);
                    $query = $this->db->get();
                    if ($query->num_rows() > 0) {
                        $this->db->where('user_id', $postdata['user_id']);
                        $this->db->update('user_profile_summary', $user_profile);
                        
                    } else {
                        $user_profile['user_id']    = $postdata['user_id'];
                        $user_profile['created_on'] = date("Y-m-d h:i:s");
                        $user_profile['created_by'] = $postdata['user_id'];
                        $query                      = $this->db->insert('user_profile_summary', $user_profile);
                        if ($query) {
                            $userprofiledata['skill_name'] = trim($skill_str, ",");
                            $this->updateMongo($userprofiledata, $flag = null, $postdata['user_id']);
                        }
                        
                        
                    }
                }
                
                
                if (count($error) > 0) {
                    $dataArray['status']  = false;
                    $dataArray['message'] = $error[0];
                } else {
                    $postdata['email_id'] = $postdata['email'];
                    unset($postdata['email']);
                    unset($postdata['highest_qualification_id']);
                    unset($postdata['last_ctc_key']);
                    unset($postdata['expected_ctc_key']);
                    unset($postdata['role_ids']);
                    
                    unset($postdata['Immdiately_check']);
                    unset($postdata['already_resigned_no']);
                    unset($postdata['already_resigned_yes']);
                    unset($postdata['notice_period_days']);
                    unset($postdata['num_of_days_to_join']);
                    unset($postdata['deacription']);
                    unset($postdata['currently_working_no']);
                    unset($postdata['employer_join_date']);
                    unset($postdata['currently_working_yes']);
                    
                    if ($this->u->saveProfile($postdata)) {
                        $this->updateMongo($personal_details, $flag = null, $postdata['user_id']);
                        $dataArray['status']  = true;
                        $dataArray['message'] = "Profile saved successfully";
                        //$dataArray['query']=$this->db->last_query();
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = SOMETHING_WRONG;
                    }
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        echo json_encode($dataArray);
    }
    
    
    
    public function getAllUserDetails()
    {
        $dataArray = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                $userinfo = $this->u->getUserDetails($postdata['user_id'], PROFILE_IMAGES);
                
                if ($userinfo != 0) {
                    $result = $this->u->fetchUserLanguages($postdata['user_id']);
                    if (count($result) > 0) {
                        $userinfo['userLanguage'] = $result;
                        
                    } else {
                        $userinfo['userLanguage'] = array();
                    }
                    
                    $result = $this->u->fetchSkills($postdata['user_id']);
                    if (count($result) > 0) {
                        $userinfo['userskill'] = $result;
                        
                    } else {
                        $userinfo['userskill'] = array();
                        
                    }
                    
                    $result = $this->u->fetchWorkExperience($postdata['user_id']);
                    if (count($result) > 0) {
                        $userinfo['userworkexperience'] = $result;
                        
                        foreach ($result as $work) {
                            if ($work['is_current_company'] == 1) {
                                $userinfo['profile_summarey']["current_company"] = $work['company_name'];
                                $userinfo['profile_summarey']["designation"]     = $work['designation'];
                                break;
                            }
                        }
                    } else {
                        $userinfo['userworkexperience']                  = array();
                        $userinfo['profile_summarey']["current_company"] = "";
                        $userinfo['profile_summarey']["designation"]     = "";
                    }
                    
                    $result = $this->u->fetchEducation($postdata['user_id']);
                    if (count($result) > 0) {
                        $userinfo['userEducations'] = $result;
                        
                    } else {
                        $userinfo['userEducations'] = array();
                        
                    }
                    
                    $result = $this->u->fetchAdditionalInfo($postdata['user_id']);
                    if (count($result) > 0) {
                        $userinfo['useraddtionalinfo'] = $result;
                    }
                    $dataArray['status']   = true;
                    $dataArray['userdata'] = $userinfo;
                    $dataArray['message']  = "User details are send";
                } else {
                    $dataArray['status']  = true;
                    $dataArray['message'] = "No such user exist";
                    
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
                
            }
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
        }
        
        echo json_encode($dataArray);
    }
    
    
    
    public function saveUserLanguage()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if ($this->checkUserIdExists($postdata['user_id'])) {
                    
                    $this->db->where(array(
                        "user_id" => $postdata['user_id']
                    ));
                    $this->db->delete("user_languages");
                    
                    $Lang_ids_arr  = $postdata["lang_id"];
                    $can_read_arr  = $postdata["readarr"];
                    $can_speak_arr = $postdata["speakarr"];
                    $can_write_arr = $postdata["writearr"];
                    $levelarr      = $postdata['pro_level'];
                    
                    for ($i = 0; $i < count($Lang_ids_arr); $i++) {
                        $tempArr[] = array(
                            "user_id" => $postdata["user_id"],
                            "language_id" => $Lang_ids_arr[$i],
                            "can_read" => $can_read_arr[$i],
                            "can_write" => $can_write_arr[$i],
                            "can_speak" => $can_speak_arr[$i],
                            "level_of_proficiency" => $levelarr[$i],
                            "created_by" => $postdata["user_id"],
                            "created_on" => date('Y-m-d')
                        );
                        
                    }
                    
                    if (count($tempArr) > 0) {
                        $this->db->insert_batch("user_languages", $tempArr);
                        if ($this->db->affected_rows()) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Language details saved successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "User id not exist";
                    
                }
                
            } else {
                
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
        
    }
    
    public function saveUserEducation()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if ($this->checkUserIdExists($postdata['user_id'])) {
                    
                    $this->db->where(array(
                        "user_id" => $postdata['user_id']
                    ));
                    $this->db->delete("user_qualifications");
                    $tempArr        = array();
                    $qua_ids        = $postdata['q_id'];
                    $spe_ids        = $postdata['s_id'];
                    $university     = $postdata['university_name'];
                    $institute_name = $postdata['institute_name'];
                    $pyear          = $postdata['passing_year'];
                    $pgrade         = $postdata['passing_grade'];
                    $achivement     = $postdata['achievement'];
                    
                    
                    for ($i = 0; $i < count($qua_ids); $i++) {
                        $tempArr[] = array(
                            "user_id" => $postdata['user_id'],
                            "qualification_level_id" => $qua_ids[$i],
                            "specialization_type_id" => $spe_ids[$i],
                            "university_name" => trim($university[$i]),
                            "institute_name" => trim($institute_name[$i]),
                            "passing_year" => $pyear[$i],
                            "passing_grade" => trim($pgrade[$i]),
                            "achievement" => trim($achivement[$i])
                            
                        );
                        
                    }
                    
                    
                    
                    
                    if (count($tempArr) > 0) {
                        $this->db->insert_batch("user_qualifications", $tempArr);
                        if ($this->db->affected_rows()) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Education  details saved successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                    }
                } else {
                    
                    $dataArray['status']  = false;
                    $dataArray['message'] = "User id not exist";
                }
                
                
                
                
            } else {
                
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
        
    }
    
    
    public function saveUserWorkExperience()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if ($this->checkUserIdExists($postdata['user_id'])) {
                    
                    $this->db->where(array(
                        "user_id" => $postdata["user_id"]
                    ));
                    $this->db->delete("user_work_experiences");
                    $cnamearr            = $postdata['company_name'];
                    $designationarr      = $postdata['designation'];
                    $ctcarr              = $postdata['ctc'];
                    $jobtypearr          = $postdata['job_type'];
                    $achievementarr      = $postdata['achievement'];
                    $nature_of_dutiesarr = $postdata['duties'];
                    $joining_date_arr    = $postdata['date_of_joining'];
                    $leaving_date_arr    = $postdata['date_of_leaving'];
                    $is_current_company  = $postdata['is_current_company'];
                    
                    for ($i = 0; $i < count($cnamearr); $i++) {
                        
                        $tempArr[] = array(
                            "user_id" => $postdata['user_id'],
                            "company_name" => trim($cnamearr[$i]),
                            "designation" => trim($designationarr[$i]),
                            "ctc" => trim($ctcarr[$i]),
                            "job_type" => $jobtypearr[$i],
                            "date_of_joining" => $joining_date_arr[$i],
                            "date_of_leaving" => $is_current_company[$i] == 0 ? $leaving_date_arr[$i] : "",
                            "duties" => isset($nature_of_dutiesarr[$i]) ? trim($nature_of_dutiesarr[$i]) : "",
                            "achievement" => isset($achievementarr[$i]) ? trim($achievementarr[$i]) : "",
                            "is_current_company" => $is_current_company[$i],
                            "exp_in_months" => 0
                            
                        );
                        
                        
                    }
                    
                    
                    if (count($tempArr) > 0) {
                        $this->db->insert_batch("user_work_experiences", $tempArr);
                        if ($this->db->affected_rows()) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "WorkExperience details saved successfully";
                            
                            
                        } else {
                            
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                            
                        }
                    }
                    
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "User id not exist";
                    
                }
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
                
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
        }
        
        
        
    }
    
    public function saveUserReferences()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if ($this->checkUserIdExists($postdata['user_id'])) {
                    $this->db->where(array(
                        "user_id" => $postdata['user_id']
                    ));
                    $this->db->delete("user_references");
                    
                    $tempArr                 = array();
                    $refunction_job_category = $postdata['refunction_job_category'];
                    $ref_email               = $postdata['ref_email'];
                    $ref_conatct             = $postdata['ref_contact'];
                    $ref_position            = $postdata['ref_position'];
                    for ($i = 0; $i < count($refunction_job_category); $i++) {
                        $tempArr[] = array(
                            "user_id" => $postdata['user_id'],
                            "name" => $refunction_job_category[$i],
                            "email" => $ref_email[$i],
                            "contact" => $ref_conatct[$i],
                            "position" => $ref_position[$i]
                        );
                        
                    }
                    
                    
                    if (count($tempArr) > 0) {
                        $this->db->insert_batch("user_references", $tempArr);
                        if ($this->db->affected_rows()) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "references details saved successfully";
                            
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                    }
                    
                    
                    
                } else {
                    
                    $dataArray['status']  = false;
                    $dataArray['message'] = "User id not exist";
                    
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
                
            }
            
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
        }
        
        echo json_encode($dataArray);
        
    }
    
    
    
    
    
    
    
    
    
    
    public function saveUserSkills()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if ($this->checkUserIdExists($postdata['user_id'])) {
                    $this->db->where(array(
                        "user_id" => $postdata['user_id']
                    ));
                    $this->db->delete("user_skills");
                    $tempArr    = array();
                    $skillsname = $postdata['skillocation_name'];
                    $experience = $postdata['experience'];
                    $level      = $postdata['pro_level'];
                    for ($i = 0; $i < count($skillsname); $i++) {
                        $tempArr[] = array(
                            "user_id" => $postdata['user_id'],
                            "skillocation_name" => $skillsname[$i],
                            "experince_in_month" => $experience[$i] * 12,
                            'level_of_proficiency' => $level[$i]
                        );
                        
                    }
                    if (count($tempArr) > 0) {
                        $this->db->insert_batch("user_skills", $tempArr);
                        if ($this->db->affected_rows()) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Skills   details saved successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                    }
                    
                    
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "User id not exist";
                    
                }
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
                
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
        
    }
    
    
    public function saveUserTestimonials()
    {
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if ($this->checkUserIdExists($postdata['user_id'])) {
                    
                    $this->db->where(array(
                        "user_id" => $postdata['user_id']
                    ));
                    $this->db->delete("user_testimonial");
                    $tempArr      = array();
                    $testimonials = $user_testimonial_arr['testimonials'];
                    $author       = $user_testimonial_arr['author'];
                    for ($i = 0; $i < count($testimonials); $i++) {
                        $tempArr[] = array(
                            "user_id" => $postdata['user_id'],
                            "testimonial" => $testimonials[$i],
                            "author" => $author[$i]
                        );
                        
                        
                    }
                    
                    
                    if (count($tempArr) > 0) {
                        $this->db->insert_batch("user_testimonial", $tempArr);
                        if ($this->db->affected_rows()) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Testimonials details saved successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                    }
                    
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "User id not exist";
                    
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
                
            }
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        
    }
    
    
    public function saveUserAdditionalInfo()
    {
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if ($this->checkUserIdExists($postdata['user_id'])) {
                    
                    
                    $dataArr = array(
                        "user_id" => $postdata['user_id'],
                        "groups_association" => implode("|", $additionInfoArr['gassociation']),
                        "online_publication" => implode("|", $additionInfoArr['opublication']),
                        "hobbies_interest" => $additionInfoArr['hobbies'],
                        "online_profile" => isset($additionInfoArr['o_profile']) ? $additionInfoArr['o_profile'] : "",
                        "facebook_profile" => isset($additionInfoArr['f_profile']) ? $additionInfoArr['f_profile'] : "",
                        "linkedin_profile" => isset($additionInfoArr['l_profile']) ? $additionInfoArr['l_profile'] : "",
                        "twitter_profile" => isset($additionInfoArr['t_profile']) ? $additionInfoArr['t_profile'] : "",
                        "created_by" => $postdata['user_id'],
                        "created_on" => date('Y-m-d')
                        
                        
                    );
                    if ($this->db->query("select * from user_additional_info where user_id=" . $this->session->userdata("id"))->result_array()) {
                        unset($dataArr['user_id']);
                        $this->db->where(array(
                            "user_id" => $postdata['user_id']
                        ));
                        $this->db->update("user_additional_info", $dataArr);
                        
                        if ($this->db->affected_rows()) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Additional information details saved successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                        
                    } else {
                        $this->db->insert("user_additional_info", $dataArr);
                        
                        if ($this->db->affected_rows()) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Additional information details saved successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                        
                    }
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "User id not exist";
                    
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
                
            }
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        
    }
    
    
    function profileImageUpload()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = $_POST;
            // $postdata = json_decode( file_get_contents('php://input'), true ); 
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                $this->load->library('mongo_db');
                $mongo  = $this->mongo_db->db->candidate;
                $result = $mongo->find(array(
                    "id" => (int) $postdata['user_id']
                ));
                foreach ($result as $id => $post) {
                    foreach ($post as $key => $val) {
                        if ($key == "id") {
                            $this->userAllDetails[$key] = intval($val);
                        } else {
                            $this->userAllDetails[$key] = $val;
                        }
                    }
                }
                
                $filedata = $_FILES;
                $this->load->helper('fileuploader');
                if (!empty($filedata['file']['name'])) {
                    $Config = array(
                        'allowed_types' => array(
                            'png',
                            'jpg',
                            'jpeg'
                        ),
                        'upload_path' => "./" . PROFILE_IMAGES,
                        'input_field_name' => 'file'
                    );
                    
                    $UploadResult = general_upload_file($Config, false);
                    if (!$UploadResult['status']) {
                        $dataArray['message'] = $UploadResult['error'];
                    } else {
                        $dataArray['status'] = true;
                        if ($this->u->updateProfilePic($postdata['user_id'], $UploadResult['file_name'], PROFILE_IMAGES)) {
                            $userprofiledata['profile_pic'] = $UploadResult['file_name'];
                            $this->updateMongo($userprofiledata, $flag = null, $postdata['user_id']);
                            
                            $dataArray['status']             = true;
                            $dataArray['message']            = "Profile image upload sucessfully";
                            $dataArray['profile_image_path'] = base_url() . PROFILE_IMAGES . "/" . $UploadResult['file_name'];
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong ";
                        }
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "File not selected";
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        echo json_encode($dataArray);
    }
    
    
    public function getCandidateDashboardDetails()
    {
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                $userid                         = $postdata['user_id'];
                $data['all_table_record_count'] = $this->createprofileinsert->get_user_table_count($userid);
                $percentage                     = 0;
                
                /********************* Calculation of percentage of all tab  **********************************/
                
                $percentage = $this->get_details->get_userdetails_percent($userid);
                if ($percentage > 100) {
                    $percentage = 100;
                }
                $data_array                       = array();
                /*********************    get all count of candidate dashboard  **********************************/
                $get_all_savedjobs_arr            = $this->Jobs->getMyAllSavedJobsDetails($userid);
                $data_array['saved_job_count']    = count($get_all_savedjobs_arr);
                $data_array['saved_search_count'] = $this->get_details->get_all_savedsearches($userid);
                $data_array['alert_count']        = $this->get_details->get_all_jobalerts($userid);
                $data_array['profile_percentage'] = $percentage;
                $jdetails_arr                     = $this->Jobs->getMyAllAppliedJobDetails($userid);
                $data_array['applied_job_count']  = $jdetails_arr;
                
                $query      = "select organizations.id,organization_name,details,industry_type,founded_year
          ,website_url,number_of_employee,company_logo,user_follow_company.is_deleted
          from user_follow_company
          left join  organizations
          on  organizations.id=user_follow_company.organization_id

          where  user_follow_company.user_id=" . $userid . " and user_follow_company.is_deleted=0";
                $follow_arr = $this->db->query($query)->result_array();
                
                if (count($follow_arr) > 0) {
                    $data_array['user_follow_compnay_count'] = count($follow_arr);
                } else {
                    $data_array['user_follow_compnay_count'] = 0;
                }
                
                $data_array['recommanded_job_count'] = 0;
                
                $dataArray['status']             = true;
                $dataArray['candiate_dashboard'] = $data_array;
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        echo json_encode($dataArray);
    }
    
    
    public function getRecommendedJobList()
    {
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $industry_ids      = array();
        $function_ids      = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            $offset   = 0;
            if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                $offset = $postdata['offset'];
            }
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                $userid                   = $postdata['user_id'];
                $data['Candidatedetails'] = $this->getallselectresults->getCandidateDetails($userid);
                $locationname             = "";
                
                foreach ($data['Candidatedetails']['Profilesummary'] as $name) {
                    $is_fresher = $name->is_fresher;
                    if ($is_fresher != 1) {
                        $experience      = $name->total_experience;
                        $experience      = explode('.', $experience);
                        $experience_from = 0;
                        if (isset($experience[0])) {
                            $experience_from = $experience[0] * 12;
                        }
                        if (isset($experience[1])) {
                            $experience = $experience_from + $experience[1];
                        } else {
                            $experience = $experience_from;
                        }
                        
                    } else {
                        $experience = 0;
                    }
                }
                
                if (!isset($experience)) {
                    $experience = "";
                }
                foreach ($data['Candidatedetails']['user_preferred_location'] as $name) {
                    $city_id = $name->city_id;
                    $locationname .= $this->Locations->getNameBySingleID($city_id) . ",";
                }
                
                foreach ($data['Candidatedetails']['personaldetails'] as $name) {
                    $city_id = $name->current_city_id;
                    $locationname .= $this->Locations->getNameBySingleID($city_id) . ",";
                }
                $locationname = trim($locationname, ",");
                
                $skill_name = "";
                foreach ($data['Candidatedetails']['user_skills'] as $name) {
                    $skill_name .= $name->skill_name . ",";
                }
                $skill_name    = rtrim($skill_name, ",");
                $city_id       = 0;
                $city_name     = " ";
                $location_name = $locationname;
                $keyword       = $skill_name;
                
                $locations = array();
                if (!empty($location_name)) {
                    $locations = explode(",", $locationname);
                }
                
                $result = $this->u->getRecommented_jobs($keyword, $locations, $experience);
                
                if (is_array($result) && count($result) > 0) {
                    $ls               = "";
                    $inds             = "";
                    $organization_ids = array();
                    foreach ($result as $key => $job) {
                        $search_job_ids[]      = $job['j_id'];
                        $j_id                  = $job['j_id'];
                        $member_id             = $job['member_id'];
                        $temp                  = explode(',', $job['jcity_id']);
                        $temp_jstate_id        = explode(',', $job['jstate_id']);
                        $member_id             = $job['member_id'];
                        $recruiter_type        = $this->home_model->recruiter_type_of_organization_member($member_id);
                        $job['recruiter_type'] = $recruiter_type;
                        $organization_id       = $job['organization_id'];
                        $org_logo              = $this->home_model->logo_of_organization($organization_id);
                        $job['org_logo']       = $org_logo;
                        $location_name         = $this->Locations->getLocationNameById($temp, $temp_jstate_id);
                        $job['location_name']  = implode(',', $location_name);
                        $locationids           = array_merge($locationids, $temp);
                        $industry_ids[]        = $job['industry_id'];
                        if (!in_array($job['organization_id'], array_keys($organization_ids))) {
                            $organization_ids[$job['organization_id']]['name']  = $job['organization_name'];
                            $organization_ids[$job['organization_id']]['count'] = 1;
                        } else {
                            $organization_ids[$job['organization_id']]['count'] = $organization_ids[$job['organization_id']]['count'] + 1;
                        }
                        
                        $query               = " select name from employment_types,jobs,job_employment where jobs.id=job_employment.job_id and employment_types.id=job_employment.employment_id and jobs.id=" . $j_id . " GROUP by employment_types.id";
                        $re                  = $this->db->query($query);
                        $this->query_execute = $this->db->last_query();
                        $temp                = array();
                        foreach ($re->result_array() as $e_type) {
                            $temp[] = $e_type['name'];
                        }
                        
                        $job['emp_types']    = implode("/", $temp);
                        $query               = "  select function_job_category as  name 
                    from function_job_category ,jobs,job_function 
                    where jobs.id=job_function.job_id and 
                    function_job_category .function_id=job_function.function_id and 
                    jobs.id=" . $j_id;
                        $re                  = $this->db->query($query);
                        $this->query_execute = $this->db->last_query();
                        $temp                = array();
                        foreach ($re->result_array() as $e_type) {
                            $temp[] = $e_type['name'];
                        }
                        
                        $job['function_name'] = implode("/", $temp);
                        $query                = " select industry_name from industry_master,jobs,job_industry where jobs.id=job_industry.job_id and industry_master.industry_id=job_industry.industry_id and jobs.id=" . $j_id;
                        $re                   = $this->db->query($query);
                        $this->query_execute  = $this->db->last_query();
                        $temp                 = array();
                        foreach ($re->result_array() as $e_type) {
                            $temp[] = $e_type['industry_name'];
                        }
                        
                        $job['industry_name'] = implode("/", $temp);
                        $dataArray_job        = $this->Jobs->get_qualification_job_name($j_id);
                        $job['education']     = $dataArray_job;
                        $result[$key]         = $job;
                    }
                    
                    if (count($organization_ids) > 0) {
                        $dataArray['filteroption']['org_list'] = $organization_ids;
                    }
                    
                    if (isset($search_job_ids) and count($search_job_ids) > 0) {
                        $dataArray['filteroption']['functionlist'] = $this->u->getFunctionIdsForJobs_web($search_job_ids);
                        $data['search_job_ids']                    = implode(",", $search_job_ids);
                        $dataArray['filteroption']['emptypelist']  = $this->u->getEmpTypeFormtedArray($search_job_ids);
                        $data['org_list']                          = $organization_ids;
                        $locationids                               = array_count_values($locationids);
                        $keys                                      = array_keys($locationids);
                        foreach ($keys as $key) {
                            $location_name = $this->Locations->getNameBySingleID($key);
                            if ($location_name != "N/A") {
                                $formated_location[] = array(
                                    "count" => $locationids[$key],
                                    "location_id" => $key,
                                    "l_name" => $location_name
                                );
                            } else {
                            }
                        }
                        
                        $dataArray['filteroption']['locationlist'] = $formated_location;
                        $ind_new_arr                               = array();
                        foreach ($industry_ids as $key) {
                            $key_v = explode(',', $key);
                            if (count($key_v) > 1) {
                                foreach ($key_v as $new_key) {
                                    $ind_new_arr[] = $new_key;
                                }
                            } else {
                                if (!empty($key)) {
                                    $ind_new_arr[] = $key;
                                }
                            }
                        }
                        
                        $industry_ids = array_count_values($ind_new_arr);
                        $keys         = array_keys($industry_ids);
                        foreach ($keys as $key) {
                            $indus_id = explode(',', $key);
                            if (count($indus_id) > 0) {
                                $industry_name       = $this->Industry->getIndustryNameById($indus_id);
                                $formated_industry[] = array(
                                    "count" => $industry_ids[$key],
                                    "industry_id" => $key,
                                    "i_name" => $industry_name[0]
                                );
                            }
                        }
                        
                        $dataArray['filteroption']['industrylist'] = $formated_industry;
                        if (count($function_ids) > 0) {
                            $function_ids = array_count_values($function_ids);
                            $keys         = array_keys($function_ids);
                            foreach ($keys as $key) {
                                $function_name       = $this->f->getFunctionNameById($key);
                                $formated_function[] = array(
                                    "function_id" => $key,
                                    "count" => $function_ids[$key],
                                    "function_job_category" => $function_name
                                );
                            }
                            
                            $dataArray['filteroption']['functionlist'] = $formated_function;
                        }
                        
                        $dataArray['filteroption']['cur_dis_jobids'] = $search_job_ids;
                        $dataArray['filteroption']['exprange']       = $this->home_model->experience_years();
                        $dataArray['filteroption']['salaryrange']    = $this->home_model->salary_master();
                    }
                    $offset                    = 0;
                    $dataArray['total_record'] = count($result);
                    $result                    = array_slice($result, $offset, $this->per_page);
                    if (count($result) > 0) {
                        if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                            $applied_job_ids = $this->Jobs->getMyAppliedJobIds($postdata['user_id']);
                            foreach ($result as $key => $job) {
                                $j_id                 = $job['j_id'];
                                $job["description"]   = html_entity_decode(strip_tags($job["description"]));
                                $job["about_company"] = html_entity_decode(strip_tags($job["about_company"]));
                                $job['sharedatalink'] = base_url() . "search/getJobDetailsById/" . $job['unique_id'];
                                if (strlen($job['company_logo']) > 0) {
                                    $url = FCPATH . comany_logo_images . $job['company_logo'];
                                    if (file_exists($url)) {
                                        $job['company_logo'] = base_url() . comany_logo_images . $job['company_logo'];
                                    } else {
                                        $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                                    }
                                } else {
                                    $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                                }
                                
                                if (in_array($j_id, $applied_job_ids)) {
                                    $job['apply_status'] = "Applied";
                                } else {
                                    $job['apply_status'] = "Apply";
                                }
                                
                                $result[$key] = $job;
                            }
                        }
                        
                        
                        $dataArray['status'] = true;
                        
                        
                        
                        
                        $dataArray['joblist'] = $result;
                        
                        
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "No any recommended job";
                        
                    }
                    
                    
                    // $dataArray['filteroption']=$filteroption;
                    
                    
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No any recommended job";
                    
                }
                
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
                
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
            
        }
        echo json_encode($dataArray);
    }
    
    public function getCompanyIFollowsList()
    {
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                $query  = "select organizations.id,organization_name,details,industry_type,founded_year
                           ,website_url,number_of_employee,company_logo,user_follow_company.is_deleted
                    from user_follow_company
                     left join  organizations
                    on  organizations.id=user_follow_company.organization_id
              
                    where  user_follow_company.user_id=" . $postdata['user_id'] . " and user_follow_company.is_deleted=0";
                $result = $this->db->query($query)->result_array();
                
                if (count($result) > 0) {
                    foreach ($result as $key => $org) {
                        $org['active_job_account']  = $this->u->getActiveJobCount($org['id']);
                        $org['toal_follower_count'] = $this->u->getTotalFollower($org['id']);
                        $org['company_logo_path']   = base_url() . comany_logo_images . $org['company_logo'];
                        $industry_name              = $this->u->get_service_companyindustry($org['id']);
                        if (count($industry_name) > 0) {
                            $ind_name = "";
                            foreach ($industry_name as $key => $value) {
                                $ind_name .= $value['ind_name'] . ",";
                            }
                            $ind_name = trim($ind_name, ',');
                        } else {
                            $ind_name = "";
                        }
                        $org['industry_name'] = $ind_name;
                        $result[$key]         = $org;
                    }
                    $dataArray['status']      = true;
                    $dataArray['message']     = "Successful";
                    $dataArray['companylist'] = $result;
                    
                    // $dataArray['companylist']=$query;
                    
                } else {
                    $dataArray['status']  = true;
                    $dataArray['message'] = "You till not  follow any company";
                    
                    
                }
                
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provided";
            }
            
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
        }
        
        
        echo json_encode($dataArray);
        
    }
    
    
    
    public function followUnfollowCompnay()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if (isset($postdata['organization_id']) && !empty($postdata['organization_id'])) {
                    if (isset($postdata['unfollow_status']) && $postdata['unfollow_status'] == true) {
                        $data               = array();
                        $data['is_deleted'] = 1;
                        $data['deleted_on'] = date("Y-m-d H:i:s");
                        $data['deleted_by'] = $postdata['user_id'];
                        $data['updated_by'] = $postdata['user_id'];
                        
                        $this->db->where(array(
                            "user_id" => $postdata['user_id'],
                            "organization_id" => $postdata['organization_id']
                        ));
                        
                        if ($this->db->update("user_follow_company", $data)) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = " You successfully unfollowed this company";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = SOMETHING_WRONG;
                            
                        }
                        
                        
                    } else {
                        $data = array();
                        unset($postdata['unfollow_status']);
                        $query  = "select * from  user_follow_company where user_id=" . $postdata['user_id'] . " and organization_id=" . $postdata['organization_id'];
                        $result = $this->db->query($query)->row_array();
                        
                        
                        if ($result == false) {
                            $postdata['is_deleted'] = 0;
                            $postdata['updated_by'] = $postdata['user_id'];
                            $postdata['created_by'] = $postdata['user_id'];
                            $postdata['created_on'] = date("Y-m-d H:i:s");
                            
                            
                            if ($this->db->insert("user_follow_company", $postdata)) {
                                $dataArray['status']  = true;
                                $dataArray['message'] = " You successfully followed this company";
                            } else {
                                $dataArray['status']  = false;
                                $dataArray['message'] = SOMETHING_WRONG;
                            }
                            
                            
                        } else {
                            
                            
                            $data['is_deleted'] = 0;
                            $data['updated_by'] = $postdata['user_id'];
                            
                            $this->db->where(array(
                                "user_id" => $postdata['user_id'],
                                "organization_id" => $postdata['organization_id']
                            ));
                            
                            if ($this->db->update("user_follow_company", $data)) {
                                $dataArray['status']  = true;
                                $dataArray['message'] = " You successfully followed this company";
                            } else {
                                $dataArray['status']  = false;
                                $dataArray['message'] = SOMETHING_WRONG;
                                
                            }
                            
                        }
                        
                        
                    }
                    
                    
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "Organization id not provide";
                    
                    
                }
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "User id not provied";
                
                
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
            
        }
        
        
        echo json_encode($dataArray);
    }
    
    
    public function getCompanyDetails()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            
            if (isset($postdata['organization_id']) && !empty($postdata['organization_id'])) {
                $query = "SELECT
                    organizations.id,
                    organizations.unique_id AS u_id,
                    organization_name,
                    details,
                    facebook_url,
                    linkedin_url,
                    twitter_handle,
                    industry_type,
                    founded_year,
                    website_url,
                    number_of_employee,
                    company_logo
                    FROM
                    organizations
                    WHERE
                    organizations.id = " . $postdata['organization_id'];
                
                $result = $this->db->query($query)->row_array();
                // $this->db->insert("query",array("query"=>$query));
                
                if ($this->db->affected_rows()) {
                    
                    $result['details'] = strip_tags($result['details']);
                    if (substr($result["linkedin_url"], 0, 5) != "https") {
                        $result["linkedin_url"] = "https://" . $result["linkedin_url"];
                    }
                    
                    if (substr($result["twitter_handle"], 0, 5) != "https") {
                        $result["twitter_handle"] = "https://" . $result["twitter_handle"];
                    }
                    
                    if (substr($result["facebook_url"], 0, 5) != "https") {
                        $result["facebook_url"] = $result["facebook_url"];
                    }
                    if (isset($result['number_of_employee'])) {
                        
                        
                        $get_no_of_employee = $this->u->getNo_of_employee_org($result['number_of_employee']);
                        if (isset($get_no_of_employee[0]['no_of_employee'])) {
                            $result['number_of_employee'] = $get_no_of_employee[0]['no_of_employee'];
                        }
                    }
                    $industry_name = $this->u->get_service_companyindustry($postdata['organization_id']);
                    if (count($industry_name) > 0) {
                        $ind_name = "";
                        foreach ($industry_name as $key => $value) {
                            $ind_name .= $value['ind_name'] . ",";
                        }
                        $ind_name = trim($ind_name, ',');
                    } else {
                        $ind_name = "";
                    }
                    $result['industry_name'] = $ind_name;
                    
                    
                    if (isset($result["company_logo"]) && !empty($result["company_logo"])) {
                        $result["company_logo_path"] = base_url() . comany_logo_images . $result["company_logo"];
                    } else {
                        
                        $result["company_logo_path"] = "";
                    }
                    
                    
                    $result["sharelink"] = base_url() . 'search/getCompanyDetailsById/' . $result['u_id'];
                    if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                        
                        $query   = "select * from  user_follow_company where user_id=" . $postdata['user_id'] . " and organization_id=" . $postdata['organization_id'];
                        $result1 = $this->db->query($query)->row_array();
                        
                        if ($result1 == false) {
                            $result['is_deleted'] = 1;
                        } else {
                            $result['is_deleted'] = $result1['is_deleted'];
                        }
                    }
                    
                    $dataArray['status']         = true;
                    $dataArray['message']        = "success";
                    $dataArray['companydetails'] = $result;
                    
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = SOMETHING_WRONG;
                    
                    
                }
                
                
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Organization id not provide";
                
            }
            
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
            
        }
        
        echo json_encode($dataArray);
    }
    
    
    
    
    
    
    function checkUserIdExists($user_id)
    {
        $this->db->where(array(
            "id" => $user_id
        ));
        $result = $this->db->get("users")->row_array();
        if (count($result) > 0) {
            
            return 1;
        } else {
            return 0;
        }
        
    }
    
    function jobOfferlist($orderby = "posted_on", $sort = "desc")
    {
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            
            $postdata = json_decode(file_get_contents("php://input"), true);
            
            $offset = 0;
            if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                $offset = $postdata['offset'];
            }
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                
                $offerresult = $this->db->query("select * from recruiter_job_offer where user_id=" . $postdata['user_id']);
                
                if (!is_bool($offerresult) && $offerresult->num_rows() > 0) {
                    
                    $jobids         = array();
                    $referencearray = array();
                    foreach ($offerresult->result_array() as $offer) {
                        $jobids[]                                              = $offer['job_id'];
                        $referencearray[$offer['job_id']]['is_offer_accepted'] = $offer['is_offer_accepted'];
                        $referencearray[$offer['job_id']]['message']           = $offer['message'];
                    }
                    
                    $where_query = "";
                    
                    if (count($jobids) > 0) {
                        
                        $where_query = "recruiter_job_offer.user_id=" . $postdata['user_id'] . " AND jobs.id in (" . implode(",", $jobids) . ")  ";
                        $result      = $this->u->joboffer($where_query, $orderby, $sort);
                        
                        if ($result->num_rows() > 0) {
                            
                            $dataArray['total_record'] = $result->num_rows();
                            $offerlist                 = array_slice($result->result_array(), $offset, $this->per_page);
                            
                            foreach ($offerlist as $key => $offer) {
                                
                                $offerlist[$key]['is_offer_accepted'] = $referencearray[$offer['id']]['is_offer_accepted'];
                                $offerlist[$key]['message']           = $referencearray[$offer['id']]['message'];
                                
                                if (isset($offerlist[$key]['industry_id']) && !empty($offerlist[$key]['industry_id'])) {
                                    
                                    $offerlist[$key]['industry_name'] = implode(",", $this->industry->getIndustryNameById(explode(",", $offerlist[$key]['industry_id'])));
                                }
                                
                                $offerlist[$key]['function_job_category'] = $this->fjc->getFunctionNameById($offerlist[$key]['function_category_id']);
                                $offerlist[$key]['sharedatalink']         = base_url() . "search/getJobDetailsById/" . $offerlist[$key]['unique_id'];
                                $offerlist[$key]["description"]           = strip_tags($offerlist[$key]["description"]);
                                $offerlist[$key]["description"]           = html_entity_decode(strip_tags($offerlist[$key]["description"]));
                                $offerlist[$key]["about_company"]         = html_entity_decode(strip_tags($offerlist[$key]["about_company"]));
                                
                                
                                
                                if (strlen($offerlist[$key]['logo']) > 0) {
                                    $url = FCPATH . comany_logo_images . $offerlist[$key]['logo'];
                                    if (file_exists($url)) {
                                        $offerlist[$key]['company_logo'] = base_url() . comany_logo_images . $offerlist[$key]['logo'];
                                    } else {
                                        $offerlist[$key]['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                                    }
                                } else {
                                    $offerlist[$key]['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                                    
                                }
                            }
                            
                            $applied_job_ids = $this->j->getMyAppliedJobIds($postdata['user_id']);
                            foreach ($offerlist as $key => $job) {
                                
                                if (in_array($job['id'], $applied_job_ids)) {
                                    $offerlist[$key]['apply_status'] = "Applied";
                                } else {
                                    $offerlist[$key]['apply_status'] = "Apply";
                                }
                            }
                            
                            
                            $dataArray['joblist'] = $offerlist;
                            $dataArray['status']  = true;
                            $dataArray['message'] = "success";
                        } else {
                            $dataArray['joblist'] = array();
                            $dataArray['status']  = false;
                            $dataArray['message'] = "There is no any job offer for you";
                            
                        }
                        
                    }
                    
                } else {
                    $dataArray['joblist'] = array();
                    $dataArray['status']  = false;
                    $dataArray['message'] = "There is no any job offer for you";
                }
                
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "user id not provided";
                
            }
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    
    public function acceptRejectOffer()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents("php://input"), true);
            
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if (isset($postdata['job_id']) && !empty($postdata['job_id'])) {
                    if (isset($postdata['is_offer_accepted']) && !empty($postdata['is_offer_accepted'])) {
                        $result = $this->db->query("update recruiter_job_offer set is_offer_accepted=" . $postdata['is_offer_accepted'] . " where user_id=" . $postdata['user_id'] . " and job_id=" . $postdata['job_id']);
                        
                        if ($this->db->affected_rows()) {
                            if ($postdata['is_offer_accepted'] == 2) {
                                $dataArray['message'] = "Job offer rejected successfully ";
                            } else if ($postdata['is_offer_accepted'] == 1) {
                                $dataArray['message'] = "Job offer accepeted successfully ";
                            }
                            
                            $dataArray['status'] = true;
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong ";
                        }
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "improper data  ";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "improper data  ";
                }
            } else {
                
                $dataArray['status']  = false;
                $dataArray['message'] = "improper data  ";
            }
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function recommandedJobCnt()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            
            $postdata = json_decode(file_get_contents("php://input"), true);
            
            $sql    = "select us.role from users as us where us.id=" . $postdata['user_id'];
            $result = $this->db->query($sql);
            $role   = $result->row();
            
            $final_sql                        = "SELECT jobs.id 
FROM   jobs 
       LEFT JOIN organizations 
              ON organizations.id = jobs.organization_id 
       LEFT JOIN job_locations 
              ON job_locations.job_id = jobs.id 
       LEFT JOIN locations 
              ON job_locations.city_id = locations.location_id 
       LEFT JOIN job_function 
              ON job_function.job_id = jobs.id 
       LEFT JOIN job_function_categories 
              ON job_function.function_id = job_function_categories.function_id
     LEFT JOIN users 
              ON jobs.function_category_id = users.work_area
WHERE  job_function.function_id IN (" . $role->role . ") 
       AND jobs.function_category_id = users.work_area 
       AND Date(jobs.created_on) BETWEEN Date_sub(Now(), INTERVAL 6 month) AND Date(Now())
       AND job_status = 1 
       AND jobs.is_published = 1 
GROUP  BY jobs.id 
ORDER  BY job_type DESC, 
          posted_on DESC ";
            $final_res                        = $this->db->query($final_sql);
            $rowcount                         = $final_res->num_rows();
            $dataArray['status']              = true;
            $dataArray['recommanded_job_cnt'] = $rowcount;
        } else {
            
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    
    public function updateMongo($userdata, $flag = NULL, $user_id = 0)
    {
        
        $fieldtoskip = array(
            "is_email_verified",
            "created_on",
            "is_published",
            "phone_no",
            "some_as_currentadd",
            "current_pincode",
            "permanent_pincode",
            "countrycode_formob",
            "countrycode_forlandline",
            "is_open_to_relocate",
            "reason_for_change",
            "passport_no",
            "passport_expiry",
            "aadhar_no",
            "pancard_no",
            "visa_information",
            "upload_resume_file",
            "reason_description",
            "date_of_joining",
            "current_currency",
            "expected_currency"
        );
        
        foreach ($userdata as $key => $val) {
            
            $this->userAllDetails[$key] = $val;
            
            
        }
        $dt                                 = new DateTime(date('Y-m-d'), new DateTimeZone('UTC'));
        $ts                                 = $dt->getTimestamp();
        $today                              = new MongoDate($ts);
        $this->userAllDetails['updated_on'] = $today;
        if (isset($this->userAllDetails['user_id'])) {
            unset($this->userAllDetails['user_id']);
        }
        
        if ($flag != NULL) {
            
            if ($flag == 1) {
                $this->load->model("search/locations", "loc");
                $this->userAllDetails['current_city'] = $this->loc->getNameBySingleID($this->userAllDetails['current_city_id']);
                
                $this->userAllDetails['current_state'] = $this->loc->getNameBySingleID($this->userAllDetails['current_state_id']);
                
                $this->userAllDetails['current_country'] = $this->loc->getNameBySingleID($this->userAllDetails['current_country_id']);
                
                $this->userAllDetails['permanent_city'] = $this->loc->getNameBySingleID($this->userAllDetails['permanent_city_id']);
                
                $this->userAllDetails['permanent_state'] = $this->loc->getNameBySingleID($this->userAllDetails['permanent_state_id']);
                
                $this->userAllDetails['permanent_country'] = $this->loc->getNameBySingleID($this->userAllDetails['permanent_country_id']);
                
                $currrent_addres = $this->userAllDetails['current_address'] . ",";
                $currrent_addres .= $this->userAllDetails['current_city'] . " ,";
                
                $currrent_addres .= $this->loc->getNameBySingleID($this->userAllDetails['current_city_id']) . " ,";
                $currrent_addres .= $this->loc->getNameBySingleID($this->userAllDetails['current_country_id']);
                
                $permanent_addres = $this->userAllDetails['permanent_address'] . ",";
                $permanent_addres .= $this->userAllDetails['permanent_city'] . " ,";
                $permanent_addres .= $this->loc->getNameBySingleID($this->userAllDetails['permanent_city_id']) . " ,";
                $permanent_addres .= $this->loc->getNameBySingleID($this->userAllDetails['permanent_country_id']);
                $this->userAllDetails['current_address']   = $currrent_addres;
                $this->userAllDetails['permanent_address'] = $permanent_addres;
            }
            
            if ($flag == 2) {
                $this->load->model("search/function_job_category", "func");
                $this->load->model("search/industry", "ind");
                $this->load->model("search/locations", "loc");
                $this->load->model("search/employmenttype", "emptype");
                $this->userAllDetails['fun']   = implode(",", $this->func->getFunctionNameById(explode(",", $this->userAllDetails['function'])));
                $this->userAllDetails['indus'] = implode(",", $this->ind->getIndustryNameById(explode(",", $this->userAllDetails['industry'])));
                if (isset($this->userAllDetails['prefferd_location']) and count($this->userAllDetails['prefferd_location']) > 0) {
                    if (!empty($this->userAllDetails['prefferd_location'])) {
                        $this->userAllDetails['location_name'] = implode(",", $this->loc->getLocationNameById(explode(",", $this->userAllDetails['prefferd_location'])));
                    }
                }
                
                $this->userAllDetails['emp_type'] = implode(",", $this->emptype->getEmploymentType(explode(",", $this->userAllDetails['emp_ids'])));
                $this->userAllDetails['function'] = array_map('intval', explode(',', $this->userAllDetails['function']));
                
                
                $this->userAllDetails['industry']          = array_map('intval', explode(',', $this->userAllDetails['industry']));
                $this->userAllDetails['prefferd_location'] = array_map('intval', explode(',', $this->userAllDetails['prefferd_location']));
                $this->userAllDetails['emp_ids']           = array_map('intval', explode(',', $this->userAllDetails['emp_ids']));
                $this->userAllDetails['is_working']        = $this->userAllDetails['is_working'];
                $this->userAllDetails['is_noticeperiod']   = $this->userAllDetails['is_noticeperiod'];
            }
        }
        if ($user_id != 0) {
            $this->load->library('mongo_db');
            $mongo           = $this->mongo_db->db->candidate;
            $condition['id'] = (int) $user_id;
            //$condition['_id']=$user_id;
            if (isset($this->userAllDetails['_id'])) {
                unset($this->userAllDetails['_id']);
            }
            
            try {
                $mongo->update($condition, $this->userAllDetails);
            }
            catch (MongoException $e) {
                echo $exception_error = $e->getMessage();
            }
        }
        
    }
    
    
    
}