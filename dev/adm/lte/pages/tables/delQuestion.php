<?php

	session_start();

    

    //echo 'TestID: '. $_GET['tID'] .'<br/>';

    //echo 'Question: '. $_GET['qID'] .'<br/>';

    

    // db connection

	include 'dbtutor.ini';



	// Connect to tutor database

	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB);

	mysqli_select_db($connection, "$tDB");

    

    // 1. Gather Option IDs

    $query = "SELECT OptionIDs FROM questions WHERE ID=".$_REQUEST['qID'];

    $result = mysqli_query($connection,$query);

    

    $oiArray = mysqli_fetch_array($result);

    //echo 'Option IDs: '. $oiArray['OptionIDs'] . ' <br/>';

    $optionIDs = $oiArray['OptionIDs'];

    $aOptionIDs = explode(",", $optionIDs);

    //echo 'Option 1: '. sizeOf($aOptionIDs) . '<br/>';

 

    // 2. Loop and Delete Options by ID

    $numDeleted = 0;

    for ($j = 0; $j < sizeOf($aOptionIDs); $j++) {

        

        $deleteID = $aOptionIDs[$j];

        // Don't delete generic options (6 of them)

        if ($deleteID > 6) {

            $rs = mysqli_query($connection, "DELETE from answers WHERE ID=".$deleteID);

            if ($rs){

                $numDeleted++;

            }                   

        }

    }

    

    // 3. Delete the Question

    $rs2 = mysqli_query($connection, "DELETE from questions WHERE ID=".$_REQUEST['qID']);

    if ($rs) {

        $msg = "1 Question Deleted. ".$numDeleted." Answers Deleted";

    }

    

    // - Done -

    header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/questions.php?testID=".$_REQUEST['tID']."&msg=".$msg);

    /*

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

			header( "Refresh: 0; URL=http://'.$_SERVER['HTTP_HOST'].'/adm/lte/pages/tables/questions.php?testID=".$_GET['tID']."&msg=Success" );

			

		}

		else {

			header( "Refresh: 0; URL=http://'.$_SERVER['HTTP_HOST'].'/adm/lte/pages/tables/tests.php?msg=eq29: Error updating saving question" );

		}

	} 

	

	// Close db connection

	mysqli_close($connection);

    */



?>