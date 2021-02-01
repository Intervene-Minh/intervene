<?php
// down_students
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
include('inc/connection.php'); 
session_start();
ob_start();
$created = date('Y-m-d H:i:s');
$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}

///  download CSV////////////
 if(isset($_GET['id'])){
$class_id =$_GET['id']; // 
//echo  $class_id.'class id'; die;
//$ff=''.$class_id;
   $data= mysql_fetch_assoc(mysql_query("SELECT class_name FROM `classes` WHERE id=".$class_id)); 
   $className=(!empty($data['class_name']))?$data['class_name']:"Class_".$class_id;
   
//$class_id =48; // 

$stuIDsArr=[];


$qr=mysql_query("SELECT student_id FROM `students_x_class` WHERE class_id=".$class_id);
while($stuIDs= mysql_fetch_assoc($qr)){

$stuIDsArr[$stuIDs['student_id']]= $stuIDs['student_id'];

} 


$StuList =implode(',',$stuIDsArr);
$classes_res=mysql_query("SELECT first_name,last_name, username FROM students WHERE id In($StuList)");
$data_arr=array();  //$sdddr=NULL;
        // output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$className.'.csv');
// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
// output the column headings
fputcsv($output, array('First Name','Last Name','Username'));
// first_name, middle_name, last_name, username, password
 // Set passwprd :
// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($classes_res)) fputcsv($output, $row);
}
else 
exit('Page not found. !');