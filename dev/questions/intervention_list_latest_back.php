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



if(isset($_POST['delete_sumbit'])){

 // print_r($_POST); die;

    //  Delete session and increase counter  //in princple account . 

   

	$arr = $_POST['arr-user'];

       if(!empty($arr))

       $ses_arr= explode(",", $arr);

       

 ////////////////////////// Delete sessions//////////////////   

       $tot_records=count($ses_arr);

       //print_r($tot_records); die;

 foreach ($ses_arr as $getid) {

    // $getid :sesid

     



  $sql="SELECT * FROM $sessoin_table WHERE 1";

    $sql.=" AND id='$getid' ";

 $ses_det=mysql_fetch_assoc(mysql_query($sql));

  $school_id=$ses_det['school_id'];// school_id

 

  $school=mysql_fetch_assoc(mysql_query("SELECT * FROM schools WHERE SchoolId=".$school_id));



   $d1=mysql_query(" DELETE FROM int_slots_x_student_teacher WHERE slot_id=".$getid);

   $d2=mysql_query(" DELETE FROM $sessoin_table WHERE id=".$getid);

   #3.crease:1 sess in account.

     //Available intervention Slot

  //  $remain_ses=intval($school['avaiable_slots'])+1;

  //$a=mysql_query(" UPDATE schools SET avaiable_slots='".$remain_ses."' WHERE SchoolId='".$school_id."' "); //+1

  

     

 //    

 }

 ////////////////////////// Delete sessions//////////////////   

  

        $tot_records=($tot_records>0)?$tot_records.'-Record deleted.':"Select a record";

      echo "<script>alert('".$tot_records."');location.href='".$_SERVER['PHP_SELF']."';</script>";

       

        

}







$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");

//////////



                          

                            $start_date =date('Y-m-d h:i:s');

                            $sql=" SELECT * FROM $sessoin_table WHERE 1 AND type='$AllowSessionType' ";

                            $sql.=" ORDER BY ses_start_time ASC ";// DESC

              

                        //  24 hours Back to Next session 

                        $one_day_before_date=date('Y-m-d', strtotime($start_date. ' - 1 days'));

                        //echo  '=one_day_before_date'.$one_day_before_date;  die; 

                        $sql.=" AND ses_start_time >= '$one_day_before_date' ";        













                                  /////Search////////////////////////////////

                                @extract($_GET) ;  

                                 if(isset($_GET['action'])&&$_GET['action']=="Search"){

                                   

                           // $sql=" SELECT * FROM $sessoin_table WHERE 1 ";

                             $sql=" SELECT * FROM $sessoin_table WHERE 1 AND type='$AllowSessionType' ";

                                  if(!empty($school_id)){ // school_id

                                $sql.=" AND school_id='$school_id' ";  

                               

                                  }

                                  

                            if(!empty($from_date)){

                           

       $date = DateTime::createFromFormat("m/d/Y" , $from_date);

            $from_date1= $date->format('Y-m-d');      

                                

                          $sql.=" AND ses_start_time >= '$from_date1' "; // 2018-04-10     

                            }

                                  

                            if(!empty($end_date)){

                               

                                 $date = DateTime::createFromFormat("m/d/Y" , $end_date);

            $end_date1= $date->format('Y-m-d');

             $end_date1.=" 23:59:59";

                          $sql.=" AND ses_start_time <='$end_date1' ";      

                            }

                                   

                          

                      $sql.=" ORDER BY ses_start_time ASC ";     

                                   

                                }else{ //No search 



                                  $sql=" SELECT * FROM $sessoin_table WHERE 1 AND type='$AllowSessionType' ";

                           // STU_ASSIGNED

                               

                                  if(isset($_GET['sid'])&&$_GET['sid']>0){

                     $sql=" SELECT * FROM $sessoin_table WHERE id=".$_GET['sid'];

        



                                  }

                          //////////DefaultFor-page/////////////////

                                  //  24 hours Back to Next session 

                        $one_day_before_date=date('Y-m-d', strtotime($start_date. ' - 3 days'));

                        //echo  '=one_day_before_date'.$one_day_before_date;  die; 



                        $sql.=" AND ses_start_time >= '$one_day_before_date' ";

                         $sql.=" ORDER BY id DESC";// DESC

                      #  $sql.=" ORDER BY ses_start_time ASC ";// DESC







                                }  //No search                                      

                           ////////////////////

                                



                                





// echo   'Test:'  , $sql; die; 



$results = mysql_query($sql);

$tot_record=mysql_num_rows($results);  





 ////Print QUestions//////// 

 if(isset($_POST['print_quiz'])) {





  //print_r($_POST); die;

   $les_id=$_POST['print_quiz'];// mysql_query



   $quiz_det=mysql_fetch_assoc(mysql_query("SELECT * FROM int_quiz WHERE lesson_id= '$les_id' "));

  

 $lesson_row=mysql_fetch_assoc(mysql_query("SELECT id,name as les_name FROM master_lessons WHERE id=".$les_id));



  //print_r($lesson_row);

   //$quiz_id=2; ## only question

    //  $quiz_id=24; # Passage quiz



    $quiz_id=$quiz_det['id'];



   $_SESSION['ses_lesson_name']=$lesson_row['les_name'];



################################################mysql_query

   $qn_res =mysql_query('SELECT qn_id FROM int_quiz_x_questions WHERE quiz_id = \'' .$quiz_id. '\' ORDER BY num ASC');



     //echo $qn_res; die; 





    $_SESSION['list'] = array();

     while ($question = mysql_fetch_assoc($qn_res)) {

         $_SESSION['list'][] = $question['qn_id'];

     }

    //  check if passage included or not  :: mysql_query

     $q_ids= implode(',', $_SESSION['list']);

     $sql=mysql_query("SELECT DISTINCT `q`.`passage` , p . * 

         FROM `questions` q

         INNER JOIN `passages` p ON `q`.`passage` = `p`.`id`

         WHERE q.`id`

         IN (".$q_ids.")");



     

   

    // print_r($_SESSION['list']); die;

      // Allow Quiz questions to print.



     if(mysql_num_rows($sql)>0){

       //  echo 'PassageQuestionsExist'.mysql_num_rows($sql);die; 

         header("Location: inc/ajax-quiz-passage-print.php");

     }else header("Location: inc/ajax-quiz-print.php"); # NEW 

   

       

       

     die();



}                                                                          

                                                                        

                            

 ?>











<script>

     

    

    

	$(document).ready(function(){

		$('#delete-user').on('click',function(){

			var count = $('#form-manager .checkbox:checked').length;

			$('#arr-user').val("");

			$('#form-manager .checkbox:checked').each(function(){

				var val = $('#arr-user').val();

				var id = $(this).val();

				$('#arr-user').val(val+','+id);

			});

			var str = $('#arr-user').val();

			$('#arr-user').val(str.replace(/^\,/, ""));

			return confirm('Are you want to delete '+count+' record?');

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

				

                      

                            <div class="table-responsive">

					 <form id="search-users" method="GET" action=""  >

						         <select name="school_id">

                        <option  value=""  >School</option>

                        <?php 

                        $school_list=mysql_query("SELECT s. * , ses.school_id

FROM int_schools_x_sessions_log ses

INNER JOIN schools s ON ses.school_id = s.SchoolId

WHERE 1

GROUP BY ses.school_id

ORDER BY s.SchoolName");



                         while( $row = mysql_fetch_assoc($school_list) ) { 

       $sel=(isset($_GET['school_id'])&&$_GET['school_id']==$row['school_id'])?'selected':'';



                          ?>

        <option  <?=$sel?> value="<?=$row['school_id']?>"><?=$row['SchoolName']?></option>

                        <?php } ?>



                       

                                </select>

                                From:

                    <input name="from_date"  value="<?=(isset($from_date))?$from_date:NULL;?>"

                           class="datepicker" data-date-format="mm/dd/yyyy"  >

                                           

                   To:

                   <input name="end_date"

                          value="<?=(isset($end_date))?$end_date:NULL;?>"  

                          class="datepicker" data-date-format="mm/dd/yyyy"  >

                        

                    

                    

                    &nbsp;<input name="action" class="btn" value="Search" type="submit">    

                

                

					</form>

				</div>

				

                            

                            

                            

                            

				<form id="form-manager" class="content_wrap" action="" method="post">

					<div class="ct_heading clear">

						

             <a href="<?=$_SERVER['PHP_SELF']?>"  title="Manage Intervention Session" 

            class="btn btn-success btn-md">Intervention Sessions(<?=$tot_record?>)</a>



            &nbsp;&nbsp;&nbsp;&nbsp;

           <!-- <h3>Manager Homework Help Sessions</h3> -->

           



           





						<ul>



						<li title="Create new"><a href="add_session.php"><i class="fa fa-plus-circle"></i></a></li>	

							<li>

								<button id="delete-user" type="submit" name="delete_sumbit"><span class="glyphicon glyphicon-trash"></span></button>

							</li>

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

									<col width="220">

									<col width="280">

									

									

									

									<col width="155">

								</colgroup>

								<tr>

									<th>#</th>

									<th>Date/Time</th>

									<th>Details</th>

                                                                        

									

									<th>Date Created</th>

								</tr>

								<?php

                                                                if( mysql_num_rows($results) > 0 ) {

                                                                    

				while( $row = mysql_fetch_assoc($results) ) {

										

								

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

          $q=" Select sd.last_name,sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";

            $q.=" WHERE ses.slot_id='".$row['id']."' ";

            $resss=mysql_query($q);

            $stud_str=array(); // middle_name

            while ($row2=mysql_fetch_assoc($resss)) {

              // last_name

   $stud_str[]=$row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];





            }  

            $stdList=(count($stud_str)>0)? implode(",", $stud_str):"NA";

            // 

       $lesson=mysql_fetch_assoc(mysql_query("SELECT id,name FROM `master_lessons` WHERE id='".$row['lesson_id']."' "));



          ?>

										

                                                                

                                                                

                                                                <tr>

											<td>



												<input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>

											</td>

                                                                                        

						                      <td>

                                   <?php //='===diff='.$in_sec; # test?>

                                 <span title="session time">

                                  <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br>     

                                       

                                    </span>



                                    







                                    

                            <a style="font-weight: bold;" href="intervention_det.php?ses_id=<?=$row['id']?>" 

                              class="btn btn-success btn-xs" title="Start Time">

                    <?=date_format(date_create($row['ses_start_time']), 'h:i a');?></a><br/> 

                                    



                                    <!-- Virtual board type -->

                                      



                                      <span><strong class="text-primary">Virtual board</strong>:

                                        <?php 
                                        if($row['board_type'] == 'wiziq')
                                           echo ucwords($row['board_type']);
                                        else if($row['board_type'] == 'newrow')
                                        {
                                            if($row['ios_newrow'] == 1)
                                                echo 'IOS Newrow';
                                            else
                                                echo ucwords($row['board_type']);
                                        }
                                        ?> 
                                      
                                      </span>



                                       <br/>

                                    









                                    <strong class="text-primary">

                                 Lesson:</strong><?=$lesson['name']?>

                                 &nbsp;&nbsp;



                                 <button class="btn btn-danger btn-sm" 

                                 type="submit" name="print_quiz" value="<?=$lesson['id']?>">

              <i class="fa fa-print"> </i>Print Quiz</button>

                                 <br/>

                                    

                                   



                                 <strong class="text-primary">

                                 Class list of students:</strong>[<?=$stdList?>]

                               



                               <br/>

                              

                                 

                               

                                 

                                 

                                 

                                            

                                     

                                

                          

                                

                                    

                                                    

                                            </td>





                                          

                                            

                                            

                         

                                                

                                                

                                              <td>

                                    <strong class="text-primary">

                                School:</strong><?=$int_school['SchoolName']?><br/>  

                                

                               <strong class="text-primary">

                                District:</strong><?=$districtName?><br/>

                                             </td>







                                              <td>

                                           

                                            <a href="intervention_det.php?ses_id=<?=$row['id']?>"

                                             class="btn btn-danger btn-sm">View</a>

                                            

                                   <a href="edit_session.php?sid=<?=$row['id']?>" class="btn btn-info btn-sm">Edit</a>

                                    &nbsp;

  <?php //if($in_sec>0){?>

                                   <a title="Delete,This session" style="display: none;"

                                      href="list-tutor-sessions.php?del_id=<?=$row['id']?>" class="btn btn-danger btn-xs">Delete</a>    

                                   <?php //}?>

                                   <br/>









                                              <strong class="text-primary">Create Date:</strong><br/>       

                                           <?=date_format(date_create($row['created_date']), 'F d,Y');?> 

                                                

                                                <br/>

                                 <strong class="text-primary">Session:</strong>incomplete<br/>       

                                 <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>&nbsp;

                                   <br/>

                             <a target="_blank" href="create_session_upcoming.php?sid=<?=$row['id']?>"

                               class="btn btn-danger btn-sm">+Try Duplicate session creation </a>

                                          

                                           



                                   



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

     $(document).ready(function () {

         $('.datepicker').datepicker({

    format: 'mm/dd/yyyy',

    startDate: '-3d'

});});

    

    </script>

<?php include("footer.php"); ?>

