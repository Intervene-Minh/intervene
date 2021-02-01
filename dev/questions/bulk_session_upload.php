<?php
/**

@Import Bulk : Questions csv.

*/


require_once ('translate/vendor/autoload.php');
use \Statickidz\GoogleTranslate;

$error	= '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
$current_date = date('Y-m-d H:i:s');

include("header.php");
include('libraries/newrow.functions.php');
if($_SESSION['login_role'] !=0) { //not admin
	header('Location: folder.php');
	exit;
}


//if not admin but want to edit return index
require_once('inc/check-role.php');
$role = checkRole();
if($quesId>0 && $role!=0){
	header('Location: index.php');
	exit;
}


 function _create_user($role_type='student',$user_arr=array()){ // tutor| student

     $get_user_email=$_GET['email']='Rajk@gmail.com';



////////////////////////////////////

$userId=time();

 //echo $_SESSION['ses_admin_token']; die; 

 $post = [

    'user_name' =>$user_arr['user_name'], //'test_tutor_'.$userId,

    'user_email' =>$user_arr['email'], //'test11@gmail.com',

    'first_name' =>$user_arr['first_name'],

     'last_name' =>$role_type,

     'CompanyUser' =>$role_type, // Instructor | Student

    

    

    

    //'gender'   => 1,

];



   //print_r($post);  die; 



   ////////////////////////////





//print_r($return_data); die;  $_SESSION['ses_admin_token']

// $token="ba2fcb22904057f9bf5ec0a2e785e3cd";

$token=$_SESSION['ses_admin_token']; // 5fc8c417a486296fb3fc334293b2b54c







///////////////////////



$ch = curl_init('https://smart.newrow.com/backend/api/users'); // Initialise cURL

       $post = json_encode($post); // Encode the data array into a JSON string

       $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token

       curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header

       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

       curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST

       curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields

       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects

       $result = curl_exec($ch); // Execute the cURL statement

       $user_row= json_decode($result); 

       curl_close($ch); // Close the cURL connection



        //return $result='==='; 

        

        return $user_row; 



     //  print_r($user_row);exit();  //die;



      // return json_decode($result); // Return the received data











   }
   
   
 function dbc_add_user($studentId,$newrow_user_id){



   



   $Student=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE id= '$studentId' ")); //TestStudent

    

   // print_r($Student); die; 



   $StudentEmail='NewStudent_'.$Student['id'].'@intervene.io';

   $StudentUsername='NewStudent_'.$Student['id'].'';



  // $user_arr=array('email'=>$StudentEmail,

  //                 'user_name'=>$StudentUsername, // UNQ

  //                 'first_name' =>$Student['first_name'],

  //                 'last_name' =>'Student', // Student| Tutor

  //                 'role_type' =>'Student', // Instructor | Student

  //                  );



  

  //  $StudentExist=mysql_fetch_assoc(mysql_query("SELECT * FROM newrow_students WHERE stu_intervene_id= '$studentId' ")); #

     

   

  $str="SELECT * FROM newrow_students WHERE stu_intervene_id= '$studentId'";

  $qr=mysql_query($str);

  $StudentExist=mysql_num_rows($qr);

                          

    if($StudentExist > 0 ){

     

     $msg=$studentId.'student,  UPDATED ssuccesfully, '.$newrow_user_id;

     

 $sql="UPDATE newrow_students SET  newrow_ref_id='$newrow_user_id',newrow_email='$StudentEmail',newrow_username='$StudentUsername' WHERE stu_intervene_id=".$studentId;

      // echo $sql; die; 

      $Add=mysql_query($sql);

      $res='Updated';



    }else{  // AddStudentTo{newrow_students}

   $sql="INSERT INTO newrow_students SET stu_intervene_id='$studentId',newrow_ref_id='$newrow_user_id',newrow_email='$StudentEmail',newrow_username='$StudentUsername'  ";

       //echo $sql; die; 

       $Add=mysql_query($sql);

      $msg= $studentId.'student,  added ssuccesfully, '.$newrow_user_id;



      $res='Added';   

     }



     return $res;



   }

if( isset($_POST['upload']) ) { 
    

    $sql_tutor = " SELECT id,f_name,lname,email FROM gig_teachers WHERE notify_all='yes' OR notify_jobs='yes' ";
    $resul_tutor = mysql_query($sql_tutor);
    $arr_notify_tutors = array();// tutor list
    while ($line = mysql_fetch_assoc($resul_tutor)) { 
      $arr_notify_tutors[]=$line;
    }
   
     $filename = $_FILES["file"]["tmp_name"];	 	
     if($_FILES["file"]["size"] > 0)
     {
	$file = fopen($filename, "r");
        $failed = 0;
        $success = 0;
        $i = 0;
	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	{ 
//           echo "<pre>";
//           print_r($getData); exit;
           if($i>=1)
           {
               $duration = $getData[7];
               $selected_date = (!empty($getData[5])) ? $getData[5]: date("d/m/Y"); 
               $start_date_arr = explode("/", $selected_date);
               
               $selected_date = $start_date_arr[2]."-".$start_date_arr[1]."-".$start_date_arr[0];
               $session_date_ymd = date('Y-m-d H:i:s', strtotime($selected_date." ".$getData[6]));  
               $end_time =  date('Y-m-d H:i', strtotime('+'.$duration.'minutes', strtotime($session_date_ymd)));
               $activity_time = date('Y-m-d H:i', strtotime('-5 minutes', strtotime($session_date_ymd)));
               
               $district = mysql_fetch_assoc(mysql_query("SELECT * FROM loc_district WHERE district_name = '".$getData[1]."'"));
               $district_id = $district['id'];
              
               $school = mysql_fetch_assoc(mysql_query("SELECT SchoolId FROM schools WHERE SchoolName = '".$getData[2]."' AND district_id = ".$district_id));
               $school_id = $school['SchoolId'];
               
               $grades = mysql_fetch_assoc(mysql_query("SELECT t. * , p.grade_level_id, p.permission FROM school_permissions p
                         LEFT JOIN terms t ON p.grade_level_id = t.id WHERE p.school_id =".$school_id." AND t.name = '".$getData[3]."'"));
               $grade_id = $grades['id'];
           
               $lessons = mysql_fetch_assoc(mysql_query("SELECT id FROM master_lessons WHERE name = '".$getData[8]."'" ));
               
               $lesson_id = (isset($lessons['id'])) ? $lessons['id']:0;
               
               $last_quiz = mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE lesson_id='$lesson_id' LIMIT 1 "));
               $quiz_id = $last_quiz['id'] = (isset($last_quiz['id'])) ? $last_quiz['id']:0;

         
                if($getData[9] == '1')
                {
                    $defaultBoard = 'newrow';
                    $curr_active_board = $defaultBoard;
                    $ios_newrow = 1;
                }
                else
                {
                  $defaultBoard = 'newrow';
                  $curr_active_board = (isset($getData[0])) ? $getData[0] : $defaultBoard;
                  $ios_newrow = 0;
                }
                
                
                $bilingual_test = 0;
                $certificate = 0;
                if($getData[10] == 1)
                {
                       $bilingual_test = 1;
                       $certificate = 0;
                }
                else
                {
                    $bilingual_test = 0;
                    if($getData[11] == 'Teacher')
                       $certificate = 2;
                    else if($getData[11] == 'ESL')
                       $certificate = 3;
                    else if($getData[11] == 'Bilingual')
                       $certificate = 4;
                }
                
                $client_id = 'Intervene123456';
              
             $sql_session = " INSERT INTO int_schools_x_sessions_log SET Tutoring_client_id='$client_id',curr_active_board='$curr_active_board',board_type='$curr_active_board',type='intervention',activity_start_time='$activity_time',ses_start_time='$session_date_ymd',"
                    . "ses_end_time='$end_time',session_duration='$duration',start_date='$selected_date', "
                    . "school_id='$school_id',lesson_id='$lesson_id',quiz_id='$quiz_id',grade_common='".$grade_id."',
                    grade_id='".$grade_id."',bilingual_test = '".@$bilingual_test."',certificate = '".$certificate."', "
                    . "created_date='".date("y-m-d H:i:s")."',district_id='$district_id', ios_newrow = '$ios_newrow' ";
              
                 
                $insert = mysql_query($sql_session)or die(mysql_error());
                $parent_id = mysql_insert_id();
                $first_ses_id = $parent_id;
                
                $ses_ids[] = array(
                                    'ses_id' => $parent_id,
                                    'ses_lesson_id' => $ses_lesson_id,
                                    'quiz' => $last_quiz['id']
                             );
            
                
              //  $_SESSION['ses_list_ids']=$ses_ids;
  
                foreach($ses_ids as $key => $line){
                   
                   $ses_id  = $line['ses_id'];
                   $ses_row = mysql_fetch_assoc(mysql_query(" SELECT * FROM `int_schools_x_sessions_log` WHERE id=".$ses_id));
 
                   $board_api_key = 'BlOM11ettmLhEMiRqRui';
                   $arr = array();
             
                   $arr['title'] = $title = 'Intervention_'.$ses_id;
                   $arr['date_start'] = date('Y-m-d', strtotime($ses_row['ses_start_time']));
                   $arr['start_time'] = date('h:i A', strtotime($ses_row['ses_start_time']));    
                   $arr['end_time'] = date('h:i A', strtotime($ses_row['ses_end_time'])); 

                    $arr['currency'] = 'usd';
                    $arr['ispaid'] = 1;

                    $arr['is_recurring']  =0;
                    $arr['repeat'] = 0;
                    
                    $arr['end_classes_count'] = 8;
                    $arr['seat_attendees'] = 8;
                    $arr['record'] = 1;
              

                    $get_class_id=9999; 

                    $observer_1_url=null;  $observer_2_url=null;$observer_3_url=null;
                    $observer_1_url='demo.com';
           
                   $Up = mysql_query(" UPDATE int_schools_x_sessions_log SET observer_url_1='$observer_1_url',braincert_class='$get_class_id' WHERE id=".$line['ses_id']);

                   $ses_id = $line['ses_id'];
                   $ses_lesson_id = $line['ses_lesson_id'];  // quiz
                   $quiz_id = $line['quiz'];
            
                   job_notify_tutors($arr_notify_tutors, $ses_id);// for each session
         
                   if(!empty($getData[4]))
                   {
                      
                       $stuList = explode(",",$getData[4]);
                 
                   for($j = 0; $j < count($stuList); $j++)
                   {
                     
                       $student = mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE username='".$stuList[$j]."'"));
                       $student_id = $student['id'];
                       $school_id = $school_id;
                       $district_id = $district_id; 
                    
                       $teacher = $student['teacher_id'];
                       $stu_name = $student['first_name'];

                       ///////////Get Board URL /////////
                       $student_board_url = 'stoped.com';
                       $student_board_url = 'NA';# Not created
                       $student_board_class = $get_class_id = 9999;


                    
                    if(empty($teacher))
                        $teacher = 0;
                    
               $sql_student = " INSERT INTO int_slots_x_student_teacher SET launchurl='$student_board_url', encryptedlaunchurl='$stu_str',
                                   braincert_class='$get_class_id',type='intervention',slot_id='$ses_id',student_id='$student_id', "
                                          . "int_teacher_id='$teacher',int_school_id='$school_id', "
                                          . "created_date='".date("y-m-d H:i:s")."',quiz_id='$quiz_id' ";
                                      

                    $insert = mysql_query($sql_student)or die(mysql_error());



                  }
                   }

                 }
                   
                include 'session_step2.php';
                    
                     }
                     
                     
                      $i++;  
                      
                      
           }
          
         
        }
			
	fclose($file);	
  

// $ses_ids[] = 5614;
//  $ses_ids[] = 5615;
//  foreach($ses_ids as $ses_id)
//  {
//      $first_ses_id = $ses_id;
//      include 'session_step2.php';
//  }
// exit;
  
     }
     
     else $msg[]='no file found!!';
          

/**
send job notification to tutors
@ whoose notify on for jobs
@ or all notification ON
**/ 
function job_notify_tutors($arr_tutors,$ses_id)
{
      $today = date("Y-m-d H:i:s"); // 
      foreach ($arr_tutors as $key => $arr) {
 
  //foreach ($arr_tutors as $tut_id) {
      $last_ses_id = $ses_id;// job_id , or session_id
      $notify_msg = 'New Job found, Session ID-'.$ses_id;
      $tutorId = $arr['id']; //$tut_id;
      $f_name  = $arr['f_name'];

      $msg_query1 = mysql_query(" INSERT INTO notifications 
        (receiver_id, type, sender_type,type_id, info, created_at) VALUES('$tutorId','jobs',
        'admin','$last_ses_id', '$notify_msg','$today')");

      $message=null;



    }




}
?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
                            <?php if(isset($isCreate)&&isset($get_newrow_room_id)){ ?>
                            <div class="alert alert-success" role="alert" style="text-align: center">Bulk session created successfully</div>
                            <?php } ?>
				<div id="single_question" class="content_wrap">
					<div class="ct_heading clear">
						<h3><i class="fa fa-plus-circle"></i>Bulk-upload Sessions</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
						<form name="form_question" id="form_question" method="post" action="" enctype="multipart/form-data">
<!--							<h4>Add new questions here:</h4>-->
                                                        <?php  
                                                       // $msg[]='fille error';  
                                                       // $msg[]='2 fille error';  $msg[]='32 fille error';
                                                        ?>
                                                        <h4><?php if(is_array(($msg))&&count($msg)>0) echo implode('<br/>', $msg);
                                                                else echo 'Please upload CSV file';  ?></h4>
                                                        
							<div class="add_question_wrap clear fullwidth">
								
                                                              
								
								<p>
									<div class="form-group">
                                <label for="school">CSV File</label>
                                <input name="file" title="file_ques" type="file">	 
                                 <!--     <input name="upload" value="Upload" type="submit"> <br>-->

                                <small class="error text-danger" style="display: none;">This field is required!</small>
                            </div>
                                                                
                                                                
                                                                </p>
								
								
								
								
								
								
								
							</div>
							<p>
								<input type="hidden" name="question_id" id="question_id" value="<?php echo $default['id']; ?>" />
								<input type="submit" name="upload" id="question_submit" class="form_button submit_button" value="Submit" />
								<input type="reset" name="question_sreset" id="question_sreset" class="form_button reset_button" value="Reset" />
                                                                                             
                                                        </p>
						</form>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<script type="text/javascript">
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
</script>

<?php include("footer.php"); ?>
<script type="text/javascript">
	$(document).ready(function(){
	    $("#trans").on("change",function(){
		   if($(this).is(":checked"))
		      $(this).val("1");
		    else
		      $(this).val("0");
		});
	});
</script>