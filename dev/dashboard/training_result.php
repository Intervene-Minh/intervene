<?php
/****
@ Training Results ;
@ Tutor attempt : 
@ get result for completed : Attempts
**/
   include("header.php");
     $page_name='Training Result';


   if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}
 //print_r($_SESSION); 
 $passing_percent=40; // 
  $teacher_id=$_SESSION['ses_teacher_id']; // Login tutor id

 // Total attempted////////////////
 $state_arr=array();
include('inc/sql_connect.php');
  $tutorid=$teacher_id=$_SESSION['ses_teacher_id']; //Login I tutor 
  

  $p2db=p2g();

//// next to action=interview
   # delete old result : mysql_query
   $testId=71;
  $delete_prev="DELETE FROM `training_result_logs` WHERE `tutor_id` =".$tutorid." 
  AND `test_id` =".$testId; //1
  //echo $delete_prev;

   $get_result=mysqli_query($p2db,$delete_prev);




  //  $rowcount=mysqli_num_rows($result);

  $sql=" SELECT * FROM `training_attempts` WHERE tutor_id=".$tutorid;

  $get_result=mysqli_query($p2db,$sql);
  $rowcount=mysqli_num_rows($get_result);

     $test_arr=array();
   while($row=mysqli_fetch_assoc($get_result)){
       if($row['status']=='completed'){ // 
      //$test_arr[$row['quiz_test_id']]=$row['pass_status'];
        // $test_arr[$row['quiz_test_id']]=$row;
        $test_arr[]=$row;
       }
   }
  //print_r($test_arr); die;
 ///////is Pass traning //////
    $sql=" SELECT * FROM `training_attempts` WHERE tutor_id=".$tutorid;
    $sql.=" AND pass_status='pass' ";
    $res=mysqli_query($p2db,$sql);
    $pass_count=mysqli_num_rows($res);

  //echo '==='.$pass_count; // die; :: Show option to apply become a tutor. 

/////////////////////////////

$test_id=5;
// Calculate results mysql_query
 $attempted1=("SELECT * FROM `training_result_logs` 
  WHERE `tutor_id` =".$_SESSION['ses_teacher_id']." AND `test_id` =".$test_id);
   //echo '<pre>',$attempted; die;

    $attempted=mysqli_query($p2db,$attempted1);
       $total_attempted=mysqli_num_rows($attempted); 

     $correct=0;
        while($row = mysqli_fetch_assoc($attempted)) {
  //  echo $row['answer_id'].'==='.$row['attempt_id']; echo '<br/>';
          // attempt_id  answer_id
          if($row['answer_id']==$row['attempt_id']){
            $correct=$correct+1;
          }    
               }  /// while


   // echo 'Tot=='.$total_attempted.'==C'.$correct;
  $get_scored=($correct*100)/$total_attempted;
   $get_scored=round($get_scored,2);
 // echo 'Training Score ===', $get_scored.'%';  die; 





   // Update for Status //////////
   $msg=null;
if(isset($_POST['traning_status']) ){
  // print_r($_POST); die;
  // become a tutor. 
   // all_state  , training
    $up=" UPDATE gig_teachers SET training='2',all_state='yes' WHERE id=".$_SESSION['ses_teacher_id'];
    $result=mysql_query($up);
    $error='Information sent.!';
    //header("Location:".$_SERVER['PHP_SELF']); exit;

  }


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
                                         
                          <?php  include("training_stage.php"); ?>




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
                  <!-- Traning Result  -->
                  <div class="ct_display clear">                   
                        <form method="post">
                        <?php if(!empty($error)){ ?>
                         <p class="text-success text-center"> <?=$error?></p>
                         <?php   }//if(!empty($$msg)){
                      // $pass_count=0;
                          ?>



                         <?php if($pass_count>=1){?>
                        <p class="text-center">
                         <button type="submit" name="traning_status" 
                         class="btn btn-success btn-lg">Finish Application</button>
                          </p>
                          <?php  } //if($pass_count>=1){?>


                        <?php
                         // print_r($test_arr);  
                         foreach ($test_arr as $key => $arr) {
                           # code...
                         

                        ?>
                                                
                        <h3 class="text-center text-primary"><?=($key+1)?>-Result:-<?=$arr['per_scored']?>% <br>
                         <span>Status-<?=$arr['pass_status']?></span> </h3> <br/>
                         <?php } 
                         // 
                          if(count($test_arr)>0){

                          ?>
                          <p class="text-center">
                         <a class="btn btn-danger btn-xs" href="training.php">Go Back, Training</a> </p>
                         <?php }?>
                          
                                                                     
                        </form>
                    </div>

                  <!-- Traning Result  -->





                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>
<?php   // print_r($_SESSION);  
 
//print_r($test_arr);
 //die; ?>