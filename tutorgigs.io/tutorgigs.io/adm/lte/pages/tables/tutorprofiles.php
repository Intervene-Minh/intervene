<?php  
	require("config.php");
	include 'timecalc.php'; 
	
	require_once("../../../classes/class.P2G.php");
	//require_once('../../../classes/class.Student.php');
	require_once('../../../classes/class.Tutor.php');
	require_once('../../../classes/class.TutorProfile.php');
	require_once('../../../classes/class.TutorBackground.php');
	require_once('../../../classes/class.TutorPaymentInfo.php');
	require_once("../../../classes/class.PaymentRequest.php");
	require_once("../../../classes/class.TutorSession.php");
	require_once("../../../classes/class.TutorRating.php");
	//require_once("../../../classes/class.Messages.php");
	//require_once('../../../classes/SiteRules.class.php');
	require_once('../../../classes/class.W9TaxInfo.php');
	   
    if(empty($_SESSION['user']) || (int)$_SESSION['user']['Security'] < 90) 
    {
        header("Location: /adm/index.php");
        die("Redirecting to index.php"); 
    }
	
				
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>P2G Admin | Tutor Profiles</title>
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
                        Tutor Profiles
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
                                                <span class="label label-primary"></span>
                                            </a>
                                            
                                        </li>
                                        <!-- Notifications: style can be found in dropdown.less -->
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-envelope"></i>
                                                <span class="label label-success"></span>
                                            </a>
                                            
                                        </li>
                                        <!-- Tasks: style can be found in dropdown.less -->
                                        <li class="dropdown tasks-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-warning"></i>
                                                <span class="label label-danger"></span>
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
                                    <table id="myTable" class="table-responsive table-hover" width="100%">
                                    	<thead>                                                    
                                             <tr>                                                
                                                <th>Profile</th>												 
												<th>Status</th>
                                                <th>Details</th>                                               
                                                <th>Actions</th>                                                      
                                            </tr>                                                    
                                        </thead>   
                                        <tbody>
                                            <?php	
                                               $tutor = new Tutor();
											   $profile = new TutorProfile();
											   $tutorUserProfiles = $tutor->LoadAllTutorUserProfiles();
											   
											   while ($tProfile = mysqli_fetch_array($tutorUserProfiles) ) {
												   $tutor->select($tProfile['TutorID']);
												   $profile->select($tutor->ID);
                                            ?>
                                            <tr id="tutorRow<? echo $tutor->ID ?>" 
												class="<? if ( $profile->Approved == 0 ) { echo "warning"; } ?>" 
												style="text-align:left; vertical-align:top;" >                                                 
                                                <td>
													<h4>
														<?php echo ucfirst($tutor->FirstName). ' '. ucfirst($tutor->LastName) ?><br>
														<a href="mailto:<?php echo $tutor->Email ?>"><?php echo $tutor->Email ?></a><br>
														
													</h4>
													<p>
														<? if (!empty ($profile->Picture) ) { ?>														
															<img src="/profile/<?php echo $profile->Picture ?>"><br>
														<? } else { ?>
															<img src="/img/p2g25b.jpg"
																alt="No Photo" 
																width="200" 
																height="200" 
																class="img-responsive"
																style="border-radius: 0px 0px 250px 250px / 0px 0px 66px 66px;"                                            
															>
														<? } ?>
													</p>
													
												</td>                                                
                                                <td>
													<? if ( $profile->IsActive == 1 ) { ?>
														<b class="text-success"><span class="fa fa-check-circle-o"></span> ACCOUNT IS ACTIVE</b>
													<? } else{ ?>
														<b class="text-danger"><span class="fa fa-times-circle-o"></span> ACCOUNT NOT ACTIVE</b>
													<? } ?>	
													<br>
													<? if ( $profile->Approved == 1 ) { ?>
														<b class="text-success"><span class="fa fa-check-circle-o"></span> PROFILE APPROVED</b>
													<? } else{ ?>
														<b class="text-danger"><span class="fa fa-times-circle-o"></span> PROFILE NOT APPROVED</b>
													<? } ?>														
													<br>	
													<? if ( $tutor->getHasVerifiedEmail() == 1 ) { ?>
														<b class="text-success"><span class="fa fa-check-circle-o"></span> EMAIL VERIFIED.</b>
													<? } else{ ?>
														<b class="text-danger"><span class="fa fa-times-circle-o"></span> EMAIL NOT VERIFIED.</b>
													<? } ?>	
													<br/>
													<?php if($profile->w9==1){?>
													
													<b class="text-success"><span class="fa fa-check-circle-o"></span> W9 VERIFIED.</b>
													<? } else{ ?>
														<b class="text-danger"><span class="fa fa-times-circle-o"></span> W9 NOT VERIFIED.</b>
													<? } ?>	
													<br/>
													<?php if($profile->payment==1){?>
													
													<b class="text-success"><span class="fa fa-check-circle-o"></span> Payment Info VERIFIED.</b>
													<? } else{ ?>
														<b class="text-danger"><span class="fa fa-times-circle-o"></span> Payment Info NOT VERIFIED.</b>
													<? } ?>	
													<hr>		
													<strong>Agreed to Terms</strong>: <? echo $tutor->ShowWelcome == 0 ? "Yes" : "No" ?><br>
													<strong>Welcome Screen</strong>: <? echo $profile->ShowWelcome == 0 ? "Yes" : "No" ?><br>
													<? 
														$bgcheck = new TutorBackground();
														$hasApplied = $bgcheck->hasApplied($tutor->ID);
														if ($hasApplied) {
															$bgcheck->select($tutor->ID);
															$bgStatus = $bgcheck->getStatusName();
														} else {
															$bgStatus = "N/A";
															
														}
														
													?>
													<strong>Background Check</strong>: <? echo $bgStatus ?><br>						
												</td>
												<td>
													<p>
														<strong>College</strong>: <? echo $tutor->College ?><br>
														<strong>Major</strong>: <? echo $tutor->Major ?><br>
														<strong>DOB</strong>: <? echo $profile->DOB ?> | <strong>Gender</strong>: <? echo $tutor->Gender ?> | <strong>Zip Code</strong>: <? echo $tutor->ZipCode ?> | <br>
														
														
														<!--<strong>IP Address</strong>: <? echo $tutor->getIPAddress() ?><br> -->
													</p>	
													<p style="max-width:350px;">
														<strong>Headline</strong>: <? echo $profile->Headline ?><br>
														<strong>Description</strong>: <? echo $profile->Description ?><br>
														<strong>City</strong>: <?php echo $profile->City;?></br>
														<strong>State</strong>: <?php echo $profile->State?></br>
														<strong>Zip</strong>: <?php echo $profile->ZipCode;?></br>
														<strong>Phone</strong>: <?php echo $profile->phone;?>
														<hr>
														<strong>Account Created On</strong>: <? echo $profile->CreatedOn ?><br>
														
													</p>
													<?php  if($profile->payment_method=='CHECK'){
													
												?>		
												<div class="alert alert-info" role="alert">
												<strong>Payment Info</strong><br/>
												<?php echo $profile->Address1;?><br/>
												<?php echo $profile->City."&nbsp;".$profile->State."&nbsp;".$profile->ZipCode?><br/>
												
												</div>
											   <?php  }?>
													
												</td>
                                                <td>
													<ul id="actions" class="list-group">
														<li><a href="adminTutorProfileEdit.php?action=toggleApproved&ID=<? echo $tutor->ID?>">Toggle APPROVED PROFILE</a></li>
														<li><a href="adminTutorProfileEdit.php?action=toggleEmailVerified&ID=<? echo $tutor->ID?>">Toggle EMAIL VERIFIED</a></li>
														<li><a href="adminTutorProfileEdit.php?action=deletePhoto&ID=<? echo $tutor->ID?>">Delete PHOTO</a></li>														
														<li><a href="adminTutorProfileEdit.php?action=disableAccount&ID=<? echo $tutor->ID?>" class="<? echo $profile->IsActive == 1 ? 'text-danger' : 'text-success' ?>"><? echo $profile->IsActive == 1 ? "DISABLE" : "ACTIVATE" ?> THIS ACCOUNT</a></li>
														<li><a href="adminTutorProfileEdit.php?action=W9&ID=<? echo $tutor->ID?>" class="<?php if($profile->w9==1){echo "text-success";}else{echo "text-danger";}?>">Toggle W9 <?php if($profile->w9==1){echo "Approved";}else{echo "Pending";}?></a></li>					
														<li><a href="adminTutorProfileEdit.php?action=Payment&ID=<? echo $tutor->ID?>" class="<?php if($profile->payment==1){echo "text-success";}else{echo "text-danger";}?>">Toggle Payment Info <?php if($profile->payment==1){echo "Approved";}else{echo "Pending";}?></a></li>					
													</ul>
													
													
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
					"order": [[ 1, "desc" ]],
					stateSave: true,
					"lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]],
					"dom": '<"top"lf<"clear">p<"clear">>rt<"bottom"ip<"clear">>'
					
				});
				
			});
		});
        
        
        </script>
        

    </body>
</html>