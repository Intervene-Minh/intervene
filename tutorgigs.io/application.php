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
 if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
//validate failed user

if($_SESSION['ses_curr_state_url']=='quiz_result.php'){
  header("location:".$_SESSION['ses_curr_state_url']); exit; // go, state page wherver
  }


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







//////Save|add Profile///////

if(isset($_POST['is_computer']) ) {//save


//	print_r($_POST); die;

 $regis_state_arr= array('step_1' =>1, // Application OK
  'step_2' => 0,
  'step_3' => 0,
  'step_4' => 0,
  'email_confirm' => 1,
   'can_access_website' => 0,
  'status_to_login' =>1);// 
 $regis_state_str=serialize($regis_state_arr);


	///////////////////

	$is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']);
	$num=mysql_num_rows($is_record);


	//print_r($_FILES); //die;
 //print_r($_POST); die;  photo_id
	// if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	 if(!empty($_FILES["photo_id"]["name"])){
	 	// if image exist unlink old image
	 	if($num==1){
	 		 $profile=mysql_fetch_assoc($is_record);
         // $image_goes=$profile['photo_id'];  // OLD Name
	 		 unlink("../tutor-images/".$profile['photo_id']);

	 	}

	 	$name=$_FILES["photo_id"]["name"];
	$image_goes=rand(1001,1111).$name;

	$target_file="../tutor-images/".$image_goes;
	if (move_uploaded_file($_FILES["photo_id"]["tmp_name"], $target_file)) {
		$msg= 'uploaded success';
	}else{	
		$msg= 'not uploaded';
		  $image_goes=null;

         }
       // 'file selected';
	 }elseif($num==1){ // previous added if any 
      $profile=mysql_fetch_assoc($is_record);
       $image_goes=$profile['photo_id'];  // OLD Name

	 }else{  
        $image_goes=null; // echo 'Not selected';
   
	 }
   // num==1,or image not upload. 

	



   // die;
	///
  $grade_str=implode(',', $_POST['grade_levels']);
   
    $subject_other=$_POST['subjects_other']; // if any 
    if(isset($_POST['subjects_other'])){
     $subject_str='other'; // no selected subject
    }else{
    	$subject_str=implode(',', $_POST['subjects']);

    }

	$signup_state_arr= array('step_1' => 1,
  'is_computer' =>$_POST['is_computer'],
  'started_date' => $_POST['started_date'],
  //'f2_q_1' => $_POST['f2_q_1'],
  //'f2_q_2' => $_POST['f2_q_2'],
  'f3_q_1' => $_POST['f3_q_1'], //Form 3 f3_q_3
  'f3_q_2' => $_POST['f3_q_2'],
  'f3_q_3' => $_POST['f3_q_3'], //Radio opion333
  'f3_q_4' => $_POST['f3_q_4'],
  'f3_q_5' => $_POST['f3_q_5'],
  'f3_q_6' => $_POST['f3_q_6'],
  'f3_q_7' => $_POST['f3_q_7'],
  'f3_q_8' => $_POST['f3_q_8'],
  'f3_q_9' => $_POST['f3_q_9'], //Form 3




  'hear' =>$_POST['hear']);



	$state_str=serialize($signup_state_arr);

	/**
INSERT INTO `tutor_profiles` (`tutor_id`, `profile_1`, `info`, `created`) VALUES (NULL, 'dffd', '3', '2019-01-23 00:00:00');
	**/ // tutorid
	

	$tutorid=$_SESSION['ses_teacher_id'];
	if($num==0){
		$msg='Profile added';
		// Subject and Grade  subjects subjects_other grade_levels
		// grade_levels  subjects_other
		//$sql=" INSERT INTO `tutor_profiles` (`tutorid`, `profile_1`, `info`) VALUES ('$tutorid', '$state_str', '3') ";

      $sql=" INSERT INTO `tutor_profiles` SET tutorid='$tutorid',profile_1= '$state_str',info='Later',grade_levels='$grade_str',subjects='$subject_str',subjects_other='$text_info_other',photo_id='$image_goes' ";


	
	}else{
		// get image
		$msg='Profile saved'; 
		$text_info_other=$_POST['subjects_other_text']; // if other subject checked. 
		// UPDATE `tutor_profiles` SET `profile_1` = 'DAT' WHERE `tutor_profiles`.`id` = 1;
		// photo_id
		$sql=" UPDATE `tutor_profiles` SET profile_1= '$state_str',info='Later',grade_levels='$grade_str',subjects='$subject_str',subjects_other='$text_info_other',photo_id='$image_goes' WHERE tutorid=".$_SESSION['ses_teacher_id'];
	}

	// echo $sql; 
	 $url_next='application.php';
      $ac=mysql_query($sql);
      // Update state of application 
       $next_state_url='quiz.php';
      //$Update=mysql_query(" UPDATE `gig_teachers` SET signup_state= '$regis_state_str' WHERE id=".$_SESSION['ses_teacher_id']);
       $Update=mysql_query(" UPDATE `gig_teachers` SET signup_state= '$regis_state_str',all_state_url='$next_state_url' WHERE id=".$_SESSION['ses_teacher_id']);
       // Step2 in sesson 
       $_SESSION['ses_curr_state_url']=$step_2_url;
    $_SESSION['ses_prev_state_url']=$step_1_url;


    echo "<script>alert('".$msg."');location.href='".$step_2_url."';</script>";
	//echo '<pre>';print_r($_POST); 
	//die;
/////insert 1 then update

}

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
                        
			<!-- <div id="content" class="col-md-8 col-md-offset-2"> -->
			<div id="content" class="col-md-12">
				<!--<div class="content_wrap">
					<div class="ct_heading clear">
                                              <h3>Home</h3>
					<div class="ct_display clear">
						<div class="item-listing clear">
							<h3 class="notfound align-center">
								<a href="#">Welcome
                                                                   <//?= $_SESSION['login_user']?>.</a>
							</h3>
                                                   <//?php 
                                                   //var_dump($_SESSION);
                                                   ?> 
                                                    
                                                    
						</div>
					</div>
				</div>-->
				<div class="page-content" style="background-image: url('images/wizard-v3.jpg')">
					<div class="wizard-v3-content">
						<div class="wizard-form">
							<div class="wizard-header">
								<h3 class="heading">Complete your Registration</h3>
								<p>Fill all form field to go next step</p>
							</div>
							<div class="form-register" >
								<div id="form-total">

								<div class="steps clearfix"><ul role="tablist"><li role="tab" aria-disabled="false" class="first current" aria-selected="true"><a id="form-total-t-0" href="#form-total-h-0" aria-controls="form-total-p-0"><span class="current-info audible"> </span><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-account"></i></span>
										<span class="step-text">Application</span>
									</div></a></li><li role="tab" aria-disabled="false"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><div class="title">
										<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
										<span class="step-text">Quiz</span>
									</div></a></li><li role="tab" aria-disabled="false"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><div class="title">
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
									<!-- <h2>
										<span class="step-icon"><i class="zmdi zmdi-account"></i></span>
										<span class="step-text">Application</span>
									</h2> -->

 
								</div>
								</div>

								


									 <!--  Form Steps -->
                                    <div class="content">


									  <form id="regForm" action="" method="post" enctype="multipart/form-data">
										  <div class="tab">
										      <div class="inner">
											     <h3 class="text-center text-primary">Account Information:</h3>

													<div class="form-row">
													 <h3>Email Address  </h3> 

														<div class="form-holder form-holder-2">
															<label class="form-row-inner">
															   
													          <input disabled="" type="text"  value="<?=$tutor_det['email']?>" class="form-control text-primary" >
																  
															</label>
														</div>
													</div>
													<div class="form-row">
													<h3>First name  </h3> 
														<div class="form-holder form-holder-2">
															<label class="form-row-inner">
															   
																<input  disabled=""  value="<?=$tutor_det['f_name']?>" class="form-control" >
															</label>
														</div>
													</div>

													<div class="form-row">
													<h3>Last name  </h3> 
														<div class="form-holder form-holder-2">
															<label class="form-row-inner">
															   
																<input  disabled=""  value="<?=$tutor_det['lname']?>" class="form-control" >
															</label>
														</div>
													</div>
													<h3>What is your phone number?  </h3> 

													<div class="form-row">
													
														<div class="form-holder form-holder-2">
															<label class="form-row-inner">
															   
																<input  disabled  value="<?=$tutor_det['phone']?>" class="form-control" >
															</label>
														</div>
													</div>

											        <!-- Step1 --> 



														<h3><?php //=$edit['is_computer']?>Do you have a computer or tablet and reliable internet access? </h3>     
														<?php 
														$yes_checked='';
														if($edit['is_computer']=='no'){
															$no_checked='checked';

														}

														?>
														<div class="form-row">
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
																 <?=($edit['is_computer']=='no'||!isset($edit['is_computer']))?'checked':null?> name="is_computer"   value="no" id="radio-r" >
																	<span class="label">
																	No</span>
																</label>
															</div>
														</div>
														<h3>How did you hear about us?</h3>

														<div class="form-row">
															<div class="form-holder form-holder-2">
																	<input type="text" name="hear"
																	 value="<?=$edit['hear']?>" 

																	 class="form-control" >
								
															</div>
														</div>
														<h3>When would you like to get started Tutoring?</h3>
														<div class="form-row">
															<div class="form-holder form-holder-2">
																<input type="text" value="<?=$edit['started_date']?>"  oninput="this.className = ''"
																 name="started_date"  placeholder="mm/dd/yyyy" class="form-control" >
								
															</div>
														</div>
														
										        </div>
										  </div>



										  


										  <div class="tab">
										    <div class="inner">
										        <div class="form-row">
												 <h3>Why do you want to Tutor?</h3> 
													<div class="form-holder form-holder-2">
													    <label class="form-row-inner">
														  <input type="text" name="f3_q_1" oninput="this.className = ''"
														  value="<?=$edit['f3_q_1']?>"  class="form-control">
														</label>
													</div>
												</div>
												<div class="form-row">
												 <h3>What makes a good Tutor?</h3> 
													<div class="form-holder form-holder-2">
													    <label class="form-row-inner">
														  <input type="text" name="f3_q_2" value="<?=$edit['f3_q_2']?>" 
														  class="form-control">
														</label>
													</div>
												</div>
												<h3> Have you ever tutored before?  </h3>     		
												<div class="form-row">
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_3']=='yes'||!isset($edit['f3_q_3']))?'checked':null?>
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
												<h3> Have you ever Tutored online?  </h3>     		
												<div class="form-row">
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio" 
														 <?=($edit['f3_q_4']=='yes'||!isset($edit['f3_q_4']))?'checked':null?>
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
												 <h3>If you have tutored, where and what did you tutor?</h3> 
													<div class="form-holder form-holder-2">
													    <label class="form-row-inner">
														  <input type="text" name="f3_q_5" oninput="this.className = ''"
														   value="<?=$edit['f3_q_5']?>" class="form-control">
														</label>
													</div>
												</div>
												<h3> How many years have you Tutored?  </h3> 
												<div class="form-row">
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_1'||!isset($edit['f3_q_6']))?'checked':null?>
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
												<h3> What grade levels do you want to Tutor?  </h3> 
												 <?php 
												  // Edit array
												  $save_grades_arr=explode(',',$data2['grade_levels']);

												  $grade_arr=array('grade_1'=>'Elementary School (3-5)',
													'grade_2'=>'Middle School (6-8)',
													'grade_3'=>'High School (9-12)'
													
													          );

												  ?>
												<div class="form-row">
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
												<h3> What subjects can you Tutor? </h3> 
												<div class="form-row">
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
												 <h3>What languages do you speak aside from English?</h3> 
													<div class="form-holder form-holder-2">
													    <label class="form-row-inner">
														  <input type="text" name="f3_q_7"  
														  value="<?=$edit['f3_q_7']?>" class="form-control">
														</label>
													</div>
												</div>
												<h3>Are you a certified Teacher? 
												<?php 
												
												// if(!isset($edit['f3_q_8'])){
												// 	$edit['f3_q_8']='yes';

												// }
												//print_r($edit['f3_q_8']); echo '==';


												?>

												</h3>
												<div class="form-row">
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_8']=='yes'||!isset($edit['f3_q_8']))?'checked':null?>
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
												<h3>How familiar are you with TEKS & STAAR?</h3>
												<?php 
												$familiar=array('opn_1'=>'Not familiar',
													'opn_2'=>'I have heard of it, but not very familiar',
													'opn_3'=>'Somewhat familiar',
													'opn_4'=>'Very familar',
													          'opn_5'=>'I am a specialist');

												?>
												<div class="form-row">
													<div class="form-holder form-holder-2">
														<label class="form-row-inner">
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_1'||!isset($edit['f3_q_9']))?'checked':null?>
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
												<p>Finally, you have made it to the last portion of the application! You're almost done. In this section we just need you to verify your identity.  </p>
												<h3>Please take a clear photo of yourself and a photo ID, so that we may confirm your identity. We will need to clearly see your name and photo to be accepted. (See example below) </h3>
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
												$photo_id='https://lh3.googleusercontent.com/KqYRICZo55OamI7-_aJNtFTJffEY5cRdAh003Yc_uqG7B_jmTmYiN0OR_oFCOCBNBzYYxULUmQ=w464';

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
										   <div style="text-align:center;margin-top:40px;">
												<span class="step"></span>
												<span class="step"></span>
												<span class="step"></span>
												<span class="step"></span>
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