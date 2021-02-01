<?php require("config.php");?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>About Us | P2G Admin</title>
<meta
	content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
	name='viewport'>
<!-- bootstrap 3.0.2 -->
<link href="../../css/bootstrap.min.css" rel="stylesheet"
	type="text/css" />
<!-- font Awesome -->
<link href="../../css/font-awesome.min.css" rel="stylesheet"
	type="text/css" />
<!-- Ionicons -->
<link href="../../css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="../../css/AdminLTE.css" rel="stylesheet" type="text/css" />
<!-- bootstrap wysihtml5 - text editor -->
<link href="../../css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"
	rel="stylesheet" type="text/css" />

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
		<a href="../../index.php" class="logo"> <!-- Add the class icon to your logo image or logo icon to add the margining -->
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
                    <? include("menu.htm"); ?>
                </section>
			<!-- /.sidebar -->
		</aside>

		<!-- Right side column. Contains the navbar and content of the page -->
		<aside class="right-side">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>Referrer</h1>

			</section>

			<!-- Main content -->
			<section class="content" ng-app="p2gApp" ng-controller="referrerCtrl">

				<div class='row'>
					<div class='col-md-6' >
					<div class="well">
					<strong>{{referrer.first_name}} {{referrer.last_name}}</strong><br/>
					{{referrer.street}}<br/>
					{{referrer.city}}, {{referrer.state}} {{referrer.zip}}<br/>
					{{referrer.phone}}<br/>
					<a href="mailto:{{r.email}}">{{referrer.email}}</a><br/>
					<strong>Ref Code:</strong> {{referrer.code}}



					</div>

					</div>
					<div class="col-md-6">
					<div class="well">
					<table>
					<tr><th>students</th><th>{{students.length}}</th></tr>
					<tr><th>tutors</th><th>{{tutors.length}}</th><tr>
					<tr><th>invites</th><th>{{invites.length}}</th></tr>
					</table>
					
					</div>
					</div>
					<!-- /.col-->
				</div>
				<div class="row">
				<div class="col-md-12">
				<ul class="nav nav-tabs">
  <li role="presentation" ng-class="{'active':tab==1}"><a ng-click="tab=1">Invites({{invites.length}})</a></li>
  <li role="presentation" ng-class="{'active': tab==2}"><a ng-click="tab=2">Tutors({{tutors.length}})</a></li>
  <li role="presentation" ng-class="{'active':tab==3}"><a ng-click="tab=3">Students({{students.length}})</a></li>
</ul>
				<table class="table table-striped">
				<thead><tr><th>name</th><th>Created on</th></tr></thead>
				<tbody>
				<tr ng-repeat="i in invites" ng-show="tab==1"><td>{{i.FirstName}} {{i.LastName}}</td><td>{{i.created}}</td></tr>
				<tr ng-repeat="i in tutors" ng-show="tab==2"><td>{{i.FirstName}} {{i.LastName}}</td><td>{{i.CreatedOn}}</td></tr>
				<tr ng-repeat="i in students" ng-show="tab==3"><td>{{i.FirstName}} {{i.LastName}}</td><td>{{i.CreatedOn}}</td></tr>
				</tbody>
				</table>
				</div>
				</div>
				<!-- ./row -->



			</section>
			<!-- /.content -->
		</aside>
		<!-- /.right-side -->
	</div>
	<!-- ./wrapper -->


	<!-- jQuery 2.0.2 -->
	<script
		src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="../../js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../../js/angular.min.js" type="text/javascript"></script>
	<!-- AdminLTE App -->
	<script src="../../js/AdminLTE/app.js" type="text/javascript"></script>



	<script type="text/javascript">
        var p2g=angular.module('p2gApp',[]);
        p2g.controller('referrerCtrl',['$scope','$http','$filter',function($scope,$http,$filter){
            $scope.tab=1;
            $scope.referrer={};
			$scope.invites=[];
			$scope.students=[];
			$scope.tutors=[];
			$scope.getReferrer=function(){
            $http.get("<?php echo $site_name;?>/rest/referrals/referrers/<?php echo $_REQUEST['id']?>").success(function(data){
				$scope.referrer=data[0];
				$scope.getInvites();
				$scope.getTutors();
				$scope.getStudents();
			        }).error(function(data,status){});
			};
			$scope.getInvites= function(){
				 $http.get("<?php echo $site_name;?>/rest/referrals/referrers/<?php echo $_REQUEST['id']?>/invites").success(function(data){
						$scope.invites=data;
						
					        }).error(function(data,status){});
			};
			$scope.getStudents= function(){
				 $http.get("<?php echo $site_name;?>/rest/referrals/referrers/<?php echo $_REQUEST['id']?>/students").success(function(data){
						$scope.students=data;
						
					        }).error(function(data,status){});
			};
			$scope.getTutors= function(){
				 $http.get("<?php echo $site_name;?>/rest/referrals/referrers/<?php echo $_REQUEST['id']?>/tutors").success(function(data){
						$scope.tutors=data;
						
					        }).error(function(data,status){});
			};

			
				$scope.getReferrer();
        }]);



        </script>

</body>
</html>
