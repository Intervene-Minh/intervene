<?php
/*****
 @Step 3: Interview
//////////
****/
 include("header.php");
 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //Interview Button



 print_r($_SESSION);
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

 ?>
<link type="text/css" href="css/home-page.css" rel="stylesheet" />
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
                                         
                          <div class="steps clearfix"><ul role="tablist"><li role="tab" aria-disabled="false" class="first done" aria-selected="false"><a id="form-total-t-0" href="#form-total-h-0" aria-controls="form-total-p-0"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-account"></i></span>
										<span class="step-text">Application</span>
									</div></a></li><li role="tab" aria-disabled="false" class="done" aria-selected="false"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Quiz</span>
									</div></a></li><li role="tab" aria-disabled="false" class="current" aria-selected="true"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><span class="current-info audible"> </span><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-card"></i></span>
										<span class="step-text">Interview</span>
									</div></a></li><li role="tab" aria-disabled="false"><a id="form-total-t-3" href="#form-total-h-3" aria-controls="form-total-p-3"><div class="title">
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
									
									<section>
										<div class="inner">
											<h3 class="text-center text-primary">Interview</h3>

											

											<div class="form-row">

												<div id="content" class="col-md-12">
												<!--  Step1 -->

												<p class="text-primary">Note- Interview step approved , you can login . !</p>

													<p>Click here to go for Calendly -<a class="btn btn-lg btn-prinary" href="<?=$calendly_url?>" >go</a>  </p>
													<!--  Step1:: go show Status -"Pending" -->


												<button name="save" class="btn btn-lg btn-danger" type="submit">Status Pending From-Admin</button><br/>
													<button name="save" class="btn btn-lg btn-success" type="submit">Approved</button>
													

												</div>

												

											</div>
											<br/>

											

											
										</div>

									</section>
									<!-- SECTION 2 -->



								<p class="txt-right"> <a href="application_status.php" class="btn btn-lg btn-success">Show Application Status</a>

														</p>


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