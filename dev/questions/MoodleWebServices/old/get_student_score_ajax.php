<?php

  include('../inc/connection.php'); 
  session_start();
  extract($_REQUEST);

  $toTalgrade=0;
  $student_per=0;
  $toal_grad_max=0;
  $toal_grad_student_get=0;
  $arrayGrd = array();

  $token = 'e5de33ad174f78ff0244e6ca67612455';
  $domainname = 'https://ellpractice.com/intervene/moodle';
  $functionname = 'gradereport_user_get_grade_items';
  require_once('cur-moodlel.php');

  $curl = new curl;

  $restformat = 'json'; 

  $params = array('courseid'=>$course_id,'userid' =>$studentID);

  $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

  $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

  $resp = $curl->post($serverurl . $restformat, $params);

  $responsG = json_decode($resp,true);

  $total_questions=count($responsG);

  foreach ($responsG['usergrades'][0]['gradeitems'] as $key=>$arr){
    //print_r($arr); die;
    $toal_grad_max=$toal_grad_max+$arr['grademax'];
    $Arrgrade_max[]=$arr['grademax'];

    $Arrgrade_graderaw[]=$arr['graderaw'];

    $toal_grad_student_get=$toal_grad_max+$arr['graderaw'];
    //$toTalgrade=$arrayGrd[$i]+$toTalgrade;

}
  if($toal_grad_max>0){

  	 $student_percentage=(100*$toal_grad_student_get)/$toal_grad_max;
  }


$score = number_format ( (100*array_sum($Arrgrade_graderaw))/array_sum($Arrgrade_max));
if($score > 0 ){


mysql_query("DELETE FROM `telpas_course_score_logs` WHERE course_id='".$course_id."' AND telpas_student_id= '".$studentID."'");  

$str="INSERT INTO `telpas_course_score_logs` SET `course_type`='".$type."',`teacher_id`='".$_SESSION['login_id']."',`course_cat_id`='".$_SESSION['CategoryID']."',`course_id`= '".$course_id."',`telpas_student_id`='".$studentID."',  `intervene_student_id`='".$intervene_student_id."', `score_percent`='".$score."'";
mysql_query($str);
echo $score.'%';
}
else{
  echo 'NA';
}



