<?php
// Add Quiz|edit|duplicat
/****
Quiz: step 1
1. Choose Grade
2 . select lesson  ONLY
$step_1_url="quiz_step1.php";

$step_2_url="quiz_step2.php";

$step_3_url="quiz_step2_new.php";
$step_4_url="quiz_step3.php";// Arrange Questions

**/
 //echo 'Step 1';
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

$error = '';

// // not required
// if (count($_SESSION['qn_list']) <= 0) {
//     $error = 'Please select questions to create assesment!';
// }


///Postto Choose Folder//
 
    if(isset($_POST['qz_submit'])) {

        $_SESSION['ses_quiz_grade']=$_POST['grade'];
        $_SESSION['ses_quiz_lesson']=$_POST['lesson'];  // lesson
      
        $les=mysql_fetch_assoc(mysql_query("SELECT id,objective_id FROM `master_lessons` WHERE id=".$_POST['lesson']));
       
        $_SESSION['ses_quiz_objective']=$les['objective_id'];  // objective_id
        $step_2_url="quiz_step2.php?taxonomy=".$_POST['grade'];
         header('Location:'.$step_2_url);  exit;
}
// Add Quiz

$questions_list = array();
if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['id']);
    $result_qz= mysql_fetch_assoc($qry);
    if ($_GET['cat'] > 0) {
        unset($result_qz['objective_id']);
    }
    $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
            . 'LEFT JOIN int_quiz_x_questions axq ON axq.qn_id = qn.id WHERE '
            . 'axq.quiz_id= \'' . $_GET['id'] . '\' ');

    while ($question = mysql_fetch_assoc($qn_query)) {
        $questions_list[] = $question['id'];
    }
}

if ($_SESSION['assess_id'] > 0) {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_SESSION['assess_id']);
    $result_qz= mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];
}

$district_level_res = mysql_query('SELECT entity_id FROM int_quiz_access WHERE quiz_id= \'' . $a_id . '\' AND access_level = "district" ');
$assessment_district = array();
while ($district = mysql_fetch_assoc($district_level_res)) {
    $assessment_district[] = $district['entity_id'];
}
$school_level_res = mysql_query('SELECT entity_id FROM int_quiz_access WHERE quiz_id= \'' . $a_id . '\' AND access_level = "school" ');
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
                data: {district: district, action: 'get_multiple_schools', school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();
    });
    
    ////////////////
     $(document).ready(function () {

        $('#districtqzz').chosen();

        $('#districtqzz').change(function () {
            district = $(this).val();

            //$('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', 
                    school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#districtqzz').change();
    });
    
   ///22
   $(document).ready(function () {

        $('#distric444').chosen();

        $('#distric444').change(function () {
            district = $(this).val();

            //$('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', 
                    school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#distric444').change();
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
                        <h3><i class="fa fa-plus-circle"></i><?php echo $_GET['id'] > 0 ? 'Edit' : 'Add'; ?> Quiz</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <p class="text-d"><?php echo (isset($error)&&!empty($error))?$error:NULL?></p>
                            
                            <h4>+add an Quiz:</h4>
                            
                            
                             <div class="add_question_wrap clear fullwidth">
                               
                                 
                                <p>
                                    <label for="lesson_name">Choose Grade</label>
                                    <?php
                                    if ($_GET['id'] > 0) {
                                        $id = $_GET['id'];
                                    } else {
                                        $id = 0;
                                    }
                                    ?>
                                    <select name="grade" id="districtqzz" class="required textbox" required="" readonly="readonly" >
                                        <option value="-1"></option>
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
                              
                            </div>
                            
                            
                            
                            

                            <div class="add_question_wrap clear fullwidth">
                                
                                
                                
                               
                                
                                
                                <p>
                                <label for="lesson_name">Choose Lesson</label>
                            
                                     <?php

                         //  $sql_lesson="SELECT * FROM `master_lessons` WHERE 1 ";
    $sql_lesson="SELECT * FROM `master_lessons` WHERE id NOT IN (SELECT DISTINCT (lesson_id) FROM int_quiz) ";
    $sql_lesson.=" ORDER BY name ASC  ";
                           // Lesson in which quiz not created.

                              $results_q= mysql_query($sql_lesson);   
                           $tot_obj=mysql_num_rows($results_q);
                        
                           ?>
                                        
                                   <select name="lesson" id="distric444" 
                                           class="required textbox" readonly="readonly" >
                                       
                                       
                                 <?php while ($line= mysql_fetch_assoc($results_q)) {
                                    // SELECT * FROM `terms` WHERE id=151  
             $obj_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `terms` WHERE id=".$line['objective_id']));// obj_short     
                                     ?>
          <option  value="<?=$line['id']?>"><?=$line['name']."-[".$obj_det['obj_short'].']'?></option>

<?php } ?>
                                    </select>      
                                    
                                </p>
                                
                            </div>
                            <!----Gradeee-------> 
                            
                           
                            
                           
                            
                            <p>
                                <input type="submit" name="qz_submit" id="lesson_submit" class="form_button submit_button" value="Submit" />
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