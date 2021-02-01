<?php
/***
 * @ assign_a_teacher in Slot 
 * @ adhoc teacher
 * 
 * 
 * 
 * 
tut_teacher_id 
modified_date
int_slots_x_student_teacher
slot_id 
tut_teacher_id
tut_admin_id
 * 
 * // fun_assign_tut
 * 
 * **/


include("header.php");
$error =NULL;
//if(!isset($_POST['getid']))exit("Page not found");
if(!isset($_POST['getid']))$error="Page not found. !";
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
///  Email Mail aassgned to > All member ..

//print_r($_POST); die; 
// getid :slot id


@extract($_POST);
 $today = date("Y-m-d H:i:s"); 
 $valid_url=true;
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

  $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
 $sdata= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_POST['getid']));
@extract($sdata);

 $today = date("Y-m-d H:i:s"); 
if (isset($_POST['signup_submit'])) {
    
    $ses_det= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_POST['getid']));
    //  	tut_accept_time  app_url  ::Auto Accepted by admin,
    // else < it filledb 
    if(intval($_POST['select_teacher'])>0){
    require_once('./inc/fun_assign_tut.php');// SendEmail     
    
       
       $tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$_POST['select_teacher']));   
   
    $sid=$_POST['getid'];  $error = 'tutTeacher Added on Slot';
    #1. notify old tutor if 
     $sesdate.=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');
    $sestime=date_format(date_create($ses_det['ses_start_time']), 'G:ia');
    $emtime=$sesdate." at-".$sestime;
      $emboday="A Tutor assigned for your Group Tutor Session scheduled for $emtime  ,<br/>Log in to see the updated schedule.";
     $rs_noti[]=sendEmails($user_id,$em_to=$em_arr['int_admin'],$body=$emboday); # test
    #2. if new assgined 
    /** 
     * 
     * 
     * **/
       
    //if($ses_det['tut_teacher_id']>0){
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
            . "app_url='".$tut_th['url_aww_app']."',tut_accept_time='$today',"
            
            . "modified_date='$today' WHERE id='$sid'", $link);
    
    
    // in studentsLog of Slot
     $up= mysql_query("UPDATE int_slots_x_student_teacher SET tut_teacher_id='".$_POST['select_teacher']."',"
            . "tut_admin_id='$id' WHERE slot_id='$sid'", $link);
     
     if($up==true){
          header("location:list-tutor-sessions.php?ses=1");exit;
     }
    }else $error="Select a Techer";
    
}








/////Listing////////

$results = mysql_query("SELECT u.*, p.`limited` FROM `users` u LEFT JOIN `packages` p ON u.`role` = p.`id` WHERE u.`id` = '$id'", $link);
// echo "SELECT u.*, p.`limited` FROM `users` u INNER JOIN `packages` p ON u.`role` = p.`id` WHERE u.`id` = '$id'";






$master_schoolid = $row['master_school_id'];
$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
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
                    <div class="ct_heading clear">
                        <h3>+Assign  Teacher-Tutor Sessions  
                        </h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
      <?php
if ($error != '') {
   echo '<p class="error">' . $error . '</p>';
} else {
              ?>
                            <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                 <h4 class="title">Tutor Sessions Information</h4>
                                <div class="profile-center col-md-12">
                                 
                                    <p>
                               <?php 
                               $sql="SELECT * FROM gig_teachers WHERE 1 ";
                               $results=mysql_query($sql);
                               ?>        
                                       
                                            <label for="select_teacher">Choose Tutor:</label><br />
                                            <select name="select_teacher" style="width: 70%" id="district">
                                           <?php while ($row= mysql_fetch_assoc($results)) { ?>
                             <option  value="<?=$row['id']; ?>"><?=$row['f_name']." ".$row['lname']?></option>

                                            <?php } ?>
                                            </select>

                                        </p>
                                    
                                    
                                    
                                    
                                        
                                        
                                        
                                        <?php
                         $int_school= mysql_fetch_assoc(mysql_query("SELECT district_id,SchoolName FROM schools WHERE SchoolId=".$school_id));     
             // district_id 
          if($int_school['district_id']>0){
          $district=mysql_fetch_assoc(mysql_query(" SELECT  district_name FROM loc_district WHERE id=".$int_school['district_id']));     
          $districtName=$district['district_name'];
          
          }   
         $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=".$teacher_id));
                 // SELECT * FROM `int_quiz` WHERE 1
            $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$quiz_id));
            
             $grade= mysql_fetch_assoc(mysql_query("SELECT * FROM `terms` WHERE id=".$grade_id));
                           //$quiz objective_name             
                   $q=" Select sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$_POST['getid']."' ";
            $resss=mysql_query($q);
            $stud_str=array();
            while ($row= mysql_fetch_assoc($resss)) {
                $stud_str[]=$row['first_name'];
            }                 
             
             
             
                                        ?>
                                    
                                 
                                    
            
                                    <table class="table-manager-user col-md-12">
								<colgroup>
									<col width="40%">
									<col width="60%">
									
								</colgroup>
								<tbody>
																		
                                                              
                                                                 <tr>
                                                            <td>
                                                                <strong class="text-primary"> School:</strong>
                                                                 </td> 
                                                                 
                                                                <td>
                                                                <?=$int_school['SchoolName']?>
                                                                 </td> 
                                                                 </tr>
                                                                 
                                                                 <tr>
                                                            <td>
                                                                <strong class="text-primary"> District:</strong>
                                                                 </td> 
                                                                 
                                                                <td>
                                                                <?=$districtName?>
                                                                 </td> 
                                                                 </tr>
                                                                 
                                                                 
                                                                 <tr>
                                                            <td>
                                                                <strong class="text-primary"> 
                                                                    Date and Time of session:</strong>
                                                                 </td> 
                                                                 
                                                                <td>
                                                                 <?=$ses_start_time?>
                                                                 </td> 
                                                                 </tr>
                                                                   
                                                                 <tr>
                                                                <td colspan="2">
                                                      <strong class="text-primary"> 
                                                                    Teacher(Intervene) input to session:</strong>           
                              <p><span class="text-danger">Teacher Name-</span><?=$int_th['first_name']?></p>
                                                   <p><span class="text-danger">Objective</span>-<?=$quiz['objective_name']?></p>
                                              <p><span class="text-danger">Notes-</span> <?=$special_notes?></p>
                                        <p><span class="text-danger">Grade-</span><?=$grade['name']?></p>
                                             <p><span class="text-danger">Students-</span><?= implode(",", $stud_str)?></p>                                                          
                                                                 </td>     
                                                                 </tr>                              
                                                                
                                                                 
                                                                 
                                                                 
                                                                 
                                                                    
                                                                 
															
                                                                </tbody></table>
                                        
                                        
                                        
                                    
                                  <!--      XXXXXXXXXXXXXX   dff-->
                                    <div class="box col-md-12">
                                   
                                        
                                        
                                         
                                         
                                        
                                        
                                
                                        <input    type="hidden" name="getid"
                                                   value="<?=$_POST['getid']?>"/> 
                                        
                                     
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit"
                                            class="button-submit" name="signup_submit">Submit</button>
                                
                                
                                </div>
                            </form>
    <?php }   ?>

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