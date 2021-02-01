<?php
include('inc/connection.php'); 
$scored_grade=0;
$divided_grade=0;
extract($_REQUEST);

$str="SELECT * FROM `telpas_student_speaking_grades`WHERE
 intervene_student_id='".$InereveneStuID."' AND `course_id`='".$course_id."' ORDER BY `question_id`"; 

$audio=mysql_query($str);
$num_rows = mysql_num_rows($audio);
if($num_rows > 0){
while ($row = mysql_fetch_assoc($audio)) 
{

$scored_grade=$row['scored_grade_number']+$scored_grade;
$divided_grade=4 + $divided_grade;
	?>
<div class="row br-2">
  	    <div class="col-md-5">
  	    	<?php
if(strstr($row['file_audio'],".wav")=='.wav' || strstr($row['file_audio'],".MP3")=='.MP3'){

	$src = 'http://intervene.io/questions/audio/uploads/recordings/'.$row['file_audio'];
}
else{

	$src =$row['file_audio'];
}
?>
  		<audio controls="" src="<?php echo $src?>"> </audio>
			</div>
			<div class="col-md-4">
						<select class="form-control" name="score[]" required="" >
						<option value="">Select</option>
						<option value="1" <?php if($row['scored_grade_number']==1){ echo 'selected';}?>>1</option>
						<option value="2" <?php if($row['scored_grade_number']==2){ echo 'selected';}?>>2</option>
						<option value="3" <?php if($row['scored_grade_number']==3){ echo 'selected';}?>>3</option>
						<option value="4" <?php if($row['scored_grade_number']==4){ echo 'selected';}?>>4</option>
						</select>
						<input type="hidden" name="id[]" value="<?php echo $row['id']?>">
						<input type="hidden" name="question_id[]" value="<?php echo $row['question_id']?>">
						<input type="hidden" name="student_id" value="<?php echo $row['intervene_student_id']?>">
						<input type="hidden" name="teacher_id" value="<?php echo $row['teacher_id']?>">
						<input type="hidden" name="course_id" value="<?php echo $row['course_id']?>">
						<input type="hidden" name="telpas_student_id" value="<?php echo $row['telpas_student_id']?>"></div>
						 <div class="col-md-2">
						 	 <a href="javascript:void(0)" class="btn btn-info" data-toggle="collapse" data-target="#button<?php echo $row['question_id']?>" onclick="_get_description(<?php echo $row['question_id']?>);">See Question</a>
						 	
						 </div>


						  <!-- Quesion result -->
							  <div id="button<?php echo $row['question_id']?>" class="row col-md-12 collapse data<?php echo $row['question_id']?>" style="padding: 40px">
								<i class="fa fa-spinner fa-spin spin" style="font-size:24px"></i>
							  </div>


                    </div>
<?php
}

$scored_grade_number=(($scored_grade/$divided_grade)*100);
if($scored_grade_number >0 ){
?>
<!--<div class="row br-2 text-center"> Total Grade:&nbsp; <span class="grade">
    <?php 
    
     if($scored_grade_number >= 1 && $scored_grade_number <= 25)
                    echo '<span  style="color:#d9534f;font-weight:bold">Beginner</span>'; 
                 else if($scored_grade_number >= 26 && $scored_grade_number <= 50)
                    echo '<span style="color:#f0ad4e;font-weight:bold">Intermediate </span>'; 
                 else if($scored_grade_number >= 51 && $scored_grade_number <= 75)
                    echo '<span style="color:#337ab7;font-weight:bold">Advanced </span>'; 
                 else if($scored_grade_number >= 76 && $scored_grade_number <= 100)
                    echo '<span style="color:#5cb85c;font-weight:bold">Advanced High </span>'; 
                 else
                     echo 'N/A';
  
    
    ?></span></div> -->
<?php
}
$string="SELECT count(*)  as cnt FROM `telpas_speaking_completed` WHERE 
 `course_id`='".$course_id."' AND


 intervene_student_id=".$InereveneStuID; 
 $qu=mysql_query($string);
$result = mysql_fetch_assoc($qu);

//if($result['cnt'] == 0 )
{
?>
<input type="hidden" name="speaking_stu_id" id="speaking_stu_id" value="<?php echo $InereveneStuID;?>">
<input type="hidden" name="speaking_course_id" id="speaking_course_id" value="<?php echo $course_id;?>">
<input type="hidden" name="action_type" id="action_type" value="speaking">
<div class="row br-2 text-center"><button type="submit" class="btn btn-default" style="padding: 6px 39px;">Save</button></div> 
<?php }
}

else{?>

	<div class="row br-2 text-center">Sorry ! Not Any Audio File Available</div> 
<?php }
?>