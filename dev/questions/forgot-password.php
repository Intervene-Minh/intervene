<?php
include("header.php");

$error=''; // Variable To Store Error Message
$success = false;
if (isset($_POST['login-submit'])) {
	if (empty($_POST['login-email'])) {
		$error = "Email is invalid";
	}
	else
	{
		$email=$_POST['login-email'];
		
		$email = stripslashes($email);
		$email = mysql_real_escape_string($email);
		
		$password = substr( md5(rand()), 0, 10);
		$password = mysql_real_escape_string($password);
		$md5password = md5($password);
		
		// SQL query to fetch information of registerd users and finds user match.
		$query = mysql_query("SELECT * FROM users WHERE email='$email' LIMIT 1", $link);
		
		if( isset($_POST['principal']) && $_POST['principal'] == 1 )
			$query = mysql_query("SELECT * FROM `schools` WHERE `SchoolMail` = '$email' LIMIT 1", $link);

		$rows = mysql_num_rows($query);
		if ($rows == 1) {
			$row = mysql_fetch_assoc($query);
			
			if( isset($_POST['principal']) && $_POST['principal'] == 1 ) {
				mysql_query("UPDATE `schools` SET `SchoolPass` = '$md5password' WHERE `SchoolMail` = '$email'", $link);
			} else {
				mysql_query("UPDATE users SET password='$md5password' WHERE email='$email'", $link);
			}
			
			
			$subject = 'Reset Password';
			
			// The message
			$message = "Your new password: $password";
			
			$headers = "From: Intervene <learn@p2g.org>\r\n"; 
			$headers.= "MIME-Version: 1.0\r\n"; 
			$headers.= "Content-Type: text/plain; charset=utf-8\r\n"; 
			$headers.= "X-Priority: 1\r\n"; 
			// Send
			if(mail($email, $subject , $message,$headers)){
				$success = true;
			}else{
				$error = "Email cannot be sent!";
			}
		} else {
			$error = "We can't find that email in our files. Please try again.";
		}
	}
}
?>
<script>
	//check fill input
	$(document).ready(function(){
		$('#login-submit').on('click',function(){
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

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div class="align-center col-md-12">
				<?php if($success){
					echo '<div class="alert alert-success" role="alert">Password has been sent. Please check your email!</div>';
				} ?>
				<form id="form-login" class="form-login" action="" method="post">
					<div class="title">Reset Password</div>
					<div class="box">
						<div class="email">
							<label for="login-email">Email</label>
							<input type="text" id="login-email" name="login-email"/>
							<div class="notif">*Please enter your email address</div>
							<input type="hidden" name="principal" value="<?php echo (isset($_GET['type']) && $_GET['type'] == 'principal' ) ? 1 : 0; ?>" />
						</div>
						<?php echo ($error != "") ? "<div><br /><font color='red'>" . $error . "</font></div>" : ""; ?>
						<button id="login-submit" class="login-submit" name="login-submit" type="submit" >Reset</button>
					</div>
				</form>
			</div>
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>
<?php ob_flush(); ?>