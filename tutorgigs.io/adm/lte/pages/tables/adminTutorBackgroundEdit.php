<?php
	
	require_once("../../../classes/class.TutorBackground.php");
	require_once("../../../classes/class.TutorProfile.php");
	
	
	$profile = new TutorProfile();
	$backgr = new TutorBackground();
	
	if ( !empty($_GET['ID']) && is_int((int)$_GET['value']) ) {
	
		$tutorID = $_GET['ID'];
		$bValue = $_GET['value'];
		
		$profile->select($tutorID);
		$backgr->select($tutorID);
		
		$profile->setBackgroundCheck($bValue);
		$backgr->setStatus($bValue);
		
		$profile->update($tutorID);
		$backgr->update( $backgr->ID );
	
	}
	
	header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tutorbackground.php" );

?>