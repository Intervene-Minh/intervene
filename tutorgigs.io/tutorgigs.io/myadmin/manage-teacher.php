<?php
/***
 * Teacher Regis. By Admin.
 * 
 * ***/


include("header.php");

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

/// Manage Teacher .. 'profile-submit'
/*
 * firstname
lastname
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
 
if (isset($_POST['signup_submit'])) {
     //print_r($_POST); die;  
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    //$username = $_POST['signup-username'];
   
    $email = $_POST['email'];
    $password = $_POST['password'];
    $md5password = md5($password);
    
    $website= test_input($_POST["url_aww_app"]);
      //$website = ($_POST["url_aww_app"]);
    
    // check if URL address syntax is valid
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
      $error = "Invalid URL"; $valid_url=false;
    } 
    
    
    if (!empty($_POST['email'])&&$valid_url==true) {
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
                header("location:teachers-list.php");exit;
               
                
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
    $error = 'Item not found';
}
$master_schoolid = $row['master_school_id'];
$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
?>
<script type="text/javascript">
    $(document).ready(function () {

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
                        <h3>+Add Teacher  
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
                                            <input type="text" id="firstname" 
                                                   required="" class="required" name="firstname" value="<?=(isset($firstname))?$firstname:NULL?>"/>
                                            <div class="notif">*First Name</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="lastname">Last Name:</label>
                                            <input type="text" id="lastname" 
                                                   class="required" name="lastname" value="<?=(isset($lastname))?$lastname:NULL?>"/>
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
                                        
                                        
                                    
                                        
                                        
                                        
                                        <div class="clearnone">&nbsp;</div>
                                        <div class="left col-md-6">
                                            <label for="password">Password:</label>
                                            <input type="password" required="" id="password" name="password"/>
                                            <div  class="">*Enter password</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="repassword">Re-Password:</label>
                                            <input type="password" required="" id="repassword" name="repassword"/>
                                            <div class="notif">"Please confirm your new password!"</div>
                                        </div>
                                        
                                        
                                        
                                      <div class="right col-md-12">
                                            <label for="phone">Address:</label>
                      
                                            
                                            <textarea  style="border: 1px solid #dddddd;
float: left;
width: 100%;
line-height: 40px;
padding: 0 10px;" id="home_address" name="home_address"><?=(isset($home_address))?$home_address:NULL?></textarea>
                                            
                                            
                                            
                                            
                                            <div class="notif">*Please enter your Address</div>
                                        </div> 
                                        
                                        
                                        
                                        
                                 <div class="right col-md-6">
                                            <label for="phone">URL for Aww App*:</label>
                                            <input   type="text" required="" placeholder="Enter Valid Url"
                           id="url_aww_app" name="url_aww_app" value="<?=(isset($url_aww_app))?$url_aww_app:NULL?>"/>
                                            <div class="notif">
                                                
                                                *URL for Aww App</div>
                            <?php  if($valid_url==false) echo "Invalid Url "; ?>
                                        </div> 
                                        
                                        
                                         <div class="right col-md-6">
                                            <label for="phone">Birthday:</label>
                                     <input type="date" id="birth_date" name="birth_date" value=""/>
                                            <div class="notif">Birthday</div>
                                        </div> 
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit" name="signup_submit">Save</button>
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