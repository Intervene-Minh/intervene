<?php
//phpinfo();

/* session_start()
ob_start(); */

include("header.php");




if( isset($_POST['login_student']) ) {
    //print_r($_POST); die;  
        $user_name = $_POST['username'];
        $password = $_POST['password'];
        $pass = base64_encode ( $_POST['password'] );
	$student_id = mysql_query("SELECT * FROM `demo_students` WHERE `username` = '$user_name' AND `password` = '$pass' AND `status` = 1 ");
	if( mysql_num_rows($student_id) == 0 ) {
		$error = 'Your information is invalid!';
	} else {
                 $students = mysql_fetch_assoc($student_id);
			$_SESSION['student_id'] = $students['id'];
                        $_SESSION['student_name'] = $students['first_name'];
                        $_SESSION['teacher_id'] = $students['teacher_id'];
                        $_SESSION['schools_id'] = $students['school_id'];
                        $_SESSION['class_id'] = $students['class_id'];
			header("Location: demo_student_assesments.php");
			exit();
		
		} 
			
	}

?>

<script>
	//check fill input
//	$(document).ready(function(){
//		$('#form-login').on('submit', function(){
//			var login = $(this);
//			var valid = true;
//			var filtr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//			
//			$(login).find('.required').each(function(){
//				if( $(this).val() == '' || ( $(this).attr('name') == 'email' && !filtr.test($(this).val()) ) ) {
//					valid = false;
//					$(this).css('border-color', '#FF0000').focus();
//					return false;
//				} else {
//					$(this).css('border-color', '#dddddd');
//				}
//			});
//			
//			return ( valid ) ? true : false;
//		});
//	});
</script>
<script>
	//check fill input
//	$(document).ready(function(){
//		$('#login-submit').on('click',function(){
//			if(!$('#login-password').val()){
//				$('#login-password').parent().addClass('warning');
//			}
//			if(!$('#login-email').val()){
//				$('#login-email').parent().addClass('warning');
//			}
//			var $email = $('#login-email').val();
//			if(!isEmail($email)){
//				$('#login-email').parent().addClass('warning');
//			}
//			var $check  = $('.warning').length;
//			if($check != 0) return false;
//		});
//		$('#form-login input').blur(function(){
//			if( $(this).val().length === 0 ) {
//				$(this).parent().addClass('warning');
//			}
//		});
//		$('#form-login input').focus(function(){
//			$(this).parent().removeClass('warning');
//		}); 
//		
//		function isEmail(email) {
//		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//		  return regex.test(email);
//		}
//	});
</script>


<div class="container text-center" style="padding:20px">


  <h2>Login</h2>
   <a href="student_login.php" class="btn" style="border:2px solid black">Student, Click here to Login Student!</a>
   <a href="/questions/login_principal.php" class="btn" style="border:2px solid black">Administrators! Click here for access</a>
   <a href="/questions/signup.php" class="btn" style="border:2px solid black">Teachers, Click here  to Sign Up!</a>
   <a href="demo_login.php" class="btn" style="border:2px solid black">Demo User, Click here to Login!</a>
   <a href="demo_student_login.php" class="btn" style="border:2px solid black">Demo Student, Click here to Login Student!</a>

</div>
    <div id="home main" class="clear fullwidth tab-pane fade in active">
    	<div class="container">
		<div class="row">
			<div class="align-center col-md-12">
				<form id="form-login" class="form-login" action="" method="post">

					<div class="title">Student Login</div>
					
					<div class="box">
						<div class="email">
							<label for="login-email">User Name</label>
							<input type="text" id="login-email" name="username"/>
							<div class="notif">*Please enter your User Name</div>
						</div>
						<div class="password">
							<label for="login-password">Password</label>
							<input type="password" id="login-password" name="password"/>
							<div class="notif">*Please enter your password</div>
						</div>
						<div class="forgot-password">
							<a href="forgot-password.php">Forgot password?</a>
						</div>
						<?php echo ($error != "") ? "<div><br /><font color='red'>" . $error . "</font></div>" : ""; ?>
						<button name="login_student" type="submit" >Login</button>
					</div>
				</form>
			</div>
			
			
			<div class="clearnone">&nbsp;</div>
		</div>
		
	</div>
		
    </div>
   

<?php include("footer.php"); ?>
<?php ob_flush(); ?>