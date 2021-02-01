<?php

include("header.php");
echo $sql="SELECT feedback_log,about_students FROM int_session_complete 
WHERE sessionid=3062";
$res=mysql_query($sql);
 
 

 $row=mysql_fetch_assoc($res);
 print_r($row);

echo $edit=unserialize($row['feedback_log']);
?>
