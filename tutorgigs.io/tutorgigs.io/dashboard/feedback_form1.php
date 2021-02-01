<?php
/**
 * @feedback_form_1
 * @ some question from db 
 * @ some on page .
 @ Student attendance and arrival time capture by tutor
 @ in session.
 @ is_attendance ==0 for old session
students_attendance  ==Array of student
 * **/
///////////////////////////
$error = '';
$author = 1;

//echo 'Student list for attendance'; 

$datetm = date('Y-m-d H:i:s');

include("header.php");
$created = date('Y-m-d H:i:s');
$page_name="Session Completion Form";
//////////////////////////
$user_id = $_SESSION['login_id'];

$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
$error = '';





function checkmydate($date) {
    $tempDate = explode('-', $date);
    // checkdate(month, day, year)
    return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
}



//unset($_SESSION['ses_attendance_arr']);
  //print_r($_SESSION);

////////////////////##############################


   $next_url="feedback_form_1.php"; // number of attendes
   
  
  /////No sessuon selected///////
  if(!isset($_SESSION['feedback_ses_id'])){
     exit("Page not found. !"); // No session id selected 
     
  }
/////No sessuon selected/////// 


   
   
   
   
  
 
 
 if (isset($_POST['add_class'])) {
  // echo '<pre>';
  //  print_r($_POST['student_present']); die; 


     $student_arr=array();
   foreach ($_POST['students'] as $key => $student_id) {
     # code...
    /// absent
    $arrival_info=$_POST['arrival'][$student_id];

    if($_POST['student_present'][$student_id]=='absent'){
      $arrival_info=NULL;
    }

    $student_arr[$student_id]=array('attendance'=>$_POST['student_present'][$student_id],
        'arrival'=>$arrival_info,
        'student_name'=>$_POST['student_names'][$student_id],
        );
   }

   // echo '<pre>';

   // echo '<pre>';
   //  print_r($student_arr); 

   //die; 
  // student_arr


     // form_1 : data  
  $_SESSION['ses_attendance_arr']=$student_arr;
  header("location:$next_url"); exit;
   

}
////////if form 1 posted/////////////////


if(isset($_SESSION['form_1'])){
    @extract($_SESSION['form_1']);
   // echo $dat_of_session;
} 

// New session come
if(isset($_SESSION['feedback_ses_id'])){ //newSession

$ses_det= mysql_fetch_assoc(mysql_query("SELECT * FROM int_schools_x_sessions_log WHERE id=".$_SESSION['feedback_ses_id']));
//$session_id;$dat_of_session; $start_time;
$session_id=$_SESSION['feedback_ses_id'];
// feedback_id Has give////////
// session exits///
///Back attendance w/o post. :: ses_attendance_arr



$edit_rows=array();
 if(isset($_SESSION['ses_attendance_arr'])){

$edit_rows=$_SESSION['ses_attendance_arr'];

 }elseif($ses_det['feedback_id']>0){ //Edit time.

  $feedback_row= mysql_fetch_assoc(mysql_query("SELECT id,is_attendance,students_attendance FROM int_session_complete WHERE sessionid=".$_SESSION['feedback_ses_id']));
  //print_r($feedback_row['students_attendance']);
  $edit_rows=unserialize($feedback_row['students_attendance']);

}
//////////Student students_attendance//////// echo '<pre>'; print_r($edit_rows);

//print_r($edit_rows);



//$no_of_students;
$dat_of_session=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');

$start_time=date_format(date_create($ses_det['ses_start_time']), 'h:i A');
 $no_of_students=$tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$session_id));
 //   quiz or Objective id 
 $sql="SELECT objective_name FROM `int_quiz` WHERE id=".$ses_det['quiz_id'];
 $quiz= mysql_fetch_assoc(mysql_query($sql));
 $objective_cover=$quiz['objective_name'];
}






 //print_r($_SESSION);

//
//if ($_GET['class_id'] > 0) {
//    $edit_class = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE id = \'' . $_GET['class_id'] . '\' '));
//    
//    if ($edit_class['id'] != $_GET['class_id']) {
//        $error = 'This is not valid class.';
//    }
//}




// required textbox feedback
// .required.textbox
?>
<style>
 
 label.feedback{ color: #000;
font-size: 20px;
line-height: 135%;
width: 100%;}
 .required.textbox.disable{background-color:gainsboro;}
</style>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i><?=$page_name?></h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="post" action="" enctype="multipart/form-data">
                            <h4>Please fill all required field:Student list</h4>
                            <div class="add_question_wrap clear fullwidth">
                                <p style="display: none;">
                          <label class="feedback" for="session_id">Please enter your session ID here *</label>
                          <input type="text" class="required textbox disable" name="session_id" id="session_id" 
                                 value="<?=(isset($session_id))?$session_id:NULL?>" readonly="">
                                </p>
                                
                                
                         

                                
                                  


                               <!--  <input required="" type="text" placeholder="Your answer" class="required textbox" name="student_issues[12595]" id="student_issues[]" value=""> -->
                               <?php 

                               // New feedback added edit_rows
                              // if(!isset($_SESSION['ses_attendance_arr'])){
                             //  if(!is_array($edit_rows)||empty($edit_rows)){
                               // if(count($edit_rows)==0){



                               $sql_students_list=" SELECT s.id as stuid,s.first_name,s.middle_name,s.last_name,ses.slot_id,ses.student_id
FROM int_slots_x_student_teacher ses
LEFT JOIN students s
ON ses.student_id =s.id ";
// $sql_students_list.=" WHERE ses.slot_id='".$_SESSION['form_1']['session_id']."' ";
$sql_students_list.=" WHERE ses.slot_id='".$_SESSION['feedback_ses_id']."' ";
            $result_stu=mysql_query($sql_students_list); 
            $i=0;

             while ($row=mysql_fetch_assoc($result_stu)) {
                $stud_str=$row['first_name'].' '.$row['middle_name'];
                $student_id=$row['student_id'];
                //  $arr['arrival']

                 $student_time=(isset($edit_rows[$student_id]['arrival']))?$edit_rows[$student_id]['arrival']:'';
                 // attendance
                 $attendance_val='';
                 $attendance_val=$edit_rows[$student_id]['attendance'];//absent|  present
                 // if(!empty($student_time)){

                  //}

                         //   student_id
                         //  Feedback 
                      //$student_info=$edit['form_3']['student_issues'][$row['student_id']];  


                               ?>

                                <p>
                          <label class="feedback"  style="color: red;" 
                          for="session_id"><?=($i+1)?>.&nbsp;<?=$stud_str?>(<?=$row['student_id']?>)-Attendance and Arrival Time.*</label>
                          <input type="hidden" id="students[<?=$row['student_id']?>]" value="<?=$row['student_id']?>"
                            name="students[<?=$row['student_id']?>]"   />

                            <input type="hidden" id="student_names[<?=$row['student_id']?>]" value="<?=$stud_str?>"
                            name="student_names[<?=$row['student_id']?>]"   />
              
                                </p>
                                
                                <div class="row">

                                <div class="form-group col-md-6">
                                            <label class="feedback" for="firstname">Attendance:</label>
                                            <select name="student_present[<?=$row['student_id']?>]" class="form-control">

                       <option value="absent"  <?=($attendance_val=='absent')?'selected':''?>   >absent</option>
                           <option value="present" <?=($attendance_val=='present')?'selected':''?>  >present</option>
                        
                                    
                                </select>

                                          
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="feedback" for="firstname">Arrival Time:</label>
                                            <input type="text" id="firstname"  class="required textbox"
                                             name="arrival[<?=$row['student_id']?>]" value="<?=$student_time?>">
                                           
                                        </div>


                                </div>

                                 <?php $i++; }?>   

                                

                               <!--   Edit ROws -->
                               <?php 
                              // foreach ($edit_rows as $student_id => $arr) {
                                 # code...
                              

                               ?>
                                 <!--  <p>
                          <label class="feedback"  style="color: red;" 
                          for="session_id">1.<?=$stud_str?>ED#<?=$student_id?> -Time and absent|present .*</label>
                          <input type="hidden" id="students[<?=$row['student_id']?>]" value="<?=$row['student_id']?>"
                            name="students[<?=$row['student_id']?>]"   />

                            <input type="hidden" id="student_names[<?=$row['student_id']?>]" value="<?=$stud_str?>"
                            name="student_names[<?=$row['student_id']?>]"   />
              
                                </p>
                                
                                <div class="row">

                                <div class="form-group col-md-6">
                                            <label class="feedback" for="firstname">Attendance:</label>
                               <select name="student_present[<?=$row['student_id']?>]" class="form-control">
                       <option value="absent" selected="">absent</option>
                           <option value="present">present</option>
                        
                                    
                                </select>

                                          
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="feedback" for="firstname">Arrival Time:</label>
                                            <input type="text" id="firstname"  class="required textbox"
                                             name="arrival[<?=$row['student_id']?>]" value="<?=$arr['arrival']?>">
                                           
                                        </div>


                                </div>
 -->
                               <?php  // } ?>

                               <!--   Edit ROws -->
                                
                                
                                
                                
                              
                                    
                                    
                                    
                                    
                               
                                
                                
                                
                                
                                

<!--                                <div id="textarea" style="display: block">
                                    Ex. : Text Area <br/><br/>
                                    <textarea class="form-control" name="std_dtl" 
                   placeholder="Robert Sam Smith" rows="5"></textarea> 

                                </div>-->
                               
                               

                                
                            </div>
                            <p>
                                
                         <input type="submit" name="add_class" id="lesson_submit" class="form_button submit_button" value="Next" />
                               
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
<?php if ($error != '') echo "alert('{$error}')"; ?>


  ///   For selecting Area at 
    $(function () {
        $('input[name="sudent_details"]').on('click', function () {
            if ($(this).val() == 'manual') {
                $('#textarea').show();
            } else {
                $('#textarea').hide();
            }
            if ($(this).val() == 'csv') {
                $('#csv-upload').show();
            } else {
                $('#csv-upload').hide();
            }
        });
    });

</script>

<?php include("footer.php"); ?>
