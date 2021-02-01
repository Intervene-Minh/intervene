<?php

/*Edit ::School Assessment only 
 * assesment_name : only edit
 * district :not reqire
 * master_school: Not required
 *  print_r($_SESSION['ses_msg_1']); 

 @ Edit function : Created by school only
 * **/

// echo '==============TT';
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
require_once('inc/school_validate.php'); // IMP
$error = '';
$list_url="school_assessment_list.php";
///////////Validate:access/////////
 
 if(is_school_assessment(intval($_GET['assesment_id']),$school_id)==0){
      $_SESSION['ses_msg_1']='Sorry, You can not modified!!';// modified
      header('Location:'.$list_url); exit;
  //  exit('Not allowed');
 }
 
//////////////////////////////
 if(isset($_GET['assesment_id'])){
$qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
$assesment_result = mysql_fetch_assoc($qry); 
 }

/////////Update Assesment///////////

if (isset($_POST['assesment_submit'])) {
    if(!empty($_POST['assesment_name'])){
    $up=mysql_query('UPDATE assessments SET assesment_name = \''.$_POST['assesment_name'].'\' 	WHERE id = \''.$_GET['assesment_id'].'\' ');
  //  echo $up ; die; 
        $assesment_id=$_GET['assesment_id'];
      $_SESSION['ses_msg_1']='Assesment has been updated successfully.';
      header('Location:'.$list_url); exit;
     
    }else $error = 'Enter assessment name';
   
}
//  End Update 




//////////////////////////////
$questions_list = array();






////////////////
 $a_id=$_GET['assesment_id']; // Edit Assement
 
//$district_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "district" ');
//$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');




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
                <?php include("sidebar_school.php"); ?>
            </div>
            
            
            <!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>
                          Edit Assesments</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Edit Assessments here">Edit Assessments here:</h4>
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Assessment Name</label>
      <input type="text" name="assesment_name" class="required textbox" 
             value="<?php print $assesment_result['assesment_name'] ?>" />
                                </p></div>
                           
                            
                             <div class="add_question_wrap clear fullwidth">
                                <b>Grade: </b><?= $assesment_result['grade_level_name'] ?>   
                               
                             </div>
                            
                            
                            

                            <p class="text-center">
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