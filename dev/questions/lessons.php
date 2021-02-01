<?php

include("header.php");
$error='';
$login_role = $_SESSION['login_role'];
$id = $_SESSION['login_id'];


if($login_role!=0 || !isGlobalAdmin()){

	header("location: index.php");
}


if(isset($_POST['print_quiz'])) {

		$les_id=$_POST['print_quiz'];// mysql_query

		$quiz_det=mysql_fetch_assoc(mysql_query("SELECT * FROM int_quiz WHERE lesson_id= '$les_id' "));

		$lesson_row=mysql_fetch_assoc(mysql_query("SELECT id,name as les_name FROM master_lessons WHERE id=".$les_id));
		$quiz_id=$quiz_det['id'];
		$_SESSION['ses_lesson_name']=$lesson_row['les_name'];
		$qn_res =mysql_query('SELECT qn_id FROM int_quiz_x_questions WHERE quiz_id = \'' .$quiz_id. '\' ORDER BY num ASC');
		$_SESSION['list'] = array();
		while ($question = mysql_fetch_assoc($qn_res)) {
			$_SESSION['list'][] = $question['qn_id'];
		}
		//check if passage included or not  :: mysql_query
		 $q_ids= implode(',', $_SESSION['list']);
		 $sql=mysql_query("SELECT DISTINCT `q`.`passage` , p . *  FROM `questions` q INNER JOIN `passages` p ON `q`.`passage` = `p`.`id`
		     WHERE q.`id` IN (".$q_ids.")");

		 if(mysql_num_rows($sql)>0){
		   //  echo 'PassageQuestionsExist'.mysql_num_rows($sql);die; 
		     header("Location: inc/ajax-quiz-passage-print.php");
		 }else header("Location: inc/ajax-quiz-print.php"); # NEW 

		   
		   
		 die();

}




/////////////////////

if(isset($_REQUEST['dlt_id']))   
                  {
                     $dlt_id=$_REQUEST['dlt_id'];
                    
                     $delete_query=mysql_query("DELETE FROM master_lessons WHERE id=$dlt_id");
					 
                     if($delete_query)
                        {
                           header("location:lessons.php");
                        }
                    
                  }
  


//$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>
<script>
	$(document).ready(function(){
		$('#dlt_id').on('click',function(){
			var count = $('#form-manager .checkbox:checked').length;
			$('#arr-user').val("");
			$('#form-manager .checkbox:checked').each(function(){
				var val = $('#arr-user').val();
				var id = $(this).val();
				$('#arr-user').val(val+','+id);
			});
			var str = $('#arr-user').val();
			$('#arr-user').val(str.replace(/^\,/, ""));
			return confirm('Do you want to delete '+count+' lesson?');
		});
	});
</script>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">

			<?php  //  $_SESSION['msg_success']='Record saved successfully!';
				?>

				  <?php   include "msg_inc_1.php";  ?>

				<div class="table-responsive">
					
				</div>
				
                            <?php   
									/////////////// Demo Users
                 $query = 'Select * from master_lessons order by id desc ';
                              /* Process Search */                                                    
									$results = mysql_query($query);
									$tot_record=mysql_num_rows($results);            
                            ?>
                                      
				<form id="form-manager" class="content_wrap" action="" method="post">

				
					<div class="ct_heading clear col-md-12">
						<h3>View Lessons(<?=$tot_record?>)</h3>
						<ul>
						<li title="Add new Lesson"><a href="manage_lesson.php"><i class="fa fa-plus-circle"></i></a></li>
							
							<!-- <li>
								<button id="dlt_id" type="submit" name="dlt_id"><span class="glyphicon glyphicon-trash"></span></button>
							</li> -->
						</ul>
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if($error != '') {
							echo '<p class="error">'.$error.'</p>';
						} else {
						?>
							<table class="table-manager-user col-md-12">
								<colgroup>
									<col width="30">
									<col width="100">
									<col width="120">
									<col width="120">
									<col width="180">
									<col width="120">
									<col width="80">

								</colgroup>
								<tr>
									<th>#</th>
									<th>Lesson Name</th>
									<th>Objective</th>
                                    <th>Uploaded File</th>
									<th>Activity URL</th>
									<th>Quiz Created</th>
                                    <th>Action</th>
								</tr>
								<?php
                                    if( mysql_num_rows($results) > 0 ) {
							        while( $row = mysql_fetch_assoc($results) ) {
											
								?>
										
                            <tr>
								<td><input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/></td>
                                <td><?php echo $row['name']; ?></td>
                                <?php $obj_det= mysql_query("SELECT * FROM `terms` where id='".$row['objective_id']."'"); 
                                while ($obj_res = mysql_fetch_assoc($obj_det)) { ?>   
                                <td><?php print $obj_res['name']; ?></td>
                                    <?php } ?>
                                <td><?php echo $row['file_name']; ?>

                                	
                                </td>
                                <td><?php echo $row['url']; ?>
                                <br/>
                                <a href="https://intervene.io/questions/uploads/lesson/<?=$row['file_name']; ?>"
                                 class="btn btn-danger btn-xs">Download-<?=$row['name'];?></a>
                                	
                                </td>


								<?php $sql= mysql_query("select * from int_quiz where lesson_id='".$row['id']."'");

								if($status_sql = mysql_fetch_assoc($sql)){ 
								//print_r($status_sql); die; 

									$is_yes='Yes';  # option to  Print QUiz Questions
								}else $is_yes='No';

									?>

								<td> <?php  echo $is_yes; ?></td>

								

                                <td><a class="btn btn-xs btn-info" href="manage_lesson.php?edit_id=<?php echo $row['id']; ?>"> <i class="glyphicon glyphicon-pencil"></i> </a>


								<?php
						

								 if($is_yes=='Yes'){ 

									?>
								<a class="btn btn-xs btn-info delete"> <i class="glyphicon glyphicon-trash"></i>  </a>
								<?php } else{?>
								<a class="btn btn-xs btn-info" id="dlt_id" name="dlt_id"
								 href="lessons.php?dlt_id=<?php echo $row['id']; ?>"> <i class="glyphicon glyphicon-trash"></i> </a>
								 <?php } ?>


								<!-- <a href="" class="btn btn-danger btn-sm">Print Quiz</a> -->
								 <?php  if($is_yes=='Yes'){?>
							<button class="btn btn-danger btn-sm" type="submit" name="print_quiz" value="<?=$row['id']?>">
							<i class="fa fa-print"></i>Print Quiz</button>
							 <br>ID:<?=$row['id']?>
							 <?php  }?>

								 </td>  
                            </tr>
								<?php
										}
									} else {
										echo '<div class="clear"><p>There is no item found!</p></div>';
									}
								?>
							</table>
						<?php } ?>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        <input type="hidden" id="arr-user" name="arr-user" value=""/>
				</form>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<script>
$(document).ready(function(){
	$(".delete").click(function(){
		alert ("Lesson has already been deleted.");
	});
});
</script>
<?php include("footer.php"); ?>