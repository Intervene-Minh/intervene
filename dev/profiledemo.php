<?php


include("header.php");
?>
<head>
<script>
$(function () {
      $('.navbar-toggle-sidebar').click(function () {
  		$('.navbar-nav').toggleClass('slide-in');
  		$('.side-body').toggleClass('body-slide-in');
  		$('#search').removeClass('in').addClass('collapse').slideUp(200);
  	});

  	$('#search-trigger').click(function () {
  		$('.navbar-nav').removeClass('slide-in');
  		$('.side-body').removeClass('body-slide-in');
  		$('.search-input').focus();
  	});
  });
</script>
</head>
<body>
<div style="padding:20px" class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<img src="/images/demobanner.png" alt="Smiley face">
		</div>
		
	</div>
	
	
</div>
<div style="padding-left:50px" class="container-fluid main-container">
  		<div class="col-md-2 sidebar">
  			<div class="row">
	<!-- uncomment code for absolute positioning tweek see top comment in css -->
	<div class="absolute-wrapper"> </div>
	<!-- Menu -->
	<div class="side-menu">
		<nav class="navbar navbar-default" role="navigation">
			<!-- Main Menu -->
			<div class="side-menu-container">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-plane"></span> Feedback</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-cloud"></span> Quizzes</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-signal"></span> Reports</a></li>

				</ul>
			</div><!-- /.navbar-collapse -->
		</nav>

	</div>
</div>  		</div>
    <div class="col-md-8 content">
        <div class="panel panel-default">
        	<div class="panel-heading">
        		Dashboard
        	</div>
        	<div style: height=auto class="panel-body">
        		Most Recent Feedback: <br><br>
				<img src="/images/ratings.png" alt="Smiley face"><br>
				JohnTheTutor | September 12th, 2017<br>
				<strong>"What a brilliant learner you are. I'm very proud of your hard work during our last session. Keep it up!"</strong>
				<br><br>
				<img src="/images/rating2.png" alt="Smiley face"><br>
				AdriannaRules | September 10th, 2017<br>
				<strong>"Once you got focused, there was nothing stopping you! That's what I call perseverance. Nice work!"</strong><br><br>
				Most Recent Quizzes: <br><br>

				<img src="/images/greengo.png">    90% - Real world connections - The Hypotenuse <a href="http://intervene.io/questions/testdemo.php">More Practice Problems </a><a href="http://intervene.io/demoquizreport.php">| Show Details</a>
				<br><br>
				<img src="/images/greengo.png">    85% - Finding the Hypotenuse of a Right Triangle <a href="http://intervene.io/questions/testdemo.php">More Practice Problems </a><a href="http://intervene.io/demoquizreport.php">| Show Details</a>
				<br><br>
				<img src="/images/yellowgo.png">    80% - Fluency- Using Exponents <a href="http://intervene.io/questions/testdemo.php">More Practice Problems </a><a href="http://intervene.io/demoquizreport.php">| Show Details</a>
				<br><br>
				<img src="/images/greengo.png">    90% - Geometry- Attributes of a Right Triangle <a href="http://intervene.io/questions/testdemo.php">More Practice Problems </a><a href="http://intervene.io/demoquizreport.php">| Show Details</a>
				
        	</div>
			
        </div>
  	</div>
	  <div class="col-md-2 content">
        <div class="panel panel-default">
        	<div class="panel-heading">
        		Dashboard
        	</div>
        	<div class="panel-body">
        		Lorem ipsum 
        	</div>
        </div>
  	</div>

  	</div>

<title>Morris.js Bar Chart Example</title>
</head>
<body>
  <div id="bar-example"></div>
</body>
</html>

</body>
		<!-- /#header -->
<?php include("footer.php"); ?>