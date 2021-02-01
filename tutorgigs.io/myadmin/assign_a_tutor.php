<?php

include("header.php");
$error =NULL;
extract($_REQUEST); 



   if(!isset($_POST['getid']))
    $error="Page not found. !";
   //////////
   
   if (isset($_GET['id'])) {
       $id = $_GET['id'];
       //if not admin but want to edit return index
       require_once('inc/check-role.php');
       $login_role = checkRole();
       if ($login_role != 0 || !isGlobalAdmin()) {
           header('Location: index.php');
           exit;
       }
   } else {
       $id = $_SESSION['login_id'];
   }
   
    $em_arr=array("int_admin"=>"rohitd448@gmail.com",
       "int_school"=>"",
       "int_teacher"=>"",
       "tut_teacher"=>"",
       "tut_admin"=>"rohit@srinfosystem.com");// all email member
  
   

    $today = date("Y-m-d H:i:s"); 
    $valid_url=true;
    function test_input($data) {
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
   }
   

   $_GET['sid']=$_POST['getid'];
     $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
    $sdata= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_POST['getid']));
   @extract($sdata);
   
    $today = date("Y-m-d H:i:s"); 
    include("braincert_api_inc.php"); ///Board uRL
   if (isset($_POST['signup_submit'])) 
   {



//print_r($_POST);
//die;
$ses_det= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_POST['getid']));

if(intval($_POST['select_teacher'])>0){

/// Generate Board URL/////////
$clss_id=$ses_det['braincert_class']; 
//echo 'braincert_class';
$tutor_id=$_POST['select_teacher'];
$board='stopped.php';
//echo '<pre>'; print_r($data_obj); die;
///////////////////////////////////
require_once('./inc/fun_assign_tut.php');// SendEmail     

$tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$_POST['select_teacher']));   

$sid=$_POST['getid']; 
 $error = 'tutTeacher Added on Slot';
#1. notify old tutor if 
$sesdate.=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');
$sestime=date_format(date_create($ses_det['ses_start_time']), 'h:i a');
$emtime=$sesdate." at-".$sestime;
$embodayxx="A Tutor assigned for your Group Tutor"
. " Session scheduled for $emtime  ,<br/>Log in to see the updated schedule.";

$emboday="You've been assigned a Group Tutor Session scheduled for $emtime. Please sign in to see more details, to access lessons, and to launch your session.";
$rs_noti[]=sendEmails($user_id,$em_to=$em_arr['int_admin'],$body=$emboday); # test

if($ses_det['tut_teacher_id']>0&&$ses_det['tut_teacher_id']!=$_POST['select_teacher']){
//Old tutor notify..
$tut_th_o=mysql_fetch_assoc(mysql_query("SELECT * FROM gig_teachers WHERE id=".$ses_det['tut_teacher_id']));
$em_arr['tut_teacher']=$tut_th_o['email']; //"tut_th@gmail.com";

$rs_noti[]=sendEmails($user_id,$em_to=$em_arr['tut_teacher'],$body=$emboday); 
}// Re-Assgined
# 2 :new
$em_arr['tut_teacher']=$tut_th['email'];
$rs_noti[]=sendEmails($user_id,$em_to=$em_arr['tut_teacher'],$body=$emboday); 
// print_r($rs_noti) ;die;
///////////////////////////
$query = mysql_query("UPDATE int_schools_x_sessions_log SET tut_teacher_id='".$_POST['select_teacher']."',"
. "braincert_board_url='".$board."',tut_accept_time='$today',"

. "modified_date='$today' WHERE id='$sid'", $link);

// in studentsLog of Slot
$up= mysql_query("UPDATE int_slots_x_student_teacher SET tut_teacher_id='".$_POST['select_teacher']."',"
. "tut_admin_id='$id' WHERE slot_id='$sid'", $link);

if($up==true){
header("location:list-tutor-sessions.php?ses=1");exit;
}
}

else {
$sid=$_POST['getid']; 
$error = 'Tutor has been unassigned';
$query = mysql_query("UPDATE int_schools_x_sessions_log SET tut_teacher_id  ='0',"
. "braincert_board_url='' , "

. "modified_date='$today' WHERE id='$sid'", $link);
$up= mysql_query("UPDATE int_slots_x_student_teacher SET tut_teacher_id=0 , "
. "tut_admin_id='$id' WHERE slot_id='$sid'", $link);

if($up==true){
header("location:list-tutor-sessions.php?ses=1");exit;
}
}
       
   }
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<div id="main" class="clear fullwidth">
   <div class="container">
      <div class="row">
         <div id="sidebar" class="col-md-4">
            <?php include("sidebar.php"); ?>
         </div>
         <!-- /#sidebar -->
         <div id="content" class="col-md-8">
            <div id="folder_wrap" class="content_wrap">
               <div class="ct_heading clear">
                  <h3>+Assign Tutor  
                  </h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">
                  <?php
           if ($error != '') {
                        echo '<p class="error">' . $error . '</p>';
                     } else { ?>
              <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                     <h4 class="title">Tutor Session Information</h4>
                     <input    type="hidden" name="getid" value="<?=$_POST['getid']?>"/> 
                     <div class="profile-center col-md-12">
                        <p>
                           <?php 
                       
                              $sql="SELECT * FROM gig_teachers WHERE all_state='yes' ";
                              $results=mysql_query($sql);
                            
                              ?>        
                           <label for="select_teacher">Choose Tutor:</label><br />
                           <select name="select_teacher" style="width: 70%" id="district">
                              <option  value="0">Unassign</option>
                              <?php while ($row= mysql_fetch_assoc($results)) { ?>
                              <option  value="<?=$row['id']; ?>"><?=$row['f_name']." ".$row['lname']?></option>
                              <?php } ?>
                           </select>
                        </p>
                        <!--      XXXXXXXXXXXXXX   dff-->
                        <?php
                        if($sdata['Tutoring_client_id']=='Drhomework123456')
                          {
                            include "views/drhomework_tutoring_info.php";
                          }
                          else{
                            include "views/intervene_tutoring_info.php";
                      } ?>
                       
                        <div class="clear">&nbsp;</div>
                        <button type="submit" id="profile-submit"
                           class="button-submit" name="signup_submit">Submit</button>
                     </div>
                  </form>
                  <?php }   ?>
                  <div class="clearnone">&nbsp;</div>
               </div>
               <!-- /.ct_display -->
            </div>
         </div>
         <!-- /#content -->
         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>
<!-- /#header -->
<?php
   //alert(Send Email To Active);
   if (isset($_GET['send']) && $_GET['send'] != '') {
       if ($_GET['send'] == 'true') {
           print('<script>alert("An activation link has been sent to the email address you\'ve provided!");</script>');
       } else {
           print('<script>alert("Activation link can not be sent. Please try again later!");</script>');
       }
   }
   ?>


<script type="text/javascript">
  

$(document).ready(function ()
{
   
$('#district').chosen();
});

</script>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<?php include("footer.php"); ?>