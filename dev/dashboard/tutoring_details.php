<?php
 /***
@ tutoring details
@Intervene session and Dr.homwork session
//////////////////////
$sdate=date('F d,Y',strtotime($ses_start_time) );
                 $at_time=date('h:i a',strtotime($ses_start_time)); //'@TEST';
                 
 . 
 **/


@extract($_GET) ;
@extract($_POST) ;
$error='';
$page_name='tutoring details';


#$error='Tutoring Detail, Testing!=== ';
////////include/////////////////////
include("header.php");
include("Tutoring.inc.php"); // 16-sept-2019

 $ses_module_arr= array('homework' =>'Homework Help','intervention' =>'Intervention' );
   
   
   //////////Validate Site Access//////////
   if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
     header("Location:".$tutor_regiser_page);exit;
   }
   
     $curr_time= date("Y-m-d H:i:s"); #currTime
   $login_role = $_SESSION['login_role'];
   $page_name="Jobs Board List";
   
   // action
   if(!isset($_SESSION['ses_teacher_id'])){
       header('Location:logout.php');exit;
   }
   
   $id = $_SESSION['ses_teacher_id'];

   
   
////////Detail /////////////////
$sql="SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
//$sql.=" AND ses_start_time >= '$startTime' and ses_start_time <= '$endTime' ";
$sql.=" AND id=".$_GET['sid']; 
$Tutoring=mysql_fetch_assoc(mysql_query($sql));   


   
   
      if(!empty($Tutoring['Tutoring_client_id']) &&$Tutoring['type']=='drhomework'){
          $TutoringId=$Tutoring['id'];
          $sql="SELECT * FROM dr_tutoring_info WHERE session_ref_id=".$TutoringId;
          $class_det=mysql_fetch_assoc(mysql_query($sql)) ;//  echo $class_det['tut_status'] ;   
  
         $drhomework_class=$class_det['session_stu_data']; //STR
   
         $drhomework_arr=json_decode($drhomework_class);
   
       
   
   
   
      }
/////////////////////////////////////
      $sdate=date('F d,Y',strtotime($Tutoring['ses_start_time']));
      $at_time=date('h:i a',strtotime($Tutoring['ses_start_time'])); 
           $val1 = date("Y-m-d H:i:s"); #currTime
           $ses_status='Session expired'; 
           $in_sec= strtotime($Tutoring['ses_start_time']) - strtotime($val1);///604800 #days>+7 days
  


//////////////////////////////////////

        $sdate=date_format(date_create($ses_start_time), 'F d,Y');
                  $at_time=date_format(date_create($ses_start_time), 'h:i a');
                  $val1 = date("Y-m-d H:i:s"); 


                  
                  $ses_status='Session expired'; 
                  $in_sec= strtotime($ses_start_time) - strtotime($val1);///604800 #days>+7 days
                  $status='<span class="btn btn-danger btn-xs">Session expired</span>';  
                  
                  ?>




<style type="text/css">
   #content .ct_display {
   width: 100%;
   padding: 25px 20px;
   display: inline-block;
   }
</style>
<div id="main" class="clear fullwidth">
   <div class="container">
      <div class="row">

         <div id="sidebar" class="col-md-4">
            <?php include("sidebar.php"); ?>
         </div>

         <!-- END: sidebar -->

         


         <div id="content" class="col-md-8">
            <div id="folder_wrap" class="content_wrap">
              



               <div class="ct_heading clear">
                  <h3>Tutoring Session details</h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">
                  <?php    if ($error != '') {  echo '<p class="error">' . $error . '</p>';   }  // Inlude  message    ?>



                  <?php  if($Tutoring['Tutoring_client_id']=='Drhomework123456'){ 
                  //Drhomework123456  ?>
                  <?php include "views/drhomework_tutoring_info.php"; ?>


                  <?php  }else{ //interveneion -Default layout  ?>

                  <?php  include "views/intervene_tutoring_info.php"; ?>
                  <?php  } ?>




                 
                  <div class="clearnone">&nbsp;</div>
               </div>  
                <!-- End:ct_display clear -->

            </div>
         </div>




         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>
<!-- End: main -->


<script type="text/javascript">
   <?php 
      if ($error != '') echo "alert('{$error}')"; ?>
</script>
<?php include("footer.php"); ?>