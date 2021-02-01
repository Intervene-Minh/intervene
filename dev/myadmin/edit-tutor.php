<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * @ Edit tutor Detail..
 * @ID-235, suspend tutor list log for 
 cancelled session by admin side. 
 * @Test:tutor_id==125
 * ***/
//echo '======';

include("header.php");
$cur_time= date("Y-m-d H:i:s");

// sql_tutor_upcoming_tutorings
$sql_tutor_upcoming_tutorings="";


  

/*
 @cdb_tutor_upcoming_tutorings
 @List upcoming TutorTutoringList
*/
  function cdb_tutor_upcoming_tutorings($sql='')
{
 $data=[];
 $tutor=$_GET['tid'];
 global $cur_time;
 $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";

$sql.=" AND ses_start_time>'".$cur_time."'";
$sql.=" AND tut_teacher_id='$tutor' ";
//echo 'TT:' , $sql; die; 
// Tutoring_client_id 
$sql.=" AND Tutoring_client_id='Intervene123456' ";

$sql.=" ORDER BY ses_start_time ASC ";
$results=mysql_query($sql);
while ($row=mysql_fetch_assoc($results))
$data[]=$row['id'];

 //display : array of tutoring ids
 if(count($data)>0)
  return $data;
   

  }

 



//cdb_add_suspend_cancelled_log///
function cdb_add_suspend_cancelled_log($arr_tutoring=[])
{  // Add log to :: int_session_suspend_cancelled_log

  global $cur_time;
  $tutor_id=$_GET['tid'];
  $random_batch=$tutor_id.'_'.time(); // 
  //echo 'List:arr_tutoring';
  //print_r($arr_tutoring); die; 

  foreach ($arr_tutoring as $tutoring_id) 
  {
  $admin_id=1;$comment='cancelled by admin! ';
  $created_at=$cur_time;


$query=" INSERT INTO int_session_suspend_cancelled_log 
(tutoring_id,tutor_id,admin_id,comment,random_batch ,created_at) VALUES 
('$tutoring_id', '$tutor_id', '$admin_id','$comment','$random_batch','$created_at' ) ";

   $addLog=mysql_query($query);

   // Update tutoring. tutor_id==0 mysql_query
   $UpdateTutoring= mysql_query("UPDATE int_schools_x_sessions_log SET tut_teacher_id='0', 
   modified_date='$cur_time' WHERE id='$tutoring_id'");

   //echo 'query===:',$query; die; 



  }
  //
  
  
  ///

}



/*
@ quiz_result
*/
function quiz_result($p2db,$getid,$test_id){
    //$test_id=$tdata['quiz_1_id']; //Default 

$attempted=("SELECT * FROM `tutor_result_logs` WHERE `tutor_id` =".$getid." AND `test_id` =".$test_id);
 $get_result=mysqli_query($p2db,$attempted);
  $quiz_1=array();
   $quiz_2=array(); 
  $total_attempted=mysqli_num_rows($get_result);
     $correct=0;
        while ($row = mysqli_fetch_assoc($get_result)) {
     //echo $row['attempt_id']; echo '<br/>';
          // attempt_id  answer_id
          if($row['answer_id']==$row['attempt_id']){
            $correct=$correct+1;
          }      
    }
 
  $get_scored=($correct*100)/$total_attempted;
  return  $get_scored=round($get_scored,2);



 } 



 ///////////////////


$error = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //if not admin but want to edit return index
    require_once('inc/check-role.php');
    $login_role = checkRole();
    if ($login_role != 0 || !isGlobalAdmin()) {
        header('Location: index.php');
        exit;
    }
} else {
    $id = $_SESSION['login_id'];
}

/// 'profile-submit'
/*
 * firstname
lname
email
phone
 * 
 * **/
@extract($_POST);
 $today = date("Y-m-d H:i:s"); 
 $valid_url=true;
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
 $getid=0;

if(isset($_GET['tid'])){
    $getid=$_GET['tid'];
    $query3 = mysql_query(" SELECT * FROM gig_teachers WHERE id='$getid' ") or die(msyql_error()); 
    $tdata=mysql_fetch_assoc($query3);
    @extract($tdata);
   // echo $email.'ghghjgjjjghj';

     $profile=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$getid));
}
////////Calculate score ///////////


include('inc/sql_connect.php'); 
   $p2db=p2g();

 if($tdata['quiz_1_status']=='completed'&&$tdata['quiz_1_id']>0){

  $test_id=$tdata['quiz_1_id']; //Default 
  $quiz_1_score=quiz_result($p2db,$getid,$test_id);
}


///Quiz 2 result 
if($tdata['quiz_2_status']=='completed'&&$tdata['quiz_2_id']>0){
  $test_id=$tdata['quiz_2_id']; //Default 
  $quiz_2_score=quiz_result($p2db,$getid,$test_id);
}
 
 //print_r($quiz_1_score); 
  
   //print_r($quiz_2_score); 










//////////Save Info////////
if (isset($_POST['form_submit'])) {
    $arr_upcoming_tutoring_id=cdb_tutor_upcoming_tutorings();
  
   // print_r($arr_upcoming_tutoring_id);
   // die;   


 $profile=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$getid);
  $phone_number=$_POST['payment_phone'];
  if(mysql_num_rows($profile)==0){
   

    $sql=" INSERT INTO `tutor_profiles` SET payment_email= '".$_POST['payment_email']."',payment_phone='$phone_number',tutorid='$getid' ";
    $res=mysql_query($sql);
  
  }else{


     $sql=" UPDATE `tutor_profiles` SET payment_email= '".$_POST['payment_email']."',payment_phone='$phone_number' WHERE tutorid=".$getid;
    // echo $sql;
   $res=mysql_query($sql);

   


   //  echo 'UPdate payment info';

  }


  //Update mail Table: 
    $sql=mysql_query("UPDATE `gig_teachers` SET payment_em= '".$_POST['payment_email']."',payment_phone='$phone_number' WHERE id=".$getid);

   // die;
/////Check for payment detail/////////////////




  ////////////////////////////
    $firstname = $_POST['f_name'];
    $lastname = $_POST['lname'];
    $email = $_POST['email'];
    
    
   // $password = $_POST['password'];
   // $md5password = md5($password);
    
    $website= test_input($_POST["url_aww_app"]);
      //$website = ($_POST["url_aww_app"]);
    
    
    // check if URL address syntax is valid
//    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
//      $error = "Invalid URL"; $valid_url=false;
//    } 
    
    
    if (!empty($_POST['email'])&&$valid_url==true) {
       // $query = mysql_query("SELECT * FROM users WHERE email = '$email' OR user_name = '$username'", $link);
       
     $query = mysql_query("SELECT * FROM gig_teachers WHERE id!='$getid' AND email = '$email' ", $link);   
        $rows = mysql_num_rows($query);
        
       ////  Validate Emial :Not of Previous uSer 
        
        if ($rows < 1) { // New Register
           
           
            //  home_address phone birth_date  	url_aww_app  created_date
          
           
           
           // status
            
            $query=" UPDATE gig_teachers SET f_name='$firstname',"
                . "lname='$lastname',status=".$_POST['status'].", "
        
                . "email='$email', ";
            
            
            if(!empty($_POST['home_address']))
             $query.=" home_address='".$_POST['home_address']."', ";
             if(!empty($_POST['phone']))
             $query.=" phone='".$_POST['phone']."', ";
             
             if(!empty($_POST['birth_date']))
             $query.=" birth_date='".$_POST['birth_date']."', ";
             if(!empty($_POST['url_aww_app']))
             $query.=" url_aww_app='".$_POST['url_aww_app']."', ";
            
            
             
             
             $query.=" created_date='$today' WHERE id='$getid' ";
         //  $query.=" created_date='$today' ";
            
            $data = mysql_query($query)or die(mysql_error());
            
        //echo "<br><br> testing " . $query . " testing <br><br>";
        //exit();
            
            $userId = mysql_insert_id();

            if ($data) {
                $_SESSION['temp_email']=$email;
                $error = "Success,Registration";# NoRegis  
                // AddLogToSuspendedUserTutoring-//
                 if($_POST['status']==2){ // Suspended Tutor
                   $message= 'Suspended Tutor::'; 
                
                $add_log=cdb_add_suspend_cancelled_log($arr_upcoming_tutoring_id);
                 }

                // AddLogToSuspendedUserTutoring-//


                header("location:tutor-list.php");exit;
               
                
            } else {
                $error = "Sorry, Can not Register try later";# NoRegis
            }
            
            
            
            
        } else {
            $error = $email."<br/>-Email already exists";
        }
        
        
    } else {
        // $valid_url=false;
        $error = "Email Required";
        
        if($valid_url==false)
            $error = $website.",-Invalid url.<br/>Enter a Valid Url";
        
        
        
    }
}








/////Listing////////

$results = mysql_query("SELECT u.*, p.`limited` FROM `users` u LEFT JOIN `packages` p ON u.`role` = p.`id` WHERE u.`id` = '$id'", $link);
// echo "SELECT u.*, p.`limited` FROM `users` u INNER JOIN `packages` p ON u.`role` = p.`id` WHERE u.`id` = '$id'";
if (mysql_num_rows($results) > 0) {
    if (mysql_num_rows($results) == 1) {
        $row = mysql_fetch_assoc($results);
    } else {
        $error = 'Error';
    }
} else {
    $error =NULL;
}
$master_schoolid = $row['master_school_id'];
$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');






?>
<script type="text/javascript">
    
    ////////////////////
    $(document).ready(function () {
         $('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
});

        
        
      //////////////////  

        $('#district').chosen();
        
        $('#district').change(function () {
            district = $(this).val();
            $('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_schools', school_id: '<?php print $master_schoolid; ?>'},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();
        $('#text-change-avt').on('click', function () {
            $('#input-change-avt').trigger("click");
        });
        $('#input-change-avt').on('change', function () {
            var inp = document.getElementById('input-change-avt');
            var file = inp.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onloadend = function (e) {
                    $('.avatar > img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
            ;
        });

        $('#profile-submit').on('click', function () {
            $('.required').each(function () {
                if (!$(this).val()) {
                    $(this).parent().addClass('warning');
                }
            });

            var $email = $('#email').val();
            if (!isEmail($email)) {
                $('#email').parent().addClass('warning');
            }

            var $check = $('.warning').length;
            if ($check != 0)
                return false;
        });
        $('#form-profile input.required').blur(function () {
            if ($(this).val().length === 0) {
                $(this).parent().addClass('warning');
            }
        });
        $('#form-profile input.required').focus(function () {
            $(this).parent().removeClass('warning');
        });
        $("#repassword").keyup(function () {
            checkPasswordMatch();
        });
        $("#password").keyup(function () {
            checkPasswordMatch();
        });

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
        function checkPasswordMatch() {
            var $password = $("#password").val();
            var $rePassword = $("#repassword").val();

            if ($password != $rePassword)
                $("#repassword").parent().addClass('warning');
            else
                $("#repassword").parent().removeClass('warning');
        }
    });
</script>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
<?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="folder_wrap" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Edit Information
                        </h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
      <?php
//if ($error != '') {
//    echo '<p class="error">' . $error . '</p>';
//} else {
              ?>
                            <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                <div class="profile-top col-md-12">
                                  
                                    
                           <?php  if(isset($error)&&!empty($error)){?>
                                    <div class="profile-item alert alert-info text-left">
                              
                                  <?=$error;?>
                                   
                                        
                                       
                           </div> <?php }?>
                                    
                                                
                                              
                                </div>
                                <div class="profile-center col-md-12">
                                    <h4 class="title">Personal Information</h4>
                                   
                                    
                                    
                                    <div class="box col-md-12">
                                        <div class="left col-md-6">
                                            <label for="firstname">First Name:</label>
                                            <input type="text" id="f_name" 
                                                   required="" class="required" name="f_name" value="<?=(isset($f_name))?$f_name:NULL?>"/>
                                            <div class="notif">*First Name</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="lastname">Last Name:</label>
                                            <input type="text" id="lname" 
                                                   class="required" name="lname" value="<?=(isset($lname))?$lname:NULL?>"/>
                                            <div class="notif">*Last Name</div>
                                        </div>
                                        <div class="left col-md-6">
                                            <label for="email">Email:</label>
                                        <input type="email" id="email"
                                       name="email" class="required" value="<?=(isset($email))?$email:NULL?>"/>
                                            <div class="notif">*Enter Valid Email</div>
                                        </div>
                                      
                                        
                                        
                                        
                                        
                                      
                                        

                                        <div class="right col-md-6">
                                            <label for="phone">Phone:</label>
                                <input type="text" class="required"  required="" id="phone" name="phone" value="<?=(isset($phone))?$phone:NULL?>"/>
                                            <div class="notif">*Please enter your Telephone Number(Home,Cell)</div>
                                        </div>
                                        
                                        
                                        <div class="right col-md-6">
                                            <label for="Status">Status:</label>
                                            <select name="status" style="background-color: aquamarine;">
                                               
                              <option value="1" <?=($status==1)?"selected":NULL?> >Active</option>
                            <option value="2"  <?=($status==2)?"selected":NULL?> >Suspended</option>
                       
                                    
                                </select>
                             
                                        </div>
                                        <?php // $tdata

                                          // print_r($all_state_url);
                                            //print_r($all_state);
                                            if($all_state_url!='home.php'){
     
                                          ?>


                                         <div class="right col-md-12" style="padding: 10px;text-align: center;">

                                            
                                            <a class="btn btn-success btn-lg" style="padding: 7px;"
                                             href="applicant_detail.php?tid=<?=$_GET['tid']?>">View Tutor application</a>
                             
                                        </div>  <?php }?>



                                        
                                    
                                        
                                        
                                        
                                        <div class="clearnone">&nbsp;</div>
                                        
                                        
                                        
                                        
                                        
                                      <div class="right col-md-12">
                                            <label for="phone">Address:</label>
                      
                                            
                                            <textarea  style="border: 1px solid #dddddd;
float: left;
width: 100%;
line-height: 40px;
padding: 0 10px;" id="home_address" name="home_address"><?=(isset($home_address))?$home_address:NULL?></textarea>
                                            
                                            
                                            
                                            
                                            <div class="notif">*Please enter your Address</div>
                                        </div> 
                                        
                                        
                                        
                                        <?php 
                                        { 
                                        // if(!empty($url_aww_app)){
                                        ?>
                                    <div class="right col-md-12">
                                            <label class="text-primary" for="phone">URL for online tutoring( https://www.groupworld.net/)*:</label>
                                            <input   type="text"  placeholder="Enter Valid Url"
                           id="url_aww_app" name="url_aww_app" value="<?=(isset($url_aww_app))?$url_aww_app:NULL?>"/>
                                            <div class="notif">
                                                
                                                *URL for online tutoring</div>
                            <?php  if($valid_url==false) echo "Invalid Url "; ?>
                                        </div> 
                                        
                                        <?php 
                                      }if(!empty($birth_date)){?>
                                         <div class="right col-md-12">
                                            <label for="phone">Birthday:</label>
                                 
                                            <input name="birth_date" value="<?=(isset($birth_date))?$birth_date:NULL?>" class="datepicker" data-date-format="mm/dd/yyyy">
                                            <div class="notif">Birthday</div>
                                        </div> 
<?php
}?>
                                      <!--   Quiz -->
                                       <?php

                                       $test_1='Math';
                            $test_2='English';
                                       ////////////
                                         if($tdata['quiz_1_status']=='completed'&&$tdata['quiz_1_id']>0){ ?>

                                      <div class="right col-md-12" >
                                           <label for="phone"><?=$test_1?> Result(%)</label>

                                           <input  readonly=""  value="<?=$quiz_1_score?>%" >
                                            
                                          
                                                </div>
                                                <?php } ?>




                                       <?php if($tdata['quiz_2_status']=='completed'&&$tdata['quiz_2_id']>0){ ?>

                                      <div class="right col-md-12" >
                                            <label for="phone"><?=$test_2?> Result(%)</label>

                                           <input  readonly=""  value="<?=$quiz_2_score?>%" >
                                            
                                          
                                                </div>
                                                <?php } ?>



                                                <?php 

                                                //if(!empty($profile['payment_email'])&&!empty($profile['payment_phone'])){

                                                ?>

                                          <div class="right col-md-12" >
                                           <h3 class="text-center text-primary">Payment information:</h3>

                           
                              <!-- <label>Paypal email address  </label>  -->
                              <div class="left col-md-12">
                                            <label for="firstname">Paypal email address:</label>
                                <input type="text" id="f45"  name="payment_email" 
                                 class="form-control" value="<?=$profile['payment_email'];?>"  >
                                           
                                        </div>

                                        <div class="left col-md-12">
                                            <label for="firstname">Paypal Phone Number:</label>
                                            <input type="text"  name="payment_phone" 
                                             class="form-control"  value="<?=$profile['payment_phone'];?>" >
                                            
                                        </div>
                                         <?php // }?>

                            
                         


                                          </div>        



                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit" name="form_submit">Save</button>
                                </div>
                            </form>
    <?php //}   ?>

                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
<?php
//alert(Send Email To Active);
if (isset($_GET['send']) && $_GET['send'] != '') {
    if ($_GET['send'] == 'true') {
        print('<script>alert("An activation link has been sent to the email address you\'ve provided!");</script>');
    } else {
        print('<script>alert("Activation link can not be sent. Please try again later!");</script>');
    }
}
?>

<?php include("footer.php"); ?>