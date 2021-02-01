    <!-- Sidebar -->

<?php $taxonomy = ( isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy']) && $_GET['taxonomy'] > 0 ) ? $_GET['taxonomy'] : 0; ?>

<?php  
if($_SESSION['expired_user']!='expired'){
    $teacher_grade_res = mysql_query("
	SELECT  * from demo_users WHERE id = \"".$_SESSION['demo_user_id']."\" AND data_dash > 0");
      
if(mysql_num_rows($teacher_grade_res) > 0 ) {
    ?>

<div id="datadash" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="#">Data dash</a></h4>
</div>	
<div class="group_1 widget-content">
    <div id="arrange" class="widget clear fullwith">
        
	<h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="demo_manage_classes.php">Manage Classes</a></h4>
        
</div>	
        <div id="arrange" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="demo_manage_assesment.php">Manage Assessments</a></h4>
</div>	
    <div id="arrange" class="widget clear fullwith">
        <h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="demo_assesment_history.php">Assessment History</a></h4>
</div>

    	
     <div id="arrange" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="demo_result_dashboard.php">Results - Dashboards</a></h4>
</div>	
     
     <div id="arrange" class="widget clear fullwith">
         <h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="demo_give_student_access.php">Print Student Access Sheet</a></h4>
</div>	
    <div id="arrange" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="demo_manage_assesment.php">How Data Dash Works</a></h4>
</div>	
    
</div>
<?php } ?>

<div id="smartprep" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="#">Smart Prep - Questions</a></h4>
</div>	

<div class="group_2 widget-content">
 <div id="arrange" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="demo_folder.php">Choose Questions</a></h4>
</div>	   
<div id="arrange" class="widget clear fullwith">
	<?php include('widget/demo_widget-arrange.php');?>
</div>		<!-- /#arrange -->

<div id="save&print" class="widget clear fullwith">
	<?php include('widget/demo_widget-save&print.php');?>
</div>		<!-- /#arrange -->

<div id="strategies" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-lightbulb-o"></i><a href="demo_strategies.php">Distractor Definitions</a></h4>
</div>		<!-- /#Strategies -->

<div id="purchase" class="widget clear fullwith">
	<h4 class="widget-title">
		<i class="fa fa-credit-card"></i><a href="/purchaseform.php" target="_blank">Purchase now</a>
	</h4>
</div>		<!-- /#Purchase now -->

	<!-- /#Feedback -->

<div id="howitworks" class="widget clear fullwith">
	<?php include('widget/widget-howitworks.php');?>
</div>		<!-- /#howitworks -->
</div>
<!--
<div id="user-lesson" class="widget clear fullwith">
	<?php # include('widget/widget-user-lesson.php');?>
</div>
-->
<!-- /#membership -->


<?php if($role==0):?>
<div id="folder" class="widget clear fullwith">
	<?php include('widget/widget-folder.php');?>
</div>		<!-- /#folder -->
<?php endif;?>

<?php if($role==0):?>
<div id="strategies" class="widget clear fullwith">
	<?php include('widget/widget-strategy.php');?>
</div>		<!-- /#Strategies -->
<?php endif;?>

<?php if($role==0):?>
<div id="passage" class="widget clear fullwith">
	<?php include('widget/widget-passage.php');?>
</div>		<!-- /#passage -->
<?php endif;?>

<?php if($role==0):?>
<div id="questions" class="widget clear fullwith">
	<?php include('widget/widget-questions.php');?>
</div>		<!-- /#questions -->

<div id="distrator" class="widget clear fullwith">
	<?php include('widget/widget-distrator.php');?>
</div>		<!-- /#distrator -->
<?php endif;?>

<?php if($role==0):?>
<div id="objective" class="widget clear fullwith">
	<?php include('widget/widget-objective.php');?>
</div>		<!-- /#objective -->
<?php endif;?>

<?php if($role==0 && isGlobalAdmin()):?>
<div id="manager_order" class="widget clear fullwith">
	<?php include('widget/widget-manager_order.php');?>
</div>		<!-- /#manager_user -->
<?php endif;?>

<?php if($role==0 && isGlobalAdmin()):?>
<div id="manager_user" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-users"></i><a href="manage-district.php">Manager District</a></h4>
</div>		<!-- /#manager_user -->
<div id="manager_user" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-users"></i><a href="manage-schools.php">Manager Schools</a></h4>
</div>
<div id="manager_user" class="widget clear fullwith">
	<?php include('widget/widget-manager_user.php');?>
</div>
<div id="manager_user" class="widget clear fullwith">
	<?php include('widget/widget-demo_manager_user.php');?>
</div>

<?php endif;?>
<div id="feedback" class="widget clear fullwith">
	<?php include('widget/widget-feedback.php');?>
</div>
<?php } ?>
<div id="profile" class="widget clear fullwith">
	<?php include('widget/demo_widget-profile.php');?>
</div>		<!-- /#profile -->