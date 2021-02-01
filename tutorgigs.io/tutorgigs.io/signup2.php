<?php
//$to = "somebody@example.com, somebodyelse@example.com";

// mail($to,$subject,$message,$headers);
 // if(mail($to,$subject,$message,$headers)) echo 'send ';
 //else echo 'Not send' ; 

 //echo 'TSET'; die;
echo 'hii';
?>

<?php
include('config/connection.php');
session_start();
ob_start();
// Loign
@extract($_POST);
@extract($_GET);
$error = '';
///////////////////////////
 //include 'PHPMailer-master/PHPMailerAutoload.php';
// function sendEmailToTutor($email,$to,$message){
 

  
//   // Create a new PHPMailer instance
//   $mail = new PHPMailer;
//   // Set who the message is to be sent from
//   $mail->setFrom('pathways2greatness@gmail.com', 'Tutogigs');
//   // Set an alternative reply-to address
//   $mail->addReplyTo('pathways2greatness@gmail.com', 'Tutogigs');
//   // Set who the message is to be sent to
//   $mail->addAddress($to, '');
//   // Set the subject line
//   $mail->Subject = 'Confirmation Mail';
//   // Replace the plain text body with one created manually
//   $mail->Body = $message;
//   $mail->AltBody = $message;
//   // send the message, check for errors
//   if (!$mail->send()) {
//     return false;
//   } else {
//       return true;
    
//   }
// }
//////////////////////////
function sendEmailToTutor($email,$to,$message){
 // $to = "isha@srinfosystem.com";
$subject = "Tutorgigs-Tutor Regiter";

//$message = "Hiiiiiiiiiiiiiiii";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <learn@intervene.io>' . "\r\n";
$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';

}



//////////////////////
if( isset($_POST['signup_submit']) ) {
    // email password
    
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
    
        $message = "Welcome to Tutorgigs!
  <br><br>
  <a href='".$code_url."'>Click here</a>,to confirm email or copy and paste below URL in browser:<br>
  'https://tutorgigs.io/profile_confirm.php?code=$code'
  <br>
  Best regards,<br />
  <strong>Tutorgigs Team</strong><br/>
  www.tutorgigs.io<br>
  Tel +185534-LEARN<br>
  Email: learn@intervene.io
  <br /><br />
  <img alt='' src='https://tutorgigs.io/logo.png'>";

   $sql = mysql_query("SELECT * FROM `gig_teachers` WHERE email='$email'");
   if (mysql_num_rows($sql) > 0) {
    // output data of each row
    $row = mysql_fetch_assoc($sql);
    if($email==$row['email'])
    {
      $_SESSION["MESSAGE_STATE"]["MSG"] = "Email Already Exist ";
      $_SESSION["MESSAGE_STATE"]["TYP"] = "alert-danger";
    }
  }else { //here you need to add else condition
    $from_email='learn@intervene.io'; // learn@intervene.io learn@p2g.org
       sendEmailToTutor($from_email,$to,$message); // ($email,$to,$message){
        if(sendEmailToTutor($from_email,$to,$message)){
         $msg= 'OK Mail sent '; //die; 

        }else{ $msg= 'not send '; // die; 
        }

         //echo $msg ; die; 

    $query = mysql_query("INSERT INTO `gig_teachers`(`f_name`, `lname`, `email`, `password`, `phone`, `code`) VALUES ('$f_name','$lname','$email','$md5password','$phone','$code')");
    // $query = "INSERT INTO `gig_teachers`(`f_name`, `lname`, `email`, `password`, `phone`, `code`) VALUES ('$f_name','$lname','$email','$md5password','$phone','$code')";
    // echo $query;
    
    if($query){
      // Send Email ...
      $_SESSION["MESSAGE_STATE"]["MSG"] = $msg.",An Activation Code Is Sent To You Check Your Email ";
      $_SESSION["MESSAGE_STATE"]["TYP"] = "alert-success";
     
    }
    header("Location:signup.php?success=1");exit;
  }
 

 
}
  
  


?>
<html>
<head>
  <title>SignUp-Tutor</title>
  
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

<link rel="stylesheet" href="styles.css" >

<!-- Latest compiled and minified JavaScript -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
  $("form").submit(function(){
    //alert("Submitted R"); return false;
  var confirm1= $('#signup_email').val();
  var confirm2= $('#confirm_signup_email').val();

  if(confirm1!=confirm2){
    alert('Email not matched');  return false;
  }else return true;
    //OK
    
  });
});
</script>
</head>

<body>

<div class="container">
      <form class="form-signup" id="form-signup" method="POST">
     
     
      <h2 class="form-signin-heading text-primary text-center">Tutor Sign Up</h2>
      
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
          <input type="text" name="confirm_email" onkeyup="confirmEmail()" id="confirm_signup_email" value="<?=$_POST['confirm_email']?>" class="form-control" placeholder="Confirm Email" required>
          <br>
          <input type="text" name="phone" value="<?=$_POST['phone']?>"  class="form-control" placeholder="Mobile" required>
          <br>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" value="<?=$_POST['password']?>" placeholder="Password" required>
        <br>
        <button id="signup-submit" name="signup_submit"
         class="btn btn-lg btn-primary btn-block" type="submit" >Sign In</button>
        
        
      </form>
</div>

</body>

</html>
<?php // session_unset(); ?>