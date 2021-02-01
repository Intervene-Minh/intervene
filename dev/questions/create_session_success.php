<?php

// sessions-list

/*

@ intervention_list.php

@ Repeat Sessions

@ add view page : 

@ show Student Board URL



@ Add Drhomework session list Link

$Arr_ses_type=array( 'homework' , 'intervention', 'drhomework');



@Only('homework' , 'intervention',) .

@Edit option::Changed

  $sql.=" AND ses_start_time >= '$one_day_before_date' "; 



**/

include("header.php");



$AllowDrhomeworkSession='no';



//echo '============';



$AllowSessionType='intervention';// SelectOnlydrhomeworkSeesionList



define('School_homework_url','school_homework_sessions.php');











$tab_sessions='int_schools_x_sessions_log'; # intervenetion and homework_help

$tab_ses_stuents='int_slots_x_student_teacher'; # student list in  

$tab_ses_quiz_answer='students_x_quiz'; # sesion student quiz answer

$tab_school_repeat_parent='int_schools_x_slots_create';







//echo 'Intervention Sessions';



$login_role = $_SESSION['login_role'];

if($login_role!=0 || !isGlobalAdmin()){

	header("location: index.php");

}



$sessoin_table="int_schools_x_sessions_log";

$error='';

$id = $_SESSION['login_id'];




   $sql=" SELECT * FROM $sessoin_table WHERE id=".$_GET['id'];



$results = mysql_query($sql);


$tot_record=mysql_num_rows($results);  

if( $tot_record)
{
    $row = mysql_fetch_assoc($results);
      $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name,last_name FROM users WHERE id=".$row['teacher_id']));

               $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));

                 $tot_std=($tot_std>0)?$tot_std:"XX";

             $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));    

         

             $int_school= mysql_fetch_assoc(mysql_query("SELECT district_id,SchoolName FROM schools WHERE SchoolId=".$row['school_id']));     

             // district_id 

          if($int_school['district_id']>0){

          $district=mysql_fetch_assoc(mysql_query(" SELECT  district_name FROM loc_district WHERE id=".$int_school['district_id']));     

          $districtName=$district['district_name'];

          

          }

          

          /// inAdmin Info SELECT * FROM `users` WHERE 1 

         $admin=mysql_fetch_assoc(mysql_query(" SELECT * FROM `users` WHERE id=1 ")); // Def

          

         // Exp time

        $sesStartTime=$row['ses_start_time'];

        $curr_time= date("Y-m-d H:i:s");

         

     $in_sec= strtotime($sesStartTime) - strtotime($curr_time);///604800 #days>+7 days

         

        $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));  

         $quiz['objective_name']=(!empty($quiz['objective_name']))?$quiz['objective_name']:"NA";

         //// list of students 

          $q=" Select sd.last_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";

            $q.=" WHERE ses.slot_id='".$row['id']."' ";

            $resss=mysql_query($q);

            $stud_str=array(); // middle_name

            while ($row2=mysql_fetch_assoc($resss)) {

              // last_name

   $stud_str[]=$row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];





            }  

            $stdList=(count($stud_str)>0)? implode(", ", $stud_str):"NA";

            // 

       $lesson=mysql_fetch_assoc(mysql_query("SELECT id,name FROM `master_lessons` WHERE id='".$row['lesson_id']."' "));
}
                                                                        

                                                                        

                            

 ?>











<div id="main" class="clear fullwidth">

	<div class="container">

		<div class="row">

			<div id="sidebar" class="col-md-4">

				<?php include("sidebar.php"); ?>

			</div>		<!-- /#sidebar -->

			<div id="content" class="col-md-8">
                          
                            <?php if( $tot_record)
{?>
                            <p> <div class="alert alert-success" role="alert" style="text-align: center"><strong>Your session has been created successfully</strong></div></p>
                            <div class="panel panel-default">
                                
  <!-- Default panel contents -->
  <div class="panel-heading">Session Details</div>
  <div class="panel-body">
   <table class="table ">
        <tr><td>Session ID</td><td> <?=ucwords($row['id'])?></td></tr>
 <tr><td>Session Time</td><td> <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?>   <?=date_format(date_create($row['ses_start_time']), 'h:i a');?></td></tr>
 <tr><td>Session Duration</td><td> <?=ucwords($row['session_duration'])?> mins</td></tr>
 <tr><td>Virtual board</td><td> <?=ucwords($row['board_type'])?></td></tr>
  <tr><td>Lesson</td><td> <?=$lesson['name']?></td></tr>
    <tr><td>School</td><td> <?=$int_school['SchoolName']?></td></tr>
      <tr><td>District</td><td> <?=$districtName?></td></tr>
      <tr><td>Class list of students</td><td> <?=$stdList?></td></tr>
</table>
  </div>

  <!-- Table -->
  
</div>
                        <p>
                            <a href="intervention_list_latest.php" class="btn btn-primary"> Go to session list </a>
                            <a href="edit_session.php?sid=<?php echo $row['id'];?>" class="btn btn-success"> Edit This Session </a>
                        </p>

                                    

<?php } else { ?>
                         <p> <div class="alert alert-danger" role="alert" style="text-align: center"><strong>Your session has not been created</strong></div></p>
<?php } ?>





				

			</div>		<!-- /#content -->

			<div class="clearnone">&nbsp;</div>

		</div>

	</div>

</div>		<!-- /#header -->




<?php include("footer.php"); ?>

