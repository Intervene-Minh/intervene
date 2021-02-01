<?php 
    require("config.php");
     
    // Log DB with vars
    $query2 = "
        UPDATE
            users
        SET
            lastlogout = DATE_ADD(NOW(),INTERVAL 1 HOUR),
            IsSignedIn ='0'
        WHERE 
            username = :username
    ";
    $query_params2 = array(':username' => $_SESSION['user']['username']);
    try { 
        $stmt = $db->prepare($query2); 
        $result = $stmt->execute($query_params2); 
    } 
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
    
    // Relesae session cookie
		unset($_SESSION['tUser']);
		unset($_SESSION['sUser']);
		unset($_SESSION['user']);
		
    
    header("Location: index.php"); 
    die("Redirecting to: index.php");
?>