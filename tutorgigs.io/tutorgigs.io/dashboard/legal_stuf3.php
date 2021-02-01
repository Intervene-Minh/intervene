<?php
/*****
@ from1: privacy_form.
@ Form2 : legal_stuff_form2.php
 @Step 3: Interview
//////////
@ echo 'Bg checks pending'; 

 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //Interview Button

****/
  $page_name='legal_stuff.php';
 include("header.php");


echo $page_name='legal_stuff33.php'; 



 //Valid User///////
 if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
//validate failed user

 //print_r($_SESSION);

 $tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));
 //print_r($tutor_det);
 @extract($tutor_det);
 //echo $status_from_admin;

//Signup State
  $user_state_arr=unserialize($tutor_det['signup_state']);
 // print_r($user_state_arr); // Save state by user. 
 $profile=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));
$edit=unserialize($profile['profile_1']);
 //print_r($edit);
 //echo '=='.$profile['is_legal_1'];
  //echo '==is_legal_2'.$profile['is_legal_2'];
  ////Validate :is_legal_1 Form ///
  if($profile['is_legal_1']==1&&$profile['is_legal_2']==0){
  	$form2_url='legal_stuff_form2.php';
  	header("Location:".$form2_url); exit;

  }

$records=array();

$records['email']=(isset($profile['payment_email']))?$profile['payment_email']:null;
$records['phone']=(isset($profile['payment_phone']))?$profile['payment_phone']:null;   // $profile['payment_phone'];
//print_r($records);











##########################Application state arr########################################
if(isset($_POST['Request'])&&$_POST['is_sign_done']==1){
  /*@submit 3 Form.**/
	print_r($_POST); die;
		
  $data=array(); // terms_form_data term_admin_sign term_tutor_sign pri_exe_team_sign
   $data['pri_tutor_sign']=$_POST['sign_done'];//Save image
   $data['pre_tutor_date']=(!empty($_POST['sign_date']))?$_POST['sign_date']:date('Y-m-d H:i:s'); ///date('Y-m-d H:i:s');
   $data['pri_name']=$_POST['sign_name'];


  //print_r($data); die;

  //////////Save//////////////////
    $up=" UPDATE tutor_profiles SET is_legal_1='1',pri_tutor_date='".$data['pre_tutor_date']."'
    ,pri_name='".$data['pri_name']."', pri_tutor_sign='".$data['pri_tutor_sign']."' WHERE tutorid=".$_SESSION['ses_teacher_id'];
    //echo $up; die; 
    

    $result=mysql_query($up);
    /// Checkfor both from 1 mysql_query
    $profile=mysql_query("SELECT * FROM `tutor_profiles` WHERE is_legal_1=1 AND is_legal_2=1 AND tutorid=".$_SESSION['ses_teacher_id']);
     if(mysql_num_rows($profile)==1){

     	//Next Step : auto -Trainning step
     	//Next Step : auto -Trainning step
     	$next_step=$_SESSION['ses_curr_state_url']='training.php';
     	 $state_update=mysql_query(" UPDATE gig_teachers SET all_state_url='$next_step',legal_stuff='1' WHERE id=".$_SESSION['ses_teacher_id']);
     	 header("Location:".$next_step); exit;

     }else{
     	$form2_url='legal_stuff_form2.php';
     	header("Location:".$form2_url); exit;

     }
    //echo $profile; die; 
    //is_legal_1
    



    // die; 
  }
  // else{
  // 	exit('Please select sign');
  // }



######################################


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
									</div></a></li>
									<li role="tab" aria-disabled="false" class="done" aria-selected="false"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Quiz</span>
									</div></a></li>

									<li role="tab" aria-disabled="false" class="done" aria-selected="false"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Interview</span>
									</div></a></li>
									<li role="tab" aria-disabled="false" class="done" aria-selected="false"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Background Checks</span>
									</div></a></li>
									<li role="tab" aria-disabled="false" class="done" aria-selected="false"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Payment Info</span>
									</div></a></li>
									<!-- Background Checks -->




									<li role="tab" aria-disabled="false" class="current" aria-selected="true"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><span class="current-info audible"> </span><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-card"></i></span>
										<span class="step-text">Legal Stuff</span>
									</div></a></li>

									

									<li role="tab" aria-disabled="false" class="last"><a id="form-total-t-5" href="#form-total-h-5" aria-controls="form-total-p-5"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
										<span class="step-text">Training </span>
									</div></a></li></ul></div>


									<!--Content:Section-->
									<?php 
									/// Stage case at :legal_stuff.php
									$show_form=0;//Request form
									if($status_from_admin=='interview_rejected'){
										$msg='Application rejected';
									}elseif($payment_info!=1){
										
										$msg='Application not alowed';



									}elseif($payment_info==1){
										//current state.:prev compelted
										if($legal_stuff==0)
											$show_form=1; //see
										elseif($legal_stuff==2)
											$msg='pending approval';
										elseif($legal_stuff==1)
									     $msg='Completed, next step ';

									}

									//////////////
									//echo $msg; 
									//echo '======'.$show_form;

								//	if($legal_stuff==0){ // 
									// 
                                     if(!empty($msg)){
									?>

									<div class="ct_display clear">                   
                                  <h3 class="text-center text-success"><?=$msg?> <br/> </h3>
                                   </div>
                                   <?php }?>
                        
                        
                        
                         
                       
                         
                        
                  
                                    <style>
									    .legal-box{
											display:inline-block;
											width:100%;
											margin:20px 0px;
										} 
										.legal-text-box{
											display:inline-block;
											width:100%;
											border:1px solid #fbfbfb;
											padding:20px;
											height:400px;
											overflow:auto;
										}
										.legal-form-box{
											display:inline-block;
											width:100%;
										}
										.date-admin{
											border-bottom: 1px solid #000;
											color:#000;
											width: 60%;
                                            display: inline-block;
										}
										.tutor-input{
											border-bottom: 1px solid #000 !important;
											color:#000;
											width: 60%;
                                            display: inline-block;
											border: none;
										}
										.tutor-span{
											width: 100px;
                                            display: inline-block;
										}
										.row {
											margin-right: 0px;
											margin-left: 0px;
										}
									</style>



									 <?php  if($show_form==1){ ?>
									<section>
										<div class="inner">
											<h3 class="text-center text-primary">Legal Stuff</h3>


											<!--  Admin sign -->

											

											<div class="form-row">

												<div id="content" class="col-md-12">

												<div class="legal-box">
												    <?php include "form_privacy_terms.php";// Form1?>
												     
													 <div class="legal-form-box">

													 <div class="row">
																     <div class="col-md-6 col-sm-6">
																	    <img src="https://tutorgigs.io/dashboard/admin-sign.png"  class="img-responsive">
																	 </div>
                                                                     <div class="col-md-6 col-sm-6" style="margin:125px 0px 0px;">
																	     Date: <span class="date-admin">1/1/2019</span>
																	 </div>																	 
														  </div>

														<!--   Uploaded Data -->

														 <!-- if Signaure 1 Uploaded -->
                                 
                                    

                                                  <div class="row">

                                                   <?php if(!empty($profile['pri_tutor_sign'])){?>
                                   <p class="text-success">Signaure 1 Uploaded</p>
                                    <div title="signature 1 data">
                                    	<?php echo $profile['pri_tutor_sign'];?>
                                    </div>


									                 <h3>Tutor (User)</h3> 
																
																    <div class="row">
																		<div class="col-md-6 col-sm-6">
																			<span class="tutor-span">Print:</span>
														 <input type="text" class="tutor-input" value="<?=$profile['pri_name']?>">
														 <br>
																			<p style="padding: 8px 100px 0px;">Tutor (User)</p>
																		</div>
																		<div class="col-md-6 col-sm-6">
																			<span class="tutor-span">Date: </span>
															<input   class="tutor-input" disabled="" 
															value="<?=$profile['pri_tutor_date']?>" />
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-6 col-sm-6">
																			<span class="tutor-span">Signature:</span>
													 <input type="text" class="tutor-input" disabled=""
													  value="<?=$profile['pri_tutor_date']?>"   />
																		</div>
																	</div>	
																																 
														  </div>


														  <?php  }//endif;?>






													<!--  admin-signature -->
													<?php  if($profile['is_legal_1']==0){?>

												
												



													       <!-- Signature -->
													     
														  <div class="row">
														        <h3>Tutor (User)</h3> 
																
																    <div class="row">
																		<div class="col-md-6 col-sm-6">
																			<span class="tutor-span">Print:</span> 
																<input type="text" class="tutor-input" name="xx" disabled=""
																 value="<?=$_SESSION['login_user']?>">
																<br>
																			<p style="padding: 8px 100px 0px;">Tutor (User)</p>
																		</div>
																		<?php 

																		?>
																		<div class="col-md-6 col-sm-6">
																			<span class="tutor-span">Date: </span>
															<!-- <input type="date" required class="tutor-input" name="sign_date"> -->
															<input type="text " readonly="" class="tutor-input" value="<?=date('m/d/Y')?>" 
															 name="sign_date">
																		</div>
																	</div>


																	<!-- <div class="row">
																		<div class="col-md-6 col-sm-6">
																			<span class="tutor-span">Signature:</span>
																			 <input type="text"  
																			 class="tutor-input" name="sign_name" required>
																		</div>
																	</div>	 -->

																	<div class="row">

										

                                                 <!-- fgfg -->
                                                 
												<div id="content" class="col-md-12">
												<!-- <p> Show signature form data</p> -->

												<?php  include "home_sign.php";?>
												<input type="hidden" id="sign_name" name="sign_name" value="<?=$_SESSION['login_user']?>">
												<input type="hidden" id="is_sign_done" name="is_sign_done" value="0">
												<input type="hidden" id="sign_done" name="sign_done" value="">
												<!--  form Field -->

												</div>
												</div>
																	<!-- Singautrexx -->








																															 
														  </div>


														   <div class="row">
														   <p class="text-center" style="margin-top:20px;">

                                            
                                                  <button type="submit" name="Request" 
                          class="btn btn-primary btn-sm" id="btn btn-lg btn-primary"  >Submit Form</button> </p>
                                                      </div>

                                                      <?php }?>
                                                      <!--  admin-signature -->


													



													 </div>
                                                </div> 												
												

										
												</div>	
												</div>

												<br/>
											
										</div>

									</section>
									<?php  }?>
									<!--Content:Section-->


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

<script type="text/javascript">
  $(document).ready(function(){
 
  // is_sign_done

  /// $('#form_class').submit(function() { 
   $('form').submit(function() {

   	var is_sign_ok=$('#is_sign_done').val();
   	// alert('PostNeow='+is_sign_ok);
   	 // $('input:radio', this).is(':checked')
    if (is_sign_ok==1) {
        // everything's fine...
        alert('Submitted!');
    }else{
    	if (is_sign_ok!=1){
    		 alert('Please Confirm signature!');

    	}
        
        return false;
    }
});
  });

 </script>

<?php include("footer.php"); ?>