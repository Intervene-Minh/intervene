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
   //print_r($_SESSION);  die;
//  total test -passed by user. 
 function quiz_score($p2db,$getid,$test_id){
    //$test_id=$tdata['quiz_1_id']; //Default 

$attempted=("SELECT * FROM `tutor_result_logs` WHERE `tutor_id` =".$getid." AND `test_id` =".$test_id);
 $get_result=mysqli_query($p2db,$attempted);
  $quiz_1=array();
   $quiz_2=array(); 
  $total_attempted=mysqli_num_rows($get_result);
     $correct=0;
        while ($row = mysqli_fetch_assoc($get_result)) {
     //echo $row['attempt_id']; echo '<br/>';
          // attempt_id  answer_id
          if($row['answer_id']==$row['attempt_id']){
            $correct=$correct+1;
          }      
    }
 
  $get_scored=($correct*100)/$total_attempted;
  return  $get_scored=round($get_scored,2);



 }


if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
  ///go : current state
 // if($_SESSION['ses_curr_state_url']!='quiz.php'){
 //  header("location:".$_SESSION['ses_curr_state_url']); exit; // go, state page wherver
 //  }

 $tutor_id=$_SESSION['ses_teacher_id'];


  ///validate:in_proces test /// in_process completed
  include('inc/sql_connect.php');

  $p2db=p2g(); // tutor_id test_type quiz_test_id status

   $quiz_1_score=quiz_score($p2db,$getid=93,$test_id=5);

 echo  'TTTet scored--'.$quiz_1_score;


  echo $test;
  die ;
  ///////////////////////////
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
									
									<section>
										<div class="inner">
											<h3 class="text-center">Quiz</h3>

											

											<div class="form-row">

												<div id="content" class="col-md-6 border-right text-center">
												 <a  href="quiz_start.php?type=math" name="save" class="btn btn-lg btn-primary" >Math</a>
												    

												</div>

												<div id="content" class="col-md-6 border-left text-center">
												<!-- <button name="save" class="btn btn-lg btn-primary" type="submit">Maths</button> -->
												 <a  href="quiz_start.php?type=english" name="save" class="btn btn-lg btn-primary" >English</a>

												</div>

											</div>
											<br/>

											
										</div>

									</section>
									<!-- SECTION 2 -->


									
									<!-- <h2>
										<span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
										<span class="step-text">Confirm</span>
									</h2>
									<section>

									</section> -->
									<!-- <p class="txt-right"> <a>Previous</a> </p> -->
 
								<p class="text-right col-md-12"><button  onclick="alert('Interview,coming soon');"  
														class="btn btn-ig btn-success">Next</button></p>


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