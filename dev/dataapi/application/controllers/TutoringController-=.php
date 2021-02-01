<?php 
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include APPPATH . 'libraries/REST_Controller.php';


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
        $this->load->model('Tutor');
        $this->load->library('form_validation');
       // $this->load->model('Login');
        
    }

    // function Tutoringkey_exists($key)
    // {
    //     $this->Tutor->Tutoring_client_id_exists($key);
    // }

    // function drhomeworkkey_exists($key)
    // {
    //     $this->Tutor->drhomework_ref_id_exists($key);
    // }

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


    public function TutorList_get()
    {
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

        $body = $this->parser->parse('emails/'.$type,$data,TRUE);
        $this->email->message($body);   
        $this->email->send();
    }





    /***
    notification email
    **/

    public function notificationEmail_get()
    {
        $res = [];

        $this->db->select('id,f_name,lname,email');
        $this->db->from('gig_teachers');
        $this->db->where('notify_jobs','yes');
        $tutor_list = $this->db->get()->result_array();

        foreach($tutor_list as $row){
        $emailId = $row['email'];
        $first_name = $row['f_name'];
        $last_name = $row['lname'];
    
        $emailData = array();
        $emailData['full_name'] = $first_name.' '.$last_name;
        $emailData['email'] = $emailId;
        $emailData['messages'] = ' HI , Tutor, 
        A new job found Session ID -2345';

        if (!empty($tutor_list)) {
            $this->sendmail('notification_email.php','New Job found- Tutorgigs.io',$emailId,$emailData);
            $res['status'] = true;
            $res['response']['success'] = "Thank you for registering, please verify your email to login to tutorgig.";
        } else {
            $res['status'] = false;
            $res['response']['error'] = "Registration failed..Try Again..";
        }
      }

        $this->response($res);
    }


      /***
    TutoringSessionInfo
      **/


      public function tutorSessionInfo_post()
      {
        $res = [];
        $app_warnings = [];

        if ($this->post('Tutoring_client_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please enter client_id.";
            $this->response($res);
        }

        if ($this->post('drhomework_ref_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please enter reference id.";
            $this->response($res);
        }

        $Tutoring_client_id = $this->post('Tutoring_client_id');
        $drhomework_ref_id = $this->post('drhomework_ref_id');


      //  $this->form_validation->set_rules('Tutoring_client_id', 'Tutoring_client_id', 'required');
       // $this->form_validation->set_rules('drhomework_ref_id', 'drhomework_ref_id', 'required');


    //     if($this->Tutor->Tutoring_client_id_exists($Tutoring_client_id)){
    //         $app_warnings[]='Client id does not exist! ';
    //    }



    //    if(!empty($app_warnings)){
    //       $msg_str=implode(',',$app_warnings);
    //        $response['success'] = 'false';
    //        $response['message'] = $msg_str;
    //        return $response;
    //    }

        $this->db->select('t.f_name,t.lname,t.email,t.url_aww_app,log.board_type,log.tut_teacher_id');
        $this->db->from('gig_teachers as t');
        $this->db->join('int_schools_x_sessions_log log', 't.id = log.tut_teacher_id ','left');
        $this->db->where('log.Tutoring_client_id',$Tutoring_client_id);
        $this->db->where('log.drhomework_ref_id',$drhomework_ref_id);
        $this->db->where('log.tut_teacher_id >',0);
        $session_list = $this->db->get()->row();
       // print_r($session_list);die;
           

        if(!empty($session_list) ){

            $results = array(
                'firstname' => $session_list->f_name,
                'lastname' => $session_list->lname,
                'email' => $session_list->email,
                'url' =>$session_list->url_aww_app,
                'board_type' =>$session_list->board_type,
        );

            $res['status'] = 'yes';
            $res['response'] = $results;
            $this->response($res);
        }
        else{
            $res['status'] = 'no';
            //$res['response'] = $session_list;
            $res['response']['message'] = "Waiting for tutor acceptance.";
            $this->response($res);
        } 
      
      }

    /***
    TutoringSessionDetails -- array(drhomework_ref_id)
    **/

      public function tutoringSessionDetails_post()
      {
        $res = [];
        $app_warnings = [];
        if ($this->post('Tutoring_client_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please enter client_id.";
            $this->response($res);
        }

        if ($this->post('drhomework_ref_id') == "")
        {
            $res['status'] = false;
            $res['response'] = "Please enter reference id.";
            $this->response($res);
        }

        $Tutoring_client_id = $this->post('Tutoring_client_id');
        $drhomework_ref_id = $this->post('drhomework_ref_id');

        $val2 = explode(',',$drhomework_ref_id);
       // print_r($val2);die;

        $this->db->select('t.f_name,t.lname,t.email,t.url_aww_app,log.board_type,log.tut_teacher_id');
        $this->db->from('gig_teachers as t');
        $this->db->join('int_schools_x_sessions_log log', 't.id = log.tut_teacher_id ','left');
        $this->db->where('log.Tutoring_client_id',$Tutoring_client_id);
        $this->db->where_in('log.drhomework_ref_id',$val2);
        $this->db->where('log.tut_teacher_id >',0);
        $session_list = $this->db->get()->result_array();
       // print_r($session_list);die;

        
        if(!empty($session_list)){

            $res['status'] = 'yes';
            $res['response'] = $session_list;
            $this->response($res);
        }
        else{
            $res['status'] = 'no';
            $res['response']['message'] = "Waiting for tutor acceptance.";
            $this->response($res);
        } 

      }


          /***
    TutoringInfoUpdate -- json data
    **/

    public function tutoringInfoUpdate_post()
    {
      $res = [];
      $app_warnings = [];
      if ($this->post('Tutoring_client_id') == "")
      {
          $res['status'] = false;
          $res['response']['error']['message'] = "Please enter client_id.";
          $this->response($res);
      }

      if ($this->post('drhomework_ses_id') == "")
      {
          $res['status'] = false;
          $res['response'] = "Please enter session id.";
          $this->response($res);
      }

    //   if ($this->post('added_by') == "")
    //   {
    //       $res['status'] = false;
    //       $res['response'] = "Please enter data";
    //       $this->response($res);
    //   } 

      $Tutoring_client_id = $this->post('Tutoring_client_id');
      $drhomework_ses_id = $this->post('drhomework_ses_id');
      $added_by = $this->post('added_by');
      //$assign = implode(',', $this->post('assignment_data'));

      $assignment_data = $this->post('assignment_data') ;
      //print_r($assignment_data);die;
      if($added_by == 'parent'){

      $data=array('assignment_link_data'=>$assignment_data);

      $this->db->where('Tutoring_client_id',$Tutoring_client_id);
      $this->db->where('drhomework_ses_id',$drhomework_ses_id);
      $this->db->update('dr_tutoring_info',$data);

      $res['status'] = 'yes';
      $res['response']['message'] = "Updated successfully";
      $this->response($res);

      }else{
        $data=array('assignment_link_student'=>$assignment_data);

        $this->db->where('Tutoring_client_id',$Tutoring_client_id);
        $this->db->where('drhomework_ses_id',$drhomework_ses_id);
        $this->db->update('dr_tutoring_info',$data);

        $res['status'] = 'yes';
        $res['response']['message'] = "Updated successfully";
        $this->response($res);
      }
 
     
    }

    // public function siteMaster_post()
    // {
    //     /*MASTER_TYPE
    //        */
    //     $response = array();

    //     if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
    //         $response['status'] = false;
    //         $response['response']['error'] = 'Invalid access token.';
    //         $this->response($response);
    //     }

    //     $master_type = $this->post('MASTER_TYPE');
    //     $search_name = $this->post('SEARCH_TEXT');

    //     if (empty($master_type)) {
    //         $response['status'] = false;
    //         $response['response']['error'] = 'Please provide master type.';
    //         $this->response($response);
    //     }
    //     $designation = $this->Project->get_designation($master_type);

    //     $teamDesignationList = $this->Project->teamByDesignationList($master_type, $search_name);

    //     if (empty($teamDesignationList)) {
    //         $response['status'] = false;
    //         $response['response']['error']['message'] = 'No result found !!!';
    //         $this->response($response);
    //     }

    //     $response['status'] = true;
    //     $response['response']['success']['result'] = $teamDesignationList;
    //     $this->response($response);        
    // }

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