<?php
/**
@ Active Coursee for Student.
@ All available courses
@ Only active Course-Cateory
  [visible] => 0
  [visibleold] => 0
@ writing|seaking course-completed mark
@ test for wrting|speaking-Incompleted-courses
@ writing&speaking, in_progress,completed status:DONE
*/
#print('==Page in stesing!!');

include("student_header.php");
if (!$_SESSION['student_id'])
{
  header('Location: login.php');
  exit;
}
include("student_inc.php");

$str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`='".$_SESSION['student_id']."' ORDER BY ID DESC
LIMIT 1";


$moodle_data = mysql_fetch_assoc(mysql_query($str));

////
$TelPasUserID =$moodle_data['TelUserID'];

/**
*@Check:courseMarkDoneByStudent
*/
$arr_course_status=[ 'in_progress'=>'In progress', 
                      'completed'=>'Completed',
                      'not_started'=>'Not started',
                  ];



 $intervene_uid=$_SESSION['student_id'];
 $course_id='26';
 $in_progress=28; 


 
   /*
  @ check writing done, in process, or completed by teacher
  Array
(
    [attempt_status] => in_progress|not_started |
    [number_question_attempted] => 2
    [course_id] => 26
)
 */


 function cdb_check_writing_attempt($course_id='26',$telpas_uid='')
 { $result=[];
  global $intervene_uid;
  global $TelPasUserID;
 

$sql_completed_course="SELECT * FROM  Tel_CourseComplete WHERE  TelUserID= '$TelPasUserID' AND CourseID= '$course_id' ";
    //echo $sql_completed_course; 

$total_record=mysql_num_rows(mysql_query($sql_completed_course));
 if($total_record>=1){
  $result['attempt_status']='completed';
  $result['number_question_attempted']='all';
  $result['course_id']=$course_id;

 }else
 { // not sure: 
    
   $sql_writing="SELECT * FROM telpas_student_writing_grades 
   WHERE telpas_student_id= '$TelPasUserID' AND course_id= '$course_id' ";



   $number_question_attempted=mysql_num_rows(mysql_query($sql_writing));
   $result['attempt_status']=$number_question_attempted>0?'in_progress':'not_started';
   $result['number_question_attempted']=$number_question_attempted;
   $result['course_id']=$course_id;


 }





  return $result;
 }

 /**
@Speaking attempt
@ Table::telpas_student_speaking_grades
@Return: Array
(
    [type] => speaking_attempt
    [attempt_status] => in_progress
    [number_question_attempted] => 1
    [course_id] => 28
)
 */

function cdb_check_speaking_attempt($course_id='20')
 {
  $result=[];
  $result['type']='speaking_attempt';

  global $intervene_uid;
  global $TelPasUserID;
 

$sql_completed_course="SELECT * FROM  Tel_CourseComplete WHERE  TelUserID= '$TelPasUserID' AND CourseID= '$course_id' ";
    //echo $sql_completed_course; 

$total_record=mysql_num_rows(mysql_query($sql_completed_course));
 if($total_record>=1){
  $result['attempt_status']='completed';
  $result['number_question_attempted']='all';
  $result['course_id']=$course_id;

 }else
 { // not sure: 
    
   $sql_writing="SELECT * FROM telpas_student_speaking_grades 
   WHERE telpas_student_id= '$TelPasUserID' AND course_id= '$course_id' ";



   $number_question_attempted=mysql_num_rows(mysql_query($sql_writing));
   $result['attempt_status']=$number_question_attempted>0?'in_progress':'not_started';
   $result['number_question_attempted']=$number_question_attempted;
   $result['course_id']=$course_id;


 }





  return $result;
 }


/*get score function*/
 function _student_get_score($table,$studet_id,$course_id)
 {

    $sql_spearking_question="SELECT * FROM  $table WHERE course_id='".$course_id."' AND intervene_student_id=".$studet_id;
    $result=mysql_query($sql_spearking_question);
    $grade_total=0;
    $grade_scored_toatal=0;
    while ($row=mysql_fetch_assoc($result)) {
      # code...
      $grade_total=$grade_total+intval($row['max_grade_number']);
      $grade_scored_toatal=$grade_scored_toatal+intval($row['scored_grade_number']);
      // print_r($row); die; 
    }

    return $scored=(100*$grade_scored_toatal)/($grade_total);
       
}




////////////
$courseType=array('reading','listening','speaking','writing' );
// echo '==' ,;
  $page_name="Telpas Courses";


////////////////

// for new student 
 if(isset($TelPasUserID)&&!empty($TelPasUserID)){
  
  $allowAutoEnrolled='yes';
 }else{
   // echo '==NEW ',$TelPasUserID;
   $allowAutoEnrolled='no';
 }
  

$TeluserName =base64_encode($moodle_data['UesrName']);
$TelPas  =   base64_encode($moodle_data['PassWord']);
/* get course details from moodle*/
include_once('MoodleWebServices/get-enroll-course.php');

if(isset($_GET['practStart']))
{
  $url= $domainname."/telpasLogin.php?username=".$TeluserName."&password=".$TelPas."&courseID=".$_GET['practStart'];
  //echo 'URL::',$url; 
  header("location:".$url);
}
//////////////////////////
# Check for Telpas Allowed or not 
$Telpas_allowed=_CheckTelpassPermissionStudent();

$courseIDArrEnroll=[];

include_once("MoodleWebServices/get-category-list.php");
    $catName=[];
    $category_arr=[];  // Student enrolled in Course-Category
    // echo '=responCategory:<pre>';
    // print_r($responCategory);
    
    foreach ($responCategory as $value)
    {
      
       // Validate only active category from moodle.
       if($value['visible']==1)
      {
     $catName[$value['id']] = $value['name'];
     $category_arr[$value['id']]=$value['name'];
     $category_arrID[] =$value['id'];

      }
    

    }


    
    //echo '<pre>' ; print_r($category_arr); 

    ///////////////




foreach ($category_arrID as $catID) {


/* get course ID */
$token = '14432b87ba8ea3a4896dc7707d10e71d';
$domainname = 'https://ellpractice.com/intervene/moodle';
$functionname = 'core_course_get_courses_by_field';
require_once('cur-moodlel.php');
$curl = new curl;
$restformat = 'json'; 

$params = array('field'=>'category','value'=>$catID);

//print_r($params);
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$responsCourse = json_decode($resp,true);

foreach ($responsCourse['courses'] as  $row) 
{

    $course_arrID[]=$row['id'];

}

}

/* get  enroll course list*/
$str = "SELECT course_id FROM `Telpas_course_users` WHERE telpas_uuid='".$TelPasUserID."'";
$Telpas_enroll_course=mysql_query($str);
while($row = mysql_fetch_assoc($Telpas_enroll_course))
{

  $courseIDArrEnroll[]=$row['course_id'];

}
/* enrol student in new course*/
$enrollCourseID = array_diff($course_arrID,$courseIDArrEnroll);
//print_r($enrollCourseID);
if(isset($enrollCourseID)&&$allowAutoEnrolled=='yes'){



    $token="831c9d38c55c65b99a5dde1bc4677ae1";
    $functionname = 'enrol_manual_enrol_users';
    $enrolment = new stdClass();
    /* Enrol student*/
    foreach ($enrollCourseID as $course_id) {
    $enrolment->roleid = 5; //estudante(student) -> 5; moderador(teacher) -> 4; professor(editingteacher) -> 3;
    $enrolment->userid =$TelPasUserID;
     $enrolment->courseid = $course_id; 
    $enrolments = array( $enrolment);
    $params = array('enrolments' => $enrolments);
    $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
    $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
    $respEn = $curl->post($serverurl . $restformat, $params);

$str="INSERT INTO `Telpas_course_users` SET `telpas_uuid`='".$TelPasUserID."',`course_id`='".$course_id."',`intervene_uuid`='".$_SESSION['student_id']."'";
    mysql_query($str);

    

    }
 }
/*Student:category-course-list::$courseList*/
 if(empty($respons['errorcode']))
 { 
   $i=1;
    include_once('MoodleWebServices/get-stdent-grade.php');
     
    $student_category_course=[];// 1 Category course for student. 
    ////////////////////////////////
      $i=1;
      //  'student_category_course::'; 

      foreach ($respons as $row)
      { 
        // Only  [idnumber] => week_practice_1
            $course_cat_id=$row['category'];
            // add more colume.
        $row['main_course_cat_name']=$category_arr[$course_cat_id];


          // get percentage for writing|Speaking//
          $course_id=$row['id'];// [id] => 21
     
        
         if(strtolower(trim($row['fullname']))=='speaking'){
           
           $prog= _student_get_score('telpas_student_speaking_grades',$_SESSION['student_id'],$course_id);
          

          //await_score($TelPasUserID,$course_id);
            $progTest=$prog;
         }
         elseif(strtolower(trim($row['fullname']))=='writing'){
            //getScore
          $prog= _student_get_score('telpas_student_writing_grades',$_SESSION['student_id'],$course_id);
           $progTest=$prog;

         }else
         { //defaultMoodle Score.
        $prog = getProcessGread($row['id'],$TelPasUserID);     
         }
         //Button state

            $buttonState ="START";
            $Status="<span style='color:##337ab7'><strong>Not Started</strong></span>";

            $courseKey=strtolower(trim($row['fullname']));
            $url_quiz_start="telpas_start_quiz_iframe.php?cid=".$row['id'];
         ////

          

          if($courseKey=='reading'||$courseKey=='listening')
          {   $custom_status_key='re_start_allowed';
            if($prog ==100)
              { 
               $buttonState = "RE START";
               $Status="<span style='color:#1d6d1f;'><strong>Completed</strong></span>";

              }elseif($prog < 100 && $prog > 0)
              {
               $buttonState = "RESUME";
               $Status="<span style='color:red;'><strong>Incomplete</strong></span>";
              }
             
          }elseif($prog < 100 && $prog > 0)
          {  //speaking&writing: No more attempt

             $buttonState = "Completed";
             $Status="<span style='color:#1d6d1f;'><strong>Completed</strong></span>";
             $custom_status_key='completed';


          }elseif(strtolower(trim($row['fullname']))=='writing'||
            strtolower(trim($row['fullname']))=='speaking')
          {  
              if($courseKey=='writing')
              $course_log=cdb_check_writing_attempt($course_id);

              if($courseKey=='speaking')
              $course_log=cdb_check_speaking_attempt($course_id);


            $Status=$status_key=$course_log['attempt_status'];
            $custom_status_key=$course_log['attempt_status'];
            $status_msg =($arr_course_status[$course_log['attempt_status']]);

           # $Status= $butn_text =($arr_course_status[$status_key]);
             
            

            $buttonState=($course_log['attempt_status']=='in_progress')?'RESUME':'START';
        $buttonState=($course_log['attempt_status']=='completed')?'Completed':$buttonState;
  $buttonColor=($course_log['attempt_status']=='completed')?'color:#1d6d1f':'color:#337ab7';

            //|START| Dyamice
            $Status="<span style='".$buttonColor."'><strong>".$status_msg."</strong></span>";
            $url_quiz_start=($course_log['attempt_status']=='completed')?'#':$url_quiz_start;
            //

          }


        //////////
        
        

         ////Temp array data ////
         $row['Status']=$Status;
         $row['custom_status_key']=$custom_status_key;
         $row['buttonState']=$buttonState;
         $row['url_quiz_start']=$url_quiz_start;
         

         $row['student_progress']=number_format($prog).'%';// percent progress 
         unset($buttonState); unset($prog);
         // Make shoret name like :: $courseType=array('reading','listening','speaking','writing' );
         $row['fullname']=strtolower(trim($row['fullname']));
         // Title of diff course in Category.
        $student_category_course[$course_cat_id][$row['fullname']]=$row;  
 
$i++; 
 } 
// student_category_course
 // echo 'student_category_course:<pre>';
 // print_r($student_category_course);
 // die;
  //
 }


?>



<div id="home main" class="clear fullwidth tab-pane fade in active">
   <div class="container">
      <div id="items_id" style="display:none;">Checking Sesion</div>
        <!-- row -->
      <div class="row">
         <div class="align-center col-md-12" style="margin-top:10px; margin-bottom:20px;">
            <div style=" width:auto;" title="">
               <?php include("nav_students_2.tpl.php"); ?>
            </div>
         </div>
         <!--  NExt col -->
         <div class="align-center col-md-12">
            <!-- StudentWelcome -->
            <div 
             id="content" style="padding:0px;" > 
               <div class="content_wrap">
                  <div class="ct_heading clear" style="text-align: left;">
                     <h3>Welcome</h3>
                  </div>
                  <!-- /.ct_heading -->
                  <div class="ct_display clear">
                     <div class="item-listing clear">
                        <h3 class="notfound align-center">
                           <a href="#" Sut="<?php echo $_SESSION['student_id']?>">Welcome <?=(!empty($_SESSION['student_name']))?$_SESSION['student_name']:''?></a>
                        </h3>
                     </div>
                  </div>
               </div>


              

            </div>
         </div>
         <br/>  <br/>

          <?php 
               if(empty($respons['errorcode'])){ 
                // Show CourseList

           ?>
        <?php  

     
        foreach ($student_category_course as $category_id=>$course_arr) { 
         
          ?>
       <!-- Category-itms -->
       <div class="col-md-12">
		 <div class="w-1000" style="margin: 20px 0px;" >
			 <div class="row text-center">
				  <h3 title="Week Practice:" class="text-center" style="margin-bottom: 30px;"><strong>
            <?=($category_arr[$category_id])?$category_arr[$category_id]:'Course Category';  //$category_id?> </strong></h3>
            <?php
            // Show row
            $courseType=array('reading','listening','speaking','writing' );
            foreach ($courseType as $course_key) {

            $line=$course_arr[$course_key];// $row1=$course_arr['reading'];
            $start_course_url='#NotEnroll';

            if($line['id']>0)
            $start_course_url='https://intervene.io/questions/telpas_practice.php?practStart='.$line['id'];

            $column_border='2px solid #ccc';
            if($course_key=='writing')
            $column_border='1px solid #fff';
            // Start button class
           $start_btn_class=($line['custom_status_key']=='completed')?'disabled':'active';

          ?>

				   <div class="col-md-3" style="border-right:<?=$column_border?>;">
					  <p style="letter-spacing:1;" title="Course: <?php print(ucfirst($course_key)); ?>">
              <strong> <?php print(ucfirst($course_key)); ?>  </strong> </p>

             <?php  if(isset($line['student_progress'])){ //courseEnroll ?>

					  <p>Progress- <?=$line['student_progress']?> </p>
					  <p> Status- <span style="color:#337ab7"><strong> <?=$line['Status']?> </strong></span> </p>
            <?php if($line['buttonState']=='Done'){$start_course_url='#';}?>
		<a href="<?=$start_course_url?>"
     class="btn btn-primary btn-lg <?=$start_btn_class?>"> <?=$line['buttonState'];?></a>

          <?php  } ?>

				  </div>
          <?php  } ?>

			 </div>
	     </div>	
       </div>
       <?php 
        } // end course-cat items

       ?>
       <?php  }else{ ?>
<br>
<a href="https://intervene.io/questions/telpas_practice.php?crta=1" class="btn btn-primary btn-lg">Please Click here to Start</a>
<?php } ?>
         <!-- C0ntent -->
      </div>
   </div>
</div>
<style>
   .w-1000{
	   width:100%;
	   display:inline-block;
	   margin-bottom:40px;
	   border:2px solid #ddd;
	   padding-bottom:30px;
   }
</style>
<?php
/* use CURL Request for Signin Ans Signup Process in moodle */
if(isset($_GET['crta']))
{

    $courseID=$_GET['course'];
    require_once('MoodleWebServices/moodle-create-account.php');
}
?> 