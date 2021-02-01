<!--<h4 class="widget-title"><i class="fa fa-home"></i></span>Home</h4>
<div  class="widget-content">
	<p class="add_new">
		<i class="fa fa-plus-circle"></i>
		<a href="#">Add Link</a>
	</p>
	
	
</div>-->
<?php  
// is_passage_grade :: only if Passage grade selected-Createfrom-questions_list.php
$add_more_url="school_assessment_step2.php?taxonomy=".$_SESSION['ses_taxonomy']; // w/o passage
  if($_SESSION['ses_taxonomy']>0&&($_SESSION['is_passage_grade']==1||$_SESSION['is_passage']==1))
      $add_more_url="school_assessment_step1.php?taxonomy=".$_SESSION['ses_taxonomy']; // Passage in Session
   
$curr_page=  substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); ?>
<a href="school.php" class="widget-title"><i class="fa fa-home"></i></span>Home</a>
<h4 class="widget-title"><i class="fa fa-question-circle"></i></span>Assessments</h4>
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