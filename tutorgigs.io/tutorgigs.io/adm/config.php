<?php 

	include(dirname(__FILE__)."/general_config.php");
	include(dirname(__FILE__)."/db_config.php");

	// -- Connection info for MySQL database 
    
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 

	try { 
		$db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); 
	} 
	catch(PDOException $ex) { 
		die("Failed to connect to the database: " . $ex->getMessage());
	} 

	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 

	//header('Content-Type: text/html; charset=utf-8'); 

	// -- Start a Session
	session_start(); 
?>
