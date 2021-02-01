<?php
require'curl.function.php';
include("emailerPage_before.php"); 
ini_set("date.timezone", "CST6CDT");
$con = new mysqli('localhost', 'mhl397', 'Developer2!','lonestaar');

if ($con ->connect_errno) {
  echo "Failed to connect to MySQL: " . $con ->connect_error;
  exit();
}

$str='Call GetAllSesion()';


$result=$con -> query($str);



while($row = $result -> fetch_assoc())
{
        

  $arr[$row['id']] = $row['drhomework_ref_id'];
     

}

$con -> close();


$apiUrl="https://drhomework.com/parent/getAllParentEmailApi.php";

$postFields=array('session_id'=>implode(',',$arr));



 $data=HitPostCurl($apiUrl,$postFields);

$res=json_decode($data,true);




for ($i=0; $i<count($res);$i++) {



$time =$res[$i]['time'];
$name =$res[$i]['name'];
$email =$res[$i]['email'];
_parentNotifyEmail($time,$name,$email);



}
 
?>
 