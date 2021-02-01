<?php
include('inc/connection.php'); 
extract($_REQUEST);
$str="SELECT id,max_grade_number,file_audio,question_id FROM `telpas_student_speaking_grades`WHERE intervene_student_id=".$InereveneStuID; 
$audio=mysql_query($str);
while ($row = mysql_fetch_assoc($audio)) 
{
	

?>
<!--
<li>
<audio controls>
  <source src="https://intervene.io/questions/audio/uploads/recordings/<?php echo $row['file_audio']?>" type="audio/ogg"></audio>
  	<a href="javascript:void(0)" onclick="_giveGrade(<?php echo $InereveneStuID?>,<?php echo $row['question_id']?>)" class="GiveGrade" >Give Grade</a>
  </li>-->
  <div class="row br-2">
  	<div class="col-md-5">
  		<audio controls="">
  			<source src="https://intervene.io/questions/audio/uploads/recordings/<?php echo $row['file_audio']?>" type="audio/ogg"> </audio>
						 </div>
						 <div class="col-md-2">
						<select class="form-control" name="score[]">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						</select>
						<input type="hidden" name="question_id[]" value="<?php echo $row['question_id']?>">
						<input type="hidden" name="student_id[]" value="<?php echo $row['intervene_student_id']?>">
						 </div>
						 <div class="col-md-5">
						      <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo-2">See Question</button>
							  <div id="demo-2" class="collapse">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit.
							  </div>
						 </div>
                    </div>
<?php
}
?>


