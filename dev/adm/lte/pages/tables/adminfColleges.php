<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/colleges.php" ); // must be first line in php
// db connection
include 'dbtutor.ini';
// Delete Tutor
if ( $_GET['action'] == 'deleteCollege' && isset($_GET['collegeID']) && $_GET['collegeID'] != '0') {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
	/*  Colleges are now saved as text, not by ID
	
	$tutors = mysqli_query($connection, "SELECT * FROM colleges where repID=".$_GET['repID']);	
	while ($tutor = mysqli_fetch_array($tutors))
	{
		$result = mysqli_query($connection, "UPDATE colleges SET collegeID='0' WHERE ID=".$tutor['ID']);
	}
	*/
	
	$status = mysqli_query($connection, "DELETE FROM colleges where ID=".$_GET['collegeID']);	
	if( !$status )
	{
		die('Could not delete college: ' . mysql_error());	
	}	
	mysqli_close($connection);	
}

//  Toggle college active status
if ($_GET['action'] == 'toggleCollege' && isset($_GET['collegeID']) && $_GET['collegeID'] != '0') {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
	
	$result = mysqli_query($connection, "SELECT * FROM colleges WHERE ID=".$_GET['collegeID']." LIMIT 1") or die('Query Error: ' . mysqli_error($connection));	
	$numRows = mysqli_num_rows($result);
	$college = mysqli_fetch_array($result);
	/*
	while ( $college = mysqli_fetch_array($result) or die('Fetch Error for ID: '.$_GET['collegeID'].' - NumRows: '.$numRows) )
	{*/
		$newVal = $college['IsActive'] == '0' ? 1 : 0;/*
	}
	*/
	$result = mysqli_query($connection, "UPDATE colleges SET IsActive='".$newVal."' WHERE ID=".$_GET['collegeID']);
	if( !$result )
	{
		die('Could not change college status: ' . mysql_error());	
	}
	mysqli_close($connection);	

	
}
// ------------------------ AJAX CALLS -----------------------------

// Edit a Category item
if ( $_POST['action'] == 'edit' && isset($_POST['colID']) &&  isset($_POST['colName']) ) {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
		
	// Update the row
	$result = mysqli_query($connection, "UPDATE colleges SET Name='".$_POST['colName']."' WHERE ID=".$_POST['colID']);
	if( !$result ) {
		die('Could not change college status: ' . mysql_error());	
	}
	mysqli_close($connection);
	
	// Return JSON result
	successResponse( "College ".$_POST['colName']." was updated successfully! ".time() );
	
}


// ----------------------- UTIL Functions ------------------------
function errorResponse ($message) {
    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(array('message' => $message)));
}

function successResponse ($message) {
	die(json_encode(array('message' => $message)));
}
?>