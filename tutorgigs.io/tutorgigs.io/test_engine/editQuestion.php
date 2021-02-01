<?php
	require_once 'includes/db_connect.php';
	
	// Multiple Choice Answers
	if ( isset($_POST['qeQuestion']) && isset($_POST['qeID']) ) {
		$query = "UPDATE questions SET Question='".$_POST['qeQuestion']."'  WHERE ID='".$_POST['qeID']."'"; 
		
		$result = mysqli_query($connection, $query);
		if ($result) {
			header( "Refresh: 0; URL=".BASE_URL."/questions.php?testID=".$_POST['qeTestID'] );
			
		}
		else {
			header( "Refresh: 0; URL=".BASE_URL."/tests.php?msg=eq29: Error updating saving question" );
		}
	} 
	
	// Close db connection
	mysqli_close($connection);

?>