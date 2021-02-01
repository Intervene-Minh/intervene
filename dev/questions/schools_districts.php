<?php
include("header.php");

if( !isset($_SESSION['schools_id']) ) {
	header("Location: login_principal.php");
	exit();
}

/* Process logout */
if( isset($_POST['logout']) ) {
	unset($_SESSION['schools_id']);
	header("Location: login_principal.php");
	exit();
}

/* Process share folders */
if( isset($_POST['share']) ) {
	$userId = $_POST['share'];
	if( isset($_POST['folders'][$userId]) ) {
		// Delete existed data
		mysql_query("DELETE FROM `shared` WHERE `schoolId` = {$_SESSION['schools_id']} AND `userId` = {$userId}");
		
		// Insert new data
		foreach( $_POST['folders'][$userId] as $item ) {
			mysql_query("INSERT INTO `shared` (`schoolId`, `userId`, `termId`) VALUES ({$_SESSION['schools_id']}, {$userId}, {$item})");
		}
	} else {
		echo "<script type='text/javascript'>alert('Please choose your registered folder(s) to share!');</script>";
	}
}

/* Process revoke */
if( isset($_POST['revoke']) ) {
	$userId = $_POST['revoke'];
	mysql_query("DELETE FROM `shared` WHERE `schoolId` = {$_SESSION['schools_id']} AND `userId` = {$userId}");
}

$users = mysql_query("
	SELECT `users`. * , GROUP_CONCAT( `shared`.`termId` SEPARATOR ',' ) AS shared_terms
	FROM `users`
	LEFT JOIN `shared` ON `users`.`id` = `shared`.`userId`
	WHERE `users`.`school` = {$_SESSION['schools_id']}
	GROUP BY `users`.`id` 
");
$school = mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE `SchoolId` = {$_SESSION['schools_id']}"));
$folders = mysql_query("
	SELECT *
	FROM `terms`
	WHERE `id`
	IN (
		SELECT `termId`
		FROM `purchasemeta`
		WHERE `purchaseId` = (
			SELECT `id`
			FROM `purchases`
			WHERE `schoolID` = {$_SESSION['schools_id']}
		)
	)
	ORDER BY `name` ASC
");
$registered_folders = array();
if(mysql_num_rows($folders) > 0) {
	while($folder = mysql_fetch_assoc($folders)) {
		$registered_folders[$folder['id']] = $folder['name'];
	}
}
?>

<div class="container">
	<div style="border: 1px solid black" class="row text-center">
		<h3>Instructions to Get Started</h3>
		<button type="button" class="btn btn-primary btn-plan-select" data-toggle="modal" data-target="#myModal">Send an invitation to your teachers</button>
		<h4>Don't forget to come back to this page to share access to your teachers once they've signed up!</h4>
					
			<!-- Modal -->
			<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">

			 <!-- Modal content-->
					 <div class="modal-content">
						 <div class="modal-header">
						 <button type="button" class="close" data-dismiss="modal">&times;</button>
						 <h4 class="modal-title">Invitations</h4>
						 </div>
							 <div class="modal-body">
								<section id ="contact">
									<div class="container-fluid">
										<div class="row text-center">
											<div class="header-section text-center">
												<h2>Invite Your Teachers</h2>
												<p>You can either copy and paste the URL below and send your own invite<br>or just insert your teachers' emails and we'll send it!<br>Here's the link your teachers need: <a style="color:blue">http://www.intervene.io/questions/signup.php</a></p>
												<hr class="bottom-line">
											</div>
				

										<form method="post" action="email-invite.php">

												<input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br> 
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br>
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br>
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br>
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br>
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br> 
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br>
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br>
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br>
												 <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address"><br>
												 <br>	
			 
												 <input name="sendername" type="text" size="30" placeholder="Your Name"><br>
												 
												 <input type="submit" name="invite" value="Invite">
										</form>
										</div>
									</div>
								</section>
	<!--/ Invite-->			</div>
					
								 <div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								 </div>
						 </div>
					</div>
			</div>
	</div>
<HR WIDTH="60%"></HR>
		<h3>Give Access</h3>
		
		<table class="table table-bordered">
			<tr>
				<td><label>School:</label> <?php echo $school['SchoolName']; ?></td>
				<td><label>Email:</label> <?php echo $school['SchoolMail']; ?></td>
				<td><label>Address:</label> <?php echo $school['SchoolAddress']; ?></td>
				<td><label>Registered Date:</label> <?php echo date('M d, Y', strtotime($school['created_at'])); ?></td>
				<td><label>Expiry Date:</label> <?php echo date('M d, Y', strtotime('+1 year', strtotime($school['created_at']))); ?></td>
				<td><form name="logout" method="POST" action=""><button type="submit" name="logout" class="btn btn-danger">Logout</button></form></td>
			</tr>
		</table>
		
		<form name="principal_action" id="principal_action" method="POST" action="">
		<table class="table table-hover">
			<tr>
				<th>STT</th>
				<th>Teacher</th>
				<th>Email</th>
				<th>Registered Folder</th>
				<th>Action</th>
			</tr>
			<?php
			if( mysql_num_rows($users) > 0 ) {
				$i = 1;
				while($user = mysql_fetch_assoc($users)) {
					$shared_terms = ($user['shared_terms'] != NULL) ? explode(',', $user['shared_terms']) : array();
					echo "<tr>";
					echo "<td>$i</td>";
					echo "<td>{$user['first_name']} {$user['last_name']}</td>";
					echo "<td>{$user['email']}</td>";
					echo "<td>";
					if( count($registered_folders) > 0 ) {
						echo "<div class='row'>";
						foreach($registered_folders as $key => $val) {
							$checked = in_array($key, $shared_terms) ? "checked" : "";
							echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
								<label><input type='checkbox' class='folders' name='folders[{$user['id']}][]' value='{$key}' {$checked} /> {$val}</label>
							</div>";
						}
						echo "</div>";
					} else {
						echo "&nbsp;";
					}
					echo "</td>";
					echo "<td>
						<button type='submit' name='share' value='{$user['id']}' class='btn btn-primary'>Share</button>
						<button type='submit' name='revoke' value='{$user['id']}' class='btn btn-danger'>Revoke</button>
					</td>";
					echo "</tr>";
					$i++;
				}
			} else {
				echo "<tr><td colspan='5'>No item found!</td></tr>";
			}
			?>
		</table>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('button[name=share]').on('click', function(){
			var checked = false;
			$(this).closest('tr').find('input.folders').each(function(){
				if( $(this).is(':checked') ) {
					checked = true;
					return false;
				}
			});
			if( checked ) {
				return true;
			}
			else {
				alert('Please choose your registered folder(s) to share!');
				return false;
			}
		});
		
		$('button[name=revoke]').on('click', function(){
			return confirm('Are you sure you want to revoke this shared folder?') ? true : false;
		});
	});
</script>

<?php include("footer.php"); ?>