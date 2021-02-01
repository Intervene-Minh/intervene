<?php

        require_once 'includes/db_connect.php';

	// Multiple Choice Answers

	if ( isset($_POST['oeOption']) && isset($_POST['oeID']) ) {

		$query = "UPDATE answers SET Answer='".$_POST['oeOption']."' WHERE ID='".$_POST['oeID']."'" ;
                $result = mysqli_query($connection, $query);

		if ($result) {

			header( "Refresh: 0; URL=".BASE_URL."/questions.php?testID=".$_POST['oeTestID'] );
                }

		else {

			header( "Refresh: 0; URL=".BASE_URL."/tests.php?msg=eq29: Error updating saving question" );

		}

	} 

	

	// Close db connection

	mysqli_close($connection);



?>