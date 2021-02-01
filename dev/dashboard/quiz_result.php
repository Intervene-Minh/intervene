<?php
/****
mysql_query(query,connection)
#  include("student_header.php"); 
// p2g  test.
@  SELECT * FROM `questions` WHERE `TestID` = 5
  unset($_SESSION['assessment']);

  header("location:quiz_result.php"); exit;
  $curr_test_id=$_SESSION['assessment']['ses_test_id'];
**/
   include("header.php");
   
   if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}


 $passing_percent=70; // 
  $teacher_id=$_SESSION['ses_teacher_id']; // Login tutor id

 // Total attempted////////////////
 $state_arr=array();
include('inc/sql_connect.php');
  $tutorid=$teacher_id=$_SESSION['ses_teacher_id']; //Login I tutor 
  

  $p2db=p2g();

//// next to action=interview
  if(isset($_GET['action'])&&$_GET['action']=='interview'){

    //echo 'Go interview step';  die;

     $idb=intervene_db();

    $sql="SELECT *
FROM `gig_teachers`
WHERE `id` = '93'  ";
$result=mysqli_query($idb,$sql);  // Teacher data.
$row=mysqli_fetch_assoc($result); 


  // all_state_url==  interview.php
   $_SESSION['ses_curr_state_url']='interview.php'; //
    $_SESSION['ses_prev_state_url']='quiz.php';

  $up=" UPDATE gig_teachers SET all_state_url='interview.php' WHERE id=".$tutorid;
    $result=mysqli_query($idb,$up);
  if($up){
    echo 'interview optn';
    // go to intervewo page 
      header("location:".$_SESSION['ses_curr_state_url']); exit;
  }

 

 

    // die; 
  }





  //  $rowcount=mysqli_num_rows($result);

  $sql=" SELECT * FROM `tutor_tests_logs` WHERE tutor_id=".$tutorid." AND quiz_test_id=".$_SESSION['signup_test_result_id'];

  $get_result=mysqli_query($p2db,$sql);
  $rowcount=mysqli_num_rows($get_result);

     $test_arr=array();
   while($row=mysqli_fetch_assoc($get_result)){
       if($row['status']=='completed'){ // 
      //$test_arr[$row['quiz_test_id']]=$row['pass_status'];
         $test_arr[$row['quiz_test_id']]=$row;
       }
   }
 
 $completed_attempt=count($test_arr); //completed_attempt
  $state_arr['total_attempted']= $completed_attempt;
   /////////////////////////////

   if($state_arr['total_attempted']==1){
     $sql=" SELECT * FROM `tutor_tests_logs` WHERE tutor_id=".$tutorid;
  $get_result=mysqli_query($p2db,$sql);
    $row=mysqli_fetch_assoc($get_result);
    //print_r($row);  // Current test stuts 

     if($row['test_type']=="math"){
      $state_arr['type_math']='inactive';
       $state_arr['type_english']='active';
    }

    if($row['test_type']=="english"){
       $state_arr['type_math']='active';
      $state_arr['type_english']='inactive';
    }


   }if($state_arr['total_attempted']==2){  // Both QUIZ done 

     $state_arr['type_math']='inactive';
    $state_arr['type_english']='inactive';
      $state_arr['both_quiz_done']='yes';
   }


  //print_($state_arr); die;

/////////MEssage ////////////////
   
  







////////////////////////////////////////////////
$con_1= @mysql_connect('localhost', 'intervenedevUser', 'Te$btu$#4f56#');
$pdb=mysql_select_db('ptwogorg_main', $con_1);  //NEW Connections

  // last attempted test id
  $sql_completed=mysql_query("SELECT * FROM tutor_tests_logs WHERE tutor_id= '".$_SESSION['ses_teacher_id']."' AND status='completed' AND quiz_test_id=".$_SESSION['signup_test_result_id']);
  $total_test=mysql_num_rows($sql_completed);
  //echo  $total_test; 
  if($total_test==2){ //2 success attempt

    $row = mysql_fetch_assoc($sql_completed);
    $test_id=$row['quiz_test_id'];
  }elseif($total_test==1){

     $row = mysql_fetch_assoc($sql_completed);
     $test_id=$row['quiz_test_id'];

  }






  //  echo  $test_id;  die;
  //$test_id=5;

// Calculate results mysql_query
 $attempted=mysql_query("SELECT * FROM `tutor_result_logs` WHERE `tutor_id` =".$_SESSION['ses_teacher_id']." AND `test_id` =".$test_id);
   //echo '<pre>',$attempted; die;

      $total_attempted=mysql_num_rows($attempted);

     $correct=0;
        while ($row = mysql_fetch_assoc($attempted)) {
    //echo $row['answer_id'].'==='.$row['attempt_id']; echo '<br/>';
          // attempt_id  answer_id
          if($row['answer_id']==$row['attempt_id']){
            $correct=$correct+1;
          }
   
   ///////Updtate stus of tutor //////////

          

    }

 $test_sql = "SELECT * FROM `tests` WHERE `ID` =".$test_id; //1
   $get_test_result = mysqli_fetch_object(mysqli_query($p2db,$test_sql));
   $passing_percent = $get_test_result->PassingMark;
   // echo 'Tot=='.$total_attempted.'==C'.$correct;
   

  $get_scored=($correct*100)/$total_attempted;
   $get_scored=round($get_scored,2);



 // echo 'Scored', $get_scored.'%';
   // Update for Status //////////
   if($get_scored>=$passing_percent){
    // $Update=mysqli_query($con," UPDATE `gig_teachers` SET all_state_url='$next_state_url' WHERE id=".$_SESSION['ses_teacher_id']);
    // pass
    //  Show other opton for quiz
    // show result or Skip the Step to : interview 
   }elseif($completed_attempt==1&&$get_scored<$passing_percent){  // update rejected. 

    $con = mysqli_connect("localhost","intervenedevUser",'Te$btu$#4f56#',"lonestaar_dev");
    $sql="SELECT *
FROM `gig_teachers`
WHERE `id` = '75'  ";
$result=mysqli_query($con,$sql);
$next_state_url='rejected_application.php';  // quiz_result.php
$next_state_url='quiz_result.php';  // 
// Associative array
$row=mysqli_fetch_assoc($result);
// $Update=mysqli_query($con," UPDATE `gig_teachers` SET all_state_url='$next_state_url' 
//   WHERE id=".$_SESSION['ses_teacher_id']);
  // $row['all_state_url']=='failed')
$Update=mysqli_query($con," UPDATE `gig_teachers` SET all_state_url='$next_state_url',status_from_admin='failed'  
  WHERE id=".$_SESSION['ses_teacher_id']);



// Update Failed step 
   }

  //die; 














               




$record['title']=$qdata['Question']; //actual question
$record['id']=$qdata['ID'];

    




?>

<link type="text/css" href="css/home-page.css" rel="stylesheet" />

<div id="main" class="clear fullwidth">
    <div class="container">

         





        <div class="row">
        <div id="content" class="col-md-12">
                
                <div class="page-content" style="background-image: url('images/wizard-v3.jpg')">
                    <div class="wizard-v3-content">
                        <div class="wizard-form">
                            <div class="wizard-header">
                                <h3 class="heading">Complete your Registration</h3>
                                <p>Fill all form field to go next step</p>
                            </div>
                            <form class="form-register" action="" method="post">
                                <div id="xxform-total">
                                         
                          <div class="steps clearfix"><ul role="tablist"><li role="tab" aria-disabled="false" class="first done" aria-selected="false">
                           <a id="form-total-t-0" href="application.php" aria-controls="form-total-p-0"><div class="title">
                    <span class="step-icon"><i class="zmdi zmdi-account"></i></span>
                    <span class="step-text"  >Application</span>
                  </div></a></li><li role="tab" aria-disabled="false" class="current" aria-selected="true"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><span class="current-info audible"> </span><div class="title">
                    <span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
                    <span class="step-text">Quiz</span>
                  </div></a></li>

                  <li role="tab" aria-disabled="false"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><div class="title">
                    <span class="step-icon"><i class="zmdi zmdi-card"></i></span>
                    <span class="step-text">Interview</span>
                  </div></a></li>

                  <li role="tab" aria-disabled="false"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><div class="title">
                    <span class="step-icon"><i class="zmdi zmdi-card"></i></span>
                    <span class="step-text">Background Checks</span>
                  </div></a></li>
                  

                  <li role="tab" aria-disabled="false"><a id="form-total-t-3" href="#form-total-h-3" aria-controls="form-total-p-3"><div class="title">
                    <span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
                    <span class="step-text">Payment Info </span>
                  </div></a></li><li role="tab" aria-disabled="false"><a id="form-total-t-4" href="#form-total-h-4" aria-controls="form-total-p-4"><div class="title">
                    <span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
                    <span class="step-text">Legal Stuff </span>
                  </div></a></li><li role="tab" aria-disabled="false" class="last"><a id="form-total-t-5" href="#form-total-h-5" aria-controls="form-total-p-5"><div class="title">
                    <span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
                    <span class="step-text">Training </span>
                  </div></a></li></ul></div>




                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        <!-- fdfgggggggggg -->
            <div  class="col-md-3"></div>
            <!-- /#sidebar -->
            <div id="content" class="col-md-12">
                <div id="single_question" class="content_wrap" 
                style="display: inline-block;width: 100%;" >

                    <div class="ct_display clear">                   
                        <form method="post">
                        <?php 
                          if($state_arr['total_attempted']==2){
                            //echo 'both attempted';
                            foreach ($test_arr as $id => $arr) {
                              # code...
                              $arr['pass_status']=($arr['pass_status']=='fail')?'Failed':$arr['pass_status'];
                              //$arr['pass_status']=($arr['pass_status']=='pass')?'Pass':'';
                              
                               if($arr['pass_status']=='fail'){ 
                        
                         mysqli_query($p2db, " DELETE FROM `tutor_tests_logs` WHERE tutor_id=".$_SESSION['ses_teacher_id']);
                         mysqli_query($p2db, " DELETE FROM `tutor_result_logs` WHERE tutor_id=".$_SESSION['ses_teacher_id']);
                         
                         $idb=intervene_db();
                         mysqli_query($idb," DELETE FROM `gig_teachers` where id = '".$_SESSION['ses_teacher_id']."' ");
                         mysqli_query($idb," DELETE FROM `tutor_profiles` where tutorid = '".$_SESSION['ses_teacher_id']."' ");
                         
                               }

                            ?>

                            <h3 class="text-center text-primary"> Quiz Result:-<?=$arr['per_scored']?>% <br>
                         <span>Status- <?=$arr['pass_status']?>  </span> </h3>

                         <?php } ?>
                         <p class="text-right col-md-12"><a href="quiz_result.php?action=interview" class="btn btn-ig btn-success">Next</a></p>



                            <?php
                          }else{  //1 quiz attempted
                        // 
                        $status='Failed';
                        if($get_scored>=$passing_percent){
                           $status='Pass';
                        }
                       

                        ?>
                        
                        <h3 class="text-center text-primary"> Quiz Result:- <?=$get_scored?>% <br/>
                         <span>Quiz Status-  <?=$status?>  </span> </h3>
                          
                        <?php 
                        if($status=='Failed'){ 
                        
                         mysqli_query($p2db, " DELETE FROM `tutor_tests_logs` WHERE tutor_id=".$_SESSION['ses_teacher_id']);
                         mysqli_query($p2db, " DELETE FROM `tutor_result_logs` WHERE tutor_id=".$_SESSION['ses_teacher_id']);
                       
                          $idb=intervene_db();
                         mysqli_query($idb," DELETE FROM `gig_teachers` where id = '".$_SESSION['ses_teacher_id']."' ");
                         mysqli_query($idb," DELETE FROM `tutor_profiles` where tutorid = '".$_SESSION['ses_teacher_id']."' ");
                         
                         

                          ?>
                   <!-- Failed -->
                   
                    <p class="text-center col-md-12 text-danger">Unfortunately, you have not passed the quiz. Please check back with us later for any future opportunities that may arise.</p>

                    <?php }   ?>
                    <?php }   ?>
                         
                        </form>
                    </div>





                    <!--  another quiz options :: if 1 Quiz Passed  -->
                    <?php  if($status=='Pass'){ // 1 Quiz only passed byt applicant?>
                    <section>
                     <p style="border: 1px solid black; padding: 20px;    text-align: center;
    font-size: 17px;">'Congratulations, you have passed one exam. Would you like to take another exam and get certified for another subject? If not, just click “Next�?!’  </p>

                    <div class="inner">
                      <h3 class="text-center">Quiz</h3>

                      

                      <div class="form-row">

                        <div id="content" class="col-md-6 border-right text-center">
                         <?php  
                         // $state_arr
                          if($state_arr['type_math']=="active"){
                         ?>
                         <a href="quiz_start.php?type=math" name="save" class="btn btn-lg btn-primary">Math</a>
                         <?php }elseif($state_arr['type_math']=="inactive"){?>
                         <a href=""  title="Quiz Done" class="btn btn-lg btn-danger">Math</a>

                         <?php  }?>
                            

                        </div>

                        <div id="content" class="col-md-6 border-left text-center">

                          <?php   if($state_arr['type_english']=="active"){ ?>

                         <a href="quiz_start.php?type=english" class="btn btn-lg btn-primary">English</a>
                         <?php }elseif($state_arr['type_english']=="inactive"){?>

                         <a  class="btn btn-lg btn-danger">English</a>
                          <?php  }?>


                        </div>
                        <br/>
                          <!-- Click next : upate state to interview - go to page interview  -->
                        <p class="text-right col-md-12"><a href="quiz_result.php?action=interview"  
                        class="btn btn-ig btn-success">Next</a></p>


                      </div>
                      <br>


                      
                    </div>

                  </section>
                  <?php }?>
                  

                   <!--  another quiz options :: if 1 Quiz Passed  -->

                    <!--  another quiz options -->






                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>
<?php   // print_r($_SESSION);  
//print_r($state_arr); 
//print_r($test_arr);
 //die; ?>