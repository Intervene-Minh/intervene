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
    $to = "isha@srinfosystem.com";
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

$tutorId = isset($_GET['tid']) ? $_GET['tid'] : false;

   
if (isset($_POST['send_email'])) {
     //print_r($_POST); die;  
    $email_to =$_POST['email_to'];
    $msg =$_POST['msg'];
    $sender_type = "admin";
    $datetm = date('Y-m-d H:i:s');
    $message = $msg;

    $select_query = mysql_query("SELECT * FROM `gig_teachers`  WHERE id='$email_to' ");
    $row = mysql_fetch_array($select_query);
    $email=$row['email'];
    $to = "isha@srinfosystem.com";
    ///$to= $email;
    $from_email='support@tutorgigs.io'; // learn@intervene.io learn@p2g.org
        if(sendEmailToTutor($from_email,$to,$message)){
         $msg1= "Email sent,<br/>";

        }else{ $msg1 = "Email not send,";}
     //  echo $msg1;exit;
   
    $insert_query = "INSERT INTO `inbox` ( `sender_id` , `receiver_id` , `message` , `created_at`, `sender_type` ) 
      VALUES ('0', '$tutorId', '$msg' ,'$datetm','$sender_type')";

       
      // echo $last_msg_id  ,'--last id '; die; 

    $data = mysql_query($insert_query)or die(mysql_error());

    $last_msg_id=mysql_insert_id();


    /////Send notifications////////

    $notify_query=mysql_query("SELECT * from gig_teachers WHERE id='$tutorId'");
    $notify_res=mysql_fetch_assoc($notify_query);

    $msg_query =mysql_query("SELECT * FROM inbox where receiver_id = $tutorId");
    $msg_res= mysql_fetch_assoc($msg_query);
   // $type_id = $msg_res['id'];

      $notify_all=$notify_res['notify_all'];
      $notify_msg=$notify_res['notify_msg'];

    if($notify_all== "yes"||$notify_msg=="yes"){
      $notify_msg='New message-'.$msg;
      $msg_query1= mysql_query("INSERT INTO notifications (sender_id, receiver_id, type, sender_type,type_id, info, created_at,updated_at) VALUES('0','$tutorId','message',
        'admin','$last_msg_id', '$notify_msg','$datetm','$datetm')");
     
    }

            unset($_POST);
            $url=$_SERVER['PHP_SELF']."?tid=".$_GET['tid'];
            ////
         header("Location: ".$url);exit;
          
}



/////Listing////////

?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->

<style>
.active{
   color: blue;
   font-weight:bold;
}
</style>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
            <div class="ct_heading clear">
                        <h3> Tutors,&nbsp;&nbsp;<a class="btn btn-primary btn-xs"
                         href="tutor-list.php">Back, Home</a></h3>
                    </div>
            <div class="box col-md-12">
            
            <?php 
            $activePageId = $tutorId;
                                      // $sql1 = "SELECT DISTINCT gig_teachers.email,gig_teachers.id AS tid FROM `gig_teachers` JOIN inbox ON gig_teachers.id = inbox.receiver_id GROUP BY inbox.receiver_id";
                                        $sql1="SELECT i.sender_id,t.* FROM `inbox` i left JOIN gig_teachers t ON i.sender_id=t.id WHERE 1 AND i.sender_id>0 GROUP BY i.sender_id ";
                                        $select_query_res = mysql_query($sql1);
                                      
                                        while($row1 = mysql_fetch_assoc($select_query_res)){
                                                $fetch_id=$row1['id'];
                                         ?>  
                                         <p <?php if($row1['id'] === $activePageId){ ?>class="active"<?php } ?>><a href="message.php?tid=<?php echo $row1['id']; ?>"><?php echo $row1['f_name'];?> <?php echo $row1['lname'];?></a></p>
                                        <?php } ?>

                                       

                                        </div>
                
<?php // include("sidebar.php"); ?>
            </div>    <!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="folder_wrap" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Message
                        </h3>
                    </div>    <!-- /.ct_heading -->
                    <div class="ct_display clear">
             <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
                                <div class="profile-top col-md-12">        
                           <?php  if(isset($error)&&!empty($error)){?>
                                    <div class="profile-item alert alert-info text-left">
                                  <?=$error;?>            
                           </div> <?php }?>                      
                                </div>


                                <div class="profile-center col-md-12">
                                    <h4 class="title"></h4>
                                    <div class="box col-md-12">
                                     
                                        <?php// echo $tutorId; exit;?>

                                        <!-- <div class="col-md-6"> -->
                                        <?php $chat_query=mysql_query("SELECT * FROM inbox where sender_id = $tutorId OR receiver_id = $tutorId");
                                         while($chat_res=mysql_fetch_object($chat_query)){
                                            if($chat_res->sender_type == 'admin'){ ?>
                                               <p style="text-align:right; color:green;" class="tutor-message"><?php echo $chat_res->message;?></p>
                                            <?php }
                                         ?>
                                         <?php if($chat_res->sender_type == 'tutor') { ?>
                                          <p style="text-align:left;border-bottom: 1px solid #f8f1f1;"> <?php  echo $chat_res->message; ?> </p>
                                          <?php } } ?>

                                       

                                        </div>


                                        <div class="profile-center col-md-12">   
                                        <div class="box col-md-12"> 
                                      <div class="right col-md-12">   
                                            <textarea  style="border: 1px solid #dddddd;float: left;width: 100%;line-height: 80px;padding: 0 10px;" id="msg" name="msg" required></textarea>
                                        </div> 
                                    
                                        </div>
                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <button type="submit" id="profile-submit" class="button-submit" name="send_email" >Send</button>
                                </div>
                            </form>
    <?php //}   ?>

                        <div class="clearnone">&nbsp;</div>
                    </div>    <!-- /.ct_display -->
                </div>
            </div>    <!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>    <!-- /#header -->
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
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script>

    // if ( window.history.replaceState ) {
    //     window.history.replaceState( null, null, window.location.href );
    // }

</script>
<?php include("footer.php"); ?>