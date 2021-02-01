<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/subjects.php" ); // must be first line in php
// db connection
include 'dbtutor.ini';
// Delete Category
if ( $_GET['action'] == 'deleteSubject' && isset($_GET['subjectID']) && $_GET['subjectID'] != '0') {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
		
	$status = mysqli_query($connection, "DELETE FROM subjects where ID=".$_GET['subjectID']);	
	if( !$status )
	{
		die('Could not delete subject: ' . mysql_error());	
	}	
	mysqli_close($connection);	
}

//  Toggle category active status
if ($_GET['action'] == 'toggleSubject' && isset($_GET['subjectID']) && $_GET['subjectID'] != '0') {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
	
	$result = mysqli_query($connection, "SELECT * FROM subjects WHERE ID=".$_GET['subjectID']." LIMIT 1") or die('Query Error: ' . mysqli_error($connection));	
	
	$numRows = mysqli_num_rows($result);
	$subject = mysqli_fetch_array($result);
	
	$newVal = $subject['IsActive'] == '0' ? 1 : 0;
	
	$result = mysqli_query($connection, "UPDATE subjects SET IsActive='".$newVal."' WHERE ID=".$_GET['subjectID']);
	if( !$result )
	{
		die('Could not change subject status: ' . mysql_error());	
	}
	mysqli_close($connection);	

}

// ------------------------ AJAX CALLS -----------------------------

// Edit a Category item
if ( $_POST['action'] == 'edit' && isset($_POST['subID']) &&  isset($_POST['subName']) ) {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
		
	// Update the row
	$result = mysqli_query($connection, "UPDATE subjects SET Name='".$_POST['subName']."' WHERE ID=".$_POST['subID']);
	if( !$result ) {
		die('Could not change category status: ' . mysql_error());	
	}
	mysqli_close($connection);
	
	// Return JSON result
	successResponse( "Category ".$_POST['subName']." was updated successfully! ".time() );
	
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