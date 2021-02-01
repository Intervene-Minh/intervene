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
 $sql.=" AND `read` = '0' ";
 $sql.=" ORDER BY created_at DESC ";
 //echo $sql ; die;
 $result= mysql_query($sql);
    $data=array();
    $total=mysql_num_rows($result);
   if($total>0){
    while ($row=mysql_fetch_assoc($result)){
    //$sumary=substr($row['info'],0,25);
      $message=($row['type']=='message')?substr($row['info'],0,30).'...':$row['info'];
     // $message=$row['info'];
      //  id, sender_type info url time
      $data[]=array('text' =>$message ,
        'name' =>'Admin' ,
         'type' =>$row['type'] ,
         'type_id' =>$row['type_id'] ,// jobid, message
        'image' =>'ap.png'  );
      // text name  image


  

    }
   }

   ##
 $result=array();
$result['total']=$total; //  new , total notification
 $result['tot_unread']=2;
  $result['content']=$data;
///////////////////////
   $json=json_encode($result);// JSON_NUMERIC_CHECK
   echo $json; die; 
   /// Display/////
 echo '<pre>', print_r($data);
 die; 


   ?> 
