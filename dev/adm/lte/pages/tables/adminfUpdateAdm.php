<?php

header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/adminmgmt.php" ); // must be first line in php

// db connection

//include 'dbtutor.ini';



/*

echo $_POST['rID'];

echo $_POST['rRule'];

echo $_POST['rValue'];

echo $_POST['rNotes'];

*/

// Update the Admin

if ( !empty($_POST['rID']) && !empty($_POST['rSecurity']) ) {

	

	// Connect to tutor database

		$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or 

			die('Connection Error: ' . mysqli_connect_error() );

		mysqli_select_db($connection, "$tDB");

		

	// Update the row

	$result = mysqli_query($connection, "UPDATE 

										users 

										SET										

										Security='".$_POST['rSecurity']."' 

										WHERE ID=".$_POST['rID']

										) or die(mysqli_error($connection));

	

	if( !$result ) {

		die('Could not change security: ' . mysqli_error($connection));	

	}

	

	mysqli_close($connection);

	

	}



?>