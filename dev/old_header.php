<?php include('questions/inc/connection.php'); ?>
<?php include('questions/inc/function-html.php'); ?>
<?php
	session_start();
	ob_start();
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php 
        $path  = $_SERVER['REQUEST_URI']; 
        $name_file = str_replace('-',' ',basename($path ,'.php'));
        $name_file = ( $name_file == 'index' || $name_file == '' ) ? 'home' : $name_file;
    ?>
	<title>Less Test Prep | <?php echo ucwords($name_file) ?></title>

	<script type="text/javascript" src="questions/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="questions/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="questions/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="questions/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="questions/js/jquery.bxslider.js"></script>
	<script type="text/javascript" src="questions/js/index-html.js"></script>
	<script type="text/javascript">
		tinymce.init({
			selector: "textarea#question_question, textarea#response_answer",
			theme	: "modern",
			plugins	: [
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor colorpicker textpattern imagetools"
			],
			tools	: "inserttable",
			menubar	: false,
			relative_urls : false,
			toolbar1: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | table bullist numlist indent outdent | link image",
		});
	</script>

	<link type="text/css" href="questions/css/font-awesome.min.css" rel="stylesheet" />
	<link type="text/css" href="questions/css/bootstrap.min.css" rel="stylesheet" />
	<link type="text/css" href="questions/css/jquery-ui.min.css" rel="stylesheet" />
	<link type="text/css" href="questions/css/style.css" rel="stylesheet" />
	<link type="text/css" href="questions/css/form.css" rel="stylesheet" />
	<link type="text/css" href="questions/css/front-end.css" rel="stylesheet" />
	<link type="text/css" href="questions/css/jquery.bxslider.css" rel="stylesheet" />
	<link type="text/css" href="questions/css/index-html.css" rel="stylesheet" />
</head>

<?php 
    $class = "p-body index-html ";
    if(isset($_SESSION['login_user'])){
        $class .= "logged ";
    }else{
        $class .= "no-user-login ";
    }
?>
<body class="<?php echo $class; ?>">

<?php include_once("questions/analyticstracking.php"); ?>

<div id="wrapper" class="clear fullwidth">

	<div id="header" class="clear fullwidth">
		<div class="container">
			<div class="row">
				<div class="logo">
					<h1>Less Test Prep</h1>
					<a href="/" title="LoneStaar">
					<img alt="LessTestPrep" style="height:40px"
					 src="https://intervene.io/questions/images/intervenenew.png" /></a>
				</div>		<!-- /.logo -->
                
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-main"> <!-- button menu mobile --><!-- data-target -->
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php 
                    // get current file name
                    $file_name = basename($_SERVER['PHP_SELF']);
                    $first_part = explode('.', $file_name);
                    // create array file name of menu
                    $directories = array("index", "commitment","comingsoon","teks");
                    // create class active for menu
                    foreach ($directories as $folder){
                        $active[$folder] = ($first_part[0] == $folder)? "current-item-menu":"";
                    }
                ?>
                <div id="main-menu" class="menu main-menu">
                    <ul>
                        <li class="item-menu <?php echo $active['index']; ?> "><a href="index.php">Home</a></li>
                        <li class="item-menu <?php echo $active['commitment']; ?>"><a href="/commitment.php">Our Commitment</a></li>
                        <li class="item-menu <?php echo $active['comingsoon']; ?>"><a href="/comingsoon.php">Demo</a></li>
                        
                    </ul>
                </div>
				<div class="users">
					<?php if(isset($_SESSION['login_user'])):?>
                    <p>
						<a href="questions/profile.php" class="welcome">Welcome <?php echo $_SESSION['login_user'];?>!</a>
						<a href="questions/logout.php" class="links"><span class="glyphicon glyphicon-arrow-right"></span> Logout</a>
					</p>
					<?php else: ?>
                        <form action="" method="post" id="index-login-form" class="index-login-form">
        					<div class="box">
        						<div class="email">
        							<input type="text" placeholder="Email" id="login-email" name="login-email"/>
        						</div>
        						<div class="password">
        							<input type="password" placeholder="Password" id="login-password" name="login-password"/>
        						</div>
        						<button id="login-submit" class="login-submit" name="login-submit" type="submit" >Sign In</button>
                                <p><a href="questions/signup.php">Sign Up Now!</a></p>
        					</div>
                        </form><!-- end index-login-form -->
					<?php endif;?>
				</div>		<!-- /.users -->
				<div class="clearnone">&nbsp;</div>
			</div>
		</div>
	</div>		<!-- /#header -->