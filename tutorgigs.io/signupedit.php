<?php
include('config/connection.php');
session_start();ob_start();
@extract($_POST);
@extract($_GET);
$error = '';


 //include 'PHPMailer-master/PHPMailerAutoload.php';
// function sendEmailToTutor($email,$to,$message){
 //echo '===';
//////////////////////////
function sendEmailToTutor($email,$to,$message,$f_name){
 // $to = "isha@srinfosystem.com";
$subject = "Please Confirm Email - Tutorgigs.io";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
//$headers .= 'Cc: gulshan@srinfosystem.com' . "\r\n";
$emailList='rohit@srinfosystem.com';
 $headers.= "Bcc: $emailList\r\n";
 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';

}



//////////////////////
if( isset($_POST['signup_submit']) ) {
    // email password
   // step_2 : wizard form 1
  $signup_state_arr= array('step_1' =>0, // Application
  'step_2' => 0,
  'step_3' => 0,
  'step_4' => 0,
  'email_confirm' => 1,
   'can_access_website' => 0,
  'status_to_login' =>1);// 
  $state_str=serialize($signup_state_arr);
   // print_r($state_str); die;
    
   // print_r($_POST); die;  
        $email = $_POST['email'];
        $password = $_POST['password'];
        $f_name = $_POST['f_name'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $md5password = md5($password);
        $code=substr(md5(mt_rand()),0,15);
        $to= $email;
        $code_url='https://tutorgigs.io/profile_confirm.php?code='.$code;
    
        $message = "Dear {$f_name},
        <br/><br/>
        Thank you for signing up to tutor with Tutorgigs.io we are excited to get started working with you. 
  <br/>
  Next step is to <a href='".$code_url."'>confirm your email here</a>

  <br/><br/>
  Best regards,<br />
  <strong>Tutorgigs Team</strong><br/>
  www.tutorgigs.io<br>
  Tel +185534-LEARN<br>
  Email: support@tutorgigs.io
  <br /><br />
  <img alt='' src='https://tutorgigs.io/logo.png'>";
     //echo  $message; die;

   $sql = mysql_query("SELECT * FROM `gig_teachers` WHERE email='$email'");
   if (mysql_num_rows($sql) > 0) {
    // output data of each row
    $row = mysql_fetch_assoc($sql);
    if($email==$row['email'])
    {
      $_SESSION["MESSAGE_STATE"]["MSG"] = "Email Already Exist ";
      $_SESSION["MESSAGE_STATE"]["TYP"] = "alert-danger";
    }
  }else { //
    $from_email='support@tutorgigs.io'; // learn@intervene.io learn@p2g.org
        if(sendEmailToTutor($from_email,$to,$message,$f_name)){
         $msg= "Email sent,<br/>";

        }else{ $msg = "Email not send,";}

         //echo $msg ; die; signup_state all_state_url
        $datetm = date('Y-m-d H:i:s'); 
        $next_state_url='application.php';// created_date

    $query = mysql_query("INSERT INTO `gig_teachers`(`created_date`,`f_name`, `lname`, `email`, `password`, `phone`, `code`,`signup_state`,`all_state_url`) VALUES ('$datetm','$f_name','$lname','$email','$md5password','$phone','$code','$state_str','$next_state_url')"); 
    //echo $query; die; 


    // $query = "INSERT INTO `gig_teachers`(`f_name`, `lname`, `email`, `password`, `phone`, `code`) VALUES ('$f_name','$lname','$email','$md5password','$phone','$code')";
    // echo $query;
    
    if($query){
      // Send Email ...
      // 
     // $_SESSION["MESSAGE_STATE"]["MSG"] = $msg."An activation code is sent to you, check your email.";
      $_SESSION["MESSAGE_STATE"]["MSG"] ="A confirmation email has been sent to your email address. Please check your email to confirm your account.";
      $_SESSION["MESSAGE_STATE"]["TYP"] = "alert-success";
      header("Location:signup.php?success=1");exit;
     
    }
    
  }
 

 
}
  


?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tutor SignUp-Tutorgigs</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">


    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

   
    <link href="css/landing-page.min.css" rel="stylesheet">
    <link href="csstutorzoho/tutorgigszohoform.css" rel="stylesheet" type="text/css"><script src="jstutorzoho/tutorgigszohoform.js"></script>
    <style type="text/css">
      #signup_section{background: url(../img/bg-masthead.jpg) no-repeat center center;}
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
  window.onload = function() {
    var $recaptcha = document.querySelector('#g-recaptcha-response');

    if($recaptcha) {
        $recaptcha.setAttribute("required", "required");
    }
};
$(document).ready(function(){
  $('#msg_section').hide();
  $("form").submit(function(){
    //alert("Submitted R"); return false;
  var confirm1= $('#signup_email').val();
  var confirm2= $('#confirm_signup_email').val();
     var msg='Error-<br/>';

  if(confirm1!=confirm2){
    $('#msg_section').show();
     msg='Email not matched';  
    // msg_section
    $('#msg_section').html(msg);
    return false;
  }else return true;
    //OK
    
  });
});
</script>

  </head>

  <body>

   <nav class="navbar navbar-light bg-light static-top">
      <div class="container">
        <a class="navbar-brand pull-left" style="width:5px;" href="https://tutorgigs.io/" ><img src="logo.png"></a> 
        
         <div class="pull-right" >
        <a class="btn btn-primary" href="login.php">Sign In</a>
        <a class="btn btn-primary" href="signup.php#signup_section">Sign Up</a>
        </div>

      </div>
    </nav>

    <!-- Icons Grid -->
    <section class="features-icons bg-light text-center">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
              <div class="features-icons-icon d-flex">
                <i class="icon-screen-desktop m-auto text-primary"></i>
              </div>
              <h3>Step 1: Qualify</h3>
              <p class="lead mb-0">Choose your subject and pass our qualification exam.</p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
              <div class="features-icons-icon d-flex">
                <i class="icon-layers m-auto text-primary"></i>
              </div>
              <h3>Step 2: Accept Jobs</h3>
              <p class="lead mb-0">Based on your availability, we'll notify you when a tutoring gig that matches your qualifications is scheduled</p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="features-icons-item mx-auto mb-0 mb-lg-3">
              <div class="features-icons-icon d-flex">
                <i class="icon-check m-auto text-primary"></i>
              </div>
              <h3>Step 3: Cash Out</h3>
              <p class="lead mb-0">Get paid for your weekly tutoring sessions</p>
            </div>
          </div>
        </div>
      </div>
    </section>

   

    <!-- Testimonials -->
    <section  style="background: url(../img/bg-masthead.jpg) no-repeat center center;" class="testimonials text-center bg-light" id="signup_section">
      <div class="container">
     <!--    <h2 class="mb-5">SignUp</h2> -->
	 <div class="form-background">
        <h2 class="form-signin-heading text-primary text-center">Tutor Sign Up</h2>
        <form class="form-signup" id="form-signup" method="POST">
          <!-- JS Msg -->
               
      <div class="alert alert-danger" role="alert"
       id="msg_section">Enter data</div>
     
     
      
       <?php
       echo (isset($_SESSION['suspended']))?$_SESSION['suspended']:NULL;
       
       if(isset($_SESSION['suspended'])&&!empty($_SESSION['suspended'])) 
       $_SESSION['suspended']=NULL;
       ?>
       <?php 
      
      //  echo $sql;
        
          if(isset($_SESSION["MESSAGE_STATE"])){
            ?>
            <div class="row message-board">
              <div class="col-sm-12">
                <div class="alert <?php echo $_SESSION["MESSAGE_STATE"]["TYP"]; ?>"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <p><?php echo $_SESSION["MESSAGE_STATE"]["MSG"]; ?></p>
                </div>
                

              </div>
            </div>
            <?php
            unset($_SESSION["MESSAGE_STATE"]);
            }
           // $_SESSION["MESSAGE_STATE"]=NULL;
         
          ?>
     
          <input type="text" name="f_name"  class="form-control" value="<?=$_POST['f_name']?>" placeholder="First Name" required>
          <br>
          <input type="text" name="lname"  class="form-control" value="<?=$_POST['lname']?>" placeholder="Last Name" required>
          <br>
          <input type="text" name="email" id="signup_email" value="<?=$_POST['email']?>"  class="form-control" placeholder="Email" required><br>
          <input type="text" name="confirm_email"  id="confirm_signup_email" value="<?=$_POST['confirm_email']?>" class="form-control" placeholder="Confirm Email" required>
          <br>
          <input type="text" name="phone" value="<?=$_POST['phone']?>"  class="form-control" placeholder="Mobile" required>
          <br>
          
<!---------Radio Starts Here---------->    
<li class="zf-radio zf-tempFrmWrapper zf-sideBySide"><label class="form-control">Are you a U.S citizen?
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><div class="zf-overflow">
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio_1" name="Radio" checktype="c1" value="Yes">
<label for="Radio_1" class="zf-radioChoice">Yes</label> </span>
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio_2" name="Radio" checktype="c1" value="No">
<label for="Radio_2" class="zf-radioChoice">No</label> </span>
<div class="zf-clearBoth"></div></div><p id="Radio_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Radio Ends Here---------->    
<!---------Radio Starts Here---------->    
<li class="zf-radio zf-tempFrmWrapper zf-sideBySide"><label class="zf-labelName">Do you have permission to work in the United Stated?
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><div class="zf-overflow">
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio1_1" name="Radio1" checktype="c1" value="Yes">
<label for="Radio1_1" class="zf-radioChoice">Yes</label> </span>
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio1_2" name="Radio1" checktype="c1" value="No">
<label for="Radio1_2" class="zf-radioChoice">No</label> </span>
<div class="zf-clearBoth"></div></div><p id="Radio1_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Radio Ends Here---------->    
<!---------Radio Starts Here---------->    
<li class="zf-radio zf-tempFrmWrapper zf-sideBySide"><label class="zf-labelName">Do you require TutorGigs to provide you with Visa or Sponsorship to be eligible to work in the U.S?
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><div class="zf-overflow">
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio2_1" name="Radio2" checktype="c1" value="Yes">
<label for="Radio2_1" class="zf-radioChoice">Yes</label> </span>
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio2_2" name="Radio2" checktype="c1" value="No">
<label for="Radio2_2" class="zf-radioChoice">No</label> </span>
<div class="zf-clearBoth"></div></div><p id="Radio2_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Radio Ends Here---------->    
<!---------Radio Starts Here---------->    
<li class="zf-radio zf-tempFrmWrapper zf-sideBySide"><label class="zf-labelName">Will you be tutoring online within the continent of United States?
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><div class="zf-overflow">
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio3_1" name="Radio3" checktype="c1" value="Yes">
<label for="Radio3_1" class="zf-radioChoice">Yes</label> </span>
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio3_2" name="Radio3" checktype="c1" value="No">
<label for="Radio3_2" class="zf-radioChoice">No</label> </span>
<div class="zf-clearBoth"></div></div><p id="Radio3_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Radio Ends Here---------->    
</ul></div><!---------template Container Starts Here---------->
<script type="text/javascript">var zf_DateRegex = new RegExp("^(([0][1-9])|([1-2][0-9])|([3][0-1]))[-](Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)[-](?:(?:19|20)[0-9]{2})$");
var zf_MandArray = [ "Radio", "Radio1", "Radio2", "Radio3"]; 
var zf_FieldArray = [ "Radio", "Radio1", "Radio2", "Radio3"]; 
var isSalesIQIntegrationEnabled = false;
var salesIQFieldsArray = [];</script>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" value="<?=$_POST['password']?>" placeholder="Password" required>
        <br>
        
        <div class="g-recaptcha" data-sitekey="6Lc3krsUAAAAAKnDeSlTaa2ggYc3hDtf9aY7Kiq-" required></div>
                          
        <button id="signup-submit" name="signup_submit"
         class="btn btn-lg btn-primary" type="submit" >Submit</button>
        
        
      </form>
     </div>   
      </div>
    </section>

   <style>
      .form-background{
		  background:#eee;
		  padding:20px;
		  width:100%;
		  display:inline-block;
	  }
    #g-recaptcha-response {
    display: block !important;
    position: absolute;
    margin: -78px 0 0 0 !important;
    width: 302px !important;
    height: 76px !important;
    z-index: -999999;
    opacity: 0;
}
   </style>

    <!-- Footer -->
    <footer class="footer bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 h-100 text-center text-lg-left my-auto">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a href="#">About</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="#">Contact</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="/terms_tutorgigs.php">Terms of Use</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="/privacy_tutorgigs.php">Privacy Policy</a>
              </li>
            </ul>
            <p class="text-muted small mb-4 mb-lg-0">&copy; Intervene, LLC 2018. All Rights Reserved.</p>
          </div>
          <div class="col-lg-6 h-100 text-center text-lg-right my-auto">
            <ul class="list-inline mb-0">
              <li class="list-inline-item mr-3">
                <a href="#">
                  <i class="fa fa-facebook fa-2x fa-fw"></i>
                </a>
              </li>
              <li class="list-inline-item mr-3">
                <a href="#">
                  <i class="fa fa-twitter fa-2x fa-fw"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-instagram fa-2x fa-fw"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
  <!--   <script src="vendor/jquery/jquery.min.js"></script> -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
