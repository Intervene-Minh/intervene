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
        
         <style>
    
ul.count-date {
    display: flex;
    align-items: center;
    margin-top: 35px;
    justify-content: space-between;
    max-width: 250px;
 
}

ul.count-date li {
    width: 50px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #fff;
    border-radius: 5px;
    font-size: 18px;
    flex-wrap: wrap;
    flex-direction: column;
    font-weight: 600;
    line-height: 1;
    flex-shrink: 0;
    margin-right: 0px;
    background: #ddd;
}

ul.count-date li span {
    display: block;
    font-weight: 400;
    font-size: 12px;
}

.counting-content h3 {
    font-size: 24px;
    line-height: 1.3;
    font-weight: bold;
    margin-bottom: 10px;
}

</style>
</head>
    <?php
     require_once'newrowAPINEWFunc.php';
      $arr_board=[];
      /*Student ID*/
      $loginStudentId=$_SESSION['student_id'];

      $curr_ses_id=$_SESSION['live']['live_ses_id'];
      
      // TITU START //
      $ses_det= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_schools_x_sessions_log` WHERE id = " .$curr_ses_id));
     // TITU END //
     // echo date("Y-m-d H:i")."=".$ses_det['ses_start_time'];
       //$curr_ses_id=5487;
      /*SessION ID*/
     $Intervention_id=$curr_ses_id;

      $Room=mysql_fetch_assoc(mysql_query("SELECT * FROM newrow_rooms WHERE ses_tutoring_id= '$Intervention_id' "));

    $get_newrow_room_id=$Room['newrow_room_id'];  //Intervention_id
$str=" SELECT * FROM newrow_room_users WHERE ses_tutoring_id= '$Intervention_id' 
      AND intervene_user_id= '$loginStudentId' AND user_type= 'student' ";
      $isStudentAdded=mysql_fetch_assoc(mysql_query($str));



      if(isset($isStudentAdded)&&!empty($isStudentAdded)){

       $canGetRoomAccess=1;
      }
      else
      {

              /*ADD USERIN NEW ROW ROOM*/
               $Student2=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE id= '$loginStudentId' "));
               $StudentEmail='NewStudent_'.$Student2['id'].'@intervene.io';
               $StudentUsername='NewStudent_'.$Student2['id'].'';
               $user_arr=array('email'=>$StudentEmail,
                              'user_name'=>$StudentUsername, // UNQ
                              'first_name' =>$Student2['first_name'],
                              'last_name' =>'Student', // Student| Tutor
                              'role_type' =>'Student', // Instructor | Student
                               );

                    //  print_r($user_arr);
                      $User_ob=_create_user($role_type='student',$user_arr);
                      $newrGeneratedId=$User_ob->data->user_id;
                      $newrow_user_id=$newrGeneratedId;

                      if($newrow_user_id > 0 )
                      {

                         $stuArr=[];
                          $stuArr[]=$newrow_user_id;
                        EnrollStudnenINClass($stuArr,$get_newrow_room_id);

                         mysql_query("DELETE FROM newrow_room_users WHERE user_type='student' AND ses_tutoring_id='$curr_ses_id' AND intervene_user_id='$loginStudentId' ");

                          $sql="INSERT INTO newrow_room_users SET newrow_user_id='$newrow_user_id', intervene_user_id='$loginStudentId',user_type='student',ses_tutoring_id='$curr_ses_id', created_at='$today',  tp_id='$curr_ses_id',newrow_room_id='$get_newrow_room_id'";

                          mysql_query($sql);

                          $str="SELECT * FROM newrow_students WHERE stu_intervene_id= '$loginStudentId'";
                          $qr=mysql_query($str);
                          $StudentExist=mysql_num_rows($qr);
                          if($StudentExist > 0 ){

                            $sql="UPDATE newrow_students SET  newrow_ref_id='$newrow_user_id',newrow_email='$StudentEmail',newrow_username='$StudentUsername' WHERE stu_intervene_id=".$loginStudentId;
                            mysql_query($sql);
                            $ref=mysql_affected_rows();
                             if($ref >0 ){
                              
                              $canGetRoomAccess=1;

                               }
                             }else{ 
                              $sql="INSERT INTO newrow_students SET stu_intervene_id='$loginStudentId',newrow_ref_id='$newrow_user_id',newrow_email='$StudentEmail',newrow_username='$StudentUsername'";
                                mysql_query($sql);
                                $lastID= mysql_insert_id();
                                if($lastID >0 ){
                                  $canGetRoomAccess=1;
                                }
                                 }
    }
     }

/*END ELSE BLICK*/


      $arr_board['newrow_room_id']=$get_newrow_room_id;

      $Student_newrow=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_students WHERE stu_intervene_id= '$loginStudentId'"));

      $arr_board['newrow_user_id']=$Student_newrow['newrow_ref_id'];


      $arr_board['live_tutoring_id']=$Intervention_id;
      //////Get ROOM Link ///////////////////////////
      if($canGetRoomAccess > 0)
          {

        $n_room_id=$arr_board['newrow_room_id']; 
        $n_user_id=$arr_board['newrow_user_id'];
      // $n_user_id=2746608 ;
        $token=$currToken;  //Get token
       $Api_url='https://smart.newrow.com/backend/api/rooms/url/'.$n_room_id.'?user_id='.$n_user_id;
        $ch = curl_init($Api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Set your auth headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  'Content-Type: application/json',
                  'Authorization: Bearer ' . $token
                ));
      // get stringified data/output. See CURLOPT_RETURNTRANSFER
    $data = curl_exec($ch);
    $Board=json_decode($data);
    $info = curl_getinfo($ch);
    curl_close($ch);
  }
    ?>
<body style="<?php echo (isset($_SESSION['login_role']) && $_SESSION['login_role'] > 0) ? "-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;" : ""; ?>">
	<?php 
///$logo="st_logo_demo.png";
include_once("analyticstracking.php"); ?>
<script type="text/javascript" > window.ZohoHCAsap=window.ZohoHCAsap||function(a,b){ZohoHCAsap[a]=b;};(function(){var d=document; var s=d.createElement("script");s.type="text/javascript";s.defer=true; s.src="https://desk.zoho.com/portal/api/web/inapp/470696000000196001?orgId=706230762"; d.getElementsByTagName("head")[0].appendChild(s); })(); </script>
<div id="wrapper" class="clear fullwidth">
    <div id="header" class="clear fullwidth" style="height:auto;">
		<div class="container">
			<div class="row">
                            <div class="col-md-4 col-xs-4">
				<div class="logo">
					<h1>Intervene | Question Database</h1>
                                        <?php  if(!empty($logo)) { ?>
					   <img alt="Less Test Prep" src="<?php print $base_url.'uploads/schoollogo/'.$logo; ?>" height="60px"  />
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
                                </div>
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
                             <div class="col-md-5 col-xs-8">    
                                <div class="counting-date" >




              <?php if(strtotime($ses_det['ses_end_time']) > time()) {  ?>
                            <ul class="count-date" style="margin-top: 10px">
               <li id="timer_days" style="color:blueviolet;float: left"></li>
              <li id="timer_hours" style="color:blueviolet;float: left"></li>
              <li id="timer_mins" style="color:blueviolet;float: left"></li>
              <li id="timer_secs" style="color:blueviolet;float: left"></li>
              </ul>
              <?php } ?>

              &nbsp;&nbsp;Minutes remaining in session
            </div>
                                 </div>
                 <div class="col-md-3 col-xs-12">  
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
				</div>	
                     </div>	<!-- /.users -->
				<div class="clearnone">&nbsp;</div>
			</div>
		</div>
	</div>		<!-- /#header -->
	<?php

	//print_r($_SESSION);?>
 
        