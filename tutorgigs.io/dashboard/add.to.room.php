<?php
/**

include("header.php");

[ses_teacher_id] => 125
    [ses_curr_state_url] => home.php
    [ses_total_pass] => 1
    [ses_access_website] => yes
    [login_user] => AjayTest
    [login_mail] => imittal@techinventive.com
    [login_role] => 1
     $Tutor_id=(isset($_SESSION['ses_teacher_id']))?$_SESSION['ses_teacher_id']:125;

**/


include("header.php");
include('newrow.functions.php');
$today = date("Y-m-d H:i:s"); // 
 $success_msg=[];
////////////////////////////////////
// echo 'new NewrowToken for admin Creeated!, Reload page again! '; 
  //  $currToken=_get_token(); 
  // echo 'TokenAdmin =='.$currToken;  die; 


 //print_r($_SESSION); die; 

####################################

 if(!isset($_GET['id'])){
 exit('?id=4443, Intervention_id missing');
 }

   if(!isset($_SESSION['ses_teacher_id'])){
    exit('Tutor login failed');
  }



 $Intervention_id=$_GET['id'];  // Launch sessionID
 
 $Tutor_id=$_SESSION['ses_teacher_id'];


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

  echo  $RoomUrlLink;  die; 
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

 //////////////////
 if(!empty($success_msg)){
  echo implode(',' , $success_msg); die; 
 }

 // print_r($success_msg); die; 




?>