<?php 
/**
@traning Start:
**/
 include("header.php");
 if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

 ///////////////////
$curr_test_id= $_SESSION['assessment']['assesment_id']; 
$created = date('Y-m-d H:i:s');
$teacher_id=$_SESSION['ses_teacher_id']; // Login tutor id

 //print_r($_SESSION);
$con_1= @mysql_connect('localhost', 'ptwogorg_prod', 'aE&ZidJX)8bl');
$pdb=mysql_select_db('ptwogorg_main', $con_1);  //NEW Connections








 //valid test state///
   // if(!isset($_GET['type'])&&!isset($_GET['in_quiz'])){
   //  exit('Page not found.!');
   // }


if(isset($_GET['in_quiz'])){
  // in_quiz ::in_process quiz id
 $test_id=$_GET['in_quiz'];
//echo   '===process';
}elseif(isset($_GET['type'])){
   $_SESSION['ses_quiz_type']='Training';
   if($_GET['type']=="english")
    $test_id=6;  // 
  if($_GET['type']=="math")
    $test_id=5;  // 
 }



$test_id=71;  // 5, Test check

 
////////selected test or in_process test id ///

 $sql_select_quest="SELECT * FROM `questions` WHERE `TestID` =".$test_id;
 $results_data=mysql_query($sql_select_quest); // type=math
                    
                 $_SESSION['assessment'] = array();
                    $_SESSION['assessment']['ses_test_id'] =$test_id; 
                    $_SESSION['assessment']['assesment_id'] =$test_id;   // $assesment['assessment_id'];
                    $_SESSION['assessment']['qn_list'] = array();


     while ($row = mysql_fetch_assoc($results_data)) {
  //echo $row['Question']; echo '<br/>';
   $_SESSION['assessment']['qn_list'][] = $row['ID'];

    }


      $cur_quiz_id=$_SESSION['assessment']['ses_test_id']; // Test ID 
   // get question attempted ::   attempt_number
      $attempted=3;
    $attemptedxx="SELECT * FROM `training_result_logs` WHERE `tutor_id` =".$teacher_id." AND `attempt_number` =".$test_id; # Stop attempt number ..

    $attempted="SELECT * FROM `training_result_logs` WHERE `tutor_id` =".$teacher_id." AND `test_id` =".$test_id;
     $total_attempted=mysql_num_rows(mysql_query($attempted));
                     $number=($total_attempted>0)?$total_attempted:0;


          
                    
          
                    //if ($resume_result['last_qn_num'] > 0) {  // Resume
                     if($total_attempted>0){
                        ?>
                        <div class="align-center col-md-12">
                
                <div style=" width:auto;" title="">
             
                        <a href="training_test.php?pos=<?=$number ?>" class="btn btn-green"><button class="form_button submit_button" style="height: 200px;width: 200px;font-size: 30px;">Resume</button></a>
                        </div></div>
            
                    <?php } else {   // START NEW   ?>
                      <div class="align-center col-md-12">
           
                        <a href="training_test.php" class="btn btn-green">
                            <button class="form_button submit_button"
                                                                                    style="height: 200px;width: 200px;
                                                                                    font-size: 30px;">START</button></a>
                                                                                    </div>

                                                                                   
                    <?php
                                          
                    }
              //  } else {


 // header("location:training_test.php"); exit;
 //print_r($_SESSION);
 ?>