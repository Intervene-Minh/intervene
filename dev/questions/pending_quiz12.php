<?php
/****
 * @students
 * @students :: list 
 @OLD
 * **/

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
<!--                <p class="text-success">Session list of students..  </p>-->
                
             
          
    
 
    <form name="principal_action" id="principal_action" method="POST" action="">
        <table class="table table-hover">
            <tr>
                <th>Session Date/Time</th>
                <th>Detail</th>
                <th>Quiz</th>
               
                <th>Action</th>
            </tr>
            <?php
            $student_id=$_SESSION['student_id'];#
            $cc=" order by created_date DESC ";
   // quiz_status # completion_date
   //$q=mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 ");
   $sql="Select sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN"
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";
           // w/o Tutor Assigned. 
              $sql="Select sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN"
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>=0 ";

           echo '<pre>', $sql;  die;
     $q=mysql_query($sql);
     
   
     
     while ($row= mysql_fetch_assoc($q)) {  
              
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
                    
               <span>
               <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br>                        
                                      
               </span>   
             <span class="btn btn-success btn-sm">
                   <?=date_format(date_create($row['ses_start_time']), 'h:i a');#?>
             </span>     
                    
                    
                   
              
              
              
              
              
              
              
                </td>
                
                 <td>
                     <strong>Teacher: </strong><?=$techer['first_name']?> 
                 
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
                      if($in_sec<0&&$in_sec>-86400){   #1 day#jus after sesion
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
                    #  55 min . stat buuto show
                      if($in_sec<=120&&$in_sec>-3300){ //activeTill55Min&&activeBefore15min# tutTH>0 # tutTH>0
                          //$app_url=$row['app_url'];
                          //  // url_aww_app   # $tutor
                          $app_url=$tutor['url_aww_app'];
                           $app_title="Click,to go to app url";
                           $app_border="2px solid green";
                          
    
                              //echo 'url=youtub.com';
                              
                      }else{# 'inavtive='; 
                                  $app_title="Active before 2 min.";
                                   $app_border="2px solid yellow ";
                                    $app_url="#";
                                //   $app_url=$tutor['url_aww_app']; # TESTTT
                                  
                                 
                              }  

               if($in_sec<-1800){  // after 30 min.# start of session
                   echo "Expired,";
               }else{
                      ?> 
                   
                   
                      <a  target="_blank" href="<?=$app_url?>" title="<?=$app_title?>" class="btn btn-green">
                       <button class="form_button submit_button" style="height: 200px; border:<?=$app_border?>;width:
                               200px;font-size:20px;">Start Tutoring session</button></a>
                  <?=$app_title?>
               <?php }?>       
                               
                
                   
                  </td>
                
            </tr> 
          <?php }?>
            
            
            
            
            
            
            
            
        </table>
        
        
        
        
        
        
        
    </form>
           
<!--                dffffdf-->
                
            </div>
        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush(); ?>

