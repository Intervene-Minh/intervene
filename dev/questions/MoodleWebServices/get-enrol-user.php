<?php


$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
require_once($Directorypath.'cur-moodlel.php');
session_start();
extract($_REQUEST);
$studentArray=[];
$student_rows=[];
 $login_teacher_id=$_SESSION['login_id'];
require_once('cur-moodlel.php');

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




$sql_student="SELECT tu.* ,s.* FROM Tel_UserDetails tu INNER JOIN students s ON tu.IntervenID=s.id WHERE tu.user_type='student' AND s.teacher_id=".$login_teacher_id;                 
$Telpas_students=mysql_query($sql_student);

while ($row = mysql_fetch_assoc($Telpas_students)) 
{

$studentArray[]= $row['TelUserID'];

}
///////Student list in a course///////

$curl = new curl;
$restformat = 'json'; 
$domainname = $webServiceURl;

$token         =  '8699be2bb98a643b363c381899dd9393';
$functionname  =  'core_enrol_get_enrolled_users';

$params = array('courseid' =>$courseID,);

$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$respons = json_decode($resp,true);

if(!empty($respons))
{  $i=1;
   foreach ($respons as $row) { 

      if(in_array($row['id'],$studentArray))
         {
          $Score= getProcessGread($courseID,$row['id']); 

         $IsComplete= _GetCompleteStatus($row['id']);?>
       <tr>
    
      <td>
         <span><?php print($row['fullname'])?></span>
      </td>
      <td> <?php echo $IsComplete?> </td>
      <td> <?php ?><?php print(number_format($Score))?>% </td>
      
   </tr>  <?php  

         
         }
      
     }
 } 

 ?>