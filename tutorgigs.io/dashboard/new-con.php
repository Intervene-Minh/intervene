<?php
$servername = "localhost";
$username = "mhl397";
$password = "Developer2!";
$dbname = "lonestaar";

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}else{

	echo 'connect';
}




$sql="SELECT * FROM int_schools_x_sessions_log WHERE 1"; 
      
$result=$db->query($sql);

$row = $result->fetch_assoc();
print_r($row);
die;
?>