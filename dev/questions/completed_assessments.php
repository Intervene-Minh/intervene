<?php
/****
 $start_date = date('Y-m-d h:i:s');
  @ # Students :teacher_id school_id grade_level_id class_id created
  Status 
  Teacher -
  Result
  # Students :teacher_id school_id grade_level_id class_id created
  @Completed Assessment   //
 * **/

 $page_name='Completed Assessments';
include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}


///@@@@@@@@@@@@
$data=array();// Listing array 
include("student_inc.php");
$msg='Pending ==pending_assessments.php';
 // print_($_SESSION);

// $sql="SELECT * FROM `teacher_x_assesments_x_students` WHERE `teacher_id` = '".$student_det['teacher_id']."' AND `student_id` =".$_SESSION['student_id'];
 # $studentId=$_SESSION['student_id'];
//$studentId=151; #testing

$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '$studentId' ";
// Completed
 $sql.=$change=" AND sa.status='Completed' ";

  // echo $sql; 
 // echo '<pre>', $sql;  die;
$result=mysql_query($sql);
$student_id=$_SESSION['student_id'];
while ($row= mysql_fetch_assoc($result)) {  
  $assessment_id=$row['assessment_id'];

  $student_scorre=mysql_fetch_assoc(mysql_query("SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, (
(
SUM( corrected ) / count( qn_id )
) *100
) AS percentage, student_id,teacher_id
FROM students_x_assesments
WHERE assessment_id =".$assessment_id."
AND student_id=".$student_id."
GROUP BY student_id"));  // round($arr['overall'],2)

   $row['per_scored']= round($student_scorre['percentage'],2); //$student_scorre['percentage'];
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}






/////////////Display
//echo $msg;
 //  echo '<pre>';
 // print_r($data);  die;
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
            <p class="text-success page-title"><?=$page_name?> </p>

          
    <form name="principal_action" id="principal_action" method="POST" action="">
        <table class="table table-hover">
            <tr>
                <th>Assessment</th>
                <th>Teacher</th>
                
               
                <th>Result</th>
            </tr>
            <?php
        
     
   
       $total_records=count($data); // assesment_name
       $i=0;
      foreach ($data as $key => $arr) {
        # code...
       //Only pending 
        //if($arr['status']!='In Progress')continue;
       // echo $arr['assesment_name'].'==<br/>';

           $start_btn=($arr['status']=="Assigned")?'START':'Resume';

  

              
        
          ?>
          <tr>
                <td>
                 <span>  <strong><?=$arr['assesment_name']?></strong> <br/> </span> 
                  <span style="display: none;">  <span class="btn btn-default btn-sm"><?=$arr['status']?></span> <br/> </span> 
                   </td>

                
                        <td>
                  <span>  <strong></strong><?=$arr['teacher']?> <br/> </span> 

                 
                 <!--  <span>  <strong>assesment_name: </strong><?=$arr['assesment_name']?> <br/> </span>  -->
                    

                      </td>
                 
                                    
                 
                 
                
                 
                  
                 

                  <td>
                     <?php if($arr['status']!="Completed"){  // Resume?> 
                 <a target="_blank" href="startnow_assessment.php?aid=<?=$arr['assessment_id']?>"
                   class="form_button submit_button"><?=$start_btn?></a> 

                      
                               
                        <?php }else echo 'Completed, <br/><strong>Score: </strong>'.$arr['per_scored'].'%'?>            
                
                   
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

