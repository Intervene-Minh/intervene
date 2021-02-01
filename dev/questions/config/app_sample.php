<?php
// $con=mysqli_connect("localhost","sradmin","Test56789","jecomdb")
$db=new mysqli('localhost','sradmin','Test56789','dbpromisingindians');

if($db->connect_errno){
    echo 'Db connection error-'.$db->connect_error;
}


/// Fetch ///
$res=$db->query(" SELECT * FROM `terms` WHERE id=2");
$res2=$res->fetch_assoc();
 // if(nlll!=={
 //    print_r($data); die;  
 // }else{
 // echo ''
 // }
 print_r($res2); die;  




?>