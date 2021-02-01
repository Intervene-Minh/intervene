<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Rabbit extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function insertUserData($data)
    {
        $this->db->insert('checkmate_user', $data);
        return $this->db->insert_id();
    }

    public function updateUserData($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('checkmate_user', $data);
        return true;
    }

	public function insertWorkinghours($data)
    {
	   
        $this->db->insert('ref_working_hours', $data);
        return $this->db->insert_id();
    }
    public function insertUserDevice($data)
    {
        return $this->db->insert('checkmate_user_device', $data);
    }

    public function insertUserSession($data)
    {
        return $this->db->insert('checkmate_user_session', $data);
    }
    public function insertInduction($data)
    {
        return $this->db->insert('checkmate_user_induction', $data);
    }
    public function insertScreenshot($data)
    {
         $this->db->insert('checkmate_user_skills_screenshots', $data);
         return $this->db->insert_id();
    }
    public function insertSkills($data)
    {
        return $this->db->insert('checkmate_user_skills', $data);
    }
    public function insertTax($data)
    {
        return $this->db->insert('checkmate_user_tax', $data);
    }

    public function getInduction($user_id)
    {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
		$this->db->from('checkmate_user_induction');

        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getSkill($user_id)
    {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $this->db->from('checkmate_user_skills');
        $result = $this->db->get()->result_array();

        return $result;
    }
    public function getTax($user_id)
    {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $this->db->from('checkmate_user_tax');
        $result = $this->db->get()->result_array();

        return $result;
    }
	public function userAccActivation($token)
    {
        $this->db->select('*');
        $this->db->where('code', $token);
        $this->db->from('checkmate_user_rabbit');
        $query = $this->db->get();
        $result = $query->row();

        if ($result) {

            if ($result->status == 0) {
                $this->db->where('id', $result->team_member_id);
                $this->db->update('checkmate_user', array('status' => 1));
                return 1;
            } elseif ($result->status == 1) {
                return 2;
            }
        } else {
            return 0;
        }

        return false;
    }
    public function checkValidUser($data = array())
    {

        $email_id = (isset($data['email_id']) && !empty($data['email_id'])) ? $data['email_id'] : "";
        $password = (isset($data['password']) && !empty($data['password'])) ? $data['password'] : "";
        $device_token = (isset($data['device_token']) && !empty($data['device_token'])) ? $data['device_token'] : "";
        $device_type = (isset($data['device_type']) && !empty($data['device_type'])) ? $data['device_type'] : "";

        //check for user valid or not
        $this->db->select('id,guid_id,name,email,location_id,status,ip_address');
        $this->db->where(array('email_id' => $email_id, 'password' => $password, 'status' => 1));
        $this->db->from('checkmate_user');
        $query = $this->db->get();
        $result = $query->result_array();
        //echo '<pre>';print_r($result);die;

        if (isset($result) && !empty($result)) {

            $user_reg_id = (isset($result[0]['id']) && !empty($result[0]['id'])) ? $result[0]['id'] : "";
            $user_guid_id = (isset($result[0]['guid_id']) && !empty($result[0]['guid_id'])) ? $result[0]['guid_id'] : "";
            $user_name = (isset($result[0]['name']) && !empty($result[0]['name'])) ? $result[0]['name'] : "";
            $user_email_id = (isset($result[0]['email_id']) && !empty($result[0]['email_id'])) ? $result[0]['email_id'] : "";
            $access_token = getNewAccessToken();

            $this->db->select('*');
            $this->db->where('user_id', $user_reg_id);
            $this->db->from('checkmate_user_device');
            $query1 = $this->db->get();
            $result1 = $query1->result_array();

            if (isset($result1) && !empty($result1)) {

                $this->db->where('user_id', $user_reg_id);
                $this->db->update('checkmate_user_device', array('device_type' => $device_type, 'device_token' => $device_token));

            } else {

                $this->db->insert('checkmate_user_device', array('user_id' => $user_reg_id, 'device_type' => $device_type, 'device_token' => $device_token, 'status' => 1, 'created' => getCurrDateTime()));
            }

            //update user session data
            $this->db->where('user_id', $user_reg_id);
            $this->db->update('checkmate_user_session', array('status' => 0));

            $this->db->insert('checkmate_user_session', array('user_id' => $user_reg_id, 'login_date_time' => getCurrDateTime(), 'logout_date_time' => getCurrDateTime(), 'access_token' => $access_token, 'status' => 1, 'created' => getCurrDateTime()));

            $r_data = array(
                'user_id' => $user_reg_id,
                'guid_id' => $user_guid_id,
                'user_name' => $user_name,
                'user_email_id' => $user_email_id,
                'access_token' => $access_token,
            );
            return $r_data;
        } else {
            return false;
        }
    }
    public function emailExists($Emailid)
    {

        $condition = array("email" => $Emailid);
        $this->db->select('email');
        $this->db->from('checkmate_user');
        $this->db->where($condition);
        return $this->db->get()->result_array();

	}
    public function getUserDataByActiveToken($token)
    {
        $this->db->select('*');
        $this->db->from('checkmate_user_rabbit');
		$this->db->where('code', $token);

        $result = $this->db->get()->row();
        return $result;
    }
    public function getTeamUserData($id)
    {
        $this->db->select('*');
        $this->db->from('checkmate_user');
		$this->db->where('id', $id);

        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getUsersByType($type,$keyword='')
    {
	
		$this->db->select("*");
		$this->db->from('checkmate_user');
		$this->db->where('user_type', $type);
		if(!empty($keyword)){
		$this->db->or_where('id', $keyword);
		$this->db->like('name', $keyword, 'both');
		$this->db->like('email', $keyword, 'both');
		}
		$result = $this->db->get()->result_array();
        return $result;
    }
   
    public function getScreenshots($ids)
    {
		$idArray = explode(',', $ids);
		$this->db->select("*");
		$this->db->from('checkmate_user_skills_screenshots');
		$this->db->where_in('id', $idArray);
		
		$result = $this->db->get()->result_array();
        return $result;
    }
   
            
	public function authenticate_user($user_email, $user_pass)
	{
		$auth_data = $this->db->where(['email'=>$user_email, 'password'=>$user_pass])->from('checkmate_user')->get()->row();

		if(empty($auth_data)) {
			$auth_data = $this->db->where(['email_id'=>$user_email, 'password'=>$user_pass])->from('checkmate_user')->get()->row();
			if(empty($auth_data)) {
				return false;
			}
			else if($auth_data->status == 0){
				 return false;
			}else{
				  $tokenData['id'] = $auth_data->id; //TODO: Replace with data for token
				  $auth_data->access_token =  AUTHORIZATION::generateToken($tokenData);
				  return $auth_data;
			}
		}else if($auth_data->status == 0){
				 return false;
		}
        $tokenData['id'] = $auth_data->id; //TODO: Replace with data for token
		$auth_data->access_token =  AUTHORIZATION::generateToken($tokenData);

		return $auth_data;
	}
}
