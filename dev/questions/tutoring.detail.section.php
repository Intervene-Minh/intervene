<div class="add_question_wrap clear fullwidth">

	 <?php  
                              $curr_time= date("Y-m-d H:i:s"); 
                             $in_sec= strtotime($ses_start_time) - strtotime($curr_time);///604800  

                             $sdate=date_format(date_create($ses_start_time), 'F d,Y');
                            $at_time=date_format(date_create($ses_start_time), 'h:i a');

                            ?>


                   <h4 style="color: #1b64a9; font-size:16px">Refrence Tutoring Date/Time:&nbsp;<?=$sdate?> at-<?=$at_time?> </h4>
                       
                         



<?php 



$res11= mysql_query(" SELECT student_id FROM int_slots_x_student_teacher WHERE slot_id=".$_GET['sid']);   
$st_arr=array();
while ($row= mysql_fetch_assoc($res11)) {
$st_arr[]=$row['student_id'];
}

$sel_class_id=$class_id;// master session
if(isset($_GET['cl'])){
$clss_det= mysql_fetch_assoc(mysql_query(" SELECT grade_level_id FROM classes WHERE id=".$_GET['cl'])); 
$sel_grade_id=$clss_det['grade_level_id'];
$sel_class_id=$_GET['cl'];
$st_arr=array();// re- assign
//  Grade id .    
}else{

}

//   chane time and    


?>






<?php 


$master_lessons=mysql_fetch_assoc(mysql_query(" SELECT * FROM master_lessons WHERE id=".$Tutoring['lesson_id']));
$quiz_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_quiz WHERE id=".$Tutoring['quiz_id']));
$terms=mysql_fetch_assoc(mysql_query(" SELECT id,name FROM terms WHERE id=".$Tutoring['grade_id']));
// 
// echo $terms['name'];

?>









<div id="textarea" style="display: block">

<span> <strong class="text-primary">
Time:</strong> </span> <?=$at_time?> <br/>


<span> <strong class="text-primary">
Grade:</strong> </span> <?=$terms['name']?> <br/>


<span> <strong class="text-primary">
Quiz:</strong> </span> <?=$quiz_det['objective_name']?> <br/>

<span> <strong class="text-primary">
Lesson:</strong> </span> <?=$master_lessons['name']?>  <br/>

<!-- <p class="text-success">  -->
<span> <strong class="text-primary">
Student List :</strong> </span> 






<?php 
$ses_id=$_GET['sid'];
$sql_student=" SELECT sx.slot_id,sx.launchurl,sx.student_id,s.id as sid,s.first_name,s.middle_name,s.last_name FROM int_slots_x_student_teacher sx
left join students s
ON sx.student_id=s.id
WHERE sx.slot_id=".$_GET['sid'];  


$res=mysql_query($sql_student);
while ($row= mysql_fetch_assoc($res)) {
$name=$row['first_name'];
$launchurl=$row['launchurl'];


if($Tutoring['board_type']=='braincert'){
echo $name.',&nbsp;';
echo '<p  class="text-danger"><a style="display: inline-block;word-break: break-all" href="'.$launchurl.'"> '.$launchurl.'</a></p> <br/>';
}else{
echo $name.',&nbsp; <br/>';
}

}

// $mster



?>



</div>



</div>