<?php
include('../questions/inc/connection.php');
$arr=array('iD'=>'123', 'name'=>'Rohit');

//  if(isset($_GET['id'])){

//  	$getid=$_GET['id'];
// echo 'test result='.$getid; die; 

//  }
////////////
 $json=json_encode($arr);

 echo $json; 
 //die;
?>