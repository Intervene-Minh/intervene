<?php 
/***
@ Tutorgigs QUIZ Dashboard. 
**/
//echo '=====';
	require("../config.php");
   // print_r($_SESSION); die;
	if(empty($_SESSION['user']) )//|| (int)$_SESSION['user']['Security'] < 90)
	{
		/*
		print_r($_SESSION);
		print "In here\n";
		exit(0);
        header("Location: /p2g.org/adm/index.php");
        die("Redirecting to index.php");
		*/
		header("Location:adm/index.php"); exit;//  /p2g.org/adm/index.php
		
	}

	// Connect to tutor database
	$connection = mysqli_connect($host, $username, $password, $dbname) or
		die('Connection Error: ' . mysqli_connect_error());
	mysqli_select_db($connection, "$dbname");

	$pathToClasses = "../classes/";
              
	require_once( $pathToClasses."class.Tutor.php" );
	require_once( $pathToClasses."class.TutorProfile.php" );
	require_once( $pathToClasses."class.TutorPaymentInfo.php" );
	require_once( $pathToClasses."class.TutorRating.php" );
	require_once( $pathToClasses."class.TutorSession.php" );
	require_once( $pathToClasses."class.TutorSessionFeedback.php" );
	require_once( $pathToClasses."class.TutorSubjects.php" );
	require_once( $pathToClasses."class.TutorTests.php" );

	require_once( $pathToClasses."class.Student.php" );
	require_once( $pathToClasses."class.StudentProfile.php" );
	require_once( $pathToClasses."class.StudentPaymentInfo.php" );

	require_once( $pathToClasses."class.Messages.php" );
        
	require_once( $pathToClasses."class.P2G.php" );
        
	require_once( $pathToClasses."class.LogSite.php" );
       
	require_once( $pathToClasses."SiteRules.class.php" );
  
	require_once( $pathToClasses."class.PaymentRequest.php" );
	require_once( $pathToClasses."class.JobPost.php" );

	require_once( $pathToClasses."Subject.class.php" );
	require_once( $pathToClasses."Category.class.php" );

	// Grab all Classes
	/*
	foreach (glob('../../site/classes/class.*.php') as $classFile) {
	    try { require_once( $classFile ); }
		catch (Exception $e) { continue; }
	}
	*/

	$tutorSession = new TutorSession();
	$allSessions = $tutorSession->adminSelectAllSessions();
	$sessionsCount = mysqli_num_rows($allSessions);

	$student = new Student();
	$allStudents = $student->adminSelectAllStudents();
	$studentCount = mysqli_num_rows($allStudents);

	// Get the profile ID
	//$tutorID = $_SESSION['user']['tutorID'];

	// Get Total Tutors
	$query = "SELECT * FROM tutors";
	$result = mysqli_query($connection, $query);
	$totalTutors = mysqli_num_rows($result);

	// Get Total Students
	$query = "SELECT * FROM studentUsers";
	$result = mysqli_query($connection, $query);
	$totalStudents = mysqli_num_rows($result);

	// Get Total Representatives
	$query = "SELECT * FROM representatives";
	$result = mysqli_query($connection, $query);
	$totalReps = mysqli_num_rows($result);


	// -- Build Data Array for Bar Chart --
	$dataArray = '[';
	$count = 1;
	$repResult = mysqli_query($connection, "SELECT * FROM representatives WHERE IsActive=1");
	while ($repRow = mysqli_fetch_array($repResult)) {
		 $tResult =  mysqli_query($connection, "SELECT * FROM tutors WHERE RepID=".$repRow['ID']);
		 $tResultA = mysqli_query($connection, "SELECT * FROM tutors WHERE RepID='".$repRow['ID']."' AND HasVerifiedEmail=1") or die (mysqli_error($connection));

		 $dataArray .="{y: '";
		 $dataArray .=$repRow['FirstName'].' '.$repRow['LastName'];
		 $dataArray .="', ";
		 $dataArray .='a: ';
		 $dataArray .=mysqli_num_rows($tResult);
		 $dataArray .=', ';
		 $dataArray .='b: ';
		 $dataArray .=mysqli_num_rows($tResultA);
		 $dataArray .='}';
		 if ($count < mysqli_num_rows($repResult))
			$dataArray .=',';
	}
	// Remove last comma
	//substr($dataArray, 0, -1);

	$dataArray .= ']';



?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tutorgigs|| Admin | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap
        <link href="css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" /> -->
        <!-- fullCalendar
        <link href="css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />-->
        <!-- Daterange picker
        <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />-->
        <!-- bootstrap wysihtml5 - text editor
        <link href="css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />-->
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.php" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <!-- <img src="/tutor/img/logo80x120.png" width="40" height="60"> --> Admin
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

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="../logout.php" class="btn btn-default btn-flat">Sign out</a>
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
                    <?  include("pages/tables/menu.htm"); ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Dashboard
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>
                                        <?php echo $totalTutors; ?>
                                    </h3>
                                    <p>
                                        Registered Tutors
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios7-people-outline"></i>
                                </div>
                                <a href="pages/tables/tutorsignup.php" class="small-box-footer">
                                    Manage Tutors <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>
                                        <?php echo $totalStudents; ?><!--<sup style="font-size: 20px">%</sup>-->
                                    </h3>
                                    <p>
                                        Registered Students
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios7-people"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Manage Students <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>
                                        <?php echo $totalReps; ?>
                                    </h3>
                                    <p>
                                        Representatives
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios7-person"></i>
                                </div>
                                <a href="pages/tables/reps.php" class="small-box-footer">
                                    Manage Reps <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>
                                        <?php echo P2G::getNumAdmins(); ?>
                                    </h3>
                                    <p>
                                        Admins
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios7-person-outline"></i>
                                </div>
                                <a href="pages/tables/reps.php" class="small-box-footer">
                                    Manage Admins <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        <? echo $sessionsCount; ?>
                                    </h3>
                                    <p>
                                        Tutoring Sessions
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-university"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Manage Sessions <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div> 
                        <!-- ./col -->
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        <?
										echo P2G::getNumPaymentRequests() ?>
                                    </h3>
                                    <p>
                                        Payment Requests
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-dollar"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Manage Pay Requests <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        <?
										echo P2G::getNumSubjects() ?>
                                    </h3>
                                    <p>
                                        Subjects
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Manage Subjects <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        <?
										echo P2G::getNumTests() ?>
                                    </h3>
                                    <p>
                                        Tutor Tests
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-university"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Manage Tests <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div><!-- /.row -->

                    <div class="row">
                    </div>


                    <!-- BAR CHART -->
                    <!-- row -->
                    <div class="row">
                    	<!-- col -->
                        <div class="col-lg-12 col-xs-12">
                            <div class="box box-primary collapsed-box">
                                <div class="box-header">
                                    <h3 class="box-title">Rep Performance</h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-default btn-sm"
                                            data-widget="collapse" data-toggle="tooltip"
                                            title="Collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-default btn-sm"
                                            data-widget="remove" data-toggle="tooltip"
                                            title="Remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body chart-responsive">
                                    <div class="chart" id="rep-perf-chart" style="height:300px;"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
                    <!-- /.row -->

                    <!-- Main row -->
                    <div class="row">



                        <!-- right col -->
                        <section class="col-lg-9 connectedSortable">



                        </section>
                        <!-- ./right col -->

                    </div><!-- /.row (main row) -->

                </section>
                <!-- /.content -->

            </aside>
            <!-- /.right-side -->

        </div>
        <!-- ./wrapper -->

        <!-- add new calendar event modal -->


        <!-- jQuery 2.0.2 -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="js/plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline
        <script src="js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>-->
        <!-- jvectormap
        <script src="js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
        <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>-->
        <!-- fullCalendar
        <script src="js/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>-->
        <!-- jQuery Knob Chart
        <script src="js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>-->
        <!-- daterangepicker
        <script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>-->
        <!-- Bootstrap WYSIHTML5 -->
        <script src="js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <!-- iCheck
        <script src="js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>-->

        <!-- AdminLTE App -->
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>

        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="js/AdminLTE/dashboard.js" type="text/javascript"></script>

        <script type="text/javascript">
		//BAR CHART
			var bar = new Morris.Bar({
				element: 'rep-perf-chart',
				resize: true,
				data: <? echo $dataArray ?>,
				barColors: ['#3366CC', '#99CCFF'],
				xkey: 'y',
				ykeys: ['a', 'b'],
				labels: ['# Tutors', '# Verified'],
				hideHover: 'auto'
			});
			/*[
					{y: '2006', a: 100, b: 90},
					{y: '2007', a: 75, b: 65},
					{y: '2008', a: 50, b: 40},
					{y: '2009', a: 75, b: 65},
					{y: '2010', a: 50, b: 40},
					{y: '2011', a: 75, b: 65},
					{y: '2012', a: 100, b: 90}
				]*/
		</script>

    </body>
</html>
