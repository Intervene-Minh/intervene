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
		$query = mysql_query("SELECT * FROM gig_admins WHERE password='$md5password' AND email='$email' LIMIT 1", $link);

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


            //khowlett Jira ManagerPortals
            // alllow for sub admins called managers

            if($_SESSION['login_role']>100){

              header("location:./mymanager/index.php");exit;
            }            



/*
            if($row['role']=="104"){  //Basic Test Manager
            	header("location:./mymanager/index.php");exit;
            }   
            elseif ($row['role']=="101"){  //Frog tutoring
            	header("location:./mymanager/index.php");exit;
            }
            elseif ($row['role']=="102"){   // Tutor4U
            	header("location:./mymanager/index.php");exit;
            }
            elseif ($row['role']=="103"){   // Basic Manager
              header("location:./mymanager/index.php");exit;
            }
            else{
            	header("location:./myadmin/index.php");exit;
            }         
	*/		



		} else {
			$error = "Email or Password is invalid";
		}
        }else if($role =="teacher"){
          //$error = "Can Not login now";
          $query = mysql_query("SELECT * FROM gig_teachers WHERE password='$md5password' AND email='$email' LIMIT 1", $link);
           $rows = mysql_num_rows($query);
           if ($rows == 1) {
               $data= mysql_fetch_assoc($query);
			$lastest_login = date('Y-m-d H:i:s');
                     $_SESSION['ses_teacher_id']=$data['id']; // ses_teacher_id# login_id
                   
			$_SESSION['login_user']=(!empty($data['f_name']))?$data['f_name']:$data['email']; // Initializing Session
			$_SESSION['login_mail']=$data['email'];
			$_SESSION['login_role']=1;
                        $error = "Login success..teacher";
                       header("location:./dashboard/index.php");exit; 
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

<div class="container">
      <form class="form-signin" method="POST">
     
      
      <h2 class="form-signin-heading text-primary text-center">Please Login</h2>
       <?php if(isset($error)){ ?>
          <div class="alert alert-danger" role="alert"> <?php echo $error; ?> </div><?php } ?>
      
      
      
      
        <select name="role" class="form-control uname"   >
            <option <?=($_POST['role']=="administrator")?"selected":NULL; ?> value="administrator">Administrator</option>
          <option <?=($_POST['role']=="teacher")?"selected":NULL; ?> value="teacher">Teacher</option>
                                                           
                                                        </select> <br/>
	 
           <input type="email" name="email" value="<?=$_POST['email']?>" class="form-control" placeholder="Email" required>
	
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" value="<?=$_POST['password']?>" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button name="login_submit" class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        
      </form>
</div>

</body>

</html>
