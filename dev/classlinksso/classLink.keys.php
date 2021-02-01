<?php
ini_set('display_errors', 1);
session_start();

/*this function use for extract post data*/
extract($_REQUEST);


/*get site URL*/
$siteURL="https://" . $_SERVER['SERVER_NAME'].'/questions/';

/* REDIRECT URL */
$redirect_uri= $siteURL."clever/get_clever_code.php";


/* Smrat Prep Data Dash (DEV) keys */
/*
$client_id="5a6aa7360a0f59b73f9a";
$secret_id="0ce959d86e91562948f5671863d3f86819e01c5f";
$district_id="5b515aa1dc6faa00013d5f46";


*/

/* intervene k12 keys* live keys*/ 


$client_id="ca037b43426b76d8e977";
$secret_id="57d1741a97859bfcdb142b860a887af1ffe54ca8";
$district_id="53ea7128ba7f53280d000032";




/*

$client_id="ca037b43426b76d8e977";
$secret_id="57d1741a97859bfcdb142b860a887af1ffe54ca8";
$district_id="57a26915def8410100000364";

*/
?>

