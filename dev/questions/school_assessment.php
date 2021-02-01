<?php
/****
 * @Create school_assessment
 * @Edit Questions: in school_assessment :Existing Assessment

 @ Edit and arrange::
 @ Complete:: Assessment : add |Edit Question 
if(!isset($_SESSION['assess_id']))unset($_SESSION['assess_id']); 

 * **/
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
require_once('inc/school_validate.php'); // IMP
//Get School :Grades& Folders as Subject
//print_r($_SESSION);
//
//
////////School:Assessment Management//////////////////////////

if(count($_SESSION['qn_list']) <= 0) {
 $error = 'Please select questions to create assesment!';
}


//$assessment_temp_name=''; //automatically name by Grade selected for assessment creation
//$assessment_temp_name=null;
if(isset($_SESSION['ses_taxonomy'])){
$data =mysql_fetch_assoc(mysql_query('SELECT name FROM terms WHERE taxonomy = "category" AND id = ' .$_SESSION['ses_taxonomy']));
$assessment_temp_name=$data['name'].='-School Assessments'; }
//////////////assesment_submit///////////////////
 //print_r($_SESSION);
if(isset($_POST['assesment_submit'])) {
    //echo '<pre>';
  // print_r($_POST);  die;
    
    ///////////////
    $error = ''; $msg=array();
    if (count($_SESSION['qn_list']) <= 0) {
        $error = 'Please select questions to create assesment!';
    }
    //// Validate for Required Field
  ///   $z=4344;  echo gettype($z);
     // name and Grade
    if(empty($_POST['assesment_name']))
        $msg[]='Enter assessment name';
    
     if(empty($_POST['grade']))
       $msg[]='Select grade name';
     if(empty($_POST['grade'])|| intval($_POST['grade'])==0)
       $msg[]='Please select correct grade';
     
     
      if(!empty($msg)&&count($msg)>0)
          $error= implode ('\n', $msg);
      
      
    
    ////// no err/////////////////////
    
    if (!$error) { 
      
       // if(!isset($error)){
            //echo 'addd'; die;
      
        $grade_id = $_POST['grade'];
        $query = mysql_query('SELECT name FROM terms WHERE taxonomy = "category" AND id = ' . $grade_id);
        $result = mysql_fetch_assoc($query);
        $grade_name = $result['name'];
        $assesment_name = $_POST['assesment_name'];
        $date = date('Y-m-d H:i:s');


        if (!isset($_POST['id'])) {
          // echo  'noerror'.$_POST['id']; die;
           // echo  'New assessment'; die;
            
            if (count($_POST['district']) > 0 && count($_POST['master_school']) > 0) {
                $master_access_level = 'school';
            } else if (count($_POST['district']) > 0) {
                $master_access_level = 'district';
            } else {
                $master_access_level = 'ALL';
            }
            
           $created_by=$school_id;
            $questions_grade=$_SESSION['ses_taxonomy'];
            // is_passage_grade
             $is_passage_folder=(isset($_SESSION['is_passage_grade'])&&$_SESSION['is_passage_grade']==1)?1:0;
        //die;
            // mysql_query :: created_by, questions_grade xx
           $res=mysql_query('INSERT INTO assessments SET '
                    . 'grade_id = \'' . $grade_id . '\' , '
                    . 'grade_level_name  = \'' . $grade_name . '\' , '
                    . 'access_level = \'' . $master_access_level . '\' , '
                    . 'assesment_name  = \'' . $assesment_name . '\' , '
                   . 'created_by  = \'' .$created_by . '\' , '
                   . 'is_passage  = \'' .$is_passage_folder . '\' , '
                   . 'questions_grade  = \'' .$questions_grade . '\' , '
                    . 'created = \'' . $date . '\' ')or die(mysql_error());
            
          // echo $res; die;
            
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
            }// school and distrct entry
            
            
        } elseif($_POST['id'] > 0) {
            $_SESSION['ses_msg_1']="Assessment Updated";
             //echo  'Edit Questions of Assessment'; die;
            // Addmore#edit
           // echo  '2nnoerror'.$_POST['id']; die;
            
            if (count($_POST['district']) > 0 && count($_POST['master_school']) > 0) {
                $master_access_level = 'school';
            } else if (count($_POST['district']) > 0) {
                $master_access_level = 'district';
            } else {
                $master_access_level = 'ALL';
            }
           // mysql_query
           $up=mysql_query('UPDATE assessments SET '
//                    . 'grade_id = \'' . $grade_id . '\' , '
//                    . 'grade_level_name  = \'' . $grade_name . '\' , '
                    . 'assesment_name  = \'' .addslashes($assesment_name). '\' , '
                 //   . 'access_level = \'' . $master_access_level . '\' , '
                    . 'updated = \'' . $date . '\' WHERE id =  ' . $_POST['id']);
           
           
            
            $assesment_id = $_POST['id'];
            /// Update Access Level///
            //mysql_query('DELETE FROM assessments_access WHERE assessment_id  = \'' . $assesment_id . '\'  ');

//            if (count($_POST['district']) > 0) {
//                for ($k = 0; $k < count($_POST['district']); $k++) {
//                    mysql_query('INSERT INTO assessments_access SET '
//                            . 'assessment_id  = \'' . $assesment_id . '\' , '
//                            . 'access_level = \'district\' , '
//                            . 'entity_id = \'' . $_POST['district'][$k] . '\' ');
//                }
//                for ($k = 0; $k < count($_POST['master_school']); $k++) {
//                    mysql_query('INSERT INTO assessments_access SET '
//                            . 'assessment_id  = \'' . $assesment_id . '\' , '
//                            . 'access_level = \'school\' , '
//                            . 'entity_id = \'' . $_POST['master_school'][$k] . '\' ');
//                }
//            }
            //// Mange questions

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
             // is_passage list ses_taxonomy qn_list
        // assess_id
        unset($_SESSION['is_passage']); 
        unset($_SESSION['ses_school_list']); 
        unset($_SESSION['ses_taxonomy']); 
        unset($_SESSION['qn_list']); 
        unset($_SESSION['is_passage_grade']); 
        ////////
        if(isset($_SESSION['assess_id']))unset($_SESSION['assess_id']); 

         header('Location:school_assessment_list.php');  exit;
       
       
       
    }
    
    
}
//////End add ,Edit QuestionList////////////////






// End Add 
//$questions_list = array();
//if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
//    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['id']);
//    $assesment_result = mysql_fetch_assoc($qry);
//    if ($_GET['cat'] > 0) {
//        unset($assesment_result['grade_id']);
//    }
//    $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
//            . 'LEFT JOIN assessments_x_questions axq ON axq.qn_id = qn.id WHERE '
//            . 'axq.assesment_id = \'' . $_GET['id'] . '\' ');
//
//    while ($question = mysql_fetch_assoc($qn_query)) {
//        $questions_list[] = $question['id'];
//    }
//}

 



//////////Edit/Add More questions/////////////
if ($_SESSION['assess_id'] > 0) {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_SESSION['assess_id']);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];// add More assessment_id
    // At Edit time  : $assessment_temp_name
    unset($assessment_temp_name);
    
}




//District///

//$district_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "district" ');
//$assessment_district = array();
//while ($district = mysql_fetch_assoc($district_level_res)) {
//    $assessment_district[] = $district['entity_id'];
//}
//$school_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "school" ');
//$assessment_school = array();
//while ($school = mysql_fetch_assoc($school_level_res)) {
//    $assessment_school[] = $school['entity_id'];
//}

//$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">
//    $(document).ready(function () {
//
//        $('#district').chosen();
//
//        $('#district').change(function () {
//            district = $(this).val();
//
//            $('#district_school').html('Loading ...');
//            $.ajax({
//                type: "POST",
//                url: "ajax.php",
//                data: {district: district, action: 'get_multiple_schools', school_id: '<?php print implode(',', $assessment_school); ?>'},
//                success: function (response) {
//                    $('#district_school').html(response);
//                    $('#d_school').chosen();
//                },
//                async: false
//            });
//        });
//        $('#district').change();
//    });
</script>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar_school.php"); ?>
            </div>		
            <!-- /#sidebar -->
            
            
              
            <div id="content" class="col-md-8">
                <?php // print_r($assesment_result) ; ?>
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i><?php echo $_GET['id'] > 0 ? 'Edit' : 'Add'; ?> Assesments</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4><?php echo ($_GET['id'] > 0 ? 'Edit' : 'Add new'); ?> Assessments here:</h4>
                            <?php echo $assessment_temp_name, '<br/>' ;?>
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Assessment Name</label>
                                    <input type="text" name="assesment_name" class="required textbox"
                                           value="<?php if(isset($_POST['assesment_name'])) print $_POST['assesment_name'];elseif(isset($assesment_result['assesment_name']))print $assesment_result['assesment_name'];else print $assessment_temp_name; ?>" />
                                </p>
                            
                            </div>
                            
                            

                  <!--                             Grade list-->
                            
                            <?php  
                            //echo $a_id.'===add more assessment_id';
                             // print_r($_SESSION['ses_datadash_grade']);
                             $sql="SELECT * FROM `school_permissions` WHERE `school_id` =".$school_id." AND `permission` = 'data_dash' "; 
                             $result=mysql_query($sql); $school_grade_arr=array();// School Grades Array
                             $grade_arr2=array();
                             while( $row = mysql_fetch_array($result) ){
                                 $school_grade_arr[]= $row['grade_level_id']; // grade_level_name
                              $grade_arr2[$row['grade_level_id']]=$row['grade_level_name'];   
                             }
 


      


                                // List of folder
                                $shared_parents = mysql_query("SELECT DISTINCT(`parent`) FROM `terms` WHERE `id` IN (" . implode(',',$school_grade_arr) . ")");
                $parents=array();
		while( $row = mysql_fetch_array($shared_parents) )
			$parents[] = $row['parent']; // School Parent folders : For assign Grade
                               //  print_r($grade_arr2); 
                             if(isset($a_id)&&$a_id >0) { 
                             $folders = mysql_fetch_assoc(mysql_query("SELECT name FROM `terms` WHERE `taxonomy` = 'category' AND id =\"".$assesment_result['grade_id']."\" "));
                              
                            ?>
                            
                            <div class="add_question_wrap clear fullwidth">
                                <?='<b>Grade: </b>'.$folders['name'];?>   
                                <input type="hidden" name="grade" value="<?=$assesment_result['grade_id']?>" />
                             </div> <?php }else{?>
                  
                  <div class="add_question_wrap clear fullwidth" title="N">
                                                                <p>
                                    <label for="lesson_name">Choose Grade</label>
                                <select name="grade" class="required textbox" readonly="readonly">
                                        <option value="">Select Grade</option>
                                        <?php  foreach($grade_arr2 as $id=>$name){?>
                                        <option value="<?=$id?>"><?=$name?></option>
                                        <?php }?></select>
                                </p>
                             </div> <?php } ?>
                  
                  
                  
                  
                  
                             
                            
                            
                  <div class="add_question_wrap clear fullwidth" style=" display: none;"  title="Not use">
                                <?php 
                              
                          
                                //////////////////
                                if($a_id >0) { //  $a_id?
                                    //$folders = mysql_fetch_assoc(mysql_query("SELECT name FROM `terms` WHERE `taxonomy` = 'category' AND id =\"".$assesment_result['grade_id']."\" "));
                                  //  print '<b>Grade: </b>'.$folders['name'];
                                }else{ ?>
                                <p>
                                    <label for="lesson_name">Choose Grade#[School Grades]</label>
                                    <?php
                                    if ($_GET['id'] > 0) {
                                        $id = $_GET['id'];
                                    } else {
                                        $id = 0;
                                    }
                                    
                                    
                                    
                                        
                                   // if : Folder &Grade>0 then-can create Assessment by School 
                                    ?>
                                    <select name="xx_grade" class="required textbox" readonly="readonly" >
                                        <option value=""></option>
                                        <?php
                                        $grade_level_id = 0;
                                        if ($_GET['cat'] > 0) {
                                            $grade_level_id = $_GET['cat'];
                                        } else if ($assesment_result['grade_id'] > 0) {
                                            $grade_level_id = $assesment_result['grade_id'];
                                        }
                                        
                                        
                                        /// List Folder&Grades::School
                                        $sql="SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = 0 AND `active` = 1";
                                        $sql.="  AND id IN ( ".implode(',',$parents)." ) ";
                                       // echo  $sql; die;
                                        $folders = mysql_query($sql);
                                        
                                        if (count($parents)>0&&mysql_num_rows($folders) > 0) {
                                            while ($folder = mysql_fetch_assoc($folders)) {
                                                $selected = ($folder['id'] == $_GET['cat'] || $folder['id'] == $assesment_result['grade_id'] ) ? ' selected="selected"' : '';
                                                echo '<option value="' . $folder['id'] . '"' . $selected . '>' . $folder['name'] . '</option>';
                                        $subfolders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` ={$folder['id']} AND `active` = 1 AND id IN ( ".implode(',',$school_grade_arr)." )  ");         
                                              //  $subfolders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = {$folder['id']} AND `active` = 1 AND id IN ( ".implode(',',$parents)." )  ");
                                                // Only School Grades:
                                                
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
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                           
                                <?php 
                             // School:: District, Schools:: $district['district_name'] ,$master['school_name']
                              $district=mysql_fetch_array(mysql_query('SELECT * from loc_district WHERE id='.$school_det['district_id']));
                               // master_school_id :: $master['school_name']
                              $master=mysql_fetch_array(mysql_query('SELECT * from master_schools WHERE id='.$school_det['master_school_id']));
                              // school_name
                                
                              
                              ?>
                  <input  type="hidden" name="district[]" value="<?php print $school_det['district_id']//$district['id'] ?>" />
                                
                            <input type="hidden" name="master_school[]"   value="<?php print $school_det['master_school_id']//$master['id'] ?>" />
                                          
                           
                            <?php if(isset($_SESSION['qn_list'])&&count($_SESSION['qn_list']) > 0) {?>
                            
                            <p>
                                <input type="submit" name="assesment_submit" id="lesson_submit" class="form_button submit_button" value="Submit" />
                                <?php if ($_GET['id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>" >
                                <?php } ?>
                                <?php if ($_SESSION['assess_id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_SESSION['assess_id']; ?>" >
<?php } ?>

                            </p> <?php }?>
                            

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