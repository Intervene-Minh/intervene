<?php
// List of slot 
/***
 * @ create-tutor-sessions ::Multiple teacher,

 * @ sessions-calendar.php
 * @ 
 * **/
include('inc/connection.php'); 
session_start();
  ob_start();
include("header_custom.php");



//echo date('t').'-days';

// if (!isset($_SESSION['schools_id'])) {
//     header("Location: login_principal.php");
//     exit();
// }

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
    
 <?php  // include "school_header.php";# ?>
   
    
 
    <form name="" id="" method="GET" action="calendar.php">
    <div class="ui container">
        <div class="ui grid">
          <div class="ui sixteen column">
            <div id="calendar"></div>
          </div>
        </div>
    </div>
      
      </div>
         <?php 
         
       //  $month = "12"; $year = "2018";
          $month =date('m'); $year =date('Y');
         
          $fdate=date('Y-m-1');
          @extract($_GET); @extract($_POST);
            // if(isset($nextmonth)){
            // $fdate = $nextmonth;
            // $year= date('Y', strtotime($fdate));
            //  $month = date('m', strtotime($fdate));
             
            // }elseif(isset($premonth)){
            // $fdate= $premonth;
            // $year= date('Y', strtotime($fdate));
            //  $month = date('m', strtotime($fdate));
            
            // }    
        /////////////////////////////////////
$start_date = "01-".$month."-".$year;
$start_time = strtotime($start_date);

$end_time = strtotime("+1 month", $start_time);   
         //  get session by Day in advance
         $school_id=$_SESSION['schools_id'];#
         $date_ses=array();
    //  print_r($school_id);exit;
             
 for($i=$start_time; $i<$end_time; $i+=86400){
   $list2[] = date('Y-m-d-D', $i); 
   $getdate=  date('Y-m-d', $i);
   $dayval=  date('d', $i);$dayval= intval($dayval);
   
//echo  date('Y-m-').$i.'<br/>';#2
 $end_date=$getdate." 23:59:59.999";
  $qq=" SELECT * FROM `int_schools_x_sessions_log` WHERE 1 
  AND ses_start_time between '$getdate' AND '$end_date' AND school_id='$school_id' ";  
 echo '<pre>';print_r($qq); die; //exit;
                $tot_ses=0;//$dff=mysql_query($qq);d
                $results=mysql_query($qq);
                $tot_ses= mysql_num_rows($results);
                $slot_str='';$k=1;
                 while($row = mysql_fetch_assoc($results) ) {
                     
                     # STU_ASSIGNED  ASSIGNED
                     $sesid=$row['id'];
                     $ses_end_time=$row['ses_end_time'];
                     $ses_start_time=$row['ses_start_time'];
                      $old_end= date_format(date_create($row['ses_start_time']), 'h:i a');
         $str_class=($row['tut_status']=="STU_ASSIGNED")?"btn btn-success btn-xs":"btn btn-danger btn-xs";
        $st_title=($row['tut_status']=="STU_ASSIGNED")?"Students Assigned":"Students Not assigned";
         
        $slot_str.='<a   style=" margin:1% 0;" title="'.$st_title.'" href="session-detail.php?id='.$sesid.'"  class="'.$str_class.'">'.$old_end.'</a><br/>';#1
                   if($k>2){
          $slot_str.='<a href="view-sessions.php?date='.$getdate.'"  class="text-success">View More('.$tot_ses.')</a>';#Last
                       break; }
                    $eventData[] = array('title'=>$st_title,'id'=>$sesid,'start'=>$ses_start_time,'end'=>$ses_end_time);
                 
                    $k++;
                 } ///
                 //  view more if > ses>5 indate
                 $date_ses[$dayval]=$slot_str;  //  per sessions
               //  $date_ses('id'=>$sesid,start_date=>'$ses_start_time',)
                }
  
             echo '<pre>'; print_r($eventData); // die;
         ?>
        
        <?php 
      

        
        /////////////Search calendar/////////////
            $currMonth=date('Y-m-1');
        //      $nxtMonth = date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
        //    $preMonth = date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
            // 1. def ::,mar
             /////////////////
            //  if(isset($nextmonth)&&!empty($nextmonth)){
            //    $currMonth=$nextmonth; #apr
            //      $preMonth=$nextmonth; #apr
            //       $preMonth= date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
            //     $nxtMonth = date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
            // }elseif(isset($premonth)){
                   
              // $nxtMonth=$premonth; // feb
            //     $currMonth=$premonth; #feb
            //    $preMonth= date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
            //    $nxtMonth= date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
            // }
         ////////////Search calendar///////////
              $year3= date('Y', strtotime($fdate)); // echo 'y/M';
             $month22 = date('M', strtotime($fdate));
            ?>
        
               <!-- view- Calendara-->
               <!-- <h3 class="text-danger text-right">
                   <button type="submit" class="btn btn-success btn-xs" name="premonth"
                            value="<?=$preMonth?>">-Prev</button>
                    <?=date('F, Y', strtotime($fdate));?> 
                   <button type="submit" class="btn btn-success btn-xs"  name="nextmonth"
                            value="<?=$nxtMonth?>">+Next</button>          
               </h3>
            <br/>   <br/>    -->
        
        <?php 

//  function year2array($year) {
//     $res = $year >= 1970;
//     if ($res) {
//       // this line gets and sets same timezone, don't ask why :)
//       date_default_timezone_set(date_default_timezone_get());

//       $dt = strtotime("-1 day", strtotime("$year-01-01 00:00:00"));
//       $res = array();
//       $week = array_fill(1, 7, false);
//       $last_month = 1;
//       $w = 1;
//       do {
//         $dt = strtotime('+1 day', $dt);
//         $dta = getdate($dt);
//         $wday = $dta['wday'] == 0 ? 7 : $dta['wday'];
//         if (($dta['mon'] != $last_month) || ($wday == 1)) {
//           if ($week[1] || $week[7]) $res[$last_month][] = $week;
//           $week = array_fill(1, 7, false);
//           $last_month = $dta['mon'];
//           }
//         $week[$wday] = $dta['mday'];
//         }
//       while ($dta['year'] == $year);
//       }
//     return $res;
//     }
    
//    // print_r(year2array(2018));
    
    
//     function month2table($month, $calendar_array) {
//         global $date_ses;
//     $ca = 'align="center"';
//     $res = "<table class=\"table table-hover\" cellpadding=\"2\" cellspacing=\"1\" style=\"border:solid 1px #000000;font-family:tahoma;font-size:12px;background-color:#ababab\"><tr><td $ca>Mo</td><td $ca>Tu</td><td $ca>We</td><td $ca>Th</td><td $ca>Fr</td><td $ca>Sa</td><td $ca>Su</td></tr>";
//     foreach ($calendar_array[$month] as $month=>$week) {
//       $res .= '<tr>';
//       foreach ($week as $day) {
                  
//            # view more session
//   // Call function for edit..
//            $sesinfo=($day>0)?$date_ses[$day]:NULL;
//           // Make sess info,entry for each day ..
//           // at the- end +add,
//          ////end check ::
//         $res .= '<td style="text-align: center;border-right: 1px solid;" width="20" bgcolor="#ffffff">' . ($day ? $day : '&nbsp;') . '<br/>'.$sesinfo.'</td>';
//         }
//       $res .= '</tr>';
//       }
//     $res .= '</table>';
//     return $res;
//     }

//    $calarr = year2array($year);$month= intval($month);
//   echo month2table($month, $calarr); // March, 2018 ::DEF
?>       
    </form>
</div>

<!-- Modal -->
 <?php include "invitations_modal.php"; ?>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js"></script>
      <script>
              	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
		//	defaultDate: '2016-12-12',
			navLinks: true, // can click day/week names to navigate views
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: <?php  echo json_encode($eventData); ?>
		});
		
	});     
       </script>

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