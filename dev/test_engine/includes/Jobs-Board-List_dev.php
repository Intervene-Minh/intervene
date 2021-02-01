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

                                        $qq.=" AND tut_teacher_id='0' ";

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

                        <col width="330">

                        <col width="200">

                        <col width="155">

                     </colgroup>

                     <tr>

                        <th>Sessions Date/Time</th>

                        <th>Claim Tutor Gig</th>

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

                            if($row['tut_teacher_id']==0){?>

                           <button type="submit" name="submit_claim" style=" background-color:orange;

                              color:#fff; border:1px solid orange"

                              class="btn btn-default" value="<?=$row['id']?>">Claim Tutor Job</button>

                           <?php  }?>

                           &nbsp; &nbsp;

                        </td>

                        <?php  }else{ // Expired session?>

                        <span class="btn btn-danger btn-xs">Expired</span>

                        <?php  }// Expired session

                           //

                           

                           

                           

                           ?>

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

<?php include("footer.php"); ?>

