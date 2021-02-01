<?php

@extract($_REQUEST) ;
include("header.php");

$error='';
$login_role = $_SESSION['login_role'];
////////////////////////
if(isset($_GET['msg'])){
  $_SESSION['msg_success']='newrow id created success!';

 }
 
    include('inc/sql_connect.php');
    $p2db = p2g();
    
    $test_sql = " SELECT * from tests WHERE Name != 'Training Test' AND IsActive = 1";
  $get_test_result = mysqli_query($p2db,$test_sql);
  
  
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
<link type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" />
<style>
    td.details-control {
    background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_close.png') no-repeat center center;
}

 tr { height: 50px }
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
                                $qq=" SELECT gig_teachers.*,tutor_profiles.approve_certificate,teacher_certificate,esl_certificate,billingual_certificate,teacher_approve, esl_approve, bilingual_approve  FROM gig_teachers left join tutor_profiles on (gig_teachers.id = tutor_profiles.tutorid) WHERE `all_state` = 'Yes' ";
                                if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                   if(!empty($email))
                                 $qq.=' AND email LIKE "%'.$email.'%"  ';
                                    if(!empty($first_name))
                                 $qq.=' AND f_name LIKE "%'.$first_name.'%"  ';
                                     if(!empty($last_name))
                                 $qq.=' AND lname LIKE "%'.$last_name.'%"  ';
                                if(!empty($created_by) && $created_by!='no')
                                 $qq.=' AND gig_teachers.created_by= "'.$created_by.'" ';
                         		}
                         		$qq.= ' order by gig_teachers.id desc ';
                                //=$qq
                                ?>




					<form id="search-users" method="GET" action="">
                                            
                                            
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title" >Filter List</h3>
                                                </div>
                                                  <div class="panel-body">

                                                      <div class="table-responsive" >


                                              <div class='col-md-3'>
                                               <div class="form-group">
                                                  <label for="exampleInputEmail1">Email</label>
                                                  <input name="email" class="form-control" placeholder="Email" value="<?=$_GET['email']?>" type="text">

                                                </div>
                                                      </div>
                                              <div class='col-md-3'>
                                                <div class="form-group">
                                                  <label for="exampleInputPassword1">First Name</label>
                                                  <input name="first_name" class="form-control" placeholder="First Name" value="<?=$_GET['first_name']?>" type="text">

                                                </div>
                                                      </div>
                                                  <div class='col-md-3'>
                                              <div class="form-group">
                                                  <label for="exampleInputPassword1">Last Name</label>
                                                   <input name="last_name" class="form-control" placeholder="Last Name" value="<?=$_GET['last_name']?>" type="text">

                                                </div>
                                                </div>

                                                          <div class='col-md-3'>
                                              <div class="form-group">
                                                  <label for="exampleInputPassword1">Type</label>
                                                   <select class="form-control" name="created_by">
                                                                                                                      <option value="no">Front Application</option>
                                                                                                                      <option value="2">Created-By Admin</option>
                                                                                                           </select>

                                                </div>
                                                </div>
                                                                                           <div class='col-md-12'>
                                                <button type="submit" class="btn btn-primary"  name="action" value="Search">Filter</button>
                                                </div>


                                                                              </div>

                                                  </div></div>
                                            
                                            
						
					</form>
				</div>
                            <?php 
                       
					//// $limit                                        
					$results = mysql_query($qq);
					$tot_record=mysql_num_rows($results);?>
                            
				<form id="form-manager" class="content_wrap" action="" method="post">
					
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="ct_heading clear" style="background-color:#f5f5f5;color:#000;padding:0px" >
						Tutors List(<?=$tot_record?>)
						
					</div>	
                                        </div>
    <div class="panel-body">
                                    
					<div class="clear">
						<?php
						if($error != '') {
							echo '<p class="error">'.$error.'</p>';
						} else { 
                                                     
                                                     
                                                     
                                                    ?>
                                            
                                            
							<div class="table-responsive">
                                                            <table id="example" class="display" cellspacing="0" width="100%" style="font-size:14px">
								
								<thead>
									<th></th>
									<th>Email</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th> Phone</th>
									<th> </th>
                                                            </thead>
								<?php
                                                                
								if( mysql_num_rows($results) > 0 ) {
									while( $row = mysql_fetch_assoc($results) ) {
										$tutorId=$row['id'];
while( $row3 = mysqli_fetch_assoc($get_test_result) ) { $test_name[] = array("name" => $row3['Name'], "id" => $row3['ID']);}
                                                        
										$Tutor_newrow=mysql_fetch_assoc(mysql_query(" SELECT * FROM  newrow_x_tutors 
									WHERE tutor_intervene_id= '$tutorId' "));
											
								?> 
                                                               <tr data-child-value="
                                                                   <h4>Certifications</h4>
            <table class='table' style='font-size:13px'>
           
             <tr class='warning'>
                   <td>Teacher Certification</td>
                   <td>
                    <?php  if($row['teacher_certificate']) { ?>
                         <a href='../teacher_certificate/<?php echo $row['teacher_certificate'];?>' target='_blank' style='color:green'><?php echo $row['teacher_certificate'];?></a> 
                         &nbsp;&nbsp; <?php if($row['teacher_approve'] == 1) {?> <span class='label label-success'>Approved</span> <?php } else { ?> <span class='label label-danger'>Disapproved</span> <?php } ?>
                    <?php } else { echo '-';} ?> 
                         </td>
                </tr>
        
               
             <tr class='warning'>
                   <td>ESL Certification</td>
                   <td>
                    <?php  if($row['esl_certificate']) { ?>
                         <a href='../teacher_certificate/<?php echo $row['esl_certificate'];?>' target='_blank' style='color:green'><?php echo $row['esl_certificate'];?></a> 
                         &nbsp;&nbsp; <?php if($row['esl_approve'] == 1) {?> <span class='label label-success'>Approved</span> <?php } else { ?> <span class='label label-danger'>Disapproved</span> <?php } ?>
                   <?php } else { echo '-';} ?> 
                         </td>
                </tr>
         
               
             <tr class='warning'>
                   <td>Bilingual Certification</td>
                   <td>
                    <?php  if($row['billingual_certificate']) { ?>
                         <a href='../teacher_certificate/<?php echo $row['billingual_certificate'];?>' target='_blank' style='color:green'><?php echo $row['billingual_certificate'];?></a> 
                         &nbsp;&nbsp; <?php if($row['bilingual_approve'] == 1) {?> <span class='label label-success'>Approved</span> <?php } else { ?> <span class='label label-danger'>Disapproved</span> <?php } ?>
                     <?php } else { echo '-';} ?> 
                         </td>
                </tr>
                 </table>
                 <h4>Exam Tests</h4>
               <table class='table' style='font-size:13px'>
                 <?php 
             foreach($test_name as $test) { 
                 $test_sql_2 = " SELECT * FROM tutor_tests_logs WHERE tutor_id='".$row['id']."' AND status='completed'  AND quiz_test_id = '".$test['id']."'";
                 $get_test_result_2 = mysqli_query($p2db,$test_sql_2);
                 $get_test_result_2_data = mysqli_fetch_assoc($get_test_result_2);
                 $is_exist = mysqli_num_rows($get_test_result_2);
             ?>
                        <tr class='warning'>
                   <td style='width:36%'><?php echo $test['name'];?></td>
                   <td>
                    <?php if($get_test_result_2_data['pass_status']) { ?>
                                                               <label style='color:green'>
									Status - <?php echo $get_test_result_2_data['pass_status'];?>
								</p>
                                                                <?php } ?>
                                                                <p style='color:green'>
                                                                <?php if($get_test_result_2_data['per_scored']) { ?>
                                                                 Score - <?php echo $get_test_result_2_data['per_scored'];?>% 
                                                                    <?php } else echo '-'; ?>
                                                                 </p>
                   </td>
                </tr>
                <?php  } ?>
               <tr class='warning'>
                   <td><a class='btn btn-success btn-sm' href='manage_certificate.php?tid=<?=$row['id']?>'>Manage Certification</a></td><td></td>
                </tr>
              
           </table>">
                                                                
                                                                    
				<td class="details-control"></td>						
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
                                                <?php 
                                                $show_warning = 0;
                                                if(!empty($row['teacher_certificate']) && is_null($row['teacher_approve']))
                                                {
                                                    $show_warning = 1;
                                                }
                                                else if(!empty($row['esl_certificate']) && is_null($row['esl_approve']))
                                                {
                                                    $show_warning = 1;
                                                }
                                                else if(!empty($row['billingual_certificate']) && is_null($row['bilingual_approve']))
                                                {
                                                    $show_warning = 1;
                                                   
                                                }
                                                
                                                if($show_warning)
                                                    echo '   &nbsp;<i  title="Cerfitifaction Uploaded" class="glyphicon glyphicon-warning-sign" style="color:red"></i>';
                                                ?>
                                            </td>
                                          

                                                                               
										</tr>
								<?php 
										}
									} else {
										echo '<div class="clear"><p>There is no item found!</p></div>';
									}
								?>
							</table></div>
						<?php } ?>
                                            </div></div>
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
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script>
   function format(value) {
      return '<div>' + value + '</div>';
  }
  $(document).ready(function () {
      var table= $('#example').DataTable({
              "lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
              "pageLength": 25,
              'columnDefs': [{
                    "targets": [0],
                    "orderable": false
                },
            ]
          });  

      // Add event listener for opening and closing details
      $('#example').on('click', 'td.details-control', function () {
          var tr = $(this).closest('tr');
          var row = table.row(tr);

          if (row.child.isShown()) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          } else {
              // Open this row
              row.child(format(tr.data('child-value'))).show();
              tr.addClass('shown');
          }
      });
  });
</script>
