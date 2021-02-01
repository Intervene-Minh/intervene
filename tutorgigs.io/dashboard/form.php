<?php

include("header.php");
include('inc/connection.php'); 
session_start();
$user_id=$_SESSION['ses_teacher_id'];
$name=$_SESSION['login_user'];
error_reporting(1);
//echo $user_id;
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<?php 
 if(isset($_POST['signup_submit'])){
    $is_computer = $_POST['is_computer'];
    $hear = $_POST['hear'];
    $started_date =$_POST['started_date'];
    $things =array('is_computer'=>$is_computer,'hear'=>$hear,'started_date'=>$started_date);
   //$thing =json_encode($things);
    $thing1 = serialize($things);
   // print_r($thing1); die;
    $sql=" INSERT INTO `tutor_profiles` (`tutorid`, `profile_1`, `info`) VALUES ('$user_id', '$thing1', '3') ";
    $s=mysql_query($sql);

     // $query= mysql_query("INSERT INTO tutor_profiles ('profile_1','tutorid') VALUES('$thing1','$user_id')")or die(mysql_error());
//      $query= "INSERT INTO tutor_profiles ('profile_1','tutorid') VALUES('" . $thing1 . "','$user_id')";
//      echo $query;
 }


?>
<?php 
$select= mysql_query("SELECT * FROM gig_teachers WHERE id='$user_id'");
 //$select="SELECT * FROM gig_teachers WHERE id='$user_id'";
  //echo $select;exit;
 $profile_res = mysql_fetch_assoc($select);

 ?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
       
            <div class="align-center col-md-12">
                <form id="form-signup" class="form-signup" action="form.php" method="post">
               <!----<form id="form-signup" class="form-signup" action="signup.php" method="post">--->     
                    <div class="title text-center">Sign Up For Teacher Access</div>
                    <div class="box">
                    <div class="signup-item">
                            <label for="signup-email">Please confirm your email address *</label>
                            <input type="email" required id="signup-email" class="required" name="email" value="<?php echo $profile_res['email'];?>"/>
                            <div class="notif">*Please enter your email</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-firstname">First name</label>
                            <input type="text"  required id="signup-firstname" value="<?php echo $profile_res['f_name'];?>" class="required" name="f_name"/>
                            <div class="notif">*Please enter your first name</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-lastname">Last name</label>
                            <input type="text" required id="signup-lastname" class="required" value="<?php echo $profile_res["lname"];?>" name="lname"/>
                            <div class="notif">*Please enter your last name</div>
                        </div>
                        
                         
                        <div class="signup-item">
                            <label for="signup-password">What is your phone number? *</label>
                            <input type="text" required id="signup-password" class="required" value="<?php echo $profile_res["phone"];?>" name="phone"/>
                            <div class="notif">*Please enter your password</div>
                        </div>
                        <div class="signup-item">
                            <label for="signup-repassword">Do you have a computer or tablet and reliable internet access?</label>
                            <input type="radio" name="is_computer" value="yes">Yes<br>
                            <input type="radio" name="is_computer" value="no">No
                            <!-- <div class="notif">*Please match your password</div> -->
                        </div>

                          <div class="signup-item">
                            <label for="">How did you hear about us? *</label>
                            <input type="text" required id="phone" class="required" name="hear" />
                            <!-- <div class="notif">*Please enter your Telephone Number(Home,Cell)</div> -->
                        </div>
                        
                         <div class="signup-item">
                            <label for="">When would you like to get started Tutoring? *</label>
                            <input type="date" id="birth_date" class="" name="started_date"/>
                            <!-- <div class="notif">Birthday </div> -->
                        </div>


                        <button id="signup-submit" class="signup-submit" name="signup_submit" type="submit" style="margin-top: 0;">Sign Up</button>

                    </div>
                </form>
            </div>
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
<?php include("footer.php"); ?>
<?php ob_flush(); ?>