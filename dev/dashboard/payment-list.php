<?php
// @ list-tutor-sessions
/*****
 @ filer by : date selected. 
 * 
 @ complete-sessions
 * ****/


// List of Teachers 
 @extract($_GET) ;
@extract($_POST) ;

include("header.php");
//include("inc/validate_site_access.php");
/////////////////////////
//////////Validate Site Access//////////
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}

$login_role = $_SESSION['login_role'];
$page_name="Payment List";
//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}

// action
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
       header("location:feedback_form_1.php"); exit;
}




$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
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
                        <?php
                          $start_date = date('Y-m-d h:i:s');
                                $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                //$qq.=" AND tut_status='STU_ASSIGNED' "; // feedback_id
                                
                                $qq=" AND feedback_id>'0' ";
                                $qq.=" AND tut_teacher_id='$id' ";#only Assigned
                               $qq.=" AND ses_start_time<'$start_date' ";
                                
                                if(isset($_GET['id']))
                                $qq.=" AND id='".$_GET['id']."' ";#only Assigned
                    $k=" SELECT COUNT(*) AS tot_paid FROM int_schools_x_sessions_log WHERE 1 ".$qq; 
                   // $paid
                $paid=mysql_fetch_row(mysql_query($k."  AND payment_status=1 "));
                 $un_paid=mysql_fetch_row(mysql_query($k."  AND payment_status=0 "));
                   // echo  $un_paid[0] ; die;
                        
                        ?>
                        
			<div id="content" class="col-md-8">
			<div class="row">
				<div class="col-md-6">
					 <div class="well well-sm" style=" margin-bottom: 0px;border-radius: 0px;">Pending</div>
					 <div class="well well-sm" style="background:  #fff; border-radius: 0px;"><?=$un_paid[0]?> Sessions</div>
				</div>
				<div class="col-md-6">
					 <div class="well well-sm" style=" margin-bottom: 0px;border-radius: 0px;">Paid</div>
					 <div class="well well-sm" style="background:  #fff; border-radius: 0px;"><?=$paid[0]?> Sessions</div>
				</div>
			</div>
			
			
                         
                            
                     
                     
                                <?php
                              
                    /////////////////////////
                           $sql.=$qq;     
                      // echo $qq;                                               
		$results = mysql_query($sql);
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
									
									<col width="465">
									
								</colgroup>
								<tr>
								
									<th>Sessions Date/Time</th>
									
                                                                       
									<th> Status</th>
									
									
									
								</tr>
								<?php
                                             if( mysql_num_rows($results) > 0 ) {
		while( $row = mysql_fetch_assoc($results) ) {
			// teacher_id								
			// TutTeacher Statatus					
              // $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=".$row['teacher_id']));
            //   $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));
              //   $tot_std=($tot_std>0)?$tot_std:"XX";
             $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));    
         
             
          //$quiz objective_name             
                   $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$row['id']."' ";
           // $resss=mysql_query($q);
            
             
            // special_notes
           // $row['special_notes']=(!empty($row['special_notes']))?$row['special_notes']:"NA";
            // payment status :
            
            ?>
										
                                                                
                                                                
                                                                <tr>
                                                                    
											
                                                                                        
				<td>
                                     
                            
                          <span><?=date_format(date_create($row['ses_start_time']), 'F d,Y');?> </span> 
                          <br>  <span  class="btn btn-success btn-xs">
                                       
                     <?=$SesTime=date_format(date_create($row['ses_start_time']), 'h:i a');#?>
                               </span>
                           </td>
                                             
                                            
                                            <td>
                            <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>         
                               <?php 
                               // payment_status payment_date
                               if($row['payment_status']==1){
                               ?>                 
                                            
                         <strong class="text-primary">Payment Date:</strong>
                                   <?=date_format(date_create($row['payment_date']), 'F d,Y, h:i a ');?><br/> 
                   <span class="btn btn-success btn-xs" title="Payment Done">Paid</span>    
                                 <br/>        
                                        
                               <?php }else{?> 
            <span class="btn btn-danger btn-xs" title="Unpaid">Unpaid</span>                   
                               <?php  }?> 
                                         
                                          
                                          
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