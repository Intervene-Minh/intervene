<?php require("config.php");?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Message Users | P2G Admin</title>
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
				<h1>Message Users</h1>

			</section>

			<!-- Main content -->
			<section class="content" ng-app="p2gApp" ng-controller="messagesCtrl">

				<div class='row'>
					<div class='col-md-12' >
					<button class="btn btn-primary" data-toggle="modal" data-target="#messageModal">Compose Message</button>  total selected: <span class="badge">{{selectedItems}}</span><br/><br/>
					
					</div>
					</div>
					<div class='row'>
					<div class='col-md-4' >
					<strong>Users:</strong> <select class="form-control" ng-model="usertype"><option value="all">All</option><option value="tutors">Tutors</option><option value="students">Students</option></select><br/><br/> 
					</div></div>
					<div class='row'>
					<div class='col-md-12' >
			<table class="table">
		<tr><td><input type="checkbox" ng-model="selectAll" ng-click="checkAll()" noicheck/></td><th>Name</th><th>email</th><th>Type</th></tr>
			
			<tr ng-repeat="item in all"><td><input type="checkbox" ng-model="item.selected" /></td><td>{{item.first_name}} {{item.last_name}}</td><td>{{item.email}}</td><td>{{item.type}}</td></tr>
			
			
		
			
			</table>

					</div>
					<!-- /.col-->
				</div>
				<!-- ./row -->

				
				
				<!-- message modal -->
			<div class="modal fade" id="messageModal" tabindex="-1" role="dialog"
				aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<form name="msgform" ng-submit="sendMessages(msg,msgform)"
							novalidate>
							<div class="modal-header">
								Send Message to {{selectedItems}} recipeints
								<button type="button" class="close" data-dismiss="modal"
									aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id="myModalLabel"></h4>
							</div>
							<div class="modal-body">
								<div class="form-group"
									ng-class="{'has-error':msgform.$submitted && msgform.subject.$invalid}">
									<label class="control-label">Subject</label> <input type="text"
										ng-model="msg.subject" name="subject" class="form-control"
										required="" /> <span id="helpBlock" class="help-block"
										ng-show="msgform.$submitted && msgform.subject.$invalid"> A
										subject is required </span>
								</div>
								<div class="form-group"
									ng-class="{'has-error':msgform.$submitted && msgform.body.$invalid}">
									<label class="control-label">Body</label>
									<textarea type="text" ng-model="msg.body" name="body"
										class="ck-editor form-control" required=""></textarea>
									<span id="helpBlock" class="help-block"
										ng-show="msgform.$submitted && msgform.body.$invalid"> A
										message body is required </span>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default"
									ng-click="closeMessagePop();">Close</button>
								<button type="submit" class="btn btn-primary">Send Message</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--  end message modal -->


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
	 <script src="../../js/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
   
	
	
	<script type="text/javascript">

	
        var p2g=angular.module('p2gApp',[]);
        p2g.controller('messagesCtrl',['$scope','$http','$filter',function($scope,$http,$filter){

            $scope.usertype="all";
            $scope.all=[];
            $scope.selectedItems=0;
            $scope.selectAll=false;
			$scope.getUsers=function(){
            $http.get("<?php echo $site_name;?>/rest/messages/"+$scope.usertype).success(function(data){
				$scope.all=data;
			        }).error(function(data,status){});
			};

			$scope.checkAll=function(){
				angular.forEach($scope.all, function(item){
				item.selected=$scope.selectAll;
				});
			};

			  $scope.closeMessagePop=function(){
		        	$scope.msg={};
					$scope.msgform.$setPristine();
					$('#messageModal').modal('hide');
		            };
			
			$scope.sendMessages=function(data,form){
				
				if(form.$valid){					
				data.users=$filter('filter')($scope.all,{selected:true});
				$http.post("<?php echo $site_name;?>/rest/messages/send",data).success(function(data){
					if(data.msg=="SUCCESS"){
						$('#messageModal').modal('hide');
							form.$setPristine();
					$scope.msg={};
					$scope.selectAll=false;
				$scope.checkAll();
						}else{
						alert(data);
							}
		        }).error(function(data,status){});
				}
				};
				$scope.$watch('usertype',function(){
					$scope.getUsers();
				},true);
				 $scope.$watch('all', function(items){
				        var selectedItems = 0;
				        angular.forEach(items, function(item){
				          selectedItems += item.selected ? 1 : 0;
				        })
				        $scope.selectedItems = selectedItems;
				      }, true);        
				$scope.$watch('selectAll',function(){
					angular.forEach($scope.all, function(item){
						item.selected=$scope.selectAll;
					});
				},true);			
				$scope.getUsers();
        }]).directive('ckEditor', [function () {
            return {
                require: '?ngModel',
                restrict: 'C',
                link: function(scope, elm, attr, ngModel) {
                  var ck = CKEDITOR.replace(elm[0]);
                  
                  if (!ngModel) return;
            
                  ck.on('pasteState', function() {
                    scope.$apply(function() {
                      ngModel.$setViewValue(ck.getData());
                        
                    });
                  });   
            
                  ngModel.$render = function(value) {
                    ck.setData(ngModel.$viewValue);
                  };
                }
            };
        }]);;

		


        </script>

</body>
</html>
