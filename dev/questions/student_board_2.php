<?php
/****
@Groupword

 * **/

 

 include('inc/connection.php'); 
session_start();ob_start();

$Board_url_newrow='https://intervene.io/questions/student_board.php';


//////////////////////////////////////////////

if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}




  $board_url='session-board.php';
  $actvity_url="actvity.php";
  $page_name='Session Board';
  $no_record='No record found.!';

##########Test Session: student###############
$data=array();// Listing array 
include("student_inc.php");

$student_school=$student_det['school_id'];
/*
$student_teacher=$student_det['teacher_id'];




$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";


$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];
  $data[]=$row;
  # code...
}
*/
#############################################
$stu_arr=array();
 


            if(isset($_SESSION['live'])){


               $student_board=$_SESSION['live']['live_board_url'];#Braincert URL.

               $curr_ses_id=$_SESSION['live']['live_ses_id'];
                 // assigned tutor Board url -->>> 

              $sql_ses_tutor_board=" SELECT ses.curr_active_board,ses.id,ses.tut_teacher_id ,ses.board_type,ses.tutor_app_url, t.*
FROM int_schools_x_sessions_log ses LEFT JOIN  gig_teachers t ON  ses.tut_teacher_id =t.id WHERE ses.id =".$curr_ses_id;

          $ses_det=mysql_fetch_assoc(mysql_query($sql_ses_tutor_board));
         $new_board_url=$ses_det['url_aww_app'];

         if(empty($ses_det['url_aww_app'])){
          exit('Sorry, Board URL not found!');
         
         }




         $move_url=$new_board_url; // Default>>>
          $move_url=$new_board_url;
          $iframe_board_url=$ses_det['url_aww_app'].'?iframe=true';
          ///////////////////////////////////

        }

          
  ?>







 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
   is_quizz_move(); // Stop Quiz MOVE




function is_quizz_move(){

  console.log('is_quizz_move=');// is_quizz_move
      //var url_1="https://intervene.io/questions/student_live_ses_ajax.php";


      var url_1="https://intervene.io/questions/ajax_check_live_board.php";

      $.ajax({ 
            type: 'GET', 
            url: url_1, 
            data: { get_param: 'student_actvity' }, 
            dataType: 'json',
            success: function (data) { 
            var str=' Test info='+data.status;
             
             console.log('===status='+data.status);
             // quiz_url
              var quiz_url='';
              // $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
             var move_url='';
             ////////Board url Changed fro groupdowr//////////
             if(data.cur_board=='newrow'){
              var groupword_board='https://intervene.io/questions/student_board.php';
              console.log('Board have changed Re-direct to newrow Board');
              window.location.href =groupword_board;
             }
             
             //console.log('sent_from='+data.sent_from);
             move_url=data.content.quiz_url;
             if(data.status=='ok'){
               window.location.href =move_url;//actvity.php |student_board.php
             }
           // Display modal
           $("#items_id").html(str);
          // $("#myModal").modal('show'); 
        // $(".last-updated").append(data.data.updated);
       
        }
    }); 

       setTimeout(function(){
      is_quizz_move();},3000);

}


</script>
  

<div id="home main" class="clear fullwidth tab-pane fade in active">

    <img src="https://intervene.io/assets/images/Intervene%20new.png" alt="Logo" style="height:40px">
    <br/>

 


    <div class="container_dfdf">
        <div class="row">
          
           <?php
           //  if(isset($ses_det['curr_active_board'])&&$ses_det['curr_active_board']=='groupworld'){
             
            ?>



           <div class="align-center">
         <?php if(isset($iframe_board_url)){  // iframe_board_url ?>
          
          <form name="principal_action" id="principal_action" method="POST" action="">
        
            <style>html,body { height: 100%; margin: 0px }</style> 
      <iframe allow="microphone; camera; display"  style="width: 100%;height:900px;" 
      scrolling="no" frameborder="0" src="<?=$iframe_board_url?>"></iframe>

        
        
        
            </form>
            <?php }else{ ?>
              <p>There is problem in tutor Board!</p>
            <p class="text-center"> 
              <a title="Back, Home" href="student_pendings.php" class="btn btn-success btn-sm">Back,</a>
            </p> 
          <?php  }  ?>
           </div>

                  
      
                      
           



      

        </div>
    </div>
</div>

<?php ob_flush();?>

