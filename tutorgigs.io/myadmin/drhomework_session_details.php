<?php
  include("header.php");
?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<!-- JS -->
<style type="text/css">
  

  #content .ct_display {
    padding: 25px 20px;
    display: inline-block;
}
</style>
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
               <?php
                  $sdate=date_format(date_create($ses_start_time), 'F d,Y');
                  $at_time=date_format(date_create($ses_start_time), 'h:i a');
                  ////////////////////Expir ses
                  
                  $val1 = date("Y-m-d H:i:s"); #currTime
                  $ses_status='Session expired'; 
                  $in_sec= strtotime($ses_start_time) - strtotime($val1);///604800 #days>+7 days
                  $status='<span class="btn btn-danger btn-xs">Session expired</span>';  
                  
                  ?>
               <div class="ct_heading clear">
                  <h3>Tutor Session-<?=$sdate?> at-<?=$at_time?></h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">

<form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
   <?php 


////////Detail /////////////////
$sql="SELECT * FROM int_schools_x_sessions_log WHERE id=".$_GET['getid'];
$Tutoring=mysql_fetch_assoc(mysql_query($sql));    
$TutoringId=$Tutoring['id'];
      
$sql="SELECT * FROM `dr_tutoring_info` WHERE session_ref_id=".$TutoringId;
$sessionRow=mysql_fetch_assoc(mysql_query($sql)) ;  

$drhomework_class=$sessionRow['session_stu_data']; //STR

$drhomework_arr=json_decode($drhomework_class);  
//print_r($sessionRow);
      ?>
   <div class="profile-top col-md-12">
      <!-- <div class="profile-item alert alert-info text-center">
         Session from -drhomework.com!</div> -->
      <div class="col-md-9">
         <div class="profile-item">
            <div class="left col-md-4">
               <label for="profile-username text-primary">Session Date/Time:</label>
            </div>
<?php  
//$at_time=date('h:i a',strtotime($Tutoring['ses_start_time']));  
//date_format(date_create($ses_start_time), 'h:i a');

$seDateTime=date('F d,Y',strtotime($Tutoring['ses_start_time']) );
$seDateTime.=' '.date('h:i a',strtotime($Tutoring['ses_start_time']) );

?>
            <div class="right col-md-8">
               <input type="text" class="required" 
                  value="<?=$seDateTime?>" style="width: 100%;">
               <!-- September 09,2019 08:15 pm -->
            </div>
         </div>
      </div>
   </div>
   <!-- 2ndRow -->
   <div class="profile-center col-md-12">
      <!--  Drhomework Detail -->
      <div class="box col-md-12">
         <br>
         <h4 class="title text-primary">Tutoring Session Information</h4>


         <div class="form-group col-md-12">
         <label for="email">Parent Name:</label>
        <span style="color: green;forn-weight:500"><?php echo   $drhomework_arr->parent_info ?></span></div>
         <div class="form-group col-md-12">
            <label for="email">Special Note:</label>
            <input class="required" value="<?=($Tutoring['special_notes'])?$Tutoring['special_notes']:'NA' ?>">
         </div>
         <div class="form-group col-md-12">
            <label for="email">Students List:</label>
            <span style="color:red;">  
            <?php if(isset($drhomework_arr->list_of_students)){ ?>
            Students(<?= count($drhomework_arr->list_of_students) ?>)- <?php 
               $SName=array_values($drhomework_arr->list_of_students);
               echo implode(', ', $SName);
               }
               
               
               ?>
            </span>
            <!-- <input class="required" value="Help text form DrHomework for this session! "> -->
         </div>
         <?php // lesson_documents_arr  ?>


         <?php  if(!empty($aasiLinkstudent) || !empty($drhomework_arr->lesson_documents_arr)){ ?>
         <div class="form-group col-md-12">
            <label for="email">Download Lesson Link </label>
            <?php 
               foreach ($drhomework_arr->lesson_documents_arr as $key_url) {
               
               ?>
            <p class="text-primary"> <a href="<?= $key_url ?>" > Download-<?= $key_url ?> </a> </p>
            <?php  }  


              $aasiLinkstudent = json_decode($sessionRow['assignment_link_student']);
               foreach ($aasiLinkstudent as $key_url) {
               
               ?>
            <p class="text-primary"> <a href="<?= $key_url ?>" >Uplaod By Student-&nbsp; <?= $key_url ?> </a> </p>
            <?php  }  // if ?>
         </div>
         <?php } 

         $aasiLink = json_decode($sessionRow['assignment_link_data']);
          if(!empty($aasiLink )){
       ?>
         <div class="form-group col-md-12">
            <label for="email">Assignment Link</label>
            <?php 
              
               foreach ($aasiLink as $key_url) {
               
               ?>
            <p class="text-primary"> <a href="<?= $key_url ?>" >Assigment-&nbsp; <?= $key_url ?> </a> </p>
            <?php  }  // if ?>
         </div>
  <?php }
  $dataDoc=json_decode($sessionRow['doc_after_job_claimed']);
  if(!empty($dataDoc)){
   ?><div class="form-group col-md-12">
            <label for="email">Document After Job Claimed</label>
            <?php 
              
               foreach ($dataDoc as $value) {
               
               ?>
            <p class="text-primary"> <a href="<?= $value ?>" ><?= $value ?> </a> </p>
            <?php  }  // if ?>
         </div>
<?php  } ?>
      <!--         Tutor -->

   </div>
</form>
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

<?php include("footer.php"); ?>