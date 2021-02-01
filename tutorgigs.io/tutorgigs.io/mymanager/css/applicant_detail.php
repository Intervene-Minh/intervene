<?php
/*****
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}

4 Steps-Form application
1. first signup  -inital registration ,name email
2. Step-1 application
 3 - step-2 QUIZ
4- step 3- Interview

****/

///
 include("header.php");
 $step_2_url='quiz.php'; //QUIZ Button
 //Valid User///////

//  if(!isset($_SESSION['ses_teacher_id'])){
//     header('Location:logout.php');exit;
// }



 $tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));
  $get_state_arr=unserialize($tutor_det['signup_state']);
   //  print_r($get_state_arr);

////Auto move to next step2/////////
 // if($get_state_arr['step_1']==1){
 // 	$msg="Quiz step";
 // 	 echo "<script>alert('".$msg."');location.href='".$step_2_url."';</script>";
 // }



  
//$_SESSION['ses_access_website']='no'; // 1==if all 4 step completed by user




  //////Profile data////

 $data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));

$edit=unserialize($data2['profile_1']);

 //print_r($data2['subjects']); echo '<br/>';






/////////////
//echo 'Application';

 ?>

<link type="text/css" href="css/home-page.css" rel="stylesheet" />
<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

#regForm {
  background-color: #ffffff;
  margin: 100px auto;
  font-family: Raleway;
  padding: 40px;
  width: 70%;
  min-width: 300px;
}

h1 {
  text-align: center;  
}

input {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #4CAF50;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #4CAF50;
}
</style>



<style>	
#regForm {
  background-color: #ffffff;
  margin: 0px;
  font-family: Raleway;
  padding: 0px;
  width: 100%;
  display: inline-block;
}

.tab {
  display: none;
}

button {
  background-color: #4CAF50;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #4CAF50;
}
</style>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<?php  //include("sidebar.php"); ?>    
                      	<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->

			   



			<!-- <div id="content" class="col-md-8 col-md-offset-2"> -->
			<div id="content" class="col-md-8">

				
				<div class="page-content" style="background-image: url('images/wizard-v3.jpg')">
					<div class="wizard-v3-content">
						<div class="wizard-form">
							<div class="wizard-header">
								<!-- <label class="heading">Details</label> -->
								
							</div>

							<!-- <div class="form-register" >
								<div id="form-total">

 
								</div>
								</div> -->

								


									 <!--  Form Steps -->
                                    <div class="content">


									  <form id="regForm" action="" method="post" enctype="multipart/form-data">
										  <div class="tab">
										      <div class="inner">
											     <h3 class="text-center text-primary">Account Information:</h3>

													<div class="form-row">
													    <label>Email Address  </label> 
														<div class="form-holder form-holder-2">
															<label class="form-row-inner">
															   
													          <input disabled="" type="text"  value="<?=$tutor_det['email']?>" class="form-control text-primary" >
																  
															</label>
														</div>
													</div>


													<div class="form-row">
													    <label>Last name  </label> 
														<div class="form-holder form-holder-2">
															<label class="form-row-inner">
															   
																<input  disabled=""  value="<?=$tutor_det['lname']?>" class="form-control" >
															</label>
														</div>
													</div>

													

													<div class="form-row">
													    <label>What is your phone number?  </label>    
														<div class="form-holder form-holder-2">
															<label class="form-row-inner">
															   
																<input  disabled  value="<?=$tutor_det['phone']?>" class="form-control" >
															</label>
														</div>
													</div>

											        <!-- Step1 --> 



														
														<?php 
														$yes_checked='';
														if($edit['is_computer']=='no'){
															$no_checked='checked';

														}

														?>
														<div class="form-row">
														   <label><?php //=$edit['is_computer']?>Do you have a computer or tablet and reliable internet access? </label>     
															<div class="form-holder form-holder-2">
																<label class="form-row-inner">
																<input type="radio"
																 <?=($edit['is_computer']=='yes')?'checked':null?>
																 name="is_computer" id="radio-r" value="yes"  >
																	<span class="label">
																	Yes</span>
																</label>
															</div>
														</div>
														<div class="form-row">
														
															<div class="form-holder form-holder-2">
																<label class="form-row-inner">
																<input type="radio" 
																 <?=($edit['is_computer']=='no')?'checked':null?> name="is_computer"   value="no" id="radio-r" >
																	<span class="label">
																	No</span>
																</label>
															</div>
														</div>
														

														<div class="form-row">
														    <label>How did you hear about us?</label>
															<div class="form-holder form-holder-2">
																	<input type="text" name="hear"
																	 value="<?=$edit['hear']?>" 

																	 class="form-control" >
								
															</div>
														</div>
														
														<div class="form-row">
														<label>When would you like to get started Tutoring?</label>
															<div class="form-holder form-holder-2">
																<input type="text" value="<?=$edit['started_date']?>"  oninput="this.className = ''"
																 name="started_date"  placeholder="mm/dd/yyyy" class="form-control" >
								
															</div>
														</div>


														
										       


										        <div class="form-row">
												 <label>Why do you want to Tutor?</label> 
													<div class="form-holder form-holder-2">
													    <label class="form-row-inner">
														  <input type="text" name="f3_q_1" oninput="this.className = ''"
														  value="<?=$edit['f3_q_1']?>"  class="form-control">
														</label>
													</div>
												</div>
												<div class="form-row">
												 <label>What makes a good Tutor?</label> 
													<div class="form-holder form-holder-2">
													    <label class="form-row-inner">
														  <input type="text" name="f3_q_2" value="<?=$edit['f3_q_2']?>" 
														  class="form-control">
														</label>
													</div>
												</div>
												
												<div class="form-row">
												    <label> Have you ever tutored before?  </label>     		
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_3']=='yes')?'checked':null?>
														 name="f3_q_3" id="radio-r" value="yes"   >
															<span class="label">
															Yes</span>
														</label>
													</div>
												</div>
												<div class="form-row">
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio" 
														 <?=($edit['f3_q_3']=='no')?'checked':null?> name="f3_q_3"  value="no" id="radio-r" >
															<span class="label">
															No</span>
														</label>
													</div>
												</div>
												     		
												<div class="form-row">
												    <label> Have you ever Tutored online?  </label> 
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio" 
														 <?=($edit['f3_q_4']=='yes')?'checked':null?>
														 name="f3_q_4" id="radio-r" value="yes"  >
															<span class="label">
															Yes</span>
														</label>
													</div>
												</div>
												<div class="form-row">
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"  
														 <?=($edit['f3_q_4']=='no')?'checked':null?> name="f3_q_4" 
														  value="no" id="radio-r" >
															<span class="label">
															No</span>
														</label>
													</div>
												</div>
												<div class="form-row">
												 <label>If you have tutored, where and what did you tutor?</label> 
													<div class="form-holder form-holder-2">
													    <label class="form-row-inner">
														  <input type="text" name="f3_q_5" oninput="this.className = ''"
														   value="<?=$edit['f3_q_5']?>" class="form-control">
														</label>
													</div>
												</div>
												
												<div class="form-row">
												    <label> How many years have you Tutored ?  </label> 
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_1')?'checked':null?>
														 name="f3_q_6" id="radio-r" value="opn_1"  >
															<span class="label">
															Less than a year</span>
														</label>
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_2')?'checked':null?>
														 name="f3_q_6" id="radio-r" value="opn_2" >
															<span class="label">
															1-3 years</span>
														</label>
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_3')?'checked':null?>
														 name="f3_q_6" id="radio-r" value="opn_3"  >
															<span class="label">
															3-5 year</span>
														</label>
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_4')?'checked':null?>
														 name="f3_q_6" id="radio-r" value="opn_4"   >
															<span class="label">
															More than 5 years</span>
														</label>
													</div>
												</div>
												
												 <?php 
												  // Edit array
												  $save_grades_arr=explode(',',$data2['grade_levels']);

												  $grade_arr=array('grade_1'=>'Elementary School (3-5)',
													'grade_2'=>'Middle School (6-8)',
													'grade_3'=>'High School (9-12)'
													
													          );

												  ?>
												<div class="form-row">
												    <label> What grade levels do you want to Tutor?  </label> 
													<div class="form-holder form-holder-2">
														  <?php 
                                                     foreach ($grade_arr as $key => $value) {
                                                     	# code...
                                                     	if(is_array($save_grades_arr))
                                                    $checked=(in_array($key,$save_grades_arr))?"checked":null; // checked
                                                    else $checked=null;
                                                     ?>
														<label class="form-row-inner" >
														<input type="checkbox" <?=$checked?>	  name="grade_levels[]" 
														id="radio-r" value="<?=$key?>" required>
															<span class="label">
															<?=$value?></span>
														</label>

														<?php }?>

														


													</div>
												</div>
												
												<div class="form-row">
												<label> What subjects can you Tutor ? </label> 
												  <?php 
												  // Edit array
												  $save_subj_arr=explode(',',$data2['subjects']);

												  $subj_arr=array('math'=>'Math',
													'english'=>'English Comprehension & Reading',
													'esl'=>'ESL',
													'languages'=>'Languages'
													          );

												  ?>
													<div class="form-holder form-holder-2">

                                                     <?php 
                                                     foreach ($subj_arr as $key => $value) {
                                                     	# code...
                                                     	if(is_array($save_subj_arr))
                                                    $sel=(in_array($key,$save_subj_arr))?"checked":null; // checked
                                                    else $sel=null;
                                                     ?>

														<label class="form-row-inner">
														<input type="checkbox" <?=$sel?>  name="subjects[]" id="radio-r"
														 value="<?=$key?>" >
															<span class="label">
															<?=$value?></span>
														</label>
														<?php }?>


														


														<!-- <label class="form-row-inner">
														<input type="checkbox"  <?=($data2['subjects']=='other')?'checked':null?>    name="subjects_other" 
														 id="radio-r" value="other"  >
															<span class="label">
															Other:<label class="form-row-inner">
														  <input type="text" name="subjects_other_text"    style="width: 80%"
														  value="<?=(isset($data2['subjects_other']))?$data2['subjects_other']:null?>" class="form-control">
														</label>

															</span>
															
															
														</label> -->

														

													</div>
												</div>
												<div class="form-row">
												 <label>What languages do you speak aside from English?</label> 
													<div class="form-holder form-holder-2">
													    <label class="form-row-inner">
														  <input type="text" name="f3_q_7"  
														  value="<?=$edit['f3_q_7']?>" class="form-control">
														</label>
													</div>
												</div>
												
												<div class="form-row">
												    <label>Are you a certified Teacher?</label>
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_8']=='yes')?'checked':null?>
														 name="f3_q_8" id="radio-r" value="yes"    >
															<span class="label">
															Yes</span>
														</label>
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_8']=='no')?'checked':null?>
														 name="f3_q_8" id="radio-r" value="no"    >
															<span class="label">
															No</span>
														</label>
													</div>
												</div>
												
												<?php 
												$familiar=array('opn_1'=>'Not familiar',
													'opn_2'=>'I have heard of it, but not very familiar',
													'opn_3'=>'Somewhat familiar',
													'opn_4'=>'Very familar',
													          'opn_5'=>'I am a specialist');

												?>
												<div class="form-row">
												    <label>How familiar are you with TEKS & STAAR?</label> 
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_1')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_1" >
															<span class="label">
															Not familiar</span>
														</label>
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_2')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_2" >
															<span class="label">
															I have heard of it, but not very familiar</span>
														</label>
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_3')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_3" >
															<span class="label">
															Somewhat familiar</span>
														</label>
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_4')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_4" >
															<span class="label">
															Very familar</span>
														</label>
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_4')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_4" >
															<span class="label">
															I'm a specialist</span>
														</label>
													</div>
												</div>




										     </div>
										  </div>





										  <div class="tab">
												<p>Finally, you have made it to the last portion of the application. You're almost done. In this section we just need you to verify your identity.  </p>
												<label>Please take a clear photo of yourself and a photo ID, such that we can confirm your identity. We will need to clearly see your name and Photo to be accepted. (see example below) </label>
												<div class="form-row">
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														  <input type="file" name="photo_id"  class="form-control"  <?php if(empty($data2['photo_id'])){?> oninput="this.className = ''" <?php } ?>  >
														   
														</label>
													</div>
												</div>
												<p><?=(!empty($data2['photo_id']))?'Uploaded photo-ID Below':'Example photo with ID'?></p>
												<?php 
												// Image if exitst 
												$photo_id='https://llabel.googleusercontent.com/KqYRICZo55OamI7-_aJNtFTJffEY5cRdAh003Yc_uqG7B_jmTmYiN0OR_oFCOCBNBzYYxULUmQ=w464';

												if(!empty($data2['photo_id'])){
												$photo_id='http://tutorgigs.io/tutor-images/'.$data2['photo_id'];	
												}


												?>
												<img src="<?=$photo_id?>" class="img-responsive"  title="Applicant ID" alt="Photo ID">
												
										  </div>
										  <div style="overflow:auto;">
											<div style="float:right;">
											  <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
											  <button   type="button" name="save" id="nextBtn" onclick="nextPrev(1)">Next</button>
											</div>
										  </div>
										</form>


									
								
									

									<!-- Start tutor  -->






								
							<!-- </form>  -->
							<!-- End form -->
						</div>
					</div>
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>	
	<!-- /#main -->

<script>

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the crurrent tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit Now";
     //document.getElementById("nextBtn").type = "submit";
     // document.getElementById("nextBtn").id = "submit_btn_id";

  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
	//alert('T'+currentTab+'n=='+n);
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  //check validation
  // Hide the current tab:
   console.log("currentTab:"+currentTab);

  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
   console.log("End x "+x.length+'==currentTab'+currentTab);
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // Radio button should be at-least 1 checked
  // if ($('input[name="f3_q_8"]:checked').length == '0'){
  //    valid = false;
  //   alert('select a option');
  //  // return false;
  //   }


  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
   /////

  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>

</script>
<?php include("footer.php"); ?>