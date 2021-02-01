<?php 
    // connection include and session start
    include('inc/conn.php'); 
    session_start();
    ob_start();

    if ( !isset($_SESSION['login_id']) ) {
        header("location: ../login.php");
        exit;
    }

    // configure URLs
    global $base_url;
    $base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
    $home_url="https://tutorgigs.io/"; 
    $login_url="https://tutorgigs.io/login.php";

    // helper functions
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
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>TutorGigs kAdmin</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="style2.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    
    <!-- Font Awesome JS -->
    <script defer src="//use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="//use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img alt="TutorGigs" src="../images/logo.png">
            </div>

            <ul class="list-unstyled components">
                <p><? echo $_SESSION["login_user"] ?></p>
                <li>
                    <a href="#ddSiteAdmin" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Site Admin</a>
                    <ul class="collapse list-unstyled" id="ddSiteAdmin">
                        <li>
                            <a href="sa_admins.php"><i class="fas fa-clipboard-list"></i> Admins</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#ddManageTutors" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Manage Tutors</a>
                    <ul class="collapse list-unstyled" id="ddManageTutors">
                        <li>
                            <a href="index.php"><i class="fas fa-clipboard-list"></i> List of Tutors</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><i class="fas fa-plus"></i> Create a Tutor</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#ddSessions" data-toggle="collapse" 
                        aria-expanded="false" class="dropdown-toggle">Sessions</a>
                    <ul class="collapse list-unstyled" id="ddSessions">
                        <li>
                            <a href="javascript:void(0);">View List</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Calendar</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Cancellations</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Booked/UnBooked</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Jobs Board</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Payment History</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Suspended Log</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#ddMessaging" data-toggle="collapse" 
                        aria-expanded="false" class="dropdown-toggle">Messaging</a>
                    <ul class="collapse list-unstyled" id="ddMessaging">
                        <li>
                            <a href="javascript:void(0);">Message a Tutor</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Broadcast</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Incoming (<span id="msgCount">0</span>)</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);">Test Engine</a>
                </li>
                <li>
                    <a href="javascript:void(0);">Applicants</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="report.zip" class="download">Download Report</a>
                </li>
                <li>
                    <a href="../../logout.php" class="article">Logout</a>
                </li>
            </ul>
        </nav>
        <!-- -->
    
<!-- /#header -->

