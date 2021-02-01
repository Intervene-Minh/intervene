<?php

/***
// error_reporting(-1);
//     ini_set('display_errors', 1);
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');

**/


 include('inc/connection.php'); 
session_start(); ob_start();


echo  $currTime= date("Y-m-d H:i:s"); #currTime

$start_date = new DateTime($currTime);
$since_start = $start_date->diff(new DateTime('2019-04-29 01:50:00'));
print_r($since_start); die; 
echo $since_start->days.' days total<br>';
echo $since_start->y.' years<br>';
echo $since_start->m.' months<br>';
echo $since_start->d.' days<br>';
echo $since_start->h.' hours<br>';
echo $since_start->i.' minutes<br>';
echo $since_start->s.' seconds<br>';

///////////////////////
// phpinfo();
 die; 


?>