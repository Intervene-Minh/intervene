<?php
/**
$link = @mysql_connect('localhost', 'mhl397', 'Developer2!');
mysql_query('SET NAMES utf8');
mysql_select_db('lonestaar', $link);
ini_set("date.timezone", "CST6CDT");


**/

$conn = mysqli_connect("localhost", "mhl397", "Developer2!", "lonestaar");

///////////////////////////

$filename = "toy_csv.csv";
$fp = fopen('php://output', 'w');

$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='phppot_examples' AND TABLE_NAME='toy'";
$result = mysqli_query($conn,$query);
while ($row = mysqli_fetch_row($result)) {
	$header[] = $row[0];
}	

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header);

// $query = "SELECT * FROM toy";
// $result = mysqli_query($conn, $query);
// while($row = mysqli_fetch_row($result)) {
// 	fputcsv($fp, $row);
// }
exit;
?>