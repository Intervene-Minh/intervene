<?php

	session_start();

	$_SESSION['qTestID'] = $_POST['qTestID'];

	

	//die($_POST['qCorrectAnswer']);

	

	// db connection

	include 'dbtutor.ini';



	// Connect to tutor database

	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB);

	mysqli_select_db($connection, "$tDB");	

	

	// Multiple Choice Answers

	if ( isset($_POST['oeOption']) && isset($_POST['oeID']) ) {

		$query = "UPDATE 

				  	answers

				  SET

				  	Answer='".$_POST['oeOption']."'

				  WHERE ID='".$_POST['oeID']."'"

		;

		

		$result = mysqli_query($connection, $query);

		if ($result) {

			header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/questions.php?testID=".$_POST['oeTestID'] );

			

		}

		else {

			header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tests.php?msg=eq29: Error updating saving question" );

		}

	} 

	

	// Close db connection

	mysqli_close($connection);



?>