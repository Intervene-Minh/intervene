<?php
include('config/connection.php'); 
session_start();ob_start();
// Loign
@extract($_POST);
@extract($_GET);
if( isset($_POST['login_submit']) ) {
    // email password
    $warning=array(); // error or warning 
   // print_r($_POST); die;  
        $password1 =trim($_POST['password']);
        $password_2=trim($_POST['password_2']);
        
         // Only Selected Role as Admin 
        
       // if($role =="administrator"||$role =="teacher"){
           $email = stripslashes($user_name);
    // $password = stripslashes($password);
    $email = mysql_real_escape_string($email);
    // $password = mysql_real_escape_string($password);
    $md5password = md5($password1);

   // password_2
    $pass_code=$_GET['code']; // mysql_query
    $user_res=mysql_query(" SELECT * FROM gig_teachers WHERE pass_code='$pass_code' ");

  
    if(mysql_num_rows($user_res)==0){
      $warning[]='Invalid code or Expired!';

    }
  //pass not match ///
     if($password1!=$password_2){
        $warning[]='Error-Message!';
        $warning[]='confirm password not match';
     }

        
   //Displaty messg//
   if(!empty($warning)){
       $error=implode('<br/>', $warning);
        //$error; die; 
    }

   /////////////////////////////
    if(empty($warning)){
       $data= mysql_fetch_assoc($user_res);
      // md5password
      $sql=mysql_query(" UPDATE gig_teachers SET pass_code='',password='$md5password' WHERE id=".$data['id']);
      // Go to Login. 
       $error= 'Password changed successfully!';
      echo "<script>alert('".$error."');location.href='login.php';</script>"; die; 
      $error= 'go update password. ';
      //header("location:login.php");exit;
      

      //die; 
    }

    // die; 
  ///////////Validate/////////////                    
        // if($role =="teacher"){
        //   //$error = "Can Not login now";
        //   $query = mysql_query("SELECT * FROM gig_teachers WHERE password='$md5password' AND email='$email' LIMIT 1", $link);
        //    $rows = mysql_num_rows($query);


           
          
        // }
        
        
        
        
        
}



?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reset Password.php-Tutorgigs</title>

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
     
      
      <h2 class="form-signin-heading text-primary text-center">Rest Password</h2>
       <?php
       echo (isset($_SESSION['suspended']))?$_SESSION['suspended']:NULL;
       
       if(isset($_SESSION['suspended'])&&!empty($_SESSION['suspended'])) 
       $_SESSION['suspended']=NULL;
       //
       if(isset($error)){ ?>
          <div class="alert alert-danger" role="alert"> <?php echo $error; ?> </div><?php } ?>
      
      
      
      
       
                                                         <br/>
   
           <input type="text" name="password" value="<?=$_POST['password']?>" class="form-control"
            placeholder="New password" required>
  
        <label for="inputPassword" class="sr-only">Password</label> 
        <br/>
        <input type="password" value="<?=$_POST['password_2']?>" name="password_2"  class="form-control"
         placeholder="Confirm password" required>  <br/>
        <button name="login_submit" class="btn btn-lg btn-primary " type="submit">Submit</button>
        
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
