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



   $no_of_happy=mysql_num_rows(mysql_query("SELECT * FROM user_survey 
    WHERE form_ques_1_ans= 'normal' "));


   
///////////////////////////////////////////
$data_arr=array('no_of_happy','no_of_unhappy','no_of_normal');

$count_arr=array('no_of_happy'=>($no_of_happy+0),
               'no_of_unhappy'=>(5+$no_of_happy),
               'no_of_normal'=>$no_of_happy
              );
$arrayVal=array_values($count_arr); 
////////////////////////// 


   $data_arr=array();  //$sdddr=NULL;
//header('Content-Type: application/csv');
//header('Content-Disposition: attachment; filename="filename.csv"');

$className='user_survey';

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$className.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('no_of_happy','no_of_unhappy','no_of_normal'));
// first_name, middle_name, last_name, username, password
 // Set passwprd :
// loop over the rows, outputting them
 # while ($row = mysql_fetch_assoc($data_arr2)) fputcsv($output, $row);
 //foreach ($arrayVal as $arr) {
   # code...
  fputcsv($output, $arrayVal); 
// }


 }else exit('Page not found. !');