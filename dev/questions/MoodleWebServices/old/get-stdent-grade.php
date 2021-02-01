<?php
extract($_REQUEST);

function getProcessGread($courseid,$studebID){
      $toTalgrade=0;
      $arrayGrd = array();
      $token = 'e5de33ad174f78ff0244e6ca67612455';
      $domainname = 'https://ellpractice.com/intervene/moodle';
      $functionname = 'gradereport_user_get_grade_items';
      require_once('cur-moodlel.php');

      $curl = new curl;

      $restformat = 'json'; 

      $params = array('courseid'=>$courseid,'userid' =>$studebID);

      $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

      $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

      $resp = $curl->post($serverurl . $restformat, $params);

      $responsG = json_decode($resp,true);

      // echo '===' ; die; 	

      //print_r($responsG); die;
      $total_questions=count($responsG);
      /*
      Percentage = (total [graderaw] ) * 100 / (total  [grademax] )
      **/ 

      $student_per=0;
      $toal_grad_max=0;
      $toal_grad_student_get=0;
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


      $progress=((100*array_sum($Arrgrade_graderaw))/array_sum($Arrgrade_max));

      return number_format($progress);
      }
?> 


