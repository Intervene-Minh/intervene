<?php
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/categories.php" ); // must be first line in php
// db connection
include 'dbtutor.ini';
// Delete Category
if ( $_GET['action'] == 'deleteCategory' && isset($_GET['categoryID']) && $_GET['categoryID'] != '0') {
		// Connect to tutor database
		$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
		mysqli_select_db($connection, "$tDB");
			
		$status = mysqli_query($connection, "DELETE FROM categories where ID=".$_GET['categoryID']);	
		if( !$status )
		{
			die('Could not delete category: ' . mysql_error());	
		}	
		mysqli_close($connection);	
}

//  Toggle category active status
if ($_GET['action'] == 'toggleCategory' && isset($_GET['categoryID']) && $_GET['categoryID'] != '0') {
		// Connect to tutor database
		$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
		mysqli_select_db($connection, "$tDB");
		
		$result = mysqli_query($connection, "SELECT * FROM categories WHERE ID=".$_GET['categoryID']." LIMIT 1") or die('Query Error: ' . mysqli_error($connection));	
		
		$numRows = mysqli_num_rows($result);
		$category = mysqli_fetch_array($result);
		
		$newVal = $category['IsActive'] == '0' ? 1 : 0;
		
		$result = mysqli_query($connection, "UPDATE categories SET IsActive='".$newVal."' WHERE ID=".$_GET['categoryID']);
		if( !$result )
		{
			die('Could not change category status: ' . mysql_error());	
		}
		mysqli_close($connection);	
	
}

// ------------------------ AJAX CALLS -----------------------------

// Edit a Category item
if ( $_POST['action'] == 'edit' && isset($_POST['catID']) &&  isset($_POST['catName']) ) {
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");
		
	// Update the row
	$result = mysqli_query($connection, "UPDATE categories SET Name='".$_POST['catName']."' WHERE ID=".$_POST['catID']);
	if( !$result ) {
		die('Could not change category status: ' . mysql_error());	
	}
	mysqli_close($connection);
	
	// Return JSON result
	successResponse( "Category ".$_POST['catName']." was updated successfully! ".time() );
	
}


// ----------------------- UTIL Functions ------------------------
function errorResponse ($message) {
    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(array('message' => $message)));
}

function successResponse ($message) {
	die(json_encode(array('message' => $message)));
}
?>