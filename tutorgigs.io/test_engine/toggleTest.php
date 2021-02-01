<?php

    require_once 'includes/db_connect.php';
    
     //  Toggle test active status
    if (
           
            @isset($_GET['testID']) && 
            @$_GET['testID'] != '0'
        ) {
            $myTest = mysqli_fetch_object(mysqli_query($connection, "SELECT * FROM tests where ID = '".$_GET['testID']."'"));
            
            if ($myTest->IsActive == 0)
                $IsActive = 1;
            else
                $IsActive = 0;
          
            $strQuery = "UPDATE `tests` SET 
                             IsActive = '".$connection->real_escape_string($IsActive)."' WHERE ID = '".$_GET['testID']."'"; 
		
            $result = mysqli_query($connection,$strQuery);
            $_SESSION['success']  = "Successfully Toggled Status of <b>$myTest->Name</b> ";
            @header('Location: index.php');  
            exit;
           
    }
   
?>