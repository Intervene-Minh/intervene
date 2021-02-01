<?php
/****
 $start_date = date('Y-m-d h:i:s');
 * @students
 * @students :: list 
 @ pending_assessments.php
 @ In Progress :: assessment of students 
 @ Assigned
 // print_($_SESSION);
// $sql="SELECT * FROM `teacher_x_assesments_x_students` WHERE `teacher_id` = '".$student_det['teacher_id']."' AND `student_id` =".$_SESSION['student_id'];
// $sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
// Left Join assessments a ON  sa.assessment_id=a.id
// WHERE sa.teacher_id = '365' AND sa.student_id= '10748' ";

  # Students :teacher_id school_id grade_level_id class_id created
  $student_school=$student_det['school_id'];
  $studentId=$_SESSION['student_id'];
 * **/

include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}
include("student_inc.php");

//////Page///////////


 $page_name='Pending Assessments List';
 $no_record='No record found.!';
$data=array();// Listing array 
$msg='Pending ==pending_assessments.php';



$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '$studentId' ";

$sql.=" AND sa.status!='Completed' ";

//echo $sql;

$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {  
 // $teacher=mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE id=".$student_det['teacher_id']));
   //$row['asssessment']='Demo asement';
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}

 //print_r($data);





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
   <p class="text-success page-title">Assigned Data Dash Tests</p>
          
    <form name="principal_action" id="principal_action" method="POST" action="">
        <table class="table table-hover">
            <tr>
                <th>Assessment</th>
                <th>Teacher</th>
               
               
                <th>&nbsp;</th>
            </tr>
            <?php
        
     
   
       $total_records=count($data); // assesment_name
       $i=0;
      foreach ($data as $key => $arr) {
        # code...
       //Only pending Completed
       //  if($arr['status']=='Completed')continue; // not Completed removed
      //  if($arr['status']!='In Progress')continue;
       // echo $arr['assesment_name'].'==<br/>';

           $start_btn=($arr['status']=="Assigned")?'START':'Resume';

  

                
        
          ?>
          <tr>
                <td>
                 <span>  <strong><?=$arr['assesment_name']?> </strong> <br/> </span> 
                   
        
              
                </td>

                
                        <td>
                  <span>  <strong></strong><?=$arr['teacher']?> <br/> </span> 
                         </td>

            

                     
                 
                                    
                 
                 
                
                 
                  
                 

                  <td>
                     <?php if($arr['status']!="Completed"){  // Resume?> 
                 <a  href="startnow_assessment.php?aid=<?=$arr['assessment_id']?>"
                   class="btn btn-primary btn-lg"><?=$start_btn?></a> 

                      
                               
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

