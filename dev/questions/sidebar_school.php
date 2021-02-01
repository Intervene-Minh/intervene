<?php
/**
@ Edit & Arrange : Question in Assessment
@ https://intervene.atlassian.net/browse/IIDS-152
***/


require_once('inc/check-role.php');
	//$role = checkRole();


//$taxonomy = ( isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy']) && $_GET['taxonomy'] > 0 ) ? $_GET['taxonomy'] : 0; 


//if($role>0): 
//    $teacher_grade_res = mysql_query("
//	SELECT  grade_level_id AS shared_terms
//	FROM `techer_permissions`
//	WHERE teacher_id = {$_SESSION['login_id']} AND grade_level_id > 0 
//");
//      
//
//        endif;

?>





	

<div id="questions" class="widget clear fullwith">
	<?php  
// is_passage_grade :: only if Passage grade selected-Createfrom-questions_list.php
$add_more_url="school_assessment_step2.php?taxonomy=".$_SESSION['ses_taxonomy']; // w/o passage
  if($_SESSION['ses_taxonomy']>0&&($_SESSION['is_passage_grade']==1||$_SESSION['is_passage']==1))
      $add_more_url="school_assessment_step1.php?taxonomy=".$_SESSION['ses_taxonomy']; // Passage in Session
   
$curr_page=  substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
// Rest assessment
$reset_assement=0;
if(!isset($_SESSION['assess_id'])&&isset($_SESSION['ses_taxonomy'])
  &&isset($_SESSION['ses_school_list'])&&count($_SESSION['ses_school_list'])>0)
$reset_assement=1;
// Reset Creation :: assessment

if(isset($_GET['ac'])&&$_GET['ac']=='clear_assess'){
   
      unset($_SESSION['ses_school_list']); 
        unset($_SESSION['ses_taxonomy']); 
        unset($_SESSION['qn_list']); 
        unset($_SESSION['is_passage_grade']); 
        unset($_SESSION['is_passage']);
        header("Location:school_assessment_step1.php");exit;
}
?>
<a href="school.php" class="widget-title"><i class="fa fa-home"></i></span>Home</a>
<h4 class="widget-title"><i class="fa fa-question-circle"></i></span>Assessments</h4>
 <?php if($reset_assement==1){?>
<a style=" float:right;"class="btn btn-danger btn-sm" title="Reset creating-Discard current Assessment, Select new grade level"
   href="<?=$_SERVER['PHP_SELF']."?ac=clear_assess"?>">Reset creating</a>
 <?php } //?>

<div  class="widget-content">
	 <?php 
         // $_SESSION['ses_taxonomy'] : creation in process
         if(isset($_SESSION['ses_taxonomy'])){?>
    <p class="add_new" style="display:<?=($curr_page=="school_assessment_step2.php")?'none':''?>;" >
		<i class="fa fa-plus-circle"></i>
		<a href="<?=$add_more_url?>">+Add More Questions</a>
	</p>
        
         <?php }else{?>
        <p class="add_new" >
		<i class="fa fa-plus-circle"></i>
		<a href="school_assessment_step1.php">Add Assessment</a>
	</p>
         
         <?php }?>
        
         <p class="add_new">
	<i class="fa fa-file-text-o"></i><a href="school_assessment_step3.php">Arrange</a></p>
         
         
        <i class="fa fa-th-list"></i><a href="school_assessment_list.php">List Assessments</a>
        
	
</div>
</div>	



<div id="profile" class="widget clear fullwith">
	<?php # include('widget/widget-profile.php');?>
</div>		<!-- /#profile -->