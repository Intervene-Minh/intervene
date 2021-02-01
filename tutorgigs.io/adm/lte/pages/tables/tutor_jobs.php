<?php  
	require("config.php");
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
        <link rel="stylesheet" type="text/css" href="datatables/media/css/jquery.dataTables.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
        <script src="categories.js" type="text/javascript"></script>
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
                                    <h3 class="box-title">Jobs</h3>
                                </div><!-- /.box-header --> 
                                <div class="navbar-form"> 
                                    <div class="btn-group">                                    	
                                        <a class="btn btn-default navbar-btn" href="add_job.php">
                                            <span class="glyphicon glyphicon-plus-sign" ></span> Add New Job</a>                                       
                                    </div>                                    
                                </div>
                                <div class="alert" id="msgBox" style="display:none">
                                		                                        
                                </div>                              
                                <div class="box-body table-responsive">                                
                                    <table id="jobTable" class="table table-responsive table-condensed table-hover">
                                    	<thead>                                                    
                                             <tr>                                                
                                                <th>Posted By</th>
                                                <th>For Subjects</th>
                                                <th style="text-align: center;">Tutor Responses</th>
                                                <th>Posted On</th>
                                                <th>Actions</th>                                                          
                                            </tr>                                                    
                                        </thead>   
                                        <tbody>
                                            <?php
                                            if($job_count > 0) {
                                                while ( $job = mysqli_fetch_assoc($job_list) ) {
                                            ?>
                                            <tr>
                                                <td><?php echo $job['FirstName']." ".$job['LastName'];?></td>
                                                <td>
                                                    <?php 
                                                        if($job['SubjectID'] == 0) {
                                                            echo $job['Other'];
                                                        }
                                                        else {
                                                            echo $job['subject_name'];
                                                        }
                                                    ?>
                                                </td>
                                                <td align="center">
                                                    <a href="view_job.php?jobID=<?php echo $job['ID'];?>"><span class="label label-primary"><?php echo $job['tutor_response_count'];?> Responses</span></a>
                                                </td>
                                                <td><?php echo $job['CreatedOn'];?></td>
                                                <td width="20%">
                                                    <a class="btn btn-primary btn-xs" href="update_job.php?jobID=<?php echo $job['ID'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
                                                    <a class="btn btn-danger btn-xs show-confirm-box" data-msg="Are you sure, you want to delete this job?" href="job_util.php?action=delete&jobID=<?php echo $job['ID'];?>"><span class="glyphicon glyphicon-remove"></span> Delete</a>
                                                    <?php
                                                    $_status = $job['IsActive'];
                                                    ?>
                                                    <a 
                                                        class="btn btn-<?php echo $_status == 1? "danger": "success"; ?> btn-xs show-confirm-box" 
                                                        data-msg="Are you sure, you want to <?php echo $_status == 1? "deactivate": "activate"; ?> this job?" 
                                                        href="job_util.php?action=status&jobID=<?php echo $job['ID'];?>"><span class="glyphicon glyphicon-eye-<?php echo $_status == 1? "close": "open"; ?>"></span> <?php echo $_status == 1? "Deactivate": "Activate"; ?></a>
                                                </td>
                                            </tr>
                                            <?php
                                                } 
                                            }
                                            else {
                                            ?>
                                            <tr>
                                                <td colspan="4">No Job Posted</td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
        <script type="text/javascript" language="javascript" src="datatables/media/js/jquery.dataTables.js"></script>
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
        
        
        
		<script type="text/javascript">
            $(document).on("click", ".show-confirm-box", function(e) {
                var _default_msg = "Are you sure?";

                if($(this).attr('data-msg')) {
                    _default_msg = $(this).attr('data-msg');
                }

                var result = confirm(_default_msg);
                if (result == true) {
                    return true;
                } else {
                    return false;
                }
            })

            $(document).ready(function(){
                $('#jobTable').dataTable({
                    "order": [[ 1, "desc" ]],
                    stateSave: true,
                    "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]],
                    "dom": '<"top"lf<"clear">p<"clear">>rt<"bottom"ip<"clear">>'
                    
                });
            })
        </script>
        

    </body>
</html>