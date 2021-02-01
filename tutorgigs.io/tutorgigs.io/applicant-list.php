<?php
// List of Teachers 
/***
 * @ http://tutorgigs.io/
 * @  tutor-list :: 
 * 
 * **/

 @extract($_GET) ;
@extract($_POST) ;

include("header.php");

$login_role = $_SESSION['login_role'];

//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}


if(isset($_POST['Accept_legal_stuff'])){
    $getid=$_POST['Accept_legal_stuff']; // mysql_query
 $tutor_stages_arr= array('application' =>1, // Application OK
  'quiz' => 1,
  'interview' =>1,
  'background_checks' =>1,
  'payment_info' => 1,
   'legal_stuff' =>1,
  'training' =>0);// 

  $regis_state_str=serialize($tutor_stages_arr);

  //////////
$next_state_url="training.php"; // mysql_query
    $Update=mysql_query(" UPDATE `gig_teachers` SET  signup_state='$regis_state_str',
      legal_stuff='1',all_state_url='$next_state_url' WHERE id=".$getid);
   header("Location:".$_SERVER['PHP_SELF']); exit;
}

////////////
////Accept Interview 
if(isset($_POST['Accept'])){
	//print_r($_POST);   die;
	//echo 'Accepted '; // Move to tutor loist

//////////Validate User State:////////

	$getid=$_POST['Accept']; // mysql_query
	$next_state_url="application_status.php"; // Complted
	$next_state_url="background_checks.php";

	  $tutor_stages_arr= array('application' =>1, // Application OK
  'quiz' => 1,
  'interview' =>1,
  'background_checks' => 0,
  'payment_info' => 0,
   'legal_stuff' => 0,
  'training' =>0);// 

	$regis_state_str=serialize($tutor_stages_arr);
    // all_application_state_approved
	//$Update=mysql_query(" UPDATE `gig_teachers` SET all_state= 'yes',all_state_url='$next_state_url',status_from_admin='all_application_state_approved' WHERE id=".$getid);
     // application  quiz  interview 
	$Update=mysql_query(" UPDATE `gig_teachers` SET  signup_state='$regis_state_str',application='1',quiz='1',interview='1',
		all_state_url='$next_state_url',status_from_admin='background_checks_pending' WHERE id=".$getid);
	//echo 'Moved to turor list ';
   header("Location:".$_SERVER['PHP_SELF']); exit;
}




////Accept Interview 
if(isset($_POST['Reject'])){
	
	//echo 'Reject '; // Rejected , Out from application . s
   // status_from_admin  === Rejected from admin side
	// URL- SET rejected_application
	$next_state_url='rejected_application.php';
	$getid=$_POST['Reject']; // mysql_query

	 $Update=mysql_query(" UPDATE `gig_teachers` SET all_state_url='$next_state_url',status_from_admin='interview_rejected' WHERE id=".$getid);
	
	 header("Location:".$_SERVER['PHP_SELF']); exit;
}




$error='';
$id = $_SESSION['login_id'];

if(isset($_POST['delete-user'])){
	$arr = $_POST['arr-user'];
	if($arr!=""){
        //Profiles delete
        $tutor_profiles = mysql_query("DELETE FROM tutor_profiles WHERE tutorid IN ('$arr')", $link);


	$query = mysql_query("DELETE FROM gig_teachers WHERE id IN ('$arr')", $link);
            
            //// Delete Role Table...
           
	}
        
        echo "<script>alert('#Record deleted..');</script>";
        ///
        
}



$schools = mysql_query("SELECT * FROM `gig_teachers` WHERE `status` = 1");
?>
<script>

function myFunction() {
    var txt;
    var r = confirm("Are you sure?.");
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

///////////////
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
			return confirm('Do you want to delete '+count+' user?');
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
                                <?php
                                //action=Search
                                $qq=" SELECT * FROM gig_teachers WHERE `all_state` = 'No' ";
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
                               
                                
					<form id="search-users" method="GET" action="">
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
                            
                            
				<form id="form-manager" class="content_wrap" onsubmit="return myFunction();"   method="post">
					<div class="ct_heading clear">
						<h3>Applicant List(<?=$tot_record?>)</h3>
						<ul>
                         <!-- <li><a href="manage-applicant.php" ><span class="fa fa-plus-circle"></span></a></li> -->
						<li>
					      <button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
						</li>
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
									<col width="220">
									<col width="80">
									<col width="80">
									<col width="120">
									<col width="155">
								</colgroup>
								<tr>
									<th>#</th>
									<th>Email</th>
									<th>First Name</th>
                                    <th>Last Name</th>
									<th> Phone</th>
									<th>Status</th>
								</tr>
								<?php
                                if( mysql_num_rows($results) > 0 ) {
							    while( $row = mysql_fetch_assoc($results) ) {
										// applicant_detail.php?tid=93	
								?>
							 <tr>
                              <td>
		                        <input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>
							 </td>						
                                                                                        
							 <td>
                                <a href="applicant_detail.php?tid=<?=$row['id']?>"><?php echo $row['email']; ?></a>
                                <br/> 
                                <a  class="btn btn-success btn-xs" href="applicant_detail.php?tid=<?=$row['id']?>">View</a>    
                            </td>
                                            <td>
                                            <?=$row['f_name']; ?> <br/>

                                            
                                            
                                            </td>
                                            <td><?=$row['lname']; ?></td>
                                            <td>
                                              <?=$row['phone']; ?>
                                            </td>
                                            
                                            <td title="Show Applicant status">
                                            <?php 
                                            // $next_state_url='rejected_application.php';
                                            $curr_url=$row['all_state_url'];
                                            $status=null;

                                           //   $curr_url='interview.php'; //TEST 
                                            $st_class="btn btn-primary btn-sm";
                                            // if inteview passed. //
                                             // echo '=='.$curr_url;   // : 4th Stage. 
                                            //   if($row['interview']=='payment_info.php'){
                                            // training.php

                                             if($row['status_from_admin']=='interview_rejected'){
                                              // Rejected :: interview_rejected
                                              $st_class="btn btn-danger btn-sm";
                                              $status='Application Rejected';

                                             }elseif($curr_url=="training.php"){
                                               $status='Training Pending';
                                               $st_class="btn btn-warning btn-sm";
 
                                             
                                            } elseif($curr_url=="application.php"){



                                              $st_class="btn btn-warning btn-sm";

                                              $status='Application Pending';
                                            } elseif($curr_url=='legal_stuff.php'){
                                               // payment_info ,  legal_stuff
                                               	$st_class="btn btn-danger btn-sm";
                                               	 $status='Legal stuff Pending';
                                               	 
                                               	 if($row['payment_info']==2)
                                               	 	 $status='Legal stuff-Pending for approval';

                                            }elseif($curr_url=='payment_info.php'){
                                            	//$status='Payment info Pending';
                                            	// pending |approved.  :: payment_info , 0,1,2
                                            	if($row['payment_info']==0){
                                            		$st_class="btn btn-danger btn-sm";
                                            		$status='Payment info Pending';
                                            	}elseif($row['payment_info']==1){
                                            		$status='Payment info approved';
                                            	}


                                            }elseif($row['interview']==1){

                                            	$st_class="btn btn-danger btn-sm";
                                            	$status='Background Checks Pending';

                                            }elseif($curr_url=="quiz.php"){

                                            	$status='Quiz Pending';
                                            }elseif($curr_url=="interview.php"){
                                            	$status='Interview Pending';
                                            	// Last 2 state

                                            }elseif($curr_url=="rejected_application.php"){
                                            		$status='Rejected';
                                            		//$st_class="btn btn-danger btn-sm";
                                            }elseif($curr_url=="quiz_result.php"&&$row['status_from_admin']=='failed'){
                                            	// Failed Failed// status_from_admin
                                          $status='Failed Application'; $st_class="btn btn-danger btn-sm";
                                            		
                                            		
                                            }
                                            ######################





                                            // otherwise remove from application
                                            //  status
                                            //  application.php'; quiz.php interview  ,application_status
                                          //  echo 'State URL-' ,$row['all_state_url'] , '<br/>';
                                            // background_checks

                                            ?>
                                          <!--   <a class="btn btn-success btn-xs" href="">Pending</a> -->


                                        <?php if(isset($status)){?>     <p><span class="<?=$st_class?>"> <?=$status?> </span> </p> <?php }?>

                                            <?php  //if($curr_url=="interview.php"){
                                     // if($curr_url=="interview.php"&&$row['status_from_admin']!='interview_rejected'){ 
                                     if($row['status_from_admin']!='interview_rejected'){ 
                                        if($curr_url=="interview.php"||$row['legal_stuff']==2){    
                                        // interview |     legal_stuff -A/R by admin 
                                          $state_btn=($row['legal_stuff']==2)?'Accept_legal_stuff':'Accept';# Accept=inteview

                                              ?>
                                            <button name="<?=$state_btn?>" class="btn btn-success btn-xs" value="<?=$row['id']?>" type="submit">Accept</button>
                                             <button name="Reject" class="btn btn-danger btn-xs" value="<?=$row['id']?>" type="submit">Reject</button>

                                             <?php   } // pprove statues 

                                             } ?>

                                             <?php  
                                             // $row['status_from_admin']=='interview_rejected'
                                             if($row['all_state_url']=="background_checks.php"&&$row['status_from_admin']!='interview_rejected'){?>
                                            
                                             <a class="btn btn-primary btn-xs" href="background_check_setting.php?tid=<?=$row['id']?>">Manage approve</a>
                                              <?php } ?>
                                             <span>Create Date: </span>
                                              <?php
                                                $datetime = strtotime($row['created_date']);
                                                if ($datetime == 0) {
                                                    echo "00h 00' 00/00/0000- ";
                                                } else {
                                                    echo date('H\h s\' - d/m/Y', $datetime);
                                                }
                                                ?>

                                              

                                              
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