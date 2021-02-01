<?php 
/***
@School: Passage Questions List
@ 


**/

include("header.php"); 
require_once('inc/school_validate.php'); // IMP


  //print_r($_SESSION);
/////Set  taxonomy in Session //////////////////
$_SESSION['is_passage_grade']=1;

if(isset($_GET['taxonomy'])&&$_GET['taxonomy']>0){
$_SESSION['ses_taxonomy']=$_GET['taxonomy'];
$_SESSION['is_passage_grade']=1; // Question Select From  passages
}
// School Questions

?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php  include("sidebar_school.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<?php 
					/*$role==0=>admin    $role==1=>user*/
					//require_once('inc/check-role.php');
					//$role = checkRole();
					 include("content/school_questions.php");
				?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#main -->

<?php include("footer.php"); ?>