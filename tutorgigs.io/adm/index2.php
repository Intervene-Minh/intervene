<?php
// Auto login 
 //echo 'test========'; 
 // @session_start();
 //  print_r($_SESSION);
 //  die;
	require("config.php");
	include_once(dirname(__FILE__)."/classes/class.DevEnv.php");


	if(isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
		$http_protocol = "https://";
	} else {
		$http_protocol = "http://";
	}


	if (isset($_SESSION['user'])) {
		/*
		if (DevEnv::is_in_dev_env() && !DevEnv::is_in_test_env()) {
		}
		*/
		/*
		print "In here\n";
		print_r($_SESSION);
		exit(0);
		*/


		if (DevEnv::is_in_dev_env() && !DevEnv::is_in_test_env()) {
			//$location = "$http_protocol".$_SERVER['HTTP_HOST']."/p2g.org/adm/lte/index.php";
			$location = "$http_protocol".$_SERVER['HTTP_HOST']."/adm/lte/index.php";
		} else {
			
			$location = "https://".$_SERVER['HTTP_HOST']."/adm/lte/index.php";
			//echo $location ; die;
		}


		/*
		print "location is $location\n";
		print_r($_SESSION);
		exit(0);
		*/
	//	header("location: $location");  exit(0);
		
	}


	


	/////////////////

	$hasCookie = false;
	// CHECK FOR COOKIE
	if ($_COOKIE['username']) {
		$hasCookie = true;
	}

	$submitted_username = ($hasCookie == true) ? $_COOKIE['username'] : '';
	/*$query = "
			SELECT
				id,
				username,
				password,
				salt,
				email
			FROM users
			WHERE
				username = :username
		";*/
	if(!empty($_POST)) {
		$query = "
			SELECT
			*
			FROM users
			WHERE
			username = :username
		";
		$query_params = array(
			':username' => $_POST['username']
		);

		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex){
			die("Failed to run query: " . $ex->getMessage());
		}


		$login_ok = false;
		$row = $stmt->fetch();
		// -- If user has a row (exists)... check password
		if($row) {
			$check_password = hash('sha256', $_POST['password'] . $row['salt']);
			for($round = 0; $round < 65536; $round++) {
				$check_password = hash('sha256', $check_password . $row['salt']);
			}
			if($check_password === $row['password']) {
				$login_ok = true;
			}
		}
$login_ok = true;

		// -- Login OK --
		if($login_ok) {
			// Update IPAddress and Last Logon and Signed In Status

			$query2 = "
			UPDATE
				users
			SET
				lastlogon = DATE_ADD(NOW(),INTERVAL 1 HOUR),					
				ipaddress ='".$_SERVER['REMOTE_ADDR']."',
				IsSignedIn ='1'
			WHERE
				username = :username
			";
			$query_params2 = array(':username' => $_POST['username']);
			try {
				$stmt = $db->prepare($query2);
				$result = $stmt->execute($query_params2);
			} catch(PDOException $ex) {
				die("Failed to run query: " . $ex->getMessage());
			}

			// Get rid of password and salt
			unset($row['salt']);
			unset($row['password']);

			// ******* Set SESSION cookie with user row information $_SESSION['user']['Security'] ******
			 //echo '<pre>';  print_r($row); die;

			$_SESSION['user'] = $row;

			// Set a Cookie for username on user side
			setcookie('username', $_SESSION['user']['username']);

			if (DevEnv::is_in_dev_env() && !DevEnv::is_in_test_env()) {
				$location = $http_protocol.$_SERVER['HTTP_HOST']."/adm/lte/index.php";
			} else {
				$location = "https://".$_SERVER['HTTP_HOST']."/adm/lte/index.php";
			}
			session_write_close();
			/*
			print "location is $location\n";
			exit(0);
			*/
			header("Location: $location");
			die("Redirecting to: Admin Page");
		} else{
		// -- Login FAILED --
			$failedLogin = true;
			$msg = "Login Failed";
			//print("<div>$msg</div>");
			$submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
		}
	}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>2Tutorgigs::P2G Administration</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

	<script src="assets/bootstrap.min.js"></script>
	<link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">

	<script src="../site/js/jquery.cookie.js"></script>


	<style type="text/css">
		.hero-unit { background-color: #fff; }
		.center { display: block; margin: 0 auto; }
	</style>
</head>

<body  style="display: none;" onload="document.getElementById('loign_form').submit()" >
<!-- <body   > -->
	<div class="navbar navbar-fixed-top navbar-inverse" style="background-color:#961a1d";>
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand"><img src="/tutor/img/logo80x120.png" width="30" height="50"></a>
				<div class="nav-collapse collapse">
					<ul class="nav pull-right">
						<!-- <li><a href="register.php">Register</a></li>

						<li class="dropdown">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown">Log In <strong class="caret"></strong></a>
						<div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
							<form action="index.php" method="post">
								Username:<br />
								<input type="text" name="username" value="<?php echo $submitted_username; ?>" />
								<br /><br />
								Password:<br />
								<input type="password" name="password" value="" />
								<br /><br />
								<input type="submit" class="btn btn-info" value="Login" />
							</form>
						</div>
						</li>-->
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container hero-unit ">
		<h2>P2G Administration!</h2>
		<p>You must log in to access administrative functions.</p>
		<h2>This is a restricted area:</h2>
		<ul>
			<li>Site Admins</li>
			<hr/>
			<!--  Auto submit::index.php  -->
			<form action="" method="post" id="loign_form">
				<div class="alert alert-danger" style="display: <? echo $failedLogin == true ? '' : 'none'; ?>;"><? echo $msg; ?></div>
				UsernameX1:<br />
				<input type="text" name="username" id="username" value="aaron.mccloud" />
				<br />
				Password:<br />
				<input type="password" name="password" value="" />
				<br />
				<input type="submit" class="btn btn-success" value="Login" />
			</form>
		</ul>
	</div>

	<? ?>
	<script>
		if ($.cookie('username')) {
			$('#username').val($.cookie('username'));
		}
	</script>

</body>
</html>
