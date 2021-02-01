<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * ***/
@extract($_REQUEST);
$sub=array(
"Elementary Math","Elementary ELA","Middle School / Junior High School - Math","Middle School / Junior High School - ELA","High School Math","High School ELA","Fluent in Spanish",

"Early Reading Phonics / English Language Learning");
include("header.php");
include('newrow.functions.php');

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

/// 'profile-submit'
/*
 * firstname
lastname
email
phone
 * 
 * **/

 $today = date("Y-m-d H:i:s"); 
 $valid_url=true;
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
 
 $Tutor=mysql_fetch_assoc(mysql_query(" SELECT * FROM  gig_teachers WHERE id = '$tid' "));
if(isset($del)){

    mysql_query("DELETE FROM  tutor_docs WHERE ID = '$del' ");
    header("location:edit-admintutor.php?tid=".$Tutor['id']);
}


   $data2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$tid));
   
   $edit = unserialize($data2['profile_1']);
   
  

//print_r($Tutor);



if (isset($_POST['signup_submit'])) {
    
    

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    $phone = $_POST['phone'];
    $home_address = $_POST['home_address'];
    $SpecialtySubjects = $_POST['SpecialtySubjects'];

    /*upload documents*/

       // File upload configuration
    $targetDir = "uploads/tutorDoc/";
    $allowTypes = array('pdf','jpeg','jpg','png','zip');
    $arrIMG=[];
    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
    if(!empty(array_filter($_FILES['files']['name']))){
        foreach($_FILES['files']['name'] as $key=>$val){
            // File upload path
            $fileName = basename($_FILES['files']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;
            
            // Check whether file type is valid
            $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
            if(in_array($fileType, $allowTypes)){
                // Upload file to server
                if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){
                    // Image db insert sql
                   $arrIMG[] = $fileName;
                }else{
                    $errorUpload .= $_FILES['files']['name'][$key].', ';
                };
            }else{
                $errorUploadType .= $_FILES['files']['name'][$key].', ';
            }
        }
        
        if(!empty($arrIMG)){
           
foreach ($arrIMG as  $value) {

$str="INSERT INTO `tutor_docs` SET `tutor_doc`='".$value."',`TutorID`='".$Tutor['id']."'";

$data = mysql_query($str);
}

            
        }
    }else{
        $statusMsg = 'Please select a file to upload.';
    }
    /*upload documents end*/
    $str="UPDATE  gig_teachers SET
   `f_name`='".$firstname."',
  `lname`='".$lastname."',
  `home_address`='".$home_address."',
  `email`='".$email."',
  `phone`='".$phone."',
  `SpecialtySubjects`='".implode($SpecialtySubjects,',')."' WHERE id='".$TutID."'
    ";
mysql_query($str);


     $is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$Tutor['id']);
     $num=mysql_num_rows($is_record);
     $profile = mysql_fetch_assoc($is_record);
     
     if(!empty($_FILES["photo_id"]["name"])){
       // if image exist unlink old image
       if($profile['photo_id']){
          unlink("../tutor-images/".$profile['photo_id']);
   
       }
   
       $name=$_FILES["photo_id"]["name"];
     $image_goes=rand(1001,1111).$name;
   
     $target_file="../tutor-images/".$image_goes;
     if (move_uploaded_file($_FILES["photo_id"]["tmp_name"], $target_file)) {
       $msg= 'uploaded success';
     }else{  
       $msg= 'not uploaded';
         $image_goes=null;
   
            }
          // 'file selected';
      }elseif($num==1){ // previous added if any 
          $image_goes=$profile['photo_id'];  // OLD Name
   
      }else{  
           $image_goes=null; // echo 'Not selected';
      
      }
     
      //  teacher certificate upload
       if(!empty($_FILES["teacher_certificate"]["name"])){
       // if image exist unlink old image
       if($profile['teacher_certificate'])
        unlink("../tutor_certificate/".$profile['teacher_certificate']);
   
       $name = $_FILES["teacher_certificate"]["name"];
       $teacher_certificate_file = time().rand(1001,1111).$name;
   
       $target_file = "../teacher_certificate/".$teacher_certificate_file;
       if (move_uploaded_file($_FILES["teacher_certificate"]["tmp_name"], $target_file)) {
          $msg= 'uploaded success';
       } else {  
          $msg = 'not uploaded';
          $teacher_certificate_file = null;
   
      }
         
      }
      elseif($num == 1){ // previous added if any 
         $teacher_certificate_file = $profile['teacher_certificate'];  // OLD Name
   
      }
      else{  
           $teacher_certificate_file = null; 
      
      }
      
      
      //  ESL certificate upload
       if(!empty($_FILES["esl_certificate"]["name"])){
       // if image exist unlink old image
       if($profile['esl_certificate'])
       {
          unlink("../tutor_certificate/".$profile['esl_certificate']);
   
       }
   
       $name = $_FILES["esl_certificate"]["name"];
       $esl_certificate_file = time().rand(1001,1111).$name;
   
       $target_file = "../teacher_certificate/".$esl_certificate_file;
       if (move_uploaded_file($_FILES["esl_certificate"]["tmp_name"], $target_file)) {
          $msg= 'uploaded success';
       } else {  
          $msg = 'not uploaded';
          $esl_certificate_file = null;
   
      }
         
      }
      elseif($num == 1){ // previous added if any 
         $esl_certificate_file = $profile['esl_certificate'];  // OLD Name
   
      }
      else{  
           $esl_certificate_file = null; 
      
      }
      
      
      //  Billingual certificate upload
       if(!empty($_FILES["billingual_certificate"]["name"])){
       // if image exist unlink old image
       if($profile['billingual_certificate'])
       {
          unlink("../tutor_certificate/".$profile['billingual_certificate']);
   
       }
   
       $name = $_FILES["billingual_certificate"]["name"];
       $billingual_certificate_file = time().rand(1001,1111).$name;
   
       $target_file = "../teacher_certificate/".$billingual_certificate_file;
       if (move_uploaded_file($_FILES["billingual_certificate"]["tmp_name"], $target_file)) {
          $msg= 'uploaded success';
       } else {  
          $msg = 'not uploaded';
          $billingual_certificate_file = null;
   
      }
         
      }
      elseif($num == 1){ // previous added if any 
         $billingual_certificate_file = $profile['billingual_certificate'];  // OLD Name
   
      }
      else{  
           $billingual_certificate_file = null; 
      
      }
   
   
      $grade_str=implode(',', $_POST['grade_levels']);
      
       $subject_other=$_POST['subjects_other']; // if any 
       if(isset($_POST['subjects_other'])){
        $subject_str='other'; // no selected subject
       }else{
         $subject_str=implode(',', $_POST['subjects']);
   
       }
   
     $signup_state_arr= array('step_1' => 1,
     'is_computer' =>$_POST['is_computer'],
     'started_date' => $_POST['started_date'],
     //'f2_q_1' => $_POST['f2_q_1'],
     //'f2_q_2' => $_POST['f2_q_2'],
     'f3_q_1' => $_POST['f3_q_1'], //Form 3 f3_q_3
     'f3_q_2' => $_POST['f3_q_2'],
     'f3_q_3' => $_POST['f3_q_3'], //Radio opion333
     'f3_q_4' => $_POST['f3_q_4'],
     'f3_q_5' => $_POST['f3_q_5'],
     'f3_q_6' => $_POST['f3_q_6'],
     'f3_q_7' => $_POST['f3_q_7'],
     'f3_q_8' => $_POST['f3_q_8'],
     'f3_q_9' => $_POST['f3_q_9'], 
     'f3_q_10' => $_POST['f3_q_10'],
     'f3_q_11' => $_POST['f3_q_11'],
   
   
   
   
     'hear' =>$_POST['hear']);
   
     $state_str = serialize($signup_state_arr);

     if($num==0){
      
         $sql=" INSERT INTO tutor_profiles SET tutorid='".$Tutor['id']."',block= '".$_POST['block']."',profile_1= '$state_str',info='Later',photo_id='$image_goes',grade_levels='$grade_str',subjects='$subject_str',subjects_other='$text_info_other',teacher_certificate = '$teacher_certificate_file',esl_certificate = '$esl_certificate_file',billingual_certificate = '$billingual_certificate_file'";
   
   
     
     }else{
       
       $sql=" UPDATE tutor_profiles SET profile_1= '$state_str',info='Later',grade_levels='$grade_str',subjects='$subject_str',photo_id='$image_goes',teacher_certificate = '$teacher_certificate_file',esl_certificate = '$esl_certificate_file',billingual_certificate = '$billingual_certificate_file',block= '".$_POST['block']."' WHERE tutorid=".$Tutor['id'];
     }



mysql_query($sql);







header("location:edit-admintutor.php?tid=".$Tutor['id']);
}
?>
<style>
.multiselect-container input {
    height: 11px;
}
.multiselect {
    overflow: hidden;
}

input[type="radio"]{ margin-top:-7px !important; }
input[type="checkbox"]{ margin-top:-7px !important; }
</style>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
<?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="folder_wrap" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Edit Tutor  
                        </h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                <input type="hidden" name="TutID" 
                                 value="<?php echo $Tutor['id']?>">

                                <div style="margin-top:0px !important;" class="profile-center col-md-12">
                                    <h4 class="title">Personal Information</h4>
                                    <div class="box col-md-12">
                                        
                                        <div class="left col-md-6">
                                            <label for="firstname">First Name:</label>
                                            <input type="text" id="firstname" 
                                                   required="" class="required" name="firstname" value="<?php echo $Tutor['f_name']?>"/>
                                            <div class="notif">*First Name</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="lastname">Last Name:</label>
                                            <input type="text" id="lastname" 
                                                   class="required" name="lastname" value="<?php echo $Tutor['lname']?>"/>
                                            <div class="notif">*Last Name</div>
                                        </div>
                                        <div class="left col-md-6">
                                            <label for="email">Email:</label>
                                        <input type="email" id="email"
                                       name="email" class="required" value="<?PHP echo $Tutor['email'];?>"/>
                                            <div class="notif">*Enter Valid Email</div>
                                        </div>
                                    <div class="right col-md-6">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="required"  required=""
                                    id="phone" name="phone" value="<?php echo $Tutor['phone']?>" maxlength="11"/>
                                    <div class="notif">*Please enter your Telephone Number(Home,Cell)</div>
                                    </div>
                                        
                                       
                                       <div class="left col-md-6">
                                    <label for="phone">Do you have a computer or tablet and reliable internet access?:</label>
                                    <div class="radio">
  <label>
    <input type="radio" <?=($edit['is_computer']=='yes')?'checked':null?> name="is_computer" id="radio-r" value="yes"  >
    Yes
  </label>
</div>
<div class="radio">
  <label>
    <input type="radio" <?=($edit['is_computer']=='no'||!isset($edit['is_computer']))?'checked':null?> name="is_computer"   value="no" id="radio-r" required>
    No
  </label>
</div> </div>
                                        
                                        <div class="right col-md-6">
                                    <label for="phone">How did you hear about us?:</label>
                                    <input type="text" name="hear"
                                       value="<?=$edit['hear']?>" 
                                       class="form-control" >
                                        </div>
                              
                                        <div class="right col-md-6" style="margin-top:12px">
                                    <label for="phone">When would you like to get started Tutoring?:</label>
                                    <div class="form-row">
                                 <div class="form-holder form-holder-2">
                                    <input type="text" value="<?=$edit['started_date']?>"  oninput="this.className = ''"
                                       name="started_date"  placeholder="mm/dd/yyyy" class="form-control" >
                                 </div>
                              </div>
                                        </div>

                                       <!-- <div class="clearnone">&nbsp;</div>
                                        <div class="left col-md-6">
                                            <label for="password">Password:</label>
                                            <input type="password" required="" id="password" name="password"/>
                                            <div  class="notif">*Enter password</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="repassword">Re-Password:</label>
                                            <input type="password" required="" id="repassword" name="repassword"/>
                                            <div class="notif">"Please confirm your new password!"</div>
                                        </div>-->
                                        
                                      <div class="right col-md-6" style="margin-top:12px">
                                            <label for="phone">Address:</label>
                                            
                                            <textarea  style="border: 1px solid #dddddd;float: left;width: 100%;line-height: 40px;padding: 0 10px;" id="home_address" name="home_address"><?php echo $Tutor['home_address']?></textarea>
                                            <div class="notif">*Please enter your Address</div>
                                        </div> 
                                        
                                    <div class="right col-md-6" style="margin-top:12px">
                                    <label for="sub">Specialty/Subjects *:</label>
                                    <select id="dates-field2" class="multiselect-ui form-control" multiple="multiple" name="SpecialtySubjects[]">
                                        <?php 


                                    $subb=explode(',', $Tutor['SpecialtySubjects']);
                                    foreach ($sub as  $value): ?>

                                    <option value="<?php echo $value?>" <?php if (in_array($value, $subb)){echo "selected";}?> ><?php echo $value?></option>
                                    <?php endforeach ?>
                       
                                    </select>
                                    </div>
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">Why do you want to Tutor?:</label>
                                            
                                            <input type="text" name="f3_q_1" oninput="this.className = ''" value="<?=$edit['f3_q_1']?>"  class="form-control">
                                        </div> 
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">What makes a good Tutor?:</label>
                                            
                                            <input type="text" name="f3_q_2" value="<?=$edit['f3_q_2']?>" class="form-control">
                                        </div> 
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">Have you ever tutored before?:</label>
                                            <div class="radio">
                                            <label>
                                              <input type="radio" <?=($edit['f3_q_3']=='yes'||!isset($edit['f3_q_3']))?'checked':null?> name="f3_q_3" id="radio-r" value="yes"   >
                                              Yes
                                            </label>
                                          </div>
                                          <div class="radio">
                                            <label>
                                             <input type="radio" <?=($edit['f3_q_3']=='no')?'checked':null?> name="f3_q_3"  value="no" id="radio-r" >
                                              No
                                            </label>
                                          </div>
                                            
                                            
                                        </div>
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">Have you ever Tutored online?:</label>
                                            <div class="radio">
                                            <label>
                                              <input type="radio" <?=($edit['f3_q_4']=='yes'||!isset($edit['f3_q_4']))?'checked':null?> name="f3_q_4" id="radio-r" value="yes"  >&nbsp; Yes
                                             
                                            </label>
                                          </div>
                                          <div class="radio">
                                            <label>
                                             <input type="radio"   <?=($edit['f3_q_4']=='no')?'checked':null?> name="f3_q_4"  value="no" id="radio-r" >&nbsp; No
                                            </label>
                                          </div>
                                            
                                            
                                        </div>
                                        <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">If you have tutored, where and what did you tutor?</label>
                                            
                                            <input type="text" name="f3_q_5" oninput="this.className = ''" value="<?=$edit['f3_q_5']?>" class="form-control">
                                        </div> 
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">How many years have you Tutored?:</label>
                                            <div class="radio">
                                            <label>
                                              <input type="radio"
                                       <?=($edit['f3_q_6']=='opn_1'||!isset($edit['f3_q_6']))?'checked':null?>
                                       name="f3_q_6" id="radio-r" value="opn_1"  >&nbsp; Less than a year
                                             
                                            </label>
                                          </div>
                                          <div class="radio">
                                            <label>
                                             <input type="radio"
                                       <?=($edit['f3_q_6']=='opn_2')?'checked':null?>
                                       name="f3_q_6" id="radio-r" value="opn_2" >&nbsp; 1-3 years
                                            </label>
                                          </div>
                                            
                                            <div class="radio">
                                            <label>
                                             <input type="radio"
                                       <?=($edit['f3_q_6']=='opn_3')?'checked':null?>
                                       name="f3_q_6" id="radio-r" value="opn_3"  >&nbsp; 3-5 years
                                            </label>
                                          </div>
                                            <div class="radio">
                                            <label>
                                             <input type="radio"
                                       <?=($edit['f3_q_6']=='opn_4')?'checked':null?>
                                       name="f3_q_6" id="radio-r" value="opn_4"   >&nbsp; More than 5 years
                                            </label>
                                          </div>
                                            
                                        </div>
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">What grade levels do you want to Tutor?:</label>
                                            <?php 
                                 // Edit array
                                 $save_grades_arr=explode(',',$data2['grade_levels']);
                                 
                                 $grade_arr=array('grade_1'=>'Elementary School (3-5)',
                                 'grade_2'=>'Middle School (6-8)',
                                 'grade_3'=>'High School (9-12)'
                                 
                                           );
                                 
                                 ?>
                                            <?php 
                                       foreach ($grade_arr as $key => $value) {
                                        # code...
                                        if(is_array($save_grades_arr))
                                       $checked=(in_array($key,$save_grades_arr))?"checked":null; // checked
                                       else $checked=null;
                                       ?>
                                            <div class="checkbox">
                                            <label>
                                              <input type="checkbox" <?=$checked?>    name="grade_levels[]" 
                                       id="checkbox-r" value="<?=$key?>" >&nbsp; <?=$value?>
                                             
                                            </label>
                                          </div>
                                            
                                             <?php }?>
                                           
                                            
                                            
                                        </div>
                                       
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">What subjects can you Tutor?:</label>
                                            <?php 
                                    // Edit array
                                    $save_subj_arr=explode(',',$data2['subjects']);
                                    
                                    $subj_arr=array('math'=>'Math',
                                    'english'=>'English Comprehension & Reading',
                                    'esl'=>'ESL',
                                    'languages'=>'Languages'
                                              );
                                    
                                    ?>
                                            <?php 
                                       foreach ($subj_arr as $key => $value) {
                                        # code...
                                        if(is_array($save_subj_arr))
                                       $sel=(in_array($key,$save_subj_arr))?"checked":null; // checked
                                       else $sel=null;
                                       ?>
                                            <div class="checkbox">
                                            <label>
                                              <input type="checkbox" <?=$sel?>  name="subjects[]" id="radio-r"
                                       value="<?=$key?>" >&nbsp; <?=$value?>
                                             
                                            </label>
                                          </div>
                                            
                                             <?php }?>
                                           
                                            
                                            
                                        </div>
                                       
                                        <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">What languages do you speak aside from English?:</label>
              
                                 
                                    <input type="text" name="f3_q_7"  
                                       value="<?=$edit['f3_q_7']?>" class="form-control">
                                    
                              </div>
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">Are you a certified Teacher?:</label>
                                            <div class="radio">
                                            <label>
                                              <input type="radio" onclick="$('#teacher_certificate').show();"
                                       <?=($edit['f3_q_8']=='yes'||!isset($edit['f3_q_8']))?'checked':null?>
                                       name="f3_q_8" id="radio-r" value="yes"    >&nbsp; Yes
                                             
                                            </label>
                                          </div>
                                          <div class="radio" style="margin-top:12px">
                                            <label>
                                             <input type="radio" onclick="$('#teacher_certificate').hide();"
                                       <?=($edit['f3_q_8']=='no')?'checked':null?>
                                       name="f3_q_8" id="radio-r" value="no"    >&nbsp; No
                                            </label>
                                          </div>
                                            
                                            <div id="teacher_certificate" style="margin-top:12px" >
                                   <div class="col-md-12">
                                        Uplaod certification
                                            <?php
                                                                if($data2['teacher_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $data2['teacher_certificate'];?>" target="_blank"><?php echo $data2['teacher_certificate'];?></a></p>
                                                                <?php } ?>
                                           <input style="font-size:13px !important;padding:0px" type="file" name="teacher_certificate">
                                  </div>
                                        </div>
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">Are you a certified in ESL?:</label>
                                            <div class="radio">
                                            <label>
                                              <input type="radio" onclick="$('#esl_certificate').show();"
                                       <?=($edit['f3_q_10']=='yes'||!isset($edit['f3_q_10']))?'checked':null?>
                                       name="f3_q_10" id="radio-r" value="yes"    >&nbsp; Yes
                                             
                                            </label>
                                          </div>
                                          <div class="radio">
                                            <label>
                                             <input type="radio" onclick="$('#esl_certificate').hide();"
                                       <?=($edit['f3_q_10']=='no')?'checked':null?>
                                       name="f3_q_10" id="radio-r" value="no"    >&nbsp; No
                                            </label>
                                          </div>
                                            
                                            <div id="teacher_certificate"  >
                                   <div class="col-md-12">  Uplaod certification
                                       <?php
                                                                if($data2['esl_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $data2['esl_certificate'];?>" target="_blank"><?php echo $data2['esl_certificate'];?></a></p>
                                                                <?php } ?>
                                       <input style="font-size:13px !important;padding:0px" type="file" name="esl_certificate">
                                  </div>
                                        </div>
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">Are you billingual?:</label>
                                            <div class="radio">
                                            <label>
                                              <input type="radio" onclick="$('#billingual_certificate').show();"
                                       <?=($edit['f3_q_11']=='yes'||!isset($edit['f3_q_11']))?'checked':null?>
                                       name="f3_q_11" id="radio-r" value="yes"    >&nbsp; Yes
                                             
                                            </label>
                                          </div>
                                          <div class="radio">
                                            <label>
                                             <input type="radio" onclick="$('#billingual_certificate').hide();"
                                       <?=($edit['f3_q_11']=='no')?'checked':null?>
                                       name="f3_q_11" id="radio-r" value="no"    >&nbsp; No
                                            </label>
                                          </div>
                                            
                                            <div id="teacher_certificate"  >
                                   <div class="col-md-12"> Uplaod certification
                                       <?php
                                                                if($data2['billingual_certificate']) { ?>
                                                                <p> <a href="../teacher_certificate/<?php echo $data2['billingual_certificate'];?>" target="_blank"><?php echo $data2['billingual_certificate'];?></a></p>
                                                                <?php } ?>
                                       <input style="font-size:13px !important;padding:0px" type="file" name="billingual_certificate">
                                  </div>
                                        </div>
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">How familiar are you with TEKS & STAAR?:</label>
                                            <?php 
                                 $familiar=array('opn_1'=>'Not familiar',
                                   'opn_2'=>'I have heard of it, but not very familiar',
                                   'opn_3'=>'Somewhat familiar',
                                   'opn_4'=>'Very familar',
                                             'opn_5'=>'I am a specialist');
                                 
                                 ?>
                                            <div class="radio">
                                            <label>
                                              <input type="radio"
                                       <?=($edit['f3_q_9']=='opn_1'||!isset($edit['f3_q_9']))?'checked':null?>
                                       name="f3_q_9" id="radio-r" value="opn_1" >&nbsp; Not familiar
                                             
                                            </label>
                                          </div>
                                          <div class="radio">
                                            <label>
                                             <input type="radio"
                                       <?=($edit['f3_q_9']=='opn_2')?'checked':null?>
                                       name="f3_q_9" id="radio-r" value="opn_2" >&nbsp; I have heard of it, but not very familiar
                                            </label>
                                          </div>
                                            
                                            <div class="radio">
                                            <label>
                                             <input type="radio"
                                       <?=($edit['f3_q_9']=='opn_3')?'checked':null?>
                                       name="f3_q_9" id="radio-r" value="opn_3" >&nbsp; Somewhat familiar
                                            </label>
                                          </div>
                                            <div class="radio">
                                            <label>
                                             <input type="radio"
                                       <?=($edit['f3_q_9']=='opn_4')?'checked':null?>
                                       name="f3_q_9" id="radio-r" value="opn_4" >&nbsp; Very familar
                                            </label>
                                          </div>
                                            
                                            <div class="radio">
                                            <label>
                                             <input type="radio"
                                       <?=($edit['f3_q_9']=='opn_4')?'checked':null?>
                                       name="f3_q_9" id="radio-r" value="opn_4" >&nbsp; I'm a specialist
                                            </label>
                                          </div>
                                        </div>
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                            <label for="phone">Block This Teacher?:</label>
                                            
                                            <div class="radio">
                                            <label>
                                              <input type="radio"
                                      <?php if($data2['block'] == 1) { ?> checked <?php } ?>
                                       name="block" id="block_1" value="1" >&nbsp; Yes
                                             
                                            </label>
                                          </div>
                                          
                                            <div class="radio">
                                            <label>
                                              <input type="radio"
                                      <?php if($data2['block'] != 1) { ?> checked <?php } ?>
                                       name="block" id="block_1" value="0" >&nbsp; No
                                             
                                            </label>
                                          </div>
                                        </div>
                                       
                                       <div class="col-md-12" style="margin-top:12px">
                                          
                                   <div class="col-md-12"> <label>Uplaod Profile Photo</label>
                                     
                                 <input type="file" name="photo_id"  class="form-control"  <?php if(empty($data2['photo_id'])){?> oninput="this.className = ''" <?php } ?>  >
                            </div>
                                           <?php 
                              // Image if exitst 
                              $photo_id='https://lh3.googleusercontent.com/KqYRICZo55OamI7-_aJNtFTJffEY5cRdAh003Yc_uqG7B_jmTmYiN0OR_oFCOCBNBzYYxULUmQ=w464';
                              
                              if(!empty($profile['photo_id'])){
                              $photo_id='https://tutorgig.englishpro.us/tutor-images/'.$profile['photo_id']; 
                              }
                              
                              
                              ?>
                                           <img src="<?=$photo_id?>" class="img-responsive"  title="Applicant ID" alt="Photo ID" style="width:200px;margin-top: 10px">
                                  </div>
                                       

									<div class="demo-droppable col-md-12" style="margin-top:12px">
									<p style="font-size: 20px">Drag and Drop Files here</p>
									<p>or</p>
									<a class="btn btn-success" style="background: #1b64a9;
									padding: 5px 20px 5px 20px;" href="javascript:void(0)">Browse Files</a>
									</div>
                                    <div class="output col-md-12"></div>   
       <div class="col-md-12">                         
<?php 

$r=mysql_query("SELECT ID,tutor_doc FROM `tutor_docs`where TutorID='".$Tutor['id']."'");

while ($row=mysql_fetch_assoc($r)) {?>

    <a href="?tid=<?php echo $Tutor['id']?>&del=<?php echo $row['ID']?>" 
onclick="return confirm('Are you sure?')"
class="pdf">https://tutorgigs.io/myadmin/uploads/tutorDoc/<?php echo $row['tutor_doc']?><i class="fa fa-close" style="color: red"></i></a>
<?php }?>
</div>
									                      </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit btn btn-primary " style="padding:10px" name="signup_submit">Update</button>
                                </div>
                            </form>
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
<?php include("footer.php"); ?>
<?php include('dragdrop.php')?>
