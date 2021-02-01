<?php
/**
@   $page_name='Payment Info';
**/

 $page_name='Payment Info';
 include("header.php");

  

 //Valid User///////
 if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
//validate failed user


 $tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));
 //print_r($tutor_det);
 @extract($tutor_det);
// echo $status_from_admin;

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

###########################################


$tutor_stages_arr= array('application' =>1, // Application OK
  'quiz' => 1,
  'interview' => 1,
  'background_checks' =>1,
  'payment_info' => 1,
   'legal_stuff' => 0,
  'training' =>0);// 



//////Save|add Profile///////

if(isset($_POST['submit']) ) {//save
 //$records=$_POST);
 // print_r($_POST); die;

//////////////////

 $is_valild=0; //1= can add data . 
$is_step_completed=0; // Current state.
  $is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']);
  $num=mysql_num_rows($is_record);
    $tutorid=$_SESSION['ses_teacher_id'];
  $regis_state_str=serialize($tutor_stages_arr);



if (empty($_POST['email'])) { // 
  $warning[]='Please select a email';
       }


if (empty($_POST['phone'])) { // 
  $warning[]='Please select a phone';
       }


/////////////////////////

  if(empty($warning)){
    // echo 'OK, saved info ';   Move for next step
    $is_step_completed=1;//
    $is_valild=1;
    //print_r($is_step_completed);
    $_SESSION['success']='Success, Go to next step ';
    
  }
    /// Validate email and phone
  

    ///////////////////////

  if($is_valild==1){
    $msg='Profile added'; // Payment detail add|updated



    $sql=" UPDATE `tutor_profiles` SET payment_email= '".$_POST['email']."',payment_phone='".$_POST['phone']."' WHERE tutorid=".$_SESSION['ses_teacher_id'];
    $res=mysql_query($sql);

     ////////if step completed. 
    $step_2_url='legal_stuff.php';  //  mysql_query payment_em payment_phone

     $Update=mysql_query(" UPDATE `gig_teachers` SET payment_em='".$_POST['email']."', payment_phone='".$_POST['phone']."',signup_state= '$regis_state_str',all_state_url='$step_2_url',status_from_admin='legal_stuff',
      payment_info='1' WHERE id=".$_SESSION['ses_teacher_id']);
     // echo $Update; die;
     // re-direct to next step
     header("Location:legal_stuff.php"); exit; 
  


  }  

if(!empty($warning)){
  $_SESSION['warning_msg']=implode('<br/>', $warning);
}

      
    ///Set warning//////
    // if(!empty($warning)){
    //    $_SESSION['warning_msg']=implode('<br/>', $warning);
    // }
    //  header("Location:application.php"); exit; 


}

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


                  <li role="tab" aria-disabled="false" class="current" aria-selected="true"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><span class="current-info audible"> </span><div class="title">
                    <span class="step-icon"><i class="zmdi zmdi-card"></i></span>
                    <span class="step-text">Payment Info</span>
                  </div></a></li>

                  

                  <li role="tab" aria-disabled="false"><a id="form-total-t-4" href="#form-total-h-4" aria-controls="form-total-p-4"><div class="title">
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
                      <div class="tab" style="display: block;">
                          
                      <div class="inner">
                

                        



                            <h3 class="text-center text-primary"><?=$page_name?></h3>


                             <?php // if($warning&&is_array($warning)){
                              if(isset($_SESSION['warning_msg'])){
                               // echo $_SESSION['warning_msg']; 
                              
                            ?>
                           <div class="alert alert-danger" role="alert"><strong>Warning-</strong><br/>
                           <?php echo $_SESSION['warning_msg'];?>
                           </div>  <?php   unset($_SESSION['warning_msg']);  }  // $_SESSION['success']?>


                           <?php  if(isset($_SESSION['success'])&&isset($stop)){ ?>

                           <div class="alert alert-success"
                            role="alert">
                           <strong>Success-</strong> Go,to next step .
                           <a class="btn btn-primary" href="legal_stuff.php">Legal Stuff</a>
                                                      </div> 
                                  <?php   unset($_SESSION['success']);  }?>

                                 <!--  end msg area -->
                                 <?php 


                                 ?>




                            <div class="form-row">
                              <label>Paypal email address  </label> 
                            <div class="form-holder form-holder-2">
                              <label class="form-row-inner">
                                 
                                    <input  type="email" name="email" placeholder="Email Address"  class="form-control text-primary"
                                     value="<?=(isset($_POST['email']))?$_POST['email']:$records['email'] ;?>"   >
                                  
                              </label>
                            </div>
                          </div>

                          <div class="form-row">
                            <label>Paypal Phone Number</label>
                              <div class="form-holder form-holder-2">
                                <input type="text"   name="phone" placeholder="Phone Number" class="form-control" 
                                 value="<?=(isset($_POST['phone']))?$_POST['phone']:$records['phone'] ;?>" >
                
                              </div>
                            </div>

                        <!-- Form2 data -->


          
                           


                          



                      <!--   <div class="form-row">
                         <label>What makes a good Tutor?</label> 
                          <div class="form-holder form-holder-2">
                              <label class="form-row-inner">
                              <input type="text" name="f3_q_2" value="" class="form-control" disabled="">
                            </label>
                          </div>
                        </div> -->
                        
                        
                        
                                
                        
                        
                        
                        
                        
                        
                                                
                        
                        

                        
                        
                        
                        
                                                

                        <!-- PHOTO ID -->
                        




                         </div></div>
                          <p class="text-center">

                          <button   type="submit"   name="submit" 
                         id="nextBtn" onclick="">Submit</button> </p>




                  <!--  <p class="text-center">
                    <a href="applicant-list.php" class="btn btn-sm btn-primary">Back</a></p> -->




                    </form>




                    
              <!-- End form -->
            </div>
          </div>
        </div>
      </div>    <!-- /#content -->
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