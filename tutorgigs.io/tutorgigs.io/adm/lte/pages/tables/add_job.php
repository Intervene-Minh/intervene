<?php  
	require("config.php");
    include_once("../../../classes/class.Notification.php");
    include_once("../../../classes/class.Messages.php");
    require_once('../../../classes/class.Tutor.php');
    require_once("../../../classes/class.Student.php");
    require_once("../../../classes/Subject.class.php");

    if(empty($_SESSION['user']) || (int)$_SESSION['user']['Security'] < 90) 
    {
        header("Location: /adm/index.php");
        die("Redirecting to index.php"); 
    }
	
		// db connection
		include 'dbtutor.ini';
		
		// Connect to tutor database
		$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or 
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $tUser);
		mysqli_select_db($connection, "$tDB");
		

        if(isset($_POST['submit'])) {
            $_sql = "INSERT INTO jobPosts
                    (
                        StudentID,
                        SubjectID,
                        Other,
                        Description,
                        IsAvailable,
                        IsActive,
                        CreatedOn,
			HourlyBudget
                    )
                    VALUES (
                        '".$_POST['student']."',
                        '".$_POST['subject']."',
                        '".$_POST['other']."',
                        '".$_POST['description']."',
                        '1',
                        '".$_POST['status']."',
                        DATE_ADD(NOW(),INTERVAL 1 HOUR),
						'".$_POST['hourly_rate']."'
                    )";
            mysqli_query($connection, $_sql);
            $id=mysqli_insert_id($connection);

            $_tutor_obj = new Tutor();
            $_tutors = $_tutor_obj->LoadAll();

            $subject = new Subject();
            $subject->Load($_POST['subject']);

            if(mysqli_num_rows($_tutors) !== 0) {
                $email_data = array();
                while ($_tutor = mysqli_fetch_assoc($_tutors)) {
                    $body=file_get_contents(dirname(__FILE__)."/email_templates/new_job.html");
                    
                    // build mail content
                    $_msg_content = array();
                    $_msg_content['subject'] = "New Tutoring Opportunity";
                    $_msg_content['inner_subject'] = "You have unread message about the job for subject <b>".$subject->Name."</b>.";
                    $_msg_content['message_body'] = "A Student has posted the following tutoring request <br><strong>".$_POST['description']."</strong><br><br><br><br>
                        <span style='color:#ffffff;font-weight:bold;color: #ffffff;font-weight: 300;padding: 10px 18px;border-collapse: collapse;background: #5bbc2e;border-top-left-radius: 2px;border-bottom-left-radius: 2px;border-top-right-radius: 2px;border-bottom-right-radius: 2px;font-size: 14px;font-family: Helvetica,arial,sans-serif;text-align: center;'><a href='https://".$_SERVER['HTTP_HOST']."/jobapplication.php?jobID=".$id."' target='_blank' style='color:#ffffff;text-align:center;text-decoration:none'>Apply for Tutor job</a></span>";
                    $_msg_content['message_receiver'] = $_tutor['FirstName'];
                    $_msg_content['profile_image'] = 'https://'.$_SERVER['HTTP_HOST']."/img/P2G_logo.jpg";

                    // replace mail content from template
                    foreach((array) $_msg_content as $key=>$val){
                        $body=str_replace("{".$key."}", $val, $body);
                    }
                    
                    $notify = new Notification();
                    $notification_body = $_msg_content['message_body'];
                    $notification_body = addslashes(strip_tags($notification_body));

                    $notify->setNotificationType("NEW_JOB");
                    $notify->setNotificationHeader($_msg_content['subject']);
                    $notify->setNotificationBody($notification_body);
                    $notify->setNotificationFrom("A");
                    $notify->setNotificationTo("T");
                    $notify->setNotificationFromId(0);
                    $notify->setNotificationToId($_tutor['ID']);
                    $notify->add();

                    $Message = new Messages();
                    $Message->setSenderID(0);
                    $Message->setRecipientID($_tutor['ID']);
                    $Message->setSubject($_msg_content['subject']);
                    $Message->setBody($notification_body);
                    $Message->setSendType("A");
                    $Message->setRecType("T");
                    $Message->setMailBoxID($_tutor['ID']);
                    $Message->insert();


                    // collect all mail data
                    $email_data[] = array(
                        "subject" => addslashes($_msg_content['subject']),
                        "email" => $_tutor['Email'],
                        "body" => base64_encode($body)
                    );
                }

                // store all email data in database 
                if(!empty($email_data)) {
                    $columns = "subject, email, body";

                    $_values = "";
                    foreach($email_data as $data) {
                        $_values .= "('".implode("', '", $data)."'),";
                    }
                    $_values = trim($_values, ",");

                    $sql = "INSERT INTO `queuedEmails` ($columns) VALUES ".$_values;
                    mysqli_query($connection, $sql);
                }

            }

            header('Location: tutor_jobs.php');
        }
		$_sql = "SELECT * FROM students";
        $students = mysqli_query($connection, $_sql);
        $student_count = $students->num_rows;
        
        $_sql = "SELECT * FROM subjects";
        $subjects = mysqli_query($connection, $_sql);
        $subject_count = $subjects->num_rows;

        $_sql = "SELECT 
                    jp.*,
                    st.FirstName,
                    st.LastName,
                    st.Email,
                    sub.Name AS subject_name,
                    count(ja.ID) AS tutor_response_count
                FROM 
                    jobPosts jp
                LEFT JOIN
                    students st
                ON 
                    st.ID = jp.StudentID
                LEFT JOIN
                    subjects sub
                ON 
                    sub.ID = jp.SubjectID
                LEFT JOIN
                    jobApplications ja
                ON
                    ja.JobPostID = jp.ID

                GROUP BY
                    jp.ID";
        $job_list = mysqli_query($connection, $_sql);
        $job_count = $job_list->num_rows;
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Job Posts | P2G Admin</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <link href="../../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../../css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
        <script src="categories.js" type="text/javascript"></script>
        <style type="text/css">
            #other_subject {
                display: none;
            }
        </style>
    </head>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="../../index.php" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img src="/img/logo80x120.png" width="40" height="60"> Admin
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                   
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    
                                    <div class="pull-right">
                                        <a href="/adm/logout.php" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">                
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        
                    </div>
                    
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <? include("menu.htm"); ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Job Post
                        <small>management</small>
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Add Jobs</h3>
                                </div>                            
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-8">   
                                            <form id="cForm" name="cForm" role="form" action="" method="post">

                                                <div class="form-group">
                                                    <label class="control-label" for="cName">Student</label>
                                                    <div class="controls">
                                                        <select required class="form-control" name="student">
                                                            <option value="">Select Student</option>
                                                            <?php
                                                            if($student_count > 0) {
                                                                while ( $student = mysqli_fetch_assoc($students) ) {
                                                            ?>
                                                            <option value="<?php echo $student['ID'];?>"><?php echo $student['FirstName']." ".$student['LastName'];?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label" for="cName">Subject</label>
                                                    <div class="controls">
                                                        <select required id="subject" class="form-control" name="subject">
                                                            <option value="">Select Subject</option>
                                                            <option value="0">Other</option>
                                                            <?php
                                                            if($subject_count > 0) {
                                                                while ( $subject = mysqli_fetch_assoc($subjects) ) {
                                                            ?>
                                                            <option value="<?php echo $subject['ID'];?>"><?php echo $subject['Name'];?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <br>
                                                        <input id="other_subject" name="other" type="text" placeholder="Other Subject" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Status</label>
                                                    <div class="controls">
                                                        <label class="radio-inline"><input checked="checked" value="1" type="radio" name="status">Active</label>
                                                        <label class="radio-inline"><input value="0" type="radio" name="status">Inactive</label>
                                                    </div>                      
                                                </div>
												
						<div class="form-group">
                                                    <label class="control-label">Hourly Budget</label>
                                                    <div class="controls">
                                                        <input id="hourly_rate" name="hourly_rate" type="text" placeholder="$" class="form-control">
                                                    </div>                      
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label class="control-label">Description</label>
                                                    <div class="controls">
                                                        <textarea required class="form-control" rows="5" name="description"></textarea>
                                                    </div>                      
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="controls pull-right">
                                                        <a class="btn btn-default" href="tutor_jobs.php">Cancel</a>
                                                        <button value="submit" name="submit" type="submit" class="btn btn-primary" href="">Submit</button>
                                                    </div>                      
                                                </div>
                                            </form>
                                        </div>
                                    </div>                       
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <!-- jQuery 2.0.2 -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT 
        <script src="../../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>-->
        <!-- AdminLTE App -->
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
        
        
        
		<script type="text/javascript">
            $("#subject").change(function(e){
                _selected_subject = $('#subject').find(":selected").val();

                if(_selected_subject == 0) {
                    $("#other_subject").show();
                }
                else {
                    $("#other_subject").val("");
                    $("#other_subject").hide();
                }
            })
        </script>
        

    </body>
</html>
