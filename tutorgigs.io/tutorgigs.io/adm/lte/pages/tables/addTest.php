<?php
//header( 'Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/tests.php?msg='.$msg ); // must be first line in php
// db connection
include 'dbtutor.ini';

// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$tDB");	
	
    // 1. Check if subject already has a test
    $hasTest = HasTest($connection, $_POST['tSubjectID']);
    if ( $hasTest == false ) {        
    // 2. Add a test
		$tVerificationCode = md5($email.time()); // encrypted email+timestamp
		$strQuery = "INSERT INTO tests (
											Name,
											SubjectID,						 
											IsActive,
											CreatedBy,
											CreatedOn
										) 
								VALUES (											
											'".$_POST['tName']."',	
											'".$_POST['tSubjectID']."',	
											'".$_POST['tIsActive']."',
											'".$_POST['tCreator']."',	
											DATE_ADD(NOW(),INTERVAL 1 HOUR)			
										)";
		
		$result = mysqli_query($connection,$strQuery) or 
			die("Failed to add test: " . mysqli_errno($connection) . ": " . mysqli_error($connection));
		
		// Close db connection
		mysqli_close($connection);
        $msg = $_POST['tName']." - Test was created";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/adm/lte/pages/tables/tests.php?msg='.$msg); 
        
    } else {        
        $msg = "at38: Error: That Subject already has a test";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/adm/lte/pages/tables/tests.php?msg='.$msg); 
            //die("Redirecting to: Admin Page"); 
    }
    
    
    function HasTest($conn, $sid) {
        $query = "SELECT * FROM tests WHERE SubjectID='".$sid."'";
        $tresult = mysqli_query($conn, $query) or die("46: SubjectID=".$sid."Error: ".mysqli_error($conn));
            
        if (mysqli_num_rows($tresult) > 0) {
            return true;
        } else {
            return false;
        }
    }

?>