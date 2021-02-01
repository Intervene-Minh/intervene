<?php
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
//include('inc/connection.php'); 
//session_start();
//	ob_start();

////////////////////
///include("header.php");
include("header_custom.php");
$created = date('Y-m-d H:i:s');

$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
$error = '';


//
//function checkmydate($date) {
//    $tempDate = explode('-', $date);
//    // checkdate(month, day, year)
//    return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
//}





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
           
            
            <!-- /#sidebar -->
            <div id="content" class="col-md-12">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Session Feedback</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="post" action="" enctype="multipart/form-data">
                            <h4>Session Feedback details</h4>
                            <?php  
                             $curr_time= date("Y-m-d H:i:s"); 
                            
                              $sql=" SELECT * FROM `int_ses_feedback_questions_answer` WHERE 1 ";
                               //$sql.=" AND sessionid =207 ";
                               if(isset($_GET['ses'])){
                               $getid=$_GET['ses'];
                               // echo $getidxx=' sessuin id-'.$_GET['ses']; 
                               }
                             // echo $sql;
                               $sql.=" AND sessionid ='$getid' ";
                                 $res= mysql_query($sql);
                        ///////////////////Session details
                                 //$getid=207;
                             if(isset($_GET['ses'])){
                                 
$mster= mysql_fetch_assoc(mysql_query("SELECT * FROM int_schools_x_sessions_log WHERE id=".$getid)); 
  @extract($mster);  }
  
  
                                
  
                            // $in_sec= strtotime($ses_start_time) - strtotime($curr_time);///604800  
                             $sdate=date_format(date_create($mster['ses_start_time']), 'F d,Y');
           $at_time=date_format(date_create($ses_start_time), 'h:i a');
           
            /// list of student 
            $q=" Select sd.first_name,sd.middle_name,sd.last_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$getid."' "; // middle_name last_name
            $resss=mysql_query($q);
            $stud_str=array();
            while ($row= mysql_fetch_assoc($resss)) {
                 $stu_name=$row['first_name']." ".$row['middle_name']." ".$row['last_name'];
                 if(trim($stu_name) != '') {
                $stud_str[]=trim($stu_name);
                 }
                
            }
            $stud_str = array_flip($stud_str);
            $stud_str = array_flip($stud_str);
           // print_r($stud_str);
                                 
                            
                            ?>
                            <div class="add_question_wrap clear fullwidth">
                              
                                

                               <p>
                                    <label class="feedback" 
                      for="stu_engaged">Date of Session:
                                    <span class="text-primary"  style="font-weight: bold;">
                                  <?=$sdate?> </span></label>
                              <br/>
                              
                              <label class="feedback" 
                      for="stu_engaged">Time of Session:
                                    <span class="text-primary"  style="font-weight: bold;">
                                  <?=$at_time?> </span></label>
                              <br/>
                              
                              
                               <label class="feedback" 
                      for="stu_engaged">Session ID:
                                    <span class="text-primary"  style="font-weight: bold;">
                                  <?=$getid?> </span></label>
                              <br/>
                              
                              <label class="feedback" 
                      for="stu_engaged">Student Names:
                                    <span class="text-primary" >
                                  <?php if(is_array($stud_str))
                                          echo implode(",",$stud_str);?> </span></label>
                              <br/>
                                  
                                </p> 
                                
                                
                                <?php if($feedback_id>0){  // feedabackgvn?>
                               
                            
                              <label class="feedback" 
                      for="stu_engaged">
                                    <span class="text-danger"  style="font-weight: bold;">
                                        Answers from Tutor Complete:
                                  </span></label>     
                                
                                
                               <?php
                             
                                 $i=0;
                                 while ($row = mysql_fetch_assoc($res)) {
                               ?> 
                                
                                <p>
                                    <label class="feedback" 
                      for="stu_engaged">Ques<?=($i+1)?>.<?=$row['ques_text']?></label>
                              <br/>
<!--                              <span class="required textbox  ">-->
                              
                              <span  style=" color: green; font-weight: bold;">
                                  Answer:<span  class="text-info"><?=$row['answer']?></span>
                              </span>
                                    
                                </p>
                                
                                 <?php $i++;} ?>
                                
                               
                                
                             <?php 
                          $sql="SELECT feedback_log,about_students FROM int_session_complete "
                                  . "WHERE sessionid=".$getid;//207
                           $res=mysql_query($sql);
                             $feedback= mysql_fetch_assoc($res);
               $edit=unserialize($feedback['about_students']);  //  for page feedback_log
               // echo '<pre>'; print_r($edit); die;
               
                 foreach ($edit as $item=>$arr) {
    

                
                              ?>   
                          
                                
                             <p>
                                    <label class="feedback" 
                      for="stu_engaged"><?=$arr['Name']?></label>
                              <br/>
<!--                              <span class="required textbox  ">-->
                             
                              <span >
                                 Info: <?=$arr['About']?></span>
                              </span>
                                    
                                </p>
                 <?php  } ?>

                      <?php } //if($feedback_id>0){  // feedabackgvn?>            
                            </div>
                            <p>
                               
                                <button type="button"  onclick="window.close();" name=""
                                title="Close or Go, Back" class="btn btn-info" value="207">Close</button>
                            
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


