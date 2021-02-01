<?php
include('../inc/connection.php'); 
session_start();
extract($_REQUEST);
$studentArray=[];
$login_teacher_id=$_SESSION['login_id'];
$category_course_arr=[];
$arr_teacher_students=[];
$class='';
 function _getScore($table,$stuid,$course_id){

      $scored_grade=0;
      $divided_grade=0;
      $str="SELECT * FROM  $table WHERE course_id='".$course_id."' AND  intervene_student_id='".$stuid."' ORDER BY `question_id`"; 

      $audio=mysql_query($str);

      while ($row = mysql_fetch_assoc($audio)) 
      {

        $scored_grade=$row['scored_grade_number']+$scored_grade;
        $divided_grade=4 + $divided_grade;

      }
        return number_format((($scored_grade/$divided_grade)*100));
   }

   /*
   @ Check for No record for students
   @ dld_students_in_speaking_writing
   **/

$stArr=[];
$str="SELECT student_id FROM `students_x_class` WHERE  `class_id`  IN (SELECT class_id FROM `class_x_teachers` WHERE `teacher_id` = $login_teacher_id)";

    $q=mysql_query($str);

      while ($r = mysql_fetch_assoc($q)) {

        $stArr[$r['student_id']]= $r['student_id'];
      }


 $sTuAr= implode(',', $stArr);

   function dld_students_in_speaking_writing($teacher_id,$course_id,$table='telpas_student_speaking_grades')
   {  
      
      global  $sTuAr;
      $arr=[];
      $str="SELECT intervene_student_id  FROM $table WHERE course_id=$course_id AND intervene_student_id IN($sTuAr)";
      $results=mysql_query($str);
      while ($row=mysql_fetch_assoc($results)) {

      $arr[]=$row['intervene_student_id'];

      }

  //
  return $arr;

   }

//

 function _getGrade($stuid,$course_id,$studentId = NULL){

      
    $str="SELECT score_percent FROM  `telpas_course_score_logs` WHERE course_id='".$course_id."' AND 
       telpas_student_id='".$stuid."'"; 

      $res=mysql_query($str);
      $row = mysql_fetch_assoc($res);
      
      return number_format($row['score_percent']);
   }




///Get category course list

$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
require_once($Directorypath.'cur-moodlel.php');
$domainname = $webServiceURl;
$functionname = 'core_course_get_courses_by_field';
$token = '14432b87ba8ea3a4896dc7707d10e71d';
$curl = new curl;
$restformat = 'json'; 

$_SESSION['CategoryID'] = $category;
$params = array('field'=>'category','value'=>$category);

//print_r($params);
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$responsCourse = json_decode($resp,true);
// Check for Course in Category:: 
// echo 'Check for Course in Category:: <pre>';
// print_r($responsCourse);
// die; 


$students_in_speaking=[]; //get_students_involved_spearking
$students_in_writing=[]; //StudentInWritings

//Course Category responsde from moodle web-services
if(!empty($responsCourse))
{
   
    foreach ($responsCourse['courses'] as  $arr) 
    {   // get Students attempted list in speaking and writing 
        $cat_course_id=$arr['id']; //20;
        if(strtolower(trim($arr['fullname']))=='speaking'){
           //   'teacher_id:',$login_teacher_id ,'::cat_course_id:',$cat_course_id; 
            $students_in_speaking=dld_students_in_speaking_writing($login_teacher_id,$cat_course_id, 'telpas_student_speaking_grades');
          


        }elseif(strtolower(trim($arr['fullname']))=='writing'){

           $students_in_writing=dld_students_in_speaking_writing($login_teacher_id,$cat_course_id, 'telpas_student_writing_grades');

        }
        //
      
      $category_course_arr[$arr['id']]=array('course_cate_name'=>$arr['fullname'],'course_cat_data'=>$arr);
      // Unqiue category- 4 course
      $courseKey=strtolower(trim($arr['fullname']));
      $arrCategoryCourses[$courseKey]=$arr['id'];
    }

}
// >>
// echo  "students_in_speaking===" ,count($students_in_speaking) ,'::<pre>';
// print_r($students_in_speaking);
// echo '<br> students_in_writing::';
// print_r($students_in_writing);
// die; 



$arr_stu=[];


$str="SELECT class_id FROM `class_x_teachers` WHERE `teacher_id` = '".$login_teacher_id."'";
$res =mysql_query($str);
while($claaID=mysql_fetch_assoc($res)){

$arr_class[$claaID['class_id']]=$claaID['class_id'];

}
$clID= implode(',', $arr_class);

$str="SELECT student_id  FROM `students_x_class` WHERE  `class_id`  IN ($clID)";

$r= mysql_query($str);


while($rR=mysql_fetch_assoc($r)){

$arr_stu[$rR['student_id']]=$rR['student_id'];

}

$stuID= implode(',', $arr_stu);

$sql_student="SELECT tu.* ,s.* FROM Tel_UserDetails tu INNER JOIN students s ON tu.IntervenID=s.id WHERE tu.user_type='student' AND
 s.id IN ($stuID)"; 
$sql_student.=" ORDER BY  s.first_name,s.last_name ASC"; 

$Telpas_students=mysql_query($sql_student);

while ($row = mysql_fetch_assoc($Telpas_students)) 
{ 
    $studentId = $row['IntervenID'];
    $telpas_stu_ID = $row['TelUserID']; //IntervenID $row['IntervenID']
    $student_name  =  ucfirst($row['first_name']).' '.$row['last_name'];
    $arr_teacher_students[$row['IntervenID']] = ['student_name'=>$student_name];

    $reading_id=$arrCategoryCourses['reading'];
    $listening_id=$arrCategoryCourses['listening'];
    $speaking_course_id=$arrCategoryCourses['speaking'];
    $writing_course_id =$arrCategoryCourses['writing'];

    $_getScore      =  _getScore('telpas_student_speaking_grades',$studentId,$speaking_course_id);

    $_getScoreWrite =  _getScore('telpas_student_writing_grades',$studentId,$writing_course_id);
  
    $results_arr['idno'] = $row['IntervenID'];
    $readingScore=_getGrade($telpas_stu_ID,$reading_id,$studentId); 

    $listeningScore=_getGrade($telpas_stu_ID,$listening_id,$studentId);
    //[25-2-2020]:Check for student- attempted spearking& writing 
   // echo 'studentId', $studentId  , '::telpas_stu_ID:' , $telpas_stu_ID = $row['TelUserID'];; die; 
   $spearking_column_class=(intval($_getScore>=1))?'graded':'bgcolor';

     $spearking_column_class='speaking-not-attempted';
     $writing_column_class='writing-not-attempted'; //IMP 

    if(intval($_getScore>=1)){
      $spearking_column_class='graded'; // score exist >=1 %.

    }else if(in_array($studentId, $students_in_speaking)){

        $spearking_column_class='bgcolor pending-grading'; // Pendig to grade for stude
    }
    // Check for writing:: Grade
     $writing_column_class='writing-not-attempted'; //IMP 

      if(intval($_getScoreWrite>=1)){
      $writing_column_class='graded'; // score exist >=1 %.

    }else if(in_array($studentId, $students_in_writing)){

        $writing_column_class='writing bgcolor pending-grading'; // Pendig to grade for stude
    }

  

 ?>
 <?php //=(intval($_getScore>=1))?'graded':'bgcolor' ?>
 <?php  //(intval($_getScoreWrite>=1))?'graded':'bgcolor'?>


    <tr>
    <td><?php print($student_name);?></td>

    <td> 
      <span id="reading<?php echo $telpas_stu_ID?>"></span>
    <?php if($readingScore > 0) { ?>
      
      <span id="reading<?php echo $telpas_stu_ID?>">
           <?php
           if($readingScore >= 1 && $readingScore <= 25)
              echo '<span  style="color:#d9534f;font-weight:bold">Beginner</span>'; 
           else if($readingScore >= 26 && $readingScore <= 50)
                echo '<span style="color:#f0ad4e;font-weight:bold">Intermediate </span>'; 
           else if($readingScore >= 51 && $readingScore <= 75)
                echo '<span style="color:#337ab7;font-weight:bold">Advanced </span>'; 
           else if($readingScore >= 76 && $readingScore <= 100)
                echo '<span style="color:#5cb85c;font-weight:bold">Advanced High </span>'; 
           else
               echo 'N/A';
         ?>
        
      </span>
      
      <br/>
    
    <?php } 
    else {
      ?>
    <a href="javascript:void(0)"  onclick="_getScore('reading',<?php print($reading_id)?>,<?php echo $telpas_stu_ID?>,<?php print($studentId)?>)" class="reading<?php echo $telpas_stu_ID?>">View Score</a>
<?php 
} ?>
  </td> 

<td>
<span id="listening<?php echo $telpas_stu_ID?>"></span>
<?php if($listeningScore > 0) { ?>
     <span id="listening<?php echo $telpas_stu_ID?>">
         <?php
           if($listeningScore >= 1 && $listeningScore <= 25)
              echo '<span  style="color:#d9534f;font-weight:bold">Beginner</span>'; 
           else if($listeningScore >= 26 && $listeningScore <= 50)
                echo '<span style="color:#f0ad4e;font-weight:bold">Intermediate </span>'; 
           else if($listeningScore >= 51 && $listeningScore <= 75)
                echo '<span style="color:#337ab7;font-weight:bold">Advanced </span>'; 
           else if($listeningScore >= 76 && $listeningScore <= 100)
                echo '<span style="color:#5cb85c;font-weight:bold">Advanced High </span>'; 
            else
               echo 'N/A';
         ?></span><br/>
<?php }  else{?>

    <a href="javascript:void(0)"  onclick="_getScore('listening',<?php print($listening_id)?>,<?php echo $telpas_stu_ID?>,<?php print($studentId)?>)" class="listening<?php echo $telpas_stu_ID?>">View Score</a>

<?php }?>

</td>
    
    <td id="speaking_<?php echo $studentId;?>_<?php echo $speaking_course_id?>" class="<?=$spearking_column_class ?>" 
      title="<?=($spearking_column_class=='speaking-not-attempted')?'Not attempted':'' ?>"   > 
       <?php  if($spearking_column_class!='speaking-not-attempted'){?>

      <span id="listen<?php echo $results_arr['idno']?>">
           <?php
           if($_getScore >= 1 && $_getScore <= 25)
              echo '<span  style="color:#d9534f;font-weight:bold">Beginner</span>'; 
           else if($_getScore >= 26 && $_getScore <= 50)
                echo '<span style="color:#f0ad4e;font-weight:bold">Intermediate </span>'; 
           else if($_getScore >= 51 && $_getScore <= 75)
                echo '<span style="color:#337ab7;font-weight:bold">Advanced </span>'; 
           else if($_getScore >= 76 && $_getScore <= 100)
                echo '<span style="color:#5cb85c;font-weight:bold">Advanced High </span>'; 
            else
               echo 'N/A';
         ?>
          
        </span>
       <br/>

    <a href="javascript:void(0)" 
     onclick="openAudioModal(<?php print($studentId)?>,<?php echo $speaking_course_id?>)"> <?=(intval($_getScore>=1))?'Change Grade':'Click To Grade' ?></a>

      <?php }else{ ?>
<span  title="<?php echo $spearking_column_class; ?>"></span> Awaited</span>
         <?php } ?>
    
   </td>



    <td id="writing_<?php echo $studentId;?>_<?php echo $writing_course_id?>" class="<?=$writing_column_class?>" title="<?= ($writing_column_class=='writing-not-attempted')?"Not attempted":""; ?> "  >

       <?php if($writing_column_class=='writing-not-attempted'){ ?>
       <span  title="<?php echo $writing_column_class; ?>"></span> Awaited</span>

     <?php  }else{ ?>
      <span id="write<?php echo $results_arr['idno']?>">
          
          <?php
           if($_getScoreWrite >= 1 && $_getScoreWrite <= 25)
              echo '<span  style="color:#d9534f;font-weight:bold">Beginner</span>'; 
           else if($_getScoreWrite >= 26 && $_getScoreWrite <= 50)
                echo '<span style="color:#f0ad4e;font-weight:bold">Intermediate </span>'; 
           else if($_getScoreWrite >= 51 && $_getScoreWrite <= 75)
                echo '<span style="color:#337ab7;font-weight:bold">Advanced </span>'; 
           else if($_getScoreWrite >= 76 && $_getScoreWrite <= 100)
                echo '<span style="color:#5cb85c;font-weight:bold">Advanced High </span>'; 
            else
               echo 'N/A';
         ?>
          
       
      </span> <br/>
      
    <a href="javascript:void(0)"  onclick="openWrittingModal(<?php print($studentId);?>,<?php echo $writing_course_id;?>)"><?=(intval($_getScoreWrite>=1))?'Change Grade':'Click To Grade' ?></a>
     <?php  } ?>

  </td>

  </tr>
    <?php } ?>