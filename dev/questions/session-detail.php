<?php


// List of slot 
/***
 * 
 * # intAcminEmail(def), school(get),intTH(GET),
 *  tuTH(GET) , 3condition  notification
 * tutAdminEm(def), 
 * //print_r($_POST);die;
    # Get member detail to notify them-
    # delete detail,:master and log
    # Increase +1, in account counter, from school
    #  :::: PAGE fun_cancel_email
 * # sendEmails($user_id,$em_to,$body){ # custom Mail to 1 User
 * 
# restriction on button working
# wrk if remain >7 days to start,: from start time of session's
  # at the time of submission. check
 * @ tutAmin==notify if> tutTeacher assigned::ok
 * **/
include("header.php");
$em_arr=array("int_admin"=>"rohitd448@gmail.com",
    "int_school"=>"",
    "int_teacher"=>"",
    "tut_teacher"=>"",
    "tut_admin"=>"rohit@srinfosystem.com");// all email member
 // 5 type of MailBody,NotifContent==>if member present

//ar_dump($em_arr);
if (!isset($_SESSION['schools_id'])) {
    header("Location: login_principal.php");
    exit();
}
///////////START//////////////
@extract($_POST);@extract($_GET);
$getid=$_GET['id'];// Process session

$sql="SELECT * FROM int_schools_x_sessions_log WHERE 1";
    $sql.=" AND id='$getid' ";
 $ses_det=mysql_fetch_assoc(mysql_query($sql));
 # $_SESSION['schools_id']
 $school=mysql_fetch_assoc(mysql_query("SELECT * FROM schools WHERE SchoolId=".$_SESSION['schools_id']));
 $em_arr['int_school']=$school['SchoolMail'];#iffexist
 // intevene teacher 
  $int_th=mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id=".$ses_det['teacher_id']));
  $District=mysql_fetch_assoc(mysql_query("SELECT district_name FROM loc_district WHERE id=".$school['district_id']));
 
 $Distric=(!empty($District['district_name']))?$District['district_name']:"NA";//emCAN
 
 
/////////Cancel Session//////////

 $rs_noti='';#noti goes to ..
 $msg=NULL;
 /// Validate////////////
 $val1 = date("Y-m-d H:i:s"); #currTime
               $start_date = new DateTime($val1); // 
               $sesStartTime=$ses_det['ses_start_time'];#StartTimeofSession
                $in_sec= strtotime($sesStartTime) - strtotime($val1);///604800>+7 days
               // if($in_sec>=604800)
 
 ////////7 days///// ::604800
  $em_subject="Principal has cancelled scheduled Tutorial session";              
if (isset($_POST['cancel'])&&$in_sec>0) {
     require_once('./inc/function_cancel_email.php');// SendEmail
    $is_int_teacher;$is_tut_teacher;// 3mem default
   $ses_det=mysql_fetch_assoc(mysql_query($sql));
   /////Def. notif////3-NotifcationMust
   
   
   
   $Sesdate=date_format(date_create($ses_det['ses_start_time']), 'd/m/Y');#previous
   $SesTime=date_format(date_create($ses_det['ses_start_time']), 'H:i');#
 
    $emboday="on date--"; // for {March 13, 2018} at {4:30 PM CST} h
   $sesdate.=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');
    $sestime=date_format(date_create($ses_det['ses_start_time']), 'h:i a');# G:ia
    $emtime=$sesdate." at-".$sestime;
     $int_teahcer=$int_th['first_name']." ".$int_th['last_name'];
       $mail_txt="Your Group Tutor Session scheduled for Date-".$sesdate." at-".$sestime." has been cancelled.";
     
    $emboday="<span style='color: red; font-weight:bold;'>Your Group Tutor Session scheduled for ".date_format(date_create($ses_det['ses_start_time']), 'F d,Y')." at"
            . " $sestime has been cancelled.<br/>"
            . "Log in to see the updated schedule.</span>";
    
    
   
    $emboday.="<br/><br/>

<strong>1.School-</strong> ".$school['SchoolName']."<br/>
    <strong>2.District-</strong> $Distric<br/>

<strong>3.Administrator Name-</strong> ".$school['SchoolName']."<br/>
<strong>4.Teacher Name-</strong>$int_teahcer<br/>
<strong>5.Session Information-</strong> $emtime<br/>
";
     //  School and Dirs
   //echo $emboday;die;// intSchool&Tut.Admin 
/////////////////Custom mail body
   
   ///////////////
   $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['int_admin'],$body=$emboday);  
   
   $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['int_school'],$body=$emboday);  
     
   $msg.="notification send to intervene admin, Tutorgigs admin<br/>";
    /////Def. notif////
   if($ses_det['teacher_id']>0){ //$int_th
       
       $em_arr['int_teacher']=$int_th['email'];//"rohit@srinfosystem.com";# 
       
       $is_int_teacher=1;
     $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['int_teacher'],$body=$mail_txt); 
     $msg.="notification send to Teacher-".$em_arr['int_teacher']."<br/>";
   }
   if($ses_det['tut_teacher_id']>0){
       
       $em_arr['tut_teacher']="int_th@gmail.com";
        $tut_th=mysql_fetch_assoc(mysql_query("SELECT * FROM gig_teachers WHERE id=".$ses_det['tut_teacher_id']));
       $em_arr['tut_teacher']=$tut_th['email']; //"tut_th@gmail.com";
       $is_int_teacher=1;
       $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['tut_admin'],$body=$emboday);
     $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['tut_teacher'],$body=$mail_txt); 
     $msg.="notification send to TutorAdmin-".$em_arr['tut_admin']."<br/>";
     $msg.="notification send to Tutor-".$em_arr['tut_teacher']."<br/>";
   }
   # ValidateTime168Hr>ORnot
  ///print_r($rs_noti);die;
   # 1. notify,2.delete.3.decrease1. 
   //print_r($em_arr);die;
     // delete details #  int_schools_x_sessions_log # int_slots_x_student_teacher
   # slot_id
   $d1=mysql_query(" DELETE FROM int_slots_x_student_teacher WHERE slot_id=".$getid);
   $d2=mysql_query(" DELETE FROM int_schools_x_sessions_log WHERE id=".$getid);
   #3.crease:1 sess in account.
    $q=" UPDATE schools SET avaiable_slots='".$currAvlSLot."' WHERE SchoolId='".$_SESSION['schools_id']."' ";
    $remain_ses=intval($school['avaiable_slots'])+1;
  $a=mysql_query(" UPDATE schools SET avaiable_slots='".$remain_ses."' WHERE SchoolId='".$_SESSION['schools_id']."' "); //+1
  
  header("Location:view-sessions.php");exit;
  //print_r($rs_noti);die;
   //echo $rs_noti;
/////////////////

   // $msg
    
}
/////////Cancel Session//////////


//////////////////////////////
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
    
 <?php  include "school_header.php";#?>
   
    
 
    <form name="principal_action" id="principal_action" method="POST" action="">
        
<!--        <p class="text-center text-success">Sucess message goes here...</p>-->
        <table class="table table-hover">
           
           
          
            
          
              <tr>
                <td> 
              <strong class="text-success">Sessions Date/Time:</strong>  
                </td>
                 <?php $seTime=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');
                  $atTime=date_format(date_create($ses_det['ses_start_time']), 'h:i a');
                 ?>
                
              <td> 
              <strong class="text-success"> <?=$seTime?> at- <?=$atTime?></strong>  
                </td>
                  
                
            </tr> 
            
            
            
             <?php 
            $techer= mysql_fetch_assoc(mysql_query(" SELECT * FROM users WHERE id='".$ses_det['teacher_id']."' ")); 
            //  Student name .. 
            $q=" Select sd.first_name,sd.middle_name,sd.last_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='$getid' ";
            $resss=mysql_query($q);
            $stud_str=array();
            while ($row= mysql_fetch_assoc($resss)) {
                //  first_name middle_name   	last_name 
                $stud_str[]=$row['first_name']." ".$row['middle_name']." ".$row['last_name'] ;
            }
            
           // $stud_str[]="Rohit";
            
            $st_class=($ses_det['tut_status']=="STU_ASSIGNED")?"btn btn-success btn-sm":"btn btn-danger btn-sm";  
            ?>
            
            <tr>
                <td> 
              <strong class="text-success">Status:</strong>  
                </td>
                
                
              <td> 
               <?php if($ses_det['tut_status']=="STU_ASSIGNED"){?>   
           <span class="btn btn-success btn-sm">Assigned</span>
               <?php }else{?>
      <span class="btn btn-danger btn-sm">Not Assigned</span>  
                 <?php }?>
              
              </td>
                  
                
            </tr>
            
            <tr>
                <td> 
              <strong class="text-success">Students:</strong>  
                </td>
                
                
              <td> 
              <?php if($ses_det['tut_status']=="STU_ASSIGNED"){?>   
              List-<?=(count($stud_str>=1))? implode(",", $stud_str):"NA";  }?>
              
              </td>
                  
                
            </tr>
            
            
            
             <tr>
                <td> 
              <strong class="text-success">Teacher:</strong>  
                </td>
                
                
              <td> 
              <strong class="text-success"><?=$techer['first_name']?> </strong>  
                </td>
                  
                
            </tr>
            
           
            
             
            
            
            
            
              
            
             <tr>
                <td> 
              <strong class="text-success">Action:</strong>  
                </td>
                
                
              <td> 
            
               
              
                  
              
              <?php
               $val1 = date("Y-m-d H:i:s"); #currTime
               $start_date = new DateTime($val1); // 
               $sesStartTime=$ses_det['ses_start_time'];#StartTimeofSession
                $in_sec= strtotime($sesStartTime) - strtotime($val1);///604800 #days>+7 days
               
 
 
 
             
               
              ?>
                <?php  if($in_sec>=172800){  ?>
                  
                  <a  title="Edit,This session"
                      href="session-edit.php?id=<?=$getid?>" class="btn btn-danger">Edit</a>   
                  <?php } ?>
              <?php  
              // if($in_sec>=604800){
              if($in_sec>0){  ?>
                  <button type="submit"class="btn btn-danger" title="Cancel session"
                      name="cancel" value="ok">Cancel</button>
               <?php } ?>
                  
                  
                  
                  
                  
                </td>
                  
                
            </tr>
            
          
            
            
            
            
        </table>
        
        
        
        
        
        
        
    </form>
    
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