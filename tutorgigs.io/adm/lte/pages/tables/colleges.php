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
		$result = mysqli_query($connection, "SELECT * FROM colleges");
		$totalColleges = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM colleges WHERE IsActive=1");
		$totalActive = $result->num_rows;
		$result = mysqli_query($connection, "SELECT * FROM colleges WHERE IsActive=0");
		$totalNotActive = $result->num_rows;
		$collegeList = mysqli_query($connection, "SELECT 
					*
					FROM
					colleges 					 
					");		
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Colleges | P2G Admin</title>
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
                        Colleges
                        <small>management</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Manage Content</a></li>
                        <li class="active">List of Colleges</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Colleges</h3>
                                    <!-- Rep Report -->
                                    <ul class="nav navbar-nav">
                                        
                                        <li class="dropdown messages-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list"></i>
                                                <span class="label label-primary"><? echo $totalColleges ?> Total</span>
                                            </a>
                                            
                                        </li>
                                        <!-- Notifications: style can be found in dropdown.less -->
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list"></i>
                                                <span class="label label-success"><? echo $totalActive ?> Active</span>
                                            </a>
                                            
                                        </li>
                                        <!-- Tasks: style can be found in dropdown.less -->
                                        <li class="dropdown tasks-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-list"></i>
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
                                            <span class="glyphicon glyphicon-plus-sign" ></span> Add New College</button>
                                        <button class="btn btn-danger navbar-btn">
                                            <span class="glyphicon glyphicon-trash" ></span> Delete a College</button>
                                    </div>
                                    <div class="btn-group"> 
                                        <button class="btn btn-warning navbar-btn">
                                                <span class="glyphicon glyphicon-list" ></span> View 'Other' College List</button>
                                    </div> 
                                </div>
                                <div class="alert" id="msgBox" style="display:none">
                                		                                        
                                </div> 
                                
                                                               
                                <div class="box-body table-responsive">                                
                                    <table id="example2" class="table table-responsive table-condensed table-hover">
                                    	<thead>                                                    
                                             <tr>                                                
                                                <th>College Name</th>                                                
                                                <th>Actions</th>                                                      
                                            </tr>                                                    
                                        </thead>   
                                        <tbody>
                                            <?php	
                                                while ( $cRecord = mysqli_fetch_array($collegeList) ) {
                                                    $rowColor = (string)$cRecord['IsActive'] == 1 ? '' : 'danger';
                                            ?>
                                            <tr id="c<?php echo $cRecord['ID'] ?>" class="<?php echo $rowColor;  ?>" style="text-align:left;" >
                                                <td id="colName<?php echo $cRecord['ID'] ?>"><?php echo $cRecord['Name'] ?></td>
                                                
                                                <td>
                                                <button type="button" class="btn btn-default" 
                                                        onClick="ToggleRow(<?php echo $cRecord['ID'] ?>);"
                                                         title="Edit this College"
                                                         >
                                                    <span class="glyphicon glyphicon-edit"></span> 
                                                    Edit
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-<?php echo $cRecord['IsActive'] ? 'warning' : 'success'; ?>" 
                                                        onClick="toggleCollegeActive(<?php echo $cRecord['ID'] ?>);" 
                                                        title="<?php echo $cRecord['IsActive'] ? 'DeActivate' : 'Activate'; ?>" 
                                                        >
                                                        <span class="glyphicon glyphicon-<?php echo $cRecord['IsActive'] ? 'ban-circle' : 'ok-sign'; ?>"></span>
                                                </button>
                                                
                                                </td>
                                                
                                            </tr>
                                            <!-- EDIT ROW  -->
                                            <tr id="edit<?php echo $cRecord['ID'] ?>" class="<?php echo $rowColor;  ?>" style="text-align:left; display:none;" > 
                                            	<td><input type="text" id="ecName<?php echo $cRecord['ID'] ?>" width="100%" value="<?php echo $cRecord['Name'] ?>"></td>
                                                
                                                <td>
                                                	<button type="button" class="btn btn-primary" 
                                                    		onClick="UpdateCollege( <?php echo $cRecord['ID'] ?>, $('#ecName<?php echo $cRecord['ID'] ?>').val() );">
                                                    		<span class="glyphicon glyphicon-floppy-disk" title="Save">
                                                            </span> Save changes
                                                    </button> 
                                                    
                                                    <button type="button" class="btn btn-default" 
                                                    		onClick="ToggleRow( <?php echo $cRecord['ID'] ?> );">
                                                    		<span class="glyphicon glyphicon-share" title="Cancel">
                                                            </span> Cancel
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
                         <h4 class="modal-title" id="myModalLabel">Add College</h4>

                    </div>
                    <div class="modal-body">
                    
                    	<div class="container" style="max-width:500px">
                              <form id="cForm" name="cForm" role="form" action="addCollege.php" method="post">
                              
                              	<!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="cName">Name</label>
                                  <div class="controls">
                                    <input id="cName" name="cName" type="text" placeholder="" class="form-control" required>
                                    
                                  </div>
                                </div>
                                <br/>                            
                                <!-- Multiple Radios -->
                                <div class="control-group">
                                  <label class="control-label" for="cIsActive">Shown in Colleges Dropdown List</label>
                                  
                                    <label class="radio" for="cIsActive-0">
                                      <input type="radio" name="cIsActive" id="cIsActive-0" value="1" checked="checked">
                                      Active
                                    </label>
                                    <label class="radio" for="cIsActive-1">
                                      <input type="radio" name="cIsActive" id="cIsActive-1" value="0">
                                      Not Active
                                    </label>                                  
                                </div>
                                
                                <!-- Button (Double) -->
                                <div class="control-group">
                                  <label class="control-label" for="cSubmit"></label>
                                  <div class="controls">
                                    <button id="cSubmit" name="cSubmit" class="btn btn-success">Submit</button>
                                    <button id="cCancel" name="cCancel" class="btn btn-warning pull-right" type="reset">Reset</button>
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
        <script src="colleges.js" type="text/javascript"></script>
		<script type="text/javascript">
        
        function deleteCollege( collegeID ) {
            window.location = "adminfColleges.php?action=deleteCollege&collegeID=" + collegeID ;
        }
        
        function toggleCollegeActive( collegeID ) {
            window.location = "adminfColleges.php?action=toggleCollege&collegeID=" + collegeID ;
        }
        </script>
        

    </body>
</html>