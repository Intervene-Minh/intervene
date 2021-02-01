<?php
	
	 if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/*
	200 => success
    400 => error
	404 => not found
	401 => authentification
	301	=> data wrong
	305	=> data exist
	303	=> error api fault
	*/
include APPPATH . 'libraries/REST_Controller.php';


class LoginController extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login');
    }
    /**
     * @OA\Post(path="/LoginController/registration",
     *   tags={"login"},
     *   summary="",
     *   description="",
     *   operationId="projectStep3",
	 *         @OA\Parameter(
     *           name="name",
     *           description="your username",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     		  @OA\Parameter(
     *           name="email_id",
     *           description="your email_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     		  @OA\Parameter(
     *           name="password",
     *           description="your password",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     		  @OA\Parameter(
     *           name="location_id",
     *           description="your location_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *		   format = "int32"
     *     )
     *         ),
     		  @OA\Parameter(
     *           name="device_token",
     *           description="your device_token",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     		  @OA\Parameter(
     *           name="device_type",
     *           description="your device_type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *		   format = "int32"
     *     )
     *         
     *       ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */      
    public function registration_post()
    {

        try
        {
            $res = array();
            $res['status'] = false;
            $name = $this->post('name');
            $emailId = $this->post('email_id');
            $password = $this->post('password');
            $location = $this->post('location_id');
            $device_token = $this->post('device_token');
            $device_type = $this->post('device_type');
            $user_title = $this->post('user_title');

            $data = array(
                'guid_id' => '0',
                'user_title' => $user_title,
                'name' => $name,
                'email_id' => $emailId,
                'password' => $password,
                'location_id' => $location,
                'active_token' => getNewAccessToken(5),
                'status' => 1,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'created' => getCurrDateTime(),
			);
			
   //          $isEmailExists = $this->Login->emailExists($emailId);
			// if(!empty($isEmailExists))
			// {
			// 	$res['status'] = false;
			// 	$res['response']['error'] = "Email id already registered, please try different.";
			// 	$this->response($res);
			// }
            
			// $result = $this->Login->insertUserData($data);

			// //update guid id
   //  		$guid_id = "GUID" . date('Y') . '0' . $result;
   //  		$update_user_data = array('guid_id' => $guid_id);
			// $this->Login->updateUserData($result, $update_user_data);
            

            $isEmailExists = $this->Login->emailExists($emailId);
            $isInvited = (!empty($isEmailExists) && isset($isEmailExists[0]['is_invited'])) ? $isEmailExists[0]['is_invited'] : 0;
            $checkmateUserIDInfo = (!empty($isEmailExists) && isset($isEmailExists[0]['id'])) ? $isEmailExists[0]['id'] : 0;
            if(!empty($isEmailExists) && $isEmailExists[0]['is_invited'] == 0)
            {

                $res['status'] = false;
                $res['response']['error'] = "Email id already registered, please try different.";
                $this->response($res);
            }
                
            if($isInvited == 1 && $checkmateUserIDInfo > 0)
            {
                $data['guid_id'] = $isEmailExists[0]['guid_id'];
                $this->Login->updateUserData($checkmateUserIDInfo, $data);
                $result = $checkmateUserIDInfo;

            } else {
                $result = $this->Login->insertUserData($data);
                //update guid id
                $guid_id = "GUID" . date('Y') . '0' . $result;
                $update_user_data = array('guid_id' => $guid_id);
                $this->Login->updateUserData($result, $update_user_data);
            }


			//insert user device
			$token_data = array(
				'user_id' => $result,
				'device_type' => $device_type,
				'device_token' => $device_token,
				'status' => 1,
				'created' => getCurrDateTime(),
			);
			// $this->Login->insertUserDevice($token_data);

			//insert user session
			$token_data = array(
				'user_id' => $result,
				'login_date_time' => getCurrDateTime(),
				'logout_date_time' => getCurrDateTime(),
				'access_token' => getNewAccessToken(),
				'status' => 1,
				'created' => getCurrDateTime(),
			);

            $this->Login->insertUserSession($token_data);

            $emailData = array();
            $emailData['full_name'] = $user_title.' '.$name;
            $emailData['username'] = $name;
            $emailData['password'] = $password;
            $emailData['email'] = $emailId;
            $emailData['messages'] = '';
            $emailData['active_code'] = getNewAccessToken();

			/* Send email functionality commented on 30th oct,
			 * A landing page is required to validate  account*/
			/*
			$message = '';
			$message .= '<strong>Please confirm your email address.</strong><br><br>';
			$message .= 'You are almost there! Please click the link below to create your account Password!. <br>';

			$data = array(
				'content' => $message,
			);
			$config = array(
				'mailtype' => 'html',
			);

			$from_email = _APP_EMAIL_;
			$to_email = $this->input->post('emailId');			
			$this->load->library('email', $config);
			$this->email->from($from_email, _APP_NAME_);
			$this->email->to($to_email);
			$this->email->subject('Check Mate registration');
			$this->email->message($content);
			$this->email->send();
			*/
			if ($result) {
				$this->sendmail('registration_email','registration',$emailId,$emailData);
				$res['status'] = true;
				$res['response']['success'] = "Thank you for registering, please verify your email to login to CheckMate.";
			} else {
				$res['status'] = false;
				$res['response']['error'] = "Registration failed..Try Again..";
			}

            $this->response($res);
		} 
		catch (Exception $e) 
		{
            $this->response($e->getMessage());            
        }
    }
    
/*
    public function useractivation_post()
    {

        $res = array();
        $res['status'] = false;

        $token = $this->post('token');

        if (empty($token)) {
            $res['status'] = false;
            $res['response']['error'] = "Please enter token.";
        } else {
            $result = $this->Login->userAccActivation($token);
			
            if ($result == 1) {
                $res['status'] = true;
                $res['response']['success'] = "Your account has been activated, please login to continue.";
            } elseif ($result == 2) {
                $res['status'] = true;
                $res['response']['success'] = "Your account has been already activated";
            } elseif ($result == 2) {
                $res['status'] = true;
                $res['response']['success'] = "Invalid token.";
            } else {
                $res['status'] = false;
                $res['response']['error'] = "There some problem for activation";
            }
            $user = $this->getUserDataByActiveToken($token);
            $this->sendmail('activation','activation',$user['email_id'],array('message'=>''));
        }
        $this->response($res);
    }
*/
    /**
     * @OA\Post(path="/LoginController/login",
     *   tags={"login"},
     *   summary="",
     *   description="",
     *   operationId="login",
     @OA\Parameter(
     *           name="email_id",
     *           description="your email_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="password",
     *           description="your password",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="device_token",
     *           description="your device_token",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="device_type",
     *           description="your device_type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *		   format = "int32"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
	public function login_post()
    {

        $this->load->library('form_validation');
        $res = array();
        $res['status'] = false;

        $email_id = $this->post('email_id');
        $password = $this->post('password');
        $device_token = $this->post('device_token');
        $device_type = $this->post('device_type');

        if(empty($email_id) || empty($password))
        {
            $res['status'] = false;
            $res['response']['error'] = "Please provide username or password";
            $this->response($res);
        }
        
        // $this->form_validation->set_error_delimiters('', '');

		// if ($this->form_validation->run('login') == false) 
		// {
        //     $errors = stripData(validation_errors());
        //     $res['response'] = $errors;
		// } 
		// else 
		// {
            $send_data = array(
                'email_id' => $email_id,
                'password' => $password,
                'device_token' => $device_token,
                'device_type' => $device_type,
            );

			$result = $this->Login->authenticate_user($email_id, $password);
			
            if ($result) {
                $this->Login->update_insert_device_token($result->id, $device_token, $device_type);
                $res['status'] = true;
                $res['response']['success'] = "Login Successfully";
                $res['response']['user_data'] = $result;
            } else {
                $res['status'] = false;
                $res['response']['error'] = "Login failed..Try Again..";
            }
        // }
        $this->response($res);

    }

	/*
    public function login_post()
    {

        $this->load->library('form_validation');
        $res = array();
        $res['status'] = false;

        $email_id = $this->post('email_id');
        $password = $this->post('password');
        $device_token = $this->post('device_token');
        $device_type = $this->post('device_type');

        $this->form_validation->set_error_delimiters('', '');

		if ($this->form_validation->run('login') == false) 
		{
            $errors = stripData(validation_errors());
            $res['response'] = $errors;
		} 
		else 
		{
            $send_data = array(
                'email_id' => $email_id,
                'password' => $password,
                'device_token' => $device_token,
                'device_type' => $device_type,
            );

			$result = $this->Login->checkValidUser($send_data);
			
            if ($result) {
                $res['status'] = true;
                $res['response']['success'] = "Login Successfully";
                $res['response']['user_data'] = $result;
            } else {
                $res['status'] = false;
                $res['response']['error'] = "Login failed..Try Again..";
            }
        }
        $this->response($res);

    }
	*/
	/**
     * @OA\Post(path="/LoginController/logout",
     *   tags={"login"},
     *   summary="",
     *   description="",
     *   operationId="logout",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     description="",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *		   format = "int32"
     *     ),
     *     description="",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
    public function logout_get()
    {
        $output = array();
        $output['status'] = false; 
        $data = array(
            'user_id',
            'logged_in' => false,
        );
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
        if ($this->session->userdata('user_id') == false) {
            $output['status'] = true;
            $output['logout'] = "You have successfully logout";
        }
        $this->response($output);
    }

	public function sendmail($type,$subject,$toEmail,$data)
    {
	        $this->load->library('parser');
            $this->load->library('email');
            $this->email->set_newline("\r\n");
            $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
            $this->email->set_header('Content-type', 'text/html');

            $this->email->from('webmaster@subbieapp.com', 'checkmate.com');
    
            $this->email->to($toEmail);  
            $this->email->subject($subject); 

            $body = $this->parser->parse('emails/'.$type.'.php',$data,TRUE);
            $this->email->message($body);   
            $this->email->send();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
