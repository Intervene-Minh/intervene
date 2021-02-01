<?php 
/***
@ Questions

Working-->Script for not spanish filter.>>
 [ <p>&nbsp;&nbsp;</p> ]


**/

include("header.php"); 

  // print_r($_SESSION);

$system_msg='language_issue_questions ===============';



//////////////////////

 //echo  $system_msg;

/***
 @ Admin and Teacher Question listing 

 echo '=====';

//print_r($_SERVER); die; 

 =======================================

   // $a=array_shift($qdata);
 	  // $column_arr=array_keys($qdata);
 	  // $column_str=implode(',', $column_arr);

**/
 
////Duplicate question ///





?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<?php 
					/*$role==0=>admin    $role==1=>user*/
					require_once('inc/check-role.php');
					$role = checkRole();
					if($role==0){ //Admin Question List. 
						include("content/lang_issue_question.php");

					}else{// teacher
                                              
                    if($role>0)include("content/teacher_passage_ques.php");
					//if($role>0)include("content/user-question.php");
                                        
					}
				?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#main -->

<?php include("footer.php"); ?>