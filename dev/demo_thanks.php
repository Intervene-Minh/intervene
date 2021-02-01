<?php require_once('header.php');
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
                               header("location: questions/demo_user_expire.php");
                            } else {
                                header("location: questions/index.php");
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
			header("Location: questions/demo_student_assesments.php");
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
<?php
    $role = $_SESSION['temp_role_id'];
   $email = $_SESSION['temp_email'];
    $first_name = $_SESSION['temp_firstname'];
    $last_name = $_SESSION['temp_lastname'];
   $dist_mail_name =  $_SESSION['temp_dist_name'];
   $role_name = $_SESSION['temp_role_name'];
    $school_mail_name = $_SESSION['temp_school_name'];
   $phone_number = $_SESSION['temp_phone'];
   $smart_prep_mail_name = $_SESSION['temp_smart_preb'];
   $data_dash_mail_name = $_SESSION['temp_data_dash'];


if($role == 1 || $role == 5 || $role == 8)    {  
   //echo $role; die;       
?>
   <script type="text/javascript">
  var _dcq = _dcq || [];
  var _dcs = _dcs || {};
  _dcs.account = '7926835';

      (function() {
    var dc = document.createElement('script');
    dc.type = 'text/javascript'; dc.async = true;
    dc.src = '//tag.getdrip.com/7926835.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(dc, s);
  })();
    _dcq.push(["identify", {
      email: "<?php echo $email; ?>",
      first_name: "<?php echo $first_name; ?>",
      last_name: "<?php echo $last_name; ?>",
      district: "<?php echo $dist_mail_name; ?>",
      title: "<?php echo $role_name; ?>",
      your_school: "<?php echo $school_mail_name; ?>",
      phone_number: "<?php echo $phone_number; ?>",
      smart_preb: "<?php echo $smart_prep_mail_name; ?>",
      data_dash: "<?php echo $data_dash_mail_name; ?>",
      tags: ["Demo Teacher"]
    }]);

    </script>
       <?php } else{ ?>
 <script type="text/javascript">
  var _dcq = _dcq || [];
  var _dcs = _dcs || {};
  _dcs.account = '7926835';

      (function() {
    var dc = document.createElement('script');
    dc.type = 'text/javascript'; dc.async = true;
    dc.src = '//tag.getdrip.com/7926835.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(dc, s);
  })();
    _dcq.push(["identify", {
      email: "<?php echo $email; ?>",
      first_name: "<?php echo $first_name; ?>",
      last_name: "<?php echo $last_name; ?>",
      district: "<?php echo $dist_mail_name; ?>",
      title: "<?php echo $role_name; ?>",
      your_school: "<?php echo $school_mail_name; ?>",
      phone_number: "<?php echo $phone_number; ?>",
      smart_preb: "<?php echo $smart_prep_mail_name; ?>",
      data_dash: "<?php echo $data_dash_mail_name; ?>",
      tags: ["Demo Administrator"]
    }]);

    </script>
      <?php }
      
        if( isset($_SESSION['temp_role_id']) )unset($_SESSION['temp_role_id']);
        if( isset($_SESSION['temp_email']) )unset($_SESSION['temp_email']);
        if( isset($_SESSION['temp_firstname']) )unset($_SESSION['temp_firstname']);
        if( isset($_SESSION['temp_lastname']) )unset($_SESSION['temp_lastname']);
        if( isset($_SESSION['temp_dist_name']) )unset($_SESSION['temp_dist_name']);
        if( isset($_SESSION['temp_role_name']) )unset($_SESSION['temp_role_name']);
        if( isset($_SESSION['temp_school_name']) )unset($_SESSION['temp_school_name']);
        if( isset($_SESSION['temp_phone']) )unset($_SESSION['temp_phone']);
        if( isset($_SESSION['temp_smart_preb']) )unset($_SESSION['temp_smart_preb']);
        if( isset($_SESSION['temp_data_dash']) )unset($_SESSION['temp_data_dash']);
      ?>
<br><br><br><br><br>
<div class="container" style="padding: 20px 15px;">
	<h3>Thanks!</h3>
        You're in! The next step is to check your email for verification.  Let us know if you need any help with the demo.  You can reach us at <a href="mailto:learn@intervene.io">learn@intervene.io</a>.
</div>
<!--

-->

<!--/ Contact-->

<?php require_once('footer.php');?>