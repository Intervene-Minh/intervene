<?php require("config.php");?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Surveys | P2G Admin</title>
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
				<h1>Surveys</h1>

			</section>

			<!-- Main content -->
			<section class="content" ng-app="p2gApp" ng-controller="surveysCtrl">
				<div class="row">
					<div class="col-sm-12">
						<form name="surveyForm" class="form-inline"
							ng-submit="addSurvey(survey,surveyForm);">
							<div class="form-group">
								<label>Add New Survey</label> <input type="text"
									class="form-control"
									style="width: auto; vertical-align: middle;"
									ng-model="survey.link" name="survey.link" />
							</div>
							<div class="form-group">
							<select name="audience" class="form-control" ng-model="survey.audience" ng-init="survey.audience='Tutor'"><option value="Tutor">Tutors</option><option value="Student">Students</option>
							
							</select>
							</div>
							<button class="btn btn-success" type="submit">Add</button>

						</form>
					</div>
					<br/>
				</div>
<br/>
				<div class="row">
					<div class="col-sm-6">
						<table class="table table-striped">
						<tr><th>Link</th><th>Audience</th><th></th></tr>
							<tr ng-repeat="s in all">
								<td>{{s.link}}</td><td>{{s.audience}}</td>
								<td><button class="btn btn-danger"
										ng-click="deleteSurvey(s.id);">Delete</button></td>
							</tr>


						</table>
					</div>
				</div>



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
        p2g.controller('surveysCtrl',['$scope','$http','$filter',function($scope,$http,$filter){
            $scope.all=[];
             
			$scope.getSurveys=function(){
            $http.get("<?php echo $site_name;?>/rest/surveys").success(function(data){
				$scope.all=data;
			        }).error(function(data,status){});
			};

			$scope.deleteSurvey=function(id){
				$http.delete("<?php echo $site_name;?>/rest/surveys/"+id).success(function(data){
					$scope.getSurveys();
		        }).error(function(data,status){});
				};
			$scope.addSurvey=function(data,form){
				if(form.$valid){
					$http.post("<?php echo $site_name;?>/rest/surveys",data).success(function(data){
						if(data.msg=="SUCCESS"){
							$scope.getSurveys();
							}else{
							alert(data);
								}
			        }).error(function(data,status){});
					}
			};
				$scope.getSurveys();
				
        }]);
			


        </script>

</body>
</html>
