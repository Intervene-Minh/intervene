<?php
include('config/connection.php'); 
session_start();ob_start();
// Loign
@extract($_POST);
@extract($_GET);
if( isset($_POST['login_submit']) ) {
    // email password
    
    //print_r($_POST); die;  
        $user_name = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
         // Only Selected Role as Admin 
        
       // if($role =="administrator"||$role =="teacher"){
           $email = stripslashes($user_name);
    // $password = stripslashes($password);
    $email = mysql_real_escape_string($email);
    // $password = mysql_real_escape_string($password);
    $md5password = md5($password);
                
                
          if($role =="administrator"){   
            // SQL query to fetch information of registerd users and finds user match.
    $query = mysql_query("SELECT * FROM gig_admins WHERE password='$md5password' AND email='$email' AND status=0 LIMIT 1", $link)or die(mysql_error());

    $rows = mysql_num_rows($query);
    if ($rows == 1) {
      $row = mysql_fetch_assoc($query);
      $lastest_login = date('Y-m-d H:i:s');
      //$query = mysql_query("UPDATE users SET latest_login='$lastest_login' WHERE password='$md5password' AND email='$email'", $link);
      $query2 = mysql_query("UPDATE gig_admins SET latest_login='$lastest_login' WHERE id='".$row['id']."'", $link) or die(mysql_error());
      
      $_SESSION['login_id']=$row['id'];
      $_SESSION['login_user']=$row['user_name']; // Initializing Session
      $_SESSION['login_mail']=$row['email'];
      $_SESSION['login_role']=$row['role'];
      $_SESSION['login_status']=$row['status'];
      
      // Init session first login
      //if( $row['email'] == 'demo@p2g.org' )
        $_SESSION['firstlogin'] = true;
      // Redirecting To Other Page myadmin/login.php
                        $error = "Success, login";
      
      //Allow reduced admin called manager login
      //kh JIRA- MANPORTAL
      if($row['role']=="100"){  //Basic Manager
              header("location:./mymanager/index.php");exit;
            }   
      elseif ($row['role']=="101"){  //Frog tutoring
              header("location:./mymanager/index.php");exit;
            }
      elseif ($row['role']=="102"){   // Tutor4U
              header("location:./mymanager/index.php");exit;
            }
      else{  //Full Admin
              header("location:./myadmin/index.php");exit;
            }



    } else {
      $error = "Email or Password is invalid";
    }
        }


/* start tutor login*/
else if($role =="teacher")
{
      //$error = "Can Not login now";
      $query = mysql_query("SELECT * FROM gig_teachers WHERE password='$md5password' AND email='$email' LIMIT 1", $link);
      $rows = mysql_num_rows($query);
      if ($rows == 1) 
      {
            $data= mysql_fetch_assoc($query);
            if($data['status']==0){
              $_SESSION['suspended']='Your account inactive, Please confirm email. !';

            }elseif($data['status']==2){
               $_SESSION['suspended']='Your account suspended, Please contact service provider. !';
            // suspended 
            }else{

                    $lastest_login = date('Y-m-d H:i:s');
                    $_SESSION['ses_teacher_id']=$data['id']; // ses_teacher_id# login_id
                    $_SESSION['ses_curr_state_url']=($data['all_state']=='no')?$data['all_state_url']:'home.php';  // next default: application.php
                    $_SESSION['ses_total_pass']=$data['total_pass_quiz'];  // no
                    $_SESSION['ses_access_website']=$data['all_state'];  //yes or No
                    $_SESSION['login_user']=(!empty($data['f_name']))?$data['f_name']:$data['email']; // Initializing Session
                    $_SESSION['login_mail']=$data['email'];
                    $_SESSION['login_role']=1;
                    $error = "Login success..Tutor";
                    mysql_query("UPDATE  gig_teachers SET  `loginStatus`='1' WHERE id='".$data['id']."'", $link);
                    header("location:./dashboard/index.php");exit;
            }
            //$_SESSION['login_status']=$row['status'];   
            //1 
      }
      else{ 

        $error= "Invalid Email&Password Combination ";
    }

}

        
        /* end tutor lohin*/
        
        
      
  }



?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login-Tutorgigs</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

   
    <link href="css/landing-page.min.css" rel="stylesheet">
   <!-- <script src="https://www.google.com/recaptcha/api.js"></script>-->
<script>
  window.onload = function() {
    var $recaptcha = document.querySelector('#g-recaptcha-response');

    if($recaptcha) {
        $recaptcha.setAttribute("required", "required");
    }
};
</script>
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

      <h2 class="form-signin-heading text-primary text-center">Please Login 7</h2>

       <?php
       echo (isset($_SESSION['suspended']))?$_SESSION['suspended']:NULL;
       
       if(isset($_SESSION['suspended'])&&!empty($_SESSION['suspended'])) 
       $_SESSION['suspended']=NULL;
       //
       if(isset($error)){ ?>
          <div class="alert alert-danger" role="alert"> <?php echo $error; ?> </div><?php } ?>
      
      
      
      
        <select name="role" class="form-control uname"   >
            <option <?=($_POST['role']=="administrator")?"selected":NULL; ?> value="administrator">Administrator</option>
          <option <?=($_POST['role']=="teacher")?"selected":NULL; ?> value="teacher">Tutor</option>
                                                           
                                                        </select> <br/>
   
           <input type="email" name="email" value="<?=$_POST['email']?>" class="form-control" placeholder="Email" required>
  
        <label for="inputPassword" class="sr-only">Password</label> 
        <br/>
        <input type="password" value="<?=$_POST['password']?>" name="password" id="inputPassword" class="form-control" placeholder="Password" required>  <br/>

   <!--   <div class="g-recaptcha" data-sitekey="6Lc3krsUAAAAAKnDeSlTaa2ggYc3hDtf9aY7Kiq-"></div> -->
      
        <button name="login_submit" class="btn btn-lg btn-primary " type="submit">Login</button>
		<a href="https://tutorgigs.io/forgot-password.php" class="text-primary"> Forgot Password</a>
        
      </form>
</div>
       </section>

   

    <!-- Testimonials -->
    
<style>
      
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
