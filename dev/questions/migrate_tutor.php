<?php
/***
@Migrate remaning tutor id . 
**/


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
////////////////////////////////////////////////



include("header.php");
include('libraries/newrow.functions.php');
$warning_msg=[];$success_msg=[];
////////////////////////////



 $getToken=_get_token(); 
$_SESSION['ses_admin_token']=$getToken;

// echo 'NewToken==' , $getToken;
//  die; 

 /////////////////////////

 $Tutoring_newrow_users=[]; // All students Newrow ids.



   //$sql_tutor=("SELECT id,mig,email,f_name,lname FROM gig_teachers WHERE all_state='yes' LIMIT 0,8");

    $sql_tutor="SELECT id,mig,email,f_name,lname FROM gig_teachers WHERE 1 AND id=892 "; // all_state =yes
   $res_tutor=mysql_query($sql_tutor);



     while($line=mysql_fetch_assoc($res_tutor)) {

      //print_r($line); die; 
       $UserEmail=$line['email'];
       $Username='Tutor_'.$line['id'].'';
       $Tutor_int_id=$line['id'];
      
       $Tutor_newrow=mysql_fetch_assoc(mysql_query(" SELECT * FROM `newrow_x_tutors` WHERE `tutor_intervene_id` = '$Tutor_int_id' "));

        //var_export($Tutor_newrow); die;
       ///////////////////////////////

      $user_arr1=array('email'=>$UserEmail,
                  'user_name'=>$Username, // UNQ
                  'first_name' =>$line['f_name'],
                  'last_name' =>'Tutor', // Student| Tutor
                  'role' =>'Instructor', // Instructor | Student
                   );


     


       if($Tutor_newrow==false){  //Create newrow ID
         $User_ob=_create_user($role_type='student',$user_arr1);# 13497.
        unset($user_arr1);
        

      if($User_ob->status=='success'){ // add ref.id
        
        $newrow_user_id=$User_ob->data->user_id;

         $sql="INSERT INTO newrow_x_tutors SET tutor_intervene_id='$Tutor_int_id',newrow_email='$UserEmail',
         newrow_username='$UserEmail',newrow_ref_id='$newrow_user_id'  ";
         //echo $sql; die;

       $Add=mysql_query($sql);
       $warning_msg[]=$newrow_user_id.'--newrow_user_id migrated for newrow id<br/> ';

      }

        ///////////
       }else{  // Id created 
         $warning_msg[]='newrow id created for tutor-'.$Tutor_int_id.'<br/>';

       }

    
     



     }





 /////////TestUser Create////////////////////
   function _create_user($role_type='tutor',$user_arr=array()){ // tutor| student
     $get_user_email=$_GET['email']='Rajk@gmail.com';

////////////////////////////////////
$userId=time();
 //echo $_SESSION['ses_admin_token']; die; 
 $post = [
    'user_name' =>$user_arr['user_name'], //'test_tutor_'.$userId,
    'user_email' =>$user_arr['email'], //'test11@gmail.com',
    'first_name' =>$user_arr['first_name'],
     'last_name' =>$role_type,
     'role' =>'Instructor', // Instructor | Student
];

 

$token=$_SESSION['ses_admin_token']; // 5fc8c417a486296fb3fc334293b2b54c
///////////////////////


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
        return $user_row; 
     //  print_r($user_row);exit();  //die;
     
 
   }

 //////////////  











//////////////////////////////////
 if(!empty($warning_msg)){
 	//print_r($warning_msg); //die; 
   echo '<br/>Warning Mesage:<br/>';
  echo implode('<br/>',$warning_msg );
  echo '<br/>';
 }
 ///////////////////
 
 if(!empty($success_msg)){
  echo '<br/>Success Mesage:<br/>';
  echo implode('<br/>',$success_msg );
  exit;
 }

?>