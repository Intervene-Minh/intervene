<?php
/****
===============

@ https://intervene.atlassian.net/browse/IIDS-158
@ Tutor help Button
Technology Test
======================
@ Session Details&Download Button added+
for homeowr help

**/

@extract($_GET) ;
@extract($_POST) ; 
$ses_time_before=-2700; 
$ses_2hr_before=-7200;

include("header.php");



//////////// Launch OR Prepare for Tutor Session////////////////////////
if(isset($_GET['sesid'])&&$_GET['sesid']>0){
  /////////////////////////
  $_SESSION['live_ses_id']=intval($_GET['sesid']);
   $Tutoring=mysql_fetch_assoc(mysql_query("SELECT *
FROM int_schools_x_sessions_log WHERE  id=".$_SESSION['live_ses_id']));

   $_SESSION['curr_ses_board']=$Tutoring['curr_active_board'];

  //////////////
  header("Location:tutor_board.php"); exit;
  
}



//////////////////////////////////
 //print_r($_SESSION);

 $cur_time= date("Y-m-d H:i:s");
     $time=strtotime($cur_time);
     $startTime = date("Y-m-d H:i:s", strtotime('-55 minutes', $time));
$endTime = date("Y-m-d H:i:s", strtotime('+55 minutes', $time));
 $one_hr_les=date("Y-m-d H:i:s", strtotime('-60 minutes', $time));
 ///*************

 $time_arr=array();
  $time_arr['curr_time']=date("Y-m-d H:i:s");
$time_arr['one_hr_les']=$one_hr_les;

 $time_arr['time_55_less']=$startTime;
 $time_arr['time_55_up']=$endTime;
      $time_arr['24_hour_back']=date('Y-m-d H:i:s',strtotime('-24 hours'));
      $time_arr['24_hour_next']=date('Y-m-d H:i:s',strtotime('+24 hours'));
      $time_arr['2_hour_less']=date('Y-m-d H:i:s',strtotime('-2 hours'));

    //print_r($time_arr); 
//////////Validate Site Access//////////
//print_r($_SESSION);
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}
/////////////////////////////////////
  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";
//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

 $page_name='My Sessions';
$error='';
$id = $_SESSION['ses_teacher_id'];

function send_job_email($to,$message,$subj=''){
  $to ="learn@intervene.io";
   
  $subject =$subj;
  // learn@intervene.io
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
 //$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 ///  if(mail($to,$subject,$message,$headers)) echo 'send ';

}
//////Send email////////

$message = "Dear {$f_name},
        <br/><br/>
        Job time has changed.Please check and requesting you to cancel job if not available for the job.
  <br/><p><strong>Job ID</strong>:{$ses_id}</p>

  <br/><br/>
  Best regards,<br />
  <strong>Tutorgigs Team</strong><br/>
  www.tutorgigs.io<br>
  Tel +185534-LEARN<br>
  Email: support@tutorgigs.io
  <br /><br />
  <img alt='' src='https://tutorgigs.io/logo.png'>";

   $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'
$tutor_det= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$tutor_id));

 $tutor_new_board_url=$tutor_det['url_aww_app'];  # FROM gig_teachers #4-april 2019








////////////Rejected////////////////////////////////////////////
if(isset($_POST['submit_reject'])){
  
  
   $ses_id=$sid=$_POST['submit_reject'];// Session id
    $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'

   $tut_th= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$tutor_id));
   
  

   // Validate::Another Tutor accept.
   $ses_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND id=".$sid));

  $em_ses_date=date_format(date_create($ses_det['ses_start_time']), 'F d,Y');
  $em_ses_time=date_format(date_create($ses_det['ses_start_time']), 'h:i a');
  //  f_name lname
  $turorName=$tut_th['f_name'].' '.$tut_th['lname'];


  $message="Hi Admin, <br/><br/>
  
  <p><strong>Tutor</strong>:{$turorName}</p>
   <p><strong>Time</strong>:{$em_ses_time}</p>
   <p><strong>Date</strong>:{$em_ses_date}</p>
    <p><strong>Session ID</strong>:{$ses_id}</p>
    <br/><br/>

    Thanks,<br/>
Tutorgig Team  ";
  

 

 // send_job_email($to,$message,$subj=''){
   $subject1=$turorName.' Cancelled the session';


   if(send_job_email($to='',$message,$subject1)){
    $em_msg= 'sent' ; 
   }else{
      $em_msg= 'Not sent' ; 
   }





   ////////////////
   $data=mysql_fetch_assoc(mysql_query(" SELECT COUNT(*) as tot_canel FROM int_session_cancel WHERE tutor_id=".$tutor_id));

 

         #logoutMesageAccount Susspended. 
   /**
 if($data['tot_canel']>=2){ 
      $susTutr= mysql_query("UPDATE gig_teachers SET status='2',"
            . "updated='$curr_time' WHERE id='$tutor_id'", $link);  
        $_SESSION['suspended']='Your account suspended, Please contact service provider. !';
       unset($_SESSION['ses_teacher_id']);
       
    header("Location:../login.php");exit;    
    }

   **/
    
    
   //////////////////////
 if($ses_det['tut_teacher_id']>0){  /// 
     //insert log: int_session_cancel:: ses_id   	tutor_id dated
      $log= mysql_query("INSERT INTO int_session_cancel SET ses_id='$sid',"
            . "tutor_id='$tutor_id',dated='$curr_time' ", $link); 
     
     ///////////////////////////
  $query = mysql_query("UPDATE int_schools_x_sessions_log SET tut_teacher_id='0',"
            . "app_url='',tut_accept_time='$curr_time',"
            
            . "modified_date='$curr_time' WHERE id='$sid'", $link);
    
    
    // in studentsLog of Slot
     $up= mysql_query("UPDATE int_slots_x_student_teacher SET tut_teacher_id='0',"
            . "tut_admin_id='1' WHERE slot_id='$sid'", $link); 
     
     
       $error="You cancelled this session. !";
   }else{
      $error_msg="Can not cancel"; 
   }


    //  	tut_teacher_id ::default 0 if , Rejected
    
}
////////////Rejected////////////////



// Submit to give feedback 
if(isset($_GET['submit_feedback'])){

     unset($_SESSION['form_2']); unset($_SESSION['form_3']); 
unset($_SESSION['form_4']); unset($_SESSION['form_5']);  
  unset($_SESSION['form_1']); //unset($_SESSION['feedback_ses_id']);
    //////////////
    $_SESSION['feedback_ses_id']=$_GET['submit_feedback'] ;
    // feedback_form1.php
       header("location:feedback_form1.php"); exit;
}



 
// Edit :: submit_feedback_edit
if(isset($_GET['submit_feedback_edit'])){

     unset($_SESSION['form_2']); unset($_SESSION['form_3']); 
unset($_SESSION['form_4']); unset($_SESSION['form_5']);  
  unset($_SESSION['form_1']); //unset($_SESSION['feedback_ses_id']);
    
    $_SESSION['feedback_ses_id']=$_GET['submit_feedback_edit'];  
       header("location:feedback_form1.php?edit=1"); exit;
}


if(isset($_POST['delete-user'])){
	$arr = $_POST['arr-user'];
	if($arr!=""){
		//$query = mysql_query("DELETE FROM demo_users WHERE id IN ('$arr')", $link);
            
            //// Delete Role Table...
            $query = mysql_query("DELETE FROM demo_users WHERE id IN ($arr)", $link);
	}
        
        echo "<script>alert('#Record deleted..');location.href='manager_demo_user.php';</script>";
        ///
        
}



$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
//Listing ///


                                
                           
                              $curr_time= date("Y-m-d H:i:s"); #currTime
                            
                                $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                               // $qq.=" AND tut_status='STU_ASSIGNED' ";
                               
                             
                                if(isset($_GET['id']))
                                $qq.=" AND id='".$_GET['id']."' ";#only Assigned
                                else{
                               #     $qq.=" AND ses_start_time>'$curr_time' ";
                                 $qq.=" AND ses_start_time>'".$time_arr['2_hour_less']."' ";
                                    
                                 $qq.=" AND tut_teacher_id='$id' ";
                                $qq.=" ORDER BY ses_start_time ASC ";    
                                }
                          
                               
                                
                                
                                /////////////////////////
                                $session_type=(isset($session_type))?$session_type:"upcoming" ;
                                if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                  $tutor= $_SESSION['ses_teacher_id'];
                                  
                           
                                  if($session_type=="past"){
                                     $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                
                                $qq.=" AND ses_start_time<'".$curr_time."'";
                                 $qq.=" AND tut_teacher_id='$tutor' ";
                                 
                                $qq.=" ORDER BY ses_start_time DESC "; 
                               
                                  }elseif($session_type=="upcoming"){
                                    $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                 
                                $qq.=" AND ses_start_time>'".$curr_time."'";
                                  $qq.=" AND tut_teacher_id='$tutor' ";
                                $qq.=" ORDER BY ses_start_time ASC ";   
                                  }elseif($session_type=="all"){
                                   $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                  $qq.=" AND tut_teacher_id='$tutor' ";
                                
                                $qq.=" ORDER BY ses_start_time ASC ";   
                               
                                  }
                                  
                                  
                               //    if(!empty($email))
                                // $qq.=' AND email LIKE "%'.$email.'%"  ';
                                
                                    
                                   
                                }
                                
                              //////////////
                               // echo $qq;
                                
                                
                                $results = mysql_query($qq);
                $tot_record=mysql_num_rows($results);
                                
                             // echo '<pre>',$qq;echo '<br/>' ; 

  //end              
  ?>

  <style type="text/css">
  #techtest{
    color: red;
    font-size: 16px;
    margin-left: 115px;
    border: 1px solid;
   /* padding: 1px 5px;
    border-radius: 3px;*/

  }
    
  </style>


<script>
function myFunction() {
    var txt;
    //var r = confirm("Press a button!\nEither OK or Cancel.\nThe button you pressed OK,Reject session.");
     var info='If you cancel this job with less than 48 hours notice you will risk being suspended and losing deducting the payments for 1 session.';

     var r = confirm(info);
    if (r == true) {
       // txt = "You pressed OK!";
        return true;
      
    } else {
         //txt = "You pressed Cancel!";
       // alert('yynot submit');
       return false;
       
    }
   // document.getElementById("demo").innerHTML = txt;
}
</script>

<script>
    
    $(function() {
       $('.delete8888').click(function(e) {
           
        e.preventDefault();
        var c = confirm("Click OK to continue?");
        if(c){
          //  return true;
           $('form#form-manager').submit();
          }
      });
  });
  ////////////////////
    
    
	$(document).ready(function(){
            
            
            
            
           ////////////////////// 
            
		$('#delete-user').on('click',function(){
			var count = $('#form-manager .checkbox:checked').length;
			$('#arr-user').val("");
			$('#form-manager .checkbox:checked').each(function(){
				var val = $('#arr-user').val();
				var id = $(this).val();
				$('#arr-user').val(val+','+id);
			});
			var str = $('#arr-user').val();
			$('#arr-user').val(str.replace(/^\,/, ""));
			return confirm('Are you want to delete '+count+' user?');
		});
	});
        
  /////////////////      
      function sent_form(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
	 form.setAttribute("target", "_blank");

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

///
$(document).ready(function() {
   $('#setdate').change(function() {
     var parentForm = $(this).closest("form");
     if (parentForm && parentForm.length > 0)
       parentForm.submit();
   });
});
</script>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
                            <div class="table-responsive">
                              <form id="search-users" method="GET" action=""  >
						<table class="table">
							<tbody><tr>
								<td><label>Filter:</label></td>
		<td>
                    <select name="session_type">
                     <option value="all" <?php echo (isset($session_type)&&$session_type=="all")?'selected':NULL; ?> >All</option>
                        <option value="upcoming" <?php echo (isset($session_type)&&$session_type=="upcoming")?'selected':NULL; ?> >Upcoming sessions</option>
                        <option value="past" <?php echo (isset($session_type)&&$session_type=="past")?'selected':NULL; ?>>Past sessions</option>
                                    
                                </select>
                    &nbsp;<input name="action" class="btn" value="Search" type="submit">    


                    <a  target="_blank"  href="https://tutorgigs.io/techtest/" id="techtest" class="btn text-danger">Technology Test</a>
                
                </td>
		
								
								
								
							</tr>
						</tbody></table>
					</form>  
                                
                                
                                
                            </div>
                            
                           
                            
                            
				<form  onsubmit="return myFunction();" id="form-manager"
                                      class="content_wrap" action="" method="post">
                                    	
                                    
                                    
                                    <div class="ct_heading clear">
						<h3><?=$page_name?>(<?=$tot_record?>)</h3>
						
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if(isset($error)&&$error != '') {
							echo '<p class="error">'.$error.'</p>';
						} 
						
                                              
                                                    
                                                    ?>
                                           
                                            
							<table class="table-manager-user col-md-12">
								<colgroup>
									
									<col width="230">
									
									<col width="230">
									<col width="100">
									
									<col width="125">
								</colgroup>
								<tr>
								
									<th>Sessions Date/Time</th>
									<th>detail</th>
                                                                       
									<th> Status</th>
									
									
									<th>Session details</th>
								</tr>
								<?php
                                          $tutor= $_SESSION['ses_teacher_id'];                      
                                             if( mysql_num_rows($results) > 0 ) {
		while( $row = mysql_fetch_assoc($results) ) {
			// teacher_id								
			// TutTeacher Statatus					
               $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=".$row['teacher_id']));
               $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));
                 $tot_std=($tot_std>0)?$tot_std:"XX";
             $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));    
          $int_school= mysql_fetch_assoc(mysql_query("SELECT SchoolName FROM schools WHERE SchoolId=".$row['school_id']));  

          $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));    
             // List of students 
          //$quiz objective_name             
                   $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$row['id']."' ";
            $resss=mysql_query($q);
            $stud_str=array(); // middle_name
            while ($row2=mysql_fetch_assoc($resss)) {
                $stud_str[]=$row2['first_name'].' '.$row2['middle_name'];
            }  
            $stdList=(count($stud_str)>0)? implode(",", $stud_str):"NA";
            // G:i a   
            // special_notes
            $row['special_notes']=(!empty($row['special_notes']))?$row['special_notes']:"NA";
           $sesStartTime=$row['ses_start_time'];
            $in_sec= strtotime($sesStartTime) - strtotime($curr_time);
            ////////////
             $quiz=mysql_fetch_assoc(mysql_query("SELECT q. * , l.id AS lesid, l.name as les_name, l.file_name
FROM `int_quiz` q
LEFT JOIN master_lessons l ON q.lesson_id = l.id
WHERE q.id =".$row['quiz_id']));

             $lesson_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `master_lessons` WHERE id=".$row['lesson_id']));




              
             $lesson_download="https://intervene.io/questions/uploads/lesson/".$lesson_det['file_name'];// 4358Question.pdf
             $tutoring_det_url="https://tutorgigs.io/dashboard/tutoring_details.php?sid=".$row['id'];
            
            ?>
										
                                                                
                                                                
                                        <tr>
                                                                    
											
                                                                                        
				                      <td>
                              <?php //=$row['braincert_board_url'];?> <br/>
                              
                                    
                                    
                             <span> <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?></span>   <br>


                                   <a title="tutoring detail"  href="<?=$tutoring_det_url?>" 
                                     class="btn btn-success btn-xs">
                                       
                     <?=$SesTime=date_format(date_create($row['ses_start_time']), 'h:i a');#?>
                                     </a> 
                              
                                     
                                
                                
                             <?php if($row['Tutoring_client_id']=='Intervene123456'){  ?>
                             
                                    <br/>
                                    <strong class="text-primary">
                                 Objective:</strong> <?=$quiz['objective_name']?>
                                    
                                    
                                    <br/>
                                    <strong class="text-primary">
                                 Class list of students:</strong>[<?=$stdList?>]
                                <br/>
                                 <strong class="text-primary">
                                 Special Notes for the lesson:</strong>
                                <?=$row['special_notes']?>
                                <br/>
                                <?php  //if(isset($quiz['file_name'])){?>

                            <a href="<?=$lesson_download?>"
                             class="btn btn-danger btn-xs">Download-<?=$lesson_det['name']?></a>  <?php // } ?>
                             <br/>

                            
                           <?php } ?>
                           <!--  <br/> -->

                          

                             

                            <br/> <span><strong class="text-primary">Class From:</strong> 
                              <?php  if($row['type']=='drhomework'){ ?>
                              <span class="btn btn-danger btn-xs"> Homework Help </span> </span>
                            <?php }else{?>
                              <span class="btn btn-primary btn-xs"> Intervention </span>
                            <?php  } ?>

                             <?php 

                                     if($row['Tutoring_client_id']=='Drhomework123456'){
                        
                        $Sessiontype='Drhomework';
                        
                        
                        }else{  $Sessiontype='Intervention'; }
                       
                        
                      
                      
                        
                      


                                     ?>


                                     <a  class="btn btn-danger btn-xs viewSession"
                                      href="javascript:void(0)" SessionID="<?=$row['id']?>"  
  action="<?=$Sessiontype?>">Session Detail & Downloads</a>





                                
                                </td>

                 
                                           
                                            
                                <td>
                                  <?php if($row['Tutoring_client_id']=='Intervene123456'){  ?>
                            <strong class="text-primary">School:</strong><?=$int_school['SchoolName']?><br/>         
                        
                           
                              <span class="btn btn-primary btn-xs"><?=$tot_std ?>-<?=$status_arr['STU_ASSIGNED']?></span>
                              <br/>
                            <?php }?>





                          <span style="display:none;"> <?='Time Dif==='.$in_sec;?><br/></span>
                            
                              
                              &nbsp; &nbsp;
                             <?php 
                             // from session time till 2hr Launch  button show to tutor
                    if($row['board_type']!='newrow'&& $row['tut_teacher_id']==$tutor&&$in_sec>$ses_2hr_before){ 
                    
                          $board_url=$tutor_new_board_url; // TutorProfileUrl
                         
                           $board_url_ifame='tutor_board.php';
                             

                              ?> 
                             <br/>      
          
                           
                             <a href="my-sessions.php?sesid=<?=$row['id']?>"  target="_blank"
                                 class="btn btn-success btn-md">
                              Launch OR Prepare for Tutor Session</a>
                         
                                <?php  } ?>







                                 <?php

                                

                                    if($in_sec<$ses_time_before)
                                      $btn_text='Post Tutorial Session Survey';//after session end time.
                                    else $btn_text='Tutorial Session Survey';

                        
                                     $feadback_bt=($row['feedback_id']>0)?"Edit Feedback":"Complete Session";
                                 if($row['feedback_id']>0) {   
                                ?> 
                          

                                      <a class="btn btn-info"
                                       href="my-sessions.php?submit_feedback_edit=<?=$row['id']?>" >Edit Feedback</a>

                                      <!-- <a></a> -->
                                            
                                             
                                 <?php  }else{ // Edit Feadback ?>
                                 <br/>
                      
 
                                      <a  class="btn btn-default"    style=" background-color:orange;
                                            color:#fff; border:1px solid orange" 
                                      href="my-sessions.php?submit_feedback=<?=$row['id']?>" ><?=$btn_text?></a>
                                    <?php  }?>         
                                    
                                </td>
                                <?php // } //just aftersesStart?>





                                
                                           
                                            <td>
                                  
                                      <?php   //if($row['tut_teacher_id']>0){?>
                      


                                  <strong class="text-primary">AssignedTo:</strong> 
                                  <?=$tut_th['f_name']." ".$tut_th['lname']?>(ME)<br/>
                                   
                                      <?php 


                                    // upcomming
                                   if($row['tut_teacher_id']==$tutor&&$in_sec>0){ 
                                    ?>
                                   <button  type="submit" name="submit_reject" title="Cancel this Job"
                                      class="btn btn-danger btn-md" value="<?=$row['id']?>">Cancel Job</button>
                                  
                                   <?php }?>
                                            
                                            </td>
                                            
                                            <td>
                                                <span><strong class="text-primary">Virtual board</strong>:
                              <?=(!empty($row['board_type']))?ucfirst($row['board_type']):'Groupworld'; ?> </span>
                             <br/>


                            <strong class="text-primary">Session id:</strong><?=$row['id']?><br/> 
                           
                                   
                                            
                                   <strong class="text-primary">Created date:</strong>    
                               <?=date_format(date_create($row['created_date']), 'F d,Y');?> 
                                <br/>
                                 
                                 <a  style="display: none;" class="btn btn-danger btn-xs" 
                                 href="https://tutorgigs.io/dashboard/tutoring_details.php?sid=<?=$row['id']?>" style="text-decoration:underline;">view</a> 
                                     <!--  Newrow Launch button:: Testing tutor only  -->
                                     <?php  
                                     // $_SESSION['ses_teacher_id']==125&&
                              if($row['board_type']=='newrow'
                                &&$row['Tutoring_client_id']=='Intervene123456'){?>
                                      <br/>
                                      <a href="tutor_join_room.php?sesid=<?=$row['id']?>" 
                                        target="_blank" class="btn btn-success btn-xs">
                              Launch with Newrow Board</a>
                                <?=$row['Tutoring_client_id'];?>
                                     <?php  } ?>

                                    


                                            </td>     
                                                             
                        
                                      
                                           
                                                                                        
                                                                                        
                                                                                        
										</tr>
								<?php
										}
									} else {
										echo '<div class="clear"><p>There is no item found!</p></div>';
									}
								?>
							</table>
						
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        <input type="hidden" id="arr-user" name="arr-user" value=""/>
				</form>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		
<!-- /#header -->


<script type="text/javascript">
   <?php 
      if ($error != '') echo "alert('{$error}')"; ?>




$('.viewSession').click(function()
{
  var SessionID=$(this).attr('SessionID');
  var action = $(this).attr('action');
$.ajax({
   type:'POST',
   data:{SessionID:SessionID,action:action},
   url:'https://tutorgigs.io/dashboard/get_session-ajax.php',
   success:function(data){
    $('#ViewDetails').modal('show');
    $('.SeessionIDD').text(SessionID);
    $('.dataview').html(data);
   }
});

});

</script>

 <!-- Modal -->
  <div class="modal fade" id="ViewDetails" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
          <h4 class="modal-title">Details Session ID <span class="SeessionIDD"></span></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class=" dataview">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<?php include("footer.php"); ?>