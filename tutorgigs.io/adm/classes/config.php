<?php 

	// These variables define the connection information for your MySQL database 
	include(dirname(__FILE__)."/../db_config.php");
    
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
	try { $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); } 
	catch(PDOException $ex){ die("Failed to connect to the database: " . $ex->getMessage());} 
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
	//header('Content-Type: text/html; charset=utf-8'); 
	session_start(); 
	/*
	// db connection
	$connection = mysqli_connect($host, $username, $password, $dbname) or 
	die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
	mysqli_select_db($connection, "$tDB");


	*/
	$site_name="www.p2g.org/sitenew";
?>
