<?php  
/****
@ Refresh by page: notifications.php
**/
session_start();
ob_start();
$home_url="https://tutorgigs.io/"; 
include('inc/connection.php'); 

//echo 'Hi get data from server. ';

 $tutorId=$_SESSION['ses_teacher_id'];
 $sql="SELECT * FROM `notifications` WHERE `receiver_id` =".$tutorId;
 $sql.=" ORDER BY created_at DESC ";
 $result= mysql_query($sql);
    $data=array();
    $total=mysql_num_rows($result);
   if($total>0){
    while ($row=mysql_fetch_assoc($result)){
      //  id, sender_type info url time
     // $data[]=array('text' =>$row['info'] ,'name' =>'Admin' ,'image' =>'ap.png'  );
      $data[]=array('text' =>'message-'.$row['id'] ,'name' =>'Admin' ,'image' =>'ap.png'  );
      // text name  image


  

    }
   }

   $json=json_encode($data);// JSON_NUMERIC_CHECK
   echo $json; die; 
   /// Display/////
 echo '<pre>', print_r($data);
 die; 


   ?> 
