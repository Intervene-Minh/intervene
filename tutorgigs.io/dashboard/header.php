<?php 
session_start();ob_start();
define("TUTOR_BOARD", "groupworld");
$home_url="https://tutorgigs.io/"; 
include('inc/connection.php'); 
include('inc/public_inc.php'); 
require_once('inc/check-role.php');

$calendly_url='https://calendly.com/tutorgigs';
 //print_r($_SESSION);
// Signup State Global//
 $step_1_url='application.php';
 $step_2_url='quiz.php'; //QUIZ Button
 $step_3_url='interview.php'; //interview
 $step_4_url="application_status.php"; //Quiz and interview status Show or Out from application status

// Signup State Global//

/////////////////

	$role = checkRole();
	$tutor_regiser_page=(isset($_SESSION['ses_curr_state_url'])&&!empty($_SESSION['ses_curr_state_url']))?$_SESSION['ses_curr_state_url']:"application.php";
	 //Commmon to all page

        
        //echo 'demo test landing'; die;
global $base_url;
$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';

/// BasicSet

	
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
	
	
	
        
        
	$pages = array('create-slot.php','view-slots.php','login.php', 'signup.php', 'forgot-password.php', 'login_principal.php', 'school.php', 'student_login.php', 'student_assesments.php', 'demo_login.php','demo_student_login.php');
        
       
        $data_dash_pages = array('manage_classes.php', 'create_class.php', 'manage_assesment.php', 'give_student_access.php');
	
        
     if( curPageName() == 'login.php' || curPageName() == 'signup.php' ) {
			header("Location:".$home_url);
			exit;
		}   
        
        
            //    var_dump($_SERVER) ; 

        $tutorId=$_SESSION['ses_teacher_id'];
 $sql="SELECT * FROM `notifications` WHERE `receiver_id` =".$tutorId;
 $sql.=" ORDER BY created_at DESC ";
 $result= mysql_query($sql);
    $data=array();
    $total=mysql_num_rows($result);
   if($total>0){
    while ($row=mysql_fetch_assoc($result)){
      //  id, sender_type info url time
      $data[]=array('text' =>$row['info'] ,'name' =>'Admin' ,'image' =>'ap.png'  );
      // text name  image


  

    }
   }

   $json=json_encode($data);// JSON_NUMERIC_CHECK
  // echo $json; die; 
       
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Teacher-Tutorgigs Dashboard </title>
    <script type="text/javascript">
    var base_url = '<?php print $base_url; ?>';
   
</script>
        
        
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
	<!-- <script src="js/jquery.steps.js"></script> -->
	<!-- <script src="js/main.js"></script>   -->
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
	<link type="text/css" href="css/roboto-font.css" rel="stylesheet" />



	<link rel="stylesheet" type="text/css" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">
                  <!--  Notification css -->
       <link type="text/css" href="css/content_notify.css" rel="stylesheet" />           

		<meta name="Description" content="We're changing the way teachers teach, one question at a time.">
		<meta name="Keywords" content="measurement worksheet, geometry worksheet, math quiz, STAAR questions, STAAR practice, place value worksheet, free worksheet, free homework, free test generator, freebie, teacher freebie, fractions worksheet, word problems, graphs">
	<!-- Quick Sprout: Grow your traffic -->
    <?php
    if($_SESSION['ses_access_website']=='yes'){   

    // $_SESSION['ses_access_website']=="no"){?>
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
<script>

      
   </script>

<script>

///var cc='<?php echo $json;?>';

 var url_1="https://tutorgigs.io/dashboard/notify_refresh_top.php";  

      $.ajax({ 
            type: 'GET', 
            url: url_1, 
            data: { get_param: 'value' }, 
            dataType: 'json',
            success: function (data) { 
              //  alert(data);
            //console.log(data);  //exit();    
            var str='';
            //alert(data[0].image);
            for(i=0;i<data.length;i++){
            console.log(data[i].text+"Data::"+i+"<br/>");
           }
        
      
    
        // $(".last-updated").append(data.data.updated);
        // $(".aqiStatus").html(data.data.text); 
        }
    });    


var initial_data='';

var notifications= [
    {
        text : 'commented on an item you liked',
        name : 'A1',
        image : 'a.png'
    },
    {
        text : 'shared a post',
        name : 'User2',
        image : 'a.png'
    },
    {
        text : 'liked your post',
        name : 'User3',
        image : ''
    }
];


$(document).ready(function(){

      

    // this is a self-invoked function called initially to render the notification data, checks if any data present in the local storage, otherwise renders the new data from array "initial_data"
    (function(){

        if(localStorage['notification']){
           var  data = notifications
         //var data = JSON.parse(localStorage['notification'])
        }else{
            data = initial_data
           // console.log(initial_data);
        }
  
        var count = 0

        for(var a=0;a<data.length;a++) {
            var item = data[a]
            var html = $('<div class="list-item noti">\
                            <div class="text fl"><b class="name fl">' + item['name'] + '::-view</b>' + item['text'] + '</div>\
                        </div>');
            $(html).data('notification', item)
//            console.log(item['read'])
            if(!item['read']){
                count = count + 1
            }else{
                $(html).addClass('background-white')
            }
            $(document).find('.notification-dropdown .items').append(html)
        }
//        $(document).find('.notify-count .value').text(count)
//        $(document).find('.notify-count').attr('count',count);
        //stop  pervious count
       // set_notification(0)
                                                 // sets the notification counter
        //save_localdata()                                                // updates local storage

    }); //(); 


///////////Push notification in 3second///////////////
    process_notify();
//var url_1="https://tutorgigs.io/dashboard/notify_refresh_top.php"; 
function process_notify(){
  console.log('process_notify===');// process_notify

    var url_1="https://tutorgigs.io/dashboard/notify_refresh2.php";  

      $.ajax({ 
            type: 'GET', 
            url: url_1, 
            data: { get_param: 'value' }, 
            dataType: 'json',
            success: function (data) { 
            console.log(data.tot_unread+'=tot_unread');  //exit();    
            var str='';
           
             //alert(data.content[0].text);// format
            for(i=0;i<data.content.length;i++){
            
            var detail_url='notifiy_clear.php?ac=clear_all&type=message';  // .php?id=1187
            var  set_class='list-item noti message';
            if(data.content[i].type=='jobs'|| data.content[i].type=='job_changed'){
                //var detail_url='Jobs-Board-List.php?id='+data.content[i].type_id;
                var detail_url='notifiy_clear.php?ac=jobs&id='+data.content[i].type_id;
                if(data.content[i].type=='job_changed'){
                    var detail_url='notifiy_clear.php?ac=job_changed&id='+data.content[i].type_id;
                }

                var  set_class='list-item noti jobs';
            }
            /// message


              str+='<div class="'+set_class+'"><a href="'+detail_url+'">\
                            <div class="text fl"><b class="name">' +data.content[i].type+ '-</b>' + data.content[i].text + '</div></a>\
                        </div>';

            //console.log(data[i].text+"Data::"+i+"<br/>");
           }
           set_notification(data.total);  // total, tot_unread
           // Display modal
           $("#items_id").html(str);
          // $("#myModal").modal('show'); 


        
      
    
        // $(".last-updated").append(data.data.updated);
        // $(".aqiStatus").html(data.data.text); 
        }
    }); 

       setTimeout(function(){
      process_notify();},3000);

}
    /// 1





    // click on notification bell
    $('.notification').click(function(){
        if(!$(document).find('.notification-dropdown').hasClass('dd')){
            hide_dropdown()
        }else{
            $('.notification-dropdown').removeClass('dd').addClass('dropdown-transition')
//            $(document).find('.notify-count .value').text(0)
//            $(document).find('.notify-count').attr('count',0)

           // set_notification(999);// Set notification cleared
            //save_localdata()
        }
    })


    // handler to close dropdown on clicking outside of it
    $(document).on('click',function(e){
        var target = $(e.target)
        if(!target.closest('.notification').length && !target.closest('.dropdown-transition').length){
            if(!$(document).find('.notification-dropdown').hasClass('dd')){
                hide_dropdown()
            }
        }
    })

    // function to close dropdown and setting notification count to 0
    function hide_dropdown(){
        $(document).find('.notification-dropdown').removeClass('dropdown-transition').addClass('dd')
        $(document).find('.notification-dropdown').find('.list-item').addClass('background-white')
//        $(document).find('.notify-count .value').text(0)
//        $(document).find('.notify-count').attr('count',0)
      //  set_notification(0)
       // save_localdata()
    }




    // timer function toh trigger push notification in every 5 second
    




    // function to update local storage
    function save_localdata(){
        var list = []

        $(document).find('.notification-dropdown .items .list-item').each(function(i,e){
            var data = $(this).data('notification')
            var new_data = {
                text : data['text'],
                name : data['name'],
                image : data['image']
            }
            if($(this).hasClass('background-white') || !$(document).find('.notification-dropdown').hasClass('dd')){
                new_data['read'] = true
            }else{
                new_data['read'] = false
            }
//            console.log(new_data['read'])
            list.push(new_data)
        })
        localStorage.removeItem('notification')
        localStorage.setItem("notification", JSON.stringify(list))

    }


    //function to update notification counter
    function set_notification(count){
        $(document).find('.notify-count').attr('count',count)
        $(document).find('.notify-count .value').text(count)

        if(count > 0){
            document.title = '('+count+') - New notification'                // adding notification count in window title, incase the user is on some other tab.
        }else{
            document.title = 'New notification'
        }
    }

})

</script>
 <?php  } ?>
<style type="text/css">
    .jobs{
         background-color:orange;
    }

    .message{
        text-align: right; background-color:#18B5B5;
    }
</style>
</head>

<body style="<?php echo ($_SESSION['login_role'] > 0) ? "-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;" : ""; ?>">

<?php #include_once("analyticstracking.php"); ?>
<?php  
                $profile_url="profile.php";
                if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
                    $profile_url="javascript:void(0);";
                }

                ?>

<div id="wrapper" class="clear fullwidth">

    
    
	<div id="header" class="clear fullwidth">
		<div class="container">
			<div class="row">
				<div class="logo">
					<h1> Question Database</h1>
					<a href="<?=$home_url?>" title="Less Test Prep"><img alt="Intervene" src="images/logo.png" /></a>
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
                  
                      
                                
                                
                                
                <div id="main-menu" class="menu main-menu">
                    <ul title="Go, Home">
                        <li class="item-menu <?php echo $active['folder']; ?> ">
                            <a href="../index.html">Home</a></li>
                    </ul>
                </div>
                <?php if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="yes"){ ?>

                <div class="notification">
					<i class="fa fa-bell-o"></i>
					<div class="notify-count count1 common-count" count="0">
						<div class="value">9</div>
					</div>
				</div>
				<div class="notification-dropdown dd">
					<div class="arrow-up"></div>
					<div class="header">
						<div class="container">
							<div class="text fl"><a href="notifications.php">Notifications</a></div>
							<div class="notify-count common-count count2 fl" count="0">
								<div class="value">0</div>
							</div>
						</div>
					</div>
					<div class="items" id="items_id">

					</div>
				</div>
                 <?php } //if(isset("yes"){ ?>  

				<div class="users">
                
					<p>
					  <a href="<?=$profile_url?>" class="welcome">Welcome,
                                             <?php if(!empty($_SESSION['login_user'])) echo $_SESSION['login_user'];?>!
                                                            
                                                           
                                                            
                                                           </a>
							<a href="logout.php" class="links">
                                                            <span class="glyphicon glyphicon-arrow-right"></span> Logout</a>	
					</p>
				</div>		<!-- /.users -->
				<div class="clearnone">&nbsp;</div>
			</div>
		</div>
	</div>		<!-- /#header -->
	
            
            
        <!-- /#Search section -->    
            <?php if($role > 0): ?>
	<div id="section-search" class="clear fullwidth">
		<div class="container">
			<div class="row">
				<?php //if($_SESSION['login_status']==0):?>
<!--					<div class="profile-item alert btn-warning text-center">
						<a style="color: #ffffff;" href="active-user.php">Please confirm your email address!</a>
						<br />
						<a href="#">(Click here to refresh page if you've already confirmed account)</a>
					</div>-->
				<?php //endif;?>
                            
                            
                            

			</div>
		</div>
	</div>
	<?php endif;?>
        
        

        