<?php  
	require("config.php");
    if(empty($_SESSION['user']) || (int)$_SESSION['user']['Security'] < 90) 
    {
        header("Location: /adm/index.php");
        die("Redirecting to index.php"); 
    }
		
	require("../../../classes/class.Representative.php");
	require("../../../classes/class.Tutor.php");
	require("../../../classes/class.P2G.php");
	
	
	// -- Edit Rep --
	if ($_POST['action'] == 'edit') {
		$editRep = new Representative();
		$editRep->select($_POST['RepID']);
		$editRep->setFirstName($_POST['FirstName']);
		$editRep->setLastName($_POST['LastName']);
		$editRep->setNotes($_POST['Notes']);
		$editRep->update($editRep->ID);
	}
	
	// -- Toggle Active/Inactive --
	if ($_POST['action'] == 'toggleActive') {
		$toggleRep = new Representative();
		$toggleRep->select($_POST['RepID']);
		if ($toggleRep->IsActive == 0) {
			$toggleRep->setIsActive(1);
		} else {
			$toggleRep->setIsActive(0);
		}
		$toggleRep->update($toggleRep->ID);
	}
	
	$representative = new Representative();	
	$allReps = $representative->selectAllReps();
	
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>P2G Admin | Manage Reps</title>
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
                <img src="/site/img/logo80x120.png" width="40" height="60"> Admin
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
                <!-- Menu sidebar: -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        
                    </div>
                    
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <? include("menu.htm"); ?>
                </section>
                <!-- /. Menu sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Representatives
                        <small>management</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">P2G Users</a></li>
                        <li class="active">Representatives</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">P2G Reps</h3>
                                    <br><br>

                                    <!-- Split button -->
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-danger">Actions</button>
                                      <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                      </button>
                                      <ul class="dropdown-menu" role="menu">
                                        <li><a href="#" data-toggle="modal" data-target="#myModal">Create a New Representative</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Delete from DB</a></li>
                                      </ul>
                                    </div>
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="tableReps" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>                                            	
                                                <th></th>
                                                
                                                <th>First</th>
                                                <th>Last</th>
                                                <th>Notes</th>
                                                <th class="text-center"># Tutors</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	<?	while ($row = mysqli_fetch_array($allReps)) { 
													$rep = new Representative();
													$rep->select($row['ID']);
											?>
                                            <!-- Data Display Row -->
                                            <tr id="Rep<? echo $rep->ID ?>" class="<? echo $rep->getIsActive() == 0 ? 'alert-danger' : '' ?>">
                                            	<td class="text-center"><? $isActiveClass = $rep->getIsActive() == 1 ? 'close' : 'checkmark' ?>
                                                    <form id="formToggleActive<? echo $rep->ID ?>" method="post" action="reps2.php">
                                                    	<input type="hidden" name="action" value="toggleActive">
                                                    	<input type="hidden" name="RepID" value="<? echo $rep->ID ?>">
                                                    	<span class="ion ion-<? echo $isActiveClass ?>-circled" 
                                                        	style="cursor:pointer;"
                                                            onClick="document.forms['formToggleActive<? echo $rep->ID ?>'].submit()"></span>
                                                    </form>
                                                </td>
                                                
                                                <td><? echo $rep->getFirstName()?></td>
                                                <td><? echo $rep->getLastName()?></td>
                                                <td><? echo $rep->getNotes()?></td>
                                                <td class="text-center"><? echo $rep->getNumTutorsByRepID($row['ID']) ?></td>
                                                <td>
                                                	<button onClick="toggleEditRow('<? echo $rep->ID ?>');"><span class="ion ion-edit"></span></button>
                                                    <!--<button><span class="ion ion-trash-a"></span></button>-->
                                                </td>
                                            </tr>   
                                            <!-- Data Edit Row -->
                                            
                                            <tr id="editRep<? echo $rep->ID ?>" class="<? echo $rep->getIsActive() == 0 ? 'alert-danger' : '' ?>" style="display:none">
                                            	<form id="formEditRep<? echo $rep->ID ?>" method="post" action="reps2.php">
                                                    <td class="text-center"><? $isActiveClass = $rep->getIsActive() == 1 ? 'close' : 'checkmark' ?>
                                                        <span class="ion ion-<? echo $isActiveClass ?>-circled"></span></td>
                                                    
                                                    <td><input name="FirstName" value="<? echo $rep->getFirstName()?>" class="form-control"></td>
                                                    <td><input name="LastName" value="<? echo $rep->getLastName()?>" class="form-control"></td>
                                                    <td><input name="Notes" value="<? echo $rep->getNotes()?>" class="form-control"></td>
                                                    <td class="text-center"><? echo $rep->getNumTutorsByRepID($row['ID']) ?></td>
                                                    <td>
                                                        <input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="RepID" value="<? echo $rep->ID ?>">                                                	
                                                        
                                                        <button onClick="document.forms['formEditRep<? echo $rep->ID ?>'].submit();"><span class="ion ion-thumbsup"></span> Save</button>
                                                        <button type="button" onClick="toggleEditRow('<? echo $rep->ID ?>');">
                                                            <span class="ion ion-close-circled"></span> Close</button>
                                                    </td>
                                                </form>
                                            </tr>
                                            
                                            <? 	} // end while ?>                                         
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                
                                                <th>First</th>
                                                <th>Last</th>
                                                <th>Notes</th>
                                                <th class="text-center"># Tutors</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>

                </section>
                <!-- /. Main content -->
                
            </aside>
            <!-- /.right-side -->
        </div>
        <!-- ./wrapper -->
        
        
        <!-- ********** MODAL WINDOWS ************ -->
		<!-- Button to trigger modal window -->
        <!-- <button class="btn btn-primary btn-lg center-block" data-toggle="modal" data-target="#myModal">pop test</button> -->
        
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria- labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel">Add P2G Representative</h4>

                    </div>
                    <div class="modal-body">
                    
                    	<div class="container" style="max-width:500px">
                              <form id="rForm" name="rForm" role="form" action="addRep.php" method="post">
                              
                              	<!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="rFirstName">First Name</label>
                                  <div class="controls">
                                    <input id="rFirstName" name="rFirstName" type="text" placeholder="" class="form-control" required>
                                    
                                  </div>
                                </div>
                            
                                <!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="rLastName">Last Name</label>
                                  <div class="controls">
                                    <input id="rLastName" name="rLastName" type="text" placeholder="" class="form-control" required>
                                    <br>
                                  </div>
                                </div>
                                
                                <!-- Multiple Radios -->
                                <div class="control-group">
                                  <label class="control-label" for="rIsActive">Shown in Tutor Sign Up List</label>
                                  
                                    <label class="radio" for="rIsActive-0">
                                      <input type="radio" name="rIsActive" id="rIsActive-0" value="1" checked="checked">
                                      Active
                                    </label>
                                    <label class="radio" for="rIsActive-1">
                                      <input type="radio" name="rIsActive" id="rIsActive-1" value="0">
                                      Not Active
                                    </label>                                  
                                </div>
                                
                                <!-- Textarea -->
                                <div class="control-group">
                                  <label class="control-label" for="rNotes">Notes</label>
                                  <div class="controls">                     
                                    <textarea id="rNotes" name="rNotes" style="width:100%" placeholder="Notes (i.e., Assigned Locations)"></textarea>
                                  </div>
                                </div>
                                
                                <!-- Button (Double) -->
                                <div class="control-group">
                                  <label class="control-label" for="rSubmit"></label>
                                  <div class="controls">
                                    <button id="rSubmit" name="rSubmit" class="btn btn-success">Submit</button>
                                    <button id="rCancel" name="rCancel" class="btn btn-warning pull-right" type="reset">Reset</button>
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
        <!-- DATA TABLES SCRIPT -->
        <script src="../../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
               
		<script type="text/javascript"> 			
			$(document).ready(function(e) {
                
				// Data Table Setup
				$('#tableReps').dataTable({
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bSort": false,
                    "bInfo": false,
                    "bAutoWidth": false
                });
				
            });
			
			// Function to Toggle Editing Row
			function toggleEditRow( repID ) {
				$('#Rep' + repID).toggle(0);
				$('#editRep' + repID).toggle(0);
			}
			
        </script>
        
        

    </body>
</html>