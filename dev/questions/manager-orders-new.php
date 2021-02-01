<?php
    /**
    @Add Orders / Products for TELPAS Pro 
    @ TELPAS Pro 

    **/
    ini_set('display_errors', 0);
    include("header.php");

    $login_role = $_SESSION['login_role'];
    if ($login_role != 0 || !isGlobalAdmin()) {
        header("location: index.php");
    }

    $puDataSchoolId = "";

    if ($_GET['id']) {
        $pu = mysql_query("
            SELECT *
            FROM `purchases`
            WHERE `id` = " . $_GET['id']
        );

        $puData = mysql_fetch_assoc($pu);
        $get_school_id = $puDataSchoolId = $puData['schoolID'];
    }
    //echo $get_school_id ; die;

    if ($puDataSchoolId) {
        $qR = mysql_query("SELECT * FROM `schools` WHERE `SchoolId` = " . $puDataSchoolId);
        $dQr = mysql_fetch_assoc($qR);
    }

    function sanitize($value) {
        return trim(strip_tags($value));
    }

    function sendEmailNoticePurchases($firstname, $email, $pass) {
        require 'inc/PHPMailer-master/PHPMailerAutoload.php';

        $message = "Dear {$firstname},
        <br/><br/> 
        Thank you for the opportunity to work with your school. We are excited to help your teachers save time in the classroom.<br/>
        Next Steps:<br/>
        1) <a href='https://intervene.io/questions/login_principal.php'>Login</a> to your account. Be sure to go to administrator login if you are visiting our site through www.intervene.io<br/>
        2) Update password if you want (optional)<br/>
        3) Share access with your teachers<br/>
        Here is the login information for your administrator account:<br/>
        Email: {$email}
        <br/>
        Temp Password: {$pass}
        <br/><br/>
        Best regards, <br/>
        <strong>Intervene Team</strong><br/>
        <a href='https://intervene.io'>www.intervene.io</a>
        <br/><br/>
        <img alt='Less Test Prep' src='https://intervene.io/questions/images/logo.png'>";

        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Set who the message is to be sent from
        $mail->setFrom('pathways2greatness@gmail.com', 'Intervene Team');
        //Set an alternative reply-to address
        $mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Team');
        // Set who the message is to be sent to
        $mail->addAddress($email, '');
        //Set the subject line
        $mail->Subject = 'Welcome to Intervene!';
        //Replace the plain text body with one created manually
        $mail->Body = $message;
        $mail->AltBody = $message;
        //send the message, check for errors
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
    //////////
    $sql = "SELECT * FROM `user_session_access` WHERE role='school' AND  user_id = " . $get_school_id;
    $data_total = mysql_num_rows(mysql_query($sql));
    $school_allowed_ses = ($data_total >= 1) ? 'yes' : 'no';   //School allowed or Not
    // is_intervention_allowed
    $sql = "SELECT * FROM user_intervention_access WHERE role = 'school' AND  user_id = " . $get_school_id;
    $total = mysql_num_rows(mysql_query($sql));
    $is_intervention_allowed=($total>=1)?'yes':'no'; 





    $Telpas_tbl='tbl_telpas_access';

    $sql="SELECT * FROM $Telpas_tbl WHERE role='school' AND user_id=".$get_school_id;
    $school_telpas_rows=mysql_num_rows(mysql_query($sql));

    //echo 'school_telpas_rows:', $school_telpas_rows;

    ////Save///////////
    if (isset($_POST['school'])) {

        // $get_school_id ::School id

        $allow_status=(isset($_POST['sessions_access']))?'yes':'no'; 
    
    // check tut session setting for schools
    $sql="SELECT * FROM user_session_access WHERE role='school' AND user_id=".$get_school_id;
    $get_num==0;
    $get_num=mysql_num_rows(mysql_query($sql));
    
    if($get_num<1&&$allow_status=='yes'){
        // mysql_query
        mysql_query("INSERT into user_session_access SET user_id='$get_school_id',role='school',school_id=".$get_school_id);  //die;
    }elseif($get_num==1&&$allow_status=='no'){  // elseif($get==1){

        mysql_query(" DELETE FROM user_session_access WHERE school_id=".$get_school_id);
        }
    ///////TelPas permission////////////
        $set_msg='';
        $Is_telpass_permission='';
        $sql="SELECT * FROM $Telpas_tbl WHERE role='school' AND user_id=".$get_school_id;
        $telpas_rows=mysql_num_rows(mysql_query($sql));
        $set_msg1=$telpas_rows.'-permission rows! ';

        if(isset($_POST['telpas_pro_access'])&&$telpas_rows<1){

            $set_msg= 'allowed permission for telpas_pro_access! ';// 
            mysql_query("INSERT into $Telpas_tbl SET user_id='$get_school_id',role='school',school_id=".$get_school_id);

        }elseif(!isset($_POST['telpas_pro_access'])){
            $set_msg= 'Remove Telpas Access';// 
        mysql_query(" DELETE FROM $Telpas_tbl WHERE school_id=".$get_school_id);

        }
        //////////echo $set_msg1 ,$set_msg; ////////
        



        


    // Intervention accesss///////////////////

        $intrv_status=(isset($_POST['intervention_access']))?'yes':'no'; 
    $sql="SELECT * FROM user_intervention_access WHERE role='school' AND user_id=".$get_school_id;$get=0;

    $get=mysql_num_rows(mysql_query($sql));
    //echo '==intervention_access'.$get; die;
    if($intrv_status=='yes'&&$get==0){
        $q="INSERT into user_intervention_access SET user_id='$get_school_id',role='school',school_id=".$get_school_id;
    }elseif($get==1&&$intrv_status=='no'){  // elseif($get==1){

        $q=" DELETE FROM user_intervention_access WHERE school_id=".$get_school_id;
        }
    $query=mysql_query($q);

    // Intervention accesss///

    // $q="UPDATE user_session_access SET allowed='$allow_status' WHERE user_id=".$get_school_id;
    
    // check tut session setting for schools
        /* Get orders data */
        $school = trim(mysql_real_escape_string($_POST['school']));
        $email = trim(mysql_real_escape_string($_POST['email']));
        $password = md5($_POST['password']);
        $state = trim(mysql_real_escape_string($_POST['state']));
        $type = trim(mysql_real_escape_string($_POST['type']));
        $subject = $_POST['subject'];
        $permission = $_POST['permission'];
        
    $data_dash_name = implode(",",$subject); 
        $smart_preb_name = implode(",",$permission);
        $district_id_mail = $_POST['district'];
            $dist_sql = mysql_query("SELECT `district_name` from loc_district where id='$district_id_mail'");
            if (mysql_num_rows($dist_sql) > 0) {
                $row = mysql_fetch_assoc($dist_sql);
                $dist_mail_name = $row['district_name'];
            }
            
            $master_school_id_mail = $_POST['master_school'];
            $school_sql = mysql_query("SELECT `school_name` from master_schools where id='$master_school_id_mail'");
            if (mysql_num_rows($school_sql) > 0) {
                $row = mysql_fetch_assoc($school_sql);
                $master_school_name = $row['school_name'];
            }
        /* Calculate total amount */
        $qty = count($subject);
        $price = ($type == 'Paper-based assessments only') ? 325 : 500;
        $amount = (int) $qty * $price;

        if ($puData['id'] && isset($_GET['ac'])) {
            $puId = $puData['id'];
            //echo 'Test'; die;
            /* Get orders data */
            $school = trim(mysql_real_escape_string($_POST['school']));
            $email = trim(mysql_real_escape_string($_POST['email']));
            $password = md5($_POST['password']);
            $state = trim(mysql_real_escape_string($_POST['state']));
            $type = trim(mysql_real_escape_string($_POST['type']));
            $subject = $_POST['subject'];
            $permission = $_POST['permission'];
            //print_r($permission);die;
            /* Get checkout data */
            $firstname = sanitize($_POST['firstname']);
            $lastname = sanitize($_POST['lastname']);

            $address = sanitize($_POST['address']);
            $phone = sanitize($_POST['phone']);
            $city = sanitize($_POST['city']);
            $zipcode = sanitize($_POST['zipcode']);
            $billing_state = sanitize($_POST['billing_state']);

            $payInfoUpdate = array();

            $payInfoUpdate['firstname'] = $firstname;
            $payInfoUpdate['lastname'] = $lastname;

            $payInfoUpdate['address'] = $address;
            $payInfoUpdate['phone'] = $phone;
            $payInfoUpdate['city'] = $city;
            $payInfoUpdate['zipcode'] = $zipcode;
            $payInfoUpdate['state'] = $billing_state;

            $dataInfo = serialize($payInfoUpdate);
            $district_id = $_POST['district'];
            $master_school_id = $_POST['master_school'];
            /* Insert data into table `schools` */
            
            
            if($district_id){
                //echo 'suhail'.$puData['schoolID'];die;
                $sql = "UPDATE `users` SET district_id = '{$district_id}' , master_school_id = '{$master_school_id}' WHERE school = " . $puData['schoolID'];
                mysql_query($sql);
            }
            
            if (!empty($password)) {
                $sql = "UPDATE `schools` SET SchoolName = '{$school}' , master_school_id = '{$master_school_id}', district_id = '{$district_id}', SchoolMail = '{$email}', SchoolPass = '{$password}', SchoolAddress = '{$state}', status = 1 WHERE SchoolId = " . $puData['schoolID'];
            } else {
                $sql = "UPDATE `schools` SET SchoolName = '{$school}' , master_school_id = '{$master_school_id}', district_id = '{$district_id}', SchoolMail = '{$email}', SchoolAddress = '{$state}', status = 1 WHERE SchoolId = " . $puData['schoolID'];
            }
            
            $schoolInsert = mysql_query($sql);
            $upload_logo_qry = '';
            if ($_FILES['schoollogo'] && $_FILES["schoollogo"]["tmp_name"]) {
                // print_r($_FILES); die;
                $cwd = getcwd();
                $uploads_dir = $cwd . '/uploads/schoollogo';

                $school_logo_res = mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE `SchoolId` = {$puData['schoolID']}"));
                if (trim($school_logo_res['schoollogo']) != '') {
                    unlink($uploads_dir . '/' . $school_logo_res['schoollogo']);
                }
                $tmp_name = $_FILES["schoollogo"]["tmp_name"];
                // basename() may prevent filesystem traversal attacks;
                // further validation/sanitation of the filename may be appropriate
                $name = $schoolInsert . '_' . basename($_FILES["schoollogo"]["name"]);
                //echo "$uploads_dir/$name"; die;

                move_uploaded_file($tmp_name, "$uploads_dir/$name");
                mysql_query("UPDATE schools SET schoollogo = \"" . $name . "\" WHERE `SchoolId` = {$puData['schoolID']}  ");
                $upload_logo_qry = '?upload_logo=yes';
            
            }
    $error = 'Details has been saved successfully.';

            /* Insert data into table `purchases` */
            $sql2 = "UPDATE `purchases` SET schoolID = '{$puDataSchoolId}', email = '{$email}', type = '{$type}', amount = '{$amount}', payInfo = '{$dataInfo}' ,role = '{$login_role}' WHERE id = " . $puId;
            $purchaseInsert = mysql_query($sql2);

            /* Insert purchase meta */
            
            mysql_query("DELETE FROM `purchasemeta` WHERE `purchaseId` = " . $puId);
        // print_r($subject); die;
            foreach ($subject as $key => $name) {
                
                mysql_query("INSERT INTO `purchasemeta` (`purchaseId`, `termId`) VALUES ('$puId', '$key')");
            }

            /* Remove shared grades that not existed after edit purchase meta */
            mysql_query("DELETE FROM `shared` WHERE `schoolId` = $puDataSchoolId AND `termId` NOT IN (" . implode(', ', $subject) . ")");

            //$permission;  
            // print_r($permission);
            /* Insert data into table `permission` */
            if ($permission) {
                $school_id = $puData['schoolID'];
                mysql_query("DELETE FROM `school_permissions` WHERE `school_id` = " . $school_id);
                foreach ($permission as $key => $name) {
                    mysql_query("INSERT INTO `school_permissions` (`school_id`, `grade_level_id`,`grade_level_name`,`permission`) VALUES ('$school_id', '$key','$name','data_dash')");
                }
            }
            if (!$_POST['upload']) {
                // header("Location: manager-orders.php".$upload_logo_qry);
                //exit();
            }
        } else {
            /* Get checkout data */
            $permission = $_POST['permission'];
            $firstname = sanitize($_POST['firstname']);
            $lastname = sanitize($_POST['lastname']);

            $address = sanitize($_POST['address']);
            $phone = sanitize($_POST['phone']);
            $city = sanitize($_POST['city']);
            $zipcode = sanitize($_POST['zipcode']);
            $billing_state = sanitize($_POST['billing_state']);
            $district_id = $_POST['district'];
            $master_school_id = $_POST['master_school'];
            $payInfoUpdate = array();

            $payInfoUpdate['firstname'] = $firstname;
            $payInfoUpdate['lastname'] = $lastname;

            $payInfoUpdate['address'] = $address;
            $payInfoUpdate['phone'] = $phone;
            $payInfoUpdate['city'] = $city;
            $payInfoUpdate['zipcode'] = $zipcode;
            $payInfoUpdate['state'] = $billing_state;

            $dataInfo = serialize($payInfoUpdate);

            /* Insert data into table `schools` */

            $schoolInsert = mysql_query("INSERT INTO `schools` (`SchoolName`, `SchoolMail`, `SchoolPass`, `SchoolAddress`, `status`, `district_id`, `master_school_id`)
                            VALUES ('$school', '$email', '$password', '$state', 1, '$district_id', '$master_school_id')");
            $schoolId = mysql_insert_id();
            if ($_FILES['schoollogo'] && $_FILES["schoollogo"]["tmp_name"]) {
                $cwd = getcwd();
                $uploads_dir = $cwd . '/uploads/schoollogo';
                $tmp_name = $_FILES["schoollogo"]["tmp_name"];
                $name = $schoolId . '_' . basename($_FILES["schoollogo"]["name"]);
                move_uploaded_file($tmp_name, "$uploads_dir/$name");
                mysql_query("UPDATE schools SET schoollogo = \"" . $name . "\" WHERE `SchoolId` = {$schoolId}  ");
            }


            /* Insert data into table `purchases` */
            $purchaseInsert = mysql_query("INSERT INTO `purchases` (`schoolID`, `email`, `type`, `amount`, `role`, `payInfo`)
                            VALUES ('$schoolId', '$email', '$type', '$amount', '$login_role', '$dataInfo')");
            $purchaseId = mysql_insert_id();

            /* Insert purchase meta */
            foreach ($subject as $key => $name) {
                mysql_query("INSERT INTO `purchasemeta` (`purchaseId`, `termId`) VALUES ('$purchaseId', '$key')");
            }
            sendEmailNoticePurchases($firstname, $email, $_POST['password']);
            //echo '<h1>'.$email.'firstname'.$firstname.'lastname'.$lastname.'dist_mail_name'.$dist_mail_name.'data_dash'.$data_dash_name.'smart_preb_name'.$smart_preb_name.'master_school_name'.$master_school_name.'school' ; die;
            
            $_SESSION['temp_email']=$email;
            $_SESSION['temp_firstname']=$firstname;
            $_SESSION['temp_lastname']=$lastname;
            $_SESSION['temp_dist_name']=$dist_mail_name;
            $_SESSION['temp_master_school']=$master_school_name;
            $_SESSION['temp_school_name']=$school;
            $_SESSION['temp_phone']=$phone;
            $_SESSION['temp_smart_preb']=$smart_preb_name;
            $_SESSION['temp_data_dash'] = $data_dash_name;
            $_SESSION['temp_address']=$address;
            $_SESSION['temp_city_name']=$city;
            $_SESSION['temp_zipcode']=$zipcode;
            $_SESSION['temp_billing_state']=$billing_state;
            
            if ($permission) {
                foreach ($permission as $key => $name) {
                    mysql_query("INSERT INTO `school_permissions` (`school_id`, `grade_level_id`,`grade_level_name`,`permission`) VALUES ('$schoolId', '$key','$name','data_dash')");
                }
            } 
            
            header("Location: manager-orders.php?id=" . $purchaseId);
            exit();
        }
    }
    //parent id- 3 parent[ 1, 2617,2618] 
    // mysql_query
    // $folders = mysql_query("
    // 	SELECT *
    // 	FROM `terms`
    // 	WHERE `taxonomy` = 'category'
    // 	AND `active` =1
    //         AND `parent` IN (1,2617,2618)
    // 	AND `id` NOT IN ( 2634, 2635,2619,2620,2900,2901 )
    // 	ORDER BY parent ASC
    // "); 





    //print_r($folders) ;die;


    $sql3 = "SELECT * FROM  purchasemeta WHERE purchaseId = " . $_GET['id'];
    $q3 = mysql_query($sql3);

    $arrayListQ = mysql_query("SELECT * FROM `terms` WHERE `id`
        IN (
            SELECT `termId`
            FROM `purchasemeta`
            WHERE `purchaseId` = {$_GET['id']}
        )
        AND `active` = 1
        ORDER BY `name` ASC");

    $arrayList = array();
    $arrayList_temp = array();
    while ($row3 = mysql_fetch_assoc($arrayListQ)) {
        $arrayList[] = $row3;
        $arrayList_temp[] = $row3['id']; 
    }

    $results = mysql_query("SELECT p. * , s.*
        FROM `purchases` p
        INNER JOIN `schools` s ON p.`schoolID` = s.`SchoolId`
        WHERE p.`id` = {$_GET['id']}");

    if (mysql_num_rows($results) > 0) {
        $row = mysql_fetch_assoc($results);
        $payInfo = unserialize($row['payInfo']);
    }

    $master_schoolid = $row['master_school_id']>0?$row['master_school_id']:0;
    $states = array('AL' => "Alabama", 'AK' => "Alaska", 'AZ' => "Arizona", 'AR' => "Arkansas", 'CA' => "California", 'CO' => "Colorado", 'CT' => "Connecticut"
        , 'DE' => "Delaware", 'DC' => "District Of Columbia", 'FL' => "Florida", 'GA' => "Georgia", 'HI' => "Hawaii", 'ID' => "Idaho", 'IL' => "Illinois"
        , 'IN' => "Indiana", 'IA' => "Iowa", 'KS' => "Kansas", 'KY' => "Kentucky", 'LA' => "Louisiana", 'ME' => "Maine", 'MD' => "Maryland"
        , 'MA' => "Massachusetts", 'MI' => "Michigan", 'MN' => "Minnesota", 'MS' => "Mississippi", 'MO' => "Missouri", 'MT' => "Montana"
        , 'NE' => "Nebraska", 'NV' => "Nevada", 'NH' => "New Hampshire", 'NJ' => "New Jersey", 'NM' => "New Mexico", 'NY' => "New York"
        , 'NC' => "North Carolina", 'ND' => "North Dakota", 'OH' => "Ohio", 'OK' => "Oklahoma", 'OR' => "Oregon", 'PA' => "Pennsylvania"
        , 'RI' => "Rhode Island", 'SC' => "South Carolina", 'SD' => "South Dakota", 'TN' => "Tennessee", 'TX' => "Texas", 'UT' => "Utah"
        , 'VT' => "Vermont", 'VA' => "Virginia", 'WA' => "Washington", 'WV' => "West Virginia", 'WI' => "Wisconsin", 'WY' => "Wyoming");
    $district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css" />
<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<div id="main" class="clear fullwidth">
        <div class="container">
            <div class="row">
                <div id="sidebar" class="col-md-4">
    <?php include("sidebar.php"); ?>
                </div>		<!-- /#sidebar -->
                <div id="content" class="col-md-8">
                    <div class="content_wrap">
                        <div class="ct_heading clear">
    <?php if (!isset($_GET['ac'])) { ?>
                                <h3>Add New Principal Account</h3>
                            <?php } else { ?>
                                <h3>Edit Principal Account</h3>
                            <?php } ?>
                        </div>		<!-- /.ct_heading -->
                        <div class="ct_display clear">
    <?php
    if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
        foreach ($_SESSION['errors'] as $error)
            echo '<p class="text-danger">' . $error . '</p>';
        unset($_SESSION['errors']);
    }
    ?>
                            <form id="checkout" method="POST" action="" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="state">State</label>
                                    <select name="state" id="state" class="form-control required">
                                        <option value="TX">Texas</option>
                                    </select>
                                    <small class="error text-danger">Please select your state!</small>
                                </div>
                                
                                
                                
                        <!--  xxx-->
                                <div class="checkbox">
                                    <label for="exampleSelectMany" 
                                        style="padding-left: 0;"><strong>Which Product(s) Do You Wish to Purchase? They cost $325 for each selection</strong></label>
                                </div>
    <?php

    $folders= mysql_query("
        SELECT *
        FROM `terms`
        WHERE `taxonomy` = 'category'
        AND `active` =1
            AND `parent` IN (1,2617,2618)
        AND `id` NOT IN ( 2634, 2635,2619,2620 )
        ORDER BY parent ASC
    "); 


    //////////////////////////////
    if ($folders) {
        
        $run = 0;
        while ($rowData = mysql_fetch_assoc($folders)) {
            $name = $rowData['name'].'&nbsp;STAAR Smart Prep';
            if($rowData['parent']==1)
            $name = $rowData['name'] . ' STAAR Question Database & Student Gap Analysis';
            // echo $arrayList[$run]['id'];
            $own = $rowData['parent'];
            if (in_array($rowData['id'], $arrayList_temp)) {
                echo '<div class="checkbox">
                                                <label><input type="checkbox" name="subject[' . $rowData['id'] . ']" class="subject" value="' . $rowData['name'] . '" checked> ' . $name . '</label>
                                            </div>';
            } else {

                echo '<div class="checkbox">
                                                <label><input type="checkbox" name="subject[' . $rowData['id'] . ']" class="subject" value="' . $rowData['name'] . '"> ' . $name. '</label>
                                            </div>';
            }
            $run++;
        }
    } else {
        echo '<p>No subject supported!</p>';
    }
    ?>


        <br />
    <div class="checkbox">
                                    <label for="exampleSelectMany"
                                    style="padding-left: 0;"><strong>Data Dash Permission</strong></label>
                                </div>

                            
    <?php
    $school_id = $puData['schoolID'];
    $grade_ids = mysql_query("
                                                    SELECT grade_level_id
                                                    FROM `school_permissions`
                                                    WHERE `school_id` = " . $school_id
    );
    //echo $school_id;
    //$grade_id_list = array();
    while ($rowdata = mysql_fetch_assoc($grade_ids)) {
        //print_r($rowdata['grade_level_id']);
        $grade_id_list[] = $rowdata['grade_level_id'];
    }
    // mysql_query


    /*$folders= mysql_query("
        SELECT *
        FROM `terms`
        WHERE `taxonomy` = 'category'
        AND `active` =1
            AND `parent` IN (1,2617,2618)
        AND `id` NOT IN ( 2634, 2635,2619,2620 )
        ORDER BY parent ASC
    "); */
    
    $folders= mysql_query("
        SELECT *
        FROM `terms`
        WHERE `taxonomy` = 'category'
        AND `active` = 1
        AND `parent` IN (1,2617,2618)
        ORDER BY parent, name ASC
    "); 

    //echo  'test for data dashX===='; die;


    if ($folders) {
        $run = 0;
        while ($rowData = mysql_fetch_assoc($folders)) {
            //print_r($rowData['id ']);
            $name = $rowData['name'] . ' Data Dash';
            // echo $arrayList[$run]['id'];
            if (in_array($rowData['id'], $grade_id_list)) {
                echo '<div class="checkbox">
                                                <label><input type="checkbox" name="permission[' . $rowData['id'] . ']" class="subject" value="' . $rowData['name'] . '" checked> ' . $name . '</label>
                                            </div>';
            } else {

                echo '<div class="checkbox">
                                                <label><input type="checkbox" name="permission[' . $rowData['id'] . ']" class="subject" value="' . $rowData['name'] . '"> ' . $name . '</label>
                                            </div>';
            }
            $run++;
        }
    } else {
        echo '<p>No subject supported!</p>';
    }

    ?>
                                
                        
                                    <label for="lesson_name">Tutorial Sessions Access:</label><br />  
                                
                                    <div class="checkbox">
                                                <label style="font-size: 17px;">
                                                        <input name="sessions_access" <?=($school_allowed_ses=='yes')?'checked':''?> 
                                                                class="subject" value=""
                                                                type="checkbox">(<span class="text-success">Yes/No</span>, grant access to Homework Help sessions)</label>
                                            </div>
                                            <br/>

                                    <?php //=$is_intervention_allowed.'@@'?>
                                    <div class="checkbox">



                                    <label style="font-size: 17px;">
            <input name="intervention_access"
                <?=($is_intervention_allowed=='yes')?'checked':''?> 
                                                                class="subject" 
                                                                type="checkbox">(<span class="text-success">Yes/No</span>, grant access to Intervention sessions)</label>
                                            
                                            </div>

                                        <!--  Telpas Pro options   -->
                                        <div class="checkbox">



                                    <label style="font-size: 17px;">
                    <?php 
                    $telpass_access=($school_telpas_rows>=1)?'checked':NULL;
                    
                    ?>                     
            <input  <?=$telpass_access;?>  
                name="telpas_pro_access"
                class="subject" type="checkbox">
                (<span class="text-danger">Yes/No</span>, grant access to TELPAS Pro )</label>
                                            
                                            </div>




                                    
                                
                                <br/>
                                <p>
                                    <label for="lesson_name">Choose District:</label><br />
                                    <select name="district" id="district">
                                    <?php while ($district = mysql_fetch_assoc($district_qry)) { ?>
                                                                            <option <?php if ($row['district_id'] == $district['id']) { ?> selected="selected" <?php } ?> value="<?php print $district['id']; ?>"><?php print $district['district_name']; ?></option>

                                    <?php } ?>
                                    </select>
                                    
                                </p>
                                <div id="district_schools">
                                    
                                    <label for="lesson_name">Choose Schools:</label>
                                    <div id="district_school">
                                        Select District to choose schools.
                                    </div>
                                
                                </div>
                                <div class="form-group">
                                    <label for="school">School name</label>
                                    <input type="text" name="school" id="school" value="<?php echo isset($row['SchoolName']) ? $row['SchoolName'] : '' ?>" class="form-control required" />
                                    <small class="error text-danger">This field is required!</small>
                                </div>
                                <div class="form-group">
                                    <label for="school">School Logo</label>
                                    <input type="file" name="schoollogo" />	 
                                    <input type="submit" name="upload" value="Upload" > <br />
    <?php
    $school_logo_res = mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE `SchoolId` = {$puData['schoolID']}"));
    //print_r($school_logo_res);
    if (trim($school_logo_res['schoollogo']) != '') {
        $cwd = getcwd();
        $uploads_dir = $cwd . '/uploads/schoollogo';
        //echo $uploads_dir;
        ?>
                                        <a href="<?php print $base_url . 'uploads/schoollogo/' . $school_logo_res['schoollogo'] ?>" target="_blank">
                                            <img src="<?php print $base_url . 'uploads/schoollogo/' . $school_logo_res['schoollogo'] ?>" height="50" width="50" />
                                        </a>
    <?php }
    ?>

                                    <small class="error text-danger">This field is required!</small>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" name="email" id="email" value="<?php echo isset($row['email']) ? $row['email'] : '' ?>" class="form-control required" placeholder="" />
                                    <p><small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small></p>
                                    <small class="error text-danger">Please enter valid email address!</small>
                                </div>
                                <div class="form-group">
                                    <label for="confirmmail">Confirm email address</label>
                                    <input type="email" id="confirmmail" value="<?php echo isset($row['email']) ? $row['email'] : '' ?>" class="form-control required" placeholder="" />
                                    <small class="error text-danger">Your email does not match!</small>
                                </div>
    <?php if (!isset($_GET['ac'])) { ?>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" class="form-control required" placeholder="">
                                        <small class="error text-danger">Please enter your password!</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm">Confirm Password</label>
                                        <input type="password" id="confirm" class="form-control required" placeholder="">
                                        <small class="error text-danger">Your password does not match!</small>
                                    </div>
    <?php } else { ?>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="">
                                        <small class="error text-danger">Please enter your password!</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm">Confirm Password</label>
                                        <input type="password" id="confirm" class="form-control" placeholder="">
                                        <small class="error text-danger">Your password does not match!</small>
                                    </div>

    <?php } ?>
                                <br />

                                <div id="checkoutForm" class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h5>Payment Information</h5>
                                        <table class="table table-border-none">
                                            <tr>
                                                <td><label for="firstname">First Name</label></td>
                                                <td>
                                                    <input type="text" name="firstname" id="firstname" value="<?php echo isset($payInfo['firstname']) ? $payInfo['firstname'] : '' ?>" class="form-control" />
                                                    <small class="error text-danger">This field is required!</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="lastname">Last Name</label></td>
                                                <td>
                                                    <input type="text" name="lastname" id="lastname" value="<?php echo isset($payInfo['lastname']) ? $payInfo['lastname'] : '' ?>" class="form-control" />
                                                    <small class="error text-danger">This field is required!</small>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h5>Billing Information</h5>
                                        <table class="table table-border-none">
                                            <tr>
                                                <td><label for="address">Address</label></td>
                                                <td>
                                                    <input type="text" name="address" id="address" value="<?php echo isset($payInfo['address']) ? $payInfo['address'] : '' ?>" class="form-control" />
                                                    <small class="error text-danger">This field is required!</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="phone">Phone</label></td>
                                                <td>
                                                    <input type="text" name="phone" id="phone" value="<?php echo isset($payInfo['phone']) ? $payInfo['phone'] : '' ?>" class="form-control" />
                                                    <small class="error text-danger">This field is required!</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="city">City</label></td>
                                                <td>
                                                    <input type="text" name="city" id="city" value="<?php echo isset($payInfo['city']) ? $payInfo['city'] : '' ?>" class="form-control" />
                                                    <small class="error text-danger">This field is required!</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="zipcode">Zip/Postal Code</label></td>
                                                <td>
                                                    <input type="text" name="zipcode" id="zipcode" value="<?php echo isset($payInfo['zipcode']) ? $payInfo['zipcode'] : '' ?>" class="form-control" />
                                                    <small class="error text-danger">This field is required!</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="state">State</label></td>
                                                <td>
                                                    <select id="state" name="billing_state" class="form-control">
                                                        <option value=""></option>
    <?php
    foreach ($states as $key => $state) {
        if ($key == $payInfo['state']) {
            echo "<option value='$key' selected>$state</option>";
        } else {
            echo "<option value='$key'>$state</option>";
        }
    }
    ?>
                                                    </select>
                                                    <small class="error text-danger">This field is required!</small>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <button type="submit" class="btn btn-primary" style="cursor: pointer; margin-top: 20px;">SAVE</button>
                                    </div>
                                </div>

                                <input type="hidden" name="type" value="Paper-based assessments only" />
                            </form>

                            <div class="clearnone">&nbsp;</div>
                        </div>		<!-- /.ct_display -->
                    </div>		<!-- /.content_wrap -->
                </div>		<!-- /#content -->
                <div class="clearnone">&nbsp;</div>
            </div>
        </div>
</div>		<!-- /#main -->

<script type="text/javascript">
    <?php
    $curr_url=$_SERVER['REQUEST_URI'];
    if ($error) { ?>
            alert('<?php print $error; ?>'); location.href='<?=$curr_url;?>';
    <?php } ?>
        $(document).ready(function () {
            $('#district').chosen();
            
            $('#district').change(function () {
                district = $(this).val();
                $('#district_school').html('Loading ...');
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {district: district, action: 'get_schools', school_id : '<?php print $master_schoolid; ?>'},
                    success: function (response) {
                        $('#district_school').html(response);
                        $('#d_school').chosen();
                    },
                    async: false
                });
            });
            $('#district').change();
            $('.error').hide();
            $('#checkout').on('submit', function (event) {
                event.preventDefault();

                var checkout = $(this);
                var validate = true;
                var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                /* Validate checkbox */
                var subject = false;
                $(checkout).find('.subject').each(function () {
                    if ($(this).is(':checked')) {
                        subject = true;
                        return false;
                    }
                });
                if (!subject) {
                    alert('Please choose the product(s) you wish to purchase');
                    $('.subject').eq(0).focus();
                    return false;
                }

                $(checkout).find('.required').each(function () {
                    if ($.trim($(this).val()) == '' ||
                            ($(this).attr('type') == 'email' && !filter.test($(this).val())) ||
                            ($(this).attr('id') == 'confirmmail' && $(this).val() != $('#email').val()) ||
                            ($(this).attr('id') == 'confirm' && $(this).val() != $('#password').val())
                            ) {
                        validate = false;
                        $(this).focus();
                        $(this).siblings('.error').show();
                        return false;
                    } else {
                        $(this).siblings('.error').hide();
                    }
                });
                if (!validate)
                    return false;

                /* Validate existed email */
                var email = $.trim($('#email').val());
                $.ajax({
                    type: "POST",
                    url: "../orders/ajax-check-email.php",
                    data: {email: email},
                    success: function (response) {
                        if (response == 0) {
                            if ($('#cardnumber').length > 0) {
                                validate = false;
                                alert('Your email is already existed!');
                                $('#email').focus();
                            }
                        }
                    },
                    async: false
                });
                if (!validate)
                    return false;

                /* Validate Card Number */
                if ($('#cardnumber').length > 0) {
                    var cardnumber = $('#cardnumber').val();
                    $.ajax({
                        type: "POST",
                        url: "../orders/validate.php",
                        data: {cardnumber: cardnumber},
                        success: function (response) {
                            if (!response) {
                                validate = false;
                                alert('Your card number is invalid!');
                                $('#cardnumber').focus();
                            }
                        },
                        async: false
                    });
                    if (!validate)
                        return false;

                }

                /* Validate Card Code */
                if ($('#cardnumber').length > 0) {
                    var cardcode = $('#cardcode').val();
                    $.ajax({
                        type: "POST",
                        url: "../orders/validate.php",
                        data: {cardnumber: cardnumber, cardcode: cardcode},
                        success: function (response) {
                            if (!response) {
                                validate = false;
                                alert('Your card code is invalid!');
                                $('#cardcode').focus();
                            }
                        },
                        async: false
                    });
                }

                $(checkout).unbind('submit').submit();
            });
        });
</script>

<?php include("footer.php"); ?>