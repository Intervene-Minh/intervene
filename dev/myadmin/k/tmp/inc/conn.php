<?php
    $link = @mysqli_connect('localhost', 'mhl397', 'Developer2!');
    mysqli_query($link, 'SET NAMES utf8');
    mysqli_select_db($link,'lonestaar');
    ini_set("date.timezone", "CST6CDT");
?>