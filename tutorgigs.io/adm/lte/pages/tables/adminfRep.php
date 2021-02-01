<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/reps.php" ); // must be first line in php
// db connection
include 'dbtutor.ini';
// Delete Tutor
if ( $_GET['action'] == 'deleteRep' && isset($_GET['repID']) && $_GET['repID'] != '0') {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
	// Change tutors rep to 0 before delete
	$tutors = mysqli_query($connection, "SELECT * FROM tutors where repID=".$_GET['repID']);	
	while ($tutor = mysqli_fetch_array($tutors))
	{
		$result = mysqli_query($connection, "UPDATE tutors SET repID='0' WHERE ID=".$tutor['ID']);
	}
	
	$status = mysqli_query($connection, "DELETE FROM representatives where ID=".$_GET['repID']);	
	if( !$status )
	{
		die('Could not delete tutor: ' . mysql_error());	
	}	
	mysqli_close($connection);	
}
//  Toggle tutor status
if ($_GET['action'] == 'toggleRep' && isset($_GET['repID']) && $_GET['repID'] != '0') {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
	
	$row = mysqli_query($connection, "SELECT * FROM representatives WHERE ID=".$_GET['repID']." LIMIT 1");	
	while ($rep = mysqli_fetch_array($row))
	{
		$newVal = $rep['IsActive'] == '0' ? 1 : 0;
	}
	$result = mysqli_query($connection, "UPDATE representatives SET IsActive='".$newVal."' WHERE ID=".$_GET['repID']);
	if( !$result )
	{
		die('Could not change representative status: ' . mysql_error());	
	}
	mysqli_close($connection);	

}

// ------------------------ AJAX CALLS -----------------------------

// Edit a Rep item
if ( $_POST['action'] == 'edit' && isset($_POST['repID']) ) {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
		
	// Update the row
	$result = mysqli_query($connection, "UPDATE 
											representatives 
										SET 
											FirstName='".$_POST['repFirstName']."', 
											LastName='".$_POST['repLastName']."',
											Notes='".$_POST['repNotes']."'
										WHERE ID=".$_POST['repID']);
	if( !$result ) {
		die('Could not change representative status: ' . mysql_error());	
	}
	mysqli_close($connection);
	
	// Return JSON result
	successResponse( "College ".$_POST['repFirstName']." ".$_POST['repLastName']." was updated successfully! ".time() );
	
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