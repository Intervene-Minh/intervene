<?php
/***
 * login_id ==>ses_teacher_id
 * **/
include("header.php");

if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

//////////Validate Site Access//////////
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}






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
    $id = $_SESSION['ses_teacher_id'];
}






//////// SaveProfile///////////
if (isset($_POST['profile-submit'])) {
    $error = 'Profile Saved';
    //var_dump($_POST);
}
if (isset($_POST['xxx_profile-submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $school = $_POST['school'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $last_updated = date('Y-m-d H:i:s');
    $district = $_POST['district'];
    $master_school_id = $_POST['master_school'];

    if (($password != "") && ($repassword != "")) {
        if ($password == $repassword) {
            $md5password = md5($password);
            $query = mysql_query("UPDATE users SET password='$md5password',email='$email',first_name='$firstname',last_name='$lastname',phone_number='$phone',last_updated='$last_updated' WHERE id='$id'", $link);
            /* user_name='$username', */
            /* changed password then redirect to login page */

            if ($_SESSION['login_role'] != 0)
                header('location: logout.php');
        }else {
            echo 'Password not match';
        }
    } else {
        $query = mysql_query("UPDATE users SET email='$email',"
                . "first_name='$firstname',"
                . "last_name='$lastname',"
                . "school='$school',"
                . "phone_number='$phone',"
                . "district_id = '$district' , "
                . "master_school_id = '$master_school_id' , "
                . "last_updated='$last_updated' "
                . "WHERE id='$id'", $link);
        /* user_name='$username', */
    }
    if (!empty($_FILES['avatar']['name'])) {
        include("inc/upload-img.php");

        $path = 'uploads/avatar/';
        $filename = $id;
        $dir = $path . $filename . '/';
        /* function upload_img($fileupload,$path,$filename,$empty) */
        if (upload_img($_FILES['avatar'], $dir, $filename, true) == 0) {
            echo 'have error';
        }
    } else {
        //echo 55;
    }
}

//////// SaveProfile///////////

//User Profile





//////////UserProfile/////

$sql="SELECT * FROM gig_teachers WHERE id='$id' ";
$results = mysql_query($sql);
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
                        <h3>Profile Details - <?php if ($row['status']) {
    echo 'Enabled';
} else {
    echo 'Disabled';
} ?></h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
<?php
//if ($error != '') {
//    echo '<p class="error">' . $error . '</p>';
//} else {
    
    
    ?>
                            <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                <div class="profile-top col-md-12">
                                    <div class="col-md-3">
                                        <div class="avatar">
                                            <?php
                                            $dir = 'uploads/avatar/' . $row['id'] . '/';
                                            $file_display = array('jpg', 'jpeg', 'png', 'gif');
                                            if (file_exists($dir) == false) {
                                                echo '<img src="images/avt-default.png" alt="avt-default.png">';
                                            } else {
                                                $dir_contents = scandir($dir);
                                                foreach ($dir_contents as $file) {
                                                    $file_type = strtolower(end(explode('.', $file)));

                                                    if ($file !== '.' && $file !== '..' && in_array($file_type, $file_display) == true) {
                                                        echo '<img src="', $dir, '/', $file, '" alt="', $file, '" />';
                                                    }
                                                }
                                            }
                                            ?>

                                        </div>
                                        <div class="change-avt">
                                            <span id="text-change-avt">Change Picture</span>
                                            <input type="file" id="input-change-avt" name="avatar"/>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="profile-item">
                                            <div class="left col-md-4">
                                                <label for="profile-username">Profile Name:</label>
                                            </div>
                                            <div class="right col-md-8">
                                                <input type="text" id="profile-username"
                                                       class="required" name="username" value="<?php echo $row['email']; ?>" readonly style="width: 100%;" />
                                                <div class="notif">*Please fill your user name</div>
                                            </div>
                                        </div>
                                        <div class="profile-item">
                                            <div class="left col-md-4">	
                                                <label>Last Updated:</label>
                                            </div>
                                            <div class="right col-md-8">
                                                <div class="last-updated">
                                                    <?php
                                                    $datetime = strtotime($row['last_updated']);
                                                    if ($datetime == 0 || $datetime < 0) {
                                                        echo '--.--.----';
                                                    } else {
                                                        echo date('F d, Y', $datetime);
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="profile-item">
                                            <div class="left col-md-4">	
                                                <label>Active User:</label>
                                            </div>
                                            <div class="right col-md-8">
    <?php if ($row['status'] == 0): ?>
                                                    <a class="btn btn-sm btn-warning" href="active-user.php">Verify Your Email</a>
                                    <?php else: ?>
                                                    <span class="btn btn-sm btn-success">Activated</span>
                                    <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
    <?php if ($row['status'] == 0): ?>
                                        <div class="profile-item alert alert-info text-center">Please confirm your email address!</div>
    <?php endif; ?>
                                </div>
                                <div class="profile-center col-md-12">
                                    <h4 class="title">Primary Information</h4>
                                    <div class="box col-md-12">
                                        <div class="left col-md-6">
                                            <label for="firstname">First Name:</label>
                               <input type="text" id="firstname" class="required" name="firstname" value="<?= $row['f_name']?>"/>
                                            <div class="notif">"lorem ipsum"</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="lastname">Last Name:</label>
                                            <input type="text" id="lastname" class="required" name="lastname" value="<?php echo $row['lname']; ?>"/>
                                            <div class="notif">"lorem ipsum"</div>
                                        </div>
                                        <div class="left col-md-6">
                                            <label for="email">Email:</label>
                                            <input type="email" id="email" name="email" class="required" value="<?php echo $row['email']; ?>"/>
                                            <div class="notif">"lorem ipsum"</div>
                                        </div>
                                       
                                        <div id="district_schools">

                                            <label for="lesson_name">Choose Options:</label>
                                            <div id="district_school">
                                                Select District to choose schools.
                                            </div>

                                        </div>

                                        <div class="right col-md-6">
                                            <label for="phone">Phone:</label>
                                            <input type="text" id="phone" name="phone" value="<?php echo $row['phone_number']; ?>"/>
                                            <div class="notif">"lorem ipsum"</div>
                                        </div>
                                        <div class="left col-md-6">
                                            <label for="school">School:</label>
                                            <select id="school" name="school" class="form-control">
                                                <option value=""></option>
                                                <?php
                                                $schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
                                                if (mysql_num_rows($schools) > 0) {
                                                    while ($shool = mysql_fetch_assoc($schools)) {
                                                        $selected = ($row['school'] == $shool['SchoolId']) ? 'selected' : '';
                                                        echo '<option value="' . $shool['SchoolId'] . '" ' . $selected . '>' . $shool['SchoolName'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="notif">Please select your school!</div>
                                        </div>
                                        <div class="clearnone">&nbsp;</div>
                                        <div class="left col-md-6">
                                            <label for="password">Password:</label>
                                            <input type="password" id="password" name="password"/>
                                            <div class="">"Leave password blank if don't want to change"</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="repassword">Re-Password:</label>
                                            <input type="password" id="repassword" name="repassword"/>
                                            <div class="notif">"Please confirm your new password!"</div>
                                        </div>
                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit" name="profile-submit">Save</button>
                                </div>
                            </form>
    <?php
//}
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