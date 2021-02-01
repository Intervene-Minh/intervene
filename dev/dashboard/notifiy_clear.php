<?php 
 @extract($_GET) ;
@extract($_POST) ;

session_start();ob_start();
$home_url="https://tutorgigs.io/"; 
include('inc/connection.php');
$sql=" UPDATE `tutor_profiles` SET `profile_1` = '$state_str',info='Later' WHERE tutorid=".$_SESSION['ses_teacher_id'];

 if(isset($_GET['ac'])&&$_GET['ac']=='clear_all'){
$tutor_id=$_SESSION['ses_teacher_id'];
 //$sql=" UPDATE notifications SET read=1 WHERE type='message' AND receiver_id='$tutor_id' ";
// UPDATE `notifications` SET `read` = '0' WHERE receiver_id=93
 $sql=" UPDATE `notifications` SET `read` = '1' WHERE receiver_id=".$_SESSION['ses_teacher_id'];
 $sql.=" AND type='message' ";
 // echo $sql ; die; 
 $ac=mysql_query($sql); 
////
   header('Location:inbox.php');exit;
 }
 // 1 -1 message notifications
 if(isset($_GET['ac'])&&$_GET['ac']=='jobs'){
$tutor_id=$_SESSION['ses_teacher_id'];
 //$sql=" UPDATE notifications SET read=1 WHERE type='message' AND receiver_id='$tutor_id' ";
// UPDATE `notifications` SET `read` = '0' WHERE receiver_id=93
 $sql=" UPDATE `notifications` SET `read` = '1' WHERE receiver_id=".$_SESSION['ses_teacher_id'];
 $sql.=" AND type='jobs' AND type_id=".$_GET['id'];
  //echo $sql ; die; 
   $ac=mysql_query($sql); 
////
  header('Location:Jobs-Board-List.php?id='.$_GET['id']);exit;
 }

 // Job Changed////
  if(isset($_GET['ac'])&&$_GET['ac']=='job_changed'){
$tutor_id=$_SESSION['ses_teacher_id'];
 //$sql=" UPDATE notifications SET read=1 WHERE type='message' AND receiver_id='$tutor_id' ";
// UPDATE `notifications` SET `read` = '0' WHERE receiver_id=93
 $sql=" UPDATE `notifications` SET `read` = '1' WHERE receiver_id=".$_SESSION['ses_teacher_id'];
 $sql.=" AND type='job_changed' AND type_id=".$_GET['id'];
  //echo $sql ; die; 
    $ac=mysql_query($sql); 
   //echo 'move';  die; 

////
  header('Location:my-sessions.php?id='.$_GET['id']);exit;
 }
 /////////





//////////Validate Site Access//////////
//print_r($_SESSION);
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}
/////////////////////////////////////
  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="Inbox";
//if($login_role!=0 || !isGlobalAdmin()){
//  header("location: index.php");
//}

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

$error='';

?>