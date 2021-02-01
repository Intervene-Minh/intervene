<?php
include("demostudent_header.php"); # student_header.php
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}
//print_r($_SESSION);
?>
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
        <div class="row">
            <div class="align-center col-md-12">
                <?php
                $assesment = mysql_fetch_assoc(mysql_query('SELECT assessment_id FROM demo_teacher_x_assesments_x_students WHERE '
                                . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
                                . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' AND status != "Completed" '));
                if ($assesment['assessment_id'] > 0) {
                    $sql = mysql_query('SELECT qn_id FROM assessments_x_questions WHERE '
                            . 'assesment_id = \'' . $assesment['assessment_id'] . '\'');
                    $_SESSION['assessment'] = array();
                    $_SESSION['assessment']['assesment_id'] = $assesment['assessment_id'];
                    $_SESSION['assessment']['qn_list'] = array();
                    while ($question_ids = mysql_fetch_assoc($sql)) {
                       
                       ///// Only Public Questions # Demo user
                 /*
                  *   $qdata= mysql_fetch_assoc(mysql_query("SELECT id,public FROM questions WHERE id='".$question_ids['qn_id']."' "));
                     if($qdata['public']==1)   
                       $_SESSION['assessment']['qn_list'][] = $question_ids['qn_id'];
                     else continue;
                  * **/
                      
                         /// Only Public Questions # Demo user
                        $_SESSION['assessment']['qn_list'][] = $question_ids['qn_id'];
                        
                        
                        
                    }
                    $resume_result = mysql_fetch_assoc(mysql_query('SELECT MAX(num) as last_qn_num FROM demo_students_x_assesments WHERE '
                                    . 'assessment_id = \'' . $assesment['assessment_id'] . '\' AND '
                                    . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
                                    . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' '));

                    if ($resume_result['last_qn_num'] > 0) {
                        ?>
                        <a href="demo_assesment_start.php?pos=<?php print $resume_result['last_qn_num']; ?>" class="btn btn-green"><button class="form_button submit_button" style="height: 200px;width: 200px;font-size: 30px;">Resume</button></a>
                    <?php } else { ?>
                        <a href="demo_assesment_start.php" class="btn btn-green"><button class="form_button submit_button" style="height: 200px;width: 200px;font-size: 30px;">Start</button></a>
                    <?php
                    }
                } else {
                    ?>
                    <h1>There are no assigned tests for you!</h1>
                    <?php
                }
                ?>  
            </div>
        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush(); ?>

