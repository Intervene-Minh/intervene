<?php
/***
 * School: school_assessment_duplicate
 * @Duplicate assessment by School
 * @print_r($school_id); die;
 * **/
//  ddf
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
include("header.php");
require_once('inc/school_validate.php'); // IMP


/////////Validate:School Assessment/////////assesment_id

if(isset($_GET['assesment_id'])&&is_school_assessment(intval($_GET['assesment_id']),$school_id)==0){
   // exit('Not allowed');
     $_SESSION['ses_msg_1']='Sorry, You can not duplicate,\n access permission error!';// modified
      header('Location:school_assessment_list.php'); exit;
     
 }
///////////////////////////////
if(isset($_GET['assesment_id']))
$asses_id = $_GET['assesment_id'];


////////@Duplicate//////

if (isset($_POST['assesment_submit'])) {
  // echo '<pre>'; print_r($_POST); die;
  // $error='';
   
   
  ////////////////////// 
   
        $grade_id = $_POST['grade'];
        $query = mysql_query('SELECT name FROM terms WHERE taxonomy = "category" AND id = ' . $grade_id);
        $result = mysql_fetch_assoc($query);
        $grade_name = $result['name'];
        $assesment_name = $_POST['assesment_name'];
        $date = date('Y-m-d H:i:s');
        
        if(isset($_POST['id'])&&$_POST['id']>0) {
            // $_POST['id'] ::assessment_id
            
            $data=mysql_fetch_assoc(mysql_query("SELECT * FROM `assessments` WHERE `id` =".$_GET['assesment_id'])); 
             // print_r($data); die;
            $master_access_level =$data['access_level']; //'ALL'; // From previous
            $grade_id=$data['grade_id'];
            $grade_name=$data['grade_level_name'];
            ///
            $questions_grade=$data['questions_grade'];
            $created_by=$data['created_by'];
            $is_passage=$data['is_passage'];
            
            // access_level
           // mysql_query
            // School: field  questions_grade created_by  is_passage
            $sql=mysql_query('INSERT INTO assessments SET '
                    . 'grade_id = \'' . $grade_id . '\' , '
                    . 'grade_level_name  = \'' . $grade_name . '\' , '
                    . 'access_level = \'' . $master_access_level . '\' , '
                    . 'assesment_name  = \'' . $assesment_name . '\' , '
                    . 'questions_grade  = \'' . $questions_grade . '\' , '
                    . 'created_by  = \'' . $created_by . '\' , '
                    . 'is_passage  = \'' . $is_passage . '\' , '
                    . 'created = \'' . $date . '\' ');
           // echo  $sql; die;
            $assesment_id= mysql_insert_id();
            
            
            // Get access level of previojus assessment mysql_query
            $access=mysql_query(" SELECT * FROM `assessments_access` WHERE `assessment_id` =".$_POST['id']);
            //echo  '<pre>' ,$access ; die;
            while($row = mysql_fetch_assoc($access)) {
                $entity_id=$row['entity_id'];
                 $access_level=$row['access_level'];
                $assement_qn_list[] = $asses['qn_id'];
                  $ff=mysql_query('INSERT INTO assessments_access SET '
                            . 'assessment_id  = \'' .$assesment_id. '\' , '
                          . 'access_level  = \'' .$access_level. '\' , '
                        
                            . 'entity_id = \'' .$entity_id . '\' ');
                 // echo  '<pre>' ,$ff ; die; 
                
            }
            
            // Add access Level of school::
//            if (count($_POST['district']) > 0) {
//                for ($k = 0; $k < count($_POST['district']); $k++) {
//                    mysql_query('INSERT INTO assessments_access SET '
//                            . 'assessment_id  = \'' . $assesment_id . '\' , '
//                            . 'access_level = \'district\' , '
//                            . 'entity_id = \'' . $_POST['district'][$k] . '\' ');
//                }
//                
//            }
            
            
       
        ///////////////
        /// Questions List Previous assessment///
        if($_GET['assesment_id'] > 0) {
            $assement_qn_list = array();
            $edit_assessment = mysql_query('SELECT qn_id  FROM assessments_x_questions WHERE assesment_id = \''.$_GET['assesment_id'].'\' ORDER BY num ASC ');
            while($asses = mysql_fetch_assoc($edit_assessment)) {
                $assement_qn_list[] = $asses['qn_id'];
            }
        }
        
        $num = 1;
        for ($i = 0; $i < count($assement_qn_list); $i++) {
            $qn_id = $assement_qn_list[$i];

            mysql_query('INSERT INTO assessments_x_questions SET '
                    . 'qn_id = \'' . $qn_id . '\' , '
                    . 'assesment_id  = \'' . $assesment_id . '\' , '
                    . 'num = \'' . $num . '\' ');
            $num = $num + 1;
        }
        
         } // getid
         $_SESSION['ses_msg_1']='Duplicate assessment created!';
   ///////////////////
       header('Location:school_assessment_list.php'); exit;
    
}
///////End:Duplicate//////

//////Detail//////
if ($asses_id > 0) {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $asses_id);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $asses_id;
}




//////////////
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
//
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
                <?php  include("sidebar_school.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i><?php echo $_GET['id'] > 0 ? 'Edit' : 'Duplicate'; ?> Assesments</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4><?php echo ($_GET['id'] > 0 ? 'Edit' : 'Add Duplicate'); ?> Assessments here:</h4>
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Assessment Name</label>
                                    <input type="text" name="assesment_name" class="required textbox" value="<?php print 'Duplicate:'.' '.$assesment_result['assesment_name']; ?>" />
                                </p></div>

                            <div class="add_question_wrap clear fullwidth">
                                <?php if($a_id >0) {
                                    $folders = mysql_fetch_assoc(mysql_query("SELECT name FROM `terms` WHERE `taxonomy` = 'category' AND id =\"".$assesment_result['grade_id']."\" "));
                                    print '<b>Grade: </b>'.$folders['name']; ?>
                                <input type="hidden" name="grade" value="<?php echo $assesment_result['grade_id']; ?>">
                               <?php } ?>
                            </div>
                            
                            
                            
                            
                            
                          
                            
                            
                            <p>
                                <input type="submit" name="assesment_submit" id="lesson_submit" class="form_button submit_button" value="Submit" />
                                <?php if ($_GET['id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>" >
                                <?php } ?>
                                <?php if ($asses_id > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $asses_id; ?>" >
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