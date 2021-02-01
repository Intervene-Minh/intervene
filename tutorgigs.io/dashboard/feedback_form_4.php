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




if(!isset($_SESSION['feedback_ses_id'])){
     exit("Page not found. !"); // No session id selected 
     
  }
  
  
  $is_edit=0; // New feedback: $is_feedback_edit
$sql="SELECT feedback_log,about_students,student_behavior_info FROM int_session_complete WHERE sessionid=".$_SESSION['feedback_ses_id'];
$res=mysql_query($sql);
  if(isset($_SESSION['form_4'])&&!empty($_SESSION['form_4'])){
//echo  'Form posted foundsXX .';
    $edit=null;
 $edit['form_4']=$_SESSION['form_4']; //new arr
 }
 
 
 if(mysql_num_rows($res)==1){ //edit
    $is_edit=1;
  $feedback= mysql_fetch_assoc($res);
$edit=unserialize($feedback['feedback_log']);  //  for page

 if(isset($_SESSION['form_4'])&&!empty($_SESSION['form_4'])){
     $edit=null;
  $edit['form_4']=$_SESSION['form_4']; //new arr
  
  //echo   $edit['form_3']['ans_opn_2'];  // Form Posted: value
 }


//echo  $is_edit; 
   //echo  $edit['form_3']['ans_opn_6']; 
  // echo '<pre>'; print_r($edit);
  // die;  // Form 2
}

 // echo $feedback['student_behavior_info']; 
  

//echo '<pre>';print_r($_SESSION); //die;


 $xx=  (isset($_SESSION['form_1']))? $_SESSION['form_1'] :"form1 data missiing";

//die;
  $next_url="feedback_form_5.php";
//unset($_SESSION['form_1']); unset($_SESSION['form_2']);
if (isset($_POST['form_submit'])) {
    $_SESSION['form_4']=$_POST;
  //print_r($_POST); die;


    header("location:$next_url"); exit;
   
//print_r(); die;
}



// back
 $back_url="feedback_form_3.php";
if (isset($_POST['back'])) {
        header("location:$back_url"); exit;
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
                    <!-- <h4>Please describe participation by students:</h4>-->
                           <div class="profile-top col-md-12">        
                               <?php  if(isset($error)&&!empty($error)){?>
                                    <div class="profile-item alert alert-info text-left">
                                  <?=$error;?>            
                           </div> <?php }?>                      
                                </div>
                            <div class="add_question_wrap clear fullwidth">
                                                        
                        <?php 
                         //if($is_edit==1){
                          if(isset($feedback['student_behavior_info'])){
                            $xxxxx=$feedback['student_behavior_info']; 
                          
                             }elseif(isset($edit['form_4']['student_behavior_info'])&&!empty($edit['form_4']['student_behavior_info'])){
                            // edit value
                             $xxxxx= $edit['form_4']['student_behavior_info'];
                             // student_behavior_info
                         }
                        ?>        
                                
                                <p>
									<label class="feedback" for="lesson_desc"> Please enter student's name and tell the teacher about any Behavior Issues</label>
									<textarea name="student_behavior_info" id="lesson_desc" class="textbox" rows="5" ><?=$xxxxx?></textarea>
								</p>          
                                
                            </div>
                            <p>
                            <a href="feedback_form_3.php" class="form_button btn btn-default">Back</a>
                    <!-- <input type="submit" name="back" id="lesson_submit" class="form_button submit_button" value="BACK" />  -->
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
<?php //if ($error != '') echo "alert('{$error}')"; ?>


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
