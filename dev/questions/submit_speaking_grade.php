<?php

extract($_REQUEST);
$scored_grade=0;
$divided_grade=0;
include('inc/connection.php'); 

/* add grade*/
if($action=='addGrade')
{

	$str="UPDATE telpas_student_speaking_grades SET `scored_grade_number`='".$score."' WHERE  `id`=".$id;
	if(mysql_query($str))
	{
		$str="SELECT * FROM `telpas_student_speaking_grades`WHERE course_id='".$course_id."' AND intervene_student_id='".$stuid."' ORDER BY `question_id`"; 
		$audio=mysql_query($str);
		while ($row = mysql_fetch_assoc($audio)) 
		{

			$scored_grade=$row['scored_grade_number']+$scored_grade;
			$divided_grade=4 + $divided_grade;
		}

		 $scored_grade_number=(($scored_grade/$divided_grade)*100);
                 
                 if($scored_grade_number >= 1 && $scored_grade_number <= 25)
                    echo '<span  style="color:#d9534f;font-weight:bold">Beginner</span>'; 
                 else if($scored_grade_number >= 26 && $scored_grade_number <= 50)
                    echo '<span style="color:#f0ad4e;font-weight:bold">Intermediate </span>'; 
                 else if($scored_grade_number >= 51 && $scored_grade_number <= 75)
                    echo '<span style="color:#337ab7;font-weight:bold">Advanced </span>'; 
                 else if($scored_grade_number >= 76 && $scored_grade_number <= 100)
                    echo '<span style="color:#5cb85c;font-weight:bold">Advanced High </span>'; 
                 else
                   echo 'N/A';
	}
}
else{
		foreach ($id as $key=>$row) {

		$str="UPDATE telpas_student_speaking_grades SET `scored_grade_number`='".$score[$key]."' WHERE  `id`=".$row;
		$row= mysql_affected_rows();
		mysql_query($str);

	}


		$str="INSERT INTO  telpas_speaking_completed SET `intervene_student_id`='".$student_id."', `teacher_id`='".$teacher_id."',`course_id`='".$course_id."',`telpas_student_id`='".$telpas_student_id."',`created_at`='".date('Y-m-d')."'";
		mysql_query($str);
                
                $str="SELECT * FROM `telpas_student_speaking_grades`WHERE course_id='".$course_id."' AND intervene_student_id='".$student_id."' ORDER BY `question_id`"; 
		$audio=mysql_query($str);
		while ($row = mysql_fetch_assoc($audio)) 
		{

			$scored_grade=$row['scored_grade_number']+$scored_grade;
			$divided_grade=4 + $divided_grade;
		}

		 $scored_grade_number=(($scored_grade/$divided_grade)*100);
                 
                 if($scored_grade_number >= 1 && $scored_grade_number <= 25)
                    echo '<span  style="color:#d9534f;font-weight:bold">Beginner</span>'; 
                 else if($scored_grade_number >= 26 && $scored_grade_number <= 50)
                    echo '<span style="color:#f0ad4e;font-weight:bold">Intermediate </span>'; 
                 else if($scored_grade_number >= 51 && $scored_grade_number <= 75)
                    echo '<span style="color:#337ab7;font-weight:bold">Advanced </span>'; 
                 else if($scored_grade_number >= 76 && $scored_grade_number <= 100)
                    echo '<span style="color:#5cb85c;font-weight:bold">Advanced High </span>'; 
                 else
                   echo 'N/A';
	}


?>
