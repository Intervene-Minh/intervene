<?php 
/****
@Default I-frame url->https://smart.newrow.com/room/?gdb-943&fr=lti

 [live_ses_id] => 2720
    [curr_ses_board] => newrow

     [ses_teacher_id] => 125
  ====================================

   [ses_teacher_id] => 125
    [ses_curr_state_url] => home.php
    [ses_total_pass] => 1
    [ses_access_website] => yes
    [login_user] => AjayTest
    [login_mail] => imittal@techinventive.com
    [login_role] => 1
    [live_ses_id] => 2895
    [curr_ses_board] => newrow
$Tutor_id=(isset($_SESSION['ses_tutor_id']))?$_SESSION['ses_tutor_id']:125;
**/



//echo 'Testing ======tutorgigs newrow board  <br/>';

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


// echo 'Tutoring==';
// print_r($_SESSION); die; 

/////////////////For Newrow ROOM/////////////////////
if(isset($_SESSION['curr_ses_board'])&&$_SESSION['curr_ses_board']=='newrow'){
  $newrow_room_url='https://tutorgigs.io/dashboard/tutor_room.php';
  header("Location:".$newrow_room_url); exit; 

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
<?php
/**

include("header.php");
include('libraries/newrow.functions.php');
@Tutor Room
**/



include('newrow.functions.php');

$today = date("Y-m-d H:i:s"); // 
 $success_msg=[];
////////////////////////////////////
// echo 'new NewrowToken for admin Creeated!, Reload page again! '; 
 


if(!isset($_SESSION['live_ses_id'])){
  exit('Launch tutoring again, live_ses_id not found! ');
}

if(!isset($_SESSION['ses_teacher_id'])){
  exit('Tutor logn failed! ');
}
##################################

   $Intervention_id=$_SESSION['live_ses_id'];  //'2892';// Testing
   $Tutor_id=$_SESSION['ses_teacher_id'];

   //print_r($_SESSION); die; 

 ////////////Global Paramenter /////////////////////////////////////


  

  $Room=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_rooms WHERE ses_tutoring_id= '$Intervention_id' ")); #Test
    //print_r($Room); die; 
   $get_newrow_room_id=$Room['newrow_room_id'];  //Intervention_id

   $Tutor_newrow=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_x_tutors WHERE tutor_intervene_id= '$Tutor_id'  "));
   // print_r($Tutor_newrow); die; 
   ///////////Check tutor if aleardy Assgined ////////
   //  echo 'Assgined status==';//$Tutor_id=23;

    $isTutorAdded=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_room_users WHERE ses_tutoring_id= '$Intervention_id' 
      AND intervene_user_id= '$Tutor_id' AND user_type= 'tutor' "));
    // echo '==';
    // print_r($isTutorAdded); die; 

    if(isset($isTutorAdded)&&!empty($isTutorAdded)){
      $canGetRoomAccess='yes';  // then only get ROOM url for-tutor
      $success_msg[]='Tutor aleardy aded to rooom';
     

    }else{
      $canGetRoomAccess='no';
    }


    // var_export($isTutorAddedStatus);


    // die; 

   //////////////////


   $arr_board=[];
   $arr_board['newrow_room_id']=$get_newrow_room_id;
   $arr_board['newrow_tutor_id']=$Tutor_newrow['newrow_ref_id'];



   $arr_board['live_tutoring_id']=$Intervention_id;

  // print_r($arr_board); die; 

   //////Get ROOM Link ///////////////////////////
   if(isset($canGetRoomAccess)&&$canGetRoomAccess=='yes'){

    echo 'Send requust=='; die; 
    $currToken=_get_token(); 
  //echo 'Token==Tutor Launch'.$currToken;  die; 

    // var_export($canGetRoomAccess);

    $n_room_id=$arr_board['newrow_room_id'];  //'24041';
  $n_user_id=$arr_board['newrow_tutor_id']; //'32284';




///////////////////////////

$token=$currToken;  //Get token 

 // $Api_url='https://smart.newrow.com/backend/api/rooms/url/'.$n_room_id.'?user_id='.$n_user_id;

  $Api_url='https://smart.newrow.com/backend/api/rooms/url/'.$n_room_id.'?user_id='.$n_user_id;

  // echo $Api_url; die;
/////////////////////////////


 #$url='https://smart.newrow.com/backend/api/rooms/url/23995';
//setup the request, you can also use CURLOPT_URL
 // 'https://smart.newrow.com/backend/api/rooms/url/23995?user_id=32284'




 $ch = curl_init($Api_url);
// Returns the data/output as a string instead of raw data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Set your auth headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Authorization: Bearer ' . $token
   ));

// get stringified data/output. See CURLOPT_RETURNTRANSFER
$data = curl_exec($ch);
 $Board=json_decode($data);
   //print_r($data); die; 
 

   print_r($Board->data->url); die;


  // if($Board->status=='success'){
  //   $_SESSION['ses_student_launch_url']=$Board->data->url;
  // }
 
 //print_r($data); die; 

// get info about the request
$info = curl_getinfo($ch);
// close curl resource to free up system resources
curl_close($ch);
////////////////////////////////////////



  //  echo 'Get room link for tuotor'; die; 


   }





 //////////////////


 // print_r($success_msg); die; 



/*
?>


<iframe
  allow="microphone *; camera *; speakers *; usermedia *; autoplay*;" 
  allowfullscreen   
  src="<?php echo $Board->data->url ;?>"  height="100%" width="100%">
</iframe> 

<?php 
*/
 //die; 
//////Stop other:: Board///////////////////

?>







<!-- NewrowAPI Board  -->
Access Control- <a href="tutor_board.php?ac=toggle_board"   class="btn btn-success btn-xs">
                              Switch / toggle to Newrow</a> |&nbsp; &nbsp;
                             <!--  <a href="tutor_board_live.php"   class="btn btn-success btn-xs">
                              Media Devices are Unavailable , Click here </a> -->



                              <hr/>
                              <br/>

  <!-- Board newrow -->
  <?php  if($_SESSION['curr_ses_board']=='newrow'){ ?>

<iframe src="https://tutorgigs.io/dashboard/newrow_code.php"  allow="microphone; camera"  height="100%" width="100%">
</iframe>
<?php  }   ?>



<?php if($_SESSION['curr_ses_board']=='groupworld'){  //IMP   ?>
 <?php  include('tutor_board_groupword.php');  ?>
  <?php }?>


