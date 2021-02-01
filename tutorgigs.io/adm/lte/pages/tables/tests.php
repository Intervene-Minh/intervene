<?php  
//echo 'Create tewst========';
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
		
		// Tally up totals
		$result = mysqli_query($connection, "SELECT * FROM tests");
		$totalTests = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM tests WHERE IsActive=1");
		$totalActive = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM tests WHERE IsActive=0");
		$totalNotActive = $result->num_rows;
		
		$testList = mysqli_query($connection, "SELECT
									tests.ID,
									tests.`Name`,
									tests.SubjectID,
									tests.IsActive,
									tests.CreatedBy,
									tests.CreatedOn,
									tests.LastUpdate,
									subjects.ID as subID,
									subjects.CategoryID,
									subjects.`Name` as subName,
									categories.ID as catID,
									categories.`Name` as catName
									FROM
									tests
									JOIN subjects ON
									tests.SubjectID = subjects.ID
									JOIN categories ON
									subjects.CategoryID = categories.ID
				
		");		
		$subjectList = mysqli_query($connection, "SELECT 
									*
									FROM
									subjects		
									WHERE IsActive=1 ORDER BY Name	 
					");	
		$categoryList = mysqli_query($connection, "SELECT 
									*
									FROM
									categories		
									WHERE IsActive=1		 
					");		
		//$testTypes = mysqli_query($connection, "SELECT * FROM testTypes");
        $home_page_url='../../index.php';
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tests | P2G Admin</title>
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
            <a href="<?=$home_page_url?>" class="logo">
               

               <!--  <img src="/img/logo80x120.png" width="40" height="60"> --> Admin
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
                        Tests
                        <small>management</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Test Engine</a></li>
                        <li class="active">Tests</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Tests</h3>
                                    <!-- Rep Report -->
                                    <ul class="nav navbar-nav">
                                        
                                        <li class="dropdown messages-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list-alt"></i>
                                                <span class="label label-primary"><? echo $totalTests ?> Total</span>
                                            </a>                                            
                                        </li>
                                        <!-- Notifications: style can be found in dropdown.less -->
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list-alt"></i>
                                                <span class="label label-success"><? echo $totalActive ?> Active</span>
                                            </a>                                            
                                        </li>
                                        <!-- Tasks: style can be found in dropdown.less -->
                                        <li class="dropdown tasks-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list-alt"></i>
                                                <span class="label label-danger"><? echo $totalNotActive ?> Not Active</span>
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
                                        <button class="btn btn-primary navbar-btn" data-toggle="modal" data-target="#myModal">
                                            <span class="glyphicon glyphicon-plus-sign" ></span>&nbsp; Create A Test</button>                                        
                                    </div>
                                </div>
                                
                                    <div class="alert alert-warning" 
                                        id="msgBox" 
                                        style="display:<? echo ( isset($_GET['msg']) && (strlen($_GET['msg']) > 0) )  ? '' : 'none';?>; width: 95%;"
                                        > 
                                            <h4><? echo $_GET['msg'] ?></h4>
                                            <p></p>
                                    </div>                             
                                
                                                               
                                <div class="box-body table-responsive">                                
                                    <table id="example2" class="table table-responsive table-condensed table-hover">
                                    	<thead>                                                    
                                             <tr>                                                
                                                <th>Test Name</th>
                                                <th>Category</th>
                                                <th>Subject</th>
                                                <th># of Questions</th>
                                                <th>Actions</th>
                                                <!--<th>IP</th>-->                                                                       
                                            </tr>                                                    
                                        </thead>   
                                        <tbody>
                                            <?php	
                                                while ( $tRecord = mysqli_fetch_array($testList) ) {
													// row color
                                                    $rowColor = (string)$tRecord['IsActive'] == 1 ? '' : 'danger';
													// num of questions
													$result = mysqli_query($connection, "SELECT * FROM questions WHERE TestID=".$tRecord['ID']) or die('numQuestions error');
													$numQuestions = $result->num_rows;
                                            ?>
                                            <tr id="t<?php echo $tRecord['ID'] ?>" class="<?php echo $rowColor;  ?>" style="text-align:left;" >
                                                
                                                <td id="testName<?php echo $tRecord['ID'] ?>"><?php echo $tRecord['Name'] ?></td>                                                
                                                <td ><a href="categories.php"><?php echo $tRecord['catName'] ?></a></td>
                                                <td><a href="subjects.php"><?php echo $tRecord['subName'] ?></a></td>
                                                <td><a href="questions.php?testID=<? echo $tRecord['ID'] ?>"><?php echo $numQuestions ?></a></td>
                                                
                                                <td>
                                                	<button type="button" class="btn btn-default" 
                                                    		onClick="window.location='questions.php?testID=<? echo $tRecord['ID'] ?>'" 
                                                            title="View Qs and As for this Test"
                                                            >
                                                    	Q <span class="glyphicon glyphicon-question-sign"></span> A
                                                    </button>
                                                    
                                                    <button type="button" class="btn btn-default" 
                                                    		onClick="ToggleRow(<?php echo $tRecord['ID'] ?>);"
                                                             title="Edit this Test"
                                                             >
                                                    	<span class="glyphicon glyphicon-edit"></span> 
                                                        Edit
                                                    </button>
                                                    <button type="button" 
                                                    		class="btn btn-<?php echo $tRecord['IsActive'] ? 'warning' : 'success'; ?>" 
                                                            onClick="toggleTestActive(<?php echo $tRecord['ID'] ?>);" 
                                                            title="<?php echo $tRecord['IsActive'] ? 'DeActivate' : 'Activate'; ?>" 
                                                            >
                                                    		<span class="glyphicon glyphicon-<?php echo $tRecord['IsActive'] ? 'ban-circle' : 'ok-sign'; ?>"></span>
                                                    </button>
                                                    <button class="btn btn-danger" onclick="deleteTest(<?php echo $tRecord['ID'] ?>);" >Delete</button>
                                                </td>
                                                <!-- EDIT ROW  -->
                                                <tr id="edit<?php echo $tRecord['ID'] ?>" class="<?php echo $rowColor;  ?>" style="text-align:left; display:none;" > 
                                                    
                                                    <td><input type="text" id="etName<?php echo $tRecord['ID'] ?>" value="<?php echo $tRecord['Name'] ?>"></td>
                                                    <td ><a href="categories.php"><?php echo $tRecord['catName'] ?></a></td>
                                                    <td><a href="subjects.php"><?php echo $tRecord['subName'] ?></a></td>
                                                    <td><a href="questions.php?testID=<? echo $tRecord['ID'] ?>"><?php echo $numQuestions ?></a></td>                                                                                                
                                                    <td>
                                                        <button type="button" class="btn btn-primary" 
                                                                onClick="UpdateTest( <?php echo $tRecord['ID'] ?>, $('#etName<?php echo $tRecord['ID'] ?>').val() );">
                                                                <span class="glyphicon glyphicon-floppy-disk" title="Save">
                                                                </span> Save changes
                                                        </button> 
                                                        
                                                        <button type="button" class="btn btn-default" 
                                                                onClick="ToggleRow( <?php echo $tRecord['ID'] ?> );">
                                                                <span class="glyphicon glyphicon-share" title="Cancel">
                                                                </span> Cancel
                                                        </button>
                                                    </td>
                                                    
                                                </tr>
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
                         <h4 class="modal-title" id="myModalLabel">Add Test</h4>

                    </div>
                    <div class="modal-body">
                    
                    	<div class="container" style="max-width:500px">
                              <form id="tForm" name="tForm" role="form" action="addTest.php" method="post">
                               <script type="text/javascript">
							   function checkSubject() {
								   //alert( $("#tSubjectID").val() > 0 );
									if ( $("#tSubjectID").val() > 0) {
										$("#tSubmit").removeAttr("disabled"); 
									} else {
										$("#tSubmit").attr("disabled", "disabled");
									}
								}
							   </script>
                                <!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="tName">Test Name</label>
                                  <div class="controls">
                                    <input id="tName" name="tName" type="text" placeholder="Test Name" class="form-control" required>
                                    
                                  </div>
                                </div>
                                <br/>      
                                <!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="tSubjectID">Subjects without Tests</label>
                                  <div class="controls">
                                 <select class="form-control" id="tSubjectID" name="tSubjectID" autofocus="" onblur="checkSubject()" onchange="checkSubject()">
                                        <option value="0" selected="">[Select a Subject for this Test]</option>
                                                <option value="81">English - English </option>
                                                        
                                                    <option value="82">Maths</option> 
                                                                                                       
                                                                                          
                                                                             </select>   
                                    
                                  </div>
                                </div>
                                 <!--  Passing percent -->

                                 <div class="control-group">
                                  <label class="control-label" for="tName">Passing percent(%)</label>
                                  <div class="controls">
                                    <input id="tName" name="percent" type="number" min="1" placeholder="e.g-30%" class="form-control" required>
                                    
                                  </div>
                                </div>




                                <br/>                 
                                <!-- Multiple Radios -->
                                <div class="control-group">
                                  <label class="control-label" for="tIsActive">Shown in Test List</label>
                                  
                                    <label class="radio" for="tIsActive-0">
                                      <input type="radio" name="tIsActive" id="tIsActive-0" value="1" checked="checked">
                                      Active
                                    </label>
                                    <label class="radio" for="tIsActive-1">
                                      <input type="radio" name="tIsActive" id="tIsActive-1" value="0">
                                      Not Active
                                    </label>                                  
                                </div>
                                <input type="hidden" id="tCreator" name="tCreator" value="<?php echo $_SESSION['user']['id']; ?>">
                                <!-- Button (Double) -->
                                <div class="control-group">
                                  <label class="control-label" for="tSubmit"></label>
                                  <div class="controls">
                                    <button id="tSubmit" name="tSubmit" class="btn btn-success" disabled="disabled">Submit</button>
                                    <button id="tCancel" name="tCancel" class="btn btn-warning pull-right" type="reset">Reset</button>
                                  </div>
                                </div>
                                
                            </form>
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


        <!-- jQuery 2.0.2 -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT 
        <script src="../../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>-->
        <!-- AdminLTE App -->
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="tests.js" type="text/javascript"></script>
		<script type="text/javascript">
        
		
		
        function deleteTest ( testID ) {
            window.location = "adminfTests.php?action=deleteTest&testID=" + testID ;
        }
        
        function toggleTestActive( testID ) {
            window.location = "adminfTests.php?action=toggleTest&testID=" + testID ;
        }
        </script>
        

    </body>
</html>