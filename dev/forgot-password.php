<?php
/*
@  $from_email='support@tutorgigs.io'; 
**/
include('config/connection.php'); 
session_start();ob_start();
// Loign
@extract($_POST);
@extract($_GET);
$from_email='support@tutorgigs.io'; 

function sendEmailToTutor($email,$to,$message,$f_name){
 // $to = "isha@srinfosystem.com";
$subject = "Password Reset- Tutorgigs.io";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
 //$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';

}




  


  // $from_email=$to='ajay@srinfosystem.com';
  //  if(sendEmailToTutor($from_email,$to,$message,$f_name)){
  //     echo ' send';
  //  }else{
  //   echo 'not send';
  //  }

  //   die; 


#================================
if( isset($_POST['login_submit']) ) {
    // email password
    
    //print_r($_POST); die;  



  #=================================================  
        $user_name = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
         // Only Selected Role as Admin 
           $email = stripslashes($user_name);
    // $password = stripslashes($password);
    $email = mysql_real_escape_string($email);
    // $password = mysql_real_escape_string($password);
    $md5password = md5($password);
                
                


          if($role =="administrator"){   
            // SQL query to fetch information of registerd users and finds user match.
    $query = mysql_query("SELECT * FROM gig_admins WHERE password='$md5password' AND email='$email' LIMIT 1", $link)or die(mysql_error());

    $rows = mysql_num_rows($query);
  


        }elseif($role =="teacher"){

           $code=substr(md5(mt_rand()),0,15);
        $to= $email;
        





          //Set :Tutor password. 
          //$error = "Can Not login now";mysql_query
          $query =mysql_query("SELECT * FROM gig_teachers WHERE email='$email'" );
           //echo $query; die; 
           $rows = mysql_num_rows($query);


           if ($rows == 1) {
               $data= mysql_fetch_assoc($query);
                if($data['status']==2){
                    $_SESSION['suspended']='Your account suspended, Please contact service provider. !';
                   // suspended 
                }else{ 

                  //  Set Password Reset 

                  $lastest_login = date('Y-m-d H:i:s');
                  $f_name=$data['f_name'];
                  $code_url='https://tutorgigs.io/reset-password.php?code='.$code;
                  $code_url.='&em='.$email;

                   $message = "Dear {$f_name},
        <br/><br/>
        To change your password, click the link below. 
  <br/><a href='".$code_url."'>Click here</a>

  <br/><br/>
  Best regards,<br />
  <strong>Tutorgigs Team</strong><br/>
  www.tutorgigs.io<br>
  Tel +185534-LEARN<br>
  Email: support@tutorgigs.io
  <br /><br />
  <img alt='' src='https://tutorgigs.io/logo.png'>";
                  // $data['id']; 
                  $sql=mysql_query(" UPDATE gig_teachers SET pass_code='$code' WHERE id=".$data['id']);
                  if($sql){

                    if(sendEmailToTutor($from_email,$to=$email,$message,$f_name)){
                     $error = "Email sent for password Reset!"; 
                     unset($_POST);
                   }else{
                     $error = "Error in sending email!"; 
                   }

                  }


                
      
                   
                   
                    //    $error = "Login success..Tutor";
                      // header("location:./dashboard/index.php");exit;
                }
      //$_SESSION['login_status']=$row['status'];   
              //1 
           }else $error= "Invalid Email!";
          
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

    <title>Forgot-Password-Tutorgigs</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

   
    <link href="css/landing-page.min.css" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-light bg-light static-top">
      <div class="container">
        <a class="navbar-brand pull-left" style="width:5px;" href="https://tutorgigs.io/" ><img src="logo.png"></a> 
        
         <div class="pull-right" >
        <a class="btn btn-primary" href="login.php">Sign In</a>
        <a class="btn btn-primary" href="signup.php#signup_section">Sign Up</a>
        </div>

      </div>
    </nav>

    <!-- Masthead -->
   

    <!-- Icons Grid -->
    <section class="features-icons bg-light text-center">
      <div class="container">
      <form class="form-signin" method="POST">
     
      
      <h2 class="form-signin-heading text-primary text-center">Forgot Password</h2>
       <?php
       echo (isset($_SESSION['suspended']))?$_SESSION['suspended']:NULL;
       
       if(isset($_SESSION['suspended'])&&!empty($_SESSION['suspended'])) 
       $_SESSION['suspended']=NULL;
       //
       if(isset($error)){ ?>
          <div class="alert alert-danger" role="alert"> <?php echo $error; ?> </div><?php } ?>
      
      
      
      
        <select name="role" class="form-control uname"   >
           
          <option <?=($_POST['role']=="teacher")?"selected":'selected'; ?> value="teacher">Tutor</option>
                                                           
                                                        </select> <br/>
   
           <input type="email" name="email" value="<?=$_POST['email']?>" class="form-control" placeholder="Email" required>
  


     

 
        <button name="login_submit"  style="margin: 10px" 
         class="btn btn-lg btn-primary " type="submit">Submit</button>
        
      </form>
</div>
       </section>

   

    <!-- Testimonials -->
   


  

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
            <p class="text-muted small mb-4 mb-lg-0">&copy; Pathways 2 Greatness, LLC 2018. All Rights Reserved.</p>
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
