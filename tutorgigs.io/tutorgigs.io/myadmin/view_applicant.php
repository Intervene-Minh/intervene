<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * @ Edit tutor Detail..
 * ***/


include("header.php");
function quiz_result($p2db,$getid,$test_id){
    //$test_id=$tdata['quiz_1_id']; //Default 

$attempted=("SELECT * FROM `tutor_result_logs` WHERE `tutor_id` =".$getid." AND `test_id` =".$test_id);
 $get_result=mysqli_query($p2db,$attempted);
  $quiz_1=array();
   $quiz_2=array(); 
  $total_attempted=mysqli_num_rows($get_result);
     $correct=0;
        while ($row = mysqli_fetch_assoc($get_result)) {
     //echo $row['attempt_id']; echo '<br/>';
          // attempt_id  answer_id
          if($row['answer_id']==$row['attempt_id']){
            $correct=$correct+1;
          }      
    }
 
  $get_scored=($correct*100)/$total_attempted;
  return  $get_scored=round($get_scored,2);



 } 

////////////////////

echo  'Scored in Quiz iff attempted ';
// quiz_1_id  quiz_1_status
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
lname
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
 $getid=0;

 /////data//////////
if(isset($_GET['tid'])){
    $getid=$_GET['tid'];
    $query3 = mysql_query(" SELECT * FROM gig_teachers WHERE id='$getid' ") or die(msyql_error()); 
    $tdata=mysql_fetch_assoc($query3);
    @extract($tdata);
    // print_r($tdata);


   // echo $email.'ghghjgjjjghj';
   $result =mysql_query("SELECT * FROM tutor_profiles WHERE tutorid='$getid'");
$data=mysql_fetch_array($result);
$edit = unserialize($data['profile_1']);


}
/////////XCalculate Quiz 1 and 2 ////////

include('inc/sql_connect.php'); 
   $p2db=p2g();

 if($tdata['quiz_1_status']=='completed'&&$tdata['quiz_1_id']>0){

  $test_id=$tdata['quiz_1_id']; //Default 
  $quiz_1_score=quiz_result($p2db,$getid,$test_id);
}


///Quiz 2 result 
if($tdata['quiz_2_status']=='completed'&&$tdata['quiz_2_id']>0){
  $test_id=$tdata['quiz_2_id']; //Default 
  $quiz_2_score=quiz_result($p2db,$getid,$test_id);
}
 
 //print_r($quiz_1_score); 
  
  // print_r($quiz_2_score); 




 
  
 //die;




   
  //die;


?>
<script type="text/javascript">
    
    ////////////////////
    $(document).ready(function () {
         $('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
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
                        <h3>Details
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
                                    <h4 class="title">View Applicant Details</h4>
                                    <div class="box col-md-12">
                                        <div class="left col-md-12">
                                            <label for="firstname">First Name:</label>
                                            <input type="text" id="f_name" 
                                                   required="" class="required" readonly name="f_name" value="<?=(isset($f_name))?$f_name:NULL?>"/>
                                            <div class="notif">*First Name</div>
                                        </div>
                                        </div>
                                        <div class="box col-md-12">
                                        <div class="right col-md-12">
                                            <label for="lastname">Last Name:</label>
                                            <input type="text" id="lname" 
                                                   class="required" name="lname" readonly value="<?=(isset($lname))?$lname:NULL?>"/>
                                            <div class="notif">*Last Name</div>
                                        </div>
                                        </div>



                                        <div class="box col-md-12">
                                        <div class="left col-md-12">
                                            <label for="email">Email:</label>
                                        <input type="email" id="email"
                                       name="email" class="required" value="<?=(isset($email))?$email:NULL?>" readonly/>
                                            <div class="notif">*Enter Valid Email</div>
                                        </div>
                                        </div>


                                        <div class="box col-md-12">
                                        <div class="right col-md-12">
                                            <label for="phone">Phone:</label>
                                <input type="text" class="required" readonly  required="" id="phone" name="phone" value="<?=(isset($phone))?$phone:NULL?>"/>
                                            <div class="notif">*Please enter your Telephone Number(Home,Cell)</div>
                                        </div>
                                        </div>
                                        <div class="box col-md-12">
                                        
                                        <div class="right col-md-12">
                                            <label for="Status">Status:</label>
                                            <select name="status" style="background-color: aquamarine;" readonly>
                                               
                              <option value="1" <?=($status==1)?"selected":NULL?> >Active</option>
                            <option value="2"  <?=($status==2)?"selected":NULL?> >Suspended</option>
                                </select>
                             
                                        </div>
                                        </div>
                                        <div class="clearnone">&nbsp;</div>
                                        <div class="box col-md-12"> 
 
                                 <div class="right col-md-12">
                                            <label for="phone">Do you have a computer or tablet and reliable internet access?</label>
                                            <input type="text" readonly name="is_computer" 
                                             value="<?php echo $edit['is_computer'];?>" >
                            
                                            
                                        </div> 
                                        <div class="box col-md-12"> 
 
                                 <div class="right col-md-12">
                                            <label for="phone">How did you hear about us?</label>
                                            <input type="text" readonly required id="phone" class="required" name="hear" value="<?php echo $edit['hear'];?>" />
                                            
                                        </div> 
                                        </div>


                                        <div class="box col-md-12"> 
                                        
                                         <div class="right col-md-12">
                                            <label for="phone">When would you like to get started Tutoring?</label>
                                 
                                            <input type="text" readonly id="birth_date"  name="started_date" value="<?php echo $edit['started_date'];?>"/>
                                        </div> 
                                      </div>

                                       <!-- QUIZ Result  -->
                                       <?php  if($tdata['quiz_1_status']=='completed'&&$tdata['quiz_1_id']>0){ ?>
                                       <div class="box col-md-12"> 
                                        
                                         <div class="right col-md-12">
                                            <label for="phone">Quiz1 Result (%)</label>
                                 
                                            <input  readonly=""  value="<?=$quiz_1_score?>%" >
                                             
                                        </div> 
                                      </div>
                                      <?php } ?>


                                      <?php  if($tdata['quiz_2_status']=='completed'&&$tdata['quiz_2_id']>0){ ?>
                                       <div class="box col-md-12"> 
                                        
                                         <div class="right col-md-12">
                                            <label for="phone">Quiz 2 Result(%)</label>
                                 
                                            <input  readonly=""  value="<?=$quiz_2_score?>%" >
                                             
                                        </div> 
                                      </div>
                                      <?php } ?>







                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <a class=" button-submit" href="applicant-list.php">Back</a>
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