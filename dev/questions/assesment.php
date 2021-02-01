<?php
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}



$error = '';
if (count($_SESSION['qn_list']) <= 0) {
    $error = 'Please select questions to create assesment!';
}
if ($_POST['assesment_submit']) {
    $error = '';
    if (count($_SESSION['qn_list']) <= 0) {
        $error = 'Please select questions to create assesment!';
    }
    
      
    
    ////// no err
    
    if (!$error) {
      
        $grade_id = $_POST['grade'];
        $query = mysql_query('SELECT name FROM terms WHERE taxonomy = "category" AND id = ' . $grade_id);
        $result = mysql_fetch_assoc($query);
        $grade_name = $result['name'];
        $assesment_name = $_POST['assesment_name'];
        $date = date('Y-m-d H:i:s');


        if (!isset($_POST['id'])) {
          // echo  'noerror'.$_POST['id']; die;
            
            
            if (count($_POST['district']) > 0 && count($_POST['master_school']) > 0) {
                $master_access_level = 'school';
            } else if (count($_POST['district']) > 0) {
                $master_access_level = 'district';
            } else {
                $master_access_level = 'ALL';
            }
            
           $for_demo=(isset($_POST['is_demo']))?'yes':'no';
            
            //
            mysql_query('INSERT INTO assessments SET '
                    . 'grade_id = \'' . $grade_id . '\' , '
                    . 'grade_level_name  = \'' . $grade_name . '\' , '
                    . 'access_level = \'' . $master_access_level . '\' , '
                    . 'assesment_name  = \'' . $assesment_name . '\' , '
                    . 'is_demo= \'' . $for_demo . '\' , '
                    . 'created = \'' . $date . '\' ')or die(mysql_error());


            $assesment_id = mysql_insert_id();
            if (count($_POST['district']) > 0) {
                for ($k = 0; $k < count($_POST['district']); $k++) {
                    mysql_query('INSERT INTO assessments_access SET '
                            . 'assessment_id  = \'' . $assesment_id . '\' , '
                            . 'access_level = \'district\' , '
                            . 'entity_id = \'' . $_POST['district'][$k] . '\' ');
                }
                for ($k = 0; $k < count($_POST['master_school']); $k++) {
                    mysql_query('INSERT INTO assessments_access SET '
                            . 'assessment_id  = \'' . $assesment_id . '\' , '
                            . 'access_level = \'school\' , '
                            . 'entity_id = \'' . $_POST['master_school'][$k] . '\' ');
                }
            }
        } else if ($_POST['id'] > 0) {
            // Addmore#edit
           // echo  '2nnoerror'.$_POST['id']; die;
            
            if (count($_POST['district']) > 0 && count($_POST['master_school']) > 0) {
                $master_access_level = 'school';
            } else if (count($_POST['district']) > 0) {
                $master_access_level = 'district';
            } else {
                $master_access_level = 'ALL';
            }

            mysql_query('UPDATE assessments SET '
//                    . 'grade_id = \'' . $grade_id . '\' , '
//                    . 'grade_level_name  = \'' . $grade_name . '\' , '
                    . 'assesment_name  = \'' . $assesment_name . '\' , '
                    . 'access_level = \'' . $master_access_level . '\' , '
                    . 'updated = \'' . $date . '\' WHERE id =  ' . $_POST['id']);
            $assesment_id = $_POST['id'];
            mysql_query('DELETE FROM assessments_access WHERE assessment_id  = \'' . $assesment_id . '\'  ');

            if (count($_POST['district']) > 0) {
                for ($k = 0; $k < count($_POST['district']); $k++) {
                    mysql_query('INSERT INTO assessments_access SET '
                            . 'assessment_id  = \'' . $assesment_id . '\' , '
                            . 'access_level = \'district\' , '
                            . 'entity_id = \'' . $_POST['district'][$k] . '\' ');
                }
                for ($k = 0; $k < count($_POST['master_school']); $k++) {
                    mysql_query('INSERT INTO assessments_access SET '
                            . 'assessment_id  = \'' . $assesment_id . '\' , '
                            . 'access_level = \'school\' , '
                            . 'entity_id = \'' . $_POST['master_school'][$k] . '\' ');
                }
            }

         $del=mysql_query('DELETE FROM assessments_x_questions WHERE assesment_id  = \'' . $assesment_id . '\'  ');
         if(isset($_SESSION['assess_id']))
         unset($_SESSION['assess_id']);//edit#addMore
         
                }
        // addQuesList
        $num = 1;
        for ($i = 0; $i < count($_SESSION['qn_list']); $i++) {
            $qn_id = $_SESSION['qn_list'][$i];

            mysql_query('INSERT INTO assessments_x_questions SET '
                    . 'qn_id = \'' . $qn_id . '\' , '
                    . 'assesment_id  = \'' . $assesment_id . '\' , '
                    . 'num = \'' . $num . '\' ');
            $num = $num + 1;
        }
        // addQuesList

        unset($_SESSION['list']);
        unset($_SESSION['qn_list']);
        header('Location: assesment_list.php');
        exit;
    }
}

// End Add 
$questions_list = array();
if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['id']);
    $assesment_result = mysql_fetch_assoc($qry);
    if ($_GET['cat'] > 0) {
        unset($assesment_result['grade_id']);
    }
    $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
            . 'LEFT JOIN assessments_x_questions axq ON axq.qn_id = qn.id WHERE '
            . 'axq.assesment_id = \'' . $_GET['id'] . '\' ');

    while ($question = mysql_fetch_assoc($qn_query)) {
        $questions_list[] = $question['id'];
    }
}

if ($_SESSION['assess_id'] > 0) {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_SESSION['assess_id']);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];
}

$district_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "district" ');
$assessment_district = array();
while ($district = mysql_fetch_assoc($district_level_res)) {
    $assessment_district[] = $district['entity_id'];
}
$school_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "school" ');
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
                        <h3><i class="fa fa-plus-circle"></i><?php echo $_GET['id'] > 0 ? 'Edit' : 'Add'; ?> Assesments</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4><?php echo ($_GET['id'] > 0 ? 'Edit' : 'Add new'); ?> Assessments here:</h4>
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Assessment Name</label>
                                    <input type="text" name="assesment_name" class="required textbox" value="<?php print $assesment_result['assesment_name']; ?>" />
                                </p>
                                <p>
                                    <input type="checkbox" name="is_demo" id="is_demo" style="vertical-align: sub;">
                                    <label for="is_demo">Is this Demo Assessment?  <em>(For Demo user)</em></label>
                                </p>


                                </div>

                            <div class="add_question_wrap clear fullwidth">
                                <?php if($a_id >0) {
                                    $folders = mysql_fetch_assoc(mysql_query("SELECT name FROM `terms` WHERE `taxonomy` = 'category' AND id =\"".$assesment_result['grade_id']."\" "));
                                    print '<b>Grade: </b>'.$folders['name'];
                                }else{ ?>
                                <p>
                                    <label for="lesson_name">Choose Grade</label>
                                    <?php
                                    if ($_GET['id'] > 0) {
                                        $id = $_GET['id'];
                                    } else {
                                        $id = 0;
                                    }
                                    ?>
                                    <select name="grade" class="required textbox" readonly="readonly" >
                                        <option value=""></option>
                                        <?php
                                        $grade_level_id = 0;
                                        if ($_GET['cat'] > 0) {
                                            $grade_level_id = $_GET['cat'];
                                        } else if ($assesment_result['grade_id'] > 0) {
                                            $grade_level_id = $assesment_result['grade_id'];
                                        }
                                        $folders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = 0 AND `active` = 1");
                                        if (mysql_num_rows($folders) > 0) {
                                            while ($folder = mysql_fetch_assoc($folders)) {
                                                $selected = ($folder['id'] == $_GET['cat'] || $folder['id'] == $assesment_result['grade_id'] ) ? ' selected="selected"' : '';
                                                echo '<option value="' . $folder['id'] . '"' . $selected . '>' . $folder['name'] . '</option>';
                                                $subfolders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = {$folder['id']} AND `active` = 1");
                                                if (mysql_num_rows($subfolders) > 0) {
                                                    while ($subfolder = mysql_fetch_assoc($subfolders)) {
                                                        if ($_GET['cat'] <= 0 && $grade_level_id == 0) {
                                                            $grade_level_id = $subfolder['id'];
                                                        }
                                                        $selected = ($subfolder['id'] == $_GET['cat'] || $subfolder['id'] == $assesment_result['grade_id']) ? ' selected="selected"' : '';
                                                        echo '<option value="' . $subfolder['id'] . '" class="subfolder"' . $selected . '>' . $subfolder['name'] . '</option>';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
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
                                <?php if ($_SESSION['assess_id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_SESSION['assess_id']; ?>" >
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