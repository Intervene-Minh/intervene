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
$page_name="Session Completion Form: Student Engagement";
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

/////No sessuon selected///////
  if(!isset($_SESSION['feedback_ses_id'])){
     exit("Page not found. !"); // No session id selected 
     
  }
/////No sessuon selected/////// 

///Edit feedback data 
$is_edit=0; // New feedback: $is_feedback_edit

//$session_id;$dat_of_session; $start_time;
$sql="SELECT feedback_log,about_students FROM int_session_complete WHERE sessionid=".$_SESSION['feedback_ses_id'];
$res=mysql_query($sql);
//$total=mysql_num_rows($res);
/**
 * 1. pic sesson data :Form posted
 * 2. for if Edit ok :: $is_edit=1;
 * 
// Session post value on proirity1, Edit data 2.

 
 * **/
 if(isset($_SESSION['form_2'])&&!empty($_SESSION['form_2'])){
//echo  'Form posted foundsXX .';
     $edit=null;
 $edit['form_2']=$_SESSION['form_2']; //new arr
 //echo   $edit['form_2']['ans_opn_2']; //tt
  //echo '<pre>'; print_r($edit); die;  // Form 2
 }
 
 
 if(mysql_num_rows($res)==1){ //edit
    $is_edit=1;
  $feedback= mysql_fetch_assoc($res);
$edit=unserialize($feedback['feedback_log']);  //  for page

 if(isset($_SESSION['form_2'])&&!empty($_SESSION['form_2'])){
     $edit=null;
  $edit['form_2']=$_SESSION['form_2']; //new arr
  //echo   $edit['form_2']['ans_opn_2'];  // Form Posted: value
 }


//echo  $is_edit; 
   // echo '<pre>'; print_r($edit); die;  // Form 2
}


 $xx=  (isset($_SESSION['form_1']))? $_SESSION['form_1'] :"form1 data missiing";

//die;
  $next_url="feedback_form_3.php";

  
  
if (isset($_POST['form_submit'])) {
    
    $_SESSION['form_2']=$_POST;
   //echo '<pre>';print_r($_POST); die;
   // Edit Feedback
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
 // Edit Feedback
 // 
 // 
 //  while editing  :: Edit as per form Post 

    header("location:$next_url"); exit;
   
//print_r(); die;
}




// back
 $back_url="feedback_form_1.php";
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
                            <h4>Please describe participation by students:</h4>
                            <div class="add_question_wrap clear fullwidth">
                             
                                
                             <?php 
                             $sql="SELECT * FROM `int_ses_feedback_questions` WHERE form_step='form_2' ";
                             $data= mysql_fetch_assoc(mysql_query($sql));
                             // Answer
                             $sql=" SELECT * FROM `int_ses_feedback_questions_answer` WHERE `sessionid` = 57 AND `tutor_id` = 2 AND `ques_id` = 2  ";
                             ?>   
     
                             <p>
     
               <label class="feedback" 
                      for="session_id">#<?=$data['ques_text']?>
                   <br>
                   <?php // echo  $edit['form_2']["ans_opn_".$data['id']]?>
                   <input  name="qn_id[<?=$data['id']?>]"  type="hidden"  value="<?=$data['id']?>"> 
                          
               </label>
                                 <?php 
                                
                                 if($data['type']=="radio"){
                                     // ans_opn_2
                                 for($i=1;$i<=3;$i++){ 
                                     if(empty($data['opn_'.$i]))continue;
                                    // checked
                                      $checked=NULL;
                                   //  if($is_edit==1&&$data["opn_".$i]===$edit['form_2']["ans_opn_".$data['id']]){
                                    
                                      if($data["opn_".$i]===$edit['form_2']["ans_opn_".$data['id']]){
                                        $checked="checked"; 
                                     }
                                   
                                 ?>
      
                                 <input required type="radio" name="<?="ans_opn_".$data['id']?>" value="<?=$data["opn_".$i]?>" <?=$checked?> /><?=$data["opn_".$i]?><br>
                                   
                                  <?php }?>       
                                        
                                        <?php }?>
                                </p>   
                                
    
                                
                               <p>
     
               <label class="feedback" 
                      for="stu_engaged">Were students engaged the entire time?*</label>
                                    <?php 
                                          // $default="no";
                                 $default_stu_engaged=(isset($edit['form_2']['stu_engaged']))?$edit['form_2']['stu_engaged']:"yes"; 

                                          ?> 
     
                                   <input type="radio" required  <?php if($default_stu_engaged=="yes") echo 'checked';  ?>
                                          name="stu_engaged" value="yes" />&nbsp;Yes<br>
                                    <input type="radio"  <?php if($default_stu_engaged=="no") echo 'checked';  ?> name="stu_engaged" value="no" />&nbsp;No
                                </p>
                                
                                
                                
                               <?php
                                $engaged_not_info=(!empty($edit['form_2']['engaged_not_info']))?$edit['form_2']['engaged_not_info']:NULL;
                                $default_txt_show=null;
                                 if($default_stu_engaged=="yes"){
                                             $default_txt_show="none";
                                             $engaged_not_info=NULL;
                                         }
                                
                               ?>
                                
                                
                                <p id="stu_engaged_area"  style=" display:<?=$default_txt_show?>;">
                          <label class="feedback" for="engaged_not_info">If not, please give details (with names) if appropriate.*</label>
              <input type="text" placeholder="Your answer" class="required textbox" 
                     name="engaged_not_info" id="engaged_not_info" 
                      value="<?=(isset($engaged_not_info))?$engaged_not_info:NULL?>" >
                                </p> 
    
                            </div>
                            <p>
                            <a href="feedback_form_1.php" class="form_button btn btn-default">Back</a>
                            <!-- <button name="back1"> <a href="http://localhost/tutorgigs.io/dashboard/feedback_form_1.php"></a>Back</button>  -->
                           
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
        $('input[name="stu_engaged"]').on('click', function () {
            if ($(this).val() == 'yes') {
                
                $('#stu_engaged_area').hide();
                $('#engaged_not_info').prop('required', false);
            } else {
                $('#stu_engaged_area').show();
                $('#engaged_not_info').prop('required', true);

            }
            
            

        });
    });

</script>

<?php include("footer.php"); ?>
