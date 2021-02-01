<?php
/*****
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}

4 Steps-Form application
1. first signup  -inital registration ,name email
2. Step-1 application
 3 - step-2 QUIZ
4- step 3- Interview
@ all question fiel then 

****/

///
 include("header.php");


 //Valid User///////
 if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
//validate failed user

 include("inc/braincert_api_inc.php");
 // 194888
 $clss_id=195965;
 //echo 'hiiiiiiiii'; die; 
 //function get_student_board_url($clss_id,$student_id){
   // $get=get_student_board_url($clss_id,$tutor_id=125);
  echo 'Board URL<br/>';
 $get=get_tutor_board_url($clss_id,$tutor_id=125);
   // get_tutor_board_url
 $data_obj=json_decode($get);

 echo '<pre>'; print_r($data_obj); die; 

?>