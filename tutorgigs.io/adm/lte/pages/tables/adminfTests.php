<?php
//header( "Refresh: 0; URL=http://'.$_SERVER['HTTP_HOST'].'/adm/lte/pages/tables/tests.php" ); // must be first line in php

require_once("../../../classes/Test.class.php");

// Delete Category
if ( 
        $_GET['action'] == 'deleteTest' && 
        isset($_GET['testID']) && 
        $_GET['testID'] != '0'
    ) {
        $myTest = new Test($_GET['testID']);
        $result = $myTest->Delete($_GET['testID']);
        if (!result) {
            die('Could not delete test: '. $myTest->Name .' - ' . mysql_error());
        }
        $msg = "Successfully Deleted <b>$myTest->Name</b> ".time();
        header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tests.php?msg=".$msg ); 
}

//  Toggle test active status
if (
        $_GET['action'] == 'toggleTest' && 
        isset($_GET['testID']) && 
        $_GET['testID'] != '0'
    ) {
        $myTest = new Test($_GET['testID']);
        if ($myTest->IsActive == 0)
            $myTest->IsActive = 1;
        else
            $myTest->IsActive = 0;
        $result = $myTest->Save();
        if( !$result ) {
		  die('Could not change active status of test:'. $myTest->Name .' - ' . mysql_error());
        }
        $msg = "Successfully Toggled Status <b>$myTest->Name</b> ".time();
        header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tests.php?msg=".$msg );
}

// ------------------------ AJAX CALLS ----------------------------- //

// - Edit Test Name
if ( 
        $_POST['action'] == 'edit' && 
        isset($_POST['testID']) &&  
        isset($_POST['testName']) 
    ) {	
    
    $myTest = new Test($_POST['testID']);
    $myTest->Name = $_POST['testName'];
    $result = $myTest->Save();
    
    // Return JSON result
    if( !$result ) {
		errorResponse('Could not change test status: ' . mysql_error());	
	} else {    	
    	successResponse( "<h4>".$myTest->Name."</h4> was updated successfully! ".time() );
    }	
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