<?php

/****

 $start_date = date('Y-m-d h:i:s');

==================

student_board_2.php

 * **/







 include('inc/connection.php'); 

session_start();ob_start();







//////////////////////////////////////////////



if (!$_SESSION['student_id']) {

    header('Location: login.php');

    exit;

}

  $board_url='session-board.php';

  $actvity_url="actvity.php";



##########Test Session: student###############

 if(isset($_GET['test'])&&$_GET['test']==1){

   $_SESSION['live']['live_ses_id']=2305; // testing session 

  

 }



 //print_r($_SESSION);



  $page_name='Session Board';

 $no_record='No record found.!';



///@@@@@@@@@@@@

$data=array();// Listing array 

include("student_inc.php");

$msg='Pending ==pending_assessments.php';

$student_school=$student_det['school_id'];

$student_teacher=$student_det['teacher_id'];









$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa

Left Join assessments a ON  sa.assessment_id=a.id

WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";





$result=mysql_query($sql);

while ($row= mysql_fetch_assoc($result)) {  

  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;

}





#############################################

 #Student info

 $stu_arr=array();

 $stu_arr22['lis_person_contact_email_primary']='Student_'.$studentId.'@gmail.com';

$Board_url_2='student_board_2.php';



if(isset($_SESSION['live'])){





               $student_board=$_SESSION['live']['live_board_url'];#Braincert URL.



               $curr_ses_id=$_SESSION['live']['live_ses_id'];

                 // assigned tutor Board url -->>> 



              $sql_ses_tutor_board=" SELECT ses.curr_active_board,ses.id,ses.tut_teacher_id ,ses.board_type,ses.tutor_app_url, t.*

FROM int_schools_x_sessions_log ses LEFT JOIN  gig_teachers t ON  ses.tut_teacher_id =t.id WHERE ses.id =".$curr_ses_id;





          $ses_det=mysql_fetch_assoc(mysql_query($sql_ses_tutor_board));

         

        if($ses_det['curr_active_board']=='newrow'){



          header("Location:student_room.php"); exit; 

        }
        else  if($ses_det['curr_active_board']=='wiziq'){
             header("Location:wiziq_vc.php"); exit; 
        }
            

        //////////////Re-direct for Newrow Rooom ///////////////////////





          //////////////////////

          if($ses_det['curr_active_board']!='newrow'){

            

            header("Location:".$Board_url_2); exit;

          }

          ////////////////////



         $move_url=$new_board_url; // Default>>>

          $move_url=$new_board_url;

          $iframe_board_url=$ses_det['url_aww_app'].'?iframe=true';





       }







          

            ?>















 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">

   is_quizz_move(); // Stop Quiz MOVE







function is_quizz_move(){



  console.log('is_quizz_move===');// is_quizz_move



      var url_1="student_live_ses_ajax.php";





      $.ajax({ 

            type: 'GET', 

            url: url_1, 

            data: { get_param: 'student_actvity' }, 

            dataType: 'json',

            success: function (data) { 

            // alert('Test');

            var str=' Test info='+data.status;

             

             console.log('status='+data.status);

             // quiz_url

              var quiz_url='';

              // $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 

             var move_url='';

             

             //console.log('sent_from='+data.sent_from);

             move_url=data.content.quiz_url;



             if(data.cur_board!='newrow'){

              var groupword_board='student_board_2.php';

              console.log('Board have changed Re-direct to board2');

              window.location.href =groupword_board;

             }



             if(data.status=='ok'){

               window.location.href =move_url;//actvity.php |student_board.php

             }

          

           // Display modal

           $("#items_id").html(str);

        // $(".aqiStatus").html(data.data.text); 

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

        

             if(isset($ses_det['curr_active_board'])&&$ses_det['curr_active_board']=='groupworld'){

             

              ?>

        



            



           <div class="align-center">

         <?php if(isset($iframe_board_url)){  // iframe_board_url ?>

          <form name="principal_action" id="principal_action" method="POST" action="">

        

       





            <style>html,body { height: 100%; margin: 0px }</style> 

      <iframe allow="microphone; camera; display"  style="width: 100%;height:900px;" 

      scrolling="no" frameborder="0" src="<?=$iframe_board_url?>"></iframe>



        

        

        

            </form>



            <?php }else{ ?>

              <p>Tutor Board URL not found!, </p>

              <a title="Back, Home" href="student_pendings.php" class="btn btn-success btn-sm">Back,</a>



                            </p>

            <?php  } ?>

                      

            </div>



           <?php }   ?>





        </div>

    </div>

</div>



<script type="text/javascript" > 





var APP_ID = "kcxwnbt6";



window.intercomSettings = {

    app_id: APP_ID

  };

(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/' + APP_ID;var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);};if(document.readyState==='complete'){l();}else if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();



</script>







<?php ob_flush();  ?>



