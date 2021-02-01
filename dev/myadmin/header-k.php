<?php include('inc/connection.php'); 
    include('inc/public_inc.php'); 
    session_start();
    ob_start();
        
    //echo 'demo test landing'; die;
    global $base_url;
    $base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
    $home_url="https://tutorgigs.io/"; 
    $login_url="https://tutorgigs.io/login.php"; 
    $admin_home_url="https://tutorgigs.io/myadmin/";
	
	function curPageName() {
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	
	function isGlobalAdmin() {
		$global_admin = array(
			'mehuls85@gmail.com',
			'mhshah2@uh.edu',
			'hx.email.me@gmail.com',
		);
		
		return in_array($_SESSION['login_mail'], $global_admin) ? true : false;
	}	
    /////////////////////////////              
	require_once('inc/check-role.php');
	$role = checkRole();
	
	$pages = array('create-slot.php','view-slots.php','login.php', 'signup.php', 'forgot-password.php', 'login_principal.php', 'school.php', 'student_login.php', 'student_assesments.php', 'demo_login.php','demo_student_login.php');       
    $data_dash_pages = array('assesment_history.php', 'result_dashboard.php', 'manage_classes.php', 'create_class.php', 'manage_assesment.php', 'give_student_access.php');
	if( !( isset($_SESSION['login_user']) && isset($_SESSION['login_id']) 
                && isset($_SESSION['login_role'])  )  ) {
		if( !in_array(curPageName(), $pages) ) {
			header("Location:".$login_url);exit;
			
		}
	} else {
		if( curPageName() == 'login.php' || curPageName() == 'signup.php' ) {
			header("Location: index.php");exit;
			
		}
		
		// Go to admin Dashboard.
		
                
                
                
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="Description" content="We're changing the way teachers teach, one question at a time.">
        <meta name="Keywords" content="measurement worksheet, geometry worksheet, math quiz, STAAR questions, STAAR practice, place value worksheet, free worksheet, free homework, free test generator, freebie, teacher freebie, fractions worksheet, word problems, graphs">
        
        <title>Admin-Tutorgigs.io</title>
        <!-- CSS -->
        <!--<link type="text/css" href="css/font-awesome.min.css" rel="stylesheet" />-->
        <link type="text/css" href="inc/fa5/css/all.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <!--<link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" />-->
        <link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet" />
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link type="text/css" href="css/form.css" rel="stylesheet" />
        <link type="text/css" href="css/front-end.css" rel="stylesheet" />
    </head>
    <body style="<?php echo ($_SESSION['login_role'] > 0) ? "-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;" : ""; ?>">
        <?php include_once("analyticstracking.php"); ?>
        <script type="text/javascript" > window.ZohoHCAsap=window.ZohoHCAsap||function(a,b){ZohoHCAsap[a]=b;};(function(){var d=document; var s=d.createElement("script");s.type="text/javascript";s.defer=true; s.src="https://desk.zoho.com/portal/api/web/inapp/470696000000205756?orgId=706230762"; d.getElementsByTagName("head")[0].appendChild(s); })(); </script>
        <div id="wrapper" class="clear fullwidth">
            <div id="header" class="clear fullwidth">
                <div class="container">
                    <div class="row" style="display: contents;">
                        <div class="logo">                            
                            <a href="../index.html" title="TutorGigs - Less Test Prep"><img alt="TutorGigs" src="images/logo.png" /></a>
                        </div>		<!-- /.logo -->                        
                        <?php 
                            // get current file name
                            $file_name = basename($_SERVER['PHP_SELF']);
                            $first_part = explode('.', $file_name);
                            // create array file name of menu
                            $directories = array("folder");
                            // create class active for menu
                            foreach ($directories as $folder){
                                $active[$folder] = ($first_part[0] == $folder)? "current-item-menu":"";
                            }
                        ?>                                
                        <div id="main-menu" class="menu main-menu">
                            <ul>
                                <li class="item-menu <?php echo $active['folder']; ?> "><a href="<?=isGlobalAdmin() ? $admin_home_url : $home_url ?>">Home</a></li>
                            </ul>
                        </div>
                        <div class="users">
                            <p>
                                <?php 
                                    if ( isset($_SESSION['login_user']) || $_SESSION['student_id'] || $_SESSION['demo_user_id'] ) : 
                                ?>                                                                
                                        <a href="profile.php" class="welcome">
                                            <i class="fa fa-user"></i> <?php if(isset($_SESSION['student_id']) && $_SESSION['student_id'] !=''){echo $_SESSION['student_name']; }elseif($_SESSION['login_user']){ echo $_SESSION['login_user'];}else{ echo $_SESSION['demo_login_user'];}?>!
                                        </a>
                                        <a href="logout.php" class="btn btn-link links">
                                            <span class="glyphicon glyphicon-arrow-right"></span> 
                                            Logout
                                        </a>
                                <?php 
                                    elseif ( !isset($_SESSION['schools_id']) ) : 
                                ?>
                                        <a href="../login.php" class="links no_padding">
                                            Login
                                        </a>
                                    
                                <?php 
                                    endif;
                                ?>
                            </p>
                        </div>		<!-- /.users -->
                        <div class="clearnone">&nbsp;</div>
                    </div>
                </div>
            </div>		
            <!-- /#header -->

