<?php

    require_once 'includes/db_connect.php';
    
    // 1. Check if subject already has a test
    $hasTest = HasTest($connection, $_POST['tName'], $_POST['test_id']);
 
    if ( $hasTest == false ) {        
   
		 $subjects = '';
		if(!empty($_POST['subject']))
                {
                    $subjects = implode(":", $_POST['subject']);
                }
		 $strQuery = "UPDATE `tests` SET Name = '".$connection->real_escape_string($_POST['tName'])."',
                             IsActive = '".$connection->real_escape_string($_POST['tIsActive'])."',
                             SubjectID = '".$subjects."',    
                             PassingMark = '".$connection->real_escape_string($_POST['percent'])."'
                             WHERE ID = '".$_POST['test_id']."'"; 
		
		$result = mysqli_query($connection,$strQuery) or 
			die("Failed to add test: " . mysqli_errno($connection) . ": " . mysqli_error($connection));
		
		// Close db connection
		mysqli_close($connection);
                $_SESSION['success'] = " The test updated successfully";
                header('Location: index.php'); 
        
    } else {        
          $_SESSION['error'] = " The test already existed";
          header('Location: index.php'); 
    }
    
    
    function HasTest($conn, $name, $id) {
        
        $query   = "SELECT * FROM tests WHERE Name='".$name."' AND ID != '".$id."'";
        $tresult = mysqli_query($conn, $query);
            
        if (mysqli_num_rows($tresult) > 0) {
            return true;
        } else {
            return false;
        }
    }

?>