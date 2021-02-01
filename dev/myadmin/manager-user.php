<?php
include("header.php");

$login_role = $_SESSION['login_role'];
if($login_role!=0 || !isGlobalAdmin()){
	header("location: index.php");
}

$error='';
$id = $_SESSION['login_id'];

//  view as Teacher
if(isset($_GET['view'])&&$_GET['view'] == 'teacher' && $_GET['id'] > 0) {
     $login_teacher = "SELECT * FROM users WHERE id = " . $_GET['id'];
    $teacher_data = mysql_fetch_assoc(mysql_query($login_teacher));

    $_SESSION['login_id'] = $teacher_data['id'];
    //$_SESSION['login_user'] = $teacher_data['user_name'];
   // $_SESSION['login_mail'] = $teacher_data['email'];
    $_SESSION['login_role'] = 1;
   // $_SESSION['login_status'] = 1;
    header("Location:folder.php");exit;
     
}



//  view as Teacher

if(isset($_POST['delete-user'])){
	$arr = $_POST['arr-user'];
	if($arr!=""){
		$query = mysql_query("DELETE FROM users WHERE id IN ('$arr')", $link);
	}
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
</script>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div class="table-responsive">
					<form id="search-users" method="GET" action="">
						<table class="table">
							<tr>
								<td><label>Search:</label></td>
								<td><input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo (isset($_GET['email'])) ? $_GET['email'] : ''; ?>" /></td>
								<td><input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?php echo (isset($_GET['first_name'])) ? $_GET['first_name'] : ''; ?>" /></td>
								<td><input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo (isset($_GET['last_name'])) ? $_GET['last_name'] : ''; ?>" /></td>
								<td>
									<select name="school" class="form-control">
										<option value="">School</option>
										<?php if( mysql_num_rows($schools) > 0 ) {
											while( $row = mysql_fetch_assoc($schools) ) {
												$select = (isset($_GET['school']) && $_GET['school'] == $row['SchoolId']) ? 'selected' : '';
												echo "<option value='{$row['SchoolId']}' {$select}>{$row['SchoolName']}</option>";
											}
										} ?>
									</select>
								</td>
								<td><input type="submit" name="action" class="btn" value="Search" /></td>
							</tr>
						</table>
					</form>
				</div>
				
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>Manager Users</h3>
						<ul>
							<li><a href="#" class="edit-user"><span class="glyphicon glyphicon-pencil"></span></a></li>
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
									<col width="55">
									<col width="120">
									<col width="150">
								</colgroup>
								<tr>
									<th>#</th>
									<th>Email</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>School</th>
									<th>Role</th>
									<th>Date Created</th>
								</tr>
								<?php
									/* Init query */
									$query = "SELECT u. * , p.`name` AS package, s.`SchoolName`
										FROM `users` u
										LEFT JOIN `packages` p ON u.`role` = p.`id`
										LEFT JOIN `schools` s ON s.`SchoolId` = u.`school`";
									
									/* Process Search */
									$where = "";
									if( isset($_GET['action']) ) {
										$email = (isset($_GET['email']) & $_GET['email'] != "") ? "u.`email` LIKE '%{$_GET['email']}%'" : "";
										$first_name = (isset($_GET['first_name']) & $_GET['first_name'] != "") ? "u.`first_name` LIKE '%{$_GET['first_name']}%'" : "";
										$last_name = (isset($_GET['last_name']) & $_GET['last_name'] != "") ? "u.`last_name` LIKE '%{$_GET['last_name']}%'" : "";
										$school = (isset($_GET['school']) & $_GET['school'] != "") ? "u.`school` = {$_GET['school']}" : "";
									}
									$where .= ($email != "") ? $email : "";
									$where .= ($first_name != "") ? (($where == "") ? $first_name : " AND " . $first_name ) : "";
									$where .= ($last_name != "") ? (($where == "") ? $last_name : " AND " . $last_name ) : "";
									$where .= ($school != "") ? (($where == "") ? $school : " AND " . $school ) : "";
									$query .= ($where != "") ? " WHERE " . $where : "";
									
									$results = mysql_query($query);
									
									if( mysql_num_rows($results) > 0 ) {
										while( $row = mysql_fetch_assoc($results) ) {
											
								?>
										<tr>
											<td>
												<input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>
											</td>
											<td>
												<a href="profile.php?id=<?php echo $row['id'];?>"><?php echo $row['email'];?></a>
											</td>
											<td><?php echo $row['first_name'];?></td>
											<td><?php echo $row['last_name'];?></td>
											<td>
											<?php if( $row['SchoolName'] != NULL ) : ?>
												<a href="manager-orders.php?id=<?php echo $row['school'];?>" target="_blank"><?php echo $row['SchoolName'];?></a>
											<?php else : ?>
												&nbsp;
											<?php endif; ?>
											</td>
											<td align="center">
											<?php echo ($row['role'] == 0) ? "Admin" : $row['package']; ?>
											</td>
											<td>
												<?php 
													$datetime = strtotime($row['date_registered']);
														if($datetime==0){
															echo "00h 00' 00/00/0000- ";
														}else{
															echo date('H\h s\' - d/m/Y',$datetime);
														}
												?>
                                                                                            <br/>
                                                                                            
                                                        <a href="manager-user.php?view=teacher&amp;id=<?=$row['id']?>" target="_blank" class="btn btn-success">View Teacher</a>
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