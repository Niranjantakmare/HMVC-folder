<?php
class user_web_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('mongo_db');
        $this->load->database();
    }
    
    public $postdata = "";

  
    public function insertMongo($userdata)
    {
        
        $this->load->library('mongo_db');
        $mongo = $this->mongo_db->db->candidate;
        try {
            $mongo->insert($userdata);
        }
        catch (MongoException $e) {
            $exception_error = $e->getMessage();
            
            
        }
    }
    



  public function CandidateAccountIDgenerate()
    {
        $pass         = array(); //remember to declare $pass as an array
        $alphabet3    = '1234567890';
        $alphaLength3 = strlen($alphabet3) - 1; //put the length -1 in cache
        for ($i = 0; $i < 7; $i++) {
            $n      = rand(0, $alphaLength3);
            $pass[] = $alphabet3[$n];
        }
        
        return implode($pass);
    }
    


    public function validateUserCredential($email_id, $password)
    {
        $query = "select * from users where email_id='" . $email_id . "' and password='" . $password . "'";
        $result = $this->db->query($query);
        if ($result) {
            return $result->row_array();
        } else {
            return 0;
        }
        
    }
    
    public function saveJobsAlert($data,$alert_id=NULL,$user_id=NULL)
        {  
        if($user_id!=NULL)
            {
               $user_id= $user_id;
            }
            else
            {
                $user_id=$this->session->userdata("id");
            }    

        if($alert_id!=NULL)   
         {    
            $data['updated_on'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $user_id;
            $this->db->where(array("user_id" => $user_id, "id" => $alert_id));
            $this->db->update("user_alert", $data);
            if ($this->db->affected_rows()) {
                return 1;
            } else {
                return 0;
            }
          } 
        else 
            {         
            $data['created_on']=date('Y-m-d H:i:s');
            $data['updated_on']=date('Y-m-d H:i:s');
            $data['created_by']=$user_id;
            if($this->db->insert("user_alert",$data))
            {
             return 1;
            } 
            else 
            {
             return 0;     
            }
           }
        }

 public function getNo_of_employee_org($linked_code)
    {
        
        $result          = $this->db->query("SELECT no_of_employee, linked_code FROM linkedin_no_of_employee where linked_code='$linked_code' ");
        $industry_result = $result->result_array();
        return $industry_result;
        
    }


    public function get_service_companyindustry($company_id)
        {
        $result = $this->db->query("SELECT (SELECT industry_name FROM industry_master WHERE industry_id = oi.industry_id) AS ind_name FROM organization_industry oi WHERE oi.org_id = '" . $company_id . "' ");
        return $result->result_array();
        }


    public function getDetails($key, $location_name, $expe = null, $recommented_count = 0, $last_date_jobs = null, $fromexp = null)
        {
        if(empty($key) and empty($location_name) and empty($expe)) 
            {
            return 0;
            }
        if ($recommented_count != 1) {
            if ($expe != "") {
                $to_exp_in_yr = $expe * 12;
            }
            
            if ($fromexp != "") {
                
                $from_exp_in_yr = $fromexp * 12;
            }else
            {
                 $from_exp_in_yr=0;
            }

             
            
        }
        
        if ($recommented_count == 1) {
            if (isset($expe) && isset($fromexp)) {
                
                $from_exp_in_yr      = $expe;
                $to_exp_in_yr        = $expe;
                $year_from_deviation = (RECOMMENTED_JOBS_EXP_DEVIATION / 100) * $from_exp_in_yr;
                $year_from_deviation = $from_exp_in_yr - $year_from_deviation;
                $year_to_deviation   = (RECOMMENTED_JOBS_EXP_DEVIATION / 100) * $to_exp_in_yr;
                $year_to_deviation   = $to_exp_in_yr + $year_to_deviation;
                if ($year_from_deviation >= 0) {
                    $from_exp_in_yr = $year_from_deviation;
                    $from_exp_in_yr = ceil($from_exp_in_yr);
                    $from_exp_in_yr = $from_exp_in_yr - 1;
                    
                } else {
                    $from_exp_in_yr = 0;
                }
                
                if ($year_to_deviation >= 0) {
                    $to_exp_in_yr = ceil($year_to_deviation);
                } else {
                    $to_exp_in_yr = 0;
                }

            }
        }
        $where_query = "";
        if ($recommented_count != 1) {
            $select_part = "select organizations.city_id as org_city_id,organizations.state_id as org_state_id,organizations.country_id as org_country_id,organizations.company_logo as org_logo, organizations.organization_name,jobs.is_company_disclose as is_company_disclose,jobs.is_third_party_hiring as is_third_party_hiring,jobs.id as j_id,jobs.company_logo as company_logo_job,jobs.unique_id as u_id,jobs.city_id as jcity_id,jobs.state_id as jstate_id,jobs.posted_on as job_created_date ,jobs.*,organizations.*,locations.*";
            
            $from_part = "FROM jobs left join organizations on  organizations.id=jobs.organization_id
            LEFT JOIN job_locations ON job_locations.job_id = jobs.id LEFT JOIN locations ON ( job_locations.city_id = locations.location_id OR  job_locations.country_id = locations.location_id OR job_locations.state_id = locations.location_id )    ";
        } else {
            
            $currentdate = date('Y-m-d h:i:s');
            
            $select_part = "select organizations.city_id as org_city_id,organizations.state_id as org_state_id,organizations.country_id as org_country_id,organizations.company_logo as org_logo,organizations.organization_name,jobs.is_company_disclose as is_company_disclose,jobs.is_third_party_hiring as is_third_party_hiring,jobs.id as j_id,jobs.company_logo as company_logo_job,jobs.unique_id as u_id,jobs.city_id as jcity_id,jobs.state_id as jstate_id,jobs.posted_on as job_created_date ,jobs.*,organizations.*,locations.*";
            
            $from_part = "FROM jobs left join organizations on  organizations.id=jobs.organization_id
            LEFT JOIN job_locations ON job_locations.job_id = jobs.id LEFT JOIN locations ON ( job_locations.city_id = locations.location_id  OR  job_locations.country_id = locations.location_id OR job_locations.state_id = locations.location_id )   ";
        }
        
        
        if (count($location_name) > 0) {
            $iteration_location = 0;
            if (count($location_name) > 0) {
                $where_query .= " ( ";
            }
            
            foreach ($location_name as $value) {
                
                $location_name = trim($value);
                if ($iteration_location == 0) {
                    $where_query .= "     (location_name  LIKE '" . $location_name . "%') ";
                } else {
                    $where_query .= "   or  (location_name  LIKE '" . $location_name . "%') ";
                }
                $iteration_location++;
            }
            if (count($location_name) > 0) {
                
                $where_query .= " ) ";
            }
            
        }
        
        if (strlen($key) != 0) {
            
            $key    = trim($key, ',');
            $key    = trim($key);
            $kwords = explode(',', $key);
            
            if (!empty($where_query)) {
                $where_query .= " and  ( ";
            } else {
                $where_query .= "  ( ";
            }
            
            
            $iteration = 0;
            foreach ($kwords as $key => $value) {
                $value = trim($value);
                if ($iteration == 0) {
                    $where_query .= "     (title  LIKE '%" . $value . "%') ";
                    $where_query .= " or ( required_skill  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( about_company  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( company_name  LIKE '%" . $value . "%' ) ";
                    
                    $where_query .= " or ( locations.location_name  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( description  LIKE '%" . $value . "%'  ) ";
                } else {
                    $where_query .= "  or (title  LIKE '%" . $value . "%') ";
                    $where_query .= " or ( required_skill  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( about_company  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( company_name  LIKE '%" . $value . "%' ) ";
                    
                    $where_query .= " or ( locations.location_name  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( description  LIKE '%" . $value . "%'  ) ";
                    
                }
                
                
                
                $iteration++;
            }
            $where_query .= " )";
            
        }
        
        if (isset($to_exp_in_yr) && isset($from_exp_in_yr)) {
            
            if (!empty($where_query)) {
                
                $where_query .= " and ( ( ( from_experince_in_month between " . $from_exp_in_yr . " and " . $to_exp_in_yr . ") or ( to_experince_in_month between " . $from_exp_in_yr . " and  " . $to_exp_in_yr . ") ) or  ( (  $from_exp_in_yr   between from_experince_in_month and  to_experince_in_month ) or (  $to_exp_in_yr    between from_experince_in_month and  to_experince_in_month  ) )  ) ";
                
            } else {
                $where_query .= "( ( ( from_experince_in_month between " . $from_exp_in_yr . " and " . $to_exp_in_yr . ") or ( to_experince_in_month between " . $from_exp_in_yr . " and  " . $to_exp_in_yr . ") ) or  ( (  $from_exp_in_yr   between from_experince_in_month and  to_experince_in_month ) or (  $to_exp_in_yr    between from_experince_in_month and  to_experince_in_month  ) )  ) ";
            }
        }
        $currentdate = date('Y-m-d');
        $query       = $select_part . "  " . $from_part . " where " . $where_query . " and job_status=1  and organizations.is_published=1 and jobs.is_published=1  and jobs.posted_on <=NOW() group by j_id order by job_type desc,job_created_date desc";
        $re          = $this->db->query($query);
        
        $this->query_execute = $this->db->last_query();
        $this->db->last_query();
        return $re->result_array();
    }


    public function checkIfEmailIdExists($email_id)
    {
        $query  = "select * from users where email_id='" . $email_id . "'";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            
            return 1;
        } else {
            return 0;
        }
        
    }
    
    public function NewGuid()
    {
        $s        = strtoupper(md5(uniqid(rand(), true)));
        $guidText = substr($s, 0, 8) . '-' . substr($s, 8, 4) . '-' . substr($s, 12, 4) . '-' . substr($s, 16, 4) . '-' . substr($s, 20);
        return $guidText;
    }
    
    
    public function user_top_job_array($orderby = "posted_on", $sort = "desc")
    {
        
        $top_job_array = $this->db->query("SELECT
  organizations.city_id AS org_city_id,
  organizations.state_id AS org_state_id,
  organizations.country_id AS org_country_id,
  organizations.id AS organization_id,
  jobs.id AS j_id,
  jobs.unique_id AS u_id,
  jobs.city_id AS jcity_id,
  jobs.state_id AS jstate_id,
  jobs.posted_on AS job_created_date,
  jobs.*,
  organizations.*,

  locations.location_name,
  jobs.company_logo AS company_logo
FROM
  jobs,
  organizations,
  
  locations
WHERE
  jobs.organization_id = organizations.id AND jobs.city_id = locations.location_id AND jobs.posted_on <= NOW() AND organizations.is_published = 1 AND job_type = 1 AND jobs.is_published = 1 AND job_status = 1
order by $orderby  $sort")->result_array();
        return $top_job_array;
    }
    
    
    
    public function register($userdata, $flag = 0, $iu = 0,$unique_id=0)
    {
        $userdata['is_published'] = 1;
        $insert_id                = 0;
        //$userdata['is_email_verified']=1;
        $userdata['created_on'] = date("Y-m-d H:i:s");
        $userdata['updated_on'] = date("Y-m-d H:i:s");
        //$userdata['profile_update_on']=date("Y-m-d H:i:s");
        $FirstName = $userdata['first_name'];
        $LastName  = $userdata['last_name'];
        $email_id  = $userdata['email_id'];
        $password  = $userdata['password'];
    

        $candidate_code = "CD";
        $account_random = $this->CandidateAccountIDgenerate();
        $account_id     = $candidate_code . $account_random;


        $data = array(
            'unique_id' => $unique_id,
            'first_name' => $FirstName,
            'last_name' => $LastName,
            'candidate_user_id' => $account_id,
            'email_id' => $email_id,
            'mobile_no' => $userdata['mobile_no'],
            'countrycode_formob' => '+91',
            'username' => $email_id,
            'password' => $password,
            'is_email_verified' => 0,
            'created_by' => $email_id,
            'created_on'=> date("Y-m-d H:i:s"),
            'visibility' => 'A',
            'is_accept_term_and_condition' => 1,
            'is_published' => 0
        );
        
      

        if ($iu == 1) {
            $this->db->where(array(
                "email_id" => $userdata['email_id']
            ));
            $this->db->update("users", $userdata);
            $this->db->where(array(
                "email_id" => $userdata['email_id']
            ));
            $result = $this->db->get("users")->row_array();
            return $result;
        } else {
            $actual_link = "";
            $insert_id   = $this->db->insert("users", $data);
            if ($insert_id) {
                  $this->insertMongo($data);
                return $insert_id;
            } else {
                return 0;
                
            }
        }
    }
    // Jobs related function 
    
    public function queryExecute($query)
    {
        $result = $this->db->query($query);
        if ($result) {
            return $result->result_array();
            
        } else {
            return 0;
        }
        
        
    }
    
    
    public function jobDetailsByID($query)
    {
        $result = $this->db->query($query);
        if ($result) {
            return $result->row_array();
        } else {
            return 0;
        }
    }

    public function applyJob($dataArray)
    {
        
        if ($this->db->insert("applied_jobs", $dataArray)) {
            return 1;
        } else {
            return 0;
        }
        
    }
    
    public function bulkapply($dataArray)
    {
        
        if ($this->db->insert_batch("applied_jobs", $dataArray)) {
            return 1;
        } else {
            return 0;
        }
        
    }
    
    public function bulksave($dataArray)
    {
        
        if ($this->db->insert_batch("saved_jobs", $dataArray)) {
            return 1;
        } else {
            return 0;
        }
        
    }
    
    
    
    public function getPersonalDetails($user_id)
    {
        
        $query  = "select * 
            from users  where id=" . $user_id;
        $result = $this->db->query($query)->row_array();
        if (count($result)) {
            return $result;
        } else {
            return 0;
        }
    }
    
    
    public function saveProfile($userdata)
    {
        unset($userdata['current_city_name']);
        unset($userdata['current_country_name']);
        unset($userdata['current_state_name']);
        unset($userdata['function_category_name']);
        unset($userdata['gender_name']);
        unset($userdata['highest_qualification_name']);
        unset($userdata['industry_name']);
        unset($userdata['role_name']);
        unset($userdata['workexperienceInMonth']);
        unset($userdata['workexperienceInYear']);
        $this->db->where(array(
            "id" => $userdata['user_id']
        ));
        unset($userdata['user_id']);
        if($this->db->update("users", $userdata)) 
        {
            
            return 1;
        }
        else 
        {
            return 0;
        }
    }
    
    public function getUserDetails($user_id, $image_folder)
    {
        $temp       = array();
        $userdetail = array();
        $query      = "select * from users left join locations on locations.location_id=users.current_city_id  where id=" . $user_id;
        
        $result = $this->db->query($query)->row_array();
        
        if (count($result) > 0) {
            if (isset($result["first_name"]) && !empty($result["first_name"])) 
            {
                $temp['first_name'] = $result["first_name"];
            }
            else 
            {
                $temp['first_name'] = "";
            }

            if(isset($result["last_name"]) && !empty($result["last_name"])) {
                $temp['last_name'] = $result["last_name"];
            } else {
                
                $temp['last_name'] = "";
            }
            
            
            if (isset($result["dob"]) && !empty($result["dob"])) {
                $temp['dob'] = date("d-m-Y", strtotime($result["dob"]));
            } else {
                
                $temp['dob'] = "";
                
            }
            
            
            if (isset($result["gender"]) && !empty($result["gender"])) {
                
                $temp['gender'] = $result["gender"] == 1 ? "Male" : "Female";
            } else {
                
                $temp['gender'] = "";
            }
            
            if (isset($result["mobile_no"]) && !empty($result["mobile_no"])) {
                $temp['mobile_no'] = $result["mobile_no"];
            } else {
                $temp['mobile_no'] = "";
                
            }
            
            if (isset($result["phone_no"]) && !empty($result["phone_no"])) {
                $temp['phone_no'] = $result["phone_no"];
            } else {
                $temp['phone_no'] = "";
                
            }
            if (isset($result["email_id"]) && !empty($result["email_id"])) {
                $temp['email'] = $result["email_id"];
            } else {
                $temp['email'] = "";
            }
            
            if (isset($result["location_name"]) && !empty($result["location_name"])) {
                $temp['location_name'] = $result["location_name"];
            } else {
                $temp['location_name'] = "";
            }
            
            if (isset($result["profile_pic"]) && !empty($result["profile_pic"])) {
                $temp['profile_image_path'] = base_url() . $image_folder . "/" . $result['profile_pic'];
            } else {
                $temp['profile_image_path'] = "";
            }
            
            $userdetail['user_personal_info'] = $temp;
            $query                            = "select  * from user_profile_summary where user_id=" . $user_id;
            
            $result1 = $this->db->query($query)->row_array();
            $temp    = array();
            
            if (isset($result1["resume_headline"]) && !empty($result1["resume_headline"])) {
                $temp['resume_headline'] = $result1["resume_headline"];
            } else {
                $temp['resume_headline'] = "";
            }
            
            if (isset($result1["profile_summary"]) && !empty($result1["profile_summary"])) {
                $temp['profile_summary'] = $result1["profile_summary"];
            } else {
                $temp['profile_summary'] = "";
            }
            
            if (isset($result1["total_experience"]) && !empty($result1["total_experience"])) {

                $workexperience = $result1['total_experience'];
                            $work_exp=explode(".",$workexperience);
                            $experience=0;
                            if(isset($work_exp[0]))
                            {
                                $experience=$exp1=$work_exp[0]*12;
                            }
                            if(isset($work_exp[1]))
                            {
                                $experience=$experience+$work_exp[1];
                            }
                            
                           $userdetail['user_personal_info']['workexperience']= (string)$experience;
                } else {
                 $userdetail['user_personal_info']['workexperience'] = "";
            }
            
            
            if (isset($result["is_fresher"]) && !empty($result["is_fresher"])) {
                $temp['is_fresher'] = $result1["is_fresher"];
            } else {
                $temp['is_fresher'] = "";
            }
            
            if (isset($result["is_working"]) && !empty($result["is_working"])) {
                $temp['is_working'] = $result1["is_working"];
            } else {
                $temp['is_working'] = "";
            }
            
            
            if (isset($result["is_noticeperiod"]) && !empty($result["is_noticeperiod"])) {
                $temp['is_noticeperiod'] = $result1["is_noticeperiod"];
            } else {
                $temp['is_noticeperiod'] = "";
            }
            
            if (isset($result["is_immediate_joining"]) && !empty($result["is_immediate_joining"])) {
                $temp['is_immediate_joining'] = $result1["is_immediate_joining"];
            } else {
                $temp['is_immediate_joining'] = "";
            }
            
            if (isset($result1["notice_period_in_days"]) && !empty($result1["notice_period_in_days"])) {
                $temp['notice_period'] = floor($result1['notice_period_in_days'] / 30);
            } elseif (isset($result1["notice_period_in_days"]) && $result1["notice_period_in_days"] == 0) {
                $temp['notice_period'] = "0";
            } else {
                $temp['notice_period'] = "-";
            }
            
            
            
            
            if (isset($result1['function']) && !empty($result1['function'])) {
                $profiledata['function_category_id'] = $result1['function'];
                $industry                            = "";
                $function_category_name              = $this->f->getFunctionNameById($result1['function']);
                
                if (count($function_category_name) > 0) {
                    $temp['function_category_name'] = implode(',', $function_category_name);
                } else {
                    $temp['function_category_name'] = "";
                }
                
                
            } else {
                $temp['function_category_name'] = "";
                $temp['function_category_id']   = "";
            }
                
            if (isset($result1['industry']) && !empty($result1['industry'])) {
                $profiledata['industry_ids'] = $result1['industry'];
                $industry_name               = $this->f->getIndustryNameById($result1['industry']);
                if (count($industry_name) > 0) {
                    $temp['industry_name'] = implode(',', $industry_name);
                } else {
                    $temp['industry_name'] = "";
                }
                
            } else {
                $temp['industry_ids']  = "";
                $temp['industry_name'] = "";
            }
            
            
            
            if (isset($result['highest_qualification']) && !empty($result['highest_qualification'])) {
                
                $temp['highest_qualification_id'] = $result['highest_qualification'];
                $qua                              = $this->qualification->getQualificationById(explode(",", $result['highest_qualification']));
                if (count($qua) > 0) {
                    $temp["highest_qualification_name"] = $qua[0];
                } else {
                    $temp["highest_qualification_name"] = "";
                    
                }
                
                
                
                
            } else {
                $temp['highest_qualification_name'] = "";
                $temp['highest_qualification_id']   = "";
            }
            
            if (isset($result1['is_open_to_relocate'])) {
                if ($result1['is_open_to_relocate'] == 1) {
                    $temp['opent_to_relocate'] = "Yes";
                    
                    
                } else {
                    $temp['opent_to_relocate'] = "No";
                    
                    
                }
                
            }
            
            if (isset($result1['current_ctc']) && !empty($result1['current_ctc'])) {
                
                $temp['current_ctc'] = $result1['current_ctc'] . " " . $result1['current_currency_type_id'];
                
            } else {
                $temp['currect_ctc'] = "";
                
                
            }
            
            if (isset($result1['expected_ctc']) && !empty($result1['expected_ctc'])) {
                
                $temp['expected_ctc'] = $result1['expected_ctc'] . " " . $result1['expected_currency_type_id'];
                
                
            } else {
                $temp['expected_ctc'] = "";
                
                
            }
            
            
            $query     = "select employment_types.* from user_employment,employment_types  where employment_types.id=user_employment.employment_id and  user_id=" . $user_id;
            $empresult = $this->db->query($query)->result_array();
            $empids    = array();
            if (!is_bool($empresult)) {
                foreach ($empresult as $emp) {
                    $empids[$emp['id']] = $emp['name'];
                    
                }
                $temp['employmenttypeids']  = implode(",", array_keys($empids));
                $temp['employmenttypename'] = implode(",", array_values($empids));
                
            } else {
                $temp['employmenttypeids']  = "";
                $temp['employmenttypename'] = "";
                
            }
            
            
            
            
            $query     = "select location_name from locations where location_id in (select city_id from user_preferred_location where user_id=" . $user_id . ")";
            $locations = $this->db->query($query)->result_array();
            $str       = "";
            if (count($locations) > 0) {
                foreach ($locations as $name) {
                    $str .= $name['location_name'] . "/";
                    
                }
                $str                        = trim($str, "/");
                $temp['users_pre_location'] = $str;
            } else {
                $temp['users_pre_location'] = "";
                
            }
            
            $userdetail['profile_summarey'] = $temp;
            return $userdetail;
            
        } else {
            
            return 0;
        }
        
        
    }
    
    
    function fetchUserLanguages($user_id)
    {
        
        $query = "select user_languages.*,language_master.language from user_languages 
             left join language_master on language_master.language_id=user_languages.language_id 
             where user_id=" . $user_id;
        return $this->db->query($query)->result_array();
        
    }
    
    
    function fetchSkills($user_id)
    {
        
        $this->db->select('id,skill_name,experince_in_month,level_of_proficiency');
        $this->db->where('user_id', $user_id);
        $data = $this->db->get('user_skills');
        return $data->result_array();
    }
    
    function fetchWorkExperience($user_id)
    {
        
        
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $this->db->order_by("date_of_joining", "desc");
        $data = $this->db->get('user_work_experiences');
        
        return $data->result_array();
        
    }
    
    function fetchEducation($user_id)
    {
        
        $query  = "select uq.*,uq.specialization_type_id as specialization, qm.qualification_id, qm.qualification  
              from qualification_master qm 
              left join  user_qualifications uq   on qm.qualification_id=uq.qualification_level_id
              where uq.user_id=$user_id";
        $result = $this->db->query($query);
        
        
        
        return $result->result_array();
        
        
    }
    
    function getQualification($sid)
    {
        $temp  = array();
        $query = "select qm.qualification_id, qm.qualification from qualification_master qm where qm.qualification_id=$sid";
        
        $result                   = $this->db->query($query)->row_array();
        //echo $this->db->last_query();
        //exit;
        $temp['s_id']             = $result['s_id'];
        $temp['specialization']   = $result['specialization'];
        $temp['qualification']    = $result['qualification'];
        $temp['qualification_id'] = $result['qualification_id'];
        
        return $temp;
        
    }
    
    
    
    function fetchTestimonial($user_id)
    {
        
        $this->db->select('id,testimonial,author,info');
        $this->db->where('user_id', $user_id);
        $data = $this->db->get('user_testimonial');
        return $data->result_array();
    }
    
    
    function fetchReferences($user_id)
    {
        
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $data = $this->db->get('user_references');
        return $data->result_array();
    }
    
    function fetchAdditionalInfo($user_id)
    {
        
        $this->db->where('user_id', $user_id);
        $data = $this->db->get('user_additional_info');
        return $data->row_array();
    }
    
    

    function getFunctionIdsForJobs_web($ids) 
{
    $functionArray           = array();
    $function_formated_array = array();
    
     $query="select function_id from job_function where job_id in(".implode(",",$ids).")";
     $result=$this->db->query($query)->result_array();
     foreach($result as $func)
     {
         $functionArray[]=$func['function_id'];
         
     }    
    $functionArray=array_count_values($functionArray);

   if(count($functionArray)>0)
   {
     $result=$this->db->query("select * from function_job_category where function_id in (".implode(",",  array_keys($functionArray)).")")->result_array();
     foreach($result as $func)
     {
         
         
         $function_formated_array[]=array("count"=>$functionArray[$func['function_id']],
                                                                  "function_job_category"=>$func['function_job_category'],
                                                                  "function_id"=>$func['function_id']
                                                                 );
         
     }  
          return $function_formated_array;
     }  


    
}

    function getEmpTypeFormtedArray($ids)
    {
        $temp    = array();
        $emp_ids = array();
        $query   = "select employment_id from job_employment where  job_id in (" . implode(',', $ids) . ")";
        $result  = $this->db->query($query)->result_array();
        foreach ($result as $r) {
            $emp_ids[] = $r['employment_id'];
            
        }
        
        
        $emp_ids = array_count_values($emp_ids);
        
        $result = $this->db->query("select * from employment_types  where id in ( " . implode(",", array_keys($emp_ids)) . ")")->result_array();
        foreach ($result as $re) {
            
            $temp1  = array(
                "count" => $emp_ids[$re['id']],
                "name" => $re['name'],
                "id" => $re['id']
            );
            $temp[] = $temp1;
            
            
        }
        
        return $temp;
        
    }
    
    
    
    public function updateProfilePic($user_id, $image_name, $folder_path)
    {
        $result = $this->db->query("select profile_pic from users where id=" . $user_id)->row_array();
        if (isset($result['profile_pic']) && !empty($result['profile_pic'])) {
            $image_path = $folder_path . $result['profile_pic'];
            if (file_exists($image_path)) {
                if (unlink($image_path)) {
                    $image_path = "";
                    
                }
            }
            
        }
        $data['profile_pic'] = $image_name;
        $this->db->where(array(
            "id" => $user_id
        ));
        $this->db->update("users", $data);
        if ($this->db->affected_rows()) {
            return 1;
            
        } else {
            return 0;
        }
    }

   

    public function getRecommented_jobs($key, $location_name, $expe = null, $recommented_count = 0, $last_date_jobs = null, $fromexp = null)
    {
        if (empty($key) and empty($location_name) and empty($expe))
        {
            return 0;
        }
        if ($recommented_count != 1)
        {
            if ($expe != "")
            {
                $to_exp_in_yr = $expe * 12;
            }

            if ($fromexp != "")
            {

                $from_exp_in_yr = $fromexp * 12;
            }

        }

        if($recommented_count == 1)
        {
            if (isset($expe) && isset($fromexp))
            {

                $from_exp_in_yr = $expe;
                $to_exp_in_yr = $expe;
                $year_from_deviation = (RECOMMENTED_JOBS_EXP_DEVIATION / 100) * $from_exp_in_yr;
                $year_from_deviation = $from_exp_in_yr - $year_from_deviation;
                $year_to_deviation = (RECOMMENTED_JOBS_EXP_DEVIATION / 100) * $to_exp_in_yr;
                $year_to_deviation = $to_exp_in_yr + $year_to_deviation;
                if ($year_from_deviation >= 0)
                {
                    $from_exp_in_yr = $year_from_deviation;
                    $from_exp_in_yr = ceil($from_exp_in_yr);
                    $from_exp_in_yr = $from_exp_in_yr - 1;

                }
                else
                {
                    $from_exp_in_yr = 0;
                }

                if ($year_to_deviation >= 0)
                {
                    $to_exp_in_yr = ceil($year_to_deviation);
                }
                else
                {
                    $to_exp_in_yr = 0;
                }
            }
        }
        $where_query = "";
        if ($recommented_count != 1)
        {
            $select_part = "select organizations.city_id as org_city_id,organizations.state_id as org_state_id,organizations.country_id as org_country_id,organizations.company_logo as org_logo, organizations.organization_name,jobs.is_company_disclose as is_company_disclose,jobs.is_third_party_hiring as is_third_party_hiring,jobs.id as j_id,jobs.company_logo as company_logo_job,jobs.unique_id as u_id,jobs.city_id as jcity_id,jobs.state_id as jstate_id,jobs.posted_on as job_created_date ,jobs.*,organizations.*,locations.*";

            $from_part = "FROM jobs left join organizations on  organizations.id=jobs.organization_id
            LEFT JOIN job_locations ON job_locations.job_id = jobs.id LEFT JOIN locations ON ( job_locations.city_id = locations.location_id OR  job_locations.country_id = locations.location_id OR job_locations.state_id = locations.location_id )    ";
        }
        else
        {

            $currentdate = date('Y-m-d h:i:s');

            $select_part = "select organizations.city_id as org_city_id,organizations.state_id as org_state_id,organizations.country_id as org_country_id,organizations.company_logo as org_logo,organizations.organization_name,jobs.is_company_disclose as is_company_disclose,jobs.is_third_party_hiring as is_third_party_hiring,jobs.id as j_id,jobs.company_logo as company_logo_job,jobs.unique_id as u_id,jobs.city_id as jcity_id,jobs.state_id as jstate_id,jobs.posted_on as job_created_date ,jobs.*,organizations.*,locations.*";

            $from_part = "FROM jobs left join organizations on  organizations.id=jobs.organization_id
            LEFT JOIN job_locations ON job_locations.job_id = jobs.id LEFT JOIN locations ON ( job_locations.city_id = locations.location_id  OR  job_locations.country_id = locations.location_id OR job_locations.state_id = locations.location_id )   ";
        }

        $get_rank = "";
        if (count($location_name) > 0)
        {
            $iteration_location = 0;
            if (count($location_name) > 0)
            {
                $where_query .= " ( ";
            }

            foreach ($location_name as $value)
            {

                $location_name = trim($value);
                if ($iteration_location == 0)
                {
                    if (empty($get_rank))
                    {
                        $get_rank .= ",(location_name  LIKE '" . $location_name . "%')";
                    }
                    else
                    {
                        $get_rank .= "+ (location_name  LIKE '" . $location_name . "%')";
                    }
                    $where_query .= "     (location_name  LIKE '" . $location_name . "%') ";
                }
                else
                {
                    $where_query .= "   or  (location_name  LIKE '" . $location_name . "%') ";

                    $get_rank .= "+ (location_name  LIKE '" . $location_name . "%')";

                }
                $iteration_location++;
            }
            if (count($location_name) > 0)
            {

                $where_query .= " ) ";
            }

        }

        if (strlen($key) != 0)
        {

            $key = trim($key, ',');
            $key = trim($key);
            $kwords = explode(',', $key);

            if (!empty($where_query))
            {
                $where_query .= " and  ( ";
            }
            else
            {
                $where_query .= "  ( ";
            }

            $iteration = 0;
            foreach ($kwords as $key => $value)
            {
                $value = trim($value);
                if ($iteration == 0)
                {

                    if (empty($get_rank))
                    {
                        $get_rank .= ",(title  LIKE '%" . $value . "%')";
                    }
                    else
                    {
                        $get_rank .= "+ (title  LIKE '%" . $value . "%')";
                    }

                    $get_rank .= "+  ( required_skill  LIKE '%" . $value . "%' )";

                    $get_rank .= "+  ( about_company  LIKE '%" . $value . "%' )";

                    $get_rank .= "+  ( company_name  LIKE '%" . $value . "%' )";

                    $where_query .= "     (title  LIKE '%" . $value . "%') ";
                    $where_query .= " or ( required_skill  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( about_company  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( company_name  LIKE '%" . $value . "%' ) ";

                    $where_query .= " or ( locations.location_name  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( description  LIKE '%" . $value . "%'  ) ";
                }
                else
                {

                    $get_rank .= "+  (title  LIKE '%" . $value . "%')";
                    $get_rank .= "+  ( required_skill  LIKE '%" . $value . "%' )";
                    $get_rank .= "+  ( about_company  LIKE '%" . $value . "%' )";
                    $get_rank .= "+  ( company_name  LIKE '%" . $value . "%' )";
                    $get_rank .= "+  ( description  LIKE '%" . $value . "%' )";
                    $where_query .= "  or (title  LIKE '%" . $value . "%') ";
                    $where_query .= " or ( required_skill  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( about_company  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( company_name  LIKE '%" . $value . "%' ) ";

                    $where_query .= " or ( locations.location_name  LIKE '%" . $value . "%' ) ";
                    $where_query .= " or ( description  LIKE '%" . $value . "%'  ) ";

                }

                $iteration++;
            }
            $where_query .= " )";

        }

        if (isset($to_exp_in_yr) && isset($from_exp_in_yr))
        {

            if (!empty($where_query))
            {

                $where_query .= " and ( ( ( from_experince_in_month between " . $from_exp_in_yr . " and " . $to_exp_in_yr . ") or ( to_experince_in_month between " . $from_exp_in_yr . " and  " . $to_exp_in_yr . ") ) or  ( (  $from_exp_in_yr   between from_experince_in_month and  to_experince_in_month ) or (  $to_exp_in_yr    between from_experince_in_month and  to_experince_in_month  ) )  ) ";

                //    $get_rank.="+  ( from_experince_in_month between " . $from_exp_in_yr . " and " . $to_exp_in_yr . ") + ( to_experince_in_month between " . $from_exp_in_yr . " and  " . $to_exp_in_yr . ") + (  $from_exp_in_yr   between from_experince_in_month and  to_experince_in_month ) + (  $to_exp_in_yr    between from_experince_in_month and  to_experince_in_month  )";
                

                
            }
            else
            {
                $where_query .= "( ( ( from_experince_in_month between " . $from_exp_in_yr . " and " . $to_exp_in_yr . ") or ( to_experince_in_month between " . $from_exp_in_yr . " and  " . $to_exp_in_yr . ") ) or  ( (  $from_exp_in_yr   between from_experince_in_month and  to_experince_in_month ) or (  $to_exp_in_yr    between from_experince_in_month and  to_experince_in_month  ) )  ) ";
            }
        }
        $currentdate = date('Y-m-d');

        if (!empty($get_rank))
        {
            $get_rank = $get_rank . " as rank";
        }

        $query = $select_part . "  " . $get_rank . " " . $from_part . " where " . $where_query . " and job_status=1  and organizations.is_published=1 and jobs.is_published=1  and jobs.posted_on <=NOW() group by j_id order by job_type desc,job_created_date desc";
        $re = $this
            ->db
            ->query($query);

        $this->query_execute = $this
            ->db
            ->last_query();

        return $re->result_array();
    }


    
    function getCandidateDashboardDetails($user_id, $joblist = NULL)
    {
        $data  = array();
        $table = array();
        $city  = array();
        if ($joblist == NULL) 
        {
            $result = $this->db->query("select count(*) as alert_count from user_alert where user_id=" . $user_id)->row_array();
            if (isset($result['alert_count']) && $result['alert_count'] > 0) {
                $data['alert_count'] = $result['alert_count'];
            } else {
                $data['alert_count'] = 0;
            }

            $resultobject = $this->db->query("select jobs.id as j_id,jobs.is_published,
                                jobs.unique_id as u_id,
                                jobs.created_on as job_created_date,
                                jobs.*,
                                organizations.*,
                                locations.*
                                from jobs,organizations,saved_jobs,locations,job_locations 
                                where
                                organizations.id=jobs.organization_id and 
                                jobs.id=job_locations.job_id and
                                jobs.id=saved_jobs.job_id and jobs.job_status=1 and jobs.is_published=1 and 
                                saved_jobs.user_id=" . $user_id . "
                                group by j_id ");
            if (isset($resultobject)) {
                $data['saved_job_count'] = $resultobject->num_rows();
            } else {
                $data['saved_job_count'] = 0;
            }

            $result = $this->db->query("select count(*) as u_f_c_count from user_follow_company where user_id=" . $user_id . " and is_deleted=0 ")->row_array();
            
            if (isset($result['u_f_c_count']) && $result['u_f_c_count'] > 0) {
                $data['user_follow_compnay_count'] = $result['u_f_c_count'];
            } else {
                $data['user_follow_compnay_count'] = 0;
            }
            $result = $this->db->query("select count(*) as s_search_count from user_save_searches where user_id=" . $user_id)->row_array();
            if (isset($result['s_search_count']) && $result['s_search_count'] > 0) {
                $data['saved_search_count'] = $result['s_search_count'];
            } else {
                $data['saved_search_count'] = 0;
                
            }
        }
        $query = "select * from users  where users.id=" . $user_id;
        $urdata = $this->db->query($query)->row_array();
        $data['userdetails'] = $urdata;
        $query = "";
        
        if (isset($urdata['role']) && !empty($urdata['role'])) {
            
            $udata['role'] = explode(",", $urdata['role']);
        }
        
        if (isset($urdata['work_area']) && !empty($urdata['work_area'])) {
            
            $udata['work_area'] = $urdata['work_area'];
        }
       
        if (isset($udata['role']) && (!empty($udata['role']))) {
            if (strlen($query) > 0) {
                
                $i = 0;
                $query .= "   ( ";
                foreach ($udata['role'] as $roleid) {
                    $query .= " job_function.function_id = $roleid";
                    
                    if ($i < count($udata['role']) - 1) {
                        $query .= " or ";
                    }
                    $i++;
                }
                
                $query .= "  ) ";
            } else {
                $i = 0;
                $query .= "  ( ";
                foreach ($udata['role'] as $roleid) {
                    $query .= " job_function.function_id = $roleid";
                    
                    if ($i < count($udata['role']) - 1) {
                        $query .= " or ";
                    }
                    $i++;
                }
                
                $query .= "  ) ";
                
            }
            
        }
        
        if (isset($udata['work_area']) && (!empty($udata['work_area']))) {
            if (strlen($query) > 0 && $query != " ") {
                $query .= " and  function_category_id=" . $udata['work_area'];
            } else {
                $query .= "function_category_id=" . $udata['work_area'];
            }
        }
      
        if (strlen($query) > 0) {
            
            $select_part = "SELECT TRIM(CHAR(9) FROM TRIM(jobs.title)) as title, jobs.organization_id, jobs.to_experince_in_month,jobs.from_experince_in_month,salary_show,description,required_skill,jobs.title,
                         jobs.is_third_party_hiring as is_third_party_hiring,jobs.created_on as posted_on,
                         jobs.ctc_start,jobs.ctc_end,jobs.is_published,
                         jobs.company_logo as jcompany_logo,organizations.unique_id as uorg_id,
                         organizations.organization_name as company_name,organizations.organization_name,contact_firstname,contact_lastname ,
                         organizations.company_logo as logo,jobs.ctc_start,jobs.ctc_end,
                         jobs.id, jobs.id as job_id ,jobs.city_id as jcity_id, jobs.unique_id as u_id,
                         jobs.posted_on as job_created_date,job_status,job_type,salary_show,
                         industry_id,function_category_id as rol_id,jobs.about_company,vacancy,function_category_id,
                         group_concat( distinct locations.location_name) as locname,
                         group_concat( distinct job_locations.city_id) as loc_id ,
                         GROUP_CONCAT( distinct function_job_category ) as role, 
                         GROUP_CONCAT( distinct job_function_categories.function_id  ) as fun_id,
                         GROUP_CONCAT( distinct qualification  ) as education,
                         GROUP_CONCAT( distinct qualification_master.qualification_id  ) as q_id
                         ,GROUP_CONCAT( distinct name ) as employmentTypeName,
                           GROUP_CONCAT( distinct employment_types.id    ) as emp_id
                        , GROUP_CONCAT( distinct experience_level ) as experienceLevelName,
                          GROUP_CONCAT( distinct exp_level_id  ) as experience_id";
                    
                    $form_part   = "from jobs LEFT JOIN organizations ON  organizations.id=jobs.organization_id
                         LEFT JOIN job_locations ON job_locations.job_id = jobs.id
                         LEFT JOIN locations ON job_locations.city_id = locations.location_id
                         LEFT JOIN  job_function ON job_function.job_id=jobs.id 
                         LEFT JOIN  job_function_categories ON  job_function.function_id=job_function_categories.function_id
                         LEFT JOIN  job_qualifications ON  job_qualifications.job_id=jobs.id 
                         LEFT JOIN  qualification_master on job_qualifications.qualification_id=qualification_master.qualification_id 
                         LEFT JOIN  job_employment ON  job_employment.job_id=jobs.id  
                         LEFT JOIN  employment_types ON  job_employment.employment_id=employment_types.id
                        LEFT JOIN  job_experiencelevel ON  job_experiencelevel.job_id=jobs.id 
                        LEFT JOIN  experience_level_master on job_experiencelevel.exp_level_id=experience_level_master.experience_id
                  
                  ";
            $where_part  = " where ";
            
            if (strlen($query) > 0) {
                
                $where_part .= "  " . $query . " AND DATE(jobs.posted_on) BETWEEN DATE_SUB(now(), INTERVAL 6 MONTH) AND DATE(now())  and jobs.job_status=1 and jobs.is_published=1  and  jobs.is_deleted=0  group by jobs.id order by job_type desc, jobs.created_on desc ";
                
            }
            
            $query  = $select_part . " " . $form_part . " " . $where_part;
            $result = $this->db->query($query)->result_array();
            
            if (count($result) > 0) {
                if ($joblist != NULL) {
                    return $result;
                } else {
                    $data['recommanded_job_count'] = count($result);
                }
                
            } else {
                if ($joblist != NULL) {
                    return $result;
                } else {
                    $data['recommanded_job_count'] = "0";
                    
                }
                
            }
            
            
            
        } else {
            
            if ($joblist != NULL) {
                return "Profile not fulfilling for recommanded job,please fill the profile details";
            } else {
                
                $data['recommanded_job_count'] = "0";
            }
            
            
            
            
        }
        
        
        
        return $data;
    }
    
    
    
    
    
    
    public function getActiveJobCount($org_id)
    {
        
        $query  = "select count(*) as activejobcount from jobs  where organization_id=" . $org_id . "  and job_status=1 ";
        $result = $this->db->query($query)->row_array();
        return $result['activejobcount'];
        
    }
    
    public function getTotalFollower($org_id)
    {
        
        $query  = "select count(*) as followercount from user_follow_company where organization_id=" . $org_id . " and is_deleted=0";
        $result = $this->db->query($query)->row_array();
        return $result['followercount'];
        
    }
    
    
    public function checkAllreadyFollow($user_id, $org_id)
    {
        $query  = "select * from  user_follow_company where user_id=" . $user_id . " and organization_id=" . $org_id;
        $result = $this->db->query($query);
        if (!is_bool($result)) {
            return $result->row_array();
        } else {
            return 0;
            
        }
        
        
    }
    
    
    
    public function joboffer($where_query, $orderby = "posted_on", $sort = "desc")
    {
        $postdata = $this->postdata;
        
        $query = " SELECT TRIM(CHAR(9) FROM TRIM(jobs.title)) as title ,locations.location_name,group_concat( distinct locations.location_name) as location_name 
                         
                         ,jobs.organization_id, jobs.to_experince_in_month,jobs.from_experince_in_month,salary_show,description,required_skill,jobs.title,
                         jobs.is_third_party_hiring as is_third_party_hiring,jobs.created_on as posted_on,jobs.created_on as job_created_date,
                         jobs.company_logo as jcompany_logo,jobs.ctc_start,jobs.ctc_end,
                         organizations.organization_name as company_name,contact_firstname,contact_lastname,organizations.organization_name,organizations.id as org_id,organizations.unique_id as uorg_id, 
                         organizations.company_logo as logo,jobs.is_published,
                         jobs.id,jobs.city_id as jcity_id, jobs.unique_id ,
                         jobs.posted_on as job_created_date,job_status,industry_id,function_category_id as rol_id,function_category_id
                         job_type,salary_show,jobs.about_company,vacancy,
                         function_category_id,
                         group_concat( distinct locations.location_name) as locname,
                         group_concat( distinct job_locations.city_id) as loc_id ,
                         GROUP_CONCAT( distinct function_job_category ) as role, 
                         GROUP_CONCAT( distinct job_function_categories.function_id  ) as fun_id,
                         GROUP_CONCAT( distinct qualification  ) as education,
                         GROUP_CONCAT( distinct qualification_master.qualification_id  ) as q_id
                         ,GROUP_CONCAT( distinct name ) as employmentTypeName,
                           GROUP_CONCAT( distinct employment_types.id    ) as emp_id
                        , GROUP_CONCAT( distinct experience_level ) as experienceLevelName,
                          GROUP_CONCAT( distinct exp_level_id  ) as experience_id
                      FROM jobs
                         LEFT JOIN organizations ON  organizations.id=jobs.organization_id
                         LEFT JOIN job_locations ON job_locations.job_id = jobs.id
                         LEFT JOIN locations ON job_locations.city_id = locations.location_id
                         LEFT JOIN  job_function ON job_function.job_id=jobs.id 
                         LEFT JOIN  job_function_categories ON  job_function.function_id=job_function_categories.function_id
                         LEFT JOIN  job_qualifications ON  job_qualifications.job_id=jobs.id 
                         LEFT JOIN  qualification_master on job_qualifications.qualification_id=qualification_master.qualification_id 
                         LEFT JOIN  job_employment ON  job_employment.job_id=jobs.id  
                         LEFT JOIN  employment_types ON  job_employment.employment_id=employment_types.id
                        LEFT JOIN  job_experiencelevel ON  job_experiencelevel.job_id=jobs.id 
                        LEFT JOIN  experience_level_master on job_experiencelevel.exp_level_id=experience_level_master.experience_id
                        LEFT JOIN  recruiter_job_offer on recruiter_job_offer.job_id=jobs.id
                        
                        
                        ";
        
        
        $query = $query . "  where  " . $where_query . " and ((jobs.is_published=1 AND jobs.is_deleted = 0 AND jobs.job_status = 1) OR (recruiter_job_offer.is_offer_accepted=1)) group by jobs.id  order by recruiter_job_offer.created_on desc ";
        
        
        
        $result = $this->db->query($query);
        
        return $result;
        
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function basicSearch($where_query, $orderby = "posted_on", $sort = "desc")
    {
        $postdata = $this->postdata;
        
        $query = " SELECT TRIM(CHAR(9) FROM TRIM(jobs.title)) as title ,locations.location_name,group_concat( distinct locations.location_name) as location_name 
                         
                         ,jobs.organization_id, jobs.to_experince_in_month,jobs.from_experince_in_month,salary_show,description,required_skill,jobs.title,
                         jobs.is_third_party_hiring as is_third_party_hiring,jobs.created_on as posted_on,jobs.created_on as job_created_date,
                         jobs.company_logo as jcompany_logo,jobs.ctc_start,jobs.ctc_end,
                         organizations.organization_name as company_name,organizations.details as about_details,contact_firstname,contact_lastname,organizations.organization_name,organizations.id as org_id,organizations.unique_id as uorg_id, 
                         organizations.company_logo as logo,jobs.is_published,
                         jobs.id,jobs.city_id as jcity_id, jobs.unique_id ,
                         jobs.posted_on as job_created_date,job_status,industry_id,function_category_id as rol_id,function_category_id
                         job_type,salary_show,jobs.about_company,vacancy,
                         function_category_id,
                         group_concat( distinct locations.location_name) as locname,
                         group_concat( distinct job_locations.city_id) as loc_id ,
                         GROUP_CONCAT( distinct function_job_category ) as role, 
                         GROUP_CONCAT( distinct job_function_categories.function_id  ) as fun_id,
                         GROUP_CONCAT( distinct qualification  ) as education,
                         GROUP_CONCAT( distinct qualification_master.qualification_id  ) as q_id
                         ,GROUP_CONCAT( distinct name ) as employmentTypeName,
                           GROUP_CONCAT( distinct employment_types.id    ) as emp_id
                        , GROUP_CONCAT( distinct experience_level ) as experienceLevelName,
                          GROUP_CONCAT( distinct exp_level_id  ) as experience_id
                      FROM jobs
                         LEFT JOIN organizations ON  organizations.id=jobs.organization_id
                         LEFT JOIN job_locations ON job_locations.job_id = jobs.id
                         LEFT JOIN locations ON job_locations.city_id = locations.location_id
                         LEFT JOIN  job_function ON job_function.job_id=jobs.id 
                         LEFT JOIN  job_function_categories ON  job_function.function_id=job_function_categories.function_id
                         LEFT JOIN  job_qualifications ON  job_qualifications.job_id=jobs.id 
                         LEFT JOIN  qualification_master on job_qualifications.qualification_id=qualification_master.qualification_id 
                         LEFT JOIN  job_employment ON  job_employment.job_id=jobs.id  
                         LEFT JOIN  employment_types ON  job_employment.employment_id=employment_types.id
                        LEFT JOIN  job_experiencelevel ON  job_experiencelevel.job_id=jobs.id 
                        LEFT JOIN  experience_level_master on job_experiencelevel.exp_level_id=experience_level_master.experience_id";
        
        
        $query = $query . "  where  " . $where_query . "  and jobs.job_status=1  and jobs.is_published=1 and jobs.is_deleted = 0   group by jobs.id order by jobs.job_type desc,jobs.$orderby $sort";
        
        $result = $this->db->query($query);
        
        
        return $result;
        
        
        
    }
    
    
      public function advanceSearch($where_query, $table, $keymap,$select_index_rank=null)
    {
       if(!empty($select_index_rank))
       {
        $select_index_rank=",".$select_index_rank;
       }
       $where_query=trim($where_query);
       if(!empty($where_query))
       {
      $where_query="and ".$where_query;
       }
        $select = "select distinct jobs.id as j_id,jobs.state_id as jstate_id,jobs.unique_id as u_id,jobs.city_id as jcity_id, jobs.company_logo as company_logo_job ,jobs.posted_on as job_created_date ,jobs.*,organizations.*,locations.* $select_index_rank";
        $from   = " from " . implode(",", $table);
        $where  = " where " . $keymap . " " . $where_query . " and  jobs.is_published=1 and  job_status=1  and jobs.is_published=1    and jobs.posted_on <=NOW()  group by j_id   order by job_type desc,job_created_date desc";
        
        
        $query = $select . " " . $from . " " . $where;
        $re                  = $this->db->query($query);
        $this->query_execute = $this->db->last_query();
        /*  echo $this->db->last_query();
        die;*/
        return $re->result_array();
    }

    
    
    
    public function getSavedJobList($where_query)
    {
        
        
        $query = "select organizations.city_id as org_city_id,organizations.state_id as org_state_id,organizations.country_id as org_country_id,organizations.company_logo as org_logo,  organizations.company_logo as org_logo ,jobs.id as j_id,jobs.company_logo as company_logo_job, jobs.city_id as jcity_id, jobs.state_id as jstate_id ,jobs.unique_id as u_id,jobs.posted_on as job_created_date, jobs.* ,organizations.*,locations.* from jobs,organizations,saved_jobs,locations where organizations.id=jobs.organization_id  and jobs.id=saved_jobs.job_id and $where_query and jobs.is_published=1 and jobs.job_status=1 and   jobs.is_deleted=0  group by jobs.id order by jobs.created_on desc";
        
        
        
        /*$query = $query . "  where   " . $where_query . "  and jobs.job_status=1 and jobs.is_published=1  and jobs.is_deleted=0 group by jobs.id order by saved_jobs.created_on desc";
        */        
        $this->db->insert("query", array(
            "query" => $query
        ));
        $result = $this->db->query($query);
        
        return $result->result_array();
        
        
    }
    
    
    public function getApplyJobList($where_query)
    {
            $query="SELECT
            organizations.city_id AS org_city_id,
            organizations.state_id AS org_state_id,
            organizations.country_id AS org_country_id,
            organizations.id AS organization_id,
            jobs.id AS j_id,
            jobs.unique_id AS u_id,
            jobs.city_id AS jcity_id,
            jobs.state_id AS jstate_id,
            jobs.posted_on AS job_created_date,
            jobs.*,
            organizations.*,
            locations.location_name,
            applied_jobs.applied_on,
            jobs.company_logo AS company_logo
            FROM
            jobs,
            applied_jobs,
            users,
            organizations,
            locations
            WHERE  applied_jobs.job_id=jobs.id and applied_jobs.user_id=users.id and
            jobs.organization_id = organizations.id  AND  organizations.is_published = 1  AND jobs.is_published = 1";

        $query = $query ." ". $where_query . " group by jobs.id order by applied_jobs.applied_on desc, applied_jobs.id desc";
        $this->db->insert("query", array(
            "query" => $query
        ));
      
        $result = $this->db->query($query);
        return $result->result_array();
      }
    
    public function salaryRangeService()
    {
        $salaryranges = array();
        $temp         = range(0, 21, 1);
        for ($i = 0; $i < count($temp) - 1; $i++) {
            
            $salaryranges[] = array(
                "salaryrange_name" => $i . "k",
                "salaryrange_id" => $temp[$i] * 1000
            );
        }
        
        return $salaryranges;
    }
    
    public function expRangeService()
    {
        
        
        return range(0, 25);
    }
    
    
    
    public function getQuery($where_query)
    {
        $postdata   = $this->postdata;
        $selectpart = null;
        $joinpart   = null;
        
        $query = "SELECT jobs.title, jobs.organization_id, jobs.to_experince_in_month,jobs.from_experince_in_month,salary_show,description,required_skill,jobs.title,
                         jobs.is_third_party_hiring as is_third_party_hiring,jobs.created_on as posted_on,
                         jobs.ctc_start,jobs.ctc_end,
                         jobs.company_logo as jcompany_logo,
                         organizations.organization_name as company_name, organizations.organization_name,contact_firstname,contact_lastname,organizations.organization_name,  
                         organizations.company_logo as or_logo,
                         organizations.company_logo as logo,
                         jobs.id,jobs.city_id as jcity_id, jobs.unique_id as u_id,
                         jobs.posted_on as job_created_date,job_status,job_type,salary_show,jobs.about_company,
                         industry_id,function_category_id as rol_id,
                          jobs.about_company,vacancy,
                         industry_id,function_category_id as rol_id,
                         group_concat( distinct locations.location_name) as locname,
                         group_concat( distinct job_locations.city_id) as loc_id ,
                         GROUP_CONCAT( distinct function_job_category ) as role, 
                         GROUP_CONCAT( distinct job_function_categories.function_id  ) as fun_id,
                         GROUP_CONCAT( distinct qualification  ) as education,
                         GROUP_CONCAT( distinct qualification_master.qualification_id  ) as q_id
                         ,GROUP_CONCAT( distinct name ) as employmentTypeName,
                           GROUP_CONCAT( distinct employment_types.id    ) as emp_id
                        , GROUP_CONCAT( distinct experience_level ) as experienceLevelName,
                          GROUP_CONCAT( distinct exp_level_id  ) as experience_id
                      from jobs 
                      left join organizations on  organizations.id=jobs.organization_id
                      LEFT JOIN job_locations ON job_locations.job_id = jobs.id
                      LEFT JOIN locations ON job_locations.city_id = locations.location_id
                      LEFT JOIN  job_function ON job_function.job_id=jobs.id 
                      LEFT JOIN  job_function_categories ON  job_function.function_id=job_function_categories.function_id
                      LEFT JOIN  job_qualifications ON  job_qualifications.job_id=jobs.id 
                      LEFT JOIN  qualification_master on job_qualifications.qualification_id=qualification_master.qualification_id 
                      LEFT JOIN  job_employment ON  job_employment.job_id=jobs.id  
                      LEFT JOIN  employment_types ON  job_employment.employment_id=employment_types.id
                      LEFT JOIN  job_experiencelevel ON  job_experiencelevel.job_id=jobs.id 
                      LEFT JOIN  experience_level_master on job_experiencelevel.exp_level_id=experience_level_master.experience_id";
        $query = $query . " where    " . $where_query . " and  job_status=1 and jobs.is_published=1 and jobs.is_deleted=0 group by jobs.id  order by job_type desc,job_created_date desc   ";
        
        
        
        return $query;
        
    }

    public function isUserUploadResume($userid){
  $this->db->select("upload_resume_file");
  $this->db->from("user_profile_summary");
  $this->db->where("user_id",$userid);
  return $this->db->get()->row();
}

    
    
}