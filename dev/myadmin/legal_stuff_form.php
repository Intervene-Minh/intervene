<?php
/*****


****/

///
 include("header.php");
 $step_2_url='quiz.php'; //QUIZ Button
 //Valid User///////
 if (!isset($_GET['tid'])){
 	exit('page not founnd');
 }



function quiz_result($p2db,$getid,$test_id){
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

//////////////////////

     if (isset($_GET['tid'])) {
    	$getid=$_GET['tid'];
    }

 $tdata=$tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$getid));

  $get_state_arr=unserialize($tutor_det['signup_state']);
   //  print_r($get_state_arr);

  //////Profile data////


 $data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$getid));

$edit=unserialize($data2['profile_1']);

  //echo '<pre>', print_r($edit);

 //print_r($data2['subjects']); echo '<br/>';

include('inc/sql_connect.php'); 
   $p2db=p2g();

 if($tdata['quiz_1_status']=='completed'&&$tdata['quiz_1_id']>0){

  $test_id=$tdata['quiz_1_id']; //Default 
  $quiz_1_score=quiz_result($p2db,$getid,$test_id);
}


///Quiz 2 result 
if($tdata['quiz_2_status']=='completed'&&$tdata['quiz_2_id']>0){
  $test_id=$tdata['quiz_2_id']; //Default 
  $quiz_2_score=quiz_result($p2db,$getid,$test_id);
}


//print_r($quiz_1_score); 
  
  // print_r($quiz_2_score); 



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
		margin-bottom:20px;
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
										      <div class="inner col-md-12">
										      <?php 
										      //echo '=='.$data2['is_legal_1'].':=is_legal_2'.$data2['is_legal_2'];

										      if($data2['is_legal_1']==1&&$data2['is_legal_2']==1){?>
										    <!--   Legal Form Data -->
										    <h3 class="text-center text-primary">Legal stuff:</h3>

										    <!--<h4 class="text-center text-primary">Policy Form signature:</h4>-->

                                               <?php include "../dashboard/form_privacy_terms.php";?>

                                               <!--  Form: Documents -->

										     

										        <div class="row">
											        <div class="col-md-6">
														<label>Name</label> 
														<input disabled="" type="text" value="<?=$data2['pri_name'];?>" class="form-control text-primary">
													</div>
                                                    <div class="col-md-6">
													    <label>Date</label> 
													    <input disabled="" type="text" value="<?=$data2['pri_tutor_date'];?>" class="form-control text-primary">	
													</div>													
												</div>

													

													<div style="display:inline-block; padding:20px;"> 
													<label>Signature</label> 
													<?php  echo  $data2['pri_tutor_sign'];?>
													</div> <br/>



													<!-- 2nd Form -->

													<?php $terms=unserialize($data2['terms_form_data']);
													//print_r($terms);
													?>
										    
										     <h4 class="text-center text-primary">Terms Form:</h4>
										     <?php include "../dashboard/form_terms.php";?>
										     <br/>

										      

										        <div class="row">
												    <div class="col-md-6">
													    <label>Name</label>  
												        <input disabled="" type="text" value="<?=$terms['term_form_name'];?>" class="form-control text-primary">	  
															
													</div>	
													<div class="col-md-6">
														<label>Date</label> 
														<input disabled="" type="text" value="<?=$terms['term_form_date'];?>" class="form-control text-primary">	
													</div>
												</div>

													

													 <div style="display:inline-block; padding:20px;">
														 <label>Signature</label> 
														 <?php  echo  $data2['term_tutor_sign'];?>
													 </div> <br/>

													<?php } //if($data2['is_legal_1']==1&&$data2['is_legal_2']==1){?>

													<!-- Payment info provived -->


										  <!-- </div> -->
									 <p class="text-center">
									  <a href="applicant-list.php" class="btn btn-sm btn-primary">Back</a></p>




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
	 <script type="text/javascript">
                                          $(document).ready(function(){
                                         	$("svg").css({"width": "90%", "height": "200px"});
                                         	});
                                         </script>

<script>
  // $("#target :input").prop("disabled", true);
$(document).ready(function(){
  $("input").prop("disabled", true);
});


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



<?php include("footer.php"); ?>