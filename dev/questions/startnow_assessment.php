<?php
include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');exit;
}
include("student_inc.php");
if(isset($_GET['aid'])){

  $curr_assessment_id=$_GET['aid'];

}

$sql=" SELECT * FROM teacher_x_assesments_x_students WHERE student_id = '".$_SESSION['student_id']."' 
AND school_id = '".$student_det['school_id']."'  AND status !='Completed' AND assessment_id='$curr_assessment_id' "; 
$assesment=mysql_fetch_assoc(mysql_query($sql));

//print_r($assesment);
if($assesment['status']=="Completed"){
  	exit('Completed Test');
  }         
?>
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
        <div class="row">
            <div class="align-center col-md-12">
              <div style=" width:auto;" title="">
               <?php
               if ($assesment['assessment_id'] > 0) {

               $str='SELECT qn_id ,num FROM assessments_x_questions WHERE '
                            . 'assesment_id = \'' . $assesment['assessment_id'] . '\'  ORDER BY num ASC';

                          
                $sql = mysql_query($str);
               
                    $_SESSION['assessment'] = array();
                    $_SESSION['assessment']['assesment_id'] = $assesment['assessment_id'];
                    $_SESSION['assessment']['qn_list'] = array();

                    while ($question_ids = mysql_fetch_assoc($sql)) {
                        $_SESSION['assessment']['qn_list'][] = $question_ids['qn_id'];

                         //$test[]= $question_ids['qn_id'];
                    }
                      $str='SELECT MAX(num) as last_qn_num FROM students_x_assesments WHERE '
                                    . 'assessment_id = \'' . $assesment['assessment_id'] . '\' AND '
                                     . 'teacher_id = \'' . $assesment['teacher_id'] . '\' AND '
                                    . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
                                    . 'school_id = \'' . $_SESSION['schools_id'] . '\' ';
                                     $resume_result = mysql_fetch_assoc(mysql_query($str));
                    if ($resume_result['last_qn_num'] > 0){
                      header("Location:assesment_start.php?pos=".$resume_result['last_qn_num']);exit;
                        ?>
						 
                        <a href="assesment_start.php?pos=<?php print $resume_result['last_qn_num']; ?>" class="btn btn-green"><button class="form_button submit_button" style="height: 200px;width: 200px;font-size: 30px;">Resume</button></a>
						
                    <?php } else { 
                    	header("Location:assesment_start.php");exit;	?>
					 
                        <a href="assesment_start.php" class="btn btn-green">
                            <button class="form_button submit_button" style="height: 200px;width: 200px; font-size: 30px;">Start</button></a>
                                                                                   
                    <?php 
                     } } else  echo  'Page not found!'; ?> </div> 
                      </div>
        </div>
    </div>
</div>
<?php ob_flush(); ?>