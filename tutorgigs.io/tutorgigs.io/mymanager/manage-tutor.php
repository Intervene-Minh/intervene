<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * ***/

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
@extract($_POST);
 $today = date("Y-m-d H:i:s"); 
 $valid_url=true;
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
 
if (isset($_POST['signup_submit'])) {
     //print_r($_POST); die;  
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    //$username = $_POST['signup-username'];
   
    $email = $_POST['email'];
    $password = $_POST['password'];
    $md5password = md5($password);
    
   // $website= test_input($_POST["url_aww_app"]);
      //$website = ($_POST["url_aww_app"]);
    
    // check if URL address syntax is valid
    
    /*if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
      $error = "Invalid URL"; $valid_url=false;
    } 
    */


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
           
         $insertValuesSQL = implode($arrIMG, ',');
            
        }
    }else{
        $statusMsg = 'Please select a file to upload.';
    }
    /*upload documents end*/
    
    if (!empty($_POST['email'])) {
       // $query = mysql_query("SELECT * FROM users WHERE email = '$email' OR user_name = '$username'", $link);
       
     $query = mysql_query("SELECT * FROM gig_teachers WHERE email = '$email' ", $link);   
        $rows = mysql_num_rows($query);
        
        if ($rows < 1) { // New Register
           
           
            //  home_address phone birth_date  	url_aww_app  created_date
                $queryxxx = "INSERT INTO `gig_teachers` ( `f_name` , `lname` , `email` , `password` ) 
                VALUES ('$firstname', '$lastname', '$email', '$md5password')";

                $query=" INSERT INTO gig_teachers SET f_name='$firstname',"
                . "lname='$lastname', "
                . " email='$email',"
                . "password='$md5password', ";

                // activate user////
                $query.=" email_confirm='1', ";
                $query.=" all_state='yes', ";
                $query.=" status_from_admin='all_application_state_approved', ";
                $query.=" status='1', ";

                //khowlett Jira ManagerPortal
                //set created by to the manager role
                $manager_role=$_SESSION['login_user'];
                //$query.=" created_by='$manager_role', ";
                $query.=" created_by='".$_SESSION['login_role']."', ";



                if(!empty($_POST['home_address']))
                $query.=" home_address='".$_POST['home_address']."', ";
                if(!empty($_POST['phone']))
                $query.=" phone='".$_POST['phone']."', ";

                if(!empty($_POST['birth_date']))
                $query.=" birth_date='".$_POST['birth_date']."', ";
                if(!empty($_POST['url_aww_app']))
                $query.=" url_aww_app='".$_POST['url_aww_app']."', ";

               

                if(!empty($_POST['SpecialtySubjects']))

                $SpecialtySubjects=implode($_POST['SpecialtySubjects'], ',');
                $query.=" SpecialtySubjects='".$SpecialtySubjects."',";

                $query.=" created_date='$today',";
                $query.="created_by='2'";
                $data = mysql_query($query)or die(mysql_error());

                $userId = mysql_insert_id();

            if ($data) {
                foreach ($arrIMG as  $value) {
                    
$str="INSERT INTO `tutor_docs` SET `tutor_doc`='".$value."',`TutorID`='".$userId."'";

 $data = mysql_query($str);
}

                $getToken=_get_token(); 
                $_SESSION['ses_admin_token']=$getToken;

                $tutorId=$userId;
                $Tutor=mysql_fetch_assoc(mysql_query(" SELECT * FROM  gig_teachers WHERE id = '$tutorId' "));
                if(isset($Tutor)&&!empty($Tutor))
                {


                    $res_newrow=mysql_query(" SELECT * FROM  newrow_x_tutors WHERE tutor_intervene_id= '$tutorId' ");
                    $Tutor_rows=mysql_num_rows($res_newrow);

                    if($Tutor_rows>=1)
                    {
                        $Tutor_newrow=mysql_fetch_assoc($res_newrow);
                        $warning_msg[]='newrow id already Created!';
                    }
                    elseif($Tutor_rows<1)
                    {
                        $canCreateNewrowId='yes';
                       // $success_msg[]='New newrow ID Created, for tutor id-'.$tutorId; 
                   }
               }
               else
               {
                $warning_msg[]='Wrong user id! ';
            }

if(isset($canCreateNewrowId)&&$canCreateNewrowId=='yes')
{
  


            $userId=time();
            $get_user_email = (string)$Tutor['email'];

            $post = [
            'user_name' =>'Tutor_'.$userId,
            'user_email' =>$get_user_email, //'test11@gmail.com',
            'first_name' =>$Tutor['f_name'], //'Mastertutor',
            'last_name' =>'Tutor',
            'role' =>'Instructor', // Instructor | Student {CompanyUser}
            ];

            ///////////////////////
            $token=$getToken;// // 5fc8c417a486296fb3fc334293b2b54c
            if(empty($token)){
                     exit('Admin token not found! ');
            }

            ///////////////////////

            $ch = curl_init('https://smart.newrow.com/backend/api/users'); // Initialise cURL
            $post = json_encode($post); // Encode the data array into a JSON string
            $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
            $result = curl_exec($ch); // Execute the cURL statement
            $user_row= json_decode($result); 
            curl_close($ch); // Close the cURL connection
            ///////////////////////////

            $Tutor_int_id=$tutorId;
            $UserEmail=$Tutor['email'];

            $newrow_user_id=$user_row->data->user_id;

            $sql="INSERT INTO newrow_x_tutors SET tutor_intervene_id='$Tutor_int_id',newrow_email='$UserEmail',
            newrow_username='$UserEmail',newrow_ref_id='$newrow_user_id'  ";

            $Add=mysql_query($sql);
     }

    
      $_SESSION['msg_success']='Tutor Added Successfully ';
      header("Location:tutor-list.php"); exit;
     /////////////////
     if(!empty($success_msg)){
      echo implode(',<br/>', $success_msg ); //print_r($success_msg);
      echo '<br/>';
     }
     /////////////////////

      if(!empty($warning_msg))
      {

            echo implode(',<br/>', $warning_msg );
       }

            } else {
                $error = "Sorry, Can not Register try later";# NoRegis
            }
            
            
            
            
        } else {
            $error = $email."<br/>-Email already exists";
        }
        
        
    } else {
        // $valid_url=false;
        $error = "Email Required";
        
        if($valid_url==false)
            $error = $website.",-Invalid url.<br/>Enter a Valid Url";
        
        
        
    }
}



$master_schoolid = $row['master_school_id'];
$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
?>
<script type="text/javascript">
    $(document).ready(function () {
        
         $('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
});
        /////////Date///////////

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
<style>
.multiselect-container input {
    height: 11px;
}
.multiselect {
    overflow: hidden;
}
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
                        <h3>+Add Tutor  
                        </h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
      <?php
//if ($error != '') {
//    echo '<p class="error">' . $error . '</p>';
//} else {
              ?>
                            <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                <div class="profile-top col-md-12">
                                  
                                    
                           <?php  if(isset($error)&&!empty($error)){?>
                                    <div class="profile-item alert alert-info text-left">
                              
                                  <?=$error;?>
                                   
                                        
                                       
                           </div> <?php }?>
                                    
                                                
                                              
                                </div>
                                <div class="profile-center col-md-12">
                                    <h4 class="title">Personal Information</h4>
                                   


<!--   
<a href="profile.php" class="welcome">Welcome <?php { echo $_SESSION['login_role'];}?>!</a>
   
-->

                                    
                                    
                                    <div class="box col-md-12">
                                        <div class="left col-md-6">
                                            <label for="firstname">First Name:</label>
                                            <input type="text" id="firstname" 
                                                   required="" class="required" name="firstname" value="<?=(isset($firstname))?$firstname:NULL?>"/>
                                            <div class="notif">*First Name</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="lastname">Last Name:</label>
                                            <input type="text" id="lastname" 
                                                   class="required" name="lastname" value="<?=(isset($lastname))?$lastname:NULL?>"/>
                                            <div class="notif">*Last Name</div>
                                        </div>
                                        <div class="left col-md-6">
                                            <label for="email">Email:</label>
                                        <input type="email" id="email"
                                       name="email" class="required" value="<?=(isset($email))?$email:NULL?>"/>
                                            <div class="notif">*Enter Valid Email</div>
                                        </div>
                                      

                                        <div class="right col-md-6">
                                            <label for="phone">Phone:</label>
                                <input type="text" class="required"  required=""
                                 id="phone" name="phone" value="<?=(isset($phone))?$phone:NULL?>" maxlength="11"/>
                                            <div class="notif">*Please enter your Telephone Number(Home,Cell)</div>
                                        </div>
                                        
                                        <div class="clearnone">&nbsp;</div>
                                        <div class="left col-md-6">
                                            <label for="password">Password:</label>
                                            <input type="password" required="" id="password" name="password"/>
                                            <div  class="notif">*Enter password</div>
                                        </div>
                                        <div class="right col-md-6">
                                            <label for="repassword">Re-Password:</label>
                                            <input type="password" required="" id="repassword" name="repassword"/>
                                            <div class="notif">"Please confirm your new password!"</div>
                                        </div>
                                        
                                      <div class="right col-md-6">
                                            <label for="phone">Address:</label>
                                            
                                            <textarea  style="border: 1px solid #dddddd;float: left;width: 100%;line-height: 40px;padding: 0 10px;" id="home_address" name="home_address"><?=(isset($home_address))?$home_address:NULL?></textarea>
                                            <div class="notif">*Please enter your Address</div>
                                        </div> 
                                        
                                    <div class="right col-md-6">
                                    <label for="sub">Specialty/Subjects *:</label>

                           

                                    	<select id="dates-field2" class="multiselect-ui form-control" multiple="multiple" name="SpecialtySubjects[]">
                                        
                                    <?php foreach ($sub as  $value): ?>

                                    <option value="<?php echo $value?>"><?php echo $value?></option>
                                    <?php endforeach ?>
                       
                                    </select>
                                    </div> 

									<div class="demo-droppable col-md-12">
									<p style="font-size: 20px">Drag and Drop Files here</p>
									<p>or</p>
									<a class="btn btn-success" style="background: #1b64a9;
									padding: 5px 20px 5px 20px;" href="javascript:void(0)">Browse Files</a>
									</div>

									<div class="output col-md-12"></div>                         </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit" name="signup_submit">Save</button>
                                </div>
                            </form>
    <?php //}   ?>

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
