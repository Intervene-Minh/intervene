<?php  
	require("config.php");
	if(empty($_SESSION['user']) || (int)$_SESSION['user']['Security'] < 90) {
		header("Location: /adm/index.php");
		die("Redirecting to index.php"); 
	}
	
	// db connection
	include 'dbtutor.ini';
	
	// Connect to tutor database
	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or 
		die('Connection Error: ' . mysqli_connect_error() . ' User:'. $tUser);
	mysqli_select_db($connection, "$tDB");
	
        $_job_id = 0;

        if(isset($_GET['jobID'])) {
            $_job_id = trim($_GET['jobID']);
        }

        if(isset($_POST['submit'])) {
            $_sql = "UPDATE jobPosts SET
                        StudentID = '".$_POST['student']."',
                        SubjectID = '".$_POST['subject']."',
                        Other = '".$_POST['other']."',
                        Description = '".$_POST['description']."',
                        IsActive = '".$_POST['status']."',
			HourlyBudget = '".$_POST['hourly_rate']."'
                    WHERE
                        ID = ".$_job_id;
            mysqli_query($connection, $_sql);

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

                WHERE 
                    jp.ID = ".$_job_id."

                GROUP BY
                    jp.ID";

        $job_list = mysqli_query($connection, $_sql);
        $job_count = $job_list->num_rows;
        $_job = array();

        if($job_count > 0) {
            $_job = mysqli_fetch_assoc($job_list);
        }
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
                                    <h3 class="box-title">Update Jobs</h3>
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
                                                                    $_selected = "";
                                                                    if($_job['StudentID'] == $student['ID']) {
                                                                        $_selected = "selected = 'selected'";
                                                                    }
                                                            ?>
                                                            <option <?php echo $_selected;?> value="<?php echo $student['ID'];?>"><?php echo $student['FirstName']." ".$student['LastName'];?></option>
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
                                                            <option <?php echo $_job['SubjectID'] == 0? "selected='selected'": ""; ?> value="0">Other</option>
                                                            <?php
                                                            if($subject_count > 0) {
                                                                while ( $subject = mysqli_fetch_assoc($subjects) ) {
                                                                    $_selected = "";
                                                                    if($_job['SubjectID'] == $subject['ID']) {
                                                                        $_selected = "selected = 'selected'";
                                                                    }
                                                            ?>
                                                            <option <?php echo $_selected;?> value="<?php echo $subject['ID'];?>"><?php echo $subject['Name'];?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <br>
                                                        <input id="other_subject" style="<?php echo $_job['SubjectID'] != 0? "": "display: block"; ?>" name="other" type="text" placeholder="Other Subject" class="form-control" value="<?php echo $_job['Other'];?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Status</label>
                                                    <div class="controls">
                                                    <?php
                                                    $_checked = 'checked="checked"';
                                                    ?>
                                                        <label class="radio-inline">
                                                            <input <?php echo $_job['IsActive'] == "1"? $_checked: ""; ?> value="1" type="radio" name="status">Active
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input <?php echo $_job['IsActive'] == "0"? $_checked: ""; ?> value="0" type="radio" name="status">Inactive
                                                        </label>
                                                    </div>                      
                                                </div>

						<div class="form-group">
                                                    <label class="control-label">Hourly Budget</label>
                                                    <div class="controls">
                                                        <input id="hourly_rate" name="hourly_rate" type="text" placeholder="$" value="<?php echo $_job['HourlyBudget']?>" class="form-control">
                                                    </div>                      
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Description</label>
                                                    <div class="controls">
                                                        <textarea required class="form-control" rows="5" name="description"><?php echo $_job['Description'];?></textarea>
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
