<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/siterules.php" ); // must be first line in php
// db connection
//include 'dbtutor.ini';
require_once("../../../classes/SiteRules.class.php");
/*
echo $_POST['rID'];
echo $_POST['rRule'];
echo $_POST['rValue'];
echo $_POST['rNotes'];
*/
// Update the Site Rule
if ( !empty($_POST['rID']) && !empty($_POST['rValue']) ) {
	
	$siteRules = new SiteRules();
	$result = $siteRules->EditRule($_POST['rID'],$_POST['rRule'],$_POST['rValue'],$_POST['rNotes']);
	
	// Connect to tutor database
	/*	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or 
			die('Connection Error: ' . mysqli_connect_error() );
		mysqli_select_db($connection, "$tDB");
		*/
	// Update the row
	/*$result = mysqli_query($connection, "UPDATE 
										siteRules 
										SET 
										Rule='".$_POST['rRule']."',
										Value='".$_POST['rValue']."',
										Notes='".$_POST['rNotes']."' 
										WHERE ID=".$_POST['rID']
										) or die(mysqli_error($connection));
	*/
	if( !$result ) {
		die('Could not change rule: ' . mysqli_error($siteRules->connection));	
	}
	
	mysqli_close($siteRules->connection);
	
	
}

?>