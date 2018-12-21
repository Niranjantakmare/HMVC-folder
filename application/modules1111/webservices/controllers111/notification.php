<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class notification extends CI_Controller
{
   public  $curl_error="";
   public  $curl_result="";
   
    public function __construct() {

        parent::__construct(); 
     
        
        
        
    }
    
    
     public function storeUser($name, $email, $gcm_regid) 
    {
      
        
        $data['name']=$name;
        $data['email']=$email;
        $data['gcm_regid']=$gcm_regid;
        $data['created_at']=date("Y-m-d H:i:s");
       
        $this->db->insert("gcm_users",$data);
        $insertedid=$this->db->insert_id();
        if($insertedid)
        {  
             $query="SELECT * FROM gcm_users WHERE id =".$insertedid;
             $result=$this->db->query($query)->row_array();
             if(count($result)>0)
             {
                 return $result;
                 
             }
             else
             {
                 return false; 
                 
             }    
             
        }
        else
        {
              return false; 
            
        }    
        
        
        
        
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmail($email) {
        $result = $this->db->query("SELECT * FROM gcm_users WHERE email = '$email' LIMIT 1")->row_array();
      
         echo json_encode($result);
    }

    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = $this->db->query("select * FROM gcm_users")->result_array();
      
       return $result;
        
    }
     public function getAllUsersDetails() {
        $result = $this->db->query("select * FROM gcm_users")->result_array();
      
       echo json_encode($result);
        
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $result = $this->db->query("SELECT email from gcm_users WHERE email = '$email'")->row_array();
       
        if (count($result) > 0) {
            
            return true;
        } else {
            // user not existed
            return false;
        }
    }

    
    
    
    
    public function send_notification($registatoin_ids, $message) 
    {
        // include config
       
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        
        $fields = array(
                'registration_ids'  => array($registatoin_ids),
                'data'              => array( "message" => $message,"typeid"=>"1" ),
                );

        $headers = array(
            'Authorization: key='.trim(GOOGLE_API_KEY),
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
           $this->curl_error='Curl failed: '. curl_error($ch);
           return 0;
        }

        // Close connection
        curl_close($ch);
        $this->curl_result=$result;
       return 1;
    }
    
    
    
    public function sendMessage($mailid=null,$message)
    {
      
        
        if($mailid!=null)
        {
           $user=$this->db->query("select * from gcm_users where email='".$mailid."'")->row_array();
           $result = $this->send_notification($user['gcm_regid'], $message);
            
        }
        else
        {    
            $result=$this->getAllUsers();

            foreach($result as $user)
            {    $message="hi sushant";
              $result = $this->send_notification($user['gcm_regid'], $message);
              if($result==1)
              {
                  $dataArray['result']=$this->curl_result;
                  $dataArray['key']=trim(GOOGLE_API_KEY);
              }  
              else 
              {
                  $dataArray['result']=$this->curl_error;
                  //$dataArray['key']=GOOGLE_API_KEY;
              }
            }
           echo json_encode($dataArray); 
        }   
    
        
    }  
    
   public function registerDevice()
    {         
                if( $this->input->server('REQUEST_METHOD') === 'POST' ) 
                {    
                      
     

                     if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["regId"])) 
                     { 
                        $name = $_POST["name"];
                        $email = $_POST["email"];
                        $gcm_regid = $_POST["regId"]; // GCM Registration ID
                       
                           $checkresult=$this->db->query("select * from gcm_users where  email='".$email."'");
                            
                            if($checkresult->num_rows()==0)
                            {
                                
                                 $result = $this->storeUser($name, $email, $gcm_regid);
                                 
                                 if($result)
                                 {
                                     
                                     $dataArray['status']=true;
                                     $dataArray['message']='success';
                                     $dataArray['result']=$result;
                                 }
                                 else
                                 { 
                                         $dataArray['status']=false;
                                         $dataArray['message']=SOMETHING_WRONG;
                                 }
                                
                            }
                            else
                            {
                                
                                   $result=$this->db->query("update gcm_users set gcm_regid=?",array($gcm_regid));
                                  
                                   if($result || $result->affected_rows() )
                                   {

                                       $dataArray['status']=true;
                                       $dataArray['message']='success';
                                       $dataArray['result']=$result;
                                   }
                                   else
                                   { 
                                           $dataArray['status']=false;
                                           $dataArray['message']=SOMETHING_WRONG;
                                   }
                                
                            }   
                            
                   
                        /*
                       $checkresult=$this->db->query("select * from gcm_users where  gcm_regid='".$gcm_regid."'");
                       if($checkresult->num_rows()==0)
                       {
                           $result = $this->storeUser($name, $email, $gcm_regid);

                          /* $registatoin_ids = array($gcm_regid);
                           $message = array("product" => "shirt");

                            $result = $this->send_notification($gcm_regid, $message);

                            if($result==0)
                            {
                                 $dataArray['status']=false;
                                 $dataArray['message']='fail';
                                  $dataArray['error']=$this->curl_error;

                            }
                            else 
                            { */
                                 $dataArray['status']=true;
                                 $dataArray['message']='success';
                                  $dataArray['result']=$result;
                           /* }


                        }  else {
                            $dataArray['status']=false;
                        $dataArray['message']=SOMETHING_WRONG;
                        } 
                        */


                     }       
                     else 
                    {
                        $dataArray['status']=false;
                        $dataArray['message']=SOMETHING_WRONG;
                    }


                }
                else 
                {
                    $dataArray['status']=false;
                    $dataArray['message']="Request Method is not supported"; 
                }
    
    echo json_encode($dataArray);
}
  
    
    
    

public function sendOfferNotification()
{

    $this->load->library("comman_class");
    $this->load->model("user","u"); 

    $resultarray=$this->db->query("select *, group_concat(recruiter_job_offer.job_id) as  jobsid, users.email_id,count(*) as offer_count from recruiter_job_offer inner join users on users.id=recruiter_job_offer.user_id where notification_flag=0 group by user_id");
    if($resultarray->num_rows()>0)
    {    $offerlist=$resultarray->result_array();
        foreach($offerlist as $offer)
        {
            
                 $where_query = "recruiter_job_offer.user_id=".$offer['user_id']." AND jobs.id in (" . $offer['jobsid'] . ")  ";
                 
                 $resultobject=$this->u->joboffer($where_query);
            
            if($resultobject->num_rows()>0)
            {     
                //echo $resultobject->num_rows()."   ".$offer['user_id']."<br>";
                
                 $subject= " You have ".$resultobject->num_rows()." new job offer";
                 $data = array("message" =>$subject, "offer_count"=>$resultobject->num_rows(), "typeid" => 3, "user_id" => $offer['user_id']);
                 $this->comman_class->sendMessage($offer['email_id'], $data);
                 
            }   

        }
        
        
        
        
    }
}

    public function unregisterDevice()
    {         
                if( $this->input->server('REQUEST_METHOD') === 'POST' ) 
                { 
                    if (isset($_POST["regId"])) 
                     {
                        $id=$_POST["regId"];
                        $this->db->where('gcm_regid', $id);
                        $this->db->delete('gcm_users');
                        $dataArray['status']=true;
                        $dataArray['message']='success';
                     }else 
                    {
                        $dataArray['status']=false;
                        $dataArray['message']=SOMETHING_WRONG;
                    }


                }
                else 
                {
                    $dataArray['status']=false;
                    $dataArray['message']="Request Method is not supported"; 
                }
                echo json_encode($dataArray);
    }
                
  
    
        
    
    
    
    
    
    
    
    
    
    
    
    


}    