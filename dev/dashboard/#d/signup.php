<?php
include("header.php");

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
    $mail->setFrom('pathways2greatness@gmail.com', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
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
    $mail->setFrom('pathways2greatness@gmail.com', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
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

include('inc/connection.php');

$error = '';
 $today = date("Y-m-d H:i:s");   
// Teacher Register
   // 
 $success_url="dashboard/login.php";
 
 
if (isset($_POST['signup_submit'])) {
    
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    //$username = $_POST['signup-username'];
   
    $email = $_POST['email'];
    $password = $_POST['password'];
    $md5password = md5($password);

    if (!empty($_POST['email'])) {
       // $query = mysql_query("SELECT * FROM users WHERE email = '$email' OR user_name = '$username'", $link);
       
     $query = mysql_query("SELECT * FROM gig_teachers WHERE email = '$email' ", $link);   
        $rows = mysql_num_rows($query);
        
        if ($rows < 1) { // New Register
           
           
            //  home_address phone birth_date  	url_aww_app  created_date
           $queryxxx = "INSERT INTO `gig_teachers` ( `f_name` , `lname` , `email` , `password` ) 
			VALUES ('$firstname', '$lastname', '$email', '$md5password')";
            
            $query=" INSERT INTO gig_teachers SET f_name='$firstname',"
                . "lname='$lastname', "
                . " email='$email',"
                . "password='$md5password', ";
             if(!empty($_POST['home_address']))
             $query.=" home_address='".$_POST['home_address']."', ";
             if(!empty($_POST['phone']))
             $query.=" phone='".$_POST['phone']."', ";
             
             if(!empty($_POST['birth_date']))
             $query.=" birth_date='".$_POST['birth_date']."', ";
             if(!empty($_POST['url_aww_app']))
             $query.=" url_aww_app='".$_POST['url_aww_app']."', ";
             
            
           $query.=" created_date='$today' ";
            
            $data = mysql_query($query)or die(mysql_error());
            
          
            
            $userId = mysql_insert_id();

            if ($data) {
                $_SESSION['temp_email']=$email;
                $error = "Success,Registration";# NoRegis  
                header("location: login.php?registered=true");exit;
               
                
            } else {
                $error = "Sorry, Can not Register try later";# NoRegis
            }
            
            
            
            
        } else {
            $error = "Email or username already exists";
        }
        
        
    } else {
        $error = "Email Required";
    }
}



/* Get list registered school */
$schools = mysql_query("SELECT `SchoolId`, `SchoolName` FROM `schools` WHERE `status` = 1");

// echo $error;
if ($error != "") {
    echo '<script>alert("' . $error . '");</script>';
}
?>
<!--<script src='https://www.google.com/recaptcha/api.js'></script>--->
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
        $('#district').change();
        $('#signup-submit').on('click', function () {
            $('.required').each(function () {
                if (!$(this).val()) {
                    $(this).parent().addClass('warning');
                }
            });

            if (!$('#signup-tac').is(":checked")) {
                $('#signup-tac').parent().addClass('warning');
            }
            ;

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
        <div class="row">
            
            <div class="align-center col-md-12">
                <form id="form-signup" class="form-signup" action="signup.php" method="post">
               <!----<form id="form-signup" class="form-signup" action="signup.php" method="post">--->     
                    <div class="title text-center">Sign Up For Teacher Access</div>
                    <div class="box">
                        <div class="signup-item">
                            <label for="signup-firstname">First name</label>
                            <input type="text" value="<?=(isset($firstname))?$firstname:NULL;?>" required id="signup-firstname" class="required" name="firstname"/>
                            <div class="notif">*Please enter your first name</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-lastname">Last name</label>
                            <input type="text" required id="signup-lastname" class="required" name="lastname"/>
                            <div class="notif">*Please enter your last name</div>
                        </div>
                        
                         <div class="signup-item">
                            <label for="signup-email">Email</label>
                            <input type="email" required id="signup-email" class="required" name="email"/>
                            <div class="notif">*Please enter your email</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-password">Password</label>
                            <input type="password" required id="signup-password" class="required" name="password"/>
                            <div class="notif">*Please enter your password</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-repassword">Re-Password</label>
                            <input type="password" required id="signup-repassword" class="required" name="signup-repassword"/>
                            <div class="notif">*Please match your password</div>
                        </div>
                        
                       
                        <div class="signup-item">
                            <label for="">Home Address</label>
                        
                            <textarea  style="border: 1px solid #dddddd;
float: left;
width: 100%;

line-height: 40px;
padding: 0 10px;" required id="home_address" class="required" name="home_address"></textarea>
                            
                            <div class="notif">*Please enter your Address</div>
                        </div>
                        
                          <div class="signup-item">
                            <label for="">Telephone Number</label>
                            <input type="text" required id="phone" class="required" name="phone"/>
                            <div class="notif">*Please enter your Telephone Number(Home,Cell)</div>
                        </div>
                        
                         <div class="signup-item">
                            <label for="">Birthday</label>
                            <input type="date" id="birth_date" class="" name="birth_date"/>
                            <div class="notif">Birthday </div>
                        </div>
                        
                         <div class="signup-item">
                            <label for="">URL for Aww App</label>
                            <input type="text" required id="url_aww_app" class="required" name="url_aww_app"/>
                            <div class="notif">*URL for Aww App</div>
                        </div>
                        
                        
                        
                        
                         <!---
                         <div class="signup-item">
                        <p>
                                <label for="lesson_name">Choose Options:</label><br />
                                <select name="district" id="district">
                               
                           <option  value="33">option1</option>  
                            <option  value="33">option1</option> 
                             <option  value="33">option1</option>  
                                  
                                </select>
                                
                            </p>
                         </div>
                        
                         --->
                       
      


                       
                        
                        
                        
                        <div class="signup-item">
                            <input type="checkbox" id="signup-tac" name="signup-tac"/>
                            <label for="signup-tac">Terms and Conditions</label>
                            <div class="notif">*Please agree to Terms and Conditions</div>
                        </div>

                     <!--   <div class="signup-item">
                            <div class="g-recaptcha" data-sitekey="6Lfs1yQUAAAAADxKZbtLfZDB-ZmGuBsfSPfbvqBw"></div>
                            <div class="notif">*Please verify captcha</div>
                        </div>-->



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
    </style>
<?php include("footer.php"); ?>
<?php ob_flush(); ?>
