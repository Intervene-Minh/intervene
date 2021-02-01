<?php  
	require("config.php");
	require("SiteRules.class.php");
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
		
		$siteRules = new SiteRules();
		
		// Tally up totals
		$result = mysqli_query($connection, "SELECT * FROM testResults");
		$totalResults = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM testResults WHERE Score >= '".$siteRules->getPassingTestScore()."'");
		$totalResultsPassing = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM testResults WHERE Score < '".$siteRules->getPassingTestScore()."'");
		$totalResultsFailing = $result->num_rows;
		
		$testResultList = mysqli_query($connection, "SELECT
									testResults.*,
									
									tutors.ID as tutorID,
									tutors.FirstName,
									tutors.LastName,
									
									tests.ID as testID,
									tests.Name as testName,
									
									subjects.ID as subID,
									subjects.CategoryID,
									subjects.`Name` as subName,
									
									categories.ID as catID,
									categories.`Name` as catName
									
									FROM
									testResults
									
									JOIN tutors ON
									testResults.TutorID = tutors.ID
									
									JOIN tests ON
									testResults.TestID = tests.ID
									
									JOIN subjects ON
									testResults.SubjectID = subjects.ID
									
									JOIN categories ON
									subjects.CategoryID = categories.ID
                                    
                                    ORDER BY EndTime DESC
				
		");		
		
		
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test Results | P2G Admin</title>
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
                        Test Results
                        <small>management</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Test Engine</a></li>
                        <li class="active">Test Results</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Test Results</h3>
                                    <!-- Rep Report -->
                                    <ul class="nav navbar-nav">
                                        
                                        <li class="dropdown messages-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list"></i>
                                                <span class="label label-primary"><? echo $totalResults ?> Total</span>
                                            </a>                                            
                                        </li>
                                        <!-- Notifications: style can be found in dropdown.less -->
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list-alt"></i>
                                                <span class="label label-success"><? echo $totalResultsPassing ?> Passing</span>
                                            </a>                                            
                                        </li>
                                        <!-- Tasks: style can be found in dropdown.less -->
                                        <li class="dropdown tasks-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list-alt"></i>
                                                <span class="label label-danger"><? echo $totalResultsFailing ?> Failed</span>
                                            </a>                                            
                                        </li><!-- /.rep report -->                                        
                                    </ul>  
                                    
                                    
                                </div><!-- /.box-header --> 
                                <div class="navbar-form">  
                                	<div class="btn-group">                                    	
                                        <button class="btn btn-default navbar-btn" onClick="window.history.back()">
                                            <span class="glyphicon glyphicon-chevron-left" ></span> Go Back</button>
                                    </div>                              
                                    <div class="btn-group">                                    	                                   	
                                        <button class="btn btn-primary navbar-btn" onClick="window.location = 'tests.php'">
                                            <span class="glyphicon glyphicon-book" ></span> View Tests</button>                                        
                                    </div>
                                </div>                                                              
                                <div class="alert" id="msgBox" style="display:none">
                                		                                        
                                </div> 
                                                               
                                <div class="box-body table-responsive">                                
                                    <table id="example2" class="table table-responsive table-condensed table-hover">
                                    	<thead>                                                    
                                             <tr>                                                
                                                <th>Tutor</th>
                                                <th>Test</th>
                                                <th></th>
                                                <th>Result</th>
                                                <th>Score</th>
                                                <th>Time</th>
                                                <th>Actions</th>
                                                <!--<th>IP</th>-->                                                                       
                                            </tr>                                                    
                                        </thead>   
                                        <tbody>
                                            <?php	
                                                while ( $tRecord = mysqli_fetch_array($testResultList) ) {
													// row color
                                                    $rowColor = (string)$tRecord['Result'] == 1 ? '' : 'danger';
													// num of questions
													$result = mysqli_query($connection, "SELECT * FROM questions WHERE TestID=".$tRecord['TestID']) or die('numQuestions error');
													$numQuestions = $result->num_rows;
                                            ?>
                                            <tr id="t<?php echo $tRecord['ID'] ?>" class="<?php echo $rowColor;  ?>" style="text-align:left;" >
                                                
                                                <td><?php echo $tRecord['FirstName'].' '.$tRecord['LastName']?></td>
                                                <td><?php echo $tRecord['testName'] ?></td>
                                                <td>
                                                    <span class="label label-default"><? echo $tRecord['catName'] ?></span>
                                                    <span class="label label-primary"><? echo $tRecord['subName'] ?></span>
                                                </td>
                                                <td>
                                                	<span class="glyphicon <?php echo $tRecord['Result'] == 1 ? 'glyphicon-check' : 'glyphicon-remove' ?>">
                                                    </span>
                                                </td>
                                                <td><?php echo $tRecord['Score'] == null ? '--' : $tRecord['Score'].' %'; ?> </td>
                                                                                              
                                                <td>
													<?php 
														// Check for incomplete test and label it as such 
														if ($tRecord['EndTime'] == null) {
															echo ("Incomplete");
														} else { 
														// Calculate Testing Time
															$startTime = new DateTime($tRecord['StartTime']);
															$endTime = new DateTime($tRecord['EndTime']);
															echo get_timespan_string($startTime, $endTime);		
														}
                                                    ?>
                                                </td>                                                    
                                                <td>
                                                	<button type="button" class="btn btn-default" 
                                                    		onClick="window.location='questions.php?testID=<? echo $tRecord['TestID'] ?>'" 
                                                            title="Review Tutor's Assessment Test"
                                                            >
                                                    	<span class="glyphicon glyphicon-list-alt"></span> 
                                                        Review Test
                                                    </button>
                                                    <button type="button" class="btn btn-primary" 
                                                    		onClick="MoreInfo(<? echo $tRecord['ID'] ?>)"
                                                            title="More Info"
                                                            >
                                                    	<span class="glyphicon glyphicon-info-sign"></span>
                                                    </button>   
                                                    <button 
															id="delTestResult" 
                                                            data-href="deleteTestResult.php?trID=<? echo $tRecord['ID'] ?>&tID=<? echo $tRecord['tutorID']?>&sID=<? echo $tRecord['subID']?>" 
                                                            data-toggle="modal" 
                                                            data-target="#confirm-delete"
															type="button" 
															class="btn btn-danger pull-right"
															title="Delete the Test Result"
                                                            >
        													<span class="glyphicon glyphicon-trash">
                                                            </span>
                                                    </button> 																	
														                                                   
                                                </td>                                                
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

		<!-- Button trigger modal -->
<!--        <button class="btn btn-primary btn-lg center-block" data-toggle="modal" data-target="#myModal">pop test</button> -->
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria- labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel">Assessment</h4>

                    </div>
                    <div class="modal-body">
                    
                    	<div class="container" style="max-width:500px">
                              <p>TODO: Add more info here</p>
                       </div>
                       
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        
        
        <!-- Confirm Delete Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong>Confirm - Delete A Test Result</strong><span class="glyphicon glyphicon-trash pull-right"></span>
                    </div>
                    <div class="modal-body">
                        <p><strong>Results of this Action: </strong></p>
                        <p>- The Test Results will be deleted from P2G data</p>
                        <p>- Tutor test Attempts for this Tutor's <strong>Subject</strong> will be RESET to 0 (Zero).</p>
                        <p>- Tutor <strong>can take this test again</strong>, as if they never took it before.</p>
                        <br />
                        <p><strong>Are You Sure?</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No! Wait...</button>
                        <a href="#" class="btn btn-danger danger pull-left">Yes! Delete Test Result</a>
                    </div>
                </div>
            </div>
        </div
        ><!-- /.Confirm Delete Modal -->


        <!-- jQuery 2.0.2 -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT 
        <script src="../../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>-->
        <!-- AdminLTE App -->
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
        <!--<script src="testResults.js" type="text/javascript"></script>-->
		<script type="text/javascript">
        
		function MoreInfo( trID ) {
			$('#myModal').modal('show');
		}
		
        function deleteTest ( testID ) {
            //window.location = "adminfTests.php?action=deleteTest&testID=" + testID ;
        }
        
        function toggleTestActive( testID ) {
            window.location = "adminfTests.php?action=toggleTest&testID=" + testID ;
        }
        
        // Delete Confirmation Modal, populates the submit button with the href from the triggering button
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));            
        });
        
        
        </script>
        

    </body>
</html>