<?php
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
$created = date('Y-m-d H:i:s');
 
//echo '===';


/////////////////////////
$user_id = $_SESSION['login_id'];
$teacher=mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id` =".$user_id));
$t_school_id=$teacher['school'];
 //print_r($teacher) ; die;
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}

if($_POST['add_class']) {



    $student_res = mysql_query('SELECT student_id as id FROM students_x_class WHERE class_id =  \''.$_POST['class'].'\' ');
    


     $str='SELECT txaxs.student_id  FROM '
            . 'teacher_x_assesments_x_students txaxs LEFT JOIN students_x_class stu ON stu.student_id = txaxs.student_id WHERE '
            . 'txaxs.assessment_id = \'' . $_POST['assesment'] . '\' '
            . 'AND stu.class_id = \''.$_GET['cid'].'\' '
            . 'AND txaxs.school_id =\'' . $school_id . '\' '; 
   $number = mysql_num_rows(mysql_query($str));
    
    if($number <=0) {
    if(mysql_num_rows($student_res) > 0) {
        while ($student = mysql_fetch_assoc($student_res)) {

        $class_id= $_GET['cid'];
         if($_POST['assesment'] >0 ){ 
                        

                       $str= 'INSERT INTO teacher_x_assesments_x_students SET '
                        . 'teacher_id = \''.$user_id.'\' , '
                        . 'assessment_id = \''.$_POST['assesment'].'\' , '
                        . 'is_spanish = \''.$_POST['is_spanish'].'\' , '
                        . 'student_id = \''.$student['id'].'\' , '
                        . 'status = \'Assigned\' , '
                        . 'school_id = \''.$school_id.'\' , '
                        . 'class_id = \''.$class_id.'\' , '
                        . 'assigned_date = \''.$created.'\' ';
                        mysql_query($str);
        }
    }
        $error = 'Assessment has been assigned successfully.';
    }
    
        }
        else{
       $error = 'Assessment has already been assigned to class - please go to Assessment History tab to delete results or re-assign to students or class.';
    }
}

$res = mysql_query('SELECT class.*, t.name as grade_name FROM classes class LEFT JOIN terms t ON t.id = class.grade_level_id '
        . 'WHERE teacher_id = \'' . $user_id . '\' ');

if($_GET['cid']) {
    $class_res = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE teacher_id = \'' . $user_id . '\' AND id =  '.$_GET['cid']));
    if($class_res['grade_level_id'] > 0) {
        $school_result = mysql_fetch_assoc(mysql_query('SELECT * FROM schools WHERE SchoolId = \''.$school_id.'\' '));
        $master_school_id = $school_result['master_school_id'];
        $district_id = $school_result['district_id'];
       // $school_id=$t_school_id;// Teacher school
       
        // = '' OR ALL
        // level = School and then entity_id = school_id
        // level = district then entity_id = school_id mysql_query
        $asses_res =mysql_query('SELECT DISTINCT(ass.id), ass.is_spanish, ass.assesment_name, ass.created_by FROM assessments ass '
                . 'LEFT JOIN assessments_access access ON ass.id =  access.assessment_id '
                . 'WHERE '
                . 'ass.grade_id =\''.$class_res['grade_level_id'].'\' '
                . ' AND ass.created_by IN(0,'.$t_school_id.') AND ('
                . '(ass.access_level = "ALL" OR ass.access_level = ""  ) OR '
                . '(ass.access_level = "district" AND access.entity_id = \''.$district_id.'\' ) OR '
                . '(ass.access_level = "school" AND access.entity_id = \''.$master_school_id.'\' ) '
                . ') ');
        //echo '<pre>'.$asses_res; die;
        
   }
    
}

if($_POST['download_test'] && $_POST['assesment']) {


    $qn_res = mysql_query('SELECT qn_id ,num FROM assessments_x_questions WHERE
     assesment_id = \'' . $_POST['assesment'] . '\' ORDER BY num ASC');
    $_SESSION['list'] = array();
     while ($question = mysql_fetch_assoc($qn_res)) {

         $_SESSION['list'][] = $question['qn_id'];

          }

          //check if passage included or not  :: mysql_query
           $q_ids= implode(',', $_SESSION['list']);
           $str="SELECT DISTINCT `q`.`passage` , p . * 
             FROM `questions` q
            INNER JOIN `passages` p ON `q`.`passage` = `p`.`id`
             WHERE q.`id`
         IN (".$q_ids.")";
         $sql=mysql_query($str);
    if(mysql_num_rows($sql)>0)
        header("Location: inc/ajax-datadash-download.php");
     else  header("Location: inc/ajax-print.php?for=teacher"); 
     die();

}

/*download spanish test*/
if($_POST['download_spanish_test'] && $_POST['assesment']) {

    $qn_res = mysql_query('SELECT qn_id ,num FROM assessments_x_questions WHERE
     assesment_id = \'' . $_POST['assesment'] . '\' ORDER BY num ASC');
    $_SESSION['list'] = array();
     while ($question = mysql_fetch_assoc($qn_res)) {

         $_SESSION['list'][] = $question['qn_id'];

          }

          //check if passage included or not  :: mysql_query
           $q_ids= implode(',', $_SESSION['list']);
           $str="SELECT DISTINCT `q`.`passage` , p . * 
             FROM `questions` q
            INNER JOIN `passages` p ON `q`.`passage` = `p`.`id`
             WHERE q.`id`
         IN (".$q_ids.")";
         $sql=mysql_query($str);
    if(mysql_num_rows($sql)>0)


        header("Location: inc/ajax-datadash-download-spanish.php");
     else  header("Location: inc/ajax-print-spanish.php?for=teacher"); 
     die();

}
?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>Manage Assessments</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="post" action="" enctype="multipart/form-data">

                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Class</label>
                                    <select name="class" class="required textbox" onchange="open_asses('<?php print $base_url.'manage_assesment.php?cid='?>', $(this).val());">
                                        <option value="">Choose Class</option>
                                        <?php
                                        if (mysql_num_rows($res) > 0) {
                                            while ($result = mysql_fetch_assoc($res)) {
                                                $selected = ($result['id'] == $_GET['cid']) ? ' selected="selected"' : '';
                                                echo '<option value="' . $result['id'] . '"' . $selected . '>'.$result['grade_name'].' : ' . $result['class_name'] . '</option>';
//                                               
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <?php if(mysql_num_rows($asses_res)>0) { ?>
                                    <label for="lesson_name">Choose Assesments</label>
                                    <?php while ($assesments = mysql_fetch_assoc($asses_res)) {  // created_by
                                         $assesments['assesment_name']=($assesments['created_by']>0)?'Principal-created-'.$assesments['assesment_name']:$assesments['assesment_name'];   
                                         ?>
                                    <br/>
                                     <input type="radio" name="assesment" value="<?php print $assesments['id'] ?>" 
                                      is_spanish="0"  class="radioClass"/>
                                     <?php print $assesments['assesment_name'] ?>
                                     <?php 
                                     /* if spanish assessment available then show this */
                                        if($assesments['is_spanish']==1){?><br>
                                        <span style="margin-left: 15px">
                                            <input type="radio" name="assesment" value="<?php print $assesments['id'] ?>" is_spanish="1" class="radioClass"/>
                                            <input type="hidden" name="is_spanish" value="1"/>
                                             <?php print '<span style="color:red;">(Spanish)</span> ' .$assesments['assesment_name']; ?>
                                        </span>
                                    <?php
                                         }
                                              } } ?>
                                </p>
                            </div>
                            <p>
                                <input type="submit" name="add_class" id="lesson_submit" class="form_button submit_button" value="Assign" /> 
                                 </p>
                            <div style="float: right;">
                                <span>NOTE: To view the assessment</span>
                                <span class="loadButton">
                                    <input type="submit" name="download_test" id="lesson_submit" class="form_button submit_button" value="Download Test" />
                                </span>
                            </div>
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
<script type="text/javascript">

<?php if ($error != '') echo "alert('{$error}');"; ?>
/*jscript code*/
    $(function () {
        $('input[name="sudent_details"]').on('click', function () {
            if ($(this).val() == 'manual') {
                $('#textarea').show();
            }
            else {
                $('#textarea').hide();
            }
            if ($(this).val() == 'csv') {
                $('#csv-upload').show();
            }
            else {
                $('#csv-upload').hide();
            }
        });
    });


$('.radioClass').click(function(){


    var is_spanish =$(this).attr('is_spanish');

    //alert(is_spanish);

    if(is_spanish==1){

        $('.loadButton').html('<input type="submit" name="download_spanish_test" id="download_spanish_test" class="form_button submit_button" value="Download Test" />');
        console.log(is_spanish);
    }
    else{

        $('.loadButton').html('<input type="submit" name="download_test" id="lesson_submit" class="form_button submit_button" value="Download Test" />');
          console.log(is_spanish);
    }

});
</script>
<?php include("footer.php"); ?>


                           

                             