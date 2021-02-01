<?php
/*****
 @Step 3: Interview
//////////
@ echo 'Bg checks pending'; 

 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //Interview Button
 @ if_legal_1 DONE move to if_legal_2 Form 

****/
  $page_name='legal_stuff.php';
 include("header.php");






 //Valid User///////
 if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
//validate failed user


 $tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));
 //print_r($tutor_det);
 @extract($tutor_det);
 //echo $status_from_admin;

//Signup State
  $user_state_arr=unserialize($tutor_det['signup_state']);
 // print_r($user_state_arr); // Save state by user. 
 $profile=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));
$edit=unserialize($profile['profile_1']);
 //print_r($edit); die; 

$records=array();

$records['email']=(isset($profile['payment_email']))?$profile['payment_email']:null;
$records['phone']=(isset($profile['payment_phone']))?$profile['payment_phone']:null;   // $profile['payment_phone'];
//print_r($records);











##########################Application state arr########################################
//////Add profile 1/////// if(isset($_POST['Request']) ) {

//if(isset($_GET['action'])&&$_GET['action']=='schedule'){
	if(isset($_POST['Request']) ){

		//print_r($_POST); die;
		/**
		@ save signature and other info. 
		// pri_tutor_sign  term_tutor_sign

		**/

    $calendly_url='https://calendly.com/tutorgigs';
    //echo 'Go interview step';  die;

    //$up=" UPDATE gig_teachers SET legal_stuff='2' WHERE id=".$_SESSION['ses_teacher_id'];
  $data=array(); // terms_form_data term_admin_sign term_tutor_sign pri_exe_team_sign
   $data['pri_tutor_sign']=$_POST['sign_done'];
   $data['pre_tutor_date']=date('Y-m-d H:i:s');
  // print_r($data); die;

  //////////Save//////////////////

    $up=" UPDATE tutor_profiles SET pre_tutor_date='".$data['pre_tutor_date']."',pri_tutor_sign='".$data['pri_tutor_sign']."' WHERE tutorid=".$_SESSION['ses_teacher_id'];
    

    $result=mysql_query($up);
    header("Location:".$_SERVER['PHP_SELF']); exit;



    // die; 
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
                        
                        
                        
                         
                       
                         
                        
                  
                                  



									 <?php  if($show_form==1){ ?>
									<section>
										<div class="inner">
											<h3 class="text-center text-primary">Legal Stuff</h3>

											

											<div class="form-row">

												<div id="content" class="col-md-12">
												<p> Show signature form data</p>

												<?php  include "home_sign.php";?>
												<input type="hidden" id="is_sign_done" name="is_sign_done" value="0">
												<input type="hidden" id="sign_done" name="sign_done" value="">
												<!--  form Field -->
												 <br/>
												<input type="text" required="" placeholder="sign_name" id="sign_name" name="sign_name" value="">
												 <br/>
												<input required="" type="text" placeholder="sign_date"
												id="sign_date" name="sign_date" value="">




												<p class="text-center">

                          <button type="submit" name="Request" 
                          class="btn btn-primary btn-sm" id="btn btn-lg btn-primary"  >Submit Form</button> </p>
                                  <!-- if Signaure 1 Uploaded -->
                                  <?php if(!empty($profile['pri_tutor_sign'])):?>
                                   <p class="text-success">Signaure 1 Uploaded</p>
                                    <div title="signature 1 data">
                                    	<?php echo $profile['pri_tutor_sign'];?>
                                    </div>
                                    <?php endif;?>
                                    <!-- if Signaure 1 Uploaded -->

										
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
        alert('Ok,');
    }else{
    	if (is_sign_ok!=1){
    		 alert('Please Confirm signature!');

    	}
        alert('Please select assessment!');
        return false;
    }
});
  });

 </script>

<?php include("footer.php"); ?>