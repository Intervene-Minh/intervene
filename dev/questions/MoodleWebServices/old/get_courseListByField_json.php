<?php

include('../inc/connection.php'); 
session_start();
extract($_REQUEST);

  function _getScore($stuid,$course_id){
      $scored_grade=0;
      $divided_grade=0;
      $str="SELECT * FROM `telpas_student_speaking_grades`WHERE course_id='".$course_id."' AND  intervene_student_id='".$stuid."' ORDER BY `question_id`"; 
      $audio=mysql_query($str);
      while ($row = mysql_fetch_assoc($audio)) 
      {

      $scored_grade=$row['scored_grade_number']+$scored_grade;
      $divided_grade=4 + $divided_grade;
      }

     return (($scored_grade/$divided_grade)*100);

}

  function _getScoreWrie($stuid,$course_id){
      $scored_grade=0;
      $divided_grade=0;
       $str="SELECT * FROM `telpas_student_writing_grades`WHERE course_id='".$course_id."' AND  intervene_student_id='".$stuid."' ORDER BY `question_id`"; 
      $audio=mysql_query($str);
      while ($row = mysql_fetch_assoc($audio)) 
      {

      $scored_grade=$row['scored_grade_number']+$scored_grade;
      $divided_grade=4 + $divided_grade;
      }

     return (($scored_grade/$divided_grade)*100);

}

///Get category course list
$token = '14432b87ba8ea3a4896dc7707d10e71d';
$domainname = 'https://ellpractice.com/intervene/moodle';
$functionname = 'core_course_get_courses_by_field';
require_once('cur-moodlel.php');
$curl = new curl;
$restformat = 'json'; 

$params = array('field'=>'category','value'=>$category);

//print_r($params);
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$responsCourse = json_decode($resp,true);

//print_r($responsCourse);
if(!empty($responsCourse))
{
    $category_course_arr=[];
foreach ($responsCourse['courses'] as  $value) 
{   
	  $category_course_arr[$value['id']]=array('course_cate_name'=>$value['fullname'] , 
	  	'idnumber'=>$value['idnumber'] ,
	  	'course_cat_data'=>$value,
	);

}

}



$studentArray=[];
$student_rows=[];
 $login_teacher_id=$_SESSION['login_id'];


include_once('get-stdent-grade.php');



function _GetCompleteStatus($stuUD){

global $courseID;
$str = "SELECT IsComplete FROM `Tel_CourseComplete` WHERE TelUserID='".$stuUD."' AND CourseID='".$courseID."'";

$Telpas_students=mysql_query($str);
$row = mysql_fetch_assoc($Telpas_students);
if($row['IsComplete'] > 0)
{
  $Status="<span style='color:#1d6d1f'><strong>Complete</strong></span>";
}
else
{

    

    $Status="<span style='color:red'><strong>Incomplete</strong></span>";
} 
          

return $Status;
}
/* get student arrya*/




// $sql_student="SELECT tu.* ,s.* FROM Tel_UserDetails tu INNER JOIN students s ON tu.IntervenID=s.id WHERE tu.user_type='student' AND s.teacher_id=".$login_teacher_id; 


$sql_student="SELECT tu.* ,s.* FROM Tel_UserDetails tu INNER JOIN students s ON tu.IntervenID=s.id WHERE tu.user_type='student' AND s.teacher_id=".$login_teacher_id; 
$sql_student.=" ORDER BY  s.first_name,s.last_name ASC "; 



$Telpas_students=mysql_query($sql_student);

while ($row = mysql_fetch_assoc($Telpas_students)) 
{

$studentArray[]= $row['TelUserID'];

}


$course_student_results=[];
foreach ($category_course_arr as $cat_course_id => $cat_couse_arr)
{



$curl = new curl;
$restformat = 'json'; 
$domainname = 'https://ellpractice.com/intervene/moodle';

$token         =  '8699be2bb98a643b363c381899dd9393';
$functionname  =  'core_enrol_get_enrolled_users';
# Test 
  //$cat_course_id=20;
///////
$params = array('courseid' => $cat_course_id); // One course students list results

$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$respons = json_decode($resp,true);

// echo '==CourseIDResult:: <pre>';
// print_r($respons); die; 

$course_student_results[$catid]=$respons;

///////////
if(!empty($respons))
{  $i=1;
   foreach ($respons as $row) 
   { 

          if(in_array($row['id'],$studentArray))
          {
              $courseID=$cat_course_id; //#
              $Score= getProcessGread($courseID,$row['id']); 
              $IsComplete= _GetCompleteStatus($row['id']);
              $Student_row[]= array(  

              'fullname'=>$row['fullname'],
              'IsComplete'=> $IsComplete,
              'Score'=> number_format($Score),
              'cat_couse_id'=> number_format($cat_course_id),
              'telsas_student_id'=> $row['id'], //   [id] => 27
              'intervene_student_id'=> $row['idnumber'], //   [idnumber] => 27
              'intervene_student_email'=> $row['email'],
              '_course_cate_name'=> $cat_couse_arr['course_cate_name'],
              '_course_idnumber'=> $cat_couse_arr['idnumber'],
              '_course_id'=> $cat_course_id,


              );

          }
      
    }
 } 

 
}
////////////////////////
  // echo '@Student_row::<pre>'; 
  // print_r($Student_row);
  // die; 

 
 foreach ($Student_row as $key => $line)
 {
 	
    #$StudentId=$line['telsas_student_id']; //STOP
    $StudentId=$line['intervene_student_id'];

    unset($line['intervene_student_email']);
    unset($line['telsas_student_id']); 
    //unset($line['fullname']);  // 

    $line['_course_cate_name']=strtolower(trim($line['_course_cate_name']));

    $output_student_row_results[$StudentId]['student_full_name']=$line['fullname'];  //
    $output_student_row_results[$StudentId]['idno']=$line['intervene_student_id'];  //
    $output_student_row_results[$StudentId][$line['_course_cate_name']]=$line;
 	   
 }
   // intervene_student_id
  echo 'JSON::.output_student_row_results:';
   //print_r($output_student_row_results); die; 

 ///////////////////
  $json=json_encode($output_student_row_results);
  print($json);
  die; 


?>



<?php


 foreach ($output_student_row_results as $student_id =>$results_arr)
 {

      $_getScore= _getScore($results_arr['idno'],$results_arr['speaking']['_course_id']);
      $_getScoreWrite= _getScoreWrie($results_arr['idno'],$results_arr['writing']['_course_id']);

      ?>
      <tr>
      <td> <?php print($results_arr['student_full_name']);?></td>
      <td> <?php  echo (isset($results_arr['reading']['Score']))?$results_arr['reading']['Score'].'%':'NA' ?></td>
      <td> 
      <?php echo ($results_arr['listening']['Score'])?$results_arr['listening']['Score'].'%':'NA' ?> </td>
      <td> 

      <span id="listen<?php echo $results_arr['idno']?>"><?php echo $_getScore?></span><span>%</span> <br/>
      <a href="javascript:void(0)"  onclick="openAudioModal(<?php echo $results_arr['idno']?>)">Click To Grade</a></td><td>
      <span id="write<?php echo $results_arr['idno']?>"><?php echo $_getScoreWrite?></span><span>%</span> <br/>
      <a href="javascript:void(0)"  onclick="openWrittingModal(<?php echo $results_arr['idno']?>)">Click To Grade@</a> </td>
   </tr>


 <?php } ?>