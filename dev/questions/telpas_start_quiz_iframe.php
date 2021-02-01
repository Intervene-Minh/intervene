<?php

include("iframe-telpass-header.php");
//print_r($_SESSION);
if (!$_SESSION['student_id'])
{
    header('Location: login.php');
    exit;
}

	 $token = '02104727338432bcc247cce5338abb18';
   $domainname = 'https://ellpractice.com/intervene/moodle';
   $functionname = 'core_course_get_contents';
   require_once('cur-moodlel.php');
   $curl = new curl;
   $restformat = 'json'; 
   
   $params = array('courseid' =>$_GET['cid']);
   
   $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
   
   $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
   
   $resp = $curl->post($serverurl . $restformat, $params);
   
   $respons = json_decode($resp,true);
   


  //print_r($respons); die;
   
  $dataReturn=$respons[1]['modules'][0];

  $dataReturn['url'];

  $dataReturn['modname'];
   ?>


<div id="home main" class="clear fullwidth tab-pane fade in active">
<div class="container">
<div class="row"> 
<iframe src="<?php echo $dataReturn['url'];?>" width="1200" height="1200" frameborder="0" allowfullscreen="allowfullscreen" allow="microphone ; camera ;">
</iframe>

</div>
</div>
</div>
<script src="../iframeTemplate/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>