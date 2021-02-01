<?php
include("header.php");

if( !isset($_SESSION['schools_id']) ) {
	header("Location: login_principal.php");
	exit();
}

/* Process change password */
if( isset($_POST['change_pass']) ) {
	$schoolid = $_SESSION['schools_id'];
	
	// Validate current password
	$curpass = md5($_POST['curpass']);
	$validate = mysql_query("SELECT * 
		FROM  `schools` 
		WHERE  `SchoolId` = $schoolid
		AND  `SchoolPass` =  '$curpass'
		LIMIT 0 , 1
	");
	if( mysql_num_rows($validate) == 0 ) {
		$error = 'Your current password does not match!';
	} else {
		// Update new password
		$newpass = md5($_POST['newpass']);
		$update = mysql_query("UPDATE `schools` SET `SchoolPass` = '$newpass' WHERE `SchoolId` = $schoolid");
		if( $update )
			echo "<script type='text/javascript'>
				alert('Your password has been changed successfully!');
				window.location.href = 'school.php';
			</script>";
		else
			echo "<script type='text/javascript'>
				alert('Can not update your password. Please try again later!');
				window.location.href = 'school.php';
			</script>";
	}
}
?>

<div class="container text-center">
	<h2>Update Administrator Password</h2>
</div>
<div class="container">
	<div class="row">
		<div class="align-center col-md-12">
			<form id="change_principal_password" class="form-login" action="" method="post">
				<div class="box">
					<div>
						<label for="curpass">Current Password</label>
						<input type="password" id="curpass" name="curpass" class="required" />
					</div>
					<?php echo isset($error) ? "<div><font color='red'>" . $error . "</font></div>" : ""; ?>
					<div>
						<p>&nbsp;</p>
						<label for="newpass">New Password</label>
						<input type="password" id="newpass" name="newpass" class="required" />
					</div>
					<div>
						<p>&nbsp;</p>
						<label for="confirm">Confirm Password</label>
						<input type="password" id="confirm" name="confirm" class="required" />
					</div>
					<div id="confirm_error" style="display: none;"><font color='red'>Your new password does not match!</font></div>
					<button id="change_pass" class="login-submit" name="change_pass" type="submit">Change Password</button>
					
					<p>&nbsp;</p>
					<a href='school.php'>Go Back</a>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#change_principal_password').on('submit', function(){
			var valid = true;
			$(this).find('.required').each(function(){
				if( $(this).val() == '' ) {
					$(this).css('border-color', '#FF0000');
					$(this).focus();
					valid = false;
					return false;
				} else {
					$(this).css('border-color', '#dddddd');
				}
			});
			
			if( !valid )
				return false;
			
			if( $('#newpass').val() != $('#confirm').val() ) {
				$('#confirm_error').show();
				$('#confirm').focus();
				valid = false;
				return false;
			} else {
				$('#confirm_error').hide();
			}
		});
	});
</script>

<?php include("footer.php"); ?>