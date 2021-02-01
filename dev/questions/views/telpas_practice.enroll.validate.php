<?php
/**
@ Active Coursee for Student 
*/

include("student_header.php");
if (!$_SESSION['student_id'])
{
  header('Location: login.php');
  exit;
}
include("student_inc.php");
//////////////////
# week practice =5, 
$allowed_telpas_course_cat=array(5);

////////////////

$str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`='".$_SESSION['student_id']."' ORDER BY ID DESC
LIMIT 1";
$moodle_data = mysql_fetch_assoc(mysql_query($str));
$TelPasUserID =$moodle_data['TelUserID'];

/* set compelete status*/
if(isset($_GET['iscom']))
{

$str = "SELECT count(IsComplete) cnt FROM `Tel_CourseComplete` WHERE TelUserID='".$TelPasUserID."' AND CourseID='".$_GET['iscom']."'";
$Telpas_students=mysql_query($str);
$row = mysql_fetch_assoc($Telpas_students);
$cnt=$row['cnt'];
if($cnt==0){
    $strIn="INSERT INTO Tel_CourseComplete SET TelUserID='".$TelPasUserID."',CourseID='".$_GET['iscom']."',IsComplete='1'";
    mysql_query($strIn);
    header('Location: telpas_practice.php');
    exit;
}
}

$TeluserName =base64_encode($moodle_data['UesrName']);
$TelPas  =   base64_encode($moodle_data['PassWord']);
/* get course details from moodle*/
include_once('MoodleWebServices/get-enroll-course.php');

if(isset($_GET['practStart']))
{
  $url= $domainname."/telpasLogin.php?username=".$TeluserName."&password=".$TelPas."&courseID=".$_GET['practStart'];
  //echo 'URL::',$url; die; 
  header("location:".$url);
}
//////////////////////////
# Check for Telpas Allowed or not 
$Telpas_allowed=_CheckTelpassPermissionStudent();

$courseIDArrEnroll=[];

include_once("MoodleWebServices/get-category-list.php");
    $catName=[];
    $category_arr=[];  // Student enrolled in Course-Category
    foreach ($responCategory as $value) {

     $catName[$value['id']] = $value['name'];
     $category_arr[$value['id']]=$value['name'];
     $category_arrID[] =$value['id'];
    }




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
if(isset($enrollCourseID)){



    $token="831c9d38c55c65b99a5dde1bc4677ae1";
    $functionname = 'enrol_manual_enrol_users';
    $enrolment = new stdClass();
    /* Enrol student*/
    foreach ($enrollCourseID as $value) {
    $enrolment->roleid = 5; //estudante(student) -> 5; moderador(teacher) -> 4; professor(editingteacher) -> 3;
    $enrolment->userid =$TelPasUserID;
     $enrolment->courseid = $value; 
    $enrolments = array( $enrolment);
    $params = array('enrolments' => $enrolments);
    $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
    $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
    $respEn = $curl->post($serverurl . $restformat, $params);



    

    }
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
            <div id="content" style="padding:0px;" > 
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
               <?php 
               if(empty($respons['errorcode'])){ 

                $i=1;
    include_once('MoodleWebServices/get-stdent-grade.php');
     
    $student_category_course=[];// 1 Category course for student. 
    ////////////////////////////////
      $i=1;
      // echo 'T=======';
      // print_r($respons);  die; 

      foreach ($respons as $row)
        { // idnumber
     // Only  [idnumber] => week_practice_1
      if($row['id']> 14){// 14
          //if(!empty($row['idnumber'])&&stripos($row['idnumber'], 'week_practice') === 0){
            $course_cat_id=$row['category'];
            // add more colume.
            $row['main_course_cat_name']=$category_arr[$course_cat_id];

            $prog= getProcessGread($row['id'],$TelPasUserID);

          if($prog < 100 && $prog > 0)
          {
            $buttonState = "RESUME";
  
            $Status="<span style='color:red'><strong>Incomplete</strong></span>";
          }
          elseif($prog ==100)
          {
             $buttonState = "RE START";
             $Status="<span style='color:#1d6d1f'><strong>Complete</strong></span>";
          } 
          else
          {
            $buttonState ="START";
            $Status="<span style='color:##337ab7'><strong>Not Started</strong></span>";
        } 
        //////////
        
        $url_quiz_start="telpas_start_quiz_iframe.php?cid=".$row['id'];

         ////Temp array data ////
         $row['Status']=$Status;
         $row['buttonState']=$buttonState;
         $row['url_quiz_start']=$url_quiz_start;
         $row['student_progress']=number_format($prog);// percent progress 
         unset($buttonState); unset($prog);
         // Make shoret name like :: $courseType=array('reading','listening','speaking','writing' );
         $row['fullname']=strtolower(trim($row['fullname']));// Title of diff course in Category.
           # Check for allowed category 
          if(!in_array($course_cat_id,$allowed_telpas_course_cat)){
            continue;
          }
        $student_category_course[$course_cat_id][$row['fullname']]=$row;  
        ///////////////////




}

$i++;} 
?>

            </div>
         </div>
         <br/>  <br/>
        <?php  

        // echo  'student_category_course: <pre>';
        // print_r($student_category_course); die; 

          $courseType=array('reading','listening','speaking','writing' );
        ///
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
          
            ?>

				   <div class="col-md-3" style="border-right:<?=$column_border?>;">
					  <p style="letter-spacing:1;" title="Course: <?php print(ucfirst($course_key)); ?>">
              <strong> <?php print(ucfirst($course_key)); ?>  </strong> </p>

             <?php  if(isset($line['student_progress'])){ //courseEnroll ?>

					  <p>Progress- <?=$line['student_progress']?>% </p>
					  <p> Status- <span style="color:#337ab7"><strong> <?=$line['Status']?> </strong></span> </p>

					  <a href="<?=$start_course_url?>" class="btn btn-primary btn-lg"> <?=$line['buttonState'] //$buttonState?> </a>

          <?php  }//if(isset($line['student_progress'])){ //courseEnroll ?>

				  </div>
          <?php  } ?>

			 </div>
	     </div>	
       </div>
       <?php 
        } // end course-cat items

       ?>
       <?php } else{?>
<br>
<a href="https://intervene.io/questions/telpas_practice.php?crta=1" class="btn btn-primary btn-lg">Please Click here to Start</a>
<?php }?>
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