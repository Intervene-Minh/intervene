<?php

    require_once 'includes/db_connect.php';
    
    // 1. Check if subject already has a test
    $hasTest = HasTest($connection, $_POST['tName']);
    
    if ( $hasTest == false ) {

   
                $subjects = '';
		if(!empty($_POST['subject']))
                {
                    $subjects = implode(":", $_POST['subject']);
                }
		 $strQuery = "INSERT INTO tests (
											Name,
                                                                                        SubjectID,
											IsActive,
                                                                                        PassingMark,
                                                                                        CreatedBy,
											CreatedOn
										) 
								VALUES (											
											'". $connection->real_escape_string($_POST['tName']) ."',
                                                                                        '". $subjects ."',
											'". $connection->real_escape_string($_POST['tIsActive']) ."',
                                                                                        '".$connection->real_escape_string($_POST['percent'])."',    
                                                                                        '". $connection->real_escape_string($_POST['tCreator'])."',	
											DATE_ADD(NOW(),INTERVAL 1 HOUR)			
										)";
	
		$result = mysqli_query($connection,$strQuery) or 
			die("Failed to add test: " . mysqli_errno($connection) . ": " . mysqli_error($connection));
		
		// Close db connection
		mysqli_close($connection);
                $_SESSION['success'] = "The test created successfully";
                header('Location: index.php'); 
                exit;
        
    } else {        
          $_SESSION['error'] = " The test already existed";
          header('Location: index.php'); 
          exit;
    }
    
    
    function HasTest($conn, $name) {
        
        $query   = "SELECT * FROM tests WHERE Name='".$name."'";
        $tresult = mysqli_query($conn, $query);
            
        if (mysqli_num_rows($tresult) > 0) {
            return true;
        } else {
            return false;
        }
    }

?>