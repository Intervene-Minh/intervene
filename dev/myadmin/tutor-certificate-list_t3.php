<?php

@extract($_REQUEST) ;
include("header.php");

$error='';
$login_role = $_SESSION['login_role'];
////////////////////////
if(isset($_GET['msg'])){
  $_SESSION['msg_success']='newrow id created success!';

 }
 $today = date("Y-m-d H:i:s"); 
///////////////////////
 // echo  '@@@' , $today = date("Y-m-d H:i:s"); 
 // die; 
 // Current time   2019-12-31 05:40:00
 $list_of_tutor_on_board="SELECT *
FROM  launch_ses_log
WHERE tutoring_end_at >= '$today' GROUP BY tutor_id ";

// echo  '<pre>';
// echo $list_of_tutor_on_board; die; 

$Result_tutors=mysql_query($list_of_tutor_on_board);
$arr_online_tutors=[];
while ($row=mysql_fetch_assoc($Result_tutors)){
   $arr_online_tutors[]=$row['tutor_id']; 
}
/////////
  //echo '====';
  //print_r($arr_online_tutors); die; 

///////////////////
if(isset($_GET['view'])){ // ?view=11
	 $getid=$_GET['view'];// mysql_fetch_assoc
	 $tutor=mysql_fetch_assoc(mysql_query("SELECT * FROM gig_teachers WHERE id=".$getid)); 
	 $_SESSION['ses_teacher_id']=$tutor['id'];
	 $_SESSION['ses_access_website']='yes';// Tutor
	  $_SESSION['login_user']=(!empty($tutor['f_name']))?$tutor['f_name']:$tutor['email']; // Initializing Session
      $_SESSION['login_mail']=$tutor['email'];
      
      //khowlett jira ManagerPortal
      //$_SESSION['login_role']=1; // ses_curr_state_url
      
      ///$_SESSION['ses_curr_state_url']='home.php';
      // index.php
      // https://tutorgigs.io/dashboard/home.php
      $url='https://tutorgigs.io/dashboard/index.php';
	 // view
       header("Location:".$url);exit;
	  //print_r($query); die; 


	}


/* update notification status*/

if(isset($_GET['n'])){
$str = "UPDATE `sessioncancelnotification` SET `ReadStatus`='1' WHERE `ID`='".$_GET['n']."'";
         $r=mysql_query($str);
         if($r > 0)
         	{
         	 $url='https://tutorgigs.io/myadmin/tutor-list.php';
         	 header("Location:".$url);exit;
         }


}
if(isset($_GET['al'])){
$str = "UPDATE `sessioncancelnotification` SET `ReadStatus`='1' WHERE `ReadStatus`=0";
         $r=mysql_query($str);
         if($r > 0)
         	{
         	 $url='https://tutorgigs.io/myadmin/tutor-list.php';
         	 header("Location:".$url);exit;
         }


}
/* /*/
$id = $_SESSION['login_id'];
/////////
if(isset($_POST['delete-user'])){
			$arr = $_POST['arr-user'];
			if($arr!="")
			{
			$total_deleted=count(explode(',', $arr));

			//echo $total_deleted, '==total_deleted==',$arr; die; 

			$tutor_profiles = mysql_query("DELETE FROM tutor_profiles WHERE tutorid IN (".$arr.")" );
			$query =mysql_query(" DELETE FROM gig_teachers WHERE id IN (".$arr.")" );


			//// Delete Role Table...

			}
			$set_msg=$total_deleted.'-Tutor deleted! ';

			echo "<script>alert('".$set_msg."');</script>";
			///
        
}

//////Mark Active//////mark_active
if(isset($_POST['mark_active'])){
	$arr = $_POST['arr-user'];
	print_r($_POST); die; 
	if($arr!=""){

	}
}

//////Mark Active//////
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
		///////Mark Activate////////mark-active-id
		$('#mark-active-id').on('click',function(){
			var count = $('#form-manager .checkbox:checked').length;
			$('#arr-user').val("");
			$('#form-manager .checkbox:checked').each(function(){
				var val = $('#arr-user').val();
				var id = $(this).val();
				$('#arr-user').val(val+','+id);
			});
			var str = $('#arr-user').val();
			$('#arr-user').val(str.replace(/^\,/, ""));
			return confirm('Are you want to activate '+count+' user?');
		});
		///////Mark Activate////////
	});
</script>
<style type="text/css">
.tust{padding: 0px 0px 0px 15px;border-radius: 60px;margin-left: 17px;}
.tust_0{background: red;}
.tust_1{background: green;}
.ListNot{list-style: none;padding: 10px 20px 0px 35px;}
.ListNot li{ padding: 1px }
.ListNot a { text-decoration:none; }
.dataview{height: 150px;overflow-x: auto;}
</style>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">

				 <?php   include "msg_inc_1.php";  ?>
                            <div class="table-responsive">
                                <?php
                                //action=Search
                                $qq=" SELECT * FROM gig_teachers WHERE `all_state` = 'Yes' ";
                                if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                   if(!empty($email))
                                 $qq.=' AND email LIKE "%'.$email.'%"  ';
                                    if(!empty($first_name))
                                 $qq.=' AND f_name LIKE "%'.$first_name.'%"  ';
                                     if(!empty($last_name))
                                 $qq.=' AND lname LIKE "%'.$last_name.'%"  ';
                                if(!empty($created_by) && $created_by!='no')
                                 $qq.=' AND created_by= "'.$created_by.'" ';
                         		}
                         		$qq.= ' order by id desc ';
                                //=$qq
                                ?>




					<form id="search-users" method="GET" action="">
						<table class="table">
							<tbody><tr>
								
								<td><input name="email" class="form-control" placeholder="Email" value="<?=$_GET['email']?>" type="text"></td>
								<td><input name="first_name" class="form-control" placeholder="First Name" value="<?=$_GET['first_name']?>" type="text"></td>
								<td><input name="last_name" class="form-control" placeholder="Last Name" value="<?=$_GET['last_name']?>" type="text"></td>
								<td>
								 <select class="form-control" name="created_by">
									<option value="no">Front Application</option>
									<option value="2">Created-By Admin</option>
							     </select>
						         </td>
						         <td><input name="action" class="btn" value="Search" type="submit"></td>
							</tr>
						</tbody></table>
					</form>
				</div>
                            <?php 
                       
					//// $limit                                        
					$results = mysql_query($qq);
					$tot_record=mysql_num_rows($results);?>
                            
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>Tutors List(<?=$tot_record?>)</h3>
						
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if($error != '') {
							echo '<p class="error">'.$error.'</p>';
						} else { ?>
							<table class="table-manager-user col-md-12">
								<colgroup>
									<col width="200">
									<col width="150">
									<col width="150">
									<col width="150">
									<col width="100">
									
								</colgroup>
								<tr>
									
									<th>Email</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th> Phone</th>
									
									<th>Login Status</th>
								</tr>
								<?php
								if( mysql_num_rows($results) > 0 ) {
									while( $row = mysql_fetch_assoc($results) ) {
										$tutorId=$row['id'];

								///Newrow ID 
										$Tutor_newrow=mysql_fetch_assoc(mysql_query(" SELECT * FROM  newrow_x_tutors 
									WHERE tutor_intervene_id= '$tutorId' "));
											
								?> <tr>
                                                                    
										
                    <td>
                    	<?php 

                    	if($row['created_by']==2){

                    		?>

                        <a href="edit-admintutor.php?tid=<?=$row['id']?>"><?php echo $row['email']; ?></a> 
<?php }
 else {?>
                       <a href="edit-tutor.php?tid=<?=$row['id']?>"><?php echo $row['email']; ?></a> 

                 <?php } ?>
                
                                            </td>
                                            <td><?=$row['f_name']; ?>   </td>
                                            <td><?=$row['lname']; ?></td>
                                            <td>
                                              <?=$row['phone']; ?>
                                            </td>
                                            
                                            
                                          

                                            <td>
                                            <a  class="btn btn-success btn-xs" href="manage_certificate.php?tid=<?=$row['id']?>">Certificate List</a>
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
<script type="text/javascript"> setInterval(function(){ location.reload(); }, 50000);</script>
<?php
$str="SELECT sn.*,ins.ses_start_time FROM `sessioncancelnotification` AS sn LEFT JOIN `int_schools_x_sessions_log` AS ins ON sn.SessionID=ins.id
WHERE sn.ReadStatus=0 AND sn.CancelTime > DATE_SUB(NOW(), INTERVAL 48 HOUR) ORDER BY sn.ID DESC";
$result = mysql_query($str);
$totalRow=mysql_num_rows($result);
if($totalRow>0){ 
?>
<script>
  $(document).ready(function(){

  	$('#ViewNotifaction').modal('show');
  });
</script>
<div class="modal fade" id="ViewNotifaction" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Canceled Job Notification</h4>
            <a href="https://tutorgigs.io/myadmin/cancelled_sessions.php" class="btn btn-primary">View All</a>
        </div>
         <div class="dataview">
         	<ul class="ListNot">
         		 <?php 
         		while($row=mysql_fetch_assoc($result)){

         		$em_ses_time=date('h:i:s A',strtotime($row['ses_start_time']));
         		$em_ses_date=date('M d,Y',strtotime($row['ses_start_time']));

         		//$date=date_create($row['CancelTime']);
			   // $datetime= date_format($date,"M h:i:s a");?>
         		
			<li><strong>Tutor Name   :</strong>   <?php echo $row['TutorName'];?></li>
			<li><strong>Session Time :</strong> <?php echo $em_ses_time; ?> CST</li>
			<li><strong>Session ID   :</strong> <?php echo $row['SessionID'];?></li>
			<li><strong>Cancel Reason: </strong> <?php echo $row['CancelReason'];?></li>
			<li><a href="tutor-list.php?n=<?php echo $row['ID']?>" class="btn btn-success">Resolve</a></li>
			<hr>
         	<?php } ?>
         	</ul>
         </div>
         <div class="modal-footer">
         	<a href="tutor-list.php?al=1" class="btn btn-danger">All Read Done</a>
         </div>
  </div>
   </div>
</div>
<?php }?>
<?php include("footer.php"); ?>