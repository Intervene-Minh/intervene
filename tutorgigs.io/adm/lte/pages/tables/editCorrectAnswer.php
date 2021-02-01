<?php

	session_start();

	$_SESSION['qTestID'] = $_GET['tID'];

	

	//die($_POST['qCorrectAnswer']);

	

	// db connection

	include 'dbtutor.ini';



	// Connect to tutor database

	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB);

	mysqli_select_db($connection, "$tDB");	

	

	// Multiple Choice Answers

	if ( isset($_GET['qID']) && isset($_GET['aID']) ) {

		$query = "UPDATE 

				  	questions

				  SET

				  	AnswerID='".$_GET['aID']."'

				  WHERE ID='".$_GET['qID']."'"

		;

		

		$result = mysqli_query($connection, $query);

		if ($result) {

			header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/questions.php?testID=".$_GET['tID']."&msg=Success" );

			

		}

		else {

			header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tests.php?msg=eq29: Error updating saving question" );

		}

	} 

	

	// Close db connection

	mysqli_close($connection);



?>