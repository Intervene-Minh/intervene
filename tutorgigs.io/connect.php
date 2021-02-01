<?php
$connection = mysqli_connect('localhost', 'p2glearn', 'Tutorme15!');
if (!$connection){
die("Database Connection Failed" . mysqli_error($connection));
}
$select_db = mysqli_select_db($connection, 'tutorgigs');
if (!$select_db){
die("Database Selection Failed" . mysqli_error($connection));
}
?>