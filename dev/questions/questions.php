<?php 
/***
@ Questions

Working-->Script for not spanish filter.>>
 [ <p>&nbsp;&nbsp;</p> ]


**/

include("header.php"); 

  // print_r($_SESSION);



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


 # questions.php?quid=7967
 if(isset($_GET['quid'])){


 	$getid=$_GET['quid'];

 	  $sql="SELECT * FROM questions WHERE id=".$getid ; 
 	  $qdata=  mysql_fetch_assoc(mysql_query($sql));

 	
 	  
    // add  Questions 
 	  $question_name=$qdata['name'];
 	   $question_name=$qdata['name'];
 	   $data_dashcal=$qdata['data_dash'];

 	   // $alert = 'add new';
 	    $copy_name='Copy from-QID:'.$qdata['id'].'-'.$qdata['name'];

		$query = "INSERT INTO `questions` (`name`, `name_spanish`, `question`, 
		`question_spanish`, 
		`author`, `category`, `type`, `answers` , `answers_spanish`, `passage`, `public`,`smart_prep`,`data_dash`)

		VALUES ('{$copy_name}', '".$qdata['name_spanish']."', '".$qdata['question']."',
		 '".$qdata['question_spanish']."', 

		 '".$qdata['author']."', 
		 '".$qdata['category']."', 

		 '".$qdata['type']."',
		  '".$qdata['answers']."' , 
		   '".$qdata['answers_spanish']."' , 

		   '".$qdata['passage']."', 
		     '".$qdata['public']."', 
		     '".$qdata['smart_prep']."', 

		   {$data_dashcal})";




   //        echo '<pre>';
		 // echo $query; die; 

		$addq= mysql_query($query);	

		# Add Objective 

		$newQuestionId=mysql_insert_id();

		 //$newQuestionId=99;

		$sql_objective=mysql_query(" SELECT *
FROM  term_relationships
WHERE  question_id= '$getid' ");
		 

		   while ($line = mysql_fetch_assoc($sql_objective)){
		  	# code...
		  	
		  	$object_item=$line['objective_id'];

		  $add_obj=mysql_query("INSERT INTO `term_relationships` (question_id, objective_id) VALUES ('{$newQuestionId}', '{$object_item}')");

		  }



		  //////Re-direct to  Edit quetions////////
		  $edit_url="single-question.php?question=".$newQuestionId; // single-question.php?question=7968

      header('Location:'.$edit_url);  exit;

 


 	  print_r($qdata); die; 


 	//  go to questio edit 
 }



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
						include("content/admin-question.php");

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