<?php 
      include('inc/connection.php'); 
      extract($_REQUEST);

      if($action=='Drhomework'){


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
               
               <a href="<?= $key_url ?>" class="btn-link"><?php echo end(explode('=',$key_url));?> -> Download</a>
            </p>
         <?php  }  
               $aasiLinkstudent = json_decode($sessionRow['assignment_link_student']);
               foreach ($aasiLinkstudent as $key_url)
                  { ?>
               <p class="text-primary">
                  <a href="<?= $key_url ?>" class="btn-link"><?php echo end(explode('=',$key_url));?> -> Download</a>
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
         $i=1;
            foreach ($aasiLink as $key_url) { ?>
         <p class="text-primary"> 
   <a href="<?= $key_url ?>">Assignment <?php echo $i; ?> ->Download </a>
     </p>
         <?php 
$i++;

          }  // if ?>
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
   </div>
</div>
</div>
<?php  }  }
else if($action=='Intervention'){


$getid=$SessionID;//ID
$qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
$qq.=" AND id=".$getid;  
//   $qq.=" AND tut_status='STU_ASSIGNED' AND id=".$getid;  
$results=mysql_query($qq);    
if(mysql_num_rows($results)<1)
$error="Sorry, no record found. !";
$slot= mysql_fetch_assoc($results);


$int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=".$slot['teacher_id']));
$tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$slot['tut_teacher_id']));  
$int_school= mysql_fetch_assoc(mysql_query("SELECT SchoolName FROM schools WHERE SchoolId=".$slot['school_id']));
$Object= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$slot['quiz_id']));    

$stud_str=[];
$sql_students="SELECT s.*, t.slot_id
FROM int_slots_x_student_teacher t
LEFT JOIN  students s
ON t.student_id =s.id
WHERE slot_id= '$getid' ";
            
$resss=mysql_query($sql_students);
while ($row= mysql_fetch_assoc($resss)) {
   $stud_str[]=$row['first_name'];
}    

$List_of_students='List-'; // Students
$List_of_students=implode(', ', $stud_str);
//////////////////
$Grade=mysql_fetch_assoc(mysql_query(" SELECT id,name,subject_grade_id FROM terms WHERE id= '".$slot['grade_id']."' "));

/////////////////////////////
                 $sdate=date('F d,Y',strtotime($slot['ses_start_time']));
                 $at_time=date('h:i a',strtotime($slot['ses_start_time'])); //'@TEST';
                  ////////////////////Expir ses
                  $val1 = date("Y-m-d H:i:s"); #currTime
                  $ses_status='Session expired'; 
                  $in_sec= strtotime($ses_start_time) - strtotime($val1);///604800 #days>+7 days
                  $status='<span class="btn btn-danger btn-xs">Session expired</span>';  

 ///////Tutor: information ////////////////////// 
  $ses_id=$_GET['getid'];
                           
  $profile= mysql_fetch_assoc(mysql_query("SELECT id,payment_email,payment_phone FROM tutor_profiles WHERE tutorid=".$slot['tut_teacher_id']));                                               
    $ses_paypal_email=(!empty($slot['paypal_email']))?$slot['paypal_email']:$profile['payment_email']; 
     $ses_paypal_phone=(!empty($slot['paypal_phone']))?$slot['paypal_phone']:$profile['payment_phone'];

  //////////////////////
  # Tutoring : {lessons}
  # SELECT * FROM lessons WHERE `id` = '10'   
     $lesson_det=mysql_fetch_assoc(mysql_query("SELECT * FROM master_lessons WHERE id=".$slot['lesson_id']));
     $lesson_download="https://intervene.io/questions/uploads/lesson/".$lesson_det['file_name'];// 



?>    
                        <div class="box col-md-12">
                           <h4 class="title col-md-12 text-primary">Intervene Information</h4>
                           <div class="left col-md-12">
                              <label for="firstname">Session Date/Time:</label>
                            <p><?=$sdate." ".$at_time?></p></div>
                        <div class="left col-md-12">
                        <label for="firstname">School Name:</label>
                        <p><?=$int_school['SchoolName']?></p></div>

                           <div class="left col-md-12">
                           <label for="firstname">Objective:</label>
                           <?=$Object['objective_name']?>
                           </div>


                            <div class="left col-md-12">
                           <label for="firstname">Subject/Grade Level of session:</label>
                           <?= ($Grade['name'])?$Grade['name']:'Grade missing'; ?>
                           </div>

                           <div class="left col-md-12">

                                <p class="required" class=""  style="text-transform: full-width;"> 
                                 <strong>List of students(<?= count($stud_str); ?>):</strong>
                                
                                 <?php echo $List_of_students; ?>
                              </p>
                              <?php  if(!empty($slot['special_notes'])){?>
                              <br/>
                              <label for="firstname">Special Note:</label>
                              <p class="required" class=""  style="text-transform: full-width;"> 
                                 <?= $slot['special_notes']?> Test demo msgg <br/>   
                              </p>
                            <?php } ?>

                            <?php  if($slot['lesson_id']>0){?>
                           <div class="left">
                           <label for="firstname">Download Lesson:</label>
                           <a href="<?=$lesson_download?>"
                           class="btn btn-danger btn-xs">Download-<?=$lesson_det['name']?></a> 
                           </div>
                           <?php  }else{ ?>
                            <div class="left">
                           <label for="firstname">Download Lesson:</label>
                           <p> No Lesson for this "Tutoring Session". </p>
                           </div>
                           <?php  } ?>
  
                           </div>
                           <div class="form-group col-md-12"> <h4 class="title text-primary">Tutor Information</h4></div>

                            <?php  if($slot['tut_teacher_id']>0){?>
  
                           <div class="form-group col-md-12">
                              <label for="email">Name of Tutor:</label>
                          <p> <?=$tut_th['f_name']." ".$tut_th['lname']?></p>
                           </div>

                           <div class="form-group col-md-12">
                              <label for="email">PayPal Email:</label>
                           <p> <?=$ses_paypal_email?></p>
                           </div>
                           <div class="form-group col-md-12">
                              <label for="email">PayPal Phone:</label>
                             <p>  <?=$ses_paypal_phone?></p>
                           </div>

                         <?php }else{  // Job not claimed yet  ?>
                          <div class="form-group col-md-12">
                              <label for="email">Tutor status:</label>
                         <p>  waiting for acceptance</p>
                           </div>
                         <?php  }?></div>
                             <!-- END: box col-md-12 -->
                     </div>

<?php } ?>
