<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/categories.php" ); // must be first line in php
// db connection
include 'dbtutor.ini';

// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");	
	
		$tVerificationCode = md5($email.time()); // encrypted email+timestamp
		$strQuery = "INSERT INTO categories (
											Name,											 
											IsActive
										) 
								VALUES (
											'".$_POST['cName']."',											
											'".$_POST['cIsActive']."'											
										)";
		
		$result = mysqli_query($connection,$strQuery) or 
			die("Failed to add category: " . mysqli_errno($connection) . ": " . mysqli_error($connection));
		
		// Close db connection
		mysqli_close($connection);

?>