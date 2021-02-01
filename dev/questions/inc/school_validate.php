<?php
/***
 * // school : data & Session
 * **/
//print_r($_SESSION); die;
$school_id=$_SESSION['schools_id']; //
 if(!isset($_SESSION['schools_id'])){
      //unset($_SESSION['assess_id']);
// unset($_SESSION['is_passage']); 
 //unset($_SESSION['list']); 
      //  unset($_SESSION['ses_taxonomy']); 
       // unset($_SESSION['qn_list']); 
      //  unset($_SESSION['is_passage_grade']); 
    header("Location:login.php");exit;  
    // echo 'Page not Found!, Login again'; die;
 }

$school_det=mysql_fetch_assoc(mysql_query("SELECT * FROM schools WHERE SchoolId=".$school_id));
@extract($school_det); // Login School Data //print_r($SchoolName);

//// Is School :Assessment/// ==1 Only
function is_school_assessment($assesment_id,$school){
    $results=mysql_query(" SELECT * FROM assessments WHERE id=".$assesment_id." AND created_by=".$school);
 $num=mysql_num_rows($results);
 return $num;

}
?>