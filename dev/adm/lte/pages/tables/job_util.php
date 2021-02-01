<?php  
	require("config.php");
    if(empty($_SESSION['user']) || (int)$_SESSION['user']['Security'] < 90) 
    {
        header("Location: /adm/index.php");
        die("Redirecting to index.php"); 
    }
	
	// db connection
	include 'dbtutor.ini';
	
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or 
		die('Connection Error: ' . mysqli_connect_error() . ' User:'. $tUser);
	mysqli_select_db($connection, "$tDB");
	
    $_action = "";
    $_job_id = 0;

    if(isset($_GET['action'])) {
        $_action = $_GET['action'];
    }
    
    if(isset($_GET['jobID'])) {
        $_job_id = $_GET['jobID'];
    }

    // Toggle job status
    if($_action == "status") {
        $_sql = "SELECT * FROM jobPosts WHERE ID =".$_job_id;
        $_job = mysqli_query($connection, $_sql);
        if($_job->num_rows > 0) {
            $_job = mysqli_fetch_array($_job);

            $_prev_status = $_job['IsActive'];
            $_new_status = 0;

            if($_prev_status == 0) {
                $_new_status = 1;
            }

            $_sql = "UPDATE jobPosts SET IsActive = ".$_new_status." WHERE ID = ".$_job_id;
            $_job = mysqli_query($connection, $_sql);
        }
    }

    // Delete specific job
    if($_action == "delete") {
        $_sql = "DELETE FROM jobPosts WHERE ID = ".$_job_id;
        $_job = mysqli_query($connection, $_sql);
    }

    header('Location: tutor_jobs.php');
?>