<?php 
// SQL connections 

function intervene_db(){
 $con = mysqli_connect("localhost","mhl397","Developer2!","lonestaar");
 return $con;
 }

 /***p2g connection*/

  function p2g(){
  	//return p2g connection
  	 $con2= mysqli_connect("localhost","ptwogorg_prod","aE&ZidJX)8bl","ptwogorg_main");
  	 return $con2;
  }
  

?>