<?php
///
include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}
?>
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
        <div class="row">
            <div class="align-center col-md-12">
                
                <div style=" width:auto;" title="">
                    
                <?php
				
                $assesment = mysql_fetch_assoc(mysql_query('SELECT assessment_id FROM teacher_x_assesments_x_students WHERE '
                                . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
                                . 'school_id = \'' . $_SESSION['schools_id'] . '\' AND '
                                . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' AND status != "Completed" AND assessment_id > 0 '));
								
               if ($assesment['assessment_id'] > 0) {
                    $sql = mysql_query('SELECT qn_id FROM assessments_x_questions WHERE '
                            . 'assesment_id = \'' . $assesment['assessment_id'] . '\'');
                    $_SESSION['assessment'] = array();
                    $_SESSION['assessment']['assesment_id'] = $assesment['assessment_id'];
                    $_SESSION['assessment']['qn_list'] = array();
                    while ($question_ids = mysql_fetch_assoc($sql)) {
                        $_SESSION['assessment']['qn_list'][] = $question_ids['qn_id'];
                    }
                    $resume_result = mysql_fetch_assoc(mysql_query('SELECT MAX(num) as last_qn_num FROM students_x_assesments WHERE '
                                    . 'assessment_id = \'' . $assesment['assessment_id'] . '\' AND '
                                    . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
                                    . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' AND '
                                    . 'school_id = \'' . $_SESSION['schools_id'] . '\' '));
					
                    if ($resume_result['last_qn_num'] > 0) {
                        ?>
						 
                        <a href="assesment_start.php?pos=<?php print $resume_result['last_qn_num']; ?>" class="btn btn-green"><button class="form_button submit_button" style="height: 200px;width: 200px;font-size: 30px;">Resume</button></a>
						
                    <?php } else { ?>
					 
                        <a href="assesment_start.php" class="btn btn-green">
                            <button class="form_button submit_button"
                                                                                    style="height: 200px;width: 200px;
                                                                                    font-size: 30px;">Start</button></a>
                                                                                   
                    <?php
																					
                    }
                } else {
                    ?>
                    
               
                        
                                                                                     
                    
                    <?php
               }
                ?> 
                    
                    <?php
            $student_id=$_SESSION['student_id'];#
            $cc=" order by created_date DESC ";
   // quiz_status # completion_date
   //$q=mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 ");
            $start_date = date('Y-m-d h:i:s');
            $date = date("Y-m-d H:i:s", strtotime('-24 hours', time())); # 2018-03-16 13:47:14
           // $sql99=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND ses_start_time< '$start_date' order by ses_start_time DESC limit 1 ";
           // back revent 
            
             $sql="Select sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN"
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";
     $sql .= " AND ses.ses_start_time<'$start_date' AND sd.quiz_status!='Completed' ";
     $sql.=" order by ses.ses_start_time DESC limit 1 ";
            # time below and Student Quiz pending# !Completed
             // echo $sql; die;
            ////////////////// own student,PrevQuiz
            $res=mysql_fetch_assoc(mysql_query($sql));
            $lastid=$res['id'];
           // echo 'last id-'.$lastid;echo '<br/>';
   $sql="Select sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN"
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";
     $sql .= " AND (ses.ses_start_time > '$start_date' OR ses.id = '$lastid' ) ";
      //echo $sql order by ses_start_time DESC
     
     $sql.=" order by ses.ses_start_time ASC ";
      $sql.=" limit 2 ";# only 1 to student 15 days# only 2:: 1 before and upcoming
    
   $q=mysql_query($sql); ?>
                
                
                    
       
          <?php
     
   
     $ses=1;
     while ($row= mysql_fetch_assoc($q)) {  
              
     $techer= mysql_fetch_assoc(mysql_query(" SELECT * FROM users WHERE id='".$row['teacher_id']."' "));         
              
           $st_class=NULL;   
             $st_class=($row['tut_status']=="STU_ASSIGNED")?"btn btn-success btn-sm":"btn btn-danger btn-sm"; 
          $st_title=($row['tut_status']=="STU_ASSIGNED")?"Students Assigned":"Students Not assigned";
     $grade=mysql_fetch_assoc(mysql_query(" SELECT name FROM terms WHERE id='".$row['grade_id']."' "));    
        $grade=($row['grade_id']>0)?$grade['name']:"NA";
         
         $tutor= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE id='".$row['tut_teacher_id']."' "));
        ?>
                  <?php
                 $val1 = date("Y-m-d H:i:s"); #currTime
 //$val1 ='2018-03-11 10:45:00'; #currTime
 $start_date = new DateTime($val1); // 
 $sesStartTime=$row['ses_start_time'];
 $enddate=new DateTime($sesStartTime);
$since_start = $start_date->diff($enddate);
 
//// 

$in_sec= strtotime($sesStartTime) - strtotime($val1);///604800 #days>+7 days
            
           ?>  
                    
                  <?php 
                     
                  //  3300 :: link show 55 min
	  //echo date('M d Y, h:i:s', strtotime($sesStartTime)).'=='.date('M d Y, h:i:s');
				  
			$atTime=date('h:i a', strtotime($sesStartTime));	  
                      if($in_sec<=120&&$in_sec>-3300){ //activeTill55Min&&activeBefore15min# tutTH>0 # tutTH>0
                          $app_url=$tutor['url_aww_app'];
                           $app_title="Click,to go to app url";
                           $app_border="2px solid green";
                          $tatrget="_blank";
    
                           
                              
                      }else{# 'inavtive='; 
                                  $app_title="Inactive, active 2 min before ".$atTime;#Active before 2 min.
                                   $app_border="2px solid yellow ";
                                    $app_url="javascript:void(0);";
                                 // $app_url=$tutor['url_aww_app'];# TEST
                                 $tatrget=""; 
                              }  

               if($in_sec<-3300){  // after 55 min.# start of session
                 $msg= "Session Expired ";
               }else{
                      ?> 
                   
               
                      <a  target="<?=$tatrget?>" href="<?=$app_url?>" title="<?=$app_title?>" class="btn btn-green">
                       <button class="form_button submit_button" style="height: 200px; border:<?=$app_border?>;width:
                               220px;font-size:30px;">Start<br/>Tutoring session</button></a>
                               <?php  
                               if($since_start->invert==0){
                                   $gfgf= $since_start->s.' seconds<br>'; }
                                   ?> 
                               <!--         </div>-->

                               
							  
                  <?php //echo$app_title?>
               <?php }?>    
                   
                 <!--    Exit Quiz-->
                 <?php 
                    // quiz_status# Completed 
                   
                    if($row['quiz_status']=="Completed"){
                       
                      $msg= $row['quiz_status'];// Completed
                         //   echo '<br/><strong class="text-primary"> Date :</strong>'.$row['completion_date'];
                    
                      
                       }else{
                    
                       // if($in_sec<0&&$in_sec>-86400){   #jsutstart ses to 1 day
                           if($in_sec<-1800&&$in_sec>-86400){   #30min ses to 1 day
                         $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
                          $quiz_title="Click to start quiz";
                     
                          ?>
                         
                          <!--      <div style="float:left;">-->
                          
                               <a href="<?=$quiz_link?>" title="<?=$quiz_title?>" class="btn btn-green">
                     <button class="form_button submit_button"
                             style="height: 200px;width: 200px;font-size: 30px;">Take Your <br/>Quiz</button></a>
                              
                               
                      
                              <?php
                          
                      }else{
                       $quiz_link= '#';  #   quiz Inactive
                       $quiz_title="available after 30 min from start a session";
                         // $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active';  #TEST
                     
                      ?>
                       
                              
                             
                   
                               
                               
                      <?php }   } //notcom  ?>            
                               
                  <!--    Exit Quiz-->
                    
                  
                    
                     <?php }?>
                
                      </div>                                                                
                 <!--        Tutoring Sections        Student aww app board     -->                                                                   
            </div>
        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush(); ?>

