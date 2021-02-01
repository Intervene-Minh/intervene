<?php



 session_start();

ob_start(); 



include("header.php");

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
<div class="container text-center" style="padding:20px;text-align: left;
    margin-left: 265px;">
   <h2>Login</h2>
</div>
<div id="home main" class="clear fullwidth tab-pane fade in active">
   <div class="container">
      <div class="row">
         <div class="align-center col-md-12">
         	<div class="col-md-6">
         		<!-- INTERVEN LOGIN FORM-->
         		<form id="form-login" class="form-login" action="" method="post">
               <div class="title">Login</div>
               <div class="box">
                  <div class="email">
                     <label for="login-email">Role</label>
                     I am a...  
                     <select name="role" class="form-control uname" onchange='SelectRole(this.value);'>
                        <option value="">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="administrator">Administrator</option>
                     </select>
                  </div>
                  <div class="email">
                     <label for="login-email">Username/Email</label>
                     <input type="text" class="required" id="login-email"  name="username"/>
                     <div class="notif">*Please enter your Username Or Email</div>
                  </div>
                  <div class="password">
                     <label for="login-password">Password</label>
                     <input type="password" class="required" id="login-password" placeholder="Password" name="password"/>
                     <div class="notif">*Please enter your password</div>
                  </div>
                  <div class="forgot-password"> <a href="forgot-password.php">Forgot password?</a>
                  </div>
                  <?php echo ($error != "") ? "<div><br /><font color='red'>" . $error . "</font></div>" : ""; ?>
                  <button id="login-submit" class="login-submit" name="login-submit" type="submit" style="width: 76px;float: right;" >Login</button>
              </div>
            </form>
         	</div>
         	<div class="col-md-2" style="margin-top: 209px"> 
         	<img src="or.png" style="width: 37px; margin-left: -54px;position: absolute"></div>
         	<div class="col-md-4">
         		<!-- CLEVER LOGIN FORM-->
         		<div class="forgot-password">
                     <a href="clever/clever-login.php"><img src="loginclever.png" style="height: 498px"></a>
                  </div>
         	</div>
           
         </div>
         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>

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

<?php include("footer_login.php"); ?>

<?php ob_flush(); ?>