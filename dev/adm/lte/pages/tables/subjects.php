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
		
		// Tally up totals
		$result = mysqli_query($connection, "SELECT * FROM subjects");
		$totalSubjects = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM subjects WHERE IsActive=1");
		$totalActive = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM subjects WHERE IsActive=0");
		$totalNotActive = $result->num_rows;
		$subjectsList = mysqli_query($connection, "SELECT 
									subjects.ID,
									subjects.CategoryID,
									subjects.Name,
									subjects.IsActive,
									categories.ID as catID,
									categories.Name as catName
									FROM
									subjects
									INNER JOIN categories
									ON subjects.CategoryID=categories.ID	
									ORDER BY subjects.Name ASC, categories.Name ASC			 
					");		
		$categoryList = mysqli_query($connection, "SELECT 
									*
									FROM
									categories		
									WHERE IsActive=1
									 		 
					");		
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Subjects | P2G Admin</title>
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
                        Subjects
                        <small>management</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Test Engine</a></li>
                        <li class="active">List of Subjects</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Subjects</h3>
                                    <!-- Rep Report -->
                                    <ul class="nav navbar-nav">                                        
                                        <li class="dropdown messages-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-book"></i>
                                                <span class="label label-primary"><? echo $totalSubjects ?> Total</span>
                                            </a>                                            
                                        </li>
                                        <!-- Notifications: style can be found in dropdown.less -->
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-book"></i>
                                                <span class="label label-success"><? echo $totalActive ?> Active</span>
                                            </a>                                            
                                        </li>
                                        <!-- Tasks: style can be found in dropdown.less -->
                                        <li class="dropdown tasks-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-book"></i>
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
                                            <span class="glyphicon glyphicon-plus-sign" ></span> Add New Subject</button>
                                    </div>
                                    <div class="btn-group">                                    	
                                        <button class="btn btn-warning navbar-btn" onClick="window.location = 'tests.php'">
                                            <span class="glyphicon glyphicon-list" ></span> View Test List</button>
                                    </div>
                                </div>
                                <div class="alert" id="msgBox" style="display:none">
                                		                                        
                                </div>                                                               
                                <div class="box-body table-responsive">                                
                                    <table id="example2" class="table table-responsive table-condensed table-hover">
                                    	<thead>                                                    
                                             <tr>                                                
                                                <th>Subject</th>
                                                <th>Category</th>
                                                <th># of Tests</th>
                                                <th>Actions</th>
                                                <!--<th>IP</th>-->                                                                       
                                            </tr>                                                    
                                        </thead>   
                                        <tbody>
                                            <?php	
                                                while ( $sRecord = mysqli_fetch_array($subjectsList) ) {
                                                    $rowColor = (string)$sRecord['IsActive'] == 1 ? '' : 'danger';
													//Test count
													$result = mysqli_query($connection, "SELECT * FROM tests WHERE SubjectID=".$sRecord['ID']);
													$testCount = $result->num_rows;
                                            ?>
                                            <tr id="s<?php echo $sRecord['ID'] ?>" class="<?php echo $rowColor;  ?>" style="text-align:left;" >
                                                
                                                <td id="subName<?php echo $sRecord['ID'] ?>"><?php echo $sRecord['Name'] ?></td>
                                                
                                                <td><a href="categories.php"><?php echo $sRecord['catName'] ?></a></td>
                                                <td ><a href="tests.php"><?php echo $testCount ?></a></td>
                                                
                                                <td>
                                                	<button type="button" class="btn btn-default" 
                                                    		onClick="window.location='tests.php'" 
                                                            title="View Test for this Subject"
                                                            >
                                                    	<span class="glyphicon glyphicon-zoom-in"></span> 
                                                        Test
                                                    </button>
                                                    
                                                    <button type="button" class="btn btn-default" 
                                                    		onClick="ToggleRow(<?php echo $sRecord['ID'] ?>);"
                                                             title="Edit this Subject"
                                                             >
                                                    	<span class="glyphicon glyphicon-edit"></span> 
                                                        Edit
                                                    </button>
                                                    <button type="button" 
                                                    		class="btn btn-<?php echo $sRecord['IsActive'] ? 'warning' : 'success'; ?>" 
                                                            onClick="toggleSubjectActive(<?php echo $sRecord['ID'] ?>);" 
                                                            title="<?php echo $sRecord['IsActive'] ? 'DeActivate' : 'Activate'; ?>" 
                                                            >
                                                    		<span class="glyphicon glyphicon-<?php echo $sRecord['IsActive'] ? 'ban-circle' : 'ok-sign'; ?>"></span>
                                                    </button>
                                                
                                                
                                                	                                             
                                                </td>
                                                <!-- EDIT ROW  -->
                                                <tr id="edit<?php echo $sRecord['ID'] ?>" class="<?php echo $rowColor;  ?>" style="text-align:left; display:none;" > 
                                                    
                                                    <td><input type="text" id="esName<?php echo $sRecord['ID'] ?>" value="<?php echo $sRecord['Name'] ?>"></td>
                                                    <td><a href="categories.php"><?php echo $sRecord['catName'] ?></a></td>
                                                	<td ><a href="tests.php"><?php echo $testCount ?></a></td>                                                                                                
                                                    <td>
                                                        <button type="button" class="btn btn-primary" 
                                                                onClick="UpdateSubject( <?php echo $sRecord['ID'] ?>, $('#esName<?php echo $sRecord['ID'] ?>').val() );">
                                                                <span class="glyphicon glyphicon-floppy-disk" title="Save">
                                                                </span> Save changes
                                                        </button> 
                                                        
                                                        <button type="button" class="btn btn-default" 
                                                                onClick="ToggleRow( <?php echo $sRecord['ID'] ?> );">
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
                         <h4 class="modal-title" id="myModalLabel">Add Subject</h4>

                    </div>
                    <div class="modal-body">
                    
                    	<div class="container" style="max-width:500px">
                              <form id="sForm" name="sForm" role="form" action="addSubject.php" method="post">
                               <script type="text/javascript">
							   function checkCategory() {
								   //alert( $("#sCategoryID").val() > 0 );
									if ( $("#sCategoryID").val() > 0) {
										$("#sSubmit").removeAttr("disabled"); 
									} else {
										$("#sSubmit").attr("disabled", "disabled");
									}
								}
							   </script>
                               <!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="sCategoryID">Category for Subject</label>
                                  <div class="controls">
                                    <select class="form-control" id="sCategoryID" name="sCategoryID" autofocus onBlur="checkCategory()" onChange="checkCategory()">
                                        <option value="0" selected>Select a Category</option>
                                        <?php
											while ($category = mysqli_fetch_array($categoryList)) {
										?>
                                        	<option value="<?php echo $category['ID']; ?>"><?php echo $category['Name']; ?></option>
                                        <?php } ?>
                                     </select>
                                    
                                  </div>
                                </div>
                                
                              	<!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="sName">Subject Name</label>
                                  <div class="controls">
                                    <input id="sName" name="sName" type="text" placeholder="" class="form-control" required>
                                    
                                  </div>
                                </div>
                                <br/>                            
                                <!-- Multiple Radios -->
                                <div class="control-group">
                                  <label class="control-label" for="sIsActive">Shown under Category List</label>
                                  
                                    <label class="radio" for="sIsActive-0">
                                      <input type="radio" name="sIsActive" id="sIsActive-0" value="1" checked="checked">
                                      Active
                                    </label>
                                    <label class="radio" for="sIsActive-1">
                                      <input type="radio" name="sIsActive" id="sIsActive-1" value="0">
                                      Not Active
                                    </label>                                  
                                </div>
                                
                                <!-- Button (Double) -->
                                <div class="control-group">
                                  <label class="control-label" for="sSubmit"></label>
                                  <div class="controls">
                                    <button id="sSubmit" name="sSubmit" class="btn btn-success" disabled="disabled">Submit</button>
                                    <button id="sCancel" name="sCancel" class="btn btn-warning pull-right" type="reset">Reset</button>
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
        <script src="subjects.js" type="text/javascript"></script>
		<script type="text/javascript">
        
		
		
        function deleteSubject (subjectID ) {
            //window.location = "adminfSubjects.php?action=deleteSubject&subjectID=" + subjectID ;
        }
        
        function toggleSubjectActive( subjectID ) {
            window.location = "adminfSubjects.php?action=toggleSubject&subjectID=" + subjectID ;
        }
        </script>
        

    </body>
</html>