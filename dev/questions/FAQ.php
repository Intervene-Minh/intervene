<?php session_start(); ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
    <head>
        <title>Lonestaar Prep, LLC | Home</title>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">    
        <link rel="shortcut icon" href="favicon.ico">  
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,300italic,400italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>  
        <!-- Global CSS -->
        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">   
        <!-- Plugins CSS -->    
        <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="assets/plugins/pe-icon-7-stroke/css/pe-icon-7-stroke.css"> 
        <link rel="stylesheet" href="assets/plugins/animate-css/animate.min.css">
        <!-- Theme CSS -->  
        <link rel="stylesheet" href="assets/css/styles.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head> 
 <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="custom.css">
  
    <body class="tour-page">    
        <div class="wrapper">
            <!-- ******HEADER****** --> 
            <header id="header" class="header">  
                <div class="container">            
                    <h1 class="logo pull-left">
                        <a href="index.php">
                            <span class="logo-title"><img class="img-responsive" alt="" src="assets/images/logo/loneStaar.png"></span>
                        </a>
                    </h1><!--//logo-->              
                    <nav id="main-nav" class="main-nav navbar-right" role="navigation">
                        <div class="navbar-header">
                            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button><!--//nav-toggle-->
                        </div><!--//navbar-header-->            
                        <div class="navbar-collapse collapse" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li class="nav-item"><a href="index.php">Home</a></li>
								<li class="nav-item"><a href="aboutus.php">About Us</a></li>
								<li class="active nav-item"><a href="aboutus.php">FAQ</a></li>
								<li class="nav-item"><a href="pricing.php">Pricing</a></li>
								<?php if( isset($_SESSION['login_user']) ) : ?>
								<li class="nav-item"><a href="questions/profile.php">Welcome <?php echo $_SESSION['login_user'];?>!</a></li>
								<li class="nav-item nav-item-cta last"><a href="questions/logout.php"><span class="glyphicon glyphicon-arrow-right"></span> Logout</a></li>
								<?php else: ?>
                                <li class="nav-item"><a href="questions/login.php">Log in</a></li>
                                <li class="nav-item nav-item-cta last"><a href="questions/signup.php" class="btn btn-cta btn-cta-primary">Sign up</a></li>
								<?php endif;?>
                            </ul><!--//nav-->
                        </div><!--//navabr-collapse-->
                    </nav><!--//main-nav-->           
                </div><!--//container-->
            </header><!--//header-->
            
            <!-- ******FAQ****** --> 
            <div class="container thumbnail text-center col-sm-12">
      
    <h3>Mathematics</h3>  
   
    </div>  
      
    </div>
    
  
    <div class="container">
        <div class="bs-example">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1st Grade</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse">
                
		
		<div class="panel-body">
			 <table class="table table-striped">
                    
		   <thead>
        <tr>
            <th>Standard</th>
            <th>Type</th>
            <th>Description</th>
            <th>Search</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1.1A</td>
            <td>Process</td>
            <td>Apply Mathematics to problems arising in everyday life, society, and the workplace</td>
            <td><a href="http://www.lonestaar.com" target="_blank">Find Questions</a></td>
        </tr>
        <tr>
            <td>1.1B</td>
            <td>Process</td>
            <td>Use a problem-solving model that incorporates analyzing given information, fomulating a plan or strategy, determining a solution, justifying the solution, and evaluating the problem-solving process and the reasonableness of the solution</td>
            <td><a href="http://www.lonestaar.com" target="_blank">Find Questions</a></td>
        </tr>
        <tr>
            <td>1.1C</td>
            <td>Process</td>
            <td>Select tools, including real objects, manipulatives, paper and pencil, and technology as appropriate, and techniques, including mental math, estimation, and number sense as appropriate, to solve problems</td>
            <td><a href="http://www.lonestaar.com" target="_blank">Find Questions</a></p></td>
        </tr>
	   <tr>
            <td>1.1D</td>
            <td>Process</td>
            <td>Communicate mathematical ideas, reasoning, and their implications using multople representations, including symbols, diagrams, graphs, and language as appropriate</td>
            <td><a href="http://www.lonestaar.com" target="_blank">Find Questions</a></td>
        </tr>
        <tr>
            <td>1.1E</td>
            <td>Process</td>
            <td>Create and use representations to organize, record and communicate mathematical ideas</td>
            <td><a href="http://www.lonestaar.com" target="_blank">Find Questions</a></td>
        </tr>
        <tr>
            <td>1.1F</td>
            <td>Process</td>
            <td>Analyze mathematical relationships to connect and communicate mathematicals ideas</td>
            <td><a href="http://www.lonestaar.com" target="_blank">Find Questions</a></td>
        </tr>
		    </tbody>
</table>  
		  
                </div>
		
		
            </div>
        </div>
					<?php if( isset($_SESSION['login_user']) ) : ?>
                    <a href="questions/" class="btn btn-cta btn-cta-primary">Sign me up!</a>
					<?php else : ?>
                    <a href="questions/signup.php" class="btn btn-cta btn-cta-primary">Sign up</a>
					<?php endif; ?>
					<!--
                    <button type="button" class="btn btn-cta btn-cta-primary" data-toggle="modal" data-target="#signup-modal">Sign up</button>
					-->
                </div>
            </section><!--//signup-->

        </div><!--//wrapper-->

        <!-- ******FOOTER****** --> 
        <footer class="footer">
            <div class="footer-content">
                <div class="container">
                    <div class="row">
                        <div class="footer-col col-md-5 col-sm-7 col-sm-12 about">
                            <div class="footer-col-inner">
                                <h3 class="title">About Us</h3>
                                <p>We believe that schools should have access to more affordable questions that better fit the needs of students. We are currently working only on Texas STAAR- grades 3-6 math and plan to expand our services throughout the year.</p>
                                <p><a class="more" href="#">Learn more <i class="fa fa-long-arrow-right"></i></a></p>

                            </div><!--//footer-col-inner-->
                        </div><!--//foooter-col-->
                        
                    </div><!--//row-->
                </div><!--//container-->        
            </div><!--//footer-content-->
            <div class="bottom-bar">
                <div class="container">
                    <div class="row">
                        <small class="copyright col-md-6 col-sm-6 col-xs-12">Copyright @ 2016 Lonestaar Prep, LLC </small>
                        <ul class="social col-md-6 col-sm-6 col-xs-12 list-inline">
                            <li><a href="#" ><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#" ><i class="fa fa-facebook"></i></a></li>                        
                            <li><a href="#" ><i class="fa fa-linkedin"></i></a></li>
                            <li class="last"><a href="#" ><i class="fa fa-youtube"></i></a></li>
                        </ul><!--//social-->
                    </div><!--//row-->
                </div><!--//container-->
            </div><!--//bottom-bar-->
        </footer><!--//footer-->

        <!-- Login Modal -->
        <div class="modal modal-login" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 id="loginModalLabel" class="modal-title text-center">Log in to your account</h4>
                    </div>
                    <div class="modal-body">
                        <div class="social-login text-center">                        
                            <ul class="list-unstyled social-login">
                                <li><button class="twitter-btn btn" type="button"><i class="fa fa-twitter"></i>Log in with Twitter</button></li>
                                <li><button class="facebook-btn btn" type="button"><i class="fa fa-facebook"></i>Log in with Facebook</button></li>
                                <li><button class="google-btn btn" type="button"><i class="fa fa-google-plus"></i>Log in with Google</button></li>
                            </ul>
                        </div>
                        <div class="divider"><span>Or</span></div>
                        <div class="login-form-container">
                            <form class="login-form">                
                                <div class="form-group email">
                                    <label class="sr-only" for="login-email">Your email</label>
                                    <input id="login-email" name="login-email" type="email" class="form-control login-email" placeholder="Your email">
                                </div><!--//form-group-->
                                <div class="form-group password">
                                    <label class="sr-only" for="login-password">Password</label>
                                    <input id="login-password" name="login-password" type="password" class="form-control login-password" placeholder="Password">
                                    <p class="forgot-password">
                                        <a href="#" id="resetpass-link" data-toggle="modal" data-target="#resetpass-modal">Forgot password?</a>
                                    </p>
                                </div><!--//form-group-->
                                <button type="submit" class="btn btn-block btn-cta-primary">Log in</button>
                                <div class="checkbox remember">
                                    <label>
                                        <input type="checkbox"> Remember me
                                    </label>
                                </div><!--//checkbox-->
                            </form>
                        </div><!--//login-form-container-->
                    </div><!--//modal-body-->
                    <div class="modal-footer">
                        <p>New to Less Test Prep? <a class="signup-link" id="signup-link" href="#">Sign up now</a></p>                    
                    </div><!--//modal-footer-->
                </div><!--//modal-content-->
            </div><!--//modal-dialog-->
        </div><!--//modal-->

        <!-- Signup Modal -->
        <div class="modal modal-signup" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 id="signupModalLabel" class="modal-title text-center">New to Lonestaar? Sign up now to start printing.</h4>
                        <p class="intro text-center">It takes less than 3 minutes!</p>
                        <p></p>
                    </div>
                    <div class="modal-body">
                        <div class="social-login text-center">                        
                            <ul class="list-unstyled social-login">
                                <li><button class="twitter-btn btn" type="button"><i class="fa fa-twitter"></i>Sign up with Twitter</button></li>
                                <li><button class="facebook-btn btn" type="button"><i class="fa fa-facebook"></i>Sign up with Facebook</button></li>
                                <li><button class="google-btn btn" type="button"><i class="fa fa-google-plus"></i>Sign up with Google</button></li>
                            </ul>
                            <p class="note">Don't worry, we won't post anything without your permission.</p>
                        </div>
                        <div class="divider"><span>Or</span></div>
                        <div class="login-form-container">
                            <form class="login-form">                
                                <div class="form-group email">
                                    <label class="sr-only" for="signup-email">Your email</label>
                                    <input id="signup-email" name="signup-email" type="email" class="form-control login-email" placeholder="Your email">
                                </div><!--//form-group-->
                                <div class="form-group password">
                                    <label class="sr-only" for="signup-password">Your password</label>
                                    <input id="signup-password" name="signup-password" type="password" class="form-control login-password" placeholder="Password">
                                </div><!--//form-group-->
                                <button type="submit" class="btn btn-block btn-cta-primary">Sign up</button>
                                <p class="note">By signing up, you agree to our terms of services and privacy policy.</p>
                            </form>
                        </div><!--//login-form-container-->
                    </div><!--//modal-body-->
                    <div class="modal-footer">
                        <p>Already have an account? <a class="login-link" id="login-link" href="#">Log in</a></p>                    
                    </div><!--//modal-footer-->
                </div><!--//modal-content-->
            </div><!--//modal-dialog-->
        </div><!--//modal-->

        <!-- Reset Password Modal -->
        <div class="modal modal-resetpass" id="resetpass-modal" tabindex="-1" role="dialog" aria-labelledby="resetpassModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 id="resetpassModalLabel" class="modal-title text-center">Password Reset</h4>
                    </div>
                    <div class="modal-body">
                        <div class="resetpass-form-container">
                            <p class="intro">Please enter your email address below and we'll email you a link to a page where you can easily create a new password.</p>
                            <form class="resetpass-form">                
                                <div class="form-group email">
                                    <label class="sr-only" for="reg-email">Your email</label>
                                    <input id="reg-email" name="reg-email" type="email" class="form-control login-email" placeholder="Your email">
                                </div><!--//form-group-->
                                <button type="submit" class="btn btn-block btn-cta-primary">Reset Password</button>
                            </form>
                        </div><!--//login-form-container-->
                    </div><!--//modal-body-->
                    <div class="modal-footer">
                        <p>I want to <a class="back-to-login-link" id="back-to-login-link" href="#">return to login</a></p>                    
                    </div><!--//modal-footer-->
                </div><!--//modal-content-->
            </div><!--//modal-dialog-->
        </div><!--//modal-->

        <!-- Javascript -->          
        <script type="text/javascript" src="assets/plugins/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script> 
        <script type="text/javascript" src="assets/plugins/bootstrap-hover-dropdown.min.js"></script>
        <script type="text/javascript" src="assets/plugins/jquery-inview/jquery.inview.min.js"></script>
        <script type="text/javascript" src="assets/plugins/isMobile/isMobile.min.js"></script>     
        <script type="text/javascript" src="assets/plugins/back-to-top.js"></script>
        <script type="text/javascript" src="assets/plugins/jquery-placeholder/jquery.placeholder.js"></script>    
        <script type="text/javascript" src="assets/plugins/FitVids/jquery.fitvids.js"></script>
        <script type="text/javascript" src="assets/plugins/flexslider/jquery.flexslider-min.js"></script>    
        <script type="text/javascript" src="assets/js/main.js"></script>   

        <!--[if !IE]>--> 
        <script type="text/javascript" src="assets/js/animations.js"></script> 
        <!--<![endif]-->           
    </body>
</html> 

