<?php
/****
 $start_date = date('Y-m-d h:i:s');
 * @students
 * @students :: list 
 @ pending_assessments.php
 @ In Progress :: assessment of students 
 @ Assigned
 @ Completed
  Asssessment Name 
  Status 
  Teacher -
  Result
  # Students :teacher_id school_id grade_level_id class_id created
  @pending >Quiz> RESUMME for completed sessions
  // $sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
// Left Join assessments a ON  sa.assessment_id=a.id
// WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";



//student incomplete quiz for completed session: in_process :: In Progress
 // $studentId=10748; 
 // echo 'Stu='.$studentId;


 
 $studentId=$_SESSION['student_id'];
 * **/

// include("student_header.php");
// if (!$_SESSION['student_id']) {
//     header('Location: login.php');
//     exit;
// }


//  $no_record='No record found.!';

///@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
$data=array();// Listing array 

##   include("student_inc.php");



$msg='pending quiz ======';
  $page_name='Incomplete Exit Quiz';
 // print_($_SESSION);


 
$sql="SELECT sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$studentId' ";
$sql.=" AND sd.quiz_status!='Completed' AND ses.ses_end_time<'$cur_time' ";
$sql.=" AND ses.quiz_id>0  AND sd.quiz_id>0 ";

 //echo $sql;  //die;

$result=mysql_query($sql);

//echo '=='.mysql_num_rows($result);


while ($row= mysql_fetch_assoc($result)) {  
  // objective_name objective_id lesson_id grade_id
  $quiz=mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));
   $row['quiz_name']=$quiz['objective_name'];
   unset($row['braincert_board_url']);
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}

   //echo 'QUIZ data.';

  //print_r($data); die; 

/////////////Display///////////
//echo $msg;
 // echo '<pre>';
 // print_r($data);
 // echo 'Student==';
 // print_r($student_det);
    if(!empty($data)>0):
?>




  <p class="text-success page-title"><?=$page_name='Incomplete Exit Quiz';?>  </p>
    <form name="principal_action" id="principal_action" method="POST" action="student_quizstartnow.php">
        <table class="table table-hover">
            <tr>
                <th width="26%">Session</th>
                <th width="15%">Teacher</th>
               
               
                <th>&nbsp;</th>
            </tr>
            <?php
        
     
   
       $total_records=count($data); // assesment_name
       $i=0;
      foreach ($data as $key => $arr) {
        # code...
       //Only pending 
       // if($arr['quiz_status']!='In Progress')continue;
           $start_btn=($arr['quiz_status']=="Assigned")?'START':'Resume';

           // student_quiz.php?id=1176

              
        
          ?>
          <tr>
                <td>

                 <span>  <strong>Session ID: </strong><?=$arr['id']?> <br> </span>
                 <span>  <strong>QUIZ:&nbsp;<?=$arr['quiz_name']?> </strong> <br/> </span> 
                   
                 
                    
                    
           
            <!--  <span class="btn btn-success btn-sm">   11:20 am             </span>  -->
              
                </td>

                
                        <td>
                  <span>  <strong></strong><?=$arr['teacher']?> <br/> </span> 

                  
                
                    

                      </td>
                

                  <td>
                     <?php if($arr['quiz_status']!="Completed"){  // Resume?> 

                 <a  href="student_quizstartnow.php?id=<?=$arr['id']?>"
                   class="btn btn-primary btn-lg"><?=$start_btn?></a> 

                  <!--  <button type="submit" class="btn btn-primary btn-lg"
                    name="ses_id" value="<?=$arr['id']?>" ><?=$start_btn?>#</button> -->

                      
                               
                        <?php }else echo 'Completed, <br/>Score:NA'?>            
                
                   
                  </td>
                
            </tr>
            
           
          <?php   $i++;     } ?>
            
            
            
            
            
            
            
            
        </table>
        
        
        
        
        
        
        
    </form>
    <?php endif;?>
