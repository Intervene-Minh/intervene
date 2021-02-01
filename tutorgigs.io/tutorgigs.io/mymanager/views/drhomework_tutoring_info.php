<?php
      $SessionID=$_GET['sid'];
      $sql="SELECT * FROM `dr_tutoring_info` WHERE session_ref_id='".$SessionID."'";
      $sessionRow=mysql_fetch_assoc(mysql_query($sql));

      $sqL="SELECT * FROM int_schools_x_sessions_log WHERE id=".$SessionID;
      $Tutoring=mysql_fetch_assoc(mysql_query($sqL)) ;

      $TutoringId=$Tutoring['id'];
      $sql="SELECT * FROM dr_tutoring_info WHERE session_ref_id=".$TutoringId;
      $class_det=mysql_fetch_assoc(mysql_query($sql)) ;//  echo $class_det['tut_status'] ;   

      $drhomework_class=$class_det['session_stu_data']; //STR
      $drhomework_arr=json_decode($drhomework_class);
      $sdate=date('F d,Y',strtotime($Tutoring['ses_start_time']));
      $at_time=date('h:i a',strtotime($Tutoring['ses_start_time']));
      $val1 = date("Y-m-d H:i:s"); #currTime
      $ses_status='Session expired'; 
      $in_sec= strtotime($Tutoring['ses_start_time']) - strtotime($val1);///604800 #days>+7 days
   
    
?>
<div class="col-md-12">
   <div class="profile-center col-md-12">
   <!--  Drhomework Detail -->
   <div class="box col-md-12">
      <h4 class="title text-primary">Tutoring Session Information</h4>
         <?php $seDateTime=date('F d,Y',strtotime($Tutoring['ses_start_time']));
         $seDateTime.=' '.date('h:i a',strtotime($Tutoring['ses_start_time'])); ?>

<div class="form-group col-md-12">
         <label for="email">Parent Name:</label>
        <span style="color: green;forn-weight:500"><?php echo   $drhomework_arr->parent_info ?></span></div>
      <div class="form-group col-md-12">
         <label for="email">Session Date/Time:</label>
        <?=$seDateTime?></div>

<div class="form-group col-md-12">
         <label for="email">Special Note:</label>
        <?=($Tutoring['special_notes'])?$Tutoring['special_notes']:'NA' ?>   </div>
         <div class="form-group col-md-12">
         <label for="email">Students List:</label>
         <span style="color:red;">  
         <?php
         if(isset($drhomework_arr->list_of_students)){ ?>
         Students(<?= count($drhomework_arr->list_of_students) ?>)- <?php 
            $SName=array_values($drhomework_arr->list_of_students);
            echo implode(', ', $SName);
            }
           ?>
         </span>
      </div>
      <?php  if(!empty($aasiLinkstudent) || !empty($drhomework_arr->lesson_documents_arr)){ ?>
      <div class="form-group col-md-12">
         <label for="email">Download Lesson Link </label>
         <?php 
            foreach ($drhomework_arr->lesson_documents_arr as $key_url) { ?>
            <p class="text-primary">
               
               <a href="<?= $key_url ?>" class="btn-link">Download -> <?php echo end(explode('=',$key_url));?></a>
            </p>
         <?php  }  
               $aasiLinkstudent = json_decode($sessionRow['assignment_link_student']);
               foreach ($aasiLinkstudent as $key_url)
                  { ?>
               <p class="text-primary">
                  <a href="<?= $key_url ?>" class="btn-link">Download -> <?php echo end(explode('=',$key_url));?></a>
               </p>
         <?php  }  ?>
      </div>
      <?php } 
         $aasiLink = json_decode($sessionRow['assignment_link_data']);
          if(!empty($aasiLink )){
         ?>
      <div class="form-group col-md-12">
         <label for="email">Assignment Link</label>
         <?php 
            foreach ($aasiLink as $key_url) { ?>
         <p class="text-primary"> 
   <a href="<?= $key_url ?>">Download -> <?php echo end(explode('=',$key_url))?></a>
     </p>
         <?php  }  // if ?>
      </div>
      <?php }
         $dataDoc=json_decode($sessionRow['doc_after_job_claimed']);
         if(!empty($dataDoc)){
          ?>
      <div class="form-group col-md-12">
         <label for="email">Document After Job Claimed</label>
         <?php 
            foreach ($dataDoc as $value) {
            ?>
         <p class="text-primary">
            
            <a href="<?= $value ?>" class="btn-link">Download -> <?php echo end(explode('=',$value));?></a> 
      </p>
         <?php  }?>
      </div>
   <?php } ?>
   </div>
</div>
</div>