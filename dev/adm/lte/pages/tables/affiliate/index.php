<?php require("../config.php");?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Referrer Activity | P2G Admin</title>
<meta
	content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
	name='viewport'>
<!-- bootstrap 3.0.2 -->
<link href="../../../css/bootstrap.min.css" rel="stylesheet"
	type="text/css" />
<!-- font Awesome -->
<link href="../../../css/font-awesome.min.css" rel="stylesheet"
	type="text/css" />
<!-- Ionicons -->
<link href="../../../css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="../../../css/AdminLTE.css" rel="stylesheet" type="text/css" />
<!-- bootstrap wysihtml5 - text editor -->
<link href="../../../css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"
	rel="stylesheet" type="text/css" />
	
<!-- GR -->

	 <link href="public/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <link href="public/bootstrap/css/jumbotron-narrow.css" rel="stylesheet">

        <link href="public/css/styles.css" rel="stylesheet">

<!-- /GR -->

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
		<a href="../../../index.php" class="logo"> <!-- Add the class icon to your logo image or logo icon to add the margining -->
			<img src="/img/logo80x120.png" width="40" height="60"> Admin
		</a>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas"
				role="button"> <span class="sr-only">Toggle navigation</span> <span
				class="icon-bar"></span> <span class="icon-bar"></span> <span
				class="icon-bar"></span>
			</a>
			<div class="navbar-right">
				<ul class="nav navbar-nav">
					<!-- User Account: style can be found in dropdown.less -->
					<li class="dropdown user user-menu"><a href="#"
						class="dropdown-toggle" data-toggle="dropdown"> <i
							class="glyphicon glyphicon-user"></i> <span><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?> <i
								class="caret"></i></span>
					</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header bg-maroon"></li>
							<!-- Menu Body -->
							<li class="user-body"></li>
							<!-- Menu Footer-->
							<li class="user-footer">

								<div class="pull-right">
									<a href="/adm/logout.php" class="btn btn-default btn-flat">Sign
										out</a>
								</div>
							</li>
						</ul></li>
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
				<div class="user-panel"></div>

				<!-- sidebar menu: : style can be found in sidebar.less -->
                    <? include("../menu.htm"); ?>
                </section>
			<!-- /.sidebar -->
		</aside>

		<!-- Right side column. Contains the navbar and content of the page -->
		<aside class="right-side">
			<!-- Content Header (Page header) -->
			<section class="content-header">

				<img id="loading-image" src="public/images/loader.gif"/>

        <div class="container">
            <div class="header">
                <ul class="nav nav-pills pull-right">
                    <li class="active"><a href="index.php">Manage advocate</a></li>
                </ul>
                <h3 class="text-muted">Manage Affiliate</h3>
            </div>

            <div class="jumbotron clearfix">
                <div class="header"><p>Make your search using this criteria</p></div>
                <form class="form-horizontal" role="form" id="form_seach_advocate">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-4 control-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputName" name="inputName" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLastname" class="col-sm-4 control-label">Last name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputLastname" name="inputLastname" placeholder="Last name">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputEmail" class="col-sm-4 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email">
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <button data-loading-text="Loading..." id="btn_search_advocate" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="no_result_found" class="alert alert-info" style="display:none">No result were found!</div>
            <div id="no_result_found" class="alert alert-warning" style="display:none">Could not get the data, please try again later!</div>
            <div id="unique_email_advocate" class="alert alert-warning" style="display:none">The email of advocate must be unique!</div>

            <div class="row marketing">
                <div style="text-align: right; margin-bottom: 10px;">
                    <button id="btn_new_advocate" type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> New advocate</button>
                </div>
                <div id="new_advocate_container" class="jumbotron clearfix" style="display: none;">
                    <div class="header">
                        <p>New Advocate</p>
                        <button type="button" class="close" id="btn_close_advocate">&times;</button>
                    </div>
                    <form class="form-horizontal" role="form" id="form_new_advocate" method="POST">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="name" class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-sm-4 control-label">Last name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <input data-loading-text="Loading..." class="btn btn-primary" type="button" value="Submit" id="btn1_new_advocate">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table_advocate">
                        <tr>
                            <th>Name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Account</th>
                            <th>Campaign</th>
                            <th>Creation date</th>
                            <th>Actions</th>
                        </tr>
                        <tbody id="lstadvocate"></tbody>
                    </table>
                    <div id="lstadvocate-pagination" style="display:none">
                        <a id="lstadvocate-previous" href="#" class="disabled">&laquo; Previous</a> 
                        <a id="lstadvocate-next" href="#">Next &raquo;</a> 
                    </div>
                </div>
            </div>

            <div class="modal fade" id="createReferralModal" tabindex="-1" role="dialog" aria-labelledby="createReferralLabel" aria-hidden="true"></div>
            <div class="modal fade" id="checkupBonusModal" tabindex="-1" role="dialog" aria-labelledby="checkupBonusLabel" aria-hidden="true"></div>
            <div class="modal fade" id="processBonusModal" tabindex="-1" role="dialog" aria-labelledby="processBonusLabel" aria-hidden="true"></div>

            <div class="footer">
                <ul class="nav nav-pills pull-left">
                    <li class="active"><a href="index.php">Manage advocate</a></li>
                </ul>

                <script type="text/javascript" src="public/js/jquery-2.0.3.min.js"></script>

                <script type="text/javascript" src="public/js/jquery.validate.min.js"></script>

                <script type="text/javascript" src="public/js/jquery.validate.defaults.js"></script>

                <script type="text/javascript" src="public/bootstrap/js/bootstrap.min.js"></script>

                <script type="text/javascript" src="public/js/date.format.js"></script>

                <script type="text/javascript" src="GRAPIJavascriptClient/geniusreferrals-api-client.js"></script>

                <script type="text/javascript" src="config/config.js"></script>

                <script type="text/javascript" src="public/js/jquery.paginate.min.js"></script>

                <script type="text/javascript" src="public/js/manage_advocate.js"></script>
            </div>
        </div> <!-- /container -->
			
			<!-- /.content -->
		</aside>
		<!-- /.right-side -->
	</div>
	<!-- ./wrapper -->




</body>
</html>











