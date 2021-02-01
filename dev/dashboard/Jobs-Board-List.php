<?php

   @extract($_GET) ;

   @extract($_POST) ;

   $error='';

   $page_name='Jobs Board';

   include("header.php");

   include("Tutoring.inc.php"); // 16-sept-2019

   include("curl.function.php"); // 16-sept-2019

   $ses_module_arr= array('homework' =>'Homework Help','intervention' =>'Intervention' );

   //////////Validate Site Access//////////

   if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){

     header("Location:".$tutor_regiser_page);exit;

   }
   
   include('inc/sql_connect.php');
 $p2db = p2g(); // tutor_id test_type quiz_test_id status
 

   /* NA START */

function getGradeName($gradeId) {

   $sql = "SELECT * 

             FROM terms 

            WHERE id = '".$gradeId."'";

   $query = mysql_query($sql);

   $row = mysql_fetch_assoc($query);

   return $row['name']; 

 } // getGradeName

 /* NA END */



   //echo $_SESSION['ses_teacher_id'];

   $curr_time= date("Y-m-d H:i:s"); #currTime

   $login_role = $_SESSION['login_role'];

   $page_name="Jobs Board List";

   // action

   if(!isset($_SESSION['ses_teacher_id'])){

       header('Location:logout.php');exit;

   }

   

   $id = $_SESSION['ses_teacher_id'];

   /////Claim a Job/////

   include("inc/braincert_api_inc.php");

   

   $tutor_profie = mysql_fetch_assoc(mysql_query(" SELECT * FROM tutor_profiles WHERE tutorid=".$id));

   if(isset($_POST['submit_claim'])) {

   

     $sid=$_POST['submit_claim'];// Tutor job :session

     $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'

     // Validate::Another Tutor accept.

     $ses_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND id=".$sid));

     $clss_id=$ses_det['braincert_class']; 

     $drhomework_ses_id=$ses_det['drhomework_ref_id'];

     $client_id=$ses_det['Tutoring_client_id'];

     $tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$tutor_id));

     //  ses time 

     $session_time=$ses_det['ses_start_time'];

     // echo 'start time-'.$session_time; 

     $time=strtotime($session_time);

     //$time = strtotime('2018-04-18 16:10:00');

     $startTime = date("Y-m-d H:i:s", strtotime('-55 minutes', $time));

     $endTime = date("Y-m-d H:i:s", strtotime('+55 minutes', $time));

    //echo  '<pre>   '. $startTime.'End times#'.$endTime ;  die;

      

       $sql="SELECT * FROM int_schools_x_sessions_log WHERE 1 ";

       $sql.=" AND ses_start_time >= '$startTime' and ses_start_time <= '$endTime' ";

       $sql.=" AND tut_teacher_id='$tutor_id' ";

       $error_msg=  '<pre>'.$sql ;

       $tot_ses_in_55min= mysql_num_rows(mysql_query($sql));

       

      if($tot_ses_in_55min>0){

          $error= 'Session over lapped,Can not Claimed. !'; //die;

      }

       

      if($ses_det['tut_teacher_id']==0){

   

   $board='stoped';

   $query = mysql_query("UPDATE int_schools_x_sessions_log SET 

   braincert_board_url='$board',tut_teacher_id='".$tutor_id."',"

   . "app_url='".$tut_th['url_aww_app']."',tut_accept_time='$curr_time',"

   . "modified_date='$curr_time' WHERE id='$sid'");

   // in studentsLog of Slot

   $up= mysql_query("UPDATE int_slots_x_student_teacher SET tut_teacher_id='".$tutor_id."',"

   . "tut_admin_id='1' WHERE slot_id='$sid'"); 

   $error="You Accepted this session";



/* check type os=f session*/ 

if($client_id=='Drhomework123456'){







/* call update status session*/

$apiUrl="https://drhomework.com/parent/updateTutorStatusApi.php";

$postFields=array('tutor_id'=>$tutor_id,'drhomework_ref_id'=>$drhomework_ses_id);

HitPostCurl($apiUrl,$postFields);

/* send notification*/



$apiUrl="https://drhomework.com/parent/getParentEmailApi.php";

$postFields=array('session_id'=>$drhomework_ses_id);

$data=HitPostCurl($apiUrl,$postFields);

$res=json_decode($data,true);

include("emailerPage.php"); 

_parentNotifyEmail($session_time,$res['name'],$res['email']);



}

 }

      else{

         $error="Already assgined job to another tutor.!"; 

      }

    /// 

   }

   

    if(isset($_POST['submit_observer_claim'])) {

   

     $sid=$_POST['submit_observer_claim'];// Tutor job :session

     $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'

     // Validate::Another Tutor accept.

     $ses_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND id=".$sid));

     $clss_id=$ses_det['braincert_class']; 

     $drhomework_ses_id=$ses_det['drhomework_ref_id'];

     $client_id=$ses_det['Tutoring_client_id'];

     $tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$tutor_id));

     //  ses time 

     $session_time=$ses_det['ses_start_time'];

     // echo 'start time-'.$session_time; 

     $time=strtotime($session_time);

     //$time = strtotime('2018-04-18 16:10:00');

     $startTime = date("Y-m-d H:i:s", strtotime('-55 minutes', $time));

     $endTime = date("Y-m-d H:i:s", strtotime('+55 minutes', $time));

    //echo  '<pre>   '. $startTime.'End times#'.$endTime ;  die;

      

       $sql="SELECT * FROM int_schools_x_sessions_log WHERE 1 ";

       $sql.=" AND ses_start_time >= '$startTime' and ses_start_time <= '$endTime' ";

       $sql.=" AND tut_teacher_id='$tutor_id' ";

       $error_msg=  '<pre>'.$sql ;

       $tot_ses_in_55min= mysql_num_rows(mysql_query($sql));

       

      if($tot_ses_in_55min>0){

          $error= 'Session over lapped,Can not Claimed. !'; //die;

      }

       

      if($ses_det['tut_observer_id']==0){

   

   $board='stoped';

   $query = mysql_query("UPDATE int_schools_x_sessions_log SET 

   braincert_board_url='$board',tut_observer_id='".$tutor_id."',"

   . "app_url='".$tut_th['url_aww_app']."',tut_accept_time='$curr_time',"

   . "modified_date='$curr_time' WHERE id='$sid'");

   // in studentsLog of Slot

   $up= mysql_query("UPDATE int_slots_x_student_teacher SET tut_observer_id='".$tutor_id."',"

   . "tut_admin_id='1' WHERE slot_id='$sid'"); 

   $error="You Accepted this session";



/* check type os=f session*/ 

if($client_id=='Drhomework123456'){







/* call update status session*/

$apiUrl="https://drhomework.com/parent/updateTutorStatusApi.php";

$postFields=array('tutor_id'=>$tutor_id,'drhomework_ref_id'=>$drhomework_ses_id);

HitPostCurl($apiUrl,$postFields);

/* send notification*/



$apiUrl="https://drhomework.com/parent/getParentEmailApi.php";

$postFields=array('session_id'=>$drhomework_ses_id);

$data=HitPostCurl($apiUrl,$postFields);

$res=json_decode($data,true);

include("emailerPage.php"); 

_parentNotifyEmail($session_time,$res['name'],$res['email']);



}

 }

      else{

         $error="Already assgined job to another tutor.!"; 

      }

    /// 

   }

   if(isset($_POST['delete-user'])){

    $arr = $_POST['arr-user'];

    if($arr!=""){

      //$query = mysql_query("DELETE FROM demo_users WHERE id IN ('$arr')", $link);

               

               //// Delete Role Table...

               $query = mysql_query("DELETE FROM demo_users WHERE id IN ($arr)", $link);

    }

           

           echo "<script>alert('#Record deleted..');location.href='manager_demo_user.php';</script>";

           ///

           

   }

   

   

   

   $schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");

   #######Update: feb 19######

   

                                   

                              

                                 $curr_time= date("Y-m-d H:i:s"); #currTime

                               

                                   $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";

                                  // $qq.=" AND tut_status='STU_ASSIGNED' ";

                                  

                                

                                   if(isset($_GET['id']))

                                   $qq.=" AND id='".$_GET['id']."' ";#only Assigned

                                   else{

                                       $qq.=" AND ses_start_time>'$curr_time' ";

                                    //$qq.=" AND tut_teacher_id IN(0,$id)";

                                        $qq.=" AND (tut_teacher_id='0' ";
$qq.=" OR (add_observer='1' and tut_observer_id IS NULL)) ";
                                   $qq.=" ORDER BY ses_start_time ASC ";    

                                   }

                             

                                  /// echo $qq;

                                   

                                   

                                   /////////////////////////

                                   $session_type=(isset($session_type))?$session_type:"upcoming" ;

                                   if(isset($_GET['action'])&&$_GET['action']=="Search"){

                                     $tutor= $_SESSION['ses_teacher_id'];

                                     // echo $session_type ; die;

                              

                                     if($session_type=="past"){

                                        $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";

                                   

                                   $qq.=" AND ses_start_time<'".$curr_time."'";

                                     $qq.=" AND tut_teacher_id IN(0,$tutor)";#only Assigned

                                   $qq.=" ORDER BY ses_start_time DESC "; 

                                  

                                     }elseif($session_type=="upcoming"){

                                       $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";

                                    

                                   $qq.=" AND ses_start_time>'".$curr_time."'";

                                     $qq.=" AND tut_teacher_id IN(0,$tutor)";#only Assigned

                                   $qq.=" ORDER BY ses_start_time ASC ";   

                                     }elseif($session_type=="all"){

                                      $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";

                                     $qq.=" AND tut_teacher_id IN(0,$tutor)";#only Assigned

                                   

                                   $qq.=" ORDER BY ses_start_time ASC ";   

                                  

                                     }

                                   }

                                   

                                   //echo $qq; 

                    //////////////

                    $results = mysql_query($qq);

                    $tot_record=mysql_num_rows($results);



                    // echo '<pre>',$qq;echo '<br/>' ; 

       ?>

}

<script>

   function myFunction() {

       var txt;

       var r = confirm("Press a button!\nEither OK or Cancel.\nThe button you pressed OK,Claim Tutor Job.");

       if (r == true) {

          // txt = "You pressed OK!";

           return true;

         

       } else {

            //txt = "You pressed Cancel!";

          // alert('yynot submit');

          return false;

          

       }

      // document.getElementById("demo").innerHTML = txt;

   }

   ///////////

   

    $(document).ready(function(){

      $('#delete-user').on('click',function(){

        var count = $('#form-manager .checkbox:checked').length;

        $('#arr-user').val("");

        $('#form-manager .checkbox:checked').each(function(){

          var val = $('#arr-user').val();

          var id = $(this).val();

          $('#arr-user').val(val+','+id);

        });

        var str = $('#arr-user').val();

        $('#arr-user').val(str.replace(/^\,/, ""));

        return confirm('Are you want to delete '+count+' user?');

      });

    });

           

     /////////////////      

         function sent_form(path, params, method) {

       method = method || "post"; // Set method to post by default if not specified.

   

       // The rest of this code assumes you are not using a library.

       // It can be made less wordy if you use one.

       var form = document.createElement("form");

       form.setAttribute("method", method);

       form.setAttribute("action", path);

     form.setAttribute("target", "_blank");

   

       for(var key in params) {

           if(params.hasOwnProperty(key)) {

               var hiddenField = document.createElement("input");

               hiddenField.setAttribute("type", "hidden");

               hiddenField.setAttribute("name", key);

               hiddenField.setAttribute("value", params[key]);

   

               form.appendChild(hiddenField);

            }

       }

   

       document.body.appendChild(form);

       form.submit();

   }

   

   ///

   $(document).ready(function() {

      $('#setdate').change(function() {

        var parentForm = $(this).closest("form");

        if (parentForm && parentForm.length > 0)

          parentForm.submit();

      });

   });

</script>

<div id="main" class="clear fullwidth">

   <div class="container">

      <div class="row">

         <div id="sidebar" class="col-md-4">

            <?php include("sidebar.php"); ?>

         </div>

         <!-- /#sidebar -->

         <div id="content" class="col-md-8">

            <!--                                <div class="table-responsive text-right">

               <a title="Jobs Board Calendar" href="jobs-board-calendar.php"

                        class="btn btn-success btn-xs" >Jobs Board Calendar</a> 

                     

                        &nbsp;</div>-->

            <form onsubmit="return myFunction();"  id="form-manager" class="content_wrap" action="" method="post">

               <div class="ct_heading clear">

                  <h3><?=$page_name?>(<?=$tot_record?>)</h3>

               </div>

               <!-- /.ct_heading -->

               <div class="clear">

                  <?php

                     if(isset($error)&&$error != '') {

                      echo '<p class="error">'.$error.'</p>';

                     } ?>

                  <table class="table-manager-user col-md-12">

                     <colgroup>

                        <col width="300">
                        <col width="150">
                        <col width="200">

                        <col width="120">

                     </colgroup>

                     <tr>

                        <th>cSessions Date/Time</th>

                        <th>Claim Tutor Gig</th>
                        <th>Status</th>

                        <th>Session details</th>

                     </tr>

                     <?php

                        $tutor= $_SESSION['ses_teacher_id'];                      

                        if( mysql_num_rows($results) > 0 ) {

                          while( $row = mysql_fetch_assoc($results) ) {

                        

                        $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=".$row['teacher_id']));

                        $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));

                         $tot_std=($tot_std>0)?$tot_std:"XX";

                        $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));    

                        $int_school= mysql_fetch_assoc(mysql_query("SELECT SchoolName FROM schools WHERE SchoolId=".$row['school_id']));     

                        //$quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));

                        $quiz=mysql_fetch_assoc(mysql_query("SELECT q. * , l.id AS lesid, l.name, l.file_name

                        FROM `int_quiz` q

                        LEFT JOIN master_lessons l ON q.lesson_id = l.id

                        WHERE q.id =".$row['quiz_id'])); 

                        /// Lession Rows////

                        $lesson_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `master_lessons` WHERE id=".$row['lesson_id'])); 

                        

                           $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";

                        $q.=" WHERE ses.slot_id='".$row['id']."' ";

                        $resss=mysql_query($q);

                        $stud_str=array(); // middle_name

                        while ($row2=mysql_fetch_assoc($resss)) {

                        

                        

                        $stud_str[]=$row2['first_name'].' '.$row2['middle_name'];

                        }  

                        $stdList=(count($stud_str)>0)? implode(",", $stud_str):"NA";

                        // G:i a   

                        // special_notes

                        $row['special_notes']=(!empty($row['special_notes']))?$row['special_notes']:"NA";

                        $sesStartTime=$row['ses_start_time'];

                        $in_sec= strtotime($sesStartTime) - strtotime($curr_time);

                        ///lesson dowload URL

                        $lesson_download="https://intervene.io/questions/uploads/lesson/".$lesson_det['file_name'];// 4358Question.pdf

                        

                        /////////////////////////////////

                        //http://drhomework.com/parent/ Session

                        if($row['Tutoring_client_id']=='Drhomework123456'){

                        $Class_from='Homework Help'; // From  -- http://drhomework.com

                        $lesson_download='';

                        $detail_url="https://tutorgigs.io/dashboard/tutoring_details.php?sid=".$row['id'];

                        $intervene_btn_clss='btn btn-danger btn-xs';

                        $Sessiontype='Drhomework';

                        

                        

                        }else{ 

                        $Class_from='Intervention';// intervention , Intervene

                        $intervene_btn_clss='btn btn-primary btn-xs';

                        $Sessiontype='Intervention';

                        $detail_url='#';

                        $detail_url="https://tutorgigs.io/dashboard/tutoring_details.php?sid=".$row['id'];

                        

                        

                        // Download-

                        

                        }

                        //////////////////////////

                        

                        

                        ?>                           

                     <tr>

                        <td>

                           <span> <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?></span>   <br>

                           <a  href="<?=$detail_url?>" class="btn btn-success btn-xs">

                           <?=$SesTime=date_format(date_create($row['ses_start_time']), 'h:i a');#?></a> 



                           <br/>

                           <?php //=$boardTypeArr['newrow']?>

                          

                           <span><strong class="text-primary" >Virtual board</strong>:<br>
                              <?php  if($row['board_type'] == 'wiziq')
                                           echo ucwords($row['board_type']);
                                        else if($row['board_type'] == 'newrow')
                                        {
                                            if($row['ios_newrow'] == 1)
                                                echo 'IOS Newrow';
                                            else
                                                echo ucwords($row['board_type']);
                                        }
                           ?>
                           </span><br/>

                           <span><strong class="text-primary" >Class From:</strong> <span class="<?=$intervene_btn_clss?>"> <?=$Class_from ?> </span> </span>  <br>

                           <!-- NA START -->

                           <span><strong class="text-primary" >Grade: </strong>:<?php echo getGradeName($row['grade_id']); ?> </span><br/>

                           <span><strong class="text-primary" >Duration: </strong>:<?php echo $row['session_duration']; ?> mins</span><br/>

                           <!-- NA END -->

                           <?php   if($row['tut_teacher_id']==0&&$in_sec>0&&$in_sec<172800){ ?>

                           <img alt="Hot Job" style=" height:40px;" title="Hot Job"

                              src="../hot-job.png" />

                           <?php }?>

                           <!--  Intervene sessionInfo -->

                           <br/>

                           <?php  if($row['type']!='drhomework'): ?>

                           <span style=" display:none; "> 

                           <strong class="text-primary">

                           Objective:</strong> <?=$quiz['objective_name']?>

                           <br/>

                           <a href="<?=$lesson_download?>" 

                              class="btn btn-danger btn-xs">Download-<?=$lesson_det['name']?></a> <br/>

                           </span>

                           <?php endif ;?>



                           <a  class="btn btn-danger btn-xs viewSession" href="javascript:void(0)" SessionID="<?=$row['id']?>"  action="<?=$Sessiontype?>">Session Detail & Downloads</a>

                        </td>

                        <td align="center"  >

                           <?php  if($in_sec>0){
                               
                               if($tutor_profie['block'] == 1)
                                   $tutor_block = 1;
                               else
                                   $tutor_block ='';
$allow_claim = 0;
$cer_approve = '';
$grade_pass_status = '';
$pass_status = '';
$claim_message = "<ul style=text-align:left>";
                                $claim_type = '';
                                  $grade_test_id = '';
                                $grade_test = '';
                            if($row['tut_teacher_id']==0){
                                
                                $tests_sql =  mysqli_query($p2db,"select *from `tests` where IsActive = '1'");
                              
                                while($test_data = mysqli_fetch_object($tests_sql))
                                {
                                  $subjects = $test_data->SubjectID;
                                    if(!empty($subjects))
                                    {
                                        $subjectArr = explode(":", $subjects);
                                        if(in_array($row['grade_id'], $subjectArr))
                                        {
                                          $grade_test_id = $test_data->ID;
                                          $grade_test = $test_data->Name;
                                            break;
                                        }
                                    }
                                }
                      
                               
//                                    $grade_sql = " SELECT * FROM `terms` WHERE id='".$row['grade_id']."'";
//                                    $get_grade = mysql_fetch_object(mysql_query($grade_sql));
//                                 
//                                    
//                                    if(strpos($get_grade->name, 'Math') !== false){
//                                       $grade_test = 'Math';
//                                    } 
//                                    else if(strpos($get_grade->name, 'Reading') !== false || strpos($get_grade->name, 'Writing') !== false){
//                                        $grade_test = 'English';
//                                    }
                                
                                if(!empty($grade_test_id) )
                                {
                                   // $grade_test_data = mysqli_fetch_object( mysqli_query($p2db,"select *from `tests` where Name = '".$grade_test."'"));
                                    $grade_test_sql = " SELECT * FROM `tutor_tests_logs` WHERE  tutor_id='".$_SESSION['ses_teacher_id']."' AND quiz_test_id='".$grade_test_id."' AND status='completed'";
                                    $grade_test_result = mysqli_query($p2db,$grade_test_sql);
                                    $is_grade_test_result = mysqli_num_rows($grade_test_result);
                                    $grade_test_result_data = mysqli_fetch_object($grade_test_result);
                                    
                                    if($grade_test_result_data->block == 1)
                                        $tutor_block = 1;
                                    else
                                       $tutor_block = ''; 
                                 
                                    
                                    if($is_grade_test_result > 0)
                                    {
                                     if($grade_test_result_data->pass_status == 'pass')
                                        $grade_pass_status = 'pass';
                                     else if($grade_test_result_data->pass_status == 'fail')
                                        $grade_pass_status = 'fail'; 
                                     else
                                       $claim_message .= "<li>Pass ".$grade_test." test</li>";
                                    }
                                    else {
                                    $claim_message .= "<li>Pass ".$grade_test." test</li>";    
                                    }
                                    
                                  
                                }
                   
                                
                                     
                                if(empty($row['bilingual_test']) && empty($row['certificate']) )
                                {
                                    if(!empty($grade_test_id) )
                                    {
                                       if($is_grade_test_result > 0 && $grade_test_result_data->pass_status == 'pass')
                                          $allow_claim = 1;
                                       else
                                          $allow_claim = ''; 
                                    }
                                    else
                                       $allow_claim = 1;
                                    
                                }
                                else if(!empty($row['bilingual_test']) && empty($row['certificate']) )
                                {
                                    $sql = " SELECT * FROM `tutor_tests_logs` WHERE tutor_id='".$_SESSION['ses_teacher_id']."' AND quiz_test_id = '73' AND status='completed' ";
                                    $get_result = mysqli_query($p2db,$sql);
                                    $teacher_test_result = mysqli_num_rows($get_result);
                                    
                                     $teacher_test_result_data = mysqli_fetch_object($get_result);
                                    
                                    if($teacher_test_result_data->block == 1)
                                        $bilingual_block = 1;
                                    else
                                       $bilingual_block = ''; 
                                
                                    if($teacher_test_result > 0)
                                    {
                                        
                                        if($teacher_test_result_data->pass_status == 'pass')
                                        {
                                           $allow_claim = 1;
                                           $pass_status = 'pass';
                                        }
                                        else
                                        {
                                           $allow_claim = 0;
                                           $pass_status = 'fail';
                                        }
                                        
                                    }
                                    $claim_type = 'test';
                                    $claim_message .= "<li>Pass bilingual(spanish) test</li>";
                                   
                                }
                                else if(!empty($row['certificate']) && empty($row['bilingual_test']))
                                { 
                                    
                                    
                                    if($row['certificate'] == 2)
                                    {
                                         $certificte = $tutor_profie['teacher_certificate']; 
                                         $approve_certificte = $tutor_profie['teacher_approve'];
                                         
                                         if($tutor_profie['certificate_block'] == 1)
                                             $cer_block = 1;
                                         else
                                             $cer_block = '';
                                         if(empty($certificte))
                                           $claim_message .= "<li>Upload Teacher certification</li>";
                                    }
                                    else if($row['certificate'] == 3)
                                    {
                                         $certificte = $tutor_profie['esl_certificate'];
                                         $approve_certificte = $tutor_profie['esl_approve'];
                                         
                                         if($tutor_profie['esl_block'] == 1)
                                             $cer_block = 1;
                                         else
                                             $cer_block = '';
                                         if(empty($certificte))
                                           $claim_message .= "<li>Upload ESL certification</li>";
                                         
                                    }
                                    else if($row['certificate'] == 4)
                                    {
                                         $certificte = $tutor_profie['billingual_certificate'];
                                         $approve_certificte = $tutor_profie['bilingual_approve'];
                                         
                                         if($tutor_profie['bilingual_block'] == 1)
                                             $cer_block = 1;
                                         else
                                             $cer_block = '';
                                         if(empty($certificte))
                                           $claim_message .= "<li>Upload Bilingual certification</li>";
                                       
                                    }
                                    if(!empty($certificte))
                                    {
                                      
                                        if($approve_certificte == 1)
                                            $allow_claim = 1;
                                        else
                                        {
                                          $allow_claim = '';  
                                          $cer_approve = 1;
                                        }
                                    }
                                     $claim_type = 'certificate';
                                 
                                }
                                
                    $claim_message .= "</ul>";

                  
                           if($is_grade_test_result && $grade_pass_status == 'pass')
                           { 
                              if(empty($tutor_block) && empty($cer_block) && empty($bilingual_block))
                              {  
                                  
                             if($allow_claim > 0) { 
                                 
                                   $teacher_test_data = mysqli_fetch_object($get_result);
                                 ?>
                           <button type="submit" name="submit_claim" style=" background-color:orange;padding-left: 24px;padding-right: 24px;

                              color:#fff; border:1px solid orange"

                              class="btn btn-default" value="<?=$row['id']?>">Claim Tutor Job</button>

                             <?php } else {
                                 
                                 if($claim_type == 'test') {
                                $test_name = 'Bilingual (Spanish)';?>
                                 
                               
                                 <button type="button"  style=" background-color:orange;padding-left: 24px;padding-right: 24px;

                              color:#fff; border:1px solid orange"

                              class="btn btn-default" onclick="showclaimmodal('<?php echo $test_name;?>','73','<?php echo $pass_status;?>','<?php echo $claim_message;?>','<?php echo $row['id'];?>','<?php echo $grade_pass_status;?>')">Claim Tutor Job</button>
                             <?php } else if($claim_type == 'certificate') { ?> 
                            
                            <?php   if($row['certificate'] == 2) $test_name = 'Certified Teacher'; else if($row['certificate'] == 3) $test_name = 'Certified Teacher in ESL'; else if($row['certificate'] == 4) $test_name = 'Certified Teacher in ESL with Bilingual Tutor ( Spanish )'; ?>
                               
                            <?php if($cer_approve != 1) {?>
                           <button type="button"  style=" background-color:orange;padding-left: 24px;padding-right: 24px;

                              color:#fff; border:1px solid orange"

                              class="btn btn-default" onclick="showclaimmodalforcertificate('<?php echo $test_name;?>','<?php echo $tutor_profile['tutorid'];?>','<?php echo $pass_status;?>','<?php echo $claim_message;?>','<?php echo $row['id'];?>','<?php echo $grade_pass_status;?>')">Claim Tutor Job</button>
                            <?php } else { ?>
                            
                             <button type="button"  style=" background-color:orange;padding-left: 24px;padding-right: 24px;

                              color:#fff; border:1px solid orange"

                              class="btn btn-default" onclick="showbanmmodalforcertificate('<?php echo $test_name;?>','<?php echo $tutor_profile['tutorid'];?>','<?php echo $pass_status;?>','<?php echo $claim_message;?>','<?php echo $row['id'];?>','<?php echo $grade_pass_status;?>')">Claim Tutor Job</button>
                            <?php } ?>

 <?php } } } else { ?> 
                                  
                                  <button type="button"  style=" background-color:orange;padding-left: 24px;padding-right: 24px;

                              color:#fff; border:1px solid orange"

                              class="btn btn-default" onclick="showblockmodal()">Claim Tutor Job</button>
                                  <?php } } else { ?>
                          <?php if($allow_claim > 0 ) { 
                                 
                                 //  $teacher_test_data = mysqli_fetch_object($get_result);
                                 ?>
                           <button type="submit" name="submit_claim" style=" background-color:orange;padding-left: 24px;padding-right: 24px;

                              color:#fff; border:1px solid orange"

                              class="btn btn-default" value="<?=$row['id']?>">Claim Tutor Job</button>

                                  <?php } else { ?>
<button type="button"  style=" background-color:orange;

                              color:#fff; border:1px solid orange"

                              class="btn btn-default" onclick="showclaimmodal('<?php echo $grade_test;?>','<?php echo $grade_test_data->ID;?>', '<?php echo $pass_status;?>','<?php echo $claim_message;?>','<?php echo $row['id'];?>','<?php echo $grade_pass_status;?>')">Claim Tutor Job</button>

                                  <?php } } ?>

                           &nbsp; &nbsp;

                      

                           <?php  } ?>
                           
                 <?php
                  if($tutor_profie['block'] == 1)
                                   $tutor_block = 1;
                               else
                                   $tutor_block ='';
$allow_claim = 0;
$cer_approve = '';
$grade_pass_status = '';
$pass_status = '';
$claim_message = "<ul style=text-align:left>";
                              $claim_type = '';
                                  $grade_test_id = '';
                                $grade_test = '';
                            if($row['tut_observer_id']==0 && $row['add_observer']==1){
                                    $tests_sql =  mysqli_query($p2db,"select *from `tests` where IsActive = '1'");
                              
                                while($test_data = mysqli_fetch_object($tests_sql))
                                {
                                  $subjects = $test_data->SubjectID;
                                    if(!empty($subjects))
                                    {
                                        $subjectArr = explode(":", $subjects);
                                        if(in_array($row['grade_id'], $subjectArr))
                                        {
                                          $grade_test_id = $test_data->ID;
                                          $grade_test = $test_data->Name;
                                            break;
                                        }
                                    }
                                }
                                    
                                if(!empty($grade_test_id) )
                                {
                                   // $grade_test_data = mysqli_fetch_object( mysqli_query($p2db,"select *from `tests` where Name = '".$grade_test."'"));
                                    $grade_test_sql = " SELECT * FROM `tutor_tests_logs` WHERE  tutor_id='".$_SESSION['ses_teacher_id']."' AND quiz_test_id='".$grade_test_id."' AND status='completed'";
                                    $grade_test_result = mysqli_query($p2db,$grade_test_sql);
                                    $is_grade_test_result = mysqli_num_rows($grade_test_result);
                                    $grade_test_result_data = mysqli_fetch_object($grade_test_result);
                                    
                                    if($grade_test_result_data->block == 1)
                                        $tutor_block = 1;
                                    else
                                       $tutor_block = ''; 
                                    
                                    
                                    if($is_grade_test_result > 0)
                                    {
                                     if($grade_test_result_data->pass_status == 'pass')
                                        $grade_pass_status = 'pass';
                                     else if($grade_test_result_data->pass_status == 'fail')
                                        $grade_pass_status = 'fail'; 
                                     else
                                       $claim_message .= "<li>Pass ".$grade_test." test</li>";
                                    }
                                    else {
                                    $claim_message .= "<li>Pass ".$grade_test." test</li>";    
                                    }
                                    
                                  
                                }
                          
                               
                                if(empty($row['bilingual_test']) && empty($row['certificate']) )
                                {
                                   if(!empty($grade_test_id) )
                                    {
                                       if($is_grade_test_result > 0 && $grade_test_result_data->pass_status == 'pass')
                                          $allow_claim = 1;
                                       else
                                          $allow_claim = ''; 
                                    }
                                    else
                                       $allow_claim = 1;
                                    
                                }
                                else if(!empty($row['bilingual_test']) && empty($row['certificate']) )
                                {
                                    $sql = " SELECT * FROM `tutor_tests_logs` WHERE tutor_id='".$_SESSION['ses_teacher_id']."' AND quiz_test_id = '73' AND status='completed' ";
                                    $get_result = mysqli_query($p2db,$sql);
                                    $teacher_test_result = mysqli_num_rows($get_result);
                                    
                                     $teacher_test_result_data = mysqli_fetch_object($get_result);
                                    
                                    if($teacher_test_result_data->block == 1)
                                        $bilingual_block = 1;
                                    else
                                       $bilingual_block = ''; 
                                
                                    if($teacher_test_result > 0)
                                    {
                                        if($teacher_test_result_data->pass_status == 'pass')
                                        {
                                           $allow_claim = 1;
                                           $pass_status = 'pass';
                                        }
                                        else
                                        {
                                           $allow_claim = 0;
                                           $pass_status = 'fail';
                                        }
                                        
                                    }
                                    $claim_type = 'test';
                                    $claim_message .= "<li>Pass bilingual(spanish) test</li>";
                                   
                                }
                                else if(!empty($row['certificate']) && empty($row['bilingual_test']))
                                { 
                                    
                                    
                                    if($row['certificate'] == 2)
                                    {
                                         $certificte = $tutor_profie['teacher_certificate']; 
                                         $approve_certificte = $tutor_profie['teacher_approve'];
                                         
                                         if($tutor_profie['certificate_block'] == 1)
                                             $cer_block = 1;
                                         else
                                             $cer_block = '';
                                         if(empty($certificte))
                                           $claim_message .= "<li>Upload Teacher certification</li>";
                                    }
                                    else if($row['certificate'] == 3)
                                    {
                                         $certificte = $tutor_profie['esl_certificate'];
                                         $approve_certificte = $tutor_profie['esl_approve'];
                                         
                                         if($tutor_profie['esl_block'] == 1)
                                             $cer_block = 1;
                                         else
                                             $cer_block = '';
                                         if(empty($certificte))
                                           $claim_message .= "<li>Upload ESL certification</li>";
                                         
                                    }
                                    else if($row['certificate'] == 4)
                                    {
                                         $certificte = $tutor_profie['billingual_certificate'];
                                         $approve_certificte = $tutor_profie['bilingual_approve'];
                                         
                                         if($tutor_profie['bilingual_block'] == 1)
                                             $cer_block = 1;
                                         else
                                             $cer_block = '';
                                         if(empty($certificte))
                                           $claim_message .= "<li>Upload Bilingual certification</li>";
                                       
                                    }
                                    if(!empty($certificte))
                                    {
                                      
                                        if($approve_certificte == 1)
                                            $allow_claim = 1;
                                        else
                                        {
                                          $allow_claim = '';  
                                          $cer_approve = 1;
                                        }
                                    }
                                     $claim_type = 'certificate';
                                 
                                }
                    $claim_message .= "</ul>";
      
                           if($is_grade_test_result && $grade_pass_status == 'pass')
                           {
                              if(empty($tutor_block) && empty($cer_block) && empty($bilingual_block))
                              {  
                             if($allow_claim > 0) { 
                                 
                                   $teacher_test_data = mysqli_fetch_object($get_result);
                                 ?>
                           <button type="submit" name="submit_observer_claim" style=" background-color:green;margin-top: 5px;

                              color:#fff; border:1px solid green"

                              class="btn btn-default" value="<?=$row['id']?>">Claim Observer Job</button>

                             <?php } else {
                                 
                                 if($claim_type == 'test') {
                                $test_name = 'Bilingual (Spanish)';?>
                                 
                               
                                 <button type="button"  style=" background-color:green;margin-top: 5px;

                              color:#fff; border:1px solid green"

                              class="btn btn-default" onclick="showclaimmodal('<?php echo $test_name;?>','73','<?php echo $pass_status;?>','<?php echo $claim_message;?>','<?php echo $row['id'];?>','<?php echo $grade_pass_status;?>')">Claim Observer Job</button>
                             <?php } else if($claim_type == 'certificate') { ?> 
                            
                            <?php   if($row['certificate'] == 2) $test_name = 'Certified Teacher'; else if($row['certificate'] == 3) $test_name = 'Certified Teacher in ESL'; else if($row['certificate'] == 4) $test_name = 'Certified Teacher in ESL with Bilingual Tutor ( Spanish )'; ?>
                               
                            <?php if($cer_approve != 1) {?>
                           <button type="button"  style=" background-color:green;margin-top: 5px;

                              color:#fff; border:1px solid green"

                              class="btn btn-default" onclick="showclaimmodalforcertificate('<?php echo $test_name;?>','<?php echo $tutor_profile['tutorid'];?>','<?php echo $pass_status;?>','<?php echo $claim_message;?>','<?php echo $row['id'];?>','<?php echo $grade_pass_status;?>')">Claim Observer Job</button>
                            <?php } else { ?>
                            
                             <button type="button"  style=" background-color:green;margin-top: 5px;

                              color:#fff; border:1px solid green"

                              class="btn btn-default" onclick="showbanmmodalforcertificate('<?php echo $test_name;?>','<?php echo $tutor_profile['tutorid'];?>','<?php echo $pass_status;?>','<?php echo $claim_message;?>','<?php echo $row['id'];?>','<?php echo $grade_pass_status;?>')">Claim Observer Job</button>
                            <?php } ?>

 <?php } } } else { ?> 
                                  
                                  <button type="button"  style=" background-color:green;margin-top: 5px;

                              color:#fff; border:1px solid green"

                              class="btn btn-default" onclick="showblockmodal()">Claim Observer Job</button>
                                  <?php } } else { ?>
                           <?php  if($allow_claim > 0 ) { 
                                 
                                   $teacher_test_data = mysqli_fetch_object($get_result);
                                 ?>
                           <button type="submit" name="submit_observer_claim" style=" background-color:green;margin-top: 5px;

                              color:#fff; border:1px solid green"

                              class="btn btn-default" value="<?=$row['id']?>">Claim Observer Job</button>

                                  <?php } else { ?>
<button type="button"  style=" background-color:green;

                              color:#fff; border:1px solid green"

                              class="btn btn-default" onclick="showclaimmodal('<?php echo $grade_test;?>','<?php echo $grade_test_data->ID;?>', '<?php echo $pass_status;?>','<?php echo $claim_message;?>','<?php echo $row['id'];?>','<?php echo $grade_pass_status;?>')">Claim Observer Job</button>

                                  <?php } } ?>

                           &nbsp; &nbsp;

                      

                           <?php  } ?>
                           
                           
                           <?php } else{ // Expired session?>

                        <span class="btn btn-danger btn-xs">Expired</span>

                        <?php  }?>
  </td>
  <td style="text-align:center">
  <?php 
   if($row['tut_teacher_id'] > 0)
    {
        $tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$row['tut_teacher_id']));
        
         echo '<strong class="text-primary">Assigned To Tutor</strong> <br />' ;
          echo $tut_th['f_name']." ".$tut_th['lname'];
    }
    else
    {
        echo '<strong class="text-primary">Assigned To Tutor</strong> <br />- ' ;
    }
        
    if($row['add_observer'] == 1)
    {
        if($row['tut_observer_id'] > 0)
        {
        $tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$row['tut_observer_id']));
        echo '<hr>';
         echo '<strong class="text-success">Assigned To Observer</strong>' ;
          echo '<br /><span>'.$tut_th['f_name']." ".$tut_th['lname'].'</span>';
    }
    else
    {
        echo '<hr>';
         echo '<span><strong class="text-success">Assigned To Observer</strong></span> <br /><label> - </label>' ;
    }
    }
  ?>
  </td>
                        <td>

                           <a  class="btn btn-danger btn-xs" href="<?= $detail_url ;?>" style="text-decoration:underline;">View</a> <br>

                           <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>         

                           <strong class="text-primary"></strong>    

                           <?=date_format(date_create($row['created_date']), 'F d,Y');?>    

                           <!--     <strong class="text-primary">Session:</strong>incomplete<br/>   -->

                        </td>

                     </tr>

                     <?php

                        }

                        } else {

                        echo '<div class="clear"><p>There is no Jobs found!</p></div>';

                        }

                        ?>

                  </table>

                  <div class="clearnone">&nbsp;</div>

               </div>

               <!-- /.ct_display -->

               <input type="hidden" id="arr-user" name="arr-user" value=""/>

            </form>

         </div>

         <!-- /#content -->

         <div class="clearnone">&nbsp;</div>

      </div>

   </div>

</div>

<!-- /#header -->

<script type="text/javascript">

   <?php 

      if ($error != '') echo "alert('{$error}')"; ?>









$('.viewSession').click(function()

{

  var SessionID=$(this).attr('SessionID');

  var action = $(this).attr('action');

$.ajax({

   type:'POST',

   data:{SessionID:SessionID,action:action},

   url:'https://tutorgigs.io/dashboard/get_session-ajax.php',

   success:function(data){

    $('#ViewDetails').modal('show');

    $('.SeessionIDD').text(SessionID);

    $('.dataview').html(data);

   }

});



});



</script>



 <!-- Modal -->

  <div class="modal fade" id="ViewDetails" role="dialog">

    <div class="modal-dialog">

      <!-- Modal content-->

      <div class="modal-content">

         <div class="modal-header">

          <h4 class="modal-title">Details Session ID <span class="SeessionIDD"></span></h4>

          <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>

        <div class=" dataview">

          <p>Some text in the modal.</p>

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>

      </div>

      

    </div>

  </div>
 
 <div class="modal fade" id="session_claim" role="dialog">

    <div class="modal-dialog">

      <!-- Modal content-->

      <div class="modal-content">
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Tutor Claim Session</h4>
      </div>
         

          <div class="panel-body" style="text-align:center" id="modal_body">

         
        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>

      </div>

      

    </div>

  </div>
<script>
function showclaimmodal(test_name, test_id, pass_status, claim_message,ses_id, garde_pass_status)
{ 
    if(pass_status == 'fail' || garde_pass_status == 'fail')
    {
        html = '<p> You can\'t claim this session since you have failed the test.</p>';
       
   }
   else
   {
      html = '<p style="text-align:left"> You need to satisfy the below requirement to claim this session</p>';
      html += '<p>'+claim_message+'</p>';
       html += '<p><a href="manage_claim_tests.php?ses_id='+ses_id+'"  class="btn btn-primary btn-lg">Go Exam & Certification Page</a></p>';
   }
$('#modal_body').html(html);
    $('#session_claim').modal('show');
}

</script>

<script>
function showclaimmodalforcertificate(test_name, tutor_id, pass_status, claim_message,ses_id)
{
    if(pass_status == 'fail')
    {
      html = '<p> You can\'t claim this session since you have failed the test.</p>';
  }
  else
  {
      html = '<p style="text-align:left"> You need to satisfy the below requirement to claim this session</p>';
      html += '<p>'+claim_message+'</p>';
      html += '<p><a href="manage_claim_tests.php?ses_id='+ses_id+'"  class="btn btn-primary btn-lg">Go Exam & Certification Page</a></p>';
  }
$('#modal_body').html(html);
    $('#session_claim').modal('show');
}

function showbanmmodalforcertificate(test_name, tutor_id, pass_status, claim_message,ses_id)
{ 
     html = '<p> Your certification need to be approved by administrator</p>';
  
  
$('#modal_body').html(html);
    $('#session_claim').modal('show');
}

function showblockmodal()
{
     html = '<p> You are not allow to claim the job as you are blocked by administrator</p>';
    
$('#modal_body').html(html);
    $('#session_claim').modal('show');
}

</script>

</script>


<?php include("footer.php"); ?>

