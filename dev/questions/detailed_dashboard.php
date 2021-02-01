<?php

/**@ detailed_dashboard
 * 
 * **/
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

@include("header.php");
$created = date('Y-m-d H:i:s');
ini_set('display_errors', 0);

$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
if ($_GET['cid']) {
    $class_res = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE teacher_id = \'' . $user_id . '\' AND id =  ' . $_GET['cid']));
   /* if ($class_res['grade_level_id'] > 0) {
        $asses_res = mysql_query('SELECT id, assesment_name FROM assessments WHERE grade_id =\'' . $class_res['grade_level_id'] . '\' ');
    }*/
     if($class_res['grade_level_id'] > 0) {
        $school_result = mysql_fetch_assoc(mysql_query('SELECT * FROM schools WHERE SchoolId = \''.$school_id.'\' '));
        $master_school_id = $school_result['master_school_id'];
        $district_id = $school_result['district_id'];
        
        // = '' OR ALL
        // level = School and then entity_id = school_id
        // level = district then entity_id = school_id
        $asses_res = mysql_query('SELECT DISTINCT(ass.id), ass.assesment_name FROM assessments ass '
                . 'LEFT JOIN assessments_access access ON ass.id =  access.assessment_id '
                . 'WHERE '
                . 'ass.grade_id =\''.$class_res['grade_level_id'].'\' '
                . 'AND ('
                . '(ass.access_level = "ALL" OR ass.access_level = ""  ) OR '
                . '(ass.access_level = "district" AND access.entity_id = \''.$district_id.'\' ) OR '
                . '(ass.access_level = "school" AND access.entity_id = \''.$master_school_id.'\' ) '
                . ') ');
        
   }
}

$res = mysql_query('SELECT class.*, t.name as grade_name FROM classes class LEFT JOIN terms t ON t.id = class.grade_level_id '
        . 'WHERE teacher_id = \'' . $user_id . '\' ');
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
                        <h3><i class="fa fa-plus-circle"></i>Data Detailed Report</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form target="_blank" name="form_class" id="form_class" method="get" action="detail_report.php" enctype="multipart/form-data">

                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Class</label>
                                    <select name="cid" class="required textbox" onchange="open_asses('<?php print $base_url . 'detailed_dashboard.php?cid=' ?>', $(this).val());">
                                        <option value="">Choose Class</option>
<?php
if (mysql_num_rows($res) > 0) {
    while ($result = mysql_fetch_assoc($res)) {
        $selected = ($result['id'] == $_GET['cid']) ? ' selected="selected"' : '';
        echo '<option value="' . $result['id'] . '"' . $selected . '>' . $result['grade_name'] . ' : ' . $result['class_name'] . '</option>';
//                                               
    }
}

?>
                                    </select>
                                </p>
                                <p>
<?php if (mysql_num_rows($asses_res) > 0) { ?>
                                        <label for="lesson_name">Choose Assesments</label>
                                        <?php while ($assesments = mysql_fetch_assoc($asses_res)) { ?>
                                            <br />
                                            <input type="radio" name="assesment" value="<?php print $assesments['id'] ?>" <?php if ($assesments['id'] == $_GET['assesment']) { ?> checked="" <?php } ?> /> <?php print $assesments['assesment_name'] ?>
                                        <?php
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                            <p>

                                <input type="submit" name="add_class" id="lesson_submit" class="form_button submit_button" style="width: 127px;" value="Get Detailed Report" />

                            </p>
                        </form>
                        <?php     ?>
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

</script>

<?php include("footer.php"); ?>
