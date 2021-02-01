<?php
/***
@ Notification Email-Tutor for Hot job for unclaimed job in next 48 hours.
@ Intervention
intervene
  $cur_time= date("Y-m-d H:i:s");  // die;
     $time=strtotime($cur_time);
     $startTime = date("Y-m-d H:i:s", strtotime('-55 minutes', $time));
$endTime = date("Y-m-d H:i:s", strtotime('+55 minutes', $time));
 $one_hr_les=date("Y-m-d H:i:s", strtotime('-60 minutes', $time));
 //$time_arr['one_hr_les']=$one_hr_les;

  //$time_arr['time_55_less']=$startTime;
// $time_arr['time_55_up']=$endTime;
notify_all -- YES Only if become tutor

Received feedback to stop the job notifications.

Only send notification if a job is unfilled an there is 48 hours remaining. Thus, if a job is unclaimed or a Tutor cancels job and there are less than 48hrs, then system should send Tutor Job Alert

Email Subject: Hot Tutor Job Alert

There are Tutor Jobs available within next 24-48 hours that are unclaimed. Are you available? Please login and claim the Tutor Gig.

Warm regards,

**/
  $page_name='Hot job notify';

session_start();ob_start(); 
 $error ='';
$author = 1;
$datetm = date('Y-m-d H:i:s');
$today = date("Y-m-d H:i:s"); // 
$msg=array();
$msg_error=array();

$time_arr=array();
  $time_arr['curr_time']=date("Y-m-d H:i:s");
      $time_arr['24_hour_back']=date('Y-m-d H:i:s',strtotime('-24 hours'));
      $time_arr['24_hour_next']=date('Y-m-d H:i:s',strtotime('+24 hours'));
        $time_arr['48_hour_next']=date('Y-m-d H:i:s',strtotime('+48 hours'));
 // print_r($time_arr);
/////////////////////
 $link = @mysql_connect('localhost', 'mhl397', 'Developer2!');
mysql_query('SET NAMES utf8');
mysql_select_db('lonestaar', $link);
ini_set("date.timezone", "CST6CDT");       

///////////////////////////

if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}





///////////Notify tutor list: all state=yes///////////////
 $sql_tutor=" SELECT id,f_name,lname,email FROM gig_teachers WHERE notify_all='yes' OR notify_jobs='yes' ";

    $resul_tutor= mysql_query($sql_tutor);
    $arr_notify_tutors=array();// tutor list
    while ($line=mysql_fetch_assoc($resul_tutor)) {
      $arr_notify_tutors[]=$line;
    }

 // print_r($arr_notify_tutors);  die; 

////////////Session list: in Next 48 hours ////////mysql_query
   $sql_session_48_hrs=mysql_query(" SELECT id,ses_start_time,tut_teacher_id,type FROM int_schools_x_sessions_log
WHERE type='intervention' AND tut_teacher_id=0 
AND ses_start_time>= '".$time_arr['curr_time']."' 
AND ses_start_time<= '".$time_arr['48_hour_next']."' ");


 if(mysql_num_rows($sql_session_48_hrs)==0){
  echo 'No sessions found in next 24-48 hrs'; die; 
 }
///////////////////////

  // echo $sql_session_48_hrs; die;

    $sesssion_arr=array();
     while ($line=mysql_fetch_assoc($sql_session_48_hrs)) {
    // $arr_notify_tutors[]=$line['id'];
      //$arr['id']=$line['id'];
      $sesssion_arr[]=$line;
    }


 //print_r($sesssion_arr);
    $msg=array();

/////////Notify session./////
 foreach ($sesssion_arr as $key => $arr) {
  // print_r($arr); die;
  $ses_id=$arr['id'];
  notify_assigned_tutor($arr_notify_tutors,$ses_id);

echo  $msg[]= 'Notifications sent for- Session ID-'.$ses_id;

  die;
   # code...
 }



/////Displau///////////////////

 if(!empty($msg)){

  echo implode('<br/>', $msg);

  die; 
 }



//include('braincert_api_inc.php');
///////Create Intervention Session //////

 

function notify_assigned_tutor($arr_tutors,$ses_id){  // assigned
  $today = date("Y-m-d H:i:s"); // 
  foreach ($arr_tutors as $key => $arr) {
    # code...
   // print_r($arr); die;
  //foreach ($arr_tutors as $tut_id) {
        $last_ses_id=$ses_id;// job_id , or session_id
      $notify_msg='Hot job found, Session ID-'.$ses_id;
      $tutorId=$arr['id']; //$tut_id;
      $f_name=$arr['f_name']; // mysql_query

      $msg_query1=(" INSERT INTO notifications 
        (receiver_id, type, sender_type,type_id, info, created_at) VALUES('$tutorId','job_changed',
        'admin','$last_ses_id', '$notify_msg','$today')");
      //echo $msg_query1 ; echo'<br/>';

      # code...
      // Send Email//
      $message=null;

     $message= "Dear {$f_name},
        <br/><br/>
        There are Tutor Jobs available within next 24-48 hours that are unclaimed. Are you available? <br/>
        Please login and claim the Tutor Gig.<br/>
         <p><strong>Job ID</strong>:{$ses_id}</p>
         <br/><br/>

  Warm Regards,<br/><br/>
  Tutor Gigs Support Team<br/>
  <img alt='' src='https://tutorgigs.io/logo.png'> <br/><br/>
  <span style=' color: blue;font-size: 14px;
    font-style: italic;font-weight: bold;'>A great tutor can inspire, hope ignite the immigration, and instill a love of leaning.</span><br/>

  <span style='font-style: italic;
    font-size: 10px;
    color: blue;'>by Brad Henry</span><br/><br/>
  <span style='color: red;
    font-weight: bold;'>(832) 590-0674</span><br/>  ";


    // Demo Email 
    $message.='<br/><br/><strong>Note:-</strong>  This is demo Email for Hot job found ,Please ignore it.!!!';

    //echo $message; die; 
  $to=$arr['email'];

    

   send_job_email($email='',$to,$message,$f_name);

      //
    }
    /////Send job notification to Tutor.///



}

///Email/////////


function send_job_email($email,$to,$message,$f_name){
 
$subject = "Hot Tutor Job Alert- Tutorgigs.io";
  // learn@intervene.io
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
 //$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
$emailList='rohit@srinfosystem.com';
 $headers.= "Bcc: $emailList\r\n";

 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';

}





//////////////////////////////
##############################

?>