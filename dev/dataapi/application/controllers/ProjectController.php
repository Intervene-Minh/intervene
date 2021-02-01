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
 public function CreateTutoringSessions_post(){
   $input=[];
   $default_board='groupworld';
   $system_warnings=[];  // 
   // echo 'CreateTutoringSessions Created! ';  die; 
   $Tutoring_client=$this->post('Tutoring_client_id');   #for every  API .Drhomework123456, [{"key":"Tutoring_client_id","value":"Drhomework123456","description":""}] 
   $drhomework_ref_id=$this->post('drhomework_ref_id');  // ID - client side : TutrongMainID.


   // echo 'Tutoring_clientXX=='.$Tutoring_client;  //OK

   //  die; 


   /////////////////////////

            $input['Tutoring_client_id']     =$Tutoring_client;

            $input['drhomework_ref_id']     = $drhomework_ref_id;  //is_drhomework.

             $input['is_drhomework'] ='yes';

            $input['board_type'] =$default_board;
            $input['curr_active_board']=$default_board;  //'groupworld'; //Deafult board
            $input['type']='drhomework'; // Default

              $input['activity_start_time'] =$this->post('ses_start_time');
               $input['ses_start_time'] =$this->post('ses_start_time');

                $input['ses_end_time'] = $this->post('ses_end_time');  //'2019-09-04 00:00:00'; 

                $input['start_date'] =date('Y-m-d', strtotime($this->post('ses_start_time')) );
                $input['school_id'] =0; 
                $input['lesson_id']=9; 
                $input['quiz_id']=9;
                $input['teacher_id']=0; #NotUsedForDrhomework
               
                 

                $input['grade_id']=$this->post('grade_id');
                $input['created_date']=$this->post('ses_start_time');
                $input['district_id']=$this->post('district_id');   
                # Add special_notes notes 

                $input['special_notes']=($this->post('special_notes'))?$this->post('special_notes'):'NA';

                //Add Session 
                $result = $this->db->insert("int_schools_x_sessions_log",$input);

              $LastSessionId=  $this->db->insert_id();


             //echo 'ListId===='.$LastSessionId;   die; 


                #+add Session detail - info. 
                $arr_send=[];
                $arr_send['session_ref_id']=$LastSessionId; // Return ID from--intervne
                $arr_send['Tutoring_client_id']=$Tutoring_client;
                $arr_send['drhomework_ses_id']=$drhomework_ref_id;//API

                
                $arr_send['dr_parent_id']=$this->post('dr_parent_id'); # API

                
               //// $SessionDetailArr=[ 'Students'=>array() , 'Parent_detail'=>'Demo nameof Parent' ]; //List of Student.

                 $arr_send['session_stu_data']=$this->post('student_info');    // JSON strings.

                  $arr_send['dr_grade_id']=$this->post('dr_grade_id'); 
                   $arr_send['intervene_grade_id']=$this->post('grade_id'); 

                  // intervene_grade_id

                 //echo 'Info detail==';

                 //print_r($arr_send);  die; 




                  $SessionDetail = $this->db->insert("dr_tutoring_info",$arr_send);



                
               

                ########################################


              //print_r($input); die; 

            
            if($result==1){
                $success='Tutoring Sessions Created! ';
                $res['status'] = true;
                $res['response']['success']=$success;
                $res['response']['tutorgigs_class_id']=$LastSessionId;
                $this->response($res);

            }else{
                $system_warnings[]='error while creating Tutoring session! ';
            }

            

   ////////addToDb///////////
     if(!empty($system_warnings)){
        $res['status'] = false;
        $res['response']['error']= implode(', ', $system_warnings) ;
        $this->response($res);

     }


  
$Sql_sesion="   INSERT INTO int_schools_x_sessions_log SET Tutoring_client_id='$Tutoring_client',drhomework_ref_id='$drhomework_ref_id',board_type='groupworld',type='intervention',activity_start_time='2019-09-04 00:55',ses_start_time='2019-09-04 01:00',ses_end_time='2019-09-04 01:50',start_date='2019-09-04 00:00:00', school_id='28',lesson_id='11',quiz_id='4',grade_id='22', created_date='2019-09-04 10:43:31',teacher_id='1999',district_id='534' ";


   

 }





    /***
     @Intervne API -
     @ Validate later sites Key .
    **/
         public function DistrictsList_get()
    {
        $data= array();
        /////////Get data/////////////////////
         # check for list type 
    // echo '======='; die; 
        //$query = $this->db->query('checkmate_user');
          $this->db->select('d.id,d.name');
        $this->db->from('districts d');
        $data = $this->db->get()->result_array();
      // echo '=xxx=';
      //  print_r($res); die; 




       

//         $query = $this->db->get('districts');

//   $Array=array();
// foreach ($query->result() as $row)
// {
//        // echo  $row->name; die; 
//         $Array[]=$row->name;
// }

//   print_r($Array); die; 
      
        //////////////////

        if (empty($data)&&isset($data)) {

            $res['status'] = false;
            $res['response']['error']['message'] = "Invalid data! ";
            $this->response($res);
        }else{
         $res['status'] = true;
         $res['response']['success']['list'] =$data;
         $this->response($res);

        }



     


        
    }


  


 




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
        
        // if (!$this->validate_access_token($this->post('access_token'), $this->post('user_id'))) {
        //     $response['status'] = false;
        //     $response['response']['error'] = 'Invalid access token.';
        //     $this->response($response);
        // }


         echo 'User id=='.$this->post('user_id'); die;


        ///////////////////////

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







        public function UpdateSessionTime_post(){
        $input  = [];
        $system_warnings =   [];
        $arr_send = [];
        $sessionID=$this->post('session_id');
        $Tutoring_client_id=$this->post('Tutoring_client_id');
        $start_date            = date('Y-m-d', strtotime($this->post('ses_start_time')));

        $special_notes=($this->post('special_notes'))?$this->post('special_notes'):'NA';
     

$query=$this->db->query("UPDATE `int_schools_x_sessions_log` SET `ses_start_time` = '".$this->post('ses_start_time')."',`ses_end_time` = '".$this->post('ses_end_time')."',`start_date` = '".$start_date."',`grade_id` = '".$this->post('grade_id')."', `created_date` = '".$this->post('ses_start_time')."',`special_notes` = '".$special_notes."' WHERE 
`Tutoring_client_id`= '".$this->post('Tutoring_client_id')."' AND `type`='drhomework' 
AND `drhomework_ref_id`='".$sessionID."'" 

);
 
 $queryOne=$this->db->affected_rows();
        if($queryOne > 0 ){
            $query=$this->db->query("UPDATE `dr_tutoring_info` SET
            `session_stu_data` = '".$this->post('student_info')."'
            WHERE  `drhomework_ses_id` ='".$this->post('session_id')."'
            AND Tutoring_client_id='".$this->post('Tutoring_client_id')."'");
            $queryTwo= $this->db->affected_rows();

            } 
        if($queryTwo > 0){
        $success='Tutoring Sessions Updated! ';
        $res['status'] = true;
        $res['response']=$success;
        $this->response($res);
    } 
    else{

        $system_warnings[]='error while creating Tutoring session!';

        }

         if(!empty($system_warnings))
         {
            $res['status'] = false;
            $res['response']['error']= implode(', ', $system_warnings) ;
            $this->response($res);
         }
 }



        public function DeleteSession_post(){


        $system_warnings =   [];
        $sessionID=$this->post('session_id');
        $Tutoring_client_id=$this->post('Tutoring_client_id');

        $query=$this->db->query("DELETE FROM int_schools_x_sessions_log 
        WHERE drhomework_ref_id='".$sessionID."'
        AND Tutoring_client_id='".$Tutoring_client_id."'
        AND type='drhomework'");

            $queryOne=$this->db->affected_rows();



            $query=$this->db->query("DELETE FROM dr_tutoring_info 
            WHERE drhomework_ses_id='".$sessionID."'
            AND Tutoring_client_id='".$Tutoring_client_id."'");
            $queryTwo= $this->db->affected_rows();

            

        if($queryTwo >0  OR $queryOne > 0){

                $success='Tutoring Sessions Delete! ';
                $res['status'] = true;
                $res['response']=$success;
                $this->response($res);
    } 
    else{

            $system_warnings[]='error while Deleting Tutoring session!';

        }

         if(!empty($system_warnings))
         {
            $res['status'] = false;
            $res['response']['error']= implode(', ', $system_warnings) ;
            $this->response($res);
         }
 }

}