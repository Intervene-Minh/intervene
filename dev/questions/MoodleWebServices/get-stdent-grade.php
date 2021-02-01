<?php
extract($_REQUEST);
 $Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';


      require_once  ($Directorypath.'cur-moodlel.php'); 


function getProcessGread($courseid,$studebID){
            $toTalgrade=0;
            $arrayGrd = array();
            $token = 'e5de33ad174f78ff0244e6ca67612455';
            global $webServiceURl;

            $domainname = $webServiceURl;
            $functionname = 'gradereport_user_get_grade_items';


            $curl = new curl;

            $restformat = 'json'; 

            $params = array('courseid'=>$courseid,'userid' =>$studebID);

            $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

            $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

            $resp = $curl->post($serverurl . $restformat, $params);

            $responsG = json_decode($resp,true);
            /*total question*/
            $total_questions=count($responsG);

            foreach ($responsG['usergrades'][0]['gradeitems'] as $key=>$arr){
                  /*get total active question array*/
                  if(empty($arr['gradeishidden']) && !empty($arr['itemname']) ){

                        $TotalQuestion[]= $arr;
                  }

                  if(!empty($arr['weightraw']) && !empty($arr['itemname'])){


                        if($arr['weightraw'] >0){
                              $AttempQuestion[]= $arr;
                        }
                  }
            }

            /*attemp question  count*/
            $attemp_question =count($AttempQuestion);
            /*total question  count*/
            $total_question =count($TotalQuestion);
            $question_Percantage=((1*$total_question)/100);

            $progress=ceil((($attemp_question)/$question_Percantage));
            return number_format($progress);

  }



      function get_total_question($courseid,$studebID){

             $token = 'e5de33ad174f78ff0244e6ca67612455';
            global $webServiceURl;
            $domainname = $webServiceURl;
            $functionname = 'gradereport_user_get_grade_items';


            $curl = new curl;

            $restformat = 'json'; 

            $params = array('courseid'=>$courseid,'userid' =>$studebID);

            $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

            $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

            $resp = $curl->post($serverurl . $restformat, $params);

            $responsG = json_decode($resp,true);
            /*total question*/
            $total_questions=count($responsG);

            foreach ($responsG['usergrades'][0]['gradeitems'] as $key=>$arr){
                  /*get total active question array*/
                  if(empty($arr['gradeishidden']) && !empty($arr['itemname']) ){
                        $TotalQuestion[]= $arr;
                  }

                 
            }

            /*total question  count*/
            $total_question =count($TotalQuestion);
            return $total_question;

             }



   function getattemp($courseid,$studebID){


      $toTalgrade=0;
      $arrayGrd = array();
      $token = 'e5de33ad174f78ff0244e6ca67612455';
      global $webServiceURl;

      $domainname = $webServiceURl;
      $functionname = 'gradereport_user_get_grade_items';


      $curl = new curl;

      $restformat = 'json'; 

      $params = array('courseid'=>$courseid,'userid' =>$studebID);

      $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

      $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

      $resp = $curl->post($serverurl . $restformat, $params);

      $responsG = json_decode($resp,true);
      /*total question*/
      $total_questions=count($responsG);

      foreach ($responsG['usergrades'][0]['gradeitems'] as $key=>$arr){

      if(empty($arr['status']) && !empty($arr['itemname'])){


     
                  $AttempQuestion[]= $arr;
            
      }
      }
      /*attemp question  count*/
      $attemp_question =count($AttempQuestion);
      return $attemp_question;
            
  }
?> 


