<?php
/**
 * @ $slot :: Tutor Sessions 
 * Sessions Date/Time


List of students
Start time 
 * @ Frm Date Pickersions Date
 *    
 * **/



include("header.php");

echo '========Intervene' ;

/////////////////

$error = '';
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

/////Get req///
if (isset($_GET['getid'])) {
    $_POST['getid']=$_GET['getid'];
}

//if (!isset($_POST['getid'])) {
//    $error="Sorry, Page not found. !";
//}


//print_r($_POST); die;
if (isset($_POST['getid'])) {
    $getid=$_POST['getid']; //ID
     
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
}




//$master_schoolid = $row['master_school_id'];
//$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
$stud_str=array();

 $q=" Select sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$_GET['getid']."' ";
            $resss=mysql_query($q);
            $stud_str=array();
            while ($row= mysql_fetch_assoc($resss)) {
                $stud_str[]=$row['first_name'];
            }      


?>


<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">


            



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
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
<?php
if ($error != '') {
    echo '<p class="error">' . $error . '</p>';
} else {
    ?>
                  <form id="form-profile"   
                        action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                <div class="profile-top col-md-12">
                                   
     <!----<div class="profile-item alert alert-info text-center">
                                            Session Detail below!</div>-->
                                    
                                   
                                    
                                    <div class="col-md-9">
                                        <div class="profile-item">
                                            <div class="left col-md-4">
                                           <label for="profile-username">Session Date/Time:</label>
                                            </div>
                                            
                                            <div class="right col-md-8">
                                                <input type="text" 
                                       
                                        class="required"  value="<?=$sdate." ".$at_time?>"
                                         style="width: 100%;" />
                                                
                                            </div>
                                        </div>
                                        
                                       <!--  <div class="profile-item">
                                            <div class="left col-md-4">
                                           <label for="profile-username">App Url:</label>
                                            </div>
                                            <div class="right col-md-8">
                                                <input type="text" 
                                       
                                        class="required"  value="<?=$app_url?>"
                                         style="width: 100%;" />
                                               
                                            </div>
                                        </div> -->

                                        
                                       <div class="profile-item">
                                           <br/>
                          <p style="font-weight:bolder;"class="left col-md-4">Total Student:<?=count($stud_str)?>,</p>
                                           <?php  //if(!empty($app_url)){
                                           $asgin_button=(!empty($app_url))?"Re-assign Tutor":"Assign Tutor";
                                           $asgin_st=(!empty($app_url))?"Session Assigned":"Session not Assigned";
                                            $asgin_class=(!empty($app_url))?"btn btn-success btn-xs":"btn btn-danger btn-xs";
                                           // Student not assgined
                                            // ASSIGNED tut_status
                                           
                                           ?>
                                            
                                           <p style="font-weight:bolder;"  
                                    class="left col-md-4">Status<? //=$$in_sec?>: 
                                               <span class="<?=$asgin_class?>"
                                                     >
                                               <?=($in_sec<-3600)?"Session expired":$asgin_st;?></span>
                                               
                                         <?php  //if($tut_status=="ASSIGNED"){
                                             
                                             ?>
<!--                                               <span class="btn btn-default btn-xs" 
                                                     style="background-color: yellow;border-color: yellow;"
                                                     >Student not Assigned</span> -->
                                                            
                                                 <?php // }?>
                                             
                                            
                                           
                                           </p>
                                           
                                         <?php if($in_sec>-3600){
                                              // $ses_status='Session Assigned'; 
                                                ?>
                                           ,
                                        <a href="javascript:void(0);"  class="text-danger"
                                   onclick="sent_form('assign_a_tutor.php', {getid:'<?=$getid?>',productname:'101',detail:'this is a text.'});"
                                          style="text-decoration:underline;"><?=$asgin_button?></a><?php }?>    

                                           <?php // }  ?>
                                               
                                               
                                               
                                           
                                            
                                          
                                        </div>  
                                        
                                        
                                        
                                        
                                       
                                        
                                    </div>
                                    
                                    
                                    
   
                                </div>
                      
                      
                      
                      
                      
                      
                      
                      
                                <div class="profile-center col-md-12">
                                    <h4 class="title text-primary">Intervene Information</h4>
                                    <div class="box col-md-12">
                                
                                      <div class="left col-md-6">
                                            <label for="firstname">School Name:</label>
                                  <input  class="required" 
                                          value="<?=$int_school['SchoolName']?>" type="text">
                                            
                                        </div>  
                                        
                                        
                                   
                                        
                                        
                                        <div class="left col-md-6">
                                            <label for="email">Teaher:</label>
                                             <input  class="required" 
                                          value="<?=$int_th['first_name']?>" type="text">
                                        </div>
                                      
                              <div class="left col-md-12">  
                                    <br/>
                                            <label for="firstname">Special Note(Teacher):</label>
                                            <p class="required" class=""  style="text-transform: full-width;"> 
                                            <?=(!empty($special_notes))?$special_notes:"NA"; ?> <br/>   
                                           
                                            </p>
                        <p class="required" class=""  style="text-transform: full-width;"> 
                            <strong>List of students:</strong>
                             <?php  if($tut_status=="ASSIGNED"){
                                             
                                             ?>
                                  <span class="btn btn-default btn-xs" 
                                                     style="background-color: yellow;border-color: yellow;"
                                                     >Student not Assigned</span>
                                                            
                                                 <?php  }else{?>
                                          <?php
                                          
                                          if(is_array($stud_str))
                                          echo implode(",",$stud_str);
                                                 } ?>
                                           
                                            </p>
                                                     
                                            
                                            
                                     
                                            
                                        </div>  
                                                   
                                      
                                 
                         
                                    </div>
                                    
                     <!--         Tutor -->
                     <?php 

                     $ses_id=$_GET['getid'];
                   //echo '<pre>';  print_r($slot);
             $profile= mysql_fetch_assoc(mysql_query("SELECT id,payment_email,payment_phone FROM tutor_profiles WHERE tutorid=".$slot['tut_teacher_id']));
           

            $ses_paypal_email=(!empty($slot['paypal_email']))?$slot['paypal_email']:$profile['payment_email']; 

            $ses_paypal_phone=(!empty($slot['paypal_phone']))?$slot['paypal_phone']:$profile['payment_phone']; 



                     ?>


                                      <div class="box col-md-12">
                                          <br/>
                                    <h4 class="title text-primary">Tutor Information</h4>    
                                    <div class="form-group col-md-12">
                                            <label for="email">Name of Tutor:</label>
                                            <input  class="required" 
                                          value="<?=$tut_th['f_name']." ".$tut_th['lname']?>" >
                                                  
                                            
                                        </div> 

                                          <div class="form-group col-md-12">
                                            <label for="email">PayPal Email:</label>
                                            <input  class="required" 
                                          value="<?=$ses_paypal_email?>" >
                                                  
                                            
                                        </div> 

                                          <!-- Phone -->
                                          <div class="form-group col-md-12">
                                            <label for="email">PayPal Phone:</label>
                                            <input  class="required" 
                                          value="<?=$ses_paypal_phone?>" >
                                                  
                                            
                                        </div> 


                                         </div>
                                    
                                      
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    <div class="clear">&nbsp;</div>
                                <div class="text-center"><a  class="btn btn-success btn-md" onclick="alert('Go, Back ');
                                        location.href='./list-tutor-sessions.php';" 
                                        href="javascript:void(0);"  >Back, Home</a></div>
                                    
                                    
                                     
                                </div>
                            </form>
    <?php
}
?>

                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
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


