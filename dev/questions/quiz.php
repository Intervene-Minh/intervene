<?php
// Add Quiz|edit|duplicat

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




// +Add Quiz

    if(isset($_POST['qz_submit'])){
    print_r($_POST); die;


  //var_dump($_POST); die;
    $error = '';
    if (count($_SESSION['qn_list']) <= 0) {
        $error = 'Please select questions to create assesment!';
    }
    
    //print_r($_POST); 
   if(empty($_POST['grade'])) $error= 'Please choose grade.'; //else echo 'selected grade';
   
    /////////////
    if (!$error) {
     
        
        
        $grade_id = $_POST['grade'];
        $query = mysql_query('SELECT name FROM terms WHERE taxonomy = "category" AND id = ' . $grade_id);
        $det= mysql_fetch_assoc($query);
        $grade_name =$det['name'];
        
        
        //////////////////
        $objterm_id= $_POST['object_id'];
       
       // $query = mysql_query('SELECT name FROM lessons WHERE id = ' . $objterm_id);
        
        $query = mysql_query('SELECT * FROM terms WHERE id = ' . $objterm_id);
        
        //echo $objterm_id; die;
        $result = mysql_fetch_assoc($query);
        $less_name= $result['name'];
        $quiz_name=(!empty($result['obj_short']))?$result['obj_short']:$result['name'];
         //echo $quiz_name.':objename'; die;
       // $quiz_name= $_POST['objective_name'];
        $date = date('Y-m-d H:i:s');
         // grade_id grade_level_name
    
                 
            
            
            
            
            
            
            $quizid= mysql_insert_id();
        

        if (!isset($_POST['id'])) {
             // new Quiz
            
            mysql_query('INSERT INTO int_quiz SET '
                    . 'objective_id = \'' . $objterm_id . '\' , '
                    . 'grade_level_name= \'' . $grade_name . '\' , '
                    . 'grade_id = \'' . $grade_id . '\' , '
                    . 'objective_name= \'' . $quiz_name . '\' , '
                    . 'created = \'' . $date . '\' ');
            $quizid= mysql_insert_id();
            
            
            $dfdf="Tesff";
           /******
            *          
            mysql_query('INSERT INTO int_quiz SET '
                    . 'objective_id = \'' . $objterm_id . '\' , '
                    . 'grade_level_name= \'' . $grade_name . '\' , '
                    . 'grade_id = \'' . $grade_id . '\' , '
                    . 'objective_name= \'' . $quiz_name . '\' , '
                    . 'created = \'' . $date . '\' ');
            
            
            
            
            
            $quizid= mysql_insert_id();
            * 
            * ****/
          
                    
                    
           
           
        } else if ($_POST['id'] > 0) { // quiz name changed
           
            $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' .$_POST['id']);
            $quiz_data= mysql_fetch_assoc($qry);
            $objterm_id= $_POST['object_id'];

            
            /////////////////// quiz name changed
            if(intval($_POST['object_id'])>0){
                $ff= ('UPDATE int_quiz SET '
                    . 'objective_id = \'' . $objterm_id . '\' , '
//                    . 'grade_level_name  = \'' . $grade_name . '\' , '
                    . 'objective_name= \'' . $quiz_name . '\' , '
                   
                    . 'updated = \'' . $date . '\' WHERE id =  ' . $_POST['id']);
                // Updated if select diff Quiz. >not previous:mysql_query
                
            }

           
           
          // if($ff) echo "Upd";die;
            
            $quizid= $_POST['id'];
           

             // Access Table Entry  

            mysql_query('DELETE FROM int_quiz_x_questions WHERE quiz_id= \'' . $quizid . '\'  ');
            // Editor DupQuiz
            if(isset($_SESSION['assess_id']))
                unset($_SESSION['assess_id']);
            
            
        } // updaed
        
        
        // insert Questions ..
        $num = 1;
        for ($i = 0; $i < count($_SESSION['qn_list']); $i++) {
            $qn_id = $_SESSION['qn_list'][$i];

            mysql_query('INSERT INTO int_quiz_x_questions SET '
                    . 'qn_id = \'' . $qn_id . '\' , '
                    . 'quiz_id= \'' . $quizid . '\' , '
                    . 'num = \'' . $num . '\' ');
            $num = $num + 1;
        }

        unset($_SESSION['list']);
        unset($_SESSION['qn_list']);
        header('Location:quiz_list.php');
        exit;
    }
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
                    </div>		



                    <!-- /.ct_heading -->
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
                                    <select name="grade" id="districtqzz" class="required textbox" readonly="readonly" >
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
                              
                            </div>
                            
                            
                            
                            <p style="border: 2px solid red;">
            <input type="submit" name="qz_submit" id="lesson_submit" class="form_button submit_button" value="Submit"  />
                                <?php if ($_GET['id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>" >
                                <?php } ?>
                                <?php if ($_SESSION['assess_id'] > 0) { ?>
             <input type="hidden" name="id" value="<?php print $_SESSION['assess_id']; ?>" >
                                   <?php } ?>

                            </p>

                            

                            
                            

                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		

                    <!-- /.ct_display -->





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