<?php
/*****
 * @ Add multiple Teacher, in session.
 * 
 * Single session edit 
 * @ start and End time edit
 * # case1 if tacher edit , past student removed
 * -- && notify students 
 * @ if only time change then- notift teacher as time changed by school princple :: before 2 days , 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * ***/

include("header.php");
$day_arr=array("Sun"=>"Sunday", "Mon"=>"Monday",
     "Tue"=>"Tuesday",
     "Wed"=>"Wednesday",
     "Thu"=>"Thursday", 
     "Fri"=>"Friday", "Sat"=>"Saturday");



if (!isset($_SESSION['schools_id'])) {
    header("Location: login_principal.php");
    exit();
}
 $today = date("Y-m-d H:i:s");   
$error=NULL;
    $error="New slot created ...";
     $tab_ses="int_schools_x_sessions_log";// log
    
    
//Edit Tutor sessions :: schools 
//var_dump($_POST);
    $msg_error=NULL;// all message while creating session
   @extract($_POST);
   
   include("fun_date_inc.php");// Fun 
    $week_str=NULL; # $_POST['week_day']
    // if(isset($_POST['ses_submit'])){
/// Edit sessiuon

    
    
    
    
    
  ///////////START::Edit/////////////
    $em_arr=array("int_admin"=>"rohitd448@gmail.com",
    "int_school"=>"",
    "int_teacher"=>"",
    "tut_teacher"=>"",
    "tut_admin"=>"rohit@srinfosystem.com");// all email member
@extract($_POST);@extract($_GET);
$getid=$_GET['id'];// Process session

$sql="SELECT * FROM int_schools_x_sessions_log WHERE 1";
    $sql.=" AND id='$getid' ";
 $ses_det=mysql_fetch_assoc(mysql_query($sql));
 //@extract($ses_det);
 # $_SESSION['schools_id']
 
 
  $school=mysql_fetch_assoc(mysql_query("SELECT * FROM schools WHERE SchoolId=".$_SESSION['schools_id']));
 $em_arr['int_school']=$school['SchoolMail'];#iffexist
 // intevene teacher 
  $int_th=mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id=".$ses_det['teacher_id']));
  $District=mysql_fetch_assoc(mysql_query("SELECT district_name FROM loc_district WHERE id=".$school['district_id']));
 
 $Distric=(!empty($District['district_name']))?$District['district_name']:"NA";//emCAN
 
/////////Cancel Session//////////
/// submit_edit .cancel

# require_once('./inc/function_cancel_email.php');// SendEmail
 $rs_noti='';#noti goes to ..
 $msg=NULL;
 /// Validate////////////
 $val1 = date("Y-m-d H:i:s"); #currTime
               $start_date = new DateTime($val1); // 
               $sesStartTime=$ses_det['ses_start_time'];#
                $in_sec= strtotime($sesStartTime) - strtotime($val1);///172800>+2 days
              
 
 ////////7 days///// ::

    $em_subject="Principal has changed scheduled Tutorial session";          
                
$msg=NULL;
  if (isset($_POST['ses_submit'])&&$in_sec>=172800) {  
     $can_create=1; #can change time 
      
     // ses_start_date
     //var_dump($_POST); die;
      $your_date = date("Y-m-d", strtotime($_POST['ses_start_date']));# Y-m-d
      
      $date=$your_date;
      $dt= $start=date('Y-m-d H:i:s', strtotime($date));  // 2012-04-20
      
      $ii=0;
        if(!empty($_POST['hour']))
      $hh=$_POST['hour'];
      if(!empty($_POST['hour'])&&$_POST['hr_type']=="pm"&&$_POST['hour']<12)
      $hh=$_POST['hour']+12;// H 24 form
      
      if(!empty($_POST['minnute']))
      $ii=$_POST['minnute'];
      
      
      
$dt= date('Y-m-d H:i',strtotime('+'.$hh.'hour +'.$ii.' minutes',strtotime($start))); # add Hour, min.

 $sesStartTime=$dt;
 //echo $sesStartTime.'-- Sart time'; 
 $sesEndTime= date('Y-m-d H:i',strtotime('+55 minutes',strtotime($sesStartTime))); 
 
 
  //    $sesStartTime  $sesEndTime
     // echo 'St-'.$sesStartTime ; echo 'Ed-'.$sesEndTime ; die;
      
      
       // if($_POST['hr_type']=="am"&&$_POST['hour']<6){
         if ($_POST['hr_type'] == "am" && ($_POST['hour'] < 6 || $_POST['hour'] == 12)) {   
        $msg .= "Session time must be in between 6 AM to 9 PM.<br/>";
         $can_create=0;}
         if($_POST['hr_type']=="pm"&&$_POST['hour']>9&& $_POST['hour'] != 12) {
         //$msg.="Above 9pm not aloowed<br/>";
         $msg .= "Session time must be in between 6 AM to 9 PM.<br/>";
         $can_create=0;}
      
      
      //  echo  $msg; die;
      
      
      
      
      
     ////////////////////
      $ses_det=mysql_fetch_assoc(mysql_query($sql));
        $old_start_date=date_format(date_create($ses_det['ses_start_time']), 'Y-m-d');#previous
     $ses_start_date1=(!empty($ses_start_date))?$ses_start_date:$old_start_date;
     
     
     
     /////////////////////
     //$dateStart = new DateTime($ses_start_date1); // StarttimeDate
     $dateStart = new DateTime($your_date); // StarttimeDate
    $now = new DateTime();

 require_once('./inc/function_cancel_email.php');// SendEmail
    $is_int_teacher;$is_tut_teacher;// 2mem default::int
  
    $Sesdate=date_format(date_create($ses_det['ses_start_time']), 'd/m/Y');#previous
     
    
    $pasDate=date_format(date_create($ses_det['ses_start_time']), 'Y-m-d');#previous
    
   $SesTime=date_format(date_create($ses_det['ses_start_time']), 'H:i');#
  // $mail_txt="Your Group Tutor Session scheduled for Date-".$Sesdate." at-".$SesTime." has been changed.";
     $today_time= date("Y-m-d H:i:s");
  // $diff= strtotime($your_date) - strtotime($pasDate);//
     
    $diff= strtotime($sesStartTime) - strtotime($today_time);// ONly futureDate
    //echo $your_date;echo '<br/>';
//var_dump($diff) ; die;
 
  
//if($date < $now) {
   
   // Wrong : 6 - am , 9 pm
   
   
  
    if($diff>=0&&$can_create==1) {    
     // $s=$_POST['time'];
   
    $endtime=$_POST['time'].":55";// Def 55 min time end tim 
      
if(!empty($ses_start_date)&&!empty($time)){
          $new_start_time=$ses_start_date." ".$time;
          
          
         
      }else{
       $new_start_time=$old_start_date." ".$time;  
      }
     # new end time 
      if(!empty($ses_start_date)&&!empty($endtime)){
          $new_end_time=$ses_start_date." ".$endtime;
        
         
      }else{
       $new_end_time=$old_start_date." ".$endtime;  
      }
      
      ///Edit:infoEmail///
      # new efmail foramt for int
      
      $emboday="on date--"; // for {March 13, 2018} at {4:30 PM CST} h
  // $sesdate.=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');
       $sesdate=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');
    $sestime=date_format(date_create($ses_det['ses_start_time']), 'h:i a');
    $emtime=$sesdate." at-".$sestime;
     $int_teahcer=$int_th['first_name']." ".$int_th['last_name'];
     $mail_txt="Your Group Tutor Session scheduled for Date-".$sesdate." at-".$sestime." has been changed."; 
    $emboday="<span style='color: red; font-weight:bold;'>Your Group Tutor Session scheduled for ".date_format(date_create($ses_det['ses_start_time']), 'F d,Y')." at"
            . " $sestime has been changed.<br/>"
            . "Log in to see the updated schedule.</span>";
    
    
   
    $emboday.="<br/><br/>

<strong>1.School-</strong> ".$school['SchoolName']."<br/>
    <strong>2.District-</strong> $Distric<br/>

<strong>3.Administrator Name-</strong> ".$school['SchoolName']."<br/>
<strong>4.Teacher Name-</strong>$int_teahcer<br/>
<strong>5.Session Information-</strong> $emtime<br/>
";
     //  School and Dirs
     // echo $emboday;die;
      
      /////////////////////////
   $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['int_admin'],$body=$emboday);  
   $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['int_school'],$body=$emboday);  
     
   $msg.="notification send to intervene admin, intervene school<br/>";
    /////Def. notif////
   if($ses_det['teacher_id']>0){  // OlD teacher
      
       $em_arr['int_teacher']=$int_th['email'];//"rohit@srinfosystem.com";#
       
       $is_int_teacher=1;
     $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['int_teacher'],$body=$mail_txt); 
     $msg.="notification send to Teacher-".$em_arr['int_teacher']."<br/>";
   }
   if($ses_det['tut_teacher_id']>0){
       // TutorEmail
     $tut_th=mysql_fetch_assoc(mysql_query("SELECT * FROM gig_teachers WHERE id=".$ses_det['tut_teacher_id']));
       $em_arr['tut_teacher']=$tut_th['email'];"tut_th@gmail.com";
       $is_int_teacher=1;
       $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['tut_admin'],$body=$emboday);
     $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['tut_teacher'],$body=$mail_txt);  
     $msg.="notification send to Tutor-".$em_arr['tut_teacher']."<br/>";
   }
   ///Edit:infoEmail///
  // print_r($rs_noti); die;
     //////////// 
      
      
      // teacher Change
      if($teacher_id!=$ses_det['teacher_id']){
          // update :delete slot student,of teacher. 1 ses=1teacherstudent
        $d1=mysql_query(" DELETE FROM int_slots_x_student_teacher WHERE slot_id=".$getid);  
        # teacher changed:Update, all teacher related field, Assigned in slot
        // quiz_id  class_id special_notes special_notes
         //    $sesStartTime  $sesEndTime
        $new_start_time=$sesStartTime;$new_end_time=$sesEndTime;#1
        $q=" UPDATE int_schools_x_sessions_log SET ses_start_time='".$new_start_time."',"
                . "teacher_id='$teacher_id',quiz_id='0',special_notes='', "
                . "class_id='0',grade_id='0', "
              . "ses_end_time='$new_end_time' "
                 . " WHERE id='".$getid."' ";
        
      }else{
         $same=1;
          $new_start_time=$sesStartTime;$new_end_time=$sesEndTime;#1
       $q=" UPDATE int_schools_x_sessions_log SET ses_start_time='".$new_start_time."',teacher_id='$teacher_id', "
              . "ses_end_time='$new_end_time' "
                 . " WHERE id='".$getid."' ";  
      }
      // teacher Change
    // Update time start and end
       
         $insert=mysql_query($q)or die(mysql_error());
         //print_r($rs_noti);
         header("Location:view-sessions.php");exit;
     # if teacher id :change then- remove old students, then-entry 
    
  
}else{ // future date
        $msg.= 'Start session date and time must be greater than now.<br/>'; //die;
     
   /// Upde date   
      
      
    
}
       
  
  ///////////////////
   # 1. notifyMember,2.delete.3.Edit: Save sessui
  
  // $d1=mysql_query(" DELETE FROM int_slots_x_student_teacher WHERE slot_id=".$getid);
  // $d2=mysql_query(" DELETE FROM int_schools_x_sessions_log WHERE id=".$getid);
  

   // $msg
    
}
/////////Edit A Session//////////
  
    
    
    



///////////////////////
 $email = $_SESSION['temp_email'];
    $firstname = $_SESSION['temp_firstname'];
    $lastname = $_SESSION['temp_lastname'];
    $dist_mail_name =  $_SESSION['temp_dist_name'];
    $master_school_name = $_SESSION['temp_master_school'];
    $school_mail_name = $_SESSION['temp_school_name'];
    $phone_number = $_SESSION['temp_phone'];
    $smart_preb_name = $_SESSION['temp_smart_preb'];
    $data_dash_name = $_SESSION['temp_data_dash'];
    $address = $_SESSION['temp_address'];
    $city = $_SESSION['temp_city_name'];
    $zipcode = $_SESSION['temp_zipcode'];
    $billing_state = $_SESSION['temp_billing_state'];
    if($_SESSION['temp_email']){
?>
       <script type="text/javascript">
            var _dcq = _dcq || [];
            var _dcs = _dcs || {};
            _dcs.account = '7926835';

            (function() {
              var dc = document.createElement('script');
              dc.type = 'text/javascript'; dc.async = true;
              dc.src = '//tag.getdrip.com/7926835.js';
              var s = document.getElementsByTagName('script')[0];
              s.parentNode.insertBefore(dc, s);
            })();
          _dcq.push(["identify", {
            email: "<?php print $email; ?>",
            first_name: "<?php print $firstname; ?>",
            last_name: "<?php print $lastname; ?>",
            your_school: "<?php print $school; ?>",
            billing_address: "<?php print $address; ?>",
            phone_number: "<?php print $phone; ?>",
            billing_city: "<?php print $city; ?>",
            data_dash: "<?php print $data_dash_name; ?>",
            billing_zipcode: "<?php print $zipcode; ?>",
            billing_state: "<?php print $billing_state; ?>",
            tags: ["Customer Admin"]
          }]);

          </script>
       
       
     <?php
    }
    
        if( isset($_SESSION['temp_email']) )unset($_SESSION['temp_email']);
        if( isset($_SESSION['temp_firstname']) )unset($_SESSION['temp_firstname']);
        if( isset($_SESSION['temp_lastname']) )unset($_SESSION['temp_lastname']);
        if( isset($_SESSION['temp_dist_name']) )unset($_SESSION['temp_dist_name']);
        if( isset($_SESSION['temp_master_school']) )unset($_SESSION['temp_master_school']);
        if( isset($_SESSION['temp_school_name']) )unset($_SESSION['temp_school_name']);
        if( isset($_SESSION['temp_phone']) )unset($_SESSION['temp_phone']);
        if( isset($_SESSION['temp_smart_preb']) )unset($_SESSION['temp_smart_preb']);
        if( isset($_SESSION['temp_data_dash']) )unset($_SESSION['temp_data_dash']);
        if( isset($_SESSION['temp_address']) )unset($_SESSION['temp_address']);
        if( isset($_SESSION['temp_city_name']) )unset($_SESSION['temp_city_name']);
        if( isset($_SESSION['temp_zipcode']) )unset($_SESSION['temp_zipcode']);
        if( isset($_SESSION['temp_billing_state']) )unset($_SESSION['temp_billing_state']);

if($_GET['view'] == 'teacher' && $_GET['id'] > 0) {
     $login_teacher = "SELECT * FROM users WHERE id = " . $_GET['id'];
    $teacher_data = mysql_fetch_assoc(mysql_query($login_teacher));

    $_SESSION['login_id'] = $teacher_data['id'];
    $_SESSION['login_user'] = $teacher_data['user_name'];
    $_SESSION['login_mail'] = $teacher_data['email'];
    $_SESSION['login_role'] = 1;
    $_SESSION['login_status'] = 1;
     echo "<script type='text/javascript'>window.location.href = 'folder.php';</script>";
	exit();
}

/* Process logout */
if (isset($_POST['logout'])) {
    unset($_SESSION['schools_id']);
    header("Location: login.php");
    exit();
}
$error = '';
if ($_POST['upload_logo']) {
    // print_r($_FILES); die;
    $cwd = getcwd();
    $uploads_dir = $cwd . '/uploads/schoollogo';

    $school_logo_res = mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE `SchoolId` = {$_SESSION['schools_id']}"));
    if (trim($school_logo_res['schoollogo']) != '') {
        unlink($uploads_dir . '/' . $school_logo_res['schoollogo']);
    }
    $tmp_name = $_FILES["schoollogo"]["tmp_name"];
    // basename() may prevent filesystem traversal attacks;
    // further validation/sanitation of the filename may be appropriate
    $name = $_SESSION['schools_id'] . '_' . basename($_FILES["schoollogo"]["name"]);
    //echo "$uploads_dir/$name"; die;

    move_uploaded_file($tmp_name, "$uploads_dir/$name");
    mysql_query("UPDATE schools SET schoollogo = \"" . $name . "\" WHERE `SchoolId` = {$_SESSION['schools_id']}  ");
    $error = 'Logo has been updated successfully.';
}
$school = mysql_fetch_assoc(mysql_query("SELECT s.*, d.district_name FROM `schools` s LEFT JOIN loc_district d ON s.district_id = d.id WHERE `SchoolId` = {$_SESSION['schools_id']}"));
$folders = mysql_query("
	SELECT *
	FROM `terms`
	WHERE `id`
	IN (
		SELECT `termId`
		FROM `purchasemeta`
		WHERE `purchaseId` = (
			SELECT `id`
			FROM `purchases`
			WHERE `schoolID` = {$_SESSION['schools_id']}
		)
	)
	ORDER BY `name` ASC
");

function sendEmailNoticeShared($email, $teacher) {
    require 'inc/PHPMailer-master/PHPMailerAutoload.php';

    $sqluser = "SELECT * FROM users WHERE id = " . $teacher;
    $sqlQu = mysql_query($sqluser);

    $toData = mysql_fetch_assoc($sqlQu);
    $to = $toData['email'];

    $message = "Dear {$toData['first_name']},
	<br /><br />
	Congratulations, you now have access to the Intervene Question Database.<br />
	Login https://intervene.io/questions/login.php to get started! Let us know if you need any help.
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br/>
	www.intervene.io<br />
	<br /><br />
	<img alt='Less Test Prep' src='https://intervene.io/questions/images/logo.png'>";

    // Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set who the message is to be sent from
    $mail->setFrom('pathways2greatness@gmail.com', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
    // Set the subject line
    $mail->Subject = 'Access Granted to Intervene Question Database';
    // Replace the plain text body with one created manually
    $mail->Body = $message;
    $mail->AltBody = $message;
    // send the message, check for errors
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}




/* Process share folders */
if (isset($_POST['share'])) {
    $userId = $_POST['share'];
    
    mysql_query("DELETE FROM `techer_permissions` WHERE `school_id` = {$_SESSION['schools_id']} AND `teacher_id` = {$userId}");
    if (isset($_POST['teacher_permission'][$userId])) {
        
        foreach ($_POST['teacher_permission'][$userId] as $item) {
            $grade_res = mysql_fetch_assoc(mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category'  AND id IN ({$item}) AND `active` = 1"));
            mysql_query('INSERT INTO techer_permissions SET '
                    . 'teacher_id = \''.$userId.'\' , '
                    . 'permission = \'data dash\' , '
                    . 'grade_level_id = \''.$item.'\' , '
                    . 'grade_level_name = \''.$grade_res['name'].'\' , '
                    . 'school_id = \''.$_SESSION['schools_id'].'\' ');
        }
    }
    if (isset($_POST['folders'][$userId])) {
        // Delete existed data
        mysql_query("DELETE FROM `shared` WHERE `schoolId` = {$_SESSION['schools_id']} AND `userId` = {$userId}");

        // Insert new data
        foreach ($_POST['folders'][$userId] as $item) {
            mysql_query("INSERT INTO `shared` (`schoolId`, `userId`, `termId`) VALUES ({$_SESSION['schools_id']}, {$userId}, {$item})");
        }

        //Send email
        sendEmailNoticeShared($school['SchoolMail'], $userId);
    } else {
        echo "<script type='text/javascript'>alert('Please choose your registered folder(s) to share!');</script>";
    }
}

/* Process revoke */
if (isset($_POST['revoke'])) {
    $userId = $_POST['revoke'];
    mysql_query("DELETE FROM `shared` WHERE `schoolId` = {$_SESSION['schools_id']} AND `userId` = {$userId}");
    mysql_query("DELETE FROM `techer_permissions` WHERE `school_id` = {$_SESSION['schools_id']} AND `teacher_id` = {$userId}");
}

$users = mysql_query("
	SELECT `users`. * , GROUP_CONCAT( `shared`.`termId` SEPARATOR ',' ) AS shared_terms
	FROM `users`
	LEFT JOIN `shared` ON `users`.`id` = `shared`.`userId`
	WHERE `users`.`school` = {$_SESSION['schools_id']}
	GROUP BY `users`.`id` 
");

$registered_folders = array();
$arra_grade_id = array();
if (mysql_num_rows($folders) > 0) {
    while ($folder = mysql_fetch_assoc($folders)) {
        $registered_folders[$folder['id']] = $folder['name'];
        $arra_grade_id[] = $folder['id'];
    }
}

$students_result = mysql_fetch_assoc(mysql_query('SELECT COUNT(id) as count FROM students WHERE school_id = \''.$_SESSION['schools_id'].'\' '));

?>

<div class="container">
      <?php  include "school_header.php";?>
</div>
          
          
          
          
          
   <!----Edit Tutor session------>
   <div class="container">
   <div id="content" class="col-md-12">
				<div id="single_question" class="content_wrap">
					<div class="ct_heading clear">
						<h3><i class="fa fa-plus-circle"></i>
                                                    <?php  //echo $result?'Edit':'Add';?> Edit Tutor session</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
	<form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
		<?php if(isset($msg)){?>					
            <p class="text-danger"><strong><?=$msg?></strong>
   <!--    <a href="view-sessions.php"  class="btn btn-success btn-xs">view Tutor sessions</a>-->
                </p><br/> <?php } 
                
                
                ?>
                
                <?php if(isset($msg_error)){?>					
            <p class="text-danger"><strong><?=$msg_error?></strong>
           
                </p><br/> <?php }?>
                
                
            
            <h4>Edit Tutor session</h4>
							<div class="add_question_wrap clear fullwidth">
                                                        
                                                            
                                                            <div class="row">  
                                                      
                                      <strong class="text-danger">Session Date:
                <?=$olddate=date_format(date_create($ses_det['ses_end_time']), 'F d,Y'); ?></strong>                  
                                                    <br/>
                                      
                                                    <input   type="hidden"  name="old_date" />         
                                                    
                                      <p class="col-md-3">
                                                                 <?php 
                                                                // $today = date("d/m/Y");   // def
                                                                 //$fddf=dat("d/m");
                                                               
                                                         $date = date_create($ses_det['ses_start_time']);
                                                                  
                                                            $old_start= date_format($date, 'H:i');
                                                                
                                         $old_end= date_format(date_create($ses_det['ses_end_time']), 'H:i');         
                                                           //echo    $old_end;
                                                              
                                       $mdate= date_format(date_create($ses_det['ses_start_time']), 'm/d/Y'); 
                                       $Hour= date_format(date_create($ses_det['ses_start_time']), 'H'); 
                                       $Min= date_format(date_create($ses_det['ses_start_time']), 'i');
                                       $Type= date_format(date_create($ses_det['ses_start_time']), 'a');
                                      
                                       $Hour=($Hour>12)?$Hour-12:$Hour;
                                        //echo $Min, $Type;
                                       ?>   
                                                                   
                                          <label for="lesson_name"> <strong class="text-danger">Choose New Date</strong>  </label>
                                                         
                                                          
                                          <input name="ses_start_date" value="<?=$mdate?>" class="datepicker" data-date-format="mm/dd/yyyy">
                                                          
                                                          
                                                          
                                                         
								</p> 
                                                         </div>
                                                         
                                                            
                                                            
                                                            <div class="row">
                                                              
                                                          <p class="col-md-6">
                                       <label for="distrator_id" ><strong class="text-danger">Start time :</strong>
                                       </label><br/>
                                                             
                                       <select class="col-md-2" name="hour"style="
                                               width:200px"  id="time" class="required textbox">
                                           <option selected="" >hour</option>                       
                                                    <?php
                                                    $i=1;
                                               while($i<= 12){
                                           $sel=($i==$Hour)?"selected":NULL; 
                                                   
                                                   ?>
  
                                     <option  <?=$sel?>
                                         value="<?=$i?>" ><?=$i?></option>                          
                                                                
                                                       <?php
                                                 $i++;   }


                                                    
                                                    ?> 
                                                   </select>
                                       
                                       <?php 
                                       //$af=array("05",);
                                       
                                       ?>
                                        <select  class="col-md-3" name="minnute"  class="required textbox">
                                           <?php 
                                           $k=0;
                                            while($k<=55){
                                             $sel=($k==$Min)?"selected":NULL;     
                                                
                                               $kff=($k>5)?$k:'0'.$k; 
                                           ?>                            
                                       <option  <?=$sel?>  value="<?=$k?>"><?=$kff?></option> 
                                            <?php  $k=$k+5;  } ?>
                                    
                                        
                                        </select> 
                                      <?php 
                                      $tArr=array('am','pm');
                                      ?>
                                            <select  class="col-md-2" name="hr_type"  class="required textbox">
                                             <?php foreach($tArr as $val){
                                             $sel=($val==$Type)?"selected":NULL;        
                                                 ?>                         
                                <option <?=$sel?>   value="<?=$val?>"><?= strtoupper($val)?></option> 
                                 <?php  } ?>
                                       
                                       </select> 
                                                                     
                                                                                         
                                                                                                            
                                                                
                                                        
                                                  
                                       
                                       
                                                                
                                                                    
                                                                </p> 
                                                                
                                                   
                                                                
                                                                
                                                                                 
                                                                
                                                                
                                                                
                                                                
                                                            </div>             
								
                                                          
                                                  
                                                                <!---End date --->
                                                                
                                                      <script>
                                                         
$(document).ready(function(){
   
     $('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
});
   
   ///////////////////
    $("#recurrence_div").hide();// def
     $("#week_days_div").hide();
    $("#hideddd").click(function(){
        
        $("#recurrence_div").hide();
    });
    $("#shwrecurrence").click(function(){
        $("#recurrence_div").show();
    });
  ////////Repeat time///////// 
     $("#repeat_type").change(function(){
        var vl= $('#repeat_type').val();
        // alert('value--'+vl);
         if(vl=="days"){ // exp date # week_days_div
           $("#expired_date_div").show();
            $("#week_days_div").hide();
         }else if(vl=="week"){ // Show 7 days to choose
             $("#expired_date_div").hide(); 
              $("#week_days_div").show();
             
         }
       // $("#expired_date_div").show();
        // else : if week then-- hide 
    });
    
    
    
});


</script>
                                                                
                                                          
                                                          
                                                                                
                                                          
                                                             
                                                       
                                
                                
                                
                         <!--- Custom recurrence-->
                                
                                 
                         
                        
                                                                
                             <!--- Custom recurrence-->            
                                                                
                                                                
                                                        
                                      <p>
		<label for="distrator_id">Choose Teachers:</label>
			<?php
                                                               
     $teacher= mysql_query("
	SELECT `users`. * , GROUP_CONCAT( `shared`.`termId` SEPARATOR ',' ) AS shared_terms
	FROM `users`
	LEFT JOIN `shared` ON `users`.`id` = `shared`.`userId`
	WHERE `users`.`school` = {$_SESSION['schools_id']}
	GROUP BY `users`.`id` 
");    if( mysql_num_rows($teacher) > 0 ) {   
    
    
    ?>
              <select name="teacher_id" id="teacher_id"  class="required textbox">
                  <option value="0">Select Techer</option>
                  <?php
		while($data= mysql_fetch_assoc($teacher) ) {
             $sel=($data['id']==$ses_det['teacher_id'])?"selected":NULL;       
                    ?>
                   <option <?=$sel?> value="<?=$data['id']?>"><?=$data['first_name']?></option> 
                    <?php  } ?>
                                                                                
                                                                               
		
										
                                                                                
                                                                     
                                                                        
                                                                        
                                                                                
									</select>
									<?php } ?>
								</p>
								
                                     
                                                                
                                                                
								
								<!----<p>
									<label for="lesson_desc">Description:</label>
									<textarea name="lesson_desc" id="lesson_desc" class="textbox" rows="5">
                                                                           </textarea>
								</p>---->
                                                                
                                                       <?php
               $val1 = date("Y-m-d H:i:s"); #currTime
               $start_date = new DateTime($val1); // 
               $sesStartTime=$ses_det['ses_start_time'];#StartTimeofSession
                $in_sec= strtotime($sesStartTime) - strtotime($val1);///604800 #days>+7 days
               
 
 
 
             
               
              ?>          
                                                                
                                                                
                                                                
                                                                
							</div>
                                      <?php  if( mysql_num_rows($teacher) >0&&$in_sec>=172800) {?>
							<p>
					
			<input type="submit" name="ses_submit" id="ses_submit" class="form_button submit_button" value="Submit" />
								
                   <input type="reset" name="lesson_reset" id="lesson_reset" class="form_button reset_button" value="Reset" />
							</p>
                                     <?php }else echo "You can not edit this session"; ?>
                                                        
						</form>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
				</div>
			</div>		<!-- /#content -->
                        
                        </div> 
          
          
          
          
          
          
          
          

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invitations</h4>
            </div>
            <div class="modal-body">
                <section id ="contact">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="header-section text-center">
                                <h2>Invite Your Teachers</h2>
                                <p>You can either copy and paste the URL below and send your own invite<br>or just insert your teachers' emails and we'll send it!<br>Here's the link your teachers need: <a style="color:blue">http://www.intervene.io/questions/signup.php</a></p>
                                <hr class="bottom-line">
                            </div>

                            <form id="form-invitation" method="post" action="email-invite.php">
                                <div id="mailboxes" class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                </div>

                                <p>Please select registered grades to share:</p>
                                <?php
                                if (count($registered_folders) > 0) {
                                    echo "<div class='row'>
                                        <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6' style='border: 2px solid;padding: 0px;margin: 0px -4px 0px 3px;'>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align: center;'>
                                         <b>SmartPrep</b>
                                        </div>";
                                    foreach ($registered_folders as $key => $val) {
                                        echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
						<input type='checkbox' class='folders' name='folders[]' value='{$key}' /> {$val}
						</div>";
                                    }
                                    echo "</div>";
                                    
                                ?>
                                <?php
                            $school_data_dash_res = mysql_query('SELECT * FROM school_permissions WHERE school_id = \'' . $_SESSION['schools_id'] . '\' ');
                            if (mysql_num_rows($school_data_dash_res) > 0) {
                                echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6' style='border: 2px solid;margin-left: 10px;padding: 0;width:48%;'>
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align: center;'>
                                    <b>Data Dash</b>
                                </div>";
                                while ($school_permission = mysql_fetch_assoc($school_data_dash_res)) {
                                    ?>
                                <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
                                <input type="checkbox"  name="teacher_invite_permission[] ?>" value="<?php print $school_permission['grade_level_id'] ?>"  /><?php print $school_permission['grade_level_name'] ?>
                                </div>
                                <?php }
                                echo "</div>";
                            }
                            echo "</div><br>";
                            echo '<input type="submit" name="invite" value="Invite" class="btn btn-primary" >';
                                } else {
                                    echo "<p>Please purchase grades to share!</p>";
                                }
                            ?>
                            </form>
                        </div>
                    </div>
                </section>
            </div>	<!-- /Invite -->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    <?php if ($error != '') { echo "alert('{$error}');"; } ?>
    $(document).ready(function () {
<?php
if (isset($_GET['invited'])) {
    echo 'alert("Invites have been sent to your teachers. Please ask them to check their spam folders in case they don\'t receive it in 5 minutes!");';
    echo 'location.replace("school.php");';
}
?>

        /* Validate invitation form */
        $('#form-invitation').on('submit', function () {
            var validmail = false;
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            $('#mailboxes input').each(function () {
                if (filter.test($(this).val())) {
                    validmail = true;
                    return false;
                }
            });
            if (!validmail) {
                alert('Please enter at least a valid email address!');
                return false;
            }

            var validgrade = false;
            $('#form-invitation input.folders').each(function () {
                if ($(this).is(':checked')) {
                    validgrade = true;
                    return false;
                }
            });

            if (!validgrade) {
                alert('Please select at least a grade to share!');
                return false;
            }
        });

        $('button[name=share]').on('click', function () {
            var checked = false;
            $(this).closest('tr').find('input.folders').each(function () {
                if ($(this).is(':checked')) {
                    checked = true;
                    return false;
                }
            });
            if (checked) {
                return true;
            } else {
                alert('Please choose your registered folder(s) to share!');
                return false;
            }
        });

        $('button[name=revoke]').on('click', function () {
            return confirm('Are you sure you want to revoke this shared folder?') ? true : false;
        });
    });
</script>

<?php include("footer.php"); ?>

<script type="text/javascript">
<?php 


if ($error != '') echo "alert('{$error}')"; ?>
</script>
