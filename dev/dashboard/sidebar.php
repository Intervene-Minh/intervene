 <?php
 /// Role(Teacher) $role==1,
 require_once('inc/check-role.php');
 $role = checkRole();

      
  $dg=''; $dg1='';
    // qq 
    $total_jobs_count=0;  
$total_count_payment=0;
$total_count_message=0;
  // jbo ser
      $curr_time= date("Y-m-d H:i:s"); #currTime
    $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
     $qq.=" AND ses_start_time>'$curr_time' ";
      $qq.=" AND (tut_teacher_id='0' ";
$qq.=" OR (add_observer='1' and tut_observer_id IS NULL)) ";
      $qq.=" ORDER BY ses_start_time ASC "; 
                            
          $total_jobs_count =mysql_num_rows(mysql_query($qq));  

///Message ///
 $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'
$sql=" SELECT * FROM `notifications` WHERE `read` = '0' AND `receiver_id` = '$tutor_id' AND `type` = 'message'";

 $total_count_message =mysql_num_rows(mysql_query($sql)); 
  
///Payment::tutor_paid_session//
 $tutor_paid_sesX=" SELECT *
FROM int_schools_x_sessions_log
WHERE payment_status=1 AND feedback_id>0 AND tut_teacher_id=".$tutor_id;
// Paid+Unpaid
$tutor_paid_ses=" SELECT *
FROM int_schools_x_sessions_log
WHERE 1 AND feedback_id>0 AND tut_teacher_id=".$tutor_id;

  //$sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND feedback_id>0 ";
//  $qq=" AND feedback_id>'0' ";
  
                                
                                
                                
                                
 $total_count_payment =mysql_num_rows(mysql_query($tutor_paid_ses)); 
                                
                            
        
    ?>



<div id="smartprep" class="widget clear fullwith">
  <h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="#">Dashboard</a></h4>
</div>  

<div class="group_2 widget-content">
    
 
    
    

  <div id="strategies" class="widget clear fullwith" title="Tutor Policy">
    <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a href="tutorpolicy.php">Tutor Policy</a></h4>
       </div> 


    <div id="strategies" class="widget clear fullwith" title="Tutor Policy">
    <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a href="https://tutorgigs.io/knowledgebase/index.html">Tutor Training Videos</a></h4>
       </div> 

<div id="strategies" class="widget clear fullwith">
  <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a href="my-sessions.php">My Sessions</a></h4>
</div>  
    

   <!-- <div id="strategies" class="widget clear fullwith">
  <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a href="my-sessions-all.php">My Sessions All</a></h4>
</div> --> 
    <div id="strategies" class="widget clear fullwith" title="Session Calendar / Job Calendar:">
  <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a href="jobs-board-calendar.php">Job Calendar</a></h4>
</div>

          <!-- Calendar2 -->
   

    
    <div id="strategies" class="widget clear fullwith" title="Jobs Board">
  <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a href="Jobs-Board-List.php">Jobs Board(<?=$total_jobs_count?>)</a></h4>
</div>
    

    
   
    
    
   

   <!--  <div id="strategies" class="widget clear fullwith" title="Lessons">
  <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a href="lessons/">Lessons</a></h4>
       </div> -->


     <!--   <div id="strategies" class="widget clear fullwith" title="Lessons List">
    <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a href="lesson_download.php">Lessons</a></h4>
       </div> -->

       
    <div id="strategies" class="widget clear fullwith" title="Complete Sessions">
  <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a  href="complete-sessions.php">Complete Sessions</a></h4>
</div>

    
    <div id="strategies" class="widget clear fullwith" title="Tutor Instructions">
  <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a  target="_blank"href="https://tutorgigs.io/dashboard/Tutor-Training -Kelly- Services.pptx">Tutor Instructions</a></h4>
</div>
    
    <div id="strategies" class="widget clear fullwith" title="Payment">
  <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a  href="payment-list.php">Payment(<?=$total_count_payment?>)</a></h4>
    </div>

    <?php  // notifications.php?>

    <div id="strategies" class="widget clear fullwith" title="Message">
    <h4 class="widget-title"><i class="fa fa-lightbulb-o"></i>
            <a  href="inbox.php">Messages(<?=$total_count_message?>)</a></h4>
    </div>

    


        <!-- tifications -->
    <div id="distrator" class="widget clear fullwith" style="/*! border: 2px solid; */">
    <h4 class="widget-title" title="Quiz"><i class="fa fa-bolt"></i>Notifications</h4>
<div class="widget-content">
    
     <p class="add_new">
        <i class="fa fa-cog"></i>
  <a href="tutor_settings.php" title="Settings">Settings</a> </p>
   
        
        <p class="add_new" title="">
    <i class="fa fa-th-list"></i>
    <a href="notifications.php">List of Notifications</a></p>

        
</div><div></div></div>





     

     
    
    
<!--    <div id="arrange" class="widget clear fullwith">
  <h4 class="widget-title"><i class="fa fa-file-text-o"></i>
            <a href="#">Options 1</a></h4>
</div>    -->
    
    
    
    
    
  <div id="arrange" class="widget clear fullwith">
  <?php #include('widget/widget-arrange.php');?>
</div>    <!-- /#arrange -->
 

<!-- /#Options -->

  

</div>



<div id="feedback" class="widget clear fullwith">
  <?php  // include('widget/widget-feedback.php');?>
</div>  

<div id="profile" class="widget clear fullwith">
    <h4 class="widget-title"><i class="fa fa-file-text-o"></i><a href="get_qualification.php" style="color:#000">Get Qualified More Subject</a></h4>


</div> 
<div id="profile" class="widget clear fullwith">
  <?php include('widget/widget-profile.php');?>

</div>  




<?php 
// admin Access options
?>










