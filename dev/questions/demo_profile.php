<?php
include("header.php");

$expired_url='demo_user_expire.php';

 if(isset($_SESSION['expired_user'])&&$_SESSION['expired_user']=='expired'){
    header("Location:".$expired_url); exit;

 }

/////////////

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
    $id = $_SESSION['demo_user_id'];
}

//print_r($_SESSION); die;
if (isset($_POST['profile-submit'])) {
    
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    //$username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $district_other = $_POST['district'];
    if($district_other == 'other'){
        $district = 0;
       $district_name = $_POST['other_district'];
       $school_id = 0; 
       $school_name = $_POST['other_school'];
    }else{
        $district = $_POST['district'];
        $school_id = $_POST['school'];
    }
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    //$last_updated = date('Y-m-d H:i:s');
    
    $master_school_id = 1;

    if (($password != "") && ($repassword != "")) {
        if ($password == $repassword) {
            $md5password = md5($password);
            $query = mysql_query("UPDATE demo_users SET password='$md5password',email='$email',first_name='$firstname',last_name='$lastname',district_id='$district',school_id='$school_id',phone_number='$phone',other_district='$district_name',other_school='$school_name' WHERE id='$id'", $link);
            /* user_name='$username', */
            /* changed password then redirect to login page */

            if ($_SESSION['login_role'] != 0)
                header('location: logout.php');
        }else {
            echo 'Password not match';
        }
    } else {
        
        //print_r($_POST); die;
        $query = mysql_query("UPDATE demo_users SET email='$email',"
                . "first_name='$firstname',"
                . "last_name='$lastname',"
                . "district_id = '$district' , "
                . "school_id='$school_id',"
                . "master_school_id = '$master_school_id' , "
                . "phone_number='$phone' , "
                . "other_district='$district_name' , "
                . "other_school='$school_name'  "
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


$results = mysql_query("SELECT u.*, p.`limited` FROM `demo_users` u LEFT JOIN `packages` p ON u.`role` = p.`id` WHERE u.`id` = '$id'", $link);
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
    function SelectDist(val){
 var element=document.getElementById('oth-dist');
 if(val=='other'){
   element.style.display='block';
   document.getElementById('school').style.display='none';
   document.getElementById('oth-school').style.display='block';
 }
 else  {
   element.style.display='none';
    document.getElementById('oth-school').style.display='none';
    document.getElementById('school').style.display='block';
 }
}
    
    $(document).ready(function () {
        $('#district').on('change', function(){
			var district = $(this).val();
			$("#school option[value='']").remove();
                        
			if( district == '' ) {
				alert('Please select a district!');
			} else {
				$.ajax({
					type	: "POST",
					url		: "get-school-district.php",
					data	: {district : district},
					success	: function(response) {
					$('#school').html(response);
					}
				});
			}
		});
                
        $('#district').trigger('change');
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

<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
<?php include("demo_sidebar.php"); ?>
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
if ($error != '') {
    echo '<p class="error">' . $error . '</p>';
} else {
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
                                                <?php echo $row['first_name'].' '.$row['last_name'];  ?>
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
                                                    <a class="btn btn-sm btn-warning" href="demo-active-user.php">Verify Your Email</a>
                                    <?php else: ?>
                                                    <span class="btn btn-sm btn-success">Activated</span>
                                    <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
    <?php if ($row['status'] == 0): ?>
                                        <div class="profile-item alert alert-info text-center">
                                            <a  href="demo-active-user.php">Please confirm your email address!</a></div>
    <?php endif; ?>
                                </div>
                                <div class="profile-center col-md-12">
                                    <h4 class="title">Customer Information</h4>
                                    <div class="box col-md-12">
                                        <div class="left col-md-6">
                                            <label for="firstname">First Name:</label>
                                            <input type="text" id="firstname" class="required" name="firstname" value="<?php echo $row['first_name']; ?>"/>
                                            <div class="notif">"lorem ipsum"</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="lastname">Last Name:</label>
                                            <input type="text" id="lastname" class="required" name="lastname" value="<?php echo $row['last_name']; ?>"/>
                                            <div class="notif">"lorem ipsum"</div>
                                        </div>
                                        <div class="left col-md-6">
                                            <label for="email">Email:</label>
                                            <input type="email" id="email" name="email" class="required" value="<?php echo $row['email']; ?>"/>
                                            <div class="notif">"lorem ipsum"</div>
                                        </div>
                                        <p>
                                            <label for="lesson_name">Choose District:</label><br />
                                            <?php //$selected = ($result['id'] == $row['district_id']) ? ' selected="selected"' : ''; ?>
                                            <select name="district" id="district" onchange='SelectDist(this.value);' class="form-control required">
						<option value=""> Select District </option>
						<?php
						if( mysql_num_rows($district_qry) > 0 ) {
							while( $district = mysql_fetch_assoc($district_qry) ){
                                                                ?>
                                                <option value="<?php echo $district['id']; ?>" <?php if(($district['id'] == $row['district_id'])){ echo "selected";} ?>><?php echo $district['district_name']; ?> </option>
                                            <?php
                                                        }
						}
						?>
                                                <option value="other" <?php if($row['district_id'] == 0){ echo "selected";} ?>>Other</option>
					</select>
                                             <input type="text" name="other_district" placeholder="Please Enter District Name" value="<?php echo $row['other_district']; ?>" class="form-control" id="oth-dist" style='display:none;'/>
                                            

                                        </p>
                                        <div id="district_schools">

                                            <label for="lesson_name">Choose Schools:</label>
                                            <select name="school" id="school" class="form-control " style='display:block;'>
						<option value=''></option>
					</select>
                                         <input type="text" name="other_school" placeholder="Please Enter School Name" value="<?php echo $row['other_school']; ?>" class="form-control" id="oth-school" style='display:none;'/>
                                        </div>

                                        <div class="right col-md-6">
                                            <label for="phone">Phone:</label>
                                            <input type="text" id="phone" name="phone" value="<?php echo $row['phone_number']; ?>"/>
                                            <div class="notif">"lorem ipsum"</div>
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