<?php
session_start(); 
ob_start();
$current_page = basename($_SERVER['REQUEST_URI']);
// add current url active
if($current_page==''){
	$current_page = 'index.php';
}
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--  -->
        <!--    Document Title-->
        <!-- =============================================-->
        <title>Intervene</title>
        <!--  -->
        <!--    Favicons-->
        <!--    =============================================-->
        <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png">
        <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicons/favicon.ico">
        <link rel="manifest" href="assets/images/favicons/manifest.json">
        <link rel="mask-icon" href="assets/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileImage" content="assets/images/favicons/mstile-150x150.png">
        <meta name="theme-color" content="#ffffff">
        <!--  -->
        <!--    Stylesheets-->
        <!--    =============================================-->
        <!-- Default stylesheets-->
        <link href="assets/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Template specific stylesheets-->
        <link href="assets/lib/loaders.css/loaders.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700|Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link href="assets/lib/iconsmind/iconsmind.css" rel="stylesheet">
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
        <link href="assets/lib/hamburgers/dist/hamburgers.min.css" rel="stylesheet">
        <link href="assets/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="assets/lib/owl.carousel/dist/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="assets/lib/owl.carousel/dist/assets/owl.theme.default.min.css" rel="stylesheet">
        <link href="assets/lib/remodal/dist/remodal.css" rel="stylesheet">
        <link href="assets/lib/remodal/dist/remodal-default-theme.css" rel="stylesheet">
        <link href="assets/lib/flexslider/flexslider.css" rel="stylesheet">
        <link href="assets/lib/lightbox2/dist/css/lightbox.css" rel="stylesheet">
        <!-- Main stylesheet and color file-->
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/custom.css" rel="stylesheet"> 
        
        		<!-- Quick Sprout: Grow your traffic -->
		<script>
		  (function(e,t,n,c,r){c=e.createElement(t),c.async=1,c.src=n,
		  r=e.getElementsByTagName(t)[0],r.parentNode.insertBefore(c,r)})
		  (document,"script","https://cdn.quicksprout.com/qs.js");
		</script>

		<script
		  src="https://code.jquery.com/jquery-1.12.4.min.js"
		  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
		  crossorigin="anonymous"></script>
  
		<!-- End Quick Sprout -->
		
		<!-- Hotjar Tracking Code for www.intervene.io -->
		<script>
			(function(h,o,t,j,a,r){
				h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
				h._hjSettings={hjid:544568,hjsv:5};
				a=o.getElementsByTagName('head')[0];
				r=o.createElement('script');r.async=1;
				r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
				a.appendChild(r);
			})(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
		</script>

        
        </head>
    <body data-spy="scroll" data-target=".inner-link" data-offset="60">
        <main>

            <!-- <div class="loading" id="preloader">
                <div class="loader h-100 d-flex align-items-center justify-content-center">
                    <div class="line-scale">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div> -->

</main>
</body>
            
            <section class="background-primary py-3 d-none d-sm-block">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-auto d-none d-lg-block">
                            <span class="fa fa-map-marker color-white fw-800 icon-position-fix"></span>
                            <p class="ml-2 mb-0 fs--1 d-inline color-white fw-700">Houston, Texas</p>
                        </div>
                        <div class="col-auto">
                            <span class="fa fa-phone color-white fw-800 icon-position-fix"></span>
                            <a class="ml-2 mb-0 fs--1 d-inline color-white fw-700" href="tel:8553453276">855-34-LEARN (53276)</a>
                        </div>
                    </div>
                    <!--/.row-->
                </div>
                <!--/.container-->
            </section>
            <div class="znav-white znav-container sticky-top navbar-elixir" id="znav-container">
                <div class="container">
                         <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand overflow-hidden pr-3" href="index.php">
                            <img src="assets/images/Intervene new.png" alt="Logo" style="height:40px" />
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <div class="hamburger hamburger--emphatic">
                                <div class="hamburger-box">
                                    <div class="hamburger-inner"></div>
                                </div>
                            </div>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav fs-0 fw-700">
                                <li>
                                    <a href="JavaScript:void(0)">Our Solutions</a>
                                    <ul class="dropdown fs--1">
                
                                        <li>
                                            <a href="tutoring.php">Online Tutoring</a>
                                        </li>
										<li>
                                            <a href="datadash.php">DataDash - Online Formative Assessments</a>
                                        </li>
											<li>
                                            <a href="https://www.intervene.io/smartprep/index.php">SmartPrep - STAAR Question Database</a>
                                        </li>
												<li>
                                            <a href="telpaspro.php">Telpas Pro</a>
                                        </li>
                                    <!--    <li>
                                            <a href="virtualclass.php">Virtual Classrooms</a>
                                        </li>-->
                          			
                                    </ul>
                                </li>
                                <li>
                                    <a href="about.php">About Us</a>
                                </li>
								 <li>
                                    <a href="testimonial1.php">Testimonials</a>
                                </li>
                                <li>
                                    <a  href="https://docs.google.com/forms/d/e/1FAIpQLSeoFbdQotNG4Z8CdHxthdwEf18rGpotE9qgR-thzSqZ59pY0g/viewform?vc=0&c=0&w=1">Contact Us</a>
                                </li>
                             
                            </ul>
                            <ul class="navbar-nav ml-lg-auto">
<!-- Conference Sign Up Button                            
                                <li>
                                    <a class="btn btn-outline-primary btn-capsule btn-md border-2x fw-700" href="https://docs.google.com/forms/d/1IEmYs1CKzqlh6J-aavOYnCPcMTWlQ8sk-DlyES1G1nA" target="_blank">Conference Sign-Up</a>
                                </li>    
-->                        	
                                <li>
                                    <a class="btn btn-outline-primary btn-capsule btn-md border-2x fw-700" href="https://intervene.io/questions/login.php" target="_blank">Log In</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
    </html>
