<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class webservice extends CI_Controller
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
        $this->config->load('searchconfig');
    }
    
    public function index()
    {
    }
    
    public function NewGuid()
    {
        $s        = strtoupper(md5(uniqid(rand(), true)));
        $guidText = substr($s, 0, 8) . '-' . substr($s, 8, 4) . '-' . substr($s, 12, 4) . '-' . substr($s, 16, 4) . '-' . substr($s, 20);
        return $guidText;
    }
    
    public function basicSearch($orderby = "posted_on", $sort = "desc")
    {
        $where_query       = "";
        $dataArray         = array();
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $industry_ids      = array();
        $function_ids      = array();
        $applied_job_ids   = array();
        $flag              = 0;
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $error    = array();
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['pre_loc_name']) && isset($postdata['pre_loc_name'])) {
                $location_name = $postdata['pre_loc_name'];
                $flag          = 1;
            }
            
            if (!empty($postdata['keyword']) && isset($postdata['keyword'])) {
                $keyword = $postdata['keyword'];
                $flag    = 1;
            } else {
                $keyword = "";
            }
            
            $experience     = "";
            $fromexperience = "";
            if (!empty($postdata['to_exp_year']) && isset($postdata['to_exp_year'])) {
                $experience = $postdata['to_exp_year'];
                $flag       = 1;
            }
            
            if (!empty($postdata['from_exp_year']) && isset($postdata['from_exp_year'])) {
                $fromexperience = $postdata['from_exp_year'];
                $flag           = 1;
            }
            $experience_wrong=0;
            if(!empty($fromexperience) || !empty($experience) )
            {
                if(!empty($fromexperience) and !empty($experience))
                {

                }else if(!empty($fromexperience))
                {
                 $dataArray['status']  = false;
                 $dataArray['message'] = "Please select to experience";
                  $flag           = 0;
                  $experience_wrong=1;
                }else
                {
                    $dataArray['status']  = false;
                $dataArray['message'] = "Please select from experience";
                 $flag           = 0;
                 $experience_wrong=1;
                }

            }

            if (!empty($location_name) && isset($location_name)) {
                $location_name         = trim($location_name);
                $data['location_name'] = $location_name;
                $location_name_id      = explode(',', $location_name);
            } else {
                $location_name_id = array();
            }
            
            if ($flag == 0) {
                if($experience_wrong==0)
                {
                $dataArray['status']  = false;
                $dataArray['message'] = "Please specify atleast one search criteria to search jobs";
                }
            } else 
            {
                $where_query = "   " . $where_query;

                $result      = $this->u->getDetails($keyword, $location_name_id, $experience, 0, null, $fromexperience);
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
                    $query                = " select industry_master.industry_id,industry_name from industry_master,jobs,job_industry where jobs.id=job_industry.job_id and industry_master.industry_id=job_industry.industry_id and jobs.id=" . $j_id;
                    $re                   = $this->db->query($query);
                    $this->query_execute  = $this->db->last_query();
                    $temp                 = array();
                    foreach ($re->result_array() as $e_type) {
                        $temp[]         = $e_type['industry_name'];
                        $industry_ids[] = $e_type['industry_id'];
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
                                "location_name" => $location_name
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
                                "industry_name" => $industry_name[0]
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
                
                if (count($result) > 0) {
                    $offset = 0;
                    if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                        $offset = $postdata['offset'];
                    }
                    
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
                        } else {
                            foreach ($result as $key => $job) {
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
                                
                                if (in_array($job['id'], $applied_job_ids)) {
                                    $job['apply_status'] = "Applied";
                                } else {
                                    $job['apply_status'] = "Apply";
                                }
                                
                                $result[$key] = $job;
                            }
                        }
                        
                        $dataArray['status']  = true;
                        $dataArray['joblist'] = $result;
                        
                        // $dataArray['message']="user_id=".$postdata['user_id']." keyword=".implode(",",$postdata['keyword']);
                        // $dataArray['filteroption']=$filteroption;
                        
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "No jobs found matching to your criteria ";
                        
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No jobs found matching to your criteria";
                }
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function advanceSearch($orderby = "posted_on", $sort = "desc")
    {
        $flag              = 0;
        $where_query       = "";
        $dataArray         = array();
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $applied_job_ids   = array();
        $industry_ids      = array();
        $function_ids      = array();
        $location_id       = $this->input->post('location_id');
        $keywords          = array();
        $table[]           = "jobs";
        $table[]           = "organizations";
        $table[]           = "locations";
        $table[]           = "job_locations";
        $select_index_rank = "";
        
        $empty_form_tracker = 0;
        
        $date = date('Y-m-d h-i-s');
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $error    = array();
            $postdata = json_decode(file_get_contents('php://input'), true);
            $save     = $postdata;
            if (isset($save['pre_loc_ids'])) {
                $location_id = $save['pre_loc_ids'];
            }
            if (isset($location_id) && (!empty($location_id))) {
                $keymap = "jobs.organization_id=organizations.id  and ( job_locations.job_id = jobs.id and job_locations.city_id = locations.location_id ) ";
            } else {
                $keymap = "jobs.organization_id=organizations.id  and ( job_locations.job_id = jobs.id or job_locations.city_id = locations.location_id or job_locations.country_id = locations.location_id or job_locations.state_id = locations.location_id ) ";
            }
            
            $where_query = "";
            if (!empty($save['keyword']) && (isset($save['keyword']))) {
                $searchoption['atleast_one_word'] = $save['keyword'];
                $all_words                        = $save['keyword'];
                $formdata['a_w']                  = $all_words;
                $data['all_these_words']          = $all_words;
                $all_words                        = preg_replace('/[ ]+/', ' ', trim($all_words));
                $arrCommonKeyword                 = explode(",", $all_words);
                $formfieldtracker                 = 1;
            }
            
            if (isset($arrCommonKeyword) and count($arrCommonKeyword) > 0) {
                if (strlen($where_query) > 0) {
                    $where_query .= "and (";
                } else {
                    $where_query .= "(";
                }
                
                if (isset($arrCommonKeyword) and count($arrCommonKeyword) > 1) {
                    for ($i = 0; $i < count($arrCommonKeyword); $i++) {
                        $where_query .= " ( (title  LIKE '%" . $arrCommonKeyword[$i] . "%') ";
                        $where_query .= " or ( required_skill  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                        $where_query .= " or ( about_company  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                        $where_query .= " or ( company_name  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                        $where_query .= " or ( description  LIKE '%" . $arrCommonKeyword[$i] . "%' ) )";
                        if ($i != count($arrCommonKeyword) - 1) {
                            $where_query .= "   or";
                        }
                    }
                    
                    $where_query .= ")";
                } else {
                    $where_query .= " ( (title  LIKE '%" . implode("|", $arrCommonKeyword) . "%') ";
                    $where_query .= " or ( about_company  LIKE '%" . implode("|", $arrCommonKeyword) . "%' )  ";
                    $where_query .= " or ( company_name  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ";
                    $where_query .= " or ( description  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ";
                    $where_query .= " or ( required_skill  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ) )";
                }
                
                $empty_form_tracker = 1;
            }
            
            if (isset($save['industry_ids']) && (!empty($save['industry_ids']))) {
                $industry_id                   = $save['industry_ids'];
                $formdata['sel_industry']      = $industry_id;
                $searchoption["industry_name"] = implode(",", $this->Industry->getIndustryNameById($industry_id));
                if (strlen($where_query) > 0) {
                    $where_query .= " and  job_industry.industry_id in  (" . implode(",", $industry_id) . ")";
                } else {
                    $where_query .= "  job_industry.industry_id in  (" . implode(",", $industry_id) . ")";
                }
                $keymap .= " and  jobs.id=job_industry.job_id ";
                $table[] = "job_industry";
                
                $empty_form_tracker = 1;
            }
            
            if (isset($save['function_id']) && !empty($save['function_id'])) {
                $function_id                   = $save['function_id'];
                $searchoption["function_name"] = implode(",", $this->f->getFunctionNameById($function_id));
                if (strlen($where_query) > 0) {
                    $where_query .= " and  function_id in (" . implode(",", $function_id) . ")";
                } else {
                    $where_query .= " function_id in (" . implode(",", $function_id) . ")";
                }
                
                
                $keymap .= " and  jobs.id=job_function.job_id ";
                $table[]            = "job_function";
                $empty_form_tracker = 1;
            }
            
            if (isset($save['experience_from_month']) and !empty($save['experience_from_month'])) {
                $from_month = $save['experience_from_month'];
                $to_month   = $save['experience_to_month'];
                if (!empty($from_month) && (!empty($to_month))) {
                    $data["experience"] = $from_month . "-" . $to_month;
                    if ($from_month > $to_month) {
                        $errorflag[] = 'Invalid Experience Range';
                    } else {
                        $searchoption["experience"] = $from_month . "-" . $to_month;
                        if (strlen($where_query) > 0) {
                            $where_query .= " and (  ( ( from_experince_in_month  between $from_month and  $to_month )  or  (   to_experince_in_month  between $to_month and  $to_month  ) )  or  ( (  $from_month   between from_experince_in_month and  to_experince_in_month ) or (  $to_month    between from_experince_in_month and  to_experince_in_month  ) )  )";
                        } else {
                            $where_query .= " (  ( ( from_experince_in_month  between $from_month and  $to_month )  or  (   to_experince_in_month  between $from_month and  $to_month  ) )  or  ( (  $from_month   between from_experince_in_month and  to_experince_in_month ) or (  $to_month    between from_experince_in_month and  to_experince_in_month  ) )  )";
                        }
                        
                        $empty_form_tracker = 1;
                    }
                }
            }
            
            if (!empty($save['ctc_start']) && !empty($save['ctc_end'])) {
                $empty_form_tracker = 1;
                if ($postdata['ctc_start'] > $postdata['ctc_end']) {
                    $errorflag[] = 'Invalid s Range';
                } else {
                    $searchoption["salary"] = $save['ctc_start'] . "-" . $save['ctc_end'];
                    $ctc_start              = $save['ctc_start'];
                    $ctc_end                = $save['ctc_end'];
                    if (strlen($where_query) > 0) {
                        $where_query .= " and (  ( ctc_Start between " . $ctc_start . " and " . $ctc_end . " )  or   ( ctc_end between " . $ctc_start . " and " . $ctc_end . "  ) ) ";
                    } else {
                        $where_query .= " (  ( ctc_Start between " . $ctc_start . " and " . $ctc_end . " )  or   ( ctc_end between " . $ctc_start . " and " . $ctc_end . "  ) ) ";
                    }
                }
            }
            if (isset($save['pre_loc_ids'])) {
                $emp_type_id = $save['emptype_ids'];
            }
            if (isset($emp_type_id) && (!empty($emp_type_id))) {
                if (count($emp_type_id) > 3) {
                    $errorflag[] = "Maximum three employment type are allowed";
                } else {
                    $searchoption["employment_types"] = implode(",", $this->emptype->getEmploymentType($emp_type_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and job_employment.employment_id in ( " . implode(",", $emp_type_id) . " )";
                    } else {
                        $where_query .= "job_employment.employment_id in (" . implode(",", $emp_type_id) . " )";
                    }
                    
                    $keymap .= " and  jobs.id=job_employment.job_id  ";
                    $table[]            = "job_employment";
                    $empty_form_tracker = 1;
                }
            }
            if (isset($save['qualification_ids'])) {
                $qualification_level = $save['qualification_ids'];
            }
            if (isset($qualification_level) && (!empty($qualification_level))) {
                $searchoption["qualification"] = implode(",", $this->qtype->getQualificationById($qualification_level));
                if (strlen($where_query) > 0) {
                    $where_query .= " and job_qualifications.qualification_id in (" . implode(",", $qualification_level) . ")";
                } else {
                    $where_query .= " job_qualifications.qualification_id in (" . implode(",", $qualification_level) . ")";
                }
                
                $keymap .= " and jobs.id=job_qualifications.job_id ";
                $table[]            = "job_qualifications";
                $empty_form_tracker = 1;
            }
            
            if (isset($save['qualification_ids'])) {
                $exp_level_id = $save['exp_level_ids'];
            }
            
            if (isset($exp_level_id) && (!empty($exp_level_id))) {
                $data["experience_level"] = implode(",", $this->elevel->getExperienceLevelById($exp_level_id));
                if (strlen($where_query) > 0) {
                    $where_query .= " and  job_experiencelevel.exp_level_id in (" . implode(",", $exp_level_id) . ")";
                } else {
                    $where_query .= " job_experiencelevel.exp_level_id in (" . implode(",", $exp_level_id) . ")";
                }
                
                $keymap .= " and  jobs.id=job_experiencelevel.job_id  ";
                $table[]            = "job_experiencelevel";
                $empty_form_tracker = 1;
            }
            
            $company = $save['company'];
            if (!empty($company) && isset($company) && ($company != "")) {
                $formdata['company']      = $company;
                $search_option["company"] = $company;
                
                if (strlen($where_query) > 0) {
                    $where_query .= " and   ";
                } else {
                    
                    $where_query .= "  ";
                }
                
                
                $company = preg_replace('/[ ]+/', ' ', trim($company));
                
                $temp  = explode(",", $company);
                $count = count($temp);
                $i     = 0;
                $where_query .= "( ";
                foreach ($temp as $val) {
                    $where_query .= "( organizations.organization_name like '%" . trim($val) . "%' or  jobs.company_name  like '%" . trim($val) . "%' ) ";
                    
                    $select_index_rank .= "+( organizations.organization_name like '%" . trim($val) . "%' or  jobs.company_name  like '%" . trim($val) . "%' )";
                    
                    if ($i < $count - 1) {
                        $where_query .= " or";
                    }
                    $i++;
                }
                $where_query .= " )";
                
                if (strlen($where_query) > 0) {
                    
                    $where_query .= "";
                }
                $empty_form_tracker = 1;
            }
            
            
            if (isset($location_id) && (!empty($location_id))) {
                $formdata['sel_loc']            = $location_id;
                $search_option["location_name"] = implode(",", $this->Locations->getLocationNameById($location_id));
                
                if (strlen($where_query) > 0) {
                    $where_query .= " and job_locations.city_id in (" . implode(",", $location_id) . " )";
                } else {
                    $where_query .= " job_locations.city_id in (" . implode(",", $location_id) . " )";
                }
                
                
                
                $empty_form_tracker = 1;
            }
            
            
            
            if ($empty_form_tracker == 1) {
                $where_query       = "   " . $where_query;
                $this->u->postdata = $postdata;
                $result            = $this->u->advanceSearch($where_query, $table, $keymap);
                
                if (!is_bool($result)) {
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
                        $query                = " select industry_master.industry_id,industry_name from industry_master,jobs,job_industry where jobs.id=job_industry.job_id and industry_master.industry_id=job_industry.industry_id and jobs.id=" . $j_id;
                        $re                   = $this->db->query($query);
                        $this->query_execute  = $this->db->last_query();
                        $temp                 = array();
                        foreach ($re->result_array() as $e_type) {
                            $temp[]         = $e_type['industry_name'];
                            $industry_ids[] = $e_type['industry_id'];
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
                                    "location_name" => $location_name
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
                                    "industry_name" => $industry_name[0]
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
                    
                    $offset = 0;
                    if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                        $offset = $postdata['offset'];
                    }
                    
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
                        } else {
                            foreach ($result as $key => $job) {
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
                                
                                if (in_array($job['id'], $applied_job_ids)) {
                                    $job['apply_status'] = "Applied";
                                } else {
                                    $job['apply_status'] = "Apply";
                                }
                                
                                $result[$key] = $job;
                            }
                        }
                        
                        $dataArray['status']  = true;
                        $dataArray['joblist'] = $result;
                        
                        // $dataArray['message']="user_id=".$postdata['user_id']." keyword=".implode(",",$postdata['keyword']);
                        // $dataArray['filteroption']=$filteroption;
                        
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "No jobs found matching to your criteria ";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No jobs found matching to your criteria ";
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Please specify atleast one search criteria ";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function jobDetailsByID()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            $query    = "select jobs.*,organizations.organization_name,locations.location_name,industry_name,job_function_categories.function_job_category
                            from jobs,locations,organizations,industry_master,job_function_categories
                            where jobs.city_id=locations.location_id and
                                  jobs.organization_id=organizations.id and
                                  jobs.function_category_id=job_function_categories.function_id and 
                                  jobs.industry_id=industry_master.industry_id";
            $query .= "  and jobs.unique_id='" . $postdata['unique_id'] . "'";
            $result = $this->u->jobDetailsByID($query);
            if ($result) {
                $result["description"]   = html_entity_decode(strip_tags($result["description"]));
                $result["about_company"] = html_entity_decode(strip_tags($result["about_company"]));
                $query                   = " select name from employment_types,jobs,job_employment 
                                                    where jobs.id=job_employment.job_id and 
                                                    employment_types.id=job_employment.employment_id 
                                                    and jobs.id=" . $result['id'];
                $re                      = $this->db->query($query);
                $temp                    = array();
                foreach ($re->result_array() as $e_type) {
                    $temp[] = $e_type['name'];
                }
                
                $result['emp_types'] = implode("/", $temp);
                $query               = "  select function_job_category as  name 
                                                      from job_function_categories,jobs,job_function 
                                                      where jobs.id=job_function.job_id and 
                                                      job_function_categories.function_id=job_function.function_id and 
                                                      jobs.id=" . $result['id'];
                $temp                = array();
                $re                  = $this->db->query($query);
                foreach ($re->result_array() as $e_type) {
                    $temp[] = $e_type['name'];
                }
                
                $result['function_name'] = implode("/", $temp);
                $dataArray['status']     = true;
                $dataArray['job']        = $result;
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Something went wrong try again";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function filterJobs($orderby = "posted_on", $sort = "desc")
    {
        $flag               = 0;
        $where_query        = "";
        $dataArray          = array();
        $cur_dis_jobids     = array();
        $locationids        = array();
        $formated_location  = array();
        $formated_industry  = array();
        $formated_function  = array();
        $applied_job_ids    = array();
        $industry_ids       = array();
        $function_ids       = array();
        $location_id        = $this->input->post('location_id');
        $keywords           = array();
        $table[]            = "jobs";
        $table[]            = "organizations";
        $table[]            = "locations";
        $table[]            = "job_locations";
        $select_index_rank  = "";
        $empty_form_tracker = 0;
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            $save     = $postdata;
            
            if (isset($save['pre_loc_ids'])) {
                $location_id = $save['pre_loc_ids'];
            }
            if (isset($location_id) && (!empty($location_id))) {
                $keymap = "jobs.organization_id=organizations.id  and ( job_locations.job_id = jobs.id and job_locations.city_id = locations.location_id ) ";
            } else {
                $keymap = "jobs.organization_id=organizations.id  and ( job_locations.job_id = jobs.id or job_locations.city_id = locations.location_id or job_locations.country_id = locations.location_id or job_locations.state_id = locations.location_id ) ";
            }
            
            $where_query = "";
            if (!empty($save['keyword']) && (isset($save['keyword']))) {
                $searchoption['atleast_one_word'] = $save['keyword'];
                $all_words                        = $save['keyword'];
                $formdata['a_w']                  = $all_words;
                $data['all_these_words']          = $all_words;
                $all_words                        = preg_replace('/[ ]+/', ' ', trim($all_words));
                $arrCommonKeyword                 = explode(",", $all_words);
                $formfieldtracker                 = 1;
            }
            
            if (isset($arrCommonKeyword) and count($arrCommonKeyword) > 0) {
                if (strlen($where_query) > 0) {
                    $where_query .= "and (";
                } else {
                    $where_query .= "(";
                }
                
                if (isset($arrCommonKeyword) and count($arrCommonKeyword) > 1) {
                    for ($i = 0; $i < count($arrCommonKeyword); $i++) {
                        $where_query .= " ( (title  LIKE '%" . $arrCommonKeyword[$i] . "%') ";
                        $where_query .= " or ( required_skill  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                        $where_query .= " or ( about_company  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                        $where_query .= " or ( company_name  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                        $where_query .= " or ( description  LIKE '%" . $arrCommonKeyword[$i] . "%' ) )";
                        if ($i != count($arrCommonKeyword) - 1) {
                            $where_query .= "   or";
                        }
                    }
                    
                    $where_query .= ")";
                } else {
                    $where_query .= " ( (title  LIKE '%" . implode("|", $arrCommonKeyword) . "%') ";
                    $where_query .= " or ( about_company  LIKE '%" . implode("|", $arrCommonKeyword) . "%' )  ";
                    $where_query .= " or ( company_name  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ";
                    $where_query .= " or ( description  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ";
                    $where_query .= " or ( required_skill  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ) )";
                }
                
                $empty_form_tracker = 1;
            }
            
            if (isset($save['industry_ids']) && (!empty($save['industry_ids']))) {
                $industry_id                   = $save['industry_ids'];
                $formdata['sel_industry']      = $industry_id;
                $searchoption["industry_name"] = implode(",", $this->Industry->getIndustryNameById($industry_id));
                if (strlen($where_query) > 0) {
                    $where_query .= " and  job_industry.industry_id in  (" . implode(",", $industry_id) . ")";
                } else {
                    $where_query .= "  job_industry.industry_id in  (" . implode(",", $industry_id) . ")";
                }
                $keymap .= " and  jobs.id=job_industry.job_id ";
                $table[] = "job_industry";
                
                $empty_form_tracker = 1;
            }
            
            if (isset($save['function_id']) && !empty($save['function_id'])) {
                $function_id                   = $save['function_id'];
                $searchoption["function_name"] = implode(",", $this->f->getFunctionNameById($function_id));
                if (strlen($where_query) > 0) {
                    $where_query .= " and  function_id in (" . implode(",", $function_id) . ")";
                } else {
                    $where_query .= " function_id in (" . implode(",", $function_id) . ")";
                }
                
                
                $keymap .= " and  jobs.id=job_function.job_id ";
                $table[]            = "job_function";
                $empty_form_tracker = 1;
            }
            
            if (isset($save['experience_from_month']) and !empty($save['experience_from_month'])) {
                $from_month = $save['experience_from_month'];
                $to_month   = $save['experience_to_month'];
                if (!empty($from_month) && (!empty($to_month))) {
                    $data["experience"] = $from_month . "-" . $to_month;
                    if ($from_month > $to_month) {
                        $errorflag[] = 'Invalid Experience Range';
                    } else {
                        $searchoption["experience"] = $from_month . "-" . $to_month;
                        if (strlen($where_query) > 0) {
                            $where_query .= " and (  ( ( from_experince_in_month  between $from_month and  $to_month )  or  (   to_experince_in_month  between $to_month and  $to_month  ) )  or  ( (  $from_month   between from_experince_in_month and  to_experince_in_month ) or (  $to_month    between from_experince_in_month and  to_experince_in_month  ) )  )";
                        } else {
                            $where_query .= " (  ( ( from_experince_in_month  between $from_month and  $to_month )  or  (   to_experince_in_month  between $from_month and  $to_month  ) )  or  ( (  $from_month   between from_experince_in_month and  to_experince_in_month ) or (  $to_month    between from_experince_in_month and  to_experince_in_month  ) )  )";
                        }
                        
                        $empty_form_tracker = 1;
                    }
                }
            }
            
            if (!empty($save['ctc_start']) && !empty($save['ctc_end'])) {
                $empty_form_tracker = 1;
                if ($postdata['ctc_start'] > $postdata['ctc_end']) {
                    $errorflag[] = 'Invalid s Range';
                } else {
                    $searchoption["salary"] = $save['ctc_start'] . "-" . $save['ctc_end'];
                    $ctc_start              = $save['ctc_start'];
                    $ctc_end                = $save['ctc_end'];
                    if (strlen($where_query) > 0) {
                        $where_query .= " and (  ( ctc_Start between " . $ctc_start . " and " . $ctc_end . " )  or   ( ctc_end between " . $ctc_start . " and " . $ctc_end . "  ) ) ";
                    } else {
                        $where_query .= " (  ( ctc_Start between " . $ctc_start . " and " . $ctc_end . " )  or   ( ctc_end between " . $ctc_start . " and " . $ctc_end . "  ) ) ";
                    }
                }
            }
            if (isset($save['pre_loc_ids'])) {
                $emp_type_id = $save['emptype_ids'];
            }
            if (isset($emp_type_id) && (!empty($emp_type_id))) {
                if (count($emp_type_id) > 3) {
                    $errorflag[] = "Maximum three employment type are allowed";
                } else {
                    $searchoption["employment_types"] = implode(",", $this->emptype->getEmploymentType($emp_type_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and job_employment.employment_id in ( " . implode(",", $emp_type_id) . " )";
                    } else {
                        $where_query .= "job_employment.employment_id in (" . implode(",", $emp_type_id) . " )";
                    }
                    
                    $keymap .= " and  jobs.id=job_employment.job_id  ";
                    $table[]            = "job_employment";
                    $empty_form_tracker = 1;
                }
            }
            if (isset($save['qualification_ids'])) {
                $qualification_level = $save['qualification_ids'];
            }
            if (isset($qualification_level) && (!empty($qualification_level))) {
                $searchoption["qualification"] = implode(",", $this->qtype->getQualificationById($qualification_level));
                if (strlen($where_query) > 0) {
                    $where_query .= " and job_qualifications.qualification_id in (" . implode(",", $qualification_level) . ")";
                } else {
                    $where_query .= " job_qualifications.qualification_id in (" . implode(",", $qualification_level) . ")";
                }
                
                $keymap .= " and jobs.id=job_qualifications.job_id ";
                $table[]            = "job_qualifications";
                $empty_form_tracker = 1;
            }
            
            if (isset($save['qualification_ids'])) {
                $exp_level_id = $save['exp_level_ids'];
            }
            
            if (isset($exp_level_id) && (!empty($exp_level_id))) {
                $data["experience_level"] = implode(",", $this->elevel->getExperienceLevelById($exp_level_id));
                if (strlen($where_query) > 0) {
                    $where_query .= " and  job_experiencelevel.exp_level_id in (" . implode(",", $exp_level_id) . ")";
                } else {
                    $where_query .= " job_experiencelevel.exp_level_id in (" . implode(",", $exp_level_id) . ")";
                }
                
                $keymap .= " and  jobs.id=job_experiencelevel.job_id  ";
                $table[]            = "job_experiencelevel";
                $empty_form_tracker = 1;
            }
            
            $company = $save['company'];
            if (!empty($company) && isset($company) && ($company != "")) {
                $formdata['company']      = $company;
                $search_option["company"] = $company;
                
                if (strlen($where_query) > 0) {
                    $where_query .= " and   ";
                } else {
                    
                    $where_query .= "  ";
                }
                
                
                $company = preg_replace('/[ ]+/', ' ', trim($company));
                
                $temp  = explode(",", $company);
                $count = count($temp);
                $i     = 0;
                $where_query .= "( ";
                foreach ($temp as $val) {
                    $where_query .= "( organizations.organization_name like '%" . trim($val) . "%' or  jobs.company_name  like '%" . trim($val) . "%' ) ";
                    
                    $select_index_rank .= "+( organizations.organization_name like '%" . trim($val) . "%' or  jobs.company_name  like '%" . trim($val) . "%' )";
                    
                    if ($i < $count - 1) {
                        $where_query .= " or";
                    }
                    $i++;
                }
                $where_query .= " )";
                
                if (strlen($where_query) > 0) {
                    
                    $where_query .= "";
                }
                $empty_form_tracker = 1;
            }
            
            
            if (isset($location_id) && (!empty($location_id))) {
                $formdata['sel_loc']            = $location_id;
                $search_option["location_name"] = implode(",", $this->Locations->getLocationNameById($location_id));
                
                if (strlen($where_query) > 0) {
                    $where_query .= " and job_locations.city_id in (" . implode(",", $location_id) . " )";
                } else {
                    $where_query .= " job_locations.city_id in (" . implode(",", $location_id) . " )";
                }
                
                
                
                $empty_form_tracker = 1;
            }
            
            
            
            if (strlen($where_query) > 0) {
                $where_query .= " and  jobs.id in (" . implode(",", $postdata['filterJobs']) . ")";
            } else {
                $where_query .= " jobs.id in (" . implode(",", $postdata['filterJobs']) . ")";
            }
            
            if ($empty_form_tracker == 1) {
                $where_query       = "   " . $where_query;
                $this->u->postdata = $postdata;
                $result            = $this->u->advanceSearch($where_query, $table, $keymap);
                
                if (!is_bool($result)) {
                    
                    $offset = 0;
                    if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                        $offset = $postdata['offset'];
                    }
                    if (count($result) > 0) {
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
                        
                    }
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
                        } else {
                            foreach ($result as $key => $job) {
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
                                
                                if (in_array($job['id'], $applied_job_ids)) {
                                    $job['apply_status'] = "Applied";
                                } else {
                                    $job['apply_status'] = "Apply";
                                }
                                
                                $result[$key] = $job;
                            }
                        }
                        
                        $dataArray['status']  = true;
                        $dataArray['joblist'] = $result;
                        
                        
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "No jobs found matching to your criteria ";
                    }
                    
                    
                } 
            }
            else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "Please specify atleast one search criteria ";
                    
                }
            
            
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function getStateListByCountryId()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['country_id']) && !empty($postdata['country_id'])) {
                
                // $result = $this->loc->getCityListByCountryId($postdata['country_id']);
                
                $result = $this->Locations->getStateList($postdata['country_id']);
                array_push($result, array(
                    'location_id' => '0',
                    'location_name' => 'NA'
                ));
                if (count($result) > 0) {
                    $dataArray['status']    = true;
                    $dataArray['statelist'] = $result;
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "State list not added";
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Please provide country id";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function getCityListByStateId()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['state_id']) && !empty($postdata['state_id'])) {
                $result = $this->Locations->getCityListByStateId($postdata['state_id']);
                array_push($result, array(
                    'location_id' => '0',
                    'location_name' => 'NA'
                ));
                if (count($result) > 0) {
                    $dataArray['status']   = true;
                    $dataArray['citylist'] = $result;
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "City list not added";
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Please provide state id";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function getOption()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['option_name']) && !empty($postdata['option_name'])) {
                switch ($postdata['option_name']) {
                    case "all":
                        $dataArray["industrylist"]       = $this->Industry->getIndustryList(1);
                        $dataArray["functionlist"]       = $this->getallselectresults->ajax_functions_list();
                        $dataArray["emptypelist"]        = $this->emptype->getEmploymentTypeList();
                        $dataArray["locationlist"]       = $this->Locations->getCityList(0, 1);
                        $dataArray["expriencelevellist"] = $this->elevel->getExperienceLevelList(1);
                        $dataArray["qualificationlist"]  = $this->qtype->getQualificationList(1);
                        $exp_temp                        = range(0, 30);
                        $dataArray['skill_last_used ']   = range(1970, date("Y"));
                        $dataArray['annual_salary_key '] = range(0, 50);
                        
                        /*$dataArray['from_experience_range'] =   $this->home_model->experience_years(); */
                        $dataArray['exprange']        = $exp_temp;
                        $dataArray["languagelist"]    = $this->getallselectresults->load_language_selectbox();
                        $dataArray['proficiencylist'] = array(
                            "Beginner",
                            "Intermediate",
                            "Expert"
                        );
                        
                        $dataArray['reasonlist'] = $this->createprofileinsert->getAllChangeReason(1);
                        
                        
                        $dataArray['yearrange']          = range(1995, date("Y"));
                        $dataArray['salaryrange']        = range(0, 50);
                        $dataArray["alertfrequencylist"] = array(
                            array(
                                "function_id" => 1,
                                "function_job_category" => "Daily"
                            ),
                            array(
                                "function_id" => 2,
                                "function_job_category" => "Weekly"
                            ),
                            array(
                                "function_id" => 3,
                                "function_job_category" => "Monthly"
                            )
                        );
                        $dataArray['countrylist']        = $this->createprofileinsert->getAllcountries(1);
                        $dataArray['aboutusurl']         = base_url() . "home/about_us";
                        $dataArray['faqurl']             = base_url() . "home/faq";
                        $dataArray['termcondition']      = base_url() . "home/terms_and_condition";
                        if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                            $query                          = "select * from users where id=" . $postdata['user_id'];
                            $result                         = $this->db->query($query)->row_array();
                            $userdata['id']                 = $result['id'];
                            $profiledata['first_name']      = $userdata['first_name'] = $result['first_name'];
                            $profiledata['last_name']       = $userdata['last_name'] = $result['last_name'];
                            $profiledata['email_id']        = $userdata['email_id'] = $result['email_id'];
                            $userdata['profile_image_path'] = isset($result['profile_pic']) ? base_url() . PROFILE_IMAGES . "/" . $result['profile_pic'] : " ";
                            $profiledata['mobile_no']       = isset($result['mobile_no']) ? $result['mobile_no'] : " ";
                            if (isset($result['gender'])) {
                                $profiledata['gender'] = $result['gender'];
                                if ($result['gender'] == 1) {
                                    $profiledata['gender_name'] = "Male";
                                } else if ($result['gender'] == 2) {
                                    $profiledata['gender_name'] = "Female";
                                }
                            }
                            
                            $profiledata['dob'] = isset($result['dob']) ? $result['dob'] : "";
                            if (isset($result['role']) && !empty($result['role'])) {
                                $profiledata['role_ids']  = $result['role'];
                                $profiledata['role_name'] = implode(",", $this->fjc->getFunctionNameById(explode(",", $result['role'])));
                            } else {
                                $profiledata['role_ids']  = "";
                                $profiledata['role_name'] = "";
                            }
                            
                            if (isset($result['current_country_id']) && !empty($result['current_country_id'])) {
                                $profiledata['current_country_id']   = $result['current_country_id'];
                                $profiledata['current_country_name'] = $this->getallselectresults->fetchcountryname($result['current_country_id']);
                            } else {
                                $profiledata['current_country_id']   = "";
                                $profiledata['current_country_name'] = "";
                            }
                            
                            if (isset($result['current_state_id']) && !empty($result['current_state_id'])) {
                                $current_country_id                = $result['current_country_id'];
                                $profiledata['current_state_id']   = $result['current_state_id'];
                                $profiledata['current_state_name'] = $this->getallselectresults->fetchstatename($current_country_id, $result['current_state_id']);
                            } else {
                                $profiledata['current_state_id']   = "";
                                $profiledata['current_state_name'] = "";
                            }
                            
                            if (isset($result['current_city_id']) && !empty($result['current_city_id'])) {
                                $profiledata['current_city_id']   = $result['current_city_id'];
                                $current_country_id               = $result['current_country_id'];
                                $current_state_id                 = $result['current_state_id'];
                                $profiledata['current_city_name'] = $this->getallselectresults->fetchcityname($current_country_id, $current_state_id, $profiledata['current_city_id']);
                            } else {
                                $profiledata['current_city_id']   = "";
                                $profiledata['current_city_name'] = "";
                            }
                            
                            $query   = "select id,skill_name,experince_in_month as experience_in_years,level_of_proficiency  as skill_level from user_skills where user_id=" . $postdata['user_id'];
                            $sresult = $this->db->query($query)->result_array();
                            if (!is_bool($sresult)) {
                                $profiledata['skill']['skill'] = $sresult;
                            } else {
                                $profiledata['skill'] = "";
                            }
                            
                            $employment_id_str = "";
                            $query             = "select * from user_employment where user_id=" . $postdata['user_id'];
                            $result2           = $this->db->query($query)->result_array();
                            
                            if (count($result2) > 0) {
                                foreach ($result2 as $key => $value) {
                                    $employment_id_str .= $value['employment_id'] . ",";
                                }
                            }
                            $expected_employment = trim($employment_id_str, ",");
                            
                            if (isset($expected_employment) && !empty($expected_employment)) {
                                $profiledata['emptype_ids'] = $expected_employment;
                                $qua                        = $this->emptype->getEmploymentType(explode(",", $expected_employment));
                                if (count($qua) > 0) {
                                    $profiledata["employee_type_name"] = implode(',', $qua);
                                } else {
                                    $profiledata['employee_type_name'] = "";
                                }
                            } else {
                                $profiledata['employee_type_name'] = "";
                                $profiledata['emptype_ids']        = "";
                            }
                            
                            $query   = "select * from user_profile_summary where user_id=" . $postdata['user_id'];
                            $result1 = $this->db->query($query)->row_array();
                            
                            
                            if (isset($result1['function']) && !empty($result1['function'])) {
                                $profiledata['function_category_id'] = $result1['function'];
                                $industry                            = "";
                                $function_category_name              = $this->f->getFunctionNameById($result1['function']);
                                if (count($function_category_name) > 0) {
                                    $profiledata['function_category_name'] = implode(',', $function_category_name);
                                } else {
                                    $profiledata['function_category_name'] = "";
                                }
                            } else {
                                $profiledata['function_category_name'] = "";
                                $profiledata['function_category_id']   = "";
                            }
                            
                            if (isset($result1['is_working']) && $result1['is_working'] == 1) {
                                $profiledata['currently_working_no']  = false;
                                $profiledata['currently_working_yes'] = true;
                                
                                if (isset($result1['is_noticeperiod']) && $result1['is_noticeperiod'] == 1) {
                                    $profiledata['currently_working']    = false;
                                    $profiledata['already_resigned_yes'] = true;
                                } else {
                                    $profiledata['already_resigned_no']  = true;
                                    $profiledata['already_resigned_yes'] = false;
                                }
                            } else {
                                $profiledata['currently_working_no']  = true;
                                $profiledata['currently_working_yes'] = false;
                                $profiledata['already_resigned_no']   = false;
                                $profiledata['already_resigned_yes']  = false;
                            }
                            
                            if (isset($result1['is_immediate_joining']) && $result1['is_immediate_joining'] == 1) {
                                $profiledata['Immdiately_check'] = true;
                            } else {
                                $profiledata['Immdiately_check'] = false;
                            }
                            
                            if (isset($result1['reason_for_change']) && !empty($result1['reason_for_change'])) {
                                $reason_for_change        = $result1['reason_for_change'];
                                $profiledata['reason_id'] = $reason_for_change;
                                
                                $query      = "select * from changefor_reason where reason_id in ( " . $reason_for_change . " )";
                                $result3    = $this->db->query($query)->result_array();
                                $reason_str = "";
                                if (count($result3) > 0) {
                                    foreach ($result3 as $key => $value) {
                                        $reason_str .= $value['reason'] . ",";
                                    }
                                }
                                $reason_str = trim($reason_str, ",");
                                
                                $profiledata['reason_for_change'] = $reason_str;
                                
                            } else {
                                $profiledata['reason_for_change'] = "";
                                $profiledata['reason_id']         = "";
                                
                            }
                            
                            if (isset($result1['reason_description']) && !empty($result1['reason_description'])) {
                                $profiledata['description'] = $result1['reason_description'];
                            } else {
                                $profiledata['description'] = "";
                            }
                            
                            if (isset($result1['date_of_joining']) && !empty($result1['date_of_joining']) && $result1['date_of_joining'] != "0000-00-00") {
                                $profiledata['employer_join_date'] = date('d-m-Y', strtotime($result1['date_of_joining']));
                            } else {
                                $profiledata['employer_join_date'] = "";
                            }
                            
                            if (isset($result1['notice_period_in_days']) && !empty($result1['notice_period_in_days'])) {
                                $profiledata['notice_period_days'] = $result1['notice_period_in_days'];
                            } else {
                                $profiledata['notice_period_days'] = "";
                            }
                            
                            
                            
                            
                            if (isset($result1['industry']) && !empty($result1['industry'])) {
                                $profiledata['last_ctc_key']     = $result1['current_ctc'];
                                $profiledata['expected_ctc_key'] = $result1['expected_ctc'];
                            } else {
                                $profiledata['last_ctc_key']     = "";
                                $profiledata['expected_ctc_key'] = "";
                            }
                            
                            
                            if (isset($result1['industry']) && !empty($result1['industry'])) {
                                $profiledata['industry_ids'] = $result1['industry'];
                                $industry_name               = $this->f->getIndustryNameById($result1['industry']);
                                if (count($industry_name) > 0) {
                                    $profiledata['industry_name'] = implode(',', $industry_name);
                                } else {
                                    $profiledata['industry_name'] = "";
                                }
                            } else {
                                $profiledata['industry_ids']  = "";
                                $profiledata['industry_name'] = "";
                            }
                            
                            if (isset($result1['total_experience'])) {
                                $workexperience = $result1['total_experience'];
                                $work_exp       = explode(".", $workexperience);
                                $experience     = 0;
                                if (isset($work_exp[0])) {
                                    $experience = $exp1 = $work_exp[0] * 12;
                                }
                                if (isset($work_exp[1])) {
                                    $experience = $experience + $work_exp[1];
                                }
                                
                                $profiledata['workexperience'] = (string) $experience;
                            }
                            
                            $profiledata['is_fresher'] = isset($result1['is_fresher']) ? $result1['is_fresher'] : "";
                            if (isset($result1["upload_resume_file"]) && !empty($result1["upload_resume_file"])) {
                                $profiledata['resume_file_name']          = $result1["upload_resume_file"];
                                $profiledata['original_resume_file_name'] = $result1["upload_resume_file"];
                                $profiledata['resume_path']               = base_url() . RESUME_FOLDER . $result1['upload_resume_file'];
                            } else {
                                $profiledata['resume_file_name']         = "";
                                $profiledata['orginal_resume_file_name'] = "";
                                $profiledata['resume_path']              = "";
                            }
                            
                            $dataArray['status']      = true;
                            $dataArray['userdata']    = $userdata;
                            $dataArray['profiledata'] = $profiledata;
                        }
                        
                        $skill_array = array();
                        $query       = "select skill_name from skill_masters order by skill_name";
                        $result      = $this->db->query($query)->result_array();
                        foreach ($result as $skill) {
                            $skill_array[] = $skill['skill_name'];
                        }
                        
                        $dataArray['skilllist'] = "";
                        break;
                    
                    case "industrylist":
                        $dataArray["industrylist"] = $this->industry->getIndustryList(1);
                        break;
                    
                    case "functionlist":
                        $dataArray["functionlist"] = $this->getallselectresults->ajax_functions_list();
                        break;
                    
                    case "emptypelist":
                        $dataArray["emptypelist"] = $this->emptype->getEmploymentTypeList();
                        break;
                    
                    case "locationlist":
                        $dataArray["locationlist"] = $this->Locations->getCityList(0, 1);
                        break;
                    
                    case "expriencelevellist":
                        $dataArray["expriencelevellist"] = $this->elevel->getExperienceLevelList(1);
                        break;
                    
                    case "qualificationlist":
                        $dataArray["qualificationlist"] = $this->qtype->getQualificationList(1);
                        break;
                    
                    case "exprange":
                        $temp                  = range(1, 25);
                        $dataArray['exprange'] = $this->home_model->experience_years();
                        break;
                    
                    case "languagelist":
                        $dataArray["languagelist"] = $this->getallselectresults->load_language_selectbox();
                        break;
                    
                    case "proficiencylist":
                        $dataArray['proficiencylist'] = array(
                            "Beginner",
                            "Intermediate",
                            "Expert"
                        );
                        break;
                    
                    case "yearrange":
                        $dataArray['yearrange'] = range(1995, date("Y"));
                        break;
                    
                    case "salaryrange":
                        $dataArray['salaryrange'] = $this->home_model->salary_master();
                        break;
                    
                    case "countrylist":
                        $dataArray['countrylist'] = $this->createprofileinsert->getAllcountries();
                        break;
                    
                    case "userdata":
                        if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                            $query                          = "select * from users where id=" . $postdata['user_id'];
                            $result                         = $this->db->query($query)->row_array();
                            $userdata['id']                 = $result['id'];
                            $userdata['first_name']         = $result['first_name'];
                            $userdata['last_name']          = $result['last_name'];
                            $userdata['email_id']           = $result['email_id'];
                            $userdata['profile_image_path'] = isset($result['profile_pic']) ? base_url() . PROFILE_IMAGES . "/" . $result['profile_pic'] : " ";
                            $userdata['mobile_no']          = isset($result['mobile_no']) ? $result['mobile_no'] : " ";
                            $dataArray['status']            = true;
                            $dataArray['userdata']          = $userdata;
                        }
                        
                        break;
                }
                
                if (count($dataArray) == 0) {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No any record exists";
                } else {
                    $dataArray['status'] = true;
                }
            } else {
                $dataArray['status']  = true;
                $dataArray['message'] = "Please specify option";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function salaryRangeService()
    {
        $salaryranges = array();
        $temp         = range(0, 50, 1);
        for ($i = 0; $i < count($temp) - 1; $i++) {
            $salaryranges[] = array(
                "salaryrange_name" => $i . "k",
                "salaryrange_id" => $temp[$i] * 1000
            );
        }
        
        return $salaryranges;
    }
    
    public function getSpecializationByQualification()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['q_id']) && !empty($postdata['q_id'])) {
                $qulification_id = $postdata['q_id'];
                $this->db->select("id,name");
                $this->db->where(array(
                    "qualification_id" => $qulification_id
                ));
                $result = $this->db->get("specialization_types")->result_array();
                if (count($result) > 0) {
                    $dataArray['status']                 = true;
                    $dataArray['specializationtypelist'] = $result;
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No result found";
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "Qualification id not provided";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function getRoleFromFunctionID()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['parent_id']) && !empty($postdata['parent_id'])) {
                $result = $this->fjc->getChildFunctionList($postdata['parent_id']);
                if (count($result) > 0) {
                    $dataArray['status']            = true;
                    $dataArray['childfunctionlist'] = $result;
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No result found";
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = " Parent function id not provided";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function applyJob()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if (isset($postdata['job_id']) && !empty($postdata['job_id'])) {
                    $applied_job_ids = $this->Jobs->getMyAppliedJobIds($postdata['user_id']);
                    if (!in_array($postdata['job_id'], $applied_job_ids)) {
                        $resume             = $this->u->isUserUploadResume($postdata['user_id']);
                        $data               = array();
                        $data['created_on'] = date('Y-m-d h-i-s');
                        $data['updated_on'] = date('Y-m-d h-i-s');
                        $data['applied_on'] = date('Y-m-d h-i-s');
                        $data['created_by'] = $postdata['user_id'];
                        $data['job_id']     = $postdata['job_id'];
                        $data['user_id']    = $postdata['user_id'];
                        if (isset($resume->upload_resume_file) and !is_null($resume->upload_resume_file)) {
                            if ($this->u->applyJob($data)) {
                                $dataArray['status']  = true;
                                $dataArray['message'] = "You are successfully applied to this jobs";
                            } else {
                                $dataArray['status']  = false;
                                $dataArray['message'] = "Something went wrong try again";
                            }
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Please upload your resume";
                        }
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "You have already applied to this job";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No any job selected";
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
    
    public function bulkapplyJobs()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if (isset($postdata['job_ids']) && !empty($postdata['job_ids'])) {
                    $applied_job_ids = $this->j->getMyAppliedJobIds($postdata['user_id']);
                    $jobids          = array_diff($postdata['job_ids'], $applied_job_ids);
                    if (count($jobids) > 0) {
                        $temp = array();
                        foreach ($jobids as $jid) {
                            $data               = array();
                            $data['created_on'] = date('Y-m-d h-i-s');
                            $data['updated_on'] = date('Y-m-d h-i-s');
                            $data['applied_on'] = date('Y-m-d h-i-s');
                            $data['created_by'] = $postdata['user_id'];
                            $data['job_id']     = $jid;
                            $data['user_id']    = $postdata['user_id'];
                            $temp[]             = $data;
                        }
                        
                        if ($this->u->bulkApply($temp)) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "You are successfully applied to these jobs";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = " Already applied to all these job";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No any job selected";
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
    
    public function bulkSavedJobs()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                if (isset($postdata['job_ids']) && !empty($postdata['job_ids'])) {
                    $savejids = $this->j->getMySavedJobIds($postdata['user_id']);
                    $jobids   = array_diff($postdata['job_ids'], $savejids);
                    if (count($jobids) > 0) {
                        $temp = array();
                        foreach ($jobids as $jid) {
                            $data               = array();
                            $data['updated_on'] = date('Y-m-d h-i-s');
                            $data['job_id']     = $jid;
                            $data['user_id']    = $postdata['user_id'];
                            $temp[]             = $data;
                        }
                        
                        if ($this->u->bulksave($temp)) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "You are successfully saved to these jobs";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "Already saved all these job";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "No any job selected";
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
    
    public function getAppliedJobList()
    {
        $dataArray         = array();
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $industry_ids      = array();
        $function_ids      = array();
        $formdata          = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                $applied_job_ids = $this->Jobs->getMyAppliedJobIds($postdata['user_id']);
                if (count($applied_job_ids) > 0) {
                    $query  = "     and jobs.id in (" . implode(",", $applied_job_ids) . ") and applied_jobs.user_id=" . $postdata['user_id'];
                    $result = $this->u->getApplyJobList($query);
                    
                    if (count($result) > 0) {
                        $offset = 0;
                        if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                            $offset = $postdata['offset'];
                        }
                        
                        $dataArray['total_record'] = count($result);
                        $result                    = array_slice($result, $offset, $this->per_page);
                        if (count($result) > 0) {
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
                                
                                $job['sharedatalink'] = base_url() . "search/getJobDetailsById/" . $job['unique_id'];
                                if ($job['is_third_party_hiring'] == 0) {
                                    $job['company_logo'] = base_url() . comany_logo_images . $job['company_logo'];
                                } else {
                                    $job['company_logo'] = base_url() . comany_logo_images . $job['company_logo'];
                                }
                                
                                $job["description"]   = html_entity_decode(strip_tags($job["description"]));
                                $job["about_company"] = html_entity_decode(strip_tags($job["about_company"]));
                                $query                = " select name from employment_types,jobs,job_employment where jobs.id=job_employment.job_id and employment_types.id=job_employment.employment_id and jobs.id=" . $j_id . " GROUP by employment_types.id";
                                $re                   = $this->db->query($query);
                                $this->query_execute  = $this->db->last_query();
                                $temp                 = array();
                                foreach ($re->result_array() as $e_type) {
                                    $temp[] = $e_type['name'];
                                }
                                
                                $job['emp_types']    = implode("/", $temp);
                                $query               = "select function_job_category as  name 
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
                        }
                        
                        $dataArray['status']  = true;
                        $dataArray['joblist'] = $result;
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "Something went wrong try again ";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "You don't have  applied  to single job ";
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
    
    public function saveAlert()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                $save                             = $postdata;
                $searchoption                     = array();
                $searchoption['keywords']         = "";
                $searchoption['industry_name']    = "";
                $searchoption["function_name"]    = "";
                $searchoption["experience"]       = "";
                $searchoption["salary"]           = "";
                $searchoption["employment_types"] = "";
                $searchoption["qualification"]    = "";
                $searchoption["experience_level"] = "";
                $searchoption["company"]          = "";
                $searchoption["location_name"]    = "";
                $errorflag                        = array();
                $arrCommonKeyword                 = array();
                $where_query                      = "";
                $empty_form_tracker               = 0;
                $dataArray                        = array();
                $query                            = "";
                $table[]                          = "jobs";
                $table[]                          = "organizations";
                $table[]                          = "locations";
                $table[]                          = " job_locations";
                $location_id                      = $save['pre_loc_ids'];
                if (isset($location_id) && (!empty($location_id))) {
                    $searchoption['location_name'] = $save['pre_loc_name'];
                    
                    $keymap = "jobs.organization_id=organizations.id  and ( job_locations.job_id = jobs.id and job_locations.city_id = locations.location_id ) ";
                } else {
                    $keymap = "jobs.organization_id=organizations.id  and ( job_locations.job_id = jobs.id or job_locations.city_id = locations.location_id or job_locations.country_id = locations.location_id or job_locations.state_id = locations.location_id ) ";
                }
                
                if (isset($save['frequency']) && !empty($save['frequency'])) {
                    $searchoption['frequency'] = $save['frequency'];
                } else {
                    $errorflag[] = "Alert frequency not selected";
                }
                
                if (empty($save["alert_name"]) && !isset($save["alert_name"])) {
                    $errorflag[] = "Enter alert name";
                } else {
                    $searchoption['name'] = $save['alert_name'];
                }
                
                $where_query = "";
                if (!empty($save['keyword']) && (isset($save['keyword']))) {
                    $searchoption['atleast_one_word'] = $save['keyword'];
                    $all_words                        = $save['keyword'];
                    $formdata['a_w']                  = $all_words;
                    $data['all_these_words']          = $all_words;
                    $all_words                        = preg_replace('/[ ]+/', ' ', trim($all_words));
                    $arrCommonKeyword                 = explode(",", $all_words);
                    $formfieldtracker                 = 1;
                }
                
                if (isset($arrCommonKeyword) and count($arrCommonKeyword) > 0) {
                    if (strlen($where_query) > 0) {
                        $where_query .= "and (";
                    } else {
                        $where_query .= "(";
                    }
                    
                    if (count($arrCommonKeyword) > 1) {
                        for ($i = 0; $i < count($arrCommonKeyword); $i++) {
                            $where_query .= " ( (title  LIKE '%" . $arrCommonKeyword[$i] . "%') ";
                            $where_query .= " or ( required_skill  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                            $where_query .= " or ( about_company  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                            $where_query .= " or ( company_name  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                            $where_query .= " or ( description  LIKE '%" . $arrCommonKeyword[$i] . "%' ) )";
                            if ($i != count($arrCommonKeyword) - 1) {
                                $where_query .= "   or";
                            }
                        }
                        
                        $where_query .= ")";
                    } else {
                        $where_query .= " ( (title  LIKE '%" . implode("|", $arrCommonKeyword) . "%') ";
                        $where_query .= " or ( about_company  LIKE '%" . implode("|", $arrCommonKeyword) . "%' )  ";
                        $where_query .= " or ( company_name  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ";
                        $where_query .= " or ( description  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ";
                        $where_query .= " or ( required_skill  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ) )";
                    }
                    
                    $empty_form_tracker = 1;
                }
                
                if (isset($save['industry_ids']) && (!empty($save['industry_ids']))) {
                    $industry_id                   = $save['industry_ids'];
                    $formdata['sel_industry']      = $industry_id;
                    $searchoption["industry_name"] = implode(",", $this->Industry->getIndustryNameById($industry_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and  industry_id in  (" . implode(",", $industry_id) . ")";
                    } else {
                        $where_query .= "  industry_id in  (" . implode(",", $industry_id) . ")";
                    }
                    
                    $empty_form_tracker = 1;
                }
                
                if (isset($save['function_id']) && !empty($save['function_id'])) {
                    $function_id                   = $save['function_id'];
                    $searchoption["function_name"] = implode(",", $this->f->getFunctionNameById($function_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and  function_id in (" . implode(",", $function_id) . ")";
                    } else {
                        $where_query .= " function_id in (" . implode(",", $function_id) . ")";
                    }
                    
                    $keymap .= " and  jobs.id=job_function.job_id ";
                    $table[]            = "job_function";
                    $empty_form_tracker = 1;
                }
                
                if (isset($save['experience_from_month']) and !empty($save['experience_from_month'])) {
                    $from_month = $save['experience_from_month'];
                    $to_month   = $save['experience_to_month'];
                    if (!empty($from_month) && (!empty($to_month))) {
                        $data["experience"] = $from_month . "-" . $to_month;
                        if ($from_month > $to_month) {
                            $errorflag[] = 'Invalid Experience Range';
                        } else {
                            $searchoption["experience"] = $from_month . "-" . $to_month;
                            if (strlen($where_query) > 0) {
                                $where_query .= " and (  ( ( from_experince_in_month  between $from_month and  $to_month )  or  (   to_experince_in_month  between $to_month and  $to_month  ) )  or  ( (  $from_month   between from_experince_in_month and  to_experince_in_month ) or (  $to_month    between from_experince_in_month and  to_experince_in_month  ) )  )";
                            } else {
                                $where_query .= " (  ( ( from_experince_in_month  between $from_month and  $to_month )  or  (   to_experince_in_month  between $from_month and  $to_month  ) )  or  ( (  $from_month   between from_experince_in_month and  to_experince_in_month ) or (  $to_month    between from_experince_in_month and  to_experince_in_month  ) )  )";
                            }
                            
                            $empty_form_tracker = 1;
                        }
                    }
                }
                
                if (!empty($save['ctc_start']) && !empty($save['ctc_end'])) {
                    $empty_form_tracker = 1;
                    if ($postdata['ctc_start'] > $postdata['ctc_end']) {
                        $errorflag[] = 'Invalid s Range';
                    } else {
                        $searchoption["salary"] = $save['ctc_start'] . "-" . $save['ctc_end'];
                        $ctc_start              = $save['ctc_start'];
                        $ctc_end                = $save['ctc_end'];
                        if (strlen($where_query) > 0) {
                            $where_query .= " and (  ( ctc_Start between " . $ctc_start . " and " . $ctc_end . " )  or   ( ctc_end between " . $ctc_start . " and " . $ctc_end . "  ) ) ";
                        } else {
                            $where_query .= " (  ( ctc_Start between " . $ctc_start . " and " . $ctc_end . " )  or   ( ctc_end between " . $ctc_start . " and " . $ctc_end . "  ) ) ";
                        }
                    }
                }
                
                $emp_type_id = $save['emptype_ids'];
                if (isset($emp_type_id) && (!empty($emp_type_id))) {
                    if (count($emp_type_id) > 3) {
                        $errorflag[] = "Maximum three employment type are allowed";
                    } else {
                        $searchoption["employment_types"] = implode(",", $this->emptype->getEmploymentType($emp_type_id));
                        if (strlen($where_query) > 0) {
                            $where_query .= " and job_employment.employment_id in ( " . implode(",", $emp_type_id) . " )";
                        } else {
                            $where_query .= "job_employment.employment_id in (" . implode(",", $emp_type_id) . " )";
                        }
                        
                        $keymap .= " and  jobs.id=job_employment.job_id  ";
                        $table[]            = "job_employment";
                        $empty_form_tracker = 1;
                    }
                }
                
                $qualification_level = $save['qualification_ids'];
                if (isset($qualification_level) && (!empty($qualification_level))) {
                    $searchoption["qualification"] = implode(",", $this->qtype->getQualificationById($qualification_level));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and job_qualifications.qualification_id in (" . implode(",", $qualification_level) . ")";
                    } else {
                        $where_query .= " job_qualifications.qualification_id in (" . implode(",", $qualification_level) . ")";
                    }
                    
                    $keymap .= " and jobs.id=job_qualifications.job_id ";
                    $table[]            = "job_qualifications";
                    $empty_form_tracker = 1;
                }
                
                $exp_level_id = $save['exp_level_ids'];
                if (isset($exp_level_id) && (!empty($exp_level_id))) {
                    $data["experience_level"] = implode(",", $this->elevel->getExperienceLevelById($exp_level_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and  job_experiencelevel.exp_level_id in (" . implode(",", $exp_level_id) . ")";
                    } else {
                        $where_query .= " job_experiencelevel.exp_level_id in (" . implode(",", $exp_level_id) . ")";
                    }
                    
                    $keymap .= " and  jobs.id=job_experiencelevel.job_id  ";
                    $table[]            = "job_experiencelevel";
                    $empty_form_tracker = 1;
                }
                
                $company = $save['company'];
                if (!empty($company) && isset($company) && ($company != "")) {
                    $searchoption["company"] = $company;
                    if (strlen($where_query) > 0) {
                        $where_query .= " and   ";
                    } else {
                        $where_query .= " ";
                    }
                }
                $location_id = $save['pre_loc_ids'];
                
                if (isset($location_id) && (!empty($location_id))) {
                    $formdata['sel_loc']            = $location_id;
                    $search_option["location_name"] = implode(",", $this->Locations->getLocationNameById($location_id));
                    
                    if (strlen($where_query) > 0) {
                        $where_query .= " and job_locations.city_id in (" . implode(",", $location_id) . " )";
                    } else {
                        $where_query .= " job_locations.city_id in (" . implode(",", $location_id) . " )";
                    }
                    
                    
                    
                    $empty_form_tracker = 1;
                }
                
                
                $where_query            = trim($where_query);
                $data['jdetails']       = $this->Jobs->getAdvanceJobDetails($where_query, $table, $keymap);
                $query                  = $this->db->last_query();
                $searchname             = $this->input->post("name");
                $formdata['alert_name'] = $searchname;
                if ($empty_form_tracker == 0 || count($errorflag) > 0) {
                    $dataArray['status'] = false;
                    if ($empty_form_tracker == 0) {
                        $dataArray['message'] = "Atlest one search criteria need to specify";
                    } else if (count($errorflag) > 0) {
                        $dataArray['message'] = $errorflag[0];
                    }
                    
                    // echo json_encode($dataArray);
                    
                } else {
                    $wq                = "  " . $where_query;
                    $this->u->postdata = $save;
                    $this->db->insert("query", array(
                        "query" => $query
                    ));
                    if (isset($save['unique_id']) && !empty($save['unique_id'])) {
                        if ($this->u->saveJobsAlert($searchoption, $save['unique_id'], $save["user_id"])) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Alert updated  sucessfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Someting went wrong try again";
                        }
                    } else {
                        $searchoption['user_id'] = $save["user_id"];
                        if ($this->u->saveJobsAlert($searchoption)) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Created succesfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Someting went wrong try again";
                        }
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
    
    public function saveSearch()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                $searchoption                     = array();
                $searchoption['industry_name']    = "";
                $searchoption["function_name"]    = "";
                $searchoption["experience"]       = "";
                $searchoption["salary"]           = "";
                $searchoption["employment_types"] = "";
                $searchoption["qualification"]    = "";
                $searchoption["experience_level"] = "";
                $searchoption["company"]          = "";
                $searchoption["location_name"]    = "";
                $errorflag                        = array();
                $arrCommonKeyword                 = array();
                $empty_form_tracker               = 0;
                $save                             = $postdata;
                unset($save['skills']);
                $dataArray        = array();
                $query            = "";
                $error            = array();
                $update           = $this->input->post("update");
                $arrCommonKeyword = array();
                $table[]          = "jobs";
                $table[]          = "organizations";
                $table[]          = "locations";
                $table[]          = "job_locations";
                $location_id      = $this->input->post('location_id');
                if (isset($location_id) && (!empty($location_id))) {
                    $keymap = "jobs.organization_id=organizations.id and ( job_locations.job_id = jobs.id and job_locations.city_id = locations.location_id )  ";
                } else {
                    $keymap = "jobs.organization_id=organizations.id and ( job_locations.job_id = jobs.id or job_locations.city_id = locations.location_id or job_locations.country_id = locations.location_id or job_locations.state_id = locations.location_id )  ";
                }
                
                $select_part = " select distinct jobs.id as j_id,jobs.state_id as jstate_id,jobs.unique_id as u_id,jobs.city_id as jcity_id, jobs.company_logo as company_logo_job ,jobs.posted_on as job_created_date,locations.*,jobs.id as j_id, jobs.state_id as jstate_id,jobs.city_id as jcity_id,jobs.unique_id as u_id,jobs.is_company_disclose as is_company_disclose,jobs.is_third_party_hiring as is_third_party_hiring,jobs.company_logo as company_logo_job ,jobs.posted_on as job_created_date ,jobs.*,organizations.*";
                $where_query = "";
                /*  if(isset($save['frequency']) && !empty($save['frequency']))
                {
                $searchoption['frequency']=$save['frequency'];
                }
                else
                {
                $errorflag[]="alert frequency not selected";
                } */
                if (empty($save["search_name"]) && !isset($save["search_name"])) {
                    $errorflag[] = "enter search name";
                } else {
                    /* if (!preg_match('/^[A-Za-z ]{3,}$/',$save['alert_name']))
                    {
                    $errorflag[]="enter only character";
                    }
                    else
                    {
                    $searchoption['name']=$save['alert_name'];
                    } */
                    $searchoption['name'] = $save['search_name'];
                }
                
                $where_query = "";
                if (!empty($save['keyword']) && (isset($save['keyword']))) {
                    $searchoption['atleast_one_word'] = $save['keyword'];
                    $all_words                        = $save['keyword'];
                    $formdata['a_w']                  = $all_words;
                    $data['all_these_words']          = $all_words;
                    $all_words                        = preg_replace('/[ ]+/', ' ', trim($all_words));
                    $arrCommonKeyword                 = explode(",", $all_words);
                    $formfieldtracker                 = 1;
                }
                
                if (isset($arrCommonKeyword) and count($arrCommonKeyword) > 0) {
                    if (strlen($where_query) > 0) {
                        $where_query .= "and (";
                    } else {
                        $where_query .= "(";
                    }
                    
                    if (count($arrCommonKeyword) > 1) {
                        for ($i = 0; $i < count($arrCommonKeyword); $i++) {
                            $where_query .= " ( (title  LIKE '%" . $arrCommonKeyword[$i] . "%') ";
                            $where_query .= " or ( required_skill  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                            $where_query .= " or ( about_company  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                            $where_query .= " or ( company_name  LIKE '%" . $arrCommonKeyword[$i] . "%' ) ";
                            $where_query .= " or ( description  LIKE '%" . $arrCommonKeyword[$i] . "%' ) )";
                            if ($i != count($arrCommonKeyword) - 1) {
                                $where_query .= "   or";
                            }
                        }
                        
                        $where_query .= ")";
                    } else {
                        $where_query .= " ( (title  LIKE '%" . implode("|", $arrCommonKeyword) . "%') ";
                        $where_query .= " or ( about_company  LIKE '%" . implode("|", $arrCommonKeyword) . "%' )  ";
                        $where_query .= " or ( company_name  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ";
                        $where_query .= " or ( description  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ";
                        $where_query .= " or ( required_skill  LIKE '%" . implode("|", $arrCommonKeyword) . "%' ) ) )";
                    }
                    
                    $empty_form_tracker = 1;
                }
                
                if (isset($save['industry_ids']) && (!empty($save['industry_ids']))) {
                    $industry_id                   = $save['industry_ids'];
                    $formdata['sel_industry']      = $industry_id;
                    $searchoption["industry_name"] = implode(",", $this->Industry->getIndustryNameById($industry_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and  industry_id in  (" . implode(",", $industry_id) . ")";
                    } else {
                        $where_query .= "  industry_id in  (" . implode(",", $industry_id) . ")";
                    }
                    
                    $empty_form_tracker = 1;
                }
                
                if (isset($save['function_id']) && !empty($save['function_id'])) {
                    $function_id                   = $save['function_id'];
                    $searchoption["function_name"] = implode(",", $this->f->getFunctionNameById($function_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and  function_id in (" . implode(",", $function_id) . ")";
                    } else {
                        $where_query .= " function_id in (" . implode(",", $function_id) . ")";
                    }
                    
                    $keymap .= " and  jobs.id=job_function.job_id ";
                    $table[]            = "job_function";
                    $empty_form_tracker = 1;
                }
                
                if (isset($save['experience_from_month']) and !empty($save['experience_from_month'])) {
                    $from_month = $save['experience_from_month'];
                    $to_month   = $save['experience_to_month'];
                    if (!empty($from_month) && (!empty($to_month))) {
                        $data["experience"] = $from_month . "-" . $to_month;
                        if ($from_month > $to_month) {
                            $errorflag[] = 'Invalid Experience Range';
                        } else {
                            $searchoption["experience"] = $from_month . "-" . $to_month;
                            if (strlen($where_query) > 0) {
                                $where_query .= " and (  ( ( from_experince_in_month  between $from_month and  $to_month )  or  (   to_experince_in_month  between $to_month and  $to_month  ) )  or  ( (  $from_month   between from_experince_in_month and  to_experince_in_month ) or (  $to_month    between from_experince_in_month and  to_experince_in_month  ) )  )";
                            } else {
                                $where_query .= " (  ( ( from_experince_in_month  between $from_month and  $to_month )  or  (   to_experince_in_month  between $from_month and  $to_month  ) )  or  ( (  $from_month   between from_experince_in_month and  to_experince_in_month ) or (  $to_month    between from_experince_in_month and  to_experince_in_month  ) )  )";
                            }
                            
                            $empty_form_tracker = 1;
                        }
                    }
                }
                
                if (!empty($save['ctc_start']) && !empty($save['ctc_end'])) {
                    $empty_form_tracker = 1;
                    if ($postdata['ctc_start'] > $postdata['ctc_end']) {
                        $errorflag[] = 'Invalid s Range';
                    } else {
                        $searchoption["salary"] = $save['ctc_start'] . "-" . $save['ctc_end'];
                        $ctc_start              = $save['ctc_start'];
                        $ctc_end                = $save['ctc_end'];
                        if (strlen($where_query) > 0) {
                            $where_query .= " and (  ( ctc_Start between " . $ctc_start . " and " . $ctc_end . " )  or   ( ctc_end between " . $ctc_start . " and " . $ctc_end . "  ) ) ";
                        } else {
                            $where_query .= " (  ( ctc_Start between " . $ctc_start . " and " . $ctc_end . " )  or   ( ctc_end between " . $ctc_start . " and " . $ctc_end . "  ) ) ";
                        }
                    }
                }
                
                $emp_type_id = $save['emptype_ids'];
                if (isset($emp_type_id) && (!empty($emp_type_id))) {
                    if (count($emp_type_id) > 3) {
                        $errorflag[] = "Maximum three employment type are allowed";
                    } else {
                        $searchoption["employment_types"] = implode(",", $this->emptype->getEmploymentType($emp_type_id));
                        if (strlen($where_query) > 0) {
                            $where_query .= " and job_employment.employment_id in ( " . implode(",", $emp_type_id) . " )";
                        } else {
                            $where_query .= "job_employment.employment_id in (" . implode(",", $emp_type_id) . " )";
                        }
                        
                        $keymap .= " and  jobs.id=job_employment.job_id  ";
                        $table[]            = "job_employment";
                        $empty_form_tracker = 1;
                    }
                }
                
                $qualification_level = $save['qualification_ids'];
                if (isset($qualification_level) && (!empty($qualification_level))) {
                    $searchoption["qualification"] = implode(",", $this->qtype->getQualificationById($qualification_level));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and job_qualifications.qualification_id in (" . implode(",", $qualification_level) . ")";
                    } else {
                        $where_query .= " job_qualifications.qualification_id in (" . implode(",", $qualification_level) . ")";
                    }
                    
                    $keymap .= " and jobs.id=job_qualifications.job_id ";
                    $table[]            = "job_qualifications";
                    $empty_form_tracker = 1;
                }
                
                $exp_level_id = $save['exp_level_ids'];
                if (isset($exp_level_id) && (!empty($exp_level_id))) {
                    $data["experience_level"] = implode(",", $this->elevel->getExperienceLevelById($exp_level_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and  job_experiencelevel.exp_level_id in (" . implode(",", $exp_level_id) . ")";
                    } else {
                        $where_query .= " job_experiencelevel.exp_level_id in (" . implode(",", $exp_level_id) . ")";
                    }
                    
                    $keymap .= " and  jobs.id=job_experiencelevel.job_id  ";
                    $table[]            = "job_experiencelevel";
                    $empty_form_tracker = 1;
                }
                
                $company = $save['company'];
                if (!empty($company) && isset($company) && ($company != "")) {
                    $searchoption["company"] = $company;
                    if (strlen($where_query) > 0) {
                        $where_query .= " and   ";
                    } else {
                        $where_query .= " ";
                    }
                    
                    $company = preg_replace('/[ ]+/', ' ', trim($company));
                    $temp    = explode(",", $company);
                    $count   = count($temp);
                    $i       = 0;
                    $where_query .= "( ";
                    foreach ($temp as $val) {
                        $where_query .= "( organizations.organization_name like '%" . trim($val) . "%' or  jobs.company_name  like '%" . trim($val) . "%' ) ";
                        if ($i < $count - 1) {
                            $where_query .= " or";
                        }
                        
                        $i++;
                    }
                    
                    $where_query .= " )";
                    if (strlen($where_query) > 0) {
                        $where_query .= "";
                    }
                    
                    $empty_form_tracker = 1;
                }
                
                $location_id = $save['pre_loc_ids'];
                if (isset($location_id) && (!empty($location_id))) {
                    $searchoption["location_name"] = implode(",", $this->Locations->getLocationNameById($location_id));
                    if (strlen($where_query) > 0) {
                        $where_query .= " and jobs.city_id in (" . implode(",", $location_id) . " )";
                    } else {
                        $where_query .= " jobs.city_id in (" . implode(",", $location_id) . " )";
                    }
                    
                    $empty_form_tracker = 1;
                }
                
                if ($empty_form_tracker == 0 || count($errorflag) > 0) {
                    $dataArray['status'] = false;
                    if ($empty_form_tracker == 0) {
                        $dataArray['message'] = "Atlest one search criteria need to specify";
                    } else if (count($errorflag) > 0) {
                        $dataArray['message'] = $errorflag[0];
                    }
                    
                    // echo json_encode($dataArray);
                    
                } else {
                    $query                 = $select_part . " from  " . implode(",", $table) . " where " . $keymap . " and  " . $where_query . "  and   job_status=1 and jobs.is_published=1  and posted_on <=NOW() and jobs.is_published=1 group by j_id order by job_type desc,job_created_date desc";
                    $user_id               = 1;
                    $data['user_id']       = $save['user_id'];
                    $this->u->postdata     = $save;
                    $searchoption['query'] = $query;
                    $this->db->insert("query", array(
                        "query" => $searchoption['query']
                    ));
                    if (isset($save['unique_id']) && !empty($save['unique_id'])) {
                        $flag = $this->Jobs->storeSaveSearch('user_save_searches    ', $searchoption, 1, $save["unique_id"]);
                        $this->db->insert("query", array(
                            "query" => $this->db->last_query()
                        ));
                        if ($flag) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Search updated  successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Someting went wrong try again";
                        }
                    } else {
                        $searchoption['user_id'] = $save["user_id"];
                        if ($this->Jobs->storeSaveSearch(1, $searchoption, NULL, $save["user_id"])) {
                            $this->db->insert("query", array(
                                "query" => $this->db->last_query()
                            ));
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Search is saved successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Someting went wrong try again";
                        }
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
    
    public function deleteSavedSearch()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                if (!empty($postdata['unique_id']) && isset($postdata['unique_id'])) {
                    $query = "delete from user_save_searches where user_id=" . $postdata['user_id'] . " and  id='" . $postdata['unique_id'] . "'";
                    $this->db->query($query);
                    if ($this->db->affected_rows()) {
                        $dataArray['status']  = true;
                        $dataArray['message'] = "Saved search is deleted successfully";
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = SOMETHING_WRONG;
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "search ids not provided";
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
    
    public function getAlertDetails()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                if (!empty($postdata['alert_id']) && isset($postdata['alert_id'])) {
                    $data   = array();
                    $option = $this->j->getJobAlertId($postdata['alert_id']);
                    if ($option != 0) {
                        $data['alert_name'] = $option['name'];
                        $data['alert_id']   = $option['unique_id'];
                        $data['skills']     = $option['skills'];
                        $data['frequency']  = $option['frequency'];
                        if (isset($option['location_name']) && !empty($option['location_name'])) {
                            $data['sel_loc_ids'] = $this->locations->getIdsByName(explode(",", $option['location_name']));
                        }
                        
                        if (isset($option['industry_name']) && !empty($option['industry_name'])) {
                            $data['sel_ind_ids'] = $this->industry->getIndustryIdByName(explode(",", $option['industry_name']));
                        }
                        
                        if (isset($option['function_category']) && !empty($option['function_category'])) {
                            $ids                          = $this->f->getIdsByName(explode(",", $option['function_category']));
                            $data['function_category_id'] = $ids[0];
                            $data['fetchFunction']        = $this->um->fetchAllFunction($ids[0]);
                            $data['sel_function_ids']     = $this->fjc->getIdsByName(explode(",", $option['function_name']));
                        }
                        
                        if (isset($option['experience']) && !empty($option['experience'])) {
                            $experience = explode("-", $option['experience']);
                            if (count($experience) > 1) {
                                $data['sel_from_year']  = ($experience[0] - $experience[0] % 12);
                                $data['sel_from_month'] = $experience[0] % 12;
                                $data['sel_to_year']    = ($experience[1] - $experience[1] % 12);
                                $data['sel_to_month']   = $experience[1] % 12;
                            } else {
                                $data['sel_from_year']  = 0;
                                $data['sel_from_month'] = 0;
                                $data['sel_to_year']    = $experience[0];
                                $data['sel_to_month']   = 0;
                            }
                        }
                        
                        if (!empty($option['experience_level'])) {
                            $data['sel_explevel_ids'] = $this->elevel->getIdsByName(explode(",", $option['experience_level']));
                        }
                        
                        if (!empty($option['qualification'])) {
                            $data['sel_qualification_ids'] = $this->qualification->getIdsByName(explode(",", $option['qualification']));
                        }
                        
                        if (!empty($option['employment_types'])) {
                            $data['sel_emptype_ids'] = $this->emptype->getIdsByName(explode(",", $option['employment_types']));
                        }
                        
                        if (!empty($option['salary']) && isset($option['salary'])) {
                            $temp                    = explode("-", $option['salary']);
                            $data['sel_from_salary'] = $temp[0];
                            $data['sel_to_salary']   = $temp[1];
                        } else {
                            $data['sel_from_salary'] = 0;
                            $data['sel_to_salary']   = 0;
                        }
                        
                        if (!empty($option['company']) && isset($option['company'])) {
                            $data['company'] = $option['company'];
                        }
                        
                        if (isset($option['keywords']) && !empty($option['keywords'])) {
                            $data['keywords'] = $option['keywords'];
                        }
                        
                        if (isset($option['skills']) && !empty($option['skills'])) {
                            $data['skills'] = $option['skills'];
                        }
                        
                        $dataArray['status']       = true;
                        $dataArray['alertdetails'] = $data;
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "no details found";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "alert ids not provided";
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
    
    public function deleteAlert()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                if (!empty($postdata['alert_id']) && isset($postdata['alert_id'])) {
                    $query = "delete from user_alert where user_id=" . $postdata['user_id'] . " and  id='" . $postdata['alert_id'] . "'";
                    $this->db->query($query);
                    if ($this->db->affected_rows()) {
                        $dataArray['status']  = true;
                        $dataArray['message'] = "Alert is deleted successfully";
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = SOMETHING_WRONG;
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "alert ids not provided";
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
    
    public function getAlertList()
    {
        $dataArray      = array();
        $formattedAlert = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                $result = $this->Jobs->getMyJobAlert($postdata['user_id']);
                if (count($result) > 0) {
                    $dataArray['status'] = true;
                    $offset              = 0;
                    if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                        $offset = $postdata['offset'];
                    }
                    
                    $dataArray['total_record'] = count($result);
                    $result                    = array_slice($result, $offset, $this->per_page);
                    foreach ($result as $key => $alert) {
                        $temp                     = array();
                        $temp['alert_name']       = $alert['name'];
                        $temp['alert_id']         = $alert['id'];
                        $temp['all_these_words']  = $alert['all_these_words'];
                        $temp['not_these_words']  = $alert['not_these_words'];
                        $temp['exact_phrase']     = $alert['exact_phrase'];
                        $temp['atleast_one_word'] = $alert['atleast_one_word'];
                        $temp['unique_id']        = $alert['id'];
                        $temp['frequency']        = $alert['frequency'];
                        $temp['frequency']        = $alert['frequency'];
                        if (isset($alert['salary']) && !empty($alert['salary'])) {
                            $salary = explode("-", $alert['salary']);
                            if (count($salary)) {
                                $temp['ctc_start'] = $salary[0];
                                $temp['ctc_end']   = $salary[1];
                            }
                        }
                        
                        if (isset($alert['experience']) && !empty($alert['experience'])) {
                            $experience = explode("-", $alert['experience']);
                            if (count($experience) > 1) {
                                $temp['experience_from_month'] = $experience[0];
                                $temp['experience_to_month']   = $experience[1];
                            } else {
                                $temp['experience_from_month'] = 0;
                                $temp['experience_to_month']   = $experience[0];
                            }
                        }
                        
                        $experience = explode("-", $alert['experience']);
                        if (isset($experience)) {
                            if (count($experience) > 1) {
                                $data['sel_from_year']  = ($experience[0] - $experience[0] % 12) / 12;
                                $data['sel_from_month'] = $experience[0] % 12;
                                $data['sel_to_year']    = ($experience[1] - $experience[1] % 12) / 12;
                                $data['sel_to_month']   = $experience[1] % 12;
                            }
                        }
                        
                        if (!empty($option['salary'])) {
                            $temp                    = explode("-", $option['salary']);
                            $data['sel_from_salary'] = $temp[0];
                            $data['sel_to_salary']   = $temp[1];
                        }
                        
                        if (!empty($option['company'])) {
                            $data['company'] = $option['company'];
                        }
                        
                        if (isset($alert['location_name']) && !empty($alert['location_name'])) {
                            $temp['pre_loc_ids'] = $this->Locations->getIdsByName(explode(",", $alert['location_name']));
                        } else {
                            $temp['pre_loc_ids'] = array();
                        }
                        
                        if (isset($alert['industry_name']) && !empty($alert['industry_name'])) {
                            $temp['industry_ids'] = $this->Industry->getIndustryIdByName(explode(",", $alert['industry_name']));
                        } else {
                            $temp['industry_ids'] = array();
                        }
                        
                        if (isset($alert['function_name']) && !empty($alert['function_name'])) {
                            $function_id         = $this->f->getIdsByName(explode(",", $alert['function_name']));
                            $temp['function_id'] = $function_id;
                        } else {
                            $temp['function_id'] = array();
                        }
                        
                        if (!empty($alert['experience_level']) && isset($alert['experience_level'])) {
                            $temp['exp_level_ids'] = $this->elevel->getIdsByName(explode(",", $alert['experience_level']));
                        } else {
                            $temp['exp_level_ids'] = array();
                        }
                        
                        if (!empty($alert['qualification']) && isset($alert['qualification'])) {
                            $temp['qualification_ids'] = $this->qtype->getIdsByName(explode(",", $alert['qualification']));
                        } else {
                            $temp['qualification_ids'] = array();
                        }
                        
                        if (!empty($alert['employment_types']) && isset($alert['employment_types'])) {
                            $temp['emptype_ids'] = $this->emptype->getIdsByName(explode(",", $alert['employment_types']));
                        } else {
                            $temp['emptype_ids'] = array();
                        }
                        
                        if (isset($alert['keywords']) && !empty($alert['keywords'])) {
                            $temp['keyword'] = $alert['keywords'];
                        } else {
                            $temp['keyword'] = "";
                        }
                        
                        if (isset($alert['skills']) && !empty($alert['skills'])) {
                            $temp['skills'] = $alert['skills'];
                        } else {
                            $temp['skills'] = "";
                        }
                        
                        if (isset($alert['company']) && !empty($alert['company'])) {
                            $temp['company'] = $alert['company'];
                        } else {
                            $temp['company'] = "";
                        }
                        
                        $formattedAlert[$key] = $temp;
                    }
                    
                    $dataArray['alertlist'] = $formattedAlert;
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "You don't have create single alert till ";
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
    
    public function getSavedSearchList()
    {
        $dataArray         = array();
        $formattedAlert    = array();
        $dataArray         = array();
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $industry_ids      = array();
        $function_ids      = array();
        $formdata          = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                $result = $this->Jobs->getMySavedSearches($postdata['user_id']);
                if ($result != 0) {
                    $dataArray['status'] = true;
                    $offset              = 0;
                    if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                        $offset = $postdata['offset'];
                    }
                    
                    $dataArray['total_record'] = count($result);
                    $result                    = array_slice($result, $offset, $this->per_page);
                    foreach ($result as $key => $alert) {
                        $temp                     = array();
                        $temp['search_name']      = $alert['name'];
                        $temp['all_these_words']  = $alert['all_these_words'];
                        $temp['not_these_words']  = $alert['not_these_words'];
                        $temp['exact_phrase']     = $alert['exact_phrase'];
                        $temp['atleast_one_word'] = $alert['atleast_one_word'];
                        $temp['keyword']          = $alert['atleast_one_word'];
                        $temp['search_id']        = $alert['id'];
                        $temp['unique_id']        = $alert['id'];
                        if (isset($alert['salary']) && !empty($alert['salary'])) {
                            $salary = explode("-", $alert['salary']);
                            if (count($salary)) {
                                $temp['ctc_start'] = $salary[0];
                                $temp['ctc_end']   = $salary[1];
                            }
                        }
                        
                        if (isset($alert['experience']) && !empty($alert['experience'])) {
                            $experience = explode("-", $alert['experience']);
                            if (count($experience) > 1) {
                                $temp['experience_from_month'] = $experience[0];
                                $temp['experience_to_month']   = $experience[1];
                            } else {
                                $temp['experience_from_month'] = 0;
                                $temp['experience_to_month']   = $experience[0];
                            }
                        }
                        
                        $experience = explode("-", $alert['experience']);
                        if (isset($experience)) {
                            if (count($experience) > 1) {
                                $data['sel_from_year']  = ($experience[0] - $experience[0] % 12) / 12;
                                $data['sel_from_month'] = $experience[0] % 12;
                                $data['sel_to_year']    = ($experience[1] - $experience[1] % 12) / 12;
                                $data['sel_to_month']   = $experience[1] % 12;
                            }
                        }
                        
                        if (!empty($option['salary'])) {
                            $temp                    = explode("-", $option['salary']);
                            $data['sel_from_salary'] = $temp[0];
                            $data['sel_to_salary']   = $temp[1];
                        }
                        
                        if (!empty($option['company'])) {
                            $data['company'] = $option['company'];
                        }
                        
                        if (isset($alert['location_name']) && !empty($alert['location_name'])) {
                            $temp['pre_loc_ids'] = $this->Locations->getIdsByName(explode(",", $alert['location_name']));
                        } else {
                            $temp['pre_loc_ids'] = array();
                        }
                        
                        if (isset($alert['industry_name']) && !empty($alert['industry_name'])) {
                            $temp['industry_ids'] = $this->Industry->getIndustryIdByName(explode(",", $alert['industry_name']));
                        } else {
                            $temp['industry_ids'] = array();
                        }
                        
                        if (isset($alert['function_name']) && !empty($alert['function_name'])) {
                            $function_id         = $this->f->getIdsByName(explode(",", $alert['function_name']));
                            $temp['function_id'] = $function_id;
                        } else {
                            $temp['function_id'] = array();
                        }
                        
                        if (!empty($alert['experience_level']) && isset($alert['experience_level'])) {
                            $temp['exp_level_ids'] = $this->elevel->getIdsByName(explode(",", $alert['experience_level']));
                        } else {
                            $temp['exp_level_ids'] = array();
                        }
                        
                        if (!empty($alert['qualification']) && isset($alert['qualification'])) {
                            $temp['qualification_ids'] = $this->qtype->getIdsByName(explode(",", $alert['qualification']));
                        } else {
                            $temp['qualification_ids'] = array();
                        }
                        
                        if (!empty($alert['employment_types']) && isset($alert['employment_types'])) {
                            $temp['emptype_ids'] = $this->emptype->getIdsByName(explode(",", $alert['employment_types']));
                        } else {
                            $temp['emptype_ids'] = array();
                        }
                        
                        /*if (isset($alert['keywords']) && !empty($alert['keywords']))
                        {
                        $temp['keyword'] = $alert['keywords'];
                        }
                        else
                        {
                        $temp['keyword'] = "";
                        }*/
                        
                        if (isset($alert['skills']) && !empty($alert['skills'])) {
                            $temp['skills'] = $alert['skills'];
                        } else {
                            $temp['skills'] = "";
                        }
                        
                        if (isset($alert['company']) && !empty($alert['company'])) {
                            $temp['company'] = $alert['company'];
                        } else {
                            $temp['company'] = "";
                        }
                        
                        $formattedAlert[$key] = $temp;
                    }
                    
                    $dataArray['alertlist'] = $formattedAlert;
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "You don't have create single alert till ";
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
    
    public function saveJob()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                if (!empty($postdata['job_id']) && isset($postdata['job_id'])) {
                    $savejids = $this->Jobs->getMySavedJobIds($postdata['user_id']);
                    if (!in_array($postdata['job_id'], $savejids)) {
                        $data['user_id']    = $postdata['user_id'];
                        $data['job_id']     = $postdata['job_id'];
                        $data['updated_on'] = date('Y-m-d h-i-s');
                        if ($this->Jobs->saveJob($data)) {
                            $dataArray['status']  = true;
                            $dataArray['message'] = "Job is saved successfully";
                        } else {
                            $dataArray['status']  = false;
                            $dataArray['message'] = "Something went wrong try again";
                        }
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "Your have already saved this job";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "job id not provided";
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
    
    public function getSaveJobList()
    {
        $dataArray         = array();
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $industry_ids      = array();
        $function_ids      = array();
        $formdata          = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                $savejids = $this->Jobs->getMySavedJobIds($postdata['user_id']);
                if (count($savejids) > 0) {
                    $query  = "   jobs.id in (" . implode(",", $savejids) . " ) and saved_jobs.user_id=" . $postdata['user_id'];
                    $result = $this->u->getSavedJobList($query);
                    if (count($result) > 0) {
                        $offset = 0;
                        if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                            $offset = $postdata['offset'];
                        }
                        
                        $dataArray['total_record'] = count($result);
                        $result                    = array_slice($result, $offset, $this->per_page);
                        $applied_job_ids           = array();
                        if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                            $applied_job_ids = $this->Jobs->getMyAppliedJobIds($postdata['user_id']);
                            $userid          = $postdata['user_id'];
                        }
                        
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
                            $job_locations_name    = $job['job_locations_name'];
                            $org_logo              = $this->home_model->logo_of_organization($organization_id);
                            $job['org_logo']       = $org_logo;
                            $location_name         = $this->Locations->getLocationNameById($temp, $temp_jstate_id);
                            $job['location_name']  = $job_locations_name;
                            $locationids           = array_merge($locationids, $temp);
                            
                            $industry_ids[] = $job['industry_id'];
                            if (!in_array($job['organization_id'], array_keys($organization_ids))) {
                                $organization_ids[$job['organization_id']]['name']  = $job['organization_name'];
                                $organization_ids[$job['organization_id']]['count'] = 1;
                            } else {
                                $organization_ids[$job['organization_id']]['count'] = $organization_ids[$job['organization_id']]['count'] + 1;
                            }
                            
                            $job["description"]   = html_entity_decode(strip_tags($job["description"]));
                            $job["about_company"] = html_entity_decode(strip_tags($job["about_company"]));
                            if (!in_array($j_id, $applied_job_ids)) {
                                $job['apply_status'] = "apply";
                            } else {
                                $job['apply_status'] = "applied";
                            }
                            
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
                        
                        $dataArray['joblist'] = $result;
                        $dataArray['status']  = true;
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = "Something went wrong try again";
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "You don't saved any jobs";
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
    
    public function deleteSavedJob()
    {
        $dataArray = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (!empty($postdata['user_id']) && isset($postdata['user_id'])) {
                if (!empty($postdata['job_id']) && isset($postdata['job_id'])) {
                    $query = "delete from saved_jobs where user_id=" . $postdata['user_id'] . " and  job_id='" . $postdata['job_id'] . "'";
                    $this->db->query($query);
                    if ($this->db->affected_rows()) {
                        $dataArray['status']  = true;
                        $dataArray['message'] = "Job deleted successfully";
                    } else {
                        $dataArray['status']  = false;
                        $dataArray['message'] = SOMETHING_WRONG;
                    }
                } else {
                    $dataArray['status']  = false;
                    $dataArray['message'] = "Job ids not provided";
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
    
    public function getFaqlist()
    {
        $dataArray = array();
        $faqresult = $this->db->query("select question,answer,created_on from faq where is_published=1 and is_deleted=1 ")->result_array();
        if (!is_bool($faqresult)) {
            $dataArray['status']  = true;
            $dataArray['message'] = "sucessful";
            $dataArray['faqlist'] = $faqresult;
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "No any question";
            $dataArray['faqlist'] = "";
        }
        
        echo json_encode($dataArray);
    }
    
    public function getTopCompanyList()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $companylist = $this->db->query("select * from  organization_members om   join  organizations  on  om.organization_id=organizations.id  where 
               is_top_organization=1 and   om.i_am_recuiter = 2");
            $postdata    = json_decode(file_get_contents('php://input'), true);
            $offset      = 0;
            if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                $offset = $postdata['offset'];
            }
            
            $dataArray['total_record'] = $companylist->num_rows();
            $companylist               = array_slice($companylist->result_array(), $offset, $this->per_page);
            if (count($companylist) > 0) {
                foreach ($companylist as $key => $company) {
                    if (isset($company['company_logo']) && !empty($company['company_logo'])) {
                        $company['company_logo_path'] = base_url() . comany_logo_images . $company['company_logo'];
                    } else {
                        $company['company_logo_path'] = "";
                    }
                    
                    $companylist[$key] = $company;
                }
                
                $dataArray['companylist'] = $companylist;
                $dataArray['status']      = true;
                $dataArray['message']     = "success";
            } else {
                $dataArray['companylist'] = array();
                $dataArray['status']      = false;
                $dataArray['message']     = "No more top company exits";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function getTopConsultantList()
    {
        $companylist = $this->db->query("select * from  organization_members om  left join  organizations  on  om.organization_id=organizations.id  where om.is_published=1 and
               om.is_top_recruiter=1 and   om.i_am_recuiter = 1");
        $offset      = 0;
        if (isset($postdata['offset']) && !empty($postdata['offset'])) {
            $offset = $postdata['offset'];
        }
        
        $dataArray['total_record'] = $companylist->num_rows();
        $companylist               = array_slice($companylist->result_array(), $offset, $this->per_page);
        if (count($companylist) > 0) {
            foreach ($companylist as $key => $company) {
                if (isset($company['company_logo']) && !empty($company['company_logo'])) {
                    $company['company_logo_path'] = base_url() . comany_logo_images . $company['company_logo'];
                } else {
                    $company['company_logo_path'] = "";
                }
                
                $companylist[$key] = $company;
            }
            
            $dataArray['companylist'] = $companylist;
            $dataArray['status']      = true;
            $dataArray['message']     = "success";
        } else {
            $dataArray['companylist'] = array();
            $dataArray['status']      = false;
            $dataArray['message']     = "No any top company exist.";
        }
        
        echo json_encode($dataArray);
    }
    
    public function getJobByCompany($orderby = "posted_on", $sort = "desc")
    {
        $applied_job_ids   = array();
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $industry_ids      = array();
        $function_ids      = array();
        $formdata          = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['org_id']) && !empty($postdata['org_id'])) {
                $where_query = "organizations.id=" . $postdata['org_id'];
                $where_query = "   " . $where_query;
                $result      = $this->u->basicSearch($where_query, $orderby, $sort);
                if (!is_bool($result)) {
                    $ls   = "";
                    $inds = "";
                    foreach ($result->result_array() as $key => $job) {
                        $cur_dis_jobids[] = $job['id'];
                        if (isset($job['industry_id']) && !empty($job['industry_id'])) {
                            $inds .= $job['industry_id'] . ",";
                        }
                        
                        $function_ids[] = $job['rol_id'];
                        if (isset($job['loc_id']) && !empty($job['loc_id'])) {
                            $ls .= $job['loc_id'] . ",";
                        }
                    }
                    
                    $locationids  = explode(",", trim($ls, ','));
                    $industry_ids = explode(",", trim($inds, ','));
                    if (count($cur_dis_jobids) > 0) {
                        $dataArray['filteroption']['emptypelist'] = $this->sm->getEmpTypeFormtedArray($cur_dis_jobids, 1);
                    }
                    
                    if (count($locationids) > 0) {
                        $locationids = array_count_values($locationids);
                        $keys        = array_keys($locationids);
                        foreach ($keys as $key) {
                            $location_name       = $this->loc->getNameBySingleID($key);
                            $formated_location[] = array(
                                "location_id" => $key,
                                "count" => $locationids[$key],
                                "location_name" => $location_name
                            );
                        }
                        
                        $dataArray['filteroption']['locationlist'] = $formated_location;
                    }
                    
                    if (count($industry_ids) > 0) {
                        $industry_ids = array_count_values($industry_ids);
                        $keys         = array_keys($industry_ids);
                        foreach ($keys as $key) {
                            $industry_name       = $this->industry->getIndustryNameById($key);
                            $formated_industry[] = array(
                                "industry_id" => $key,
                                "count" => $industry_ids[$key],
                                "industry_name" => $industry_name
                            );
                        }
                        
                        $dataArray['filteroption']['industrylist'] = $formated_industry;
                    }
                    
                    if (count($function_ids) > 0) {
                        $function_ids = array_count_values($function_ids);
                        $keys         = array_keys($function_ids);
                        foreach ($keys as $key) {
                            $function_name       = $this->fjc->getFunctionNameById($key);
                            $formated_function[] = array(
                                "function_id" => $key,
                                "count" => $function_ids[$key],
                                "function_job_category" => $function_name
                            );
                        }
                        
                        $dataArray['filteroption']['functionlist'] = $formated_function;
                    }
                    
                    $dataArray['filteroption']['cur_dis_jobids'] = $cur_dis_jobids;
                    $dataArray['filteroption']['exprange']       = $this->u->expRangeService();
                    $dataArray['filteroption']['salaryrange']    = $this->u->salaryRangeService();
                    $offset                                      = 0;
                    if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                        $offset = $postdata['offset'];
                    }
                    
                    $dataArray['total_record'] = $result->num_rows();
                    $result                    = array_slice($result->result_array(), $offset, $this->per_page);
                    if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                        $applied_job_ids = $this->j->getMyAppliedJobIds($postdata['user_id']);
                    }
                    
                    foreach ($result as $key => $job) {
                        $job["description"]   = html_entity_decode(strip_tags($job["description"]));
                        $job["about_company"] = html_entity_decode(strip_tags($job["about_company"]));
                        if (!in_array($job['id'], $applied_job_ids)) {
                            $job['apply_status'] = "apply";
                        } else {
                            $job['apply_status'] = "applied";
                        }
                        
                        $locids               = $this->loc->getJobLocations($job['id']);
                        $job['location_name'] = implode(" , ", array_values($locids));
                        $job['industry_name'] = implode(",", $this->industry->getIndustryNameById(explode(",", $job['industry_id'])));
                        $job['sharedatalink'] = base_url() . "search/getJobDetailsById/" . $job['unique_id'];
                        if (strlen($job['logo']) > 0) {
                            $url = FCPATH . comany_logo_images . $job['logo'];
                            if (file_exists($url)) {
                                $job['company_logo'] = base_url() . comany_logo_images . $job['logo'];
                            } else {
                                $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                            }
                        } else {
                            $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                        }
                        
                        $result[$key] = $job;
                    }
                    
                    $dataArray['joblist'] = $result;
                    $dataArray['status']  = true;
                    $dataArray['message'] = "success";
                } else {
                    $dataArray['joblist'] = array();
                    $dataArray['status']  = false;
                    $dataArray['message'] = "there is no any job exist for this company";
                }
            } else {
                $dataArray['status']  = false;
                $dataArray['message'] = "provide organization id ";
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    /* Not Used HotJobs function changed the functionality as per the discussion on 5 Jan 2017 with client by tester sushant */
    public function getHotJob_backup($orderby = "posted_on", $sort = "desc")
    {
        $applied_job_ids   = array();
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $industry_ids      = array();
        $function_ids      = array();
        $formdata          = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                $applied_job_ids          = $this->j->getMyAppliedJobIds($postdata['user_id']);
                $userid                   = $postdata['user_id'];
                $where_query              = "";
                $data['Candidatedetails'] = $this->get_all_candidate_details->getCandidateDetails($userid);
                $userdetails              = $data['Candidatedetails']['personaldetails'][0];
                $udata                    = array();
                $udata['work_industry']   = explode(",", $userdetails->work_industry);
                $udata['role']            = explode(",", $userdetails->role);
                $udata['work_area']       = $userdetails->work_area;
                if (isset($userdetails->role) && !empty($userdetails->role)) {
                    $udata['role'] = explode(",", $userdetails->role);
                }
                
                if (isset($userdetails->work_area) && !empty($userdetails->work_area)) {
                    $udata['work_area'] = $userdetails->work_area;
                }
                
                if (isset($udata['role']) && (!empty($udata['role']))) {
                    if (strlen($where_query) > 0) {
                        $i = 0;
                        $where_query .= " and ( ";
                        foreach ($udata['role'] as $roleid) {
                            $where_query .= " job_function.function_id=$roleid";
                            if ($i < count($udata['role']) - 1) {
                                $where_query .= " or ";
                            }
                            
                            $i++;
                        }
                        
                        $where_query .= "  ) ";
                    } else {
                        $i = 0;
                        $where_query .= "  ( ";
                        foreach ($udata['role'] as $roleid) {
                            $where_query .= " job_function.function_id=$roleid";
                            if ($i < count($udata['role']) - 1) {
                                $where_query .= " or ";
                            }
                            
                            $i++;
                        }
                        
                        $where_query .= "  ) ";
                    }
                }
                
                if (isset($udata['work_area']) && (!empty($udata['work_area']))) {
                    if (strlen($where_query) > 0 && $where_query != " ") {
                        $where_query .= " and function_category_id=" . $udata['work_area'];
                    } else {
                        $where_query .= "function_category_id=" . $udata['work_area'];
                    }
                }
                
                $where_query .= " AND job_type = 1";
                $this->u->postdata = $udata;
                $result            = $this->u->basicSearch($where_query, $orderby, $sort);
                
                // echo $this->db->last_query(); exit;
                
                if (!is_bool($result) && $result->num_rows()) {
                    $ls   = "";
                    $inds = "";
                    foreach ($result->result_array() as $key => $job) {
                        $cur_dis_jobids[] = $job['id'];
                        if (isset($job['industry_id']) && !empty($job['industry_id'])) {
                            $inds .= $job['industry_id'] . ",";
                        }
                        
                        $function_ids[] = $job['rol_id'];
                        if (isset($job['loc_id']) && !empty($job['loc_id'])) {
                            $ls .= $job['loc_id'] . ",";
                        }
                    }
                    
                    $locationids  = explode(",", trim($ls, ','));
                    $industry_ids = explode(",", trim($inds, ','));
                    if (count($cur_dis_jobids) > 0) {
                        $dataArray['filteroption']['emptypelist'] = $this->sm->getEmpTypeFormtedArray($cur_dis_jobids, 1);
                    }
                    
                    if (count($locationids) > 0) {
                        $locationids = array_count_values($locationids);
                        $keys        = array_keys($locationids);
                        foreach ($keys as $key) {
                            $location_name       = $this->loc->getNameBySingleID($key);
                            $formated_location[] = array(
                                "location_id" => $key,
                                "count" => $locationids[$key],
                                "location_name" => $location_name
                            );
                        }
                        
                        $dataArray['filteroption']['locationlist'] = $formated_location;
                    }
                    
                    if (count($industry_ids) > 0) {
                        $industry_ids = array_count_values($industry_ids);
                        $keys         = array_keys($industry_ids);
                        foreach ($keys as $key) {
                            $industry_name       = $this->industry->getIndustryNameById($key);
                            $formated_industry[] = array(
                                "industry_id" => $key,
                                "count" => $industry_ids[$key],
                                "industry_name" => $industry_name
                            );
                        }
                        
                        $dataArray['filteroption']['industrylist'] = $formated_industry;
                    }
                    
                    if (count($function_ids) > 0) {
                        $function_ids = array_count_values($function_ids);
                        $keys         = array_keys($function_ids);
                        foreach ($keys as $key) {
                            $function_name       = $this->fjc->getFunctionNameById($key);
                            $formated_function[] = array(
                                "function_id" => $key,
                                "count" => $function_ids[$key],
                                "function_job_category" => $function_name
                            );
                        }
                        
                        $dataArray['filteroption']['functionlist'] = $formated_function;
                    }
                    
                    $dataArray['filteroption']['cur_dis_jobids'] = $cur_dis_jobids;
                    $dataArray['filteroption']['exprange']       = $this->u->expRangeService();
                    $dataArray['filteroption']['salaryrange']    = $this->u->salaryRangeService();
                    $offset                                      = 0;
                    if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                        $offset = $postdata['offset'];
                    }
                    
                    $dataArray['total_record'] = $result->num_rows();
                    $result                    = array_slice($result->result_array(), $offset, $this->per_page);
                    foreach ($result as $key => $job) {
                        $job["description"]   = html_entity_decode(strip_tags($job["description"]));
                        $job["about_company"] = html_entity_decode(strip_tags($job["about_company"]));
                        if (in_array($job['id'], $applied_job_ids)) {
                            $job['apply_status'] = "Applied";
                        } else {
                            $job['apply_status'] = "Apply";
                        }
                        
                        if (isset($job['industry_id']) && $job['industry_id'] != " ") {
                            $job['industry_name'] = implode(",", $this->industry->getIndustryNameById(explode(",", $job['industry_id'])));
                        }
                        
                        $job['function_job_category'] = $this->fjc->getFunctionNameById($job['function_category_id']);
                        $job['sharedatalink']         = base_url() . "search/getJobDetailsById/" . $job['unique_id'];
                        if (strlen($job['logo']) > 0) {
                            $url = FCPATH . comany_logo_images . $job['logo'];
                            if (file_exists($url)) {
                                $job['company_logo'] = base_url() . comany_logo_images . $job['logo'];
                            } else {
                                $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                            }
                        } else {
                            $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                        }
                        
                        $result[$key] = $job;
                    }
                    
                    $dataArray['joblist'] = $result;
                    $dataArray['status']  = true;
                    $dataArray['message'] = "success ";
                } else {
                    $dataArray['joblist'] = array();
                    $dataArray['status']  = false;
                    $dataArray['message'] = "There is no any hot job ";
                }
            } else {
                $where_query = " jobs.job_type=1";
                $result      = $this->u->basicSearch($where_query, $orderby, $sort);
                if (!is_bool($result) && $result->num_rows()) {
                    $ls   = "";
                    $inds = "";
                    foreach ($result->result_array() as $key => $job) {
                        $cur_dis_jobids[] = $job['id'];
                        if (isset($job['industry_id']) && !empty($job['industry_id'])) {
                            $inds .= $job['industry_id'] . ",";
                        }
                        
                        $function_ids[] = $job['rol_id'];
                        if (isset($job['loc_id']) && !empty($job['loc_id'])) {
                            $ls .= $job['loc_id'] . ",";
                        }
                    }
                    
                    $locationids  = explode(",", trim($ls, ','));
                    $industry_ids = explode(",", trim($inds, ','));
                    if (count($cur_dis_jobids) > 0) {
                        $dataArray['filteroption']['emptypelist'] = $this->sm->getEmpTypeFormtedArray($cur_dis_jobids, 1);
                    }
                    
                    if (count($locationids) > 0) {
                        $locationids = array_count_values($locationids);
                        $keys        = array_keys($locationids);
                        foreach ($keys as $key) {
                            $location_name       = $this->loc->getNameBySingleID($key);
                            $formated_location[] = array(
                                "location_id" => $key,
                                "count" => $locationids[$key],
                                "location_name" => $location_name
                            );
                        }
                        
                        $dataArray['filteroption']['locationlist'] = $formated_location;
                    }
                    
                    if (count($industry_ids) > 0) {
                        $industry_ids = array_count_values($industry_ids);
                        $keys         = array_keys($industry_ids);
                        foreach ($keys as $key) {
                            $industry_name       = $this->industry->getIndustryNameById($key);
                            $formated_industry[] = array(
                                "industry_id" => $key,
                                "count" => $industry_ids[$key],
                                "industry_name" => $industry_name
                            );
                        }
                        
                        $dataArray['filteroption']['industrylist'] = $formated_industry;
                    }
                    
                    if (count($function_ids) > 0) {
                        $function_ids = array_count_values($function_ids);
                        $keys         = array_keys($function_ids);
                        foreach ($keys as $key) {
                            $function_name       = $this->fjc->getFunctionNameById($key);
                            $formated_function[] = array(
                                "function_id" => $key,
                                "count" => $function_ids[$key],
                                "function_job_category" => $function_name
                            );
                        }
                        
                        $dataArray['filteroption']['functionlist'] = $formated_function;
                    }
                    
                    $dataArray['filteroption']['cur_dis_jobids'] = $cur_dis_jobids;
                    $dataArray['filteroption']['exprange']       = $this->u->expRangeService();
                    $dataArray['filteroption']['salaryrange']    = $this->u->salaryRangeService();
                    $offset                                      = 0;
                    if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                        $offset = $postdata['offset'];
                    }
                    
                    $dataArray['total_record'] = $result->num_rows();
                    $result                    = array_slice($result->result_array(), $offset, $this->per_page);
                    foreach ($result as $key => $job) {
                        $job["description"]   = strip_tags($job["description"]);
                        $job["about_company"] = strip_tags($job["about_company"]);
                        if (in_array($job['id'], $applied_job_ids)) {
                            $job['apply_status'] = "applied";
                        } else {
                            $job['apply_status'] = "apply";
                        }
                        
                        if (isset($job['industry_id']) && !empty($job['industry_id'])) {
                            $job['industry_name'] = implode(",", $this->industry->getIndustryNameById(explode(",", $job['industry_id'])));
                        }
                        
                        $job['function_job_category'] = $this->fjc->getFunctionNameById($job['function_category_id']);
                        $job['sharedatalink']         = base_url() . "search/getJobDetailsById/" . $job['unique_id'];
                        if (strlen($job['logo']) > 0) {
                            $url = FCPATH . comany_logo_images . $job['logo'];
                            if (file_exists($url)) {
                                $job['company_logo'] = base_url() . comany_logo_images . $job['logo'];
                            } else {
                                $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                            }
                        } else {
                            $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                        }
                        
                        $result[$key] = $job;
                    }
                    
                    $dataArray['joblist'] = $result;
                    $dataArray['status']  = true;
                    $dataArray['message'] = "success";
                } else {
                    $dataArray['joblist'] = array();
                    $dataArray['status']  = false;
                    $dataArray['message'] = "there is no any hot job";
                }
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
    
    public function getTopJob($orderby = "posted_on", $sort = "desc")
    {
        $applied_job_ids   = array();
        $cur_dis_jobids    = array();
        $locationids       = array();
        $formated_location = array();
        $formated_industry = array();
        $formated_function = array();
        $industry_ids      = array();
        $organization_ids  = array();
        $function_ids      = array();
        $formdata          = array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            $result   = $this->u->user_top_job_array($orderby, $sort);
            if (count($result) > 0) {
                $ls              = "";
                $inds            = "";
                $userid          = "";
                $applied_job_ids = array();
                if (isset($postdata['user_id']) && !empty($postdata['user_id'])) {
                    $applied_job_ids = $this->Jobs->getMyAppliedJobIds($postdata['user_id']);
                    $userid          = $postdata['user_id'];
                }
                
                if (count($result) > 0) {
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
                        
                        if (in_array($j_id, $applied_job_ids)) {
                            $job['apply_status'] = "Applied";
                        } else {
                            $job['apply_status'] = "Apply";
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
                }
                
                /*if(count($organization_ids)>0)
                {
                $dataArray['filteroption']['org_list']     = $organization_ids;
                }*/
                /*if(count($search_job_ids))
                {
                $dataArray['filteroption']['functionlist']
                = $this->f->getFunctionIdsForJobs($search_job_ids);
                $data['search_job_ids'] = implode(",", $search_job_ids);
                $dataArray['filteroption']['emptypelist']
                = $this->emptype->getEmpTypeFormtedArray($search_job_ids);
                $data['org_list']       = $organization_ids;
                $locationids            = array_count_values($locationids);
                $keys                   = array_keys($locationids);
                foreach ($keys as $key) {
                $location_name = $this->Locations->getNameBySingleID($key);
                if ($location_name != "N/A") {
                $formated_location[$key] = array(
                "count" => $locationids[$key],
                "l_name" => $location_name
                );
                } else {
                }
                }
                
                $dataArray['filteroption']['locationlist'] = $formated_location;
                $ind_new_arr=array();
                foreach ($industry_ids as $key) {
                $key_v=explode(',', $key);
                if(count($key_v)>1)
                {
                foreach($key_v as $new_key) {
                $ind_new_arr[]=$new_key;
                }
                }
                else
                {
                if(!empty($key))
                {
                $ind_new_arr[]=$key;
                }
                }
                }
                
                $industry_ids          = array_count_values($ind_new_arr);
                $keys                  = array_keys($industry_ids);
                foreach ($keys as $key) {
                $indus_id=explode(',', $key);
                $industry_name           = $this->Industry->getIndustryNameById($indus_id);
                $formated_industry[$key] = array(
                "count" => $industry_ids[$key],
                "i_name" => $industry_name[0]
                );
                }
                
                $dataArray['filteroption']['industrylist'] = $formated_industry;
                if (count($function_ids) > 0)
                {
                $function_ids = array_count_values($function_ids);
                $keys = array_keys($function_ids);
                foreach ($keys as $key) {
                $function_name = $this->f->getFunctionNameById($key);
                $formated_function[  ] = array("function_id"=>$key,"count" => $function_ids[$key], "function_job_category" => $function_name);
                }
                
                $dataArray['filteroption']['functionlist'] = $formated_function;
                }
                }*/
                $dataArray['filteroption']['cur_dis_jobids'] = $search_job_ids;
                /*               $dataArray['filteroption']['exprange']=$this->home_model->experience_years();
                $dataArray['filteroption']['salaryrange'] = $this->home_model->salary_master();*/
                $offset                                      = 0;
                if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                    $offset = $postdata['offset'];
                }
                
                $dataArray['total_record'] = count($result);
                $result                    = array_slice($result, $offset, $this->per_page);
                $dataArray['joblist']      = $result;
                $dataArray['status']       = true;
                $dataArray['message']      = "success";
            } else {
                $dataArray['joblist'] = array();
                $dataArray['status']  = false;
                $dataArray['message'] = "there is no any top job";
            }
        } else {
            $dataArray['joblist'] = array();
            $dataArray['status']  = false;
            $dataArray['message'] = "Invalid method call";
        }
        
        echo json_encode($dataArray);
    }
    
    public function runAlertQuery($orderby = "posted_on", $sort = "desc")
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $postdata = json_decode(file_get_contents('php://input'), true);
            $offset   = 0;
            if (isset($postdata['offset']) && !empty($postdata['offset'])) {
                $offset = $postdata['offset'];
            }
            
            $query  = "select query from user_alert where unique_id='" . $postdata['search_id'] . "' and user_id=" . $postdata['user_id'];
            $result = $this->db->query($query)->row_array();
            if ($result) {
                $query = $result['query'] . ",  $orderby $sort";
                $temp  = $this->db->query($query);
                if ($temp->num_rows() > 0) {
                    $ls   = "";
                    $inds = "";
                    foreach ($temp->result_array() as $key => $job) {
                        $cur_dis_jobids[] = $job['id'];
                        if (isset($job['industry_id']) && !empty($job['industry_id'])) {
                            $inds .= $job['industry_id'] . ",";
                        }
                        
                        $function_ids[] = $job['rol_id'];
                        if (isset($job['loc_id']) && !empty($job['loc_id'])) {
                            $ls .= $job['loc_id'] . ",";
                        }
                    }
                    
                    $locationids  = explode(",", trim($ls, ','));
                    $industry_ids = explode(",", trim($inds, ','));
                    if (count($cur_dis_jobids) > 0) {
                        $dataArray['filteroption']['emptypelist'] = $this->sm->getEmpTypeFormtedArray($cur_dis_jobids, 1);
                    }
                    
                    if (count($locationids) > 0) {
                        $locationids = array_count_values($locationids);
                        $keys        = array_keys($locationids);
                        foreach ($keys as $key) {
                            $location_name       = $this->loc->getNameBySingleID($key);
                            $formated_location[] = array(
                                "location_id" => $key,
                                "count" => $locationids[$key],
                                "location_name" => $location_name
                            );
                        }
                        
                        $dataArray['filteroption']['locationlist'] = $formated_location;
                    }
                    
                    if (count($industry_ids) > 0) {
                        $industry_ids = array_count_values($industry_ids);
                        $keys         = array_keys($industry_ids);
                        foreach ($keys as $key) {
                            $industry_name       = $this->industry->getIndustryNameById($key);
                            $formated_industry[] = array(
                                "industry_id" => $key,
                                "count" => $industry_ids[$key],
                                "industry_name" => $industry_name
                            );
                        }
                        
                        $dataArray['filteroption']['industrylist'] = $formated_industry;
                    }
                    
                    if (count($function_ids) > 0) {
                        $function_ids = array_count_values($function_ids);
                        $keys         = array_keys($function_ids);
                        foreach ($keys as $key) {
                            $function_name       = $this->fjc->getFunctionNameById($key);
                            $formated_function[] = array(
                                "function_id" => $key,
                                "count" => $function_ids[$key],
                                "function_job_category" => $function_name
                            );
                        }
                        
                        $dataArray['filteroption']['functionlist'] = $formated_function;
                    }
                    
                    $dataArray['filteroption']['cur_dis_jobids'] = $cur_dis_jobids;
                    $dataArray['filteroption']['exprange']       = $this->u->expRangeService();
                    $dataArray['filteroption']['salaryrange']    = $this->u->salaryRangeService();
                    $dataArray['total_record']                   = $temp->num_rows();
                    $result                                      = array_slice($temp->result_array(), $offset, $this->per_page);
                    foreach ($result as $key => $job) {
                        $job["description"]           = html_entity_decode(strip_tags($job["description"]));
                        $job["about_company"]         = html_entity_decode(strip_tags($job["about_company"]));
                        $industry_ids                 = array_merge($industry_ids, explode(",", $job['industry_id']));
                        $job['function_job_category'] = $this->fjc->getFunctionNameById($job['function_category_id']);
                        $job['industry_name']         = implode(",", $this->industry->getIndustryNameById(explode(",", $job['industry_id'])));
                        $job['sharedatalink']         = base_url() . "search/getJobDetailsById/" . $job['unique_id'];
                        if (strlen($job['logo']) > 0) {
                            $url = FCPATH . comany_logo_images . $job['logo'];
                            if (file_exists($url)) {
                                $job['company_logo'] = base_url() . comany_logo_images . $job['logo'];
                            } else {
                                $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                            }
                        } else {
                            $job['company_logo'] = base_url() . 'asset/admin/images/noimage.jpg';
                        }
                        
                        if (in_array($job['id'], $applied_job_ids)) {
                            $job['apply_status'] = "Applied";
                        } else {
                            $job['apply_status'] = "Apply";
                        }
                        
                        $result[$key] = $job;
                    }
                    
                    $dataArray['joblist'] = $result;
                    $dataArray['status']  = true;
                    $dataArray['message'] = "success";
                } else {
                    $dataArray['joblist'] = array();
                    $dataArray['status']  = false;
                    $dataArray['message'] = "there is no any hot job";
                }
            }
        } else {
            $dataArray['status']  = false;
            $dataArray['message'] = "Request method is not supported";
        }
        
        echo json_encode($dataArray);
    }
}

?>
