<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tutorsignup.php" ); // must be first line in php
// db connection
include 'dbtutor.ini';
// Delete Tutor
if ( $_GET['action'] == 'delete' && isset($_GET['tutorID']) ) {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
	
	$status = mysqli_query($connection, "DELETE FROM tutors where ID=".$_GET['tutorID']);	
	if( !$status )
	{
		die('Could not delete tutor: ' . mysql_error());	
	}	
	mysqli_close($connection);	
}
//  Toggle tutor status
if ($_GET['action'] == 'toggle' && isset($_GET['tutorID']) ) {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
	
	$row = mysqli_query($connection, "SELECT * FROM tutors WHERE ID=".$_GET['tutorID']." LIMIT 1");	
	while ($tutor = mysqli_fetch_array($row))
	{
		$newVal = $tutor['HasVerifiedEmail'] == '0' ? 1 : 0;
	}
	$result = mysqli_query($connection, "UPDATE tutors SET HasVerifiedEmail='".$newVal."' , VerifiedEmailOn=DATE_ADD(NOW(),INTERVAL 1 HOUR) 
WHERE ID=".$_GET['tutorID']);
	if( !$result )
	{
		die('Could not change tutor status: ' . mysql_error());	
	}
	mysqli_close($connection);	
	//header( 'Location: http://www.p2g.com/tutor/admin.php' );
	
}

?>