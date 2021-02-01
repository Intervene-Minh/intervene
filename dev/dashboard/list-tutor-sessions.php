<?php
// @ list-tutor-sessions
/*****
 * 
# Show list of Sesson in tutAdmin
 -Only those session, which Have Students , 
 -- Student assigned by teacher, quiz by intTeacher 
 *  // tutor sesion status : compacted incomplete, started, 
>Start working on teacher assigned from. Gig admin
 -Waiting for approval's from gigTeacher,
 -if Accept > aww app url ,==> show Students
 - Reject then- not show to student, 
 # tutTeacher#tut_accept_time & app_url , fiiled if tutTeacher accept
 
 * pending# last time to Accept Session by tutTeacher
 *  #  STU_ASSIGNED ASSIGNED >>int staus
 # tut Stauts >>SES_ASSIGNED NOT_ASSIGNED
 * ****/


// List of Teachers 
 @extract($_GET) ;
@extract($_POST) ;

include("header.php");

$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";
//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}

// action





$error='';
$id = $_SESSION['login_id'];

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
                                $qq=" SELECT * FROM int_schools_x_slots_master WHERE 1 ";
                                $qq.=" AND tut_status='STU_ASSIGNED' ";
                              // All slot -student had Assiened
                                //////////
                                if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                     
                                  
                                   if(!empty($email))
                                 $qq.=' AND email LIKE "%'.$email.'%"  ';
                                    if(!empty($first_name))
                                 $qq.=' AND f_name LIKE "%'.$first_name.'%"  ';
                                     if(!empty($last_name))
                                 $qq.=' AND lname LIKE "%'.$last_name.'%"  ';
                                    
                                   
                                }
                                 
                                //=$qq
                                
                                ?>
                               
                                
                                <form id="search-users" method="GET" action=""  style="display:none;">
						<table class="table">
							<tbody><tr>
								<td><label>Search:</label></td>
								<td><input name="email" class="form-control" placeholder="Email" value="<?=$_GET['email']?>" type="text"></td>
								<td><input name="first_name" class="form-control" placeholder="First Name" value="<?=$_GET['first_name']?>" type="text"></td>
								<td><input name="last_name" class="form-control" placeholder="Last Name" value="<?=$_GET['last_name']?>" type="text"></td>
								
								<td><input name="action" class="btn" value="Search" type="submit"></td>
							</tr>
						</tbody></table>
					</form>
				</div>
                            
                            
                            
                            <?php 
                       
                                //// $limit                                        
                                                                        
                                                                       
		$results = mysql_query($qq);
                $tot_record=mysql_num_rows($results);
									
                                                                        
                                                                        
                            
                            ?>
                            
                            
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3><?=$page_name?>(<?=$tot_record?>)</h3>
						<ul>
							<li><a href="#" class="edit-user"><span class="glyphicon glyphicon-pencil"></span></a></li>
							
						</ul>
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if($error != '') {
							echo '<p class="error">'.$error.'</p>';
						} else {
						
                                              
                                                    
                                                    ?>
							<table class="table-manager-user col-md-12">
								<colgroup>
									<col width="30">
									<col width="160">
									
									<col width="220">
									<col width="120">
									
									<col width="155">
								</colgroup>
								<tr>
									<th>#</th>
									<th>Sessions Date/Time</th>
									<th>detail</th>
                                                                       
									<th> Status</th>
									
									
									<th>Date Created</th>
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
             
             
               ?>
										
                                                                
                                                                
                                                                <tr>
                                                                    
					<td>
		<input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>
											</td>						
                                                                                        
				<td><a href="#"><?php echo $row['slot_date_time']; ?></a> </td>
                 
                                           
                                            
                                <td>
                            <strong class="text-primary">School:</strong><?=$int_school['SchoolName']?><br/>         
                           <strong class="text-primary">Teacher:</strong><?=$int_th['first_name']; ?><br/>
                           <strong class="text-primary">Students:</strong><?=$tot_std ?><br/>
                              <span class="btn btn-primary btn-xs">STU_ASSIGNED</span>
                                </td>
                                
                                
                                           
                                            <td>
                                  
                                      <?php if($row['tut_teacher_id']>0){?>
                                  
                                  
                                  <?php if(!empty($row['app_url'])){?>
                                  <span class="btn btn-success btn-xs">ACCEPTED</span>
                                  <br/><a> Aww app Url-https://www.youtube.com/</a>
                                  <?php  }else{?>
                                   <span class="btn btn-warning btn-xs">SES_ASSIGNED</span>
                                  -waiting for Acceptance
                                  <?php  } ?>
                                  <strong class="text-primary">AssignTo:</strong> 
                                  <?=$tut_th['f_name']." ".$tut_th['lname']?><br/>
                                      <?php }else{?>
                                  <span class="btn btn-danger btn-xs">NOT_ASSIGNED</span>
                                     
                                       <a href="javascript:void(0);" 
                                   onclick="sent_form('assign_a_teacher.php', {getid:'<?=$row[id]?>',productname:'58',detail:'this is a text.'});"
                                   style="text-decoration:underline;">Assign teacher</a> 
                                    <?php } ?>
                                            
                                            </td>
                                            
                                            <td>
                                                <?php
                                                $datetime = strtotime($row['created_date']);
                                                if ($datetime == 0) {
                                                    echo "00h 00' 00/00/0000- ";
                                                } else {
                                                    echo date('H\h s\' - d/m/Y', $datetime);
                                                }
                                                ?><br/>
                                          <strong class="text-primary">Session:</strong>incomplete<br/>       
                                                
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