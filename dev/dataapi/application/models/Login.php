<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends CI_Model
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

    public function insertUserDevice($data)
    {
        return $this->db->insert('checkmate_user_device', $data);
    }

    public function insertUserSession($data)
    {
        return $this->db->insert('checkmate_user_session', $data);
    }

    public function update_insert_device_token($user_reg_id, $device_token, $device_type) {
			$this->db->select('*');
			$this->db->where('user_id', $user_reg_id);
			$this->db->where('device_token', $device_token);
			$this->db->from('checkmate_user_device');
			$query1 = $this->db->get();
			$result1 = $query1->result_array();
			if (isset($result1) && !empty($result1)) {
				
			  $this->db->where('device_token', $device_token);
              $this->db->delete('checkmate_user_device');
                if($this->db->affected_rows() > 0) 
                {
                  $this->db->insert('checkmate_user_device', array('user_id' => $user_reg_id, 'device_type' => $device_type, 'device_token' => $device_token, 'status' => 1, 'created' => getCurrDateTime()));
                }
			} else {

				$this->db->insert('checkmate_user_device', array('user_id' => $user_reg_id, 'device_type' => $device_type, 'device_token' => $device_token, 'status' => 1, 'created' => getCurrDateTime()));
			}
	}
	
    public function userAccActivation($token)
    {
        $this->db->select('*');
        $this->db->where('active_token', $token);
        $this->db->from('checkmate_user');
        $query = $this->db->get();
        $result = $query->row();

        if ($result) {

            if ($result->status == 0) {
                $this->db->where('id', $result->id);
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
        $this->db->select('id,guid_id,name,email_id,location_id,status,ip_address');
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
        $condition = array("email_id" => $Emailid);
        $this->db->select('email_id,is_invited,id,guid_id');
        $this->db->from('checkmate_user');
        $this->db->where($condition);
        return $this->db->get()->result_array();

	}
	public function emailExistsInvite($Emailid)
    {
        $condition = array("email_id" => $Emailid);
        $this->db->select('email_id');
        $this->db->from('checkmate_user');
        $this->db->where($condition);
        return $this->db->get()->result_array();
	}
    
	public function getUserData($id)
    {
        $this->db->select('*');
        $this->db->from('checkmate_user');
		$this->db->where('id', $id);

        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getUserDataByActiveToken($token)
    {
        $this->db->select('*');
        $this->db->from('checkmate_user');
		$this->db->where('active_token', $token);

        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getTeamUserData($id)
    {
        $this->db->select('*');
        $this->db->from('checkmate_team');
		$this->db->where('id', $id);

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getJobOfferData($id)
    {
        $this->db->select('*');
        $this->db->from('checkmate_project_resources');
        $this->db->join('checkmate_project','checkmate_project.id = checkmate_project_resources.project_id','inner');
        $this->db->where('user_id', $id);

        $result = $this->db->get()->result_array();
        return $result;
    }
    
  //   public function getUsersByType($type,$keyword='',$projectId='')
  //   {
	
		// $this->db->select("`cu.id`, `cu.guid_id`, `cu.name`, `cu.email_id`, `cu.location_id`, `cu.status`, `cu.ip_address`, `cu.created`, `cu.avatar`, `cu.phone_no`, `cu.address`, `cu.user_type`, `cu.created_at`, `cu.updated_at`, `cu.employee_name`, `cu.client_name`, `cu.designation`,if(cu.user_type =2 ,`cu.user_title` , '') as `user_title`, ( select count(cur.id) from checkmate_user_rabbit cur where cur.team_member_id = cu.id limit 1 ) as isAvailabelInRabbit");
		// $this->db->from('checkmate_user cu');
  //       if(!empty($projectId))
  //       {
  //           $this->db->join('checkmate_project_resources cp','cp.user_id = cu.id','inner');
  //           $this->db->where('cp.project_id', $projectId);
  //       }
  //       if(!empty($type))
  //       {
  //           $this->db->where('user_type', $type);    
  //       }
        
		// if(!empty($keyword))
  //       {
		//     $this->db->or_where('id', $keyword);
	 //       	$this->db->like('name', $keyword, 'both');
		//     $this->db->like('email_id', $keyword, 'both');
		// }
		// $result = $this->db->get()->result_array();
  //       return $result;
  //   }

    public function getUsersByType($type,$keyword='',$projectId='')
    {   // Project Users list
    
        $this->db->select("`cu.id`, `cu.guid_id`, `cu.name`, `cu.email_id`, `cu.location_id`, `cu.status`, `cu.ip_address`, `cu.created`, `cu.avatar`, `cu.phone_no`, `cu.address`, `cu.user_type`, `cu.created_at`, `cu.updated_at`, `cu.employee_name`, `cu.client_name`, `cu.designation`,if(cu.user_type =2 ,`cu.user_title` , '') as `user_title`, ( select count(cur.id) from checkmate_user_rabbit cur where cur.team_member_id = cu.id limit 1 ) as isAvailabelInRabbit");
        $this->db->from('checkmate_user cu');
        if(!empty($projectId))
        {
            $this->db->join('checkmate_project_resources cp','cp.user_id = cu.id','inner');
            $this->db->where('cp.project_id', $projectId);
        }
        if(!empty($type))
        {
            $type = intval($type);
            $this->db->where('user_type', $type);
        }
        
        if(!empty($keyword))
        {
            $this->db->or_where('id', $keyword);
            $this->db->like('name', $keyword, 'both');
            $this->db->like('email_id', $keyword, 'both');
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

	public function authenticate_user($user_email, $user_pass)
	{
		$tokenData = array();
		$auth_data = $this->db->where(['email_id'=>$user_email, 'password'=>$user_pass])->from('checkmate_user')->get()->row();

		if(empty($auth_data)) {
			 return false;
/*
			$auth_data = $this->db->where(['email'=>$user_email, 'password'=>$user_pass])->from('checkmate_team')->get()->row();
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
*/
		}
		else if($auth_data->status == 0){
				 return false;
		}
        $tokenData['id'] = $auth_data->id; //TODO: Replace with data for token
		$auth_data->access_token =  AUTHORIZATION::generateToken($tokenData);

		return $auth_data;
	}
	public function send_ios_notification($token, $title, $message) {
        define('CHEKMATE_API_ACCESS_KEY', 'AAAAoZaWezY:APA91bHuC47P7N01gjvJmaYfnh_MZzMaQerNhyVOcSD3UQx3ReoALwqrOr8zx9aTFE-gSRfx17cz0B0V8RBuvnWGPAzpSHD-wrZN-rbmeA51FOt4FGDY1iGJhyKZTsjfZQ9lFG4LWZ8i');
        define('WHITE_RABIT_API_ACCESS_KEY', 'AAAArD6Z5Ks:APA91bG5H5tEBVSXRpi6JK3Gj4E0LdbG9P1IjopniIyMDoHOEZ9JiostUwpklrIq2NV1DBFK2axIYaxu7n6k0mIBxcO51qNscP3TyLyAyfGtbBOzFVA6bGlIhMXVYBDI_gaKeZuMuXM0');
        $registrationIds = $token;
        $whiteToken = array();
        $checkmate = array();
        foreach ($registrationIds as $value) {
           $this->db->select('*');
           $this->db->from('checkmate_user_device');
           $this->db->where('device_token', $value);
           $result = $this->db->get()->row();
           if($result->device_type == "whiterabbit")
           {
             $whiteToken[]= $result->device_token;
           }
           if($result->device_type == "checkmate")
           {
             $checkmate[]= $result->device_token;
           }
        }
        $data['data']['detail'] = $message;
        $data['data']['title'] = $title;
        $data['content_available'] = true;
        $data['mutable_content'] = true;



        $data['notification']['title'] = $title;
        $data['notification']['body'] = $message;

        $data['notification']['sound'] = 'default';
        $data['notification']['priotity'] = 'high';
        
        // Fill the default values required for each payload.
        if(!empty($whiteToken))
        {
            
            $data['registration_ids'] = $whiteToken;
            $headers = array
                    (
                    'Authorization: key=' . WHITE_RABIT_API_ACCESS_KEY,
                    'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);
            curl_close($ch);
            return true;
        }
        if(!empty($checkmate))
        {
            
            $data['registration_ids'] = $checkmate;
            $headers = array
                    (
                    'Authorization: key=' . CHEKMATE_API_ACCESS_KEY,
                    'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);
            curl_close($ch);
            return true;
        }
        
    }
}
