<?php
/*****
 @ filer by : date selected. 
 * ****/


 @extract($_GET) ;
@extract($_POST) ;
include("header.php");
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}


/////////////////////////////////////
  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="Inbox";
//if($login_role!=0 || !isGlobalAdmin()){
//  header("location: index.php");
//}

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
################################

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
 

 ////////Send a message/////
 if(isset($_POST['send_email'])||isset($_POST['msg'])){
 $datetm = date('Y-m-d H:i:s');
 $sender_type = "tutor";
 $to= 'support@tutorgigs.io';
 $message = "{$msg}";
 $from_email=$email; 
     // if(sendEmailToAdmin($from_email,$to,$message)){
   $msg=trim($_POST['msg']); 

     if(!empty($msg)){

          $msg=nl2br($msg);
         $msg=addslashes($msg);
         //echo $msg.'==';  die; 
         

        $insert_query = "INSERT INTO `inbox` ( `sender_id` , `receiver_id` , `message` , `sender_type`, `created_at`, `updated_at` ) 
         VALUES ('$id', '0', '$msg' ,'$sender_type' ,'$datetm','$datetm')";
         
       
         $data = mysql_query($insert_query)or die(mysql_error());

         unset($_POST);
         header("Location: ".$_SERVER['PHP_SELF']);

       }else{
          $error= "Message cannot be empty!";

       }
           
}


?>
<script type="text/javascript">
  $(document).ready(function(){
    

$('#message_area').animate({scrollTop: $('#message_area').prop("scrollHeight")}, 500);   
  // $("img").load(function(){
  //   alert("Image loaded.");
  // });


});
</script>
<style>
#main {
    padding: 10px 0 50px;
    background-color: #fafafa;
    color: black;
}
#content .ct_display {
    padding: 25px 0px 0px;
    display: inline-block;
    width: 100%;
	border: 0px solid #cdcfd2;
	background: #f3f3f5;
}
.text-send{
	text-align: right;
    background-color: #fff;
    padding: 10px 15px;
    width: auto;
    display: inline-block;
    float: right;
    color: #000000;
    border-radius: 10px;
	font-size:16px;
}
.text-recived{
	text-align: left;
    background-color: #fff;
    padding: 10px 15px;
    width: auto;
    display: inline-block;
    float: left;
    color: #000;
    border-radius: 10px;
	font-size:16px;
}
.dd-ll {
    width: 100%;
    display: inline-block;
	padding: 0px 20px;
}
.dd-mm{
    width: 100%;
    display: inline-block;
	padding: 0px 20px;
}
.form-text{
	border: 0px solid #dddddd;
	float: left;
	width: 100%;
	line-height: 30px;
	padding: 10px 20px;
	color:#000;
	resize: none;
}
.form-text:hover, .form-text:focus{
	outline:none !important;
}
.profile-center {
	background: #fff;
    padding: 0px 0px;
    margin: 0px 0px 0px;
    float: left;
    width: 100%;
    border-radius: 0px 0px 5px 5px !important;
}
.profile-center>.button-submit {
    text-align: center;
    border: none;
	border: 0 !important;
    background: url(https://tutorgigs.io/send-button.png) no-repeat right !important;
    padding: 0px 5px !important;
    margin-top: 20px !important;
    position: relative;
    width: 20px !important;
    height: 20px !important;
    cursor: pointer;
    box-shadow: none !important;
    float: right;
    text-indent: -9999px;
    white-space: nowrap;
	margin-right: 20px;
}
.profile-top {
    height: 640px;
    overflow: auto;
}
</style>
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
           <?php
            // if(isset($error)&&$error != '') {
            //   echo '<p class="error">'.$error.'</p>';
            // }  

            ?>
             <form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">
              <?php  if(isset($error)&&!empty($error)){?>
                                    <div class="profile-item alert alert-info text-left">
                                  <?=$error;?>            
                           </div> <?php }?>    



                                <div class="profile-top col-md-12" id="message_area">




           

                                      <div class="col-md-12">
                                        <?php $chat_query=mysql_query("SELECT * FROM inbox where sender_id = $id OR receiver_id = $id");
                                         while($chat_res=mysql_fetch_object($chat_query)){
                                            if($chat_res->sender_type == 'admin'){ ?>
                                               <div class="dd-ll"><p class="text-recived" style="text-align:left;" class="tutor-message"><?php echo $chat_res->message;?></p></div>
                                            <?php }
                                         ?>
                                         <?php if($chat_res->sender_type == 'tutor') { ?>
                                          <div class="dd-mm"><p class="text-send" style="text-align:right;"> <?php  echo $chat_res->message; ?> </p></div>
                                          <?php } } ?>
                                        </div>
                                        </div>

                                <div class="profile-center col-md-12">
                                    <div class="box col-md-10">
                                             
                                            <!--<label for="msg">Message:</label>-->    
                                            <textarea  class="form-text" placeholder="Your message" id="msg" name="msg" required></textarea>
                                    </div>
                                    <button type="submit"  title="Send" 
                                     id="profile-submit" class="button-submit" name="send_email">Send</button>
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
<script type="text/javascript">

//     $("#msg").keyup(function(event){
//     if(event.keyCode == 13){
//        $("#form-profile").submit();
//       // alert(abcd);
//     }
// });
</script>
