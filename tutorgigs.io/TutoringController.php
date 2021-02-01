<?php 
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include APPPATH . 'libraries/REST_Controller.php';
error_reporting(1);

/** @@ new modules.
 * @OA\Info(
 *     description="checkmate api.  You can find
out more about api at http://subbieapp.com/",
 *     version="1.0.0",
 *     title="Swagger checkmate api",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="dinhminhquoi@yahoo.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * ),
 *     @OA\Server(
 *         description="OpenApi host",
 *         url="http://subbieapp.com/checkmate/api/"
 *     ),
  *     @OA\Server(
 *         description="OpenApi host",
 *         url="http://checkmate/"
 *     )
 */

class TutoringController extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Project');
       // $this->load->model('Login');
        
    }

    /* Author : Kailash
     * Date : 25 Oct 2018
     * Modification after client call
     */

    /* Possible option for MASTER_TYPE
     *
     * GET_SITE_CONTRACTOR
     * GET_CLIENT
     * GET_PROJECT_DIRECTOR
     * GET_PROJECT_MANAGER
     * GET_SITE_MANAGER
     * GET_SITE_FOREMAN
     * GET_SITE_ENGINEER
     * GET_HEALTH_AND_SAFETY_REP
     * GET_CONTRACT_ADMINISTRATOR
     *
     */
    /*
    @Grade List 
    @ Type-GET 
    #for every  API .Drhomework123456, [{"key":"Tutoring_client_id","value":"Drhomework123456","description":""}] 

    **/
    public function GradeList_get(){

        $res = [];
        /*MASTER_TYPE
           */
//         $response = array();$input=[];

//    $system_warnings=[];  // 
//    // echo 'CreateTutoringSessions Created! ';  die; 
//    $Tutoring_client=$this->post('Tutoring_client_id');   
//    $drhomework_ref_id=$this->post('drhomework_ref_id');  // ID - client side : TutrongMainID
//    $system_warnings[]='Please select data!  ';

//    print_r($system_warnings); die; 

   //////////////////////////

    $val2 = array(1,2617,2618);

    $this->db->select('id,name,taxonomy');
    $this->db->from('terms');
    $this->db->where_in('parent',$val2);
    $this->db->where('active',1);
    $this->db->where('taxonomy','category');
    $grade_list = $this->db->get()->result_array();

    if(isset($grade_list)){
        $res['status'] = true;
        $res['response']['success']['result'] = $grade_list;
        $this->response($res);
    }else{
        $res['status'] = false;
        $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
        $this->response($res);
    } 

    }



      /***
    get list of schools
      **/


    public function SchoolList_post(){

    $res = [];

    if ($this->post('district_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please enter district_id.";
            $this->response($res);
        }

    $district_id = $this->post('district_id');

    $this->db->select('id,school_name');
    $this->db->from('master_schools');
    $this->db->where('district_id',$district_id);
    $school_list = $this->db->get()->result_array();

    if(isset($school_list)){
        $res['status'] = true;
        $res['response']['success']['result'] = $school_list;
        $this->response($res);
    }else{
        $res['status'] = false;
        $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
        $this->response($res);
    } 

    }



      /***
    get list of tutors
      **/


      public function TutorList_get(){

        $res = [];

        $this->db->select('id,f_name,lname,email,notify_all,notify_jobs');
        $this->db->from('gig_teachers');
        $this->db->where('notify_all','yes');
        $this->db->where('notify_jobs','yes');
        $tutor_list = $this->db->get()->result_array();
    
        if(isset($tutor_list)){
            $res['status'] = true;
            $res['response']['success']['result'] = $tutor_list;
            $this->response($res);
        }else{
            $res['status'] = false;
            $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
            $this->response($res);
        } 
    
        }


   /***
    mail function
      **/

      public function sendmail($type,$subject,$toEmail,$data)
      {
          $this->load->library('parser');
          $this->load->library('email');
          $this->email->set_newline("\r\n");
          $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
          $this->email->set_header('Content-type', 'text/html');

          $this->email->from('support@tutorgigs.io');
  
          $this->email->to($toEmail);  
          $this->email->subject($subject); 

          $body = $this->parser->parse('emails/'.$type.'.php',$data,TRUE);
          $this->email->message($body);   
          $this->email->send();
      }





      /***
    notification email
      **/

        public function notificationEmail_post()
        {
            $res = [];
            $emailId = $this->post('email_id');
            $first_name = $this->post('first_name');
            $last_name = $this->post('last_name');

            $this->db->select('id,f_name,lname,email,notify_all,notify_jobs');
            $this->db->from('gig_teachers');
            $this->db->where('notify_jobs','yes');
            $tutor_list = $this->db->get()->result_array();

            $emailData = array();
            $emailData['full_name'] = $first_name.' '.$last_name;
            $emailData['username'] = $first_name.' '.$last_name;
            $emailData['email'] = $emailId;
            $emailData['messages'] = ' HI , Tutor, 
            A new job found Session ID -2345';

            if (!empty($tutor_list)) {
				$this->sendmail('notification_email','New Job found- Tutorgigs.io',$emailId,$emailData);
				$res['status'] = true;
				$res['response']['success'] = "Thank you for registering, please verify your email to login to CheckMate.";
			} else {
				$res['status'] = false;
				$res['response']['error'] = "Registration failed..Try Again..";
			}

            $this->response($res);
        }






     public function siteMaster_post()
     {
        /*MASTER_TYPE
           */
        $response = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $response['status'] = false;
            $response['response']['error'] = 'Invalid access token.';
            $this->response($response);
        }

        $master_type = $this->post('MASTER_TYPE');
        $search_name = $this->post('SEARCH_TEXT');

        if (empty($master_type)) {
            $response['status'] = false;
            $response['response']['error'] = 'Please provide master type.';
            $this->response($response);
        }
        $designation = $this->Project->get_designation($master_type);

        $teamDesignationList = $this->Project->teamByDesignationList($master_type, $search_name);

        if (empty($teamDesignationList)) {
            $response['status'] = false;
            $response['response']['error']['message'] = 'No result found !!!';
            $this->response($response);
        }

        $response['status'] = true;
        $response['response']['success']['result'] = $teamDesignationList;
        $this->response($response);        
    }

    /**
    @ API-  CreateTutoringSessions  
 [Tutoring_client_id --  Drhomework123456
  drhomework_ref_id -- ID: dr_sessions
  is_drhomework -- yes
  ses_start_time  --
  ses_end_time --- 
  # No activity for  Dr. homework session . 
  # Optional exit QUiz or Assignment at- Drhomework side.  
 ]

    **/

}