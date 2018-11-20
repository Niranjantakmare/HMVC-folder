<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_Service extends CI_Model {

    var $client_service = "frontend-angular-client";
    var $auth_key       = "angularsamplerestapikey";

    public function check_auth_client(){
        $client_service = $this->input->get_request_header('Client-Service', TRUE);
        $auth_key  = $this->input->get_request_header('Auth-Key', TRUE);
        if($client_service == $this->client_service && $auth_key == $this->auth_key){
            return true;
        } else {
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        }
    }

    public function login($email_id,$password){
        $q  = $this->db->select('unique_id,password,performerID')->from('performers')->where('email',$email_id)->get()->row();
        if($q == ""){
            return array('status' => 204,'message' => 'email id not found.');
        } else {
            $hashed_password = $q->password;
            $id              = $q->performerID;
		    $unique_id              = $q->unique_id;
         	$confirm_password=MD5($password);				
			if ($confirm_password==$hashed_password) {
               $last_login = date('Y-m-d H:i:s');
               $token = crypt(substr( md5(rand()), 0, 7));
               $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
               $this->db->trans_start();
               $this->db->where('performerID',$id)->update('performers',array('last_login' => $last_login));
               $this->db->insert('users_authentication',array('users_id' => $unique_id,'token' => $token,'expired_at' => $expired_at));
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'message' => 'Successfully login.','user_d' => $unique_id, 'token' => $token);
               }
            } else {
               return array('status' =>204,'message' => 'Wrong password.');
            }
        }
    }

    public function logout(){
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $this->db->where('users_id',$users_id)->where('token',$token)->delete('users_authentication');
        return array('status' => 200,'message' => 'Successfully logout.');
    }

    public function auth(){
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
	  
        $q  = $this->db->select('expired_at')->from('users_authentication')->where('users_id',$users_id)->where('token',$token)->get()->row();
        if($q == ""){
			return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        } else {
            if($q->expired_at < date('Y-m-d H:i:s')){
                return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));
            } else {
                $updated_at = date('Y-m-d H:i:s');
                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
                $this->db->where('users_id',$users_id)->where('token',$token)->update('users_authentication',array('expired_at' => $expired_at,'updated_at' => $updated_at));
                return array('status' => 200,'message' => 'Authorized.');
            }
        }
    }

    public function performer_all_data(){
        return $this->db->select('performerID,firstname,lastname,message,image')->from('performers')->order_by('performerID','desc')->get()->result();
    }

    public function performer_detail_data($id){
        return $this->db->select('performerID,unique_id,firstname,lastname,message,image')->from('performers')->where('unique_id',$id)->order_by('performerID','desc')->get()->row();
    }

    public function performer_create_data($data){
        $this->db->insert('performers',$data);
        return array('status' => 201,'message' => 'Data has been created.');
    }

    public function performer_update_data($id,$data){
        $this->db->where('performerID',$id)->update('performers',$data);
        return array('status' => 200,'message' => 'Data has been updated.');
    }

    public function performer_delete_data($id){
        $this->db->where('performerID',$id)->delete('performers');
        return array('status' => 200,'message' => 'Data has been deleted.');
    }


	public function get_performer_songslist($unique_id){
		$result = $this->db->query("SELECT sg.songID,sg.name,sg.artist,ps.is_favorite FROM `performers` pf, 	performersongs ps,songs sg  WHERE  	pf.performerID=ps.performerID and ps.songID=sg.songID and pf.unique_id='$unique_id' order by sg.songID");
		return $result->result_array();
	
	}
}