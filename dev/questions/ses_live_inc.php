<?php

$cur_time= date("Y-m-d H:i:s");  // die;
######################
$no_record='No record found.!';
$time_arr['pendings_url']=$pendings_url='student_pendings.php';
$time_arr['actvity_url'] = $actvity_url="actvity.php";
$time_arr['board_url']=$board_url='student_board.php';

$time=strtotime($cur_time);
$startTime = date("Y-m-d H:i:s", strtotime('-55 minutes', $time));
$endTime = date("Y-m-d H:i:s", strtotime('+55 minutes', $time));
$one_hr_les=date("Y-m-d H:i:s", strtotime('-60 minutes', $time));
///*************

$time_arr=array();
$time_arr['curr_time']=date("Y-m-d H:i:s");
$time_arr['one_hr_les']=$one_hr_les;

$time_arr['time_55_less']=$startTime;
$time_arr['time_55_up']=$endTime;
$time_arr['24_hour_back']=date('Y-m-d H:i:s',strtotime('-24 hours'));
$time_arr['24_hour_next']=date('Y-m-d H:i:s',strtotime('+24 hours'));

$time_arr['actvity_min']=5;
$time_arr['ses_live_duration']=30;# 30 min.at board
$time_arr['ses_quiz_duration']=10;# 10 min.
#$time_arr['ses_total'] =actvity_min+ses_live_duration+quiz_10_min
$time_arr['ses_total_time']=$time_arr['actvity_min']+$time_arr['ses_live_duration']+$time_arr['ses_quiz_duration'];
$page_name='Recent session';
$no_record='No record found.!';
//Global to session page//
$student_school=$student_det['school_id'];
$student_teacher=$teachD;//
$studentId=$_SESSION['student_id'];
?>
