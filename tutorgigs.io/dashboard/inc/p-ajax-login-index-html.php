<?php

/**
 * @author Phuongpro
 * @copyright 2016
 */

session_start();
ob_start();
include_once("connection.php");

$action = $_POST['action'];

switch($action) {
	case 'login_index':
		login_index();
		break;
}

/* Load list pages of selected book */
function login_index() {
    $return = array();
	if( empty($_POST['email']) || empty($_POST['pass']) ) {
        $return['error'] = "Email or Password is invalid";
	}else{
        $email = $_POST['email'];
        $password = $_POST['pass'];
        
        
		$email = stripslashes($email);
		$password = stripslashes($password);
		$email = mysql_real_escape_string($email);
		$password = mysql_real_escape_string($password);
		$md5password = md5($password);
        
        
		// SQL query to fetch information of registerd users and finds user match.
		$query = mysql_query("SELECT * FROM users WHERE password='$md5password' AND email='$email'");

		$rows = mysql_num_rows($query);
		if ($rows == 1) {
			$row = mysql_fetch_assoc($query);
			$lastest_login = date('Y-m-d H:i:s');
			$query = mysql_query("UPDATE users SET latest_login='$lastest_login' WHERE password='$md5password' AND email='$email'");
			
			$_SESSION['login_id']=$row['id'];
			$_SESSION['login_user']=$row['user_name']; // Initializing Session
			$_SESSION['login_role']=$row['role'];
			
            $return['msg'] = "<p>
        						<a href='questions/profile.php' class='welcome'>Welcome {$_SESSION['login_user']}!</a>
        						<a href='questions/logout.php' class='links'><span class='glyphicon glyphicon-arrow-right'></span> Logout</a>
					       </p>";
            
		} else {
			$return['error'] = "Username or Password is invalid";
		}
	}
    if($return)
        echo json_encode($return);
        
    die();
}

?>