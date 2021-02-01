<?php
   /**
    * @ $slot :: Tutor Sessions 
    * Sessions Date/Time
   
   
   List of students
   Start time 
    * @ Frm Date Pickersions Date
    *   @http://drhomework.com/parent/ :: Tutoring session detail 
    * **/
   
  
   
   include("header.php");
   
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
                  <!-- Drhomwork Detail -->
                  <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                     <div class="profile-center col-md-12">
                        <h4 class="title text-primary">DrhomworkHelp Session detailTest </h4>
						<?php
						$SessionData = mysql_fetch_assoc(mysql_query("Select * FROM dr_tutoring_info where session_ref_id= '".$_POST['getid']."'"));
						$data=json_decode($SessionData['session_stu_data']);
						?>
                        <!--         Tutor -->
                        <div class="box col-md-12">
                           <br>
                           <h4 class="title text-primary">Tutor Information</h4>
                           <div class="form-group col-md-12">
                              <label for="email">Name of Tutor:</label>
                              <input class="required" value=" ">
                           </div>
                           <div class="form-group col-md-12">
                              <label for="email">PayPal Email:</label>
                              <input class="required" value="">
                           </div>
                           <!-- Phone -->
                           <div class="form-group col-md-12">
                              <label for="email">PayPal Phone:</label>
                              <input class="required" value="">
                           </div>
                        </div>
                        <div class="clear">&nbsp;</div>
                        <div class="text-center"><a class="btn btn-success btn-md" onclick="alert('Go, Back ');
                           location.href='./list-tutor-sessions.php';" href="javascript:void(0);">Back, Home</a></div>
                     </div>
                  </form>
                  <!-- Drhomework detail  -->
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