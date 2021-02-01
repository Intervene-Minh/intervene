<?php

/***
// error_reporting(-1);
//     ini_set('display_errors', 1);
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');
 ###########
 $sql="SELECT sd.quiz_status,sd.launchurl,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN "
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";  // tut_teacher_id 
      
       //$sql.=" AND ses.ses_start_time >= '".$time_arr['24_hour_back']."' ";
        # 55 min. less session in Penidng list.
        #$sql.=" AND ses.ses_start_time >= '".$time_arr['time_55_less']."' ";
       # sesion before: 55 min. or in next 55 min from current time.
       $sql.=' ORDER BY ses.ses_start_time ASC ';

       @@@@@@@@@@
       for now please just extend 10 mins to the tutorial session time for students
There is a redirect that happens 50 mins after start of tutorial session please add 10 mins

       @@@@@@@@@
       //print_r($time_arr); # globals for sessions
@@@@@@@@@@@@@@@@@@@@@@@@@
Groupword to Newrow if Changed 
@ 
OCt- 9, 2019


**/




 include('inc/connection.php'); 
session_start(); ob_start();
if (!$_SESSION['student_id']) {
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
  $isBoardChanged='no';

          //echo '========='.$in_sec;  die;

              $content_arr=array();   
               if($row['curr_active_board']!='newrow'){
                $currentBoard=$row['curr_active_board'];
                $isBoardChanged='yes';
                // Board have chage d

               }
              //$in_sec=-3001; //test, go to quiz
                    
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
                          $content_arr['quiz_url']='https://intervene.io/questions/student_pendings.php';

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

