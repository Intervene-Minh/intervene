<?php
/****
 * @End Quiz by Students 
 * @ after 30 min.  from start time of session.
 * @ fromlisting QUIZ
 // student_id# schools_id# teacher_id==1
                //  int_slots_x_student_teacher
 
 * **/

include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}

 if(isset($_GET['id'])){
  $sesid=$_GET['id'];  

 }elseif($_POST['ses_id']) {

   $sesid=$_POST['ses_id'];

   // echo 'Session id='.$sesid; die; 

 }
 
          
                 
   ////////////////////
   if(isset($sesid)){
  unset($_SESSION['assessment']);
 }              




                $qq=" SELECT * FROM int_slots_x_student_teacher WHERE 1 ";
                $qq.=" AND student_id='".$_SESSION['student_id']."' ";
                $qq.=" AND slot_id='$sesid' ";
                $qq.=" AND quiz_status!='Completed' ";
                 $quizdet= mysql_fetch_assoc(mysql_query($qq));

               //echo  $qq; 
             //  echo $quizdet['quiz_id'] ;die; # 6

               // print_r($quizdet); die; 


?>
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
        <div class="row">
            <div class="align-center col-md-12">
                <?php
                
                /////// Quiz
                if ($quizdet['quiz_id'] > 0) {
                    // == int_quiz_x_questions
                    $sql = mysql_query('SELECT qn_id FROM int_quiz_x_questions WHERE '
                            . 'quiz_id = \'' . $quizdet['quiz_id'] . '\'');

                    $_SESSION['quiz_ses_type']=$quizdet['type'];
                     
                    $_SESSION['quizz'] = array(); // Quiz sesion
                    $_SESSION['quizz']['quiz_id'] = $quizdet['quiz_id'];
                    $_SESSION['quizz']['tses_id'] =$sesid;#Tutor_sessId
                    $_SESSION['quizz']['qn_list'] = array();
                    while ($question_ids = mysql_fetch_assoc($sql)) {
                        $_SESSION['quizz']['qn_list'][] = $question_ids['qn_id'];
                    }
                    
                    #   hcekckk quiz_id
                    $resume_result = mysql_fetch_assoc(mysql_query('SELECT MAX(num) as last_qn_num FROM students_x_quiz WHERE '
                                    . 'quiz_id = \'' . $quizdet['quiz_id'] . '\' AND '
                                    . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
                               
                                    . 'tutses_id = \'' . $sesid . '\' ')); ##XXX


                    if ($resume_result['last_qn_num'] > 0) { # RESUME Options option
                       #old# assesment_start.php?pos=
                        $redirect="quiz-start.php?pos=".$resume_result['last_qn_num'];

                                } else {  // Startt

                   $redirect="quiz-start.php";

                    }

                   header("Location:".$redirect);exit;
                 //// /////////////////////  
               } else {
                    ?>
                    <h1>There are no assigned test Quiz for you!</h1>
                    <?php
               }
                ?>




            </div>
        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush(); ?>

<?php //print_r($_SESSION); ?>

