<?php 
session_start();ob_start();
$home_url="https://tutorgigs.io/"; 
$calendly_url='https://www.google.com/';

// Signup State Global//
 $step_1_url='application.php';
 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //interview
 $step_4_url="application_status.php"; //Quiz and interview status Show or Out from application status

// Signup State Global//

/////////////////

	//$role = checkRole();
	$tutor_regiser_page=(isset($_SESSION['ses_curr_state_url'])&&!empty($_SESSION['ses_curr_state_url']))?$_SESSION['ses_curr_state_url']:"application.php";

	


include('inc/sql_connect.php'); 


  
//////////////p2G Connection/////////////////
//$con_1= @mysql_connect('localhost', 'ptwogorg_prod', 'aE&ZidJX)8bl');
//$pdb=mysql_select_db('ptwogorg_main', $con_1);  //NEW Connections

  
   
 // function intervene_db(){
 // $con = mysqli_connect("localhost","mhl397","Developer2!","lonestaar");
 // return $con;
 // }

  $idb=intervene_db();

    $sql="SELECT *
FROM `gig_teachers`
WHERE `id` = '93'  ";
$result=mysqli_query($idb,$sql);  // Teacher data.

// Associative array
$row=mysqli_fetch_assoc($result);

  print_r($row);

  echo '<br>============p2g data,</br/>';
  //echo '<br>============p2g data,</br/>';


  // function p2g(){
  // 	//return p2g connection
  // 	 $con2= mysqli_connect("localhost","ptwogorg_prod","aE&ZidJX)8bl","ptwogorg_main");
  // 	 return $con2;
  // }

  $p2db=p2g();

 $sql2=" SELECT * FROM `tutor_result_logs` WHERE qn_id=29";
 $get_result=mysqli_query($p2db,$sql2);

 $row2=mysqli_fetch_assoc($get_result);
  print_r($row2);
  


?>