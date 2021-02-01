<?php
/**
 * @ $slot :: Tutor Sessions 
 * Sessions Date/Time
 * @ Frm Date Pickersions Date
 @ Homework help :  detail and 
 @ braincert : Code generate.
 *     $_SESSION['msg_success'];
  $_SESSION['msg_warning'];==>
  $_SESSION['msg_error'];==> sto
  $_SESSION['msg_info']

 * **/



include("header.php");

$error = '';
//echo 'Homework';
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

    $slot= mysql_fetch_assoc(mysql_query($qq));
    @extract($slot);
     $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=".$slot['teacher_id']));
     $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$slot['tut_teacher_id']));    
          $int_school= mysql_fetch_assoc(mysql_query("SELECT SchoolName FROM schools WHERE SchoolId=".$slot['school_id']));
}




//$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
// generate
include('braincert_api_inc.php');
$msg=NULL;
if(isset($_POST['generate'])) {
   // echo '<pre>', print_r($_POST); die; 
  ////Board URL:://///////// getid=1257
     $ses_id=$_GET['getid'];
      $ses_row=mysql_fetch_assoc(mysql_query(" SELECT *
FROM `int_schools_x_sessions_log`
WHERE id=".$ses_id));

   $board_api_key='BlOM11ettmLhEMiRqRui';
$arr=array();
//$ses_id=$getid;
$arr['title']=$title='Demo_Homework_Help_'.$ses_id;
 $arr['date_start']=date('Y-m-d', strtotime($ses_row['ses_start_time']));// 2019-02-15  //$date_start='2019-02-15';
$arr['start_time']=date('h:i A', strtotime($ses_row['ses_start_time']));     //$start_time='09:30 AM';
 $arr['end_time']=date('h:i A', strtotime($ses_row['ses_end_time']));  //$end_time='10:20 AM';



$arr['currency']='usd';
$arr['ispaid']=1; //1

$arr['is_recurring']=1;
$arr['repeat']=1;
$arr['weekdays']='1,2,3';

$arr['end_classes_count']=3;
$arr['seat_attendees']=5;
$arr['record']=1;
//  print_r($arr); die;
 //$get_data=get_braincert_class($arr,$ses_id=$getid,$type='Intervention');
   // print_r($get_data);  die;  // Update Class id of braincert. 

    $get_class_id=$get_data->class_id;   // die;  mysql_query
     $Up=mysql_query(" UPDATE int_schools_x_sessions_log SET braincert_class='$get_class_id' WHERE id=".$ses_id);
      //echo '<pre>',$Up; die; 
     $sql_student=mysql_query(" SELECT sx.slot_id,sx.student_id,s.id as sid,s.first_name,s.middle_name,s.last_name FROM int_slots_x_student_teacher sx
left join students s
ON sx.student_id=s.id
WHERE sx.slot_id=".$ses_id);
     while($row=mysql_fetch_assoc($sql_student)){
      echo $student_id=$row['student_id']; echo ',<br/>';

       ///////////Get Board URL /////////
      $student_id=$row['student_id'];
    //  $stu_str=get_student_board_url($clss_id=$get_class_id,$student_id=$student_id);//str
      //$stu_obj=json_decode($stu_str);
     // print_r($stu_obj); die; 
      // braincert_class
       $student_board_url=$stu_obj->launchurl;  // die; 
      $student_board_class=$get_class_id;
      //Update URL 
      
                           // echo $sql_student; die; WHERE `slot_id` = '1257' AND `student_id` = '10585'
                            $student_board_url='abc.com';
         $upp=(" UPDATE int_slots_x_student_teacher SET launchurl='$student_board_url' WHERE slot_id='$ses_id' AND student_id='$student_id' ");      
         echo '<pre>', $upp; die   ;            
          


           // $insert = mysql_query($sql_student)or die(mysql_error());


     }


    




    ///Disply
    $_SESSION['msg_info']='Record added';
}

?>
<script type="text/javascript">
    $(document).ready(function () {

        $('#district').chosen();
        
        $('#district').change(function () {
            district = $(this).val();
            $('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_schools', school_id: '<?php print $master_schoolid; ?>'},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();
        $('#text-change-avt').on('click', function () {
            $('#input-change-avt').trigger("click");
        });
        $('#input-change-avt').on('change', function () {
            var inp = document.getElementById('input-change-avt');
            var file = inp.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onloadend = function (e) {
                    $('.avatar > img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
            ;
        });

        $('#profile-submit').on('click', function () {
            $('.required').each(function () {
                if (!$(this).val()) {
                    $(this).parent().addClass('warning');
                }
            });

            var $email = $('#email').val();
            if (!isEmail($email)) {
                $('#email').parent().addClass('warning');
            }

            var $check = $('.warning').length;
            if ($check != 0)
                return false;
        });
        $('#form-profile input.required').blur(function () {
            if ($(this).val().length === 0) {
                $(this).parent().addClass('warning');
            }
        });
        $('#form-profile input.required').focus(function () {
            $(this).parent().removeClass('warning');
        });
        $("#repassword").keyup(function () {
            checkPasswordMatch();
        });
        $("#password").keyup(function () {
            checkPasswordMatch();
        });

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
        function checkPasswordMatch() {
            var $password = $("#password").val();
            var $rePassword = $("#repassword").val();

            if ($password != $rePassword)
                $("#repassword").parent().addClass('warning');
            else
                $("#repassword").parent().removeClass('warning');
        }
    });
    
    /////////////////      
      function sent_form(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
	// form.setAttribute("target", "_blank");//

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}
    
    
    
</script>
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

                   <div class="col-sm-12">
                   <?php  include("msg_inc_1.php");?>

  <!--    <div class="alert alert-success"> <a href=""
      class="close" data-dismiss="alert" aria-label="close">×</a>
                 Msg-Information added. !
                </div> -->
   

                </div>



                                <div class="profile-top col-md-12">
                          
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
                                        
                                        <!-- <div class="profile-item">
                                            <div class="left col-md-4">
                                           <label for="profile-username">App Url:</label>
                                            </div>
                                            <div class="right col-md-8">
                                              <p><?=$app_url?></p>
                                               
                                            </div>
                                        </div> -->
                                        
                                    </div>     </div>

                                     <!-- Time   <div class="profile-top col-md-12">-->
                                    
                                    
                                    
  
                             
                      
                      
                      
                      
                      
                      
                      
                      
                                <div class="profile-center col-md-12">
                                    <h4 class="title text-primary">Intervene Information</h4>
                                    <div class="box col-md-12">
                                
                                      <div class="left col-md-6">
                                            <label for="firstname">School Name:</label>
                                            <p class="text-danger"><?=$int_school['SchoolName']?></p>

                                
                                            
                                        </div>  
                                        
                                        
                                   
                                        
                                        
                                        <div class="left col-md-6">
                                            <label for="email">Teaher:</label>
                                        <p class="text-danger"><?=$int_th['first_name']?></p>
                                          
                                        </div>
                                      
                              <div class="left col-md-12">  
                                    <br/>
                                            <label for="firstname">Special Note(Teacher):</label>
                                            <p class="required" class=""  style="text-transform: full-width;"> 
                                            <?=(!empty($special_notes))?$special_notes:"NA"; ?> <br/>   
                                           
                                            </p>
                                            
                                     
                                            
                                        </div>  
                                                   
                                      
                                 
                         
                                    </div>
                                    
                     <!--         Tutor -->
                                      <div class="box col-md-12">
                                          <br/>
                                    <h4 class="title text-primary">Tutor Information</h4>    
                                  <div class="left col-md-6">
                                            <label for="email">Name of Tutor:</label>
                                           <p> <?=$tut_th['f_name']." ".$tut_th['lname']?></p>
                                                  
                                            
                                        </div> 
                                      
                                      
                                      </div>

                                       <?php 

                                       // $getid=$_POST['getid']; //ID  :: $slot

                                // $master  $mater['lesson_id'] grade_id 
                     $master_lessons=mysql_fetch_assoc(mysql_query(" SELECT * FROM master_lessons WHERE id=".$slot['lesson_id']));
                      $quiz_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_quiz WHERE id=".$slot['quiz_id']));
                   $terms=mysql_fetch_assoc(mysql_query(" SELECT id,name FROM terms WHERE id=".$slot['grade_id']));
                                // 
                                    // echo $terms['name'];


                                ?>




                                      <div class="box col-md-12"> 
                                      <br/>
                                      <h4 class="title text-primary">Students Info</h4> 

                                       <div id="textarea" style="display: block">

                                     <span> <strong class="text-primary">
                                 Grade:</strong> </span> <?=$terms['name']?> <br/>


                                   <span> <strong class="text-primary">
                                 Quiz:</strong> </span> <?=$quiz_det['objective_name']?> <br/>

                                   <span> <strong class="text-primary">
                                 Lesson:</strong> </span> <?=$master_lessons['name']?>  <br/>
                                    
                                   <!-- <p class="text-success">  -->
                                     <span> <strong class="text-primary">
                                  Student List :</strong> </span> 


 



                                   <?php 
                                   // getid
                                   $ses_id=$_GET['getid'];
                                   $sql_student=" SELECT sx.slot_id,sx.launchurl,sx.student_id,s.id as sid,s.first_name,s.middle_name,s.last_name FROM int_slots_x_student_teacher sx
left join students s
ON sx.student_id=s.id
WHERE sx.slot_id= '$ses_id' ";  


                           $res=mysql_query($sql_student);
                           $num_students=mysql_num_rows($res);
                             while ($row= mysql_fetch_assoc($res)) {
                               $name=$row['first_name'];
                                $launchurl=$row['launchurl'];

                               echo $name.',&nbsp;';
                               if(!empty($row['launchurl']))
                               echo '<p  class="text-danger"><a style="display: inline-block;word-break: break-all" href="'.$launchurl.'"> '.$launchurl.'</a></p> <br/>';
                                
                              }

                              // $mster



                                   ?>
                                    
                              

                                </div> </div>  

                               


                                      
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    <div class="clear">&nbsp;</div>
                                <div class="text-center">
                                  <?php  if($num_students>=1){?>

                                 <button type="submit"
                                 class="btn btn-primary btn-lg"
                                  name="generate" value="generate">Generate Braincert URL</button>
                                  <?php  } ?>


                                <a  class="btn btn-success btn-md" onclick="alert('Go, Back ');
                                        location.href='./sessions-list.php';" 
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

<?php include("footer.php"); ?>
