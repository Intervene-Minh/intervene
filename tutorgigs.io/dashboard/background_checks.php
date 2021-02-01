<?php
/*****
 @Step 3: Interview
//////////
@ echo 'Bg checks pending'; 

 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //Interview Button
  // Can access web site.
//$_SESSION['ses_access_website']='no'; // 1==if all 4 step completed by user
 // $_SESSION['ses_access_website']; 


 //print_r($_SESSION);
****/
 include("header.php");
 $tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));
 //print_r($tutor_det);
 @extract($tutor_det);
 //echo $status_from_admin;

//Signup State
  $user_state_arr=unserialize($tutor_det['signup_state']);
 // print_r($user_state_arr); // Save state by user. 
 $data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));
$edit=unserialize($data2['profile_1']);
 //print_r($edit);









$next_url='https://www.screenmenow.com/v2/home?c=SLRT_06151'; 
//////Add profile 1/////////////////////
  if(isset($_GET['action'])&&$_GET['action']=='schedule'){
    $next_url='https://www.screenmenow.com/v2/home?c=SLRT_06151'; 
   // echo 'Go payment info  step';  die;
    schedule_for_curr_step($status='processing');

     // $sql=" UPDATE gig_teachers SET background_checks='2',status_from_admin='processing' WHERE id=".$_SESSION['ses_teacher_id'];
   //   echo '<pre>', $sql; die; 
    //$result=mysql_query($sql);

    header("location:".$next_url); exit;
 
  }
/*
-pending
-processing 
- approval_pending
-approved  : by 

**/

///////////////////////////////////////
  function schedule_for_curr_step($status){
  	//  Request 2 for curr Step : 
  	// when approve from admin 

  $up=" UPDATE gig_teachers SET background_checks='2',status_from_admin='$status' WHERE id=".$_SESSION['ses_teacher_id'];
    $result=mysql_query($up);

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
								<p>Fill out entire form field to go to the next step</p>
							</div>
							<form class="form-register" action="" method="post">
								<div id="xxform-total">
                                         
                          <div class="steps clearfix"><ul role="tablist"><li role="tab" aria-disabled="false" class="first done" aria-selected="false"><a id="form-total-t-0" href="#form-total-h-0" aria-controls="form-total-p-0"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-account"></i></span>
										<span class="step-text">Application</span>
									</div></a></li>
									<li role="tab" aria-disabled="false" class="done" aria-selected="false"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Quiz</span>
									</div></a></li>

									<li role="tab" aria-disabled="false" class="done" aria-selected="false"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Interview</span>
									</div></a></li>


									<li role="tab" aria-disabled="false" class="current" aria-selected="true"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><span class="current-info audible"> </span><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-card"></i></span>
										<span class="step-text">Background Checks</span>
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
									<?php if($status_from_admin=='interview_rejected'){ ?>
									<div class="ct_display clear">                   
                        <form method="post">
                        
                        
                        <h3 class="text-center text-primary"> 
                        Your application has been rejected <br>
                         
                       
                         
                        
                    </h3></form></div>
									<?php }elseif($background_checks==2|| $background_checks==1){  
										$status_show='';
										if($background_checks==1)
											$status_show='Step approved';
										if($background_checks==2)
											$status_show='Pending approval'; // in processing 

										?>
							   

							     <div class="ct_display clear">                   
                                  <form method="post">
                        
                        
                        <h3 class="text-center text-success">Status-<?=$status_show;   //=$background_checks.'='.$status_show?>  <br>    </h3>
                        <p class="text-center">Click Below to Proceed to Background Check Steps   </p>
                        <p class="text-center">
                        <a target="_blank" href="<?=$next_url?>" 
                        class="btn btn-lg btn-warning" title="Click for Background Check">Click Here</a></p>
                         
                       
                         
                        
                  
                                   </form></div>
                                   <?php }else{?>


									
									<section>
										<div class="inner">
											<h3 class="text-center text-primary">Background Checks</h3>

											

											<div class="form-row">

												<div id="content" class="col-md-12">
												<!--  Step1 -->

												<!-- <p class="text-primary">Note- Interview step approved , you can login . !</p> -->

									<p class="text-center" >Click Below to Proceed to Background Check Steps   </p>
									<br/>
													
												</div>

												

											</div>
											<br/>

											

											
										</div>

									</section> 
									<!-- SECTION 2 -->



								<p class="text-center"> <a target="_blank" href="./background_checks.php?action=schedule" class="btn btn-lg btn-success">Click </a>

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