<?php
    include("header.php");
    if (!isset($_SESSION['schools_id'])) {
        header("Location: login_principal.php");
        exit();
    }
    /////////////////////////////
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
    
    if (isset($_SESSION['temp_email'])) unset($_SESSION['temp_email']);
    if (isset($_SESSION['temp_firstname'])) unset($_SESSION['temp_firstname']);
    if (isset($_SESSION['temp_lastname'])) unset($_SESSION['temp_lastname']);
    if (isset($_SESSION['temp_dist_name'])) unset($_SESSION['temp_dist_name']);
    if (isset($_SESSION['temp_master_school'])) unset($_SESSION['temp_master_school']);
    if (isset($_SESSION['temp_school_name'])) unset($_SESSION['temp_school_name']);
    if (isset($_SESSION['temp_phone'])) unset($_SESSION['temp_phone']);
    if (isset($_SESSION['temp_smart_preb'])) unset($_SESSION['temp_smart_preb']);
    if (isset($_SESSION['temp_data_dash'])) unset($_SESSION['temp_data_dash']);
    if (isset($_SESSION['temp_address'])) unset($_SESSION['temp_address']);
    if (isset($_SESSION['temp_city_name'])) unset($_SESSION['temp_city_name']);
    if (isset($_SESSION['temp_zipcode'])) unset($_SESSION['temp_zipcode']);
    if (isset($_SESSION['temp_billing_state'])) unset($_SESSION['temp_billing_state']);

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
        /////////////////////////////////////////////////////
        unset($_SESSION['is_passage']); 
        unset($_SESSION['list']); 
            unset($_SESSION['ses_taxonomy']); 
            unset($_SESSION['qn_list']); 
            unset($_SESSION['is_passage_grade']); 
        //////////////Unsset all created by:School//////////////////
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
        $error = 'ZZLogo has been updated successfully.';
    }


    $school = mysql_fetch_assoc(mysql_query("SELECT s.*, d.district_name FROM `schools` s LEFT JOIN loc_district d ON s.district_id = d.id WHERE `SchoolId` = {$_SESSION['schools_id']}"));
    $folders = mysql_query("SELECT 
        *
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
    $curr_school=$_SESSION['schools_id'];
    /* Process share folders */
    if (isset($_POST['share'])) {
        //echo '<pre>';   print_r($_POST); 
        
        $allow_status=(isset($_POST['sessions_access']))?'yes':'no'; 
        // echo  $allow_status.'==go Session access'; die;
    
        $userId = $_POST['share'];
        
        // Tut. session permisson to Teacher
        $sql="SELECT * FROM user_session_access WHERE role='teacher' AND user_id=".$userId;
        $get=mysql_num_rows(mysql_query($sql));
    
        if($allow_status=='yes'&&$get==0)
            $q="INSERT into user_session_access SET user_id='$userId',role='teacher',school_id=".$curr_school;
        elseif($get==1){  // delete with role and school : singl row only-teacher
            $q=" DELETE FROM user_session_access WHERE role='teacher' AND user_id='$userId' AND school_id=".$curr_school;
        }
        
        $query=mysql_query($q);
   
 
        // Tut. session permisson to Teacher
    
        ////////////
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
        // delete Tut. session settings 
        $curr_school=$_SESSION['schools_id'];
        $userId = $_POST['revoke'];
        $q=" DELETE FROM user_session_access WHERE role='teacher' AND user_id='$userId' AND school_id=".$curr_school;
        $query=mysql_query($q);
        //echo '<pre>';    print_r($_POST); die; 
        
        
    
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

    // Teachers Tut. allow status 
    //$sql=" SELECT * FROM `user_session_access` WHERE 1";


    $res=mysql_query(" SELECT * FROM `user_session_access` WHERE role='teacher' AND  school_id=".$curr_school);
    while($data=mysql_fetch_assoc($res)){
        $arr_allow_teacher[]=$data['user_id'];
    }
    //print_r($arr_allow_teacher);    //die;
    //  if(in_array(316, $arr_allow_teacher))
    //          echo 'exit key'; else echo 'not '; die;
  
?>

<div class="container">
    <?php 
        include "school_header-k.php";
    ?>

    <form name="principal_action" id="principal_action" method="POST" action="">
        <?php // echo 'School allowed Tut. sessions-'.$ses_allowed; die;?>
        <table class="table table-hover" >
            <tr>
                <th>STT</th>
                <th>Teacher</th>
                <th>Email</th>
                <th>Registered Folder</th>
                <th>Students #</th>
                <th>Action</th>
            </tr>
            <?php
            if (mysql_num_rows($users) > 0) {
                $i = 1;
                while ($user = mysql_fetch_assoc($users)) {
                    $total_students = mysql_fetch_assoc(mysql_query('SELECT COUNT(id) as total FROM students WHERE teacher_id = \''.$user['id'].'\' ')); 
                    //print_r($total_students);
                    $shared_terms = ($user['shared_terms'] != NULL) ? explode(',', $user['shared_terms']) : array();
                    echo "<tr>";
                    echo "<td>$i</td>";
                    echo "<td>{$user['first_name']} {$user['last_name']}</td>";
                    echo "<td>{$user['email']}<br>Number Of Student Per Teacher: {$total_students['total']}</td>";
                    echo "<td>";
                    if (count($registered_folders) > 0) {
                        echo "<div class='row' style='border: 2px solid; margin-right:0px;'>";
                         echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align: center;'><b>SmartPrep</b></div>";
                        foreach ($registered_folders as $key => $val) {
                            $checked = in_array($key, $shared_terms) ? "checked" : "";
                            echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
							<input type='checkbox' class='folders' name='folders[{$user['id']}][]' value='{$key}' {$checked} /> {$val}
						</div>";
                        }
                        echo "</div>";
                    } else {
                        echo "&nbsp;";
                    }
                    echo "</td>";
                    ?>
                    
                            
                            <?php
                            $school_data_dash_res = mysql_query('SELECT * FROM school_permissions WHERE school_id = \'' . $_SESSION['schools_id'] . '\' ');
                            if (mysql_num_rows($school_data_dash_res) > 0) { ?>
                            <td><div class="row" style="border: 2px solid; margin: 0px -4px 0px -15px;"><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align: center;'>
                                <b>Data Dash</b>
                            </div>
                            <?php
                                while ($school_permission = mysql_fetch_assoc($school_data_dash_res)) {
                                    $check_previous_perm = mysql_query('SELECT * FROM techer_permissions WHERE '
                                            . 'teacher_id  = \''.$user['id'].'\' AND '
                                            . 'school_id = \''.$_SESSION['schools_id'].'\' AND '
                                            . 'grade_level_id = \''.$school_permission['grade_level_id'].'\' ');
                                    // if(in_array(316, $arr_allow_teacher))
                                    ?>
                            <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
                                <input type="checkbox" <?php if(mysql_num_rows($check_previous_perm) > 0) { ?>  checked=""<?php } ?> name="teacher_permission[<?php print $user['id']; ?>][]" value="<?php print $school_permission['grade_level_id'] ?>"  /><?php print $school_permission['grade_level_name'] ?>
                            </div>
                                <?php }  if($ses_allowed=='yes'){  ?>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-info1">
                                        <input  name="sessions_access" <?=(in_array($user['id'], $arr_allow_teacher))?'checked':''?> 
                                                value="yes" type="checkbox">Tutor session access 
                                </div> <?php }?>
             
                                     </div>
                    </td><?php
                            } ?>
                    
                    <?php
                    echo "<td>
                                         
                                         <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'><a href='school.php?view=teacher&id={$user['id']}' target='_blank' class='btn btn-success'>Teacher View</a>
                                             
					<button type='submit' name='share' value='{$user['id']}' class='btn btn-primary'>Share</button>
					<button type='submit' name='revoke' value='{$user['id']}' class='btn btn-danger'>Revoke</button>
                                         </div>
                                            
				</td>";
                    echo "</tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='5'>No item found!</td></tr>";
            }
            ?>
        </table>
    </form>
</div>

<!-- Modal -->
<?php include "invitations_modal.php";?>

<script type="text/javascript">
    <?php 
        if ($error != '') { echo "alert('{$error}');"; } 
    ?>
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