<?php
/***
 * Edit a Quiz
 * @
 * 
 * ****/

$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

$error = '';


//if (count($_SESSION['qn_list']) <= 0) {
 //   $error = 'Please select questions to create assesment!';
//}

if(isset($_GET['quiz_id'])){
$qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['quiz_id']);
$assesment_result = mysql_fetch_assoc($qry); 
$qry2 = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['quiz_id']);
$assesment_det= mysql_fetch_assoc($qry2); 
 }


 

/////////Update Assesment///////////

if ($_POST['assesment_submit']) {
   //  var_dump($_POST);die;
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['quiz_id']);
$assesment_result = mysql_fetch_assoc($qry);


    $acc_lev="district"; # district  school
     // $obj_det= mysql_fetch_assoc(mysql_query('SELECT name FROM lessons WHERE id = ' .$_POST['object_id']));
      
    $obj_det= mysql_fetch_assoc(mysql_query('SELECT * FROM terms WHERE id = ' .$_POST['object_id']));
        $quiz_name= $obj_det['name'];# $quiz_name
       $quiz_name=(!empty($obj_det['obj_short']))?$obj_det['obj_short']:$obj_det['name'];
        
        
        // SELECT * FROM `int_quiz` WHERE 1
       //  valid Objective
     //$qry2 = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['quiz_id']);
   $vquiz=mysql_num_rows(mysql_query("SELECT * FROM int_quiz WHERE id!='".$_GET['quiz_id']."' AND objective_id='".$_POST['object_id']."' "));
    
      #  	objective_name  	
    ////////////////////////////// object_id
    //  object_id
   // $q="UPDATE int_quiz SET quiz_name='".$_POST['quiz_name']."', access_level='".$acc_lev."'  WHERE id='".$_GET['quiz_id']."' ";
 $q="UPDATE int_quiz SET objective_id='".$_POST['object_id']."', objective_name='".$quiz_name."'  WHERE id='".$_GET['quiz_id']."' ";   
     // if NewObject
      
     if($vquiz==0&&$_POST['object_id']!=$_GET['quiz_id'])
     $a=mysql_query($q); 
    
  /////////////////////////////  
    
 $quizz_id=$_GET['quiz_id'];
             
  
  
  
    
       
    
    
     $error = count($_POST['district']) .'Assesment has been updated successfully.';
        //header('Location:edit_assesment.php?assesment_id='.$_GET['quiz_id']);
    header('Location:quiz_list.php?ac=1');
       exit;
   
}
//  End Update 




//////////////////////////////
$questions_list = array();
if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['id']);
    $assesment_result = mysql_fetch_assoc($qry);
    if ($_GET['cat'] > 0) {
        unset($assesment_result['grade_id']);
    }
    $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
            . 'LEFT JOIN int_quiz_x_questions axq ON axq.qn_id = qn.id WHERE '
            . 'axq.assesment_id = \'' . $_GET['id'] . '\' ');

    while ($question = mysql_fetch_assoc($qn_query)) {
        $questions_list[] = $question['id'];
    }
}

if ($_SESSION['assess_id'] > 0) {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_SESSION['assess_id']);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];
}


////////////////
 $a_id=$_GET['quiz_id']; // Edit Assement
 // $a_id=
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
                        <h3><i class="fa fa-plus-circle"></i>
                          Edit Quiz</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Edit here">Edit:Quiz</h4>
                           
                            
                            
                             <div class="add_question_wrap clear fullwidth">
                                
                                <p>
                                    <label for="lesson_name">Choose Objective</label>
                                    <?php
                               // $folders = mysql_fetch_assoc(mysql_query(
                                    
                             $qry2 = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['quiz_id']);
                         $quiz_det= mysql_fetch_assoc($qry2);       
                                    
                                    
                                    ?>
                                    <select name="object_id" class="required textbox" readonly="readonly" >
                                        <option value=""></option>
                                        <?php
                                       
                                        
                                       // $qq=" SELECT * FROM `lessons` WHERE 1 ORDER by date_created DESC  ";
                                      // $results=mysql_query($qq);
                               $results = mysql_query(" SELECT id,name,obj_short FROM `terms` WHERE `taxonomy` = 'objective' AND `active` = 1");        
                                       
                                       
                                        // Select Lessons >>     
                                            
                                        while ($line= mysql_fetch_assoc($results)) {
                          $line['obj_short']=(!empty($line['obj_short']))?$line['obj_short']:$line['name'];                
                                            
                            $sdd=(isset($_GET['quiz_id'])&&$line['id']==$quiz_det['objective_id'])?"selected":NULL; 
                                            
                                            ?>
                                 <option <?=$sdd?>  value="<?=$line['id']?>"><?=$line['obj_short']?></option>           
                                                   <?php
                                            }
                                        
                                        ?>
                                    </select>
                                </p>
                               
                            </div>
                            

                            <div class="add_question_wrap clear fullwidth" style=" ">
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
                            
                            
                           
                           
                            
                            
                            
                            
                            
                            
                            
                            
                            <p>
           <input type="submit" name="assesment_submit" id="lesson_submit" class="form_button submit_button" value="Update" />
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