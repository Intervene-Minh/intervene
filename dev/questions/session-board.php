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
 * **/
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');

include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}
  $board_url='session-board.php';
  $actvity_url="actvity.php";


  $page_name='Session Board';
 $no_record='No record found.!';

///@@@@@@@@@@@@
$data=array();// Listing array 
include("student_inc.php");
$msg='Pending ==pending_assessments.php';
 // print_($_SESSION);
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
                 <?php //include("nav_students.php"); ?>
       

        
                
                      </div>    





            </div>
            <?php 

        $student_board='https://api.braincert.com/html5/build/whiteboard.php?token=LOjC4YNSt9hYAzFPT7EZLlYvGrstJBA--2BzToB2SSuu--2F--2F7aioQShtW6vxhKna7s7nM4zREhEEdkN7KCVZORRz--2Bm170YFVWCTUwf9subTRJki4CPOb3OS7X4K--2Bm--2FvgDJx5PB--2Fe3aLxNbyOfmfqtIG5mK9yXMZBZPiAuJfqmIxyYeULx--2BM1Bduoq0h7Dnj--2FZCW0YDJyM--2Fh2UP2haVgdtwF0YLw--3D--3D';
            ?>
           <!--  NExt col -->
           <div class="align-center col-md-12">
<!--                <p class="text-success">Session list of students..  </p>-->

          <form name="principal_action" id="principal_action" method="POST" action="">
        
        <iframe  style="width: 100%; height: 600px;" src="<?=$student_board?>">
            </iframe>

        
        
        
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

