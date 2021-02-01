<?php
include('inc/connection.php'); 
session_start(); ob_start();
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit;
}

include("student_inc.php");
include("ses_live_inc.php");# Tutor sesion Live 

// print_r($_SESSION); die;
#########################################################
$redirect_sec=-3900; // 65X60:: 50min+5min activity+10min extra

//echo $redirect_sec.'Test=='; die; 

$student_id=$_SESSION['student_id'];#
$cc=" order by created_date DESC ";
  #########################################################         

$sql="SELECT * FROM int_schools_x_sessions_log WHERE id=".$_SESSION['live']['live_ses_id'];
//echo '<pre>', $sql;    die;
$result=mysql_query($sql);
$row= mysql_fetch_assoc($result);
$val1 = date("Y-m-d H:i:s"); #currTime
$sesStartTime=$row['ses_start_time'];  
$in_sec= strtotime($sesStartTime) - strtotime($val1);///604800 #days>+7 days
$currentBoard=$row['curr_active_board'];
/*
$dur=$row['session_duration'];

$hours = floor($idurnit / 3600);
$minutes = floor(($dur / 60) % 60);
$seconds = $dur % 60;
$redirect_sec=-$seconds; // 65X60:: 50min+5min activity+10min extra

*/
//echo '========='.$in_sec;  die;

$content_arr=array();   
if($row['curr_active_board']!='newrow'){
$currentBoard=$row['curr_active_board'];
$isBoardChanged='yes';
// Board have chage d

}
              //$in_sec=-3001; //test, go to quiz
                    
// TITU START //

if($_GET['get_param'] == student_actvity && $_GET['from_page'] == 'student_room')
{
    $sess_end_activity_time = strtotime($val1);
   // $ses_duration = strtotime($sesStartTime) + 2 * 60;
    $ses_duration = strtotime($sesStartTime) + $row['session_duration'] * 60; 
    
    
    if($sess_end_activity_time >= $ses_duration)
    {
                       $content_arr['diff']=$in_sec;      
                       $content_arr['ses_state']='quiz_state';
                       $content_arr['live_ses_id']=$_SESSION['live']['live_ses_id'];
                       if($row['quiz_id'])
                         $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
                       else
                           $quiz_link= "welcome.php";
                       
                       $content_arr['quiz_url']=$quiz_link;
                       // Quiz not assigned 
//                        if($row['quiz_id']<1){
//                         
//                          $content_arr['quiz_url']='https://intervene.io/questions/logout.php';
//
//                         }


                        $trigger=1;  //break;
    }
}
else
{
// TITU END //

                      if($in_sec<=300&&$in_sec>=-300){  // //activity time.

                          $content_arr['diff']=$in_sec;
                           $content_arr['ses_state']='actvity_state';
                      
                     }elseif($in_sec<=-301&&$in_sec>$redirect_sec){ //55minX60 =3300

                       //50min board time ::<student_board.php
                       $content_arr['diff']=$in_sec;      
                       $content_arr['ses_state']='board_state';
                       //$content_arr['is_redirect']=$is_redirect=1;
                       $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
                       $content_arr['quiz_url']='#';


                         
                      }elseif($in_sec<$redirect_sec&&$in_sec>-86400){  // quiz_state_iff >>session over, quiz not attempted. 


                           $content_arr['diff']=$in_sec;      
                       $content_arr['ses_state']='quiz_state';
                       $content_arr['live_ses_id']=$_SESSION['live']['live_ses_id'];
                       $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
                       $content_arr['quiz_url']=$quiz_link;
                       // Quiz not assigned 
                        if($row['quiz_id']<1){
                          // NoQuizLogoutStudentfromBoard.
                          $content_arr['quiz_url']='https://intervene.io/questions/logout.php';

                         }


                        $trigger=1;  //break;


                      }
                      // For Quiz sate

// IF Manual - tutor mark session as closed: then-re-direct to quiz: tutor_mark_end
 if(isset($row['tutor_mark_end'])&&$row['tutor_mark_end']==1){
    $content_arr=array();
   $content_arr['diff']=$in_sec;      
                       $content_arr['ses_state']='quiz_state';
                       $content_arr['live_ses_id']=$_SESSION['live']['live_ses_id'];
                       $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
                       $content_arr['quiz_url']=$quiz_link;
                        $trigger=1;  //break; Go, to quiz. 

 }



}

////////////////Display Result-- trigger///////////////////////////

 if(isset($trigger)&&$trigger==1){
   $result=array();
  $result['status']='ok';
  $result['cur_board']=$currentBoard; //'newrow';
  $result['cur_board_changed']=$isBoardChanged;  //'yes';
  $result['sent_from']=$_GET['get_param'];
  $result['content']=$content_arr;
 

 }else{

   $result=array();
  $result['status']='notok';
  $result['cur_board']=$currentBoard; //'newrow';
  $result['cur_board_changed']=$isBoardChanged;


  $result['sent_from']=$_GET['get_param'];
  $result['content']=$content_arr;
 

 }

  $json=json_encode($result);
  echo $json; die; 

  //  print_r($content_arr);

 //////////////////End///////////////////////////////////
    ob_flush();
 //print_r($_SESSION);
 ?>

