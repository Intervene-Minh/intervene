<?php include('inc/connection.php'); 
session_start();
	ob_start();
        
        //echo 'demo test landing'; die;
global $base_url;
$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
?>
<script type="text/javascript">
    var base_url = '<?php print $base_url; ?>';
</script>
<?php
	
	function curPageName() {
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	
	function isGlobalAdmin() {
		$global_admin = array(
			'mehuls85@gmail.com',
			'mhshah2@uh.edu',
			'hx.email.me@gmail.com',
		);
		
		return in_array($_SESSION['login_mail'], $global_admin) ? true : false;
	}
	
	require_once('inc/check-role.php');
	$role = checkRole();
	
	$pages = array('create-slot.php','view-slots.php','login.php', 'signup.php', 'forgot-password.php', 'login_principal.php', 'school.php', 'student_login.php', 'student_assesments.php', 'demo_login.php','demo_student_login.php');
        
        
        
        
        $data_dash_pages = array('assesment_history.php', 'result_dashboard.php', 'manage_classes.php', 'create_class.php', 'manage_assesment.php', 'give_student_access.php');
	if( !( isset($_SESSION['login_user']) && isset($_SESSION['login_id']) && isset($_SESSION['login_role'])  ) && !isset($_SESSION['demo_user_id']) ) {
		if( !in_array(curPageName(), $pages) ) {
			header("Location: login.php");
			exit;
		}
	} else {
		if( curPageName() == 'login.php' || curPageName() == 'signup.php' ) {
			header("Location: index.php");
			exit;
		}
		
		// Check if current logged in teacher is not shared any folder/subject yet
		if( $role > 0 ) {
			$check = mysql_query("SELECT * FROM `shared` WHERE `userId` = {$_SESSION['login_id']}");
			if( mysql_num_rows($check) == 0 && curPageName() != 'notify.php' && curPageName() != 'profile.php' ) {
				header("Location: notify.php");
				exit();
			} elseif( mysql_num_rows($check) > 0 && curPageName() == 'notify.php' ) {
				header("Location: index.php");
				exit();
			}
		}
	}
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
	<script type="text/javascript">
		var AMTcgiloc = "http://www.imathas.com/cgi-bin/mimetex.cgi";  		//change me
	</script>
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

<body style="<?php echo ($_SESSION['login_role'] > 0) ? "-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;" : ""; ?>">

<?php include_once("analyticstracking.php"); ?>

<div id="wrapper" class="clear fullwidth">

	<div id="header" class="clear fullwidth">
		<div class="container">
			<div class="row">
				<div class="logo">
					<h1>Intervene | Question Database</h1>
					<a href="../index.php" title="Less Test Prep"><img alt="Intervene" src="images/logo.png" /></a>
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
                  <?php 
                  if(isset($_SESSION['demo_user_id'])){ ?>
                      <div id="main-menu" class="menu main-menu">
                    <ul>
                        <?php
                        if($_SESSION['expired_user']=='expired'){
                        ?>
                        <li class="item-menu <?php echo $active['folder']; ?> "><a href="demo_user_expire.php">Home</a></li>
                        <?php }else{ ?>
                        <li class="item-menu <?php echo $active['folder']; ?> "><a href="demo_folder.php">Home</a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php  } else{ ?>              
                <div id="main-menu" class="menu main-menu">
                    <ul>
                        <li class="item-menu <?php echo $active['folder']; ?> "><a href="folder.php">Home</a></li>
                    </ul>
                </div>
                <?php } ?>
				<div class="users">
					<p>
						<?php if( isset($_SESSION['login_user']) || $_SESSION['student_id'] || $_SESSION['demo_user_id'] ) : ?>
                                                        <a href="profile.php" class="welcome">Welcome <?php if($_SESSION['student_id'] !=''){echo $_SESSION['student_name']; }elseif($_SESSION['login_user']){ echo $_SESSION['login_user'];}else{ echo $_SESSION['demo_login_user'];}?>!</a>
							<a href="logout.php" class="links"><span class="glyphicon glyphicon-arrow-right"></span> Logout</a>
						<?php elseif( !isset($_SESSION['schools_id']) ) : ?>
							<a href="login.php" class="links no_padding">Login</a>
							/
							<a href="signup.php" class="links no_padding">Sign up</a>
						<?php endif;?>
					</p>
				</div>		<!-- /.users -->
				<div class="clearnone">&nbsp;</div>
			</div>
		</div>
	</div>		<!-- /#header -->
	<?php if($role > 0) : ?>
	<div id="section-search" class="clear fullwidth">
		<div class="container">
			<div class="row">
				<?php if($_SESSION['login_status']==0):?>
					<div class="profile-item alert btn-warning text-center">
						<a style="color: #ffffff;" href="active-user.php">Please confirm your email address!</a>
						<br />
						<a href="https://intervene.io/questions/folder.php">(Click here to refresh page if you've already confirmed account)</a>
					</div>
				<?php endif;?>
                                <?php if( !in_array(curPageName(), $data_dash_pages) ){ include("search-form.php"); } ?>
			</div>
		</div>
	</div>
	<?php endif;?>

        <?php if($_SESSION['demo_user_id'] !='') { ?>
	<div id="expired-section" class="clear fullwidth">
		<div class="container">
                    
                    <?php include("search-form.php"); ?>
                    
                    
                    <!---XXX--->
                    
			<div class="row">
				 <div class="warning_heading header-warning clear" id="expired_users">
                                            <?php if($_SESSION['expired_user'] !='') { ?>
						<h3><a class="open-renewal" href="javascript:;">Trial Expired: Click Here For Full Access</a></h3>
                                            <?php } else{ ?>
                                                <h3> <a href="#" data-toggle="modal" data-target="#myModal">DEMO ACCOUNT: For full access CLICK HERE</a></h3>
                                                <div id="myModal" class="modal fade" role="dialog">
                                                    <?php
                                                    if (isset($_POST['submit-renewal'])) {
                                                        $data = $_POST['renewl'];
                                                        if($data == 'purchase_now'){
                                                            $url = "https://docs.google.com/forms/d/e/1FAIpQLSfhCNWnfrxIDxXw_Q5bgz_Jvwd7suOhj34DqBhh8eQgvKjq4Q/viewform";
                                                             header("location: $url");
                                                        }elseif($data == 'into_dist'){
                                                            header("location: send_mail.php");
                                                        }elseif($data == 'schedule_apmnt'){
                                                            $url = "https://calendly.com/intervene/purchase/01-22-2018";
                                                             header("location: $url");
                                                        }      
                                                    }
                                                    ?>
                                                    <div class="modal-dialog">
                                                      <!-- Modal content-->
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                          <h4 class="modal-title">DEMO ACCOUNT: For full access</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="model-form" name="rewal-form" action="" method="POST">
                                                                <input type="radio" name="renewl" value="purchase_now">Purchase now <br>
                                                                <input type="radio" name="renewl" value="into_dist">Introduce us to your district or administrator<br>
                                                                <input type="radio" name="renewl" value="schedule_apmnt">Schedule appointment to discuss <br>
                                                                        <input type="submit" class="btn btn-success" name="submit-renewal" value="Submit">
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                      </div>

                                                    </div>
                                                  </div>
                                                <style>
                                                    form.model-form {
                                                    width: 50%;
                                                    margin: 0px auto;
                                                    text-align: left;
                                                    }
													
                                                    </style>
                                            <?php } ?>
						
					</div>
			</div>
		</div>
	</div>
	<style>
	.sticky {
		  position: fixed;
		  top: 0;
		  width: 1170px;
		  z-index: 9999;
	}

	.sticky + .fullwidth {
	   padding-top: 102px;
	 }
	</style>
	<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("expired_users");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>
	
        <?php } ?>