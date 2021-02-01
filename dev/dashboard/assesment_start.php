<?php include("student_header.php"); ?>
<?php
//print_r($_SESSION['assessment']);

///////////
 print_r($_SESSION);  die;
 $assesment_id = $_SESSION['assessment']['assesment_id']; 
$created = date('Y-m-d H:i:s');
$student_id = $_SESSION['student_id'];
$teacher_id = $_SESSION['teacher_id'];
$school_id = $_SESSION['schools_id'];
$class_id = $_SESSION['class_id'];
//die;
//ini_set('display_errors', 1);
if(!$assesment_id) {
    header('Location: student_assesments.php');
    exit;
}
mysql_query('UPDATE teacher_x_assesments_x_students SET status = "In Progress" WHERE '
        . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
        . 'school_id = \'' . $_SESSION['schools_id'] . '\' AND '
        . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' AND '
        . 'assessment_id = \''.$assesment_id.'\' ');

if ($_GET['pos']) {
    $qn_id = $_SESSION['assessment']['qn_list'][$_GET['pos']];
  //  echo $qn_id; die;
    $prev = ($_GET['pos'] - 1);
    $next = ($_GET['pos'] + 1);
} else {
    $qn_id = $_SESSION['assessment']['qn_list'][0];
    $prev = -1;
    $next = 1;
}
if(!$_GET['pos'] || $_GET['pos'] == 0) {
    $current_qn = 1;
}else{
    $current_qn = $_GET['pos'] + 1;
}
if($_POST['complete']) {
mysql_query('UPDATE teacher_x_assesments_x_students SET status = "Completed" , completion_date = \''.$created.'\' WHERE '
        . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
        . 'school_id = \'' . $_SESSION['schools_id'] . '\' AND '
        . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' AND '
        . 'assessment_id = \''.$assesment_id.'\' ');
$_SESSION = array();
unset($_SESSION);   
header('Location: login.php');
exit;
}
if ($_POST['next']) {
	
    if($_POST['answer'] == "0" || $_POST['answer'] > 0) { 
    $flip_array = array_flip($_SESSION['assessment']['qn_list']);
    $given_qn = $_POST['question_id']; // quedtion
    $answer = $_POST['answer']; //answer
    $gievn_questions = mysql_fetch_assoc(mysql_query("SELECT * FROM `questions` WHERE id = " . $given_qn));
    //print_r($gievn_questions); die;
    $gievn_questions_ans = unserialize($gievn_questions['answers']);
    $qn_num_result = mysql_fetch_assoc(mysql_query("SELECT num FROM `assessments_x_questions` WHERE qn_id = \"" . $given_qn."\" "
            . "AND assesment_id = \"".$assesment_id."\" "));
    $num = $qn_num_result['num'];
    $correct = $gievn_questions_ans[$answer]['corect']; // corrected
    $distractor = $gievn_questions_ans[$answer]['explain']; 
	$distractor = $distractor ? $distractor : 0;
    $pos = $flip_array[$given_qn] + 1;

    $gievn_questions = mysql_fetch_assoc(mysql_query("SELECT grade_id FROM `assessments` WHERE id = " . $assesment_id));
    $grade_id = $gievn_questions['grade_id'];
    mysql_query('DELETE FROM students_x_assesments WHERE student_id = \'' . $student_id . '\'  AND qn_id = \'' . $given_qn . '\'  AND assessment_id = \'' . $assesment_id . '\' AND teacher_id = \'' . $teacher_id . '\' ');
	
    mysql_query('INSERT INTO students_x_assesments SET '
            . 'teacher_id = \'' . $teacher_id . '\' , '
            . 'assessment_id = \'' . $assesment_id . '\' , '
            . 'student_id = \'' . $student_id . '\' , '
            . 'qn_id = \'' . $given_qn . '\' , '
            . 'num = \''.$num.'\' , '
            . 'answer = \'' . $answer . '\' , '
            . 'corrected = \'' . ($correct ? $correct : 0) . '\' , '
            . 'class_id = \'' . $class_id . '\' , '
            . 'distractor = \''.$distractor.'\' , '
            . 'school_id = \'' . $school_id . '\' , '
            . 'grade_id = \'' . $grade_id . '\' , '
            . 'created = \'' . $created . '\' ');
    //echo $correct;
    //print_r($gievn_questions_ans);  
    header('Location: assesment_start.php?pos=' . $next);
    exit;
    }else{
        $error = 'Please select an answer.';
    }
    
}

$stored_res = mysql_query('SELECT * FROM students_x_assesments WHERE '
        . 'student_id = \'' . $student_id . '\'  AND '
        . 'qn_id = \'' . $qn_id . '\'  '
        . 'AND assessment_id = \'' . $assesment_id . '\' '
        . 'AND teacher_id = \'' . $teacher_id . '\' ');
$stored_result = mysql_fetch_assoc($stored_res);
$childs = mysql_query("SELECT * FROM `questions` WHERE id = " . $qn_id);
   $res_passage=mysql_query("SELECT DISTINCT `q`.`passage` , `q`.`id` AS qid, p. *
FROM `questions` q INNER JOIN `passages` p ON `q`.`passage` = `p`.`id` WHERE q.`id`IN (".$qn_id.") ");
   //die; 
  $data=mysql_fetch_assoc($res_passage);
  //print_r($data);
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
                            if (mysql_num_rows($childs) > 0) {
                                echo '<ul class="ul-list">';

                               // if ($passage_id != 0) {
                                if (mysql_num_rows($res_passage) > 0) {
                                    echo '<h2 style="color: #1b64a9;">' . $data['title'] . '</h2>';
                                    echo $data['content'];
                                }

                                $i = 1;
                                $qtr = 0;
                                $alpha = array('a', 'b', 'c', 'd');
                                while ($item = mysql_fetch_assoc($childs)) {
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
                                            if($answer['image']==''){
                                            $echo .= ( $result == "" ) ? "" : '<p>'.strip_tags($answer['answer']).'</p>';
                                            }
                                            $echo .= ($answer['image'] != '') ? '<img src="' . $base_url . $answer['image'] . '"' . $width . $height . ' />' : '';
                                            /*
                                              if( $answer['explain'] != '' ) {
                                              $explain = mysql_fetch_assoc(mysql_query("SELECT `name` FROM `distrators` WHERE `id` = " . $answer['explain']));
                                              $echo .= $explain ? '<p><i>' . $explain['name'] . '</i></p>' : '';
                                              }
                                             */
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
                                            <a href="assesment_start.php?pos=<?php print $prev; ?>" class="form_button submit_button" style="background: red;display:inline-block; width: 50px; "  >Back</a>
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
                                    <a href="assesment_start.php" class="form_button submit_button"  style="background: red;padding: 5px;"  >Go Back and Check Work</a>
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