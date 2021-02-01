<?php
// @ list-tutor-sessions
/*****
 @ filer by : date selected. 
 * 
 @ complete-sessions
 @List of Tutor sessions those end SES_START_TIME>>end_time<curr_time. and Feedback Pending
 @ MAR 30,19
 * ****/


// List of Teachers 
 @extract($_GET) ;
@extract($_POST) ;

include("header.php");


$login_role = $_SESSION['login_role'];
$page_name="List of Complete Sessions";
 $curr_time= date("Y-m-d H:i:s"); #currTime

///Tutor Access////////
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}




$error='';
$id = $_SESSION['ses_teacher_id'];


if(isset($_POST['submit_feedback'])){
    # clear past session
     unset($_SESSION['form_2']); unset($_SESSION['form_3']); 
unset($_SESSION['form_4']); unset($_SESSION['form_5']);  
  unset($_SESSION['form_1']); //unset($_SESSION['feedback_ses_id']);
    //////////////
    $_SESSION['feedback_ses_id']=$_POST['submit_feedback']; //
       header("Location:feedback_form1.php"); exit;
}




//$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>

<script>
function myFunction() {
    var txt;
    //var r = confirm("Press a button!\nEither OK or Cancel.\nThe button you pressed OK,Reject session.");
     var r = confirm("Are you sure.");
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
	$(document).ready(function(){
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
				
				
                     <!--         <div class="table-responsive"> </div>-->
                                <?php
                                //action=Search
                                
                                  
                                $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                //$qq.=" AND tut_status='STU_ASSIGNED' "; // feedback_id
                                $qq.=" AND feedback_id='0' ";
                                $qq.=" AND tut_teacher_id='$id' ";#only Assigned

                               //$qq.=" AND ses_start_time<'$curr_time' "; // Come just after session start.
                                 $qq.=" AND ses_end_time<'$curr_time' "; // after session end time
                                
                                if(isset($_GET['id']))
                                $qq.=" AND id='".$_GET['id']."' ";#only Assigned
                                //echo  $qq;
                              $results = mysql_query($qq);
                               $tot_record=mysql_num_rows($results);

                                 
                                                                       
                                                                        
                            
                            ?>
                            
                            
				<form onsubmit="return myFunction();" id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3><?=$page_name?>(<?=$tot_record?>)</h3>
						
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if($error != '') {
							echo '<p class="error">'.$error.'</p>';
						} else {
						
                                              
                                                    
                                                    ?>
							<table class="table-manager-user col-md-12">
								<colgroup>
									
									<col width="220">
									
									<col width="230">
									<col width="100">
									
									<col width="135">
								</colgroup>
								<tr>
								
									<th>Sessions Date/Time</th>
									<th>detail</th>
                                                                       
									<th> Status</th>
									
									
									<th>Session details</th>
								</tr>
								<?php
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
            ?>
										
                                                                
                                                                
                                                                <tr>
                                                                    
											
                                                                                        
				<td>
                                     
                             <span>
                                    <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br>        
                                      
                                    </span>       
                                    
                                    
                                    
                                       
                                    <span  class="btn btn-success btn-xs">
                                       
                     <?=$SesTime=date_format(date_create($row['ses_start_time']), 'h:i a');#?>
                              
                                     
                                 </span> 
                                    
                                    
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
                                
                                </td>
                 
                                           
                                            
                                <td>
                            <strong class="text-primary">School:</strong><?=$int_school['SchoolName']?><br/>         
                           <strong class="text-primary">Teacher:</strong><?=$int_th['first_name']; ?><br/>
                           
                              <span class="btn btn-primary btn-xs"><?=$tot_std ?>-<?=$status_arr['STU_ASSIGNED']?></span>

                             
                                    
                                
                                
                                </td>
                                
                                
                                           
                                            <td>
                                  
                                      <?php if($row['tut_teacher_id']>0){?>
                                  
                                  
                                  <?php if(!empty($row['app_url'])){ // ACCEPTED?>
                                                <br/>
                                   <span class="btn btn-success btn-xs">Session Assigned</span>
                                 
                                  
                                  <?php  }else{ //  -waiting for Acceptance?>
                                   <span class="btn btn-warning btn-xs">Session Assigned</span>
                                   <?php  } ?>
                                   
                                   
                                  
                                  <strong class="text-primary">AssignedTo:</strong> 
                                  <?=$tut_th['f_name']." ".$tut_th['lname']?>(ME)<br/>
                                   
                                      <?php }else{?>
                                  <span class="btn btn-danger btn-xs"><?=$status_arr['SES_NOT_ASSIGNED']?></span>
                                     
                                        
                                    <?php } ?>
                                            
                                            </td>
                                            
                                            <td>
                            <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>         
                                                
                                                
                                                <br>
                                   <strong class="text-primary">Created date:</strong>    
                               <?=date_format(date_create($row['created_date']), 'F d,Y');?>       
                                                
                                                <br/>
                                                <?php 

                                                $btn_text='Tutorial Session Survey';

                                                ?>
                                                
                                                <button style=" background-color:orange;
                                            color:#fff; border:1px solid orange"   type="submit" name="submit_feedback" title="Complete Session"
                                      class="btn btn-default" value="<?=$row['id']?>">Session Survey</button>                    
                         

         <!--     <strong class="text-primary">Session:</strong>incomplete<br/>   -->
                                          
                                         
                                          
                                          
                                            </td>
                                                                                        
                                                                                        
                                                                                        
										</tr>
								<?php
										}
									} else {
										echo '<div class="clear"><p>There is no item found!</p></div>';
									}
								?>
							</table>
						<?php } ?>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        <input type="hidden" id="arr-user" name="arr-user" value=""/>
				</form>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>