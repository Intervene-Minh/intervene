<?php
/****
 $start_date = date('Y-m-d h:i:s');
 
  # Students :teacher_id school_id grade_level_id class_id created
  @pending >Quiz> RESUMME for completed sessions
  @Students all completed QUIZ- completed sessions.
 * **/

include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}

  $page_name='Completed Quiz';
 $no_record='No record found.!';

///@@@@@@@@@@@@
$data=array();// Listing array 
include("student_inc.php");

$msg='';
 // print_($_SESSION);


$student_school=$student_det['school_id'];
$student_teacher=$student_det['teacher_id'];// 

// $sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
// Left Join assessments a ON  sa.assessment_id=a.id
// WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";

#$studentId=$_SESSION['student_id'];  //mysql_fetch_assoc(mysql_query(
//$quiz_id=1176;
// 


 
 

$sql=" SELECT sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$studentId' ";

 $sql.=$change=" AND sd.quiz_status='Completed' ";
 //echo  $sql;

$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {  
  $sesId=$row['id'];
  $quiz_score=" SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, (
(
SUM( corrected ) / count( qn_id )
) *100
) AS percentage, student_id,teacher_id FROM students_x_quiz WHERE student_id= '$studentId' AND tutses_id= '$sesId' AND quiz_id='".$row['quiz_id']."'
Group by  student_id";
$score=mysql_fetch_assoc(mysql_query($quiz_score));
 // print_r($score) ; die; 

  $row['score']=round($score['percentage'],2);


  // objective_name objective_id lesson_id grade_id
  $quiz=mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));
   $row['quiz_name']=$quiz['objective_name'];
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}


// echo '<pre>';
//  print_r($data); die;


/////////////Display///////////
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
            <p class="text-success page-title"><?=$page_name?>  </p>-
    <form name="principal_action" id="principal_action" method="POST" action="student_quizstartnow.php">
        <table class="table table-hover">
            <tr>
                <th>Session</th>
                <th>Teacher</th>
                <th>Result</th>
               
               
            </tr>
            <?php
        
     
   
       $total_records=count($data); // assesment_name
       $i=0;
      foreach ($data as $key => $arr) {
        # code...
       //Only pending 
       // if($arr['quiz_status']!='In Progress')continue;
       // echo $arr['assesment_name'].'==<br/>';

           $start_btn=($arr['quiz_status']=="Assigned")?'START':'Resume';

     // student_quiz.php?id=1176

              
        
          ?>
          <tr>
                <td>

                 <span>  <strong>Session ID: </strong><?=$arr['id']?> <br> </span>
                  <span>  <strong class="text-primary">Quiz:&nbsp;<?=$arr['quiz_name']?> </strong> <br/> </span>

                 
                   
                 
                   <span style="display: none;">  <span class="btn btn-default btn-sm"><?=$arr['quiz_status']?></span> <br/> </span> 
                    
           
            <!--  <span class="btn btn-success btn-sm">   11:20 am             </span>  -->
              
                </td>

                
                        <td>
                        
                  <span>  <strong> </strong><?=$arr['teacher']?> <br/> </span> 

                  
                
                    

                      </td>
                 
                                    
                 
                 
                
                 
                  
                  <td> <span>    <?php echo 'Completed, <br/><strong>Score:</strong>'.$arr['score'].'%'?>   </span>  </td>

                  
                
            </tr>
            
           
          <?php   $i++;     } ?>
            
            
            
            
            
            
            
            
        </table>
        
        
        
        
        
        
        
    </form>
           
<!--                dffffdf-->
                
            </div>

           <!-- C0ntent -->



        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush();
 //print_r($_SESSION);
 ?>

