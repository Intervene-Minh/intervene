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

		//print_r($_POST); die;
		/**
		@ save signature and other info. 
		// pri_tutor_sign  term_tutor_sign
		 $calendly_url='https://calendly.com/tutorgigs';

		**/
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
                                  
                                   </div>
                                   <?php }?>
                        
                        
                        
                         
                       
                         
                        
                  
                                    <style>
									    .page-container{
											width:100%;
											display:inline-block;
											border:1px solid #fbfbfb;
											padding:20px;
											margin:0px 0px 20px;
										}
										.legal-text-box{
											display:inline-block;
											width:100%;
											height:400px;
											overflow:auto;
											padding:20px;
										}
										.page-number{
											border-bottom:1px solid #ccc;
											margin-bottom:20px;
										}
										.row {
											margin-right: 0px;
											margin-left: 0px;
										}
										h3{
											margin-top:0px;
										}
										table{border: 1px solid #ddd;}
										.requester-left{
											border-right:1px solid #000;
											width:100%;
											display:inline-block;
											padding:0px 20px;
											min-height: 117px;
										}
										.requester-left span{
											font-size:40px;
											font-weight:700;
										}
										.requester-right{
											border-left:1px solid #000;
											width:100%;
											display:inline-block;
											padding:0px 20px;
											min-height: 117px;
										}
										.tax-form{
											border-top: 1px solid #000;
											width:100%;
											display:inline-block;
										}
										
										.tax-form-form{
											width:100%;
											border-left:1px solid #000;
											display:inline-block;
											padding:20px 0px 0px;
										}
										.form-control-2{
											width:100%;
											background-color:#fff;
											border-bottom:1px solid #000 !important;
											box-shadow:none;
											color:#000;
											border:none;
											padding:0px 10px;
										}
										.form-inline{
											margin-top:7px;
										}
										.form-control-3{
											background-color:#fff;
											border-bottom:1px solid #000 !important;
											box-shadow:none;
											color:#000;
											border:none;
											padding:0px 20px;
										}
										.inline-vvv{
											background-color:#fff;
											border-bottom:1px solid #000 !important;
											box-shadow:none;
											color:#000;
											border:none;
											margin-left:6px;
											margin-bottom:10px;
										}
										.checkbox-inline, .radio-inline{
											margin-right:10px;
											font-size: 12px;
											margin-top:2px;
											padding: 0px 0px 0px 30px;
										}
										.checkbox-inline + .checkbox-inline, .radio-inline + .radio-inline{
											margin-left:0px;
											font-size: 12px;
											margin-top:2px
										}
										.tax-right{
											border-left:1px solid #000;
											width:100%;
											display:inline-block;
											padding:0px 0px;
										}
										label {
											padding: 5px 10px;
										}
										.form-group {
											margin-bottom: 0px;
										}
										.stepss{
											width:100%;
											display:inline-block;
											border-bottom:1px solid #000;
											font-size:16px;
											color:#000;
											margin: 0px 0px 10px;
                                            padding: 10px 0px;
										}
										.stepss span{
											background:#000;
											padding:10px;
											color:#fff;
											margin-right:20px;
										}
										.sign-box{
											border-top:1px solid #000;
											border-bottom:1px solid #000;
											width:100%;
											display:inline-block;
											padding:0px 0px;
										}
										.sign-text{
											color:#000;
											border-right:1px solid #000;
											font-size:30px;
										}
										.sms-verification-simple{
											  position: relative;
											  width: 300px;
											  margin: 10px 0; 
											}

											.sms-verification-simple input{
											  border: none;
											  box-shadow: none;
											  outline: none;
											  font-size: 25px;
											  width: 300px;
											  height: 50px;
											  letter-spacing: 15.3px;
											  padding: 0 15px;
											  -webkit-appearance: none;
											  -moz-appearance:    none;
											  appearance:         none;
											  font-weight: 320;
											  background: transparent;
											  color: #000;
											  overflow: hidden;
											  
											}

											.sms-verification-simple input:focus, .sms-verification-simple input:active, .sms-verification-simple input:visited{
											  outline: none!important;
											  border: none!important;
											  box-shadow: none!important;
											  
											}

											.sms-verification-simple span{
											  position: absolute;
											  bottom: 0;
											  left: 10px;
											  width: 23px;
											  height: 2px;
											  background-color: #000;
											  border-radius: 30px;
											}
											.sms-verification-simple span:nth-child(1){
											  left: 0px;
											}
											.sms-verification-simple span:nth-child(2){
											  left: 40px;
											}
											.sms-verification-simple span:nth-child(3){
											  left: 70px;
											}
											.sms-verification-simple span:nth-child(4){
											  left: 100px;
											}
											.sms-verification-simple span:nth-child(5){
											  left: 130px;
											}
											.sms-verification-simple span:nth-child(6){
											  left: 160px;
											}
											.sms-verification-simple span:nth-child(7){
											  left: 190px;
											}
											.sms-verification-simple span:nth-child(8){
											  left: 220px;
											}
											.sms-verification-simple span:nth-child(9){
											  left: 250px;
											}
									</style>
                                    <script>

                                    $(".multiple-input").keyup(function () {
										if (this.value.length == this.maxLength) {
										  $(this).next('.multiple-inputs').focus();
										}
									});

										/*..............................................................................................
										* jQuery function for verification input
										......................................................................................................*/

										$('.multiple-input1').on('keyup', function(e){
											e.preventDefault();
											var max_length = parseInt($(this).attr('maxLength'));
											var _length = parseInt($(this).val().length);
											if(_length >= max_length) {
												$('.multiple-input2').focus().removeAttr('readonly');
												// $(this).attr('readonly', 'readonly');
											}
											if(_length <= 0){
												return;
											}
										});
										$('.multiple-input2').on('keyup', function(e){
											e.preventDefault();
											var max_length = parseInt($(this).attr('maxLength'));
											var _length = parseInt($(this).val().length);
											if(_length >= max_length) {
												$('.multiple-input3').focus().removeAttr('readonly');
												// $(this).attr('readonly', 'readonly');
											}
											if(_length <= 0){
												$('.multiple-input1').focus().removeAttr('readonly');
												$(this).attr('readonly', 'readonly');
											}
										});
										 $('.multiple-input3').on('keyup', function(e){
											e.preventDefault();
											var max_length = parseInt($(this).attr('maxLength'));
											var _length = parseInt($(this).val().length);
											if(_length >= max_length) {
												$('.multiple-input4').focus().removeAttr('readonly');
												// $(this).attr('readonly', 'readonly');
											}
											if(_length <= 0){
												$('.multiple-input2').focus().removeAttr('readonly');
												$(this).attr('readonly', 'readonly');
											}
										});
										$('.multiple-input4').on('keyup', function(e){
											e.preventDefault();
											var max_length = parseInt($(this).attr('maxLength'));
											var _length = parseInt($(this).val().length);
											if(_length >= max_length) {
												return;
											}
											if(_length <= 0){
												$('.multiple-input3').focus().removeAttr('readonly');
												$(this).attr('readonly', 'readonly');
											}
										});
                                    </script>									


									 <?php  if($show_form==1){ ?>
									<section>
									        <div class="page-container">
												<div class="row">
													<div class="col-md-3">
														 <div class="requester-left">
															Form   <span>W-9</span><br> (Rev. October 2018) Department of the Treasury  Internal Revenue Service  
														 </div>
													</div>
													<div class="col-md-6">
													      <h4 class="text-center">Request for Taxpayer<br>Identification Number and Certification</h4>
														  <p class="text-center">Go to www.irs.gov/FormW9 for instructions and the latest information.</p>
														  
													</div>
													<div class="col-md-3">
														<div class="requester-right">
														   <p><b>Give Form to the requester. Do not send to the IRS.</b></p>
														</div>
													</div>
												</div>
												<div class="row">
												    <div class="tax-form">
													    <div class="col-md-1">
                                                        </div> 														
														 <div class="col-md-11">
														    <div class="tax-form-form">
															     <div class="form-group">
																	<label for="email">1 Name (as shown on your income tax return). Name is required on this line; do not leave this line blank</label>
																	<input type="text" name="name_income_tax" placeholder="name" class="form-control-2" id="email">
																  </div>
																  <div class="form-group">
																	<label for="pwd">2	Business name/disregarded entity name, if different from above</label>
																	<input type="text" name="business_name" placeholder="Business name" class="form-control-2" id="pwd">
																  </div>
																  <div class="row"  style="border-bottom:1px solid #000;">
																        <div class="col-md-8" style="padding:0px;">
																	        <div class="form-group">
																				<label for="pwd">Check appropriate box for federal tax classification of the person whose name is entered on line 1. Check only one of the following seven boxes</label>
																				<label class="checkbox-inline">
																				  <input name="tax_classification" type="checkbox" value="">Individual/sole proprietor or single-member LLC
																				</label>
																				<label class="checkbox-inline">
																				  <input name="tax_classification" type="checkbox" value=""> C Corporation 
																				</label>
																				<label class="checkbox-inline">
																				  <input name="tax_classification" type="checkbox" value="">S Corporation 
																				</label>
																				<label class="checkbox-inline">
																				  <input name="tax_classification"  type="checkbox" value="">Partnership
																				</label>
																				<label class="checkbox-inline">
																				  <input name="tax_classification" type="checkbox" value="">Trust/estate
																				</label><br>
																				<label class="checkbox-inline">
																				  <input name="tax_classification"  type="checkbox" value="">Limited liability company. Enter the tax classification (C=C corporation, S=S corporation, P=Partnership <input type="text" class="inline-vvv" id="pwd">
																				</label>
																				<p  style="padding:0px 10px;"><b>Note:</b> Check the appropriate box in the line above for the tax classification of the single-member owner.  Do not check LLC if the LLC is classified as a single-member LLC that is disregarded from the owner unless the owner of the LLC is another LLC that is not disregarded from the owner for U.S. federal tax purposes. Otherwise, a single-member LLC that is disregarded from the owner should check the appropriate box for the tax classification of its owner. </p>
																				<label class="checkbox-inline">
																				  <input name="tax_classification_other" type="checkbox" value="">Other (see instructions)  <input name="tax_classification_text"  type="text" class="inline-vvv" id="pwd">
																				</label>
																			 </div>
																	  
																	    </div>
																		<div class="col-md-4" style="padding:0px;">
																	         <div class="tax-right" style=" min-height: 290px;">
																			    <div class="form-group">
																					<label for="email">4  Exemptions (codes apply only to certain entities, not individuals; see instructions on page 3):</label>
																				 </div>
																				 <div class="form-group">
																					<label for="email">Exempt payee code (if any)</label>
																					<input placeholder="Exempt payee code" name="exempt_payee_code" type="text" class="form-control-2" id="email">
																				 </div>
																				 <div class="form-group">
																					<label for="email">Exemption from FATCA reporting code (if any)</label>
																					<input placeholder="Exemption from FATCA" name="exempt_from_fatca" type="text" class="form-control-2" id="email">
																				 </div>
																			    <p style="padding:0px 10px;">(Applies to accounts maintained outside the U.S.)</p>
																			 </div>
																	  
																	    </div>
																  </div>
																  <div class="row">
																        <div class="col-md-7" style="padding:0px;">
                                                                            <div class="form-group">
																				<label for="email">5  Address (number, street, and apt. or suite no.) See instructions.</label>
																				<input placeholder="Address" name="income_tax_address" type="text" class="form-control-2" id="email">
																			</div>
																	        <div class="form-group">
																				<label for="email">6  City, state, and ZIP code</label>
																				<input placeholder="City, state, and ZIP code" name="income_tax_address_city" type="text" class="form-control-2" id="email">
																			</div>
																	    </div>
																		<div class="col-md-5" style="padding:0px;">
																	         <div class="tax-right"  >
																				 <div class="form-group">
																					<label for="email">Requester’s name and address (optional)</label>
																					<textarea placeholder="name and address" name="income_tax_name_and_address" type="text" class="form-control-2" id="email" style="height:75px;"></textarea>
																				 </div>
																			 </div>
																	  
																	    </div>
																  </div>
																  <div class="form-group">
																		<label for="email">7 List account number(s) here (optional)</label>
																		<input placeholder="List account number(s)" type="text"name="list_account_number" class="form-control-2" id="email">
																  </div>
															</div>
														 </div>
													</div>
												</div>
												<div class="row">
												    <div class="col-md-12">
													     <h6 class="stepss"><span>Part I</span>Taxpayer Identification Number (TIN) </h6>
													</div>
												    <div class="row">
													     <div class="col-md-7"> 
														    <p>
															  Enter your TIN in the appropriate box. The TIN provided must match the name given on line 1 to avoid backup withholding. For individuals, this is generally your social security number (SSN). However, for a resident alien, sole proprietor, or disregarded entity, see the instructions for Part I, later. For other entities, it is your employer identification number (EIN). If you do not have a number, see How to get a TIN, later. 
															  <br><br>
															  Note: If the account is in more than one name, see the instructions for line 1. Also see What Name and Number To Give the Requester for guidelines on whose number to enter.

															</p>
														 </div>
														 <div class="col-md-5"> 
														    <div class="form-group">
																<label for="email">Social security number</label>
																<div class="sms-verification-simple">
																  <input type="text" name="social_security_number" class="form-control" maxlength = "9" placeholder="" aria-describedby="basic-addon1">
																  <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
																</div>
															</div>
															<p style="margin:10px 0px;padding:0px 20px">or</p>
															<div class="form-group">
																<label for="email">Employer identification number </label>
																<div class="sms-verification-simple">
																  <input name="employer_identification_number " type="text" class="form-control" maxlength = "9" placeholder="" aria-describedby="basic-addon1">
																  <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
																</div>
															</div>
														 </div>
												    </div>
                                                    <div class="col-md-12">
													     <h6 class="stepss"><span>Part II</span> Certification  </h6>
														 <p>
														   Under penalties of perjury, I certify that: <br><br>
														   1. The number shown on this form is my correct taxpayer identification number (or I am waiting for a number to be issued to me); and <br><br>
														   2. I am not subject to backup withholding because: (a) I am exempt from backup withholding, or (b) I have not been notified by the Internal Revenue Service (IRS) that I am subject to backup withholding as a result of a failure to report all interest or dividends, or (c) the IRS has notified me that I am no longer subject to backup withholding; and <br><br>
														   3. I am a U.S. citizen or other U.S. person (defined below); and <br><br>
														   4. The FATCA code(s) entered on this form (if any) indicating that I am exempt from FATCA reporting is correct. <br><br>
														   <b>Certification instructions.</b> You must cross out item 2 above if you have been notified by the IRS that you are currently subject to backup withholding because you have failed to report all interest and dividends on your tax return. For real estate transactions, item 2 does not apply. For mortgage interest paid, acquisition or abandonment of secured property, cancellation of debt, contributions to an individual retirement arrangement (IRA), and generally, payments other than interest and dividends, you are not required to sign the certification, but you must provide your correct TIN. See the instructions for Part II, later. 
														 </p>
													</div>
													<div class="sign-box">
													    <div class="row">
														    <div class="col-md-2">
													            <div class="sign-text">Sign Here</div>
															</div>
															<div class="col-md-10">
															      <div class="form-inline" >
																	  <div class="form-group">
																		<label for="email">Signature of U.S. person </label>
																		<input placeholder="Signature" name="signature_person" type="text" class="form-control-3">
																	  </div>
																	  <div class="form-group">
																		<label for="pwd"> Date:</label>
																		<input placeholder="date" name="signature_date" type="date" class="form-control-3">
																	  </div>
																   </div> 
															</div>
														</div>
													</div>
                                                    <div class="row">
													     <div class="col-md-6"> 
															<h4> General Instructions</h4> 
															<p>Section references are to the Internal Revenue Code unless otherwise noted.</p> 
															<p>Future developments. For the latest information about developments related to Form W-9 and its instructions, such as legislation enacted after they were published, go to www.irs.gov/FormW9.</p>
															<h4>Purpose of Form </h4>
															<p>An individual or entity (Form W-9 requester) who is required to file an information return with the IRS must obtain your correct taxpayer identification number (TIN) which may be your social security number (SSN), individual taxpayer identification number (ITIN), adoption taxpayer identification number (ATIN), or employer identification number (EIN), to report on an information return the amount paid to you, or other amount reportable on an information return. Examples of information returns include, but are not limited to, the following.</p> 
															<p>• Form 1099-INT (interest earned or paid)</p>
														 </div>
														 <div class="col-md-6"> 
														      <p>• Form 1099-DIV (dividends, including those from stocks or mutual funds)</p>
														      <p>• Form 1099-MISC (various types of income, prizes, awards, or gross proceeds)</p> 
															  <p>• Form 1099-B (stock or mutual fund sales and certain other transactions by brokers)</p> 
															  <p>• Form 1099-S (proceeds from real estate transactions) </p>
															  <p>• Form 1099-K (merchant card and third party network transactions)</p> 
															  <p>• Form 1098 (home mortgage interest), 1098-E (student loan interest), 1098-T (tuition)</p> 
															  <p>• Form 1099-C (canceled debt) </p>
															  <p>• Form 1099-A (acquisition or abandonment of secured property) Use Form W-9 only if you are a U.S. person (including a resident alien), to provide your correct TIN. If you do not return Form W-9 to the requester with a TIN, you might be subject to backup withholding. See What is backup withholding, later.</p>
														 </div>
												    </div>      													
												</div>
											</div>   
										   <div class="legal-text-box">		        
										        <div class="page-container">
													<div class="row">
														<div class="page-number row"><div class="text-left col-md-6">Form W-9 (Rev. 10-2018)</div><div class="text-right col-md-6">Page <b>2 </b></div></div>
														
													</div>
													<div class="row">
													     <div class="col-md-6">
														    <p>By signing the filled-out form, you:</p>
															<p>1.	Certify that the TIN you are giving is correct (or you are waiting for a number to be issued),</p>
															<p>2.	Certify that you are not subject to backup withholding, or</p>
															<p>3.	Claim exemption from backup withholding if you are a U.S. exempt payee. If applicable, you are also certifying that as a U.S. person, your allocable share of any partnership income from a U.S. trade or business is not subject to the withholding tax on foreign partners' share of effectively connected income, and</p>
															<p>4.	Certify that FATCA code(s) entered on this form (if any) indicating that you are exempt from the FATCA reporting, is correct. See What is FATCA reporting, later, for further information.</p>
															<p><b>Note:</b> If you are a U.S. person and a requester gives you a form other than Form W-9 to request your TIN, you must use the requester’s form if it is substantially similar to this Form W-9.</p>
															<p><b>Definition of a U.S. person.</b> For federal tax purposes, you are considered a U.S. person if you are:</p>
															<p>•	An individual who is a U.S. citizen or U.S. resident alien;</p>
															<p>•	A partnership, corporation, company, or association created or organized in the United States or under the laws of the United States;</p>
															<p>•	An estate (other than a foreign estate); or</p>
															<p>•	A domestic trust (as defined in Regulations section 301.7701-7).</p>
															<p><b>Special rules for partnerships.</b> Partnerships that conduct a trade or business in the United States are generally required to pay a withholding tax under section 1446 on any foreign partners’ share of effectively connected taxable income from such business. Further, in certain cases where a Form W-9 has not been received, the rules under section 1446 require a partnership to presume that a partner is a foreign person, and pay the section 1446 withholding tax. Therefore, if you are a U.S. person that is a partner in a partnership conducting a trade or business in the United States, provide Form W-9 to the partnership to establish your U.S. status and avoid section 1446 withholding on your share of partnership income.</p>
															<p>In the cases below, the following person must give Form W-9 to the partnership for purposes of establishing its U.S. status and avoiding withholding on its allocable share of net income from the partnership conducting a trade or business in the United States.</p>
															<p>•	In the case of a disregarded entity with a U.S. owner, the U.S. owner of the disregarded entity and not the entity;</p>
															<p>•	In the case of a grantor trust with a U.S. grantor or other U.S. owner, generally, the U.S. grantor or other U.S. owner of the grantor trust and not the trust; and</p>
															<p>•	In the case of a U.S. trust (other than a grantor trust), the U.S. trust (other than a grantor trust) and not the beneficiaries of the trust.</p>
															<p><b>Foreign person.</b> If you are a foreign person or the U.S. branch of a foreign bank that has elected to be treated as a U.S. person, do not use Form W-9. Instead, use the appropriate Form W-8 or Form 8233 (see Pub. 515, Withholding of Tax on Nonresident Aliens and Foreign Entities).</p>
															<p><b>Nonresident alien who becomes a resident alien.</b> Generally, only a nonresident alien individual may use the terms of a tax treaty to reduce or eliminate U.S. tax on certain types of income. However, most tax treaties contain a provision known as a “saving clause.” Exceptions specified in the saving clause may permit an exemption from tax to continue for certain types of income even after the payee has otherwise become a U.S. resident alien for tax purposes.</p>
															<p>If you are a U.S. resident alien who is relying on an exception contained in the saving clause of a tax treaty to claim an exemption from U.S. tax on certain types of income, you must attach a statement to Form W-9 that specifies the following five items.</p>
															<p>1.	The treaty country. Generally, this must be the same treaty under which you claimed exemption from tax as a nonresident alien.</p>
															<p>2.	The treaty article addressing the income.</p>
															<p>3.	The article number (or location) in the tax treaty that contains the saving clause and its exceptions.</p>
															<p>4.	The type and amount of income that qualifies for the exemption from tax.</p>
															<p>5.	Sufficient facts to justify the exemption from tax under the terms of the treaty article.</p>
														 </div>
														<div class="col-md-6">
                                                            <p><b>Example.</b> Article 20 of the U.S.-China income tax treaty allows an exemption from tax for scholarship income received by a Chinese student temporarily present in the United States. Under U.S. law, this student will become a resident alien for tax purposes if his or her stay in the United States exceeds 5 calendar years. However, paragraph 2 of the first Protocol to the U.S.-China treaty (dated April 30, 1984) allows the provisions of Article 20 to continue to apply even after the Chinese student becomes a resident alien of the United States. A Chinese student who qualifies for this exception (under paragraph 2 of the first protocol) and is relying on this exception to claim an exemption from tax on his or her scholarship or fellowship income would attach to Form W-9 a statement that includes the information described above to support that exemption.</p>
															<p>If you are a nonresident alien or a foreign entity, give the requester the appropriate completed Form W-8 or Form 8233.</p>
															<h4>Backup Withholding</h4>
															<p><b>What is backup withholding?</b> Persons making certain payments to you must under certain conditions withhold and pay to the IRS 24% of such payments. This is called “backup withholding.” Payments that may be subject to backup withholding include interest, tax-exempt interest, dividends, broker and barter exchange transactions, rents, royalties, nonemployee pay, payments made in settlement of payment card and third party network transactions, and certain payments from fishing boat operators. Real estate transactions are not subject to backup withholding.</p>
															<p>You will not be subject to backup withholding on payments you receive if you give the requester your correct TIN, make the proper certifications, and report all your taxable interest and dividends on your tax return.</p>
															<p><b>Payments you receive will be subject to backup withholding if:</b></p>
															<p>1.	You do not furnish your TIN to the requester,</p>
															<p>2.	You do not certify your TIN when required (see the instructions for Part II for details),</p>
															<p>3.	The IRS tells the requester that you furnished an incorrect TIN,</p>
															<p>4.	The IRS tells you that you are subject to backup withholding</p>
															<p>because you did not report all your interest and dividends on your tax return (for reportable interest and dividends only), or</p>
															<p>5.	You do not certify to the requester that you are not subject to backup withholding under 4 above (for reportable interest and dividend accounts opened after 1983 only).</p>
															<p>Certain payees and payments are exempt from backup withholding. See Exempt payee code, later, and the separate Instructions for the Requester of Form W-9 for more information.</p>
															<p>Also see Special rules for partnerships, earlier.</p>
															<h4>What is FATCA Reporting?</h4>
															<p>The Foreign Account Tax Compliance Act (FATCA) requires a participating foreign financial institution to report all United States account holders that are specified United States persons. Certain payees are exempt from FATCA reporting. See Exemption from FATCA reporting code, later, and the Instructions for the Requester of Form W-9 for more information.</p>
															<h4>Updating Your Information</h4>
															<p>You must provide updated information to any person to whom you claimed to be an exempt payee if you are no longer an exempt payee and anticipate receiving reportable payments in the future from this person. For example, you may need to provide updated information if you are a C corporation that elects to be an S corporation, or if you no longer are tax exempt. In addition, you must furnish a new Form W-9 if the name or TIN changes for the account; for example, if the grantor of a grantor trust dies.</p>
															<h4>Penalties</h4>
															<p><b>Failure to furnish TIN.</b> If you fail to furnish your correct TIN to a requester, you are subject to a penalty of $50 for each such failure unless your failure is due to reasonable cause and not to willful neglect.</p>
															<p><b>Civil penalty for false information with respect to withholding.</b> If you make a false statement with no reasonable basis that results in no backup withholding, you are subject to a $500 penalty.</p>

														</div>
													</div>
												</div> 
												<div class="page-container">
													<div class="row">
														<div class="page-number row"><div class="text-left col-md-6">Form W-9 (Rev. 10-2018)</div><div class="text-right col-md-6">Page <b>3 </b></div></div>
													</div>
													<div class="row">
													     <div class="col-md-6">
														    <p><b>Criminal penalty for falsifying information.</b> Willfully falsifying certifications or affirmations may subject you to criminal penalties including fines and/or imprisonment.</p>
															<p><b>Misuse of TINs.</b> If the requester discloses or uses TINs in violation of federal law, the requester may be subject to civil and criminal penalties.</p>
															<h4>Specific Instructions</h4>
															<p><b>Line 1</b></p>
															<p>You must enter one of the following on this line; do not leave this line blank. The name should match the name on your tax return.</p>
															<p>If this Form W-9 is for a joint account (other than an account maintained by a foreign financial institution (FFI)), list first, and then circle, the name of the person or entity whose number you entered in Part I of Form W-9. If you are providing Form W-9 to an FFI to document a joint account, each holder of the account that is a U.S. person must provide a Form W-9.</p>
															<p><b>a.	Individual.</b> Generally, enter the name shown on your tax return. If you have changed your last name without informing the Social Security Administration (SSA) of the name change, enter your first name, the last name as shown on your social security card, and your new last name.</p>
															<p><b>Note: ITIN applicant:</b> Enter your individual name as it was entered on your Form W-7 application, line 1a. This should also be the same as the name you entered on the Form 1040/1040A/1040EZ you filed with your application.</p>
															<p><b>b.	Sole proprietor or single-member LLC.</b> Enter your individual name as shown on your 1040/1040A/1040EZ on line 1. You may enter your business, trade, or “doing business as” (DBA) name on line 2.</p>
															<p><b>c.	Partnership, LLC that is not a single-member LLC, C corporation, or S corporation.</b> Enter the entity's name as shown on the entity's tax return on line 1 and any business, trade, or DBA name on line 2.</p>
															<p><b>d.	Other entities.</b> Enter your name as shown on required U.S. federal tax documents on line 1. This name should match the name shown on the charter or other legal document creating the entity. You may enter any business, trade, or DBA name on line 2.</p>
															<p><b>e.	Disregarded entity.</b> For U.S. federal tax purposes, an entity that is disregarded as an entity separate from its owner is treated as a “disregarded entity.” See Regulations section 301.7701-2(c)(2)(iii). Enter the owner's name on line 1. The name of the entity entered on line 1 should never be a disregarded entity. The name on line 1 should be the name shown on the income tax return on which the income should be reported. For example, if a foreign LLC that is treated as a disregarded entity for U.S. federal tax purposes has a single owner that is a U.S. person, the U.S. owner's name is required to be provided on line 1. If the direct owner of the entity is also a disregarded entity, enter the first owner that is not disregarded for federal tax purposes. Enter the disregarded entity's name on line 2, “Business name/disregarded entity name.” If the owner of the disregarded entity is a foreign person, the owner must complete an appropriate Form W-8 instead of a Form W-9. This is the case even if the foreign person has a U.S. TIN.</p>
															<p><b>Line 2</b></p>
															<p>If you have a business name, trade name, DBA name, or disregarded entity name, you may enter it on line 2.</p>
															<p><b>Line 3</b></p>
															<p>Check the appropriate box on line 3 for the U.S. federal tax classification of the person whose name is entered on line 1. Check only one box on line 3.</p>

														</div>
														<div class="col-md-6">
														    <table class="table table-bordered" style="width:100%">
															  <tr>
																<th>IF the entity/person on line 1 is a(n) . . .</th>
																<th>THEN check the box for . . . </th>
															  </tr>
															  <tr>
																<td>•	Corporation</td>
																<td>Corporation</td>
															  </tr>
															  <tr>
																<td>
																    •	Individual<br><br>
																	•	Sole proprietorship, or<br><br>
																	•	Single-member limited liability company (LLC) owned by an individual and disregarded for U.S. federal tax purposes.
																 </td>
																<td>Individual/sole proprietor or single-member LLC</td>
															  </tr>
															  <tr>
																<td>
																    •	LLC treated as a partnership for U.S. federal tax purposes,<br><br>
																	•	LLC that has filed Form 8832 or 2553 to be taxed as a corporation, or<br><br>
																	•	LLC that is disregarded as an entity separate from its owner but the owner is another LLC that is not disregarded for U.S. federal tax purposes.

																</td>
																<td>Limited liability company and enter the appropriate tax classification.(P= Partnership; C= C corporation; or S= S corporation)</td>
															  </tr>
															  <tr>
																<td>•   Partnership</td>
																<td>Partnership</td>
															  </tr>
															  <tr>
																<td>•	Trust/estate</td>
																<td>Trust/estate</td>
															  </tr>
															</table>
															<h4>Line 4, Exemptions</h4>
															<p>if you are exempt from backup withholding and/or FATCA reporting, enter in the appropriate space on line 4 any code(s) that may apply to you.</p>
                                                            <p><b>Exempt payee code</b></p>
															<p>•	Generally, individuals (including sole proprietors) are not exempt from backup withholding.</p>
															<p>•	Except as provided below, corporations are exempt from backup withholding for certain payments, including interest and dividends.</p>
															<p>•	Corporations are not exempt from backup withholding for payments made in settlement of payment card or third party network transactions.</p>
															<p>•	Corporations are not exempt from backup withholding with respect to attorneys’ fees or gross proceeds paid to attorneys, and corporations that provide medical or health care services are not exempt with respect to payments reportable on Form 1099-MISC.</p>
															<p>The following codes identify payees that are exempt from backup withholding. Enter the appropriate code in the space in line 4.</p>
															<p>1—An organization exempt from tax under section 501(a), any IRA, or a custodial account under section 403(b)(7) if the account satisfies the requirements of section 401(f)(2)</p>
															<p>2—The United States or any of its agencies or instrumentalities</p>
															<p>3—A state, the District of Columbia, a U.S. commonwealth or possession, or any of their political subdivisions or instrumentalities</p>
															<p>4—A foreign government or any of its political subdivisions, agencies, or instrumentalities</p>
															<p>5—A corporation</p>
															<p>6—A dealer in securities or commodities required to register in the United States, the District of Columbia, or a U.S. commonwealth or possession</p>
															<p>7—A futures commission merchant registered with the Commodity Futures Trading Commission</p>
															<p>8—A real estate investment trust</p>
															<p>9—An entity registered at all times during the tax year under the Investment Company Act of 1940</p>
															<p>10—A common trust fund operated by a bank under section 584(a) 11—A financial institution</p>
															<p>12—A middleman known in the investment community as a nominee or custodian</p>
															<p>13—A trust exempt from tax under section 664 or described in section 4947</p>
                                                        </div>
													</div>
												</div>
										        <div class="page-container">
													<div class="row">
														<div class="page-number row"><div class="text-left col-md-6">Form W-9 (Rev. 10-2018)</div><div class="text-right col-md-6">Page <b>4 </b></div></div>
														
													</div>
													<div class="row">
													     <div class="col-md-6">
														    <p>The following chart shows types of payments that may be exempt from backup withholding. The chart applies to the exempt payees listed above, 1 through 13. </p>
															<table class="table table-bordered" style="width:100%">
															  <tr>
																<th>IF the payment is for . . .</th>
																<th> THEN the payment is exempt for . . . </th>
															  </tr>
															  <tr>
																<td>Interest and dividend payments</td>
																<td>All exempt payees except for 7</td>
															  </tr>
															  <tr>
															      <td>Broker transactions</td>
																  <td>Exempt payees 1 through 4 and 6 through 11 and all C corporations. S corporations must not enter an exempt payee code because they are exempt only for sales of noncovered securities acquired prior to 2012.</td>
															  </tr>
															  <tr>
															      <td>Barter exchange transactions and patronage dividends </td>
																  <td>Exempt payees 1 through 4</td>
															  </tr>
															  <tr>
															      <td>Payments over $600 required to be reported and direct sales over $5,000<sup>1</sup></td>
																  <td>Generally, exempt payees 1 through 5<sup>2</sup></td>
															  </tr>
															  <tr>
															      <td>Payments made in settlement of payment card or third party network transactions </td>
																  <td>Exempt payees 1 through 4</td>
															  </tr>
															</table>
															<p><sup>1</sup>See Form 1099-MISC, Miscellaneous Income, and its instructions.</p>
															<p><sup>2</sup>However, the following payments made to a corporation and reportable on Form 1099-MISC are not exempt from backup withholding: medical and health care payments, attorneys’ fees, gross proceeds paid to an attorney reportable under section 6045(f), and payments for services paid by a federal executive agency.</p>
															<p>Exemption from FATCA reporting code. The following codes identify payees that are exempt from reporting under FATCA. These codes apply to persons submitting this form for accounts maintained outside of the United States by certain foreign financial institutions. Therefore, if you are only submitting this form for an account you hold in the United States, you may leave this field blank. Consult with the person requesting this form if you are uncertain if the financial institution is subject to these requirements. A requester may indicate that a code is not required by providing you with a Form W-9 with “Not Applicable” (or any similar indication) written or printed on the line for a FATCA exemption code.</p>
															<p>A—An organization exempt from tax under section 501(a) or any individual retirement plan as defined in section 7701(a)(37)</p>
															<p>B—The United States or any of its agencies or instrumentalities</p>
															<p>C—A state, the District of Columbia, a U.S. commonwealth or possession, or any of their political subdivisions or instrumentalities</p>
															<p>D—A corporation the stock of which is regularly traded on one or more established securities markets, as described in Regulations section 1.1472-1(c)(1)(i)</p>
															<p>E—A corporation that is a member of the same expanded affiliated group as a corporation described in Regulations section 1.1472-1(c)(1)(i)</p>
															<p>F—A dealer in securities, commodities, or derivative financial instruments (including notional principal contracts, futures, forwards, and options) that is registered as such under the laws of the United States or any state</p>
															<p>G—A real estate investment trust</p>
															<p>H—A regulated investment company as defined in section 851 or an entity registered at all times during the tax year under the Investment Company Act of 1940</p>
															<p>I—A common trust fund as defined in section 584(a)</p>
															<p>J—A bank as defined in section 581</p>
															<p>K—A broker</p>
															<p>L—A trust exempt from tax under section 664 or described in section 4947(a)(1)</p>
														 </div>
														<div class="col-md-6">
                                                            <p>M—A tax exempt trust under a section 403(b) plan or section 457(g) plan</p>
															<p><b>Note:</b> You may wish to consult with the financial institution requesting this form to determine whether the FATCA code and/or exempt payee code should be completed.</p>
															<h4>Line 5</h4>
															<p>Enter your address (number, street, and apartment or suite number). This is where the requester of this Form W-9 will mail your information returns. If this address differs from the one the requester already has on file, write NEW at the top. If a new address is provided, there is still a chance the old address will be used until the payor changes your address in their records.</p>
															<h4>Line 6</h4>
															<p>Enter your city, state, and ZIP code.</p>
															<h4>Part I. Taxpayer Identification Number (TIN)</h4>
															<p><b>Enter your TIN in the appropriate box.</b> If you are a resident alien and you do not have and are not eligible to get an SSN, your TIN is your IRS individual taxpayer identification number (ITIN). Enter it in the social security number box. If you do not have an ITIN, see How to get a TIN below.</p>
															<p>If you are a sole proprietor and you have an EIN, you may enter either your SSN or EIN.</p>
                                                            <p>If you are a single-member LLC that is disregarded as an entity separate from its owner, enter the owner’s SSN (or EIN, if the owner has one). Do not enter the disregarded entity’s EIN. If the LLC is classified as a corporation or partnership, enter the entity’s EIN.</p>
                                                            <p><b>Note:</b> See What Name and Number To Give the Requester, later, for further clarification of name and TIN combinations.</p>
                                                            <p>How to get a TIN. If you do not have a TIN, apply for one immediately. To apply for an SSN, get Form SS-5, Application for a Social Security Card, from your local SSA office or get this form online at www.SSA.gov. You may also get this form by calling 1-800-772-1213. Use Form W-7, Application for IRS Individual Taxpayer Identification Number, to apply for an ITIN, or Form SS-4, Application for Employer Identification Number, to apply for an EIN. You can apply for an EIN online by accessing the IRS website at www.irs.gov/Businesses and clicking on Employer Identification Number (EIN) under Starting a Business. Go to www.irs.gov/Forms to view, download, or print Form W-7 and/or Form SS-4. Or, you can go to www.irs.gov/OrderForms to place an order and have Form W-7 and/or SS-4 mailed to you within 10 business days.</p>
                                                            <p>If you are asked to complete Form W-9 but do not have a TIN, apply for a TIN and write “Applied For” in the space for the TIN, sign and date the form, and give it to the requester. For interest and dividend payments, and certain payments made with respect to readily tradable instruments, generally you will have 60 days to get a TIN and give it to the requester before you are subject to backup withholding on payments. The 60-day rule does not apply to other types of payments. You will be subject to backup withholding on all such payments until you provide your TIN to the requester.</p>
															<p><b>Note:</b> Entering “Applied For” means that you have already applied for a TIN or that you intend to apply for one soon.</p>
															<p><b>Caution:</b> A disregarded U.S. entity that has a foreign owner must use the appropriate Form W-8.</p>
                                                            <h4>Part II. Certification</h4>
															<p>To establish to the withholding agent that you are a U.S. person, or resident alien, sign Form W-9. You may be requested to sign by the withholding agent even if item 1, 4, or 5 below indicates otherwise.</p>
															<p>For a joint account, only the person whose TIN is shown in Part I should sign (when required). In the case of a disregarded entity, the person identified on line 1 must sign. Exempt payees, see Exempt payee code, earlier.</p>
															<p><b>Signature requirements.</b> Complete the certification as indicated in items 1 through 5 below.</p>
														</div>
													</div>
												</div>
										        <div class="page-container">
													<div class="row">
														<div class="page-number row"><div class="text-left col-md-6">Form W-9 (Rev. 10-2018)</div><div class="text-right col-md-6">Page <b>5 </b></div></div>
														
													</div>
													<div class="row">
													     <div class="col-md-6">
														    <p><b>1. Interest, dividend, and barter exchange accounts opened before 1984 and broker accounts considered active during 1983</b>. You must give your correct TIN, but you do not have to sign the certification. </p>
														    <p><b>2. Interest, dividend, broker, and barter exchange accounts opened after 1983 and broker accounts considered inactive during 1983.</b> You must sign the certification or backup withholding will apply. If you are supject to backup withholding and you are merely providing your correct TIN to the requester, you must cross out item 2 in the certification before signing the form.</p>  
															<p><b>3. Real estate transactions.</b>You must sign the certification. You may cross out item 2 of the certification.</p>
															<p><b>4. Other payments.</b> You must give your correct TIN, but you do not have to sign the certification unless you have been notified that you have previously given an incorrect TIN. “Other payments” include payments made in the course of the requester’s trade or business for rents, royalties, goods (other than bills for merchandise), medical and health care services (including payments to corporations), payments to a nonemployee for services, payments made in settlement of payment card and third party network transactions, payments to certain fishing boat crew members and fishermen, and gross proceeds paid to attorneys (including payments to corporations).  </p>
															<p><b>5. Mortgage interest paid by you, acquisition or abandonment of secured property, cancellation of debt, qualified tuition program payments (under section 529), ABLE accounts (under section 529A), IRA, Coverdell ESA, Archer MSA or HSA contributions or distributions, and pension distributions.</b> You must give your correct TIN, but you do not have to sign the certification.</p>
															<h4>What Name and Number To Give the Requester</h4> 
															<table class="table table-bordered" style="width:100%">
															  <tr>
																<th>For this type of account:</th>
																<th> Give name and SSN of: </th>
															  </tr>
															  <tr>
																<td>
																1. Individual <br><br> 
																2. Two or more individuals (joint  account) other than an account maintained by an FFI <br><br>
																3. Two or more U.S. persons     (joint account maintained by an FFI) <br><br>
																4. Custodial account of a minor (Uniform Gift to Minors Act)<br><br>
																5. a. The usual revocable savings trust (grantor is also trustee) <br><br>b. So-called trust account that is not a legal or valid trust under state law<br><br>
																6. Sole proprietorship or disregarded entity owned by an individual<br><br>
																7. Grantor trust filing under Optional Form 1099 Filing Method 1 (see Regulations section 1.671-4(b)(2)(i) (A))
																</td>
																<td>
																    The individual <br><br>
																	The actual owner of the account or, if combined funds, the first individual on the account<sup>1</sup><br> <br>  
                                                                    Each holder of the account <br><br>
																	The minor<sup>2 </sup><br><br>
																	The grantor-trustee<sup>1</sup><br><br>
																	The actual owner<sup>1</sup><br><br>
																	The owner<sup>3</sup><br><br>
																	The grantor<sup>*</sup>
																</td>
															  </tr>
															</table>
                                                            <table class="table table-bordered" style="width:100%">
															  <tr>
																<th>For this type of account:</th>
																<th> Give name and EIN of: </th>
															  </tr>
															  <tr>
																<td>
																8. Disregarded entity not owned by an individual <br><br> 
																9. A valid trust, estate, or pension trust  <br><br>
																10. Corporation or LLC electing corporate status on Form 8832 or Form 2553 <br><br>
																11. Association, club, religious, charitable, educational, or other taxexempt organization<br><br>
																12. Partnership or multi-member LLC <br><br>
																13. A broker or registered nominee <br><br>
																</td>
																<td>
																    The owner <br><br>
																	Legal entity<sup>4</sup><br> <br>  
                                                                    The corporation <br><br>
																	The organization<br><br>
																	The partnership<br><br>
																	The broker or nominee<br><br>
																</td>
															  </tr>
															</table>	
														 </div>
														  <div class="col-md-6">
														    <table class="table table-bordered" style="width:100%">
															  <tr>
																<th>For this type of account:</th>
																<th> Give name and EIN of: </th>
															  </tr>
															  <tr>
																<td>
																14. Account with the Department of Agriculture in the name of a public entity (such as a state or local government, school district, or prison) that receives agricultural program payments <br><br> 
																15. Grantor trust filing under the Form 1041 Filing Method or the Optional Form 1099 Filing Method 2 (see Regulations section 1.671-4(b)(2)(i)(B))<br><br>
																</td>
																<td>
																    The public entity <br><br>
																	The trust<br><br>  
																</td>
															  </tr>
															</table>
                                                            <p><sup>1</sup>List first and circle the name of the person whose number you furnish. If only one person on a joint account has an SSN, that person’s number must be furnished.</p>
															<p><sup>2</sup>Circle the minor’s name and furnish the minor’s SSN.</p>
															<p><sup>3</sup>	You must show your individual name and you may also enter your business or DBA name on the “Business name/disregarded entity” name line. You may use either your SSN or EIN (if you have one), but the IRS encourages you to use your SSN.</p>
															<p><sup>4</sup>	List first and circle the name of the trust, estate, or pension trust. (Do not furnish the TIN of the personal representative or trustee unless the legal entity itself is not designated in the account title.) Also see Special rules for partnerships, earlier.</p>
															<p><b><sup>*</sup>Note:</b> The grantor also must provide a Form W-9 to trustee of trust.</p>
															<p><b>Note:</b> If no name is circled when more than one name is listed, the number will be considered to be that of the first name listed.</p>
															<h4>Secure Your Tax Records From Identity Theft</h4>
															<p>Identity theft occurs when someone uses your personal information such as your name, SSN, or other identifying information, without your permission, to commit fraud or other crimes. An identity thief may use your SSN to get a job or may file a tax return using your SSN to receive a refund.</p>
															<p>To reduce your risk:</p>
															<p>•    Protect your SSN,</p>
															<p>•	Ensure your employer is protecting your SSN, and</p>
															<p>•	Be careful when choosing a tax preparer.</p>
															<p>If your tax records are affected by identity theft and you receive a notice from the IRS, respond right away to the name and phone number printed on the IRS notice or letter.</p>
															<p>If your tax records are not currently affected by identity theft but you think you are at risk due to a lost or stolen purse or wallet, questionable credit card activity or credit report, contact the IRS Identity Theft Hotline at 1-800-908-4490 or submit Form 14039.</p>
															<p>For more information, see Pub. 5027, Identity Theft Information for Taxpayers.</p>
															<p>Victims of identity theft who are experiencing economic harm or a systemic problem, or are seeking help in resolving tax problems that have not been resolved through normal channels, may be eligible for Taxpayer Advocate Service (TAS) assistance. You can reach TAS by calling the TAS toll-free case intake line at 1-877-777-4778 or TTY/TDD 1-800-829-4059.</p>
															<p>Protect yourself from suspicious emails or phishing schemes. Phishing is the creation and use of email and websites designed to mimic legitimate business emails and websites. The most common act is sending an email to a user falsely claiming to be an established legitimate enterprise in an attempt to scam the user into surrendering private information that will be used for identity theft.</p>
														  </div>
													</div>
												</div>
											    <div class="page-container">
													<div class="row">
														<div class="page-number row"><div class="text-left col-md-6">Form W-9 (Rev. 10-2018)</div><div class="text-right col-md-6">Page <b>6 </b></div></div>
														
													</div>
													<div class="row">
													     <div class="col-md-6">
														    
														     <p>The IRS does not initiate contacts with taxpayers via emails. Also, the IRS does not request personal detailed information through email or ask taxpayers for the PIN numbers, passwords, or similar secret access information for their credit card, bank, or other financial accounts. </p>
															 <p>If you receive an unsolicited email claiming to be from the IRS, forward this message to phishing@irs.gov. You may also report misuse of the IRS name, logo, or other IRS property to the Treasury Inspector General for Tax Administration (TIGTA) at 1-800-366-4484. You can forward suspicious emails to the Federal Trade Commission at spam@uce.gov or report them at www.ftc.gov/complaint. You can contact the FTC at www.ftc.gov/idtheft or 877-IDTHEFT (877-438-4338). 
															 If you have been the victim of identity theft, see www.IdentityTheft.gov and Pub. 5027. </p>
															 <p>Visit www.irs.gov/IdentityTheft to learn more about identity theft and how to reduce your risk.</p>

														 </div>
														  <div class="col-md-6">
														     <h3>Privacy Act Notice</h3>
															 <p>Section 6109 of the Internal Revenue Code requires you to provide your correct TIN to persons (including federal agencies) who are required to file information returns with the IRS to report interest, dividends, or certain other income paid to you; mortgage interest you paid; the acquisition or abandonment of secured property; the cancellation of debt; or contributions you made to an IRA, Archer MSA, or HSA. The person collecting this form uses the information on the form to file information returns with the IRS, reporting the above information. Routine uses of this information include giving it to the Department of Justice for civil and criminal litigation and to cities, states, the District of Columbia, and U.S. commonwealths and possessions for use in administering their laws. The information also may be disclosed to other countries under a treaty, to federal and state agencies to enforce civil and criminal laws, or to federal law enforcement and intelligence agencies to combat terrorism. You must provide your TIN whether or not you are required to file a tax return. Under section 3406, payers must generally withhold a percentage of taxable interest, dividend, and certain other payments to a payee who does not give a TIN to the payer. Certain penalties may also apply for providing false or fraudulent information.</p>
														  </div>
													
													</div>
												</div>
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

  /// $('#form_class').supmit(function() { 
   $('form').supmit(function() {

   	var is_sign_ok=$('#is_sign_done').val();
   	// alert('PostNeow='+is_sign_ok);
   	 // $('input:radio', this).is(':checked')
    if (is_sign_ok==1) {
        // everything's fine...
        alert('supmitted!');
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