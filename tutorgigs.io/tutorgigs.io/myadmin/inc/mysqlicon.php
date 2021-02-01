<?php
ini_set("date.timezone", "CST6CDT");
$con = new mysqli('localhost', 'intervenedevUser', 'Te$btu$#4f56#','intervene_dev');

if ($con ->connect_errno) {
  echo "Failed to connect to MySQL: " . $con ->connect_error;
  exit();
}
?>
