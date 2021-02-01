<?php
/***
@ Intervention
intervene
**/
//echo 'Intervention';
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

if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

$error = '';

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
         // print_r($)
      if(!empty($_POST['hour'][$i])&&$_POST['hr_type'][$i]=="pm"&&$_POST['hour'][$i]<12)
       $hh= $_POST['hour'][$i]+12; // H 24 form
       elseif(!empty($_POST['hour'][$i]))
         $hh= $_POST['hour'][$i];
       

      if (!empty($_POST['minnute'][$i]))
         $ii = $_POST['minnute'][$i];
       //StartTime ,EndTime
        $start_time= date('Y-m-d H:i', strtotime('+' . $hh . 'hour +' . $ii . ' minutes', strtotime($session_date_ymd))); # add Hour, min.::session_start_time

    $end_time= date('Y-m-d H:i', strtotime('+55 minutes', strtotime($start_time)));
    
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

       // lesson_id quiz_id grade_id class_id special_notes app_url
       $sql_session= " INSERT INTO interv_sessions SET create_id='999',ses_start_time='$start_time',"
                            . "ses_end_time='$end_time',start_date='$session_date_ymd', "
                   . "school_id='$school_id',lesson_id='$ses_lesson_id',quiz_id='$quiz_id',grade_id='".$_POST['grade']."', "
                            . "created_date='$today',teacher_id='1999',district_id='$district_id' ";

                            if($i>0){ //child session
                                $sql_session= " INSERT INTO interv_sessions SET create_id='999',ses_start_time='$start_time',"
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
    # code...
   $ses_id=$line['ses_id'];
    $ses_lesson_id=$line['ses_lesson_id'];  // quiz
  $quiz_id=$line['quiz'];
 //  foreach ($ses_ids as $ses_id) {
    //student
    for($j=0;$j<count($_POST['student']);$j++){
      $student_id=$_POST['student'][$j];
      $school_id=$_POST['master_school'][0];
         $district_id=$_POST['district'][0]; 
      $student_row=mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE id=".$student_id));
      $teacher=$student_row['teacher_id'];
      // int_teacher_id quiz_id
      // type student_id student_id quiz_id quiz_status Assigned
      $sql_student=" INSERT INTO interv_x_student_teacher SET slot_id='$ses_id',student_id='$student_id', "
                            . "int_teacher_id='$teacher',int_school_id='$school_id', "
                            . "created_date='$today',quiz_id='$quiz_id' ";
                           // echo $sql_student; die;

            $insert = mysql_query($sql_student)or die(mysql_error());                
      //print_r($data); die;
      // interv_x_student_teacher
     # code...
    }
   }

   $error=(count($_POST['lesson'])).'-Sessions Created!';
   //print_r($arr); die;


}
///////Create Intervention Session //////





//  if(isset($_GET['assesment_id'])){
// $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
// $assesment_result = mysql_fetch_assoc($qry); 
//  }

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
                           

                            <div class="row" id="add_more" >
                            <div class="col-md-6">
                             <label for="date">Date</label>
                            <input type="date"  placeholder="dd-mm-yyyy" 
                            style=" width: 100%" name="select_date[]" class="required textbox" value=""/>
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
 });  
 </script>


<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>
