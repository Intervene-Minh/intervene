<form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
   <?php 
      //echo '<pre>';
      // print_r($drhomework_arr);
      
       // print_r($Tutoring); 
      




$sql="SELECT * FROM `dr_tutoring_info` WHERE session_ref_id='".$_GET['sid']."'";
$sessionRow=mysql_fetch_assoc(mysql_query($sql)) ;    

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
      <div class="clear">&nbsp;</div>
      <div class="text-center"><a class="btn btn-success btn-md" onclick="alert('Go, Back ');
         location.href='Jobs-Board-List.php';" href="javascript:void(0);">Back, Home</a></div>
      <!-- "profile-center col-md-12 -->
   </div>
</form>