<!-- Sidebar -->
<?php
	require_once('inc/check-role.php');
	$role = checkRole();
?>
<?php $taxonomy = ( isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy']) && $_GET['taxonomy'] > 0 ) ? $_GET['taxonomy'] : 0; ?>

<?php  if($role>0): 
    $teacher_grade_res = mysql_query("
	SELECT  grade_level_id AS shared_terms
	FROM `techer_permissions`
	WHERE teacher_id = {$_SESSION['login_id']} AND grade_level_id > 0 
");
      ////////////////////////

        
     
        
        
   


        
///

 endif;

 // end//

$tutor_sq=" SELECT * FROM `inbox` WHERE `sender_id` > '0' AND is_read=0 ORDER BY `created_at` DESC ";
$sq_arr=array();

$res_tutor_sq=mysql_query($tutor_sq);
while ($row=mysql_fetch_assoc($res_tutor_sq)) {
$sq_arr[]=$row['sender_id'];
  }
  $sq_arr2=array_unique($sq_arr);
// print_r($sq_arr2);
/// Get pending . 

$sql_pending="SELECT i.sender_id,  count(i.sender_id) as tot_msg,t.id as tid,t.f_name,t.lname
FROM inbox i 
left join  gig_teachers t
ON i.sender_id=t.id
WHERE i.sender_id>0 AND i.is_read='0'
Group By i.sender_id
ORDER BY i.created_at DESC ";


// $sql_pending="SELECT i.sender_id,  count(i.sender_id) as tot_msg,t.id as tid,t.f_name,t.lname
// FROM inbox i 
// left join  gig_teachers t
// ON i.sender_id=t.id
// WHERE i.sender_id>0 AND i.is_read='0' AND i.sender_id IN()
// Group By i.sender_id
// ORDER BY i.created_at DESC ";


$res_msg_pending=mysql_query($sql_pending);
$pending_arr==array();
while ($row=mysql_fetch_assoc($res_msg_pending)) {
  # code...
  $pending_arr[$row['sender_id']]=$row;
}
 // $pending_arr

 //print_r($pending_arr);






?>









<?php  //if($role==0):?>
<div id="objective" class="widget clear fullwith">
	<?php include('widget/widget-teachers.php');?>
</div>		<!-- /#objective -->


<div id="objective" class="widget clear fullwith">
	<?php include('widget/widget-sessions-k.php');?>
</div>		<!-- /#objective -->
<?php  // endif;?>






<?php  //if($role==0 && isGlobalAdmin()):?>
<!-- <div id="manager_order" class="widget clear fullwith"> -->
	<?php //include('widget/widget-manager_order.php');?>
<!-- </div>	 -->	<!-- /#manager_user -->
<?php // endif;?>



	


<div id="objective" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-paragraph"></i>Test engine</h4>
<div class="widget-content">
	<p class="list"><i class="fa fa-th-list"></i><a href="https://tutorgigs.io/adm/index2.php">View</a></p>
    
    <!-- <p class="add_new">
		<i class="fa fa-plus-circle"></i>
		<a href="manage-tutor.php">+Add a Tutor</a>
	</p> -->
	
</div></div>

<div id="objective" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-paragraph"></i>Manage Applicant</h4>
<div class="widget-content">
	<p class="list"><i class="fa fa-th-list"></i><a href="applicant-list.php">List of applicant</a></p>
    
   
	
</div></div>

<div id="objective" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-paragraph"></i>Message</h4>
<div class="widget-content">
	<p class="list"><i class="fa fa-th-list"></i><a href="message.php">Message a Tutor</a></p>
	<p class="list"><i class="fa fa-th-list"></i><a href="broadcast-message.php">Broadcast a Message</a></p>
  <p class="list"><i class="fa fa-th-list"></i><a href="broadcast-message_old.php">Broadcast2</a></p>
  <?php 
      
 
 //print_r($pending_arr);
 foreach ($sq_arr2 as $tid) {
   # code...
  // $tname=$pending_arr[$tid]['f_name'].' '.$arr2['lname'];
  $tname=$pending_arr[$tid]['f_name'];
  $count_pending=(isset($pending_arr[$tid]['tot_msg']))?$pending_arr[$tid]['tot_msg']:0;
   // $arr2['tot_msg']

 
  ?>

  <p class="list"><i class="fa fa-user"></i><a href="message.php?tid=<?=$tid?>"><?=$tname?> (<?=$count_pending?>)</a></p>
    <?php }?>
   
	
</div></div>



<!-- <div id="objective" class="widget clear fullwith">
	<div class="dropdown">
		<button class="dropbtn"><i class="fa fa-paragraph"></i> Dropdown</button>
		<div class="dropdown-content">
			<a href="#">Link 1</a>
			<a href="#">Link 2</a>
			<a href="#">Link 3</a>
	  </div>
    
    </div>
</div> -->

<style>
.dropbtn {
  background-color: transparent;
  color: black;
  padding: 0px;
  font-size: 16px;
  border: none;
  width: 100%;
  display: inline-block;
  text-align:left;
  border-bottom: 1px solid #d5d5d7;
  padding-bottom: 9px;
}

.dropdown {
  position: relative;
  display: inline-block;
  font-weight: bold;
  width: 100%;
  margin-bottom:20px;
}

.dropdown-content {
  display: none;
  position: relative;
  background-color: transparent;
  min-width: 160px;
  top:0px;
  /*box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);*/
  z-index: 1;
}
#sidebar .dropbtn .fa {
    margin-right: 15px;
    color: #f0ad4e;
}
.dropdown-content a {
  color: black;
  padding: 5px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: transparent;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: transparent;}
</style>

<div id="profile" class="widget clear fullwith">
	<?php include('widget/widget-profile.php');?>
</div>		<!-- /#profile -->