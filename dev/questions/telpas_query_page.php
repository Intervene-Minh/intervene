<?php
/* get telpas user id */
$str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`='".$_SESSION['student_id']."' ORDER BY ID DESC
LIMIT 1";
$moodle_data = mysql_fetch_assoc(mysql_query($str));
$TeluserID =$moodle_data['TelUserID'];
$arrCrs=[];
$arrCrsComp=[];
$arrW=[];
$arrS=[];
$arrR=[];
$arrStatus=array();
$arrStatusW=array();
$arrStatusS=array();


if(!empty($TeluserID) && $TeluserID > 0){


/* get enroll course*/
$str="SELECT course_id FROM `Telpas_course_users` WHERE `telpas_uuid` =".$TeluserID;
$res=mysql_query($str);
while($row = mysql_fetch_assoc($res)){

	$arrCrs[$row['course_id']]= $row['course_id'];
}



/* get complelete course*/
$str="SELECT CourseID FROM `Tel_CourseComplete` WHERE `TelUserID` =".$TeluserID;
$res=mysql_query($str);
while($row = mysql_fetch_assoc($res)){

	$arrCrsComp[]= $row['CourseID'];
}

/* get score  write*/
$strWrite="SELECT CEIL( ((100* sum(scored_grade_number))/( sum(max_grade_number)) ))  as  perdentage_score,
 sum(scored_grade_number) as student_scored,
 sum(max_grade_number) as max_score, course_id
FROM  telpas_student_writing_grades WHERE telpas_student_id='".$TeluserID."'
Group By course_id";

$res=mysql_query($strWrite);

while($row = mysql_fetch_assoc($res)){

		$prog= number_format($row['perdentage_score']);
		$course_id=$row['course_id'];
		$arrW[$row['course_id']]= $row['perdentage_score'];

		if(in_array(trim($course_id),$arrCrsComp))
		{  

          $arrStatusW[$row['course_id']]['btn']      = "Completed";
          $arrStatusW[$row['course_id']]['status']   = "<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
           //$arrStatusW[$row['course_id']]['dis']      = "disabled";
           $arrStatusW[$row['course_id']]['dis']      = " ";
      	}
      	elseif($prog  > 0)
      	{  

          $arrStatusW[$row['course_id']]['btn']      = "Completed";
          $arrStatusW[$row['course_id']]['status']   = "<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
           //$arrStatusW[$row['course_id']]['dis']      = "disabled";
          $arrStatusW[$row['course_id']]['dis']      = "";
       }

}


/* get score  speak*/
/*
$strSpeaking="SELECT CEIL( ((100* sum(scored_grade_number))/( sum(max_grade_number)) ))  as  perdentage_score,
 sum(scored_grade_number) as student_scored,
 sum(max_grade_number) as max_score, course_id

FROM  telpas_student_speaking_grades WHERE telpas_student_id='".$TeluserID."'
Group By course_id";

$res=mysql_query($strSpeaking);
while($row = mysql_fetch_assoc($res)){

	$course_id=$row['course_id'];
	$prog= number_format($row['perdentage_score']);
	$arrS[$row['course_id']]= $row['perdentage_score'];
	if(in_array(trim($course_id),$arrCrsComp))
      {  

          $arrStatusS[$row['course_id']]['btn']      = "Completed";
          $arrStatusS[$row['course_id']]['status']   = "<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
          //$arrStatusS[$row['course_id']]['dis']      = "disabled";
           $arrStatusS[$row['course_id']]['dis']      = "";
      }
      elseif($prog > 0)
      {  

          $arrStatusS[$row['course_id']]['btn']      = "Completed";
          $arrStatusS[$row['course_id']]['status']   = "<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
         // $arrStatusS[$row['course_id']]['dis']      = "disabled";
          $arrStatusS[$row['course_id']]['dis']      = " ";
      }
      
}
*/


function get_statusSW($table='telpas_student_speaking_grades',$courseID=75)

{

    $str=" SELECT total_question,attemp_question FROM $table WHERE  course_id= $courseID && intervene_student_id =".$_SESSION['student_id'];
    $res=mysql_query($str);
    /*attemp question  count*/
    $attemp_question = mysql_num_rows($res);
    /*total question  count*/
    $row = mysql_fetch_assoc($res);
    $total_question =$row['total_question'];
    $question_Percantage=((1*$total_question)/100);
    $attemp_question= $row['attemp_question']+$attemp_question;

    $prog=ceil((($attemp_question)/$question_Percantage));
    if($prog ==100)
    {
    $btn    =  "Completed";
    $status  =  "<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
    }
    if($prog < 100 && $prog > 0)
    {
    $btn     = "RESUME";
    $status  = "<span style='color:red;'><strong>Incomplete</strong></span>";
    }

    elseif($prog < 100 && $prog > 0)
    {  

    $btn      = "Completed";
    $status  ="<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
    }


    return array('btn'=>$btn,'status'=>$status,'prog'=>$prog);


      }


/* get score  listeing and Reading */
 $str="SELECT score_percent , course_id FROM  `telpas_course_score_logs` WHERE telpas_student_id=".$TeluserID;
$res=mysql_query($str);
while($row = mysql_fetch_assoc($res)){
  $prog= number_format(($row['score_percent']));
  $arrR[$row['course_id']]= $row['score_percent'];
   if($prog ==100)
      {
         $arrStatus[$row['course_id']]['btn']     =  "Completed";
         $arrStatus[$row['course_id']]['status']  =  "<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
      }
      if($prog < 100 && $prog > 0)
      {
          $arrStatus[$row['course_id']]['btn']     = "RESUME";
          $arrStatus[$row['course_id']]['status']  = "<span style='color:red;'><strong>Incomplete</strong></span>";
      }
      
      elseif($prog < 100 && $prog > 0)
      {  

          $arrStatus[$row['course_id']]['btn']      = "Completed";
          $arrStatus[$row['course_id']]['status']  ="<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
      }
  }
}



?>