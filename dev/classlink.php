<?php

/*INCLUDE DATA BASE CONNECTION FILE*/
require_once $_SERVER['DOCUMENT_ROOT'].'/questions/inc/connection.php';
/*get site URL*/
$siteURL="https://" . $_SERVER['SERVER_NAME'].'/questions/';
session_start();
/*student login function*/
function _studentLogin($student_id){

    global $siteURL;
    $redirectURL = $siteURL.'welcome.php';
    $student_id = mysql_query("SELECT * FROM `students` WHERE `id` = $student_id AND `status` = 1 ");
    if( mysql_num_rows($student_id) == 0 ) {

        $error = 'Your information is invalid!';
    } 
    else {

      $students = mysql_fetch_assoc($student_id);
      $_SESSION['student_id'] = $students['id'];
      $studi= $students['id'];
      $_SESSION['student_name'] = $students['first_name'];
      $_SESSION['last_name'] = $students['last_name'];
      $_SESSION['schools_id'] = $students['school_id'];
      $str="SELECT tch.teacher_id FROM `students_x_class` as stu INNER JOIN `class_x_teachers` AS tch ON stu.class_id=tch.class_id WHERE stu.student_id=$studi";
      $teachD= mysql_fetch_assoc(mysql_query($str));

      $_SESSION['teacher_id'] = $teachD['teacher_id'];
      header("Location: $redirectURL"); 

    }
}

/* redirect funtion*/
function _redirect($localion){

    $url="https://" . $_SERVER['SERVER_NAME'].'/'.$localion;
    echo "<script> window.location='".$url."';</script>";
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
extract($_REQUEST);

$secret="2f2dfad642825b8128aaa5b8d94607b5";
$client_id="c160076006337907d132875efff1c7d7926b9c249624";



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://launchpad.classlink.com/oauth2/v2/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => "client_secret=$secret&client_id=$client_id&code=$code&response_type=code",
));

$res = curl_exec($curl);

curl_close($curl);


$resArr=json_decode($res);


$token =$resArr->access_token;


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://nodeapi.classlink.com/v2/my/info',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $token"
  ),
));

$response = curl_exec($curl);

curl_close($curl);



$StuArr=json_decode($response);



$tenant_Id=$StuArr->TenantId;

$email=$StuArr->Email;

$Sourced_Id=  $StuArr->SourcedId;


if(!empty($Sourced_Id))

{
       
          $qr=mysql_query("SELECT id FROM students WHERE email='".$email."'");
          $count = mysql_num_rows($qr);

        if($count > 0 ){ // check duplicate user
          $res = mysql_fetch_assoc($qr);
          $student_id= $res['id']; // get exist student id
         _studentLogin($student_id); /*student login */
        }
        else{

            /*redirct*/
            _redirect('warning-message.php');
        }

      }