<?php
/*****
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}
// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
@ Attempt QUIZ
 
 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //Interview Button

****/
 include("header.php");
  


if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
//$_SESSION['ses_curr_state_url'] ='quiz.php'; 
  ///go : current state
 if($_SESSION['ses_curr_state_url']!='quiz.php'){
  header("location:".$_SESSION['ses_curr_state_url']); exit; // go, state page wherver
  }

  $tutorid= $tutor_id=$_SESSION['ses_teacher_id'];


  ///validate:in_proces test /// in_process completed
  include('inc/sql_connect.php');

  $p2db=p2g(); // tutor_id test_type quiz_test_id status
 $sql=" SELECT * FROM `tutor_tests_logs` WHERE tutor_id='$tutor_id' AND status='in_process' ";
 $get_result=mysqli_query($p2db,$sql);
 $rowcount=mysqli_num_rows($get_result);

  $check= $rowcount.'==toal test';
  if($rowcount==1){
  	 $row=mysqli_fetch_assoc($get_result);
  	 // go to RESUME state if in_process  QUIZ ses_curr_state_url
  	  $curr_state_url='quiz_start.php?in_quiz='.$row['quiz_test_id'];
  	  $_SESSION['ses_curr_state_url']=$curr_state_url;
  	  // set sesson curr_state_url also 
  	  header("location:".$curr_state_url); exit;
  }

    


/////////////
  //  echo $check;
  // die;
  /////////Validate quiz attempted or not //
//$tutorid
$sql=" SELECT * FROM `tutor_tests_logs` WHERE tutor_id=".$tutorid;

  $get_result=mysqli_query($p2db,$sql);
  $rowcount=mysqli_num_rows($get_result);
  $test_arr=array();
   while($row=mysqli_fetch_assoc($get_result)){
       if($row['status']=='completed'){ // 
      $test_arr[$row['quiz_test_id']]=$row['pass_status'];
       }
   }
 
 $completed_attempt=count($test_arr); //completed_attempt
   
   // if both attempted //
    if($completed_attempt>1){ 

     	 header("location:quiz_result.php"); exit;
     }



  ///////////////////

   $state_arr['total_attempted']= $rowcount;

   if($state_arr['total_attempted']==1){
    $row=mysqli_fetch_assoc($get_result);
    // print_r($row);  // Current test stuts 

     if($row['test_type']=="math"){
      $state_arr['type_math']='inactive';
       $state_arr['type_english']='active';
    }

    if($row['test_type']=="english"){
       $state_arr['type_math']='active';
      $state_arr['type_english']='inactive';
    }


   }if($state_arr['total_attempted']==2){  // Both QUIZ done 

     $state_arr['type_math']='inactive';
    $state_arr['type_english']='inactive';
      $state_arr['both_quiz_done']='yes';
   }


   //  print_r($state_arr);
     // if matth passed 



/////////Validate quiz attempted or not //






 $tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));

//Signup State
  $get_state_arr=unserialize($tutor_det['signup_state']);
  //print_r($get_state_arr);
  // Can access web site.
$_SESSION['ses_access_website']='no'; // 1==if all 4 step completed by user
  $_SESSION['ses_access_website']; 

 $data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));

$edit=unserialize($data2['profile_1']);
 //print_r($edit);

//////Add profile 1///////
if(isset($_POST['xxxxsave']) ) {

	$signup_state_arr= array('step_1' => 1,
  'is_computer' =>$_POST['is_computer'],
  'started_date' => $_POST['started_date'],
  'hear' =>$_POST['hear']);// started_date is_computer

	$state_str=serialize($signup_state_arr);

	/**
INSERT INTO `tutor_profiles` (`tutor_id`, `profile_1`, `info`, `created`) VALUES (NULL, 'dffd', '3', '2019-01-23 00:00:00');
	**/ // tutorid
	$is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']);
	$num=mysql_num_rows($is_record);

	$tutorid=$_SESSION['ses_teacher_id'];
	if($num==0){
		$msg='added';
		$sql=" INSERT INTO `tutor_profiles` (`tutorid`, `profile_1`, `info`) VALUES ('$tutorid', '$state_str', '3') ";
	
	}else{
		$msg='Profile saved'; 
		// UPDATE `tutor_profiles` SET `profile_1` = 'DAT' WHERE `tutor_profiles`.`id` = 1;
		$sql=" UPDATE `tutor_profiles` SET `profile_1` = '$state_str',info='Later' WHERE tutorid=".$_SESSION['ses_teacher_id'];
	}

	// echo $sql; 
	
      $ac=mysql_query($sql);
    echo $msg;
	//echo '<pre>';print_r($_POST); 
	//die;
/////insert 1 then update

}


$testList = mysqli_query($p2db, "SELECT * FROM `tests` WHERE `Name` != 'Training Test' AND IsActive=1"); //1
 ?>
<link type="text/css" href="css/home-page.css" rel="stylesheet" />
<style>
.border-right{
    border-right: 1px solid #ccc;
    padding: 60px 0px !important;
	margin:20px 0px;
}
.border-left{
    padding: 60px 0px !important;
	margin:20px 0px;
}
.btn-lg {
    padding: 15px 50px;
    font-size: 30px;
}
.btn-ig{
  padding: 10px 30px;
  font-size: 20px;
  margin-bottom:40px;
}
</style>

<style>
    .feature-list-card .clock_time {
    position: absolute;
    top: -7px;
    left: -19px;
    transform: rotate(-45deg);
    overflow: hidden;
}
.feature-list-card .clock_time div {
    background-color: orange;
    color: #fff;
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 5px;
    padding-bottom: 5px;
    width: 100px;
    text-align: center;
}
</style>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<?php  //include("sidebar.php"); ?>    
                        
			<!-- <div id="content" class="col-md-8 col-md-offset-2"> -->
			<div id="content" class="col-md-12">
				
				<div class="page-content" style="background-image: url('images/wizard-v3.jpg')">
					<div class="wizard-v3-content">
						<div class="wizard-form">
							<div class="wizard-header">
								<h3 class="heading">Complete your Registration</h3>
								<p>Fill all form field to go next step</p>
							</div>
							<form class="form-register" action="" method="post">
								<div id="xxform-total">
                                         
                           <div class="steps clearfix"><ul role="tablist"><li role="tab" aria-disabled="false" class="first done" aria-selected="false">
                           <a id="form-total-t-0" href="application.php" aria-controls="form-total-p-0"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-account"></i></span>
										<span class="step-text"  >Application</span>
									</div></a></li><li role="tab" aria-disabled="false" class="current" aria-selected="true"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><span class="current-info audible"> </span><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Quiz</span>
									</div></a></li>

									<li role="tab" aria-disabled="false"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-card"></i></span>
										<span class="step-text">Interview</span>
									</div></a></li>

									<li role="tab" aria-disabled="false"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-card"></i></span>
										<span class="step-text">Background Checks</span>
									</div></a></li>
									

									<li role="tab" aria-disabled="false"><a id="form-total-t-3" href="#form-total-h-3" aria-controls="form-total-p-3"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
										<span class="step-text">Payment Info </span>
									</div></a></li><li role="tab" aria-disabled="false"><a id="form-total-t-4" href="#form-total-h-4" aria-controls="form-total-p-4"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
										<span class="step-text">Legal Stuff </span>
									</div></a></li><li role="tab" aria-disabled="false" class="last"><a id="form-total-t-5" href="#form-total-h-5" aria-controls="form-total-p-5"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
										<span class="step-text">Training </span>
									</div></a></li></ul></div>


									<!-- SECTION 1 -->
									
                                                                        <section >
										<div class="inner">
											<h2 class="text-center">Quiz</h2>
                                                                                        <hr>
                                                                                        <div class="col-md-12" style="margin-bottom:3%">
                                                                                        <div class="row">
          <?php while ( $tRecord = mysqli_fetch_array($testList) ) {
              
              $result = mysqli_query($p2db, "SELECT * FROM questions WHERE TestID=".$tRecord['ID']) ;
              $numQuestions = $result->num_rows;
              
              if($numQuestions > 0) {
                  
                   $sql = " SELECT * FROM `tutor_tests_logs` WHERE tutor_id='".$_SESSION['ses_teacher_id']."' AND quiz_test_id = '".$tRecord['ID']."'";
                   $test_result = mysqli_fetch_object(mysqli_query($p2db,$sql));
                   if($test_result->status == 'completed' && $test_result->pass_status == 'pass')
                   {
                        $bg_color = '#61428f';
                        $status_text = 'Completed';
                        $mark_color = 'green';
                        $quiz_link = "quiz_result.php";
                   }
                   else if($test_result->status == 'completed' && $test_result->pass_status == 'fail')
                   {
                        $bg_color = '#61428f';
                        $status_text = 'Failed';
                        $mark_color = 'red';
                         $quiz_link = "quiz_result.php";
                   }
                   else if($test_result->status == 'in_process')
                   {
                       $bg_color = '#61428f'; 
                       $status_text = 'In Progress';
                       $mark_color = 'orange';
                       $quiz_link = "quiz_start.php?test_id=".$tRecord['ID'];
                   }
                   else
                   {
                       $bg_color = '#61428f'; 
                       $status_text = '';
                       $quiz_link = "quiz_start.php?test_id=".$tRecord['ID'];
                   }
          ?>
          
          <div class="col-md-4 "><div class="jumbotron feature-list-card" style="background-color: <?php echo $bg_color;?>">
               <?php if(!empty($status_text)) { ?>
                  <div class="clock_time" >
                                            <div style="background-color:<?php  echo $mark_color;?> !important;font-size:15px">
                                                <?php echo ucwords($status_text);?>
                                            </div>
                                        </div>
               <?php } ?>
                  <a href="<?php echo $quiz_link;?>"><h4 style="color:#fff;font-size: 25px;text-align: center;"><?php echo $tRecord['Name'];?> </h4></a></div> </div>
              <?php } ?>    
        <?php } ?>        
                        
 </div>  
                                                                                             </div> 
											<?php 
											//print_r($test_arr);  //die;
											// 6==English,5== math


											?>

											

<!--											<div class="form-row">
											

												<div id="content" class="col-md-6 border-right text-center">
												 <?php  if($test_arr[5]=='pass'|| $test_arr[5]=='fail'){?>
												  <a  href="quiz_result.php" 
												   class="btn btn-lg btn-danger" >Math</a>
												   <br/>Status-attempted
												  <?php  }else{?>

												 <a  href="quiz_start.php?type=math"  class="btn btn-lg btn-primary" >Math</a> 
												  <?php  } ?>
												    

												</div>



												<div id="content" class="col-md-6 border-left text-center">
												<?php  if($test_arr[6]=='pass'|| $test_arr[6]=='fail'){?>
												 <a  href="quiz_result.php" class="btn btn-lg btn-danger" >English</a>
												  <br/>Status-attempted
												 <?php  }else{?>
												  <a  href="quiz_start.php?type=english" class="btn btn-lg btn-primary" >English</a>

												 <?php  } ?>


												</div>

											</div>-->
											<br/>

											
										</div>

									</section>
									<!-- SECTION 2 -->
									<?php 

									if($_SESSION['ses_total_pass']>=1){

									?>
 
								<p class="text-right col-md-12">
								<a href="quiz_result.php?action=interview" 
								class="btn btn-ig btn-success">Skip</a>
								</p>

														<?php }else{ // not show?>
														<p style="display: none;" class="text-right col-md-12">
														<button    onclick="alert('Not allowed')"
														class="btn btn-ig btn-success">Next</button>
														</p>
														<?php }?>


								</div>
							</form>
						</div>
					</div>
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#main -->

<?php include("footer.php"); ?>
<?php  // print_r($_SESSION);   //die; ?>