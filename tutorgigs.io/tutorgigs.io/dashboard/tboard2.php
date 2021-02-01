<?php
/**
@ Ref. :: sessions-listing.php
@  upcoming sessions
@ Past sessions::

*/

///
 @extract($_GET) ;
@extract($_POST) ;

$ses_time_before=-2700; #
$ses_2hr_before=-7200;


//include("header.php");

       session_start();ob_start();
define("TUTOR_BOARD", "groupworld");
$home_url="https://tutorgigs.io/"; 
include('inc/connection.php'); 
include('inc/public_inc.php'); 
require_once('inc/check-role.php');

$calendly_url='https://calendly.com/tutorgigs';
 //print_r($_SESSION);
// Signup State Global//
 $step_1_url='application.php';
 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //interview
 $step_4_url="application_status.php"; //Quiz and interview status Show or Out from application status

// Signup State Global//

/////////////////

  $role = checkRole();
  $tutor_regiser_page=(isset($_SESSION['ses_curr_state_url'])&&!empty($_SESSION['ses_curr_state_url']))?$_SESSION['ses_curr_state_url']:"application.php";
   //Commmon to all page

############################


 $cur_time= date("Y-m-d H:i:s");  // die;
     $time=strtotime($cur_time);
     $startTime = date("Y-m-d H:i:s", strtotime('-55 minutes', $time));
$endTime = date("Y-m-d H:i:s", strtotime('+55 minutes', $time));
 $one_hr_les=date("Y-m-d H:i:s", strtotime('-60 minutes', $time));
 ///*************

 $time_arr=array();
  $time_arr['curr_time']=date("Y-m-d H:i:s");
$time_arr['one_hr_les']=$one_hr_les;

 $time_arr['time_55_less']=$startTime;
 $time_arr['time_55_up']=$endTime;
      $time_arr['24_hour_back']=date('Y-m-d H:i:s',strtotime('-24 hours'));
      $time_arr['24_hour_next']=date('Y-m-d H:i:s',strtotime('+24 hours'));
      $time_arr['2_hour_less']=date('Y-m-d H:i:s',strtotime('-2 hours'));

    //print_r($time_arr); 
//////////Validate Site Access//////////
//print_r($_SESSION);

// if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
//   header("Location:".$tutor_regiser_page);exit;
// }


/////////////////////////////////////
  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";
//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

 $page_name='My Sessions';
$error='';
$id = $_SESSION['ses_teacher_id'];

################################

function send_job_email($to,$message,$subj=''){
  $to ="learn@intervene.io";
   
  $subject =$subj;
  // learn@intervene.io
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
 //$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';

}
//////Send email////////

$message = "Dear {$f_name},
        <br/><br/>
        Job time has changed.Please check and requesting you to cancel job if not available for the job.
  <br/><p><strong>Job ID</strong>:{$ses_id}</p>

  <br/><br/>
  Best regards,<br />
  <strong>Tutorgigs Team</strong><br/>
  www.tutorgigs.io<br>
  Tel +185534-LEARN<br>
  Email: support@tutorgigs.io
  <br /><br />
  <img alt='' src='https://tutorgigs.io/logo.png'>";

   $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'
$tutor_det= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$tutor_id));

 $tutor_new_board_url=$tutor_det['url_aww_app'];  # FROM gig_teachers #4-april 2019

// https://www.groupworld.net/room/2816/lunden?iframe=true
//$iframe_url=$default_tutor_board='https://www.groupworld.net/room/2816/lunden?iframe=true';

if(isset($tutor_det['url_aww_app'])&&!empty($tutor_det['url_aww_app'])){
  $iframe_url=$tutor_det['url_aww_app'].'?iframe=true';
}


///echo $iframe_url; die; 




////////////Rejected////////////////////////////////////////////
if(isset($_POST['submit_reject'])){
  //print_r($_POST); die;
  
   $ses_id=$sid=$_POST['submit_reject'];// Session id
    $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'

 
/// die;
   $tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$tutor_id));
   
  

   // Validate::Another Tutor accept.
   $ses_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND id=".$sid));

  $em_ses_date=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');
  $em_ses_time=date_format(date_create($ses_det['ses_start_time']), 'h:i a');
  //  f_name lname
  $turorName=$tut_th['f_name'].' '.$tut_th['lname'];


  $message="Hi Admin, <br/><br/>
  
  <p><strong>Tutor</strong>:{$turorName}</p>
   <p><strong>Time</strong>:{$em_ses_time}</p>
   <p><strong>Date</strong>:{$em_ses_date}</p>
    <p><strong>Session ID</strong>:{$ses_id}</p>
    <br/><br/>

    Thanks,<br/>
Tutorgig Team  ";
  

 

 // send_job_email($to,$message,$subj=''){
   $subject1=$turorName.' Cancelled the session';


   if(send_job_email($to='',$message,$subject1)){
    $em_msg= 'sent' ; 
   }else{
      $em_msg= 'Not sent' ; 
   }


  // die;


   ////////////////
   $data=mysql_fetch_assoc(mysql_query(" SELECT COUNT(*) as tot_canel FROM int_session_cancel WHERE tutor_id=".$tutor_id));
   //echo $data['tot_canel']; die;

         #logoutMesageAccount Susspended. 
   /**
 if($data['tot_canel']>=2){ 
      $susTutr= mysql_query("UPDATE gig_teachers SET status='2',"
            . "updated='$curr_time' WHERE id='$tutor_id'", $link);  
        $_SESSION['suspended']='Your account suspended, Please contact service provider. !';
       unset($_SESSION['ses_teacher_id']);
       
    header("Location:../login.php");exit;    
    }

   **/
    
    
   //////////////////////
 if($ses_det['tut_teacher_id']>0){  /// 
     //insert log: int_session_cancel:: ses_id   	tutor_id dated
      $log= mysql_query("INSERT INTO int_session_cancel SET ses_id='$sid',"
            . "tutor_id='$tutor_id',dated='$curr_time' ", $link); 
     
     ///////////////////////////
  $query = mysql_query("UPDATE int_schools_x_sessions_log SET tut_teacher_id='0',"
            . "app_url='',tut_accept_time='$curr_time',"
            
            . "modified_date='$curr_time' WHERE id='$sid'", $link);
    
    
    // in studentsLog of Slot
     $up= mysql_query("UPDATE int_slots_x_student_teacher SET tut_teacher_id='0',"
            . "tut_admin_id='1' WHERE slot_id='$sid'", $link); 
     
     
       $error="You cancelled this session. !";
   }else{
      $error_msg="Can not cancel"; 
   }


    //  	tut_teacher_id ::default 0 if , Rejected
    
}
////////////Rejected////////////////


if(isset($_POST['submit_claim'])){
 
  //  print_r($_POST); 
 $sid=$_POST['submit_claim'];// Tutor job :session

  $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'
/// die;
   $tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$tutor_id));
   
   // Validate::Another Tutor accept.
   $ses_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND id=".$sid));
  // echo 'Current status of-'.$ses_det['tut_teacher_id']; die; // 0 if not assgined
   if($ses_det['tut_teacher_id']==0){
  $query = mysql_query("UPDATE int_schools_x_sessions_log SET tut_teacher_id='".$tutor_id."',"
            . "app_url='".$tut_th['url_aww_app']."',tut_accept_time='$curr_time',"
            
            . "modified_date='$curr_time' WHERE id='$sid'", $link);
    
    
    // in studentsLog of Slot
     $up= mysql_query("UPDATE int_slots_x_student_teacher SET tut_teacher_id='".$tutor_id."',"
            . "tut_admin_id='1' WHERE slot_id='$sid'", $link); 
       $error="You Accepted this session";
   }else{
      $error_msg="Already assgined job to another tutor. !"; 
   }
 /// 
   
     
}
// Submit to give feedback 
if(isset($_POST['submit_feedback'])){
    # clear past session
     unset($_SESSION['form_2']); unset($_SESSION['form_3']); 
unset($_SESSION['form_4']); unset($_SESSION['form_5']);  
  unset($_SESSION['form_1']); //unset($_SESSION['feedback_ses_id']);
    //////////////
    $_SESSION['feedback_ses_id']=$_POST['submit_feedback']; //
    // feedback_form1.php
       header("location:feedback_form1.php"); exit;
}
// Edit 
if(isset($_POST['submit_feedback_edit'])){
     # clear past session
     unset($_SESSION['form_2']); unset($_SESSION['form_3']); 
unset($_SESSION['form_4']); unset($_SESSION['form_5']);  
  unset($_SESSION['form_1']); //unset($_SESSION['feedback_ses_id']);
    
    $_SESSION['feedback_ses_id']=$_POST['submit_feedback_edit']; //
       header("location:feedback_form1.php?edit=1"); exit;
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
//Listing ///


                                
                           
                              $curr_time= date("Y-m-d H:i:s"); #currTime
                            
                                $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                               // $qq.=" AND tut_status='STU_ASSIGNED' ";
                               
                             
                                if(isset($_GET['id']))
                                $qq.=" AND id='".$_GET['id']."' ";#only Assigned
                                else{
                               #     $qq.=" AND ses_start_time>'$curr_time' ";
                                 $qq.=" AND ses_start_time>'".$time_arr['2_hour_less']."' ";
                                    
                                 $qq.=" AND tut_teacher_id='$id' ";
                                $qq.=" ORDER BY ses_start_time ASC ";    
                                }
                          
                               
                                
                                
                                /////////////////////////
                                $session_type=(isset($session_type))?$session_type:"upcoming" ;
                                if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                  $tutor= $_SESSION['ses_teacher_id'];
                                  // echo $session_type ; die;
                           
                                  if($session_type=="past"){
                                     $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                
                                $qq.=" AND ses_start_time<'".$curr_time."'";
                                 $qq.=" AND tut_teacher_id='$tutor' ";
                                 
                                $qq.=" ORDER BY ses_start_time DESC "; 
                               
                                  }elseif($session_type=="upcoming"){
                                    $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                 
                                $qq.=" AND ses_start_time>'".$curr_time."'";
                                  $qq.=" AND tut_teacher_id='$tutor' ";
                                $qq.=" ORDER BY ses_start_time ASC ";   
                                  }elseif($session_type=="all"){
                                   $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                  $qq.=" AND tut_teacher_id='$tutor' ";
                                
                                $qq.=" ORDER BY ses_start_time ASC ";   
                               
                                  }
                                  
                                  
                               //    if(!empty($email))
                                // $qq.=' AND email LIKE "%'.$email.'%"  ';
                                
                                    
                                   
                                }
                                
                              //////////////
                               // echo $qq;
                                
                                
                                $results = mysql_query($qq);
                $tot_record=mysql_num_rows($results);
                                
                             // echo '<pre>',$qq;echo '<br/>' ; 

  //end              
  ?>

<script>
function myFunction() {
    var txt;
    //var r = confirm("Press a button!\nEither OK or Cancel.\nThe button you pressed OK,Reject session.");
     var info='If you cancel this job with less than 48 hours notice you will risk being suspended and losing deducting the payments for 1 session.';

     var r = confirm(info);
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
</script>

<script>
    
    $(function() {
       $('.delete8888').click(function(e) {
           
        e.preventDefault();
        var c = confirm("Click OK to continue?");
        if(c){
          //  return true;
           $('form#form-manager').submit();
          }
      });
  });
  ////////////////////
    
    
	$(document).ready(function(){
            
            
            
            
           ////////////////////// 
            
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

<!-- <div class="logo">
          
          <a href="https://tutorgigs.io/" title="Less Test Prep"><img alt="Intervene" src="images/logo.png"></a>
        </div> -->

	<div class="container">
		<div class="row">
		


			<div id="content" class="col-md-12">

      <style>html,body { height: 100%; margin: 0px }</style> 
      <iframe allow="microphone; camera; display"  style="width: 100%;height:900px;" 
      scrolling="no" frameborder="0" src="<?=$iframe_url?>"></iframe>


      </div>
                  

			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

