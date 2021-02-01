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

class ProjectController extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Project');
        $this->load->model('Login');
        $this->load->model('Rabbit');
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


     /**
     * @OA\Post(path="/ProjectController/siteMaster",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="siteMaster",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     *   @OA\Parameter(
     *     name="MASTER_TYPE",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     *   @OA\Parameter(
     *     name="SEARCH_TEXT",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
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
     * @OA\Post(path="/ProjectController/projectStep1",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectStep1",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     *   @OA\Parameter(
     *           name="site_manager_id",
     *           description="site_manager_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *           )
     *         ),
     @OA\Parameter(
     *           name="project_contract_administrator_id",
     *           description="project_contract_administrator_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *           )

     *         ),
     @OA\Parameter(
     *           name="healthandsafty_rep_id",
     *           description="healthandsafty_rep_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *           )
     *         ),
     @OA\Parameter(
     *           name="project_name",
     *           description="project_name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              type="string",
     *           )
     *         ),
     @OA\Parameter(
     *           name="client_id",
     *           description="client_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              type="string",
     *           )
     *         ),
     @OA\Parameter(
     *           name="project_address",
     *           description="project_address",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              type="string",
     *           )
     *         ),
     @OA\Parameter(
     *           name="project_city",
     *           description="project_city",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              type="string",
     *           )
     *         ),
     @OA\Parameter(
     *           name="project_lat",
     *           description="project_lat",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              type="string",
     *           )
     *         ),
     @OA\Parameter(
     *           name="project_lng",
     *           description="project_lng",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              type="string",
     *           )
     *         ),
     @OA\Parameter(
     *           name="project_da_working_hrs",
     *           description="project_da_working_hrs",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *           )
     *         ),
     @OA\Parameter(
     *           name="project_manager_id",
     *           description="project_manager_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *              type="string",
     *           )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     
     public function projectStep1_post()
     {
        $res = array();
        $res['status'] = false;
        $error = 0;

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        //$project_no = "PRO".date('Y');
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_name = $this->post('project_name');
        $site_manager_id = $this->post('site_manager_id');
        $project_contract_administrator_id = $this->post('project_contract_administrator_id');
        $healthandsafty_rep_id = $this->post('healthandsafty_rep_id');
        $client_id = $this->post('client_id');
        $project_address = $this->post('project_address');
        $project_city = $this->post('project_city');
        $project_lat = $this->post('project_lat');
        $project_lng = $this->post('project_lng');
        $project_da_working_hrs = $this->post('project_da_working_hrs');
        $project_manager_id = $this->post('project_manager_id');
        $project_id = $this->post('project_id');


        if (isset($project_name) && empty($project_name)) {
            $error = 1;
            $res['response']['error']['project_name'] = 'Please enter project name.';
        }
       /* if (isset($project_estimate) && empty($project_estimate) && intval($project_estimate) == 0 ) {
            $error = 1;
            $res['response']['error']['project_estimate'] = 'Please enter project estimate.';
        }
        if (isset($project_no) && empty($project_no)) {
            $error = 1;
            $res['response']['error']['project_no'] = 'Please enter project no.';
        }
        if (isset($site_contractor_id) && (empty($site_contractor_id) || $site_contractor_id == 0)) {
            $error = 1;
            $res['response']['error']['site_contractor_id'] = 'Please enter site contractor.';
        }
        if (isset($client_id) && (empty($client_id) || $client_id == 0)) {
            $error = 1;
            $res['response']['error']['project_name'] = 'Please select client name.';
        }
        if (isset($project_address) && empty($project_address)) {
            $error = 1;
            $res['response']['error']['project_address'] = 'Please enter project address.';
        }
        if (isset($project_lat) && empty($project_lat)) {
            $error = 1;
            $res['response']['error']['project_address'] = 'Please provide project lat.';
        }
        if (isset($project_lng) && empty($project_lng)) {
            $error = 1;
            $res['response']['error']['project_address'] = 'Please provide project lng.';
        }
        if (isset($project_status) && (empty($project_status) || $project_status == 0)) {
            $error = 1;
            $res['response']['error']['project_name'] = 'Please select project status.';
        }
        if (isset($project_da_working_hrs) && empty($project_da_working_hrs)&& intval($project_da_working_hrs) == 0) {
            $error = 1;
            $res['response']['error']['project_da_working_hrs'] = 'Please enter project DA working hrs.';
        }
        if (isset($project_director_id) && (empty($project_director_id) || $project_director_id == 0)) {
            $error = 1;
            $res['response']['error']['project_director_id'] = 'Please select project director.';
        }
        if (isset($project_manager_id) && (empty($project_manager_id) || $project_manager_id == 0)) {
            $error = 1;
            $res['response']['error']['project_manager_id'] = 'Please select project manager.';
        }*/

        if ($error == 1) {

            $res['status'] = false;

        } else {
            $now = new DateTime();
            $now = $now->format('Y-m-d H:i:s');
            $pro_step_one_data = array(
                'user_id' => $user_id,
                'project_name' => $project_name,
                'site_contractor_id' => $site_contractor_id,
                'client_id' => $client_id,
                'project_address' => $project_address,
                'project_city' => $project_city,
                'project_lat' => $project_lat,
                'project_lng' => $project_lng,
                'project_da_working_hrs' => $project_da_working_hrs,
                'project_director_id' => $project_director_id,
                'project_manager_id' => $project_manager_id,
                'created' => $now,
                'project_site_manager_id' => $site_manager_id,
                'project_contract_administrator_id' => $project_contract_administrator_id,
                'project_healthandsafty_rep_id' => $healthandsafty_rep_id
            );

            try
            {
               if(!(isset($project_id)&& empty($project_id)))
               {
                $checkProject = $this->Project->getProjectData($project_id); 
                if ($checkProject) 
                {
                    $lastId = $this->Project->updateProject($project_id,$pro_step_one_data); 
                    if ($lastId) {
                        $res['status'] = true;
                        $res['response']['success']['message'] = "Project updated successfully";
                        $res['response']['success']['project_id'] = $project_id;
                    } else {
                        $res['status'] = false;
                        $res['response']['error']['message'] = "OOPS !!! Unable to update project, please try again";
                    }
                }
                else {
                    $res['status'] = false;
                    $res['response']['error']['message'] = "Project does not exist.";
                }
            }
            else
            {
                $lastId = $this->Project->addProject($pro_step_one_data);
                if ($lastId) {
                    $res['status'] = true;
                    $res['response']['success']['message'] = "Project created successfully";
                    $res['response']['success']['project_id'] = $lastId;
                } else {
                    $res['status'] = false;
                    $res['response']['error']['message'] = "OOPS !!! Unable to add new project, please try again";
                }

            }
        } catch (Exception $e) {
            $res['status'] = false;
            $res['response']['error']['message'] = $e->getMessage();
        }

    }
    $this->response($res);
}

     /**
     * @OA\Post(path="/ProjectController/projectStep2",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectStep2",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
 *  @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_start_date",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_completion_date",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_total_months",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_foreman_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_manager_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_site_manager_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_first_aider_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_number",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_estimate",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_status",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     ) 
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  

     public function projectStep2_post()
     {
        $res = array();
        $res['status'] = false;
        $error = 0;

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $project_id = $this->post('project_id');
        $access_token = $this->post('access_token');
        $project_start_date = $this->post('project_start_date');
        $project_completion_date = $this->post('project_completion_date');
        $project_total_months = intval($this->post('project_total_months'));
        $project_number = $this->post('project_number');
        $project_estimate = str_replace("$", "", $this->post('project_estimate')) ;
        $project_foreman_id = $this->post('project_foreman_id');
        $project_status = $this->post('project_status');
        $subbie_project_manager_id = $this->post('project_manager_id');
        $subbie_site_manager_id = $this->post('project_site_manager_id');
        $project_first_aider_id = $this->post('project_first_aider_id');
        
        
        
        if (isset($project_total_months) && empty($project_total_months)) {
            $error = 1;
            $res['response']['error']['project_total_months'] = 'Please select project total months.';
        }
        if (isset($project_number) && empty($project_number)) {
            $error = 1;
            $res['response']['error']['project_number'] = 'Please select project number.';
        }
        if (isset($project_estimate) && empty($project_estimate)) {
            $error = 1;
            $res['response']['error']['project_estimate'] = 'Please select project estimate.';
        }
        if (isset($project_status) && empty($project_status)) {
            $error = 1;
            $res['response']['error']['project_status'] = 'Please select project status.';
        }
        if (isset($project_start_date) && empty($project_start_date)) {
            $error = 1;
            $res['response']['error']['project_start_date'] = 'Please select start date.';
        }
        if (isset($project_completion_date) && empty($project_completion_date)) {
            $error = 1;
            $res['response']['error']['project_completion_date'] = 'Please select completion date.';
        }
        if (isset($project_total_months) && empty($project_total_months)) {
            $error = 1;
            $res['response']['error']['project_total_months'] = 'Please select total months.';
        }
        if (isset($project_site_manager_id) && (empty($project_site_manager_id) || $project_site_manager_id == 0)) {
            $error = 1;
            $res['response']['error']['project_site_manager_id'] = 'Please select site manager name.';
        }
        if (isset($project_foreman_id) && (empty($project_foreman_id) || $project_foreman_id == 0)) {
            $error = 1;
            $res['response']['error']['project_foreman_id'] = 'Please select foreman.';
        }
/*
        if (isset($project_site_eng_id) && (empty($project_site_eng_id) || $project_site_eng_id == 0)) {
            $error = 1;
            $res['response']['error']['project_site_eng_id'] = 'Please select site engineer.';
        }
        if (isset($project_rep_id) && (empty($project_rep_id) || $project_rep_id == 0)) {
            $error = 1;
            $res['response']['error']['project_rep_id'] = 'Please select health and safety representative.';
        }
        if (isset($project_contract_administrator_id) && (empty($project_contract_administrator_id) || $project_contract_administrator_id == 0)) {
            $error = 1;
            $res['response']['error']['project_contract_administrator_id'] = 'Please select contract administrator.';
        }
*/

        if ($error == 1) {

            $res['status'] = false;

        } else {
            $data = array(
                'user_id' => $user_id,
                'project_start_date' => $project_start_date,
                'project_completion_date' => $project_completion_date,
                'project_total_months' => $project_total_months,
                'project_foreman_id' => $project_foreman_id,
                'project_status' => $project_status,
                'status' => $project_status,
                'project_manager_proxy_id' => !empty($subbie_project_manager_id)? $subbie_project_manager_id:'',
                'project_site_manager_proxy_id' => !empty($subbie_site_manager_id)? $subbie_site_manager_id:'',
                'project_first_aider_id' => !empty($project_first_aider_id)? $project_first_aider_id:'',
                'project_number' => $project_number,
                'project_estimate' => $project_estimate
            );
            try
            {
                $result = $this->Project->updateProject($project_id, $data);

                if ($result) {
                    if($subbie_project_manager_id)
                    {
                        $from_user =  $this->db->where('id', $user_id)->from('checkmate_user')->get()->result_array();
                        $from_user_name = $from_user[0]['name'];
                        $to_user =  $this->db->where('id', $subbie_project_manager_id)->from('checkmate_user')->get()->result_array();
                        $to_user_name = $from_user[0]['name'];
                        $project_info =  $this->db->where('id', $project_id)->from('checkmate_project')->get()->result_array();
                        $project_name = $project_info[0]['name'];

                        $device_token =  $this->db->where('user_id', $subbie_project_manager_id)->from('checkmate_user_device')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'You have added as project manager in '.$project_name;
                        $message = $from_user_name.' added you have added in '.$project_name.' team.';
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
                    }
                    if($subbie_site_manager_id)
                    {
                        $from_user =  $this->db->where('id', $user_id)->from('checkmate_user')->get()->result_array();
                        $from_user_name = $from_user[0]['name'];
                        $to_user =  $this->db->where('id', $subbie_site_manager_id)->from('checkmate_user')->get()->result_array();
                        $to_user_name = $from_user[0]['name'];
                        $project_info =  $this->db->where('id', $project_id)->from('checkmate_project')->get()->result_array();
                        $project_name = $project_info[0]['name'];

                        $device_token =  $this->db->where('user_id', $subbie_site_manager_id)->from('checkmate_user_device')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'You have added as site manager in '.$project_name;
                        $message = $from_user_name.' added you have added in '.$project_name.' team.';
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
                    }
                     if($project_foreman_id)
                    {
                        $from_user =  $this->db->where('id', $user_id)->from('checkmate_user')->get()->result_array();
                        $from_user_name = $from_user[0]['name'];
                        $to_user =  $this->db->where('id', $project_foreman_id)->from('checkmate_user')->get()->result_array();
                        $to_user_name = $from_user[0]['name'];
                        $project_info =  $this->db->where('id', $project_id)->from('checkmate_project')->get()->result_array();
                        $project_name = $project_info[0]['name'];

                        $device_token =  $this->db->where('user_id', $subbie_site_manager_id)->from('checkmate_user_device')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'You have added as forman in '.$project_name;
                        $message = $from_user_name.' added you have added in '.$project_name.' team.';
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
                    }
                    $res['status'] = true;
                    $res['response']['success']['message'] = "Details updated successfully";
                    $res['response']['success']['project_id'] = $project_id;
                } else {
                    $res['status'] = false;
                    $res['response']['error']['message'] = "OOPS !!! Unable to update project, please try again";
                }

            } catch (Exception $e) {
                $res['status'] = false;
                $res['response']['error']['message'] = $e->getMessage();
            }

        }
        $this->response($res);
    }
    
    /**
     * @OA\Post(path="/ProjectController/projectStep3",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectStep3",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_brief_scope",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_scope",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_site_map",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_picture",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function projectStep3_post()
     {
        $res = array();
        $res['status'] = false;
        $error = 0;

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_id = $this->post('project_id');
        $project_brief_scope = $this->post('project_brief_scope');
        $project_scope = $this->post('project_scope');
        $project_site_map = $this->post('project_site_map');
        $project_picture = $this->post('project_picture');

        if (isset($project_brief_scope) && empty($project_brief_scope)) {
            $error = 1;
            $res['response']['error']['project_brief_scope'] = 'Please enter project brief scope.';
        }
        if (isset($project_scope) && empty($project_scope)) {
            $error = 1;
            $res['response']['error']['project_scope'] = 'Please enter project scope.';
        }

        if ($error == 1) {
            $res['status'] = false;
        } else {


            if(empty($project_site_map) && empty($project_picture) )
            {
                $data = array(
                    'project_brief_scope' => $project_brief_scope,
                    'project_scope' => $project_scope
                );
            }
            else if(empty($project_picture))
            {
             $data = array(
                'project_brief_scope' => $project_brief_scope,
                'project_scope' => $project_scope,
                'project_site_map' => base_url('api/' . $this->upload('site_map',$project_site_map))
            );
         }
         else if(empty($project_site_map))
         {
             $data = array(
                'project_brief_scope' => $project_brief_scope,
                'project_scope' => $project_scope,
                'project_picture' => base_url('api/' . $this->upload('site_picture',$project_picture)) 
            );
         }
         else
         {
             $data = array(
                'project_brief_scope' => $project_brief_scope,
                'project_scope' => $project_scope,
                'project_site_map' => base_url('api/' . $this->upload('site_map',$project_site_map)) ,
                'project_picture' => base_url('api/' . $this->upload('site_picture',$project_picture)) 
            );
         }
         try
         {
            $result = $this->Project->updateProject($project_id, $data);

            if ($result) {
                $res['status'] = true;
                $res['response']['success']['message'] = "Project details updated successfully";
                $res['response']['success']['project_id'] = $project_id;
            } else {
                $res['status'] = false;
                $res['response']['error']['message'] = "OOPS !!! Unable to update project, please try again";
            }
        } catch (Exception $e) {
            $res['status'] = false;
            $res['response']['error']['message'] = $e->getMessage();
        }
    }

    $this->response($res);

}


function mime2ext($mime){
    $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
    "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
    "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
    "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
    "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
    "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
    "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
    "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
    "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
    "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
    "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
    "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
    "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
    "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
    "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
    "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
    "pdf":["application\/pdf","application\/octet-stream"],
    "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
    "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
    "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
    "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
    "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
    "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
    "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
    "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
    "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
    "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
    "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
    "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
    "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
    "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
    "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
    "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
    "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
    "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
    "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
    "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
    "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
    "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
    "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
    "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
    "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
    "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
    "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
    "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
    $all_mimes = json_decode($all_mimes,true);
    foreach ($all_mimes as $key => $value) {
        if(array_search($mime,$value) !== false) return $key;
    }
    return false;
}
public function upload($type,$base64)
{
    ini_set('memory_limit', '200M' );
    ini_set('upload_max_filesize', '200M');  
    ini_set('post_max_size', '200M');  
    ini_set('max_input_time', 3600);  
    ini_set('max_execution_time', 3600);
    $return = '';
    $string_exploded = explode(';base64,', "data:image/png;base64,".$base64);
    try
    {
        $data = array();
        if(!empty($string_exploded) && count($string_exploded) >= 1)
        {
            $extension = $this->mime2ext( str_replace("data:", "", $string_exploded[0]));
                $decoded_file = base64_decode($string_exploded[1]); // decode the file
                $file = 'assets/uploads/'.$type.'/' . uniqid() .'.'. (!empty($extension)?$extension:"png");
                file_put_contents($file, $decoded_file); // save
                if(file_exists($file))
                {
                   $return = $file;   
               }
           }    

       }
       catch (Exception $e) 
       {
           $return = $e->getMessage();
       }

       return $return;
   }
/*
function upload_file($encoded_string){
 
    $target_dir = 'assets/uploads/site_map/'; // add the specific path to save the file
    $filedata = explode(',', $encoded_string); 
    $decoded_file = base64_decode($filedata[1]);
   
    $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
    $extension = mime2ext($mime_type); // extract extension from mime type
    //echo "extension->".$extension;
    $file = uniqid() .'.'. $extension; // rename file as a unique name
    $file_dir = $target_dir . uniqid() .'.'. $extension;
    try {
        file_put_contents($file_dir, $decoded_file); // save
        //database_saving($file);
 //       header('Content-Type: application/json');
        //echo json_encode("File Uploaded Successfully");
        $result =1;
    } catch (Exception $e) {
   //     header('Content-Type: application/json');
       // echo json_encode($e->getMessage());
        $result =0;
    }
    return $result;
}
*/
/**
     * @OA\Post(path="/ProjectController/addSiteMap",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addSiteMap",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_site_map",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function addSiteMap_post()
     {
        ini_set( 'memory_limit', '200M' );
        ini_set('upload_max_filesize', '200M');  
        ini_set('post_max_size', '200M');  
        ini_set('max_input_time', 3600);  
        ini_set('max_execution_time', 3600);
        
        $res = array();
        $res['status'] = false;
        $error = 0;
        // $encoded_string = "data:image/jpeg;base64,";
       //  $encoded_string = !empty($_POST['project_site_map']) ? $_POST['project_site_map'] : $encoded_string;

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_id = $this->post('project_id');
        $project_site_map = $this->post('project_site_map');

        if (isset($project_site_map) && empty($project_site_map)) {
            $error = 1;
            $res['response']['error']['message'] = 'Please add project site map.';
            $this->response($res);
        }
       // $string_exploded = explode(';base64,', $project_site_map);

        try
        {
            /* Before adding new entry to project table
            * get previous site map and store in
            * site map history table */

            $site_map_data = $this->Project->getById($project_id, 'checkmate_project', 'project_site_map');
            if (!empty($site_map_data)) {
                $data = array(
                    'user_id' => $user_id,
                    'project_id' => $project_id,
                    'project_site_map' => $site_map_data,
                    'created' => getCurrDateTime(),
                );

                $this->Project->createSiteMapHistory($data);
            }
            $data = array();

            $data = array('project_site_map' => base_url('api/'.$this->upload('site_map',$project_site_map)));                 
            $result = $this->Project->updateProject($project_id, $data);
                    // print_r($result);exit;
            if ($result) {
                $res['status'] = true;
                $res['response']['success']['message'] = "Sitemap added successfully";
                $res['response']['success']['project_id'] = $project_id;
            } else {
                $res['status'] = false;
                $res['response']['error']['message'] = "OOPS !!! Unable to update project, please try again";
            }
/*
    if(!empty($string_exploded) && count($string_exploded) >= 1)
    {
        $extension = $this->mime2ext( str_replace("data:", "", $string_exploded[0]));
        $decoded_file = base64_decode($string_exploded[1]); // decode the file

        $file = 'assets/uploads/site_map/' . uniqid() .'.'. (empty($extension)?$extension:".png");
        
        file_put_contents($file, $decoded_file); // save
         if(file_exists($file)){
                
            }
    }   
*/
    


} catch (Exception $e) {
    $res['status'] = false;
    $res['response']['error']['message'] = $e->getMessage();
}

$this->response($res);
}
/**
     * @OA\Post(path="/ProjectController/addProjectPicture",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addProjectPicture",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *           description="your project_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_image",
     *           description="Base_64 of image",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function addProjectPicture_post()
     {
        $res = array();
        $res['status'] = false;
        $error = 0;

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_id = $this->post('project_id');
        $project_image = $this->post('project_image');

        if (isset($project_image) && empty($project_image)) {
            $error = 1;
            $res['response']['error']['project_image'] = 'Please add project image.';
            $this->response($res);
        }

        try
        {
            $data = array();
            $data = array(
                'user_id' => $user_id,
                'project_id' => $project_id,
                'project_picture' => base_url('api/'.$this->upload('site_picture',$project_image)),
                'created' => getCurrDateTime(),
            );

            $result = $this->Project->addProjectPictures($data);

            if ($result) {
                $res['status'] = true;
                $res['response']['success']['message'] = "Project picture added successfully";
                $res['response']['success']['project_id'] = $project_id;
            } else {
                $res['status'] = false;
                $res['response']['error']['message'] = "OOPS !!! Unable to update project, please try again";
            }

        } catch (Exception $e) {
            $res['status'] = false;
            $res['response']['error']['message'] = $e->getMessage();
        }

        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/markProjectActive",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="markProjectActive",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */ 
     public function markProjectActive_post()
     {
        $res = array();
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_id = $this->post('project_id');

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        if (isset($project_id) && empty($project_id)) {
            $error = 1;
            $res['response']['error']['project_id'] = 'Please provide project id.';
            $this->response($res);
        }
        $data = array('status' => 2,'project_status' => 2);
        $result = $this->Project->updateProject($project_id, $data);
        try
        {

            $data = array('status' => 1,'project_status' => 1);
            $result = $this->Project->updateProject($project_id, $data);

            if ($result) {
                /*
                 * Get project and project images based on project id and return back to user
                 */
                  $project_data = $this->db->query("SELECT project_name FROM checkmate_project  WHERE id = $project_id")->row();
                  $getUserData = $this->db->query("SELECT user_id, site_contractor_id, project_director_id, project_manager_id, project_site_manager_id, project_foreman_id, project_site_eng_id, project_contract_administrator_id FROM checkmate_project  WHERE id = $project_id")->row();
                   foreach ($getUserData as $key => $value) {
                        $device_token =  $this->db->where('user_id',$value)->from('checkmate_user_device')->get()->result_array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        
                    }
                        $title = 'subbie has reach to the location';
                        $message = 'project '.$project_data->project_name.'subbie reached at project location';
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
                $projectData = $this->Project->getById($project_id, 'checkmate_project');
                $projectImages = $this->Project->getByCriteria(array('project_id' => $project_id), 'checkmate_project_pictures');
                if (!empty($projectImages)) {
                    foreach ($projectImages as $k => $v) {
                        $projectImages[$k]->project_picture =strpos($v->project_picture,"http")>0?$v->project_picture:   base_url('api/' . $v->project_picture);
                    }
                }
								
                $projectData->projectImages = $projectImages;

                $res['status'] = true;
                $res['response']['success']['message'] = "Your project is now added successfully";
                $res['response']['success']['project'] = $projectData;
                
            } else {
                $res['status'] = false;
                $res['response']['error']['message'] = "OOPS !!! Unable to update project, please try again";
            }
        } catch (Exception $e) {
            $res['status'] = false;
            $res['response']['error']['message'] = $e->getMessage();
        }

        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/projectList",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectList",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="RESULT_TYPE",
     *           description="your RESULT_TYPE",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function projectList_post()
     {
        /*
         * Possible options for RESULT_TYPE are
         * RESULT_ALL_PROJECT
         * RESULT_LATEST_PROJECT
         */


        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $result_type = $this->post('RESULT_TYPE');
        
        if ($result_type == "RESULT_LATEST_PROJECT") {
            $projects = $this->Project->getProjectByUser($user_id, "RESULT_LATEST_PROJECT", array('id, user_id, project_estimate, project_no, project_name, project_city, project_address, project_lat, project_lng, project_site_map, project_status,project_manager_id,project_brief_scope'));
        }else{
            $projects = $this->Project->getProjectByUser($user_id, "RESULT_ALL_PROJECT", array('id, user_id, project_estimate, project_no, project_name, project_city, project_address, project_lat, project_lng, project_site_map, project_status,project_manager_id,project_brief_scope'));
        }

        if (empty($projects)) {
            $res['status'] = false;
            $res['response']['error'] = "No result found";
            $this->response($res);
        }

        foreach ($projects as $k => $p) {
            /* Get project image based on project id */
            $projectImages = $this->Project->getByCriteria(array('project_id' => $p->id), 'checkmate_project_pictures');

            if (!empty($projectImages)) {
                foreach ($projectImages as $i => $j) {
                    $projectImages[$i]->project_picture = strpos($j->project_picture,"http")>0?$j->project_picture:   base_url('api/' . $j->project_picture); 
                }
            }

            $projects[$k]->project_site_map = $p->project_site_map;
            $projects[$k]->project_images = $projectImages;
            $manager = $this->Project->getManager($p->id);
            $projects[$k]->manager =  count($manager)>0?$manager:[];
            /* Get project equipment based on project id*/
            $projectEquipements = $this->Project->getProjectEquipments($p->id);
            $project_Equipements = array();
            if(!empty($projectEquipements))
            {
                foreach ($projectEquipements as $k => $m)
                {
                    $project_Equipements[$k]->id =  $m->id;
                    $project_Equipements[$k]->equipment_name = $m->equipment_name;
                    $project_Equipements[$k]->equipment_image = strpos($m->equipment_image,"http")>0?$m->equipment_image:   base_url('api/' . $m->equipment_image);
                }
            }       
            $projects[$k]->project_equipments = $project_Equipements;

            /* Get project employee based on project id*/
            $projectEmployees = $this->Project->getProjectEmployee($p->id);
            if(!empty($projectEmployees))
            {
                foreach ($projectEmployees as $k => $m)
                {
                    $projectMilestones[$k]->id =  $m->id;
                    $projectMilestones[$k]->name = $m->name;
                    $projectMilestones[$k]->image = strpos($m->image,"http")>0?$m->image:   base_url('api/' . $m->image);  
                }
            }
            $projects[$k]->project_employees = $projectEmployees;
            /* Get project milestone */
            $projectMilestones = $this->Project->getByCriteria(array('project_id' => $p->id), 'checkmate_project_milestone');
            if(!empty($projectMilestones))
            {
                foreach ($projectMilestones as $k => $m)
                {
                    $projectMilestones[$k]->project_milestone_name =  $m->project_milestone_name;
                    $projectMilestones[$k]->budget = $m->budget;
                    $projectMilestones[$k]->id = $m->id;
                }
            }
            $projects[$k]->project_milestones = $projectMilestones;
        }
        $resuls =array();
        foreach ($projects as $k )
        {
            $resuls[]=  $k;
        }
        $res['status'] = true;
        $res['response']['success']['projects'] = $resuls;
        $this->response($res);
    }
    /* Get project details */
    /**
     * @OA\Post(path="/ProjectController/projectDetail",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectDetail",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function projectDetail_post()
     {        
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $project_id = $this->post('project_id');

        $projects = $this->Project->getProjectByUserAndId($user_id, $project_id);
        
        if (empty($projects)) {
            $res['status'] = false;
            $res['response']['error'] = "No result found";
            $this->response($res);
        }

        foreach ($projects as $k => $p) {
            /* Get project image based on project id */
            $projectImages = $this->Project->getByCriteria(array('project_id' => $p->id), 'checkmate_project_pictures');

            if (!empty($projectImages)) {
                foreach ($projectImages as $i => $j) {
                    $projectImages[$i]->project_picture = strpos($j->project_picture,"http")>0?$j->project_picture:   base_url('api/' . $j->project_picture);
                }
            }

            $projects[$k]->project_site_map = $p->project_site_map;
            $projects[$k]->project_images = $projectImages;

            /* Get project equipment based on project id*/
            $projectEquipements = $this->Project->getProjectEquipments($p->id,0,10);    
            //E.id, E.equipment_name, E.equipment_image'
            $project_Equipements = array();
            if(!empty($projectEquipements))
            {
                foreach ($projectEquipements as $k => $m)
                {
                    $project_Equipements[$k]->id =  $m->id;
                    $project_Equipements[$k]->equipment_name = $m->equipment_name;
                    $project_Equipements[$k]->equipment_image = strpos($m->equipment_image,"http")>0?$m->equipment_image:   base_url('api/' . $m->equipment_image);

                }
            }       
            $projects[$k]->project_equipments = $project_Equipements;

            /* Get project employee based on project id*/
            $projectEmployees = $this->Project->getProjectEmployee($p->id,0,10);  
            $project_Employees = array();
            if(!empty($projectEmployees))
            {
                foreach ($projectEmployees as $k => $m)
                {
                    $project_Employees[$k]->id =  $m->id;
                    $project_Employees[$k]->name = $m->name;
                    $project_Employees[$k]->image = strpos($m->image,"http")>0?$m->image:   base_url('api/' . $m->image);
                }
            }       
            $projects[$k]->project_employees = $project_Employees;
        }

        $res['status'] = true;
        $res['response']['success']['projects'] = $projects;
        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/projectDetailFull",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectDetailFull",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function projectDetailFull_post()
     {        
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $project_id = $this->post('project_id');
        $milestone_req  = $this->post('milestone_req');
        
        if ($this->post('milestone_req') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please provide milestone_req 0 or 1";
            $this->response($res);
        }

        $projects = $this->Project->getProjectByUserAndId($user_id, $project_id);
        
        if (empty($projects)) {
            $res['status'] = false;
            $res['response']['error'] = "No result found";
            $this->response($res);
        }

        $projectWorkingHours = $projects[0]->project_da_working_hrs;
        $projectWorkingHours = (!empty($projectWorkingHours)) ? explode(",", $projectWorkingHours) : array();
        $projectWorkingHoursCount = (!empty($projectWorkingHours)) ? count($projectWorkingHours) : 0;

        $projectWorkingHoursArray = array();
        $projectWorkingHoursArrayKey = 0;

        foreach ($projectWorkingHours as $projectWorkingHoursKey => $projectWorkingHoursValue)
        {   
            $workingHoursInfo = $this->Project->getworkinghrsDetails($projectWorkingHoursValue);
            if(!empty($workingHoursInfo))
            {
                $projectWorkingHoursArray[$projectWorkingHoursArrayKey] = $workingHoursInfo;
                $projectWorkingHoursArrayKey = $projectWorkingHoursArrayKey + 1;
            }
            
        }

        foreach ($projects as $k => $p) {
            /* Get project image based on project id */
            $projectImages = $this->Project->getByCriteria(array('project_id' => $p->id), 'checkmate_project_pictures');

            if (!empty($projectImages)) {
                foreach ($projectImages as $i => $j) {
                    $projectImages[$i]->project_picture = strpos($j->project_picture,"http")>0?$j->project_picture:   base_url('api/' . $j->project_picture);
                }
            }


            $projects[$k]->project_site_map = $p->project_site_map;
            $projects[$k]->project_images = $projectImages;

            /* Get project equipment based on project id*/
            $projectEquipements = $this->Project->getProjectEquipments($p->id,0,10);            
            $project_Equipements = array();
            if(!empty($projectEquipements))
            {
                foreach ($projectEquipements as $k => $m)
                {
                    $project_Equipements[$k]->id =  $m->id;
                    $project_Equipements[$k]->equipment_name = $m->equipment_name;
                    $project_Equipements[$k]->equipment_image = strpos($m->equipment_image,"http")>0?$m->equipment_image:   base_url('api/' . $m->equipment_image);

                }
            }       
            $projects[$k]->project_equipments = $project_Equipements;

            /* Get project employee based on project id*/
            $projectEmployees = $this->Project->getProjectEmployee($p->id,0,10);    
            $project_Employees = array();
            if(!empty($projectEmployees))
            {
                foreach ($projectEmployees as $k => $m)
                {
                    $project_Employees[$k]->id =  $m->id;
                    $project_Employees[$k]->name = $m->name;
                    $project_Employees[$k]->image = strpos($m->image,"http")>0?$m->image:   base_url('api/' . $m->image);
                }
            }     
            
            
            $project_Employees_new =  $this->Project->getprojectemployee_new($p->id);
            $projects[$k]->project_employees  = count($project_Employees_new)>0?$project_Employees_new:[];
            
            $siteContracter =  $this->Project->getSiteContracter($p->id);
            $projects[$k]->siteContracter = count($siteContracter)>0?$siteContracter:[];
            $client = $this->Project->getClient($p->id);
            $projects[$k]->client = count($client)>0?$client:[];
            $director = $this->Project->getDirector($p->id);
            $projects[$k]->director = count($director)>0?$director:[];
            $manager = $this->Project->getManager($p->id);
            $projects[$k]->manager =  count($manager)>0?$manager:[];
            $siteManager = $this->Project->getSiteManager($p->id);
            $projects[$k]->siteManager =  count($siteManager)>0?$siteManager:[];
            $foreMan = $this->Project->getForeMan($p->id);
            $projects[$k]->foreMan = count($foreMan)>0?$foreMan:[];
            $siteEngineer = $this->Project->getSiteEngineer($p->id);
            $projects[$k]->siteEngineer = count($siteEngineer)>0?$siteEngineer:[];
            $contractAdministrator = $this->Project->getContractAdministrator($p->id);
            $projects[$k]->contractAdministrator = count($contractAdministrator)>0?$contractAdministrator:[];

            $siteManagersubby = $this->Project->getproject_subbie_manager($p->id);
            $projects[$k]->ManagerSubby = count($siteManagersubby)>0?$siteManagersubby:[];
            $project_subbie_site_manager = $this->Project->getproject_subbie_site_manager($p->id);
            $projects[$k]->siteManagerSubby = count($project_subbie_site_manager)>0?$project_subbie_site_manager:[];
            $project_first_aider = $this->Project->getproject_first_aider($p->id);
            $projects[$k]->FirstAider = count($project_first_aider)>0?$project_first_aider:[];
            $project_thealthandsafty_rep = $this->Project->gethealthandsafty_rep($p->id);
            $projects[$k]->HealthandSaftyRep = count($project_thealthandsafty_rep)>0?$project_thealthandsafty_rep:[];
            // $project_getworkinghrs = $this->Project->getworkinghrs($p->id);
            // $projects[$k]->Project_working_hrs = count($project_getworkinghrs)>0?$project_getworkinghrs:[];

            $projects[$k]->Project_working_hrs = $projectWorkingHoursArray;

            
            if($milestone_req == 1) 
            {
                $projectmilestone = $this->Project->getMilestonebyproject($p->id);
                $projects[$k]->milestone = count($projectmilestone)>0?$projectmilestone:[];
            }
            else
            {
               $projects[$k]->milestone = [];
           }

       }

       $res['status'] = true;
       $res['response']['success']['projects'] = $projects;
       $this->response($res);
   }
   /* Get equipment master*/
    /**
     * @OA\Post(path="/ProjectController/equipmentList",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="equipmentList",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
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
    public function equipmentList_post()
    {
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $res = array();
        $equipmentList = $this->Project->equipmentList();

        if (empty($equipmentList)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "No result found !!!";
            $this->response($res);
        }

        foreach ($equipmentList as $k=>$e)
        {
            $equipmentList[$k]['equipment_image'] = $e['equipment_image'] == "" ? "" : base_url('api/'.$e['equipment_image']);
        }

        $res['status'] = true;
        $res['response']['success']['equipments'] = $equipmentList;
        $this->response($res);
    }
    
    public function updateSubbyLocation_post()
    {
       //print_r($_POST); exit;
        // if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Invalid access token.';
        //     $this->response($res);
        // }
        
        $project_id = $this->post('project_id');
        $User_id = explode(',', $this->post('user_id'));
        $positionX = explode(',', $this->post('positionX'));
        $positionY = explode(',', $this->post('positionY'));
        //$User_y = $this->post('user_y');
        $i = 0;
        foreach($positionX as $row){
            $data['user_x']     = $positionX[$i];
            $data['user_y']     = $positionY[$i];
            $data['project_id'] = $project_id;
            $data['user_id']    = $User_id[$i];
            $result = $this->db->insert("checkmate_project_location",$data);
           
        }
        //$result = $this->Project->updateSubbyLocation($project_id,$User_id,$User_X,$User_y);
                $allproject_locations = $this->Project->get_rows_c1('checkmate_project_location', 'project_id', $project_id);
                
        if (!$result) {
            $res['status'] = false;
            $res['response']['error']['message'] = "No result found !!!";
            $this->response($res);
        }
        else
        {
            $project_data = $this->db->query("SELECT user_id, project_name FROM checkmate_project  WHERE id = $project_id")->row();
            $device_token =  $this->db->where('user_id', $project_data->user_id)->from('checkmate_user_device')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'subbie has reach to the location';
                        $message = 'project '.$project_data->project_name.'subbie reached at project location';
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
            $res['status'] = true;
            $res['response']['locations'] = $allproject_locations;
            $res['response']['success']['message'] = "Location Updated Successfully!!!";
            $this->response($res);
        }
    }
    
    
    public function getSubbyLocation_post()
    {
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $res = array();
        $project_id = $this->post('project_id');
        $User_id = $this->post('user_id');
        $all_location = $this->post('all_location');
         
       $latest_loc=1;
        $SubbyLocation = $this->Project->getSubbyLocation($project_id,$User_id,$all_location,$latest_loc);

        if (empty($SubbyLocation)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "No result found !!!";
            $this->response($res);
        }

        $res['status'] = true;
        $res['response']['success']['location'] = $SubbyLocation;
        $this->response($res);
    }
    
    public function getSubbyTimeSheet_post()
    {
       
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $res = array();
        $project_id = $this->post('project_id');
        $User_id = $this->post('user_id');
        $page_num = $this->post('page_num');
        $is_admin = $this->post('is_admin');
         
          if($is_admin==1){
            $getSubbyTimeSheet = $this->Project->getSubbyTimeSheet($project_id,0,$page_num);
        }else{
             $getSubbyTimeSheet = $this->Project->getSubbyTimeSheet($project_id,$User_id,$page_num);

        }
       
        

        if (empty($getSubbyTimeSheet)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "No result found !!!";
            $this->response($res);
        }

        
        $res['status'] = true;
        $res['response']['success']['location'] = $getSubbyTimeSheet;
        $this->response($res);
    }
    /***
    Project recent activities
    // access_token user_id,  project_id
    @ checkmate_subby_timesheet
    @ checkmate_sitemap_history

    **/
    public function Project_recent_activities_post(){
          //echo 'Test';  die; 

         if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }
        // User id :already login. 
        $user_id=$this->post('user_id');
         $project_id=$this->post('project_id');

         if(empty($user_id)||$user_id<=0){
             $res['status'] = false;
            $res['response']['error'] = 'Enter user ID.';
            $this->response($res);

         }
          if(empty($project_id)||$project_id<=0){
             $res['status'] = false;
            $res['response']['error'] = 'Enter project_id ID.';
            $this->response($res);

         }

         /////////Data//////
          
             //$this->db->select('t.*,u.id as uid,u.guid_id,u.name');
                  $this->db->select('t.id,t.time_in,t.time_out,u.id as uid,u.guid_id,u.name');
                    $this->db->from('checkmate_subby_timesheet t');
                    $this->db->join('checkmate_user u', 't.user_id =u.id ','left');
                   $this->db->where('t.project_id', $project_id);
                    $get_data= $this->db->get()->result();
                    //print_r($get_data); die;

              ////////////
                    if(!empty($get_data)){
                         $res['status'] = true;
                         $res['project_id'] =$project_id;
                  $res['response']=$get_data;
                  $this->response($res);
            

                    }else{
                        $res['status'] = false;
            $res['response']['error'] = 'No record found';
            $this->response($res);

                    }
                   





    }
    
    public function updateSubbyTimeSheet_post()
    {
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $res = array();
        $project_id = $this->post('project_id');
        $User_id = $this->post('user_id');
        $inout = $this->post('inout');
        $entry_id=$this->post('user_entry_id'); 

        /// For Out User :: entry_id Required 
        // if($inout>=1&&$entry_id<=0){
        //      $res['status'] =false;
        //     $res['response']['success']['message'] = "Plase provide entry_id, to a exit user!!!";
        //     $this->response($res);
        // }
           //   echo '===TEddf';
        //print_r($entry_id); die;
        //   $locationcheck = $this->Project->checkSubbyTime($project_id,$User_id,$entry_id);
         
       
       $locationcheck = $this->Project->checkSubbyTime($project_id,$User_id);
       //print_r($locationcheck);die;

       if(empty($locationcheck) && $inout == 0 )
       {
            $result = $this->Project->updateSubbyTime($project_id,$User_id,$inout,$entry_id);
            $project_data = $this->db->query("SELECT user_id, project_name FROM checkmate_project  WHERE id = $project_id")->row();
						$subbie_info = $this->db->where('id', $project_data->user_id)->from('checkmate_user')->get()->result_array();
             $device_token =  $this->db->where('user_id', $project_data->user_id)->from('checkmate_user_device')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'Subbie location update';
                        $message = $subbie_info[0]['name'].' is reached to '.$project_data->project_name;
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
       }
       else if($inout == 0){ // if entered not exit previously
          //  $res['status'] = false;
           // $res['response']['error']['message'] = "This User already Logged in Please Logout First";
            //$this->response($res);
				 $result = $this->Project->updateSubbyTime($project_id,$User_id,$inout);
            $project_data = $this->db->query("SELECT user_id, project_name FROM checkmate_project  WHERE id = $project_id")->row();
						$subbie_info = $this->db->where('id', $project_data->user_id)->from('checkmate_user')->get()->result_array();
             $device_token =  $this->db->where('user_id', $project_data->user_id)->from('checkmate_user_device')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'Subbie location update';
                        $message = $subbie_info[0]['name'].' is reached to '.$project_data->project_name;
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
       }


       ///////////if user not login.////////
       
       if(empty($locationcheck) && $inout == 1 )
       {
//            $res['status'] = false;
//            $res['response']['error']['message'] = "This user not logged in please Login first";
//            $this->response($res);
				  $result = $this->Project->updateSubbyTime($project_id,$User_id,$inout);
            $project_data = $this->db->query("SELECT user_id, project_name FROM checkmate_project  WHERE id = $project_id")->row();
						$subbie_info = $this->db->where('id', $project_data->user_id)->from('checkmate_user')->get()->result_array();
             $device_token =  $this->db->where('user_id', $project_data->user_id)->from('checkmate_user_device')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'Subbie location update';
                        $message = $subbie_info[0]['name'].' is exit from '.$project_data->project_name.' region';
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
       }
       if($inout == 1)
       {   // Exit
           $result = $this->Project->updateSubbyTime($project_id,$User_id,$inout,$entry_id);
           $result = $this->Project->updateSubbyTime($project_id,$User_id,$inout,$entry_id);
					 $subbie_info = $this->db->where('id', $project_data->user_id)->from('checkmate_user')->get()->result_array();
           $project_data = $this->db->query("SELECT user_id, project_name FROM checkmate_project  WHERE id = $project_id")->row();
           $device_token =  $this->db->where('user_id', $project_data->user_id)->from('checkmate_user_device')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'Subbie location update';
                       $message = $subbie_info[0]['name'].' is exit from '.$project_data->project_name.' region';
       }
        if (!$result)
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "No result found !!!";
            $this->response($res);
        }
        else
        {
            $res['status'] = true;
            $res['response']['success']['message'] = "TimeLog Updated Successfully!!!";
            $this->response($res);
        }
    }
    
    

    /* Equipment Add*/
    /**
     * @OA\Post(path="/ProjectController/equipmentAdd",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="equipmentAdd",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="equipment_name",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="short_desc",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="long_desc",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="image",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  


     public function equipmentAdd_post()
     {
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $equipment_name = $this->post('equipment_name');
        $equipment_short_desc = $this->post('short_desc');
        $equipment_long_desc = $this->post('long_desc');
        $equipment_image = $this->input->post('image');

        if(!empty($equipment_image))
        {
            $equipment_image = $this->upload('equipments',$equipment_image);
        }

        $data = array(
            'equipment_name' => $equipment_name,
            'equipment_short_desc'=>$equipment_short_desc,
            'equipment_long_desc'=>$equipment_long_desc,
            'equipment_image'=>!empty($equipment_image)?$equipment_image:"",
            'created_at'=>now(),
            'updated_at'=>now()
        );

        $rs = $this->Project->save($data, 'checkmate_equipment');

        if($rs)
        {
            $res['status'] = true;
            $res['response']['success'] = 'Equipment added successfully.';
            $this->response($res);
        }
        else
        {
            $res['status'] = false;
            $res['response']['error'] = 'Unable to add equipments.';
            $this->response($res);
        }

    }
 /*
 @ equipment_assign_user
 @ Add a quipment  to USER


 **/


  public function equipment_assign_user_post()
     {
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }
         if ($this->post('user_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }
        ////////////////////////

           $equipmentId=$this->post('equipment_id');
           $equipmentId=$this->post('equipment_id');
           $assign_user_id=$this->post('assign_user_id');
           $equipment_type=$this->post('equipment_type');

        #################

        

         // Assign User ID//
        if ($this->post('assign_user_id') == ""||$assign_user_id<0) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Assign to User id required";
            $this->response($res);
        }




         if ($this->post('equipment_id') == ""||$equipmentId<0) {
            $res['status'] = false;
            $res['response']['error']['message'] = "equipment id required";
            $this->response($res);
        }


         


         if ($this->post('equipment_type') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "equipment type required";
            $this->response($res);
        }
        // Validate equipment /////////
         $this->db->select('*');
         $this->db->from('checkmate_user_equipments');
         $this->db->where(['resource_id'=> $assign_user_id, 'equipment_id'=>$equipmentId,'equipment_type'=>$equipment_type ]);   
         //$this->db->where('time_out is null');  

        
          $valid= $this->db->get()->result_array();
          // num_rows();
         // print_r($valid); die; 
          if(isset($valid)&&count($valid)==1){
            
             $res['status'] = false;
            $res['response']['error'] = 'Equipment already asigined to user.';
            $this->response($res);


          }else{  // 'add new';
           

        $data = array(
            'resource_id' => $assign_user_id,
            'equipment_id'=>$equipmentId,
            'equipment_type'=>$equipment_type,
            //'created_at'=>now(),
            'created_at'=>now()
        );

        $rs = $this->Project->save($data, 'checkmate_user_equipments');

        if($rs){
            $res['status'] = true;
            $res['response']['success'] = 'Assigned successfully.';
            $this->response($res);
        }else{
            $res['status'] = false;
            $res['response']['error'] = 'Unable to asigin equipments.';
            $this->response($res);
        }

         } //  echo 'add new';
        ///////////////////



    }




    /* Add team member to project */
    /**
     * @OA\Post(path="/ProjectController/addTeamToProject",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addTeamToProject",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Parameter(
     *     name="team_member_id",
     *      required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
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
     public function addTeamToProject_post()
     {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        if ($this->post('project_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please provide project id";
            $this->response($res);
        }

        if ($this->post('team_member_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please provide team member to add to the project";
            $this->response($res);
        }

        # validate if project id is correct

        $projectData = $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();

        if (empty($projectData)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Invalid project id, please check and try again";
            $this->response($res);
        }

        # validate if team member id is correct

        $teamMemberData = $this->db->where("id", $this->post('team_member_id'))->from('checkmate_team')->get()->result_array();

        if (empty($teamMemberData)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Invalid team member id, please check and try again";
            $this->response($res);
        }

        $data = array(
            'user_id' => $this->post('user_id'),
            'project_id' => $this->post('project_id'),
            'employee_id' => $this->post('team_member_id'),
            'created_at' => now(),
            'updated_at' => now(),
        );

        $result = $this->Project->addTeamToProject($data);
                $from_user =  $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();
				$from_user_name = $from_user[0]['name'];
				$to_user =  $this->db->where('id', $this->post('team_member_id'))->from('checkmate_user')->get()->result_array();
				$to_user_name = $from_user[0]['name'];
				$project_info =  $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();
				$project_name = $project_info[0]['name'];

				$device_token =  $this->db->where('user_id', $this->post('team_member_id'))->from('checkmate_user_device')->get()->result_array();
				$tokens = array();
				for($inc = 0; $inc <count($device_token); $inc++) {
					$tokens[] = $device_token[$inc]['device_token'];
				}
				$title = 'You have added in '.$project_name;
				$message = $from_user_name.' added you have added in '.$project_name.' team.';
				//end notification
				$this->Login->send_ios_notification($token, $title, $message);

        if (empty($result)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
            $this->response($res);
        }

        $res['status'] = true;
        $res['response']['success']['message'] = "Team member added to project";
        $this->response($res);
    }
    /* Add equipment to project */
/**
     * @OA\Post(path="/ProjectController/inviteCode",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="inviteCode",
     @OA\Parameter(
     *           name="code",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function inviteCode_post()
     {

        $code = $this->post('code');


        # Get taksList
        $result = $this->Project->getInviteCode($code);

        if(empty($result)) 
        {
            $res['status'] = false;
            $res['response']['error'] = 'No result found !!!.';
            $this->response($res);
        }
        $projectDetail = $this->Project->getByCriteria(array( 'id'=>$result->project_id), 'checkmate_project', array('id, project_estimate, project_name, project_address, project_city, project_lat, project_lng, project_status,milestone_coordinates'));
        $result->project = $projectDetail;
        $res['status'] = true;
        $res['response']['code'] = $code;
        $res['response']['result'] = $result;
        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/createAccountByInviteCode",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="createAccountByInviteCode",
     @OA\Parameter(
     *           name="code",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="name",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="email_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="password",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="location_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="device_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="device_type",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function createAccountByInviteCode_post()
     {
         $res = array();
/*
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
*/
        $code = $this->post('code');
        $name = $this->post('name');
        $emailId = $this->post('email_id');
        $password = $this->post('password');
        $location = $this->post('location_id');

        # Step 1 validate token 
/*
        if (!$this->validate_access_token($access_token, $user_id)) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }
*/
        $result = $this->Project->getInviteCode($code);
        
        if(is_array($result)) 
        {
            $res['status'] = false;
            $res['response']['error'] = 'invite code invalid!!!.';
            $this->response($res);
        }
        if($result->status > 0) 
        {
            $res['status'] = false;
            $res['response']['error'] = 'invite code used !!!.';
            $this->response($res);
        }

        $update_user_data = array(
            'status' => 1,
            'updated_at' => getCurrDateTime(),
        );

        $this->Project->updateInviteCode($code,$update_user_data);

        $this->Project->updateResource($result->team_member_id, array(  'updated_at' => getCurrDateTime(),'status' => 1, 'email' => $emailId, 'password' => $password, 'location_id' => $location));
        $this->sendmail('activation_invite','activation_invite',$emailId,array('message'=>''));
        $res['status'] = true;
        $res['response']['result'] = $result;
        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/addInviteResource",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addInviteResource",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
@OA\Parameter(
     *           name="organisation_name",
     *           description="your organisation_name",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="email_address",
     *           description="your email_address",
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
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="resource_type",
     *           description="your resource_type",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_id",
     *           description="your project_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="payment_type",
     *           description="your payment_type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="paymetn_value",
     *           description="your paymetn_value",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="employee_position",
     *           description="your employee_position",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="working_hours",
     *           description="your working_hours",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="manager",
     *           description="your manager",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     
     public function addInviteResource_post()
     {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $name = $this->post('organisation_name');
        $emailId = $this->post('email_address');
        $location = $this->post('location_id');

        $userTitle = $this->post('user_title');

        $userTitle = (!empty($userTitle)) ? $userTitle : '';

        # validate if people id is correct
        $isEmailExists = $this->Login->emailExistsInvite($emailId);
        if(!empty($isEmailExists))
        {
            $res['status'] = false;
            $res['response']['error'] = "Email id already registered, please try different.";
            $this->response($res);
        }
        
        $invite_user_data = array(
            'guid_id'       =>      0,
            'location_id'   =>      $location,
            'user_title'    =>      $userTitle,
            'created_at'    =>      now(),
            'updated_at'    =>      now(),
            "email_id"      =>      $this->post('email_address'),
            "password"      =>      uniqid(),
            "user_type"     =>      $this->post('resource_type'),
            'created_at'    =>      now(),
            'updated_at'    =>      now(),
            "address"       =>      "",
            "phone_no"      =>      "",
            "avatar"        =>      base_url('api/' . $this->upload('avatars',$this->post('image'))),
            "name"          =>      $this->post('organisation_name'),
            "is_invited"    =>      1,
        );

        $invite_user = $this->Project->addTeamMember($invite_user_data);
        $guid_id = "GUID" . date('Y') . '0' . $invite_user;
        $update_user_data = array('guid_id' => $guid_id);
        $this->Login->updateUserData($invite_user, $update_user_data);

        $ownerName = $this->Project->getOwnerName($this->post('user_id'));

        $invite_code = uniqid();
        $data = array(
           'team_member_id' => $invite_user,
           'owner_id'       => $this->post('user_id'),
           'project_id'     => $this->post('project_id'),
           'created_at'     => now(),
           'updated_at'     => now(),
           'expired_at'     =>  date('Y-m-d H:m:s', strtotime('+1 week')),
           "code"           => $invite_code,
           "status"         => "0",
           "email_address"  => $this->post('email_address'),
           "resource_type"  => $this->post('resource_type'),
           "paymetn_value"  => $this->post('paymetn_value'),
           "payment_type"   => $this->post('payment_type'),
           "organisation_name"=> $this->post('organisation_name'),
           "employee_position"  => $this->post('employee_position'),
           "working_hours"  => $this->post('working_hours'),
           "manager"    => $this->post('manager'),
       );

        $invite_id = $this->Project->insertInvite($data);
        if (empty($invite_user)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
            $this->response($res);
        }

        $emailData = array();
        $emailData['full_name'] = $name;
        $emailData['sender_full_name'] = $ownerName;
        $emailData['active_code'] = getNewAccessToken();
        $emailData['invite_code'] = $invite_code;
        $emailData['active_url'] = base_url('api/welcome/activation?type=1&id='.$invite_code);
        $emailData['message'] = 'invite added to project';

        $this->sendmail('invite_new','invite',$this->post('email_address'),$emailData);
        $res['status'] = true;
        $res['response']['success']['message'] = "invite added to project";
        $res['response']['success']['data'] = array("id"=>$invite_user,"expired_at"=>$data['expired_at'],"code"=>$data['code'],'image'=>$invite_user_data['avatar']);
        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/addResourceToProject",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addResourceToProject",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="resources_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */ 
     public function addResourceToProject_post()
     {
        $res = array();

        // if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Invalid access token.';
        //     $this->response($res);
        // }

        # Check if user exists
        if ($this->post('user_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        if ($this->post('project_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please provide project id";
            $this->response($res);
        }

        # validate if project id is correct

        $projectData = $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();

        if (empty($projectData)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Invalid project id, please check and try again";
            $this->response($res);
        }

        # validate if people id is correct
        $list_resources = explode(',', $this->post('resources_id'));
        if(!empty($list_resources) && is_array($list_resources))
        {
            $from_user =  $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();
                            $from_user_name = $from_user[0]['name'];
                            $project_info =  $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();
                            $project_name = $project_info[0]['project_name'];
                            $title = 'New project';
                            $message = $from_user_name.' added you in '.$project_name;

            foreach($list_resources as $pt)
            {
                $data = array(
                   'user_id' => $this->post('user_id'),
                   'project_id' => $this->post('project_id'),
                   'resources_id' => $pt,
                   'created_at' => now(),
                   'updated_at' => now()
               );

               $result = $this->Project->addResourceToProject($data);
                $device_token =  $this->db->where('user_id', $pt)->from('checkmate_user_device')->get()->result_array();
                  for($inc = 0; $inc <count($device_token); $inc++) {
                    $tokens[] = $device_token[$inc]['device_token'];
                   }
            }
            
            $this->Login->send_ios_notification($tokens, $title, $message);
        }


        if (empty($result)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
            $this->response($res);
        }

        $res['status'] = true;
        $res['response']['success']['message'] = "resource added to project";
        $this->response($res);
    }


    /* Add equipment to project */
    /**
     * @OA\Post(path="/ProjectController/addEquipementToProject",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addEquipementToProject",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="equipment_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */ 
     
     public function sendtestmail_post()
     {
        $invite_code = "kjdskhfjsdhfjas";
        $email = 'Saurabh@aviktechnosoft.com';
        $this->sendmail('registration','registration',$email,array('invite_code'=> $invite_code,'message'=>"invite added to project","active_url"=> base_url('api/welcome/activation?type=1&id='.$invite_code)));
        $res['status'] = true;
        $res['response']['success']['message'] = "Equipment added to project";
        $this->response($res);
    }
    public function addEquipementToProject_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        if ($this->post('project_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please provide project id";
            $this->response($res);
        }

        if ($this->post('equipment_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please provide equipment id";
            $this->response($res);
        }

        # validate if project id is correct

        $projectData = $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();

        if (empty($projectData)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Invalid project id, please check and try again";
            $this->response($res);
        }

        # validate if equipment id is correct
        $list_resources = explode(',', $this->post('equipment_id'));
        if(!empty($list_resources) && is_array($list_resources))
        {
            foreach($list_resources as $pt)
            {
                $equipmentData = $this->db->where("id", $pt)->from('checkmate_equipment')->get()->result_array();

                if (empty($equipmentData)) {
                   $res['status'] = false;
                   $res['response']['error']['message'] = "Invalid equipment id, please check and try again";
                   $this->response($res);
               }

               $data = array(
                   'user_id' => $this->post('user_id'),
                   'project_id' => $this->post('project_id'),
                   'equipment_id' => $pt,
                   'created_at' => now(),
                   'updated_at' => now(),
               );

               $result = $this->Project->addEquipmentToProject($data);
           }
       }


       if (empty($result)) {
        $res['status'] = false;
        $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
        $this->response($res);
    }

    $res['status'] = true;
    $res['response']['success']['message'] = "Equipment added to project";
    $this->response($res);
}

/* Create Tasks and sub task */
    /**
     * @OA\Post(path="/ProjectController/addTask",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addTask",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     *      @OA\Parameter(
     *           name="milestone_id",
     *           description="your milestone_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_name",
     *           description="your task_name",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_start_date",
     *           description="your task_start_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_end_date",
     *           description="your task_end_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_description",
     *           description="your task_description",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_estimation",
     *           description="your task_estimation",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_team",
     *           description="your task_team",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_team_leader",
     *           description="your task_team_leader",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_equipment",
     *           description="your task_equipment",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
    public function addTask_post()
    {
        # Step 1 validate token 
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $milestone_id = $this->post('milestone_id');
        $startDate = $this->post('task_start_date');
        $start_date = date("Y-m-d H:i:s", strtotime($startDate));
        $endDate = $this->post('task_end_date');
        $end_date = date("Y-m-d H:i:s", strtotime($endDate));
        $taskName = $this->post('task_name');
        if(empty($taskName))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide task name';
            $this->response($res);
        }
        $user_id = $this->post('user_id');
        $project_id = $this->post('project_id');
        $task_description = $this->post('task_description');
        $task_estimation = $this->post('task_estimation');
        $task_start_date = $start_date;
        $task_end_date = $end_date;
        $task_team = $this->post('task_team');
        $task_team_leader = $this->post('task_team_leader');
        $task_equipment = $this->post('task_equipment');

        $data = array(
            'user_id'=>$user_id,
            'project_id'=>$project_id,
            'milestone_id'=>$milestone_id,
            'task_name'=>$taskName,
            'task_description'=>$task_description,
            'task_estimation'=>$task_estimation,
            'task_start_date'=>$task_start_date,
            'task_end_date'=>$task_end_date,
            'task_team'=>$task_team,
            'task_team_leader'=>$task_team_leader,
            'task_equipment'=>$task_equipment,
            'task_parent'=>0
        );

        $rs = $this->Project->save($data, 'ref_task');

        if(!$rs)
        {
            $res['status'] = false;
            $res['response']['error'] = 'Unable to add task, please try again later';
            $this->response($res);
        }
        else
        {
         $res['status'] = true;
         $res['response']['success'] = 'Task added successfully.';
         $this->response($res);
     }


 }
    /**
     * @OA\Post(path="/ProjectController/taskList",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="taskList",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function taskList_post()
     {
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_id = $this->post('project_id');
        $milestone_id = $this->post('milestone_id');

        # Step 1 validate token 
        if (!$this->validate_access_token($access_token, $user_id)) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Get taksList
        $taskList = $this->Project->taskList($user_id, $project_id,$milestone_id) ;
        if(empty($taskList)) 
        {
            $res['status'] = false;
            $res['response']['error'] = 'No result found !!!.';
            $this->response($res);
        }

        $res['status'] = true;
        $res['response']['result'] = $taskList;
        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/addSubTask",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addSubTask",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="subtask_start_date",
     *           description="your subtask_start_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_end_date",
     *           description="your subtask_end_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="task_id",
     *           description="your task_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_name",
     *           description="your subtask_name",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_id",
     *           description="your project_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_estimate",
     *           description="your subtask_estimate",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_description",
     *           description="your subtask_description",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_team",
     *           description="your subtask_team",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_team_leader",
     *           description="your subtask_team_leader",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_equipment",
     *           description="your subtask_equipment",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function addSubTask_post() 
     {
        # Step 1 validate token 
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $startDate = $this->post('subtask_start_date');
        $start_date = date("Y-m-d H:i:s", strtotime($startDate));
        $endDate = $this->post('subtask_end_date');
        $end_date = date("Y-m-d H:i:s", strtotime($endDate));
        $taskId = $this->post('task_id');
        $subTaskName = $this->post('subtask_name');
        if(empty($subTaskName))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide subtask name';
            $this->response($res);
        }
        $project_id = $this->post('project_id');
        $subtask_estimate = $this->post('subtask_estimate');
        $subtask_description = $this->post('subtask_description');
        $subtask_team = $this->post('subtask_team');
        $subtask_team_leader = $this->post('subtask_team_leader');
        $subtask_equipment = $this->post('subtask_equipment');

        $data = array(
            'project_id'=>$project_id,
            'task_id'=>$taskId,
            'subtask_name'=>$subTaskName,
            'subtask_start_date'=>$start_date,
            'subtask_end_date'=>$end_date,
            'subtask_estimate'=>$subtask_estimate,
            'subtask_description'=>$subtask_description,
            'subtask_team'=>$subtask_team,
            'subtask_team_leader'=>$subtask_team_leader,
            'subtask_equipment'=>$subtask_equipment
        );

        $rs = $this->Project->save($data, 'ref_sub_task');

        if(!$rs)
        {
            $res['status'] = false;
            $res['response']['error'] = 'Unable to add task, please try again later';
            $this->response($res);
        }
        else
        {
            $res['status'] = true;
            $res['response']['success'] = 'Subtask added successfully.';
            $this->response($res);
        }
    }
    /**
     * @OA\Post(path="/ProjectController/subTaskList",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="subTaskList",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="task_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function subTaskList_post()
     {
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_id = $this->post('project_id');
        $task_id = $this->post('task_id');

        # Step 1 validate token 
        if (!$this->validate_access_token($access_token, $user_id)) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Get taksList
        $subTaskList = $this->Project->subTaskList($user_id,$project_id,$task_id);
        if(empty($subTaskList)) 
        {
            $res['status'] = false;
            $res['response']['error'] = 'No result found !!!.';
            $this->response($res);
        }

        $res['status'] = true;
        $res['response']['result'] = $subTaskList;
        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/editMilestone",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="editMilestone",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *         @OA\Parameter(
     *           name="project_milestone_coordinates",
     *           description="your project_milestone_coordinates",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_team",
     *           description="your project_team",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *      
     *         ),
     @OA\Parameter(
     *           name="project_equipment",
     *           description="your project_equipment",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="start_date",
     *           description="your start_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_teamlead",
     *           description="your project_teamlead",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_milestone_name",
     *           description="your project_milestone_name",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="punch_list_type",
     *           description="your punch_list_type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_milestone_estimate",
     *           description="your project_milestone_estimate",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_description",
     *           description="your project_description",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="budget",
     *           description="your budget",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */ 
     public function editMilestone_post()
     {
       //print_r($_POST); exit;
        $milestone_id = $this->post('milestone_id');
        $coordinates =  implode(',', json_decode($this->post('project_milestone_coordinates'), true)  );
        $project_team = implode(',', json_decode($this->post('project_team'), true) );
        $projectEquipment = implode(',', json_decode($this->post('project_equipment'), true) );        
        $project_teamlead = $this->post('project_teamlead');

        $startDate = $this->post('start_date');
        $start_date = date("Y-m-d H:i:s", strtotime($startDate));
        $endDate = $this->post('end_date');
        $end_date = date("Y-m-d H:i:s", strtotime($endDate));

        $project_milestone_name = $this->post('project_milestone_name');
        $punch_list_type = $this->post('punch_list_type');
        $project_id = $this->post('project_id');
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $estimate = $this->post('project_milestone_estimate');
        $start_date = $start_date; 
        $end_date = $end_date; 
        $description = $this->post('project_description');
        $budget = $this->post('budget');
        



        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }
        


        if(empty($project_id))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please send project id.';
            $this->response($res);
        }

        if(empty($punch_list_type))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide punch list.';
            $this->response($res);
        }

        if(empty($estimate))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Project milestone estimate can\'t be null.';
            $this->response($res);
        }

        if( count($project_team) == 0 || empty($project_teamlead) || count($projectEquipment) == 0)
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please assign Team/Team Lead or Equipments for the project.';
            $this->response($res);
        }
        


        $rs = $this->Project->getProjectByUserAndId($user_id, $project_id);
        
        if(empty($rs))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Milestone can be set for self projects only';
            $this->response($res);
        }


        $data = array(
            'punch_list_type'=>$punch_list_type,
            'project_milestone_name'=>$project_milestone_name,            
            'project_id'=>$project_id,         
            'teamlead_id'=>$project_teamlead,        
            'employee_id'=>!empty($project_team)?$project_team:'',         
            'equipment_id'=>!empty($projectEquipment)?$projectEquipment:'', 
            'project_milestone_estimate'=>$estimate,
            'milestone_coordinates'=>!empty($coordinates)?$coordinates:'',
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'description'=>$description,
            'budget'=>$budget,
            'created_at'=>now(),
            'updated_at'=>now()

        );
				$project_team = explode(',', $project_team);
				foreach($project_team as $pt)
				{
					$from_user =  $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();
					$from_user_name = $from_user[0]['name'];
					$to_user =  $this->db->where('id', $pt)->from('checkmate_user')->get()->result_array();
					$to_user_name = $from_user[0]['name'];
					$project_info =  $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();
					$project_name = $project_info[0]['project_name'];

					$device_token =  $this->db->where('user_id', $pt)->from('checkmate_user_device')->get()->result_array();
					$tokens = array();
					for($inc = 0; $inc <count($device_token); $inc++) {
						$tokens[] = $device_token[$inc]['device_token'];
					}
					$title = 'New Milestone';
					$message = $from_user_name.' added you in '.$project_milestone_name;
					$this->Login->send_ios_notification($tokens, $title, $message);
				}
				$from_user =  $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();
					$from_user_name = $from_user[0]['name'];
					$to_user =  $this->db->where('id', $pt)->from('checkmate_user')->get()->result_array();
					$to_user_name = $from_user[0]['name'];
					$project_info =  $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();
					$project_name = $project_info[0]['project_name'];

					$device_token =  $this->db->where('user_id', $project_teamlead)->from('checkmate_user_device')->get()->result_array();
					$tokens = array();
					for($inc = 0; $inc <count($device_token); $inc++) {
						$tokens[] = $device_token[$inc]['device_token'];
					}
					$title = 'New Milestone';
					$message = $from_user_name.' added you in '.$project_milestone_name;
					$this->Login->send_ios_notification($tokens, $title, $message);
					
        $milestoneId_data = $this->Project->updateMilestone($milestone_id,$data);


        $projectTeam = $this->post('project_team');
        $projectTeam = json_decode($projectTeam);
        if(!empty($projectTeam))
        {
            $tokens = array();
            $project_id = $this->post('project_id');
            $from_user =  $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();
                            $from_user_name = $from_user[0]['name'];
                            $project_info =  $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();
                            $project_name = $project_info[0]['project_name'];
                            $title = 'New project';
                            $message = $from_user_name.' added you in '.$project_name;
            $resourceID = $this->db->select('resources_id')->from('checkmate_project_resources')->where('project_id',$project_id)->get()->row_array();
            $resourceID = (!empty($resourceID)) ? $resourceID['resources_id'] : 0;
            foreach ($projectTeam as $projectTeamKey => $projectTeamValue)
            {
                $checkRecords = $this->db->select('id')->from('checkmate_project_resources')->where('user_id',$projectTeamValue)->get()->row_array();
                if(count($checkRecords) == 0)
                {
                    $insertData = array();
                    $insertData['created_at'] = getCurrDateTime();
                    $insertData['user_id'] = $projectTeamValue;
                    $insertData['project_id'] = $project_id;
                    $insertData['resources_id'] = $resourceID;
                    $this->db->insert('checkmate_project_resources',$insertData);
                    $device_token =  $this->db->where('user_id', $resourceID)->from('checkmate_user_device')->get()->result_array();
                           
                            for($inc = 0; $inc <count($device_token); $inc++) {
                                $tokens[] = $device_token[$inc]['device_token'];
                    }
                }
            }
            $this->Login->send_ios_notification($tokens, $title, $message);
        }

        if($milestoneId_data)
        {           
            $res['status'] = true;
            $res['response']['success'] = 'New milestone added successfully';
            $this->response($res);
        }
        else
        {
            $res['status'] = false;
            $res['response']['error'] = $data;//'Something went wrong, please try again later';
            $this->response($res);
        }


    }
      // update milestone employee_id
   public function SubbieToMilestone_post()
     {

        $milestone_id = $this->post('milestone_id');
        $user_id = $this->post('user_id');
        $subbie_id = $this->post('subbie_id'); 
        $status = $this->post('status');


        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
             $res['status'] = false;
             $res['response']['error'] = 'Invalid access token.';
             $this->response($res);
         }

        if(empty($subbie_id))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please send subbie id.';
            $this->response($res);
        }
        // if(empty($status))
        // {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Please send status.';
        //     $this->response($res);
        // }

        if(empty($milestone_id))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide milestone id';
            $this->response($res);
        }
        $is_exist = $this->Project->isExistMilestoneEmpId($milestone_id,$subbie_id);
         $resourseData = $this->db->query('SELECT * FROM checkmate_project_resources pS LEFT JOIN checkmate_project_milestone pM ON pS.project_id = pM.project_id WHERE  pS.resources_id = '.$subbie_id.' AND pM.id = '.$milestone_id.'')->row();
        if(empty($resourseData))
        {
            //echo '1'; die();
            $projectData = $this->db->query('SELECT project_id FROM checkmate_project_milestone WHERE id = '.$milestone_id.'')->row();
            $insertData = array();
                    $insertData['created_at'] = getCurrDateTime();
                    $insertData['user_id'] = $user_id;
                    $insertData['project_id'] = $projectData->project_id;
                    $insertData['resources_id'] = $subbie_id;
                    $this->Project->addResource($insertData);
        }
        if($status == "1")
        {
            if($is_exist)
            {
                $res['status'] = false;
                $res['response']['error'] = 'Subbie already assign to this milestone';
                $this->response($res);
            }
            $milestoneId_data = $this->db->query("UPDATE checkmate_project_milestone SET employee_id = CONCAT(employee_id,',',$subbie_id) WHERE id=$milestone_id");
            if($milestoneId_data)
            { 
                $from_user =  $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();
                            $from_user_name = $from_user[0]['name'];
                            $to_user =  $this->db->where('id', $this->post('subbie_id'))->from('checkmate_user')->get()->result_array();
                            $to_user_name = $from_user[0]['name'];
//                          $project_info =  $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();
//                          $project_name = $project_info[0]['name'];
                            
                            $device_token =  $this->db->where('user_id', $this->post('subbie_id'))->from('checkmate_user_device')->get()->result_array();
                            $tokens = array();
                            for($inc = 0; $inc <count($device_token); $inc++) {
                                $tokens[] = $device_token[$inc]['device_token'];
                            }
                            $title = 'You have added in milestone';
                            $message = $from_user_name.' added you have added in milestone';
                            $this->Login->send_ios_notification($tokens, $title, $message);          
                $res['status'] = true;
                $res['response']['success'] = 'subbie assign to milestone added successfullyXX';
                $this->response($res);
            }
            else
            {
                $res['status'] = false;
                $res['response']['error'] = $data;//'Something went wrong, please try again later';
                $this->response($res);
            }
        }
        if($status == "0")
        {
            if(!$is_exist)
            {
                $res['status'] = false;
                $res['response']['error'] = 'Subbie not exist in this milestone';
                $this->response($res);
            }
            $milestoneId_remove = $this->db->query("UPDATE checkmate_project_milestone SET employee_id = TRIM(BOTH ',' FROM REPLACE(REPLACE(CONCAT(',',REPLACE(employee_id, ',', ',,'), ','),',$subbie_id,', ''), ',,', ',')) WHERE FIND_IN_SET($subbie_id, employee_id) AND id = $milestone_id");
            if($milestoneId_remove)
            {           
                $res['status'] = true;
                $res['response']['success'] = 'subbie removed to milestone successfully';
                $this->response($res);
            }
            else
            {
                $res['status'] = false;
                $res['response']['error'] = $data;//'Something went wrong, please try again later';
                $this->response($res);
            }
        }
        


    }

/*
    public function addMilestone_post()
    {
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_id = $this->post('project_id');
        $milestone_name = $this->post('milestone_name');

        # Step 1 validate token 
        if (!$this->validate_access_token($access_token, $user_id)) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }
        
        $data = array(
            'user_id'=>$user_id,
            'project_id'=>$project_id,
            'milestone_name'=>$milestone_name,            
            'date_created'=>now(),
            'date_updated'=>now()
        );

        $rs = $this->Project->save($data, 'ref_milestone');

        if(!$rs)
        {
            $res['status'] = false;
            $res['response']['error'] = 'Unable to add milestone, please try again later';
            $this->response($res);
        }
        else
        {
            $res['status'] = success;
            $res['response']['success'] = 'Milestone added successfully.';
            $this->response($res);
        }
    }
*/
/**
     * @OA\Post(path="/ProjectController/milestoneList",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="milestoneList",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="need_project_details",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_picture",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */ 

     public function milestoneList_post()
     {
        $response = array();
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $response['status'] = false;
            $response['response']['error'] = 'Invalid access token.';
            $this->response($response);
        }

        $milestoneUserList = $this->Project->milestoneUserList($this->post('project_id'));

        foreach ($milestoneUserList as $milestoneUserListKey => $milestoneUserListValue)
        {
            $milestoneUserList[$milestoneUserListKey]['teamLeadInfo'] = $this->Project->getUserInfo($milestoneUserListValue['teamlead_id']);

            $teamInfo = array();

            $employees = (!empty($milestoneUserListValue['employee_id'])) ? explode(",", $milestoneUserListValue['employee_id']) : array();

            foreach ($employees as $employeesKey => $employeesValue)
            {
                $teamInfo[$employeesKey] = $this->Project->getUserInfo($employeesValue);
            }

            $milestoneUserList[$milestoneUserListKey]['teamMemberInfo'] = $teamInfo;

            $equipmentInfo = array();

            $equipments = (!empty($milestoneUserListValue['equipment_id'])) ? explode(",", $milestoneUserListValue['equipment_id']) : array();

            foreach ($equipments as $equipmentsKey => $equipmentsValue)
            {
                $equipmentInfo[$equipmentsKey] = $this->Project->getEquipmentInfo($equipmentsValue);
            }

            $milestoneUserList[$milestoneUserListKey]['equipmentInfo'] = $equipmentInfo;

        }

        if (empty($milestoneUserList))
        {
            $response['status'] = false;
            $response['response']['error']['message'] = 'No result found !!!';
            $this->response($response);
        }

        foreach ($milestoneUserList as $k => $p) 
        {
            $projectDetail = $this->Project->getByCriteria(array('user_id'=>$this->post('user_id'), 'id'=>$p['project_id']), 'checkmate_project', array('id, project_estimate, project_name, project_address, project_city, project_lat, project_lng, project_status'));
            $milestoneUserList[$k]['project']= count($projectDetail)>0?$projectDetail[0]:array() ;
        }


      $response['status'] = true;
      $response['response']['result'] = $milestoneUserList;
      $this->response($response);        
  }
/*
    public function milestoneList_post()
    {
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_id = $this->post('project_id');
        $need_project_details = $this->post('need_project_details');

        if (!$this->validate_access_token($access_token, $user_id)) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

            
        if($need_project_details == false){
             # Get milestone list
            $milestoneDetailList = $this->Project->getByCriteria(array('project_id'=>$project_id), 'checkmate_project_milestone', array('id, milestone_name'));
            $res['status'] = true;
            $res['response']['milestoneDetailList'] = $milestoneDetailList;
            $this->response($res);
        }else{
             # Get milestone list with project details
             $projectDetail = $this->Project->getByCriteria(array('user_id'=>$user_id, 'id'=>$project_id), 'checkmate_project', array('id, project_estimate, project_name, project_address, project_city, project_lat, project_lng, project_status,milestone_coordinates'));
             $milestoneTeamDetailList = $this->Project->getMilestoneListByProjectID(array('project_id'=>$project_id), 'checkmate_project_milestone');
           

            $res['status'] = true;

            $res['response']['result'] = $milestoneTeamDetailList['milestoneList'];
            $this->response($res);
        }
        

        if(empty($milestoneList))
        {
            $res['status'] = false;
            $res['response']['error'] = 'No result found !!!';
            $this->response($res);
        }

    }
*/



    /*  create milestone */
    /**
     * @OA\Post(path="/ProjectController/addProjectMilestone",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addProjectMilestone",
    *         @OA\Parameter(
     *           name="project_milestone_coordinates",
     *           description="your project_milestone_coordinates",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_team",
     *           description="your project_team",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *      
     *         ),
     @OA\Parameter(
     *           name="project_equipment",
     *           description="your project_equipment",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="start_date",
     *           description="your start_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_teamlead",
     *           description="your project_teamlead",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_milestone_name",
     *           description="your project_milestone_name",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="punch_list_type",
     *           description="your punch_list_type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_id",
     *           description="your project_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="user_id",
     *     required=true,
     *           description="your user_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="access_token",
     *           description="your access_token",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_milestone_estimate",
     *           description="your project_milestone_estimate",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_description",
     *           description="your project_description",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="budget",
     *           description="your budget",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function addProjectMilestone_post()
     {
        /* Punch List
         * Scheduled = 1
         * UnScheduled = 2
         */ 

        $coordinates =  implode(',', json_decode($this->post('project_milestone_coordinates'), true)  );
        $project_team = implode(',', json_decode($this->post('project_team'), true) );
        $projectEquipment = implode(',', json_decode($this->post('project_equipment'), true) );     
        $project_teamlead = $this->post('project_teamlead');

        $startDate = $this->post('start_date');
        $start_date = date("Y-m-d H:i:s", strtotime($startDate));
        $endDate = $this->post('end_date');
        $end_date = date("Y-m-d H:i:s", strtotime($endDate));

        $project_milestone_name = $this->post('project_milestone_name');
        $punch_list_type = $this->post('punch_list_type');
        $project_id = $this->post('project_id');
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $estimate = $this->post('project_milestone_estimate');
        $start_date = $start_date; /* Start Date Format - YYYY-MM-DD*/
        $end_date = $end_date; /* End Date Format -YYYY-MM-DD*/
        $description = $this->post('project_description');
        $budget = $this->post('budget');


        # Step 1 validate token
        // if (!$this->validate_access_token($access_token, $user_id)) {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Invalid access token.';
        //     $this->response($res);
        // }

        if(empty($project_id))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please send project id.';
            $this->response($res);
        }

        // if(empty($punch_list_type))
        // {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Please provide punch list.';
        //     $this->response($res);
        // }
         //   estimate
        // if(empty($estimate))
        // {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Project milestone estimate can\'t be null.';
        //     $this->response($res);
        // }

        //print_r($project_milestone_name);  die;

            // check for project
         if(empty($project_milestone_name)){
             $res['status'] = false;
            //$res['response']['error'] = 'Test==='.$project_milestone_name;
            // $res['response']['error'] = 'Project Name can\'t be null.';
             $res['response']['error'] = 'Project Name can\'t be null.';
            $this->response($res);

         }



        // if( count($project_team) == 0 || empty($project_teamlead) || count($projectEquipment) == 0)
        // {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Please assign Team/ Team Lead or Equipments for the project.';
        //     $this->response($res);
        // }

        # Validate if project is valid and belongs to the user

        // $rs = $this->Project->getProjectByUserAndId($user_id, $project_id);
        
        // if(empty($rs))
        // {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Milestone can be set for self projects only';
        //     $this->response($res);
        // }


        /* Validation done, now let's store the information*/
        $table = 'checkmate_project_milestone';
        $data = array(
            'punch_list_type'=>$punch_list_type,
            'project_milestone_name'=>$project_milestone_name,            
            'project_id'=>$project_id,         
            'teamlead_id'=>$project_teamlead,        
            'employee_id'=>!empty($project_team)?$project_team:'',         
            'equipment_id'=>!empty($projectEquipment)?$projectEquipment:'', 
            'project_milestone_estimate'=>$estimate,
            'milestone_coordinates'=>!empty($coordinates)?$coordinates:'',
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'description'=>$description,
            'budget'=>$budget,
            'created_at'=>now(),
            'updated_at'=>now()

        );
        $milestoneId = $this->Project->save($data, $table);
				$project_team = explode(',', $project_team);
				foreach($project_team as $pt)
				{
					$from_user =  $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();
					$from_user_name = $from_user[0]['name'];
					$to_user =  $this->db->where('id', $pt)->from('checkmate_user')->get()->result_array();
					$to_user_name = $from_user[0]['name'];
					$project_info =  $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();
					$project_name = $project_info[0]['project_name'];

					$device_token =  $this->db->where('user_id', $pt)->from('checkmate_user_device')->get()->result_array();
					$tokens = array();
					for($inc = 0; $inc <count($device_token); $inc++) {
						$tokens[] = $device_token[$inc]['device_token'];
					}
					$title = 'New Milestone';
					$message = $from_user_name.' added you in '.$project_milestone_name;
					$this->Login->send_ios_notification($tokens, $title, $message);
				}
				$from_user =  $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();
					$from_user_name = $from_user[0]['name'];
					$to_user =  $this->db->where('id', $pt)->from('checkmate_user')->get()->result_array();
					$to_user_name = $from_user[0]['name'];
					$project_info =  $this->db->where('id', $this->post('project_id'))->from('checkmate_project')->get()->result_array();
					$project_name = $project_info[0]['project_name'];

					$device_token =  $this->db->where('user_id', $project_teamlead)->from('checkmate_user_device')->get()->result_array();
					$tokens = array();
					for($inc = 0; $inc <count($device_token); $inc++) {
						$tokens[] = $device_token[$inc]['device_token'];
					}
					$title = 'New Milestone';
					$message = $from_user_name.' added you in '.$project_milestone_name;
					$this->Login->send_ios_notification($tokens, $title, $message);
					
        $projectTeam = $this->post('project_team');
        $projectTeam = json_decode($projectTeam);
        if(!empty($projectTeam))
        {
            $project_id = $this->post('project_id');
            $resourceID = $this->db->select('resources_id')->from('checkmate_project_resources')->where('project_id',$project_id)->get()->row_array();
            $resourceID = (!empty($resourceID)) ? $resourceID['resources_id'] : 0;
            foreach ($projectTeam as $projectTeamKey => $projectTeamValue)
            {
                $checkRecords = $this->db->select('id')->from('checkmate_project_resources')->where('user_id',$projectTeamValue)->get()->row_array();
                if(count($checkRecords) == 0)
                {
                    $insertData = array();
                    $insertData['created_at'] = getCurrDateTime();
                    $insertData['user_id'] = $projectTeamValue;
                    $insertData['project_id'] = $project_id;
                    $insertData['resources_id'] = $resourceID;
                    $this->db->insert('checkmate_project_resources',$insertData);
                }
            }
        }

        if($milestoneId)
        {           
            $res['status'] = true;
            $res['response']['success'] = 'New milestone added successfully';
            $this->response($res);
        }

        $res['status'] = false;
        $res['response']['error'] = 'Something went wrong, please try again later';
        $this->response($res);
    }

    /* Add new todo */
    /**
     * @OA\Post(path="/ProjectController/addTodo",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addTodo",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="todo_title",
     *           description="your todo_title",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="start_date",
     *           description="your start_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="end_date",
     *           description="your end_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="todo_desc",
     *           description="your todo_desc",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="todo_team",
     *           description="your todo_team",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="todo_team_lead",
     *           description="your todo_team_lead",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="todo_equipment",
     *           description="your todo_equipment",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  

     public function addTodo_post()
     {
        # Step 1 validate token
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $todo_title = $this->post('todo_title');
        $start_date = $this->post('start_date');
        $end_date = $this->post('end_date');
        $todo_desc = $this->post('todo_desc');
        $todo_team = $this->post('todo_team');
        $todo_team_lead = $this->post('todo_team_lead');
        $todo_equipment = $this->post('todo_equipment');

        if(empty($todo_title))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide todo title';
            $this->response($res);
        }

        if(empty($start_date) || empty($end_date))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Start date or End date can\'t be null';
            $this->response($res);
        }

        if(empty($todo_team) || empty($todo_equipment) || empty($todo_team_lead))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please add Team/ Team Lead and Equipments to the todo';
            $this->response($res);
        }

        $table = "checkmate_todo";
        $data = array(
            'user_id'=> $user_id,
            'todo_title'=> $todo_title,
            'start_date'=> $start_date,
            'end_date'=> $end_date,
            'todo_description'=> $todo_desc,
            'date_created'=>now(),
            'date_updated'=>now()
        );
        $todoId = $this->Project->save($data, $table);

        if($todoId)
        {
            /* Assign team to todo
             * Team can have multiple employees, we are expecting
             * comma separated values for team
             */

            $todo_teamArr = explode(',', $todo_team);

            $table = 'checkmate_todo_team';
            if(!empty($todo_teamArr) && is_array($todo_teamArr))
            {
                foreach($todo_teamArr as $pt)
                {
                    $data = array(
                        'todo_id'=> $todoId,
                        'employee_id'=>$pt,
                        'date_created'=>now(),
                        'date_updated'=>now()
                    );

                    $this->Project->save($data, $table);
                }
            }

            /* Assign team lead to todo
             * Expecting comma separated values for team lead
             */
            $todo_team_leadArr = explode(',', $todo_team_lead);

            $table = 'checkmate_todo_teamleader';
            if(!empty($todo_team_leadArr) && is_array($todo_team_leadArr))
            {
                foreach ($todo_team_leadArr as $tl)
                {
                    $data = array(
                        'todo_id'=> $todoId,
                        'teamlead_id'=>$tl,
                        'date_created'=>now(),
                        'date_updated'=>now()
                    );
                    $this->Project->save($data, $table);
                }
            }

            /* Assign equipment to todo
             * Expecting comma separated values for equipment
             */

            $todo_equipmentArr = explode(',', $todo_equipment);

            $table  = 'checkmate_todo_equipment';
            if(!empty($todo_equipmentArr) && is_array($todo_equipmentArr))
            {
                foreach ($todo_equipmentArr as $m)
                {
                    $data = array(
                        'todo_id'=> $todoId,
                        'equipment_id'=>$m,
                        'date_created'=>now(),
                        'date_updated'=>now()
                    );
                    $this->Project->save($data, $table);
                }
            }

            $res['status'] = true;
            $res['response']['success'] = 'New todo added successfully';
            $this->response($res);
        }
        else
        {
            $res['status'] = false;
            $res['response']['error'] = 'OOPS !!! Something went wrong, please try again later';
            $this->response($res);
        }
    }
    /**
     * @OA\Post(path="/ProjectController/employee",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="employee",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="employee_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function employee_post()
     {
        $response = array();

        # Step 1 validate token
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $employeeId = $this->post('employee_id');
        $employeeData = $this->Project->getByCriteria(array('id'=>$employeeId), 'checkmate_team', array('name', 'email', 'image', 'phone', 'address'));
        if(empty($employeeData))
        {
            $res['status'] = false;
            $res['response']['error'] = 'No data found !!!';
            $this->response($res);
        }

        $res['status'] = true;
        $res['response']['success']['result'] = $employeeData;
        $this->response($res);

        return $response;
    }
    /**
     * @OA\Post(path="/ProjectController/equipment",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="equipment",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="equipment_id",
     *           description="your equipment_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="SEARCH_TEXT",
     *           description="your SEARCH_TEXT",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function equipment_post()
     {
        $response = array();

        # Step 1 validate token
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $equipmentId = $this->post('equipment_id');
        $equipmentData = $this->Project->getByCriteria(array('id'=>$equipmentId), 'checkmate_equipment', array('equipment_name', 'equipment_image'));

        if(empty($equipmentData))
        {
            $res['status'] = false;
            $res['response']['error'] = 'No data found !!!';
            $this->response($res);
        }

        $res['status'] = true;
        $res['response']['success']['result'] = $equipmentData;
        $this->response($res);

        return $response;
    }

/**
     * @OA\Post(path="/ProjectController/projectData",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectData",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function projectData_post()
     {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $user_id = $this->post('user_id');
        $project_id = $this->post('project_id');

        $criteria = array('id'=>$project_id, 'user_id'=>$user_id);
        $projects = $this->Project->getByCriteria($criteria, 'checkmate_project', array('id, user_id, project_estimate, project_no, project_name, project_city, project_address, project_lat, project_lng, project_site_map, project_status'));

        if (empty($projects)) {
            $res['status'] = false;
            $res['response']['error'] = "Project does not exists for user";
            $this->response($res);
        }

        /* Get project image based on project id */
        $projectImages = $this->Project->getByCriteria(array('project_id' => $project_id), 'checkmate_project_pictures');

        foreach ($projects as $k => $p) {

            if (!empty($projectImages)) {
                foreach ($projectImages as $i => $j) {
                    $projectImages[$i]->project_picture = strpos($j->project_picture,"http")>0?$j->project_picture:   base_url('api/' . $j->project_picture);
                }
            }

            $projects[$k]->project_site_map = $p->project_site_map;
            $projects[$k]->project_images = $projectImages;

            /* Get project equipment based on project id*/
            $projectEquipements = $this->Project->getProjectEquipments($p->id,0,2);
            $project_Equipements = array();
            if(!empty($projectEquipements))
            {
                foreach ($projectEquipements as $k => $m)
                {
                    $project_Equipements[$k]->id =  $m->id;
                    $project_Equipements[$k]->equipment_name = $m->equipment_name;
                    $project_Equipements[$k]->equipment_image = strpos($m->equipment_image,"http")>0?$m->equipment_image:   base_url('api/' . $m->equipment_image);
                }
            }       
            $projects[$k]->project_equipments = $project_Equipements;

            /* Get project employee based on project id*/
            $projectEmployees = $this->Project->getProjectEmployee($p->id,0,2);
            $project_Employees = array();
            if(!empty($projectEmployees))
            {
                foreach ($projectEmployees as $k => $m)
                {
                    $project_Employees[$k]->id =  $m->id;
                    $project_Employees[$k]->name = $m->name;
                    $project_Employees[$k]->image = strpos($m->image,"http")>0?$m->image:   base_url('api/' . $m->image);
                }
            }   
            $projects[$k]->project_employees = $project_Employees;

            /* Get project milestone */
            $projectMilestones = $this->Project->getByCriteria(array('project_id' => $project_id), 'checkmate_project_milestone');
            if(!empty($projectMilestones))
            {
                foreach ($projectMilestones as $k => $m)
                {
                    $projectMilestones[$k]->milestone_coordinates = explode(',', $m->milestone_coordinates);
                }
            }
            $projects[$k]->project_milestones = $projectMilestones;
        }

        $res['status'] = true;
        $res['response']['success']['projects'] = $projects;
        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/updateTask",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="updateTask",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="task_id",
     *           description="your task_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="milestone_id",
     *           description="your milestone_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_name",
     *           description="your task_name",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_start_date",
     *           description="your task_start_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_end_date",
     *           description="your task_end_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_description",
     *           description="your task_description",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_estimation",
     *           description="your task_estimation",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_team",
     *           description="your task_team",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_team_leader",
     *           description="your task_team_leader",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="task_equipment",
     *           description="your task_equipment",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function updateTask_post()
     {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }
        $task_id = $this->post('task_id');
        $milestone_id = $this->post('milestone_id');
        $taskName = $this->post('task_name');
        $milestone_id = $this->post('milestone_id');
        $startDate = $this->post('task_start_date');
        $start_date = date("Y-m-d H:i:s", strtotime($startDate));
        $endDate = $this->post('task_end_date');
        $end_date = date("Y-m-d H:i:s", strtotime($endDate));
        $taskName = $this->post('task_name');
        $user_id = $this->post('user_id');
        $project_id = $this->post('project_id');
        $task_description = $this->post('task_description');
        $task_estimation = $this->post('task_estimation');
        $task_start_date = $start_date;
        $task_end_date = $end_date;
        $task_team = $this->post('task_team');
        $task_team_leader = $this->post('task_team_leader');
        $task_equipment = $this->post('task_equipment');
        if(empty($taskName))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide task name';
            $this->response($res);
        }
        $user_id = $this->post('user_id');
        $data = array(
            'user_id'=>$user_id,
            'project_id'=>$project_id,
            'milestone_id'=>$milestone_id,
            'task_name'=>$taskName,
            'task_description'=>$task_description,
            'task_estimation'=>$task_estimation,
            'task_start_date'=>$task_start_date,
            'task_end_date'=>$task_end_date,
            'task_team'=>$task_team,
            'task_team_leader'=>$task_team_leader,
            'task_equipment'=>$task_equipment,
            'task_parent'=>0,
            'date_updated'=>now()
        );
        $criteria = array('user_id'=>$user_id, 'id'=>$task_id);
        $rs = $this->Project->updateByCriteria($criteria, 'ref_task', $data);

        if(!$rs)
        {
            $res['status'] = false;
            $res['response']['error'] = 'Unable to update task, please try again later';
            $this->response($res);
        }
        else
        {
            $res['status'] = true;
            $res['response']['success'] = 'Task updated successfully.';
            $this->response($res);
        }


        $this->response($res);
    }
    /**
     * @OA\Post(path="/ProjectController/updateSubtask",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="updateSubtask",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     
     @OA\Parameter(
     *           name="task_id",
     *           description="your task_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_id",
     *           description="your subtask_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_name",
     *           description="your subtask_name",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_start_date",
     *           description="your subtask_start_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_end_date",
     *           description="your subtask_end_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_id",
     *           description="your project_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_estimate",
     *           description="your subtask_estimate",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_description",
     *           description="your subtask_description",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_team",
     *           description="your subtask_team",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_team_leader",
     *           description="your subtask_team_leader",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="subtask_equipment",
     *           description="your subtask_equipment",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function updateSubtask_post()
     {
        # Step 1 validate token
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $task_id = $this->post('task_id');
        $subtask_id = $this->post('subtask_id');
        $subTaskName = $this->post('subtask_name');
        $startDate = $this->post('subtask_start_date');
        $start_date = date("Y-m-d H:i:s", strtotime($startDate));
        $endDate = $this->post('subtask_end_date');
        $end_date = date("Y-m-d H:i:s", strtotime($endDate));
        $taskId = $this->post('task_id');
        $subTaskName = $this->post('subtask_name');
        $project_id = $this->post('project_id');
        $subtask_estimate = $this->post('subtask_estimate');
        $subtask_description = $this->post('subtask_description');
        $subtask_team = $this->post('subtask_team');
        $subtask_team_leader = $this->post('subtask_team_leader');
        $subtask_equipment = $this->post('subtask_equipment');
        if(empty($subTaskName))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide subtask name';
            $this->response($res);
        }
        $user_id = $this->post('user_id');

        $data = array(
            'project_id'=>$project_id,
            'task_id'=>$taskId,
            'subtask_name'=>$subTaskName,
            'subtask_start_date'=>$start_date,
            'subtask_end_date'=>$end_date,
            'subtask_estimate'=>$subtask_estimate,
            'subtask_description'=>$subtask_description,
            'subtask_team'=>$subtask_team,
            'subtask_team_leader'=>$subtask_team_leader,
            'subtask_equipment'=>$subtask_equipment,
            'date_updated'=>now()
        );
        $criteria = array('project_id'=>$project_id, 'id'=>$subtask_id);
        $rs = $this->Project->updateByCriteria($criteria, 'ref_sub_task', $data);

        if(!$rs)
        {
            $res['status'] = false;
            $res['response']['error'] = 'Unable to update subtask, please try again later';
            $this->response($res);
        }
        else
        {
            $res['status'] = true;
            $res['response']['success'] = 'Subtask updated successfully.';
            $this->response($res);
        }
    }
    /**
     * @OA\Post(path="/ProjectController/projectUsers",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectUsers",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),     
     @OA\Parameter(
     *           name="search_text",
     *           description="your search_text",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function projectUsers_post()
     {
        # Step 1 validate token
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $project_id = $this->post('project_id');
        $search_text = $this->post('search_text');

        $sql = "SELECT ct.name, ct.email, ct.image, ct.phone FROM checkmate_team ct JOIN checkmate_project_team cpt ON cpt.employee_id = ct.id WHERE cpt.project_id = $project_id";
        if(!empty($search_text))
        {
            $sql .=" and ct.name LIKE '%$search_text%'";
        }

        $rs = $this->db->query($sql)->result_array();
        if(empty($rs))
        {
            $res['status'] = false;
            $res['response']['error'] = 'No result found !!!';
            $this->response($res);
        }
        else {
            $res['status'] = true;
            $res['response']['success']['result'] = $rs;
            $this->response($res);
        }

    }

    /**
     * @OA\Post(path="/ProjectController/projectEquipments",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="projectEquipments",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="search_text",
     *           description="your search_text",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function projectEquipments_post()
     {
        # Step 1 validate token
        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        $project_id = $this->post('project_id');
        $search_text = $this->post('search_text');

        $sql = "SELECT re.equipment_name, re.equipment_short_desc, re.equipment_long_desc, re.equipment_image FROM checkmate_equipment re JOIN checkmate_project_equipment cpe ON cpe.equipment_id = re.id WHERE cpe.project_id = $project_id";
        if(!empty($search_text))
        {
            $sql .=" and re.equipment_name LIKE '%$search_text%'";
        }

        $rs = $this->db->query($sql)->result_array();
        if(empty($rs))
        {
            $res['status'] = false;
            $res['response']['error'] = 'No result found !!!';
            $this->response($res);
        }
        else {
            $res['status'] = true;
            $res['response']['success']['result'] = $rs;
            $this->response($res);
        }

    }

    public function validate_access_token($access_token, $user_id)
    {
        $response = array();

        if (empty($access_token)) {
            $response['status'] = false;
            $response['response']['error'] = 'Token Missing.';
            $this->response($response);
        }

        if (empty($user_id)) {
            $response['status'] = false;
            $response['response']['error'] = 'User Id Missing.';
            $this->response($response);
        }
        $validateToken = AUTHORIZATION::validateToken($access_token);
        $validateTimestamp = AUTHORIZATION::validateTimestamp($access_token);
       // $result = $this->db->where(['user_id' => $user_id, 'access_token' => $access_token])->from('checkmate_user_session')->get()->result_array();

        if ($validateToken == false) {

            return false;
        }

        return true;

    }
    /**
     * @OA\Post(path="/ProjectController/addPayment",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="addPayment",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="wage",
     *           description="your wage",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="salary",
     *           description="your salary",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="hourly_rate",
     *           description="your hourly_rate",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="day_rate",
     *           description="your day_rate",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function addPayment_post()
     {
        $response = array();
        $wage = $this->post('wage');
        $salary = $this->post('salary');
        $hourly_rate = $this->post('hourly_rate');
        $day_rate = $this->post('day_rate');
        if (empty($this->post('wage'))) {
            $wage = 0;
        }
        if (empty($this->post('salary'))) {
            $salary = 0;
        }
        if (empty($this->post('hourly_rate'))) {
            $hourly_rate = 0;
        }
        if (empty($this->post('day_rate'))) {
         $day_rate = 0;
     }
     if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
        $response['status'] = false;
        $response['response']['error'] = 'Invalid access token.';
        $this->response($response);
    }


    try
    {

        $data = array(
            'wage' => $wage,
            'salary' => $salary,
            'hourly_rate' => $hourly_rate,
            'day_rate' => $day_rate
        );

        $result = $this->Project->addPayment($data);

        if (intval($result)>0) {
            $res['status'] = true;
            $res['response']['success']['message'] = "a Payment added successfully";
            $res['response']['success']['payment_id'] = $result;
        } else {
            $res['status'] = false;
            $res['response']['error']['message'] = "OOPS !!! Unable to update Payment, please try again";
        }


    } catch (Exception $e) {
        $res['status'] = false;
        $res['response']['error']['message'] = $e->getMessage();
    }
    $this->response($res);  
    
}    

    /**
     * @OA\Post(path="/ProjectController/ProfileAvatar",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="ProfileAvatar",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),     
     *      @OA\Parameter(
     *           name="image",
     *           description="your image",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         )    ,
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
    public function ProfileAvatar_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }


        # validate if profile id is correct

        $profileData = $this->db->where('id', $this->post('user_id'))->from('checkmate_user')->get()->result_array();

        if (empty($profileData)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Invalid profile id, please check and try again";
            $this->response($res);
        }
/*
        $profile = $this->Login->getUserData($this->post('user_id'));
        if (empty($profile)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
            $this->response($res);
        }
*/

        $this->Login->updateUserData($this->post('user_id'),array('avatar'=>base_url('api/' . $this->upload('avatars',$this->post('image')))));
        $profile = $this->Login->getUserData($this->post('user_id'));
        if (empty($profile)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
            $this->response($res);
        }
        $res['status'] = true;
        $res['response']['success']['avatar'] = base_url('api/' . $this->upload('avatars',$this->post('image')));

        $this->response($res);
    }
    /**
     * @OA\Get(path="/ProjectController/ProfileAvatar",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="ProfileAvatar_",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
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
    public function ProfileAvatar_get()
    {
        $res = array();

        if (!$this->validate_access_token($this->get('access_token'), $this->get('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->get('user_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }


        # validate if profile id is correct

        $profileData = $this->db->where('id', $this->get('user_id'))->from('checkmate_user')->get()->result_array();

        if (empty($profileData)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Invalid profile id, please check and try again";
            $this->response($res);
        }
        $profile = $this->Rabbit->getUserData($this->post('user_id'));
        if (empty($profile)) {
            $res['status'] = false;
            $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
            $this->response($res);
        }
        $res['status'] = true;
        $res['response']['success']['avatar'] = count( $profile)>0? base_url('api/' . $profile[0]['avatar']):'';

        $this->response($res);
    }

/**
     * @OA\Post(path="/ProjectController/editProject",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="editProject",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     @OA\Parameter(
     *           name="project_name",
     *           description="your project_name",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_estimate",
     *           description="your project_estimate",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_no",
     *           description="your project_no",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="site_contractor_id",
     *           description="your site_contractor_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="client_id",
     *           description="your client_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_address",
     *           description="your project_address",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_city",
     *           description="your project_city",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_lat",
     *           description="your project_lat",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_lng",
     *           description="your project_lng",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_status",
     *           description="your project_status",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_da_working_hrs",
     *           description="your project_da_working_hrs",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_director_id",
     *           description="your project_director_id",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_manager_id",
     *           description="your project_manager_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_id",
     *           description="your project_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_start_date",
     *           description="your project_start_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_completion_date",
     *           description="your project_completion_date",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_total_months",
     *           description="your project_total_months",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_site_number",
     *           description="your project_site_number",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_site_manager_id",
     *           description="your project_site_manager_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_foreman_id",
     *           description="your project_foreman_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_site_eng_id",
     *           description="your project_site_eng_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_rep_id",
     *           description="your project_rep_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     *  @OA\Parameter(
     *           name="project_contract_administrator_id",
     *           description="your project_contract_administrator_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_brief_scope",
     *           description="your project_brief_scope",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     @OA\Parameter(
     *           name="project_scope",
     *           description="your project_scope",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function editProject_post()
     {
        $res = array();
        $res['status'] = false;
        $error = 0;

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        //$project_no = "PRO".date('Y');
        $user_id = $this->post('user_id');
        $access_token = $this->post('access_token');
        $project_name = $this->post('project_name');
        $project_estimate = $this->post('project_estimate');
        $project_no = $this->post('project_no');
        $site_contractor_id = $this->post('site_contractor_id');
        $client_id = $this->post('client_id');
        $project_address = $this->post('project_address');
        $project_city = $this->post('project_city');
        $project_lat = $this->post('project_lat');
        $project_lng = $this->post('project_lng');
        $project_status = $this->post('project_status');
        $project_da_working_hrs = $this->post('project_da_working_hrs');
        $project_director_id = $this->post('project_director_id');
        $project_manager_id = $this->post('project_manager_id');
        $project_id = $this->post('project_id');
        $project_start_date = $this->post('project_start_date');
        $project_completion_date = $this->post('project_completion_date');
        $project_total_months = $this->post('project_total_months');
        $project_site_number = $this->post('project_site_number');
        $project_site_manager_id = $this->post('project_site_manager_id');
        $project_foreman_id = $this->post('project_foreman_id');
        $project_site_eng_id = $this->post('project_site_eng_id');
        $project_rep_id = $this->post('project_rep_id');
        $project_contract_administrator_id = $this->post('project_contract_administrator_id');
        $project_brief_scope = $this->post('project_brief_scope');
        $project_scope = $this->post('project_scope');

        if (isset($project_brief_scope) && empty($project_brief_scope)) {
            $error = 1;
            $res['response']['error']['project_brief_scope'] = 'Please enter project brief scope.';
        }
        if (isset($project_scope) && empty($project_scope)) {
            $error = 1;
            $res['response']['error']['project_scope'] = 'Please enter project scope.';
        }
        if (isset($project_start_date) && empty($project_start_date)) {
            $error = 1;
            $res['response']['error']['project_start_date'] = 'Please select start date.';
        }
        if (isset($project_completion_date) && empty($project_completion_date)) {
            $error = 1;
            $res['response']['error']['project_completion_date'] = 'Please select completion date.';
        }
        if (isset($project_total_months) && empty($project_total_months)) {
            $error = 1;
            $res['response']['error']['project_total_months'] = 'Please select total months.';
        }
        if (isset($project_site_manager_id) && (empty($project_site_manager_id) || $project_site_manager_id == 0)) {
            $error = 1;
            $res['response']['error']['project_site_manager_id'] = 'Please select site manager name.';
        }
        if (isset($project_foreman_id) && (empty($project_foreman_id) || $project_foreman_id == 0)) {
            $error = 1;
            $res['response']['error']['project_foreman_id'] = 'Please select foreman.';
        }
        if (isset($project_site_eng_id) && (empty($project_site_eng_id) || $project_site_eng_id == 0)) {
            $error = 1;
            $res['response']['error']['project_site_eng_id'] = 'Please select site engineer.';
        }
        if (isset($project_rep_id) && (empty($project_rep_id) || $project_rep_id == 0)) {
            $error = 1;
            $res['response']['error']['project_rep_id'] = 'Please select health and safety representative.';
        }
        if (isset($project_contract_administrator_id) && (empty($project_contract_administrator_id) || $project_contract_administrator_id == 0)) {
            $error = 1;
            $res['response']['error']['project_contract_administrator_id'] = 'Please select contract administrator.';
        }
        if (isset($project_name) && empty($project_name)) {
            $error = 1;
            $res['response']['error']['project_name'] = 'Please enter project name.';
        }
        if (isset($project_estimate) && empty($project_estimate)) {
            $error = 1;
            $res['response']['error']['project_estimate'] = 'Please enter project estimate.';
        }
        if (isset($project_no) && empty($project_no)) {
            $error = 1;
            $res['response']['error']['project_no'] = 'Please enter project no.';
        }
        if (isset($site_contractor_id) && (empty($site_contractor_id) || $site_contractor_id == 0)) {
            $error = 1;
            $res['response']['error']['site_contractor_id'] = 'Please enter site contractor.';
        }
        if (isset($client_id) && (empty($client_id) || $client_id == 0)) {
            $error = 1;
            $res['response']['error']['project_name'] = 'Please select client name.';
        }
        if (isset($project_address) && empty($project_address)) {
            $error = 1;
            $res['response']['error']['project_address'] = 'Please enter project address.';
        }
        if (isset($project_lat) && empty($project_lat)) {
            $error = 1;
            $res['response']['error']['project_address'] = 'Please provide project lat.';
        }
        if (isset($project_lng) && empty($project_lng)) {
            $error = 1;
            $res['response']['error']['project_address'] = 'Please provide project lng.';
        }
        if (isset($project_status) && (empty($project_status) || $project_status == 0)) {
            $error = 1;
            $res['response']['error']['project_name'] = 'Please select project status.';
        }
        if (isset($project_da_working_hrs) && empty($project_da_working_hrs)) {
            $error = 1;
            $res['response']['error']['project_da_working_hrs'] = 'Please enter project DA working hrs.';
        }
        if (isset($project_director_id) && (empty($project_director_id) || $project_director_id == 0)) {
            $error = 1;
            $res['response']['error']['project_director_id'] = 'Please select project director.';
        }
        if (isset($project_manager_id) && (empty($project_manager_id) || $project_manager_id == 0)) {
            $error = 1;
            $res['response']['error']['project_manager_id'] = 'Please select project manager.';
        }

        if ($error == 1) {

            $res['status'] = false;

        } else {
            $now = new DateTime();
            $now = $now->format('Y-m-d H:i:s');
            $pro_step_one_data = array(
                'project_name' => $project_name,
                'project_estimate' => $project_estimate,
                'project_no' => $project_no,
                'site_contractor_id' => $site_contractor_id,
                'client_id' => $client_id,
                'project_address' => $project_address,
                'project_city' => $project_city,
                'project_lat' => $project_lat,
                'project_lng' => $project_lng,
                'project_status' => $project_status,
                'project_da_working_hrs' => $project_da_working_hrs,
                'project_director_id' => $project_director_id,
                'project_manager_id' => $project_manager_id,
                'project_start_date' => $project_start_date,
                'project_completion_date' => $project_completion_date,
                'project_total_months' => $project_total_months,
                'project_site_number' => $project_site_number,
                'project_site_manager_id' => $project_site_manager_id,
                'project_foreman_id' => $project_foreman_id,
                'project_site_eng_id' => $project_site_eng_id,
                'project_rep_id' => $project_rep_id,
                'project_contract_administrator_id' => $project_contract_administrator_id,
                'project_brief_scope' => $project_brief_scope,
                'project_scope' => $project_scope,
            );

            try
            {
                $lastId = $this->Project->updateProject($project_id,$pro_step_one_data);

                if ($lastId) {
                    $res['status'] = true;
                    $res['response']['success']['message'] = "Project created successfully";
                    $res['response']['success']['project_id'] = $lastId;
                } else {
                    $res['status'] = false;
                    $res['response']['error']['message'] = "OOPS !!! Unable to add new project, please try again";
                }
            } catch (Exception $e) {
                $res['status'] = false;
                $res['response']['error']['message'] = $e->getMessage();
            }

        }
        $this->response($res);
    }
     /**
     * @OA\Post(path="/ProjectController/deleteProject",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="deleteProject",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     *  @OA\Parameter(
     *           name="project_id",
     *           description="your project_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="status",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function deleteProject_post()
     {
      $res = array();
      $res['status'] = false;
      $error = 0;

      if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
        $res['status'] = false;
        $res['response']['error'] = 'Invalid access token.';
        $this->response($res);
    }

        //$project_no = "PRO".date('Y');
    $user_id = $this->post('user_id');
    $access_token = $this->post('access_token');

    $project_id = $this->post('project_id');


    if (isset($project_id) && (empty($project_id) || $project_id == 0)) {
        $error = 1;
        $res['response']['error']['project_id'] = 'Please select project id.';
    }

    if ($error == 1) {

        $res['status'] = false;

    } else {


     $pro_step_one_data = array(
      'project_status' => 0,
      'status' => 0
  );

     try
     {
        $lastId = $this->Project->updateProject($project_id,$pro_step_one_data);

        if ($lastId) {
            $res['status'] = true;
            $res['response']['success']['message'] = "Project delete successfully";
        } else {
            $res['status'] = false;
            $res['response']['error']['message'] = "OOPS !!! Unable to add new project, please try again";
        }
    } catch (Exception $e) {
        $res['status'] = false;
        $res['response']['error']['message'] = $e->getMessage();
    }

}
$this->response($res);
}
    /**
     * @OA\Post(path="/ProjectController/updateProjectStatus",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="updateProjectStatus",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     *  @OA\Parameter(
     *           name="project_id",
     *           description="your project_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     )
     *         ),
     @OA\Parameter(
     *           name="status",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function updateProjectStatus_post()
     {
      $res = array();
      $res['status'] = false;
      $error = 0;

      if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
        $res['status'] = false;
        $res['response']['error'] = 'Invalid access token.';
        $this->response($res);
    }

        //$project_no = "PRO".date('Y');
    $user_id = $this->post('user_id');
    $access_token = $this->post('access_token');

    $project_id = $this->post('project_id');
    $status = $this->post('status');


    if (isset($project_id) && (empty($project_id) || $project_id == 0)) {
        $error = 1;
        $res['response']['error']['project_id'] = 'Please select project id.';
    }

    if ($error == 1) {

        $res['status'] = false;

    } else {
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');
        $pro_step_one_data = array(
          'project_status' => $status,
          'status' => $status,
          'updated_at'=>now()
      );

        try
        {
            $lastId = $this->Project->updateProject($project_id,$pro_step_one_data);

            if ($lastId) {
                $res['status'] = true;
                $res['response']['success']['message'] = "Project created successfully";
                $res['response']['success']['project_id'] = $lastId;
            } else {
                $res['status'] = false;
                $res['response']['error']['message'] = "OOPS !!! Unable to add new project, please try again";
            }
        } catch (Exception $e) {
            $res['status'] = false;
            $res['response']['error']['message'] = $e->getMessage();
        }

    }
    $this->response($res);
}
    /**
     * @OA\Post(path="/ProjectController/updateMilestoneStatus",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="updateMilestoneStatus",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),   
     *  @OA\Parameter(
     *     name="projct_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),   
     @OA\Parameter(
     *           name="status",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *      @OA\Parameter(
     *           name="milestone_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */  
     public function updateMilestoneStatus_post()
     {
         $milestone_id = $this->post('milestone_id');
         $status = $this->post('status');
         $user_id  = $this->post('user_id');
         $project_id  = $this->post('project_id');
				 //1 Work in progress
				 // 2 Hold
				 // 3 Completed
				 $status_arr[1] = 'Work In Progress';
				 $status_arr[2] = 'Hold';
				 $status_arr[3] = 'Completed';

        # Step 1 validate token
        #if (!$this->validate_access_token($access_token, $user_id)) {
       #     $res['status'] = false;
        #    $res['response']['error'] = 'Invalid access token.';
         #   $this->response($res);
        #}

         $response = array();
        //  if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
        //     $response['status'] = false;
        //     $response['response']['error'] = 'Invalid access token.';
        //     $this->response($response);
        // }

        if(empty($status))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please send status.';
            $this->response($res);
        }

        if(empty($milestone_id))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide milestone id.';
            $this->response($res);
        } 

        if(empty($project_id))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide Project id.';
            $this->response($res);
        } 

        if(empty($user_id))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Please provide user id.';
            $this->response($res);
        }


        # Validate if project is valid and belongs to the user

        $rs = $this->Project->getMilestone_project($project_id, $milestone_id);

        if(empty($rs))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Milestone can be set for self projects only'  ;
            $this->response($res);
        }


        /* Validation done, now let's store the information*/
        $data = array(
            'status'=>$status,
            'updated_at'=>now()

        );
        $milestoneId = $this->Project->updateMilestone($milestone_id,$data);

        if($milestoneId)
        {  
            $project_data = $this->db->query("SELECT user_id, project_name FROM checkmate_project  WHERE id = $project_id")->row();
						// Team Lead 
						// Admin is project creator
            
						$subbie_info = $this->db->where('id', $project_data->user_id)->from('checkmate_user')->get()->result_array();
						if( $project_data->user_id != $user_id) {
							$device_token =  $this->db->where('user_id', $project_data->user_id)->from('checkmate_user_device')->get()->result_array();
						}else{
							$team_lead = $this->db->where('id', $milestone_id)->from('checkmate_project_milestone')->get()->result_array();
							
							$device_token =  $this->db->where('user_id',$team_lead[0]['teamlead_id'])->from('checkmate_user_device')->get()->result_array();
						}
						
						$milestone_info = $this->db->where('id', $milestoneId)->from('checkmate_project_milestone')->get()->result_array();
                        $tokens = array();
                        for($inc = 0; $inc <count($device_token); $inc++) {
                            $tokens[] = $device_token[$inc]['device_token'];
                        }
                        $title = 'Milestone status update';
                        $message = $subbie_info[0]['name'].' has changed '.$milestone_info[0]['project_milestone_name'].' status to '.$status_arr[$status];
                        //end notification
                        $this->Login->send_ios_notification($tokens, $title, $message);
            $res['status'] = true;
            $res['response']['success'] = 'New milestone added successfully';
            $this->response($res);
        }

        $res['status'] = false;
        $res['response']['error'] = 'Something went wrong, please try again later';
        $this->response($res);
    }
     /**
     * @OA\Post(path="/ProjectController/WorkingHours",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="WorkingHours",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     *        @OA\Parameter(
     *           name="time_from",
     *           description="your time_from hA",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *        @OA\Parameter(
     *           name="time_to",
     *           description="your time_to hA",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
              @OA\Parameter(
     *           name="date_from",
     *           description="your date_from days of week[mon-sun]",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *        @OA\Parameter(
     *           name="date_to",
     *           description="your date_to days of week[mon-sun]",
     *           required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *         ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(response=400, description="")
     * )
     */      
              public function WorkingHours_post()
              {
                 $time_from = date("Y-m-d H:i:s", strtotime($this->post('time_from')));
                 $time_to =   date("Y-m-d H:i:s", strtotime($this->post('time_to')));
                 $date_from =   date("Y-m-d H:i:s", strtotime($this->post('date_from')));
                 $date_to =   date("Y-m-d H:i:s", strtotime($this->post('date_to')));
                 $res = array();

                 if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
                    $res['status'] = false;
                    $res['response']['error'] = 'Invalid access token.';
                    $this->response($res);
                }

        # Check if user exists
                if ($this->post('user_id') == "") {
                    $res['status'] = false;
                    $res['response']['error']['message'] = "Please login first";
                    $this->response($res);
                }


        # validate if profile id is correct

                $data = array(
                'time_from' => $time_from,//getCurrDateTime($time_from,'Y-m-d hA'),
                'time_to' => $time_to,//getCurrDateTime($time_to,'Y-m-d hA'),
                'date_from' => $date_from,//getCurrDateTime($date_from,'Y-m-d H:i:s'),
                'date_to' => $date_to,//getCurrDateTime($date_to,'Y-m-d H:i:s'),
                'status' => 1,
                'created_at' => getCurrDateTime(),
                'updated_at' => getCurrDateTime()
            );

                $WorkingHours_id = $this->Rabbit->insertWorkinghours($data);
                if (empty($WorkingHours_id)) {
                    $res['status'] = false;
                    $res['response']['error']['message'] = "Something went wrong while adding, please try again later";
                    $this->response($res);
                }


                $res['status'] = true;
                $res['response']['success']['result'] = array('workinghours_id'=>$WorkingHours_id,'time_from' => $time_from,
                    'time_to' => $time_to,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'status' => 1);

                $this->response($res);
            }

    /**
     * @OA\Get(path="/ProjectController/WorkingHoursList",
     *   tags={"project"},
     *   summary="",
     *   description="",
     *   operationId="WorkingHoursList",
     *   @OA\Parameter(
     *     name="access_token",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format = "int32"
     *     ),
     *     description="",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *      @OA\JsonContent(ref="#/components/schemas/WorkingHoursList"),
     *   ),
     *   @OA\Response(response=400, description="")
     * )
      * @OA\Schema(
 *   schema="WorkingHoursList",
 *   type="integer",
 *   format="int64",
 *   description="The unique identifier of a product in our catalog"
 * )
     */  
    public function WorkingHoursList_get()
    {
        $res = array();


        // if (!$this->validate_access_token($this->get('access_token'), $this->get('user_id'))) {
        //     $res['status'] = false;
        //     $res['response']['error'] = 'Invalid access token.';
        //     $this->response($res);
        // }

        # Check if user exists
        if ($this->get('user_id') == "") {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }
        // optional  project_id
        // if ($this->get('project_id') == "") {
        //     $res['status'] = false;
        //     $res['response']['error']['message'] = "Please provide project id";
        //     $this->response($res);
        // }

        //$projectID = $this->get('project_id');
        $userID = $this->get('user_id');


        # validate if profile id is correct
        if($userID>0){// XXX
        $list = $this->db->from('ref_working_hours rwh')
                        ->where('rwh.id NOT IN ( select rwhp.ref_working_hours_id from removed_working_hours_project rwhp where rwhp.user_id = '.$userID.' )')
                        ->get()->result_array();
                    }else{

                         $list = $this->db->from('ref_working_hours rwh')
                        ->get()->result_array();
                        // all ->where('rwh.status',1)

                    }


        # validate if profile id is correct

        // $list = $this->db->from('ref_working_hours')->get()->result_array();

        // if (empty($list)) {
        //     $res['status'] = false;
        //     $res['response']['error']['message'] = 'No result found !!!';
        //     $this->response($res);
        // }

        $res['status'] = true;
        $res['response']['success']['result'] =$list;

        $this->response($res);
    }
    
    public function sendmail($type,$subject,$toEmail,$data)
    {

        $this->load->library('parser');
        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
        $this->email->set_header('Content-type', 'text/html');

        $this->email->from('webmaster@checkmate.com', 'checkmate.com');

        $this->email->to($toEmail);  
        $this->email->subject($subject); 

        $body = $this->parser->parse('emails/'.$type.'.php',$data,TRUE);
        $this->email->message($body);   
        $this->email->send();
    }

    public function WorkingHoursListRemove_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        if ($this->post('project_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please provide project id";
            $this->response($res);
        }

        if ($this->post('ref_working_hours_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please provide da working hour id";
            $this->response($res);
        }
        # validate if profile id is correct
        
        $insertData = array();
        $insertData['user_id'] = $this->post('user_id');
        $insertData['project_id'] = $this->post('project_id');
        $insertData['ref_working_hours_id'] = $this->post('ref_working_hours_id');
        $insertData['created_at'] = getCurrDateTime();
        $insertData['updated_at'] = getCurrDateTime();

        
        //  validate record if exist.
         $this->db->select("*");
            $this->db->from('ref_working_hours');
         $this->db->where('id',$insertData['ref_working_hours_id']);
       //  $this->db->where('status',1);
          $record= $this->db->get()->row();
          //print_r($record); die;
          
         // echo '==='.$count; die; 
          
   // $insert = $this->db->insert('removed_working_hours_project',$insertData);

        // Update in self Table 
          if(count($record)>0){
              if($record->status==1){

                 $arr=array();$arr['status']=0;
         $this->db->where('id', $insertData['ref_working_hours_id']);
        $this->db->update('ref_working_hours', $arr);
        /// insert 
        $insert = $this->db->insert('removed_working_hours_project',$insertData);
        $res['status'] = true;
            $res['response']['success']['result'] = 'Record removed for this project successfully !!!';

              }elseif($record->status==0){ //# status==0 - reomved from list
                 $res['status'] = false;
            $res['response']['error']['message'] = 'Working hours_id,already updated!!!';



              }
           


          }elseif(count($record)==0){

             $res['status'] = false;
            $res['response']['error']['message'] = 'Working hours_id wrong,Please try after sometime !!!';

          }else{
             $res['status'] = false;
            $res['response']['error']['message'] = 'xSomething went wrong !!! Please try after sometime !!!';

          }
         
        //$update_status=$this->db->update('status',$insertData);


        // $deleteRecord = $this->db->delete('ref_working_hours',array('id'=>$refWorkingHoursID));

        

        //////////////////
        $this->response($res);
    }

    public function createMobileStaticPlant_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $insertData = $_POST;

        unset($insertData['access_token']);
        $insertData['created_at'] = getCurrDateTime();

        $this->db->insert('checkmate_mobile_static_plants',$insertData);

        $res['status'] = true;
        $res['response']['success']['result'] = 'Mobile Static Plan Created Successfully !!!';
        $this->response($res);
    }

    public function getMobileStaticPlant_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $userID = $this->post('user_id');
        $projectID = $this->post('project_id');
        $mileStoneID = $this->post('milestone_id');

        $this->db->select('*');
        $this->db->from('checkmate_mobile_static_plants');
        if(!empty($projectID))
        {
            $this->db->where('project_id',$projectID);    
        }
        if(!empty($mileStoneID))
        {
            $this->db->where('milestone_id',$mileStoneID);    
        }

        $result = $this->db->get()->result_array();
        
        $res['status'] = true;
        $res['response']['success']['result'] = $result;
        $this->response($res);
    }

    public function createMobileEquipment_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $insertData = $_POST;

        unset($insertData['access_token']);
        $insertData['created_at'] = getCurrDateTime();

        $this->db->insert('checkmate_mobile_equipments',$insertData);

        $res['status'] = true;
        $res['response']['success']['result'] = 'Mobile Equipment Created Successfully !!!';
        $this->response($res);
    }

    public function getMobileEquipment_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $userID = $this->post('user_id');
        $projectID = $this->post('project_id');
        $mileStoneID = $this->post('milestone_id');
        $checkmate_mobile_static_plants_id = $this->post('checkmate_mobile_static_plants_id');

        $this->db->select('*');
        $this->db->from('checkmate_mobile_equipments');
        if(!empty($projectID))
        {
            $this->db->where('project_id',$projectID);    
        }
        if(!empty($mileStoneID))
        {
            $this->db->where('milestone_id',$mileStoneID);    
        }
        if(!empty($checkmate_mobile_static_plants_id))
        {
            $this->db->where('checkmate_mobile_static_plants_id',$checkmate_mobile_static_plants_id);    
        }

        $result = $this->db->get()->result_array();
        
        $res['status'] = true;
        $res['response']['success']['result'] = $result;
        $this->response($res);
    }

    public function getProjectUsersList_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $userType = $this->input->post('user_type');

        $this->db->select('cu.*,cpr.project_id');
        $this->db->from('checkmate_project_resources cpr');
        $this->db->join('checkmate_user cu','cu.id = cpr.user_id','inner');
        if(!empty($userType))
        {
            $this->db->where('cu.user_type',$userType);
        }
        $res = $this->db->get()->result_array();
        $res['status'] = true;
        $res['response']['success']['result'] = $result;
        $this->response($res);
    }

    public function createElectricalEquipments_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $insertData = $_POST;

        unset($insertData['access_token']);
        $insertData['created_at'] = getCurrDateTime();

        $this->db->insert('checkmate_electrical_equipments',$insertData);

        $res['status'] = true;
        $res['response']['success']['result'] = 'Electrical Equipment Created Successfully !!!';
        $this->response($res);
    }

    public function getElectricalEquipment_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $userID = $this->post('user_id');
        $projectID = $this->post('project_id');
        $mileStoneID = $this->post('milestone_id');

        $this->db->select('*');
        $this->db->from('checkmate_mobile_static_plants');
        if(!empty($projectID))
        {
            $this->db->where('project_id',$projectID);    
        }
        if(!empty($mileStoneID))
        {
            $this->db->where('milestone_id',$mileStoneID);    
        }

        $result = $this->db->get()->result_array();
        
        $res['status'] = true;
        $res['response']['success']['result'] = $result;
        $this->response($res);
    }

    public function createFireEquipments_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $insertData = $_POST;

        unset($insertData['access_token']);
        $insertData['created_at'] = getCurrDateTime();

        $this->db->insert('checkmate_fire_equipments',$insertData);

        $res['status'] = true;
        $res['response']['success']['result'] = 'Fire Equipment Created Successfully !!!';
        $this->response($res);
    }

    public function getFireEquipment_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $userID = $this->post('user_id');
        $projectID = $this->post('project_id');
        $mileStoneID = $this->post('milestone_id');

        $this->db->select('*');
        $this->db->from('checkmate_fire_equipments');
        if(!empty($projectID))
        {
            $this->db->where('project_id',$projectID);    
        }
        if(!empty($mileStoneID))
        {
            $this->db->where('milestone_id',$mileStoneID);    
        }

        $result = $this->db->get()->result_array();
        
        $res['status'] = true;
        $res['response']['success']['result'] = $result;
        $this->response($res);
    }

    public function createPPE_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $insertData = $_POST;

        unset($insertData['access_token']);
        $insertData['created_at'] = getCurrDateTime();

        $this->db->insert('checkmate_ppe_equipments',$insertData);

        $res['status'] = true;
        $res['response']['success']['result'] = 'PPE Created Successfully !!!';
        $this->response($res);
    }

    public function getPPE_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $userID = $this->post('user_id');
        $projectID = $this->post('project_id');
        $mileStoneID = $this->post('milestone_id');

        $this->db->select('*');
        $this->db->from('checkmate_ppe_equipments');
        if(!empty($projectID))
        {
            $this->db->where('project_id',$projectID);    
        }
        if(!empty($mileStoneID))
        {
            $this->db->where('milestone_id',$mileStoneID);    
        }

        $result = $this->db->get()->result_array();
        
        $res['status'] = true;
        $res['response']['success']['result'] = $result;
        $this->response($res);
    }

    public function createGear_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $insertData = $_POST;

        unset($insertData['access_token']);
        $insertData['created_at'] = getCurrDateTime();

        $this->db->insert('checkmate_gear_equipments',$insertData);

        $res['status'] = true;
        $res['response']['success']['result'] = 'Gear Created Successfully !!!';
        $this->response($res);
    }

    public function getGear_post()
    {
        $res = array();

        if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id')))
        {
            $res['status'] = false;
            $res['response']['error'] = 'Invalid access token.';
            $this->response($res);
        }

        # Check if user exists
        if ($this->post('user_id') == "")
        {
            $res['status'] = false;
            $res['response']['error']['message'] = "Please login first";
            $this->response($res);
        }

        $userID = $this->post('user_id');
        $projectID = $this->post('project_id');
        $mileStoneID = $this->post('milestone_id');

        $this->db->select('*');
        $this->db->from('checkmate_gear_equipments');
        if(!empty($projectID))
        {
            $this->db->where('project_id',$projectID);    
        }
        if(!empty($mileStoneID))
        {
            $this->db->where('milestone_id',$mileStoneID);    
        }

        $result = $this->db->get()->result_array();
        
        $res['status'] = true;
        $res['response']['success']['result'] = $result;
        $this->response($res);
    }

}