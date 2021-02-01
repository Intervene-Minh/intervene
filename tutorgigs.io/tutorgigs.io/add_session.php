<?php
/***
@ Intervention
intervene
**/
//echo 'Interventionx';
#  ## SessionType: homework,intervention  >>IMP

$tab_sessions='int_schools_x_sessions_log'; # intervenetion and homework_help
$tab_ses_stuents='int_slots_x_student_teacher'; # student list in  intervention
$tab_ses_quiz_answer='students_x_quiz'; # sesion student quiz answer


# no result for old students > all quiz are deleted. 


$district_wise_school_list="SELECT s.district_id, count( s.SchoolId ) AS totsc, d.id, d.district_name
FROM `schools` s
LEFT JOIN loc_district d ON s.district_id = d.id
WHERE s.district_id >0
GROUP BY s.district_id
ORDER BY d.district_name";


$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
$error= 'Intervention Session create';
$today = date("Y-m-d H:i:s"); // 
$msg=array();
$msg_error=array();


include("header.php");
include('braincert_api_inc.php');
////////////////////////

if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

$error = '';

$sql_tutor=" SELECT id,f_name,lname,email FROM gig_teachers WHERE notify_all='yes' OR notify_jobs='yes' ";
    $resul_tutor= mysql_query($sql_tutor);
    $arr_notify_tutors=array();// tutor list
    while ($line=mysql_fetch_assoc($resul_tutor)) {
    // $arr_notify_tutors[]=$line['id'];
      //$arr['id']=$line['id'];
      $arr_notify_tutors[]=$line;
    }

  //print_r($arr_notify_tutors);  die; 
#///////////////////////////

    function send_job_email($email,$to,$message,$f_name){
 // $to = "isha@srinfosystem.com";
$subject = "New Job found- Tutorgigs.io";
  // learn@intervene.io
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';

}


// $from_email=$to='ajay@srinfosystem.com';
// $f_name='RohitK';
// $ses_id=35466;

// $message = "Dear {$f_name},
//         <br/><br/>
//         a new Job found. 
//   <br/><p><strong>Job ID</strong>:{$ses_id}</p>

//   <br/><br/>
//   Best regards,<br />
//   <strong>Tutorgigs Team</strong><br/>
//   www.tutorgigs.io<br>
//   Tel +185534-LEARN<br>
//   Email: support@tutorgigs.io
//   <br /><br />
//   <img alt='' src='https://tutorgigs.io/logo.png'>";
  
   //echo $message; die;

  //  if(send_job_email($from_email,$to,$message,$f_name)){
  //     echo ' send';
  //  }else{
  //   echo 'not send';
  //  }

  //   die;

   //echo '===';
///////Create Intervention Session //////
if (isset($_POST['create_submit'])){


    // interv_sessions , interv_slots_create( ondate, repeat store)
   //echo '<pre>'; print_r($_POST); die;
// lesson[]
   $arr=array();// data arr
   for($i=0;$i<count($_POST['lesson']);$i++){

  // $arr['lesson']['id']=$_POST['lesson'][$i];
 $selected_date= (!empty($_POST['select_date'][$i])) ?$_POST['select_date'][$i]: date("Y-m-d"); 
    $session_date_ymd=date('Y-m-d H:i:s', strtotime($selected_date));  // 2012-04-20
   
   
    //echo $_POST['hour'][$i],'::',$_POST['minnute'][$i],$_POST['hr_type'][$i];
          $ii = 0;
           $hh= $_POST['hour'][$i];
         // print_r($)
      if(!empty($_POST['hour'][$i])&&$_POST['hr_type'][$i]=="pm"&&$_POST['hour'][$i]<12)
       $hh= $_POST['hour'][$i]+12; // H 24 form
       elseif(!empty($_POST['hour'][$i])&&$_POST['hr_type'][$i]=="am"&&$_POST['hour'][$i]==12){
         $hh= $_POST['hour'][$i]-12;//Break not allowed 12am session
       }
       // 6 am to 9 am :: allowed only.
       /*
        if ($_POST['hr_type'] == "am" && ($_POST['hour'] < 6 || $_POST['hour'] == 12)) {
        $msg .= "Session time must be in between 6 AM to 9 PM.<br/>";
        $can_create = 0;
    }
    if ($_POST['hr_type'] == "pm" && ($_POST['hour'] > 9 && $_POST['hour'] != 12)) {
        $msg .= "Session time must be in between 6 AM to 9 PM<br/>";
        $can_create = 0;
    }


       **/
       
       

      if (!empty($_POST['minnute'][$i]))
         $ii = $_POST['minnute'][$i];
       //StartTime ,EndTime
        $start_time= date('Y-m-d H:i', strtotime('+' . $hh . 'hour +' . $ii . ' minutes', strtotime($session_date_ymd))); # add Hour, min.::session_start_time

    $end_time= date('Y-m-d H:i', strtotime('+35 minutes', strtotime($start_time)));
    $activity_time= date('Y-m-d H:i', strtotime('-5 minutes', strtotime($start_time)));
    
    // $arr['lesson']['end_time']=$end_time;

      $ses_lesson_id=$_POST['lesson'][$i];//for each session.

      $arr['lesson'][]=array('les_id'=>$_POST['lesson'][$i],
                                         'start_time'=>$start_time,
                                         'end_time'=>$end_time);
        // $parentid=0;
         if(isset($parent_id)&&$parent_id>0){
          $parentid=$parent_id;  }else{ $parentid=0; }


        $school_id=$_POST['master_school'][0];
         $district_id=$_POST['district'][0]; 
          $last_quiz=mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE lesson_id='$ses_lesson_id' LIMIT 1 "));
        $quiz_id=$last_quiz['id']=(isset($last_quiz['id']))?$last_quiz['id']:0;

       // lesson_id quiz_id grade_id class_id special_notes app_url , activity_start_time
       $sql_session= " INSERT INTO int_schools_x_sessions_log SET type='intervention',activity_start_time='$activity_time',ses_start_time='$start_time',"
                            . "ses_end_time='$end_time',start_date='$session_date_ymd', "
                   . "school_id='$school_id',lesson_id='$ses_lesson_id',quiz_id='$quiz_id',grade_id='".$_POST['grade']."', "
                            . "created_date='$today',teacher_id='1999',district_id='$district_id' ";

                           // echo '<pre>',$sql_session; die; 



                            if($i>0){ //child session
                                $sql_session= " INSERT INTO int_schools_x_sessions_log SET type='intervention',activity_start_time='$activity_time',ses_start_time='$start_time',"
                            . "ses_end_time='$end_time',start_date='$session_date_ymd', "
                  . "school_id='$school_id',lesson_id='$ses_lesson_id',quiz_id='$quiz_id',grade_id='".$_POST['grade']."', "
                            . "created_date='$today',parent_id='$parentid',district_id='$district_id' ";

                            }


                         // echo $sql; die;
                            // Last lesson QUIZ
       


                    $insert = mysql_query($sql_session)or die(mysql_error());
                    if($i==0){
                     $parent_id= mysql_insert_id();
            $ses_ids[]=array('ses_id'=>$parent_id,'ses_lesson_id'=>$ses_lesson_id,'quiz'=>$last_quiz['id']);
                    }else{  $lastid= mysql_insert_id();
                      $ses_ids[]=array('ses_id'=>$lastid,'ses_lesson_id'=>$ses_lesson_id,'quiz'=>$last_quiz['id']);
                    }


      //echo 'Hour:' ,$hh, '::==',$ii; die;
     // echo  $start_time,'Ended::',$end_time,'<br/>'; // die;

   }
     
   ///Save Students//
 // print_r($ses_ids);  die; 
  foreach ($ses_ids as $key => $line) {
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
 // print_r($arr); die;
 $get_data=get_braincert_class($arr,$ses_id=$getid,$type='Intervention');
   // print_r($get_data);  die;  // Update Class id of braincert. 

     $get_class_id=$get_data->class_id;   // die;  mysql_query


   // Observer URL//
$observer_1_url=null;  $observer_2_url=null;$observer_3_url=null;

 $obs_url_1= get_observer_url($get_class_id,$ob_id=19901,$stu_name='');
   $url_1_obj=json_decode($obs_url_1);
   if($url_1_obj->status=='ok'){
    $observer_1_url=$url_1_obj->launchurl;
   }

   // 2 URL
  ////2 url obserStudents
 $obs_url_2= get_observerStu_url($get_class_id,$ob_id=19902,$stu_name='');
   $obj2=json_decode($obs_url_2);
   if($obj2->status=='ok'){
    $observer_2_url=$obj2->launchurl;
   }
   // 3 URL///

   $obs_url_3= get_observerStu_url($get_class_id,$ob_id=19903,$stu_name='');
   $obj3=json_decode($obs_url_3);
   if($obj3->status=='ok'){
    $observer_3_url=$obj3->launchurl;
   }



 //$obs_url_2= get_observer_url($get_class_id,$ob_id=19902,$stu_name='');
 // $obs_url_3= get_observer_url($get_class_id,$ob_id=19902,$stu_name='');
  // echo $observer_1_url; die; 
    //echo '<pre>'; print_r($obs_url_1);  die;





     $Up=mysql_query(" UPDATE int_schools_x_sessions_log SET observer_url_3='$observer_3_url',observer_url_2='$observer_2_url',observer_url_1='$observer_1_url',braincert_class='$get_class_id' WHERE id=".$line['ses_id']);
      //echo '<pre>',$Up; die; 


    //////end: board URL //////////
    # code...
   $ses_id=$line['ses_id'];
    $ses_lesson_id=$line['ses_lesson_id'];  // quiz
  $quiz_id=$line['quiz'];
  // Send notifications /// 
  job_notify_tutors($arr_notify_tutors,$ses_id);// for each session
// Send notifications /// 
    //student add
    for($j=0;$j<count($_POST['student']);$j++){
      $student_id=$_POST['student'][$j];
      $school_id=$_POST['master_school'][0];
         $district_id=$_POST['district'][0]; 
      $student_row=mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE id=".$student_id));
      $teacher=$student_row['teacher_id'];
      $stu_name=$student_row['first_name'];

      $stu_namexx=(!empty($student_row['middle_name']))?' '.$student_row['middle_name']:'';

           ///////////Get Board URL /////////
     // $stu_str=get_student_board_url($clss_id=$get_class_id,$student_id);//str
      $stu_str=get_student_board_url($clss_id=$get_class_id,$student_id,$stu_name);//str
      $stu_obj=json_decode($stu_str);
     // print_r($stu_obj); die; 

      if($stu_obj->status=='ok'){ 
         $student_board_url=$stu_obj->launchurl;  // die; 
      }else{ 
        $student_board_url='NA';# Not created
      }
      // braincert_class
      
      $student_board_class=$get_class_id;
      //echo '<pre>',$stu_url_data; die;  
      // launchurl  encryptedlaunchurl

      /////////////////////
      // int_teacher_id quiz_id
      // type student_id student_id quiz_id quiz_status Assigned
      $sql_student=" INSERT INTO int_slots_x_student_teacher SET launchurl='$student_board_url',
      encryptedlaunchurl='$stu_str',
      braincert_class='$get_class_id',type='intervention',slot_id='$ses_id',student_id='$student_id', "
                            . "int_teacher_id='$teacher',int_school_id='$school_id', "
                            . "created_date='$today',quiz_id='$quiz_id' ";
                           // echo $sql_student; die;

            $insert = mysql_query($sql_student)or die(mysql_error());                
      //print_r($data); die;
      // interv_x_student_teacher
     # code...
    }
   }
   /////Send notification ////
         //Stuend : create auto board URL 
       //foreach ($ses_ids as $key => $line) {
     
     
          //} 

    // add- notification 
   // Obserrver URL ///
   
     

   /////Send notification ////
   $error=(count($_POST['lesson'])).'-Sessions Created!=='.$get_class_id; //die ;
   //print_r($arr); die;


}
///////Create Intervention Session //////






/**
send job notification to tutors
@ whoose notify on for jobs
@ or all notification ON
**/ 
function job_notify_tutors($arr_tutors,$ses_id){
  $today = date("Y-m-d H:i:s"); // 
  foreach ($arr_tutors as $key => $arr) {
    # code...
   // print_r($arr); die;
  //foreach ($arr_tutors as $tut_id) {
        $last_ses_id=$ses_id;// job_id , or session_id
      $notify_msg='New Job found, Session ID-'.$ses_id;
      $tutorId=$arr['id']; //$tut_id;
      $f_name=$arr['f_name'];

      $msg_query1= mysql_query(" INSERT INTO notifications 
        (receiver_id, type, sender_type,type_id, info, created_at) VALUES('$tutorId','jobs',
        'admin','$last_ses_id', '$notify_msg','$today')");

      # code...
      // Send Email//
      $message=null;
      $message = "Dear {$f_name},
        <br/><br/>
        a new Job found. 
  <br/><p><strong>Job ID</strong>:{$ses_id}</p>

  <br/><br/>
  Best regards,<br />
  <strong>Tutorgigs Team</strong><br/>
  www.tutorgigs.io<br>
  Tel +185534-LEARN<br>
  Email: support@tutorgigs.io
  <br /><br />
  <img alt='' src='https://tutorgigs.io/logo.png'>";
  $to=$arr['f_name'];
  send_job_email($email='',$to,$message,$f_name);
  //send_job_email($email,$to,$message,$f_name){

      //
    }
    /////Send job notification to Tutor.///



}






/////////Update Assesment///////////
 




//////////////////////////////
$questions_list = array();
if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['id']);
    $assesment_result = mysql_fetch_assoc($qry);
    if ($_GET['cat'] > 0) {
        unset($assesment_result['grade_id']);
    }
    $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
            . 'LEFT JOIN assessments_x_questions axq ON axq.qn_id = qn.id WHERE '
            . 'axq.assesment_id = \'' . $_GET['id'] . '\' ');

    while ($question = mysql_fetch_assoc($qn_query)) {
        $questions_list[] = $question['id'];
    }
}

if ($_SESSION['assess_id'] > 0 && !$_GET['assesment_id']) {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_SESSION['assess_id']);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];
}


////////////////
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

/// 
//$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
 //district having sql

$district_qry = mysql_query($district_wise_school_list);
//echo 'Total District';
//echo mysql_num_rows($district_qry); 

?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>


<script type="text/javascript">
// 
   $(document).ready(function () {

$(document).on('click', '.datepicker', function(){
    //this.id='';
      console.log('Allow date'+this.id);

       $('.datepicker').datepicker({
                            format: 'mm/dd/yyyy',
                                    startDate: '-3d'
                                    });

   // $(this).removeClass('datepicker'); 
});


  // date
  // $('.datepicker').datepicker({
  //                           format: 'mm/dd/yyyy',
  //                                   startDate: '-3d'
  //                                   });

    /////////////////////////
        $('#district').chosen();

        $('#district').change(function () {
            district = $(this).val();

            $('#district_school').html('Loading ...');
            //$('#grade_div').html('Please wait...');
            $.ajax({
                type: "POST",
                url: "ajax-sessions.php",
                data: {district: district, action: 'get_multiple_schools', school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                     $.getScript("intervention.js");
                    //Check for school/////


                },
                async: true
            });
        });
        $('#district').change();
        ////////////grade_id////////////////
        // $('#grade_id').chosen();
      




    }); //end 
    ////////////////////////







////////Form submit/////
$(document).ready(function(){
  $("#form_passage").submit(function(){
    //event.preventDefault();
    var tot_students= $("#students_id :selected").length;
    if(tot_students<1||tot_students>4){

         alert(tot_students+'-Student, choose (1-4)students!');  return false;
    }else{  // submit Please wailt
      // $('#lesson_submit').val('Wait');

     // alert('Please wailt!, Submiting');
      $('#lesson_submit').val('wait');
      $('#lesson_submit').html('Please wait..');
      $("input[type='submit']", this)
      .val("Please Wait...")
      .attr('disabled', 'disabled');
      
     return true;
    
    }
    //return true;

       // alert(count+'= Students');

   // alert("Submitted"); 
   
  });
});

/////Student Select////


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
                          Create Session</h3>
                    </div>    <!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Create Session:">Create Session:</h4>
                          
                             <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Choose District:</label><br />
                      <select name="district[]" id="district"  >
                <?php while ($district = mysql_fetch_assoc($district_qry)) { ?>
                            <option <?php if (in_array($district['id'], $assessment_district)) { ?> selected="selected" <?php } ?> value="<?php print $district['id']; ?>"><?php print $district['district_name']; ?></option>
                            <?php } ?>
                                    </select>

                                </p>
                            </div>

                            
                            <div class="add_question_wrap clear fullwidth">
                                <div id="XXdistrict_schoolsXX">

                                    <label for="lesson_name">School:</label>
                                    <div id="district_school">
                            
                                        Select District to choose schools.
                                    </div>

                                </div>
                            </div>





                            <div class="add_question_wrap clear fullwidth" >
                             <?php
                                   
                                    $school_id=28;//  From dropdown

          //  $sql_grades="SELECT * FROM `school_permissions` WHERE `school_id` =".$school_id;
            $sql_grades="SELECT t. * , p.grade_level_id, p.permission FROM school_permissions p
LEFT JOIN terms t ON p.grade_level_id = t.id WHERE p.school_id =".$school_id;
                                 ?>


                                <p>
                     <label for="lesson_name">Choose Grade:</label>
                                   <div id="grade_div">
                                        Select Grade options.
                                    </div>

                                </p>
                                <?php // } ?>
                            </div>

                              <div class="add_question_wrap clear fullwidth">
                                <div id="">

                                    <label for="lesson_name">Choose (1-4)students:</label>
                                    <div id="student_div">
                                        Select Students options.
                                    </div>

                                </div>
                            </div>


                              <!-- Select Date time -->

                            <div class="add_question_wrap clear fullwidth">
                            
                          
                           


                            <!-- <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="float:right;">X</button> -->
                            <!-- datepicker required textbox -->
                           

                            <div class="row" id="add_more" >
                            <div class="col-md-6">
                             <label for="date">Date</label>

                          
                            <input 
                               name="select_date[]" class="datepicker textbox cdate" 
                                data-date-format="mm/dd/yyyy">


                            </div>

                             <div class="col-md-6">
                              <p>
                                    <label for="lesson_name">Choose Lesson:</label>
                                    <select name="lesson[]" id="lesson" class="textbox">
                                    <option value="">Select Lesson</option>
                                        <?php $lesson_query =mysql_query("select * from master_lessons");
                                        while ($lesson = mysql_fetch_assoc($lesson_query)) { ?>
                                   <option value="<?php echo $lesson['id']; ?>"><?php echo $lesson['name']; ?></option>

                                      <?php } ?>
                                    </select>
                                </p>
                            </div>
                             <!-- Times -->
                             <div class="col-md-12">
                             <label for="date">Start Time:</label>

                            <p>
                            <select class="col-md-4" name="hour[]" style="width:200px"  id="time" class="textbox">
                             <?php
                               $i = 1;
                               while ($i <= 12) {
                               $sel = (isset($_POST['hour']) && $i == $_POST['hour']) ? "selected" : NULL;
                             ?>
                             <option  <?= $sel ?>
                             value="<?= $i ?>" ><?= $i ?></option>                         
                             <?php
                               $i++; }
                             ?> 
                            </select>

                            <select  class="col-md-3" name="minnute[]"  class="textbox">
                            <?php
                              $k = 0;
                              while ($k <= 55) {
                              $sel = ($k == $_POST['minnute']) ? "selected" : NULL;
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
                                <select  class="col-md-2" name="hr_type[]"  class="textbox">
                                    <?php
                                    foreach ($tArr as $val) {
                                        $sel = ($val == $_POST['hr_type']) ? "selected" : NULL;
                                        ?>                         
                                        <option <?= $sel ?>   value="<?= $val ?>"><?= strtoupper($val) ?></option> 
                                    <?php } ?>

                                </select>
                                </p>
                             </div>




                            </div>

                           

                               



                                <!-- repeat_id Section -->


                                <!-- <div id="repeat_id"> -->
                                <div class="row"  id="repeat_id" >
                               
                                </div>
                             
                              



                             </div>



                             <p class="align-right" style=" text-align: right;">
                              <a class="btn btn-primary btn-sm" name="add" id="add" ><i class="fa fa-plus-circle" >+Add More</i></a>
                              </p>



                              













                            <!--  dff -->

                            <p>

      

           <button type="submit" name="create_submit"
            id="lesson_submit" class="form_button" value="" >Create Session</button>
                                

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




///////////////////
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){ 
       // alert('');

       


        ////////////////

           i++;  
           var text_ino=$('#add_more').html();
           var html = '';
         //var datatext='<div id="row'+i+'" >'+text_ino+'</div>';
           var datatext='<div title="parent" id="row'+i+'"><div class="col-md-10"  >'+text_ino+'</div><div  class="col-md-2"><button type="button" name="remove" id="'+i+'"  class="btn btn-danger btn_remove" style="float:right;">X</button></div></div>';

          $('#repeat_id').append(datatext);
          /////////////////////////////
           // $('.datepicker').datepicker({
           //                  format: 'mm/dd/yyyy',
           //                          startDate: '-3d'
           //                          });


          ///////////////////


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
 });  
 </script>


<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>
