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

  $sql=" SELECT * FROM `tutor_tests_logs` WHERE tutor_id=".$tutorid." AND quiz_test_id = ".$_SESSION['test_exam_result_id'];

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
  $sql_completed=mysql_query("SELECT * FROM tutor_tests_logs WHERE tutor_id= '".$_SESSION['ses_teacher_id']."' AND status='completed' AND quiz_test_id = ".$_SESSION['test_exam_result_id']);
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

    $con = mysqli_connect("localhost","intervenedevUser", 'Te$btu$#4f56#',"lonestaar_dev");
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
            
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>
        <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Test Result</h3>
                    </div>		<!-- /.ct_heading -->
                    
                    <div class="ct_display clear" style="text-align: center;padding-bottom: 103px;">                   
                        
                     
                            <?php
                         
                        // 
                        $status='Failed';
                        if($get_scored>=$passing_percent){
                           $status='Pass';
                        }

                        ?>
                        
                        <h3 class="text-center text-primary"> Mark :- <?=$get_scored?>% <br/>
                         <span>Quiz Status-  <?=$status?>  </span> </h3>
                          
                        <?php if($status=='Failed'){ 
                          // Update :: 
                          // Stay on page : quiz_result.php if Failed


                          ?>
                   <!-- Failed -->
                   
                    <p class="text-center text-danger">Unfortunately, you have not passed the quiz. Please check back with us later for any future opportunities that may arise.</p>

                    <?php } else {  ?>
                     <div class="col-md-12">
                     <?php if(((isset($_SESSION['cer_complete']) && $_SESSION['cer_complete'] == 1) || !isset($_SESSION['manage_tutor_sess_id']))) { ?>
                    
                   <div class="col-md-12" style="text-align: center">   <a href="Jobs-Board-List.php" class="btn btn-ig btn-primary" >GO TO JOB BOARD</a></div>
                                <?php } else { ?>
                      <div class="col-md-12" style="text-align: center">   <a href="manage_claim_tests.php?ses_id=<?php echo $_SESSION['manage_tutor_sess_id'];?>" class="btn btn-ig btn-primary" >GO TO Exam & Certification Page</a></div>
            </div>                  
 <?php } ?>
                           <?php }   ?>
                  
                        
                    </div>
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