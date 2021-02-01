<?php
// List of Teachers 
/***

 [https://intervene.atlassian.net/browse/IIDS-156]
  [login_id] => 10
    [login_user] => xdrh
    [login_mail] => rohit@gmail.com
    [login_role] => 0
    [login_status] => 0
    [firstlogin] => 1
    @Newrow iD generate for tutor .
    @generate Newrow ID 

 * 
 * **/

 @extract($_GET) ;
@extract($_POST) ;
include("header.php");


$login_role = $_SESSION['login_role'];
////////////////////////
if(isset($_GET['msg'])){
  $_SESSION['msg_success']='newrow id created success!';

 }




///////////////////
if(isset($_GET['view'])){ // ?view=11
	 $getid=$_GET['view'];// mysql_fetch_assoc
	 $tutor=mysql_fetch_assoc(mysql_query("SELECT * FROM gig_teachers WHERE id=".$getid)); 
	 $_SESSION['ses_teacher_id']=$tutor['id'];
	 $_SESSION['ses_access_website']='yes';// Tutor
	  $_SESSION['login_user']=(!empty($tutor['f_name']))?$tutor['f_name']:$tutor['email']; // Initializing Session
      $_SESSION['login_mail']=$tutor['email'];
      $_SESSION['login_role']=1; // ses_curr_state_url
      ///$_SESSION['ses_curr_state_url']='home.php';
      // index.php
      // https://tutorgigs.io/dashboard/home.php
      $url='https://tutorgigs.io/dashboard/index.php';
	 // view
       header("Location:".$url);exit;
	  //print_r($query); die; 


	}




$error='';
$id = $_SESSION['login_id'];

/////////
if(isset($_POST['delete-user'])){
	$arr = $_POST['arr-user'];
	if($arr!=""){
		

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

                                //khowlett Jira ManagerPortal	
                                //filter by manager	
                                $current_role=$_SESSION['login_role'];

                                $qq=" SELECT * FROM gig_teachers WHERE `all_state` = 'Yes' AND `created_by` = $current_role ";
                                //$qq=" SELECT * FROM gig_teachers WHERE `all_state` = 'Yes' AND `created_by` = '102' ";
                                //$qq=" SELECT * FROM gig_teachers WHERE `all_state` = 'Yes' ";
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
                            
                            
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>Tutors List(<?=$tot_record?>)</h3>
						<ul>
                                                    <li><a href="manage-tutor.php" ><span class="fa fa-plus-circle">
                                                            
                                                            </span></a></li>
							<li>
					<button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
							</li>

							<!-- <li>
					<button id="mark-active-id" type="submit" title="Mark Active" name="mark_active">
					<i class="fa fa-unlock" aria-hidden="true"></i></button>
							</li> -->
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
									
									
									<th>Date Created</th>
								</tr>
								<?php
                                                                if( mysql_num_rows($results) > 0 ) {
							while( $row = mysql_fetch_assoc($results) ) {
								$tutorId=$row['id'];

								///Newrow ID 
								$Tutor_newrow=mysql_fetch_assoc(mysql_query(" SELECT * FROM  newrow_x_tutors 
									WHERE tutor_intervene_id= '$tutorId' "));
											
								?>
										
                                                                
                                                                
                                                                <tr>
                                                                    
					<td>
		<input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>
											</td>						
                                                                                        
										<td>
                                                <a href="edit-tutor.php?tid=<?=$row['id']?>"><?php echo $row['email']; ?></a>
                                            
                                              <br/> 
                                              
                                  <a  class="btn btn-success btn-xs"
                                         href="edit-tutor.php?tid=<?=$row['id']?>">Edit</a> &nbsp;  &nbsp;
                                         <?php 
                                         $url_tutor='tutor-list.php?view='.$row['id'];
                                          $application_url='applicant_detail.php?tid='.$row['id'];

                                         ?>

                            
                  <a  class="btn btn-danger btn-xs"  target="_blank"
                                  href="<?=$url_tutor?>">View as Tutor</a> &nbsp;
                                  



                                         <?php 
                                         if($row['all_state_url']!='home.php')
                                         $view_url="applicant_detail.php?tid=".$row['id'];
                                         else $view_url='#';

                                         ?>

                                          <a class="btn btn-primary btn-xs"  style="display: none;">
                                             href="<?=$view_url?>">View Tutor application</a>


                                             
                                          
                                            </td>
                                            <td><?=$row['f_name']; ?> <br/>
                                           <?php if($row['status']==1){?>
                                         <a  class="btn btn-success btn-xs" href="edit-tutor.php?tid=<?=$row['id']?>">Active</a>
                                             <?php }elseif($row['status']==2){?>
                                          <a  class="btn btn-danger btn-xs" href="edit-tutor.php?tid=<?=$row['id']?>">Suspended</a>
                                          <a style="font-size: 13px;" href="edit-tutor.php?tid=<?=$row['id']?>" ><u>Mark Active</u></a>
                                           <?php }?> 
                                            
                                            </td>
                                            <td><?=$row['lname']; ?></td>
                                            <td>
                                              <?=$row['phone']; ?>
                                            </td>
                                            
                                            <td>
                                                <?php
                                                $datetime = strtotime($row['created_date']);
                                                if ($datetime == 0) {
                                                    echo "00h 00' 00/00/0000- ";
                                                } else {
                                                    echo date('H\h s\' - d/m/Y', $datetime);
                                                }
                                                ?>
                                                <a class="btn btn-success btn-xs" href="<?=$application_url?>">Applicant Details</a>
                                                <br/>

                                               
                                              <?php if(!empty($Tutor_newrow['newrow_ref_id'])){ ?>
                                                <span><strong class="text-primary">Tutor newrow ID:</strong>
                                             <?=($Tutor_newrow['newrow_ref_id'])?$Tutor_newrow['newrow_ref_id']:'NA'?> 
                                              </span>
                                          <?php }else{ ?>
                                          	 <a class="btn btn-danger btn-xs" 
                                                href="generate.newrow_id.php?uid=<?=$row['id']?>">Generate ID</a>
                                                <br/>

                                          <?php  }// ?>



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