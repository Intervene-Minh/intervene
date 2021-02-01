<?php
$link = @mysql_connect('localhost', 'mhl397', 'Developer2!');
mysql_query('SET NAMES utf8');
mysql_select_db('lonestaar', $link);
ini_set("date.timezone", "CST6CDT");
?>