<?php 
// SQL connections 

function intervene_db(){
 $con = mysqli_connect("localhost","intervenedevUser",'Te$btu$#4f56#',"intervene_dev");
 return $con;
 }

 /***p2g connection*/

  function p2g(){
  	//return p2g connection
  	 $con2= mysqli_connect("localhost","intervenedevUser",'Te$btu$#4f56#',"ptwogorg_main");
  	 return $con2;
  }
  

?>