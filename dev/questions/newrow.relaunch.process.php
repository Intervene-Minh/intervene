<?php

/*

@ send TutorngId

**/



 function _create_user($role_type='student',$user_arr=array())

   { 

      // tutor| student

      $get_user_email=$_GET['email']='Rajk@gmail.com';



      ////////////////////////////////////

      $userId=time();

      $post = [

      'user_name' =>$user_arr['user_name'], //'test_tutor_'.$userId,

      'user_email' =>$user_arr['email'], //'test11@gmail.com',

      'first_name' =>$user_arr['first_name'],

      'last_name' =>$role_type,

      'CompanyUser' =>$role_type, // Instructor | Student

      ];



   





      $token=$_SESSION['ses_admin_token'];



      $ch = curl_init('https://smart.newrow.com/backend/api/users'); // Initialise cURL

      $post = json_encode($post); // Encode the data array into a JSON string

      $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token

      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST

      curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields

      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects

      $result = curl_exec($ch); // Execute the cURL statement

      $user_row= json_decode($result); 

      curl_close($ch); // Close the cURL connection



      //return $result='==='; 



      return $user_row; 

   }



   /*

   @Add user



   */



   function dbc_add_user($studentId,$newrow_user_id)

 {

    $Student=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE id= '$studentId' ")); //TestStudent

    $StudentEmail='Student_'.$Student['id'].'@intervene.io';

    $StudentUsername='Student_'.$Student['id'].'';

    $StudentExist=mysql_fetch_assoc(mysql_query("SELECT * FROM newrow_students WHERE stu_intervene_id= '$studentId' ")); #



    // die; 



    if(!empty($StudentExist))

    {



    $msg=$studentId.'student,  UPDATED ssuccesfully, '.$newrow_user_id;



    $sql="UPDATE newrow_students SET  newrow_ref_id='$newrow_user_id',newrow_email='$StudentEmail',newrow_username='$StudentUsername' WHERE stu_intervene_id=".$studentId;

    // echo $sql; die; 

    $Add=mysql_query($sql);

    $res='Updated';



    }else

    {  

    // AddStudentTo{newrow_students}

    $sql="INSERT INTO newrow_students SET stu_intervene_id='$studentId',newrow_ref_id='$newrow_user_id',newrow_email='$StudentEmail',newrow_username='$StudentUsername'  ";

    //echo $sql; die; 

    $Add=mysql_query($sql);

    $msg= $studentId.'student,  added ssuccesfully, '.$newrow_user_id;



    $res='Added';   

    }



    return $res;





}





////

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

$curr_board=TUTOR_BOARD; 











include("header.php");

include('libraries/newrow.functions.php');

// warning message

$warning_msg=[];

$success_msg=[];











///////////////////////////////////////////

 

if ($_SESSION['login_role'] != 0)

{

    header('Location: folder.php');

    exit;

}



///



 if(!isset($_GET['id'])){

  exit('Enter session ID!, ?id=232424');

 }







//////////////////////////





$getToken=_get_token(); 

$_SESSION['ses_admin_token']=$getToken;

$Tutoring_students_arrStop=array('13500','13501');



$Tutoring_id=$_GET['id'];// Student_list

$arr_students=[];

$res_students=mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id = '$Tutoring_id' ");





  while ($line=mysql_fetch_assoc($res_students)) {

    $arr_students[]=$line['student_id'];

  }





if(empty($arr_students)){

  exit('No student in session');

}



//

# Student in Tutoring:: Test









///////////////////Validate///////////////////////





 if(isset($_GET['id']))

 {



    $Intervention_id=$_GET['id'];

    $Intervention_id=$_GET['id'];

    $room_row=_create_room($Intervention_id);



    if($room_row->status=='error')

    { 

        unset($isCreate);

        $warning_msg[]='Room already exist in your company! ';# [code] => 11001

        ///Get nwewrow rooom id from intervene db if room aleardy created 





    }elseif($room_row->status=='success')

    {

          $isCreate=1;

          $NewrowRoomId=$room_row->data->id;

          $get_newrow_room_id=$NewrowRoomId; //NewroomId

          $roomName='Intervention_room'.$Intervention_id;



    }







     if($isCreate)

     { 

          // Saver room ID to intervene system 



          $sql="INSERT INTO newrow_rooms SET newrow_room_id='$get_newrow_room_id',

          ses_tutoring_id='$Intervention_id',name='$roomName',description='$roomName',

          tp_id='$Intervention_id',created_at='$today' ";

          $Save=mysql_query($sql)or die($sql);

          if($Save){

            $success_msg[] ='<tr><td>Created Room ID</td><td>'.$get_newrow_room_id.'</td></tr>';
          }

    }



//Step2:Student Regsiter at- Newrow 



$Tutoring_students_arr=$arr_students; // All students Newrow ids. 

$arr_student_nid=[];





     foreach ($Tutoring_students_arr as $studentId) 

     {

 

        $temp_student_id=$studentId;

        $Student=mysql_fetch_assoc(mysql_query("SELECT * FROM newrow_students WHERE stu_intervene_id= '$studentId' ")); 

        $Student['newrow_ref_id']=trim($Student['newrow_ref_id']);









        if($Student['newrow_ref_id']!='')

        { 



        $Tutoring_newrow_users[]=$Student['newrow_ref_id'];

        $newrow_user_id=$Student['newrow_ref_id'];



        }else

        {

        //CreateNewrowIDForStudent--API

        $Student2=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE id= '$studentId' "));

        $StudentEmail='Student_'.$Student2['id'].'@intervene.io';

        $StudentUsername='Student_'.$Student2['id'].'';



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









        ///////////////////////





        $Res=dbc_add_user($studentId,$newrow_user_id);

        unset($user_arr);

        }









        # Add student Newrow IDS

        $arr_student_nid[$temp_student_id]=$newrow_user_id;

















     } // Endforeeach





 }









 //Step-3 Student List in Tutoring Session



$newrow_students_add=array_values($arr_student_nid);

$ses_tutoring_id=$_GET['id'];

$Created_room_id=$get_newrow_room_id;

// Check of Newroom aleaready created for Tutoring ID 

$Room_row=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_rooms 

WHERE ses_tutoring_id= '$ses_tutoring_id'"));

// If Already created : 

$Created_room_id=(!empty($Room_row['newrow_room_id'])&&$Room_row['newrow_room_id']!='')?$Room_row['newrow_room_id']:$Created_room_id;



// Add students to intervne:TutoringIdAllStudents



  foreach ($arr_student_nid as $intervene_id=> $newrowid)

  {

     $deletOld=mysql_query("DELETE FROM newrow_room_users WHERE user_type='student' AND ses_tutoring_id='$ses_tutoring_id' AND intervene_user_id='$intervene_id' ");







  //////////////////////////////////



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

  



 // Step4-Add user to room :Live newrow Room///



 if(isset($isCreate)&&isset($get_newrow_room_id))

 {  



      // get_newrow_room_id{New room ID }

     $success_msg[] ='<tr><td>Newrow students ids added to room</td><td>'.implode(', ',$newrow_students_add).'</td></tr>' ;

      $var=$_GET['room'];$userArr=[]; 

      $post = [  

      'enroll_users' =>$newrow_students_add,  //array('32284','32287'),

      //'unenroll_users' =>'Custom room by api.',



      ];



      $token=$_SESSION['ses_admin_token'];

      $Testing_room_id=$get_newrow_room_id;

      $RoomUrlLink='https://smart.newrow.com/backend/api/rooms/participants/'.$Testing_room_id;

      $api_url='rooms/participants/<room_id>​';







     //curl_init

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

    }

    $result_josn = json_decode($result);

       $success_msg[]='<tr><td>User added Info</td><td>'
               . 'Status: <span class="label label-success"> '.$result_josn->status.'</span>'
               . '<br>Added : '.$result_josn->data->added.'<br>Removed : '.$result_josn->data->removed.'</td></tr>';

  
    // return json_decode($result); // Return the received data 



 }











// Step4-Add user to room ///



    $_SESSION['ses_newrow_ids']=$Tutoring_newrow_users;

    $success_msg[]='<tr><td>Room created at newrow status<t/d><td><span class="label label-success">Success</span></td></tr>';

  
 $sql=" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_GET['id'];



$results = mysql_query($sql);


$tot_record=mysql_num_rows($results);  

if( $tot_record)
{
    $row = mysql_fetch_assoc($results);
      $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name,last_name FROM users WHERE id=".$row['teacher_id']));

               $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));

                 $tot_std=($tot_std>0)?$tot_std:"XX";

             $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));    

         

             $int_school= mysql_fetch_assoc(mysql_query("SELECT district_id,SchoolName FROM schools WHERE SchoolId=".$row['school_id']));     

             // district_id 

          if($int_school['district_id']>0){

          $district=mysql_fetch_assoc(mysql_query(" SELECT  district_name FROM loc_district WHERE id=".$int_school['district_id']));     

          $districtName=$district['district_name'];

          

          }

          

          /// inAdmin Info SELECT * FROM `users` WHERE 1 

         $admin=mysql_fetch_assoc(mysql_query(" SELECT * FROM `users` WHERE id=1 ")); // Def

          

         // Exp time

        $sesStartTime=$row['ses_start_time'];

        $curr_time= date("Y-m-d H:i:s");

         

     $in_sec= strtotime($sesStartTime) - strtotime($curr_time);///604800 #days>+7 days

         

        $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));  

         $quiz['objective_name']=(!empty($quiz['objective_name']))?$quiz['objective_name']:"NA";

         //// list of students 

          $q=" Select sd.last_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";

            $q.=" WHERE ses.slot_id='".$row['id']."' ";

            $resss=mysql_query($q);

            $stud_str=array(); // middle_name

            while ($row2=mysql_fetch_assoc($resss)) {

              // last_name

   $stud_str[]=$row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];





            }  

            $stdList=(count($stud_str)>0)? implode(", ", $stud_str):"NA";

            // 

       $lesson=mysql_fetch_assoc(mysql_query("SELECT id,name FROM `master_lessons` WHERE id='".$row['lesson_id']."' "));
}
?>
<div id="main" class="clear fullwidth">

	<div class="container">

		<div class="row">

			<div id="sidebar" class="col-md-4">

				<?php include("sidebar.php"); ?>

			</div>		<!-- /#sidebar -->

			<div id="content" class="col-md-8">
                          <p> <div class="alert alert-success" role="alert" style="text-align: center"><strong>Your session has been created successfully</strong></div></p>
                        
                        <?php  if(!empty($warning_msg)){ ?>
                        <p> <div class="alert alert-danger" role="alert" style="text-align: center"><strong><?php echo implode(" ", $warning_msg);?></strong></div></p>
                        <?php } ?>
                
                            <?php if(!empty($success_msg))
{?>
                            
                            <div class="panel panel-default">
                                
  <!-- Default panel contents -->
  <div class="panel-heading">Newrow Session Details</div>
  <div class="panel-body">
   <table class="table ">
        <tr><td>Session ID</td><td> <?=ucwords($row['id'])?></td></tr>
 <tr><td>Session Time</td><td> <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?>   <?=date_format(date_create($row['ses_start_time']), 'h:i a');?></td></tr>
 <tr><td>Session Duration</td><td> <?=ucwords($row['session_duration'])?> mins</td></tr>
 <tr><td>Virtual board</td><td> <?=ucwords($row['board_type'])?></td></tr>
  <tr><td>Lesson</td><td> <?=$lesson['name']?></td></tr>
    <tr><td>School</td><td> <?=$int_school['SchoolName']?></td></tr>
      <tr><td>District</td><td> <?=$districtName?></td></tr>
      <tr><td>Class list of students</td><td> <?=$stdList?></td></tr>
      <?php
       if(!empty($success_msg)){
          echo implode('',$success_msg );
      }
 ?>
</table>
  </div>

  <!-- Table -->
  
</div>
                        <p>
                            <a href="intervention_list_latest.php" class="btn btn-primary"> Go to session list </a>
                             <a href="edit_session.php?sid=<?php echo $row['id'];?>" class="btn btn-success"> Edit This Session </a>
                        </p>

                                    

<?php } else { ?>
                         <p> <div class="alert alert-danger" role="alert" style="text-align: center"><strong>Your session has not been created</strong></div></p>
<?php } ?>





				

			</div>		<!-- /#content -->

			<div class="clearnone">&nbsp;</div>

		</div>

	</div>

</div>		<!-- /#header -->




<?php include("footer.php"); ?>
