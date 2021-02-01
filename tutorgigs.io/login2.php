<?php

echo '===================TTT';
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
		$query = mysql_query("SELECT * FROM gig_admins WHERE password='$md5password' AND email='$email' LIMIT 1", $link)or die(mysql_error());

		$rows = mysql_num_rows($query);
		if ($rows == 1) {
			$row = mysql_fetch_assoc($query);
			$lastest_login = date('Y-m-d H:i:s');
			//$query = mysql_query("UPDATE users SET latest_login='$lastest_login' WHERE password='$md5password' AND email='$email'", $link);
			
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
			header("location:./myadmin/index.php");exit; 
		} else {
			$error = "Email or Password is invalid";
		}
        }else if($role =="teacher"){
          //$error = "Can Not login now";
          $query = mysql_query("SELECT * FROM gig_teachers WHERE password='$md5password' AND email='$email' LIMIT 1", $link);
           $rows = mysql_num_rows($query);
           if ($rows == 1) {
               $data= mysql_fetch_assoc($query);
                if($data['status']==2){
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
                       header("location:./dashboard/index.php");exit;
                }
			//$_SESSION['login_status']=$row['status'];   
              //1 
           }else $error= "Invalid Email&Password Combination ";
          
        }
        
        
        
        
        
			
	}



?>
<html>
<head>
	<title>User Login Using PHP & MySQL</title>
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

<link rel="stylesheet" href="styles.css" >

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

<div class="container">
      <form class="form-signin" method="POST">
     
      
      <h2 class="form-signin-heading text-primary text-center">Please Login</h2>
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
        <input type="password" value="<?=$_POST['password']?>" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button name="login_submit" class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        
      </form>
</div>

</body>

</html>
