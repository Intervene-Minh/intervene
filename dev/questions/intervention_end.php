<?php
/**@  $start_date = date('Y-m-d h:i:s');
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}
**/

//include("student_header.php");

include('inc/connection.php'); 
session_start();
  ob_start();
/////////////////////
include("student_inc.php");
include("ses_live_inc.php");# Tutor sesion Live 
   //print_r($time_arr); # globals for sessions
/*
# &roomId=123&userId=456&userName=Teacher&isTeacher=1
$_POST['isTeacher']=0; // 1386
$_SESSION['live']['live_ses_id']=1386;

**/
 $curr_time= date("Y-m-d H:i:s"); #currTime
///Session end ///
if(isset($_GET['isTeacher'])&&$_GET['isTeacher']==1){

  // Tutor board
  header("Location:https://tutorgigs.io/dashboard/my-sessions.php");exit;

}elseif(isset($_GET['isTeacher'])&&$_GET['isTeacher']==0){
  //  Re-direct student to Session Quiz
   if(!isset($_SESSION['live']['live_ses_id'])){ // student_logut
    //exit('Session time out, login');
    echo 'Session time out, login'; die; 
   }
   // roomId=203174&userId=10806&userName=mtest1&isTeacher=0,
   $bclass_id=$_GET['roomId'];
   $sql_res=mysql_query("SELECT * FROM int_schools_x_sessions_log WHERE braincert_class= '$bclass_id' ");
   $ses_row=mysql_fetch_assoc($sql_res);
   $sesStartTime=$ses_row['ses_start_time'];
   $in_sec= strtotime($sesStartTime) - strtotime($curr_time);///604800 #days>+7 days
    // print_r($ses_row['ses_start_time']);  die; 

   //echo $in_sec.'===';  die; 
    if($in_sec<=300&&$in_sec>=-300){ 
      $msg='Activity going on!';
    }elseif($in_sec<=-301){
      // elseif($in_sec<=-301&&$in_sec>-2100){

      $msg='You are already in session!'; // 'Session going on!';
      $live_sesid=(isset($_SESSION['live']['live_ses_id']))?$_SESSION['live']['live_ses_id']:$ses_row['id'];
  $quiz_url="student_quiz.php?id=".$live_sesid;
  header("Location:".$quiz_url);exit;
  

    }elseif($in_sec<-2100&&$in_sec>-86400){// 
        // Go to Quiz OPtion
      $msg='Quiz on!';
      $live_sesid=(isset($_SESSION['live']['live_ses_id']))?$_SESSION['live']['live_ses_id']:$ses_row['id'];

      ////////////
    //$quiz_url='student_quizstartnow.php?id='.$live_sesid;//Direct ques open
  $quiz_url="student_quiz.php?id=".$live_sesid;
  header("Location:".$quiz_url);exit;
    }
   //  check by class code. 
   echo $msg; die; 
   
}




///////
 // echo 'intervention_end =='; die; 

echo 'Page not found.!'; die; 






##############################################
 // page globals 
  $page_name='Student Pendings';




///Listing////////////////

function student_assessment_pending(){
  $data=array();// Listing array
   global $student_school;
   global $student_teacher;
$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";

 //return $sql; 
$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {  
   //$row['asssessment']='Demo asement';
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}
//return
return $data;
}
/////





/////////////Display////

//$get_rows=student_assessment_pending();
 //print_r($get_rows); die; 

//echo $msg;
 // echo '<pre>';
 // print_r($data);
 // echo 'Student==';
 // print_r($student_det);

?>
<script type="text/javascript">
    is_activity_move();
//var url_1="https://tutorgigs.io/dashboard/notify_refresh_top.php"; 
function is_activity_move(){

  console.log('is_activity_move===');// is_activity_move

    // var url_1="https://tutorgigs.io/dashboard/notify_refresh2.php";  
     // var url_1="https://intervene.io/questions/test_ajax.php"; 
      var url_1="https://intervene.io/questions/student_pendings_ajax.php";
      $.ajax({ 
            type: 'GET', 
            url: url_1, 
            data: { get_param: 'student_pendings' }, 
            dataType: 'json',
            success: function (data) { 
            
            var str=' Test info='+data.url_redirect;

             //console.log(str);
             console.log('Sent_from::'+data.sent_from);
             console.log('status='+data.status);
             var move_url='actvity.php';
             console.log('url_redirect='+data.url_redirect);
             if(data.status=='OK'){
              move_url=data.url_redirect;
              window.location.href =move_url;//actvity.php |student_board.php
              
             }
             // console.log(data.is_redirect+'=is_redirect');  // url_redirect
             //str+=data.length;
           // Display modal
           $("#items_id").html(str);
          // $("#myModal").modal('show'); 


        
      
    
        // $(".last-updated").append(data.data.updated);
        // $(".aqiStatus").html(data.data.text); 
        }
    }); 

       setTimeout(function(){
      is_activity_move();},3000);

}


</script>

<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
      <div id="items_id" style="display: none;">
         CHecking sesion
      </div>
        <div class="row">
            <div class="align-center col-md-12" style="margin-top:10px; margin-bottom: 100px;">
                
                <div style=" width:auto;" title="">
                 <?php include("nav_students.php"); ?>
       

        
                
                      </div>    





            </div>
           <!--  NExt col -->
           <div class="align-center col-md-12">
<!--                <p class="text-success">Session list of students..  </p>-->
               <?php 



            ##########################
            $student_id=$_SESSION['student_id'];#
            $cc=" order by created_date DESC ";
   $sql="SELECT sd.quiz_status,sd.launchurl,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN "
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";  // tut_teacher_id 
      
      // $sql.=" AND ses.ses_start_time >= '".$time_arr['24_hour_back']."' ";
        # 55 min. less session in Penidng list.: include 1 past session.
        $sql.=" AND ses.ses_start_time >= '".$time_arr['time_55_less']."' ";

       // Next 24 thoud
        //$sql.=" AND ses.ses_start_time <= '".$time_arr['24_hour_next']."' ";
       $sql.=' ORDER BY ses.ses_start_time ASC ';


           // w/o Tutor Assigned. 
          

           //echo '<pre>', $sql; //   die;


     $result=mysql_query($sql);
               
     $total_ses=mysql_num_rows($result);

            if($total_ses>0){

               ?>


          <form name="principal_action" id="principal_action" method="POST" action="">

           </form>
       
        
        
        
     
        
   
          <?php  }

            // pendnig_quiz_inc.php?>


           <!--  Student Quziz pendings -->
            <?php  include("inc/pendnig_quiz_inc.php");?>
 
                <!--  Student Assessment -->
                
          
            <?php  include("inc/pending_assessment_inc.php");?>
            <!--  Student Quziz pendings -->
                      
            </div><!--   <div class="align-center col-md-12"> -->

           <!-- C0ntent -->
          



        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush();
 //print_r($_SESSION);
 ?>

