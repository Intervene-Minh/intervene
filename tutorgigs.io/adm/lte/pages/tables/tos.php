<?php  


	require("config.php");
    if(empty($_SESSION['user']) || (int)$_SESSION['user']['Security'] < 90) 
    {
        header("Location: /adm/index.php");
        die("Redirecting to index.php"); 
    }
    require_once("../../../classes/class.EditablePageContent.php");
    $epc = new EditablePageContent();

	
	
	if ($_POST['action'] == 'saveStudent') {
			$epc->setContent($_POST['TOSs']);
		$epc->update(3);
		
	}


	if ($_POST['action'] == 'saveTutor') {
		$epc->setContent($_POST['TOSt']);
		$epc->update(4);
		
	}

    // Save All
    if ($_POST['action'] == 'saveAll') {
    	$epc->setContent($_POST['saTOSs']);
    	$epc->update(3);
       
        $epc->setContent($_POST['saTOSt']);
        $epc->update(4);
    }
    $tosTutor= new EditablePageContent();
    $tosTutor->select(4);
    $tosStudent= new EditablePageContent();
    $tosStudent->select(3);
	// Open File
	/*
		$fh = fopen($htmlroot . 'site/' . $tosFile,'r');
		
		while ($line = fgets($fh)) { 
		   echo($line);
		}
												
		fclose($fh);
	*/ 
					
?> 
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Terms of Service | P2G Admin</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../../css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="../../css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        
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
                                <li class="user-header bg-maroon">
                                   
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
                        Legal
                        <small>Documents displayed on website</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li><a href="#">Forms</a></li>
                        <li class="active">Legal</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <div class='row'>
                        <div class='col-md-12'>
                            <div class='box box-danger'>
                                <div class='box-header'>
                                    <h3 class='box-title'>Student Terms of Service <small>agreed upon when users sign up or pay for sessions</small></h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                        <!--<button class="btn btn-info btn-sm" data-widget='remove' data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
                                    </div><!-- /. tools -->
                                </div><!-- /.box-header -->
                                <div class='box-body pad'>
                                    <form method="post" action="tos.php">
                                        <textarea id="TOSs" name="TOSs" rows="10" cols="80">
                                            <? 												
												echo $tosStudent->getContent();											
											?>
                                        </textarea><br>  
                                        <input type="hidden" name="action" value="saveStudent">   
                                        <input type="submit" class="btn btn-primary" value="Update STUDENT Terms of Service">                   
                                    </form>
                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col-->
                    </div><!-- ./row -->
					
					<div class='row'>
                        <div class='col-md-12'>
                            <div class='box box-danger'>
                                <div class='box-header'>
                                    <h3 class='box-title'>Tutor Terms of Service <small>agreed upon when users sign up or pay for sessions</small></h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                        <!--<button class="btn btn-info btn-sm" data-widget='remove' data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
                                    </div><!-- /. tools -->
                                </div><!-- /.box-header -->
                                <div class='box-body pad'>
                                    <form method="post" action="tos.php">
                                        <textarea id="TOSt" name="TOSt" rows="10" cols="80">
                                            <? 												
												echo $tosTutor->getContent();											
											?>
                                        </textarea><br>  
                                        <input type="hidden" name="action" value="saveTutor">   
                                        <input type="submit" class="btn btn-primary" value="Update TUTOR Terms of Service">                   
                                    </form>
                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col-->
                    </div><!-- ./row -->

                    <div class="row" style="text-align:center;">
                             <form id="formSubmitAll" method="post" action="tos.php">
                                <input type="hidden" name="action" value="saveAll"> 
                                <input id="saTOSs" type="hidden"  name="saTOSs" value=""> 
                                <input id="saTOSt" type="hidden"  name="saTOSt"  value="">                                 
                                <input type="submit" class="btn btn-success" value="Save Changes to ALL">
                            </form>
                    </div>



                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->


        <!-- jQuery 2.0.2 -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- CK Editor -->
        <script src="../../js/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 
        <script src="../../js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>-->
        <script type="text/javascript">

            $( document ).ready( function() {
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace('TOSt');
                // Wait 1
                setTimeout( function() {                    
                    CKEDITOR.replace('TOSs');
                }, 1000);
                
                // Grab all data on Submit All
                $('#formSubmitAll').submit(function( event ) {
                    $('#saTOSs').val( CKEDITOR.instances.TOSs.getData() );
                    $('#saTOSt').val( CKEDITOR.instances.TOSt.getData() );
                    //event.preventDefault();                    
                });


            });
        </script>

    </body>
</html>