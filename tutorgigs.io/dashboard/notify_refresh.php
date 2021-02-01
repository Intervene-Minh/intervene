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
      $data[]=$row;


  

    }
   }
   /// Display/////
 // echo '<pre>', print_r($data);
 //  die; 


   ?> 

<h1 align="center">Notification(<?=$total?>)</h1>



<ul id="myUL">
   <?php 
  if($total>0){
  foreach ($data as $key => $arr) {
    # code...
    if($arr['type']=="jobs"){
      $url='Jobs-Board-List.php?id='.$arr['type_id'];
    }else{ $url='inbox.php';}
    
    
    //if job notify then - go job-board. 
  
   ?> 
<li title="<?=$arr['info']?>"><a href="<?=$url?>"> <?=$arr['info']?></a></li>

   <?php 
    }
   } ?>
   </ul>