<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * ***/


include("header.php");
$from_email='support@tutorgigs.io'; 

######################
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

#///////

   function send_a_email($email,$to,$message,$f_name=''){
$subject = "Message- Tutorgigs.io";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
 //$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';

}


///////////

//     die; 




/////////////////////////////

$tutorId = isset($_GET['tid']) ? $_GET['tid'] : false;

//////////Read Message ///////////

 if(isset($_GET['tid'])&&$_GET['tid']>0){
  
  $sql_up_unread=" UPDATE `inbox` SET `is_read` = '1' WHERE is_read!=1 AND sender_id=".$_GET['tid'];
 //$sql.=" AND type='jobs' AND type_id=".$_GET['id'];
  //echo '<pre>',  $sql ; die; 
 $sql_unread=" SELECT *
FROM inbox
Where is_read!=1 AND sender_id=125";

   $ac=mysql_query($sql_up_unread); 

   
 }
//////////Read Message ///////////
/// Last pending user 

   $sql2=" SELECT * FROM `inbox` WHERE sender_id>0 AND is_read=0 ORDER BY `created_at` DESC LIMIT 0,1 ";

 $last_tutor=mysql_fetch_assoc(mysql_query($sql2));
  if(!isset($_GET['tid'])){
      header("Location:message.php?tid=".$last_tutor['sender_id']);exit;
       }

                                         

                                       





   
///////////////////////////
if (isset($_POST['send_email'])||isset($_POST['msg'])) {
     
    
    $tutor_id=$_GET['tid'];
    $msg =$_POST['msg'];
    $sender_type = "admin";
    $datetm = date('Y-m-d H:i:s');
     $msg=trim($msg);

    //$select_query = mysql_query("SELECT * FROM `gig_teachers`  WHERE id='$tutor_id' ");
    $select_query = mysql_query("SELECT id,email FROM `gig_teachers`  WHERE id='$tutor_id' ");
    $row =mysql_fetch_assoc($select_query);
   // print_r($row); die; 
    $email=$row['email'];
    
    //$to = "rohit@srinfosystem.com";
    $to= $email; //Mail sent to 

      




       if(!empty($msg)){
        $error='sent';
         // addslashes
      $message=$msg=addslashes(nl2br($msg));  //$message= $msg; // Email message 

         /// Send Email
          if(send_a_email($from_email,$to,$message,$f_name)){
             $msg1= "Email sent<br/>";  //   die;

         }else{ $msg1 = "Email not send,";}

         //echo $msg.'===='; die; 

         ///////////////////////////



      
    
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
      //$notify_msg='New message-'.$msg;
      $notify_msg=$msg;

      $msg_query1= mysql_query("INSERT INTO notifications (sender_id, receiver_id, type, sender_type,type_id, info, created_at,updated_at) VALUES('0','$tutorId','message',
        'admin','$last_msg_id', '$notify_msg','$datetm','$datetm')");
     
    }

      unset($_POST);
            $url=$_SERVER['PHP_SELF']."?tid=".$_GET['tid'];
         header("Location: ".$url);exit;

  }else{ 
   $error.="Message cannot be empty!.<br/>";
  }

          
          
}



/////Listing////////
// $error="Fields should not be empty.<br/>";
?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->

<style>
.active{
   background:#fff;
   padding:10px !important;
   margin:10px 0px 0px;
   border-radius:10px;
   border:none !important;
}
.active a{
   color: #37a000;
   font-weight:bold;
}
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
    height: 540px;
    overflow: auto;
}
.profile-wrap {
    padding: 0;
    margin: 0px;
}
.list-item{
	margin: 0 0 0px;
    border-bottom: 0px solid #fff;
    padding: 10px 10px;
    color: #000;
	font-size:16px;
}
.icon-section-1 {
    float: right;
	margin: -4px 0px 0px;
}
.icon-section-1 span.right-chev {
    background: #37A000;
    border: 2px solid #f3f3f3;
    width: 30px;
    height: 30px;
    border-radius: 50px;
    text-align: center;
    padding: 0px;
    float: left;
    margin: 0px 0px 0px;
    -webkit-box-shadow: 0px 2px 6px 0px rgba(245, 245, 245, 1);
    -moz-box-shadow: 0px 2px 6px 0px rgba(245, 245, 245, 1);
    box-shadow: 0px 2px 6px 0px rgba(245, 245, 245, 1);
	list-style:none;
}
.icon-section-1 span.right-chev i {
    color: #fff !important;
    font-size: 15px;
    padding: 7px 0px 0px 9px;
}
</style>
<script type="text/javascript">
  $(document).ready(function(){
    
$('#message_area').animate({scrollTop: $('#message_area').prop("scrollHeight")}, 500);   

});
</script>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4" style="background:#f3f3f5;">
            <div class="ct_heading clear">
                        <h3> Tutors,&nbsp;&nbsp;<a class="btn btn-primary btn-xs"
                         href="tutor-list.php">Back, Home</a></h3>
                    </div>
            <div class="box">
            
            <?php 
            $activePageId = $tutorId;
                                      // $sql1 = "SELECT DISTINCT gig_teachers.email,gig_teachers.id AS tid FROM `gig_teachers` JOIN inbox ON gig_teachers.id = inbox.receiver_id GROUP BY inbox.receiver_id";
                                        $sql1="SELECT i.sender_id,t.* FROM `inbox` i left JOIN gig_teachers t ON i.sender_id=t.id WHERE 1 AND i.sender_id>0 GROUP BY i.sender_id ";

                                        ///////////////
                                 //       $sql2=" SELECT * FROM `inbox` WHERE sender_id>0 ORDER BY `created_at` DESC LIMIT 0,1 ";




                                        $tutor_sq=" SELECT * FROM `inbox` WHERE `sender_id` > '0' ORDER BY `created_at` DESC ";

                                        $sq_arr=array();

$res_tutor_sq=mysql_query($tutor_sq);
while ($row=mysql_fetch_assoc($res_tutor_sq)) {
$sq_arr[]=$row['sender_id'];
  }
  $sq_arr2=array_unique($sq_arr);
   // print_r($sq_arr2);  
/// Get pending . 

$sql_pending="SELECT i.sender_id,  count(i.sender_id) as tot_msg,t.id as tid,t.f_name,t.lname
FROM inbox i 
left join  gig_teachers t
ON i.sender_id=t.id
WHERE i.sender_id>0 AND i.is_read='0'
Group By i.sender_id
ORDER BY i.created_at DESC ";



$res_msg_pending=mysql_query($sql_pending);
$pending_arr==array();
while ($row2=mysql_fetch_assoc($res_msg_pending)) {
  # code...
  $pending_arr[$row2['sender_id']]=$row2;
}
 

     //print_r($pending_arr);  





                                         


                                          foreach ($sq_arr2 as $tid) {
                                       $teacher=mysql_fetch_assoc(mysql_query(" SELECT * FROM `gig_teachers` WHERE id=".$tid));
   # code...
   //$tname=$pending_arr[$tid]['f_name'].' '.$arr2['lname'];
                                       $teacherName=$teacher['f_name'].' '.$teacher['lname'];

           $count_pending=(isset($pending_arr[$tid]['tot_msg']))?$pending_arr[$tid]['tot_msg']:0;

              $active_class=($tid ==$activePageId)?'active':'';
   // $arr2['tot_msg']



                                         ?>
										 
                                         <p class="list-item <?=$active_class?>"  >
                                         <a href="message.php?tid=<?=$tid?>"><?php echo $teacherName ; ?> &nbsp;(<?=$count_pending?>) </a>
										     <span class="icon-section-1">
													  <span class="right-chev"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
											 </span>
										 
										                    </p>
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

                     
            
                           <?php  if(isset($error)&&!empty($error)){?>
                                    <div class="profile-item alert alert-info text-left">
                                  <?=$error;?>            
                           </div> <?php }?> 

                              
                                


                                <div class="profile-top col-md-12" id="message_area">
                                    <div class="box col-md-12" >
                                     
                                        <?php// echo $tutorId; exit;?>

                                        <!-- <div class="col-md-6"> -->
                                        <?php $chat_query=mysql_query("SELECT * FROM inbox where sender_id = $tutorId OR receiver_id = $tutorId");
                                         while($chat_res=mysql_fetch_object($chat_query)){
                                            if($chat_res->sender_type == 'admin'){ ?>
                                               <div class="dd-ll"><p class="text-recived"><?php echo $chat_res->message;?>
											   
											   
											   </p></div>
                                            <?php }
                                         ?>
                                         <?php if($chat_res->sender_type == 'tutor') { ?>
                                          <div class="dd-mm"><p class="text-send"> <?php  echo $chat_res->message; ?> </p></div>
                                          <?php } } ?>

                                       

                                        </div>
                                </div> 

                                        <div class="profile-center col-md-12">   
                                        <div class="box col-md-10">  
                                            <textarea class="form-text" id="msg" name="msg" placeholder="Your message" required></textarea>
                                       
                                    
                                        </div>
                                    
                                    <button type="submit" id="profile-submit"  title="Send" 
                                    class="button-submit" placeholder="Your Message" name="send_email" >Send</button>
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
<script type="text/javascript">

//     $("#msg").keyup(function(event){
//     if(event.keyCode == 13){
//        $("form").submit();
//       // alert(abcd);
//     }
// });
</script>