<?php
// @ list-tutor-sessions
/*****
 @ filer by : date selected. 
 * ****/


// List of Teachers 
 @extract($_GET) ;
@extract($_POST) ;

include("header.php");
include("user-class.php");
//////////Validate Site Access//////////
//print_r($_SESSION);
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}
/////////////////////////////////////
  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";


// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

$error='';
$id = $_SESSION['ses_teacher_id'];
 if(isset($_POST['submit_data']) ) {
      
     $is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']);
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
     
        $sql=" UPDATE tutor_profiles SET teacher_certificate = '$teacher_certificate_file',esl_certificate = '$esl_certificate_file',billingual_certificate = '$billingual_certificate_file' WHERE tutorid=".$_SESSION['ses_teacher_id'];
        $ac=mysql_query($sql);
        

        
if(isset($_GET['act']) && $_GET['act'] == 'tut')
{
    $_SESSION['upload_message'] = "Your certification document uploaded successfully";
    unset($_SESSION['cer_complete']);
    header("Location:upload_document.php?act=tut");
}
  else 
  {
      $_SESSION['cer_complete'] = 1;
      if(isset($_SESSION['claim_exam_complete']) && $_SESSION['claim_exam_complete'] == 1)
          header("Location:Jobs-Board-List.php");
      else
          header("Location:manage_claim_tests.php?ses_id=".$_SESSION['manage_tutor_sess_id']);
        
  }
        exit;
 }

 if(isset($_GET['act']) && $_GET['act'] == 'del')
{
   
  $sql=" UPDATE tutor_profiles SET ".$_GET['cer_type']." = NULL WHERE tutorid=".$_SESSION['ses_teacher_id']; 
    $ac=mysql_query($sql);
   
    $_SESSION['upload_message'] = "Your certification document deleted successfully";
    

    header("Location:upload_document.php?act=tut");
}
  $is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']);

     $profile = mysql_fetch_assoc($is_record);

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
                        <?php unset($_SESSION['upload_message']); } ?>
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
			
<?php if(isset($_SESSION['manage_certificate_type'])) { ?>
                 
                 <?php  if($_SESSION['manage_certificate_type'] == 2) { ?>
                   <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Certified Teacher Certification</label>
								</p>
                                                                <?php
                                                                if($profile['teacher_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $profile['teacher_certificate'];?>" target="_blank"><?php echo $profile['teacher_certificate'];?></a> <a href="upload_document.php?act=del&cer_type=teacher_certificate" onclick="return confirm('Are you sure you want to delete thos document?')"><i class="glyphicon glyphicon-remove-circle" style="float:right;color:red;font-size: 20px"></i></a></p>
                                                                <?php } ?>
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="teacher_certificate">
       
								
								
                             
                                </div> 
                 <?php } ?>
                  <?php if($_SESSION['manage_certificate_type'] == 3) { ?>
                 <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Certified in ESL Certification</label>
								</p>
                                                                 <?php
                                                                if($profile['esl_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $profile['esl_certificate'];?>" target="_blank"><?php echo $profile['esl_certificate'];?></a><a href="upload_document.php?act=del&cer_type=esl_certificate" onclick="return confirm('Are you sure you want to delete thos document?')"><i class="glyphicon glyphicon-remove-circle" style="float:right;color:red;font-size: 20px"></i></a></p>
                                                                <?php } ?>
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="esl_certificate">
              </div> 
                  <?php } ?>
                  <?php if($_SESSION['manage_certificate_type'] == 4) { ?>
                 <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Billingual Certification</label>
								</p>
                                                                 <?php
                                                                if($profile['billingual_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $profile['billingual_certificate'];?>" target="_blank"><?php echo $profile['billingual_certificate'];?></a><a href="upload_document.php?act=del&cer_type=billingual_certificate" onclick="return confirm('Are you sure you want to delete thos document?')"><i class="glyphicon glyphicon-remove-circle" style="float:right;color:red;font-size: 20px"></i></a></p>
                                                                <?php } ?>
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="billingual_certificate">
       
								
								
								

                             
                                </div>
                  <?php } ?>
<?php } else { ?>

                         <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Certified Teacher Certification</label>
								</p>
                                                                <?php
                                                                if($profile['teacher_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $profile['teacher_certificate'];?>" target="_blank"><?php echo $profile['teacher_certificate'];?></a> <a href="upload_document.php?act=del&cer_type=teacher_certificate" onclick="return confirm('Are you sure you want to delete thos document?')"><i class="glyphicon glyphicon-remove-circle" style="float:right;color:red;font-size: 20px"></i></a></p>
                                                                <?php } ?>
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="teacher_certificate">
       
								
								
                             
                                </div> 
                 
                 <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Certified in ESL Certification</label>
								</p>
                                                                 <?php
                                                                if($profile['esl_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $profile['esl_certificate'];?>" target="_blank"><?php echo $profile['esl_certificate'];?></a><a href="upload_document.php?act=del&cer_type=esl_certificate" onclick="return confirm('Are you sure you want to delete thos document?')"><i class="glyphicon glyphicon-remove-circle" style="float:right;color:red;font-size: 20px"></i></a></p>
                                                                <?php } ?>
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="esl_certificate">
              </div> 
                 
                 <div class="add_question_wrap clear fullwidth">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod Billingual Certification</label>
								</p>
                                                                 <?php
                                                                if($profile['billingual_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $profile['billingual_certificate'];?>" target="_blank"><?php echo $profile['billingual_certificate'];?></a><a href="upload_document.php?act=del&cer_type=billingual_certificate" onclick="return confirm('Are you sure you want to delete thos document?')"><i class="glyphicon glyphicon-remove-circle" style="float:right;color:red;font-size: 20px"></i></a></p>
                                                                <?php } ?>
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="billingual_certificate">
       
								
								
								

                             
                                </div>
<?php } ?>
                 <div class="clear">&nbsp;</div>
                                 <button type="submit" id="profile-submit" class="btn btn-primary"
                                  name="submit_data">Uplaod Document</button>
							</div>
			 
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

	<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
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