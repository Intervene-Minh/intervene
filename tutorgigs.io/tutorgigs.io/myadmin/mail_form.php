<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * ***/


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

@extract($_POST);
 $today = date("Y-m-d H:i:s"); 
 $valid_url=true;
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function sendEmailToTutor($email,$to,$message){
  
   // Always set content-type when sending HTML email
   $headers = "MIME-Version: 1.0" . "\r\n";
   $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
   
   // More headers
   $headers .= 'From: <support@tutorgigs.io>' . "\r\n";
   $headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
    if(mail($to,$message,$headers)) return true;
    else return false;
    ///  if(mail($to,$subject,$message,$headers)) echo 'send ';
   
   }

   
if (isset($_POST['send_email'])) {
     //print_r($_POST); die;  
    $email_to =$_POST['email_to'];
    $msg =$_POST['msg'];
    $sender_type = "message";
    $datetm = date('Y-m-d H:i:s');
    $message = $msg;

    $select_query = mysql_query("SELECT * FROM `gig_teachers`  WHERE id='$email_to' ");
    $row = mysql_fetch_array($select_query);
    $email=$row['email'];

    $to= $email;
    $from_email='support@tutorgigs.io'; // learn@intervene.io learn@p2g.org
        if(sendEmailToTutor($from_email,$to,$message)){
         $msg1= "Email sent,<br/>";

        }else{ $msg1 = "Email not send,";}

           $insert_query = "INSERT INTO `inbox` ( `sender_id` , `receiver_id` , `message` , `created_at`, `sender_type`, `updated_at` ) 
			VALUES ('$id', '$email_to', '$msg' ,'$datetm','$sender_type','$datetm')";
            
           // echo $insert_query;exit;
            $data = mysql_query($insert_query)or die(mysql_error());
              
}



/////Listing////////

?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script>
$(document).ready(function(){
 // $('#chat').hide();
$("select").change(function(){
  //  $('#chat').show();
    //window.location='http://localhost/tutorgigs.io/myadmin/mail_form.php?id' +this.value
});

});
</script>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
<?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="folder_wrap" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3> Mail
                        </h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
             <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                <div class="profile-top col-md-12">        
                           <?php  if(isset($error)&&!empty($error)){?>
                                    <div class="profile-item alert alert-info text-left">
                                  <?=$error;?>            
                           </div> <?php }?>                      
                                </div>

             
                                <div id="chat">   
                                    <div class="box col-md-12">
                                    <?php $chat_query=mysql_query("SELECT inbox.message FROM `inbox` JOIN gig_teachers ON inbox.receiver_id = gig_teachers.id WHERE receiver_id='$email_to'");
                                         while($chat_res=mysql_fetch_array($chat_query)){
                                         ?>
                                        <div class="left col-md-6">
                                           <input type="text" value="<?php  echo $chat_res['message']; ?>"> 
                                        </div>
                                         <?php } ?>
                                        </div>
                                        <div class="box col-md-12">  
                                        <?php $chat_query1=mysql_query("SELECT receiver_inbox.message FROM `receiver_inbox` JOIN gig_teachers ON receiver_inbox.sender_id = gig_teachers.id WHERE sender_id='$email_to'");
                                         while($chat_res1=mysql_fetch_array($chat_query1)){
                                         ?>   
                                      <div class="right col-md-6">
                                      <input type="text" value="<?php  echo $chat_res1['message']; ?>">
                                        </div>     
                                        <?php } ?>     
                                    </div>       
                                </div>   
                                <div class="profile-center col-md-12">
                                    <h4 class="title"></h4>
                                    <div class="box col-md-12">
                                        <div class="left col-md-6">
                                            <label for="email">Email:</label>
                                        <select name="email_to" class="required" id="select_id">
                                        <option value="">Select Email</option>
                                        <?php $select_query = mysql_query("SELECT * FROM gig_teachers ");
                                              while($row = mysql_fetch_array($select_query)){
                                                  $fetch_id=$row['id'];
                                         ?>  
                                        <option value="<?php echo $row['id'] ?>"<?php if($email_to==$fetch_id){echo "selected";}?>><?php echo $row['email'];?></option>
                                              <?php } ?>
                                        </select>
                                        </div>
                                               
                                      <div class="right col-md-12">
                                            <label for="msg">Message:</label>    
                                            <textarea  style="border: 1px solid #dddddd;
float: left;
width: 100%;
line-height: 80px;
padding: 0 10px;" id="msg" name="msg"></textarea>
                                                                    
                                            <div class="notif">*Please enter your Address</div>
                                        </div> 
                                    
                                        
                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit" name="send_email">Send</button>
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