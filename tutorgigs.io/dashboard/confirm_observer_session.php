<?php
include("inc/connection.php");

 $ses_det = mysql_fetch_assoc(mysql_query("SELECT * FROM `int_schools_x_sessions_log` WHERE id=".$_GET['ses_id']));
 
 if($ses_det['observer_confirm'] == 1)
    mysql_query("UPDATE `int_schools_x_sessions_log` SET observer_confirm = 0 WHERE id=".$_GET['ses_id']);
 else
     mysql_query("UPDATE `int_schools_x_sessions_log` SET observer_confirm = 1 WHERE id=".$_GET['ses_id']);
 
?>