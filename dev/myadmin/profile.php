<?php
/****
@tutor admin profile

**/

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


 //print_r($_SESSION);

 $admin_id=$_SESSION['login_id'];

 $profile=mysql_fetch_assoc(mysql_query(" SELECT * FROM `gig_admins` WHERE id=".$admin_id));
 //print_r($profile);

  //$warning_msg=array();
    // $warning_msg[]= 'info change messsage!';  //die; 

//////Save///////////

if (isset($_POST['profile_submit'])) {
  //print_r($_POST); die; 

  $warning_msg=array();


   //$warning_msg[]= 'info change messsage!';  //die; 

  ######################################



    
    $username = $_POST['user_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $last_updated = date('Y-m-d H:i:s');


    if (($password != "") && ($repassword != "")) {
           

        if ($password == $repassword) {
            //echo '==change password'; die;
            $md5password = md5($password);  // mysql_query

            $query =mysql_query("UPDATE gig_admins SET password='$md5password',email='$email',last_updated='$last_updated',user_name='$username' WHERE id='$id'");

            session_destroy(); 

            $warning_msg[]= 'Password updated, please login again!!';  
             header('location:logout.php');exit; 
           

            /* user_name='$username', */
            /* changed password then redirect to login page */
             //logout user change reflect .
            // header('location: logout.php');exit; 


        }else {
            $warning_msg[]= 'Password not match';  //die; 
        }




    }else{  // passsword not updated::profile save

             
 $query =mysql_query("UPDATE gig_admins SET email='$email',last_updated='$last_updated',user_name='$username' WHERE id='$id'");

        // $query = mysql_query("UPDATE users SET email='$email',"
        //         . "first_name='$firstname',"
        //         . "last_name='$lastname',"
        //         . "school='$school',"
        //         . "phone_number='$phone',"
        //         . "district_id = '$district' , "
        //         . "master_school_id = '$master_school_id' , "
        //         . "last_updated='$last_updated' "
        //         . "WHERE id='$id'" );
    $profile=mysql_fetch_assoc(mysql_query(" SELECT * FROM `gig_admins` WHERE id=".$admin_id)); //getback

         $warning_msg[]= 'Information saved!';  //die; 

        /* user_name='$username', */
    }

//////////////////////
     if(!empty($warning_msg)){

        $show_msg=implode('<br/>', $warning_msg);
     }


   


}

////////////////////////////





$Sql="SELECT * FROM gig_admins WHERE id = '$id' ";
$results = mysql_query($Sql);




// echo "SELECT u.*, p.`limited` FROM `users` u INNER JOIN `packages` p ON u.`role` = p.`id` WHERE u.`id` = '$id'";
if (mysql_num_rows($results) > 0) {
    if (mysql_num_rows($results) == 1) {
        $row = mysql_fetch_assoc($results);
    } else {
        $error = 'Error';
    }
} else {
    $error = 'No record found! ';
}





$master_schoolid = $row['master_school_id'];
$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
?>
<script type="text/javascript">
    $(document).ready(function () {

        // $('#district').chosen();
        
        // $('#district').change(function () {
        //     district = $(this).val();
        //     $('#district_school').html('Loading ...');
        //     $.ajax({
        //         type: "POST",
        //         url: "ajax.php",
        //         data: {district: district, action: 'get_schools', school_id: '<?php print $master_schoolid; ?>'},
        //         success: function (response) {
        //             $('#district_school').html(response);
        //             $('#d_school').chosen();
        //         },
        //         async: false
        //     });
        // });

        // $('#district').change();
        // $('#text-change-avt').on('click', function () {
        //     $('#input-change-avt').trigger("click");
        // });


        // $('#input-change-avt').on('change', function () {
        //     var inp = document.getElementById('input-change-avt');
        //     var file = inp.files[0];
        //     if (file) {
        //         var reader = new FileReader();
        //         reader.onloadend = function (e) {
        //             $('.avatar > img').attr('src', e.target.result);
        //         };
        //         reader.readAsDataURL(file);
        //     }
        //     ;
        // });



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
                        <h3>Profile Details</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
<?php
if ($error != '') {
    echo '<p class="error">' . $error . '</p>';
} else {
    ?>
                            <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                             <?php 

                              if(!empty($warning_msg)&&count($warning_msg)>0){
                                $show_msg=implode('<br/>', $warning_msg);
    

                             ?>
                             <div class="profile-item alert alert-danger text-center"><?=$show_msg?></div>
                             <?php }?>
                                






                                <div class="profile-center col-md-12">

                                




                                    <h4 class="title">Profile Information</h4>
                                    <div class="box col-md-12">


                                        <div class="left col-md-12">
                                            <label for="firstname">Username:</label>
                                            <input type="text" id="firstname" class="required" name="user_name" 
                                              value="<?=(!empty($profile['user_name']))?$profile['user_name']:NULL; ?>"  />
                                            <div class="notif">"Username required"</div>
                                        </div>

                                      



                                        <div class="left col-md-12">
                                            <label for="email">Email:</label>
                                            <input type="email" id="email" name="email" class="required"
                                             value="<?=(!empty($profile['email']))?$profile['email']:NULL; ?>"  />
                                            <div class="notif">"Email required"</div>
                                        </div>



                                    


                                    



                                        <!-- <div class="clearnone">&nbsp;</div> -->


                                        <div class="left col-md-6" style="display: none;">
                                            <label for="password">Password:</label>
                                            <input type="password" id="password" name="password" value=""   />
                                            <div class="">"Leave password blank if don't want to change"</div>
                                        </div>
                                        <div class="right col-md-6" style="display: none;"  >
                                            <label for="repassword">Re-Password:</label>
                                            <input type="password" id="repassword" name="repassword"/>
                                            <div class="notif">"Please confirm your new password!"</div>
                                        </div>


                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit" name="profile_submit">Save</button>
                                </div>
                            </form>
    <?php
}
?>

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