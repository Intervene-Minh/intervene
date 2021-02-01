<?php
/****
mysql_query(query,connection)
#  include("student_header.php"); 
// p2g  test.
@  SELECT * FROM `questions` WHERE `TestID` = 5
@training Tes attempt. 
**/
   include("header.php");
$per_passing=40;  // From TestID
$move_next_url='training_result.php';//default

//print_r($_SESSION);
function quiz_score($p2db,$getid,$test_id){
    //$test_id=$tdata['quiz_1_id']; //Default 

$attempted=("SELECT * FROM `training_result_logs` WHERE `tutor_id` =".$getid." AND `test_id` =".$test_id);
 $get_result=mysqli_query($p2db,$attempted);
 // $quiz_1=array();
  // $quiz_2=array(); 
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

   ///////////
   // print_r($_SESSION);  // die;
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}


   include('inc/sql_connect.php');
  $tutorid=$teacher_id=$_SESSION['ses_teacher_id']; 
  $curr_test_id=$_SESSION['assessment']['ses_test_id'];

  $p2db=p2g();
  $quiz_type=$_SESSION['ses_quiz_type']='Training'; 


  //  $rowcount=mysqli_num_rows($result);
 // $sql2=" SELECT * FROM `training_result_logs` WHERE qn_id=29";
 // $get_result=mysqli_query($p2db,$sql2);

 // $row2=mysqli_fetch_assoc($get_result);
 //  print_r($row2);
 //  die;




   $sql="SELECT * FROM `training_attempts`
     WHERE `tutor_id` =".$tutorid." AND `quiz_test_id` =".$curr_test_id; //1

     

     

   $get_result=mysqli_query($p2db,$sql);
   $rowcount=mysqli_num_rows($get_result);
  //echo  'row found='.$rowcount; //die;


///////Update Attempt number ////
  // if($rowcount==0){  // $created
  //     $sql="INSERT INTO training_attempts SET tutor_id='$tutorid',test_type='$quiz_type',
  //     quiz_test_id='$curr_test_id',status='process',assigned_date='$created' ";
  //      $a=mysqli_query($p2db,$sql);

  // }else{  //Update
    
  //   $up="UPDATE training_attempts SET status='in_process' WHERE tutor_id='$tutorid' AND quiz_test_id=".$curr_test_id;
  //   $ax=mysqli_query($p2db,$up);

  // }
///////Update Attempt number ////


     //echo '<pre>',$sql;   die;


//////////////////////////
if ($_GET['pos']) {
    $qn_id = $_SESSION['assessment']['qn_list'][$_GET['pos']];
  //  echo $qn_id; die;
    $prev = ($_GET['pos'] - 1);
    $next = ($_GET['pos'] + 1);
} else {
    $qn_id = $_SESSION['assessment']['qn_list'][0];
    $prev = -1;
    $next = 1;
}
if(!$_GET['pos'] || $_GET['pos'] == 0) {
    $current_qn = 1;
}else{
    $current_qn = $_GET['pos'] + 1;
}

////////Save Complete //////////////
if($_POST['complete']) {
   
  //print_r($_POST);die;
  ##################

  $pass_status=null;
  //echo '==='.$curr_test_id; die; 
   $get_scored=quiz_score($p2db,$getid=$tutorid,$test_id=$curr_test_id);

   //echo $get_scored.'==Scored'; die; 
////////////////////////
   //$get_scored=45;
   $_SESSION['ses_curr_scored']=$get_scored;



    if($get_scored>=$per_passing){
       $pass_status='pass';
    }else{
       $pass_status='fail';
    }

   $up="UPDATE training_attempts SET status='completed',per_scored='$get_scored',
   per_passing='$per_passing',pass_status='$pass_status' 
   WHERE tutor_id='$tutorid' AND quiz_test_id=".$curr_test_id;

   // new attempt ///
    $insert_attempt="INSERT INTO  training_attempts SET quiz_test_id='$curr_test_id',tutor_id='$tutorid',status='completed',per_scored='$get_scored',
   per_passing='$per_passing',pass_status='$pass_status' " ;
   /////// auto become tutor if pass_status=='pass'///

   if($pass_status=='pass'){

    $_SESSION['is_applicant_tutor']=1; // popup
    $_SESSION['ses_curr_state_url']='home.php';// ses_total_pass

    $_SESSION['ses_access_website']='yes';


    unset($_SESSION['ses_total_pass']);
    $move_next_url='home.php';

   }

   //echo  '<pre>', $insert_attempt; die; 
    $ax=mysqli_query($p2db,$insert_attempt);
  ////////////////////////////// 
      $idb=intervene_db();
      // get total pass in intervene 
      $sql="SELECT * FROM `gig_teachers` WHERE id=".$tutorid;
      $result=mysqli_query($idb,$sql); 
      $tutor_det=mysqli_fetch_assoc($result);
      
    
      if($pass_status=='pass'){
        $pass_count=$tutor_det['total_pass_quiz']+1;
      }else{
        $pass_count=$tutor_det['total_pass_quiz']; 
        //print_r($pass_count);  die;
      }
     $sql="INSERT INTO int_training_logs SET tutor_id='$tutorid',test_type='$quiz_type',
      quiz_test_id='$curr_test_id',status='completed',assigned_date='$created' ";   
             $result=mysqli_query($idb,$sql);  // Training in Intervene


    $traning_test_score=$get_scored;
   // if  quiz 1 have pass then -- go to  quiz_2_status
  $up=" UPDATE gig_teachers SET traning_status='completed',
  traning_test_score='$traning_test_score' WHERE id=".$tutorid;
  if($pass_status=='pass'){// all_state==yes
    $up=" UPDATE gig_teachers SET traning_status='completed',
  traning_test_score='$traning_test_score',all_state='yes',notify_all='yes',all_state_url='home.php' WHERE id=".$tutorid;
  }
  
    $result=mysqli_query($idb,$up);       


   //////////Clear all quiz sesson////////////
   unset($_SESSION['assessment']);
   unset($_SESSION['ses_quiz_type']);
   /// Move  $move_next_url='home.php';
  header("Location:".$move_next_url); exit;
}

/////////Completed///////

 $show_qn_id=$qn_id;

  //echo $show_qn_id,'===========qnid show_qn_id';
////////////////////////////







 

///////////Saave////////////
if ($_POST['next']) {

    /// Check Previous attempte question. 

  //print_r($_POST);  // die;
$con_1= @mysql_connect('localhost', 'ptwogorg_prod', 'aE&ZidJX)8bl');
$pdb=mysql_select_db('ptwogorg_main', $con_1);  //NEW Connections


$given_qn = $_POST['question_id']; // quedtion
//////////////////////////////
  // Question Past Data. 

 $sql=" SELECT * FROM `questions` WHERE `ID` =".$given_qn;
 $qreal= mysql_fetch_assoc(mysql_query($sql));
  // actual_ans_id  attempt_opn_id
 $actual_ans_id=$qreal['AnswerID']; // qreal data
  $optiona_arr=explode(',', $qreal['OptionIDs']);

  //echo '<pre>'; print_r($optiona_arr); die; 


  // $attempt_opn_id=101;  // Seleccted options by Tutor 
   $choose_opn_number=$_POST['answer']; // if not select then - can not submit 
  $attempt_opn_id=$optiona_arr[$choose_opn_number];


 // OptionIDs  AnswerID

      //if Option is not selected. 
    if($_POST['answer'] == "0" || $_POST['answer'] > 0) { 

    $flip_array = array_flip($_SESSION['assessment']['qn_list']);

    $given_qn = $_POST['question_id']; // quedtion
    $answer = $_POST['answer']; //answer

    $gievn_questions = mysql_fetch_assoc(mysql_query("SELECT * FROM `questions` WHERE id = " . $given_qn));

    //print_r($gievn_questions); die;
    $gievn_questions_ans = unserialize($gievn_questions['answers']);

    $qn_num_result = mysql_fetch_assoc(mysql_query("SELECT num FROM `training_result_logs` WHERE qn_id = \"" . $given_qn."\" "
            . "AND test_id = \"".$cur_quiz_id."\" "));



    $num = $qn_num_result['num']; # TEST

    $correct = $gievn_questions_ans[$answer]['corect']; // corrected
    $distractor = $gievn_questions_ans[$answer]['explain']; 
    $distractor = $distractor ? $distractor : 0;
    $pos = $flip_array[$given_qn] + 1;

   // $gievn_questions = mysql_fetch_assoc(mysql_query("SELECT grade_id FROM `assessments` WHERE id = " . $cur_quiz_id));

  

    /////////////////Insert into>p2g Record.
    // training_result_logs training_result_logs mysql_query
     $teacher_id=$_SESSION['ses_teacher_id']; // Login tutor id
     //$cur_quiz_id=$show_qn_id; //  ses_test_id
   // actual_ans_id  attempt_opn_id


      $cur_quiz_id=$_SESSION['assessment']['ses_test_id']; // Test ID 
    // $given_qn
   //if old quiz exist 
     

      $delete_prev=mysql_query("DELETE FROM `training_result_logs` WHERE `qn_id`='$given_qn' AND `tutor_id` =".$teacher_id." AND `test_id` =".$_SESSION['assessment']['ses_test_id']); //1

       $q_attempted=mysql_query("SELECT * FROM `training_result_logs` WHERE `qn_id`='$given_qn' AND `tutor_id` =".$teacher_id." AND `test_id` =".$_SESSION['assessment']['ses_test_id']); //1

      //echo 'exist ='.mysql_num_rows($q_attempted);  die;
 



   $add_sql=mysql_query('INSERT INTO training_result_logs SET '
            . 'tutor_id = \'' . $teacher_id . '\' , '
            . 'test_id = \'' . $_SESSION['assessment']['ses_test_id'] . '\' , '
            . 'qn_id = \'' . $given_qn . '\' , '
            . 'num = \''.$num.'\' , '
            . 'answer_id = \'' . $actual_ans_id . '\' , '
             . 'attempt_id = \'' . $attempt_opn_id . '\' , '
            . 'answer = \'' . $answer . '\' , '
            . 'corrected = \'' . ($correct ? $correct : 0) . '\' , '
            . 'created = \'' . $created . '\' ');


   // $add=" INSERT INTO `training_result_logs` (`tutor_id`, `test_id`, `student_id`, `qn_id`, `num`, `answer`, `corrected`, `objective_type`, `distractor`, `class_id`, `school_id`, `grade_id`) VALUES ('', '334', '3', '33', '3', '', '', NULL, '0', '', '', '') ";

  // $add_sql=mysql_query($add);



      // echo '<pre>', $add_sql; die;

    //  $add="";


    //if($add_sql) echo 'Aadded'; else echo 'Not';

    //  die;
    //echo $correct;
    //print_r($gievn_questions_ans);  
    header('Location:training_test.php?pos=' . $next);
    exit;
    }else{
        $error = 'Please select an answer!';
    }
    
}






   // if Result Saved////
// $stored_res = mysql_query('SELECT * FROM students_x_assesments WHERE '
//         . 'student_id = \'' . $student_id . '\'  AND '
//         . 'qn_id = \'' . $qn_id . '\'  '
//         . 'AND assessment_id = \'' . $cur_quiz_id . '\' '
//         . 'AND teacher_id = \'' . $teacher_id . '\' ');



// $stored_result = mysql_fetch_assoc($stored_res);
// $childs = mysql_query("SELECT * FROM `questions` WHERE id = " . $qn_id);
    // if Result Saved////
  



  //print_r($data);

/////////////////Get Question Data /////////////


$con_1= @mysql_connect('localhost', 'ptwogorg_prod', 'aE&ZidJX)8bl');
//mysql_query('SET NAMES utf8');
$pdb=mysql_select_db('ptwogorg_main', $con_1);

if($pdb){
$s= 'Connnected db'; // Success
}else{
    echo 'Not Connnected db';

}

 //$sql="SELECT * FROM `questions` WHERE `TestID` = 5";
  

 $sql=" SELECT * FROM `questions` WHERE `ID` =".$show_qn_id;
 $qdata= mysql_fetch_assoc(mysql_query($sql));

$tot_ques=mysql_num_rows(mysql_query($sql));

// Question ID  Question OptionIDs AnswerID
 $record=array();

   //option in QUestion 
   $options_is_str= $qdata['OptionIDs'];

    $options_is_str; 
 // Select all answer of a qustion
 $sql_answer="SELECT * FROM `answers` WHERE `ID` IN (97,98,99,100)";
  $s=" SELECT * FROM `answers` WHERE `ID` IN (".$options_is_str.") ";

 $sql_answer=mysql_query(" SELECT * FROM `answers` WHERE `ID` IN (".$options_is_str.") ");

   $opn_arr=array();

   // array titl=, checked true.
    //$old_choose=null;
    if(isset($show_qn_id)){  // old edit question
    $attempted=mysql_fetch_assoc(mysql_query("SELECT * FROM `training_result_logs`
     WHERE `qn_id`='$show_qn_id' AND `tutor_id` =".$teacher_id." AND `test_id` =".$_SESSION['assessment']['ses_test_id'])); //1
    
     $old_choose=$attempted['answer'];
    }
    

    //print_r($attempted);


   while($row=mysql_fetch_assoc($sql_answer)){
                                              
   $opn_arr[]= $row['Answer'];  
      }

      //print_r($opn_arr);
                                          
     // die;
                                             


                                       




$record['title']=$qdata['Question']; //actual question
$record['id']=$qdata['ID'];
//print_r($_SESSION['assessment']);







?>

<link type="text/css" href="css/home-page.css" rel="stylesheet" />
<style>
.list-answers li img{
  max-width:100%;
  height: auto;
}
</style>
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


                    <!-- Header -->
                <div id="single_question" class="content_wrap">

                    <div class="ct_display clear">                   
                        <form method="post">
                        <?php  if($tot_ques>0){ ?>
                        <ul class="ul-list">

                          <li style="border: none;list-style:none;">
                                        <div class="ques-text">
                                            <div style="float:left;margin-right:10px;"> <?=$current_qn?>. </div>
                                            <div class="questions-detail"> 
                                            <?=$record['title']; //?>

                                            </div>
                                            <?php

                                           // print_r($opn_arr);
                                            //if($tot_ques>0){


                                            ?>


                                            <ul class="list-answers col-md-6">
                                            <li>a.  &nbsp;&nbsp;&nbsp;<input type="radio"
                                             name="answer" value="0"  <?=(isset($attempted['qn_id'])&&$old_choose==0)?'checked':null?>  >
                                            <p>  <?php  echo $opn_arr[0];?>
                                            </p>

                                            </li>


                                            <li class="col-right">b.&nbsp;&nbsp;&nbsp;<input type="radio" 
                                            name="answer" value="1"  <?=($old_choose==1)?'checked':null?> >
                                            <p><?php  echo $opn_arr[1];?> </p></li>
                                            <div class="clearnone">&nbsp;</div>

                                            </ul>


                                            <ul class="list-answers col-md-6">

                                            <li>c.  &nbsp;&nbsp;&nbsp;<input type="radio"
                                             name="answer" value="2" <?=($old_choose==2)?'checked':null?> >
                                             
                                                <p> <?php echo $opn_arr[2];?>  </p>
                                            
                                             </li>


                                            <li class="col-right">d.  &nbsp;&nbsp;&nbsp;<input type="radio"
                                             name="answer" value="3" <?=($old_choose==3)?'checked':null?>   >
                                             <p> <?php  echo $opn_arr[3];?> </p></li>

                                             <div class="clearnone">&nbsp;</div>

                                             </ul> 


                                             </div>
                                        <div class="ques-button" style="border: none; ">
                                            <input type="hidden" name="question_id" value="<?=$record['id']?>">
                                                  <?php if ($prev != -1) { ?>
                                            <a href="training_test.php?pos=<?php print $prev; ?>" class="form_button submit_button"
                                             style="background: red;display:inline-block; width: 50px; "  >Back</a>
                                            <?php } ?>

                                                <input type="submit" class="form_button submit_button" 
                                                name="next" style="background: green; " value="Next">
                                            
                                        </div>

                                    </li>
                                    </ul>
                                    <?php }else{?>

                                     <div style="text-align: center;">
                                    <h1>You've Answered Every Question!</h1>
                                    <a href="training_test.php" class="form_button submit_button"  style="background: red;padding: 5px;"  >Go Back and Check Work</a>
                                     <br/><br/>
                                        <input type="submit" class="form_button submit_button" style="background: green;" name="complete" value="I'm done!" >
                                    </div>    <?php  } //}else{?>
                         
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>