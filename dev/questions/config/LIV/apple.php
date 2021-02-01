<?php


/*****

2:15 am		Chicago (GMT-5)

Chicago used for session.
**/




//$db=new mysqli('localhost','root','root','checkmate_api');
ini_set("date.timezone", "CST6CDT");
/////
define("TUTOR_BOARD", "groupworld");


@session_start();
$today = date("Y-m-d H:i:s"); // 

define("SITE_TITLE","Drhomework.com");

define("SITE_TITLE_PARENT","Parent | Drhomework.com");

// echo  '===';  die; 

$db=new mysqli('localhost','drhomework','}3U2ccUP#,j+','drhomework');

/////////////////////////////

if($db->connect_errno){
    echo 'Db connection error-'.$db->connect_error;
}


/// Fetch ///
// $res=$db->query(" SELECT * FROM `ty_student` WHERE 	student_id=8");
// $res2=$res->fetch_assoc();

 // if(nlll!=={
 //    print_r($data); die;  
 // }else{
 // echo ''
 // }


 //print_r($res2);// die;  




?>