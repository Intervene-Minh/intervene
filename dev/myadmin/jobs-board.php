<?php
/****
 @intervene
 @ tutoiring detail popup

 @ all upcoming : unclaimed-job 
 * ****/




 @extract($_GET) ;
@extract($_POST) ;



///////////////////////////

include("header.php");

$login_role = $_SESSION['login_role'];
$page_name="Jobs Board";
//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}

// action
// del_id

if(isset($_GET['del_id'])){
  // Can session , delete detail
   
  $getid=$_GET['del_id'];
  $sql="SELECT * FROM int_schools_x_sessions_log WHERE 1";
    $sql.=" AND id='$getid' ";
 $ses_det=mysql_fetch_assoc(mysql_query($sql));
 
  $school=mysql_fetch_assoc(mysql_query("SELECT * FROM schools WHERE SchoolId=".$_SESSION['schools_id']));
  $school_id=$ses_det['school_id'];// school_id
  //echo 'schol id-'.$school_id; die;
  ////////////////////////////////////////////
    # slot_id
   $d1=mysql_query(" DELETE FROM int_slots_x_student_teacher WHERE slot_id=".$getid);
   $d2=mysql_query(" DELETE FROM int_schools_x_sessions_log WHERE id=".$getid);
   #3.crease:1 sess in account.
   
    $remain_ses=intval($school['avaiable_slots'])+1;
  $a=mysql_query(" UPDATE schools SET avaiable_slots='".$remain_ses."' WHERE SchoolId='".$school_id."' "); //+1
  
    echo "<script>alert('Record deleted..');location.href='list-tutor-sessions.php';</script>"; 
}


//
$error=''; $id = $_SESSION['login_id'];


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
?>
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
	// form.setAttribute("target", "_blank");//

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
</script>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				
				
                            <div class="table-responsive">
                                <?php
                                //action=Search
                                 $start_date = date('Y-m-d h:i:s');
                               
                                 $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                 
                                 if(isset($_GET['id'])){
                                     $qq.=" AND id='".$_GET['id']."' "; 
                                 }else{
                                 // tut_teacher_id
                                 $qq.=" AND tut_teacher_id='0'";    
                                $qq.=" AND ses_start_time>'".$start_date."'";
                                $qq.=" ORDER BY ses_start_time ASC ";
                                 }
                              // echo $qq;
                              // 
                             
                               
                                 $session_type=(isset($session_type))?$session_type:"upcoming" ;
                                if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                  
                                   
                           
                                  if($session_type=="past"){
                                     $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                               
                                $qq.=" AND ses_start_time<'".$start_date."'";
                                $qq.=" ORDER BY ses_start_time DESC "; 
                               
                                  }elseif($session_type=="upcoming"){
                                    $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                 
                                $qq.=" AND ses_start_time>'".$start_date."'";
                                $qq.=" ORDER BY ses_start_time ASC ";   
                                  }elseif($session_type=="all"){
                                   $qq=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                
                                
                                $qq.=" ORDER BY ses_start_time ASC ";   
                               
                                  }
                                  
                                  
                               //    if(!empty($email))
                                // $qq.=' AND email LIKE "%'.$email.'%"  ';
                                
                                    
                                   
                                }
                                 
                               
                            // echo $qq ;// die;
                               // Filter form 
                                ?>
                               
                                
                                
				</div>
                            
                            
                            
                            <?php 
                       
                                //// $limit                                        
                                                                        
                         // echo $qq;                                             
		$results = mysql_query($qq);
                $tot_record=mysql_num_rows($results);
									
                                                                        
                                                                        
                            
                            ?>
                            
                            
				<form id="form-manager" class="content_wrap" action="" method="post">
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
									
									<col width="190">
									
									<col width="220">
									<col width="120">
									
									<col width="155">
								</colgroup>
								<tr>
									
									<th>Session Date/Time</th>
									<th>detail</th>
                                                                       
									<th> Status</th>
									
									
									<th>Session details</th>
								</tr>
								<?php
                                             if( mysql_num_rows($results) > 0 ) {
		while( $row = mysql_fetch_assoc($results) ) {
										
								
               $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name,last_name FROM users WHERE id=".$row['teacher_id']));
               $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));
                 $tot_std=($tot_std>0)?$tot_std:"XX";
             $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));    
         
             $int_school= mysql_fetch_assoc(mysql_query("SELECT district_id,SchoolName FROM schools WHERE SchoolId=".$row['school_id']));     
             // district_id 
          if($int_school['district_id']>0){
          $district=mysql_fetch_assoc(mysql_query(" SELECT  district_name FROM loc_district WHERE id=".$int_school['district_id']));     
          $districtName=$district['district_name'];
          
          }
          
          /// inAdmin Info SELECT * FROM `users` WHERE 1 
         $admin=mysql_fetch_assoc(mysql_query(" SELECT * FROM `users` WHERE id=1 ")); // Def
          
         // Exp time
        $sesStartTime=$row['ses_start_time'];
        $curr_time= date("Y-m-d H:i:s");
         
     $in_sec= strtotime($sesStartTime) - strtotime($curr_time);///604800 #days>+7 days
         
        $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));  
         $quiz['objective_name']=(!empty($quiz['objective_name']))?$quiz['objective_name']:"NA";
         //// list of students 
          $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$row['id']."' ";
            $resss=mysql_query($q);
            $stud_str=array(); // middle_name
            while ($row2=mysql_fetch_assoc($resss)) {
                $stud_str[]=$row2['first_name'].' '.$row2['middle_name'];
            }  
            $stdList=(count($stud_str)>0)? implode(",", $stud_str):"NA";
          ?>
										
                                                                
                                                                
                              <tr>
                                <td> 

                            

                               <span>
                                  <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br>     
                                       
                                    </span> 


                                    
                            <a  href="<?=$ses_det_url?>"   class="btn btn-success btn-xs" title="Start Time">
                    <?=date_format(date_create($row['ses_start_time']), 'h:i a');?></a> 
                                    <br/>
                                    
                                    
                                  

                               

                                 <br> 
                                 <span><strong class="text-primary">Class From:</strong> </span>

                                  <?php  if($row['Tutoring_client_id']=='Drhomework123456'){
                                    $Sessiontype='Drhomework';
                                   ?>
                              <span class="btn btn-danger btn-xs"> Homework Help </span> 
                                <?php }  else {

                                  $Sessiontype='Intervention';
                                  ?>
                              <span class="btn btn-primary btn-xs">Intervention </span> 
                                <?php } ?>

                             <a class="btn btn-danger btn-xs viewSession"
                                      href="javascript:void(0)"
                                      sessionid="<?= $row['id']?>" action="<?= $Sessiontype ?>">Session Detail &amp; Downloads</a>
                                </td>
                                 <td>
                    <a  class="btn btn-danger btn-xs viewSession" href="javascript:void(0)"
                                   SessionID="<?=$row['id']?>"
  action="<?= $Sessiontype ?>">See Details</a>
                                  
                                </td>
                                
                                
                                           
                                            <td>
      
                                      <?php 
                                      $status='<span class="btn btn-warning btn-xs">Session expired!</span>';
                                      
                                      if($in_sec<-3600){ // till 1 hour can
                                      //echo $in_sec.'-Exp session <br/>';
                                      echo $status;
                                      }else{
                                          
                                     
                                      if($row['tut_teacher_id']>0){?>
                                  
                                  
                                  <?php if(!empty($row['app_url'])){ // ACCEPTED?>
                                                <br/>
                                   <span class="btn btn-success btn-xs">Session Assigned</span>
                                 <!-- <br/><a>App Url-https://www.youtube.com/</a>--->
                                  
                                  <?php  }else{ //  -waiting for Acceptance?>
                                   <span class="btn btn-warning btn-xs">Session Assigned</span>
                                   <?php  } ?>
                                   
                                   
                                  
                                  <strong class="text-primary">AssignedTo:</strong> 
                                  <?=$tut_th['f_name']." ".$tut_th['lname']?><br/>
                                  <a href="javascript:void(0);"  class="text-danger"
                                   onclick="sent_form('assign_a_tutor.php', {getid:'<?=$row[id]?>',productname:'101',detail:'this is a text.'});"
                                   style="text-decoration:underline;">Re-assign Tutor</a> 
                                      <?php }else{?>
                                  <span class="btn btn-danger btn-xs"><?=$status_arr['SES_NOT_ASSIGNED']?></span>
                                     
                                       <a href="javascript:void(0);" 
                                   onclick="sent_form('assign_a_tutor.php', {getid:'<?=$row[id]?>',productname:'58',detail:'this is a text.'});"
                                   style="text-decoration:underline;">Assign Tutor</a> 
                                    <?php } 
                                      }
                                    //exp
                                    ?>
                                  
                                  
                                  
                                  
                                  
                                            
                                            </td>
                                            
                                            <td>
                                                <strong class="text-primary">Create Date:</strong><br/>       
                                           <?=date_format(date_create($row['created_date']), 'F d,Y');?> 
                                                
                                                <br/>
                                          <strong class="text-primary">Session:</strong>incomplete<br/>       
                                          <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>
                                          
                                          <a href="session-details.php?sid=<?=$row['id']?>"
                                           style="text-decoration:underline;">View</a>   
                                   &nbsp;

                                   <?php if($in_sec>0){?>
                                   <a title="Delete,This session"
                                      href="list-tutor-sessions.php?del_id=<?=$row['id']?>" class="btn btn-danger btn-xs">Delete</a>    
                                   <?php }?>
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

<script type="text/javascript">
  
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