<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/reps.php" ); // must be first line in php
// db connection
include 'dbtutor.ini';

// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or errorResponse('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");	
	
		$tVerificationCode = md5($email.time()); // encrypted email+timestamp
		$strQuery = "INSERT INTO representatives (
											FirstName, 
											LastName, 
											IsActive,
											Notes,											
											CreatedOn
										) 
								VALUES (
											'".$_POST['rFirstName']."',
											'".$_POST['rLastName']."',
											'".$_POST['rIsActive']."',
											'".$_POST['rNotes']."',											
											DATE_ADD(NOW(),INTERVAL 1 HOUR)	
										)";
		
		$result = mysqli_query($connection,$strQuery) or 
			errorResponse("Failed to add representative: " . mysqli_errno($connection) . ": " . mysqli_error($connection));
		
		// Close db connection
		mysqli_close($connection);

?>