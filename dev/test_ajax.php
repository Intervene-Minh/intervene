<?php

/***
// error_reporting(-1);
//     ini_set('display_errors', 1);
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');

**/


 include('inc/connection.php'); 
session_start(); ob_start();


echo  $val1 = date("Y-m-d H:i:s"); #currTime

$start_date = new DateTime('2007-09-01 04:10:58');
$since_start = $start_date->diff(new DateTime('2012-09-11 10:25:00'));
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