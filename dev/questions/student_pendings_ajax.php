<?php
/****
 // error_reporting(-1);
//     ini_set('display_errors', 1);
 ***/




 include('inc/connection.php'); 
session_start();
  ob_start();

$actvity_url="actvity.php";

if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}

/////////////////////
include("student_inc.php");
include("ses_live_inc.php");# Tutor sesion Live 
   //print_r($time_arr); # globals for sessions
#####################
 // page globals 
$board_url='session-board.php';
 //session re-direct time
//$redirect_sec=3600;
$redirect_sec=-4200; // 70X60 60min+10 min extra.


  $page_name='check Recent activity|session';

            ##########################
            $student_id=$_SESSION['student_id'];#
            $cc=" order by created_date DESC ";
//   $sql="SELECT sd.quiz_status,sd.launchurl,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN "
//           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";  
            
// TITU START //
            
             $sql="SELECT sd.quiz_status,sd.launchurl,sd.completion_date,sd.student_id,ses.*, m.url FROM int_schools_x_sessions_log ses INNER JOIN "
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id LEFT JOIN  master_lessons m ON ses.lesson_id=m.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 "; 
           
          

// TITU END //
      
       //$sql.=" AND ses.ses_start_time >= '".$time_arr['24_hour_back']."' ";
        # 55 min. less session in Penidng list.
        #$sql.=" AND ses.ses_start_time >= '".$time_arr['time_55_less']."' ";
       # sesion before: 55 min. or in next 55 min from current time.
       // Next 24 thoud
        //$sql.=" AND ses.ses_start_time <= '".$time_arr['24_hour_next']."' ";
       $sql.=' ORDER BY ses.ses_start_time ASC';

        //  echo '<pre>', $sql; //   die;


     $result=mysql_query($sql);
  
     while ($row= mysql_fetch_assoc($result)){  
  //echo date("Y-m-d H:i:s")."=".$row['ses_start_time']; exit;   
    
    $val1 = date("Y-m-d H:i:s"); #currTime
 //$val1 ='2018-03-11 10:45:00'; #currTime
 $start_date = new DateTime($val1); // 
 $sesStartTime=$row['ses_start_time'];
 $enddate=new DateTime($row['ses_start_time']);
$since_start = $start_date->diff($enddate);
  $time_str='';
  
  // TITU START
//  $ses_duration = strtotime($sesStartTime) + $row['session_duration'] * 60;
//  if($ses_duration < strtotime($val1))
//  {
//      
//      exit;
//  }
// TITU END //
  
 if($since_start->invert==0){
 $time_str.= '<br/><strong>Remaing time:-</strong>';
$time_str.= $since_start->days.' days<br>';
               $time_str.= $since_start->h.' hr,';
$time_str.= $since_start->i.' min,';
 $time_str.= $since_start->s.' seconds<br>';}
//// 
 // echo 'Curr - TIME-'.$val1.'<br/>';
$in_sec= strtotime($sesStartTime) - strtotime($val1);///604800 #days>+7 days

/*
$dur=$row['session_duration'];
$hours = floor($idurnit / 3600);
$minutes = floor(($dur / 60) % 60);
$seconds = $dur % 60;
$redirect_sec=-$seconds+600; // 65X60:: 50min+5min activity+10min extra
*/
$content_arr=array();
                         //echo '==='.$in_sec;  die; 
                         
// TITU START

if($_GET['get_param'] == student_actvity)
{
  $sess_end_activity_time = strtotime($val1) - strtotime($sesStartTime);

  if($sess_end_activity_time >= 60)
  {
                        $content_arr['app_url'] = 'student_board.php';
                        $app_title = "Go to student whiteboard";
                        $content_arr['is_redirect'] = $is_redirect=1;
                        $content_arr['url_redirect']='student_board.php';
                        $_SESSION['live']['live_ses_id']=$row['id']; ///set for acivity url
                        $trigger=1;
                        break;
  }
}
else
{
  //echo $val1."=".$sesStartTime.'='.$in_sec; exit;
    $sess_end_activity_time = strtotime($val1);
     $ses_duration = strtotime($sesStartTime) + 5 * 60;

                    if(($in_sec<=600 && $sess_end_activity_time <=$ses_duration)  && !empty($row['url']))    // 10 min before if activity attachded
                    { 
                        $content_arr['app_url'] = $actvity_url;
                        $app_title = "Go to actvity-acitvity.php";
                        $content_arr['is_redirect'] = $is_redirect=1;
                        $content_arr['url_redirect']=$actvity_url;
                        $_SESSION['live']['live_ses_id']=$row['id']; ///set for acivity url
                        $trigger=1;
                        break;
                    

                     }
                     elseif($in_sec<=300 && $in_sec>=-300 && empty($row['url'])){  // 5 min before if activity not attached
//                      $content_arr['app_url']=$actvity_url;
//                          $app_title="Go to actvity-acitvity.php";
//                         $content_arr['is_redirect'] = $is_redirect=1;
//                      $content_arr['url_redirect']=$actvity_url;
//                      $_SESSION['live']['live_ses_id']=$row['id']; 
//                       $trigger=1;break;
                         
                            $content_arr['url_redirect']='student_board.php';

                        $app_title="Click,to go to app url";  
                       $content_arr['is_redirect']=$is_redirect=1;
                        $content_arr['ses_id']=$row['id'];
                      
                      /// sesion Lock ID
                      $_SESSION['live']['live_ses_id']=$row['id'];
                      $_SESSION['live']['live_board_url']=$row['launchurl'];
                      $_SESSION['live']['app_other']='student_in_session';
                      $_SESSION['live']['stu_curr_url']='student_board.php';
                        // end page
                       $trigger=1;break;
                    

                     }elseif($in_sec <=-301 && $in_sec>$redirect_sec){ //activeTill55Min&&act: 30minX60::35 min # 2100
                       
                      
                       
                        $content_arr['url_redirect']='student_board.php';

                        $app_title="Click,to go to app url";  
                       $content_arr['is_redirect']=$is_redirect=1;
                        $content_arr['ses_id']=$row['id'];
                      
                      /// sesion Lock ID
                      $_SESSION['live']['live_ses_id']=$row['id'];
                      $_SESSION['live']['live_board_url']=$row['launchurl'];
                      $_SESSION['live']['app_other']='student_in_session';
                      $_SESSION['live']['stu_curr_url']='student_board.php';
                        // end page
                       $trigger=1;break;

                      
                         
                      }elseif($in_sec<$redirect_sec&&$in_sec>-86400){// Quiz time

                              $content_arr['is_redirect']=$is_redirect=0;
                              $content_arr['url_redirect'] ='#';

                              $app_title="Quiz Option.";

                              $app_url="quiz_start.php";
                              // $trigger=1;break;

                      }
}

// TITU END
                      // else{# 'inavtive='; #Listing shows
                      //  $content_arr['is_redirect']=$is_redirect=0;
                      //     $content_arr['url_redirect'] ='#';
                      //             $app_title="Actvity link active 5min. before.";
                      //               $app_url="javascript:void(0);";

                      //               //list sessions///////////
                      //               //$ses[]=['Remaing']=$time_str;
                      //             $arr=array();
                      //          $arr['Remaing']=$time_str;
                      //          $arr['ses_id']=$row['id'].'_SES';
                      //           $arr['in_sec']=$in_sec;
                      //               $content_arr['listing'][$row['id']]=$arr;

                      //                }
                              #################





            


            //                       
           }//end while
###################
      

     
        

////////////////////
     if(isset($trigger)&&$trigger==1){


      $result=array();
  $result['status']='OK';
  $result['sent_from']=$_GET['get_param'];
  $result['is_redirect']=1;
  $result['url_redirect']=$content_arr['url_redirect'];
  $result['content']=$content_arr;

       $json=json_encode($result);
    echo $json; die; 



     }else{
       $result=array();
  $result['status']='not';
  $result['sent_from']=$_GET['get_param'];
  $result['is_redirect']=0;
  $result['url_redirect']='#';
  $result['content']=$time_arr;
  $json=json_encode($result);
    echo $json; die; 

     }    
   
 //  print_r($content_arr);
// else echo 'List data';

          ob_flush();
 //print_r($_SESSION);
 ?>

