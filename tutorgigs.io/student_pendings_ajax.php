<?php
// error_reporting(-1);
//     ini_set('display_errors', 1);
/****
 $start_date = date('Y-m-d h:i:s');
  # Students :teacher_id school_id grade_level_id class_id created
  @ Student All pending
  1. Pending quiz
  2. pending : assessment
  3. upcoming session > 
    $board_url='session-board.php';
  $actvity_url="actvity.php";
  $page_name='Recent session';
 $no_record='No record found.!';
 * **/
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');

include("student_header.php");
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
  $page_name='Recent session';




///Listing////////////////

?>

<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
        <div class="row">
          
           <!--  NExt col -->
           <div class="align-center col-md-12">
          <form name="principal_action" id="principal_action" method="POST" action="">
       


            <?php

            ##########################
            $student_id=$_SESSION['student_id'];#
            $cc=" order by created_date DESC ";
   $sql="SELECT sd.quiz_status,sd.launchurl,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN "
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";  // tut_teacher_id 
      
      # $sql.=" AND ses.ses_start_time >= '".$time_arr['24_hour_back']."' ";
        # 55 min. less session in Penidng list.
        #$sql.=" AND ses.ses_start_time >= '".$time_arr['time_55_less']."' ";

       // Next 24 thoud
        //$sql.=" AND ses.ses_start_time <= '".$time_arr['24_hour_next']."' ";
       $sql.=' ORDER BY ses.ses_start_time ASC ';

           //echo '<pre>', $sql; //   die;


     $result=mysql_query($sql);
     
     while ($row= mysql_fetch_assoc($result)){  
    $val1 = date("Y-m-d H:i:s"); #currTime
 //$val1 ='2018-03-11 10:45:00'; #currTime
 $start_date = new DateTime($val1); // 
 $sesStartTime=$row['ses_start_time'];
 $enddate=new DateTime($row['ses_start_time']);
$since_start = $start_date->diff($enddate);
  $time_str='';
 if($since_start->invert==0){
 $time_str.= '<br/><strong>Remaing time:-</strong>';
$time_str.= $since_start->days.' days<br>';
               $time_str.= $since_start->h.' hr,';
$time_str.= $since_start->i.' min,';
 $time_str.= $since_start->s.' seconds<br>';}
//// 
 // echo 'Curr - TIME-'.$val1.'<br/>';
$in_sec= strtotime($sesStartTime) - strtotime($val1);///604800 #days>+7 days
   #Time trigger if >activity Move
#$in_sec=-1900;# quiz options
 $in_sec=-1;# quiz options
$in_sec=0;#actvity

                    $target_next=0;
                      $content_arr=array();
                        if($in_sec<=300&&$in_sec>=1){  // 60x5 ::   15 min before activity.
                    
                      $content_arr['app_url']=$app_url=$actvity_url;
                          $app_title="Go to actvity-acitvity.php";
                          $target_next=1;
                          # ####
                         $content_arr['is_redirect'] = $is_redirect=1;
                      $content_arr['url_redirect'] =$url_redirect=$app_url;

                     }elseif($in_sec<=0&&$in_sec>-1800){ //activeTill55Min&&act: 30minX60
              
                      // at ses time to 30 min. board active.: go to ::<student_board.php
              $app_url=(!empty($row['braincert_class']))?$row['launchurl']:$board_url;
                           $app_title="Click,to go to app url";
                           $app_border="2px solid green";
                           $target_next=1;
                       $content_arr['is_redirect']=$is_redirect=1;
                      $content_arr['url_redirect'] =$url_redirect=$app_url;
                         
                      }elseif($in_sec<-1800&&$in_sec>-86400){

                               $content_arr['is_redirect']=$is_redirect=0;
                          $content_arr['url_redirect'] ='#';

                               $app_title="Quiz Option.";
                                   $app_border="2px solid green ";
                                    $app_url="quiz_start.php";
                                    $target_next=1;

                      }else{# 'inavtive='; #Listing shows
                       $content_arr['is_redirect']=$is_redirect=0;
                          $content_arr['url_redirect'] ='#';

                                  $app_title="Actvity link active 5min. before.";
                                   $app_border="2px solid yellow ";
                                    $app_url="javascript:void(0);";
                                    $target_next=0; 

                                     }
                                     //
                # if($in_sec<-1800&&$in_sec>-86400){    
                                 

                                     # quiz option at 
          
        
          ?>
          
           <?='=='.$in_sec.'<br/>'; # Test time.?>
                <?=$time_str?><br/>
                 Session_ID: <?=$row['id']?><br/>
          <?php }?>


          <?php 
           echo 'Redirect status =<br/>';
           print_r($content_arr);
           echo '<br/>';
          ?>
            
            
            
            
            
      

        
        
        
    </form>
                      
            </div>

           <!-- C0ntent -->



        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush();
 //print_r($_SESSION);
 ?>

