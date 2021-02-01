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
				<h1>payments</h1>

			</section>

			<!-- Main content -->
			<section class="content" ng-app="p2gApp" ng-controller="paymentsCtrl">
<div class="row">
<div class="col-md-12">

<ul class="nav nav-tabs" ng-init="tab=1">
  <li role="presentation" ng-class="{active:tab==1}"><a ng-click="tab=1" >Pending</a></li>
  <li role="presentation" ng-class="{active:tab==2}"><a ng-click="tab=2">Completed</a></li>

</ul>
</div>

</div>
				<div class='row'>
					<div class='col-md-12' >
					<table class="table table-stripped">
					<thead>
					<tr><th>id</th><th>Student</th><th>Tutor</th><th>ApprovedBy</th><th>Hours</th><th>Total</th><th></th></tr>
					</thead>
					<tbody>
					<tr ng-repeat="payment in payments" ng-show="tab==1">
					<td>{{payment.ID}}</td>
					<td>{{payment.lesson.student.firstName}} {{payment.lesson.student.lastName}}<br/>
					<a href="mailto:{{payment.lesson.student.Email}}">{{payment.lesson.student.Email}}</a>
					</td>

					<td>{{payment.lesson.tutor.firstName}} {{payment.lesson.tutor.lastName}}<br/>
					<a href="mailto:{{payment.lesson.tutor.Email}}">{{payment.lesson.tutor.Email}}</a> </td>
					<td>{{payment.ApprovedBy}}</td>
					<td>{{payment.HoursToPay}}</td>
					<td>${{payment.total}}</td>

					<td><span class="btn btn-primary" ng-click="updatepayment(payment);">pay</span></td></tr>

							<tr ng-repeat="payment in paid" ng-show="tab==2">
					<td>{{payment.ID}}</td>
					<td>{{payment.lesson.student.firstName}} {{payment.lesson.student.lastName}}<br/>
					<a href="mailto:{{payment.lesson.student.Email}}">{{payment.lesson.student.Email}}</td>
					<td>{{payment.lesson.tutor.firstName}} {{payment.lesson.tutor.lastName}}<br/>
					<a href="mailto:{{payment.lesson.tutor.Email}}">{{payment.lesson.tutor.Email}} </td>
					<td>{{payment.ApprovedBy}}</td>
					<td>{{payment.HoursToPay}}</td>
					<td>${{payment.total}}</td>
					<td>{{payment.PaidOn}}</td>
					<td></td></tr>
					</tbody>
					</table>



					</div>
					<!-- /.col-->
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


	 <!-- <script type="text/javascript" src="affiliate/GRAPIJavascriptClient/geniusreferrals-api-client.js"></script> -->
	 <script type="text/javascript" src="https://template.geniusreferrals.com/bundles/portal/js/geniusreferrals-tool-box_1.1.1.js"></script>

                <script type="text/javascript" src="affiliate/config/config.js"></script>

                <script type="text/javascript" src="affiliate/public/js/jquery.paginate.min.js"></script>




	<script type="text/javascript">



        var p2g=angular.module('p2gApp',[]);
        p2g.controller('paymentsCtrl',['$scope','$http','$filter',function($scope,$http,$filter){
					// var strUsername = apiConfig.gr_username;
					// var strAuthToken = apiConfig.gr_auth_token;
					// var strAccount = apiConfig.gr_rfp_account;
					// var arrToken = [];
					// var client = new gr.client();
					// var auth = new gr.auth(strUsername, strAuthToken);

            $scope.all=[];
            $scope.paymenmts=[];
            $scope.paid=[];
			$scope.getPayments=function(){
            $http.get("<?php echo $site_name;?>/rest/payments").success(function(data){
				$scope.all=data;
				$scope.payments=$filter('filter')($scope.all,{Paid:0});
				$scope.paid=$filter('filter')($scope.all,{Paid:1});
                }).error(function(data,status){});
			};
			$scope.updatepayment=function(payment){
				payment.Paid=1;
				//processGRPayment(payment.lesson.tutor.Email,payment.total)
				  $http.put("<?php echo $site_name;?>/rest/payments/"+payment.ID,payment).success(function(data){
						postBonus(payment.lesson.student.Email,payment.SessionID);
						postBonus(payment.lesson.tutor.Email,payment.SessionID);
						$scope.getPayments();

		                }).error(function(data,status){});
				};

				$scope.getPayments();
//GR
		var postBonus=function(email,id){
	 	     var toolbox = new grToolbox();
			toolbox.processForBonus({
			"grUsername" : "pathways2greatness@gmail.com",
			"grTemplateSlug": "p2g-referrals",
			"grCustomerEmail": email,
			"grReference" : id//,  /** could be the order id, timestamp, etc. **/
		//  "grTotalOfPayments" : "", /** optional (integer) **/
		//  "grPaymentAmount" : "PAYMENT_AMOUNT" /** optional (float) **/
	});
		}

//
// // var response = client.getAdvocates(auth, strAccount, 1, 50);
// // response.success(function(data) {
// //
// // 		$.each(data.data.results, function(i, elem) {
// // 				if (typeof elem._campaign_contract === 'undefined')
// // 						campaign_contract = '';
// // 				else
// // 				{
// // 					arrToken.push(elem)
// // 					 console.log(elem.email);
// // 				}
// //
// // 		});
// // });
//
//
// processGRPayment = function(email,amount_payments){
// for(var i = 0; i < arrToken.length; i++)
// {
// if(email == arrToken[i].email){
// 	console.info(arrToken[i]);
// 	var advocate_token = arrToken[i]._advocate_referrer.token
// 	var reference = "00001"+Math.round(new Date().getTime()/1000);
// 	console.log("Pay this via GR "+advocate_token)
// 		var arrBonus = '{"bonus":{"advocate_token":"' + advocate_token + '","reference":"' + reference + '","amount_of_payments":"' + amount_payments + '"}}';
// 					var strAdvocateToken = advocate_token;
// 												var arrAdvocate = '{"currency_code":"USD"}';
// 												var objResponse4 = client.patchAdvocate(auth, strAccount, strAdvocateToken, $.parseJSON(arrAdvocate));
// 												objResponse4.success(function(data) {
// 						 var objResponse1 = client.postBonuses(auth, strAccount, $.parseJSON(arrBonus));
// 									objResponse1.success(function(data) {
//
// 											var objResponse2 = client.getBonuses(auth, strAccount, 1, 1, '', '-created');
// 											objResponse2.success(function(dataResponse2) {
//
// 													var objResponse3 = client.getAdvocate(auth, strAccount, dataResponse2.data.results[0].referred_advocate_token);
// 													objResponse3.success(function(data) {
// 			console.log("Success");
// 			/*
// 															$('#processBonusModal #status_success span#lb_status').html('Success');
// 															$('#processBonusModal #status_success span#lb_bonus_amount').html(dataResponse2.data.results[0].amount);
// 															$('#processBonusModal #status_success span#lb_advocates_referrer').html(data.data.name);
//
// 															$('#processBonusModal #container_status_success').css('display', 'block');
// 															$('#processBonusModal #container_status_fail').css('display', 'none');
// 			*/
// 													});
// 													objResponse3.fail(function(data) {
// 														console.log("Failed");
//
// 			/*
// 															$('#processBonusModal #status_fail span#lb_status').html('Fail');
// 															$('#processBonusModal #container_status_fail').css('display', 'block');
// 															$('#processBonusModal #container_status_success').css('display', 'none');
// 			*/
// 													});
// 											});
// 									});
//
// 												});
//
//
//
//
//
//
//
// }
//
// }
// }//end GR






        }]);





        </script>


</body>
</html>
