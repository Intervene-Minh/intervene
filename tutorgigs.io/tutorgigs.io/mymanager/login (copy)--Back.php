<?php
//phpinfo();

/* session_start()
ob_start(); */

include("header.php");
        $email = $_SESSION['temp_email'];
        $firstname = $_SESSION['temp_firstname'];
        $lastname = $_SESSION['temp_lastname'];
        $school_mail_name = $_SESSION['temp_school_name'];
        if($_SESSION['temp_email']){
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
                      email: "<?php print $email; ?>",
                      first_name: "<?php print $firstname; ?>",
                      last_name: "<?php print $lastname; ?>",
                      your_school: "<?php print $school_mail_name; ?>",
                      tags: ["Customer Teacher"]
                    }]);

                    </script>
                    <?php
        }
        if( isset($_SESSION['temp_email']) )unset($_SESSION['temp_email']);
        if( isset($_SESSION['temp_firstname']) )unset($_SESSION['temp_firstname']);
        if( isset($_SESSION['temp_lastname']) )unset($_SESSION['temp_lastname']);
        if( isset($_SESSION['temp_school_name']) )unset($_SESSION['temp_school_name']);


if( isset($_POST['login_submit']) ) {
      // print_r($_POST); die;  
        $user_name = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
         // Only Selected Role as Admin 
        
        if($role =="administrator"||$role =="teacher"){
            
            
            
		$email = stripslashes($user_name);
		// $password = stripslashes($password);
		$email = mysql_real_escape_string($email);
		// $password = mysql_real_escape_string($password);
		$md5password = md5($password);
		// Selecting Database
		/*$db = mysql_select_db("company", $connection); selected in header */
		
		// SQL query to fetch information of registerd users and finds user match.
		$query = mysql_query("SELECT * FROM gig_admins WHERE password='$md5password' AND email='$email' LIMIT 1", $link);

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
				<form id="form-login" class="form-login" action="" method="post">

					<div class="title">Login</div>
					<?php 
                                        
                                        
                                        ?>
					<div class="box">
                                            <div class="email">
							<label for="login-email">Role</label>
                                                        I am a...  <select name="role" class="form-control uname" onchange='SelectRole(this.value);'>
                                     <option value="administrator" <?=($_POST['role']=="administrator")?"selected":NULL; ?> >Administrator</option>
                                                   <option value="teacher" <?=($_POST['role']=="teacher")?"selected":NULL; ?> >Teacher</option>
                                                           
                                                        </select>
						</div>
						<div class="email">
							<label for="login-email">Username/Email</label>
                                                        <input type="text" class="required" id="login-email" value="<?=$_POST['username']?>"  name="username"/>
							<div class="notif">*Please enter your Username Or Email</div>
						</div>
						<div class="password">
							<label for="login-password">Password</label>
                                                <input type="password" class="required" id="login-password" value="<?=$_POST['password']?>" placeholder="Password" name="password"/>
							<div class="notif">*Please enter your password</div>
						</div>
                         <p>Are you a demo user? <a style="color: #1e225c;
font-size: 14px;
font-weight: bolder;
text-decoration: underline;" href="demo_login.php">Click here to login to the demo</a></p>
						<div class="forgot-password">
							<a href="forgot-password.php">Forgot password?</a>
						</div>
						<?php echo ($error != "") ? "<div><br /><font color='red'>" . $error . "</font></div>" : ""; ?>
                                            <button id="login-submit" class="login-submit" name="login_submit" type="submit" >Login</button>
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
        if(val == 'teacher' || val == 'administrator'){
          $("#login-email").attr("placeholder", "Email");
        }else{
     $("#login-email").attr("placeholder", "Username"); 
    }
   }
</script>
<?php include("footer.php"); ?>
<?php ob_flush(); ?>