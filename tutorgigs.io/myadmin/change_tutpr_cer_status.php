<?php

@extract($_REQUEST) ;
include("header.php");

$error='';
$login_role = $_SESSION['login_role'];
////////////////////////
if(isset($_GET['msg'])){
  $_SESSION['msg_success']='newrow id created success!';

 }
 $today = date("Y-m-d H:i:s"); 
 $sql = mysql_query("select * from tutor_profiles where tutorid=".$_GET['tid']);
 $data = mysql_fetch_object($sql);
 
 if($data->approve_certificate == 1)
     $status = 0;
 else 
    $status = 1;

  $sql = mysql_query("update tutor_profiles set approve_certificate='".$status."'  where tutorid=".$_GET['tid']);
  header("Location:tutor-certificate-list.php");
  exit;
?>