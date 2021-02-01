<?php
/**
 * @feedback_form_1
 * @ some question from db 
 * @ some on page .
 * **/
///////////////////////////
$error = '';
$author = 1;
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





   $next_url="feedback_form_2.php";
   
  
  /////No sessuon selected///////
  if(!isset($_SESSION['feedback_ses_id'])){
     exit("Page not found. !"); // No session id selected 
     
  }
/////No sessuon selected/////// 


   
   
   
   
  //echo $_SESSION['feedback_ses_id'];     //  Cusom id
 
 
 if (isset($_POST['add_class'])) {
     // form_1 : data  
  $_SESSION['form_1']=$_POST;header("location:$next_url"); exit;
   

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
//$no_of_students;
$dat_of_session=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');

$start_time=date_format(date_create($ses_det['ses_start_time']), 'h:i A');
 $no_of_students=$tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$session_id));
 //   quiz or Objective id 
 $sql="SELECT objective_name FROM `int_quiz` WHERE id=".$ses_det['quiz_id'];
 $quiz= mysql_fetch_assoc(mysql_query($sql));
 $objective_cover=$quiz['objective_name'];
}








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
                            <h4>Please fill all required field:</h4>
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                          <label class="feedback" for="session_id">Please enter your session ID here *</label>
                          <input type="text" class="required textbox disable" name="session_id" id="session_id" 
                                 value="<?=(isset($session_id))?$session_id:NULL?>" readonly="">
                                </p>
                                
                                
                          <p>
                              <label class="feedback" for="dat_of_session">Please enter date of session*</label>
                          <input type="text" class="required textbox disable" name="dat_of_session" id="dat_of_session" 
                      value="<?=(isset($dat_of_session))?$dat_of_session:NULL?>" readonly="">
                                </p> 
                                
                                
                                   <p>
                              <label class="feedback" for="start_time">Please enter start time of session*</label>
                          <input type="text" class="required textbox disable" name="start_time" id="start_time" 
                      value="<?=(isset($start_time))?$start_time:NULL?>"  readonly="" >
                                </p>
                                
                                 <p>
                              <label class="feedback" for="objective_cover">What Objective did you cover?*</label>
                          <input type="text"   placeholder="Your answer"
                                 class="required textbox disable" name="objective_cover" id="objective_cover" 
                      value="<?=(isset($objective_cover))?$objective_cover:NULL?>">
                                </p>
                                
                                   <p>
                              <label class="feedback" for="no_of_students">How many students did you have in your tutor session *</label>
                          <input type="text" 
                                 class="required textbox disable" name="no_of_students" id="no_of_students" 
                      value="<?=(isset($no_of_students))?$no_of_students:0?>">
                                </p>
                                
                                
                                
                                
                                
                              
                                    
                                    
                                    
                                    
                               
                                
                                
                                
                                
                                

<!--                                <div id="textarea" style="display: block">
                                    Ex. : Text Area <br/><br/>
                                    <textarea class="form-control" name="std_dtl" 
                   placeholder="Robert Sam Smith" rows="5"></textarea> 

                                </div>-->
                               
                               

                                
                            </div>


                           <!--  <p>
                                
                         <input type="submit" name="add_class" id="lesson_submit" class="form_button submit_button" value="Next" />
                               
                            </p> -->

                             <p>
                            <a href="feedback_form1.php" class="form_button btn btn-default">Back</a>
                    
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
