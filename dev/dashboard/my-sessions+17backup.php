<?php
   /****
   ===============
   
   @ https://intervene.atlassian.net/browse/IIDS-158
   @ Tutor help Button
   Technology Test
   
   **/
   
   @extract($_GET) ;
   @extract($_POST) ; 
   $ses_time_before=-2700; # 45X60# entire : 5 sec. after ses. end 30 min
   $ses_2hr_before=-7200;
   
   include("header.php");
   
   
                              
                                 $curr_time= date("Y-m-d H:i:s"); #currTime
                                   $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND id=2904";
   
                                   $results = mysql_query($qq);
                   $tot_record=mysql_num_rows($results);
                             
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
         </div>
         <!-- /#sidebar -->
         <div id="content" class="col-md-8">
            <div class="table-responsive">
               <form id="search-users" method="GET" action=""  >
                  <table class="table">
                     <tbody>
                        <tr>
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
                     </tbody>
                  </table>
               </form>
            </div>
            <form  onsubmit="return myFunction();" id="form-manager"
               class="content_wrap" action="" method="post">
               <div class="ct_heading clear">
                  <h3><?=$page_name?>(<?=$tot_record?>)</h3>
               </div>
               <!-- /.ct_heading -->
               <div class="clear">
                  <?php
                     if(isset($error)&&$error != '') {
                      echo '<p class="error">'.$error.'</p>';
                     } ?>
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
                        
                  $quizo= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));    
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
                $quiz1=mysql_fetch_assoc(mysql_query("SELECT q. * , l.id AS lesid, l.name as les_name, l.file_name
                        FROM `int_quiz` q
                        LEFT JOIN master_lessons l ON q.lesson_id = l.id
                        WHERE q.id =".$row['quiz_id']));
                        
                        $lesson_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `master_lessons` WHERE id=".$row['lesson_id']));
                        
                        
                        
                        
                        //print_r($quiz); die; 
                        $lesson_download="https://intervene.io/questions/uploads/lesson/".$lesson_det['file_name'];// 4358Question.pdf
                        
                        
                        ?>
                     <tr>
                        <td>
                           <?php //=$row['braincert_board_url'];?> <br/>
                           <?php //='=='.$quiz['les_name'] ?>
                           <span> <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?></span>   <br>   
                           <span  class="btn btn-success btn-xs">
                           <?=$SesTime=date_format(date_create($row['ses_start_time']), 'h:i a');#?>
                           </span> 
                           <br/>
                           <strong class="text-primary">
                           Objective:</strong> <?=$quizo['objective_name']?>
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
                        </td>
                        <td>
                           <strong class="text-primary">School:</strong><?=$int_school['SchoolName']?><br/>         
                           <!--   <strong class="text-primary">Teacher:</strong><?php  //=$int_th['first_name']; ?><br/> -->
                           <span class="btn btn-primary btn-xs"><?=$tot_std ?>-<?=$status_arr['STU_ASSIGNED']?></span>
                           <br/>
                           <?php  
                              //$ses_time_before=-10; # 45X60# entire time of session
                                    ?> 
                           <span style="display:none;"> <?='Time Dif==='.$in_sec;?><br/></span>
                           &nbsp; &nbsp;
                           <?php 
                              // from session time till 2hr Launch  button show to tutor
                              if($row['tut_teacher_id']==$tutor&&$in_sec>$ses_2hr_before){ 
                              // $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'
                              //10 sec before 
                              $board_url=$tutor_new_board_url; // TutorProfileUrl
                              // i-frame url //
                              $board_url_ifame='tutor_board.php';
                              
                              
                               ?> 
                           <br/>      
                           <a href="my-sessions.php?sesid=<?=$row['id']?>"  target="_blank"
                              class="btn btn-success btn-md">
                           Launch OR Prepare for Tutor Session</a>
                           <?php  } ?>
                           <?php
                              //after sessionTime: 45min after Survey button Display.
                              
                              // $in_sec=-2701; //Testingn // $in_sec=-3; //Testingn
                              
                              // if($in_sec<0){ //just after session started.
                              
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
                           <strong class="text-primary">Session id:</strong><?=$row['id']?><br/> 
                           <strong class="text-primary">Board:</strong>
                           <span class="btn btn-success btn-xs">
                           <?=ucwords($row['board_type'])?></span>  <br/> 
                           <strong class="text-primary">Created date:</strong>    
                           <?=date_format(date_create($row['created_date']), 'F d,Y');?>  
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
               </div>
               <!-- /.ct_display -->
               <input type="hidden" id="arr-user" name="arr-user" value=""/>
            </form>
         </div>
         <!-- /#content -->
         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>
<!-- /#header -->
<?php include("footer.php"); ?>