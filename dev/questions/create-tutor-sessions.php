<?php
/* * ***
 * @ Add multiple Teacher, in session.

 * ** */

include("header.php");
$day_arr = array("Sun" => "Sunday", "Mon" => "Monday",
    "Tue" => "Tuesday",
    "Wed" => "Wednesday",
    "Thu" => "Thursday",
    "Fri" => "Friday", "Sat" => "Saturday");



if (!isset($_SESSION['schools_id'])) {
    header("Location: login_principal.php");
    exit();
}
$today = date("Y-m-d H:i:s");
$error = NULL;
$error = "New slot created ...";
$tab_ses = "int_schools_x_sessions_log"; // log
//Create Tutor sessions :: schools 
//var_dump($_POST);
$msg_error = NULL; // all message while creating session
@extract($_POST);

include("fun_date_inc.php"); // Fun 
$week_str = NULL; # $_POST['week_day']

$msg = NULL;
$em_subject="Principal has scheduled new tutorial session"; 
 $today = date("Y-m-d H:i:s");
if (isset($_POST['ses_submit'])) {
     require_once('./inc/function_cancel_email.php');// SendEmail
    $embody="You've been assigned a Group Tutor Sessions scheduled for below following date and time.<br/>";
    //$embody.=" <strong>Date-</strong>March,12 2018.<br/>";
   $embody_footer="<br/><br/>Please sign in to see more details, to access lessons, and to launch your session.";
   
   $teacher_email="rohitd448@gmail.com";# rohitsrinfosystem.com
   // $rs_noti[]=sendEmails($user_id=1,$em_to=$teacher_email,$body=$embody);
 
    // var_dump($_POST); die;   # "Y-m-d H:i:s"
    $can_create = 1; #allowed
    $ss_startime = "";
    ////////////////////////////////////
    $data = mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE SchoolId='" . $_SESSION['schools_id'] . "' "));
    $s_id = $_SESSION['schools_id'];
    $th_id = $_POST['teacher_id'];
    // ///////////////// Tutor sessions Date///12: would be in pm(afternoon)
    $_POST['endtime'] = $_POST['time'] . ":55"; # 55 min sess


    $your_date = date("Y-m-d", strtotime($_POST['ses_start_date'])); # Y-m-d

    $stardate = (!empty($_POST['ses_start_date'])) ? $your_date : date("Y-m-d"); #currentDate

    $dt = $start = date('Y-m-d H:i:s', strtotime($stardate));  // 2012-04-20
   
    if ($_POST['hr_type'] == "am" && ($_POST['hour'] < 6 || $_POST['hour'] == 12)) {
        $msg .= "Session time must be in between 6 AM to 9 PM.<br/>";
        $can_create = 0;
    }
    if ($_POST['hr_type'] == "pm" && ($_POST['hour'] > 9 && $_POST['hour'] != 12)) {
        $msg .= "Session time must be in between 6 AM to 9 PM<br/>";
        $can_create = 0;
    }




    $ii = 0;
    if (!empty($_POST['hour']))
        $hh = $_POST['hour'];

    if(!empty($_POST['hour'])&&$_POST['hr_type']=="pm"&&$_POST['hour']<12)
        $hh = $_POST['hour'] + 12; // H 24 form



    if (!empty($_POST['minnute']))
        $ii = $_POST['minnute'];


    $sesStartTime = date('Y-m-d H:i', strtotime('+' . $hh . 'hour +' . $ii . ' minutes', strtotime($start))); # add Hour, min.

    $sesEndTime = date('Y-m-d H:i', strtotime('+55 minutes', strtotime($sesStartTime)));
      //  $msg.="Start-".$sesStartTime;   $msg.="End-".$sesEndTime;   // echo $msg; die;
   // onlly time >
       $at_time=date_format(date_create($sesStartTime), 'h:i a');
       $at_date=date_format(date_create($sesStartTime), 'F d,Y');
       
       
    if (count($_POST['teacher_id']) < 1) {
        $msg .= "Select a teacher<br/>";
        $can_create = 0;
    }

   
   
   
  
    
    //$diff = strtotime($your_date) - strtotime($today); // ONly futureDate
   $diff = strtotime($sesStartTime) - strtotime($today); // ONly futureTime

    if ($diff < 0) {
        $can_create = 0;
        $msg .= 'Start session date and time must be greater than now.<br/>';
    }
    
    
    
     $tot_teacher = count($_POST['teacher_id']); // >1
     
    //////////////Validate all ////////////////////////////////////////////////
    if ($can_create == 1) {
        
        
        if ($_POST['ses_type'] == "ondate") { // 1 creation= tot Teacher
             // $at_date  $at_time :$embody_footer
      
         $embody="You've been assigned a Group Tutor Sessions scheduled for below following date and time.<br/>"
                 . " <strong>Date-</strong> $at_date  <br/><strong>Time-</strong>$at_time.<br/>".$embody_footer;    
            
            $enddate = $start = date('Y-m-d H:i:s', strtotime($stardate));  //onDate  $stardat==$enddate
            //////////////////
          
            $avSes = $data['avaiable_slots'];
            $newTotSes = $tot_teacher;
            if ($newTotSes > $data['avaiable_slots']) {

                $msg = "You can not create $newTotSes sessions since you have $avSes left in your account.";
            } else {
                 

                ///////////////////////////////////////////////////
                for ($k = 0; $k < count($_POST['teacher_id']); $k++) {
                    
                    $int_th=mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id=".$_POST['teacher_id'][$k])); 
                   $teacher_email=$int_th['email'];
                    //////////////////////////
                    $qqq = " INSERT INTO int_schools_x_slots_create SET session_start_time='$dt',"
                            . "session_end_time='" . $_POST['endtime'] . "',started_date='$stardate', "
                            . "ses_type='ondate',"
                            . "school_id='$s_id', "
                            . "created_date='$today',teacher_id='" . $_POST['teacher_id'][$k] . "'";
                    $insert = mysql_query($qqq)or die(mysql_error());
                    $create_id = mysql_insert_id();
                    $ses_end_time = NUUL; # same day add end time
                    ////////Creation


                    $ses_end_time = $sesEndTime;


                    //  	ses_end_time ,,  :: create_id
                    $qqq = " INSERT INTO int_schools_x_sessions_log SET create_id='$create_id',ses_start_time='$sesStartTime',"
                            . "ses_end_time='$ses_end_time',school_id='$s_id',start_date='$stardate', "
                            . "created_date='$today',teacher_id='" . $_POST['teacher_id'][$k] . "'";
                    $insert = mysql_query($qqq)or die(mysql_error());
                 /////////Send Mail..///////////   
                  $rs_noti[]=sendEmails($user_id=1,$em_to=$teacher_email,$embody);   
                   
                    
                }
              ////Ext
              // $rs_noti[]=sendEmails($user_id=1,$em_to='rohitd448@gmail.com',$embody); #TEST  


                $currAvlSLot = ($data['avaiable_slots'] > 0) ? intval($data['avaiable_slots']) - $tot_teacher : 0;
                // $currAvlSLot=($data['avaiable_slots']>0)?intval($data['avaiable_slots'])-1:0; 
                $q = " UPDATE schools SET avaiable_slots='" . $currAvlSLot . "' WHERE SchoolId='" . $_SESSION['schools_id'] . "' ";
                $a = mysql_query($q); //slotUpdated  
                $msg = $error = $tot_teacher . "- New Sessions Created.! ";
            }  // add
        } elseif ($_POST['ses_type'] == "repeat") {  // 1 creation -multi ses.
            $msg_error = NULL; // 



            if ($repeat_type == "week") {
                $can_insert = 0; # insMultiSes




                if (isset($_POST['week_day']) && count($_POST['week_day']) > 0) {
                    $can_insert = 1; # insMultiSes 
                    $week_str = implode(",", $_POST['week_day']);
                    //echo ' week '; die;
                }
                if (!isset($_POST['week_day']) && count($_POST['week_day']) < 1)
                    $msg .= count($_POST['week_day']) . "min. 1-week day select<br/>";



                $recurrence = $num_diff . "-week"; #for days repeat
                $repeat_type = "week"; # days|week 
                $enddate = NULL; //$Date = "2018-03-17";

                $totdays = 7 * $num_diff;
                $enddate = date('Y-m-d', strtotime($stardate . ' +' . $totdays . ' days'));
                $endate = $enddate;


                //  echo  $stardate;  echo 'End date--'.$enddate; die;


               $dates_arr = date_range_week($stardate, $last = $enddate, $step = '+1 day', $output_format = 'Y-m-d');
                 $dates_em_arr= date_range_week($stardate, $last = $enddate, $step = '+1 day', $output_format = 'F d,Y');
               // print_r($dates_em_arr); die;
                $embody.=" <strong>Time-</strong>$at_time<br/> <strong>Below are the dates:-</strong><br/>";  
                $dates_str= implode('<br/>', $dates_em_arr);
                //print_r($dates_str); die;
             
              if(count($dates_em_arr)>0){
                  $embody.=$dates_str; $embody.=$embody_footer;}
                // Dates, on selective:: 
            }// Week
            //
         /////////Validate session //////////////
            $tot_techer = count($_POST['teacher_id']);
            $tot_dates = count($dates_arr);
            $newTotSes = $tot_dates * $tot_techer;
            

            if ($newTotSes > $data['avaiable_slots']) { //canCreae
               $can_insert = 0;  $avSes = $data['avaiable_slots'];
                $msg = "You can not create $newTotSes sessions since you have $avSes left in your account.";
                  
            }

            /////////Validate session //////////////   
           
            ////Multi-Repeat session/// $can_insert=0; # not
            if ($can_insert == 1) {
                #1.creation entry :


                for ($k = 0; $k < count($_POST['teacher_id']); $k++) {
                    // $repeat_type="days";# days|week
                    
            $int_th=mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id=".$_POST['teacher_id'][$k])); 
            $teacher_email=$int_th['email'];
                    # $week_str #repeat_days
                    $qqq = " INSERT INTO int_schools_x_slots_create SET session_start_time='$dt',"
                            . "session_end_time='" . $endate . "',started_date='$stardate',expired_date='$endate', "
                            . "ses_type='repeat',repeat_type='$repeat_type',recurrence='$recurrence', "
                            . "school_id='$s_id',repeat_days='$week_str', "
                            . "created_date='$today',teacher_id='" . $_POST['teacher_id'][$k] . "'";
                    $insert = mysql_query($qqq)or die(mysql_error());
                    $create_ids = mysql_insert_id(); // creation
                    /////

                    foreach ($dates_arr as $val) {
                        /////
                        $dt = $start = date('Y-m-d H:i:s', strtotime($val));
                        $ses_start_time = NULL;
                        $ses_end_time = NULL;
                        // $ses_start_time=$dt;
                        $sesStartTime = date('Y-m-d H:i', strtotime('+' . $hh . 'hour +' . $ii . ' minutes', strtotime($dt))); # add Hour, min.
                        ###
                        $sesEndTime = date('Y-m-d H:i', strtotime('+55 minutes', strtotime($sesStartTime)));
                        $ses_start_time = $sesStartTime;
                        $ses_end_time = $sesEndTime;
                        // selectvalidEnd time



                        $sql = " INSERT INTO int_schools_x_sessions_log SET create_id='$create_ids',ses_start_time='$ses_start_time',"
                                . " school_id='$s_id',start_date='$ses_start_time',end_date='$ses_end_time',ses_end_time='$ses_end_time', "
                                . "created_date='$today',teacher_id='" . $_POST['teacher_id'][$k] . "'";
                        $insert = mysql_query($sql)or die(mysql_error());
                        
                    }
                    /////////////Mail to Teacher /// 
                $rs_noti[]=sendEmails($user_id=1,$em_to=$teacher_email,$embody);
                  
                    
                }
               $rs_noti[]=sendEmails($user_id=1,$em_to='rohitd448@gmail.com',$embody); #TEST   
               
               /////////////Mail to Teacher /// 
             
             
                $crSes = ($tot_techer * $tot_dates);
                $currAvlSLot = ($data['avaiable_slots'] > 0) ? intval($data['avaiable_slots']) - $crSes : 0;

                $q = " UPDATE schools SET avaiable_slots='" . $currAvlSLot . "' WHERE SchoolId='" . $_SESSION['schools_id'] . "' ";
                $a = mysql_query($q); //slotUpdated  
                
            }// $can_insert
            ////Multi-Repeat session///       
        } // 1 creation -multi ses.
    }  // Validate TRUE///
    
   // print_r($rs_noti); die;
    
   
    //$error = 'Slot added ... ';  
}


///////////////////////
$email = $_SESSION['temp_email'];
$firstname = $_SESSION['temp_firstname'];
$lastname = $_SESSION['temp_lastname'];
$dist_mail_name = $_SESSION['temp_dist_name'];
$master_school_name = $_SESSION['temp_master_school'];
$school_mail_name = $_SESSION['temp_school_name'];
$phone_number = $_SESSION['temp_phone'];
$smart_preb_name = $_SESSION['temp_smart_preb'];
$data_dash_name = $_SESSION['temp_data_dash'];
$address = $_SESSION['temp_address'];
$city = $_SESSION['temp_city_name'];
$zipcode = $_SESSION['temp_zipcode'];
$billing_state = $_SESSION['temp_billing_state'];
if ($_SESSION['temp_email']) {
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

if (isset($_SESSION['temp_email']))
    unset($_SESSION['temp_email']);
if (isset($_SESSION['temp_firstname']))
    unset($_SESSION['temp_firstname']);
if (isset($_SESSION['temp_lastname']))
    unset($_SESSION['temp_lastname']);
if (isset($_SESSION['temp_dist_name']))
    unset($_SESSION['temp_dist_name']);
if (isset($_SESSION['temp_master_school']))
    unset($_SESSION['temp_master_school']);
if (isset($_SESSION['temp_school_name']))
    unset($_SESSION['temp_school_name']);
if (isset($_SESSION['temp_phone']))
    unset($_SESSION['temp_phone']);
if (isset($_SESSION['temp_smart_preb']))
    unset($_SESSION['temp_smart_preb']);
if (isset($_SESSION['temp_data_dash']))
    unset($_SESSION['temp_data_dash']);
if (isset($_SESSION['temp_address']))
    unset($_SESSION['temp_address']);
if (isset($_SESSION['temp_city_name']))
    unset($_SESSION['temp_city_name']);
if (isset($_SESSION['temp_zipcode']))
    unset($_SESSION['temp_zipcode']);
if (isset($_SESSION['temp_billing_state']))
    unset($_SESSION['temp_billing_state']);

if ($_GET['view'] == 'teacher' && $_GET['id'] > 0) {
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
                    . 'teacher_id = \'' . $userId . '\' , '
                    . 'permission = \'data dash\' , '
                    . 'grade_level_id = \'' . $item . '\' , '
                    . 'grade_level_name = \'' . $grade_res['name'] . '\' , '
                    . 'school_id = \'' . $_SESSION['schools_id'] . '\' ');
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

$students_result = mysql_fetch_assoc(mysql_query('SELECT COUNT(id) as count FROM students WHERE school_id = \'' . $_SESSION['schools_id'] . '\' '));
?>

<div class="container">
<?php include "school_header.php"; ?>
</div>





<!----Create Tutor sessions------>
<div class="container">
    <div id="content" class="col-md-12">
        <div id="single_question" class="content_wrap">
            <div class="ct_heading clear">
                <h3><i class="fa fa-plus-circle"></i>
<?php //echo $result?'Edit':'Add';?> Create Tutor sessions</h3>
            </div>		<!-- /.ct_heading -->
            <div class="ct_display clear">
                <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">

<?php if (isset($msg)) { ?>					
                        <p class="text-danger"><strong>
    <?= $msg ?>

                            </strong>

                        </p><br/> <?php } ?>







<?php if (isset($msg_error)) { ?>					
                        <p class="text-danger"><strong><?= $msg_error ?></strong>

                        </p><br/> <?php } ?>



                    <h4>+Create Tutor sessions </h4>
                    <div class="add_question_wrap clear fullwidth">
                        <div class="row">  
                            <script>

                                $(document).ready(function(){


                                $("#ccccses_start_date").change(function(){
                                alert('jiii');
                                //$("#ses_start_date").hide();
                                });
                                // $("#expired_date_div").show();
                                // else : if week then-- hide 
                                });
                                });</script>

                            <p class="col-md-3">
<?php
$today = date("m/d/Y");   // def
//$fddf=dat("d/m");
?>   

                                <label for="lesson_name">

                                    Start Date(Optional):</label>
                                <input value="<?= $today ?>" name="ses_start_date" class="datepicker" data-date-format="mm/dd/yyyy">
                            </p> 
                        </div>



                        <div class="row">

                            <p class="col-md-6">
                                <label for="distrator_id" ><strong class="text-danger">Start time :</strong>
                                </label><br/>

                                <select class="col-md-2" name="hour"style="
                                        width:200px"  id="time" class="required textbox">

<?php
$i = 1;
while ($i <= 12) {
    $sel = (isset($_POST['hour']) && $i == $_POST['hour']) ? "selected" : NULL;
    ?>

                                        <option  <?= $sel ?>
                                            value="<?= $i ?>" ><?= $i ?></option>                          

    <?php
    $i++;
}
?> 
                                </select>

<?php
//$af=array("05",);
// echo  $_POST['minnute'].'-MinXXXXX';
?>
                                <select  class="col-md-3" name="minnute"  class="required textbox">
<?php
$k = 0;
while ($k <= 55) {
    $sel = ($k == $_POST['minnute']) ? "selected" : NULL;

    $kff = ($k > 5) ? $k : '0' . $k;
    ?>                            
                                        <option  <?= $sel ?> value="<?= $k ?>"><?= $kff ?></option> 
                                        <?php $k = $k + 5;
                                    } ?>


                                </select> 
                                    <?php
                                    $tArr = array('am', 'pm');
                                    $Type
                                    ?>
                                <select  class="col-md-2" name="hr_type"  class="required textbox">
                                    <?php
                                    foreach ($tArr as $val) {
                                        $sel = ($val == $_POST['hr_type']) ? "selected" : NULL;
                                        ?>                         
                                        <option <?= $sel ?>   value="<?= $val ?>"><?= strtoupper($val) ?></option> 
                                    <?php } ?>

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
                            $("#recurrence_div").hide(); // def
                            $("#week_days_div").hide();
                            $("#hideddd").click(function(){

                            $("#recurrence_div").hide();
                            });
                            $("#shwrecurrence").click(function(){
                            $("#recurrence_div").show();
                            });
                            ////////Repeat time///////// 
                            $("#repeat_type").change(function(){
                            var vl = $('#repeat_type').val();
                            // alert('value--'+vl);
                            if (vl == "days"){ // exp date # week_days_div
                            $("#expired_date_div").show();
                            $("#week_days_div").hide();
                            } else if (vl == "week"){ // Show 7 days to choose
                            $("#expired_date_div").hide();
                            $("#week_days_div").show();
                            }
                            // $("#expired_date_div").show();
                            // else : if week then-- hide 
                            });
                            });</script>



                        <div class="row">
                            <p class="col-md-12">
                                <label for="ses_type">Session Type:</label><br/>             
                                <input name="ses_type"id="hideddd" value="ondate" checked="" type="radio">On Date<br>
                                <input name="ses_type" id="shwrecurrence" value="repeat" type="radio">Custom Repeat(Custom recurrence)
                            </p>  

                        </div>                   






                        <!--- Custom recurrence-->

                        <div class="row" id="recurrence_div" >
                            <strong>Custom recurrence</strong>  <br/> 
                            <p class="col-md-12">
                                <?php  
                              $num_diff=(isset($_POST['num_diff']))?$_POST['num_diff']:1; 
                                ?>

                                <input  class="col-md-2" name="num_diff"
                                        autocomplete="off"  aria-label="Days to repeat"
                                        value="<?=$num_diff?>" autofocus=""
                                        min="1"  data-initial-value="1" data-previous-value="1" type="number">


                                <select  class="col-md-2" name="repeat_type" id="" class="required textbox">

                                    <option selected="" value="week">Week</option> 





                                </select>    
                            </p>  

                            <p class="col-md-12" id="expired_date_div">


<?php
/// $day_arr
// print_r($_POST['week_day']);echo 'XXXX';
foreach ($day_arr as $key => $value) {
    // $vadf
    $sel = ((in_array($key, $_POST['week_day']))) ? "checked" : NULL;
    ?>	
                                    
                                    &nbsp;   &nbsp;<input name="week_day[]" value="<?= $key ?>" id="week_day[<?= $key ?>]" 
                                                          style="vertical-align: sub;" <?= $sel ?> type="checkbox"><label for="question_public"
                                           style="color:#4CAF50; font-weight: bold;"><?= $key ?></label>

<?php } ?>



                            </p>


                            <br/>

                        </div> 

                        <p id="week_days_div">
                            list of 		</p>

                        <!--- Custom recurrence-->            



                        <p>
                            <label for="distrator_id">Choose Teachers:</label>
                                <?php $teacher = mysql_query("
	SELECT `users`. * , GROUP_CONCAT( `shared`.`termId` SEPARATOR ',' ) AS shared_terms
	FROM `users`
	LEFT JOIN `shared` ON `users`.`id` = `shared`.`userId`
	WHERE `users`.`school` = {$_SESSION['schools_id']}
	GROUP BY `users`.`id` 
");
                                if (mysql_num_rows($teacher) > 0) { ?>









    <?php
    # 
    while ($data = mysql_fetch_assoc($teacher)) {
        $sel = ((in_array($data['id'], $_POST['teacher_id']))) ? "checked" : NULL;
        ?>
                                    <input name="teacher_id[]" <?= $sel ?> value="<?= $data['id'] ?>" id="teacher_id[]" 
                                           style="vertical-align: sub;" type="checkbox">
                                    <label for="question_public" style="color:#4CAF50; font-weight: bold;"><?= $data['first_name'] . " " . $data['last_name'] ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;</label>




                                <?php } ?>




                            <?php } ?>
                        </p>





<!----<p>
        <label for="lesson_desc">Description:</label>
        <textarea name="lesson_desc" id="lesson_desc" class="textbox" rows="5">
           </textarea>
</p>---->






                    </div>
                            <?php if (mysql_num_rows($teacher) > 0) { ?>
                        <p>

                            <input type="submit" name="ses_submit" id="ses_submit" class="form_button submit_button" value="Submit" />

                            <input type="reset" name="lesson_reset" id="lesson_reset" class="form_button reset_button" value="Reset" />
                        </p>
<?php } else echo "No Teachers,You can not Assigned slot to Teachers"; ?>

                </form>
                <div class="clearnone">&nbsp;</div>
            </div>		<!-- /.ct_display -->
        </div>
    </div>		<!-- /#content -->

</div> 









<!-- Modal -->
 <?php  include "invitations_modal.php";?>

<script type="text/javascript">
                                <?php if ($error != '') {
                                    echo "alert('{$error}');";
                                } ?>
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
    });</script>

<?php include("footer.php"); ?>

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>
