<?php
/***
@Demo assessment only 


**/


$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
$created = date('Y-m-d H:i:s');

$user_id = $_SESSION['demo_user_id'];
//$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
//$rows = mysql_num_rows($query);
//if ($rows == 1) {
//    $row = mysql_fetch_assoc($query);
//    $school_id = $row['school'];
//}
$school_id = $_SESSION['school_id'];
$teacher_class = mysql_query('SELECT class_name, id FROM demo_classes WHERE teacher_id = \'' . $user_id . '\' ');

if ($_POST) {

    $assesment_id = implode(', ', $_POST['re_assign_student']);

    mysql_query('DELETE FROM demo_teacher_x_assesments_x_students WHERE  '
            . 'teacher_id = \'' . $user_id . '\' AND '
            . 'assessment_id = \'' . $_POST['assesment'] . '\' AND '
            . 'student_id IN (' . $assesment_id . ')  ');
    
    mysql_query('DELETE FROM demo_students_x_assesments WHERE  '
            . 'teacher_id = \'' . $user_id . '\' AND '
            . 'assessment_id = \'' . $_POST['assesment'] . '\' AND '
            . 'student_id IN (' . $assesment_id . ')  ');
    for ($i = 0; $i < count($_POST['re_assign_student']); $i++) {
        mysql_query('INSERT INTO demo_teacher_x_assesments_x_students SET '
                . 'teacher_id = \'' . $user_id . '\' , '
                . 'assessment_id = \'' . $_POST['assesment'] . '\' , '
                . 'student_id = \'' . $_POST['re_assign_student'][$i] . '\' , '
                . 'status = \'Assigned\' , '
                . 'assigned_date = \'' . $created . '\' ');
        $error = "Successfully Reassigned";
    }

    if (count($_POST['re_assign_student']) <= 0) {
        $error = "Either there is no assignment chosen or any student is selected for the reassign!";
    }
}

$teacher_grade_res = mysql_query("
	SELECT  GROUP_CONCAT( grade_level_id SEPARATOR ',' ) AS shared_terms
	FROM `techer_permissions`
	WHERE teacher_id = {$user_id} 
");
$t_grades = mysql_fetch_assoc($teacher_grade_res);
$teacher_grade = $t_grades['shared_terms'];

if ($_GET['cid'] > 0) {
    $class_grade = mysql_fetch_assoc(mysql_query('SELECT grade_level_id FROM demo_classes WHERE id = \'' . $_GET['cid'] . '\' '));
    $grade_id = $class_grade['grade_level_id'];
}




$sql_asses_res=" SELECT DISTINCT(ass.id), ass.assesment_name FROM assessments ass LEFT JOIN assessments_access access ON ass.id = access.assessment_id WHERE ass.grade_id =".$grade_id;
$sql_asses_res.=" AND ass.is_demo='yes' ";
$asses_res=mysql_query($sql_asses_res);


 //echo $sql_asses_res;  die; //mysql_query


//$asses_res = mysql_query('SELECT id, assesment_name FROM assessments WHERE grade_id  IN (' . $grade_id . ') ');

if ($_GET['assesment_id']) {

    $assesment_student = mysql_query('SELECT txaxs.student_id, CONCAT(stu.first_name ," ", stu.middle_name ," ", stu.last_name ) as stu_name, txaxs.status FROM '
            . 'demo_teacher_x_assesments_x_students txaxs LEFT JOIN demo_students stu ON stu.id = txaxs.student_id WHERE '
            . 'txaxs.assessment_id = \'' . $_GET['assesment_id'] . '\' '
            . 'AND stu.class_id = \'' . $_GET['cid'] . '\' '
            . 'AND txaxs.teacher_id = \'' . $user_id . '\' ');
}
?>
<style>table, th, td {
        border: 1px solid black;
    }
    td{padding: 5px;}
</style>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("demo_sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>Assessment History</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="post" action="" enctype="multipart/form-data">

                            <div class="add_question_wrap clear fullwidth">
                                <p> <label for="lesson_name">Class List</label><select name="cid" class="form-control" onchange="open_asses('<?php print $base_url . 'demo_assesment_history.php?cid=' ?>', $(this).val());">
                                        <option value="">Choose Class</option>
                                        <?php
                                        while ($cls = mysql_fetch_assoc($teacher_class)) {
                                            $select = (isset($_GET['cid']) && $_GET['cid'] == $cls['id']) ? 'selected' : '';
                                            echo "<option value='{$cls['id']}' {$select}>{$cls['class_name']}</option>";
                                        }
                                        ?>

                                    </select></p>
                                <p>
                                    <label for="lesson_name">Assessment List</label>
                                    <select name="assesment" class="required textbox" onchange="open_asses('<?php print $base_url . 'demo_assesment_history.php?cid=' . $_GET['cid'] . '&assesment_id=' ?>', $(this).val());">
                                        <option value="">Choose Assesment</option>
                                        <?php
                                        if (mysql_num_rows($asses_res) > 0) {
                                            while ($result = mysql_fetch_assoc($asses_res)) {

                                                $selected = ($result['id'] == $_GET['assesment_id']) ? ' selected="selected"' : '';
                                                echo '<option value="' . $result['id'] . '"' . $selected . '>' . $result['assesment_name'] . '</option>';
//                                               
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <?php if (!isset($_GET['assesment_id'])) { ?>
                                        Choose Assesment to populate Student List
                                        <?php } if (mysql_num_rows($assesment_student) > 0) {
                                        ?>

                                    <table width="100%" style="background: #fff; padding: 10px;border-collapse: collapse;"> 

                                        <tr >
                                            <td>&nbsp;&nbsp;<input type="checkbox" id="ckbCheckAll" /></td><td> <b>Student Name</b></td><td><b>Status</b></td><td><b>Score</b></td></tr>
                                        <?php
                                        while ($stu_data = mysql_fetch_assoc($assesment_student)) {
                                            if (trim($stu_data['stu_name']) != '') {
                                                ?>
                                                <tr><td>
                                                        &nbsp;&nbsp;<input type="checkbox" class="checkBoxClass" name="re_assign_student[]" value="<?php print $stu_data['student_id']; ?>"> 
                                                    </td><td>
            <?php print $stu_data['stu_name']; ?> 
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($stu_data['status'] == 'Asigned') {
                                                            print 'Assigned Not Started';
                                                        } else if ($stu_data['status'] == 'In Progress') {
                                                            print 'Started - Incomplete';
                                                        } else {
                                                            print $stu_data['status'];
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $score_res = mysql_fetch_assoc(mysql_query('SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, ((SUM( corrected ) / count( qn_id )) *100) AS percentage, student_id
FROM demo_students_x_assesments
WHERE assessment_id =\'' . $_GET['assesment_id'] . '\' AND class_id = \'' . $_GET['cid'] . '\'  AND student_id = \'' . $stu_data['student_id'] . '\'
'));
                                                        print $per = $score_res['percentage'] ? round($score_res['percentage']) . ' %' : 'n/a';
                                                        ?>
                                                    </td>
                                                </tr>

                                                <?php
                                            }
                                        }
                                    } else {
                                        ?>
                                        You have not assigned this assesment to any student.
                                    <?php } ?>
                                </table>
                            </div>
                            <p>
                                <?php if ($_GET['assesment_id'] > 0) { ?>
                                    <input type="submit" name="add_class" id="lesson_submit" style="width: 200px;"  class="form_button submit_button" value="Delete and Restart Assesment" />
                                <?php } ?>

                            </p>
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
<?php if ($error != '') echo "alert('{$error}')"; ?>

    $(function () {
        $("#ckbCheckAll").click(function () {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });
        $('input[name="sudent_details"]').on('click', function () {
            if ($(this).val() == 'manual') {
                $('#textarea').show();
            } else {
                $('#textarea').hide();
            }
            if ($(this).val() == 'csv') {
                $('#csv-upload').show();
            } else {
                $('#csv-upload').hide();
            }
        });
    });

</script>
<?php include("footer.php"); ?>
