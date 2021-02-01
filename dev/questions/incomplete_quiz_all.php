<?php
/****
 $start_date = date('Y-m-d h:i:s');

  @pending >Quiz> RESUMME for completed sessions
  @ Test page: Pending and 
  // Assigned , Assigned
 * **/

include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}

  echo $page_name=' Test page Incomplete Exit Quiz';
 $no_record='No record found.!';

///@@@@@@@@@@@@
$data=array();// Listing array 
include("student_inc.php");
$msg='pending quiz ======';
 // print_($_SESSION);


$student_school=$student_det['school_id'];
$student_teacher=$student_det['teacher_id'];// 
//student incomplete quiz for completed session: in_process :: In Progress // $studentId=10748; 


 
 $studentId=$_SESSION['student_id'];

$sql="SELECT sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$studentId' ";

$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {  
  // objective_name objective_id lesson_id grade_id
  $quiz=mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));
   $row['quiz_name']=$quiz['objective_name'];
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}





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
                <th>Quiz</th>
                <th>Other</th>
               
                <th>Action</th>
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
                 <span>  <strong>QUIZ<?=$arr['quiz_name']?> </strong> <br/> </span> 
                   
                 
                   <span>  <span class="btn btn-default btn-sm"><?=$arr['quiz_status']?></span> <br/> </span> 
                    
           
            <!--  <span class="btn btn-success btn-sm">   11:20 am             </span>  -->
              
                </td>

                
                        <td>
                  <span>  <strong>Teacher: </strong><?=$arr['teacher']?> <br/> </span> 

                  
                
                    

                      </td>
                 
                                    
                 
                 
                
                 
                  
                  <td> <span>  <strong>NA </strong><?php //=$arr['teacher']?> <br/> </span>  </td>

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

