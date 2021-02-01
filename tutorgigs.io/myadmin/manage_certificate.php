<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * ***/
@extract($_REQUEST);
$sub=array(
"Elementary Math","Elementary ELA","Middle School / Junior High School - Math","Middle School / Junior High School - ELA","High School Math","High School ELA","Fluent in Spanish",

"Early Reading Phonics / English Language Learning");
include("header.php");
include('newrow.functions.php');

$error = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //if not admin but want to edit return index
    require_once('inc/check-role.php');
    $login_role = checkRole();
    if ($login_role != 0 || !isGlobalAdmin()) {
        header('Location: index.php');
        exit;
    }
} else {
    $id = $_SESSION['login_id'];
}

/// 'profile-submit'
/*
 * firstname
lastname
email
phone
 * 
 * **/

  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";


 if(isset($_POST['submit_data']) ) {
      
     $is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_POST['tutor_id']);
     $num=mysql_num_rows($is_record);
     $profile = mysql_fetch_assoc($is_record);
     
      //  teacher certificate upload
       if(!empty($_FILES["teacher_certificate"]["name"])){
       // if image exist unlink old image
       if($num == 1)
       {
           unlink("../tutor_certificate/".$profile['teacher_certificate']);
   
       }
   
       $name = $_FILES["teacher_certificate"]["name"];
       $teacher_certificate_file = time().rand(1001,1111).$name;
   
       $target_file = "../teacher_certificate/".$teacher_certificate_file;
       if (move_uploaded_file($_FILES["teacher_certificate"]["tmp_name"], $target_file)) {
          $msg= 'uploaded success';
       } else {  
          $msg = 'not uploaded';
          $teacher_certificate_file = null;
   
      }
         
      }
      elseif($num == 1){ // previous added if any 
         $teacher_certificate_file = $profile['teacher_certificate'];  // OLD Name
   
      }
      else{  
           $teacher_certificate_file = null; 
      
      }
      
      
      //  ESL certificate upload
       if(!empty($_FILES["esl_certificate"]["name"])){
       // if image exist unlink old image
       if($num == 1)
       {
          unlink("../tutor_certificate/".$profile['esl_certificate']);
   
       }
   
       $name = $_FILES["esl_certificate"]["name"];
       $esl_certificate_file = time().rand(1001,1111).$name;
   
       $target_file = "../teacher_certificate/".$esl_certificate_file;
       if (move_uploaded_file($_FILES["esl_certificate"]["tmp_name"], $target_file)) {
          $msg= 'uploaded success';
       } else {  
          $msg = 'not uploaded';
          $esl_certificate_file = null;
   
      }
         
      }
      elseif($num == 1){ // previous added if any 
         $esl_certificate_file = $profile['esl_certificate'];  // OLD Name
   
      }
      else{  
           $esl_certificate_file = null; 
      
      }
      
      
      //  Billingual certificate upload
       if(!empty($_FILES["billingual_certificate"]["name"])){
       // if image exist unlink old image
       if($num == 1)
       {
          unlink("../tutor_certificate/".$profile['billingual_certificate']);
   
       }
   
       $name = $_FILES["billingual_certificate"]["name"];
       $billingual_certificate_file = time().rand(1001,1111).$name;
   
       $target_file = "../teacher_certificate/".$billingual_certificate_file;
       if (move_uploaded_file($_FILES["billingual_certificate"]["tmp_name"], $target_file)) {
          $msg= 'uploaded success';
       } else {  
          $msg = 'not uploaded';
          $billingual_certificate_file = null;
   
      }
         
      }
      elseif($num == 1){ // previous added if any 
         $billingual_certificate_file = $profile['billingual_certificate'];  // OLD Name
         
        
   
      }
      else{  
           $billingual_certificate_file = null; 
      
      }
     
        $sql=" UPDATE tutor_profiles SET teacher_certificate = '$teacher_certificate_file',esl_certificate = '$esl_certificate_file',billingual_certificate = '$billingual_certificate_file' WHERE tutorid=".$_POST['tutor_id'];
        $ac=mysql_query($sql);
        

        
 header("Location:manage_certificate.php?tid=".$_POST['tutor_id']);
    exit;
 }

 if(isset($_GET['act']) && $_GET['act'] == 'del')
{
   if($_GET['cer_type'] == 'teacher_certificate')
       $field = " teacher_approve = NULL";
   else if($_GET['cer_type'] == 'esl_certificate')
       $field = " esl_approve = NULL";
   if($_GET['cer_type'] == 'billingual_certificate')
       $field = " bilingual_approve = NULL";
   
    $sql=" UPDATE tutor_profiles SET ".$_GET['cer_type']." = NULL, ".$field." WHERE tutorid=".$_GET['tutor_id'];
    $ac=mysql_query($sql);
   
    $_SESSION['upload_message'] = "Your certification document deleted successfully";
    

    header("Location:manage_certificate.php?tid=".$_GET['tutor_id']);
}

if(isset($_GET['act']) && $_GET['act'] == 'block' && isset($_GET['cer_type']))
{
   
    $tutor_sql = mysql_query( "SELECT * from `tutor_profiles` where tutorid='".$_GET['tutor_id']."'");
    $tutor_sql_data = mysql_fetch_object($tutor_sql);
    
    if($_GET['cer_type'] == 'certificate')
    {
       

        if($tutor_sql_data->certificate_block == 1)
            $sql=" UPDATE tutor_profiles SET certificate_block = NULL where tutorid='".$_GET['tutor_id']."'";
        else
           $sql=" UPDATE tutor_profiles SET certificate_block = 1 where tutorid='".$_GET['tutor_id']."'";
    }  
    else if($_GET['cer_type'] == 'esl')
    {
       

        if($tutor_sql_data->esl_block == 1)
            $sql=" UPDATE tutor_profiles SET esl_block = NULL where tutorid='".$_GET['tutor_id']."'";
        else
           $sql=" UPDATE tutor_profiles SET esl_block = 1 where tutorid='".$_GET['tutor_id']."'";
    }  
    else if($_GET['cer_type'] == 'bilingual')
    {
       

        if($tutor_sql_data->bilingual_block == 1)
            $sql=" UPDATE tutor_profiles SET bilingual_block = NULL where tutorid='".$_GET['tutor_id']."'";
        else
           $sql=" UPDATE tutor_profiles SET bilingual_block = 1 where tutorid='".$_GET['tutor_id']."'";
    }  
//echo $sql; exit;
    $ac=mysql_query($sql);
   
    $_SESSION['upload_message'] = "The certificate document blocked successfully";
    

    header("Location:manage_certificate.php?tid=".$_GET['tutor_id']);
    exit;
}

if(isset($_GET['act']) && $_GET['act'] == 'appr_status' && isset($_GET['cer_type']))
{
   
    $tutor_sql = mysql_query( "SELECT * from `tutor_profiles` where tutorid='".$_GET['tutor_id']."'");
    $tutor_sql_data = mysql_fetch_object($tutor_sql);
    
    if($_GET['cer_type'] == 'certificate')
    {
       

        if($tutor_sql_data->teacher_approve == 1)
        {
            $sql=" UPDATE tutor_profiles SET teacher_approve = 0 where tutorid='".$_GET['tutor_id']."'";
            $_SESSION['upload_message'] = "The certificate document disapproved successfully";
        }
        else
        {
           $sql=" UPDATE tutor_profiles SET teacher_approve = 1 where tutorid='".$_GET['tutor_id']."'";
           $_SESSION['upload_message'] = "The certificate document approved successfully";
        }
    }  
    else if($_GET['cer_type'] == 'esl')
    {
       

        if($tutor_sql_data->esl_approve == 1)
        {
            $sql=" UPDATE tutor_profiles SET esl_approve = 0 where tutorid='".$_GET['tutor_id']."'";
            $_SESSION['upload_message'] = "The certificate document disapproved successfully";
        }
        else
        {
           $sql=" UPDATE tutor_profiles SET esl_approve = 1 where tutorid='".$_GET['tutor_id']."'";
           $_SESSION['upload_message'] = "The certificate document approved successfully";
        }
    }  
    else if($_GET['cer_type'] == 'bilingual')
    {
       

        if($tutor_sql_data->bilingual_approve == 1)
        {
            $sql=" UPDATE tutor_profiles SET bilingual_approve = 0 where tutorid='".$_GET['tutor_id']."'";
             $_SESSION['upload_message'] = "The certificate document disapproved successfully";
        }
        else
        {
           $sql=" UPDATE tutor_profiles SET bilingual_approve = 1 where tutorid='".$_GET['tutor_id']."'";
            $_SESSION['upload_message'] = "The certificate document approved successfully";
        }
    }  
//echo $sql; exit;
    $ac=mysql_query($sql);
   
   
    

    header("Location:manage_certificate.php?tid=".$_GET['tutor_id']);
    exit;
}

if(isset($_GET['act']) && $_GET['act'] == 'block' && empty($_GET['cer_type']))
{
   
    include('inc/sql_connect.php');
    $p2db = p2g(); 
    
    $test_sql = mysqli_query($p2db, "SELECT * from `tutor_tests_logs` where tutor_id='".$_GET['tutor_id']."' AND quiz_test_id = '".$_GET['test_id']."'");
    $test_sql_data = mysqli_fetch_object($test_sql);
   
    if($test_sql_data->block == 1)
        $sql=" UPDATE tutor_tests_logs SET block = NULL where `tutor_tests_logs`.tutor_id='".$_GET['tutor_id']."' AND quiz_test_id = '".$_GET['test_id']."'";
    else
       $sql=" UPDATE tutor_tests_logs SET block = 1 where `tutor_tests_logs`.tutor_id='".$_GET['tutor_id']."' AND quiz_test_id = '".$_GET['test_id']."'";
            
     
    $ac=mysqli_query($p2db, $sql);
   
    $_SESSION['upload_message'] = "The exam test blocked successfully";
    

    header("Location:manage_certificate.php?tid=".$_GET['tutor_id']);
    exit;
}

if(isset($_GET['act']) && $_GET['act'] == 'reset' && empty($_GET['cer_type']))
{
   
    include('inc/sql_connect.php');
    $p2db = p2g(); 
    
    if(!empty(($_GET['test_id'])))
    {
        $sql=" DELETE FROM tutor_tests_logs where  tutor_id='".$_GET['tutor_id']."' AND quiz_test_id = '".$_GET['test_id']."'";
        $ac=mysqli_query($p2db, $sql);

        $sql=" DELETE FROM tutor_result_logs where  tutor_id='".$_GET['tutor_id']."' AND test_id = '".$_GET['test_id']."'";
        $ac=mysqli_query($p2db, $sql);
    }
    else
    {
        $sql=" DELETE FROM tutor_tests_logs where  tutor_id='".$_GET['tutor_id']."'";
        $ac=mysqli_query($p2db, $sql);

        $sql=" DELETE FROM tutor_result_logs where  tutor_id='".$_GET['tutor_id']."' ";
        $ac=mysqli_query($p2db, $sql);
    }
   
    $_SESSION['upload_message'] = "The exam test reseted successfully";
    

    header("Location:manage_certificate.php?tid=".$_GET['tutor_id']);
    exit;
}


if(isset($_POST['test_id']) && isset($_POST['tutor_id']))
{
   
    include('inc/sql_connect.php');
    $p2db = p2g(); 
    
    $test_sql = mysqli_query($p2db, "SELECT * from `tests` where ID='".$_POST['test_id']."'");
    $test_sql_data = mysqli_fetch_object($test_sql);
    
    
    if($_POST['score'] >= $test_sql_data->PassingMark)
        $pass_status = 'pass';
    else
        $pass_status = 'fail';
    
    $test_sql_2 = " SELECT * FROM tutor_tests_logs WHERE tutor_id='".$_POST['tutor_id']."'  AND quiz_test_id = '".$_POST['test_id']."'";
    $get_test_result_2 = mysqli_query($p2db,$test_sql_2);
    $get_test_result_2_data = mysqli_num_rows($get_test_result_2);
   
    if($get_test_result_2_data)
       $sql =" UPDATE tutor_tests_logs SET per_scored = '".$_POST['score']."', pass_status = '".$pass_status."', status = 'completed' where tutor_id='".$_POST['tutor_id']."' AND quiz_test_id = '".$_POST['test_id']."'";
    else
        $sql =" INSERT INTO tutor_tests_logs SET per_scored = '".$_POST['score']."', school_id=0 ,test_type = '".$test_sql_data->Name."', per_passing = '".$test_sql_data->PassingMark."', pass_status = '".$pass_status."', status = 'completed', tutor_id='".$_POST['tutor_id']."',quiz_test_id = '".$_POST['test_id']."'";
   
    $ac=mysqli_query($p2db, $sql);
   
    $_SESSION['upload_message'] = "The exam test score updated successfully";
    

    header("Location:manage_certificate.php?tid=".$_POST['tutor_id']);
    exit;
}
  $is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$tid);

  $profile = mysql_fetch_assoc($is_record);
     
  include('inc/sql_connect.php');
  $p2db = p2g(); 
  //$test_sql = " SELECT `tutor_tests_logs`.*, `tests`.name, `tests`.PassingMark FROM `tutor_tests_logs` INNER JOIN `tests` ON(`tutor_tests_logs`.quiz_test_id = `tests`.ID) WHERE `tutor_tests_logs`.tutor_id='$tid' AND `tutor_tests_logs`.status='completed'  ";
  $test_sql = " SELECT * from tests WHERE Name != 'Training Test' AND IsActive = 1";
  $get_test_result = mysqli_query($p2db,$test_sql);

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
                        <h3>Uplaod Certification Document</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <?php
                        if(isset($_SESSION['upload_message'])) { ?>
                        <div class="alert alert-success" role="alert" style="text-align: center"><?php echo $_SESSION['upload_message'];?></div>
                        <?php unset($_SESSION['upload_document']); } ?>
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
			
<input type="hidden" name="tutor_id" value="<?php echo $_GET['tid'];?>">

                         <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Certified Teacher Certification</label>
								</p>
                                                                <?php
                                                                if($profile['teacher_certificate']) { ?>
                                                                <p> 
                                                                    <a href="../teacher_certificate/<?php echo $profile['teacher_certificate'];?>" target="_blank"><?php echo $profile['teacher_certificate'];?></a> 
                                                                    <a style="float:right" class="btn btn-sm btn-success" href="manage_certificate.php?act=del&cer_type=teacher_certificate&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to delete thos document?')">Delete</a>
                                                                   &nbsp; <?php if($profile['certificate_block'] == 1) { ?>   
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-primary" href="manage_certificate.php?act=block&cer_type=certificate&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to unblock this document?')">Unblock</a>
                                                                 <?php } else {?>
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-danger" href="manage_certificate.php?act=block&cer_type=certificate&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to block this document?')">Block</a>
                                                                 <?php } ?>
                                                                    
                                                                     &nbsp; <?php if($profile['teacher_approve'] == 1) { ?>   
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-info" href="manage_certificate.php?act=appr_status&cer_type=certificate&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to disapprove this document?')">Disapprove</a>
                                                                 <?php } else {?>
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-danger" href="manage_certificate.php?act=appr_status&cer_type=certificate&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to approve this document?')">Approve</a>
                                                                 <?php } ?>
                                                                </p>
                                                                <?php } ?>
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="teacher_certificate">
                                                             
                                </div> 
                 
                 <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Certified in ESL Certification</label>
								</p>
                                                                 <?php
                                                                if($profile['esl_certificate']) { ?>
                                                                <p> 
                                                                    <a href="../teacher_certificate/<?php echo $profile['esl_certificate'];?>" target="_blank"><?php echo $profile['esl_certificate'];?></a>
                                                                    <a style="float:right" class="btn btn-sm btn-success" href="manage_certificate.php?act=del&cer_type=esl_certificate&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to delete this document?')">Delete</a>
                                                                    &nbsp; <?php if($profile['esl_block'] == 1) { ?>   
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-primary" href="manage_certificate.php?act=block&cer_type=esl&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to unblock this document?')">Unblock</a>
                                                                 <?php } else {?>
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-danger" href="manage_certificate.php?act=block&cer_type=esl&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to block this document?')">Block</a>
                                                                 <?php } ?>
                                                                     &nbsp; <?php if($profile['esl_approve'] == 1) { ?>   
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-info" href="manage_certificate.php?act=appr_status&cer_type=esl&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to disapprove this document?')">Disapprove</a>
                                                                 <?php } else {?>
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-danger" href="manage_certificate.php?act=appr_status&cer_type=esl&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to approve this document?')">Approve</a>
                                                                 <?php } ?>
                                                                </p>
                                                                <?php } ?>
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="esl_certificate">
                                                                
              </div> 
                 
                 <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Billingual Certification</label>
								</p>
                                                                 <?php
                                                                if($profile['billingual_certificate']) { ?>
                                                                <p> 
                                                                    <a href="../teacher_certificate/<?php echo $profile['billingual_certificate'];?>" target="_blank"><?php echo $profile['billingual_certificate'];?></a>
                                                                    <a style="float:right" class="btn btn-sm btn-success" href="manage_certificate.php?act=del&cer_type=billingual_certificate&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to delete thos document?')">Delete</a>
                                                                 &nbsp; <?php if($profile['bilingual_block'] == 1) { ?>   
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-primary" href="manage_certificate.php?act=block&cer_type=bilingual&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to unblock this document?')">Unblock</a>
                                                                 <?php } else {?>
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-danger" href="manage_certificate.php?act=block&cer_type=bilingual&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to block this document?')">Block</a>
                                                                 <?php } ?>
                                                                     &nbsp; <?php if($profile['bilingual_approve'] == 1) { ?>   
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-info" href="manage_certificate.php?act=appr_status&cer_type=bilingual&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to disapprove this document?')">Disapprove</a>
                                                                 <?php } else {?>
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-danger" href="manage_certificate.php?act=appr_status&cer_type=bilingual&tutor_id=<?php echo $profile['tutorid'];?>" onclick="return confirm('Are you sure you want to approve this document?')">Approve</a>
                                                                 <?php } ?>
                                                                </p>
                                                                <?php } ?>
                                                             <input style="font-size:13px !important;padding:0px" type="file" name="billingual_certificate">
       
                                </div>
                 <div class="clear">&nbsp;</div>
                 <button type="submit" id="profile-submit" class="btn btn-primary"
                                  name="submit_data">Uplaod Document</button>
                 
                               
							</div>
			 
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                    
                    
                    <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Tutor Exam test</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear" style="padding-top: 10px">
                        <a style="float:right;margin-bottom: 7px" class="btn btn-sm btn-info" href="manage_certificate.php?act=reset&tutor_id=<?php echo $_GET['tid'];?>" onclick="return confirm('Are you sure you want to reset all exam test?')">Reset All</a>
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
			

                <?php 
            
                while( $row = mysqli_fetch_assoc($get_test_result) ) { 
                   $test_sql_2 = " SELECT * FROM tutor_tests_logs WHERE tutor_id='".$_GET['tid']."' AND status='completed'  AND quiz_test_id = '".$row['ID']."'";
                    $get_test_result_2 = mysqli_query($p2db,$test_sql_2);
                    $get_test_result_2_data = mysqli_fetch_assoc($get_test_result_2);
                    $is_exist = mysqli_num_rows($get_test_result_2);
                    ?>
                         <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;"><?php echo $row['Name'];?></label>
								</p>
                                                                <?php if($get_test_result_2_data['pass_status']) { ?>
                                                               <p>
									Status - <?php echo $get_test_result_2_data['pass_status'];?>
								</p>
                                                                <?php } ?>
                                                                <p>
                                                                <?php if($get_test_result_2_data['per_scored']) { ?>
                                                                 Score - <?php echo $get_test_result_2_data['per_scored'];?>% 
                                                                    <?php } ?>
                                                                 &nbsp;
                                                                 <?php 
                                                                 if($is_exist > 0)
                                                                 {
                                                                     
                                                                     
                                                                 if($get_test_result_2_data['block'] == 1) { ?>   
                                                                    <a style="float:right" class="btn btn-sm btn-primary" href="manage_certificate.php?act=block&test_id=<?php echo $row['ID'];?>&tutor_id=<?php echo $_GET['tid'];?>" onclick="return confirm('Are you sure you want to unblock this exam test?')">Unblock</a>
                                                                 <?php } else {?>
                                                                    <a style="float:right" class="btn btn-sm btn-danger" href="manage_certificate.php?act=block&test_id=<?php echo $row['ID'];?>&tutor_id=<?php echo $_GET['tid'];?>" onclick="return confirm('Are you sure you want to block this exam test?')">Block</a>
                                                                 <?php } }?>
                                                                     <a style="float:right;margin-right: 4px" class="btn btn-sm btn-success"  onclick="showscoremodal('<?php echo $row['ID'];?>','<?php echo $_GET['tid'];?>','<?php echo $get_test_result_2_data['per_scored'];?>','<?php echo $row['PassingMark'];?>')" href="javascript:void(0)" >Edit Score</a>
                                                                    <a style="float:right;margin-right: 4px" class="btn btn-sm btn-info" href="manage_certificate.php?act=reset&test_id=<?php echo $row['ID'];?>&tutor_id=<?php echo $_GET['tid'];?>" onclick="return confirm('Are you sure you want to reset this exam test?')">Reset</a>
                                                                </p>
                                                              
                                                             
                                </div> 
                 
                <?php } ?>
                 
                 
                 <div class="clear">&nbsp;</div>
                               
							</div>
			 
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->


<div class="modal fade" id="session_claim" role="dialog">

    <div class="modal-dialog">

      <!-- Modal content-->
 <form method="post">
      <div class="modal-content">

         <div class="modal-header">

          <h4 class="modal-title">Edit Test Score</h4>

          <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>

          <div class="panel-body" style="text-align:center" id="modal_body">
               
                    <input type="hidden" name="test_id" id="test_id" value="">
                     <input type="hidden" name="tutor_id" id="tutor_id" value="">
                     <div class="form-group" style="text-align: left">
    <label for="exampleInputEmail1">Passing Score</label>
    <input type="text" class="form-control" id="passing_score" name="passing_score" value="" readonly>
  </div>
  <div class="form-group" style="text-align: left">
    <label for="exampleInputPassword1">Score</label>
    <input type="text" class="form-control" id="score" name="score" value="">
  </div>


 

         
        </div>

        <div class="modal-footer">
 <button type="submit" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>

      </div>

      </form>

    </div>

  </div>

	<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
    
 function showscoremodal(test_id, tutor_id, score, passing_mark)
{
    $('#score').val(score);
    $('#test_id').val(test_id);
    $('#tutor_id').val(tutor_id);
    $('#passing_score').val(passing_mark);
    $('#session_claim').modal('show');
}
    
//select all checkboxes
$("#notify_all").change(function(){  //"select all" change 
	var status = this.checked; // "select all" checked status
    //alert(status);
	$('.case').each(function(){ //iterate all listed checkbox items
		this.checked = status; //change ".checkbox" checked status
	});
});

$('.case').change(function(){ //".checkbox" change 
	//uncheck "select all", if one of the listed checkbox item is unchecked
	if(this.checked == false){ //if this item is unchecked
		$("#notify_all")[0].checked = false; //change "select all" checked status to false
	}
	
	//check "select all" if all checkbox items are checked
	if ($('.case:checked').length == $('.case').length ){ 
		$("#notify_all")[0].checked = true; //change "select all" checked status to true
	}
});


</script>
<?php include("footer.php"); ?>