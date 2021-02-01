<?php
/**
@ Ref. :: sessions-listing.php
@  upcoming sessions
@ Past sessions::

@newrow launch button
@ tutor board ;
====================
@IDS-229
@ the live room instructor = moderator.
*/

///
 @extract($_GET) ;
@extract($_POST) ;

$ses_time_before=-2700; # 45X60# entire : 5 sec. after ses. end 30 min
       $ses_2hr_before=-7200;

include("header.php");



//echo '=====join() room';  die; 



// move to session//
if(isset($_GET['sesid'])&&$_GET['sesid']>0){
 
  ///////////////////////
  // set session 
  $_SESSION['live_ses_id']=intval($_GET['sesid']);
   $Tutoring=mysql_fetch_assoc(mysql_query("SELECT *
FROM int_schools_x_sessions_log WHERE  id=".$_SESSION['live_ses_id']));

   $_SESSION['curr_ses_board']=$Tutoring['curr_active_board'];

   

//////////Add tutorToRoom//////////////////////////////////////////////
include('newrow.functions.inc.php');

$today = date("Y-m-d H:i:s"); // 
 $success_msg=[];
 $Tutor_id=1280; // Moderator Test ID 


  $Intervention_id=$_GET['sesid'];  // Launch sessionID

////////////////////////////////////
 $Delete_old_log=mysql_query("DELETE FROM launch_ses_log WHERE tutoring_id=".$Intervention_id);

 # add status log For Online tutor :: ses_end_time
  $sql_add_log="INSERT INTO launch_ses_log SET tutoring_id='$Intervention_id',
  tutor_id='$Tutor_id',
  tutoring_end_at='".$Tutoring['ses_end_time']."', 
  tutoring_start_date='".$Tutoring['ses_start_time']."', 
  launch_at='$today' ";
  
  // echo '<pre>' ,
  //  $sql_add_log; 
  $Add=mysql_query($sql_add_log);

   # add status log For Online tutor 



// echo 'new NewrowToken for admin Creeated!, Reload page again! '; 
 $currToken=_get_token(); 
  //echo 'TokenAdmin XXXX=='.$currToken;  die; 



   
 
 


 /////////////////////////////////////////////////

   

   

  
 


  $Room=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_rooms WHERE ses_tutoring_id= '$Intervention_id' ")); #Test
    //print_r($Room); die; 
   $get_newrow_room_id=$Room['newrow_room_id'];  //Intervention_id

   $Tutor_newrow=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_x_tutors WHERE tutor_intervene_id= '$Tutor_id'  "));
   // print_r($Tutor_newrow); die; 
   ///////////Check tutor if aleardy Assgined ////////
   //  echo 'Assgined status==';//$Tutor_id=23;

    $isTutorAdded=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_room_users WHERE ses_tutoring_id= '$Intervention_id' 
      AND intervene_user_id= '$Tutor_id' AND user_type= 'tutor' "));

    if(isset($isTutorAdded)&&!empty($isTutorAdded)){
      $isTutorAddedStatus='yes';
      $success_msg[]='Tutor aleardy aded to rooom';
    }else{
      $isTutorAddedStatus='no';
    }


    // var_export($isTutorAddedStatus);


    // die; 

   //////////////////


   $arr_board=[];
   $arr_board['newrow_room_id']=$get_newrow_room_id;
   $arr_board['newrow_tutor_id']=$Tutor_newrow['newrow_ref_id'];



   $arr_board['live_tutoring_id']=$Intervention_id;

  // print_r($arr_board); die; 



     $sql1="INSERT INTO newrow_room_users SET newrow_user_id='".$arr_board['newrow_tutor_id']."',
  intervene_user_id='$Tutor_id',
  user_type='tutor',
  ses_tutoring_id='$Intervention_id', 
  created_at='$today', 
  tp_id='$Intervention_id', 

  newrow_room_id='$get_newrow_room_id' ";
       //echo $sql; die; 

   ////////////////////

   //print_r($arr_board); die; 
   

  /////////////////////////////////


  if(isset($arr_board['newrow_tutor_id'])&&$isTutorAddedStatus=='no'){

 $success_msg[]='Newrow students ids added to room'.implode(',',$newrow_students_add) ;


  // $var=$_GET['room'];
  $userArr=[];

  // $success_msg[]='2-Student are added to Room-ID:'.$get_newrow_room_id ;
  ###########

 $newrow_tutor_add[]=$arr_board['newrow_tutor_id'];
 

 $post = [
    'enroll_users' =>$newrow_tutor_add,  //array('32284','32287'),
    //'unenroll_users' =>'Custom room by api.',
    
];


//$Testing_room_id=23995;//Ses_room2240.
  $token=$currToken;
  $Testing_room_id=$arr_board['newrow_room_id'];


  /////////////////////////////////////////////



$RoomUrlLink='https://smart.newrow.com/backend/api/rooms/participants/'.$Testing_room_id;

  // echo  $RoomUrlLink;  die; 
$api_url='rooms/participants/<room_id>​';


   $ch = curl_init($RoomUrlLink); // Initialise cURL


       $post = json_encode($post); // Encode the data array into a JSON string
       $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields


       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
       $result = curl_exec($ch); // Execute the cURL statement
       $user_row= json_decode($result); 
       curl_close($ch); // Close the cURL connection


         if(!$result) {
          echo 'No response!';die;
            //return false;
          }



        //print_r($result);exit();

     


          $success_msg[]='Tutor added successfully to room! '.$result;

       //Add  Tutor to Data to intervene system. //
       // $deletOld=mysql_query("DELETE FROM newrow_room_users WHERE user_type='student' AND ses_tutoring_id='$ses_tutoring_id' AND intervene_user_id='$intervene_id' ");

   

    //////////////////////////////////
        $sql="INSERT INTO newrow_room_users SET newrow_user_id='".$arr_board['newrow_tutor_id']."',
  intervene_user_id='$Tutor_id',
  user_type='tutor',
  ses_tutoring_id='$Intervention_id', 
  created_at='$today', 
  tp_id='$Intervention_id', 

  newrow_room_id='$get_newrow_room_id' ";
  

       $Add=mysql_query($sql);



      // return json_decode($result); // Return the received data









 }

 ///////////MESGG///////
 // if(!empty($success_msg)){
 //  echo implode(',' , $success_msg); die; 
 // }

 




###########################################

  //////////////
  header("Location:join_room.php"); exit;
}

 //print_r($_SESSION);



?>