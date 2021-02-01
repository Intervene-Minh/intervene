<?php
// @ list-tutor-sessions
/*****
 @ filer by : date selected. 
 * ****/


// List of Teachers 
 @extract($_GET) ;
@extract($_POST) ;

include("header.php");
//////////Validate Site Access//////////
//print_r($_SESSION);
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}
/////////////////////////////////////
  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";
//if($login_role!=0 || !isGlobalAdmin()){
//  header("location: index.php");
//}

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

$error='';
$id = $_SESSION['ses_teacher_id'];
$email = $_SESSION['login_user'];
function sendEmailToAdmin($email,$to,$message){
  
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    // More headers
     $headers .= 'From: <support@tutorgigs.io>' . "\r\n";
     $headers .= 'Cc: isha@srinfosystem.com' . "\r\n";
     if(mail($to,$message,$headers)) return true;
     else return false;
       if(mail($to,$message,$headers)) echo 'send ';
       else echo 'not send';
    }
 

if(isset($_POST['send_email'])){
 
 $msg = $_POST['msg'];
 $datetm = date('Y-m-d H:i:s');
 $sender_type = "tutor";
 $to= 'support@tutorgigs.io';
 $message = "{$msg}";
 $from_email=$email; 
     if(sendEmailToAdmin($from_email,$to,$message)){
      $msg1= "Email sent,<br/>";

     }else{ $msg1 = "Email not send,";}

        $insert_query = "INSERT INTO `inbox` ( `sender_id` , `receiver_id` , `message` , `sender_type`, `created_at`, `updated_at` ) 
         VALUES ('$id', '0', '$msg' ,'$sender_type' ,'$datetm','$datetm')";
         
       // echo $insert_query;exit;
         $data = mysql_query($insert_query)or die(mysql_error());

         unset($_POST);
         header("Location: ".$_SERVER['PHP_SELF']);
           
}


?>

<div id="main" class="clear fullwidth">
  <div class="container">
    <div class="row">
      <div id="sidebar" class="col-md-4">
        <?php include("sidebar.php"); ?>
      </div>    <!-- /#sidebar -->
      <div id="content" class="col-md-8">

         <div class="ct_heading clear">
            <h3><?=$page_name?></h3>
            
          </div>    <!-- /.ct_heading -->
          <div class="ct_display clear">
             <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                <div class="profile-top col-md-12">     
            <?php
            if(isset($error)&&$error != '') {
              echo '<p class="error">'.$error.'</p>';
            }  ?>

                        <div class="col-md-12">
                                        <?php $chat_query=mysql_query("SELECT * FROM inbox where sender_id = $id OR receiver_id = $id");
                                         while($chat_res=mysql_fetch_object($chat_query)){
                                            if($chat_res->sender_type == 'admin'){ ?>
                                               <p style="text-align:left;" class="tutor-message"><?php echo $chat_res->message;?></p>
                                            <?php }
                                         ?>
                                         <?php if($chat_res->sender_type == 'tutor') { ?>
                                          <p style="text-align:right;"> <?php  echo $chat_res->message; ?> </p>
                                          <?php } } ?>
                                        </div>
                                        </div>

                                <div class="profile-center col-md-12">
                                    <h4 class="title"></h4>
                                    <div class="box col-md-12">
                                             
                                      <div class="right col-md-12">
                                            <label for="msg">Message:</label>    
                                            <textarea  style="border: 1px solid #dddddd;float: left;width: 100%;line-height: 80px;padding: 0 10px;" id="msg" name="msg" required></textarea>
                                        </div> 
                                    
                                        
                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit" name="send_email">Send</button>
                                </div>     
            <div class="clearnone">&nbsp;</div>
          </div>    <!-- /.ct_display -->
                                        <input type="hidden" id="arr-user" name="arr-user" value=""/>
        </form>
      </div>    <!-- /#content -->
      <div class="clearnone">&nbsp;</div>
    </div>
  </div>
</div>    <!-- /#header -->

<?php include("footer.php"); ?>