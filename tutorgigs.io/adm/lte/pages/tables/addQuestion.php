<?php
	session_start();
	$_SESSION['qTestID'] = $_POST['qTestID'];
	header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/questions.php" );
	//die($_POST['qCorrectAnswer']);
	
	// db connection
	include 'dbtutor.ini';

	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB);
	mysqli_select_db($connection, "$tDB");	
	
	// Multiple Choice Answers
	if ($_POST['qQuestionType'] == 1) {
		
		// Setup array and loop
		$numOptions = $_POST['qNumOptions'];
		
		// OptionIDs array
		$arrOptions = array();
		
		
		// 1. Iterate through Options and Insert into DB
		for ($k = 0; $k < $numOptions; $k++) {
			$answerOption = "qOption".($k+1);
			$strQuery = "INSERT INTO answers (
												Answer,
												Type,													
												CreatedOn
											) 
									VALUES (											
												'".$_POST[$answerOption]."',	
												'2',
												DATE_ADD(NOW(),INTERVAL 1 HOUR)			
											)
			";
			$result = mysqli_query($connection, $strQuery) or die (mysqli_error($connection));
			$arrOptions[$k] = mysqli_insert_id($connection);
		}
		
		$optIDs = implode(",", $arrOptions);
		$caPos = $_POST['qCorrectAnswer'];
		$correctAnswerID = $arrOptions[$caPos-1];
		
		// 2. Insert the Question and Option IDs and Correct Answer ID
		$strQuery2 = "INSERT INTO questions (
											TestID,
											Question,
											OptionIDs,
											AnswerID,
											IsActive,
											CreatorID,
											CreatedOn
											)
									VALUES (
											'".$_POST['qTestID']."',
											'".$_POST['qQuestion']."',
											'".$optIDs."',
											'".$correctAnswerID."',
											'".$_POST['qIsActive']."',
											'".$_POST['qCreator']."',
											DATE_ADD(NOW(),INTERVAL 1 HOUR)
											)
		";
		
		$result2 = mysqli_query($connection, $strQuery2) or die (mysqli_error($connection));
	} // -- End Multiple Choice
		
	// True / False
	if ($_POST['qQuestionType'] == 2) {
		
		// Setup array and loop
		$numOptions = 2;
		
		// OptionIDs array
		$arrOptions = array(1,2);
		
		$optIDs = implode(",", $arrOptions);
		$caPos = $_POST['qCorrectAnswer'];
		$correctAnswerID = $arrOptions[$caPos-1];
		
		// 2. Insert the Question and Option IDs and Correct Answer ID
		$strQuery2 = "INSERT INTO questions (
											TestID,
											Question,
											OptionIDs,
											AnswerID,
											IsActive,
											CreatorID,
											CreatedOn
											)
									VALUES (
											'".$_POST['qTestID']."',
											'".$_POST['qQuestion']."',
											'".$optIDs."',
											'".$correctAnswerID."',
											'".$_POST['qIsActive']."',
											'".$_POST['qCreator']."',
											DATE_ADD(NOW(),INTERVAL 1 HOUR)
											)
		";
		
		$result2 = mysqli_query($connection, $strQuery2) or die (mysqli_error($connection));
		
		
		
	} // -- End T/F
	
	// Yes / No
	if ($_POST['qQuestionType'] == 3) {
		
		// Setup array and loop
		$numOptions = 2;
		
		// OptionIDs array
		$arrOptions = array(5,6);
		
		$optIDs = implode(",", $arrOptions);
		$caPos = $_POST['qCorrectAnswer'];
		$correctAnswerID = $arrOptions[$caPos-1];
		
		// 2. Insert the Question and Option IDs and Correct Answer ID
		$strQuery2 = "INSERT INTO questions (
											TestID,
											Question,
											OptionIDs,
											AnswerID,
											IsActive,
											CreatorID,
											CreatedOn
											)
									VALUES (
											'".$_POST['qTestID']."',
											'".$_POST['qQuestion']."',
											'".$optIDs."',
											'".$correctAnswerID."',
											'".$_POST['qIsActive']."',
											'".$_POST['qCreator']."',
											DATE_ADD(NOW(),INTERVAL 1 HOUR)
											)
		";
		
		$result2 = mysqli_query($connection, $strQuery2) or die (mysqli_error($connection));
		
		
		
	} // -- End Y/N
	
	// Close db connection
	mysqli_close($connection);

?>