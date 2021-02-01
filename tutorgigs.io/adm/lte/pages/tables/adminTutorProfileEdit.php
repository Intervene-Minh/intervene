<?php

	require_once("../../../classes/class.Tutor.php");
	require_once("../../../classes/class.TutorProfile.php");
	
	if ( !empty($_GET['action']) && !empty($_GET['ID']) ) {
		
		
		$action = $_GET['action'];
		$tutorID = $_GET['ID'];
		if ( !empty($_GET['redirect']) )
			$redirect = $_GET['redirect'];
		
		// -- Toggle Profile as Approved/Disapproved --
		if ($action == "toggleApproved") {
		
			$profile = new TutorProfile();
			$profile->select( (int)$tutorID);
			
			$profile->setApproved($profile->Approved == 0 ? 1 : 0);
			
			$profile->update((int)$tutorID);
		}
		
		// -- Toggle Profile Email Verified --
		if ($action == "toggleEmailVerified") {
		
			$tutor = new Tutor();
			$tutor->select( (int)$tutorID);
			
			$tutor->setHasVerifiedEmail($tutor->HasVerifiedEmail == 0 ? 1 : 0);
			
			$tutor->update((int)$tutorID);
		}
		
		// -- Toggle Profile Email Verified --
		if ($action == "deletePhoto") {
		
			$profile = new TutorProfile();
			$profile->select( (int)$tutorID);
			
			$profile->ClearPhoto();
		}
		
		// -- Toggle Profile Email Verified --
		if ($action == "disableAccount") {
		
			$profile = new TutorProfile();
			$profile->select( (int)$tutorID);
			
			$profile->setIsActive($profile->IsActive == 0 ? 1 : 0);
			
			$profile->update( (int)$tutorID);
		}
		if($action=="W9"){
			$profile = new TutorProfile();
			$profile->select( (int)$tutorID);
				if($profile->w9==1){
					$profile->w9=0;
				}else{
					$profile->w9=1;
				}
			
			$profile->update( (int)$tutorID);
		
		}
	
		if($action=="Payment"){
			
		
			$profile = new TutorProfile();
			$profile->select( (int)$tutorID);
		
			if($profile->payment==1){
				$profile->payment=0;
			}else{
				$profile->payment=1;
			}

			$profile->update( (int)$tutorID);
		}
	}
	
	if(isset($_SERVER['HTTPS']) &&$_SERVER['HTTPS'] == "on") {
		header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tutorprofiles.php?msg=Updated Tutor Profile" );
	} else {
		header( "Refresh: 0; URL=http://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tutorprofiles.php?msg=Updated Tutor Profile" );
	}

?>
