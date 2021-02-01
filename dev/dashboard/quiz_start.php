<?php 
/* @Assign Questions
//print_r($_SESSION['assessment']);


// type=english
 

 // Test cateogry   type=english
  //$test_id=5; // math
  // at -inter view step ////STATE validate
  // if($_SESSION['ses_curr_state_url']!='quiz.php'){
  // header("location:".$_SESSION['ses_curr_state_url']); exit; // go, state page wherver
  // }

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
$con_1= @mysql_connect('localhost', 'intervenedevUser', 'Te$btu$#4f56#');
$pdb=mysql_select_db('ptwogorg_main', $con_1);  //NEW Connections







/*
 //valid test state
   if(!isset($_GET['type'])&&!isset($_GET['in_quiz'])){
    exit('Page not found.!');
   }


if(isset($_GET['in_quiz'])){
  // in_quiz ::in_process quiz id
 $test_id=$_GET['in_quiz'];
//echo   '===process';
}elseif(isset($_GET['type'])){
   $_SESSION['ses_quiz_type']=$_GET['type'];
   if($_GET['type']=="english")
    $test_id=6;  // 
  if($_GET['type']=="math")
    $test_id=5;  // 
 }
*/

 if(!isset($_GET['test_id']) && !isset($_GET['in_quiz'])){
    exit('Page not found.!');
   }
   if(isset($_GET['in_quiz'])){
      // in_quiz ::in_process quiz id
     $test_id=$_GET['in_quiz'];
   }
   else
   {
      $test_id = $_GET['test_id'];
   }
 
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
   // get question attempted 
    $attempted="SELECT * FROM `tutor_result_logs` WHERE `tutor_id` =".$teacher_id." AND `test_id` =".$test_id;
     $total_attempted=mysql_num_rows(mysql_query($attempted));

     
                $test_id=70;

                    /// if Already attempted. ///////////////
                     // TEST Exist of USER 

                    $resume_result = mysql_fetch_assoc(mysql_query('SELECT MAX(num) as last_qn_num FROM students_x_assesments WHERE '
                                    . 'assessment_id = \'' . $test_id . '\' AND '
                                    . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
                                    . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' AND '
                                    . 'school_id = \'' . $_SESSION['schools_id'] . '\' '));



                   // $number=($total_attempted>0)?$total_attempted-1:0;
                     $number=($total_attempted>0)?$total_attempted:0;


          
                    
					
                    //if ($resume_result['last_qn_num'] > 0) {  // Resume
                     if($total_attempted>0){
                        ?>
                        <div class="align-center col-md-12">
                
                <div style=" width:auto;" title="">
						 
                        <a href="start_test.php?pos=<?=$number ?>" class="btn btn-green"><button class="form_button submit_button" style="height: 200px;width: 200px;font-size: 30px;">Resume</button></a>
                        </div></div>
						
                    <?php } else {   // START NEW   ?>
                      <div class="align-center col-md-12">
					 
                        <a href="start_test.php" class="btn btn-green">
                            <button class="form_button submit_button"
                                                                                    style="height: 200px;width: 200px;
                                                                                    font-size: 30px;">START</button></a>
                                                                                    </div>

                                                                                   
                    <?php
																					
                    }
              //  } else {


 // header("location:start_test.php"); exit;
 //print_r($_SESSION);
 ?>