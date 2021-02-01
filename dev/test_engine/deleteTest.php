<?php

    require_once 'includes/db_connect.php';
    
    if( !empty(@$_GET['testID']))
    {
        $delete = mysqli_query($connection, "DELETE FROM tests WHERE ID = '".$_GET['testID']."'");
        if($delete)
        {
             $_SESSION['success'] = "Test deleted successfully";
             header('Location: index.php'); 
             exit;
        }
        else
        {
            $_SESSION['error'] = "Test deleted failed";
            header('Location: index.php');  
            exit;
        }
    }
   
?>