<?php
include('inc/connection.php'); 
global $base_url;
$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
?>
<script type="text/javascript">
    var base_url = '<?php print $base_url; ?>';
</script>
<?php
	session_start();
	ob_start();
	//print_r($_SESSION);
	function curPageName() {
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}

	 $str="SELECT schoollogo FROM schools WHERE Schoolid = '".$_SESSION['schools_id']."'";
	$school_logo_res = mysql_fetch_assoc(mysql_query($str));

	$logo = $school_logo_res['schoollogo'];	


	require_once"function.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Intervention Question Database</title>
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="js/tinymce/plugins/asciimath/js/ASCIIMathMLwFallback.js"></script>
	<script type="text/javascript">var AMTcgiloc = "http://www.imathas.com/cgi-bin/mimetex.cgi";</script>
	<script type="text/javascript">
		tinymce.init({
			selector: "textarea#question_question, textarea#question_question_spanish, textarea#response_answer, textarea#passage_content, textarea#passage_content_spanish, textarea#response_answer_spanish, textarea#package_description, textarea#lesson_desc",
			theme	: "modern",
			paste_data_images: true,
			plugins	: [
				"asciimath code asciisvg",
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor colorpicker textpattern imagetools"
			],
			tools	: "inserttable",
			menubar	: false,
			relative_urls : false,
			file_picker_callback: function(callback, value, meta) {
				if (meta.filetype == 'image') {
					$('#upload').trigger('click');
					$('#upload').on('change', function() {
						var file = this.files[0];
						var reader = new FileReader();
						reader.onload = function(e) {
							callback(e.target.result, {
								alt: ''
							});
						};
						reader.readAsDataURL(file);
					});
				}
			},
			toolbar1: "insertfile styleselect | bold underline italic | alignleft aligncenter alignright alignjustify | table bullist numlist indent outdent | link image",
			toolbar2: "asciimath asciimathcharmap",
			AScgiloc : 'https://intervene.io/questions/php/svgimg.php',		//change me  
			ASdloc : 'js/tinymce/plugins/asciisvg/js/d.svg',			//change me  	
			content_css: "css/content.css"
		});
		tinymce.init({
			selector: "input.answers",
			theme	: "modern",
			plugins	: [
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor colorpicker textpattern imagetools"
			],
			tools	: "inserttable",
			menubar	: false,
			relative_urls : false,
			toolbar1: "bold underline italic",
		});
	</script>
	<link type="text/css" href="css/font-awesome.min.css" rel="stylesheet" />
	<link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" />
	<link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet" />
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	<link type="text/css" href="css/form.css" rel="stylesheet" />
	<link type="text/css" href="css/front-end.css" rel="stylesheet" />
	<meta name="Description" content="We're changing the way teachers teach, one question at a time.">
	<meta name="Keywords" content="measurement worksheet, geometry worksheet, math quiz, STAAR questions, STAAR practice, place value worksheet, free worksheet, free homework, free test generator, freebie, teacher freebie, fractions worksheet, word problems, graphs">
	<!-- Quick Sprout: Grow your traffic -->
	<script>
	  (function(e,t,n,c,r){c=e.createElement(t),c.async=1,c.src=n,
	  r=e.getElementsByTagName(t)[0],r.parentNode.insertBefore(c,r)})
	  (document,"script","https://cdn.quicksprout.com/qs.js");
	</script>
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
<body style="<?php echo (isset($_SESSION['login_role']) && $_SESSION['login_role'] > 0) ? "-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;" : ""; ?>">
	<?php 
///$logo="st_logo_demo.png";
include_once("analyticstracking.php"); ?>
<script type="text/javascript" > window.ZohoHCAsap=window.ZohoHCAsap||function(a,b){ZohoHCAsap[a]=b;};(function(){var d=document; var s=d.createElement("script");s.type="text/javascript";s.defer=true; s.src="https://desk.zoho.com/portal/api/web/inapp/470696000000196001?orgId=706230762"; d.getElementsByTagName("head")[0].appendChild(s); })(); </script>
<div id="wrapper" class="clear fullwidth">
    <div id="header" class="clear fullwidth" style="height:auto;">
		<div class="container">
			<div class="row">
				<div class="logo">
					<h1>Intervene | Question Database</h1>
                                        <?php  if(!empty($logo)) { ?>
					   <img alt="Less Test Prep" src="<?php print $base_url.'uploads/schoollogo/'.$logo; ?>" height="100"  />
                                        <?php } else { ?>
                                           <h4>Less Test Prep</h4>
                                        <?php } ?>  
				</div>		<!-- /.logo -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-main"> <!-- button menu mobile --><!-- data-target -->
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php 
                    // get current file name
                    $file_name = basename($_SERVER['PHP_SELF']);
                    $first_part = explode('.', $file_name);
                    // create array file name of menu
                    $directories = array("folder");
                    // create class active for menu
                    foreach ($directories as $folder){
                        $active[$folder] = ($first_part[0] == $folder)? "current-item-menu":"";
                    }
                ?>
                
				<div class="users">
					<p>
						<?php if( isset($_SESSION['login_user']) || $_SESSION['student_id'] ) : ?>
							<a href="welcome.php" class="welcome">Welcome <?php if($_SESSION['student_id'] !=''){echo $_SESSION['student_name']; }else{ echo $_SESSION['login_user'];}?>!</a>
							<a href="logout.php" class="links"><span class="glyphicon glyphicon-arrow-right"></span> Logout</a>
						<?php elseif( !isset($_SESSION['schools_id']) ) : ?>
							<a href="login.php" class="links no_padding">Login</a>/
							<a href="signup.php" class="links no_padding">Sign up</a>
						<?php endif;?>
					</p>
				</div>		<!-- /.users -->
				<div class="clearnone">&nbsp;</div>
			</div>
		</div>
	</div>		<!-- /#header -->
	<?php

	//print_r($_SESSION);?>