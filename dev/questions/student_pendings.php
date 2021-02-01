<?php
/****
 $start_date = date('Y-m-d h:i:s');
  # Students :teacher_id school_id grade_level_id class_id created
  @ Student All pending
  1. Pending quiz
  2. pending : assessment
  3. upcoming session > 
    $board_url='session-board.php';
  $actvity_url="actvity.php";
  $page_name='Recent session';
 $no_record='No record found.!';
 @ 3 Section : Sessions, Quiz, Assessments
 * **/
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');

include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}

/////////////////////
include("student_inc.php");
include("ses_live_inc.php");# Tutor sesion Live 
   //print_r($time_arr); # globals for sessions
#####################
 // page globals 
  $page_name='Student Pendings';


//echo date("Y-m-d H:i");

///Listing////////////////

function student_assessment_pending(){
  $data=array();// Listing array
   global $student_school;
   global $student_teacher;
$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";

 //return $sql; 
$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {  
   //$row['asssessment']='Demo asement';
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}
//return
return $data;
}
/////





/////////////Display////

//$get_rows=student_assessment_pending();
 //print_r($get_rows); die; 

//echo $msg;
 // echo '<pre>';
 // print_r($data);
 // echo 'Student==';
 // print_r($student_det);

?>
<script type="text/javascript">
    is_activity_move();
//var url_1="https://tutorgigs.io/dashboard/notify_refresh_top.php"; 
function is_activity_move(){

  console.log('is_activity_move===');// is_activity_move

    // var url_1="https://tutorgigs.io/dashboard/notify_refresh2.php";  
     // var url_1="https://intervene.io/questions/test_ajax.php"; 
      var url_1="student_pendings_ajax.php";
      $.ajax({ 
            type: 'GET', 
            url: url_1, 
            data: { get_param: 'student_pendings' }, 
            dataType: 'json',
            success: function (data) { 
            
            var str=' Test info='+data.url_redirect;

             //console.log(str);
             console.log('Sent_from::'+data.sent_from);
             console.log('status='+data.status);
             var move_url='actvity.php';
             console.log('url_redirect='+data.url_redirect);
             if(data.status=='OK'){
              move_url=data.url_redirect;
              window.location.href =move_url;//actvity.php |student_board.php
              
             }
             // console.log(data.is_redirect+'=is_redirect');  // url_redirect
             //str+=data.length;
           // Display modal
           $("#items_id").html(str);
          // $("#myModal").modal('show'); 


        
      
    
        // $(".last-updated").append(data.data.updated);
        // $(".aqiStatus").html(data.data.text); 
        }
    }); 

       setTimeout(function(){
      is_activity_move();},3000);

}


</script>

<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
      <div id="items_id" style="display: none;">
         CHecking sesion
      </div>
        <div class="row">
            <div class="align-center col-md-12" style="margin-top:10px; margin-bottom: 100px;">
                
                <div style=" width:auto;" title="">
                 <?php include("nav_students.php"); ?>
       

        
                
                      </div>    





            </div>
           <!--  NExt col -->
           <div class="align-center col-md-12">
<!--                <p class="text-success">Session list of students..  </p>-->
               <?php 



            ##########################
            $student_id=$_SESSION['student_id'];#
            $cc=" order by created_date DESC ";
   $sql="SELECT sd.quiz_status,sd.launchurl,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN "
           . " int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$student_id' AND ses.tut_teacher_id>0 ";  // tut_teacher_id 
      
      // $sql.=" AND ses.ses_start_time >= '".$time_arr['24_hour_back']."' ";
        # 55 min. less session in Penidng list.: include 1 past session.
        $sql.=" AND ses.ses_start_time >= '".$time_arr['time_55_less']."' ";

       // Next 24 thoud
        //$sql.=" AND ses.ses_start_time <= '".$time_arr['24_hour_next']."' ";
       $sql.=' ORDER BY ses.ses_start_time ASC ';


           // w/o Tutor Assigned. 
          

           //echo '<pre>', $sql; //   die;


     $result=mysql_query($sql);
               
     $total_ses=mysql_num_rows($result);

            if($total_ses>0){

               ?>


          <form name="principal_action" id="principal_action" method="POST" action="">
        <table class="table table-hover">
            <tr>
                <th width="26%">Session Date/Time</th>
                <th width="15%">Detail</th>
                <th>Quiz</th>
               
                <th>Action</th>
            </tr>
            <?php



     
   
     
     while ($row= mysql_fetch_assoc($result)){  
              
     $techer= mysql_fetch_assoc(mysql_query(" SELECT * FROM users WHERE id='".$row['teacher_id']."' "));         
      
      $tutor= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE id='".$row['tut_teacher_id']."' "));
         // url_aww_app   # $tutor
      $st_class=NULL;   
             $st_class=($row['tut_status']=="STU_ASSIGNED")?"btn btn-success btn-sm":"btn btn-danger btn-sm"; 
          $st_title=($row['tut_status']=="STU_ASSIGNED")?"Students Assigned":"Students Not assigned";
     $grade=mysql_fetch_assoc(mysql_query(" SELECT name FROM terms WHERE id='".$row['grade_id']."' "));    
        $grade=($row['grade_id']>0)?$grade['name']:"NA";
        
          ?>
            
           <tr>
                <td>
                <span>  <strong>Session ID: </strong><?=$row['id']?> <br> </span>
               
                 
               <span>
               <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br></span>                           
                                      
               
             <span class="btn btn-success btn-lg" style="font-weight: bold;">
                   <?=date_format(date_create($row['ses_start_time']), 'h:i a');#?>
             </span> 
              <!-- Extra -->
             <!--  <br/>
              <span style=""><?php  
              echo 'Time:'.$start_date = date('Y-m-d H:i:s'); ?></span>  --> 
              <br/>

                  <strong>Type: </strong><?=$row['type']?> <br/>

              </td>   
                  
              
              
                


                
                 <td>
                    <!--  <strong>Teacher: </strong><?php //=$techer['first_name']?> <br/> -->
                    


                 
                 <?php
                 $val1 = date("Y-m-d H:i:s"); #currTime
 //$val1 ='2018-03-11 10:45:00'; #currTime
 $start_date = new DateTime($val1); // 
 $sesStartTime=$row['ses_start_time'];
 $enddate=new DateTime($row['ses_start_time']);
$since_start = $start_date->diff($enddate);
 if($since_start->invert==0){
 echo '<br/><strong>Remaing time:-</strong>';
echo $since_start->days.' days<br>';
               echo $since_start->h.' hr,';
echo $since_start->i.' min,';
 echo $since_start->s.' seconds<br>';}
//// 
 // echo 'Curr - TIME-'.$val1.'<br/>';
$in_sec= strtotime($sesStartTime) - strtotime($val1);///604800 #days>+7 days
            
           ?>
                    
                 
                 
                 </td>
                 
                  
                  <td>
                    <?php 

                     #activity - 300-1,
                    # session  0 to 30min(-1800)
                      // 900 ::15*60 min
                    #$in_sec=-1800;// after 30 min. ses_start_time
                     #$in_sec=-1802;// after 30 min. ses_start_time
                    # $in_sec=200;// 1-300, : activity time
                    # $in_sec=-1799;// 1-300, : go, board.
                   // echo '=='.$in_sec.'<br/>'; # Test time.

                    //////////////
                    // quiz_status# Completed 
                   
                    if($row['quiz_status']=="Completed"){
                    
                    ?>
                  <span class="btn btn-success btn-sm">
                   <?=$row['quiz_status'];?></span>    
                      
                      
                       <?php
                        echo '<br/><strong class="text-primary">
                                 Date :</strong>'.$row['completion_date'];
                       }else{

                         if($in_sec<-1800&&$in_sec>-86400){  //Quiz after 30 min.,next 24 hr.
                         $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
                          $quiz_title="Click to start quiz";
                     
                          ?>
                         <a href="<?=$quiz_link?>" class="btn btn-primary btn-lg" 
                         title="<?=$quiz_title?>" class="btn btn-green">Take Your Quiz</a>
                                                                                                   
                      
                              <?php 
                               }else{
                       $quiz_link= '#';  #   quiz Inactive
                       $quiz_title="available as start a session";

                                 ?>
                      <span title="<?=$quiz_title?>">NA</span>
                   
                               
                               
                      <?php }   } //notcom  ?>            
                               
                      
                  
                  </td>
                  <td>
            
                    <?php 
                   

                    $target_next=0;
                        if($in_sec<=300&&$in_sec>=1){  // 60x5 ::   15 min before activity.
                    
                         $app_url=$actvity_url;
                          $app_title="Go to actvity-acitvity.php";
                          $target_next=1;
                          # ####
                          $is_redirect=1;
                            $url_redirect=$app_url;

                     }elseif($in_sec<=0&&$in_sec>-1800){ //activeTill55Min&&act: 30minX60
              
                      // at ses time to 30 min. board active.: go to ::<student_board.php
              $app_url=(!empty($row['braincert_class']))?$row['launchurl']:$board_url;
                           $app_title="Click,to go to app url";
                           $app_border="2px solid green";
                           $target_next=1;
                           $is_redirect=1;
                            $url_redirect=$app_url;
                         
                      }else{# 'inavtive='; 
                                  $app_title="Auto redirect to Actvity 5 min. before.";
                                   $app_border="2px solid yellow ";
                                    $app_url="javascript:void(0);";
                                    $target_next=0;  }//

                               

                                ?> 




                  
                  
                     
                      <?php 
                      if($in_sec<-1800){  // after 30 min.: sesion show exipred  
                         echo "Session Expired,";
                         }else{ // Board Button?>

                      <a    <?php if($target_next==1){?> target="_blank" <?php }?>
                       href="<?=$app_url?>" title="<?=$app_title?>"
                       class="btn btn-green form_button submit_button" style="padding: 80px 0px;height: 200px; border:<?=$app_border?>;width:
                               200px;font-size:20px;">Start Tutoring session</a>
                               <?=$app_title?>
                                <?php }?>

                      
                       
                  
                          
                               
                
                   
                  </td>
                
            </tr> 
          <?php }?>
            
            
            
            
            
            
            
            
        </table>
        
        
        
        
        
        
        
    </form>
          <?php  }

            // pendnig_quiz_inc.php?>


           <!--  Student Quziz pendings -->
            <?php  include("inc/pendnig_quiz_inc.php");?>
 
                <!--  Student Assessment -->
                
          
            <?php  include("inc/pending_assessment_inc.php");?>
            <!--  Student Quziz pendings -->
                      
            </div><!--   <div class="align-center col-md-12"> -->

           <!-- C0ntent -->
          



        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush();
 //print_r($_SESSION);
 ?>

