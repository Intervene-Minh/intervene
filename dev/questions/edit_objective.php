<?php
/***
@ Edit objective and
@ add Grade/Subject to ID to Objective 


**/
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');


// echo 'Teset===============';

////////////////////////////////////
include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

$error = '';


//if (count($_SESSION['qn_list']) <= 0) {
 //   $error = 'Please select questions to create assesment!';
//}




 if(isset($_GET['id'])){  //  objective  Data 

$edit=mysql_fetch_assoc(mysql_query(" SELECT * FROM  terms WHERE id=".$_GET['id']));
 
 // print_r($edit); 

 //$assesment_result = mysql_fetch_assoc($qry); 

 }



/////////Update Assesment///////////



if ($_POST['assesment_submit']) {


   // echo '<pre>';  print_r($_POST); die; 

  $getid=$_GET['id'];
    $_POST['grade_id']=trim($_POST['grade_id']);
    $obj_name=trim($_POST['obj_name']);
    // var_dump($_POST['grade_id']); die; 

    if(empty($_POST['grade_id'])||intval($_POST['grade_id'])<1){
        $error = 'Please Select Subject / Grade Level';
    }

   ////no validation error ////
    if(empty($error)){

         $sql_update="UPDATE terms SET name='$obj_name', subject_grade_id='".$_POST['grade_id']."' WHERE id=".$getid;
          //echo "can update".$sql_update; die; 
          $up=mysql_query($sql_update);
           $redirect="manage_objectives.php";

           if(isset($_POST['taxonomy']))
          $redirect="manage_objectives.php?taxonomy=".$_POST['taxonomy'];

     header('Location:'.$redirect);  exit;

    } //



       
   
}
//  End Update 




//////////////////////////////
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

if ($_SESSION['assess_id'] > 0 && !$_GET['assesment_id']) {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_SESSION['assess_id']);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];
}


////////////////
 $a_id=$_GET['assesment_id']; // Edit Assement
 // $a_id=
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
                        <h3><i class="fa fa-plus-circle"></i>
                          Edit Objective</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            
                            <h4 title="Edit Assessments here">Edit Grade/Subject here:</h4>

                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Objective Name</label>
      <input type="text" name="obj_name" class="required textbox"   value="<?=$edit['name']?>" />
            
                                </p>
                                <input type="hidden" name="taxonomy" value="<?=$edit['parent']?>">


                <?php  //$demo=(isset($assesment_result['is_demo'])&&$assesment_result['is_demo']=='yes')?'checked':'';?>

                                


                                </div>

                            <div class="add_question_wrap clear fullwidth" >
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
                                    <select name="grade_id" class="required textbox" readonly="readonly" >
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

                                                $selected = ($folder['id'] == $edit['subject_grade_id'] ) ? 'selected' :NULL;


                                                echo '<option value="' . $folder['id'] . '"' . $selected . '>' . $folder['name'] . '</option>';
                                                $subfolders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = {$folder['id']} AND `active` = 1");
                                                if (mysql_num_rows($subfolders) > 0) {
                                                    while ($subfolder = mysql_fetch_assoc($subfolders)) {
                                                        if ($_GET['cat'] <= 0 && $grade_level_id == 0) {
                                                            $grade_level_id = $subfolder['id'];
                                                        }

                                                        $selectedX = ($subfolder['id'] == $_GET['cat'] || $subfolder['id'] == $assesment_result['grade_id']) ? ' selected="selected"' : '';
                                                         $selected = ($subfolder['id'] == $edit['subject_grade_id'] ) ? 'selected' :NULL;



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




                            


                           



                            <p>
           <input type="submit" name="assesment_submit" id="lesson_submit" class="form_button submit_button" value="Update" />
                                <?php if ($_GET['id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>" >
                                <?php } ?>
                                <?php if ($_SESSION['assess_id'] > 0 && !$_GET['assesment_id']) { ?>
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