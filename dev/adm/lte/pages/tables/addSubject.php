<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/subjects.php" ); // must be first line in php
// db connection
include 'dbtutor.ini';

// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");	
	
		$tVerificationCode = md5($email.time()); // encrypted email+timestamp
		$strQuery = "INSERT INTO subjects (
											CategoryID,
											Name,											 
											IsActive
										) 
								VALUES (
											'".$_POST['sCategoryID']."',
											'".$_POST['sName']."',											
											'".$_POST['sIsActive']."'											
										)";
		
		$result = mysqli_query($connection,$strQuery) or 
			die("Failed to add subject: " . mysqli_errno($connection) . ": " . mysqli_error($connection));
		
		// Close db connection
		mysqli_close($connection);

?>