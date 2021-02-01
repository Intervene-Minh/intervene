<?php 
/****
@Default I-frame url->https://smart.newrow.com/room/?gdb-943&fr=lti
@ groupworld , groupworld , newrow
include("Tutoring.inc.php"); // 16-sept-2019
@ Iframe refresh file source From( tutor_board_live.php)
 [live_ses_id] => 2720
    [curr_ses_board] => newrow

     [ses_teacher_id] => 125
**/



echo 'Testing ======tutorgigs newrow board  <br/>';

 session_start();
 ob_start();
 $home_url="https://tutorgigs.io/"; 
include('inc/connection.php'); 
include('inc/public_inc.php'); 
require_once('inc/check-role.php');

 //print_r($_SESSION); die;

 $ses_arr=array();

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

/////////////Toggle Board :: //////////////
 if(isset($_GET['ac'])){
  $update_board=''; #Default-- newrow
  $toggle_board='groupworld';# groupworld || newrow
    $current_baord='';
    $curr_ses_id=$_SESSION['live_ses_id'];

    $Tutoring=mysql_fetch_assoc(mysql_query("SELECT *
FROM int_schools_x_sessions_log WHERE  id=".$curr_ses_id));
    
    if($Tutoring['curr_active_board']=='newrow'){
      
    $update_board='groupworld';
    }elseif($Tutoring['curr_active_board']=='groupworld'){
      $update_board='newrow';

    }

    //print_r($Tutoring); die;
    

  ////////////////////

 $up=mysql_query(" UPDATE int_schools_x_sessions_log  SET curr_active_board='$update_board' WHERE id=".$curr_ses_id);

   $_SESSION['curr_ses_board']=$update_board;
   header("Location:".$_SERVER['PHP_SELF'] ); exit; 

 }

 ///////////////////
  //print_r($_SESSION); die; 
// tutor_board_groupword.php

  
?>
Access Control- <a href="tutor_board.php?ac=toggle_board"   class="btn btn-success btn-xs">
                              Switch / toggle to Groupworld</a> |&nbsp; &nbsp;
                              <a href="tutor_board_live.php"   class="btn btn-success btn-xs">
                              Media Devices are Unavailable , Click here </a>



                              <hr/>
                              <br/>

  <!-- Board newrow -->
  <?php  if($_SESSION['curr_ses_board']=='newrow'){
    // 1 static rooom 
    // https://smart.newrow.com/room/?crv-317&fr=lti

   ?>

<iframe src="https://smart.newrow.com/room/?crv-317&fr=lti"  allow="microphone; camera"  height="100%" width="100%">
</iframe>
<?php  }   ?>



<?php if($_SESSION['curr_ses_board']=='groupworld'){  //IMP   ?>
 <?php  include('tutor_board_groupword.php');  ?>
  <?php }?>


