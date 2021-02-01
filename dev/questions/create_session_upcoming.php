<?php



$author = 1;

$error= '';

$msg=array();

$msg_error=array();

include("header.php");

include('libraries/newrow.functions.php');

///////////////////////////////////

$datetm = date('Y-m-d H:i:s');

$today = date("Y-m-d H:i:s"); // 

if ($_SESSION['login_role'] != 0) { //not admin

    header('Location: folder.php');

    exit;

}

function send_job_email($email,$to,$message,$f_name){

 // $to = "isha@srinfosystem.com";

$subject = "Job Changed- Tutorgigs.io";

  // learn@intervene.io

// Always set content-type when sending HTML email

$headers = "MIME-Version: 1.0" . "\r\n";

$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



// More headers

$headers .= 'From: <support@tutorgigs.io>' . "\r\n";

 //$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";

 if(mail($to,$subject,$message,$headers)) return true;

 else return false;

 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';



}



///Edit///

$edit=array();

 if(isset($_GET['sid'])){

  $record=mysql_fetch_assoc(mysql_query("SELECT * FROM `int_schools_x_sessions_log` WHERE id=".$_GET['sid']));

 $edit['select_date']=date("m/d/Y",strtotime($record['ses_start_time']));

 $edit['hour_a']=date("H",strtotime($record['ses_start_time']));

 $edit['hour']=date("h",strtotime($record['ses_start_time']));

 $edit['minnute']=date("i",strtotime($record['ses_start_time']));

 $edit['hr_type']=date("a",strtotime($record['ses_start_time']));



  $dur=$record['session_duration'];



  if($record['session_duration']==0){



  $dur=50;

  }



  $schoolid =$record['school_id'];

    $districtid =$record['district_id'];

  $student_id_arr=array();

  $sql="SELECT student_id FROM `int_slots_x_student_teacher` WHERE slot_id=".$_GET['sid'];

  $results=mysql_query($sql);

  while ($row=mysql_fetch_assoc($results))

  {

  $student_id_arr[]=$row['student_id'];

  # code...



  }

  // print_r($student_id_arr);die; 

  $selected= implode('S', $student_id_arr);

 }



  $sql_tutor=" SELECT id,f_name,lname,email FROM gig_teachers WHERE notify_all='yes' OR notify_jobs='yes' ";

  $resul_tutor= mysql_query($sql_tutor);

  $arr_notify_tutors=array();// tutor list

  while ($line=mysql_fetch_assoc($resul_tutor)) {

  // $arr_notify_tutors[]=$line['id'];

  //$arr['id']=$line['id'];

  $arr_notify_tutors[]=$line;

  }



  include('braincert_api_inc.php');







/*   ///////////////////////////

function Duplicate_tutoring($data)

{  

*/

if (isset($_POST['create_submit']))

   {  

   



    $duration=$_POST['duration'];

    $client_id='Intervene123456';

    $arr=array();// data arr

    $selected_date= (!empty($_POST['select_date'])) ?$_POST['select_date']: date("Y-m-d"); 

    $session_date_ymd=date('Y-m-d H:i:s', strtotime($selected_date));  // 2012-04-20

    //echo $_POST['hour'].'::',$_POST['minnute'],'==',$_POST['hr_type'];

    $ii = 0;

    if(!empty($_POST['hour'])&&$_POST['hr_type']=="pm"&&$_POST['hour']<12)

    $hh= $_POST['hour']+12; // H 24 form

    elseif(!empty($_POST['hour']))

    $hh= $_POST['hour'];



    if (!empty($_POST['minnute']))

    $ii = $_POST['minnute'];

    //StartTime ,EndTime

    $start_time= date('Y-m-d H:i', strtotime('+' . $hh . 'hour +' . $ii . ' minutes', strtotime($session_date_ymd))); # add Hour, min.::session_start_time



    $end_time= date('Y-m-d H:i', strtotime('+'.$duration.'minutes', strtotime($start_time)));

    $activity_time= date('Y-m-d H:i', strtotime('-5 minutes', strtotime($start_time)));



    $ses_lesson_id=$_POST['lesson'];

    $arr['lesson'][]=array('les_id'=>$_POST['lesson'][$i],'start_time'=>$start_time,'end_time'=>$end_time);

    $school_id=$_POST['schoolid'];

    $district_id=$_POST['districtid']; 

    $last_quiz=mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE lesson_id='$ses_lesson_id' LIMIT 1 "));

    $quiz_id=$last_quiz['id']=(isset($last_quiz['id']))?$last_quiz['id']:0;

 //   $defaultBoard='newrow';

  //  $curr_active_board=(isset($_POST['virtual_board']))?$_POST['virtual_board']:$defaultBoard;
    
    // TITU START //
    
     if($_POST['virtual_board'] == '1')
     {
         $defaultBoard      = 'newrow';
         $curr_active_board = $defaultBoard;
         $ios_newrow        = 1;
     }
     else
     {
        $defaultBoard      ='newrow';
        $curr_active_board = (isset($_POST['virtual_board']) )? $_POST['virtual_board'] : $defaultBoard;
        $ios_newrow        = 0;
     }
    //TITU END //

    // lesson_id quiz_id grade_id class_id special_notes app_url , activity_start_time

  $sql_session= " INSERT INTO int_schools_x_sessions_log SET Tutoring_client_id='$client_id',curr_active_board='$curr_active_board',board_type='$curr_active_board',type='intervention',activity_start_time='$activity_time',ses_start_time='$start_time',"

. "ses_end_time='$end_time',

  session_duration  = '$duration',

  start_date='$session_date_ymd', "

. "school_id='$school_id',lesson_id='$ses_lesson_id',quiz_id='$quiz_id',grade_common='".$_POST['grade']."',grade_id='".$_POST['grade']."', "

. "created_date='$today',teacher_id='1999',district_id='$district_id', ios_newrow = '$ios_newrow' ";

// if testing session > it allowed only .1 session

 //echo $sql_session; //die;                        

 $q = mysql_query($sql_session)or die(mysql_error());

 /////////if Testin session ////////////////

 if($i==0)

 { 



    $parent_id= mysql_insert_id();

    $first_ses_id=$parent_id;// Add more first session 

    $ses_ids[]=array('ses_id'=>$parent_id,

    'ses_lesson_id'=>$ses_lesson_id,

    'quiz'=>$last_quiz['id']);

}

else

{

    $lastid= mysql_insert_id();

    $ses_ids[]=array('ses_id'=>$lastid,

    'ses_lesson_id'=>$ses_lesson_id,

    'quiz'=>$last_quiz['id']);

}



   

     



     

    #####################################

    // send session ID to Step2 

    $_SESSION['ses_list_ids']=$ses_ids;

    ///Save Students//

    //print_r($ses_ids);  die; 

  foreach($ses_ids as $key => $line)

  {

      ////Board URL::///////////

      $ses_id=$line['ses_id'];



      $ses_row=mysql_fetch_assoc(mysql_query(" SELECT *

      FROM `int_schools_x_sessions_log`

      WHERE id=".$ses_id));

      $board_api_key='BlOM11ettmLhEMiRqRui';

      $arr=array();

      //$ses_id=$getid;

      $arr['title']=$title='Intervention_'.$ses_id;

      $arr['date_start']=date('Y-m-d', strtotime($ses_row['ses_start_time']));// 2019-02-15  //$date_start='2019-02-15';

      $arr['start_time']=date('h:i A', strtotime($ses_row['ses_start_time']));     //$start_time='09:30 AM';

      $arr['end_time']=date('h:i A', strtotime($ses_row['ses_end_time']));  //$end_time='10:20 AM';

      $arr['currency']='usd';

      $arr['ispaid']=1; //1

      $arr['is_recurring']=0;//1 |0

      $arr['repeat']=0;

      #$arr['weekdays']='1,2,3';

      $arr['end_classes_count']=8;//3

      $arr['seat_attendees']=8;

      $arr['record']=1;

      $get_class_id=9999;   // die;  mysql_query

      // Observer URL//

      $observer_1_url=null; 

      $observer_2_url=null;

      $observer_3_url=null;

      $observer_1_url='demo.com';





     echo  $str="UPDATE int_schools_x_sessions_log SET observer_url_1='$observer_1_url',braincert_class='$get_class_id' WHERE id=".$line['ses_id'];







      $Up=mysql_query($str);









      //////end: board URL //////////

      # code...

      $ses_id=$line['ses_id'];

      $ses_lesson_id=$line['ses_lesson_id'];  // quiz

      $quiz_id=$line['quiz'];

      // Send notifications /// 

     // job_notify_tutors($arr_notify_tutors,$ses_id);// for each session

      // Send notifications /// 

    //student add

     

      for($j=0;$j<count($_POST['student']);$j++)

      {

          $student_id=$_POST['student'][$j];



          $school_id=$_POST['schoolid'];

          $district_id=$_POST['districtid']; 



          $student_row=mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE id=".$student_id));

          $teacher=$student_row['teacher_id'];

          $stu_name=$student_row['first_name'];

          $stu_namexx=(!empty($student_row['middle_name']))?' '.$student_row['middle_name']:'';

          ///////////Get Board URL /////////

          ##=========================

          $student_board_url='stoped.com';

          $student_board_url='NA';# Not created

          $student_board_class=$get_class_id=9999;

          // type student_id student_id quiz_id quiz_status Assigned
if(empty($teacher))
    $teacher = 0;
          $sql_student=" INSERT INTO int_slots_x_student_teacher SET launchurl='$student_board_url',

          encryptedlaunchurl='$stu_str',

          braincert_class='$get_class_id',type='intervention',slot_id='$ses_id',student_id='$student_id', "

          . "int_teacher_id='$teacher',int_school_id='$school_id', "

          . "created_date='$today',quiz_id='$quiz_id' ";

          // echo $sql_student; die;

          $insert = mysql_query($sql_student)or die(mysql_error());



    }



   }





           $redrectTo='create_session_step2';

           header("Location:newrow.relaunch.process.php?id=".$first_ses_id); exit;

}

  

  



function notify_assigned_tutor($arr_tutors,$ses_id)

{  



      // assigned

      $today = date("Y-m-d H:i:s"); // 

      foreach ($arr_tutors as $key => $arr) {

      # code...

      $last_ses_id=$ses_id;// job_id , or session_id

      // $notify_msg='Job time Changed, Session ID-'.$ses_id;

      $notify_msg1='There was a change to your session,ID:'.$ses_id;

      $notify_msg1.='This could mean that the lesson was updated or the time has changed. Please log in to tutorgigs to check your sessions.';

      $notify_msg='Job time Changed, Session ID-'.$ses_id;

      $tutorId=$arr['id']; //$tut_id;

      $f_name=$arr['f_name']; // mysql_query

      $msg_query1=mysql_query(" INSERT INTO notifications 

      (receiver_id, type, sender_type,type_id, info, created_at) VALUES('$tutorId','job_changed',

      'admin','$last_ses_id', '$notify_msg','$today')");

      //echo $msg_query1 ; echo'<br/>';

      # code...

      // Send Email//

      $message=null;

      $message = "Dear {$f_name},

      <br/><br/>

      Job time has changed.Please check and requesting you to cancel job if not available for the job.

      <br/><p><strong>Job ID</strong>:{$ses_id}</p>

      <br/><br/>

      Best regards,<br />

      <strong>Tutorgigs Team</strong><br/>

      www.tutorgigs.io<br>

      Tel +185534-LEARN<br>

      Email: support@tutorgigs.io

      <br /><br />

      <img alt='' src='https://tutorgigs.io/logo.png'>";

      $to=$arr['email'];

      send_job_email($email='',$to,$message,$f_name);



      }



}

//////////////////////////////

$questions_list = array();

if ($_SESSION['assess_id'] > 0 && !$_GET['assesment_id']) {

    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_SESSION['assess_id']);

    $assesment_result = mysql_fetch_assoc($qry);

    $a_id = $_SESSION['assess_id'];

}

//////////////

$a_id=$_GET['assesment_id']; // Edit Assement

 // $a_id=

$district_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "district" ');

$assessment_district = array();

while ($district = mysql_fetch_assoc($district_level_res)) {

    $assessment_district[] = $district['entity_id'];

}

$school_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "school" ');

$assessment_school = array();

while ($school = mysql_fetch_assoc($school_level_res)) {

    $assessment_school[] = $school['entity_id'];

}

$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');

?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">

   $(document).ready(function () {

        $('#district').chosen();

        $('#district').change(function () {

            district = $(this).val();

            $('#district_school').html('Loading ...');

            $('#grade_div').html('Please wait...');

            $.ajax({

                type: "POST",

                url: "ajax-sessions.php",

                data: {district: district, action: 'get_multiple_schools', school_id: '<?php print implode(',', $assessment_school); ?>'},

                success: function (response) {

                    $('#district_school').html(response);



                },

                async: false

            });

        });

        $('#district').change();

    });

///////Grade id///////////////////////////////

$(document).ready(function () {



     $('.datepicker').datepicker({

          format: 'mm/dd/yyyy',

          startDate: '-3d'

          });

        ///School : select Grade//

        $('#students_id').chosen();

        $('#grade_id').chosen();

        $('#students_id').change();

        ////////////////////////////

    });

////////Form submit/////

$(document).ready(function(){

  $("#form_passage").submit(function(){

    //event.preventDefault();

    var tot_students= $("#students_id :selected").length;

    if(tot_students<1||tot_students>8){

         alert(tot_students+'-Student, choose (1-8)students!');  return false;

    }  

  });

});

</script>

<div id="main" class="clear fullwidth">

    <div class="container">

        <div class="row">

            <div id="sidebar" class="col-md-4">

                <?php include("sidebar.php"); ?>

            </div>    <!-- /#sidebar -->

            <div id="content" class="col-md-8">

                <div id="single_question" class="content_wrap">

                    <div class="ct_heading clear">

                        <h3><i class="fa fa-plus-circle"></i>

                          Create Duplicate Session</h3>

                    </div>    <!-- /.ct_heading -->

                    <div class="ct_display clear">

                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">

                        <?php include "msg_inc_1.php";?>

                            <div class="add_question_wrap clear fullwidth">
<p>
                     <label for="lesson_name">Virtual board :</label>
                              <select name="virtual_board" id="virtual_board" class="textbox">
                                  <?php if($record['board_type'] == 'newrow') { ?>
                                    <option value="newrow"  <?php if($record['ios_newrow'] == '0') { echo 'selected'; } ?> >Newrow Virtual Board</option>
                                     <option value="1" <?php if($record['ios_newrow'] == '1') { echo 'selected';}?>>IOS - Newrow Virtual Board</option>
                                  <?php } else if($record['board_type'] == 'wiziq'){ ?>
                                     <option value="wiziq">wizIQ Virtual Board</option>
                                  <?php } ?>
                                  </select>
                                   

                                
                                  
                                </p>
                              <p>

                                <label for="lesson_name"> <strong>Grade:</strong></label>

                                   <div id="grade_div">

                                    <input type="hidden" name="schoolid" value="<?= $schoolid?>">

                                    <input type="hidden" name="districtid" value="<?= $districtid?>">

                                    <?php   



                  $str="SELECT t. * , p.grade_level_id, p.permission FROM school_permissions p

                  LEFT JOIN terms t ON p.grade_level_id = t.id WHERE p.school_id =".$schoolid;

                  $sql_grades=mysql_query($str);

                  $select = '<select  name="grade" id="grade_id" class="required textbox">';

                  while($grade = mysql_fetch_assoc($sql_grades)) 

                  {

                    if($record['grade_id']==$grade['id'])

                    {

                      $selec = 'selected';

                    }else

                    {

                      $selec = '';

                    }

                    

                    $select .= '<option value="'.$grade['id'].'" '.$selec.'>'.$grade['name'].'</option>';

                   }

                    $select .= '</select>';

                    echo $select;

                    ?></div>

                                </p>

                                <?php // } ?>

                            </div>

                             <div class="add_question_wrap clear fullwidth">

                               <p>

                              <?php

                            $sql="SELECT * FROM students WHERE grade_level_id='".$record['grade_id']."' ";

                            $sql.=" AND school_id='".$record['school_id']."' ";

                            $res= mysql_query($sql); 

                            $totStud=mysql_num_rows($res);?>

                            <label for="lesson_name">Choose (1-8)students:</label>

                              <div id="student_div"></div>

                           

                            <div id="student_data"></div>

                            

                                </p>

                            </div>

                              <!-- Select Date time -->

                            <div class="add_question_wrap clear fullwidth">

                              <p class="text-info" style="">Select upcoming date:</p>

                            <div class="row" id="add_more" >

                            <div class="col-md-6">

                             <label for="date">Date:</label>

                             <input value=""

                               name="select_date" class="datepicker required textbox" data-date-format="mm/dd/yyyy">

                            </div>

                             <div class="col-md-6">

                              <p>

                                <label for="lesson_name">Choose Lesson:</label>

                                <select name="lesson" id="lesson" class="textbox">

                                <option value="">Select Lesson</option>

                                <?php $lesson_query =mysql_query("select * from master_lessons");

                                while ($lesson = mysql_fetch_assoc($lesson_query)) { 

                                $sel='';

                                $sel=(isset($record['lesson_id'])&&$record['lesson_id']==$lesson['id'])?'selected':'';



                                ?>

                                <option value="<?=$lesson['id']; ?>" <?=$sel?> ><?=$lesson['name']?></option>



                                <?php } ?>

                                </select>

                                </p>

                            </div>

                             <!-- Times -->

                             <div class="col-md-12">

                             <label for="date">Time:</label>

                            <p>

                            <select class="col-md-4" name="hour" style="width:200px"  id="time" class="textbox">

                             <?php

                               $i = 1;

                               while ($i <= 12) {

                               $sel = (isset($edit['hour']) && $i ==$edit['hour']) ? "selected" : NULL;

                             ?>

                             <option  <?= $sel ?>

                             value="<?= $i ?>" ><?= $i ?></option>                         

                             <?php

                               $i++; }

                             ?> 

                            </select>

                            <select  class="col-md-3" name="minnute"  class="textbox">

                            <?php

                            // $edit['minnute']

                              $k = 0;

                              while ($k <= 55) {

                              $sel = ($k ==$edit['minnute']) ? "selected" : NULL;

                              $kff = ($k > 5) ? $k : '0' . $k;

                             ?>                            

                            <option  <?= $sel ?> value="<?= $k ?>"><?= $kff ?></option> 

                              <?php $k = $k + 5;

                                } ?>

                                </select> 

                                    <?php

                                    $tArr = array('am', 'pm');

                                    $Type

                                    ?>

                                <select  class="col-md-2" name="hr_type"  class="textbox">

                                    <?php

                                    foreach ($tArr as $val) {

                                        $sel = ($val ==$edit['hr_type']) ? "selected" : NULL;

                                        ?>                         

                                        <option <?= $sel ?>   value="<?= $val ?>"><?= strtoupper($val) ?></option> 

                                    <?php } ?>



                                </select>

                                <div class="col-md-3" style="margin-top: -46px">

  

                                    <label for="date">Duration OF Session:</label>

                                    <input type="number" min="15" max="60" step="5" required class="form-control" name="duration" value="<?=$dur?>">

                                    </div>

                                </p>

                             </div>

                            </div>

                                <!-- repeat_id Section -->

                                <!-- <div id="repeat_id"> -->

                                <div class="row"  id="repeat_id"></div></div><p>

                                  <button type="submit" name="create_submit"

                                  id="lesson_submit" class="form_button">Duplicate,Tutoring-ID:-<?= $_GET['sid'] ?></button>

                            </p>

                        </form>

                        <div class="clearnone">&nbsp;</div>

                    </div>    <!-- /.ct_display -->

                </div>

            </div>    <!-- /#content -->

            <div class="clearnone">&nbsp;</div>

        </div>

    </div>

</div>    <!-- /#header -->

<script type="text/javascript">

<?php if ($error != '') echo "alert('{$error}')"; ?>

</script>

<script>  

 $(document).ready(function(){  

      var i=1;  

      $('#add').click(function(){  

           i++;  

           var text_ino=$('#add_more').html();

           var html = '';

         //var datatext='<div id="row'+i+'" >'+text_ino+'</div>';

           var datatext='<div title="parent" id="row'+i+'"><div class="col-md-10"  >'+text_ino+'</div><div  class="col-md-2"><button type="button" name="remove" id="'+i+'"  class="btn btn-danger btn_remove" style="float:right;">X</button></div></div>';



          $('#repeat_id').append(datatext);

      });  



      $(document).on('click', '.btn_remove', function(){  

           var button_id = $(this).attr("id");   

           $('#row'+i+'').remove();  

           i--;

      });  

      $('#submit').click(function(){            

           $.ajax({  

                url:"name.php",  

                method:"POST",  

                data:$('#add_session').serialize(),  

                success:function(data)  

                {  

                     alert(data);  

                     $('#add_session')[0].reset();  

                }  

           });  

      });  





  /* get student list*/

        $('#grade_id').change(function () {

            var  grade = $(this).val();

            var schoolid= '<?= $schoolid?>';

            var selected='<?= $selected?>'

            $('#student_div').html('Students Loading...');

            $.ajax({

                type: "POST",

                url: "ajax-ses.php",

                data: {grade_id: grade, action: 'get_multiple_studentsEdit', schoolid: schoolid,selected:selected},

                success: function (data) {

                    $('#student_data').html(data);

                    $('#students_id').chosen();

                    $('#student_div').html('');

                     },

                async: true

            });

        });

        $('#grade_id').change();

 });  

 </script>

<style>

    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }

</style>

<?php include("footer.php"); ?>