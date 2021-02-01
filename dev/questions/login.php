<?php
include('inc/connection.php'); 
session_start();
ob_start(); 


$error='';

if(isset($_SESSION['temp_email'])){ $email = $_SESSION['temp_email'];}

if(isset($_SESSION['temp_firstname'])){$firstname = $_SESSION['temp_firstname'];}

if(isset($_SESSION['temp_lastname'])){$lastname = $_SESSION['temp_lastname'];}

if(isset($_SESSION['temp_school_name'])){$school_mail_name = $_SESSION['temp_school_name'];}



if( isset($_SESSION['temp_email']) )unset($_SESSION['temp_email']);

if( isset($_SESSION['temp_firstname']) )unset($_SESSION['temp_firstname']);

if( isset($_SESSION['temp_lastname']) )unset($_SESSION['temp_lastname']);

if( isset($_SESSION['temp_school_name']) )unset($_SESSION['temp_school_name']);





if( isset($_POST['login-submit']) ) {

    //print_r($_POST); die;  

        $user_name = $_POST['username'];

        $password = $_POST['password'];

        $role = $_POST['role'];

        if($role == 'teacher'){

		$email = stripslashes($user_name);

		// $password = stripslashes($password);

		$email = mysql_real_escape_string($email);

		// $password = mysql_real_escape_string($password);

		$md5password = md5($password);

		// Selecting Database

		/*$db = mysql_select_db("company", $connection); selected in header */

		

		// SQL query to fetch information of registerd users and finds user match.




		$query = mysql_query("SELECT * FROM users WHERE password='$md5password' AND email='$email' LIMIT 1", $link);



		 $rows = mysql_num_rows($query);

		if ($rows == 1) {

			$row = mysql_fetch_assoc($query);

			$lastest_login = date('Y-m-d H:i:s');

			$query = mysql_query("UPDATE users SET latest_login='$lastest_login' WHERE password='$md5password' AND email='$email'", $link);

			

			$_SESSION['login_id']=$row['id'];

			$_SESSION['login_user']=$row['user_name']; // Initializing Session

			$_SESSION['login_mail']=$row['email'];

			$_SESSION['login_role']=$row['role'];

			$_SESSION['login_status']=$row['status'];

			$_SESSION['ses_subdmin']=$row['is_subdmin']; // For multi-sub-admin User..

			

			// Init session first login

			if( $row['email'] == 'demo@p2g.org' )

				$_SESSION['firstlogin'] = true;

			

			header("location: index.php"); // Redirecting To Other Page

		} else {

			$error = "Username or Password is invalid";

		}

        }elseif($role == 'administrator'){

            if( $_SESSION['schools_id'] > 0) { 

	//header("Location: school.php");

            echo "<script type='text/javascript'>window.location.href = 'school.php';</script>";

	exit();

         }

         $mail = mysql_real_escape_string($user_name);

	$pass = md5($password);

	

	$checkmail = mysql_query("SELECT * FROM `schools` WHERE `SchoolMail` = '$mail' AND `status` = 1");

	if( mysql_num_rows($checkmail) == 0 ) {

		$error = 'Your information is invalid!';

	} else {

		$checkpass = mysql_query("SELECT * FROM `schools` WHERE `SchoolMail` = '$mail' AND `SchoolPass` = '$pass' AND `status` = 1");

		if( mysql_num_rows($checkpass) == 0 ) {

			$error = 'Your information is invalid!';

		} else {

			$school = mysql_fetch_assoc($checkpass);

			$_SESSION['schools_id'] = $school['SchoolId'];

			header("Location: school.php");

			exit();

		}

	}

        }else{





$pass = base64_encode ( $password );
//$pass = md5 ( $password );
$student_id = mysql_query("SELECT * FROM `students` WHERE `username` = '$user_name' AND `password` = '$pass' AND `status` = 1 ");

if( mysql_num_rows($student_id) == 0 ) {

$error = 'Your information is invalid!';

} else {

$students = mysql_fetch_assoc($student_id);

$_SESSION['student_id'] = $students['id'];

$studi= $students['id'];

$_SESSION['student_name'] = $students['first_name'];

$_SESSION['last_name'] = $students['last_name'];

$str="SELECT tch.teacher_id FROM `students_x_class` as stu INNER JOIN `class_x_teachers` AS tch ON stu.class_id=tch.class_id WHERE stu.student_id=$studi";

$teachD= mysql_fetch_assoc(mysql_query($str));



$_SESSION['teacher_id'] = $teachD['teacher_id'];

$_SESSION['schools_id'] = $students['school_id'];

//$_SESSION['class_id'] = $students['class_id'];

////////////////////

// header("Location:student_pendings.php"); exit;

header("Location:welcome.php"); exit;

// header("Location:student_assesments.php"); exit;





} 

  }

			

	}
?>

<!DOCTYPE html>
<!-- saved from url=(0030)https://tsubasaworld.com/login -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>Intervene- Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A robust suite of app and landing page templates by Medium Rare">
    <link href="login_assets/css/css.css" rel="stylesheet">
    <link href="login_assets/css/entypo.css" rel="stylesheet" type="text/css" media="all">
    <link href="login_assets/css/theme.css" rel="stylesheet" type="text/css" media="all">
    <link href="login_assets/css/dscountdown.css" rel="stylesheet" type="text/css" media="all">
    <link href="login_assets/css/style.css" rel="stylesheet" type="text/css" media="all">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <script type="text/javascript" src="login_assets/js/jquery_002.js"></script>
    <style>
/*        .login_section .left {max-width:50%!important;flex: 0 0 50% !important;}
        .login_section .right {max-width:50%!important;flex: 0 0 50% !important;}*/
        .notif {
    float: left;
    color: #e60000;
    text-indent: 5px;
    visibility: hidden;
}
       .warning > .notif {
    visibility: visible;
}
    </style>
    
    <script>

	//check fill input

	$(document).ready(function(){

		$('#form-login').on('submit', function(){

			var login = $(this);

			var valid = true;

			var filtr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

			

			$(login).find('.required').each(function(){

				if( $(this).val() == '' ) {

					valid = false;

					$(this).css('border-color', '#FF0000').focus();

					return false;

				} else {

					$(this).css('border-color', '#dddddd');

				}

			});

			

			return ( valid ) ? true : false;

		});

	});

</script>

<script>

	//check fill input

	$(document).ready(function(){

//		$('#login-submit').on('click',function(){
//
//			if(!$('#login-password').val()){
//
//				$('#login-password').parent().addClass('warning');
//
//			}
//
//			if(!$('#login-email').val()){
//
//				$('#login-email').parent().addClass('warning');
//
//			}
//
//			var $email = $('#login-email').val();
//
//			
//
//			var $check  = $('.warning').length;
//
//			if($check != 0) return false;
//
//		});

		$('#form-login input').blur(function(){

			if( $(this).val().length === 0 ) {

				$(this).parent().addClass('warning');

			}

		});

		$('#form-login input').focus(function(){

			$(this).parent().removeClass('warning');

		}); 

		

		

	});

</script>
<style>
    .BadgeLoginButton--largeContainer {
    border-radius: .1875rem;
    box-shadow: 0 0 .25rem #a8aeba;
    position: relative;
}
a.Button--regular, button.Button--regular {
    padding: .75rem 1rem .5rem;
}
a.Button--primary, button.Button--primary {
    background-color: #436cf2;
    border-color: #436cf2 #436cf2 #3158d7;
    color: #fff;
}

.clever_box{width:30%;}
 @media only screen and (max-width: 767px) {
.clever_box{width:60%;}
    }

  .showpass {float: left;padding-left: 11px;}
</style>
  </head>

  <body cz-shortcut-listen="true">

    <div class="navbar-container">
    </div>
    <div class="main-container login_section">
        <div class="right" style="max-width:50% important;margin-top: -2%" >
          <div class="col-12 col-lg-12 align-self-center"> <a href="https://englishpro.us/" style="float:left;border:1px solid #198754; border-radius: 5px;padding: 5px;font-weight: normal;color:#198754">Back to home</a> </div>
          <div class="form_content" style="margin-top:15%">
               
               <div class="col-12 col-lg-12 align-self-center" >
               
                <div class="logo_2" >
                <img src="images/intervenenew.png" alt="" style="max-width: 200px !important">
              </div>
              <div class="text-center">
                <h1>Login to Intervene</h1>
                
               
              </div>
                  <?php echo ($error != "") ? "<div class='alert alert-danger' role='alert' style='text-align:center;background-color:#d9534f;border-color:#d9534f;color:#fff'>" . $error . "</div>" : ""; ?>
                <form  id="form-login" class="form-login mb-4" action="" method="post" >
                 <div class="form-group">
                  <label class="d-none" for="signup-email">Role</label>
                  <select name="role" class="form-control form-control-lg uname" onchange='SelectRole(this.value);'>
                        <option value="">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="administrator">Administrator</option>
                     </select>
               </div>
                <div class="form-group">
                  <label class="d-none" for="signup-email">Username/Email</label>
                  <input class="form-control form-control-lg" type="text" id="login-email"  name="username" placeholder="Enter a Username/Email" required="" />
<!--                 <div class="notif">*Please enter your password</div>-->
               </div>
                <div class="form-group ">
                  <label class="d-none" for="signup-password">Password</label>
                  <input class="form-control form-control-lg" type="password" id="login-password"  name="password" placeholder="Password" required="" />
                   </div>
                   <div class="form-group showpass">
                    <input type="checkbox" onclick="myFunction()" id="showpass"><label for="showpass">Show Password</label>
                     </div>
                
                <div class="bottom_link">
                    <a href="forgot-password.php">Forgot password?</a>
                   
                </div>
                
                <div class="text-center mt-4">
                  <button type="submit" class="login-submit btn btn-lg btn-primary" id="login-submit" name="login-submit">Login</button>
                </div>
              </form>
                
            </div>
          </div>
    </div>
        <div class="left" style="max-width:50% important;">
        <div class="overlay"></div>
        <div class="left_caption">
      
         
       <a href="clever/clever-login.php">
           <img class="BadgeLoginButton--largeImage clever_box"  src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMTY5cHgiIGhlaWdodD0iMjA4cHgiIHZpZXdCb3g9IjAgMCAxNjkgMjA4IiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPgogICAgPCEtLSBHZW5lcmF0b3I6IFNrZXRjaCA1NSAoNzgwNzYpIC0gaHR0cHM6Ly9za2V0Y2hhcHAuY29tIC0tPgogICAgPHRpdGxlPkxhcmdlQmFkZ2U8L3RpdGxlPgogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0iTGFyZ2VCYWRnZSI+CiAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUtMTA2IiBmaWxsPSIjRkZGRkZGIiBmaWxsLXJ1bGU9Im5vbnplcm8iIHg9IjAiIHk9IjAiIHdpZHRoPSIxNjguOTAzNDg0IiBoZWlnaHQ9IjIwOCI+PC9yZWN0PgogICAgICAgICAgICA8ZyBpZD0iY2xldmVyIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgzNy42NjkxMjIsIDI5LjI4MTkzNCkiIGZpbGw9IiM1NTgyRjciIGZpbGwtcnVsZT0ibm9uemVybyI+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTIuNjQzMDI3OCwyNC43ODg0MDY3IEM1LjQ1MDI2NTI2LDI0Ljc4ODQwNjcgMC4xMDgwOTQ0NTgsMTkuNDM2Nzk5MSAwLjEwODA5NDQ1OCwxMi42NzIxNDE0IEwwLjEwODA5NDQ1OCwxMi42MDQ2MTgyIEMwLjEwODA5NDQ1OCw1LjkwNjk1NDgxIDUuMzQ1NjQ1OTIsMC40MjExODIzNjIgMTIuODUyNjMyMywwLjQyMTE4MjM2MiBDMTcuNDYxNTUzMiwwLjQyMTE4MjM2MiAyMC4yMTk4ODI0LDEuOTAyMTA4MjEgMjIuNDg5MzE3Myw0LjA1NTk3MzggTDE5LjA2NzYwNjQsNy44NTkzMDg3MSBDMTcuMTgyMDgwNiw2LjIxMDE5MjAxIDE1LjI2MTgwMzYsNS4yMDA1MTc5MiAxMi44MTc2OTgyLDUuMjAwNTE3OTIgQzguNjk3NDg4NjQsNS4yMDA1MTc5MiA1LjcyOTU1NSw4LjQ5ODc1MTM0IDUuNzI5NTU1LDEyLjUzNzI3MTQgTDUuNzI5NTU1LDEyLjYwNDYxODIgQzUuNzI5NTU1LDE2LjY0MzMxNDYgOC42Mjc2MjA0OCwyMC4wMDkwNzEyIDEyLjgxNzY5ODIsMjAuMDA5MDcxMiBDMTUuNjEwOTYxNSwyMC4wMDkwNzEyIDE3LjMyMTgxNjksMTguOTMyMjI2NSAxOS4yNDIyNzY4LDE3LjI0OTQzNjQgTDIyLjY2Mzk4NzcsMjAuNTgxMTY2OSBDMjAuMTUwMDE0MiwyMy4xNzI2MTA4IDE3LjM1Njc1MSwyNC43ODg0MDY3IDEyLjY0MzAyNzgsMjQuNzg4NDA2NyIgaWQ9IkZpbGwtMSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBvbHlnb24gaWQ9IkZpbGwtMiIgcG9pbnRzPSIyMy4xNTIzMzMyIDAuMTY4MDE0NTYzIDI4LjQ1OTU2OTkgMC4xNjgwMTQ1NjMgMjguNDU5NTY5OSAyNC43MzcxMDMyIDIzLjE1MjMzMzIgMjQuNzM3MTAzMiI+PC9wb2x5Z29uPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTM4LjMwNTQ5MzYsMTAuMTE0MTk0NiBDMzYuMTA1NzQzOSwxMC4xMTQxOTQ2IDM0LjY3NDE3ODMsMTEuNjI4NjE3NiAzNC4yNTUxNTIyLDEzLjk1MTAyNjYgTDQyLjI1MTAzMjgsMTMuOTUxMDI2NiBDNDEuOTM2ODA5LDExLjY2MjQ2NzMgNDAuNTQwMTc3NCwxMC4xMTQxOTQ2IDM4LjMwNTQ5MzYsMTAuMTE0MTk0NiBNNDcuMzQ4ODQ3OSwxNy4xNDg0MTYxIEwzNC4zMjUwMjAzLDE3LjE0ODQxNjEgQzM0Ljg0ODg0ODcsMTkuNDcwNDcyNSAzNi41MjQ3NywyMC42ODIxODcyIDM4Ljg5OTE5MDEsMjAuNjgyMTg3MiBDNDAuNjc5NzMwOCwyMC42ODIxODcyIDQxLjk3MTc0MzEsMjAuMTQzNzY0OSA0My40MzgyNDI4LDE4LjgzMTAyOTkgTDQ2LjQ3NTg2MTcsMjEuNDIyODI2NCBDNDQuNzMwMDcyMiwyMy41MDkzNDUyIDQyLjIxNjA5ODcsMjQuNzg4NDA2NyAzOC44MjkzMjE5LDI0Ljc4ODQwNjcgQzMzLjIwNzY3ODUsMjQuNzg4NDA2NyAyOS4wNTI3MTc3LDIwLjk4NTA3MTggMjkuMDUyNzE3NywxNS40NjU2MjU5IEwyOS4wNTI3MTc3LDE1LjM5ODI3OTEgQzI5LjA1MjcxNzcsMTAuMjQ4ODg4MyAzMi44NTg1MjA2LDYuMDA4MTUxNDEgMzguMzA1NDkzNiw2LjAwODE1MTQxIEM0NC41NTU1ODQ3LDYuMDA4MTUxNDEgNDcuNDE4NzE2MSwxMC42ODY0NjY3IDQ3LjQxODcxNjEsMTUuODAyMTg0IEw0Ny40MTg3MTYxLDE1Ljg2OTUzMDggQzQ3LjQxODcxNjEsMTYuMzc0Mjc5NyA0Ny4zODM3ODIsMTYuNjc3MTY0MyA0Ny4zNDg4NDc5LDE3LjE0ODQxNjEiIGlkPSJGaWxsLTMiPjwvcGF0aD4KICAgICAgICAgICAgICAgIDxwb2x5bGluZSBpZD0iRmlsbC00IiBwb2ludHM9IjU3LjUxMjQ3MDYgMjQuNjk1MzE5OSA1Mi42OTQzMTEgMjQuNjk1MzE5OSA0NS4zMjY2OTUxIDYuNTIxMDEwMTMgNTAuOTQ4MzM4NSA2LjUyMTAxMDEzIDU1LjEzODIzMzQgMTguNjAzNjAyIDU5LjM2MzA2MjMgNi41MjEwMTAxMyA2NC44ODAwODY0IDYuNTIxMDEwMTMgNTcuNTEyNDcwNiAyNC42OTUzMTk5Ij48L3BvbHlsaW5lPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTcxLjc1Njk3OSwxMC4xMTQxOTQ2IEM2OS41NTcyMjk0LDEwLjExNDE5NDYgNjguMTI1NjYzNywxMS42Mjg2MTc2IDY3LjcwNjYzNzYsMTMuOTUxMDI2NiBMNzUuNzAyMzM1MywxMy45NTEwMjY2IEM3NS4zODgyOTQ0LDExLjY2MjQ2NzMgNzMuOTkxNjYyOCwxMC4xMTQxOTQ2IDcxLjc1Njk3OSwxMC4xMTQxOTQ2IE04MC44MDAzMzM0LDE3LjE0ODQxNjEgTDY3Ljc3NjMyMjksMTcuMTQ4NDE2MSBDNjguMzAwMzM0MSwxOS40NzA0NzI1IDY5Ljk3NjI1NTQsMjAuNjgyMTg3MiA3Mi4zNTA0OTI2LDIwLjY4MjE4NzIgQzc0LjEzMTIxNjIsMjAuNjgyMTg3MiA3NS40MjMyMjg1LDIwLjE0Mzc2NDkgNzYuODg5NzI4MywxOC44MzEwMjk5IEw3OS45MjczNDcyLDIxLjQyMjgyNjQgQzc4LjE4MTU1NzYsMjMuNTA5MzQ1MiA3NS42Njc1ODQyLDI0Ljc4ODQwNjcgNzIuMjgwNjI0NSwyNC43ODg0MDY3IEM2Ni42NTkxNjM5LDI0Ljc4ODQwNjcgNjIuNTA0MDIwMiwyMC45ODUwNzE4IDYyLjUwNDAyMDIsMTUuNDY1NjI1OSBMNjIuNTA0MDIwMiwxNS4zOTgyNzkxIEM2Mi41MDQwMjAyLDEwLjI0ODg4ODMgNjYuMzEwMDA2LDYuMDA4MTUxNDEgNzEuNzU2OTc5LDYuMDA4MTUxNDEgQzc4LjAwNzA3MDEsNi4wMDgxNTE0MSA4MC44NzAwMTg2LDEwLjY4NjQ2NjcgODAuODcwMDE4NiwxNS44MDIxODQgTDgwLjg3MDAxODYsMTUuODY5NTMwOCBDODAuODcwMDE4NiwxNi4zNzQyNzk3IDgwLjgzNTI2NzUsMTYuNjc3MTY0MyA4MC44MDAzMzM0LDE3LjE0ODQxNjEiIGlkPSJGaWxsLTUiPjwvcGF0aD4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik05Mi4zMjIzNjEzLDExLjcxMjAwNzggQzg4Ljc5NTY2NTMsMTEuNzEyMDA3OCA4Ni42MzA4NDk3LDEzLjc2NTAyOTQgODYuNjMwODQ5NywxOC4wNzMxMTMyIEw4Ni42MzA4NDk3LDI0LjczNzEwMzIgTDgxLjMyMzc5NTksMjQuNzM3MTAzMiBMODEuMzIzNzk1OSw2LjY5NzMxMDgzIEw4Ni42MzA4NDk3LDYuNjk3MzEwODMgTDg2LjYzMDg0OTcsMTAuMzMyMTAyMyBDODcuNzEzMjU3NSw3Ljg0MTUwMjM0IDg5LjQ1OTA0Nyw2LjIyNjA1OTA3IDkyLjYwMTY1MSw2LjM2MDU3NjUgTDkyLjYwMTY1MSwxMS43MTIwMDc4IEw5Mi4zMjIzNjEzLDExLjcxMjAwNzgiIGlkPSJGaWxsLTYiPjwvcGF0aD4KICAgICAgICAgICAgPC9nPgogICAgICAgICAgICA8ZyBpZD0iR3JvdXAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDQxLjg4NDI1NSwgODcuMzMxOTY5KSI+CiAgICAgICAgICAgICAgICA8cmVjdCBpZD0iUmVjdGFuZ2xlLTEwNyIgc3Ryb2tlPSIjNDI3NEY2IiBzdHJva2Utd2lkdGg9IjYuMDY2NjY2NjciIHg9IjAuMDMzMzMzMzMiIHk9IjAuMDMzMzMzMzMiIHdpZHRoPSIzNS42NTA2Mjc5IiBoZWlnaHQ9IjM0LjE4MTUzMDkiPjwvcmVjdD4KICAgICAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUtMTA3IiBmaWxsPSIjNDI3NEY2IiBmaWxsLXJ1bGU9Im5vbnplcm8iIHg9IjEwLjkwNTc2NDgiIHk9IjEwLjQxNjA2NTgiIHdpZHRoPSIxMy45MDU3NjQ4IiBoZWlnaHQ9IjEzLjQxNjA2NTgiPjwvcmVjdD4KICAgICAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUtMTA3IiBzdHJva2U9IiM0Mjc0RjYiIHN0cm9rZS13aWR0aD0iNi4wNjY2NjY2NyIgeD0iNDguNzAzNTEwMyIgeT0iMC4wMzMzMzMzMyIgd2lkdGg9IjM1LjY1MDYyNzkiIGhlaWdodD0iMzQuMTgxNTMwOSI+PC9yZWN0PgogICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZS0xMDciIGZpbGw9IiM0Mjc0RjYiIGZpbGwtcnVsZT0ibm9uemVybyIgeD0iNTkuNTc1OTQxOCIgeT0iMTAuNDE2MDY1OCIgd2lkdGg9IjEzLjkwNTc2NDgiIGhlaWdodD0iMTMuNDE2MDY1OCI+PC9yZWN0PgogICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZS0xMDciIHN0cm9rZT0iIzQyNzRGNiIgc3Ryb2tlLXdpZHRoPSI2LjA2NjY2NjY3IiB4PSIwLjAzMzMzMzMzIiB5PSI0Ni45ODk1NjM4IiB3aWR0aD0iMzUuNjUwNjI3OSIgaGVpZ2h0PSIzNC4xODE1MzA5Ij48L3JlY3Q+CiAgICAgICAgICAgICAgICA8ZyBpZD0iR3JvdXAtMTAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDQ1LjY3MDE3NywgNDEuNzIwMjE5KSIgZmlsbD0iIzQyNzRGNiIgZmlsbC1ydWxlPSJub256ZXJvIj4KICAgICAgICAgICAgICAgICAgICA8cG9seWdvbiBpZD0iQ29tYmluZWQtU2hhcGUiIHBvaW50cz0iMTYuMjIzMzkyMyAyMC4xMjQwOTg4IDkuMjcwNTA5ODkgMjAuMTI0MDk4OCA5LjI3MDUwOTg5IDQyLjQ4NDIwODUgMCA0Mi40ODQyMDg1IDAgLTkuMjU5ZS0xMyA5LjI3MDUwOTg5IC05LjI1OWUtMTMgMjUuNDkzOTAyMiAtOS4yNTllLTEzIDI1LjQ5MzkwMjIgOC45NDQwNDM5IDMyLjQ0Njc4NDYgOC45NDQwNDM5IDMyLjQ0Njc4NDYgLTkuMjU5ZS0xMyA0MS43MTcyOTQ1IC05LjI1OWUtMTMgNDEuNzE3Mjk0NSAyMC4xMjQwOTg4IDQxLjcxNzI5NDUgMjYuODMyMTMxNyAxNi4yMjMzOTIzIDI2LjgzMjEzMTciPjwvcG9seWdvbj4KICAgICAgICAgICAgICAgICAgICA8cmVjdCBpZD0iUmVjdGFuZ2xlLTExMyIgeD0iMzIuNDQ2Nzg0NiIgeT0iMzMuNTQwMTY0NiIgd2lkdGg9IjkuMjcwNTA5ODkiIGhlaWdodD0iOC45NDQwNDM5Ij48L3JlY3Q+CiAgICAgICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZS0xMTMiIHg9IjE2LjIyMzM5MjMiIHk9IjMzLjU0MDE2NDYiIHdpZHRoPSI5LjI3MDUwOTg5IiBoZWlnaHQ9IjguOTQ0MDQzOSI+PC9yZWN0PgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZS0xMDciIGZpbGw9IiM0Mjc0RjYiIGZpbGwtcnVsZT0ibm9uemVybyIgeD0iMTAuOTA1NzY0OCIgeT0iNTcuMzcyMjk2MyIgd2lkdGg9IjEzLjkwNTc2NDgiIGhlaWdodD0iMTMuNDE2MDY1OCI+PC9yZWN0PgogICAgICAgICAgICA8L2c+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4=" alt="Clever Badge log in">
          </a>
         <div>
             <a href="clever/clever-login.php" class="login-submit btn btn-lg btn-primary clever_box"  id="login-submit" name="login-submit">Login With Clever</a>
          </div>
        </div>
      </div>
      



 <script type="text/javascript" src="login_assets/js/jquery.min.js"></script>
  
    <script type="text/javascript" src="login_assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="login_assets/js/theme.js"></script>

<script type="text/javascript">

  $("#login-email").attr("placeholder", "Username");

     function SelectRole(val){

        if(val == 'teacher' || val == 'administrator'){

          $("#login-email").attr("placeholder", "Email");

        }else{

     $("#login-email").attr("placeholder", "Username"); 

    }

   }



</script>
<script>
function myFunction() {
  var x = document.getElementById("login-password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
</body></html>
