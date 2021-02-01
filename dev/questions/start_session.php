<?php
/****
 $start_date = date('Y-m-d h:i:s');
 * @students
  Status 
  Teacher -
  Result
  # Students :teacher_id school_id grade_level_id class_id created
 * **/
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');

include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}
   $board_url='session-board.php';
  $actvity_url="actvity.php";
  $page_name='Recent session';
 $no_record='No record found.!';

/////////////////////

   $cur_time= date("Y-m-d H:i:s");  // die;
     $time=strtotime($cur_time);
     $startTime = date("Y-m-d H:i:s", strtotime('-55 minutes', $time));
$endTime = date("Y-m-d H:i:s", strtotime('+55 minutes', $time));
 $one_hr_les=date("Y-m-d H:i:s", strtotime('-60 minutes', $time));
 ///*************

 $time_arr=array();
  $time_arr['curr_time']=date("Y-m-d H:i:s");
$time_arr['one_hr_les']=$one_hr_les;

 $time_arr['time_55_less']=$startTime;
 $time_arr['time_55_up']=$endTime;
      $time_arr['24_hour_back']=date('Y-m-d H:i:s',strtotime('-24 hours'));
      $time_arr['24_hour_next']=date('Y-m-d H:i:s',strtotime('+24 hours'));

    //print_r($time_arr); 

 //echo '==one hour=='.$one_hr_les;
 //echo  '<pre>   '. $startTime.'End times#'.$endTime ;  die;
   
   $sql="SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
   $sql.=" AND ses_start_time >= '$startTime' and ses_start_time <= '$endTime' ";
   $sql.=" AND tut_teacher_id='$tutor_id' ";
  // echo  $error_msg=  '<pre>'.$sql ; die;







///@@@@@@@@@@@@
$data=array();// Listing array 
include("student_inc.php");
$msg='Pending ==pending_assessments.php';
 //print_r($_SESSION); //print_r(expression)



// $sql="SELECT * FROM `teacher_x_assesments_x_students` WHERE `teacher_id` = '".$student_det['teacher_id']."' AND `student_id` =".$_SESSION['student_id'];
// $sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
// Left Join assessments a ON  sa.assessment_id=a.id
// WHERE sa.teacher_id = '365' AND sa.student_id= '10748' ";

$student_school=$student_det['school_id'];
$student_teacher=$student_det['teacher_id'];// 

$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";


$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {  
 // $teacher=mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE id=".$student_det['teacher_id']));
   //$row['asssessment']='Demo asement';
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}






/////////////Display
//echo $msg;
 // echo '<pre>';
 // print_r($data);
 // echo 'Student==';
 // print_r($student_det);

?>

<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
        <div class="row">
            <div class="align-center col-md-12" style="margin-top:10px; margin-bottom: 100px;">
                
                <div style=" width:auto;" title="">
                 <?php include("nav_students.php"); ?>
       

        
                
                      </div>    





            </div>
           <!--  NExt col -->
           <div class="align-center col-md-12">
<!--                <p class="text-success">Session list of students..  </p>-->
               <?php 
               



               ?>


          <form name="principal_action" id="principal_action" method="POST" action="">
        <table class="table table-hover">
            <tr>
                <th>Session Date/Time</th>
                <th>Detail</th>
                <th>Quiz</th>
               
                <th>Action</th>
            </tr>
            <?php




            ##########################
            $student_id=$_SESSION['student_id'];#
            $cc=" order by created_date DESC ";
   // quiz_status # completion_date
            // $sql.=" AND ses_start_time >= '$startTime' and ses_start_time <= '$endTime' ";
   //$q=mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 ");
      // Tutor >0 in session.
   $sql="SELECT sd.quiz_status,sd.launchurl,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN "
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";  // tut_teacher_id 

       $sql.=" AND ses.ses_start_time >= '".$time_arr['24_hour_back']."' ";
       // Next 24 thoud
        //$sql.=" AND ses.ses_start_time <= '".$time_arr['24_hour_next']."' ";
       $sql.=' ORDER BY ses.ses_start_time ASC ';


           // w/o Tutor Assigned. 
          

           //echo '<pre>', $sql;   die;


     $result=mysql_query($sql);
     
   
     
     while ($row= mysql_fetch_assoc($result)){  
              
     $techer= mysql_fetch_assoc(mysql_query(" SELECT * FROM users WHERE id='".$row['teacher_id']."' "));         
      
      $tutor= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE id='".$row['tut_teacher_id']."' "));
         // url_aww_app   # $tutor
      $st_class=NULL;   
             $st_class=($row['tut_status']=="STU_ASSIGNED")?"btn btn-success btn-sm":"btn btn-danger btn-sm"; 
          $st_title=($row['tut_status']=="STU_ASSIGNED")?"Students Assigned":"Students Not assigned";
     $grade=mysql_fetch_assoc(mysql_query(" SELECT name FROM terms WHERE id='".$row['grade_id']."' "));    
        $grade=($row['grade_id']>0)?$grade['name']:"NA";
        
          ?>
            
           <tr>
                <td>
                 Session_ID: <?=$row['id']?><br/>
                  <span style=""><?php  echo 'Time:'.$start_date = date('Y-m-d H:i:s'); ?></span>  
                  <br/>
               <span>
               <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br>                        
                                      
               </span>   
             <span class="btn btn-success btn-lg" style="font-weight: bold;">
                   <?=date_format(date_create($row['ses_start_time']), 'h:i a');#?>
             </span> 

              </td>   
                  
              
              
                


                
                 <td>
                    <!--  <strong>Teacher: </strong><?php //=$techer['first_name']?> <br/> -->
                     <strong>Type: </strong><?=$row['type']?> <br/>


                 
                 <?php
                 $val1 = date("Y-m-d H:i:s"); #currTime
 //$val1 ='2018-03-11 10:45:00'; #currTime
 $start_date = new DateTime($val1); // 
 $sesStartTime=$row['ses_start_time'];
 $enddate=new DateTime($row['ses_start_time']);
$since_start = $start_date->diff($enddate);
 if($since_start->invert==0){
 echo '<br/><strong>Remaing time:-</strong>';
echo $since_start->days.' days<br>';
               echo $since_start->h.' hr,';
echo $since_start->i.' min,';
 echo $since_start->s.' seconds<br>';}
//// 
 // echo 'Curr - TIME-'.$val1.'<br/>';
$in_sec= strtotime($sesStartTime) - strtotime($val1);///604800 #days>+7 days
            
           ?>
                    
                 
                 
                 </td>
                 
                  
                  <td>
                    <?php 
                    // quiz_status# Completed 
                   
                    if($row['quiz_status']=="Completed"){
                       
                      
                    
                    ?>
                  <span class="btn btn-success btn-sm">
                   <?=$row['quiz_status'];?></span>    
                      
                      
                       <?php
                        echo '<br/><strong class="text-primary">
                                 Date :</strong>'.$row['completion_date'];
                       }else{
                       
                       
                       
                       
                       
                     // if($in_sec<-1800){ // after 30 min.#show
                    //  if($in_sec<0&&$in_sec>-86400){   #1 day#jus after sesion
                         if($in_sec<-1800&&$in_sec>-86400){  //after 30 min quiz apear
                         $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
                          $quiz_title="Click to start quiz";
                     
                          ?>
                         <a href="<?=$quiz_link?>" title="<?=$quiz_title?>" class="btn btn-green">
                       <span class="form_button submit_button"
                               style="height: 200px; width:
                               200px;font-size: 20px;">Take Your Quiz</span></a>  
                      
                              <?php
                          
                      }else{
                       $quiz_link= '#';  #   quiz Inactive
                       $quiz_title="available as start a session";
                         // $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active';  #TEST
                     
                      //}
                      ?>
                      <span title="<?=$quiz_title?>">NA</span>
                   
                               
                               
                      <?php }   } //notcom  ?>            
                               
                      
                  
                  </td>
                  <td>
                      
                      
                  <?php // echo $newDate = date("d-m-Y", strtotime($row['created_date']));?>
                   
                   
                    <?php 
                      // 900 ::15*60 min
                  //  echo '=='.$in_sec.'<br/>'; # Test time. 

                    // 15 min before > actvity url 
                     
                   
                    $target_next=0;
                   # $in_sec=899;#Test
                   // if($in_sec<=0&&$in_sec>-3300){ //activeTill55Min&&act
                     if($in_sec<=0&&$in_sec>-1800){ //activeTill55Min&&act: 30minX60
              $app_url=(!empty($row['braincert_class']))?$row['launchurl']:$board_url;

                           $app_title="Click,to go to app url";
                           $app_border="2px solid green";
                           $target_next=1;
                          
    
                              //echo 'url=youtub.com';
                           //  15 min before activity.
                              
                      }elseif($in_sec<=900){  // 60x15 ::   15 min before activity.
                    
                         $app_url=$actvity_url;
                          $app_title="Click,to go to actvity";
                          $target_next=1;

                     }else{# 'inavtive='; 
                                 // $app_title="Active before 2 min.";
                                  #$app_title="Active at session time.";

                                  $app_title="Actvity link active 15min. before.";
                                   $app_border="2px solid yellow ";
                                    $app_url="javascript:void(0);";
                                    $target_next=0;
                                //   $app_url=$tutor['url_aww_app']; # TESTTT
                                  
                                 
                              }//  

                              # Test_Board_url 
                          $app_url_end=(!empty($row['braincert_class']))?$row['launchurl']:$board_url; 



                                ?> 




                  
                  
                     
                      <?php 
                      if($in_sec<-1800){  // after 30 min.# start of session Link url  
                         echo "Session Expired,";
                         }else{ // Board Button?>

                      <a    <?php if($target_next==1){?> target="_blank" <?php }?>
                       href="<?=$app_url?>" title="<?=$app_title?>"
                       class="btn btn-green form_button submit_button" style="padding: 80px 0px;height: 200px; border:<?=$app_border?>;width:
                               200px;font-size:20px;">Start Tutoring session</a>

                      
                       
                  <?=$app_title?>
                    <?php }?>       
                               
                
                   
                  </td>
                
            </tr> 
          <?php }?>
            
            
            
            
            
            
            
            
        </table>
        
        
        
        
        
        
        
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

