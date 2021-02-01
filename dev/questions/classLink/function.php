<?php

require_once '../inc/connection.php';
require_once('OneRoster.php');
// Create new OneRoster object with the client_id and client_secret

function checkDup($table,$where){

	$numRows=0;
	$str ="SELECT * FROM $table WHERE $where";
	$qr=mysql_query($str);
	$numRows = mysql_num_rows($qr);
	return $numRows;


}

function getfiled($table,$filed,$where){

	$str ="SELECT $filed FROM $table WHERE $where";
	$qr=mysql_query($str);
	$row = mysql_fetch_assoc($qr);
	return $row;


}

function getfiledval($table,$filed,$where){

    $str ="SELECT $filed FROM $table WHERE $where";
    $qr=mysql_query($str);
    $row = mysql_fetch_assoc($qr);
    return $row[$filed];


}

function _getRosterData($Apiurl){

    global  $oneRoster;
    // Make the request to the given url and store the array with status_code and response
    $res = $oneRoster->makeRosterRequest($Apiurl);
    return json_decode($res["response"], true);
}

function pre($ar){

	echo"<pre>";
	print_r($ar);
	echo"</pre>";
}

?>