<?php


$step_2_tutoring_url='create_session_2.php';





/////////////////////////////////////

$tab_sessions='int_schools_x_sessions_log'; # intervenetion and homework_help

$tab_ses_stuents='int_slots_x_student_teacher'; # student list in  intervention

$tab_ses_quiz_answer='students_x_quiz'; # sesion student quiz answer

$client_id='Intervene123456';

 define("TUTOR_BOARD","groupworld");





$error = '';

$author = 1;

$datetm = date('Y-m-d H:i:s');

$today = date("Y-m-d H:i:s"); // 

$msg=array();

$msg_error=array();

$curr_board=TUTOR_BOARD;  ## braincert 






// warning message

$warning_msg=[];

$success_msg=[];



if(isset($_SESSION['ses_admin_token'])&&!empty($_SESSION['ses_admin_token'])){

  $demo='test';

}




 $getToken=_get_token(); 
//echo $getToken; exit;
$_SESSION['ses_admin_token'] = $getToken;


        $Tutoring_id = $first_ses_id;

 $arr_students=[];

 $res_students = mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id = '$Tutoring_id' ");

  while ($line = mysql_fetch_assoc($res_students)) {

    $arr_students[] = $line['student_id'];

    # code...

  }





if(empty($arr_students)){

  exit('No student in session');

}







///////////////////Validate///////////////////////



 if(isset($first_ses_id)){

 

 $Intervention_id = $first_ses_id;

////////////////////////////////

  $Intervention_id = $first_ses_id;

     $room_row=_create_room($Intervention_id);



    if($room_row->status=='error'){ // 

      unset($isCreate);
 
      $warning_msg[]='Room already exist in your company!';# [code] => 11001

      ///Get nwewrow rooom id fron intervene db if room aleardy created 

      // get_newrow_room_id



    }elseif($room_row->status=='success'){

      $isCreate=1;

    $NewrowRoomId=$room_row->data->id;

    $get_newrow_room_id=$NewrowRoomId; //NewroomId

    $roomName='Intervention_room'.$Intervention_id;



    }

 $isCreate=1;

    $NewrowRoomId=$Intervention_id;

    $get_newrow_room_id=$NewrowRoomId; //NewroomId

    $roomName='Intervention_room'.$Intervention_id;




     if($isCreate){ // Saver room ID to intervene system 



  $sql="INSERT INTO newrow_rooms SET newrow_room_id='$get_newrow_room_id',

    ses_tutoring_id='$Intervention_id',name='$roomName',description='$roomName',

    tp_id='$Intervention_id',created_at='$today' ";

    $Save=mysql_query($sql)or die($sql);

    if($Save){

      $success_msg[] ='<tr><td>Created Room ID</td><td>'.$get_newrow_room_id.'</td></tr>';

    }

    }

  $Tutoring_students_arr = $arr_students; // All students Newrow ids. 

    $arr_student_nid=[];

     foreach ($Tutoring_students_arr as $studentId) {

       $temp_student_id = $studentId;

      $Student = mysql_fetch_assoc(mysql_query("SELECT * FROM newrow_students WHERE stu_intervene_id= '$studentId' ")); 

      $Student['newrow_ref_id'] = trim($Student['newrow_ref_id']);



      if($Student['newrow_ref_id']!=''){ //GetOldNewrowIDFor --interveneSystem

       $Tutoring_newrow_users[]=$Student['newrow_ref_id'];

       $newrow_user_id=$Student['newrow_ref_id'];



      }else{ //CreateNewrowIDForStudent--API



   $Student2=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE id= '$studentId' "));

   $StudentEmail='NewStudent_'.$Student2['id'].'@intervene.io';

   $StudentUsername='NewStudent_'.$Student2['id'].'';



      $user_arr=array('email'=>$StudentEmail,

                  'user_name'=>$StudentUsername, // UNQ

                  'first_name' =>$Student2['first_name'],

                  'last_name' =>'Student', // Student| Tutor

                  'role_type' =>'Student', // Instructor | Student

                   );



    

      $User_ob=_create_user($role_type='student',$user_arr);# 13497.

    

       $newrGeneratedId=$User_ob->data->user_id;

       $newrow_user_id=$newrGeneratedId;

       //New NewrowID

       $Tutoring_newrow_users[]= $newrow_user_id;

         $Res=dbc_add_user($studentId,$newrow_user_id);

         unset($user_arr);

      }

       $arr_student_nid[$temp_student_id]=$newrow_user_id;

     } // Endforeeach

 }

   $newrow_students_add=array_values($arr_student_nid);

   $ses_tutoring_id=$first_ses_id;



   $Created_room_id=$get_newrow_room_id;

   // Check of Newroom aleaready created for Tutoring ID 

   $Room_row=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_rooms 

    WHERE ses_tutoring_id= '$ses_tutoring_id'"));

   // If Already created : 

   $Created_room_id=(!empty($Room_row['newrow_room_id'])&&$Room_row['newrow_room_id']!='')?$Room_row['newrow_room_id']:$Created_room_id;


  foreach ($arr_student_nid as $intervene_id=> $newrowid) {

    $deletOld=mysql_query("DELETE FROM newrow_room_users WHERE user_type='student' AND ses_tutoring_id='$ses_tutoring_id' AND intervene_user_id='$intervene_id' ");

  $sql="INSERT INTO newrow_room_users SET newrow_user_id='$newrowid',

  intervene_user_id='$intervene_id',

  user_type='student',

  ses_tutoring_id='$ses_tutoring_id', 

  created_at='$today', 

  tp_id='$ses_tutoring_id', 



  newrow_room_id='$Created_room_id' ";

       //echo $sql; die; 

       $Add=mysql_query($sql);



     }



///////////////////////////

 // Step4-Add user to room ///

 // isCreate

 if(isset($isCreate)&&isset($get_newrow_room_id)){  // get_newrow_room_id{New room ID }

  $var=$_GET['room'];$userArr=[];

  
 $post = [

    'enroll_users' =>$newrow_students_add,  //array('32284','32287'),

    //'unenroll_users' =>'Custom room by api.',

    

];





//$Testing_room_id=23995;//Ses_room2240.

$token=$_SESSION['ses_admin_token'];

// $  $Testing_room_id=(isset($_GET['newrow_ref_room']))?$_GET['newrow_ref_room']:23995;

  $Testing_room_id=$get_newrow_room_id;



$RoomUrlLink='https://smart.newrow.com/backend/api/rooms/participants/'.$Testing_room_id;

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





       $result_josn = json_decode($result);

      
 }


      $_SESSION['ses_newrow_ids']=$Tutoring_newrow_users;





  



?>

