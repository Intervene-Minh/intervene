<?php 
include("student_header.php"); 
$qzz_id = $_SESSION['quizz']['quiz_id'];
$tut_ses_id= $_SESSION['quizz']['tses_id'];# mange QuizRow::
$created = date('Y-m-d H:i:s');
$student_id = $_SESSION['student_id']; // student_id

$student_row=mysql_fetch_assoc(mysql_query(" SELECT * FROM students WHERE id=".$student_id));  // 


$ses_det= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_schools_x_sessions_log` WHERE id = " .$tut_ses_id));
$teacher_id =$ses_det['teacher_id'];
$school_id = $ses_det['school_id'];
$class_id = $ses_det['class_id'];

/////////////////////
$qq=('UPDATE int_slots_x_student_teacher SET status = "In Progress" WHERE '
        . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
        . 'school_id = \'' . $_SESSION['schools_id'] . '\' AND '
        . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' AND '
        . 'quiz_id = \''.$qzz_id.'\' ');

mysql_query(" UPDATE int_slots_x_student_teacher SET quiz_status='In Progress' WHERE slot_id='$tut_ses_id' AND "
        . "student_id='$student_id' ");
// Update status
//////////
if ($_GET['pos']) {
    $qn_id = $_SESSION['quizz']['qn_list'][$_GET['pos']];
  //  echo $qn_id; die;
    $prev = ($_GET['pos'] - 1);
    $next = ($_GET['pos'] + 1);
} else {
    $qn_id = $_SESSION['quizz']['qn_list'][0];
    $prev = -1;
    $next = 1;
}
if(!$_GET['pos'] || $_GET['pos'] == 0) {
    $current_qn = 1;
}else{
    $current_qn = $_GET['pos'] + 1;
}
//////////////

//////complete///////OKK
if($_POST['complete']) 
{
        
        mysql_query('UPDATE int_slots_x_student_teacher SET quiz_status= "Completed" , completion_date = \''.$created.'\' WHERE '
        . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '

        . 'slot_id= \''.$tut_ses_id.'\' ')or die(mysql_error());

        header('Location:https://intervene.io/questions/welcome.php');
        exit;

}
//////next///////
if ($_POST['next'])
{
	
    if($_POST['answer'] == "0" || $_POST['answer'] > 0) { 
    $flip_array = array_flip($_SESSION['quizz']['qn_list']);
    $given_qn = $_POST['question_id']; // quedtion
    $answer = $_POST['answer']; //answer
    $gievn_questions = mysql_fetch_assoc(mysql_query("SELECT * FROM `questions` WHERE id = " . $given_qn));
    //print_r($gievn_questions); die;
    $gievn_questions_ans = unserialize($gievn_questions['answers']);
    $qn_num_result = mysql_fetch_assoc(mysql_query("SELECT num FROM `int_quiz_x_questions` WHERE qn_id = \"" . $given_qn."\" "
            . "AND quiz_id = \"".$qzz_id."\" "));
    $num = $qn_num_result['num'];
    //$num=99; //
    $correct = $gievn_questions_ans[$answer]['corect']; // corrected
    $distractor = $gievn_questions_ans[$answer]['explain']; 
	$distractor = $distractor ? $distractor : 0;
    $pos = $flip_array[$given_qn] + 1;
    $gievn_questions = mysql_fetch_assoc(mysql_query("SELECT grade_id FROM `int_quiz` WHERE id = " . $qzz_id));
    $grade_id = $gievn_questions['grade_id'];
    
   //   delete quesiont
    mysql_query('DELETE FROM students_x_quiz WHERE student_id = \'' . $student_id . '\'  AND qn_id = \'' . $given_qn . '\'  AND quiz_id = \'' . $qzz_id . '\' AND tutses_id = \'' . $tut_ses_id . '\' ')or die(mysql_error());
    //$class_id=99;  // quiz_ses_type
    $type=$_SESSION['quiz_ses_type'];
    $stu_teacher_id=$student_row['teacher_id']; //class_id
    $stu_class_id=$student_row['class_id'];
     //$type='intervention';
    mysql_query('INSERT INTO students_x_quiz SET '
            . 'teacher_id = \'' . $stu_teacher_id . '\' , '
            . 'quiz_id = \'' . $qzz_id . '\' ,tutses_id= \'' . $tut_ses_id . '\' , '
            . 'student_id = \'' . $student_id . '\' , '
            . 'qn_id = \'' . $given_qn . '\' , '
            . 'num = \''.$num.'\' , '
            . 'answer = \'' . $answer . '\' , '
            . 'corrected = \'' . ($correct ? $correct : 0) . '\' , '
            . 'class_id = \'' . $stu_class_id . '\' , '
            . 'distractor = \''.$distractor.'\' , '
            . 'school_id = \'' . $school_id . '\' , '
            . 'grade_id = \'' . $grade_id . '\' , '
            . 'type = \'' . $type . '\' , '
            . 'created = \'' . $created . '\' ')or die(mysql_error());
    /// Mulit student attempt same quies, by quiz id
    //echo $correct;
    //print_r($gievn_questions_ans);  
    header('Location: quiz-start.php?pos=' . $next);exit;

    }else{
        $error = 'Please select an answer.';
    }
    
}
//////next///////
// get question Result ::
$stored_res = ('SELECT * FROM students_x_quiz WHERE '
        . 'student_id = \'' . $student_id . '\'  AND '
        . 'qn_id = \'' . $qn_id . '\'  '
        . 'AND quiz_id = \'' . $qzz_id . '\' '
        . 'AND teacher_id = \'' . $teacher_id . '\' ');

$stored_res = mysql_query('SELECT * FROM students_x_quiz WHERE '
        . 'student_id = \'' . $student_id . '\'  AND '
        . 'qn_id = \'' . $qn_id . '\'  '
        . 'AND quiz_id = \'' . $qzz_id . '\' '
        . 'AND tutses_id = \'' . $tut_ses_id . '\' ');

$stored_result = mysql_fetch_assoc($stored_res);
///  Get questions
$childs = mysql_query("SELECT * FROM `questions` WHERE id = " . $qn_id);


?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div  class="col-md-3"></div>
            <!-- /#sidebar -->
            <div id="content" class="col-md-12">
                <div id="single_question" class="content_wrap">

                    <div class="ct_display clear">                   
                        <form method="post">
                            <?php
                            if (mysql_num_rows($childs) > 0)
                            {
                                echo '<ul class="ul-list">';
                                $i = 1;
                                $qtr = 0;
                                $alpha = array('a', 'b', 'c', 'd');
                                while ($item = mysql_fetch_assoc($childs)) {
                                    $passage_id=$item['passage'];
                                    if($passage_id > 0){
                                        $result_passage = mysql_query("SELECT * FROM `passages` p WHERE `id` = $passage_id ORDER BY `date_created` DESC");
                                         $this_passage = mysql_fetch_assoc($result_passage);
                                          echo '<h2>' . $this_passage['title'] . '</h2>';
                                          echo $this_passage['content'];

                                     }
                                    if ($item['type'] == 1) {
                                        //$echo = '<ul class="list-answers">';
                                        //lv-edit 04/05/2016
                                        $lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers']);
                                        $answers = unserialize($lv_answers);
                                        // $answers = unserialize($item['answers']);
                                        $ans = 0;
                                        //end
                                        foreach ($answers as $key => $answer) {
                                            $converted = strtr($answer['answer'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
                                            $clear = strip_tags($converted);
                                            $result = trim($clear, chr(0xC2) . chr(0xA0));
                                            $result = trim($result);
                                            //lv-edit-2
                                            $answer['answer'] = str_replace('\"', '"', $answer['answer']);
                                            //end
                                            $width = ( isset($answer['width']) && $answer['width'] != "" ) ? " width='" . $answer['width'] . "'" : "";
                                            $height = ( isset($answer['height']) && $answer['height'] != "" ) ? " height='" . $answer['height'] . "'" : "";
                                            if($qtr %2 == 0) {
                                              $echo .= '<ul class="list-answers col-md-6">';  
                                            }
                                            $qtr = $qtr + 1;
                                            $echo .= ($key % 2 == 1) ? '<li class="col-right">' : '<li>';
                                            $echo .= $alpha[$key].'.  &nbsp;&nbsp;&nbsp;';
                                            if (mysql_num_rows($stored_res) > 0) {

                                                $sel = ($stored_result['answer'] == $ans) ? 'checked="checked"' : '';

                                                $echo .= '<input type="radio" name="answer" value="' . $ans . '" ' . $sel . '  />';
                                            } else {
                                                $echo .= '<input type="radio" name="answer" value="' . $ans . '"  />';
                                            }

                                            $ans = $ans + 1;
                                            $echo .= ( $result == "" ) ? "" : $answer['answer'];
                                            $echo .= ($answer['image'] != '') ? '<p><img src="' . $base_url . $answer['image'] . '"' . $width . $height . ' /></p>' : '';
                                            $echo .= ($key % 2 == 1) ? '</li><div class="clearnone">&nbsp;</div>' : '</li>';
                                            if($qtr %2 == 0) {
                                                $echo .= '</ul>';
                                            }
                                        }
                                        //$echo .= '</ul>';
                                    } else {
                                        $echo = $item['answers'];
                                    }
                                    ?>
                                                <li style="border: none;list-style:none;">
                                                <div class="ques-text">
                                                <?php echo '<div style="float:left;margin-right:10px;">'.$current_qn.'. </div><div> '.$item['question']; ?></div>

                                                <?php echo $echo; ?>
                                                </div>
                                                <div class="ques-button" style="border: none; ">
                                                <input type="hidden" name="question_id" value="<?php print $qn_id; ?>" />
                                                <?php if ($prev != -1) { ?>
                                                <a href="quiz-start.php?pos=<?php print $prev; ?>" class="form_button submit_button" style="background: red;display:inline-block; width: 50px; "  >Back</a>
                                                <?php } ?>
                                                <input type="submit" class="form_button submit_button" name="next" style="background: green; " value="Next" >

                                                </div>

                                                </li>
                                    <?php
                                    $i++;
                                }
                                echo '</ul>';
                            } else {
                                ?>
                                    <div style="text-align: center;">
                                    <h1>You've Answered Every Question!</h1>
                                    <a href="quiz-start.php" class="form_button submit_button"  style="background: red;padding: 5px;"  >Go Back and Check Work</a>
                                     <br/><br/>
                                        <input type="submit" class="form_button submit_button" style="background: green;" name="complete" value="I'm done!" >
                                    </div>
                                    <?php 
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>