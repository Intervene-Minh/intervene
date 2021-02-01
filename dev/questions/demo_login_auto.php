<?php
/**
 * @Demo techer auto login. 
 * 
 * **/

//phpinfo();

/* session_start()
ob_start(); */
include('inc/connection.php'); 
session_start();
	ob_start();
        

//include("header.php");
if(isset($_GET['tid'])){
    //echo $_GET['tid'] , 'login teacher';  die;
    $query = mysql_query("SELECT * FROM demo_users WHERE id=".$_GET['tid'], $link);

		$rows = mysql_num_rows($query);
		if ($rows == 1) {
			$row = mysql_fetch_assoc($query);
			
			$_SESSION['demo_user_id']=$row['id'];
                        $_SESSION['demo_login_user']=$row['first_name'];
			$_SESSION['demo_user_mail']=$row['email'];
			$_SESSION['demo_user_role']='demo_user';
			$_SESSION['login_status']=$row['status'];
                        $_SESSION['data_dash']=$row['data_dash'];
                        $_SESSION['school_id']=$row['school_id'];
                        $_SESSION['demo_login_role'] = $row['role'];
			//$_SESSION['expiry_date']=$row['expiry_date'];
			// Init session first login
//			if( $row['email'] == 'demo@p2g.org' )
//				$_SESSION['firstlogin'] = true;
			;
                        
                           $expire = strtotime($row['expiry_date']);
                            $today = strtotime("today midnight");
                            if($today > $expire){
                                $_SESSION['expired_user']='expired';
                               header("location: demo_user_expire.php");
                            } else {
                                header("location: index.php");
                            }
                             //header("location: index.php");
			 // Redirecting To Other Page
		}
    
}
        
        
 /////////////////////       
        
$error=''; // Variable To Store Error Message
if (isset($_POST['login-submit'])) {
        $username=$_POST['login-email'];
	$password=$_POST['login-password'];
       $role = $_POST['role'];
        if($role == 'teacher'){
	if (empty($_POST['login-email']) || empty($_POST['login-password'])) {
		$error = "Email or Password is invalid";
	}
	else
	{
		
		$email = stripslashes($username);
		// $password = stripslashes($password);
		$email = mysql_real_escape_string($email);
		// $password = mysql_real_escape_string($password);
		$md5password = md5($password);
		// Selecting Database
		/*$db = mysql_select_db("company", $connection); selected in header */
		
		// SQL query to fetch information of registerd users and finds user match.
		$query = mysql_query("SELECT * FROM demo_users WHERE password='$md5password' AND email='$email' LIMIT 1", $link);

		$rows = mysql_num_rows($query);
		if ($rows == 1) {
			$row = mysql_fetch_assoc($query);
			
			$_SESSION['demo_user_id']=$row['id'];
                        $_SESSION['demo_login_user']=$row['first_name'];
			$_SESSION['demo_user_mail']=$row['email'];
			$_SESSION['demo_user_role']='demo_user';
			$_SESSION['login_status']=$row['status'];
                        $_SESSION['data_dash']=$row['data_dash'];
                        $_SESSION['school_id']=$row['school_id'];
                        $_SESSION['demo_login_role'] = $row['role'];
			//$_SESSION['expiry_date']=$row['expiry_date'];
			// Init session first login
//			if( $row['email'] == 'demo@p2g.org' )
//				$_SESSION['firstlogin'] = true;
			;
                        
                           $expire = strtotime($row['expiry_date']);
                            $today = strtotime("today midnight");
                            if($today > $expire){
                                $_SESSION['expired_user']='expired';
                               header("location: demo_user_expire.php");
                            } else {
                                header("location: index.php");
                            }
                             //header("location: index.php");
			 // Redirecting To Other Page
		} else {
			$error = "Username or Password is invalid";
		}
		/*mysql_close($connection); // Closing Connection*/
	}
        }else{
            
        $pass = base64_encode ( $password );
	$student_id = mysql_query("SELECT * FROM `demo_students` WHERE `username` = '$username' AND `password` = '$pass' AND `status` = 1 ");
	if( mysql_num_rows($student_id) == 0 ) {
		$error = 'Your information is invalid!';
	} else {
                 $students = mysql_fetch_assoc($student_id);
			$_SESSION['student_id'] = $students['id'];
                        $_SESSION['student_name'] = $students['first_name'];
                        $_SESSION['teacher_id'] = $students['teacher_id'];
                        $_SESSION['schools_id'] = $students['school_id'];
                        $_SESSION['class_id'] = $students['class_id'];
                        $_SESSION['is_demo_student'] =1; // For demo student
			header("Location: demo_student_assesments.php");
			exit();
		
		}
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
		$('#login-submit').on('click',function(){
			if(!$('#login-password').val()){
				$('#login-password').parent().addClass('warning');
			}
			if(!$('#login-email').val()){
				$('#login-email').parent().addClass('warning');
			}
			var $email = $('#login-email').val();
			
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
		
		
	});
</script>


<div class="container text-center" style="padding:20px">


  <h2>Login</h2>

</div>
    <div id="home main" class="clear fullwidth tab-pane fade in active">
    	<div class="container">
		<div class="row">
			<div class="align-center col-md-12">
				<?php echo (isset($_GET['registered']) && $_GET['registered'] == 'true') ? "<h4>You have registered successfully. Please login to use the service!</h4>" : ""; ?>
				<form id="form-login" class="form-login" action="demo_login.php" method="post">

					<div class="title text-center">Demo User Login</div>
					
					<div class="box">
                                                <div class="email">
							<label for="login-email">Role</label>
                                                        I am a...  <select name="role" class="form-control uname" onchange='SelectRole(this.value);'>
                                                            <option value="">Student</option>
                                                            <option value="teacher">Educator</option>
                                                        </select>
						</div>
						<div class="username-email">
							<label for="login-email">Username/Email</label>
							<input type="text" id="login-email" name="login-email"/>
							<div class="notif">*Please enter your Username Or Email</div>
						</div>
						<div class="password">
							<label for="login-password">Password</label>
							<input type="password" id="login-password" placeholder="Password" name="login-password"/>
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
   <script type="text/javascript">
    $("#login-email").attr("placeholder", "Username");
       function SelectRole(val){
         if(val == 'teacher'){
            $("#login-email").attr("placeholder", "Email");
         }else{
            $("#login-email").attr("placeholder", "Username"); 
         }
       }
</script>

<?php include("footer.php"); ?>
<?php ob_flush(); ?>