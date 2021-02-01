<?php
/*
@Email from:learn@intervene.io
https://intervene.atlassian.net/browse/IIDS-155
@ Teacher iser regsier by - admin only 
**/
include("header.php");

 $set_msg=1;

 

///////////////////////////

 
function sendEmailToPrincipal($email, $to, $schoolName, $firstname, $lastname) {
    require 'inc/PHPMailer-master/PHPMailerAutoload.php';

    $message = "Dear {$schoolName},
	<br /><br />
	{$firstname} {$lastname} has requested access to {$schoolName} Intervene Account. Please login and share access.<br />
	Once you have shared access, your teacher will get an email with confirmation. 
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br/>
	www.intervene.io
	<br /><br />
	<img alt='Less Test Prep' src='https://intervene.io/questions/images/logo.png'>";
   

    // Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set who the message is to be sent from
    $mail->setFrom('learn@intervene.io', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('learn@intervene.io', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
  //  $mail->AddCC("rohit@srinfosystem.com", '');
    // Set the subject line
    $mail->Subject = 'A teacher has requested access to Intervene';
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

function sendNoticeToPrincipal($to, $schoolName, $firstname, $lastname) {
    require_once 'inc/PHPMailer-master/PHPMailerAutoload.php';

    $message = "Dear {$schoolName},
	<br /><br />
	{$firstname} {$lastname} has completed sign up for your school's Intervene.io account.
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br />
	www.intervene.io
	<br /><br />
	<img alt='Less Test Prep' src='https://intervene.io/questions/images/logo.png'>";
    



    // Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set who the message is to be sent from
    $mail->setFrom('learn@intervene.io', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('learn@intervene.io', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
    $mail->AddCC("rohit@srinfosystem.com", '');
    // Set the subject line
    $mail->Subject = 'A teacher just signed up!';
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
////////////////////////////////////////

include('inc/connection.php');

$error = '';

//Require the class
require('libraries/formkey.class.php');
//Start the class
$formKey = new formKey();
 

$warning_msg=array();
##############################
if (isset($_POST['signup_submit'])){



    ///////////////////////////////////

    $firstname = $_POST['signup-firstname'];
    $lastname = $_POST['signup-lastname'];
    $username = $_POST['signup-username'];
    $school= $_POST['signup-school'];
    $email = $_POST['signup-email'];
    $password = $_POST['signup-password'];
    $md5password = md5($password);
    //////////////If invited Email/////////////////
     $sql_invited=mysql_query("SELECT * FROM `invitation` WHERE `teacherEmail` = '{$email}'
                                     ORDER BY `date_invited` DESC
                                     LIMIT 0 , 1"); 
       if (mysql_num_rows($sql_invited) > 0){

          
                    // Teacher was invited - make share registered grades now
                    $ref_data= mysql_fetch_assoc($sql_invited);
                    //School id Changed
                   $school=$ref_data['schoolId'];
       }
    

      //  $warning_msg[] = "No email";

       //   $warning_msg[] = "Email or username already exists";

    ////////////////////////////////  if ($rows < 1) {
      $query = mysql_query("SELECT * FROM users WHERE email = '$email' OR user_name = '$username'", $link);
        $rows = mysql_num_rows($query);

        if (empty($_POST['signup-email'])){
             $warning_msg[] = "Enter Email id";  }


         if ($rows>0){
            $warning_msg[] = "Email or username already exists";

        }
        // Valid request
        // if($formKey->validate()){
        //      $warning_msg[] = "Valid Request!";  }

          
#################################################

    if(!isset($_POST['form_key']) || !$formKey->validate()){
        // 

        //Form key is invalid, show an error
        $warning_msg[] = 'Form key error!';

    }elseif(!empty($_POST['signup-email'])&&$rows <1){   //  New user &&'No form key error!';
     
            //get default question remaining in table packages (Free Package)
            $q_remaining = 0;
            //need check on livesite 2016-08-23

            /// Insert district id and School
            // district_id master_school_id
            $dist_id= $_POST['district']; 
            $school_id=$school; 
            $query= "INSERT INTO `users` ( `user_name` , `email` , `password` , `first_name` , `last_name` , `school` , `q_remaining`,`district_id`,`master_school_id` ) 
			VALUES ('$username', '$email', '$md5password', '$firstname', '$lastname', '$school', $q_remaining,'$dist_id','$school_id')";
            $data = mysql_query($query);
            $userId = mysql_insert_id();

            if ($data){
                // Get school data
                $sq = mysql_query("SELECT `SchoolId`, `SchoolName`, `SchoolMail` FROM `schools` WHERE `SchoolId` = " . $school);
                $dataSchool = mysql_fetch_assoc($sq);

                // Check if teacher was invited by principal mysql_query
                $invitedxx = ("SELECT *
				FROM `invitation`
				WHERE `schoolId` = {$school}
				AND `teacherEmail` = '{$email}'
				ORDER BY `invitation`.`date_invited` DESC
				LIMIT 0 , 1");
                                
                                 $invited=mysql_query("SELECT * FROM `invitation` WHERE `teacherEmail` = '{$email}'
                                     ORDER BY `date_invited` DESC
                                     LIMIT 0 , 1"); 
                                 ////////////Ref by shool_id;////////////
                            ## invitedCode
                                 if(mysql_num_rows($invited) > 0){
                    // Teacher was invited - make share registered grades now
                    $invitation = mysql_fetch_assoc($invited);
                     // Teacher was invited - make share registered for- Tutor sessions
                      if($invitation['session_allowed']=='yes'){  //allow tut. session to teacher
                       $q="INSERT into user_session_access SET user_id='$userId',role='teacher',school_id=".$school; 
                       $query=mysql_query($q);
                      }
                   
                    
                    
                    /////end : tut. Session permison
                    $data_dash = unserialize($invitation['data_dash']);
                    $sharedGrades = unserialize($invitation['shareItems']);
                    
                    
                   //////////////////////////////////
                    
                    foreach ($sharedGrades as $grade){
                        mysql_query("INSERT INTO `shared` (`schoolId`, `userId`, `termId`) VALUES ({$school}, {$userId}, {$grade})");
                    }
                    
                    foreach ($data_dash as $dash){
                        $grade_level_name = mysql_fetch_assoc(mysql_query("SELECT name FROM terms WHERE id = {$dash} "));
                        $grade_name = $grade_level_name['name'];
                        
                        mysql_query("INSERT INTO `techer_permissions` (`school_id`, `teacher_id`, `grade_level_id`, `permission`, `grade_level_name`) VALUES ({$school}, {$userId}, {$dash}, 'data dash', '{$grade_name}')");
                    }

                    // Notice principal that teacher was completed signup
                   // sendNoticeToPrincipal($dataSchool['SchoolMail'], $dataSchool['SchoolName'], $firstname, $lastname);
                     
                    // Send activate email
                    require_once('inc/function-active-user.php');
                    sendEmailToActive($userId);
                    
                }else{  // Send email to principal
                   
                   // sendEmailToPrincipal($email, $dataSchool['SchoolMail'], $dataSchool['SchoolName'], $firstname, $lastname);
                    require_once('inc/function-active-user.php');
                      sendEmailToActive($userId);
                } ## if (mysql_num_rows($invited) > 0) {


            

                ////////////////////////////////////////////////////////////
                $_SESSION['temp_email']=$email;
                $_SESSION['temp_firstname']=$firstname;
                $_SESSION['temp_lastname']=$lastname;
                $_SESSION['temp_school_name']=$dataSchool['SchoolName'];
                /////////////////////////
                   $usersList='  <a href="manager-user.php" class="links link-text">Users List</a> ';
                   $_SESSION['msg_success']='Registration successful.!, '.$usersList;

                $success_msg='yes';


               // header("location:signup.php?registered=true");



            }else{
                $warning_msg[]= "CAN'T CONNECT TO DATABASE";
            }


       


       }   //  if (!empty($_POST['signup-email'])){



/////Display Message/////////

    if(!empty($warning_msg)){
    $error=implode('<br/>',$warning_msg);
        //echo $error;
    }


}




/* Get list registered school */
$schools = mysql_query("SELECT `SchoolId`, `SchoolName` FROM `schools` WHERE `status` = 1");



// Registration successful.Email sent to teacher email id 


?>

<?php if(isset($success_msg)||isset($_GET['registered'])){ ?>
<script type="text/javascript">
    alert('Registration successful.!');
    //location.href='https://intervene.io/questions/signup.php';
</script>
<?php } ?>

<style type="text/css">
    a.link-text{
        text-decoration: underline;
    color: #1b64a9;
    font-size: 15px;

    }
</style>


<script src='https://www.google.com/recaptcha/api.js'></script>
<!--html>
  <head>
    <title>reCAPTCHA demo: Simple page</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>
    <form action="?" method="POST">
      <div class="g-recaptcha" data-sitekey="6Ld4L7sUAAAAANDpqvHSLSUV2A8REgbPfUGFUemg"></div>
      <br/>
      <input type="submit" value="Submit">
    </form>
  </body>
</html-->
<script>
    $(document).ready(function () {
        $('#district').chosen();
        
        $('#district').change(function () {
            district = $(this).val();
             $('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_signup_schools', school_id : '<?php print $master_schoolid; ?>'},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();// signup-submit

        $('#signup-submit').on('click', function () { // signup-submit_xxx
            $('.required').each(function () {
                if (!$(this).val()) {
                    $(this).parent().addClass('warning');
                }
            });


            // if (!$('#signup-tac').is(":checked")) {
            //     $('#signup-tac').parent().addClass('warning');
            // }  //;

            var $email = $('#signup-email').val();
            if (!isEmail($email)) {
                $('#signup-email').parent().addClass('warning');
            }

            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                /*reCaptcha not verified*/
                $('.g-recaptcha').parent().addClass('warning');
            } else {
                /*reCaptch verified*/
                $('.g-recaptcha').parent().removeClass('warning');
            }
            var $check = $('.warning').length;
            if ($check != 0)
                return false;
        });



        $('#form-signup .required').blur(function () {
            if ($(this).val().length === 0) {
                $(this).parent().addClass('warning');
            }
        });
        $('#form-signup .required').focus(function () {
            $(this).parent().removeClass('warning');
        });
        $("#signup-repassword").keyup(function () {
            checkPasswordMatch();
        });
        $("#signup-password").keyup(function () {
            checkPasswordMatch();
        });

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
        function checkPasswordMatch() {
            var $password = $("#signup-password").val();
            var $rePassword = $("#signup-repassword").val();

            if ($password != $rePassword)
                $("#signup-repassword").parent().addClass('warning');
            else
                $("#signup-repassword").parent().removeClass('warning');
        }
    });
</script>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<?php 
$district_qry = mysql_query('SELECT loc.* from loc_district loc INNER JOIN schools s ON s.district_id = loc.id GROUP BY loc.id ORDER BY district_name ASC ');

?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <?php   include "msg_inc_1.php";  ?>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-md-12 col-md-12">
                <p class="h2">Teachers, Sign Up for Access</p>
                <h3>
                    <ul>
                        <li>We have a printable database of STAAR Math questions.</li>
                        <li>You can just choose the questions you love and push print!</li>
                        <li>There's also an innovative answer key that will help you find misconceptions.</li>
                        <li>The PDF printout will help you create small groups with that information.</li>
                        <li>Need help? Contact us at-learn@intervene.io</li>
                </h3>


            </div>
            <div class="align-center col-lg-6 col-md-6 col-md-12 col-md-12">
               <!-- <p> Show error.</p> -->
               <?php    if(!empty($warning_msg)){  ?> 
                <p> <?php  echo implode('<br/>', $warning_msg);  ?>  </p>
               <?php  }?>
                <form id="form-signup" class="form-signup" action="signup.php" method="post">

                  <?php $formKey->outputKey(); ?>

                    <div class="title">Sign Up For Teacher Access</div>
                    <div class="box">
                        <div class="signup-item">
                            <label for="signup-firstname">First name</label>
                            <input type="text" id="signup-firstname" class="required" value="<?=$_POST['signup-firstname']?>" 
                             name="signup-firstname"/>
                            <div class="notif">*Please enter your first name</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-lastname">Last name</label>
                            <input type="text" id="signup-lastname" class="required"  value="<?=$_POST['signup-lastname']?>" 
                            name="signup-lastname"/>
                            <div class="notif">*Please enter your last name</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-username">User name</label>
                            <input type="text" id="signup-username" class="required"  value="<?=$_POST['signup-username']?>"
                             name="signup-username"/>
                            <div class="notif">*Please enter your user name</div>
                        </div>
                         <div class="signup-item">
                        <p>
                                <label for="lesson_name">Choose District: </label><br />
                                <select name="district" id="district">
                                <?php while ($district = mysql_fetch_assoc($district_qry)) { 
                                    $sel='';
                                   // if ($row['district_id'] == $district['id']) { 
                                    if(isset($_POST['district'])&&$district['id']==$_POST['district'])
                                        $sel='selected';


                                  ?>

                                                                        <option <?=$sel?>  value="<?php print $district['id']; ?>"><?php print $district['district_name']; ?></option>

                                <?php } ?>
                                </select>
                                
                            </p>
                         </div>
                         <div class="signup-item">
                            <div id="district_schools">
                                
                                <label for="lesson_name">Choose Schools:</label>
                                <div id="district_school">
                                    Select District to choose schools.
                                </div>
                               
                            </div>
                         </div>

                       <!--   at sumbit -->


                      <!--  <div class="signup-item">
                            <label for="signup-school">SchoolX</label>
                            <select id="signup-school" class="form-control required" name="signup-school">
                                <option value=""></option>
                                <?php
                                if (mysql_num_rows($schools) > 0)
                                    while ($shool = mysql_fetch_array($schools))
                                        echo "<option value='{$shool['SchoolId']}'>{$shool['SchoolName']}</option>";
                                ?>
                            </select>
                            <div class="notif">*Please select your school</div>
                        </div>  -->


                        <div class="signup-item">
                            <label for="signup-email">Email</label>
                            <input type="email" id="signup-email" 
                            value="<?=(isset($_POST['signup-username']))?$_POST['signup-email']:NULL?>" class="required" 
                            name="signup-email"/>
                            <div class="notif">*Please enter your email</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-password">Password</label>
                            <input type="password" id="signup-password" class="required" value="<?=$_POST['signup-password']?>" 
                             name="signup-password"/>
                            <div class="notif">*Please enter your password</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-repassword">Re-Password</label>
                            <input type="password" id="signup-repassword" class="required" name="signup-repassword"/>
                            <div class="notif">*Please match your password</div>
                        </div>
                        <div class="signup-item">
                            <input type="checkbox" id="signup-tac" name="signup-tac" required />
                            <label for="signup-tac">Terms and Conditions</label>
                            <div class="notif">*Please agree to Terms and Conditions</div>
                        </div>

                        <div class="signup-item">
                            <div class="g-recaptcha" data-sitekey="6Lfs1yQUAAAAADxKZbtLfZDB-ZmGuBsfSPfbvqBw"></div>
                            <div class="notif">*Please verify captcha</div>
                        </div>

                        <button id="signup-submit" class="signup-submit" name="signup_submit" type="submit" style="margin-top: 0;">Sign Up</button>
                    </div>
                </form>
            </div>
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

<style>
    #district_chosen{width:100% !important;}
    .form-signup .box {
    float: left;
    width: 100%;
}
.chosen-container .chosen-results {
    position: relative;
    overflow-x: hidden;
    overflow: auto;
    margin: 0 4px 4px 0;
    padding: 0 0 0 4px;
    max-height: 240px;
    -webkit-overflow-scrolling: touch;
    width: 100%;
}
#d_school_chosen{width:100%!important;}
    </style>

<?php include("footer.php"); ?>
<?php ob_flush(); ?>
