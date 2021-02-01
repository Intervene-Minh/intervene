<?php
/**
@ Ref. :: sessions-listing.php
@  upcoming sessions
@ Past sessions::

*/

///
 @extract($_GET) ;
@extract($_POST) ;

error_reporting(-1);
ini_set('display_errors', 1);

$ses_time_before=-2700; # 45X60# entire : 5 sec. after ses. end 30 min
$ses_2hr_before=-7200;

include("header.php");

//echo  TUTOR_BOARD ; 

// move to session//
if(isset($_GET['sesid'])&&$_GET['sesid']>0){
  // set session 
  $_SESSION['live_ses_id']=intval($_GET['sesid']);
  header("Location:tutor_board.php"); exit;
}

 //print_r($_SESSION);

 $cur_time= date("Y-m-d H:i:s");  // die;
     $time=strtotime($cur_time);
     $startTime = date("Y-m-d H:i:s", strtotime('-55 minutes', $time));
$endTime = date("Y-m-d H:i:s", strtotime('+55 minutes', $time));
 $one_hr_les=date("Y-m-d H:i:s", strtotime('-60 minutes', $time));
 ///*************

 $time_arr=array();
  $time_arr['curr_time']=date("Y-m-d H:i:s");
$time_arr['one_hr_les']=$one_hr_les;

 $time_arr['time_55_less']=$startTime;
 $time_arr['time_55_up']=$endTime;
      $time_arr['24_hour_back']=date('Y-m-d H:i:s',strtotime('-24 hours'));
      $time_arr['24_hour_next']=date('Y-m-d H:i:s',strtotime('+24 hours'));
      $time_arr['2_hour_less']=date('Y-m-d H:i:s',strtotime('-2 hours'));

    //print_r($time_arr); 
//////////Validate Site Access//////////
//print_r($_SESSION);
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}
/////////////////////////////////////
  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";
//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

 $page_name='My Sessions';
$error='';
$id = $_SESSION['ses_teacher_id'];



////////////Rejected////////////////////////////////////////////

////////////Rejected////////////////




                $schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
//Listing ///


                                
                           
                              $curr_time= date("Y-m-d H:i:s"); #currTime
                            
                                $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                               // $qq.=" AND tut_status='STU_ASSIGNED' ";
                               
                             
                                if(isset($_GET['id']))
                                $qq.=" AND id='".$_GET['id']."' ";#only Assigned
                                else{
                               #     $qq.=" AND ses_start_time>'$curr_time' ";
                                 $qq.=" AND ses_start_time>'".$time_arr['2_hour_less']."' ";
                                    
                                 $qq.=" AND tut_teacher_id='$id' ";
                                $qq.=" ORDER BY ses_start_time ASC ";    
                                }
                          
                               
                                
                                
                                /////////////////////////
                                $session_type=(isset($session_type))?$session_type:"upcoming" ;
                                if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                  $tutor= $_SESSION['ses_teacher_id'];
                                  // echo $session_type ; die;
                           
                                  if($session_type=="past"){
                                     $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                
                                $qq.=" AND ses_start_time<'".$curr_time."'";
                                 $qq.=" AND tut_teacher_id='$tutor' ";
                                 
                                $qq.=" ORDER BY ses_start_time DESC "; 
                               
                                  }elseif($session_type=="upcoming"){
                                    $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                 
                                $qq.=" AND ses_start_time>'".$curr_time."'";
                                  $qq.=" AND tut_teacher_id='$tutor' ";
                                $qq.=" ORDER BY ses_start_time ASC ";   
                                  }elseif($session_type=="all"){
                                   $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                  $qq.=" AND tut_teacher_id='$tutor' ";
                                
                                $qq.=" ORDER BY ses_start_time ASC ";   
                               
                                  }
                                  
                                  
                               //    if(!empty($email))
                                // $qq.=' AND email LIKE "%'.$email.'%"  ';
                                
                                    
                                   
                                }
                                
                              //////////////
                               // echo $qq;
                                
                                
                                $results = mysql_query($qq);
                $tot_record=mysql_num_rows($results);
                                
                             // echo '<pre>',$qq;echo '<br/>' ; 

  //end              
  ?>



<script>
    

///
$(document).ready(function() {
   $('#setdate').change(function() {
     var parentForm = $(this).closest("form");
     if (parentForm && parentForm.length > 0)
       parentForm.submit();
   });
});
</script>


<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
                            <div class="table-responsive">
                              <form id="search-users" method="GET" action=""  >
						<table class="table">
							<tbody><tr>
								<td><label>Filter:</label></td>
		<td>
                    <select name="session_type">
                     <option value="all" <?php echo (isset($session_type)&&$session_type=="all")?'selected':NULL; ?> >All</option>
                        <option value="upcoming" <?php echo (isset($session_type)&&$session_type=="upcoming")?'selected':NULL; ?> >Upcoming sessions</option>
                        <option value="past" <?php echo (isset($session_type)&&$session_type=="past")?'selected':NULL; ?>>Past sessions</option>
                                    
                                </select>
                    &nbsp;<input name="action" class="btn" value="Search" type="submit">    
                
                </td>
		
								
								
								
							</tr>
						</tbody></table>
					</form>  
                                
                                
                                
                            </div>
                                                 
                            
                            
		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if(isset($error)&&$error != '') {
							echo '<p class="error">'.$error.'</p>';
						} 
          $my_sessions = []; ?>
                                           
                                            
	<table class="table-manager-user col-md-12">

							
	<?php
                $tutor= $_SESSION['ses_teacher_id'];                      
                if( mysql_num_rows($results) > 0 ) {
	            	while( $row = mysql_fetch_assoc($results) ) {
			// teacher_id								
			// TutTeacher Statatus					
               $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=".$row['teacher_id']));
               $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));
                 $tot_std=($tot_std>0)?$tot_std:"XX";
             $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));    
          $int_school= mysql_fetch_assoc(mysql_query("SELECT SchoolName FROM schools WHERE SchoolId=".$row['school_id']));  

          $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));    
             // List of students 
          //$quiz objective_name             
            $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$row['id']."' ";
            $resss=mysql_query($q);
            $stud_str=array(); // middle_name
            while ($row2=mysql_fetch_assoc($resss)) {
                $stud_str[]=$row2['first_name'].' '.$row2['middle_name'];
            }  
            $stdList=(count($stud_str)>0)? implode(",", $stud_str):"NA";
            // G:i a   
            // special_notes
            $row['special_notes']=(!empty($row['special_notes']))?$row['special_notes']:"NA";
           $sesStartTime=$row['ses_start_time'];
            $in_sec= strtotime($sesStartTime) - strtotime($curr_time);
            ////////////
             $quiz=mysql_fetch_assoc(mysql_query("SELECT q. * , l.id AS lesid, l.name as les_name, l.file_name
                      FROM `int_quiz` q
                      LEFT JOIN master_lessons l ON q.lesson_id = l.id
                      WHERE q.id =".$row['quiz_id']));

             $lesson_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `master_lessons` WHERE id=".$row['lesson_id']));




              //print_r($quiz); die; 
             $lesson_download="https://intervene.io/questions/uploads/lesson/".$lesson_det['file_name'];// 4358Question.pdf
          
             $my_sessions[] = array(
                'session_date' => date_format(date_create($sesStartTime), 'F d,Y'),
                'session_time' => date_format(date_create($row['ses_start_time']), 'h:i a'),
                'objective' => $quiz['objective_name'],
                'class_list' => $stdList,
                'special_notes'=>$row['special_notes'],
                'download' => "https://intervene.io/questions/uploads/lesson/".$lesson_det['file_name'],
                'school_name' => $int_school['SchoolName'],
                'students_assign'=>$tot_std -$status_arr['STU_ASSIGNED'],
                'assigned_to' => $tut_th['f_name']." ".$tut_th['lname'],
                'session_id' => $row['id'],
                'board_type' => ucwords($row['board_type']),
                'date'=>date_format(date_create($row['created_date']), 'F d,Y'),

            );
   
            
            ?>
										                                               

								<?php
                                        }
                                        
            
            print_r($my_sessions);die;

									} else {
										echo '<div class="clear"><p>There is no item found!</p></div>';
									}
								?>
							</table>
						
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        <input type="hidden" id="arr-user" name="arr-user" value=""/>
				</form>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>