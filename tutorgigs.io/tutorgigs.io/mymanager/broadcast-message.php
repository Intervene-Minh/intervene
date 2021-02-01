<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * ***/


include("header.php");
$from_email='support@tutorgigs.io'; 
//echo '===Testing';
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


function send_a_email($email,$to,$message,$f_name=''){
$subject = "Chat Message- Tutorgigs.io";// Message
 //$to_main='rohit@srinfosystem.com';
$to_main=''; // all tutor email goes to : tutor in bcc
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$emailList=$to;
// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
  $headers.= "Bcc: $emailList\r\n";
//$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";

 if(mail($to_main,$subject,$message,$headers)) return true;
 else return false;
   //if(mail($to_main,$subject,$message,$headers)) echo 'sentX '.$emailList; die; 

}




 /// Bulk Email ///
// $message=" Dear tutor , <br/>
// your getting mesag
// it is likely an issue with slow internet connection on your end. We have tested and fixed internet connectivity issues at all of the schools at this point. Here are a couple of step to take. First: chec
// Thank you <br/> <br/>   ";

 

  //echo $message; die; 




if (isset($_POST['msg_tutor']) || isset($_POST['msg'])) {

  //  print_r($_POST); die;  


  $tutor= $_POST['tutor'];

   $msg =trim($_POST['msg']);
   $sender_type = "admin";
   $datetm = date('Y-m-d H:i:s');
   $total_tutor=count($_POST['tutor']);

  $can_insert=0;

  if($total_tutor > 0 && !empty($msg)){
    $can_insert=1;
      //1
  }else{ $error='';
   $error.="Fields should not be empty.<br/>";

     if(!isset($_POST['tutor']))
       $error.="Please a select tutor.<br/>";
      if(empty($msg))
         $error.="Enter your message.<br/>";

     
    // print_r($_POST); die; 
     //  if(!empty())
     
      // mesage case 
  }


     if($can_insert==1){
      $error="Success, Message sent.<br/>";
       $msg_text=$msg =addslashes($_POST['msg']);
       $emails_arr=array();
  foreach($_POST['tutor'] as $tutor){
    $tutor_det=mysql_fetch_assoc(mysql_query("SELECT id,email FROM `gig_teachers`  WHERE id='$tutor' "));
    $emails_arr[]=$tutor_det['email'];
   $insert_query = "INSERT INTO `inbox` ( `sender_id` , `receiver_id` , `message` , `created_at`, `sender_type` ) 
           VALUES ('0', '".$tutor."', '$msg' ,'$datetm','$sender_type')";

   $data = mysql_query($insert_query)or die(mysql_error());
  $last_insert_id =mysql_insert_id();
  $notify_query=mysql_query("SELECT * from gig_teachers WHERE id='$tutor'");
  $notify_res=mysql_fetch_assoc($notify_query);
  $notify_all=$notify_res['notify_all'];
  $notify_msg=$notify_res['notify_msg'];
if($notify_all== "yes" OR $notify_msg=="yes"){
  $msg_query1= mysql_query("INSERT INTO notifications (sender_id, receiver_id, type, sender_type,type_id, info, created_at,updated_at) VALUES('0','$tutor','message','admin','$last_insert_id', '$msg_text','$datetm','$datetm')");
 
      }
      
     
     // header("Location: ".$_SERVER['PHP_SELF']); exit; 
    } 
    /////////////// 
    $msg_text=nl2br($_POST['msg']);// Orginal message at email.

    $quote='"A great tutor can inspire hope, ignite the imagination, and instill a love of learning."';
    $msg_text.= "
  <br/><br/>Warm Regards,<br/><br/>
  Tutor Gigs Support Team<br/>
  <img alt='' src='https://tutorgigs.io/logo.png'> <br/><br/>
  <span style=' color: blue;font-size: 14px;
    font-style: italic;font-weight: bold;'>".$quote."</span><br/>

  <span style='font-style: italic;
    font-size: 10px;
    color: blue;'>by Brad Henry</span><br/><br/>
  <span style='color: red;
    font-weight: bold;'>(832) 590-0674</span><br/>


  ";
     unset($_POST);
     // echo $msg_text ; die; 
    $to_email=implode(',',$emails_arr);
     if(send_a_email($from_email,$to=$to_email,$msg_text,$f_name)){
       
    $info= 'Sent';  //  die;
     }else{
      $info= 'Not Sent'; 
     }
    ////Send Email///



   }
   
}

?>



<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i> Broadcast Message</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
                          <!-- profile-top col-md-12 -->
                      <div class=""> 
                           <?php  if(isset($error)&&!empty($error)){?>
                                    <div class="profile-item alert alert-info text-left">
                                  <?=$error;?>            
                           </div> <?php }?>                      
                                </div>


                       <div class="add_question_wrap clear fullwidth">
                <p>
                  <input type="checkbox" value="yes"  id="notify_all" name="notify_all" style="vertical-align: sub;">
                  <label for="question_public" style="font-weight:bold;">Select All</label>
                </p>  </div>




                      









                            
                            <?php  
                            //   $curr_time= date("Y-m-d H:i:s"); 
                            //  $in_sec= strtotime($ses_start_time) - strtotime($curr_time);///604800  
 
                            //  $sdate=date_format(date_create($ses_start_time), 'F d,Y');
                            // $at_time=date_format(date_create($ses_start_time), 'h:i a');

                            // tutor[]
                            ?>
                                                    
               <div class="add_question_wrap clear fullwidth"
                style="display:inline-block;">
                 <?php
                        
                           //////////////////
      
                             if(isset($_POST['tutor']))
                                 $st_arr=$_POST['tutor'];// save#change
                               // $sql=" SELECT * FROM `gig_teachers` WHERE notify_all= 'yes' AND all_state='yes' ";
                               $current_role=$_SESSION['login_role'];
                                $sql=" SELECT * FROM `gig_teachers` WHERE 1 AND all_state='yes' AND `created_by` = $current_role ";



                               #  $select_query = mysql_query("SELECT * FROM gig_teachers ");
                             $select_query = mysql_query($sql);
                               $total=mysql_num_rows($select_query);
                              // echo '==total--'.$total;

                                        
                                        ?>  




                            <div class="row">
                            <?php 
                             while($row=mysql_fetch_assoc($select_query)){
                              $user=$row['email'];
                              //  lname  email f_name
                              $tutor_name=$row['f_name'];

                              if(!empty($row['lname']))
                                $tutor_name.=' '.$row['lname'];

                           $check=(in_array($row['id'],$_POST['tutor']))?'checked':null;
                             // $check='checked';

                             ?>
             <div class="left col-md-6">
                <p>
                <input type="checkbox" <?=$check?> value="<?=$row['id'];?>"
                  class="case" name="tutor[]" style="vertical-align: sub;">
                <label for="smart_prep" style="font-weight:bold;"><?=$tutor_name.'&nbsp;('.$user.')'?></label>
                 </p> 
                 </div> <?php }?>
                   
              

                  <!--  <div class="left col-md-4">
                <p>
                <input type="checkbox" value="yes" 
                 class="case" name="tutor[]" style="vertical-align: sub;">
                <label for="smart_prep" style="font-weight:bold;">rahul221@srinfosystem.com</label>
                 </p> 
                 </div> -->


                 </div>






    
                             
                                
                               
                    
                          <div id="textarea" style="display: block"> 
                                   <textarea class="form-text" required name="msg" placeholder="Your message" id="msg"><?=$_POST["msg"] ?></textarea>   
                                </div> 
                                <input type="submit" name="msg_tutor" title="Send" 
                                 id="" class="form_button submit_button" value="Submit" /> 								
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
<style>
.form-text {
    border: 0px solid #dddddd;
    float: left;
    width: 93%;
    line-height: 20px;
    padding: 10px 20px;
    color: #000;
    resize: none;
}
.form_button {
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
</style>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->

<script type="text/javascript">
//     // enter submit text
//     $("#msg").keyup(function(event){
//     if(event.keyCode == 13){
//        $("form").submit();
//       // alert(abcd);
//     }
// });
</script>
<script>
//select all checkboxes
$("#notify_all").change(function(){  //"select all" change 
  var status = this.checked; // "select all" checked status
    //alert(status);
  $('.case').each(function(){ //iterate all listed checkbox items
    this.checked = status; //change ".checkbox" checked status
  });
});



$('.case').change(function(){ //".checkbox" change 
  //uncheck "select all", if one of the listed checkbox item is unchecked
  if(this.checked == false){ //if this item is unchecked
    $("#notify_all")[0].checked = false; //change "select all" checked status to false
  }
  
  //check "select all" if all checkbox items are checked
  if ($('.case:checked').length == $('.case').length ){ 
    $("#notify_all")[0].checked = true; //change "select all" checked status to true
  }
});


</script>


<?php include("footer.php"); ?>
