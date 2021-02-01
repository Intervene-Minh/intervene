<?php  
	require("config.php");
	include 'timecalc.php';    
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
		
		// Tally up totals
		$result = mysqli_query($connection, "SELECT * FROM tutors");
		$totalTutors = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM tutors WHERE HasVErifiedEmail=1");
		$totalTutorsVerified = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM tutors WHERE HasVErifiedEmail=0");
		$totalTutorsNotVerified = $result->num_rows;
		$tList = mysqli_query($connection, "SELECT 
					tutors.ID,
					tutors.FirstName,
					tutors.LastName,
					tutors.ZipCode,
					tutors.Email,
					tutors.Gender,
					tutors.Major,
					tutors.College,
					tutors.RepID,
					tutors.Notes,
					tutors.Agree,
					tutors.HasVerifiedEmail,
					tutors.VerificationCode,
					tutors.VerifiedEmailOn,
					tutors.CreatedOn,
					tutors.IPAddress
					
					FROM
					tutors 	");		
		//representatives.ID AS repID,
				//	representatives.FirstName AS repFirstName,
					//representatives.LastName AS repLastName		 
					//INNER JOIN representatives 
					//ON tutors.RepID=representatives.ID");		
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>P2G Admin | Tutor Sign Up</title>
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
        
        <link href="/adm/lte/css/theme.default.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="datatables/media/css/jquery.dataTables.css">


        

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
                        Tutor Tables
                        <small>data tables</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Manage Users</a></li>
                        <li class="active">Tutor Sign Up</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Tutors</h3>
                                    <!-- Tutor Report -->
                                    <ul class="nav navbar-nav">
                                        
                                        <li class="dropdown messages-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-user"></i>
                                                <span class="label label-primary"><? echo $totalTutors ?> total</span>
                                            </a>
                                            
                                        </li>
                                        <!-- Notifications: style can be found in dropdown.less -->
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-envelope"></i>
                                                <span class="label label-success"><? echo $totalTutorsVerified ?> verified</span>
                                            </a>
                                            
                                        </li>
                                        <!-- Tasks: style can be found in dropdown.less -->
                                        <li class="dropdown tasks-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-warning"></i>
                                                <span class="label label-danger"><? echo $totalTutorsNotVerified ?> not verified</span>
                                            </a>
                                            
                                        </li><!-- /.tutor report -->
                                    </ul>             
                                </div><!-- /.box-header --> 
                                                
                                <div class="navbar-form">   
                                	<div class="btn-group"> 
                                        <button class="btn btn-default navbar-btn" onClick="window.history.back()">
                                                <span class="fa fa-arrow-left" ></span> Go Back</button>
                                    </div>                            
                                    <!--<div class="btn-group">                                    	
                                        <button class="btn btn-primary navbar-btn">
                                            <span class="glyphicon glyphicon-plus-sign" ></span> Add a Tutor</button>
                                    </div>
                                    <div class="btn-group"> 
                                        <button class="btn btn-default navbar-btn">
                                                <span class="glyphicon glyphicon-list" ></span> Tutor Details</button>
                                    </div> -->
                                </div>
                                <div class="alert" id="msgBox" style="display:none">
                                		                                        
                                </div>
                                             
                                <div class="box-body table-responsive">                                
                                    <table id="myTable" class="table-responsive table-condensed table-hover table-striped" width="100%">
                                    	<thead>                                                    
                                             <tr>                                                
                                                <th>First</th>
                                                <th>Last</th>
                                                <th>Zip</th> 
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <!--<th>Major</th> 
                                                <th>College</th>-->
                                                <th>Rep</th>                
                                                <th>Date</th>
                                                <th></th>
                                                <!--<th>IP</th>-->                                                                       
                                            </tr>                                                    
                                        </thead>   
                                        <tbody>
                                            <?php	
                                                while ( $tRecord = mysqli_fetch_array($tList) ) {
                                                    $rowColor = (string)$tRecord['HasVerifiedEmail'] == 1 ? '' : 'danger';
                                            ?>
                                            <tr id="t<?php echo $tRecord['ID'] ?>" class="<?php echo $rowColor;  ?>" style="text-align:left;" >                                                 
                                                <td><?php echo ucfirst($tRecord['FirstName']) ?></td>
                                                <td><?php echo ucfirst($tRecord['LastName']) ?></td>
                                                <td><?php echo $tRecord['ZipCode'] ?></td> 
                                                <td><?php echo $tRecord['Email'] ?></td>
                                                <td><?php echo $tRecord['Gender'] ?></td>
                                                <!--<td style="width: 15%;"><?php echo $tRecord['Major'] ?></td> 
                                                <td style="width: 15%;"><?php echo $tRecord['College'] ?></td>-->
                                                <td><a href="reps.php"><?php echo $tRecord['repFirstName'].' '.$tRecord['repLastName'] ?></a></td>
                                                <td style="width: 10%;"><?php 
                                                        //$startTime = new DateTime($tRecord['CreatedOn']);
														  //$endTime = new DateTime(date("Y-m-d H:i:s", strtotime("+1 HOUR")));													  
														  //echo get_timespan_string($startTime, $endTime);
                                                        echo $tRecord['CreatedOn']; 
                                                    ?>
                                                </td>
                                                <td>
                                                	<button type="button" 
                                                        class="btn btn-<?php echo $tRecord['HasVerifiedEmail'] ? 'warning' : 'success'; ?>" 
                                                        onClick="toggleTutorVerified(<?php echo $tRecord['ID'] ?>);" 
                                                        title="<?php echo $tRecord['HasVerifiedEmail'] ? 'DeActivate' : 'Activate'; ?>" 
                                                        >
                                                        <span class="glyphicon glyphicon-<?php echo $tRecord['HasVerifiedEmail'] ? 'ban-circle' : 'ok-sign'; ?>"></span>
                                                	</button>                                                	
                                                    </td>
                                                <!--<td><?php echo $tRecord['IPAddress'] ?></td>-->
                                            </tr>						
                                            <?php } ?>   
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
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
        
        <script 
			src="/adm/lte/js/jquery.tablesorter.min.js" 
			type="text/javascript"></script>
            
        <script src="/adm/lte/js/jquery.tablesorter.widgets.min.js"></script>
        
      
<script type="text/javascript" language="javascript" src="datatables/media/js/jquery.dataTables.js"></script>

		<script type="text/javascript">
		
		$(document).ready(function() {
			$(function() {
				/*
				$("#myTable").tablesorter({
					theme : 'default',
 
					sortList : [[8,0]],
				 
					// header layout template; {icon} needed for some themes
					headerTemplate : '{content}{icon}',
				 
					// initialize column styling of the table
					widgets : ["columns"],
					widgetOptions : {
					  // change the default column class names
					  // primary is the first column sorted, secondary is the second, etc
					  columns : [ "Date" ]
					}
				});
				*/
				// -- DATA TABLE --
				$('#myTable').dataTable({
					"order": [[ 6, "desc" ]],
					stateSave: true,
					"lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]],
					"dom": '<"top"lf<"clear">p<"clear">>rt<"bottom"ip<"clear">>'
				});
				
			});
		});
        
        function deleteTutor( tutorID ) {
            window.location = "adminf.php?action=delete&tutorID=" + tutorID ;
        }
        
        function toggleTutorVerified( tutorID ) {
            window.location = "adminf.php?action=toggle&tutorID=" + tutorID ;
        }
        </script>
        

    </body>
</html>