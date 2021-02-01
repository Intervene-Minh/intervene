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

 

if (isset($_POST['form_submit'])) {
    $_SESSION['form_3']=$_POST;
  //print_r($_POST); die;
    # if save student  feedback info 
    
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
                      for="session_id">#<?=$data['ques_text']?></label>
                                 <?php 
                                 // if type ==radio
                                 if($data['type']=="radio"||$data['type']=="radio|other"){
                                 ?>
      
                                    <input type="radio" name="<?="ans_opn_".$data['id']?>" value="yes" checked="" /> Yes<br>
                                      <input type="radio" name="<?="ans_opn_".$data['id']?>" value="no" checked="" /> No<br>
                                      <?php  if($data['type']=="radio"){ ?>
                                    <input type="radio" name="<?="ans_opn_".$data['id']?>" value="Somewhat" /> Somewhat
                                    <?php }else{?>
                                 <input type="radio" name="<?="ans_opn_".$data['id']?>" value="other_info" /> Other
                                 <input type="text" placeholder="Your answer" style=" width: 60%"
                                        name="<?="ans_other_text_".$data['id']?>" id="" 
                      value="">
                                    <?php } ?>
                                    
                                 <?php }?>
                                </p>   
                                
                            <?php  
                            
                                 }?>   
                                
                                
                                
                                
                                
                              
                                
                                
                         <?php  
                         // Student List that attend the session .: Not all Students
                         // those are assigned by intervene teacher in a Live session
                         // session_id
                         $total_students=$_SESSION['form_1']['no_of_students'];// def
                           //for($i=1;$i<=$total_students;$i++){
                          $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$_SESSION['form_1']['session_id']."' ";
            $resss=mysql_query($q); 
            $i=1;
                          while ($row2=mysql_fetch_assoc($resss)) {
                $stud_str=$row2['first_name'].' '.$row2['middle_name'];
                         //  	student_id
                         
                           
                             ?>           
                                
                                
                                
                                 <p>
                          <label class="feedback" for="session_id"><?=$i?>.<?=$stud_str?>- Please enter student's name and tell the teacher about any additional academic issues .*</label>
              <input type="text" placeholder="Your answer" class="required textbox"
                     name="student_issues[<?=$row2['student_id']?>]" id="student_issues[]" 
                      value="">
                                </p> 
                            <?php $i++; }?>   
                                
                                
                               
                                
                            </div>
                            <p>
                    <input type="submit" name="back" id="lesson_submit" class="form_button submit_button" value="BACK" />            
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
