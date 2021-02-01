<?php
/**
 * @feedback_form_2
 * @ some question from db 
 * @ some on page .
 * **/
///////////////////////////
$error = '';
//echo 'Tsttt===';
$author = 1;
$datetm = date('Y-m-d H:i:s');


include("header.php");
$created = date('Y-m-d H:i:s');
$page_name="Session Completion Form: Student Behavior";
//////////////////////////
$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
$error = '';

function checkmydate($date) {
    $tempDate = explode('-', $date);
    // checkdate(month, day, year)
    return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
}



//print_r($_SESSION['ses_attendance_arr']);


 $curr_time= date("Y-m-d H:i:s"); #currTime

//echo '<pre>';print_r($_SESSION); die;


 $xx=  (isset($_SESSION['form_1']))? $_SESSION['form_1'] :"form1 data missiing";

  $next_url="feedback_form_3.php";

  
  
  if(!isset($_SESSION['feedback_ses_id'])){
     exit("Page not found. !"); // No session id selected 
     
  }
 ////////////////////////////////
   $is_edit=0; // New feedback: $is_feedback_edit
$sql="SELECT feedback_log,about_students FROM int_session_complete WHERE sessionid=".$_SESSION['feedback_ses_id'];
$res=mysql_query($sql);
  if(isset($_SESSION['form_5'])&&!empty($_SESSION['form_5'])){
  //echo  'Form posted founds.'; // atpost
  
 }
 
 
 if(mysql_num_rows($res)==1){ //edit
    $is_edit=1;
    //echo 'Edkit seion tru'; die;
  $feedback= mysql_fetch_assoc($res);
$edit=unserialize($feedback['feedback_log']);  //  for page

 if(isset($_SESSION['form_5'])&&!empty($_SESSION['form_5'])){
     $edit=null;
  $edit['form_5']=$_SESSION['form_5']; //new arr
  //echo   $edit['form_5']['ans_opn_2'];  // Form Posted: value
 }


//echo  $is_edit; 
   //echo  $edit['form_5']['ans_opn_6']; 
  // echo '<pre>'; print_r($edit);
   //die;  // Form 2
}

 
  
  
  
  
  //print_r($_SESSION);
  $profile=mysql_fetch_assoc(mysql_query("SELECT id,payment_email,payment_phone FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));
  


//$edit=unserialize($profile['profile_1']);
  
  ////////////////////////////////
  
if (isset($_POST['form_submit'])) {
    $_SESSION['form_5']=$_POST;
    // $_SESSION['form_1']['session_id']
    $ses_id=$_SESSION['form_1']['session_id'];
    /**$ses_id :form1 posted id 
     * 
     *    school_id teacher_id
     * // about_students see_different
     * // stu_engaged engaged_not_info :: custom 
     * **/

  /// Update tutor paypal Email ////
    $tutorId=$_SESSION['ses_teacher_id'];
    // is_paypal
    if (isset($_POST['is_paypal'])&&$_POST['is_paypal']=='No') {
      /// Validate profile 
      $profile1=mysql_fetch_assoc(mysql_query("SELECT id,payment_email,payment_phone FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));

       if($profile1){ 

       // echo 'Updated new '; die; 

          $sql=mysql_query(" UPDATE `tutor_profiles` SET payment_email= '".$_POST['is_paypal_email']."',updated='$curr_time' WHERE tutorid=".$tutorId);
      // echo $sql; die ;
       $msg='PayPal Email updated for payment option.';
       //$res=mysql_query($sql);

    

       }else{

        $sql=" INSERT INTO `tutor_profiles` SET payment_email= '".$_POST['is_paypal_email']."',tutorid='$tutorId' ";
       
        $res=mysql_query($sql);
        
       }
     
    //////Update in main Table/////////
        $profile2=mysql_fetch_assoc(mysql_query("SELECT id,payment_email,payment_phone FROM `tutor_profiles` WHERE tutorid=".$_SESSION['ses_teacher_id']));

         $sql=mysql_query(" UPDATE `gig_teachers` SET payment_em= '".$profile2['payment_email']."',updated='$curr_time' WHERE id=".$tutorId);
        // mysql_query
      // die; 





    }




  $ses_det= mysql_fetch_assoc(mysql_query("SELECT * FROM int_schools_x_sessions_log WHERE id=".$ses_id));
//$session_id;$dat_of_session; $start_time; 
    $about_students_str=""; // 5 Student info
    // student_issues
    $stu_arr=$_SESSION['form_3']['student_issues'];// Students list
    $student_arr=array();
    foreach ($stu_arr as $sid=> $value) {
        $x=array("Name"=>"StuName".$sid,"About"=>$value);
        $student_arr[$sid]=$x;
    }
  
    $about_students_str=serialize($student_arr); //  var_export($student_arr); die;
   // feedback_log
   $st_bh_info=$_SESSION['form_4']['student_behavior_info'];
   //$_SESSION['form_4']['student_behavior_info']=mysql_real_escape_string($_SESSION['form_4']['student_behavior_info']) ;
  $_SESSION['form_4']['student_behavior_info']=''; // for seralisze 
    
    
    // $feedback_log_serialize=serialize($student_arr);
 // echo '<pre>';print_r($_SESSION); die;
    $feedback_log_serialize=serialize($_SESSION);
    //$original_array=unserialize($feedback_log_serialize);
    
     // echo '<pre>';print_r($original_array); die;
     // add session data.
    ////////////////////////////////////
    $engaged_not_info=($_SESSION['form_2']['stu_engaged']=="no")?$_SESSION['form_2']['engaged_not_info']:NULL;
 $students_attendance_str=serialize($_SESSION['ses_attendance_arr']);
  // is_attendance  students_attendance

    ////////////////////////////// mysql_real_escape_string htmlentities
   $query = " INSERT INTO int_session_complete SET sessionid='$ses_id',"
          . "is_complete='yes',is_attendance='1', "
          . "students_attendance='".$students_attendance_str."', "
           . "ses_start_time='".$ses_det['ses_start_time']."', "
          . "quiz_id='".$ses_det['quiz_id']."', "
          . "no_of_students='".$_SESSION['form_1']['no_of_students']."', "
          . "school_id='".$ses_det['school_id']."', "
          . "teacher_id='".$ses_det['teacher_id']."', "
          . "tut_teacher_id='".$ses_det['tut_teacher_id']."', "
          . "student_behavior_info='".addslashes($st_bh_info)."', "
          . "about_students='".addslashes($about_students_str)."', "
          . "see_different='".addslashes($_POST['see_different'])."', "
          . "stu_engaged='".$_SESSION['form_2']['stu_engaged']."', "
           . 'feedback_log=\''.addslashes($feedback_log_serialize) .' \', '
          .   'engaged_not_info=\''.addslashes($engaged_not_info) .' \', '
          . "created='$curr_time' ";  //die;
  
  /////////Edit ////////////
  if($is_edit==1){
  $query= " UPDATE int_session_complete SET sessionid='$ses_id',"
          
         
       
          . "students_attendance='".$students_attendance_str."', "
          . "student_behavior_info='".addslashes($st_bh_info)."', "
          . "about_students='".addslashes($about_students_str)."', "
          . "see_different='".addslashes($_POST['see_different'])."', "
          . "stu_engaged='".$_SESSION['form_2']['stu_engaged']."', "
           . "feedback_log='".addslashes($feedback_log_serialize)."', "
          . "engaged_not_info='".addslashes($engaged_not_info)."', "
          . "updated='$curr_time' ";
  $query.=" WHERE sessionid='$ses_id' AND tut_teacher_id='".$_SESSION['ses_teacher_id']."' ";
  
  
  }
  
  //echo '<pre>';  echo $query ; die;
  
   $main= mysql_query($query) or die('error-'.mysql_error());
    //if($main==true) echo  'Update dd info ';
    // die;
    if($is_edit==0){
          $feed_id= mysql_insert_id();
       //   echo $feed_id.'Feedback id '; die;
   //$up= " UPDATE int_schools_x_sessions_log SET feedback_id='$feed_id' WHERE id=".$ses_id;
          $updated_paypal_email=$_POST['is_paypal_email'];
  
  $up= " UPDATE int_schools_x_sessions_log SET paypal_email='$updated_paypal_email',feedback_id='$feed_id' WHERE id=".$ses_id;

   mysql_query($up);// Update feedback in main table 
    }
 //  Save all questions:: Answer//
  //  int_ses_feedback_questions_answer # int_ses_feedback_questions
   $sql=" SELECT * FROM `int_ses_feedback_questions` WHERE 1 ";
    $result_qq= mysql_query($sql);$strl=NULL;
   while( $row = mysql_fetch_assoc($result_qq) ) {
       // $ses_det['tut_teacher_id'] :: Tutor of Live session 
       $qanswer=$_SESSION[$row['form_step']]['ans_opn_'.$row['id']];
       ////////////////// other_info ::Previous 
       $opn_other=($qanswer=="Other")?'yes':'no';
       if($qanswer=="Other")
       $qanswer=$_SESSION[$row['form_step']]['ans_other_text_'.$row['id']];
       // ques_text tutor_id
   $query= " INSERT INTO int_ses_feedback_questions_answer SET sessionid='$ses_id',"
          . "ques_id='".$row['id']."',ques_text='".$row['ques_text']."',tutor_id='".$ses_det['tut_teacher_id']."',"
           . "answer='$qanswer',opn_other='$opn_other', "
          . "created='$curr_time' ";
    if($is_edit==1){
       // echo 'Edit feedback info '; die;
     $query= " UPDATE int_ses_feedback_questions_answer SET sessionid='$ses_id',"
          . "ques_id='".$row['id']."', "
           . "answer='$qanswer',opn_other='$opn_other', "
          . "updated='$curr_time' ";   
        
       $query.=" WHERE sessionid='$ses_id' AND tutor_id='".$_SESSION['ses_teacher_id']."' AND ques_id='".$row['id']."' ";  
    
       
       
    }
   
   ///   Edit feedback///
   
   
   
   
   $insert= mysql_query($query) or die('Question'.mysql_error());
   if($insert==true) echo 'Ques edit succesffuly . '; //die;
   
   $arr[]= $_SESSION[$row['form_step']]['ans_other_text_'.$row['id']];
  // echo $row['form_step'].'ans_opn_'.$row['id']; 
    // ans_opn_8   
   // Other
   // ans_other_text_8
   #  ques_id opn_other answer
   }
   //print_r($arr); die;
    $error=" Your response has been recorded.";
    
    
//  unset($_SESSION['form_4']); unset($_SESSION['form_5']);  
//     unset($_SESSION['form_3']); unset($_SESSION['form_2']);
//  unset($_SESSION['form_1']);
     unset($_SESSION['ses_attendance_arr']);
  
   header("location:feedback_success.php"); exit;
    
    

   
  
  //Unset all form seesson. 
}




// back
 $back_url="feedback_form_4.php";
if (isset($_POST['back'])) {
        header("location:$back_url"); exit;
}









$teacher_grade_res = mysql_query("
	SELECT  GROUP_CONCAT( grade_level_id SEPARATOR ',' ) AS shared_terms
	FROM `techer_permissions`
	WHERE teacher_id = {$user_id} 
");
$t_grades = mysql_fetch_assoc($teacher_grade_res);
$teacher_grade = $t_grades['shared_terms'];
if ($_GET['class_id'] > 0) {
    $edit_class = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE id = \'' . $_GET['class_id'] . '\' '));
    
    if ($edit_class['id'] != $_GET['class_id']) {
        $error = 'This is not valid class.';
    }
}
// required textbox feedback
?>
<style>
 
 label.feedback{ color: #000;
font-size: 20px;
line-height: 135%;
width: 100%;}
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
                            <h4>We need your feedback to improve the system for Tutors and Students. Please take a moment to share.</h4>
                            <div class="add_question_wrap clear fullwidth">
                               
                                
                                
                             <?php 
                             $sql=" SELECT * FROM int_ses_feedback_questions WHERE form_step='form_5' ORDER BY weight ";
                             $res= mysql_query($sql);
                             
                            while($data=mysql_fetch_assoc($res)){
                             
                             ?>   
                                
                                
                               
                             <p>
     
               <label class="feedback" 
                      for="session_id"><?=$data['ques_text']?></label>
                                 <?php 
                                 // if type ==radio
                                 if($data['type']=="radio"||$data['type']=="radio|other"){
                                 ?>
      
                                 
                                   <?php 
                                
                                 // opn_1 , opn_5 
                         
                                 for($i=1;$i<=5;$i++){ 
                                     if(empty($data['opn_'.$i]))continue;
                                    // checked
                                      $checked=NULL;
                                      $option_key=$data["opn_".$i];
                                     // if($data["opn_".$i]=="Other")
                                              
                                      ///// Other Option Check
                                     if($is_edit==1&&$option_key===$edit['form_5']["ans_opn_".$data['id']]){
                                        $checked="checked"; 
                                     }
                                     // For posted value:
                                     //elseif($i==1)
                                        // $checked="checked";
                                        // checked
                                     // opn_other
                                     // ans_other_text_6
                                     # Test , change other options #
                                    if($edit['form_5']["ans_opn_".$data['id']]!="Other") 
                                     $edit['form_5']["ans_other_text_".$data['id']]=NULL;
                                    # Test , change other options #
                                 ?>
      
                                 <input type="radio" 
                                        name="<?="ans_opn_".$data['id']?>" value="<?=$data["opn_".$i]?>" <?=$checked?> required /><?=$data["opn_".$i]?><br>
                                   <?php 
                                   
                                   ///////
                                   if($data['opn_other']=="yes"&&$data["opn_".$i]=="Other"){
                                   ?>
                                 <input type="text" placeholder="Your answer" style=" width: 60%"
                                        name="<?="ans_other_text_".$data['id']?>" id="" 
                      value="<?=$edit['form_5']["ans_other_text_".$data['id']]?>"> <?php }?>
                                
                                 
                                  <?php }?>    
                                 
                                 
                                 
                                    
                                 <?php }?>
                                </p>   
                                
                            <?php  
                            
                                 }?>    
     
                                
                                <?php 
                                // see_different 
                                // $edit['form_5']["ans_other_text_".$data['id']]
                                $see_different=$edit['form_5']['see_different'];
                                ?>
                                
                                
                                 <p id="stu_engaged_area">
                          <label class="feedback" for="engaged_not_info">What would you like to see different? *</label>
              <input type="text" placeholder="Your answer" class="required textbox" 
                     name="see_different" required id="engaged_not_info" 
                      value="<?=(isset($see_different))?$see_different:NULL?>">
                                </p> 
                                
     
                           

                           <!--  Paypal Email  -->
                           <?php 
                           $Paypal_email='rohit@srinfosystem.com';

                           ?>
                           <p>
                   <script>
$(document).ready(function(){
  $('#paypal_email_id').hide();
  ////
  $('input[type=radio][name=is_paypal]').change(function() {

      //alert('Vlaue:'+this.value);

    if (this.value == 'Yes') {
      $("#paypal_email_id").prop('required',false);
       $('#paypal_email_id').hide();
        
    }

     if (this.value == 'No') {
      // alert("Enter email");
      $("#paypal_email_id").prop('required',true);
       $('#paypal_email_id').show();
        
    }

    //
    
});

  
    //alert('fgfh');
});
</script>
                         <?php if(!empty($profile['payment_email'])){?>
                       <label class="feedback" for="session_id">Is <?=$profile['payment_email']?> your PayPal email?</label>
                                       
                                 
                                         
                                 <input type="radio" name="is_paypal" value="Yes" checked="" >Yes<br>
                                                                   
                                 
                                        
                                
                                                                   
                                 
                                        
                                 <input type="radio" name="is_paypal" value="No">No<br>
                                       <input type="text" placeholder="Your Email"
                                        style=" width: 60%" name="is_paypal_email"  id="paypal_email_id" value="">

                                        <?php }else{?>
                              <label class="feedback" for="session_id"> Add your PayPal email?</label>
                              <input type="radio" name="is_paypal" value="No" checked="">
                               <input type="text" placeholder="Your Email"
                                        style=" width: 60%" name="is_paypal_email"  id="" >



                                        <?php }?> 


                                  </p>

                                   </div>



                            <p>
                            <a href="feedback_form_4.php" class="form_button btn btn-default">Back</a>
                    <!-- <input type="submit" name="back" id="lesson_submit" class="form_button submit_button" value="BACK" />  -->
                         <input type="submit" name="form_submit" id="lesson_submit" class="form_button submit_button" value="SUBMIT" />
                               
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
        $('input[name="stu_engaged"]').on('click', function () {
            if ($(this).val() == 'yes') {
                
                $('#stu_engaged_area').hide();
            } else {
                $('#stu_engaged_area').show();
               
            }
            
            

        });
    });

</script>

<?php include("footer.php"); ?>
