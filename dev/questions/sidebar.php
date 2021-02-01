<style>
   .menu_heading{  font-size: 16px !important;}  
   .sub_menu_heading{  font-size: 14px !important;}  
</style>
<?php
   require_once('inc/check-role.php');
   $role = checkRole();
   $taxonomy = ( isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy']) && $_GET['taxonomy'] > 0 ) ? $_GET['taxonomy'] : 0;
   if($role>0): 
   
$teacher_grade_res = mysql_query("SELECT  grade_level_id AS shared_terms FROM `techer_permissions` WHERE teacher_id = {$_SESSION['login_id']} AND grade_level_id > 0");
   
   if(mysql_num_rows($teacher_grade_res) > 0){?>
<div id="datadash" class="widget clear fullwith">
   <h4 class="widget-title menu_heading"><i class="fa fa-file-text-o"></i><a href="#">Data Dash</a></h4>
</div>
<div class="group_1 widget-content">
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a href="manage_classes.php">Manage Classes</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a href="manage_assesment.php">Manage Assessments</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a href="assesment_history.php">Assessment History</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a href="result_dashboard.php">Results - Dashboards</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith" title="Detailed Report">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a style="line-height:1px" href="detailed_dashboard.php">Detailed Report</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a href="give_student_access.php">Print Student Access Sheet</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading" ><i class="fa fa-file-text-o"></i><a target="_blank" href="https://intervene.io/Data Dash Instructions.pdf">How Data Dash Works</a></h4>
   </div>
</div>
<?php } ?>
<div id="smartprep" class="widget clear fullwith">
   <h4 class="widget-title menu_heading"><i class="fa fa-file-text-o"></i><a href="#" >Smart Prep - Questions</a></h4>
</div>
<div class="group_2 widget-content">
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a href="folder.php">Choose Questions</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <?php include('widget/widget-arrange.php');?>
   </div>
   <!-- /#arrange -->
   <div id="save&print" class="widget clear fullwith">
      <?php include('widget/widget-save&print.php');?>
   </div>
   <!-- /#arrange -->
   <div id="strategies" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-lightbulb-o"></i><a href="strategies.php">Distractor Definitions</a></h4>
   </div>
   <!-- /#Strategies -->
   <div id="purchase" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading">
         <i class="fa fa-credit-card"></i><a href="/purchaseform.php" target="_blank">Purchase Now</a>
      </h4>
   </div>
   <!-- /#Purchase now -->
   <!-- /#Feedback -->
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a target="_blank" href="../Smart Prep Instructions.docx.pdf">How Smart Prep Works</a></h4>
   </div>
   <div id="howitworks" class="widget clear fullwith">
      <?php include('widget/widget-howitworks.php');?>
   </div>
   <!-- /#howitworks -->
</div>
<?php  // Intervention
   $teacherId=$_SESSION['login_id'];
   
   $teacher_intervention=mysql_query("SELECT * FROM `int_slots_x_student_teacher` WHERE type='intervention' AND  int_teacher_id = '$teacherId'");
   
   $total_int=mysql_num_rows($teacher_intervention);
   
   if($total_int>0) {?>
<div id="datadashw" class="widget clear fullwith">
   <h4 class="widget-title menu_heading"><i class="fa fa-file-text-o"></i>
      <a href="#">Interventions<?php //=$total_int?></a>
   </h4>
</div>
<div class="group_3 widget-content">
   <div id="arrange" class="widget clear fullwith" title="Schedule Tutor Sessions Calendar">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i>
         <a href="view_calendar.php">Calendar View</a>
      </h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a href="give_student_access.php">Print Student Access</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-check-circle"></i><a href="school_attendance.php">Student Attendance</a></h4>
   </div>
   <div id="arrange" class="widget clear fullwith" title="Quiz-Results">
      <h4 class="widget-title sub_menu_heading">
         <i class="fa fa-file-text-o"></i><a href="quiz_results.php">Intervention Progress Monitoring</a>
      </h4>
      <div class="widget-content">
         <p class="add_new sub_menu_heading">
            <i class="fa fa-file-text-o"></i><a href="result_exit_quiz.php">Quiz Data Dash</a>
         </p>
      </div>
   </div>
</div>
<?php }  endif;?>
<!-- Telpas-menu -->
<?php 
   $Permission = _CheckTelpassPermission();
   if($Permission==1){?>
<div title="TELPAS Pro" id="telpas_menu" class="widget clear fullwith">
   <h4 class="widget-title menu_heading"><i class="fa fa-file-text-o"></i>
      <a href="#">TELPAS Pro </a>
   </h4>
</div>
<div class="group_4 widget-content">
   <div id="arrange" class="widget clear fullwith"
      title="Manage Classes">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i>
         <a href="manage_classes.php">Manage Classes</a>
      </h4>
   </div>
   <div id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a 
         href="http://ec2-35-165-58-67.us-west-2.compute.amazonaws.com/dev/teacherLogin.php">Progress Monitoring(Telpas Result) </a></h4>
   </div>
   <div style="display:;" id="arrange" class="widget clear fullwith">
      <h4 class="widget-title sub_menu_heading"><i class="fa fa-file-text-o"></i><a 
         href="give_student_access.php">
         Print Student Access Sheet</a>
      </h4>
   </div>
</div>
<?php }?>
<?php if($role==0):?>
<?php include('widget/widget-folder.php');?>
<!-- /#folder -->
<?php endif;?>
<?php if($role==0&&$_SESSION['ses_subdmin']=='no'):?>
<?php include('widget/widget-strategy.php');?>
<?php endif;?>
<?php if($role==0):?>
<?php include('widget/widget-passage.php');?>
<?php endif;?>
<?php if($role==0):?>
<?php include('widget/widget-questions.php');?>
<?php if($role==0&&$_SESSION['ses_subdmin']=='no'){?>
<?php include('widget/widget-distrator.php');?>
<div id="Courses" class="widget clear fullwith">
   <h4 class="widget-title"><i class="fa fa-bolt"></i>
      <a href="#" class="menu_heading">Courses</a>
   </h4>
</div>
<div class="Courses_widget widget-content">
   <p class="add_new">
      <i class="fa fa-plus-circle"></i>
      <a href="manage_lesson.php" title="Add Lessons">Add Lessons</a>
   </p>
   <p class="add_new" title="Lessons List">
      <i class="fa fa-file-text-o"></i><a href="lessons.php">Lessons</a>
   </p>
</div>
<!-- class link grade-->
<div id="Rosters" class="widget clear fullwith">
   <h4 class="widget-title"><i class="fa fa-bolt"></i>
      <a href="#" class="menu_heading">Rosters</a>
   </h4>
</div>
<div class="Rosters_widget widget-content">
   <p class="add_new" title="Rosters List">
      <i class="fa fa-file-text-o"></i><a href="class_link_grade.php">Class Link Grade</a>
   </p>
   <p class="add_new" title="Rosters List">
      <i class="fa fa-file-text-o"></i><a href="add_new_roster.php">Rosters</a>
   </p>
</div>
<!---Intervention list-->
<div id="Intervention" class="widget clear fullwith">
   <h4 class="widget-title"><i class="fa fa-bolt"></i>
      <a href="#" class="menu_heading">Intervention(Tutorial)</a>
   </h4>
</div>
<div class="Intervention_widget widget-content">
   <p class="add_new" title="Schedule Session">
      <i class="fa fa-plus-circle"></i><a href="intervention_list.php">Schedule Session</a>
   </p>
   <p class="add_new" title="View Calendar">
      <i class="fa fa-th-list"></i><a href="intervention_calendar.php">View Calendar</a>
   </p>
   <p class="add_new" title="Schedule Session">
      <i class="fa fa-plus-circle"></i><a href="add_session.php">Add Session</a>
   </p>
   <p class="add_new" title="Schedule Session using Newrow">
      <i class="fa fa-plus-circle"></i><a style="color: red;" href="create_session.php">Create Session(newrow)</a>
   </p>
   <p class="add_new" title="Schedule Session using Newrow 25 max">
      <i class="fa fa-plus-circle"></i><a style="color: blue;" href="create_session-k.php">Create Session(newrow) 25 max</a>
   </p>
   <p class="add_new" title="Schedule Session">
      <i class="fa fa-plus-circle"></i><a href="bulk_session_upload.php">Bulk Session Upload</a>
   </p>
</div>
<?php if($role==0 && isGlobalAdmin()):?>
<div id="distrator" class="widget clear fullwith">
   <div id="feedback" class="widget clear fullwith">
      <h4 class="widget-title"><i class="fa fa-headphones"></i><a href="https://ellpractice.com/login/index.php" title="Teplas Pro" target="_blank" class="menu_heading">English Support & TELPAS Pro</a></h4>
   </div>
</div>
<?php endif;?>
<?php include('widget/widget-quiz.php');?>
<?php } ?>	
<?php endif;?>
<?php if($role==0&&$_SESSION['ses_subdmin']=='no'):?>
<?php include('widget/widget-objective.php');?>
<?php endif;?>
<?php if($role==0 && isGlobalAdmin()):?>
<?php include('widget/widget-manager_order.php');?>
<?php endif;?>
<?php if($role==0 && isGlobalAdmin()):?>
<div id="manager_user" class="widget clear fullwith">
   <h4 class="widget-title"><i class="fa fa-users"></i><a href="manage-district.php" class="menu_heading">Manager District</a></h4>
</div>
<!-- /#manager_user -->
<div id="manager_user" class="widget clear fullwith">
   <h4 class="widget-title"><i class="fa fa-users"></i><a href="manage-schools.php" class="menu_heading">Manager Schools</a></h4>
</div>
<div id="manager_user" class="widget clear fullwith">
   <?php include('widget/widget-manager_user.php');?>
</div>
<div id="manager_user" class="widget clear fullwith">
   <?php include('widget/widget-demo_manager_user.php');?>
</div>
<?php endif;?>
<?php if($_SESSION['ses_subdmin']!='yes'){  ?>
<div id="feedback" class="widget clear fullwith">
   <?php include('widget/widget-feedback.php');?>
</div>
<?php  } ?>
<div id="profile" class="widget clear fullwith">
   <?php include('widget/widget-profile.php');?>
</div>
<!-- /#profile -->