<!-- this is create account-->
<?php
    /* create Account In Moodle*/

    $Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
    require_once($Directorypath.'cur-moodlel.php');
    $token = 'fcc6f9e980dbdef0496fac348966499f';
    $domainname = $webServiceURl;
    $functionname = 'core_user_create_users';

    $curl = new curl;
    $restformat = 'json'; 
    $userDetails = new stdClass();

    $ppas   =    'studentI@1';
    $uname  =    $_SESSION['student_name'].'_'.$_SESSION['student_id'];

if(empty($_SESSION['last_name'])){

    $_SESSION['last_name']=$_SESSION['student_name'];
}
    $userDetails->username              =       str_replace(' ', '',strtolower($uname));
    $userDetails->password              =       $ppas;
    $userDetails->firstname             =       $_SESSION['student_name'];
    $userDetails->lastname              =       $_SESSION['last_name'];
    $userDetails->email                 =       str_replace(' ', '',$uname.'@intervene.com');
    $userDetails->auth                  =       'manual';
    $userDetails->idnumber              =       $_SESSION['student_id'];

    $users = array($userDetails);
    $params = array('users' => $users);

    $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
    $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
    $resp = $curl->post($serverurl . $restformat, $params);
    $respons        = json_decode($resp,true); 
    $userID         =    $respons[0]['id'];
    $username       =    $respons[0]['username'];
    $IntervenID     =    $_SESSION['student_id'];
    $PassWord       =   $ppas;

if(!empty($userID)){
    
        $str="INSERT INTO Tel_UserDetails SET UesrName='".$username."',TelUserID='".$userID."',IntervenID='".$IntervenID."',PassWord='".$PassWord."'";
        mysql_query($str);
  }
/* Step 2 Course enrollment*/

/* check user add or not*/
if(!empty($userID))
{
  
/* get category course List*/
include_once("get-category-list.php");
$category_arr=[];  
$course_arr=[];  /// Student enrolled in Course-Category


foreach ($responCategory as $value)
{

    /* get course ID */
    $token = '14432b87ba8ea3a4896dc7707d10e71d';

    $Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
    require_once($Directorypath.'cur-moodlel.php');

    $domainname = $webServiceURl;
    $functionname = 'core_course_get_courses_by_field';

    $curl = new curl;
    $restformat = 'json'; 

    $params = array('field'=>'category','value'=>$value['id']);

    //print_r($params);
    $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

    $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

    $resp = $curl->post($serverurl . $restformat, $params);

    $responsCourse = json_decode($resp,true);

    foreach ($responsCourse['courses'] as $row) 
    {
           
            $course_id=$row['id'];
            $categoryid=$row['categoryid'];


            //categoryid
            $token="831c9d38c55c65b99a5dde1bc4677ae1";
            $functionname = 'enrol_manual_enrol_users';
            $enrolment = new stdClass();
            /* Enrol student*/

            $enrolment->roleid = 5; //estudante(student) -> 5; moderador(teacher) -> 4; professor(editingteacher) -> 3;
            $enrolment->userid =$userID;
            $enrolment->courseid = $course_id; 
            $enrolments = array( $enrolment);
            $params = array('enrolments' => $enrolments);
            $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
            $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
            $respEn = $curl->post($serverurl . $restformat, $params);
            /* add enroll Course ID */
            $str="INSERT INTO `Telpas_course_users` SET `telpas_uuid`='".$userID."',`course_id`='".$course_id."',`course_cat_id`='".$categoryid."',
            `intervene_uuid`='".$_SESSION['student_id']."'";
            mysql_query($str);


    }

}
    $url="http://englishpro.us/questions/telpas_practice_success.php";
    header("location:".$url);
}
?>