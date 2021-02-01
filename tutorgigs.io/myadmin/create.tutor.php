<?php
/***
//$sql_tutor=mysql_query("SELECT id,mig,email,f_name,lname FROM gig_teachers WHERE all_state='yes' LIMIT 0,8");


 $tutorId=901; // Test  1
@Backup file 
@Create tutor at-newrow
**/
@session_start();


include("header.php");
include('newrow.functions.php');
$warning_msg=[];$success_msg=[];

///////////////////////////

//////////////////
$getToken=_get_token(); 
$_SESSION['ses_admin_token']=$getToken;

//echo 'TTTTTTTTTTTTT==create token=' , $getToken; die; 
//////////////////////////////

 if(!isset($_GET['uid'])){
  exit('Tutor id missig, ?uid=34435');
 }


$tutorId=$_GET['uid'];
$Tutor=mysql_fetch_assoc(mysql_query(" SELECT * FROM  gig_teachers WHERE id = '$tutorId' "));

// print_r($Tutor);
// echo '==';
//  var_export($Tutor);
// die;


 if(isset($Tutor)&&!empty($Tutor)){
  $success_msg[]='User found! ';

    $res_newrow=mysql_query(" SELECT * FROM  newrow_x_tutors WHERE tutor_intervene_id= '$tutorId' ");
    $Tutor_rows=mysql_num_rows($res_newrow);

    if($Tutor_rows<1){
      $success_msg[]=$Tutor_rows.'- rows found in';
      $canCreateNewrowId='yes';
      ///////////////////////////////
       // $newrow_user_id=$User_ob->data->user_id;
     

      ///////////////////////

    }elseif($Tutor_rows>=1){
      $Tutor_newrow=mysql_fetch_assoc($res_newrow);

      $warning_msg[]='newrow id already Created! ';

    }

  ////////////////
 }else{
  $warning_msg[]='Wrong user id! ';

 }


//////////////////


    /////////////////////////////////////
   



   






////////////////////////////////////
  if(empty($warning_msg)&&isset($canCreateNewrowId)&&$canCreateNewrowId=='yes'){
    //print_r($Tutor);

    //////////////////

$userId=time();
$get_user_email='Test'.$userId.'@gmail.com';
$get_user_email = (string)$Tutor['email'];

 //echo $_SESSION['ses_admin_token']; die; f_name
 $post = [
    'user_name' =>'Demo_tutor_'.$userId,
    'user_email' =>$get_user_email, //'test11@gmail.com',
    'first_name' =>$Tutor['f_name'], //'Mastertutor',
     'last_name' =>'Tutor',
     'role' =>'Instructor', // Instructor | Student {CompanyUser}
    
   
];

 $post333 = [
    'user_name' =>'Demotest_tutor_'.$userId,
    'user_email' =>$get_user_email, //'test11@gmail.com',
    'first_name' =>'Rohit',
     'last_name' =>'Tutor',
     'role' =>'Instructor', // Instructor | Student {CompanyUser}
    
   
];
///////////////////////
//print_r($post); die; 






$token=$getToken;// // 5fc8c417a486296fb3fc334293b2b54c

if(empty($token)){
  exit('Admin token not found! ');
}

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
       ///////////////////////////

        $Tutor_int_id=$tutorId;
      $UserEmail=$Tutor['email'];
      
      $UserEmail='Tdff';
      $newrow_user_id=$user_row->data->user_id;

         $sql="INSERT INTO newrow_x_tutors SET tutor_intervene_id='$Tutor_int_id',newrow_email='$UserEmail',
         newrow_username='$UserEmail',newrow_ref_id='$newrow_user_id'  ";
        // echo $sql; die;

       $Add=mysql_query($sql);



       print_r($user_row);exit();  //die;

     }

      // return json_decode($result); // Return the received data
     /////////////////
     if(!empty($success_msg)){
      echo implode(',<br/>', $success_msg ); //print_r($success_msg);
      echo '<br/>';
     }
     /////////////////////

      if(!empty($warning_msg)){

      echo implode(',<br/>', $warning_msg );
    }

?>