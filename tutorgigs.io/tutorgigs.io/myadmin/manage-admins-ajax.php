<?php
    include('inc/connection.php'); 
    session_start();
    ob_start();

    if (!isset($_REQUEST["action"])) {
        echo "error"; die;
    }

    $action = $_REQUEST['action'];      
    
    if ($action == "check-dupe-email") {
        
        if (!isset($_POST["email"])) {
            echo "error"; die;
        }
        $email = $_POST['email'];
        $resultDupe = mysql_query("SELECT * FROM `gig_admins` WHERE email = '".$email."';") or die(mysql_error());
        if (mysql_num_rows($resultDupe) > 0) {
            echo "true"; die;            
        } else {
            echo "false, $email, ".mysql_num_rows($resultDupe); die;
        }
    }
    if ($action == "toggle-status") {
        $sql = "INSERT INTO gig_admins 
            SET `status` = '".$_REQUEST["status"]."'
        ";
        $resultCreate = mysql_query($sql) or die(mysql_error());
        if (mysql_num_rows($resultCreate) > 0) {
            echo "true"; die;
        } else {
            echo "false"; die;
        }
    }    
    if ($action == "change-password") {
        $sql = "";
        $resultCreate = mysql_query($sql) or die(mysql_error());
        if (mysql_num_rows($resultCreate) > 0) {
            echo "true"; die;
        } else {
            echo "false"; die;
        }
    }
    if ($action == "get-md5") {
        echo md5($_REQUEST["string"]);
    }
?>