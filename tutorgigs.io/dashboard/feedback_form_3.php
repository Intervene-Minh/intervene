<?php
/**
 * @feedback_form_2
 * @ some question from db 
 * @ some on page .
 * **/
///////////////////////////
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
$created = date('Y-m-d H:i:s');
$page_name="Session Completion Form:Level of Understanding";
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

  $next_url="feedback_form_4.php";
  
  
  /////No sessuon selected///////
  if(!isset($_SESSION['feedback_ses_id'])){
     exit("Page not found. !"); // No session id selected 
     
  }
/////No sessuon selected/////// 

 $is_edit=0; // New feedback: $is_feedback_edit
$sql="SELECT feedback_log,about_students FROM int_session_complete WHERE sessionid=".$_SESSION['feedback_ses_id'];
$res=mysql_query($sql);
  if(isset($_SESSION['form_3'])&&!empty($_SESSION['form_3'])){
//echo  'Form posted foundsXX .';
$edit=null;
$edit['form_3']=$_SESSION['form_3'];
 }
 
 
 if(mysql_num_rows($res)==1){ //edit
    $is_edit=1;
  $feedback= mysql_fetch_assoc($res);
$edit=unserialize($feedback['feedback_log']);  //  for page

 if(isset($_SESSION['form_3'])&&!empty($_SESSION['form_3'])){
     $edit=null;
  $edit['form_3']=$_SESSION['form_3']; //new arr
  //echo   $edit['form_3']['ans_opn_2'];  // Form Posted: value
 }

}

  
$xx=  (isset($_SESSION['form_2']))? $_SESSION['form_2'] :"form1 data missiing";

if (isset($_POST['form_submit'])) {
    $_SESSION['form_3']=$_POST;
  //print_r($_POST); die;
    # if save student  feedback info 
    $ses_id=$_SESSION['feedback_ses_id'];//Session edit id
 if($is_edit==199){
     foreach ($_POST['qn_id'] as $key=> $qid){
         ///$qanswer=$_SESSION[$row['form_step']]['ans_opn_'.$row['id']];
         $answer_q=$_POST["ans_opn_".$qid];
       
         
     $up= " UPDATE int_ses_feedback_questions_answer SET answer='$answer_q'"
             . " WHERE sessionid='$ses_id' AND tutor_id='".$_SESSION['ses_teacher_id']."' AND ques_id='$qid' ";
   mysql_query($up);// Update feedback in main table 
  
         
     }
   
   // hiddent qn id
 }
  header("location:$next_url"); exit;
   
}

//echo '<pre>';print_r($_SESSION); die;


// back2
 $back_url="feedback_form_2.php";
if (isset($_POST['back'])) {
        header("location:$back_url"); exit;
}


    
        
//$t_grades = mysql_fetch_assoc($teacher_grade_res);
//$teacher_grade = $t_grades['shared_terms'];
//if ($_GET['class_id'] > 0) {
//    $edit_class = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE id = \'' . $_GET['class_id'] . '\' '));
//    
//    if ($edit_class['id'] != $_GET['class_id']) {
//        $error = 'This is not valid class.';
//    }
//}


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
                            <h4>Please describe participation by students:</h4>
                            <div class="add_question_wrap clear fullwidth">
                             
                                
                             <?php 
                             $sql=" SELECT * FROM int_ses_feedback_questions WHERE form_step='form_3' ORDER BY weight ";
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
                         
                                 for($i=1;$i<=3;$i++){ 
                                     if(empty($data['opn_'.$i]))continue;
                                    // checked
                                      $checked=NULL;
                                    //  if($is_edit==1&&$data["opn_".$i]===$edit['form_3']["ans_opn_".$data['id']]){
                                    //     $checked="checked"; 
                                        if($data["opn_".$i]===$edit['form_3']["ans_opn_".$data['id']]){
                                            $checked="checked"; 
                                     }
                                     // For posted value:
                                     //elseif($i==1)
                                        // $checked="checked";
                                        // checked
                                     // opn_other
                                     // ans_other_text_6
                                  # Test , change other options #
                                    if($edit['form_3']["ans_opn_".$data['id']]!="Other") 
                                     $edit['form_3']["ans_other_text_".$data['id']]=NULL;
                                    # Test , change other options #    
                                     
                                     
                                 ?>
      
                                 <input type="radio" required
                                        name="<?="ans_opn_".$data['id']?>" value="<?=$data["opn_".$i]?>" <?=$checked?> /><?=$data["opn_".$i]?><br>
                                   <?php 
                                   if($data['opn_other']=="yes"&&$data["opn_".$i]=="Other"){
                                   ?>
                                 <input type="text" placeholder="Your answer" style=" width: 60%"
                                        name="<?="ans_other_text_".$data['id']?>" id="" 
                      value="<?=$edit['form_3']["ans_other_text_".$data['id']]?>"> <?php }?>
                                
                                 
                                  <?php }?>
                                 
                                 
                                 <?php }?>
                                 
                                 
                                </p>   
                                
                            <?php }?>   
           
                                
                         <?php  
                         // student_issues
                         
                         
                         // Student List that attend the session .: Not all Students
                         // those are assigned by intervene teacher in a Live session
                         // session_id
                         //echo  $student_info=$edit['form_3']['ans_opn_4'];
                          //  print_r($student_info=$edit['form_3']['student_issues'][285]); die;
                         $total_students=$_SESSION['form_1']['no_of_students'];// def
                           //for($i=1;$i<=$total_students;$i++){
                          $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$_SESSION['form_1']['session_id']."' ";
            $resss=mysql_query($q); 
            $i=1;
                          while ($row2=mysql_fetch_assoc($resss)) {
                $stud_str=$row2['first_name'].' '.$row2['middle_name'];
                         //  	student_id
                         //  Feedback 
                      $student_info=$edit['form_3']['student_issues'][$row2['student_id']];  
                             ?>           
                                
                                
                                
                                 <p>
                          <label class="feedback" for="session_id"><?=$i?>.<?=$stud_str?>- Please enter student's name and tell the teacher about any additional academic issues .*</label>
              <input required type="text" placeholder="Your answer" class="required textbox"
                     name="student_issues[<?=$row2['student_id']?>]" id="student_issues[]" 
                      value="<?=$student_info?>">
                                </p> 
                            <?php $i++; }?>   
                                
                                
                               
                                
                            </div>
                            <p>
                            <a href="feedback_form_2.php" class="form_button btn btn-default">Back</a>
                    <!-- <input type="submit" name="back" id="lesson_submit" class="form_button submit_button" value="BACK" /> -->
                         <input type="submit" name="form_submit" id="lesson_submit" class="form_button submit_button" value="Next" />
                               
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
