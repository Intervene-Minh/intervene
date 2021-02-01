<?php
/****
 $start_date = date('Y-m-d h:i:s');
  # Students :teacher_id school_id grade_level_id class_id created
  @ Student All pending
  1. Pending quiz
  2. pending : assessment
  3. upcoming session > 
    $board_url='session-board.php';
  $actvity_url="actvity.php";
  $page_name='Recent session';
 $no_record='No record found.!';
 * **/
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');

//include("student_header.php");
include('inc/connection.php'); 
session_start();
  ob_start();


if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}

/////////////////////
include("student_inc.php");
include("ses_live_inc.php");# Tutor sesion Live 
   print_r($time_arr); # globals for sessions
#####################
 // page globals 
  $page_name='Recent session';

$json=json_encode($time_arr);

 //echo $json;

///Listing////////////////

?>