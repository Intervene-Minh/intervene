<?php
/****
 * Duplicate a Quiz
 * - Add questions
 * ****/

$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}
$quizz_id= $_GET['quiz_id'];

//$error = '';

if ($_POST['assesment_submit']) {
    //print_r($_POST); die;
        $lessonid= $_POST['grade'];
        
        
        $query = mysql_query('SELECT name FROM lessons WHERE id = ' . $lessonid);
        
        
        $result = mysql_fetch_assoc($query);
        //////// Grade=> lesName , les ID
        
        $grade_name = $result['name'];
        $qzz_name= $_POST['quiz_name'];
        $date = date('Y-m-d H:i:s');
        if ($_POST['id']) {
           // print_r($_POST); die;
            if (count($_POST['district']) > 0 && count($_POST['master_school']) > 0) {
                $master_access_level = 'school';
            } else if (count($_POST['district']) > 0) {
                $master_access_level = 'district';
            } else {
                $master_access_level = 'ALL';
            }
           
            mysql_query('INSERT INTO int_quiz SET '
                    . 'lesson_id = \'' . $lessonid . '\' , '
                    . 'lesson_name  = \'' . $grade_name . '\' , '
                    . 'access_level = \'' . $master_access_level . '\' , '
                    . 'quiz_name  = \'' . $qzz_name . '\' , '
                    . 'created = \'' . $date . '\' ');
            $assesment_id = mysql_insert_id();
            if (count($_POST['district']) > 0) {
                for ($k = 0; $k < count($_POST['district']); $k++) {
                    mysql_query('INSERT INTO int_quiz_access SET '
                            . 'quiz_id  = \'' . $assesment_id . '\' , '
                            . 'access_level = \'district\' , '
                            . 'entity_id = \'' . $_POST['district'][$k] . '\' ');
                }
                for ($k = 0; $k < count($_POST['master_school']); $k++) {
                    mysql_query('INSERT INTO int_quiz_access SET '
                            . 'quiz_id  = \'' . $assesment_id . '\' , '
                            . 'access_level = \'school\' , '
                            . 'entity_id = \'' . $_POST['master_school'][$k] . '\' ');
                }
            }
        }
        if($_GET['quiz_id'] > 0) {
            $assement_qn_list = array();
            $edit_assessment = mysql_query('SELECT qn_id  FROM int_quiz_x_questions WHERE quiz_id = \''.$_GET['quiz_id'].'\' ORDER BY num ASC ');
            while($asses = mysql_fetch_assoc($edit_assessment)) {
                $assement_qn_list[] = $asses['qn_id'];
            }
        }
        
        $num = 1;
        for ($i = 0; $i < count($assement_qn_list); $i++) {
            $qn_id = $assement_qn_list[$i];

            mysql_query('INSERT INTO int_quiz_x_questions SET '
                    . 'qn_id = \'' . $qn_id . '\' , '
                    . 'quiz_id  = \'' . $assesment_id . '\' , '
                    . 'num = \'' . $num . '\' ');
            $num = $num + 1;
        }

        header('Location:quiz_list.php');
        exit;
    
}

//  End Duplicate

if ($quizz_id > 0) {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $quizz_id);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $quizz_id;
}

$district_level_res = mysql_query('SELECT entity_id FROM int_quiz_access WHERE quiz_id = \'' . $a_id . '\' AND access_level = "district" ');
$assessment_district = array();
while ($district = mysql_fetch_assoc($district_level_res)) {
    $assessment_district[] = $district['entity_id'];
}
$school_level_res = mysql_query('SELECT entity_id FROM int_quiz_access WHERE quiz_id = \'' . $a_id . '\' AND access_level = "school" ');
$assessment_school = array();
while ($school = mysql_fetch_assoc($school_level_res)) {
    $assessment_school[] = $school['entity_id'];
}

$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#district').chosen();

        $('#district').change(function () {
            district = $(this).val();

            $('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', school_id: '<?php print implode(',', $assessment_school); ?>'},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();
    });
</script>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i><?php echo $_GET['id'] > 0 ? 'Edit' : 'Duplicate'; ?> Quiz</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4><?php echo ($_GET['id'] > 0 ? 'Edit' : 'Add Duplicate'); ?> Quiz here:</h4>
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Quiz Name</label>
                                    <input type="text" name="quiz_name" class="required textbox" value="<?php print 'Duplicate:'.' '.$assesment_result['quiz_name']; ?>" />
                                </p></div>

                            <div class="add_question_wrap clear fullwidth">
                                <?php if($a_id >0) {
                                    $folders = mysql_fetch_assoc(mysql_query("SELECT name FROM `terms` WHERE `taxonomy` = 'category' AND id =\"".$assesment_result['grade_id']."\" "));
                                    print '<b>Grade: </b>'.$folders['name']; ?>
                                <input type="hidden" name="grade" value="<?php echo $assesment_result['lesson_id']; ?>">
                               <?php } ?>
                            </div>
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Choose District:</label><br />
                                    <select name="district[]" id="district" multiple="true">
                                        <?php while ($district = mysql_fetch_assoc($district_qry)) { ?>
                                            <option <?php if (in_array($district['id'], $assessment_district)) { ?> selected="selected" <?php } ?> value="<?php print $district['id']; ?>"><?php print $district['district_name']; ?></option>

<?php } ?>
                                    </select>

                                </p>
                            </div>
                            <div class="add_question_wrap clear fullwidth">
                                <div id="district_schools">

                                    <label for="lesson_name">Choose Schools:</label>
                                    <div id="district_school">
                                        Select District to choose schools.
                                    </div>

                                </div>
                            </div>
                            <p>
                                <input type="submit" name="assesment_submit" id="lesson_submit" class="form_button submit_button" value="Submit" />
                                <?php if ($_GET['id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>" >
                                <?php } ?>
                                <?php if ($quizz_id > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $quizz_id; ?>" >
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
</script>
<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>