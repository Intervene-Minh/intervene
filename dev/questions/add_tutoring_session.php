<?php  
   /*
   $Arr_ses_type=array( 'homework' , 'intervention', 'drhomework');
   @ Step 2: 
   -- Complete with 3 image 
   -- or 1- 2 assignment in session. 
   
   ==================
   assigning_lesson_opn [assignments , file_uploads]
   assignments >> Go to 2nd step page 
   [if not complete add session to interven on 2nd page otherwise delete whole sesison-data. . ]
   
   file_uploads >>it create session on same page. 
   
   
   
   $Sql_sesionX="   INSERT INTO dr_sessions SET board_type='groupworld',type='drhomework',ses_start_time='2019-09-04 01:00',ses_end_time='2019-09-04 01:50',start_date='2019-09-04 00:00:00', school_id='28',lesson_id='11',quiz_id='4',grade_id='22', created_date='2019-09-04 10:43:31',teacher_id='1999',district_id='534' ";
   --- SessionStartTime > CurrentDateTime. [2 hr atleast. in future. ]
   ========================
   //print_r($_SESSION); die; 
   @Add- assigment update link
   @Uploaded File link to Tutoring 
   
   **/
   
   
   
   
   
   
   // @session_start();
   // $today = date("Y-m-d H:i:s"); // 
   
   
    include('../config/apple.php'); // curl.functions.php
    include('curl.functions.php');
    include "common.inc.php";
   
    $Step_2_url='select.assignments.php';
    $curDateTime=$today;
    //echo '===' , $curDateTime; 
   ////////////////////////
   if(isset($show_msg)&&!empty($show_msg)){
     echo $show_msg;
   }
   
   
   
   
   
       // $Pdf_arr=array();
       // while($row = $res_sql->fetch_assoc()){  
       // print_r($row); die;
        
       // }
   
   
   ///////////////////////////////
   
   
   $postFields = [
       'Tutoring_client_id' =>"Drhomework123456", //For All
       'drhomework_ref_id' =>999,
       'board_type' =>'groupworld', //fixed
        'type' => 'drhomework' ,
        'ses_start_time' => '2019-09-15 13:15:00' ,
        'ses_end_time' => '2019-09-15 14:15:00' ,
        'district_id' => 101 ,
        'grade_id' => 101 , // DrgradeID
   
       
       
       
       //'otherField'   => 1,
   ];
   
   
     $apiUrl='https://intervene.io/dataapi/index.php/ProjectController/CreateTutoringSessions';
   
   
   
     $SesEndInterval=50; // Min. 
   
     $parentId=$_SESSION['ses_admin_teacher_id'];  // LoginUesr
   
   
     
       //print_r($_SESSION); 
   
   
     $Next_url="https://drhomework.com/parent/my-tutoring-sessions/";
     ///////////////////////
   
    if(isset($_POST['add_session'])){
   
   
      $input=array();$warning_msg=[]; 
   
        // $warning_msg[]='Please Select required Field!!   ';
   
        if(!isset($_POST['student']))
          $warning_msg[]='Select Student(1-4)! ';
   
        if(isset($_POST['student'])&&is_array($_POST['student'])&&count($_POST['student'])>4)
          $warning_msg[]='1-4 students allowed only! ';
         # grade
   
        if(!isset($_POST['grade']))
          $warning_msg[]='Grade required! ';
   
   
         if(!isset($_POST['assigning_lesson_opn']))
          $warning_msg[]='Please select 1-option, for assigning lesson! ';
   
   
   
          
   
   
   
   
   
        //echo '<pre>' ; print_r($_POST); die; 
      
       //if(isset(var))
   
   ##################################################################
   
      $assignments_opn='no';$assigning_lesson_opn='';
   
   if(isset($_POST['assigning_lesson_opn'])&&!empty($_POST['assigning_lesson_opn'])){
       $assigning_lesson_opn= $_POST['assigning_lesson_opn'];
   
       if($_POST['assigning_lesson_opn']=='assignments')
         $assignments_opn='yes';
   }
   
   
     if(empty($warning_msg)){
   
   
    for($i=0;$i<count($_POST['hour']);$i++){
   
    $selected_date= (!empty($_POST['select_date'][$i])) ?$_POST['select_date'][$i]: date("Y-m-d"); 
   
       $session_date_ymd=date('Y-m-d H:i:s', strtotime($selected_date));  // 2012-04-20
      
      
       //echo $_POST['hour'][$i],'::',$_POST['minnute'][$i],$_POST['hr_type'][$i];
   
   
             $ii = 0;   $hh= $_POST['hour'][$i];
   
         if(!empty($_POST['hour'][$i])&&$_POST['hr_type'][$i]=="pm"&&$_POST['hour'][$i]<12)
          $hh= $_POST['hour'][$i]+12; // H 24 form
          elseif(!empty($_POST['hour'][$i])&&$_POST['hr_type'][$i]=="am"&&$_POST['hour'][$i]==12){
            $hh= $_POST['hour'][$i]-12;//Break not allowed 12am session
          }
   
   
          // 6 am to 9 am :: allowed only.
        
   
         if (!empty($_POST['minnute'][$i]))
            $ii = $_POST['minnute'][$i];
          //StartTime ,EndTime
           $start_time= date('Y-m-d H:i', strtotime('+' . $hh . 'hour +' . $ii . ' minutes', strtotime($session_date_ymd))); # add Hour, min.::session_start_time
   
            $futureTimeDiff=strtotime($start_time)-strtotime($curDateTime); // >=1 (Must)# f-1750 
   
             // echo  'curDateTime>' , $curDateTime , '<br/>' ;
             // echo '==========start_time='.$start_time;
            
   
              
   
               if($futureTimeDiff<1800){ // 3600::1 Hour Back from current time
                 $warning_msg[]=$start_time.', Past Date&Time not allowed!,Current Time:'.$curDateTime;
                 break;  // Session not allowd  in_back_hr.
                //  30 min. back time for CST not allowed to create session . 
               }
   
               ///// 6 AM to 9 PM ONly/////
           //      if ($_POST['hr_type'][$i] == "am" && ($_POST['hour'][$i] < 6 || $_POST['hour'][$i]== 12)) {
           // $warning_msg[]= "Session time must be in between 6 AM to 9 PM.<br/>";
           //    break; //$can_create = 0;
           //  }
   
           //   if ($_POST['hr_type'][$i] == "pm" && ($_POST['hour'][$i] > 9 && $_POST['hour'][$i] != 12)) {
           //    $warning_msg[]= "Session time must be in between 6 AM to 9 PM.<br/>";
           //               break; //  $can_create = 0;
           //      }
   
   
   
          // echo  $session_date_ymd.'session_date_ymd===========start_time'.$start_time;  die ;
   
       $end_time= date('Y-m-d H:i', strtotime('+50 minutes', strtotime($start_time)));
       // $arr['lesson']['end_time']=$end_time;
   
         $ses_lesson_id=99;//for each session.
   
         // $arr['lesson'][]=array('les_id'=>$_POST['lesson'][$i],
         //                                    'start_time'=>$start_time,
         //                                    'end_time'=>$end_time);
           // $parentid=0;
   
         $curr_board='groupworld';
   
           $parentid=999;
           $school_id=99;
            $district_id=9; 
             $quiz_id=99;  //NO quiz in Dresssion.
   
          $sql_session= " INSERT INTO dr_sessions SET board_type='$curr_board',type='drhomework', ses_start_time='$start_time', ";
   
                               $sql_session.=" parent_id='$parentId' ,ses_end_time='$end_time',start_date='$session_date_ymd', ";
   
                               $sql_session.= "school_id='$school_id',quiz_id='$quiz_id',grade_id='".$_POST['grade']."', ";
                               ## 
                               $sql_session.= "assigning_lesson_opn='$assigning_lesson_opn',have_assignments='$assignments_opn', ";
   
                                 if(!empty($_POST['special_notes']))
                                 $sql_session.= "special_notes='".addslashes($_POST['special_notes'])."', ";
   
                               $sql_session.= "created_date='$today',district_id='$district_id' ";
   
                           // echo '<pre>',$sql_session; die;
   
   
   
                      }
   
                       $Add=$db->query($sql_session); 
                        if($Add){
        $warning_msg[]='Session Created!!!! ';
        //print_r($db); die; 
   
        $tutoring_id=  $db->insert_id;
        $_SESSION['ses_tutoring_id']=$tutoring_id; // For Step2:UPload link
   
      }
   
   
   
                  }
   
   
   
   // if(empty($warning_msg)){
   
   ///////////////////////////////////////////////
   
     
   
   
     
   
   
      ///// of session ./////////////
        if(isset($tutoring_id)&&$tutoring_id>0){ // SessionCreated
       
          //////////IMage uploads///////////////
          $arr_sessionUploadType=array('lesson_pdf_image'=>'1 or more pdf uploaded in Session' ,  
                                 'exit_assignment_contains'=>'Drhomwork session have 1 or Max 2 exit Assignments! ' );
   
        
    
          $target_file='../uploads/';
          $uploads_msg=[];
   
          $assignmentid=0;$assignment_file_url='www.demo.com';
           $assignment_file='';
   
          for($i=0;$i<count($_FILES['upload_lesson']);$i++){
   
            if(!empty($_FILES["upload_lesson"]["name"][$i])){
   
   
   
           $mov_to=$target_file.$_FILES["upload_lesson"]["name"][$i];
           $assignment_file=$_FILES["upload_lesson"]["name"][$i];
   
           $fileUrl='http://drhomework.com/uploads/'.$assignment_file;
           // file_name_url
   
           if (move_uploaded_file($_FILES["upload_lesson"]["tmp_name"][$i], $mov_to)) {
             $uploads_msg[]=  'OK, moved to- '.$mov_to;
             //////Add in db///////
              $sql_upload= " INSERT INTO dr_session_x_upload SET parent_id='$parentId',
            dr_sessions_id='".$tutoring_id."', ";
   
            $sql_upload.= "upload_type='lesson_pdf_image',file_name='$assignment_file',file_name_url='$fileUrl', ";
             $sql_upload.= "date_created='$today',assignment_id='$assignmentid' ";
   
              $Addsql_upload=$db->query($sql_upload); 
              // $uploads_msg[]='File--'. $_FILES["upload_lesson"]["name"][$i];
               //echo $sql_upload; die; 
   
   
             //////Add in db///////
   
   
           }
   
        // die; 
          }
   
          }
   
         
   
   
   
         ###################################
   
   
              
      $ses_id=$tutoring_id; 
       $student_board_url=88; $stu_str=''; 
   
              $today='2019-09-04 01:00';
              $quiz_id=99;
            
     foreach ($_POST['student'] as $key => $stu_id) {
   
         $student_id=$stu_id;
     
   
       $sql_student=" INSERT INTO dr_sessions_x_students SET launchurl='$student_board_url',
         encryptedlaunchurl='$stu_str',
         braincert_class='',type='drhomework',slot_id='$ses_id',student_id='$student_id', "
   
                               . "created_date='$today',quiz_id='$quiz_id' ";
   
   
                 if($student_id>0)
                 $AddStudent=$db->query($sql_student); 
   
     }
   
              //////////
        # Send data to Intervene Tutoring session. 
   
         $res=$db->query(" SELECT * FROM dr_sessions WHERE id=".$tutoring_id);
          $tutoring_det=$res->fetch_assoc();
        
   
          $Sql_Student_det=$db->query( " SELECT ds.slot_id, ds.student_id, s.*
   FROM dr_sessions_x_students ds
   LEFT JOIN ty_student s
   ON ds.student_id=s.student_id
   WHERE slot_id=".$tutoring_id);  // 2514
   
     
       $student_name_list=[];
       $i=0;
     while($row = $Sql_Student_det->fetch_assoc()){  
    // $student_name_list[]=$row['first_name'].' '.$row['last_name'] ;
       $student_name_list[]=(!empty($row['first_name']))?$row['first_name']:'Student_'.($i+1);
       
     $i++;
     }
   
   
       //die; 
   
   
   #########################################
     /***
     1-ObjectiveName
   2-Class list of Student
   3-Special notes for Lession 
   4- Donwnload link
   5-Lessonpdf
   6 Tutoring_assigment ;; https://intervene.io/questions/uploads/lesson/91173.2D Math Example 2.pptx
   
     **/  
      $dymm_dowload_link="http://drhomework.com/uploads/assignments/assignment_id_25.pptx";
   
   
     $download_assigment_arr=array('http://drhomework.com/uploads/assignments/assignment_id_25.pptx',
                           'http://drhomework.com/uploads/assignments/assignment_id_25.pptx' );  // 0 OR >=1
   
     $list_assigment_arr=array('3-Assignemtn1',
                                 '3-Assignemtn554 ' ); // 0 OR >=1
   
   
     $UPload_opn_url="http://drhomework.com/uploads/uplaods.pdf";
   
      // lesson///////////////
     $lesson_documents_arr=array('http://drhomework.com/uploads/uplaods.pdf',
                           'http://drhomework.com/uploads/uplaods.pdf' );  // 0 OR >=1.
   
     
       $res_sql=$db->query(" SELECT *
   FROM  dr_session_x_upload
   WHERE  dr_sessions_id =".$tutoring_id);
       $lesson_documents_arr=array();
       while($row = $res_sql->fetch_assoc()){  
         //print_r($row); die;
         $lesson_documents_arr[]=$row['file_name_url'];
       }
   
       /////Grade selected ////
   
       $res_sql_grade=$db->query(" SELECT *
   FROM  ty_grade
   WHERE  grade_id ='".$_POST['grade']."' ");   // $tutoring_det['grade_id']
   
     $Grade=  $res_sql_grade->fetch_assoc();
   
        //print_r($lesson_documents_arr); die;
   
      $GradeName=(isset($Grade['name']))?$Grade['name']:'';
     
   
   
      $parent_info=$_SESSION['ses_admin_teacher_name'];  //'Demo Parent form DrHomework';
   
     ////////All parameter required for each session //////////////
         
     $TutoringInfoArr=array('objective_name'=>'3.5H', 
                             'list_of_students'=>$student_name_list,
                              'download_assigment_arr'=>$download_assigment_arr,
   
                              'list_assigment_arr'=>$list_assigment_arr,
   
                              'lesson_documents_arr'=>$lesson_documents_arr,
                              'parent_info'=>$parent_info,
   
                              'dr_grade_name'=>$GradeName,  // From DrSession.
                              'client_ses_id'=>$ses_id,
                              'parent_id'=>$parentId,
                             'help'=>'Help text form DrHomework for this session! ',  ); 
   
         //echo '<pre>';
       // print_r($TutoringInfoArr); die;
   
   
   
    $TutoringInfoArr_str=json_encode($TutoringInfoArr);  
   
    $tutoring_special_notes=addslashes($_POST['special_notes']);  //$tutoring_det['ses_end_time']; //'special notes from parent!  ';
   
   
   #######################################
   
   
             $postFields = [
       'Tutoring_client_id' =>"Drhomework123456", //For All
       'drhomework_ref_id' =>$tutoring_id,
       'board_type' =>'groupworld', //fixed
        'type' => 'drhomework' ,
        'ses_start_time' =>$tutoring_det['ses_start_time'], //'2019-09-15 13:15:00' ,
        'ses_end_time' => $tutoring_det['ses_end_time'], 
        'district_id' => $district_id ,
        'grade_id' => $tutoring_det['grade_id'], // DrgradeID
   
         'student_info' =>$TutoringInfoArr_str,  //Array or JSON
   
         'special_notes' =>$tutoring_special_notes, // , special_notes
         'dr_parent_id' =>$parentId ,
         'dr_grade_id' => $tutoring_det['grade_id'],
   
         'lession_pdf_link' =>'Full url from drhomework , 2 more',
         'assignment_pdf_link' =>'Full url from drhomework , 1-2 only',// Not Needfull
   
       
       
       
       //'otherField'   => 1,
   ];
   
          //echo '<pre>';
   
         // print_r($TutoringInfoArr_str); die; 
   
   
   
        # STOP Sending Jobs
   
       $GetResponse= _add_tutoringIntervne($postFields,$apiUrl); //SessionCreatedAt-Intervene
   
       
   
   
   
   
   
   
   
        // assigning_lesson_opn
       if(isset($_POST['assigning_lesson_opn'])&&$_POST['assigning_lesson_opn']=='assignments'){
        // $Next_url='https://drhomework.com/parent/assign-homework/';
        $Next_url="https://drhomework.com/parent/select-uploads/";
         header("Location:".$Next_url); exit; 
       }elseif(isset($_POST['assigning_lesson_opn'])&&$_POST['assigning_lesson_opn']=='file_uploads'){
         header("Location:".$Next_url); exit; 
   
       }
   
   }
   
   
                      
   
   
   
   
   
   
   ////////////////////////////////////////////
    if(!empty($warning_msg)){  // print_r($warning_msg);
   
   
      $msg_str=implode(', ', $warning_msg);
   
   
    }
      
   
   
    
   
    }
     //include('session_check.php'); //$db->debug=1;
   
    //extract($_POST);
   
    define("MODULE_NAME","Add Tutoring Session") ; 
   
   
     // if(isset($uploads_msg)){
     //   print_r($uploads_msg); die; 
     // }
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title><?=MODULE_NAME?> | <?=SITE_TITLE_PARENT?>  </title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <!-- Styles -->
      <link href="/student/css/slate.css" rel="stylesheet" />
      <link href="/student/css/slate-responsive.css" rel="stylesheet" />
      <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
      <link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen">
      <link rel="stylesheet" href="/css/skeleton.css" type="text/css" media="screen">
      <link rel="stylesheet" href="/css/superfish.css" type="text/css" media="screen">
      <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen">
      <link rel="stylesheet" href="/css/slider.css" type="text/css" media="screen">
      <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
      <script src="/js/jquery-1.7.1.min.js"></script>
      <script src="/js/script.js"></script>
      <!-- <script src="/js/superfish.js"></script> -->
      <script src="/js/jquery.responsivemenu.js"></script>
      <script src="/js/jquery.hoverIntent.js"></script>
      <script src="/js/slides.min.jquery.js"></script>
      <script src="/js/jquery.easing.1.3.js" type="text/javascript"></script>
      <link href="/teacher/css/bootstrap.css" rel="stylesheet" />
      <link href="/teacher/css/bootstrap-responsive.css" rel="stylesheet" />
      <link href="/teacher/css/bootstrap-overrides.css" rel="stylesheet" />
      <link href="/teacher/css/ui-lightness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
      <link href="/teacher/css/slate.css" rel="stylesheet" />
      <link href="/teacher/css/slate-responsive.css" rel="stylesheet" />
      <!-- Javascript -->
      <script src="/teacher/js/jquery-1.7.2.min.js"></script>
      <script src="/teacher/js/jquery-ui-1.8.21.custom.min.js"></script>
      <script src="/teacher/js/jquery.ui.touch-punch.min.js"></script>
      <script src="/teacher/js/bootstrap.js"></script>
      <script src="/teacher/js/plugins/validate/jquery.validate.js"></script>
      <script src="/teacher/js/Slate.js"></script>
      <script src="/teacher/js/demos/demo.validate.js"></script>
      <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <script type="text/javascript">
         $(function () {
         
         // $('#datepicker-inline').datepicker ();
         // $('#datepicker-inline2').datepicker ();
         
         $('#start_date').datepicker ();
         $('#event_date').datepicker ({ dateFormat: "yy-mm-dd" });
         
         var date = new Date();
          var d = date.getDate();
          var m = date.getMonth();
          var y = date.getFullYear();
          
          // $('#calendar-holder').fullCalendar({
          //  header: {
          //    left: 'prev, next',
          //    center: 'title',
          //    right: 'month,basicWeek,basicDay,'
          //  },
          //  events: [
              
          //    {
          //      title:  Date(),
          //      start: new Date(y, m, 28),
          //      end: new Date(y, m, 29),
          //      color: Slate.chartColors[0]
          //    }
          //  ]
          // });
         
         });
         
      </script>
      <!-- New Js -->
      <script type="text/javascript">
         // var get_url='https://intervene.io/questions/get-school-district.php';
           // var get_url='https://intervene.io/ajax/get-school-district.php';
           var get_url='https://drhomework.com/parent/get-school-district.php';
         
         $(document).ready(function(){
             $('#oth-school').hide();
             $('#oth-dist').hide();
         console.log('URL:'+get_url);
         
         $('#district').chosen();
         $('#school').chosen();
         $('#district').on('change', function(){
         var district = $(this).val();
         //alert(district+':district');
         ///////
         var element=document.getElementById('oth-dist');
         
                     if(district=='other'){
                       console.log('Other selected:'+district);
                      $('#oth-dist').show(); $('#oth-school').show();
                         $('#school_div').html('<span></span>');
                      $('#school_div').hide();
                     //document.getElementById('school').style.display='none';
                     return false;
                      }else{// hide other
                          $('#oth-dist').hide(); $('#oth-school').hide();
                          $('#school_div').show();
                        }
                         
         
         
                      
         
         
         
         
         
         //////////////////////////
         
         //$("#school option[value!='']").remove();
                             
         // if( district == '' ){
         //   alert('Please select a district!');
         //    }else{
         //   $.ajax({
         //     type  : "POST",
         //     url   :get_url,
         //     data  : {district : district},
         //     success : function(response) {  $('#school_div').html(response); 
         //     $('#school').chosen();
         //     }
         //   });
         // }
         
         });
            ////Schooo selection //
            
            // var school_val=$(this).val();
            
         
         
         
         
           /////////////////////////////////
         
         
         });
      </script>
   </head>
   <body id="page1">
      <!--======================== header ============================-->
      <header>
         <div class="main">
            <!--======================== logo ============================-->
            <?php 
               # Header and Nave not working.
               
               //include '../head.php';
               //include '../nav.php';
               ?>
            <!--=================== slider ==================-->
         </div>
         </div>
         <div class="clear"></div>
         </div>
         </div>
      </header>
      <!--======================== content ===========================-->
      <section id="content">
         <div class="wrapper">
            <div class="main">
               <div class="content-box">
                  <div class="container_16">
                     <?php // include 'top.php'?> <!-- /#nav -->
                     <!-- /#header -->
                     <!-- /#header -->
                     <div id="nav" style="display:none">
                     </div>
                     <!-- /#nav -->
                     <br>
                     <div id="content" style="background-color:#FFFFFF;width:960px;">
                        <div class="container" style="background-color:#FFFFFF;width:960px;">
                           <div id="page-title" class="clearfix">
                              <h2 style="text-align:left"> <?= MODULE_NAME ?>  </h2>
                              <ul class="breadcrumb">
                                 <li>
                                    <a href="/parent/dashboard/">Home</a> <span class="divider">/</span>
                                 </li>
                                 <li>
                                    <a href="/parent/my-tutoring-sessions/">Tutoring Sessions</a> <span class="divider">/</span>
                                 </li>
                                 <li class="active">Create Session</li>
                              </ul>
                           </div>
                           <!-- /.page-title -->
                           <div class="row">
                              <div class="span12" style="width:960px">
                                 <div id="validation" class="widget highlight widget-form">
                                    <div class="widget-header">
                                       <h3>
                                          <i class="icon-pencil"></i>Create Session,
                                          &nbsp; Current Date/Time:<?= $curDateTime ?>(-CST)              
                                       </h3>
                                    </div>
                                    <!-- /widget-header -->
                                    <div class="widget-content">
                                       <?php if(isset($errormessage) && $errormessage!=""){?>
                                       <div class="alert alert-error">
                                          <a class="close" data-dismiss="alert" href="#">&times;</a>
                                          <h4 class="alert-heading"><?php echo $errormessage?></h4>
                                       </div>
                                       <?php }?>
                                       <?php 
                                          ///   $students_added= $db->GetOne("SELECT count(student_id) FROM ty_student_teacher WHERE teacher_id='".get_session('ses_admin_teacher_id')."'");
                                          
                                            //  if($students_added<$student_number){
                                                ?>
                                       <!--   <form action="https://drhomework.com/parent/add-tutoring-session/" id="contact-form" class="form-horizontal"  method="post"novalidate /> -->
                                       <form action="https://drhomework.com/parent/add-tutoring-session/" id="contact-form"
                                          class="form-horizontal"  method="post" enctype="multipart/form-data" />
                                          <input type="hidden" name="save" value="save">
                                          <fieldset>
                                             <?php  if(isset($msg_str)&&!empty($msg_str)): ?>
                                             <p style="color: red;" > 
                                                <?php  if(isset($msg_str)&&!empty($msg_str)) echo $msg_str; ?>
                                             </p>
                                             <?php  endif; ?>
                                             <!-- MObile Number -->
                                             <!--   <div class="control-group">
                                                <label class="control-label" for="name">Select Session</label>
                                                <div class="controls">
                                                <input type="text"  value="<?php  //echo $mobile_number;?>"
                                                class="validate[required,custom[onlyLetterSp]]" name="session_create" id="name"/>
                                                </div>
                                                </div> -->
                                             <div class="control-group"   id="District_area"  >
                                                <label class="control-label" for="dist">Select Student(1-4)*</label>
                                                <div class="controls">
                                                   <!--   name="student[]" id="students_id" multiple="true" -->
                                                   <?php  $stu=array();
                                                      $res_students=$db->query(" SELECT * FROM `ty_student` WHERE 1 AND parent_id=".$parentId);
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      ?>
                                                   <select name="student[]" id="district"   multiple="true"   class="form-control required">
                                                      <option value=""> Select student </option>
                                                      <?php while($row = $res_students->fetch_assoc()){  
                                                         // $_POST['student'] 
                                                         $sid=$row['student_id'];
                                                         $sel=(isset($_POST['student'])&&in_array($sid, $_POST['student']) )?'selected':'';
                                                         
                                                         
                                                          ?>
                                                      <option  <?=$sel; ?>  value="<?php  echo $row['student_id']; ?>"> <?php  echo $row['first_name']; ?> </option>
                                                      <?php }  ?>
                                                   </select>
                                                </div>
                                                <!-- OtherofDistrict -->
                                                <div class="controlsST">
                                                   <input type="text" name="other_district"  style="margin-left: 20px;margin-top: 2px;" 
                                                      placeholder="Please Enter District Name" class="form-control" id="oth-dist"  />
                                                </div>
                                             </div>
                                             <!-- XXXX --> <!-- <div class="form-group"  > -->
                                             <?php 
                                                $sql_grade=$db->query(" SELECT * FROM `ty_grade` WHERE 1 ");
                                                
                                                
                                                ?> 
                                             <div class="control-group">
                                                <label class="control-label" for="validateSelect">Grade *</label>
                                                <div class="controls">
                                                   <select name="grade" >
                                                      <?php while($row = $sql_grade->fetch_assoc()){  
                                                         // $_POST['student'] 
                                                         $sid=$row['grade_id'];
                                                         $sel=(isset($_POST['student'])&&$row['grade_id']===$_POST['student'])?'selected':'';
                                                         // grade_id  name
                                                         
                                                         
                                                         ?>
                                                      <option  <?=$sel; ?>  value="<?php  echo $row['grade_id']; ?>"> <?php  echo $row['name']; ?> </option>
                                                      <?php }  ?>
                                                      <!-- <option value="2"> 4th </option> -->
                                                   </select>
                                                </div>
                                             </div>
                                             <!--c. The parent will have to choose an assignment for the student.  
                                                2 options for assigning lesson to student: 
                                                1. Upload Assignment  
                                                2. Choose from Dr. HW Database.  -->
                                             <div class="control-group">
                                                <label class="control-label" for="validateSelect"> Options for assigning lesson*</label>
                                                <div class="controls">
                                                   <input type="radio" <?php echo  ($_POST['assigning_lesson_opn']=='file_uploads')?'checked':''; ?>  
                                                      name="assigning_lesson_opn" value="file_uploads">1. Upload Assignment <br>
                                                   <input type="radio"  <?php echo  ($_POST['assigning_lesson_opn']=='assignments')?'checked':''; ?>  
                                                      name="assigning_lesson_opn" value="assignments">2. Choose from Dr. HW Database.  
                                                </div>
                                             </div>
                                             <!-- Upload Assignment From system::
                                                Option 1 - Upload assignment (Need to have an option to upload a pdf or PowerPoint or jpeg image)
                                                
                                                -->
                                             <div class="control-group" id="upload_lesson_id" style="display: none;" >
                                                <label class="control-label" for="validateSelect">Upload assignment </label>
                                                <div class="controls">
                                                   <input type="file" id="firt_upload1" class="form-control" name="upload_lesson[]"> <br>
                                                   <input type="file" class="form-control" name="upload_lesson[]"> <br>
                                                   <input type="file" class="form-control" name="upload_lesson[]"> <br>
                                                </div>
                                             </div>
                                             <script>
                                                $(document).ready(function(){
                                                    var selcted_les="<?=$_POST['assigning_lesson_opn']?>"; //  fromForm
                                                    console.log('selcted_les=='+selcted_les);
                                                    if(selcted_les=='file_uploads'){
                                                       $("#upload_lesson_id").show();
                                                    }
                                                
                                                 $('input[type=radio][name=assigning_lesson_opn]').change(function() {
                                                    if (this.value == 'assignments') {
                                                        //alert("assignments");
                                                        $("#upload_lesson_id").hide();
                                                    }
                                                    else if (this.value == 'file_uploads') {
                                                       $("#upload_lesson_id").show();
                                                        //alert("file_uploads Transfer Thai Gayo");
                                                    }
                                                });
                                                
                                                  ///////////
                                                });
                                             </script>
                                             <!--  Search able Disctrict -->
                                             <div class="control-group"  style="display: none;">
                                                <label class="control-label" for="school">School *</label>
                                                <div class="controls">
                                                   <div id="school_div">
                                                      <input  type="text" placeholder="Select district" readonly="" 
                                                         id="other_opn" class="form-control">
                                                   </div>
                                                   <!-- other_school -->
                                                   <input type="text" name="other_school" placeholder="Please Enter School Name" 
                                                      class="form-control" id="oth-school">
                                                </div>
                                                <!-- controls -->
                                             </div>
                                             <!-- Date select -->
                                             <?php 
                                                $selected_date=date('Y-m-d');
                                                 if(isset($_POST['select_date'][0])){
                                                
                                                    $selected_date=$_POST['select_date'][0];
                                                   //echo 'Selected item==='.$selected_date ;
                                                 }
                                                
                                                
                                                
                                                ?>
                                             <div class="control-group">
                                                <label class="control-label" for="validateSelect">Date :&nbsp;&nbsp;&nbsp;</label>
                                                <div class="controls">
                                                   <input type="text" name="select_date[]" id="event_date" value="<?=$selected_date ?>" placeholder="Click for Datepicker" />
                                                </div>
                                             </div>
                                             <!-- <div class="control-group">
                                                <label class="control-label" for="date">Date</label>
                                                <div class="controls">
                                                
                                                   <input 
                                                                  name="select_date[]" class="datepicker textbox cdate" 
                                                                   data-date-format="mm/dd/yyyy">
                                                
                                                </div>
                                                </div> -->
                                             <!-- Add time and date -->
                                            
                                              <div class="control-group" id="add_more" >
                                                <!-- Times -->

                                                <label class="control-label" for="date">Start Time:</label>
                                                <div class="controls" id="time_section">
                                                   
                                                   <select name="hour[]"  id="time" class="textbox">
                                                      <?php
                                                         $i = 1;
                                                         while ($i <= 12) {
                                                         $sel = (isset($_POST['hour'][0]) && $i == $_POST['hour'][0]) ? "selected" : NULL;//Multi
                                                         ?>
                                                      <option  <?= $sel ?>
                                                         value="<?= $i ?>" ><?= $i ?></option>
                                                      <?php
                                                         $i++; }
                                                         ?> 
                                                   </select>
                                                   <select name="minnute[]"  class="textbox">
                                                      <?php
                                                         $k = 0;
                                                         while ($k <= 55) {
                                                         // $sel = ($k == $_POST['minnute']) ? "selected" : NULL;
                                                           $sel = (isset($_POST['minnute'])&&$k == $_POST['minnute'][0]) ? "selected" : NULL;
                                                         
                                                         $kff = ($k > 5) ? $k : '0' . $k;
                                                         ?>                            
                                                      <option  <?= $sel ?> value="<?= $k ?>"><?= $kff ?></option>
                                                      <?php $k = $k + 5;
                                                         } ?>
                                                   </select>
                                                   <?php
                                                      $tArr = array('am', 'pm');
                                                      $Type
                                                      ?>
                                                   <select name="hr_type[]"  class="textbox">
                                                      <?php
                                                         foreach ($tArr as $val) {
                                                         
                                                             $sel = (isset($_POST['hr_type']) && $val == $_POST['hr_type'][0]) ? "selected" : NULL;
                                                         
                                                             ?>                         
                                                      <option <?= $sel ?>   value="<?= $val ?>"><?= strtoupper($val) ?></option>
                                                      <?php } ?>
                                                   </select>
                                                </div>
                                                <!-- Select assessment -->
                                             </div>
                                             <!-- Add date -->
                                             <div class="control-group">
                                                <label class="control-label" for="validateSelect">Special notes *</label>
                                                <div class="controls">
                                                   <textarea placeholder="Note" 
                                                      name="special_notes" rows="4" 
                                                      cols="50"><?=(isset($_POST['special_notes']))?$_POST['special_notes']:''; ?> </textarea>
                                                </div>
                                             </div>
                                             <div class="control-group">
                                                <div class="controls">
                                                   <button type="submit" name="add_session" class="btn btn-danger btn-large"> Submit </button>
                                                </div>
                                             </div>
                                          </fieldset>
                                       </form>
                                    </div>
                                    <!-- /widget-content -->
                                 </div>
                                 <!-- /.widget --><!-- /.widget -->
                              </div>
                              <!-- /span8 -->
                              <!-- /.span4 -->
                           </div>
                           <!-- /row -->
                        </div>
                        <!-- /.container -->
                     </div>
                     <!-- /#content -->
                  </div>
               </div>
            </div>
         </div>
      </section>
      <script type="text/javascript">
         /////Get DrHomework-- School list. // $('#district1').change(function() /////   
      </script>
      <!--======================== footer ============================-->
      <?php include '../footer.php';?>
   </body>
   <style>
      /*#school_chosen{width:100%!important;}*/
   </style>
   <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css" />
   <!-- JS -->
   <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
</html>