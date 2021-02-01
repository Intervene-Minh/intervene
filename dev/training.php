<?php
/*****
 @Step 3: Interview
//////////
@ echo 'Bg checks pending'; 

 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //Interview Button

****/
   $page_name='Training ';
 include("header.php");


 include('inc/sql_connect.php');
  $tutorid=$_SESSION['ses_teacher_id'];
  $sql=" SELECT * FROM `training_attempts` WHERE tutor_id=".$tutorid;
    $sql.=" AND status='completed' ";
    //echo $sql; die; 
   $p2db=p2g();
    $res=mysqli_query($p2db,$sql);
    $count_attempted=mysqli_num_rows($res);
 //echo '===='.$count_attempted;






 //Valid User///////
 if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
//validate failed user
/////////////////////

$dir = "../training_docs/";

// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
     $file_arr=array();
    while (($file = readdir($dh)) !== false){

    if ($file == '.' || $file == '..') {
        continue;
    }else{
       //echo "filename:" . $file . "<br>";
      $file_arr[]=$file;
    }
       ////
     
    }
    closedir($dh);
  }
}


 print_r($file_arr);



/////////////////////////

 $tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));
// print_r($tutor_det);
 @extract($tutor_det);
 //echo $status_from_admin;

//Signup State
  $user_state_arr=unserialize($tutor_det['signup_state']);
 // print_r($user_state_arr); // Save state by user. 
 $profile=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));
$edit=unserialize($profile['profile_1']);
 //print_r($edit);

$records=array();

$records['email']=(isset($profile['payment_email']))?$profile['payment_email']:null;
$records['phone']=(isset($profile['payment_phone']))?$profile['payment_phone']:null;   // $profile['payment_phone'];
//print_r($records);








// start_test
 if(isset($_POST['start_test']) ){

 	header("Location:training_start.php"); exit;
   // print_r($_POST); die; 
  // training test session start. : 
 }

##########################Application state arr########################################
//////Add profile 1/////// if(isset($_POST['Request']) ) {

//if(isset($_GET['action'])&&$_GET['action']=='schedule'){
	// if(isset($_POST['Request']) ){

 //    $calendly_url='https://calendly.com/tutorgigs';
 //    //echo 'Go interview step';  die;

 //    $up=" UPDATE gig_teachers SET legal_stuff='2' WHERE id=".$_SESSION['ses_teacher_id'];
 //    $result=mysql_query($up);
 //    header("Location:".$_SERVER['PHP_SELF']); exit;



 //    // die; 
 //  }

 ///CHeck result///


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
								<?php  include("training_stage.php"); ?>
                                         
                          


									<!--Content:Section-->
									<?php 
									$prev_stage='legal_stuff';
									$next_stage='training_attempt';
									$page_stage='training';

									/// Stage case at :legal_stuff.php
									$show_form=0;//Request form
									if($status_from_admin=='interview_rejected'){
										$msg='Application rejected';
									}elseif($legal_stuff!=1){
										
										$msg='Application not alowed';



									}elseif($legal_stuff==1){ 
										//current state.:prev compelted
										if($training==0)
											$show_form=1; //see
										elseif($training==2)
											$msg='Pending approval';
										elseif($training==1)
									     $msg='Completed, next step ';

									}

									//////////////
									//echo $msg; 
									//echo '======'.$show_form;

								
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
								<h3 class="text-center text-primary"><?=$page_name?> 
                                  <?php if($count_attempted>0){ ?>
								| <a class="btn btn-success btn-xs" href="training_result.php">View Results</a>
								<?php }?>

								 </h3>

											

											<div class="form-row">

												<div id="content" class="col-md-12">
												<p class="text-center">
												 <button type="submit" name="start_test"
												  class="btn btn-warning btn-sm" >Start Test Now?</button>
												  </p>
												  <p>
												  	<?php 
												  	$url_file1='https://docs.google.com/gview?url=http://tutorgigs.io/dashboard/technology-testing.pptx&embedded=true';




												 // 	echo $url_file;

												  	if(isset($_GET['key'])){
                                                   	 	$change_url=$file_arr[$_GET['key']];
                                                   	 	$set_key=$_GET['key'];
                                                   	 }else{
                                                   	 	$change_url=$file_arr[0];// Firs file
                                                   	 	$set_key=0;

                                                   	 }// technology-testing.pptx



                                  $url_file='https://docs.google.com/gview?url=http://tutorgigs.io/training_docs/'.$change_url.'&embedded=true';

                                                   	 echo '==File:'.$change_url;

												  	///////////

                                                   foreach ($file_arr as $key => $value) {
                                                   	# code...
                                                   	# key=2
                                                   ///$fi
                                                   	// btn btn-success btn-xs
                                                  // $change_url=(isset($_GET['key']))?$file_arr[]
                                                   	$activ_clss=($key==$set_key)?'btn btn-success btn-xs':'btn btn-link btn-xs';


												  	?>
	  	
								<h3 class="text-left text-primary">  
                                 <a class="<?=$activ_clss?>" href="training.php?key=<?=$key?>">File Link:<?=$value?></a>
								
								 </h3>  <?php   }
								                     ?>



												  </p>
												<!-- <p class="text-center"> </p> -->

                               <iframe style="width: 100%; height: 500px;"    src="<?=$url_file?>">
  <p>Your browser does not support iframes.</p>
</iframe>



										
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

<?php include("footer.php"); ?>
<?php  //print_r($_SESSION);//?>