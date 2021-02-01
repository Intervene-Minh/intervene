<?php
    /***
    @ Roster Upload 
    @ Intervention 
    **/
    $today = date("Y-m-d H:i:s");

    if (isset($_SESSION['schools_id'])&&$_SESSION['schools_id']>0) {
        $data=mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE SchoolId='".$_SESSION['schools_id']."' "));
        @extract($data);
        $get_school_id=$_SESSION['schools_id'];
    }
    $is_slot_alloted=0;#not
    
    if ($avaiable_slots>0) {
        $is_slot_alloted=1;#CanCreate
    }
    // $tot_ses_used# created by admin
    $tot_ses_used=mysql_num_rows(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE type='homework' AND school_id=".$_SESSION['schools_id']));
    
    /// School allowed Tut. session or not
    $sql="SELECT * FROM user_session_access WHERE role='school' AND user_id=".$get_school_id; //die;
    $get_total=mysql_num_rows(mysql_query($sql));
    // echo $get_total.' ==1 , ie. allowed access'; die;  
    //print_r($get); // die;
    $ses_allowed=($get_total==1)?'yes':'no'; 

    //echo 'Tut. ses allowed='.$ses_allowed;
    //still not allowed, allowed , stoped==>
    $tut_ses_page= array('create-tutor-sessions.php','view-sessions.php','sessions-calendar.php','session-detail.php' );// For a School
    //echo curPageName().'=' ; // Current Page

    if ($ses_allowed=='no'&&in_array(curPageName(), $tut_ses_page)) {
        $error='not allowed';
        header("Location:school.php");exit;      
    }

    /////Upload Roster////////// 
    if (isset($_POST['upload_roster'])) {
        $cwd = getcwd();
        $uploads_dir = $cwd . '/uploads/rosters';  //rosters schoollogo
        
        // if (trim($school_logo_res['schoollogo']) != '') {
            //     unlink($uploads_dir . '/' . $school_logo_res['schoollogo']);
            // }
            
            $tmp_name = $_FILES["csv_upload"]["tmp_name"];
            $name = $_SESSION['schools_id'] . '_' . basename($_FILES["csv_upload"]["name"]);
            if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
                $query=mysql_query("INSERT INTO school_master_rosters (school_id,filename,status,created) VALUES ('$get_school_id','$name','In Space Review','$today') ");  //die;
                $error = 'Roseter added.';
            }
            
            // In Space Review
        } // upload_roster
    
    
?>
    <div class="text-center alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <h3>Instructions to Get Started</h3>
        <button type="button" class="btn btn-primary btn-plan-select" data-toggle="modal" data-target="#myModal">Send an invitation to your teachers</button>
        <br/>
        <br/>
        <h4>Once you've sent invitations to your teachers, you're done! Let us know if you need any help by emailing support at <a href="mailto:learn@p2g.org">learn@p2g.org</a></h4>.
    </div>
    <!-- old instruction to get started headers -->
    <div style="border: 1px solid black; display: none;" class="row text-center">
        <h3>Instructions to Get Started</h3>
        <button type="button" class="btn btn-primary btn-plan-select" data-toggle="modal" data-target="#myModal">Send an invitation to your teachers</button>
        <h4>Once you've sent invitations to your teachers, you're done! Let us know if you need any help by emailing support at learn@p2g.org</h4>
        <hr width="60%"></hr> 
    </div>
       
    <table class="table table-bordered">
        <tr>
            <td rowspan="3" width="200px">
                <form id="schoolLogo" enctype="multipart/form-data" method="post">
                    <?php 
                        if ($school['schoollogo']) { 
                    ?>
                        <a href="<?php print $base_url . 'uploads/+/' . $school['schoollogo'] ?>" target="_blank">
                            <img src="<?php print $base_url . 'uploads/schoollogo/' . $school['schoollogo'] ?>" width="200" />
                        </a>
                    <?php 
                        }
                    ?>
                </form>                
            </td>
            <td>
                <h1 style="line-height: 25px;">
                    <?=$school["SchoolName"] ?><br/>
                    <small><?=$school["district_name"] ?></small>
                </h1>
                <p>
                    # Students: <b> <?=$students_result['count'] ?></b><br/>
                </p>
            </td>
            <td>
                <p><button></button></p>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
        </tr>
    </table>        
    <!-- old table -->
    <table class="table table-bordered">
        <tr>
            <td> 
                <form enctype="multipart/form-data" method="post">
                    <?php 
                        if ($school['schoollogo']) { 
                    ?>
                        <a href="<?php print $base_url . 'uploads/schoollogo/' . $school['schoollogo'] ?>" target="_blank">
                            <img src="<?php print $base_url . 'uploads/schoollogo/' . $school['schoollogo'] ?>" height="50" width="50" />
                        </a>
                    <?php 
                        }
                    ?>
                    <input type="file" name="schoollogo" />
                    <input type="submit" name="upload_logo" value="upload" class="btn btn-primary" />
                    <br/>
                    <br/>
                    <hr/>
                    <input type="file" name="csv_upload"   style="margin: 10px" />
                    <input type="submit" name="upload_roster" value="Upload Roster" class="btn btn-danger" />
                    <br/>  
                </form>          
                <p class="text-right">
                    <br/>
                    <a title="My Assessments"
                        href="school_assessment_list.php" class="btn btn-success btn-sm">My Assessments</a>
                    <a title=""
                        href="select_assessment.php" class="btn btn-success btn-sm">Data Dash Report</a>
                </p>
            </td>
            <td>
                <label>School:</label> <?php echo $school['SchoolName']; ?> <br/>
                <label>District:</label> <?php echo $school['district_name']; ?> 
                <br>
                <a title="Upload a Result CSV" style=" margin-top:115px; " 
                    href="school_result_upload.php" class="btn btn-danger btn-sm">Upload a Result CSV</a>
            </td>
            <td style="position: relative;">
                <label>Total Number of Students:</label> <?php echo $students_result['count']?$students_result['count']:'0'; ?> 
                <br>
                <a title="Attendance Report" style="bottom: 12px; position: absolute;" 
                    href="school_attendance.php" class="btn btn-success btn-sm">Attendance</a>                    
            </td>
            <td><label>Email:</label> <?php echo $school['SchoolMail']; ?>
            </td>
            <td><label>Address:</label> <?php echo $school['SchoolAddress']; ?>
            </td>
            <td>
                <label>Registered Date:</label> 
                <?php echo date('M d, Y', strtotime($school['created_at'])); ?>
            </td>
            <td>
                <label>Expiry Date:</label> <?php echo date('M d, Y', strtotime('+1 year', strtotime($school['created_at']))); ?>
            </td>
            <td>
                <form name="logout" method="POST" action="">
                    <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                </form>
            </td>
        </tr>
        <?php 
            // $is_slot_alloted=0;
            if ($ses_allowed=='yes') { 
        ?>
                <tr>
                    <td colspan="8" title="Available"> 
                        <p>
                            <strong class="text-danger" title="Remaining Tutors Sessions-<?=$avaiable_slots?>">
                                Remaining Sessions-<?=$avaiable_slots?>
                            </strong>
                            <br/>
                            <strong title="Used Tutors Sessions-<?=$tot_ses_used?>" class="text-success">
                                Used Sessions-<?=$tot_ses_used?>
                            </strong>
                        </p> 
                        <a href="school.php" class="btn btn-danger btn-sm">Home</a> |            
                            &nbsp;   &nbsp; &nbsp;   &nbsp;
                        <a href="create-tutor-sessions.php"  class="btn btn-primary btn-sm">+Create a Tutor Session</a>            
                        &nbsp;   &nbsp;
                        <a title="View Created Tutor Session"
                            href="view-sessions.php" class="btn btn-success btn-sm">View Tutor Sessions</a> 
                            &nbsp;   &nbsp;
                        <a title="View Sessions calendar"
                            href="sessions-calendar.php" class="btn btn-success btn-sm">Sessions calendar</a> 
                            &nbsp;   &nbsp;
                        <a title="Upload Result"
                            href="school_result_upload.php" class="btn btn-success btn-sm">Upload Result</a>             
                    </td>            
                </tr>
        <?php  
            }
        ?>      
    </table>