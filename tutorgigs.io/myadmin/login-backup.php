<?php
//phpinfo();

/* session_start()
ob_start(); */

include("header.php");

$error=''; // Variable To Store Error Message
if (isset($_POST['login-submit'])) {
	if (empty($_POST['login-email']) || empty($_POST['login-password'])) {
		$error = "Email or Password is invalid";
	}
	else
	{
		// Define $email and $password
		$email=$_POST['login-email'];
		$password=$_POST['login-password'];
		// Establishing Connection with Server by passing server_name, user_id and password as a parameter
		/*$connection = mysql_connect("localhost", "root", ""); connected in header with $connection = $link*/

		// To protect MySQL injection for Security purpose
		$email = stripslashes($email);
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
			
			// Init session first login
			if( $row['email'] == 'demo@p2g.org' )
				$_SESSION['firstlogin'] = true;
			
			header("location: index.php"); // Redirecting To Other Page
		} else {
			$error = "Username or Password is invalid";
		}
		/*mysql_close($connection); // Closing Connection*/
	}
}
?>
<?php

if( isset($_SESSION['schools_id']) ) {
//	header("Location: school.php");
	//exit();
}

if( isset($_POST['submit']) ) {
	$mail = mysql_real_escape_string($_POST['email']);
	$pass = md5($_POST['password']);
	
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
}
?>

<script>
	//check fill input
	$(document).ready(function(){
		$('#form-login').on('submit', function(){
			var login = $(this);
			var valid = true;
			var filtr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
			$(login).find('.required').each(function(){
				if( $(this).val() == '' || ( $(this).attr('name') == 'email' && !filtr.test($(this).val()) ) ) {
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
		$('#login-submit').on('click',function(){
			if(!$('#login-password').val()){
				$('#login-password').parent().addClass('warning');
			}
			if(!$('#login-email').val()){
				$('#login-email').parent().addClass('warning');
			}
			var $email = $('#login-email').val();
			if(!isEmail($email)){
				$('#login-email').parent().addClass('warning');
			}
			var $check  = $('.warning').length;
			if($check != 0) return false;
		});
		$('#form-login input').blur(function(){
			if( $(this).val().length === 0 ) {
				$(this).parent().addClass('warning');
			}
		});
		$('#form-login input').focus(function(){
			$(this).parent().removeClass('warning');
		}); 
		
		function isEmail(email) {
		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  return regex.test(email);
		}
	});
</script>


<div class="container text-center" style="padding:20px">


  <h2>Login</h2>
   <a href="student_login.php" class="btn" style="border:2px solid black">Student, Click here to Login Student!</a>
   <a href="/questions/login_principal.php" class="btn" style="border:2px solid black">Administrators! Click here for access</a>
   <a href="/questions/signup.php" class="btn" style="border:2px solid black">Teachers, Click here to Sign Up!</a>
   <a href="demo_login.php" class="btn" style="border:2px solid black">Demo User, Click here to Login!</a>
   <a href="demo_student_login.php" class="btn" style="border:2px solid black">Demo Student, Click here to Login Student!</a>

</div>
    <div id="home main" class="clear fullwidth tab-pane fade in active">
    	<div class="container">
		<div class="row">
			<div class="align-center col-md-12">
				<?php echo (isset($_GET['registered']) && $_GET['registered'] == 'true') ? "<h4>You have registered successfully. Please login to use the service!</h4>" : ""; ?>
				<form id="form-login" class="form-login" action="login.php" method="post">

					<div class="title">Teacher Login</div>
					
					<div class="box">
						<div class="email">
							<label for="login-email">Email</label>
							<input type="text" id="login-email" name="login-email"/>
							<div class="notif">*Please enter your email</div>
						</div>
						<div class="password">
							<label for="login-password">Password</label>
							<input type="password" id="login-password" name="login-password"/>
							<div class="notif">*Please enter your password</div>
						</div>
						<div class="forgot-password">
							<a href="forgot-password.php">Forgot password?</a>
						</div>
						<?php echo ($error != "") ? "<div><br /><font color='red'>" . $error . "</font></div>" : ""; ?>
						<button id="login-submit" class="login-submit" name="login-submit" type="submit" >Login</button>
					</div>
				</form>
			</div>
			
			
			<div class="clearnone">&nbsp;</div>
		</div>
		
	</div>
		
    </div>
   

<?php include("footer.php"); ?>
<?php ob_flush(); ?>