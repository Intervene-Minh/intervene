<?php
// Student Score in 1 speaking 

$page_name="Telpas Courses";
$allowed_telpas_course_cat=array(5);

include('inc/connection.php'); 
global $base_url;
$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';

 // echo  $base_url, 'Testing==';//
 @session_start();ob_start();

  if (!$_SESSION['student_id'])
{
  header('Location: login.php');
  exit;
}
/////////////////////
 $spearking_score=_student_speaking_score($studet_id=103,$course_id=20);
 echo  'Res',$spearking_score; 

 function _student_speaking_score($studet_id,$course_id)
 {

 $sql_spearking_question="SELECT *
FROM telpas_student_speaking_grades
WHERE 1
AND course_id='".$course_id."'
AND telpas_student_id=".$studet_id;
$result=mysql_query($sql_spearking_question);
 $grade_total=0;
 $grade_scored_toatal=0;
 while ($row=mysql_fetch_assoc($result)) {
 	# code...
 	$grade_total=$grade_total+intval($row['max_grade_number']);
 	$grade_scored_toatal=$grade_scored_toatal+intval($row['scored_grade_number']);
     // print_r($row); die; 
 }
  
   $scored=(100*$grade_scored_toatal)/($grade_total);
   return $scored;
  
  //echo 'TTT==',$scored; die; 
  //return $
}






 function _student_get_score($table,$studet_id,$course_id)
 {

		$sql_spearking_question="SELECT * FROM  $table WHERE course_id='".$course_id."' AND intervene_student_id=".$studet_id;
		$result=mysql_query($sql_spearking_question);
		$grade_total=0;
		$grade_scored_toatal=0;
		while ($row=mysql_fetch_assoc($result)) {
			# code...
			$grade_total=$grade_total+intval($row['max_grade_number']);
			$grade_scored_toatal=$grade_scored_toatal+intval($row['scored_grade_number']);
			// print_r($row); die; 
		}
		
		$scored=(100*$grade_scored_toatal)/($grade_total);
   		return $scored;

}
?>