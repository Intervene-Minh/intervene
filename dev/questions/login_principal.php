<?php
include("header.php");

if( $_SESSION['schools_id'] > 0) { 
	// header("Location: school.php");
	echo "<script type='text/javascript'>window.location.href = 'school.php';</script>";
	exit();
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
    <div class="container text-center">
            <h2>Administrator Login</h2>
    </div>
    <div id="home main" class="clear fullwidth tab-pane fade in active">
    	<div class="container">
			<div class="row">
				<div class="align-center col-md-12">
					<form id="form-login" class="form-login" action="" method="post">
						<div class="title">Administrator Login</div>
						<div class="box">
							<div class="email">
								<label for="email">Email</label>
								<input type="email" id="email" name="email" class="required" />
							</div>
							<div class="password">
								<label for="password">Password</label>
								<input type="password" id="password" name="password" class="required" />
							</div>
							<div class="forgot-password">
								<a href="forgot-password.php?type=principal">Forgot password?</a>
							</div>
							<?php echo isset($error) ? "<div><br /><font color='red'>" . $error . "</font></div>" : ""; ?>
							<button id="submit" class="login-submit" name="submit" type="submit">Login</button>
						</div>
					</form>
				</div>
			</div>
		</div>
    </div>

<?php include("footer.php"); ?>
<?php ob_flush(); ?>