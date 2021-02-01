<?php

$getid= $_GET['sid'];

$qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
$qq.=" AND id=".$getid;  
//   $qq.=" AND tut_status='STU_ASSIGNED' AND id=".$getid;  
$results=mysql_query($qq);    
if(mysql_num_rows($results)<1)
$error="Sorry, no record found. !";
$slot= mysql_fetch_assoc($results);
@extract($slot);

$int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=".$slot['teacher_id']));
$tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$slot['tut_teacher_id']));  
$int_school= mysql_fetch_assoc(mysql_query("SELECT SchoolName FROM schools WHERE SchoolId=".$slot['school_id']));
$Object= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$slot['quiz_id']));    
$lesson_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `master_lessons` WHERE id=".$slot['lesson_id']));
$lesson_download="https://intervene.io/questions/uploads/lesson/".$lesson_det['file_name'];// 

$stud_str=[];
   
$q="SELECT sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id WHERE ses.slot_id='".$getid."'";
              
$resss=mysql_query($q);

while ($row= mysql_fetch_assoc($resss)) {

   $stud_str[]=$row['first_name'];

}    


   

                  $sdate=date_format(date_create($ses_start_time), 'F d,Y');
                  $at_time=date_format(date_create($ses_start_time), 'h:i a');
                  ////////////////////Expir ses
                  
                  $val1 = date("Y-m-d H:i:s"); #currTime
                  $ses_status='Session expired'; 
                  $in_sec= strtotime($ses_start_time) - strtotime($val1);///604800 #days>+7 days
                  $status='<span class="btn btn-danger btn-xs">Session expired</span>';  
                  
                  ?>
              
                 
                     <div class="col-md-12">
                        <h4 class="title text-primary">Intervene Information</h4>
                        <div class="box col-md-12">
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
                              <br/>
                              <label for="firstname">Special Note:</label>
                              <p class="required" class=""  style="text-transform: full-width;"> 
                                 <?= $slot['special_notes']?> <br/>   
                              </p>
                              <p class="required" class=""  style="text-transform: full-width;"> 
                                 <strong>List of students:</strong>
                                
                                 <?php echo  implode(",",$stud_str);?>
                              </p>
                           </div>
                        <?php 
                           $ses_id=$_GET['getid'];
                           //echo '<pre>';  print_r($slot);
                           $profile= mysql_fetch_assoc(mysql_query("SELECT id,payment_email,payment_phone FROM tutor_profiles WHERE tutorid=".$slot['tut_teacher_id']));
                           
                           
                           $ses_paypal_email=(!empty($slot['paypal_email']))?$slot['paypal_email']:$profile['payment_email']; 
                           
                           $ses_paypal_phone=(!empty($slot['paypal_phone']))?$slot['paypal_phone']:$profile['payment_phone']; 
                           ?>
                      
                            <div class="form-group col-md-12"> <h4 class="title text-primary">Tutor Information</h4></div>
                           <div class="form-group col-md-12">
                              <label for="email">Name of Tutor:</label>
                          <p> <?=$tut_th['f_name']." ".$tut_th['lname']?></p>
                           </div>
                           <div class="form-group col-md-12">
                              <label for="email">PayPal Email:</label>
                           <p> <?=$ses_paypal_email?></p>
                           </div>
                           <!-- Phone -->
                           <div class="form-group col-md-12">
                              <label for="email">PayPal Phone:</label>
                         <p>  <?=$ses_paypal_phone?></p>
                           </div>


                           <div class="left col-md-12">
                           <a href="<?=$lesson_download?>"
                           class="btn btn-danger btn-xs">Download-<?=$lesson_det['name']?></a> 
                           </div>
                     
                    </div>

                     </div>
          